<?php $this->loadBricks('Manager'); ?>
	<ul id="selectlanguage">
<?php foreach ($this->_['languages'] as $language) { ?>
		<li>
			<a href="?data=<?php echo $language; ?>">
				<img src="plugin/Base3Manager/assets/img/lang/<?php echo $language; ?>.png" title="<?php echo $this->_['bricks']['manager']['switchlanguage-' . $language]; ?>" />
			</a>
		</li>
<?php } ?>
	</ul>

	<style>
		#selectlanguage { list-style:none; margin:0; padding:0; }
		#selectlanguage li { display:inline-block; margin:0 5px 0 0; padding:0; }
		#selectlanguage a { text-decoration:none; }
		#selectlanguage img { width:24px; }
	</style>
