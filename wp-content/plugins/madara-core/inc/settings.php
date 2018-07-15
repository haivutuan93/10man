<?php

class WP_MANGA_SETTING {

	public $settings;

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'wp_manga_setting_page' ) );
		add_action( 'admin_init', array( $this, 'wp_manga_setting_save' ) );

		$settings = $this->settings = get_option( 'wp_manga_settings', array() );

		if ( isset( $settings['enable_comment'] ) ) {
            if( $settings['enable_comment'] == 1 ) {
                add_action( 'wp_manga_discusion', array( $this, 'wp_manga_discusion_func' ) );
            }
		}

	}

	public function wp_manga_setting_page() {
		add_submenu_page( 'edit.php?post_type=wp-manga', esc_html__( 'WP Manga Settings', WP_MANGA_TEXTDOMAIN ), esc_html__( 'WP Manga Settings', WP_MANGA_TEXTDOMAIN ), 'manage_options', 'wp-manga-settings', array( $this, 'wp_manga_setting_page_layout' )  );
	}

	function wp_manga_script_settings(){

		$settings = $this->settings;

		if( !empty( $settings['loading_slick'] ) ) {
			wp_dequeue_style( 'wp-manga-slick-css' );
			wp_dequeue_style( 'wp-manga-slick-theme-css' );
			wp_dequeue_script( 'wp-manga-slick-js' );
		}

		if( !empty( $settings['loading_fontawesome'] ) ) {
			wp_dequeue_style( 'wp-manga-font-awesome' );
		}

		if( !empty( $settings['loading_ionicon'] ) ) {
			wp_dequeue_style( 'wp-manga-ionicons' );
		}

	}

	function wp_manga_setting_page_layout() {

		if( file_exists( WP_MANGA_DIR . 'inc/admin-template/settings/settings-page.php' ) ){
			include( WP_MANGA_DIR . 'inc/admin-template/settings/settings-page.php' );
		}

	}

	function wp_manga_setting_save() {

		if ( isset( $_POST['wp_manga_settings'] ) ) {

			$wp_manga_settings = $_POST['wp_manga_settings'];
			$wp_manga_settings['manga_slug']                   = urldecode( sanitize_title( $_POST['wp_manga_settings']['manga_slug'] ) );
			$wp_manga_settings['manga_post_type_archive_slug'] = urldecode( sanitize_title( $_POST['wp_manga_settings']['manga_post_type_archive_slug'] ) );
			$wp_manga_settings['loading_bootstrap']            = isset( $wp_manga_settings['loading_bootstrap'] ) ? $wp_manga_settings['loading_bootstrap'] : '0';
			$wp_manga_settings['loading_slick']                = isset( $wp_manga_settings['loading_slick'] ) ? $wp_manga_settings['loading_slick'] : '0';
			$wp_manga_settings['loading_fontawesome']          = isset( $wp_manga_settings['loading_fontawesome'] ) ? $wp_manga_settings['loading_fontawesome'] : '0';
			$wp_manga_settings['loading_ionicon']              = isset( $wp_manga_settings['loading_ionicon'] ) ? $wp_manga_settings['loading_ionicon'] : '0';
			$wp_manga_settings['admin_hide_bar']               = isset( $wp_manga_settings['admin_hide_bar'] ) ? $wp_manga_settings['admin_hide_bar'] : '0';
			$wp_manga_settings['default_storage']              = isset( $wp_manga_settings['default_storage'] ) ? $wp_manga_settings['default_storage'] : 'local';
			$wp_manga_settings['hosting_selection']            = isset( $wp_manga_settings['hosting_selection'] ) ? $wp_manga_settings['hosting_selection'] : '0';
			$wp_manga_settings['single_manga_seo']          = isset( $wp_manga_settings['single_manga_seo'] ) ? $wp_manga_settings['single_manga_seo'] : '0';
			update_option( 'wp_manga_settings', $wp_manga_settings );

			//change manga slug
			$args = get_post_type_object( 'wp-manga' );

			$args->rewrite['slug'] = $wp_manga_settings['manga_slug'];
			$args->has_archive = $wp_manga_settings['manga_post_type_archive_slug'];

			register_post_type( $args->name, $args );

			flush_rewrite_rules();

		}

		do_action('wp_manga_setting_save');

	}

	function get_manga_option( $option, $default_value = false ){

		$settings = get_option( 'wp_manga_settings', array() );

		if( isset( $settings[$option] ) ){
			return $settings[$option];
		}

		return $default_value;

	}

	function wp_manga_discusion_func() {
        ?>
		<div id="manga-discission" class="c-blog__heading style-2 font-heading">
			<i class="ion-ios-star"></i>
			<h4> <?php esc_html_e( 'THẢO LUẬN', WP_MANGA_TEXTDOMAIN ); ?> </h4>
		</div>
		<?php
		comments_template();
	}
}
$GLOBALS['wp_manga_setting'] = new WP_MANGA_SETTING();
