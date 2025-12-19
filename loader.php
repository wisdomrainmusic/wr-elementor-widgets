<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * -------------------------------------------------------
 * REQUIRED FILES
 * -------------------------------------------------------
 */
require_once WR_EW_PLUGIN_DIR . 'includes/render-product-card.php';
require_once WR_EW_PLUGIN_DIR . 'includes/ajax-blog-grid.php';
require_once WR_EW_PLUGIN_DIR . 'includes/ajax-product-grid-full.php';

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

    // Swiper (global – slider kullanan her şey için)
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
     * Asset Map
     */
    $assets = [

        'hero-slider-full'   => [ 'css' => true, 'js' => [ 'jquery', 'wr-swiper' ] ],

        'blog-grid'            => [ 'css' => true, 'js' => [ 'jquery' ] ],
        'video-banner'         => [ 'css' => true, 'js' => [ 'jquery' ] ],
        'product-grid-full'    => [ 'css' => true, 'js' => [ 'jquery' ] ],
        'testimonial-full'     => [ 'css' => true, 'js' => [ 'jquery', 'wr-swiper' ] ],
        'campaign-bar-full'    => [ 'css' => true, 'js' => [ 'jquery' ] ],

        'instagram-story' => [ 'css' => true, 'js' => [ 'jquery' ] ],

        // ✅ INLINE (CSS/JS widget içinde)
        'product-full-tabs'  => [ 'css' => false, 'js' => false ],
        'featured-card-full' => [ 'css' => false, 'js' => false ],
        'hero-banner-full'   => [ 'css' => false, 'js' => false ],
    ];

    foreach ( $assets as $key => $config ) {

        // CSS
        if ( ! empty( $config['css'] ) ) {
            wp_enqueue_style(
                "wr-{$key}-css",
                WR_EW_PLUGIN_URL . "assets/css/{$key}.css",
                [],
                '2.2'
            );
        }

        // JS
        if ( ! empty( $config['js'] ) ) {

            // Generic enqueue (diğer widgetlar)
            wp_enqueue_script(
                "wr-{$key}-js",
                WR_EW_PLUGIN_URL . "assets/js/{$key}.js",
                $config['js'],
                '2.2',
                true
            );

            if ( 'product-grid-full' === $key ) {
                wp_localize_script(
                    "wr-{$key}-js",
                    'wrPgFullData',
                    [
                        'ajax_url' => admin_url( 'admin-ajax.php' ),
                        'nonce'    => wp_create_nonce( 'wr_pg_full_nonce' ),
                        'debug'    => ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ? true : false,
                    ]
                );
            } elseif ( 'campaign-bar-full' === $key ) {
                wp_localize_script(
                    "wr-{$key}-js",
                    'wrCampaignBarFullData',
                    [
                        'debug' => ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ? true : false,
                    ]
                );
            } else {
                wp_localize_script(
                    "wr-{$key}-js",
                    'wrEwAjax',
                    [ 'ajax_url' => admin_url( 'admin-ajax.php' ) ]
                );
            }
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

        // Core
        'blog-grid',
        'category-grid',
        'hero-slider-full',
        'instagram-story',
        'video-banner',
        'product-grid-full',
        'testimonial-full',
        'campaign-bar-full',

        // ✅ Yeni nesil (inline)
        'product-full-tabs',
        'featured-card-full',
        'hero-banner-full',
    ];

    // %100 garantili class map
    $class_map = [
        'product-full-tabs'  => 'WR_EW_Product_Full_Tabs',
        'featured-card-full' => 'WR_EW_Featured_Card_Full',
        'hero-banner-full'   => 'WR_EW_Hero_Banner_Full',
        'product-grid-full'  => 'WR_EW_Product_Grid_Full',
        'testimonial-full'   => 'WR_EW_Testimonial_Full',
    ];

    foreach ( $widget_dirs as $widget ) {

        $file = WR_EW_PLUGIN_DIR . "widgets/{$widget}/widget.php";
        if ( ! file_exists( $file ) ) {
            continue;
        }

        require_once $file;

        $default_class = 'WR_EW_' . str_replace(
            '-',
            '_',
            ucwords( $widget, '-' )
        );

        $class_name = $class_map[ $widget ] ?? $default_class;

        if ( class_exists( $class_name ) ) {
            $widgets_manager->register( new $class_name() );
        }
    }
});
