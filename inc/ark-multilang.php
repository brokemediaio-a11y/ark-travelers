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

/**
 * Detect if current request URL is Swedish (/sv or /sv/...). Handles root and subdirectory installs.
 *
 * @param string $path Path from REQUEST_URI (no query, slashes trimmed).
 * @return bool
 */
function ark_multilang_uri_is_swedish( $path ) {
    if ( $path === 'sv' ) {
        return true;
    }
    if ( strpos( $path, 'sv/' ) === 0 ) {
        return true;
    }
    if ( substr( $path, -3 ) === '/sv' || $path === 'sv' ) {
        return true;
    }
    if ( strpos( $path, '/sv/' ) !== false ) {
        return true;
    }
    return false;
}

/**
 * Set Swedish from URL immediately (does not rely on query_vars or request filter).
 * Ensures /sv/ always shows Swedish even if caching or host alters query vars.
 */
function ark_multilang_set_lang_from_uri() {
    if ( is_admin() ) {
        return;
    }
    if ( defined( 'ARK_LANG' ) ) {
        return;
    }
    $uri  = isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '';
    $path = trim( parse_url( $uri, PHP_URL_PATH ), '/' );
    if ( ark_multilang_uri_is_swedish( $path ) ) {
        define( 'ARK_LANG', ARK_LANG_SV );
    }
}
add_action( 'init', 'ark_multilang_set_lang_from_uri', 2 );

/**
 * Fallback: set Swedish on template_redirect if URL is /sv/ or main query has ark_lang=sv (so all /sv/.../ pages show Swedish).
 */
function ark_multilang_set_lang_on_template_redirect() {
    if ( defined( 'ARK_LANG' ) ) {
        return;
    }
    $uri  = isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '';
    $path = trim( parse_url( $uri, PHP_URL_PATH ), '/' );
    if ( ark_multilang_uri_is_swedish( $path ) ) {
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
 * Set Swedish from main query as soon as query is parsed (before template). Ensures subpages like /sv/fly/ get Swedish.
 */
function ark_multilang_set_lang_from_wp_query() {
    if ( is_admin() || defined( 'ARK_LANG' ) ) {
        return;
    }
    global $wp_query;
    if ( ! $wp_query instanceof WP_Query ) {
        return;
    }
    if ( $wp_query->get( 'ark_lang' ) === ARK_LANG_SV ) {
        define( 'ARK_LANG', ARK_LANG_SV );
        $GLOBALS['ark_translations'] = null;
    }
}
add_action( 'wp', 'ark_multilang_set_lang_from_wp_query', 0 );

/**
 * Prevent WordPress from redirecting /sv/ to / (canonical redirect would strip language).
 */
function ark_multilang_prevent_canonical_redirect( $redirect_url, $requested_url ) {
    if ( empty( $redirect_url ) ) {
        return $redirect_url;
    }
    $path = trim( parse_url( $requested_url, PHP_URL_PATH ), '/' );
    if ( ark_multilang_uri_is_swedish( $path ) ) {
        return false;
    }
    return $redirect_url;
}
add_filter( 'redirect_canonical', 'ark_multilang_prevent_canonical_redirect', 10, 2 );

/**
 * Prevent caching of Swedish pages so /sv/ is never served as cached English.
 */
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

/**
 * Register rewrite rules for /sv/ and /sv/.../
 * More specific rules first so /sv/package/ doesn't become pagename=package.
 */
function ark_multilang_rewrite_rules() {
    add_rewrite_rule( 'sv/package/([^/]+)/?$', 'index.php?ark_lang=sv&post_type=umrah&name=$matches[1]', 'top' );
    add_rewrite_rule( 'sv/package/?$', 'index.php?ark_lang=sv&post_type=umrah', 'top' );
    add_rewrite_rule( 'sv/(.+?)/?$', 'index.php?ark_lang=sv&pagename=$matches[1]', 'top' );
    add_rewrite_rule( 'sv/?$', 'index.php?ark_lang=sv', 'top' );
}
add_action( 'init', 'ark_multilang_rewrite_rules', 1 );

/**
 * Flush rewrite rules once so /sv/ and /sv/.../ work (e.g. after theme update or first install).
 */
function ark_multilang_maybe_flush_rules() {
    if ( is_admin() ) {
        return;
    }
    if ( get_option( 'ark_multilang_rules_flushed', false ) ) {
        return;
    }
    flush_rewrite_rules();
    update_option( 'ark_multilang_rules_flushed', true );
}
add_action( 'init', 'ark_multilang_maybe_flush_rules', 99 );

/**
 * If user hits /sv/ or /sv/.../ and gets 404 (rules not flushed yet), flush and redirect so next load works.
 */
function ark_multilang_404_flush_and_redirect() {
    if ( ! is_404() ) {
        return;
    }
    $uri = isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '';
    $path = trim( parse_url( $uri, PHP_URL_PATH ), '/' );
    if ( $path !== 'sv' && strpos( $path, 'sv/' ) !== 0 ) {
        return;
    }
    flush_rewrite_rules();
    update_option( 'ark_multilang_rules_flushed', true );
    wp_safe_redirect( home_url( '/' . $path . '/' ) );
    exit;
}
add_action( 'template_redirect', 'ark_multilang_404_flush_and_redirect', 1 );

/**
 * Register query var for ark_lang
 */
function ark_multilang_query_vars( $vars ) {
    $vars[] = 'ark_lang';
    return $vars;
}
add_filter( 'query_vars', 'ark_multilang_query_vars' );

/**
 * Set ARK_LANG constant when request is Swedish (front-end only).
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

/**
 * When request has ark_lang=sv, ensure ARK_LANG is set and handle Swedish home.
 * This runs on 'request' so language is set before any template uses ark_t().
 */
function ark_multilang_request_swedish_home( $query_vars ) {
    if ( is_admin() ) {
        return $query_vars;
    }
    if ( empty( $query_vars['ark_lang'] ) || $query_vars['ark_lang'] !== ARK_LANG_SV ) {
        return $query_vars;
    }
    // Ensure Swedish is active for this request (in case parse_request ran in wrong order)
    if ( ! defined( 'ARK_LANG' ) ) {
        define( 'ARK_LANG', ARK_LANG_SV );
    }
    // Already requesting a specific page or post type (e.g. /sv/fly/, /sv/package/slug/)
    if ( ! empty( $query_vars['pagename'] ) || ! empty( $query_vars['post_type'] ) ) {
        return $query_vars;
    }
    // Swedish home: force main query to load the front page so content is shown
    $front_page_id = (int) get_option( 'page_on_front' );
    if ( get_option( 'show_on_front' ) === 'page' && $front_page_id > 0 ) {
        $query_vars['page_id'] = $front_page_id;
    }
    return $query_vars;
}
add_filter( 'request', 'ark_multilang_request_swedish_home', 10, 1 );

/**
 * Current language code: 'en' or 'sv'
 */
function ark_lang() {
    return defined( 'ARK_LANG' ) && ARK_LANG === ARK_LANG_SV ? ARK_LANG_SV : ARK_LANG_EN;
}

/**
 * Current path without language prefix (e.g. 'fly', 'about', '' for home)
 */
function ark_current_path() {
    $uri  = isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '';
    $uri  = strtok( $uri, '?' );
    $path = trim( parse_url( $uri, PHP_URL_PATH ), '/' );
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
 * URL for the current language (internal links). Use for all internal navigation.
 *
 * @param string $path Path with leading slash, e.g. '/', '/fly/', '/about/'
 * @return string Full URL
 */
function ark_url( $path ) {
    $path = $path === '' ? '/' : $path;
    if ( ark_lang() === ARK_LANG_SV ) {
        $path = ( $path === '/' ) ? '/sv/' : '/sv' . $path;
    }
    return home_url( $path );
}

/**
 * URL for the other language (for language switcher). Preserves current page.
 *
 * @param string $lang 'en' or 'sv'
 * @return string Full URL
 */
function ark_switch_url( $lang ) {
    $current = ark_current_path();
    if ( $lang === ARK_LANG_SV ) {
        return $current ? home_url( '/sv/' . $current . '/' ) : home_url( '/sv/' );
    }
    return $current ? home_url( '/' . $current . '/' ) : home_url( '/' );
}

/**
 * Load translation array for current language (cached in $GLOBALS['ark_translations']).
 */
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

/**
 * Get translated string by key. Use in templates instead of esc_html_e().
 *
 * @param string $key Key from languages/en.php or languages/sv.php
 * @return string
 */
function ark_t( $key ) {
    $translations = ark_load_translations();
    return isset( $translations[ $key ] ) ? $translations[ $key ] : $key;
}

/**
 * Output hreflang and canonical in head (SEO).
 */
function ark_multilang_seo_head() {
    if ( is_admin() || ! function_exists( 'ark_url' ) ) {
        return;
    }
    $current_path = ark_current_path();
    $base = $current_path ? '/' . $current_path . '/' : '/';
    $sv_base = $current_path ? '/sv/' . $current_path . '/' : '/sv/';
    $canonical = ark_lang() === ARK_LANG_SV ? home_url( $sv_base ) : home_url( $base );
    echo '<link rel="canonical" href="' . esc_url( $canonical ) . '">' . "\n";
    echo '<link rel="alternate" hreflang="en" href="' . esc_url( home_url( $base ) ) . '">' . "\n";
    echo '<link rel="alternate" hreflang="sv" href="' . esc_url( home_url( $sv_base ) ) . '">' . "\n";
    echo '<link rel="alternate" hreflang="x-default" href="' . esc_url( home_url( $base ) ) . '">' . "\n";
}
add_action( 'wp_head', 'ark_multilang_seo_head', 5 );

/**
 * Filter Umrah single and archive URLs so they use /sv/ when language is Swedish.
 */
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

/**
 * Filter Umrah archive URL for Swedish.
 */
function ark_multilang_umrah_archive_link( $url, $post_type ) {
    if ( ! function_exists( 'ark_lang' ) || ark_lang() !== ARK_LANG_SV || $post_type !== 'umrah' ) {
        return $url;
    }
    return home_url( '/sv/package/' );
}
add_filter( 'post_type_archive_link', 'ark_multilang_umrah_archive_link', 10, 2 );

/**
 * Rewrite nav menu item URLs to /sv/... when viewing in Swedish so menu clicks stay in Swedish.
 */
function ark_multilang_nav_menu_links( $items, $args ) {
    if ( ! function_exists( 'ark_lang' ) || ark_lang() !== ARK_LANG_SV ) {
        return $items;
    }
    $home = rtrim( home_url(), '/' );
    foreach ( $items as $item ) {
        if ( empty( $item->url ) ) {
            continue;
        }
        $item_host = parse_url( $item->url, PHP_URL_HOST );
        $home_host = parse_url( $home, PHP_URL_HOST );
        if ( $item_host !== $home_host ) {
            continue;
        }
        $path = trim( (string) parse_url( $item->url, PHP_URL_PATH ), '/' );
        if ( $path === '' || $path === 'sv' ) {
            $item->url = home_url( '/sv/' );
            continue;
        }
        if ( strpos( $path, 'sv/' ) === 0 ) {
            continue;
        }
        if ( $path === 'umrah' ) {
            $item->url = home_url( '/sv/package/' );
            continue;
        }
        if ( strpos( $path, 'umrah/' ) === 0 ) {
            $slug = substr( $path, 6 );
            $item->url = home_url( '/sv/package/' . $slug . '/' );
            continue;
        }
        $item->url = home_url( '/sv/' . $path . '/' );
    }
    return $items;
}
add_filter( 'wp_nav_menu_objects', 'ark_multilang_nav_menu_links', 10, 2 );

/**
 * Add body class and ensure html lang attribute for Swedish (helps debugging and accessibility).
 */
function ark_multilang_body_class( $classes ) {
    if ( function_exists( 'ark_lang' ) && ark_lang() === ARK_LANG_SV ) {
        $classes[] = 'ark-lang-sv';
    }
    return $classes;
}
add_filter( 'body_class', 'ark_multilang_body_class' );

/**
 * Set correct lang attribute on <html> for Swedish pages.
 */
function ark_multilang_html_lang( $output ) {
    if ( function_exists( 'ark_lang' ) && ark_lang() === ARK_LANG_SV ) {
        return 'lang="sv" dir="ltr"';
    }
    return $output;
}
add_filter( 'language_attributes', 'ark_multilang_html_lang' );

/**
 * Flush rewrite rules on theme activation so /sv/ works.
 */
function ark_multilang_activation() {
    ark_multilang_rewrite_rules();
    flush_rewrite_rules();
    delete_option( 'ark_multilang_rules_flushed' );
}
add_action( 'after_switch_theme', 'ark_multilang_activation' );
