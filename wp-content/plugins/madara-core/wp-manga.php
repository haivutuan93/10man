<?php
	/**
	 *  Plugin Name: Madara - Core
	 *  Description: Manga creator
	 *  Plugin URI: http://www.mangabooth.com/
	 *  Author: MangaBooth
	 *  Author URI: https://themeforest.net/user/wpstylish
	 *  Author Email: mangabooth@gmail.com
	 *  Version: 1.3
	 *  Text Domain: mangabooth
	 * @since 1.0
	 */

	if ( ! defined( 'WP_MANGA_FILE' ) ) {
		define( 'WP_MANGA_FILE', __FILE__ );
	}
	// plugin dir path
	if ( ! defined( 'WP_MANGA_DIR' ) ) {
		define( 'WP_MANGA_DIR', plugin_dir_path( __FILE__ ) );
	}
	// plugin dir URI
	if ( ! defined( 'WP_MANGA_URI' ) ) {
		define( 'WP_MANGA_URI', plugin_dir_url( __FILE__ ) );
	}

	//data dir
	if ( ! defined( 'WP_MANGA_DATA_DIR' ) ) {
		$wp_upload_dir = wp_upload_dir();
		define( 'WP_MANGA_DATA_DIR', $wp_upload_dir['basedir'] . '/WP-manga/data/' );
	}
	//data url
	if ( ! defined( 'WP_MANGA_DATA_URL' ) ) {
		$wp_upload_dir = wp_upload_dir();
		define( 'WP_MANGA_DATA_URL', $wp_upload_dir['baseurl'] . '/WP-manga/data/' );
	}
	//json dir
	if ( ! defined( 'WP_MANGA_JSON_DIR' ) ) {
		$wp_upload_dir = wp_upload_dir();
		define( 'WP_MANGA_JSON_DIR', $wp_upload_dir['basedir'] . '/WP-manga/json/' );
	}
	//temp dir
	if ( ! defined( 'WP_MANGA_EXTRACT_DIR' ) ) {
		define( 'WP_MANGA_EXTRACT_DIR', WP_MANGA_DIR . 'extract/' );
	}
	//temp url
	if ( ! defined( 'WP_MANGA_EXTRACT_URL' ) ) {
		define( 'WP_MANGA_EXTRACT_URL', WP_MANGA_URI . 'extract/' );
	}

	if ( ! defined( 'WP_MANGA_TEXTDOMAIN' ) ) {
		define( 'WP_MANGA_TEXTDOMAIN', 'manga-core' );
	}

	// Note: update : [page] --> need mime type : might be video or text
	// AMAZON S3: NAME/CHAPTER/...
	// PICASA : ALBUM-CHAPNAME
	// Arrange page option in backend
	class WP_MANGA {

		public function __construct() {
			global $pagenow;
			$this->dir = WP_MANGA_DIR;
			$this->uri = WP_MANGA_URI;

			require_once( WP_MANGA_DIR . 'inc/database/database.php' );
			require_once( WP_MANGA_DIR . 'inc/database/database-chapter.php' );
			require_once( WP_MANGA_DIR . 'inc/database/database-volume.php' );

			require_once( WP_MANGA_DIR . '/inc/post-type.php' );
			require_once( WP_MANGA_DIR . '/inc/manga-type/manga-chapter.php' );
			require_once( WP_MANGA_DIR . '/inc/manga-type/text-chapter.php' );
			require_once( WP_MANGA_DIR . '/inc/settings.php' );
			require_once( WP_MANGA_DIR . '/inc/ajax.php' );
			require_once( WP_MANGA_DIR . '/inc/storage.php' );
			require_once( WP_MANGA_DIR . '/inc/template.php' );
			require_once( WP_MANGA_DIR . '/inc/sidebar.php' );
			require_once( WP_MANGA_DIR . '/inc/functions.php' );
			require_once( WP_MANGA_DIR . '/inc/first-install.php' );
			require_once( WP_MANGA_DIR . '/inc/zip-validation.php' );

			require_once( WP_MANGA_DIR . '/inc/comments/wp-comments.php' );
			require_once( WP_MANGA_DIR . '/inc/comments/disqus-comments.php' );

			// user action
			require_once( WP_MANGA_DIR . '/inc/user-actions.php' );

			require_once( WP_MANGA_DIR . '/inc/login/login.php' );


			require_once( WP_MANGA_DIR . '/inc/upload/imgur-upload.php' );
			require_once( WP_MANGA_DIR . '/inc/upload/google-upload.php' );
			require_once( WP_MANGA_DIR . '/inc/upload/amazon-upload.php' );

			/*
			 * Temporary remove this Widget
			 * */
			//require_once( WP_MANGA_DIR . '/inc/widgets/manga-taxonomy.php' );

			require_once( WP_MANGA_DIR . '/inc/widgets/recent-manga.php' );
			require_once( WP_MANGA_DIR . '/inc/widgets/manga-slider.php' );
			require_once( WP_MANGA_DIR . '/inc/widgets/manga-popular-slider.php' );
			require_once( WP_MANGA_DIR . '/inc/widgets/manga-search.php' );
			require_once( WP_MANGA_DIR . '/inc/widgets/manga-genres.php' );
			require_once( WP_MANGA_DIR . '/inc/widgets/manga-release.php' );
			require_once( WP_MANGA_DIR . '/inc/widgets/manga-user-section.php' );

			require_once( WP_MANGA_DIR . '/inc/login/login.php' );

			register_activation_hook( __FILE__, array( $this, 'manga_activation_sampledata' ) );
			add_action( 'admin_init', array( $this, 'manga_activation_sampledata_redirect' ) );

			// create default user page
			register_activation_hook( WP_MANGA_FILE, array( $this, 'wp_manga_active' ) );

			if ( $pagenow == 'post.php' || $pagenow == 'post-new.php' ) {
				add_action( 'admin_footer', array( $this, 'chapter_edit_modal' ) );
			}

			add_action( 'admin_menu', array( $this, 'wp_manga_menu_page' ) );
			add_action( 'admin_init', array( $this, 'wp_manga_save' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_script' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_script' ), 1000 );
			add_action( 'init', array( $this, 'get_token' ) );
			add_filter( 'body_class', array( $this, 'wp_manga_body_classes' ) );
			add_action( 'init', array( $this, 'wp_manga_load_plugin_textdomain' ) );

			add_image_size( 'manga-thumb-1', 110, 150, true );
			add_image_size( 'manga-single', 193, 278, true );
			add_image_size( 'manga_wg_post_1', 75, 106, true );
			add_image_size( 'manga_wg_post_2', 300, 165, true );
			add_image_size( 'manga-slider', 642, 320, true );

		}

		function wp_manga_load_plugin_textdomain() {
			load_plugin_textdomain( 'manga-core', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
		}

		public function wp_manga_menu_page() {
			add_submenu_page( 'edit.php?post_type=wp-manga', esc_html__( 'WP Manga Storage', WP_MANGA_TEXTDOMAIN ), esc_html__( 'WP Manga Storage', WP_MANGA_TEXTDOMAIN ), 'manage_options', 'wp-manga-storage', array(
				$this,
				'wp_manga_menu_page_layout'
			) );
		}

		function wp_manga_menu_page_layout() {

			if ( file_exists( WP_MANGA_DIR . 'inc/admin-template/settings/settings-storage.php' ) ) {
				include( WP_MANGA_DIR . 'inc/admin-template/settings/settings-storage.php' );
			}

		}

		function admin_enqueue_script() {

			global $pagenow;
			// style
			wp_enqueue_style( 'wp-manga-css', WP_MANGA_URI . 'assets/css/admin.css' );

			wp_enqueue_script( 'functions', WP_MANGA_URI . 'assets/js/functions.js' );
			wp_enqueue_script( 'admin_js', WP_MANGA_URI . 'assets/js/admin.js' );

			wp_localize_script( 'admin_js', 'wpManga', array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'home_url' => get_home_url(),
			) );

			wp_enqueue_style( 'wp-manga-font-awesome', WP_MANGA_URI . 'assets/css/font-awesome/css/font-awesome.min.css' );
			wp_enqueue_style( 'wp-manga-ionicons', WP_MANGA_URI . 'assets/css/ionicons/css/ionicons.min.css' );

			if ( $this->is_manga_edit_page() || $pagenow == 'edit.php' ) {
				wp_enqueue_script( 'wp_manga_popup_js', WP_MANGA_URI . 'assets/js/manga-popup.js', array( 'jquery' ), '', true );
			}

			if ( $this->is_manga_edit_page() ) {
				wp_enqueue_script( 'wp-manga-admin-single-manga', WP_MANGA_URI . 'assets/js/admin-single-manga.js', array( 'jquery' ), '', true );

				// formdata script
				wp_enqueue_script( 'wp-media-upload-form-data', WP_MANGA_URI . 'assets/js/form-data.js' );

				wp_enqueue_script( 'wp-manga-upload', WP_MANGA_URI . 'assets/js/upload.js', array( 'jquery' ), '', true );
				wp_enqueue_script( 'wp-manga-search-chapter', WP_MANGA_URI . 'assets/js/search-chapter.js', array( 'jquery' ), '', true );

				wp_enqueue_script( 'wp-manga-text-chapter', WP_MANGA_URI . 'assets/js/create-text-chapter.js', array( 'jquery' ), '', true );
			}

			if ( $pagenow == 'edit.php' ) {
				//download script
				wp_enqueue_script( 'wp_manga_download_js', WP_MANGA_URI . 'assets/js/manga-download.js', array( 'jquery' ), '', true );
			}
		}

		function bootstrap_check_enqueued() {

			global $wp_scripts;
			$all_scripts = array_column( $wp_scripts->registered, 'src' );

			foreach ( $all_scripts as $script ) {
				if ( strpos( $script, 'bootstrap' ) !== false ) {
					return true;
				}
			}

			return false;
		}

		function enqueue_script() {

			$settings = $GLOBALS['wp_manga_setting']->settings;

			global $wp_scripts;

			$bootstrap   = isset( $settings['loading_bootstrap'] ) ? $settings['loading_bootstrap'] : 'true';
			$slick       = isset( $settings['loading_slick'] ) ? $settings['loading_slick'] : 'true';
			$fontawesome = isset( $settings['loading_fontawesome'] ) ? $settings['loading_fontawesome'] : 'true';
			$ionicon     = isset( $settings['loading_ionicon'] ) ? $settings['loading_ionicon'] : 'true';

			if ( $bootstrap == 'true' && $this->bootstrap_check_enqueued() == false ) {
				wp_enqueue_style( 'wp-manga-bootstrap-css', WP_MANGA_URI . 'assets/css/bootstrap.min.css' );
				wp_enqueue_script( 'wp-manga-bootstrap-js', WP_MANGA_URI . 'assets/js/bootstrap.min.js', array( 'jquery' ), '', true );
			}

			//slick
			if ( $slick == 'true' ) {
				wp_enqueue_style( 'wp-manga-slick-css', WP_MANGA_URI . 'assets/slick/slick.css' );
				wp_enqueue_style( 'wp-manga-slick-theme-css', WP_MANGA_URI . 'assets/slick/slick-theme.css' );
				wp_enqueue_script( 'wp-manga-slick-js', WP_MANGA_URI . 'assets/slick/slick.min.js', array( 'jquery' ), '', true );
			}

			if ( $fontawesome == 'true' ) {
				wp_enqueue_style( 'wp-manga-font-awesome', WP_MANGA_URI . 'assets/css/font-awesome/css/font-awesome.min.css' );
			}

			if ( $ionicon == 'true' ) {
				wp_enqueue_style( 'wp-manga-ionicons', WP_MANGA_URI . 'assets/css/ionicons/css/ionicons.min.css' );
			}

			wp_enqueue_script( 'wp-manga', WP_MANGA_URI . 'assets/js/script.js', array(
				'jquery',
				'jquery-ui-autocomplete'
			), '', true );
			wp_localize_script( 'wp-manga', 'manga', array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'home_url' => get_home_url(),
			) );

			wp_enqueue_style( 'wp-manga-plugin-css', WP_MANGA_URI . 'assets/css/style.css' );

			wp_enqueue_style( 'wp-manga-font', 'https://fonts.googleapis.com/css?family=Poppins' );

			if ( is_search() ) {
				wp_enqueue_script( 'wp-manga-search', WP_MANGA_URI . 'assets/js/manga-search.js', array( 'jquery' ), '', true );
			}
		}

		function chapter_edit_modal() {

			if ( file_exists( WP_MANGA_DIR . 'inc/admin-template/manga-single/chapter-edit-modal.php' ) ) {
				include( WP_MANGA_DIR . 'inc/admin-template/manga-single/chapter-edit-modal.php' );
			}

		}

		function get_chapter( $post_id ) {
			global $wp_manga;
			$uniqid       = $wp_manga->get_uniqid( $post_id );
			$json_storage = WP_MANGA_JSON_DIR . $uniqid . '/manga.json';
			if ( file_exists( $json_storage ) ) {
				$raw  = file_get_contents( $json_storage );
				$data = json_decode( $raw, true );

				if ( isset( $data['chapters'] ) ) {
					$chapters['total_chapters'] = count( $data['chapters'] );
					foreach ( $data['chapters'] as $chapter => $value ) {
						$chapters['chapters'][ $chapter ] = $value;
					}

					return $chapters;
				}
			}

			return false;
		}

		function get_manga( $post_id ) {
			$uniqid              = $this->get_uniqid( $post_id );
			$manga_json_file_dir = WP_MANGA_JSON_DIR . $uniqid . '/manga.json';

			if ( file_exists( $manga_json_file_dir ) ) {
				$manga_json = file_get_contents( $manga_json_file_dir );
				$manga      = json_decode( $manga_json, true );

				if ( is_array( $manga ) ) {
					return $manga;
				}
			}

			return false;
		}

		function get_hosts( $post_id, $chapter_id ) {
			global $wp_manga;
			$uniqid       = $wp_manga->get_uniqid( $post_id );
			$json_storage = WP_MANGA_JSON_DIR . $uniqid . '/manga.json';
			if ( file_exists( $json_storage ) ) {
				$raw     = file_get_contents( $json_storage );
				$data    = json_decode( $raw, true );
				$storage = $data['chapters'][ $chapter_id ]['storage'];

				return $storage;
			} else {
				return false;
			}
		}

		function get_chapter_hosts( $post_id, $chapter_id ) {

			$host = $this->get_hosts( $post_id, $chapter_id );

			if ( $host == false ) {
				return false;
			}

			$host_arr = array();
			foreach ( $host as $key => $value ) {
				if ( 'inUse' != $key ) {
					$host_arr[] = $key;
				}
			}

			return $host_arr;

		}

		function get_single_chapter( $post_id, $chapter_id ) {
			global $wp_manga;
			$uniqid       = $wp_manga->get_uniqid( $post_id );
			$json_storage = WP_MANGA_JSON_DIR . $uniqid . '/manga.json';
			if ( file_exists( $json_storage ) ) {
				$raw = file_get_contents( $json_storage );

				$data = json_decode( $raw, true );

				return $data['chapters'][ $chapter_id ];
			} else {
				return false;
			}
		}

		function manga_nav( $position ) {
                    global $global_chapter_by_slug;
                    if( isset( $GLOBALS['madara_manga_navigation_html'] ) ){
				echo $this->wp_manga_nav_breadcrumbs( $GLOBALS['madara_manga_navigation_html'], $position );
				return;
			}

			global $wp_manga_chapter, $wp_manga_chapter_type, $wp_manga_text_type, $wp_manga_volume;

			$is_content_manga = $this->is_content_manga( get_the_ID() );

			$cur_chap = get_query_var( 'chapter' );
//			$chapter  = $wp_manga_chapter->get_chapter_by_slug( get_the_ID(), $cur_chap );
                        $chapter = $global_chapter_by_slug;

			//all chaps in same volume
			$all_chaps = $wp_manga_volume->get_volume_chapters( get_the_ID(), $chapter['volume_id'], 'name', 'asc' );

			if ( ! $chapter ) {
				return;
			}

			$args = array(
				'cur_chap'  => $cur_chap,
				'chapter'   => $chapter,
				'all_chaps' => $all_chaps,
				'position'  => $position,
			);

			$ouput = '';

			ob_start();

			if ( ! $is_content_manga ) {
				$wp_manga_chapter_type->manga_nav( $args );
			} else {
				$wp_manga_text_type->manga_nav( $args );
			}

			$output = ob_get_contents();

			ob_end_clean();

			$GLOBALS['madara_manga_navigation_html'] = $output;

			echo $this->wp_manga_nav_breadcrumbs( $output, $position );

		}

		function wp_manga_nav_breadcrumbs( $output, $position ){

			if( $position !== 'header' ){
				return $output;
			}

			$end_html = explode('<div class="wp-manga-nav">', $output);

			if( !isset( $end_html[1] ) ){
				return $output;
			}

			global $wp_manga_template;
			ob_start();
			?>
				<div class="wp-manga-nav">
					<div class="entry-header_wrap">
						<?php $wp_manga_template->load_template( 'manga', 'breadcrumb', true ); ?>
					</div>
					<?php echo $end_html[1]; ?>
			<?php
			$output = ob_get_contents();
			ob_end_clean();

			return $output;

		}

		function wp_manga_breadcrumbs() {
			$output = '<div class="c-breadcrumb">';
			$output .= '<ol class="breadcrumb">';

			$output .= '<li><a href="' . get_post_type_archive_link( 'wp-manga' ) . '"> ' . __( 'Manga', WP_MANGA_TEXTDOMAIN ) . ' </a></li>';

			if ( ( is_tax( 'wp-manga-author' ) || is_tax( 'wp-manga-release' ) || is_tax( 'wp-manga-genre' ) || is_tax( 'wp-manga-artist' ) ) && ! is_search() ) {

				$taxonomy     = get_query_var( 'taxonomy' );
				$term         = get_term_by( 'slug', get_query_var( $taxonomy ), $taxonomy );
				$current_term = $term;
				$term_output  = '';

				while ( $term->parent !== 0 ) {
					$parent_term = get_term_by( 'id', $term->parent, $taxonomy );
					$term_output = '<li><a href="' . get_term_link( $parent_term->term_id, $taxonomy ) . '">' . $parent_term->name . '</a></li>';
					$term        = $parent_term;
				}

				$output .= $term_output . '<li><span>' . $current_term->name . '</span></li>';

			}


			if ( is_singular( 'wp-manga' ) ) {
				$output .= '<li><a href="' . get_the_permalink( get_the_ID() ) . '">' . get_the_title() . '</a></li>';
			}

			if ( is_search() ) {
				$search = isset( $_GET['s'] ) ? $_GET['s'] : false;
				$output .= '<li><span>' . __( 'Showing search results for: ', WP_MANGA_TEXTDOMAIN ) . $search . '</span></li>';
			}

			$output .= '</ol>';
			$output .= '</div>';

			return $output;
		}

		function wp_manga_chapter_breadcrumbs( $chapter_name, $chapter_name_extend = '' ) {
			$output = '<div class="c-breadcrumb">';
			$output .= '<ol class="breadcrumb">';
			if ( is_singular( 'wp-manga' ) ) {
				$output .= '<li><a href="' . get_post_type_archive_link( 'wp-manga' ) . '"> Manga </a></li>';
				$output .= '<li><a href="' . get_the_permalink( get_the_ID() ) . '">' . get_the_title() . '</a></li>';
				$output .= '<li class="active">' . $chapter_name . ' : ' . $chapter_name_extend . '</li>';
			}
			$output .= '</ol>';
			$output .= '</div>';

			return $output;
		}

		function get_uniqid( $post_id ) {

			$uniqid = get_post_meta( $post_id, 'manga_unique_id', true );

			return $uniqid;

		}

		public function wp_manga_save() {

			if ( isset( $_POST['wp_manga'] ) ) {

				$options = get_option( 'wp_manga', $_POST['wp_manga'] );

				$manga_options = array();
				foreach ( $_POST['wp_manga'] as $key => $value ) {
					$manga_options[ $key ] = trim( $value );
				}

				//if google client id and client secret is change, then remove refreshtoken
				if ( $manga_options['google_client_id'] !== $options['google_client_id'] || $manga_options['google_client_secret'] !== $options['google_client_secret'] || ! isset( $_POST['google_refreshtoken'] ) ) {
					update_option( 'wp_manga_google_refreshToken', null );
				}

				//if imgur client id and client secret is change, then remove refreshtoken
				if ( $manga_options['imgur_client_secret'] !== $options['imgur_client_secret'] || $manga_options['imgur_client_id'] !== $options['imgur_client_id'] ) {
					update_option( 'wp_manga_imgur_refreshToken', null );
				}

				update_option( 'wp_manga', $manga_options );

			}

		}

		function post_url( $url, $params ) {
			$ch        = curl_init();
			$headers   = array();
			$headers[] = 'Content-Type: application/x-www-form-urlencoded';
			curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );
			curl_setopt( $ch, CURLOPT_URL, $url );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
			curl_setopt( $ch, CURLOPT_HTTPGET, 0 );
			curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query( $params, null, '&' ) );
			curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
			$ret = curl_exec( $ch );

			curl_close( $ch );

			return $ret;
		}

		function get_token() {
			$state   = isset( $_GET['state'] ) ? $_GET['state'] : null;
			$options = get_option( 'wp_manga', array() );
			$code    = isset( $_GET['code'] ) ? $_GET['code'] : null;

			if ( 'imgur' == $state && $code ) {
				$imgur_client_id     = isset( $options['imgur_client_id'] ) ? $options['imgur_client_id'] : '';
				$imgur_client_secret = isset( $options['imgur_client_secret'] ) ? $options['imgur_client_secret'] : '';
				$url                 = 'https://api.imgur.com/oauth2/token';
				$params              = array(
					'code'          => $code,
					'client_id'     => $imgur_client_id,
					'client_secret' => $imgur_client_secret,
					'grant_type'    => 'authorization_code',
				);
				$res                 = $this->post_url( $url, $params );
				$tokens              = json_decode( $res );
				if ( isset( $tokens->refresh_token ) ) {
					update_option( 'wp_manga_imgur_refreshToken', $refresh_token );
				}
				exit( wp_safe_redirect( admin_url( 'edit.php?post_type=wp-manga&page=wp-manga-storage' ) ) );
			} else if ( 'picasa' == $state && $code ) {
				$google_client_id     = isset( $options['google_client_id'] ) ? $options['google_client_id'] : '';
				$google_client_secret = isset( $options['google_client_secret'] ) ? $options['google_client_secret'] : '';
				$google_redirect      = isset( $options['google_redirect'] ) ? $options['google_redirect'] : '';
				$url                  = 'https://www.googleapis.com/oauth2/v4/token';
				$params               = array(
					'code'          => $code,
					'client_id'     => $google_client_id,
					'client_secret' => $google_client_secret,
					'redirect_uri'  => $google_redirect,
					'grant_type'    => 'authorization_code',
					'access_type'   => 'offline',
				);
				$res                  = $this->post_url( $url, $params );
				$tokens               = json_decode( $res );

				if ( isset( $tokens->refresh_token ) ) {
					update_option( 'wp_manga_google_refreshToken', $tokens->refresh_token );
				} else {
					set_transient( 'google_authorized', false );
					set_transient( 'google_authorization_error', esc_html__( 'Cannot get Google Refresh Token, please try again', 'madara' ) );
				}
				exit( wp_safe_redirect( admin_url( 'edit.php?post_type=wp-manga&page=wp-manga-storage' ) ) );
			}
		}

		function get_available_host() {

			$available_host = array();
			$options        = get_option( 'wp_manga', array() );

			$available_host['local']['value'] = 'local';
			$available_host['local']['text']  = 'Local';

			// imgur
			$imgur_client_id     = isset( $options['imgur_client_id'] ) ? $options['imgur_client_id'] : null;
			$imgur_client_secret = isset( $options['imgur_client_secret'] ) ? $options['imgur_client_secret'] : null;
			$imgur_refresh_token = get_option( 'wp_manga_imgur_refreshToken', null );

			if ( $imgur_client_id && $imgur_client_secret && $imgur_refresh_token ) {
				$available_host['imgur']['value'] = 'imgur';
				$available_host['imgur']['text']  = __( 'Imgur', WP_MANGA_TEXTDOMAIN );
			}

			// google
			global $wp_manga_google_upload;
			$accessToken = $wp_manga_google_upload->get_access_token();
			if ( $accessToken ) {
				$available_host['picasa']['value'] = 'picasa';
				$available_host['picasa']['text']  = __( 'Blogspot', WP_MANGA_TEXTDOMAIN );
			}

			// amazon
			$amazon_s3_access_key    = isset( $options['amazon_s3_access_key'] ) ? $options['amazon_s3_access_key'] : null;
			$amazon_s3_access_secret = isset( $options['amazon_s3_access_secret'] ) ? $options['amazon_s3_access_secret'] : null;
			$amazon_s3_region        = isset( $options['amazon_s3_region'] ) ? $options['amazon_s3_region'] : null;

			if ( $amazon_s3_access_key && $amazon_s3_access_secret && $amazon_s3_region ) {
				$available_host['amazon']['value'] = 'amazon';
				$available_host['amazon']['text']  = __( 'Amazon S3', WP_MANGA_TEXTDOMAIN );
			}

			return $available_host;
		}

		function wp_manga_active() {

			$first_active = get_option( 'wp_manga_first_active', false );
			if ( ! $first_active ) {
				update_option( 'wp_manga_first_active', true );
				update_option( 'wp_manga_notice', true ); // true == no notice
				$this->create_default_page();
				set_transient( 'wp_manga_welcome_redirect', true );
			}

		}

		function create_default_page() {

			$user_page = wp_insert_post( array(
				'post_title'   => 'User Settings',
				'post_status'  => 'publish',
				'post_type'    => 'page',
				'post_content' => '[manga-user-page]'
			) );

			$archive_page = wp_insert_post( array(
				'post_title'  => 'Manga',
				'post_status' => 'publish',
				'post_type'   => 'page',
			) );

			$posts_data = array(
				'user_page'          => $user_page,
				'manga_archive_page' => $archive_page
			);

			$options = get_option( 'wp_manga_settings', array() );

			$user_page_settings = array_merge( $options, $posts_data );

			update_option( 'wp_manga_settings', $user_page_settings );

		}

		function is_manga_edit_page() {

			global $pagenow, $post;

			if ( ( $pagenow == 'post.php' || $pagenow == 'post-new.php' ) && $post->post_type == 'wp-manga' ) {
				return true;
			}

			return false;
		}

		function wp_manga_get_tags() {

			$manga_tags    = get_the_terms( get_the_ID(), 'wp-manga-tag' );
			$manga_tags    = isset( $manga_tags ) && ! empty( $manga_tags ) ? $manga_tags : array();
			$tag_count     = count( $manga_tags );
			$tag_flag      = 0;
			$separate_char = ', ';

			if ( $manga_tags == false || is_wp_error( $manga_tags ) ) {
				return;
			}

			?>
            <div class="wp-manga-tags-wrapper">
                <div class="wp-manga-tags">
                    <h5><?php esc_html_e( 'Tags: ', WP_MANGA_TEXTDOMAIN ); ?></h5>
                    <div class="wp-manga-tags-list">
						<?php foreach ( $manga_tags as $tag ) {
							$tag_flag ++;
							if ( $tag_flag == $tag_count ) {
								$separate_char = '';
							}
							?>
                            <a href="<?php echo esc_url( get_term_link( $tag->term_id ) ); ?>" class=""><?php echo esc_html( $tag->name ); ?></a><?php echo esc_html( $separate_char ); ?><?php } ?>
                    </div>
                </div>
            </div>
			<?php
		}

		function mangabooth_manga_query( $manga_args ) {

			$manga_args['post_type']   = 'wp-manga';
			$manga_args['post_status'] = 'publish';

			switch ( $manga_args['orderby'] ) {
				case 'alphabet' :
					$manga_args['orderby'] = 'post_title';
					$manga_args['order']   = 'ASC';
					break;
				case 'ratings' :
					$manga_args['orderby']  = 'meta_value_num';
					$manga_args['meta_key'] = '_manga_avarage_reviews';
					break;
				case 'latest' :
					$manga_args['orderby']  = 'meta_value_num';
					$manga_args['meta_key'] = '_latest_update';
					break;
				case 'trending' :
					$manga_args['orderby']  = 'meta_value_num';
					$manga_args['meta_key'] = '_wp_manga_week_views';
					break;
				case 'views' :
					$manga_args['orderby']  = 'meta_value_num';
					$manga_args['meta_key'] = '_wp_manga_views';
					break;
				case 'new-manga' :
					$manga_args['orderby'] = 'date';
					break;
				case 'random' :
					$query_args['orderby'] = 'rand';
			}

			$manga_query = new WP_Query( $manga_args );

			return $manga_query;

		}

		function wp_manga_body_classes( $classes ) {

			global $wp_manga_functions;

			if ( $wp_manga_functions->is_wp_manga_page() ) {
				$classes[] = 'wp-manga-page';
			}

			if ( $wp_manga_functions->is_manga_reading_page() ) {
				$classes[] = 'reading-manga';
			}

			if ( $wp_manga_functions->is_manga_single() ) {
				$classes[] = 'manga-page';
			}

			return $classes;

		}

		function wp_manga_pagination( $query, $element, $template ) {

			if ( $query->max_num_pages == 1 || get_query_var( 'wp_manga_paged' ) >= $query->max_num_pages ) {
				return;
			}

			global $wp_manga_setting;
			$paging_style = $wp_manga_setting->get_manga_option( 'paging_style', 'load-more' );

			if ( $paging_style == 'load-more' ) {
				return $this->wp_manga_ajax_loadmore( $query, $element, $template );
			} else {
				return $this->wp_manga_default_pagination( $query, $element );
			}

		}

		function wp_manga_default_pagination( $query, $element ) {

			$args = array(
				'total'     => $query->max_num_pages,
				'current'   => get_query_var( 'wp_manga_paged' ),
				'prev_next' => false,
				'show_all'  => false,
				'end_size'  => 3,
				'mid_size'  => 5,
			);

			$html = '<div class="wp-manga-default-pagination wp-manga-pagination">';
			$html .= '<div class="nav-links">';
			$html .= paginate_links( $args );
			$html .= '</div>';
			$html .= '</div>';

			return $html;
		}

		function wp_manga_ajax_loadmore( $query, $element, $template ) {

			$current_page = get_query_var( 'wp_manga_paged' );

			$html = '<div class="wp-manga-ajax-loadmore wp-manga-pagination">';
			$html .= '<div class="wp-manga-ajax-button" data-element="' . esc_attr( $element ) . '" data-template="' . esc_attr( $template ) . '">';
			$html .= '<i class="fa fa-spinner fa-spin"></i>';
			$html .= '<span>' . esc_html( 'Load more', WP_MANGA_TEXTDOMAIN ) . '</span>';
			$html .= '</div>';
			$html .= '</div>';

			$args                  = $query->query;
			$args['max_num_pages'] = $query->max_num_pages;

			$html .= $this->wp_manga_query_vars_js( $args );

			return $html;
		}

		function wp_manga_query_vars_js( $args, $echo = false ) {

			$html = '<div class="wp-manga-query-vars hidden">';
			$html .= '<script>';
			$html .= 'var manga_args = ' . json_encode( $args ) . ';';
			$html .= '</script>';
			$html .= '</div>';

			if ( $echo ) {
				echo wp_kses( $html, array(
					'div'    => array(
						'class' => array(),
					),
					'script' => array(),
				) );
			}

			return $html;
		}

		function wp_manga_bg_img() {

			return WP_MANGA_URI . 'assets/images/bg-search.jpg';

		}

		function wp_manga_breadcrumb_middle( $obj ) {

			global $wp_manga_functions;

			if ( $obj == null ) {
				return;
			}

			$middle = array();

			if ( $wp_manga_functions->is_manga_single() ) {

				if ( $obj->parent != 0 ) {

					$post_id = $obj->parent;

					do {
						$parent = get_post( $post_id );
						if ( $parent && ! is_wp_error( $parent ) ) {
							$middle[ $parent->post_title ] = get_the_permalink( $parent->ID );
						}
						$post_id = $parent->ID;
					} while ( isset( $post_id ) && $post_id != 0 );

				}

				if ( has_term( '', 'wp-manga-genre' ) ) {

					$genre = get_the_terms( $obj->ID, 'wp-manga-genre' )[0];

					do {

						if ( $genre && ! is_wp_error( $genre ) ) {

							$middle[ $genre->name ] = get_term_link( $genre );
						}

						$genre = get_term_by( 'id', $genre->parent, 'wp-manga-genre' );

					} while ( isset( $genre->parent ) && $genre->parent != 0 );

				}

			} elseif ( is_tax() && $wp_manga_functions->is_manga_archive() ) {

				if ( $obj->parent == 0 ) {
					return false;
				}

				$term = get_term_by( 'id', $obj->parent, $obj->taxonomy );

				do {
					if ( $term && ! is_wp_error( $term ) ) {
						$middle[ $term->name ] = get_term_link( $term );
					}
					$term = get_term_by( 'id', $term->parent, $term->taxonomy );
				} while ( isset( $term->parent ) && $term->parent !== 0 );

			}

			return $middle;

		}

		function wp_manga_search_filter_url( $filter ) {

			if ( empty( $filter ) && empty( $_GET ) ) {
				return;
			}

			$get_vars = array_merge( $_GET, array( 'm_orderby' => $filter ) );

			$url = add_query_arg( $get_vars, home_url() );

			return $url;

		}

		function manga_activation_sampledata() {
			add_option( 'manga_activation_sampledata_redirect', true );
		}

		function manga_activation_sampledata_redirect() {
			if ( get_option( 'manga_activation_sampledata_redirect', false ) ) {
				delete_option( 'manga_activation_sampledata_redirect' );
				if ( ! isset( $_GET['activate-multi'] ) ) {
					wp_redirect( admin_url( 'plugins.php?wp-manga=first-install' ) );
				}
			}
		}

		function is_content_manga( $post_id ) {

			$chapter_type = get_post_meta( $post_id, '_wp_manga_chapter_type', true );

			return $chapter_type == 'text' || $chapter_type == 'video' ? true : false;

		}

	}

	$GLOBALS['wp_manga'] = new WP_MANGA();
