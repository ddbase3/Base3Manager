<?php
	$this->setPath(DIR_PLUGIN . $this->_['plugin']);
	$this->loadBricks('Bricks');
?>
<a class="toggle" href='#'></a>
<ul id="modulesubnavi">
<?php
	foreach ($this->_['subnavi'] as $button) {
		$name = isset($button['translate']) && $button['translate'] == 1
			? $this->_['bricks']['bricks'][$button['name']]
			: $button['name'];
?>
	<li>
		<a href="?name=content&alias=<?php echo $this->_['alias']; ?>&subnavialias=<?php echo $button['subnavi']; ?>" rev="<?php echo $button['dialog']['width']."x".$button['dialog']['height']; ?>" title="<?php echo htmlentities($button['name']); ?>">
			<?php echo $name; ?>
		</a>
	</li>
<?php
	}
?>
</ul>

