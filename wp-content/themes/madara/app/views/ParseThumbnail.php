<?php

	/**
	 * Class ParseThumbnail
	 *
	 * @since Madara Alpha 1.0
	 * @package madara
	 */

	namespace App\Views;

	// Prevent direct access to this file
	defined( 'ABSPATH' ) || die( 'Direct access to this file is not allowed.' );

	use App\Madara;

	class ParseThumbnail {

		public function __construct() {

		}

		/**
		 * Get Thumbnail IMG tag
		 *
		 * @param $size - array/string - If array is passed in, it will be mapped to appropriate size declared by theme
		 * @param $post_id - int - Post ID. If not passed in, current post ID wil be used
		 * @param $source_sizes - mixed - If not passed in, default WordPress source sizes will be used
		 *
		 * @return string - IMG tag
		 */
		public function render( $size = array(), $post_id = - 1, $source_sizes = '' ) {
			if ( $post_id == - 1 ) {
				//if there is no ID
				$post_id = get_the_ID();
			}

			if ( is_array( $size ) && count( $size ) == 2 ) {
				$size = \App\Config\ThemeConfig::thumbSizeMapping( $size );
			} else {
				// do nothing
			}

			$size = apply_filters( 'madara_thumbnail_size_filter', $size, $post_id );

			//get attachment id
			if ( get_post_type( $post_id ) == 'attachment' ) {
				$attachment_id = $post_id;
			} else {
				$attachment_id = get_post_thumbnail_id( $post_id );
			}

			//return
			if ( function_exists( 'wp_get_attachment_image_srcset' ) ) {

				$lazyload  = Madara::getOption( 'lazyload', 'off' );
				$lazyClass = '';

				$img_src    = wp_get_attachment_image_url( $attachment_id, $size );
				$img_srcset = wp_get_attachment_image_srcset( $attachment_id, $size );
				$img_sizes  = wp_get_attachment_image_sizes( $attachment_id, $size );

				if ( $source_sizes != '' ) {
					$img_sizes = $source_sizes;
				}

				$html_img_src        = $img_src != '' ? ( ( $lazyload == 'on' ) ? ' data-src="' . $img_src . '"' : ' src="' . $img_src . '"' ) : '';
				$html_img_responsive = ( $img_srcset != '' && $img_sizes != '' ) ? ( ( $lazyload == 'on' ) ? ' data-srcset="' . $img_srcset . '" data-sizes="' . $img_sizes . '"' : ' srcset="' . $img_srcset . '" sizes="' . $img_sizes . '"' ) : '';

				$image_attributes = wp_get_attachment_image_src( $attachment_id, $size );

				if ( $lazyload == 'on' ) {
					$ratio = '';

					/**
					 * Add ratio placeholder for each image. This placeholder need remove padding-top when imageloaded (lazyloaded)
					 * .effect-fade need add style for this effect in CSS.
					 */

					if ( ! empty( $image_attributes ) ) {

						if ( $image_attributes[2] / $image_attributes[1] <= 1 ) {
							$ratio = ( $image_attributes[2] / $image_attributes[1] * 100 );
							$ratio = 'style="padding-top:' . $ratio . '%; "';
						} else {
							$ratio = $image_attributes[2];
							$ratio = 'style="padding-top:' . $ratio . 'px; "';
						}

					}


					$lazyload_dfimg = apply_filters( 'madara_image_placeholder_url', get_parent_theme_file_uri( '/images/dflazy.jpg' ), $size );

					$lazyClass = ' class="img-responsive lazyload effect-fade" src="' . $lazyload_dfimg . '" ' . $ratio;
				} else {
					$lazyClass = ' class="img-responsive"';
				}

				$html = $html_img_src != '' ? '<img width="' . $image_attributes[1] . '" height="' . $image_attributes[2] . '" ' . $html_img_src . $html_img_responsive . $lazyClass . ' alt="' . get_the_title() . '"/>' : '';

				// this filter is used by Nelio External Thumbnail plugin
				$html = apply_filters( 'post_thumbnail_html', $html, $post_id, $attachment_id, $size, $image_attributes );

				return $html;

			} else {
				return wp_get_attachment_image( $attachment_id, $size );
			}
		}
	}