<?php
/**
 * Adds "Homepage Hero" to Appearance > Customize.
 * This is what lets the client swap the cover photo himself,
 * with zero code and zero developer involvement.
 */
function khoji_customize_register( $wp_customize ) {

    $wp_customize->add_section( 'khoji_hero_section', array(
        'title'    => 'Homepage Hero',
        'priority' => 30,
    ) );

    $wp_customize->add_setting( 'khoji_hero_image', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );

    $wp_customize->add_control( new WP_Customize_Image_Control(
        $wp_customize,
        'khoji_hero_image_control',
        array(
            'label'    => 'Hero image',
            'description' => 'Recommended: 2000 x 1200px, JPG or WebP, under 250 KB. Keep the left third plain - the headline sits there.',
            'section'  => 'khoji_hero_section',
            'settings' => 'khoji_hero_image',
        )
    ) );

    $wp_customize->add_setting( 'khoji_parallax_image', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );

    $wp_customize->add_control( new WP_Customize_Image_Control(
        $wp_customize,
        'khoji_parallax_image_control',
        array(
            'label'       => 'Mid-page feature image',
            'description' => 'The dark section between Best Sellers and the delivery promises. A strong single shoe photo works best - it fills the whole width. 2000 x 1000px or wider.',
            'section'     => 'khoji_hero_section',
            'settings'    => 'khoji_parallax_image',
        )
    ) );

    $wp_customize->add_setting( 'khoji_parallax_text', array(
        'default'           => 'Built for the street. Made to be worn.',
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    $wp_customize->add_control( 'khoji_parallax_text_control', array(
        'label'    => 'Mid-page feature caption',
        'section'  => 'khoji_hero_section',
        'settings' => 'khoji_parallax_text',
        'type'     => 'text',
    ) );

    $wp_customize->add_setting( 'khoji_marquee_text', array(
        'default'           => 'Free delivery nationwide | Cash on delivery | 7-day exchange | Find your fit',
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    $wp_customize->add_control( 'khoji_marquee_control', array(
        'label'       => 'Marquee text',
        'description' => 'Separate items with a | character.',
        'section'     => 'khoji_hero_section',
        'settings'    => 'khoji_marquee_text',
        'type'        => 'text',
    ) );
}
add_action( 'customize_register', 'khoji_customize_register' );

/**
 * A sidebar for the shop-page filter widgets (brand + size),
 * so those checkboxes come from WooCommerce's own widgets and
 * work immediately, no custom filter JS to maintain.
 */
function khoji_widgets_init() {
    register_sidebar( array(
        'name'          => 'Khoji Shop Filters',
        'id'            => 'khoji-shop-filters',
        'description'   => 'Shown in the sidebar on the shop page. Add "Filter Products by Category" and "Filter Products by Attribute" widgets here.',
        'before_widget' => '<div class="fgroup">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4>',
        'after_title'   => '</h4>',
    ) );
}
add_action( 'widgets_init', 'khoji_widgets_init' );
