<?php

	/**
	 * Class Layout
	 *
	 * @package madara
	 */

	namespace App\Models\Entity;

	use App\Models;

	class Layout extends Models\Metadata {
		public function __construct() {

		}

		/**
		 * Get Margin & Padding settings of Page Header
		 *
		 * @param $args
		 *
		 * @return array
		 */
		public function getHeaderSpace( $args ) {
			$space = array();
			foreach ( $args as $value ) {
				$item = $this->getOption( $value, '' );
				if ( $item == "" ) {
					$item = 0;
				}
				array_push( $space, $item );
			}

			return $space;
		}
	}