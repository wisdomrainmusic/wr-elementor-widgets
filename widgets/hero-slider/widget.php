<?php
if ( ! defined( 'ABSPATH' ) ) exit;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Repeater;
use Elementor\Utils;

class WR_EW_Hero_Slider extends Widget_Base {

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
        $this->register_content_controls();
        $this->register_style_controls();
    }

    protected function register_content_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __( 'Slides', 'wr-ew' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'image',
            [
                'label'   => __( 'Background Image', 'wr-ew' ),
                'type'    => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $repeater->add_control(
            'title',
            [
                'label'   => __( 'Title', 'wr-ew' ),
                'type'    => Controls_Manager::TEXT,
                'default' => __( 'Your Slide Title', 'wr-ew' ),
            ]
        );

        $repeater->add_control(
            'subtitle',
            [
                'label'   => __( 'Subtitle', 'wr-ew' ),
                'type'    => Controls_Manager::TEXTAREA,
                'default' => __( 'Your slide subtitle goes here.', 'wr-ew' ),
            ]
        );

        $repeater->add_control(
            'button_text',
            [
                'label'   => __( 'Button Text', 'wr-ew' ),
                'type'    => Controls_Manager::TEXT,
                'default' => __( 'Learn More', 'wr-ew' ),
            ]
        );

        $repeater->add_control(
            'button_link',
            [
                'label'   => __( 'Button URL', 'wr-ew' ),
                'type'    => Controls_Manager::URL,
                'default' => [
                    'url' => '#',
                ],
            ]
        );

        $repeater->add_control(
            'button_new_tab',
            [
                'label'        => __( 'Open in new tab', 'wr-ew' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'Yes', 'wr-ew' ),
                'label_off'    => __( 'No', 'wr-ew' ),
                'return_value' => 'yes',
                'default'      => '',
            ]
        );

        $this->add_control(
            'slides',
            [
                'label'       => __( 'Slides', 'wr-ew' ),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [],
                'title_field' => '{{{ title }}}',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'slider_settings_section',
            [
                'label' => __( 'Slider Settings', 'wr-ew' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'autoplay',
            [
                'label'        => __( 'Autoplay', 'wr-ew' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', 'wr-ew' ),
                'label_off'    => __( 'Off', 'wr-ew' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'autoplay_delay',
            [
                'label'   => __( 'Autoplay Delay (ms)', 'wr-ew' ),
                'type'    => Controls_Manager::NUMBER,
                'min'     => 1000,
                'max'     => 10000,
                'step'    => 100,
                'default' => 4000,
                'condition' => [
                    'autoplay' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'loop',
            [
                'label'        => __( 'Loop', 'wr-ew' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', 'wr-ew' ),
                'label_off'    => __( 'Off', 'wr-ew' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'show_arrows',
            [
                'label'        => __( 'Show Arrows', 'wr-ew' ),
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
                'label'        => __( 'Show Dots', 'wr-ew' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'Show', 'wr-ew' ),
                'label_off'    => __( 'Hide', 'wr-ew' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'fullwidth',
            [
                'label'        => __( 'Full Width (Hero)', 'wr-ew' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', 'wr-ew' ),
                'label_off'    => __( 'Off', 'wr-ew' ),
                'return_value' => 'yes',
                'default'      => '',
                'description'  => __( 'Stretch the slider to 100vw without breaking boxed layouts.', 'wr-ew' ),
            ]
        );

        $this->end_controls_section();
    }

    protected function register_style_controls() {
        $this->start_controls_section(
            'style_slider_section',
            [
                'label' => __( 'Slider', 'wr-ew' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'slider_height',
            [
                'label' => __( 'Slider Height', 'wr-ew' ),
                'type'  => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 240,
                        'max' => 900,
                    ],
                ],
                'default' => [
                    'size' => 550,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .wr-hero-slider' => '--wr-hero-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'slide_background',
                'label'    => __( 'Slide Background', 'wr-ew' ),
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .wr-hero-slider .wr-hero-slide',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'style_content_position_section',
            [
                'label' => __( 'Content Position', 'wr-ew' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'content_offset_x',
            [
                'label' => __( 'Horizontal Offset (X)', 'wr-ew' ),
                'type'  => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [ 'min' => -300, 'max' => 300 ],
                ],
                'default' => [ 'size' => 0, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .wr-hero-slider' => '--wr-hero-x: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_offset_y',
            [
                'label' => __( 'Vertical Offset (Y)', 'wr-ew' ),
                'type'  => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [ 'min' => -300, 'max' => 300 ],
                ],
                'default' => [ 'size' => 0, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .wr-hero-slider' => '--wr-hero-y: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'style_panel_section',
            [
                'label' => __( 'Text Panel', 'wr-ew' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'panel_background',
                'label'    => __( 'Panel Background', 'wr-ew' ),
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .wr-hero-slider .wr-hero-slide__panel',
            ]
        );

        $this->add_responsive_control(
            'panel_padding',
            [
                'label'      => __( 'Padding', 'wr-ew' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .wr-hero-slider .wr-hero-slide__panel' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'default' => [
                    'top' => '20',
                    'right' => '24',
                    'bottom' => '20',
                    'left' => '24',
                    'unit' => 'px',
                ],
            ]
        );

        $this->add_control(
            'panel_border_radius',
            [
                'label'      => __( 'Border Radius', 'wr-ew' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .wr-hero-slider .wr-hero-slide__panel' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'panel_border',
                'selector' => '{{WRAPPER}} .wr-hero-slider .wr-hero-slide__panel',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'panel_box_shadow',
                'selector' => '{{WRAPPER}} .wr-hero-slider .wr-hero-slide__panel',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'style_title_section',
            [
                'label' => __( 'Title', 'wr-ew' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __( 'Color', 'wr-ew' ),
                'type'  => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-hero-slider .wr-hero-slide__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} .wr-hero-slider .wr-hero-slide__title',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'style_subtitle_section',
            [
                'label' => __( 'Subtitle', 'wr-ew' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'subtitle_color',
            [
                'label' => __( 'Color', 'wr-ew' ),
                'type'  => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-hero-slider .wr-hero-slide__subtitle' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'subtitle_typography',
                'selector' => '{{WRAPPER}} .wr-hero-slider .wr-hero-slide__subtitle',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'style_button_section',
            [
                'label' => __( 'Button', 'wr-ew' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'button_typography',
                'selector' => '{{WRAPPER}} .wr-hero-slider .wr-hero-slide__btn',
            ]
        );

        $this->start_controls_tabs( 'button_style_tabs' );

        $this->start_controls_tab(
            'button_tab_normal',
            [ 'label' => __( 'Normal', 'wr-ew' ) ]
        );

        $this->add_control(
            'button_text_color',
            [
                'label' => __( 'Text Color', 'wr-ew' ),
                'type'  => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-hero-slider .wr-hero-slide__btn' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_bg_color',
            [
                'label' => __( 'Background Color', 'wr-ew' ),
                'type'  => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-hero-slider .wr-hero-slide__btn' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'button_border',
                'selector' => '{{WRAPPER}} .wr-hero-slider .wr-hero-slide__btn',
            ]
        );

        $this->add_control(
            'button_border_radius',
            [
                'label'      => __( 'Border Radius', 'wr-ew' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .wr-hero-slider .wr-hero-slide__btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_padding',
            [
                'label'      => __( 'Padding', 'wr-ew' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .wr-hero-slider .wr-hero-slide__btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'default' => [
                    'top' => '12',
                    'right' => '24',
                    'bottom' => '12',
                    'left' => '24',
                    'unit' => 'px',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'button_tab_hover',
            [ 'label' => __( 'Hover', 'wr-ew' ) ]
        );

        $this->add_control(
            'button_text_color_hover',
            [
                'label' => __( 'Text Color', 'wr-ew' ),
                'type'  => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-hero-slider .wr-hero-slide__btn:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_bg_color_hover',
            [
                'label' => __( 'Background Color', 'wr-ew' ),
                'type'  => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-hero-slider .wr-hero-slide__btn:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'button_box_shadow_hover',
                'selector' => '{{WRAPPER}} .wr-hero-slider .wr-hero-slide__btn:hover',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        if ( empty( $settings['slides'] ) || ! is_array( $settings['slides'] ) ) {
            return;
        }

        $this->add_render_attribute( 'wrapper', 'class', 'wr-hero-slider' );

        if ( 'yes' === $settings['fullwidth'] ) {
            $this->add_render_attribute( 'wrapper', 'class', 'is-fullwidth' );
        }

        $this->add_render_attribute( 'wrapper', 'data-autoplay', ( 'yes' === $settings['autoplay'] ) ? 'true' : 'false' );
        $this->add_render_attribute( 'wrapper', 'data-delay', ! empty( $settings['autoplay_delay'] ) ? $settings['autoplay_delay'] : 4000 );
        $this->add_render_attribute( 'wrapper', 'data-loop', ( 'yes' === $settings['loop'] ) ? 'true' : 'false' );
        $this->add_render_attribute( 'wrapper', 'data-arrows', ( 'yes' === $settings['show_arrows'] ) ? 'true' : 'false' );
        $this->add_render_attribute( 'wrapper', 'data-dots', ( 'yes' === $settings['show_dots'] ) ? 'true' : 'false' );

        ?>
        <div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
            <div class="wr-hero-slider-swiper swiper">
                <div class="swiper-wrapper">
                    <?php foreach ( $settings['slides'] as $slide ) :
                        $image_url = ! empty( $slide['image']['url'] ) ? $slide['image']['url'] : Utils::get_placeholder_image_src();
                        $title     = ! empty( $slide['title'] ) ? $slide['title'] : '';
                        $subtitle  = ! empty( $slide['subtitle'] ) ? $slide['subtitle'] : '';
                        $btn_text  = ! empty( $slide['button_text'] ) ? $slide['button_text'] : '';
                        $btn_link  = ! empty( $slide['button_link']['url'] ) ? $slide['button_link']['url'] : '';
                        $target    = ( ! empty( $slide['button_new_tab'] ) && 'yes' === $slide['button_new_tab'] ) ? '_blank' : '_self';
                        $rel       = ( '_blank' === $target ) ? 'noopener noreferrer' : '';
                        ?>
                        <div class="swiper-slide wr-hero-slide">
                            <div class="wr-hero-slide__bg" style="background-image: url('<?php echo esc_url( $image_url ); ?>');"></div>
                            <div class="wr-hero-slide__content">
                                <div class="wr-hero-slide__panel">
                                    <?php if ( $title ) : ?>
                                        <h2 class="wr-hero-slide__title"><?php echo esc_html( $title ); ?></h2>
                                    <?php endif; ?>

                                    <?php if ( $subtitle ) : ?>
                                        <div class="wr-hero-slide__subtitle"><?php echo wp_kses_post( $subtitle ); ?></div>
                                    <?php endif; ?>

                                    <?php if ( $btn_text && $btn_link ) : ?>
                                        <a class="wr-hero-slide__btn" href="<?php echo esc_url( $btn_link ); ?>" target="<?php echo esc_attr( $target ); ?>" <?php echo $rel ? 'rel="' . esc_attr( $rel ) . '"' : ''; ?>>
                                            <?php echo esc_html( $btn_text ); ?>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="swiper-pagination"></div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
            </div>
        </div>
        <?php
    }
}
