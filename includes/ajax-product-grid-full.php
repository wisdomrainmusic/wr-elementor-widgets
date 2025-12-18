<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Hard guard: WooCommerce must be available.
 */
function wr_pg_full_is_wc_ready() {
    return function_exists( 'wc_get_product' ) && class_exists( 'WC_Product' );
}

/**
 * Render a single product card for the Product Grid Full widget.
 */
function wr_pg_full_render_product_card( $product, $args = [] ) {
    if ( ! $product instanceof WC_Product || ! $product->is_visible() ) {
        return;
    }

    $defaults = [
        'show_price'  => true,
        'button_text' => __( 'SEÇENEKLER', 'wr-ew' ),
    ];

    $args = wp_parse_args( $args, $defaults );

    $product_link = $product->get_permalink();
    ?>
    <div class="wr-pg-full__item">
        <div class="wr-pg-full__thumb">
            <a href="<?php echo esc_url( $product_link ); ?>" class="wr-pg-full__image-link">
                <?php echo $product->get_image( 'medium' ); ?>
            </a>
        </div>

        <div class="wr-pg-full__meta">
            <a href="<?php echo esc_url( $product_link ); ?>" class="wr-pg-full__title-link">
                <h3 class="wr-pg-full__title"><?php echo esc_html( $product->get_name() ); ?></h3>
            </a>

            <?php if ( ! empty( $args['show_price'] ) ) : ?>
                <div class="wr-pg-full__price">
                    <?php echo wp_kses_post( $product->get_price_html() ); ?>
                </div>
            <?php endif; ?>

            <div class="wr-pg-full__actions">
                <a href="<?php echo esc_url( $product_link ); ?>" class="wr-pg-full__btn">
                    <?php echo esc_html( $args['button_text'] ); ?>
                </a>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Build pagination HTML for the Product Grid Full widget.
 */
function wr_pg_full_render_pagination( $current_page, $max_pages ) {
    $current_page = max( 1, (int) $current_page );
    $max_pages    = max( 1, (int) $max_pages );

    if ( $max_pages <= 1 ) {
        return '';
    }

    ob_start();
    ?>
    <div class="wr-pg-full__pagination-inner">
        <?php if ( $current_page > 1 ) : ?>
            <button class="wr-pg-full__page" data-page="<?php echo esc_attr( $current_page - 1 ); ?>">&lt;</button>
        <?php endif; ?>

        <?php for ( $i = 1; $i <= $max_pages; $i++ ) :
            $is_active = (int) $i === (int) $current_page ? ' is-active' : '';
            ?>
            <button class="wr-pg-full__page<?php echo esc_attr( $is_active ); ?>" data-page="<?php echo esc_attr( $i ); ?>">
                <?php echo esc_html( $i ); ?>
            </button>
        <?php endfor; ?>

        <?php if ( $current_page < $max_pages ) : ?>
            <button class="wr-pg-full__page" data-page="<?php echo esc_attr( $current_page + 1 ); ?>">&gt;</button>
        <?php endif; ?>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * AJAX filter handler for Product Grid Full.
 */
function wr_pg_full_ajax_handler() {
    if ( ! wr_pg_full_is_wc_ready() ) {
        wp_send_json_error( [ 'message' => 'WooCommerce not available' ], 400 );
    }

    if ( ! check_ajax_referer( 'wr_pg_full_nonce', 'nonce', false ) ) {
        wp_send_json_error( [ 'message' => 'Invalid nonce' ], 400 );
    }

    $cat        = isset( $_POST['cat'] ) ? (int) $_POST['cat'] : 0;
    $page       = isset( $_POST['page'] ) ? max( 1, (int) $_POST['page'] ) : 1;
    $per_page   = isset( $_POST['per_page'] ) ? max( 1, (int) $_POST['per_page'] ) : 12;
    $columns    = isset( $_POST['columns'] ) && (int) $_POST['columns'] === 3 ? 3 : 4;
    $show_price = ! empty( $_POST['show_price'] ) && 'yes' === sanitize_text_field( wp_unslash( $_POST['show_price'] ) );
    $button_txt = ! empty( $_POST['button_text'] ) ? sanitize_text_field( wp_unslash( $_POST['button_text'] ) ) : __( 'SEÇENEKLER', 'wr-ew' );

    $query_args = [
        'post_type'      => 'product',
        'post_status'    => 'publish',
        'posts_per_page' => $per_page,
        'paged'          => $page,
    ];

    if ( $cat > 0 ) {
        $query_args['tax_query'] = [
            [
                'taxonomy' => 'product_cat',
                'field'    => 'term_id',
                'terms'    => $cat,
            ],
        ];
    }

    $query = new WP_Query( $query_args );

    ob_start();
    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            $product = wc_get_product( get_the_ID() );
            wr_pg_full_render_product_card( $product, [
                'show_price'  => $show_price,
                'button_text' => $button_txt,
            ] );
        }
    } else {
        echo '<div class="wr-pg-full__empty">' . esc_html__( 'No products found.', 'wr-ew' ) . '</div>';
    }
    $items_html = ob_get_clean();

    $pagination_html = wr_pg_full_render_pagination( $page, (int) $query->max_num_pages );

    wp_reset_postdata();

    wp_send_json_success(
        [
            'items_html'      => $items_html,
            'pagination_html' => $pagination_html,
            'page'            => $page,
            'max_pages'       => (int) $query->max_num_pages,
            'total'           => (int) $query->found_posts,
            'columns'         => $columns,
        ]
    );
}

add_action( 'wp_ajax_wr_pg_full_filter', 'wr_pg_full_ajax_handler' );
add_action( 'wp_ajax_nopriv_wr_pg_full_filter', 'wr_pg_full_ajax_handler' );
