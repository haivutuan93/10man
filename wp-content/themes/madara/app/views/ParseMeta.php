<?php

	/**
	 * Class ParsePagination
	 *
	 * @since Madara Alpha 1.0
	 * @package madara
	 */

	namespace App\Views;

	class ParseMeta {
		public function __construct() {

		}

		/**
		 * Display post title
		 *
		 * @madara
		 *
		 * @since Madara Alpha 1.0
		 * @package madara
		 */
		public function renderPostTitle( $tag = 'h3', $link = 1 ) {
			if ( $link ) {
				the_title( sprintf( '<%s class="heading"><a href="%s" title="%s" rel="bookmark">', $tag, esc_url( get_permalink() ), get_the_title() ), sprintf( '</a></%s>', $tag ) );
			} else {
				the_title( sprintf( '<%s class="heading">', $tag ), sprintf( '</a></%s>', $tag ) );
			}

		}

		/**
		 * Display post meta
		 *
		 * @madara
		 *
		 * @since Madara Alpha 1.0
		 * @package madara
		 */
		public function renderPostMeta( $args = array( 'date', 'author', 'comment_count', 'category' ) ) {
			$html = '';
			ob_start();

			$madara_showdate        = \App\Madara::getOption( 'single_published_date', 'on' );
			$madara_single_category = \App\Madara::getOption( 'single_category', 'on' );

			if ( 'post' == get_post_type() && ( $madara_showdate == 'on' ) || $madara_single_category == 'on' ) : ?>
                <div class="post-on">
                    <span class="font-meta"><?php echo esc_html__( 'posted on', 'madara' ); ?></span>
                    <div class="c-blog__date">

						<?php
							if ( $madara_showdate == 'on' ) {
								$this->renderPublishDate();
							}
						?>

						<?php
							if ( $madara_single_category == 'on' ) {
								echo '<span class="post-category"><br/>' . wp_kses_post( $this->renderPostCategory() ) . '</span>';
							}
						?>

                    </div>
                </div>
			<?php endif;

			$html = ob_get_contents();

			ob_end_clean();

			if ( $html != '' ) {
				$html = '<div class="entry-meta">' . $html . '<div class="post-meta total-count font-meta">' . self::renderPostViews() . '' . self::renderPostTotalShareCounter( 0, 1 ) . '</div></div>';
			}

			echo wp_kses_post( $html );
		}

		/**
		 * @return string
		 *
		 * @since Madara Alpha 1.0
		 * @package madara
		 */
		public function renderPostCategory() {
			$output = '';

			$categories = get_the_category();

			if ( ! empty( $categories ) ) {
				$html_array = array();
				foreach ( $categories as $category ) {
					$cat_name = $category->name;
					$cat_url  = get_category_link( $category->term_id );

					array_push( $html_array, '<a href="' . esc_url( $cat_url ) . '" title="' . esc_html__( 'View all posts in ', 'madara' ) . esc_attr( $cat_name ) . '">' . esc_html( $cat_name ) . '</a>' );
				}

				$output .= implode( ', ', $html_array );

				return $output;
			}
		}

		/**
		 * Author meta tag of current post (in loop)
		 *
		 * @return string
		 */
		function renderAuthorLink() {
			$output = '<a href="' . get_author_posts_url( get_the_author_meta( 'ID' ) ) . '" class="author-url">' . get_the_author() . '</a>';

			return $output;
		}


		/**
		 * Get Publish Date
		 *
		 * @since Madara Alpha 1.0
		 * @package madara
		 */
		function renderPublishDate() {
			$time_string = '<time class="published" datetime="%1$s">%2$s</time>';

			$time_string = sprintf( $time_string, esc_attr( get_the_date( 'c' ) ), esc_html( get_the_date() ), esc_attr( get_the_modified_date( 'c' ) ), esc_html( get_the_modified_date() ) );
			printf( '<span class="posted-on"> %1$s</span>', sprintf( '<a href="%1$s" rel="bookmark">%2$s</a>', esc_url( get_permalink() ), $time_string ) );
		}

		function renderPostViews( $echo = 0 ) {
			$views = '';

			if ( function_exists( 'echo_tptn_post_count' ) ) {
				$views .= '<span class="count-view">' . do_shortcode( '[tptn_views]' ) . ' ' . esc_html__( 'Views', 'madara' ) . '</span>';
			}

			if ( $echo == 1 ) {
				echo wp_kses_post( $views );
			} else {
				return $views;
			}
		}

		function renderPostTotalShareCounter( $echo = 0, $hide_networks = 0 ) {
			$total_counter = '';

			if ( class_exists( 'APSS_Class' ) ) {
				$total_counter .= '<span class="count-share ' . ( $hide_networks == 1 ? 'hide-networks' : '' ) . '">' . do_shortcode( '[apss_share total_counter="1" counter="1"]' ) . ' ' . esc_html__( 'Share', 'madara' ) . '</span>';
			}

			if ( $echo == 1 ) {
				echo wp_kses_post( $total_counter );
			} else {
				return $total_counter;
			}
		}

	}
