<?php
	if ( ! is_user_logged_in() ) {
		return;
	}

	$user_id = get_current_user_id();
	//update reading settings
	$reading_style = isset( $_POST['_manga_reading_style'] ) ? $_POST['_manga_reading_style'] : $GLOBALS['wp_manga_functions']->get_reading_style();

	if ( isset( $_POST['tab-pane'] ) && $_POST['tab-pane'] == 'reader' ) {
		update_user_meta( $user_id, '_manga_reading_style', $reading_style );
		update_user_meta( $user_id, '_manga_img_per_page', $img_per_page );
		$is_update = true;
	}

?>

<?php if ( isset( $is_update ) && $is_update == true ) { ?>
    <div class="alert alert-success alert-dismissable">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <strong><?php esc_html_e( 'Success!', WP_MANGA_TEXTDOMAIN ); ?></strong> <?php esc_html_e( ' Update successfully', WP_MANGA_TEXTDOMAIN ); ?>
    </div>
<?php } ?>

<div class="tab-group-item image_setting">
    <form method="post">
        <div class="settings-heading">
            <h3><?php esc_html_e( 'Reading Settings', WP_MANGA_TEXTDOMAIN ); ?></h3>
        </div>
        <div class="tab-item">
            <div class="settings-title">
                <h3><?php esc_html_e( 'Reading Style', WP_MANGA_TEXTDOMAIN ); ?></h3>
            </div>
            <div class="checkbox">
                <input id="manga_reading_page" type="radio" name="_manga_reading_style" value="paged" <?php checked( $reading_style, 'paged' ); ?>>
                <label for="manga_reading_page"><?php esc_html_e( 'Paged', WP_MANGA_TEXTDOMAIN ); ?></label>
            </div>
            <div class="checkbox">
                <input id="manga_reading_list" type="radio" name="_manga_reading_style" value="list" <?php checked( $reading_style, 'list' ); ?>>
                <label for="manga_reading_list"><?php esc_html_e( 'List', WP_MANGA_TEXTDOMAIN ); ?></label>
            </div>
        </div>
        <br/>
        <input class="form-control" type="submit" value="<?php esc_html_e( 'Submit', WP_MANGA_TEXTDOMAIN ); ?>" id="reading-input-submit">
        <input type="hidden" name="tab-pane" value="reader"/>
    </form>
</div>
