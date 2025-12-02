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

add_action( 'wp_enqueue_scripts', function() {
    wp_register_style( 'wr-usp-row-css', WR_EW_PLUGIN_URL . 'assets/css/usp-row.css', [], '1.0' );
    wp_register_script( 'wr-usp-row-js', WR_EW_PLUGIN_URL . 'assets/js/usp-row.js', [ 'jquery' ], '1.0', true );
}, 5 );

// Load plugin loader.
require_once WR_EW_PLUGIN_DIR . 'loader.php';

add_action( 'wp_ajax_wr_filter_products', 'wr_filter_products' );
add_action( 'wp_ajax_nopriv_wr_filter_products', 'wr_filter_products' );
add_action( 'wp_ajax_wr_update_wishlist', 'wr_update_wishlist' );
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

function wr_update_wishlist() {
    if ( ! is_user_logged_in() ) {
        wp_send_json_error( [ 'message' => 'Unauthorized' ], 401 );
    }

    check_ajax_referer( 'wr_grid_nonce', 'nonce' );

    $product_id = isset( $_POST['product_id'] ) ? intval( $_POST['product_id'] ) : 0;
    $operation  = isset( $_POST['operation'] ) ? sanitize_text_field( wp_unslash( $_POST['operation'] ) ) : '';
    $local_ids  = isset( $_POST['local_ids'] ) ? (array) $_POST['local_ids'] : [];

    $wishlist = wr_get_user_wishlist_ids();

    if ( ! empty( $local_ids ) ) {
        $wishlist = array_merge( $wishlist, array_map( 'intval', $local_ids ) );
    }

    if ( $product_id > 0 ) {
        if ( 'remove' === $operation ) {
            $wishlist = array_filter(
                $wishlist,
                function ( $id ) use ( $product_id ) {
                    return intval( $id ) !== $product_id;
                }
            );
        } elseif ( 'toggle' === $operation ) {
            if ( in_array( $product_id, $wishlist, true ) ) {
                $wishlist = array_filter(
                    $wishlist,
                    function ( $id ) use ( $product_id ) {
                        return intval( $id ) !== $product_id;
                    }
                );
            } else {
                $wishlist[] = $product_id;
            }
        } else {
            $wishlist[] = $product_id;
        }
    }

    $wishlist = array_values( array_unique( array_map( 'intval', $wishlist ) ) );

    wr_save_user_wishlist_ids( $wishlist );

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
        global $product;

        while ( $query->have_posts() ) {
            $query->the_post();

            $product         = wc_get_product( get_the_ID() );
            $wr_card_context = 'wishlist';

            include WR_EW_PLUGIN_DIR . 'widgets/product-grid/card.php';
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

    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    check_ajax_referer( 'wr_grid_nonce', 'nonce' );

    $page = isset( $_POST['page'] ) ? max( 1, intval( $_POST['page'] ) ) : 1;
    $cat  = isset( $_POST['cat'] )  ? absint( $_POST['cat'] )          : 0;

    $args = [
        'post_type'      => 'product',
        'posts_per_page' => 12,
        'paged'          => $page,
    ];

    // Kategori filtresi
    if ( $cat ) {
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
            global $product;
            // TAM BURASI: Grid ve Ajax her yerde aynı kartı kullanıyor
            include WR_EW_PLUGIN_DIR . 'widgets/product-grid/card.php';
        }
    } else {
        echo '<p>No products found.</p>';
    }

    wp_reset_postdata();

    echo ob_get_clean();
    wp_die();
}
