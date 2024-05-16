<div class="sharedialog">
	<div class="step1">
		<ul>
			<li><a href="#" rel="clipboard"><img src="plugin/Base3Manager/assets/img/icons64/clipboard.png" /><span>Zwischenablage</span></a></li>
			<li><a href="#" rel="contact"><img src="plugin/Base3Manager/assets/img/icons64/contact.png" /><span>Kontakt</span></a></li>
			<li><a href="#" rel="user"><img src="plugin/Base3Manager/assets/img/icons64/user.png" /><span>Benutzer</span></a></li>
		</ul>
	</div>

	<div class="step2 clipboard">
		<p>URL zum Kopieren:</p>
		<input type="text" name="link" value="" style="width:100%;" />
	</div>

	<div class="step2 contact">
	</div>

	<div class="step2 user">
	</div>
</div>

<script>

	$(".sharedialog .step1 ul li a").click(function() {

		var rel = $(this).attr("rel");

		$(".sharedialog .step1").hide();

		var url = "?name=connector&out=json&module=system&method=uuid&id=<?php echo $this->_['id']; ?>";
		$.get(url, function(result) {
			var link = "http://www.base3.de/base3/viewer/?uuid=" + result.uuid;

			$(".sharedialog .step2." + rel).show();

			if (rel == "clipboard") {
				$(".sharedialog .step2.clipboard input").val(link);
				copyToClipboard(link);
			}

			if (rel == "contact") {
			}

			if (rel == "user") {
			}

		});

		return false;
	});
</script>

<style>
	.sharedialog ul { margin:0; padding:0; list-style:none; }
	.sharedialog ul li { float:left; width:auto; margin:0; padding:0; }
	.sharedialog ul li a { display:block; width:100px; margin:0; padding:0; text-align:center; text-decoration:none; }
	.sharedialog ul li a img { display:block; margin:0 auto; }
	.sharedialog ul li a span { font-size:10px; }
	.sharedialog .step2 { display:none; }
</style>
