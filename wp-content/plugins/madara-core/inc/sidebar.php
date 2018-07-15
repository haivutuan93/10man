<?php

	class WP_MANGA_SIDEBAR {

		public function __construct() {
			add_action( 'widgets_init', array( $this, 'wp_manga_sidebar' ) );
		}

		function wp_manga_sidebar() {

			$before_widget = apply_filters( 'wp_manga_sidebar_before_widget', '<aside id="%1$s" class="wp-manga-section widget %2$s">' );
			$after_widget  = apply_filters( 'wp_manga_sidebar_after_widget', '</aside>' );
			$before_title  = apply_filters( 'wp_manga_sidebar_before_title', '<h3 class="widget-title">' );
			$after_title   = apply_filters( 'wp_manga_sidebar_after_title', '</h3>' );

			register_sidebar( array(
				'name'          => esc_html__( 'WP Manga - Archives Sidebar', WP_MANGA_TEXTDOMAIN ),
				'description'   => esc_html__( 'Appear in Manga Archives Page', WP_MANGA_TEXTDOMAIN ),
				'id'            => 'manga_archive_sidebar',
				'before_widget' => $before_widget,
				'after_widget'  => $after_widget,
				'before_title'  => $before_title,
				'after_title'   => $after_title,
			) );
			register_sidebar( array(
				'name'          => esc_html__( 'WP Manga - Single Sidebar', WP_MANGA_TEXTDOMAIN ),
				'description'   => esc_html__( 'Appear in Single Manga Page', WP_MANGA_TEXTDOMAIN ),
				'id'            => 'manga_single_sidebar',
				'before_widget' => $before_widget,
				'after_widget'  => $after_widget,
				'before_title'  => $before_title,
				'after_title'   => $after_title,
			) );

			register_sidebar( array(
				'name'          => esc_html__( 'WP Manga - Reading Page Sidebar', WP_MANGA_TEXTDOMAIN ),
				'description'   => esc_html__( 'Appear in Manga Reading Page', WP_MANGA_TEXTDOMAIN ),
				'id'            => 'manga_reading_sidebar',
				'before_widget' => $before_widget,
				'after_widget'  => $after_widget,
				'before_title'  => $before_title,
				'after_title'   => $after_title,
			) );
		}


	}

	$GLOBALS['wp_manga_sidebar'] = new WP_MANGA_SIDEBAR();
