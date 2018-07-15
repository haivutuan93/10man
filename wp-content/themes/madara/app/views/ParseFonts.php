<?php

	/**
	 * Class ParseFonts
	 *
	 *
	 * @since Madara Alpha 1.0
	 * @package madara
	 */

	namespace App\Views;

	use App\Madara;

	class ParseFonts {
		public function __construct() {
			add_filter( 'madara_default_fonts_css', array( $this, 'enqueue_local_fonts' ) );
			add_filter( 'madara_default_fonts_css', array( $this, 'enqueue_themeoption_fonts' ) );
		}

		/**
		 * return custom css for local fonts used
		 */
		public function enqueue_local_fonts( $custom_css ) {
			if ( Madara::getOption( 'font_using_custom', 'off' ) == 'off' ) {
				if ( file_exists( get_parent_theme_file_path( '/app/config-local-fonts.php' ) ) ) {

					include( '/app/config-local-fonts.php' );

					if ( isset( $madara_fonts ) && count( $madara_fonts ) > 0 ) {

						$parserFont = new \App\Views\ParseLocalFonts();

						$index = 0;
						foreach ( $madara_fonts as $font => $variations ) {
							$custom_css .= $parserFont->render( $font, $variations );

							$index ++;
						}
					}
				}
			}

			return $custom_css;
		}

		/**
		 * return custom css settings for Font in Theme Options
		 */
		public function enqueue_themeoption_fonts( $custom_css ) {
			$font_family = '';

			// add custom font-face
			$local_fonts = array( 'custom_font_1', 'custom_font_2' );
			foreach ( $local_fonts as $local_font ) {
				$font_url = Madara::getOption( $local_font, '' );
				if ( $font_url != '' ) {
					$fontface   = sprintf( '@font-face{font-family: %s;src: url(%s);}', $local_font, $font_url );
					$custom_css .= $fontface;
				}
			}

			return $custom_css;
		}
	}
    