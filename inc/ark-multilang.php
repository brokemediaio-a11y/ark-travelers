<?php
/**
 * ARK Travelers – Legacy multilingual helpers (now neutral for TranslatePress)
 *
 * This file previously contained a custom URL-based multilingual system.
 * It has been refactored to provide compatibility functions that wrap
 * standard WordPress i18n, allowing TranslatePress to manage translations.
 *
 * @package ARK_Travelers
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Current language code: always 'en' for TranslatePress to take over.
 *
 * @return string Always returns 'en'.
 */
function ark_lang() {
    return 'en';
}

/**
 * Current page path without the language prefix (now always returns raw path).
 *
 * @return string Current page path.
 */
function ark_current_path() {
    $uri  = isset( $_SERVER['REQUEST_URI'] ) ? (string) $_SERVER['REQUEST_URI'] : '';
    $path = (string) parse_url( $uri, PHP_URL_PATH );
    $home_path = trim( (string) parse_url( home_url( '/' ), PHP_URL_PATH ), '/' );
    if ( $home_path !== '' ) {
        if ( $path === $home_path ) {
            return '';
        }
        if ( strpos( $path, $home_path . '/' ) === 0 ) {
            return trim( substr( $path, strlen( $home_path ) + 1 ), '/' );
        }
    }
    return trim( $path, '/' );
}

/**
 * Language-prefixed URL for internal links (now just home_url()).
 * TranslatePress will handle URL rewriting.
 *
 * @param string $path e.g. '/', '/fly/', '/about/'
 * @return string
 */
function ark_url( $path ) {
    $path = $path === '' ? '/' : $path;
    return home_url( $path );
}

/**
 * URL for the other language (now just home_url() for current page).
 * TranslatePress will handle language switching.
 *
 * @param string $lang 'en' or 'sv'
 * @return string
 */
function ark_switch_url( $lang ) {
    return home_url( ark_current_path() );
}

/**
 * Translations helper (now uses standard WordPress i18n).
 * This wraps __() so TranslatePress can detect and translate strings.
 *
 * Example:
 *   echo esc_html( ark_t( 'Home' ) );
 *
 * @param string $key Human-readable English string.
 * @return string
 */
function ark_t( $key ) {
    return __( $key, 'ark-travelers' );
}
