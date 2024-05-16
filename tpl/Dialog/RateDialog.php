<?php
	if (!$this->_['id']) {
		echo "Kein Eintrag ausgew&auml;hlt.";
		exit();
	}
?>
<p>Datensatz bewerten</p>
<div id="rateform" style="height:22px;">
	<select name="rating" class="rating" style="display:none;">
		<option></option>
		<option value="1">ungen&uuml;gend</option>
		<option value="2">mangelhaft</option>
		<option value="3">ausreichend</option>
		<option value="4">befriedigend</option>
		<option value="5">gut</option>
		<option value="6">sehr gut</option>
	</select>
</div>
<div id="rate">
	<img border="0" src="plugin/Base3Manager/assets/img/loading.gif" />
</div>


<script>
	var url = "?name=connector&out=json&module=system&method=rating&id=<?php echo $this->_['id']; ?>";
	var selectrating = $('select[name="rating"]');

	selectrating.change(function() {
		var rating = $(this).val();
		$.post("?name=connector&out=json&module=system&method=setrating", {
			id: <?php echo $this->_['id']; ?>,
			rating: rating ? 7 - rating : 0  // 0: rating aufheben, sonst 1-6
		}, function() {
			$.get(url, function(result) {
				var num_of_ratings = parseInt(result.num_of_ratings);
				var average_rating = parseInt(result.average_rating);
				showRatingDesc(num_of_ratings, average_rating);
			});
		});
	});

	var showRatingDesc = function(num_of_ratings, average_rating) {
		$("#rate").html(num_of_ratings
			? '(' + num_of_ratings + ' Bewertung' + ( num_of_ratings == 1 ? '' : 'en' ) + ' / Durchschnitt ' + average_rating + ')'
			: '(Bisher keine Bewertungen)'
		);
	}

	$.get(url, function(result) {
		var rating = parseInt(result.rating);
		var num_of_ratings = parseInt(result.num_of_ratings);
		var average_rating = parseInt(result.average_rating);

		$("#rateform .rating").rating({
			startValue: null,
			cancelTitle: "Keine Auswahl"
		});
		var noOp = function() { return false; };
		selectrating.click(noOp);
		selectrating.next('div').find('a:nth-child(' + ( rating ? 7 - rating : 7 ) + ')').click();  // 7: Button rating aufheben
		selectrating.unbind("click", noOp);

		showRatingDesc(num_of_ratings, average_rating);
	});
</script>
