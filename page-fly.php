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
            <h2 class="ark-fly-cta-heading"><?php echo esc_html( ark_t( 'Ready to book your flight?' ) ); ?></h2>
            <p class="ark-fly-cta-text"><?php echo esc_html( ark_t( 'Use the search form above or explore popular destinations below.' ) ); ?></p>
            <a href="#ark-fly-form" class="btn-primary ark-fly-cta-btn"><?php echo esc_html( ark_t( 'Back to search form' ) ); ?></a>
        </div>
    </section>

    <section class="ark-section ark-fly-popular">
        <div class="ark-container">
            <h2 class="ark-section-title"><?php echo esc_html( ark_t( 'Popular Destinations' ) ); ?></h2>
            <div class="ark-fly-routes-grid">
                <a href="#ark-fly-form" class="ark-route-card card" data-origin="Stockholm" data-dest="Dubai" style="--route-bg:url('https://images.unsplash.com/photo-1751473058035-3b7ef98d0367?w=1200&q=85&fit=crop');">
                    <span class="ark-route-city"><?php echo esc_html( ark_t( 'Dubai' ) ); ?></span>
                    <span class="ark-route-country"><?php echo esc_html( ark_t( 'United Arab Emirates' ) ); ?></span>
                </a>
                <a href="#ark-fly-form" class="ark-route-card card" data-origin="Stockholm" data-dest="Riyadh" style="--route-bg:url('https://images.unsplash.com/photo-1674822858255-fcc093a1ef43?w=1200&q=85&fit=crop');">
                    <span class="ark-route-city"><?php echo esc_html( ark_t( 'Riyadh' ) ); ?></span>
                    <span class="ark-route-country"><?php echo esc_html( ark_t( 'Saudi Arabia' ) ); ?></span>
                </a>
                <a href="#ark-fly-form" class="ark-route-card card" data-origin="Stockholm" data-dest="Athens" style="--route-bg:url('https://images.unsplash.com/photo-1767907573610-6f1d6c501d6f?w=1200&q=85&fit=crop');">
                    <span class="ark-route-city"><?php echo esc_html( ark_t( 'Athens' ) ); ?></span>
                    <span class="ark-route-country"><?php echo esc_html( ark_t( 'Greece' ) ); ?></span>
                </a>
                <a href="#ark-fly-form" class="ark-route-card card" data-origin="Stockholm" data-dest="Cairo" style="--route-bg:url('https://images.unsplash.com/photo-1630201187972-dc4136076c6c?w=1200&q=85&fit=crop');">
                    <span class="ark-route-city"><?php echo esc_html( ark_t( 'Cairo' ) ); ?></span>
                    <span class="ark-route-country"><?php echo esc_html( ark_t( 'Egypt' ) ); ?></span>
                </a>
                <a href="#ark-fly-form" class="ark-route-card card" data-origin="Stockholm" data-dest="Makkah" style="--route-bg:url('https://images.unsplash.com/photo-1768001863885-fd5bad96ebfc?w=1200&q=85&fit=crop');">
                    <span class="ark-route-city"><?php echo esc_html( ark_t( 'Makkah' ) ); ?></span>
                    <span class="ark-route-country"><?php echo esc_html( ark_t( 'Saudi Arabia' ) ); ?></span>
                </a>
                <a href="#ark-fly-form" class="ark-route-card card" data-origin="Stockholm" data-dest="Medina" style="--route-bg:url('https://images.unsplash.com/photo-1742465294457-3c405ef99c23?w=1200&q=85&fit=crop');">
                    <span class="ark-route-city"><?php echo esc_html( ark_t( 'Medina' ) ); ?></span>
                    <span class="ark-route-country"><?php echo esc_html( ark_t( 'Saudi Arabia' ) ); ?></span>
                </a>
                <a href="#ark-fly-form" class="ark-route-card card" data-origin="Stockholm" data-dest="Istanbul" style="--route-bg:url('https://images.unsplash.com/photo-1763965367191-6455ef032c79?w=1200&q=85&fit=crop');">
                    <span class="ark-route-city"><?php echo esc_html( ark_t( 'Istanbul' ) ); ?></span>
                    <span class="ark-route-country"><?php echo esc_html( ark_t( 'Turkiye' ) ); ?></span>
                </a>
                <a href="#ark-fly-form" class="ark-route-card card" data-origin="Stockholm" data-dest="Sydney" style="--route-bg:url('https://images.unsplash.com/photo-1760129744104-4802b1b1a017?w=1200&q=85&fit=crop');">
                    <span class="ark-route-city"><?php echo esc_html( ark_t( 'Sydney' ) ); ?></span>
                    <span class="ark-route-country"><?php echo esc_html( ark_t( 'Australia' ) ); ?></span>
                </a>
            </div>
        </div>
    </section>

    <section class="ark-section ark-fly-why" aria-labelledby="why-book-heading">
        <div class="ark-container ark-fly-why-inner">
            <p class="ark-fly-why-eyebrow"><?php echo esc_html( ark_t( 'Why choose us' ) ); ?></p>
            <h2 id="why-book-heading" class="ark-fly-why-title"><?php echo esc_html( ark_t( 'Why Book With Us' ) ); ?></h2>
            <div class="ark-fly-benefits-grid">
                <article class="ark-fly-benefit-card" data-icon="price">
                    <span class="ark-fly-benefit-icon" aria-hidden="true"></span>
                    <h3 class="ark-fly-benefit-title"><?php echo esc_html( ark_t( 'Best Price Guarantee' ) ); ?></h3>
                    <p class="ark-fly-benefit-desc"><?php echo esc_html( ark_t( 'We match or beat any quoted fare.' ) ); ?></p>
                </article>
                <article class="ark-fly-benefit-card" data-icon="calendar">
                    <span class="ark-fly-benefit-icon" aria-hidden="true"></span>
                    <h3 class="ark-fly-benefit-title"><?php echo esc_html( ark_t( 'Flexible Date Changes' ) ); ?></h3>
                    <p class="ark-fly-benefit-desc"><?php echo esc_html( ark_t( 'Change your dates with minimal hassle.' ) ); ?></p>
                </article>
                <article class="ark-fly-benefit-card ark-fly-benefit-card-featured" data-icon="support">
                    <span class="ark-fly-benefit-icon" aria-hidden="true"></span>
                    <h3 class="ark-fly-benefit-title"><?php echo esc_html( ark_t( '24/7 Customer Support' ) ); ?></h3>
                    <p class="ark-fly-benefit-desc"><?php echo esc_html( ark_t( 'Real help, anytime, in your language.' ) ); ?></p>
                </article>
                <article class="ark-fly-benefit-card" data-icon="shield">
                    <span class="ark-fly-benefit-icon" aria-hidden="true"></span>
                    <h3 class="ark-fly-benefit-title"><?php echo esc_html( ark_t( 'Transparent Pricing' ) ); ?></h3>
                    <p class="ark-fly-benefit-desc"><?php echo esc_html( ark_t( 'No hidden fees. Ever.' ) ); ?></p>
                </article>
                <article class="ark-fly-benefit-card ark-fly-benefit-card-wide" data-icon="partners">
                    <span class="ark-fly-benefit-icon" aria-hidden="true"></span>
                    <h3 class="ark-fly-benefit-title"><?php echo esc_html( ark_t( 'Exclusive Airline Partnerships' ) ); ?></h3>
                    <p class="ark-fly-benefit-desc"><?php echo esc_html( ark_t( 'Access to fares and routes others can\'t offer.' ) ); ?></p>
                </article>
            </div>
            <div class="ark-fly-why-trust">
                <span class="ark-trust-badge">IATA</span>
                <span class="ark-trust-badge"><?php echo esc_html( ark_t( 'Secure payment' ) ); ?></span>
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
