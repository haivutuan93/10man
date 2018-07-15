jQuery(document).ready(function () {

	jQuery('a.debug-report').click(function () {

		var report = '';

		jQuery('.system-status table:not(.system-status-debug) thead, .system-status:not(.system-status-debug) tbody').each(function () {

			if (jQuery(this).is('thead')) {

				var label = jQuery(this).find('th:eq(0)').data('export-label') || jQuery(this).text();
				report = report + "\n### " + jQuery.trim(label) + " ###\n\n";

			} else {

				jQuery('tr', jQuery(this)).each(function () {

					var label = jQuery(this).find('td:eq(0)').data('export-label') || jQuery(this).find('td:eq(0)').text();
					var the_name = jQuery.trim(label).replace(/(<([^>]+)>)/ig, ''); // Remove HTML
					var the_value_element = jQuery(this).find('td:eq(2)');
					if (jQuery(the_value_element).find('img').length >= 1) {
						var the_value = jQuery.trim(jQuery(the_value_element).find('img').attr('alt'));
					} else {
						var the_value = jQuery.trim(jQuery(this).find('td:eq(2)').text());
					}
					var value_array = the_value.split(', ');

					if (value_array.length > 1) {

						// If value have a list of plugins ','
						// Split to add new line
						var output = '';
						var temp_line = '';
						jQuery.each(value_array, function (key, line) {
							temp_line = temp_line + line + '\n';
						});

						the_value = temp_line;
					}

					report = report + '' + the_name + ': ' + the_value + "\n";
				});

			}
		});

		try {
			jQuery('#debug-report').slideDown();
			jQuery('#debug-report textarea').val(report).focus().select();
			jQuery(this).parent().fadeOut();
			return false;
		} catch (e) {
			console.log(e);
		}

		return false;
	});


	jQuery('#copy-for-support').tipTip({
		'attribute': 'data-tip',
		'activation': 'click',
		'fadeIn': 50,
		'fadeOut': 50,
		'delay': 0
	});

	jQuery('body').on('copy', '#copy-for-support', function (e) {
		e.clipboardData.clearData();
		e.clipboardData.setData('text/plain', jQuery('#debug-report textarea').val());
		e.preventDefault();
	});

});