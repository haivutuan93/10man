<?php

	/**
	 * Class ParsePagination
	 *
	 * @since Madara Alpha 1.0
	 * @package madara
	 */

	namespace App\Views;

	use App\Models;

	class ParsePagination {
		public function __construct() {
			$this->metadata = new Models\Metadata();
		}

		/**
		 * Display navigation to next/previous set of posts when applicable.
		 *
		 * @params $content_div & $template are passed to Ajax pagination
		 *
		 *
		 * @since Madara Alpha 1.0
		 * @package madara
		 */
		public function renderPageNavigation(
			$content_div = '#main',
			$template = 'html/loop/content',
			$custom_query = false,
			$nav_type = ''
		) {
			if ( ! $custom_query ) {
				$custom_query = $GLOBALS['wp_query'];
			}

			if ( $custom_query->max_num_pages < 2 ) {
				return;
			}

			if ( $nav_type == '' ) {
				$nav_type = \App\Madara::getOption( 'archive_navigation', 'default' );
			}

			switch ( $nav_type ) {
				case 'default':
					$this->renderPageNavigationDefault( $custom_query );
					break;
				case 'ajax':
					$this->renderPageNavigationAjax( $content_div, $template, $custom_query );
					break;
				case 'wp_pagenavi':
					if ( ! function_exists( 'wp_pagenavi' ) ) {

						$this->renderPageNavigationDefault( $custom_query );

					} else {
						echo '<div class="col-xs-12 col-md-12">';
						if ( isset( $custom_query ) ) {
							wp_pagenavi( array( 'query' => $custom_query ) );
						} else {
							wp_pagenavi();
						}
						echo '</div>';
					}
					break;
			}
		}

		/**
		 * Display navigation to next/previous set of posts when applicable. Default WordPress style
		 *
		 * @since Madara Alpha 1.0
		 * @package madara
		 */
		public function renderPageNavigationDefault( $custom_query = false ) {
			$wp_query = madara_get_global_wp_query();

			if ( $custom_query ) {
				// we need to clone the main query, so next_posts_link and previous_posts_link work
				$temp_query = clone $wp_query;
				$wp_query   = clone $custom_query;
			}

			// Don't print empty markup if there's only one page.
			if ( $wp_query->max_num_pages < 2 ) {
				return;
			}

			$rtlmode = \App\Madara::getOption( 'rtl', 'off' );

			?>
            <nav class="navigation paging-navigation">
                <div class="col-xs-12 col-md-12">
                    <h4 class="screen-reader-text">
						<?php esc_html_e( 'Posts navigation', 'madara' ); ?>
                    </h4>

                    <div class="nav-links">
                        <!--Previous-->
						<?php if ( ( $rtlmode != 'on' && get_next_posts_link( null, $wp_query->max_num_pages ) ) || ( $rtlmode == 'on' && get_previous_posts_link( null, $wp_query->max_num_pages ) ) ) { ?>
                            <div class="nav-previous pull-left">
								<?php
								if(is_front_page()){
                                                                    echo '<a href ="' . get_site_url() . '/manga/"> <span class="ion ion-android-arrow-forward meta-nav"></span> All manga </a>';
                                                                }else{
                                                                    if ( $rtlmode == 'on' ) {
										echo get_previous_posts_link( wp_kses( __( '<span class="ion ion-android-arrow-forward meta-nav"></span> Older Posts', 'madara' ), array( 'span' => array( 'class' => array( '' ) ) ) ), $wp_query->max_num_pages );
									} else {
										echo get_next_posts_link( wp_kses( __( '<span class="ion ion-android-arrow-back meta-nav"></span> Older Posts', 'madara' ), array( 'span' => array( 'class' => array() ) ) ), $wp_query->max_num_pages );
									}
                                                                }
								
								?>
                            </div>

						<?php } ?>

                        <!--Next-->
						<?php if ( ( $rtlmode != 'on' && get_previous_posts_link( null, $wp_query->max_num_pages ) ) || ( $rtlmode == 'on' && get_next_posts_link( null, $wp_query->max_num_pages ) ) ) { ?>
                            <div class="nav-next pull-right">
								<?php

									if ( $rtlmode == 'on' ) {
										echo get_next_posts_link( wp_kses( __( 'Newer posts <span class="ion ion-android-arrow-back meta-nav"></span>', 'madara' ), array( 'span' => array( 'class' => array() ) ) ), $wp_query->max_num_pages );
									} else {
										echo get_previous_posts_link( wp_kses( __( 'Newer posts <span class="ion ion-android-arrow-forward meta-nav"></span>', 'madara' ), array( 'span' => array( 'class' => array() ) ) ), $wp_query->max_num_pages );
									}

								?>
                            </div>

						<?php } ?>

                    </div>
                    <!-- .nav-links -->
                </div>
            </nav><!-- .navigation -->

			<?php
			// return the main query
			if ( $custom_query ) {
				$wp_query = clone $temp_query;
			}
		}

		/**
		 * Display navigation to next/previous set of posts when applicable. Ajax loading
		 *
		 * @params $content_div (string) - ID of the DIV which contains items
		 * @params $template (string) - name of the template file that hold HTML for a single item. It will look for specific post-format template files
		 * For example, if $template = 'content'
		 * it will look for content-$post_format.php first (i.e content-video.php, content-audio.php...)
		 * then it will look for content.php if no post-format template is found
		 *
		 *
		 * @since Madara Alpha 1.0
		 * @package madara
		 */
		public function renderPageNavigationAjax(
			$content_div = '#main',
			$template = 'content',
			$custom_query = false
		) {

			// Don't print empty markup if there's only one page.
			if ( ! $custom_query ) {
				$custom_query = $GLOBALS['wp_query'];
			}

			if ( $custom_query->max_num_pages < 2 ) {
				return;
			}

			if ( isset( $custom_query ) ) {
				global $wp;

				$args = $custom_query->query;
				?>
                <script type="text/javascript">
					var __madara_query_vars = <?php echo str_replace( '\/', '/', json_encode( $args ) ); ?>;
                </script>
				<?php
			}
			?>

            <nav class="navigation-ajax">
                <a href="javascript:void(0)" data-target="<?php echo esc_attr( $content_div ); ?>" data-template="<?php echo esc_attr( $template ); ?>" id="navigation-ajax" class="btn btn-default load-ajax">
                    <div class="load-title">
						<?php echo esc_html__( 'LOAD MORE', 'madara' ); ?>
                        <i class="ion-android-arrow-dropdown"></i>
                    </div> &nbsp;<div></div>&nbsp;<div></div>&nbsp;<div></div>
                </a>
            </nav>

			<?php
		}
	}
