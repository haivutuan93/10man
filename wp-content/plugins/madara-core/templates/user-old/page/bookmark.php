<?php
/*
* Bookmark template
*/
?>
<?php if ( is_user_logged_in() ) : ?>
<?php
    global $wp_manga_functions,$wp_manga_template,$wp_manga_setting,$wp_manga_user_actions;
	$user_id = get_current_user_id();
	$bookmarks = get_user_meta( $user_id, '_bookmarked', false );

	?>
	<div class="tabs-content-wrap">
		<div class="tab-content">
            <div class="tab-pane active" id="boomarks">
                <table class="table table-hover list-bookmark">
                    <thead>
                        <tr>
                            <th><?php esc_html_e('Manga Name', WP_MANGA_TEXTDOMAIN); ?></th>
                            <th><?php esc_html_e('Time', WP_MANGA_TEXTDOMAIN); ?></th>
                            <th><?php esc_html_e('Edit', WP_MANGA_TEXTDOMAIN); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                    	<?php if ( $bookmarks ): ?>
                    		<?php foreach ( $bookmarks as $bookmark ):
                    		$post_id = get_post_meta( $bookmark, '_post_id', true );
                    		$data    = get_post_meta( $bookmark, '_bookmark_data', true );
                    		$link    = get_the_permalink( $post_id );

                    		if ( !empty( $data['chapter'] ) ) {
                    			$link = $wp_manga_functions->build_chapter_url( $post_id, $data['chapter']['chapter_slug'] );
                    		}

                    		if ( $data['page'] != '' ) {
                                $link = $wp_manga_functions->build_chapter_url( $post_id, $data['chapter']['chapter_slug'], null, null, $data['page'] );
                    		}
							?>
        						<tr id="bookmark-manga-<?php echo esc_attr( $bookmark ); ?>">
		                            <td>
		                                <div class="mange-name">
		                                    <div class="item-thumb">
		                                        <?php echo get_the_post_thumbnail( $post_id, 'manga-thumb-1' ) ?>
		                                    </div>
		                                    <div class="item-infor">
		                                        <div class="post-title">
		                                            <h3><a href="<?php echo esc_url( $link ); ?>"><?php echo get_the_title( $post_id ); ?></a></h3>
		                                        </div>
		                                        <div class="chapter">
		                                        	<?php if ( !empty( $data['chapter'] ) ): ?>
	                                        			<span><?php echo esc_attr( $data['chapter']['chapter_name'] ) ?></span>
		                                        	<?php endif ?>
		                                            <?php if ( $data['page'] != '' ): ?>
	                                        			<span><?php echo esc_attr__( 'page ', WP_MANGA_TEXTDOMAIN ); ?><?php echo esc_attr( $data['page'] ) ?></span>
		                                        	<?php endif ?>
		                                        </div>
		                                    </div>
		                                </div>
		                            </td>
		                            <td>
		                                <div class="post-on">
		                                    <?php
		                                    	$bookmark_time = get_post_meta( $bookmark, '_bookmark_time',  true );
		                                    	echo esc_attr( date( "F j, Y, g:i a", $bookmark_time ) );
		                                    ?>
		                                </div>
		                            </td>
		                            <td>
		                                <div class="action">
		                                    <div class="checkbox">
		                                        <input id="box3" class="bookmark-checkbox" value="<?php echo esc_attr( $bookmark ); ?>" type="checkbox">
		                                        <label for="box3"></label>
		                                    </div>
		                                    <a class="wp-manga-delete-bookmark" href="javascript:void(0)" data-action="delete-bookmark" data-bookmark="<?php echo esc_attr( $bookmark ); ?>"><i class="ion-ios-close"></i></a>
		                                </div>
		                            </td>
		                        </tr>
                    		<?php endforeach ?>
                    	<?php endif ?>
                        <tr>
                            <td colspan="3">
                                <div class="remove-all pull-right">
                                    <div class="checkbox">
                                        <input id="wp-manga-bookmark-checkall" type="checkbox">
                                        <label for="wp-manga-bookmark-checkall"><?php esc_attr_e( 'Check All', WP_MANGA_TEXTDOMAIN ) ?></label>
                                    </div>
                                    <button type="button" id="delete-bookmark-manga" class="btn btn-default"><?php esc_attr_e( 'Delete', WP_MANGA_TEXTDOMAIN ) ?></button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
		</div>
    </div>
<?php endif; ?>
