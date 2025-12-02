<?php
/**
 * WR Blog Grid – AJAX handler + shared render functions
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Shared: card renderer
 */
function wr_ew_render_blog_grid_items( $query, $args = [] ) {

    $defaults = [
        'excerpt_length' => 18,
        'show_excerpt'   => 'yes',
        'show_read_more' => 'yes',
        'read_more_text' => __( 'Read more', 'wr-elementor-widgets' ),
    ];

    $args = wp_parse_args( $args, $defaults );

    if ( ! $query instanceof WP_Query ) {
        return;
    }

    if ( ! $query->have_posts() ) {
        echo '<div class="wr-blog-grid-empty">' . esc_html__( 'No posts found.', 'wr-elementor-widgets' ) . '</div>';
        return;
    }

    while ( $query->have_posts() ) :
        $query->the_post();

        $post_id = get_the_ID();
        $permalink = get_permalink( $post_id );
        ?>
        <article class="wr-blog-card">
            <div class="wr-blog-thumb">
                <a href="<?php echo esc_url( $permalink ); ?>">
                    <?php
                    if ( has_post_thumbnail( $post_id ) ) {
                        echo get_the_post_thumbnail(
                            $post_id,
                            'medium_large',
                            [
                                'class' => 'wr-blog-thumb-img',
                                'alt'   => esc_attr( get_the_title( $post_id ) ),
                            ]
                        );
                    } else {
                        ?>
                        <div class="wr-blog-thumb-placeholder"></div>
                        <?php
                    }
                    ?>
                </a>
            </div>

            <div class="wr-blog-content">
                <h3 class="wr-blog-title">
                    <a href="<?php echo esc_url( $permalink ); ?>">
                        <?php echo esc_html( get_the_title( $post_id ) ); ?>
                    </a>
                </h3>

                <div class="wr-blog-meta">
                    <span class="wr-blog-date">
                        <?php echo esc_html( get_the_date() ); ?>
                    </span>
                </div>

                <?php if ( 'yes' === $args['show_excerpt'] ) : ?>
                    <div class="wr-blog-excerpt">
                        <?php
                        $excerpt = get_the_excerpt();
                        $words   = explode( ' ', wp_strip_all_tags( $excerpt ) );
                        $limit   = (int) $args['excerpt_length'];

                        if ( $limit > 0 && count( $words ) > $limit ) {
                            $excerpt = implode( ' ', array_slice( $words, 0, $limit ) ) . '…';
                        }

                        echo esc_html( $excerpt );
                        ?>
                    </div>
                <?php endif; ?>

                <?php if ( 'yes' === $args['show_read_more'] ) : ?>
                    <div class="wr-blog-readmore">
                        <a class="wr-blog-readmore-btn" href="<?php echo esc_url( $permalink ); ?>">
                            <?php echo esc_html( $args['read_more_text'] ); ?>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </article>
        <?php
    endwhile;
}

/**
 * Shared: pagination renderer
 */
function wr_ew_render_blog_grid_pagination( $query, $widget_id ) {

    if ( ! $query instanceof WP_Query ) {
        return;
    }

    $total_pages = (int) $query->max_num_pages;

    if ( $total_pages <= 1 ) {
        return;
    }

    $current_page = max( 1, (int) $query->get( 'paged' ) );
    ?>
    <div class="wr-blog-pagination-inner" data-widget-id="<?php echo esc_attr( $widget_id ); ?>">
        <?php
        // Prev
        if ( $current_page > 1 ) :
            ?>
            <button class="wr-blog-page-btn wr-blog-page-prev" data-page="<?php echo esc_attr( $current_page - 1 ); ?>">
                &lt;
            </button>
            <?php
        endif;

        for ( $i = 1; $i <= $total_pages; $i++ ) :
            $is_active = ( $i === $current_page ) ? ' is-active' : '';
            ?>
            <button class="wr-blog-page-btn<?php echo esc_attr( $is_active ); ?>" data-page="<?php echo esc_attr( $i ); ?>">
                <?php echo esc_html( $i ); ?>
            </button>
            <?php
        endfor;

        // Next
        if ( $current_page < $total_pages ) :
            ?>
            <button class="wr-blog-page-btn wr-blog-page-next" data-page="<?php echo esc_attr( $current_page + 1 ); ?>">
                &gt;
            </button>
            <?php
        endif;
        ?>
    </div>
    <?php
}

/**
 * AJAX handler
 */
function wr_ew_blog_grid_ajax() {

    check_ajax_referer( 'wr_blog_grid_nonce', 'nonce' );

    $page       = isset( $_POST['page'] ) ? max( 1, (int) $_POST['page'] ) : 1;
    $cat_id     = isset( $_POST['category'] ) ? (int) $_POST['category'] : 0;
    $per_page   = isset( $_POST['per_page'] ) ? (int) $_POST['per_page'] : 6;
    $parent_cat = isset( $_POST['parent_cat'] ) ? (int) $_POST['parent_cat'] : 0;

    $args = [
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'posts_per_page' => $per_page,
        'paged'          => $page,
    ];

    if ( $cat_id > 0 ) {
        $args['cat'] = $cat_id;
    } elseif ( $parent_cat > 0 ) {
        $args['cat'] = $parent_cat;
    }

    $query = new WP_Query( $args );

    $render_args = [
        'excerpt_length' => isset( $_POST['excerpt_length'] ) ? (int) $_POST['excerpt_length'] : 18,
        'show_excerpt'   => ! empty( $_POST['show_excerpt'] ) ? sanitize_text_field( wp_unslash( $_POST['show_excerpt'] ) ) : 'yes',
        'show_read_more' => ! empty( $_POST['show_read_more'] ) ? sanitize_text_field( wp_unslash( $_POST['show_read_more'] ) ) : 'yes',
        'read_more_text' => ! empty( $_POST['read_more_text'] ) ? sanitize_text_field( wp_unslash( $_POST['read_more_text'] ) ) : __( 'Read more', 'wr-elementor-widgets' ),
    ];

    ob_start();
    wr_ew_render_blog_grid_items( $query, $render_args );
    $html_items = ob_get_clean();

    ob_start();
    wr_ew_render_blog_grid_pagination( $query, sanitize_text_field( wp_unslash( $_POST['widget_id'] ?? '' ) ) );
    $html_pagination = ob_get_clean();

    wp_reset_postdata();

    wp_send_json_success(
        [
            'items'      => $html_items,
            'pagination' => $html_pagination,
        ]
    );
}

add_action( 'wp_ajax_wr_blog_grid_pagination', 'wr_ew_blog_grid_ajax' );
add_action( 'wp_ajax_nopriv_wr_blog_grid_pagination', 'wr_ew_blog_grid_ajax' );
