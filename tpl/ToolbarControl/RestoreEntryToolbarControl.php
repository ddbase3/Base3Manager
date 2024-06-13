        <button class="edit_mode edit_access entry_select entry_filled" id="restore_entry" title="&Auml;nderungen r&uuml;ckg&auml;ngig machen">
                <img border="0" src="plugin/Base3Manager/assets/img/icons/arrow_redo.png" />
        </button>

	<script>
		$("#restore_entry").click(function() {
			if (currentEntryId == 0)
				loadEntry(currentModule, "last");
			else
				loadEntry(currentModule, "id", currentEntryId);
			set_view_mode();
		});
	</script>
