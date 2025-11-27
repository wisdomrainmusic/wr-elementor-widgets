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
add_action( 'wp_ajax_wr_add_to_wishlist', 'wr_add_to_wishlist' );
add_action( 'wp_ajax_wr_remove_from_wishlist', 'wr_remove_from_wishlist' );
add_action( 'wp_ajax_wr_get_wishlist', 'wr_get_wishlist' );
add_shortcode( 'wr_wishlist', 'wr_wishlist_shortcode' );
add_action( 'wp_footer', 'wr_render_wishlist_fab' );

function wr_get_user_wishlist_ids() {
    if ( ! is_user_logged_in() ) {
        return [];
    }

    $user_id = get_current_user_id();
    $ids     = get_user_meta( $user_id, 'wr_wishlist', true );

    if ( empty( $ids ) || ! is_array( $ids ) ) {
        return [];
    }

    return array_values( array_unique( array_map( 'intval', $ids ) ) );
}

function wr_save_user_wishlist_ids( $ids ) {
    if ( ! is_user_logged_in() ) {
        return;
    }

    $user_id = get_current_user_id();
    $ids     = array_values( array_unique( array_map( 'intval', (array) $ids ) ) );

    update_user_meta( $user_id, 'wr_wishlist', $ids );
}

function wr_add_to_wishlist() {
    if ( ! is_user_logged_in() ) {
        wp_send_json_error( [ 'message' => 'Unauthorized' ], 401 );
    }

    $product_id = isset( $_POST['product_id'] ) ? intval( $_POST['product_id'] ) : 0;

    if ( $product_id <= 0 ) {
        wp_send_json_error( [ 'message' => 'Invalid product' ], 400 );
    }

    $wishlist   = wr_get_user_wishlist_ids();
    $wishlist[] = $product_id;

    wr_save_user_wishlist_ids( $wishlist );

    wp_send_json( wr_get_user_wishlist_ids() );
}

function wr_remove_from_wishlist() {
    if ( ! is_user_logged_in() ) {
        wp_send_json_error( [ 'message' => 'Unauthorized' ], 401 );
    }

    $product_id = isset( $_POST['product_id'] ) ? intval( $_POST['product_id'] ) : 0;

    if ( $product_id <= 0 ) {
        wp_send_json_error( [ 'message' => 'Invalid product' ], 400 );
    }

    $wishlist = wr_get_user_wishlist_ids();
    $wishlist = array_filter(
        $wishlist,
        function( $id ) use ( $product_id ) {
            return intval( $id ) !== $product_id;
        }
    );

    wr_save_user_wishlist_ids( $wishlist );

    wp_send_json( wr_get_user_wishlist_ids() );
}

function wr_get_wishlist() {
    if ( ! is_user_logged_in() ) {
        wp_send_json_error( [ 'message' => 'Unauthorized' ], 401 );
    }

    wp_send_json( wr_get_user_wishlist_ids() );
}

function wr_wishlist_shortcode() {
    if ( ! is_user_logged_in() ) {
        return '<p>' . esc_html__( 'Please log in to view your wishlist.', 'wr-ew' ) . '</p>';
    }

    $wishlist_ids = wr_get_user_wishlist_ids();

    if ( empty( $wishlist_ids ) ) {
        return '<p>' . esc_html__( 'Your wishlist is empty.', 'wr-ew' ) . '</p>';
    }

    $query = new WP_Query(
        [
            'post_type'      => 'product',
            'post__in'       => $wishlist_ids,
            'orderby'        => 'post__in',
            'posts_per_page' => -1,
        ]
    );

    ob_start();

    echo '<div class="wr-wishlist-grid">';

    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();

            $product     = wc_get_product( get_the_ID() );
            $image_url   = get_the_post_thumbnail_url( get_the_ID(), 'medium' );
            $image_url   = $image_url ? $image_url : wc_placeholder_img_src();
            $product_url = get_permalink();

            echo '<div class="wr-product-item">';
            echo '<button class="wr-wishlist-btn" data-id="' . get_the_ID() . '">';
            echo '<svg class="wr-heart-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 21s-5.052-3.21-8.106-6.264C2.108 13.95 1 12.486 1 10.75 1 8.678 2.678 7 4.75 7c1.264 0 2.493.593 3.25 1.528C8.757 7.593 9.986 7 11.25 7 13.322 7 15 8.678 15 10.75c0 1.736-1.108 3.2-2.894 3.986C13.052 17.79 12 21 12 21z"/></svg>';
            echo '</button>';
            echo '<a href="' . esc_url( $product_url ) . '" class="wr-product-link">';
            echo '<img src="' . esc_url( $image_url ) . '" alt="' . esc_attr( get_the_title() ) . '">';
            echo '<h3>' . esc_html( get_the_title() ) . '</h3>';
            echo '<span class="price">' . wp_kses_post( $product->get_price_html() ) . '</span>';
            echo '</a>';

            if ( $product && $product->is_purchasable() && $product->is_in_stock() ) {
                echo sprintf(
                    '<a href="%1$s" data-quantity="1" class="button add_to_cart_button ajax_add_to_cart" data-product_id="%2$s" data-product_sku="%3$s" rel="nofollow">%4$s</a>',
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

    echo '</div>';

    return ob_get_clean();
}

function wr_render_wishlist_fab() {
    $wishlist_page = get_page_by_path( 'wishlist' );
    $wishlist_url  = $wishlist_page ? get_permalink( $wishlist_page ) : home_url( '/wishlist/' );

    echo '<a href="' . esc_url( $wishlist_url ) . '" class="wr-wishlist-fab">';
    echo '<span class="wr-fab-heart">❤</span>';
    echo '<span class="wr-wishlist-count">0</span>';
    echo '</a>';
}

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

            wc_get_template( 'content-product.php', [], '', WR_EW_PLUGIN_DIR . 'templates/' );
        }
    }

    $products_html = ob_get_clean();

    $total_pages     = $query->max_num_pages;
    $original_query  = $GLOBALS['wp_query'] ?? null;

    ob_start();

    if ( $total_pages > 1 ) {
        $GLOBALS['wp_query'] = $query;

        wc_setup_loop(
            [
                'total'        => $query->found_posts,
                'total_pages'  => $total_pages,
                'per_page'     => $per_page,
                'current_page' => $page,
                'is_paginated' => true,
            ]
        );

        echo '<div class="wr-pagination">';
        woocommerce_pagination();
        echo '</div>';

        wc_reset_loop();
    }

    $GLOBALS['wp_query'] = $original_query;

    $pagination_html = ob_get_clean();

    wp_reset_postdata();

    echo $products_html . $pagination_html;
    wp_die();
}
