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
        $this->start_controls_section('content_section', [
            'label' => __('Content', 'wr-ecom'),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('posts_per_page', [
            'label' => __('Products Count', 'wr-ecom'),
            'type'  => \Elementor\Controls_Manager::NUMBER,
            'default' => 10,
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $args = [
            'post_type'      => 'product',
            'posts_per_page' => $settings['posts_per_page'],
            'post_status'    => 'publish',
        ];

        $loop = new WP_Query($args);
        ?>

        <div class="wr-product-carousel swiper">
            <div class="swiper-wrapper">

                <?php if ($loop->have_posts()) : ?>
                    <?php while ($loop->have_posts()) : $loop->the_post(); global $product; ?>

                        <div class="swiper-slide wr-product-card">
                            <a href="<?php the_permalink(); ?>" class="wr-product-card-inner">

                                <div class="wr-product-image">
                                    <?php echo $product->get_image('medium'); ?>
                                </div>

                                <h3 class="wr-product-title"><?php the_title(); ?></h3>

                                <div class="wr-product-price">
                                    <?php echo $product->get_price_html(); ?>
                                </div>

                            </a>
                        </div>

                    <?php endwhile; wp_reset_postdata(); ?>
                <?php else : ?>

                    <div class="swiper-slide">
                        <p>No products found.</p>
                    </div>

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
                spaceBetween: 20,
                loop: true,
                navigation: {
                    nextEl: ".wr-carousel-next",
                    prevEl: ".wr-carousel-prev",
                },
                pagination: {
                    el: ".swiper-pagination",
                    clickable: true,
                },
            });
        });
        </script>

        <?php
    }
}
