	jQuery(document).ready(function($){
		
        $(document).on( 'click', '.search-adv-reset', function(){
			$('input:text').val('');
			$('input:checkbox').attr('checked', false);
			$('input:radio').attr('checked', false);
			return false;
		});
		
        $(document).on( 'click', '#manga-search-adv', function(){
			$('#search-advanced').toggleClass('collapse');
			return false;
		});
		
	});