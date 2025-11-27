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
    $cat  = isset( $_POST['cat'] ) ? intval( $_POST['cat'] ) : 0;
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => 12,
    );

    if ( $cat > 0 ) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'product_cat',
                'field'    => 'term_id',
                'terms'    => $cat,
            ),
        );
    }

    $query = new WP_Query( $args );

    ob_start();

    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();

            $product    = wc_get_product( get_the_ID() );
            $image_url  = get_the_post_thumbnail_url( get_the_ID(), 'medium' );
            $image_url  = $image_url ? $image_url : wc_placeholder_img_src();
            $product_url = get_permalink();

            ?>
            <div class="wr-product-item">
                <a href="<?php echo esc_url( $product_url ); ?>">
                    <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php the_title_attribute(); ?>">
                    <h3><?php the_title(); ?></h3>
                    <span class="price"><?php echo wp_kses_post( $product->get_price_html() ); ?></span>
                </a>
            </div>
            <?php
        }
        wp_reset_postdata();
    }

    echo ob_get_clean();
    wp_die();
}
