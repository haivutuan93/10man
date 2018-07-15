<?php

	class WP_MANGA_POST_TYPE {

		public function __construct() {
			add_action( 'init', array( $this, 'wp_manga' ) );

			add_action( 'add_meta_boxes', array( $this, 'wp_manga_meta_box' ) );
			add_action( 'save_post', array( $this, 'wp_manga_data_save' ), 10, 2 );
			add_filter( 'post_row_actions', array( $this, 'wp_manga_download_hook' ), 10, 2 );
			add_filter( 'register_post_type_args', array( $this, 'wp_manga_slug' ), 100, 2 );

			add_action( 'admin_footer', array( $this, 'wp_manga_popup' ) );

			add_action( 'init', array( $this, 'wp_manga_rewrite_rules' ) );

			add_action( 'updated_user_meta', array( $this, 'show_manga_taxonomies' ), 10, 4 );

			add_filter( 'madara_manga_query_filter', array( $this, 'search_c_extend_name'), 10, 2 );
		}

		function wp_manga()
		{
			register_post_type( 'wp-manga', array(
				'description' => __( 'Manga Name', WP_MANGA_TEXTDOMAIN ),
				'labels' => array(
					'name'               => esc_html__( 'Manga',                   WP_MANGA_TEXTDOMAIN ),
					'singular_name'      => esc_html__( 'Manga',                    WP_MANGA_TEXTDOMAIN ),
					'menu_name'          => esc_html__( 'Manga',                  WP_MANGA_TEXTDOMAIN ),
					'all_items'          => esc_html__( 'All Manga',               WP_MANGA_TEXTDOMAIN ),
					'add_new'            => esc_html__( 'Add New',                    WP_MANGA_TEXTDOMAIN ),
					'add_new_item'       => esc_html__( 'Add New Manga',            WP_MANGA_TEXTDOMAIN ),
					'edit_item'          => esc_html__( 'Edit Manga',               WP_MANGA_TEXTDOMAIN ),
					'new_item'           => esc_html__( 'New Manga',                WP_MANGA_TEXTDOMAIN ),
					'view_item'          => esc_html__( 'View Manga',               WP_MANGA_TEXTDOMAIN ),
					'search_items'       => esc_html__( 'Search Manga',            WP_MANGA_TEXTDOMAIN ),
					'not_found'          => esc_html__( 'No Manga found',          WP_MANGA_TEXTDOMAIN ),
					'not_found_in_trash' => esc_html__( 'No Manga found in Trash', WP_MANGA_TEXTDOMAIN ),
				),
				'supports' => array(
					'title',
					'thumbnail',
					'publicize',
					'wpcom-markdown',
					'editor',
	                'comments'
				),
				'rewrite' => array(
					'slug'       => 'manga',
					'with_front' => false,
					'feeds'      => true,
					'pages'      => true,
				),
				'public'          => true,
				'show_ui'         => true,
				'menu_position'   => 20,
				'menu_icon'       => 'dashicons-book',
				'capability_type' => 'page',
				'map_meta_cap'    => true,
				'taxonomies'      => array( 'wp-manga-tag', 'wp-manga-release', 'wp-manga-author', 'wp-manga-category', 'wp-manga-artist' ),
				'has_archive'     => true,
				'query_var'       => WP_MANGA_TEXTDOMAIN,
			) );

			register_taxonomy( 'wp-manga-tag', array( 'wp-manga' ), array(
				'hierarchical'		=> false,
				'labels'			=> array(
					'name'				=> esc_html__( 'Manga Tags', WP_MANGA_TEXTDOMAIN ),
					'singular_name' 	=> esc_html__( 'Manga Tags', WP_MANGA_TEXTDOMAIN ),
					'menu_name'         => esc_html__( 'Manga Tags',         WP_MANGA_TEXTDOMAIN ),
					'all_items'         => esc_html__( 'All Manga Tags',     WP_MANGA_TEXTDOMAIN ),
					'edit_item'         => esc_html__( 'Edit Manga Tag',     WP_MANGA_TEXTDOMAIN ),
					'view_item'         => esc_html__( 'View Manga Tag',     WP_MANGA_TEXTDOMAIN ),
					'update_item'       => esc_html__( 'Update Manga Tag',   WP_MANGA_TEXTDOMAIN ),
					'add_new_item'      => esc_html__( 'Add Manga Tag',  WP_MANGA_TEXTDOMAIN ),
					'new_item_name'     => esc_html__( 'New Manga Tag', WP_MANGA_TEXTDOMAIN ),
					'parent_item'       => esc_html__( 'Parent Manga Tag',   WP_MANGA_TEXTDOMAIN ),
					'parent_item_colon' => esc_html__( 'Parent Manga Tag:',  WP_MANGA_TEXTDOMAIN ),
					'search_items'      => esc_html__( 'Search Manga Tag',  WP_MANGA_TEXTDOMAIN ),
				),
				'public'            => true,
				'show_ui'           => true,
				'show_in_menu' 		=> true,
				'show_admin_column' => true,
				'query_var'         => true,
				'rewrite'           => array( 'slug' => 'manga-tag' ),
			) );

			// Release year

			register_taxonomy( 'wp-manga-release' , array( 'wp-manga' ), array(
				'hierarchical'      => false,
				'labels'            => array(
					'name'              => esc_html__( 'Manga Release Year',         WP_MANGA_TEXTDOMAIN ),
					'singular_name'     => esc_html__( 'Manga Release Year',          WP_MANGA_TEXTDOMAIN ),
					'menu_name'         => esc_html__( 'Manga Release Year',         WP_MANGA_TEXTDOMAIN ),
					'all_items'         => esc_html__( 'All Manga Release Year',     WP_MANGA_TEXTDOMAIN ),
					'edit_item'         => esc_html__( 'Edit Manga Release Year',     WP_MANGA_TEXTDOMAIN ),
					'view_item'         => esc_html__( 'View Manga Release Year',     WP_MANGA_TEXTDOMAIN ),
					'update_item'       => esc_html__( 'Update Manga Release Year',   WP_MANGA_TEXTDOMAIN ),
					'add_new_item'      => esc_html__( 'Add New Manga Release Year',  WP_MANGA_TEXTDOMAIN ),
					'new_item_name'     => esc_html__( 'New Manga Release Year', 	WP_MANGA_TEXTDOMAIN ),
					'parent_item'       => esc_html__( 'Parent Manga Release Year',   WP_MANGA_TEXTDOMAIN ),
					'parent_item_colon' => esc_html__( 'Parent Manga Release Year:',  WP_MANGA_TEXTDOMAIN ),
					'search_items'      => esc_html__( 'Search Manga Release Year',  WP_MANGA_TEXTDOMAIN ),
					'separate_items_with_commas' => esc_html__( 'Separate Release Years with commas',      WP_MANGA_TEXTDOMAIN ),
					'choose_from_most_used'      => esc_html__( 'Choose from the most used release years', WP_MANGA_TEXTDOMAIN ),
				),
				'public'            => true,
				'show_ui'           => true,
				'show_in_menu' 		=> true,
				'show_admin_column' => true,
				'query_var'         => true,
				'rewrite'           => array( 'slug' => 'manga-release' ),
			));

			// Author

			register_taxonomy( 'wp-manga-author', array( 'wp-manga' ), array(
					'hierarchical'      => false,
					'labels'            => array(
					'name'                       => esc_html__( 'Manga Authors',                   WP_MANGA_TEXTDOMAIN ),
					'singular_name'              => esc_html__( 'Manga Author',                    WP_MANGA_TEXTDOMAIN ),
					'menu_name'                  => esc_html__( 'Manga Authors',                   WP_MANGA_TEXTDOMAIN ),
					'all_items'                  => esc_html__( 'All Manga Authors',               WP_MANGA_TEXTDOMAIN ),
					'edit_item'                  => esc_html__( 'Edit Manga Author',               WP_MANGA_TEXTDOMAIN ),
					'view_item'                  => esc_html__( 'View Manga Author',               WP_MANGA_TEXTDOMAIN ),
					'update_item'                => esc_html__( 'Update Manga Author',             WP_MANGA_TEXTDOMAIN ),
					'add_new_item'               => esc_html__( 'Add New Manga Author',            WP_MANGA_TEXTDOMAIN ),
					'new_item_name'              => esc_html__( 'New Manga Author Name',           WP_MANGA_TEXTDOMAIN ),
					'search_items'               => esc_html__( 'Search Manga Author',            WP_MANGA_TEXTDOMAIN ),
					'popular_items'              => esc_html__( 'Popular Manga Author',           WP_MANGA_TEXTDOMAIN ),
					'separate_items_with_commas' => esc_html__( 'Separate authors with commas',      WP_MANGA_TEXTDOMAIN ),
					'add_or_remove_items'        => esc_html__( 'Add or remove authors',             WP_MANGA_TEXTDOMAIN ),
					'choose_from_most_used'      => esc_html__( 'Choose from the most used authors', WP_MANGA_TEXTDOMAIN ),
					'not_found'                  => esc_html__( 'No authors found.',                 WP_MANGA_TEXTDOMAIN ),
				),
				'public'            => true,
				'show_ui'           => true,
				'show_in_nav_menus' => true,
				'show_admin_column' => true,
				'query_var'         => true,
				'rewrite'           => array( 'slug' => 'manga-author' ),
				));

				// artist
				register_taxonomy( 'wp-manga-artist', array( 'wp-manga' ), array(
					'hierarchical'      => false,
					'labels'            => array(
					'name'                       => esc_html__( 'Manga Artists',                   WP_MANGA_TEXTDOMAIN ),
					'singular_name'              => esc_html__( 'Manga Artist',                    WP_MANGA_TEXTDOMAIN ),
					'menu_name'                  => esc_html__( 'Manga Artists',                   WP_MANGA_TEXTDOMAIN ),
					'all_items'                  => esc_html__( 'All Manga Artists',               WP_MANGA_TEXTDOMAIN ),
					'edit_item'                  => esc_html__( 'Edit Manga Artist',               WP_MANGA_TEXTDOMAIN ),
					'view_item'                  => esc_html__( 'View Manga Artist',               WP_MANGA_TEXTDOMAIN ),
					'update_item'                => esc_html__( 'Update Manga Artist',             WP_MANGA_TEXTDOMAIN ),
					'add_new_item'               => esc_html__( 'Add New Manga Artist',            WP_MANGA_TEXTDOMAIN ),
					'new_item_name'              => esc_html__( 'New Manga Artist Name',           WP_MANGA_TEXTDOMAIN ),
					'search_items'               => esc_html__( 'Search Manga Artist',            WP_MANGA_TEXTDOMAIN ),
					'popular_items'              => esc_html__( 'Popular Manga Artist',           WP_MANGA_TEXTDOMAIN ),
					'separate_items_with_commas' => esc_html__( 'Separate artists with commas',      WP_MANGA_TEXTDOMAIN ),
					'add_or_remove_items'        => esc_html__( 'Add or remove Artists',             WP_MANGA_TEXTDOMAIN ),
					'choose_from_most_used'      => esc_html__( 'Choose from the most used Artists', WP_MANGA_TEXTDOMAIN ),
					'not_found'                  => esc_html__( 'No Artists found.',                 WP_MANGA_TEXTDOMAIN ),
				),
				'public'            => true,
				'show_ui'           => true,
				'show_in_nav_menus' => true,
				'show_admin_column' => true,
				'query_var'         => true,
				'rewrite'           => array( 'slug' => 'manga-artist' ),
				));

			// genre

			register_taxonomy( 'wp-manga-genre' , array( 'wp-manga' ), array(
					'hierarchical'      => true,
					'labels'            => array(
						'name'              => esc_html__( 'Manga Genres',         WP_MANGA_TEXTDOMAIN ),
						'singular_name'     => esc_html__( 'Manga Genre',          WP_MANGA_TEXTDOMAIN ),
						'menu_name'         => esc_html__( 'Manga Genres',         WP_MANGA_TEXTDOMAIN ),
						'all_items'         => esc_html__( 'All Manga Genres',     WP_MANGA_TEXTDOMAIN ),
						'edit_item'         => esc_html__( 'Edit Manga Genre',     WP_MANGA_TEXTDOMAIN ),
						'view_item'         => esc_html__( 'View Manga Genre',     WP_MANGA_TEXTDOMAIN ),
						'update_item'       => esc_html__( 'Update Manga Genre',   WP_MANGA_TEXTDOMAIN ),
						'add_new_item'      => esc_html__( 'Add New Manga Genre',  WP_MANGA_TEXTDOMAIN ),
						'new_item_name'     => esc_html__( 'New Manga Genre Name', WP_MANGA_TEXTDOMAIN ),
						'parent_item'       => esc_html__( 'Parent Manga Genre',   WP_MANGA_TEXTDOMAIN ),
						'parent_item_colon' => esc_html__( 'Parent Manga Genre:',  WP_MANGA_TEXTDOMAIN ),
						'search_items'      => esc_html__( 'Search Manga Genres',  WP_MANGA_TEXTDOMAIN ),
					),
					'public'            => true,
					'show_ui'           => true,
					'show_in_nav_menus' => true,
					'show_admin_column' => true,
					'query_var'         => true,
					'rewrite'           => array( 'slug' => 'manga-genre' ),
			));

			register_post_type( 'manga-bookmark', array(
				'rewrite' => array(
					'slug' => 'manga-bookmark'
				),
				'has_archive' => true,
				'query_var' => true,
				'publicly_queryable' => true,
				'public' => false,
				)
			);

			register_post_type( 'chapter_text_content', array(
				'rewrite' => array(
					'slug' => 'chapter_text_content'
				),
				'has_archive' => false,
				'query_var' => true,
				'publicly_queryable' => true,
				'public' => false,
				)
			);
		}

		function wp_manga_meta_box() {
			add_meta_box( 'manga-information-metabox', __( 'WP Manga', WP_MANGA_TEXTDOMAIN ), array(
					$this,
					'wp_manga_metabox'
				), 'wp-manga', 'normal', 'high' );

			add_meta_box( 'manga_status_settings', __( 'Manga Status', WP_MANGA_TEXTDOMAIN ), array(
					$this,
					'wp_manga_status'
				), 'wp-manga', 'side', 'high' );

			add_meta_box( 'manga_views', __( 'Manga Views', WP_MANGA_TEXTDOMAIN ), array(
					$this,
					'wp_manga_views'
				), 'wp-manga', 'side', 'high' );
		}

		function wp_manga_views( $post ) {
			$views = get_post_meta( $post->ID, '_wp_manga_views', true );

			if ( $views == false ) {
				$views = 0;
			}
			?>
            <input type="number" name="manga-views" value="<?php echo $views ?>"/>
			<?php
		}

		function wp_manga_status( $post ) {
			$status = get_post_meta( $post->ID, '_wp_manga_status', true );
			?>
            <select id="manga-status" name="manga-status">
                <option value="on-going" <?php selected( 'on-going', $status, true ); ?>><?php esc_attr_e( 'Chưa xong' ) ?></option>
                <option value="end" <?php selected( 'end', $status, true ); ?>><?php esc_attr_e( 'Hoàn thành' ) ?></option>
            </select>
			<?php
		}

		function wp_manga_metabox( $post ) {
			if ( file_exists( WP_MANGA_DIR . 'inc/admin-template/manga-single/manga-metabox.php' ) ) {
				include( WP_MANGA_DIR . 'inc/admin-template/manga-single/manga-metabox.php' );
			}

		}

		function wp_manga_data_save( $post_id, $post ) {

			if( $post->post_type !== 'wp-manga' ){
				return;
			}

			//set dummy data
			if( empty( get_post_meta( $post_id, 'manga_unique_id', true ) ) ){

				update_post_meta( $post_id, '_manga_avarage_reviews', 0 );
				update_post_meta( $post_id, '_wp_manga_week_views', 0 );

				if( !isset( $_POST['manga-views'] ) ){
					update_post_meta( $post_id, '_wp_manga_views', 0 );
				}

				//add unique id for manga post
				$uniqid = uniqid( 'manga_' );
				update_post_meta( $post_id, 'manga_unique_id', $uniqid );
			}

			if ( isset( $_POST['manga-status'] ) ) {
				update_post_meta( $post_id, '_wp_manga_status', $_POST['manga-status'] );
			}

			if( isset( $_POST['manga-views'] ) ){
				update_post_meta($post_id, '_wp_manga_views', $_POST['manga-views']);
			}

			if( isset( $_POST['wp-manga-alternative'] ) ){
				update_post_meta($post_id, '_wp_manga_alternative', $_POST['wp-manga-alternative']);
			}

			if( isset( $_POST['wp-manga-type'] ) ){
				update_post_meta($post_id, '_wp_manga_type', $_POST['wp-manga-type']);
			}

			$first_create = get_post_meta( $post_id, '_latest_update', true );
			if ( !$first_create || $first_create == '' ) {
				update_post_meta( $post_id, '_latest_update', current_time( 'timestamp', false ) );
			}

		}

		function wp_manga_download_hook( $actions, $post ) {

			if ( $post->post_type == 'wp-manga' ) {

				global $wp_manga_chapter;

				$chapters = $wp_manga_chapter->get_manga_chapters( $post->ID );

				if ( $chapters !== false ) {

					$output = '<a href="#" id="wp-manga-download" name="' . $post->ID . '"> Download Manga </a>';

					$output .= '<input type="hidden" name="post-name" value="' . get_the_title( $post->ID ) . '">';

					$actions['wp-manga-download'] = $output;

				}

				return $actions;
			}

			return $actions;

		}


		function wp_manga_popup() {

			global $pagenow, $post, $wp_manga;
			?>
			<div id="wp-manga-popup-all">
				<div class="wp-manga-popup-background">
				</div>
				<div class="wp-manga-popup-wrapper">
					<div id="wp-manga-popup-header">
						<h3 id="wp-manga-post-title"></h3>
						<div id="wp-manga-popup-exit">
							<i class="fa fa-times" aria-hidden="true"></i>
						</div>
					</div>
					<hr>
					<div id="wp-manga-popup-content">
						<input type="hidden" name="postID" value="">

						<button id="wp-manga-download-button" class="button button-primary"> <i class="fa fa-download"></i>
							<?php echo esc_html__( 'Download Manga', WP_MANGA_TEXTDOMAIN ); ?>
						</button>

						<div class="wp-manga-popup-content-msg"></div>

						<div class="wp-manga-popup-loading">
							<div class="wp-manga-popup-loading-wrapper">
			                    <i class="fa fa-spinner fa-spin"></i>
			                </div>
						</div>
					</div>
				</div>
			</div>
			<?php
		}

		function list_all_chapters( $post_id ) {

			global $wp_manga_functions;
			$chapters = $wp_manga_functions->get_all_chapters( $post_id );
			$output   = $wp_manga_functions->list_chapters_by_volume( $chapters, false );

			return $output;
		}

		function wp_manga_slug( $args, $post_type ) {

			if ( $post_type == 'wp-manga' ) {
				$wp_manga_settings = get_option( 'wp_manga_settings' );

				if ( ! empty( $wp_manga_settings['manga_slug'] ) ) {
					$args['rewrite']['slug'] = $wp_manga_settings['manga_slug'];
				}

				if ( ! empty( $wp_manga_settings['manga_post_type_archive_slug'] ) ) {
					$args['has_archive'] = $wp_manga_settings['manga_post_type_archive_slug'];
				}
			}

			return $args;

		}

		function allow_to_choose_chapter_type( $post_id ) {

			$chapter_type = get_post_meta( $post_id, '_wp_manga_chapter_type', true );

			if ( ! empty( $chapter_type ) ) {
				return false;
			}

			//if this is add new manga post page
			if ( ! isset( $_GET['post'] ) ) {
				return true;
			}

			//if this manga is already published but haven't any chapter, then it is allowed to choose type
			global $wp_manga_chapter;
			$chapters = $wp_manga_chapter->get_manga_chapters( $post_id );

			if ( empty( $chapters ) ) {
				return true;
			}

			return false;

		}

		function wp_manga_rewrite_rules() {

			$manga_post_type = get_post_type_object( 'wp-manga' );
			$slug = $manga_post_type->rewrite['slug'];

			//rewrite endpoint for chapter
			add_rewrite_endpoint( 'chapter', EP_PERMALINK );
			add_rewrite_endpoint( 'volume', EP_PERMALINK );

			add_rewrite_rule("{$slug}/([^/]+)/([^/]+)/([^/]+)/?$",'index.php?manga-core=$matches[1]&volume=$matches[2]&chapter=$matches[3]','top');

			add_rewrite_rule("{$slug}/([^/]+)/([^/]+)/?$",'index.php?manga-core=$matches[1]&chapter=$matches[2]','top');

		}

		//this func keep all manga taxonomies boxes are showed
		function show_manga_taxonomies( $meta_id, $object_id, $meta_key, $meta_value ){
			if( $meta_key == 'metaboxhidden_wp-manga' && !empty( $meta_value ) ){
				update_user_meta( $object_id, $meta_key, array() );
			}
		}

		function search_c_extend_name( $manga_query, $manga_args ){

			global $wp_manga_chapter;

			if( empty( $manga_args['s'] ) ){
				return $manga_query;
			}

			//Search Chapter Extend Name
			$chapters = $wp_manga_chapter->get_chapters( array(), $manga_args['s'], 'date' );

			if( !empty( $chapters ) ){

				$post_ids = array_column( $chapters, 'post_id' );

				$search_posts = new WP_Query(
					array(
						'post_type' => 'wp-manga',
						'post__in' => $post_ids
					)
				);

				if( $search_posts->have_posts() ){
					$manga_query->posts = array_merge( $manga_query->posts, $search_posts->posts );

					$manga_query->post_count += $search_posts->post_count;
					$manga_query->found_posts += $search_posts->found_posts;
				}

			}

			return $manga_query;
		}

	}

	$GLOBALS['wp_manga_post_type'] = new WP_MANGA_POST_TYPE();
