/**
 * Declare list of shortcodes. Id must be the same with each shortcode ID (declared in their related .js file)
 */
var madara_SHORTCODES_LIST = {
	blog: 'Blog',
	heading: 'Heading',
	post_slider: 'Post Slider',
	manga_chapter: 'Manga Chapter',
	manga_listing: 'Manga Listing',
};

/**
 *
 * DO NOT EDIT BELOW THIS LINE ============================================
 *
 */

var madara_shortcode_createColorPickAction = function () {
	// Taken from core plugins
	var editor = tinymce.activeEditor;

	var colorPickerCallback = editor.settings.color_picker_callback;

	if (colorPickerCallback) {
		return function () {
			var self = this;

			colorPickerCallback.call(
				editor,
				function (value) {
					self.value(value).fire('change');
				},
				self.value()
			);
		};
	}
};

/**
 * Default Effect Options for shortcode.element
 */
var madara_SHORTCODE_EFFECT_OPTIONS = [
	{
		type: 'listbox',
		name: 'aos',
		label: 'Animation Effect',
		values: [
			{text: 'none', value: 'none'},
			{text: 'fade', value: 'fade'},
			{text: 'fade-up', value: 'fade-up'},
			{text: 'fade-down', value: 'fade-down'},
			{text: 'fade-left', value: 'fade-left'},
			{text: 'fade-right', value: 'fade-right'},
			{text: 'fade-up-right', value: 'fade-up-right'},
			{text: 'fade-up-left', value: 'fade-up-left'},
			{text: 'fade-down-right', value: 'fade-down-right'},
			{text: 'fade-down-left', value: 'fade-down-left'},
			{text: 'flip-up', value: 'flip-up'},
			{text: 'flip-down', value: 'flip-down'},
			{text: 'flip-left', value: 'flip-left'},
			{text: 'flip-right', value: 'flip-right'},
			{text: 'slide-up', value: 'slide-up'},
			{text: 'slide-left', value: 'slide-left'},
			{text: 'slide-right', value: 'slide-right'},
			{text: 'zoom-in', value: 'zoom-in'},
			{text: 'zoom-in-up', value: 'zoom-in-up'},
			{text: 'zoom-in-down', value: 'zoom-in-down'},
			{text: 'zoom-in-left', value: 'zoom-in-left'},
			{text: 'zoom-in-right', value: 'zoom-in-right'},
			{text: 'zoom-out', value: 'zoom-out'},
			{text: 'zoom-out-up', value: 'zoom-out-up'},
			{text: 'zoom-out-down', value: 'zoom-out-down'},
			{text: 'zoom-out-left', value: 'zoom-out-left'},
			{text: 'zoom-out-right', value: 'zoom-out-right'},
		],
		tooltip: 'Choose come-in effect for this element'
	},
	{
		type: 'textbox',
		name: 'aos_delay',
		label: 'Animation Delay',
		value: "100",
		id: '_aos_delay',
		tooltip: 'Number of milliseconds before showing up'
	},
	{
		type: 'textbox',
		name: 'aos_offset',
		label: 'Animation Offset',
		value: "100",
		id: '_aos_offset',
		tooltip: 'Number of milliseconds of animation duration'
	},
	{
		type: 'textbox',
		name: 'aos_duration',
		label: 'Animation Duration',
		value: "400",
		id: '_aos_duration',
		tooltip: 'Duration of animation, in milliseconds'
	},
	{
		type: 'listbox',
		name: 'aos_easing',
		label: 'Animation Easing',
		values: [
			{text: 'none', value: 'none'},
			{text: 'linear', value: 'linear'},
			{text: 'ease', value: 'ease'},
			{text: 'ease-in', value: 'ease-in'},
			{text: 'ease-out', value: 'ease-out'},
			{text: 'ease-in-out', value: 'ease-in-out'},
			{text: 'ease-in-back', value: 'ease-in-back'},
			{text: 'ease-out-back', value: 'ease-out-back'},
			{text: 'ease-in-out-back', value: 'ease-in-out-back'},
			{text: 'ease-in-sine', value: 'ease-in-sine'},
			{text: 'ease-out-sine', value: 'ease-out-sine'},
			{text: 'ease-in-out-sine', value: 'ease-in-out-sine'},
			{text: 'ease-in-quad', value: 'ease-in-quad'},
			{text: 'ease-out-quad', value: 'ease-out-quad'},
			{text: 'ease-in-out-quad', value: 'ease-in-out-quad'},
			{text: 'ease-in-cubic', value: 'ease-in-cubic'},
			{text: 'ease-out-cubic', value: 'ease-out-cubic'},
			{text: 'ease-in-out-cubic', value: 'ease-in-out-cubic'},
			{text: 'ease-in-quart', value: 'ease-in-quart'},
			{text: 'ease-out-quart', value: 'ease-out-quart'},
			{text: 'ease-in-out-quart', value: 'ease-in-out-quart'},
		],
		tooltip: 'Choose Easing Effect'
	},
	{
		type: 'listbox',
		name: 'aos_once',
		label: 'Animation Loop',
		values: [
			{text: 'Infinite', value: 'false'},
			{text: 'Once', value: 'true'}
		],
		tooltip: 'Only animate once or infinite'
	}
];

/**
 * Default Design Options for shortcode.block
 */
var madara_SHORTCODE_DESIGN_OPTIONS = madara_SHORTCODE_EFFECT_OPTIONS.concat([
	{
		type: 'checkbox',
		name: 'container',
		label: 'Container',
		value: "",
		id: '_container',
		tooltip: 'Wrap the element in a container or not (full-width block)'
	},
	{
		type: 'textbox',
		name: 'padding',
		label: 'Padding',
		value: "",
		id: '_padding',
		tooltip: 'Format: TOP RIGHT BOTTOM LEFT. ex: 0px 0px 0px 0px'
	},
	{
		type: 'textbox',
		name: 'margin',
		label: 'Margin',
		value: "",
		id: '_margin',
		tooltip: 'Format: TOP RIGHT BOTTOM LEFT. ex: 0px 0px 0px 0px'
	},
	{
		type: 'checkbox',
		name: 'gutter',
		label: 'Gutter',
		value: "",
		id: '_gutter',
		tooltip: 'Gutter is the margin between columns'
	},
	{
		type: 'textbox',
		classes: 'colorbox',
		placeholder: '#000000',
		name: 'background_color',
		label: 'Background Color',
		value: "",
		tooltip: 'Hexa color of Background',
		id: '_background_2134234color'
	},
	{
		type: 'textbox',
		name: 'background_image',
		label: 'Background Image',
		value: "",
		id: '_background_image',
		tooltip: 'URL or Attachment ID of background image'
	},
	{
		type: 'checkbox',
		name: 'parallax',
		label: 'Parallax',
		value: "",
		id: '_parallax',
		tooltip: 'Enable Parallax effect for background image'
	},
]);

/**
 * Extends shortcode Typo & Effect Options.
 */
var madara_SHORTCODE_EXTENDS_TYPO = [

	{type: 'textbox', name: 'size', label: 'Font Size', tooltip: 'In Pixel. Example: 14px'},

	{
		type: 'textbox',
		name: 'line_height',
		label: 'Line Height',
		'values': '',
		tooltip: 'Text Line Height. Ex: 1.5em or 24px'
	},

	{
		type: 'listbox',
		name: 'weight',
		label: 'Font Weight',
		'values': [
			{text: 'Weight', value: ''},
			{text: '100', value: '100'},
			{text: '200', value: '200'},
			{text: '300', value: '300'},
			{text: '400', value: '400'},
			{text: '500', value: '500'},
			{text: '500', value: '500'},
			{text: '600', value: '600'},
			{text: '700', value: '700'},
			{text: '800', value: '800'},
			{text: '900', value: '900'},
			{text: 'Bold', value: 'bold'},
			{text: 'Bolder', value: 'bolder'},
			{text: 'Inherit', value: 'inherit'},
			{text: 'Initial', value: 'initial'},
			{text: 'Lighter', value: 'lighter'},
			{text: 'Normal', value: 'normal'},
		]
	},

	{
		type: 'listbox',
		name: 'style',
		label: 'Font Style',
		'values': [
			{text: 'Style', value: ''},
			{text: 'inherit', value: 'inherit'},
			{text: 'initial', value: 'initial'},
			{text: 'italic', value: 'italic'},
			{text: 'normal', value: 'normal'},
			{text: 'oblique', value: 'oblique'},
		]
	},

	{
		type: 'textbox',
		name: 'spacing',
		label: 'Letter Spacing',
		tooltip: 'Letter Spacing value, including suffix. For example: 10px'
	},

	{
		type: 'textbox',
		classes: 'colorbox',
		placeholder: '#000000',
		name: 'color',
		label: 'Color',
		value: "",
		id: 'newcolorpicker_typocolor'
	},

	{
		type: 'textbox',
		classes: 'colorbox',
		placeholder: '#ffffff',
		name: 'bg_color',
		label: 'Background Color',
		value: "",
		id: 'newcolorpicker_typo_bg_color'
	},

	{
		type: 'textbox',
		name: 'padding',
		label: 'Padding',
		value: "",
		id: 'typo_padding',
		tooltip: 'Format: TOP RIGHT BOTTOM LEFT. ex: 0px 0px 0px 0px'
	},

	{
		type: 'textbox',
		name: 'margin',
		label: 'Margin',
		value: "",
		id: 'typo_margin',
		tooltip: 'Format: TOP RIGHT BOTTOM LEFT. ex: 0px 0px 0px 0px'
	},

	{
		type: 'listbox',
		name: 'alignment',
		label: 'Alignment',
		'values': [
			{text: 'Align Left', value: 'left'},
			{text: 'Align Right', value: 'right'},
			{text: 'Align Center', value: 'center'},
		]
	},

	{
		type: 'textbox',
		name: 'font_family',
		label: 'Font Family ',
		tooltip: 'Enter custom Font Family Name for this typo'
	},

	{
		type: 'textbox',
		name: 'mobile_size',
		label: 'Mobile Font Size',
		tooltip: 'Font Size on mobile screen, in Pixel. Example: 14px'
	},

	{
		type: 'textbox',
		name: 'mobile_line_height',
		label: 'Mobile Line Height',
		tooltip: 'Line Height on mobile screen, in Pixel. Example: 26px'
	},

	{
		type: 'textbox',
		name: 'mobile_padding',
		label: 'Mobile Padding',
		value: "",
		id: 'typo_mobile_padding',
		tooltip: 'Padding on mobile screen. Format: TOP RIGHT BOTTOM LEFT. ex: 0px 0px 0px 0px'
	},

	{
		type: 'textbox',
		name: 'mobile_margin',
		label: 'Mobile Margin',
		value: "",
		id: 'typo_mobile_margin',
		tooltip: 'Margin on mobile screen. Format: TOP RIGHT BOTTOM LEFT. ex: 0px 0px 0px 0px'
	},

	{
		type: 'listbox',
		name: 'mobile_alignment',
		label: 'Mobile Alignment',
		'values': [
			{text: 'Default', value: ''},
			{text: 'Align Left', value: 'left'},
			{text: 'Align Right', value: 'right'},
			{text: 'Align Center', value: 'center'},
		]
	},
];
madara_SHORTCODE_EXTENDS_TYPO = madara_SHORTCODE_EXTENDS_TYPO.concat(madara_SHORTCODE_EFFECT_OPTIONS);

function madara_shortcode_get_effect_params(data) {
	var params = '';
	if (data.aos != '') {
		params = ' aos="' + data.aos + '" aos-offset="' + data.aos_offset + '" aos-delay="' + data.aos_delay + '" aos-duration="' + data.aos_duration + '" aos-easing="' + data.aos_easing + '" ' + (data.aos_once == 'false' ? '' : 'aos-once="true"');
	}

	return params;
}

function madara_shortcode_get_design_params(data) {

	var params = madara_shortcode_get_effect_params(data);

	if (data.container != 0) {
		params += ' container="1" ';
	}

	if (data.padding != '') {
		params += ' padding="' + data.padding + '" ';
	}

	if (data.margin != '') {
		params += ' margin="' + data.margin + '" ';
	}

	if (data.gutter != 0) {
		params += ' gutter="1" ';
	}

	if (data.parallax != 0) {
		params += ' parallax="1" ';
	}

	if (data.background_image != '') {
		params += ' background-image="' + data.background_image + '" ';
	}

	if (data.background_color != '') {
		params += ' background-color="' + data.background_color + '" ';
	}

	return params;
}

function madara_shortcode_get_extents_typo_params(data) {

	var params = '';

	var size = data.size;
	var weight = data.weight;
	var style = data.style;
	var color = data.color;
	var bg_color = data.bg_color;
	var padding = data.padding;
	var margin = data.margin;
	var alignment = data.alignment;
	var line_height = data.line_height;
	var letter_spacing = data.spacing;
	var font_family = data.font_family;
	var mobile_size = data.mobile_size;
	var mobile_line_height = data.mobile_line_height;
	var mobile_padding = data.mobile_padding;
	var mobile_margin = data.mobile_margin;
	var mobile_alignment = data.mobile_alignment;

	if (size != '') {
		params += ' size="' + size + '" ';
	}
	if (weight != '') {
		params += ' weight="' + weight + '" ';
	}
	if (style != '') {
		params += ' style="' + style + '" ';
	}
	if (color != '') {
		params += ' color="' + color + '" ';
	}
	if (bg_color != '') {
		params += ' bg_color="' + bg_color + '" ';
	}
	if (padding != '') {
		params += ' padding="' + padding + '" ';
	}
	if (margin != '') {
		params += ' margin="' + margin + '" ';
	}
	if (alignment != '') {
		params += ' alignment="' + alignment + '" ';
	}
	if (line_height != '') {
		params += ' line_height="' + line_height + '" ';
	}
	if (letter_spacing != '') {
		params += ' letter_spacing="' + letter_spacing + '" ';
	}
	if (font_family != '') {
		params += ' font_family="' + font_family + '" ';
	}
	if (mobile_size != '') {
		params += ' mobile_size="' + mobile_size + '" ';
	}
	if (mobile_line_height != '') {
		params += ' mobile_line_height="' + mobile_line_height + '" ';
	}
	if (mobile_padding != '') {
		params += ' mobile_padding="' + mobile_padding + '" ';
	}
	if (mobile_margin != '') {
		params += ' mobile_margin="' + mobile_margin + '" ';
	}
	if (mobile_alignment != '') {
		params += ' mobile_alignment="' + mobile_alignment + '" ';
	}

	params += madara_shortcode_get_effect_params(data);

	return params;
}

(function () {
	var body = [];


	for (var key in madara_SHORTCODES_LIST) {
		body.push({
			type: 'button',
			name: madara_SHORTCODES_LIST[key],
			text: madara_SHORTCODES_LIST[key],
			label: madara_SHORTCODES_LIST[key],
			id: 'madara_shortcode_' + key + '_opener'
		});
	}

	tinymce.PluginManager.add('madara_shortcode_list', function (editor, url) {
		editor.addButton('madara_shortcode_list', {
			text: '',
			tooltip: 'Shortcode',
			id: 'madara_shortcode_listshortcode',
			icon: 'icons',
			onclick: function () {
				// Open window
				editor.windowManager.open({
					title: 'Shortcode',
					body: body,
				});

				jQuery('.mce-colorbox').each(function () {
					var $j = jQuery;
					var $this = $j(this);
					$j(this).wpColorPicker({
						change: function (event, ui) {
							$j('.mce-textbox', $this.closest('.wp-picker-container')).css({'background-color': ui.color.toString()});
						}
					});
				});
			}
		});
	});

	jQuery(document).ready(function () {
		for (var key in madara_SHORTCODES_LIST) {
			jQuery(document).on('click', '#madara_shortcode_' + key + '_opener button', (function (key) {
				return function () {
					jQuery('.mce-foot button').trigger("click");
					jQuery('#madara_shortcode_' + key + ' button', jQuery(tinymce.activeEditor.container)).trigger("click");

					jQuery('.mce-colorbox').each(function () {
						var $j = jQuery;
						var $this = $j(this);
						$j(this).wpColorPicker({
							change: function (event, ui) {
								$j('.mce-textbox', $this.closest('.wp-picker-container')).css({'background-color': ui.color.toString()});
							}
						});
					});
				};
			})(key));
		}
	});

})();
