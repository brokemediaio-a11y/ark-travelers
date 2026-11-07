<?php
/**
 * Flight finder – server side (Duffel API).
 *
 * The browser never talks to Duffel. It calls the two AJAX actions below, PHP
 * calls Duffel with the secret token, removes every price from the response and
 * returns itineraries only. Prices are sent to the sales team by email instead.
 *
 * Configuration (wp-config.php, never in the theme):
 *   define( 'ARK_DUFFEL_TOKEN', 'duffel_live_…' );          // 'duffel_test_…' on staging
 *   define( 'ARK_FLIGHTS_NOTIFY_EMAIL', 'sales@example.com' ); // optional, defaults to the admin email
 *
 * @package ARK_Travelers
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

const ARK_FLIGHTS_API_BASE = 'https://api.duffel.com';

/**
 * Duffel access token from wp-config.php or the server environment.
 *
 * @return string Empty string when not configured.
 */
function ark_flights_token() {
    if ( defined( 'ARK_DUFFEL_TOKEN' ) && is_string( ARK_DUFFEL_TOKEN ) ) {
        return trim( ARK_DUFFEL_TOKEN );
    }
    $env = getenv( 'ARK_DUFFEL_TOKEN' );
    return is_string( $env ) ? trim( $env ) : '';
}

/**
 * Where lead emails go.
 *
 * @return string
 */
function ark_flights_notify_email() {
    if ( defined( 'ARK_FLIGHTS_NOTIFY_EMAIL' ) && is_email( ARK_FLIGHTS_NOTIFY_EMAIL ) ) {
        return ARK_FLIGHTS_NOTIFY_EMAIL;
    }
    return get_option( 'admin_email' );
}

/**
 * Contact details shown on "Contact for price & booking" buttons.
 * Keep in sync with footer.php.
 *
 * @return array{whatsapp:string,tel:string,display:string}
 */
function ark_flights_contact() {
    return array(
        'whatsapp' => '46700101401',
        'tel'      => '+46700101401',
        'display'  => '+46 700 101 401',
    );
}

/**
 * Allow at most $limit calls per visitor IP in the time window.
 *
 * @param string $bucket Counter name.
 * @param int    $limit  Max calls.
 * @param int    $window Seconds.
 * @return bool True when the visitor is over the limit.
 */
function ark_flights_rate_limited( $bucket, $limit, $window = 600 ) {
    $ip    = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : 'unknown';
    $key   = 'ark_fly_rl_' . $bucket . '_' . md5( $ip );
    $count = (int) get_transient( $key );
    if ( $count >= $limit ) {
        return true;
    }
    set_transient( $key, $count + 1, $window );
    return false;
}

/**
 * Call the Duffel REST API.
 *
 * @param string     $method GET or POST.
 * @param string     $path   Path with query string.
 * @param array|null $body   JSON body (wrapped in {"data": …}).
 * @param int        $timeout Seconds.
 * @return array|WP_Error Decoded "data" member, or an error whose code is
 *                        not_configured | rate_limited | invalid_request | timeout | provider_unavailable.
 */
function ark_flights_duffel_request( $method, $path, $body = null, $timeout = 30 ) {
    $token = ark_flights_token();
    if ( '' === $token ) {
        return new WP_Error( 'not_configured', 'ARK_DUFFEL_TOKEN is not set.' );
    }

    $args = array(
        'method'  => $method,
        'timeout' => $timeout,
        'headers' => array(
            'Authorization'   => 'Bearer ' . $token,
            'Duffel-Version'  => 'v2',
            'Accept'          => 'application/json',
            'Content-Type'    => 'application/json',
            'Accept-Encoding' => 'gzip',
        ),
    );
    if ( null !== $body ) {
        $args['body'] = wp_json_encode( array( 'data' => $body ) );
    }

    $response = wp_remote_request( ARK_FLIGHTS_API_BASE . $path, $args );
    if ( is_wp_error( $response ) ) {
        $msg = $response->get_error_message();
        error_log( '[ark-flights] request failed: ' . $msg );
        return new WP_Error( false !== stripos( $msg, 'timed out' ) ? 'timeout' : 'provider_unavailable', $msg );
    }

    $status = (int) wp_remote_retrieve_response_code( $response );
    $json   = json_decode( wp_remote_retrieve_body( $response ), true );

    if ( $status >= 200 && $status < 300 && is_array( $json ) && isset( $json['data'] ) ) {
        return $json['data'];
    }

    $first = isset( $json['errors'][0] ) && is_array( $json['errors'][0] ) ? $json['errors'][0] : array();
    $code  = isset( $first['code'] ) ? (string) $first['code'] : 'unknown';
    error_log( sprintf( '[ark-flights] %s %s -> HTTP %d (%s)', $method, strtok( $path, '?' ), $status, $code ) );

    if ( 401 === $status || 403 === $status ) {
        return new WP_Error( 'not_configured', 'Duffel rejected the access token.' );
    }
    if ( 429 === $status ) {
        return new WP_Error( 'rate_limited', 'Duffel rate limit.' );
    }
    if ( 400 === $status || 422 === $status ) {
        return new WP_Error( 'invalid_request', isset( $first['message'] ) ? (string) $first['message'] : 'Invalid search.' );
    }
    return new WP_Error( 'provider_unavailable', 'Duffel returned HTTP ' . $status );
}

/**
 * Visitor-facing text for an error code.
 *
 * @param string $code Error code.
 * @return string
 */
function ark_flights_error_message( $code ) {
    switch ( $code ) {
        case 'invalid_request':
            return __( 'We couldn\'t search those details. Please check the airports and dates.', 'ark-travelers' );
        case 'rate_limited':
            return __( 'Too many searches right now. Please wait a minute and try again.', 'ark-travelers' );
        case 'timeout':
            return __( 'The airlines took too long to answer. Please try again.', 'ark-travelers' );
        case 'bad_nonce':
            return __( 'Your session has expired. Please refresh the page and try again.', 'ark-travelers' );
        default:
            return __( 'We couldn\'t reach the airline systems. Please try again, or contact us and we\'ll search for you.', 'ark-travelers' );
    }
}

/**
 * ISO 8601 duration ("PT7H10M", "P1DT2H") to minutes.
 *
 * @param mixed $iso Duration.
 * @return int|null
 */
function ark_flights_duration_minutes( $iso ) {
    if ( ! is_string( $iso ) || ! preg_match( '/^P(?:(\d+)D)?(?:T(?:(\d+)H)?(?:(\d+)M)?)?$/', $iso, $m ) ) {
        return null;
    }
    return ( (int) ( isset( $m[1] ) ? $m[1] : 0 ) * 1440 ) + ( (int) ( isset( $m[2] ) ? $m[2] : 0 ) * 60 ) + (int) ( isset( $m[3] ) ? $m[3] : 0 );
}

/**
 * 430 minutes -> "7h 10m".
 *
 * @param int|null $minutes Minutes.
 * @return string
 */
function ark_flights_format_duration( $minutes ) {
    if ( null === $minutes ) {
        return '';
    }
    return sprintf( '%dh %02dm', intdiv( $minutes, 60 ), $minutes % 60 );
}

/**
 * Whole days between two YYYY-MM-DD dates (arrival day offset, e.g. "+1").
 *
 * @param string $from Date.
 * @param string $to   Date.
 * @return int
 */
function ark_flights_day_diff( $from, $to ) {
    try {
        $a = new DateTimeImmutable( $from, new DateTimeZone( 'UTC' ) );
        $b = new DateTimeImmutable( $to, new DateTimeZone( 'UTC' ) );
    } catch ( Exception $e ) {
        return 0;
    }
    return (int) $a->diff( $b )->format( '%r%a' );
}

/**
 * Place array from Duffel -> small safe array.
 *
 * @param mixed $place Raw place.
 * @return array{iata:string,city:string,name:string}
 */
function ark_flights_place( $place ) {
    $place = is_array( $place ) ? $place : array();
    $iata  = isset( $place['iata_code'] ) ? (string) $place['iata_code'] : '';
    $name  = isset( $place['name'] ) ? (string) $place['name'] : $iata;
    return array(
        'iata' => $iata,
        'city' => isset( $place['city_name'] ) && $place['city_name'] ? (string) $place['city_name'] : $name,
        'name' => $name,
    );
}

/**
 * Turn Duffel offers into de-duplicated itineraries WITHOUT prices.
 *
 * Duffel returns one offer per fare type for the same flights, so offers are
 * grouped by their flight numbers and departure times: one group = one option.
 * Options are ordered cheapest first, but the price itself is only returned in
 * the "internal" member, which is for the sales email and never sent to the browser.
 *
 * @param array $offers Duffel offers.
 * @param int   $limit  Max options to return for display.
 * @return array{total:int,options:array,internal:array}
 */
function ark_flights_normalize_offers( array $offers, $limit ) {
    $groups = array();

    foreach ( $offers as $offer ) {
        if ( ! is_array( $offer ) || empty( $offer['slices'] ) || ! is_array( $offer['slices'] ) ) {
            continue;
        }

        $slices = array();
        $key    = array();

        foreach ( $offer['slices'] as $slice ) {
            $segments = isset( $slice['segments'] ) && is_array( $slice['segments'] ) ? array_values( $slice['segments'] ) : array();
            if ( ! $segments ) {
                continue 2;
            }
            $first = $segments[0];
            $last  = $segments[ count( $segments ) - 1 ];

            $dep_raw = isset( $first['departing_at'] ) ? (string) $first['departing_at'] : '';
            $arr_raw = isset( $last['arriving_at'] ) ? (string) $last['arriving_at'] : '';
            if ( strlen( $dep_raw ) < 16 || strlen( $arr_raw ) < 16 ) {
                continue 2;
            }

            $flights = array();
            $via     = array();
            foreach ( $segments as $i => $seg ) {
                $carrier   = isset( $seg['marketing_carrier']['iata_code'] ) ? (string) $seg['marketing_carrier']['iata_code'] : '';
                $number    = isset( $seg['marketing_carrier_flight_number'] ) ? (string) $seg['marketing_carrier_flight_number'] : '';
                $flights[] = trim( $carrier . ' ' . $number );
                $key[]     = $carrier . $number . '@' . ( isset( $seg['departing_at'] ) ? (string) $seg['departing_at'] : '' );
                if ( $i > 0 && isset( $seg['origin']['iata_code'] ) ) {
                    $via[] = (string) $seg['origin']['iata_code'];
                }
            }

            $minutes = ark_flights_duration_minutes( isset( $slice['duration'] ) ? $slice['duration'] : null );

            $slices[] = array(
                'from'         => ark_flights_place( isset( $first['origin'] ) ? $first['origin'] : null ),
                'to'           => ark_flights_place( isset( $last['destination'] ) ? $last['destination'] : null ),
                'date'         => substr( $dep_raw, 0, 10 ),
                'dep'          => substr( $dep_raw, 11, 5 ),
                'arr'          => substr( $arr_raw, 11, 5 ),
                'arrDayOffset' => ark_flights_day_diff( substr( $dep_raw, 0, 10 ), substr( $arr_raw, 0, 10 ) ),
                'duration'     => ark_flights_format_duration( $minutes ),
                'stops'        => count( $segments ) - 1,
                'via'          => $via,
                'flights'      => $flights,
            );
            $key[] = '/';
        }

        if ( ! $slices ) {
            continue;
        }

        $group_key = implode( '|', $key );
        $price     = isset( $offer['total_amount'] ) ? (float) $offer['total_amount'] : INF;

        // Keep the cheapest offer of each group as the representative one.
        if ( isset( $groups[ $group_key ] ) && $groups[ $group_key ]['price'] <= $price ) {
            continue;
        }

        $seg0 = $offer['slices'][0]['segments'][0];
        $pax0 = isset( $seg0['passengers'][0] ) && is_array( $seg0['passengers'][0] ) ? $seg0['passengers'][0] : array();

        $bags = null;
        if ( isset( $pax0['baggages'] ) && is_array( $pax0['baggages'] ) ) {
            $bags = array(
                'checked' => 0,
                'carryOn' => 0,
            );
            foreach ( $pax0['baggages'] as $bag ) {
                $qty = isset( $bag['quantity'] ) ? (int) $bag['quantity'] : 0;
                if ( isset( $bag['type'] ) && 'checked' === $bag['type'] ) {
                    $bags['checked'] += $qty;
                } elseif ( isset( $bag['type'] ) && 'carry_on' === $bag['type'] ) {
                    $bags['carryOn'] += $qty;
                }
            }
        }

        $logo = isset( $offer['owner']['logo_symbol_url'] ) ? (string) $offer['owner']['logo_symbol_url'] : '';

        $groups[ $group_key ] = array(
            'price'    => $price,
            'currency' => isset( $offer['total_currency'] ) ? (string) $offer['total_currency'] : '',
            'option'   => array(
                'airline' => isset( $offer['owner']['name'] ) ? (string) $offer['owner']['name'] : '',
                'logo'    => 0 === strpos( $logo, 'https://' ) ? $logo : '',
                'cabin'   => isset( $pax0['cabin_class_marketing_name'] ) && $pax0['cabin_class_marketing_name']
                    ? (string) $pax0['cabin_class_marketing_name']
                    : ( isset( $pax0['cabin_class'] ) ? ucfirst( str_replace( '_', ' ', (string) $pax0['cabin_class'] ) ) : '' ),
                'bags'    => $bags,
                'slices'  => $slices,
            ),
        );
    }

    uasort(
        $groups,
        static function ( $a, $b ) {
            return $a['price'] <=> $b['price'];
        }
    );

    $options  = array();
    $internal = array();
    foreach ( $groups as $group ) {
        if ( count( $options ) < $limit ) {
            $options[] = $group['option'];
        }
        if ( count( $internal ) < 3 ) {
            $internal[] = array(
                'airline'  => $group['option']['airline'],
                'price'    => $group['price'],
                'currency' => $group['currency'],
                'summary'  => ark_flights_summarise_option( $group['option'] ),
            );
        }
    }

    return array(
        'total'    => count( $groups ),
        'options'  => $options,
        'internal' => $internal,
    );
}

/**
 * One-line description of an option for the sales email.
 *
 * @param array $option Option.
 * @return string
 */
function ark_flights_summarise_option( array $option ) {
    $parts = array();
    foreach ( $option['slices'] as $s ) {
        $parts[] = sprintf(
            '%s %s-%s %s-%s %s (%s, %s)',
            $s['date'],
            $s['from']['iata'],
            $s['to']['iata'],
            $s['dep'],
            $s['arr'],
            implode( ' ', $s['flights'] ),
            $s['duration'],
            $s['stops'] ? $s['stops'] . ' stop' . ( $s['stops'] > 1 ? 's' : '' ) : 'direct'
        );
    }
    return $option['airline'] . ': ' . implode( ' | ', $parts );
}

/**
 * Read and validate the search form. Returns an array or a WP_Error whose
 * message is safe to show to the visitor.
 *
 * @return array|WP_Error
 */
function ark_flights_read_request() {
    // phpcs:disable WordPress.Security.NonceVerification.Missing -- nonce is checked by the caller.
    $field = static function ( $name ) {
        return isset( $_POST[ $name ] ) ? sanitize_text_field( wp_unslash( $_POST[ $name ] ) ) : '';
    };

    $trip     = 'oneway' === $field( 'trip' ) ? 'oneway' : 'roundtrip';
    $origin   = strtoupper( $field( 'origin_iata' ) );
    $dest     = strtoupper( $field( 'destination_iata' ) );
    $depart   = $field( 'depart' );
    $return   = $field( 'return' );
    $cabin    = $field( 'class' );
    $adults   = (int) $field( 'adults' );
    $children = (int) $field( 'children' );
    $infants  = (int) $field( 'infants' );

    $name  = $field( 'contact_name' );
    $email = isset( $_POST['contact_email'] ) ? sanitize_email( wp_unslash( $_POST['contact_email'] ) ) : '';
    $cc    = $field( 'contact_country_code' );
    $phone = $field( 'contact_phone' );
    // phpcs:enable

    if ( ! preg_match( '/^[A-Z]{3}$/', $origin ) || ! preg_match( '/^[A-Z]{3}$/', $dest ) ) {
        return new WP_Error( 'invalid_request', __( 'Please choose the departure and arrival airports from the list.', 'ark-travelers' ) );
    }
    if ( $origin === $dest ) {
        return new WP_Error( 'invalid_request', __( 'Departure and arrival must be different.', 'ark-travelers' ) );
    }

    $date_ok = static function ( $d ) {
        return (bool) preg_match( '/^\d{4}-\d{2}-\d{2}$/', $d ) && false !== strtotime( $d );
    };
    $today = wp_date( 'Y-m-d' );
    if ( ! $date_ok( $depart ) || $depart < $today ) {
        return new WP_Error( 'invalid_request', __( 'Please choose a departure date that is today or later.', 'ark-travelers' ) );
    }
    if ( 'roundtrip' === $trip && ( ! $date_ok( $return ) || $return < $depart ) ) {
        return new WP_Error( 'invalid_request', __( 'Please choose a return date on or after the departure date.', 'ark-travelers' ) );
    }

    $cabins = array(
        'economy'  => 'economy',
        'premium'  => 'premium_economy',
        'business' => 'business',
        'first'    => 'first',
    );
    if ( ! isset( $cabins[ $cabin ] ) ) {
        $cabin = 'economy';
    }

    $adults   = max( 1, min( 9, $adults ) );
    $children = max( 0, min( 9, $children ) );
    $infants  = max( 0, min( 4, $infants ) );
    if ( $adults + $children + $infants > 9 ) {
        return new WP_Error( 'invalid_request', __( 'We can search for up to 9 travellers at a time.', 'ark-travelers' ) );
    }
    if ( $infants > $adults ) {
        return new WP_Error( 'invalid_request', __( 'Each infant must travel with an adult.', 'ark-travelers' ) );
    }

    if ( '' === $name || ! is_email( $email ) || '' === $phone ) {
        return new WP_Error( 'invalid_request', __( 'Please fill in your name, email and phone so we can send you the prices.', 'ark-travelers' ) );
    }
    if ( ! preg_match( '/^\+\d{1,4}$/', $cc ) ) {
        $cc = '';
    }

    return array(
        'trip'     => $trip,
        'origin'   => $origin,
        'dest'     => $dest,
        'depart'   => $depart,
        'return'   => 'roundtrip' === $trip ? $return : '',
        'cabin'    => $cabins[ $cabin ],
        'adults'   => $adults,
        'children' => $children,
        'infants'  => $infants,
        'name'     => $name,
        'email'    => $email,
        'phone'    => trim( $cc . ' ' . $phone ),
    );
}

/**
 * Duffel offer request body for a validated search.
 *
 * @param array $s Search from ark_flights_read_request().
 * @return array
 */
function ark_flights_offer_request_body( array $s ) {
    $slices = array(
        array(
            'origin'         => $s['origin'],
            'destination'    => $s['dest'],
            'departure_date' => $s['depart'],
        ),
    );
    if ( 'roundtrip' === $s['trip'] ) {
        $slices[] = array(
            'origin'         => $s['dest'],
            'destination'    => $s['origin'],
            'departure_date' => $s['return'],
        );
    }

    $passengers = array();
    for ( $i = 0; $i < $s['adults']; $i++ ) {
        $passengers[] = array( 'type' => 'adult' );
    }
    // The form doesn't ask for ages; Duffel prices children and infants by age.
    for ( $i = 0; $i < $s['children']; $i++ ) {
        $passengers[] = array( 'age' => 10 );
    }
    for ( $i = 0; $i < $s['infants']; $i++ ) {
        $passengers[] = array( 'age' => 1 );
    }

    return array(
        'slices'      => $slices,
        'passengers'  => $passengers,
        'cabin_class' => $s['cabin'],
    );
}

/**
 * Email the sales team the enquiry, including the real prices.
 *
 * @param array         $s      Search.
 * @param array|null    $result Normalised result, or null when the search failed.
 * @param string        $status Short status for the subject line.
 */
function ark_flights_send_lead( array $s, $result, $status ) {
    $clean = static function ( $v ) {
        return trim( preg_replace( '/[\r\n]+/', ' ', (string) $v ) );
    };

    $subject = sprintf(
        'Flight search: %s-%s %s%s (%s) [%s]',
        $s['origin'],
        $s['dest'],
        $s['depart'],
        $s['return'] ? ' / ' . $s['return'] : '',
        $clean( $s['name'] ),
        $status
    );

    $lines = array(
        'A visitor searched for flights on the website. Prices are shown to you only.',
        '',
        'Name:     ' . $clean( $s['name'] ),
        'Email:    ' . $s['email'],
        'Phone:    ' . $clean( $s['phone'] ),
        '',
        'Route:    ' . $s['origin'] . ' -> ' . $s['dest'] . ( 'roundtrip' === $s['trip'] ? ' (round trip)' : ' (one way)' ),
        'Depart:   ' . $s['depart'],
        'Return:   ' . ( $s['return'] ? $s['return'] : '-' ),
        'Cabin:    ' . $s['cabin'],
        'Travellers: ' . $s['adults'] . ' adult(s), ' . $s['children'] . ' child(ren), ' . $s['infants'] . ' infant(s)',
        '',
    );

    if ( $result && ! empty( $result['internal'] ) ) {
        $lines[] = 'Options found: ' . $result['total'] . '. Cheapest:';
        foreach ( $result['internal'] as $i => $row ) {
            $lines[] = sprintf( '%d) %s %s - %s', $i + 1, number_format( $row['price'], 2, '.', '' ), $row['currency'], $row['summary'] );
        }
    } else {
        $lines[] = 'No options were shown to the visitor (' . $status . '). Please contact them.';
    }

    $headers = array( 'Reply-To: ' . $clean( str_replace( array( '<', '>', '"' ), '', $s['name'] ) ) . ' <' . $s['email'] . '>' );

    wp_mail( ark_flights_notify_email(), $subject, implode( "\n", $lines ), $headers );
}

/**
 * AJAX: search flights. Returns itineraries without prices.
 */
function ark_flight_search() {
    if ( ! check_ajax_referer( 'ark_nonce', 'nonce', false ) ) {
        wp_send_json_error(
            array(
                'code'    => 'bad_nonce',
                'message' => ark_flights_error_message( 'bad_nonce' ),
            ),
            403
        );
    }

    // Honeypot: bots fill every field. Pretend nothing was found.
    if ( ! empty( $_POST['website'] ) ) {
        wp_send_json_success(
            array(
                'totalOptions' => 0,
                'options'      => array(),
            )
        );
    }

    $search = ark_flights_read_request();
    if ( is_wp_error( $search ) ) {
        wp_send_json_error(
            array(
                'code'    => 'invalid_request',
                'message' => $search->get_error_message(),
            ),
            400
        );
    }

    if ( ark_flights_rate_limited( 'search', (int) apply_filters( 'ark_flights_search_limit', 10 ) ) ) {
        wp_send_json_error(
            array(
                'code'    => 'rate_limited',
                'message' => ark_flights_error_message( 'rate_limited' ),
            ),
            429
        );
    }

    // Identical searches within ten minutes reuse the same Duffel answer.
    $cache_key = 'ark_fly_s_' . md5( wp_json_encode( ark_flights_offer_request_body( $search ) ) . ark_flights_token() );
    $result    = get_transient( $cache_key );

    if ( false === $result ) {
        if ( function_exists( 'set_time_limit' ) ) {
            @set_time_limit( 60 ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
        }

        $data = ark_flights_duffel_request(
            'POST',
            '/air/offer_requests?return_offers=true&supplier_timeout=15000',
            ark_flights_offer_request_body( $search ),
            35
        );

        if ( is_wp_error( $data ) ) {
            $code = $data->get_error_code();
            // The visitor's details are still a lead: the team can quote by hand.
            ark_flights_send_lead( $search, null, 'search failed: ' . $code );
            wp_send_json_error(
                array(
                    'code'    => $code,
                    'message' => ark_flights_error_message( $code ),
                ),
                'invalid_request' === $code ? 400 : 502
            );
        }

        $offers = isset( $data['offers'] ) && is_array( $data['offers'] ) ? $data['offers'] : array();
        $result = ark_flights_normalize_offers( $offers, (int) apply_filters( 'ark_flights_max_results', 10 ) );
        set_transient( $cache_key, $result, 10 * MINUTE_IN_SECONDS );
    }

    ark_flights_send_lead( $search, $result, $result['total'] . ' options' );

    wp_send_json_success(
        array(
            'totalOptions' => $result['total'],
            'options'      => $result['options'], // No price anywhere in here.
        )
    );
}
add_action( 'wp_ajax_ark_flight_search', 'ark_flight_search' );
add_action( 'wp_ajax_nopriv_ark_flight_search', 'ark_flight_search' );

/**
 * AJAX: airport / city suggestions for the From and To fields.
 */
function ark_fly_places() {
    if ( ! check_ajax_referer( 'ark_nonce', 'nonce', false ) ) {
        wp_send_json_error( array( 'code' => 'bad_nonce' ), 403 );
    }

    $query = isset( $_GET['q'] ) ? sanitize_text_field( wp_unslash( $_GET['q'] ) ) : '';
    $query = trim( $query );
    if ( strlen( $query ) < 2 || strlen( $query ) > 60 ) {
        wp_send_json_success( array( 'places' => array() ) );
    }

    $cache_key = 'ark_fly_p_' . md5( strtolower( $query ) );
    $places    = get_transient( $cache_key );

    if ( false === $places ) {
        if ( ark_flights_rate_limited( 'places', 90 ) ) {
            wp_send_json_error( array( 'code' => 'rate_limited' ), 429 );
        }

        $data = ark_flights_duffel_request( 'GET', '/places/suggestions?query=' . rawurlencode( $query ), null, 8 );
        if ( is_wp_error( $data ) ) {
            wp_send_json_error( array( 'code' => $data->get_error_code() ), 502 );
        }

        $places = array();
        foreach ( $data as $p ) {
            if ( ! is_array( $p ) || empty( $p['iata_code'] ) ) {
                continue;
            }
            $places[] = array(
                'iata'    => (string) $p['iata_code'],
                'type'    => isset( $p['type'] ) && 'city' === $p['type'] ? 'city' : 'airport',
                'name'    => isset( $p['name'] ) ? (string) $p['name'] : (string) $p['iata_code'],
                'city'    => isset( $p['city_name'] ) ? (string) $p['city_name'] : '',
                'country' => isset( $p['iata_country_code'] ) ? (string) $p['iata_country_code'] : '',
            );
            if ( count( $places ) >= 8 ) {
                break;
            }
        }
        set_transient( $cache_key, $places, 12 * HOUR_IN_SECONDS );
    }

    wp_send_json_success( array( 'places' => $places ) );
}
add_action( 'wp_ajax_ark_fly_places', 'ark_fly_places' );
add_action( 'wp_ajax_nopriv_ark_fly_places', 'ark_fly_places' );
