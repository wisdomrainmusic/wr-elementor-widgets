<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'wp_ajax_wr_ajax_load_products', 'wr_ajax_load_products' );
add_action( 'wp_ajax_nopriv_wr_ajax_load_products', 'wr_ajax_load_products' );

function wr_ajax_load_products() {

    check_ajax_referer( 'wr_grid_nonce', 'nonce' );

    $page = isset( $_POST['page'] ) ? intval( $_POST['page'] ) : 1;
    $cat  = isset( $_POST['cat'] ) ? absint( $_POST['cat'] )  : 0;

    $args = [
        'post_type'      => 'product',
        'posts_per_page' => 12,
        'paged'          => $page,
    ];

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

    ob_start(); ?>

    <div class="wr-product-items">
        <?php
        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post();
                // Load custom WR product card
                include WR_EW_PLUGIN_DIR . 'widgets/product-grid/card.php';
            }
        }
        ?>
    </div>

    <div class="wr-pagination">
        <?php
        echo paginate_links([
            'total'     => $query->max_num_pages,
            'current'   => $page,
            'format'    => '#',
            'type'      => 'plain',
            'prev_text' => '&lt;',
            'next_text' => '&gt;',
        ]);
        ?>
    </div>

    <?php
    wp_reset_postdata();
    echo ob_get_clean();
    wp_die();
}
