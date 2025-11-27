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
        $per_page   = 12;
        $paged      = 1;

        $categories = get_terms( [
            'taxonomy'   => 'product_cat',
            'hide_empty' => false,
        ] );

        echo '<div class="wr-product-grid-wrapper" data-columns="' . esc_attr( $columns ) . '">';

        // Sidebar
        echo '<aside class="wr-filter-sidebar">';
        echo '<div class="wr-filter-header">' . esc_html__( 'Categories', 'wr-elementor-widgets' ) . '</div>';
        echo '<ul>';
        if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
            foreach ( $categories as $cat ) {
                echo '<li data-cat="' . esc_attr( $cat->term_id ) . '">' . esc_html( $cat->name ) . '</li>';
            }
        }
        echo '</ul>';
        echo '</aside>';

        // Product grid
        echo '<div class="wr-product-items" data-page="1" data-per-page="' . esc_attr( $per_page ) . '">';

        $args = [
            'post_type'      => 'product',
            'posts_per_page' => $per_page,
            'paged'          => $paged,
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
                echo '<button class="wr-wishlist-btn" data-id="' . get_the_ID() . '">';
                echo '<svg class="wr-heart-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 21s-5.052-3.21-8.106-6.264C2.108 13.95 1 12.486 1 10.75 1 8.678 2.678 7 4.75 7c1.264 0 2.493.593 3.25 1.528C8.757 7.593 9.986 7 11.25 7 13.322 7 15 8.678 15 10.75c0 1.736-1.108 3.2-2.894 3.986C13.052 17.79 12 21 12 21z"/></svg>';
                echo '</button>';
                echo '<a href="' . esc_url( $product_url ) . '" class="wr-product-link">';
                echo '<img src="' . esc_url( $image_url ) . '" alt="' . esc_attr( get_the_title() ) . '">';
                echo '<h3>' . esc_html( get_the_title() ) . '</h3>';
                echo '<span class="price">' . wp_kses_post( $product->get_price_html() ) . '</span>';
                echo '</a>';

                if ( $product && $product->is_purchasable() && $product->is_in_stock() ) {
                    echo sprintf(
                        '<a href="%1$s" data-quantity="1" class="wr-add-to-cart button add_to_cart_button ajax_add_to_cart" data-product_id="%2$s" data-product_sku="%3$s" rel="nofollow">%4$s</a>',
                        esc_url( $product->add_to_cart_url() ),
                        esc_attr( $product->get_id() ),
                        esc_attr( $product->get_sku() ),
                        esc_html( $product->add_to_cart_text() )
                    );
                }

                echo '</div>';
            }
            wp_reset_postdata();
        }

        // Pagination
        $total_pages = $query->max_num_pages;
        if ( $total_pages > 1 ) {
            echo '<div class="wr-product-pagination">';
            for ( $i = 1; $i <= $total_pages; $i++ ) {
                $active = ( $i === $paged ) ? ' active' : '';
                echo '<button type="button" class="wr-page-btn' . $active . '" data-page="' . esc_attr( $i ) . '">' . esc_html( $i ) . '</button>';
            }
            if ( $paged < $total_pages ) {
                echo '<button type="button" class="wr-page-btn wr-page-next" data-page="' . esc_attr( $paged + 1 ) . '">&rarr;</button>';
            }
            echo '</div>';
        }

        echo '</div>'; // .wr-product-items
        echo '</div>'; // .wr-product-grid-wrapper
    }
}
