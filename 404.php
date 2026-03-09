<?php
/**
 * 404 error page – centered content, search, quick links
 *
 * @package ARK_Travelers
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();
?>

<main id="main" class="ark-main ark-main-404 site-main">
    <section class="ark-404-content">
        <div class="ark-404-inner">
            <h1 class="ark-404-title">404</h1>
            <h2 class="ark-404-heading"><?php esc_html_e( 'Page Not Found', 'ark-travelers' ); ?></h2>
            <p class="ark-404-text"><?php esc_html_e( "The page you're looking for doesn't exist or has been moved.", 'ark-travelers' ); ?></p>
            <form role="search" method="get" class="ark-404-search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                <label for="ark-404-s"><span class="screen-reader-text"><?php esc_html_e( 'Search for:', 'ark-travelers' ); ?></span></label>
                <input type="search" id="ark-404-s" name="s" placeholder="<?php esc_attr_e( 'Search…', 'ark-travelers' ); ?>">
                <button type="submit" class="btn-primary"><?php esc_html_e( 'Search', 'ark-travelers' ); ?></button>
            </form>
            <nav class="ark-404-links" aria-label="<?php esc_attr_e( 'Quick links', 'ark-travelers' ); ?>">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'ark-travelers' ); ?></a>
                <a href="<?php echo esc_url( home_url( '/umrah/' ) ); ?>"><?php esc_html_e( 'Umrah Packages', 'ark-travelers' ); ?></a>
                <a href="<?php echo esc_url( home_url( '/fly/' ) ); ?>"><?php esc_html_e( 'FLY', 'ark-travelers' ); ?></a>
                <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Contact', 'ark-travelers' ); ?></a>
            </nav>
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn-primary"><?php esc_html_e( 'Back to Homepage', 'ark-travelers' ); ?></a>
        </div>
    </section>
</main>

<?php
get_footer();
