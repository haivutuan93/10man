<?php

	/**
	 * Class Widget
	 *
	 * @since Madara Alpha 1.0
	 * @package madara
	 */

	namespace App\Plugins\Widgets;

	use App\Madara;

	class WidgetExtension {
		public function __construct() {
			global $madara_options_widget_width, $madara_options_widget_variation, $madara_options_widget_icon, $madara_wg_heading_style;

			if ( ( ! $madara_options_widget_width = get_option( 'madara_wg_custom_widget_width' ) ) || ! is_array( $madara_options_widget_width ) ) {
				$madara_options_widget_width = array();
			}

			if ( ( ! $madara_wg_heading_style = get_option( 'madara_wg_heading_style' ) ) || ! is_array( $madara_wg_heading_style ) ) {
				$madara_wg_heading_style = array();
			}

			if ( ( ! $madara_options_widget_variation = get_option( 'madara_wg_custom_variation' ) ) || ! is_array( $madara_options_widget_variation ) ) {
				$madara_options_widget_variation = array();
			}

			if ( ( ! $madara_options_widget_icon = get_option( 'madara_wg_custom_icon' ) ) || ! is_array( $madara_options_widget_icon ) ) {
				$madara_options_widget_icon = array();
			}

			add_action( 'sidebar_admin_setup', array( $this, 'add_widget_custom_variation_field' ) );
			add_filter( 'dynamic_sidebar_params', array( $this, 'before_widget' ) );
			add_action( 'init', array( $this, 'supportShortcode' ) );
		}

		/**
		 * Add Custom Variation field to let users set custom css class to any widget
		 *
		 * @since Madara Alpha 1.0
		 * @package madara
		 */
		public function add_widget_custom_variation_field() {
			global $wp_registered_widgets, $wp_registered_widget_controls, $madara_options_widget_variation, $madara_options_widget_width, $madara_wg_heading_style;

			foreach ( $wp_registered_widgets as $id => $widget ) {
				if ( ! $wp_registered_widget_controls[ $id ] ) {
					wp_register_widget_control( $id, $widget['name'], array( $this, 'emptyControlCallback' ) );
				}

				$wp_registered_widget_controls[ $id ]['callback_ct_redirect'] = $wp_registered_widget_controls[ $id ]['callback'];
				$wp_registered_widget_controls[ $id ]['callback']             = array(
					$this,
					'madara_widget_add_custom_fields'
				);

				array_push( $wp_registered_widget_controls[ $id ]['params'], $id );
			}

			if ( 'post' == strtolower( $_SERVER['REQUEST_METHOD'] ) ) {
				foreach ( (array) $_POST['widget-id'] as $widget_number => $widget_id ) {
					$custom_variation = array(); // array of custom_variation, layout, values

					if ( isset( $_POST[ $widget_id . '-madara_wg_custom_variation' ] ) ) {
						array_push( $custom_variation, trim( $_POST[ $widget_id . '-madara_wg_custom_variation' ] ) );
					} else {
						array_push( $custom_variation, '' );
					}

					if ( isset( $_POST[ $widget_id . '-madara_wg_layout' ] ) ) {
						array_push( $custom_variation, trim( $_POST[ $widget_id . '-madara_wg_layout' ] ) );
					} else {
						array_push( $custom_variation, '' );
					}

					if ( isset( $_POST[ $widget_id . '-madara_wg_heading_style' ] ) ) {
						array_push( $custom_variation, trim( $_POST[ $widget_id . '-madara_wg_heading_style' ] ) );
					} else {
						array_push( $custom_variation, '' );
					}

					if ( isset( $_POST[ $widget_id . '-madara_wg_custom_icon' ] ) ) {
						array_push( $custom_variation, trim( $_POST[ $widget_id . '-madara_wg_custom_icon' ] ) );
					} else {
						array_push( $custom_variation, '' );
					}

					$madara_options_widget_variation[ $widget_id ] = $custom_variation;

					if ( isset( $_POST[ $widget_id . '-madara_wg_custom_widget_width' ] ) ) {
						$madara_options_widget_width[ $widget_id ] = trim( $_POST[ $widget_id . '-madara_wg_custom_widget_width' ] );
					}

				}
			}

			update_option( 'madara_wg_custom_widget_width', $madara_options_widget_width );

			update_option( 'madara_wg_custom_variation', $madara_options_widget_variation );
		}

		/**
		 * Support running shortcode in Text Widget
		 *
		 * @since Madara Alpha 1.0
		 * @package madara
		 */
		public function supportShortcode() {
			global $wp_embed;

			return add_filter( 'widget_text', array( $wp_embed, 'run_shortcode' ), 8 );
		}

		/**
		 * Support running shortcode in Text Widget
		 *
		 * @since Madara Alpha 1.0
		 * @package madara
		 */
		public function widgetAutoEmbed() {
			global $wp_embed;

			return add_filter( 'widget_text', array( $wp_embed, 'autoembed' ), 8 );
		}

		/**
		 *
		 * @since Madara Alpha 1.0
		 * @package madara
		 */
		public function madara_widget_add_custom_fields() {
			global $wp_registered_widget_controls, $madara_options_widget_variation, $madara_options_widget_width, $madara_wg_heading_style;

			$params = func_get_args();

			$id = array_pop( $params );

			$callback = $wp_registered_widget_controls[ $id ]['callback_ct_redirect'];
			if ( is_callable( $callback ) ) {
				call_user_func_array( $callback, $params );
			}

			$values = ! empty( $madara_options_widget_variation[ $id ] ) ? $madara_options_widget_variation[ $id ] : array();

			if ( isset( $params[0]['number'] ) ) {
				$number = $params[0]['number'];
			}

			if ( $number == - 1 ) {
				$number = "__i__";
				$values = array();
			}

			$id_disp = $id;

			if ( isset( $number ) ) {
				$id_disp = $wp_registered_widget_controls[ $id ]['id_base'] . '-' . $number;
			}

			echo '<div class="widget-separator"><!-- --></div><a href="javascript:void(0)" class="btn-widget_appearance">' . esc_html__( 'Change Widget Appearance', 'madara' ) . '</a>';

			if ( is_array( $values ) && count( $values ) == 4 ) {
				$custom_variation = $values[0];
				$layout           = $values[1];
				$heading_style    = $values[2];
				$widget_icon      = $values[3];
			} else {
				$custom_variation = '';
				$layout           = '';
				$heading_style    = '';
				$widget_icon      = '';
			}

			echo '<div class="widget-appearance-settings">';

			echo "<p class='widget_appearance' style='display:none'><label for='" . esc_attr( $id_disp ) . "-madara_wg_layout'>" . esc_html__( 'Layout', 'madara' ) . ": <select class='widefat' name='" . esc_attr( $id_disp ) . "-madara_wg_layout' id='" . esc_attr( $id_disp ) . "-madara_wg_layout'>
				<option value='default' " . selected( $layout, 'default', false ) . ">" . esc_html__( 'Default', 'madara' ) . "</option>
				<option value='bordered' " . selected( $layout, 'bordered', false ) . ">" . esc_html__( 'Bordered', 'madara' ) . "</option>
				<option value='background' " . selected( $layout, 'background', false ) . ">" . esc_html__( 'Background', 'madara' ) . "</option>
				</select></label></p>";

			echo "<p class='widget_appearance' style='display:none'><label for='" . esc_attr( $id_disp ) . "-madara_wg_custom_variation'>" . esc_html__( 'Custom Variation - CSS Classes', 'madara' ) . ": <input class='widefat' type='text' name='" . esc_attr( $id_disp ) . "-madara_wg_custom_variation' id='" . esc_attr( $id_disp ) . "-madara_wg_custom_variation' value='" . esc_attr( $custom_variation ) . "' /></label></p>";

			$value = ! empty( $madara_options_widget_width[ $id ] ) ? htmlspecialchars( stripslashes( $madara_options_widget_width[ $id ] ), ENT_QUOTES ) : '';


			if ( $number == - 1 ) {
				$number = "%i%";
				$value  = "";
			}

			$html = '';

			$html .= "<p class='widget_appearance madara-footer-width hidden' id='madara-" . esc_attr( $id_disp ) . "'>";
			$html .= "<label for='" . esc_attr( $id_disp ) . "-madara_wg_custom_widget_width'>" . esc_html__( 'Widget Width', 'madara' ) . ":";
			$html .= "<select name='" . esc_attr( $id_disp ) . "-madara_wg_custom_widget_width' id='" . $id_disp . "-madara_wg_custom_widget_width'>";
			$html .= "<option value='col-xs-12 col-md-12' " . ( $value == 'col-xs-12 col-md-12' ? 'selected="selected"' : '' ) . ">col-md-12</option>";
			$html .= "<option value='col-xs-12 col-md-11' " . ( $value == 'col-xs-12 col-md-11' ? 'selected="selected"' : '' ) . ">col-md-11</option>";
			$html .= "<option value='col-xs-12 col-md-10' " . ( $value == 'col-xs-12 col-md-10' ? 'selected="selected"' : '' ) . ">col-md-10</option>";
			$html .= "<option value='col-xs-12 col-md-9' " . ( $value == 'col-xs-12 col-md-9' ? 'selected="selected"' : '' ) . ">col-md-9</option>";
			$html .= "<option value='col-xs-12 col-md-8' " . ( $value == 'col-xs-12 col-md-8' ? 'selected="selected"' : '' ) . ">col-md-8</option>";
			$html .= "<option value='col-xs-12 col-md-7' " . ( $value == 'col-xs-12 col-md-7' ? 'selected="selected"' : '' ) . ">col-md-7</option>";
			$html .= "<option value='col-xs-12 col-md-6' " . ( $value == 'col-xs-12 col-md-6' ? 'selected="selected"' : '' ) . ">col-md-6</option>";
			$html .= "<option value='col-xs-12 col-md-5' " . ( $value == 'col-xs-12 col-md-5' ? 'selected="selected"' : '' ) . ">col-md-5</option>";
			$html .= "<option value='col-xs-12 col-md-4' " . ( $value == 'col-xs-12 col-md-4' ? 'selected="selected"' : '' ) . ">col-md-4</option>";
			$html .= "<option value='col-xs-12 col-md-3' " . ( $value == 'col-xs-12 col-md-3' ? 'selected="selected"' : '' ) . ">col-md-3</option>";
			$html .= "<option value='col-xs-12 col-md-2' " . ( $value == 'col-xs-12 col-md-2' ? 'selected="selected"' : '' ) . ">col-md-2</option>";
			$html .= "<option value='col-xs-12 col-md-1' " . ( $value == 'col-xs-12 col-md-1' ? 'selected="selected"' : '' ) . ">col-md-1</option>";
			$html .= "</select>";
			$html .= "</label>";
			$html .= "</p>";

			$html .= "<p class='widget_appearance c-wg-heading-style' style='display:none' id='" . esc_attr( $id_disp ) . "-madara_wg_heading_style'>";
			$html .= "<label for='" . esc_attr( $id_disp ) . "-madara_wg_heading_style'>" . esc_html__( 'Heading Style', 'madara' ) . ":";
			$html .= "<select name='" . esc_attr( $id_disp ) . "-madara_wg_heading_style' id='" . esc_attr( $id_disp ) . "-madara_wg_heading_style'>";
			$html .= "<option value='heading-style-1' " . ( $heading_style == 'heading-style-1' ? 'selected="selected"' : '' ) . ">" . esc_html__( 'Style 1', 'madara' ) . "</option>";
			$html .= "<option value='heading-style-2' " . ( $heading_style == 'heading-style-2' ? 'selected="selected"' : '' ) . ">" . esc_html__( 'Style 2', 'madara' ) . "</option>";
			$html .= "</select>";
			$html .= "</label>";
			$html .= "</p>";

			echo( $html );

			echo "<p class='widget_appearance' style='display:none'><label for='" . esc_attr( $id_disp ) . "-madara_wg_custom_icon'>" . esc_html__( 'Font Icon - CSS Classes', 'madara' ) . ": <input class='widefat' type='text' name='" . esc_attr( $id_disp ) . "-madara_wg_custom_icon' id='" . esc_attr( $id_disp ) . "-madara_wg_custom_icon' value='" . esc_attr( $widget_icon ) . "' /></label></p>";

			echo '</div>';
		}

		public function before_widget( $params ) {
			global $madara_options_widget_width, $madara_options_widget_variation, $madara_wg_heading_style;

			$id                 = $params[0]['widget_id'];
			$widget_width_class = ! empty( $madara_options_widget_width[ $id ] ) ? htmlspecialchars( stripslashes( $madara_options_widget_width[ $id ] ), ENT_QUOTES ) : '';

			$classe_to_add = $widget_width_class;

			$widget_variation = isset( $madara_options_widget_variation[ $id ] ) ? $madara_options_widget_variation[ $id ] : array();

			if ( is_array( $widget_variation ) && count( $widget_variation ) == 4 ) {
				$custom_variation = $widget_variation[0];
				$layout           = $widget_variation[1];
				$heading_style    = $widget_variation[2];
				$widget_icon      = $widget_variation[3];
			} else {
				$custom_variation = '';
				$layout           = '';
				$heading_style    = '';
				$widget_icon      = '';
			}

			if ( $params[0]['before_widget'] != '' ) {

				if ( $classe_to_add == '' ) {
					global $wid_def;

					if ( $wid_def == 1 ) {
						$classe_to_add = 'col-xs-12 col-md-4';
					} else {
						$classe_to_add = 'col-xs-12 col-md-12';
					}

				}

				$classe_to_add = 'class="widget ' . $classe_to_add . ' ';

				$classe_to_add .= ' ' . ( $custom_variation ? htmlspecialchars( stripslashes( $custom_variation ), ENT_QUOTES ) . ' ' : '' );

				$classe_to_add .= ' ' . ( $layout ? htmlspecialchars( stripslashes( $layout ), ENT_QUOTES ) . ' ' : 'default' );

				$classe_to_add .= ' ' . ( $widget_icon && $widget_icon != '' ? ' ' : 'no-icon' );

				$classe_to_add .= ' ' . ( $heading_style && $heading_style != '' ? htmlspecialchars( stripslashes( $heading_style ) ) : '' );

				$classe_to_add = apply_filters( 'madara_before_widget_classes', $classe_to_add, $params );

				$params[0]['before_widget'] = implode( $classe_to_add, explode( 'class="widget', $params[0]['before_widget'], 2 ) );

				if ( ! $heading_style || $heading_style == '' || $heading_style == 'heading-style-1' ) {
					$params[0]['before_title'] = '<div class="widget-heading font-nav"><h5 class="heading">';
					$params[0]['after_title']  = '</h5></div>';
				} else {
					$params[0]['before_title'] = '<div class="c-blog__heading style-2 font-heading"><h4>';
					$params[0]['after_title']  = '</h4></div>';
				}


				if ( $widget_icon != '' ) {
					if ( Madara::getOption( 'rtl', 'off' ) == 'on' ) {
						$params[0]['after_title'] = '<i class="' . $widget_icon . '"></i>' . $params[0]['after_title'];
					} else {

						$params[0]['before_title'] .= '<i class="' . $widget_icon . '"></i>';
					}
				} elseif ( $widget_icon != '' ) {
					$params[0]['before_widget'] .= '<i class="' . $widget_icon . '"></i>';
				}

				// check if widget has title, wrap content of widget into a div
				// get widget name
				$widget_name = substr( $id, 0, strrpos( $id, '-' ) );
				$w_index     = substr( $id, strrpos( $id, '-' ) + 1 );

				$w_options = get_option( 'widget_' . $widget_name );
				$w_options = $w_options[ $w_index ];
				if ( isset( $w_options['title'] ) && $w_options['title'] != '' ) {
					$params[0]['after_title']  .= '<div class="widget-content">';
					$params[0]['after_widget'] .= '</div>';
				} else {
					$params[0]['before_widget'] .= '<div class="widget-content">';
					$params[0]['after_widget']  .= '</div>';
				}
			} else {
				// for widgets added by Custom Sidebars, the 'before_widget' and 'after_widget' is empty
				// so add default classes here
				$classe_to_add .= ' ' . ( $custom_variation ? htmlspecialchars( stripslashes( $custom_variation ), ENT_QUOTES ) . ' ' : '' );

				$classe_to_add .= ' ' . ( $layout ? htmlspecialchars( stripslashes( $layout ), ENT_QUOTES ) . ' ' : 'ui-wide' );

				$classe_to_add = apply_filters( 'madara_before_widget_classes', $classe_to_add, $params );

				// get widget name
				$widget_name = substr( $id, 0, strrpos( $id, '-' ) );

				global $wp_registered_widgets;

				// get widget classname
				$classname_ = '';
				foreach ( (array) $wp_registered_widgets[ $id ]['classname'] as $cn ) {
					if ( is_string( $cn ) ) {
						$classname_ .= '_' . $cn;
					} elseif ( is_object( $cn ) ) {
						$classname_ .= '_' . get_class( $cn );
					}
				}
				$classname_ = ltrim( $classname_, '_' );

				$params[0]['before_widget'] = '<div class="row"><div id="' . $id . '" class="widget widget_' . $widget_name . ' ' . $classname_ . ' ' . $classe_to_add . '"><div class="widget__inner ' . $widget_name . '__inner">';
				$params[0]['after_widget']  = '</div></div></div>';
				$params[0]['before_title']  = '<div class="widget-title"><h4 class="heading">';
				$params[0]['after_title']   = '</h4></div>';

				// check if widget has title, wrap content of widget into a div
				$w_index = substr( $id, strrpos( $id, '-' ) + 1 );

				$w_options = get_option( 'widget_' . $widget_name );
				$w_options = $w_options[ $w_index ];
				if ( isset( $w_options['title'] ) && $w_options['title'] != '' ) {
					$params[0]['after_title']  .= '<div class="widget-content">';
					$params[0]['after_widget'] .= '</div>';
				} else {
					$params[0]['before_widget'] .= '<div class="widget-content">';
					$params[0]['after_widget']  .= '</div>';
				}
			}

			return $params;
		}

		/**
		 * Intentional empty. Do not delete
		 */
		public function emptyControlCallback() {

		}
	}
