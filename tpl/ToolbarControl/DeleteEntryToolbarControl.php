        <button class="view_mode edit_access entry_select entry_filled" id="delete_entry" title="Eintrag l&ouml;schen">
                <img border="0" src="plugin/Base3Manager/assets/img/icons/delete.png" />
        </button>

	<script>
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
						$.post("?name=connector&out=json&module=system&method=delete", { id: currentEntryId }, function() {
							loadEntry();
						});
						$(this).dialog("close");
					},
					"Abbrechen": function() { $(this).dialog("close"); }
				}
			});
		});
	</script>
