<?php
/**
 * ARK Travelers – Legacy multilingual helpers (now neutral for TranslatePress)
 *
 * The original version of this file implemented a full URL-based multilingual
 * system (/sv/ prefix, custom rewrite rules, hreflang, body classes, etc.).
 * That logic has been removed so that language handling is delegated entirely
 * to a dedicated translation plugin (e.g. TranslatePress).
 *
 * What remains here are very small, language-neutral helpers so existing
 * templates that call ark_url() or ark_t() continue to work without errors.
 *
 * @package ARK_Travelers
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Current language code.
 *
 * Historically returned 'en' or 'sv'. Now always returns 'en' so that
 * templates relying on ark_lang() remain safe, while actual language
 * switching is handled by the translation plugin.
 *
 * @return string
 */
function ark_lang() {
    return 'en';
}

/**
 * Language-agnostic URL helper for internal links.
 *
 * Previously added /sv prefixes based on the current language. It is now a
 * thin wrapper around home_url() with no language-aware behaviour so that
 * translation plugins can manage URL structures themselves.
 *
 * @param string $path e.g. '/', '/fly/', '/about/'.
 * @return string
 */
function ark_url( $path ) {
    $path = $path === '' ? '/' : $path;
    return home_url( $path );
}

/**
 * Backwards-compatible translation helper.
 *
 * The original implementation loaded large translation arrays from
 * languages/en.php and languages/sv.php. Since language handling is now
 * offloaded to a plugin, this helper simply returns the given key as a
 * human-readable string using WordPress i18n, allowing plugins like
 * TranslatePress to translate the rendered output.
 *
 * Usage example in templates:
 *   echo esc_html( ark_t( 'Home' ) );
 *
 * @param string $key Human-readable English string.
 * @return string
 */
function ark_t( $key ) {
    return __( $key, 'ark-travelers' );
}

/**
 * Backwards-compatible switch URL helper.
 *
 * Kept only so existing calls do not fatal. Now simply returns home_url()
 * for the requested language code without applying any custom routing.
 *
 * @param string $lang Language code like 'en' or 'sv'.
 * @return string
 */
function ark_switch_url( $lang ) {
    // Let the translation plugin handle language-specific URLs.
    return home_url( '/' );
}

