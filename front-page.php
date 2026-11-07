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

    <!-- Trust credentials marquee -->
    <section class="ark-section ark-trust-bar ark-section-parallax" aria-label="<?php echo esc_attr( ark_t( 'Trust credentials' ) ); ?>" data-parallax-section>
        <div class="ark-marquee">
            <div class="ark-marquee-inner">
                <span class="ark-marquee-item"><?php echo esc_html( ark_t( 'Trusted Since 2016' ) ); ?></span>
                <span class="ark-marquee-item"><?php echo esc_html( ark_t( '100+ Airlines Partnered' ) ); ?></span>
                <span class="ark-marquee-item"><?php echo esc_html( ark_t( '24/7 Swedish Support' ) ); ?></span>
                <span class="ark-marquee-item"><?php echo esc_html( ark_t( '50+ Destinations Worldwide' ) ); ?></span>
                <span class="ark-marquee-item"><?php echo esc_html( ark_t( 'Ministry of Hajj Approved' ) ); ?></span>
                <span class="ark-marquee-item"><?php echo esc_html( ark_t( 'GDPR Compliant' ) ); ?></span>
                <span class="ark-marquee-item"><?php echo esc_html( ark_t( '15,000+ Happy Travellers' ) ); ?></span>
                <span class="ark-marquee-item"><?php echo esc_html( ark_t( 'Trusted Since 2016' ) ); ?></span>
                <span class="ark-marquee-item"><?php echo esc_html( ark_t( '100+ Airlines Partnered' ) ); ?></span>
                <span class="ark-marquee-item"><?php echo esc_html( ark_t( '24/7 Swedish Support' ) ); ?></span>
                <span class="ark-marquee-item"><?php echo esc_html( ark_t( '50+ Destinations Worldwide' ) ); ?></span>
            </div>
        </div>
    </section>

    <!-- Our Services – three cards with scroll parallax (1 & 3: bottom→up, 2: up→down) -->
    <section class="ark-section ark-services ark-services-parallax ark-section-parallax" id="ark-services" aria-labelledby="services-heading" data-parallax-section>
        <div class="ark-container">
            <h2 id="services-heading" class="ark-section-title"><?php echo esc_html( ark_t( 'Our Services' ) ); ?></h2>
            <div class="ark-services-parallax-grid">
                <article class="ark-service-parallax-card ark-service-parallax-card-up ark-service-card-1" data-parallax="up">
                    <div class="ark-service-parallax-card-inner">
                        <div class="ark-service-parallax-icon"><i class="fa-solid fa-plane-departure" aria-hidden="true"></i></div>
                        <h3><?php echo esc_html( ark_t( 'Flight Booking Made Easy' ) ); ?></h3>
                        <p><?php echo esc_html( ark_t( 'Compare fares across 100+ airlines and book in minutes. One-way, round-trip, or multi-city — we find the best routes and prices so you don\'t have to.' ) ); ?></p>
                        <ul class="ark-service-parallax-features">
                            <li><?php echo esc_html( ark_t( 'Best price guarantee' ) ); ?></li>
                            <li><?php echo esc_html( ark_t( 'Multi-city & round-trips' ) ); ?></li>
                            <li><?php echo esc_html( ark_t( 'Instant e-ticket delivery' ) ); ?></li>
                        </ul>
                    </div>
                </article>
                <article class="ark-service-parallax-card ark-service-parallax-card-down ark-service-card-2" data-parallax="down">
                    <div class="ark-service-parallax-card-inner">
                        <div class="ark-service-parallax-icon"><i class="fa-solid fa-passport" aria-hidden="true"></i></div>
                        <h3><?php echo esc_html( ark_t( 'Visas, Tickets & Travel Docs' ) ); ?></h3>
                        <p><?php echo esc_html( ark_t( 'From boarding passes to visa applications, we handle every document in one place — so you arrive prepared and stress-free.' ) ); ?></p>
                        <ul class="ark-service-parallax-features">
                            <li><?php echo esc_html( ark_t( 'Boarding passes & e-tickets' ) ); ?></li>
                            <li><?php echo esc_html( ark_t( 'Visa application support' ) ); ?></li>
                            <li><?php echo esc_html( ark_t( 'Travel insurance' ) ); ?></li>
                        </ul>
                    </div>
                </article>
                <article class="ark-service-parallax-card ark-service-parallax-card-up ark-service-card-3" data-parallax="up">
                    <div class="ark-service-parallax-card-inner">
                        <div class="ark-service-parallax-icon"><i class="fa-solid fa-mosque" aria-hidden="true"></i></div>
                        <h3><?php echo esc_html( ark_t( 'Umrah & Spiritual Journeys' ) ); ?></h3>
                        <p><?php echo esc_html( ark_t( 'Complete Umrah packages with flights, hotels near Haram, expert guides, and full spiritual support.' ) ); ?></p>
                        <ul class="ark-service-parallax-features">
                            <li><?php echo esc_html( ark_t( 'Complete packages' ) ); ?></li>
                            <li><?php echo esc_html( ark_t( 'Hotels near Haram' ) ); ?></li>
                            <li><?php echo esc_html( ark_t( 'Expert guides' ) ); ?></li>
                        </ul>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <!-- Umrah packages (general) -->
    <section class="ark-section ark-featured-package ark-parallax ark-section-parallax" data-parallax-section>
        <div class="ark-parallax-layer ark-parallax-speed-mid"></div>
        <div class="ark-container ark-featured-inner">
            <span class="ark-badge"><?php echo esc_html( ark_t( 'Umrah Packages' ) ); ?></span>
            <h2 class="ark-featured-title"><?php echo esc_html( ark_t( 'Umrah Packages for Every Pilgrim' ) ); ?></h2>
            <p class="ark-featured-highlights"><?php echo esc_html( ark_t( 'From spiritual retreats to group pilgrimages—flights, hotels near the Haram, and expert guidance.' ) ); ?></p>
            <div class="ark-featured-ctas">
                <a href="<?php echo esc_url( ark_url( '/fly/' ) ); ?>" class="btn-primary"><?php echo esc_html( ark_t( 'Book Flights Now' ) ); ?></a>
                <a href="<?php echo esc_url( ark_url( '/umrah/' ) ); ?>" class="btn-glass"><?php echo esc_html( ark_t( 'View Umrah Packages' ) ); ?></a>
            </div>
        </div>
    </section>

    <!-- Why Choose ARK -->
    <section class="ark-section ark-why ark-section-parallax" data-parallax-section>
        <div class="ark-container">
            <h2 class="ark-section-title"><?php echo esc_html( ark_t( 'Why Choose ARK' ) ); ?></h2>
            <div class="ark-why-grid">
                <article class="ark-why-card ark-why-card-1 ark-reveal">
                    <span class="ark-why-number" aria-hidden="true">01</span>
                    <span class="ark-why-icon ark-why-icon-shield" aria-hidden="true"></span>
                    <div class="ark-why-fold" aria-hidden="true"></div>
                    <h3><?php echo esc_html( ark_t( 'Swedish Standards + Islamic Values' ) ); ?></h3>
                    <p><?php echo esc_html( ark_t( 'We combine Nordic reliability with deep respect for your faith and traditions.' ) ); ?></p>
                </article>
                <article class="ark-why-card ark-why-card-2 ark-reveal">
                    <span class="ark-why-number" aria-hidden="true">02</span>
                    <span class="ark-why-icon ark-why-icon-tag" aria-hidden="true"></span>
                    <div class="ark-why-fold" aria-hidden="true"></div>
                    <h3><?php echo esc_html( ark_t( 'No Hidden Costs Ever' ) ); ?></h3>
                    <p><?php echo esc_html( ark_t( 'Transparent pricing with no surprise fees. What you see is what you pay.' ) ); ?></p>
                </article>
                <article class="ark-why-card ark-why-card-3 ark-reveal">
                    <span class="ark-why-number" aria-hidden="true">03</span>
                    <span class="ark-why-icon ark-why-icon-globe" aria-hidden="true"></span>
                    <div class="ark-why-fold" aria-hidden="true"></div>
                    <h3><?php echo esc_html( ark_t( 'Multilingual Support' ) ); ?></h3>
                    <p><?php echo esc_html( ark_t( 'Swedish, English, and Arabic so you can communicate in comfort.' ) ); ?></p>
                </article>
                <article class="ark-why-card ark-why-card-4 ark-reveal">
                    <span class="ark-why-number" aria-hidden="true">04</span>
                    <span class="ark-why-icon ark-why-icon-lock" aria-hidden="true"></span>
                    <div class="ark-why-fold" aria-hidden="true"></div>
                    <h3><?php echo esc_html( ark_t( 'Fully Insured & GDPR Protected' ) ); ?></h3>
                    <p><?php echo esc_html( ark_t( 'Your data and your journey are protected to the highest standards.' ) ); ?></p>
                </article>
            </div>
        </div>
    </section>

    <!-- Boarding Pass Destination Showcase -->
    <?php
    $ark_destinations = array(
        array(
            'code'     => 'JED',
            'city'     => ark_t( 'Makkah' ),
            'country'  => ark_t( 'Saudi Arabia' ),
            'flag'     => '🇸🇦',
            'duration' => '5h 30m',
            'flight'   => 'ARK 247',
            'gate'     => 'A12',
            'seat'     => '14A',
            'image'    => 'https://images.unsplash.com/photo-1591604129939-f1efa4d9f7fa?w=1400&q=85&fit=crop',
            'accent'   => '#d4a857',
        ),
        array(
            'code'     => 'DXB',
            'city'     => ark_t( 'Dubai' ),
            'country'  => ark_t( 'United Arab Emirates' ),
            'flag'     => '🇦🇪',
            'duration' => '6h 15m',
            'flight'   => 'ARK 182',
            'gate'     => 'B07',
            'seat'     => '08F',
            'image'    => 'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?w=1400&q=85&fit=crop',
            'accent'   => '#c98e57',
        ),
        array(
            'code'     => 'IST',
            'city'     => ark_t( 'Istanbul' ),
            'country'  => ark_t( 'Turkey' ),
            'flag'     => '🇹🇷',
            'duration' => '3h 45m',
            'flight'   => 'ARK 311',
            'gate'     => 'C04',
            'seat'     => '12D',
            'image'    => 'https://images.unsplash.com/photo-1524231757912-21f4fe3a7200?w=1400&q=85&fit=crop',
            'accent'   => '#9b5d8a',
        ),
        array(
            'code'     => 'CAI',
            'city'     => ark_t( 'Cairo' ),
            'country'  => ark_t( 'Egypt' ),
            'flag'     => '🇪🇬',
            'duration' => '5h 10m',
            'flight'   => 'ARK 405',
            'gate'     => 'A05',
            'seat'     => '19C',
            'image'    => 'https://images.unsplash.com/photo-1539768942893-daf53e448371?w=1400&q=85&fit=crop',
            'accent'   => '#b58656',
        ),
        array(
            'code'     => 'ATH',
            'city'     => ark_t( 'Athens' ),
            'country'  => ark_t( 'Greece' ),
            'flag'     => '🇬🇷',
            'duration' => '4h 20m',
            'flight'   => 'ARK 502',
            'gate'     => 'B11',
            'seat'     => '06A',
            'image'    => 'https://images.unsplash.com/photo-1555993539-1732b0258235?w=1400&q=85&fit=crop',
            'accent'   => '#5b87a8',
        ),
        array(
            'code'     => 'RUH',
            'city'     => ark_t( 'Riyadh' ),
            'country'  => ark_t( 'Saudi Arabia' ),
            'flag'     => '🇸🇦',
            'duration' => '6h 50m',
            'flight'   => 'ARK 619',
            'gate'     => 'A09',
            'seat'     => '03B',
            'image'    => 'https://images.unsplash.com/photo-1586724237569-f3d0c1dee8c6?w=1400&q=85&fit=crop',
            'accent'   => '#7a8c6a',
        ),
    );
    ?>
    <section class="ark-section ark-routes-explorer ark-section-parallax" id="ark-routes-explorer" aria-labelledby="ark-routes-heading" data-parallax-section>
        <div class="ark-routes-bg" aria-hidden="true">
            <div class="ark-routes-stars"></div>
            <div class="ark-routes-glow"></div>
        </div>
        <div class="ark-container">
            <header class="ark-routes-header">
                <span class="ark-routes-eyebrow ark-reveal"><?php echo esc_html( ark_t( 'Departing daily from Stockholm' ) ); ?></span>
                <h2 id="ark-routes-heading" class="ark-routes-title ark-reveal"><?php echo esc_html( ark_t( 'Where will you fly next?' ) ); ?></h2>
                <p class="ark-routes-subtitle ark-reveal"><?php echo esc_html( ark_t( 'Six of our most-loved routes, ready to take off. Pick a destination and we\'ll handle every detail of your journey.' ) ); ?></p>
            </header>

            <div class="ark-routes-stage ark-reveal" data-routes-stage>
                <button type="button" class="ark-routes-nav ark-routes-nav-prev" data-routes-prev aria-label="<?php echo esc_attr( ark_t( 'Previous destination' ) ); ?>">
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M15 6l-6 6 6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>

                <div class="ark-routes-deck" data-routes-deck>
                    <?php foreach ( $ark_destinations as $i => $dest ) : ?>
                        <article
                            class="ark-route-pass <?php echo $i === 0 ? 'is-active' : ''; ?>"
                            data-index="<?php echo (int) $i; ?>"
                            style="--route-accent: <?php echo esc_attr( $dest['accent'] ); ?>; --route-image: url('<?php echo esc_url( $dest['image'] ); ?>');"
                            aria-hidden="<?php echo $i === 0 ? 'false' : 'true'; ?>"
                        >
                            <div class="ark-route-pass-bg" aria-hidden="true"></div>
                            <div class="ark-route-pass-grain" aria-hidden="true"></div>

                            <div class="ark-route-pass-inner">
                                <div class="ark-route-pass-header">
                                    <div class="ark-route-brand">
                                        <span class="ark-route-brand-mark">A</span>
                                        <span class="ark-route-brand-name">ARK<small><?php echo esc_html( ark_t( 'Travelers' ) ); ?></small></span>
                                    </div>
                                    <span class="ark-route-pass-tag"><?php echo esc_html( ark_t( 'Boarding Pass' ) ); ?></span>
                                </div>

                                <div class="ark-route-pass-route">
                                    <div class="ark-route-endpoint">
                                        <span class="ark-route-airport-code">ARN</span>
                                        <span class="ark-route-airport-name">Stockholm</span>
                                    </div>

                                    <div class="ark-route-pass-arc" aria-hidden="true">
                                        <svg viewBox="0 0 200 60" preserveAspectRatio="none">
                                            <path class="ark-route-arc-path" d="M 8 50 Q 100 -20 192 50" fill="none" stroke="currentColor" stroke-width="1.4" stroke-dasharray="3 4" stroke-linecap="round"/>
                                        </svg>
                                        <span class="ark-route-arc-plane">
                                            <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M21 16v-2l-8-5V3.5C13 2.7 12.3 2 11.5 2S10 2.7 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5z"/></svg>
                                        </span>
                                    </div>

                                    <div class="ark-route-endpoint ark-route-endpoint-to">
                                        <span class="ark-route-airport-code" data-route-code><?php echo esc_html( $dest['code'] ); ?></span>
                                        <span class="ark-route-airport-name" data-route-city><?php echo esc_html( $dest['city'] ); ?> <?php echo esc_html( $dest['flag'] ); ?></span>
                                    </div>
                                </div>

                                <div class="ark-route-pass-meta">
                                    <div class="ark-route-meta-item">
                                        <span class="ark-route-meta-label"><?php echo esc_html( ark_t( 'Duration' ) ); ?></span>
                                        <span class="ark-route-meta-value"><?php echo esc_html( $dest['duration'] ); ?></span>
                                    </div>
                                    <div class="ark-route-meta-item">
                                        <span class="ark-route-meta-label"><?php echo esc_html( ark_t( 'Flight' ) ); ?></span>
                                        <span class="ark-route-meta-value"><?php echo esc_html( $dest['flight'] ); ?></span>
                                    </div>
                                    <div class="ark-route-meta-item">
                                        <span class="ark-route-meta-label"><?php echo esc_html( ark_t( 'Gate' ) ); ?></span>
                                        <span class="ark-route-meta-value"><?php echo esc_html( $dest['gate'] ); ?></span>
                                    </div>
                                    <div class="ark-route-meta-item">
                                        <span class="ark-route-meta-label"><?php echo esc_html( ark_t( 'Seat' ) ); ?></span>
                                        <span class="ark-route-meta-value"><?php echo esc_html( $dest['seat'] ); ?></span>
                                    </div>
                                </div>

                                <div class="ark-route-pass-tear" aria-hidden="true">
                                    <span class="ark-route-tear-notch ark-route-tear-notch-left"></span>
                                    <span class="ark-route-tear-line"></span>
                                    <span class="ark-route-tear-notch ark-route-tear-notch-right"></span>
                                </div>

                                <div class="ark-route-pass-footer">
                                    <div class="ark-route-footer-info">
                                        <span class="ark-route-meta-label"><?php echo esc_html( ark_t( 'Destination' ) ); ?></span>
                                        <span class="ark-route-footer-city" data-route-country><?php echo esc_html( $dest['country'] ); ?></span>
                                    </div>
                                    <div class="ark-route-barcode" aria-hidden="true">
                                        <?php for ( $b = 0; $b < 32; $b++ ) : ?><span></span><?php endfor; ?>
                                    </div>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>

                <button type="button" class="ark-routes-nav ark-routes-nav-next" data-routes-next aria-label="<?php echo esc_attr( ark_t( 'Next destination' ) ); ?>">
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M9 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
            </div>

            <div class="ark-routes-dots ark-reveal" role="tablist" aria-label="<?php echo esc_attr( ark_t( 'Choose destination' ) ); ?>">
                <?php foreach ( $ark_destinations as $i => $dest ) : ?>
                    <button
                        type="button"
                        class="ark-routes-dot <?php echo $i === 0 ? 'is-active' : ''; ?>"
                        data-routes-dot="<?php echo (int) $i; ?>"
                        role="tab"
                        aria-selected="<?php echo $i === 0 ? 'true' : 'false'; ?>"
                        aria-label="<?php echo esc_attr( $dest['city'] ); ?>"
                    >
                        <span class="ark-routes-dot-inner"></span>
                        <span class="ark-routes-dot-label"><?php echo esc_html( $dest['code'] ); ?></span>
                    </button>
                <?php endforeach; ?>
            </div>

            <div class="ark-routes-cta ark-reveal">
                <a href="<?php echo esc_url( ark_url( '/fly/' ) ); ?>" class="btn-primary"><?php echo esc_html( ark_t( 'Plan This Trip' ) ); ?></a>
                <span class="ark-routes-cta-aside"><?php echo esc_html( ark_t( 'Or request a custom route — we\'ll find the best fare.' ) ); ?></span>
            </div>
        </div>
    </section>

    <?php get_template_part( 'template-parts/google-reviews' ); ?>

    <!-- Final CTA -->
    <section class="ark-section ark-cta-final ark-section-parallax" data-parallax-section>
        <div class="ark-cta-final-bg"></div>
        <div class="ark-container ark-cta-final-inner">
            <h2 class="ark-cta-final-title"><?php echo esc_html( ark_t( 'Begin Your Journey Today' ) ); ?></h2>
            <div class="ark-cta-final-buttons">
                <a href="<?php echo esc_url( ark_url( '/fly/' ) ); ?>" class="btn-primary"><?php echo esc_html( ark_t( 'Book Your Flight' ) ); ?></a>
                <a href="<?php echo esc_url( ark_url( '/contact/' ) ); ?>" class="btn-glass"><?php echo esc_html( ark_t( 'Contact Us' ) ); ?></a>
            </div>
            <p class="ark-cta-final-trust"><?php echo esc_html( ark_t( 'Fully protected' ) ); ?> &middot; <?php echo esc_html( ark_t( 'GDPR compliant' ) ); ?> &middot; <?php echo esc_html( ark_t( 'No obligations' ) ); ?></p>
        </div>
    </section>
</main>

<?php
get_footer();
