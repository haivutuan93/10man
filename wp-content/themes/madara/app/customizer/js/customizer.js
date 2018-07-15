/**
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

(function ($) {
	// Site title and description.
	wp.customize('blogname', function (value) {
		value.bind(function (to) {
			//$('.site-header.style-1 .c-top-bar .c-top-bar__inner .c-message p').text(to);
		});
	});
	wp.customize('blogdescription', function (value) {
		value.bind(function (to) {
			//$('.site-description').text(to);
		});
	});
})(jQuery);
