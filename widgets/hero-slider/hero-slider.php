<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class WR_EW_Hero_Slider extends \Elementor\Widget_Base {

    public function get_name() {
        return 'wr-hero-slider';
    }

    public function get_title() {
        return __( 'WR Hero Slider', 'wr-ew' );
    }

    public function get_icon() {
        return 'eicon-slider-album';
    }

    public function get_categories() {
        return [ 'wr-widgets' ];
    }

    public function get_keywords() {
        return [ 'slider', 'hero', 'banner', 'wr' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'content_section',
            [
                'label' => __( 'Slides', 'wr-ew' ),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'title',
            [
                'label' => __( 'Title', 'wr-ew' ),
                'type'  => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Your Slide Title', 'wr-ew' ),
            ]
        );

        $repeater->add_control(
            'subtitle',
            [
                'label' => __( 'Subtitle', 'wr-ew' ),
                'type'  => \Elementor\Controls_Manager::TEXTAREA,
                'default' => __( 'Your slide subtitle goes here.', 'wr-ew' ),
            ]
        );

        $repeater->add_control(
            'button_text',
            [
                'label' => __( 'Button Text', 'wr-ew' ),
                'type'  => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Learn More', 'wr-ew' ),
            ]
        );

        $repeater->add_control(
            'button_link',
            [
                'label' => __( 'Button Link', 'wr-ew' ),
                'type'  => \Elementor\Controls_Manager::URL,
                'default' => [
                    'url' => '#'
                ]
            ]
        );

        $repeater->add_control(
            'image',
            [
                'label'   => __( 'Background Image', 'wr-ew' ),
                'type'    => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->add_control(
            'slides',
            [
                'label'       => __( 'Slides', 'wr-ew' ),
                'type'        => \Elementor\Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [],
                'title_field' => '{{{ title }}}',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        if ( empty( $settings['slides'] ) ) return;

        echo '<div class="wr-hero-slider">';

        foreach ( $settings['slides'] as $slide ) {

            $bg = isset( $slide['image']['url'] ) ? $slide['image']['url'] : '';

            echo '<div class="wr-hero-slide" style="background-image:url(' . esc_url( $bg ) . ')">';

            echo '<div class="wr-hero-content">';
            if ( ! empty( $slide['title'] ) ) {
                echo '<h2>' . esc_html( $slide['title'] ) . '</h2>';
            }

            if ( ! empty( $slide['subtitle'] ) ) {
                echo '<p>' . esc_html( $slide['subtitle'] ) . '</p>';
            }

            if ( ! empty( $slide['button_text'] ) ) {
                $link = $slide['button_link']['url'] ?? '#';
                echo '<a href="' . esc_url( $link ) . '" class="wr-hero-btn">' . esc_html( $slide['button_text'] ) . '</a>';
            }

            echo '</div>'; // content

            echo '</div>'; // slide
        }

        echo '</div>'; // slider
    }
}
