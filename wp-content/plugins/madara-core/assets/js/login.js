jQuery(document).ready(function($){

	//Reg submit
	$(document).on( 'click', 'form#registerform input[name="wp-submit"]', function(e) {

		e.preventDefault();
		var user_login = $("#registerform input[name='user_sign-up']").val();
		var user_pass = $("#registerform input[name='pass_sign-up']").val();
		var user_email = $("#registerform input[name='email_sign-up']").val();

		if( user_login =='' ) {
			$('p.message.register').text( 'Username can\'t be empty' );
			return false;
		}

		if( user_email =='' ) {
			$('p.message.register').text( 'Username can\'t be empty' );
			return false;
		}

		if( user_pass =='' ) {
			$('p.message.sign-up').text( 'Password can\'t be empty' );
			return false;
		}

		jQuery.ajax({
			url : wpMangaLogin.admin_ajax,
			type : 'POST',
			data : {
				action : 'wp_manga_signup',
				user_login: user_login,
				user_pass: user_pass,
				user_email: user_email,
			},
			success : function(response){
				if( response.success ){
					$('form#registerform').remove();
					$('p.message.register').text( response.data );
				}else{
					$('p.message.register').text( response.data );
				}
			}
		});

		return false;

	});

	//Login submit
	$(document).on( 'click', 'form#loginform input[name="wp-submit"]', function(e) {

		e.preventDefault();

		var user_login = $("#loginform input[name='log']").val();
		var user_pass = $("#loginform input[name='pwd']").val();
		var rememberme = $("#loginform input[name='rememberme']").val();
		var isBookmarking = $('input[name="bookmarking"]').val();
		var loginItems = $('.c-modal_item');

		if( user_login == '' ) {
			$('p.message.login').text( 'Please enter username' );
			return false;
		}

		if( user_pass == '' ) {
			$('p.message.login').text( 'Please enter username' );
			return false;

		}

		jQuery.ajax({
			url : wpMangaLogin.admin_ajax,
			type : 'POST',
			data : {
				action : 'wp_manga_signin',
				login : user_login,
				pass : user_pass,
				rememberme : rememberme
			},
			success : function(response){
				if( response.success == true ){
					$('.modal#form-login').modal('hide');
					if( loginItems.length != 0 ) {
						loginItems.empty();
						jQuery.ajax({
							type: 'POST',
							url : wpMangaLogin.admin_ajax,
							data : {
								action : 'wp-manga-get-user-section'
							},
							success : function( response ) {
								if( response.success ) {
									loginItems.append( response.data );
								}
							}
						});
					}
					if( isBookmarking == 1 ) {
						$('.wp-manga-action-button').trigger('click');
					}
				}else{
					$('p.message.login').text( 'Invalid Username or Password' );
				}
			}
		});
		return false;
	});

	$(document).on( 'click', 'form#resetform input[name="wp-submit"]', function(e){
		e.preventDefault();
		var user = $('input[name="user_reset"]').val();

		if( user == '' ) {
			$('p.message.reset').text( 'Username or Email cannot be empty' );
			return false;
		}

		jQuery.ajax({
			url : wpMangaLogin.admin_ajax,
			type : 'POST',
			data : {
				action : 'wp_manga_reset',
				user : user,
			},
			success : function( response ) {
				if( response.success ) {
					$('form#resetform').remove();
					$('p.message.reset').text( response.data );
				}else{
					$('p.message.reset').text( response.data );
				}
			}
		});

	});

	$(document).on( 'click', '.to-login', function(e){
		e.preventDefault();
		$('.modal').modal('hide');
		setTimeout( function(){
			$('#form-login').modal('show');
		}, 500 );
	});

	$(document).on( 'click', '.to-reset', function(e){
		e.preventDefault();
		$('.modal').modal('hide');
		setTimeout( function(){
			$('#form-reset').modal('show');
		}, 500 );
	});

	$(document).on( 'click', '.backtoblog', function(e){
		$('.modal').modal('hide');
	});

});
