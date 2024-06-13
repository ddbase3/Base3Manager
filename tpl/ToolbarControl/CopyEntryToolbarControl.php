        <button class="view_mode entry_select entry_filled" id="copy_entry" title="Eintrag kopieren">
                <img border="0" src="plugin/Base3Manager/assets/img/icons/application_double.png" />
        </button>

	<script>
		$("#copy_entry").click(function() {
			var modaldialog = $('<div class="modaldialog" />').appendTo("body").dialog({
				width: 350,
				height: 200,
				title: "Eintrag kopieren",
				modal: true,
				open: function () {
					$(this).load("?name=copydialog&id=" + currentEntryId, function() {
						$(this).find('[name="newentryname"]').val(currentEntry["name"] + " (Kopie)");
					});
				},
				close: function () { $(".modaldialog").dialog("destroy").remove(); },
				buttons: {
					"Ok": function() {
						$.post("?name=connector&out=json&module=system&method=copy", {
							id: currentEntryId,
							name: $(".modaldialog").find('[name="newentryname"]').val(),
							allocs: $(".modaldialog").find('[name="allocs"]').attr('checked') ? 1 : 0
						}, function(res) {
							loadEntry(currentModule, "id", res["newentryid"]);
						});
						$(this).dialog("close");
					},
					"Schlie▒~_en": function() { $(this).dialog("close"); }
				}
			});
		});
	</script>
