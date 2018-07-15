<?php

	/**
	 * Class parseGoogleFonts
	 *
	 * @since Madara Alpha 1.0
	 * @package madara
	 */

	namespace App\Views;

	use App\Models\Entity;

	class ParseGoogleFonts {
		private $typography;

		public function __construct() {
			$this->typography = new Entity\Typography();
		}

		public function render() {
			$options = $this->typography->getOption( 'google_font', '' );

			/**
			 * Main Font Famly
			 * Heading Font Family
			 * Navigation Font Family
			 */
			$getGoogleFonts = array();

			$getMainFontFamily = $this->typography->getMainFontFamily();
			array_push( $getGoogleFonts, $getMainFontFamily );

			$getHeadingFontFamily = $this->typography->getHeadingFontFamily();
			array_push( $getGoogleFonts, $getHeadingFontFamily );

			$getNavigationFontFamily = $this->typography->getNavigationFontFamily();
			array_push( $getGoogleFonts, $getNavigationFontFamily );

			if ( $options == 'on' ) {
				return $getGoogleFonts;
			} else {
				return false;
			}
		}
	}