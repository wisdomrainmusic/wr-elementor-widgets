<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! function_exists( 'wr_render_product_card' ) ) {

    /**
     * Clean WR Product Card (No Wishlist Elements)
     */
    function wr_render_product_card( WC_Product $product, $context = 'grid' ) {

        if ( empty( $product ) || ! $product->is_visible() ) {
            return;
        }

        $product_id    = (int) $product->get_id();
        $product_link  = $product->get_permalink();
        $context_class = $context ? ' wr-context-' . sanitize_html_class( $context ) : '';
        ?>

        <div class="wr-product-card<?php echo esc_attr( $context_class ); ?>">

            <!-- IMAGE WRAPPER -->
            <div class="wr-product-image-wrapper">

                <!-- IMAGE LINK -->
                <a href="<?php echo esc_url( $product_link ); ?>" class="wr-product-link">
                    <div class="wr-product-image">
                        <?php echo $product->get_image( 'medium' ); ?>
                    </div>
                </a>

                <?php if ( function_exists( 'wrw_render_wishlist_button' ) ) : ?>
                    <div class="wr-product-card__wishlist">
                        <?php wrw_render_wishlist_button( $product, $context ); ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- TITLE -->
            <a href="<?php echo esc_url( $product_link ); ?>">
                <h3 class="wr-product-title">
                    <?php echo esc_html( $product->get_name() ); ?>
                </h3>
            </a>

            <!-- PRICE -->
            <div class="wr-product-price">
                <?php echo wp_kses_post( $product->get_price_html() ); ?>
            </div>

            <!-- ACTIONS -->
            <div class="wr-card-actions">
                <a href="<?php echo esc_url( $product->add_to_cart_url() ); ?>"
                   class="wr-add-to-cart-btn button add_to_cart_button ajax_add_to_cart product_type_<?php echo esc_attr( $product->get_type() ); ?>"
                   data-product_id="<?php echo esc_attr( $product_id ); ?>"
                   data-product_sku="<?php echo esc_attr( $product->get_sku() ); ?>"
                   aria-label="<?php echo esc_attr( $product->add_to_cart_description() ); ?>">
                    <?php echo esc_html( $product->add_to_cart_text() ); ?>
                </a>
            </div>

        </div>
        <?php
    }
}
