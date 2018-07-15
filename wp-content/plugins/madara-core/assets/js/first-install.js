jQuery(document).ready(function($){

    $(document).on( 'click', 'a.main-button', function(){
        var step = $(this).attr( 'href' );
        var allSteps = $('a[data-toggle="tab"]');

        allSteps.each( function( i, e ){
            $(this).parent().removeClass('active');
        } );

        $('a[data-toggle="tab"][href="'+step+'"]').parent().addClass('active');
    });

    $(document).on( 'click', 'a[data-toggle="tab"]', function(){
        var step = $(this).attr( 'href' );
        var stepNo = step.charAt( step.length - 1);
        var width = 0 + 25 * ( stepNo - 1 );

        $('.active-liner').css( 'width', width+'%' );

        $('a[data-toggle="tab"]').each( function( i, e ){
            if( i + 1 < stepNo ) {
                $(this).addClass('active-step');
            }else{
                $(this).removeClass('active-step');
            }
        } );
    });

    $(document).on( 'click', 'a[data-save="manga-page"]', function(){
        var mangaArchivePage = $('select[name="manga_archive_page"]').val();
        var userPage = $('select[name="user_page"]').val();

        $.ajax({
            type: 'POST',
            url : manga_ajax_url.admin_ajax,
            data : {
                action : 'wp_manga_first_install_page_save',
                manga_archive_page : mangaArchivePage,
                user_page : userPage,
            },
        });

    });

    $(document).on( 'click', 'a[data-save="manga-post-type"]', function(){
        var mangaSlug = $('input[name="manga-slug"]').val();

        $.ajax({
            type: 'POST',
            url : manga_ajax_url.admin_ajax,
            data : {
                action : 'wp_manga_first_install_post_save',
                manga_slug : mangaSlug,
            },
        });

    });


});
