// JavaScript Document
(function () {
	tinymce.PluginManager.add('manga_listing', function (editor, url) {
		editor.addButton('manga_listing', {
			text: 'Manga Listing',
			id: 'madara_shortcode_manga_listing',
			tooltip: 'Mandara Manga Listing',
			onclick: function () {
				// Open window
				var body = [
					{type: 'textbox', name: 'heading', label: 'Heading', tooltip: 'Title for Manga Listing section'},

					{
						type: 'textbox',
						name: 'heading_icon',
						label: 'Heading Icon',
						tooltip: 'Icon on Heading for Manga Listing section'
					},

					{
						type: 'listbox',
						name: 'chapter_type',
						label: 'Manga Chapter Type',
						'values': [
							{text: 'All', value: 'all'},
							{text: 'Image', value: 'manga'},
							{text: 'Text', value: 'text'},
							{text: 'Video', value: 'video'},
						],
					},

					{type: 'textbox', name: 'count', label: 'Count', tooltip: 'number of items to query'},

					{
						type: 'listbox',
						name: 'items_per_row',
						label: 'Items Per Row',
						'values': [
							{text: '2 items per row', value: 2},
							{text: '3 items per row', value: 3},

						],
						tooltip: 'Type of Manga Chapter to query'
					},

					{
						type: 'listbox',
						name: 'orderby',
						label: 'Oder By',
						'values': [
							{text: 'Latest', value: 'latest'},
							{text: 'Most viewed', value: 'view'},
							{text: 'Most commented', value: 'comment'},
							{text: 'Title', value: 'title'},
							{text: 'Input(only available when using ids parameter)', value: 'input'},
							{text: 'Random', value: 'random'},
						],
						tooltip: 'condition to query items'
					},

					{
						type: 'listbox',
						name: 'order',
						label: 'Oder',
						'values': [
							{text: 'Descending', value: 'DESC'},
							{text: 'Ascending', value: 'ASC'},
						],
					},

					{
						type: 'textbox',
						name: 'ids',
						label: 'IDs',
						tooltip: 'list of post IDs to query, separated by a comma. If this value is not empty, cats, tags and featured are omitted'
					},

				];

				editor.windowManager.open({
					title: 'Madara Manga Listing',
					body: body,
					onsubmit: function (e) {

						var heading = e.data.heading;
						if (heading != '') {
							heading = 'heading="' + heading + '"';
						}

						var heading_icon = e.data.heading_icon;
						if (heading_icon != '') {
							heading_icon = 'heading_icon="' + heading_icon + '"';
						}

						var chapter_type = e.data.chapter_type;
						if (chapter_type != '') {
							chapter_type = 'chapter_type="' + chapter_type + '"';
						}

						var count = e.data.count;
						if (count != '') {
							count = 'count="' + count + '"';
						}

						var items_per_row = e.data.items_per_row;
						if (items_per_row != '') {
							items_per_row = 'items_per_row="' + items_per_row + '"';
						}

						var orderby = e.data.orderby;
						if (orderby != '') {
							orderby = 'orderby="' + orderby + '"';
						}


						var order = e.data.order;
						if (order != '') {
							order = 'order="' + order + '"';
						}


						var ids = e.data.ids;
						if (ids != '') {
							ids = 'ids="' + ids + '"';
						}

						editor.insertContent('[manga_listing ' + heading + ' ' + heading_icon + ' ' + chapter_type + ' ' + count + ' ' + items_per_row + ' ' + orderby + ' ' + order + ' ' + ids + ']');
					}
				});
			}
		});
	});
})();
