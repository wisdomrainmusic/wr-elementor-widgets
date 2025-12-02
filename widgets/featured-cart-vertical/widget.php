<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class WR_EW_Featured_Cart_Vertical extends \Elementor\Widget_Base {

    public function get_name() {
        return 'featured-cart-vertical';
    }

    public function get_title() {
        return __( 'Featured Cart Vertical', 'wr-ew' );
    }

    public function get_icon() {
        return 'eicon-image-box';
    }

    public function get_categories() {
        return [ 'wr-widgets', 'wr-ecommerce-elements' ];
    }

    public function get_style_depends() {
        return [ 'wr-featured-cart-vertical-css' ];
    }

    public function get_script_depends() {
        return [ 'wr-featured-cart-vertical-js' ];
    }

    protected function register_controls() {

        /* ============================
         * CARDS (REPEATER)
         * ============================ */
        $this->start_controls_section(
            'section_cards',
            [
                'label' => __( 'Cards', 'wr-ew' ),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'image',
            [
                'label' => __( 'Image', 'wr-ew' ),
                'type'  => \Elementor\Controls_Manager::MEDIA,
            ]
        );

        $repeater->add_control(
            'title',
            [
                'label' => __( 'Title', 'wr-ew' ),
                'type'  => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Bir alt başlık ekleyin', 'wr-ew' ),
            ]
        );

        $repeater->add_control(
            'desc',
            [
                'label' => __( 'Description', 'wr-ew' ),
                'type'  => \Elementor\Controls_Manager::TEXTAREA,
                'default' => __( 'Birkaç satır gövde metni ekle', 'wr-ew' ),
            ]
        );

        $repeater->add_control(
            'button_text',
            [
                'label' => __( 'Button Text', 'wr-ew' ),
                'type'  => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Detay', 'wr-ew' ),
            ]
        );

        $repeater->add_control(
            'button_link',
            [
                'label' => __( 'Button Link', 'wr-ew' ),
                'type'  => \Elementor\Controls_Manager::URL,
                'default' => [ 'url' => '#' ],
            ]
        );

        $this->add_control(
            'cards',
            [
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [],
            ]
        );

        $this->end_controls_section();


        /* ============================
         * TITLE STYLE
         * ============================ */
        $this->start_controls_section(
            'style_title_section',
            [
                'label' => __( 'Title', 'wr-ew' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __( 'Color', 'wr-ew' ),
                'type'  => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-fcv-content h3' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} .wr-fcv-content h3',
            ]
        );

        $this->end_controls_section();


        /* ============================
         * DESCRIPTION STYLE
         * ============================ */
        $this->start_controls_section(
            'style_desc_section',
            [
                'label' => __( 'Description', 'wr-ew' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'desc_color',
            [
                'label' => __( 'Color', 'wr-ew' ),
                'type'  => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-fcv-content p' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'desc_typography',
                'selector' => '{{WRAPPER}} .wr-fcv-content p',
            ]
        );

        $this->end_controls_section();


        /* ============================
         * BUTTON STYLE
         * ============================ */
        $this->start_controls_section(
            'style_button_section',
            [
                'label' => __( 'Button', 'wr-ew' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'button_text_color',
            [
                'label' => __( 'Text Color', 'wr-ew' ),
                'type'  => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-fcv-button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_bg_color',
            [
                'label' => __( 'Background Color', 'wr-ew' ),
                'type'  => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-fcv-button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'button_typography',
                'selector' => '{{WRAPPER}} .wr-fcv-button',
            ]
        );

        $this->end_controls_section();
    }

    /* ============================
     * RENDER
     * ============================ */
    protected function render() {

        $settings = $this->get_settings_for_display();
        if ( empty( $settings['cards'] ) ) return;

        echo '<div class="wr-featured-cart-vertical-wrapper">';

        foreach ( $settings['cards'] as $card ) {

            echo '<div class="wr-fcv-card">';

            if ( ! empty( $card['image']['url'] ) ) {
                echo '<div class="wr-fcv-image">';
                echo '<img src="' . esc_url( $card['image']['url'] ) . '" alt="">';
                echo '</div>';
            }

            echo '<div class="wr-fcv-content">';

            if ( ! empty( $card['title'] ) ) {
                echo '<h3>' . esc_html( $card['title'] ) . '</h3>';
            }

            if ( ! empty( $card['desc'] ) ) {
                echo '<p>' . esc_html( $card['desc'] ) . '</p>';
            }

            if ( ! empty( $card['button_text'] ) ) {
                $url = ! empty( $card['button_link']['url'] ) ? $card['button_link']['url'] : '#';

                echo '<a href="' . esc_url($url) . '" class="wr-fcv-button">';
                echo esc_html($card['button_text']);
                echo '</a>';
            }

            echo '</div>'; // content
            echo '</div>'; // card
        }

        echo '</div>'; // wrapper
    }
}
