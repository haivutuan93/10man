<?php

	/**
	 * Class ParseAuthor
	 */

	namespace App\Views;

	class ParseAuthor {

		/**
		 * Get Author Avatar
		 *
		 * @author
		 *
		 * @since Madara Alpha 1.0
		 * @package madara
		 */

		public function __construct() {

		}

		public function renderAuthorAvatar( $ID = false, $size = 170, $echo = true ) {
			global $post;

			$user_avatar = false;
			$email       = '';

			if ( $ID == false ) {
				$ID    = get_the_author_meta( 'ID' );
				$email = get_the_author_meta( 'email' );
			} else {
				$email = get_the_author_meta( 'email', $ID );
			}

			$user_avatar = get_avatar( $email, $size );

			if ( $echo ) {
				echo wp_kses_post( $user_avatar );
			} else {
				return $user_avatar;
			}
		}
		
	}