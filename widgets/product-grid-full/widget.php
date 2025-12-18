<?php
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

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
        return [ 'wr-ecommerce-elements' ];
    }

    public function get_style_depends() {
        return [ 'wr-product-grid-full-css' ];
    }

    public function get_script_depends() {
        return [ 'wr-product-grid-full-js' ];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __( 'Content', 'wr-ew' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'columns',
            [
                'label'   => __( 'Columns (Desktop)', 'wr-ew' ),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    3 => __( '3 Columns', 'wr-ew' ),
                    4 => __( '4 Columns', 'wr-ew' ),
                ],
                'default' => 4,
            ]
        );

        $this->add_control(
            'per_page',
            [
                'label'   => __( 'Products Per Page', 'wr-ew' ),
                'type'    => Controls_Manager::NUMBER,
                'min'     => 1,
                'max'     => 48,
                'default' => 12,
            ]
        );

        $this->add_control(
            'show_price',
            [
                'label'        => __( 'Show Prices', 'wr-ew' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'Show', 'wr-ew' ),
                'label_off'    => __( 'Hide', 'wr-ew' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label'       => __( 'Button Text', 'wr-ew' ),
                'type'        => Controls_Manager::TEXT,
                'placeholder' => __( 'SEÇENEKLER', 'wr-ew' ),
                'default'     => __( 'SEÇENEKLER', 'wr-ew' ),
            ]
        );

        $this->end_controls_section();

        /**
         * STYLE: Wrapper
         */
        $this->start_controls_section(
            'section_style_wrapper',
            [
                'label' => __( 'Wrapper', 'wr-ew' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'wrapper_background',
                'label'    => __( 'Background', 'wr-ew' ),
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .wr-pg-full',
            ]
        );

        $this->end_controls_section();

        /**
         * STYLE: Sidebar
         */
        $this->start_controls_section(
            'section_style_sidebar',
            [
                'label' => __( 'Sidebar', 'wr-ew' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'sidebar_background',
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .wr-pg-full__sidebar',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'category_typography',
                'selector' => '{{WRAPPER}} .wr-pg-full__cat',
            ]
        );

        $this->add_control(
            'category_color',
            [
                'label'     => __( 'Category Color', 'wr-ew' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-pg-full__cat' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'category_hover_color',
            [
                'label'     => __( 'Category Hover Color', 'wr-ew' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-pg-full__cat:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'category_active_color',
            [
                'label'     => __( 'Category Active Color', 'wr-ew' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-pg-full__cat.is-active' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * STYLE: Product Title
         */
        $this->start_controls_section(
            'section_style_title',
            [
                'label' => __( 'Product Title', 'wr-ew' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} .wr-pg-full__title',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label'     => __( 'Color', 'wr-ew' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-pg-full__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * STYLE: Price
         */
        $this->start_controls_section(
            'section_style_price',
            [
                'label' => __( 'Price', 'wr-ew' ),
                'tab'   => Controls_Manager::TAB_STYLE,
                'condition' => [ 'show_price' => 'yes' ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'price_typography',
                'selector' => '{{WRAPPER}} .wr-pg-full__price',
            ]
        );

        $this->add_control(
            'price_color',
            [
                'label'     => __( 'Color', 'wr-ew' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-pg-full__price' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * STYLE: Button
         */
        $this->start_controls_section(
            'section_style_button',
            [
                'label' => __( 'Button', 'wr-ew' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'button_typography',
                'selector' => '{{WRAPPER}} .wr-pg-full__btn',
            ]
        );

        $this->start_controls_tabs( 'tabs_button_style' );

        $this->start_controls_tab(
            'tab_button_normal',
            [ 'label' => __( 'Normal', 'wr-ew' ) ]
        );

        $this->add_control(
            'button_text_color',
            [
                'label'     => __( 'Text Color', 'wr-ew' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-pg-full__btn' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_bg_color',
            [
                'label'     => __( 'Background Color', 'wr-ew' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-pg-full__btn' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'button_border',
                'selector' => '{{WRAPPER}} .wr-pg-full__btn',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'button_box_shadow',
                'selector' => '{{WRAPPER}} .wr-pg-full__btn',
            ]
        );

        $this->add_responsive_control(
            'button_padding',
            [
                'label'      => __( 'Padding', 'wr-ew' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .wr-pg-full__btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'button_border_radius',
            [
                'label'      => __( 'Border Radius', 'wr-ew' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .wr-pg-full__btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_button_hover',
            [ 'label' => __( 'Hover', 'wr-ew' ) ]
        );

        $this->add_control(
            'button_text_color_hover',
            [
                'label'     => __( 'Text Color', 'wr-ew' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-pg-full__btn:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_bg_color_hover',
            [
                'label'     => __( 'Background Color', 'wr-ew' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-pg-full__btn:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_border_color_hover',
            [
                'label'     => __( 'Border Color', 'wr-ew' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-pg-full__btn:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();

        /**
         * STYLE: Pagination
         */
        $this->start_controls_section(
            'section_style_pagination',
            [
                'label' => __( 'Pagination', 'wr-ew' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'pagination_typography',
                'selector' => '{{WRAPPER}} .wr-pg-full__page',
            ]
        );

        $this->add_responsive_control(
            'pagination_spacing',
            [
                'label'      => __( 'Spacing', 'wr-ew' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => 0, 'max' => 32 ] ],
                'selectors'  => [
                    '{{WRAPPER}} .wr-pg-full__pagination-inner' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs( 'tabs_pagination_style' );

        $this->start_controls_tab(
            'tab_pagination_normal',
            [ 'label' => __( 'Normal', 'wr-ew' ) ]
        );

        $this->add_control(
            'pagination_text_color',
            [
                'label'     => __( 'Text Color', 'wr-ew' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-pg-full__page' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'pagination_bg_color',
            [
                'label'     => __( 'Background Color', 'wr-ew' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-pg-full__page' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'pagination_border',
                'selector' => '{{WRAPPER}} .wr-pg-full__page',
            ]
        );

        $this->add_control(
            'pagination_radius',
            [
                'label'      => __( 'Border Radius', 'wr-ew' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .wr-pg-full__page' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'pagination_padding',
            [
                'label'      => __( 'Padding', 'wr-ew' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .wr-pg-full__page' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_pagination_hover',
            [ 'label' => __( 'Hover', 'wr-ew' ) ]
        );

        $this->add_control(
            'pagination_text_color_hover',
            [
                'label'     => __( 'Text Color', 'wr-ew' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-pg-full__page:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'pagination_bg_color_hover',
            [
                'label'     => __( 'Background Color', 'wr-ew' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-pg-full__page:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'pagination_border_color_hover',
            [
                'label'     => __( 'Border Color', 'wr-ew' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-pg-full__page:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_pagination_active',
            [ 'label' => __( 'Active', 'wr-ew' ) ]
        );

        $this->add_control(
            'pagination_text_color_active',
            [
                'label'     => __( 'Text Color', 'wr-ew' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-pg-full__page.is-active' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'pagination_bg_color_active',
            [
                'label'     => __( 'Background Color', 'wr-ew' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-pg-full__page.is-active' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'pagination_border_color_active',
            [
                'label'     => __( 'Border Color', 'wr-ew' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-pg-full__page.is-active' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    protected function render_sidebar_categories( $active_cat = 0 ) {
        $categories = get_terms(
            [
                'taxonomy'   => 'product_cat',
                'hide_empty' => true,
                'orderby'    => 'name',
                'order'      => 'ASC',
            ]
        );

        if ( is_wp_error( $categories ) ) {
            return;
        }

        $visible   = array_slice( $categories, 0, 12 );
        $remaining = array_slice( $categories, 12 );
        ?>
        <button class="wr-pg-full__sidebar-toggle" type="button"><?php esc_html_e( 'Categories', 'wr-ew' ); ?></button>

        <div class="wr-pg-full__sidebar-inner">
            <button class="wr-pg-full__cat<?php echo 0 === (int) $active_cat ? ' is-active' : ''; ?>" data-cat="0" type="button">
                <?php esc_html_e( 'All', 'wr-ew' ); ?>
            </button>

            <?php foreach ( $visible as $term ) : ?>
                <button class="wr-pg-full__cat<?php echo (int) $active_cat === (int) $term->term_id ? ' is-active' : ''; ?>" data-cat="<?php echo esc_attr( $term->term_id ); ?>" type="button">
                    <?php echo esc_html( $term->name ); ?>
                </button>
            <?php endforeach; ?>

            <?php if ( ! empty( $remaining ) ) : ?>
                <div class="wr-pg-full__more">
                    <button class="wr-pg-full__more-toggle" type="button"><?php esc_html_e( 'More', 'wr-ew' ); ?></button>
                    <div class="wr-pg-full__more-list" hidden>
                        <?php foreach ( $remaining as $term ) : ?>
                            <button class="wr-pg-full__cat<?php echo (int) $active_cat === (int) $term->term_id ? ' is-active' : ''; ?>" data-cat="<?php echo esc_attr( $term->term_id ); ?>" type="button">
                                <?php echo esc_html( $term->name ); ?>
                            </button>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }

    protected function render_products( $settings ) {
        if ( ! function_exists( 'wc_get_product' ) || ! class_exists( 'WC_Product' ) ) {
            return [
                'items'      => '<div class="wr-pg-full__empty">WooCommerce is not active.</div>',
                'pagination' => '',
            ];
        }

        $page       = 1;
        $per_page   = max( 1, (int) ( $settings['per_page'] ?? 12 ) );
        $show_price = ( ! empty( $settings['show_price'] ) && 'yes' === $settings['show_price'] );
        $button_txt = ! empty( $settings['button_text'] ) ? $settings['button_text'] : __( 'SEÇENEKLER', 'wr-ew' );

        $query = new WP_Query(
            [
                'post_type'      => 'product',
                'post_status'    => 'publish',
                'posts_per_page' => $per_page,
                'paged'          => $page,
            ]
        );

        ob_start();
        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post();
                $product = wc_get_product( get_the_ID() );
                wr_pg_full_render_product_card( $product, [
                    'show_price'  => $show_price,
                    'button_text' => $button_txt,
                ] );
            }
        } else {
            echo '<div class="wr-pg-full__empty">' . esc_html__( 'No products found.', 'wr-ew' ) . '</div>';
        }
        $html = ob_get_clean();

        $pagination = wr_pg_full_render_pagination( $page, (int) $query->max_num_pages );

        wp_reset_postdata();

        return [
            'items'      => $html,
            'pagination' => $pagination,
        ];
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $columns    = ( 3 === (int) $settings['columns'] ) ? 3 : 4;
        $per_page   = max( 1, (int) $settings['per_page'] );
        $show_price = ( ! empty( $settings['show_price'] ) && 'yes' === $settings['show_price'] );
        $button_txt = ! empty( $settings['button_text'] ) ? $settings['button_text'] : __( 'SEÇENEKLER', 'wr-ew' );

        $data = $this->render_products( $settings );
        ?>
        <div class="wr-pg-full is-cols-<?php echo esc_attr( $columns ); ?>" data-per-page="<?php echo esc_attr( $per_page ); ?>" data-columns="<?php echo esc_attr( $columns ); ?>" data-show-price="<?php echo esc_attr( $show_price ? 'yes' : 'no' ); ?>" data-button-text="<?php echo esc_attr( $button_txt ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'wr_pg_full_nonce' ) ); ?>" data-widget-id="<?php echo esc_attr( $this->get_id() ); ?>">
            <aside class="wr-pg-full__sidebar">
                <?php $this->render_sidebar_categories(); ?>
            </aside>

            <div class="wr-pg-full__content">
                <div class="wr-pg-full__grid">
                    <?php echo $data['items']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                </div>

                <div class="wr-pg-full__pagination">
                    <?php echo $data['pagination']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                </div>
            </div>
        </div>
        <?php
    }
}
