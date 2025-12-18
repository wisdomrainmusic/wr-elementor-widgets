<?php
/**
 * WR Product Grid Full – AJAX handler and shared render helpers
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Render product cards for WR Product Grid Full.
 *
 * @param WP_Query $query        The query instance.
 * @param string   $button_text  Button label.
 */
function wr_pg_full_render_products( $query, $button_text = 'SEÇENEKLER' ) {
    if ( ! $query instanceof WP_Query ) {
        return;
    }

    if ( ! $query->have_posts() ) {
        echo '<div class="wr-pg-full__empty">' . esc_html__( 'No products found.', 'wr-ew' ) . '</div>';
        return;
    }

    while ( $query->have_posts() ) {
        $query->the_post();

        $product_id = get_the_ID();
        $product    = wc_get_product( $product_id );

        if ( ! $product ) {
            continue;
        }

        $permalink = get_permalink( $product_id );
        ?>
        <article class="wr-pg-full__card">
            <a class="wr-pg-full__thumb" href="<?php echo esc_url( $permalink ); ?>">
                <?php echo $product->get_image( 'woocommerce_thumbnail' ); ?>
            </a>

            <div class="wr-pg-full__card-body">
                <h3 class="wr-pg-full__title">
                    <a href="<?php echo esc_url( $permalink ); ?>">
                        <?php echo esc_html( $product->get_name() ); ?>
                    </a>
                </h3>

                <div class="wr-pg-full__price">
                    <?php echo wp_kses_post( $product->get_price_html() ); ?>
                </div>

                <a class="wr-pg-full__button" href="<?php echo esc_url( $permalink ); ?>">
                    <?php echo esc_html( $button_text ); ?>
                </a>
            </div>
        </article>
        <?php
    }
}

/**
 * Render pagination for WR Product Grid Full.
 *
 * @param WP_Query $query The query instance.
 */
function wr_pg_full_render_pagination( $query ) {
    if ( ! $query instanceof WP_Query ) {
        return;
    }

    $max_pages = (int) $query->max_num_pages;
    $current   = max( 1, (int) $query->get( 'paged' ) );

    if ( $max_pages <= 1 ) {
        return;
    }
    ?>
    <div class="wr-pg-full__pagination-inner">
        <?php if ( $current > 1 ) : ?>
            <button class="wr-pg-full__page wr-pg-full__page-prev" data-page="<?php echo esc_attr( $current - 1 ); ?>" type="button">&lt;</button>
        <?php endif; ?>

        <?php for ( $i = 1; $i <= $max_pages; $i++ ) : ?>
            <?php $is_active = ( $i === $current ) ? ' is-active' : ''; ?>
            <button class="wr-pg-full__page<?php echo esc_attr( $is_active ); ?>" data-page="<?php echo esc_attr( $i ); ?>" type="button">
                <?php echo esc_html( $i ); ?>
            </button>
        <?php endfor; ?>

        <?php if ( $current < $max_pages ) : ?>
            <button class="wr-pg-full__page wr-pg-full__page-next" data-page="<?php echo esc_attr( $current + 1 ); ?>" type="button">&gt;</button>
        <?php endif; ?>
    </div>
    <?php
}

/**
 * AJAX: Filter products by category + pagination.
 */
function wr_pg_full_filter_ajax() {
    $nonce_valid = check_ajax_referer( 'wr_pg_full_nonce', 'nonce', false );

    if ( ! $nonce_valid ) {
        wp_send_json_error( [ 'message' => __( 'Invalid request.', 'wr-ew' ) ], 400 );
    }

    if ( ! function_exists( 'wc_get_product' ) ) {
        wp_send_json_error( [ 'message' => __( 'WooCommerce is required for this widget.', 'wr-ew' ) ], 400 );
    }

    $cat       = isset( $_POST['cat'] ) ? (int) $_POST['cat'] : 0;
    $page      = isset( $_POST['page'] ) ? max( 1, (int) $_POST['page'] ) : 1;
    $per_page  = isset( $_POST['per_page'] ) ? max( 1, (int) $_POST['per_page'] ) : 9;
    $btn_text  = ! empty( $_POST['button_text'] ) ? sanitize_text_field( wp_unslash( $_POST['button_text'] ) ) : 'SEÇENEKLER';

    $args = [
        'post_type'      => 'product',
        'post_status'    => 'publish',
        'posts_per_page' => $per_page,
        'paged'          => $page,
    ];

    if ( $cat > 0 ) {
        $args['tax_query'] = [
            [
                'taxonomy' => 'product_cat',
                'field'    => 'term_id',
                'terms'    => [ $cat ],
            ],
        ];
    }

    $query = new WP_Query( $args );

    ob_start();
    wr_pg_full_render_products( $query, $btn_text );
    $items_html = ob_get_clean();

    ob_start();
    wr_pg_full_render_pagination( $query );
    $pagination_html = ob_get_clean();

    wp_reset_postdata();

    wp_send_json_success(
        [
            'items_html'      => $items_html,
            'pagination_html' => $pagination_html,
            'page'            => $page,
            'max_pages'       => (int) $query->max_num_pages,
        ]
    );
}

add_action( 'wp_ajax_wr_pg_full_filter', 'wr_pg_full_filter_ajax' );
add_action( 'wp_ajax_nopriv_wr_pg_full_filter', 'wr_pg_full_filter_ajax' );
