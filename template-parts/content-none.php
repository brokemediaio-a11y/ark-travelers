<?php
/**
 * Template part for displaying a message when no content is available.
 *
 * @package ARK_Travelers
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section class="ark-no-results">
	<p><?php echo esc_html( function_exists( 'ark_t' ) ? ark_t( 'nothing_found' ) : __( 'Nothing found.', 'ark-travelers' ) ); ?></p>
</section>
