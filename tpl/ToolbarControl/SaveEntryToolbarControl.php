        <button class="edit_mode edit_access entry_select entry_filled" id="save_entry" title="Daten speichern">
                <img border="0" src="plugin/Base3Manager/assets/img/icons/accept.png" />
        </button>

	<script>
		$("#save_entry").click(function() {
			if (currentEntryId == 0) return;
			var data = $("#content").serialize();
			data = "id=" + currentEntryId + "&" + data;
			$.post("?name=connector&out=json&module="+currentModule+"&method=save", data, function(res) {
				loadEntry(currentModule, "id", currentEntryId);
				// onCurrentEntryChangedHeader();
			});
			set_view_mode();
		});
	</script>
