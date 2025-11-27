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

    protected function render() {
        $settings = $this->get_settings_for_display();

        // PRODUCT QUERY
        $args = [
            'post_type'      => 'product',
            'posts_per_page' => $settings['posts_per_page'],
            'post_status'    => 'publish',
        ];

        // Category filter
        if ( ! empty($settings['product_categories']) ) {
            $args['tax_query'] = [
                [
                    'taxonomy' => 'product_cat',
                    'field'    => 'slug',
                    'terms'    => $settings['product_categories'],
                ],
            ];
        }

        $loop = new WP_Query($args);
        ?>

        <div class="wr-product-carousel swiper">
            <div class="swiper-wrapper">

            <?php if ($loop->have_posts()) : ?>
                <?php while ($loop->have_posts()) : $loop->the_post(); global $product; ?>

                <div class="swiper-slide">
                    <div class="wr-carousel-card">

                        <div class="wr-carousel-image">
                            <?php echo $product->get_image('medium'); ?>
                        </div>

                        <h3 class="wr-carousel-title"><?php the_title(); ?></h3>

                        <div class="wr-carousel-price">
                            <?php echo $product->get_price_html(); ?>
                        </div>

                        <div class="wr-carousel-actions">

                            <!-- ADD TO CART -->
                            <a href="<?php echo esc_url( $product->add_to_cart_url() ); ?>"
                               class="button add_to_cart_button ajax_add_to_cart">
                               Add to cart
                            </a>

                            <!-- WISHLIST -->
                            <div class="wr-carousel-wishlist wr-wishlist-btn"
                                 data-product-id="<?php echo get_the_ID(); ?>">

                                <svg viewBox="0 0 24 24">
                                    <path d="M12 21s-6.5-4.35-9-8.5C1.03 9.02 2.1 5.6 4.9 4.4c1.8-.8 4-.3 5.1 1C11.1 4.1 13.3 3.6 15.1 4.4c2.8 1.2 3.87 4.62 1.9 8.1C18.5 16.65 12 21 12 21z"
                                          stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>

                            </div>

                        </div>

                    </div>
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
