<p>Diesen Ordner &ouml;ffnen mit:</p>

<a class="openapp" href="<?php echo $this->_['url']; ?>dashboard/?folder_id=<?php echo $this->_['id']; ?>" target="_blank" id="openapp_dashboard" style="display:block; margin:5px 0; padding:2px; border:1px outset #cccccc; -webkit-border-radius:5px; -khtml-border-radius:5px; -moz-border-radius:5px; border-radius:5px; background:#eeeeee; text-decoration:none; text-align:center; font-weight:bold;">
	Dashboard
</a>

<a class="openapp" href="<?php echo $this->_['url']; ?>gallery/galleria/?folder_id=<?php echo $this->_['id']; ?>" target="_blank" id="openapp_gallery" style="display:block; margin:5px 0; padding:2px; border:1px outset #cccccc; -webkit-border-radius:5px; -khtml-border-radius:5px; -moz-border-radius:5px; border-radius:5px; background:#eeeeee; text-decoration:none; text-align:center; font-weight:bold;">
	Gallery
</a>

<a class="openapp" href="<?php echo $this->_['url']; ?>maps/?folder_id=<?php echo $this->_['id']; ?>" target="_blank" id="openapp_map" style="display:block; margin:5px 0; padding:2px; border:1px outset #cccccc; -webkit-border-radius:5px; -khtml-border-radius:5px; -moz-border-radius:5px; border-radius:5px; background:#eeeeee; text-decoration:none; text-align:center; font-weight:bold;">
	Karte
</a>

<a class="openapp" href="<?php echo $this->_['url']; ?>desktop/?entry_id=<?php echo $this->_['id']; ?>" target="_blank" id="openapp_desktop" style="display:block; margin:5px 0; padding:2px; border:1px outset #cccccc; -webkit-border-radius:5px; -khtml-border-radius:5px; -moz-border-radius:5px; border-radius:5px; background:#eeeeee; text-decoration:none; text-align:center; font-weight:bold;">
	Desktop
</a>

<p style="text-align:center;">
	<? $url = "http://www.system.dev.base3-cms.de/base3/mobile/desktop/?entry_id=" . $this->_['id']; ?>
	<img border="0" src="http://chart.apis.google.com/chart?chs=150x150&cht=qr&chld=L|0&chl=<? echo $url; ?>" />
</p>

<script>
	$(".openapp").click(function() {
		$(".dashboard-dialogdiv").dialog("destroy").remove();
	});
</script>
