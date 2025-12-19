<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Widget_Base;

class WR_EW_Promo_Grid_Full extends Widget_Base {

    const STYLE_HANDLE  = 'wr-promo-grid-full-css';
    const SCRIPT_HANDLE = 'wr-promo-grid-full-js';

    public function get_name() {
        return 'wr-promo-grid-full';
    }

    public function get_title() {
        return __( 'WR Promo Grid Full', 'wr-ew' );
    }

    public function get_icon() {
        return 'eicon-gallery-grid';
    }

    public function get_categories() {
        return [ 'wr-widgets' ];
    }

    public function get_style_depends() {
        if ( ! wp_style_is( self::STYLE_HANDLE, 'registered' ) ) {
            wp_register_style(
                self::STYLE_HANDLE,
                WR_EW_PLUGIN_URL . 'assets/css/promo-grid-full.css',
                [],
                '1.0.0'
            );
        }

        return [ self::STYLE_HANDLE ];
    }

    public function get_script_depends() {
        if ( ! wp_script_is( self::SCRIPT_HANDLE, 'registered' ) ) {
            wp_register_script(
                self::SCRIPT_HANDLE,
                WR_EW_PLUGIN_URL . 'assets/js/promo-grid-full.js',
                [],
                '1.0.0',
                true
            );
        }

        return [ self::SCRIPT_HANDLE ];
    }

    protected function register_controls() {
        $this->register_content_controls();
        $this->register_style_controls();
    }

    private function register_content_controls() {
        $this->start_controls_section(
            'section_layout',
            [
                'label' => __( 'Layout', 'wr-ew' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'preset',
            [
                'label'   => __( 'Layout Preset', 'wr-ew' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'three_mosaic_left',
                'toggle'  => false,
                'options' => [
                    'two_equal'         => [ 'title' => __( 'Two Equal', 'wr-ew' ), 'icon' => 'eicon-gallery-grid' ],
                    'two_split_70_30'   => [ 'title' => __( 'Two 70/30', 'wr-ew' ), 'icon' => 'eicon-columns' ],
                    'two_split_30_70'   => [ 'title' => __( 'Two 30/70', 'wr-ew' ), 'icon' => 'eicon-columns' ],
                    'three_equal'       => [ 'title' => __( 'Three Equal', 'wr-ew' ), 'icon' => 'eicon-slider-3d' ],
                    'three_mosaic_left' => [ 'title' => __( '3 Mosaic L', 'wr-ew' ), 'icon' => 'eicon-gallery-grid' ],
                    'three_mosaic_right'=> [ 'title' => __( '3 Mosaic R', 'wr-ew' ), 'icon' => 'eicon-gallery-grid' ],
                    'four_checker'      => [ 'title' => __( 'Four Checker', 'wr-ew' ), 'icon' => 'eicon-gallery-grid' ],
                    'four_mosaic_left'  => [ 'title' => __( '4 Mosaic L', 'wr-ew' ), 'icon' => 'eicon-gallery-grid' ],
                    'four_mosaic_right' => [ 'title' => __( '4 Mosaic R', 'wr-ew' ), 'icon' => 'eicon-gallery-grid' ],
                    'six_grid'          => [ 'title' => __( 'Six Grid', 'wr-ew' ), 'icon' => 'eicon-gallery-grid' ],
                ],
            ]
        );

        foreach ( $this->get_preset_hints() as $preset_key => $preset_hint ) {
            $this->add_control(
                'preset_hint_' . $preset_key,
                [
                    'type'            => Controls_Manager::RAW_HTML,
                    'raw'             => esc_html( $preset_hint ),
                    'content_classes' => 'wr-pgfull__preset-hint',
                    'condition'       => [ 'preset' => $preset_key ],
                ]
            );
        }

        $this->add_control(
            'full_width',
            [
                'label'        => __( 'Full Width (Hero Like)', 'wr-ew' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', 'wr-ew' ),
                'label_off'    => __( 'Off', 'wr-ew' ),
                'return_value' => 'yes',
                'default'      => '',
            ]
        );

        $this->add_responsive_control(
            'content_max_width',
            [
                'label'      => __( 'Content Max Width', 'wr-ew' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [ 'min' => 768, 'max' => 1920 ],
                ],
                'default'        => [ 'size' => 1200, 'unit' => 'px' ],
                'tablet_default' => [ 'size' => 960, 'unit' => 'px' ],
                'mobile_default' => [ 'size' => 9999, 'unit' => 'px' ],
                'condition'      => [ 'full_width' => 'yes' ],
                'selectors'      => [
                    '{{WRAPPER}} .wr-promo-grid-full .wr-pgfull__inner' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'side_padding',
            [
                'label'      => __( 'Side Padding', 'wr-ew' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => 0, 'max' => 80 ] ],
                'default'        => [ 'size' => 20, 'unit' => 'px' ],
                'tablet_default' => [ 'size' => 16, 'unit' => 'px' ],
                'mobile_default' => [ 'size' => 12, 'unit' => 'px' ],
                'condition'      => [ 'full_width' => 'yes' ],
                'selectors'      => [
                    '{{WRAPPER}} .wr-promo-grid-full .wr-pgfull__inner' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_tiles',
            [
                'label' => __( 'Tiles', 'wr-ew' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'show_tile',
            [
                'label'        => __( 'Show Tile', 'wr-ew' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'Show', 'wr-ew' ),
                'label_off'    => __( 'Hide', 'wr-ew' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $repeater->add_control(
            'image',
            [
                'label'   => __( 'Image', 'wr-ew' ),
                'type'    => Controls_Manager::MEDIA,
                'default' => [],
            ]
        );

        $repeater->add_control(
            'title',
            [
                'label'       => __( 'Title', 'wr-ew' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => '',
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'subtitle',
            [
                'label'       => __( 'Subtitle', 'wr-ew' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => '',
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'button_text',
            [
                'label'       => __( 'Button Text', 'wr-ew' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => '',
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'link',
            [
                'label'       => __( 'Link', 'wr-ew' ),
                'type'        => Controls_Manager::URL,
                'placeholder' => 'https://',
                'options'     => [ 'url', 'is_external', 'nofollow' ],
                'default'     => [
                    'url'         => '',
                    'is_external' => false,
                    'nofollow'    => false,
                ],
            ]
        );

        $repeater->add_control(
            'open_in_new_tab',
            [
                'label'        => __( 'Open in new tab', 'wr-ew' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'Yes', 'wr-ew' ),
                'label_off'    => __( 'No', 'wr-ew' ),
                'return_value' => 'yes',
                'default'      => '',
            ]
        );

        $repeater->add_control(
            'overlay',
            [
                'label'        => __( 'Enable Overlay', 'wr-ew' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', 'wr-ew' ),
                'label_off'    => __( 'Off', 'wr-ew' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $repeater->add_control(
            'content_position',
            [
                'label'   => __( 'Overlay Content Position', 'wr-ew' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'bottom-left',
                'options' => [
                    'bottom-left'  => __( 'Bottom Left', 'wr-ew' ),
                    'bottom-center'=> __( 'Bottom Center', 'wr-ew' ),
                    'bottom-right' => __( 'Bottom Right', 'wr-ew' ),
                    'center-left'  => __( 'Center Left', 'wr-ew' ),
                    'center'       => __( 'Center', 'wr-ew' ),
                    'center-right' => __( 'Center Right', 'wr-ew' ),
                    'top-left'     => __( 'Top Left', 'wr-ew' ),
                    'top-center'   => __( 'Top Center', 'wr-ew' ),
                    'top-right'    => __( 'Top Right', 'wr-ew' ),
                ],
            ]
        );

        $this->add_control(
            'tiles',
            [
                'label'       => __( 'Tiles', 'wr-ew' ),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => $this->get_default_tiles(),
                'title_field' => '{{{ title }}}',
            ]
        );

        foreach ( $this->get_preset_hints() as $preset_key => $preset_hint ) {
            $this->add_control(
                'image_hint_' . $preset_key,
                [
                    'type'            => Controls_Manager::RAW_HTML,
                    'raw'             => esc_html( sprintf( __( 'Recommended images for this preset: %s', 'wr-ew' ), $preset_hint ) ),
                    'content_classes' => 'wr-pgfull__preset-hint',
                    'condition'       => [ 'preset' => $preset_key ],
                ]
            );
        }

        $this->end_controls_section();
    }

    private function register_style_controls() {
        $this->start_controls_section(
            'section_container',
            [
                'label' => __( 'Container', 'wr-ew' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'container_min_height',
            [
                'label'      => __( 'Container Min Height', 'wr-ew' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => 200, 'max' => 900 ] ],
                'default'        => [ 'size' => 360, 'unit' => 'px' ],
                'tablet_default' => [ 'size' => 300, 'unit' => 'px' ],
                'mobile_default' => [ 'size' => 240, 'unit' => 'px' ],
                'selectors'      => [
                    '{{WRAPPER}} .wr-promo-grid-full' => 'min-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'fixed_height',
            [
                'label'        => __( 'Fixed Height Mode', 'wr-ew' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', 'wr-ew' ),
                'label_off'    => __( 'Off', 'wr-ew' ),
                'return_value' => 'yes',
                'default'      => '',
            ]
        );

        $this->add_responsive_control(
            'container_height',
            [
                'label'      => __( 'Container Height', 'wr-ew' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => 200, 'max' => 900 ] ],
                'default'        => [ 'size' => 520, 'unit' => 'px' ],
                'tablet_default' => [ 'size' => 420, 'unit' => 'px' ],
                'condition'      => [ 'fixed_height' => 'yes' ],
                'selectors'      => [
                    '{{WRAPPER}} .wr-promo-grid-full.wr-pgfull--has-fixed-height' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'container_width_mode',
            [
                'label'   => __( 'Container Width Mode', 'wr-ew' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'auto',
                'options' => [
                    'auto'   => __( 'Auto', 'wr-ew' ),
                    'custom' => __( 'Custom', 'wr-ew' ),
                ],
            ]
        );

        $this->add_responsive_control(
            'container_max_width',
            [
                'label'      => __( 'Container Max Width', 'wr-ew' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => 600, 'max' => 1920 ] ],
                'condition'  => [ 'container_width_mode' => 'custom' ],
                'selectors'  => [
                    '{{WRAPPER}} .wr-promo-grid-full .wr-pgfull__inner' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'container_padding',
            [
                'label'      => __( 'Container Padding', 'wr-ew' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .wr-promo-grid-full .wr-pgfull__inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_grid',
            [
                'label' => __( 'Grid', 'wr-ew' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'grid_gap',
            [
                'label'      => __( 'Grid Gap', 'wr-ew' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => 0, 'max' => 80 ] ],
                'default'        => [ 'size' => 20, 'unit' => 'px' ],
                'tablet_default' => [ 'size' => 16, 'unit' => 'px' ],
                'mobile_default' => [ 'size' => 12, 'unit' => 'px' ],
                'selectors'      => [
                    '{{WRAPPER}} .wr-promo-grid-full .wr-pgfull__grid' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'tile_min_height',
            [
                'label'      => __( 'Tile Min Height', 'wr-ew' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => 100, 'max' => 900 ] ],
                'default'        => [ 'size' => 340, 'unit' => 'px' ],
                'tablet_default' => [ 'size' => 280, 'unit' => 'px' ],
                'mobile_default' => [ 'size' => 220, 'unit' => 'px' ],
                'selectors'      => [
                    '{{WRAPPER}} .wr-promo-grid-full .wr-pgfull__tile' => 'min-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_tile',
            [
                'label' => __( 'Tile', 'wr-ew' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'border_radius',
            [
                'label'      => __( 'Border Radius', 'wr-ew' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => 0, 'max' => 100 ] ],
                'default'    => [ 'size' => 16, 'unit' => 'px' ],
                'selectors'  => [
                    '{{WRAPPER}} .wr-promo-grid-full .wr-pgfull__tile'         => 'border-radius: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .wr-promo-grid-full .wr-pgfull__media'        => 'border-radius: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .wr-promo-grid-full .wr-pgfull__media img'    => 'border-radius: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .wr-promo-grid-full .wr-pgfull__overlay'      => 'border-radius: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .wr-promo-grid-full .wr-pgfull__content'      => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'hover_effect',
            [
                'label'   => __( 'Hover Effect', 'wr-ew' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none' => __( 'None', 'wr-ew' ),
                    'zoom' => __( 'Zoom', 'wr-ew' ),
                    'dim'  => __( 'Dim', 'wr-ew' ),
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_media',
            [
                'label' => __( 'Media', 'wr-ew' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'object_fit',
            [
                'label'   => __( 'Object Fit', 'wr-ew' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'cover',
                'options' => [
                    'cover'   => __( 'Cover', 'wr-ew' ),
                    'contain' => __( 'Contain', 'wr-ew' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .wr-promo-grid-full .wr-pgfull__media img' => 'object-fit: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'media_zoom',
            [
                'label'      => __( 'Media Zoom', 'wr-ew' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => 1, 'max' => 1.3, 'step' => 0.05 ] ],
                'default'    => [ 'size' => 1 ],
                'selectors'  => [
                    '{{WRAPPER}} .wr-promo-grid-full' => '--wr-pgfull-media-scale: {{SIZE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_overlay',
            [
                'label' => __( 'Overlay', 'wr-ew' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'overlay_color',
            [
                'label'     => __( 'Overlay Color', 'wr-ew' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-promo-grid-full' => '--wr-pgfull-overlay-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'overlay_opacity',
            [
                'label'   => __( 'Overlay Opacity', 'wr-ew' ),
                'type'    => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'   => [ 'px' => [ 'min' => 0, 'max' => 1, 'step' => 0.05 ] ],
                'default' => [ 'size' => 0.35 ],
                'selectors' => [
                    '{{WRAPPER}} .wr-promo-grid-full' => '--wr-pgfull-overlay-opacity: {{SIZE}};',
                ],
            ]
        );

        $this->add_control(
            'overlay_text_color',
            [
                'label'     => __( 'Text Color', 'wr-ew' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-promo-grid-full .wr-pgfull__content' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .wr-promo-grid-full .wr-pgfull__content a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .wr-promo-grid-full .wr-pgfull__content span' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_typography',
            [
                'label' => __( 'Typography', 'wr-ew' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} .wr-promo-grid-full .wr-pgfull__title',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label'     => __( 'Title Color', 'wr-ew' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-promo-grid-full .wr-pgfull__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'subtitle_typography',
                'selector' => '{{WRAPPER}} .wr-promo-grid-full .wr-pgfull__subtitle',
            ]
        );

        $this->add_control(
            'subtitle_color',
            [
                'label'     => __( 'Subtitle Color', 'wr-ew' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-promo-grid-full .wr-pgfull__subtitle' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'button_typography',
                'selector' => '{{WRAPPER}} .wr-promo-grid-full .wr-pgfull__button',
            ]
        );

        $this->start_controls_tabs( 'button_colors_tabs' );

        $this->start_controls_tab(
            'button_normal',
            [ 'label' => __( 'Normal', 'wr-ew' ) ]
        );

        $this->add_control(
            'button_text_color',
            [
                'label'     => __( 'Button Text Color', 'wr-ew' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-promo-grid-full .wr-pgfull__button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_bg_color',
            [
                'label'     => __( 'Button Background Color', 'wr-ew' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-promo-grid-full .wr-pgfull__button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'button_hover',
            [ 'label' => __( 'Hover', 'wr-ew' ) ]
        );

        $this->add_control(
            'button_text_color_hover',
            [
                'label'     => __( 'Button Text Color (Hover)', 'wr-ew' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-promo-grid-full .wr-pgfull__button:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_bg_color_hover',
            [
                'label'     => __( 'Button Background Color (Hover)', 'wr-ew' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-promo-grid-full .wr-pgfull__button:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'section_content_position',
            [
                'label' => __( 'Content Position', 'wr-ew' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'content_block_max_width',
            [
                'label'      => __( 'Content Block Max Width', 'wr-ew' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => 120, 'max' => 800 ] ],
                'selectors'  => [
                    '{{WRAPPER}} .wr-promo-grid-full .wr-pgfull__content' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_padding',
            [
                'label'      => __( 'Content Padding', 'wr-ew' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .wr-promo-grid-full .wr-pgfull__content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'offset_x',
            [
                'label'      => __( 'Offset X', 'wr-ew' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => -120, 'max' => 120 ] ],
                'selectors'  => [
                    '{{WRAPPER}} .wr-promo-grid-full' => '--wr-pgfull-offset-x: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'offset_y',
            [
                'label'      => __( 'Offset Y', 'wr-ew' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => -120, 'max' => 120 ] ],
                'selectors'  => [
                    '{{WRAPPER}} .wr-promo-grid-full' => '--wr-pgfull-offset-y: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $preset       = isset( $settings['preset'] ) ? $settings['preset'] : 'three_mosaic_left';
        $max_by_preset = $this->get_preset_tile_limits();
        $max_tiles    = isset( $max_by_preset[ $preset ] ) ? $max_by_preset[ $preset ] : 6;
        $tiles        = isset( $settings['tiles'] ) ? array_slice( $settings['tiles'], 0, $max_tiles ) : [];
        $areas        = $this->get_preset_areas( $preset );

        $this->add_render_attribute( 'wrapper', 'class', 'wr-promo-grid-full' );
        $this->add_render_attribute( 'wrapper', 'data-preset', esc_attr( $preset ) );

        if ( 'yes' === ( $settings['full_width'] ?? '' ) ) {
            $this->add_render_attribute( 'wrapper', 'class', 'wr-pgfull--fullwidth' );
        }

        if ( 'yes' === ( $settings['fixed_height'] ?? '' ) ) {
            $this->add_render_attribute( 'wrapper', 'class', 'wr-pgfull--has-fixed-height' );
        }

        if ( 'custom' === ( $settings['container_width_mode'] ?? 'auto' ) ) {
            $this->add_render_attribute( 'wrapper', 'class', 'wr-pgfull--custom-width' );
        }

        if ( ! empty( $settings['hover_effect'] ) && 'none' !== $settings['hover_effect'] ) {
            $this->add_render_attribute( 'wrapper', 'class', 'wr-pgfull--hover-' . esc_attr( $settings['hover_effect'] ) );
        }

        $grid_classes = [ 'wr-pgfull__grid', 'wr-pgfull__grid--' . esc_attr( $preset ) ];

        echo '<div ' . $this->get_render_attribute_string( 'wrapper' ) . '>';
        echo '<div class="wr-pgfull__inner">';
        echo '<div class="' . implode( ' ', $grid_classes ) . '">';

        $index = 0;
        foreach ( $tiles as $tile ) {
            $index++;
            if ( 'yes' !== ( $tile['show_tile'] ?? '' ) ) {
                continue;
            }

            if ( empty( $tile['image']['url'] ) ) {
                continue;
            }

            $area_key = $areas[ $index - 1 ] ?? 'a';
            $tile_tag = ! empty( $tile['link']['url'] ) ? 'a' : 'div';
            $tile_id  = 'wr-pgfull-tile-' . $index;

            $this->add_render_attribute( $tile_id, 'class', [ 'wr-pgfull__tile', 'wr-pgfull__tile--' . $area_key ] );
            $this->add_render_attribute( $tile_id, 'class', 'wr-pgfull__tile-position-' . esc_attr( $tile['content_position'] ?? 'bottom-left' ) );

            if ( 'a' === $tile_tag ) {
                $this->add_render_attribute( $tile_id, 'href', esc_url( $tile['link']['url'] ) );
                if ( 'yes' === ( $tile['open_in_new_tab'] ?? '' ) || ! empty( $tile['link']['is_external'] ) ) {
                    $this->add_render_attribute( $tile_id, 'target', '_blank' );
                    $this->add_render_attribute( $tile_id, 'rel', 'noopener noreferrer' );
                }

                if ( ! empty( $tile['link']['nofollow'] ) ) {
                    $this->add_render_attribute( $tile_id, 'rel', 'nofollow' );
                }
            }

            echo '<' . $tile_tag . ' ' . $this->get_render_attribute_string( $tile_id ) . '>';

            echo '<div class="wr-pgfull__media">';
            echo '<img src="' . esc_url( $tile['image']['url'] ) . '" alt="' . esc_attr( $tile['title'] ?? '' ) . '" loading="lazy" />';
            echo '</div>';

            $overlay_classes = [ 'wr-pgfull__overlay' ];
            if ( 'yes' !== ( $tile['overlay'] ?? 'yes' ) ) {
                $overlay_classes[] = 'wr-pgfull__overlay--disabled';
            }

            echo '<div class="' . implode( ' ', $overlay_classes ) . '">';
            echo '<div class="wr-pgfull__content wr-pgfull__content--' . esc_attr( $tile['content_position'] ?? 'bottom-left' ) . '">';

            if ( ! empty( $tile['title'] ) ) {
                echo '<div class="wr-pgfull__title">' . esc_html( $tile['title'] ) . '</div>';
            }

            if ( ! empty( $tile['subtitle'] ) ) {
                echo '<div class="wr-pgfull__subtitle">' . esc_html( $tile['subtitle'] ) . '</div>';
            }

            if ( ! empty( $tile['button_text'] ) ) {
                $button_tag = ( 'a' !== $tile_tag && ! empty( $tile['link']['url'] ) ) ? 'a' : 'span';
                $button_attributes = 'class="wr-pgfull__button"';
                $button_rels       = [];

                if ( 'a' === $button_tag ) {
                    $button_attributes .= ' href="' . esc_url( $tile['link']['url'] ) . '"';
                    if ( 'yes' === ( $tile['open_in_new_tab'] ?? '' ) || ! empty( $tile['link']['is_external'] ) ) {
                        $button_attributes .= ' target="_blank"';
                        $button_rels[] = 'noopener';
                        $button_rels[] = 'noreferrer';
                    }

                    if ( ! empty( $tile['link']['nofollow'] ) ) {
                        $button_rels[] = 'nofollow';
                    }

                    if ( ! empty( $button_rels ) ) {
                        $button_attributes .= ' rel="' . esc_attr( implode( ' ', array_unique( $button_rels ) ) ) . '"';
                    }
                }

                echo '<' . $button_tag . ' ' . $button_attributes . '>' . esc_html( $tile['button_text'] ) . '</' . $button_tag . '>';
            }

            echo '</div>';
            echo '</div>';
            echo '</' . $tile_tag . '>';
        }

        echo '</div>';
        echo '</div>';
        echo '</div>';
    }

    private function get_default_tiles() {
        $defaults = [];
        for ( $i = 1; $i <= 3; $i++ ) {
            $defaults[] = [
                'show_tile'       => 'yes',
                'title'           => sprintf( __( 'Tile %d Title', 'wr-ew' ), $i ),
                'subtitle'        => __( 'Add subtitle here', 'wr-ew' ),
                'button_text'     => __( 'Learn More', 'wr-ew' ),
                'overlay'         => 'yes',
                'content_position'=> 'bottom-left',
            ];
        }

        return $defaults;
    }

    private function get_preset_tile_limits() {
        return [
            'two_equal'         => 2,
            'two_split_70_30'   => 2,
            'two_split_30_70'   => 2,
            'three_equal'       => 3,
            'three_mosaic_left' => 3,
            'three_mosaic_right'=> 3,
            'four_checker'      => 4,
            'four_mosaic_left'  => 4,
            'four_mosaic_right' => 4,
            'six_grid'          => 6,
        ];
    }

    private function get_preset_areas( $preset ) {
        $map = [
            'two_equal'          => [ 'a', 'b' ],
            'two_split_70_30'    => [ 'a', 'b' ],
            'two_split_30_70'    => [ 'a', 'b' ],
            'three_equal'        => [ 'a', 'b', 'c' ],
            'three_mosaic_left'  => [ 'a', 'b', 'c' ],
            'three_mosaic_right' => [ 'a', 'b', 'c' ],
            'four_checker'       => [ 'a', 'b', 'c', 'd' ],
            'four_mosaic_left'   => [ 'a', 'b', 'c', 'd' ],
            'four_mosaic_right'  => [ 'a', 'b', 'c', 'd' ],
            'six_grid'           => [ 'a', 'b', 'c', 'd', 'e', 'f' ],
        ];

        return $map[ $preset ] ?? [ 'a', 'b', 'c', 'd', 'e', 'f' ];
    }

    private function get_preset_hints() {
        return [
            'two_equal'          => 'A=1200x800, B=1200x800',
            'two_split_70_30'    => 'A=1400x900, B=900x900',
            'two_split_30_70'    => 'A=900x900, B=1400x900',
            'three_equal'        => 'A=1000x800, B=1000x800, C=1000x800',
            'three_mosaic_left'  => 'A=1400x900, B=900x700, C=900x700',
            'three_mosaic_right' => 'A=1400x900, B=900x700, C=900x700',
            'four_checker'       => 'A=900x700, B=900x700, C=900x700, D=900x700',
            'four_mosaic_left'   => 'A=1400x1100, B=900x700, C=900x700, D=900x700',
            'four_mosaic_right'  => 'A=1400x1100, B=900x700, C=900x700, D=900x700',
            'six_grid'           => 'A=800x600, B=800x600, C=800x600, D=800x600, E=800x600, F=800x600',
        ];
    }
}
