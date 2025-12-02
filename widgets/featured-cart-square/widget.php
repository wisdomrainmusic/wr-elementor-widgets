<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class WR_EW_Featured_Cart_Square extends \Elementor\Widget_Base {

    public function get_name() {
        return 'featured-cart-square';
    }

    public function get_title() {
        return __( 'Featured Cart Square', 'wr-ew' );
    }

    public function get_icon() {
        return 'eicon-gallery-grid';
    }

    public function get_categories() {
        return [ 'wr-widgets', 'wr-ecommerce-elements' ];
    }

    public function get_style_depends() {
        return [ 'wr-featured-cart-square-css' ];
    }

    public function get_script_depends() {
        return [ 'wr-featured-cart-square-js' ];
    }

    protected function register_controls() {

        /* --------------------------------
         * SECTION: CARDS
         * -------------------------------- */
        $this->start_controls_section(
            'section_cards',
            [
                'label' => __( 'Cards', 'wr-ew' ),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new \Elementor\Repeater();

        /* IMAGE */
        $repeater->add_control(
            'image',
            [
                'label' => __( 'Image', 'wr-ew' ),
                'type'  => \Elementor\Controls_Manager::MEDIA,
            ]
        );

        /* TITLE */
        $repeater->add_control(
            'title',
            [
                'label' => __( 'Title', 'wr-ew' ),
                'type'  => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Bir alt başlık ekleyin', 'wr-ew' ),
            ]
        );

        /* DESCRIPTION */
        $repeater->add_control(
            'desc',
            [
                'label' => __( 'Description', 'wr-ew' ),
                'type'  => \Elementor\Controls_Manager::TEXTAREA,
                'default' => __( 'Birkaç satır gövde metni ekle', 'wr-ew' ),
            ]
        );

        /* BUTTON TEXT */
        $repeater->add_control(
            'button_text',
            [
                'label' => __( 'Button Text', 'wr-ew' ),
                'type'  => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Detay', 'wr-ew' ),
            ]
        );

        /* BUTTON LINK */
        $repeater->add_control(
            'button_link',
            [
                'label' => __( 'Button Link', 'wr-ew' ),
                'type'  => \Elementor\Controls_Manager::URL,
                'default' => [ 'url' => '#' ],
            ]
        );

        /* APPLY REPEATER */
        $this->add_control(
            'cards',
            [
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [],
            ]
        );

        $this->end_controls_section();

        /* --------------------------------
         * STYLE — Title / Desc / Button
         * -------------------------------- */

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
                'default' => '#111',
                'selectors' => [
                    '{{WRAPPER}} .wr-fcs-content h3' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} .wr-fcs-content h3',
            ]
        );

        $this->end_controls_section();


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
                'default' => '#444',
                'selectors' => [
                    '{{WRAPPER}} .wr-fcs-content p' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'desc_typography',
                'selector' => '{{WRAPPER}} .wr-fcs-content p',
            ]
        );

        $this->end_controls_section();


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
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .wr-fcs-button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_bg_color',
            [
                'label' => __( 'Background Color', 'wr-ew' ),
                'type'  => \Elementor\Controls_Manager::COLOR,
                'default' => '#111',
                'selectors' => [
                    '{{WRAPPER}} .wr-fcs-button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'button_typography',
                'selector' => '{{WRAPPER}} .wr-fcs-button',
            ]
        );

        $this->end_controls_section();
    }

    /* --------------------------------
     * RENDER
     * -------------------------------- */
    protected function render() {

        $settings = $this->get_settings_for_display();
        if ( empty( $settings['cards'] ) ) return;

        echo '<div class="wr-featured-cart-square-wrapper">';

        foreach ( $settings['cards'] as $card ) {

            echo '<div class="wr-fcs-card">';

            /* IMAGE */
            if ( ! empty( $card['image']['url'] ) ) {
                echo '<div class="wr-fcs-image">';
                echo '<img src="' . esc_url( $card['image']['url'] ) . '" alt="">';
                echo '</div>';
            }

            /* CONTENT */
            echo '<div class="wr-fcs-content">';

            if ( ! empty( $card['title'] ) ) {
                echo '<h3>' . esc_html( $card['title'] ) . '</h3>';
            }

            if ( ! empty( $card['desc'] ) ) {
                echo '<p>' . esc_html( $card['desc'] ) . '</p>';
            }

            if ( ! empty( $card['button_text'] ) ) {
                $btn_url = ! empty( $card['button_link']['url'] ) ? $card['button_link']['url'] : '#';

                echo '<a href="' . esc_url($btn_url) . '" class="wr-fcs-button">';
                echo esc_html($card['button_text']);
                echo '</a>';
            }

            echo '</div>'; // content

            echo '</div>'; // card
        }

        echo '</div>'; // wrapper
    }
}
