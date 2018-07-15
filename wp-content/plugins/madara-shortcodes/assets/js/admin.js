String.prototype.endsWith = function(suffix) {
    return this.indexOf(suffix, this.length - suffix.length) !== -1;
};

jQuery.ajaxPrefilter(function( options, originalOptions, jqXHR ) {
    /**
     * Temporarily comment this line to fix VC saving row issue
     *
    /* options.async = true; */
});

jQuery(document).ajaxSuccess(function(e, xhr, settings) {
	var widget_id_base = 'text';
	
	/** 
	 * widget text is saved
	 */
	if(typeof settings.data !== 'undefined' && typeof settings.data == 'string' && settings.data.search('action=save-widget') != -1) {
		if(settings.data.search('id_base=text') != -1 || settings.data.search('id_base=black-studio-tinymce') != -1){
			// send widget settings to server to process again
			var data = {
				'action': 'ct_save_widget_text',
				'data': settings.data
			};

			// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
			jQuery.post(ajaxurl, data, function(response) {
				// server returns nothing! (intentionally)
				// alert(response);
			});
		}
	}
});