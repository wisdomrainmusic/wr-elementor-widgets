<?php
/**
 * Product loop content with wishlist integration.
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( empty( $product ) || ! $product->is_visible() ) {
    return;
}
?>
<li <?php wc_product_class( 'wr-product-item', $product ); ?>>
    <button class="wr-wishlist-btn" data-product-id="<?php echo esc_attr( get_the_ID() ); ?>">
        <svg class="wr-heart-icon" viewBox="0 0 24 24">
            <path d="M12 21s-6.5-4.35-9-8.5C1.03 9.02 2.1 5.6 4.9 4.4c1.8-.8 4-.3 5.1 1C11.1 4.1 13.3 3.6 15.1 4.4c2.8 1.2 3.87 4.62 1.9 8.1C18.5 16.65 12 21 12 21z" stroke-width="2" />
        </svg>
    </button>

    <?php
    /**
     * Hook: woocommerce_before_shop_loop_item.
     *
     * @hooked woocommerce_template_loop_product_link_open - 10
     */
    do_action( 'woocommerce_before_shop_loop_item' );

    /**
     * Hook: woocommerce_before_shop_loop_item_title.
     *
     * @hooked woocommerce_show_product_loop_sale_flash - 10
     * @hooked woocommerce_template_loop_product_thumbnail - 10
     */
    do_action( 'woocommerce_before_shop_loop_item_title' );

    /**
     * Hook: woocommerce_shop_loop_item_title.
     *
     * @hooked woocommerce_template_loop_product_title - 10
     */
    do_action( 'woocommerce_shop_loop_item_title' );

    /**
     * Hook: woocommerce_after_shop_loop_item_title.
     *
     * @hooked woocommerce_template_loop_rating - 5
     * @hooked woocommerce_template_loop_price - 10
     */
    do_action( 'woocommerce_after_shop_loop_item_title' );

    /**
     * Hook: woocommerce_after_shop_loop_item.
     *
     * @hooked woocommerce_template_loop_product_link_close - 5
     * @hooked woocommerce_template_loop_add_to_cart - 10
     */
    do_action( 'woocommerce_after_shop_loop_item' );
    ?>
</li>
