<?php

	/**
	 * Starter Content for the theme
	 *
	 * @package madara
	 * @since WordPress 4.7
	 */

	namespace App\Plugins\madara_Starter_Content;

	class StarterContent {
		public static function initialize() {
			add_action( 'after_setup_theme', array( __CLASS__, 'register_starter_content' ) );
			add_filter( 'get_theme_starter_content', array( __CLASS__, 'get_starter_content' ) );
		}

		/**
		 * Build starter content. See example at https://core.trac.wordpress.org/browser/trunk/src/wp-content/themes/twentyseventeen/functions.php?rev=39373#L106
		 * Core provided starter content is defined here: https://core.trac.wordpress.org/browser/trunk/src/wp-includes/theme.php?rev=39373#L1910
		 * Use filter get_theme_starter_content to change it.
		 */
		public static function register_starter_content() {
			add_theme_support( 'starter-content', array(
				'widgets' => array(
					'main_sidebar' => array(
						'meta_custom' => array(
							'meta',
							array(
								'title' => 'Sample Widget Title',
							)
						),
					),
				),

			) );
		}

		/**
		 * Filter core-provided starter content here
		 */
		public static function get_starter_content( $content, $config ) {
			return $content;
		}
	}