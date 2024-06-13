        <button class="view_mode entry_select" id="filter_entry" title="Filter für Auflistungen und Suche">
                <img border="0" src="plugin/Base3Manager/assets/img/icons/filter.png" />
        </button>

	<script>
		$("#filter_entry").on('click', function() {
			var modaldialog = $('<div class="modaldialog" />').appendTo("body").dialog({
				width: 500,
				height: 300,
				title: "Filter",
				modal: true,
				open: function () { $(this).html('<p>Keine Filter verf&uuml;gbar.</p>').load("?name=filterdialog&module="+currentModule); },
				close: function () { $(".modaldialog").dialog("destroy").remove(); },
				buttons: {
					"Schlie▒~_en": function() { $(this).dialog("close"); }
				}
			});
		});
	</script>
