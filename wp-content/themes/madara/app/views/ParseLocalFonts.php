<?php

	/**
	 * Class parseLocalFonts
	 *
	 * @since Madara Alpha 1.0
	 * @package madara
	 */

	namespace App\Views;

	use App\Models\Entity;

	class ParseLocalFonts {
		private $typography;

		public function __construct() {
			$this->typography = new Entity\Typography();
		}

		/**
		 * render @font-face
		 *
		 * @params - $font_name - string - name of font
		 * @params - $font_variations - array - array of font variations, such as 'regular', 'bold', 'italic'. At least one variation (regular) is included
		 *
		 * return @font-face string
		 */
		public function render( $font_name, $font_variations = array( 'regular' ) ) {
			$css = array();

			foreach ( $font_variations as $var ) {
				$font_url = get_parent_theme_file_uri( '/css/fonts/' . $font_name . '/' . $font_name . '-' . $var . '.ttf' );
				$fontface = sprintf( '@font-face{font-family: %s-%s;src: url(%s);}', $font_name, $var, $font_url );

				array_push( $css, $fontface );
			}

			$custom_css = '';
			if ( count( $css ) > 0 ) {
				foreach ( $css as $local_font ) {
					$custom_css .= $local_font;
				}
			}

			return $custom_css;
		}
	}