<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// Elementor init.
add_action( 'elementor/widgets/register', 'wr_ew_register_widgets' );
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

// Register widgets loader.
function wr_ew_register_widgets( $widgets_manager ) {

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

        $widget_file = WR_EW_PLUGIN_DIR . 'widgets/' . $widget . '/' . $widget . '.php';

        if ( file_exists( $widget_file ) ) {
            require_once $widget_file;

            $class_name = 'WR_EW_' . str_replace( '-', '_', ucwords( $widget, '-' ) );
            if ( class_exists( $class_name ) ) {
                $widgets_manager->register( new $class_name() );
            }
        }
    }
}
