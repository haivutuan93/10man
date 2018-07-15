(function ($) {

	"use strict";

	jQuery(window).load(function (e) {
		/*
		 * Pre-Loading
		 * */
		jQuery("#pageloader").fadeOut(500);

	});

	jQuery("body").removeClass("preload");

	$(document).ready(function () {

		/*sub-header*/
		$('li.menu-item-has-children a[href^="#"]').on('touchend click', function (e) {
			var $this = $(this);
			if ($this.parents('.c-sub-header-nav').length) {
				e.preventDefault();
				$this.parent().toggleClass('active')
			} else {
				e.preventDefault();
			}
		});

		/*menu off-canvas*/

		jQuery(".off-canvas ul > li.menu-item-has-children").addClass("hiden-sub-canvas");
		jQuery(".off-canvas ul >li.menu-item-has-children").append('<i class="fa fa-caret-right" aria-hidden="true"></i>');
		var menu_open = $('.menu_icon__open');
		var menu_close = $('.menu_icon__close');
		var menu_slide = $('.off-canvas');
		// var
		menu_open.on('click', function () {
			menu_open.addClass('active');
			menu_slide.addClass('active');
			$('body').addClass('open_canvas');
		});
		menu_close.on('click', function (e) {
			e.preventDefault();
			menu_open.removeClass('active');
			menu_slide.removeClass('active');
			$('body').removeClass('open_canvas');
		});

		$(".off-canvas ul >li.menu-item-has-children > i").on('click', function () {
			var $this = $(this).parent("li");
			$this.toggleClass("active").children("ul").slideToggle();
			return false;
		});
		$(document).on(" touchend click", function (e) {
			if (!$(e.target).hasClass('menu_icon__open') && !$(e.target).closest('.off-canvas').hasClass('active')) {
				menu_slide.removeClass('active');
				menu_open.removeClass("active");
				$('body').removeClass('open_canvas');
			}
		});
		/**
		 * Sticky Menu
		 * @type {Window}
		 */

		var stickyNavigation = $('.c-sub-header-nav').length > 0 ? $('.c-sub-header-nav').offset().top : 0;
		var cloneHeader = $("<div>", {
			class: "clone-header"
		})
		$(cloneHeader).insertBefore(".c-sub-header-nav");
		var navigationHeight = $('.c-sub-header-nav').outerHeight(true);

		/**
		 * Compare scrollTop position to add .sticky class
		 */
		var felis_need_add_sticky = function () {
			var scrollTop = $(window).scrollTop();
			if (scrollTop - stickyNavigation > 750 && $("body").hasClass("sticky-enabled")) {
				$(cloneHeader).css('height', navigationHeight);
				$('.c-sub-header-nav').addClass('sticky');
				$('body').addClass('sticky__active');
				$('.c-sub-header-nav').fadeIn(300, 'linear');
			}
			else if (scrollTop - stickyNavigation <= navigationHeight + 5 && $("body").hasClass("sticky-enabled")) {
				// $(cloneHeader).remove();
				$(cloneHeader).css('height', 0);
				$('.c-sub-header-nav').removeClass('sticky');
				$('body').removeClass('sticky__active');
			}

		}

		/**
		 * Detect scrolling up or down, to add .sticky class
		 */
		var stickyNav = function () {
			if (typeof stickyNav.x == 'undefined') {
				stickyNav.x = window.pageXOffset;
				stickyNav.y = window.pageYOffset;
			}
			;

			var diffX = stickyNav.x - window.pageXOffset;
			var diffY = stickyNav.y - window.pageYOffset;


			if (diffX < 0) {
				// Scroll right
			} else if (diffX > 0) {
				// Scroll left
			} else if (diffY < 0) {
				// Scroll down
				if ($('body').hasClass('sticky-style-2')) {
					$('.c-sub-header-nav').removeClass('sticky');
					$('body').removeClass('sticky__active');
					$('.clone-header').css('height', 0);


				} else {
					felis_need_add_sticky();
				}
			} else if (diffY > 0) {
				// Scroll up

				felis_need_add_sticky();
			} else {
				// First scroll event
			}

			stickyNav.x = window.pageXOffset;
			stickyNav.y = window.pageYOffset;
		};

		if ($('body').hasClass('sticky-enabled')) {
			$(window).on('scroll', function () {
				if ($(window).width() >= 768) {
					stickyNav();
				}
			});
		}

		if ($(window).width() >= 768) {
			$('body').delegate('.page-item-detail .item-thumb.hover-details', 'mousemove', (function (e) {
				var postID = $(this).attr('data-post-id');
				var currentPostID;
				var hoverPostID;
				currentPostID = 'manga-item-' + postID;
				hoverPostID = 'manga-hover-' + postID;
				var check_bar = $('body').hasClass('admin-bar');
				var parentOffset = $(this).offset();
				var relativeXPosition = (e.pageX); //offset -> method allows you to retrieve the current position of an element 'relative' to the document
				var relativeYPosition = (e.pageY);
				var _width_infor = $("#hover-infor").width();
				var infor_left = (relativeXPosition - 15) - _width_infor;
				var infor_right = relativeXPosition + 15;
				var infor_top = check_bar ? (relativeYPosition - 32) : (relativeYPosition);
				var body_outerW = ($(window).outerWidth() / 2);

				$("#hover-infor").addClass('active');
				$("#hover-infor").show();
				$(".icon-load-info").css({
					"position": "absolute",
					"top": infor_top - 20,
					"left": relativeXPosition - 20,
					"display": "inline-block",
					"z-index": "99999",
					"width": "40px",
					"height": "40px",
				});
				$("#hover-infor").css({
					"position": "absolute",
					"top": infor_top,
					"display": "inline-block",
					"z-index": "99999",
				});
				if (relativeXPosition >= body_outerW) {
					$("#hover-infor").css({
						"left": infor_left,
					});
				}
				else {
					$("#hover-infor").css({
						"left": infor_right,
					});
				}
			})).mouseout(function () {
				$("#hover-infor").removeClass('active');
				$("#hover-infor").hide();
			});
		}


		//Go To Top
		jQuery('.go-to-top').on('click', function () {
			jQuery('html, body').animate({scrollTop: 0}, 500);
		});
		jQuery(window).on('scroll', function () {
			if (jQuery(window).scrollTop() >= (window.innerHeight * 0.5)) {
				if (!jQuery('.go-to-top').hasClass('active')) {
					jQuery('.go-to-top').addClass('active');
				}
				;
			} else {
				jQuery('.go-to-top').removeClass('active');
			}
			;
		});
		// search
		$('.main-menu-search .open-search-main-menu').on('click', function () {
			var $this = $(this);

			if ($this.hasClass('search-open')) {
				$this.parents('.c-header__top').find('.search-main-menu').removeClass('active');
				setTimeout(function () {
					$this.parents('.c-header__top').find('.search-main-menu').find('input[type="text"]').blur();
				}, 200);
				$this.removeClass('search-open');
			} else {
				$this.parents('.c-header__top').find('.search-main-menu').addClass('active');
				setTimeout(function () {
					$this.parents('.c-header__top').find('.search-main-menu').find('input[type="text"]').focus();
				}, 200);
				$this.addClass('search-open');
			}
			;
		});

		$(".genres_wrap .btn-genres").click(function () {
			var $this = $(this);
			var $this_parent;
			$this.toggleClass("active");
			$this_parent = $this.parents(".genres_wrap");
			$this_parent.find(".genres__collapse").slideToggle(300);
			$this_parent.find(".c-blog__heading.style-3").toggleClass("active");
		});


		// accordion  view chap
		$(".listing-chapters_wrap ul.main > li.has-child").on('click', function (e) {
			var $this = $(this);
			$(e.target).toggleClass("active").children("ul").slideToggle(300);
		});

		$(".listing-chapters_wrap ul.main > li a.has-child").on('click', function (e) {
			var $this = $(this);
			$(e.target).next("ul").slideToggle(300);
			$(e.target).parent().toggleClass("active");
		});

		$("#checkall").click(function () {
			$('table.list-bookmark input:checkbox').not(this).prop('checked', this.checked);
		});

		// header slider
		$(".manga-slider .slider__container").each(function () {

			var $this = $(this);
			var style = $(this).parents(".manga-slider").attr('data-style');
			var manga_slidesToShow = parseInt($(this).parents(".manga-slider").attr('data-count'));
			var check_style = $this.parents(".style-3").length;
			var check_rtl = (jQuery("body").css('direction') === "rtl");
			var manga_style_1 = {
				dots: true,
				infinite: true,
				speed: 500,
				centerMode: (((manga_slidesToShow % 2 !== 0) && (!check_style)) ? true : false),
				slidesToShow: manga_slidesToShow,
				slidesToScroll: check_style ? 3 : 1,
				arrows: false,
				rtl: check_rtl,
				responsive: [{
					breakpoint: 992,
					settings: {
						slidesToShow: (manga_slidesToShow == 1) ? 1 : 2,
						slidesToScroll: (manga_slidesToShow == 1) ? 1 : 2,
						infinite: true,
						centerMode: false,
						dots: true
					}
				}, {
					breakpoint: 660,
					settings: {
						slidesToShow: 1,
						slidesToScroll: 1,
						infinite: true,
						variableWidth: false,
						dots: true
					}
				}, {
					breakpoint: 480,
					settings: {
						slidesToShow: 1,
						variableWidth: false,
					}
				}]
			}
			var manga_style_2 = {
				dots: true,
				infinite: true,
				speed: 500,
				slidesToShow: manga_slidesToShow,
				slidesToScroll: manga_slidesToShow,
				arrows: false,
				rtl: check_rtl,
				responsive: [{
					breakpoint: 992,
					settings: {
						slidesToShow: (manga_slidesToShow == 1) ? 1 : 2,
						slidesToScroll: (manga_slidesToShow == 1) ? 1 : 2,
						infinite: true,
						dots: true
					}
				}, {
					breakpoint: 768,
					settings: {
						slidesToShow: 1,
						slidesToScroll: 1,
						infinite: true,
						dots: true
					}
				}]
			}
			var manga_style_3 = {
				dots: true,
				infinite: true,
				speed: 500,
				slidesToShow: manga_slidesToShow,
				slidesToScroll: manga_slidesToShow,
				arrows: false,
				rtl: check_rtl,
				responsive: [{
					breakpoint: 992,
					settings: {
						slidesToShow: (manga_slidesToShow == 1) ? 1 : 2,
						slidesToScroll: (manga_slidesToShow == 1) ? 1 : 2,
						infinite: true,
						dots: true
					}
				}, {
					breakpoint: 768,
					settings: {
						slidesToShow: 1,
						slidesToScroll: 1,
						infinite: true,
						dots: true
					}
				}]
			}

			switch (style) {
				case 'style-1':
					$this.slick(manga_style_1);
					break;
				case 'style-2':
					$this.slick(manga_style_2);
					break;
				case 'style-4':
					$this.slick(manga_style_3);
					break;
			}

		});

		// popular slider
		$(".popular-slider .slider__container").each(function () {
			var manga_slidesToShow = parseInt($(this).parents(".popular-slider").attr('data-count'));
			var check_rtl = (jQuery("body").css('direction') === "rtl");
			var popular_style_2 = {
				dots: false,
				infinite: true,
				speed: 500,
				slidesToShow: manga_slidesToShow,
				arrows: true,
				rtl: check_rtl,
				slidesToScroll: manga_slidesToShow,
				responsive: [
					{
						breakpoint: 1700,
						settings: {
							slidesToShow: (manga_slidesToShow == 1) ? 1 : 4,
							slidesToScroll: (manga_slidesToShow == 1) ? 1 : 4,
						}
					},
					{
						breakpoint: 1400,
						settings: {
							slidesToShow: (manga_slidesToShow == 1) ? 1 : 3,
							slidesToScroll: (manga_slidesToShow == 1) ? 1 : 3,
						}
					},
					{
						breakpoint: 992,
						settings: {
							slidesToShow: (manga_slidesToShow == 1) ? 1 : 2,
							slidesToScroll: (manga_slidesToShow == 1) ? 1 : 2,
						}
					},
					{
						breakpoint: 600,
						settings: {
							slidesToShow: 1,
							slidesToScroll: 1,
						}
					},
				]
			}
			var popular_style_1 = {
				dots: false,
				infinite: true,
				speed: 500,
				slidesToShow: manga_slidesToShow,
				arrows: true,
				rtl: check_rtl,
				slidesToScroll: manga_slidesToShow,
				responsive: [
					{
						breakpoint: 1700,
						settings: {
							slidesToShow: 4,
							slidesToScroll: 4,
						}
					},
					{
						breakpoint: 1200,
						settings: {
							slidesToShow: 3,
							slidesToScroll: 3,
						}
					},
					{
						breakpoint: 992,
						settings: {
							slidesToShow: 2,
							slidesToScroll: 2,
						}
					},
					{
						breakpoint: 600,
						settings: {
							slidesToShow: 1,
							slidesToScroll: 1,
						}
					},
				]
			}

			var popular_style_3 = popular_style_1;

			var $this = $(this);
			var style = $(this).parents(".popular-slider").attr('data-style');
			switch (style) {
				case 'style-1':
					$this.slick(popular_style_1);
					break;
				case 'style-2':
					$this.slick(popular_style_2);
					break;
				case 'style-3':
					$this.slick(popular_style_3);
					break;
			}

		});

		if ($('body').has('.summary__content').length) {
			var text = $('.summary__content'),
				btn = $('.content-readmore'),
				h = text[0].scrollHeight;
			if (h > 120) {
				btn.addClass('less');
				btn.css('display', 'inline-block');
			} else {
				btn.css('display', 'none');
				$('.description-summary').addClass('hide_show-more');
			}

			btn.click(function (e) {
				e.stopPropagation();

				if (btn.hasClass('less')) {
					btn.removeClass('less');
					btn.addClass('more');
					btn.text('Show less  ');
					text.addClass('active');
					text.animate({'height': h});
				} else {
					btn.addClass('less');
					btn.removeClass('more');
					text.removeClass('active');
					btn.text('Show more  ');
					text.animate({'height': '120px'});
				}
			});
		}

		if ($('body').has('.version-chap').length) {
			var text_chap = $('.version-chap');
			var btn_chap = $('.chapter-readmore');
			var height_parent = text_chap.height();
			var check_show_btn = function () {
				if (height_parent >= 550) {
					btn_chap.addClass('less-chap');
					btn_chap.fadeIn(300);
					$('.listing-chapters_wrap').addClass('show');
					text_chap.addClass('active');
				} else {
					btn_chap.fadeOut(300);
					$('.listing-chapters_wrap').removeClass('show');
					text_chap.removeClass('active')
				}
			}
			$(".listing-chapters_wrap ul.main > li.has-child").on('click', function (e) {
				var $this = $(this);
				setTimeout(function () {
					height_parent = $this.parents('.version-chap').height();
					check_show_btn();
				}, 300);
			});
			check_show_btn();
			btn_chap.click(function (e) {
				e.stopPropagation();
				if (btn_chap.hasClass('less-chap')) {
					btn_chap.removeClass('less-chap');
					btn_chap.fadeOut(300);
					text_chap.addClass('loading');
					$('.listing-chapters_wrap').removeClass('show');
					setTimeout(function () {
						btn_chap.remove();
						text_chap.animate({'max-height': '100%'});
						text_chap.removeClass('loading');
						text_chap.addClass('loaded');
					}, 1000);
				}
			});
		}
	});


	function shortString() {
		var shorts = document.querySelectorAll('.short');
		if (shorts) {
			Array.prototype.forEach.call(shorts, function (ele) {
				var str = ele.innerText,
					indt = '...';

				if (ele.hasAttribute('data-limit')) {
					if (str.length > ele.dataset.limit) {
						var result = str.substring(0, ele.dataset.limit - indt.length).trim() + indt;
						ele.innerText = result;
						str = null;
						result = null;
					}
				} else {
					throw Error('Cannot find attribute \'data-limit\'');
				}
			});
		}
	}

	window.onload = function () {
		shortString();
	};
})(jQuery);
