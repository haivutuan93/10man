<?php

	namespace App\Models\Entity;

	use App\Models;

	class Page extends Models\Metadata {
		private $ID;

		public function __construct( $page_id = null ) {
			if ( ! $page_id ) {
				$page_id = get_the_ID();
			}

			$this->ID = $page_id;
		}
	}