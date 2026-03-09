<?php
/**
 * Fly Page Hero Banner - Static hero with parallax background
 *
 * @package ARK_Travelers
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<section class="ark-fly-hero-banner" id="ark-fly-hero">
    <!-- Parallax Background -->
    <div class="ark-fly-hero-parallax">
        <div class="ark-parallax-layer ark-parallax-speed-slow" data-speed="0.5"></div>
    </div>
    
    <!-- Form box centered on top of image -->
    <div class="ark-fly-hero-form-wrap">
        <?php get_template_part( 'template-parts/fly-form' ); ?>
    </div>
</section>
