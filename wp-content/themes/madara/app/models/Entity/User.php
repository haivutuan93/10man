<?php

	namespace App\Models\Entity;

	use App\Models;

	class User extends Models\Metadata {
		private $user_ID;

		public function __construct( $user_ID ) {
			$this->user_ID = $user_ID;
		}

		public function getUserAvatar( $ID = false, $size = 60 ) {
			global $post;

			$user_avatar = false;

			if ( $ID == false ) {
				$ID = get_the_author_meta( 'ID' );
			} else {
				$email = get_the_author_meta( 'email', $ID );
			}

			$user_avatar = get_avatar( $size, get_parent_theme_file_uri( '/images/avatar-2x.jpg' ) );

			return $user_avatar;
		}

		public function getUserEmail() {
			$user_email = '';

			return $user_email;
		}

		public function getUserWebsite() {
			$user_website = '';

			return $user_website;
		}

		public function getUserBiographical() {
			$user_biographical = '';

			return $user_biographical;
		}

		public function getUserSocial() {
			$user_social = array();

			return $user_social;
		}
	}