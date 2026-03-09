<?php
/**
 * Checkout – WooCommerce checkout page. Hero + content (matches Contact/About style).
 *
 * @package ARK_Travelers
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main id="main" class="ark-main ark-main-checkout site-main">
	<section class="ark-woo-hero ark-checkout-hero">
		<div class="ark-container">
			<h1 class="ark-woo-hero-title"><?php echo esc_html( function_exists( 'ark_t' ) ? ark_t( 'checkout_hero_title' ) : __( 'Checkout', 'ark-travelers' ) ); ?></h1>
			<p class="ark-woo-hero-subtitle"><?php echo esc_html( function_exists( 'ark_t' ) ? ark_t( 'checkout_hero_subtitle' ) : __( 'Complete your order securely.', 'ark-travelers' ) ); ?></p>
		</div>
	</section>
	<?php
	while ( have_posts() ) :
		the_post();
		the_content();
	endwhile;
	?>
</main>

<?php
get_footer();
