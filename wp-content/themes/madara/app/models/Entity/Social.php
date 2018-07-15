<?php

	/**
	 * Social Class
	 *
	 * @package madara
	 */

	namespace App\Models\Entity;

	use App\Models;

	class Social extends Models\Metadata {
		private $default_accounts;

		private $custom_accounts;

		private $target;

		private static $instance;

		public static function getInstance() {
			if ( null == self::$instance ) {
				self::$instance = new Social();
			}

			return self::$instance;
		}

		public static function initialize() {
			$instance = self::getInstance();
			add_action( 'init', array( $instance, 'init' ) );
		}

		function init() {
			if ( function_exists( 'ot_settings_id' ) ) {
				add_filter( ot_settings_id() . '_args', array( $this, 'filter_theme_options' ) );
			}
		}

		/**
		 * Return array of default Social Account settings in Theme Options
		 */
		public function get_theme_option_account_args() {
			return array(
				array(
					'id'      => 'facebook',
					'label'   => esc_html__( 'Facebook', 'madara' ),
					'desc'    => esc_html__( 'The custom URL to your Facebook profile page (Please include http://)', 'madara' ),
					'std'     => '',
					'type'    => 'text',
					'section' => 'social_account'
				),
				array(
					'id'      => 'twitter',
					'label'   => esc_html__( 'Twitter', 'madara' ),
					'desc'    => esc_html__( 'The custom URL to your Twitter profile page (Please include http://)', 'madara' ),
					'std'     => '',
					'type'    => 'text',
					'section' => 'social_account'
				),
				array(
					'id'      => 'linkedin',
					'label'   => esc_html__( 'LinkedIn', 'madara' ),
					'desc'    => esc_html__( 'The custom URL to your LinkedIn profile page (Please include http://)', 'madara' ),
					'std'     => '',
					'type'    => 'text',
					'section' => 'social_account'
				),
				array(
					'id'      => 'tumblr',
					'label'   => esc_html__( 'Tumblr', 'madara' ),
					'desc'    => esc_html__( 'The custom URL to your Tumblr profile page (Please include http://)', 'madara' ),
					'std'     => '',
					'type'    => 'text',
					'section' => 'social_account'
				),
				array(
					'id'      => 'google-plus',
					'label'   => esc_html__( 'Google Plus', 'madara' ),
					'desc'    => esc_html__( 'The custom URL to your Goolge Plus profile page (Please include http://)', 'madara' ),
					'std'     => '',
					'type'    => 'text',
					'section' => 'social_account'
				),
				array(
					'id'      => 'pinterest',
					'label'   => esc_html__( 'Pinterest', 'madara' ),
					'desc'    => esc_html__( 'The custom URL to your Pinterest profile page (Please include http://)', 'madara' ),
					'std'     => '',
					'type'    => 'text',
					'section' => 'social_account'
				),
				array(
					'id'      => 'youtube',
					'label'   => esc_html__( 'Youtube', 'madara' ),
					'desc'    => esc_html__( 'The custom URL to your Youtube profile page (Please include http://)', 'madara' ),
					'std'     => '',
					'type'    => 'text',
					'section' => 'social_account'
				),
				array(
					'id'      => 'flickr',
					'label'   => esc_html__( 'Flickr', 'madara' ),
					'desc'    => esc_html__( 'The custom URL to your Flickr profile page (Please include http://)', 'madara' ),
					'std'     => '',
					'type'    => 'text',
					'section' => 'social_account'
				),
				array(
					'id'      => 'dribbble',
					'label'   => esc_html__( 'Dribbble', 'madara' ),
					'desc'    => esc_html__( 'The custom URL to your Dribble profile page (Please include http://)', 'madara' ),
					'std'     => '',
					'type'    => 'text',
					'section' => 'social_account'
				),
				array(
					'id'      => 'behance',
					'label'   => esc_html__( 'Behance', 'madara' ),
					'desc'    => esc_html__( 'The custom URL to your Behance profile page (Please include http://)', 'madara' ),
					'std'     => '',
					'type'    => 'text',
					'section' => 'social_account'
				),
				array(
					'id'      => 'envelope',
					'label'   => esc_html__( 'Email', 'madara' ),
					'desc'    => esc_html__( 'The custom URL to your Email account', 'madara' ),
					'std'     => '',
					'type'    => 'text',
					'section' => 'social_account',
				),
				array(
					'id'      => 'rss',
					'label'   => esc_html__( 'RSS', 'madara' ),
					'desc'    => esc_html__( 'Your website RSS Feed URL', 'madara' ),
					'std'     => '',
					'type'    => 'text',
					'section' => 'social_account',
				)
			);
		}

		/**
		 * Return array of theme option args
		 */
		public function get_theme_option_args() {
			return array_merge( $this->get_theme_option_account_args(), array(
				array(
					'id'       => 'custom_social_account',
					'label'    => esc_html__( 'Custom Social Accounts', 'madara' ),
					'desc'     => esc_html__( 'Add Social Account', 'madara' ),
					'type'     => 'list-item',
					'class'    => '',
					'section'  => 'social_account',
					'choices'  => array(),
					'settings' => array(
						array(
							'label' => esc_html__( 'Icon Font Awesome', 'madara' ),
							'id'    => 'icon_custom_social_account',
							'type'  => 'text',
							'desc'  => esc_html__( 'Enter Font Awesome class (Ex: fa-facebook)', 'madara' ),
						),
						array(
							'label' => esc_html__( 'URL', 'madara' ),
							'id'    => 'url_custom_social_account',
							'type'  => 'text',
							'desc'  => esc_html__( 'Enter full link to your account (including http://)', 'madara' ),
						),
					)
				),
				array(
					'id'      => 'open_social_link_new_tab',
					'label'   => esc_html__( 'Open Social link in new tab?', 'madara' ),
					'desc'    => '',
					'std'     => 'on',
					'type'    => 'on-off',
					'section' => 'social_account'
				)
			) );
		}

		/**
		 * Add Social Accounts options to Theme Options
		 */
		function filter_theme_options( $args ) {
			$args['settings'] = array_merge( $args['settings'], $this->get_theme_option_args() );

			return $args;
		}

		/**
		 * @return array
		 */
		public function getDefaultSocialAccounts() {
			$arr = array();

			foreach ( $this->get_theme_option_account_args() as $arg ) {
				$arr = array_merge( $arr, array( $arg['id'] => $arg['label'] ) );
			}

			$this->default_accounts = $arr;

			return $arr;
		}

		/**
		 * @return bool|mixed|null|string
		 */
		public function getCustomSocialAccounts() {
			$this->custom_accounts = $this->getOption( 'custom_social_account', '' );

			return $this->custom_accounts;
		}

		/**
		 * @return string
		 */
		public function getTargetOpenSocial() {
			$this->target = $this->getOption( 'open_social_link_new_tab', 'on' ) == 'on' ? '_blank' : '_parent';

			return $this->target;
		}
	}
