<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// Elementor init.
add_action( 'elementor/elements/categories_registered', 'wr_ew_register_category' );

// Register custom category.
function wr_ew_register_category( $elements_manager ) {
    $elements_manager->add_category(
        'wr-widgets',
        [
            'title' => __( 'WR Widgets', 'wr-ew' ),
            'icon'  => 'fa fa-plug',
        ]
    );
}

// Enqueue Swiper & custom slider assets
add_action('wp_enqueue_scripts', function() {
    // Swiper CSS
    wp_enqueue_style(
        'wr-swiper',
        'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css'
    );

    // Custom CSS
    wp_enqueue_style(
        'wr-hero-slider-css',
        WR_EW_PLUGIN_URL . 'assets/css/hero-slider.css',
        [],
        '1.0.0'
    );

    // Swiper JS
    wp_enqueue_script(
        'wr-swiper',
        'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js',
        [],
        null,
        true
    );

    // Custom JS
    wp_enqueue_script(
        'wr-hero-slider-js',
        WR_EW_PLUGIN_URL . 'assets/js/hero-slider.js',
        ['wr-swiper'],
        '1.0.0',
        true
    );

    wp_enqueue_style(
        'wr-product-grid-css',
        WR_EW_PLUGIN_URL . 'assets/css/product-grid.css',
        [],
        '1.0.0'
    );

    wp_enqueue_script(
        'wr-product-grid-js',
        WR_EW_PLUGIN_URL . 'assets/js/product-grid.js',
        ['jquery'],
        '1.0.0',
        true
    );

    wp_localize_script(
        'wr-product-grid-js',
        'wrpg',
        [ 'ajax_url' => admin_url('admin-ajax.php') ]
    );
});

add_action( 'elementor/widgets/register', function( $widgets_manager ) {

    $widget_dirs = [
        'hero-slider',
        'category-grid',
        'product-grid',
        'product-carousel',
        'category-slider',
        'banner',
        'icon-box',
        'testimonials',
        'campaign-bar',
        'blog-grid'
    ];

    foreach ( $widget_dirs as $widget ) {

        $file = WR_EW_PLUGIN_DIR . 'widgets/' . $widget . '/widget.php';

        if ( file_exists( $file ) ) {
            require_once $file;

            $class_name = 'WR_EW_' . str_replace( '-', '_', ucwords( $widget, '-' ) );

            if ( class_exists( $class_name ) ) {
                $widgets_manager->register( new $class_name() );
            }
        }
    }
} );
