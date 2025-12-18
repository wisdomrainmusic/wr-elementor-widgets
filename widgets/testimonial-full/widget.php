<?php
if ( ! defined( 'ABSPATH' ) ) exit;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

class WR_EW_Testimonial_Full extends Widget_Base {

    public function get_name() { return 'wr-testimonial-full'; }
    public function get_title() { return __( 'WR Testimonial Full', 'wr-ew' ); }
    public function get_icon() { return 'eicon-testimonial-carousel'; }
    public function get_categories() { return [ 'wr-widgets' ]; }

    public function get_style_depends() { return [ 'wr-testimonial-full-css' ]; }
    public function get_script_depends() { return [ 'wr-testimonial-full-js', 'wr-swiper' ]; }

    protected function register_controls() {

        /* CONTENT */
        $this->start_controls_section('content_section', [ 'label' => __( 'Testimonials', 'wr-ew' ) ]);

        $rep = new Repeater();
        $rep->add_control('avatar', [
            'label' => 'Avatar',
            'type' => Controls_Manager::MEDIA,
            'default' => [ 'url' => '' ],
        ]);
        $rep->add_control('name', [
            'label' => 'Name',
            'type' => Controls_Manager::TEXT,
            'default' => 'Ayşe K.'
        ]);
        $rep->add_control('title', [
            'label' => 'Title / Role',
            'type' => Controls_Manager::TEXT,
            'default' => 'Verified Buyer'
        ]);
        $rep->add_control('text', [
            'label' => 'Text',
            'type' => Controls_Manager::TEXTAREA,
            'default' => 'This is a testimonial text.'
        ]);
        $rep->add_control('rating', [
            'label' => 'Stars',
            'type' => Controls_Manager::NUMBER,
            'min' => 1,
            'max' => 5,
            'default' => 5
        ]);

        $this->add_control('items', [
            'type' => Controls_Manager::REPEATER,
            'fields' => $rep->get_controls(),
            'title_field' => '{{{ name }}}',
            'default' => [],
        ]);

        $this->end_controls_section();

        /* SLIDER */
        $this->start_controls_section('slider_section', [ 'label' => __( 'Slider Settings', 'wr-ew' ) ]);

        $this->add_responsive_control('per_view', [
            'label' => 'Cards Per View',
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ '' ],
            'range' => [ '' => [ 'min' => 1, 'max' => 4, 'step' => 1 ] ],
            'default' => [ 'size' => 3 ],
            'tablet_default' => [ 'size' => 2 ],
            'mobile_default' => [ 'size' => 1 ],
        ]);

        $this->add_responsive_control('per_group', [
            'label' => 'Cards Per Group (Next/Prev)',
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ '' ],
            'range' => [ '' => [ 'min' => 1, 'max' => 4, 'step' => 1 ] ],
            'default' => [ 'size' => 3 ],
            'tablet_default' => [ 'size' => 2 ],
            'mobile_default' => [ 'size' => 1 ],
        ]);

        $this->add_control('space_between', [
            'label' => 'Space Between',
            'type' => Controls_Manager::NUMBER,
            'default' => 18,
            'min' => 0,
            'max' => 60,
        ]);

        $this->add_control('loop', [
            'label' => 'Loop',
            'type' => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default' => '',
        ]);

        $this->add_control('autoplay', [
            'label' => 'Autoplay',
            'type' => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default' => '',
        ]);

        $this->add_control('autoplay_delay', [
            'label' => 'Autoplay Delay (ms)',
            'type' => Controls_Manager::NUMBER,
            'default' => 3500,
            'min' => 1000,
            'max' => 10000,
            'condition' => [ 'autoplay' => 'yes' ],
        ]);

        $this->add_control('speed', [
            'label' => 'Transition Speed (ms)',
            'type' => Controls_Manager::NUMBER,
            'default' => 600,
            'min' => 100,
            'max' => 2000,
        ]);

        $this->end_controls_section();

        /* STYLE: WRAPPER */
        $this->start_controls_section('style_wrapper', [
            'label' => 'Wrapper',
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'wrapper_bg',
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .wr-tf',
            ]
        );

        $this->add_responsive_control('wrapper_max_width', [
            'label' => 'Max Width',
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ 'px', '%' ],
            'range' => [
                'px' => [ 'min' => 300, 'max' => 1600 ],
                '%'  => [ 'min' => 50,  'max' => 100 ],
            ],
            'default' => [ 'unit' => '%', 'size' => 100 ],
            'selectors' => [
                '{{WRAPPER}} .wr-tf' => 'max-width: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('wrapper_align', [
            'label' => 'Align',
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'flex-start' => [ 'title' => 'Left', 'icon' => 'eicon-text-align-left' ],
                'center' => [ 'title' => 'Center', 'icon' => 'eicon-text-align-center' ],
                'flex-end' => [ 'title' => 'Right', 'icon' => 'eicon-text-align-right' ],
            ],
            'default' => 'center',
            'selectors' => [
                '{{WRAPPER}} .wr-tf-wrap' => 'justify-content: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();

        /* STYLE: CARD */
        $this->start_controls_section('style_card', [
            'label' => 'Card',
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('card_bg', [
            'label' => 'Background',
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .wr-tf-card' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(Group_Control_Border::get_type(), [
            'name' => 'card_border',
            'selector' => '{{WRAPPER}} .wr-tf-card',
        ]);

        $this->add_control('card_radius', [
            'label' => 'Border Radius',
            'type' => Controls_Manager::SLIDER,
            'range' => [ 'px' => [ 'min' => 0, 'max' => 40 ] ],
            'default' => [ 'size' => 18 ],
            'selectors' => [
                '{{WRAPPER}} .wr-tf-card' => 'border-radius: {{SIZE}}px;',
            ],
        ]);

        $this->add_responsive_control('card_padding', [
            'label' => 'Padding',
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px' ],
            'default' => [ 'top'=>22,'right'=>22,'bottom'=>22,'left'=>22,'unit'=>'px' ],
            'selectors' => [
                '{{WRAPPER}} .wr-tf-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_group_control(Group_Control_Box_Shadow::get_type(), [
            'name' => 'card_shadow',
            'selector' => '{{WRAPPER}} .wr-tf-card',
        ]);

        $this->end_controls_section();

        /* STYLE: AVATAR */
        $this->start_controls_section('style_avatar', [
            'label' => 'Avatar',
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('avatar_size', [
            'label' => 'Size',
            'type' => Controls_Manager::SLIDER,
            'range' => [ 'px' => [ 'min' => 28, 'max' => 90 ] ],
            'default' => [ 'size' => 44 ],
            'selectors' => [
                '{{WRAPPER}} .wr-tf-avatar' => 'width: {{SIZE}}px; height: {{SIZE}}px;',
            ],
        ]);

        $this->add_control('avatar_radius', [
            'label' => 'Radius',
            'type' => Controls_Manager::SLIDER,
            'range' => [ 'px' => [ 'min' => 0, 'max' => 50 ] ],
            'default' => [ 'size' => 50 ],
            'selectors' => [
                '{{WRAPPER}} .wr-tf-avatar' => 'border-radius: {{SIZE}}px;',
            ],
        ]);

        $this->end_controls_section();

        /* STYLE: TEXT */
        $this->start_controls_section('style_text', [
            'label' => 'Text & Typography',
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('name_color', [
            'label' => 'Name Color',
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .wr-tf-name' => 'color: {{VALUE}};',
            ],
        ]);
        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'name_typo',
            'selector' => '{{WRAPPER}} .wr-tf-name',
        ]);

        $this->add_control('title_color', [
            'label' => 'Title Color',
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .wr-tf-title' => 'color: {{VALUE}};',
            ],
        ]);
        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'title_typo',
            'selector' => '{{WRAPPER}} .wr-tf-title',
        ]);

        $this->add_control('text_color', [
            'label' => 'Review Text Color',
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .wr-tf-text' => 'color: {{VALUE}};',
            ],
        ]);
        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'text_typo',
            'selector' => '{{WRAPPER}} .wr-tf-text',
        ]);

        $this->end_controls_section();

        /* STYLE: STARS */
        $this->start_controls_section('style_stars', [
            'label' => 'Stars',
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('star_color', [
            'label' => 'Star Color',
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .wr-tf-stars span.is-on' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('star_off_color', [
            'label' => 'Star Empty Color',
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .wr-tf-stars span.is-off' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('star_size', [
            'label' => 'Star Size',
            'type' => Controls_Manager::SLIDER,
            'range' => [ 'px' => [ 'min' => 12, 'max' => 28 ] ],
            'default' => [ 'size' => 16 ],
            'selectors' => [
                '{{WRAPPER}} .wr-tf-stars' => 'font-size: {{SIZE}}px;',
            ],
        ]);

        $this->end_controls_section();

        /* STYLE: ARROWS & DOTS */
        $this->start_controls_section('style_nav', [
            'label' => 'Arrows & Dots',
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('arrow_bg', [
            'label' => 'Arrow Background',
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .wr-tf-prev, {{WRAPPER}} .wr-tf-next' => 'background: {{VALUE}};',
            ],
        ]);
        $this->add_control('arrow_color', [
            'label' => 'Arrow Color',
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .wr-tf-prev, {{WRAPPER}} .wr-tf-next' => 'color: {{VALUE}};',
            ],
        ]);
        $this->add_control('dot_color', [
            'label' => 'Dot Color',
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .wr-tf-pagination .swiper-pagination-bullet' => 'background: {{VALUE}};',
            ],
        ]);
        $this->add_control('dot_active_color', [
            'label' => 'Dot Active Color',
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .wr-tf-pagination .swiper-pagination-bullet-active' => 'background: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        if ( empty($s['items']) ) return;

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
        <div class="wr-tf-wrap">
            <div class="wr-tf" data-settings="<?php echo esc_attr( wp_json_encode($data) ); ?>">
                <div class="swiper">
                    <div class="swiper-wrapper">
                        <?php foreach ( $s['items'] as $item ) :
                            $avatar_url = !empty($item['avatar']['url']) ? $item['avatar']['url'] : '';
                            $name  = isset($item['name']) ? $item['name'] : '';
                            $title = isset($item['title']) ? $item['title'] : '';
                            $text  = isset($item['text']) ? $item['text'] : '';
                            $rating = isset($item['rating']) ? (int)$item['rating'] : 5;
                            if ($rating < 1) $rating = 1;
                            if ($rating > 5) $rating = 5;
                        ?>
                        <div class="swiper-slide">
                            <div class="wr-tf-card">
                                <div class="wr-tf-top">
                                    <div class="wr-tf-left">
                                        <div class="wr-tf-avatar">
                                            <?php if ($avatar_url): ?>
                                                <img src="<?php echo esc_url($avatar_url); ?>" alt="<?php echo esc_attr($name); ?>">
                                            <?php else: ?>
                                                <span class="wr-tf-avatar-fallback"></span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="wr-tf-meta">
                                            <div class="wr-tf-name"><?php echo esc_html($name); ?></div>
                                            <div class="wr-tf-title"><?php echo esc_html($title); ?></div>
                                        </div>
                                    </div>
                                    <div class="wr-tf-stars" aria-label="Rating">
                                        <?php for ($i=1; $i<=5; $i++): ?>
                                            <span class="<?php echo ($i <= $rating) ? 'is-on' : 'is-off'; ?>">★</span>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <div class="wr-tf-text"><?php echo esc_html($text); ?></div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="wr-tf-pagination"></div>
                    <button type="button" class="wr-tf-prev" aria-label="Previous">‹</button>
                    <button type="button" class="wr-tf-next" aria-label="Next">›</button>
                </div>
            </div>
        </div>
        <?php
    }
}
