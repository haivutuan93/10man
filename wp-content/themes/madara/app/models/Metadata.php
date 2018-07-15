<?php

	/**
	 * Class CT_Metadata_M
	 *
	 * @package madara
	 */

	namespace App\Models;

	class Metadata extends Data {

		public function __construct() {

		}

		/**
		 * Copy from ot-functions.php
		 *
		 * @param        $option_id
		 * @param string $default
		 *
		 * @return string
		 */
		public function ot_get_option( $option_id, $default = '' ) {
			$options = get_option( $this->ot_options_id() );
			if ( isset( $options[ $option_id ] ) && '' != $options[ $option_id ] ) {
				return $this->ot_wpml_filter( $options, $option_id );
			}

			return $default;
		}

		/**
		 * Copy from ot-functions.php
		 *
		 * @return mixed|void
		 */
		public function ot_options_id() {
			return apply_filters( 'ot_options_id', 'option_tree' );
		}

		/**
		 * Copy from ot-functions.php
		 *
		 * @return mixed|void
		 */
		public function ot_settings_id() {
			return apply_filters( 'ot_settings_id', 'option_tree_settings' );
		}

		/**
		 * Copy from ot-functions.php
		 *
		 * @param $options
		 * @param $option_id
		 *
		 * @return mixed
		 */
		public function ot_wpml_filter( $options, $option_id ) {
			if ( function_exists( 'icl_t' ) ) {
				$settings = get_option( $this->ot_settings_id() );
				if ( isset( $settings['settings'] ) ) {
					foreach ( $settings['settings'] as $setting ) {
						if ( $option_id == $setting['id'] && in_array( $setting['type'], array(
								'list-item',
								'slider'
							) ) ) {
							foreach ( $options[ $option_id ] as $key => $value ) {
								foreach ( $value as $ckey => $cvalue ) {
									$id      = $option_id . '_' . $ckey . '_' . $key;
									$_string = icl_t( 'Theme Options', $id, $cvalue );
									if ( ! empty( $_string ) ) {
										$options[ $option_id ][ $key ][ $ckey ] = $_string;
									}
								}
							}
						} else if ( $option_id == $setting['id'] && $setting['type'] == 'social-links' ) {
							foreach ( $options[ $option_id ] as $key => $value ) {
								foreach ( $value as $ckey => $cvalue ) {
									$id      = $option_id . '_' . $ckey . '_' . $key;
									$_string = icl_t( 'Theme Options', $id, $cvalue );
									if ( ! empty( $_string ) ) {
										$options[ $option_id ][ $key ][ $ckey ] = $_string;
									}
								}
							}
						} else if ( $option_id == $setting['id'] && in_array( $setting['type'], apply_filters( 'ot_wpml_option_types', array(
								'text',
								'textarea',
								'textarea-simple'
							) ) ) ) {
							$_string = icl_t( 'Theme Options', $option_id, $options[ $option_id ] );
							if ( ! empty( $_string ) ) {
								$options[ $option_id ] = $_string;
							}
						}
					}
				}
			}

			return $options[ $option_id ];
		}

		/**
		 * Get Metadatas
		 *
		 * @param      $options
		 * @param null $default
		 *
		 * @return bool|mixed|null|string
		 */
		public function getOption( $options, $default = null ) {
			return \App\Madara::getOption( $options, $default );
		}

		/**
		 * @param $id
		 *
		 * @return bool
		 */
		public function getLabel( $id ) {
			if ( empty( $id ) ) {
				return false;
			}
			$settings = get_option( 'option_tree_settings' );

			if ( empty( $settings['settings'] ) ) {
				return false;
			}
			foreach ( $settings['settings'] as $setting ) {
				if ( $setting['id'] == $id && isset( $setting['label'] ) ) {
					return $setting['label'];
				}
			}
		}
	}
