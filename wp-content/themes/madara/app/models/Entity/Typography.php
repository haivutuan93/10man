<?php

	/**
	 * Class Typography
	 *
	 * @package madara
	 */

	namespace App\Models\Entity;

	use App\Models;

	class Typography extends Models\Metadata {
		private $defaultFonts = array();

		public function __construct() {
			$this->defaultFonts = [
				'Crimson+Text',
				'Playfair+Display',
				'Montserrat:400,700'
			];
		}

		public function getMainFontFamily() {
			if ( $this->getOption( 'main_font_on_google', 'off' ) == 'on' ) {
				$fontFamily = $this->getOption( 'main_font_google_family', '' );

				if ( is_array( $fontFamily ) && $fontFamily[0]['family'] != '' ) {
					$fontFamily = $fontFamily[0]['family'];

					$fonts = get_theme_mod( 'ot_google_fonts' );
					if ( $fonts && isset( $fonts[ $fontFamily ] ) ) {
						$fontFamily = $fonts[ $fontFamily ]['family'];
					}
				} else {
					$fontFamily = '';
				}
			} else {
				$fontFamily = $this->getOption( 'main_font_family', '' );
			}

			if ( $fontFamily != '' ) {
				return $fontFamily;
			} else {
				return '';
			}
		}

		public function getHeadingFontFamily() {
			if ( $this->getOption( 'heading_font_on_google', 'off' ) == 'on' ) {
				$fontFamily = $this->getOption( 'heading_font_google_family', '' );

				if ( is_array( $fontFamily ) && $fontFamily[0]['family'] != '' ) {
					$fontFamily = $fontFamily[0]['family'];

					$fonts = get_theme_mod( 'ot_google_fonts' );
					if ( $fonts && isset( $fonts[ $fontFamily ] ) ) {
						$fontFamily = $fonts[ $fontFamily ]['family'];
					}
				} else {
					$fontFamily = '';
				}
			} else {
				$fontFamily = $this->getOption( 'heading_font_family', '' );
			}

			if ( $fontFamily != '' ) {
				return $fontFamily;
			} else {
				return '';
			}
		}

		public function getNavigationFontFamily() {
			if ( $this->getOption( 'navigation_font_on_google', 'off' ) == 'on' ) {
				$fontFamily = $this->getOption( 'navigation_font_google_family', '' );
				if ( is_array( $fontFamily ) && $fontFamily[0]['family'] != '' ) {
					$fontFamily = $fontFamily[0]['family'];

					$fonts = get_theme_mod( 'ot_google_fonts' );
					if ( $fonts && isset( $fonts[ $fontFamily ] ) ) {
						$fontFamily = $fonts[ $fontFamily ]['family'];
					}
				} else {
					$fontFamily = '';
				}
			} else {
				$fontFamily = $this->getOption( 'navigation_font_family', '' );
			}

			if ( $fontFamily != '' ) {
				return $fontFamily;
			} else {
				return '';
			}
		}

		public function getMetaFontFamily() {
			if ( $this->getOption( 'meta_font_on_google', 'off' ) == 'on' ) {
				$fontFamily = $this->getOption( 'meta_font_google_family', '' );

				if ( is_array( $fontFamily ) && $fontFamily[0]['family'] != '' ) {
					$fontFamily = $fontFamily[0]['family'];

					$fonts = get_theme_mod( 'ot_google_fonts' );
					if ( $fonts && isset( $fonts[ $fontFamily ] ) ) {
						$fontFamily = $fonts[ $fontFamily ]['family'];
					}
				} else {
					$fontFamily = '';
				}
			} else {
				$fontFamily = $this->getOption( 'meta_font_family', '' );
			}

			if ( $fontFamily != '' ) {
				return $fontFamily;
			} else {
				return '';
			}
		}

		public function getGoogleFontName( $fontFamily ) {
			$fontFamilyName = $fontFamily;

			if ( \App\Helpers\Common::isStartWith( $fontFamily, 'http' ) ) {
				$idx = strpos( $fontFamilyName, '=' );
				if ( $idx > - 1 ) {
					$fontFamilyName = substr( $fontFamilyName, $idx );
				}
			}

			$idx = strpos( $fontFamilyName, ':' );

			if ( $idx > - 1 ) {
				$fontFamilyName = substr( $fontFamilyName, 0, $idx );
				$fontFamilyName = str_replace( '+', ' ', $fontFamilyName );
			}

			return $fontFamilyName;
		}
	}