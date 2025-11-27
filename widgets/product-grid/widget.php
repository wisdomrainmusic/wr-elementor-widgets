<?php
if ( ! defined( 'ABSPATH' ) ) exit;

use Elementor\Widget_Base;

class WR_EW_Product_Grid extends Widget_Base {

    public function get_name() {
        return 'wr-product-grid';
    }

    public function get_title() {
        return 'WR Product Grid';
    }

    public function get_icon() {
        return 'eicon-products';
    }

    public function get_categories() {
        return [ 'wr-widgets' ];
    }

    protected function render() {
        echo '<div class="wr-product-grid-test">';
        echo '<div class="wr-product-grid-sidebar">Sidebar Test</div>';
        echo '<div class="wr-product-grid-items">Grid Test</div>';
        echo '</div>';
    }
}
