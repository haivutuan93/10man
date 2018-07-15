<?php
	/**
	 * 1.0
	 * @package    Madara
	 * @author     Madara Team <hi@madara.com>
	 * @copyright  Copyright (C) 2014 Madara.com. All Rights Reserved.
	 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
	 *
	 * Websites: http://www.madara.com
	 */

	namespace App;

	// Prevent direct access to this file
	defined( 'ABSPATH' ) || die( 'Direct access to this file is not allowed.' );

	/**
	 * Core class.
	 *
	 * @package  Madara
	 * @since    1.0
	 */
	class Madara {
		/**
		 * Define theme version.
		 *
		 * @var  string
		 */
		const VERSION = '1.0';

		private static $instance;

		public static function getInstance() {
			if ( null == self::$instance ) {
				self::$instance = new Madara();
			}

			return self::$instance;
		}

		/**
		 * Initialize Madara Core.
		 *
		 * @return  void
		 */
		public function initialize() {
			/**
			 * Require Autoload Class
			 *
			 * @since Madara Alpha 1.0
			 *
			 * PLEASE DO NOT DELETE
			 */
			require( 'lib/autoload.php' );

			/**
			 * load theme options, using Option Tree
			 */
			Config\OptionTree::setup();

			/**
			 * Make sure theme has required plugins
			 */
			Plugins\TGM_Plugin_Activation\ThemeRequired::initialize();

			/**
			 * Initialize the Welcome page
			 */
			Plugins\madara_Welcome\Welcome::initialize();

			/**
			 * Initialize Starter Content package
			 */
			Plugins\madara_Starter_Content\StarterContent::initialize();

			/**
			 * Initialize the Social Accounts Entity
			 */
			Models\Entity\Social::initialize();

			/**
			 * Initialize the Ads Entity
			 */
			Models\Entity\Ads::initialize();

			new Config\ThemeConfig();

			/**
			 * Global setup
			 */
			$this->setup();
		}


		/**
		 * Get option from Theme Options. Currently it uses Option Tree plugin for Theme Options
		 *
		 * @param $option string - Option name
		 * @param $default_value - Default value if not set
		 *
		 * @return mixed - Value of option
		 */
		public static function getOption( $option, $default_value = null ) {
			// check if current page or post has its own metadata
			if ( is_singular() ) {

				$setting = get_post_meta( get_the_ID(), $option, true );

				if ( ! isset( $setting ) || $setting == '' || $setting == 'default' ) {
					// if current page/post setting is empty, then check in Theme Options
					// this requires same names in metadata and theme options
					$setting = \App\Config\OptionTree::getOption( $option, $default_value );
				}

			} else {

				$setting = \App\Config\OptionTree::getOption( $option, $default_value );

			}

			return apply_filters( 'madara_get_option', $setting, $option, $default_value );
		}

		/**
		 * Setup theme
		 */
		private function setup() {
			/**
			 * Hooks to alter template
			 */
			require( 'hooks/template-hooks.php' );

			$this->__registerWidgets();

			/**
			 * Base template tags and functions for this theme.
			 */
			if ( file_exists( get_parent_theme_file_path( '/app/inc/template-tags.php' ) ) ) {
				require( 'inc/template-tags.php' );
			}

			if ( file_exists( get_parent_theme_file_path( '/app/inc/extras.php' ) ) ) {
				require( 'inc/extras.php' );
			}

			/**
			 * Import all Metadatas
			 */
			require( 'metadatas/metadatas.php' );

			add_action( 'wp_enqueue_scripts', array( $this, '__enqueueScripts' ) );

			add_action( 'admin_init', array( $this, '__admin_init' ) );
			add_action( 'admin_print_styles', array( $this, '__adminStyles' ) );
			add_action( 'admin_enqueue_scripts', array( $this, '__adminScripts' ) );

			add_action( 'wp_enqueue_scripts', array( $this, '__enqueueGoogleFonts' ) );

			add_action( 'wp_head', array( $this, '__wp_head' ), 999 );
			add_action( 'wp_footer', array( $this, '__wp_footer' ), 100 );
			add_action( 'login_enqueue_scripts', array( $this, '__admin_login_page' ) );

			/**
			 * Ajax page navigation
			 */
			// when the request action is 'madara_load_more', the ajax_load_next_page() will be called
			add_action( 'wp_ajax_madara_load_more', array( $this, 'ajax_load_next_page' ) );
			add_action( 'wp_ajax_nopriv_madara_load_more', array( $this, 'ajax_load_next_page' ) );

		}

		function __admin_init() {
			add_editor_style( 'editor.css' );
		}

		/**
		 * fortmat value of WP_Query $args submitted via POST
		 */
		private function __format_POST_args( $args ) {
			if ( is_array( $args ) ) {
				foreach ( $args as $key => $val ) {
					$val          = $this->__format_POST_args( $val );
					$args[ $key ] = $val;
				}
			} else {
				if ( is_numeric( $args ) ) {
					$args = intval( $args );
				}
				if ( $args == 'false' ) {
					$args = false;
				}
				if ( $args == 'true' ) {
					$args = true;
				}

				$args = str_replace( '\"', '"', $args );
			}

			return $args;
		}

		/**
		 * Ajax call to load next page
		 *
		 * @return HTML
		 */
		function ajax_load_next_page() {

			// Get current page
			$page = intval( $_POST['page'] );

			// current query vars
			$vars = $_POST['vars'];
			if ( ! isset( $vars ) ) {
				$vars = array();
			}

			// convert string value into corresponding data types
			$vars = $this->__format_POST_args( $vars );

			// item template file
			$template = $_POST['template'];

			// Return next page
			$page = intval( $page ) + 1;

			$posts_per_page = isset( $vars['posts_per_page'] ) ? $vars['posts_per_page'] : get_option( 'posts_per_page' );

			if ( $page == 0 ) {
				$page = 1;
			}
			$offset = ( $page - 1 ) * $posts_per_page;
			/*
			 * This is confusing. Just leave it here to later reference
			 *

			 *
			 */


			// get more posts per page than necessary to detect if there are more posts
			$args = array( 'posts_per_page' => $posts_per_page + 1, 'offset' => $offset );
			$args = array_merge( $vars, $args );

			if ( ! isset( $args['post_status'] ) ) {
				$args['post_status'] = 'publish';
			}

			if ( isset( $args['sidebar'] ) ) {
				set_query_var( 'sidebar', $args['sidebar'] );
			}

			if ( isset( $args['archive_content_columns'] ) ) {
				set_query_var( 'archive_content_columns', $args['archive_content_columns'] );
			}

			// remove unnecessary variables
			unset( $args['paged'] );
			unset( $args['p'] );
			unset( $args['page'] );
			unset( $args['pagename'] ); // this is neccessary in case Posts Page is set to a static page
			unset( $args['sidebar'] );
			unset( $args['archive_content_columns'] );

			$query = new \WP_Query( $args );

			$idx = 0;
			set_query_var( 'madara_offset', $offset + 1 );
			set_query_var( 'madara_total', $posts_per_page );
			$madara_loop_index = '';

			if ( $query->have_posts() ) {
				while ( $query->have_posts() ) {
					$query->the_post();
					$madara_loop_index ++;
					set_query_var( 'madara_loop_index', $madara_loop_index );

					if ( $madara_loop_index < $posts_per_page + 1 ) {
						if ( ( strpos( $template, 'plugins' ) !== false ) ) {
							include( $template );
						} else {
							//$post_format = get_post_format() ? get_post_format : '';
							get_template_part( $template, get_post_format() );
						}
					}
				}

				if ( $query->post_count <= $posts_per_page ) {
					// there are no more posts
					// print a flag to detect
					echo '<div class="invi no-posts"><!-- --></div>';
				}
			} else {
				// no posts found
			}

			/* Restore original Post Data */
			wp_reset_postdata();
			die( '' );
		}


		/**
		 * Register widgets
		 */
		protected function __registerWidgets() {

			/**
			 * Extend widgets behavior
			 */
			$widgetExtension = new \App\Plugins\Widgets\WidgetExtension();
		}

		/**
		 * Parse Google Fonts
		 *
		 * @since Madara Alpha 1.0
		 */
		function __enqueueGoogleFonts() {
			$getGoogleFonts = new Views\ParseGoogleFonts();

			$googleFonts = $getGoogleFonts->render();

			if ( $googleFonts ) {
				wp_enqueue_style( 'google-fonts', '//fonts.googleapis.com/css?family=' . implode( '|', $googleFonts ) );
			}
		}

		/**
		 * Enqueue LESS styles
		 */
		function __enqueue_less_styles( $style_tag, $handle ) {

			global $wp_styles;
			$obj = $wp_styles->query( $handle );
			if ( $obj === false ) {
				return $style_tag;
			}
			if ( ! preg_match( '/\.less$/U', $obj->src ) ) {
				return $style_tag;
			}
			/*
			 * The current stylesheet is a LESS stylesheet, so make according changes
			 * */
			$rel       = isset( $obj->extra['alt'] ) && $obj->extra['alt'] ? 'alternate stylesheet' : 'stylesheet';
			$style_tag = str_replace( "rel='" . $rel . "'", "rel='stylesheet/less'", $style_tag );
			$style_tag = str_replace( "id='" . $handle . "-css'", "id='" . $handle . "-less'", $style_tag );

			return $style_tag;
		}

		/**
		 * Enqueue scripts and styles.
		 */
		function __enqueueScripts() {
			if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
				wp_enqueue_script( 'comment-reply' );
			}

			wp_enqueue_script( 'madara-core', get_parent_theme_file_uri( '/js/core.js' ), array( 'jquery' ), '', true );

			if ( $this->getOption( 'scroll_effect', 'off' ) == 'on' ) {
				wp_enqueue_script( 'smoothscroll', get_parent_theme_file_uri( '/js/smoothscroll.js' ), array(), '1.4.5', true );
			}

			if ( $this->getOption( 'lazyload', 'off' ) == 'on' ) {
				wp_enqueue_script( 'lazysizes', get_parent_theme_file_uri( '/js/lazysizes/lazysizes.min.js' ), array(), '2.0.7', true );
			}


			wp_enqueue_script( 'bootstrap', get_parent_theme_file_uri( '/js/bootstrap.min.js' ), array(), '3.3.7', true );
			wp_enqueue_script( 'shuffle', get_parent_theme_file_uri( '/js/shuffle.min.js' ), array(), '', true );
		}

		/**
		 * Style for admin
		 */
		function __adminStyles() {
			wp_enqueue_style( 'fontawesome', get_parent_theme_file_uri( '/app/lib/fontawesome/web-fonts-with-css/css/fontawesome-all.min.css' ), array(), '5.0.6' );
			wp_enqueue_style( 'madara-admin-style', get_parent_theme_file_uri( '/admin/assets/css/style.css' ) );
		}

		/**
		 * Scripts for admin
		 */
		function __adminScripts() {
			wp_enqueue_media();

			wp_enqueue_script( 'wp-color-picker' );

			wp_enqueue_script( 'madara-admin', get_parent_theme_file_uri( '/admin/js/madara-admin.js' ), array( 'jquery' ), '', true );

			wp_enqueue_style( 'madara-color-picker', get_parent_theme_file_uri( '/admin/assets/lib/colorpicker-master/jquery.colorpicker.css' ) );
			wp_enqueue_script( 'madara-color-picker', get_parent_theme_file_uri( '/admin/assets/lib/colorpicker-master/jquery.colorpicker.js' ), array( 'jquery' ), '1.2.13', true );
		}

		/**
		 * add extra meta tags in HEAD
		 */
		function __wp_head() {
			/**
			 * pre-built meta tags
			 */
			if ( Madara::getOption( 'echo_meta_tags', 'on' ) == 'on' ) {
				if ( function_exists( 'madara_meta_tags' ) ) {
					madara_meta_tags();
				}
			}

		}

		/**
		 * add custom code to footer
		 */
		function __wp_footer() {
			do_action( 'madara-footer' );
		}

		/**
		 * custom login page
		 */
		function __admin_login_page() {
			if ( $img = Madara::getOption( 'login_logo_image' ) ) {
				?>
                <style type="text/css">
                    body.login div#login h1 a {
                        background-image: url(<?php echo esc_url($img); ?>);
                        width: 320px;
                        height: 120px;
                        background-size: auto;
                        background-position: center;
                    }
                </style>
			<?php }
		}

		/**
		 * Get current theme name
		 */
		public static function getThemeName() {
			$theme = wp_get_theme();

			return $theme->get( 'Name' );
		}

		/**
		 * Get current theme vesion
		 */
		public static function getThemeVersion() {
			$theme = wp_get_theme();

			return $theme->get( 'Version' );
		}
	}
