<?php
	if ( ! is_user_logged_in() ) {
		return;
	}

	$account_resp = isset( $_POST['tab-pane'] ) && $_POST['tab-pane'] == 'account' ? madara_update_user_settings() : null;

	$user_id = get_current_user_id();
	$user    = get_user_by( 'ID', $user_id );

?>
<form method="post">

	<?php if ( $account_resp == true ) { ?>
        <div class="alert alert-success alert-dismissable">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <strong><?php esc_html_e( 'Success!', 'madara' ); ?></strong> <?php esc_html_e( ' Cập nhật thành công', 'madara' ); ?>
        </div>
	<?php } elseif ( $account_resp === false && isset( $_POST['tab-pane'] ) && $_POST['tab-pane'] == 'account' ) { ?>
        <div class="alert alert-danger alert-dismissable">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <strong><?php esc_html_e( 'Failed!', 'madara' ); ?></strong><?php esc_html_e( ' Cập nhật thất bại. Hãy thử lại! ', 'madara' ); ?>
        </div>
	<?php } ?>

    <input type="hidden" value="<?php echo esc_attr( $user_id ); ?>" name="userID">
	<?php wp_nonce_field( '_wp_manga_save_user_settings' ); ?>
    <input type="hidden" name="tab-pane" value="account">
    <div class="tab-group-item">
        <div class="tab-item">
            <div class="choose-avatar">
				<div class="loading-overlay">
					<div class="loading-icon">
						<i class="fas fa-spinner fa-spin"></i>
					</div>
				</div>
				<div class="c-user-avatar">
					<?php echo get_avatar( $user_id, 195 ); ?>
				</div>
            </div>
            <div class="form form-choose-avatar">
                <div class="select-flie">
                    <!--Update Avatar -->
                    <form action="#">
						<?php esc_html_e( 'Chỉ dùng .jpg .png hoặc .gif file', 'madara' ); ?>
                        <label class="select-avata"><input type="file" name="wp-manga-user-avatar"></label>
                        <input type="submit" value="<?php esc_html_e( 'Upload', 'madara' ); ?>" name="wp-manga-upload-avatar" id="wp-manga-upload-avatar">
                    </form>

                </div>
            </div>
        </div>
        <div class="tab-item">

            <div class="settings-title">
                <h3>
					<?php esc_html_e( 'Thay đổi tên hiển thị', 'madara' ); ?>
                </h3>
            </div>
            <div class="form-group row">
                <label for="name-input" class="col-md-3"><?php esc_html_e( 'Tên hiển thị hiện tại', 'madara' ); ?></label>
                <div class="col-md-9">
					<?php if ( isset( $user->data->user_nicename ) ) { ?>
                        <span class="show"><?php echo esc_html( $user->data->user_nicename ); ?></span>
					<?php } ?>
                </div>
            </div>
            <div class="form-group row">
                <label for="name-input" class="col-md-3"><?php esc_html_e( 'Tên hiển thị mới', 'madara' ); ?></label>
                <div class="col-md-9">
                    <input class="form-control" type="text" value="" name="user-new-name">
                </div>
            </div>
            <div class="form-group row">
                <label for="name-input-submit" class="col-md-3"><?php esc_html_e( 'Cập nhật', 'madara' ); ?></label>
                <div class="col-md-9">
                    <input class="form-control" type="submit" value="<?php esc_html_e( 'Cập nhật', 'madara' ); ?>" id="name-input-submit">
                </div>
            </div>

        </div>
        <div class="tab-item">
            <div class="settings-title">
                <h3>
					<?php esc_html_e( 'Thay đổi email', 'madara' ); ?>
                </h3>
            </div>
            <div class="form-group row">
                <label for="email-input" class="col-md-3"><?php esc_html_e( 'Email của bạn', 'madara' ); ?></label>
                <div class="col-md-9">
					<?php if ( isset( $user->data->user_email ) ) { ?>
                        <span class="show"><?php echo esc_html( $user->data->user_email ); ?></span>
					<?php } ?>
                </div>
            </div>
            <div class="form-group row">
                <label for="email-input" class="col-md-3"><?php esc_html_e( 'Email mới', 'madara' ); ?></label>
                <div class="col-md-9">
                    <input class="form-control" type="email" value="" id="email-input" name="user-new-email">
                </div>
            </div>
            <div class="form-group row">
                <label for="email-input-submit" class="col-md-3"><?php esc_html_e( 'Cập nhật', 'madara' ); ?></label>
                <div class="col-md-9">
                    <input class="form-control" type="submit" value="<?php esc_html_e( 'Cập nhật', 'madara' ); ?>" id="email-input-submit">
                </div>
            </div>
        </div>
        <div class="tab-item">
            <div class="settings-title">
                <h3>
					<?php esc_html_e( 'Đổi mật khẩu', 'madara' ); ?>
                </h3>
            </div>

            <div class="form-group row">
                <label for="currrent-password-input" class="col-md-3"><?php esc_html_e( 'Mật khẩu hiện tại', 'madara' ); ?></label>
                <div class="col-md-9">
                    <input class="form-control" type="password" value="" id="currrent-password-input" name="user-current-password">
                </div>
            </div>
            <div class="form-group row">
                <label for="new-password-input" class="col-md-3"><?php esc_html_e( 'Mật khẩu mới', 'madara' ); ?></label>
                <div class="col-md-9">
                    <input class="form-control" type="password" value="" id="new-password-input" name="user-new-password">
                </div>
            </div>
            <div class="form-group row">
                <label for="comfirm-password-input" class="col-md-3"><?php esc_html_e( 'Xác nhận mật khẩu', 'madara' ); ?></label>
                <div class="col-md-9">
                    <input class="form-control" type="password" value="" id="comfirm-password-input" name="user-new-password-confirm">
                </div>
            </div>
            <div class="form-group row">
                <label for="password-input-submit" class="col-md-3"><?php esc_html_e( 'Cập nhật', 'madara' ); ?></label>
                <div class="col-md-9">
                    <input class="form-control" type="submit" value="<?php esc_html_e( 'Cập nhật', 'madara' ); ?>" id="password-input-submit">
                </div>
            </div>
        </div>
    </div>
</form>
