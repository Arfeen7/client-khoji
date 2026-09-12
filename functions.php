<?php
/**
 * Khoji theme setup
 */

/* ---- basic theme support ---- */
function khoji_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );   // needed for product photos
    add_theme_support( 'woocommerce' );        // tells WordPress this theme works with WooCommerce
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'khoji_setup' );

/* ---- load our CSS and JS on every page ---- */
function khoji_assets() {
    wp_enqueue_style( 'khoji-style', get_stylesheet_uri(), array(), '1.0' );

    wp_enqueue_style(
        'khoji-fonts',
        'https://fonts.googleapis.com/css2?family=Archivo:wdth,wght@62..125,100..900&family=DM+Mono:wght@400;500&display=swap',
        array(), null
    );

    // app.js still holds the dot-field engine, header, splash, size-chip UI.
    // The PRODUCTS / BRANDS arrays at the top of it are no longer used on
    // WordPress pages - real data comes from WooCommerce instead.
    wp_enqueue_script( 'khoji-app', get_template_directory_uri() . '/app.js', array(), '1.0', true );
}
add_action( 'wp_enqueue_scripts', 'khoji_assets' );

/* ---- remove WooCommerce's default wrapping markup ----
   We build our own wrapper divs inside the templates instead,
   so the .shop / .pdp / .grid classes from our CSS still apply. */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

/* ---- turn off WooCommerce's default styling so ours is the only CSS ---- */
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

/* ---- helper: format a WooCommerce price the "Rs 6,499" way ---- */
function khoji_price( $amount ) {
    return 'Rs ' . number_format( (float) $amount, 0 );
}

/* ---- load the Customizer controls (hero image, marquee text) ---- */
require get_template_directory() . '/inc/customizer.php';

/**
 * Make the Sale link and the header search box actually filter
 * the shop page, instead of always showing every product.
 *
 * - Sale link goes to:   /shop/?on_sale=1
 * - Search box goes to:  /shop/?s=your+text
 * - A brand tile already works on its own (it links to a real
 *   WooCommerce category page) - this hook just makes sure that
 *   isn't accidentally widened if a filter runs at the same time.
 */
function khoji_filter_shop_query( $query ) {
    if ( is_admin() || ! $query->is_main_query() ) {
        return;
    }
    if ( ! ( is_shop() || is_product_category() || is_product_taxonomy() ) ) {
        return;
    }

    // "Sale" nav link: /shop/?on_sale=1
    if ( isset( $_GET['on_sale'] ) ) {
        $sale_ids = wc_get_product_ids_on_sale();
        if ( empty( $sale_ids ) ) {
            $sale_ids = array( 0 ); // no sale items right now - show none, not everything
        }
        $query->set( 'post__in', $sale_ids );
    }

    // Header search box: /shop/?s=your+text
    if ( isset( $_GET['s'] ) && '' !== trim( $_GET['s'] ) ) {
        $query->set( 's', sanitize_text_field( wp_unslash( $_GET['s'] ) ) );
    }
}
add_action( 'pre_get_posts', 'khoji_filter_shop_query' );

/* ---- helper: get the size attribute options for a product, marking sold-out ones ----
   Assumes a global product attribute called "Size" (pa_size) with values like
   "UK 6 / EU 39". Falls back gracefully if it isn't set up yet. */
function khoji_get_sizes( $product ) {
    $out = array();
    if ( ! $product || ! $product->is_type( 'variable' ) ) {
        return $out;
    }
    foreach ( $product->get_available_variations() as $variation ) {
        $variation_obj = wc_get_product( $variation['variation_id'] );
        $size_attr     = isset( $variation['attributes']['attribute_pa_size'] )
            ? $variation['attributes']['attribute_pa_size'] : '';
        $out[] = array(
            'label'   => $size_attr,
            'in_stock'=> $variation_obj ? $variation_obj->is_in_stock() : false,
        );
    }
    return $out;
}
