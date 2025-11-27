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
        $settings = $this->get_settings_for_display();
        $columns  = ! empty( $settings['columns'] ) ? $settings['columns'] : '3';

        $categories = get_terms( [
            'taxonomy'   => 'product_cat',
            'hide_empty' => false,
        ] );

        echo '<div class="wr-product-grid-wrapper" data-columns="' . esc_attr( $columns ) . '">';

        echo '<aside class="wr-filter-sidebar"><ul>';
        if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
            foreach ( $categories as $cat ) {
                echo '<li data-cat="' . esc_attr( $cat->term_id ) . '">' . esc_html( $cat->name ) . '</li>';
            }
        }
        echo '</ul></aside>';

        echo '<div class="wr-product-items">';

        $args = [
            'post_type'      => 'product',
            'posts_per_page' => 12,
        ];

        $query = new WP_Query( $args );

        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post();

                $product     = wc_get_product( get_the_ID() );
                $image_url   = get_the_post_thumbnail_url( get_the_ID(), 'medium' );
                $image_url   = $image_url ? $image_url : wc_placeholder_img_src();
                $product_url = get_permalink();

                echo '<div class="wr-product-item">';
                echo '<a href="' . esc_url( $product_url ) . '">';
                echo '<img src="' . esc_url( $image_url ) . '" alt="' . esc_attr( get_the_title() ) . '">';
                echo '<h3>' . esc_html( get_the_title() ) . '</h3>';
                echo '<span class="price">' . wp_kses_post( $product->get_price_html() ) . '</span>';
                echo '</a>';
                echo '</div>';
            }
            wp_reset_postdata();
        }

        echo '</div>';
        echo '</div>';
    }
}
