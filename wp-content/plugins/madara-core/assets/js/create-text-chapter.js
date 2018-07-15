(function($){
    $(document).ready(function(){

        $(document).on('click', '#wp-manga-content-chapter-create', function(e){

            e.preventDefault();

            tinyMCE.triggerSave();

            var postID            = $('input[name="postID"]').val(),
                chapterName       = $('#chapter-content #wp-manga-chapter-name').val(),
                chapterNameExtend = $('#chapter-content #wp-manga-chapter-name-extend').val(),
                chapterVolume     = $('#chapter-content #wp-manga-volume').val(),
                chapterContent    = $('#chapter-content #wp-manga-chapter-content').val();

            if ( !chapterName || '' == chapterName ) {
                mangaSingleMessage( 'You need to input Chapter\'s name', '#chapter-create-msg', false );
            	return;
            }

            if ( !chapterContent || '' == chapterContent ) {
                mangaSingleMessage( 'Chapter Content cannot be empty', '#chapter-create-msg', false );
            	return;
            }

            showLoading();

            $.ajax({
                url : wpManga.ajax_url,
                type : 'POST',
                data : {
                    action:            'wp_manga_create_content_chapter',
                    postID:            postID,
                    chapterName:       chapterName,
                    chapterNameExtend: chapterNameExtend,
                    chapterVolume:     chapterVolume,
                    chapterContent:    chapterContent,
                },
                success : function( response ) {
                    if( response.success ){
                        mangaSingleMessage( response.data.message, '#chapter-create-msg', true );
                        clearFormFields( '#chapter-content' );

                        //reset content in editor
                        if( typeof tinyMCE !== 'undefined' && tinyMCE.get('wp-manga-chapter-content') !== null ){
                            tinyMCE.get('wp-manga-chapter-content').setContent('');
                            tinyMCE.triggerSave();
                        }
                    }else if ( typeof response.data !== 'undefined' ){
                        mangaSingleMessage( response.data.message, '#chapter-create-msg', false );
                    }
                    updateChaptersList();
                    hideLoading();
                }
            });

        });

    });
})(jQuery);
