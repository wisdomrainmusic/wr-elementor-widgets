<?php
/**
 * WR Tab Product Grid - AJAX + Helpers (FINAL + LOAD MORE)
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Alt kategoride ürün var mı kontrolü
 */
function wr_tpg_term_has_products( $term_id ) {

    if ( ! class_exists( 'WC_Product' ) ) {
        return false;
    }

    $query = new WP_Query([
        'post_type'      => 'product',
        'post_status'    => 'publish',
        'posts_per_page' => 1,
        'tax_query'      => [
            [
                'taxonomy' => 'product_cat',
                'field'    => 'term_id',
                'terms'    => (int) $term_id,
            ],
        ],
    ]);

    $has = $query->have_posts();
    wp_reset_postdata();

    return $has;
}

/**
 * Verilen alt kategori için ürünleri çeker
 * ve WR ürün kartı ile HTML üretir.
 *
 * @return array { html, count, has_more }
 */
function wr_tpg_fetch_products( $parent_cat_id, $subcat_slug, $per_page, $page = 1 ) {

    if ( ! class_exists( 'WooCommerce' ) ) {
        return [
            'html'     => '',
            'count'    => 0,
            'has_more' => false,
        ];
    }

    $per_page = $per_page ? (int) $per_page : 6;
    $page     = $page ? (int) $page : 1;
    $offset   = ( $page - 1 ) * $per_page;

    $args = [
        'post_type'      => 'product',
        'post_status'    => 'publish',
        'posts_per_page' => $per_page,
        'offset'         => $offset,
        'tax_query'      => [
            [
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => $subcat_slug,
            ],
        ],
    ];

    $loop = new WP_Query( $args );

    ob_start();

    if ( $loop->have_posts() ) {

        // İlk sayfada UL wrapper; diğer sayfalarda da UL içinde ekliyoruz,
        // ama front-end'te zaten mevcut grid'e append yapacağız.
        echo '<ul class="products wr-tpg-products">';

        while ( $loop->have_posts() ) {
            $loop->the_post();

            echo '<li class="product wr-tpg-card">';

            $product = wc_get_product( get_the_ID() );

            if ( $product && function_exists( 'wr_render_product_card' ) ) {
                echo wr_render_product_card( $product );

            } elseif ( $product && function_exists( 'wr_ew_render_product_card' ) ) {
                echo wr_ew_render_product_card( $product );

            } else {
                // Fallback – büyük ihtimal kullanılmayacak
                wc_get_template_part( 'content', 'product' );
            }

            echo '</li>';
        }

        echo '</ul>';

    } else {

        // Sadece ilk sayfada "No products" mesajı gösterelim.
        if ( 1 === $page ) {
            echo '<div class="wr-tpg-no-products">No products found.</div>';
        }
    }

    $html  = ob_get_clean();
    $count = $loop->post_count;

    wp_reset_postdata();

    return [
        'html'     => $html,
        'count'    => $count,
        'has_more' => ( $count === $per_page ),
    ];
}

/**
 * AJAX callback – tab değişimi + load more
 * JSON → { html, has_more }
 */
function wr_tab_product_grid_load_callback() {

    if ( empty( $_POST['subcat_slug'] ) || empty( $_POST['product_count'] ) ) {
        wp_send_json_error();
    }

    $parent_cat_id    = isset( $_POST['parent_cat'] ) ? (int) $_POST['parent_cat'] : 0;
    $subcat_slug      = sanitize_text_field( wp_unslash( $_POST['subcat_slug'] ) );
    $products_per_tab = (int) $_POST['product_count'];
    $page             = ! empty( $_POST['page'] ) ? (int) $_POST['page'] : 1;

    $result = wr_tpg_fetch_products( $parent_cat_id, $subcat_slug, $products_per_tab, $page );

    wp_send_json_success( [
        'html'     => $result['html'],
        'has_more' => $result['has_more'],
    ] );
}

add_action( 'wp_ajax_wr_tab_product_grid_load', 'wr_tab_product_grid_load_callback' );
add_action( 'wp_ajax_nopriv_wr_tab_product_grid_load', 'wr_tab_product_grid_load_callback' );
