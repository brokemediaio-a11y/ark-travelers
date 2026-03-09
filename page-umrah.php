<?php
/**
 * Umrah packages listing – hero, filter sidebar, package grid (8 sample packages)
 *
 * @package ARK_Travelers
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();

$packages_query = new WP_Query( array(
    'post_type'      => 'umrah',
    'posts_per_page' => 50,
    'post_status'    => 'publish',
    'orderby'        => 'menu_order title',
    'order'          => 'ASC',
) );
$packages_count = $packages_query->found_posts;
?>

<main id="main" class="ark-main ark-main-umrah site-main">
    <section class="ark-umrah-hero">
        <div class="ark-umrah-hero-overlay"></div>
        <div class="ark-container ark-umrah-hero-inner">
            <h1 class="ark-umrah-hero-title"><?php echo esc_html( ark_t( 'umrah_hero_title' ) ); ?></h1>
            <p class="ark-umrah-hero-subtitle"><?php echo esc_html( ark_t( 'umrah_hero_subtitle' ) ); ?></p>
        </div>
    </section>

    <div class="ark-umrah-layout">
        <aside class="ark-umrah-sidebar" id="ark-umrah-sidebar">
            <form class="ark-filters-form" id="ark-filters-form" aria-label="<?php echo esc_attr( ark_t( 'umrah_filters_aria' ) ); ?>">
                <h3 class="ark-filters-title"><?php echo esc_html( ark_t( 'umrah_filters' ) ); ?></h3>

                <div class="ark-filter-group">
                    <label for="ark-departure-month"><?php echo esc_html( ark_t( 'umrah_departure_month' ) ); ?></label>
                    <select id="ark-departure-month" name="departure">
                        <option value=""><?php echo esc_html( ark_t( 'umrah_any' ) ); ?></option>
                        <option value="2025-12"><?php echo esc_html( ark_t( 'umrah_dec_2025' ) ); ?></option>
                        <option value="2026-01"><?php echo esc_html( ark_t( 'umrah_jan_2026' ) ); ?></option>
                        <option value="2026-02"><?php echo esc_html( ark_t( 'umrah_feb_2026' ) ); ?></option>
                        <option value="2026-03"><?php echo esc_html( ark_t( 'umrah_mar_2026' ) ); ?></option>
                        <option value="2026-04"><?php echo esc_html( ark_t( 'umrah_apr_2026' ) ); ?></option>
                        <option value="2026-05"><?php echo esc_html( ark_t( 'umrah_may_2026' ) ); ?></option>
                        <option value="2026-06"><?php echo esc_html( ark_t( 'umrah_jun_2026' ) ); ?></option>
                    </select>
                </div>

                <div class="ark-filter-group">
                    <span class="ark-filter-label"><?php echo esc_html( ark_t( 'umrah_duration' ) ); ?></span>
                    <label class="ark-checkbox"><input type="checkbox" name="duration" value="5-7"> <?php echo esc_html( ark_t( 'umrah_5_7_days' ) ); ?></label>
                    <label class="ark-checkbox"><input type="checkbox" name="duration" value="8-14"> <?php echo esc_html( ark_t( 'umrah_8_14_days' ) ); ?></label>
                    <label class="ark-checkbox"><input type="checkbox" name="duration" value="15-21"> <?php echo esc_html( ark_t( 'umrah_15_21_days' ) ); ?></label>
                    <label class="ark-checkbox"><input type="checkbox" name="duration" value="22+"> <?php echo esc_html( ark_t( 'umrah_22_days' ) ); ?></label>
                </div>

                <div class="ark-filter-group">
                    <label for="ark-price-min"><?php echo esc_html( ark_t( 'umrah_price_range' ) ); ?></label>
                    <div class="ark-price-range">
                        <input type="number" id="ark-price-min" name="price_min" min="20000" max="60000" value="20000" step="1000">
                        <span>–</span>
                        <input type="number" id="ark-price-max" name="price_max" min="20000" max="60000" value="60000" step="1000">
                    </div>
                </div>

                <div class="ark-filter-group">
                    <span class="ark-filter-label"><?php echo esc_html( ark_t( 'umrah_star_rating' ) ); ?></span>
                    <label class="ark-checkbox"><input type="checkbox" name="stars" value="5"> 5 <?php echo esc_html( ark_t( 'umrah_stars' ) ); ?></label>
                    <label class="ark-checkbox"><input type="checkbox" name="stars" value="4"> 4 <?php echo esc_html( ark_t( 'umrah_stars' ) ); ?></label>
                    <label class="ark-checkbox"><input type="checkbox" name="stars" value="3"> 3 <?php echo esc_html( ark_t( 'umrah_stars' ) ); ?></label>
                </div>

                <div class="ark-filter-group">
                    <span class="ark-filter-label"><?php echo esc_html( ark_t( 'umrah_package_type' ) ); ?></span>
                    <label class="ark-checkbox"><input type="checkbox" name="type" value="Essential"> <?php echo esc_html( ark_t( 'umrah_essential' ) ); ?></label>
                    <label class="ark-checkbox"><input type="checkbox" name="type" value="Standard"> <?php echo esc_html( ark_t( 'umrah_standard' ) ); ?></label>
                    <label class="ark-checkbox"><input type="checkbox" name="type" value="Premium"> <?php echo esc_html( ark_t( 'umrah_premium' ) ); ?></label>
                    <label class="ark-checkbox"><input type="checkbox" name="type" value="Luxury"> <?php echo esc_html( ark_t( 'umrah_luxury' ) ); ?></label>
                </div>

                <button type="button" class="ark-btn-reset" id="ark-reset-filters"><?php echo esc_html( ark_t( 'umrah_reset' ) ); ?></button>
            </form>
        </aside>

        <div class="ark-umrah-main">
            <div class="ark-packages-toolbar">
                <p class="ark-packages-count" id="ark-packages-count"><?php echo esc_html( $packages_count . ' ' . ( $packages_count === 1 ? ark_t( 'umrah_package' ) : ark_t( 'umrah_packages' ) ) ); ?></p>
                <div class="ark-packages-sort">
                    <label for="ark-sort"><?php echo esc_html( ark_t( 'umrah_sort_by' ) ); ?></label>
                    <select id="ark-sort" name="sort">
                        <option value="popular"><?php echo esc_html( ark_t( 'umrah_popular' ) ); ?></option>
                        <option value="price-asc"><?php echo esc_html( ark_t( 'umrah_price_low_high' ) ); ?></option>
                        <option value="price-desc"><?php echo esc_html( ark_t( 'umrah_price_high_low' ) ); ?></option>
                        <option value="duration"><?php echo esc_html( ark_t( 'umrah_duration_sort' ) ); ?></option>
                        <option value="departure"><?php echo esc_html( ark_t( 'umrah_departure_sort' ) ); ?></option>
                    </select>
                </div>
            </div>

            <div class="ark-packages-grid" id="ark-packages-grid">
                <?php
                if ( $packages_query->have_posts() ) :
                    while ( $packages_query->have_posts() ) :
                        $packages_query->the_post();
                        $pid = get_the_ID();
                        $price = get_post_meta( $pid, 'price_sek', true );
                        if ( $price === '' || $price === false ) {
                            $price = get_post_meta( $pid, 'ark_price', true ) ?: '34,900';
                        }
                        $days = (int) get_post_meta( $pid, 'ark_duration', true ) ?: 15;
                        $stars = (int) get_post_meta( $pid, 'ark_stars', true ) ?: 5;
                        $badge = get_post_meta( $pid, 'ark_badge', true ) ?: '';
                        $departure = get_post_meta( $pid, 'ark_departure', true ) ?: '';
                        $duration_key = get_post_meta( $pid, 'ark_duration_key', true ) ?: '';
                        $type = get_post_meta( $pid, 'ark_type', true ) ?: '';
                        $price_num = (int) str_replace( array( ',', ' ' ), '', $price );
                        $thumb = get_the_post_thumbnail_url( $pid, 'package-card' ) ?: 'https://images.unsplash.com/photo-1511895426328-dc8714191300?w=600&h=338';
                        ?>
                    <article class="ark-package-card card" data-departure="<?php echo esc_attr( $departure ); ?>" data-duration="<?php echo esc_attr( $duration_key ); ?>" data-stars="<?php echo esc_attr( $stars ); ?>" data-type="<?php echo esc_attr( $type ); ?>" data-price="<?php echo esc_attr( $price_num ); ?>" data-days="<?php echo esc_attr( $days ); ?>">
                        <a href="<?php the_permalink(); ?>" class="ark-package-card-link">
                            <div class="ark-package-card-image-wrap">
                                <img class="ark-package-card-image" src="<?php echo esc_url( $thumb ); ?>" alt="" loading="lazy" width="600" height="338">
                                <?php if ( $badge ) : ?>
                                    <span class="ark-package-badge"><?php echo esc_html( $badge ); ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="ark-package-card-body">
                                <h3 class="ark-package-card-title"><?php the_title(); ?></h3>
                                <p class="ark-package-card-meta"><?php echo esc_html( $days . ' ' . ark_t( 'umrah_days' ) . ' · ' . $stars . ' ' . ark_t( 'umrah_stars' ) ); ?></p>
                                <p class="ark-package-card-price"><?php echo esc_html( 'SEK ' . str_replace( ',', ' ', $price ) ); ?></p>
                                <ul class="ark-package-features">
                                    <li><?php echo esc_html( ark_t( 'umrah_flights' ) ); ?></li>
                                    <li><?php echo esc_html( ark_t( 'umrah_hotels' ) ); ?></li>
                                    <li><?php echo esc_html( ark_t( 'umrah_visa_support' ) ); ?></li>
                                </ul>
                                <span class="ark-package-card-cta"><?php echo esc_html( ark_t( 'umrah_view_details' ) ); ?></span>
                            </div>
                        </a>
                    </article>
                <?php
                    endwhile;
                    wp_reset_postdata();
                else :
                    ?>
                    <p class="ark-no-packages"><?php echo esc_html( ark_t( 'umrah_no_packages' ) ); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<?php
get_footer();
