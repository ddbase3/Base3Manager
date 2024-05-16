<p>
	Datensatz archivieren
</p>

<form id="setarchiveform">
	<div id="setarchive">
		<img border="0" src="plugin/Base3Manager/assets/img/loading.gif" />
	</div>
</form>


<script>
	var url = "?name=connector&out=json&module=system&method=archived&id=<?php echo $this->_['id']; ?>";
	$.get(url, function(result) {
		var archived = parseInt(result.archived);
		var frm = $("#setarchive");
		frm.text('');
		var arch0 = $('<input type="radio" id="setarchive0" name="setarchive" value="0" />').appendTo(frm);
		$('<label for="setarchive0">Offen</label>').appendTo(frm);
		var arch1 = $('<input type="radio" id="setarchive1" name="setarchive" value="1" />').appendTo(frm);
		$('<label for="setarchive1">Archiv</label>').appendTo(frm);
		$('#setarchive'+archived).attr("checked", "checked");
		$("#setarchive").buttonset();
		$('input[name="setarchive"]').change(function() {
			$.post("?name=connector&out=json&module=system&method=setarchive", {
				id: <?php echo $this->_['id']; ?>,
				archive: $(this).val()
			});
		});
	});
</script>
