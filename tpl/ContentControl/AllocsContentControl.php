<fieldset style="width:600px; height:380px; float:left;">
	<legend>Verbundene Eintr&auml;ge</legend>
	<div name="tableallocs" style="width:600px; height:380px; overflow-x:hidden;"></div>
</fieldset>

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

	var reloadCurrentEntry = function() {
		loadEntry(currentModule, "id", currentEntryId);
	};

	var doAction = function(com, grid) {
		if (!$('.trSelected', grid).length && com != 'Hinzufügen' && com != 'Verbinden') {
			alert("Kein Eintrag ausgewählt");
			return;
		}
		if (com == 'Hinzufügen') {
			var modaldialog = $('<div class="modaldialog" />').appendTo("body").dialog({
				width: 500,
				height: 340,
				title: "Neuer Eintrag",
				modal: true,
				open: function () {
/*
									$(this).load("modules/" + currentModule + "/tabs/" + currentTab + "/create" + opt.subEntryName + ".dialog.php", function() {
										modaldialog.find("form").submit(function() { return false; });
									});
*/
				},
				close: function () { $(".modaldialog").dialog("destroy").remove(); },
				buttons: {
					"Ok": function() {
/*
										var data = $(this).find("form").serialize();
										$.post("ajax/connector.php?module=" + currentModule + "&method=" + opt.subEntryName + "items&id=" + currentEntryId, data, function(res) {
											$('table[name="' + opt.subEntryName + 's"]').flexReload();
										});
										$(this).dialog("close");
*/
					},
					"Schließen": function() { $(this).dialog("close"); }
				}
			});
		} else if (com == 'Verbinden') {
			var modaldialog = $('<div class="modaldialog" />').appendTo("body").dialog({
				width: 500,
				height: 340,
				title: "Einträge verbinden",
				modal: true,
				open: function () {
					var form = $('<form name="connect" />').appendTo(this).submit(function() { return false; });
					$('<p>Den aktuellen Eintrag verbinden mit</p>').appendTo(form);
					var selectentryid = $('<input type="hidden" name="selectentryid" value="" />').appendTo(form);
					var selectentry = $('<input type="text" name="selectentry" style="width:100%;" />').appendTo(form);
					selectentry.autocomplete({
						source: "ajax/connector.php?module=system&method=suggest",
						minLength: 2,
						focus: function(event, ui) {
							selectentry.val(ui.item.label);
							return false;
						},
						select: function(event, ui) {
							selectentryid.val(ui.item.value);
							selectentry.val(ui.item.label);
							return false;
						}
					})
					.autocomplete("instance")._renderItem = function(ul, item) {
						return $('<li>')
							.data("item.autocomplete", item)
							.append('<div><span class="ui-icon ui-icon-' + icons[item.type] + '"></span> ' + item.label + '</div>')
							.appendTo(ul);
					};
				},
				close: function () { $(".modaldialog").dialog("destroy").remove(); },
				buttons: {
					"Ok": function() {
						var id = parseInt($(this).find('input[name="selectentryid"]').val());
						$.post("ajax/connector.php?module=system&method=connect", { id1: currentEntryId, id2: id }, function() {
							$('table[name="allocs"]').flexReload();
							reloadCurrentEntry();
						});
						$(this).dialog("close");
					},
					"Schließen": function() { $(this).dialog("close"); }
				}
			});
		} else if (com == 'Bearbeiten') {
			var id = parseInt($('.trSelected', grid).first().attr("id").substr(3));
			var type = $('.trSelected', grid).first().find('td[abbr="type"]').text();
			loadType(type, 'id', id);
		} else if (com == 'Trennen') {
			var modaldialog = $('<div class="modaldialog" />').appendTo("body").dialog({
				width: 300,
				height: 200,
				title: "Eintrag trennen",
				modal: true,
				open: function () { $(this).html('<p>Soll die Einträge wirklich getrennt werden?</p>'); },
				close: function () { $(".modaldialog").dialog("destroy").remove(); },
				buttons: {
					"Ok": function() {
						var id = parseInt($('.trSelected', grid).first().attr("id").substr(3));
						$.post("ajax/connector.php?module=system&method=disconnect", { id1: currentEntryId, id2: id }, function() {
							$('table[name="allocs"]').flexReload();
							reloadCurrentEntry();
						});
						$(this).dialog("close");
					},
					"Abbrechen": function() { $(this).dialog("close"); }
				}
			});
		} else if (com == 'Löschen') {
			var modaldialog = $('<div class="modaldialog" />').appendTo("body").dialog({
				width: 300,
				height: 200,
				title: "Eintrag löschen",
				modal: true,
				open: function () { $(this).html('<p>Soll der Eintrag wirklich gel&ouml;scht werden?</p>'); },
				close: function () { $(".modaldialog").dialog("destroy").remove(); },
				buttons: {
					"Ok": function() {
						var id = parseInt($('.trSelected', grid).first().attr("id").substr(3));
						$.post("ajax/connector.php?module=system&method=delete", { id: id }, function() {
							$('table[name="allocs"]').flexReload();
							reloadCurrentEntry();
						});
						$(this).dialog("close");
					},
					"Abbrechen": function() { $(this).dialog("close"); }
				}
			});
		}
	}

	var onCurrentEntryChangedContent = function() {
		$('div[name="tableallocs"]').html('<table name="allocs" style="display:none;"></table>');
		$('table[name="allocs"]').flexigrid({
			url : 'ajax/connector.php?module=system&method=allocs&id=' + currentEntryId,
			dataType : 'json',
			colModel : [
				// { display: '', name: 'entryid', width: 20, process: procMe },
				{ display: '', name: 'entryid', width: 20, sortable: true, align: 'center' },
				{ display: 'Name', name: 'name', width: 400, sortable: true, align: 'left' },
				{ display: 'Typ', name: 'typename', width: 100, sortable: true, align: 'left' },
				{ display: 'Alias', name: 'type', width: 60, sortable: true, align: 'left', hide: true }
			],
			buttons : [
				{ name: 'Hinzufügen', bclass: 'add', onpress: doAction },
				{ name: 'Verbinden', bclass: 'connect', onpress: doAction },
				{ name: 'Bearbeiten', bclass: 'edit', onpress: doAction },
				{ name: 'Trennen', bclass: 'disconnect', onpress: doAction },
				{ name: 'Löschen', bclass: 'delete', onpress: doAction },
				{ separator: true } 
			],
			searchitems : [
				{ display: 'Name', name: 'name', isdefault: true }
			],
			sortname: "name",
			sortorder: "asc",
			pagestat: 'Einträge: {total} | Zeige {from} bis {to}',
			pagetext: 'Seite',
			outof: 'von',
			usepager: true,
			useRp: true,
			rp: 10,
			rpOptions: [10,15,20,40,60,80,100],
			width: "auto",
			height: 290,
			resizable: false,
			singleSelect: true,
			datacol: {
				'entryid': function(val) {
					return '<a class="tageditalloc" href="#" rel="' + val + '"><img src="modules/tag/tabs/allocs/edit.gif" border="0" /></a>';
				}
			},
			onSuccess: function(grid) {
				$(".tageditalloc").click(function() {
					var id = $(this).attr("rel");
					var type = $(this).parents("tr").first().children("td").last().text();
					loadType(type, 'id', id);
					return false;
				});
			}
		});


	}
</script>

<style>
	div[name="tableallocs"] div.fbutton .add { background: url(plugin/Base3Manager/assets/img/icons/add.png) no-repeat center left; }
	div[name="tableallocs"] div.fbutton .connect { background: url(plugin/Base3Manager/assets/img/icons/connect.png) no-repeat center left; }
	div[name="tableallocs"] div.fbutton .delete { background: url(plugin/Base3Manager/assets/img/icons/delete.png) no-repeat center left; }
	div[name="tableallocs"] div.fbutton .disconnect { background: url(plugin/Base3Manager/assets/img/icons/disconnect.png) no-repeat center left; }
	div[name="tableallocs"] div.fbutton .edit { background: url(plugin/Base3Manager/assets/img/icons/page_white_edit.png) no-repeat center left; }
</style>
