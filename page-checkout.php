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
			<h1 class="ark-woo-hero-title"><?php echo esc_html__( 'Checkout', 'ark-travelers' ); ?></h1>
			<p class="ark-woo-hero-subtitle"><?php echo esc_html__( 'Complete your booking with secure payment', 'ark-travelers' ); ?></p>
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
