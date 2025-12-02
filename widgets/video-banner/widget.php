<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class WR_EW_Video_Banner extends \Elementor\Widget_Base {

    public function get_name() {
        return 'wr-video-banner';
    }

    public function get_title() {
        return __( 'WR Video Banner', 'wr-elementor-widgets' );
    }

    public function get_icon() {
        return 'eicon-play';
    }

    public function get_categories() {
        return [ 'wr-widgets', 'wr-ecommerce-elements' ];
    }

    public function get_style_depends() {
        return [ 'wr-video-banner-css' ];
    }

    public function get_script_depends() {
        return [ 'wr-video-banner-js' ];
    }

    protected function register_controls() {

        /* -------------------------------------
           CONTENT
        ------------------------------------- */
        $this->start_controls_section(
            'section_content',
            [
                'label' => __( 'Video', 'wr-elementor-widgets' ),
            ]
        );

        $this->add_control(
            'video_url',
            [
                'label'       => __( 'YouTube / Vimeo URL', 'wr-elementor-widgets' ),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'placeholder' => 'https://youtube.com/... veya https://vimeo.com/...',
                'label_block' => true,
            ]
        );

        $this->add_control(
            'video_type',
            [
                'label'   => __( 'Video Type', 'wr-elementor-widgets' ),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => 'auto',
                'options' => [
                    'auto'      => __( 'Auto Detect', 'wr-elementor-widgets' ),
                    'horizontal'=> __( 'Horizontal (16:9)', 'wr-elementor-widgets' ),
                    'vertical'  => __( 'Short / Vertical (9:16)', 'wr-elementor-widgets' ),
                ],
            ]
        );

        $this->end_controls_section();


        /* -------------------------------------
           STYLE
        ------------------------------------- */
        $this->start_controls_section(
            'section_style',
            [
                'label' => __( 'Style', 'wr-elementor-widgets' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'border_radius',
            [
                'label' => __( 'Border Radius', 'wr-elementor-widgets' ),
                'type'  => \Elementor\Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .wr-video-banner iframe' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }


    /* -------------------------------------
       RENDER
    ------------------------------------- */
    protected function render() {

        $settings  = $this->get_settings_for_display();
        $url       = isset( $settings['video_url'] ) ? esc_url( $settings['video_url'] ) : '';
        $type      = isset( $settings['video_type'] ) ? $settings['video_type'] : 'auto';

        if ( empty( $url ) ) {
            return;
        }

        // AUTO DETECT (SHORT vs NORMAL)
        if ( $type === 'auto' ) {

            if ( preg_match( '/shorts|short/i', $url ) ) {
                $type = 'vertical';
            } else {
                $type = 'horizontal';
            }
        }

        $ratio_class = $type === 'vertical' ? 'ratio-9x16' : 'ratio-16x9';

        // YouTube embed convert
        if ( strpos( $url, 'youtube.com' ) !== false || strpos( $url, 'youtu.be' ) !== false ) {

            $url = preg_replace(
                '/watch\?v=([a-zA-Z0-9_\-]+)/',
                'embed/$1',
                $url
            );

            $url = str_replace( 'youtu.be/', 'www.youtube.com/embed/', $url );

            // Disable related videos + autoplayless
            $url .= ( strpos( $url, '?' ) === false ? '?' : '&' ) . 'rel=0&autoplay=0&loop=0&controls=1';
        }

        // Vimeo embed convert
        if ( strpos( $url, 'vimeo.com' ) !== false ) {
            $url = preg_replace( '/vimeo.com\/(\d+)/', 'player.vimeo.com/video/$1', $url );
            $url .= '?autoplay=0&loop=0&title=0&byline=0&portrait=0';
        }

        ?>
        <div class="wr-video-banner <?php echo esc_attr( $ratio_class ); ?>">
            <iframe
                src="<?php echo esc_url( $url ); ?>"
                frameborder="0"
                allowfullscreen
                allow="fullscreen; accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
            ></iframe>
        </div>
        <?php
    }
}
