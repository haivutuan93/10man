<?php

	namespace App\Config;

	// Prevent direct access to this file
	defined( 'ABSPATH' ) || die( 'Direct access to this file is not allowed.' );

	class ThemeConfig {
		/**
		 * return array of required plugins, used with TGM Plugin Activation
		 */
		public static function getRequiredPlugins() {
			if ( file_exists( get_parent_theme_file_path( '/app/config-required-plugins.php' ) ) ) {
				include( get_template_directory() . '/app/config-required-plugins.php' );

				return apply_filters( 'madara_required_plugins', $madara_required_plugins );
			}

			return array();
		}

		/* Return all thumb sizes available in theme */
		public static function getAllThumbSizes() {
			if ( file_exists( get_parent_theme_file_path( '/app/config-image-sizes.php' ) ) ) {
				include( get_template_directory() . '/app/config-image-sizes.php' );

				return apply_filters( 'madara_thumbnail_sizes', $madara_image_sizes );
			}

			return array();
		}

		/* Return actual size of thumb used when a preferred size is requested
		 *
		 * @preferred_size - array - array(width, height)
		 *
		 * @return - string/array - name of thumb size or return itself if not found any mapping
		 *
		 */
		public static function thumbSizeMapping( $preferred_size ) {
			if ( file_exists( get_parent_theme_file_path( '/app/config-image-sizes.php' ) ) ) {
				include( get_template_directory() . '/app/config-image-sizes.php' );

				$mapping = apply_filters( 'madara_thumbnail_sizes', $madara_image_size_mapping );

				if ( isset( $mapping[ $preferred_size[0] . 'x' . $preferred_size[1] ] ) ) {
					return $mapping[ $preferred_size[0] . 'x' . $preferred_size[1] ];
				} else {
					return $preferred_size;
				}
			}

			return $preferred_size;
		}

		/* Return list of thumb sizes which is turned on in Theme Options */
		public function getConfiguredThumbSizes() {
			$thumb_sizes = ThemeConfig::getAllThumbSizes();

			$availabe_sizes = array();

			if ( function_exists( 'ot_get_option' ) ) {
				foreach ( $thumb_sizes as $size => $config ) {

					if ( ot_get_option( $size, 'on' ) == 'on' ) {
						// return only size that is turned on in Theme Options
						$availabe_sizes = array_merge( $availabe_sizes, array( $size => $config ) );
					}
				}
			} else {
				// get all sizes
				$availabe_sizes = $thumb_sizes;
			}

			$vals = apply_filters( 'madara_thumb_config', $availabe_sizes );

			return $vals;
		}

		public function __construct() {
			add_action( 'madara_reg_thumbnail', array( $this, 'register_thumbsizes' ), 1, 1 );
		}

		/**
		 * Register Image Sizes
		 *
		 * @param $size_array - array - Array of sizes
		 */
		public function register_thumbsizes( $size_array = array() ) {

			if ( ! is_array( $size_array ) || ! count( $size_array ) ) {
				$size_array = $this->getConfiguredThumbSizes();
			}

			foreach ( $size_array as $size => $att ) {
				add_image_size( $size, $att[0], $att[1], $att[2] );
			}
		}
	}