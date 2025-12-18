<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class WR_EW_Featured_Card_Full extends \Elementor\Widget_Base {

    const STYLE_HANDLE  = 'wr-featured-card-full-css';
    const SCRIPT_HANDLE = 'wr-featured-card-full-js';

    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );

        add_action( 'wp_enqueue_scripts', [ $this, 'register_assets' ] );
        add_action( 'elementor/frontend/after_register_styles', [ $this, 'register_assets' ] );
        add_action( 'elementor/frontend/after_register_scripts', [ $this, 'register_assets' ] );
    }

    public function get_name() {
        return 'featured-card-full';
    }

    public function get_title() {
        return __( 'WR Featured Card Full', 'wr-ew' );
    }

    public function get_icon() {
        return 'eicon-post-slider';
    }

    public function get_categories() {
        return [ 'wr-widgets', 'wr-ecommerce-elements' ];
    }

    public function get_style_depends() {
        return [ self::STYLE_HANDLE ];
    }

    public function get_script_depends() {
        return [ self::SCRIPT_HANDLE, 'wr-swiper' ];
    }

    public function register_assets() {

        if ( ! wp_style_is( self::STYLE_HANDLE, 'registered' ) ) {

            wp_register_style( self::STYLE_HANDLE, false, [], '2.3' );

            $css = "
/* ===== WR Featured Card Full ===== */
.wr-fcf__root{ width:100%; }

/* Full width hero mode */
.wr-fcf--fullbleed-yes .wr-fcf__root{
    width:100vw;
    position:relative;
    left:50%;
    margin-left:-50vw;
    margin-right:-50vw;
}

/* Box */
.wr-fcf__box{
    background: var(--wr-fcf-box-bg, #ffffff);
    border-radius: var(--wr-fcf-box-radius, 22px);
    box-shadow: var(--wr-fcf-box-shadow, 0 10px 30px rgba(0,0,0,.08));
    padding: var(--wr-fcf-box-pad, 34px);
}

/* Swiper */
.wr-fcf__swiper{ width:100%; padding: 10px 12px 52px; }
.wr-fcf__swiper .swiper-wrapper{ align-items: stretch !important; }
.wr-fcf__swiper .swiper-slide{
    height:auto !important;
    display:flex !important;
}

/* Card */
.wr-fcf__card{
    width:100%;
    height:100% !important;
    min-height: var(--wr-fcf-card-minh, 420px);

    background: var(--wr-fcf-card-bg, #ffffff);
    border: 1px solid var(--wr-fcf-card-border, rgba(0,0,0,.08));
    border-radius: var(--wr-fcf-card-radius, 18px);
    padding: var(--wr-fcf-card-pad, 18px);
    box-shadow: var(--wr-fcf-card-shadow, none);

    display:flex;
    flex-direction:column;
    gap: 12px;
}

/* Image */
.wr-fcf__img{
    width:100%;
    height: var(--wr-fcf-img-h, 260px);
    border-radius: var(--wr-fcf-img-radius, 14px);
    background: var(--wr-fcf-img-bg, transparent);
    overflow:hidden;

    display:flex;
    align-items:center;
    justify-content:center;
}
.wr-fcf__img img{
    width:100%;
    height:100%;
    object-fit: var(--wr-fcf-img-fit, contain); /* contain = kırpma yok */
    display:block;
}

/* Text */
.wr-fcf__title{
    margin: 0;
    font-size: 18px;
    line-height: 1.25;
    color: var(--wr-fcf-title-color, #111827);
}
.wr-fcf__text{
    margin: 0;
    font-size: 14px;
    line-height: 1.45;
    color: var(--wr-fcf-text-color, #4b5563);
}

/* Button */
.wr-fcf__actions{ margin-top:auto; display:flex; gap:10px; align-items:center; }
.wr-fcf__btn{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    padding: 11px 16px;

    border-radius: var(--wr-fcf-btn-radius, 8px);
    border: 1px solid var(--wr-fcf-btn-border, rgba(0,0,0,.35));
    background: var(--wr-fcf-btn-bg, #ffffff);
    color: var(--wr-fcf-btn-color, #111827);

    text-decoration:none;
    transition: background .15s ease, color .15s ease, border-color .15s ease;
    font-size: 13px;
    letter-spacing: .02em;
}
.wr-fcf__btn:hover{
    background: var(--wr-fcf-btn-bg-hover, var(--wr-fcf-btn-bg, #ffffff));
    color: var(--wr-fcf-btn-color-hover, var(--wr-fcf-btn-color, #111827));
    border-color: var(--wr-fcf-btn-border-hover, var(--wr-fcf-btn-border, rgba(0,0,0,.35)));
}

/* Arrows */
.wr-fcf__nav{
    position:absolute;
    top: 50%;
    transform: translateY(-50%);
    z-index: 20;

    width: 44px;
    height: 44px;
    border-radius: 999px;

    border: 1px solid var(--wr-fcf-nav-border, rgba(0,0,0,.12));
    background: var(--wr-fcf-nav-bg, #ffffff);
    box-shadow: var(--wr-fcf-nav-shadow, 0 10px 25px rgba(0,0,0,.08));

    display:flex;
    align-items:center;
    justify-content:center;
}
.wr-fcf__nav:after{ font-size: 16px; color: var(--wr-fcf-nav-color, #111827); }
.wr-fcf__nav--prev{ left: 6px; }
.wr-fcf__nav--next{ right: 6px; }

/* Pagination */
.wr-fcf__pagination{ bottom: 10px !important; }
.wr-fcf .swiper-pagination-bullet{ opacity: .35; }
.wr-fcf .swiper-pagination-bullet-active{ opacity: 1; }

@media (max-width: 1024px){
    .wr-fcf__box{ padding: 26px; }
    .wr-fcf__img{ height: var(--wr-fcf-img-h-t, 230px); }
    .wr-fcf__card{ min-height: var(--wr-fcf-card-minh-t, 380px); }
}
@media (max-width: 767px){
    .wr-fcf__box{ padding: 18px; }
    .wr-fcf__img{ height: var(--wr-fcf-img-h-m, 210px); }
    .wr-fcf__card{ min-height: var(--wr-fcf-card-minh-m, 360px); }
    .wr-fcf__nav{ display:none; }
}
";
            wp_add_inline_style( self::STYLE_HANDLE, $css );
        }

        if ( ! wp_script_is( self::SCRIPT_HANDLE, 'registered' ) ) {

            wp_register_script( self::SCRIPT_HANDLE, false, [ 'jquery', 'wr-swiper' ], '2.3', true );

            $js = "
(function($){
    function initOne(root){
        var \$root = $(root);
        if(!\$root.length) return;

        var \$swiperEl = \$root.find('.wr-fcf__swiper');
        if(!\$swiperEl.length) return;

        if(\$swiperEl.data('wrSwiperInited')) return;

        var slidesPerView = parseInt(\$swiperEl.attr('data-cols') || '4', 10);
        if(isNaN(slidesPerView) || slidesPerView < 1) slidesPerView = 4;

        var group = slidesPerView;

        var nextEl = \$root.find('.wr-fcf__nav--next')[0];
        var prevEl = \$root.find('.wr-fcf__nav--prev')[0];
        var pagEl  = \$root.find('.wr-fcf__pagination')[0];

        var swiper = new Swiper(\$swiperEl[0], {
            slidesPerView: slidesPerView,
            slidesPerGroup: group,
            spaceBetween: 18,
            watchOverflow: true,
            speed: 400,
            navigation: { nextEl: nextEl, prevEl: prevEl },
            pagination: { el: pagEl, clickable: true },
            breakpoints: {
                0:    { slidesPerView: 1, slidesPerGroup: 1, spaceBetween: 14 },
                640:  { slidesPerView: 2, slidesPerGroup: 2, spaceBetween: 16 },
                1024: { slidesPerView: slidesPerView, slidesPerGroup: group, spaceBetween: 18 }
            }
        });

        \$swiperEl.data('wrSwiperInited', true);
        \$swiperEl.data('wrSwiper', swiper);
    }

    function initAll(){
        $('.wr-fcf__root').each(function(){
            initOne(this);
        });
    }

    $(window).on('elementor/frontend/init', function(){
        if(window.elementorFrontend){
            elementorFrontend.hooks.addAction('frontend/element_ready/featured-card-full.default', function(){
                initAll();
            });
        }
    });

    $(document).ready(function(){ initAll(); });

})(jQuery);
";
            wp_add_inline_script( self::SCRIPT_HANDLE, $js );
        }
    }

    protected function register_controls() {

        /**
         * CONTENT
         */
        $this->start_controls_section(
            'section_content',
            [
                'label' => __( 'Cards', 'wr-ew' ),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'columns_per_view',
            [
                'label'   => __( 'Cards Per View (Desktop)', 'wr-ew' ),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => '4',
                'options' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                ],
            ]
        );

        $this->add_control(
            'full_bleed',
            [
                'label'        => __( 'Full Width (Hero Like)', 'wr-ew' ),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => __( 'Yes', 'wr-ew' ),
                'label_off'    => __( 'No', 'wr-ew' ),
                'return_value' => 'yes',
                'default'      => '',
                'prefix_class' => 'wr-fcf--fullbleed-',
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'image',
            [
                'label'   => __( 'Image', 'wr-ew' ),
                'type'    => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $repeater->add_control(
            'title',
            [
                'label'       => __( 'Title', 'wr-ew' ),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'default'     => __( 'Featured Item', 'wr-ew' ),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'text',
            [
                'label'       => __( 'Text', 'wr-ew' ),
                'type'        => \Elementor\Controls_Manager::TEXTAREA,
                'default'     => __( 'Short description goes here.', 'wr-ew' ),
                'rows'        => 4,
            ]
        );

        $repeater->add_control(
            'button_text',
            [
                'label'       => __( 'Button Text', 'wr-ew' ),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'default'     => __( 'Detaylar', 'wr-ew' ),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'link',
            [
                'label'         => __( 'Link', 'wr-ew' ),
                'type'          => \Elementor\Controls_Manager::URL,
                'placeholder'   => 'https://',
                'show_external' => true,
                'default'       => [
                    'url'         => '',
                    'is_external' => false,
                    'nofollow'    => false,
                ],
            ]
        );

        $this->add_control(
            'cards',
            [
                'label'       => __( 'Items', 'wr-ew' ),
                'type'        => \Elementor\Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    [
                        'title'       => 'Featured 1',
                        'text'        => 'Short text here.',
                        'button_text' => 'Detaylar',
                    ],
                    [
                        'title'       => 'Featured 2',
                        'text'        => 'Short text here.',
                        'button_text' => 'Detaylar',
                    ],
                ],
                'title_field' => '{{{ title }}}',
            ]
        );

        $this->end_controls_section();

        /**
         * STYLE: Box
         */
        $this->start_controls_section(
            'section_style_box',
            [
                'label' => __( 'Box', 'wr-ew' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'box_bg',
            [
                'label' => __( 'Background', 'wr-ew' ),
                'type'  => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-fcf__box' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'box_radius',
            [
                'label' => __( 'Radius', 'wr-ew' ),
                'type'  => \Elementor\Controls_Manager::SLIDER,
                'range' => [ 'px' => [ 'min' => 0, 'max' => 60 ] ],
                'selectors' => [
                    '{{WRAPPER}} .wr-fcf__box' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'box_padding',
            [
                'label' => __( 'Padding', 'wr-ew' ),
                'type'  => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .wr-fcf__box' =>
                        'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'box_shadow',
                'selector' => '{{WRAPPER}} .wr-fcf__box',
            ]
        );

        $this->end_controls_section();

        /**
         * STYLE: Cards
         */
        $this->start_controls_section(
            'section_style_cards',
            [
                'label' => __( 'Cards', 'wr-ew' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'card_bg',
            [
                'label' => __( 'Card Background', 'wr-ew' ),
                'type'  => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-fcf__root' => '--wr-fcf-card-bg: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'card_border',
            [
                'label' => __( 'Card Border Color', 'wr-ew' ),
                'type'  => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-fcf__root' => '--wr-fcf-card-border: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'card_radius',
            [
                'label' => __( 'Card Radius', 'wr-ew' ),
                'type'  => \Elementor\Controls_Manager::SLIDER,
                'range' => [ 'px' => [ 'min' => 0, 'max' => 50 ] ],
                'selectors' => [
                    '{{WRAPPER}} .wr-fcf__root' => '--wr-fcf-card-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'card_padding',
            [
                'label' => __( 'Card Padding', 'wr-ew' ),
                'type'  => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .wr-fcf__card' =>
                        'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'card_min_height',
            [
                'label' => __( 'Card Min Height', 'wr-ew' ),
                'type'  => \Elementor\Controls_Manager::SLIDER,
                'range' => [ 'px' => [ 'min' => 240, 'max' => 900 ] ],
                'default' => [ 'size' => 420, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .wr-fcf__root' => '--wr-fcf-card-minh: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'card_shadow',
                'selector' => '{{WRAPPER}} .wr-fcf__card',
            ]
        );

        $this->end_controls_section();

        /**
         * STYLE: Image
         */
        $this->start_controls_section(
            'section_style_image',
            [
                'label' => __( 'Image', 'wr-ew' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'img_height',
            [
                'label' => __( 'Image Height', 'wr-ew' ),
                'type'  => \Elementor\Controls_Manager::SLIDER,
                'range' => [ 'px' => [ 'min' => 120, 'max' => 520 ] ],
                'default' => [ 'size' => 260, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .wr-fcf__root' => '--wr-fcf-img-h: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'img_fit',
            [
                'label'   => __( 'Image Fit', 'wr-ew' ),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => 'contain',
                'options' => [
                    'contain' => __( 'Contain (No Crop)', 'wr-ew' ),
                    'cover'   => __( 'Cover (Crop)', 'wr-ew' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .wr-fcf__root' => '--wr-fcf-img-fit: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'img_radius',
            [
                'label' => __( 'Image Radius', 'wr-ew' ),
                'type'  => \Elementor\Controls_Manager::SLIDER,
                'range' => [ 'px' => [ 'min' => 0, 'max' => 40 ] ],
                'selectors' => [
                    '{{WRAPPER}} .wr-fcf__root' => '--wr-fcf-img-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'img_bg',
            [
                'label' => __( 'Image Background', 'wr-ew' ),
                'type'  => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-fcf__root' => '--wr-fcf-img-bg: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * STYLE: Typography + Colors
         */
        $this->start_controls_section(
            'section_style_text',
            [
                'label' => __( 'Text', 'wr-ew' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __( 'Title Color', 'wr-ew' ),
                'type'  => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-fcf__root' => '--wr-fcf-title-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typo',
                'selector' => '{{WRAPPER}} .wr-fcf__title',
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => __( 'Text Color', 'wr-ew' ),
                'type'  => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-fcf__root' => '--wr-fcf-text-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'text_typo',
                'selector' => '{{WRAPPER}} .wr-fcf__text',
            ]
        );

        $this->end_controls_section();

        /**
         * STYLE: Button + Arrows
         */
        $this->start_controls_section(
            'section_style_btn_nav',
            [
                'label' => __( 'Button & Arrows', 'wr-ew' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'btn_typo',
                'selector' => '{{WRAPPER}} .wr-fcf__btn',
            ]
        );

        $this->add_control(
            'btn_bg',
            [
                'label' => __( 'Button Background', 'wr-ew' ),
                'type'  => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-fcf__root' => '--wr-fcf-btn-bg: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btn_color',
            [
                'label' => __( 'Button Text Color', 'wr-ew' ),
                'type'  => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-fcf__root' => '--wr-fcf-btn-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btn_border',
            [
                'label' => __( 'Button Border Color', 'wr-ew' ),
                'type'  => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-fcf__root' => '--wr-fcf-btn-border: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btn_bg_hover',
            [
                'label' => __( 'Button Hover Background', 'wr-ew' ),
                'type'  => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-fcf__root' => '--wr-fcf-btn-bg-hover: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btn_color_hover',
            [
                'label' => __( 'Button Hover Text Color', 'wr-ew' ),
                'type'  => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-fcf__root' => '--wr-fcf-btn-color-hover: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btn_border_hover',
            [
                'label' => __( 'Button Hover Border Color', 'wr-ew' ),
                'type'  => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-fcf__root' => '--wr-fcf-btn-border-hover: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btn_radius',
            [
                'label' => __( 'Button Radius', 'wr-ew' ),
                'type'  => \Elementor\Controls_Manager::SLIDER,
                'range' => [ 'px' => [ 'min' => 0, 'max' => 40 ] ],
                'selectors' => [
                    '{{WRAPPER}} .wr-fcf__root' => '--wr-fcf-btn-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'nav_bg',
            [
                'label' => __( 'Arrow Background', 'wr-ew' ),
                'type'  => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-fcf__root' => '--wr-fcf-nav-bg: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'nav_color',
            [
                'label' => __( 'Arrow Icon Color', 'wr-ew' ),
                'type'  => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-fcf__root' => '--wr-fcf-nav-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'nav_border',
            [
                'label' => __( 'Arrow Border Color', 'wr-ew' ),
                'type'  => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-fcf__root' => '--wr-fcf-nav-border: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {

        $settings = $this->get_settings_for_display();
        $cards    = $settings['cards'] ?? [];
        $cols     = ! empty( $settings['columns_per_view'] ) ? (int) $settings['columns_per_view'] : 4;

        if ( $cols < 1 ) $cols = 1;
        if ( $cols > 5 ) $cols = 5;

        if ( empty( $cards ) ) return;

        $uid = 'wr-fcf-' . $this->get_id();

        echo '<div id="' . esc_attr( $uid ) . '" class="wr-fcf__root wr-fcf">';
            echo '<div class="wr-fcf__box">';

                echo '<div class="wr-fcf__nav wr-fcf__nav--prev swiper-button-prev"></div>';
                echo '<div class="wr-fcf__nav wr-fcf__nav--next swiper-button-next"></div>';

                echo '<div class="wr-fcf__swiper swiper" data-cols="' . esc_attr( $cols ) . '">';
                    echo '<div class="swiper-wrapper">';

                    foreach ( $cards as $item ) {

                        $img_url = '';
                        if ( ! empty( $item['image']['url'] ) ) {
                            $img_url = $item['image']['url'];
                        }

                        $title = isset( $item['title'] ) ? $item['title'] : '';
                        $text  = isset( $item['text'] ) ? $item['text'] : '';
                        $btn   = isset( $item['button_text'] ) ? $item['button_text'] : '';
                        $link  = $item['link'] ?? [];

                        $url = ! empty( $link['url'] ) ? $link['url'] : '';
                        $target = ! empty( $link['is_external'] ) ? ' target="_blank"' : '';
                        $nofollow = ! empty( $link['nofollow'] ) ? ' rel="nofollow"' : '';

                        echo '<div class="swiper-slide">';
                            echo '<div class="wr-fcf__card">';

                                if ( $img_url ) {
                                    echo '<div class="wr-fcf__img"><img src="' . esc_url( $img_url ) . '" alt="' . esc_attr( wp_strip_all_tags( $title ) ) . '"></div>';
                                }

                                if ( $title !== '' ) {
                                    echo '<h3 class="wr-fcf__title">' . esc_html( $title ) . '</h3>';
                                }

                                if ( $text !== '' ) {
                                    echo '<p class="wr-fcf__text">' . esc_html( $text ) . '</p>';
                                }

                                if ( $btn !== '' && $url !== '' ) {
                                    echo '<div class="wr-fcf__actions">';
                                        echo '<a class="wr-fcf__btn" href="' . esc_url( $url ) . '"' . $target . $nofollow . '>' . esc_html( $btn ) . '</a>';
                                    echo '</div>';
                                }

                            echo '</div>';
                        echo '</div>';
                    }

                    echo '</div>';
                    echo '<div class="wr-fcf__pagination swiper-pagination"></div>';
                echo '</div>';

            echo '</div>';
        echo '</div>';
    }
}

