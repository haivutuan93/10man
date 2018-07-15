<?php

class WP_MANGA_STORAGE {

	public function __construct() {
		// add_action('init', array( $this, 'local_storage' ) );
		add_action( 'before_delete_post', array( $this, 'delete_manga' ) );
		add_action( 'wp_manga_upload_after_extract', array( $this, 'check_storage_limit' ), 10, 4 );
		add_action( 'wp_manga_upload_after_extract', array( $this, 'set_temp_dir_to_clean'), 10, 4 );
	}

    function mime_content_type( $filename ) {

        $mime_types = array(

            'txt' => 'text/plain',
            'htm' => 'text/html',
            'html' => 'text/html',
            'php' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'xml' => 'application/xml',
            'swf' => 'application/x-shockwave-flash',
            'flv' => 'video/x-flv',

            // images
            'png' => 'image/png',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'ico' => 'image/vnd.microsoft.icon',
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'svg' => 'image/svg+xml',
            'svgz' => 'image/svg+xml',

            // archives
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            'exe' => 'application/x-msdownload',
            'msi' => 'application/x-msdownload',
            'cab' => 'application/vnd.ms-cab-compressed',

            // audio/video
            'mp3' => 'audio/mpeg',
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',

            // adobe
            'pdf' => 'application/pdf',
            'psd' => 'image/vnd.adobe.photoshop',
            'ai' => 'application/postscript',
            'eps' => 'application/postscript',
            'ps' => 'application/postscript',

            // ms office
            'doc' => 'application/msword',
            'rtf' => 'application/rtf',
            'xls' => 'application/vnd.ms-excel',
            'ppt' => 'application/vnd.ms-powerpoint',

            // open office
            'odt' => 'application/vnd.oasis.opendocument.text',
            'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        );
        $explode = explode('.',$filename);
        $ext = strtolower(array_pop( $explode ));
        if (array_key_exists($ext, $mime_types)) {
            return $mime_types[$ext];
        }
        elseif (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME);
            $mimetype = finfo_file($finfo, $filename);
            finfo_close($finfo);
            return $mimetype;
        }
        else {
            return 'application/octet-stream';
        }
    }

	function slugify( $text )
	{

//      // replace non letter or digits by -
//	  $text = preg_replace('~[^\pL\d]+~u', '-', $text);
//
//	  // transliterate
//	  $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
//
//	  // remove unwanted characters
//	  $text = preg_replace('~[^-\w]+~', '', $text);
//
//	  // trim
//	  $text = trim($text, '-');
//
//	  // remove duplicate -
//	  $text = preg_replace('~-+~', '-', $text);
//
//	  // lowercase
//	  $text = strtolower($text);
//
//	  if (empty($text)) {
//	    return 'n-a';
//	  }
//
//	  return $text;
            $text = trim(mb_strtolower($text));
            $text = preg_replace('/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/', 'a', $text);
            $text = preg_replace('/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/', 'e', $text);
            $text = preg_replace('/(ì|í|ị|ỉ|ĩ)/', 'i', $text);
            $text = preg_replace('/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/', 'o', $text);
            $text = preg_replace('/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/', 'u', $text);
            $text = preg_replace('/(ỳ|ý|ỵ|ỷ|ỹ)/', 'y', $text);
            $text = preg_replace('/(đ)/', 'd', $text);
            $text = preg_replace('/[^a-z0-9-\s]/', '', $text);
            $text = preg_replace('/([\s]+)/', '-', $text);
            $text = preg_replace('/---/', '-', $text);
            $text = preg_replace('/--/', '-', $text);
            return $text;
	}

	function unzip( $temp ) {
		$zip = new ZipArchive;
		$return = array();
		$res = $zip->open( $temp['tmp_name'] );
		$extract = WP_MANGA_DIR.'extract/';

		if ($res === TRUE) {
		  $zip->extractTo( $extract );
		  $zip->close();

		  if ( is_dir($extract) ){
		  if ($dh = opendir($extract)){
		    while ( ( $file = readdir($dh) ) !== false){

				if( is_dir( rtrim( $extract, '/' ) . '/' . $file ) ){
					continue;
				}

		    	if ( '.' != $file && '..' != $file && $file !== '.DS_Store' ) {
		    		$return[ $file ] = $extract.$file;
		    	}
		    }
		    closedir($dh);
		  }
		}

			return $return;
		} else {
		  return false;
		}
	}

	function local_storage( $uniqid, $c_slug, $extract, $extract_uri, $overwrite = 'false' ) {

		global $wp_manga;

			if ( is_dir($extract) ){
				if ($dh = opendir($extract)){
					while ( ( $file = readdir($dh) ) !== false){

						if( is_dir( rtrim( $extract, '/' ) . '/' . $file ) ){
							continue;
						}

						if ( '.' != $file && '..' != $file && $file !== '.DS_Store' ) {
							//rename image name to slug
							$file_name = preg_replace( '/\s/', '-', $file );
							$current_file_path = $extract . '/' . $file;
							$new_file_path = $extract . '/' . $file_name;
							rename( $current_file_path, $new_file_path );

							$result['file'][] = $uniqid . '/' . $c_slug . '/' . $file_name;
						}
					}
					closedir($dh);
				}

				natcasesort( $result['file'] );

                $result['host'] = $extract_uri;

                return $result;
			}

        return false;
	}

	function local_remove_storage( $path ) {

		$path = rtrim( $path, '/');

		if( file_exists( $path ) ){

			if( is_dir( $path ) ){

				//use scandir instead of glob will grab hidden files
				$all_files = scandir( $path );

				if( count( $all_files ) === 2 ){
					rmdir( $path );
					return true;
				}

				foreach( $all_files as $file ){

					if( $file === '.' || $file === '..' ){
						continue;
					}

					$this->local_remove_storage( $path . '/' . $file );
				}

				if( count( scandir( $path ) ) === 2 ){
					rmdir( $path );
					return true;
				}

			}elseif( is_file( $path ) ){
				unlink( $path );
			}

		}

		return false;
	}

	function create_chapter( $chapter_args, $result, $storage, $overwrite = 'false' ){

		global $wp_manga, $wp_manga_chapter;

		$chapter_id = $wp_manga_chapter->insert_chapter( $chapter_args );

		if( $chapter_id == false ) {
			return false;
		}

		return $this->create_json(
			$chapter_args['post_id'],
			array(
				'chapter_id' => $chapter_id
			),
			$result,
			$storage,
			$overwrite );

	}

	function update_chapter( $chapter_args, $result, $storage, $overwrite = true ){

		global $wp_manga, $wp_manga_chapter;

		$wp_manga_chapter->update_chapter( $chapter_args['update'], $chapter_args['args'] );

		return $this->create_json(
			$chapter_args['args']['post_id'],
			array(
				'chapter_id' => $chapter_args['args']['chapter_id'],
				'new_slug' => isset( $chapter_args['new_slug'] ) ? $chapter_args['new_slug'] : ''
			),
			$result,
			$storage,
			$overwrite );

	}

	function create_json( $post_id, $chapter = array(), $result, $storage, $overwrite = 'false'  ) {

        global $wp_manga;
        $uniqid = $wp_manga->get_uniqid( $post_id );

		$path = WP_MANGA_JSON_DIR . $uniqid;
		wp_mkdir_p( $path );
		$json_storage = WP_MANGA_JSON_DIR . $uniqid . '/manga.json';

		if (  file_exists( $json_storage ) ) {
			$raw = file_get_contents( $json_storage );
			$data = json_decode( $raw, true );
		} else {
			$data = array();
		}

		$data['uniqid'] = $uniqid;

		$total = count( $result['file'] );

		$chapter_id = $chapter['chapter_id'];

		$data['chapters'][$chapter_id]['total_page'] = $total;

        if( !empty( $chapter['new_slug'] ) ) {

			//change folder name of local storage
			if( isset( $data['chapters'][$chapter_id]['storage']['local'] ) ) {
				$chapter_dir = WP_MANGA_DATA_DIR . $uniqid . '/' . $chapter['new_slug']['current_chapter_slug'];
				$new_chapter_dir = WP_MANGA_DATA_DIR . $uniqid . '/' . $chapter['new_slug']['new_chapter_slug'];

				if( file_exists( $chapter_dir ) ) {
					$rename = rename( $chapter_dir, $new_chapter_dir );
				}

				$is_name_changed = true;
			}

        }

		//unset( $data['chapters'][$chapterSlug]['storage'][ $storage ] );
		$data['chapters'][$chapter_id]['storage'][ $storage ]['host'] = $result[ 'host' ];
		$data['chapters'][$chapter_id]['storage']['inUse'] = $storage;

		if( $overwrite == true ) {
			unset( $data['chapters'][$chapter_id]['storage'][ $storage ]['page'] );
		}

		foreach( $result['file'] as $file ){

			if( !isset( $page ) ){
				$page = 1;
			}

            //if name is change, change file url
            if( !empty( $chapter['new_slug'] ) ) {
                $explode = explode( $chapter['new_slug']['current_chapter_slug'], $file );
                $file = implode( $chapter['new_slug']['new_chapter_slug'], $explode );
            }

			$data['chapters'][$chapter_id]['storage'][ $storage ]['page'][ $page ]['src'] = $file;
			$data['chapters'][$chapter_id]['storage'][ $storage ]['page'][ $page ]['mime'] = $this->mime_content_type( $file );

			$page++;
		}

		$new_date = current_time( 'timestamp', false );
		$old_date = get_post_meta( $post_id, '_latest_update', true );
		update_post_meta( $post_id, '_latest_update', $new_date , $old_date );

		$fp = fopen( $json_storage , 'w');
		fwrite( $fp, json_encode( $data ) );
		fclose( $fp );

		return $data['chapters'][$chapter_id];
	}

	function delete_chapter_json( $post_id, $chapter_id ) {

		global $wp_manga;

		$uniqid = $wp_manga->get_uniqid( $post_id );

		$path = WP_MANGA_JSON_DIR . $uniqid;
		wp_mkdir_p( $path );
		$json_storage = WP_MANGA_JSON_DIR . $uniqid . '/manga.json';

		if (  file_exists( $json_storage ) ) {
			$raw = file_get_contents( $json_storage );

			$data = json_decode( $raw, true );
		} else {
			$data = array();
		}

		$data['uniqid'] = $uniqid;

		unset( $data['chapters'][$chapter_id] );

		$fp = fopen( $json_storage , 'w');
		fwrite( $fp, json_encode( $data ) );
		fclose( $fp );

	}

	function get_host( $storage ) {
		$host = '';
		switch ( $storage ) {
			case 'local':
				$host = WP_MANGA_DATA_URL;
				break;

			default:
				# code...
				break;
		}
		return $host;
	}


	// for upload to other server :
	// 1. create temp folder
	// 2. upload files
	// 3. delete temp folder

	function _storage( $uniqid, $slugified_name, $extract_dir, $extract_uri, $host ) {

        global $wp_manga, $wp_manga_imgur_upload, $wp_manga_google_upload, $wp_manga_amazon_upload;

        chmod( $extract_dir, 0777 );
		$result = array();

			if ( is_dir($extract_dir) ){
				if ($dh = opendir($extract_dir)){
				while ( ( $file = readdir($dh) ) !== false){

					if( is_dir( rtrim( $extract, '/' ) . '/' . $file ) ){
						continue;
					}

					if ( '.' != $file && '..' != $file && $file !== '.DS_Store' ) {

                        //rename image name to slug
                        $file_name = preg_replace( '/\s/', '-', $file );
                        $current_file_path = $extract_dir . '/' . $file;
                        $new_file_path = $extract_dir . '/' . $file_name;
                        rename( $current_file_path, $new_file_path );

						$upload['file'][] = '/'.$file_name;
					}
				}
				closedir($dh);
				}
			}
			natcasesort( $upload['file'] );

			$upload['dir'] = $extract_dir;
			$upload['uniqid'] = $uniqid;
			$upload['host'] = $extract_uri;
			$upload['chapter'] = $slugified_name;

			switch ( $host ) {
				case 'imgur':
					$data = $wp_manga_imgur_upload->imgur_upload( $upload );
					break;
				case 'picasa':
					$data = $wp_manga_google_upload->google_upload( $upload );
					break;
				case 'amazon':
					$data = $wp_manga_amazon_upload->amazon_upload( $upload );
					break;
				default:
					# code...
					break;
			}

			if( isset( $data->data->error ) ) {
				return array( 'error' => 'storage_error', 'message' => $data->data->error->message );
			}


			$result['host'] = '';
			$result['file'] = $data;

			return $result;

	}

	function create_volume( $volumeName, $postID ) {

		global $wp_manga, $wp_manga_volume;

		return $wp_manga_volume->insert_volume( array(
			'post_id' => $postID,
			'volume_name' => $volumeName,
		) );

	}

    function duplicate_server( $post_id, $chapter_id, $duplicate_to ) {

        global $wp_manga, $wp_manga_functions, $wp_manga_chapter;

        $uniqid = $wp_manga->get_uniqid( $post_id );
		$chapter_slug = $wp_manga_chapter->get_chapter_slug_by_id( $post_id, $chapter_id );

        $manga_json_file = WP_MANGA_JSON_DIR . $uniqid . '/manga.json';

        if( !file_exists( $manga_json_file ) ) {
            return __('Manga JSON file doesn\'t exist ', WP_MANGA_TEXTDOMAIN);
        }

        $manga_json = file_get_contents( $manga_json_file );
        $manga = json_decode( $manga_json, true );
        $chapter = isset( $manga['chapters'][$chapter_id] ) ? $manga['chapters'][$chapter_id] : '';

        if( empty( $chapter ) ) {
            return __('Chapter doesn\'t exists', WP_MANGA_TEXTDOMAIN);
        }

        if( isset( $chapter['storage'][$duplicate_to] ) ) {
            return __('Chapter is already existed on this server', WP_MANGA_TEXTDOMAIN);
        }

        //using for duplicate from local or to local
        $chapter_dir = WP_MANGA_DATA_DIR . $uniqid . '/' . $chapter_slug . '/';

		//specific for upload to local storage
		$chapter_uri = WP_MANGA_DATA_URL;

        //start duplicating

		//if chapter is already in local
        if( isset( $chapter['storage']['local'] ) ) {

			$chapter_uri = WP_MANGA_DATA_URL . $uniqid . '/' . $chapter_slug . '/';

            $response = $this->wp_manga_upload_action(
				$uniqid,
				$chapter_slug,
				$chapter_dir,
				$chapter_uri,
				$duplicate_to
			);

        }else{

            $storage_index = key( $chapter['storage'] );
            $storage = $chapter['storage'][$storage_index];

            //if duplicate to server not local, then chapter dir is temp
            if( $duplicate_to !== 'local' ) {
                $chapter_dir = WP_MANGA_DIR . '/extract/temp/' . $uniqid . '/' . $chapter_slug . '/';
                $chapter_uri = WP_MANGA_URI . '/extract/temp/' . $uniqid . '/' . $chapter_slug . '/';
            }

			//pull chapter to local
            foreach( $storage['page'] as $page => $file ) {
                $content = file_get_contents( $file['src'] );
                $mime_type = explode( '/', $file['mime'] );

				//create chapter dir for putting content
				if( !file_exists( $chapter_dir ) ){
					wp_mkdir_p( $chapter_dir );
				}

                file_put_contents( $chapter_dir . $page . '.' . $mime_type[1], $content );

				do_action( 'wp_manga_upload_after_extract', $post_id, $chapter_slug, $chapter_dir, $duplicate_to );
            }

            if( file_exists( $chapter_dir ) || !empty( glob( $chapter_dir . '/*' ) ) ) {

                $response = $this->wp_manga_upload_action(
					$uniqid,
					$chapter_slug,
					$chapter_dir,
					$chapter_uri,
					$duplicate_to
				);

            }
        }

        if( !empty( $response ) ) {

			$this->create_json( $post_id, array( 'chapter_id' => $chapter_id ), $response, $duplicate_to );

			if( strpos( $chapter_dir, 'temp' ) !== false ){
				$this->local_remove_storage( $chapter_dir );
			}

            return __('Duplicate successfully!', WP_MANGA_TEXTDOMAIN ) ;

        }else{

			$this->local_remove_storage( $chapter_dir );

            return __('There was something wrong happened, please try again later ', WP_MANGA_TEXTDOMAIN );
        }
    }

	function delete_volume( $post_id, $volume_id ){

		global $wp_manga_volume, $wp_manga_chapter;

		//delete volume from database
		$wp_manga_volume->delete_volume( array(
			'post_id' => $post_id,
			'volume_id' => $volume_id
		) );

		//get and delete all chapters in this volume
		$chapters = $wp_manga_chapter->get_chapters( array(
			'post_id'   => $post_id,
			'volume_id' => $volume_id
		) );

		if( !empty( $chapters ) ){
			foreach( $chapters as $chapter ){
				$this->delete_chapter( $post_id, $chapter['chapter_id'] );
			}
		}

		return true;

	}

	function delete_chapter( $post_id , $c_id ){

		global $wp_manga_chapter;

		$this->local_delete_chapter_files( $post_id, $c_id );

		$this->delete_chapter_json( $post_id, $c_id );
		$wp_manga_chapter->delete_chapter( array(
			'post_id' => $post_id,
			'chapter_id' => $c_id
		) );

		return true;

	}

	function local_delete_chapter_files( $post_id, $c_id ){

		global $wp_manga, $wp_manga_chapter;

		$uniqid = $wp_manga->get_uniqid( $post_id );
		$c_slug = $wp_manga_chapter->get_chapter_slug_by_id( $post_id, $c_id );
		$c_path = WP_MANGA_DATA_DIR . "$uniqid/$c_slug";

		if( !file_exists( $c_path ) ) {
			return false;
		}

		$this->local_remove_storage( $c_path );

		return true;

	}

	function delete_manga( $post_id ){

		if( get_post_type( $post_id ) !== 'wp-manga' ){
			return;
		}

		global $wp_manga, $wp_manga_chapter, $wp_manga_volume;

		$uniqid = $wp_manga->get_uniqid( $post_id );

		if( empty( $uniqid ) ){
			return;
		}

		//remove files
		$c_path = WP_MANGA_DATA_DIR . "$uniqid/";

		if( file_exists( $c_path ) ) {
			$this->local_remove_storage( $c_path );
		}

		//remove json
		$json_path = WP_MANGA_JSON_DIR. "$uniqid/";

		if( file_exists( $json_path ) ) {
			$this->local_remove_storage( $json_path );
		}

		//remove from database
		//	remove all volume, remove all chapter
		$wp_manga_volume->delete_volume( array(
			'post_id' => $post_id
		) );
		$wp_manga_chapter->delete_chapter( array(
			'post_id' => $post_id
		) );

	}

	function count_image_files( $dir ){

		$count = 0;

		if( is_dir( $dir ) ) {
			foreach( glob( $dir . '/*' ) as $file ) {
				if( is_dir( $file ) ){
					$count += $this->count_image_files( $file );
				}else{
					if( strpos( $this->mime_content_type( $file ), 'image' ) !== false ) {
						$count++;
					}
				}
			}
		}else{
			if( strpos( $this->mime_content_type( $file ), 'image' ) !== false ) {
				$count++;
			}
		}

		return $count;

	}

	function move_chapter_dir( $old_chapter_dir, $new_chapter_dir ){

		if( is_dir( $old_chapter_dir ) ) {

			//if this chapter is exist
			if( !is_dir( $new_chapter_dir ) ){
				mkdir( $new_chapter_dir );
			}

			//copy all file to new dir
			$files = scandir( $old_chapter_dir );
			foreach( $files as $file ) {
				$file_path = $old_chapter_dir . '/' . $file;
				if( is_file( $file_path ) ) {
					copy( $file_path, $new_chapter_dir . '/' . $file );
					unlink( $file_path );
				}
			}

			$response = rmdir( $old_chapter_dir );

			if( $response ) {
				return true;
			}
		}

		return false;
	}

	function zip_chapter( $post_id, $chapter_id, $storage = '' ) {

		global $wp_manga, $wp_manga_chapter;
		$uniqid = $wp_manga->get_uniqid( $post_id );
		$chapter_slug = $wp_manga_chapter->get_chapter_slug_by_id( $post_id, $chapter_id );

		$zip = array(
			'zip_dir' => WP_MANGA_DIR . 'extract/' . $chapter_slug . '.zip',
			'zip_path' => WP_MANGA_URI . 'extract/' . $chapter_slug . '.zip',
		);

		$chapter_zip = new ZipArchive();
		$resp = $chapter_zip->open( $zip['zip_dir'], ZipArchive::CREATE );

		$manga_chapter_type = get_post_meta( $post_id, '_wp_manga_chapter_type', true );

		if( $manga_chapter_type == 'text' || $manga_chapter_type == 'video' ){
			wp_send_json_error( esc_html__("Download Content Chapter is not supported", 'madara' ) );
		}

		if( $storage == 'local' ) {

			$chapter_path = WP_MANGA_DATA_DIR . "$uniqid/$chapter_slug";

			if( file_exists( $chapter_path ) ) {
				$files = glob( $chapter_path .'/*' );
				$chapter_zip = new ZipArchive();

				$resp = $chapter_zip->open( $zip['zip_dir'], ZipArchive::CREATE );
				if( $resp ) {
					foreach( $files as $file ) {
						$chapter_zip->addFile( $file, basename( $file ) );
					}
				}
			}
		}

		if( $storage == 'imgur' ||  $storage == 'picasa' || $storage == 'amazon' ) {

			$manga_json_file = WP_MANGA_JSON_DIR . $uniqid . '/manga.json';

			if( !file_exists( $manga_json_file ) ) {
				return false;
			}
			$manga_json = file_get_contents( $manga_json_file );
			$manga = json_decode( $manga_json, true );
			$chapter_files = $manga['chapters'][$chapter_id]['storage'][$storage]['page'];



			if( $resp ) {
				foreach( $chapter_files as $page=>$file ) {
					$file_content = file_get_contents( $file['src'] );
					$chapter_zip->addFromString( $chapter_slug . '/' . $page . '.jpg', $file_content );
				}
			}

		}

		$chapter_zip->close();

		if( file_exists( $zip['zip_dir'] ) ) {
			return $zip;
		}

		return false;
	}

	function zip_manga( $post_id, $uniqid, $storage = '' ) {

		global $wp_manga_chapter, $wp_manga_functions;

		$manga_name = $this->slugify( get_the_title( $post_id ) );

		if( !file_exists( WP_MANGA_EXTRACT_DIR ) ){

			$make_dir = mkdir( WP_MANGA_EXTRACT_DIR );

			if( !$make_dir ){
				return false;
			}
		}

		$zip = array(
			'zip_dir' => WP_MANGA_EXTRACT_DIR . $manga_name . '.zip',
			'zip_path' => WP_MANGA_EXTRACT_URL . $manga_name . '.zip',
		);

		$manga_zip = new ZipArchive();
		$resp = $manga_zip->open( $zip['zip_dir'], ZipArchive::CREATE | ZIPARCHIVE::OVERWRITE );

		if( !$resp ){
			wp_send_json_error( esc_html__("Cannot create zip file", 'madara' ) );
		}

		$manga_chapter_type = get_post_meta( $post_id, '_wp_manga_chapter_type', true );

		if( empty( $manga_chapter_type ) ){
			$manga_chapter_type = 'manga';
		}

		global $wp_manga_functions, $wp_manga_text_type;

		$all_chapters = $wp_manga_functions->get_all_chapters( $post_id );

		if( !$all_chapters ){
			wp_send_json_error( esc_html__("This Manga doesn't have any chapter", 'madara' ) );
		}

		if( $manga_chapter_type == 'manga' ){
			//get json file
			$manga_json_file = WP_MANGA_JSON_DIR . $uniqid . '/manga.json';

			if( !file_exists( $manga_json_file ) ) {
				wp_send_json_error( esc_html__("Not Found Manga Json", WP_MANGA_TEXTDOMAIN) );
			}

			$manga_json = file_get_contents( $manga_json_file );
			$manga_json = json_decode( $manga_json, true );

			if( empty( $manga_json['chapters'] ) ){
				wp_send_json_error( esc_html__("This Manga doesn't have any chapters", 'madara' ) );
			}

			$manga_chapters = $manga_json['chapters'];

			//check manga directory
			$manga_dir = WP_MANGA_DATA_DIR . $uniqid;
			if( !file_exists( $manga_dir ) ){
				wp_send_json_error( esc_html__("Cannot find Manga data", 'madara' ) );
			}

		}

		foreach( $all_chapters as $volume => $volume_data ){

			$chapters = $volume_data['chapters'];

			foreach( $chapters as $chapter ){

				$chapter_id = $chapter['chapter_id'];

				if( $manga_chapter_type == 'manga' ){

					if( !isset( $manga_chapters[$chapter_id] ) ){
						continue;
					}

					$this_chapter = $manga_chapters[$chapter_id];
					$chapter_zip_path = $volume_data['volume_name'] . '/' . $chapter['chapter_name'];

					if( isset( $this_chapter['storage']['local'] ) ){

						foreach( $this_chapter['storage']['local']['page'] as $page => $file ){

							$file_path = WP_MANGA_DATA_DIR . $file['src'];
							$file_extension = pathinfo( $file_path );

							if( !file_exists( $file_path ) ){
								continue;
							}

							$manga_zip->addfile( $file_path, $chapter_zip_path . '/' . $page . '.' . $file_extension['extension'] );
						}
					}else{

						$storage = key( $this_chapter['storage'] );

						foreach( $this_chapter['storage'][$storage]['page'] as $page => $file ) {
							$file_content = file_get_contents( $file['src'] );
							$file_extension = pathinfo( $file_path );

							$manga_zip->addFromString( $chapter_zip_path . '/' . $page . '.' . $file_extension['extension'], $file_content );
						}
					}

				}else{
					$chapter_content = $wp_manga_text_type->get_chapter_content_post( $chapter_id );

					if( !$chapter_content ){
						continue;
					}

					$manga_zip->addFromString( $volume_data['volume_name'] . '/' . $chapter['chapter_name'] . '.txt', $chapter_content->post_content );
				}

			}
		}

		$manga_zip->close();

		if( file_exists( $zip['zip_dir'] ) ) {
			return $zip;
		}

		return false;
	}

	function wp_manga_upload_single_chapter( $chapter_args, $extract, $extract_uri, $storage, $overwrite = 'false' ){

		//chapter_args structure
		// $chapter_args = array(
		// 	'post_id'             => $post_id,
		// 	'volume_id'           => $volume,
		// 	'chapter_name'        => $name,
		// 	'chapter_name_extend' => $nameExtend,
		// 	'chapter_slug'        => $slugified_name,
		// );

		if( file_exists( $extract ) ) {

			global $wp_manga, $wp_manga_chapter;
			$uniqid = $wp_manga->get_uniqid( $chapter_args['post_id'] );
			$result = $this->wp_manga_upload_action( $uniqid, $chapter_args['chapter_slug'], $extract, $extract_uri, $storage, $overwrite );

			if ( isset( $result['error'] ) ) {

				return $result;

			}elseif( !empty( $result ) ){

				if( $overwrite == 'true' ) {

					$chapter_id = $wp_manga_chapter->get_chapter_id_by_slug( $chapter_args['post_id'], $chapter_args['chapter_slug'] );

					$update_args = array(
						'update' => array(
							'volume_id'           => $chapter_args['volume_id'],
							'chapter_name'        => $chapter_args['chapter_name'],
							'chapter_name_extend' => $chapter_args['chapter_name_extend'],
							'chapter_slug'        => $chapter_args['chapter_slug'],
						),
						'args' => array(
							'post_id'    => $chapter_args['post_id'],
							'chapter_id' => $chapter_id,
						)
					);

					$response = $this->update_chapter( $update_args, $result, $storage, $overwrite );
				}else{
					$response = $this->create_chapter( $chapter_args, $result, $storage, $overwrite );
				}

			}

			return $result;
		}

		return false;
	}

	function wp_manga_upload_action( $uniqid, $c_slug, $extract, $extract_uri, $storage, $overwrite = false ){

		switch ( $storage ) {
			case 'local':
				$result = $this->local_storage( $uniqid, $c_slug, $extract, $extract_uri, $overwrite );
				break;
			case 'imgur':
				$result = $this->_storage( $uniqid, $c_slug, $extract, $extract_uri, 'imgur' );
				break;
			case 'picasa':
				$result = $this->_storage( $uniqid, $c_slug, $extract,  $extract_uri, 'picasa' );
				break;
			case 'amazon':
				$result = $this->_storage( $uniqid, $c_slug, $extract, $extract_uri, 'amazon' );
				break;
		}

		return $result;

	}

	function check_storage_limit( $post_id, $slugified_name, $extract, $storage ){

		if( $storage == 'imgur' && $this->count_image_files( $extract ) > 50 ){

			$this->local_remove_storage( $extract );

			wp_send_json_error( __('Oops, Imgur only allows user to upload maximum 50 images per hour, but your file contains more than 50 images. Please try another files', WP_MANGA_TEXTDOMAIN ) );
		}

		if( $storage == 'picasa' ){

			global $wp_manga_google_upload;
			$album = $wp_manga_google_upload->get_album();

			if( $album == false ){
				$this->local_remove_storage( $extract );
				wp_send_json_error( __('Not found this Album', WP_MANGA_TEXTDOMAIN ) );
			}

			$current_album_numphotos = $album['numphotos'];

			if( $current_album_numphotos !== false && intval( $current_album_numphotos ) == 2000 ){
				$this->local_remove_storage( $extract );
				wp_send_json_error( __( sprintf( 'Oops, Picasa only allows 2,000 photos in an album while %s album already has %d items.', $album['title'], $current_album_numphotos ), WP_MANGA_TEXTDOMAIN ) );
			}

			$count_files = $this->count_image_files( $extract );

			if(  $count_files !== false && intval( $current_album_numphotos + $count_files ) >= 2000 ){
				$this->local_remove_storage( $extract );
				wp_send_json_error( esc_html__( sprintf( 'Oops, Picasa  only allows 2,000 photos in an album while %s album already has %d items and this .zip file contains %d photos. Please create a new album to upload successfully.', $album['title'], $current_album_numphotos, $count_files ), WP_MANGA_TEXTDOMAIN ) );
			}

		}


	}

	//There might be errors caused while uploading files, so this function will clean temp folder for a specific time
	function set_temp_dir_to_clean( $post_id, $slugified_name, $extract, $storage ){

		global $wp_manga;

		$uniqid = $wp_manga->get_uniqid( $post_id );

		if( $storage !== 'local' ){

			$current_paths = get_transient( 'path_to_clean_' . $uniqid );

			if( $current_paths == false ){
				set_transient( 'path_to_clean_' . $uniqid, array( $extract ) );
			}else{
				set_transient( 'path_to_clean_' . $uniqid, array_merge( $current_paths, array( $extract ) ) );
			}
		}

	}

	/**
 	 * Handle Manga Upload ( multi chapters )
	 */

	function manga_upload( $post_id, $manga_zip, $storage ){

		global $wp_manga_functions, $wp_manga, $wp_manga_volume;

		$uniqid = $wp_manga->get_uniqid( $post_id );

		$temp_name = $manga_zip['tmp_name'];
		$temp_dir_name = $this->slugify( explode( '.', $manga_zip['name'] )[0] );

		//open zip
		$zip_manga = new ZipArchive();

		if( ! $zip_manga->open( $temp_name ) ) {
			wp_send_json_error( __('Cannot open Zip file ', WP_MANGA_TEXTDOMAIN ) );
		}

		//extract manga zip
		if( $storage == 'local' ) {
			$extract = WP_MANGA_DATA_DIR . $uniqid . '/' . $temp_dir_name;
			$final_extract = WP_MANGA_DATA_DIR . $uniqid;
			$extract_uri = WP_MANGA_DATA_URL;
		}

		if( $storage == 'imgur' || $storage == 'picasa' || $storage == 'amazon' ) {
			$extract = WP_MANGA_DIR . 'extract/temp/' . $uniqid . '/' . $temp_dir_name;
			$extract_uri = WP_MANGA_URI . 'extract/temp/' . $uniqid . '/' . $temp_dir_name;
		}

		$zip_manga->extractTo( $extract );
		$zip_manga->close();

		do_action( 'wp_manga_upload_after_extract', $post_id, $temp_dir_name, $extract, $storage );

		//scan all dir
		$scandir_lv1 = glob( $extract . '/*' );
		$result = array();

		//Dir level 1
		foreach( $scandir_lv1 as $dir_lv1 ) {

			if( basename( $dir_lv1 ) === '__MACOSX' ){
				continue;
			}

			if( is_dir( $dir_lv1 ) ) {

				//rename dir to slug name
				$dir_slug_lv1   = $wp_manga_functions->unique_slug( $post_id, basename( $dir_lv1 ) );
				$rename_dir_lv1 = $extract . '/' . $dir_slug_lv1;
				$rename_dir_lv1_uri            = $extract_uri . '/' . $dir_slug_lv1;
				rename( $dir_lv1, $rename_dir_lv1 );

				//check if dir level 1 is volume dir or chapter dir
				$scandir_lv2 = glob( $rename_dir_lv1 . '/*' );
				$is_volume = false;
				foreach( $scandir_lv2 as $dir_lv2 ) {

					if( basename( $dir_lv2 ) === '__MACOSX' ){
						continue;
					}

					//if dir level 2 is dir then dir level 1 is volume
					if( is_dir( $dir_lv2 ) ) {

						//rename dir lv2 to slug
						$dir_slug_lv2 = $wp_manga_functions->unique_slug( $post_id, basename( $dir_lv2 ) );
						$rename_dir_lv2 = $rename_dir_lv1 . '/' . $dir_slug_lv2;
						rename( $dir_lv2, $rename_dir_lv2 );

						if( $storage == 'local' ) {
							//move to original manga directory
							$chapter_path = $final_extract . '/' . $dir_slug_lv2;
							$response = $this->move_chapter_dir( $rename_dir_lv2, $chapter_path );
							$extract_uri_final = $extract_uri;

							if( $response == false ){
								wp_send_json_error( __('Access denied! Please check directory permission', WP_MANGA_TEXTDOMAIN ) );
							}

						}else{
							$chapter_path = $rename_dir_lv2;
							$extract_uri_final = $rename_dir_lv1_uri . '/' . $dir_slug_lv2;
						}

						//By now, dir lv1 is volume. Then check if this volume is already existed or craete a new one
						$this_volume = $wp_manga_volume->get_volumes(
							array(
								'post_id' => $post_id,
								'volume_name' => basename( $dir_lv1 ),
							)
						);

						if( $this_volume == false ){
							$this_volume = $this->create_volume( basename( $dir_lv1 ), $post_id );
						}else{
							$this_volume = $this_volume[0]['volume_id'];
						}

						//upload each chapter dir
						$result[basename( $dir_lv2 )] = $this->wp_manga_upload_single_chapter(
							array(
								'post_id'             => $post_id,
								'volume_id'           => $this_volume,
								'chapter_name'        => basename( $dir_lv2 ),
								'chapter_name_extend' => '',
								'chapter_slug'        => $dir_slug_lv2
							),
							$chapter_path,
							$extract_uri_final, //$chapter_uri
							$storage
						);

						//confirm dir level 1 is volume
						$is_volume = true;
					}

				}

				if( $is_volume !== true ) {
					//if dir level 1 is chapter
					if( $storage == 'local' ) {
						$chapter_path = $final_extract . '/' . $dir_slug_lv1;
						$response = $this->move_chapter_dir( $rename_dir_lv1, $chapter_path );
						$extract_uri_final = $extract_uri;
					}else{
						$chapter_path = $extract . '/' . $dir_slug_lv1;
						$extract_uri_final = $rename_dir_lv1_uri;
					}

					$result[basename( $dir_lv1 )] = $this->wp_manga_upload_single_chapter(
						array(
							'post_id'             => $post_id,
							'volume_id'           => 0,
							'chapter_name'        => basename( $dir_lv1 ),
							'chapter_name_extend' => '',
							'chapter_slug'        => $dir_slug_lv1,
						),
						$chapter_path,
						$extract_uri_final,
						$storage
					);

				}
			}
		}

		$this->local_remove_storage( $extract );

		if( !empty( $result ) ) {
			return array(
				'success' => true,
				'message' => __('Upload Complete!', WP_MANGA_TEXTDOMAIN ),
			);
		}else{
			return array(
				'success' => false,
				'message' => __('This zip file is invalid for uploading manga', WP_MANGA_TEXTDOMAIN ),
			);
		}
	}

}
$GLOBALS['wp_manga_storage'] = new WP_MANGA_STORAGE();
