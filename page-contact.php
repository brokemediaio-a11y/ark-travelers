<?php
/**
 * Contact – hero, form (AJAX), contact info, map, FAQ accordion
 *
 * @package ARK_Travelers
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();
?>

<main id="main" class="ark-main ark-main-contact site-main">
    <section class="ark-contact-hero">
        <div class="ark-container">
            <h1 class="ark-contact-hero-title"><?php echo esc_html( ark_t( 'contact_hero_title' ) ); ?></h1>
            <p class="ark-contact-hero-subtitle"><?php echo esc_html( ark_t( 'contact_hero_subtitle' ) ); ?></p>
        </div>
    </section>

    <div class="ark-contact-layout">
        <div class="ark-contact-main">
            <form id="ark-contact-form" class="ark-contact-form card" aria-label="<?php echo esc_attr( ark_t( 'contact_form_aria' ) ); ?>">
                <?php wp_nonce_field( 'ark_nonce', 'ark_contact_nonce' ); ?>
                <input type="hidden" name="ark_lang" value="<?php echo esc_attr( ark_lang() ); ?>">
                <div class="ark-form-row">
                    <div class="ark-form-group">
                        <label for="ark-contact-name"><?php echo esc_html( ark_t( 'contact_name' ) ); ?> <span class="required">*</span></label>
                        <input type="text" id="ark-contact-name" name="name" required>
                    </div>
                    <div class="ark-form-group">
                        <label for="ark-contact-email"><?php echo esc_html( ark_t( 'contact_email' ) ); ?> <span class="required">*</span></label>
                        <input type="email" id="ark-contact-email" name="email" required>
                    </div>
                </div>
                <div class="ark-form-group">
                    <label for="ark-contact-phone"><?php echo esc_html( ark_t( 'contact_phone' ) ); ?></label>
                    <input type="tel" id="ark-contact-phone" name="phone">
                </div>
                <div class="ark-form-group">
                    <label for="ark-contact-subject"><?php echo esc_html( ark_t( 'contact_subject' ) ); ?></label>
                    <select id="ark-contact-subject" name="subject">
                        <option value="General Inquiry"><?php echo esc_html( ark_t( 'contact_subject_general' ) ); ?></option>
                        <option value="Umrah Packages"><?php echo esc_html( ark_t( 'contact_subject_umrah' ) ); ?></option>
                        <option value="Flight Booking"><?php echo esc_html( ark_t( 'contact_subject_flight' ) ); ?></option>
                        <option value="Existing Booking"><?php echo esc_html( ark_t( 'contact_subject_booking' ) ); ?></option>
                        <option value="Complaint"><?php echo esc_html( ark_t( 'contact_subject_complaint' ) ); ?></option>
                    </select>
                </div>
                <div class="ark-form-group">
                    <label for="ark-contact-message"><?php echo esc_html( ark_t( 'contact_message' ) ); ?> <span class="required">*</span></label>
                    <textarea id="ark-contact-message" name="message" rows="5" required></textarea>
                </div>
                <div class="ark-form-group">
                    <label class="ark-checkbox">
                        <input type="checkbox" name="privacy" value="1" required>
                        <?php echo esc_html( ark_t( 'contact_privacy' ) ); ?>
                    </label>
                </div>
                <div id="ark-contact-message-box" class="ark-form-message" role="alert" aria-live="polite"></div>
                <button type="submit" class="btn-primary"><?php echo esc_html( ark_t( 'contact_submit' ) ); ?></button>
            </form>
        </div>

        <aside class="ark-contact-sidebar">
            <div class="ark-contact-info card">
                <h3><?php echo esc_html( ark_t( 'contact_info' ) ); ?></h3>
                <p class="ark-contact-address">
                    <?php echo esc_html( ark_t( 'contact_address_placeholder' ) ); ?><br>
                    <?php echo esc_html( ark_t( 'contact_city_placeholder' ) ); ?><br>
                    <?php echo esc_html( ark_t( 'contact_sweden' ) ); ?>
                </p>
                <p><strong><?php echo esc_html( ark_t( 'contact_phone_label' ) ); ?></strong> <a href="tel:+46000000000">+46 XX XXX XX XX</a></p>
                <p><strong><?php echo esc_html( ark_t( 'contact_email_label' ) ); ?></strong> <a href="mailto:info@arktravelers.com">info@arktravelers.com</a></p>
                <p><strong><?php echo esc_html( ark_t( 'contact_hours' ) ); ?></strong><br>
                    <?php echo esc_html( ark_t( 'contact_mon_fri' ) ); ?><br>
                    <?php echo esc_html( ark_t( 'contact_sat' ) ); ?><br>
                    <?php echo esc_html( ark_t( 'contact_sun' ) ); ?><br>
                    <?php echo esc_html( ark_t( 'contact_24_7_line' ) ); ?>
                </p>
                <a href="https://wa.me/46000000000" class="ark-whatsapp-btn" target="_blank" rel="noopener">WhatsApp</a>
            </div>

            <div class="ark-contact-map">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2032.991381559888!2d18.06894831533406!3d59.32932348166224!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x465f9d6796555555%3A0x52963c60b481386!2sStockholm%2C%20Sweden!5e0!3m2!1sen!2s!4v1640000000000!5m2!1sen!2s" width="100%" height="200" style="border:0;border-radius:12px;" allowfullscreen="" loading="lazy" title="Map"></iframe>
            </div>
        </aside>
    </div>

    <section class="ark-section ark-contact-faq" id="faq" aria-labelledby="contact-faq-heading">
        <div class="ark-container ark-contact-faq-inner">
            <p class="ark-contact-faq-eyebrow"><?php echo esc_html( ark_t( 'contact_faq_eyebrow' ) ); ?></p>
            <h2 id="contact-faq-heading" class="ark-contact-faq-title"><?php echo esc_html( ark_t( 'contact_faq_title' ) ); ?></h2>
            <div class="ark-accordion ark-contact-accordion">
                <div class="ark-accordion-item">
                    <button type="button" class="ark-accordion-head" aria-expanded="false"><?php echo esc_html( ark_t( 'contact_faq_1_q' ) ); ?></button>
                    <div class="ark-accordion-body"><p><?php echo esc_html( ark_t( 'contact_faq_1_a' ) ); ?></p></div>
                </div>
                <div class="ark-accordion-item">
                    <button type="button" class="ark-accordion-head" aria-expanded="false"><?php echo esc_html( ark_t( 'contact_faq_2_q' ) ); ?></button>
                    <div class="ark-accordion-body"><p><?php echo esc_html( ark_t( 'contact_faq_2_a' ) ); ?></p></div>
                </div>
                <div class="ark-accordion-item">
                    <button type="button" class="ark-accordion-head" aria-expanded="false"><?php echo esc_html( ark_t( 'contact_faq_3_q' ) ); ?></button>
                    <div class="ark-accordion-body"><p><?php echo esc_html( ark_t( 'contact_faq_3_a' ) ); ?></p></div>
                </div>
                <div class="ark-accordion-item">
                    <button type="button" class="ark-accordion-head" aria-expanded="false"><?php echo esc_html( ark_t( 'contact_faq_4_q' ) ); ?></button>
                    <div class="ark-accordion-body"><p><?php echo esc_html( ark_t( 'contact_faq_4_a' ) ); ?></p></div>
                </div>
                <div class="ark-accordion-item">
                    <button type="button" class="ark-accordion-head" aria-expanded="false"><?php echo esc_html( ark_t( 'contact_faq_5_q' ) ); ?></button>
                    <div class="ark-accordion-body"><p><?php echo esc_html( ark_t( 'contact_faq_5_a' ) ); ?></p></div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php
get_footer();
