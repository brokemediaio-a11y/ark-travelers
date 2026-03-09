<?php
/**
 * Single Umrah package – hero, sticky sidebar, overview, included, itinerary, FAQ, terms & conditions
 *
 * @package ARK_Travelers
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();

while ( have_posts() ) {
    the_post();
    $post_id = get_the_ID();
    $title = get_the_title();
    $price = get_post_meta( $post_id, 'price_sek', true );
    if ( $price === '' || $price === false ) {
        $price = get_post_meta( $post_id, 'ark_price', true ) ?: '34,900';
    }
    $duration = get_post_meta( $post_id, 'duration', true );
    if ( $duration === '' || $duration === false ) {
        $duration = get_post_meta( $post_id, 'ark_duration', true ) ?: '15';
    }
    $stars = get_post_meta( $post_id, 'ark_stars', true ) ?: '5';
    $badge = get_post_meta( $post_id, 'ark_badge', true ) ?: '';
    $departures = get_post_meta( $post_id, 'ark_departures', true );
    $linked_product_id = function_exists( 'get_field' ) ? get_field( 'linked_product_id', $post_id ) : get_post_meta( $post_id, 'linked_product_id', true );
    $linked_product_id = (int) $linked_product_id;
    $buy_now_url       = '';
    if ( $linked_product_id && function_exists( 'wc_get_product' ) ) {
        $wc_product = wc_get_product( $linked_product_id );
        if ( $wc_product && function_exists( 'wc_get_checkout_url' ) ) {
            $buy_now_url = wc_get_checkout_url() . '?add-to-cart=' . $linked_product_id;
        }
    }
    if ( ! is_array( $departures ) ) {
        $departures = array( '2026-02-15', '2026-03-01', '2026-03-15' );
    }
    if ( empty( $departures ) ) {
        $departures = array( '2026-02-15', '2026-03-01', '2026-03-15' );
    }
    ?>
<main id="main" class="ark-main ark-main-single-umrah site-main">
    <!-- Sticky booking bar: appears when hero card scrolls out of view -->
    <div class="ark-sticky-cta" id="ark-sticky-cta" aria-hidden="true">
        <div class="ark-container ark-sticky-cta-inner">
            <div class="ark-sticky-cta-info">
                <span class="ark-sticky-cta-title"><?php echo esc_html( $title ); ?></span>
                <span class="ark-sticky-cta-price">SEK <?php echo esc_html( $price ); ?></span>
            </div>
            <div class="ark-sticky-cta-actions">
                <a href="tel:+46000000000" class="ark-sticky-cta-icon-btn ark-sticky-cta-call" aria-label="<?php echo esc_attr( ark_t( 'single_call_aria' ) ); ?>">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                </a>
                <a href="https://wa.me/46000000000" class="ark-sticky-cta-icon-btn ark-sticky-cta-wa" target="_blank" rel="noopener" aria-label="WhatsApp">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                </a>
                <?php if ( $buy_now_url ) : ?>
                    <a href="<?php echo esc_url( $buy_now_url ); ?>" class="ark-sticky-cta-btn btn-primary"><?php echo esc_html( ark_t( 'single_buy_now' ) ); ?></a>
                <?php else : ?>
                    <span class="ark-sticky-cta-btn btn-primary">Package unavailable.</span>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <section class="ark-single-hero">
        <div class="ark-single-hero-bg" style="background-image: url('<?php echo esc_url( get_the_post_thumbnail_url( $post_id, 'package-hero' ) ?: 'https://images.unsplash.com/photo-1511895426328-dc8714191300?w=1920' ); ?>');"></div>
        <div class="ark-single-hero-overlay"></div>
        <div class="ark-container ark-single-hero-inner">
            <div class="ark-hero-text">
                <?php if ( $badge ) : ?>
                    <span class="ark-single-badge"><?php echo esc_html( $badge ); ?></span>
                <?php endif; ?>
                <h1 class="ark-single-hero-title"><?php echo esc_html( $title ); ?></h1>
                <div class="ark-single-hero-meta">
                    <span><?php echo esc_html( $duration . ' ' . ark_t( 'umrah_days' ) ); ?></span>
                    <span><?php echo esc_html( $stars . ' ' . ark_t( 'umrah_stars' ) ); ?></span>
                    <span class="ark-single-hero-price">SEK <?php echo esc_html( $price ); ?></span>
                </div>
            </div>
            <div class="ark-hero-booking-wrap ark-hero-booking-animate">
                <div class="ark-hero-booking-card card">
                    <div class="ark-booking-card-accent"></div>
                    <h3 class="ark-hero-booking-title"><?php echo esc_html( ark_t( 'single_book_package' ) ); ?></h3>
                    <p class="ark-booking-price">SEK <span class="ark-booking-price-value"><?php echo esc_html( $price ); ?></span></p>
                    <form class="ark-booking-form" aria-label="<?php echo esc_attr( ark_t( 'single_booking_form_aria' ) ); ?>">
                        <div class="ark-form-group">
                            <label for="ark-hero-departure-select"><?php echo esc_html( ark_t( 'single_departure_date' ) ); ?></label>
                            <select id="ark-hero-departure-select" name="departure">
                                <?php foreach ( $departures as $d ) : ?>
                                    <option value="<?php echo esc_attr( $d ); ?>"><?php echo esc_html( date_i18n( 'j M Y', strtotime( $d ) ) ); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="ark-form-group">
                            <label for="ark-hero-travelers"><?php echo esc_html( ark_t( 'single_number_travelers' ) ); ?></label>
                            <select id="ark-hero-travelers" name="travelers">
                                <?php for ( $i = 1; $i <= 9; $i++ ) : ?>
                                    <option value="<?php echo (int) $i; ?>"><?php echo (int) $i; ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <?php if ( $buy_now_url ) : ?>
                            <a href="<?php echo esc_url( $buy_now_url ); ?>" class="ark-booking-cta btn-primary"><?php echo esc_html( ark_t( 'single_buy_now' ) ); ?></a>
                        <?php else : ?>
                            <span class="ark-booking-cta btn-primary">Package unavailable.</span>
                        <?php endif; ?>
                    </form>
                    <p class="ark-booking-phone"><?php echo esc_html( ark_t( 'single_call_us' ) ); ?> <a href="tel:+46000000000">+46 XX XXX XX XX</a></p>
                    <a href="https://wa.me/46000000000" class="ark-whatsapp-btn" target="_blank" rel="noopener">WhatsApp</a>
                </div>
            </div>
        </div>
    </section>

    <div class="ark-single-layout">
        <div class="ark-single-content">
            <section class="ark-single-section ark-package-overview ark-reveal" data-reveal>
                <header class="ark-overview-header">
                    <h2 class="ark-overview-title"><?php echo esc_html( ark_t( 'single_overview_title' ) ); ?></h2>
                </header>
                <div class="ark-single-body ark-overview-body">
                    <?php
                    // Check for custom overview meta field first, then fall back to post content
                    $custom_overview = get_post_meta( $post_id, 'ark_package_overview', true );
                    if ( ! empty( $custom_overview ) ) {
                        echo wp_kses_post( $custom_overview );
                    } elseif ( get_the_content() ) {
                        the_content();
                    } else {
                        echo '<p class="ark-overview-intro">' . esc_html( ark_t( 'single_overview_intro' ) ) . '</p>';
                        echo '<p>' . esc_html( ark_t( 'single_overview_breakdown' ) ) . '</p>';
                    }
                    ?>
                </div>
            </section>

            <section class="ark-single-section ark-included-section">
                <header class="ark-included-header">
                    <h2 class="ark-included-title"><?php echo esc_html( ark_t( 'single_included_title' ) ); ?></h2>
                </header>
                <ul class="ark-included-list">
                    <?php
                    $included = get_post_meta( $post_id, 'ark_included', true );
                    if ( ! is_array( $included ) || empty( $included ) ) {
                        $included = array(
                            ark_t( 'single_included_flights' ),
                            ark_t( 'single_included_hotels' ),
                            ark_t( 'single_included_meals' ),
                            ark_t( 'single_included_transport' ),
                            ark_t( 'single_included_visa' ),
                            ark_t( 'single_included_guide' ),
                            ark_t( 'single_included_insurance' ),
                        );
                    }
                    foreach ( $included as $item ) :
                        if ( ! empty( trim( $item ) ) ) :
                            ?>
                            <li><span class="ark-included-item-text"><?php echo esc_html( $item ); ?></span></li>
                            <?php
                        endif;
                    endforeach;
                    ?>
                </ul>
            </section>

            <section class="ark-single-section ark-reveal" data-reveal>
                <h2><?php echo esc_html( ark_t( 'single_itinerary_title' ) ); ?></h2>
                <div class="ark-accordion">
                    <?php
                    $itinerary = get_post_meta( $post_id, 'ark_itinerary', true );
                    if ( ! is_array( $itinerary ) || empty( $itinerary ) ) {
                        $itinerary = array(
                            array( 'day' => ark_t( 'single_itinerary_day1' ), 'content' => ark_t( 'single_itinerary_day1_content' ) ),
                            array( 'day' => ark_t( 'single_itinerary_day2_7' ), 'content' => ark_t( 'single_itinerary_day2_7_content' ) ),
                            array( 'day' => ark_t( 'single_itinerary_day8_13' ), 'content' => ark_t( 'single_itinerary_day8_13_content' ) ),
                            array( 'day' => ark_t( 'single_itinerary_day14_15' ), 'content' => ark_t( 'single_itinerary_day14_15_content' ) ),
                        );
                    }
                    $itinerary_index = 0;
                    foreach ( $itinerary as $item ) :
                        if ( empty( $item['day'] ) || empty( $item['content'] ) ) {
                            continue;
                        }
                        $itinerary_index++;
                        $is_first = ( $itinerary_index === 1 );
                        ?>
                        <div class="ark-accordion-item<?php echo $is_first ? ' is-open' : ''; ?>">
                            <button type="button" class="ark-accordion-head" aria-expanded="<?php echo $is_first ? 'true' : 'false'; ?>" aria-controls="itinerary-<?php echo esc_attr( $itinerary_index ); ?>" id="acc-itinerary-<?php echo esc_attr( $itinerary_index ); ?>"><?php echo esc_html( $item['day'] ); ?></button>
                            <div id="itinerary-<?php echo esc_attr( $itinerary_index ); ?>" class="ark-accordion-body" role="region" aria-labelledby="acc-itinerary-<?php echo esc_attr( $itinerary_index ); ?>">
                                <p><?php echo esc_html( $item['content'] ); ?></p>
                            </div>
                        </div>
                        <?php
                    endforeach;
                    ?>
                </div>
            </section>

            <section class="ark-single-section ark-reveal" data-reveal>
                <h2><?php echo esc_html( ark_t( 'single_important_title' ) ); ?></h2>
                <div class="ark-important-info">
                    <?php
                    $important = get_post_meta( $post_id, 'ark_important_info', true );
                    if ( empty( $important ) ) {
                        $important = ark_t( 'single_important_default' );
                    }
                    echo wp_kses_post( $important );
                    ?>
                </div>
            </section>

            <section class="ark-single-section ark-faq-section ark-reveal" data-reveal>
                <h2><?php echo esc_html( ark_t( 'single_faq_title' ) ); ?></h2>
                <div class="ark-accordion" id="ark-faq-accordion">
                    <?php
                    $faq = get_post_meta( $post_id, 'ark_faq', true );
                    if ( ! is_array( $faq ) || empty( $faq ) ) {
                        $faq = array(
                            array( 'question' => ark_t( 'single_faq_q1' ), 'answer' => ark_t( 'single_faq_a1' ) ),
                            array( 'question' => ark_t( 'single_faq_q2' ), 'answer' => ark_t( 'single_faq_a2' ) ),
                            array( 'question' => ark_t( 'single_faq_q3' ), 'answer' => ark_t( 'single_faq_a3' ) ),
                        );
                    }
                    $faq_index = 0;
                    foreach ( $faq as $item ) :
                        if ( empty( $item['question'] ) || empty( $item['answer'] ) ) {
                            continue;
                        }
                        $faq_index++;
                        ?>
                        <div class="ark-accordion-item">
                            <button type="button" class="ark-accordion-head" aria-expanded="false" aria-controls="faq-<?php echo esc_attr( $faq_index ); ?>" id="acc-faq-<?php echo esc_attr( $faq_index ); ?>"><?php echo esc_html( $item['question'] ); ?></button>
                            <div id="faq-<?php echo esc_attr( $faq_index ); ?>" class="ark-accordion-body" role="region" aria-labelledby="acc-faq-<?php echo esc_attr( $faq_index ); ?>">
                                <p><?php echo esc_html( $item['answer'] ); ?></p>
                            </div>
                        </div>
                        <?php
                    endforeach;
                    ?>
                </div>
            </section>

            <section class="ark-single-section ark-terms-section ark-reveal" data-reveal>
                <header class="ark-terms-header">
                    <h2 class="ark-terms-title"><?php echo esc_html( ark_t( 'single_terms_title' ) ); ?></h2>
                </header>
                <div class="ark-terms-content">
                    <?php
                    $terms = get_post_meta( $post_id, 'ark_terms_conditions', true );
                    if ( empty( $terms ) ) {
                        $terms = '<p>' . esc_html( ark_t( 'single_terms_default' ) ) . '</p>';
                    }
                    echo wp_kses_post( $terms );
                    ?>
                </div>
            </section>

            <section class="ark-single-cta-strip ark-reveal" data-reveal>
                <p class="ark-cta-strip-text"><?php echo esc_html( ark_t( 'single_cta_strip' ) ); ?></p>
                <div class="ark-cta-strip-actions">
                    <a href="tel:+46000000000" class="ark-cta-strip-btn ark-cta-call"><?php echo esc_html( ark_t( 'single_call_us_btn' ) ); ?></a>
                    <a href="https://wa.me/46000000000" class="ark-cta-strip-btn ark-cta-whatsapp" target="_blank" rel="noopener">WhatsApp</a>
                </div>
            </section>
        </div>
    </div>

</main>
    <?php
}

get_footer();
