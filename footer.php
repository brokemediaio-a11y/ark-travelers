<?php
/**
 * Site footer – 4 columns, newsletter, bottom bar
 *
 * @package ARK_Travelers
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

</div><!-- #content -->

<footer id="colophon" class="ark-footer" role="contentinfo">
    <div class="ark-footer-grid">
        <div class="ark-footer-brand">
            <a href="<?php echo esc_url( function_exists( 'ark_url' ) ? ark_url( '/' ) : home_url( '/' ) ); ?>" rel="home" class="ark-footer-logo">
                <?php if ( has_custom_logo() ) : ?>
                    <?php the_custom_logo(); ?>
                <?php else : ?>
                    <img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/ark%20final%20logo%20no%20bg.png' ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" class="ark-logo-img" width="180" height="48">
                <?php endif; ?>
            </a>
            <p class="ark-footer-tagline"><?php echo esc_html( get_bloginfo( 'description' ) ); ?></p>
            <div class="ark-footer-social" aria-label="<?php echo esc_attr( function_exists( 'ark_t' ) ? ark_t( 'footer_social_aria' ) : __( 'Social links', 'ark-travelers' ) ); ?>">
                <a href="https://facebook.com" target="_blank" rel="noopener noreferrer" aria-label="Facebook"><svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg></a>
                <a href="https://instagram.com" target="_blank" rel="noopener noreferrer" aria-label="Instagram"><svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg></a>
            </div>
        </div>

        <div class="ark-footer-col">
            <h4><?php echo esc_html( function_exists( 'ark_t' ) ? ark_t( 'footer_quick_links' ) : __( 'Quick Links', 'ark-travelers' ) ); ?></h4>
            <?php
            if ( has_nav_menu( 'footer' ) ) {
                wp_nav_menu( array(
                    'theme_location' => 'footer',
                    'menu_class'     => 'ark-footer-menu',
                    'container'      => false,
                ) );
            } else {
                ?>
                <ul class="ark-footer-menu">
                    <li><a href="<?php echo esc_url( function_exists( 'ark_url' ) ? ark_url( '/umrah/' ) : home_url( '/umrah/' ) ); ?>"><?php echo esc_html( function_exists( 'ark_t' ) ? ark_t( 'footer_umrah' ) : __( 'Umrah Packages', 'ark-travelers' ) ); ?></a></li>
                    <li><a href="<?php echo esc_url( function_exists( 'ark_url' ) ? ark_url( '/fly/' ) : home_url( '/fly/' ) ); ?>"><?php echo esc_html( function_exists( 'ark_t' ) ? ark_t( 'footer_fly' ) : __( 'FLY', 'ark-travelers' ) ); ?></a></li>
                    <li><a href="<?php echo esc_url( function_exists( 'ark_url' ) ? ark_url( '/about/' ) : home_url( '/about/' ) ); ?>"><?php echo esc_html( function_exists( 'ark_t' ) ? ark_t( 'footer_about' ) : __( 'About', 'ark-travelers' ) ); ?></a></li>
                    <li><a href="<?php echo esc_url( function_exists( 'ark_url' ) ? ark_url( '/contact/' ) : home_url( '/contact/' ) ); ?>"><?php echo esc_html( function_exists( 'ark_t' ) ? ark_t( 'footer_contact' ) : __( 'Contact', 'ark-travelers' ) ); ?></a></li>
                    <li><a href="<?php echo esc_url( function_exists( 'ark_url' ) ? ark_url( '/contact/#faq' ) : home_url( '/contact/#faq' ) ); ?>"><?php echo esc_html( function_exists( 'ark_t' ) ? ark_t( 'footer_faq' ) : __( 'FAQ', 'ark-travelers' ) ); ?></a></li>
                    <li><a href="<?php echo esc_url( function_exists( 'ark_url' ) ? ark_url( '/privacy/' ) : home_url( '/privacy/' ) ); ?>"><?php echo esc_html( function_exists( 'ark_t' ) ? ark_t( 'footer_privacy' ) : __( 'Privacy', 'ark-travelers' ) ); ?></a></li>
                    <li><a href="<?php echo esc_url( function_exists( 'ark_url' ) ? ark_url( '/terms/' ) : home_url( '/terms/' ) ); ?>"><?php echo esc_html( function_exists( 'ark_t' ) ? ark_t( 'footer_terms' ) : __( 'Terms', 'ark-travelers' ) ); ?></a></li>
                </ul>
                <?php
            }
            ?>
        </div>

        <div class="ark-footer-col">
            <h4><?php echo esc_html( function_exists( 'ark_t' ) ? ark_t( 'footer_support' ) : __( 'Support', 'ark-travelers' ) ); ?></h4>
            <ul class="ark-footer-support-list">
                <li><a href="<?php echo esc_url( function_exists( 'ark_url' ) ? ark_url( '/contact/' ) : home_url( '/contact/' ) ); ?>"><?php echo esc_html( function_exists( 'ark_t' ) ? ark_t( 'footer_customer_service' ) : __( 'Customer Service', 'ark-travelers' ) ); ?></a></li>
                <li><a href="tel:+46000000000"><?php echo esc_html( function_exists( 'ark_t' ) ? ark_t( 'footer_emergency' ) : __( 'Emergency Contact', 'ark-travelers' ) ); ?></a></li>
                <li><a href="<?php echo esc_url( function_exists( 'ark_url' ) ? ark_url( '/gdpr/' ) : home_url( '/gdpr/' ) ); ?>"><?php echo esc_html( function_exists( 'ark_t' ) ? ark_t( 'footer_gdpr' ) : __( 'GDPR Info', 'ark-travelers' ) ); ?></a></li>
                <li><a href="<?php echo esc_url( function_exists( 'ark_url' ) ? ark_url( '/contact/?subject=complaint' ) : home_url( '/contact/?subject=complaint' ) ); ?>"><?php echo esc_html( function_exists( 'ark_t' ) ? ark_t( 'footer_complaint' ) : __( 'Complaint Form', 'ark-travelers' ) ); ?></a></li>
            </ul>
        </div>

        <div class="ark-footer-col ark-footer-newsletter">
            <h4><?php echo esc_html( function_exists( 'ark_t' ) ? ark_t( 'footer_newsletter' ) : __( 'Newsletter', 'ark-travelers' ) ); ?></h4>
            <form class="ark-newsletter-form" action="#" method="post" aria-label="<?php echo esc_attr( function_exists( 'ark_t' ) ? ark_t( 'footer_newsletter_aria' ) : __( 'Newsletter signup', 'ark-travelers' ) ); ?>">
                <input type="email" name="email" placeholder="<?php echo esc_attr( function_exists( 'ark_t' ) ? ark_t( 'footer_email_placeholder' ) : __( 'Your email', 'ark-travelers' ) ); ?>" required aria-label="<?php echo esc_attr( function_exists( 'ark_t' ) ? ark_t( 'footer_email_aria' ) : __( 'Email address', 'ark-travelers' ) ); ?>">
                <button type="submit" class="btn-primary"><?php echo esc_html( function_exists( 'ark_t' ) ? ark_t( 'footer_submit' ) : __( 'Submit', 'ark-travelers' ) ); ?></button>
            </form>
        </div>
    </div>

    <div class="ark-footer-bottom">
        <span>&copy; 2026 ARK Travelers AB &middot; <?php echo esc_html( function_exists( 'ark_t' ) ? ark_t( 'footer_org_nr' ) : __( 'Org.nr: 556XXX-XXXX', 'ark-travelers' ) ); ?></span>
        <span><?php echo esc_html( function_exists( 'ark_t' ) ? ark_t( 'footer_address' ) : __( 'Street 12, Stockholm, Sweden', 'ark-travelers' ) ); ?></span>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
