<?php
if ( ! defined( 'ABSPATH' ) ) exit;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

class WR_EW_Promo_Grid_Full extends Widget_Base {

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
        return [ 'wr-promo-grid-full-css' ];
    }

    public function get_script_depends() {
        return [ 'wr-promo-grid-full-js' ];
    }

    protected function register_controls() {

        $this->start_controls_section( 'section_layout', [
            'label' => __( 'Content', 'wr-ew' ),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ] );

        $this->add_control( 'preset', [
            'label'   => __( 'Layout Preset', 'wr-ew' ),
            'type'    => Controls_Manager::CHOOSE,
            'options' => [
                'two_equal'          => [ 'title' => __( 'Two Equal', 'wr-ew' ), 'icon' => 'eicon-gallery-grid' ],
                'two_split_70_30'    => [ 'title' => __( '70 / 30', 'wr-ew' ), 'icon' => 'eicon-h-align-stretch' ],
                'two_split_30_70'    => [ 'title' => __( '30 / 70', 'wr-ew' ), 'icon' => 'eicon-h-align-stretch' ],
                'three_equal'        => [ 'title' => __( 'Three Equal', 'wr-ew' ), 'icon' => 'eicon-gallery-grid' ],
                'three_mosaic_left'  => [ 'title' => __( 'Mosaic Left', 'wr-ew' ), 'icon' => 'eicon-columns' ],
                'three_mosaic_right' => [ 'title' => __( 'Mosaic Right', 'wr-ew' ), 'icon' => 'eicon-columns' ],
                'four_checker'       => [ 'title' => __( 'Checker', 'wr-ew' ), 'icon' => 'eicon-gallery-masonry' ],
                'four_mosaic_left'   => [ 'title' => __( 'Four Mosaic L', 'wr-ew' ), 'icon' => 'eicon-gallery-masonry' ],
                'four_mosaic_right'  => [ 'title' => __( 'Four Mosaic R', 'wr-ew' ), 'icon' => 'eicon-gallery-masonry' ],
                'six_grid'           => [ 'title' => __( 'Six Grid', 'wr-ew' ), 'icon' => 'eicon-gallery-grid' ],
            ],
            'toggle'  => false,
            'default' => 'two_equal',
        ] );

        $this->add_control( 'full_width', [
            'label'        => __( 'Full Width', 'wr-ew' ),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => __( 'Yes', 'wr-ew' ),
            'label_off'    => __( 'No', 'wr-ew' ),
            'return_value' => 'yes',
            'default'      => 'no',
        ] );

        $this->add_responsive_control( 'content_max_width', [
            'label'      => __( 'Content Max Width', 'wr-ew' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => [ 'px', '%' ],
            'range'      => [
                'px' => [ 'min' => 300, 'max' => 1800 ],
                '%'  => [ 'min' => 10, 'max' => 100 ],
            ],
            'selectors'  => [
                '{{WRAPPER}} .wr-pgfull__inner' => 'max-width: {{SIZE}}{{UNIT}};',
            ],
        ] );

        $this->add_responsive_control( 'side_padding', [
            'label'      => __( 'Side Padding', 'wr-ew' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => [ 'px', '%', 'em' ],
            'range'      => [ 'px' => [ 'min' => 0, 'max' => 120 ], '%' => [ 'min' => 0, 'max' => 15 ], 'em' => [ 'min' => 0, 'max' => 8 ] ],
            'selectors'  => [
                '{{WRAPPER}} .wr-pgfull__inner' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}};',
            ],
        ] );

        $repeater = new Repeater();

        $repeater->add_control( 'show_tile', [
            'label'        => __( 'Show Tile', 'wr-ew' ),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ] );

        $repeater->add_control( 'tile_clickable', [
            'label'        => __( 'Tile Clickable', 'wr-ew' ),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'no',
        ] );

        $repeater->add_control( 'tile_type', [
            'label'   => __( 'Tile Type', 'wr-ew' ),
            'type'    => Controls_Manager::SELECT,
            'options' => [
                'image' => __( 'Image', 'wr-ew' ),
                'video' => __( 'Video (MP4)', 'wr-ew' ),
            ],
            'default' => 'image',
        ] );

        $repeater->add_control( 'image', [
            'label'   => __( 'Image', 'wr-ew' ),
            'type'    => Controls_Manager::MEDIA,
            'default' => [ 'url' => Controls_Manager::get_placeholder_image_src() ],
            'condition' => [ 'tile_type' => 'image' ],
        ] );

        $repeater->add_control( 'video_url', [
            'label'       => __( 'Video URL (MP4)', 'wr-ew' ),
            'type'        => Controls_Manager::TEXT,
            'label_block' => true,
            'placeholder' => __( 'https://example.com/video.mp4', 'wr-ew' ),
            'condition'   => [ 'tile_type' => 'video' ],
        ] );

        $repeater->add_control( 'title', [
            'label'       => __( 'Title', 'wr-ew' ),
            'type'        => Controls_Manager::TEXT,
            'label_block' => true,
        ] );

        $repeater->add_control( 'subtitle', [
            'label'       => __( 'Subtitle', 'wr-ew' ),
            'type'        => Controls_Manager::TEXTAREA,
            'rows'        => 2,
        ] );

        $repeater->add_control( 'button_text', [
            'label'       => __( 'Button Text', 'wr-ew' ),
            'type'        => Controls_Manager::TEXT,
            'label_block' => true,
        ] );

        $repeater->add_control( 'tile_link', [
            'label'         => __( 'Link', 'wr-ew' ),
            'type'          => Controls_Manager::URL,
            'label_block'   => true,
            'placeholder'   => __( 'https://your-link.com', 'wr-ew' ),
            'show_external' => true,
            'default'       => [ 'url' => '', 'is_external' => false, 'nofollow' => false ],
        ] );

        $repeater->add_control( 'overlay_enable', [
            'label'        => __( 'Overlay Enable', 'wr-ew' ),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ] );

        $repeater->add_control( 'overlay_position', [
            'label'   => __( 'Overlay Content Position', 'wr-ew' ),
            'type'    => Controls_Manager::SELECT,
            'options' => [
                'center'      => __( 'Center', 'wr-ew' ),
                'top_left'    => __( 'Top Left', 'wr-ew' ),
                'top_right'   => __( 'Top Right', 'wr-ew' ),
                'bottom_left' => __( 'Bottom Left', 'wr-ew' ),
                'bottom_right'=> __( 'Bottom Right', 'wr-ew' ),
            ],
            'default' => 'center',
        ] );

        $repeater->add_control( 'media_zoom', [
            'label' => __( 'Media Zoom', 'wr-ew' ),
            'type'  => Controls_Manager::SLIDER,
            'range' => [ 'px' => [ 'min' => 0, 'max' => 100 ] ],
            'default' => [ 'size' => 50 ],
        ] );

        $repeater->add_control( 'media_offset_x', [
            'label' => __( 'Media Offset X (%)', 'wr-ew' ),
            'type'  => Controls_Manager::SLIDER,
            'range' => [ '%' => [ 'min' => -50, 'max' => 50 ] ],
            'default' => [ 'size' => 0 ],
        ] );

        $repeater->add_control( 'media_offset_y', [
            'label' => __( 'Media Offset Y (%)', 'wr-ew' ),
            'type'  => Controls_Manager::SLIDER,
            'range' => [ '%' => [ 'min' => -50, 'max' => 50 ] ],
            'default' => [ 'size' => 0 ],
        ] );

        $repeater->add_control( 'content_max_width', [
            'label'      => __( 'Content Max Width', 'wr-ew' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => [ 'px', '%' ],
            'range'      => [ 'px' => [ 'min' => 100, 'max' => 1200 ], '%' => [ 'min' => 10, 'max' => 100 ] ],
        ] );

        $repeater->add_responsive_control( 'content_padding', [
            'label'      => __( 'Content Padding', 'wr-ew' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', 'em', '%' ],
        ] );

        $this->add_control( 'tiles', [
            'label'       => __( 'Tiles', 'wr-ew' ),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $repeater->get_controls(),
            'title_field' => '{{{ title }}}',
            'default'     => [
                [ 'title' => __( 'Promo #1', 'wr-ew' ) ],
                [ 'title' => __( 'Promo #2', 'wr-ew' ) ],
                [ 'title' => __( 'Promo #3', 'wr-ew' ) ],
            ],
        ] );

        $this->end_controls_section();

        $this->start_controls_section( 'section_container_style', [
            'label' => __( 'Container', 'wr-ew' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_responsive_control( 'min_height', [
            'label' => __( 'Min Height', 'wr-ew' ),
            'type'  => Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'vh' ],
            'range' => [ 'px' => [ 'min' => 0, 'max' => 1200 ], 'vh' => [ 'min' => 10, 'max' => 100 ] ],
            'selectors' => [
                '{{WRAPPER}} .wr-pgfull__grid' => 'min-height: {{SIZE}}{{UNIT}};',
            ],
        ] );

        $this->add_control( 'width_mode', [
            'label'   => __( 'Width Mode', 'wr-ew' ),
            'type'    => Controls_Manager::SELECT,
            'options' => [
                'boxed' => __( 'Boxed', 'wr-ew' ),
                'full'  => __( 'Full', 'wr-ew' ),
            ],
            'default' => 'boxed',
        ] );

        $this->add_responsive_control( 'container_padding', [
            'label'      => __( 'Padding', 'wr-ew' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', 'em', '%' ],
            'selectors'  => [
                '{{WRAPPER}} .wr-pgfull' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ] );

        $this->end_controls_section();

        $this->start_controls_section( 'section_grid_style', [
            'label' => __( 'Grid', 'wr-ew' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_responsive_control( 'grid_gap', [
            'label' => __( 'Gap', 'wr-ew' ),
            'type'  => Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'em' ],
            'range' => [ 'px' => [ 'min' => 0, 'max' => 80 ], 'em' => [ 'min' => 0, 'max' => 5 ] ],
            'selectors' => [
                '{{WRAPPER}} .wr-pgfull__grid' => 'gap: {{SIZE}}{{UNIT}};',
            ],
        ] );

        $this->add_responsive_control( 'tile_min_height', [
            'label' => __( 'Tile Min Height', 'wr-ew' ),
            'type'  => Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range' => [ 'px' => [ 'min' => 160, 'max' => 800 ] ],
            'selectors' => [
                '{{WRAPPER}} .wr-pgfull__tile' => 'min-height: {{SIZE}}{{UNIT}};',
            ],
            'devices' => [ 'desktop', 'tablet' ],
        ] );

        $this->end_controls_section();

        $this->start_controls_section( 'section_tile_style', [
            'label' => __( 'Tile', 'wr-ew' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_control( 'tile_radius', [
            'label' => __( 'Border Radius', 'wr-ew' ),
            'type'  => Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range' => [ 'px' => [ 'min' => 0, 'max' => 80 ] ],
            'selectors' => [
                '{{WRAPPER}} .wr-pgfull__tile-body' => 'border-radius: {{SIZE}}{{UNIT}};',
            ],
        ] );

        $this->add_control( 'tile_hover', [
            'label'   => __( 'Hover Effect', 'wr-ew' ),
            'type'    => Controls_Manager::SELECT,
            'options' => [
                'none'  => __( 'None', 'wr-ew' ),
                'lift'  => __( 'Lift', 'wr-ew' ),
                'glow'  => __( 'Glow', 'wr-ew' ),
            ],
            'default' => 'lift',
        ] );

        $this->add_group_control( Group_Control_Box_Shadow::get_type(), [
            'name'     => 'tile_shadow',
            'selector' => '{{WRAPPER}} .wr-pgfull__tile-body',
        ] );

        $this->end_controls_section();

        $this->start_controls_section( 'section_media_style', [
            'label' => __( 'Media', 'wr-ew' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_control( 'media_fit', [
            'label'   => __( 'Object Fit', 'wr-ew' ),
            'type'    => Controls_Manager::SELECT,
            'options' => [
                'cover'   => __( 'Cover', 'wr-ew' ),
                'contain' => __( 'Contain', 'wr-ew' ),
                'fill'    => __( 'Fill', 'wr-ew' ),
            ],
            'default' => 'cover',
            'selectors' => [
                '{{WRAPPER}} .wr-pgfull__media img, {{WRAPPER}} .wr-pgfull__media video' => 'object-fit: {{VALUE}};',
            ],
        ] );

        $this->add_control( 'media_zoom_global', [
            'label' => __( 'Global Media Zoom', 'wr-ew' ),
            'type'  => Controls_Manager::SLIDER,
            'range' => [ 'px' => [ 'min' => 0, 'max' => 100 ] ],
            'default' => [ 'size' => 50 ],
        ] );

        $this->end_controls_section();

        $this->start_controls_section( 'section_overlay_style', [
            'label' => __( 'Overlay', 'wr-ew' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_control( 'overlay_color', [
            'label' => __( 'Color', 'wr-ew' ),
            'type'  => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .wr-pgfull__overlay' => 'background-color: {{VALUE}};',
            ],
        ] );

        $this->add_control( 'overlay_opacity', [
            'label' => __( 'Opacity', 'wr-ew' ),
            'type'  => Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range' => [ 'px' => [ 'min' => 0, 'max' => 100 ] ],
            'default' => [ 'size' => 40 ],
            'selectors' => [
                '{{WRAPPER}} .wr-pgfull__overlay' => 'opacity: calc({{SIZE}} / 100);',
            ],
        ] );

        $this->add_control( 'overlay_text_color', [
            'label' => __( 'Text Color', 'wr-ew' ),
            'type'  => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .wr-pgfull__content' => 'color: {{VALUE}};',
            ],
        ] );

        $this->end_controls_section();

        $this->start_controls_section( 'section_typography_style', [
            'label' => __( 'Typography', 'wr-ew' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_group_control( Group_Control_Typography::get_type(), [
            'name'     => 'title_typo',
            'selector' => '{{WRAPPER}} .wr-pgfull__title',
        ] );

        $this->add_control( 'title_color', [
            'label' => __( 'Title Color', 'wr-ew' ),
            'type'  => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .wr-pgfull__title' => 'color: {{VALUE}};',
            ],
        ] );

        $this->add_group_control( Group_Control_Typography::get_type(), [
            'name'     => 'subtitle_typo',
            'selector' => '{{WRAPPER}} .wr-pgfull__subtitle',
        ] );

        $this->add_control( 'subtitle_color', [
            'label' => __( 'Subtitle Color', 'wr-ew' ),
            'type'  => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .wr-pgfull__subtitle' => 'color: {{VALUE}};',
            ],
        ] );

        $this->add_group_control( Group_Control_Typography::get_type(), [
            'name'     => 'button_typo',
            'selector' => '{{WRAPPER}} .wr-pgfull__button',
        ] );

        $this->add_control( 'button_color', [
            'label' => __( 'Button Text Color', 'wr-ew' ),
            'type'  => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .wr-pgfull__button' => 'color: {{VALUE}};',
            ],
        ] );

        $this->add_control( 'button_bg', [
            'label' => __( 'Button Background', 'wr-ew' ),
            'type'  => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .wr-pgfull__button' => 'background-color: {{VALUE}};',
            ],
        ] );

        $this->end_controls_section();

        $this->start_controls_section( 'section_content_position', [
            'label' => __( 'Content Position', 'wr-ew' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_control( 'content_offset_x', [
            'label' => __( 'Global Offset X (%)', 'wr-ew' ),
            'type'  => Controls_Manager::SLIDER,
            'range' => [ '%' => [ 'min' => -50, 'max' => 50 ] ],
            'default' => [ 'size' => 0 ],
            'selectors' => [
                '{{WRAPPER}} .wr-pgfull__content' => '--wr-pgfull-content-offset-x: {{SIZE}}%;',
            ],
        ] );

        $this->add_control( 'content_offset_y', [
            'label' => __( 'Global Offset Y (%)', 'wr-ew' ),
            'type'  => Controls_Manager::SLIDER,
            'range' => [ '%' => [ 'min' => -50, 'max' => 50 ] ],
            'default' => [ 'size' => 0 ],
            'selectors' => [
                '{{WRAPPER}} .wr-pgfull__content' => '--wr-pgfull-content-offset-y: {{SIZE}}%;',
            ],
        ] );

        $this->add_responsive_control( 'content_block_width', [
            'label' => __( 'Block Width', 'wr-ew' ),
            'type'  => Controls_Manager::SLIDER,
            'size_units' => [ 'px', '%' ],
            'range' => [ 'px' => [ 'min' => 120, 'max' => 1200 ], '%' => [ 'min' => 10, 'max' => 100 ] ],
            'selectors' => [
                '{{WRAPPER}} .wr-pgfull__content' => 'max-width: {{SIZE}}{{UNIT}};',
            ],
        ] );

        $this->add_responsive_control( 'content_block_padding', [
            'label'      => __( 'Block Padding', 'wr-ew' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', 'em', '%' ],
            'selectors'  => [
                '{{WRAPPER}} .wr-pgfull__content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ] );

        $this->end_controls_section();
    }

    private function calculate_zoom_scale( $value ) {
        $value = is_numeric( $value ) ? floatval( $value ) : 50;
        $value = max( 0, min( 100, $value ) );

        if ( $value <= 50 ) {
            $scale = 0.8 + ( $value / 50 ) * 0.2;
        } else {
            $scale = 1 + ( ( $value - 50 ) / 50 ) * 0.4;
        }

        return max( 0.01, round( $scale, 3 ) );
    }

    private function get_preset_areas() {
        return [
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
    }

    private function get_area_for_index( $preset, $index ) {
        $areas = $this->get_preset_areas();
        return isset( $areas[ $preset ][ $index ] ) ? $areas[ $preset ][ $index ] : '';
    }

    private function build_tile_style( $tile, $global_zoom_scale ) {
        $styles = [];

        if ( isset( $tile['media_zoom']['size'] ) ) {
            $styles[] = '--wr-pgfull-media-zoom:' . $this->calculate_zoom_scale( $tile['media_zoom']['size'] );
        } elseif ( $global_zoom_scale ) {
            $styles[] = '--wr-pgfull-media-zoom:' . $global_zoom_scale;
        }

        if ( isset( $tile['media_offset_x']['size'] ) ) {
            $styles[] = '--wr-pgfull-media-offset-x:' . floatval( $tile['media_offset_x']['size'] ) . '%';
        }

        if ( isset( $tile['media_offset_y']['size'] ) ) {
            $styles[] = '--wr-pgfull-media-offset-y:' . floatval( $tile['media_offset_y']['size'] ) . '%';
        }

        if ( isset( $tile['content_max_width']['size'] ) && ! empty( $tile['content_max_width']['size'] ) ) {
            $styles[] = '--wr-pgfull-block-max:' . $tile['content_max_width']['size'] . ( $tile['content_max_width']['unit'] ?? 'px' );
        }

        if ( isset( $tile['content_padding'] ) ) {
            $pad = $tile['content_padding'];
            $styles[] = '--wr-pgfull-block-padding:' . $pad['top'] . ( $pad['unit'] ?? 'px' ) . ' ' . $pad['right'] . ( $pad['unit'] ?? 'px' ) . ' ' . $pad['bottom'] . ( $pad['unit'] ?? 'px' ) . ' ' . $pad['left'] . ( $pad['unit'] ?? 'px' );
        }

        return $styles ? 'style="' . esc_attr( implode( ';', $styles ) ) . '"' : '';
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $preset   = $settings['preset'] ?? 'two_equal';

        $global_zoom_scale = $this->calculate_zoom_scale( $settings['media_zoom_global']['size'] ?? 50 );

        $wrapper_classes = [ 'wr-pgfull' ];
        if ( 'yes' === ( $settings['full_width'] ?? 'no' ) ) {
            $wrapper_classes[] = 'is-full-width';
        }

        if ( 'full' === ( $settings['width_mode'] ?? 'boxed' ) ) {
            $wrapper_classes[] = 'is-wide';
        }

        $hover_effect = $settings['tile_hover'] ?? 'lift';
        $wrapper_classes[] = 'hover-' . $hover_effect;

        $this->add_render_attribute( 'wrapper', 'class', implode( ' ', $wrapper_classes ) );
        $this->add_render_attribute( 'wrapper', 'style', '--wr-pgfull-media-zoom:' . $global_zoom_scale . ';' );
        $this->add_render_attribute( 'grid', 'class', 'wr-pgfull__grid' );
        $this->add_render_attribute( 'grid', 'data-preset', esc_attr( $preset ) );

        echo '<div ' . $this->get_render_attribute_string( 'wrapper' ) . '>';
        echo '<div class="wr-pgfull__inner">';
        echo '<div ' . $this->get_render_attribute_string( 'grid' ) . '>';

        if ( ! empty( $settings['tiles'] ) && is_array( $settings['tiles'] ) ) {
            foreach ( $settings['tiles'] as $index => $tile ) {

                if ( 'yes' !== ( $tile['show_tile'] ?? 'yes' ) ) {
                    continue;
                }

                $area = $this->get_area_for_index( $preset, $index );
                $tile_classes = [ 'wr-pgfull__tile' ];
                if ( ! empty( $tile['tile_type'] ) ) {
                    $tile_classes[] = 'type-' . $tile['tile_type'];
                }
                if ( ! empty( $tile['overlay_enable'] ) && 'yes' === $tile['overlay_enable'] ) {
                    $tile_classes[] = 'has-overlay';
                }

                $tile_style = $this->build_tile_style( $tile, $global_zoom_scale );

                $tag_open  = '<div class="' . esc_attr( implode( ' ', $tile_classes ) ) . '" ' . $tile_style;
                $tag_close = '</div>';

                $link = $tile['tile_link']['url'] ?? '';

                if ( 'yes' === ( $tile['tile_clickable'] ?? 'no' ) && ! empty( $link ) ) {
                    $target_attr   = ! empty( $tile['tile_link']['is_external'] ) ? ' target="_blank" rel="noopener noreferrer"' : '';
                    $nofollow_attr = ! empty( $tile['tile_link']['nofollow'] ) ? ' rel="nofollow"' : '';
                    $tag_open = '<a class="' . esc_attr( implode( ' ', $tile_classes ) ) . '" ' . $tile_style . ' href="' . esc_url( $link ) . '"' . $target_attr . $nofollow_attr . '>';
                    $tag_close = '</a>';
                }

                if ( ! empty( $area ) ) {
                    $tag_open = str_replace( '" ', '" data-area="' . esc_attr( $area ) . '" ', $tag_open );
                }

                echo $tag_open;

                echo '<div class="wr-pgfull__tile-body">';
                echo '<div class="wr-pgfull__media">';

                if ( ( $tile['tile_type'] ?? 'image' ) === 'video' && ! empty( $tile['video_url'] ) ) {
                    echo '<video class="wr-pgfull__video" src="' . esc_url( $tile['video_url'] ) . '" playsinline muted preload="metadata" tabindex="0">';
                    echo '</video>';
                } elseif ( ! empty( $tile['image']['url'] ) ) {
                    $image_alt = isset( $tile['title'] ) ? esc_attr( $tile['title'] ) : '';
                    echo '<img src="' . esc_url( $tile['image']['url'] ) . '" alt="' . $image_alt . '" loading="lazy" />';
                }

                echo '</div>'; // media

                if ( 'yes' === ( $tile['overlay_enable'] ?? 'yes' ) ) {
                    echo '<div class="wr-pgfull__overlay" aria-hidden="true"></div>';
                }

                $content_classes = [ 'wr-pgfull__content' ];
                $position = $tile['overlay_position'] ?? 'center';
                $content_classes[] = 'pos-' . $position;

                echo '<div class="' . esc_attr( implode( ' ', $content_classes ) ) . '">';

                if ( ! empty( $tile['title'] ) ) {
                    echo '<h3 class="wr-pgfull__title">' . wp_kses_post( $tile['title'] ) . '</h3>';
                }

                if ( ! empty( $tile['subtitle'] ) ) {
                    echo '<div class="wr-pgfull__subtitle">' . wp_kses_post( $tile['subtitle'] ) . '</div>';
                }

                $has_tile_link = 'yes' === ( $tile['tile_clickable'] ?? 'no' ) && ! empty( $link );
                $can_render_button = ! $has_tile_link && ! empty( $tile['button_text'] ) && ! empty( $link );

                if ( $can_render_button ) {
                    $target_attr   = ! empty( $tile['tile_link']['is_external'] ) ? ' target="_blank" rel="noopener noreferrer"' : '';
                    $nofollow_attr = ! empty( $tile['tile_link']['nofollow'] ) ? ' rel="nofollow"' : '';
                    echo '<a class="wr-pgfull__button" href="' . esc_url( $link ) . '"' . $target_attr . $nofollow_attr . '>' . esc_html( $tile['button_text'] ) . '</a>';
                }

                echo '</div>'; // content

                echo '</div>'; // tile body
                echo $tag_close;
            }
        }

        echo '</div>'; // grid
        echo '</div>'; // inner
        echo '</div>'; // wrapper
    }
}
