<?php

	/**
	 * Image Helper
	 */

	namespace App\Helpers;

	class Image {
		public function __contstruct() {

			add_filter( 'madara_image_placeholder_url', array( $this, 'imagePlaceholderUrlFilter' ), 10, 2 );
		}

		public function imagePlaceholderUrlFilter( $url, $image_size ) {
			switch ( $image_size ) {
				case 'madara_misc_thumb_11':
				case 'madara_misc_thumb_10':
				case 'madara_misc_thumb_9':
				case 'madara_misc_thumb_8':
				case 'madara_misc_thumb_7':
				case 'madara_misc_thumb_6':
				case 'madara_misc_thumb_5':
				case 'madara_misc_thumb_4':
					break;
				default:
					$url = get_stylesheet_directory_uri() . '/images/img-placeholder.jpg';
					break;
			}

			return $url;
		}

		/**
		 * @param $sources
		 * @param $size_array
		 * @param $image_src
		 * @param $image_meta
		 * @param $attachment_id
		 *
		 * @return mixed
		 */
		function customSrcset( $sources, $size_array, $image_src, $image_meta, $attachment_id ) {
			$image_size_name = 'thumb_640x480';

			$breakpoint = 640;

			$upload_dir = wp_upload_dir();

			if ( isset( $image_meta['sizes'][ $image_size_name ] ) ) {
				$img_url                = $upload_dir['baseurl'] . '/' . str_replace( basename( $image_meta['file'] ), $image_meta['sizes'][ $image_size_name ]['file'], $image_meta['file'] );
				$sources[ $breakpoint ] = array(
					'url'        => $img_url,
					'descriptor' => 'w',
					'value'      => $breakpoint,
				);
			}

			return $sources;
		}
	}