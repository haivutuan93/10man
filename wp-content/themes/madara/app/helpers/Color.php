<?php

	/**
	 * Color Helper
	 */

	namespace App\Helpers;

	class Color {
		public function __construct() {

		}

		/**
		 * Convert HEX to RGB
		 *
		 * @param $hex
		 *
		 * @return string
		 *
		 * @since Madara Alpha 1.0
		 * @package madara
		 */
		function madara_hex2rgb( $hex ) {
			$hex = str_replace( "#", "", $hex );

			if ( strlen( $hex ) == 3 ) {
				$r = hexdec( substr( $hex, 0, 1 ) . substr( $hex, 0, 1 ) );
				$g = hexdec( substr( $hex, 1, 1 ) . substr( $hex, 1, 1 ) );
				$b = hexdec( substr( $hex, 2, 1 ) . substr( $hex, 2, 1 ) );
			} else {
				$r = hexdec( substr( $hex, 0, 2 ) );
				$g = hexdec( substr( $hex, 2, 2 ) );
				$b = hexdec( substr( $hex, 4, 2 ) );
			}
			$rgb = array( $r, $g, $b );

			return $rgb; //Returns an array with the rgb values
		}

		/**
		 * Convert RGB to HEX
		 *
		 * @param $rgb
		 *
		 * @return string
		 *
		 * @since Madara Alpha 1.0
		 * @package madara
		 */
		function rgb2hexa( $rgb ) {
			if ( count( $rgb ) == 3 ) {
				if ( $rgb[0] < 10 ) {
					$hex1 = '0' . $rgb[0];
				} else {
					$hex1 = dechex( $rgb[0] );
				}
				if ( $rgb[1] < 10 ) {
					$hex2 = '0' . $rgb[1];
				} else {
					$hex2 = dechex( $rgb[1] );
				}
				if ( $rgb[2] < 10 ) {
					$hex3 = '0' . $rgb[2];
				} else {
					$hex3 = dechex( $rgb[2] );
				}

				return $hex1 . $hex2 . $hex3;
			}

			return '000';
		}

		/**
		 * Get Gradient Color
		 *
		 * @param $basic_hexa
		 * @param $step_rgb
		 *
		 * @return mixed
		 *
		 * @since Madara Alpha 1.0
		 * @package madara
		 */
		function colorGradientGenerator( $basic_hexa, $step_rgb ) {
			$basic_rbg = $this->madara_hex2rgb( $basic_hexa );

			$r = $basic_rbg[0] - $step_rgb[0];

			if ( $r < 0 ) {
				$r = 0;
			}

			$g = $basic_rbg[1] - $step_rgb[1];
			if ( $g < 0 ) {
				$g = 0;
			}

			$b = $basic_rbg[2] - $step_rgb[2];
			if ( $b < 0 ) {
				$b = 0;
			}

			return $this->rgb2hexa( array( $r, $g, $b ) );
		}

		/* Add opacity to a Hexa color */
		public static function madara_hex2rgba( $hex, $opacity ) {

			$hex = str_replace( "#", "", $hex );

			if ( strlen( $hex ) == 3 ) {
				$r = hexdec( substr( $hex, 0, 1 ) . substr( $hex, 0, 1 ) );
				$g = hexdec( substr( $hex, 1, 1 ) . substr( $hex, 1, 1 ) );
				$b = hexdec( substr( $hex, 2, 1 ) . substr( $hex, 2, 1 ) );
			} else {
				$r = hexdec( substr( $hex, 0, 2 ) );
				$g = hexdec( substr( $hex, 2, 2 ) );
				$b = hexdec( substr( $hex, 4, 2 ) );
			}

			$opacity = $opacity / 100;

			$rgba = array( $r, $g, $b, $opacity );

			return implode( ",", $rgba ); // returns the rgb values separated by commas
		}

		public static function madara_adjust_Brightness( $hex, $steps ) {
			// Steps should be between -255 and 255. Negative = darker, positive = lighter
			$steps = max( - 255, min( 255, $steps ) );

			// Normalize into a six character long hex string
			$hex = str_replace( '#', '', $hex );
			if ( strlen( $hex ) == 3 ) {
				$hex = str_repeat( substr( $hex, 0, 1 ), 2 ) . str_repeat( substr( $hex, 1, 1 ), 2 ) . str_repeat( substr( $hex, 2, 1 ), 2 );
			}

			// Split into three parts: R, G and B
			$color_parts = str_split( $hex, 2 );
			$return      = '#';

			foreach ( $color_parts as $color ) {
				$color  = hexdec( $color ); // Convert to decimal
				$color  = max( 0, min( 255, $color + $steps ) ); // Adjust color
				$return .= str_pad( dechex( $color ), 2, '0', STR_PAD_LEFT ); // Make two char hex code
			}

			return $return;
		}

		public static function madara_background_gradient( $start = false, $percent = '20%', $end = false ) {
			$css_attr = '';
			if ( $start ) {
				if ( ! $end ) {
					$start_lighten = self::madara_adjust_Brightness( $start, '20' );
					$css_attr      .= '
						  background: -webkit-linear-gradient(left, ' . $start . ' ' . $percent . ',' . $start_lighten . ');
						  background: -o-linear-gradient(right, ' . $start . ' ' . $percent . ',' . $start_lighten . ');
						  background: -moz-linear-gradient(right, ' . $start . ' ' . $percent . ',' . $start_lighten . ');
						  background: linear-gradient(to right, ' . $start . ' ' . $percent . ',' . $start_lighten . ');
					';
				} else {
					$css_attr .= '
						  background: -webkit-linear-gradient(left, ' . $start . ' ' . $percent . ',' . $end . ');
						  background: -o-linear-gradient(right, ' . $start . ' ' . $percent . ',' . $end . ');
						  background: -moz-linear-gradient(right, ' . $start . ' ' . $percent . ',' . $end . ');
						  background: linear-gradient(to right, ' . $start . ' ' . $percent . ',' . $end . ');
					';
				}

			}

			return $css_attr;
		}

	}