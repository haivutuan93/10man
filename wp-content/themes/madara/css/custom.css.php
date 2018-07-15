<?php

/* Get Theme Options here and echo custom CSS */

/**
 * Class CustomCSS
 */



if (!function_exists('madara_custom_CSS')) {

	function madara_custom_CSS()
	{

		$madara             = new App\Madara();
		$madara_option_tree = new App\Config\OptionTree();
		$typography         = new App\Models\Entity\Typography();

		//Color
		$site_custom_colors          = $madara->getOption('site_custom_colors', 'off');
		$header_custom_colors        = $madara->getOption('header_custom_colors', 'off');
		$header_bottom_custom_colors = $madara->getOption('header_bottom_custom_colors', 'off');
		$mobile_menu_custom_color    = $madara->getOption('mobile_menu_custom_color', 'off');

		$front_page_custom_colors            = get_post_meta(get_the_ID(), 'custom_colors', true);
		$front_page_header_custom_colors     = get_post_meta(get_the_ID(), 'header_colors', true);
		$front_page_header_bottom_colors     = get_post_meta(get_the_ID(), 'header_bottom_colors', true);
		$front_page_header_mobile_menu_color = get_post_meta(get_the_ID(), 'mobile_menu_color', true);

		$custom_css          = '';
		$main_color          = '';
		$main_color_end      = '';
		$link_color_hover    = '';
		$hot_badges_bg_color = '';
		$new_badges_bg_color = '';
		$custom_badges_bg_color = '';
		$star_color          = '';

		$nav_item_color           = '';
		$nav_item_hover_color     = '';
		$nav_sub_bg               = '';
		$nav_sub_bg_border_color  = '';
		$nav_sub_item_color       = '';
		$nav_sub_item_hover_color = '';
		$nav_sub_item_hover_bg    = '';

		$header_bottom_bg                = '';
		$bottom_nav_item_color           = '';
		$bottom_nav_item_hover_color     = '';
		$bottom_nav_sub_bg               = '';
		$bottom_nav_sub_item_color       = '';
		$bottom_nav_sub_item_hover_color = '';
		$bottom_nav_sub_border_bottom    = '';

		$btn_bg          = '';
		$btn_color       = '';
		$btn_hover_bg    = '';
		$btn_hover_color = '';

		$canvas_menu_background = '';
		$canvas_menu_color      = '';
		$canvas_menu_hover      = '';

		if ($site_custom_colors == 'on' || (is_page() && $front_page_custom_colors == 'on')) {

			if ($front_page_custom_colors == 'off') {
				$main_color          = $madara_option_tree::getOption('main_color', '');
				$main_color_end      = $madara_option_tree::getOption('main_color_end', '');
				$link_color_hover    = $madara_option_tree::getOption('link_color_hover', '');
				$hot_badges_bg_color = $madara_option_tree::getOption('hot_badges_bg_color', '');
				$new_badges_bg_color = $madara_option_tree::getOption('new_badges_bg_color', '');
				$custom_badges_bg_color = $madara_option_tree::getOption('custom_badges_bg_color', '');
				$star_color          = $madara_option_tree::getOption('star_color', '');
				$btn_bg              = $madara_option_tree::getOption('btn_bg', '');
				$btn_color           = $madara_option_tree::getOption('btn_color', '');
				$btn_hover_bg        = $madara_option_tree::getOption('btn_hover_bg', '');
				$btn_hover_color     = $madara_option_tree::getOption('btn_hover_color', '');
			} else {
				$main_color          = $madara->getOption('main_color', '');
				$main_color_end      = $madara->getOption('main_color_end', '');
				$link_color_hover    = $madara->getOption('link_color_hover', '');
				$hot_badges_bg_color = $madara->getOption('hot_badges_bg_color', '');
				$new_badges_bg_color = $madara->getOption('new_badges_bg_color', '');
				$custom_badges_bg_color = $madara->getOption('custom_badges_bg_color', '');
				$star_color          = $madara->getOption('star_color', '');
				$btn_bg              = $madara->getOption('btn_bg', '');
				$btn_color           = $madara->getOption('btn_color', '');
				$btn_hover_bg        = $madara->getOption('btn_hover_bg', '');
				$btn_hover_color     = $madara->getOption('btn_hover_color', '');
			}

		}

		if ($header_custom_colors == 'on' || (is_page() && $front_page_header_custom_colors == 'on')) {

			if ($front_page_header_custom_colors == 'off') {
				$nav_item_color           = $madara_option_tree::getOption('nav_item_color', '');
				$nav_item_hover_color     = $madara_option_tree::getOption('nav_item_hover_color', '');
				$nav_sub_bg               = $madara_option_tree::getOption('nav_sub_bg', '');
				$nav_sub_bg_border_color  = $madara_option_tree::getOption('nav_sub_bg_border_color', '');
				$nav_sub_item_color       = $madara_option_tree::getOption('nav_sub_item_color', '');
				$nav_sub_item_hover_color = $madara_option_tree::getOption('nav_sub_item_hover_color', '');
				$nav_sub_item_hover_bg    = $madara_option_tree::getOption('nav_sub_item_hover_bg', '');

			} else {
				$nav_item_color           = $madara->getOption('nav_item_color', '');
				$nav_item_hover_color     = $madara->getOption('nav_item_hover_color', '');
				$nav_sub_bg               = $madara->getOption('nav_sub_bg', '');
				$nav_sub_bg_border_color  = $madara->getOption('nav_sub_bg_border_color', '');
				$nav_sub_item_color       = $madara->getOption('nav_sub_item_color', '');
				$nav_sub_item_hover_color = $madara->getOption('nav_sub_item_hover_color', '');
				$nav_sub_item_hover_bg    = $madara->getOption('nav_sub_item_hover_bg', '');
			}

		}

		if ($header_bottom_custom_colors == 'on' || (is_page() && $front_page_header_bottom_colors == 'on')) {

			if ($front_page_header_bottom_colors == 'off') {
				$header_bottom_bg                = $madara_option_tree::getOption('header_bottom_bg', '');
				$bottom_nav_item_color           = $madara_option_tree::getOption('bottom_nav_item_color', '');
				$bottom_nav_item_hover_color     = $madara_option_tree::getOption('bottom_nav_item_hover_color', '');
				$bottom_nav_sub_bg               = $madara_option_tree::getOption('bottom_nav_sub_bg', '');
				$bottom_nav_sub_item_color       = $madara_option_tree::getOption('bottom_nav_sub_item_color', '');
				$bottom_nav_sub_item_hover_color = $madara_option_tree::getOption('bottom_nav_sub_item_hover_color', '');
				$bottom_nav_sub_border_bottom    = $madara_option_tree::getOption('bottom_nav_sub_border_bottom', '');
			} else {
				$header_bottom_bg                = $madara->getOption('header_bottom_bg', '');
				$bottom_nav_item_color           = $madara->getOption('bottom_nav_item_color', '');
				$bottom_nav_item_hover_color     = $madara->getOption('bottom_nav_item_hover_color', '');
				$bottom_nav_sub_bg               = $madara->getOption('bottom_nav_sub_bg', '');
				$bottom_nav_sub_item_color       = $madara->getOption('bottom_nav_sub_item_color', '');
				$bottom_nav_sub_item_hover_color = $madara->getOption('bottom_nav_sub_item_hover_color', '');
				$bottom_nav_sub_border_bottom    = $madara->getOption('bottom_nav_sub_border_bottom', '');
			}

		}

		if ($mobile_menu_custom_color == 'on' || (is_page() && $front_page_header_mobile_menu_color == 'on')) {

			if ($front_page_header_mobile_menu_color == 'off') {
				$canvas_menu_background = $madara_option_tree::getOption('canvas_menu_background', '');
				$canvas_menu_color      = $madara_option_tree::getOption('canvas_menu_color', '');
				$canvas_menu_hover      = $madara_option_tree::getOption('canvas_menu_hover', '');
			} else {
				$canvas_menu_background = $madara->getOption('canvas_menu_background', '');
				$canvas_menu_color      = $madara->getOption('canvas_menu_color', '');
				$canvas_menu_hover      = $madara->getOption('canvas_menu_hover', '');
			}

		}

		$overwrite_body_schema = isset( $_GET['body_schema'] ) && $_GET['body_schema'] != '' ? $_GET['body_schema'] : '';
		if ( $overwrite_body_schema != '' && $overwrite_body_schema == 'dark' ) {
			$main_color = $main_color_end = '#363636';
			$header_bottom_bg = '#1c1c1c';
			$bottom_nav_item_color = '#ffffff';
			$custom_css .= '.site-header .c-sub-header-nav.with-border { border-bottom: none; }';
			$link_color_hover = '#eb3349';
		}

		if ($main_color != '') {

			if ($main_color_end != '') {
				$bg_gradient = App\Helpers\Color::madara_background_gradient($main_color, '40%', $main_color_end);
				$custom_css .= '.c-blog__heading.style-2 i {' . $bg_gradient . ' ;}';
				$custom_css .= '.c-blog__heading.style-2 i:after, .settings-page .nav-tabs-wrap ul.nav-tabs li.active:after { border-left-color: ' . $main_color_end . ' ;}';
			} else {
				$bg_gradient   = App\Helpers\Color::madara_background_gradient($main_color);
				$start_lighten = App\Helpers\Color::madara_adjust_Brightness($main_color, '20');
				$custom_css .= '.c-blog__heading.style-2 i:after, .settings-page .nav-tabs-wrap ul.nav-tabs li.active:after { border-left-color: ' . $start_lighten . ' ;}';
			}

			$custom_css .= '.site-header .main-navigation.style-1, .widget-heading, .widget.background:after, .c-blog__heading.style-2 i, .tab-wrap .c-nav-tabs ul.c-tabs-content li.active a:after, .tab-wrap .c-nav-tabs ul.c-tabs-content li:hover a:after, .tab-wrap .c-nav-tabs ul.c-tabs-content li a:after, .related-heading.font-nav, .c-blog__heading.style-3, .settings-page .nav-tabs-wrap ul.nav-tabs li.active a, .off-canvas {' . $bg_gradient . ' ;}';

			$custom_css .= '.widget-heading:after, .related-heading.font-nav:after, .genres_wrap .c-blog__heading.style-3.active:after { border-top-color: ' . $main_color . ' ;}';

			$custom_css .= 'a:hover, body.modal-open .modal .modal-content a:hover, .tab-wrap .c-nav-tabs ul.c-tabs-content li.active a, .tab-wrap .c-nav-tabs ul.c-tabs-content li:hover a, body.search.search-results .search-wrap .tab-content-wrap .c-tabs-item .c-tabs-item__content .tab-summary .post-content .post-content_item .summary-content:not(.release-year) a:hover, body.search.search-results .search-wrap .tab-content-wrap .c-tabs-item .c-tabs-item__content .tab-summary .post-content .post-content_item .summary-content.release-year a:hover, body.search.search-results .search-wrap .tab-content-wrap .c-tabs-item .c-tabs-item__content .tab-meta .latest-chap .chapter a:hover, .c-blog-post .entry-header .entry-meta .post-on:before, .manga-slider .slider__container .slick-dots li.slick-active button:before, .manga-slider .slider__container .slick-dots li button:hover:before, body.manga-page .profile-manga .tab-summary .summary_content_wrap .summary_content .post-status .manga-action .count-comment .action_icon a i, body.manga-page .profile-manga .tab-summary .summary_content_wrap .summary_content .post-status .manga-action .add-bookmark .action_icon a i, body.manga-page .profile-manga .tab-summary .summary_content_wrap .summary_content .post-status .manga-action .count-comment .action_detail a i, body.manga-page .profile-manga .tab-summary .summary_content_wrap .summary_content .post-status .manga-action .add-bookmark .action_detail a i, body.manga-page .profile-manga .post-title a, body.manga-page .content-readmore:hover, body.text-ui-light.manga-page .content-readmore:hover, .genres_wrap .genres__collapse .genres ul li a:hover, .genres_wrap .genres__collapse .genres ul li a:hover:before, .c-blog-post .entry-header .entry-meta .post-on .posted-on a:hover, body.search .c-search-header__wrapper #search-advanced .search-advanced-form .form-group.checkbox-group .checkbox label:hover, .site-header .main-navigation .search-navigation .menu-search .open-search-main-menu, .c-btn.c-btn_style-2, body.search .c-search-header__wrapper .search-content .btn-search-adv, body.reading-manga .entry-header .entry-header_wrap .action-icon ul li a, body.reading-manga .c-select-bottom .entry-header_wrap .action-icon ul li a, .widget.c-released .released-item-wrap ul.list-released li a:hover, body.manga-page .profile-manga .post-title h3, .genres_wrap .genres__collapse #genres ul li:hover a, .genres_wrap .genres__collapse #genres ul li:hover a:before, input[type=checkbox]:checked + label:before, input[type=radio]:checked + label:before, .genres_wrap a.btn-genres, .c-breadcrumb .breadcrumb li a:hover, body.search.search-results .search-wrap .tab-content-wrap .c-tabs-item .c-tabs-item__content .tab-summary .post-content .post-content_item.mg_genres .summary-content, body.page .c-page-content .c-page .c-page__content .page-content-listing .page-listing-item .page-item-detail .item-summary .list-chapter .chapter-item .vol a:hover, #hover-infor .item_thumb .post-title a, body.manga-page .version-chap:before, body.manga-page .content-readmore:hover, body.manga-page .chapter-readmore:hover, .icon-load-info, .c-blog-post .entry-header .entry-meta .post-on .c-blog__date .post-category a:hover, .woocommerce ul.products li.product .price, .woocommerce div.entry-summary p.price, .woocommerce div.entry-summary form.cart .variations .label, .woocommerce div.entry-summary form.cart .quantity-text, .widget_product_categories .product-categories li a:hover, .woocommerce ul.products li.product h2:hover, .woocommerce .c-woo-breadcrumb a:hover { color: ' . $main_color . ' ;}';

			$custom_css .= '.navigation-ajax .load-ajax:not(.show-loading):hover, .listing-chapters_wrap .has-child .wp-manga-chapter:before, .c-wg-button-wrap .btn:hover, body.manga-page .page-content-listing.single-page .listing-chapters_wrap ul.main.version-chap .wp-manga-chapter:before, .site-header .search-main-menu form input[type=submit], .form-submit input[type=submit], #comments.comments-area #respond.comment-respond .comment-form .form-submit #submit, .c-btn.c-btn_style-1, .settings-page input[type="submit"], .settings-page .remove-all #delete-bookmark-manga, body.manga-page .page-content-listing.single-page .listing-chapters_wrap > ul.main.version-chap li .wp-manga-chapter:before, .woocommerce ul.products li.product .button, .woocommerce span.onsale, .woocommerce .widget_price_filter .price_slider_amount .button:not(:hover), .woocommerce .woocommerce-pagination .page-numbers li span.current, .woocommerce .woocommerce-pagination .page-numbers li .prev:hover, .woocommerce .woocommerce-pagination .page-numbers li .next:hover, .woocommerce div.entry-summary form.cart .single_add_to_cart_button { background: ' . $main_color . ' ;}';

			$custom_css .= '.navigation-ajax .load-ajax:not(.show-loading):hover, .popular-slider .slider__container .slider__item .slider__content .slider__content_item .chapter-item .chapter a:hover, body.search .c-search-header__wrapper .search-content .btn-search-adv.collapsed, .c-btn.c-btn_style-2, body.search .c-search-header__wrapper .search-content .btn-search-adv, .genres_wrap a.btn-genres, .wpcf7-validation-errors, .text-ui-light.woocommerce-page input[type="text"]:focus, .text-ui-light.woocommerce-page input[type="email"]:focus, .text-ui-light.woocommerce-page input[type="search"]:focus, .text-ui-light.woocommerce-page input[type="url"]:focus, .text-ui-light.woocommerce-page input[type="password"]:focus, .text-ui-light.woocommerce-page input[type="tel"]:focus, .text-ui-light.woocommerce-page .input-text:focus, .text-ui-light.woocommerce-page input[type="text"]:active, .text-ui-light.woocommerce-page input[type="email"]:active, .text-ui-light.woocommerce-page input[type="search"]:active, .text-ui-light.woocommerce-page input[type="url"]:active, .text-ui-light.woocommerce-page input[type="password"]:active, .text-ui-light.woocommerce-page input[type="tel"]:active, .text-ui-light.woocommerce-page .input-text:active, .text-ui-light.woocommerce-page input[type="text"]:hover, .text-ui-light.woocommerce-page input[type="email"]:hover, .text-ui-light.woocommerce-page input[type="search"]:hover, .text-ui-light.woocommerce-page input[type="url"]:hover, .text-ui-light.woocommerce-page input[type="password"]:hover, .text-ui-light.woocommerce-page input[type="tel"]:hover, .text-ui-light.woocommerce-page .input-text:hover, .text-ui-light.woocommerce-page select.orderby:hover  { border-color: ' . $main_color . ' ;}';

			$custom_css .= '.site-header .c-sub-header-nav .c-sub-nav_wrap .sub-nav_content .sub-nav_list > li.menu-item-has-children > ul.sub-menu, .site-header .c-sub-header-nav .c-sub-nav_wrap .c-modal_item .c-user_item .c-user_menu { border-bottom-color: ' . $main_color . ' ;}';

			$custom_css .= '.widget.widget_tag_cloud .tag-cloud-link:hover, body.page .c-page-content .c-page .c-page__content .page-content-listing .page-listing-item .page-item-detail .item-summary .list-chapter .chapter-item .chapter:hover, .popular-slider .slider__container .slider__item .slider__content .slider__content_item .chapter-item .chapter a:hover, .widget.c-popular .popular-item-wrap .popular-content .chapter-item .chapter:hover, .site-footer .top-footer .wrap_social_account ul.social_account__item li a, .site-content .main-col .item-tags ul li a:hover, .popular-slider .slider__container .slick-arrow:hover, .widget.background.widget_tag_cloud .tag-cloud-link:hover, .wp-pagenavi a:hover, body.search.search-results .search-wrap .tab-content-wrap .c-tabs-item .c-tabs-item__content .tab-meta .latest-chap .chapter:hover, .go-to-top:hover, .widget.c-popular .widget-view-more, body.search .c-search-header__wrapper .search-content .search-form .search-submit, body.reading-manga .entry-header .select-pagination .nav-links .nav-next a:not(:hover), body.reading-manga .c-select-bottom .select-pagination .nav-links .nav-next a:not(:hover), body.reading-manga .entry-header .entry-header_wrap .action-icon ul li:hover a, body.reading-manga .c-select-bottom .entry-header_wrap .action-icon ul li:hover a, .widget.c-released .released-search form [type="submit"], body.modal-open .modal .modal-content .modal-body .login .submit .wp-submit:hover, body.manga-page .profile-manga .tab-summary .loader-inner > div, .wpcf7-submit, .woocommerce #respond input#submit.alt, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt, .woocommerce .woocommerce-cart-form .cart button.button, .woocommerce .cart input.button:not(:hover) { background-color: ' . $main_color . ' ;}';
		}

		if( $link_color_hover != '' ){
			$custom_css .= 'a:hover, .c-breadcrumb .breadcrumb li a:hover, .tab-wrap .c-nav-tabs ul.c-tabs-content li.active a, .tab-wrap .c-nav-tabs ul.c-tabs-content li:hover a, .c-blog-post .entry-header .entry-meta .post-on .posted-on a:hover, .c-blog-post .entry-header .entry-meta .post-on .c-blog__date .post-category a:hover, body.manga-page .content-readmore:hover, body.text-ui-light.manga-page .content-readmore:hover, body.manga-page .chapter-readmore:hover, body.page .c-page-content .c-page .c-page__content .page-content-listing .page-listing-item .page-item-detail .item-summary .list-chapter .chapter-item .vol a:hover, .site-header .main-navigation.style-1 .search-navigation .search-navigation__wrap .link-adv-search a:hover, .woocommerce ul.products li.product h2:hover, .woocommerce .c-woo-breadcrumb a:hover { color: ' . $link_color_hover . ';}';
			$custom_css .= '.tab-wrap .c-nav-tabs ul.c-tabs-content li.active a:after, .tab-wrap .c-nav-tabs ul.c-tabs-content li:hover a:after { background: ' . $link_color_hover . ';}';
		}

		if ($hot_badges_bg_color != '') {
			$custom_css .= 'body.page .c-page-content .c-page .c-page__content .page-content-listing .page-listing-item .page-item-detail .item-summary .manga-title-badges.hot { background-color: ' . $hot_badges_bg_color . ' ;}';
		}

		if ($new_badges_bg_color != '') {
			$custom_css .= 'body.page .c-page-content .c-page .c-page__content .page-content-listing .page-listing-item .page-item-detail .item-summary .manga-title-badges.new { background-color: ' . $new_badges_bg_color . ' ;}';
		}

		if ($custom_badges_bg_color != '') {
			$custom_css .= 'body.page .c-page-content .c-page .c-page__content .page-content-listing .page-listing-item .page-item-detail .item-summary .manga-title-badges.custom { background-color: ' . $custom_badges_bg_color . ' ;}';
		}

		if ($star_color != '') {
			$custom_css .= '.meta-item.rating .rating_current, .meta-item.rating .rating_current_half, body.manga-page .profile-manga .tab-summary .post-rating i.ion-ios-star, body.manga-page .profile-manga .tab-summary .post-rating i.ion-ios-star.rating_current, body.manga-page .profile-manga .tab-summary .post-rating i.ion-ios-star-half, body.manga-page .profile-manga .tab-summary .post-rating .user-rating i.ion-ios-star, body.manga-page .profile-manga .tab-summary .post-rating .post-total-rating i.ion-ios-star, body.manga-page .profile-manga .tab-summary .post-rating .post-total-rating i.ion-ios-star.rating_current, body.manga-page .profile-manga .tab-summary .post-rating .user-rating i.ion-ios-star, body.manga-page .profile-manga .tab-summary .post-rating .user-rating i.ion-ios-star.rating_current, .woocommerce .star-rating, .woocommerce .star-rating::before { color: ' . $star_color . ' ;}';
		}

		if ($nav_item_color != '') {
			$custom_css .= '.site-header .main-navigation .main-menu ul.main-navbar > li > a, .site-header .main-navigation.style-1 .search-navigation .search-navigation__wrap .link-adv-search a { color: ' . $nav_item_color . ' ;}';
		}

		if ($nav_item_hover_color != '') {
			$nav_item_hover_color_rgba_50 = App\Helpers\Color::madara_hex2rgba($nav_item_hover_color, 50);

			$custom_css .= '.site-header .main-navigation .main-menu ul.main-navbar > li > a:hover, .site-header .main-navigation.style-1 .search-navigation .search-navigation__wrap .link-adv-search a:hover { color: ' . $nav_item_hover_color . ' ;}';

			$custom_css .= '.site-header .main-navigation .main-menu ul.main-navbar > li > a:before { background-color: rgba( ' . $nav_item_hover_color_rgba_50 . ') ;}';
		}

		if ($nav_sub_bg != '') {
			$custom_css .= '.main-navigation .main-menu ul ul.sub-menu { background-color: ' . $nav_sub_bg . ' ;}';
		}

		if ($nav_sub_bg_border_color != '') {
			$custom_css .= '.main-navigation .main-menu ul ul.sub-menu > li { border-bottom-color: ' . $nav_sub_bg_border_color . ' ;}';
		}

		if ($nav_sub_item_color != '') {
			$custom_css .= '.main-navigation .main-menu ul ul.sub-menu a { color: ' . $nav_sub_item_color . ' ;}';
		}

		if ($nav_sub_item_hover_color != '') {
			$custom_css .= '.main-navigation .main-menu ul ul.sub-menu li:hover > a { color: ' . $nav_sub_item_hover_color . ' ;}';
		}

		if ($nav_sub_item_hover_bg != '') {
			$custom_css .= '.main-navigation .main-menu ul ul.sub-menu li:hover > a { background-color: ' . $nav_sub_item_hover_bg . ' ;}';
		}

		if ($header_bottom_bg != '') {
			$custom_css .= '.site-header .c-sub-header-nav { background-color: ' . $header_bottom_bg . ' ;}';
		}

		if ($bottom_nav_item_color != '') {
			$custom_css .= '.site-header .c-sub-header-nav .c-sub-nav_wrap .sub-nav_content .sub-nav_list > li > a, .site-header .c-sub-header-nav .c-sub-nav_wrap .c-modal_item .c-user_item span, .site-header .c-sub-header-nav .c-sub-nav_wrap .c-modal_item .btn-active-modal { color: ' . $bottom_nav_item_color . ' ;}';
			$custom_css .= '.site-header .c-sub-header-nav .c-sub-nav_wrap .c-modal_item .btn-active-modal { border-color: ' . $bottom_nav_item_color . ' ;}';
		}

		if ($bottom_nav_item_hover_color != '') {
			$custom_css .= '.site-header .c-sub-header-nav .c-sub-nav_wrap .sub-nav_content .sub-nav_list > li:hover > a, .site-header .c-sub-header-nav .c-sub-nav_wrap .c-modal_item .btn-active-modal:hover { color: ' . $bottom_nav_item_hover_color . ' ;}';
			$custom_css .= '.site-header .c-sub-header-nav .c-sub-nav_wrap .c-modal_item .btn-active-modal:hover { border-color: ' . $bottom_nav_item_hover_color . ' ;}';
		}

		if ($bottom_nav_sub_bg) {
			$custom_css .= '.site-header .c-sub-header-nav .c-sub-nav_wrap .sub-nav_content .sub-nav_list > li.menu-item-has-children > ul.sub-menu, .site-header .c-sub-header-nav .c-sub-nav_wrap .sub-nav_content .sub-nav_list > li.menu-item-has-children > ul.sub-menu .sub-menu, .site-header .c-sub-header-nav .c-sub-nav_wrap .sub-nav_content .sub-nav_list li .sub-menu .sub-menu a, .site-header .c-sub-header-nav .c-sub-nav_wrap .sub-nav_content .sub-nav_list li .sub-menu .sub-menu li:hover a, .site-header .c-sub-header-nav .c-sub-nav_wrap .c-modal_item .c-user_item .c-user_menu { background-color: ' . $bottom_nav_sub_bg . ' ;}';
		}

		if ($bottom_nav_sub_item_color != '') {
			$custom_css .= '.site-header .c-sub-header-nav .c-sub-nav_wrap .sub-nav_content .sub-nav_list .sub-menu li a, .site-header .c-sub-header-nav .c-sub-nav_wrap .c-modal_item .c-user_item .c-user_menu a { color: ' . $bottom_nav_sub_item_color . ' ;}';
		}

		if ($bottom_nav_sub_item_hover_color != '') {
			$custom_css .= '.site-header .c-sub-header-nav .c-sub-nav_wrap .sub-nav_content .sub-nav_list .sub-menu li a:hover, .site-header .c-sub-header-nav .c-sub-nav_wrap .c-modal_item .c-user_item .c-user_menu a:hover { color: ' . $bottom_nav_sub_item_hover_color . ' ;}';
		}

		if ($bottom_nav_sub_border_bottom != '') {
			$custom_css .= '.site-header .c-sub-header-nav .c-sub-nav_wrap .sub-nav_content .sub-nav_list > li.menu-item-has-children > ul.sub-menu, .site-header .c-sub-header-nav .c-sub-nav_wrap .c-modal_item .c-user_item .c-user_menu { border-bottom-color : ' . $bottom_nav_sub_border_bottom . ' ;}';
		}

		if ($btn_bg != '') {
			$custom_css .= '.widget.c-popular .widget-view-more, #comments.comments-area #respond.comment-respond .comment-form .form-submit #submit, .site-header .search-main-menu form input[type=submit], body.search .c-search-header__wrapper .search-content .search-form .search-submit, .c-btn.c-btn_style-1, .settings-page input[type="submit"], .settings-page .remove-all #delete-bookmark-manga, body.reading-manga .entry-header .select-pagination .nav-links .nav-next a:not(:hover), body.reading-manga .c-select-bottom .select-pagination .nav-links .nav-next a:not(:hover), .widget.c-released .released-search form [type="submit"], .wpcf7-submit, .woocommerce ul.products li.product .button, .woocommerce span.onsale,  .woocommerce .woocommerce-pagination .page-numbers li span.current, .woocommerce .woocommerce-pagination .page-numbers li .prev:hover, .woocommerce .woocommerce-pagination .page-numbers li .next:hover, .woocommerce div.entry-summary form.cart .single_add_to_cart_button, .woocommerce button.button.alt, .woocommerce a.button.alt, .woocommerce .woocommerce-cart-form .cart button.button, .woocommerce .cart input.button:not(:hover) { background-color : ' . $btn_bg . ' ;}';
			$custom_css .= '.woocommerce .widget_shopping_cart .buttons a:not(:hover), .woocommerce.widget_shopping_cart .buttons a:not(:hover), .woocommerce .widget_price_filter .price_slider_amount .button:not(:hover), .woocommerce div.product form.cart .button, .woocommerce .cart-collaterals .checkout-button.button.alt { background : ' . $btn_bg . ' ;}';
			$custom_css .= '.c-btn.c-btn_style-2, body.search .c-search-header__wrapper .search-content .btn-search-adv.collapsed, body.search .c-search-header__wrapper .search-content .btn-search-adv, .genres_wrap a.btn-genres { border-color : ' . $btn_bg . ' ;}';
			$custom_css .= '.c-btn.c-btn_style-2, body.search .c-search-header__wrapper .search-content .btn-search-adv, .genres_wrap a.btn-genres { color : ' . $btn_bg . ' ;}';
		}

		if ($btn_color != '') {
			$custom_css .= '.widget.c-popular .widget-view-more, #comments.comments-area #respond.comment-respond .comment-form .form-submit #submit, .site-header .search-main-menu form input[type=submit], body.search .c-search-header__wrapper .search-content .search-form .search-submit, .c-btn.c-btn_style-1, .settings-page input[type="submit"], .settings-page .remove-all #delete-bookmark-manga, body.reading-manga .entry-header .select-pagination .nav-links .nav-next a:not(:hover), body.reading-manga .c-select-bottom .select-pagination .nav-links .nav-next a:not(:hover), .widget.c-released .released-search form [type="submit"], .wpcf7-submit, .woocommerce ul.products li.product .button, .woocommerce .woocommerce-pagination .page-numbers li span.current, .woocommerce .widget_shopping_cart .buttons a:not(:hover), .woocommerce.widget_shopping_cart .buttons a:not(:hover), .widget_price_filter .price_slider_amount button, .woocommerce div.product form.cart .button, .woocommerce .cart-collaterals .checkout-button.button.alt, .woocommerce button.button.alt, .woocommerce a.button.alt, .woocommerce .woocommerce-cart-form .cart button.button, .woocommerce .woocommerce-cart-form .cart button.button, .woocommerce .cart input.button:not(:hover) { color : ' . $btn_color . ' ;}';
		}

		if ($btn_hover_bg != '') {
			$custom_css .= '.widget.c-popular .widget-view-more:hover, #comments.comments-area #respond.comment-respond .comment-form .form-submit #submit:hover, .site-header .search-main-menu form input[type=submit]:hover, body.search .c-search-header__wrapper .search-content .search-form .search-submit:hover, .c-btn.c-btn_style-1:hover, .c-btn.c-btn_style-2:hover, body.search .c-search-header__wrapper .search-content .btn-search-adv:hover, .settings-page input[type="submit"]:hover, .settings-page .remove-all #delete-bookmark-manga:hover, body.reading-manga .entry-header .select-pagination .nav-links .nav-next a:hover, body.reading-manga .c-select-bottom .select-pagination .nav-links .nav-next a:hover, .widget.c-released .released-search form [type="submit"]:hover, .genres_wrap a.btn-genres:hover, .wpcf7-submit:hover, .navigation-ajax .load-ajax:not(.show-loading):hover, .go-to-top:hover, body.text-ui-light .popular-slider .slider__container .slick-arrow:hover, .woocommerce ul.products li.product .button:hover, .widget_shopping_cart .woocommerce-mini-cart__buttons a:hover, .woocommerce .widget_price_filter .price_slider_amount .button:hover, .woocommerce div.product form.cart .button:hover, .woocommerce .cart-collaterals .checkout-button.button.alt:hover, .woocommerce button.button.alt:hover, .woocommerce a.button.alt:hover, .woocommerce .woocommerce-cart-form .cart button.button:hover, .woocommerce .cart input.button:hover { background-color : ' . $btn_hover_bg . ' ;}';
			$custom_css .= '.c-btn.c-btn_style-2:hover, body.search .c-search-header__wrapper .search-content .btn-search-adv:hover, body.search .c-search-header__wrapper .search-content .btn-search-adv.collapsed:hover, .genres_wrap a.btn-genres:hover { border-color : ' . $btn_hover_bg . ' ;}';
		}

		if ($btn_hover_color != '') {
			$custom_css .= '.widget.c-popular .widget-view-more:hover, #comments.comments-area #respond.comment-respond .comment-form .form-submit #submit:hover, .site-header .search-main-menu form input[type=submit]:hover, body.search .c-search-header__wrapper .search-content .search-form .search-submit:hover, .c-btn.c-btn_style-1:hover, .c-btn.c-btn_style-2:hover, body.search .c-search-header__wrapper .search-content .btn-search-adv:hover, .settings-page input[type="submit"]:hover, .settings-page .remove-all #delete-bookmark-manga:hover, body.reading-manga .entry-header .select-pagination .nav-links .nav-next a:hover, body.reading-manga .c-select-bottom .select-pagination .nav-links .nav-next a:hover, .widget.c-released .released-search form [type="submit"]:hover, .genres_wrap a.btn-genres:hover, .wpcf7-submit:hover, .woocommerce ul.products li.product .button:hover, .woocommerce .woocommerce-pagination .page-numbers li span.current:hover, .woocommerce .widget_shopping_cart .buttons a:hover, .woocommerce.widget_shopping_cart .buttons a:hover, .widget_price_filter .price_slider_amount button:hover, .woocommerce div.product form.cart .button:hover, .woocommerce .cart-collaterals .checkout-button.button.alt:hover, .woocommerce button.button.alt:hover, .woocommerce a.button.alt:hover, .woocommerce .woocommerce-cart-form .cart button.button:hover, .woocommerce .cart input.button:hover { color : ' . $btn_hover_color . ' ;}';
		}

		if ($canvas_menu_background != '') {
			$custom_css .= '.off-canvas { background : ' . $canvas_menu_background . ' ;}';
		}

		if ($canvas_menu_color != '') {
			$custom_css .= '.off-canvas ul.main-navbar li a, .off-canvas ul.main-navbar li.menu-item-has-children > i.fa:before { color : ' . $canvas_menu_color . ' ;}';
		}

		if ($canvas_menu_hover != '') {
			$custom_css .= '.off-canvas ul.main-navbar li a:hover { color : ' . $canvas_menu_hover . ' ;}';
		}

		$pre_loading_color = $madara->getOption('pre_loading_icon_color', '');

		if ($pre_loading_color != '') {
			$custom_css .= '#pageloader .loader-inner.ball-pulse > div, #pageloader .loader-inner.ball-pulse-sync > div, #pageloader .loader-inner.ball-beat > div, #pageloader .loader-inner.ball-grid-pulse > div, #pageloader .loader-inner.ball-grid-beat > div, #pageloader .loader-inner.ball-pulse-rise>div, #pageloader .loader-inner.ball-rotate>div, #pageloader .loader-inner.ball-rotate>div:after, #pageloader .loader-inner.ball-rotate>div:before, #pageloader .loader-inner.cube-transition>div, #pageloader .loader-inner.ball-zig-zag>div, #pageloader .loader-inner.ball-zig-zag-deflect>div, #pageloader .loader-inner.line-scale-party>div, #pageloader .loader-inner.line-scale-pulse-out-rapid>div, #pageloader .loader-inner.line-scale-pulse-out>div, #pageloader .loader-inner.line-scale>div, #pageloader .loader-inner.line-spin-fade-loader>div, #pageloader .loader-inner.ball-scale>div, #pageloader .loader-inner.ball-scale-multiple>div, #pageloader .loader-inner.ball-spin-fade-loader>div, #pageloader .loader-inner.square-spin>div {background-color: ' . $pre_loading_color . '}';
			$custom_css .= '#pageloader .loader-inner.ball-clip-rotate > div {border: 2px solid ' . $pre_loading_color . '; border-bottom-color: transparent;}';
			$custom_css .= '#pageloader .loader-inner.ball-scale-ripple>div, #pageloader .loader-inner.ball-scale-ripple-multiple>div {border: 2px solid ' . $pre_loading_color . ';}';
			$custom_css .= '#pageloader .loader-inner.ball-clip-rotate-pulse>div:first-child {background: ' . $pre_loading_color . ';}';
			$custom_css .= '#pageloader .loader-inner.ball-clip-rotate-pulse>div:last-child {border-color: ' . $pre_loading_color . ' transparent;}';
			$custom_css .= '#pageloader .loader-inner.ball-clip-rotate-multiple>div {border: 2px solid ' . $pre_loading_color . '; border-bottom-color: transparent; border-top-color: transparent;}';
			$custom_css .= '#pageloader .loader-inner.triangle-skew-spin>div { border-left: 20px solid transparent; border-right: 20px solid transparent; border-bottom: 20px solid ' . $pre_loading_color . ';}';
			$custom_css .= '#pageloader .loader-inner.ball-triangle-path>div {border: 1px solid ' . $pre_loading_color . ';}';

			$custom_css .= '#pageloader .loader-inner.semi-circle-spin>div {background-image: linear-gradient(transparent 0,transparent 70%, ' . $pre_loading_color . ' 30%, ' . $pre_loading_color . ' 100%);}';
		}

		if ($madara->getOption('font_using_custom', 'off') == 'on') {
			// Custom Fonts

			//Google Font
			$main_google_font = $madara->getOption('main_font_on_google', 'off');

			$mainFontFamily     = $typography->getMainFontFamily();
			$headingFontFamily  = $typography->getHeadingFontFamily();
			$metaFontFamily     = $typography->getMetaFontFamily();
			$mainNavigationFont = $typography->getNavigationFontFamily();

			//Line Height
			$main_line_height = $madara->getOption('main_font_line_height', 1.5);

			$h1_line_height = $madara->getOption('h1_line_height', 1.5);
			$h2_line_height = $madara->getOption('h2_line_height', 1.5);
			$h3_line_height = $madara->getOption('h3_line_height', 1.5);
			$h4_line_height = $madara->getOption('h4_line_height', 1.5);
			$h5_line_height = $madara->getOption('h5_line_height', 1.5);
			$h6_line_height = $madara->getOption('h6_line_height', 1.5);

			$h1_font_weight = $madara->getOption('h1_font_weight', 900);
			$h2_font_weight = $madara->getOption('h2_font_weight', 900);
			$h3_font_weight = $madara->getOption('h3_font_weight', 900);
			$h4_font_weight = $madara->getOption('h4_font_weight', 600);
			$h5_font_weight = $madara->getOption('h5_font_weight', 600);
			$h6_font_weight = $madara->getOption('h6_font_weight', 500);

			$meta_font_line_height = $madara->getOption('meta_font_line_height');

			//Font Size
			$mainFontFamily_size = $madara->getOption('main_font_size', 14);
			$h1_size             = $madara->getOption('heading_font_size_h1', 34);
			$h2_size             = $madara->getOption('heading_font_size_h2', 30);
			$h3_size             = $madara->getOption('heading_font_size_h3', 24);
			$h4_size             = $madara->getOption('heading_font_size_h4', 18);
			$h5_size             = $madara->getOption('heading_font_size_h5', 16);
			$h6_size             = $madara->getOption('heading_font_size_h6', 14);

			$custom_font_1 = $madara->getOption('custom_font_1', '');
			$custom_font_2 = $madara->getOption('custom_font_2', '');
			$custom_font_3 = $madara->getOption('custom_font_3', '');

			//Font Wwight
			$main_font_weight = $madara->getOption('main_font_weight', 'normal');

			/**
			 * Main Font Family
			 */
			if ($mainFontFamily != '') {
				$mainFontName = $typography->getGoogleFontName($mainFontFamily);

				$texts = preg_split('/&/', $mainFontName);
				if (count($texts) > 1) {
					$mainFontName = $texts[0];
				}
				if ($mainFontName != '') {

					$custom_css .= '
							body{
								font-family: ' . esc_html($mainFontName) . ', serif;
							}';
				}
				;
			}

			/**
			 * Main Font Weight
			 */
			if ($main_font_weight != '' && $main_font_weight != 'normal') {
				$custom_css .= '
						body, body.modal-open .modal .modal-content .modal-body .login label, .c-breadcrumb .breadcrumb li a{
							font-weight: ' . esc_html($main_font_weight) . ';
						}';
			}

			if ($mainFontFamily_size != 14) {
				$custom_css .= 'body, #comments.comments-area #respond.comment-respond .comment-form > *, body.manga-page .profile-manga .tab-summary .post-content_item > *, body.manga-page .profile-manga .post-status .post-content_item > *, body.manga-page .content-readmore, .widget.my-history .my-history-item-wrap > *, .widget table#wp-calendar > thead > tr > td, .widget table#wp-calendar tbody > tr > td, .widget table#wp-calendar tfoot > tr > td, .widget table#wp-calendar > thead > tr > th, .widget table#wp-calendar tbody > tr > th, .widget table#wp-calendar tfoot > tr > th, .paging-navigation .nav-links .nav-button > a, .widget.widget_rss ul li .rssSummary, body.modal-open .modal .modal-content .modal-body .login label, body.modal-open .modal .modal-content .modal-body .login .submit .wp-submit, body.search .c-search-header__wrapper #search-advanced .search-advanced-form .form-group:not(.checkbox-group) > *, .c-breadcrumb .breadcrumb li a {font-size: ' . $mainFontFamily_size . 'px}';
			}

			if ($main_line_height != 1.5) {
				$custom_css .= 'body, .font-meta a, body.reading-manga .entry-header .select-pagination .nav-links .nav-next a, body.reading-manga .c-select-bottom .select-pagination .nav-links .nav-next a, body.manga-page .content-readmore, .c-blog-post .entry-content .entry-content_wrap, body.search .c-search-header__wrapper #search-advanced .search-advanced-form .form-group:not(.checkbox-group) > *, .c-breadcrumb .breadcrumb li a {line-height: ' . $main_line_height . '}';
			}

			/**
			 * Heading Font Family
			 */
			if ($headingFontFamily != '') {
				$headingFontName = $typography->getGoogleFontName($headingFontFamily);
				$texts           = preg_split('/&/', $headingFontName);
				if (count($texts) > 1) {
					$headingFontName = $texts[0];
				}
				if ($headingFontName != '') {

					$custom_css .= '
							h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6, h1 a, h2 a, h3 a, h4 a, h5 a, h6 a, .h1 a, .h2 a, .h3 a, .h4 a, .h5 a, .h6 a, .heading a, .font-heading, .widget-title a
							 {
								font-family: ' . esc_html($headingFontName) . ', serif;
							}';
				}
			}

			if ($h1_size != 34) {
				$custom_css .= 'h1, .h1 {font-size: ' . $h1_size . 'px}';
			}

			if ($h1_line_height != 1.4) {
				$custom_css .= 'h1, .h1 {line-height: ' . $h1_line_height . 'em}';
			}

			if ($h1_font_weight != 600) {
				$custom_css .= 'h1, .h1 {font-weight: ' . $h1_font_weight . '}';
			}

			if ($h2_size != 30) {
				$custom_css .= 'h2, .h2 {font-size: ' . $h2_size . 'px}';
			}

			if ($h2_line_height != 1.4) {
				$custom_css .= 'h2, .h2 {line-height: ' . $h2_line_height . 'em}';
			}

			if ($h2_font_weight != 600) {
				$custom_css .= 'h2, .h2 {font-weight: ' . $h2_font_weight . '}';
			}

			if ($h3_size != 24) {
				$custom_css .= 'h3, .h3 {font-size: ' . $h3_size . 'px}';
			}

			if ($h3_line_height != 1.4) {
				$custom_css .= 'h3, .h3 {line-height: ' . $h3_line_height . 'em}';
			}

			if ($h3_font_weight != 600) {
				$custom_css .= 'h3, .h3 {font-weight: ' . $h3_font_weight . '}';
			}

			if ($h4_size != 18) {
				$custom_css .= 'h4, .h4 {font-size: ' . $h4_size . 'px}';
			}

			if ($h4_line_height != 1.4) {
				$custom_css .= 'h4, .h4 {line-height: ' . $h4_line_height . 'em}';
			}

			if ($h4_font_weight != 600) {
				$custom_css .= 'h4, .h4 {font-weight: ' . $h4_font_weight . '}';
			}

			if ($h5_size != 16) {
				$custom_css .= 'h5, .h5 {font-size: ' . $h5_size . 'px}';
			}

			if ($h5_line_height != 1.4) {
				$custom_css .= 'h5, .h5 {line-height: ' . $h5_line_height . 'em}';
			}

			if ($h5_font_weight != 600) {
				$custom_css .= 'h5, .h5 {font-weight: ' . $h5_font_weight . '}';
			}

			if ($h6_size != 14) {
				$custom_css .= 'h6, .h6 {font-size: ' . $h6_size . 'px}';
			}

			if ($h6_line_height != 1.4) {
				$custom_css .= 'h6, .h6 {line-height: ' . $h6_line_height . 'em}';
			}

			if ($h6_font_weight != 600) {
				$custom_css .= 'h6, .h6{font-weight: ' . $h6_font_weight . '}';
			}

			/**
			 * Meta Font Family
			 */
			if ($metaFontFamily != '') {
				$metaFontName = $typography->getGoogleFontName($metaFontFamily);
				$texts        = preg_split('/&/', $metaFontName);
				if (count($texts) > 1) {
					$metaFontName = $texts[0];
				}
				if ($metaFontName != '') {

					$custom_css .= ' .font-meta, .posts-date, time, .popular-slider .slider__container .slider__item .slider__content .slider__content_item .chapter-item .chapter a {
								font-family: ' . esc_html($metaFontName) . ', serif;
							}';
				}
			}

			if ($mainNavigationFont != '') {
				$custom_css .= '.main-navigation .main-menu a, .second-menu a, .site-header .c-sub-header-nav .c-sub-nav_wrap .c-modal_item .c-user_item span, .site-header .c-sub-header-nav .c-sub-nav_wrap .c-modal_item .c-user_item .c-user_menu a, .site-header .c-sub-header-nav .c-sub-nav_wrap .c-modal_item .btn-active-modal, .site-header .main-navigation.style-1 .search-navigation .search-navigation__wrap .link-adv-search a {font-family: ' . esc_html($mainNavigationFont) . '}';
			}

			$mainNavigationFontSize = $madara->getOption('navigation_font_size', 18);

			if ($mainNavigationFontSize != 14) {
				$custom_css .= '.main-navigation .main-menu a, .second-menu a, .main-navigation .main-menu ul li > a, .main-navigation .main-menu ul li a, .site-header .c-sub-header-nav .c-sub-nav_wrap .c-modal_item .c-user_item span, .site-header .c-sub-header-nav .c-sub-nav_wrap .c-modal_item .c-user_item .c-user_menu a, .site-header .c-sub-header-nav .c-sub-nav_wrap .c-modal_item .btn-active-modal, .site-header .main-navigation.style-1 .search-navigation .search-navigation__wrap .link-adv-search a {font-size: ' . $mainNavigationFontSize . 'px}';
			}

			$navigation_font_weight = $madara->getOption('navigation_font_weight', 400);

			if ($navigation_font_weight != 400) {
				$custom_css .= '.c-main-navigation .c-main-navigation__inner .c-main-menu .main-menu .navbar-nav > li > a, .site-header .c-sub-header-nav .c-sub-nav_wrap .c-modal_item .c-user_item span, .site-header .c-sub-header-nav .c-sub-nav_wrap .c-modal_item .c-user_item .c-user_menu a, .site-header .main-navigation.style-1 .search-navigation .search-navigation__wrap .link-adv-search a, .site-header .c-sub-header-nav .c-sub-nav_wrap .sub-nav_content .sub-nav_list li a, .site-header .main-navigation .main-menu ul.main-navbar > li > a {font-weight: ' . $navigation_font_weight . '}';
			}


			/**
			 * Custom Font 1
			 */
			if ($custom_font_1 != '') {
				$custom_css .= '
					@font-face {
						font-family: "custom_font_1";
						src: url(' . esc_url($custom_font_1) . ');
					}';
			}

			/**
			 * Custom Font 2
			 */
			if ($custom_font_2 != '') {
				$custom_css .= '
					@font-face {
						font-family: "custom_font_2";
						src: url(' . esc_url($custom_font_2) . ');
					}';
			}

			/**
			 * Custom Font 3
			 */
			if ($custom_font_3 != '') {
				$custom_css .= '
					@font-face {
						font-family: "custom_font_3";
						src: url(' . esc_url($custom_font_3) . ');
					}';
			}
		}

		$custom_css .= '
			#pageloader.spinners{
				position:fixed;
				top:0;
				left:0;
				width:100%;
				height:100%;
				z-index:99999;
				background:' . $madara::getOption( 'pre_loading_bg_color', '#222' ) . '
			}
		';
		$custom_css .= '
			p.madara-unyson{
				color: #FF0000;
			}
		';
		$custom_css .= '
			.table.table-hover.list-bookmark tr:last-child td{
				text-align: center;
			}
		';

		$custom_css .= '#adminmenu .wp-submenu li.current { display: none !important;}';

		$custom_css .= '.show_tgmpa_version{ float: right; padding: 0em 1.5em 0.5em 0; }';

		$custom_css .= '.tgmpa > h2{ font-size: 23px; font-weight: 400; line-height: 29px; margin: 0; padding: 9px 15px 4px 0;}';

		$custom_css .= '.update-php{ width: 100%; height: 98%; min-height: 850px; padding-top: 1px; }';

		/**
		 * print out custom css in theme options
		 */
		$madara_to_custom_css = $madara->getOption( 'custom_css', '' );

		/**
		 * retina logo process
		 */
		$retina_logo = $madara->getOption( 'retina_logo_image', '' );

		if ( $retina_logo != '' ) {
			$custom_css .= '@media only screen and (-webkit-min-device-pixel-ratio: 2),(min-resolution: 192dpi) {
				/* Retina Logo */
				.site-header .c-header__top .wrap_branding a {background:url(' . esc_url( $retina_logo ) . ') no-repeat left; background-size:contain; display:block;}
				.site-header .c-header__top .wrap_branding a img{ opacity:0; visibility:hidden;}
			}';
		}

		$custom_css .= '.c-blog-post .entry-content .entry-content_wrap .read-container img.alignleft { margin: 10px 30px 10px 0 !important; } ';

		$custom_css .= '.c-blog-post .entry-content .entry-content_wrap .read-container img.alignright { margin: 10px 0px 10px 30px !important; } ';

		$custom_css .= '.read-container i.fas.fa-spinner.fa-spin{ font-size: 31px; color: #888; }';

		$custom_css .= '.c-blog-post .entry-content .entry-content_wrap .read-container img{ cursor : pointer; }';

		$custom_css .= '.choose-avatar .loading-overlay {
			position: absolute;
			top: 0;
			left: 0;
			right: 0;
			bottom: 0;
			background-color: rgba(255, 255, 255, 0.72);
			z-index: 1;
			display: none;
		}

		.choose-avatar .loading-overlay i.fas.fa-spinner {
			font-size: 40px;
			color: #ec3348;
		}

		.choose-avatar .loading-overlay .loading-icon {
			position: absolute;
			top: 50%;
			left: 50%;
			transform: translate(-50%,-50%);
		}

		.choose-avatar.uploading .loading-overlay {
			display: block;
		}';

		$custom_css .= '.site-header .c-sub-header-nav .entry-header {
			display: none;
			margin-bottom: 15px;
		}

		.site-header .c-sub-header-nav.sticky .entry-header {
			display: block;
		}

		.site-header .c-sub-header-nav.hide-sticky-menu.sticky .c-sub-nav_wrap{
			display:none;
		}
		.site-header .c-sub-header-nav.hide-sticky-menu .entry-header{
			margin-top:15px;
		}
';

		if ( $custom_css != '' ) {
			$custom_css .= $madara_to_custom_css;
		}

		return $custom_css;

	}
}
