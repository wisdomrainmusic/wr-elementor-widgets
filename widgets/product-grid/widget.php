<?php
if ( ! defined( 'ABSPATH' ) ) exit;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;

if ( ! function_exists( 'wr_pg_build_pagination_html' ) ) {
	function wr_pg_build_pagination_html( $current, $total ) {
		$current = max( 1, (int) $current );
		$total   = max( 1, (int) $total );

		if ( $total <= 1 ) return '';

		$html = '';

		if ( $current > 1 ) {
			$html .= '<a href="#" class="page-numbers prev" data-page="' . ( $current - 1 ) . '">&lt;</a>';
		}

		for ( $i = 1; $i <= $total; $i++ ) {
			if ( $i === $current ) {
				$html .= '<span class="page-numbers current" aria-current="page">' . $i . '</span>';
			} else {
				$html .= '<a href="#" class="page-numbers" data-page="' . $i . '">' . $i . '</a>';
			}
		}

		if ( $current < $total ) {
			$html .= '<a href="#" class="page-numbers next" data-page="' . ( $current + 1 ) . '">&gt;</a>';
		}

		return $html;
	}
}

class WR_EW_Product_Grid extends Widget_Base {

	public function get_name() { return 'wr-product-grid'; }
	public function get_title() { return 'WR Product Grid'; }
	public function get_icon() { return 'eicon-products'; }
	public function get_categories() { return [ 'wr-widgets', 'wr-ecommerce-elements' ]; }

	public function get_style_depends() { return [ 'wr-product-grid-css' ]; }
	public function get_script_depends() { return [ 'wr-grid-js' ]; }

	protected function register_controls() {

		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'wr-ew' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'columns',
			[
				'label'   => __( 'Columns', 'wr-ew' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'2' => __( '2', 'wr-ew' ),
					'3' => __( '3', 'wr-ew' ),
					'4' => __( '4', 'wr-ew' ),
				],
				'default' => '3',
			]
		);

		$this->add_control(
			'per_page',
			[
				'label'   => __( 'Products Per Page', 'wr-ew' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => 4,
				'max'     => 60,
				'step'    => 1,
				'default' => 12,
			]
		);

		$this->end_controls_section();
	}

	protected function render() {

		$s        = $this->get_settings_for_display();
		$columns  = ! empty( $s['columns'] ) ? (string) $s['columns'] : '3';
		$per_page = ! empty( $s['per_page'] ) ? max( 1, absint( $s['per_page'] ) ) : 12;

                $nonce = wp_create_nonce( 'wr_pg_nonce' );

		// JS config (widget bazlı garanti)
		$inline = 'window.wrGridData = window.wrGridData || {};'
		        . 'window.wrGridData.ajax_url = ' . wp_json_encode( admin_url( 'admin-ajax.php' ) ) . ';'
                        . 'window.wrGridData.nonce = ' . wp_json_encode( $nonce ) . ';'
                        . 'window.wrGridData.debug = ' . ( defined( 'WP_DEBUG' ) && WP_DEBUG ? 'true' : 'false' ) . ';';
		wp_add_inline_script( 'wr-grid-js', $inline, 'before' );

		$categories = get_terms( [
			'taxonomy'   => 'product_cat',
			'hide_empty' => true,
			'orderby'    => 'name',
			'order'      => 'ASC',
		] );

		$paged = 1;

		$q = new WP_Query( [
			'post_type'      => 'product',
			'posts_per_page' => $per_page,
			'paged'          => $paged,
		] );

		$total_pages = max( 1, (int) $q->max_num_pages );

		echo '<div class="wr-product-grid-wrapper" data-columns="' . esc_attr( $columns ) . '" data-per-page="' . esc_attr( $per_page ) . '" data-nonce="' . esc_attr( $nonce ) . '">';

		// Sidebar
		echo '<aside class="wr-filter-sidebar" aria-label="Product categories">';
		echo '<div class="wr-filter-header" role="button" tabindex="0">' . esc_html__( 'Categories', 'wr-ew' ) . '</div>';

		echo '<div class="wr-cat-list-wrap">';
		echo '<ul class="wr-cat-list">';

		if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
			foreach ( $categories as $cat ) {
				echo '<li class="wr-cat-item" data-cat="' . esc_attr( $cat->term_id ) . '">' . esc_html( $cat->name ) . '</li>';
			}
		} else {
			echo '<li class="wr-cat-empty">' . esc_html__( 'No categories found.', 'wr-ew' ) . '</li>';
		}

		echo '</ul></div>';
		echo '</aside>';

		// Grid
		echo '<div class="wr-ajax-grid" data-widget="product-grid" data-columns="' . esc_attr( $columns ) . '" data-per-page="' . esc_attr( $per_page ) . '" data-nonce="' . esc_attr( $nonce ) . '">';

		echo '<div class="wr-product-items">';
		if ( $q->have_posts() ) {
			while ( $q->have_posts() ) {
				$q->the_post();
				include WR_EW_PLUGIN_DIR . 'widgets/product-grid/card.php';
			}
		} else {
			echo '<p class="wr-no-products">' . esc_html__( 'No products found.', 'wr-ew' ) . '</p>';
		}
		wp_reset_postdata();
		echo '</div>';

		echo '<div class="wr-pagination">';
		echo wr_pg_build_pagination_html( $paged, $total_pages );
		echo '</div>';

		echo '</div>'; // .wr-ajax-grid
		echo '</div>'; // .wr-product-grid-wrapper
	}
}

/**
 * AJAX
 * - tek action
 * - JSON response
 * - nonce hatasında 400 -> debug kolay
 */
if ( ! function_exists( 'wr_pg_filter_products' ) ) {
        function wr_pg_filter_products() {

                if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
                        error_log( 'WR_PG AJAX POST: ' . wp_json_encode( $_POST ) );
                }

                if ( ! check_ajax_referer( 'wr_pg_nonce', 'nonce', false ) ) {
                        wp_send_json_error( [ 'message' => 'Invalid nonce' ], 400 );
                }

                if ( ! isset( $_POST['cat'], $_POST['page'], $_POST['per_page'] ) ) {
                        wp_send_json_error( [ 'message' => 'Missing parameters' ], 400 );
                }

                $cat      = absint( wp_unslash( $_POST['cat'] ) );
                $page     = max( 1, absint( wp_unslash( $_POST['page'] ) ) );
                $per_page = min( 60, max( 1, absint( wp_unslash( $_POST['per_page'] ) ) ) );

                $args = [
                        'post_type'      => 'product',
                        'posts_per_page' => $per_page,
			'paged'          => $page,
		];

		if ( $cat ) {
			$args['tax_query'] = [
				[
					'taxonomy' => 'product_cat',
					'field'    => 'term_id',
					'terms'    => $cat,
				],
			];
		}

		$q = new WP_Query( $args );

		ob_start();
		if ( $q->have_posts() ) {
			while ( $q->have_posts() ) {
				$q->the_post();
				include WR_EW_PLUGIN_DIR . 'widgets/product-grid/card.php';
			}
		} else {
			echo '<p class="wr-no-products">' . esc_html__( 'No products found.', 'wr-ew' ) . '</p>';
		}
		wp_reset_postdata();
		$items_html = ob_get_clean();

		$total_pages = max( 1, (int) $q->max_num_pages );

		wp_send_json_success( [
			'items_html'      => $items_html,
			'pagination_html' => wr_pg_build_pagination_html( $page, $total_pages ),
			'page'            => $page,
			'max'             => $total_pages,
			'cat'             => $cat,
			'per_page'        => $per_page,
		] );
	}
}

if ( ! has_action( 'wp_ajax_wr_pg_filter_products', 'wr_pg_filter_products' ) ) {
	add_action( 'wp_ajax_wr_pg_filter_products', 'wr_pg_filter_products' );
	add_action( 'wp_ajax_nopriv_wr_pg_filter_products', 'wr_pg_filter_products' );
}
