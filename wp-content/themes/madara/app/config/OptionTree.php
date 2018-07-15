<?php

	namespace App\Config;

	class OptionTree {
		/**
		 * Setup the Theme Options
		 */
		public static function setup() {
			/**
			 * Initialize the Madara Custom Theme Options.
			 *
			 * make sure Option Tree plugin is installed
			 */
			if ( class_exists( 'OT_Loader' ) ) {
				add_action( 'admin_init', array( __CLASS__, 'loadSettings' ) );

				/**
				 * Optional: set 'ot_show_pages' filter to false.
				 * This will hide the settings & documentation pages.
				 */
				add_filter( 'ot_show_pages', '__return_true' );

				/**
				 * Optional: set 'ot_show_new_layout' filter to false.
				 * This will hide the "New Layout" section on the Theme Options page.
				 */
				add_filter( 'ot_show_new_layout', '__return_false' );
			}
		}

		/**
		 * Get option from Theme Options. Currently it uses Option Tree plugin for Theme Options
		 *
		 * @param $option string - Option name
		 * @param $default_value - Default value if not set
		 *
		 * @return mixed - Value of option
		 */
		public static function getOption( $option = '', $default_value = '' ) {
			if ( function_exists( 'ot_get_option' ) ) {
				return ot_get_option( $option, $default_value );
			}

			return $default_value;
		}

		/**
		 * Build the custom settings & update OptionTree.
		 *
		 * @return    void
		 * @since     1.0
		 */
		public static function loadSettings() {
			if ( ! function_exists( 'ot_settings_id' ) ) {
				return;
			}

			/**
			 * Get a copy of the saved settings array.
			 */
			$saved_settings = get_option( ot_settings_id(), array() );

			// require core settings
			require( 'theme-options.php' );
			$custom_settings      = $madara_theme_options;
			$madara_theme_options = array(); // clear

			// require custom settings
			if ( file_exists( get_parent_theme_file_path( '/app/theme-options.php' ) ) ) {
				require( get_template_directory() . '/app/theme-options.php' );
				if ( isset( $madara_theme_options['settings'] ) && count( $madara_theme_options['settings'] ) > 0 ) {
					$custom_settings['settings'] = array_merge( $custom_settings['settings'], $madara_theme_options['settings'] );
				}

				if ( isset( $madara_theme_options['sections'] ) && count( $madara_theme_options['sections'] ) > 0 ) {
					$custom_settings['sections'] = array_merge( $custom_settings['sections'], $madara_theme_options['sections'] );
				}
			}

			/* Add settings panel for Thumb Sizes */
			$thumb_sizes = ThemeConfig::getAllThumbSizes();

			if ( is_array( $thumb_sizes ) ) {
				foreach ( $thumb_sizes as $size => $config ) {
					$custom_settings['settings'][] = array(
						'id'      => $size,
						'label'   => $config[3],
						'desc'    => $config[4],
						'std'     => 'on',
						'type'    => 'on-off',
						'section' => 'misc'
					);
				}
			}

			/* allow settings to be filtered before saving */
			$custom_settings = apply_filters( ot_settings_id() . '_args', $custom_settings );

			/* settings are not the same update the DB */
			if ( $saved_settings !== $custom_settings ) {
				update_option( ot_settings_id(), $custom_settings );
			}

		}
	}