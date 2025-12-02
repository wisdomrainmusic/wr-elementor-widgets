<?php
/**
 * WR Tab Product Grid - AJAX + Helpers
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Check if a term has products
 *
 * @param int $term_id
 * @return bool
 */
function wr_tpg_term_has_products( $term_id ) {
    if ( ! class_exists( 'WC_Product' ) ) {
        return false;
    }

    $query = new WP_Query(
        [
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
        ]
    );

    $has_products = $query->have_posts();
    wp_reset_postdata();

    return $has_products;
}

/**
 * Generate products HTML for a given subcategory
 *
 * @param int    $parent_cat_id
 * @param string $subcat_slug
 * @param int    $products_per_tab
 * @return string
 */
function wr_tpg_get_products_html( $parent_cat_id, $subcat_slug, $products_per_tab ) {
    if ( ! class_exists( 'WooCommerce' ) ) {
        return '';
    }

    $products_per_tab = $products_per_tab ? (int) $products_per_tab : 6;

    $args = [
        'post_type'      => 'product',
        'post_status'    => 'publish',
        'posts_per_page' => $products_per_tab,
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

    if ( $loop->have_posts() ) :
        echo '<ul class="products wr-tpg-products">';
        while ( $loop->have_posts() ) :
            $loop->the_post();
            // WooCommerce default product card + Add to cart
            wc_get_template_part( 'content', 'product' );
        endwhile;
        echo '</ul>';
    else :
        echo '<div class="wr-tpg-no-products">';
        esc_html_e( 'No products found in this category.', 'wr-elementor-widgets' );
        echo '</div>';
    endif;

    wp_reset_postdata();

    $html = ob_get_clean();

    return wp_kses_post( $html );
}

/**
 * AJAX Callback
 * Action: wr_tab_product_grid_load
 *
 * Params:
 *  - parent_cat
 *  - subcat_slug
 *  - product_count
 */
function wr_tab_product_grid_load_callback() {

    if ( empty( $_POST['subcat_slug'] ) || empty( $_POST['product_count'] ) ) {
        wp_die(); // WordPress AJAX default output: 0
    }

    $parent_cat_id    = isset( $_POST['parent_cat'] ) ? (int) $_POST['parent_cat'] : 0;
    $subcat_slug      = sanitize_text_field( wp_unslash( $_POST['subcat_slug'] ) );
    $products_per_tab = (int) $_POST['product_count'];

    $html = wr_tpg_get_products_html( $parent_cat_id, $subcat_slug, $products_per_tab );

    echo $html; // sadece grid HTML
    wp_die();
}

add_action( 'wp_ajax_wr_tab_product_grid_load', 'wr_tab_product_grid_load_callback' );
add_action( 'wp_ajax_nopriv_wr_tab_product_grid_load', 'wr_tab_product_grid_load_callback' );
