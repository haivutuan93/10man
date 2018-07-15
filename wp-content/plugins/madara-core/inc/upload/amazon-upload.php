<?php
/**
 *  Version: 1.0.0
 *  Text Domain: mangabooth-manga
 *  @since 1.0.0
 */
use Aws\S3\S3Client;

class WP_MANGA_AMAZON_UPLOAD {

	private $access_key;

	private $access_secret;

	private $region;

	public $bucket;

	public $buckets;

	public function __construct() {

		// requere library
		require WP_MANGA_DIR .'lib/amazons3/aws-autoloader.php';
		$options = get_option( 'wp_manga', array() );

		$amazon_s3_access_key = isset( $options['amazon_s3_access_key'] ) ? $options['amazon_s3_access_key'] : null;
		$amazon_s3_access_secret = isset( $options['amazon_s3_access_secret'] ) ? $options['amazon_s3_access_secret'] : null;
		$amazon_s3_region = isset( $options['amazon_s3_region'] ) ? $options['amazon_s3_region'] : null;

		$this->access_key = $amazon_s3_access_key;
		$this->access_secret = $amazon_s3_access_secret;
		$this->region = $amazon_s3_region;
		$this->buckets = $this->amazon_get_bucket();

		// $this->bucket = $this->get_upload_bucket( $this->buckets );

	}

	// get amazon bucket when choose in the uploader
	function amazon_get_bucket() {
		$client = $this->create_client();
		if ( $client  ) {
			try {
				$buckets = $client->listBuckets();
				$bucket = $buckets['Buckets'];
			} catch (Exception $e) {
				$bucket = false;
			}
		} else {
			$bucket = false;
		}
		return $bucket;
	}

	function get_upload_bucket( $buckets ) {
		if ( $buckets ) {
			$options = get_option( 'wp_manga', array() );
			$amazon_s3_bucket = isset( $options['amazon_s3_bucket'] ) ? $options['amazon_s3_bucket'] : null;
			if ( $amazon_s3_bucket ) {
				$bucket = $amazon_s3_bucket;
			} else {
				$bucket = $buckets[0]['Name'];
			}

			return $bucket;
		} else {
			return false;
		}
	}


	// create amazon credential to access bucket and upload files
	function create_client() {
		$amazon_s3_access_key = $this->access_key;
		$amazon_s3_access_secret =$this->access_secret;
		$amazon_s3_region = $this->region;
		if ( $amazon_s3_access_key && $amazon_s3_access_secret && $amazon_s3_region ) {
			return S3Client::factory(array(
			    'credentials' => array(
			        'key'    => $amazon_s3_access_key,
			        'secret' => $amazon_s3_access_secret,
			    ),
			    'region' => $amazon_s3_region,
			    'version' => 'latest',
			));
		} else {
			return false;
		}
	}

	function amazon_upload( $upload ) {
		global $wp_manga_storage;
		$bucket = $this->get_upload_bucket( $this->buckets );
		if ( $bucket ) {
				$client = $this->create_client();
				foreach ( $upload['file'] as $file ) {
					$dir = $upload['dir'].$file;
					$path = $upload['host'].$file;
                    $uniqid = $upload['uniqid'];
                    $s3_dir = substr($upload['dir'], strpos( $upload['dir'], $uniqid));
					$mime = $wp_manga_storage->mime_content_type( $file );
					$image = $this->image_upload( $client, $bucket , $path, $dir, $s3_dir.$file , $mime );
					$result[] = $image;
				}
			return $result;
		} else {
			return false;
		}
	}


	// Upload action ( get files from ajax )
	function image_upload( $client, $bucket , $image_url, $image_dir, $name , $mime ) {

            $fileName = $name;
        	$base64 = $this->get_base64( $image_url );
			$size = $this->get_size( $image_dir );
			$fh = fopen( $image_dir , 'r');
			$data = fread($fh, $size);
			fclose($fh);
            $result = $client->putObject(
            	array(
            	    'Bucket'     => $bucket,
				    'Key'        => $fileName,
				    'ACL'           => 'public-read',
				    'ContentType'   => $mime,
				    'Body'          => $data
				)
        	);

        	return $result['ObjectURL'];
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
}
$GLOBALS['wp_manga_amazon_upload'] = new WP_MANGA_AMAZON_UPLOAD();