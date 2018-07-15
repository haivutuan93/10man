<?php
/**
 *  Version: 1.0.0
 *  Text Domain: WPStylish-wp-cloud
 *  @since 1.0.0
 */

class WP_MANGA_GOOGLE_UPLOAD {

	private $googleClientID;

	private $googleClientSecret;

	private $googleRedirect;

	private $googleRefreshToken;

	public function __construct() {
		// add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_script' ) );

		add_action('wp_ajax_wp-manga-google-save-credential', array( $this,'wp_manga_google_save_credential' ) );
		add_action('wp_ajax_non_priv_wp-manga-google-save-credential', array( $this, 'wp_manga_google_save_credential') );

		//do this check on update refreshToken for the first time save, on every after the get_access_token will auto update status of fresh token
		add_action('update_option', array( $this, 'check_refresh_token'), 10, 3 );

		$options = get_option( 'wp_manga', array() );
		$google_client_id = isset( $options['google_client_id'] ) ? $options['google_client_id'] : '';
		$google_client_secret = isset( $options['google_client_secret'] ) ? $options['google_client_secret'] : '';
		$google_redirect = isset( $options['google_redirect'] ) ? $options['google_redirect'] : '';
		$google_refreshtoken = get_option('wp_manga_google_refreshToken', null);

		$this->googleClientID = $google_client_id;
		$this->googleClientSecret = $google_client_secret;
		$this->googleRedirect = $google_redirect;
		$this->googleRefreshToken = $google_refreshtoken;

	}

	// enqueue script
	// function enqueue_script() {
	// 	wp_enqueue_script( 'wp-cloud-picasa', wp_manga_URI . 'assets/js/picasa.js', array( 'jquery' ), '', true );
	// }

	function wp_manga_google_save_credential() {
		$googleClientID = isset( $_POST['googleClientID'] ) ? trim( $_POST['googleClientID'] ) : '';
		$googleClientSecret = isset( $_POST['googleClientSecret'] ) ? trim( $_POST['googleClientSecret'] ) : '';
		$googleRedirect = isset( $_POST['googleRedirect'] ) ? trim( $_POST['googleRedirect'] ) : '';
		$options = get_option( 'wp_manga', array() );

		if ( $googleClientID ) {
			$options['google_client_id'] = $googleClientID;
		}
		if ( $googleClientSecret ) {
			$options['google_client_secret'] = $googleClientSecret;
		}
		if ( $googleRedirect ) {
			$options['google_redirect'] = $googleRedirect;
		}
		update_option( 'wp_manga', $options );
		wp_send_json_success();
		die(0);
	}

	// get access token to picasa web api
	function get_access_token( $refreshToken = '' ) {

		if( empty( $this->googleClientID ) || empty( $this->googleClientSecret ) || ( empty( $refreshToken ) && empty( $this->googleRefreshToken ) ) ){
			return;
		}

		$headers = array();
	    // $headers[] = 'Content-Type: application/x-www-form-urlencoded';
		$url = 'https://www.googleapis.com/oauth2/v4/token';
		$params = array(
	        'client_id' => $this->googleClientID,
			'client_secret'   => $this->googleClientSecret,
			'refresh_token'   => !empty( $refreshToken ) ? $refreshToken : $this->googleRefreshToken,
			'grant_type'      => 'refresh_token'
        );

        $token = $this->post_url( $headers, $url, $params );

		if( !isset( $token->access_token ) ){
			//if refresh token is already expired or revoke, then set the authorizing state of picasa is false
			set_transient('google_authorized', false);

			if( isset( $token->error_description ) ){
				//put error message to transient
				set_transient('google_authorization_error', $token->error_description);
			}

			return false;
		}

		set_transient('google_authorized', true);
		delete_transient( 'google_authorization_error' );

        return $token->access_token;

	}

	function google_upload( $upload ) {
		global $wp_manga_storage;
		$result = array();
		$google_refreshtoken = $this->googleRefreshToken;
		if ( $google_refreshtoken ) {
			$accessToken = $this->get_access_token();

			if ( $accessToken ) {
				foreach ( $upload['file'] as $file ) {
					$dir = $upload['dir'].$file;
					$path = $upload['host'].$file;
					$mime = $wp_manga_storage->mime_content_type( $file );
					$image = $this->image_upload( $accessToken, $path, $dir, $file , $mime );
					$result[] = $this->blogspot_url_filter( (string) $image );
				}
			}
			return $result;
		} else {
			return false;
		}
	}

	function blogspot_url_filter( $image_url ){

		$path = explode( 'googleusercontent.com/', $image_url );

	    if( isset( $path[1] ) ){
	        $path = $path[1];
	        $number = rand( 1, 4 );
	        $image_url = "https://{$number}.bp.blogspot.com/{$path}";
	    }

		return $image_url;
	}

	function construct_curl( $url, $headers = false, $opts = false ){

		$accessToken = $this->get_access_token();

		if( empty( $accessToken ) ) {
			return false;
		}

		$curl_headers = array();
	    $curl_headers[] = 'Authorization: Bearer '.$accessToken;

		if( $headers ){
			$curl_headers = array_merge( $curl_headers, $headers );
		}

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);

		curl_setopt($ch, CURLOPT_HTTPHEADER, $curl_headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

		if( $opts ){
			foreach( $opts as $key => $value ){
				curl_setopt( $ch, $key, $value );
			}
		}

		$ret = curl_exec($ch);

	    if($errno = curl_errno($ch)) {
		    $error_message = curl_strerror($errno);
		    echo "cURL error ({$errno}):\n {$error_message}"; die();
		}

	    curl_close($ch);

	    return simplexml_load_string( $ret );

	}

	function get_album_list(){

		$url = 'https://picasaweb.google.com/data/feed/api/user/default/?fields=entry(id,title,gphoto:numphotos)';

		$result = $this->construct_curl( $url );

		if( ! $result ){
			return false;
		}

		$albums = array();

		$i = 0;

		while( isset( $result->entry[$i] ) ){

			$title = $result->entry[$i]->title->__toString();
			if( $title == 'Drop Box' ){
				$id = 'default';
			}else{
				$explode = explode( '/', $result->entry[$i]->id );
				$id = end( $explode );
			}

			$albums[$id] = array(
				'title' 	=> $title,
				'numphotos' => $result->entry[$i]->children( 'gphoto', true )->numphotos->__toString()
			);
			$i++;
		}

		return $albums;

	}

	function get_album(){

		$album = get_option( 'google_latest_album', 'default' );
		$albums = $this->get_album_list();

		if( isset( $albums[$album] ) ){
			return $albums[$album];
		}

		return false;

	}

	function get_album_numphotos( $album_id = 'default' ){ //check if album exceeds 2000 photos limit

		$albums = $this->get_album_list();

		if( isset( $albums[$album_id] ) ){
			return $albums[$album_id]['numphotos'];
		}

		return false;
	}

	function albums_dropdown( $default_storage, $echo = false ){

		$albums = $this->get_album_list();
		$latest_album = get_option( 'google_latest_album', 'default' );

		if( $albums == false ){

			//get error message
			$error = get_transient('google_authorization_error');
			if( $error ){
				return '<div class="error">' . esc_html( $error ) . '</div>';
			}

			return;
		}

		$html = '';

		$html .= '<select id="wp-manga-blogspot-albums" class="wp-manga-blogspot-albums ' . ($default_storage == 'picasa' ? '' : 'hidden') . '" name="wp-manga-blogspot-albums">';
			foreach( $albums as $id => $album ){
				$html .= '<option value="'.esc_attr( $id ).'"' . selected( $id, $latest_album, false ) . '>' . sprintf( esc_html__('[Album] %s (having %d items)', WP_MANGA_TEXTDOMAIN ), $album['title'], $album['numphotos'] ) . '</option>';
			}
		$html .= '</select>';

		if( $echo ){
			echo wp_kses( $html, array(
				'select'    => array(
					'id'       => array(),
					'class'    => array(),
					'name'     => array()
				),
				'option'    => array(
					'value'    => array(),
					'selected' => array(),
				),
			) );
			return;
		}

		return $html;

	}

	function image_upload( $accessToken, $image_url, $image_dir, $name , $mime ) {

		$image = array();

		$album = get_option( 'google_latest_album', 'default' );

		$url = 'https://picasaweb.google.com/data/feed/api/user/default/albumid/' . $album . '?imgmax=d';

		// need to be base64 file
		$base64 = $this->get_base64( $image_url );
		$size = $this->get_size( $image_dir );

		$fh = fopen( $image_dir , 'r');
		$data = fread($fh, $size);
		fclose($fh);

		$result = $this->construct_curl(
			$url,
			array(
				'GData-Version: 3',
				'Content-Type: '.$mime,
				'Content-Length: '.$size,
				'Slug: '.$name
			),
			array(
				CURLOPT_POST => 1,
				CURLOPT_POSTFIELDS => $data
			)
		);

		$image = $result->content->attributes()->src;

		return $image;
	}

	function get_base64( $path ) {
		$data = file_get_contents( $path );
		$base64 = base64_encode( $data );
		return $base64;
	}

	function get_size( $dir ) {
		$size = filesize( $dir );
		return $size;
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

	function check_refresh_token( $option, $oldvalue, $value ){ //check google authorizing status

		if( $option == 'wp_manga_google_refreshToken' && !empty( $value ) ){
			//authorizing status will be set in get_access_token function
			$this->get_access_token( $value );
		}

	}
}
$GLOBALS['wp_manga_google_upload'] = new WP_MANGA_GOOGLE_UPLOAD();
