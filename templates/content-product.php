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

// Tek bir merkezden kart üretelim
include WR_EW_PLUGIN_DIR . 'widgets/product-grid/card.php';
