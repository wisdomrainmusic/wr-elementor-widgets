<?php
if ( ! defined( 'ABSPATH' ) ) exit;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;

class WR_EW_Product_Grid_Full extends Widget_Base {

    public function get_name() {
        return 'wr-product-grid-full';
    }

    public function get_title() {
        return __( 'WR Product Grid Full', 'wr-ew' );
    }

    public function get_icon() {
        return 'eicon-products';
    }

    public function get_categories() {
        return [ 'wr-widgets', 'wr-ecommerce-elements' ];
    }

    public function get_style_depends() {
        return [ 'wr-product-grid-full-css' ];
    }

    public function get_script_depends() {
        return [ 'wr-product-grid-full-js' ];
    }

    protected function register_controls() {
        $this->start_controls_section( 'section_content', [
            'label' => __( 'Content', 'wr-ew' ),
        ] );

        $this->add_control( 'columns', [
            'label'   => __( 'Desktop Columns', 'wr-ew' ),
            'type'    => Controls_Manager::SELECT,
            'options' => [
                '3' => '3',
                '4' => '4',
            ],
            'default' => '3',
        ] );

        $this->add_control( 'per_page', [
            'label'   => __( 'Products Per Page', 'wr-ew' ),
            'type'    => Controls_Manager::NUMBER,
            'default' => 9,
            'min'     => 1,
            'max'     => 24,
        ] );

        $this->add_control( 'button_text', [
            'label'   => __( 'Button Text', 'wr-ew' ),
            'type'    => Controls_Manager::TEXT,
            'default' => 'SEÇENEKLER',
        ] );

        $this->end_controls_section();

        // Wrapper background
        $this->start_controls_section( 'section_wrapper_style', [
            'label' => __( 'Wrapper', 'wr-ew' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_group_control( Group_Control_Background::get_type(), [
            'name'     => 'wrapper_bg',
            'types'    => [ 'classic', 'gradient' ],
            'selector' => '{{WRAPPER}} .wr-pg-full',
        ] );

        $this->end_controls_section();

        // Sidebar
        $this->start_controls_section( 'section_sidebar_style', [
            'label' => __( 'Sidebar', 'wr-ew' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_group_control( Group_Control_Background::get_type(), [
            'name'     => 'sidebar_bg',
            'types'    => [ 'classic', 'gradient' ],
            'selector' => '{{WRAPPER}} .wr-pg-full__sidebar',
        ] );

        $this->end_controls_section();

        // Categories
        $this->start_controls_section( 'section_categories_style', [
            'label' => __( 'Categories', 'wr-ew' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_group_control( Group_Control_Typography::get_type(), [
            'name'     => 'cat_typography',
            'selector' => '{{WRAPPER}} .wr-pg-full__cat, {{WRAPPER}} .wr-pg-full__more-toggle',
        ] );

        $this->add_responsive_control( 'cat_padding', [
            'label'      => __( 'Padding', 'wr-ew' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', 'em', '%' ],
            'selectors'  => [
                '{{WRAPPER}} .wr-pg-full' => '--wr-pg-full-cat-pad: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ] );

        $this->add_responsive_control( 'cat_radius', [
            'label' => __( 'Border Radius', 'wr-ew' ),
            'type'  => Controls_Manager::SLIDER,
            'range' => [ 'px' => [ 'min' => 0, 'max' => 40 ] ],
            'selectors' => [
                '{{WRAPPER}} .wr-pg-full' => '--wr-pg-full-cat-radius: {{SIZE}}{{UNIT}};',
            ],
        ] );

        $this->start_controls_tabs( 'tabs_cat_states' );

        $this->start_controls_tab( 'tab_cat_normal', [ 'label' => __( 'Normal', 'wr-ew' ) ] );
        $this->add_control( 'cat_color', [
            'label' => __( 'Text Color', 'wr-ew' ),
            'type'  => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .wr-pg-full' => '--wr-pg-full-cat-color: {{VALUE}};',
            ],
        ] );
        $this->add_control( 'cat_bg', [
            'label' => __( 'Background', 'wr-ew' ),
            'type'  => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .wr-pg-full' => '--wr-pg-full-cat-bg: {{VALUE}};',
            ],
        ] );
        $this->add_control( 'cat_border', [
            'label' => __( 'Border Color', 'wr-ew' ),
            'type'  => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .wr-pg-full' => '--wr-pg-full-cat-border: {{VALUE}};',
            ],
        ] );
        $this->end_controls_tab();

        $this->start_controls_tab( 'tab_cat_hover', [ 'label' => __( 'Hover', 'wr-ew' ) ] );
        $this->add_control( 'cat_color_hover', [
            'label' => __( 'Text Color', 'wr-ew' ),
            'type'  => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .wr-pg-full' => '--wr-pg-full-cat-color-hover: {{VALUE}};',
            ],
        ] );
        $this->add_control( 'cat_bg_hover', [
            'label' => __( 'Background', 'wr-ew' ),
            'type'  => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .wr-pg-full' => '--wr-pg-full-cat-bg-hover: {{VALUE}};',
            ],
        ] );
        $this->add_control( 'cat_border_hover', [
            'label' => __( 'Border Color', 'wr-ew' ),
            'type'  => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .wr-pg-full' => '--wr-pg-full-cat-border-hover: {{VALUE}};',
            ],
        ] );
        $this->end_controls_tab();

        $this->start_controls_tab( 'tab_cat_active', [ 'label' => __( 'Active', 'wr-ew' ) ] );
        $this->add_control( 'cat_color_active', [
            'label' => __( 'Text Color', 'wr-ew' ),
            'type'  => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .wr-pg-full' => '--wr-pg-full-cat-color-active: {{VALUE}};',
            ],
        ] );
        $this->add_control( 'cat_bg_active', [
            'label' => __( 'Background', 'wr-ew' ),
            'type'  => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .wr-pg-full' => '--wr-pg-full-cat-bg-active: {{VALUE}};',
            ],
        ] );
        $this->add_control( 'cat_border_active', [
            'label' => __( 'Border Color', 'wr-ew' ),
            'type'  => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .wr-pg-full' => '--wr-pg-full-cat-border-active: {{VALUE}};',
            ],
        ] );
        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        // Product title
        $this->start_controls_section( 'section_title_style', [
            'label' => __( 'Product Title', 'wr-ew' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_group_control( Group_Control_Typography::get_type(), [
            'name'     => 'title_typography',
            'selector' => '{{WRAPPER}} .wr-pg-full__title',
        ] );

        $this->add_control( 'title_color', [
            'label' => __( 'Color', 'wr-ew' ),
            'type'  => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .wr-pg-full' => '--wr-pg-full-title-color: {{VALUE}};',
            ],
        ] );

        $this->end_controls_section();

        // Price
        $this->start_controls_section( 'section_price_style', [
            'label' => __( 'Price', 'wr-ew' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_group_control( Group_Control_Typography::get_type(), [
            'name'     => 'price_typography',
            'selector' => '{{WRAPPER}} .wr-pg-full__price',
        ] );

        $this->add_control( 'price_color', [
            'label' => __( 'Color', 'wr-ew' ),
            'type'  => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .wr-pg-full' => '--wr-pg-full-price-color: {{VALUE}};',
            ],
        ] );

        $this->end_controls_section();

        // Button
        $this->start_controls_section( 'section_button_style', [
            'label' => __( 'Button', 'wr-ew' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_group_control( Group_Control_Typography::get_type(), [
            'name'     => 'btn_typography',
            'selector' => '{{WRAPPER}} .wr-pg-full__button',
        ] );

        $this->add_responsive_control( 'btn_padding', [
            'label'      => __( 'Padding', 'wr-ew' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', 'em', '%' ],
            'selectors'  => [
                '{{WRAPPER}} .wr-pg-full' => '--wr-pg-full-btn-pad: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ] );

        $this->add_control( 'btn_radius', [
            'label' => __( 'Border Radius', 'wr-ew' ),
            'type'  => Controls_Manager::SLIDER,
            'range' => [ 'px' => [ 'min' => 0, 'max' => 40 ] ],
            'selectors' => [
                '{{WRAPPER}} .wr-pg-full' => '--wr-pg-full-btn-radius: {{SIZE}}{{UNIT}};',
            ],
        ] );

        $this->start_controls_tabs( 'tabs_btn_states' );

        $this->start_controls_tab( 'tab_btn_normal', [ 'label' => __( 'Normal', 'wr-ew' ) ] );
        $this->add_control( 'btn_color', [
            'label' => __( 'Text Color', 'wr-ew' ),
            'type'  => Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .wr-pg-full' => '--wr-pg-full-btn-color: {{VALUE}};' ],
        ] );
        $this->add_control( 'btn_bg', [
            'label' => __( 'Background', 'wr-ew' ),
            'type'  => Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .wr-pg-full' => '--wr-pg-full-btn-bg: {{VALUE}};' ],
        ] );
        $this->add_group_control( Group_Control_Border::get_type(), [
            'name'     => 'btn_border',
            'selector' => '{{WRAPPER}} .wr-pg-full__button',
        ] );
        $this->end_controls_tab();

        $this->start_controls_tab( 'tab_btn_hover', [ 'label' => __( 'Hover', 'wr-ew' ) ] );
        $this->add_control( 'btn_color_hover', [
            'label' => __( 'Text Color', 'wr-ew' ),
            'type'  => Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .wr-pg-full' => '--wr-pg-full-btn-color-hover: {{VALUE}};' ],
        ] );
        $this->add_control( 'btn_bg_hover', [
            'label' => __( 'Background', 'wr-ew' ),
            'type'  => Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .wr-pg-full' => '--wr-pg-full-btn-bg-hover: {{VALUE}};' ],
        ] );
        $this->add_control( 'btn_border_hover', [
            'label' => __( 'Border Color', 'wr-ew' ),
            'type'  => Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .wr-pg-full' => '--wr-pg-full-btn-border-hover: {{VALUE}};' ],
        ] );
        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        // Pagination
        $this->start_controls_section( 'section_pagination_style', [
            'label' => __( 'Pagination', 'wr-ew' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_group_control( Group_Control_Typography::get_type(), [
            'name'     => 'page_typography',
            'selector' => '{{WRAPPER}} .wr-pg-full__page',
        ] );

        $this->add_responsive_control( 'page_gap', [
            'label' => __( 'Spacing', 'wr-ew' ),
            'type'  => Controls_Manager::SLIDER,
            'range' => [ 'px' => [ 'min' => 0, 'max' => 30 ] ],
            'selectors' => [
                '{{WRAPPER}} .wr-pg-full' => '--wr-pg-full-page-gap: {{SIZE}}{{UNIT}};',
            ],
        ] );

        $this->add_control( 'page_radius', [
            'label' => __( 'Border Radius', 'wr-ew' ),
            'type'  => Controls_Manager::SLIDER,
            'range' => [ 'px' => [ 'min' => 0, 'max' => 30 ] ],
            'selectors' => [
                '{{WRAPPER}} .wr-pg-full' => '--wr-pg-full-page-radius: {{SIZE}}{{UNIT}};',
            ],
        ] );

        $this->start_controls_tabs( 'tabs_page_states' );

        $this->start_controls_tab( 'tab_page_normal', [ 'label' => __( 'Normal', 'wr-ew' ) ] );
        $this->add_control( 'page_color', [
            'label' => __( 'Text Color', 'wr-ew' ),
            'type'  => Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .wr-pg-full' => '--wr-pg-full-page-color: {{VALUE}};' ],
        ] );
        $this->add_control( 'page_bg', [
            'label' => __( 'Background', 'wr-ew' ),
            'type'  => Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .wr-pg-full' => '--wr-pg-full-page-bg: {{VALUE}};' ],
        ] );
        $this->add_control( 'page_border', [
            'label' => __( 'Border Color', 'wr-ew' ),
            'type'  => Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .wr-pg-full' => '--wr-pg-full-page-border: {{VALUE}};' ],
        ] );
        $this->end_controls_tab();

        $this->start_controls_tab( 'tab_page_hover', [ 'label' => __( 'Hover', 'wr-ew' ) ] );
        $this->add_control( 'page_color_hover', [
            'label' => __( 'Text Color', 'wr-ew' ),
            'type'  => Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .wr-pg-full' => '--wr-pg-full-page-color-hover: {{VALUE}};' ],
        ] );
        $this->add_control( 'page_bg_hover', [
            'label' => __( 'Background', 'wr-ew' ),
            'type'  => Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .wr-pg-full' => '--wr-pg-full-page-bg-hover: {{VALUE}};' ],
        ] );
        $this->add_control( 'page_border_hover', [
            'label' => __( 'Border Color', 'wr-ew' ),
            'type'  => Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .wr-pg-full' => '--wr-pg-full-page-border-hover: {{VALUE}};' ],
        ] );
        $this->end_controls_tab();

        $this->start_controls_tab( 'tab_page_active', [ 'label' => __( 'Active', 'wr-ew' ) ] );
        $this->add_control( 'page_color_active', [
            'label' => __( 'Text Color', 'wr-ew' ),
            'type'  => Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .wr-pg-full' => '--wr-pg-full-page-color-active: {{VALUE}};' ],
        ] );
        $this->add_control( 'page_bg_active', [
            'label' => __( 'Background', 'wr-ew' ),
            'type'  => Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .wr-pg-full' => '--wr-pg-full-page-bg-active: {{VALUE}};' ],
        ] );
        $this->add_control( 'page_border_active', [
            'label' => __( 'Border Color', 'wr-ew' ),
            'type'  => Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .wr-pg-full' => '--wr-pg-full-page-border-active: {{VALUE}};' ],
        ] );
        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    protected function render() {
        $settings    = $this->get_settings_for_display();
        $columns     = in_array( $settings['columns'] ?? '3', [ '3', '4' ], true ) ? $settings['columns'] : '3';
        $per_page    = ! empty( $settings['per_page'] ) ? max( 1, (int) $settings['per_page'] ) : 9;
        $button_text = ! empty( $settings['button_text'] ) ? $settings['button_text'] : 'SEÇENEKLER';

        if ( ! function_exists( 'wc_get_product' ) ) {
            echo '<div class="wr-pg-full__empty">' . esc_html__( 'WooCommerce is required for this widget.', 'wr-ew' ) . '</div>';
            return;
        }

        $terms = get_terms(
            [
                'taxonomy'   => 'product_cat',
                'hide_empty' => true,
                'number'     => 0,
            ]
        );

        if ( is_wp_error( $terms ) ) {
            $terms = [];
        }

        $visible_terms = array_slice( $terms, 0, 12 );
        $more_terms    = array_slice( $terms, 12 );

        $query_args = [
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'posts_per_page' => $per_page,
            'paged'          => 1,
        ];

        $query = new WP_Query( $query_args );

        $wrapper_classes = [ 'wr-pg-full', 'is-cols-' . $columns ];
        ?>
        <div class="<?php echo esc_attr( implode( ' ', $wrapper_classes ) ); ?>" data-per-page="<?php echo esc_attr( $per_page ); ?>" data-columns="<?php echo esc_attr( $columns ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'wr_pg_full_nonce' ) ); ?>" data-active-cat="0" data-btn-text="<?php echo esc_attr( $button_text ); ?>">
            <div class="wr-pg-full__sidebar">
                <button class="wr-pg-full__sidebar-toggle" type="button"><?php esc_html_e( 'Categories', 'wr-ew' ); ?></button>

                <div class="wr-pg-full__cats-wrap">
                    <div class="wr-pg-full__cats">
                        <button class="wr-pg-full__cat is-active" data-cat="0" type="button"><?php esc_html_e( 'All', 'wr-ew' ); ?></button>
                        <?php foreach ( $visible_terms as $term ) : ?>
                            <button class="wr-pg-full__cat" data-cat="<?php echo esc_attr( $term->term_id ); ?>" type="button"><?php echo esc_html( $term->name ); ?></button>
                        <?php endforeach; ?>
                    </div>

                    <?php if ( ! empty( $more_terms ) ) : ?>
                        <button class="wr-pg-full__more-toggle" type="button"><?php esc_html_e( 'More', 'wr-ew' ); ?></button>
                        <div class="wr-pg-full__cats-hidden">
                            <?php foreach ( $more_terms as $term ) : ?>
                                <button class="wr-pg-full__cat" data-cat="<?php echo esc_attr( $term->term_id ); ?>" type="button"><?php echo esc_html( $term->name ); ?></button>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="wr-pg-full__content">
                <div class="wr-pg-full__grid">
                    <div class="wr-pg-full__grid-inner">
                        <?php wr_pg_full_render_products( $query, $button_text ); ?>
                    </div>
                </div>
                <div class="wr-pg-full__pagination">
                    <?php wr_pg_full_render_pagination( $query ); ?>
                </div>
            </div>
        </div>
        <?php

        wp_reset_postdata();
    }
}
