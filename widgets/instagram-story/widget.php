<?php
/**
 * WR Elementor Widget - Instagram Story (Full Modal Version)
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class WR_EW_Instagram_Story extends \Elementor\Widget_Base {

    public function get_name() {
        return 'wr-instagram-story';
    }

    public function get_title() {
        return __( 'WR Instagram Story', 'wr-elementor-widgets' );
    }

    public function get_icon() {
        return 'eicon-slideshow';
    }

    public function get_categories() {
        return [ 'wr-widgets', 'wr-ecommerce-elements' ];
    }

    public function get_style_depends() {
        return [ 'wr-instagram-story-css' ];
    }

    public function get_script_depends() {
        return [ 'wr-instagram-story-js' ];
    }

    protected function register_controls() {

        /* -------------------------------------------------
           CONTENT TAB
        ------------------------------------------------- */
        $this->start_controls_section(
            'section_content',
            [
                'label' => __( 'Stories', 'wr-elementor-widgets' ),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'story_label',
            [
                'label'       => __( 'Highlight Label', 'wr-elementor-widgets' ),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'default'     => __( 'Highlight 1', 'wr-elementor-widgets' ),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'story_thumbnail',
            [
                'label'   => __( 'Highlight Thumbnail', 'wr-elementor-widgets' ),
                'type'    => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        for ( $i = 1; $i <= 4; $i++ ) {

            $repeater->add_control(
                'slide_' . $i . '_heading',
                [
                    'label'     => sprintf( __( 'Slide %d', 'wr-elementor-widgets' ), $i ),
                    'type'      => \Elementor\Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $repeater->add_control(
                "slide{$i}_image",
                [
                    'label'   => __( 'Image', 'wr-elementor-widgets' ),
                    'type'    => \Elementor\Controls_Manager::MEDIA,
                ]
            );

            $repeater->add_control(
                "slide{$i}_title",
                [
                    'label'       => __( 'Title', 'wr-elementor-widgets' ),
                    'type'        => \Elementor\Controls_Manager::TEXT,
                    'label_block' => true,
                ]
            );

            $repeater->add_control(
                "slide{$i}_link_text",
                [
                    'label'       => __( 'Link Text', 'wr-elementor-widgets' ),
                    'type'        => \Elementor\Controls_Manager::TEXT,
                    'label_block' => true,
                ]
            );

            $repeater->add_control(
                "slide{$i}_link_url",
                [
                    'label'       => __( 'Link URL', 'wr-elementor-widgets' ),
                    'type'        => \Elementor\Controls_Manager::URL,
                    'default'     => [
                        'url'         => '',
                        'is_external' => true,
                        'nofollow'    => true,
                    ],
                ]
            );
        }

        $this->add_control(
            'stories',
            [
                'label'       => __( 'Highlights', 'wr-elementor-widgets' ),
                'type'        => \Elementor\Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    [ 'story_label' => __( 'Highlight 1', 'wr-elementor-widgets' ) ],
                    [ 'story_label' => __( 'Highlight 2', 'wr-elementor-widgets' ) ],
                ],
                'title_field' => '{{{ story_label }}}',
            ]
        );

        $this->end_controls_section();


        /* -------------------------------------------------
           STYLE TAB
        ------------------------------------------------- */
        $this->start_controls_section(
            'section_style',
            [
                'label' => __( 'Style', 'wr-elementor-widgets' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        // Highlight Label Color
        $this->add_control(
            'story_label_color',
            [
                'label' => __( 'Highlight Label Color', 'wr-elementor-widgets' ),
                'type'  => \Elementor\Controls_Manager::COLOR,
                'default' => '#111111',
                'selectors' => [
                    '{{WRAPPER}} .wr-is-label' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Highlight Label Typography
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'story_label_typo',
                'selector' => '{{WRAPPER}} .wr-is-label',
            ]
        );

        // Modal Title Color
        $this->add_control(
            'modal_title_color',
            [
                'label' => __( 'Modal Title Color', 'wr-elementor-widgets' ),
                'type'  => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .wr-is-modal-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Modal Title Typography
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'modal_title_typography',
                'selector' => '{{WRAPPER}} .wr-is-modal-title',
            ]
        );

        // Link Text Color
        $this->add_control(
            'modal_link_color',
            [
                'label' => __( 'Link Text Color', 'wr-elementor-widgets' ),
                'type'  => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .wr-is-modal-link' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Link Background
        $this->add_control(
            'modal_link_bg',
            [
                'label' => __( 'Link Background', 'wr-elementor-widgets' ),
                'type'  => \Elementor\Controls_Manager::COLOR,
                'default' => 'rgba(255,255,255,0.15)',
                'selectors' => [
                    '{{WRAPPER}} .wr-is-modal-link' => 'background: {{VALUE}};',
                ],
            ]
        );

        // Link Typography
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'modal_link_typo',
                'selector' => '{{WRAPPER}} .wr-is-modal-link',
            ]
        );

        $this->end_controls_section();
    }


    /* -------------------------------------------------
       RENDER
    ------------------------------------------------- */
    protected function render() {

        $settings = $this->get_settings_for_display();
        $stories  = ! empty( $settings['stories'] ) ? $settings['stories'] : [];

        if ( empty( $stories ) ) return;

        $stories_data = [];

        foreach ( $stories as $item ) {

            $slides = [];

            for ( $i = 1; $i <= 4; $i++ ) {

                $image = $item["slide{$i}_image"]['url'] ?? '';

                if ( ! $image ) continue;

                $slides[] = [
                    'image'            => esc_url_raw( $image ),
                    'title'            => sanitize_text_field( $item["slide{$i}_title"] ?? '' ),
                    'link_text'        => sanitize_text_field( $item["slide{$i}_link_text"] ?? '' ),
                    'link_url'         => $item["slide{$i}_link_url"]['url'] ?? '',
                    'link_is_external' => ! empty( $item["slide{$i}_link_url"]['is_external'] ),
                    'link_nofollow'    => ! empty( $item["slide{$i}_link_url"]['nofollow'] ),
                ];
            }

            if ( empty( $slides ) ) continue;

            $thumb = $item['story_thumbnail']['url'] ?? $slides[0]['image'];

            $stories_data[] = [
                'label'     => sanitize_text_field( $item['story_label'] ?? '' ),
                'thumbnail' => esc_url_raw( $thumb ),
                'slides'    => $slides,
            ];
        }

        if ( empty( $stories_data ) ) return;

        $data_attr = esc_attr( wp_json_encode( $stories_data ) );
        ?>

        <div class="wr-instagram-story-wrapper" data-wr-stories="<?php echo $data_attr; ?>">

            <div class="wr-instagram-story-arrows">
                <button class="wr-story-arrow prev" type="button"><span>&larr;</span></button>
                <button class="wr-story-arrow next" type="button"><span>&rarr;</span></button>
            </div>

            <div class="wr-instagram-story-list">
                <?php foreach ( $stories_data as $index => $story ) : ?>
                    <div class="wr-is-item" data-story-index="<?php echo esc_attr( $index ); ?>">
                        <div class="wr-is-thumb-wrapper">
                            <div class="wr-is-thumb">
                                <img src="<?php echo esc_url( $story['thumbnail'] ); ?>" alt="<?php echo esc_attr( $story['label'] ); ?>">
                            </div>
                        </div>
                        <?php if ( $story['label'] ) : ?>
                            <div class="wr-is-label"><?php echo esc_html( $story['label'] ); ?></div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- MODAL -->
            <div class="wr-is-modal" aria-hidden="true">
                <div class="wr-is-modal-inner">

                    <div class="wr-is-modal-header">
                        <div class="wr-is-modal-progress"></div>
                        <div class="wr-is-modal-top-row">
                            <div class="wr-is-modal-label"></div>
                            <button class="wr-is-modal-close" type="button">&times;</button>
                        </div>
                    </div>

                    <div class="wr-is-modal-body">

                        <div class="wr-is-modal-click wr-is-modal-click-prev"></div>

                        <div class="wr-is-modal-center">

                            <button class="wr-is-modal-arrow modal-prev" type="button">&larr;</button>
                            <button class="wr-is-modal-arrow modal-next" type="button">&rarr;</button>

                            <img class="wr-is-modal-image" src="" alt="">
                            <div class="wr-is-modal-text">
                                <div class="wr-is-modal-title"></div>
                                <a href="#" class="wr-is-modal-link" target="_blank" rel="nofollow noopener"></a>
                            </div>
                        </div>

                        <div class="wr-is-modal-click wr-is-modal-click-next"></div>

                    </div>
                </div>
            </div>

        </div>

        <?php
    }
}

