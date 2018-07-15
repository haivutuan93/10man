<?php

	/**
	 * String Helper
	 */

	namespace App\Helpers;

	class CString {
		public function __construct() {

		}

		public function isStartWith( $haystack, $needle ) {
			return ! strncmp( $haystack, $needle, strlen( $needle ) );
		}
	}