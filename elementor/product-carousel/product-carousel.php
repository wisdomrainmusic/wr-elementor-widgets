<?php
if ( ! defined( 'ABSPATH' ) ) exit;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;

class WR_Product_Carousel extends Widget_Base {

    public function get_name() {
        return 'wr-product-carousel';
    }

    public function get_title() {
        return __( 'WR Product Carousel', 'wr-ecom' );
    }

    public function get_icon() {
        return 'eicon-slider-album';
    }

    public function get_categories() {
        return [ 'wr-ecommerce-elements' ];
    }

    public function get_script_depends() {
        return [ 'wr-swiper' ];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __( 'Content', 'wr-ecom' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'posts_per_page',
            [
                'label'   => __( 'Products Count', 'wr-ecom' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 10,
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        include __DIR__ . '/templates/product-carousel-view.php';
    }
}
