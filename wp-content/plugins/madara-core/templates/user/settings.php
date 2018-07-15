<?php

	if ( ! is_user_logged_in() ) {
		return;
	}

	global $wp_manga_template;

	$tab_pane = isset( $_POST['tab-pane'] ) ? $_POST['tab-pane'] : 'boomarks';

?>
<div class="wp-manga-section">
	<div class="row settings-page">
	    <div class="col-md-3 col-sm-3">
	        <div class="nav-tabs-wrap">
	            <ul class="nav nav-tabs">
	                <li class="<?php echo ( $tab_pane == 'boomarks' ) ? 'active' : ''; ?>">
	                    <a href="#boomarks" data-toggle="tab"><i class="ion-android-bookmark"></i><?php esc_html_e( 'Bookmarks', WP_MANGA_TEXTDOMAIN ); ?>
	                    </a></li>
	                <li class="<?php echo ( $tab_pane == 'reader' ) ? 'active' : ''; ?>">
	                    <a href="#reader" data-toggle="tab"><i class="ion-gear-b"></i><?php esc_html_e( 'Reader Settings', WP_MANGA_TEXTDOMAIN ); ?>
	                    </a></li>
	            </ul>
	        </div>
	    </div>
	    <div class="col-md-9 col-sm-9">
	        <div class="tabs-content-wrap">
	            <div class="tab-content">
	                <div class="tab-pane <?php echo ( $tab_pane == 'boomarks' ) ? 'active' : ''; ?>" id="boomarks">
						<?php $wp_manga_template->load_template( 'user/page/bookmark' ); ?>
	                </div>
	                <div class="tab-pane <?php echo $tab_pane == 'reader' ? 'active' : ''; ?>" id="reader">
						<?php $wp_manga_template->load_template( 'user/page/reader-settings' ); ?>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>
</div>
