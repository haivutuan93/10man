<?php

	/**
	 * 1.0
	 * @package    Madara
	 * @author     Mangabooth <mangabooth@gmail.com>
	 * @copyright  Copyright (C) 2018 mangabooth.com. All Rights Reserved
	 *
	 * Websites: https://mangabooth.com/
	 */

	namespace App;

	// Prevent direct access to this file
	defined( 'ABSPATH' ) || die( 'Direct access to this file is not allowed.' );

	require( get_template_directory() . '/app/core.php' );

	require( 'lib/walker_mobile_menu.class.php' );

	if ( class_exists( 'WP_MANGA' ) ) {
		/*
		 * check plugin wp-manga active or not.
		 * */
		require( get_template_directory() . '/madara-core/manga-functions.php' );

	}

	/**
	 * Core class.
	 *
	 * @package  Madara
	 * @since    1.0
	 */
	class MadaraStarter extends Madara {

		private static $instance;

		public static function getInstance() {
			if ( null == self::$instance ) {
				self::$instance = new MadaraStarter();
			}

			return self::$instance;
		}

		/**
		 * Initialize Madara Core.
		 *
		 * @return  void
		 */
		public function initialize() {
			add_action( 'template_redirect', array( $this, 'set_content_width' ), 0 );

			parent::initialize();

			if ( class_exists( 'woocommerce' ) ) {
				Plugins\madara_WooCommerce\WooCommerce::initialize();
			}

			$this->registerWidgets();

			/**
			 * Custom template tags and functions for this theme.
			 */
			require( get_template_directory() . '/inc/template-tags.php' );
			require( get_template_directory() . '/inc/extras.php' );

			add_action( 'after_setup_theme', array( $this, 'addThemeSupport' ) );
			add_action( 'widgets_init', array( $this, 'registerSidebar' ) );
			add_action( 'after_setup_theme', array( $this, 'registerNavMenus' ) );


			add_action( 'wp_enqueue_scripts', array( $this, 'enqueueScripts' ) );

			add_action( 'madara_release_logs', array( $this, 'release_logs' ) );
			add_filter( 'theme_page_templates', array( $this, 'makewp_exclude_page_templates' ) );

			add_action( 'admin_init', array( $this, 'madara_vc_disable_update' ), 9 );
		}

		/**
		 * Set the content width in pixels, based on the theme's design and stylesheet.
		 *
		 * Priority 0 to make it available to lower priority callbacks.
		 *
		 * @global int $content_width
		 */
		function set_content_width() {

			$content_width = 980;

			$GLOBALS['content_width'] = apply_filters( 'madara_content_width', $content_width );
		}

		/**
		 * Hides the custom post template for pages on WordPress 4.6 and older
		 *
		 * @param array $post_templates Array of page templates. Keys are filenames, values are translated names.
		 *
		 * @return array Filtered array of page templates.
		 */
		function makewp_exclude_page_templates( $post_templates ) {
			if ( version_compare( $GLOBALS['wp_version'], '4.7', '<' ) ) {
				// unset( $post_templates['page-templates/my-full-width-post-template.php'] );
			}

			return $post_templates;
		}

		/**
		 * Add Theme Support
		 *
		 * @return void
		 */
		function addThemeSupport() {

			load_theme_textdomain( 'madara', get_template_directory() . '/languages' );

			add_theme_support( 'automatic-feed-links' );

			add_theme_support( "title-tag" );

			add_theme_support( 'post-thumbnails' );

			add_theme_support( 'custom-background' );

			add_theme_support( 'html5', array(
				'comment-form',
				'comment-list',
				'search-form',
				'gallery',
				'caption',
			) );

			// register thumb sizes
			do_action( 'madara_reg_thumbnail' );
		}

		/**
		 * Madara Sidebar Init
		 *
		 * @since Madara Alpha 1.0
		 */
		function registerSidebar() {
			/*
			 * register WP Manga Main Top Sidebar & WP Manga Main Top Second Sidebar when plugin wp-manga activated.
			 * */
			do_action( 'madara_add_manga_sidebar' );

			$main_sidebar_before_widget = apply_filters( 'madara_main_sidebar_before_widget', '<div class="row"><div id="%1$s" class="widget %2$s"><div class="widget__inner %2$s__inner c-widget-wrap">' );
			$main_sidebar_after_widget  = apply_filters( 'madara_main_sidebar_after_widget', '</div></div></div>' );

			$before_widget = apply_filters( 'madara_sidebar_before_widget', '<div id="%1$s" class="widget %2$s"><div class="widget__inner %2$s__inner c-widget-wrap">' );
			$after_widget  = apply_filters( 'madara_sidebar_after_widget', '</div></div>' );

			$before_title = apply_filters( 'madara_sidebar_before_title', '<div class="widget-heading font-nav"><h5 class="heading">' );
			$after_title  = apply_filters( 'madara_sidebar_after_title', '</h5></div>' );

			register_sidebar( array(
				'name'          => esc_html__( 'Main Sidebar', 'madara' ),
				'id'            => 'main_sidebar',
				'description'   => esc_html__( 'Main Sidebar used by all pages', 'madara' ),
				'before_widget' => $main_sidebar_before_widget,
				'after_widget'  => $main_sidebar_after_widget,
				'before_title'  => $before_title,
				'after_title'   => $after_title,
			) );

			register_sidebar( array(
				'name'          => esc_html__( 'Single Post Sidebar', 'madara' ),
				'id'            => 'single_post_sidebar',
				'description'   => esc_html__( 'Appear in Single Post', 'madara' ),
				'before_widget' => $main_sidebar_before_widget,
				'after_widget'  => $main_sidebar_after_widget,
				'before_title'  => $before_title,
				'after_title'   => $after_title,
			) );

			register_sidebar( array(
				'name'          => esc_html__( 'Search Sidebar', 'madara' ),
				'id'            => 'search_sidebar',
				'description'   => esc_html__( 'Search Sidebar in header', 'madara' ),
				'before_widget' => $before_widget,
				'after_widget'  => $after_widget,
				'before_title'  => $before_title,
				'after_title'   => $after_title,
			) );

			register_sidebar( array(
				'name'          => esc_html__( 'Main Top Sidebar', 'madara' ),
				'id'            => 'top_sidebar',
				'description'   => esc_html__( 'Appear before main content', 'madara' ),
				'before_widget' => $before_widget,
				'after_widget'  => $after_widget,
				'before_title'  => $before_title,
				'after_title'   => $after_title,
			) );

			register_sidebar( array(
				'name'          => esc_html__( 'Main Top Second Sidebar', 'madara' ),
				'id'            => 'top_second_sidebar',
				'description'   => esc_html__( 'Appear before main content', 'madara' ),
				'before_widget' => $before_widget,
				'after_widget'  => $after_widget,
				'before_title'  => $before_title,
				'after_title'   => $after_title,
			) );

			register_sidebar( array(
				'name'          => esc_html__( 'Body Top Sidebar', 'madara' ),
				'id'            => 'body_top_sidebar',
				'description'   => esc_html__( 'Appear before body content', 'madara' ),
				'before_widget' => $before_widget,
				'after_widget'  => $after_widget,
				'before_title'  => $before_title,
				'after_title'   => $after_title,
			) );

			register_sidebar( array(
				'name'          => esc_html__( 'Body Bottom Sidebar', 'madara' ),
				'id'            => 'body_bottom_sidebar',
				'description'   => esc_html__( 'Appear after body content', 'madara' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s"><div class="widget__inner %2$s__inner c-widget-wrap">',
				'after_widget'  => '</div></div>',
				'before_title'  => '<div class="widget-title"><div class="c-blog__heading style-2 font-heading"><h4>',
				'after_title'   => '</h4></div></div>',
			) );

			register_sidebar( array(
				'name'          => esc_html__( 'Main Bottom Sidebar', 'madara' ),
				'id'            => 'bottom_sidebar',
				'description'   => esc_html__( 'Appear after main content', 'madara' ),
				'before_widget' => $before_widget,
				'after_widget'  => $after_widget,
				'before_title'  => $before_title,
				'after_title'   => $after_title,
			) );

			register_sidebar( array(
				'name'          => esc_html__( 'Footer Sidebar', 'madara' ),
				'id'            => 'footer_sidebar',
				'description'   => esc_html__( 'Appear in Footer', 'madara' ),
				'before_widget' => $before_widget,
				'after_widget'  => $after_widget,
				'before_title'  => $before_title,
				'after_title'   => $after_title,
			) );
		}

		/**
		 * Register Menu Location
		 *
		 * @since Madara Alpha 1.0
		 */
		function registerNavMenus() {
			register_nav_menus( array(
				'primary_menu'   => esc_html__( 'Primary Menu', 'madara' ),
				'secondary_menu' => esc_html__( 'Secondary Menu', 'madara' ),
				'mobile_menu'    => esc_html__( 'Mobile Menu', 'madara' ),
				'footer_menu'    => esc_html__( 'Footer Menu', 'madara' ),
			) );
		}

		/**
		 * Enqueue needed scripts
		 */
		function enqueueScripts() {
			wp_enqueue_style( 'google-fonts', 'https://fonts.googleapis.com/css?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i' );

			if ( $this->getOption( 'loading_fontawesome', 'on' ) == 'on' ) {
				wp_enqueue_style( 'fontawesome', get_parent_theme_file_uri( '/app/lib/fontawesome/web-fonts-with-css/css/fontawesome-all.min.css' ), array(), '5.0.6' );
			}
			if ( $this->getOption( 'loading_ionicons', 'on' ) == 'on' ) {
				wp_enqueue_style( 'ionicons', get_parent_theme_file_uri( '/css/fonts/ionicons/css/ionicons.min.css' ) );
			}
			if ( $this->getOption( 'loading_ct_icons', 'on' ) == 'on' ) {
				wp_enqueue_style( 'madara-icons', get_parent_theme_file_uri( '/css/fonts/ct-icon/ct-icon.css' ) );
			}

			wp_enqueue_style( 'bootstrap', get_parent_theme_file_uri( '/css/bootstrap.min.css' ) );
			wp_enqueue_style( 'slick', get_parent_theme_file_uri( '/js/slick/slick.css' ) );
			wp_enqueue_style( 'slick-theme', get_parent_theme_file_uri( '/js/slick/slick-theme.css' ) );
			//Temporary
			wp_enqueue_style( 'loaders', get_parent_theme_file_uri( '/css/loaders.min.css' ) );
			wp_enqueue_style( 'madara-css', get_stylesheet_uri() );

			wp_enqueue_script( 'imagesloaded' );
			wp_enqueue_script( 'slick', get_parent_theme_file_uri( '/js/slick/slick.min.js' ), array( 'jquery' ), '1.7.1', true );
			wp_enqueue_script( 'aos', get_parent_theme_file_uri( '/js/aos.js' ), array(), '', true );
			wp_enqueue_script( 'madara-js', get_parent_theme_file_uri( '/js/template.js' ), array(
				'jquery',
				'bootstrap',
				'shuffle'
			), '', true );

			if ( $this->getOption( 'archive_navigation', 'default' ) == 'ajax' ) {
				wp_enqueue_script( 'madara-ajax', get_parent_theme_file_uri( '/js/ajax.js' ), array( 'jquery' ), '', true );
			}

			$js_params = array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) );

			global $wp_query, $wp;

			$js_params['query_vars']  = $wp_query->query_vars;
			$js_params['current_url'] = home_url( $wp->request );

			wp_localize_script( 'madara-js', 'madara', $js_params );

			/**
			 * Add Custom CSS
			 */
			require( get_template_directory() . '/css/custom.css.php' );

			$custom_css = madara_custom_CSS();

			wp_add_inline_style( 'madara-css', $custom_css );
		}

		/**
		 * Include required widgets for each theme
		 *
		 * @return void
		 */
		public function registerWidgets() {

			/**
			 * Madara Posts Widget Init
			 *
			 */
			$madara_Posts = new Plugins\Widgets\Posts();

			if ( class_exists( 'WP_MANGA' ) ) {
				$mangaHistoryWidget  = new Plugins\Widgets\MangaHistory();
				$mangaBookmarkWidget = new Plugins\Widgets\MangaBookmark();
			}
		}

		public function release_logs() {
			// sample release logs
			?>
            <ul>
                <li><span>First release</span></li>
            </ul>
			<?php
		}

		/* Disable VC auto-update */
		public function madara_vc_disable_update() {
			if ( function_exists( 'vc_license' ) && function_exists( 'vc_updater' ) && ! vc_license()->isActivated() ) {

				remove_filter( 'upgrader_pre_download', array( vc_updater(), 'preUpgradeFilter' ), 10 );
				remove_filter( 'pre_set_site_transient_update_plugins', array(
					vc_updater()->updateManager(),
					'check_update'
				) );

			}
		}

	}


	$madara = MadaraStarter::getInstance();
	$madara->initialize();
