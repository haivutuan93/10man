// JavaScript Document
(function () {
	tinymce.PluginManager.add('madara_blog', function (editor, url) {
		editor.addButton('madara_blog', {
			text: '',
			tooltip: 'MangaStream Blog',
			id: 'madara_shortcode_blog',
			onclick: function () {
				// Open window
				var body = [

					{
						type: 'textbox',
						name: 'count',
						label: 'Number of posts to show',
						tooltip: 'Number of posts to show. Default value is 6'
					},

					{
						type: 'listbox',
						name: 'items_per_row',
						label: 'Items per row',
						'values': [
							{text: '3 items', value: 3},
							{text: '2 items', value: 2},

						],
						tooltip: 'Items per row'
					},

					{
						type: 'textbox',
						name: 'cats',
						label: 'Category',
						tooltip: 'List of category (slug), separated by a comma'
					},

					{type: 'textbox', name: 'tags', label: 'Tags', tooltip: 'List of tag (slug), separated by a comma'},

					{type: 'textbox', name: 'ids', label: 'Ids', tooltip: 'Specify post IDs to retrieve'},

					{
						type: 'listbox',
						name: 'order',
						label: 'Order',
						'values': [
							{text: 'DESC', value: 'DESC'},
							{text: 'ASC', value: 'ASC'}
						]
					},

					{
						type: 'listbox',
						name: 'orderby',
						label: 'Order by',
						'values': [
							{text: 'Date', value: 'date'},
							{text: 'ID', value: 'ID'},
							{text: 'Author', value: 'author'},
							{text: 'Title', value: 'title'},
							{text: 'Name', value: 'name'},
							{text: 'Modified', value: 'modified'},
							{text: 'Random', value: 'rand'},
							{text: 'Comment count', value: 'comment_count'},
							{text: 'Post__in', value: 'post__in'},
						]
					},

				];

				editor.windowManager.open({
					title: 'MangaStream Blog',
					body: body,
					onsubmit: function (e) {
						var count = e.data.count;
						if (count != '') {
							count = 'count="' + count + '"';
						}

						var items_per_row = e.data.items_per_row;
						if (items_per_row != '') {
							items_per_row = 'items_per_row="' + items_per_row + '"';
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

						var order = e.data.order;
						if (order != '') {
							order = 'order="' + order + '"';
						}

						var orderby = e.data.orderby;
						if (orderby != '') {
							orderby = 'orderby="' + orderby + '"';
						}

						editor.insertContent('[manga_blog ' + count + ' ' + items_per_row + ' ' + cats + ' ' + tags + ' ' + ids + ' ' + order + ' ' + orderby + ' ][/manga_blog]');
					}
				});
			}
		});
	});
})();
