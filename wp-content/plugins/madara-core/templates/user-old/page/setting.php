<?php
/*
* Bookmark template
*/
?>
<?php if ( is_user_logged_in() ): ?>
	<?php global $wp_manga_functions,$wp_manga_template,$wp_manga_setting,$wp_manga_user_actions;
	?>
	<div class="tab-group-item image_setting">
		<form action="" method="POST">
		<div class="settings-heading">
	        <h3><?php echo esc_attr__( 'Reader Settings', WP_MANGA_TEXTDOMAIN ); ?></h3>
	    </div>
		<div class="tab-item">
		    <div class="settings-title">
		        <h3>Default reading style</h3>
		    </div>
		    <?php $reading_style = $wp_manga_functions->get_reading_style(); ?>
		    <div class="checkbox">
		        <input id="reading-paging-style" type="radio" name="reading-style" value="paged" <?php checked( 'paged', $reading_style, true ); ?>>
		        <label for="reading-paging-style"><?php echo esc_attr__( 'Paged', WP_MANGA_TEXTDOMAIN ); ?></label>
		    </div>
		    <div class="checkbox">
		        <input id="reading-list-stype" type="radio" name="reading-style" value="list" <?php checked( 'list', $reading_style, true ); ?>>
		        <label for="reading-list-stype"><?php echo esc_attr__( 'List', WP_MANGA_TEXTDOMAIN ); ?></label>
		    </div>
		</div>
		<?php wp_nonce_field( '_wp_manga_save_user_settings' ) ?>
		<button type="submit" id="user-save-settings" class="btn btn-default"><?php esc_attr_e( 'Save Changes', WP_MANGA_TEXTDOMAIN ) ?></button>
		</form>
	</div>
<?php endif ?>
