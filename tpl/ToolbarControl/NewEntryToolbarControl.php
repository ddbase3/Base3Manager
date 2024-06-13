        <button class="view_mode entry_select entry_empty" id="new_entry" title="Neuer Eintrag">
                <img border="0" src="plugin/Base3Manager/assets/img/icons/add.png" />
        </button>

	<script>
		$("#new_entry").click(function() {
			var modaldialog = $('<div class="modaldialog" />').appendTo("body").dialog({
				width: <?php echo isset($this->_['module']['create']) && isset($this->_['module']['create']['width']) ? $this->_['module']['create']['width'] : 800; ?>,
				height: <?php echo isset($this->_['module']['create']) && isset($this->_['module']['create']['height']) ? $this->_['module']['create']['height'] : 500; ?>,
				title: "Neuer Eintrag",
				modal: true,
				open: function () {
					$(this).load("?name=createdialog&module="+currentModule, function() {
						modaldialog.find("form").submit(function() { return false; });
					});
				},
				close: function () { $(".modaldialog").dialog("destroy").remove(); },
				buttons: {
					"Ok": function() {
						var data = $(this).find("form").serialize();
						$.post("?name=connector&out=json&module="+currentModule+"&method=create", data, function(res) {
							loadEntry(currentModule, "id", res["id"]);
						});
						$(this).dialog("close");
					},
					"Schlie▒~_en": function() { $(this).dialog("close"); }
				}
			});
		});
	</script>
