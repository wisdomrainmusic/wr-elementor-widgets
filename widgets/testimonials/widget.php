<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class WR_EW_Testimonials extends \Elementor\Widget_Base {

    public function get_name() { return 'wr-testimonials'; }
    public function get_title() { return __( 'WR Testimonials', 'wr-ew' ); }
    public function get_icon() { return 'eicon-testimonial-carousel'; }
    public function get_categories() { return [ 'wr-widgets' ]; }

    public function get_style_depends() { return [ 'wr-testimonials-css' ]; }
    public function get_script_depends() { return [ 'wr-testimonials-js', 'wr-swiper' ]; }

    protected function register_controls() {

        /* =========================
         * CONTENT
         * ========================= */
        $this->start_controls_section('content_section', [
            'label' => __( 'Testimonials', 'wr-ew' )
        ]);

        $repeater = new \Elementor\Repeater();

        $repeater->add_control('avatar', [
            'label' => 'Avatar',
            'type'  => \Elementor\Controls_Manager::MEDIA,
            'default' => [ 'url' => '' ],
        ]);

        $repeater->add_control('name', [
            'label' => 'Name',
            'type'  => \Elementor\Controls_Manager::TEXT,
            'default' => 'Ayşe K.'
        ]);

        $repeater->add_control('title', [
            'label' => 'Title / Role',
            'type'  => \Elementor\Controls_Manager::TEXT,
            'default' => 'Verified Buyer'
        ]);

        $repeater->add_control('text', [
            'label' => 'Text',
            'type'  => \Elementor\Controls_Manager::TEXTAREA,
            'default' => 'This is a testimonial text.'
        ]);

        $repeater->add_control('rating', [
            'label' => 'Stars',
            'type'  => \Elementor\Controls_Manager::NUMBER,
            'min' => 1,
            'max' => 5,
            'default' => 5
        ]);

        $this->add_control('items', [
            'type' => \Elementor\Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'title_field' => '{{{ name }}}',
            'default' => []
        ]);

        $this->end_controls_section();

        /* =========================
         * SLIDER SETTINGS
         * ========================= */
        $this->start_controls_section('slider_section', [
            'label' => __( 'Slider Settings', 'wr-ew' ),
        ]);

        $this->add_responsive_control('per_view', [
            'label' => 'Cards Per View',
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ '' ],
            'range' => [ '' => [ 'min' => 1, 'max' => 4, 'step' => 1 ] ],
            'default' => [ 'size' => 3 ],
            'tablet_default' => [ 'size' => 2 ],
            'mobile_default' => [ 'size' => 1 ],
        ]);

        $this->add_responsive_control('per_group', [
            'label' => 'Cards Per Group (Next/Prev)',
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ '' ],
            'range' => [ '' => [ 'min' => 1, 'max' => 4, 'step' => 1 ] ],
            'default' => [ 'size' => 3 ],
            'tablet_default' => [ 'size' => 2 ],
            'mobile_default' => [ 'size' => 1 ],
        ]);

        $this->add_control('space_between', [
            'label' => 'Space Between',
            'type' => \Elementor\Controls_Manager::NUMBER,
            'default' => 18,
            'min' => 0,
            'max' => 60,
        ]);

        $this->add_control('loop', [
            'label' => 'Loop',
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default' => '',
        ]);

        $this->add_control('autoplay', [
            'label' => 'Autoplay',
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default' => '',
        ]);

        $this->add_control('autoplay_delay', [
            'label' => 'Autoplay Delay (ms)',
            'type' => \Elementor\Controls_Manager::NUMBER,
            'default' => 3500,
            'min' => 1000,
            'max' => 10000,
            'condition' => [ 'autoplay' => 'yes' ],
        ]);

        $this->add_control('speed', [
            'label' => 'Transition Speed (ms)',
            'type' => \Elementor\Controls_Manager::NUMBER,
            'default' => 600,
            'min' => 100,
            'max' => 2000,
        ]);

        $this->end_controls_section();

        /* =========================
         * STYLE: WRAPPER
         * ========================= */
        $this->start_controls_section('style_wrapper', [
            'label' => 'Wrapper',
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('wrapper_max_width', [
            'label' => 'Max Width',
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px', '%' ],
            'range' => [
                'px' => [ 'min' => 300, 'max' => 1400 ],
                '%'  => [ 'min' => 50,  'max' => 100 ],
            ],
            'default' => [ 'unit' => '%', 'size' => 100 ],
            'selectors' => [
                '{{WRAPPER}} .wr-testimonials' => 'max-width: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('wrapper_align', [
            'label' => 'Align',
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'flex-start' => [ 'title' => 'Left', 'icon' => 'eicon-text-align-left' ],
                'center'     => [ 'title' => 'Center', 'icon' => 'eicon-text-align-center' ],
                'flex-end'   => [ 'title' => 'Right', 'icon' => 'eicon-text-align-right' ],
            ],
            'default' => 'center',
            'selectors' => [
                '{{WRAPPER}} .wr-testimonials-wrap' => 'justify-content: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();

        /* =========================
         * STYLE: CARD
         * ========================= */
        $this->start_controls_section('style_card', [
            'label' => 'Card',
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('card_bg', [
            'label' => 'Background',
            'type'  => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .wr-testimonial-card' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Border::get_type(), [
            'name' => 'card_border',
            'selector' => '{{WRAPPER}} .wr-testimonial-card',
        ]);

        $this->add_control('card_radius', [
            'label' => 'Border Radius',
            'type'  => \Elementor\Controls_Manager::SLIDER,
            'range' => [ 'px' => [ 'min' => 0, 'max' => 40 ] ],
            'default' => [ 'size' => 18 ],
            'selectors' => [
                '{{WRAPPER}} .wr-testimonial-card' => 'border-radius: {{SIZE}}px;',
            ],
        ]);

        $this->add_responsive_control('card_padding', [
            'label' => 'Padding',
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px' ],
            'default' => [ 'top'=>22,'right'=>22,'bottom'=>22,'left'=>22,'unit'=>'px' ],
            'selectors' => [
                '{{WRAPPER}} .wr-testimonial-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Box_Shadow::get_type(), [
            'name' => 'card_shadow',
            'selector' => '{{WRAPPER}} .wr-testimonial-card',
        ]);

        $this->end_controls_section();

        /* =========================
         * STYLE: AVATAR
         * ========================= */
        $this->start_controls_section('style_avatar', [
            'label' => 'Avatar',
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('avatar_size', [
            'label' => 'Size',
            'type'  => \Elementor\Controls_Manager::SLIDER,
            'range' => [ 'px' => [ 'min' => 28, 'max' => 90 ] ],
            'default' => [ 'size' => 44 ],
            'selectors' => [
                '{{WRAPPER}} .wr-avatar' => 'width: {{SIZE}}px; height: {{SIZE}}px;',
            ],
        ]);

        $this->add_control('avatar_radius', [
            'label' => 'Radius',
            'type'  => \Elementor\Controls_Manager::SLIDER,
            'range' => [ 'px' => [ 'min' => 0, 'max' => 50 ] ],
            'default' => [ 'size' => 50 ],
            'selectors' => [
                '{{WRAPPER}} .wr-avatar' => 'border-radius: {{SIZE}}px;',
            ],
        ]);

        $this->end_controls_section();

        /* =========================
         * STYLE: TYPOGRAPHY + COLORS
         * ========================= */
        $this->start_controls_section('style_text', [
            'label' => 'Text & Typography',
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('name_color', [
            'label' => 'Name Color',
            'type'  => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .wr-name' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'name_typo',
            'selector' => '{{WRAPPER}} .wr-name',
        ]);

        $this->add_control('title_color', [
            'label' => 'Title Color',
            'type'  => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .wr-title' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'title_typo',
            'selector' => '{{WRAPPER}} .wr-title',
        ]);

        $this->add_control('text_color', [
            'label' => 'Review Text Color',
            'type'  => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .wr-text' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'text_typo',
            'selector' => '{{WRAPPER}} .wr-text',
        ]);

        $this->end_controls_section();

        /* =========================
         * STYLE: STARS
         * ========================= */
        $this->start_controls_section('style_stars', [
            'label' => 'Stars',
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('star_color', [
            'label' => 'Star Color',
            'type'  => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .wr-stars span.is-on' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('star_off_color', [
            'label' => 'Star Empty Color',
            'type'  => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .wr-stars span.is-off' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('star_size', [
            'label' => 'Star Size',
            'type'  => \Elementor\Controls_Manager::SLIDER,
            'range' => [ 'px' => [ 'min' => 12, 'max' => 28 ] ],
            'default' => [ 'size' => 16 ],
            'selectors' => [
                '{{WRAPPER}} .wr-stars' => 'font-size: {{SIZE}}px;',
            ],
        ]);

        $this->end_controls_section();

        /* =========================
         * STYLE: ARROWS & DOTS
         * ========================= */
        $this->start_controls_section('style_nav', [
            'label' => 'Arrows & Dots',
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('arrow_bg', [
            'label' => 'Arrow Background',
            'type'  => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .wr-t-prev, {{WRAPPER}} .wr-t-next' => 'background: {{VALUE}};',
            ],
        ]);

        $this->add_control('arrow_color', [
            'label' => 'Arrow Color',
            'type'  => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .wr-t-prev, {{WRAPPER}} .wr-t-next' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('dot_color', [
            'label' => 'Dot Color',
            'type'  => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .wr-t-pagination .swiper-pagination-bullet' => 'background: {{VALUE}};',
            ],
        ]);

        $this->add_control('dot_active_color', [
            'label' => 'Dot Active Color',
            'type'  => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .wr-t-pagination .swiper-pagination-bullet-active' => 'background: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        if ( empty($s['items']) ) return;

        $uid = 'wr-t-' . $this->get_id();

        $per_view_d = !empty($s['per_view']['size']) ? (int)$s['per_view']['size'] : 3;
        $per_view_t = !empty($s['per_view_tablet']['size']) ? (int)$s['per_view_tablet']['size'] : 2;
        $per_view_m = !empty($s['per_view_mobile']['size']) ? (int)$s['per_view_mobile']['size'] : 1;

        $per_grp_d = !empty($s['per_group']['size']) ? (int)$s['per_group']['size'] : $per_view_d;
        $per_grp_t = !empty($s['per_group_tablet']['size']) ? (int)$s['per_group_tablet']['size'] : $per_view_t;
        $per_grp_m = !empty($s['per_group_mobile']['size']) ? (int)$s['per_group_mobile']['size'] : $per_view_m;

        $data = [
            'spaceBetween' => isset($s['space_between']) ? (int)$s['space_between'] : 18,
            'speed'        => isset($s['speed']) ? (int)$s['speed'] : 600,
            'loop'         => ($s['loop'] ?? '') === 'yes',
            'autoplay'     => ($s['autoplay'] ?? '') === 'yes',
            'autoplayDelay'=> isset($s['autoplay_delay']) ? (int)$s['autoplay_delay'] : 3500,
            'perView'      => [ 'd'=>$per_view_d,'t'=>$per_view_t,'m'=>$per_view_m ],
            'perGroup'     => [ 'd'=>$per_grp_d,'t'=>$per_grp_t,'m'=>$per_grp_m ],
        ];
        ?>
        <div class="wr-testimonials-wrap">
            <div class="wr-testimonials" id="<?php echo esc_attr($uid); ?>" data-settings="<?php echo esc_attr( wp_json_encode($data) ); ?>">
                <div class="swiper">
                    <div class="swiper-wrapper">
                        <?php foreach ( $s['items'] as $item ) :
                            $avatar_url = '';
                            if ( !empty($item['avatar']['url']) ) $avatar_url = $item['avatar']['url'];
                            $name  = isset($item['name']) ? $item['name'] : '';
                            $title = isset($item['title']) ? $item['title'] : '';
                            $text  = isset($item['text']) ? $item['text'] : '';
                            $rating = isset($item['rating']) ? (int)$item['rating'] : 5;
                            if ($rating < 1) $rating = 1;
                            if ($rating > 5) $rating = 5;
                        ?>
                            <div class="swiper-slide">
                                <div class="wr-testimonial-card">
                                    <div class="wr-top">
                                        <div class="wr-left">
                                            <div class="wr-avatar">
                                                <?php if ($avatar_url): ?>
                                                    <img src="<?php echo esc_url($avatar_url); ?>" alt="<?php echo esc_attr($name); ?>">
                                                <?php else: ?>
                                                    <span class="wr-avatar-fallback"></span>
                                                <?php endif; ?>
                                            </div>
                                            <div class="wr-meta">
                                                <div class="wr-name"><?php echo esc_html($name); ?></div>
                                                <div class="wr-title"><?php echo esc_html($title); ?></div>
                                            </div>
                                        </div>
                                        <div class="wr-stars" aria-label="Rating">
                                            <?php for ($i=1; $i<=5; $i++): ?>
                                                <span class="<?php echo ($i <= $rating) ? 'is-on' : 'is-off'; ?>">★</span>
                                            <?php endfor; ?>
                                        </div>
                                    </div>

                                    <div class="wr-text"><?php echo esc_html($text); ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="wr-t-pagination"></div>
                    <button type="button" class="wr-t-prev" aria-label="Previous">‹</button>
                    <button type="button" class="wr-t-next" aria-label="Next">›</button>
                </div>
            </div>
        </div>
        <?php
    }
}
