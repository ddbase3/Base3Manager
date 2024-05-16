<p>
	ID: <span id="logs_id"></span><br />
	UUID: <span id="logs_uuid"></span><br />
</p>

<table id="log" class="datatable" border="0">
	<colgroup>
		<col width="100" />
		<col width="140" />
		<col width="300" />
	</colgroup>
	<tr>
		<th>Aktion</th>
		<th>Datum/Zeit</th>
		<th>Benutzer</th>
	</tr>
</table>

<script>
	var url = "?name=connector&out=json&module=system&method=log&id=<?php echo $this->_['id']; ?>";
	$.getJSON(url, function(result) {
		$('#logs_id').text(result.id);
		$('#logs_uuid').text(result.uuid);
		for (key in result.logs) {
			var log = result.logs[key];
			$("#log").append('<tr><td>'+log["action"]+'</td><td>'+log["datetime"]+'</td><td>'+log["fullname"]+'</td></tr>');
		}
	});	
</script>
