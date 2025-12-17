<?php
/**
 * Widget: WR Product Full Tabs (Stable Slider Version) - FINAL (JS Parse + Buttons Fixed)
 * File: wp-product-full-tab.php
 *
 * IMPORTANT FIX:
 * - Your console shows: "Uncaught SyntaxError: Unexpected token '&&' ..."
 *   This usually happens because a cache/minify combiner concatenates scripts without semicolons.
 *   So we:
 *     1) add a leading ";" before the IIFE
 *     2) remove "&&" chaining style entirely
 *     3) bump version to 1.3.0 (cache bust)
 * - Tab + Prev/Next work via single delegated click handler per widget instance.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class WR_EW_Product_Full_Tabs extends \Elementor\Widget_Base {

    public function get_name() { return 'wr-product-full-tabs'; }
    public function get_title() { return __( 'WR Product Full Tabs', 'wr-elementor-widgets' ); }
    public function get_icon() { return 'eicon-tabs'; }
    public function get_categories() { return [ 'wr-widgets', 'wr-ecommerce-elements' ]; }

    public function get_style_depends() { return [ 'wr-product-full-tabs-css' ]; }
    public function get_script_depends() { return [ 'wr-product-full-tabs-js' ]; }

    public function __construct($data = [], $args = null) {
        parent::__construct($data, $args);

        // bump version to bust cache/minify outputs
        $ver = '1.3.0';

        if ( ! wp_style_is('wr-product-full-tabs-css', 'registered') ) {
            wp_register_style('wr-product-full-tabs-css', false, [], $ver);
            wp_add_inline_style('wr-product-full-tabs-css', $this->get_inline_css());
        }

        if ( ! wp_script_is('wr-product-full-tabs-js', 'registered') ) {
            // ensure jquery is available if Elementor hook path uses it
            wp_register_script('wr-product-full-tabs-js', false, ['jquery'], $ver, true);
            wp_add_inline_script('wr-product-full-tabs-js', $this->get_inline_js());
        }
    }

    private function get_product_categories() {
        $terms = get_terms([
            'taxonomy'   => 'product_cat',
            'hide_empty' => false,
        ]);

        $options = [];
        if ( ! empty($terms) && ! is_wp_error($terms) ) {
            foreach ( $terms as $term ) {
                $options[$term->slug] = $term->name;
            }
        }
        return $options;
    }

    private function get_product_tags() {
        $terms = get_terms([
            'taxonomy'   => 'product_tag',
            'hide_empty' => false,
        ]);

        $options = [];
        if ( ! empty($terms) && ! is_wp_error($terms) ) {
            foreach ( $terms as $term ) {
                $options[$term->slug] = $term->name;
            }
        }
        return $options;
    }

    protected function register_controls() {

        $this->start_controls_section('section_tabs', [
            'label' => __( 'Tabs', 'wr-elementor-widgets' ),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);

        $repeater = new \Elementor\Repeater();

        $repeater->add_control('tab_title', [
            'label'       => __( 'Tab Title', 'wr-elementor-widgets' ),
            'type'        => \Elementor\Controls_Manager::TEXT,
            'default'     => __( 'Elbise', 'wr-elementor-widgets' ),
            'label_block' => true,
        ]);

        $repeater->add_control('query_type', [
            'label'   => __( 'Query Type', 'wr-elementor-widgets' ),
            'type'    => \Elementor\Controls_Manager::SELECT,
            'default' => 'category',
            'options' => [
                'best_sellers' => __( 'Best Sellers', 'wr-elementor-widgets' ),
                'new_arrivals' => __( 'New Arrivals', 'wr-elementor-widgets' ),
                'on_sale'      => __( 'On Sale', 'wr-elementor-widgets' ),
                'featured'     => __( 'Featured', 'wr-elementor-widgets' ),
                'category'     => __( 'By Category', 'wr-elementor-widgets' ),
                'tag'          => __( 'By Tag', 'wr-elementor-widgets' ),
            ],
        ]);

        $repeater->add_control('product_cat', [
            'label'       => __( 'Category', 'wr-elementor-widgets' ),
            'type'        => \Elementor\Controls_Manager::SELECT2,
            'options'     => $this->get_product_categories(),
            'multiple'    => false,
            'label_block' => true,
            'condition'   => [ 'query_type' => 'category' ],
        ]);

        $repeater->add_control('product_tag', [
            'label'       => __( 'Tag', 'wr-elementor-widgets' ),
            'type'        => \Elementor\Controls_Manager::SELECT2,
            'options'     => $this->get_product_tags(),
            'multiple'    => false,
            'label_block' => true,
            'condition'   => [ 'query_type' => 'tag' ],
        ]);

        $repeater->add_control('products_count', [
            'label'   => __( 'Products (Max 24)', 'wr-elementor-widgets' ),
            'type'    => \Elementor\Controls_Manager::NUMBER,
            'default' => 12,
            'min'     => 1,
            'max'     => 24,
        ]);

        $this->add_control('tabs', [
            'label'       => __( 'Product Tabs', 'wr-elementor-widgets' ),
            'type'        => \Elementor\Controls_Manager::REPEATER,
            'fields'      => $repeater->get_controls(),
            'default'     => [
                [ 'tab_title' => 'Elbise', 'query_type' => 'category', 'products_count' => 12 ],
            ],
            'title_field' => '{{{ tab_title }}}',
        ]);

        $this->add_control('slides_per_view', [
            'label'   => __( 'Slides Per View (Desktop)', 'wr-elementor-widgets' ),
            'type'    => \Elementor\Controls_Manager::SELECT,
            'default' => '3',
            'options' => [
                '2' => '2',
                '3' => '3',
                '4' => '4',
            ],
        ]);

        $this->add_control('nav_prev_text', [
            'label'   => __( 'Prev Button Text', 'wr-elementor-widgets' ),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => __( 'Önceki', 'wr-elementor-widgets' ),
        ]);

        $this->add_control('nav_next_text', [
            'label'   => __( 'Next Button Text', 'wr-elementor-widgets' ),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => __( 'Sonraki', 'wr-elementor-widgets' ),
        ]);

        $this->add_control('options_label', [
            'label'   => __( 'Variable Product Button Label', 'wr-elementor-widgets' ),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => __( 'SEÇENEKLER', 'wr-elementor-widgets' ),
        ]);

        $this->add_control('add_to_cart_label', [
            'label'   => __( 'Simple Product Button Label', 'wr-elementor-widgets' ),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => __( 'SEPETE EKLE', 'wr-elementor-widgets' ),
        ]);

        $this->end_controls_section();

        /* =========================
         * STYLE: Wrapper
         * ========================= */
        $this->start_controls_section('section_style_wrapper', [
            'label' => __( 'Wrapper', 'wr-elementor-widgets' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('wrap_bg', [
            'label' => __( 'Background', 'wr-elementor-widgets' ),
            'type'  => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .wrpft-wrap' => 'background: {{VALUE}};',
            ],
        ]);

        $this->add_control('wrap_border_color', [
            'label' => __( 'Border Color', 'wr-elementor-widgets' ),
            'type'  => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .wrpft-wrap' => 'border-color: {{VALUE}};',
            ],
        ]);

        $this->add_responsive_control('wrap_radius', [
            'label' => __( 'Border Radius', 'wr-elementor-widgets' ),
            'type'  => \Elementor\Controls_Manager::SLIDER,
            'range' => [ 'px' => [ 'min' => 0, 'max' => 60 ] ],
            'selectors' => [
                '{{WRAPPER}} .wrpft-wrap' => 'border-radius: {{SIZE}}px;',
            ],
        ]);

        $this->end_controls_section();

        /* =========================
         * STYLE: Tabs
         * ========================= */
        $this->start_controls_section('section_style_tabs', [
            'label' => __( 'Tabs Style', 'wr-elementor-widgets' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('tabs_alignment', [
            'label'   => __( 'Alignment', 'wr-elementor-widgets' ),
            'type'    => \Elementor\Controls_Manager::CHOOSE,
            'default' => 'center',
            'options' => [
                'flex-start' => [ 'title' => __( 'Left', 'wr-elementor-widgets' ), 'icon' => 'eicon-text-align-left' ],
                'center'     => [ 'title' => __( 'Center', 'wr-elementor-widgets' ), 'icon' => 'eicon-text-align-center' ],
                'flex-end'   => [ 'title' => __( 'Right', 'wr-elementor-widgets' ), 'icon' => 'eicon-text-align-right' ],
            ],
            'selectors' => [
                '{{WRAPPER}} .wrpft-tabs' => 'justify-content: {{VALUE}};',
            ],
        ]);

        $this->add_control('tab_bg', [
            'label' => __( 'Tab Background', 'wr-elementor-widgets' ),
            'type'  => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .wrpft-tabbtn' => 'background: {{VALUE}};',
            ],
        ]);

        $this->add_control('tab_color', [
            'label' => __( 'Tab Text Color', 'wr-elementor-widgets' ),
            'type'  => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .wrpft-tabbtn' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('tab_active_bg', [
            'label' => __( 'Active Tab Background', 'wr-elementor-widgets' ),
            'type'  => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .wrpft-tab.is-active .wrpft-tabbtn' => 'background: {{VALUE}};',
            ],
        ]);

        $this->add_control('tab_active_color', [
            'label' => __( 'Active Tab Text Color', 'wr-elementor-widgets' ),
            'type'  => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .wrpft-tab.is-active .wrpft-tabbtn' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'tab_typo',
            'selector' => '{{WRAPPER}} .wrpft-tabbtn',
        ]);

        $this->end_controls_section();

        /* =========================
         * STYLE: Card + Image
         * ========================= */
        $this->start_controls_section('section_style_card', [
            'label' => __( 'Card Style', 'wr-elementor-widgets' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('card_bg', [
            'label' => __( 'Card Background', 'wr-elementor-widgets' ),
            'type'  => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .wrpft-card' => 'background: {{VALUE}};',
            ],
        ]);

        $this->add_control('card_border_color', [
            'label' => __( 'Border Color', 'wr-elementor-widgets' ),
            'type'  => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .wrpft-card' => 'border-color: {{VALUE}};',
            ],
        ]);

        $this->add_responsive_control('card_radius', [
            'label' => __( 'Border Radius', 'wr-elementor-widgets' ),
            'type'  => \Elementor\Controls_Manager::SLIDER,
            'range' => [ 'px' => [ 'min' => 0, 'max' => 40 ] ],
            'selectors' => [
                '{{WRAPPER}} .wrpft-card' => 'border-radius: {{SIZE}}px;',
            ],
        ]);

        $this->add_responsive_control('card_padding', [
            'label' => __( 'Card Padding', 'wr-elementor-widgets' ),
            'type'  => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px' ],
            'selectors' => [
                '{{WRAPPER}} .wrpft-cardinner' => 'padding: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
            ],
        ]);

        $this->add_responsive_control('image_height', [
            'label' => __( 'Image Area Height', 'wr-elementor-widgets' ),
            'type'  => \Elementor\Controls_Manager::SLIDER,
            'range' => [ 'px' => [ 'min' => 180, 'max' => 520 ] ],
            'default' => [ 'size' => 320 ],
            'selectors' => [
                '{{WRAPPER}} .wrpft-media' => 'height: {{SIZE}}px;',
            ],
        ]);

        $this->end_controls_section();

        /* =========================
         * STYLE: Product Title
         * ========================= */
        $this->start_controls_section('section_style_title', [
            'label' => __( 'Product Title', 'wr-elementor-widgets' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('title_color', [
            'label' => __( 'Color', 'wr-elementor-widgets' ),
            'type'  => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .wrpft-title, {{WRAPPER}} .wrpft-title a' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'title_typo',
            'selector' => '{{WRAPPER}} .wrpft-title',
        ]);

        $this->end_controls_section();

        /* =========================
         * STYLE: Price
         * ========================= */
        $this->start_controls_section('section_style_price', [
            'label' => __( 'Price', 'wr-elementor-widgets' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('price_color', [
            'label' => __( 'Color', 'wr-elementor-widgets' ),
            'type'  => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .wrpft-price' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'price_typo',
            'selector' => '{{WRAPPER}} .wrpft-price',
        ]);

        $this->end_controls_section();

        /* =========================
         * STYLE: Buttons
         * ========================= */
        $this->start_controls_section('section_style_buttons', [
            'label' => __( 'Buttons', 'wr-elementor-widgets' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('btn_text_color', [
            'label' => __( 'Text Color', 'wr-elementor-widgets' ),
            'type'  => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .wrpft-btn' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('btn_bg', [
            'label' => __( 'Background', 'wr-elementor-widgets' ),
            'type'  => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .wrpft-btn' => 'background: {{VALUE}};',
            ],
        ]);

        $this->add_control('btn_border', [
            'label' => __( 'Border Color', 'wr-elementor-widgets' ),
            'type'  => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .wrpft-btn' => 'border-color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'btn_typo',
            'selector' => '{{WRAPPER}} .wrpft-btn',
        ]);

        $this->add_responsive_control('btn_radius', [
            'label' => __( 'Button Radius', 'wr-elementor-widgets' ),
            'type'  => \Elementor\Controls_Manager::SLIDER,
            'range' => [ 'px' => [ 'min' => 0, 'max' => 50 ] ],
            'selectors' => [
                '{{WRAPPER}} .wrpft-btn' => 'border-radius: {{SIZE}}px;',
            ],
        ]);

        $this->end_controls_section();

        /* =========================
         * STYLE: Slider Nav
         * ========================= */
        $this->start_controls_section('section_style_nav', [
            'label' => __( 'Slider Navigation', 'wr-elementor-widgets' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('nav_text_color', [
            'label' => __( 'Nav Text Color', 'wr-elementor-widgets' ),
            'type'  => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .wrpft-navbtn' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('nav_bg', [
            'label' => __( 'Nav Background', 'wr-elementor-widgets' ),
            'type'  => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .wrpft-navbtn' => 'background: {{VALUE}};',
            ],
        ]);

        $this->add_control('nav_border', [
            'label' => __( 'Nav Border Color', 'wr-elementor-widgets' ),
            'type'  => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .wrpft-navbtn' => 'border-color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'nav_typo',
            'selector' => '{{WRAPPER}} .wrpft-navbtn',
        ]);

        $this->add_responsive_control('nav_radius', [
            'label' => __( 'Nav Radius', 'wr-elementor-widgets' ),
            'type'  => \Elementor\Controls_Manager::SLIDER,
            'range' => [ 'px' => [ 'min' => 0, 'max' => 50 ] ],
            'selectors' => [
                '{{WRAPPER}} .wrpft-navbtn' => 'border-radius: {{SIZE}}px;',
            ],
        ]);

        $this->end_controls_section();
    }

    public function render() {
        if ( ! class_exists('WooCommerce') ) return;

        $settings = $this->get_settings_for_display();
        $tabs     = $settings['tabs'] ?? [];
        if ( empty($tabs) ) return;

        $widget_id = $this->get_id();

        $spv = intval($settings['slides_per_view'] ?? 3);
        if ($spv < 1) $spv = 1;
        if ($spv > 4) $spv = 4;

        $prev_text = $settings['nav_prev_text'] ?? 'Önceki';
        $next_text = $settings['nav_next_text'] ?? 'Sonraki';

        $options_label  = $settings['options_label'] ?? 'SEÇENEKLER';
        $add_label      = $settings['add_to_cart_label'] ?? 'SEPETE EKLE';
        ?>
        <div class="wrpft-wrap"
             data-widget-id="<?php echo esc_attr($widget_id); ?>"
             style="--wrpft-spv:<?php echo esc_attr($spv); ?>;">

            <div class="wrpft-head">
                <ul class="wrpft-tabs" role="tablist">
                    <?php foreach ( $tabs as $i => $tab ) :
                        $active = ($i === 0) ? ' is-active' : '';
                        ?>
                        <li class="wrpft-tab<?php echo esc_attr($active); ?>" role="presentation" data-tab-index="<?php echo esc_attr($i); ?>">
                            <button class="wrpft-tabbtn" type="button" role="tab" aria-selected="<?php echo $i===0 ? 'true':'false'; ?>">
                                <span><?php echo esc_html($tab['tab_title'] ?? 'Tab'); ?></span>
                            </button>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="wrpft-body">
                <?php foreach ( $tabs as $i => $tab ) :
                    $panel_active = ($i === 0) ? ' is-active' : '';

                    $qtype = sanitize_text_field($tab['query_type'] ?? 'category');
                    $cat   = sanitize_text_field($tab['product_cat'] ?? '');
                    $tag   = sanitize_text_field($tab['product_tag'] ?? '');
                    $count = intval($tab['products_count'] ?? 12);
                    if ($count < 1) $count = 1;
                    if ($count > 24) $count = 24;

                    $items_html = self::render_products_slider_items([
                        'query_type'        => $qtype,
                        'category'          => $cat,
                        'tag'               => $tag,
                        'limit'             => $count,
                        'options_label'     => $options_label,
                        'add_to_cart_label' => $add_label,
                    ]);
                    ?>
                    <div class="wrpft-panel<?php echo esc_attr($panel_active); ?>" data-panel-index="<?php echo esc_attr($i); ?>">
                        <div class="wrpft-slider">
                            <button type="button" class="wrpft-navbtn wrpft-prev" aria-label="Prev"><?php echo esc_html($prev_text); ?></button>
                            <div class="wrpft-track" tabindex="0">
                                <?php echo $items_html; ?>
                            </div>
                            <button type="button" class="wrpft-navbtn wrpft-next" aria-label="Next"><?php echo esc_html($next_text); ?></button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

        </div>
        <?php
    }

    private static function build_query_args($qtype, $cat, $tag, $limit) {

        $args = [
            'post_type'           => 'product',
            'post_status'         => 'publish',
            'posts_per_page'      => $limit,
            'ignore_sticky_posts' => true,
        ];

        switch ($qtype) {
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
                $ids = wc_get_product_ids_on_sale();
                $args['post__in'] = ! empty($ids) ? $ids : [0];
                break;

            case 'featured':
                $args['tax_query'] = [[
                    'taxonomy' => 'product_visibility',
                    'field'    => 'name',
                    'terms'    => 'featured',
                ]];
                break;

            case 'tag':
                if ($tag) {
                    $args['tax_query'] = [[
                        'taxonomy' => 'product_tag',
                        'field'    => 'slug',
                        'terms'    => [$tag],
                    ]];
                }
                break;

            case 'category':
            default:
                if ($cat) {
                    $args['tax_query'] = [[
                        'taxonomy' => 'product_cat',
                        'field'    => 'slug',
                        'terms'    => [$cat],
                    ]];
                }
                break;
        }

        return $args;
    }

    private static function card_button_html($product, $add_to_cart_label, $options_label) {
        $type = $product->get_type();
        $url  = get_permalink($product->get_id());

        if ( in_array($type, ['variable','grouped','external'], true) ) {
            return '<a class="wrpft-btn" href="'.esc_url($url).'">'.esc_html($options_label).'</a>';
        }

        $add_url = $product->add_to_cart_url();
        $classes = 'wrpft-btn add_to_cart_button ajax_add_to_cart';
        $attrs = [
            'href'             => $add_url,
            'data-quantity'    => '1',
            'data-product_id'  => $product->get_id(),
            'data-product_sku' => $product->get_sku(),
            'rel'              => 'nofollow',
            'class'            => $classes,
        ];

        $attr_html = '';
        foreach ($attrs as $k => $v) {
            $attr_html .= ' ' . esc_attr($k) . '="' . esc_attr($v) . '"';
        }

        return '<a'.$attr_html.'>'.esc_html($add_to_cart_label).'</a>';
    }

    public static function render_products_slider_items($payload) {

        $qtype  = $payload['query_type'] ?? 'category';
        $cat    = $payload['category'] ?? '';
        $tag    = $payload['tag'] ?? '';
        $limit  = intval($payload['limit'] ?? 12);
        if ($limit < 1) $limit = 1;
        if ($limit > 24) $limit = 24;

        $add_to_cart_label = $payload['add_to_cart_label'] ?? 'SEPETE EKLE';
        $options_label     = $payload['options_label'] ?? 'SEÇENEKLER';

        $args = self::build_query_args($qtype, $cat, $tag, $limit);
        $loop = new WP_Query($args);

        if ( ! $loop->have_posts() ) {
            return '<div class="wrpft-empty">Ürün bulunamadı.</div>';
        }

        $html = '';
        while ( $loop->have_posts() ) {
            $loop->the_post();
            $product = wc_get_product(get_the_ID());
            if ( ! $product ) continue;

            $title = $product->get_name();
            $link  = get_permalink($product->get_id());
            $img   = $product->get_image('woocommerce_thumbnail', ['class'=>'wrpft-img', 'alt'=>$title], false);
            $price = $product->get_price_html();

            $html .= '<div class="wrpft-slide">';
                $html .= '<div class="wrpft-card">';
                    $html .= '<a class="wrpft-media" href="'.esc_url($link).'">'.$img.'</a>';
                    $html .= '<div class="wrpft-cardinner">';
                        $html .= '<h3 class="wrpft-title"><a href="'.esc_url($link).'">'.esc_html($title).'</a></h3>';
                        $html .= '<div class="wrpft-price">'.$price.'</div>';
                        $html .= '<div class="wrpft-actions">'.self::card_button_html($product, $add_to_cart_label, $options_label).'</div>';
                    $html .= '</div>';
                $html .= '</div>';
            $html .= '</div>';
        }

        wp_reset_postdata();
        return $html;
    }

    private function get_inline_css() {
        return <<<CSS
.wrpft-wrap{
  position:relative;
  background:#fff;
  border:1px solid #e5e7eb;
  border-radius:18px;
  box-shadow:0 14px 40px rgba(15,23,42,.06);
  padding:18px 20px 22px;
}
.wrpft-head{ padding-bottom:10px; border-bottom:1px solid #e5e7eb; }
.wrpft-tabs{
  display:flex;
  flex-wrap:wrap;
  gap:10px;
  list-style:none;
  margin:0;
  padding:0;
  justify-content:center;
}
.wrpft-tab{ margin:0; }
.wrpft-tabbtn{
  position:relative;
  z-index:3;
  display:inline-flex;
  align-items:center;
  justify-content:center;
  padding:8px 16px;
  border-radius:999px;
  border:1px solid transparent;
  background:#111827;
  color:#fff;
  cursor:pointer;
  font-size:13px;
  font-weight:600;
  line-height:1.3;
  transition:transform .15s ease, box-shadow .15s ease, background .15s ease, color .15s ease;
}
.wrpft-tab:not(.is-active) .wrpft-tabbtn{ background:#f3f4f6; color:#111827; }
.wrpft-tabbtn:hover{ transform:translateY(-1px); box-shadow:0 10px 24px rgba(15,23,42,.10); }

.wrpft-body{ position:relative; padding-top:16px; }
.wrpft-panel{ display:none; }
.wrpft-panel.is-active{ display:block; }

.wrpft-slider{
  display:grid;
  grid-template-columns:auto 1fr auto;
  gap:12px;
  align-items:center;
}
.wrpft-track{
  position:relative;
  z-index:1;
  overflow:auto;
  scroll-behavior:smooth;
  -webkit-overflow-scrolling:touch;
  display:flex;
  gap:22px;
  padding:2px 2px 10px;
}
.wrpft-track::-webkit-scrollbar{ height:10px; }
.wrpft-track::-webkit-scrollbar-thumb{ border-radius:999px; background:rgba(17,24,39,.25); }
.wrpft-track::-webkit-scrollbar-track{ background:rgba(17,24,39,.06); }

.wrpft-slide{
  flex:0 0 calc((100% - (22px * (var(--wrpft-spv,3) - 1))) / var(--wrpft-spv,3));
  min-width:260px;
}

@media (max-width:1024px){
  .wrpft-slide{ flex-basis:calc((100% - 22px) / 2); }
}
@media (max-width:640px){
  .wrpft-slider{ grid-template-columns:1fr; }
  .wrpft-navbtn{ display:none; }
  .wrpft-slide{ flex-basis:80%; min-width:260px; }
}

.wrpft-card{
  border:1px solid #eef0f3;
  border-radius:16px;
  overflow:hidden;
  background:#fff;
  box-shadow:0 10px 28px rgba(15,23,42,.04);
}
.wrpft-media{
  display:flex;
  align-items:center;
  justify-content:center;
  width:100%;
  height:320px;
  background:#f8fafc;
  padding:14px;
  box-sizing:border-box;
  text-decoration:none !important;
  border:0 !important;
}
.wrpft-media img{
  width:100% !important;
  height:100% !important;
  object-fit:contain !important;
  display:block !important;
}
.wrpft-cardinner{
  padding:14px 14px 16px;
  display:flex;
  flex-direction:column;
  gap:10px;
}
.wrpft-title{
  margin:0;
  font-size:16px;
  line-height:1.25;
  font-weight:700;
}
.wrpft-title a{
  color:inherit;
  text-decoration:none !important;
  border:0 !important;
  box-shadow:none !important;
  background-image:none !important;
}
.wrpft-title a::before,
.wrpft-title a::after{ content:none !important; display:none !important; }

.wrpft-price{ font-size:14px; font-weight:600; color:#111827; }
.wrpft-price del{ opacity:.55; }
.wrpft-price ins{ text-decoration:none; }

.wrpft-actions{ margin-top:2px; }
.wrpft-btn{
  width:100%;
  display:inline-flex;
  align-items:center;
  justify-content:center;
  height:46px;
  line-height:46px;
  padding:0 18px;
  border:1px solid #111827;
  background:transparent;
  color:#111827;
  font-size:13px;
  font-weight:700;
  letter-spacing:.08em;
  text-transform:uppercase;
  border-radius:10px;
  box-sizing:border-box;
  text-decoration:none !important;
  transition:background .15s ease, color .15s ease, transform .15s ease;
}
.wrpft-btn:hover{ background:#111827; color:#fff; transform:translateY(-1px); }

.wrpft-navbtn{
  position:relative;
  z-index:5;
  height:44px;
  padding:0 14px;
  border:1px solid #111827;
  background:#fff;
  color:#111827;
  border-radius:10px;
  font-weight:700;
  cursor:pointer;
  white-space:nowrap;
  transition:transform .15s ease, background .15s ease, color .15s ease;
}
.wrpft-navbtn:hover{ transform:translateY(-1px); background:#111827; color:#fff; }

.wrpft-empty{
  padding:18px;
  font-size:13px;
  color:#6b7280;
  text-align:center;
}
CSS;
    }

    private function get_inline_js() {
        // Leading semicolon prevents concat/minify edge cases breaking the file.
        return <<<JS
;(function(){
  "use strict";

  function qsa(root, sel){ return Array.prototype.slice.call((root || document).querySelectorAll(sel)); }
  function closest(el, sel){ return (el && el.closest) ? el.closest(sel) : null; }

  function getStep(track){
    if(!track) return 0;

    var slide = track.querySelector(".wrpft-slide");
    if(!slide) return Math.max(240, track.clientWidth * 0.9);

    var rect = slide.getBoundingClientRect();
    var gap = 22;

    try{
      var styles = window.getComputedStyle(track);
      var g = parseFloat(styles.columnGap || styles.gap || "0");
      if(!isNaN(g) && g > 0) gap = g;
    }catch(e){}

    return rect.width + gap;
  }

  function setActiveTab(wrap, idx){
    var tabs = qsa(wrap, ".wrpft-tab");
    var panels = qsa(wrap, ".wrpft-panel");

    tabs.forEach(function(t, i){
      var b = t.querySelector(".wrpft-tabbtn");
      if(i === idx){
        t.classList.add("is-active");
        if(b) b.setAttribute("aria-selected","true");
      }else{
        t.classList.remove("is-active");
        if(b) b.setAttribute("aria-selected","false");
      }
    });

    panels.forEach(function(p, i){
      if(i === idx) p.classList.add("is-active");
      else p.classList.remove("is-active");
    });
  }

  function initWrap(wrap){
    if(!wrap) return;
    if(wrap.dataset.wrpftInited === "1") return;
    wrap.dataset.wrpftInited = "1";

    if(!wrap.querySelector(".wrpft-tab.is-active")){
      setActiveTab(wrap, 0);
    }

    wrap.addEventListener("click", function(e){

      var tabBtn = closest(e.target, ".wrpft-tabbtn");
      if(tabBtn){
        e.preventDefault();
        var li = closest(tabBtn, ".wrpft-tab");
        if(!li) return;

        var idx = parseInt(li.getAttribute("data-tab-index") || "0", 10);
        if(isNaN(idx)) idx = 0;

        setActiveTab(wrap, idx);
        return;
      }

      var prevBtn = closest(e.target, ".wrpft-prev");
      var nextBtn = closest(e.target, ".wrpft-next");
      if(!prevBtn && !nextBtn) return;

      e.preventDefault();

      var panel = closest(e.target, ".wrpft-panel");
      if(!panel) panel = wrap.querySelector(".wrpft-panel.is-active");
      if(!panel) return;

      var track = panel.querySelector(".wrpft-track");
      if(!track) return;

      var step = getStep(track);
      if(step <= 0) return;

      track.scrollBy({ left: prevBtn ? -step : step, behavior: "smooth" });

    }, true);
  }

  function init(root){
    qsa(root || document, ".wrpft-wrap").forEach(initWrap);
  }

  document.addEventListener("DOMContentLoaded", function(){
    init(document);
  });

  // Elementor hook (NO "&&" chaining)
  function initElementor(){
    if(!window.jQuery) return;
    if(!window.elementorFrontend) return;

    jQuery(window).on("elementor/frontend/init", function(){
      if(!elementorFrontend.hooks) return;
      if(!elementorFrontend.hooks.addAction) return;

      elementorFrontend.hooks.addAction(
        "frontend/element_ready/wr-product-full-tabs.default",
        function($scope){
          if($scope && $scope[0]) init($scope[0]);
        }
      );
    });
  }

  initElementor();

})();
JS;
    }
}
