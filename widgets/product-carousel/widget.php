<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class WR_Product_Carousel extends \Elementor\Widget_Base {

    public function get_name() {
        return 'wr-product-carousel';
    }

    public function get_title() {
        return __( 'WR Product Carousel', 'wr-ecom' );
    }

    public function get_icon() {
        return 'eicon-slider-album';
    }

    public function get_categories() {
        return [ 'wr-ecommerce-elements' ];
    }

    public function get_script_depends() {
        return [ 'wr-swiper' ];
    }

    protected function register_controls() {

        // CONTENT
        $this->start_controls_section('content_section', [
            'label' => __('Content', 'wr-ecom'),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);

        // Product Count
        $this->add_control('posts_per_page', [
            'label' => __('Products Count', 'wr-ecom'),
            'type'  => \Elementor\Controls_Manager::NUMBER,
            'default' => 10,
        ]);

        // Product Categories
        $this->add_control('product_categories', [
            'label' => __('Product Categories', 'wr-ecom'),
            'type'  => \Elementor\Controls_Manager::SELECT2,
            'multiple' => true,
            'options' => $this->get_product_categories(),
            'description' => __('Leave empty to show all products.', 'wr-ecom'),
        ]);

        // Product Tags
        $this->add_control('product_tags', [
            'label' => __('Product Tags', 'wr-ecom'),
            'type' => \Elementor\Controls_Manager::SELECT2,
            'multiple' => true,
            'options' => $this->get_product_tags(),
            'description' => __('Leave empty to ignore tag filtering.', 'wr-ecom'),
        ]);

        $this->end_controls_section();
    }

    private function get_product_categories() {
        $terms = get_terms([
            'taxonomy' => 'product_cat',
            'hide_empty' => false,
        ]);

        $options = [];
        if ($terms && !is_wp_error($terms)) {
            foreach ($terms as $term) {
                $options[$term->slug] = $term->name;
            }
        }
        return $options;
    }

    private function get_product_tags() {
        $terms = get_terms([
            'taxonomy' => 'product_tag',
            'hide_empty' => false,
        ]);

        $options = [];
        if ($terms && !is_wp_error($terms)) {
            foreach ($terms as $term) {
                $options[$term->slug] = $term->name;
            }
        }
        return $options;
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        // PRODUCT QUERY
        $args = [
            'post_type'      => 'product',
            'posts_per_page' => $settings['posts_per_page'],
            'post_status'    => 'publish',
        ];

        $tax_query = [];

        if ( ! empty($settings['product_categories']) ) {
            $tax_query[] = [
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => $settings['product_categories'],
            ];
        }

        if ( ! empty($settings['product_tags']) ) {
            $tax_query[] = [
                'taxonomy' => 'product_tag',
                'field'    => 'slug',
                'terms'    => $settings['product_tags'],
            ];
        }

        if ( ! empty($tax_query) ) {
            $args['tax_query'] = $tax_query;
        }

        $loop = new WP_Query($args);
        ?>

        <div class="wr-product-carousel swiper">
            <div class="swiper-wrapper">

            <?php if ( $loop->have_posts() ) : ?>
                <?php while ( $loop->have_posts() ) : $loop->the_post(); global $product; ?>

                <div class="swiper-slide">
                    <?php include WR_EW_PLUGIN_DIR . 'widgets/product-carousel/card.php'; ?>
                </div>

                <?php endwhile; wp_reset_postdata(); ?>
            <?php endif; ?>

            </div>

            <div class="wr-carousel-prev swiper-button-prev"></div>
            <div class="wr-carousel-next swiper-button-next"></div>
            <div class="swiper-pagination"></div>
        </div>

        <script>
        document.addEventListener("DOMContentLoaded", function() {
            new Swiper(".wr-product-carousel", {
                slidesPerView: 4,
                spaceBetween: 24,
                loop: true,
                navigation: {
                    nextEl: ".wr-carousel-next",
                    prevEl: ".wr-carousel-prev",
                },
                pagination: {
                    el: ".swiper-pagination",
                    clickable: true,
                },
                breakpoints: {
                    0: {
                        slidesPerView: 1,
                        spaceBetween: 16,
                    },
                    640: {
                        slidesPerView: 2,
                        spaceBetween: 20,
                    },
                    992: {
                        slidesPerView: 3,
                        spaceBetween: 24,
                    },
                    1200: {
                        slidesPerView: 4,
                        spaceBetween: 24,
                    }
                }
            });
        });
        </script>

        <?php
    }
}
