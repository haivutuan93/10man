<?php

	/**
	 * String Helper
	 */

	namespace App\Helpers;

	class Common {
		public function __construct() {

		}

		/**
		 * Get background CSS from a background object (array)
		 *
		 * @param array - ( [background-color] => [background-repeat] => [background-attachment] => [background-position] => [background-size] => [background-image] => )
		 *
		 * @return string - CSS
		 */
		public static function getBackgroundCSS( $background = array() ) {
			$css = '';

			$props = array(
				'background-color',
				'background-repeat',
				'background-attachment',
				'background-position',
				'background-size',
				'background-image'
			);

			foreach ( $props as $prop ) {
				if ( isset( $background[ $prop ] ) && $background[ $prop ] != '' ) {
					if ( $prop != 'background-image' ) {
						$css .= $prop . ': ' . $background[ $prop ] . ';';
					} else {
						$css .= $prop . ': url(' . $background[ $prop ] . ');';
					}
				}
			}

			return $css;
		}

		public static function isStartWith( $haystack, $needle ) {
			return ! strncmp( $haystack, $needle, strlen( $needle ) );
		}

		/**
		 * @param - $spacing - array of top, right, bottom, left, unit values
		 */
		public static function get_spacing_css( $spacing ) {
			if ( is_array( $spacing ) && count( $spacing ) == 5 ) {
				return $spacing['top'] . $spacing['unit'] . ' ' . $spacing['right'] . $spacing['unit'] . ' ' . $spacing['bottom'] . $spacing['unit'] . ' ' . $spacing['left'] . $spacing['unit'];
			}

			return '';
		}
	}