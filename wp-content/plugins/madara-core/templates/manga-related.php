<?php
	global $wp_manga_setting;
	$related_manga = $wp_manga_setting->get_manga_option('related_manga');
	$related_by        = isset( $related_manga['related_by'] ) ? $related_manga['related_by'] : 'related_genre';
	$thumb_size        = array( 75, 106 );

	if ( $related_by ) {
		$post_id = get_the_ID();

		$related_args = array(
			'post_type'      => 'wp-manga',
			'post_status'    => 'publish',
			'posts_per_page' => 4,
			'post__not_in'   => array( $post_id ),
		);

		switch ( $related_by ) {
			case 'related_author':
				$author_terms = wp_get_post_terms( $post_id, 'wp-manga-author' );

				$manga_author = array();

				if ( ! empty( $author_terms ) ) {
					foreach ( $author_terms as $author ) {
						$manga_author[] = $author->term_id;
					}
				}

				$related_args['tax_query'] = array(
					array(
						'taxonomy' => 'wp-manga-author',
						'field'    => 'term_id',
						'terms'    => $manga_author
					)
				);
				break;
			case 'related_year':
				$year_terms = wp_get_post_terms( $post_id, 'wp-manga-release' );

				$manga_year = array();

				if ( ! empty( $year_terms ) ) {
					foreach ( $year_terms as $year ) {
						$manga_year[] = $year->term_id;
					}
				}

				$related_args['tax_query'] = array(
					array(
						'taxonomy' => 'wp-manga-release',
						'field'    => 'term_id',
						'terms'    => $manga_year
					)
				);
				break;
			case 'related_artist' :
				$artists_terms = wp_get_post_terms( $post_id, 'wp-manga-artist' );

				$manga_artists = array();

				if ( ! empty( $artists_terms ) ) {
					foreach ( $artists_terms as $artists ) {
						$manga_artists[] = $artists->term_id;
					}
				}

				$related_args['tax_query'] = array(
					array(
						'taxonomy' => 'wp-manga-artist',
						'field'    => 'term_id',
						'terms'    => $manga_artists
					)
				);
				break;
			case 'related_genre' :

				$genre_terms = wp_get_post_terms( $post_id, 'wp-manga-genre' );

				$genre_id = array();

				if ( ! empty( $genre_terms ) ) {
					foreach ( $genre_terms as $term ) {
						$genre_id[] = $term->term_id;
					}
				}

				$related_args['tax_query'] = array(
					array(
						'taxonomy' => 'wp-manga-genre',
						'field'    => 'term_id',
						'terms'    => $genre_id
					)
				);
				break;
		}

		$related_query = new WP_Query( $related_args );

		if ( $related_query->have_posts() ) {
			?>

            <div class="row c-row related-manga">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="c-blog__heading style-2 font-heading">
                        <h4>
                            <i class="ion-ios-star"></i>
							<?php esc_html_e( 'YOU MAY ALSO LIKE', WP_MANGA_TEXTDOMAIN ) ?></h4>
                    </div>
                </div>


				<?php

					while ( $related_query->have_posts() ) {

						$related_query->the_post();

						$date = get_post_meta( get_the_ID(), '_latest_update', true );

						?>

                        <div class="col-xs-12 col-sm-6 col-md-3">
                            <div class="related-reading-wrap">
                                <div class="related-reading-img widget-thumbnail">
                                    <a title="<?php echo get_the_title(); ?>" href="<?php echo get_the_permalink(); ?>">
										<?php
											if ( has_post_thumbnail( get_the_ID() ) ) {
												echo the_post_thumbnail( $thumb_size );
											}
										?>
                                    </a>
                                </div>
                                <div class="related-reading-content">
                                    <h5 class="widget-title">
                                        <a href="<?php echo get_the_permalink(); ?>" title="<?php echo get_the_title(); ?>">
											<?php echo get_the_title(); ?>
                                        </a>
                                    </h5>
                                </div>
								<?php if ( $date && '' != $date ) { ?>
                                    <div class="post-on font-meta">
                                        <span>
                                            <?php echo date( get_option( 'date_format' ), $date ); ?>
                                        </span>
                                    </div>
								<?php } ?>
                            </div>
                        </div>
						<?php
					}

				?>
            </div>

			<?php
		}

		wp_reset_postdata();
	}
