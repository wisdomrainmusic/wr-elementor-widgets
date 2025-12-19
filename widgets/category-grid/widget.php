<?php
if ( ! defined( 'ABSPATH' ) ) exit;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Group_Control_Typography;

class WR_EW_Category_Grid extends Widget_Base {

    public function get_name() {
        return 'wr-category-grid';
    }

    public function get_title() {
        return __( 'WR Category Grid', 'wr-ew' );
    }

    public function get_icon() {
        return 'eicon-gallery-grid';
    }

    public function get_categories() {
        return [ 'wr-widgets' ];
    }

    public function get_keywords() {
        return [ 'category', 'product', 'grid', 'woo', 'wr' ];
    }

    public function get_style_depends() {
        wp_register_style(
            'wr-category-grid',
            WR_EW_PLUGIN_URL . 'assets/css/category-grid.css',
            [],
            '1.0.0'
        );

        return [ 'wr-category-grid' ];
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
            'categories',
            [
                'label'    => __( 'Select Categories', 'wr-ew' ),
                'type'     => Controls_Manager::SELECT2,
                'multiple' => true,
                'options'  => $this->get_product_categories_options(),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'columns',
            [
                'label'   => __( 'Columns', 'wr-ew' ),
                'type'    => Controls_Manager::SELECT,
                'default' => '3',
                'options' => [
                    '2' => __( '2 Columns', 'wr-ew' ),
                    '3' => __( '3 Columns', 'wr-ew' ),
                    '4' => __( '4 Columns', 'wr-ew' ),
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style',
            [
                'label' => __( 'Content', 'wr-ew' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __( 'Title Color', 'wr-ew' ),
                'type'  => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-category-grid__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} .wr-category-grid__title',
            ]
        );

        $this->add_control(
            'count_color',
            [
                'label' => __( 'Count Color', 'wr-ew' ),
                'type'  => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-category-grid__count' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'count_typography',
                'selector' => '{{WRAPPER}} .wr-category-grid__count',
            ]
        );

        $this->end_controls_section();
    }

    private function get_product_categories_options() {
        $options = [];

        $terms = get_terms(
            [
                'taxonomy'   => 'product_cat',
                'hide_empty' => false,
            ]
        );

        if ( ! is_wp_error( $terms ) ) {
            foreach ( $terms as $term ) {
                $options[ $term->term_id ] = $term->name;
            }
        }

        return $options;
    }

    protected function render() {
        $settings  = $this->get_settings_for_display();
        $columns   = isset( $settings['columns'] ) ? $settings['columns'] : '3';
        $term_ids  = ! empty( $settings['categories'] ) ? $settings['categories'] : [];

        if ( empty( $term_ids ) ) {
            $terms = get_terms(
                [
                    'taxonomy'   => 'product_cat',
                    'hide_empty' => false,
                ]
            );
        } else {
            $terms = get_terms(
                [
                    'taxonomy'   => 'product_cat',
                    'hide_empty' => false,
                    'include'    => $term_ids,
                ]
            );
        }

        if ( empty( $terms ) || is_wp_error( $terms ) ) {
            echo '<p>' . esc_html__( 'No categories found.', 'wr-ew' ) . '</p>';
            return;
        }

        $column_class = 'wr-category-grid--cols-' . absint( $columns );
        ?>
        <div class="wr-category-grid <?php echo esc_attr( $column_class ); ?>">
            <?php foreach ( $terms as $term ) :
                $thumbnail_id = get_term_meta( $term->term_id, 'thumbnail_id', true );
                $image_url    = $thumbnail_id ? wp_get_attachment_image_url( $thumbnail_id, 'medium' ) : false;
                $placeholder  = function_exists( 'wc_placeholder_img_src' ) ? wc_placeholder_img_src() : Utils::get_placeholder_image_src();
                $image_url    = $image_url ? $image_url : $placeholder;
                $link         = get_term_link( $term );
                ?>
                <a class="wr-category-grid__item" href="<?php echo esc_url( $link ); ?>">
                    <div class="wr-category-grid__thumb">
                        <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $term->name ); ?>">
                    </div>
                    <h3 class="wr-category-grid__title"><?php echo esc_html( $term->name ); ?></h3>
                    <span class="wr-category-grid__count"><?php echo sprintf( _n( '%s product', '%s products', $term->count, 'wr-ew' ), number_format_i18n( $term->count ) ); ?></span>
                </a>
            <?php endforeach; ?>
        </div>
        <?php
    }
}
