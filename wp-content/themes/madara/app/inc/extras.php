<?php

	/**
	 * helper functions
	 */

	use App\Madara;
	use App\Models\Database;

	/**
	 * Get Front-Page template query settings
	 */
	function madara_get_front_page_query( $post_type = 'post', $page = 1 ) {
		$posts_per_page = Madara::getOption( 'page_post_count' ) ? Madara::getOption( 'page_post_count' ) : get_option( 'posts_per_page' );
		$cats           = Madara::getOption( 'page_post_cats' );
		$tags           = Madara::getOption( 'page_post_tags' );
		$ids            = Madara::getOption( 'page_post_ids' );
		$order          = Madara::getOption( 'page_post_order' );
		$orderby        = Madara::getOption( 'page_post_orderby' );

		if ( $orderby == 'name' ) {
			$order = 'ASC';
		}

		$args = array(
			'post_type'  => $post_type,
			'categories' => $cats,
			'tags'       => $tags,
			'ids'        => $ids
		);

		return Database::getPosts( $posts_per_page, $order, $page, $orderby, $args );
	}

	/**
	 * Adds custom classes to the array of body classes.
	 *
	 * @param array $classes Classes for the body element.
	 *
	 * @return array
	 */
	function madara_body_classes( $classes ) {
		$classes[] = 'page';

		$header_layout = Madara::getOption( 'header_style', 1 );
		$classes[]     = 'header-style-' . $header_layout;

		// if we are in Full Page template and Sectionized mode, sticky menu should be turned off
		if ( is_page() && basename( get_page_template() ) == 'fullpage.php' && get_post_meta( get_the_ID(), 'fullpage_autoscrolling', true ) == 'on' ) {
			// do nothing
		} else {
			$sticky_menu = Madara::getOption( 'nav_sticky', 1 );
			$sticky_navgiation = Madara::getOption('manga_reading_sticky_navigation', 'on');
			if ( $sticky_menu != 0 || $sticky_navgiation == 'on' ) {
				$classes[] = 'sticky-enabled';
				$classes[] = 'sticky-style-' . $sticky_menu;
			}
		}

		$sidebar = madara_get_theme_sidebar_setting();
		if ( $sidebar != 'full' && is_active_sidebar( 'main_sidebar' ) ) {
			$classes[] = 'is-sidebar';
		}


		$body_schema           = Madara::getOption( 'body_schema', 'light' );
		$overwrite_body_schema = isset( $_GET['body_schema'] ) && $_GET['body_schema'] != '' ? $_GET['body_schema'] : '';

		if ( $overwrite_body_schema != '' ) {
			if ( $overwrite_body_schema == 'dark' ) {
				$classes[] = 'text-ui-light';
			} else {
				$classes[] = 'text-ui-dark';
			}
		} else {
			if ( $body_schema == 'light' ) {
				$classes[] = 'text-ui-dark';
			} else {
				$classes[] = 'text-ui-light';
			}
		}

		return $classes;
	}

	add_filter( 'body_class', 'madara_body_classes' );

	add_filter( 'document_title_parts', 'madara_wp_title' );
	function madara_wp_title( $title ) {

		if ( is_404() ) {
			$title['title'] = Madara::getOption( 'page404_head_tag', $title['title'] );
		}

		return $title;
	}

	/**
	 * Use for global wp_query, get total number of posts in a query
	 */
	function madara_get_found_posts( $custom_query = null ) {
		if ( ! $custom_query ) {
			global $wp_query;
			$custom_query = $wp_query;
		}

		$found_posts = 0;
		if ( $custom_query ) {
			$found_posts = $custom_query->found_posts;
		}

		return $found_posts;
	}

	/**
	 * Use for global wp_query, get total number of posts in a query
	 */
	function madara_get_post_count( $custom_query = null ) {

		if ( ! $custom_query ) {
			global $wp_query;
			$custom_query = $wp_query;
		}

		$post_count = 0;
		if ( $custom_query ) {
			$post_count = $custom_query->post_count;
		}

		return $post_count;
	}

	/**
	 * Setup postdata for object $item
	 */
	function madara_setup_postdata( $item ) {
		global $post;
		$post = $item;
		setup_postdata( $post );
	}

	/**
	 * Set custom query to be Main Query, so we can use Template Tags like normal
	 *
	 * @return WP_Query main query to be returned later
	 */
	function madara_set_main_query( $custom_query ) {
		global $wp_query;

		$temp_query = $wp_query;

		$wp_query = $custom_query;

		return $temp_query;
	}