<?php
if ( ! defined( 'ABSPATH' ) ) exit;

global $product;

$wr_card_context = isset( $wr_card_context ) ? $wr_card_context : 'carousel';
wr_render_product_card( $product, $wr_card_context );
