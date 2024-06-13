        <button class="view_mode" id="reload_entry" title="Eintrag neu laden">
                <img border="0" src="plugin/Base3Manager/assets/img/icons/arrow_refresh.png" />
        </button>

	<script>
		$("#reload_entry").click(function() {
			loadEntry(currentModule, "id", currentEntryId);
		});
	</script>

