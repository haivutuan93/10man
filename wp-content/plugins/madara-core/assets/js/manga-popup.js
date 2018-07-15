jQuery(document).ready(function($){
        
        $(document).on( 'click', '.wp-manga-popup-background', function(e){
			
			$('#wp-manga-popup-all').hide();
			clearMangaPopup();
	
		});
		
		$(document).on( 'click', '#wp-manga-popup-exit', function(e){
			
			$('#wp-manga-popup-all').hide();
			clearMangaPopup();
	
		});
        
        function clearMangaPopup(){
			$('.wp-manga-chapters-list').hide();
			$('.wp-manga-chapters-list ul').empty();
			$('.wp-manga-popup-content-msg').hide();
			$('.wp-manga-popup-content-msg').empty();
		}

    });