<?php

class WP_MANGA_AJAX {

	public function __construct() {
                //add list chapter in reading manga page
                add_action('wp_ajax_wp-manga-list-chapter-reading', array( $this,'wp_manga_list_chapter_reading' ) );

		// upload manga in backend
		add_action('wp_ajax_wp-manga-upload-chapter', array( $this,'wp_manga_upload_chapter' ) );

		// get manga
		add_action('wp_ajax_wp-manga-get-chapter', array( $this,'wp_manga_get_chapter' ) );

		// save manga paging ( back-end )
		add_action('wp_ajax_wp-manga-save-chapter-paging', array( $this,'wp_save_chapter_paging' ) );

		// download manga chapter ( back-end )
		add_action('wp_ajax_wp-manga-download-chapter', array( $this, 'wp_manga_download_chapter' ) );

		// delete manga chapter ( back-end )
		add_action('wp_ajax_wp-manga-delete-chapter', array( $this,'wp_manga_delete_chapter' ) );

		// create volume ( back-end )
		add_action('wp_ajax_wp-manga-create-volume', array( $this,'wp_manga_create_volume' ) );

		// save rating when user click ( front-end )
		add_action('wp_ajax_wp-manga-save-rating', array( $this,'wp_manga_save_rating' ) );
		add_action('wp_ajax_nopriv_wp-manga-save-rating', array( $this, 'wp_manga_save_rating') );

		// get next manga in list page ( front-end )
		add_action('wp_ajax_wp-manga-get-next-manga', array( $this,'wp_manga_get_next_manga' ) );
		add_action('wp_ajax_nopriv_wp-manga-get-next-manga', array( $this, 'wp_manga_get_next_manga') );

		// bookmark manga
		add_action('wp_ajax_wp-manga-user-action', array( $this,'wp_manga_user_action' ) );
		add_action('wp_ajax_nopriv_wp-manga-user-action', array( $this, 'wp_manga_user_action') );

		// delete bookmark manga
		add_action('wp_ajax_wp-manga-delete-bookmark', array( $this,'wp_manga_delete_bookmark' ) );
		add_action('wp_ajax_nopriv_wp-manga-delete-bookmark', array( $this, 'wp_manga_delete_bookmark') );

		add_action('wp_ajax_wp-manga-delete-multi-bookmark', array( $this,'wp_manga_delete_multi_bookmark' ) );
		add_action('wp_ajax_nopriv_wp-manga-delete-multi-bookmark', array( $this, 'wp_manga_delete_multi_bookmark') );

		// search manga
		add_action('wp_ajax_wp-manga-search-manga', array( $this,'wp_manga_search_manga' ) );
		add_action('wp_ajax_nopriv_wp-manga-search-manga', array( $this, 'wp_manga_search_manga') );

		//delete_zip
		add_action('wp_ajax_wp-manga-delete-zip', array( $this, 'wp_manga_delete_zip' ) );
		add_action('wp_ajax_nopriv_wp-manga-delete-zip', array( $this, 'wp_manga_delete_zip' ) );

		//show chapters to download
		add_action('wp_ajax_wp-manga-chapters-to-down', array( $this, 'wp_manga_chapters_to_down' ) );

		//download manga
		add_action('wp_ajax_wp-download-manga', array( $this, 'wp_download_manga' ) );

		//upload manga (multi chapters)
        add_action('wp_ajax_wp-manga-upload', array( $this, 'wp_manga_upload' ) );

        add_action('wp_ajax_wp-update-chapters-list', array( $this, 'wp_update_chapters_list' ) );

        add_action( 'wp_ajax_search-chapter', array( $this, 'wp_manga_search_chapter' ) );

        //duplicate server
        add_action( 'wp_ajax_wp-manga-duplicate-server', array( $this, 'wp_manga_duplicate_server' ) );

        add_action( 'wp_ajax_wp-manga-upload-avatar', array( $this, 'wp_manga_upload_avatar' ) );
        add_action( 'wp_ajax_nopriv_wp-manga-upload-avatar', array( $this, 'wp_manga_upload_avatar' ) );

		add_action( 'wp_ajax_wp-manga-get-user-section', array( $this, 'wp_manga_get_user_section' ) );
		add_action( 'wp_ajax_nopriv_wp-manga-get-user-section', array( $this, 'wp_manga_get_user_section' ) );

		//save page setting in first install page
		add_action( 'wp_ajax_wp_manga_first_install_page_save', array( $this, 'wp_manga_first_install_page_save') );

		//save post type setting in first install page
		add_action( 'wp_ajax_wp_manga_first_install_post_save', array( $this, 'wp_manga_first_install_post_save') );

		add_action( 'wp_ajax_wp_manga_skip_first_install', array( $this, 'wp_manga_skip_first_install' ) );

		add_action( 'wp_ajax_update_picasa_album_dropdown', array( $this, 'update_picasa_album_dropdown' ) );

		//change volume name
		add_action( 'wp_ajax_update_volume_name', array( $this, 'update_volume_name' ) );

		//delete volume
		add_action( 'wp_ajax_wp_manga_delete_volume', array( $this, 'wp_manga_delete_volume' ) );

		add_action( 'wp_ajax_wp_manga_archive_loadmore', array( $this, 'wp_manga_archive_loadmore' ) );

		add_action( 'wp_ajax_wp_manga_clean_temp_folder', array( $this, 'wp_manga_clean_temp_folder' ) );

		add_action( 'wp_ajax_wp_manga_create_content_chapter', array( $this, 'wp_manga_create_content_chapter' ) );

		add_action( 'wp_ajax_wp_manga_save_chapter_type', array( $this, 'wp_manga_save_chapter_type' ) );

		add_action( 'wp_ajax_replace_blogspot_url', array( $this, 'replace_blogspot_url' ) );

		add_action( 'wp_ajax_chapter_content_upload', array( $this, 'chapter_content_upload' ) );

		add_action( 'wp_ajax_chapter_navigate_page', array( $this, 'chapter_navigate_page' ) );
		add_action( 'wp_ajax_nopriv_chapter_navigate_page', array( $this, 'chapter_navigate_page' ) );

	}
        
        function wp_manga_list_chapter_reading($post_id) {
            $post_id = $_POST[postID];
            $cur_chap = $_POST[curChap];
            global $wp_manga_chapter, $wp_manga_text_type, $wp_manga;
            
            $chapter  = $wp_manga_chapter->get_chapter_by_slug( $post_id, $cur_chap );
            $all_chapters = madara_get_global_wp_manga_functions()->get_all_chapters($post_id);
            $all_chaps = $all_chapters[$chapter['volume_id']][chapters];
            
            $args = array(
				'cur_chap'  => $cur_chap,
				'chapter'   => $chapter,
				'all_chaps' => $all_chaps,
				'position'  => 'header',
			);
            $outout = '';
                        
            ob_start();
            $wp_manga_text_type->manga_nav_ajax( $args, $post_id );
            $outout = ob_get_contents();
            ob_end_clean();
            
            echo $outout;
        }

	function chapter_navigate_page(){

		if( empty( $_GET['postID'] ) ){
			$this->send_json( 'error', esc_html__('Missing post ID', WP_MANGA_TEXTDOMAIN ) );
		}

		if( empty( $_GET['manga-paged'] ) ){
			$this->send_json( 'error', esc_html__('Missing Query Page', WP_MANGA_TEXTDOMAIN ) );
		}

		if( empty( $_GET['chapter'] ) ){
			$this->send_json( 'error', esc_html__('Missing Chapter param', WP_MANGA_TEXTDOMAIN ) );
		}

		global $wp_manga_template, $wp_manga_chapter_type, $wp_manga_chapter, $wp_manga_volume;
		global $post, $wp_query;

		$this_post = get_post( $_GET['postID'] );

		$post = $this_post;
		$wp_query->set('chapter', $_GET['chapter']);

		$output = array();

		//get content for navigation
		// $this_chap = $wp_manga_chapter->get_chapter_by_slug( $_GET['postID'], $_GET['chapter'] );
		//
		// if( !$this_chap ){
		// 	$this->send_json( 'error', esc_html__('Cannot find this chapter', WP_MANGA_TEXTDOMAIN ) );
		// }
		//
		// $all_chaps = $wp_manga_volume->get_volume_chapters( $_GET['postID'], $this_chap['volume_id'], 'name', 'asc' );
		//
		// ob_start();
		// $wp_manga_chapter_type->manga_pager( $_GET['manga-paged'], $_GET['total-page'], 'paged', $all_chaps );
		//
		// $output['navigation'] = ob_get_contents();
		// ob_end_clean();

		ob_start();
		$wp_manga_template->load_template('reading-content/content-reading', 'paged');
		$output = ob_get_contents();
		ob_end_clean();

		$this->send_json('success', '', $output);

	}

	function chapter_content_upload(){

		if( empty( $_FILES['file'] ) ){
			wp_send_json_error( array( 'message' => esc_html__('Missing File', WP_MANGA_TEXTDOMAIN) ) );
		}

		if( empty( $_POST['postID'] ) ){
			wp_send_json_error( array( 'message' => esc_html__('Missing Post ID', WP_MANGA_TEXTDOMAIN ) ) );
		}

		if( empty( $_POST['chapterType'] ) ){
			wp_send_json_error( array( 'message' => esc_html__('Missing Chapter Type', WP_MANGA_TEXTDOMAIN ) ) );
		}

		$post_id = $_POST['postID'];
		$volume = isset( $_POST['volume'] ) ? $_POST['volume'] : '';
		$chapter_type = $_POST['chapterType'];

		global $wp_manga_text_type;

		$response = $wp_manga_text_type->upload_handler( $post_id, $_FILES['file'] );

		if( $response['success'] ){
			wp_send_json_success( $response );
		}else{
			wp_send_json_error( $response );
		}

	}

	function replace_blogspot_url(){

		global $wp_manga_google_upload;
		$all_manga_dirs = glob( WP_MANGA_JSON_DIR . '/*' );

		foreach( $all_manga_dirs as $dir ){

			$manga_json = $dir . '/manga.json';

			if( !file_exists( $manga_json ) ){
				continue;
			}

			$manga_json_data = file_get_contents( $manga_json );
			$manga_data = json_decode( $manga_json_data, true );

			if( empty( $manga_data['chapters'] ) ){
				continue;
			}

			foreach( $manga_data['chapters'] as $chapter_id => $chapter ){

				if( !empty( $chapter['storage']['picasa'] ) ){
					var_dump( $chapter['storage']['picasa']['page'] );
					foreach( $chapter['storage']['picasa']['page'] as $page_num => $page ){
						$manga_data['chapters'][$chapter_id]['storage']['picasa']['page'][$page_num]['src'] = $wp_manga_google_upload->blogspot_url_filter( $page['src'] );
					}
				}
			}

			$fp = fopen( $manga_json , 'w');
			fwrite( $fp, json_encode( $manga_data ) );
			fclose( $fp );

		}

		update_option('_wp_manga_is_blogspot_replaced', true);
		wp_send_json_success();

	}

	function wp_manga_save_chapter_type(){

		$post_id = isset( $_POST['postID'] ) ? $_POST['postID'] : '';
		$chapter_type = isset( $_POST['chapterType'] ) ? $_POST['chapterType'] : '';

		if( empty( $post_id ) ){
			wp_send_json_error( esc_html__('Missing Post ID', WP_MANGA_TEXTDOMAIN ) );
		}

		if( empty( $chapter_type ) ){
			wp_send_json_error( esc_html__('Missing Chapter Type', WP_MANGA_TEXTDOMAIN ) );
		}

		if( !in_array( $chapter_type, array( 'manga', 'text', 'video' ) ) ){
			wp_send_json_error( esc_html__('Invalid Chapter Type', WP_MANGA_TEXTDOMAIN ) );
		}

		update_post_meta( $post_id, '_wp_manga_chapter_type', $chapter_type );

		wp_send_jsoN_success();

	}

	function wp_manga_create_content_chapter(){

		global $wp_manga_text_type;

		if( empty( $_POST['postID'] ) ){
			wp_send_json_error( array( 'message' => esc_html__('Missing Post ID', 'madara') ) );
		}

		$_POST = stripslashes_deep( $_POST );

		$chapter_args = array(
			'post_id'             => isset( $_POST['postID'] ) ? $_POST['postID'] : '',
			'chapter_name'        => isset( $_POST['chapterName'] ) ? $_POST['chapterName'] : '',
			'chapter_name_extend' => isset( $_POST['chapterNameExtend'] ) ? $_POST['chapterNameExtend'] : '',
			'volume_id'           => isset( $_POST['chapterVolume'] ) ? $_POST['chapterVolume'] : '',
			'chapter_content'     => isset( $_POST['chapterContent'] ) ? $_POST['chapterContent'] : '',
		);

		$resp = $wp_manga_text_type->insert_chapter( $chapter_args );

		wp_send_json_success( array( 'message' => esc_html__('Chapter is created successfully!', WP_MANGA_TEXTDOMAIN), 'data' => $resp ) );

	}

	function wp_manga_clean_temp_folder(){

		$post_id = isset( $_POST['postID'] ) ? $_POST['postID'] : '';

		if( empty( $post_id ) ){
			wp_send_json_error( __('Missing Post ID', WP_MANGA_TEXTDOMAIN ) );
		}

		global $wp_manga, $wp_manga_storage;
		$uniqid = $wp_manga->get_uniqid( $post_id );
		$paths_to_clean = get_transient( 'path_to_clean_' . $uniqid );

		if( $paths_to_clean ){
			foreach( $paths_to_clean as $path ){
				$wp_manga_storage->local_remove_storage( $path );
			}
		}

		delete_transient( 'path_to_clean_' . $uniqid );

		wp_send_json_success();

	}

	function wp_manga_archive_loadmore(){

		$manga_args = isset( $_POST['manga_args'] ) ? $_POST['manga_args'] : '';
		$template = $_POST['template'];

		if( empty( $manga_args ) ){
			wp_send_json_error();
		}

		global $wp_manga, $wp_manga_template;

		$manga_args['paged'] += 1;
		$manga_query = $wp_manga->mangabooth_manga_query( $manga_args );

		if( $manga_query->have_posts() ) {

			$wp_manga->wp_manga_query_vars_js( $manga_args );

			$index = 0;

			set_query_var( 'wp_manga_posts_per_page', $manga_query->post_count );
			set_query_var( 'wp_manga_paged', $manga_args['paged'] );

			while( $manga_query->have_posts() ){
				$index++;
				set_query_var( 'wp_manga_post_index', $index );

				$manga_query->the_post();
				$wp_manga_template->load_template( 'content/content', $template );
			}

			$args = $manga_query->query;
			$args['max_num_pages'] = $manga_query->max_num_pages;

			$wp_manga->wp_manga_query_vars_js( $args, true );

			die();

		}

		wp_reset_postdata();

		die(0);

	}

	function wp_manga_delete_volume(){

		$volume_id = isset( $_POST['volumeID'] ) ? $_POST['volumeID'] : '';
		$post_id = isset( $_POST['postID'] ) ? $_POST['postID'] : '';

		if( empty( $volume_id ) && $volume_id !== '0' ){
			wp_send_json_error( __('Missing Volume ID', WP_MANGA_TEXTDOMAIN ) );
		}

		global $wp_manga_storage;
		$wp_manga_storage->delete_volume( $post_id, $volume_id );

		wp_send_json_success();

	}

	function update_volume_name(){

		$post_id     = isset( $_POST['postID'] ) ? $_POST['postID'] : '';
		$volume_id   = isset( $_POST['volumeID'] ) ? $_POST['volumeID'] : '';
		$volume_name = isset( $_POST['volumeName'] ) ? $_POST['volumeName'] : '';

		if( empty( $volume_id ) ){
			wp_send_json_error( __('Missing Volume ID', WP_MANGA_TEXTDOMAIN ) );
		}

		global $wp_manga_volume;
		$args = array(
			'volume_id' => $volume_id,
		);

		if( !empty( $post_id ) ){
			$args['post_id'] = $post_id;
		}

		$result = $wp_manga_volume->update_volume( array( 'volume_name' => $volume_name ), $args );

		wp_send_json_success( $result );

	}

	function update_picasa_album_dropdown(){

		$album = get_option( 'google_latest_album', 'default' );
		$albums = $GLOBALS['wp_manga_google_upload']->get_album_list();
		$current_album = isset( $_POST['current_album'] ) ? $_POST['current_album'] : '';

		$html = '';

		foreach( $albums as $id => $album ){
			$html .= '<option value="'.$id.'"' . selected( $id, $current_album, false ) . '>' . sprintf( __('[Album] %s (having %d items)', WP_MANGA_TEXTDOMAIN ), $album['title'], $album['numphotos'] ) . '</option>';
		}

		wp_send_json_success( $html );

	}

	function wp_manga_get_user_section(){

		if( !is_user_logged_in() ) {
			wp_send_json_error();
		}

		global $wp_manga_functions;
		$user_section = $wp_manga_functions->get_user_section();

		if( $user_section !== false ) {
			wp_send_json_success( $user_section );
		}

		wp_send_json_error();

	}

    function wp_manga_upload_avatar() {

        $avatar_file = $_FILES['userAvatar'];
        $user_id = isset( $_POST['userID'] ) ? $_POST['userID'] : '';

        if( !isset( $_POST['_wpnonce'] ) || !wp_verify_nonce( $_POST['_wpnonce'], '_wp_manga_save_user_settings' ) || empty( $user_id ) ) {
            wp_send_json_error( array( 'msg' => __('I smell some cheating here', WP_MANGA_TEXTDOMAIN ) ) );
        }

        //handle upload
        require_once( ABSPATH . 'wp-admin/includes/admin.php' );
        $avatar = wp_handle_upload(  $avatar_file, array( 'test_form' => false ) );

        if( isset( $avatar['error'] ) || isset( $avatar['upload_error_handler'] ) ) {
            wp_send_json_error( array( 'msg' => __('Upload failed! Please try again later', WP_MANGA_TEXTDOMAIN ) ) );
        }

        //resize avatar
        $avatar_editor = wp_get_image_editor( $avatar['file'] );
        if( !is_wp_error( $avatar_editor ) ) {
            $avatar_editor->resize( 195, 195, false );
            $avatar_editor->save( $avatar['file'] );
        }

        //media upload
        $avatar_media = array(
            'post_mime_type'    => $avatar['type'],
            'post_title'        => '_wp_user_' . $user_id . '_avatar',
            'post_content'      => '',
            'post_status'       => 'inherit',
            'guid'              => $avatar['url'],
            'post_author'       => $user_id,
        );

        $avatar_id = wp_insert_attachment( $avatar_media, $avatar['url'] );

        if( $avatar_id == 0 ) {
            wp_send_json_error( array( 'msg' => __('Upload failed! Please try again later', WP_MANGA_TEXTDOMAIN ) ) );
        }

        //update metadata
        $user_meta = update_user_meta( $user_id, '_wp_manga_user_avt_id', $avatar_id );
        $avatar_meta = update_post_meta( $avatar_id, '_wp_manga_user_id', $user_id );

        if( !empty( $user_meta ) && !empty( $avatar_meta ) ) {
            wp_send_json_success( get_avatar( $user_id, 195 ) );
        }

    }

	function wp_manga_upload_chapter() {

        global $wp_manga, $wp_manga_storage, $wp_manga_functions, $wp_manga_chapter;

        if( !$_FILES ) {
            wp_send_json_error( array(
		      'message' => 'Upload fail!'
            ) );
        }

		$_POST = stripslashes_deep( $_POST );

		$post_id = $_POST['post'];
		$name    = $_POST['name'];
		$volume  = isset( $_POST['volume'] ) ? $_POST['volume']: '0';

		if( !isset( $_POST['c_overwrite'] ) ){ //if doesn't have c_overwrite means chapter upload isn't verified yet

			//then start to verify chapter name
			$data_uniq_chapter = $wp_manga_functions->check_unique_chapter( $name, $volume, $post_id );

			if( $data_uniq_chapter !== 'false' && !isset( $_POST['overwrite'] ) && isset( $data_uniq_chapter['output'] ) ) {
				wp_send_json_error( array(
					'error'   => 'chapter_existed',
					'message' => __( 'This Chapter name is already existed, please choose if you want to over write or create a new one', WP_MANGA_TEXTDOMAIN ),
					'output'  => $data_uniq_chapter['output'],
				) );
			}

		}

		$nameExtend = isset( $_POST['nameExtend'] ) ? $_POST['nameExtend'] : '';
		$storage    = $_POST['storage'];
		$chapter    = $_FILES[ key( $_FILES ) ];
		$overwrite  = isset( $_POST['overwrite'] ) ? $_POST['overwrite'] : 'false';

		//if storage is blogspot
		if( $storage == 'picasa' && isset( $_POST['picasa_album'] ) ){
			update_option( 'google_latest_album', $_POST['picasa_album'] );
		}

        //start extracting
        $uniqid = $wp_manga->get_uniqid( $post_id );

		if( ( isset( $_POST['overwrite'] ) && $_POST['overwrite'] == 'false' ) || isset( $data_uniq_chapter['overwrite'] ) ) { //if not overwrite
			$slugified_name = $data_uniq_chapter['c_uniq_slug'];
		}elseif( isset( $_POST['overwrite'] ) && $_POST['overwrite'] == 'true' && isset( $_POST['c_overwrite'] ) ){ //if overwrite chapter
			$c_overwrite = $wp_manga_chapter->get_chapter_by_id( $post_id, $_POST['c_overwrite'] );
			$wp_manga_storage->local_delete_chapter_files( $post_id, $_POST['c_overwrite'] );
			$slugified_name = $c_overwrite['chapter_slug'];
		}else{
			$slugified_name = $wp_manga_storage->slugify( $name );
		}

        if( $storage == 'local' ) {
            $wp_upload_dir = wp_upload_dir();
            $extract = WP_MANGA_DATA_DIR . $uniqid . '/' . $slugified_name;
            $extract_uri = WP_MANGA_DATA_URL;
        }

        if( $storage == 'imgur' || $storage == 'picasa' || $storage == 'amazon' ) {
            $extract = WP_MANGA_DIR . 'extract/temp/' . $uniqid . '/' . $slugified_name;
            $extract_uri = WP_MANGA_URI . 'extract/temp/' . $uniqid . '/' . $slugified_name;
        }

        $chapter_zip = new ZipArchive();
		if( $chapter_zip->open( $chapter['tmp_name'] ) ) {
            $chapter_zip->extractTo( $extract );
            $chapter_zip->close();
        }

		do_action( 'wp_manga_upload_after_extract', $post_id, $slugified_name, $extract, $storage );

		$chapter_args = array(
			'post_id'             => $post_id,
			'volume_id'           => $volume,
			'chapter_name'        => $name,
			'chapter_name_extend' => $nameExtend,
			'chapter_slug'        => $slugified_name,
		);

        //upload chapter
        $result = $wp_manga_storage->wp_manga_upload_single_chapter( $chapter_args, $extract, $extract_uri, $storage, $overwrite );

		if( $storage !== 'local' ){
			$wp_manga_storage->local_remove_storage( $extract );
		}

        if( !empty( $result ) ) {
            if( isset( $result['error'] ) ) {
				wp_send_json_error( $result );
			}else{
				wp_send_json_success( $result );
			}
        }else{
            wp_send_json_error( __('Upload failed. Please try again later', WP_MANGA_TEXTDOMAIN ) );
        }

	    exit;
	}

	function wp_manga_get_chapter() {

		global $wp_manga, $wp_manga_chapter;
		$postID       = isset( $_GET['postID'] ) ? $_GET['postID'] : null;
		$chapterID    = isset( $_GET['chapterID'] ) ? $_GET['chapterID'] : null;
		$chapter_type = !empty( $_GET['type'] ) ? $_GET['type'] : 'manga';

		if ( empty( $postID ) ) {
			wp_send_json_error( __('Missing Post ID', WP_MANGA_TEXTDOMAIN ) );
		}

		if( empty( $chapterID ) ){
			wp_send_json_error( __('Missing Chapter ID', WP_MANGA_TEXTDOMAIN ) );
		}

		$this_chapter = $wp_manga_chapter->get_chapter_data( $postID, $chapterID );

		if( !$this_chapter ){
			wp_send_json_error( __('Cannot find this Chapter', WP_MANGA_TEXTDOMAIN ) );
		}

		if( $chapter_type == 'text' || $chapter_type == 'video' ){

			/**
		 	 * Get text chapter and video chapter
		 	 */
			if( isset( $this_chapter['chapter_id'] ) ){

				global $wp_manga_text_type;

				//get chapter content
				$chapter_post_content = $wp_manga_text_type->get_chapter_content_post( $this_chapter['chapter_id'] );

				if( $chapter_post_content ){
					$chapter_data = array(
	                    'type'    => $chapter_type,
	                    'chapter' => $this_chapter,
	                    'data'    => $chapter_post_content->post_content,
	                );

	                wp_send_json_success( $chapter_data );
				}else{
	                wp_send_json_error( esc_html__('Cannot find Chapter Content Data', WP_MANGA_TEXTDOMAIN ) );
	            }
			}
		}else{

			/**
		 	 * Get manga chapter
		 	 */

			$uniqid = $wp_manga->get_uniqid( $postID );

			$json_storage = WP_MANGA_JSON_DIR . $uniqid . '/manga.json';

			if( !file_exists( $json_storage ) ){
				wp_send_json_error( __('Cannot find this JSON file', WP_MANGA_TEXTDOMAIN ) );
			}

			$raw = file_get_contents( $json_storage );
			$data = json_decode( $raw, true );

			if( !isset( $data['chapters'][$chapterID] ) ){
				wp_send_json_error( __('Cannot find this Chapter in JSON file', WP_MANGA_TEXTDOMAIN ) );
			}

			$manga = array(
				'type' => 'manga',
				'chapter' => $this_chapter,
				'data'    => $data['chapters'][$chapterID],
			);

			$available_host = $wp_manga->get_available_host();

			foreach( $data['chapters'][$chapterID]['storage'] as $host => $storage ){

				//skip inUse in storage array
				if( $host == 'inUse' ){
					continue;
				}

				//add storage name to return data
				$manga['data']['storage'][$host]['name'] = $available_host[$host]['text'];
				unset( $available_host[$host] );
			}

			if( !empty( $available_host ) ){
				$manga['available_host'] = $available_host;
			}

			wp_send_json_success( $manga );

		}

	}

	function wp_save_chapter_paging() {

		global $wp_manga_storage, $wp_manga_chapter;

		$_POST = stripslashes_deep( $_POST );

		$paging         = isset( $_POST['paging'] ) ? $_POST['paging'] : null;
		$postID         = isset( $_POST['postID'] ) ? $_POST['postID'] : null;
		$chapterID      = isset( $_POST['chapterID'] ) ? $_POST['chapterID'] : null;
		$storage        = isset( $_POST['storage'] ) ? $_POST['storage'] : null;
		$chapterNewName = isset( $_POST['chapterNewName'] ) ? $_POST['chapterNewName'] : null;
		$nameExtend     = isset( $_POST['chapterNameExtend'] ) ? $_POST['chapterNameExtend'] : null;
		$chapter_type   = isset( $_POST['chapterType'] ) ? $_POST['chapterType'] : 'manga';
		$chapterContent = isset( $_POST['chapterContent'] ) ? $_POST['chapterContent'] : '';

		$volume = isset( $_POST['volume'] ) ? $_POST['volume'] : '0';

		$chapter_args = array(
			'update'               => array(
				'volume_id'           => $volume,
				'chapter_name'        => $chapterNewName,
				'chapter_name_extend' => $nameExtend,
			),
			'args'                 => array(
				'post_id'             => $postID,
				'chapter_id'          => $chapterID,
			)
		);

		if( $chapter_type == 'manga' ){

			$result['file'] = $paging;
			$result['volume'] = $volume;

			//#needcheck tai sao phai get lai host?
			$result['host'] = $wp_manga_storage->get_host( $storage );

			$chapter_slug = $wp_manga_storage->slugify( $chapterNewName );
			$this_chapter = $wp_manga_chapter->get_chapter_by_id( $postID, $chapterID );

			if( $this_chapter['chapter_slug'] !== $chapter_slug ){
				$chapter_args['new_slug'] = array(
					'current_chapter_slug' => $this_chapter['chapter_slug'],
					'new_chapter_slug'     => $chapter_slug,
				);
			}

			$chapter_data = $wp_manga_storage->update_chapter( $chapter_args, $result, $storage );

			wp_send_json_success($chapter_data);

		}else{

			$wp_manga_chapter->update_chapter( $chapter_args['update'], $chapter_args['args'] );

			global $wp_manga_text_type;
			//update chapter content
			$chapter_post_content = $wp_manga_text_type->get_chapter_content_post( $chapterID );

			$resp = wp_update_post( array(
				'ID' => $chapter_post_content->ID,
				'post_content' => $chapterContent,
			) );

			wp_send_json_success( $resp );

		}
	}

	function wp_manga_delete_chapter() {

		global $wp_manga_storage;
		$postID    = isset( $_POST['postID'] ) ? $_POST['postID'] : null;
		$chapterID = isset( $_POST['chapterID'] ) ? $_POST['chapterID'] : null;

		$wp_manga_storage->delete_chapter( $postID, $chapterID );

		wp_send_json_success( $chapterID );

	}

	function wp_manga_create_volume() {
		global $wp_manga_storage;
		$volumeName = isset( $_POST['volumeName'] ) ? $_POST['volumeName'] : null;
		$postID = isset( $_POST['postID'] ) ? $_POST['postID'] : null;
		if ( $postID ) {
			$volume_id = $wp_manga_storage->create_volume( $volumeName, $postID );

			wp_send_json_success( $volume_id );
		} else {
			wp_send_json_error();
		}
	}

	function wp_manga_get_chapter_by_volume() {
		global $wp_manga, $wp_manga_functions;
		$volume = isset( $_POST['volume'] ) ? $_POST['volume'] : null;
		$postID = isset( $_POST['postID'] ) ? $_POST['postID'] : null;
		$uniqid = $wp_manga->get_uniqid( $postID );
		if ( $volume ) {

            $result = $wp_manga_functions->get_chapter_by_volume( $uniqid, $volume );

			if ( !empty( $result ) ) {
				wp_send_json_success( $result );
			} else {
				wp_send_json_error();
			}

		} else {
			wp_send_json_error();
		}
	}

	function wp_manga_save_rating() {

		global $wp_manga_functions;

		$postID = isset( $_POST['postID'] ) ? $_POST['postID'] : null;
		$rating = isset( $_POST['star'] ) ? $_POST['star'] : null;
		if ( $postID ) {
			$key = '_manga_reviews';
			$prev_reviews = get_post_meta( $postID, $key, true );

            if( '' == $prev_reviews ) {
                $prev_reviews = array();
            }

			if ( is_user_logged_in() ) {
				$new_reviews = $prev_reviews;
				$new_reviews[ get_current_user_id() ] = $rating;
			} else {
				$ipaddress = $wp_manga_functions->get_client_ip();
				$new_reviews = $prev_reviews;
				$new_reviews[ $ipaddress ] = $rating;
			}

			update_post_meta( $postID, $key, $new_reviews, $prev_reviews );
			$review = $wp_manga_functions->get_total_review( $postID, $new_reviews );
			update_post_meta( $postID, '_manga_avarage_reviews', $review );

			$rating_html = $wp_manga_functions->manga_rating( $postID, true );

			wp_send_json_success( array(
				'rating_html' => $rating_html,
				'text' => sprintf( _n( 'Average %1s / %2s out of %3s total vote.', 'Average %1s / %2s out of %3s total votes.', count( $new_reviews ), WP_MANGA_TEXTDOMAIN ), $review, '5', count( $new_reviews ) ),
			) );
		}
		die();
	}



	function wp_manga_get_next_manga() {
		global $wp_manga_functions;
		$paged = isset( $_POST['paged'] ) ? $_POST['paged'] : null;
		$term = isset( $_POST['term'] ) ? $_POST['term'] : null;
		$taxonomy = isset( $_POST['taxonomy'] ) ? $_POST['taxonomy'] : null;
		$orderby = isset( $_POST['orderby'] ) ? $_POST['orderby'] : 'latest';
		if ( $paged ) {
			$args = array(
				'post_type' => 'wp-manga',
				'post_status' => 'publish',
				'paged' => $paged,
			);

			if ( $term && $taxonomy ) {
				$args['tax_query'] = array(
					array(
						'taxonomy' => $taxonomy,
						'field'    => 'slug',
						'terms'    => $term,
					),
				);
			}

			if ( $orderby ) {
				switch ( $orderby ) {
					case 'latest':
    					$args['orderby'] = 'meta_value_num';
    					$args['meta_key'] = '_latest_update';
    					break;
    				case 'alphabet':
    					$args['orderby'] = 'post_title';
    					$args['order'] = 'ASC';
    					break;
    				case 'rating':
    					$args['orderby'] = 'meta_value_num';
    					$args['meta_key'] = '_manga_avarage_reviews';
    					break;
					case 'trending':
    					$args['orderby']= 'meta_value_num';
    					$args['meta_key'] = '_wp_manga_week_views';
    					break;
    				case 'most-views':
    					$args['orderby']= 'meta_value_num';
    					$args['meta_key'] = '_wp_manga_views';
    					break;
    				case 'new-manga':
    					$args['orderby'] = 'date';
    					$args['order'] = 'DESC';
    					break;
    				default:
    					$args['orderby'] = 'date';
    					$args['order'] = 'DESC';
    					break;
				}
			}

			$manga = new WP_Query( $args );

			if ( $manga->posts ) {
				$max_page = $manga->max_num_pages;
				$result = array();
				foreach ( $manga->posts as $post ) {
					$html = $wp_manga_functions->get_html( $post->ID );
					$result['posts'][] = $html;
				}

				if ( intval( $max_page ) == intval( $paged ) ) {
					$result['next'] = null;
				} else {
					$result['next'] = intval( $paged ) + 1;
				}
				wp_send_json_success( $result );
			} else {
				wp_send_json_error( array( 'code' => 'no-post' ) );
			}
		} else {
			wp_send_json_error( array( 'code' => 'no-page' ) );
		}
	}

	function wp_manga_user_action()
	{
		global $wp_manga_login, $wp_manga, $wp_manga_functions;
		$userAction = isset( $_POST['userAction'] ) ? $_POST['userAction'] : null;

		if ( $userAction ) {
			switch ( $userAction ) {
				case 'bookmark':
					if ( is_user_logged_in() ) {

						$post_id    = isset( $_POST['postID'] ) ? $_POST['postID'] : '';
						$chapter_id = isset( $_POST['chapter'] ) ? $_POST['chapter'] : null;
						$paged      = isset( $_POST['page'] ) ? $_POST['page'] : '';
						$user_id    = get_current_user_id();

						if ( empty( $post_id ) || empty( $user_id ) ) {
							wp_send_json_error();
						}

						$this_bookmark = array(
							'id' => $post_id,
							'c'  => $chapter_id,
							'p'  => $paged,
							't'  => current_time( 'timestamp' ),
						);

						$current_bookmark = get_user_meta( $user_id, '_wp_manga_bookmark', true );

						if ( empty( $current_bookmark ) ) {

							$current_bookmark = array( $this_bookmark );

						} elseif ( is_array( $current_bookmark ) ) { //if history already existed

							//check if current chapter is existed
							$index = array_search( $post_id, array_column( $current_bookmark, 'id' ) );
							if ( $index !== false ) {
								$current_bookmark[ $index ] = $this_bookmark;
							} else {
								$current_bookmark[] = $this_bookmark;
							}
						}
						$response = update_user_meta( $user_id, '_wp_manga_bookmark', $current_bookmark );

						if ( $response == true ) {
							if( empty( $chapter ) && empty( $paged ) ){
								$is_manga_single = true;
							}
							$link = $wp_manga_functions->create_bookmark_link( $post_id, $is_manga_single );
							wp_send_json_success( $link );
						}

						wp_send_json_error( $response );


					} else {
						wp_send_json_error( array( 'code' => 'login_error' ) );
					}
					break;

				default:
					# code...
					break;
			}
		} else {
			wp_send_json_error( array( 'code' => 'unknow_action' ) );
		}
		die(0);
	}

	function wp_manga_delete_bookmark() {

		global $wp_manga_functions;
		$post_id         = isset( $_POST['postID'] ) ? $_POST['postID'] : '';
		$is_manga_single = $_POST['isMangaSingle'];

		$user_id         = get_current_user_id();
		$bookmark_manga  = get_user_meta( $user_id, '_wp_manga_bookmark', true );

		if ( empty( $post_id ) || empty( $bookmark_manga ) ) {
			wp_send_json_error();
		}

		foreach ( $bookmark_manga as $index => $manga ) {
			if ( $manga['id'] == $post_id ) {
				unset( $bookmark_manga[ $index ] );
			}
		}

		$resp = update_user_meta( $user_id, '_wp_manga_bookmark', $bookmark_manga );

		if ( $resp == true ) {
			if ( empty( $bookmark_manga ) && $is_manga_single !== 'true' ) {
				wp_send_json_success( array(
					'is_empty' => true,
					'msg'      => wp_kses( __( '<span>You haven\'t bookmark any manga yet</span>', WP_MANGA_TEXTDOMAIN ), array( 'span' => array() ) )
				) );
			};
			$link = $wp_manga_functions->create_bookmark_link( $post_id, $is_manga_single );
			wp_send_json_success( $link );
		}

		wp_send_json_error();
	}

	function wp_manga_delete_multi_bookmark() {

		$bookmark_ids = isset( $_POST['bookmark'] ) ? $_POST['bookmark'] : null;

		$user_id = get_current_user_id();
		$bookmark_manga = get_user_meta( $user_id, '_wp_manga_bookmark', true );

		if ( $bookmark_ids ) {
			if ( is_user_logged_in() ) {

				foreach ( $bookmark_manga as $index => $manga ) {
					if ( in_array( $manga['id'], $bookmark_ids ) ) {
						unset( $bookmark_manga[ $index ] );
					}
				}

				$resp = update_user_meta( $user_id, '_wp_manga_bookmark', $bookmark_manga );

				if ( $resp == true ) {
					if ( empty( $bookmark_manga ) ) {
						wp_send_json_success( array(
							'is_empty' => true,
							'msg'      => wp_kses( __( '<span>You haven\'t bookmark any manga yet</span>', WP_MANGA_TEXTDOMAIN ), array( 'span' => array() ) )
						) );
					};
					wp_send_json_success();
				}

				wp_send_json_error();
			}
		} else {
			wp_send_json_error( array( 'message' => __( 'Eh, try to cheat ahh !?', WP_MANGA_TEXTDOMAIN ) ) );
		}
		die(0);
	}

	function wp_manga_search_manga() {

		$title = isset( $_POST['title'] ) ? $_POST['title'] : null;
		if ( !$title ) {
			wp_send_json_error( array( array(
				'error' => 'empty title',
				'message' => __( 'No manga found', WP_MANGA_TEXTDOMAIN ),
			) ) );
		}

		$search = $title;
		$args_query = array(
			'post_type'      => 'wp-manga',
			'posts_per_page' => 6,
			'post_status'    => 'publish',
			's'              => $title,
		);

		$query = new WP_Query( $args_query );

		$query = apply_filters('madara_manga_query_filter', $query, $args_query );

		$results = array();
		if ( $query->have_posts() ) {
			$html = '';
			while ( $query->have_posts() ) {
				$query->the_post();
				$results[] = array(
					'title' => get_post_field( 'post_title', get_the_ID() ),
					'url' => get_permalink( get_the_ID() )
				);
			}
			wp_reset_query();
			wp_send_json_success( $results );
		} else {
			wp_reset_query();
			wp_send_json_error( array( array( 'error' => 'not found', 'message' => __( 'No Manga found', WP_MANGA_TEXTDOMAIN ) ) ) );
		}

		die(0);
	}

	function wp_manga_download_chapter(){

		global $wp_manga_storage;
		$post_id = isset( $_POST['postID'] ) ? $_POST['postID'] : '';
		$chapter_id = isset( $_POST['chapterID'] ) ? $_POST['chapterID'] : '';
		$storage = isset( $_POST['storage'] ) ? $_POST['storage'] : '';

		if( !empty( $post_id ) && !empty( $chapter_id ) && !empty( $storage ) ) {
			$zip = $wp_manga_storage->zip_chapter( $post_id, $chapter_id, $storage );

			if( $zip !== false ) {
				wp_send_json_success( $zip );
			}
		}

		wp_send_json_error();
	}

	function wp_manga_delete_zip(){

		$zip_dir = isset( $_POST['zipDir'] ) ? $_POST['zipDir'] : '';

		if( !empty( $zip_dir ) ){
			unlink( $zip_dir );
		}

	}

	function wp_manga_chapters_to_down(){

		$post_id = isset( $_POST['postID'] ) ? $_POST['postID'] : '';
		$storage = isset( $_POST['storage'] ) ? $_POST['storage'] : '';

		if( !empty( $post_id ) && !empty( $storage ) ) {

			global $wp_manga, $wp_manga_chapter, $wp_manga_functions;
			$uniqid = $wp_manga->get_uniqid( $post_id );
			$manga_json_dir = WP_MANGA_JSON_DIR . $uniqid . '/manga.json';

			if( file_exists( $manga_json_dir ) ) {

				$manga_json = file_get_contents( $manga_json_dir );
				$manga = json_decode( $manga_json, true );
				$all_chapters = $manga['chapters'];

				$output = '';

				foreach( $all_chapters as $chapter_id => $chapter ) {

					if( !in_array( $storage, $chapter['storage'] ) ) {
						continue;
					}else{
						$this_chapter = $wp_manga_chapter->get_chapter_by_id( $post_id, $chapter_id );

						$output .= '<li>';
							$output .= $this_chapter['chapter_name'];
							$output .= $wp_manga_functions->filter_extend_name( $this_chapter['chapter_name_extend'] );
						$output .= '</li>';
					}

				}

				if( !empty( $output ) ) {
					wp_send_json_success( $output );
				}else{
					wp_send_json_error( __( ' doesn\'t have any chapter on this server.', WP_MANGA_TEXTDOMAIN ) );
				}
			}
		}

		wp_send_json_error( __('Something wrong happened. Please try again later', WP_MANGA_TEXTDOMAIN ) );
	}

	function wp_download_manga(){

		$post_id = isset( $_POST['postID'] ) ? $_POST['postID'] : '';

		if( !empty( $post_id ) ) {
			global $wp_manga, $wp_manga_storage;

			$uniqid = $wp_manga->get_uniqid( $post_id );

			$manga_zip = $wp_manga_storage->zip_manga( $post_id, $uniqid, $storage );

			if( $manga_zip ) {

				$response = array(
					'zip' => $manga_zip
				);

				wp_send_json_success( $response );

			}

		}

		wp_send_json_error( __('Something wrong happened. Please try again later', WP_MANGA_TEXTDOMAIN ) );

	}

    function wp_manga_upload(){

        if( $_FILES ) {

            global $wp_manga, $wp_manga_storage, $wp_manga_functions, $wp_manga_chapter, $wp_manga_volume;

            $storage = isset( $_POST['storage'] ) ? $_POST['storage'] : '';
            $post_id = isset( $_POST['postID'] ) ? $_POST['postID'] : '';
            $volume = ( isset( $_POST['volume'] ) && $_POST['volume'] !== 'none' ) ? $_POST['volume'] : null;

            $manga_zip = $_FILES[ key( $_FILES ) ];

            if( empty( $post_id ) ) {
                wp_send_json_error( __('Missing post ID', WP_MANGA_TEXTDOMAIN ) );
            }

			//if storage is blogspot
			if( $storage == 'picasa' && isset( $_POST['picasa_album'] ) ){
				update_option( 'google_latest_album', $_POST['picasa_album'] );
			}

            $response = $wp_manga_storage->manga_upload( $post_id, $manga_zip, $storage );

			if( !empty( $response['success'] ) ){
				wp_send_json_success( $response['message'] );
			}else{
				wp_send_json_error( $response['message'] );
			}

        }else{
            wp_send_json_error( __('Please choose zip file to upload', WP_MANGA_TEXTDOMAIN ) );
        }

        wp_send_json_error( __('Something wrong happened, please try again later', WP_MANGA_TEXTDOMAIN ) );
    }

    function wp_update_chapters_list() {

        global $wp_manga, $wp_manga_post_type;

        $post_id = isset( $_POST['postID'] ) ? $_POST['postID'] : '';
        $output = $wp_manga_post_type->list_all_chapters( $post_id );

        if( !empty( $output ) ) {
            wp_send_json_success( $output );
        }

        wp_send_json_error();

    }

    function wp_manga_search_chapter(){

		global $wp_manga_functions, $wp_manga_chapter;

		$post_id = !empty( $_POST['post'] ) ? $_POST['post'] : null;
		$search = !empty( $_POST['chapter'] ) ? $_POST['chapter'] : null;

		$post = get_post( get_post( $post_id ) );

		$chapters = $wp_manga_functions->get_latest_chapters( $post_id, $search, 10 );

		$volumes = array();

		foreach( $chapters as $chapter ){

			$this_chapter_volume = $wp_manga_chapter->get_chapter_volume( $post_id, $chapter['chapter_id'] );

			if( !isset( $volumes[$chapter['volume_id'] ] ) ){
				$volumes[$chapter['volume_id']] = array(
					'volume_name' => $this_chapter_volume['volume_name']
				);
			}

			$volumes[$chapter['volume_id']]['chapters'][] = $chapter;

		}

		$output = '';
		if ( $chapters ) {
			$output .= $wp_manga_functions->list_chapters_by_volume( $volumes, true );
		}else{
			$output = __('<span> Nothing matches </span>', WP_MANGA_TEXTDOMAIN);
		}

		wp_send_json_success( $output );

	}

    function wp_manga_duplicate_server(){

        $post_id          = isset( $_POST['postID'] ) ? $_POST['postID'] : '';
        $chapter_id       = isset( $_POST['chapterID'] ) ? $_POST['chapterID'] : '';
        $duplicate_server = isset( $_POST['duplicateServer'] ) ? $_POST['duplicateServer'] : '';

        if( empty( $post_id ) || empty( $chapter_id ) || empty( $duplicate_server ) ) {
            wp_send_json_error();
        }

        global $wp_manga_storage;
        $response = $wp_manga_storage->duplicate_server( $post_id, $chapter_id, $duplicate_server );

        if( $response !== false ) {
            wp_send_json_success( $response );
        }
    }

	function wp_manga_first_install_page_save(){

		$manga_archive_page = isset( $_POST['manga_archive_page'] ) ? $_POST['manga_archive_page'] : 0;
		$user_page = isset( $_POST['user_page'] ) ? $_POST['user_page'] : 0;

		if( $manga_archive_page == 0 && $user_page == 0 ) {
			return false;
		}

		$settings = get_option( 'wp_manga_settings' , array() );
		$settings['manga_archive_page'] = $manga_archive_page;
		$settings['user_page'] = $user_page;

		$resp = update_option( 'wp_manga_settings', $settings );

		wp_send_json_success( $resp );

	}

	function wp_manga_first_install_post_save(){

		$manga_slug = isset( $_POST['manga_slug'] ) ? $_POST['manga_slug'] : 'manga';

		if( $manga_slug == 'manga' ) {
			return false;
		}

		$settings = get_option( 'wp_manga_settings' , array() );
		$settings['manga_slug'] = urldecode( sanitize_title( $manga_slug ) );
		update_option( 'wp_manga_settings', $settings );

		$args = get_post_type_object( 'wp-manga' );
		$args->rewrite['slug'] = $manga_slug;
		register_post_type( $args->name, $args );
		flush_rewrite_rules();

		wp_send_json_success();

	}

	function wp_manga_skip_first_install(){

		$resp = update_option( 'wp_manga_notice', true );
		wp_send_json_success( $resp );

	}

	function send_json( $type, $msg, $data = null ){

		$response = array(
			'message' => $msg
		);

		if( $data ){
			$response['data'] = $data;
		}

		if( $type == 'success' ){
			wp_send_json_success( $response );
		}else{
			wp_send_json_error( $response );
		}

	}
}
$GLOBALS['wp_manga_ajax'] = new WP_MANGA_AJAX();
