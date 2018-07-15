jQuery(document).ready(function($){

    function cleanTempFolder( postID ){

        $.ajax({
            url : wpManga.ajax_url,
            type : 'POST',
            data : {
                action : 'wp_manga_clean_temp_folder',
                postID : postID,
            },
        });

    }

    //upload chapter
    $(document).on( 'click', '#wp-manga-chapter-file-upload', function(e){

    	e.preventDefault();

    	var post       = $('input[name="postID"]').val();
    	var name       = $('#wp-manga-chapter-name').val();
    	var storage    = $('#wp-manga-chapter-storage').val();
    	var nameExtend = $('#wp-manga-chapter-name-extend').val();

    	if ( !name || '' == name ) {
            mangaSingleMessage( 'You need to input Chapter\'s name', '#chapter-upload-msg', false );
    		return;
    	}

    	var volume = $('#chapter-upload #wp-manga-volume').val();

    	var fd = new FormData();
    	fd.append( 'post', post );
    	fd.append( 'name' , name );
        fd.append( 'nameExtend', nameExtend );
        fd.append( 'storage' , storage );
        fd.append( 'volume', volume );


        if( storage == 'picasa' ){
            fd.append( 'picasa_album', $('#chapter-upload #wp-manga-blogspot-albums').val() );
        }


        var file_data = $('#wp-manga-chapter-file')[0].files; // for multiple files

        if ( file_data.length == 0 ) {
            mangaSingleMessage( 'You need to upload files.', '#chapter-upload-msg', false );
    		return;
	    }

        var validate = validateFile( file_data[0], '#chapter-upload-msg' );
        if( validate == false ) {
            return;
        }

	    for(var i = 0;i<file_data.length;i++){
	        fd.append("file_"+i, file_data[i]);
	    }

        if( $('input[name="chapter-overwrite"]').is(':checked') ){
            if( $('input#overwrite').is(':checked') ){
                fd.append( 'overwrite', true );

                if( $('input[name="chapter-to-overwrite"]').is(':checked') ){
                    fd.append( 'c_overwrite', $('input[name="chapter-to-overwrite"]:checked').val() );
                }else{
                    mangaSingleMessage( 'Please choose chapter to overwrite', '#chapter-upload-msg', false );
                }
            }else{
                fd.append( 'overwrite', false );
            }
        }

        showLoading();
        $('#chapter-overwrite').hide();
        $('#chapters-overwrite-select').hide();
        $('input[name="chapter-overwrite"]').prop( 'checked', false );

	    $.ajax({
            url: wpManga.ajax_url+'?action=wp-manga-upload-chapter',
            type: 'POST',
            data: fd,
            enctype: 'multipart/form-data',
		    cache: false,
		    contentType: false,
		    processData: false,
	        success: function( resp ) {

                //if success
                if ( resp.success == true ) {

                    updateChaptersList();

                    //clear fields when success
                    clearFormFields( '.chapter-input' );

                    mangaSingleMessage( 'Upload Complete!', '#chapter-upload-msg', true );

	            }else if( resp.success == false && resp.data !== undefined && resp.data.error == 'chapter_existed' ) { //if false and chapter is existed

                    mangaSingleMessage( resp.data.message, '#chapter-upload-msg', false );
                    $('#chapter-overwrite').show();

                    if( resp.data.output !== undefined ) {
                        $('.chapter-overwrite-contains').html( resp.data.output );
                    }

                }else if( resp.success == false && resp.data.error == 'storage_error' ){
                    mangaSingleMessage( resp.data.message, '#chapter-upload-msg', false );
                }else{
                    mangaSingleMessage( resp.data, '#chapter-upload-msg', false );
                }

	        },
            complete: function( jqXHR, textStatus ){
                hideLoading();
                cleanTempFolder();
                if( storage == 'picasa' ){
                    updatePicasaAlbumDropdown( $('#chapter-upload #wp-manga-blogspot-albums').val() );
                }
            }
	    });

    });

    $(document).on( 'click', 'input[name="chapter-overwrite"]', function(){
        if( $('#overwrite').is(':checked') ) {
            $('#chapters-overwrite-select').show();
        }else{
            $('#chapters-overwrite-select').hide();
        }
    });

    //upload manga
    $(document).on('click', '#wp-manga-upload', function(e){

        e.preventDefault();

        var mangaFile = $('#wp-manga-file')[0].files[0];
        var volume = $('#wp-manga-volume-upload').val();
        var storage = $('select[name="manga-storage"]').val();
        var postID = $('input[name="post_ID"]').val();

        if( $('#wp-manga-file')[0].files.length == 0 ) {
            mangaSingleMessage( 'Please choose file first', '#manga-upload-msg', false );
            return false;
        }
        showLoading();
        var formData = new FormData;
        formData.append( 'mangaFile', mangaFile );
        formData.append( 'volume', volume );
        formData.append( 'storage', storage );
        formData.append( 'postID', postID );

        if( storage == 'picasa' ){
            formData.append( 'picasa_album', $('#manga-upload #wp-manga-blogspot-albums').val() );
        }

        jQuery.ajax({
            url : wpManga.ajax_url+'?action=wp-manga-upload',
            processData : false,
            contentType : false,
            enctype: 'multipart/form-data',
            type : 'POST',
            data : formData,
            success : function( response ){
                if( response.success == true ) {
                    mangaSingleMessage( response.data, '#manga-upload-msg', true );
                    updateChaptersList();
                }

                if( response.success == false ) {
                    mangaSingleMessage( response.data, '#manga-upload-msg', false );
                }
            },
            complete: function( jqXHR, textStatus ){
                hideLoading();
                cleanTempFolder();
                if( storage == 'picasa' ){
                    updatePicasaAlbumDropdown( $('#manga-upload #wp-manga-blogspot-albums').val() );
                }
            }
        });
    });

    function updatePicasaAlbumDropdown( current_album ){

        $.ajax({
            url : wpManga.ajax_url,
            type: 'POST',
            data : {
                action : 'update_picasa_album_dropdown',
                current_album : current_album
            },
            success : function( response ){
                if( response.success == true ){
                    $('.wp-manga-blogspot-albums').each(function( i, e ){
                        $(this).empty();
                        $(this).append( response.data );
                    });
                }else{
                    return;
                }
            }
        });

    }

    //upload content chapter
    $(document).on( 'click', '#chapter-content-upload-btn', function(e){

        e.preventDefault();

        var volume = $('#chapter-content-upload .wp-manga-volume').val(),
            file = $('#chapter-content-upload #chapter-content-file')[0].files,
            chapterType = $('input[name="wp-manga-chapter-type"]').val(),
            postID = $('input[name="postID"]').val(),
            messageID = '#chapter-content-upload-msg';

        if( file.length === 0 ){
            mangaSingleMessage( 'Please choose Multi Chapters File', messageID, false );
        }

        var fd = new FormData();

        fd.append( 'file', file[0] );
        fd.append( 'volume', volume );
        fd.append( 'chapterType', chapterType );
        fd.append( 'postID', postID );
        fd.append( 'action', 'chapter_content_upload' );

        $.ajax({
            url : wpManga.ajax_url,
            type : 'POST',
            data: fd,
            enctype: 'multipart/form-data',
		    cache: false,
		    contentType: false,
		    processData: false,
            beforeSend : function(){
                showLoading();
            },
            success : function( response ){
                if( response.success ){
                    clearFormFields('#chapter-content-upload');
                    mangaSingleMessage( 'Upload Complete', messageID, true );
                }
                // else{
                //     mangaSingleMessage( response.data.message, messageID, false );
                // }
            },
            complete : function(){
                hideLoading();
                updateChaptersList();
            }
        });
    });
});
