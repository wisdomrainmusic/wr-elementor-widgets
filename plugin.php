<?php
/*
Plugin Name: WR Elementor Widgets
Description: Custom Elementor widget set for WR E-Commerce Theme.
Version: 1.0.0
Author: Wisdom Rain
Text Domain: wr-ew
*/

if ( ! defined( 'ABSPATH' ) ) exit;

// Define plugin paths.
define( 'WR_EW_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'WR_EW_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Load plugin loader.
require_once WR_EW_PLUGIN_DIR . 'loader.php';

add_action( 'wp_ajax_wr_filter_products', 'wr_filter_products' );
add_action( 'wp_ajax_nopriv_wr_filter_products', 'wr_filter_products' );

function wr_filter_products() {
    $cat      = isset( $_POST['cat'] ) ? intval( $_POST['cat'] ) : 0;
    $page     = isset( $_POST['page'] ) ? max( 1, intval( $_POST['page'] ) ) : 1;
    $per_page = 12;

    $args = [
        'post_type'      => 'product',
        'posts_per_page' => $per_page,
        'paged'          => $page,
    ];

    if ( $cat > 0 ) {
        $args['tax_query'] = [
            [
                'taxonomy' => 'product_cat',
                'field'    => 'term_id',
                'terms'    => $cat,
            ],
        ];
    }

    $query = new WP_Query( $args );

    ob_start();

    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();

            $product     = wc_get_product( get_the_ID() );
            $image_url   = get_the_post_thumbnail_url( get_the_ID(), 'medium' );
            $image_url   = $image_url ? $image_url : wc_placeholder_img_src();
            $product_url = get_permalink();

            echo '<div class="wr-product-item">';
            echo '<a href="' . esc_url( $product_url ) . '" class="wr-product-link">';
            echo '<img src="' . esc_url( $image_url ) . '" alt="' . esc_attr( get_the_title() ) . '">';
            echo '<h3>' . esc_html( get_the_title() ) . '</h3>';
            echo '<span class="price">' . wp_kses_post( $product->get_price_html() ) . '</span>';
            echo '</a>';

            if ( $product && $product->is_purchasable() && $product->is_in_stock() ) {
                echo sprintf(
                    '<a href="%1$s" data-quantity="1" class="wr-add-to-cart button add_to_cart_button ajax_add_to_cart" data-product_id="%2$s" data-product_sku="%3$s" rel="nofollow">%4$s</a>',
                    esc_url( $product->add_to_cart_url() ),
                    esc_attr( $product->get_id() ),
                    esc_attr( $product->get_sku() ),
                    esc_html( $product->add_to_cart_text() )
                );
            }

            echo '</div>';
        }
        wp_reset_postdata();
    }

    $total_pages = $query->max_num_pages;
    if ( $total_pages > 1 ) {
        echo '<div class="wr-product-pagination">';
        for ( $i = 1; $i <= $total_pages; $i++ ) {
            $active = ( $i === $page ) ? ' active' : '';
            echo '<button type="button" class="wr-page-btn' . $active . '" data-page="' . esc_attr( $i ) . '">' . esc_html( $i ) . '</button>';
        }
        if ( $page < $total_pages ) {
            echo '<button type="button" class="wr-page-btn wr-page-next" data-page="' . esc_attr( $page + 1 ) . '">&rarr;</button>';
        }
        echo '</div>';
    }

    echo ob_get_clean();
    wp_die();
}
