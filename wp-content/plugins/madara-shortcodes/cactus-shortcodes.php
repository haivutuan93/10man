<?php

	/*
	Plugin Name: Madara - Shortcodes
	Description: Enable shortcodes for Madara theme
	Plugin URI: http://www.mangabooth.com/
	Version: 1.3
	Author: MangaBooth
	Author URI: https://themeforest.net/user/wpstylish
	License: Commercial
	*/

	/**
	 * @package Madara
	 * @version 1.0
	 */

	if ( ! class_exists( 'MadaraPlugin' ) ) {
		require 'inc/core/MadaraPlugin.php';
	}

	if ( ! defined( 'CT_SHORTCODE_BASE_FILE' ) ) {
		define( 'CT_SHORTCODE_BASE_FILE', __FILE__ );
	}
	if ( ! defined( 'CT_SHORTCODE_BASE_DIR' ) ) {
		define( 'CT_SHORTCODE_BASE_DIR', dirname( CT_SHORTCODE_BASE_FILE ) );
	}
	if ( ! defined( 'CT_SHORTCODE_PLUGIN_URL' ) ) {
		define( 'CT_SHORTCODE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
	}
	if ( ! defined( 'CT_SHORTCODE_VERSION' ) ) {
		define( 'CT_SHORTCODE_VERSION', '1.0.0' );
	}
	if ( ! function_exists( 'sc_get_plugin_url' ) ) {
		function sc_get_plugin_url() {
			return plugin_dir_path( __FILE__ );
		}
	}

	include sc_get_plugin_url() . 'configuration.php';

	include sc_get_plugin_url() . 'shortcodes/class.shortcode.base.php';

	global $madara_shortcodes;

	foreach ( $madara_shortcodes as $name => $params ) {
		if ( isset( $params['path'] ) ) {
			include $params['path'];
		}
	}

	include CT_SHORTCODE_BASE_DIR . '/inc/helpers.php';

	include CT_SHORTCODE_BASE_DIR . '/inc/plugin-filters.php';
	/**
	 * core file. do not change
	 */
	include CT_SHORTCODE_BASE_DIR . '/inc/classes/ct-shortcodes.php';

	/**
	 * Plugin class
	 */
	class CT_Plugin_Shortcode extends MadaraPlugin {
		private static $instance;

		public static function getInstance() {
			if ( null == self::$instance ) {
				self::$instance = new CT_Plugin_Shortcode();
			}

			return self::$instance;
		}

		private function __construct() {
			// Variables
			$this->template_url = apply_filters( 'madara_shortcodes_template_url', 'ct-shortcodes/' );

			add_action( 'init', array( $this, 'init' ), 0 );
		}

		/**
		 * Get the plugin path. This function must be defined here
		 *
		 * @access public
		 * @return string
		 */
		public function plugin_path() {
			if ( $this->plugin_path ) {
				return $this->plugin_path;
			}

			return $this->plugin_path = untrailingslashit( plugin_dir_path( __FILE__ ) );
		}

		public function init() {
			load_plugin_textdomain( 'madara', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
		}
	}