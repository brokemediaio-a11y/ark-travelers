<?php
/**
 * ARK Travelers – URL-based multilingual (EN / SV)
 * / = English, /sv/ = Swedish. Same templates, no plugins, no duplicate pages.
 *
 * @package ARK_Travelers
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/** Supported languages */
define( 'ARK_LANG_EN', 'en' );
define( 'ARK_LANG_SV', 'sv' );

/** @var array|null Cached translations for current language */
$GLOBALS['ark_translations'] = null;

/* -----------------------------------------------------------------------
 * Internal helpers
 * -------------------------------------------------------------------- */

/**
 * Return the raw request path, trimmed of slashes. No home_url() call so it
 * is safe to use at init priority 1-2.
 *
 * @return string e.g. 'sv', 'sv/fly', 'about', ''
 */
function ark_multilang_raw_path() {
    $uri  = isset( $_SERVER['REQUEST_URI'] ) ? (string) $_SERVER['REQUEST_URI'] : '';
    $path = (string) parse_url( $uri, PHP_URL_PATH );
    return trim( $path, '/' );
}

/**
 * Return the request path, stripped of a WordPress subdirectory prefix if
 * WP is installed in a folder (safe to call from request/template_redirect).
 *
 * @return string e.g. 'sv', 'sv/fly', 'about', ''
 */
function ark_multilang_request_path() {
    $path      = ark_multilang_raw_path();
    $home_path = trim( (string) parse_url( home_url( '/' ), PHP_URL_PATH ), '/' );
    if ( $home_path !== '' ) {
        if ( $path === $home_path ) {
            return '';
        }
        if ( strpos( $path, $home_path . '/' ) === 0 ) {
            return trim( substr( $path, strlen( $home_path ) + 1 ), '/' );
        }
    }
    return $path;
}

/**
 * True when the given (already trimmed) path belongs to the Swedish version.
 *
 * @param string $path Trimmed path, e.g. 'sv', 'sv/fly'.
 * @return bool
 */
function ark_multilang_uri_is_swedish( $path ) {
    return ( $path === 'sv' )
        || ( strpos( $path, 'sv/' ) === 0 )
        || ( substr( $path, -3 ) === '/sv' )
        || ( strpos( $path, '/sv/' ) !== false );
}

/* -----------------------------------------------------------------------
 * Language detection — runs as early as possible so ARK_LANG is set
 * before any template or translation call.
 * -------------------------------------------------------------------- */

/**
 * Detect Swedish from the raw REQUEST_URI path at init priority 1.
 * Uses ark_multilang_raw_path() — no home_url() dependency.
 */
function ark_multilang_set_lang_from_uri() {
    if ( is_admin() || defined( 'ARK_LANG' ) ) {
        return;
    }
    if ( ark_multilang_uri_is_swedish( ark_multilang_raw_path() ) ) {
        define( 'ARK_LANG', ARK_LANG_SV );
    }
}
add_action( 'init', 'ark_multilang_set_lang_from_uri', 1 );

/**
 * Fallback detection on template_redirect (catches cases where init ran in
 * a weird order or ark_lang query var was set by rewrite rules).
 */
function ark_multilang_set_lang_on_template_redirect() {
    if ( defined( 'ARK_LANG' ) ) {
        return;
    }
    if ( ark_multilang_uri_is_swedish( ark_multilang_raw_path() ) ) {
        define( 'ARK_LANG', ARK_LANG_SV );
        $GLOBALS['ark_translations'] = null;
        return;
    }
    if ( get_query_var( 'ark_lang' ) === ARK_LANG_SV ) {
        define( 'ARK_LANG', ARK_LANG_SV );
        $GLOBALS['ark_translations'] = null;
    }
}
add_action( 'template_redirect', 'ark_multilang_set_lang_on_template_redirect', 0 );

/**
 * Extra fallback: set from WP_Query vars once they're available.
 */
function ark_multilang_set_lang_from_wp_query() {
    if ( is_admin() || defined( 'ARK_LANG' ) ) {
        return;
    }
    global $wp_query;
    if ( $wp_query instanceof WP_Query && $wp_query->get( 'ark_lang' ) === ARK_LANG_SV ) {
        define( 'ARK_LANG', ARK_LANG_SV );
        $GLOBALS['ark_translations'] = null;
    }
}
add_action( 'wp', 'ark_multilang_set_lang_from_wp_query', 0 );

/* -----------------------------------------------------------------------
 * Routing — set correct WP query vars for Swedish URLs.
 * Runs on the 'request' filter so it works regardless of whether the WP
 * rewrite rules are in the database.
 * -------------------------------------------------------------------- */

/**
 * Primary routing: reads REQUEST_URI directly and sets query vars for ALL
 * Swedish pages. Priority 5 = runs before any other 'request' filter.
 */
function ark_multilang_route_sv( $query_vars ) {
    if ( is_admin() ) {
        return $query_vars;
    }

    // Use the subdirectory-aware path for routing (home_url is safe here).
    $path = ark_multilang_request_path();

    if ( ! ark_multilang_uri_is_swedish( $path ) ) {
        return $query_vars;
    }

    // Ensure ARK_LANG is set (belt-and-suspenders — init already did this).
    if ( ! defined( 'ARK_LANG' ) ) {
        define( 'ARK_LANG', ARK_LANG_SV );
    }
    $query_vars['ark_lang'] = ARK_LANG_SV;

    // Extract the slug that follows 'sv/'.
    $slug = '';
    if ( $path !== 'sv' && strpos( $path, 'sv/' ) === 0 ) {
        $slug = trim( substr( $path, 3 ), '/' );
    }

    if ( $slug === '' ) {
        // --- Swedish homepage ---
        $front_page_id = (int) get_option( 'page_on_front' );
        if ( get_option( 'show_on_front' ) === 'page' && $front_page_id > 0 ) {
            // Replace vars so no stale pagename/error leaks from WP's default routing.
            $query_vars = array(
                'ark_lang' => ARK_LANG_SV,
                'page_id'  => $front_page_id,
            );
        }
        return $query_vars;
    }

    // --- Umrah single: sv/package/{name} ---
    if ( strpos( $slug, 'package/' ) === 0 ) {
        $pkg_name = trim( substr( $slug, 8 ), '/' );
        unset( $query_vars['pagename'], $query_vars['error'], $query_vars['name'] );
        $query_vars['post_type'] = 'umrah';
        $query_vars['name']      = $pkg_name;
        return $query_vars;
    }

    // --- Umrah archive: sv/package ---
    if ( $slug === 'package' ) {
        unset( $query_vars['pagename'], $query_vars['error'] );
        $query_vars['post_type'] = 'umrah';
        return $query_vars;
    }

    // --- Regular page: sv/fly, sv/about, sv/contact, sv/umrah … ---
    unset( $query_vars['error'] );
    $query_vars['pagename'] = $slug;
    return $query_vars;
}
add_filter( 'request', 'ark_multilang_route_sv', 5 );

/* -----------------------------------------------------------------------
 * Canonical redirect prevention
 * -------------------------------------------------------------------- */

/**
 * Stop WordPress redirecting /sv/... to the English canonical URL.
 */
function ark_multilang_prevent_canonical_redirect( $redirect_url, $requested_url ) {
    if ( empty( $redirect_url ) ) {
        return $redirect_url;
    }
    $path = trim( (string) parse_url( $requested_url, PHP_URL_PATH ), '/' );
    if ( ark_multilang_uri_is_swedish( $path ) ) {
        return false;
    }
    return $redirect_url;
}
add_filter( 'redirect_canonical', 'ark_multilang_prevent_canonical_redirect', 10, 2 );

/* -----------------------------------------------------------------------
 * Cache-busting for Swedish pages
 * -------------------------------------------------------------------- */

function ark_multilang_no_cache_sv() {
    if ( ! function_exists( 'ark_lang' ) || ark_lang() !== ARK_LANG_SV ) {
        return;
    }
    if ( headers_sent() ) {
        return;
    }
    header( 'Cache-Control: no-cache, no-store, must-revalidate, max-age=0' );
    header( 'Pragma: no-cache' );
    header( 'Expires: 0' );
}
add_action( 'send_headers', 'ark_multilang_no_cache_sv', 1 );

/* -----------------------------------------------------------------------
 * Rewrite rules (fallback for when the request filter isn't enough, e.g.
 * REST API, sitemaps). Priority 1 = registered before everything else.
 * -------------------------------------------------------------------- */

function ark_multilang_rewrite_rules() {
    add_rewrite_rule( 'sv/package/([^/]+)/?$', 'index.php?ark_lang=sv&post_type=umrah&name=$matches[1]', 'top' );
    add_rewrite_rule( 'sv/package/?$', 'index.php?ark_lang=sv&post_type=umrah', 'top' );
    add_rewrite_rule( 'sv/(.+?)/?$', 'index.php?ark_lang=sv&pagename=$matches[1]', 'top' );
    add_rewrite_rule( 'sv/?$', 'index.php?ark_lang=sv', 'top' );
}
add_action( 'init', 'ark_multilang_rewrite_rules', 1 );

/**
 * Flush rules once per version so /sv/ rules reach the WP DB.
 */
define( 'ARK_MULTILANG_RULES_VERSION', 4 );

function ark_multilang_maybe_flush_rules() {
    if ( (int) get_option( 'ark_multilang_rules_version', 0 ) >= ARK_MULTILANG_RULES_VERSION ) {
        return;
    }
    flush_rewrite_rules( false );
    update_option( 'ark_multilang_rules_version', ARK_MULTILANG_RULES_VERSION );
}
add_action( 'init', 'ark_multilang_maybe_flush_rules', 99 );
add_action( 'admin_init', 'ark_multilang_maybe_flush_rules' );

/**
 * If a /sv/… page 404s (rules not yet flushed), flush and redirect once.
 */
function ark_multilang_404_flush_and_redirect() {
    if ( ! is_404() ) {
        return;
    }
    $path = ark_multilang_raw_path();
    if ( $path !== 'sv' && strpos( $path, 'sv/' ) !== 0 ) {
        return;
    }
    flush_rewrite_rules();
    update_option( 'ark_multilang_rules_version', ARK_MULTILANG_RULES_VERSION );
    wp_safe_redirect( home_url( '/' . $path . '/' ) );
    exit;
}
add_action( 'template_redirect', 'ark_multilang_404_flush_and_redirect', 1 );

/* -----------------------------------------------------------------------
 * Registered query var
 * -------------------------------------------------------------------- */

function ark_multilang_query_vars( $vars ) {
    $vars[] = 'ark_lang';
    return $vars;
}
add_filter( 'query_vars', 'ark_multilang_query_vars' );

/**
 * Set ARK_LANG from parse_request vars (belt-and-suspenders).
 */
function ark_multilang_set_lang( $wp ) {
    if ( is_admin() || ! isset( $wp->query_vars['ark_lang'] ) ) {
        return;
    }
    if ( $wp->query_vars['ark_lang'] === ARK_LANG_SV && ! defined( 'ARK_LANG' ) ) {
        define( 'ARK_LANG', ARK_LANG_SV );
    }
}
add_action( 'parse_request', 'ark_multilang_set_lang', 5 );

/* -----------------------------------------------------------------------
 * Public API functions — used by templates and header.php
 * -------------------------------------------------------------------- */

/**
 * Current language code: 'en' or 'sv'.
 */
function ark_lang() {
    return defined( 'ARK_LANG' ) && ARK_LANG === ARK_LANG_SV ? ARK_LANG_SV : ARK_LANG_EN;
}

/**
 * Current page path without the language prefix, e.g. 'fly', 'about', ''.
 */
function ark_current_path() {
    // Use subdirectory-aware path (home_url is safe at template/header time).
    $path = ark_multilang_request_path();
    if ( $path === 'sv' ) {
        return '';
    }
    if ( strpos( $path, 'sv/' ) === 0 ) {
        $path = substr( $path, 3 );
    } elseif ( preg_match( '#/sv/(.+)$#', $path, $m ) ) {
        $path = $m[1];
    }
    return trim( $path, '/' );
}

/**
 * Language-prefixed URL for internal links (use instead of home_url()).
 *
 * @param string $path e.g. '/', '/fly/', '/about/'
 * @return string
 */
function ark_url( $path ) {
    $path = $path === '' ? '/' : $path;
    if ( ark_lang() === ARK_LANG_SV ) {
        $path = ( $path === '/' ) ? '/sv/' : '/sv' . $path;
    }
    return home_url( $path );
}

/**
 * URL for the other language, preserving the current page.
 *
 * @param string $lang 'en' or 'sv'
 * @return string
 */
function ark_switch_url( $lang ) {
    $current = ark_current_path();
    if ( $lang === ARK_LANG_SV ) {
        return $current ? home_url( '/sv/' . $current . '/' ) : home_url( '/sv/' );
    }
    return $current ? home_url( '/' . $current . '/' ) : home_url( '/' );
}

/* -----------------------------------------------------------------------
 * Translations
 * -------------------------------------------------------------------- */

function ark_load_translations() {
    if ( is_array( $GLOBALS['ark_translations'] ) ) {
        return $GLOBALS['ark_translations'];
    }
    $lang = ark_lang();
    $file = get_stylesheet_directory() . '/languages/' . $lang . '.php';
    if ( ! is_readable( $file ) ) {
        $file = get_stylesheet_directory() . '/languages/en.php';
    }
    $GLOBALS['ark_translations'] = is_readable( $file ) ? (array) include $file : array();
    return $GLOBALS['ark_translations'];
}

function ark_t( $key ) {
    $translations = ark_load_translations();
    return isset( $translations[ $key ] ) ? $translations[ $key ] : $key;
}

/* -----------------------------------------------------------------------
 * SEO / hreflang
 * -------------------------------------------------------------------- */

function ark_multilang_seo_head() {
    if ( is_admin() || ! function_exists( 'ark_url' ) ) {
        return;
    }
    $current_path = ark_current_path();
    $base         = $current_path ? '/' . $current_path . '/' : '/';
    $sv_base      = $current_path ? '/sv/' . $current_path . '/' : '/sv/';
    $canonical    = ark_lang() === ARK_LANG_SV ? home_url( $sv_base ) : home_url( $base );
    echo '<link rel="canonical" href="' . esc_url( $canonical ) . '">' . "\n";
    echo '<link rel="alternate" hreflang="en" href="' . esc_url( home_url( $base ) ) . '">' . "\n";
    echo '<link rel="alternate" hreflang="sv" href="' . esc_url( home_url( $sv_base ) ) . '">' . "\n";
    echo '<link rel="alternate" hreflang="x-default" href="' . esc_url( home_url( $base ) ) . '">' . "\n";
}
add_action( 'wp_head', 'ark_multilang_seo_head', 5 );

/* -----------------------------------------------------------------------
 * Permalink filters — keep Umrah URLs in /sv/package/ when Swedish
 * -------------------------------------------------------------------- */

function ark_multilang_umrah_permalink( $url, $post ) {
    if ( ! function_exists( 'ark_lang' ) || ark_lang() !== ARK_LANG_SV ) {
        return $url;
    }
    if ( $post instanceof WP_Post && $post->post_type === 'umrah' ) {
        return home_url( '/sv/package/' . $post->post_name . '/' );
    }
    return $url;
}
add_filter( 'post_type_link', 'ark_multilang_umrah_permalink', 10, 2 );

function ark_multilang_umrah_archive_link( $url, $post_type ) {
    if ( ! function_exists( 'ark_lang' ) || ark_lang() !== ARK_LANG_SV || $post_type !== 'umrah' ) {
        return $url;
    }
    return home_url( '/sv/package/' );
}
add_filter( 'post_type_archive_link', 'ark_multilang_umrah_archive_link', 10, 2 );

/* -----------------------------------------------------------------------
 * Nav menu — rewrite all internal links to /sv/… when viewing Swedish
 * -------------------------------------------------------------------- */

function ark_multilang_nav_menu_links( $items, $args ) {
    if ( ! function_exists( 'ark_lang' ) || ark_lang() !== ARK_LANG_SV ) {
        return $items;
    }
    $home      = rtrim( home_url(), '/' );
    $home_host = (string) parse_url( $home, PHP_URL_HOST );

    foreach ( $items as $item ) {
        if ( empty( $item->url ) ) {
            continue;
        }
        $parsed    = wp_parse_url( $item->url );
        if ( ! is_array( $parsed ) ) {
            continue;
        }
        $item_host = isset( $parsed['host'] ) ? (string) $parsed['host'] : '';
        if ( $item_host !== '' && $item_host !== $home_host ) {
            continue; // external link — leave alone
        }

        // Normalise path relative to home.
        $raw_path  = isset( $parsed['path'] ) ? trim( $parsed['path'], '/' ) : '';
        $home_path = trim( (string) parse_url( $home . '/', PHP_URL_PATH ), '/' );
        if ( $home_path !== '' && strpos( $raw_path, $home_path . '/' ) === 0 ) {
            $raw_path = trim( substr( $raw_path, strlen( $home_path ) + 1 ), '/' );
        } elseif ( $raw_path === $home_path ) {
            $raw_path = '';
        }
        $path = $raw_path;

        if ( $path === '' || $path === 'sv' ) {
            $item->url = home_url( '/sv/' );
            continue;
        }
        if ( strpos( $path, 'sv/' ) === 0 ) {
            continue; // already Swedish
        }
        if ( $path === 'umrah' ) {
            $item->url = home_url( '/sv/package/' );
            continue;
        }
        if ( strpos( $path, 'umrah/' ) === 0 ) {
            $item->url = home_url( '/sv/package/' . substr( $path, 6 ) . '/' );
            continue;
        }
        $item->url = home_url( '/sv/' . $path . '/' );
    }
    return $items;
}
add_filter( 'wp_nav_menu_objects', 'ark_multilang_nav_menu_links', 10, 2 );

/* -----------------------------------------------------------------------
 * Body class & HTML lang attribute
 * -------------------------------------------------------------------- */

function ark_multilang_body_class( $classes ) {
    if ( function_exists( 'ark_lang' ) && ark_lang() === ARK_LANG_SV ) {
        $classes[] = 'ark-lang-sv';
    }
    return $classes;
}
add_filter( 'body_class', 'ark_multilang_body_class' );

function ark_multilang_html_lang( $output ) {
    if ( function_exists( 'ark_lang' ) && ark_lang() === ARK_LANG_SV ) {
        return 'lang="sv" dir="ltr"';
    }
    return $output;
}
add_filter( 'language_attributes', 'ark_multilang_html_lang' );

/* -----------------------------------------------------------------------
 * Theme activation
 * -------------------------------------------------------------------- */

function ark_multilang_activation() {
    ark_multilang_rewrite_rules();
    flush_rewrite_rules();
    delete_option( 'ark_multilang_rules_version' );
    delete_option( 'ark_multilang_rules_flushed' ); // legacy cleanup
}
add_action( 'after_switch_theme', 'ark_multilang_activation' );
