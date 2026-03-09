<?php
/**
 * Testimonials – Polaroid Wall of Memories
 *
 * @package ARK_Travelers
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$testimonials = array(
    array( 'name' => 'Ahmed K.', 'location' => 'Stockholm', 'date' => 'Ramadan 2025', 'categories' => array( 'ramadan', 'family' ), 'verified' => true, 'video' => false, 'img' => 'https://images.unsplash.com/photo-1511895426328-dc8714191300?w=400&h=400&fit=crop' ),
    array( 'name' => 'Fatima L.', 'location' => 'Malmö', 'date' => 'December 2024', 'categories' => array( 'family' ), 'verified' => true, 'video' => true, 'img' => 'https://images.unsplash.com/photo-1547483238-f400e65ccd56?w=400&h=400&fit=crop' ),
    array( 'name' => 'Ibrahim M.', 'location' => 'Göteborg', 'date' => 'October 2024', 'categories' => array( 'family' ), 'verified' => true, 'video' => false, 'img' => 'https://images.unsplash.com/photo-1506929562872-bb421503ef21?w=400&h=400&fit=crop' ),
    array( 'name' => 'Sara N.', 'location' => 'Uppsala', 'date' => 'March 2025', 'categories' => array( 'first-time' ), 'verified' => true, 'video' => false, 'img' => 'https://images.unsplash.com/photo-1564769625905-b8d0e2c2c8e5?w=400&h=400&fit=crop' ),
    array( 'name' => 'Omar & Layla', 'location' => 'Stockholm', 'date' => 'January 2025', 'categories' => array( 'hajj-prep', 'family' ), 'verified' => true, 'video' => false, 'img' => 'https://images.unsplash.com/photo-1529156069898-49953e7b2a2a?w=400&h=400&fit=crop' ),
    array( 'name' => 'Yusuf A.', 'location' => 'Malmö', 'date' => 'Ramadan 2025', 'categories' => array( 'ramadan' ), 'verified' => false, 'video' => false, 'img' => 'https://images.unsplash.com/photo-1518684079-3c2d00c513ff?w=400&h=400&fit=crop' ),
    array( 'name' => 'Amina & Hassan', 'location' => 'Göteborg', 'date' => 'November 2024', 'categories' => array( 'family', 'first-time' ), 'verified' => true, 'video' => true, 'img' => 'https://images.unsplash.com/photo-1605649487212-47bdab064df7?w=400&h=400&fit=crop' ),
    array( 'name' => 'Khalid R.', 'location' => 'Stockholm', 'date' => 'February 2025', 'categories' => array( 'hajj-prep' ), 'verified' => true, 'video' => false, 'img' => 'https://images.unsplash.com/photo-1488646953014-85cb44e25828?w=400&h=400&fit=crop' ),
);
?>
<!-- Testimonials – Polaroid Wall of Memories -->
<section class="ark-section testimonials-polaroid ark-section-parallax" id="ark-testimonials" aria-labelledby="testimonials-heading" data-parallax-section>
    <div class="testimonials-polaroid-bg"></div>
    <div class="polaroid-floating-quotes" aria-hidden="true">
        <span class="polaroid-quote-deco polaroid-quote-deco-1">"</span>
        <span class="polaroid-quote-deco polaroid-quote-deco-2">"</span>
    </div>
    <div class="ark-container">
        <header class="testimonials-header">
            <h2 id="testimonials-heading" class="testimonials-title"><?php echo esc_html( ark_t( 'testimonials_title' ) ); ?></h2>
            <p class="testimonials-subtitle"><?php echo esc_html( ark_t( 'testimonials_subtitle' ) ); ?></p>
        </header>

        <div class="testimonials-filters testimonials-filters-desktop" role="tablist" aria-label="<?php echo esc_attr( ark_t( 'testimonials_filter_aria' ) ); ?>">
            <button type="button" class="testimonials-filter is-active" data-filter="all" aria-pressed="true"><?php echo esc_html( ark_t( 'testimonials_all' ) ); ?></button>
            <button type="button" class="testimonials-filter" data-filter="ramadan" aria-pressed="false"><?php echo esc_html( ark_t( 'testimonials_ramadan' ) ); ?></button>
            <button type="button" class="testimonials-filter" data-filter="family" aria-pressed="false"><?php echo esc_html( ark_t( 'testimonials_family' ) ); ?></button>
            <button type="button" class="testimonials-filter" data-filter="first-time" aria-pressed="false"><?php echo esc_html( ark_t( 'testimonials_first_time' ) ); ?></button>
            <button type="button" class="testimonials-filter" data-filter="hajj-prep" aria-pressed="false"><?php echo esc_html( ark_t( 'testimonials_hajj_prep' ) ); ?></button>
        </div>

        <!-- Desktop: masonry, 2 rows visible, Show more -->
        <div class="testimonials-desktop">
            <div class="testimonials-masonry-wrap" id="testimonials-masonry-wrap">
                <div class="testimonials-masonry">
                    <?php foreach ( $testimonials as $i => $t ) :
                        $num = $i + 1;
                        $quote = function_exists( 'ark_t' ) ? ark_t( 'testimonial_' . $num . '_quote' ) : '';
                        $full  = function_exists( 'ark_t' ) ? ark_t( 'testimonial_' . $num . '_full' ) : '';
                        $cats = implode( ' ', array_map( function ( $c ) { return 'cat-' . $c; }, $t['categories'] ) );
                        $rotation = ( $i % 5 ) - 2;
                    ?>
                    <article class="polaroid-card <?php echo esc_attr( $cats ); ?> polaroid-reveal" data-categories="<?php echo esc_attr( implode( ',', $t['categories'] ) ); ?>" data-full="<?php echo esc_attr( $full ); ?>" data-name="<?php echo esc_attr( $t['name'] ); ?>" data-location="<?php echo esc_attr( $t['location'] ); ?>" style="--polaroid-rotate: <?php echo (int) $rotation; ?>deg;" tabindex="0" role="button">
                        <div class="polaroid-tape" aria-hidden="true"></div>
                        <div class="polaroid-photo-wrap">
                            <div class="polaroid-photo" style="background-image: url('<?php echo esc_url( $t['img'] ); ?>');"></div>
                            <?php if ( ! empty( $t['video'] ) ) : ?>
                            <span class="polaroid-video-icon" aria-hidden="true">
                                <svg width="48" height="48" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M8 5v14l11-7z"/></svg>
                            </span>
                            <?php endif; ?>
                            <span class="polaroid-date"><?php echo esc_html( $t['date'] ); ?></span>
                        </div>
                        <div class="polaroid-body">
                            <p class="polaroid-quote"><?php echo esc_html( $quote ); ?></p>
                            <div class="polaroid-meta">
                                <span class="polaroid-name"><?php echo esc_html( $t['name'] ); ?></span>
                                <span class="polaroid-location"><?php echo esc_html( $t['location'] ); ?></span>
                            </div>
                            <div class="polaroid-footer">
                                <span class="polaroid-stars" aria-label="5 stars">★★★★★</span>
                                <?php if ( ! empty( $t['verified'] ) ) : ?>
                                <span class="polaroid-verified" title="<?php echo esc_attr( ark_t( 'testimonials_verified' ) ); ?>">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                    <?php echo esc_html( ark_t( 'testimonials_verified' ) ); ?>
                                </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </article>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="testimonials-show-more-wrap">
                <button type="button" class="testimonials-show-more btn-polaroid-outline" id="testimonials-show-more" aria-expanded="false" data-more="<?php echo esc_attr( ark_t( 'testimonials_show_more' ) ); ?>" data-less="<?php echo esc_attr( ark_t( 'testimonials_show_less' ) ); ?>"><?php echo esc_html( ark_t( 'testimonials_show_more' ) ); ?></button>
            </div>
        </div>

        <!-- Mobile: single slideshow with swipe -->
        <div class="testimonials-mobile">
            <div class="testimonials-slideshow-viewport" id="testimonials-slideshow-viewport">
                <div class="testimonials-slideshow-track" id="testimonials-slideshow-track">
                    <?php foreach ( $testimonials as $i => $t ) :
                        $num = $i + 1;
                        $quote = function_exists( 'ark_t' ) ? ark_t( 'testimonial_' . $num . '_quote' ) : '';
                        $full  = function_exists( 'ark_t' ) ? ark_t( 'testimonial_' . $num . '_full' ) : '';
                    ?>
                    <div class="polaroid-slide">
                        <article class="polaroid-card polaroid-card-slide" data-full="<?php echo esc_attr( $full ); ?>" data-name="<?php echo esc_attr( $t['name'] ); ?>" data-location="<?php echo esc_attr( $t['location'] ); ?>" tabindex="0" role="button">
                            <div class="polaroid-tape" aria-hidden="true"></div>
                            <div class="polaroid-photo-wrap">
                                <div class="polaroid-photo" style="background-image: url('<?php echo esc_url( $t['img'] ); ?>');"></div>
                                <?php if ( ! empty( $t['video'] ) ) : ?>
                                <span class="polaroid-video-icon" aria-hidden="true"><svg width="48" height="48" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg></span>
                                <?php endif; ?>
                                <span class="polaroid-date"><?php echo esc_html( $t['date'] ); ?></span>
                            </div>
                            <div class="polaroid-body">
                                <p class="polaroid-quote"><?php echo esc_html( $quote ); ?></p>
                                <div class="polaroid-meta">
                                    <span class="polaroid-name"><?php echo esc_html( $t['name'] ); ?></span>
                                    <span class="polaroid-location"><?php echo esc_html( $t['location'] ); ?></span>
                                </div>
                                <div class="polaroid-footer">
                                    <span class="polaroid-stars">★★★★★</span>
                                    <?php if ( ! empty( $t['verified'] ) ) : ?>
                                    <span class="polaroid-verified"><?php echo esc_html( ark_t( 'testimonials_verified' ) ); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </article>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="testimonials-slideshow-dots" id="testimonials-slideshow-dots" role="tablist"></div>
        </div>

        <div class="testimonials-connections" aria-hidden="true">
            <svg class="polaroid-connection polaroid-connection-1" viewBox="0 0 200 80" preserveAspectRatio="none"><path class="connection-line" d="M0 40 Q50 20 100 40 T200 40" fill="none" stroke="currentColor" stroke-width="1" stroke-dasharray="4 4"/></svg>
            <svg class="polaroid-connection polaroid-connection-2" viewBox="0 0 200 60" preserveAspectRatio="none"><path class="connection-line" d="M0 30 Q100 10 200 30" fill="none" stroke="currentColor" stroke-width="1" stroke-dasharray="4 4"/></svg>
        </div>

        <div class="testimonials-cta">
            <a href="<?php echo esc_url( ark_url( '/contact/' ) ); ?>" class="btn-polaroid-outline"><?php echo esc_html( ark_t( 'testimonials_share' ) ); ?></a>
        </div>
    </div>

    <div id="testimonial-modal" class="polaroid-modal" role="dialog" aria-modal="true" aria-labelledby="modal-title" aria-hidden="true">
        <div class="polaroid-modal-backdrop" aria-hidden="true"></div>
        <div class="polaroid-modal-content">
            <button type="button" class="polaroid-modal-close" aria-label="<?php echo esc_attr( ark_t( 'testimonials_close' ) ); ?>">&times;</button>
            <h3 id="modal-title" class="polaroid-modal-title"></h3>
            <p class="polaroid-modal-location"></p>
            <div class="polaroid-modal-body"></div>
        </div>
    </div>
</section>
