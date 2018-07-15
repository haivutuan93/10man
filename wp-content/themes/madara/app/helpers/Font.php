<?php

	/**
	 * Font Helper
	 */

	namespace App\Helpers;

	class Font {
		public function __contstruct() {

		}

		/**
		 * Get Google Font name from a full family_name
		 *
		 * @param $family_name
		 *
		 * @return mixed|string
		 */
		function getGoogleFontName( $family_name ) {
			$name = $family_name;

			if ( startsWith( $family_name, 'http' ) ) {
				$idx = strpos( $name, '=' );
				if ( $idx > - 1 ) {
					$name = substr( $name, $idx );
				}
			}

			$idx = strpos( $name, ':' );

			if ( $idx > - 1 ) {
				$name = substr( $name, 0, $idx );
				$name = str_replace( '+', ' ', $name );
			}

			return $name;
		}
	}