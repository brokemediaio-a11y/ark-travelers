<?php
/**
 * Site header – fixed nav, scroll effect, cart, mobile menu
 *
 * @package ARK_Travelers
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>
<body <?php body_class( 'ark-body' ); ?>>
<?php wp_body_open(); ?>

<header id="masthead" class="ark-header" role="banner" aria-label="<?php echo esc_attr__( 'Site header', 'ark-travelers' ); ?>">
    <div class="ark-header-inner">
        <div class="ark-header-logo">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="ark-logo-link">
                <?php if ( has_custom_logo() ) : ?>
                    <?php the_custom_logo(); ?>
                <?php else : ?>
                    <?php
                    $logo_no_bg = get_stylesheet_directory_uri() . '/assets/images/ark%20final%20logo%20no%20bg.png';
                    $logo_bg    = get_stylesheet_directory_uri() . '/assets/images/ark%20final%20logo.png';
                    ?>
                    <img src="<?php echo esc_url( $logo_no_bg ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" class="ark-logo-img ark-logo-default" width="180" height="48">
                    <img src="<?php echo esc_url( $logo_bg ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" class="ark-logo-img ark-logo-white-bg" width="180" height="48">
                <?php endif; ?>
            </a>
        </div>

        <nav id="ark-primary-nav" class="ark-nav" aria-label="<?php echo esc_attr__( 'Primary navigation', 'ark-travelers' ); ?>">
            <?php
            if ( has_nav_menu( 'primary' ) ) {
                wp_nav_menu( array(
                    'theme_location' => 'primary',
                    'menu_class'     => 'nav-menu',
                    'container'      => false,
                    'items_wrap'     => '<ul class="nav-menu" id="navMenu">%3$s</ul>',
                ) );
            } else {
                ark_fallback_menu();
            }
            ?>
        </nav>

        <div class="ark-header-actions">
            <a href="<?php echo esc_url( function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : '#' ); ?>" class="ark-header-cart" aria-label="<?php echo esc_attr__( 'Shopping cart', 'ark-travelers' ); ?>">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
                <span class="ark-cart-count" aria-live="polite">0</span>
            </a>

            <button type="button" class="ark-nav-toggle" aria-label="<?php echo esc_attr__( 'Toggle menu', 'ark-travelers' ); ?>" aria-expanded="false" aria-controls="ark-primary-nav">
                <span class="ark-nav-toggle-bar"></span>
                <span class="ark-nav-toggle-bar"></span>
                <span class="ark-nav-toggle-bar"></span>
            </button>
        </div>
    </div>
</header>

<div id="content" class="ark-content site-content">
