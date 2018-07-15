// JavaScript Document
(function () {
	tinymce.PluginManager.add('manga_chapter', function (editor, url) {
		editor.addButton('manga_chapter', {
			text: 'Manga Chapter',
			id: 'madara_shortcode_manga_chapter',
			tooltip: 'Mandara Manga Chapter',
			onclick: function () {
				// Open window
				var body = [

					{
						type: 'textbox',
						name: 'id',
						label: 'Manga ID',
						tooltip: 'Enter ID of manga to display'
					},

					{
						type: 'listbox',
						name: 'order',
						label: 'Chapter Oder',
						'values': [
							{text: 'Ascending', value: 'ASC'},
							{text: 'Descending', value: 'DESC'},
						],
					},

					{
						type: 'listbox',
						name: 'manga_details',
						label: 'Enable Manga Details',
						'values': [
							{text: 'Enable', value: '1'},
							{text: 'Disable', value: '0'},
						],
					},

					{
						type: 'listbox',
						name: 'manga_title',
						label: 'Enable Manga Title',
						'values': [
							{text: 'Enable', value: '1'},
							{text: 'Disable', value: '0'},
						],
					},

				];

				editor.windowManager.open({
					title: 'Madara Manga Chapter',
					body: body,
					onsubmit: function (e) {

						var id = e.data.id;
						if (id != '') {
							id = 'id="' + id + '"';
						}

						var order = e.data.order;
						if (order != '') {
							order = 'order="' + order + '"';
						}

						var manga_details = e.data.manga_details;
						if (manga_details != '') {
							manga_details = 'manga_details="' + manga_details + '"';
						}

						var manga_title = e.data.manga_title;
						if (manga_title != '') {
							manga_title = 'manga_title="' + manga_title + '"';
						}

						editor.insertContent('[manga_chapter ' + id + ' ' + order + ' ' + manga_details + ' ' + manga_title + ']');
					}
				});
			}
		});
	});
})();
