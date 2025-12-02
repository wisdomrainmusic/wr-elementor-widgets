<?php
/**
 * WR Banner Elementor Widget
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Utils;

class WR_EW_Banner extends Widget_Base {

    public function get_name() {
        return 'wr-banner';
    }

    public function get_title() {
        return __( 'WR Banner (Mobile First)', 'wr-ew' );
    }

    public function get_icon() {
        return 'eicon-banner';
    }

    public function get_categories() {
        // Loader kuralına göre: wr-widgets ve/veya wr-ecommerce-elements
        return [ 'wr-widgets', 'wr-ecommerce-elements' ];
    }

    public function get_style_depends() {
        // assets/css/banner.css
        return [ 'wr-banner-css' ];
    }

    public function get_script_depends() {
        // assets/js/banner.js
        return [ 'wr-banner-js' ];
    }

    protected function register_controls() {

        /**
         * -------------------------------------------------------
         * CONTENT TAB
         * -------------------------------------------------------
         */
        $this->start_controls_section(
            'section_content',
            [
                'label' => __( 'Content', 'wr-ew' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        // Banner Image
        $this->add_control(
            'banner_image',
            [
                'label'   => __( 'Background Image', 'wr-ew' ),
                'type'    => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        // Eyebrow / Label
        $this->add_control(
            'eyebrow_text',
            [
                'label'       => __( 'Eyebrow Text', 'wr-ew' ),
                'type'        => Controls_Manager::TEXT,
                'placeholder' => __( 'New collection', 'wr-ew' ),
                'default'     => __( 'New collection', 'wr-ew' ),
                'label_block' => true,
            ]
        );

        // Heading
        $this->add_control(
            'heading_text',
            [
                'label'       => __( 'Heading', 'wr-ew' ),
                'type'        => Controls_Manager::TEXT,
                'placeholder' => __( 'Premium calm for your digital life', 'wr-ew' ),
                'default'     => __( 'Premium calm for your digital life', 'wr-ew' ),
                'label_block' => true,
            ]
        );

        // Subheading
        $this->add_control(
            'subheading_text',
            [
                'label'       => __( 'Subheading', 'wr-ew' ),
                'type'        => Controls_Manager::TEXTAREA,
                'placeholder' => __( 'Unlimited access to books, audio and mindful content.', 'wr-ew' ),
                'default'     => __( 'Unlimited access to books, audio and mindful content.', 'wr-ew' ),
                'rows'        => 3,
            ]
        );

        // Button toggle
        $this->add_control(
            'show_button',
            [
                'label'        => __( 'Show Button', 'wr-ew' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'Show', 'wr-ew' ),
                'label_off'    => __( 'Hide', 'wr-ew' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        // Button text
        $this->add_control(
            'button_text',
            [
                'label'       => __( 'Button Text', 'wr-ew' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => __( 'Start free trial', 'wr-ew' ),
                'placeholder' => __( 'Start free trial', 'wr-ew' ),
                'condition'   => [
                    'show_button' => 'yes',
                ],
            ]
        );

        // Button URL
        $this->add_control(
            'button_url',
            [
                'label'     => __( 'Button Link', 'wr-ew' ),
                'type'      => Controls_Manager::URL,
                'dynamic'   => [
                    'active' => true,
                ],
                'default'   => [
                    'url'         => '#',
                    'is_external' => false,
                    'nofollow'    => false,
                ],
                'condition' => [
                    'show_button' => 'yes',
                ],
            ]
        );

        // Layout: horizontal alignment (text side)
        $this->add_responsive_control(
            'content_align',
            [
                'label'   => __( 'Content Alignment', 'wr-ew' ),
                'type'    => Controls_Manager::CHOOSE,
                'options' => [
                    'left'   => [
                        'title' => __( 'Left', 'wr-ew' ),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'wr-ew' ),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'right'  => [
                        'title' => __( 'Right', 'wr-ew' ),
                        'icon'  => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'left',
                'toggle'  => true,
            ]
        );

        // Layout: vertical align
        $this->add_responsive_control(
            'content_vertical_align',
            [
                'label'   => __( 'Vertical Alignment', 'wr-ew' ),
                'type'    => Controls_Manager::CHOOSE,
                'options' => [
                    'top'    => [
                        'title' => __( 'Top', 'wr-ew' ),
                        'icon'  => 'eicon-v-align-top',
                    ],
                    'middle' => [
                        'title' => __( 'Middle', 'wr-ew' ),
                        'icon'  => 'eicon-v-align-middle',
                    ],
                    'bottom' => [
                        'title' => __( 'Bottom', 'wr-ew' ),
                        'icon'  => 'eicon-v-align-bottom',
                    ],
                ],
                'default' => 'middle',
                'toggle'  => true,
            ]
        );

        $this->end_controls_section();

        /**
         * -------------------------------------------------------
         * STYLE TAB – BANNER
         * -------------------------------------------------------
         */
        $this->start_controls_section(
            'section_style_banner',
            [
                'label' => __( 'Banner', 'wr-ew' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        // Banner min-height (responsive)
        $this->add_responsive_control(
            'banner_min_height',
            [
                'label'      => __( 'Min Height (px)', 'wr-ew' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min' => 160,
                        'max' => 520,
                    ],
                ],
                'default'    => [
                    'size' => 220,
                    'unit' => 'px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .wr-banner' => 'min-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        // Border radius
        $this->add_control(
            'banner_border_radius',
            [
                'label'      => __( 'Border Radius', 'wr-ew' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default'    => [
                    'top'    => 16,
                    'right'  => 16,
                    'bottom' => 16,
                    'left'   => 16,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .wr-banner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wr-banner-media img' => 'border-radius: inherit;',
                ],
            ]
        );

        // Banner padding (inner content)
        $this->add_responsive_control(
            'banner_padding',
            [
                'label'      => __( 'Content Padding', 'wr-ew' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default'    => [
                    'top'    => 16,
                    'right'  => 16,
                    'bottom' => 16,
                    'left'   => 16,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .wr-banner-inner' =>
                        'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Overlay background (gradient)
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'banner_overlay_background',
                'label'    => __( 'Overlay', 'wr-ew' ),
                'types'    => [ 'gradient' ],
                'selector' => '{{WRAPPER}} .wr-banner-overlay',
            ]
        );

        // Box shadow
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'banner_box_shadow',
                'selector' => '{{WRAPPER}} .wr-banner',
            ]
        );

        $this->end_controls_section();

        /**
         * -------------------------------------------------------
         * STYLE TAB – CONTENT
         * -------------------------------------------------------
         */
        $this->start_controls_section(
            'section_style_content',
            [
                'label' => __( 'Content', 'wr-ew' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        // Eyebrow typography + color
        $this->add_control(
            'eyebrow_color',
            [
                'label'     => __( 'Eyebrow Color', 'wr-ew' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-banner-eyebrow' => 'color: {{VALUE}};',
                ],
                'default'   => '#ffffff',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eyebrow_typography',
                'selector' => '{{WRAPPER}} .wr-banner-eyebrow',
            ]
        );

        // Heading typography + color
        $this->add_control(
            'heading_color',
            [
                'label'     => __( 'Heading Color', 'wr-ew' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-banner-heading' => 'color: {{VALUE}};',
                ],
                'default'   => '#ffffff',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'heading_typography',
                'selector' => '{{WRAPPER}} .wr-banner-heading',
            ]
        );

        // Subheading typography + color
        $this->add_control(
            'subheading_color',
            [
                'label'     => __( 'Subheading Color', 'wr-ew' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-banner-subheading' => 'color: {{VALUE}};',
                ],
                'default'   => '#f5f5f5',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'subheading_typography',
                'selector' => '{{WRAPPER}} .wr-banner-subheading',
            ]
        );

        // Button color
        $this->add_control(
            'button_background',
            [
                'label'     => __( 'Button Background', 'wr-ew' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .wr-banner-button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_text_color',
            [
                'label'     => __( 'Button Text Color', 'wr-ew' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#111111',
                'selectors' => [
                    '{{WRAPPER}} .wr-banner-button' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Button typography
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'button_typography',
                'selector' => '{{WRAPPER}} .wr-banner-button',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        // Image URL + ALT + placeholder fallback
        $image_url = '';
        $image_alt = '';

        if ( ! empty( $settings['banner_image']['id'] ) ) {
            $image_id  = (int) $settings['banner_image']['id'];
            $image_url = wp_get_attachment_image_url( $image_id, 'full' );
            $image_alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
        } elseif ( ! empty( $settings['banner_image']['url'] ) ) {
            $image_url = $settings['banner_image']['url'];
        }

        if ( ! $image_url ) {
            $image_url = Utils::get_placeholder_image_src();
        }

        if ( ! $image_alt ) {
            $image_alt = ! empty( $settings['heading_text'] )
                ? $settings['heading_text']
                : get_bloginfo( 'name' );
        }

        // Aria label (a11y)
        $aria_label = ! empty( $settings['heading_text'] )
            ? $settings['heading_text']
            : __( 'Promotional banner', 'wr-ew' );

        // Wrapper attributes
        $this->add_render_attribute( 'wrapper', 'class', 'wr-banner-widget' );

        // Alignment classes
        $content_align = isset( $settings['content_align'] ) ? $settings['content_align'] : 'left';
        $vertical_align = isset( $settings['content_vertical_align'] ) ? $settings['content_vertical_align'] : 'middle';

        $this->add_render_attribute( 'inner', 'class', 'wr-banner-inner' );
        $this->add_render_attribute( 'inner', 'class', 'wr-banner-align-' . esc_attr( $content_align ) );
        $this->add_render_attribute( 'inner', 'class', 'wr-banner-valign-' . esc_attr( $vertical_align ) );

        // Button link attributes
        if ( ! empty( $settings['button_url']['url'] ) ) {
            $this->add_render_attribute( 'banner_button', 'href', esc_url( $settings['button_url']['url'] ) );

            if ( ! empty( $settings['button_url']['is_external'] ) ) {
                $this->add_render_attribute( 'banner_button', 'target', '_blank' );
            }

            $rel = [];
            if ( ! empty( $settings['button_url']['nofollow'] ) ) {
                $rel[] = 'nofollow';
            }
            if ( ! empty( $settings['button_url']['is_external'] ) ) {
                $rel[] = 'noopener';
            }
            if ( ! empty( $rel ) ) {
                $this->add_render_attribute( 'banner_button', 'rel', implode( ' ', $rel ) );
            }
        } else {
            $this->add_render_attribute( 'banner_button', 'href', '#' );
        }

        // Aria label for button
        if ( ! empty( $settings['button_text'] ) ) {
            $this->add_render_attribute(
                'banner_button',
                'aria-label',
                esc_attr( $settings['button_text'] . ' – ' . $aria_label )
            );
        }

        ?>
        <div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
            <div class="wr-banner" role="region" aria-label="<?php echo esc_attr( $aria_label ); ?>">
                <div class="wr-banner-media" aria-hidden="true">
                    <img
                        src="<?php echo esc_url( $image_url ); ?>"
                        alt="<?php echo esc_attr( $image_alt ); ?>"
                        loading="lazy"
                    />
                    <span class="wr-banner-overlay"></span>
                </div>

                <div <?php echo $this->get_render_attribute_string( 'inner' ); ?>>
                    <div class="wr-banner-content">
                        <?php if ( ! empty( $settings['eyebrow_text'] ) ) : ?>
                            <div class="wr-banner-eyebrow">
                                <?php echo esc_html( $settings['eyebrow_text'] ); ?>
                            </div>
                        <?php endif; ?>

                        <?php if ( ! empty( $settings['heading_text'] ) ) : ?>
                            <h2 class="wr-banner-heading">
                                <?php echo esc_html( $settings['heading_text'] ); ?>
                            </h2>
                        <?php endif; ?>

                        <?php if ( ! empty( $settings['subheading_text'] ) ) : ?>
                            <div class="wr-banner-subheading">
                                <?php echo esc_html( $settings['subheading_text'] ); ?>
                            </div>
                        <?php endif; ?>

                        <?php if ( 'yes' === $settings['show_button'] && ! empty( $settings['button_text'] ) ) : ?>
                            <div class="wr-banner-actions">
                                <a
                                    <?php echo $this->get_render_attribute_string( 'banner_button' ); ?>
                                    class="wr-banner-button"
                                    role="button"
                                >
                                    <span class="wr-banner-button-text">
                                        <?php echo esc_html( $settings['button_text'] ); ?>
                                    </span>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}
