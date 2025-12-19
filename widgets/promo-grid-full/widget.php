<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// phpcs:ignoreFile
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Utils;

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
        return [ self::STYLE_HANDLE ];
    }

    public function get_script_depends() {
        return [ self::SCRIPT_HANDLE ];
    }

    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );

        // Register assets only (no global enqueue)
        add_action( 'elementor/frontend/after_register_styles', [ $this, 'register_assets' ] );
        add_action( 'elementor/frontend/after_register_scripts', [ $this, 'register_assets' ] );
    }

    public function register_assets() {
        if ( ! wp_style_is( self::STYLE_HANDLE, 'registered' ) ) {
            wp_register_style(
                self::STYLE_HANDLE,
                WR_EW_PLUGIN_URL . 'assets/css/promo-grid-full.css',
                [],
                '1.0.0'
            );
        }

        if ( ! wp_script_is( self::SCRIPT_HANDLE, 'registered' ) ) {
            wp_register_script(
                self::SCRIPT_HANDLE,
                WR_EW_PLUGIN_URL . 'assets/js/promo-grid-full.js',
                [ 'jquery' ],
                '1.0.0',
                true
            );
        }
    }

    private function is_youtube_url( $url ) {
        if ( ! $url ) return false;
        return (bool) preg_match( '~(youtube\.com|youtu\.be)~i', $url );
    }

    private function get_youtube_id( $url ) {
        if ( ! $url ) return '';

        if ( preg_match( '~youtu\.be/([A-Za-z0-9_-]{6,})~', $url, $m ) ) {
            return $m[1];
        }

        $parts = wp_parse_url( $url );
        if ( ! empty( $parts['query'] ) ) {
            parse_str( $parts['query'], $q );
            if ( ! empty( $q['v'] ) ) {
                return preg_replace( '~[^A-Za-z0-9_-]~', '', $q['v'] );
            }
        }

        if ( preg_match( '~/(embed|shorts)/([A-Za-z0-9_-]{6,})~', $url, $m ) ) {
            return $m[2];
        }

        return '';
    }

    private function get_youtube_embed_src( $url ) {
        $id = $this->get_youtube_id( $url );
        if ( ! $id ) return '';

        $params = [
            'autoplay'       => '0',
            'controls'       => '1',
            'rel'            => '0',
            'modestbranding' => '1',
            'playsinline'    => '1',
            'enablejsapi'    => '1',
        ];

        return 'https://www.youtube-nocookie.com/embed/' . rawurlencode( $id ) . '?' . http_build_query( $params, '', '&' );
    }

    protected function register_controls() {

        /**
         * CONTENT: Layout
         */
        $this->start_controls_section(
            'section_layout',
            [
                'label' => __( 'Layout', 'wr-ew' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'layout_preset',
            [
                'label'   => __( 'Layout Preset', 'wr-ew' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'three_mosaic_left',
                'toggle'  => false,
                'options' => [
                    'two_equal'          => [ 'title' => __( 'Two Equal', 'wr-ew' ), 'icon' => 'eicon-column' ],
                    'two_split_70_30'    => [ 'title' => __( '70 / 30', 'wr-ew' ), 'icon' => 'eicon-column' ],
                    'two_split_30_70'    => [ 'title' => __( '30 / 70', 'wr-ew' ), 'icon' => 'eicon-column' ],
                    'three_equal'        => [ 'title' => __( 'Three', 'wr-ew' ), 'icon' => 'eicon-column' ],
                    'three_mosaic_left'  => [ 'title' => __( 'Mosaic L', 'wr-ew' ), 'icon' => 'eicon-gallery-grid' ],
                    'three_mosaic_right' => [ 'title' => __( 'Mosaic R', 'wr-ew' ), 'icon' => 'eicon-gallery-grid' ],
                    'four_checker'       => [ 'title' => __( 'Checker', 'wr-ew' ), 'icon' => 'eicon-gallery-grid' ],
                    'four_mosaic_left'   => [ 'title' => __( '4 Mosaic L', 'wr-ew' ), 'icon' => 'eicon-gallery-grid' ],
                    'four_mosaic_right'  => [ 'title' => __( '4 Mosaic R', 'wr-ew' ), 'icon' => 'eicon-gallery-grid' ],
                    'six_grid'           => [ 'title' => __( 'Six', 'wr-ew' ), 'icon' => 'eicon-gallery-grid' ],
                ],
            ]
        );

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
                'range'      => [ 'px' => [ 'min' => 768, 'max' => 1920 ] ],
                'default'    => [ 'size' => 1200, 'unit' => 'px' ],
                'tablet_default' => [ 'size' => 960, 'unit' => 'px' ],
                'mobile_default' => [ 'size' => 9999, 'unit' => 'px' ],
                'condition'  => [ 'full_width' => 'yes' ],
                'selectors'  => [
                    '{{WRAPPER}} .wr-pgfull__inner' => 'max-width: {{SIZE}}{{UNIT}};',
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
                'default'    => [ 'size' => 20, 'unit' => 'px' ],
                'tablet_default' => [ 'size' => 16, 'unit' => 'px' ],
                'mobile_default' => [ 'size' => 12, 'unit' => 'px' ],
                'condition'  => [ 'full_width' => 'yes' ],
                'selectors'  => [
                    '{{WRAPPER}} .wr-pgfull__inner' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}};',
                ],
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
            'tile_clickable',
            [
                'label'        => __( 'Tile Clickable (use Link on whole tile)', 'wr-ew' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', 'wr-ew' ),
                'label_off'    => __( 'Off', 'wr-ew' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $repeater->add_control(
            'tile_type',
            [
                'label'   => __( 'Tile Type', 'wr-ew' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'image',
                'options' => [
                    'image' => __( 'Image', 'wr-ew' ),
                    'video' => __( 'Video', 'wr-ew' ),
                ],
            ]
        );

        $repeater->add_control(
            'image',
            [
                'label'     => __( 'Image', 'wr-ew' ),
                'type'      => Controls_Manager::MEDIA,
                'default'   => [ 'url' => Utils::get_placeholder_image_src() ],
                'condition' => [ 'tile_type' => 'image' ],
            ]
        );

        $repeater->add_control(
            'video_url',
            [
                'label'       => __( 'Video URL', 'wr-ew' ),
                'type'        => Controls_Manager::TEXT,
                'placeholder' => 'https://example.com/video.mp4',
                'label_block' => true,
                'condition'   => [ 'tile_type' => 'video' ],
            ]
        );

        $repeater->add_control(
            'title',
            [
                'label'       => __( 'Title', 'wr-ew' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => __( 'Promo Title', 'wr-ew' ),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'subtitle',
            [
                'label'       => __( 'Subtitle', 'wr-ew' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => __( 'Short supporting copy goes here.', 'wr-ew' ),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'button_text',
            [
                'label'       => __( 'Button Text', 'wr-ew' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'link',
            [
                'label'         => __( 'Link', 'wr-ew' ),
                'type'          => Controls_Manager::URL,
                'placeholder'   => 'https://',
                'show_external' => true,
                'default'       => [ 'url' => '', 'is_external' => false, 'nofollow' => false ],
            ]
        );

        $repeater->add_control(
            'new_tab',
            [
                'label'        => __( 'Open in new tab', 'wr-ew' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', 'wr-ew' ),
                'label_off'    => __( 'Off', 'wr-ew' ),
                'return_value' => 'yes',
                'default'      => '',
            ]
        );

        $repeater->add_control(
            'overlay_enable',
            [
                'label'        => __( 'Overlay enable', 'wr-ew' ),
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
                'label'   => __( 'Overlay content position', 'wr-ew' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'bottom-left',
                'options' => [
                    'bottom-left'   => __( 'Bottom Left', 'wr-ew' ),
                    'bottom-center' => __( 'Bottom Center', 'wr-ew' ),
                    'bottom-right'  => __( 'Bottom Right', 'wr-ew' ),
                    'center-left'   => __( 'Center Left', 'wr-ew' ),
                    'center'        => __( 'Center', 'wr-ew' ),
                    'center-right'  => __( 'Center Right', 'wr-ew' ),
                    'top-left'      => __( 'Top Left', 'wr-ew' ),
                    'top-center'    => __( 'Top Center', 'wr-ew' ),
                    'top-right'     => __( 'Top Right', 'wr-ew' ),
                ],
            ]
        );

        $this->add_control(
            'tiles',
            [
                'label'          => __( 'Tiles', 'wr-ew' ),
                'type'           => Controls_Manager::REPEATER,
                'fields'         => $repeater->get_controls(),
                'prevent_empty'  => false,
                'title_field'    => '{{{ title }}}',
                'default'        => [
                    [ 'title' => __( 'Promo A', 'wr-ew' ) ],
                    [ 'title' => __( 'Promo B', 'wr-ew' ) ],
                    [ 'title' => __( 'Promo C', 'wr-ew' ) ],
                    [ 'title' => __( 'Promo D', 'wr-ew' ) ],
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * STYLE: Container
         */
        $this->start_controls_section(
            'section_style_container',
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
                'default'    => [ 'size' => 360, 'unit' => 'px' ],
                'tablet_default' => [ 'size' => 300, 'unit' => 'px' ],
                'mobile_default' => [ 'size' => 240, 'unit' => 'px' ],
                'selectors'  => [
                    '{{WRAPPER}} .wr-promo-grid-full' => 'min-height: {{SIZE}}{{UNIT}};',
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
                'condition'  => [ 'container_width_mode' => 'custom', 'full_width!' => 'yes' ],
                'selectors'  => [
                    '{{WRAPPER}} .wr-pgfull__inner' => 'max-width: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .wr-pgfull__inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * STYLE: Grid
         */
        $this->start_controls_section(
            'section_style_grid',
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
                'range'      => [ 'px' => [ 'min' => 0, 'max' => 120 ] ],
                'default'    => [ 'size' => 20, 'unit' => 'px' ],
                'tablet_default' => [ 'size' => 16, 'unit' => 'px' ],
                'mobile_default' => [ 'size' => 12, 'unit' => 'px' ],
                'selectors'  => [
                    '{{WRAPPER}} .wr-pgfull__grid' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'tile_min_height',
            [
                'label'      => __( 'Tile Min Height', 'wr-ew' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => 200, 'max' => 900 ] ],
                'default'    => [ 'size' => 340, 'unit' => 'px' ],
                'tablet_default' => [ 'size' => 280, 'unit' => 'px' ],
                'mobile_default' => [ 'size' => 220, 'unit' => 'px' ],
                'selectors'  => [
                    '{{WRAPPER}} .wr-pgfull__tile' => 'min-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * STYLE: Tile
         */
        $this->start_controls_section(
            'section_style_tile',
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
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 80 ] ],
                'default' => [ 'size' => 16, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .wr-pgfull__tile' => 'border-radius: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .wr-pgfull__media, {{WRAPPER}} .wr-pgfull__overlay' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'hover_effect',
            [
                'label'   => __( 'Hover Effect', 'wr-ew' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'zoom',
                'options' => [
                    'none' => __( 'None', 'wr-ew' ),
                    'zoom' => __( 'Zoom', 'wr-ew' ),
                    'dim'  => __( 'Dim', 'wr-ew' ),
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * STYLE: Media
         */
        $this->start_controls_section(
            'section_style_media',
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
                ],
                'selectors' => [
                    '{{WRAPPER}} .wr-pgfull__media img, {{WRAPPER}} .wr-pgfull__media video' => 'object-fit: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'media_zoom',
            [
                'label' => __( 'Media Zoom', 'wr-ew' ),
                'type'  => Controls_Manager::SLIDER,
                'size_units' => [ 'custom' ],
                'range' => [ 'custom' => [ 'min' => 1.0, 'max' => 1.3, 'step' => 0.05 ] ],
                'default' => [ 'size' => 1.0, 'unit' => '' ],
                'selectors' => [
                    '{{WRAPPER}} .wr-promo-grid-full' => '--wr-pgfull-media-zoom: {{SIZE}};',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * STYLE: Overlay
         */
        $this->start_controls_section(
            'section_style_overlay',
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
                    '{{WRAPPER}} .wr-pgfull__overlay' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'overlay_opacity',
            [
                'label' => __( 'Overlay Opacity', 'wr-ew' ),
                'type'  => Controls_Manager::SLIDER,
                'size_units' => [ 'custom' ],
                'range' => [ 'custom' => [ 'min' => 0, 'max' => 1, 'step' => 0.05 ] ],
                'default' => [ 'size' => 0.35, 'unit' => '' ],
                'selectors' => [
                    '{{WRAPPER}} .wr-pgfull__overlay' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->add_control(
            'overlay_text_color',
            [
                'label' => __( 'Text Color', 'wr-ew' ),
                'type'  => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-pgfull__title, {{WRAPPER}} .wr-pgfull__subtitle, {{WRAPPER}} .wr-pgfull__button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * STYLE: Typography
         */
        $this->start_controls_section(
            'section_style_typography',
            [
                'label' => __( 'Typography', 'wr-ew' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typo',
                'selector' => '{{WRAPPER}} .wr-pgfull__title',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __( 'Title Color', 'wr-ew' ),
                'type'  => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-pgfull__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'subtitle_typo',
                'selector' => '{{WRAPPER}} .wr-pgfull__subtitle',
            ]
        );

        $this->add_control(
            'subtitle_color',
            [
                'label' => __( 'Subtitle Color', 'wr-ew' ),
                'type'  => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-pgfull__subtitle' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'button_typo',
                'selector' => '{{WRAPPER}} .wr-pgfull__button',
            ]
        );

        $this->add_control(
            'button_text_color',
            [
                'label' => __( 'Button Text Color', 'wr-ew' ),
                'type'  => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-pgfull__button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_text_color_hover',
            [
                'label' => __( 'Button Text Color (Hover)', 'wr-ew' ),
                'type'  => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-pgfull__button:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_bg_color',
            [
                'label' => __( 'Button Background', 'wr-ew' ),
                'type'  => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-pgfull__button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_bg_color_hover',
            [
                'label' => __( 'Button Background (Hover)', 'wr-ew' ),
                'type'  => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-pgfull__button:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * STYLE: Content Position (Block Move)
         */
        $this->start_controls_section(
            'section_style_content_position',
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
                    '{{WRAPPER}} .wr-pgfull__content' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_padding',
            [
                'label'      => __( 'Content Padding', 'wr-ew' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .wr-pgfull__content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .wr-pgfull__content' => '--wr-pgfull-x: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .wr-pgfull__content' => '--wr-pgfull-y: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    private function preset_max_tiles( $preset ) {
        $map = [
            'two_equal'          => 2,
            'two_split_70_30'    => 2,
            'two_split_30_70'    => 2,
            'three_equal'        => 3,
            'three_mosaic_left'  => 3,
            'three_mosaic_right' => 3,
            'four_checker'       => 4,
            'four_mosaic_left'   => 4,
            'four_mosaic_right'  => 4,
            'six_grid'           => 6,
        ];
        return isset( $map[ $preset ] ) ? $map[ $preset ] : 6;
    }

    private function render_tile( $tile, $hover_effect ) {
        if ( empty( $tile['show_tile'] ) || 'yes' !== $tile['show_tile'] ) {
            return '';
        }

        $tile_type = ! empty( $tile['tile_type'] ) ? $tile['tile_type'] : 'image';

        $link_url = '';
        $tile_link_is_external = false;
        $tile_link_nofollow = false;

        if ( ! empty( $tile['link'] ) && is_array( $tile['link'] ) && ! empty( $tile['link']['url'] ) ) {
            $link_url = $tile['link']['url'];
            $tile_link_is_external = ! empty( $tile['link']['is_external'] );
            $tile_link_nofollow = ! empty( $tile['link']['nofollow'] );
        }

        $is_tile_clickable = ( empty( $tile['tile_clickable'] ) || 'yes' === $tile['tile_clickable'] );
        $tile_link = ( $is_tile_clickable && $link_url ) ? $link_url : '';

        $new_tab = ! empty( $tile['new_tab'] ) && 'yes' === $tile['new_tab'];
        $overlay = empty( $tile['overlay_enable'] ) || 'yes' === $tile['overlay_enable'];
        $pos     = ! empty( $tile['content_position'] ) ? $tile['content_position'] : 'bottom-left';

        $classes = [ 'wr-pgfull__tile', 'wr-pgfull__tile--' . $tile_type ];
        if ( $hover_effect && 'none' !== $hover_effect ) {
            $classes[] = 'wr-pgfull__tile--hover-' . $hover_effect;
        }

        $tag = $tile_link ? 'a' : 'div';

        $attrs = '';
        if ( $tile_link ) {
            $attrs .= ' href="' . esc_url( $tile_link ) . '"';
            $rel = [];
            if ( $new_tab || $tile_link_is_external ) $attrs .= ' target="_blank"';
            if ( $tile_link_nofollow ) $rel[] = 'nofollow';
            if ( $new_tab || $tile_link_is_external ) $rel[] = 'noopener';
            if ( ! empty( $rel ) ) $attrs .= ' rel="' . esc_attr( implode( ' ', array_unique( $rel ) ) ) . '"';
        }

        ob_start();
        ?>
        <<?php echo $tag; ?> class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>"<?php echo $attrs; ?>>
            <div class="wr-pgfull__media">
                <?php if ( 'video' === $tile_type && ! empty( $tile['video_url'] ) ) : ?>
                    <?php if ( $this->is_youtube_url( $tile['video_url'] ) ) : ?>
                        <?php $yt_src = $this->get_youtube_embed_src( $tile['video_url'] ); ?>
                        <?php if ( $yt_src ) : ?>
                            <iframe
                                class="wr-pgfull__youtube"
                                src="<?php echo esc_url( $yt_src ); ?>"
                                title="<?php echo esc_attr( ! empty( $tile['title'] ) ? $tile['title'] : 'YouTube video' ); ?>"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen
                            ></iframe>
                        <?php else : ?>
                            <div class="wr-pgfull__placeholder"></div>
                        <?php endif; ?>
                    <?php else : ?>
                        <video class="wr-pgfull__video" muted playsinline preload="metadata" src="<?php echo esc_url( $tile['video_url'] ); ?>"></video>
                    <?php endif; ?>
                <?php elseif ( ! empty( $tile['image']['url'] ) ) : ?>
                    <img src="<?php echo esc_url( $tile['image']['url'] ); ?>" alt="<?php echo esc_attr( ! empty( $tile['title'] ) ? $tile['title'] : '' ); ?>" loading="lazy" />
                <?php else : ?>
                    <div class="wr-pgfull__placeholder"></div>
                <?php endif; ?>
            </div>

            <?php if ( $overlay ) : ?>
                <div class="wr-pgfull__overlay"></div>
            <?php endif; ?>

            <div class="wr-pgfull__content wr-pgfull__content--pos-<?php echo esc_attr( $pos ); ?>">
                <?php if ( ! empty( $tile['title'] ) ) : ?>
                    <h3 class="wr-pgfull__title"><?php echo esc_html( $tile['title'] ); ?></h3>
                <?php endif; ?>

                <?php if ( ! empty( $tile['subtitle'] ) ) : ?>
                    <p class="wr-pgfull__subtitle"><?php echo esc_html( $tile['subtitle'] ); ?></p>
                <?php endif; ?>

                <?php
                // If tile itself is linked, do NOT render nested anchors.
                // If tile is not linked, render button as link only if URL exists.
                if ( ! $tile_link && ! empty( $tile['button_text'] ) ) :
                    $btn_url = $link_url;
                    if ( $btn_url ) :
                        $btn_target = ( $new_tab || ( ! empty( $tile['link']['is_external'] ) ) ) ? ' target="_blank"' : '';
                        $btn_rel = [];
                        if ( ! empty( $tile['link']['nofollow'] ) ) $btn_rel[] = 'nofollow';
                        if ( $new_tab || ( ! empty( $tile['link']['is_external'] ) ) ) $btn_rel[] = 'noopener';
                        $btn_rel_attr = ! empty( $btn_rel ) ? ' rel="' . esc_attr( implode( ' ', array_unique( $btn_rel ) ) ) . '"' : '';
                        ?>
                        <a class="wr-pgfull__button" href="<?php echo esc_url( $btn_url ); ?>"<?php echo $btn_target; ?><?php echo $btn_rel_attr; ?>>
                            <?php echo esc_html( $tile['button_text'] ); ?>
                        </a>
                    <?php else : ?>
                        <span class="wr-pgfull__button"><?php echo esc_html( $tile['button_text'] ); ?></span>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </<?php echo $tag; ?>>
        <?php
        return ob_get_clean();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $preset = ! empty( $settings['layout_preset'] ) ? $settings['layout_preset'] : 'three_mosaic_left';
        $hover  = isset( $settings['hover_effect'] ) ? $settings['hover_effect'] : 'zoom';
        $full_width = ( ! empty( $settings['full_width'] ) && 'yes' === $settings['full_width'] );

        $tiles_raw = ( ! empty( $settings['tiles'] ) && is_array( $settings['tiles'] ) ) ? $settings['tiles'] : [];
        $tiles     = array_slice( $tiles_raw, 0, $this->preset_max_tiles( $preset ) );

        $wrapper_classes = [ 'wr-promo-grid-full' ];
        if ( $full_width ) $wrapper_classes[] = 'wr-pgfull--fullwidth';

        $grid_classes = [ 'wr-pgfull__grid', 'wr-pgfull__grid--' . $preset ];

        echo '<div class="' . esc_attr( implode( ' ', $wrapper_classes ) ) . '">';
        echo '<div class="wr-pgfull__inner">';
        echo '<div class="' . esc_attr( implode( ' ', $grid_classes ) ) . '">';

        foreach ( $tiles as $tile ) {
            echo $this->render_tile( $tile, $hover );
        }

        echo '</div></div></div>';
    }
}
