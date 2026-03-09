<?php
/**
 * Flight booking page – hero, search form, popular routes, why book, trust badges
 *
 * @package ARK_Travelers
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();
?>

<main id="main" class="ark-main ark-main-fly site-main">
    <!-- Hero Banner with Parallax -->
    <?php get_template_part( 'template-parts/fly-hero' ); ?>

    <!-- CTA section: heading + scroll to form -->
    <section class="ark-section ark-fly-search ark-fly-cta-section" data-parallax-section>
        <div class="ark-container ark-fly-cta-inner">
            <h2 class="ark-fly-cta-heading"><?php echo esc_html( ark_t( 'fly_ready_book' ) ); ?></h2>
            <p class="ark-fly-cta-text"><?php echo esc_html( ark_t( 'fly_use_form' ) ); ?></p>
            <a href="#ark-fly-form" class="btn-primary ark-fly-cta-btn"><?php echo esc_html( ark_t( 'fly_back_to_form' ) ); ?></a>
        </div>
    </section>

    <section class="ark-section ark-fly-popular">
        <div class="ark-container">
            <h2 class="ark-section-title"><?php echo esc_html( ark_t( 'fly_popular_destinations' ) ); ?></h2>
            <div class="ark-fly-routes-grid">
                <a href="#ark-fly-form" class="ark-route-card card" data-origin="Stockholm" data-dest="Dubai" style="--route-bg:url('https://images.unsplash.com/photo-1751473058035-3b7ef98d0367?w=1200&q=85&fit=crop');">
                    <span class="ark-route-city"><?php echo esc_html( ark_t( 'fly_destination_dubai' ) ); ?></span>
                    <span class="ark-route-country"><?php echo esc_html( ark_t( 'fly_country_uae' ) ); ?></span>
                </a>
                <a href="#ark-fly-form" class="ark-route-card card" data-origin="Stockholm" data-dest="Riyadh" style="--route-bg:url('https://images.unsplash.com/photo-1674822858255-fcc093a1ef43?w=1200&q=85&fit=crop');">
                    <span class="ark-route-city"><?php echo esc_html( ark_t( 'fly_destination_riyadh' ) ); ?></span>
                    <span class="ark-route-country"><?php echo esc_html( ark_t( 'fly_country_saudi' ) ); ?></span>
                </a>
                <a href="#ark-fly-form" class="ark-route-card card" data-origin="Stockholm" data-dest="Athens" style="--route-bg:url('https://images.unsplash.com/photo-1767907573610-6f1d6c501d6f?w=1200&q=85&fit=crop');">
                    <span class="ark-route-city"><?php echo esc_html( ark_t( 'fly_destination_athens' ) ); ?></span>
                    <span class="ark-route-country"><?php echo esc_html( ark_t( 'fly_country_greece' ) ); ?></span>
                </a>
                <a href="#ark-fly-form" class="ark-route-card card" data-origin="Stockholm" data-dest="Cairo" style="--route-bg:url('https://images.unsplash.com/photo-1630201187972-dc4136076c6c?w=1200&q=85&fit=crop');">
                    <span class="ark-route-city"><?php echo esc_html( ark_t( 'fly_destination_cairo' ) ); ?></span>
                    <span class="ark-route-country"><?php echo esc_html( ark_t( 'fly_country_egypt' ) ); ?></span>
                </a>
                <a href="#ark-fly-form" class="ark-route-card card" data-origin="Stockholm" data-dest="Makkah" style="--route-bg:url('https://images.unsplash.com/photo-1768001863885-fd5bad96ebfc?w=1200&q=85&fit=crop');">
                    <span class="ark-route-city"><?php echo esc_html( ark_t( 'fly_destination_makkah' ) ); ?></span>
                    <span class="ark-route-country"><?php echo esc_html( ark_t( 'fly_country_saudi' ) ); ?></span>
                </a>
                <a href="#ark-fly-form" class="ark-route-card card" data-origin="Stockholm" data-dest="Medina" style="--route-bg:url('https://images.unsplash.com/photo-1742465294457-3c405ef99c23?w=1200&q=85&fit=crop');">
                    <span class="ark-route-city"><?php echo esc_html( ark_t( 'fly_destination_medina' ) ); ?></span>
                    <span class="ark-route-country"><?php echo esc_html( ark_t( 'fly_country_saudi' ) ); ?></span>
                </a>
                <a href="#ark-fly-form" class="ark-route-card card" data-origin="Stockholm" data-dest="Istanbul" style="--route-bg:url('https://images.unsplash.com/photo-1763965367191-6455ef032c79?w=1200&q=85&fit=crop');">
                    <span class="ark-route-city"><?php echo esc_html( ark_t( 'fly_destination_istanbul' ) ); ?></span>
                    <span class="ark-route-country"><?php echo esc_html( ark_t( 'fly_country_turkiye' ) ); ?></span>
                </a>
                <a href="#ark-fly-form" class="ark-route-card card" data-origin="Stockholm" data-dest="Sydney" style="--route-bg:url('https://images.unsplash.com/photo-1760129744104-4802b1b1a017?w=1200&q=85&fit=crop');">
                    <span class="ark-route-city"><?php echo esc_html( ark_t( 'fly_destination_sydney' ) ); ?></span>
                    <span class="ark-route-country"><?php echo esc_html( ark_t( 'fly_country_australia' ) ); ?></span>
                </a>
            </div>
        </div>
    </section>

    <section class="ark-section ark-fly-why" aria-labelledby="why-book-heading">
        <div class="ark-container ark-fly-why-inner">
            <p class="ark-fly-why-eyebrow"><?php echo esc_html( ark_t( 'fly_why_choose' ) ); ?></p>
            <h2 id="why-book-heading" class="ark-fly-why-title"><?php echo esc_html( ark_t( 'fly_why_book' ) ); ?></h2>
            <div class="ark-fly-benefits-grid">
                <article class="ark-fly-benefit-card" data-icon="price">
                    <span class="ark-fly-benefit-icon" aria-hidden="true"></span>
                    <h3 class="ark-fly-benefit-title"><?php echo esc_html( ark_t( 'fly_best_price' ) ); ?></h3>
                    <p class="ark-fly-benefit-desc"><?php echo esc_html( ark_t( 'fly_best_price_desc' ) ); ?></p>
                </article>
                <article class="ark-fly-benefit-card" data-icon="calendar">
                    <span class="ark-fly-benefit-icon" aria-hidden="true"></span>
                    <h3 class="ark-fly-benefit-title"><?php echo esc_html( ark_t( 'fly_flexible_dates' ) ); ?></h3>
                    <p class="ark-fly-benefit-desc"><?php echo esc_html( ark_t( 'fly_flexible_dates_desc' ) ); ?></p>
                </article>
                <article class="ark-fly-benefit-card ark-fly-benefit-card-featured" data-icon="support">
                    <span class="ark-fly-benefit-icon" aria-hidden="true"></span>
                    <h3 class="ark-fly-benefit-title"><?php echo esc_html( ark_t( 'fly_24_7_support' ) ); ?></h3>
                    <p class="ark-fly-benefit-desc"><?php echo esc_html( ark_t( 'fly_24_7_support_desc' ) ); ?></p>
                </article>
                <article class="ark-fly-benefit-card" data-icon="shield">
                    <span class="ark-fly-benefit-icon" aria-hidden="true"></span>
                    <h3 class="ark-fly-benefit-title"><?php echo esc_html( ark_t( 'fly_transparent' ) ); ?></h3>
                    <p class="ark-fly-benefit-desc"><?php echo esc_html( ark_t( 'fly_transparent_desc' ) ); ?></p>
                </article>
                <article class="ark-fly-benefit-card ark-fly-benefit-card-wide" data-icon="partners">
                    <span class="ark-fly-benefit-icon" aria-hidden="true"></span>
                    <h3 class="ark-fly-benefit-title"><?php echo esc_html( ark_t( 'fly_exclusive_partners' ) ); ?></h3>
                    <p class="ark-fly-benefit-desc"><?php echo esc_html( ark_t( 'fly_exclusive_partners_desc' ) ); ?></p>
                </article>
            </div>
            <div class="ark-fly-why-trust">
                <span class="ark-trust-badge">IATA</span>
                <span class="ark-trust-badge"><?php echo esc_html( ark_t( 'fly_secure_payment' ) ); ?></span>
            </div>
        </div>
    </section>

    <section class="ark-section ark-fly-results" id="ark-fly-results" aria-hidden="true">
        <div class="ark-container">
            <div id="ark-fly-results-list"></div>
        </div>
    </section>
</main>

<?php
get_footer();
