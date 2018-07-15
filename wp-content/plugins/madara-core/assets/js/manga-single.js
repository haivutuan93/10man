(function ($) {

    $(document).ready(function() {

        // accordion  view chap
        jQuery(".listing-chapters_wrap ul.main li.has-child").append('<i class="icon ion-ios-plus-empty"></i>');

        $(".wp-manga-section .listing-chapters_wrap ul.main > li.has-child").on('click', function (e) {
            var $this = $(this);
            $(e.target).toggleClass("active").children("ul").slideToggle(300);
        });
    });

    $(document).on( 'mouseover', '.ratings_stars', function(){
        $(this).prevAll( '.ratings_stars ' ).andSelf().removeClass('ion-ios-star-outline');
        $(this).prevAll( '.ratings_stars ' ).andSelf().removeClass('ion-ios-star-half');
        $(this).prevAll( '.ratings_stars ' ).andSelf().addClass('ion-ios-star');

        $(this).nextAll( '.ratings_stars ' ).removeClass('ion-ios-star');
        $(this).nextAll( '.ratings_stars ' ).removeClass('ion-ios-star-half');
        $(this).nextAll( '.ratings_stars ' ).addClass('ion-ios-star-outline');
    });

    $(document).on( 'mouseout', '.ratings_stars', function(){
        $(this).prevAll().andSelf().removeClass('ion-ios-star').addClass('ion-ios-star-outline');
        var all = $('.ratings_stars');
        $.each( all,function(i,e){
            if ( $(e).hasClass( 'rating_current' ) ) {
                $(e).removeClass('ion-ios-star-outline')
                $(e).addClass('ion-ios-star');
            }
            if ( $(e).hasClass( 'rating_current_half' ) ) {
                $(e).removeClass('ion-ios-star-outline')
               $(e).addClass('ion-ios-star-half');
            }
        } )
    });

    $( document ).on('click', '.ratings_stars', function(e) {

            e.preventDefault();
            var t = $(this);
            var widget = $(this).parent();
            var star = t.parent().find('.ion-ios-star').length;
            var postID = $('.rating-post-id').val();

            $('.post-rating').css( 'opacity', '0.2' );
            $('.post-rating .user-rating').css( 'display', 'block' );
            $('.post-rating .post-total-rating').css( 'display', 'none' );
            $('.post-content .loader-inner').fadeIn();

            jQuery.ajax({
                url: wpMangaSingle.ajax_url,
                type: 'POST',
                data: {
                    action : 'wp-manga-save-rating',
                    star: star,
                    postID: postID,
                },
                success: function( response ) {
                    if ( response.success ) {
                        $('.summary-content.vote-details').text( response.data.text );
                        $('.post-rating').html( response.data.rating_html );
                    }

                },
                complete: function(xhr, textStatus) {
                    $('.post-rating .user-rating').removeAttr( 'style');
                    $('.post-rating .post-total-rating').removeAttr( 'style' );
                    $('.post-content .loader-inner').fadeOut();
                    $('.post-rating').css( 'opacity', '1' );
                }
            });
        });

})(jQuery);
