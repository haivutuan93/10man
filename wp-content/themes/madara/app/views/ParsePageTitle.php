<?php

	/**
	 * Class ParsePageTitle
	 *
	 * @since Madara Alpha 1.0
	 * @package madara
	 */

	namespace App\Views;

	use App\Madara;

	class ParsePageTitle {

		public function __construct() {

		}

		public function render( $echo = 1, $auto_h = 1 ) {
			global $post;

			$title = '';

			$text['home']     = esc_html__( 'Home', 'madara' );
			$text['category'] = '%s';
			$text['search']   = esc_html__( 'Search Results for', 'madara' ) . ' <span>%s</span>';
			$text['tag']      = esc_html__( 'Tag', 'madara' ) . ' "%s"';
			$text['author']   = esc_html__( 'Author', 'madara' ) . ' %s';
			$text['404']      = Madara::getOption( 'page404_title', esc_html__( 'Page not found', 'madara' ) );

			$parent_id = $parent_id_2 = ( $post ) ? $post->post_parent : 0;

			if ( is_front_page() ) {

				$title = $text['home'];

			} elseif ( is_home() ) {

				$title = get_option( 'page_for_posts' ) ? get_the_title( get_option( 'page_for_posts' ) ) : esc_html__( 'Blog', 'madara' );

			} else {

				if ( is_category() ) {
					$title = sprintf( $text['category'], single_cat_title( '', false ) );

				} elseif ( is_search() ) {
					$title = sprintf( $text['search'], get_search_query() );

				} elseif ( is_day() ) {
					$title = esc_html__( "Archives for ", 'madara' ) . date_i18n( get_option( 'date_format' ), strtotime( get_the_date() ) );

				} elseif ( is_month() ) {
					$title = esc_html__( "Archives for ", 'madara' ) . get_the_date( 'F, Y' );

				} elseif ( is_year() ) {
					$title = esc_html__( "Archives for ", 'madara' ) . get_the_date( 'Y' );

				} elseif ( is_single() && ! is_attachment() ) {
					if ( get_post_type() != 'post' ) {

						$post_type = get_post_type_object( get_post_type() );
						$slug      = $post_type->rewrite;
						$title     = $slug['slug'] ? $slug['slug'] : $post_type->labels->singular_name;

					} else {
						$title = get_the_title();
					}

				} elseif ( is_tag() ) {
					$title = sprintf( $text['tag'], single_tag_title( '', false ) );

				} elseif ( is_tax() ) {
					$term  = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
					$title = $term->name;

				} elseif ( ! is_single() && ! is_page() && get_post_type() != 'post' && ! is_404() && ! is_tag() ) {

					$post_type = get_post_type_object( get_post_type() );
					$slug      = $post_type->rewrite;
					$title     = ( $slug['slug'] ? $slug['slug'] : $post_type->labels->singular_name );

				} elseif ( is_attachment() ) {
					if ( $parent = get_post( $parent_id ) ) {
						$title = printf( get_permalink( $parent ), $parent->post_title );

					} else {
						$title = get_the_title();
					}

				} elseif ( is_page() ) {
					$title = get_the_title();

				} elseif ( is_author() ) {
					global $author;
					$userdata = get_userdata( $author );
					$title    = sprintf( $text['author'], $userdata->display_name );

				} elseif ( is_404() ) {
					$title = $text['404'];
				}
			}

			$title = apply_filters( 'madara_page_title', '<h1 class="h1" >' . $title . '</h1>', $title );

			if ( $echo ) {
				echo wp_kses_post( $title );
			} else {
				return $title;
			}
		}
	}
