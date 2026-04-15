<?php
/**
 * Google Reviews – "coming soon" state.
 * When the Google Business Profile is live, replace the $google_place_id
 * and $google_review_url below and set ARK_GOOGLE_REVIEWS_LIVE to true
 * in wp-config.php (or simply switch the constant here) to show real cards.
 *
 * @package ARK_Travelers
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/*
 * ─── CONFIGURATION ────────────────────────────────────────────────────
 * Set these once the Google Business Profile is confirmed.
 *
 *   $google_review_url   – direct "write a review" link from Google Maps
 *   $google_maps_url     – Google Maps listing URL
 *   $google_place_name   – display name shown in the badge
 *   $reviews_live        – flip to true to show real review cards
 * ──────────────────────────────────────────────────────────────────────
 */
$google_review_url = 'https://g.page/r/PLACEHOLDER/review'; // ← replace with real link
$google_maps_url   = 'https://maps.google.com/?q=ARK+Travelers+Stockholm';
$google_place_name = 'ARK Travelers AB';
$reviews_live      = false; // ← set to true + add $reviews array when profile is live

/*
 * ─── REAL REVIEWS (fill this array when live) ─────────────────────────
 * Each item: [ 'name', 'date', 'stars' (1-5), 'text', 'avatar_initial' ]
 * ──────────────────────────────────────────────────────────────────────
 */
$reviews = array(
    // array( 'name' => 'Ahmed K.', 'date' => 'March 2025', 'stars' => 5, 'text' => '…', 'initial' => 'A' ),
);
?>

<section class="ark-section ark-google-reviews ark-section-parallax" id="ark-google-reviews" aria-labelledby="google-reviews-heading" data-parallax-section>

    <div class="ark-container">

        <!-- ── Header ─────────────────────────────── -->
        <header class="ark-gr-header">
            <div class="ark-gr-header-top">
                <!-- Google "G" multicolour logo -->
                <span class="ark-gr-google-logo" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="36" height="36">
                        <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
                        <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
                        <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
                        <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.18 1.48-4.97 2.31-8.16 2.31-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
                        <path fill="none" d="M0 0h48v48H0z"/>
                    </svg>
                </span>
                <div class="ark-gr-header-text">
                    <h2 id="google-reviews-heading" class="ark-gr-title">
                        <?php echo esc_html( ark_t( 'Google Reviews' ) ); ?>
                    </h2>
                    <p class="ark-gr-place"><?php echo esc_html( $google_place_name ); ?></p>
                </div>
            </div>

            <!-- Rating summary -->
            <div class="ark-gr-rating-summary">
                <span class="ark-gr-big-score">5.0</span>
                <div class="ark-gr-rating-detail">
                    <span class="ark-gr-stars" aria-label="<?php echo esc_attr( ark_t( '5 out of 5 stars' ) ); ?>">
                        <?php for ( $s = 0; $s < 5; $s++ ) : ?>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="#FBBC05" aria-hidden="true"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        <?php endfor; ?>
                    </span>
                    <span class="ark-gr-review-count">
                        <?php echo esc_html( ark_t( 'Based on Google Reviews' ) ); ?>
                    </span>
                </div>
                <a href="<?php echo esc_url( $google_maps_url ); ?>" class="ark-gr-powered-badge" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( ark_t( 'View on Google Maps' ) ); ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="14" height="14" aria-hidden="true">
                        <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
                        <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
                        <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
                        <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.18 1.48-4.97 2.31-8.16 2.31-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
                    </svg>
                    <?php echo esc_html( ark_t( 'Powered by Google' ) ); ?>
                </a>
            </div>
        </header>

        <!-- ── Reviews grid / coming-soon state ───── -->
        <?php if ( $reviews_live && ! empty( $reviews ) ) : ?>

        <div class="ark-gr-grid" role="list">
            <?php foreach ( $reviews as $r ) :
                $stars = min( 5, max( 1, (int) $r['stars'] ) );
            ?>
            <article class="ark-gr-card ark-reveal" role="listitem">
                <div class="ark-gr-card-header">
                    <span class="ark-gr-avatar" aria-hidden="true"><?php echo esc_html( $r['initial'] ); ?></span>
                    <div class="ark-gr-card-meta">
                        <span class="ark-gr-reviewer-name"><?php echo esc_html( $r['name'] ); ?></span>
                        <span class="ark-gr-review-date"><?php echo esc_html( $r['date'] ); ?></span>
                    </div>
                    <svg class="ark-gr-card-google-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="20" height="20" aria-hidden="true">
                        <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
                        <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
                        <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
                        <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.18 1.48-4.97 2.31-8.16 2.31-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
                    </svg>
                </div>
                <div class="ark-gr-card-stars" aria-label="<?php echo esc_attr( $stars . ' ' . ark_t( 'stars' ) ); ?>">
                    <?php for ( $s = 0; $s < $stars; $s++ ) : ?>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="#FBBC05" aria-hidden="true"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                    <?php endfor; ?>
                </div>
                <p class="ark-gr-card-text"><?php echo esc_html( $r['text'] ); ?></p>
            </article>
            <?php endforeach; ?>
        </div>

        <?php else : ?>

        <!-- Coming-soon state: transparent + honest -->
        <div class="ark-gr-coming-soon">
            <div class="ark-gr-cs-cards" aria-hidden="true">
                <?php for ( $p = 0; $p < 3; $p++ ) : ?>
                <div class="ark-gr-placeholder-card">
                    <div class="ark-gr-ph-header">
                        <span class="ark-gr-ph-avatar"></span>
                        <div class="ark-gr-ph-lines">
                            <span class="ark-gr-ph-line ark-gr-ph-line-name"></span>
                            <span class="ark-gr-ph-line ark-gr-ph-line-date"></span>
                        </div>
                    </div>
                    <div class="ark-gr-ph-stars">
                        <?php for ( $s = 0; $s < 5; $s++ ) : ?>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="#E8E8E8" aria-hidden="true"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        <?php endfor; ?>
                    </div>
                    <div class="ark-gr-ph-text-lines">
                        <span class="ark-gr-ph-line"></span>
                        <span class="ark-gr-ph-line ark-gr-ph-line-short"></span>
                        <span class="ark-gr-ph-line ark-gr-ph-line-med"></span>
                    </div>
                </div>
                <?php endfor; ?>
            </div>

            <div class="ark-gr-cs-message">
                <div class="ark-gr-cs-icon" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="48" height="48">
                        <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
                        <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
                        <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
                        <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.18 1.48-4.97 2.31-8.16 2.31-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
                    </svg>
                </div>
                <h3 class="ark-gr-cs-title"><?php echo esc_html( ark_t( 'Real Reviews, Coming Soon' ) ); ?></h3>
                <p class="ark-gr-cs-body">
                    <?php echo esc_html( ark_t( 'We only show genuine Google reviews from real customers. Our Google Business Profile is being set up — reviews will appear here automatically once it is live.' ) ); ?>
                </p>
                <a href="<?php echo esc_url( $google_review_url ); ?>" class="ark-gr-review-btn btn-primary" target="_blank" rel="noopener noreferrer">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="18" height="18" aria-hidden="true" style="flex-shrink:0;">
                        <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
                        <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
                        <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
                        <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.18 1.48-4.97 2.31-8.16 2.31-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
                    </svg>
                    <?php echo esc_html( ark_t( 'Be the First to Review Us' ) ); ?>
                </a>
            </div>
        </div>

        <?php endif; ?>

    </div><!-- .ark-container -->
</section>
