<?php
if ( ! defined( 'ABSPATH' ) ) exit;

global $product;
?>

<div class="wr-product-card">

    <button
        class="wr-wishlist-btn"
        data-product-id="<?php echo (int) $product->get_id(); ?>"
    >
        <svg class="wr-heart-icon" viewBox="0 0 24 24">
            <path d="M12 21s-6-4.35-10-9c-2-2.48-2-6.5 1-8.5 2.5-1.6 6-.5 7.5 2C12 3 15.5 2 18 3.5c3 2 3 6.02 1 8.5-4 4.65-10 9-10 9z"/>
        </svg>
    </button>

    <div class="wr-product-image">
        <?php echo $product->get_image(); ?>
    </div>

    <h3 class="wr-product-title"><?php the_title(); ?></h3>

    <div class="wr-product-price">
        <?php echo $product->get_price_html(); ?>
    </div>

    <div class="wr-card-actions">
        <a href="<?php echo esc_url( $product->add_to_cart_url() ); ?>"
            class="wr-add-to-cart-btn"
            data-product_id="<?php echo $product->get_id(); ?>">
            Add to cart
        </a>
    </div>

</div>
