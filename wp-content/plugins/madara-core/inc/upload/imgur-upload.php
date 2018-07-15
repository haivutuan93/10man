<?php
/**
 *  Version: 1.0.0
 *  Text Domain: mangabooth-manga
 *  @since 1.0.0
 */
// NOTE UP LOAD IMGUR CREATE ALBUM : NAME-CHAPNAME.....
class WP_MANGA_IMGUR_UPLOAD {

	public function __construct() {
		// add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_script' ) );
		add_action('wp_ajax_wp-manga-imgur-save-credential', array( $this, 'wp_manga_imgur_save_credential' ) );
		add_action('wp_ajax_non_priv_wp-manga-imgur-save-credential', array( $this, 'wp_manga_imgur_save_credential') );
	}

	// enqueue script
	// function enqueue_script() {
	// 	wp_enqueue_script( 'wp-cloud-imgur', WP_CLOUD_URI . 'assets/js/imgur-upload.js', array( 'jquery' ), '', true );
	// }

	function wp_manga_imgur_save_credential() {
		$client_id = isset( $_POST['imgurClientID'] ) ? $_POST['imgurClientID'] : '';
		$client_secret = isset( $_POST['imgurClientSecret'] ) ? $_POST['imgurClientSecret'] : '';
		$options = get_option( 'mangabooth_manga', array() );
		if ( $client_id ) {
			$options['imgur_client_id'] = $client_id;
		}
		if ( $client_secret ) {
			$options['imgur_client_secret'] = $client_secret;
		}
		update_option( 'mangabooth_manga', $options );
		wp_send_json_success();
		die(0);
	}

	function imgur_upload( $upload ) {
		global $wp_manga_storage;
		$result = array();
		$options = get_option( 'wp_manga', array() );
		$imgur_client_id = isset( $options['imgur_client_id'] ) ? $options['imgur_client_id'] : '';
		$imgur_client_secret = isset( $options['imgur_client_secret'] ) ? $options['imgur_client_secret'] : '';
		$imgur_refreshtoken = get_option('wp_manga_imgur_refreshToken', null);
		if ( $imgur_refreshtoken ) {
			$accessToken = $this->get_access_token( $imgur_client_id, $imgur_client_secret, $imgur_refreshtoken );
			if ( $accessToken ) {
				$title = $upload['uniqid'].'_'.$upload['chapter'];
				$album = $this->create_album( $accessToken, $title );
				// $result['extra']['album_id'] = $album->data->id;
				// $result['extra']['deletehash'] = $album->data->deletehash;
				if ( $album ) {
					foreach ( $upload['file'] as $file ) {
						$path = $upload['host'].$file;
						$mime = $wp_manga_storage->mime_content_type( $file );
						$image = $this->image_upload( $accessToken, $path, $file , $album->data->id, $mime );
						if( isset( $image->data->link ) ) {
							$result[] = $image->data->link;
						}elseif( isset( $image->data->error->type ) && $image->data->error->type == 'ImgurException' ){
							$result = $image;
							break;
						}
					}
				}
			}
			return $result;
		} else {
			return false;
		}
	}

	function create_album( $accessToken, $title ) {
		$headers = array();
	    $headers[] = 'Authorization: Bearer '.$accessToken;
		$url = 'https://api.imgur.com/3/album';
		$params = array(
	        'title' => $title,
        );
		$album = $this->post_url( $headers, $url, $params );
		return $album;
	}

	function image_upload( $accessToken, $image_url, $name , $album, $mime ) {
		$headers = array();
	    $headers[] = 'Authorization: Bearer '.$accessToken;
		$url = 'https://api.imgur.com/3/image';
		// need to be base64 file
		$base64 = $this->get_base64( $image_url );

		$params = array(
	        'image' => $base64,
	        'album' => $album,
	        'type' => $mime,
	        'name' => $name,
        );
		$image = $this->post_url( $headers, $url, $params );
		return $image;
	}

	function get_base64( $path ) {
		$data = file_get_contents( $path );
		$base64 = base64_encode( $data );
		return $base64;
	}

	// function delete_album( $uniqid, $name ) {

	// }

	function get_access_token( $client_id, $client_secret, $refreshtoken ) {
        $headers = array();
	    $headers[] = 'Content-Type: application/x-www-form-urlencoded';
		$url = 'https://api.imgur.com/oauth2/token';
		$params = array(
	        'client_id' => $client_id,
			'client_secret' => $client_secret,
			'refresh_token' => $refreshtoken,
			'grant_type' => 'refresh_token'
        );

        $token = $this->post_url( $headers, $url, $params );
        return $token->access_token;
	}



	function post_url( $headers, $url, $params ) {
	    $ch = curl_init();
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_HTTPGET, 0);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params, null, '&'));
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	    $ret = curl_exec($ch);


		// debug
		   if($errno = curl_errno($ch)) {
		    $error_message = curl_strerror($errno);
		    echo "cURL error ({$errno}):\n {$error_message}";
		}
	    curl_close($ch);

	    return json_decode( $ret );
	  }

}
$GLOBALS['wp_manga_imgur_upload'] = new WP_MANGA_IMGUR_UPLOAD();