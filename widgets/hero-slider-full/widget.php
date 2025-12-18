<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class WR_EW_Hero_Slider_Full extends \Elementor\Widget_Base {

    const KEY = 'hero-slider-full';

    public function get_name() { return 'hero-slider-full'; }
    public function get_title() { return __( 'WR Hero Slider Full', 'wr-ew' ); }
    public function get_icon() { return 'eicon-slider-full-screen'; }
    public function get_categories() { return [ 'wr-widgets', 'wr-ecommerce-elements' ]; }

    public function get_style_depends() {
        return [ 'wr-' . self::KEY . '-css' ];
    }

    public function get_script_depends() {
        // IMPORTANT: 'wr-swiper' mevcutsa loader/plugin içinde zaten kayıtlı olmalı.
        return [ 'wr-' . self::KEY . '-js', 'wr-swiper' ];
    }

    protected function register_controls() {

        $this->start_controls_section('section_content', [
            'label' => __( 'Content', 'wr-ew' ),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);

        $repeater = new \Elementor\Repeater();

        $repeater->add_control('title', [
            'label' => __( 'Title', 'wr-ew' ),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => 'Your Slide Title',
            'label_block' => true,
        ]);

        $repeater->add_control('subtitle', [
            'label' => __( 'Subtitle', 'wr-ew' ),
            'type' => \Elementor\Controls_Manager::TEXTAREA,
            'default' => 'Your slide subtitle goes here.',
            'rows' => 3,
        ]);

        $repeater->add_control('button_text', [
            'label' => __( 'Button Text', 'wr-ew' ),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => 'Learn More',
        ]);

        $repeater->add_control('button_link', [
            'label' => __( 'Button Link', 'wr-ew' ),
            'type' => \Elementor\Controls_Manager::URL,
            'placeholder' => '#',
            'default' => [ 'url' => '#', 'is_external' => false, 'nofollow' => false ],
        ]);

        $repeater->add_control('bg_image', [
            'label' => __( 'Background Image', 'wr-ew' ),
            'type' => \Elementor\Controls_Manager::MEDIA,
            'default' => [ 'url' => \Elementor\Utils::get_placeholder_image_src() ],
        ]);

        $this->add_control('slides', [
            'label' => __( 'Slides', 'wr-ew' ),
            'type' => \Elementor\Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [
                [ 'title' => 'Your Slide Title' ],
                [ 'title' => 'Your Slide Title' ],
                [ 'title' => 'Your Slide Title' ],
            ],
            'title_field' => '{{{ title }}}',
        ]);

        $this->add_control('full_width_hero', [
            'label' => __( 'Full Width (Hero Like)', 'wr-ew' ),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default' => '',
        ]);

        $this->add_control('show_tabs', [
            'label' => __( 'Show Tabs', 'wr-ew' ),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default' => 'yes',
        ]);

        $this->end_controls_section();

        // Layout
        $this->start_controls_section('section_layout', [
            'label' => __( 'Layout', 'wr-ew' ),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('slider_height', [
            'label' => __( 'Slider Height', 'wr-ew' ),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'vh' ],
            'range' => [
                'px' => [ 'min' => 240, 'max' => 1200 ],
                'vh' => [ 'min' => 30, 'max' => 100 ],
            ],
            'default' => [ 'unit' => 'px', 'size' => 445 ],
            'selectors' => [
                '{{WRAPPER}} .wr-hero-slider-full' => 'height: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('overlay_color', [
            'label' => __( 'Overlay Color', 'wr-ew' ),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => 'rgba(0,0,0,0.25)',
            'selectors' => [
                '{{WRAPPER}} .wr-hero-slider-full__overlay' => 'background: {{VALUE}};',
            ],
        ]);

        $this->add_control('bg_fill_color', [
            'label' => __( 'Background Fill Color (Behind Image)', 'wr-ew' ),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#000000',
            'selectors' => [
                '{{WRAPPER}} .wr-hero-slider-full__bg' => 'background: {{VALUE}};',
            ],
        ]);

        $this->add_control('bg_fit_scale', [
            'label' => __( 'Background Zoom Out (Fit)', 'wr-ew' ),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ '%' ],
            'range' => [ '%' => [ 'min' => 70, 'max' => 140 ] ],
            'default' => [ 'unit' => '%', 'size' => 110 ],
            'selectors' => [
                '{{WRAPPER}} .wr-hero-slider-full__bgimg' => 'transform: translate(-50%, -50%) scale(calc({{SIZE}}/100));',
            ],
        ]);

        $this->add_responsive_control('hero_border_radius', [
            'label' => __( 'Hero Border Radius', 'wr-ew' ),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range' => [ 'px' => [ 'min' => 0, 'max' => 80 ] ],
            'default' => [ 'unit' => 'px', 'size' => 0 ],
            'selectors' => [
                '{{WRAPPER}} .wr-hero-slider-full' => 'border-radius: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('hero_inner_padding', [
            'label' => __( 'Hero Inner Padding', 'wr-ew' ),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px' ],
            'default' => [ 'top'=>16,'right'=>16,'bottom'=>16,'left'=>16,'unit'=>'px' ],
            'selectors' => [
                '{{WRAPPER}} .wr-hero-slider-full__inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();

        // Content box
        $this->start_controls_section('section_content_box', [
            'label' => __( 'Content Box', 'wr-ew' ),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('content_align', [
            'label' => __( 'Content Alignment', 'wr-ew' ),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'flex-start' => [ 'title' => __( 'Left', 'wr-ew' ), 'icon' => 'eicon-text-align-left' ],
                'center' => [ 'title' => __( 'Center', 'wr-ew' ), 'icon' => 'eicon-text-align-center' ],
                'flex-end' => [ 'title' => __( 'Right', 'wr-ew' ), 'icon' => 'eicon-text-align-right' ],
            ],
            'default' => 'flex-start',
            'selectors' => [
                '{{WRAPPER}} .wr-hero-slider-full__content-wrap' => 'justify-content: {{VALUE}};',
            ],
        ]);

        $this->add_responsive_control('move_x', [
            'label' => __( 'Move Left / Right', 'wr-ew' ),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range' => [ 'px' => [ 'min' => -600, 'max' => 600 ] ],
            'default' => [ 'unit' => 'px', 'size' => 0 ],
            'selectors' => [
                '{{WRAPPER}} .wr-hero-slider-full__content' => 'transform: translateX({{SIZE}}{{UNIT}}) translateY(var(--wr-move-y, 0px));',
            ],
        ]);

        $this->add_responsive_control('move_y', [
            'label' => __( 'Move Up / Down', 'wr-ew' ),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range' => [ 'px' => [ 'min' => -600, 'max' => 600 ] ],
            'default' => [ 'unit' => 'px', 'size' => 0 ],
            'selectors' => [
                '{{WRAPPER}} .wr-hero-slider-full__content' => '--wr-move-y: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('content_max_width', [
            'label' => __( 'Content Max Width', 'wr-ew' ),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range' => [ 'px' => [ 'min' => 200, 'max' => 900 ] ],
            'default' => [ 'unit' => 'px', 'size' => 420 ],
            'selectors' => [
                '{{WRAPPER}} .wr-hero-slider-full__content' => 'max-width: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('content_bg', [
            'label' => __( 'Content Background', 'wr-ew' ),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => 'rgba(0,0,0,0.0)',
            'selectors' => [
                '{{WRAPPER}} .wr-hero-slider-full__content' => 'background: {{VALUE}};',
            ],
        ]);

        $this->add_responsive_control('content_padding', [
            'label' => __( 'Content Padding', 'wr-ew' ),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px' ],
            'default' => [ 'top'=>0,'right'=>0,'bottom'=>0,'left'=>0,'unit'=>'px' ],
            'selectors' => [
                '{{WRAPPER}} .wr-hero-slider-full__content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'title_typo',
            'label' => __( 'Title Typography', 'wr-ew' ),
            'selector' => '{{WRAPPER}} .wr-hero-slider-full__title',
        ]);

        $this->add_control('title_color', [
            'label' => __( 'Title Color', 'wr-ew' ),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .wr-hero-slider-full__title' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'subtitle_typo',
            'label' => __( 'Subtitle Typography', 'wr-ew' ),
            'selector' => '{{WRAPPER}} .wr-hero-slider-full__subtitle',
        ]);

        $this->add_control('subtitle_color', [
            'label' => __( 'Subtitle Color', 'wr-ew' ),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .wr-hero-slider-full__subtitle' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'button_typo',
            'label' => __( 'Button Typography', 'wr-ew' ),
            'selector' => '{{WRAPPER}} .wr-hero-slider-full__btn',
        ]);

        $this->add_control('button_text_color', [
            'label' => __( 'Button Text Color', 'wr-ew' ),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .wr-hero-slider-full__btn' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('button_bg_color', [
            'label' => __( 'Button Background', 'wr-ew' ),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .wr-hero-slider-full__btn' => 'background: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();

        // Tabs style
        $this->start_controls_section('section_tabs_style', [
            'label' => __( 'Tabs Style', 'wr-ew' ),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            'condition' => [ 'show_tabs' => 'yes' ],
        ]);

        $this->add_responsive_control('tabs_alignment', [
            'label' => __( 'Alignment', 'wr-ew' ),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'flex-start' => [ 'title' => __( 'Left', 'wr-ew' ), 'icon' => 'eicon-text-align-left' ],
                'center' => [ 'title' => __( 'Center', 'wr-ew' ), 'icon' => 'eicon-text-align-center' ],
                'flex-end' => [ 'title' => __( 'Right', 'wr-ew' ), 'icon' => 'eicon-text-align-right' ],
            ],
            'default' => 'flex-start',
            'selectors' => [
                '{{WRAPPER}} .wr-hero-slider-full__tabs' => 'justify-content: {{VALUE}};',
            ],
        ]);

        $this->add_control('tabs_box_bg', [
            'label' => __( 'Box Background', 'wr-ew' ),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .wr-hero-slider-full__tabs-box' => 'background: {{VALUE}};',
            ],
        ]);

        $this->add_responsive_control('tabs_box_radius', [
            'label' => __( 'Box Radius', 'wr-ew' ),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range' => [ 'px' => [ 'min' => 0, 'max' => 60 ] ],
            'default' => [ 'unit' => 'px', 'size' => 25 ],
            'selectors' => [
                '{{WRAPPER}} .wr-hero-slider-full__tabs-box' => 'border-radius: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('tabs_box_padding', [
            'label' => __( 'Box Padding', 'wr-ew' ),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px' ],
            'default' => [ 'top'=>10,'right'=>10,'bottom'=>10,'left'=>10,'unit'=>'px' ],
            'selectors' => [
                '{{WRAPPER}} .wr-hero-slider-full__tabs-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_control('tab_bg', [
            'label' => __( 'Tab Background', 'wr-ew' ),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .wr-hero-slider-full__tab' => 'background: {{VALUE}};',
            ],
        ]);

        $this->add_control('tab_text', [
            'label' => __( 'Tab Text Color', 'wr-ew' ),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .wr-hero-slider-full__tab' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('tab_active_bg', [
            'label' => __( 'Tab Active Background', 'wr-ew' ),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .wr-hero-slider-full__tab.is-active' => 'background: {{VALUE}};',
            ],
        ]);

        $this->add_control('tab_active_text', [
            'label' => __( 'Tab Active Text Color', 'wr-ew' ),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .wr-hero-slider-full__tab.is-active' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'tab_typo',
            'label' => __( 'Typography', 'wr-ew' ),
            'selector' => '{{WRAPPER}} .wr-hero-slider-full__tab',
        ]);

        $this->end_controls_section();

        // Arrows
        $this->start_controls_section('section_arrows', [
            'label' => __( 'Arrows', 'wr-ew' ),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('arrow_color', [
            'label' => __( 'Arrow Color', 'wr-ew' ),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .wr-hero-slider-full__arrow' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('arrow_color_hover', [
            'label' => __( 'Arrow Color (Hover)', 'wr-ew' ),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .wr-hero-slider-full__arrow:hover' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_responsive_control('arrow_size', [
            'label' => __( 'Arrow Size', 'wr-ew' ),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range' => [ 'px' => [ 'min' => 24, 'max' => 80 ] ],
            'default' => [ 'unit' => 'px', 'size' => 44 ],
            'selectors' => [
                '{{WRAPPER}} .wr-hero-slider-full__arrow' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .wr-hero-slider-full__arrow svg' => 'width: calc({{SIZE}}{{UNIT}} * 0.45); height: calc({{SIZE}}{{UNIT}} * 0.45);',
            ],
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $slides = isset($settings['slides']) ? $settings['slides'] : [];

        if ( empty($slides) ) return;

        $uid = 'wrhsf-' . $this->get_id();
        $is_hero = (isset($settings['full_width_hero']) && $settings['full_width_hero'] === 'yes') ? 'is-hero' : '';
        $has_tabs = (isset($settings['show_tabs']) && $settings['show_tabs'] === 'yes') ? 'has-tabs' : 'no-tabs';

        ?>
        <div class="wr-hero-slider-full <?php echo esc_attr($is_hero . ' ' . $has_tabs); ?>" data-wr-hero-slider-full="<?php echo esc_attr($uid); ?>">
            <div class="wr-hero-slider-full__inner">

                <div class="wr-hero-slider-full__swiper swiper" id="<?php echo esc_attr($uid); ?>">
                    <div class="swiper-wrapper">
                        <?php foreach ( $slides as $index => $s ) :
                            $title = isset($s['title']) ? $s['title'] : '';
                            $subtitle = isset($s['subtitle']) ? $s['subtitle'] : '';
                            $btn_text = isset($s['button_text']) ? $s['button_text'] : '';
                            $btn_link = isset($s['button_link']['url']) ? $s['button_link']['url'] : '';
                            $btn_is_ext = !empty($s['button_link']['is_external']);
                            $btn_nofollow = !empty($s['button_link']['nofollow']);
                            $bg = isset($s['bg_image']['url']) ? $s['bg_image']['url'] : '';
                        ?>
                        <div class="wr-hero-slider-full__slide swiper-slide">
                            <div class="wr-hero-slider-full__bg">
                                <?php if ($bg): ?>
                                    <img class="wr-hero-slider-full__bgimg" src="<?php echo esc_url($bg); ?>" alt="" loading="lazy" />
                                <?php endif; ?>
                                <div class="wr-hero-slider-full__overlay"></div>
                            </div>

                            <div class="wr-hero-slider-full__content-wrap">
                                <div class="wr-hero-slider-full__content">
                                    <?php if ($title): ?><div class="wr-hero-slider-full__title"><?php echo esc_html($title); ?></div><?php endif; ?>
                                    <?php if ($subtitle): ?><div class="wr-hero-slider-full__subtitle"><?php echo esc_html($subtitle); ?></div><?php endif; ?>

                                    <?php if ($btn_text && $btn_link):
                                        $rel = [];

                                        if ( $btn_is_ext ) {
                                            $rel[] = 'noopener';
                                            $rel[] = 'noreferrer';
                                        }

                                        if ( $btn_nofollow ) {
                                            $rel[] = 'nofollow';
                                        }

                                        $rel_attr = ! empty( $rel ) ? ' rel="' . esc_attr( implode( ' ', $rel ) ) . '"' : '';
                                    ?>
                                        <a class="wr-hero-slider-full__btn"
                                           href="<?php echo esc_url($btn_link); ?>"
                                           <?php echo $btn_is_ext ? 'target="_blank"' : ''; ?>
                                           <?php echo $rel_attr; ?>>
                                            <?php echo esc_html($btn_text); ?>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <button class="wr-hero-slider-full__arrow wr-hero-slider-full__arrow--prev" type="button" aria-label="Previous slide">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M15.5 19a1 1 0 0 1-.7-.3l-6-6a1 1 0 0 1 0-1.4l6-6a1 1 0 1 1 1.4 1.4L11 12l4.9 4.9A1 1 0 0 1 15.5 19z"/></svg>
                    </button>

                    <button class="wr-hero-slider-full__arrow wr-hero-slider-full__arrow--next" type="button" aria-label="Next slide">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M8.5 19a1 1 0 0 1-.7-1.7L12.7 12 7.8 7.1a1 1 0 1 1 1.4-1.4l6 6a1 1 0 0 1 0 1.4l-6 6a1 1 0 0 1-.7.3z"/></svg>
                    </button>
                </div>

                <?php if ($has_tabs === 'has-tabs'): ?>
                <div class="wr-hero-slider-full__tabs">
                    <div class="wr-hero-slider-full__tabs-box">
                        <?php foreach ( $slides as $i => $s ):
                            $t = isset($s['title']) ? $s['title'] : ('Slide ' . ($i+1));
                        ?>
                            <button type="button" class="wr-hero-slider-full__tab<?php echo $i === 0 ? ' is-active' : ''; ?>" data-wr-tab-index="<?php echo esc_attr($i); ?>">
                                <?php echo esc_html($t); ?>
                            </button>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

            </div>
        </div>
        <?php
    }
}
