<div id="entryname" style="position:absolute; left:10px; top:5px; width:700px; min-height:20px;">
	<span class="entryname" id="header_name" style="color:#006600; font-size:19px;"></span>
</div>

<div id="preview" style="position:absolute; right:300px; top:5px; width:100px; height:63px; background:center no-repeat; background-size:contain;"></div>

<div id="entryinfo" style="position:absolute; right:10px; bottom:5px; width:280px; font-size:10px; color:#666666; text-align:right;"></div>

<script>
	$("#entryname").css("cursor", "pointer").click(function() {
		if ($(this).find('input').length) return;
		var span = $(this).find("span");
		var name = span.text();
		span.hide();
		$(this).append('<input type="text" class="newentryname" name="newentryname" value="'+name+'" style="width:690px; color:#006600; font-size:19px; border:none; padding:0; margin:0;" />');
		$(this).find(".newentryname").focus().blur(function() {
			var newname = $(this).val();
			$.post('ajax/connector.php?module=system&method=setname', { id: currentEntryId, name: newname });
			$(this).parent().find("span").show().text(newname);
			$(this).remove();
		}).keyup(function(e) {
			if (e.keyCode == 13) $(this).blur();
		});
	});

	var onCurrentEntryChangedHeader = function() {
		$("#header_name").text(typeof currentEntry["name"] === "undefined" ? "" : currentEntry["name"]);

		$("#preview").css("background-image", "url(ajax/preview.php?id="+currentEntryId+"&sx=200&sy=200)");

		$.get('ajax/connector.php?module=system&method=info&id='+currentEntryId, function(res) {
			var entryinfo = "";
			if (typeof res.comments !== "undefined") entryinfo += '<img title="Kommentare" border="0" src="plugin/Base3Manager/assets/img/head/comments.png" /> '+res.comments+'<br />';
			if (typeof res.created !== "undefined") entryinfo += '<img title="erstellt" border="0" src="plugin/Base3Manager/assets/img/head/add.png" /> '+convertDateSql2Human(res.created)+' - '+res.createdby+'<br />';
			if (typeof res.lastchanged !== "undefined") entryinfo += '<img title="ge&auml;ndert" border="0" src="plugin/Base3Manager/assets/img/head/page_white_edit.png" /> '+convertDateSql2Human(res.lastchanged)+' - '+res.lastchangedby+'<br />';
			$("#entryinfo").html(entryinfo);
		});
	}
</script>
