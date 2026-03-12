<?php
/**
 * ARK Travelers Child Theme Functions
 *
 * @package ARK_Travelers
 * @version 2.3.82
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Legacy multilingual helpers (now language-neutral) for backwards compatibility.
require_once get_stylesheet_directory() . '/inc/ark-multilang.php';

/**
 * Enqueue parent theme
 */
function ark_enqueue_parent_styles() {
    wp_enqueue_style( 'hello-elementor', get_template_directory_uri() . '/style.css', array(), '1.0.0' );
}
add_action( 'wp_enqueue_scripts', 'ark_enqueue_parent_styles', 9 );

/**
 * Enqueue child theme assets
 */
function ark_enqueue_child_assets() {
    $theme_version = wp_get_theme()->get( 'Version' );
    $uri          = get_stylesheet_directory_uri();

    // Google Fonts (Sora, Inter, Caveat for handwritten quotes)
    wp_enqueue_style( 'ark-fonts', 'https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600&family=Inter:wght@400;500;600&family=Caveat:wght@400;500;600&display=swap', array(), null );

    // Base CSS (always load)
    wp_enqueue_style( 'ark-base', $uri . '/assets/css/base.css', array( 'hello-elementor' ), $theme_version );

    // Page-specific CSS (by slug; page-umrah.php etc. are slug templates)
    if ( is_front_page() ) {
        wp_enqueue_style( 'font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css', array(), '6.5.1' );
        // Reuse Fly page hero banner (airplane scroll) styles on the homepage
        wp_enqueue_style( 'ark-fly', $uri . '/assets/css/fly.css', array( 'ark-base' ), $theme_version );
        wp_enqueue_style( 'ark-home', $uri . '/assets/css/home.css', array( 'ark-base', 'ark-fly' ), $theme_version );
    }
    if ( is_page( 'umrah' ) ) {
        wp_enqueue_style( 'ark-umrah', $uri . '/assets/css/umrah.css', array( 'ark-base' ), $theme_version );
    }
    if ( is_page( 'fly' ) ) {
        wp_enqueue_style( 'ark-fly', $uri . '/assets/css/fly.css', array( 'ark-base' ), $theme_version );
    }
    if ( is_page( 'about' ) ) {
        wp_enqueue_style( 'ark-about', $uri . '/assets/css/about.css', array( 'ark-base' ), $theme_version );
    }
    if ( is_page( 'contact' ) ) {
        wp_enqueue_style( 'ark-contact', $uri . '/assets/css/contact.css', array( 'ark-base' ), $theme_version );
    }
    if ( is_singular( 'umrah' ) ) {
        wp_enqueue_style( 'ark-single-umrah', $uri . '/assets/css/single-umrah.css', array( 'ark-base' ), $theme_version );
    }
    $is_woo_cart_or_checkout = false;
    if ( function_exists( 'is_cart' ) && function_exists( 'is_checkout' ) ) {
        $is_woo_cart_or_checkout = is_cart() || is_checkout();
    }
    if ( ! $is_woo_cart_or_checkout && is_page() ) {
        $current_page = get_post();
        if ( $current_page instanceof WP_Post ) {
            $page_content = (string) $current_page->post_content;
            $is_woo_cart_or_checkout = has_shortcode( $page_content, 'woocommerce_cart' ) || has_shortcode( $page_content, 'woocommerce_checkout' );
        }
    }
    if ( $is_woo_cart_or_checkout ) {
        $woo_style_deps = array( 'ark-base' );
        foreach ( array( 'woocommerce-general', 'woocommerce-layout' ) as $woo_handle ) {
            if ( wp_style_is( $woo_handle, 'registered' ) ) {
                $woo_style_deps[] = $woo_handle;
            }
        }
        wp_enqueue_style( 'ark-woocommerce', $uri . '/assets/css/woocommerce.css', $woo_style_deps, $theme_version );
    }

    // Main JavaScript (always load, no jQuery)
    wp_enqueue_script( 'ark-main', $uri . '/assets/js/main.js', array(), $theme_version, true );

    // Page-specific JavaScript
    if ( is_front_page() ) {
        // GSAP + ScrollTrigger for overlapping video parallax (homepage only)
        wp_enqueue_script( 'gsap', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js', array(), '3.12.5', true );
        wp_enqueue_script( 'gsap-scrolltrigger', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js', array( 'gsap' ), '3.12.5', true );
        // Use the same Airplane Scroll animation as the Fly page for the homepage hero
        wp_enqueue_script( 'airplane-scroll', $uri . '/assets/js/airplane-scroll.js', array( 'ark-main' ), $theme_version, true );
        wp_enqueue_script( 'ark-video-overlap', $uri . '/assets/js/video-overlap-parallax.js', array( 'ark-main', 'gsap', 'gsap-scrolltrigger' ), $theme_version, true );
        wp_enqueue_script( 'ark-animations', $uri . '/assets/js/animations.js', array( 'ark-main', 'airplane-scroll' ), $theme_version, true );
        
        // Localize script with frame count and folder path (reuse Fly page config)
        wp_localize_script( 'airplane-scroll', 'airplaneScrollConfig', array(
            'frameCount'         => 93,  // Desktop landscape frames
            'framesFolder'       => $uri . '/assets/images/airplane%20zip/',
            'mobileFrameCount'   => 240, // Portrait frames for mobile/tablet
            'mobileFramesFolder' => $uri . '/assets/images/airplane%20mobile%20zip/',
            'themeUrl'           => $uri,
        ) );
    }
    if ( is_page( 'umrah' ) ) {
        wp_enqueue_script( 'ark-packages', $uri . '/assets/js/packages.js', array( 'ark-main' ), $theme_version, true );
    }
    if ( is_page( 'fly' ) ) {
        // Load animations.js for parallax effects (no airplane-scroll animation)
        wp_enqueue_script( 'ark-animations', $uri . '/assets/js/animations.js', array( 'ark-main' ), $theme_version, true );
        wp_enqueue_script( 'ark-fly-js', $uri . '/assets/js/fly.js', array( 'ark-main' ), $theme_version, true );
    }
    if ( is_singular( 'umrah' ) ) {
        wp_enqueue_script( 'ark-single-umrah', $uri . '/assets/js/single-umrah.js', array( 'ark-main' ), $theme_version, true );
    }

    // Localize script
    wp_localize_script( 'ark-main', 'arkData', array(
        'ajaxUrl'  => admin_url( 'admin-ajax.php' ),
        'homeUrl'  => home_url( '/' ),
        'themeUrl' => $uri,
        'nonce'    => wp_create_nonce( 'ark_nonce' ),
    ) );
}
add_action( 'wp_enqueue_scripts', 'ark_enqueue_child_assets', 10 );

/**
 * Add body class for Fly page (same pattern as front-page for no-gap hero)
 */
function ark_body_class_page_fly( $classes ) {
    if ( is_page( 'fly' ) ) {
        $classes[] = 'page-fly';
    }
    return $classes;
}
add_filter( 'body_class', 'ark_body_class_page_fly' );

/**
 * Force dedicated templates for WooCommerce Cart/Checkout pages
 * even if page slugs differ from cart/checkout.
 */
function ark_force_woocommerce_page_templates( $template ) {
    if ( function_exists( 'is_cart' ) && is_cart() ) {
        $cart_template = locate_template( 'page-cart.php' );
        if ( $cart_template ) {
            return $cart_template;
        }
    }

    if ( function_exists( 'is_checkout' ) && is_checkout() ) {
        $is_order_received = function_exists( 'is_order_received_page' ) && is_order_received_page();
        if ( ! $is_order_received ) {
            $checkout_template = locate_template( 'page-checkout.php' );
            if ( $checkout_template ) {
                return $checkout_template;
            }
        }
    }

    return $template;
}
add_filter( 'template_include', 'ark_force_woocommerce_page_templates', 30 );

/**
 * Theme setup
 */
function ark_theme_setup() {
    load_child_theme_textdomain( 'ark-travelers', get_stylesheet_directory() . '/languages' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'custom-logo', array(
        'height'      => 80,
        'width'       => 240,
        'flex-height' => true,
        'flex-width'  => true,
        'header-text' => array( 'site-title', 'site-description' ),
    ) );
    add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );

    register_nav_menus( array(
        'primary' => __( 'Primary Menu', 'ark-travelers' ),
        'footer'  => __( 'Footer Menu', 'ark-travelers' ),
    ) );

    add_image_size( 'package-card', 1200, 675, true );
    add_image_size( 'package-hero', 1920, 1080, true );
    add_image_size( 'team-member', 400, 400, true );

    // WooCommerce support (when plugin is active)
    if ( class_exists( 'WooCommerce' ) ) {
        add_theme_support( 'woocommerce' );
        add_theme_support( 'wc-product-gallery-zoom' );
        add_theme_support( 'wc-product-gallery-lightbox' );
    }
}
add_action( 'after_setup_theme', 'ark_theme_setup' );

/**
 * Create required front-end pages (Fly, Umrah, About, Contact) if they don't exist.
 * Runs on theme activation so the FLY menu link works; safe to run again (skips existing).
 */
function ark_create_required_pages() {
    $pages = array(
        'fly' => array(
            'title'   => _x( 'FLY', 'Page title', 'ark-travelers' ),
            'content'  => '',
            'slug'     => 'fly',
        ),
        'umrah' => array(
            'title'   => __( 'Umrah Packages', 'ark-travelers' ),
            'content'  => '',
            'slug'     => 'umrah',
        ),
        'about' => array(
            'title'   => __( 'About Us', 'ark-travelers' ),
            'content'  => '',
            'slug'     => 'about',
        ),
        'contact' => array(
            'title'   => __( 'Contact', 'ark-travelers' ),
            'content'  => '',
            'slug'     => 'contact',
        ),
    );

    foreach ( $pages as $key => $data ) {
        if ( get_page_by_path( $data['slug'], OBJECT, 'page' ) ) {
            continue;
        }
        wp_insert_post( array(
            'post_title'   => $data['title'],
            'post_name'    => $data['slug'],
            'post_content' => $data['content'],
            'post_status'  => 'publish',
            'post_type'    => 'page',
            'post_author'  => 1,
        ) );
    }

    // Flush rewrite rules so /package/slug/ URLs work for Umrah CPT.
    flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'ark_create_required_pages' );

/**
 * Create WooCommerce Cart and Checkout pages when WooCommerce is active and pages are missing.
 * Sets WC options so the plugin uses these pages. Safe to run multiple times.
 */
function ark_maybe_create_woocommerce_pages() {
    if ( ! class_exists( 'WooCommerce' ) ) {
        return;
    }
    $cart_page_id     = (int) get_option( 'woocommerce_cart_page_id', 0 );
    $checkout_page_id = (int) get_option( 'woocommerce_checkout_page_id', 0 );

    if ( ! $cart_page_id || ! get_post( $cart_page_id ) ) {
        $cart_id = wp_insert_post( array(
            'post_title'   => __( 'Cart', 'ark-travelers' ),
            'post_name'    => 'cart',
            'post_content' => '[woocommerce_cart]',
            'post_status'  => 'publish',
            'post_type'    => 'page',
            'post_author'  => 1,
        ) );
        if ( $cart_id && ! is_wp_error( $cart_id ) ) {
            update_option( 'woocommerce_cart_page_id', $cart_id );
        }
    }

    if ( ! $checkout_page_id || ! get_post( $checkout_page_id ) ) {
        $checkout_id = wp_insert_post( array(
            'post_title'   => __( 'Checkout', 'ark-travelers' ),
            'post_name'    => 'checkout',
            'post_content' => '[woocommerce_checkout]',
            'post_status'  => 'publish',
            'post_type'    => 'page',
            'post_author'  => 1,
        ) );
        if ( $checkout_id && ! is_wp_error( $checkout_id ) ) {
            update_option( 'woocommerce_checkout_page_id', $checkout_id );
        }
    }
}
add_action( 'init', 'ark_maybe_create_woocommerce_pages', 21 );

/* ----------------------------------------------------------------------------
 * Umrah CPT → WooCommerce Auto-Create + Auto-Sync
 * Admin manages only Umrah CPT; Woo products are system-managed and hidden.
 * ---------------------------------------------------------------------------- */

/**
 * Get or create WooCommerce product category "Umrah". Returns term_id or 0.
 */
function ark_get_or_create_umrah_product_category() {
    if ( ! taxonomy_exists( 'product_cat' ) ) {
        return 0;
    }
    $term = get_term_by( 'slug', 'umrah', 'product_cat' );
    if ( $term && ! is_wp_error( $term ) ) {
        return (int) $term->term_id;
    }
    $insert = wp_insert_term( 'Umrah', 'product_cat', array( 'slug' => 'umrah' ) );
    if ( is_wp_error( $insert ) ) {
        return 0;
    }
    return (int) $insert['term_id'];
}

/**
 * Auto-create or sync hidden Woo product when Umrah CPT is saved (ACF save_post).
 */
function ark_umrah_sync_woo_product( $post_id ) {
    static $running = false;
    if ( $running ) {
        return;
    }

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    $post = get_post( $post_id );
    if ( ! $post || $post->post_type !== 'umrah' ) {
        return;
    }
    if ( $post->post_status !== 'publish' ) {
        return;
    }
    if ( ! class_exists( 'WooCommerce' ) || ! class_exists( 'WC_Product_Simple' ) ) {
        return;
    }

    $running = true;

    $linked_product_id = (int) get_post_meta( $post_id, 'linked_product_id', true );
    $price_raw         = get_post_meta( $post_id, 'price_sek', true );
    $price             = is_numeric( str_replace( array( ',', ' ' ), '', $price_raw ) ) ? (float) str_replace( array( ',', ' ' ), '', $price_raw ) : 0;
    $duration          = get_post_meta( $post_id, 'duration', true );
    $includes          = get_post_meta( $post_id, 'includes', true );
    $short_desc        = $post->post_excerpt ? $post->post_excerpt : '';
    $long_parts        = array( $post->post_content );
    if ( $duration !== '' && $duration !== false ) {
        $long_parts[] = "\n\nDuration: " . $duration;
    }
    if ( $includes !== '' && $includes !== false ) {
        $long_parts[] = "\n\nIncludes: " . $includes;
    }
    $long_desc = implode( '', $long_parts );
    $thumb_id  = (int) get_post_thumbnail_id( $post_id );
    $cat_id    = ark_get_or_create_umrah_product_category();

    if ( ! $linked_product_id ) {
        $product = new WC_Product_Simple();
        $product->set_name( $post->post_title );
        $product->set_regular_price( (string) $price );
        $product->set_price( (string) $price );
        $product->set_virtual( true );
        $product->set_catalog_visibility( 'hidden' );
        $product->set_status( 'publish' );
        $product->set_short_description( $short_desc );
        $product->set_description( $long_desc );
        if ( $thumb_id ) {
            $product->set_image_id( $thumb_id );
        }
        $product->save();
        if ( $cat_id ) {
            wp_set_object_terms( $product->get_id(), array( $cat_id ), 'product_cat' );
        }
        update_post_meta( $post_id, 'linked_product_id', $product->get_id() );
    } else {
        $product = wc_get_product( $linked_product_id );
        if ( $product ) {
            $product->set_name( $post->post_title );
            $product->set_regular_price( (string) $price );
            $product->set_price( (string) $price );
            $product->set_virtual( true );
            $product->set_catalog_visibility( 'hidden' );
            $product->set_short_description( $short_desc );
            $product->set_description( $long_desc );
            if ( $thumb_id ) {
                $product->set_image_id( $thumb_id );
            }
            $product->save();
            if ( $cat_id ) {
                wp_set_object_terms( $product->get_id(), array( $cat_id ), 'product_cat' );
            }
        }
    }

    $running = false;
}
add_action( 'acf/save_post', 'ark_umrah_sync_woo_product', 20 );

/**
 * When Umrah CPT is permanently deleted, delete the linked Woo product.
 */
function ark_umrah_delete_linked_woo_product( $post_id ) {
    if ( get_post_type( $post_id ) !== 'umrah' ) {
        return;
    }
    if ( ! class_exists( 'WooCommerce' ) ) {
        return;
    }
    $linked_product_id = (int) get_post_meta( $post_id, 'linked_product_id', true );
    if ( $linked_product_id && get_post( $linked_product_id ) ) {
        wp_delete_post( $linked_product_id, true );
    }
}
add_action( 'before_delete_post', 'ark_umrah_delete_linked_woo_product', 10, 1 );

/* ---------------------------------------------------------------------------- */

/**
 * Ensure required pages exist on first load (e.g. after upload); runs once per install.
 */
function ark_maybe_create_required_pages() {
    if ( get_option( 'ark_required_pages_created', false ) ) {
        return;
    }
    ark_create_required_pages();
    update_option( 'ark_required_pages_created', true );
}
add_action( 'init', 'ark_maybe_create_required_pages', 20 );

/**
 * Register Umrah Package custom post type
 */
function ark_register_umrah_cpt() {
    $labels = array(
        'name'               => __( 'Umrah Packages', 'ark-travelers' ),
        'singular_name'      => __( 'Umrah Package', 'ark-travelers' ),
        'add_new'            => __( 'Add New Package', 'ark-travelers' ),
        'add_new_item'       => __( 'Add New Umrah Package', 'ark-travelers' ),
        'edit_item'           => __( 'Edit Umrah Package', 'ark-travelers' ),
        'new_item'            => __( 'New Umrah Package', 'ark-travelers' ),
        'view_item'           => __( 'View Umrah Package', 'ark-travelers' ),
        'search_items'        => __( 'Search Packages', 'ark-travelers' ),
        'not_found'           => __( 'No packages found', 'ark-travelers' ),
        'not_found_in_trash'  => __( 'No packages found in Trash', 'ark-travelers' ),
    );

    $args = array(
        'labels'              => $labels,
        'public'              => true,
        'has_archive'         => true,
        'menu_icon'           => 'dashicons-airplane',
        'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
        'rewrite'             => array( 'slug' => 'package' ),
        'show_in_rest'        => true,
    );

    register_post_type( 'umrah', $args );
}
add_action( 'init', 'ark_register_umrah_cpt' );

/**
 * Create sample Umrah packages so "View Details" links work (single package page).
 * Runs once; creates 8 packages matching the listing. Flush rewrite after.
 */
function ark_create_sample_umrah_packages() {
    if ( get_option( 'ark_sample_umrah_packages_created', false ) ) {
        return;
    }

    $packages = array(
        array( 'title' => 'Ramadan Umrah 2026', 'days' => 15, 'stars' => 5, 'price' => '34,900', 'type' => 'Premium', 'badge' => 'FEATURED', 'departure' => '2026-02', 'duration_key' => '15-21' ),
        array( 'title' => 'Spring Umrah Package', 'days' => 10, 'stars' => 4, 'price' => '28,900', 'type' => 'Standard', 'badge' => '', 'departure' => '2026-04', 'duration_key' => '8-14' ),
        array( 'title' => 'Luxury Umrah Experience', 'days' => 21, 'stars' => 5, 'price' => '54,900', 'type' => 'Luxury', 'badge' => 'Premium', 'departure' => '2026-03', 'duration_key' => '22+' ),
        array( 'title' => 'Family Umrah Package', 'days' => 12, 'stars' => 4, 'price' => '32,900', 'type' => 'Standard', 'badge' => '', 'departure' => '2026-05', 'duration_key' => '8-14' ),
        array( 'title' => 'Essential Umrah Package', 'days' => 7, 'stars' => 3, 'price' => '21,900', 'type' => 'Essential', 'badge' => '', 'departure' => '2026-01', 'duration_key' => '5-7' ),
        array( 'title' => 'Hajj Preparation Umrah', 'days' => 14, 'stars' => 5, 'price' => '39,900', 'type' => 'Premium', 'badge' => '', 'departure' => '2026-06', 'duration_key' => '8-14' ),
        array( 'title' => 'Winter Umrah Package', 'days' => 10, 'stars' => 4, 'price' => '29,900', 'type' => 'Standard', 'badge' => '', 'departure' => '2025-12', 'duration_key' => '8-14' ),
        array( 'title' => 'Express Umrah Package', 'days' => 5, 'stars' => 5, 'price' => '26,900', 'type' => 'Essential', 'badge' => '', 'departure' => '2026-02', 'duration_key' => '5-7' ),
    );

    $default_departures = array( '2026-02-15', '2026-03-01', '2026-03-15' );

    $default_overview = '<p>' . __( 'This package includes return flights, accommodation near the Haram, daily meals, ground transportation, visa support, and the guidance of an experienced spiritual guide.', 'ark-travelers' ) . '</p><p>' . __( 'For a full breakdown of inclusions and the day-by-day itinerary, see the sections below.', 'ark-travelers' ) . '</p>';

    foreach ( $packages as $p ) {
        $slug = sanitize_title( $p['title'] );
        if ( get_page_by_path( $slug, OBJECT, 'umrah' ) ) {
            continue;
        }
        $id = wp_insert_post( array(
            'post_title'   => $p['title'],
            'post_name'    => $slug,
            'post_content' => $default_overview,
            'post_status'  => 'publish',
            'post_type'    => 'umrah',
            'post_author'  => 1,
        ) );
        if ( $id && ! is_wp_error( $id ) ) {
            update_post_meta( $id, 'ark_price', $p['price'] );
            update_post_meta( $id, 'ark_duration', (string) $p['days'] );
            update_post_meta( $id, 'ark_stars', (string) $p['stars'] );
            update_post_meta( $id, 'ark_badge', $p['badge'] );
            update_post_meta( $id, 'ark_departures', $default_departures );
            update_post_meta( $id, 'ark_type', $p['type'] );
            update_post_meta( $id, 'ark_departure', $p['departure'] );
            update_post_meta( $id, 'ark_duration_key', $p['duration_key'] );
        }
    }

    update_option( 'ark_sample_umrah_packages_created', true );
    flush_rewrite_rules();
}
add_action( 'init', 'ark_create_sample_umrah_packages', 25 );

/**
 * One-time backfill: add default overview content to existing Umrah packages that have empty content.
 */
function ark_backfill_umrah_overview_content() {
    if ( get_option( 'ark_umrah_overview_backfilled', false ) ) {
        return;
    }
    $overview = '<p>' . __( 'This package includes return flights, accommodation near the Haram, daily meals, ground transportation, visa support, and the guidance of an experienced spiritual guide.', 'ark-travelers' ) . '</p><p>' . __( 'For a full breakdown of inclusions and the day-by-day itinerary, see the sections below.', 'ark-travelers' ) . '</p>';
    $posts = get_posts( array(
        'post_type'      => 'umrah',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
    ) );
    foreach ( $posts as $post ) {
        if ( ! trim( $post->post_content ) ) {
            wp_update_post( array(
                'ID'           => $post->ID,
                'post_content' => $overview,
            ) );
        }
    }
    update_option( 'ark_umrah_overview_backfilled', true );
}
add_action( 'init', 'ark_backfill_umrah_overview_content', 26 );

/**
 * Fallback navigation menu (uses ark_url and ark_t for multilingual)
 */
function ark_fallback_menu() {
    ?>
    <ul class="nav-menu" id="navMenu">
        <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="nav-link"><?php echo esc_html__( 'Home', 'ark-travelers' ); ?></a></li>
        <li><a href="<?php echo esc_url( home_url( '/fly/' ) ); ?>" class="nav-link"><?php echo esc_html__( 'FLY', 'ark-travelers' ); ?></a></li>
        <li><a href="<?php echo esc_url( home_url( '/umrah/' ) ); ?>" class="nav-link"><?php echo esc_html__( 'Umrah Packages', 'ark-travelers' ); ?></a></li>
        <li><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>" class="nav-link"><?php echo esc_html__( 'About Us', 'ark-travelers' ); ?></a></li>
        <li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="nav-link"><?php echo esc_html__( 'Contact', 'ark-travelers' ); ?></a></li>
    </ul>
    <?php
}

/**
 * AJAX handler for contact form (uses ark_t when ark_lang is sent)
 */
function ark_handle_contact_form() {
    check_ajax_referer( 'ark_nonce', 'nonce' );

    $name    = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
    $email   = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
    $phone   = isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '';
    $subject = isset( $_POST['subject'] ) ? sanitize_text_field( wp_unslash( $_POST['subject'] ) ) : '';
    $message = isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';
    $privacy = isset( $_POST['privacy'] ) ? (bool) $_POST['privacy'] : false;

    if ( empty( $name ) || empty( $email ) || empty( $message ) || ! $privacy ) {
        wp_send_json_error(
            array(
                'message' => __( 'Please fill all required fields and accept the privacy policy.', 'ark-travelers' ),
            )
        );
    }

    if ( ! is_email( $email ) ) {
        wp_send_json_error(
            array(
                'message' => __( 'Please enter a valid email address.', 'ark-travelers' ),
            )
        );
    }

    $to          = get_option( 'admin_email' );
    $email_subject = 'Contact Form: ' . $subject;
    $email_body   = sprintf(
        "Name: %s\nEmail: %s\nPhone: %s\nSubject: %s\n\nMessage:\n%s",
        $name,
        $email,
        $phone,
        $subject,
        $message
    );

    $sent = wp_mail( $to, $email_subject, $email_body );

    if ( $sent ) {
        wp_send_json_success(
            array(
                'message' => __( 'Thank you! We\'ll get back to you soon.', 'ark-travelers' ),
            )
        );
    } else {
        wp_send_json_error(
            array(
                'message' => __( 'Something went wrong. Please try again.', 'ark-travelers' ),
            )
        );
    }
}
add_action( 'wp_ajax_ark_contact_form', 'ark_handle_contact_form' );
add_action( 'wp_ajax_nopriv_ark_contact_form', 'ark_handle_contact_form' );

/**
 * Add meta boxes for Umrah package content sections (admin-editable)
 */
function ark_add_umrah_meta_boxes() {
    add_meta_box(
        'ark_package_overview',
        __( 'Package Overview', 'ark-travelers' ),
        'ark_render_overview_meta_box',
        'umrah',
        'normal',
        'high'
    );
    add_meta_box(
        'ark_package_included',
        __( "What's Included", 'ark-travelers' ),
        'ark_render_included_meta_box',
        'umrah',
        'normal',
        'high'
    );
    add_meta_box(
        'ark_package_itinerary',
        __( 'Detailed Itinerary', 'ark-travelers' ),
        'ark_render_itinerary_meta_box',
        'umrah',
        'normal',
        'high'
    );
    add_meta_box(
        'ark_package_important',
        __( 'Important Information', 'ark-travelers' ),
        'ark_render_important_meta_box',
        'umrah',
        'normal',
        'default'
    );
    add_meta_box(
        'ark_package_faq',
        __( 'FAQ', 'ark-travelers' ),
        'ark_render_faq_meta_box',
        'umrah',
        'normal',
        'default'
    );
    add_meta_box(
        'ark_package_terms',
        __( 'Terms & Conditions', 'ark-travelers' ),
        'ark_render_terms_meta_box',
        'umrah',
        'normal',
        'default'
    );
    add_meta_box(
        'ark_package_product',
        __( 'WooCommerce Product', 'ark-travelers' ),
        'ark_render_product_meta_box',
        'umrah',
        'side',
        'default'
    );
}
add_action( 'add_meta_boxes', 'ark_add_umrah_meta_boxes' );

/**
 * Render "Package Overview" meta box
 */
function ark_render_overview_meta_box( $post ) {
    wp_nonce_field( 'ark_save_meta', 'ark_meta_nonce' );
    ?>
    <p><?php esc_html_e( 'The Package Overview section appears at the top of the package page. You can edit this content using the main WordPress editor above, or use the custom overview field below for more control.', 'ark-travelers' ); ?></p>
    <p><strong><?php esc_html_e( 'Note:', 'ark-travelers' ); ?></strong> <?php esc_html_e( 'The main editor content is used for the Package Overview section. If you want to override it, use the custom overview field below.', 'ark-travelers' ); ?></p>
    <?php
    $overview = get_post_meta( $post->ID, 'ark_package_overview', true );
    if ( empty( $overview ) && ! empty( $post->post_content ) ) {
        $overview = $post->post_content;
    }
    wp_editor( $overview, 'ark_package_overview', array(
        'textarea_name' => 'ark_package_overview',
        'media_buttons' => true,
        'textarea_rows' => 12,
        'teeny' => false,
    ) );
}

/**
 * Render "What's Included" meta box
 */
function ark_render_included_meta_box( $post ) {
    wp_nonce_field( 'ark_save_meta', 'ark_meta_nonce' );
    $included = get_post_meta( $post->ID, 'ark_included', true );
    if ( ! is_array( $included ) ) {
        $included = array(
            __( 'Return flights from Stockholm', 'ark-travelers' ),
            __( 'Hotel accommodations (star rating, distance from Haram)', 'ark-travelers' ),
            __( 'Daily breakfast + dinner', 'ark-travelers' ),
            __( 'Ground transportation', 'ark-travelers' ),
            __( 'Visa processing assistance', 'ark-travelers' ),
            __( 'Expert spiritual guide', 'ark-travelers' ),
            __( 'Comprehensive travel insurance', 'ark-travelers' ),
        );
    }
    ?>
    <p><?php esc_html_e( 'Enter one item per line. Each line will appear as a bullet point.', 'ark-travelers' ); ?></p>
    <textarea name="ark_included" id="ark_included" rows="10" style="width:100%;"><?php echo esc_textarea( implode( "\n", $included ) ); ?></textarea>
    <?php
}

/**
 * Render "Detailed Itinerary" meta box
 */
function ark_render_itinerary_meta_box( $post ) {
    wp_nonce_field( 'ark_save_meta', 'ark_meta_nonce' );
    $itinerary = get_post_meta( $post->ID, 'ark_itinerary', true );
    if ( ! is_array( $itinerary ) ) {
        $itinerary = array(
            array( 'day' => __( 'Day 1: Departure & Arrival', 'ark-travelers' ), 'content' => __( 'Depart from Stockholm. Arrival in Jeddah, transfer to Makkah. Check-in and rest.', 'ark-travelers' ) ),
            array( 'day' => __( 'Day 2–7: Makkah', 'ark-travelers' ), 'content' => __( 'Umrah rites, prayers at the Haram, spiritual guidance and support.', 'ark-travelers' ) ),
            array( 'day' => __( 'Day 8–13: Medina', 'ark-travelers' ), 'content' => __( 'Transfer to Medina. Visit Masjid an-Nabawi and key sites.', 'ark-travelers' ) ),
            array( 'day' => __( 'Day 14–15: Return', 'ark-travelers' ), 'content' => __( 'Transfer to Jeddah airport. Return flight to Stockholm.', 'ark-travelers' ) ),
        );
    }
    ?>
    <div id="ark-itinerary-items">
        <?php foreach ( $itinerary as $index => $item ) : ?>
            <div class="ark-itinerary-item" style="margin-bottom:15px;padding:15px;border:1px solid #ddd;border-radius:4px;">
                <label style="display:block;font-weight:600;margin-bottom:5px;"><?php esc_html_e( 'Day/Title:', 'ark-travelers' ); ?></label>
                <input type="text" name="ark_itinerary[<?php echo esc_attr( $index ); ?>][day]" value="<?php echo esc_attr( $item['day'] ); ?>" style="width:100%;margin-bottom:10px;" />
                <label style="display:block;font-weight:600;margin-bottom:5px;"><?php esc_html_e( 'Description:', 'ark-travelers' ); ?></label>
                <textarea name="ark_itinerary[<?php echo esc_attr( $index ); ?>][content]" rows="3" style="width:100%;"><?php echo esc_textarea( $item['content'] ); ?></textarea>
                <button type="button" class="button" onclick="this.parentElement.remove()" style="margin-top:5px;"><?php esc_html_e( 'Remove', 'ark-travelers' ); ?></button>
            </div>
        <?php endforeach; ?>
    </div>
    <button type="button" id="ark-add-itinerary-item" class="button"><?php esc_html_e( 'Add Day', 'ark-travelers' ); ?></button>
    <script>
    document.getElementById('ark-add-itinerary-item')?.addEventListener('click', function() {
        var container = document.getElementById('ark-itinerary-items');
        var index = container.children.length;
        var div = document.createElement('div');
        div.className = 'ark-itinerary-item';
        div.style.cssText = 'margin-bottom:15px;padding:15px;border:1px solid #ddd;border-radius:4px;';
        div.innerHTML = '<label style="display:block;font-weight:600;margin-bottom:5px;"><?php esc_html_e( 'Day/Title:', 'ark-travelers' ); ?></label>' +
            '<input type="text" name="ark_itinerary[' + index + '][day]" value="" style="width:100%;margin-bottom:10px;" />' +
            '<label style="display:block;font-weight:600;margin-bottom:5px;"><?php esc_html_e( 'Description:', 'ark-travelers' ); ?></label>' +
            '<textarea name="ark_itinerary[' + index + '][content]" rows="3" style="width:100%;"></textarea>' +
            '<button type="button" class="button" onclick="this.parentElement.remove()"><?php esc_html_e( 'Remove', 'ark-travelers' ); ?></button>';
        container.appendChild(div);
    });
    </script>
    <?php
}

/**
 * Render "Important Information" meta box
 */
function ark_render_important_meta_box( $post ) {
    wp_nonce_field( 'ark_save_meta', 'ark_meta_nonce' );
    $important = get_post_meta( $post->ID, 'ark_important_info', true );
    if ( empty( $important ) ) {
        $important = __( 'Visa requirements, health recommendations, and packing list will be sent upon booking. Please ensure your passport is valid for at least 6 months.', 'ark-travelers' );
    }
    ?>
    <p><?php esc_html_e( 'Important information displayed to customers before booking.', 'ark-travelers' ); ?></p>
    <?php
    wp_editor( $important, 'ark_important_info', array(
        'textarea_name' => 'ark_important_info',
        'media_buttons' => false,
        'textarea_rows' => 8,
    ) );
}

/**
 * Render "FAQ" meta box
 */
function ark_render_faq_meta_box( $post ) {
    wp_nonce_field( 'ark_save_meta', 'ark_meta_nonce' );
    $faq = get_post_meta( $post->ID, 'ark_faq', true );
    if ( ! is_array( $faq ) ) {
        $faq = array(
            array( 'question' => __( 'When is the best time to book?', 'ark-travelers' ), 'answer' => __( 'We recommend booking at least 2–3 months in advance for the best availability and prices.', 'ark-travelers' ) ),
            array( 'question' => __( 'Is travel insurance included?', 'ark-travelers' ), 'answer' => __( 'Yes, comprehensive travel insurance is included in all our packages.', 'ark-travelers' ) ),
            array( 'question' => __( 'Can I extend my stay?', 'ark-travelers' ), 'answer' => __( 'Extensions may be possible subject to visa and flight availability. Contact us for options.', 'ark-travelers' ) ),
        );
    }
    ?>
    <div id="ark-faq-items">
        <?php foreach ( $faq as $index => $item ) : ?>
            <div class="ark-faq-item" style="margin-bottom:15px;padding:15px;border:1px solid #ddd;border-radius:4px;">
                <label style="display:block;font-weight:600;margin-bottom:5px;"><?php esc_html_e( 'Question:', 'ark-travelers' ); ?></label>
                <input type="text" name="ark_faq[<?php echo esc_attr( $index ); ?>][question]" value="<?php echo esc_attr( $item['question'] ); ?>" style="width:100%;margin-bottom:10px;" />
                <label style="display:block;font-weight:600;margin-bottom:5px;"><?php esc_html_e( 'Answer:', 'ark-travelers' ); ?></label>
                <textarea name="ark_faq[<?php echo esc_attr( $index ); ?>][answer]" rows="3" style="width:100%;"><?php echo esc_textarea( $item['answer'] ); ?></textarea>
                <button type="button" class="button" onclick="this.parentElement.remove()" style="margin-top:5px;"><?php esc_html_e( 'Remove', 'ark-travelers' ); ?></button>
            </div>
        <?php endforeach; ?>
    </div>
    <button type="button" id="ark-add-faq-item" class="button"><?php esc_html_e( 'Add FAQ', 'ark-travelers' ); ?></button>
    <script>
    document.getElementById('ark-add-faq-item')?.addEventListener('click', function() {
        var container = document.getElementById('ark-faq-items');
        var index = container.children.length;
        var div = document.createElement('div');
        div.className = 'ark-faq-item';
        div.style.cssText = 'margin-bottom:15px;padding:15px;border:1px solid #ddd;border-radius:4px;';
        div.innerHTML = '<label style="display:block;font-weight:600;margin-bottom:5px;"><?php esc_html_e( 'Question:', 'ark-travelers' ); ?></label>' +
            '<input type="text" name="ark_faq[' + index + '][question]" value="" style="width:100%;margin-bottom:10px;" />' +
            '<label style="display:block;font-weight:600;margin-bottom:5px;"><?php esc_html_e( 'Answer:', 'ark-travelers' ); ?></label>' +
            '<textarea name="ark_faq[' + index + '][answer]" rows="3" style="width:100%;"></textarea>' +
            '<button type="button" class="button" onclick="this.parentElement.remove()" style="margin-top:5px;"><?php esc_html_e( 'Remove', 'ark-travelers' ); ?></button>';
        container.appendChild(div);
    });
    </script>
    <?php
}

/**
 * Render "Terms & Conditions" meta box
 */
function ark_render_terms_meta_box( $post ) {
    wp_nonce_field( 'ark_save_meta', 'ark_meta_nonce' );
    $terms = get_post_meta( $post->ID, 'ark_terms_conditions', true );
    if ( empty( $terms ) ) {
        $terms = __( 'By booking you agree to our terms. Cancellation policy and payment terms will be provided at the time of booking.', 'ark-travelers' );
    }
    ?>
    <p><?php esc_html_e( 'Terms and conditions specific to this package.', 'ark-travelers' ); ?></p>
    <?php
    wp_editor( $terms, 'ark_terms_conditions', array(
        'textarea_name' => 'ark_terms_conditions',
        'media_buttons' => false,
        'textarea_rows' => 10,
    ) );
}

/**
 * Render "WooCommerce Product" meta box – link this package to a WC product for Request Booking.
 */
function ark_render_product_meta_box( $post ) {
    wp_nonce_field( 'ark_save_meta', 'ark_meta_nonce' );
    $linked_id   = (int) get_post_meta( $post->ID, 'linked_product_id', true );
    $product_id  = (int) get_post_meta( $post->ID, 'ark_product_id', true );
    $display_id  = $product_id ? $product_id : $linked_id;
    ?>
    <p><?php esc_html_e( 'Link this Umrah package to a WooCommerce product. "Buy Now" adds that product to cart and sends the customer to checkout.', 'ark-travelers' ); ?></p>
    <p><?php esc_html_e( 'If you use ACF auto-sync, Linked Woo Product ID is set automatically; manual Product ID below is a fallback.', 'ark-travelers' ); ?></p>
    <?php if ( ! class_exists( 'WooCommerce' ) ) : ?>
        <p><strong><?php esc_html_e( 'WooCommerce is not active.', 'ark-travelers' ); ?></strong> <?php esc_html_e( 'Install and activate WooCommerce to enable checkout.', 'ark-travelers' ); ?></p>
    <?php endif; ?>
    <?php if ( $linked_id ) : ?>
        <p><strong><?php esc_html_e( 'Linked product (auto):', 'ark-travelers' ); ?></strong> <?php echo (int) $linked_id; ?></p>
    <?php endif; ?>
    <p>
        <label for="ark_product_id"><?php esc_html_e( 'Product ID (manual fallback)', 'ark-travelers' ); ?></label>
        <input type="number" id="ark_product_id" name="ark_product_id" value="<?php echo $product_id ? (int) $product_id : ''; ?>" min="1" step="1" style="width:100%;" placeholder="<?php esc_attr_e( 'e.g. 123', 'ark-travelers' ); ?>">
    </p>
    <?php
    if ( $display_id && class_exists( 'WooCommerce' ) ) {
        $product = wc_get_product( $display_id );
        if ( $product ) {
            echo '<p><em>' . esc_html( $product->get_name() ) . ' — ' . wp_kses_post( $product->get_price_html() ) . '</em></p>';
        } else {
            echo '<p><em>' . esc_html__( 'Product not found.', 'ark-travelers' ) . '</em></p>';
        }
    }
}

/**
 * Get Buy Now URL for an Umrah package: add-to-cart then redirect to checkout if product linked, else contact.
 * Prefers linked_product_id (ACF / auto-sync), then ark_product_id (manual).
 *
 * @param int $post_id Umrah post ID
 * @return string URL
 */
function ark_get_umrah_request_booking_url( $post_id ) {
    $title = get_the_title( $post_id );
    if ( class_exists( 'WooCommerce' ) ) {
        $product_id = (int) get_post_meta( $post_id, 'linked_product_id', true );
        if ( ! $product_id ) {
            $product_id = (int) get_post_meta( $post_id, 'ark_product_id', true );
        }
        if ( $product_id && wc_get_product( $product_id ) ) {
            return wc_get_cart_url() . '?add-to-cart=' . $product_id . '&ark_checkout=1';
        }
    }
    return home_url( '/contact/' ) . '?package=' . rawurlencode( $title );
}

/**
 * Redirect to checkout after add-to-cart when ark_checkout=1 is present (Buy Now flow).
 */
function ark_add_to_cart_redirect_to_checkout( $url ) {
    if ( isset( $_GET['ark_checkout'] ) && isset( $_GET['add-to-cart'] ) && function_exists( 'wc_get_checkout_url' ) ) {
        return wc_get_checkout_url();
    }
    return $url;
}
add_filter( 'woocommerce_add_to_cart_redirect', 'ark_add_to_cart_redirect_to_checkout', 10, 1 );

/**
 * Save meta box data
 */
function ark_save_umrah_meta_boxes( $post_id ) {
    if ( ! isset( $_POST['ark_meta_nonce'] ) || ! wp_verify_nonce( $_POST['ark_meta_nonce'], 'ark_save_meta' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }
    if ( get_post_type( $post_id ) !== 'umrah' ) {
        return;
    }

    // Save included items
    if ( isset( $_POST['ark_included'] ) ) {
        $included = array_filter( array_map( 'trim', explode( "\n", sanitize_textarea_field( wp_unslash( $_POST['ark_included'] ) ) ) ) );
        update_post_meta( $post_id, 'ark_included', $included );
    }

    // Save itinerary
    if ( isset( $_POST['ark_itinerary'] ) && is_array( $_POST['ark_itinerary'] ) ) {
        $itinerary = array();
        foreach ( $_POST['ark_itinerary'] as $item ) {
            if ( ! empty( $item['day'] ) && ! empty( $item['content'] ) ) {
                $itinerary[] = array(
                    'day' => sanitize_text_field( $item['day'] ),
                    'content' => sanitize_textarea_field( $item['content'] ),
                );
            }
        }
        update_post_meta( $post_id, 'ark_itinerary', $itinerary );
    }

    // Save important info
    if ( isset( $_POST['ark_important_info'] ) ) {
        update_post_meta( $post_id, 'ark_important_info', wp_kses_post( wp_unslash( $_POST['ark_important_info'] ) ) );
    }

    // Save FAQ
    if ( isset( $_POST['ark_faq'] ) && is_array( $_POST['ark_faq'] ) ) {
        $faq = array();
        foreach ( $_POST['ark_faq'] as $item ) {
            if ( ! empty( $item['question'] ) && ! empty( $item['answer'] ) ) {
                $faq[] = array(
                    'question' => sanitize_text_field( $item['question'] ),
                    'answer' => sanitize_textarea_field( $item['answer'] ),
                );
            }
        }
        update_post_meta( $post_id, 'ark_faq', $faq );
    }

    // Save terms
    if ( isset( $_POST['ark_terms_conditions'] ) ) {
        update_post_meta( $post_id, 'ark_terms_conditions', wp_kses_post( wp_unslash( $_POST['ark_terms_conditions'] ) ) );
    }

    // Save WooCommerce product ID
    if ( isset( $_POST['ark_product_id'] ) ) {
        $pid = absint( $_POST['ark_product_id'] );
        update_post_meta( $post_id, 'ark_product_id', $pid ? $pid : '' );
    }

    // Save package overview (if custom overview is provided, it overrides post content)
    if ( isset( $_POST['ark_package_overview'] ) ) {
        $overview = wp_kses_post( wp_unslash( $_POST['ark_package_overview'] ) );
        update_post_meta( $post_id, 'ark_package_overview', $overview );
        // Also update post content if overview is different from current content
        if ( $overview !== $post->post_content ) {
            // Only update if admin explicitly wants to sync
            // For now, we'll use the meta field and let the template decide
        }
    }
}
add_action( 'save_post', 'ark_save_umrah_meta_boxes' );

/**
 * On the cart page: replace the default "Browse store" link in the empty-cart block
 * with "Browse packages" pointing to the Umrah packages page.
 */
function ark_empty_cart_browse_packages_script() {
    if ( ! function_exists( 'is_cart' ) || ! is_cart() ) {
        return;
    }
    $umrah_url = esc_url( home_url( '/umrah/' ) );
    ?>
    <script>
    (function() {
        function arkFixEmptyCartLink() {
            var block = document.querySelector('.wp-block-woocommerce-empty-cart-block');
            if ( ! block ) { return; }
            var links = block.querySelectorAll('p a, a');
            links.forEach(function(a) {
                if ( a.textContent.trim().toLowerCase() === 'browse store' || a.href.indexOf('/shop') !== -1 ) {
                    a.textContent = '<?php echo esc_js( __( 'Browse packages', 'ark-travelers' ) ); ?>';
                    a.href = '<?php echo $umrah_url; ?>';
                }
            });
        }
        if ( document.readyState === 'loading' ) {
            document.addEventListener('DOMContentLoaded', arkFixEmptyCartLink);
        } else {
            arkFixEmptyCartLink();
        }
        /* Also run after WooCommerce blocks re-render (e.g. React hydration) */
        setTimeout(arkFixEmptyCartLink, 800);
    })();
    </script>
    <?php
}
add_action( 'wp_footer', 'ark_empty_cart_browse_packages_script', 20 );

/**
 * Add language switcher to mobile menu
 */
function ark_add_language_switcher_to_menu( $items, $args ) {
    // Only add to primary menu
    if ( isset( $args->theme_location ) && $args->theme_location === 'primary' ) {
        $lang_switcher = '<li class="menu-item ark-lang-switcher-mobile">' . do_shortcode( '[language-switcher]' ) . '</li>';
        $items .= $lang_switcher;
    }
    return $items;
}
add_filter( 'wp_nav_menu_items', 'ark_add_language_switcher_to_menu', 10, 2 );