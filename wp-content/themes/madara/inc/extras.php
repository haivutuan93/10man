<?php

	use App\Madara;

	/**
	 * Get sidebar setting for a particular page
	 */

	function madara_get_theme_sidebar_setting() {
		$sidebar = '';

		if ( is_404() ) {
			$sidebar = 'hidden';
		} elseif ( class_exists( 'WP_MANGA' ) && is_manga_archive() ) {
			$sidebar = Madara::getOption( 'manga_archive_sidebar', 'right' );
		} elseif ( class_exists( 'WP_MANGA' ) && is_manga_single() ) {
			$sidebar = Madara::getOption( 'manga_single_sidebar', 'right' );
		} elseif ( class_exists( 'WP_MANGA' ) && is_manga_reading_page() ) {
			$sidebar = Madara::getOption( 'manga_reading_page_sidebar', 'right' );
		} elseif ( is_page() ) {
			$sidebar = Madara::getOption( 'page_sidebar', 'right' );
		} elseif ( class_exists( 'WP_MANGA' ) && ! is_manga() && is_archive() || ( class_exists( 'WP_MANGA' ) && class_exists( 'WP_MANGA' ) && ! is_manga() && is_front_page() && is_home() || ( class_exists( 'WP_MANGA' ) && ! is_manga() && is_home() ) ) ) {
			$sidebar = Madara::getOption( 'archive_sidebar', 'right' );
		} else {
			$sidebar = Madara::getOption( 'single_sidebar', 'right' );
		}

		return apply_filters( 'madara_sidebar_setting', $sidebar );
	}

	/**
	 * Get page title of all pages
	 */
	function madara_get_page_title() {

		$page_title = '';
		if ( is_home() ) {
			$page_title = Madara::getOption( 'blog_heading', '' );
			$page_title = $page_title ? $page_title : get_bloginfo( 'name' );
		} elseif ( is_search() ) {
			$page_title = esc_html__( 'Search Results', 'madara' );
		} elseif ( is_singular() ) {
			$page_title = get_the_title();
		} elseif ( is_archive() ) {
			$page_title = '';

			if ( is_category() ) :
				$page_title = single_cat_title( '', false );

			elseif ( is_tag() ) :
				$page_title = single_tag_title( '', false );

			elseif ( is_author() ) :
				$page_title = sprintf( esc_html__( 'Author: %s', 'madara' ), '<span class="vcard">' . get_the_author() . '</span>' );

			elseif ( is_day() ) :
				$page_title = sprintf( esc_html__( 'Day: %s', 'madara' ), '<span>' . get_the_date() . '</span>' );

			elseif ( is_month() ) :
				$page_title = sprintf( esc_html__( 'Month: %s', 'madara' ), '<span>' . get_the_date( _x( 'F Y', 'monthly archives date format', 'madara' ) ) . '</span>' );

			elseif ( is_year() ) :
				$page_title = sprintf( esc_html__( 'Year: %s', 'madara' ), '<span>' . get_the_date( _x( 'Y', 'yearly archives date format', 'madara' ) ) . '</span>' );

			elseif ( is_tax( 'post_format', 'post-format-aside' ) ) :
				$page_title = esc_html__( 'Asides', 'madara' );

			elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) :
				$page_title = esc_html__( 'Galleries', 'madara' );

			elseif ( is_tax( 'post_format', 'post-format-image' ) ) :
				$page_title = esc_html__( 'Images', 'madara' );

			elseif ( is_tax( 'post_format', 'post-format-video' ) ) :
				$page_title = esc_html__( 'Videos', 'madara' );

			elseif ( is_tax( 'post_format', 'post-format-quote' ) ) :
				$page_title = esc_html__( 'Quotes', 'madara' );

			elseif ( is_tax( 'post_format', 'post-format-link' ) ) :
				$page_title = esc_html__( 'Links', 'madara' );

			elseif ( is_tax( 'post_format', 'post-format-status' ) ) :
				$page_title = esc_html__( 'Statuses', 'madara' );

			elseif ( is_tax( 'post_format', 'post-format-audio' ) ) :
				$page_title = esc_html__( 'Audios', 'madara' );

			elseif ( is_tax( 'post_format', 'post-format-chat' ) ) :
				$page_title = esc_html__( 'Chats', 'madara' );

			elseif ( is_tax( 'ct_portfolio_cat' ) ) :

				$term = get_queried_object();

				if ( $term ) {
					$page_title = sprintf( esc_html__( 'Projects in %s category', 'madara' ), $term->name );
				} else {
					$page_title = esc_html__( 'Archives', 'madara' );
				}

			elseif ( is_post_type_archive( 'ct_portfolio' ) ) :

				$text = ct_portfolio_get_filter_condition_in_words();

				if ( $text != '' ) {
					$page_title = $text;
				} else {

					$page_title = esc_html__( 'All Projects', 'madara' );
				}

			elseif ( is_post_type_archive( 'ct_office' ) ) :
				$page_title = esc_html__( 'Location', 'madara' );
			elseif ( is_post_type_archive( 'ct_service' ) ) :
				$page_title = esc_html__( 'All Services', 'madara' );
			else:
				$page_title = esc_html__( 'Archives', 'madara' );
			endif;

			$page_title = apply_filters( 'madara_archive_title', $page_title );
		}

		return $page_title;
	}
