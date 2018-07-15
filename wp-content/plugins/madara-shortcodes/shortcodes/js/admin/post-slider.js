// JavaScript Document
(function () {
	tinymce.PluginManager.add('madara_post_slider', function (editor, url) {
		editor.addButton('madara_post_slider', {
			text: '',
			id: 'madara_shortcode_post_slider',
			tooltip: 'MangaStream Post Slider',
			onclick: function () {
				// Open window
				var body = [
					{
						type: 'listbox',
						name: 'style',
						label: 'Slide Style',
						'values': [
							{text: 'Style 1', value: '1'},
							{text: 'Style 2', value: '2'},
							{text: 'Style 3', value: '3'},
							{text: 'Style 4', value: '4'},
						],
					},

					{type: 'textbox', name: 'count', label: 'Count', tooltip: 'number of items to query'},

					{
						type: 'listbox',
						name: 'number',
						label: 'Number',
						'values': [
							{text: '2 items', value: 2},
							{text: '3 items', value: 3},
							{text: '4 items', value: 4},
							{text: '1 item', value: 1},

						],
						tooltip: 'Number of Post to show'
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
						name: 'time',
						label: 'Time Range to query posts',
						'values': [
							{text: 'All Time', value: 'all'},
							{text: '1 Day', value: 'day'},
							{text: '1 Week', value: 'week'},
							{text: '1 Month', value: 'month'},
							{text: '1 Year', value: 'year'},
						],
						tooltip: 'Time Range to query posts'
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
						name: 'cats',
						label: 'Categories',
						tooltip: 'list of categories (ID) to query items from, separated by a comma'
					},

					{
						type: 'textbox',
						name: 'tags',
						label: 'Tags',
						tooltip: 'list of tags slug to query items from, separated by a comma. For example: tag-1, tag-2, tag-3'
					},

					{
						type: 'textbox',
						name: 'ids',
						label: 'IDs',
						tooltip: 'list of post IDs to query, separated by a comma. If this value is not empty, cats, tags and featured are omitted'
					},

				];

				editor.windowManager.open({
					title: 'MangaStream Post Slider',
					body: body,
					onsubmit: function (e) {

						var style = e.data.style;
						if (style != '') {
							style = 'style="' + style + '"';
						}

						var count = e.data.count;
						if (count != '') {
							count = 'count="' + count + '"';
						}

						var number = e.data.number;
						if (number != '') {
							number = 'number="' + number + '"';
						}

						var orderby = e.data.orderby;
						if (orderby != '') {
							orderby = 'orderby="' + orderby + '"';
						}

						var time = e.data.time;
						if (time != '') {
							time = 'time="' + time + '"';
						}

						var order = e.data.order;
						if (order != '') {
							order = 'order="' + order + '"';
						}

						var cats = e.data.cats;
						if (cats != '') {
							cats = 'cats="' + cats + '"';
						}

						var tags = e.data.tags;
						if (tags != '') {
							tags = 'tags="' + tags + '"';
						}

						var ids = e.data.ids;
						if (ids != '') {
							ids = 'ids="' + ids + '"';
						}

						editor.insertContent('[manga_post_slider ' + style + ' ' + count + ' ' + number + ' ' + orderby + ' ' + time + ' ' + order + ' ' + cats + ' ' + tags + ' ' + ids + ']');
					}
				});
			}
		});
	});
})();
