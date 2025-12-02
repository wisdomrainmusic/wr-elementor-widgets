<?php
if ( ! defined( 'ABSPATH' ) ) exit;

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
        return [ 'video-banner' ];
    }

    public function get_script_depends() {
        return [ 'video-banner' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'section_content',
            [ 'label' => __( 'Video', 'wr-elementor-widgets' ) ]
        );

        $this->add_control(
            'video_url',
            [
                'label'       => __( 'YouTube / Vimeo URL', 'wr-elementor-widgets' ),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'placeholder' => 'https://youtube.com/... veya https://vimeo.com/...'
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
                    'vertical'  => __( 'Short / Vertical (9:16)', 'wr-elementor-widgets' )
                ]
            ]
        );

        $this->end_controls_section();


        // STYLE > Height control
        $this->start_controls_section(
            'section_style',
            [
                'label' => __( 'Style', 'wr-elementor-widgets' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'video_height',
            [
                'label' => __( 'Video Height', 'wr-elementor-widgets' ),
                'type'  => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'vh' ],
                'range' => [
                    'px' => [ 'min' => 200, 'max' => 900 ],
                    'vh' => [ 'min' => 20, 'max' => 100 ]
                ],
                'default' => [ 'size' => 380, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .wr-video-banner' => 'height: {{SIZE}}{{UNIT}};'
                ]
            ]
        );

        $this->end_controls_section();
    }


    protected function render() {

        $settings  = $this->get_settings_for_display();
        $url       = $settings['video_url'];
        $type      = $settings['video_type'];

        if ( empty( $url ) ) return;

        // AUTO DETECT SHORT VIDEO
        if ( $type === 'auto' ) {
            if ( preg_match('/shorts/i', $url) ) {
                $type = 'vertical';
            } else {
                $type = 'horizontal';
            }
        }

        $video_id = '';
        $is_youtube = false;
        $is_vimeo   = false;

        // UNIVERSAL YOUTUBE ID PARSER (Shorts + Watch + Embed + youtu.be)
        if (strpos($url, 'youtu') !== false) {

            $is_youtube = true;

            // Extract ID from any YouTube URL
            preg_match(
                '#(?:youtube\.com/(?:shorts/|watch\?v=|embed/)|youtu\.be/)([A-Za-z0-9_\-]{6,})#',
                $url,
                $match
            );

            if (!empty($match[1])) {
                $video_id = $match[1];
            }
        }

        // -------------- VIMEO ID PARSING --------------
        if ( strpos($url, 'vimeo') !== false ) {

            $is_vimeo = true;

            if (preg_match('/vimeo\.com\/(\d+)/', $url, $m)) {
                $video_id = $m[1];
            }
        }

        ?>
        <div class="wr-video-banner" 
             data-player-type="<?php echo $is_youtube ? 'youtube' : ($is_vimeo ? 'vimeo' : 'unknown'); ?>"
             data-video-id="<?php echo esc_attr($video_id); ?>"
             data-aspect="<?php echo esc_attr($type); ?>">
            <div class="wr-video-player"></div>
        </div>
        <?php
    }
}

