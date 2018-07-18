jQuery(document).ready(function($){
        addChapterListReadingPage();
        function addChapterListReadingPage(){
            var postID = $("#manga-nav-header").attr("name");
            var curChap = $("#manga-nav-header").attr("data-value");
            $.ajax({
			type : 'GET',
			url : 'https://10manga.com/api/ajax/ajax_chapter/',
			data : {				
				action : 'wp-manga-list-chapter-reading', 
                                postID: postID,
                                curChap: curChap
			},
			success : function( response ){
                                $('#manga-nav-header').css("margin-bottom", 0 +"px");
                                $('#manga-nav-header').append(response);
                                $('#manga-nav-footer').html(response);
			}
		});
        }

	function chapterNavigationAjax( page ){

		if( typeof reading_ajax_params == 'undefined' ){
			return false;
		}

		var readContainer = $('.read-container'),
			loadingHTML = '<i class="fas fa-spinner fa-spin"></i>',
			currentPageSelect = $('.wp-manga-nav select#single-pager > option[selected="selected"]'),
			prevBtn = $('.nav-links .nav-previous'),
			nextBtn = $('.nav-links .nav-next');

		$.ajax({
			type : 'GET',
			url : manga.ajax_url,
			data : {
				postID : reading_ajax_params.postID,
				chapter : reading_ajax_params.chapter,
				'manga-paged' : page,
				action : 'chapter_navigate_page'
			},
			beforeSend : function(){
				readContainer.html( loadingHTML );

				//set changes in navigation
				currentPageSelect.removeAttr('selected');

				var newPageSelect =
				$('.wp-manga-nav select#single-pager > option[value="' + page + '"]');
				newPageSelect.attr('selected', 'selected');

				//remove prev or next if doesn't have prev or next navigation
				if( newPageSelect.prev().length == 0 && typeof reading_ajax_params.prev_chap_url == 'undefined' ){
					prevBtn.hide();
				}else{
					prevBtn.show();
				}
				if( newPageSelect.next().length == 0 && typeof reading_ajax_params.next_chap_url == 'undefined' ){
					nextBtn.hide();
				}else{
					nextBtn.show();
				}

				//apend to url
				history.pushState({}, null, newPageSelect.data('redirect') );
				//window.location.href = '/?manga-page=' + page;
			},
			success : function( response ){
				readContainer.html( response.data.data );
			}
		});
	}

	//chapter next page ajax
	$(document).on('click', '.wp-manga-nav .nav-links > div > a.btn', function(e){

		if( typeof reading_ajax_params == 'undefined' ){
			return true;
		}

		e.preventDefault();

		var currentPageSelect = $('.wp-manga-nav select#single-pager > option[selected="selected"]');

		//get query page
		if( $(this).hasClass('prev_page') ){
			//if this page doesn't have prev page then redirect to prev chap
			if( currentPageSelect.prev().length == 0 && typeof reading_ajax_params.prev_chap_url !== 'undefined' ){

				window.location = reading_ajax_params.prev_chap_url;

				return false;
			}else{
				var page = currentPageSelect.prev().attr('value');
			}
		}else{
			if( currentPageSelect.next().length == 0 && typeof reading_ajax_params.next_chap_url !== 'undefined' ){
				window.location = reading_ajax_params.next_chap_url;

				return false;
			}else{
				var page = currentPageSelect.next().attr('value');
			}
		}

		chapterNavigationAjax( page );

	});

	$(document).on('click', '.c-blog-post .entry-content .entry-content_wrap .read-container img', function(e){
		e.preventDefault();
		$('.wp-manga-nav .nav-links a.next_page')[0].click();
	});

	//show appropriate chapter select for volume
	$(document).on('change', '.selectpicker.volume-select', function(){
		$('.chapter-selection > .c-selectpicker.selectpicker_chapter').each(function(){
			$(this).hide();
		});
		$('.chapter-selection > .c-selectpicker.selectpicker_chapter[for="volume-id-' + $(this).val() + '"]').show();
	});

	//navigate by keyword button
	$(document).keydown(function(e){
		var redirect = '';

		if( e.keyCode == 37 && $('.wp-manga-nav .nav-links .nav-previous > a').length > 0 ){	//left key
			$('.wp-manga-nav .nav-links a.prev_page')[0].click();
		}else if( e.keyCode == 39 &&  $('.wp-manga-nav .nav-links .nav-next > a').length > 0 ){ //right key
			$('.wp-manga-nav .nav-links a.next_page')[0].click();
		}
	});


	//prevent empty Comment content submitting
	$(document).ready(function(){
		$('textarea#comment').attr('required', 'required');

		$('#commentform').on('submit', function(){
			if( !$('#commentform')[0].checkValidity() ){
				alert('Comment cannot be empty');
				return false;
			}
			return true;
		});
                
                
	});

	$(document).on( 'change', '.wp-manga-nav .single-chapter-select, #single-pager, .wp-manga-nav .host-select, .wp-manga-nav .reading-style-select', function(e){
		e.preventDefault();

		if( $(this).attr('id') == 'single-pager' ){
			if( typeof reading_ajax_params !== 'undefined' ){
				chapterNavigationAjax( $(this).val() );
				return false;
			}
		}

		var t = $(this);
		var redirect = t.find(':selected').attr('data-redirect');
		window.location = redirect;
	});

	var ajaxHandling = false;
	var loginModal = false;

	// bookmark action
	$(document).on( 'click', '.wp-manga-action-button', function(e) {

		e.preventDefault();

		if( typeof requireLogin2BookMark !== 'undefined' ){
			$('.modal#form-login').modal('show');
			$('input[name="bookmarking"]').val('1');
			return;
		}

		if( $('.add-bookmark').length != 0 ) {
			$('.add-bookmark').css('opacity', '0.5');
		}else{
			$('.action_list_icon > li:first-child').css('opacity', '0.5');
		}

		if ( !ajaxHandling ) {
			ajaxHandling = true;
			var t = $(this);
			var action = t.data( 'action');
			var postID = t.data( 'post' );
			var chapter = t.data( 'chapter' );
			var page = t.data( 'page' );
			jQuery.ajax({
				url: manga.ajax_url,
				type: 'POST',
				data: {
					action : 'wp-manga-user-action',
					userAction : action,
					postID : postID,
					chapter : chapter,
					page : page,
				},
				success: function( response ) {
					if ( response.success ) {
						if( $('.add-bookmark').length != 0 ) {
							$('.add-bookmark').empty();
							$('.add-bookmark').append( response.data );
							$('.add-bookmark').css('opacity', '1');
						}else{
							$('.action_list_icon > li:first-child').empty();
							$('.action_list_icon > li:first-child').append( response.data );
							$('.action_list_icon > li:first-child').css('opacity', '1');
						}

					} else {
						if ( response.data.code == 'login_error' ) {

						} else {
							alert( response.data.code );
						}
					}
				},
				complete: function(xhr, textStatus) {
					ajaxHandling = false;
				}
			});
		}
	})

	var ajaxBookmarkDelete = false;
	$(document).on( 'click', '.wp-manga-delete-bookmark', function(e){

		e.preventDefault();

		if ( !ajaxBookmarkDelete ) {

			ajaxBookmarkDelete = true;
			var t = $( this );
			var postID = t.data( 'post-id' );
			var rowBookmark = $(this).closest("tr");

			if( rowBookmark.length != 0 ) {
				rowBookmark.css('opacity', '0.5');
				var isMangaSingle = false;
			}

			if( $('.add-bookmark .action_icon .wp-manga-delete-bookmark').length != 0 ) {
				var isMangaSingle = true;
				$('.add-bookmark').css('opacity', '0.5');
			}else{
				var isMangaSingle = false;
				$('.action_list_icon > li:first-child').css('opacity', '0.5');
			}

			jQuery.ajax({
		        url: manga.ajax_url,
		        type: 'POST',
		        data: {
		            action : 'wp-manga-delete-bookmark',
		            postID: postID,
					isMangaSingle : isMangaSingle
		        },
		        success: function( response ) {
		            if ( response.success ) {
						if( rowBookmark.length != 0 ) {
							rowBookmark.fadeOut();
							rowBookmark.remove();
						}
						if( typeof isMangaSingle !== 'undefined' && isMangaSingle == true ) {
							$('.add-bookmark').empty();
							$('.add-bookmark').append( response.data );
							$('.add-bookmark').css('opacity', '1');
						}else if( typeof isMangaSingle !== 'undefined' && isMangaSingle == false ){
							$('.action_list_icon > li:first-child').empty();
							$('.action_list_icon > li:first-child').append( response.data );
							$('.action_list_icon > li:first-child').css('opacity', '1');
						}
		            }
		        },
		        complete: function(xhr, textStatus) {
		        	ajaxBookmarkDelete = false;
		        }
		    });
		}
	})

	var ajaxBookmarkDelete = false;
	$(document).on( 'click', '#delete-bookmark-manga', function(e){
		e.preventDefault();
		if ( !ajaxBookmarkDelete ) {
			ajaxBookmarkDelete = true;
			var bookmark = [];
			$('.bookmark-checkbox:checkbox:checked').each(function(i){
	          bookmark[i] = $(this).val();
			  $(this).closest('tr').addClass('remove');
			  $(this).closest('tr').css( 'opacity', '0.5' );
	        });
			jQuery.ajax({
		        url: manga.ajax_url,
		        type: 'POST',
		        data: {
		            action : 'wp-manga-delete-multi-bookmark',
		            bookmark: bookmark,
		        },
		        success: function( response ) {

		            if ( response.success ) {
						$('tr.remove').remove();
		            }
		        },
		        complete: function(xhr, textStatus) {
		        	ajaxBookmarkDelete = false;
		        }
		    });
		}
	})

	$(document).on( 'change', '#wp-manga-bookmark-checkall', function(e){
		e.preventDefault();
		var t = $(this);
		var chechbox = $('.bookmark-checkbox');
		if ( chechbox.length > 0 ) {
			if ( t.is(':checked') ) {
				$.each( chechbox, function(i,e){
					$(e).prop( 'checked', true );
				})
			} else {
				$.each( chechbox, function(i,e){
					$(e).prop( 'checked', false );
				})
			}
		}
	})

    // search
    // manga-search-field
	$('form.manga-search-form input.manga-search-field').each(function(){

		var searchIcon = $(this).parent().children('.ion-ios-search-strong');

	 	var append = $(this).parent();

		$(this).autocomplete({
			appendTo: append,
			source: function( request, resp ) {
				$.ajax({
					url: manga.ajax_url,
					type: 'POST',
					dataType: 'json',
					data: {
						action: 'wp-manga-search-manga',
						title: request.term,
					},
					success: function( data ) {
						resp( $.map( data.data, function( item ) {
							if ( true == data.success ) {
								return {
									label: item.title,
									value: item.title,
									url: item.url,
								}
							} else {
								return {
									label: item.message,
									value: item.message,
									click: false
								}
							}
						}))
					}
				});
			},
			select: function( e, ui ) {
				if ( ui.item.url ) {
					window.location.href = ui.item.url;
				} else {
					if ( ui.item.click ) {
						return true;
					} else {
						return false;
					}
				}
			},
			open: function( e, ui ) {
				var acData = $(this).data( 'uiAutocomplete' );
				acData.menu.element.addClass('manga-autocomplete').find('li').each(function(){
					var $self = $(this),
						keyword = $.trim( acData.term ).split(' ').join('|');
					$self.html( $self.text().replace( new RegExp( "(" + keyword + ")", "gi" ), '<span class="manga-text-highlight">$1</span>' ) );
				});
			}
		});
	});

	$(document).on( 'click', '#wp-manga-upload-avatar', function(e){

		e.preventDefault();

		var thisBtn = $(this),
			chooseAvatar = $('.choose-avatar'),
			userAvatarSection = $('.c-user-avatar');

		var fd = new FormData();
		var userAvatar = $('input[name="wp-manga-user-avatar"]')[0].files[0];
		var userID = $('input[name="userID"]').val();
		var _wpnonce = $('input[name="_wpnonce"]').val();

		fd.append( 'action', 'wp-manga-upload-avatar' );
		fd.append( 'userAvatar', userAvatar );
		fd.append( 'userID', userID );
		fd.append( '_wpnonce', _wpnonce );

		jQuery.ajax({
			url : manga.ajax_url,
			type : 'POST',
			enctype: 'multipart/form-data',
			cache: false,
			contentType: false,
			processData: false,
			data : fd,
			beforeSend: function(){
				thisBtn.attr('disabled', 'disabled');
				chooseAvatar.addClass('uploading');
			},
			success: function( response ) {
				if( response.success ) {
					userAvatarSection.empty();
					userAvatarSection.append( response.data );
				}
			},
			complete: function(){
				thisBtn.removeAttr('disabled');
				chooseAvatar.removeClass('uploading');
			}
		});

	});

	$(document).on( 'click', '.remove-manga-history', function(e){
		e.preventDefault();
		var postID = $(this).data('manga');
		var item = $(this).closest('.col-md-4');

		item.css( 'opacity', '0.5' );
		$.ajax({
			url : manga.ajax_url,
			type : 'POST',
			data : {
				'action' : 'manga-remove-history',
				'postID' : postID,
			},
			success : function( response ) {
				if( response.success ) {
					item.fadeOut();
					item.remove();
					if( response.data.is_empty == true ){
						$('.tab-pane#history').empty();
						$('.tab-pane#history').append( response.data.msg );
					}
				}else{
					item.css( 'opacity', '1' );
				}
			}
		});
	});

	//Ajax pagination
	var wpMangaAjaxPosts = false;

	$( document ).on( 'click', '.wp-manga-ajax-button', function(e){

		e.preventDefault();
		if ( wpMangaAjaxPosts == false ) {

			wpMangaAjaxPosts = true;

			var t = $( this ),
				e = $(this).data('element'),
				template = $(this).data('template'),
				button = $(this).parent();

			t.addClass('loading');

			$.ajax({
				url: manga.ajax_url,
				type: 'POST',
				data: {
					action: 'wp_manga_archive_loadmore',
					manga_args : manga_args,
					template : template
				},
				success: function( response ){
					t.removeClass('loading');
					$('.wp-manga-query-vars').remove();
					$(e).append( response );

					if( manga_args.paged >= manga_args.max_num_pages ){
						button.remove();
					}
				}
			})


		}
	});

});
