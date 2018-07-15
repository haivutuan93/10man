<?php

	/**
	 *
	 * Custom_Walker_Nav_Menu_Mobile Class
	 *
	 * @since Madara Alpha 1.0
	 * @package madara
	 */

	namespace App\Plugins;

	class Custom_Walker_Nav_Menu_Mobile extends \Walker_Nav_Menu {

		public function __construct() {

		}

		/**
		 * @param string $output
		 * @param int $depth
		 * @param array $args
		 */
		public function start_lvl( &$output, $depth = 0, $args = array() ) {
			$output .= "</option>\n";
		}

		/**
		 * @param string $output
		 * @param int $depth
		 * @param array $args
		 */
		public function end_lvl( &$output, $depth = 0, $args = array() ) {
			$output .= "";
		}

		/**
		 * @param string $output
		 * @param object $item
		 * @param int $depth
		 * @param array $args
		 */
		public function end_el( &$output, $item, $depth = 0, $args = array() ) {
			if ( substr( $output, - strlen( "</option>\n" ) ) === "</option>\n" ) {
			} else {
				$output .= "</option>\n";
			}
		}

		/**
		 * @param string $output
		 * @param object $item
		 * @param int $depth
		 * @param array $args
		 * @param int $current_object_id
		 */
		public function start_el( &$output, $item, $depth = 0, $args = array(), $current_object_id = 0 ) {
			$wp_query = madara_get_global_wp_query();

			$indent = ( $depth > 0 ? str_repeat( "\t", $depth ) : '' );

			// depth dependent classes
			$depth_classes     = array(
				( $depth == 0 ? 'main-menu-item' : 'sub-menu-item' ),
				( $depth >= 2 ? 'sub-sub-menu-item' : '' ),
				'menu-item-depth-' . $depth
			);
			$depth_class_names = esc_attr( implode( ' ', $depth_classes ) );

			$classes = empty( $item->classes ) ? array() : (array) $item->classes;

			$is_parent = false;

			$valid_classes = array();

			$icon_classes = array();

			foreach ( $classes as $class ) {
				if ( strpos( $class, 'icon-' ) === false || strpos( $class, 'icon-' ) != 0 ) {
					$valid_classes[] = $class;
				} else {
					$icon_classes[] = $class;
				}
				if ( $class == 'parent' ) {
					$is_parent = true;
				}
			}

			$class_names = esc_attr( implode( ' ', apply_filters( 'nav_menu_css_class', array_filter( $valid_classes ), $item ) ) );

			if ( str_replace( 'current-menu-item', '', $class_names ) != $class_names ) {
				$output .= $indent . '<option value="' . ( ! empty( $item->url ) ? $item->url : get_permalink( $item->ID ) ) . '" selected="selected">';
			} else {
				$output .= $indent . '<option value="' . ( ! empty( $item->url ) ? $item->url : get_permalink( $item->ID ) ) . '">';
			}

			$attributes = ! empty( $item->attr_title ) ? ' title="' . esc_attr( $item->attr_title ) . '"' : '';
			$attributes .= ! empty( $item->target ) ? ' target="' . esc_attr( $item->target ) . '"' : '';
			$attributes .= ! empty( $item->xfn ) ? ' rel="' . esc_attr( $item->xfn ) . '"' : '';
			$attributes .= ! empty( $item->url ) ? ' href="' . esc_attr( $item->url ) . '"' : ' href="' . get_permalink( $item->ID ) . '"';

			$attributes .= ' class="menu-link ' . ( $depth > 0 ? 'sub-menu-link' : 'main-menu-link' ) . '"';
			$sub_menu   = '';

			if ( $depth > 0 ) {
				for ( $i = 0; $i < $depth; $i ++ ) {
					$sub_menu .= ' - ';
				}
			}
			$item_output = sprintf( '%1$s%3$s%4$s%5$s%6$s', is_array( $args ) ? $args['before'] : $args->before, $attributes, is_array( $args ) ? $args['link_before'] : $args->link_before, apply_filters( 'the_title', $sub_menu . ( empty( $item->post_title ) ? $item->title : $item->post_title ), $item->ID ), is_array( $args ) ? $args['link_after'] : $args->link_after, is_array( $args ) ? $args['after'] : $args->after );

			// build html
			$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
		}
	}