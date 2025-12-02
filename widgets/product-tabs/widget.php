<?php
/**
 * WR Elementor Widget - Product Tabs (Dynamic + AJAX + Select2 Category & Tag Picker)
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class WR_EW_Product_Tabs extends \Elementor\Widget_Base {

    public function get_name() {
        return 'wr-product-tabs';
    }

    public function get_title() {
        return __( 'WR Product Tabs', 'wr-elementor-widgets' );
    }

    public function get_icon() {
        return 'eicon-tabs';
    }

    public function get_categories() {
        return [ 'wr-widgets', 'wr-ecommerce-elements' ];
    }

    public function get_style_depends() {
        return [ 'wr-product-tabs-css' ];
    }

    public function get_script_depends() {
        return [ 'wr-product-tabs-js' ];
    }

    /**
     * ---------------------------------------
     *  CATEGORY LIST LOADER
     * ---------------------------------------
     */
    private function get_product_categories() {

        $terms = get_terms([
            'taxonomy'   => 'product_cat',
            'hide_empty' => false,
        ]);

        $options = [];

        if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
            foreach ( $terms as $term ) {
                $options[$term->slug] = $term->name;
            }
        }

        return $options;
    }

    /**
     * ---------------------------------------
     *  TAG LIST LOADER
     * ---------------------------------------
     */
    private function get_product_tags() {

        $terms = get_terms([
            'taxonomy'   => 'product_tag',
            'hide_empty' => false,
        ]);

        $options = [];

        if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
            foreach ( $terms as $term ) {
                $options[$term->slug] = $term->name;
            }
        }

        return $options;
    }



    protected function register_controls() {

        // -----------------------------------------
        // CONTENT: TABS
        // -----------------------------------------
        $this->start_controls_section(
            'section_tabs',
            [
                'label' => __( 'Tabs', 'wr-elementor-widgets' ),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'tab_title',
            [
                'label'       => __( 'Tab Title', 'wr-elementor-widgets' ),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'default'     => __( 'New Arrivals', 'wr-elementor-widgets' ),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'query_type',
            [
                'label'   => __( 'Query Type', 'wr-elementor-widgets' ),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => 'new_arrivals',
                'options' => [
                    'best_sellers'  => __( 'Best Sellers', 'wr-elementor-widgets' ),
                    'new_arrivals'  => __( 'New Arrivals', 'wr-elementor-widgets' ),
                    'on_sale'       => __( 'On Sale', 'wr-elementor-widgets' ),
                    'featured'      => __( 'Featured Products', 'wr-elementor-widgets' ),
                    'category'      => __( 'By Category', 'wr-elementor-widgets' ),
                    'tag'           => __( 'By Tag', 'wr-elementor-widgets' ),
                ],
            ]
        );

        $repeater->add_control(
            'product_count',
            [
                'label'   => __( 'Products Per Tab', 'wr-elementor-widgets' ),
                'type'    => \Elementor\Controls_Manager::NUMBER,
                'default' => 6,
                'min'     => 1,
                'max'     => 24,
            ]
        );

        /**
         * ---------------------------------------
         *  CATEGORY SELECT2
         * ---------------------------------------
         */
        $repeater->add_control(
            'product_cat',
            [
                'label'       => __( 'Category', 'wr-elementor-widgets' ),
                'type'        => \Elementor\Controls_Manager::SELECT2,
                'options'     => $this->get_product_categories(),
                'multiple'    => false,
                'label_block' => true,
                'condition'   => [
                    'query_type' => 'category',
                ],
            ]
        );

        /**
         * ---------------------------------------
         *  TAG SELECT2 (NEW)
         * ---------------------------------------
         */
        $repeater->add_control(
            'product_tag',
            [
                'label'       => __( 'Tag', 'wr-elementor-widgets' ),
                'type'        => \Elementor\Controls_Manager::SELECT2,
                'options'     => $this->get_product_tags(),
                'multiple'    => false,
                'label_block' => true,
                'condition'   => [
                    'query_type' => 'tag',
                ],
            ]
        );


        $this->add_control(
            'tabs',
            [
                'label'       => __( 'Product Tabs', 'wr-elementor-widgets' ),
                'type'        => \Elementor\Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    [ 'tab_title' => 'Best Sellers',    'query_type' => 'best_sellers',  'product_count' => 6 ],
                    [ 'tab_title' => 'New Arrivals',    'query_type' => 'new_arrivals',  'product_count' => 6 ],
                    [ 'tab_title' => 'On Sale',         'query_type' => 'on_sale',       'product_count' => 6 ],
                    [ 'tab_title' => 'Featured Products','query_type' => 'featured',     'product_count' => 6 ],
                ],
                'title_field' => '{{{ tab_title }}}',
            ]
        );

        $this->end_controls_section();



        // -----------------------------------------
        // STYLE SECTION
        // -----------------------------------------
        $this->start_controls_section(
            'section_style_tabs',
            [
                'label' => __( 'Tabs Style', 'wr-elementor-widgets' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'tabs_alignment',
            [
                'label'   => __( 'Alignment', 'wr-elementor-widgets' ),
                'type'    => \Elementor\Controls_Manager::CHOOSE,
                'default' => 'flex-start',
                'options' => [
                    'flex-start' => [
                        'title' => __( 'Left', 'wr-elementor-widgets' ),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'wr-elementor-widgets' ),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'flex-end' => [
                        'title' => __( 'Right', 'wr-elementor-widgets' ),
                        'icon'  => 'eicon-text-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wr-pt-tabs-nav' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }



    /**
     * ---------------------------------------
     *  FRONTEND RENDER
     * ---------------------------------------
     */
    public function render() {
        $settings  = $this->get_settings_for_display();
        $tabs      = $settings['tabs'] ?? [];
        $widget_id = $this->get_id();

        if ( empty( $tabs ) ) return;

        $ajax_url = admin_url( 'admin-ajax.php' );
        ?>

        <div class="wr-product-tabs-wrapper"
             data-widget-id="<?php echo esc_attr($widget_id); ?>"
             data-ajax-url="<?php echo esc_url($ajax_url); ?>">

            <div class="wr-pt-header">
                <ul class="wr-pt-tabs-nav">

                    <?php foreach ( $tabs as $index => $tab ) :

                        $active = ($index === 0) ? ' is-active' : '';

                        ?>
                        <li class="wr-pt-tab<?php echo $active; ?>"
                            data-query-type="<?php echo esc_attr($tab['query_type']); ?>"
                            data-count="<?php echo esc_attr($tab['product_count']); ?>"
                            data-cat="<?php echo esc_attr($tab['product_cat'] ?? ''); ?>"
                            data-tag="<?php echo esc_attr($tab['product_tag'] ?? ''); ?>">

                            <button class="wr-pt-tab-button">
                                <span><?php echo esc_html($tab['tab_title']); ?></span>
                            </button>

                        </li>

                    <?php endforeach; ?>

                </ul>
            </div>

            <div class="wr-pt-body">

                <div id="wr-pt-products-<?php echo esc_attr($widget_id); ?>" class="wr-pt-products-grid">
                    <?php self::render_products_for_tab( $tabs[0] ); ?>
                </div>

                <div class="wr-pt-loading-overlay">
                    <div class="wr-pt-spinner"></div>
                </div>

            </div>

        </div>

        <?php
    }



    /**
     * ---------------------------------------
     *  QUERY BUILDER
     * ---------------------------------------
     */
    protected static function build_query_args( $tab ) {

        $query_type    = $tab['query_type'] ?? 'new_arrivals';
        $product_count = intval($tab['product_count'] ?? 6);
        $product_cat   = $tab['product_cat'] ?? '';
        $product_tag   = $tab['product_tag'] ?? '';

        $args = [
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'posts_per_page' => $product_count,
        ];

        switch ( $query_type ) {

            case 'best_sellers':
                $args['meta_key'] = 'total_sales';
                $args['orderby']  = 'meta_value_num';
                $args['order']    = 'DESC';
                break;

            case 'new_arrivals':
                $args['orderby'] = 'date';
                $args['order']   = 'DESC';
                break;

            case 'on_sale':
                $args['post__in'] = wc_get_product_ids_on_sale();
                break;

            case 'featured':
                $args['tax_query'] = [
                    [
                        'taxonomy' => 'product_visibility',
                        'field'    => 'name',
                        'terms'    => 'featured'
                    ]
                ];
                break;

            case 'category':
                if ( $product_cat ) {
                    $args['tax_query'] = [
                        [
                            'taxonomy' => 'product_cat',
                            'field'    => 'slug',
                            'terms'    => [$product_cat],
                        ]
                    ];
                }
                break;

            case 'tag':
                if ( $product_tag ) {
                    $args['tax_query'] = [
                        [
                            'taxonomy' => 'product_tag',
                            'field'    => 'slug',
                            'terms'    => [$product_tag],
                        ]
                    ];
                }
                break;
        }

        return $args;
    }



    /**
     * ---------------------------------------
     *  RENDER PRODUCTS
     * ---------------------------------------
     */
    public static function render_products_for_tab( $tab ) {

        if ( ! class_exists( 'WooCommerce' ) ) {
            echo '<p class="wr-pt-message">WooCommerce is not active.</p>';
            return;
        }

        $args = self::build_query_args($tab);
        $loop = new WP_Query($args);

        if ( ! $loop->have_posts() ) {
            echo '<p class="wr-pt-message">No products found for this tab.</p>';
            return;
        }

        echo '<div class="wr-pt-grid-inner">';

        while ( $loop->have_posts() ) :
            $loop->the_post();

            $product = wc_get_product( get_the_ID() );

            if ( ! $product ) {
                continue;
            }

            echo '<div class="wr-product-tabs__item">';
                wr_render_product_card( $product, 'tabs' );
            echo '</div>';

        endwhile;

        echo '</div>';
        wp_reset_postdata();
    }



    /**
     * ---------------------------------------
     *  AJAX LOADER
     * ---------------------------------------
     */
    public static function ajax_load_tab() {

        $tab = [
            'query_type'    => sanitize_text_field($_POST['query_type'] ?? 'new_arrivals'),
            'product_count' => intval($_POST['product_count'] ?? 6),
            'product_cat'   => sanitize_text_field($_POST['category'] ?? ''),
            'product_tag'   => sanitize_text_field($_POST['tag'] ?? ''),
        ];

        self::render_products_for_tab( $tab );

        wp_die();
    }
}


// AJAX HOOKS
add_action( 'wp_ajax_wr_product_tabs_load',       [ 'WR_EW_Product_Tabs', 'ajax_load_tab' ] );
add_action( 'wp_ajax_nopriv_wr_product_tabs_load',[ 'WR_EW_Product_Tabs', 'ajax_load_tab' ] );
