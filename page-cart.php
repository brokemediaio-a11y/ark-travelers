<?php
/**
 * Cart – WooCommerce cart page. Hero + content (matches Contact/About style).
 *
 * @package ARK_Travelers
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main id="main" class="ark-main ark-main-cart site-main">
	<section class="ark-woo-hero ark-cart-hero">
		<div class="ark-container">
			<h1 class="ark-woo-hero-title"><?php echo esc_html__( 'Your Cart', 'ark-travelers' ); ?></h1>
			<p class="ark-woo-hero-subtitle"><?php echo esc_html__( 'Review your items and proceed to checkout', 'ark-travelers' ); ?></p>
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
