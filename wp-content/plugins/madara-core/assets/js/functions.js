
    function clearMessage( sel ) {
        jQuery( sel ).empty();
        jQuery( sel ).removeClass('success-msg');
        jQuery( sel ).removeClass('error-msg');
    }

    function addMessage( text, sel ){
        jQuery( sel ).text( text );
    }

    function mangaSingleMessage( text, sel, suc  ) {
        clearMessage( sel );
        addMessage( text, sel );

        if( suc == true ) {
            jQuery( sel ).addClass('success-msg');
        }else{
            jQuery( sel ).addClass('error-msg');
        }

        jQuery( sel ).fadeIn();

    }

    function showLoading(){
        jQuery('.wp-manga-popup-loading').fadeIn();
    }

    function hideLoading(){
        jQuery('.wp-manga-popup-loading').fadeOut();
    }

    function clearFormFields( sel ){

        jQuery( sel + ' input[type="text"]').each( function( i, e ){
            jQuery(this).val('');
        } );

        jQuery( sel + ' select option:first').each( function( i, e ){
            jQuery(this).prop( 'selected', true );
        });

        jQuery( sel + ' input[type="file"]').each( function( i, e ){
            jQuery(this).val('');
        });

    }

    function validateFile( file, sel ){

        if( file.size / 1000 >= file_size_settings.upload_max_filesize ) {
            mangaSingleMessage( 'This file exceeds the upload_max_filesize directive in php.ini', sel, false );
            return false;
        }

        if( file.size / 1000 >= file_size_settings.post_max_size ) {
            mangaSingleMessage( 'This file exceeds the post_max_size directive in php.ini', sel, false );
            return false;
        }

    }

    function updateChaptersList(){

        jQuery('.fetching-data').toggleClass('hidden');
        jQuery('.chapter-list').css('opacity', '0.5');

        var postID = jQuery('input[name="post_ID"]').val();

        jQuery.ajax({
            url : wpManga.ajax_url,
            type : 'POST',
            data : {
                action : 'wp-update-chapters-list',
                postID : postID,
            },
            success : function( response ) {
                if( response.success == true ) {
                    jQuery('.chapter-list span').remove();
                    jQuery('.chapter-list').html( response.data );
                }

                jQuery('.fetching-data').toggleClass('hidden');
                jQuery('.chapter-list').css('opacity', '1');

            }
        });
    }

    function syncTextContent( id ){
        var textTab = jQuery( 'textarea#' + id );

        if( isTinyMCEActive === false ){
            tinyMCE.get( id ).setContent( textTab.val() );
        }
    }

    function isTinyMCEActive( id ){

        if( typeof tinyMCE == 'undefined' || tinyMCE.get( id ) == null ){
            return false;
        }

        var wrapper = jQuery( '#wp-' + id + '-wrap' );

        return wrapper.hasClass('tmce-active');
    }
