<?php


	use App\Madara;

	function madara_scripts() {

		if ( class_exists( 'WP_MANGA' ) ) {

			//dequeue duplicated script and style from manga plugin
			$styles = array(
				'wp-manga-plugin-css',
				'wp-manga-bootstrap-css',
				'wp-manga-slick-css',
				'wp-manga-slick-theme-css',
				'wp-manga-font-awesome',
				'wp-manga-ionicons',
				'wp-manga-font'
			);

			foreach ( $styles as $style ) {
				wp_dequeue_style( $style );
			}
			wp_dequeue_script( 'slick' );
			wp_dequeue_script( 'wp-manga-bootstrap-js' );

		}

		//enqueue madara ajax for manga archive
		if ( is_manga_archive() || ( is_page_template( 'page-templates/front-page.php' ) && Madara::getOption( 'page_content' ) == 'manga' ) ) {
			wp_enqueue_script( 'madara-ajax', get_parent_theme_file_uri( '/js/ajax.js' ), array( 'jquery' ), '', true );

			$manga_hover_details = Madara::getOption( 'manga_hover_details', 'off' );
			if ( $manga_hover_details == 'on' ) {
				wp_enqueue_script( 'madara_ajax_hover_content', get_parent_theme_file_uri( '/js/manga-hover.js' ), array( 'jquery' ), '', true );
				wp_localize_script( 'madara_ajax_hover_content', 'madara_hover_load_post', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
			}
		}

		//user history for manga reading page
		if ( is_manga_reading_page() && is_user_logged_in() ) {
			wp_enqueue_script( 'user_history', get_parent_theme_file_uri( '/js/history.js' ), array( 'jquery' ), '', true );
			wp_localize_script( 'user_history', 'user_history_params', array(
				'ajax_url' => admin_url() . 'admin-ajax.php',
				'postID'   => get_the_ID(),
				'chapter'  => get_query_var( 'chapter' ),
				'page'     => isset( $_GET['manga-paged'] ) ? $_GET['manga-paged'] : '',
			) );
		}

	}

	add_action( 'wp_enqueue_scripts', 'madara_scripts', 1001 );

	function madara_user_avatar( $avatar, $id_or_email, $args_size, $args_default, $args_alt, $args ) {

		if ( is_numeric( $id_or_email ) ) {
			$user_id = $id_or_email;
		} elseif ( isset( $id_or_email->user_id ) ) {
			$user_id = $id_or_email->user_id;
		} else {
			return $avatar;
		}

		$avatar_id = get_user_meta( $user_id, '_wp_manga_user_avt_id', true );

		if ( ! empty( $avatar_id ) ) {

			$url = wp_get_attachment_url( $avatar_id );

			if ( $url != false ) {
				$exploded   = explode( 'class=', $avatar );
				$new_avatar = "<img alt='" . esc_attr( $args_alt ) . "' src='$url' class=" . $exploded[1];

				return $new_avatar;
			}
		}

		return $avatar;
	}

	add_filter( 'get_avatar', 'madara_user_avatar', 10, 6 );

	function madara_get_manga_archive_sidebar() {

		$wp_manga_functions = madara_get_global_wp_manga_functions();

		if ( is_manga_archive_page() || is_manga_archive_front_page() ) { //if this is Manga Archive Page or Manga Archive Page Front-page then use page sidebar from Edit Page > Page Sidebar
			$manga_archive_page  = $wp_manga_functions->get_manga_archive_page_setting();
			$madara_page_sidebar = get_post_meta( $manga_archive_page, 'page_sidebar', true );
		}

		if ( ! isset( $madara_page_sidebar ) || $madara_page_sidebar == 'default' || $madara_page_sidebar == '' ) {
			$madara_page_sidebar = Madara::getOption( 'manga_archive_sidebar', 'right' );
		}

		return $madara_page_sidebar;

	}

	function is_manga_archive_front_page() {

		$wp_manga_functions = madara_get_global_wp_manga_functions();

		return $wp_manga_functions->is_manga_archive_front_page();

	}

	function is_manga_archive_page() {

		$wp_manga_functions = madara_get_global_wp_manga_functions();

		return $wp_manga_functions->is_manga_archive_page();

	}

	function is_manga_posttype_archive() {

		$wp_manga_functions = madara_get_global_wp_manga_functions();

		return $wp_manga_functions->is_manga_posttype_archive();

	}

	function is_manga_search_page() {

		$wp_manga_functions = madara_get_global_wp_manga_functions();

		return $wp_manga_functions->is_manga_search_page();

	}

	function is_manga_single() {

		$wp_manga_functions = madara_get_global_wp_manga_functions();

		return $wp_manga_functions->is_manga_single();

	}

	function is_manga_reading_page() {

		$wp_manga_functions = madara_get_global_wp_manga_functions();

		return $wp_manga_functions->is_manga_reading_page();

	}

	function is_manga_archive() {

		$wp_manga_functions = madara_get_global_wp_manga_functions();

		return $wp_manga_functions->is_manga_archive();

	}

	if ( ! function_exists( 'is_manga' ) ) {
		function is_manga() {

			if ( is_manga_single() || is_manga_archive() || is_manga_reading_page() || is_manga_search_page() ) {
				return true;
			}

			return false;
		}
	}

	function madara_update_user_settings() {

		//update account Settings
		$new_name    = isset( $_POST['user-new-name'] ) ? $_POST['user-new-name'] : '';
		$new_email   = isset( $_POST['user-new-email'] ) ? $_POST['user-new-email'] : '';
		$current_pwd = isset( $_POST['user-current-password'] ) ? $_POST['user-current-password'] : '';
		$new_pwd     = isset( $_POST['user-new-passsword'] ) ? $_POST['user-new-password'] : '';
		$confirm_pwd = isset( $_POST['user-new-password-confirm'] ) ? $_POST['user-new-password-confirm'] : '';

		$wp_nonce = isset( $_POST['_wpnonce'] ) ? $_POST['_wpnonce'] : '';
		$user_id  = isset( $_POST['userID'] ) ? $_POST['userID'] : '';

		if ( empty( $user_id ) || empty( $wp_nonce ) || ! wp_verify_nonce( $wp_nonce, '_wp_manga_save_user_settings' ) ) {
			return false;
		}

		//account Settings
		$user = array(
			'ID' => $user_id
		);

		if ( ! empty( $new_name ) ) {
			update_user_meta( $user_id, 'nickname', $new_name );
			$user['user_nicename'] = $new_name;
			$user['display_name']  = $new_name;
		}

		if ( ! empty( $new_email ) ) {
			$user['user_email'] = $new_email;
		}

		if ( ! empty( $current_pwd ) && ! empty( $new_pwd ) && ! empty( $confirm_pwd ) && ( $new_pwd !== $confirm_pwd ) ) {

			$user_obj = get_user_by( 'ID', $user_id );

			if ( ! is_wp_error( $user_obj ) && wp_check_password( $current_pwd, $user_obj->data->user_pass, $user_id ) ) {
				wp_set_password( $new_pwd, $user_id );
			}

		}

		if ( count( $user ) > 1 ) {
			$resp = wp_update_user( $user );

			if ( ! is_wp_error( $resp ) ) {
				return true;
			}
		}

		return false;

	}

	function madara_search_filter_url( $filter ) {

		$wp_manga = madara_get_global_wp_manga();

		return $wp_manga->wp_manga_search_filter_url( $filter );

	}

	if ( ! function_exists( 'madara_manga_query' ) ) {


		function madara_manga_query( $manga_args ) {

			$manga_args['post_type']   = 'wp-manga';
			$manga_args['post_status'] = 'publish';

			switch ( $manga_args['orderby'] ) {
				case 'alphabet' :
					$manga_args['orderby'] = 'post_title';
					$manga_args['order']   = 'ASC';
					break;
				case 'rating' :
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
			}

			$manga_query = new WP_Query( $manga_args );

			return apply_filters( 'madara_manga_query_filter', $manga_query, $manga_args );

		}
	}

	function madara_user_history() {
                
		$post_id      = isset( $_POST['postID'] ) ? $_POST['postID'] : '';
		$chapter_slug = isset( $_POST['chapterSlug'] ) ? $_POST['chapterSlug'] : '';
		$paged        = isset( $_POST['paged'] ) ? $_POST['paged'] : '';
		$img_id       = isset( $_POST['img_id'] ) ? $_POST['img_id'] : '';
		$user_id      = get_current_user_id();

		if ( empty( $post_id ) || empty( $chapter_slug ) || empty( $user_id ) ) {
			wp_send_json_error();
		}
                global $global_chapter_by_slug;
		//get chapter name
		if ( class_exists( 'WP_MANGA' ) ) {
//			$chapter = madara_get_global_wp_manga_chapter()->get_chapter_by_slug( $post_id, $chapter_slug );
                        $chapter = $global_chapter_by_slug;
		}

		$this_history = array(
			'id' => $post_id,
			'c'  => $chapter['chapter_id'],
			'p'  => $paged,
			'i'  => $img_id,
			't'  => current_time( 'timestamp' ),
		);

		$current_history = get_user_meta( $user_id, '_wp_manga_history', true );

		if ( empty( $current_history ) ) {

			$current_history = array( $this_history );

		} elseif ( is_array( $current_history ) ) { //if history already existed

			//if there are more than 12 manga in history
			if ( count( $current_history ) > 12 ) {
				unset( $current_history[ key( $current_history ) ] );
			}

			//check if current chapter is existed
			$index = array_search( $post_id, array_column( $current_history, 'id' ) );
			if ( $index !== false ) {
				$current_history[ $index ] = $this_history;
			} else {
				$current_history[] = $this_history;
			}
		}
		$response = update_user_meta( $user_id, '_wp_manga_history', $current_history );

		if ( $response == true ) {
			wp_send_json_success();
		}

		wp_send_json_error( $response );
	}

	add_action( 'wp_ajax_manga-user-history', 'madara_user_history' );
	add_action( 'wp_ajax_nopriv_manga-user-history', 'madara_user_history' );

	function madara_remove_history() {

		$post_id       = isset( $_POST['postID'] ) ? $_POST['postID'] : '';
		$user_id       = get_current_user_id();
		$history_manga = get_user_meta( $user_id, '_wp_manga_history', true );

		if ( empty( $post_id ) || empty( $history_manga ) ) {
			wp_send_json_error();
		}

		foreach ( $history_manga as $index => $manga ) {
			if ( $manga['id'] == $post_id ) {
				unset( $history_manga[ $index ] );
			}
		}

		$resp = update_user_meta( $user_id, '_wp_manga_history', $history_manga );

		if ( $resp == true ) {
			if ( empty( $history_manga ) ) {
				wp_send_json_success( array(
					'is_empty' => true,
					'msg'      => wp_kses( __( '<span>You haven\'t read any manga yet</span>', 'madara' ), array( 'span' => array() ) )
				) );
			};
			wp_send_json_success();
		}

		wp_send_json_error();
	}

	add_action( 'wp_ajax_manga-remove-history', 'madara_remove_history' );
	add_action( 'wp_ajax_nopriv_manga-remove-history', 'madara_remove_history' );

	function madara_get_reading_style( $reading_style = null ) {

		if ( is_user_logged_in() ) {

			$user_reading_style = get_user_meta( get_current_user_id(), '_manga_reading_style', true );
			if ( ! empty( $user_reading_style ) ) {
				return $user_reading_style;
			}

		}

		$reading_style = Madara::getOption( 'manga_reading_style', 'paged' );

		return $reading_style;

	}

	add_filter( 'get_reading_style', 'madara_get_reading_style' );

	function madara_get_img_per_page() {

		if ( is_user_logged_in() ) {

			$user_img_per_page = get_user_meta( get_current_user_id(), '_manga_img_per_page', true );

			if ( ! empty( $user_img_per_page ) ) {
				return $user_img_per_page;
			}
		}

		$img_per_page = Madara::getOption( 'manga_reading_images_per_page', '1' );

		return $img_per_page;

	}

	function madara_blog_search( $query ) {

		if ( ! is_manga_search_page() && is_search() && $query->get( 'post_type' ) !== 'nav_menu_item' ) {
			$query->set( 'post_type', 'post' );
		}

		return $query;

	}

	add_filter( 'pre_get_posts', 'madara_blog_search' );

	function madara_info_filter( $value ) {

		if ( empty( $value ) ) {
			$value = esc_html__( 'Updating', 'madara' );
		}

		return $value;

	}

	add_filter( 'wp_manga_info_filter', 'madara_info_filter' );

	function madara_hover_load_post() {

		$post_id = isset( $_REQUEST['postid'] ) && $_REQUEST['postid'] != '' ? intval( $_REQUEST['postid'] ) : '';

		if ( $post_id != '' ) {
			$post_content = get_post( $post_id );
			$post_excerpt = $post_content->post_content;
			$post_excerpt = wp_trim_words( $post_excerpt, apply_filters( 'mangasteam_hover_summary', 35 ) );

			$wp_manga_functions = madara_get_global_wp_manga_functions();
			$thumb_size         = array( 193, 278 );
			$alternative        = $wp_manga_functions->get_manga_alternative( $post_id );
			$authors            = $wp_manga_functions->get_manga_authors( $post_id );
			$artists            = $wp_manga_functions->get_manga_artists( $post_id );
			$genres             = $wp_manga_functions->get_manga_genres( $post_id );
			$rank               = $wp_manga_functions->get_manga_rank( $post_id );
			$views              = $wp_manga_functions->get_manga_monthly_views( 2544 );

			ob_start();

			?>

            <div id="manga-hover-<?php echo esc_attr( $post_id ) ?>" class="infor_items">
                <div class="infor_item__wrap">
                    <div class="item_thumb">
                        <div class="thumb_img">
							<?php
								if ( has_post_thumbnail( $post_id ) ) {
									?>
                                    <a href="<?php echo get_the_permalink( $post_id ); ?>" title="<?php echo get_the_title( $post_id ); ?>">
										<?php echo madara_thumbnail( $thumb_size, $post_id ); ?>
                                    </a>
									<?php
								}
							?>
                        </div>
                        <div class="post-title font-title">
                            <h5>
                                <a href="<?php echo get_the_permalink( $post_id ); ?>"><?php echo get_the_title( $post_id ); ?></a>
                            </h5>
                        </div>
                    </div>
                    <div class="item_content">
                        <div class="post-content">

                            <div class="meta-item rating">
								<?php
									$wp_manga_functions->manga_rating_display( $post_id );
								?>
                            </div>

                            <div class="post-content_item item_rank">
                                <div class="summary-heading">
                                    <h5>
										<?php echo esc_attr__( 'Rank', 'madara' ); ?>
                                    </h5>
                                </div>
                                <div class="summary-content">
									<?php echo sprintf( _n( ' %1s, it has %2s monthly view', ' %1s, it has %2s monthly views', $views, 'madara' ), $rank, $views ); ?>
                                </div>
                            </div>
							<?php if ( $alternative ): ?>
                                <div class="post-content_item item_alternative">
                                    <div class="summary-heading">
                                        <h5>
											<?php echo esc_attr__( 'Alternative', 'madara' ); ?>
                                        </h5>
                                    </div>
                                    <div class="summary-content">
										<?php echo wp_kses_post( $alternative ); ?>
                                    </div>
                                </div>
							<?php endif ?>

							<?php if ( $authors ): ?>
                                <div class="post-content_item item_authors">
                                    <div class="summary-heading">
                                        <h5>
											<?php echo esc_attr__( 'Author(s)', 'madara' ); ?>
                                        </h5>
                                    </div>
                                    <div class="summary-content">
										<?php echo wp_kses_post( $authors ); ?>
                                    </div>
                                </div>
							<?php endif ?>

							<?php if ( $artists ): ?>
                                <div class="post-content_item item_artists">
                                    <div class="summary-heading">
                                        <h5>
											<?php echo esc_attr__( 'Artist(s)', 'madara' ); ?>
                                        </h5>
                                    </div>
                                    <div class="summary-content">
                                        <div class="artist-content">
											<?php echo wp_kses_post( $artists ); ?>
                                        </div>
                                    </div>
                                </div>
							<?php endif ?>

							<?php if ( $genres ): ?>
                                <div class="post-content_item item_genres">
                                    <div class="summary-heading">
                                        <h5>
											<?php echo esc_attr__( 'Genre(s)', 'madara' ); ?>
                                        </h5>
                                    </div>
                                    <div class="summary-content">
                                        <div class="genres-content">
											<?php echo wp_kses_post( $genres ); ?>
                                        </div>
                                    </div>
                                </div>
							<?php endif ?>

							<?php if ( $post_excerpt ): ?>
                                <div class="post-content_item item_summary">
                                    <div class="summary-heading">
                                        <h5>
											<?php echo esc_attr__( 'Summary', 'madara' ); ?>
                                        </h5>
                                    </div>
                                    <div class="summary-content">
										<?php echo wp_kses_post( $post_excerpt ); ?>
                                    </div>
                                </div>
							<?php endif ?>
                        </div>
                    </div>
                </div>
            </div>

			<?php

			$html = ob_get_contents();

			ob_end_clean();

			echo $html;

		}

		die();

	}

	add_action( 'wp_ajax_nopriv_madara_hover_load_post', 'madara_hover_load_post' );
	add_action( 'wp_ajax_madara_hover_load_post', 'madara_hover_load_post' );

	add_filter( 'madara_thumbnail_size_filter', 'madara_thumbnail_size_filter', 10, 2 );
	function madara_thumbnail_size_filter( $size, $post_id ) {

		if ( has_post_thumbnail( $post_id ) ) {
			$thumb_url  = get_the_post_thumbnail_url( $post_id );
			$thumb_type = 'gif';
			if ( $thumb_url != '' ) {
				$type = substr( $thumb_url, - 3 );
			}
		}

		$allow_thumb_gif = Madara::getOption( 'manga_single_allow_thumb_gif', 'off' );

		if ( $allow_thumb_gif == 'on' && $thumb_type == $type ) {
			$size = 'full';
		}

		return $size;
	}

	function madara_page_reading_ajax(){

		$reading_style = madara_get_reading_style();

		if( $reading_style == 'paged' ){
			$ajax = Madara::getOption('manga_page_reading_ajax', 'on');

			if( $ajax == 'on' ){
				return true;
			}
		}

		return false;

	}

	add_filter( 'pre_get_document_title', 'change_title_for_manga_single' );
	function change_title_for_manga_single( $title ) {
		global $post, $wp_manga_chapter, $wp_manga_setting, $global_chapter_by_slug;
		if ( is_single() && isset( $post->post_type ) && $post->post_type == 'wp-manga' && get_query_var( 'chapter' ) != '' ) {

			$single_manga_seo = $wp_manga_setting->get_manga_option( 'single_manga_seo', 'manga' );
			$site_name        = get_bloginfo( 'name' );

			$chapter_slug = get_query_var( 'chapter' );
//			$chapter      = $wp_manga_chapter->get_chapter_by_slug( $post->ID, $chapter_slug );
                        $chapter = $global_chapter_by_slug;
			$chapter_name = $chapter['chapter_name'];

			$title = $post->post_title . ' - ' . $chapter_name;
			if ( $single_manga_seo == 1 ) {
				$title .= ' - ' . $site_name;
			}

			return $title;
		}

		return $title;
	}

	function madara_reading_page_classes( $classes ) {

		if ( is_manga_reading_page() && Madara::getOption( 'manga_reading_dark_mode', 'off' ) == 'on' ) {
			if ( ( $key = array_search( 'text-ui-dark', $classes ) ) !== false ) {
				unset( $classes[ $key ] );
			}
			if ( ! isset( $classes['text-ui-light'] ) ) {
				$classes[] = 'text-ui-light';
			}
		}

		return $classes;
	}

	add_filter( 'body_class', 'madara_reading_page_classes', 11 );
