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
                    <option value="+46" data-flag="se" selected>+46</option>
                    <option value="+47" data-flag="no">+47</option>
                    <option value="+45" data-flag="dk">+45</option>
                    <option value="+358" data-flag="fi">+358</option>
                    <option value="+1" data-flag="us">+1</option>
                    <option value="+44" data-flag="gb">+44</option>
                    <option value="+49" data-flag="de">+49</option>
                    <option value="+33" data-flag="fr">+33</option>
                    <option value="+39" data-flag="it">+39</option>
                    <option value="+34" data-flag="es">+34</option>
                    <option value="+90" data-flag="tr">+90</option>
                    <option value="+966" data-flag="sa">+966</option>
                    <option value="+971" data-flag="ae">+971</option>
                    <option value="+974" data-flag="qa">+974</option>
                    <option value="+965" data-flag="kw">+965</option>
                    <option value="+968" data-flag="om">+968</option>
                    <option value="+962" data-flag="jo">+962</option>
                    <option value="+973" data-flag="bh">+973</option>
                    <option value="+20" data-flag="eg">+20</option>
                    <option value="+212" data-flag="ma">+212</option>
                    <option value="+216" data-flag="tn">+216</option>
                    <option value="+213" data-flag="dz">+213</option>
                    <option value="+93" data-flag="af">+93</option>
                    <option value="+880" data-flag="bd">+880</option>
                    <option value="+94" data-flag="lk">+94</option>
                    <option value="+31" data-flag="nl">+31</option>
                    <option value="+32" data-flag="be">+32</option>
                    <option value="+41" data-flag="ch">+41</option>
                    <option value="+43" data-flag="at">+43</option>
                    <option value="+48" data-flag="pl">+48</option>
                    <option value="+351" data-flag="pt">+351</option>
                    <option value="+7" data-flag="ru">+7</option>
                    <option value="+91" data-flag="in">+91</option>
                    <option value="+92" data-flag="pk">+92</option>
                    <option value="+60" data-flag="my">+60</option>
                    <option value="+62" data-flag="id">+62</option>
                    <option value="+234" data-flag="ng">+234</option>
                    <option value="+27" data-flag="za">+27</option>
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
