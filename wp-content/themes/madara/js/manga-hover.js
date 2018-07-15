(function ($) {
	'use strict';
	$(function (e) {
		$(document).ready(function () {

			var delay = 650, setTimeoutConst;
			$('body').delegate('.page-item-detail .item-thumb.hover-details', 'mouseenter mouseleave', function (e) {
				var $this = $(this);

				var postID = $this.closest('.c-image-hover').attr('data-post-id');

				e.preventDefault();

				if (e.type == 'mouseenter') {

					$('#hover-infor').before("<i class='icon-load-info fa fa-spinner'></i>");
					setTimeoutConst = setTimeout(function () {
						$this = $(e.currentTarget);
						if (!$this.hasClass('hover-loaded') && !$this.hasClass('loading')) {
							$this.addClass('loading');
							if ($('#hover-infor').hasClass('hover-loaded')) {
								$('#hover-infor').removeClass('hover-loaded');
							}

							if ($('#hover-infor #manga-hover-' + postID).length == 0) {
								var $this = $(this);
								$.ajax({
									type: 'post',
									dataType: 'html',
									url: madara_hover_load_post.ajax_url,
									data: {
										action: 'madara_hover_load_post',
										postid: postID,
									},
									success: function (response) {
										$('#hover-infor > div').css('display', 'none');
										$('#hover-infor').append(response);
										$('#hover-infor #manga-hover-' + postID).fadeIn(400);
										$this.addClass('hover-loaded');
										$this.removeClass('loading');
										$('#hover-infor').addClass('hover-loaded');
										$('#hover-infor').removeClass('loading');
										$('.icon-load-info').remove();
										$this.fadeIn(400);
									},
									error: function (response) {
										$this.removeClass('hover-loaded');
										$this.removeClass('loading');
										$('#hover-infor').removeClass('loading');
										$('#hover-infor').removeClass('hover-loaded');
									}

								});
							}
						} else if ($('#hover-infor').hasClass('hover-loaded')) {
							$('.icon-load-info').remove();
							$('#hover-infor #manga-hover-' + postID).fadeIn(400);
						}
					}, delay);
				} else if (e.type == 'mouseleave') {
					clearTimeout(setTimeoutConst);
					$('#hover-infor > div').css('display', 'none');
					$('.icon-load-info').remove();

				}
			});

		});
	});
})(jQuery);