<?php
	/**
	 *
	 * ParseHeaderSpace Class
	 *
	 * @since Madara Alpha 1.0
	 * @package madara
	 */

	namespace App\Views;

	use App\Models\Entity;

	class ParsePageHeader {
		private $model;

		public function __construct() {
			$this->model = new Entity\Layout();
		}

		/**
		 * @return array
		 */
		public function renderHeaderMargins() {
			$args = array(
				'page_header_margin_top',
				'page_header_margin_right',
				'page_header_margin_bottom',
				'page_header_margin_left'
			);

			$header_margins = $this->model->getHeaderSpace( $args );

			return $header_margins;

		}

		/**
		 * @return array
		 */
		public function renderHeaderPaddings() {
			$args = array(
				'page_header_padding_top',
				'page_header_padding_right',
				'page_header_padding_bottom',
				'page_header_padding_left'
			);

			$header_paddings = $this->model->getHeaderSpace( $args );

			return $header_paddings;
		}
	}