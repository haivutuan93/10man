<?php
	/**
	 * WooCommerce extension
	 *
	 * @package cactus
	 */

	namespace App\Plugins\madara_WooCommerce;

	use \App\Madara;

	class WooCommerce {

		public static $page_slug = 'madara-woocommerce';

		private static $instance;

		public static function getInstance() {
			if ( null == self::$instance ) {
				self::$instance = new WooCommerce();
			}

			return self::$instance;
		}

		public static function initialize() {
			$woocommerce = WooCommerce::getInstance();

			add_action( 'widgets_init', array( $woocommerce, 'registerSidebar' ) );
			add_action( 'after_setup_theme', array( $woocommerce, 'add_woocommerce_support' ) );
			add_filter( 'woocommerce_page_title', array( $woocommerce, 'archives_title' ) );
			add_filter( 'option_tree_settings_args', array( $woocommerce, 'register_theme_options' ) );
			add_filter( 'woocommerce_breadcrumb_defaults', array( $woocommerce, 'woocommerce_breadcrumbs' ) );
			add_filter( 'woocommerce_sale_flash', array( $woocommerce, 'woocommerce_custom_sale_text' ) );
			add_filter( 'woocommerce_pagination_args', array( $woocommerce, 'woocommerce_custom_pagination' ) );
			add_action( 'wp_footer', array( $woocommerce, 'woocommerce_quantity_script' ) );
			$template = WooCommerceTemplate::getInstance();
			$template->setup();

		}


		/**
		 * Register WooCommerce sidebar
		 */
		public function registerSidebar() {
			register_sidebar( array(
				'name'          => esc_html__( 'WooCommerce Sidebar', 'madara' ),
				'id'            => 'woo_sidebar',
				'description'   => esc_html__( 'Used by all WooCommerce pages', 'madara' ),
				'before_widget' => '<div class="row"><div id="%1$s" class="widget %2$s"><div class="widget__inner %2$s__inner c-widget-wrap">',
				'after_widget'  => '</div></div></div>',
				'before_title'  => '<div class="widget-heading font-nav"><h5 class="heading">',
				'after_title'   => '</h5></div>',
			) );
		}

		/**
		 * Add Theme Options for WooCommerce
		 */
		public function register_theme_options( $custom_settings ) {
			$custom_settings['sections'][] = array(
				'id'    => 'woocommerce',
				'title' => '<i class="fas fa-shopping-cart"></i>' . esc_html__( 'WooCommerce', 'madara' )
			);

			$custom_settings['settings'][] = array(
				'id'      => 'woocommerce_breadcrumb_bg',
				'label'   => esc_html__( 'Breadcrumb Background', 'madara' ),
				'desc'    => esc_html__( 'Set default Header Background for all WooCommerce pages. The Shop page has its own settings to override this default header', 'madara' ),
				'std'     => '',
				'type'    => 'background',
				'section' => 'woocommerce',
			);

			$custom_settings['settings'][] = array(
				'id'      => 'woocommerce_archive_title',
				'label'   => esc_html__( 'Archives Page Title', 'madara' ),
				'desc'    => esc_html__( 'Page Title of WooCommerce All Products page', 'madara' ),
				'std'     => 'Shop',
				'type'    => 'text',
				'section' => 'woocommerce',
			);

			$custom_settings['settings'][] = array(
				'id'      => 'woocommerce_sidebar_position',
				'label'   => esc_html__( 'Shop Sidebar Position', 'madara' ),
				'desc'    => esc_html__( 'Choose Sidebar Position for Shop and Single Product page', 'madara' ),
				'std'     => 'right',
				'type'    => 'select',
				'section' => 'woocommerce',
				'choices' => array(
					array(
						'value' => 'left',
						'label' => esc_html__( 'Left', 'madara' )
					),
					array(
						'value' => 'right',
						'label' => esc_html__( 'Right', 'madara' )
					),
					array(
						'value' => 'full',
						'label' => esc_html__( 'Hidden', 'madara' )
					)
				)
			);

			$custom_settings['settings'][] = array(
				'id'      => 'woocommerce_count',
				'label'   => esc_html__( 'Posts Per Page', 'madara' ),
				'desc'    => esc_html__( 'Enter number of products per page in All Products page', 'madara' ),
				'std'     => 10,
				'type'    => 'text',
				'section' => 'woocommerce',
			);

			$custom_settings['settings'][] = array(
				'id'      => 'woocommerce_items_per_row',
				'label'   => esc_html__( 'Number of items per row', 'madara' ),
				'desc'    => esc_html__( 'Enter number of products per row in All Products page', 'madara' ),
				'std'     => 4,
				'type'    => 'select',
				'section' => 'woocommerce',
				'choices' => array(
					array(
						'value' => 2,
						'label' => esc_html__( '2 Items', 'madara' )
					),
					array(
						'value' => 3,
						'label' => esc_html__( '3 Items', 'madara' )
					),
					array(
						'value' => 4,
						'label' => esc_html__( '4 Items', 'madara' )
					),
					array(
						'value' => 5,
						'label' => esc_html__( '5 Items', 'madara' )
					),
					array(
						'value' => 6,
						'label' => esc_html__( '6 Items', 'madara' )
					)

				)
			);

			return $custom_settings;
		}

		public function add_woocommerce_support() {
			add_theme_support( 'woocommerce' );
			add_theme_support( 'wc-product-gallery-zoom' );
			add_theme_support( 'wc-product-gallery-lightbox' );
			add_theme_support( 'wc-product-gallery-slider' );
		}


		function woocommerce_breadcrumbs() {

			$woocommerce_breadcrumb_bg = madara_output_background_options( 'woocommerce_breadcrumb_bg', '' );
			if ( $woocommerce_breadcrumb_bg == '' ) {
				$woocommerce_breadcrumb_bg = 'background-image: url(' . esc_url( get_parent_theme_file_uri( "/images/bg-search.jpg" ) ) . ')';
			}

			return array(
				'delimiter'   => ' &#47; ',
				'wrap_before' => '<div class="c-breadcrumb-wrapper" style="' . $woocommerce_breadcrumb_bg . '"><div class="container"><div class="row"><div class="col-md-12"><ul class="c-woo-breadcrumb">',
				'wrap_after'  => '</ul></div></div></div></div>',
				'before'      => '<li>',
				'after'       => '</li>',
				'home'        => esc_html__( 'Home', 'madara' ),
			);

		}

		/**
		 * Change page title of Archives page
		 */
		public function archives_title( $page_title ) {
			if ( function_exists( 'is_shop' ) && is_shop() ) {
				return Madara::getOption( 'woocommerce_archive_title', 'Shop' );
			}

			return $page_title;
		}

		function woocommerce_custom_sale_text( $content ) {

			$content = '<span class="onsale">' . esc_html__( 'sale', 'madara' ) . '</span>';

			return $content;
		}


		function woocommerce_custom_pagination( $args ) {

			$args['prev_text'] = esc_html__( 'Prev', 'madara' );
			$args['next_text'] = esc_html__( 'Next', 'madara' );

			return $args;
		}

		function woocommerce_quantity_script() {
			if ( ! is_admin() ) { ?>
                <script>
					jQuery(document).ready(function ($) {
						$('.woocommerce .quantity').on('click', '.minus', function (e) {
							var $inputQty = $(this).parent().find('input.qty');
							var val = parseInt($inputQty.val());
							var step = $inputQty.attr('step');
							step = 'undefined' !== typeof(step) ? parseInt(step) : 1;
							if (val > 0) {
								$inputQty.val(val - step).change();
							}
						});
						$('.woocommerce .quantity').on('click', '.plus', function (e) {
							var $inputQty = $(this).parent().find('input.qty');
							var val = parseInt($inputQty.val());
							var step = $inputQty.attr('step');
							step = 'undefined' !== typeof(step) ? parseInt(step) : 1;
							$inputQty.val(val + step).change();
						});
					});
                </script>
			<?php }
		}

	}