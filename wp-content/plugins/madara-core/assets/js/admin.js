jQuery(document).ready(function($){

	$(document).on( 'click', 'a.wp-manga-skip', function(e){
		e.preventDefault();

		$('.wp-manga-first-install-notice').fadeOut();
		$('.wp-manga-first-install-notice').remove();
		$.ajax({
			type: 'POST',
			url : ajaxurl,
			data : {
				action : 'wp_manga_skip_first_install',
			},
		});
	});

	// setting page

	$(document).on( 'click', '.dashicons.dashicons-arrow-down',function(e){
		e.preventDefault();
		var t = $(this);
		var image = t.parent().find( '.eg-detail img' );
		image.toggleClass('show');
	})

	//replace blogspot images progress in storage page
	$(document).on('click', '#replace-blogspot-url', function(e){
		e.preventDefault();

		var replaceWrapper = $('.replace-blogspot-wrapper');

		$('.replace-blogspot-wrapper .spinner').css('visibility', 'unset');

		$.ajax({
			type : 'POST',
			url : ajaxurl,
			data : {
				action : 'replace_blogspot_url'
			},
			success : function( response ){
				if( response.success ){
					replaceWrapper.empty();
					replaceWrapper.html('<span class="dashicons dashicons-yes"></span> Replaced successfully!');
				}
			}
		});
	});

	// imgur
	$(document).on( 'click', '#imgur-authorize', function(e){
		e.preventDefault();
		var imgurClientID = $('input[name="wp_manga[imgur_client_id]"]').val();
		var imgurClientSecret = $('input[name="wp_manga[imgur_client_secret]"]').val();
		if ( '' == imgurClientID || '' == imgurClientSecret  ) {
			if ( '' == imgurClientID && '' == imgurClientSecret ) {
				alert('You need to input Imgur Client ID and Client Secret');
			} else if ( '' == imgurClientID ) {
				alert('You need to input Imgur Client ID');
			} else if ( '' == imgurClientSecret ) {
				alert('You need to input Imgur Client Secret');
			}
		} else {
			jQuery.ajax({
				url: wpManga.ajax_url,
				type: 'POST',
				data: {
					action : 'wp-manga-imgur-save-credential',
					imgurClientID : imgurClientID,
					imgurClientSecret : imgurClientSecret,
				},
				success: function( response ) {
					if ( response.success ) {
					 	window.location = "https://api.imgur.com/oauth2/authorize?response_type=code&state=imgur&client_id="+imgurClientID;
					}
				},
				complete: function(xhr, textStatus) {
				}
			});

		}

	})

	// google
	$(document).on( 'click', '#google-authorize', function(e){
		e.preventDefault();
		var googleClientID = $('input[name="wp_manga[google_client_id]"]').val();
		var googleClientSecret = $('input[name="wp_manga[google_client_secret]"]').val();
		var googleRedirect = $('input[name="wp_manga[google_redirect]"]').val();
		if ( '' == googleClientID || '' == googleRedirect || '' == googleClientSecret  ) {
			if ( '' == googleClientID ) {
				alert('You need to input Google Client ID');
			} else if ( '' == googleClientSecret ) {
				alert('You need to input Google Client Secret');
			} else if ( '' == googleRedirect ) {
				alert('You need to input Google Redicret URL');
			}

		} else {
			jQuery.ajax({
				url: wpManga.ajax_url,
				type: 'POST',
				data: {
					action : 'wp-manga-google-save-credential',
					googleClientID : googleClientID,
					googleClientSecret : googleClientSecret,
					googleRedirect : googleRedirect,
				},
				success: function( response ) {
					if ( response.success ) {
					 	window.location = "https://accounts.google.com/o/oauth2/v2/auth?scope=https://picasaweb.google.com/data/&client_id="+googleClientID+"&redirect_uri="+googleRedirect+"&response_type=code&access_type=offline&state=picasa&include_granted_scopes=true&prompt=consent";
					}
				},
				complete: function(xhr, textStatus) {
				}
			});

		}

	})
});
