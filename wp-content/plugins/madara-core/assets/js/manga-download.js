	jQuery(document).ready(function($){

		$(document).on( 'click', '#wp-manga-download', function(e){

			e.preventDefault();
			var postID = $(this).attr( 'name' );
			var postName = $( '#post-' + postID + ' input[name="post-name"]').val();

			$('#wp-manga-popup-all').fadeIn();
			$('#wp-manga-post-title').text( 'Download "' + postName + '"' );
			$('#wp-manga-popup-content input[name="postID"]').val(postID);

		});

		$(document).on( 'click', '#wp-manga-download-button', function(e){

			e.preventDefault();

			$('.wp-manga-popup-loading').fadeIn();

			var postID = $('#wp-manga-popup-content input[name="postID"]').val();

			jQuery.ajax({

				url : ajaxurl,
				type : 'POST',
				data : {
					action : 'wp-download-manga',
					postID : postID,
				},
				success : function ( response ) {
					if( response.success == true ) {

						$('.wp-manga-popup-loading').fadeOut(300);
						window.location = response.data.zip.zip_path;

						jQuery.ajax({
							url : ajaxurl,
							type : 'POST',
							data : {
								action : 'wp-manga-delete-zip',
								zipDir : response.data.zip.zip_dir,
							},
						});

					}else{
						$('.wp-manga-popup-loading').fadeOut(300);
						$('.wp-manga-popup-content-msg').text(response.data);
						$('.wp-manga-popup-content-msg').fadeIn(1000);
					}
				}


			});

		});



        function clearDownloadPopup(){
			$('.wp-manga-popup-content-msg').hide();
			$('.wp-manga-popup-content-msg').empty();
		}

	});
