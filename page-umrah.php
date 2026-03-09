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
            <h1 class="ark-umrah-hero-title"><?php echo esc_html( ark_t( 'Tailored Umrah Journeys' ) ); ?></h1>
            <p class="ark-umrah-hero-subtitle"><?php echo esc_html( ark_t( 'From intimate spiritual retreats to group pilgrimages' ) ); ?></p>
        </div>
    </section>

    <div class="ark-umrah-layout">
        <aside class="ark-umrah-sidebar" id="ark-umrah-sidebar">
            <form class="ark-filters-form" id="ark-filters-form" aria-label="<?php echo esc_attr( ark_t( 'Filter packages' ) ); ?>">
                <h3 class="ark-filters-title"><?php echo esc_html( ark_t( 'Filters' ) ); ?></h3>

                <div class="ark-filter-group">
                    <label for="ark-departure-month"><?php echo esc_html( ark_t( 'Departure Month' ) ); ?></label>
                    <select id="ark-departure-month" name="departure">
                        <option value=""><?php echo esc_html( ark_t( 'Any' ) ); ?></option>
                        <option value="2025-12"><?php echo esc_html( ark_t( 'Dec 2025' ) ); ?></option>
                        <option value="2026-01"><?php echo esc_html( ark_t( 'Jan 2026' ) ); ?></option>
                        <option value="2026-02"><?php echo esc_html( ark_t( 'Feb 2026' ) ); ?></option>
                        <option value="2026-03"><?php echo esc_html( ark_t( 'Mar 2026' ) ); ?></option>
                        <option value="2026-04"><?php echo esc_html( ark_t( 'Apr 2026' ) ); ?></option>
                        <option value="2026-05"><?php echo esc_html( ark_t( 'May 2026' ) ); ?></option>
                        <option value="2026-06"><?php echo esc_html( ark_t( 'Jun 2026' ) ); ?></option>
                    </select>
                </div>

                <div class="ark-filter-group">
                    <span class="ark-filter-label"><?php echo esc_html( ark_t( 'Duration' ) ); ?></span>
                    <label class="ark-checkbox"><input type="checkbox" name="duration" value="5-7"> <?php echo esc_html( ark_t( '5–7 days' ) ); ?></label>
                    <label class="ark-checkbox"><input type="checkbox" name="duration" value="8-14"> <?php echo esc_html( ark_t( '8–14 days' ) ); ?></label>
                    <label class="ark-checkbox"><input type="checkbox" name="duration" value="15-21"> <?php echo esc_html( ark_t( '15–21 days' ) ); ?></label>
                    <label class="ark-checkbox"><input type="checkbox" name="duration" value="22+"> <?php echo esc_html( ark_t( '22+ days' ) ); ?></label>
                </div>

                <div class="ark-filter-group">
                    <label for="ark-price-min"><?php echo esc_html( ark_t( 'Price range (SEK)' ) ); ?></label>
                    <div class="ark-price-range">
                        <input type="number" id="ark-price-min" name="price_min" min="20000" max="60000" value="20000" step="1000">
                        <span>–</span>
                        <input type="number" id="ark-price-max" name="price_max" min="20000" max="60000" value="60000" step="1000">
                    </div>
                </div>

                <div class="ark-filter-group">
                    <span class="ark-filter-label"><?php echo esc_html( ark_t( 'Star rating' ) ); ?></span>
                    <label class="ark-checkbox"><input type="checkbox" name="stars" value="5"> 5 <?php echo esc_html( ark_t( 'stars' ) ); ?></label>
                    <label class="ark-checkbox"><input type="checkbox" name="stars" value="4"> 4 <?php echo esc_html( ark_t( 'stars' ) ); ?></label>
                    <label class="ark-checkbox"><input type="checkbox" name="stars" value="3"> 3 <?php echo esc_html( ark_t( 'stars' ) ); ?></label>
                </div>

                <div class="ark-filter-group">
                    <span class="ark-filter-label"><?php echo esc_html( ark_t( 'Package type' ) ); ?></span>
                    <label class="ark-checkbox"><input type="checkbox" name="type" value="Essential"> <?php echo esc_html( ark_t( 'Essential' ) ); ?></label>
                    <label class="ark-checkbox"><input type="checkbox" name="type" value="Standard"> <?php echo esc_html( ark_t( 'Standard' ) ); ?></label>
                    <label class="ark-checkbox"><input type="checkbox" name="type" value="Premium"> <?php echo esc_html( ark_t( 'Premium' ) ); ?></label>
                    <label class="ark-checkbox"><input type="checkbox" name="type" value="Luxury"> <?php echo esc_html( ark_t( 'Luxury' ) ); ?></label>
                </div>

                <button type="button" class="ark-btn-reset" id="ark-reset-filters"><?php echo esc_html( ark_t( 'Reset filters' ) ); ?></button>
            </form>
        </aside>

        <div class="ark-umrah-main">
            <div class="ark-packages-toolbar">
                <p class="ark-packages-count" id="ark-packages-count">
                    <?php
                    echo esc_html(
                        $packages_count . ' ' . ( $packages_count === 1 ? ark_t( 'package' ) : ark_t( 'packages' ) )
                    );
                    ?>
                </p>
                <div class="ark-packages-sort">
                    <label for="ark-sort"><?php echo esc_html( ark_t( 'Sort by' ) ); ?></label>
                    <select id="ark-sort" name="sort">
                        <option value="popular"><?php echo esc_html( ark_t( 'Popular' ) ); ?></option>
                        <option value="price-asc"><?php echo esc_html( ark_t( 'Price (Low–High)' ) ); ?></option>
                        <option value="price-desc"><?php echo esc_html( ark_t( 'Price (High–Low)' ) ); ?></option>
                        <option value="duration"><?php echo esc_html( ark_t( 'Duration' ) ); ?></option>
                        <option value="departure"><?php echo esc_html( ark_t( 'Departure Date' ) ); ?></option>
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
                                <p class="ark-package-card-meta"><?php echo esc_html( $days . ' ' . ark_t( 'days' ) . ' · ' . $stars . ' ' . ark_t( 'stars' ) ); ?></p>
                                <p class="ark-package-card-price"><?php echo esc_html( 'SEK ' . str_replace( ',', ' ', $price ) ); ?></p>
                                <ul class="ark-package-features">
                                    <li><?php echo esc_html( ark_t( 'Flights' ) ); ?></li>
                                    <li><?php echo esc_html( ark_t( 'Hotels' ) ); ?></li>
                                    <li><?php echo esc_html( ark_t( 'Visa support' ) ); ?></li>
                                </ul>
                                <span class="ark-package-card-cta"><?php echo esc_html( ark_t( 'View Details' ) ); ?></span>
                            </div>
                        </a>
                    </article>
                <?php
                    endwhile;
                    wp_reset_postdata();
                else :
                    ?>
                    <p class="ark-no-packages"><?php echo esc_html( ark_t( 'No packages found. Add Umrah packages in the admin.' ) ); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<?php
get_footer();
