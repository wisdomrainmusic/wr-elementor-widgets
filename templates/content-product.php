<?php
/**
 * WR Custom Product Card Template
 * This replaces the default WooCommerce content-product.php
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

global $product;

if ( empty( $product ) || ! $product->is_visible() ) {
    return;
}

wr_render_product_card( $product, 'grid' );
