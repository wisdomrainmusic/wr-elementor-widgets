<?php
/**
 * WR Elementor Widget - Tab Product Grid (FINAL + LOAD MORE)
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class WR_EW_Tab_Product_Grid extends \Elementor\Widget_Base {

    public function get_name() {
        return 'wr-tab-product-grid';
    }

    public function get_title() {
        return __( 'WR Tab Product Grid', 'wr-elementor-widgets' );
    }

    public function get_icon() {
        return 'eicon-tabs';
    }

    public function get_categories() {
        return [ 'wr-widgets', 'wr-ecommerce-elements' ];
    }

    public function get_style_depends() {
        return [ 'wr-tab-product-grid-css' ];
    }

    public function get_script_depends() {
        return [ 'wr-tab-product-grid-js' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'section_query',
            [
                'label' => __( 'Query', 'wr-elementor-widgets' ),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        // Parent product_cat Terms
        $terms = get_terms([
            'taxonomy'   => 'product_cat',
            'hide_empty' => false,
            'parent'     => 0,
        ]);

        $parent_cat_options = [ '' => __( '— Select Parent Category —', 'wr-elementor-widgets' ) ];

        if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
            foreach ( $terms as $term ) {
                $parent_cat_options[ $term->term_id ] = $term->name;
            }
        }

        $this->add_control(
            'parent_category',
            [
                'label'       => __( 'Parent Category', 'wr-elementor-widgets' ),
                'type'        => \Elementor\Controls_Manager::SELECT2,
                'options'     => $parent_cat_options,
                'label_block' => true,
                'multiple'    => false,
                'default'     => '',
                'description' => __( 'Select a main product category. Subcategories will be used as tabs.', 'wr-elementor-widgets' ),
            ]
        );

        $this->add_control(
            'products_per_tab',
            [
                'label'   => __( 'Products Per Tab', 'wr-elementor-widgets' ),
                'type'    => \Elementor\Controls_Manager::NUMBER,
                'min'     => 1,
                'max'     => 48,
                'step'    => 1,
                'default' => 6,
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings         = $this->get_settings_for_display();
        $parent_cat_id    = ! empty( $settings['parent_category'] ) ? (int) $settings['parent_category'] : 0;
        $products_per_tab = ! empty( $settings['products_per_tab'] ) ? (int) $settings['products_per_tab'] : 6;
        $widget_id        = $this->get_id();

        if ( ! $parent_cat_id ) {
            if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
                echo '<div class="wr-tab-product-grid-notice">';
                esc_html_e( 'Please select a parent product category.', 'wr-elementor-widgets' );
                echo '</div>';
            }
            return;
        }

        // Alt kategoriler
        $subcategories = get_terms([
            'taxonomy'   => 'product_cat',
            'hide_empty' => true,
            'parent'     => $parent_cat_id,
            'orderby'    => 'name',
            'order'      => 'ASC',
        ]);

        if ( is_wp_error( $subcategories ) || empty( $subcategories ) ) {
            if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
                echo '<div class="wr-tab-product-grid-notice">';
                esc_html_e( 'No subcategories with products found for this parent category.', 'wr-elementor-widgets' );
                echo '</div>';
            }
            return;
        }

        // İlk aktif tab
        $first_valid_subcat = null;
        foreach ( $subcategories as $subcat ) {
            if ( function_exists( 'wr_tpg_term_has_products' ) && wr_tpg_term_has_products( $subcat->term_id ) ) {
                $first_valid_subcat = $subcat;
                break;
            }
        }

        if ( ! $first_valid_subcat ) {
            if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
                echo '<div class="wr-tab-product-grid-notice">';
                esc_html_e( 'No subcategories with products found for this parent category.', 'wr-elementor-widgets' );
                echo '</div>';
            }
            return;
        }

        // İlk tab için ürünler (page = 1)
        $initial_result = wr_tpg_fetch_products(
            $parent_cat_id,
            $first_valid_subcat->slug,
            $products_per_tab,
            1
        );

        $initial_html     = $initial_result['html'];
        $initial_has_more = $initial_result['has_more'] ? '1' : '0';
        ?>

        <div class="wr-tab-product-grid-wrapper"
             data-widget-id="<?php echo esc_attr( $widget_id ); ?>"
             data-parent-cat="<?php echo esc_attr( $parent_cat_id ); ?>"
             data-count="<?php echo esc_attr( $products_per_tab ); ?>"
             data-initial-subcat="<?php echo esc_attr( $first_valid_subcat->slug ); ?>"
             data-has-more="<?php echo esc_attr( $initial_has_more ); ?>">

            <!-- TABS -->
            <div class="wr-tpg-tabs">
                <?php foreach ( $subcategories as $subcat ) : ?>
                    <?php
                    if ( function_exists( 'wr_tpg_term_has_products' ) && ! wr_tpg_term_has_products( $subcat->term_id ) ) {
                        continue;
                    }
                    $is_active = ( $subcat->term_id === $first_valid_subcat->term_id );
                    ?>
                    <button
                        type="button"
                        class="wr-tpg-tab<?php echo $is_active ? ' is-active' : ''; ?>"
                        data-subcat="<?php echo esc_attr( $subcat->slug ); ?>">
                        <span class="wr-tpg-tab-label">
                            <?php echo esc_html( $subcat->name ); ?>
                        </span>
                    </button>
                <?php endforeach; ?>
            </div>

            <!-- GRID WRAPPER -->
            <div class="wr-tpg-grid-wrapper">
                <div class="wr-tpg-loading-overlay">
                    <div class="wr-tpg-spinner"></div>
                </div>

                <div class="wr-tpg-grid" id="wr-tpg-grid-<?php echo esc_attr( $widget_id ); ?>">
                    <?php echo $initial_html; ?>
                </div>
            </div>

            <!-- LOAD MORE -->
            <div class="wr-tpg-loadmore-wrapper">
                <button type="button" class="wr-tpg-loadmore-btn">
                    <?php esc_html_e( 'Load more', 'wr-elementor-widgets' ); ?>
                </button>
            </div>

        </div>

        <?php
    }
}
