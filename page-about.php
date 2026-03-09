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
            <p class="ark-about-hero-eyebrow ark-reveal"><?php echo esc_html( ark_t( 'about_eyebrow' ) ); ?></p>
            <h1 class="ark-about-hero-title ark-reveal"><?php echo esc_html( ark_t( 'about_hero_title' ) ); ?></h1>
            <p class="ark-about-hero-subtitle ark-reveal"><?php echo esc_html( ark_t( 'about_hero_subtitle' ) ); ?></p>
        </div>
        <div class="ark-about-hero-scroll">
            <span class="ark-about-hero-scroll-line"></span>
        </div>
    </section>

    <section class="ark-section ark-about-story" aria-labelledby="about-story-heading">
        <div class="ark-container ark-about-story-inner">
            <div class="ark-about-story-content">
                <h2 id="about-story-heading" class="ark-about-story-title ark-reveal"><?php echo esc_html( ark_t( 'about_story' ) ); ?></h2>
                <div class="ark-about-story-text ark-reveal">
                    <p><?php echo esc_html( ark_t( 'about_story_intro' ) ); ?></p>
                    <p><?php echo esc_html( ark_t( 'about_story_history' ) ); ?></p>
                    <p><?php echo esc_html( ark_t( 'about_story_services' ) ); ?></p>
                    <h3 class="ark-about-trust-heading"><?php echo esc_html( ark_t( 'about_trust_heading' ) ); ?></h3>
                    <ul class="ark-about-trust-list">
                        <li><?php echo esc_html( ark_t( 'about_trust_1' ) ); ?></li>
                        <li><?php echo esc_html( ark_t( 'about_trust_2' ) ); ?></li>
                        <li><?php echo esc_html( ark_t( 'about_trust_3' ) ); ?></li>
                        <li><?php echo esc_html( ark_t( 'about_trust_4' ) ); ?></li>
                    </ul>
                    <p><?php echo esc_html( ark_t( 'about_story_commitment' ) ); ?></p>
                </div>
            </div>
            <div class="ark-about-story-visual ark-reveal">
                <div class="ark-about-story-image-wrap">
                    <img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/arkoffice.jpg' ); ?>" alt="<?php echo esc_attr( ark_t( 'about_office_alt' ) ); ?>" width="800" height="533" loading="lazy">
                </div>
            </div>
        </div>
    </section>

    <section class="ark-section ark-about-why" aria-labelledby="about-why-heading">
        <div class="ark-container">
            <p class="ark-about-why-eyebrow ark-reveal"><?php echo esc_html( ark_t( 'about_why_eyebrow' ) ); ?></p>
            <h2 id="about-why-heading" class="ark-about-why-title ark-reveal"><?php echo esc_html( ark_t( 'about_why_title' ) ); ?></h2>
            <div class="ark-about-why-grid">
                <article class="ark-about-why-card ark-reveal">
                    <span class="ark-about-why-icon" aria-hidden="true">◆</span>
                    <h3><?php echo esc_html( ark_t( 'about_integrity' ) ); ?></h3>
                    <p><?php echo esc_html( ark_t( 'about_integrity_desc' ) ); ?></p>
                </article>
                <article class="ark-about-why-card ark-reveal">
                    <span class="ark-about-why-icon" aria-hidden="true">✓</span>
                    <h3><?php echo esc_html( ark_t( 'about_tailored' ) ); ?></h3>
                    <p><?php echo esc_html( ark_t( 'about_tailored_desc' ) ); ?></p>
                </article>
                <article class="ark-about-why-card ark-about-why-card-featured ark-reveal">
                    <span class="ark-about-why-icon" aria-hidden="true">♥</span>
                    <h3><?php echo esc_html( ark_t( 'about_cultural' ) ); ?></h3>
                    <p><?php echo esc_html( ark_t( 'about_cultural_desc' ) ); ?></p>
                </article>
                <article class="ark-about-why-card ark-reveal">
                    <span class="ark-about-why-icon" aria-hidden="true">◷</span>
                    <h3><?php echo esc_html( ark_t( 'about_excellence' ) ); ?></h3>
                    <p><?php echo esc_html( ark_t( 'about_excellence_desc' ) ); ?></p>
                </article>
            </div>
        </div>
    </section>

    <section class="ark-section ark-about-journey" aria-labelledby="about-journey-heading">
        <div class="ark-container">
            <p class="ark-about-journey-eyebrow ark-reveal"><?php echo esc_html( ark_t( 'about_journey_eyebrow' ) ); ?></p>
            <h2 id="about-journey-heading" class="ark-about-journey-title ark-reveal"><?php echo esc_html( ark_t( 'about_journey_title' ) ); ?></h2>
            <div class="ark-about-journey-track">
                <div class="ark-about-journey-line" aria-hidden="true"></div>
                <div class="ark-about-journey-milestones">
                    <div class="ark-about-milestone ark-reveal">
                        <span class="ark-about-milestone-dot"></span>
                        <span class="ark-about-milestone-year"><?php echo esc_html( ark_t( 'about_founded' ) ); ?></span>
                        <p><?php echo esc_html( ark_t( 'about_founded_desc' ) ); ?></p>
                    </div>
                    <div class="ark-about-milestone ark-reveal">
                        <span class="ark-about-milestone-dot"></span>
                        <span class="ark-about-milestone-year"><?php echo esc_html( ark_t( 'about_iata_cert' ) ); ?></span>
                        <p><?php echo esc_html( ark_t( 'about_iata_cert_desc' ) ); ?></p>
                    </div>
                    <div class="ark-about-milestone ark-reveal">
                        <span class="ark-about-milestone-dot"></span>
                        <span class="ark-about-milestone-year"><?php echo esc_html( ark_t( 'about_today' ) ); ?></span>
                        <p><?php echo esc_html( ark_t( 'about_today_desc' ) ); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="ark-section ark-about-trust" aria-labelledby="about-trust-heading">
        <div class="ark-container">
            <h2 id="about-trust-heading" class="ark-about-trust-title ark-reveal"><?php echo esc_html( ark_t( 'about_trust_title' ) ); ?></h2>
            <div class="ark-about-trust-badges">
                <span class="ark-about-badge ark-reveal"><?php echo esc_html( ark_t( 'about_badge_iata' ) ); ?></span>
                <span class="ark-about-badge ark-reveal"><?php echo esc_html( ark_t( 'about_badge_hajj' ) ); ?></span>
                <span class="ark-about-badge ark-reveal"><?php echo esc_html( ark_t( 'about_badge_airline' ) ); ?></span>
                <span class="ark-about-badge ark-reveal"><?php echo esc_html( ark_t( 'about_badge_secure' ) ); ?></span>
            </div>
        </div>
    </section>

    <section class="ark-section ark-about-cta">
        <div class="ark-about-cta-bg"></div>
        <div class="ark-container ark-about-cta-inner">
            <h2 class="ark-about-cta-title ark-reveal"><?php echo esc_html( ark_t( 'about_cta_title' ) ); ?></h2>
            <p class="ark-about-cta-text ark-reveal"><?php echo esc_html( ark_t( 'about_cta_text' ) ); ?></p>
            <a href="<?php echo esc_url( ark_url( '/contact/' ) ); ?>" class="btn-primary ark-about-cta-btn ark-reveal"><?php echo esc_html( ark_t( 'about_contact_btn' ) ); ?></a>
        </div>
    </section>
</main>

<?php
get_footer();
