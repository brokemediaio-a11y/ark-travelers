<?php
/**
 * Checkout Form
 *
 * Override: ARK Travelers – two-column layout (billing left, order review right, sticky summary).
 * All WooCommerce hooks and form structure preserved.
 *
 * @see https://github.com/woocommerce/woocommerce/blob/trunk/plugins/woocommerce/templates/checkout/form-checkout.php
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_checkout_form', $checkout );

if ( $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) );
	return;
}
?>

<div class="ark-container ark-woocommerce-wrap woocommerce">
<form name="checkout" method="post" class="checkout woocommerce-checkout ark-checkout-form" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">
	<div class="ark-checkout-layout">
		<?php if ( $checkout->get_checkout_fields() ) : ?>
			<?php
			$show_shipping = WC()->cart && WC()->cart->needs_shipping_address() && ! empty( $checkout->get_checkout_fields( 'shipping' ) );
			?>
			<div class="ark-checkout-details ark-checkout-form ark-card">
				<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>
				<div class="col2-set<?php echo $show_shipping ? '' : ' ark-col2-single'; ?>">
					<div class="col-1">
						<?php do_action( 'woocommerce_checkout_billing' ); ?>
					</div>
					<?php if ( $show_shipping ) : ?>
						<div class="col-2">
							<?php do_action( 'woocommerce_checkout_shipping' ); ?>
						</div>
					<?php endif; ?>
				</div>
				<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>
			</div>
		<?php endif; ?>

		<div class="ark-order-review-wrap ark-order-summary">
			<?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>
			<h3 id="order_review_heading"><?php esc_html_e( 'Your order', 'woocommerce' ); ?></h3>
			<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>
			<div id="order_review" class="woocommerce-checkout-review-order ark-order-review ark-card">
				<?php do_action( 'woocommerce_checkout_order_review' ); ?>
			</div>
			<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
		</div>
	</div>
</form>

</div>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
