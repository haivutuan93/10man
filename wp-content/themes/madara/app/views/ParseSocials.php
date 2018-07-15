<?php

	/**
	 * Class CT_Social_V
	 *
	 * @since Madara Alpha 1.0
	 * @package madara
	 */

	namespace App\Views;

	use App\Models\Entity;

	class ParseSocials {
		private $model;

		public function __construct() {
			$this->model = Entity\Social::getInstance();
		}

		/**
		 * Render Social Account Buttons
		 */
		public function renderSocialAccounts( $echo = true ) {
			$default_accounts  = $this->model->getDefaultSocialAccounts();
			$custom_accounts   = $this->model->getCustomSocialAccounts();
			$target            = $this->model->getTargetOpenSocial();
			$social_wrap_class = 'list-inline social_account__item';
			$social_class      = 'social-icons';

			// count number of icons
			$count = 0;

			$html = "";

			$html .= "<ul class='$social_wrap_class'>";

			foreach ( $default_accounts as $key => $value ) {

				$social_url = $this->model->getOption( $key, '' );
				$label      = $this->model->getLabel( $key );

				if ( $social_url ) {
					if ( $key == 'envelope' ) {
						$social_url = 'mailto:' . $social_url;
					}

					$html .= "<li>";

					$html .= '<a class="' . esc_attr( $social_class ) . '" target="' . esc_attr( $target ) . '" href="' . esc_url( $social_url ) . '" title="' . esc_attr( $label ) . '">';

					$html .= '<i class="fab fa-' . esc_attr( $key ) . '" aria-hidden="true"></i>';


					$html .= '</a>';

					$html .= '</li>';

					$count ++;
				}

			}

			if ( $custom_accounts ) {
				foreach ( $custom_accounts as $value ) {
					$custom_accounts_class = 'social-icons';

					$html .= "<li " . ( ( isset( $show_class ) && $show_class == true ) ? 'class="custom-' . esc_attr( $value['icon_custom_social_account'] ) . '"' : '' ) . ">";


					$html .= '<a class="' . esc_attr( $custom_accounts_class ) . '" target="' . esc_attr( $target ) . '" href="' . esc_url( $value["url_custom_social_account"] ) . '" title="' . esc_attr( $value["title"] ) . '">';


					$html .= '<i class="' . esc_attr( $value["icon_custom_social_account"] ) . '" aria-hidden="true"></i>';

					$html .= '</a>';

					$html .= '</li>';

					$count ++;
				}
			}

			$html .= '</ul>';

			if ( ! $count ) {
				$html = '';
			}

			if ( $echo ) {
				echo wp_kses_post( $html );
			} else {
				return $html;
			}
		}

		/**
		 * Render Social Share Button
		 *
		 * @param bool|false $id
		 */
		public function renderSocialShare( $id = false, $echo = true ) {
			if ( ! $id ) {
				$id = get_the_ID();
			}

			$html = '';

			if ( $this->model->getOption( 'sharing_facebook' ) == 'on' ) {
				$html .= '<li>
                        <a class="social-icons icon-facebook"
                           title="' . esc_html__( 'Share on Facebook', 'madara' ) . '"
                           href="#" target="_blank"
                           rel="nofollow"
                           onclick="window.open(\'https://www.facebook.com/sharer/sharer.php?u=\' + \'' . urlencode( get_permalink( $id ) ) . '\',\'facebook-share-dialog\',\'width=626,height=436\');return false;">
                            <i class="fa fa-facebook"></i>
                        </a>
                    </li>';
			}

			if ( $this->model->getOption( 'sharing_twitter' ) == 'on' ) {
				$html .= '<li>
                            <a class="social-icons icon-twitter"
                               href="#"
                               title="' . esc_html__( 'Share on Twitter', 'madara' ) . '"
                               rel="nofollow" target="_blank"
                               onclick="window.open(\'http://twitter.com/share?text=' . urlencode( get_the_title( $id ) ) . '&amp;url=' . urlencode( get_permalink( $id ) ) . '\',\'twitter-share-dialog\',\'width=626,height=436\');return false;">
                                <i class="fa fa-twitter"></i>
                            </a>
                        </li>';
			}

			if ( $this->model->getOption( 'sharing_linkedIn' ) == 'on' ) {
				$html .= '<li>
                            <a class="social-icons icon-linkedin"
                               href="#"
                               title="' . esc_html__( 'Share on LinkedIn', 'madara' ) . '"
                               rel="nofollow" target="_blank"
                               onclick="window.open(\'http://www.linkedin.com/shareArticle?mini=true&url=' . urlencode( get_permalink( $id ) ) . '&amp;title=' . urlencode( get_the_title( $id ) ) . '&amp;source=' . urlencode( get_bloginfo( 'name' ) ) . '\',\'linkedin-share-dialog\',\'width=626,height=436\');return false;">
                                <i class="fa fa-linkedin"></i>
                            </a>
                        </li>';
			}

			if ( $this->model->getOption( 'sharing_tumblr' ) == 'on' ) {
				$html .= '<li>
                            <a class="social-icons icon-tumblr"
                               href="#"
                               title="' . esc_html__( 'Share on Tumblr', 'madara' ) . '"
                               rel="nofollow"
                               target="_blank"
                               onclick="window.open(\'http://www.tumblr.com/share/link?url=' . urlencode( get_permalink( $id ) ) . '&amp;name=' . urlencode( get_the_title( $id ) ) . '\',\'tumblr-share-dialog\',\'width=626,height=436\');return false;">
                                <i class="fa fa-tumblr"></i>
                            </a>
                        </li>';
			}

			if ( $this->model->getOption( 'sharing_google' ) == 'on' ) {
				$html .= '<li>
                            <a class="social-icons icon-google-plus"
                               href="#"
                               title="' . esc_html__( 'Share on Google Plus', 'madara' ) . '"
                               rel="nofollow"
                               target="_blank"
                               onclick="window.open(\'https://plus.google.com/share?url=' . urlencode( get_permalink( $id ) ) . '\',\'googleplus-share-dialog\',\'width=626,height=436\');return false;">
                                <i class="fa fa-google-plus"></i>
                            </a>
                        </li>';
			}

			if ( $this->model->getOption( 'sharing_pinterest' ) == 'on' ) {
				$html .= '<li>
                            <a class="social-icons icon-pinterest"
                               href="#"
                               title="' . esc_html__( 'Pin this', 'madara' ) . '"
                               rel="nofollow"
                               target="_blank"
                               onclick="window.open(\'//pinterest.com/pin/create/button/?url=' . urlencode( get_permalink( $id ) ) . '&amp;media=' . urlencode( wp_get_attachment_url( get_post_thumbnail_id( $id ) ) ) . '&amp;description=' . urlencode( get_the_title( $id ) ) . '\',\'pin-share-dialog\',\'width=626,height=436\');return false;">
                                <i class="fa fa-pinterest"></i>
                            </a>
                        </li>';
			}

			if ( $this->model->getOption( 'sharing_email' ) == 'on' ) {
				$html .= '<li>
                            <a class="social-icons icon-envelope"
                               href="mailto:?subject=' . get_the_title( $id ) . '&amp;body=' . urlencode( get_permalink( $id ) ) . '"
                               title="' . esc_html__( 'Email this', 'madara' ) . '">
                                <i class="fa fa-envelope"></i>
                            </a>
                        </li>';
			}

			if ( $html != '' ) {
				$html = '<div class="item-heading"><h4 class="heading">' . esc_html__( 'SHARE THIS POST', 'madara' ) . '</h4></div><ul class="list-inline article-social-share social-list-btn">' . $html . '</ul>';
			}

			if ( $echo ) {
				echo wp_kses_post( $html );
			} else {
				return $html;
			}

		}
	}
