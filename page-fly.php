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
            <h2 class="ark-section-title"><?php echo esc_html( ark_t( 'fly_popular_routes' ) ); ?></h2>
            <div class="ark-fly-routes-grid">
                <a href="#ark-fly-form" class="ark-route-card card" data-origin="Stockholm" data-dest="Jeddah" data-price="3200"><?php echo esc_html( ark_t( 'fly_stockholm_jeddah' ) ); ?> <span><?php echo esc_html( ark_t( 'fly_from_sek' ) . ' 3,200' . ark_t( 'fly_currency_suffix' ) ); ?></span></a>
                <a href="#ark-fly-form" class="ark-route-card card" data-origin="Stockholm" data-dest="Medina" data-price="3500"><?php echo esc_html( ark_t( 'fly_stockholm_medina' ) ); ?> <span><?php echo esc_html( ark_t( 'fly_from_sek' ) . ' 3,500' . ark_t( 'fly_currency_suffix' ) ); ?></span></a>
                <a href="#ark-fly-form" class="ark-route-card card" data-origin="Stockholm" data-dest="Dubai" data-price="2800"><?php echo esc_html( ark_t( 'fly_stockholm_dubai' ) ); ?> <span><?php echo esc_html( ark_t( 'fly_from_sek' ) . ' 2,800' . ark_t( 'fly_currency_suffix' ) ); ?></span></a>
                <a href="#ark-fly-form" class="ark-route-card card" data-origin="Stockholm" data-dest="Istanbul" data-price="1200"><?php echo esc_html( ark_t( 'fly_stockholm_istanbul' ) ); ?> <span><?php echo esc_html( ark_t( 'fly_from_sek' ) . ' 1,200' . ark_t( 'fly_currency_suffix' ) ); ?></span></a>
                <a href="#ark-fly-form" class="ark-route-card card" data-origin="Stockholm" data-dest="Cairo" data-price="2400"><?php echo esc_html( ark_t( 'fly_stockholm_cairo' ) ); ?> <span><?php echo esc_html( ark_t( 'fly_from_sek' ) . ' 2,400' . ark_t( 'fly_currency_suffix' ) ); ?></span></a>
                <a href="#ark-fly-form" class="ark-route-card card" data-origin="Stockholm" data-dest="Amman" data-price="2600"><?php echo esc_html( ark_t( 'fly_stockholm_amman' ) ); ?> <span><?php echo esc_html( ark_t( 'fly_from_sek' ) . ' 2,600' . ark_t( 'fly_currency_suffix' ) ); ?></span></a>
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
