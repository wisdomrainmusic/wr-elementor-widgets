<?php
/**
 * WR Elementor Widget - Testimonials (Slider)
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class WR_EW_Testimonials extends \Elementor\Widget_Base {

    public function get_name() {
        return 'wr-testimonials';
    }

    public function get_title() {
        return __( 'WR Testimonials', 'wr-elementor-widgets' );
    }

    public function get_icon() {
        return 'eicon-testimonial-carousel';
    }

    public function get_categories() {
        return [ 'wr-widgets', 'wr-ecommerce-elements' ];
    }

    public function get_style_depends() {
        return [ 'wr-testimonials-css' ];
    }

    public function get_script_depends() {
        return [ 'wr-testimonials-js' ];
    }

    protected function register_controls() {

        /* -------------------------------------------------
           CONTENT TAB
        ------------------------------------------------- */
        $this->start_controls_section(
            'section_content',
            [
                'label' => __( 'Testimonials', 'wr-elementor-widgets' ),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'testimonial_content',
            [
                'label'       => __( 'Testimonial Text', 'wr-elementor-widgets' ),
                'type'        => \Elementor\Controls_Manager::TEXTAREA,
                'default'     => __( 'This is a sample testimonial. Replace it with real customer feedback.', 'wr-elementor-widgets' ),
                'label_block' => true,
                'rows'        => 4,
            ]
        );

        $repeater->add_control(
            'testimonial_name',
            [
                'label'       => __( 'Name', 'wr-elementor-widgets' ),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'default'     => __( 'John Doe', 'wr-elementor-widgets' ),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'testimonial_role',
            [
                'label'       => __( 'Role / Title', 'wr-elementor-widgets' ),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'default'     => __( 'Happy Customer', 'wr-elementor-widgets' ),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'testimonial_company',
            [
                'label'       => __( 'Company (optional)', 'wr-elementor-widgets' ),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'default'     => '',
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'testimonial_rating',
            [
                'label'   => __( 'Rating', 'wr-elementor-widgets' ),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => '5',
                'options' => [
                    '0' => __( 'No rating', 'wr-elementor-widgets' ),
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                ],
            ]
        );

        $repeater->add_control(
            'testimonial_photo',
            [
                'label'   => __( 'Photo', 'wr-elementor-widgets' ),
                'type'    => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->add_control(
            'items',
            [
                'label'       => __( 'Testimonials', 'wr-elementor-widgets' ),
                'type'        => \Elementor\Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    [
                        'testimonial_content' => __( 'Amazing experience! The products and the overall service exceeded my expectations.', 'wr-elementor-widgets' ),
                        'testimonial_name'    => __( 'Jane Smith', 'wr-elementor-widgets' ),
                        'testimonial_role'    => __( 'Designer', 'wr-elementor-widgets' ),
                        'testimonial_company' => __( 'Creative Studio', 'wr-elementor-widgets' ),
                        'testimonial_rating'  => '5',
                    ],
                    [
                        'testimonial_content' => __( 'Very smooth shopping journey and excellent customer support.', 'wr-elementor-widgets' ),
                        'testimonial_name'    => __( 'Michael Brown', 'wr-elementor-widgets' ),
                        'testimonial_role'    => __( 'E-commerce Manager', 'wr-elementor-widgets' ),
                        'testimonial_company' => '',
                        'testimonial_rating'  => '4',
                    ],
                ],
                'title_field' => '{{{ testimonial_name }}}',
            ]
        );

        $this->end_controls_section();


        /* -------------------------------------------------
           STYLE TAB - CARD
        ------------------------------------------------- */
        $this->start_controls_section(
            'section_style_card',
            [
                'label' => __( 'Card', 'wr-elementor-widgets' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'card_background',
            [
                'label'     => __( 'Background Color', 'wr-elementor-widgets' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .wr-testimonial-card' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'card_border_radius',
            [
                'label'      => __( 'Border Radius', 'wr-elementor-widgets' ),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 40,
                    ],
                ],
                'default'    => [
                    'size' => 16,
                    'unit' => 'px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .wr-testimonial-card' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'card_padding',
            [
                'label'      => __( 'Padding', 'wr-elementor-widgets' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'default'    => [
                    'top'    => 24,
                    'right'  => 24,
                    'bottom' => 24,
                    'left'   => 24,
                    'unit'   => 'px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .wr-testimonial-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'card_shadow',
                'selector' => '{{WRAPPER}} .wr-testimonial-card',
            ]
        );

        $this->end_controls_section();


        /* -------------------------------------------------
           STYLE TAB - TEXTS
        ------------------------------------------------- */
        $this->start_controls_section(
            'section_style_texts',
            [
                'label' => __( 'Texts', 'wr-elementor-widgets' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        // Testimonial Text
        $this->add_control(
            'testimonial_text_color',
            [
                'label'     => __( 'Testimonial Text Color', 'wr-elementor-widgets' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .wr-testimonial-text' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'testimonial_text_typography',
                'selector' => '{{WRAPPER}} .wr-testimonial-text',
            ]
        );

        // Name
        $this->add_control(
            'name_color',
            [
                'label'     => __( 'Name Color', 'wr-elementor-widgets' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#111111',
                'selectors' => [
                    '{{WRAPPER}} .wr-testimonial-name' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'name_typography',
                'selector' => '{{WRAPPER}} .wr-testimonial-name',
            ]
        );

        // Role / Company
        $this->add_control(
            'meta_color',
            [
                'label'     => __( 'Role / Company Color', 'wr-elementor-widgets' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#777777',
                'selectors' => [
                    '{{WRAPPER}} .wr-testimonial-meta' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'meta_typography',
                'selector' => '{{WRAPPER}} .wr-testimonial-meta',
            ]
        );

        // Rating
        $this->add_control(
            'rating_color',
            [
                'label'     => __( 'Rating Color', 'wr-elementor-widgets' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#ffb400',
                'selectors' => [
                    '{{WRAPPER}} .wr-testimonial-rating' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();


        /* -------------------------------------------------
           STYLE TAB - ARROWS
        ------------------------------------------------- */
        $this->start_controls_section(
            'section_style_arrows',
            [
                'label' => __( 'Arrows', 'wr-elementor-widgets' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs( 'tabs_arrows_style' );

        // NORMAL
        $this->start_controls_tab(
            'tab_arrows_normal',
            [
                'label' => __( 'Normal', 'wr-elementor-widgets' ),
            ]
        );

        $this->add_control(
            'arrow_color',
            [
                'label'     => __( 'Arrow Color', 'wr-elementor-widgets' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#111111',
                'selectors' => [
                    '{{WRAPPER}} .wr-testimonials-arrow'      => 'color: {{VALUE}};',
                    '{{WRAPPER}} .wr-testimonials-arrow span' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'arrow_bg_color',
            [
                'label'     => __( 'Background Color', 'wr-elementor-widgets' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => 'rgba(255,255,255,0.9)',
                'selectors' => [
                    '{{WRAPPER}} .wr-testimonials-arrow' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'arrow_border_color',
            [
                'label'     => __( 'Border Color', 'wr-elementor-widgets' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => 'rgba(0,0,0,0.06)',
                'selectors' => [
                    '{{WRAPPER}} .wr-testimonials-arrow' => 'border: 1px solid {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'arrow_size',
            [
                'label'      => __( 'Button Size', 'wr-elementor-widgets' ),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [ 'min' => 24, 'max' => 80 ],
                ],
                'default'    => [
                    'size' => 32,
                    'unit' => 'px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .wr-testimonials-arrow' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'arrow_icon_size',
            [
                'label'      => __( 'Icon Size', 'wr-elementor-widgets' ),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [ 'min' => 10, 'max' => 40 ],
                ],
                'default'    => [
                    'size' => 16,
                    'unit' => 'px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .wr-testimonials-arrow' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        // HOVER
        $this->start_controls_tab(
            'tab_arrows_hover',
            [
                'label' => __( 'Hover', 'wr-elementor-widgets' ),
            ]
        );

        $this->add_control(
            'arrow_color_hover',
            [
                'label'     => __( 'Arrow Color (Hover)', 'wr-elementor-widgets' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#111111',
                'selectors' => [
                    '{{WRAPPER}} .wr-testimonials-arrow:hover'      => 'color: {{VALUE}};',
                    '{{WRAPPER}} .wr-testimonials-arrow:hover span' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'arrow_bg_color_hover',
            [
                'label'     => __( 'Background Color (Hover)', 'wr-elementor-widgets' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => 'rgba(255,255,255,1)',
                'selectors' => [
                    '{{WRAPPER}} .wr-testimonials-arrow:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'arrow_border_color_hover',
            [
                'label'     => __( 'Border Color (Hover)', 'wr-elementor-widgets' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => 'rgba(0,0,0,0.12)',
                'selectors' => [
                    '{{WRAPPER}} .wr-testimonials-arrow:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'arrow_shadow',
                'selector' => '{{WRAPPER}} .wr-testimonials-arrow',
            ]
        );

        $this->end_controls_section();
    }


    /* -------------------------------------------------
       RENDER
    ------------------------------------------------- */
    protected function render() {

        $settings = $this->get_settings_for_display();
        $items    = ! empty( $settings['items'] ) ? $settings['items'] : [];

        if ( empty( $items ) ) {
            return;
        }

        $prepared_items = [];

        foreach ( $items as $item ) {

            $photo_arr = $item['testimonial_photo'] ?? [];
            $photo_url = $photo_arr['url'] ?? '';

            $prepared_items[] = [
                'content' => wp_kses_post( $item['testimonial_content'] ?? '' ),
                'name'    => sanitize_text_field( $item['testimonial_name'] ?? '' ),
                'role'    => sanitize_text_field( $item['testimonial_role'] ?? '' ),
                'company' => sanitize_text_field( $item['testimonial_company'] ?? '' ),
                'rating'  => isset( $item['testimonial_rating'] ) ? (int) $item['testimonial_rating'] : 0,
                'photo'   => esc_url_raw( $photo_url ),
            ];
        }

        if ( empty( $prepared_items ) ) {
            return;
        }

        $data_attr = esc_attr( wp_json_encode( $prepared_items ) );
        ?>

        <div class="wr-testimonials-wrapper" data-wr-testimonials="<?php echo $data_attr; ?>">

            <div class="wr-testimonials-header"></div>

            <div class="wr-testimonials-slider">
                <div class="wr-testimonials-track">
                    <?php foreach ( $prepared_items as $item ) : ?>
                        <div class="wr-testimonial-card">
                            <div class="wr-testimonial-top">
                                <div class="wr-testimonial-quote-icon">“</div>
                                <div class="wr-testimonial-text">
                                    <?php echo wp_kses_post( $item['content'] ); ?>
                                </div>
                            </div>

                            <div class="wr-testimonial-bottom">
                                <div class="wr-testimonial-profile">

                                    <?php if ( ! empty( $item['photo'] ) ) : ?>
                                        <div class="wr-testimonial-photo">
                                            <img src="<?php echo esc_url( $item['photo'] ); ?>" alt="<?php echo esc_attr( $item['name'] ); ?>">
                                        </div>
                                    <?php endif; ?>

                                    <div class="wr-testimonial-info">
                                        <?php if ( ! empty( $item['name'] ) ) : ?>
                                            <div class="wr-testimonial-name">
                                                <?php echo esc_html( $item['name'] ); ?>
                                            </div>
                                        <?php endif; ?>

                                        <?php
                                        $meta_parts = [];
                                        if ( ! empty( $item['role'] ) ) {
                                            $meta_parts[] = $item['role'];
                                        }
                                        if ( ! empty( $item['company'] ) ) {
                                            $meta_parts[] = $item['company'];
                                        }
                                        $meta_text = implode( ' • ', array_map( 'esc_html', $meta_parts ) );
                                        ?>

                                        <?php if ( ! empty( $meta_text ) ) : ?>
                                            <div class="wr-testimonial-meta">
                                                <?php echo $meta_text; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <?php if ( ! empty( $item['rating'] ) && $item['rating'] > 0 ) : ?>
                                    <div class="wr-testimonial-rating">
                                        <?php
                                        $rating = max( 0, min( 5, (int) $item['rating'] ) );
                                        for ( $i = 1; $i <= 5; $i++ ) {
                                            echo $i <= $rating ? '★' : '☆';
                                        }
                                        ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                        </div>
                    <?php endforeach; ?>
                </div>

                <button class="wr-testimonials-arrow prev" type="button" aria-label="<?php esc_attr_e( 'Previous', 'wr-elementor-widgets' ); ?>">
                    <span>&larr;</span>
                </button>
                <button class="wr-testimonials-arrow next" type="button" aria-label="<?php esc_attr_e( 'Next', 'wr-elementor-widgets' ); ?>">
                    <span>&rarr;</span>
                </button>

                
            </div>

        </div>

        <?php
    }
}
