<?php
	if ( ! is_user_logged_in() ) {
		return;
	}

	$user_id = get_current_user_id();
	//update reading settings
	$reading_style = isset( $_POST['_manga_reading_style'] ) ? $_POST['_manga_reading_style'] : $GLOBALS['wp_manga_functions']->get_reading_style();
	$img_per_page  = isset( $_POST['_manga_img_per_page'] ) ? $_POST['_manga_img_per_page'] : madara_get_img_per_page();

	if ( isset( $_POST['tab-pane'] ) && $_POST['tab-pane'] == 'reader' ) {
		update_user_meta( $user_id, '_manga_reading_style', $reading_style );
		update_user_meta( $user_id, '_manga_img_per_page', $img_per_page );
		$is_update = true;
	}

?>

<?php if ( isset( $is_update ) && $is_update == true ) { ?>
    <div class="alert alert-success alert-dismissable">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <strong><?php esc_html_e( 'Success!', 'madara' ); ?></strong> <?php esc_html_e( ' Update successfully', 'madara' ); ?>
    </div>
<?php } ?>

<div class="tab-group-item image_setting">
    <form method="post">
        <div class="settings-heading">
            <h3><?php esc_html_e( 'Reading Settings', 'madara' ); ?></h3>
        </div>
        <div class="tab-item">
            <div class="settings-title">
                <h3><?php esc_html_e( 'Reading Style', 'madara' ); ?></h3>
            </div>
            <div class="checkbox">
                <input id="manga_reading_page" type="radio" name="_manga_reading_style" value="paged" <?php checked( $reading_style, 'paged' ); ?>>
                <label for="manga_reading_page"><?php esc_html_e( 'Paged', 'madara' ); ?></label>
            </div>
            <div class="checkbox">
                <input id="manga_reading_list" type="radio" name="_manga_reading_style" value="list" <?php checked( $reading_style, 'list' ); ?>>
                <label for="manga_reading_list"><?php esc_html_e( 'List', 'madara' ); ?></label>
            </div>
        </div>
        <div class="tab-item">
            <div class="settings-title">
                <h3><?php esc_html_e( 'Images Per Page', 'madara' ); ?></h3>
                <span class="description"><?php esc_html_e( 'Only works with paged reading style', 'madara' ); ?></span>
            </div>
            <div class="checkbox">
                <input id="per_page_default" type="radio" name="_manga_img_per_page" value="1" <?php checked( $img_per_page, '1' ); ?>>
                <label for="per_page_default"><?php esc_html_e( 'Load 1 image per page (default)', 'madara' ); ?></label>
            </div>
            <div class="checkbox">
                <input id="3_per_page" type="radio" name="_manga_img_per_page" value="3" <?php checked( $img_per_page, '3' ); ?>>
                <label for="3_per_page"><?php esc_html_e( 'Load 3 images per page', 'madara' ); ?></label>
            </div>
            <div class="checkbox">
                <input id="6_per_page" type="radio" name="_manga_img_per_page" value="6" <?php checked( $img_per_page, '6' ); ?>>
                <label for="6_per_page"><?php esc_html_e( 'Load 6 images per page', 'madara' ); ?></label>
            </div>
            <div class="checkbox">
                <input id="10_per_page" type="radio" name="_manga_img_per_page" value="10" <?php checked( $img_per_page, '10' ); ?>>
                <label for="10_per_page"><?php esc_html_e( 'Load 10 images per page', 'madara' ); ?></label>
            </div>
        </div>
        <br/>
        <input class="form-control" type="submit" value="<?php esc_html_e( 'Submit', 'madara' ); ?>" id="reading-input-submit">
        <input type="hidden" name="tab-pane" value="reader"/>
    </form>
</div>
