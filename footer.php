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
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="ark-footer-logo">
                <?php if ( has_custom_logo() ) : ?>
                    <?php the_custom_logo(); ?>
                <?php else : ?>
                    <img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/ark%20final%20logo%20no%20bg.png' ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" class="ark-logo-img" width="180" height="48">
                <?php endif; ?>
            </a>
            <p class="ark-footer-tagline"><?php echo esc_html( get_bloginfo( 'description' ) ); ?></p>
            <div class="ark-footer-social" aria-label="<?php echo esc_attr__( 'Social links', 'ark-travelers' ); ?>">
                <a href="https://facebook.com" target="_blank" rel="noopener noreferrer" aria-label="Facebook"><svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg></a>
                <a href="https://instagram.com" target="_blank" rel="noopener noreferrer" aria-label="Instagram"><svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg></a>
            </div>
        </div>

        <div class="ark-footer-col">
            <h4><?php echo esc_html__( 'Quick Links', 'ark-travelers' ); ?></h4>
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
                    <li><a href="<?php echo esc_url( home_url( '/umrah/' ) ); ?>"><?php echo esc_html__( 'Umrah Packages', 'ark-travelers' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/fly/' ) ); ?>"><?php echo esc_html__( 'FLY', 'ark-travelers' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>"><?php echo esc_html__( 'About', 'ark-travelers' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php echo esc_html__( 'Contact', 'ark-travelers' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/contact/#faq' ) ); ?>"><?php echo esc_html__( 'FAQ', 'ark-travelers' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/privacy/' ) ); ?>"><?php echo esc_html__( 'Privacy', 'ark-travelers' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/terms/' ) ); ?>"><?php echo esc_html__( 'Terms', 'ark-travelers' ); ?></a></li>
                </ul>
                <?php
            }
            ?>
        </div>

        <div class="ark-footer-col">
            <h4><?php echo esc_html__( 'Support', 'ark-travelers' ); ?></h4>
            <ul class="ark-footer-support-list">
                <li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php echo esc_html__( 'Customer Service', 'ark-travelers' ); ?></a></li>
                <li><a href="tel:+46700101401"><?php echo esc_html__( 'Emergency Contact', 'ark-travelers' ); ?>: +46 700 101 401</a></li>
                <li><a href="<?php echo esc_url( home_url( '/gdpr/' ) ); ?>"><?php echo esc_html__( 'GDPR Info', 'ark-travelers' ); ?></a></li>
                <li><a href="<?php echo esc_url( home_url( '/contact/?subject=complaint' ) ); ?>"><?php echo esc_html__( 'Complaint Form', 'ark-travelers' ); ?></a></li>
            </ul>
        </div>

        <div class="ark-footer-col ark-footer-newsletter">
            <h4><?php echo esc_html__( 'Newsletter', 'ark-travelers' ); ?></h4>
            <form class="ark-newsletter-form" action="#" method="post" aria-label="<?php echo esc_attr__( 'Newsletter signup', 'ark-travelers' ); ?>">
                <input type="email" name="email" placeholder="<?php echo esc_attr__( 'Your email', 'ark-travelers' ); ?>" required aria-label="<?php echo esc_attr__( 'Email address', 'ark-travelers' ); ?>">
                <button type="submit" class="btn-primary"><?php echo esc_html__( 'Submit', 'ark-travelers' ); ?></button>
            </form>
        </div>
    </div>

    <div class="ark-footer-bottom">
        <span>&copy; 2026 ARK Travelers AB</span>
        <span><?php echo esc_html__( 'Stockholm, Sweden · Kopparbacken 11, 163 46', 'ark-travelers' ); ?></span>
    </div>
</footer>

<?php
// Floating WhatsApp button (site-wide)
$ark_whatsapp_phone = '46700101401'; // +46 700 101 401
$ark_whatsapp_text  = rawurlencode( "Hi ARK Travelers! I'm interested in international flight tickets and/or visa assistance. Can you help me?" );
$ark_whatsapp_url   = 'https://wa.me/' . $ark_whatsapp_phone . '?text=' . $ark_whatsapp_text;
?>
<a
    class="ark-whatsapp-float"
    href="<?php echo esc_url( $ark_whatsapp_url ); ?>"
    target="_blank"
    rel="noopener noreferrer"
    aria-label="<?php echo esc_attr__( 'Chat with ARK Travelers on WhatsApp', 'ark-travelers' ); ?>"
>
    <span class="screen-reader-text"><?php echo esc_html__( 'Chat on WhatsApp', 'ark-travelers' ); ?></span>
    <svg class="ark-whatsapp-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" aria-hidden="true" focusable="false">
        <path fill="currentColor" d="M19.11 17.73c-.26-.13-1.55-.77-1.79-.86-.24-.09-.41-.13-.59.13-.18.26-.68.86-.84 1.03-.15.18-.31.2-.57.07-.26-.13-1.11-.41-2.11-1.31-.78-.7-1.31-1.56-1.46-1.82-.15-.26-.02-.4.11-.53.12-.12.26-.31.39-.46.13-.15.18-.26.26-.44.09-.18.04-.33-.02-.46-.07-.13-.59-1.43-.81-1.96-.21-.51-.43-.44-.59-.44-.15 0-.33-.02-.5-.02-.18 0-.46.07-.7.33-.24.26-.92.9-.92 2.2s.94 2.55 1.07 2.73c.13.18 1.86 2.84 4.5 3.98.63.27 1.12.43 1.5.55.63.2 1.2.17 1.65.1.5-.07 1.55-.63 1.77-1.23.22-.6.22-1.12.15-1.23-.07-.11-.24-.18-.5-.31z"/>
        <path fill="currentColor" d="M16.02 3C8.84 3 3 8.84 3 16.02c0 2.31.61 4.56 1.77 6.55L3 29l6.61-1.69c1.9 1.04 4.04 1.59 6.41 1.59C23.2 28.9 29 23.06 29 16.02 29 8.84 23.2 3 16.02 3zm0 23.48c-2.08 0-4.06-.56-5.79-1.61l-.41-.24-3.92 1 1.05-3.82-.27-.43a10.33 10.33 0 0 1-1.6-5.36c0-5.73 4.68-10.41 10.94-10.41 6.03 0 10.57 4.68 10.57 10.41 0 5.73-4.54 10.46-10.57 10.46z"/>
    </svg>
</a>

<?php wp_footer(); ?>
</body>
</html>
