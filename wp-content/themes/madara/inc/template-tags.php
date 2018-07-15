<?php
	/**
	 * Template Tags hold functions to print out HTML
	 *
	 * @package madara
	 */

	use App\Madara;

	/**
	 * get information of current page in Project Listing
	 */
	function madara_pagination_current_page_info( $custom_query = null ) {
		if ( ! $custom_query ) {
			$wp_query = madara_get_global_wp_query();

			$custom_query = $wp_query;
		}

		$vars         = $custom_query->query_vars;
		$current_page = $vars['paged'];
		$current_page = $current_page == 0 ? 1 : $current_page;
		$start_index  = ( $current_page - 1 ) * $vars['posts_per_page'] + 1;
		$end_index    = $start_index + $vars['posts_per_page'] - 1;
		$total        = $custom_query->found_posts;

		if ( $end_index > $total ) {
			$end_index = $total;
		}

		$current_category = esc_html__( 'All', 'madara' );

		if ( is_tax( 'ct_portfolio_cat' ) ) {
			$term = get_queried_object();
			if ( $term ) {
				$current_category = $term->name;
			}
		}

		$filter_text = ct_portfolio_get_filter_condition_in_words();

		if ( $filter_text == '' ) {

			if ( $total > 1 ) {

				$html = sprintf( wp_kses( __( '<div class="c-meta"><div class="item-meta"><ul><li><p>Showing <span>%d-%d</span> of <span>%d</span> projects in <span>%s</span></p></li></ul></div></div>', 'madara' ), array( 'ul'   => array(),
				                                                                                                                                                                                                                  'li'   => array(),
				                                                                                                                                                                                                                  'p'    => array(),
				                                                                                                                                                                                                                  'span' => array(),
				                                                                                                                                                                                                                  'div'  => array( 'class' => array() )
				) ), $start_index, $end_index, $total, $current_category );

			} else {

				$html = sprintf( wp_kses( __( '<div class="c-meta"><div class="item-meta"><ul><li><p>Showing <span>%d-%d</span> of <span>%d</span> project in <span>%s</span></p></li></ul></div></div>', 'madara' ), array( 'ul'   => array(),
				                                                                                                                                                                                                                 'li'   => array(),
				                                                                                                                                                                                                                 'p'    => array(),
				                                                                                                                                                                                                                 'span' => array(),
				                                                                                                                                                                                                                 'div'  => array( 'class' => array() )
				) ), $start_index, $end_index, $total, $current_category );

			}

		} else {

			if ( $total > 1 ) {
				$html = sprintf( wp_kses( __( '<div class="c-meta"><div class="item-meta"><ul><li><p>Showing <span>%d-%d</span> of <span>%d</span> projects found</li></ul></div></div>', 'madara' ), array( 'ul'   => array(),
				                                                                                                                                                                                                 'li'   => array(),
				                                                                                                                                                                                                 'p'    => array(),
				                                                                                                                                                                                                 'span' => array(),
				                                                                                                                                                                                                 'div'  => array( 'class' => array() )
				) ), $start_index, $end_index, $total );
			} else {
				$html = sprintf( wp_kses( __( '<div class="c-meta"><div class="item-meta"><ul><li><p>Showing <span>%d-%d</span> of <span>%d</span> projects found</li></ul></div></div>', 'madara' ), array( 'ul'   => array(),
				                                                                                                                                                                                                 'li'   => array(),
				                                                                                                                                                                                                 'p'    => array(),
				                                                                                                                                                                                                 'span' => array(),
				                                                                                                                                                                                                 'div'  => array( 'class' => array() )
				) ), $start_index, $end_index, $total );
			}
		}

		$html = apply_filters( 'madara_pagination_current_page_info', $html );

		return $html;
	}

	/**
	 * Get AOS properties string for Header
	 */
	function madara_get_header_aos_properties() {
		$header_aos = Madara::getOption( 'header_aos', '' );
		$properties = '';
		if ( $header_aos != '' ) {
			$properties .= 'data-aos="' . esc_attr( $header_aos ) . '" data-aos-once="true"';

			$header_aos_delay = Madara::getOption( 'header_aos_delay', '500' );
			if ( $header_aos_delay != '' ) {
				$properties .= ' data-aos-delay="' . $header_aos_delay . '"';
			}
		}

		return $properties;
	}