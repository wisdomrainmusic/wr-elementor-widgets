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
        <?php
        global $product;
        $wr_card_context = 'elementor-grid';
        include WR_EW_PLUGIN_DIR . 'widgets/product-grid/card.php';
        ?>
    <?php endforeach; ?>
</div>
