<?php
/**
 * Airplane Scroll Section - Canvas-based scroll-driven animation
 * Similar to React implementation: wheel events drive frames, scroll bar doesn't move until all frames covered
 *
 * @package ARK_Travelers
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<section class="airplane-scroll panel" id="airplane-scroll" aria-label="<?php echo esc_attr( ark_t( 'Airplane window scroll experience' ) ); ?>">
    <!-- Loading State -->
    <div class="airplane-scroll-sticky airplane-scroll-spinner-wrap" id="airplane-scroll-loading">
        <div class="airplane-scroll-spinner" aria-hidden="true"></div>
        <p class="airplane-scroll-loading-text"><?php echo esc_html( ark_t( 'Preparing your journey…' ) ); ?></p>
        <div class="airplane-scroll-progress-bar">
            <div class="airplane-scroll-progress-fill" id="airplane-scroll-progress-fill"></div>
        </div>
        <p class="airplane-scroll-loading-pct" id="airplane-scroll-loading-pct">0%</p>
    </div>

    <!-- Error State -->
    <div class="airplane-scroll-sticky airplane-scroll-error-inner" id="airplane-scroll-error" style="display: none;">
        <p><?php echo esc_html( ark_t( 'Could not load the experience.' ) ); ?></p>
        <button type="button" class="airplane-scroll-retry-btn" id="airplane-scroll-retry-btn">
            <?php echo esc_html( ark_t( 'Retry' ) ); ?>
        </button>
    </div>

    <!-- Main Canvas Container -->
    <div class="airplane-scroll-sticky" id="airplane-scroll-sticky" style="display: none;">
        <canvas id="airplane-scroll-canvas" class="airplane-scroll-canvas" aria-hidden="true"></canvas>
        
        <!-- Overlays -->
        <div class="airplane-scroll-overlays">
            <!-- Copy at 0% -->
            <div class="airplane-scroll-copy airplane-scroll-copy-0" id="airplane-copy-0">
                <h2 class="airplane-scroll-title"><?php echo esc_html( ark_t( 'Your journey begins at the window.' ) ); ?></h2>
            </div>

            <!-- Copy at 30% -->
            <div class="airplane-scroll-copy airplane-scroll-copy-30" id="airplane-copy-30" style="opacity: 0;">
                <p class="airplane-scroll-body"><?php echo esc_html( ark_t( 'From your seat…' ) ); ?></p>
            </div>

            <!-- Copy at 60% -->
            <div class="airplane-scroll-copy airplane-scroll-copy-60" id="airplane-copy-60" style="opacity: 0;">
                <p class="airplane-scroll-body"><?php echo esc_html( ark_t( '…to the world beyond.' ) ); ?></p>
            </div>

            <!-- Hero CTA (75-90%) -->
            <div class="airplane-scroll-hero-cta" id="airplane-hero-cta" style="opacity: 0;">
                <div class="airplane-hero-cta-content">
                    <p class="airplane-hero-eyebrow"><?php echo esc_html( ark_t( 'Sweden\'s Premier Travel & Flight Booking Agency' ) ); ?></p>
                    <h1 class="airplane-hero-title">
                        <?php echo esc_html( ark_t( 'Book Your' ) ); ?> 
                        <span class="airplane-hero-highlight"><?php echo esc_html( ark_t( 'Flight' ) ); ?></span> 
                        <?php echo esc_html( ark_t( '& Travel with Confidence' ) ); ?>
                    </h1>
                    <p class="airplane-hero-subtitle"><?php echo esc_html( ark_t( 'Seamless flight bookings, Umrah packages, and 24/7 support. Your journey starts here.' ) ); ?></p>
                    <p class="airplane-hero-cta-text"><?php echo esc_html( ark_t( 'Request your flight in seconds.' ) ); ?></p>
                    <div class="airplane-hero-ctas">
                        <a href="<?php echo esc_url( ark_url( '/fly/' ) ); ?>" class="btn-airplane-primary"><?php echo esc_html( ark_t( 'Book Flights' ) ); ?></a>
                        <a href="<?php echo esc_url( ark_url( '/umrah/' ) ); ?>" class="btn-airplane-outline"><?php echo esc_html( ark_t( 'Umrah Packages' ) ); ?></a>
                    </div>
                </div>
            </div>

            <!-- Copy at 90% (reserved for optional copy) -->
            <div class="airplane-scroll-copy airplane-scroll-copy-90" id="airplane-copy-90" style="opacity: 0;" aria-hidden="true"></div>
        </div>
    </div>
</section>
