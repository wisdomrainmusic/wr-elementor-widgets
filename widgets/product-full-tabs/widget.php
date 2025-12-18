<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class WR_EW_Product_Full_Tabs extends \Elementor\Widget_Base {

    const STYLE_HANDLE  = 'wr-product-full-tabs-css';
    const SCRIPT_HANDLE = 'wr-product-full-tabs-js';

    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );

        add_action( 'wp_enqueue_scripts', [ $this, 'register_assets' ] );
        add_action( 'elementor/frontend/after_register_styles', [ $this, 'register_assets' ] );
        add_action( 'elementor/frontend/after_register_scripts', [ $this, 'register_assets' ] );
    }

    public function get_name() {
        return 'product-full-tabs';
    }

    public function get_title() {
        return __( 'WR Product Full Tabs', 'wr-ew' );
    }

    public function get_icon() {
        return 'eicon-tabs';
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
/* ===== WR Product Full Tabs ===== */
.wr-pft{ width:100%; }

/* Full width hero mode – doğru çalışan versiyon */
.wr-pft--fullbleed-yes .wr-pft{
    width:100vw;
    position:relative;
    left:50%;
    margin-left:-50vw;
    margin-right:-50vw;
}
.wr-pft--fullbleed-yes .wr-pft__box{
    width:100%;
    max-width:none;
}

.wr-pft__box{
    background: var(--wr-pft-box-bg, #ffffff);
    border-radius: var(--wr-pft-box-radius, 22px);
    box-shadow: var(--wr-pft-box-shadow, 0 10px 30px rgba(0,0,0,.08));
    padding: var(--wr-pft-box-pad, 34px);
}

.wr-pft__tabs{
    display:flex;
    flex-wrap:wrap;
    gap: 10px;
    justify-content: var(--wr-pft-tabs-align, center);
    margin-bottom: 22px;
}
.wr-pft__tab-btn{
    cursor:pointer;
    border: 1px solid var(--wr-pft-tab-border, rgba(0,0,0,.12));
    border-radius: 999px;
    padding: 10px 16px;
    background: var(--wr-pft-tab-bg, #0f172a);
    color: var(--wr-pft-tab-color, #ffffff);
    transition: all .2s ease;
    line-height: 1;
    user-select:none;
}
.wr-pft__tab-btn.is-active{
    background: var(--wr-pft-tab-bg-active, #111827);
    color: var(--wr-pft-tab-color-active, #ffffff);
    border-color: var(--wr-pft-tab-border-active, rgba(0,0,0,.12));
}

/* ✅ HOVER KAPALI: hover'da renk değişmesin */
.wr-pft__tab-btn:hover{
    background: var(--wr-pft-tab-bg, #0f172a);
    color: var(--wr-pft-tab-color, #ffffff);
    border-color: var(--wr-pft-tab-border, rgba(0,0,0,.12));
}

.wr-pft__tab-btn:focus{ outline:none; box-shadow: 0 0 0 3px rgba(59,130,246,.22); }

.wr-pft__panes{ position:relative; }
.wr-pft__pane{ display:none; }
.wr-pft__pane.is-active{ display:block; }

.wr-pft__swiper{
    width:100%;
    padding: 10px 12px 52px;
}

/* Equal height slide/cards */
.wr-pft .swiper-wrapper{
    align-items: stretch !important;
}
.wr-pft .swiper-slide{
    height: auto !important;
    display: flex !important;
}
.wr-pft__card{
    height: 100% !important;
    width: 100%;
    background: var(--wr-pft-card-bg, #ffffff);
    border: 1px solid var(--wr-pft-card-border, rgba(0,0,0,.08));
    border-radius: var(--wr-pft-card-radius, 14px);
    padding: var(--wr-pft-card-pad, 18px);
    box-shadow: var(--wr-pft-card-shadow, none);
    display:flex;
    flex-direction:column;
    justify-content:flex-start;
    gap: 12px;
}

.wr-pft__img{
    width:100%;
    min-height: var(--wr-pft-img-minh, 220px);
    border-radius: var(--wr-pft-img-radius, 12px);
    background: var(--wr-pft-img-bg, transparent);
    display:flex;
    align-items:center;
    justify-content:center;
    overflow:hidden;
}
.wr-pft__img img{
    width:100%;
    height:100%;
    object-fit: contain;
}

.wr-pft__title{
    margin: 0;
    font-size: 18px;
    line-height: 1.25;
    color: var(--wr-pft-title-color, #111827);
}
.wr-pft__title a{
    color: inherit;
    text-decoration: none;
}
.wr-pft__title a:hover{ text-decoration: underline; }

.wr-pft__price{
    font-size: 15px;
    color: var(--wr-pft-price-color, #4b5563);
    min-height: 24px;
}

.wr-pft__actions{
    margin-top: auto;
    display:flex;
    gap: 10px;
    align-items:center;
}
.wr-pft__btn{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    padding: 11px 16px;
    border-radius: var(--wr-pft-btn-radius, 2px);
    border: 1px solid var(--wr-pft-btn-border, rgba(0,0,0,.65));
    background: var(--wr-pft-btn-bg, #ffffff);
    color: var(--wr-pft-btn-color, #111827);
    text-decoration:none;
    transition: all .2s ease;
    letter-spacing: .08em;
    text-transform: uppercase;
    font-size: 12px;
}
.wr-pft__btn:hover{
    background: var(--wr-pft-btn-bg-hover, #111827);
    color: var(--wr-pft-btn-color-hover, #ffffff);
    border-color: var(--wr-pft-btn-border-hover, #111827);
}

.wr-pft__nav{
    position:absolute;
    top: 50%;
    transform: translateY(-50%);
    z-index: 20;
    width: 44px;
    height: 44px;
    border-radius: 999px;
    border: 1px solid var(--wr-pft-nav-border, rgba(0,0,0,.12));
    background: var(--wr-pft-nav-bg, #ffffff);
    box-shadow: var(--wr-pft-nav-shadow, 0 10px 25px rgba(0,0,0,.08));
    display:flex;
    align-items:center;
    justify-content:center;
}
.wr-pft__nav:after{ font-size: 16px; color: var(--wr-pft-nav-color, #111827); }
.wr-pft__nav--prev{ left: 6px; }
.wr-pft__nav--next{ right: 6px; }

.wr-pft__pagination{ bottom: 10px !important; }
.wr-pft .swiper-pagination-bullet{ opacity: .35; }
.wr-pft .swiper-pagination-bullet-active{ opacity: 1; }

@media (max-width: 1024px){
    .wr-pft__box{ padding: 26px; }
    .wr-pft__img{ min-height: 200px; }
}
@media (max-width: 767px){
    .wr-pft__box{ padding: 18px; }
    .wr-pft__img{ min-height: 180px; }
    .wr-pft__nav{ display:none; }
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

        var \$btns = \$root.find('.wr-pft__tab-btn');
        var \$panes = \$root.find('.wr-pft__pane');

        function activateTab(tabId){
            \$btns.removeClass('is-active');
            \$btns.filter('[data-tab=\"'+tabId+'\"]').addClass('is-active');

            \$panes.removeClass('is-active');
            var \$pane = \$panes.filter('[data-pane=\"'+tabId+'\"]').addClass('is-active');

            var \$swiperEl = \$pane.find('.wr-pft__swiper');
            if(\$swiperEl.length && !\$swiperEl.data('wrSwiperInited')){
                var slidesPerView = parseInt(\$swiperEl.attr('data-cols') || '3', 10);
                var group = slidesPerView;

                var nextEl = \$pane.find('.wr-pft__nav--next')[0];
                var prevEl = \$pane.find('.wr-pft__nav--prev')[0];
                var pagEl  = \$pane.find('.wr-pft__pagination')[0];

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
            }else if(\$swiperEl.length && \$swiperEl.data('wrSwiper')){
                try { \$swiperEl.data('wrSwiper').update(); } catch(e){}
            }
        }

        \$btns.on('click', function(){
            activateTab($(this).data('tab'));
        });

        var firstId = \$btns.first().data('tab');
        if(firstId) activateTab(firstId);
    }

    function initAll(){
        $('.wr-pft').each(function(){
            if($(this).data('wrPftInited')) return;
            $(this).data('wrPftInited', true);
            initOne(this);
        });
    }

    $(window).on('elementor/frontend/init', function(){
        if(window.elementorFrontend){
            elementorFrontend.hooks.addAction('frontend/element_ready/product-full-tabs.default', function(){
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

    private function get_product_categories_options() {
        $options = [];
        $terms = get_terms([
            'taxonomy'   => 'product_cat',
            'hide_empty' => false,
        ]);

        if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
            foreach ( $terms as $t ) {
                $options[ (string) $t->term_id ] = $t->name;
            }
        }
        return $options;
    }

    private function get_product_tags_options() {
        $options = [];
        $terms = get_terms([
            'taxonomy'   => 'product_tag',
            'hide_empty' => false,
        ]);

        if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
            foreach ( $terms as $t ) {
                $options[ (string) $t->term_id ] = $t->name;
            }
        }
        return $options;
    }

    protected function register_controls() {

        $this->start_controls_section(
            'section_content',
            [
                'label' => __( 'Tabs', 'wr-ew' ),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'columns_per_view',
            [
                'label'   => __( 'Cards Per View (Desktop)', 'wr-ew' ),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => '3',
                'options' => [
                    '3' => '3',
                    '4' => '4',
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
                'prefix_class' => 'wr-pft--fullbleed-',
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'tab_title',
            [
                'label'       => __( 'Tab Title', 'wr-ew' ),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'default'     => __( 'New Arrivals', 'wr-ew' ),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'query_type',
            [
                'label'   => __( 'Query Type', 'wr-ew' ),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => 'category',
                'options' => [
                    'category' => __( 'By Category', 'wr-ew' ),
                    'tag'      => __( 'By Tag', 'wr-ew' ),
                ],
            ]
        );

        $repeater->add_control(
            'category_id',
            [
                'label'       => __( 'Category', 'wr-ew' ),
                'type'        => \Elementor\Controls_Manager::SELECT2,
                'options'     => $this->get_product_categories_options(),
                'multiple'    => false,
                'label_block' => true,
                'condition'   => [ 'query_type' => 'category' ],
            ]
        );

        $repeater->add_control(
            'tag_id',
            [
                'label'       => __( 'Tag', 'wr-ew' ),
                'type'        => \Elementor\Controls_Manager::SELECT2,
                'options'     => $this->get_product_tags_options(),
                'multiple'    => false,
                'label_block' => true,
                'condition'   => [ 'query_type' => 'tag' ],
            ]
        );

        $repeater->add_control(
            'products_per_tab',
            [
                'label'   => __( 'Products Per Tab (Max 24)', 'wr-ew' ),
                'type'    => \Elementor\Controls_Manager::NUMBER,
                'min'     => 1,
                'max'     => 24,
                'step'    => 1,
                'default' => 12,
            ]
        );

        $this->add_control(
            'tabs',
            [
                'label'       => __( 'Product Tabs', 'wr-ew' ),
                'type'        => \Elementor\Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    [
                        'tab_title'        => 'Elbise',
                        'query_type'       => 'category',
                        'products_per_tab' => 12,
                    ]
                ],
                'title_field' => '{{{ tab_title }}}',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_tabs',
            [
                'label' => __( 'Tabs Style', 'wr-ew' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'tabs_alignment',
            [
                'label'   => __( 'Alignment', 'wr-ew' ),
                'type'    => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [ 'title' => __( 'Left', 'wr-ew' ), 'icon' => 'eicon-text-align-left' ],
                    'center'     => [ 'title' => __( 'Center', 'wr-ew' ), 'icon' => 'eicon-text-align-center' ],
                    'flex-end'   => [ 'title' => __( 'Right', 'wr-ew' ), 'icon' => 'eicon-text-align-right' ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .wr-pft' => '--wr-pft-tabs-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'box_bg',
            [
                'label' => __( 'Box Background', 'wr-ew' ),
                'type'  => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-pft' => '--wr-pft-box-bg: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'box_radius',
            [
                'label' => __( 'Box Radius', 'wr-ew' ),
                'type'  => \Elementor\Controls_Manager::SLIDER,
                'range' => [ 'px' => [ 'min' => 0, 'max' => 60 ] ],
                'selectors' => [
                    '{{WRAPPER}} .wr-pft' => '--wr-pft-box-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'box_padding',
            [
                'label' => __( 'Box Padding', 'wr-ew' ),
                'type'  => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .wr-pft__box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'box_shadow',
                'selector' => '{{WRAPPER}} .wr-pft__box',
            ]
        );

        $this->add_control(
            'tab_btn_bg',
            [
                'label' => __( 'Tab Background', 'wr-ew' ),
                'type'  => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-pft' => '--wr-pft-tab-bg: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tab_btn_color',
            [
                'label' => __( 'Tab Text Color', 'wr-ew' ),
                'type'  => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-pft' => '--wr-pft-tab-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tab_btn_bg_active',
            [
                'label' => __( 'Tab Active Background', 'wr-ew' ),
                'type'  => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-pft' => '--wr-pft-tab-bg-active: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tab_btn_color_active',
            [
                'label' => __( 'Tab Active Text Color', 'wr-ew' ),
                'type'  => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-pft' => '--wr-pft-tab-color-active: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'tab_typo',
                'selector' => '{{WRAPPER}} .wr-pft__tab-btn',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_title',
            [
                'label' => __( 'Product Title', 'wr-ew' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __( 'Color', 'wr-ew' ),
                'type'  => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-pft' => '--wr-pft-title-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typo',
                'selector' => '{{WRAPPER}} .wr-pft__title',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_btn',
            [
                'label' => __( 'Options Button', 'wr-ew' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'btn_bg',
            [
                'label' => __( 'Background', 'wr-ew' ),
                'type'  => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-pft' => '--wr-pft-btn-bg: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btn_color',
            [
                'label' => __( 'Text Color', 'wr-ew' ),
                'type'  => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-pft' => '--wr-pft-btn-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btn_border',
            [
                'label' => __( 'Border Color', 'wr-ew' ),
                'type'  => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-pft' => '--wr-pft-btn-border: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btn_bg_hover',
            [
                'label' => __( 'Hover Background', 'wr-ew' ),
                'type'  => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-pft' => '--wr-pft-btn-bg-hover: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btn_color_hover',
            [
                'label' => __( 'Hover Text Color', 'wr-ew' ),
                'type'  => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-pft' => '--wr-pft-btn-color-hover: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btn_radius',
            [
                'label' => __( 'Radius', 'wr-ew' ),
                'type'  => \Elementor\Controls_Manager::SLIDER,
                'range' => [ 'px' => [ 'min' => 0, 'max' => 40 ] ],
                'selectors' => [
                    '{{WRAPPER}} .wr-pft' => '--wr-pft-btn-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'btn_typo',
                'selector' => '{{WRAPPER}} .wr-pft__btn',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_cards',
            [
                'label' => __( 'Product Cards', 'wr-ew' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'card_bg',
            [
                'label' => __( 'Card Background', 'wr-ew' ),
                'type'  => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-pft' => '--wr-pft-card-bg: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'card_radius',
            [
                'label' => __( 'Card Radius', 'wr-ew' ),
                'type'  => \Elementor\Controls_Manager::SLIDER,
                'range' => [ 'px' => [ 'min' => 0, 'max' => 40 ] ],
                'selectors' => [
                    '{{WRAPPER}} .wr-pft' => '--wr-pft-card-radius: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .wr-pft__card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'card_shadow',
                'selector' => '{{WRAPPER}} .wr-pft__card',
            ]
        );

        $this->add_control(
            'img_min_height',
            [
                'label' => __( 'Image Area Min Height', 'wr-ew' ),
                'type'  => \Elementor\Controls_Manager::SLIDER,
                'range' => [ 'px' => [ 'min' => 120, 'max' => 420 ] ],
                'default' => [ 'size' => 220, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .wr-pft' => '--wr-pft-img-minh: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_misc',
            [
                'label' => __( 'Price & Arrows', 'wr-ew' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'price_color',
            [
                'label' => __( 'Price Color', 'wr-ew' ),
                'type'  => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-pft' => '--wr-pft-price-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'nav_bg',
            [
                'label' => __( 'Arrow Background', 'wr-ew' ),
                'type'  => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-pft' => '--wr-pft-nav-bg: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'nav_color',
            [
                'label' => __( 'Arrow Icon Color', 'wr-ew' ),
                'type'  => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-pft' => '--wr-pft-nav-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'nav_border',
            [
                'label' => __( 'Arrow Border Color', 'wr-ew' ),
                'type'  => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-pft' => '--wr-pft-nav-border: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    private function query_products( $tab ) {

        if ( ! class_exists( 'WooCommerce' ) ) {
            return [];
        }

        $limit = isset( $tab['products_per_tab'] ) ? (int) $tab['products_per_tab'] : 12;
        if ( $limit < 1 )  $limit = 1;
        if ( $limit > 24 ) $limit = 24;

        $tax_query = [];

        $query_type = $tab['query_type'] ?? 'category';

        if ( $query_type === 'tag' && ! empty( $tab['tag_id'] ) ) {
            $tax_query[] = [
                'taxonomy' => 'product_tag',
                'field'    => 'term_id',
                'terms'    => [ (int) $tab['tag_id'] ],
            ];
        } elseif ( ! empty( $tab['category_id'] ) ) {
            $tax_query[] = [
                'taxonomy' => 'product_cat',
                'field'    => 'term_id',
                'terms'    => [ (int) $tab['category_id'] ],
            ];
        }

        $args = [
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'posts_per_page' => $limit,
            'orderby'        => 'date',
            'order'          => 'DESC',
            'no_found_rows'  => true,
        ];

        if ( ! empty( $tax_query ) ) {
            $args['tax_query'] = $tax_query;
        }

        $q = new \WP_Query( $args );
        return $q->have_posts() ? $q->posts : [];
    }

    private function render_card( $post ) {

        $product = wc_get_product( $post->ID );
        if ( ! $product ) return;

        $permalink = get_permalink( $post->ID );
        $title     = get_the_title( $post->ID );
        $img_html  = $product->get_image( 'woocommerce_single' );
        $price     = $product->get_price_html();

        echo '<div class="swiper-slide">';
            echo '<div class="wr-pft__card">';
                echo '<div class="wr-pft__img">' . $img_html . '</div>';
                echo '<h3 class="wr-pft__title"><a href="' . esc_url( $permalink ) . '">' . esc_html( $title ) . '</a></h3>';
                echo '<div class="wr-pft__price">' . wp_kses_post( $price ) . '</div>';
                echo '<div class="wr-pft__actions">';
                    echo '<a class="wr-pft__btn" href="' . esc_url( $permalink ) . '">Seçenekler</a>';
                echo '</div>';
            echo '</div>';
        echo '</div>';
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $tabs     = $settings['tabs'] ?? [];
        $cols     = ! empty( $settings['columns_per_view'] ) ? (int) $settings['columns_per_view'] : 3;
        if ( $cols !== 4 ) $cols = 3;

        if ( empty( $tabs ) ) {
            return;
        }

        $uid = 'wr-pft-' . $this->get_id();
        echo '<div id="' . esc_attr( $uid ) . '" class="wr-pft">';
            echo '<div class="wr-pft__box">';

                echo '<div class="wr-pft__tabs">';
                foreach ( $tabs as $i => $tab ) {
                    $tab_id = $uid . '-tab-' . $i;
                    $title  = $tab['tab_title'] ?? ( 'Tab ' . ( $i + 1 ) );
                    echo '<button type="button" class="wr-pft__tab-btn" data-tab="' . esc_attr( $tab_id ) . '">' . esc_html( $title ) . '</button>';
                }
                echo '</div>';

                echo '<div class="wr-pft__panes">';
                foreach ( $tabs as $i => $tab ) {
                    $tab_id = $uid . '-tab-' . $i;
                    echo '<div class="wr-pft__pane" data-pane="' . esc_attr( $tab_id ) . '">';

                        echo '<div class="wr-pft__nav wr-pft__nav--prev swiper-button-prev"></div>';
                        echo '<div class="wr-pft__nav wr-pft__nav--next swiper-button-next"></div>';

                        echo '<div class="wr-pft__swiper swiper" data-cols="' . esc_attr( $cols ) . '">';
                            echo '<div class="swiper-wrapper">';

                                $posts = $this->query_products( $tab );
                                if ( empty( $posts ) ) {
                                    echo '<div class="swiper-slide">';
                                        echo '<div class="wr-pft__card">';
                                            echo '<div class="wr-pft__title">' . esc_html__( 'No products found for this tab.', 'wr-ew' ) . '</div>';
                                        echo '</div>';
                                    echo '</div>';
                                } else {
                                    foreach ( $posts as $p ) {
                                        $this->render_card( $p );
                                    }
                                }

                            echo '</div>';
                            echo '<div class="wr-pft__pagination swiper-pagination"></div>';
                        echo '</div>';

                    echo '</div>';
                }
                echo '</div>';

            echo '</div>';
        echo '</div>';
    }
}
