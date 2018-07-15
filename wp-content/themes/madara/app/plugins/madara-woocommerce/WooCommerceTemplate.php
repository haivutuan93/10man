<?php
	/**
	 * WooCommerce Template Extension
	 *
	 * @package madara
	 */

	namespace App\Plugins\madara_WooCommerce;

	use \App\Madara;

	class WooCommerceTemplate {
		private static $instance;

		public static function getInstance() {
			if ( null == self::$instance ) {
				self::$instance = new WooCommerceTemplate();
			}

			return self::$instance;
		}

		public static $page_slug = 'madara-woocommerce-template';

		public function setup() {
			// Override theme default specification for product # per row
			add_filter( 'loop_shop_columns', array( $this, 'loop_columns' ), 999 );
			add_filter( 'woocommerce_product_thumbnails_columns', array( $this, 'product_thumbnails_columns' ) );
			add_filter( 'loop_shop_per_page', array( $this, 'posts_per_page' ), 20 );

		}


		/**
		 * Return posts per page
		 */
		function posts_per_page() {
			return \App\Madara::getOption( 'woocommerce_count', 10 );
		}

		/**
		 * Return number of items per row
		 */
		function loop_columns() {
			return \App\Madara::getOption( 'woocommerce_items_per_row', 4 );
		}

		function product_thumbnails_columns() {
			return 6;
		}
	}