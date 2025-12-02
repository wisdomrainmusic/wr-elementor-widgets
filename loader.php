<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * -------------------------------------------------------
 * REQUIRED FILES
 * -------------------------------------------------------
 */
require_once WR_EW_PLUGIN_DIR . 'includes/ajax-product-grid.php';
require_once WR_EW_PLUGIN_DIR . 'includes/render-product-card.php';
require_once WR_EW_PLUGIN_DIR . 'includes/ajax-blog-grid.php';
require_once WR_EW_PLUGIN_DIR . 'includes/tab-product-grid.php';


/**
 * -------------------------------------------------------
 * ELEMENTOR CATEGORIES
 * -------------------------------------------------------
 */
add_action( 'elementor/elements/categories_registered', function( $elements_manager ) {

    $elements_manager->add_category(
        'wr-widgets',
        [
            'title' => __( 'WR Widgets', 'wr-ew' ),
            'icon'  => 'fa fa-plug'
        ]
    );

    $elements_manager->add_category(
        'wr-ecommerce-elements',
        [
            'title' => __( 'WR Ecommerce Elements', 'wr-ew' ),
            'icon'  => 'eicon-woocommerce'
        ]
    );
});


/**
 * -------------------------------------------------------
 * ENQUEUE GLOBAL ASSETS
 * -------------------------------------------------------
 */
add_action( 'wp_enqueue_scripts', function() {

    /**
     * Swiper Library
     */
    wp_enqueue_style(
        'wr-swiper',
        'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css'
    );

    wp_enqueue_script(
        'wr-swiper',
        'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js',
        [],
        null,
        true
    );


    /**
     * Widget Asset Map
     */
    $assets = [

        'hero-slider'              => [ 'css' => true, 'js' => [ 'wr-swiper' ] ],
        'category-slider'          => [ 'css' => true, 'js' => [ 'wr-swiper' ] ],

        'banner'                   => [ 'css' => true, 'js' => [ 'jquery' ] ],
        'blog-grid'                => [ 'css' => true, 'js' => [ 'jquery' ] ],
        'campaign-bar'             => [ 'css' => true, 'js' => [ 'jquery' ] ],

        // Product Grid — only wr-grid.js (wishlist removed)
        'product-grid'             => [ 'css' => true, 'js' => [ 'jquery', 'wr-grid-js' ] ],

        'featured-cart-horizontal' => [ 'css' => true, 'js' => [ 'jquery' ] ],
        'featured-cart-vertical'   => [ 'css' => true, 'js' => [ 'jquery' ] ],
        'featured-cart-square'     => [ 'css' => true, 'js' => [ 'jquery' ] ],

        'instagram-story'          => [ 'css' => true, 'js' => [ 'jquery' ] ],
        'testimonials'             => [ 'css' => true, 'js' => [ 'jquery' ] ],

        'product-tabs'             => [ 'css' => true, 'js' => [ 'jquery' ] ],
        'tab-product-grid'         => [ 'css' => true, 'js' => [ 'jquery' ] ],
    ];


    /**
     * UNIVERSAL LOADER
     */
    foreach ( $assets as $key => $config ) {

        // CSS
        if ( ! empty( $config['css'] ) ) {
            wp_enqueue_style(
                "wr-{$key}-css",
                WR_EW_PLUGIN_URL . "assets/css/{$key}.css",
                [],
                '2.1'
            );
        }

        // JS
        if ( ! empty( $config['js'] ) ) {

            // Special rule: product-grid → wr-grid.js
            if ( $key === 'product-grid' ) {
                wp_enqueue_script(
                    'wr-grid-js',
                    WR_EW_PLUGIN_URL . 'assets/js/wr-grid.js',
                    [ 'jquery' ],
                    '2.1',
                    true
                );
            }

            wp_enqueue_script(
                "wr-{$key}-js",
                WR_EW_PLUGIN_URL . "assets/js/{$key}.js",
                $config['js'],
                '2.1',
                true
            );

            wp_localize_script(
                "wr-{$key}-js",
                'wrEwAjax',
                [ 'ajax_url' => admin_url( 'admin-ajax.php' ) ]
            );
        }
    }

});


/**
 * -------------------------------------------------------
 * ELEMENTOR WIDGET LOADER
 * -------------------------------------------------------
 */
add_action( 'elementor/widgets/register', function( $widgets_manager ) {

    $widget_dirs = [
        'banner',
        'blog-grid',
        'campaign-bar',
        'category-grid',
        'category-slider',
        'featured-cart-horizontal',
        'featured-cart-vertical',
        'featured-cart-square',
        'hero-slider',
        'instagram-story',
        'product-grid',
        'product-tabs',
        'tab-product-grid',
        'testimonials',
        // ❌ wishlist removed
    ];

    foreach ( $widget_dirs as $widget ) {

        $file = WR_EW_PLUGIN_DIR . "widgets/{$widget}/widget.php";

        if ( file_exists( $file ) ) {

            require_once $file;

            $class_name = 'WR_EW_' . str_replace(
                '-', '_',
                ucwords( $widget, '-' )
            );

            if ( class_exists( $class_name ) ) {
                $widgets_manager->register( new $class_name() );
            }
        }
    }
});
