<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'wp_ajax_wr_ajax_load_products', 'wr_ajax_load_products' );
add_action( 'wp_ajax_nopriv_wr_ajax_load_products', 'wr_ajax_load_products' );

function wr_ajax_load_products() {

    $page = isset( $_POST['page'] ) ? intval( $_POST['page'] ) : 1;

    $args = [
        'post_type'      => 'product',
        'posts_per_page' => 12,
        'paged'          => $page,
    ];

    $query = new WP_Query( $args );

    ob_start(); ?>

    <div class="wr-product-items">
        <?php
        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post();
                wc_get_template( 'content-product.php', [], '', WR_EW_PLUGIN_DIR . 'templates/' );
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
