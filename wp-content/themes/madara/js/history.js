(function ($) {

	"use strict";

	jQuery(document).ready(function ($) {

		var interval = 5000;

		function updateHistory() {
			if( $('.wp-manga-chapter-img').length > 0 ){
				var img = $('.wp-manga-chapter-img').prop('id');
				var img_id = img.replace('image-', '');
			}else{
				var img_id = '';
			}

			$.ajax({
				url: user_history_params.ajax_url,
				type: 'POST',
				data: {
					action:      'manga-user-history',
					postID:      user_history_params.postID,
					chapterSlug: user_history_params.chapter,
					paged:       user_history_params.page,
					img_id:      img_id
				}
			});
		}

		setTimeout(updateHistory, interval);

	});

})(jQuery);
