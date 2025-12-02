<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class WR_EW_Usp_Row extends \Elementor\Widget_Base {

    public function get_name() {
        return 'wr-usp-row';
    }

    public function get_title() {
        return __( 'WR USP Row', 'wr-elementor-widgets' );
    }

    public function get_icon() {
        return 'eicon-star';
    }

    public function get_categories() {
        return [ 'wr-widgets', 'wr-ecommerce-elements' ];
    }

    public function get_style_depends() {
        return [ 'wr-usp-row-css' ];
    }

    public function get_script_depends() {
        return [ 'wr-usp-row-js' ];
    }

    protected function register_controls() {

        /* ------------------------------
           CONTENT TAB
        ------------------------------ */
        $this->start_controls_section(
            'section_content',
            [
                'label' => __( 'USP Items', 'wr-elementor-widgets' ),
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'usp_icon',
            [
                'label' => __( 'Icon', 'wr-elementor-widgets' ),
                'type'  => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-check-circle',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $repeater->add_control(
            'usp_title',
            [
                'label'       => __( 'USP Text', 'wr-elementor-widgets' ),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'default'     => __( 'Free Shipping', 'wr-elementor-widgets' ),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'usp_items',
            [
                'label'   => __( 'Items', 'wr-elementor-widgets' ),
                'type'    => \Elementor\Controls_Manager::REPEATER,
                'fields'  => $repeater->get_controls(),
                'default' => [
                    [ 'usp_title' => 'Free Shipping' ],
                    [ 'usp_title' => 'Secure Payment' ],
                    [ 'usp_title' => '24/7 Support' ],
                ],
                'title_field' => '{{{ usp_title }}}',
            ]
        );

        $this->end_controls_section();


        /* ------------------------------
           STYLE TAB
        ------------------------------ */
        $this->start_controls_section(
            'section_style',
            [
                'label' => __( 'Typography', 'wr-elementor-widgets' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'usp_text_color',
            [
                'label'     => __( 'Text Color', 'wr-elementor-widgets' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#222222',
                'selectors' => [
                    '{{WRAPPER}} .wr-usp-item' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'usp_icon_color',
            [
                'label'     => __( 'Icon Color', 'wr-elementor-widgets' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#111111',
                'selectors' => [
                    '{{WRAPPER}} .wr-usp-item i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .wr-usp-item svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'usp_typography',
                'selector' => '{{WRAPPER}} .wr-usp-item',
            ]
        );

        $this->end_controls_section();
    }

    /* ------------------------------
       RENDER
    ------------------------------ */
    protected function render() {
        $settings = $this->get_settings_for_display();

        if ( empty( $settings['usp_items'] ) ) return;

        echo '<div class="wr-usp-row">';

        foreach ( $settings['usp_items'] as $item ) {

            echo '<div class="wr-usp-item">';

            if ( ! empty( $item['usp_icon']['value'] ) ) {
                \Elementor\Icons_Manager::render_icon( $item['usp_icon'], [ 'aria-hidden' => 'true' ] );
            }

            echo '<span class="wr-usp-text">' . esc_html( $item['usp_title'] ) . '</span>';

            echo '</div>';
        }

        echo '</div>';
    }
}
