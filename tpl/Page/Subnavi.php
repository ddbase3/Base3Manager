<ul id="modulesubnavi">
<?php foreach ($this->_['subnavi'] as $button) { /* modules / <?php echo $alias; ?> / subnavi / <?php echo $buttonalias; ?> / dialog.php */ ?>
	<li>
		<a href="content.php?alias=<?php echo $this->_['alias']; ?>&subnavialias=<?php echo $button['subnavi']; ?>" rev="<?php echo $button['dialog']['width']."x".$button['dialog']['height']; ?>" title="<?php echo htmlentities($button['name']); ?>">
			<?php echo htmlentities($button['name']); ?>
		</a>
	</li>
<?php } ?>
</ul>

<div class="control">

<?php if ($this->_['manager']['controlnewentry']) { ?>
	<button class="view_mode entry_select entry_empty" id="new_entry" title="Neuer Eintrag">
		<img border="0" src="plugin/Base3Manager/assets/img/icons/add.png" />
	</button>
<?php } ?>
<?php if ($this->_['manager']['controlcopyentry']) { ?>
	<button class="view_mode entry_select entry_filled" id="copy_entry" title="Eintrag kopieren">
		<img border="0" src="plugin/Base3Manager/assets/img/icons/application_double.png" />
	</button>
<?php } ?>
<?php if ($this->_['manager']['controlreloadentry']) { ?>
	<button class="view_mode" id="reload_entry" title="Eintrag neu laden">
		<img border="0" src="plugin/Base3Manager/assets/img/icons/arrow_refresh.png" />
	</button>
<?php } ?>
<?php if ($this->_['manager']['controleditentry']) { ?>
	<button class="view_mode edit_access entry_select entry_filled" id="edit_entry" title="Eintrag bearbeiten">
		<img border="0" src="plugin/Base3Manager/assets/img/icons/page_white_edit.png" />
	</button>
<?php } ?>
<?php if ($this->_['manager']['controlsaveentry']) { ?>
	<button class="edit_mode edit_access entry_select entry_filled" id="save_entry" title="Daten speichern">
		<img border="0" src="plugin/Base3Manager/assets/img/icons/accept.png" />
	</button>
<?php } ?>
<?php if ($this->_['manager']['controlrestoreentry']) { ?>
	<button class="edit_mode edit_access entry_select entry_filled" id="restore_entry" title="&Auml;nderungen r&uuml;ckg&auml;ngig machen">
		<img border="0" src="plugin/Base3Manager/assets/img/icons/arrow_redo.png" />
	</button>
<?php } ?>
<?php if ($this->_['manager']['controldeleteentry']) { ?>
	<button class="view_mode edit_access entry_select entry_filled" id="delete_entry" title="Eintrag l&ouml;schen">
		<img border="0" src="plugin/Base3Manager/assets/img/icons/delete.png" />
	</button>
<?php } ?>
<?php if ($this->_['manager']['controlarchiveentry']) { ?>
	<button class="view_mode edit_access entry_select entry_filled" id="archive_entry" title="Archivieren">
		<img border="0" src="plugin/Base3Manager/assets/img/icons/package.png" />
	</button>
<?php } ?>
<?php if ($this->_['manager']['controlaccessentry']) { ?>
	<button class="view_mode edit_access entry_select entry_filled" id="access_entry" title="Zugriffssteuerung">
		<img border="0" src="plugin/Base3Manager/assets/img/icons/lock.png" />
	</button>
<?php } ?>
<?php if ($this->_['manager']['controlrateentry']) { ?>
	<button class="view_mode entry_select entry_filled" id="rate_entry" title="Eintrag bewerten">
		<img border="0" src="plugin/Base3Manager/assets/img/icons/star.png" />
	</button>
<?php } ?>
<?php if ($this->_['manager']['controlcommententry']) { ?>
	<button class="view_mode entry_select entry_filled" id="comment_entry" title="Kommentare anzeigen">
		<img border="0" src="plugin/Base3Manager/assets/img/icons/comments.png" />
	</button>
<?php } ?>
<?php if ($this->_['manager']['controllogentry']) { ?>
	<button class="view_mode entry_select entry_filled" id="log_entry" title="Log anzeigen">
		<img border="0" src="plugin/Base3Manager/assets/img/icons/book.png" />
	</button>
<?php } ?>
<?php if ($this->_['manager']['controlcloudentry']) { ?>
	<button class="view_mode entry_select entry_filled" id="cloud_entry" title="Eintrag mit anderem Tool &ouml;ffnen">
		<img border="0" src="plugin/Base3Manager/assets/img/icons/weather_clouds.png" />
	</button>
<?php } ?>
<?php if ($this->_['manager']['controlshareentry']) { ?>
	<button class="view_mode entry_select entry_filled" id="share_entry" title="Eintrag teilen">
		<img border="0" src="plugin/Base3Manager/assets/img/icons/share.png" />
	</button>
<?php } ?>
<?php if ($this->_['manager']['controlfindentry']) { ?>
	<button class="view_mode entry_select" id="find_entry" title="Eintrag suchen" data-control="<?php echo $this->_['module']['list']; ?>">
		<img border="0" src="plugin/Base3Manager/assets/img/icons/find.png" />
	</button>
<?php } ?>
<?php if ($this->_['manager']['controlsuggestentry']) { ?>
	<input type="text" class="view_mode entry_select" id="suggest_entry" value="" title="Suchfeld" />
<?php } ?>
<?php if ($this->_['manager']['controlgotolastentry']) { ?>
	<button class="view_mode entry_select" id="goto_last_entry" title="Zum letzten Eintrag">
		<img border="0" src="plugin/Base3Manager/assets/img/icons/resultset_first.png" />
	</button>
<?php } ?>
<?php if ($this->_['manager']['controlgotonextentry']) { ?>
	<button class="view_mode entry_select" id="goto_next_entry" title="Zum n&auml;chsten Eintrag">
		<img border="0" src="plugin/Base3Manager/assets/img/icons/resultset_previous.png" />
	</button>
<?php } ?>
<?php if ($this->_['manager']['controlgotopreventry']) { ?>
	<button class="view_mode entry_select" id="goto_prev_entry" title="Zum vorherigen Eintrag">
		<img border="0" src="plugin/Base3Manager/assets/img/icons/resultset_next.png" />
	</button>
<?php } ?>
<?php if ($this->_['manager']['controlgotofirstentry']) { ?>
	<button class="view_mode entry_select" id="goto_first_entry" title="Zum ersten Eintrag">
		<img border="0" src="plugin/Base3Manager/assets/img/icons/resultset_last.png" />
	</button>
<?php } ?>
<?php if ($this->_['manager']['controlfilterentry']) { ?>
	<button class="view_mode entry_select" id="filter_entry" title="Filter für Auflistungen und Suche">
		<img border="0" src="plugin/Base3Manager/assets/img/icons/filter.png" />
	</button>
<?php } ?>
</div>

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

	isSingleEntryModule = <?php echo $this->_['module']['single'] ? "true" : "false"; ?>;

	$("#goto_last_entry").click(function() { loadEntry(currentModule, "last"); });
	$("#goto_next_entry").click(function() { loadEntry(currentModule, "next"); });
	$("#goto_prev_entry").click(function() { loadEntry(currentModule, "prev"); });
	$("#goto_first_entry").click(function() { loadEntry(currentModule, "first"); });

	$("#archive_entry").click(function() {
		var modaldialog = $('<div class="modaldialog" />').appendTo("body").dialog({
			width: 250,
			height: 200,
			title: "Archiv",
			modal: true,
			open: function () { $(this).load("archivedialog.php?id=" + currentEntryId); },
			close: function () { $(".modaldialog").dialog("destroy").remove(); },
			buttons: {
				"Schließen": function() { $(this).dialog("close"); }
			}
		});
	});

	$("#cloud_entry").click(function() {
		var modaldialog = $('<div class="modaldialog" />').appendTo("body").dialog({
			width: 250,
			height: 410,
			title: "BASE3 System",
			modal: true,
			open: function () { $(this).load("clouddialog.php?id=" + currentEntryId); },
			close: function () { $(".modaldialog").dialog("destroy").remove(); },
			buttons: {
				"Schließen": function() { $(this).dialog("close"); }
			}
		});
	});

	$("#share_entry").click(function() {
		var modaldialog = $('<div class="modaldialog" />').appendTo("body").dialog({
			width: 400,
			height: 300,
			title: "Eintrag teilen",
			modal: true,
			open: function () { $(this).load("sharedialog.php?id=" + currentEntryId); },
			close: function () { $(".modaldialog").dialog("destroy").remove(); },
			buttons: {
				"Schließen": function() { $(this).dialog("close"); }
			}
		});
	});

	$("#filter_entry").on('click', function() {
		var modaldialog = $('<div class="modaldialog" />').appendTo("body").dialog({
			width: 500,
			height: 300,
			title: "Filter",
			modal: true,
			open: function () { $(this).html('<p>Keine Filter verf&uuml;gbar.</p>').load("filterdialog.php?module="+currentModule); },
			close: function () { $(".modaldialog").dialog("destroy").remove(); },
			buttons: {
				"Schließen": function() { $(this).dialog("close"); }
			}
		});
	});

	$("#log_entry").on('click', function() {
		var modaldialog = $('<div class="modaldialog" />').appendTo("body").dialog({
			width: 570,
			height: 500,
			title: "Log Eintrag #" + currentEntryId,
			modal: true,
			open: function () { $(this).load("logdialog.php?id=" + currentEntryId); },
			close: function () { $(".modaldialog").dialog("destroy").remove(); },
			buttons: {
				"Schließen": function() { $(this).dialog("close"); }
			}
		});
	});

	$("#comment_entry").click(function() {
		var modaldialog = $('<div class="modaldialog" />').appendTo("body").dialog({
			width: 600,
			height: 500,
			title: "Kommentare",
			modal: true,
			// open: function () { $(this).load("ajax/comments.dialog.php?id=" + currentEntryId); },
			open: function () { $(this).load("commentsdialog.php?id=" + currentEntryId); },
			close: function () { $(".modaldialog").dialog("destroy").remove(); },
			buttons: {
				"Schließen": function() { $(this).dialog("close"); }
			}
		});
	});

	$("#access_entry").click(function() {
		var modaldialog = $('<div class="modaldialog" />').appendTo("body").dialog({
			width: 800,
			height: 500,
			title: "Zugriff",
			modal: true,
			open: function () { $(this).load("accessdialog.php?id=" + currentEntryId); },
			close: function () { $(".modaldialog").dialog("destroy").remove(); },
			buttons: {
				"Schließen": function() { $(this).dialog("close"); }
			}
		});
	});

	$("#rate_entry").click(function() {
		var modaldialog = $('<div class="modaldialog" />').appendTo("body").dialog({
			width: 400,
			height: 300,
			title: "Bewertung",
			modal: true,
			open: function () { $(this).load("ratedialog.php?id=" + currentEntryId); },
			close: function () { $(".modaldialog").dialog("destroy").remove(); },
			buttons: {
				"Schließen": function() { $(this).dialog("close"); }
			}
		});
	});

	$("#find_entry").on('click', function() {
		let control = $(this).attr('data-control');
		let url = control + '.php?alias=' + currentModule;
		$('<div class="dialog" />').appendTo("body").dialog({
			width: 800,
			height: 500,
			title: "Detailsuche",
			// open: function () { $(this).load("modules / "+currentModule+" / search / detail.php"); },
			open: function () { $(this).load(url); },
			close: function () { $(this).dialog("destroy").remove(); },
			buttons: {
				"Schließen": function() { $(this).dialog("close"); }
			}
		});
	});

	$("#suggest_entry").autocomplete({
		source: "ajax/connector.php?module="+currentModule+"&method=suggest",
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

	$("#copy_entry").click(function() {
		var modaldialog = $('<div class="modaldialog" />').appendTo("body").dialog({
			width: 350,
			height: 200,
			title: "Eintrag kopieren",
			modal: true,
			open: function () {
				$(this).load("copydialog.php?id=" + currentEntryId, function() {
					$(this).find('[name="newentryname"]').val(currentEntry["name"] + " (Kopie)");
				});
			},
			close: function () { $(".modaldialog").dialog("destroy").remove(); },
			buttons: {
				"Ok": function() {
					$.post("ajax/connector.php?module=system&method=copy", {
						id: currentEntryId,
						name: $(".modaldialog").find('[name="newentryname"]').val(),
						allocs: $(".modaldialog").find('[name="allocs"]').attr('checked') ? 1 : 0
					}, function(res) {
						loadEntry(currentModule, "id", res["newentryid"]);
					});
					$(this).dialog("close");
				},
				"Schließen": function() { $(this).dialog("close"); }
			}
		});
	});

	<?php $createDialogSize = isset($this->_['module']['create']) ? $this->_['module']['create'] : null; ?>
	$("#new_entry").click(function() {
		var modaldialog = $('<div class="modaldialog" />').appendTo("body").dialog({
			width: <?php echo $createDialogSize == null ? 800 : $createDialogSize["width"]; ?>,
			height: <?php echo $createDialogSize == null ? 500 : $createDialogSize["height"]; ?>,
			title: "Neuer Eintrag",
			modal: true,
			open: function () {
				// $(this).load("modules / "+currentModule+" / create / create.php", function() {
				$(this).load("createdialog.php?module="+currentModule, function() {
					modaldialog.find("form").submit(function() { return false; });
				});
			},
			close: function () { $(".modaldialog").dialog("destroy").remove(); },
			buttons: {
				"Ok": function() {
					var data = $(this).find("form").serialize();
					$.post("ajax/connector.php?module="+currentModule+"&method=create", data, function(res) {
						loadEntry(currentModule, "id", res["id"]);
					});
					$(this).dialog("close");
				},
				"Schließen": function() { $(this).dialog("close"); }
			}
		});
	});

	$("#edit_entry").click(function() {
		set_edit_mode();
	});

	$("#save_entry").click(function() {
		if (currentEntryId == 0) return;
		var data = $("#content").serialize();
		data = "id=" + currentEntryId + "&" + data;
		$.post("ajax/connector.php?module="+currentModule+"&method=save", data, function(res) {
			loadEntry(currentModule, "id", currentEntryId);
			// onCurrentEntryChangedHeader();
		});
		set_view_mode();
	});

	$("#reload_entry").click(function() {
		loadEntry(currentModule, "id", currentEntryId);
	});

	$("#restore_entry").click(function() {
		if (currentEntryId == 0)
			loadEntry(currentModule, "last");
		else
			loadEntry(currentModule, "id", currentEntryId);
		set_view_mode();
	});

	$("#delete_entry").click(function() {
		var modaldialog = $('<div class="modaldialog" />').appendTo("body").dialog({
			width: 300,
			height: 200,
			title: "Eintrag löschen",
			modal: true,
			open: function () { $(this).html('<p>Soll der Eintrag wirklich gel&ouml;scht werden?</p>'); },
			close: function () { $(".modaldialog").dialog("destroy").remove(); },
			buttons: {
				"Ok": function() {
					$.post("ajax/connector.php?module=system&method=delete", { id: currentEntryId }, function() {
						loadEntry();
					});
					$(this).dialog("close");
				},
				"Abbrechen": function() { $(this).dialog("close"); }
			}
		});
	});

</script>

