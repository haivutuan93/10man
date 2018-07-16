<?php
	/*
	* Content for manga search
	*/

	global $wp_query;

	$wp_manga           = madara_get_global_wp_manga();
	$wp_manga_functions = madara_get_global_wp_manga_functions();
	$wp_manga_settings  = madara_get_global_wp_manga_setting();

	$thumb_size = array( 193, 278 );

	$manga_reading_style = $wp_manga_functions->get_reading_style();
        $id_post = get_the_ID();
	$manga_alternative   = get_post_meta( $id_post, '_wp_manga_alternative', true );
	$manga_author        = get_the_terms( $id_post, 'wp-manga-author' );
	$manga_artist        = get_the_terms( $id_post, 'wp-manga-artist' );
	$manga_genre         = get_the_terms( $id_post, 'wp-manga-genre' );
	$manga_status        = get_post_meta( $id_post, '_wp_manga_status', true );
	$manga_release       = get_the_terms( $id_post, 'wp-manga-release' );
?>
<div class="c-tabs-item__content">
    <div class="col-sm-2 col-md-2">
        <div class="tab-thumb c-image-hover">
			<?php
				if ( has_post_thumbnail() ) {
					?>
                    <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
						<?php echo madara_thumbnail( $thumb_size ); ?>
                    </a>
					<?php
				}
			?>
        </div>
    </div>
    <div class="col-sm-10 col-md-10">
        <div class="tab-summary">
            <div class="post-title">
                <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
            </div>
            <div class="post-content">
				<?php

					if ( ! empty( $manga_alternative ) ) {
						?>

                        <div class="post-content_item mg_alternative">
                            <div class="summary-heading">
                                <h5>
									<?php esc_html_e( 'Tên khác', 'madara' ); ?>
                                </h5>
                            </div>
                            <div class="summary-content">
								<?php echo esc_html( $manga_alternative ); ?>
                            </div>
                        </div>

						<?php
					}
				?>
				<?php
					//var_dump( $manga_author );
					if ( ! is_wp_error( $manga_author ) && ! empty( $manga_author ) ) {
						?>
                        <div class="post-content_item mg_author">
                            <div class="summary-heading">
                                <h5>
									<?php esc_html_e( 'Tác giả', 'madara' ); ?>
                                </h5>
                            </div>
                            <div class="summary-content">
								<?php
									$authors = array();
									foreach ( $manga_author as $author ) {
										$authors[] = '<a href="' . get_term_link( $author ) . '">' . esc_html( $author->name ) . '</a>';
									}

									echo implode( ', ', $authors );
								?>
                            </div>
                        </div>
						<?php
					}

				?>

				<?php
					if ( ! is_wp_error( $manga_artist ) && ! empty( $manga_artist ) ) {
						?>
                        <div class="post-content_item mg_artists">
                            <div class="summary-heading">
                                <h5>
									<?php esc_html_e( 'Họa sĩ', 'madara' ); ?>
                                </h5>
                            </div>

                            <div class="summary-content">
								<?php
									$artists = array();
									foreach ( $manga_artist as $artist ) {
										$artists[] = '<a href="' . get_term_link( $artist ) . '">' . esc_html( $artist->name ) . '</a>';
									}

									echo implode( ', ', $artists );
								?>
                            </div>
                        </div>
						<?php
					}
				?>
				<?php
					if ( ! is_wp_error( $manga_genre ) && ! empty( $manga_genre ) ) {
						?>
                        <div class="post-content_item mg_genres">
                            <div class="summary-heading">
                                <h5>
									<?php esc_html_e( 'Thể loại', 'madara' ); ?>
                                </h5>
                            </div>
                            <div class="summary-content">
								<?php
									$genres = array();
									foreach ( $manga_genre as $genre ) {
										$genres[] = '<a href="' . get_term_link( $genre ) . '">' . esc_html( $genre->name ) . '</a>';
									}

									echo implode( ', ', $genres );
								?>
                            </div>
                        </div>
						<?php
					}
				?>
				<?php
					if ( ! empty( $manga_status ) ) {
						?>
                        <div class="post-content_item mg_status">
                            <div class="summary-heading">
                                <h5>
									<?php esc_html_e( 'Tình trạng', 'madara' ); ?>
                                </h5>
                            </div>
                            <div class="summary-content">
								<?php
									$status = $wp_manga_functions->get_manga_status( get_the_ID() );
									echo esc_html( $status ); ?>
                            </div>
                        </div>
						<?php
					}
				?>
				<?php
					if ( ! is_wp_error( $manga_release ) && ! empty( $manga_release ) ) {
						?>
                        <div class="post-content_item mg_release">
                            <div class="summary-heading">
                                <h5>
									<?php esc_html_e( 'Xuất bản năm', 'madara' ); ?>
                                </h5>
                            </div>
                            <div class="summary-content release-year">
								<?php
									$releases = array();
									foreach ( $manga_release as $release ) {
										$releases[] = '<a href="' . get_term_link( $release ) . '">' . esc_html( $release->name ) . '</a>';
									}

									echo implode( ', ', $releases );
								?>
                            </div>
                        </div>
						<?php
					}
				?>
            </div>
        </div>
        <div class="tab-meta">
			<?php
				//Get latest chapter
				$chapter = $wp_manga_functions->get_latest_chapters( get_the_ID(), null, 2 );
				if ( ! empty( $chapter ) ) {
					$latest_chapter     = $chapter[0];
					$latest_chapter_url = $wp_manga_functions->build_chapter_url( get_the_ID(), $latest_chapter['chapter_slug'], 'paged' );
					?>
                    <div class="meta-item latest-chap">
						<?php if ( ! empty( $latest_chapter['chapter_name'] ) ) { ?>
                            <span class="font-meta"><?php echo esc_html__( 'Mới nhất', 'madara' ); ?> </span>
                            <span class="font-meta chapter"><a href="<?php echo esc_url( $latest_chapter_url ); ?>"><?php echo esc_html( $latest_chapter['chapter_name'] ); ?></a></span>
						<?php } ?>
                    </div>
					<?php
					$update_time = ! empty( $latest_chapter['date'] ) ? $latest_chapter['date'] : '';
					if ( ! empty( $update_time ) ) {
						?>
                        <div class="meta-item post-on">
                            <span class="font-meta"><?php echo esc_html( $update_time ); ?></span>
                        </div>
						<?php
					}
					?>
                    <div class="meta-item rating">
						<?php
							$wp_manga_functions->manga_rating_display( get_the_ID() );
						?>
                    </div>
					<?php
				}
			?>
        </div>
    </div>
</div>
