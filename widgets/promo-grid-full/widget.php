<?php
if ( ! defined( 'ABSPATH' ) ) exit;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;

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
        wp_register_style(
            self::STYLE_HANDLE,
            WR_EW_PLUGIN_URL . 'assets/css/promo-grid-full.css',
            [],
            '1.0.0'
        );

        return [ self::STYLE_HANDLE ];
    }

    public function get_script_depends() {
        wp_register_script(
            self::SCRIPT_HANDLE,
            WR_EW_PLUGIN_URL . 'assets/js/promo-grid-full.js',
            [ 'jquery' ],
            '1.0.0',
            true
        );

        return [ self::SCRIPT_HANDLE ];
    }

    protected function register_controls() {
        $this->register_content_controls();
        $this->register_preset_requirements_controls();
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
                'label'   => __( 'Preset', 'wr-ew' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'two_equal',
                'options' => [
                    'two_equal'         => __( 'Two Equal', 'wr-ew' ),
                    'two_split_70_30'   => __( 'Two Split 70/30', 'wr-ew' ),
                    'two_split_30_70'   => __( 'Two Split 30/70', 'wr-ew' ),
                    'three_equal'       => __( 'Three Equal', 'wr-ew' ),
                    'three_mosaic_left' => __( 'Three Mosaic Left', 'wr-ew' ),
                    'three_mosaic_right'=> __( 'Three Mosaic Right', 'wr-ew' ),
                    'four_checker'      => __( 'Four Checker', 'wr-ew' ),
                    'four_mosaic_left'  => __( 'Four Mosaic Left', 'wr-ew' ),
                    'four_mosaic_right' => __( 'Four Mosaic Right', 'wr-ew' ),
                    'six_grid'          => __( 'Six Grid', 'wr-ew' ),
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
            'image',
            [
                'label'   => __( 'Image', 'wr-ew' ),
                'type'    => Controls_Manager::MEDIA,
                'default' => [],
            ]
        );

        $repeater->add_control(
            'overlay',
            [
                'label'        => __( 'Enable Overlay', 'wr-ew' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'Show', 'wr-ew' ),
                'label_off'    => __( 'Hide', 'wr-ew' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $repeater->add_control(
            'content_position',
            [
                'label'   => __( 'Content Position', 'wr-ew' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'bottom_left',
                'options' => [
                    'top_left'     => __( 'Top Left', 'wr-ew' ),
                    'top_right'    => __( 'Top Right', 'wr-ew' ),
                    'bottom_left'  => __( 'Bottom Left', 'wr-ew' ),
                    'bottom_right' => __( 'Bottom Right', 'wr-ew' ),
                    'center'       => __( 'Center', 'wr-ew' ),
                ],
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
                'type'        => Controls_Manager::TEXTAREA,
                'rows'        => 2,
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
            'tile_clickable',
            [
                'label'        => __( 'Tile Clickable (Whole Tile)', 'wr-ew' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'Yes', 'wr-ew' ),
                'label_off'    => __( 'No', 'wr-ew' ),
                'return_value' => 'yes',
                'default'      => '',
            ]
        );

        $repeater->add_control(
            'content_max_width',
            [
                'label'      => __( 'Content Max Width (px)', 'wr-ew' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min' => 120,
                        'max' => 1200,
                    ],
                ],
                'default'    => [
                    'size' => '',
                    'unit' => 'px',
                ],
            ]
        );

        $repeater->add_responsive_control(
            'content_padding',
            [
                'label'      => __( 'Content Padding', 'wr-ew' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'default'    => [
                    'top'      => '',
                    'right'    => '',
                    'bottom'   => '',
                    'left'     => '',
                    'isLinked' => true,
                ],
            ]
        );

        $this->add_control(
            'tiles',
            [
                'label'       => __( 'Promo Tiles', 'wr-ew' ),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [],
                'title_field' => '{{{ title || "Tile" }}}',
            ]
        );

        $this->end_controls_section();
    }

    private function register_preset_requirements_controls() {
        $this->start_controls_section(
            'section_preset_requirements',
            [
                'label' => __( 'Preset Image Requirements', 'wr-ew' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'preset_two_equal_info',
            [
                'type'            => Controls_Manager::RAW_HTML,
                'raw'             => __(
                    '<strong>Two Equal</strong><br/>A: 1600×900 (16:9)<br/>B: 1600×900 (16:9)<br/>Images will be cropped to fit; use recommended aspect ratio for best results.',
                    'wr-ew'
                ),
                'content_classes' => 'wr-pgfull__preset-note',
                'condition'       => [ 'preset' => 'two_equal' ],
            ]
        );

        $this->add_control(
            'preset_two_split_70_30_info',
            [
                'type'            => Controls_Manager::RAW_HTML,
                'raw'             => __(
                    '<strong>Two Split 70/30</strong><br/>A (wide): 2000×1125 (16:9)<br/>B (narrow): 1000×1125 (8:9)<br/>Images will be cropped to fit; use recommended aspect ratio for best results.',
                    'wr-ew'
                ),
                'content_classes' => 'wr-pgfull__preset-note',
                'condition'       => [ 'preset' => 'two_split_70_30' ],
            ]
        );

        $this->add_control(
            'preset_two_split_30_70_info',
            [
                'type'            => Controls_Manager::RAW_HTML,
                'raw'             => __(
                    '<strong>Two Split 30/70</strong><br/>A (narrow): 1000×1125 (8:9)<br/>B (wide): 2000×1125 (16:9)<br/>Images will be cropped to fit; use recommended aspect ratio for best results.',
                    'wr-ew'
                ),
                'content_classes' => 'wr-pgfull__preset-note',
                'condition'       => [ 'preset' => 'two_split_30_70' ],
            ]
        );

        $this->add_control(
            'preset_three_equal_info',
            [
                'type'            => Controls_Manager::RAW_HTML,
                'raw'             => __(
                    '<strong>Three Equal</strong><br/>A: 1200×900 (4:3)<br/>B: 1200×900 (4:3)<br/>C: 1200×900 (4:3)<br/>Images will be cropped to fit; use recommended aspect ratio for best results.',
                    'wr-ew'
                ),
                'content_classes' => 'wr-pgfull__preset-note',
                'condition'       => [ 'preset' => 'three_equal' ],
            ]
        );

        $this->add_control(
            'preset_three_mosaic_left_info',
            [
                'type'            => Controls_Manager::RAW_HTML,
                'raw'             => __(
                    '<strong>Three Mosaic Left</strong><br/>A (tall): 1200×1600 (3:4)<br/>B: 1200×800 (3:2)<br/>C: 1200×800 (3:2)<br/>Images will be cropped to fit; use recommended aspect ratio for best results.',
                    'wr-ew'
                ),
                'content_classes' => 'wr-pgfull__preset-note',
                'condition'       => [ 'preset' => 'three_mosaic_left' ],
            ]
        );

        $this->add_control(
            'preset_three_mosaic_right_info',
            [
                'type'            => Controls_Manager::RAW_HTML,
                'raw'             => __(
                    '<strong>Three Mosaic Right</strong><br/>A: 1200×800 (3:2)<br/>B (tall): 1200×1600 (3:4)<br/>C: 1200×800 (3:2)<br/>Images will be cropped to fit; use recommended aspect ratio for best results.',
                    'wr-ew'
                ),
                'content_classes' => 'wr-pgfull__preset-note',
                'condition'       => [ 'preset' => 'three_mosaic_right' ],
            ]
        );

        $this->add_control(
            'preset_four_checker_info',
            [
                'type'            => Controls_Manager::RAW_HTML,
                'raw'             => __(
                    '<strong>Four Checker</strong><br/>A: 1200×900 (4:3)<br/>B: 1200×900 (4:3)<br/>C: 1200×900 (4:3)<br/>D: 1200×900 (4:3)<br/>Images will be cropped to fit; use recommended aspect ratio for best results.',
                    'wr-ew'
                ),
                'content_classes' => 'wr-pgfull__preset-note',
                'condition'       => [ 'preset' => 'four_checker' ],
            ]
        );

        $this->add_control(
            'preset_four_mosaic_left_info',
            [
                'type'            => Controls_Manager::RAW_HTML,
                'raw'             => __(
                    '<strong>Four Mosaic Left</strong><br/>A (tall): 1200×1600 (3:4)<br/>B: 1200×800 (3:2)<br/>C (tall-ish): 1200×1200 (1:1)<br/>D: 1200×800 (3:2)<br/>Images will be cropped to fit; use recommended aspect ratio for best results.',
                    'wr-ew'
                ),
                'content_classes' => 'wr-pgfull__preset-note',
                'condition'       => [ 'preset' => 'four_mosaic_left' ],
            ]
        );

        $this->add_control(
            'preset_four_mosaic_right_info',
            [
                'type'            => Controls_Manager::RAW_HTML,
                'raw'             => __(
                    '<strong>Four Mosaic Right</strong><br/>A: 1200×800 (3:2)<br/>B (tall): 1200×1600 (3:4)<br/>C (tall-ish): 1200×1200 (1:1)<br/>D: 1200×800 (3:2)<br/>Images will be cropped to fit; use recommended aspect ratio for best results.',
                    'wr-ew'
                ),
                'content_classes' => 'wr-pgfull__preset-note',
                'condition'       => [ 'preset' => 'four_mosaic_right' ],
            ]
        );

        $this->add_control(
            'preset_six_grid_info',
            [
                'type'            => Controls_Manager::RAW_HTML,
                'raw'             => __(
                    '<strong>Six Grid</strong><br/>A: 1200×900 (4:3)<br/>B: 1200×900 (4:3)<br/>C: 1200×900 (4:3)<br/>D: 1200×900 (4:3)<br/>E: 1200×900 (4:3)<br/>F: 1200×900 (4:3)<br/>Images will be cropped to fit; use recommended aspect ratio for best results.',
                    'wr-ew'
                ),
                'content_classes' => 'wr-pgfull__preset-note',
                'condition'       => [ 'preset' => 'six_grid' ],
            ]
        );

        $this->end_controls_section();
    }

    private function register_style_controls() {
        // Container
        $this->start_controls_section(
            'section_container_style',
            [
                'label' => __( 'Container', 'wr-ew' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'container_min_height',
            [
                'label'      => __( 'Min Height', 'wr-ew' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [ 'min' => 200, 'max' => 1200 ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .wr-pgfull' => '--wr-pgfull-min-h: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'width_mode',
            [
                'label'   => __( 'Width Mode', 'wr-ew' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'boxed',
                'options' => [
                    'boxed' => __( 'Boxed', 'wr-ew' ),
                    'full'  => __( 'Full Width', 'wr-ew' ),
                ],
            ]
        );

        $this->add_responsive_control(
            'container_padding',
            [
                'label'      => __( 'Padding', 'wr-ew' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .wr-pgfull__inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Grid
        $this->start_controls_section(
            'section_grid_style',
            [
                'label' => __( 'Grid', 'wr-ew' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'grid_gap',
            [
                'label'      => __( 'Gap', 'wr-ew' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [ 'min' => 0, 'max' => 80 ],
                ],
                'default'    => [
                    'size' => 16,
                    'unit' => 'px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .wr-pgfull' => '--wr-pgfull-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'tile_min_height',
            [
                'label'      => __( 'Tile Min Height', 'wr-ew' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [ 'min' => 120, 'max' => 900 ],
                ],
                'default'    => [
                    'size' => 360,
                    'unit' => 'px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .wr-pgfull' => '--wr-pgfull-tile-min: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Tile
        $this->start_controls_section(
            'section_tile_style',
            [
                'label' => __( 'Tile', 'wr-ew' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'tile_radius',
            [
                'label' => __( 'Border Radius', 'wr-ew' ),
                'type'  => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 80 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wr-pgfull' => '--wr-pgfull-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'tile_hover_effect',
            [
                'label'   => __( 'Hover Effect', 'wr-ew' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none' => __( 'None', 'wr-ew' ),
                    'lift' => __( 'Lift', 'wr-ew' ),
                    'glow' => __( 'Glow', 'wr-ew' ),
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'tile_shadow',
                'selector' => '{{WRAPPER}} .wr-pgfull__tile',
            ]
        );

        $this->end_controls_section();

        // Media
        $this->start_controls_section(
            'section_media_style',
            [
                'label' => __( 'Media', 'wr-ew' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'media_object_fit',
            [
                'label'   => __( 'Object Fit', 'wr-ew' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'cover',
                'options' => [
                    'cover'   => __( 'Cover', 'wr-ew' ),
                    'contain' => __( 'Contain', 'wr-ew' ),
                    'fill'    => __( 'Fill', 'wr-ew' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .wr-pgfull' => '--wr-pgfull-object-fit: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'media_zoom',
            [
                'label'   => __( 'Global Media Zoom', 'wr-ew' ),
                'type'    => Controls_Manager::SLIDER,
                'size_units' => [ '%' ],
                'range'   => [
                    '%' => [ 'min' => 0, 'max' => 100 ],
                ],
                'default' => [
                    'size' => 50,
                    'unit' => '%',
                ],
                'description' => __( '0 = zoom-out (0.80), 50 = normal (1.00), 100 = zoom-in (1.40)', 'wr-ew' ),
            ]
        );

        $this->end_controls_section();

        // Overlay
        $this->start_controls_section(
            'section_overlay_style',
            [
                'label' => __( 'Overlay', 'wr-ew' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'overlay_color',
            [
                'label' => __( 'Overlay Color', 'wr-ew' ),
                'type'  => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-pgfull' => '--wr-pgfull-overlay-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'overlay_opacity',
            [
                'label' => __( 'Overlay Opacity', 'wr-ew' ),
                'type'  => Controls_Manager::SLIDER,
                'size_units' => [ '%' ],
                'range' => [
                    '%' => [ 'min' => 0, 'max' => 100 ],
                ],
                'default' => [
                    'size' => 35,
                    'unit' => '%',
                ],
                'selectors' => [
                    '{{WRAPPER}} .wr-pgfull' => '--wr-pgfull-overlay-opacity: calc({{SIZE}} / 100);',
                ],
            ]
        );

        $this->add_control(
            'content_text_color',
            [
                'label' => __( 'Text Color', 'wr-ew' ),
                'type'  => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-pgfull' => '--wr-pgfull-text-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Typography
        $this->start_controls_section(
            'section_typography_style',
            [
                'label' => __( 'Typography', 'wr-ew' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} .wr-pgfull__title',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __( 'Title Color', 'wr-ew' ),
                'type'  => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-pgfull' => '--wr-pgfull-title-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'subtitle_typography',
                'selector' => '{{WRAPPER}} .wr-pgfull__subtitle',
            ]
        );

        $this->add_control(
            'subtitle_color',
            [
                'label' => __( 'Subtitle Color', 'wr-ew' ),
                'type'  => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-pgfull' => '--wr-pgfull-subtitle-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'button_typography',
                'selector' => '{{WRAPPER}} .wr-pgfull__button',
            ]
        );

        $this->add_control(
            'button_text_color',
            [
                'label' => __( 'Button Text Color', 'wr-ew' ),
                'type'  => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-pgfull' => '--wr-pgfull-button-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_bg_color',
            [
                'label' => __( 'Button Background', 'wr-ew' ),
                'type'  => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-pgfull' => '--wr-pgfull-button-bg: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Content Position
        $this->start_controls_section(
            'section_content_position_style',
            [
                'label' => __( 'Content Position', 'wr-ew' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'content_offset_x',
            [
                'label'      => __( 'Offset X', 'wr-ew' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range'      => [
                    'px' => [ 'min' => -200, 'max' => 200 ],
                    '%'  => [ 'min' => -100, 'max' => 100 ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .wr-pgfull' => '--wr-pgfull-offset-x: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'content_offset_y',
            [
                'label'      => __( 'Offset Y', 'wr-ew' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range'      => [
                    'px' => [ 'min' => -200, 'max' => 200 ],
                    '%'  => [ 'min' => -100, 'max' => 100 ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .wr-pgfull' => '--wr-pgfull-offset-y: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_block_width',
            [
                'label'      => __( 'Content Max Width', 'wr-ew' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range'      => [
                    'px' => [ 'min' => 120, 'max' => 1200 ],
                    '%'  => [ 'min' => 10, 'max' => 100 ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .wr-pgfull' => '--wr-pgfull-block-max: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_block_padding',
            [
                'label'      => __( 'Content Padding', 'wr-ew' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .wr-pgfull' => '--wr-pgfull-block-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $preset     = ! empty( $settings['preset'] ) ? $settings['preset'] : 'two_equal';
        $width_mode = ! empty( $settings['width_mode'] ) ? $settings['width_mode'] : 'boxed';
        $tiles      = is_array( $settings['tiles'] ?? [] ) ? $settings['tiles'] : [];

        $zoom_setting = isset( $settings['media_zoom']['size'] ) ? floatval( $settings['media_zoom']['size'] ) : 50;
        $zoom_scale   = $this->calculate_zoom_scale( $zoom_setting );

        $wrapper_classes = [
            'wr-pgfull',
            'wr-pgfull--preset-' . esc_attr( $preset ),
        ];

        if ( 'full' === $width_mode ) {
            $wrapper_classes[] = 'wr-pgfull--full';
        }

        if ( ! empty( $settings['tile_hover_effect'] ) && 'none' !== $settings['tile_hover_effect'] ) {
            $wrapper_classes[] = 'wr-pgfull--hover-' . esc_attr( $settings['tile_hover_effect'] );
        }

        $wrapper_style = sprintf( '--wr-pgfull-media-zoom: %s;', esc_attr( $zoom_scale ) );

        echo '<div class="' . esc_attr( implode( ' ', $wrapper_classes ) ) . '" style="' . $wrapper_style . '">';
        echo '<div class="wr-pgfull__inner">';
        echo '<div class="wr-pgfull__grid">';

        $letters = [ 'a', 'b', 'c', 'd', 'e', 'f' ];

        foreach ( $tiles as $index => $tile ) {
            $this->render_tile( $tile, $letters[ $index ] ?? '' );
        }

        echo '</div>';
        echo '</div>';
        echo '</div>';
    }

    private function render_tile( $tile, $letter ) {
        $image   = $tile['image']['url'] ?? '';
        $overlay = isset( $tile['overlay'] ) && 'yes' === $tile['overlay'];

        $title    = $tile['title'] ?? '';
        $subtitle = $tile['subtitle'] ?? '';
        $button   = $tile['button_text'] ?? '';
        $position = $tile['content_position'] ?? 'bottom_left';

        $link_settings = $tile['link'] ?? [];
        $link_url      = $link_settings['url'] ?? '';
        $is_external   = ! empty( $link_settings['is_external'] );
        $nofollow      = ! empty( $link_settings['nofollow'] );

        $tile_clickable = ! empty( $tile['tile_clickable'] ) && ! empty( $link_url );

        $tag = $tile_clickable ? 'a' : 'div';

        $tile_attrs = '';
        if ( $tile_clickable ) {
            $tile_attrs .= ' href="' . esc_url( $link_url ) . '"';
            $tile_attrs .= $is_external ? ' target="_blank"' : '';

            $rel = [];
            if ( $is_external ) {
                $rel[] = 'noopener';
                $rel[] = 'noreferrer';
            }
            if ( $nofollow ) {
                $rel[] = 'nofollow';
            }

            if ( ! empty( $rel ) ) {
                $tile_attrs .= ' rel="' . esc_attr( implode( ' ', array_unique( $rel ) ) ) . '"';
            }
        }

        $inline_style = '';

        $content_max_width = $tile['content_max_width']['size'] ?? '';
        $content_max_unit  = $tile['content_max_width']['unit'] ?? 'px';
        if ( '' !== $content_max_width && is_numeric( $content_max_width ) ) {
            $inline_style .= '--wr-pgfull-block-max: ' . esc_attr( $content_max_width . $content_max_unit ) . ';';
        }

        $padding = $this->build_padding_value( $tile['content_padding'] ?? [] );
        if ( $padding ) {
            $inline_style .= '--wr-pgfull-block-padding: ' . esc_attr( $padding ) . ';';
        }

        $tile_classes = [ 'wr-pgfull__tile' ];
        if ( $letter ) {
            $tile_classes[] = 'wr-pgfull__tile--' . esc_attr( $letter );
        }

        echo '<' . tag_escape( $tag ) . ' class="' . esc_attr( implode( ' ', $tile_classes ) ) . '"' . $tile_attrs;
        if ( ! empty( $inline_style ) ) {
            echo ' style="' . $inline_style . '"';
        }
        echo '>';

        echo '<div class="wr-pgfull__tile-body">';

        if ( $image ) {
            echo '<div class="wr-pgfull__media">';
            echo '<img src="' . esc_url( $image ) . '" alt="' . esc_attr( $title ) . '" loading="lazy" />';
            echo '</div>';
        }

        if ( $overlay ) {
            echo '<div class="wr-pgfull__overlay"></div>';
        }

        $has_content = $title || $subtitle || ( ! $tile_clickable && $button && $link_url );
        if ( $has_content ) {
            $content_classes = [
                'wr-pgfull__content',
                'wr-pgfull__content--' . esc_attr( $position ),
            ];

            echo '<div class="' . esc_attr( implode( ' ', $content_classes ) ) . '">';

            if ( $title ) {
                echo '<div class="wr-pgfull__title">' . esc_html( $title ) . '</div>';
            }

            if ( $subtitle ) {
                echo '<div class="wr-pgfull__subtitle">' . esc_html( $subtitle ) . '</div>';
            }

            if ( ! $tile_clickable && $button && $link_url ) {
                $rel = [];
                if ( $is_external ) {
                    $rel[] = 'noopener';
                    $rel[] = 'noreferrer';
                }
                if ( $nofollow ) {
                    $rel[] = 'nofollow';
                }

                echo '<a class="wr-pgfull__button" href="' . esc_url( $link_url ) . '"';
                echo $is_external ? ' target="_blank"' : '';

                if ( ! empty( $rel ) ) {
                    echo ' rel="' . esc_attr( implode( ' ', array_unique( $rel ) ) ) . '"';
                }

                echo '>' . esc_html( $button ) . '</a>';
            }

            echo '</div>';
        }

        echo '</div>';
        echo '</' . tag_escape( $tag ) . '>';
    }

    private function build_padding_value( $padding ) {
        if ( empty( $padding ) || ! is_array( $padding ) ) {
            return '';
        }

        $top    = isset( $padding['top'] ) && '' !== $padding['top'] ? $padding['top'] . ( $padding['unit'] ?? 'px' ) : '';
        $right  = isset( $padding['right'] ) && '' !== $padding['right'] ? $padding['right'] . ( $padding['unit'] ?? 'px' ) : '';
        $bottom = isset( $padding['bottom'] ) && '' !== $padding['bottom'] ? $padding['bottom'] . ( $padding['unit'] ?? 'px' ) : '';
        $left   = isset( $padding['left'] ) && '' !== $padding['left'] ? $padding['left'] . ( $padding['unit'] ?? 'px' ) : '';

        if ( '' === $top && '' === $right && '' === $bottom && '' === $left ) {
            return '';
        }

        return trim( implode( ' ', [ $top ?: '0', $right ?: '0', $bottom ?: '0', $left ?: '0' ] ) );
    }

    private function calculate_zoom_scale( $value ) {
        $value = max( 0, min( 100, floatval( $value ) ) );

        if ( $value <= 50 ) {
            return 0.8 + ( ( $value / 50 ) * 0.2 );
        }

        return 1.0 + ( ( ( $value - 50 ) / 50 ) * 0.4 );
    }
}
