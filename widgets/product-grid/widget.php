<?php
if ( ! defined( 'ABSPATH' ) ) exit;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;

class WR_EW_Product_Grid extends Widget_Base {

    public function get_name() {
        return 'wr-product-grid';
    }

    public function get_title() {
        return 'WR Product Grid';
    }

    public function get_icon() {
        return 'eicon-products';
    }

    public function get_categories() {
        return [ 'wr-widgets' ];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __( 'Content', 'wr-elementor-widgets' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'columns',
            [
                'label'   => __( 'Columns', 'wr-elementor-widgets' ),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    '2' => __( '2', 'wr-elementor-widgets' ),
                    '3' => __( '3', 'wr-elementor-widgets' ),
                    '4' => __( '4', 'wr-elementor-widgets' ),
                ],
                'default' => '3',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings   = $this->get_settings_for_display();
        $columns    = ! empty( $settings['columns'] ) ? $settings['columns'] : '3';

        $categories = get_terms( [
            'taxonomy'   => 'product_cat',
            'hide_empty' => false,
        ] );

        echo '<div class="wr-product-grid-wrapper" data-columns="' . esc_attr( $columns ) . '">';

        // Sidebar
        echo '<aside class="wr-filter-sidebar">';
        echo '<div class="wr-filter-header">' . esc_html__( 'Categories', 'wr-elementor-widgets' ) . '</div>';
        echo '<ul>';
        if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
            foreach ( $categories as $cat ) {
                echo '<li data-cat="' . esc_attr( $cat->term_id ) . '">' . esc_html( $cat->name ) . '</li>';
            }
        }
        echo '</ul>';
        echo '</aside>';

        ?>
        <div id="wr-ajax-grid"
             data-widget="product-grid"
             data-columns="<?php echo esc_attr( $columns ); ?>">

            <div class="wr-product-items">
                <?php
                $paged = max( 1, absint( get_query_var( 'paged' ) ), absint( get_query_var( 'page' ) ) );
                $args  = [
                    'post_type'      => 'product',
                    'posts_per_page' => 12,
                    'paged'          => $paged,
                ];
                $query = new WP_Query( $args );

                if ( $query->have_posts() ) {
                    while ( $query->have_posts() ) {
                        $query->the_post();
                        wc_get_template( 'content-product.php', [], '', WR_EW_PLUGIN_DIR . 'templates/' );
                    }
                }
                wp_reset_postdata();
                ?>
            </div>

            <div class="wr-pagination">
                <?php
                    echo paginate_links([
                        'total'     => $query->max_num_pages,
                        'current'   => $paged,
                        'format'    => '#',
                        'type'      => 'plain',
                        'prev_text' => '&lt;',
                        'next_text' => '&gt;',
                    ]);
                ?>
            </div>

        </div>
        <?php
        echo '</div>'; // .wr-product-grid-wrapper
    }
}
