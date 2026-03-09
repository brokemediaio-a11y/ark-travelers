<?php
/**
 * About Us – hero, story, why ARK, journey, trust, CTA
 * Unique, modern, elegant. No team section.
 *
 * @package ARK_Travelers
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();
?>

<main id="main" class="ark-main ark-main-about site-main">
    <section class="ark-about-hero">
        <div class="ark-about-hero-bg" style="background-image: url('https://images.unsplash.com/photo-1547483238-f400e65ccd56?w=1920');"></div>
        <div class="ark-about-hero-overlay"></div>
        <div class="ark-container ark-about-hero-inner">
            <p class="ark-about-hero-eyebrow ark-reveal"><?php echo esc_html( ark_t( 'About ARK Travelers' ) ); ?></p>
            <h1 class="ark-about-hero-title ark-reveal"><?php echo esc_html( ark_t( 'Your Journey, Our Purpose' ) ); ?></h1>
            <p class="ark-about-hero-subtitle ark-reveal"><?php echo esc_html( ark_t( 'Sweden\'s trusted partner for Umrah and meaningful travel' ) ); ?></p>
        </div>
        <div class="ark-about-hero-scroll">
            <span class="ark-about-hero-scroll-line"></span>
        </div>
    </section>

    <section class="ark-section ark-about-story" aria-labelledby="about-story-heading">
        <div class="ark-container ark-about-story-inner">
            <div class="ark-about-story-content">
                <h2 id="about-story-heading" class="ark-about-story-title ark-reveal"><?php echo esc_html( ark_t( 'Our Story' ) ); ?></h2>
                <div class="ark-about-story-text ark-reveal">
                    <p><?php echo esc_html( ark_t( 'Ark Travelers AB is more than a travel company—it\'s a service built on real experience, real challenges, and a genuine desire to help.' ) ); ?></p>
                    <p><?php echo esc_html( ark_t( 'Since 2016, our founder has dedicated themselves to making Umrah and international travel easier for families and individuals, especially those who struggled with unclear information or stressful arrangements. By 2025, Ark Travelers AB officially established its base in Sweden, bringing this mission to the wider European and global community.' ) ); ?></p>
                    <p><?php echo esc_html( ark_t( 'Today, we provide a full range of travel services, including Umrah packages, flights, hotel bookings, excursions, and visa assistance—all handled with sincerity, care, and personal attention.' ) ); ?></p>
                    <h3 class="ark-about-trust-heading"><?php echo esc_html( ark_t( 'Why people trust us:' ) ); ?></h3>
                    <ul class="ark-about-trust-list">
                        <li><?php echo esc_html( ark_t( 'We listen to your needs' ) ); ?></li>
                        <li><?php echo esc_html( ark_t( 'We guide with honesty and clarity' ) ); ?></li>
                        <li><?php echo esc_html( ark_t( 'We prepare you step-by-step' ) ); ?></li>
                        <li><?php echo esc_html( ark_t( 'We support you throughout your journey' ) ); ?></li>
                    </ul>
                    <p><?php echo esc_html( ark_t( 'At Ark Travelers AB, we are committed to making your travel smooth and stress-free, standing by you from the moment you dream of your trip until you safely return home.' ) ); ?></p>
                </div>
            </div>
            <div class="ark-about-story-visual ark-reveal">
                <div class="ark-about-story-image-wrap">
                    <img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/arkoffice.jpg' ); ?>" alt="<?php echo esc_attr( ark_t( 'ARK Travelers office' ) ); ?>" width="800" height="533" loading="lazy">
                </div>
            </div>
        </div>
    </section>

    <section class="ark-section ark-about-why" aria-labelledby="about-why-heading">
        <div class="ark-container">
            <p class="ark-about-why-eyebrow ark-reveal"><?php echo esc_html( ark_t( 'Why choose us' ) ); ?></p>
            <h2 id="about-why-heading" class="ark-about-why-title ark-reveal"><?php echo esc_html( ark_t( 'What Makes ARK Stand Out' ) ); ?></h2>
            <div class="ark-about-why-grid">
                <article class="ark-about-why-card ark-reveal">
                    <span class="ark-about-why-icon" aria-hidden="true">◆</span>
                    <h3><?php echo esc_html( ark_t( 'Integrity & Transparency' ) ); ?></h3>
                    <p><?php echo esc_html( ark_t( 'No hidden fees. Clear pricing and honest advice so you can book with confidence.' ) ); ?></p>
                </article>
                <article class="ark-about-why-card ark-reveal">
                    <span class="ark-about-why-icon" aria-hidden="true">✓</span>
                    <h3><?php echo esc_html( ark_t( 'Tailored to You' ) ); ?></h3>
                    <p><?php echo esc_html( ark_t( 'Your journey is unique. We tailor every detail to your needs, timeline, and budget.' ) ); ?></p>
                </article>
                <article class="ark-about-why-card ark-about-why-card-featured ark-reveal">
                    <span class="ark-about-why-icon" aria-hidden="true">♥</span>
                    <h3><?php echo esc_html( ark_t( 'Cultural Respect' ) ); ?></h3>
                    <p><?php echo esc_html( ark_t( 'We honour the spiritual significance of Umrah and the trust you place in us.' ) ); ?></p>
                </article>
                <article class="ark-about-why-card ark-reveal">
                    <span class="ark-about-why-icon" aria-hidden="true">◷</span>
                    <h3><?php echo esc_html( ark_t( 'Excellence in Detail' ) ); ?></h3>
                    <p><?php echo esc_html( ark_t( 'From visa support to hotel proximity and guides—we aim for the highest standards.' ) ); ?></p>
                </article>
            </div>
        </div>
    </section>

    <section class="ark-section ark-about-journey" aria-labelledby="about-journey-heading">
        <div class="ark-container">
            <p class="ark-about-journey-eyebrow ark-reveal"><?php echo esc_html( ark_t( 'Our path' ) ); ?></p>
            <h2 id="about-journey-heading" class="ark-about-journey-title ark-reveal"><?php echo esc_html( ark_t( 'Key Milestones' ) ); ?></h2>
            <div class="ark-about-journey-track">
                <div class="ark-about-journey-line" aria-hidden="true"></div>
                <div class="ark-about-journey-milestones">
                    <div class="ark-about-milestone ark-reveal">
                        <span class="ark-about-milestone-dot"></span>
                        <span class="ark-about-milestone-year"><?php echo esc_html( ark_t( 'Founded' ) ); ?></span>
                        <p><?php echo esc_html( ark_t( 'Started in Stockholm with a focus on dignified spiritual travel.' ) ); ?></p>
                    </div>
                    <div class="ark-about-milestone ark-reveal">
                        <span class="ark-about-milestone-dot"></span>
                        <span class="ark-about-milestone-year"><?php echo esc_html( ark_t( 'IATA Certified' ) ); ?></span>
                        <p><?php echo esc_html( ark_t( 'Official recognition and airline partnerships for your peace of mind.' ) ); ?></p>
                    </div>
                    <div class="ark-about-milestone ark-reveal">
                        <span class="ark-about-milestone-dot"></span>
                        <span class="ark-about-milestone-year"><?php echo esc_html( ark_t( 'Today' ) ); ?></span>
                        <p><?php echo esc_html( ark_t( 'Trusted by thousands for Umrah and international travel from the Nordics.' ) ); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="ark-section ark-about-trust" aria-labelledby="about-trust-heading">
        <div class="ark-container">
            <h2 id="about-trust-heading" class="ark-about-trust-title ark-reveal"><?php echo esc_html( ark_t( 'Certifications & Trust' ) ); ?></h2>
            <div class="ark-about-trust-badges">
                <span class="ark-about-badge ark-reveal"><?php echo esc_html( ark_t( 'IATA Certified' ) ); ?></span>
                <span class="ark-about-badge ark-reveal"><?php echo esc_html( ark_t( 'Ministry of Hajj Approved' ) ); ?></span>
                <span class="ark-about-badge ark-reveal"><?php echo esc_html( ark_t( 'Airline partnerships' ) ); ?></span>
                <span class="ark-about-badge ark-reveal"><?php echo esc_html( ark_t( 'Secure booking' ) ); ?></span>
            </div>
        </div>
    </section>

    <section class="ark-section ark-about-cta">
        <div class="ark-about-cta-bg"></div>
        <div class="ark-container ark-about-cta-inner">
            <h2 class="ark-about-cta-title ark-reveal"><?php echo esc_html( ark_t( 'Ready to Begin Your Journey?' ) ); ?></h2>
            <p class="ark-about-cta-text ark-reveal"><?php echo esc_html( ark_t( 'Get in touch for a personalised quote or to ask any question.' ) ); ?></p>
            <a href="<?php echo esc_url( ark_url( '/contact/' ) ); ?>" class="btn-primary ark-about-cta-btn ark-reveal"><?php echo esc_html( ark_t( 'Contact Us' ) ); ?></a>
        </div>
    </section>
</main>

<?php
get_footer();
