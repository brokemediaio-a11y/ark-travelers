<?php
/**
 * Flight search form – used in hero overlay on fly page
 *
 * @package ARK_Travelers
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<form id="ark-fly-form" class="ark-fly-form card ark-fly-hero-form" action="#" method="get">
    <div class="ark-fly-trip-type">
        <label class="ark-radio"><input type="radio" name="trip" value="roundtrip" checked> <?php echo esc_html( ark_t( 'form_roundtrip' ) ); ?></label>
        <label class="ark-radio"><input type="radio" name="trip" value="oneway"> <?php echo esc_html( ark_t( 'form_oneway' ) ); ?></label>
    </div>
    <div class="ark-fly-form-row">
        <div class="ark-fly-field">
            <label for="ark-origin"><?php echo esc_html( ark_t( 'form_from' ) ); ?></label>
            <input type="text" id="ark-origin" name="origin" placeholder="<?php echo esc_attr( ark_t( 'form_origin_placeholder' ) ); ?>" autocomplete="off" required>
        </div>
        <div class="ark-fly-field">
            <label for="ark-destination"><?php echo esc_html( ark_t( 'form_to' ) ); ?></label>
            <input type="text" id="ark-destination" name="destination" placeholder="<?php echo esc_attr( ark_t( 'form_destination_placeholder' ) ); ?>" autocomplete="off" required>
        </div>
    </div>
    <div class="ark-fly-form-row">
        <div class="ark-fly-field">
            <label for="ark-depart"><?php echo esc_html( ark_t( 'form_departure' ) ); ?></label>
            <input type="date" id="ark-depart" name="depart" required>
        </div>
        <div class="ark-fly-field ark-return-wrap">
            <label for="ark-return"><?php echo esc_html( ark_t( 'form_return' ) ); ?></label>
            <input type="date" id="ark-return" name="return">
        </div>
        <div class="ark-fly-field">
            <label for="ark-class"><?php echo esc_html( ark_t( 'form_class' ) ); ?></label>
            <select id="ark-class" name="class">
                <option value="economy"><?php echo esc_html( ark_t( 'form_economy' ) ); ?></option>
                <option value="premium"><?php echo esc_html( ark_t( 'form_premium' ) ); ?></option>
                <option value="business"><?php echo esc_html( ark_t( 'form_business' ) ); ?></option>
                <option value="first"><?php echo esc_html( ark_t( 'form_first' ) ); ?></option>
            </select>
        </div>
    </div>
    <div class="ark-fly-form-row ark-fly-contact">
        <div class="ark-fly-field">
            <label for="ark-fly-name"><?php echo esc_html( ark_t( 'form_name' ) ); ?></label>
            <input type="text" id="ark-fly-name" name="contact_name" placeholder="<?php echo esc_attr( ark_t( 'form_name_placeholder' ) ); ?>" autocomplete="name" required>
        </div>
    </div>
    <div class="ark-fly-form-row ark-fly-contact">
        <div class="ark-fly-field">
            <label for="ark-fly-email"><?php echo esc_html( ark_t( 'form_email' ) ); ?></label>
            <input type="email" id="ark-fly-email" name="contact_email" placeholder="<?php echo esc_attr( ark_t( 'form_email_placeholder' ) ); ?>" autocomplete="email" required>
        </div>
    </div>
    <div class="ark-fly-form-row">
        <div class="ark-fly-field ark-fly-phone-field">
            <label for="ark-fly-phone"><?php echo esc_html( ark_t( 'form_phone' ) ); ?></label>
            <div class="ark-fly-phone-inline">
                <select id="ark-fly-country" name="contact_country_code" class="ark-fly-country-code" aria-label="<?php echo esc_attr( ark_t( 'form_country_code' ) ); ?>">
                    <option value="+46" selected>🇸🇪 +46</option>
                    <option value="+47">🇳🇴 +47</option>
                    <option value="+45">🇩🇰 +45</option>
                    <option value="+358">🇫🇮 +358</option>
                    <option value="+1">🇺🇸 +1</option>
                    <option value="+44">🇬🇧 +44</option>
                    <option value="+49">🇩🇪 +49</option>
                    <option value="+33">🇫🇷 +33</option>
                    <option value="+39">🇮🇹 +39</option>
                    <option value="+34">🇪🇸 +34</option>
                    <option value="+90">🇹🇷 +90</option>
                    <option value="+966">🇸🇦 +966</option>
                    <option value="+971">🇦🇪 +971</option>
                    <option value="+20">🇪🇬 +20</option>
                    <option value="+31">🇳🇱 +31</option>
                    <option value="+32">🇧🇪 +32</option>
                    <option value="+41">🇨🇭 +41</option>
                    <option value="+43">🇦🇹 +43</option>
                    <option value="+48">🇵🇱 +48</option>
                    <option value="+351">🇵🇹 +351</option>
                    <option value="+7">🇷🇺 +7</option>
                    <option value="+91">🇮🇳 +91</option>
                    <option value="+92">🇵🇰 +92</option>
                    <option value="+60">🇲🇾 +60</option>
                    <option value="+62">🇮🇩 +62</option>
                    <option value="+234">🇳🇬 +234</option>
                    <option value="+27">🇿🇦 +27</option>
                </select>
                <input type="tel" id="ark-fly-phone" name="contact_phone" placeholder="<?php echo esc_attr( ark_t( 'form_phone_placeholder' ) ); ?>" autocomplete="tel" required>
            </div>
        </div>
    </div>
    <div class="ark-fly-form-row ark-fly-passengers">
        <div class="ark-fly-field">
            <label for="ark-adults"><?php echo esc_html( ark_t( 'form_adults' ) ); ?></label>
            <select id="ark-adults" name="adults">
                <?php for ( $i = 1; $i <= 9; $i++ ) : ?>
                    <option value="<?php echo (int) $i; ?>"><?php echo (int) $i; ?></option>
                <?php endfor; ?>
            </select>
        </div>
        <div class="ark-fly-field">
            <label for="ark-children"><?php echo esc_html( ark_t( 'form_children' ) ); ?></label>
            <select id="ark-children" name="children">
                <?php for ( $i = 0; $i <= 9; $i++ ) : ?>
                    <option value="<?php echo (int) $i; ?>"><?php echo (int) $i; ?></option>
                <?php endfor; ?>
            </select>
        </div>
        <div class="ark-fly-field">
            <label for="ark-infants"><?php echo esc_html( ark_t( 'form_infants' ) ); ?></label>
            <select id="ark-infants" name="infants">
                <?php for ( $i = 0; $i <= 4; $i++ ) : ?>
                    <option value="<?php echo (int) $i; ?>"><?php echo (int) $i; ?></option>
                <?php endfor; ?>
            </select>
        </div>
    </div>
    <div class="ark-fly-form-actions">
        <button type="submit" class="btn-primary"><?php echo esc_html( ark_t( 'form_search_flights' ) ); ?></button>
    </div>
</form>
