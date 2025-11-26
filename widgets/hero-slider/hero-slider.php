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

        /* ------------------------------
         * CONTENT CONTROLS (Slides)
         * ------------------------------ */
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
                'label'   => __( 'Title', 'wr-ew' ),
                'type'    => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Your Slide Title', 'wr-ew' ),
            ]
        );

        $repeater->add_control(
            'subtitle',
            [
                'label'   => __( 'Subtitle', 'wr-ew' ),
                'type'    => \Elementor\Controls_Manager::TEXTAREA,
                'default' => __( 'Your slide subtitle goes here.', 'wr-ew' ),
            ]
        );

        $repeater->add_control(
            'button_text',
            [
                'label'   => __( 'Button Text', 'wr-ew' ),
                'type'    => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Learn More', 'wr-ew' ),
            ]
        );

        $repeater->add_control(
            'button_link',
            [
                'label' => __( 'Button Link', 'wr-ew' ),
                'type'  => \Elementor\Controls_Manager::URL,
                'default' => [
                    'url' => '#',
                ],
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

        /* ------------------------------
         * STYLE TAB — TITLE
         * ------------------------------ */
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
                    '{{WRAPPER}} .wr-hero-content h2' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} .wr-hero-content h2',
            ]
        );

        $this->end_controls_section();

        /* ------------------------------
         * STYLE TAB — SUBTITLE
         * ------------------------------ */
        $this->start_controls_section(
            'style_subtitle_section',
            [
                'label' => __( 'Subtitle', 'wr-ew' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'subtitle_color',
            [
                'label' => __( 'Color', 'wr-ew' ),
                'type'  => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-hero-content p' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'subtitle_typography',
                'selector' => '{{WRAPPER}} .wr-hero-content p',
            ]
        );

        $this->end_controls_section();

        /* ------------------------------
         * STYLE TAB — BUTTON
         * ------------------------------ */
        $this->start_controls_section(
            'style_button_section',
            [
                'label' => __( 'Button', 'wr-ew' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'btn_text_color',
            [
                'label' => __( 'Text Color', 'wr-ew' ),
                'type'  => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-hero-btn' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'btn_bg_color',
            [
                'label' => __( 'Background Color', 'wr-ew' ),
                'type'  => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-hero-btn' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'button_typography',
                'selector' => '{{WRAPPER}} .wr-hero-btn',
            ]
        );

        $this->end_controls_section();

        /* ------------------------------
         * STYLE TAB — LAYOUT
         * ------------------------------ */
        $this->start_controls_section(
            'style_layout_section',
            [
                'label' => __( 'Layout', 'wr-ew' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        // Ana yükseklik kontrolü (responsive).
        $this->add_responsive_control(
            'slider_height',
            [
                'label' => __( 'Height', 'wr-ew' ),
                'type'  => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'vh' ],
                'range' => [
                    'px' => [ 'min' => 200, 'max' => 900 ],
                    'vh' => [ 'min' => 20, 'max' => 100 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wr-hero-slide' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        // Minimum yükseklik (farklı oranlı görseller için güvenlik bariyeri).
        $this->add_responsive_control(
            'slider_min_height',
            [
                'label' => __( 'Min Height', 'wr-ew' ),
                'type'  => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'vh' ],
                'range' => [
                    'px' => [ 'min' => 200, 'max' => 900 ],
                    'vh' => [ 'min' => 20, 'max' => 100 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wr-hero-slide' => 'min-height: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        // Maksimum yükseklik (auto modlarında taşmayı önler).
        $this->add_responsive_control(
            'slider_max_height',
            [
                'label' => __( 'Max Height', 'wr-ew' ),
                'type'  => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'vh' ],
                'range' => [
                    'px' => [ 'min' => 300, 'max' => 1200 ],
                    'vh' => [ 'min' => 30, 'max' => 100 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wr-hero-slide' => 'max-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        // Overlay rengi kontrolü.
        $this->add_control(
            'overlay_color',
            [
                'label' => __( 'Overlay Color', 'wr-ew' ),
                'type'  => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-hero-slide::after' => 'background-color: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        // İçerik hizalama.
        $this->add_responsive_control(
            'content_alignment',
            [
                'label'   => __( 'Content Alignment', 'wr-ew' ),
                'type'    => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'wr-ew' ),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'wr-ew' ),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'wr-ew' ),
                        'icon'  => 'eicon-text-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wr-hero-content' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        // Image fit kontrolü (cover / contain / auto).
        $this->add_control(
            'image_fit',
            [
                'label'   => __( 'Image Fit', 'wr-ew' ),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    ''        => __( 'Default (Cover)', 'wr-ew' ),
                    'cover'   => __( 'Cover', 'wr-ew' ),
                    'contain' => __( 'Contain', 'wr-ew' ),
                    'auto'    => __( 'Auto', 'wr-ew' ),
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .wr-hero-slide' => 'background-size: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        if ( empty( $settings['slides'] ) ) {
            return;
        }

        echo '<div class="wr-hero-slider-swiper swiper">';
        echo '<div class="swiper-wrapper">';

        foreach ( $settings['slides'] as $slide ) {

            $bg = isset( $slide['image']['url'] ) ? $slide['image']['url'] : '';

            echo '<div class="wr-hero-slide swiper-slide" style="background-image:url(' . esc_url( $bg ) . ')">';

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

            echo '</div>'; // wr-hero-content

            echo '</div>'; // wr-hero-slide
        }

        echo '</div>'; // swiper-wrapper
        echo '<div class="swiper-pagination"></div>';
        echo '<div class="swiper-button-prev"></div>';
        echo '<div class="swiper-button-next"></div>';
        echo '</div>'; // swiper
    }
}
