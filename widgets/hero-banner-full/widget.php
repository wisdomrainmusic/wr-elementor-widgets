<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class WR_EW_Hero_Banner_Full extends \Elementor\Widget_Base {

    const STYLE_HANDLE = 'wr-hero-banner-full-css';

    public function get_name() { return 'hero-banner-full'; }
    public function get_title() { return __( 'WR Hero Banner Full', 'wr-ew' ); }
    public function get_icon() { return 'eicon-slider-full-screen'; }
    public function get_categories() { return [ 'wr-widgets', 'wr-ecommerce-elements' ]; }
    public function get_style_depends() { return [ self::STYLE_HANDLE ]; }

    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );

        // Frontend
        add_action( 'wp_enqueue_scripts', [ $this, 'register_assets' ] );

        // Elementor Editor (iframe) + Preview
        add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'register_assets' ] );
        add_action( 'elementor/preview/enqueue_styles', [ $this, 'register_assets' ] );
    }

    public function register_assets() {

        if ( ! wp_style_is( self::STYLE_HANDLE, 'registered' ) ) {
            wp_register_style( self::STYLE_HANDLE, false, [], '1.0.3' );
        }

        $css = "
        .wr-hero-single{position:relative;width:100%;}
        .wr-hero-single.is-fullwidth{width:100vw;max-width:100vw;left:50%;right:50%;margin-left:-50vw;margin-right:-50vw;}
        .wr-hero-single .wr-hero-wrap{
            position:relative;
            width:100%;
            overflow:hidden;
            border-radius:var(--wr-hero-radius, 0px);
            min-height:var(--wr-hero-min-height, 520px);
        }
        .wr-hero-single .wr-hero-fill{position:absolute;inset:0;background:var(--wr-hero-fill, transparent);}
        .wr-hero-single .wr-hero-bg{
            position:absolute;inset:0;
            background-position:center;
            background-repeat:no-repeat;
            background-size:cover;
            transform:scale(var(--wr-hero-zoom, 1));
            transform-origin:center;
            will-change:transform;
        }
        .wr-hero-single .wr-hero-overlay{position:absolute;inset:0;background:var(--wr-hero-overlay, transparent);}
        .wr-hero-single .wr-hero-inner{
            position:relative;z-index:2;
            display:flex;align-items:center;
            width:100%;height:100%;
            padding:var(--wr-hero-padding, 0px);
        }
        .wr-hero-single .wr-hero-content{
            transform:translate(var(--wr-content-x, 0px), var(--wr-content-y, 0px));
            max-width:var(--wr-content-maxw, 640px);
            background:var(--wr-content-bg, transparent);
            padding:var(--wr-content-pad, 0px);
            border-radius:var(--wr-content-radius, 0px);
        }
        .wr-hero-single .wr-hero-eyebrow{margin:0 0 10px 0;}
        .wr-hero-single .wr-hero-title{margin:0 0 10px 0;}
        .wr-hero-single .wr-hero-sub{margin:0 0 18px 0;}

        .wr-hero-single .wr-hero-btn{
            display:inline-flex;align-items:center;justify-content:center;
            text-decoration:none;border:none;cursor:pointer;
            border-radius:var(--wr-btn-radius, 999px);
            padding:var(--wr-btn-pad, 12px 22px);
            transition:transform .18s ease, background-color .18s ease, color .18s ease, opacity .18s ease;
            background:var(--wr-btn-bg, #ffffff);
            color:var(--wr-btn-color, #111111);
        }
        .wr-hero-single .wr-hero-btn:hover{background:var(--wr-btn-bg-hover, var(--wr-btn-bg, #ffffff));color:var(--wr-btn-color-hover, var(--wr-btn-color, #111111));transform:translateY(-1px);}
        .wr-hero-single .wr-hero-btn:active{transform:translateY(0px);opacity:.95;}
        ";

        wp_add_inline_style( self::STYLE_HANDLE, $css );
    }

    protected function register_controls() {

        // CONTENT
        $this->start_controls_section(
            'section_content',
            [
                'label' => __( 'Content', 'wr-ew' ),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'bg_image',
            [
                'label'   => __( 'Background Image', 'wr-ew' ),
                'type'    => \Elementor\Controls_Manager::MEDIA,
                'default' => [ 'url' => \Elementor\Utils::get_placeholder_image_src() ],
            ]
        );

        $this->add_control(
            'eyebrow',
            [
                'label'       => __( 'Eyebrow Text', 'wr-ew' ),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'default'     => '',
                'label_block' => true,
            ]
        );

        $this->add_control(
            'heading',
            [
                'label'       => __( 'Heading', 'wr-ew' ),
                'type'        => \Elementor\Controls_Manager::TEXTAREA,
                'default'     => __( 'Your Heading', 'wr-ew' ),
                'label_block' => true,
                'rows'        => 2,
            ]
        );

        $this->add_control(
            'subheading',
            [
                'label'       => __( 'Subheading', 'wr-ew' ),
                'type'        => \Elementor\Controls_Manager::TEXTAREA,
                'default'     => __( 'Your subheading text goes here.', 'wr-ew' ),
                'label_block' => true,
                'rows'        => 2,
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label'       => __( 'Button Text', 'wr-ew' ),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'default'     => __( 'Shop Now', 'wr-ew' ),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'button_link',
            [
                'label'       => __( 'Button Link', 'wr-ew' ),
                'type'        => \Elementor\Controls_Manager::URL,
                'placeholder' => 'https://',
                'options'     => [ 'url', 'is_external', 'nofollow' ],
                'default'     => [ 'url' => '', 'is_external' => false, 'nofollow' => false ],
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
                'default'      => 'yes',
            ]
        );

        $this->end_controls_section();

        // STYLE - LAYOUT
        $this->start_controls_section(
            'section_style_layout',
            [
                'label' => __( 'Style', 'wr-ew' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'hero_min_height',
            [
                'label'      => __( 'Hero Min Height', 'wr-ew' ),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => 280, 'max' => 1100 ] ],
                'default'    => [ 'unit' => 'px', 'size' => 585 ],
            ]
        );

        $this->add_control(
            'overlay_color',
            [
                'label'   => __( 'Overlay Color', 'wr-ew' ),
                'type'    => \Elementor\Controls_Manager::COLOR,
                'default' => 'rgba(0,0,0,0)',
            ]
        );

        $this->add_control(
            'bg_zoom_out',
            [
                'label'      => __( 'Background Zoom Out (Fit)', 'wr-ew' ),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ '%' ],
                'range'      => [ '%' => [ 'min' => 50, 'max' => 120 ] ],
                'default'    => [ 'unit' => '%', 'size' => 83 ],
            ]
        );

        $this->add_control(
            'bg_fill_color',
            [
                'label'   => __( 'Background Fill Color (Behind Image)', 'wr-ew' ),
                'type'    => \Elementor\Controls_Manager::COLOR,
                'default' => 'transparent',
            ]
        );

        $this->add_control(
            'hero_radius',
            [
                'label'      => __( 'Hero Border Radius', 'wr-ew' ),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => 0, 'max' => 60 ] ],
                'default'    => [ 'unit' => 'px', 'size' => 0 ],
            ]
        );

        $this->add_control(
            'hero_padding',
            [
                'label'      => __( 'Hero Inner Padding', 'wr-ew' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default'    => [ 'top' => 0, 'right' => 0, 'bottom' => 0, 'left' => 0, 'unit' => 'px' ],
            ]
        );

        $this->add_control(
            'heading_content_position',
            [
                'label'     => __( 'Content Position', 'wr-ew' ),
                'type'      => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'content_x',
            [
                'label'      => __( 'Move Left / Right', 'wr-ew' ),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => -400, 'max' => 400 ] ],
                'default'    => [ 'unit' => 'px', 'size' => 105 ],
            ]
        );

        $this->add_control(
            'content_y',
            [
                'label'      => __( 'Move Up / Down', 'wr-ew' ),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => -300, 'max' => 300 ] ],
                'default'    => [ 'unit' => 'px', 'size' => 96 ],
            ]
        );

        $this->add_control(
            'content_maxw',
            [
                'label'      => __( 'Content Max Width', 'wr-ew' ),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => 280, 'max' => 900 ] ],
                'default'    => [ 'unit' => 'px', 'size' => 640 ],
            ]
        );

        $this->add_control(
            'content_bg',
            [
                'label'   => __( 'Content Background', 'wr-ew' ),
                'type'    => \Elementor\Controls_Manager::COLOR,
                'default' => 'transparent',
            ]
        );

        $this->add_control(
            'content_pad',
            [
                'label'      => __( 'Content Padding', 'wr-ew' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default'    => [ 'top' => 0, 'right' => 0, 'bottom' => 0, 'left' => 0, 'unit' => 'px' ],
            ]
        );

        $this->add_control(
            'content_radius',
            [
                'label'      => __( 'Content Radius', 'wr-ew' ),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => 0, 'max' => 40 ] ],
                'default'    => [ 'unit' => 'px', 'size' => 0 ],
            ]
        );

        $this->end_controls_section();

        // STYLE - TEXT
        $this->start_controls_section(
            'section_style_text',
            [
                'label' => __( 'Text Styles', 'wr-ew' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'eyebrow_typo',
                'label'    => __( 'Eyebrow Typography', 'wr-ew' ),
                'selector' => '{{WRAPPER}} .wr-hero-eyebrow',
            ]
        );

        $this->add_control(
            'eyebrow_color',
            [
                'label'     => __( 'Eyebrow Color', 'wr-ew' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-hero-eyebrow' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typo',
                'label'    => __( 'Title Typography', 'wr-ew' ),
                'selector' => '{{WRAPPER}} .wr-hero-title',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label'     => __( 'Title Color', 'wr-ew' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-hero-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'sub_typo',
                'label'    => __( 'Subheading Typography', 'wr-ew' ),
                'selector' => '{{WRAPPER}} .wr-hero-sub',
            ]
        );

        $this->add_control(
            'sub_color',
            [
                'label'     => __( 'Subheading Color', 'wr-ew' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-hero-sub' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'heading_button_styles',
            [
                'label'     => __( 'Button', 'wr-ew' ),
                'type'      => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'btn_typo',
                'label'    => __( 'Button Typography', 'wr-ew' ),
                'selector' => '{{WRAPPER}} .wr-hero-btn',
            ]
        );

        $this->add_control(
            'btn_text_color',
            [
                'label'     => __( 'Button Text Color', 'wr-ew' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-hero-btn' => '--wr-btn-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btn_bg_color',
            [
                'label'     => __( 'Button Background Color', 'wr-ew' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-hero-btn' => '--wr-btn-bg: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btn_text_color_hover',
            [
                'label'     => __( 'Button Text Hover Color', 'wr-ew' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-hero-btn' => '--wr-btn-color-hover: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btn_bg_color_hover',
            [
                'label'     => __( 'Button Background Hover Color', 'wr-ew' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-hero-btn' => '--wr-btn-bg-hover: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btn_radius',
            [
                'label'      => __( 'Button Radius', 'wr-ew' ),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => 0, 'max' => 50 ] ],
                'default'    => [ 'unit' => 'px', 'size' => 999 ],
                'selectors'  => [
                    '{{WRAPPER}} .wr-hero-btn' => '--wr-btn-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'btn_padding',
            [
                'label'      => __( 'Button Padding', 'wr-ew' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default'    => [ 'top' => 12, 'right' => 22, 'bottom' => 12, 'left' => 22, 'unit' => 'px' ],
                'selectors'  => [
                    '{{WRAPPER}} .wr-hero-btn' => '--wr-btn-pad: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $s = $this->get_settings_for_display();

        // Ensure CSS is loaded in editor/preview too
        wp_enqueue_style( self::STYLE_HANDLE );

        $img_url = ! empty( $s['bg_image']['url'] ) ? $s['bg_image']['url'] : '';
        $eyebrow = isset( $s['eyebrow'] ) ? $s['eyebrow'] : '';
        $heading = isset( $s['heading'] ) ? $s['heading'] : '';
        $sub     = isset( $s['subheading'] ) ? $s['subheading'] : '';
        $btn_text= isset( $s['button_text'] ) ? $s['button_text'] : '';
        $btn_link= isset( $s['button_link']['url'] ) ? $s['button_link']['url'] : '';

        $btn_is_external = ! empty( $s['button_link']['is_external'] );
        $btn_nofollow    = ! empty( $s['button_link']['nofollow'] );

        $btn_attrs = '';
        if ( $btn_is_external ) $btn_attrs .= ' target="_blank"';
        if ( $btn_nofollow ) $btn_attrs .= ' rel="nofollow"';

        $min_h = ! empty( $s['hero_min_height']['size'] ) ? (int) $s['hero_min_height']['size'] : 520;

        $zoom_percent = ! empty( $s['bg_zoom_out']['size'] ) ? (float) $s['bg_zoom_out']['size'] : 100.0;
        $zoom_scale   = max( 0.5, min( 1.2, $zoom_percent / 100.0 ) );

        $overlay = isset( $s['overlay_color'] ) ? $s['overlay_color'] : 'rgba(0,0,0,0)';
        $fill    = isset( $s['bg_fill_color'] ) ? $s['bg_fill_color'] : 'transparent';

        $x = ! empty( $s['content_x']['size'] ) ? (int) $s['content_x']['size'] : 0;
        $y = ! empty( $s['content_y']['size'] ) ? (int) $s['content_y']['size'] : 0;
        $maxw = ! empty( $s['content_maxw']['size'] ) ? (int) $s['content_maxw']['size'] : 640;

        $hero_radius = ! empty( $s['hero_radius']['size'] ) ? (int) $s['hero_radius']['size'] : 0;
        $content_radius = ! empty( $s['content_radius']['size'] ) ? (int) $s['content_radius']['size'] : 0;

        $content_bg = isset( $s['content_bg'] ) ? $s['content_bg'] : 'transparent';

        $hero_pad = $s['hero_padding'] ?? [];
        $hero_pad_css = sprintf(
            '%dpx %dpx %dpx %dpx',
            isset($hero_pad['top']) ? (int)$hero_pad['top'] : 0,
            isset($hero_pad['right']) ? (int)$hero_pad['right'] : 0,
            isset($hero_pad['bottom']) ? (int)$hero_pad['bottom'] : 0,
            isset($hero_pad['left']) ? (int)$hero_pad['left'] : 0
        );

        $content_pad = $s['content_pad'] ?? [];
        $content_pad_css = sprintf(
            '%dpx %dpx %dpx %dpx',
            isset($content_pad['top']) ? (int)$content_pad['top'] : 0,
            isset($content_pad['right']) ? (int)$content_pad['right'] : 0,
            isset($content_pad['bottom']) ? (int)$content_pad['bottom'] : 0,
            isset($content_pad['left']) ? (int)$content_pad['left'] : 0
        );

        $is_full = ( isset( $s['full_width'] ) && $s['full_width'] === 'yes' );

        $style = sprintf(
            '--wr-hero-min-height:%dpx;--wr-hero-zoom:%s;--wr-hero-overlay:%s;--wr-hero-fill:%s;--wr-content-x:%dpx;--wr-content-y:%dpx;--wr-content-maxw:%dpx;--wr-hero-radius:%dpx;--wr-hero-padding:%s;--wr-content-bg:%s;--wr-content-pad:%s;--wr-content-radius:%dpx;',
            $min_h,
            rtrim( rtrim( number_format( $zoom_scale, 2, '.', '' ), '0' ), '.' ),
            $overlay ? $overlay : 'transparent',
            $fill ? $fill : 'transparent',
            $x,
            $y,
            $maxw,
            $hero_radius,
            $hero_pad_css,
            $content_bg ? $content_bg : 'transparent',
            $content_pad_css,
            $content_radius
        );

        $class = 'wr-hero-single' . ( $is_full ? ' is-fullwidth' : '' );
        ?>
        <div class="<?php echo esc_attr( $class ); ?>" style="<?php echo esc_attr( $style ); ?>">
            <div class="wr-hero-wrap">
                <div class="wr-hero-fill"></div>
                <div class="wr-hero-bg" style="<?php echo $img_url ? 'background-image:url(' . esc_url( $img_url ) . ');' : ''; ?>"></div>
                <div class="wr-hero-overlay"></div>

                <div class="wr-hero-inner">
                    <div class="wr-hero-content">
                        <?php if ( $eyebrow !== '' ) : ?>
                            <div class="wr-hero-eyebrow"><?php echo esc_html( $eyebrow ); ?></div>
                        <?php endif; ?>

                        <?php if ( $heading !== '' ) : ?>
                            <div class="wr-hero-title"><?php echo wp_kses_post( nl2br( esc_html( $heading ) ) ); ?></div>
                        <?php endif; ?>

                        <?php if ( $sub !== '' ) : ?>
                            <div class="wr-hero-sub"><?php echo wp_kses_post( nl2br( esc_html( $sub ) ) ); ?></div>
                        <?php endif; ?>

                        <?php if ( $btn_text !== '' && $btn_link !== '' ) : ?>
                            <a class="wr-hero-btn" href="<?php echo esc_url( $btn_link ); ?>"<?php echo $btn_attrs; ?>>
                                <?php echo esc_html( $btn_text ); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}

