<?php
	/*
	*  Manga Breadcrumb
	*/

	use App\Madara;

	$wp_query = madara_get_global_wp_query();
	$object   = $wp_query->queried_object;

	$madara_breadcrumb_bg = is_manga_archive() ? madara_output_background_options( 'manga_archive_breadcrumb_bg', '' ) : '';

	$madara_genres_block       = Madara::getOption( 'manga_archive_genres', 'on' );
	$manga_archive_genres_collapse = Madara::getOption( 'manga_archive_genres_collapse', 'on' );
	$manga_archive_genres_title    = Madara::getOption( 'manga_archive_genres_title', 'GENRES' );

	$overwrite_genres_collapse = isset( $_GET['genres_collapse'] ) && $_GET['genres_collapse'] != '' ? $_GET['genres_collapse'] : '';

	if ( $overwrite_genres_collapse != '' && $overwrite_genres_collapse == 'on' ) {
		$manga_archive_genres_collapse = $overwrite_genres_collapse;
	}

	if ( is_post_type_archive( 'wp-manga' ) || is_home() || is_front_page() ) {

		$object = null;

	} elseif ( is_manga_archive() ) {

		$obj_title = $object->name;
		$obj_url   = get_term_link( $object );

	} elseif ( is_manga_single() || is_manga_reading_page() ) {

		$obj_title = $object->post_title;
		$obj_url   = get_the_permalink( $object->ID );

	}

	$breadcrumb_bg_html = '';
	if ( is_manga_archive() && ! is_manga_search_page() ) {
		$breadcrumb_bg_html .= 'style="';

		$breadcrumb_bg_html .= $madara_breadcrumb_bg != '' ? $madara_breadcrumb_bg : 'background-image: url(' . get_parent_theme_file_uri( '/images/bg-search.jpg' );

		$breadcrumb_bg_html .= '"';
	}

	if ( ! is_page_template() || ! is_home() && ! is_front_page() ) {

		?>

        <div class="c-breadcrumb-wrapper" <?php echo wp_kses_post( $breadcrumb_bg_html ); ?>>

			<?php if ( is_manga_archive() ) { ?>
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
						<?php } ?>

                        <div class="c-breadcrumb">
                            <ol class="breadcrumb">
                                <li>
                                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
										<?php esc_html_e( 'Home', 'madara' ); ?>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo esc_url( get_post_type_archive_link( 'wp-manga' ) ); ?>">
										<?php esc_html_e( 'Manga', 'madara' ); ?>
                                    </a>
                                </li>

								<?php
									$middle = madara_get_global_wp_manga()->wp_manga_breadcrumb_middle( $object );

									if ( ! empty( $middle ) ) {
										$middle = array_reverse( $middle );

										foreach ( $middle as $name => $link ) { ?>
                                            <li>
                                                <a href="<?php echo esc_url( $link ); ?>">
													<?php echo esc_html( $name ); ?>
                                                </a>
                                            </li>
										<?php }
									}
								?>

								<?php if ( $object !== null ) { ?>
                                    <li>
                                        <a href="<?php echo esc_url( $obj_url ); ?>">
											<?php echo esc_html( $obj_title ); ?>
                                        </a>
                                    </li>
								<?php } ?>

								<?php if ( is_manga_reading_page() && class_exists( 'WP_MANGA' ) ) {
									$chapter_slug = get_query_var( 'chapter' );

									if ( ! empty( $chapter_slug ) ) {
										$wp_manga         = madara_get_global_wp_manga();
										$wp_manga_chapter = madara_get_global_wp_manga_chapter();
										$chapter_json     = $wp_manga->get_chapter( get_the_ID() );

//										$chapter_db = $wp_manga_chapter->get_chapter_by_slug( get_the_ID(), $chapter_slug );
                                                                                global $global_chapter_by_slug;
                                                                                $chapter_db = $global_chapter_by_slug;
										$c_name   = isset( $chapter_db['chapter_name'] ) ? $chapter_db['chapter_name'] : '';
										$c_extend = madara_get_global_wp_manga_functions()->filter_extend_name( $chapter_db['chapter_name_extend'] );

										if ( isset( $c_name ) ) {
											?>
                                            <li class="active">
												<?php echo esc_html( $c_name . $c_extend ); ?>
                                            </li>
											<?php
										}
									}
								} ?>

                            </ol>
                        </div>

						<?php if ( is_manga_reading_page() ) { ?>
                            <div class="action-icon">
                                <ul class="action_list_icon list-inline">
                                    <li>
										<?php echo madara_get_global_wp_manga_functions()->bookmark_link_e(); ?>
                                    </li>
                                </ul>
                            </div>
						<?php } ?>
						<?php if ( ! is_manga_single() && ! is_manga_reading_page() && $madara_genres_block == 'on' && ! is_manga_search_page() ) {

							//genre query
							$genre_args = array(
								'taxonomy'   => 'wp-manga-genre',
								'hide_empty' => false,
							);
							$genres     = get_terms( $genre_args );
							if ( ! empty( $genres ) && ! is_wp_error( $genres ) && is_manga_archive() ) {
								?>

                                <div class="c-genres-block archive-page">
                                    <div class="genres_wrap">

                                        <div class="c-blog__heading style-3 font-heading <?php echo ($manga_archive_genres_collapse == 'on') ? 'active' : ''; ?>">
                                            <h5><?php echo esc_html( $manga_archive_genres_title ); ?></h5>
                                        </div>
                                        <a class="btn btn-genres ion-arrow-down-b pull-right <?php echo ($manga_archive_genres_collapse == 'on') ? 'active' : ''; ?>"></a>
                                        <div class="genres__collapse" style="<?php echo ($manga_archive_genres_collapse == 'on') ? 'display: block' : 'display: none'; ?>">
											<?php

												if ( ! empty( $genres ) && ! is_wp_error( $genres ) ) { ?>
                                                    <div class="row genres">
                                                        <ul class="list-unstyled">
															<?php
																foreach ( $genres as $genre ) {
																	?>
                                                                    <li class="col-xs-6 col-sm-4 col-md-2">
                                                                        <a href="<?php echo esc_url( get_term_link( $genre ) ); ?>">
																			<?php echo esc_html( $genre->name ); ?>

                                                                            <span class="count">
                                                                        (<?php echo esc_html( $genre->count ); ?>)
                                                                    </span>

                                                                        </a>
                                                                    </li>
																	<?php
																}
															?>
                                                        </ul>
                                                    </div>

												<?php } ?>
                                        </div>

                                    </div>
                                </div>
							<?php }
						}
						?>
						<?php if ( is_manga_archive() ) { ?>
                    </div>
                </div>
            </div>
		<?php } ?>
        </div>

	<?php }
