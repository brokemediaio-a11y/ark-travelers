<?php
/**
 * Hero Video Scrubbing Section
 * Scroll-based zoom animation using video frames from airplane zip folder
 * Hero content fades in when animation completes on last frame
 *
 * @package ARK_Travelers
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<section id="hero-video-scrub" class="hero-video-scrubbing">
    <!-- Loading Screen -->
    <div class="video-scrub-loading" id="videoScrubLoading">
            <div class="loading-content">
            <div class="loading-spinner"></div>
            <p class="loading-text"><?php echo esc_html( ark_t( 'Loading Experience' ) ); ?></p>
            <div class="loading-progress">
                <div class="progress-bar" id="loadingProgress"></div>
            </div>
            <p class="loading-percentage" id="loadingPercentage">0%</p>
        </div>
    </div>
    
    <!-- Video Scrubbing Canvas -->
    <div class="video-scrub-container" id="videoScrubContainer">
        <canvas id="videoCanvas" class="video-canvas"></canvas>
        
        <!-- Hero Overlay Content (fades in on last frame)
             Reuses the Fly page airplane hero banner styling so the homepage
             hero matches the Fly page look (full-width, full-height banner). -->
        <div class="video-overlay-content airplane-scroll-hero-cta" id="heroContentFinal">
            <div class="airplane-hero-cta-content">
                <p class="airplane-hero-eyebrow">
                    <?php echo esc_html( ark_t( 'Your journey begins at the window.' ) ); ?>
                </p>
                <h1 class="airplane-hero-title">
                    <?php echo esc_html( ark_t( 'Book Your' ) ); ?>
                    <span class="airplane-hero-highlight">
                        <?php echo esc_html( ark_t( 'Flight' ) ); ?>
                    </span>
                    <?php echo esc_html( ark_t( '& Travel with Confidence' ) ); ?>
                </h1>
                <p class="airplane-hero-subtitle">
                    <?php echo esc_html( ark_t( 'Seamless flight bookings, Umrah packages, and 24/7 support. Your journey starts here.' ) ); ?>
                </p>
                <div class="airplane-hero-ctas">
                    <a href="<?php echo esc_url( ark_url( '/fly/' ) ); ?>" class="btn-airplane-primary">
                        <?php echo esc_html( ark_t( 'Book Flights' ) ); ?>
                    </a>
                    <a href="<?php echo esc_url( ark_url( '/umrah/' ) ); ?>" class="btn-airplane-outline">
                        <?php echo esc_html( ark_t( 'Umrah Packages' ) ); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Scroll Indicator -->
    <div class="scroll-indicator" data-scroll-hide id="scrollIndicator">
        <div class="scroll-mouse">
            <div class="scroll-wheel"></div>
        </div>
        <p><?php echo esc_html( ark_t( 'scroll_down' ) ); ?></p>
    </div>
</section>
