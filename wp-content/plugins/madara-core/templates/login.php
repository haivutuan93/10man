<?php
/* login template */
?>

<!-- Modal -->

<div class="wp-manga-section">
	<input type="hidden" name="bookmarking" value="0"/>
	<div class="modal fade" id="form-login" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span></button>
				</div>
				<div class="modal-body">
					<div id="login" class="login">
						<h1>
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" tabindex="-1"><?php echo esc_html__( 'Sign in', WP_MANGA_TEXTDOMAIN ); ?></a>
						</h1>
						<p class="message login"></p>
						<?php do_action('login_head'); ?>
						<form name="loginform" id="loginform" method="post">
							<p>
								<label><?php echo esc_html__( 'Username or Email Address *', WP_MANGA_TEXTDOMAIN ); ?>
									<br>
									<input type="text" name="log" class="input user_login" value="" size="20">
								</label>
							</p>
							<p>
								<label><?php echo esc_html__( 'Password *', WP_MANGA_TEXTDOMAIN ); ?>
									<br>
									<input type="password" name="pwd" class="input user_pass" value="" size="20">
								</label>
							</p>
							<p class="forgetmenot">
								<label>
									<input name="rememberme" type="checkbox" id="rememberme" value="forever"><?php echo esc_html__( 'Remember Me ', WP_MANGA_TEXTDOMAIN ); ?>
								</label>
							</p>
							<p class="submit">
								<input type="submit" name="wp-submit" class="button button-primary button-large wp-submit" value="<?php esc_html_e( 'Log In', WP_MANGA_TEXTDOMAIN ); ?>">
								<input type="hidden" name="redirect_to" value="<?php echo esc_url( home_url( '/' ) ) . 'wp-admin/'; ?>">
								<input type="hidden" name="testcookie" value="1">
							</p>
							<?php do_action('login_form'); ?>
						</form>
						<p class="nav">
							<a href="javascript:avoid(0)" class="to-reset"><?php echo esc_html__( 'Lost your password?', WP_MANGA_TEXTDOMAIN ); ?></a>
						</p>
						<p class="backtoblog">
							<a href="javascript:void(0)"><?php echo esc_html__( '&larr; Back to ', WP_MANGA_TEXTDOMAIN ); ?><?php echo esc_attr( get_bloginfo( 'name' ) ); ?></a>
						</p>
					</div>
				</div>
				<div class="modal-footer"></div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="form-sign-up" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span></button>
				</div>
				<div class="modal-body">
					<div id="sign-up" class="login">
						<h1>
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" tabindex="-1"><?php echo esc_html__( 'Sign Up', WP_MANGA_TEXTDOMAIN ); ?></a>
						</h1>
						<p class="message register"><?php echo esc_html__( 'Register For This Site.', WP_MANGA_TEXTDOMAIN ); ?></p>
						<?php do_action('login_head'); ?>
						<form name="registerform" id="registerform" novalidate="novalidate">
							<p>
								<label><?php echo esc_html__( 'Username *', WP_MANGA_TEXTDOMAIN ); ?>
									<br>
									<input type="text" name="user_sign-up" class="input user_login" value="" size="20">
								</label>
							</p>
							<p>
								<label><?php echo esc_html__( 'Email Address *', WP_MANGA_TEXTDOMAIN ); ?>
									<br>
									<input type="email" name="email_sign-up"  class="input user_email" value="" size="20">
								</label>
							</p>
							<p>
								<label><?php echo esc_html__( 'Password *', WP_MANGA_TEXTDOMAIN ); ?><br>
									<input type="password" name="pass_sign-up" class="input user_pass" value="" size="25">
								</label>
							</p>

							<input type="hidden" name="redirect_to" value="">
							<p class="submit">
								<input type="submit" name="wp-submit" class="button button-primary button-large wp-submit" value="<?php esc_html_e('Register', WP_MANGA_TEXTDOMAIN ); ?>">
							</p>
						</form>
						<?php do_action('login_form'); ?>
						<p class="nav">
							<a href="javascript:void(0)" class="to-login"><?php echo esc_html__( 'Log in', WP_MANGA_TEXTDOMAIN ); ?></a>
							|
							<a href="javascript:void(0)" class="to-reset"><?php echo esc_html__( 'Lost your password?', WP_MANGA_TEXTDOMAIN ); ?></a>
						</p>
						<p class="backtoblog">
							<a href="javascript:void(0)"><?php echo esc_html__( '&larr; Back to ', WP_MANGA_TEXTDOMAIN ); ?><?php echo esc_attr( get_bloginfo( 'name' ) ); ?></a>
						</p>
					</div>
				</div>
				<div class="modal-footer"></div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="form-reset" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span></button>
				</div>
				<div class="modal-body">
					<div id="reset" class="login">
						<h1>
							<a href="javascript:void(0)" class="to-reset"><?php echo esc_html__( 'Lost your password?', WP_MANGA_TEXTDOMAIN ); ?></a>
						</h1>
						<p class="message reset"><?php echo esc_html__( 'Please enter your username or email address. You will receive a link to create a new password via email.', WP_MANGA_TEXTDOMAIN ); ?></p>
						<form name="resetform" id="resetform" method="post">
							<p>
								<label><?php echo esc_html__( 'Username or Email Address', WP_MANGA_TEXTDOMAIN ); ?>
									<br>
									<input type="text" name="user_reset" id="user_reset" class="input" value="" size="20">
								</label>
							</p>
							<p class="submit">
								<input type="submit" name="wp-submit" class="button button-primary button-large wp-submit" value="<?php esc_html_e( 'Get New Password', WP_MANGA_TEXTDOMAIN ); ?>">
								<input type="hidden" name="testcookie" value="1">
							</p>
						</form>
						<p>
							<a class="backtoblog" href="javascript:void(0)"><?php echo esc_html__( '&larr; Back to  ', WP_MANGA_TEXTDOMAIN ); ?><?php echo esc_attr( get_bloginfo( 'name' ) ); ?></a>
						</p>
					</div>
				</div>
				<div class="modal-footer"></div>
			</div>
		</div>
	</div>
</div>
