<?php

	/**
	 * @version    1.0
	 * @package    Madara
	 * @author     Madara Team <hi@madara.com>
	 * @copyright  Copyright (C) 2014 Madara.com. All Rights Reserved.
	 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
	 *
	 * Websites: http://www.madara.com
	 */

	/**
	 * Class that provides common render functions.
	 *
	 * @package  Madara
	 * @since    1.0
	 */

	namespace App\Views;

	class MadaraView {
		/**
		 * Render HTML of different part on page
		 */
		public static function render() {
			$numArgs = func_num_args();
			if ( $numArgs > 0 ) {
				$component = func_get_arg( 0 );

				// remove first arg
				$args   = func_get_args();
				$params = array();
				$i      = 0;
				foreach ( $args as $key => $value ) {
					if ( $key > 0 ) {
						array_push( $params, $value );
						$i ++;
					}
				}

				// call appropriate function
				call_user_func( 'App\Views\MadaraView::render' . $component, $params );
			}
		}

		/**
		 * Get title of current page (Post, Page, Category, Tag...)
		 */
		public static function renderTitle() {
			$pageTitle = new ParsePageTitle();

			return $pageTitle->render();
		}

		public static function renderBreadcrumbs() {
			$breadcumbs = new ParseBreadcrumbs();

			return $breadcumbs->render();
		}

		public static function renderAuthorAvatar() {

			$author = new ParseAuthor();

			return $author->renderAuthorAvatar();

		}

		public static function renderPostNavigation() {
			$postNavigation = new ParsePostNavigation();

			return $postNavigation->render();
		}
	}