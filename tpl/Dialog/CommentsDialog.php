<div id="comments"><img border="0" src="img/loading.gif" /></div>

<form id="newcomment">
	<p>Neuer Kommentar</p>
	<textarea name="comment" style="width:100%; height:50px;"></textarea>
	<input type="submit" name="submit" value="Senden" />
</form>

<script>
	var url = "ajax/connector.php?module=system&method=comments&id=<?php echo $this->_['id']; ?>";
	var printComments = function(result) {
		$("#comments").html("");
		for (key in result) {
			var comment = result[key];
			var divcomment = $('<div class="comment"></div>');
			divcomment.append('<p class="commentname">'+comment["fullname"]+'</p>');
			divcomment.append('<p class="commentdate">'+comment["datetime"]+'</p>');
			divcomment.append('<p class="commenttext">'+comment["comment"]+'</p>');
			$("#comments").append(divcomment);
		}
	}
	$.get(url, function(result) { printComments(result); });
	$("#newcomment").submit(function() {
		$("#comments").append('<img border="0" src="img/loading.gif" />');
		$.post("ajax/connector.php?module=system&method=comments", {
			id: <?php echo $this->_['id']; ?>,
			comment: $("#newcomment textarea").val()
		}, function(result) {
			$("#newcomment textarea").val("");
			printComments(result);
		});
		return false;
	});
</script>
