<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! function_exists( 'wr_render_product_card' ) ) {
    /**
     * Render a unified product card for grid, carousel, and shortcode contexts.
     */
    function wr_render_product_card( WC_Product $product, $context = 'grid' ) {
        if ( empty( $product ) || ! $product->is_visible() ) {
            return;
        }

        $context_class = $context ? ' wr-context-' . sanitize_html_class( $context ) : '';
        ?>
        <div class="wr-product-card<?php echo esc_attr( $context_class ); ?>">

            <?php do_action( 'woocommerce_before_shop_loop_item_title' ); ?>

            <div class="wr-product-image">
                <a href="<?php the_permalink(); ?>" class="wr-product-link">
                    <?php echo $product->get_image( 'medium' ); ?>
                </a>
            </div>

            <h3 class="wr-product-title">
                <a href="<?php the_permalink(); ?>" class="wr-product-link">
                    <?php echo esc_html( $product->get_name() ); ?>
                </a>
            </h3>

            <div class="wr-product-price">
                <?php echo wp_kses_post( $product->get_price_html() ); ?>
            </div>

            <?php do_action( 'woocommerce_after_shop_loop_item_title' ); ?>

            <div class="wr-card-actions">
                <button
                    class="wr-wishlist-btn"
                    data-product-id="<?php echo (int) $product->get_id(); ?>">
                    <svg class="wr-heart-icon" viewBox="0 0 24 24">
                        <path d="M12 21s-6-4.35-10-9c-2-2.48-2-6.5 1-8.5 2.5-1.6 6-.5 7.5 2C12 3 15.5 2 18 3.5c3 2 3 6.02 1 8.5-4 4.65-10 9-10 9z"/>
                    </svg>
                </button>

                <a
                    href="<?php echo esc_url( $product->add_to_cart_url() ); ?>"
                    class="wr-add-to-cart-btn button add_to_cart_button ajax_add_to_cart product_type_<?php echo esc_attr( $product->get_type() ); ?>"
                    data-product_id="<?php echo esc_attr( $product->get_id() ); ?>"
                    data-product_sku="<?php echo esc_attr( $product->get_sku() ); ?>"
                    aria-label="<?php echo esc_attr( $product->add_to_cart_description() ); ?>"
                >
                    <?php echo esc_html( $product->add_to_cart_text() ); ?>
                </a>
            </div>

            <?php do_action( 'woocommerce_after_shop_loop_item' ); ?>
        </div>
        <?php
    }
}
