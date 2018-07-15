	jQuery(document).ready(function ($) {
        var requestAjax;

		$('#search-chapter').keyup(function () {
            if( requestAjax !== undefined ) {
                requestAjax.abort();
            }
			$('.search-chapter-section').addClass('loading');
			chapter = $('#search-chapter').val();
            postID = $('#post_ID').val();

            if( chapter == '' ) {
                requestAjax = jQuery.ajax({
                    url : wpManga.ajax_url,
                    type : 'POST',
                    data : {
                        action : 'wp-update-chapters-list',
                        postID : postID,
                    },
                    success : function( response ) {
                        if( response.success == true ) {
                            $('.chapter-list span').remove();
                            $('.chapter-list').html( response.data );
                        }
                    },
                    complete: function(){
					   $('.search-chapter-section').removeClass('loading');
				    }
                });
                return false;
            }

			requestAjax = jQuery.ajax({
				url: wpManga.ajax_url,
				type: 'POST',
				data: {
					action: 'search-chapter',
					chapter: chapter,
					post: postID,
				},
				success: function (response) {
					if (response.success == true) {
						$('.chapter-list').html(response.data);
					} else {
						$('.chapter-list').html('Something wrong happened');
					}
					$('.search-chapter-section').removeClass('loading');
				},
				complete: function(){

				}
			});
		});
	});
