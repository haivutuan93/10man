<?php
	/**
	 * @package madara
	 */

	get_header();

	/**
	 * madara_before_main_page hook
	 *
	 * @hooked madara_output_before_main_page - 10
	 * @hooked madara_output_top_sidebar - 89
	 *
	 * @author
	 * @since 1.0
	 * @code     Madara
	 */

	do_action( 'madara_before_main_page' );

	global $wp_manga, $wp_manga_functions;
	$s         = isset( $_GET['s'] ) ? $_GET['s'] : '';
	$s_genre   = isset( $_GET['genre'] ) ? $_GET['genre'] : array();
	$s_author  = isset( $_GET['author'] ) ? $_GET['author'] : '';
	$s_artist  = isset( $_GET['artist'] ) ? $_GET['artist'] : '';
	$s_release = isset( $_GET['release'] ) ? $_GET['release'] : '';
	$s_status  = isset( $_GET['status'] ) ? $_GET['status'] : '';

	$s_orderby = isset( $_GET['m_orderby'] ) ? $_GET['m_orderby'] : 'latest';
	$s_paged   = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;

	$s_args = array(
		's'        => $s,
		'orderby'  => $s_orderby,
		'paged'    => $s_paged,
		'template' => 'search'
	);

	if ( ! empty( $s_status ) ) {
		$s_args['meta_key']   = 'wp_manga_status';
		$s_args['meta_value'] = $s_status;
	}

	$tax_query = array();

	if ( ! empty( $s_genre ) ) {
		$tax_args = array(
			'taxonomy' => 'wp-manga-genre',
			'slug'     => $s_genre
		);

		$queried_genre = new WP_Term_Query( $tax_args );
		$genres        = array();

		if ( ! empty( $queried_genre->get_terms() ) ) {
			foreach ( $queried_genre->get_terms() as $genre ) {
				$genres[] = $genre->term_id;
			}
		}

		if ( ! empty( $genres ) ) {
			$tax_query[] = array(
				'taxonomy' => 'wp-manga-genre',
				'field'    => 'term_id',
				'terms'    => $genres
			);
		}
	}

	if ( ! empty( $s_author ) ) {
		$tax_args = array(
			'taxonomy' => 'wp-manga-author',
			'search'   => $s_author
		);

		$queried_author = new WP_Term_Query( $tax_args );
		$authors        = array();

		if ( ! empty( $queried_author->get_terms() ) ) {
			foreach ( $queried_author->get_terms() as $author_term ) {
				$authors[] = $author_term->term_id;
			}
		}

		if ( ! empty( $s_author ) ) {
			$tax_query[] = array(
				'taxonomy' => 'wp-manga-author',
				'field'    => 'term_id',
				'terms'    => $authors
			);
		}
	}

	if ( ! empty( $s_artist ) ) {
		$tax_args = array(
			'taxonomy' => 'wp-manga-artist',
			'search'   => $s_artist
		);

		$queried_artist = new WP_Term_Query( $tax_args );
		$artists        = array();

		if ( ! empty( $queried_artist->get_terms() ) ) {
			foreach ( $queried_artist->get_terms() as $artist ) {
				$artists[] = $artist->term_id;
			}
		}

		if ( ! empty( $s_artist ) ) {
			$tax_query[] = array(
				'taxonomy' => 'wp-manga-artist',
				'field'    => 'term_id',
				'terms'    => $artists
			);
		}
	}

	if ( ! empty( $s_release ) ) {
		$tax_args = array(
			'taxonomy' => 'wp-manga-release',
			'search'   => $s_release,
		);

		$queried_release = new WP_Term_Query( $tax_args );
		$releases        = array();

		if ( ! empty( $queried_release->get_terms() ) ) {
			foreach ( $queried_release->get_terms() as $release ) {
				$releases[] = $release->term_id;
			}
		}

		if ( ! empty( $s_release ) ) {
			$tax_query = array(
				'taxonomy' => 'wp-manga-release',
				'field'    => 'term_id',
				'terms'    => $releases
			);
		}
	}

	if ( ! empty( $tax_query ) ) {
		$s_args['tax_query'] = array(
			'relation' => 'OR',
			$tax_query
		);
	}

	$s_query = madara_manga_query( $s_args );

	$search_header_background = madara_output_background_options( 'search_header_background' );

?>
    <!--<header class="site-header">-->
    <div class="c-search-header__wrapper" style="<?php echo ( $search_header_background != '' ) ? $search_header_background : 'background-image: url(' . get_parent_theme_file_uri( '/images/bg-search.jpg' ) . ');'; ?>">
        <div class="container">

			<?php get_template_part( 'madara-core/manga', 'breadcrumb' ); ?>

            <div class="search-content">
                <form role="search" method="get" class="search-form">
                    <label> <span class="screen-reader-text"><?php esc_html_e( 'Search for:', 'madara' ); ?></span>
                        <input type="search" class="search-field" placeholder="<?php esc_html_e( 'Search...', 'madara' ); ?>" value="<?php echo esc_attr( $s ); ?>" name="s">
                        <input type="hidden" name="post_type" value="wp-manga">
                        <script>
							jQuery(document).ready(function ($) {
								$('form.search-form input.search-field[name="s"]').keyup(function () {
									var s = $('form.search-form input.search-field[name="s"]').val();
									$('form.search-advanced-form input[name="s"]').val(s);
								});
							});
                        </script>
                    </label>
                    <input type="submit" class="search-submit" value="<?php esc_html_e( 'Search', 'madara' ); ?>">
                </form>
                <a class="btn-search-adv collapsed" data-toggle="collapse" data-target="#search-advanced"><?php esc_html_e( 'Advanced', 'madara' ); ?>
                    <span class="icon-search-adv"></span></a>
            </div>
            <div class="collapse" id="search-advanced">
                <form action="" method="get" role="form" class="search-advanced-form">
                    <input type="hidden" name="s" id="adv-s" value="">
                    <input type="hidden" name="post_type" value="wp-manga">
                    <!-- Manga Genres -->
                    <div class="form-group checkbox-group row">
						<?php
							$genre_args = array(
								'taxonomy'   => 'wp-manga-genre',
								'hide_empty' => false
							);

							$genres = get_terms( $genre_args );

							if ( ! empty( $genres ) ) {
								foreach ( $genres as $genre ) {
									$checked = array_search( $genre->slug, $s_genre ) !== false ? 'checked' : '';
									?>
                                    <div class="checkbox col-xs-6 col-sm-4 col-md-2 ">
                                        <input id="<?php echo esc_attr( $genre->slug ); ?>" value="<?php echo esc_attr( $genre->slug ); ?>" name="genre[]" type="checkbox" <?php echo esc_attr( $checked ); ?>/>
                                        <label for="<?php echo esc_attr( $genre->slug ); ?>"> <?php echo esc_html( $genre->name ); ?> </label>
                                    </div>
									<?php
								}
							}
						?>

                    </div>
                    <!-- Manga Author -->
                    <div class="form-group">
                        <span><?php esc_html_e( 'Tác giả', 'madara' ); ?></span>
                        <input type="text" class="form-control" name="author" placeholder="Author" value="<?php echo esc_attr( $s_author ); ?>">
                    </div>
                    <!-- Manga Artist -->
                    <div class="form-group">
                        <span><?php esc_html_e( 'Họa sĩ', 'madara' ); ?></span>
                        <input type="text" class="form-control" name="artist" placeholder="Artist" value="<?php echo esc_attr( $s_artist ); ?>">
                    </div>
                    <!-- Manga Release -->
                    <div class="form-group">
                        <span><?php esc_html_e( 'Năm xuất bản', 'madara' ); ?></span>
                        <input type="text" class="form-control" name="release" placeholder="Year" value="<?php echo esc_attr( $s_release ); ?>">
                    </div>
                    <!-- Manga Status -->
                    <div class="form-group">
                        <span><?php esc_html_e( 'Trạng thái', 'madara' ); ?></span>
                        <div class="checkbox-inline">
                            <input id="complete" type="checkbox" name="status" <?php checked( 'complete', $s_status ); ?> />
                            <label for="complete"><?php esc_html_e( 'Hoàn thành', 'madara' ); ?></label>
                        </div>
                        <div class="checkbox-inline">
                            <input id="on-going" type="checkbox" name="status" <?php checked( 'on-going', $s_status ); ?> />
                            <label for="on-going"><?php esc_html_e( 'Chưa xong', 'madara' ); ?></label>
                        </div>
                    </div>
                    <div class="form-group group-btn">
                        <button type="submit" class="c-btn c-btn_style-1 search-adv-submit"><?php esc_html_e( 'Search', 'madara' ); ?></button>
                        <button type="submit" class="c-btn c-btn_style-2 search-adv-reset"><?php esc_html_e( 'Reset', 'madara' ); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div><!--</header>-->
    <script type="text/javascript">
		var manga_args = <?php echo str_replace( '\/', '/', json_encode( $s_args ) ); ?>;
    </script>
    <div class="c-page-content">
        <div class="content-area">
            <div class="container">
                <div class="row">
                    <div class="main-col col-md-12 sidebar-hidden">

						<?php get_template_part( 'html/main-bodytop' ); ?>

                        <!-- container & no-sidebar-->
                        <div class="main-col-inner">
							<?php
								if ( $s_query->have_posts() ) {
									?>
                                    <div class="search-wrap">
                                        <div class="tab-wrap">
                                            <div class="c-blog__heading style-2 font-heading">
                                                <h4>
                                                    <i class="<?php madara_default_heading_icon(); ?>"></i> <?php echo sprintf( _n( '%s result', '%s results', $s_query->found_posts, 'madara' ), $s_query->found_posts ); ?>
                                                </h4>
												<?php get_template_part( 'madara-core/manga-filter' ); ?>
                                            </div>
                                        </div>
                                        <!-- Tab panes -->
                                        <div class="tab-content-wrap">
                                            <div role="tabpanel" class="c-tabs-item">
                                                <div class="row">
													<?php

														while ( $s_query->have_posts() ) {

															$s_query->the_post();

															get_template_part( 'madara-core/content/content', 'search' );

														}

														wp_reset_postdata();
													?>
                                                </div>
												<?php
													$madara_pagination = new App\Views\ParsePagination();
													$madara_pagination->renderPageNavigation( '.c-tabs-item .row', 'madara-core/content/content-search', $s_query );
												?>
                                            </div>
                                        </div>
                                    </div>
									<?php
								} else {
									get_template_part( 'madara-core/content/content', 'none' );
								}
							?>
                        </div>

						<?php get_template_part( 'html/main-bodybottom' ); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
<?php

	/**
	 * madara_after_main_page hook
	 *
	 * @hooked madara_output_after_main_page - 90
	 * @hooked madara_output_bottom_sidebar - 91
	 *
	 * @author
	 * @since 1.0
	 * @code     Madara
	 */
	do_action( 'madara_after_main_page' );

	get_footer();
