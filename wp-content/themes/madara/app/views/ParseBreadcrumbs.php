<?php

	/**
	 * Class ParseBreadcrumbs
	 */

	namespace App\Views;

	class ParseBreadcrumbs {

		public function __construct() {

		}

		/**
		 * Show Breadcumbs
		 *
		 * @author
		 *
		 * @since Madara Alpha 1.0
		 * @package madara
		 */
		public function render( $echo = 1 ) {
			global $post;

			$html = '';

			$text['home']     = esc_html__( 'Home', 'madara' ); // text for the 'Home' link
			$text['category'] = '%s'; // text for a category page
			$text['search']   = esc_html__( 'Search Results for', 'madara' ) . ' "%s"'; // text for a search results page
			$text['tag']      = esc_html__( 'Tag', 'madara' ) . ' "%s"'; // text for a tag page
			$text['author']   = ' %s'; // text for an author page
			$text['404']      = esc_html__( '404', 'madara' ); // text for the 404 page

			$show_current   = 1; // 1 - show current post/page/category title in breadcrumbs, 0 - don't show
			$show_on_home   = 1; // 1 - show breadcrumbs on the homepage, 0 - don't show
			$show_home_link = 1; // 1 - show the 'Home' link, 0 - don't show
			$show_title     = 1; // 1 - show the title for the links, 0 - don't show
			$delimiter      = ' <span class="ct-s-v-u"><i class="fa fa-caret-right"></i></span> '; // delimiter between crumbs
			$before         = '<span class="current">'; // tag before the current crumb
			$after          = '</span>'; // tag after the current crumb

			$home_link    = home_url( '/' );
			$link_before  = '';
			$link_after   = '';
			$link_attr    = '';//' rel="v:url" property="v:title"';
			$link         = $link_before . '<a' . $link_attr . ' href="%1$s">%2$s</a>' . $link_after;
			$parent_id    = $parent_id_2 = ( $post ) ? $post->post_parent : 0;
			$frontpage_id = get_option( 'page_on_front' );

			$event_layout = '';

			if ( is_front_page() ) {

				if ( $show_on_home == 1 ) {
					$html .= '<div class="c-page-breadcrumb"><a href="' . $home_link . '">' . $text['home'] . '</a></div>';
				}

			} elseif ( is_home() ) {
				$title = get_option( 'page_for_posts' ) ? get_the_title( get_option( 'page_for_posts' ) ) : esc_html__( 'Blog', 'madara' );
				$html  .= '<div class="c-page-breadcrumb"><a href="' . $home_link . '">' . $text['home'] . '</a> . ' . $title . '</div>';
			} else {

				$html .= '<div class="c-page-breadcrumb">';
				if ( $show_home_link == 1 ) {
					if ( function_exists( "is_shop" ) && is_shop() ) {

					} else {
						$html .= '<a href="' . $home_link . '">' . $text['home'] . '</a>'; //rel="v:url" property="v:title"
						if ( $frontpage_id == 0 || $parent_id != $frontpage_id ) {
							$html .= $delimiter;
						}
					}
				}

				if ( is_category() ) {
					$this_cat = get_category( get_query_var( 'cat' ), false );
					if ( $this_cat->parent != 0 ) {
						$cats = get_category_parents( $this_cat->parent, true, $delimiter );
						if ( $show_current == 0 ) {
							$cats = preg_replace( "#^(.+)$delimiter$#", "$1", $cats );
						}
						$cats = str_replace( '<a', $link_before . '<a' . $link_attr, $cats );
						$cats = str_replace( '</a>', '</a>' . $link_after, $cats );
						if ( $show_title == 0 ) {
							$cats = preg_replace( '/ title="(.*?)"/', '', $cats );
						}
						$html .= esc_html( $cats );
					}
					if ( $show_current == 1 ) {
						$html .= $before . sprintf( $text['category'], single_cat_title( '', false ) ) . $after;
					}

				} elseif ( is_search() ) {
					$html .= $before . sprintf( $text['search'], get_search_query() ) . $after;

				} elseif ( is_day() ) {
					$html .= sprintf( $link, get_year_link( get_the_time( 'Y' ) ), get_the_time( 'Y' ) ) . $delimiter;
					$html .= sprintf( $link, get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) ), get_the_time( 'F' ) ) . $delimiter;
					$html .= $before . get_the_time( 'd' ) . $after;

				} elseif ( is_month() ) {
					$html .= sprintf( $link, get_year_link( get_the_time( 'Y' ) ), get_the_time( 'Y' ) ) . $delimiter;
					$html .= $before . get_the_time( 'F' ) . $after;

				} elseif ( is_year() ) {
					$html .= $before . get_the_time( 'Y' ) . $after;

				} elseif ( is_single() && ! is_attachment() ) {
					if ( get_post_type() != 'post' ) {
						$post_type = get_post_type_object( get_post_type() );
						$slug      = $post_type->rewrite;
						$html      .= sprintf( $link, $home_link . $slug['slug'] . '/', $post_type->labels->singular_name );
						if ( $show_current == 1 ) {
							$html .= $delimiter . $before . get_the_title() . $after;
						}
					} else {
						$cat  = get_the_category();
						$cat  = $cat[0];
						$cats = get_category_parents( $cat, true, $delimiter );
						if ( $show_current == 0 ) {
							$cats = preg_replace( "#^(.+)$delimiter$#", "$1", $cats );
						}
						$cats = str_replace( '<a', $link_before . '<a' . $link_attr, $cats );
						$cats = str_replace( '</a>', '</a>' . $link_after, $cats );
						if ( $show_title == 0 ) {
							$cats = preg_replace( '/ title="(.*?)"/', '', $cats );
						}
						$html .= $cats;
						if ( $show_current == 1 ) {
							$html .= $before . get_the_title() . $after;
						}
					}

				} elseif ( ! is_single() && ! is_page() && get_post_type() != 'post' && ! is_404() ) {
					if ( function_exists( "is_shop" ) && is_shop() ) {
						do_action( 'woocommerce_before_main_content' );
						do_action( 'woocommerce_after_main_content' );
					} else {
						$post_type = get_post_type_object( get_post_type() );
						if ( isset( $post_type->rewrite ) ) {
							$slug           = $post_type->rewrite;
							$downloads_text = ot_get_option( 'downloads_text' );
							if ( get_post_type() == 'download' && $downloads_text != '' ) {
								$html .= $before . ( esc_attr( $downloads_text ) ) . $after;
							} else {
								$html .= $before . ( $slug['slug'] ? $slug['slug'] : $post_type->labels->singular_name ) . $after;
							}
						}
					}

				} elseif ( is_attachment() ) {
					$parent = get_post( $parent_id );
					$cat    = get_the_category( $parent->ID );
					$cat    = isset( $cat[0] ) ? $cat[0] : '';
					if ( $cat ) {
						$cats = get_category_parents( $cat, true, $delimiter );
						$cats = str_replace( '<a', $link_before . '<a' . $link_attr, $cats );
						$cats = str_replace( '</a>', '</a>' . $link_after, $cats );
						if ( $show_title == 0 ) {
							$cats = preg_replace( '/ title="(.*?)"/', '', $cats );
						}
						$html .= esc_html( $cats );
					}
					printf( $link, get_permalink( $parent ), $parent->post_title );
					if ( $show_current == 1 ) {
						$html .= $delimiter . $before . get_the_title() . $after;
					}

				} elseif ( is_page() && ! $parent_id ) {
					if ( $show_current == 1 ) {
						$html .= $before . get_the_title() . $after;
					}

				} elseif ( is_page() && $parent_id ) {
					if ( $parent_id != $frontpage_id ) {
						$breadcrumbs = array();
						while ( $parent_id ) {
							$page = get_page( $parent_id );
							if ( $parent_id != $frontpage_id ) {
								$breadcrumbs[] = sprintf( $link, get_permalink( $page->ID ), get_the_title( $page->ID ) );
							}
							$parent_id = $page->post_parent;
						}
						$breadcrumbs = array_reverse( $breadcrumbs );
						for ( $i = 0; $i < count( $breadcrumbs ); $i ++ ) {
							$html .= strip_shortcodes( $breadcrumbs[ $i ] );
							if ( $i != count( $breadcrumbs ) - 1 ) {
								$html .= $delimiter;
							}
						}
					}
					if ( $show_current == 1 ) {
						if ( $show_home_link == 1 || ( $parent_id_2 != 0 && $parent_id_2 != $frontpage_id ) ) {
							$html .= $delimiter;
						}
						$html .= $before . get_the_title() . $after;
					}

				} elseif ( is_tag() ) {
					$html .= $before . esc_attr( sprintf( $text['tag'] ), single_tag_title( '', false ) ) . $after;

				} elseif ( is_author() ) {
					global $author;
					$userdata = get_userdata( $author );
					$html     .= $before . esc_attr( sprintf( $text['author'], $userdata->display_name ) ) . $after;

				} elseif ( is_404() ) {
					$html .= $before . esc_attr( $text['404'] ) . $after;
				}

				$html .= '</div><!-- .breadcrumbs -->';

			}

			if ( $echo ) {
				echo wp_kses_post( $html );
			} else {
				return $html;
			}
		}
	}