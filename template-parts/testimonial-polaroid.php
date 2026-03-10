<?php
/**
 * Testimonials – Polaroid Wall of Memories
 *
 * @package ARK_Travelers
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Polaroid images: pilgrimage (Makkah/Medina, pilgrims, praying) + tourism mix. All from Unsplash.
$testimonials = array(
    array( 'name' => 'Ahmed K.', 'location' => 'Stockholm', 'date' => 'Ramadan 2025', 'categories' => array( 'ramadan', 'family' ), 'verified' => true, 'video' => false, 'img' => 'https://images.unsplash.com/photo-1770786106021-52580470e31e?w=400&h=400&fit=crop' ),
    array( 'name' => 'Fatima L.', 'location' => 'Malmö', 'date' => 'December 2024', 'categories' => array( 'family' ), 'verified' => true, 'video' => true, 'img' => 'https://images.unsplash.com/photo-1488646953014-85cb44e25828?w=400&h=400&fit=crop' ),
    array( 'name' => 'Ibrahim M.', 'location' => 'Göteborg', 'date' => 'October 2024', 'categories' => array( 'family' ), 'verified' => true, 'video' => false, 'img' => 'https://images.unsplash.com/photo-1554794470-42d3cd193ecc?w=400&h=400&fit=crop' ),
    array( 'name' => 'Sara N.', 'location' => 'Uppsala', 'date' => 'March 2025', 'categories' => array( 'first-time' ), 'verified' => true, 'video' => false, 'img' => 'https://images.unsplash.com/photo-1736240624842-c13db7ba4275?w=400&h=400&fit=crop' ),
    array( 'name' => 'Omar & Layla', 'location' => 'Stockholm', 'date' => 'January 2025', 'categories' => array( 'hajj-prep', 'family' ), 'verified' => true, 'video' => false, 'img' => 'https://images.unsplash.com/photo-1742465294457-3c405ef99c23?w=400&h=400&fit=crop' ),
    array( 'name' => 'Yusuf A.', 'location' => 'Malmö', 'date' => 'Ramadan 2025', 'categories' => array( 'ramadan' ), 'verified' => false, 'video' => false, 'img' => 'https://images.unsplash.com/photo-1513072064285-240f87fa81e8?w=400&h=400&fit=crop' ),
    array( 'name' => 'Amina & Hassan', 'location' => 'Göteborg', 'date' => 'November 2024', 'categories' => array( 'family', 'first-time' ), 'verified' => true, 'video' => true, 'img' => 'https://images.unsplash.com/photo-1692566123227-0f68f1b9dac6?w=400&h=400&fit=crop' ),
    array( 'name' => 'Khalid R.', 'location' => 'Stockholm', 'date' => 'February 2025', 'categories' => array( 'hajj-prep' ), 'verified' => true, 'video' => false, 'img' => 'https://images.unsplash.com/photo-1768001863885-fd5bad96ebfc?w=400&h=400&fit=crop' ),
);

// Map testimonial numbers to English quotes and full text
$testimonial_quotes = array(
    1 => 'ARK Travelers made our Umrah journey seamless. From visa to hotels near the Haram, everything was perfect. We felt cared for every step of the way.',
    2 => 'The 24/7 support in Swedish was a blessing. When our flight was delayed, they rebooked everything within hours.',
    3 => 'Professional, transparent, and spiritually considerate. Our family will book with ARK again for our next pilgrimage.',
    4 => 'My first Umrah. I didn\'t know what to expect—ARK guided me through every step. I cried at the first sight of the Kaaba.',
    5 => 'Best flight prices and no hidden fees. Stress-free from start to finish. We\'re preparing for Hajj with ARK next year.',
    6 => 'From the first call to the last day in Madinah—everything was perfect. Hotels, transport, peace of mind.',
    7 => 'We took our parents for their first Umrah. ARK made it gentle, dignified, and unforgettable.',
    8 => 'Clear pricing, no surprises. The spiritual preparation guide they sent was beautiful. Ready for Hajj with ARK.',
);

$testimonial_full = array(
    1 => 'We had been planning Umrah for years. ARK handled everything—visas, flights, hotels minutes from the Haram. The spiritual support and Swedish-speaking guide made it stress-free. Our children still talk about the trip. Jazakallah khair.',
    2 => 'I was nervous traveling with my mother. ARK\'s team answered every question in Swedish and rebooked our flights when there was a delay—no extra cost. We arrived in Madinah the same day. Truly grateful.',
    3 => 'We wanted a company that understood both Swedish efficiency and the spiritual weight of the journey. ARK delivered. No hidden fees, clear communication, and a real sense of care. Already planning our return.',
    4 => 'As a first-time pilgrim I had so many questions. ARK\'s team never made me feel silly. They arranged my visa, hotel, and transport. When I finally stood in front of the Kaaba, I couldn\'t stop crying. This journey changed my life.',
    5 => 'We did Umrah with ARK and are now saving for Hajj. They explained the process, gave us a clear timeline, and we trust them completely. Transparent pricing and a team that feels like family.',
    6 => 'I traveled solo for Ramadan Umrah. ARK sorted my visa, flight, and a hotel so close to the Haram I could walk. The 24/7 support number gave me confidence. Already recommending to friends.',
    7 => 'Our parents had dreamed of Umrah for decades. ARK arranged wheelchair access, ground-floor rooms, and a pace that suited them. Seeing our mother at the Kaaba—we will never forget it. Shukran ARK.',
    8 => 'I\'m in the Hajj prep group and the support from ARK has been outstanding. They sent a preparation guide, checklist, and we have a dedicated contact. Feeling ready and grateful.',
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
            <h2 id="testimonials-heading" class="testimonials-title"><?php echo esc_html( ark_t( 'Stories From The Holy Journey' ) ); ?></h2>
            <p class="testimonials-subtitle"><?php echo esc_html( ark_t( 'Real experiences from 15,000+ pilgrims' ) ); ?></p>
        </header>

        <div class="testimonials-filters testimonials-filters-desktop" role="tablist" aria-label="<?php echo esc_attr( ark_t( 'Filter testimonials' ) ); ?>">
            <button type="button" class="testimonials-filter is-active" data-filter="all" aria-pressed="true"><?php echo esc_html( ark_t( 'All' ) ); ?></button>
            <button type="button" class="testimonials-filter" data-filter="ramadan" aria-pressed="false"><?php echo esc_html( ark_t( 'Ramadan' ) ); ?></button>
            <button type="button" class="testimonials-filter" data-filter="family" aria-pressed="false"><?php echo esc_html( ark_t( 'Family' ) ); ?></button>
            <button type="button" class="testimonials-filter" data-filter="first-time" aria-pressed="false"><?php echo esc_html( ark_t( 'First-Time' ) ); ?></button>
            <button type="button" class="testimonials-filter" data-filter="hajj-prep" aria-pressed="false"><?php echo esc_html( ark_t( 'Hajj Prep' ) ); ?></button>
        </div>

        <!-- Desktop: masonry, 2 rows visible, Show more -->
        <div class="testimonials-desktop">
            <div class="testimonials-masonry-wrap" id="testimonials-masonry-wrap">
                <div class="testimonials-masonry">
                    <?php foreach ( $testimonials as $i => $t ) :
                        $num = $i + 1;
                        $quote = isset( $testimonial_quotes[ $num ] ) ? $testimonial_quotes[ $num ] : '';
                        $full  = isset( $testimonial_full[ $num ] ) ? $testimonial_full[ $num ] : '';
                        $cats = implode( ' ', array_map( function ( $c ) { return 'cat-' . $c; }, $t['categories'] ) );
                        $rotation = ( $i % 5 ) - 2;
                    ?>
                    <article class="polaroid-card <?php echo esc_attr( $cats ); ?> polaroid-reveal" data-categories="<?php echo esc_attr( implode( ',', $t['categories'] ) ); ?>" data-full="<?php echo esc_attr( $full ); ?>" data-name="<?php echo esc_attr( $t['name'] ); ?>" data-location="<?php echo esc_attr( $t['location'] ); ?>" style="--polaroid-rotate: <?php echo (int) $rotation; ?>deg;" tabindex="0" role="button">
                        <div class="polaroid-tape" aria-hidden="true"></div>
                        <div class="polaroid-photo-wrap">
                            <div class="polaroid-photo polaroid-photo-develop" style="background-image: url('<?php echo esc_url( $t['img'] ); ?>');"></div>
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
                                <span class="polaroid-verified" title="<?php echo esc_attr( ark_t( 'Verified Journey' ) ); ?>">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                    <?php echo esc_html( ark_t( 'Verified Journey' ) ); ?>
                                </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </article>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="testimonials-show-more-wrap">
                <button type="button" class="testimonials-show-more btn-polaroid-outline" id="testimonials-show-more" aria-expanded="false" data-more="<?php echo esc_attr( ark_t( 'Show more stories' ) ); ?>" data-less="<?php echo esc_attr( ark_t( 'Show less' ) ); ?>"><?php echo esc_html( ark_t( 'Show more stories' ) ); ?></button>
            </div>
        </div>

        <!-- Mobile: single slideshow with swipe -->
        <div class="testimonials-mobile">
            <div class="testimonials-slideshow-viewport" id="testimonials-slideshow-viewport">
                <div class="testimonials-slideshow-track" id="testimonials-slideshow-track">
                    <?php foreach ( $testimonials as $i => $t ) :
                        $num = $i + 1;
                        $quote = isset( $testimonial_quotes[ $num ] ) ? $testimonial_quotes[ $num ] : '';
                        $full  = isset( $testimonial_full[ $num ] ) ? $testimonial_full[ $num ] : '';
                    ?>
                    <div class="polaroid-slide">
                        <article class="polaroid-card polaroid-card-slide" data-full="<?php echo esc_attr( $full ); ?>" data-name="<?php echo esc_attr( $t['name'] ); ?>" data-location="<?php echo esc_attr( $t['location'] ); ?>" tabindex="0" role="button">
                            <div class="polaroid-tape" aria-hidden="true"></div>
                            <div class="polaroid-photo-wrap">
                                <div class="polaroid-photo polaroid-photo-develop" style="background-image: url('<?php echo esc_url( $t['img'] ); ?>');"></div>
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
                                    <span class="polaroid-verified"><?php echo esc_html( ark_t( 'Verified Journey' ) ); ?></span>
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
            <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn-polaroid-outline"><?php echo esc_html( ark_t( 'Share Your Story' ) ); ?></a>
        </div>
    </div>

    <div id="testimonial-modal" class="polaroid-modal" role="dialog" aria-modal="true" aria-labelledby="modal-title" aria-hidden="true">
        <div class="polaroid-modal-backdrop" aria-hidden="true"></div>
        <div class="polaroid-modal-content">
            <button type="button" class="polaroid-modal-close" aria-label="<?php echo esc_attr( ark_t( 'Close' ) ); ?>">&times;</button>
            <h3 id="modal-title" class="polaroid-modal-title"></h3>
            <p class="polaroid-modal-location"></p>
            <div class="polaroid-modal-body"></div>
        </div>
    </div>
</section>
