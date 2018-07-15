<?php
	/**
	 *  Plugin Name: MangaBooth Manga
	 *  Description: Login
	 */


	Class WP_MANGA_LOGIN {

		public function __construct() {

			// add_shortcode( 'wp_manga_user', array($this, 'wp_manga_user_shortcode') );

			add_action( 'wp_enqueue_scripts', array( $this, 'wp_manga_login_styles' ), 50 );
			add_action( 'wp_ajax_wp_manga_signin', array( $this, 'wp_manga_sign_in' ) );
			add_action( 'wp_ajax_nopriv_wp_manga_signin', array( $this, 'wp_manga_sign_in' ) );
			add_action( 'wp_ajax_wp_manga_signup', array( $this, 'wp_manga_sign_up' ) );
			add_action( 'wp_ajax_nopriv_wp_manga_signup', array( $this, 'wp_manga_sign_up' ) );
			add_action( 'wp_footer', array( $this, 'login_template' ) );
			add_action( 'wp_loaded', array( $this, 'remove_wp_toolbar' ) );


		}

		function login_template() {

			if ( ! is_user_logged_in() ) {
				global $wp_manga_template;
				$wp_manga_template->load_template( 'login', false );
			}

		}

		function wp_manga_login_styles() {
			wp_enqueue_script( 'wp-manga-login-ajax', WP_MANGA_URI . 'assets/js/login.js', array( 'jquery' ), '', true );
			wp_localize_script( 'wp-manga-login-ajax', 'wpMangaLogin', array(
				'admin_ajax' => admin_url( 'admin-ajax.php' ),
				'home_url'   => get_home_url(),
			) );
		}

		function wp_manga_sign_in() {

			$user_data                  = array();
			$user_data['user_login']    = trim( $_POST['login'] );
			$user_data['user_password'] = trim( $_POST['pass'] );
			$user_data['remember']      = $_POST['rememberme'];
			$user                       = wp_signon( $user_data, false );
			$response                   = array( 'loginerrors' => $user->get_error_message() );

			if ( is_wp_error( $user ) ) {
				wp_send_json( $response );
			} else {
				wp_set_current_user( $user->ID, $user_data['user_login'] );
				wp_send_json_success();
			}
		}

		function wp_manga_sign_up() {

			if ( ! empty( $_POST['user_login'] ) && ! empty( $_POST['user_pass'] ) && ! empty( $_POST['user_email'] ) ) {
				$user_data               = array();
				$user_data['user_login'] = trim( $_POST['user_login'] );
				$user_data['user_pass']  = trim( $_POST['user_pass'] );
				$user_data['user_email'] = trim( $_POST['user_email'] );
				$user_data['role']       = 'subscriber';

				$user_id = wp_insert_user( $user_data );
				if ( is_wp_error( $user_id ) ) {
					wp_send_json_error( __( $user_id->get_error_message(), WP_MANGA_TEXTDOMAIN ) );
				} else {
					wp_send_json_success( __( 'Registration successfully! You can login now.', WP_MANGA_TEXTDOMAIN ) );
				}
			} else {
				wp_send_json_error( __( 'There was an error when registration', WP_MANGA_TEXTDOMAIN ) );
			}

		}

		function wp_manga_reset() {

			$user_reset = isset( $_POST['user'] ) ? $_POST['user'] : '';

			if ( empty( $user ) ) {
				wp_send_json_error( __( 'Username or email address cannot be empty' ) );
			}

			$user_reset = trim( $user_reset );

			if ( strpos( $user_reset, '@' ) !== false ) {
				$user = get_user_by( 'email', $user_reset );
			} else {
				$user = get_user_by( 'login', $user_reset );
			}

			if ( $user == false ) {
				wp_send_json_error( __( 'There is no user registered with that email address or username.', WP_MANGA_TEXTDOMAIN ) );
			}

			$random_psw = wp_generate_password( 12, false );

			$to      = $user->user_email;
			$subject = __( 'Reset your password on ', WP_MANGA_TEXTDOMAIN ) . get_option( 'blogname' );
			$headers = array( get_option( 'blogname' ) );
			$message = __( 'Your new password is ', WP_MANGA_TEXTDOMAIN ) . $random_psw;

			$mail = wp_mail( $to, $subject, $message, $headers );

			if ( $mail == false ) {
				wp_send_json_error( __( 'Cannot send email', WP_MANGA_TEXTDOMAIN ) );
			} elseif ( $mail == true ) {
				$resp = wp_set_password( $random_psw, $user->ID );
				if ( $resp == true ) {
					wp_send_json_success( __( 'Please check your email address for you new password', WP_MANGA_TEXTDOMAIN ) );
				}
			}

			wp_send_json_error( __( 'Oops, something went wrong when resetiing your password.', WP_MANGA_TEXTDOMAIN ) );

		}

		function remove_wp_toolbar() {

			if ( is_user_logged_in() ) {

				//check current user role
				$user  = wp_get_current_user();
				$roles = $user->roles;

				global $wp_manga_setting;
				//check if hide admin for administrator
				$admin_hide_bar = $wp_manga_setting->get_manga_option( 'admin_hide_bar', false );

				if ( in_array( 'administrator', $roles ) && $admin_hide_bar == false ) {
					show_admin_bar( true );

					return;
				}
			}

			show_admin_bar( false );
		}
	}

	$GLOBALS['wp_manga_login'] = new WP_MANGA_LOGIN;
