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
            <h1 class="ark-contact-hero-title"><?php echo esc_html( ark_t( 'Get in Touch' ) ); ?></h1>
            <p class="ark-contact-hero-subtitle"><?php echo esc_html( ark_t( 'Our team is here to help 24/7' ) ); ?></p>
        </div>
    </section>

    <div class="ark-contact-layout">
        <div class="ark-contact-main">
            <form id="ark-contact-form" class="ark-contact-form card" aria-label="<?php echo esc_attr( ark_t( 'Contact form' ) ); ?>">
                <?php wp_nonce_field( 'ark_nonce', 'ark_contact_nonce' ); ?>
                <div class="ark-form-row">
                    <div class="ark-form-group">
                        <label for="ark-contact-name"><?php echo esc_html( ark_t( 'Name' ) ); ?> <span class="required">*</span></label>
                        <input type="text" id="ark-contact-name" name="name" required>
                    </div>
                    <div class="ark-form-group">
                        <label for="ark-contact-email"><?php echo esc_html( ark_t( 'Email' ) ); ?> <span class="required">*</span></label>
                        <input type="email" id="ark-contact-email" name="email" required>
                    </div>
                </div>
                <div class="ark-form-group">
                    <label for="ark-contact-phone"><?php echo esc_html( ark_t( 'Phone' ) ); ?></label>
                    <input type="tel" id="ark-contact-phone" name="phone">
                </div>
                <div class="ark-form-group">
                    <label for="ark-contact-subject"><?php echo esc_html( ark_t( 'Subject' ) ); ?></label>
                    <select id="ark-contact-subject" name="subject">
                        <option value="General Inquiry"><?php echo esc_html( ark_t( 'General Inquiry' ) ); ?></option>
                        <option value="Umrah Packages"><?php echo esc_html( ark_t( 'Umrah Packages' ) ); ?></option>
                        <option value="Flight Booking"><?php echo esc_html( ark_t( 'Flight Booking' ) ); ?></option>
                        <option value="Existing Booking"><?php echo esc_html( ark_t( 'Existing Booking' ) ); ?></option>
                        <option value="Complaint"><?php echo esc_html( ark_t( 'Complaint' ) ); ?></option>
                    </select>
                </div>
                <div class="ark-form-group">
                    <label for="ark-contact-message"><?php echo esc_html( ark_t( 'Message' ) ); ?> <span class="required">*</span></label>
                    <textarea id="ark-contact-message" name="message" rows="5" required></textarea>
                </div>
                <div class="ark-form-group">
                    <label class="ark-checkbox">
                        <input type="checkbox" name="privacy" value="1" required>
                        <?php echo esc_html( ark_t( 'I agree to the privacy policy' ) ); ?>
                    </label>
                </div>
                <div id="ark-contact-message-box" class="ark-form-message" role="alert" aria-live="polite"></div>
                <button type="submit" class="btn-primary"><?php echo esc_html( ark_t( 'Submit' ) ); ?></button>
            </form>
        </div>

        <aside class="ark-contact-sidebar">
            <div class="ark-contact-info card">
                <h3><?php echo esc_html( ark_t( 'Contact Info' ) ); ?></h3>
                <p class="ark-contact-address">
                    <?php echo esc_html( ark_t( 'Stockholm, Sweden' ) ); ?><br>
                    <?php echo esc_html( ark_t( 'Kopparbacken 11, 163 46' ) ); ?>
                </p>
                <p><strong><?php echo esc_html( ark_t( 'Phone:' ) ); ?></strong> <a href="tel:+46700101401">+46 700 101 401</a></p>
                <p><strong><?php echo esc_html( ark_t( 'Email:' ) ); ?></strong> <a href="mailto:info@arktravelers.com">info@arktravelers.com</a></p>
                <p><strong><?php echo esc_html( ark_t( 'Business Hours:' ) ); ?></strong><br>
                    <?php echo esc_html( ark_t( 'Mon–Fri: 9:00 – 18:00' ) ); ?><br>
                    <?php echo esc_html( ark_t( 'Sat: 10:00 – 16:00' ) ); ?><br>
                    <?php echo esc_html( ark_t( 'Sun: Closed' ) ); ?><br>
                    <?php echo esc_html( ark_t( '24/7 Emergency Line: +46 700 101 401' ) ); ?>
                </p>
                <a href="https://wa.me/46700101401" class="ark-whatsapp-btn" target="_blank" rel="noopener">WhatsApp</a>
            </div>

            <div class="ark-contact-map">
                <iframe src="https://www.google.com/maps?q=Kopparbacken+11,+163+46+Stockholm,+Sweden&output=embed" width="100%" height="200" style="border:0;border-radius:12px;" allowfullscreen="" loading="lazy" title="Map"></iframe>
            </div>
        </aside>
    </div>

    <section class="ark-section ark-contact-faq" id="faq" aria-labelledby="contact-faq-heading">
        <div class="ark-container ark-contact-faq-inner">
            <p class="ark-contact-faq-eyebrow"><?php echo esc_html( ark_t( 'Got questions?' ) ); ?></p>
            <h2 id="contact-faq-heading" class="ark-contact-faq-title"><?php echo esc_html( ark_t( 'Frequently Asked Questions' ) ); ?></h2>
            <div class="ark-accordion ark-contact-accordion">
                <div class="ark-accordion-item">
                    <button type="button" class="ark-accordion-head" aria-expanded="false"><?php echo esc_html( ark_t( 'How do I book an Umrah package?' ) ); ?></button>
                    <div class="ark-accordion-body"><p><?php echo esc_html( ark_t( 'Contact us via the form, phone, or WhatsApp. We will send you a tailored quote and guide you through the booking process.' ) ); ?></p></div>
                </div>
                <div class="ark-accordion-item">
                    <button type="button" class="ark-accordion-head" aria-expanded="false"><?php echo esc_html( ark_t( 'Do you offer payment plans?' ) ); ?></button>
                    <div class="ark-accordion-body"><p><?php echo esc_html( ark_t( 'Yes, we offer flexible payment options. Details depend on the package and departure date.' ) ); ?></p></div>
                </div>
                <div class="ark-accordion-item">
                    <button type="button" class="ark-accordion-head" aria-expanded="false"><?php echo esc_html( ark_t( 'What if I need to cancel?' ) ); ?></button>
                    <div class="ark-accordion-body"><p><?php echo esc_html( ark_t( 'Our cancellation policy is outlined in your booking terms. We recommend travel insurance.' ) ); ?></p></div>
                </div>
                <div class="ark-accordion-item">
                    <button type="button" class="ark-accordion-head" aria-expanded="false"><?php echo esc_html( ark_t( 'Is visa included?' ) ); ?></button>
                    <div class="ark-accordion-body"><p><?php echo esc_html( ark_t( 'Visa processing assistance is included in our Umrah packages. We guide you through the requirements.' ) ); ?></p></div>
                </div>
                <div class="ark-accordion-item">
                    <button type="button" class="ark-accordion-head" aria-expanded="false"><?php echo esc_html( ark_t( 'Do you serve non-Swedish residents?' ) ); ?></button>
                    <div class="ark-accordion-body"><p><?php echo esc_html( ark_t( 'Yes. We serve travelers from the Nordic region and beyond. Contact us for your specific situation.' ) ); ?></p></div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php
get_footer();
