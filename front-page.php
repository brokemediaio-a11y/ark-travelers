<?php
/**
 * Homepage template – hero, trust bar, services, featured package, why choose, testimonials, CTA
 *
 * @package ARK_Travelers
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();
?>

<main id="main" class="ark-main ark-main-home" role="main">
    <!-- Hero: Airplane Scroll Animation (same as Fly page) -->
    <?php get_template_part( 'template-parts/airplane-scroll' ); ?>

    <!-- GIF Section (panel for GSAP pin + snap) -->
    <section class="ark-video-section panel ark-section-parallax" id="ark-video-section" aria-label="<?php echo esc_attr( ark_t( 'home_travel_aria' ) ); ?>" data-parallax-section>
        <div class="ark-video-wrapper">
            <img class="ark-video-parallax" src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/ark video gif.gif' ); ?>" alt="<?php echo esc_attr( ark_t( 'home_office_alt' ) ); ?>" />
        </div>
    </section>

    <!-- Trust credentials marquee -->
    <section class="ark-section ark-trust-bar ark-section-parallax" aria-label="<?php echo esc_attr( ark_t( 'home_trust_aria' ) ); ?>" data-parallax-section>
        <div class="ark-marquee">
            <div class="ark-marquee-inner">
                <span class="ark-marquee-item"><?php echo esc_html( ark_t( 'home_iata' ) ); ?></span>
                <span class="ark-marquee-item"><?php echo esc_html( ark_t( 'home_pilgrims' ) ); ?></span>
                <span class="ark-marquee-item"><?php echo esc_html( ark_t( 'home_24_7_support' ) ); ?></span>
                <span class="ark-marquee-item"><?php echo esc_html( ark_t( 'home_hajj_approved' ) ); ?></span>
                <span class="ark-marquee-item"><?php echo esc_html( ark_t( 'home_gdpr' ) ); ?></span>
                <span class="ark-marquee-item"><?php echo esc_html( ark_t( 'home_award' ) ); ?></span>
                <span class="ark-marquee-item"><?php echo esc_html( ark_t( 'home_iata' ) ); ?></span>
                <span class="ark-marquee-item"><?php echo esc_html( ark_t( 'home_pilgrims' ) ); ?></span>
            </div>
        </div>
    </section>

    <!-- Our Services – three cards with scroll parallax (1 & 3: bottom→up, 2: up→down) -->
    <section class="ark-section ark-services ark-services-parallax ark-section-parallax" id="ark-services" aria-labelledby="services-heading" data-parallax-section>
        <div class="ark-container">
            <h2 id="services-heading" class="ark-section-title"><?php echo esc_html( ark_t( 'home_our_services' ) ); ?></h2>
            <div class="ark-services-parallax-grid">
                <article class="ark-service-parallax-card ark-service-parallax-card-up ark-service-card-1" data-parallax="up">
                    <div class="ark-service-parallax-card-inner">
                        <div class="ark-service-parallax-icon"><i class="fa-solid fa-plane" aria-hidden="true"></i></div>
                        <h3><?php echo esc_html( ark_t( 'home_flight_easy' ) ); ?></h3>
                        <p><?php echo esc_html( ark_t( 'home_flight_desc' ) ); ?></p>
                        <ul class="ark-service-parallax-features">
                            <li><?php echo esc_html( ark_t( 'home_best_price' ) ); ?></li>
                            <li><?php echo esc_html( ark_t( 'home_etickets' ) ); ?></li>
                            <li><?php echo esc_html( ark_t( 'home_booking_support' ) ); ?></li>
                        </ul>
                    </div>
                </article>
                <article class="ark-service-parallax-card ark-service-parallax-card-down ark-service-card-2" data-parallax="down">
                    <div class="ark-service-parallax-card-inner">
                        <div class="ark-service-parallax-icon"><i class="fa-solid fa-ticket" aria-hidden="true"></i></div>
                        <h3><?php echo esc_html( ark_t( 'home_etickets_docs' ) ); ?></h3>
                        <p><?php echo esc_html( ark_t( 'home_etickets_desc' ) ); ?></p>
                        <ul class="ark-service-parallax-features">
                            <li><?php echo esc_html( ark_t( 'home_etickets' ) ); ?></li>
                            <li><?php echo esc_html( ark_t( 'home_visa_assist' ) ); ?></li>
                            <li><?php echo esc_html( ark_t( 'home_travel_insurance' ) ); ?></li>
                        </ul>
                    </div>
                </article>
                <article class="ark-service-parallax-card ark-service-parallax-card-up ark-service-card-3" data-parallax="up">
                    <div class="ark-service-parallax-card-inner">
                        <div class="ark-service-parallax-icon"><i class="fa-solid fa-mosque" aria-hidden="true"></i></div>
                        <h3><?php echo esc_html( ark_t( 'home_umrah_spiritual' ) ); ?></h3>
                        <p><?php echo esc_html( ark_t( 'home_umrah_desc' ) ); ?></p>
                        <ul class="ark-service-parallax-features">
                            <li><?php echo esc_html( ark_t( 'home_complete_packages' ) ); ?></li>
                            <li><?php echo esc_html( ark_t( 'home_hotels_haram' ) ); ?></li>
                            <li><?php echo esc_html( ark_t( 'home_expert_guides' ) ); ?></li>
                        </ul>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <!-- Featured package -->
    <section class="ark-section ark-featured-package ark-parallax ark-section-parallax" data-parallax-section>
        <div class="ark-parallax-layer ark-parallax-speed-mid"></div>
        <div class="ark-container ark-featured-inner">
            <span class="ark-badge"><?php echo esc_html( ark_t( 'home_most_popular' ) ); ?></span>
            <h2 class="ark-featured-title"><?php echo esc_html( ark_t( 'home_ramadan_2026' ) ); ?></h2>
            <p class="ark-featured-highlights"><?php echo esc_html( ark_t( 'home_15_days' ) ); ?> &middot; <?php echo esc_html( ark_t( 'home_5_star' ) ); ?> &middot; <?php echo esc_html( ark_t( 'home_expert_guides' ) ); ?></p>
            <p class="ark-featured-price"><?php echo esc_html( ark_t( 'home_starting_sek' ) ); ?></p>
            <div class="ark-featured-ctas">
                <a href="<?php echo esc_url( ark_url( '/fly/' ) ); ?>" class="btn-primary"><?php echo esc_html( ark_t( 'home_book_flights_now' ) ); ?></a>
                <a href="<?php echo esc_url( ark_url( '/umrah/' ) ); ?>" class="btn-glass"><?php echo esc_html( ark_t( 'home_view_umrah' ) ); ?></a>
            </div>
        </div>
    </section>

    <!-- Why Choose ARK -->
    <section class="ark-section ark-why ark-section-parallax" data-parallax-section>
        <div class="ark-container">
            <h2 class="ark-section-title"><?php echo esc_html( ark_t( 'home_why_choose' ) ); ?></h2>
            <div class="ark-why-grid">
                <article class="ark-why-card ark-why-card-1 ark-reveal">
                    <span class="ark-why-number" aria-hidden="true">01</span>
                    <span class="ark-why-icon ark-why-icon-shield" aria-hidden="true"></span>
                    <div class="ark-why-fold" aria-hidden="true"></div>
                    <h3><?php echo esc_html( ark_t( 'home_swedish_standards' ) ); ?></h3>
                    <p><?php echo esc_html( ark_t( 'home_swedish_standards_desc' ) ); ?></p>
                </article>
                <article class="ark-why-card ark-why-card-2 ark-reveal">
                    <span class="ark-why-number" aria-hidden="true">02</span>
                    <span class="ark-why-icon ark-why-icon-tag" aria-hidden="true"></span>
                    <div class="ark-why-fold" aria-hidden="true"></div>
                    <h3><?php echo esc_html( ark_t( 'home_no_hidden' ) ); ?></h3>
                    <p><?php echo esc_html( ark_t( 'home_no_hidden_desc' ) ); ?></p>
                </article>
                <article class="ark-why-card ark-why-card-3 ark-reveal">
                    <span class="ark-why-number" aria-hidden="true">03</span>
                    <span class="ark-why-icon ark-why-icon-globe" aria-hidden="true"></span>
                    <div class="ark-why-fold" aria-hidden="true"></div>
                    <h3><?php echo esc_html( ark_t( 'home_multilingual' ) ); ?></h3>
                    <p><?php echo esc_html( ark_t( 'home_multilingual_desc' ) ); ?></p>
                </article>
                <article class="ark-why-card ark-why-card-4 ark-reveal">
                    <span class="ark-why-number" aria-hidden="true">04</span>
                    <span class="ark-why-icon ark-why-icon-lock" aria-hidden="true"></span>
                    <div class="ark-why-fold" aria-hidden="true"></div>
                    <h3><?php echo esc_html( ark_t( 'home_insured_gdpr' ) ); ?></h3>
                    <p><?php echo esc_html( ark_t( 'home_insured_gdpr_desc' ) ); ?></p>
                </article>
            </div>
        </div>
    </section>

    <?php get_template_part( 'template-parts/testimonial-polaroid' ); ?>

    <!-- Final CTA -->
    <section class="ark-section ark-cta-final ark-section-parallax" data-parallax-section>
        <div class="ark-cta-final-bg"></div>
        <div class="ark-container ark-cta-final-inner">
            <h2 class="ark-cta-final-title"><?php echo esc_html( ark_t( 'home_begin_journey' ) ); ?></h2>
            <div class="ark-cta-final-buttons">
                <a href="<?php echo esc_url( ark_url( '/fly/' ) ); ?>" class="btn-primary"><?php echo esc_html( ark_t( 'home_book_flight' ) ); ?></a>
                <a href="<?php echo esc_url( ark_url( '/contact/' ) ); ?>" class="btn-glass"><?php echo esc_html( ark_t( 'home_contact_us' ) ); ?></a>
            </div>
            <p class="ark-cta-final-trust"><?php echo esc_html( ark_t( 'home_fully_protected' ) ); ?> &middot; <?php echo esc_html( ark_t( 'home_gdpr_compliant' ) ); ?> &middot; <?php echo esc_html( ark_t( 'home_no_obligations' ) ); ?></p>
        </div>
    </section>
</main>

<?php
get_footer();
