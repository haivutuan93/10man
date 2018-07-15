<?php

	/**
	 * Base class to register shortcodes
	 */
	class CT_Shortcodes {

		function __construct() {
			add_action( 'init', array( &$this, 'init' ) );
			add_action( 'after_setup_theme', array( &$this, 'register_imagesizes' ) );

			if ( ! is_admin() ) {
				add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
				add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

				add_action( 'wp_head', array( &$this, 'madara_shortcodes_wp_head' ), 101 );
			}
		}

		function init() {

			if ( is_admin() ) {
				wp_enqueue_style( "ct_shortcode_admin_style", CT_SHORTCODE_PLUGIN_URL . 'assets/css/style-admin.css' );
				wp_enqueue_script( 'ct_shortcode_admin', CT_SHORTCODE_PLUGIN_URL . 'assets/js/admin.js' );

				add_action( 'save_post', array( &$this, 'madara_savepost_parse_shortcode_custom_css' ), 9999, 1 );

				add_action( 'wp_ajax_ct_save_widget_text', array( $this, 'widget_text_save_callback' ) );

				add_action( 'ot_after_theme_options_save', array( $this, 'ot_after_save' ) );
			}

			if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
				return;
			}

			if ( get_user_option( 'rich_editing' ) == 'true' ) {
				add_filter( 'mce_external_plugins', array( &$this, 'register_classic_editor_plugins' ) );
				add_filter( 'mce_buttons_3', array( &$this, 'add_classic_editor_buttons' ) );

				// remove a button. Used to remove a button created by another plugin
				remove_filter( 'mce_buttons_3', array( &$this, 'remove_classic_editor_buttons' ) );
			}
		}

		//  process Theme Options data to check if there is any shortcode used
		function ot_after_save( $options ) {
			$clones          = $options;
			$used_shortcodes = array();
			$global_css      = '';

			// hook to process further Option Tree's $options
			$optiontree_keys = array();
			$optiontree_keys = apply_filters( 'ct-shortcode-ot_keys_allowing_shortcode', $optiontree_keys );

			/*
			 * sample
			 * add_filter('ct-shortcode-ot_keys_allowing_shortcode', 'my_ot_keys_allowing_shortcode', 10, 1);
			 * function my_ot_keys_allowing_shortcode($optiontree_keys){
					return array('custom_key');
			   }
			 */

			if ( count( $optiontree_keys ) > 0 ) {
				foreach ( $options as $key => $val ) {
					if ( in_array( $key, $optiontree_keys ) ) {

						$replacements = array();
						$css          = $this->madara_parse_inlinecss( $val, $used_shortcodes, $replacements );

						if ( $css != '' ) {

							$new_val = $val;
							foreach ( $replacements as $replace ) {
								$new_val = preg_replace( '/' . $replace[0] . '/', $replace[1], $new_val, 1 );
							}

							$global_css     .= ';' . $css;
							$clones[ $key ] = $new_val;
						}
					}
				}

			}

			if ( startsWith( $global_css, ';' ) ) {
				$global_css = substr( $global_css, 1 );
			}

			$shortcodes = get_option( 'ct_shortcodes_used_in_ot' );
			if ( ! isset( $shortcodes ) || ! is_array( $shortcodes ) ) {
				add_option( 'ct_shortcodes_used_in_ot', array() );
			}

			$shortcodes = $used_shortcodes;
			update_option( 'ct_shortcodes_used_in_ot', $shortcodes );


			// update global custom CSS in theme options, to be called in every pages
			$global_custom_css = get_option( 'ct_ot_custom_css' );
			if ( ! isset( $global_custom_css ) || ! is_array( $global_custom_css ) ) {
				add_option( 'ct_ot_custom_css', '' );
			}

			$global_custom_css = $global_css;
			update_option( 'ct_ot_custom_css', $global_custom_css );

			update_option( ot_options_id(), $clones );

		}

		function enqueue_styles() {
			wp_enqueue_style( 'ct-shortcode', CT_SHORTCODE_PLUGIN_URL . 'shortcodes/css/shortcodes.css' );

			/**
			 * register scripts
			 */
			global $madara_shortcodes;
			foreach ( $madara_shortcodes as $shortcode ) {
				if ( isset( $shortcode['css'] ) && count( $shortcode['css'] ) > 0 ) {
					foreach ( $shortcode['css'] as $css => $params ) {
						wp_register_style( $css, $params['path'] );
					}
				}
			}

			if ( is_singular() ) {
				$id = get_the_ID();

				$shortcodes = get_post_meta( $id, '_madara_shortcodes', true );

				if ( isset( $shortcodes ) && is_array( $shortcodes ) && count( $shortcodes ) > 0 ) {

					foreach ( $shortcodes as $tag ) {

						$config = madara_get_shortcode_config( $tag );

						if ( isset( $config['css'] ) && count( $config['css'] ) > 0 ) {
							foreach ( $config['css'] as $css => $params ) {
								wp_enqueue_style( $css );
							}
						}
					}
				}
			}
		}

		function enqueue_scripts() {
			/**
			 * register scripts
			 */
			global $madara_shortcodes;

			foreach ( $madara_shortcodes as $shortcode ) {
				if ( isset( $shortcode['js'] ) && count( $shortcode['js'] ) > 0 ) {
					foreach ( $shortcode['js'] as $js => $params ) {
						wp_register_script( $js, $params['path'], isset( $params['dependencies'] ) ? $params['dependencies'] : null, isset( $params['version'] ) ? $params['version'] : '' );
					}
				}
			}
		}

		/**
		 * hook to save_post to parse custom css
		 */
		function madara_savepost_parse_shortcode_custom_css( $post_id ) {
			$post = get_post( $post_id );

			$content = $post->post_content;

			$used_shortcodes = array();
			$replacements    = array();
			$css             = $this->madara_parse_inlinecss( $content, $used_shortcodes, $replacements );

			// list of meta fields which "may" contain shortcodes
			$metas = apply_filters( 'ct_shortcodes_parse_shortcode_custom_css_in_metas', array() );

			$header_used_shortcodes = array();
			$header_replacements    = array();
			$header_css             = '';
			$header_contents        = array();

			foreach ( $metas as $meta ) {
				// check if there is any shortcodes used in Post Meta
				$header_contents[ $meta ] = get_post_meta( $post_id, $meta, true );

				$header_used_shortcodes_temp = array();
				$header_replacements_temp    = array();

				$header_css .= $this->madara_parse_inlinecss( $header_contents[ $meta ], $header_used_shortcodes_temp, $header_replacements_temp );

				$header_used_shortcodes       = array_merge( $header_used_shortcodes, $header_used_shortcodes_temp );
				$header_replacements[ $meta ] = $header_replacements_temp;
			}

			$css             .= $header_css;
			$used_shortcodes = array_merge( $used_shortcodes, $header_used_shortcodes );

			if ( empty( $css ) ) {
				delete_post_meta( $post_id, '_madara_shortcodes_custom_css' );
			} else {
				update_post_meta( $post_id, '_madara_shortcodes_custom_css', $css );
			}

			if ( count( $used_shortcodes ) > 0 ) {
				update_post_meta( $post_id, '_madara_shortcodes', $used_shortcodes );
			} else {
				delete_post_meta( $post_id, '_madara_shortcodes' );
			}


			foreach ( $replacements as $replace ) {
				$str = str_replace( array( '/', '(', ')', '$' ), array(
					'\/',
					'\(',
					'\)',
					'\$'
				), $replace[0] ); // in case there are '/' characters, need to put '\' in front of them
				$rep = str_replace( array( '/', '(', ')', '$' ), array(
					'\/',
					'\(',
					'\)',
					'\$'
				), $replace[1] ); // in case there are '/' characters, need to put '\' in front of them

				$content = preg_replace( '/' . $str . '/', $rep, $content, 1 );

				// then remove '\'
				$content = str_replace( array( '\/', '\(', '\)', '\$' ), array( '/', '(', ')', '$' ), $content );
			}


			$post->post_content = $content;

			/**
			 * temporarily disable this, as it may empty the meta
			 * ID MUST be presented in the shortcode
			 *
			 * // re-update post meta content. This happens when shortcode is missing ID property
			 * foreach($header_replacements as $meta => $replaces){
			 *
			 * foreach($replaces as $replace){
			 * $str = str_replace(array('/','(',')','$'),array('\/','\(','\)','\$'), $replace[0]); // in case there are '/' characters, need to put '\' in front of them
			 * $rep = str_replace(array('/','(',')','$'),array('\/','\(','\)','\$'), $replace[1]); // in case there are '/' characters, need to put '\' in front of them
			 *
			 * $header_contents[$meta] = preg_replace('/' . $str . '/', $rep, $header_contents[$meta], 1);
			 *
			 * // then remove '\'
			 * $header_contents[$meta] = str_replace('\/', '/', $header_contents[$meta]);
			 * }
			 *
			 * update_post_meta( $post_id, $meta, $header_contents[$meta] );
			 * }
			 *
			 */

			// unhook this function so it doesn't loop infinitely
			remove_action( 'save_post', array( $this, 'madara_savepost_parse_shortcode_custom_css' ), 9999, 1 );

			// update the post, which calls save_post again
			wp_update_post( $post );

			// re-hook this function
			add_action( 'save_post', array( $this, 'madara_savepost_parse_shortcode_custom_css' ), 9999, 1 );
		}

		/**
		 * extract inline css inside shortcode "css" attritube
		 */
		function madara_parse_inlinecss( $content, &$used_shortcodes, &$replacements ) {
			$css = '';
			// check is $content has any shortcode contain a parameter, which value is a CSS string, ex ".class{property:value}"

			preg_match_all( '/' . get_shortcode_regex() . '/', $content, $shortcodes );

			foreach ( $shortcodes[2] as $index => $tag ) {

				$shortcode = madara_get_shortcode_config( $tag );

				if ( $shortcode ) {
					array_push( $used_shortcodes, $tag );
					$attr_array = shortcode_parse_atts( trim( $shortcodes[3][ $index ] ) );

					if ( isset( $shortcode['class'] ) ) {
						$the_class = $shortcode['class'];
						$the_obj   = new $the_class( $attr_array, $shortcodes[5][ $index ] );

						$new_css = $the_obj->inlineCSSGenerator();

						$css .= $new_css;

						// replace the shortcode with new one (having generated id)
						$reg = array( $tag . $shortcodes[3][ $index ], $tag . $the_obj->toString( true ) );
						if ( $new_css != '' ) {
							array_push( $replacements, $reg );
						}
					}
				}
			}

			// recursively parse inner content
			foreach ( $shortcodes[5] as $shortcode_content ) {
				$css .= $this->madara_parse_inlinecss( $shortcode_content, $used_shortcodes, $replacements );
			}

			return $css;
		}

		/**
		 * print out custom css of shortcodes into wp_head
		 */
		function madara_shortcodes_wp_head() {
			// write out custom code for shortcodes

			if ( is_singular() ) {
				$id = get_the_ID();

				$shortcodes = get_post_meta( $id, '_madara_shortcodes', true );

				if ( isset( $shortcodes ) && is_array( $shortcodes ) && count( $shortcodes ) > 0 ) {

					foreach ( $shortcodes as $tag ) {

						$config = madara_get_shortcode_config( $tag );

						if ( isset( $config['js'] ) && count( $config['js'] ) > 0 ) {
							$idx = 1;
							foreach ( $config['js'] as $js => $params ) {

								wp_enqueue_script( $js );
								$idx ++;
							}
						}

						if ( isset( $config['css'] ) && count( $config['css'] ) > 0 ) {
							$idx = 1;
							foreach ( $config['css'] as $css => $params ) {
								wp_enqueue_style( $css );
								$idx ++;
							}
						}
					}
				}


				$css = get_post_meta( $id, '_madara_shortcodes_custom_css', true );

				if ( $css != '' ) {
					echo '<style type="text/css">' . $css . '</style>';
				}
			}

			// write global custom css
			$custom_css        = '';
			$global_custom_css = get_option( 'ct_custom_css' );

			if ( isset( $global_custom_css ) && is_array( $global_custom_css ) ) {
				foreach ( $global_custom_css as $key => $css ) {
					// check if widget is active
					preg_match( '/(.*)\[(.*)\]/', $key, $matches );
					// widget id_base
					$id_base = substr( $matches[1], 7 );

					if ( is_active_widget( false, $id_base . '-' . $matches[2], $id_base, true ) ) {
						$custom_css .= $css;
					}
				}
			}

			if ( $custom_css != '' ) {
				echo '<style type="text/css" id="ct_global_custom_css">' . $custom_css . '</style>';
			}

			// write custom css used in Theme Options
			$ot_custom_css = get_option( 'ct_ot_custom_css' );

			if ( $ot_custom_css != '' ) {
				echo '<style type="text/css" id="ct_global_ot_custom_css">' . $ot_custom_css . '</style>';
			}

			// enqueue_scripts and enqueue_styles for shortcodes used in widget
			$shortcodes = get_option( 'ct_shortcodes_used_in_widgets' );

			if ( isset( $shortcodes ) && is_array( $shortcodes ) ) {
				foreach ( $shortcodes as $key => $tags ) {
					// check if widget is active
					preg_match( '/(.*)\[(.*)\]/', $key, $matches );
					// widget id_base
					$id_base = substr( $matches[1], 7 );

					if ( is_active_widget( false, $id_base . '-' . $matches[2], $id_base, true ) ) {
						foreach ( $tags as $tag ) {
							$config = madara_get_shortcode_config( $tag );

							if ( isset( $config['js'] ) && count( $config['js'] ) > 0 ) {

								foreach ( $config['js'] as $js => $params ) {
									wp_enqueue_script( $js );
								}
							}

							if ( isset( $config['css'] ) && count( $config['css'] ) > 0 ) {

								foreach ( $config['css'] as $css => $params ) {
									wp_enqueue_style( $css );
								}
							}
						}
					}
				}
			}

			// enqueue_scripts and enqueue_styles for shortcodes used in theme options
			$shortcodes = get_option( 'ct_shortcodes_used_in_ot' );

			if ( isset( $shortcodes ) && is_array( $shortcodes ) && isset( $tag ) ) {

				foreach ( $shortcodes as $tags ) {
					$config = madara_get_shortcode_config( $tag );

					if ( isset( $config['js'] ) && count( $config['js'] ) > 0 ) {

						foreach ( $config['js'] as $js => $params ) {
							wp_enqueue_script( $js );
						}
					}

					if ( isset( $config['css'] ) && count( $config['css'] ) > 0 ) {

						foreach ( $config['css'] as $css => $params ) {
							wp_enqueue_style( $css );
						}
					}
				}
			}

			wp_enqueue_script( 'ct-shortcode-js', CT_SHORTCODE_PLUGIN_URL . 'shortcodes/js/ct-shortcodes.js', array(
				'imagesloaded',
				'slick'
			), CT_SHORTCODE_VERSION, true );
		}

		function register_classic_editor_plugins( $plgs ) {
			global $madara_shortcodes;
			foreach ( $madara_shortcodes as $name => $params ) {
				if ( isset( $params['classic_js'] ) ) {
					$plgs[ $name ] = $params['classic_js'];
				}
			}

			return $plgs;
		}


		/**
		 * remove a button from Classic Editor
		 */
		function remove_classic_editor_buttons( $btns ) {
			// add a button to remove
			// array_push($btns, 'ct_shortcode');
			return $btns;
		}

		function add_classic_editor_buttons( $btns ) {
			global $madara_shortcodes;
			foreach ( $madara_shortcodes as $name => $params ) {
				if ( isset( $params['classic_js'] ) ) {
					array_push( $btns, $name );
				}
			}

			return $btns;
		}

		/**
		 * register new image sizes to use in shortcodes here
		 *
		 */
		function register_imagesizes() {

			global $madara_size_array; // defined in Madara's theme
			if ( ! $madara_size_array ) {
				$madara_size_array = array();
			}

			/**
			 * register new sizes
			 */
			$madara_size_array_shortcode = array(/*
			'thumb_566x377' => array(566, 377, true, array('thumb_566x377','thumb_566x377','thumb_566x377','thumb_760x570'))
			*/
			);
			$madara_size_array_shortcode = apply_filters( 'madara_shortcode_image_sizes', $madara_size_array_shortcode );

			$madara_size_array = array_merge( $madara_size_array, $madara_size_array_shortcode );

			/**
			 * action madara_reg_thumbnail - defined in theme
			 */
			do_action( 'madara_reg_thumbnail', $madara_size_array );
		}

		/**
		 * Ajax function to be called when a widget is saved
		 */
		function widget_text_save_callback() {
			global $wpdb;

			$data = $_POST['data'];

			$vals = explode( '&', $data );

			foreach ( $vals as $item ) {
				$arr = explode( '=', $item );
				$key = urldecode( $arr[0] );
				$val = urldecode( $arr[1] );
				if ( endsWith( $key, '[text]' ) ) {
					$used_shortcodes = array();
					$replacements    = array();
					$css             = $this->madara_parse_inlinecss( $val, $used_shortcodes, $replacements );

					if ( $css != '' ) {
						$new_val = $val;
						foreach ( $replacements as $replace ) {
							$new_val = preg_replace( '/' . $replace[0] . '/', $replace[1], $new_val, 1 );
						}

						$widget = str_replace( '[text]', '', $key );

						// update global custom CSS, to be called in every pages
						$global_custom_css = get_option( 'ct_custom_css' );
						if ( ! isset( $global_custom_css ) || ! is_array( $global_custom_css ) ) {
							$global_custom_css = array();
							add_option( 'ct_custom_css', $global_custom_css );
						}

						$global_custom_css[ $widget ] = $css;
						update_option( 'ct_custom_css', $global_custom_css );

						$shortcodes = get_option( 'ct_shortcodes_used_in_widgets' );
						if ( ! isset( $shortcodes ) || ! is_array( $shortcodes ) ) {
							$shortcodes = array();
							add_option( 'ct_shortcodes_used_in_widgets', $shortcodes );
						}
						$shortcodes[ $widget ] = $used_shortcodes;
						update_option( 'ct_shortcodes_used_in_widgets', $shortcodes );

						preg_match( '/(.*)\[(.*)\]/', $widget, $matches );
						$id_base = substr( $matches[1], 7 );

						$widget_options = get_option( 'widget_' . $id_base );

						$widget_options[ $matches[2] ]['text'] = $new_val;

						update_option( 'widget_' . $id_base, $widget_options );

						// do this silently. So echo empty;
						break;
					}
				}
			}

			wp_die(); // this is required to terminate immediately and return a proper response
		}
	}

	$ct_shortcodes = new CT_Shortcodes();

	/**
	 * this function must be called if inner shortcode is used
	 *
	 * For example:
	 * [parent][child][/child][parent]
	 *
	 */
	if ( ! function_exists( 'madara_remove_wpautop' ) ) {

		function madara_remove_wpautop( $content, $autop = false ) {

			if ( $autop ) {
				$content = wpautop( preg_replace( '/<\/?p\>/', "\n", $content ) . "\n" );
			}

			return do_shortcode( shortcode_unautop( $content ) );
		}
	}

	/**
	 * Remove unwanted empty <p>
	 */
	add_filter( 'the_content', 'madara_remove_unwanted_p', 11 );

	if ( ! function_exists( 'madara_remove_unwanted_p' ) ) {
		function madara_remove_unwanted_p( $content = null ) {
			if ( $content ) {
				$s       = array(
					'/' . preg_quote( '</div>', '/' ) . '[\s\n\f]*' . preg_quote( '</p>', '/' ) . '/i',
					'/' . preg_quote( '<p>', '/' ) . '[\s\n\f]*' . preg_quote( '<div ', '/' ) . '/i',
					'/' . preg_quote( '<p>', '/' ) . '[\s\n\f]*' . preg_quote( '<section ', '/' ) . '/i',
					'/' . preg_quote( '</section>', '/' ) . '[\s\n\f]*' . preg_quote( '</p>', '/' ) . '/i'
				);
				$r       = array( "</div>", "<div ", "<section ", "</section>" );
				$content = preg_replace( $s, $r, $content );

				return $content;
			}

			return null;
		}
	}

	/**
	 * return configuration declaration of a madara-shortcode
	 */
	function madara_get_shortcode_config( $tag ) {
		global $madara_shortcodes;
		foreach ( $madara_shortcodes as $name => $params ) {
			if ( isset( $params['tag'] ) ) {
				if ( $tag == $params['tag'] ) {
					return $params;
				}
			}
		}

		return null;
	}

	if ( ! function_exists( 'madara_shortcode_get_category' ) ) {
		function madara_shortcode_get_category( $cats_class = 0 ) {
			$class = '';
			if ( $cats_class == 1 ) {
				$class = 'btn btn-link ruby';
			}
			$categories = get_the_category();
			$i          = 0;
			foreach ( $categories as $cat ) {
				$i ++;
				$separate = ', ';
				if ( $i == 1 ) {
					$separate = '';
				}
				$cat_name = $cat->name;
				$cat_url  = get_category_link( $cat->term_id );

				echo $separate . '<a href="' . esc_url( $cat_url ) . '" class="item-cat ' . $class . '" title="' . esc_html__( 'View all posts in ', 'madara' ) . esc_attr( $cat_name ) . '">' . esc_html( $cat_name ) . '</a>';
			}
		}
	}

	if ( ! function_exists( 'madara_shortcode_metadatas' ) ) {
		function madara_shortcode_metadatas( $only_cats = 0, $remove_cats = 0 ) {
			?>
            <div class="item-meta">
                <ul>
					<?php if ( $only_cats != 1 ) : ?>
                        <li>
                            <a href="<?php the_permalink(); ?>"><?php the_time( get_option( 'date_format' ) ); ?></a>
                        </li>
                        <li>
                            <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>">
								<?php echo esc_html__( 'By', 'madara' ) . ' ' . get_the_author(); ?>
                            </a>
                        </li>

					<?php endif; ?>

					<?php if ( $remove_cats != 1 ) : ?>
                        <li>
							<?php madara_shortcode_get_category(); ?>
                        </li>
					<?php endif; ?>

                </ul>
            </div>
			<?php
		}
	}

	//add image size
	add_filter( 'madara_thumbnail_sizes', 'madara_shortcode_thumbnail_sizes', 5, 1 );
	function madara_shortcode_thumbnail_sizes( $sizes ) {
		$arr_sizes = array(
			'madara_misc_thumb_post_slider' => array(
				642,
				320,
				true,
				esc_html__( 'Thumb 642x320px', 'madara' ),
				esc_html__( 'This thumb size is used for: Shortcode Post Slider', 'madara' )
			),
		);

		$all_size = array_merge( $sizes, $arr_sizes );

		asort( $all_size );

		return $all_size;
	}

	add_filter( 'madara_thumb_config', 'madara_shortcode_thumb_config', 5, 1 );

	function madara_shortcode_thumb_config( $mapping_array ) {
		$mapping = array(
			'642x320' => 'madara_misc_thumb_post_slider', // Shortcode Post Slider
		);

		$all_mapping = array_merge( $mapping_array, $mapping );

		return $all_mapping;
	}

	// Enable shortcodes in text widgets
	add_filter( 'widget_text', 'do_shortcode' );