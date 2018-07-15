<?php
	/** Single template of Manga **/

	get_header();

	global $wp_manga, $wp_manga_functions, $wp_manga_template;
	$thumb_size        = array( 193, 278 );

	$alternative       = $wp_manga_functions->get_manga_alternative( get_the_ID() );
	$rank              = $wp_manga_functions->get_manga_rank( get_the_ID() );
	$views             = $wp_manga_functions->get_manga_monthly_views( get_the_ID() );
	$authors           = $wp_manga_functions->get_manga_authors( get_the_ID() );
	$rate              = $wp_manga_functions->get_total_review( get_the_ID() );
	$vote              = $wp_manga_functions->get_total_vote( get_the_ID() );
	$artists           = $wp_manga_functions->get_manga_artists( get_the_ID() );
	$genres            = $wp_manga_functions->get_manga_genres( get_the_ID() );

	$wp_manga_settings = get_option( 'wp_manga_settings' );
	$related_manga     = isset( $wp_manga_settings['related_manga']['state'] ) ? $wp_manga_settings['related_manga']['state'] : null;

?>
<?php do_action( 'before_manga_single' ); ?>

<div class="wp-manga-section">
	<div class="profile-manga" style="background-image:url('<?php echo esc_attr( $wp_manga->wp_manga_bg_img() ); ?>')">
	    <div class="container">
	        <div class="row">
	            <div class="col-xs-12 col-sm-12 col-md-12">
					<?php $wp_manga_template->load_template( 'manga', 'breadcrumb' ); ?>
	                <div class="post-title">
	                    <h3><?php echo esc_html( get_the_title() ); ?></h3>
	                </div>
	                <div class="tab-summary <?php echo has_post_thumbnail() ? '' : 'no-thumb'; ?>">

						<?php if ( has_post_thumbnail() ) { ?>
	                        <div class="summary_image">
	                            <a href="<?php echo get_the_permalink(); ?>">
									<?php echo the_post_thumbnail( $thumb_size ); ?>
	                            </a>
	                        </div>
						<?php } ?>
	                    <div class="summary_content_wrap">
	                        <div class="summary_content">
	                            <div class="post-content">
	                                <div class="post-rating">
										<?php echo $wp_manga_functions->manga_rating( get_the_ID(), true ); ?>
	                                </div>
	                                <div class="post-content_item">
	                                    <div class="summary-heading">
	                                        <h5><?php echo esc_attr__( 'Rating', WP_MANGA_TEXTDOMAIN ); ?></h5>
	                                    </div>
	                                    <div class="summary-content vote-details">
											<?php echo sprintf( _n( 'Average %1s / %2s out of %3s total vote.', 'Average %1s / %2s out of %3s total votes.', $vote, WP_MANGA_TEXTDOMAIN ), $rate, '5', $vote ); ?>
	                                    </div>
	                                </div>
	                                <div class="post-content_item">
	                                    <div class="summary-heading">
	                                        <h5>
												<?php echo esc_attr__( 'Rank', WP_MANGA_TEXTDOMAIN ); ?>
	                                        </h5>
	                                    </div>
	                                    <div class="summary-content">
											<?php echo sprintf( _n( ' %1s, it has %2s monthly view', ' %1s, it has %2s monthly views', $views, WP_MANGA_TEXTDOMAIN ), $rank, $views ); ?>
	                                    </div>
	                                </div>
									<?php if ( $alternative ): ?>
	                                    <div class="post-content_item">
	                                        <div class="summary-heading">
	                                            <h5>
													<?php echo esc_attr__( 'Alternative', WP_MANGA_TEXTDOMAIN ); ?>
	                                            </h5>
	                                        </div>
	                                        <div class="summary-content">
												<?php echo $alternative; ?>
	                                        </div>
	                                    </div>
									<?php endif ?>

									<?php if ( $authors ): ?>
	                                    <div class="post-content_item">
	                                        <div class="summary-heading">
	                                            <h5>
													<?php echo esc_attr__( 'Author(s)', WP_MANGA_TEXTDOMAIN ); ?>
	                                            </h5>
	                                        </div>
	                                        <div class="summary-content">
	                                            <div class="author-content">
													<?php echo $authors; ?>
	                                            </div>
	                                        </div>
	                                    </div>
									<?php endif ?>

									<?php if ( $artists ): ?>
	                                    <div class="post-content_item">
	                                        <div class="summary-heading">
	                                            <h5>
													<?php echo esc_attr__( 'Artist(s)', WP_MANGA_TEXTDOMAIN ); ?>
	                                            </h5>
	                                        </div>
	                                        <div class="summary-content">
	                                            <div class="artist-content">
													<?php echo $artists; ?>
	                                            </div>
	                                        </div>
	                                    </div>
									<?php endif ?>

									<?php if ( $genres ): ?>
	                                    <div class="post-content_item">
	                                        <div class="summary-heading">
	                                            <h5>
													<?php echo esc_attr__( 'Genre(s)', WP_MANGA_TEXTDOMAIN ); ?>
	                                            </h5>
	                                        </div>
	                                        <div class="summary-content">
	                                            <div class="genres-content">
													<?php echo $genres; ?>
	                                            </div>
	                                        </div>
	                                    </div>
									<?php endif ?>

									<?php $type = $wp_manga_functions->get_manga_type( get_the_ID() ); ?>
									<?php if ( $type ): ?>
	                                    <div class="post-content_item">
	                                        <div class="summary-heading">
	                                            <h5>
													<?php echo esc_attr__( 'Type', WP_MANGA_TEXTDOMAIN ); ?>
	                                            </h5>
	                                        </div>
	                                        <div class="summary-content">
												<?php echo $type; ?>
	                                        </div>
	                                    </div>
									<?php endif ?>
	                            </div>
	                            <div class="post-status">

	                                <div class="post-content_item">
	                                    <div class="summary-heading">
	                                        <h5>
												<?php echo esc_attr__( 'Release', WP_MANGA_TEXTDOMAIN ); ?>
	                                        </h5>
	                                    </div>
	                                    <div class="summary-content">
											<?php echo $wp_manga_functions->get_manga_release( get_the_ID() ); ?>
	                                    </div>
	                                </div>

	                                <div class="post-content_item">
	                                    <div class="summary-heading">
	                                        <h5>
												<?php echo esc_attr__( 'Status', WP_MANGA_TEXTDOMAIN ); ?>
	                                        </h5>
	                                    </div>
	                                    <div class="summary-content">
											<?php echo $wp_manga_functions->get_manga_status( get_the_ID() ); ?>
	                                    </div>
	                                </div>

	                                <div class="manga-action">
	                                    <div class="count-comment">
	                                        <div class="action_icon">
	                                            <a href="#manga-discission"><i class="ion-chatbubble-working"></i></a>
	                                        </div>
	                                        <div class="action_detail">
												<?php $comments_count = wp_count_comments( get_the_ID() ); ?>
	                                            <span><?php printf( _n( '%s comment', '%s comments', $comments_count->approved, WP_MANGA_TEXTDOMAIN ), $comments_count->approved ); ?></span>
	                                        </div>
	                                    </div>
	                                    <div class="add-bookmark">
	                                        <?php echo $wp_manga_functions->create_bookmark_link( get_the_ID() ); ?>
	                                    </div>
	                                </div>

	                            </div>
	                        </div>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>

	<div class="c-page-content style-1">
	    <div class="content-area">
	        <div class="container">
	            <div class="row">
	                <div class="main-col col-md-8 col-sm-8">
	                    <!-- container & no-sidebar-->
	                    <div class="main-col-inner">
	                        <div class="c-page">
	                            <!-- <div class="c-page__inner"> -->
	                            <div class="c-page__content">

									<?php if ( get_the_content() != '' ) { ?>

	                                    <div class="c-blog__heading style-2 font-heading">

	                                        <h4>
	                                            <i class="ion-ios-star"></i>
												<?php echo esc_attr__( 'Summary', WP_MANGA_TEXTDOMAIN ); ?>
	                                        </h4>
	                                    </div>

	                                    <div class="description-summary">
	                                        <div class="summary__content">
												<?php the_content(); ?>
	                                        </div>
										</div>

									<?php } ?>

	                                <div class="c-blog__heading style-2 font-heading">

	                                    <h4>
	                                        <i class="ion-ios-star"></i>
											<?php echo esc_attr__( 'LATEST MANGA RELEASES', WP_MANGA_TEXTDOMAIN ); ?>
	                                    </h4>
	                                </div>
	                                <div class="page-content-listing single-page">
	                                    <div class="listing-chapters_wrap">
											<?php $manga = $wp_manga_functions->get_all_chapters( get_the_ID() ); ?>
											<?php if ( $manga ) : ?>
	                                            <ul class="main version-chap">
													<?php
														$single   = isset( $manga['0']['chapters'] ) ? $manga['0']['chapters'] : null;
														if ( $single ) : ?><?php foreach ( $single as $chapter ) :
															$style = $wp_manga_functions->get_reading_style();
															$link = $wp_manga_functions->build_chapter_url( get_the_ID(), $chapter['chapter_slug'], $style );
															?>
	                                                        <li class="wp-manga-chapter">
	                                                            <a href="<?php echo $link ?>">
																	<?php echo isset( $chapter['chapter_name'] ) ? esc_attr( $chapter['chapter_name'] . $wp_manga_functions->filter_extend_name( $chapter['chapter_name_extend'] ) ) : ''; ?>
	                                                            </a><span class="chapter-release-date"><i><?php echo isset( $chapter['date'] ) ? $wp_manga_functions->get_time_diff( $chapter['date'] ) : ''; ?></i></span>
	                                                        </li>
														<?php endforeach; ?><?php unset( $manga['0'] ); endif;
													?>

													<?php

														if ( ! empty( $manga ) ) {

															$manga = array_reverse( $manga );

															foreach (
																$manga

																as $vol_id => $vol
															) {

																$chapters = isset( $vol['chapters'] ) ? $vol['chapters'] : null;

																$chapters_child_class = $chapters ? 'has-child' : 'no-child';
																?>

	                                                            <li class="<?php echo $chapters ? 'parent has-child' : 'parent no-child';
																	echo isset( $first_volume ) ? '' : ' active'; ?>">

																	<?php echo isset( $vol['volume_name'] ) ? '<a href="javascript:void(0)" class="' . $chapters_child_class . '">' . $vol['volume_name'] . '</a>' : ''; ?>
																	<?php

																		if ( $chapters ) { ?>
	                                                                        <ul class="sub-chap list-chap" <?php echo isset( $first_volume ) ? '' : ' style="display: block;"'; ?> >

																				<?php $chapters = array_reverse( $chapters ); ?>

																				<?php foreach ( $chapters as $chapter ) {
																					$style = $wp_manga_functions->get_reading_style();

																					$link          = $wp_manga_functions->build_chapter_url( get_the_ID(), $chapter['chapter_slug'], $style );
																					$c_extend_name = $wp_manga_functions->filter_extend_name( $chapter['chapter_name_extend'] );
																					?>
	                                                                                <li class="wp-manga-chapter">
	                                                                                    <a href="<?php echo $link ?>">
																							<?php echo esc_html( $chapter['chapter_name'] . $c_extend_name ) ?>
	                                                                                    </a><span class="chapter-release-date"><i><?php echo $wp_manga_functions->get_time_diff( $chapter['date'] ) ?></i></span>
	                                                                                </li>
																				<?php } ?>
	                                                                        </ul>
																		<?php } else { ?>
	                                                                        <span class="no-chapter"><?php echo esc_attr__( 'There is no chapters', WP_MANGA_TEXTDOMAIN ); ?></span>
																		<?php } ?>
	                                                            </li>
																<?php $first_volume = false; ?><?php } //endforeach; ?><?php } //endif-empty( $volume);
													?>
	                                            </ul>
											<?php else : ?><?php echo esc_attr__( 'Manga has no chapter yet.', WP_MANGA_TEXTDOMAIN ); ?><?php endif; ?>
											<?php echo '<div class="c-chapter-readmore"><span class="btn btn-link chapter-readmore">' . esc_html__( 'Show more ', WP_MANGA_TEXTDOMAIN ) . '</span></div>' ?>
	                                    </div>
	                                </div>
	                            </div>
	                            <!-- </div> -->
	                        </div>
	                        <!-- comments-area -->
							<?php do_action( 'wp_manga_discusion' ); ?>
	                        <!-- END comments-area -->

							<?php

								if ( $related_manga == 1 ) {
									$wp_manga_template->load_template( 'manga', 'related' );
								}

								$wp_manga->wp_manga_get_tags();
							?>

	                    </div>
	                </div>
					<div class="sidebar-col col-md-4 col-sm-4">
						<?php dynamic_sidebar( 'manga_single_sidebar' ); ?>
					</div>
	            </div>
	        </div>
	    </div>
	</div>
</div>

<?php do_action( 'after_manga_single' ); ?>
<?php get_footer(); ?>
