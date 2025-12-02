<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class WR_EW_Campaign_Bar extends \Elementor\Widget_Base {

    public function get_name() {
        return 'wr-campaign-bar';
    }

    public function get_title() {
        return __( 'WR Campaign Bar', 'wr-ew' );
    }

    public function get_icon() {
        return 'eicon-banner';
    }

    public function get_categories() {
        return [ 'wr-widgets', 'wr-ecommerce-elements' ];
    }

    public function get_style_depends() {
        return [ 'wr-campaign-bar-css' ];
    }

    public function get_script_depends() {
        return [ 'wr-campaign-bar-js' ];
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
            'text',
            [
                'label' => __( 'Text', 'wr-ew' ),
                'type'  => \Elementor\Controls_Manager::TEXT,
                'default' => '🌟 Big Winter Sale – Up to 40% OFF!',
            ]
        );

        $this->add_control(
            'url',
            [
                'label' => __( 'Link URL', 'wr-ew' ),
                'type'  => \Elementor\Controls_Manager::URL,
                'placeholder' => 'https://',
                'default' => [ 'url' => '#' ],
            ]
        );

        $this->add_control(
            'text_mode',
            [
                'label' => __( 'Text Mode', 'wr-ew' ),
                'type'  => \Elementor\Controls_Manager::SELECT,
                'default' => 'normal',
                'options' => [
                    'normal'   => __('Static Text', 'wr-ew'),
                    'marquee'  => __('Scrolling / Marquee', 'wr-ew'),
                ],
            ]
        );

        $this->end_controls_section();


        // STYLE
        $this->start_controls_section(
            'section_style',
            [
                'label' => __( 'Style', 'wr-ew' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'background_color',
            [
                'label' => __( 'Background Color', 'wr-ew' ),
                'type'  => \Elementor\Controls_Manager::COLOR,
                'default' => '#111111',
                'selectors' => [
                    '{{WRAPPER}} .wr-campaign-bar' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => __( 'Text Color', 'wr-ew' ),
                'type'  => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .wr-campaign-bar-text' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {

        $s = $this->get_settings_for_display();
        $url = $s['url']['url'];

        $wrapper_class = $s['text_mode'] === 'marquee'
            ? 'wr-campaign-bar wr-marquee-enabled'
            : 'wr-campaign-bar';
        ?>

        <div class="<?php echo $wrapper_class; ?>">

            <a href="<?php echo esc_url( $url ); ?>" class="wr-campaign-bar-link">

                <?php if ( $s['text_mode'] === 'marquee' ) : ?>

                    <div class="wr-marquee">
                        <div class="wr-marquee-inner">
                            <span class="wr-campaign-bar-text"><?php echo esc_html( $s['text'] ); ?></span>
                            <span class="wr-campaign-bar-text"><?php echo esc_html( $s['text'] ); ?></span>
                            <span class="wr-campaign-bar-text"><?php echo esc_html( $s['text'] ); ?></span>
                        </div>
                    </div>

                <?php else : ?>

                    <span class="wr-campaign-bar-text">
                        <?php echo esc_html( $s['text'] ); ?>
                    </span>

                <?php endif; ?>
            </a>
        </div>

        <?php
    }
}
