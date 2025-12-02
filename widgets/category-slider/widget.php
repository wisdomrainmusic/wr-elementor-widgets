<?php
/**
 * WR Category Slider Widget
 *
 * @package WR_Elementor_Widgets
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;

class WR_EW_Category_Slider extends Widget_Base {

    public function get_name() {
        return 'wr_category_slider';
    }

    public function get_title() {
        return __( 'WR Category Slider', 'wr-ew' );
    }

    public function get_icon() {
        return 'eicon-slider-push';
    }

    public function get_categories() {
        // Eski yapıya göre kendi kategorimiz:
        return [ 'wr-widgets', 'wr-ecommerce-elements' ];
    }

    public function get_style_depends() {
        return [ 'wr-category-slider-css' ];
    }

    public function get_script_depends() {
        return [ 'wr-swiper', 'wr-category-slider-js' ];
    }

    /**
     * Helper: WooCommerce product_cat terms
     */
    protected function get_product_cat_options() {
        if ( ! taxonomy_exists( 'product_cat' ) ) {
            return [];
        }

        $terms = get_terms(
            [
                'taxonomy'   => 'product_cat',
                'hide_empty' => false,
            ]
        );

        if ( is_wp_error( $terms ) || empty( $terms ) ) {
            return [];
        }

        $options = [];
        foreach ( $terms as $term ) {
            $options[ $term->term_id ] = $term->name;
        }

        return $options;
    }

    protected function register_controls() {

        /**
         * CONTENT TAB: Query
         */
        $this->start_controls_section(
            'section_query',
            [
                'label' => __( 'Query', 'wr-ew' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'source',
            [
                'label'   => __( 'Source', 'wr-ew' ),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'all'      => __( 'All Categories', 'wr-ew' ),
                    'selected' => __( 'Selected Only', 'wr-ew' ),
                ],
                'default' => 'all',
            ]
        );

        $this->add_control(
            'categories',
            [
                'label'       => __( 'Categories', 'wr-ew' ),
                'type'        => Controls_Manager::SELECT2,
                'multiple'    => true,
                'options'     => $this->get_product_cat_options(),
                'label_block' => true,
                'condition'   => [
                    'source' => 'selected',
                ],
            ]
        );

        $this->add_control(
            'hide_empty',
            [
                'label'        => __( 'Hide Empty', 'wr-ew' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'Yes', 'wr-ew' ),
                'label_off'    => __( 'No', 'wr-ew' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'show_product_count',
            [
                'label'        => __( 'Show Product Count', 'wr-ew' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'Yes', 'wr-ew' ),
                'label_off'    => __( 'No', 'wr-ew' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'items_limit',
            [
                'label'   => __( 'Max Categories', 'wr-ew' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 8,
                'min'     => 1,
                'max'     => 50,
            ]
        );

        $this->add_control(
            'orderby',
            [
                'label'   => __( 'Order By', 'wr-ew' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'name',
                'options' => [
                    'name'       => __( 'Name', 'wr-ew' ),
                    'slug'       => __( 'Slug', 'wr-ew' ),
                    'count'      => __( 'Product Count', 'wr-ew' ),
                    'term_id'    => __( 'Term ID', 'wr-ew' ),
                    'menu_order' => __( 'Menu Order', 'wr-ew' ),
                ],
            ]
        );

        $this->add_control(
            'order',
            [
                'label'   => __( 'Order', 'wr-ew' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'ASC',
                'options' => [
                    'ASC'  => __( 'ASC', 'wr-ew' ),
                    'DESC' => __( 'DESC', 'wr-ew' ),
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * CONTENT TAB: Slider
         */
        $this->start_controls_section(
            'section_slider',
            [
                'label' => __( 'Slider', 'wr-ew' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_responsive_control(
            'slides_per_view',
            [
                'label'          => __( 'Slides Per View', 'wr-ew' ),
                'type'           => Controls_Manager::NUMBER,
                'default'        => 4,
                'tablet_default' => 3,
                'mobile_default' => 2,
                'min'            => 1,
                'max'            => 6,
            ]
        );

        $this->add_control(
            'space_between',
            [
                'label'   => __( 'Space Between (px)', 'wr-ew' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 24,
                'min'     => 0,
                'max'     => 80,
            ]
        );

        $this->add_control(
            'autoplay',
            [
                'label'        => __( 'Autoplay', 'wr-ew' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'Yes', 'wr-ew' ),
                'label_off'    => __( 'No', 'wr-ew' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'autoplay_delay',
            [
                'label'     => __( 'Autoplay Delay (ms)', 'wr-ew' ),
                'type'      => Controls_Manager::NUMBER,
                'default'   => 4000,
                'min'       => 1000,
                'max'       => 15000,
                'step'      => 500,
                'condition' => [
                    'autoplay' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'show_arrows',
            [
                'label'        => __( 'Navigation Arrows', 'wr-ew' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'Show', 'wr-ew' ),
                'label_off'    => __( 'Hide', 'wr-ew' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'show_dots',
            [
                'label'        => __( 'Pagination Dots', 'wr-ew' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'Show', 'wr-ew' ),
                'label_off'    => __( 'Hide', 'wr-ew' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->end_controls_section();

        /**
         * STYLE TAB: Card
         */
        $this->start_controls_section(
            'section_style_card',
            [
                'label' => __( 'Card', 'wr-ew' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'card_bg',
                'label'    => __( 'Background', 'wr-ew' ),
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .wr-category-card',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'card_border',
                'selector' => '{{WRAPPER}} .wr-category-card',
            ]
        );

        $this->add_control(
            'card_radius',
            [
                'label'      => __( 'Border Radius', 'wr-ew' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 40,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .wr-category-card' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'card_shadow',
                'selector' => '{{WRAPPER}} .wr-category-card',
            ]
        );

        $this->add_responsive_control(
            'card_padding',
            [
                'label'      => __( 'Padding', 'wr-ew' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors'  => [
                    '{{WRAPPER}} .wr-category-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * STYLE TAB: Text
         */
        $this->start_controls_section(
            'section_style_text',
            [
                'label' => __( 'Text', 'wr-ew' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label'     => __( 'Title Color', 'wr-ew' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-category-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} .wr-category-title',
            ]
        );

        $this->add_control(
            'count_color',
            [
                'label'     => __( 'Count Color', 'wr-ew' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-category-count' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'count_typography',
                'selector' => '{{WRAPPER}} .wr-category-count',
            ]
        );

        $this->end_controls_section();
    }

    protected function get_terms_from_settings( $settings ) {
        if ( ! taxonomy_exists( 'product_cat' ) ) {
            return [];
        }

        $args = [
            'taxonomy'   => 'product_cat',
            'hide_empty' => ( isset( $settings['hide_empty'] ) && 'yes' === $settings['hide_empty'] ),
            'number'     => ! empty( $settings['items_limit'] ) ? absint( $settings['items_limit'] ) : 0,
            'orderby'    => $settings['orderby'] ?? 'name',
            'order'      => $settings['order'] ?? 'ASC',
        ];

        if ( 'selected' === ( $settings['source'] ?? 'all' ) && ! empty( $settings['categories'] ) ) {
            $args['include'] = array_map( 'intval', (array) $settings['categories'] );
        }

        $terms = get_terms( $args );

        if ( is_wp_error( $terms ) || empty( $terms ) ) {
            return [];
        }

        return $terms;
    }

    protected function render_category_card( $term, $settings ) {

        $thumbnail_id = get_term_meta( $term->term_id, 'thumbnail_id', true );
        $image_src    = $thumbnail_id ? wp_get_attachment_image_src( $thumbnail_id, 'medium_large' ) : false;

        $show_count = ( isset( $settings['show_product_count'] ) && 'yes' === $settings['show_product_count'] );
        ?>
        <div class="swiper-slide">
            <div class="wr-category-card">
                <a href="<?php echo esc_url( get_term_link( $term, 'product_cat' ) ); ?>" class="wr-category-link">
                    <div class="wr-category-thumb">
                        <?php if ( $image_src ) : ?>
                            <img src="<?php echo esc_url( $image_src[0] ); ?>"
                                 alt="<?php echo esc_attr( $term->name ); ?>">
                        <?php else : ?>
                            <span class="wr-category-badge">
                                <?php esc_html_e( 'No Image', 'wr-ew' ); ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <h3 class="wr-category-title">
                        <?php echo esc_html( $term->name ); ?>
                    </h3>

                    <?php if ( $show_count ) : ?>
                        <div class="wr-category-count">
                            <?php
                            printf(
                                /* translators: %s: product count */
                                esc_html( _n( '%s product', '%s products', $term->count, 'wr-ew' ) ),
                                '<strong>' . esc_html( number_format_i18n( $term->count ) ) . '</strong>'
                            );
                            ?>
                        </div>
                    <?php endif; ?>
                </a>
            </div>
        </div>
        <?php
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $terms = $this->get_terms_from_settings( $settings );
        if ( empty( $terms ) ) {
            echo '<div class="wr-category-slider-empty">';
            esc_html_e( 'No categories found.', 'wr-ew' );
            echo '</div>';
            return;
        }

        $slides_desktop = ! empty( $settings['slides_per_view'] ) ? (int) $settings['slides_per_view'] : 4;
        $slides_tablet  = ! empty( $settings['slides_per_view_tablet'] ) ? (int) $settings['slides_per_view_tablet'] : 3;
        $slides_mobile  = ! empty( $settings['slides_per_view_mobile'] ) ? (int) $settings['slides_per_view_mobile'] : 2;
        $space_between  = ! empty( $settings['space_between'] ) ? (int) $settings['space_between'] : 24;

        $this->add_render_attribute(
            'slider',
            [
                'class'               => 'wr-category-slider swiper',
                'data-slides-desktop' => $slides_desktop,
                'data-slides-tablet'  => $slides_tablet,
                'data-slides-mobile'  => $slides_mobile,
                'data-space-between'  => $space_between,
                'data-autoplay'       => ( isset( $settings['autoplay'] ) && 'yes' === $settings['autoplay'] ) ? 'yes' : 'no',
                'data-autoplay-delay' => ! empty( $settings['autoplay_delay'] ) ? (int) $settings['autoplay_delay'] : 4000,
            ]
        );
        ?>
        <div class="wr-category-slider-wrapper">
            <div <?php echo $this->get_render_attribute_string( 'slider' ); ?>>
                <div class="swiper-wrapper">
                    <?php
                    foreach ( $terms as $term ) {
                        $this->render_category_card( $term, $settings );
                    }
                    ?>
                </div>

                <?php if ( ! empty( $settings['show_arrows'] ) && 'yes' === $settings['show_arrows'] ) : ?>
                    <button type="button" class="wr-cat-slider-nav wr-cat-slider-prev" aria-label="<?php esc_attr_e( 'Previous', 'wr-ew' ); ?>">
                        <svg viewBox="0 0 24 24" role="img" aria-hidden="true">
                            <path d="M14.7 5.3a1 1 0 0 1 0 1.4L10.41 11H19a1 1 0 1 1 0 2h-8.59l4.3 4.3a1 1 0 0 1-1.42 1.4l-6-6a1 1 0 0 1 0-1.4l6-6a1 1 0 0 1 1.41 0Z"></path>
                        </svg>
                    </button>
                    <button type="button" class="wr-cat-slider-nav wr-cat-slider-next" aria-label="<?php esc_attr_e( 'Next', 'wr-ew' ); ?>">
                        <svg viewBox="0 0 24 24" role="img" aria-hidden="true">
                            <path d="M9.3 18.7a1 1 0 0 1 0-1.4L13.59 13H5a1 1 0 0 1 0-2h8.59l-4.3-4.3a1 1 0 1 1 1.42-1.4l6 6a1 1 0 0 1 0 1.4l-6 6a1 1 0 0 1-1.41 0Z"></path>
                        </svg>
                    </button>
                <?php endif; ?>

                <?php if ( ! empty( $settings['show_dots'] ) && 'yes' === $settings['show_dots'] ) : ?>
                    <div class="wr-cat-slider-pagination"></div>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
}
