<?php
	$this->setPath(DIR_PLUGIN . $this->_['plugin']);
	$this->loadBricks('Bricks');
?>
<ul>
<?php
	foreach ($this->_['tabs'] as $tab) {
		$name = isset($tab['translate']) && $tab['translate'] == 1
			? $this->_['bricks']['bricks'][$tab['name']]
			: $tab['name'];
?>
	<li>
		<a href="#" rel="<?php echo $this->_['alias']; ?>" rev="<?php echo $tab['tab']; ?>">
			<?php echo $name; ?>
		</a>
	</li>
<?php
	}
?>
</ul>
