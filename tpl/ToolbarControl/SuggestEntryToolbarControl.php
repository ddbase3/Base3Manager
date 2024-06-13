        <input type="text" class="view_mode entry_select" id="suggest_entry" value="" title="Suchfeld" />

	<script>
		var icons = {
			'contact': 'person',
			'note': 'note',
			'folder': 'folder-collapsed',
			'file': 'document',
			'tag': 'tag',
			'code': 'calculator',
			'link': 'extlink',
			'text': 'script',
			'address': 'home',
			'date': 'clock',
			'product': 'lightbulb',
			'project': 'suitcase',
			'task': 'wrench',
			'account': 'key',
			'resource': 'bullet'
		};

		$("#suggest_entry").autocomplete({
			source: "?name=connector&out=json&module="+currentModule+"&method=suggest",
			minLength: 2,
			focus: function(event, ui) {
				$("#suggest_entry").val(ui.item.label);
				return false;
			},
			select: function(event, ui) {
				loadModule(ui.item.module, "id", ui.item.value);
				$("#suggest_entry").val(ui.item.label);
				return false;
			}
		})
		.autocomplete("instance")._renderItem = function(ul, item) {
			return $('<li>')
				.data("item.autocomplete", item)
				.append('<div><span class="ui-icon ui-icon-' + icons[item.type] + '"></span> ' + item.label + '</div>')
				.appendTo(ul);
		};
	</script>

