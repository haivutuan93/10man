jQuery(document).ready(function($){

    //bring to tabs
    var status = $('#status-section'),
        release = $('#release-year-section'),
        author = $('#author-section'),
        artist = $('#artist-section'),
        genre = $('#genre-section'),
        tags = $('#tags-section'),
        views = $('#views-section');

    $('#tagsdiv-wp-manga-release').appendTo(release);
    $('#manga_status_settings').appendTo(status);
    $('#tagsdiv-wp-manga-author').appendTo(author);
    $('#tagsdiv-wp-manga-artist').appendTo(artist);
    $('#wp-manga-genrediv').appendTo(genre);
    $('#manga_views').appendTo(views);
    $('#tagsdiv-wp-manga-tag').appendTo(tags);

    $('.wp-manga-content h2').removeClass('ui-sortable-handle');
    $('.wp-manga-content h2').removeClass('hndle');

    //save manga type
    $(document).on('click', 'input[name="wp-manga-chapter-type"]',function(){

        $('.wp-manga-info.wp-manga-tabs-wrapper').removeClass('choosing-manga-type');

        var postID = $('input[name="postID"]').val(),
            chapterType = $(this).val();

        //append manga chapter type label
        $('<span class="wp-manga-chapter-type-label"></span>').insertAfter('#manga-information-metabox > h2 > span');

        //set value for input wp-manga-chapter-type
        $('input[name="wp-manga-chapter-type"]').val( chapterType );

        var label = $('span.wp-manga-chapter-type-label');
        label.addClass(chapterType);

        chapterTypeLabel = chapterType == 'manga' ? 'image' : chapterType;
        label.text(chapterTypeLabel);

        $.ajax({
            url : wpManga.ajax_url,
            type : 'POST',
            data : {
                action : 'wp_manga_save_chapter_type',
                postID : postID,
                chapterType : chapterType,
            },
            success : function(){
                $('.choose-manga-type').remove();
            }
        });

    });

    //tabs
    $(document).on( 'click', '.wp-manga-tabs > ul > li', function(e){
        e.preventDefault();
        $('.wp-manga-tabs > ul > li').removeClass('tab-active');
        $(this).addClass('tab-active');

        $('.tab-content').hide();
        var content = $(this).children().attr('href');
        $(content).show();
    });

    $(document).on('click', 'input[name="wp-manga-chapter-type"]', function(){
        showChapterTypeTabs();
    });

    //show chapter type compatible tabs
    function showChapterTypeTabs(){

        if( $('input[name="wp-manga-chapter-type"]:checked').length !== 0 ){
            var chapterType = $('input[name="wp-manga-chapter-type"]:checked').val();
            $('input[name="wp-manga-chapter-type"][type="hidden"]').val( chapterType );
        }else if( $('input[name="wp-manga-chapter-type"]').length !== 0 ){
            var chapterType = $('input[name="wp-manga-chapter-type"][type="hidden"]').val();
        }

        if( chapterType == 'manga' ){
            $('.manga-tab-select').show();
            $('.text-tab-select').hide();
            $('.video-tab-select').hide();
        }else if( chapterType == 'text' ){
            $('.manga-tab-select').hide();
            $('.text-tab-select').show();
            $('.video-tab-select').hide();
        }else if( chapterType == 'video' ){
            $('.manga-tab-select').hide();
            $('.text-tab-select').hide();
            $('.video-tab-select').show();
        }else{
            $('.manga-tab-select').show();
            $('.text-tab-select').hide();
            $('.video-tab-select').hide();
        }

        $('.wp-manga-content .chapter-content-tab').each(function(i,e){
            if( chapterType == 'manga' && !$(this).hasClass('manga-chapter-tab') ){
                $(this).remove();
            }else if( ( chapterType == 'text' || chapterType == 'video' ) && !$(this).hasClass('chapter-content-tab') ){
                $(this).remove();
            }
        });
    }

	var modal = $('#wp-manga-chapter-modal');

    function showModal( refresh = true ){

        if( refresh == true ){
            modalRefresh();
        }

        modal.fadeIn(200);
    }

    function hideModal(){
        modal.fadeOut(200);
    }

    function showModalLoading(){
        $('#wp-manga-modal-content').addClass( 'loading' );
        $('.wp-manga-modal .wp-manga-popup-loading').show();
        $('.wp-manga-disable').removeClass('hidden');
    }

    function hideModalLoading(){
        $('#wp-manga-modal-content').removeClass( 'loading' );
        $('.wp-manga-modal .wp-manga-popup-loading').hide();
        $('.wp-manga-disable').addClass('hidden');
    }

    $(document).on('click', '.wp-manga-modal-dismiss', function(){
        hideModal();
    });

	$( "#manga-sortable" ).sortable();

	var doingAjax = false;
	var response = false;

    //edit chapter
	$(document).on( 'click', '.wp-manga-edit-chapter', function(e){

        e.preventDefault();

        var t = $( this );
        var postID = $('input[name="postID"]').val();
        var chapterID = t.attr( 'data-chapter' );

        if( chapterID == $('#wp-manga-modal-chapter').val() ){
            showModal( false );
        }else{

            if ( doingAjax == false ) {

                doingAjax = true;

                showModal();
                showModalLoading();

                $('#wp-manga-modal-post-id').val( postID );
                $('#wp-manga-modal-chapter').val( chapterID );

                ajaxGetChapterModalContent( postID, chapterID );

                doingAjax = false;
    		}

        }

	})

    function ajaxGetChapterModalContent( postID, chapterID ){

        $.ajax({
            url: wpManga.ajax_url,
            type: 'GET',
            dataType: 'json',
            data: {
                action:    'wp-manga-get-chapter',
                postID:    postID,
                chapterID: chapterID,
                type:      $('input[name="wp-manga-chapter-type"][type="hidden"]').val(),
            },
            success: function(resp){

                hideModalLoading();

                if ( resp.success == true ) {
                    modalGetChapter( resp.data );
                } else {
                    hideModal();
                    alert( resp.data );
                }
            },
        });

    }

    //save chapter
	$(document).on( 'click', '#wp-manga-save-paging-button',function(e){

		e.preventDefault();

        showModalLoading();

        var postID      = $('#wp-manga-modal-post-id').val();
		var chapterID         = $('#wp-manga-modal-chapter').val();
		var chapterNewName    = $('#wp-manga-modal-chapter-name').val();
		var chapterNameExtend = $('#wp-manga-modal-chapter-extend-name').val();
		var volume            = $('#manga-volume-dropdown').val();
        var chapterType = $('input[name="wp-manga-chapter-type"]').val();

        if( chapterType == 'text' || chapterType == 'video' ){

            tinyMCE.triggerSave();

            var chapterContent = $('textarea#wp-manga-chapter-content-wp-editor').val();

            // var isTMCEActive = isTinyMCEActive( 'wp-manga-chapter-content-wp-editor' );
            //
            // if( isTMCEActive ){
            //     var chapterContent = tinyMCE.get().getContent();
            // }else{
            //     var chapterContent = $('textarea#wp-manga-chapter-content-wp-editor').val();
            // }

            $.ajax({
    			url: wpManga.ajax_url,
    			type: 'POST',
    			dataType: 'json',
    			data: {
    				action  : 'wp-manga-save-chapter-paging',
    				postID: postID,
    				chapterID: chapterID,
    				chapterNewName: chapterNewName,
    				chapterNameExtend: chapterNameExtend,
    				volume: volume,
                    chapterType : chapterType,
                    chapterContent : chapterContent,
    			},
    			success: function(resp){

                    hideModalLoading();

    				if ( resp.success == true ) {
    					hideModal();
                        updateChaptersList();
    				} else {
                        hideModal();
    					alert( 'Can\'t Process.' );
    				}
    				doingAjax = false;
    			}
    		});

        }else{
            var images  = $('.wp-paging');
            var paging  = [];

            $.each( images,function(i,e) {
                paging.push( $(e).data( 'src' ) );
            })

            var storage = $('#manga-storage-dropdown').val();

            $.ajax({
    			url: wpManga.ajax_url,
    			type: 'POST',
    			dataType: 'json',
    			data: {
    				action  : 'wp-manga-save-chapter-paging',
    				paging : paging,
    				postID: postID,
    				chapterID: chapterID,
    				storage: storage,
    				chapterNewName: chapterNewName,
    				chapterNameExtend: chapterNameExtend,
    				volume: volume,
    			},
    			success: function(resp){

                    hideModalLoading();

    				if ( resp.success == true ) {
    					hideModal();
                        updateChaptersList();
    				} else {
                        hideModal();
    					alert( 'Can\'t Process.' );
    				}
    				doingAjax = false;
    			}
    		});
        }
	})

	$(document).on( 'click', '#wp-manga-dismiss-modal', function(e){
		hideModal();
	} )

	function modalRefresh() {
		$('#wp-manga-modal-post-id').val('');
		$('#wp-manga-modal-chapter').val('');

        modalContentRefresh();
	}

    function modalContentRefresh(){
        $('#manga-sortable').html('');
        $('#manga-storage-dropdown').html('');
        $('#manga-volume-dropdown').val('0');
		$('#wp-manga-modal-chapter-name').val();
        $('.duplicate-chapter').hide();
        $('#duplicate-server').empty();
    }

    function modalGetChapter( chapter, storage = '' ){

        if( storage !== '' ){
            modalContentRefresh();
        }

        //assign chapter name
        $('#wp-manga-modal-chapter-name').val(chapter.chapter.chapter_name);

        //assign chappter extend name
        if( chapter.chapter.chapter_name_extend !== '' ) {
            $('#wp-manga-modal-chapter-extend-name').val( chapter.chapter.chapter_name_extend);
        }

        //assign volume
        $('#manga-volume-dropdown').val( chapter.chapter.volume_id );

        if( chapter.type == 'manga' ){

            //add class for manga to wp-manga-modal-content
            $('#wp-manga-modal-content').addClass('manga');

            var sortZone = $('#manga-sortable');

            //assign in use storage
            var inUse = storage !== '' ? storage : chapter.data.storage.inUse;

            $.each( chapter.data.storage,function(i,e){
                if ( i != 'inUse' ) {
                    $('#manga-storage-dropdown').append('<option value='+i+'>'+e.name+'</option>')
                };

                if ( i == inUse ) {
                    $.each( e.page, function( num,value ){
                        switch(value.mime) {
                            case 'image/jpeg':
                                var html = '<li class="wp-paging" data-src="'+value.src+'" data-page="'+num+'"><img src="'+e.host+value.src+'"><a href="javascript:void(0)" class="delete-page"></a></li>';
                                break;
                            default:
                               var html = '<li class="wp-paging" data-src="'+value.src+'" data-page="'+num+'"><img src="'+e.host+value.src+'"><a href="javascript:void(0)" class="delete-page"></a></li>';
                        }
                        sortZone.append( html );
                    })
                }
            });

            $('#manga-storage-dropdown').val( inUse );

            if( chapter.available_host !== undefined ) {
                $.each( chapter.available_host, function( i, e  ) {
                    $('select#duplicate-server').append('<option value="' + e.value + '">' + e.text + '</option>');
                });
                $('.duplicate-chapter').show();
            }

        }else if( chapter.type == 'text' || chapter.type == 'video' ){
            var editorID = 'wp-manga-chapter-content-wp-editor',
                isTMCEActive = isTinyMCEActive( editorID );

            //set content for editor
            if( isTMCEActive ){
                tinyMCE.get( editorID ).setContent( chapter.data );
            }else if( isTMCEActive === false ){
                $('textarea#' + editorID ).val( chapter.data );
            }
        }

    }

	//Download Chapter
	$(document).on( 'click', '#wp-manga-download-chapter-button', function(e){

        e.preventDefault();

        var confirmDownload = confirm( 'Are you sure you want to download this chapter?');

        if( confirmDownload == false ){
            return;
        }

        showModalLoading();

		var postID = $('#wp-manga-modal-post-id').val();
		var chapterID = $('#wp-manga-modal-chapter').val();
		var storage = $('#manga-storage-dropdown').val();

		$.ajax({
			url: wpManga.ajax_url,
			type: 'POST',
			data: {
				action:    'wp-manga-download-chapter',
				postID:    postID,
				chapterID: chapterID,
				storage:   storage
			},
			success: function( response ) {

                hideModalLoading();

				if( response.success == true ) {
					window.location = response.data.zip_path;
					$.ajax({
						url: wpManga.ajax_url,
						type: 'POST',
						data: {
							action: 'wp-manga-delete-zip',
							zipDir : response.data.zip_dir,
						}
					});
				}else{
					alert(' Something wrong happened. Please try again later ');
				}
			}
		});
	});

    //Delete Chapter from list
    $(document).on( 'click', "#wp-manga-delete-chapter", function(e){

        e.preventDefault();

        var confirmDelete = confirm("Are you sure that you want to delete this Chapter?");

        if( confirmDelete == false ){
            return;
        }

        $(this).parent().fadeOut();

		var postID    = $('#post_ID').val();
		var chapterID = $(this).data('chapter');

        $.ajax({
			url: wpManga.ajax_url,
			type: 'POST',
			data: {
				action:    'wp-manga-delete-chapter',
				postID:    postID,
				chapterID: chapterID,
			},
			success: function(resp){
				if ( resp.success ) {
                    $(this).parent().remove();
				}
			},
		});

    });

	// Delete Chapter from Modal
	$(document).on( 'click', "#wp-manga-delete-chapter-button", function(e){
		e.preventDefault();

        var confirmDelete = confirm("Are you sure that you want to delete this Chapter?");

        if( confirmDelete == false ){
            return;
        }

        showModalLoading();

		var postID    = $('#wp-manga-modal-post-id').val();
		var chapterID = $('#wp-manga-modal-chapter').val();
		$.ajax({
			url: wpManga.ajax_url,
			type: 'POST',
			dataType: 'json',
			data: {
				action:    'wp-manga-delete-chapter',
				postID:    postID,
				chapterID: chapterID,
			},
			success: function(resp){
                hideModalLoading();
				if ( resp.success ) {
					updateChaptersList();
					hideModal();
				} else {
					alert( 'Can\'t Process.' );
                    hideModal();
				}
				doingAjax = false;
			},
		});
	})

	// storage change in modal
	$(document).on( 'change', '#manga-storage-dropdown', function(e){
		e.preventDefault();

        showModalLoading();

		var current = $(this).val();
		var postID = $('#wp-manga-modal-post-id').val();
		var chapterID = $('#wp-manga-modal-chapter').val();
		$.ajax({
			url: wpManga.ajax_url,
			type: 'GET',
			dataType: 'json',
			data: {
				action : 'wp-manga-get-chapter',
				postID : postID,
				chapterID : chapterID,
			},
			success: function(resp){
                hideModalLoading();

				if ( resp.success ) {
                    modalGetChapter( resp.data, current );
				} else {
					alert( resp.data );
				}
				doingAjax = false;
			},
		});
	})

    //add new volume
    $(document).on( 'click', 'button#new-volume', function(e){
        e.preventDefault();
        $(this).next('.new-volume').show();
    });

    $(document).on('click', 'button#add-new-volume', function(e){
        e.preventDefault();
        var volumeDropdown = $('.wp-manga-volume');
        var volumeName = $(this).parent().children('input[name="volume-name"]').val();
        var thisVolumeSelect = $(this).parents().children('.wp-manga-volume');
        var postID = $('input[name="postID"]').val();

        if( volumeName == '' ) {
            alert('Volume name cannot be empty');
            return;
        }

        $('.new-volume').addClass('loading');
        jQuery.ajax({
            url : wpManga.ajax_url,
            type : 'POST',
            data : {
                action : 'wp-manga-create-volume',
                postID : postID,
                volumeName : volumeName,
            },
            success : function( response ) {
                if( response.success == true ) {
                    $('.new-volume').fadeOut();
                    $('.add-new-volume').show();
                    $('input#volume-name').val('');
                    $('.new-volume').removeClass('loading');

                    //add new volume to dropdown
                    var volumeItem = '<option value="'+response.data+'">'+volumeName+'</option>';

                    volumeDropdown.each( function(i,e){
                        $(this).append( volumeItem );
                    } );

                    thisVolumeSelect.val( response.data );

                    updateChaptersList();
                }
            }
        });
    });

    //volume toggle
    $(document).on( 'click', 'h3.volume-title', function(){

        var volume = $(this).parent();
        volume.toggleClass('expanded');

        if( volume.hasClass( 'expanded' ) ){
            $(this).next().css( 'max-height', $(this).next().prop("scrollHeight") );
        }else{
            $(this).next().css( 'max-height', '' );
        }
    });

    //duplicate chapter
    $(document).on('click', '#duplicate-btn', function(e){

        e.preventDefault();

        showModalLoading();

        var postID = $('#wp-manga-modal-post-id').val();
        var chapterID = $('#wp-manga-modal-chapter').val();
        var duplicateServer = $('#duplicate-server').val();

        jQuery.ajax({
            url : wpManga.ajax_url,
            type : 'POST',
            data : {
                action : 'wp-manga-duplicate-server',
                postID : postID,
                chapterID : chapterID,
                duplicateServer : duplicateServer
            },
            success : function( response ) {

                if( response.success == true ) {
                    modalContentRefresh();
                    ajaxGetChapterModalContent( postID, chapterID );
                    alert(response.data);
                }

            },
            complete : function( jqXHR, textStatus ){

                hideModalLoading();

                $.ajax({
                    url : wpManga.ajax_url,
                    type : 'POST',
                    data : {
                        action : 'wp_manga_clean_temp_folder',
                        postID : postID,
                    },
                });

            }
        });
    });

    //delete image in chapter
    $(document).on( 'click', '.delete-page', function(e){
        e.preventDefault();
        var thisPage = $(this).closest('li');
        thisPage.remove();

    });

    //show blogspot albums
    $(document).on( 'change', 'select[name="wp-manga-chapter-storage"]', function(){
        if( $('select[name="wp-manga-chapter-storage"]').val() == 'picasa' ) {
            $('#chapter-upload #wp-manga-blogspot-albums').show();
        }else{
            $('#chapter-upload #wp-manga-blogspot-albums').hide();
        }
    });

    $(document).on( 'change', 'select[name="manga-storage"]', function(){
        if( $('select[name="manga-storage"]').val() == 'picasa' ) {
            $('#manga-upload #wp-manga-blogspot-albums').show();
        }else{
            $('#manga-upload #wp-manga-blogspot-albums').hide();
        }
    });

    //show volume name input
    $(document).on( 'click', '#edit-volume-name', function(e){
        var volumeInputField = $(this).parents('.volume-title').children('.volume-input-field'),
            volumeTitle = $(this).parents('.volume-title').children('span');

        volumeInputField.show();
        volumeInputField.focus();
        volumeTitle.css( 'opacity' , 0 );

    });

    //turn off volume toggle when click on child element
    $(document).on( 'click', 'h3.volume-title > *', function(e){
        e.stopPropagation();
    });

    $(document).on( 'focusout', '.volume-title > input.volume-input-field', function(){

        //hide volume name field
        $(this).hide();

        var oldName = $(this).parent('h3.volume-title').children('span'),
            newName = $(this).val(),
            volumeID = $(this).parents('.manga-single-volume').data('volume-id'),
            postID = $('input[name="post_ID"]').val();

        //if volume name is different, then ajax updating
        if( oldName.text() !== newName ){

            jQuery.ajax({
                url : wpManga.ajax_url,
                type : 'POST',
                data: {
                    action: 'update_volume_name',
                    postID : postID,
                    volumeID : volumeID,
                    volumeName : newName
                },
            });
        }

        //assign new volume name for volume title
        oldName.text( newName );
        oldName.css( 'opacity', 1 );
    });

    $(document).on( 'click', '#wp-manga-delete-volume', function(e){

        var question = 'Are you sure you want to delete this Volume and all chapters inside?';

        var thisVolume = $(this).parents('li.manga-single-volume'),
            volumeID = thisVolume.data('volume-id'),
            postID = $('input[name="post_ID"]').val();

        if( volumeID == 0 ){
            question += ' By this, you will delete all chapters without Volume.';
        }

        var confirmDelete = confirm( question );

        if( confirmDelete !== true ){
            return;
        }

        if( volumeID == 0 ){
            thisVolume.children('ul').hide();
            $(this).hide();
        }else{
            thisVolume.hide();
        }

        $.ajax({
            url : wpManga.ajax_url,
            type : 'POST',
            data : {
                action : 'wp_manga_delete_volume',
                volumeID : volumeID,
                postID : postID,
            },
            success : function(){
                if( volumeID == 0 ){
                    thisVolume.children('ul').hide();
                    $(this).remove();
                }else{
                    thisVolume.remove();
                }
            }

        });
    });

    //prevent submit when focusing on manga fields
    $(document).on( 'submit', 'form', function(e){

        if( $('#manga-information-metabox input[type="text"].disable-submit').is(':focus') ){
            $('.button').each(function( i, e ){
                if( $(this).hasClass( 'disabled' ) ) {
                    $(this).removeClass( 'disabled' );
                }
            });

            if( $('input[type="text"][name="volume-name"]').is(':focus') ) {
                $('button#add-new-volume').trigger('click');
            }

            if( $('.volume-title > input.volume-input-field').is(':focus') ){
                $('.volume-title > input.volume-input-field').each(function( i, e ){
                    if( $(this).is(':focus') ){
                        $(this).focusout();
                    }
                });
            }

            return false;
        }

    });

    //append chappter type label into metabox
    $(document).ready(function(){

        //if it's new chapter
        if( $('.choose-manga-type').length > 0 ){
            return;
        }

        var chapterType = $('input[name="wp-manga-chapter-type"]').val();

        $('<span class="wp-manga-chapter-type-label"></span>').insertAfter('#manga-information-metabox > h2 > span');

        var label = $('span.wp-manga-chapter-type-label');
        label.addClass(chapterType);
        label.text(chapterType);

    });

});
