<?php
/**
 * WR Elementor Widget - Blog Grid (2x3 + Category Sidebar + AJAX)
 */

if (!defined('ABSPATH')) {
    exit;
}

class WR_EW_Blog_Grid extends \Elementor\Widget_Base
{

    public function get_name()
    {
        return 'wr-blog-grid';
    }

    public function get_title()
    {
        return __('WR Blog Grid', 'wr-elementor-widgets');
    }

    public function get_icon()
    {
        return 'eicon-post-list';
    }

    public function get_categories()
    {
        return ['wr-widgets', 'wr-ecommerce-elements'];
    }

    public function get_style_depends()
    {
        return ['wr-blog-grid-css'];
    }

    public function get_script_depends()
    {
        return ['wr-blog-grid-js'];
    }

    protected function register_controls()
    {

        // -------------------------------------------------
        // CONTENT TAB — QUERY
        // -------------------------------------------------
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Query', 'wr-elementor-widgets'),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'posts_per_page',
            [
                'label'   => __('Posts Per Page', 'wr-elementor-widgets'),
                'type'    => \Elementor\Controls_Manager::NUMBER,
                'default' => 6,
                'min'     => 1,
                'step'    => 1,
            ]
        );

        $this->add_control(
            'parent_category',
            [
                'label'       => __('Parent Category (optional)', 'wr-elementor-widgets'),
                'type'        => \Elementor\Controls_Manager::SELECT2,
                'options'     => $this->get_categories_for_control(),
                'multiple'    => false,
                'label_block' => true,
                'description' => __('If selected, sidebar will list only children of this category. If empty, all categories listed.', 'wr-elementor-widgets'),
            ]
        );

        $this->end_controls_section();

        // -------------------------------------------------
        // CONTENT TAB — META & EXCERPT
        // -------------------------------------------------
        $this->start_controls_section(
            'section_meta',
            [
                'label' => __('Post Meta', 'wr-elementor-widgets'),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_excerpt',
            [
                'label'        => __('Show Excerpt', 'wr-elementor-widgets'),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => __('Yes', 'wr-elementor-widgets'),
                'label_off'    => __('No', 'wr-elementor-widgets'),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'excerpt_length',
            [
                'label'     => __('Excerpt Length (words)', 'wr-elementor-widgets'),
                'type'      => \Elementor\Controls_Manager::NUMBER,
                'default'   => 18,
                'min'       => 5,
                'step'      => 1,
                'condition' => ['show_excerpt' => 'yes'],
            ]
        );

        $this->add_control(
            'show_read_more',
            [
                'label'        => __('Show Read More Button', 'wr-elementor-widgets'),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => __('Yes', 'wr-elementor-widgets'),
                'label_off'    => __('No', 'wr-elementor-widgets'),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'read_more_text',
            [
                'label'     => __('Read More Text', 'wr-elementor-widgets'),
                'type'      => \Elementor\Controls_Manager::TEXT,
                'default'   => __('Read more', 'wr-elementor-widgets'),
                'condition' => ['show_read_more' => 'yes'],
            ]
        );

        $this->end_controls_section();

        // -------------------------------------------------
        // STYLE TAB — READ MORE BUTTON PRO
        // -------------------------------------------------
        $this->start_controls_section(
            'section_style_readmore',
            [
                'label'     => __('Read More Button', 'wr-elementor-widgets'),
                'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => ['show_read_more' => 'yes'],
            ]
        );

        // TABS: Normal / Hover
        $this->start_controls_tabs('tabs_readmore_style');

        // NORMAL TAB
        $this->start_controls_tab(
            'tab_readmore_normal',
            [
                'label' => __('Normal', 'wr-elementor-widgets'),
            ]
        );

        $this->add_control(
            'readmore_color',
            [
                'label'     => __('Text Color', 'wr-elementor-widgets'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-blog-readmore-btn' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'readmore_bg_color',
            [
                'label'     => __('Background Color', 'wr-elementor-widgets'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-blog-readmore-btn' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'     => 'readmore_border',
                'selector' => '{{WRAPPER}} .wr-blog-readmore-btn',
            ]
        );

        $this->end_controls_tab();

        // HOVER TAB
        $this->start_controls_tab(
            'tab_readmore_hover',
            [
                'label' => __('Hover', 'wr-elementor-widgets'),
            ]
        );

        $this->add_control(
            'readmore_hover_color',
            [
                'label'     => __('Text Color', 'wr-elementor-widgets'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-blog-readmore-btn:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'readmore_hover_bg_color',
            [
                'label'     => __('Background Color', 'wr-elementor-widgets'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wr-blog-readmore-btn:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'     => 'readmore_border_hover',
                'selector' => '{{WRAPPER}} .wr-blog-readmore-btn:hover',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        // Typography
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'readmore_typography',
                'label'    => __('Typography', 'wr-elementor-widgets'),
                'selector' => '{{WRAPPER}} .wr-blog-readmore-btn',
            ]
        );

        // Padding
        $this->add_responsive_control(
            'readmore_padding',
            [
                'label'      => __('Padding', 'wr-elementor-widgets'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .wr-blog-readmore-btn' =>
                        'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Radius
        $this->add_responsive_control(
            'readmore_radius',
            [
                'label'      => __('Border Radius', 'wr-elementor-widgets'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .wr-blog-readmore-btn' =>
                        'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Helper: get categories
     */
    protected function get_categories_for_control()
    {
        $terms   = get_terms(
            [
                'taxonomy'   => 'category',
                'hide_empty' => false,
            ]
        );
        $options = ['' => __('- All Categories -', 'wr-elementor-widgets')];

        if (!is_wp_error($terms) && !empty($terms)) {
            foreach ($terms as $term) {
                $options[$term->term_id] = $term->name;
            }
        }

        return $options;
    }

    protected function render()
    {
        $settings  = $this->get_settings_for_display();
        $widget_id = $this->get_id();

        $posts_per_page = !empty($settings['posts_per_page'])
            ? (int)$settings['posts_per_page']
            : 6;

        if ($posts_per_page < 1) {
            $posts_per_page = 6;
        }

        $parent_cat = !empty($settings['parent_category'])
            ? (int)$settings['parent_category']
            : 0;

        $query_args = [
            'post_type'      => 'post',
            'post_status'    => 'publish',
            'posts_per_page' => $posts_per_page,
            'paged'          => 1,
        ];

        if ($parent_cat) {
            $query_args['cat'] = $parent_cat;
        }

        $query = new WP_Query($query_args);

        $ajax_args = [
            'excerpt_length' => isset($settings['excerpt_length']) ? (int)$settings['excerpt_length'] : 18,
            'show_excerpt'   => (isset($settings['show_excerpt']) && 'yes' === $settings['show_excerpt']) ? 'yes' : 'no',
            'show_read_more' => (isset($settings['show_read_more']) && 'yes' === $settings['show_read_more']) ? 'yes' : 'no',
            'read_more_text' => !empty($settings['read_more_text'])
                ? $settings['read_more_text']
                : __('Read more', 'wr-elementor-widgets'),
        ];

        $nonce = wp_create_nonce('wr_blog_grid_nonce');
        ?>

        <div class="wr-blog-grid-wrapper"
             data-widget-id="<?php echo esc_attr($widget_id); ?>"
             data-mode="auto"
             data-perpage="<?php echo esc_attr($posts_per_page); ?>"
             data-parent-cat="<?php echo esc_attr($parent_cat); ?>"
             data-excerpt="<?php echo esc_attr($ajax_args['excerpt_length']); ?>"
             data-show-excerpt="<?php echo esc_attr($ajax_args['show_excerpt']); ?>"
             data-show-readmore="<?php echo esc_attr($ajax_args['show_read_more']); ?>"
             data-readmore-text="<?php echo esc_attr($ajax_args['read_more_text']); ?>"
             data-ajax-url="<?php echo esc_url(admin_url('admin-ajax.php')); ?>"
             data-nonce="<?php echo esc_attr($nonce); ?>">

            <div class="wr-blog-grid-layout">

                <aside class="wr-blog-grid-sidebar">
                    <div class="wr-blog-sidebar-title">
                        <?php esc_html_e('Categories', 'wr-elementor-widgets'); ?>
                    </div>

                    <ul class="wr-blog-category-list">
                        <li class="wr-blog-cat-item is-active" data-cat-id="0">
                            <?php esc_html_e('All', 'wr-elementor-widgets'); ?>
                        </li>

                        <?php
                        $cat_args = [
                            'taxonomy'   => 'category',
                            'hide_empty' => true,
                        ];

                        if ($parent_cat) {
                            $cat_args['parent'] = $parent_cat;
                        }

                        $categories = get_terms($cat_args);

                        if (!is_wp_error($categories) && !empty($categories)) :
                            foreach ($categories as $cat) :
                                ?>
                                <li class="wr-blog-cat-item" data-cat-id="<?php echo esc_attr($cat->term_id); ?>">
                                    <?php echo esc_html($cat->name); ?>
                                </li>
                                <?php
                            endforeach;
                        endif;
                        ?>
                    </ul>
                </aside>

                <div class="wr-blog-grid-main">

                    <div id="wr-blog-grid-<?php echo esc_attr($widget_id); ?>" class="wr-blog-grid">
                        <?php
                        if (function_exists('wr_ew_render_blog_grid_items')) {
                            wr_ew_render_blog_grid_items($query, $ajax_args);
                        }
                        ?>
                    </div>

                    <div id="wr-blog-pagination-<?php echo esc_attr($widget_id); ?>" class="wr-blog-grid-pagination">
                        <?php
                        if (function_exists('wr_ew_render_blog_grid_pagination')) {
                            wr_ew_render_blog_grid_pagination($query, $widget_id);
                        }
                        ?>
                    </div>

                </div>

            </div>
        </div>

        <?php
        wp_reset_postdata();
    }
}
