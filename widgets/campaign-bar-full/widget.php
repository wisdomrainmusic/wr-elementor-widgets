<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class WR_EW_Campaign_Bar_Full extends \Elementor\Widget_Base {

    public function get_name() {
        return 'wr-campaign-bar-full';
    }

    public function get_title() {
        return __( 'WR Campaign Bar Full', 'wr-ew' );
    }

    public function get_icon() {
        return 'eicon-notification';
    }

    public function get_categories() {
        return [ 'wr-widgets', 'wr-ecommerce-elements' ];
    }

    public function get_keywords() {
        return [ 'campaign', 'bar', 'full', 'marquee', 'notice' ];
    }

    public function get_style_depends() {
        return [ 'wr-campaign-bar-full-css' ];
    }

    public function get_script_depends() {
        return [ 'wr-campaign-bar-full-js' ];
    }

    protected function register_controls() {

        // CONTENT TAB
        $this->start_controls_section(
            'section_content',
            [
                'label' => __( 'Content', 'wr-ew' ),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'text',
            [
                'label'       => __( 'Text', 'wr-ew' ),
                'type'        => \Elementor\Controls_Manager::TEXTAREA,
                'default'     => __( '✨ Big Winter Sale – Up to 40% OFF!', 'wr-ew' ),
                'label_block' => true,
                'rows'        => 2,
            ]
        );

        $this->add_control(
            'link',
            [
                'label'         => __( 'Link URL', 'wr-ew' ),
                'type'          => \Elementor\Controls_Manager::URL,
                'placeholder'   => 'https://',
                'show_external' => true,
                'default'       => [ 'url' => '', 'is_external' => false, 'nofollow' => false ],
            ]
        );

        $this->add_control(
            'text_mode',
            [
                'label'   => __( 'Text Mode', 'wr-ew' ),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'static'  => __( 'Static Text', 'wr-ew' ),
                    'marquee' => __( 'Scrolling / Marquee', 'wr-ew' ),
                ],
                'default' => 'static',
            ]
        );

        $this->add_control(
            'marquee_speed',
            [
                'label'     => __( 'Speed', 'wr-ew' ),
                'type'      => \Elementor\Controls_Manager::SLIDER,
                'size_units'=> [ 'px' ],
                'range'     => [ 'px' => [ 'min' => 5, 'max' => 120 ] ],
                'default'   => [ 'size' => 18, 'unit' => 'px' ],
                'description' => __( 'Seconds for one full marquee loop.', 'wr-ew' ),
                'condition' => [ 'text_mode' => 'marquee' ],
            ]
        );

        $this->add_control(
            'full_width',
            [
                'label'        => __( 'Full Width (Hero)', 'wr-ew' ),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => __( 'Yes', 'wr-ew' ),
                'label_off'    => __( 'No', 'wr-ew' ),
                'return_value' => 'yes',
                'default'      => '',
            ]
        );

        $this->end_controls_section();

        // STYLE TAB
        $this->start_controls_section(
            'section_style',
            [
                'label' => __( 'Style', 'wr-ew' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'height',
            [
                'label'      => __( 'Height', 'wr-ew' ),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => 30, 'max' => 140 ] ],
                'selectors'  => [
                    '{{WRAPPER}} .wr-cbf' => 'height: {{SIZE}}{{UNIT}}; min-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name'     => 'background',
                'label'    => __( 'Background', 'wr-ew' ),
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .wr-cbf',
            ]
        );

        $this->add_responsive_control(
            'padding',
            [
                'label'      => __( 'Padding', 'wr-ew' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .wr-cbf__inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'text_alignment',
            [
                'label'     => __( 'Alignment', 'wr-ew' ),
                'type'      => \Elementor\Controls_Manager::CHOOSE,
                'options'   => [
                    'left'   => [ 'title' => __( 'Left', 'wr-ew' ), 'icon' => 'eicon-text-align-left' ],
                    'center' => [ 'title' => __( 'Center', 'wr-ew' ), 'icon' => 'eicon-text-align-center' ],
                    'right'  => [ 'title' => __( 'Right', 'wr-ew' ), 'icon' => 'eicon-text-align-right' ],
                ],
                'default'   => 'center',
                'selectors' => [
                    '{{WRAPPER}} .wr-cbf__inner' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'typography',
                'selector' => '{{WRAPPER}} .wr-cbf__text, {{WRAPPER}} .wr-cbf__marquee-content',
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label'     => __( 'Text Color', 'wr-ew' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .wr-cbf' => '--wr-cbf-text: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'     => 'border',
                'selector' => '{{WRAPPER}} .wr-cbf',
            ]
        );

        $this->add_control(
            'border_radius',
            [
                'label'      => __( 'Border Radius', 'wr-ew' ),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => 0, 'max' => 50 ] ],
                'selectors'  => [
                    '{{WRAPPER}} .wr-cbf' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'box_shadow',
                'selector' => '{{WRAPPER}} .wr-cbf',
            ]
        );

        $this->add_control(
            'hover_background',
            [
                'label'     => __( 'Hover Background', 'wr-ew' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-cbf.has-link:hover' => 'background-color: {{VALUE}};',
                ],
                'description' => __( 'Applies when a link is set.', 'wr-ew' ),
            ]
        );

        $this->add_control(
            'hover_text_color',
            [
                'label'     => __( 'Hover Text Color', 'wr-ew' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-cbf.has-link:hover' => '--wr-cbf-text: {{VALUE}};',
                ],
                'description' => __( 'Applies when a link is set.', 'wr-ew' ),
            ]
        );

        $this->add_control(
            'marquee_gap',
            [
                'label'     => __( 'Marquee Gap', 'wr-ew' ),
                'type'      => \Elementor\Controls_Manager::SLIDER,
                'size_units'=> [ 'px' ],
                'range'     => [ 'px' => [ 'min' => 8, 'max' => 180 ] ],
                'default'   => [ 'size' => 48, 'unit' => 'px' ],
                'condition' => [ 'text_mode' => 'marquee' ],
                'selectors' => [
                    '{{WRAPPER}} .wr-cbf' => '--wr-cbf-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $text         = ! empty( $settings['text'] ) ? $settings['text'] : '';
        $link         = $settings['link'];
        $is_marquee   = ( 'marquee' === ( $settings['text_mode'] ?? 'static' ) );
        $is_fullwidth = ( isset( $settings['full_width'] ) && 'yes' === $settings['full_width'] );
        $speed_value  = isset( $settings['marquee_speed']['size'] ) ? floatval( $settings['marquee_speed']['size'] ) : 18;

        $classes = [ 'wr-cbf' ];
        if ( $is_fullwidth ) {
            $classes[] = 'is-fullwidth';
        }
        if ( $is_marquee ) {
            $classes[] = 'is-marquee';
        }
        if ( ! empty( $link['url'] ) ) {
            $classes[] = 'has-link';
        }

        $this->add_render_attribute( 'wrapper', 'class', implode( ' ', $classes ) );

        $this->add_render_attribute( 'wrapper', 'data-speed', $speed_value );

        if ( $is_marquee ) {
            $this->add_render_attribute( 'wrapper', 'style', sprintf( '--wr-cbf-speed: %ss;', $speed_value ) );
        }

        $tag = 'div';
        $link_attributes = [];

        if ( ! empty( $link['url'] ) ) {
            $tag = 'a';
            $link_attributes['href'] = $link['url'];

            if ( ! empty( $link['is_external'] ) ) {
                $link_attributes['target'] = '_blank';
            }

            if ( ! empty( $link['nofollow'] ) ) {
                $link_attributes['rel'] = 'nofollow';
            }

            if ( ! empty( $link['is_external'] ) ) {
                $existing_rel = $link_attributes['rel'] ?? '';
                $link_attributes['rel'] = trim( $existing_rel . ' noopener noreferrer' );
            }
        }

        $this->add_render_attribute( 'link', 'class', 'wr-cbf__link' );
        foreach ( $link_attributes as $attr => $value ) {
            $this->add_render_attribute( 'link', $attr, $value );
        }

        $text_markup = wp_kses_post( $text );

        echo '<div ' . $this->get_render_attribute_string( 'wrapper' ) . '>';

        echo '<' . $tag . ' ' . $this->get_render_attribute_string( 'link' ) . '>';
        echo '<span class="wr-cbf__inner">';

        if ( $is_marquee ) {
            echo '<span class="wr-cbf__marquee">';
            echo '<span class="wr-cbf__marquee-content">';

            for ( $i = 0; $i < 6; $i++ ) {
                echo '<span class="wr-cbf__text">' . $text_markup . '</span>';
            }

            echo '</span>';
            echo '</span>';
        } else {
            echo '<span class="wr-cbf__text">' . $text_markup . '</span>';
        }

        echo '</span>';
        echo '</' . $tag . '>';
        echo '</div>';
    }
}
