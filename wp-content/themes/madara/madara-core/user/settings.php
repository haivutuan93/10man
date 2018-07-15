<?php

	if ( ! is_user_logged_in() ) {
		return;
	}

	$wp_manga_template = madara_get_global_wp_manga_template();

	$tab_pane = isset( $_POST['tab-pane'] ) ? $_POST['tab-pane'] : 'boomarks';

?>
<script type="text/javascript">
	jQuery(function($){
		var hash = window.location.hash;
		hash && $('.settings-page ul.nav a[href="' + hash + '"]').tab('show');

		$('.settings-page .nav-tabs a').click(function (e) {
			$(this).tab('show');
			var scrollmem = $('body').scrollTop() || $('html').scrollTop();
			window.location.hash = this.hash;
			$('html,body').scrollTop(scrollmem);
		});
	});
</script>
<div class="row settings-page">
    <div class="col-md-3 col-sm-3">
        <div class="nav-tabs-wrap">
            <ul class="nav nav-tabs">
                <li class="<?php echo ( $tab_pane == 'boomarks' ) ? 'active' : '' ; ?>">
                    <a href="#boomarks" data-toggle="tab"><i class="ion-android-bookmark"></i><?php esc_html_e( 'Đánh dấu', 'madara' ); ?>
                    </a>
				</li>
                <li class="<?php echo ( $tab_pane == 'history' ) ? 'active' : ''; ?>">
                    <a href="#history" data-toggle="tab"><i class="ion-android-alarm-clock"></i><?php esc_html_e( 'Lịch sử', 'madara' ); ?>
                    </a>
				</li>
                
                <li class="<?php echo ( $tab_pane == 'reader' ) ? 'active' : ''; ?>">
                    <a href="#account" data-toggle="tab"><i class="ion-android-person"></i><?php esc_html_e( 'Cài đặt', 'madara' ); ?>
                    </a>
				</li>
				<?php do_action('madara_user_nav_tabs', $tab_pane ); ?>
            </ul>
        </div>
    </div>
    <div class="col-md-9 col-sm-9">
        <div class="tabs-content-wrap">
            <div class="tab-content">
                <div class="tab-pane <?php echo ( $tab_pane == 'boomarks' ) ? 'active' : ''; ?>" id="boomarks">
					<?php $wp_manga_template->load_template( 'user/page/bookmark' ); ?>
                </div>
                <div class="tab-pane <?php echo ( $tab_pane == 'history' ) ? 'active' : ''; ?>" id="history">
					<?php $wp_manga_template->load_template( 'user/page/history' ); ?>
                </div>
                <div class="tab-pane <?php echo ( $tab_pane == 'reader' ) ? 'active' : ''; ?>" id="reader">
					<?php $wp_manga_template->load_template( 'user/page/reader-settings' ); ?>
                </div>
                <div class="tab-pane <?php echo ( $tab_pane == 'account' ) ? 'active' : ''; ?>" id="account">
					<?php $wp_manga_template->load_template( 'user/page/account-settings' ); ?>
                </div>
				<?php do_action('madara_user_nav_contents', $tab_pane ); ?>
            </div>
        </div>
    </div>
</div>
