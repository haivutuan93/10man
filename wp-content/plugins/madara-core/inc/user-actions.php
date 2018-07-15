<?php

	class WP_MANGA_USER_ACTION {

		public $user_actions;

		public function __construct() {

			add_shortcode( 'manga-user-page', array( $this, 'wp_manga_user_page' ) );

			add_action( 'admin_bar_menu', array( $this, 'add_admin_bar_manga_settings' ), 10, 1 );

			add_action( 'wp', array( $this, 'wp_manga_user_page_redirect' ) );
		}

		function wp_manga_user_page() {

			global $wp_manga_template;

			$template = $wp_manga_template->load_template( 'user/settings', '', true );

			return $template;

		}

		function wp_manga_user_page_redirect(){

			global $wp_manga_setting;

			$user_page = $wp_manga_setting->get_manga_option('user_page');

			if( $user_page == get_the_ID() && !is_user_logged_in() ){
				wp_safe_redirect( home_url('/'), 302 );
			}

		}

		function wp_manga_user_section() {

			global $wp_manga_functions;

			$html = '<div class="c-modal_item">';

			if ( is_user_logged_in() ) {

				$html .= $wp_manga_functions->get_user_section( 75 );

			} else {

				$html .= '<a href="javascript:void(0)" data-toggle="modal" data-target="#form-login" class="btn-active-modal">' . esc_html__( 'Sign in', WP_MANGA_TEXTDOMAIN ) . '</a>';
				$html .= '<a href="javascript:void(0)" data-toggle="modal" data-target="#form-sign-up" class="btn-active-modal">' . esc_html__( 'Sign up', WP_MANGA_TEXTDOMAIN ) . '</a>';

			}

			$html .= '</div>';

			return $html;

		}

		function add_admin_bar_manga_settings( $bar ) {

			global $wp_manga_setting;

			$user_page = $wp_manga_setting->get_manga_option('user_page');

			if ( $user_page ) {
				$link = get_the_permalink( $user_page );
				$bar->add_node( array(
					'id'     => 'wp-manga-settings',
					'title'  => __( 'Manga Settings', WP_MANGA_TEXTDOMAIN ),
					'href'   => $link,
					'parent' => 'user-actions',
				) );
			}
		}

		function user_save_settings() {

			global $wp_manga_setting;

			if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( esc_html( $_POST['_wpnonce'] ), '_wp_manga_save_user_settings' ) ) {
				return false;
			}

			$current_user     = get_current_user_id();

			$reading_style = isset( $_POST['reading-style'] ) ? $_POST['reading-style'] : 'paged';

			update_user_meta( $current_user, '_manga_reading_style', $reading_style );
			wp_safe_redirect( get_the_permalink( $wp_manga_setting->get_manga_option('user_page') ) . '#setting' );
			exit;

		}

	}

	$GLOBALS['wp_manga_user_actions'] = new WP_MANGA_USER_ACTION();
