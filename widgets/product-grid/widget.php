<?php
if ( ! defined( 'ABSPATH' ) ) exit;

use Elementor\Controls_Manager;
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

    protected function register_controls() {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __( 'Content', 'wr-elementor-widgets' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'columns',
            [
                'label'   => __( 'Columns', 'wr-elementor-widgets' ),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    '2' => __( '2', 'wr-elementor-widgets' ),
                    '3' => __( '3', 'wr-elementor-widgets' ),
                    '4' => __( '4', 'wr-elementor-widgets' ),
                ],
                'default' => '3',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings   = $this->get_settings_for_display();
        $columns    = ! empty( $settings['columns'] ) ? $settings['columns'] : '3';
        $categories = get_terms( 'product_cat' );

        echo '<div class="wr-product-grid">';

        echo '<aside class="wr-filter-sidebar">';
        echo '<ul>';

        if ( ! is_wp_error( $categories ) && ! empty( $categories ) ) {
            foreach ( $categories as $category ) {
                printf(
                    '<li data-cat="%1$s">%2$s</li>',
                    esc_attr( $category->term_id ),
                    esc_html( $category->name )
                );
            }
        }

        echo '</ul>';
        echo '</aside>';

        printf( '<div class="wr-product-items" data-columns="%s">', esc_attr( $columns ) );
        echo '<p>Grid Products Placeholder</p>';
        echo '</div>';

        echo '</div>';
    }
}
