<?php

	if ( ! is_user_logged_in() ) {
		return;
	}

	global $wp_manga_chapter, $wp_manga_functions;

	$user_id       = get_current_user_id();
	$bookmarks     = get_user_meta( $user_id, '_wp_manga_bookmark', true );
	$reading_style = $GLOBALS['wp_manga_functions']->get_reading_style();
	$reading_style = ! empty( $reading_style ) ? $reading_style : 'paged';

?>

<table class="table table-hover list-bookmark">
    <thead>
    <tr>
        <th><?php esc_html_e( 'Manga Name', WP_MANGA_TEXTDOMAIN ); ?></th>
        <th><?php esc_html_e( 'Time', WP_MANGA_TEXTDOMAIN ); ?></th>
        <th><?php esc_html_e( 'Edit', WP_MANGA_TEXTDOMAIN ); ?></th>
    </tr>
    </thead>
    <tbody>

	<?php if ( ! empty( $bookmarks ) ) {
		foreach ( $bookmarks as $bookmark ) {

			$post = get_post( intval( $bookmark['id'] ) );

			if ( $post == null || $post->post_status !== 'publish' ) {
				continue;
			}

			$post_id = $bookmark['id'];

			//get chapter
			if ( class_exists( 'WP_MANGA' ) && ! empty( $bookmark['c'] ) && ! is_array( $bookmark['c'] ) ) {

				$chapter = $wp_manga_chapter->get_chapter_by_id( $post->ID, $bookmark['c'] );

			}

			$permalink = get_the_permalink( $post_id );
			$title     = get_the_title( $post_id );
			$time      = $bookmark['t'];

			?>
            <tr>
                <td>
                    <div class="mange-name">
                        <div class="item-thumb">
							<?php if ( has_post_thumbnail( $post_id ) ) { ?>
                                <a href="<?php echo esc_url( $permalink ); ?>" title="<?php echo esc_attr( $title ); ?>">
									<?php echo get_the_post_thumbnail( $post_id, array( 75, 106 ) ); ?>
                                </a>
							<?php } ?>
                        </div>
                        <div class="item-infor">
                            <div class="post-title">
								<?php if ( ! empty( $title ) ) { ?>
                                    <h3>
                                        <a href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_attr( $title ); ?></a>
                                    </h3>
								<?php } ?>
                            </div>
							<?php if ( ! empty( $chapter ) ) {
								$chapter_url = $wp_manga_functions->build_chapter_url( $post_id, $chapter['chapter_slug'], $reading_style );
								?>
                                <div class="chapter">
                                    <span><a href="<?php echo esc_url( $chapter_url ); ?>"><?php echo isset( $chapter['chapter_name'] ) ? esc_html( $chapter['chapter_name'] ) : ''; ?></a></span>

									<?php if ( ! empty( $bookmark['p'] ) && $reading_style == 'paged' ) {
										$paged_url = $wp_manga_functions->build_chapter_url( $post_id, $chapter['chapter_slug'], $reading_style, null, $bookmark['p'] );
										?>
                                        <span><a href="<?php echo esc_url( $paged_url ); ?>"><?php esc_html_e( 'page ', WP_MANGA_TEXTDOMAIN ); ?><?php echo esc_html( $bookmark['p'] ) ?></a></span>
									<?php } ?>

                                </div>
							<?php } ?>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="post-on">
						<?php echo esc_html( $GLOBALS['wp_manga_functions']->get_time_diff( $time, true ) ); ?>
                    </div>
                </td>
                <td>
                    <div class="action">
                        <div class="checkbox">
                            <input id="<?php echo esc_attr( $post_id ); ?>" class="bookmark-checkbox" value="<?php echo esc_attr( $post_id ); ?>" type="checkbox">
                            <label for="<?php echo esc_attr( $post_id ); ?>"></label>
                        </div>
                        <a class="wp-manga-delete-bookmark" href="javascript:void(0)" data-action="delete-bookmark" data-post-id="<?php echo esc_attr( $post_id ); ?>"><i class="ion-ios-close"></i></a>
                    </div>
                </td>
            </tr>
			<?php
		}
	} ?>
	<?php if ( ! empty( $bookmarks ) ) { ?>

		<?php foreach ( $bookmarks as $bookmark_id ) {
			$post_id = get_post_meta( $bookmark_id, '_post_id', true );

			?>

		<?php } ?>

        <tr>
            <td colspan="3">
                <div class="remove-all pull-right">
                    <div class="checkbox">
                        <input id="checkall" type="checkbox">
                        <label for="checkall"><?php esc_html_e( 'Check all', WP_MANGA_TEXTDOMAIN ); ?></label>
                    </div>
                    <button type="button" id="delete-bookmark-manga" class="btn btn-default"><?php esc_html_e( 'Delete', WP_MANGA_TEXTDOMAIN ); ?></button>
                </div>
            </td>
        </tr>

	<?php } else { ?>
        <tr>
            <td colspan="3" style="text-align:center;"> <?php esc_html_e( 'No Manga Bookmarked', WP_MANGA_TEXTDOMAIN ); ?> </td>
        </tr>
	<?php } ?>
    </tbody>
</table>
