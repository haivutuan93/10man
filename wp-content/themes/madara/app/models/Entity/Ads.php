<?php

	/**
	 * Class Ads
	 *
	 * @package madara
	 */

	namespace App\Models\Entity;

	use App\Models;

	class Ads extends Models\Metadata {
		private $adsense_id;

		private static $instance;

		public static function getInstance() {
			if ( null == self::$instance ) {
				self::$instance = new Ads();
			}

			return self::$instance;
		}

		public function __construct() {
			$this->adsense_id = \App\Madara::getOption( 'adsense_id' );
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
		 * Add Social Accounts options to Theme Options
		 */
		function filter_theme_options( $args ) {
			$args['settings'] = array_merge( $args['settings'], $this->get_theme_option_args() );

			return $args;
		}

		private function get_ad_slots() {
			$ad_slots = array(
				'ads_before_content' => 'Before of content Ads',
				'ads_after_content'  => 'After of content Ads',
				'ads_footer'         => 'Footer Ads',
				'ads_wall_left'      => 'Wall Ads Left',
				'ads_wall_right'     => 'Wall Ads Right',
			);

			return apply_filters( 'madara_ad_slots', $ad_slots );
		}

		/**
		 * Return array of theme option args
		 */
		public function get_theme_option_args() {
			$ad_slots = $this->get_ad_slots();

			$args = array();

			foreach ( $ad_slots as $slot => $name ) {
				$args = array_merge( $args, array(
					array(
						'id'      => 'adsense_slot_' . $slot,
						'label'   => sprintf( esc_html__( '%s - AdSense Ads Slot ID', 'madara' ), $name ),
						'desc'    => sprintf( esc_html__( 'If you want to display %s, enter Google AdSense Ad Slot ID here. If left empty, "%s - Custom Code" will be used', 'madara' ), $name, $name ),
						'std'     => '',
						'type'    => 'text',
						'section' => 'advertising'
					),
					array(
						'id'       => $slot,
						'label'    => sprintf( esc_html__( '%s - Custom Code', 'madara' ), $name ),
						'desc'     => sprintf( esc_html__( 'Enter custom code for %s position', 'madara' ), $name ),
						'std'      => '',
						'type'     => 'textarea-simple',
						'section'  => 'advertising',
						'operator' => 'and'
					)
				) );
			}

			return $args;
		}

		/**
		 * Get google responsive adsense code
		 *
		 * @param $pub_id - string - Google Publisher ID
		 * @param $slot_id - string - Google Ad Slot ID
		 *
		 * @return string - HTML code
		 */
		private function get_gooogle_responsive_ad( $pub_id, $slot_id ) {
			$idx = rand( 0, 1000 );

			$html = '
            <div id="google-ads-' . esc_attr( $idx ) . '"></div>
             
            <script type="text/javascript">
             
            /* Calculate the width of available ad space */
            ad = document.getElementById("google-ads-' . esc_js( $idx ) . '");
             
            if (ad.getBoundingClientRect().width) {
                adWidth = ad.getBoundingClientRect().width; // for modern browsers
            } else {
                adWidth = ad.offsetWidth; // for old IE
            }
             
            /* Replace ca-pub-XXX with your AdSense Publisher ID */
            google_ad_client = "' . esc_js( $pub_id ) . '";
             
            /* Replace 1234567890 with the AdSense Ad Slot ID */
            google_ad_slot = "' . esc_js( $slot_id ) . '";

            /* Do not change anything after this line */
            if ( adWidth >= 728 )
            google_ad_size = ["728", "90"]; /* Leaderboard 728x90 */
            else if ( adWidth >= 468 )
            google_ad_size = ["468", "60"]; /* Banner (468 x 60) */
            else if ( adWidth >= 336 )
            google_ad_size = ["336", "280"]; /* Large Rectangle (336 x 280) */
            else if ( adWidth >= 300 )
            google_ad_size = ["300", "250"]; /* Medium Rectangle (300 x 250) */
            else if ( adWidth >= 250 )
            google_ad_size = ["250", "250"]; /* Square (250 x 250) */
            else if ( adWidth >= 200 )
            google_ad_size = ["200", "200"]; /* Small Square (200 x 200) */
            else if ( adWidth >= 180 )
            google_ad_size = ["180", "150"]; /* Small Rectangle (180 x 150) */
            else
            google_ad_size = ["125", "125"]; /* Button (125 x 125) */
             
            document.write (
            "<ins class=\"adsbygoogle\" style=\"display:inline-block;width:"
            + google_ad_size[0] + "px;height:"
            + google_ad_size[1] + "px\" data-ad-client=\""
            + google_ad_client + "\" data-ad-slot=\""
            + google_ad_slot + "\"></ins>"
            );
            (adsbygoogle = window.adsbygoogle || []).push({});
             
            </script>
             
            <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js">
            </script>';

			return $html;
		}

		/**
		 * @param $ad_pos - string - Name of ad position, defined in Theme Options
		 * @param $class - string - Optional class name
		 *
		 * @return string - HTML code
		 */
		public function get_ad( $ad_pos, $class = '' ) {
			$slot_id = \App\Madara::getOption( 'adsense_slot_' . $ad_pos );
			$pub_id  = $this->adsense_id;

			$html = '';

			if ( $pub_id != '' && $slot_id != '' ) {
				$html .= '<div class="ad c-ads ' . esc_attr( $class ) . '">' . $this->get_gooogle_responsive_ad( $pub_id, $slot_id ) . '</div>';
			} else {
				$html .= \App\Madara::getOption( $ad_pos ) != '' ? '<div class="ad c-ads custom-code ' . esc_attr( $class ) . '">' . \App\Madara::getOption( $ad_pos ) . '</div>' : '';
			}

			return $html;
		}
	}