<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class WR_EW_Campaign_Bar extends \Elementor\Widget_Base {

    public function get_name() { return 'wr-campaign-bar'; }
    public function get_title() { return __( 'WR Campaign Bar', 'wr-ew' ); }
    public function get_icon() { return 'eicon-banner'; }
    public function get_categories() { return [ 'wr-widgets', 'wr-ecommerce-elements' ]; }
    public function get_style_depends() { return [ 'wr-campaign-bar-css' ]; }
    public function get_script_depends() { return [ 'wr-campaign-bar-js' ]; }

    protected function register_controls() {

        $this->start_controls_section('section_content', [
            'label' => __( 'Content', 'wr-ew' ),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('text', [
            'label' => __( 'Text', 'wr-ew' ),
            'type'  => \Elementor\Controls_Manager::TEXT,
            'default' => '🌟 Big Winter Sale – Up to 40% OFF!',
        ]);

        $this->add_control('url', [
            'label' => __( 'Link URL', 'wr-ew' ),
            'type'  => \Elementor\Controls_Manager::URL,
            'placeholder' => 'https://',
            'default' => [ 'url' => '#' ],
        ]);

        $this->add_control('text_mode', [
            'label' => __( 'Text Mode', 'wr-ew' ),
            'type'  => \Elementor\Controls_Manager::SELECT,
            'default' => 'normal',
            'options' => [
                'normal'   => __('Static Text', 'wr-ew'),
                'marquee'  => __('Scrolling / Marquee', 'wr-ew'),
            ],
        ]);

        $this->add_control('marquee_speed', [
            'label' => __( 'Marquee Speed', 'wr-ew' ),
            'type'  => \Elementor\Controls_Manager::SELECT,
            'default' => 'normal',
            'options' => [
                'slow'   => __('Slow', 'wr-ew'),
                'normal' => __('Normal', 'wr-ew'),
                'fast'   => __('Fast', 'wr-ew'),
            ],
            'condition' => [
                'text_mode' => 'marquee',
            ],
        ]);

        $this->add_control('full_width_hero', [
            'label' => __( 'Full Width (Hero)', 'wr-ew' ),
            'type'  => \Elementor\Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default' => '',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style', [
            'label' => __( 'Style', 'wr-ew' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('background_color', [
            'label' => __( 'Background Color', 'wr-ew' ),
            'type'  => \Elementor\Controls_Manager::COLOR,
            'default' => '#111111',
            'selectors' => [
                '{{WRAPPER}} .wr-campaign-bar' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('text_color', [
            'label' => __( 'Text Color', 'wr-ew' ),
            'type'  => \Elementor\Controls_Manager::COLOR,
            'default' => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .wr-campaign-bar-text' => 'color: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();
    }

    protected function render() {

        $s = $this->get_settings_for_display();

        $url  = ! empty( $s['url']['url'] ) ? $s['url']['url'] : '#';
        $mode = isset( $s['text_mode'] ) ? $s['text_mode'] : 'normal';

        $is_hero = ( ( $s['full_width_hero'] ?? '' ) === 'yes' );

        // ---- Marquee duration (tek kaynak: CSS variable) ----
        $speed = $s['marquee_speed'] ?? 'normal';

        $dur_map = [
            'slow'   => 32,
            'normal' => 22,
            'fast'   => 14,
        ];
        $base = $dur_map[ $speed ] ?? 22;

        // Hero mod biraz daha “premium yavaş”
        $duration = $is_hero ? (int) round($base * 1.25) : $base;
        $duration_css = max(8, $duration) . 's';

        $outer_classes = [ 'wr-campaign-bar-outer' ];
        if ( $mode === 'marquee' ) $outer_classes[] = 'wr-marquee-enabled';
        if ( $is_hero ) $outer_classes[] = 'wr-is-hero';

        $outer_class_attr = implode(' ', array_map('sanitize_html_class', $outer_classes));

        // İçerik tekrar sayısı (segment içi) — gerçek loop için 2 segment basacağız
        $repeat_count = 10;
        ?>
        <div class="<?php echo esc_attr( $outer_class_attr ); ?>" style="--wr-marquee-duration: <?php echo esc_attr($duration_css); ?>;">
            <div class="wr-campaign-bar">
                <a href="<?php echo esc_url( $url ); ?>" class="wr-campaign-bar-link">

                    <?php if ( $mode === 'marquee' ) : ?>

                        <div class="wr-marquee" aria-label="<?php echo esc_attr( wp_strip_all_tags( $s['text'] ) ); ?>">
                            <div class="wr-marquee-track" data-wr-marquee>
                                <div class="wr-marquee-segment" aria-hidden="false">
                                    <?php for ( $i = 0; $i < $repeat_count; $i++ ) : ?>
                                        <span class="wr-campaign-bar-text"><?php echo esc_html( $s['text'] ); ?></span>
                                    <?php endfor; ?>
                                </div>
                                <div class="wr-marquee-segment" aria-hidden="true">
                                    <?php for ( $i = 0; $i < $repeat_count; $i++ ) : ?>
                                        <span class="wr-campaign-bar-text"><?php echo esc_html( $s['text'] ); ?></span>
                                    <?php endfor; ?>
                                </div>
                            </div>
                        </div>

                    <?php else : ?>

                        <span class="wr-campaign-bar-text">
                            <?php echo esc_html( $s['text'] ); ?>
                        </span>

                    <?php endif; ?>

                </a>
            </div>
        </div>
        <?php
    }
}
