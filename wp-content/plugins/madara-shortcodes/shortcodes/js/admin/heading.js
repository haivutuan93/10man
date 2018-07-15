// JavaScript Document
(function () {
	tinymce.PluginManager.add('madara_heading', function (editor, url) {
		editor.addButton('madara_heading', {
			text: '',
			id: 'madara_shortcode_heading',
			tooltip: 'MangaStream Heading',
			onclick: function () {
				// Open window
				var body = [

					{
						type: 'listbox',
						name: 'style',
						label: 'Heading Style',
						'values': [
							{text: 'Style 1', value: '1'},
							{text: 'Style 2', value: '2'},
						],
					},

					{type: 'textbox', name: 'content', label: 'Heading Content'},

					{type: 'textbox', name: 'icon', label: 'Icon CSS Class', tooltip: "Icon CSS Class. Ex: fa fa-star"},

					{
						type: 'textbox',
						classes: 'colorbox',
						placeholder: '#000000',
						name: 'icon_color',
						label: 'Icon Color',
						value: "",
						id: 'newcolorpicker_text_color'
					},

					{
						type: 'textbox',
						classes: 'colorbox',
						placeholder: '#000000',
						name: 'heading_bg',
						label: 'Heading Background',
						value: "",
						id: 'newcolorpicker_bg_color'
					},

					{
						type: 'textbox',
						name: 'margin',
						label: 'Margin',
						value: "",
						tooltip: 'Format: TOP RIGHT BOTTOM LEFT. ex: 0px 0px 0px 0px. Leave blank to use default style.'
					},

					{
						type: 'listbox',
						name: 'border',
						label: 'Border Bottom',
						'values': [
							{text: 'Enable', value: 1},
							{text: 'Disable', value: 0},
						],
						tooltip: 'For style 2 only.'
					},

				];

				editor.windowManager.open({
					title: 'MangaStream Heading',
					body: body,
					onsubmit: function (e) {

						var content = e.data.content;

						var style = e.data.style;
						if (style != '') {
							style = 'style="' + style + '"';
						}

						var icon = e.data.icon;
						if (icon != '') {
							icon = 'icon="' + icon + '"';
						}

						var icon_color = e.data.icon_color;
						if (icon_color != '') {
							icon_color = 'icon_color="' + icon_color + '"';
						}

						var margin = e.data.margin;
						if (margin != '') {
							margin = 'margin="' + margin + '"';
						}

						var border = e.data.border;
						if (border != '') {
							border = 'border="' + border + '"';
						}

						var heading_bg = e.data.heading_bg;
						if (heading_bg != '') {
							heading_bg = 'heading_bg="' + heading_bg + '"';
						}

						editor.insertContent('[manga_heading ' + style + ' ' + icon + ' ' + icon_color + ' ' + heading_bg + ' ' + margin + ' ' + border + ']' + content + '[/manga_heading]');
					}
				});
			}
		});
	});
})();
