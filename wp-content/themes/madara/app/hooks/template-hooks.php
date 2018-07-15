<?php

	/**
	 * hooks to change template HTML
	 * @package madara
	 *
	 */


	use App\Madara;
	use App\Views\MadaraView;


	add_action( 'madara_before_body', 'madara_output_before_body', 10 );
	function madara_output_before_body() {
		// print out pre-loading effect

		if ( Madara::getOption( 'pre_loading', - 1 ) == 1 || ( Madara::getOption( 'pre_loading', - 1 ) == 2 && ( is_front_page() ) ) ) {
			$ajax_loading_template = \App\Madara::getOption( 'ajax_loading_effect', 'ball-grid-pulse' );

			$madara_logo = \App\Madara::getOption( 'pre_loading_logo', '' );
			if ( $madara_logo == '' ) {
				$madara_logo = madara_get_logo( false, true );
			} else {
				$madara_logo = '<a class="logo" href="' . esc_url( home_url( '/' ) ) . '" title="' . esc_attr( get_bloginfo( 'name' ) ) . '">
			    <img class="for-original" src="' . esc_url( $madara_logo ) . '" alt="' . esc_attr( get_bloginfo( 'name' ) ) . '"/></a>';
			}

			$html = '<div id="pageloader" class="spinners">
			<div class="spinner"><div class="c-pre-loading-logo">' . $madara_logo . '</div>';

			ob_start();
			get_template_part( 'html/ajax-loading/' . $ajax_loading_template );
			$html .= ob_get_contents();
			ob_end_clean();

			$html .= '
            </div>
        </div>';

			$html = apply_filters( 'madara_pre_loading_html', $html );

			echo wp_kses_post( $html );
		}
	}

	add_action( 'madara_page_header', 'madara_blog_breadcrumbs', 100 );
	function madara_blog_breadcrumbs() {
		if ( is_post_type_archive( 'post' ) || is_single() ) {
			if ( Madara::getOption( 'archive_breadcrumbs', 'off' ) == 'on' ) {
				?>
                <div id="c-blog_breadcrumbs">
                    <div class="container">
                        <div class="row c-row">
                            <div class="col-md-12 c-col">
								<?php MadaraView::renderBreadcrumbs(); ?>
                            </div>
                        </div>
                    </div>
                </div>
			<?php }
		}
	}


	add_filter( 'excerpt_length', 'madara_custom_excerpt_length', 999 );
	function madara_custom_excerpt_length( $length ) {
		return Madara::getOption( 'custom_excerpt_length', $length );
	}

	/**
	 * Filter the excerpt "read more" string.
	 *
	 * @param string $more "Read more" excerpt string.
	 *
	 * @return string (Maybe) modified "read more" excerpt string.
	 */
	function madara_excerpt_more( $more ) {
		$html = '...';
		$html .= '<div class="c-read-more"><a class="c-read-more-link" href="' . get_the_permalink( get_the_ID() ) . '">' . esc_html__( 'Continue Reading', 'madara' ) . '</a> ' . esc_html( '&rarr;' ) . '</div>';

		return apply_filters( 'madara_excerpt_more_content', $html );
	}

	add_filter( 'excerpt_more', 'madara_excerpt_more' );

	add_filter( 'madara_dashboard_heading', 'madara_welcome_text' );
	function madara_welcome_text( $text ) {
		return esc_html__( 'Madara Dashboard', 'madara' );
	}

	add_filter( 'madara_theme_document_url', 'madara_online_document' );
	function madara_online_document( $url ) {
		return '//demo.mangabooth.com/doc';
	}

	add_filter( 'madara_theme_support_url', 'madara_online_support' );
	function madara_online_support( $url ) {
		return '//themeforest.net/user/wpstylish';
	}

	add_action( 'wp_footer', 'madara_go_to_top' );
	function madara_go_to_top() {
		$is_gototop = Madara::getOption( 'go_to_top', 'off' );

		if ( $is_gototop != 'off' ) {
			?>
            <div class="go-to-top active">
                <i class="ion-android-arrow-up"></i>
            </div>
			<?php
		}

	}

	add_filter( 'wp_manga_sidebar_before_widget', 'madara_sidebar_filter_before_widget', 999 );
	function madara_sidebar_filter_before_widget() {
		$before_widget = '<div class="row"><div id="%1$s" class="widget %2$s"><div class="widget__inner %2$s__inner c-widget-wrap">';

		return $before_widget;
	}

	add_filter( 'wp_manga_sidebar_after_widget', 'madara_sidebar_filter_after_widget', 999 );
	function madara_sidebar_filter_after_widget() {
		$after_widget = '</div></div></div>';

		return $after_widget;
	}

	add_filter( 'wp_manga_sidebar_before_title', 'madara_sidebar_before_title', 999 );
	function madara_sidebar_before_title() {
		$before_title = '<div class="widget-title"><h4 class="heading">';

		return $before_title;
	}

	add_filter( 'wp_manga_sidebar_after_title', 'madara_sidebar_after_title', 999 );
	function madara_sidebar_after_title() {
		$after_title = '</h4></div>';

		return $after_title;
	}

	if ( class_exists( 'WP_MANGA' ) ) {

		/*
		 * check plugin wp-manga active or not.
		 * */

		add_action( 'madara_add_manga_sidebar', 'madara_add_manga_sidebar' );

		function madara_add_manga_sidebar() {

			register_sidebar( array(
				'name'          => esc_html__( 'WP Manga - Main Top Sidebar', 'madara' ),
				'id'            => 'manga_main_top_sidebar',
				'description'   => esc_html__( 'Appear before main content in Manga Pages', 'madara' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s"><div class="widget__inner %2$s__inner c-widget-wrap">',
				'after_widget'  => '</div></div>',
				'before_title'  => '<div class="widget-title"><div class="c-blog__heading style-2 font-heading"><h4>',
				'after_title'   => '</h4></div></div>',
			) );

			register_sidebar( array(
				'name'          => esc_html__( 'WP Manga - Main Top Second Sidebar', 'madara' ),
				'id'            => 'manga_main_top_second_sidebar',
				'description'   => esc_html__( 'Appear before main content in Manga Pages', 'madara' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s"><div class="widget__inner %2$s__inner c-widget-wrap">',
				'after_widget'  => '</div></div>',
				'before_title'  => '<div class="widget-title"><div class="c-blog__heading style-2 font-heading"><h4>',
				'after_title'   => '</h4></div></div>',
			) );

			register_sidebar( array(
				'name'          => esc_html__( 'WP Manga - Main Bottom Sidebar', 'madara' ),
				'id'            => 'manga_main_bottom_sidebar',
				'description'   => esc_html__( 'Appear after main content in Manga Pages', 'madara' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s"><div class="widget__inner %2$s__inner c-widget-wrap">',
				'after_widget'  => '</div></div>',
				'before_title'  => '<div class="widget-title"><div class="c-blog__heading style-2 font-heading"><h4>',
				'after_title'   => '</h4></div></div>',
			) );

		}

	}

	function madara_output_background_options( $header_bg = '', $echo = 0 ) {

		$header_style = '';

		if ( $header_bg != '' ) {

			$header_bg = Madara::getOption( $header_bg );

			if ( is_array( $header_bg ) ) {
				$header_bg_repeat     = isset( $header_bg['background-repeat'] ) ? $header_bg['background-repeat'] : '';
				$header_bg_attachment = isset( $header_bg['background-attachment'] ) ? $header_bg['background-attachment'] : '';
				$header_bg_position   = isset( $header_bg['background-position'] ) ? $header_bg['background-position'] : '';
				$header_bg_size       = isset( $header_bg['background-size'] ) ? $header_bg['background-size'] : '';
				$header_bg_image      = isset( $header_bg['background-image'] ) ? $header_bg['background-image'] : '';
				$header_bg_color      = isset( $header_bg['background-color'] ) ? $header_bg['background-color'] : '';

				if ( ! empty( $header_bg ) ) {

					if ( $header_bg_color != '' ) {
						$header_style .= 'background-color:' . $header_bg_color . '; ';
					}

					if ( $header_bg_image != '' ) {
						$header_style .= 'background-image:url(' . esc_url( $header_bg_image ) . '); ';
					}

					if ( $header_bg_repeat != '' ) {
						$header_style .= 'background-repeat:' . $header_bg_repeat . '; ';
					}

					if ( $header_bg_attachment != '' ) {
						$header_style .= 'background-attachment:' . $header_bg_attachment . '; ';
					}

					if ( $header_bg_size != '' ) {
						$header_style .= 'background-size:' . $header_bg_size . '; ';
					}

					if ( $header_bg_position != '' ) {
						$header_style .= 'background-position:' . $header_bg_position . '; ';
					}

				}
			}

		}

		if ( $echo == 1 ) {
			echo esc_html( $header_style );
		} else {
			return $header_style;
		}
	}

	function madara_output_sidebar_container_classes( $option = '', $default_value = '', $echo = 0 ) {
		$container_class = '';

		if ( $option != '' ) {

			$option = Madara::getOption( $option, $default_value );

			if ( $option == 'container' ) {
				$container_class = 'container c-container';
			} else if ( $option == 'full_width' ) {
				$container_class = 'container-fluid c-container-fluid';
			} else {
				$container_class = 'container custom-width c-container';
			}
		}

		if ( $echo == 1 ) {
			echo esc_attr( $container_class );
		} else {
			return $container_class;
		}

	}

	function madara_output_spacing_options( $option = '', $default_value = '', $echo = 0 ) {

		$spacing = '';

		if ( $option != '' ) {
			$option = Madara::getOption( $option, $default_value );
			if ( is_array( $option ) ) {

				$unit   = isset( $option['unit'] ) ? $option['unit'] : 'px';
				$top    = isset( $option['top'] ) ? $option['top'] . $unit . ' ' : '';
				$right  = isset( $option['right'] ) ? $option['right'] . $unit . ' ' : '';
				$bottom = isset( $option['bottom'] ) ? $option['bottom'] . $unit . ' ' : '';
				$left   = isset( $option['left'] ) ? $option['left'] . $unit . ' ' : '';

				if ( $top != '' || $right != '' || $bottom != '' || $left != '' ) {
					$spacing .= ' padding:' . $top . $right . $bottom . $left . '; ';
				}
			}
		}

		if ( $echo == 1 ) {
			echo esc_html( $spacing );
		} else {
			return $spacing;
		}

	}

	add_filter( 'upload_mimes', 'madara_add_custom_upload_mimes' );
	function madara_add_custom_upload_mimes( $existing_mimes ) {

		$existing_mimes['otf']  = 'application/x-font-otf';
		$existing_mimes['woff'] = 'application/x-font-woff';
		$existing_mimes['ttf']  = 'application/x-font-ttf';
		$existing_mimes['svg']  = 'image/svg+xml';
		$existing_mimes['eot']  = 'application/vnd.ms-fontobject';

		return $existing_mimes;
	}
