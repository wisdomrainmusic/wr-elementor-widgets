<?php
/**
 * Elementor Product Grid template.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( empty( $products ) || ! is_array( $products ) ) {
    return;
}
?>

<div class="wr-product-grid">
    <?php foreach ( $products as $product ) : ?>
        <div class="wr-product-card">

            <!-- Wishlist Button -->
            <div class="wr-wishlist-btn">
                <i class="far fa-heart"></i>
            </div>

            <div class="wr-product-image">
                <?php echo $product->get_image(); ?>
            </div>

            <div class="wr-product-content">
                <h3 class="wr-product-title"><?php echo esc_html( $product->get_name() ); ?></h3>
                <div class="wr-product-price"><?php echo wp_kses_post( $product->get_price_html() ); ?></div>

                <div class="wr-card-actions">
                    <a href="<?php echo esc_url( $product->add_to_cart_url() ); ?>"
                       class="wr-add-to-cart-btn"
                       data-product_id="<?php echo esc_attr( $product->get_id() ); ?>">
                        <?php esc_html_e( 'Add to cart', 'wr-elementor-widgets' ); ?>
                    </a>
                </div>

            </div>

        </div>
    <?php endforeach; ?>
</div>
