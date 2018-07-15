<?php

	if ( ! is_user_logged_in() ) {
		return;
	}

	$user_id       = get_current_user_id();
	$history_manga = get_user_meta( $user_id, '_wp_manga_history', true );
	$reading_style = $GLOBALS['wp_manga_functions']->get_reading_style();
?>

<div class="tab-group-item">

	<?php if ( is_array( $history_manga ) && ! empty( $history_manga ) ) { ?>

		<?php
		$thumb_size       = array( 75, 106 );
		$history_manga    = array_reverse( $history_manga );
		$history_manga_id = array_column( $history_manga, 'id' );
		$history_manga    = array_combine( $history_manga_id, $history_manga );

		$posts = get_posts( array(
			'post_type'   => 'wp-manga',
			'post_status' => 'publish',
			'include'     => $history_manga_id
		) );

		if ( !empty( $posts ) ) {

			$last_index = count( $posts ) - 1;

			foreach ( $posts as $post ) {

				if ( ! isset( $index ) ) {
					$index = 0;
				}

				$this_history_manga = $history_manga[ $post->ID ];
				$post_url           = get_the_permalink( $post->ID );

				//get chapter
				if ( class_exists( 'WP_MANGA' ) && ! empty( $this_history_manga['c'] ) && ! is_array( $this_history_manga['c'] ) ) {

					$wp_manga_chapter = madara_get_global_wp_manga_chapter();
					$chapter          = $wp_manga_chapter->get_chapter_by_id( $post->ID, $this_history_manga['c'] );

				}

				if ( $index % 3 == 0 ) {
					?>
	                <div class="tab-item"><div class="row">
					<?php
				}
				?>

	            <div class="col-md-4">
	                <div class="history-content">
	                    <div class="item-thumb">
							<?php if ( has_post_thumbnail( $post->ID ) ) { ?>
	                            <a href="<?php echo esc_url( $post_url ); ?>" title="<?php echo esc_attr( $post->post_title ); ?>">
									<?php echo madara_thumbnail( $thumb_size, $post->ID ); ?>
	                            </a>
							<?php } ?>
	                    </div>
	                    <div class="item-infor">
	                        <div class="settings-title">
	                            <h3>
	                                <a href="<?php echo esc_url( $post_url ); ?>"><?php echo esc_html( $post->post_title ); ?></a>
	                            </h3>
	                        </div>
							<?php if ( ! empty( $chapter ) ) { ?>
	                            <div class="chapter">
									<?php
										$c_url = $GLOBALS['wp_manga_functions']->build_chapter_url( $post->ID, $chapter['chapter_slug'], $reading_style );

										if( !empty( $this_history_manga['i'] ) && $reading_style == 'list' ){
											$c_url .= '#image-' . $this_history_manga['i'];
										}
									?>
	                                <span class="chap">
											<a href="<?php echo esc_url( $c_url ); ?>">
												<?php echo esc_html( $chapter['chapter_name'] ); ?>
											</a>
										</span>
									<?php if ( ! empty( $this_history_manga['p'] ) && $reading_style == 'paged' ) {
										$p_url = add_query_arg( array(
											'manga-paged' => $this_history_manga['p']
										), $c_url ); ?>

	                                    <span class="page">
	                                        <a href="<?php echo esc_url( $p_url ); ?>">
	                                            page <?php echo esc_html( $this_history_manga['p'] ); ?>
	                                        </a>
	                                    </span>
									<?php } ?>
	                            </div>
							<?php } ?>
	                        <div class="post-on font-meta">
								<?php echo esc_html( $GLOBALS['wp_manga_functions']->get_time_diff( $this_history_manga['t'], true ) ); ?>
	                        </div>
	                    </div>
	                    <div class="action">
	                        <a href="javascript:void(0)" class="remove-manga-history" data-manga="<?php echo esc_attr( $post->ID ); ?>"><i class="ion-ios-close"></i></a>
	                    </div>
	                </div>
	            </div>

				<?php
				if ( $index % 3 == 2 || $index == $last_index ) {
					?>
	                </div></div>
					<?php
				}

				$index ++;

			}

			wp_reset_postdata();
		}

	?>
	<?php } ?>

	<?php if( empty( $posts ) ){ ?>
		<span><?php esc_html_e( 'You haven\'t read any manga yet', 'madara' ); ?></span>
	<?php } ?>

</div>
