<?php

	/**
	 * Number Helper
	 */

	namespace App\Helpers;

	class Number {
		public function __construct() {

		}

		public static function get_formatted_string_number( $n, $decimals = 2, $suffix = '' ) {
			if ( ! $suffix ) {
				$suffix = 'K,M,B';
			}
			$suffix = explode( ',', $suffix );

			if ( $n < 1000 ) { // any number less than a Thousand
				$shorted = number_format( $n );
			} elseif ( $n < 1000000 ) { // any number less than a million
				$shorted = number_format( $n / 1000, $decimals ) . $suffix[0];
			} elseif ( $n < 1000000000 ) { // any number less than a billion
				$shorted = number_format( $n / 1000000, $decimals ) . $suffix[1];
			} else { // at least a billion
				$shorted = number_format( $n / 1000000000, $decimals ) . $suffix[2];
			}

			return $shorted;
		}

		/**
		 * This function transforms the php.ini notation for numbers (like '2M') to an integer.
		 *
		 * @static
		 * @access public
		 * @since 3.8.0
		 *
		 * @param string $size The size.
		 *
		 * @return int
		 */
		public static function let_to_num( $size ) {
			$l   = substr( $size, - 1 );
			$ret = substr( $size, 0, - 1 );
			switch ( strtoupper( $l ) ) {
				case 'P':
					$ret *= 1024;
				case 'T':
					$ret *= 1024;
				case 'G':
					$ret *= 1024;
				case 'M':
					$ret *= 1024;
				case 'K':
					$ret *= 1024;
			}

			return $ret;
		}
	}