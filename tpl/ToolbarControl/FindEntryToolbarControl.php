        <button class="view_mode entry_select" id="find_entry" title="Eintrag suchen" data-control="<?php echo $this->_['module']['list']; ?>">
                <img border="0" src="plugin/Base3Manager/assets/img/icons/find.png" />
        </button>

	<script>
		$("#find_entry").on('click', function() {
			let control = $(this).attr('data-control');
			let url = '?name=' + control + '&alias=' + currentModule;
			$('<div class="dialog" />').appendTo("body").dialog({
				width: 800,
				height: 500,
				title: "Detailsuche",
				// open: function () { $(this).load("modules / "+currentModule+" / search / detail.php"); },
				open: function () { $(this).load(url); },
				close: function () { $(this).dialog("destroy").remove(); },
				buttons: {
					"Schliessen": function() { $(this).dialog("close"); }
				}
			});
		});
	</script>

