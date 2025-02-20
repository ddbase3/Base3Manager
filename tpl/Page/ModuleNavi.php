<a class="toggle" href='#'></a>
<ul>
<?php
	foreach ($this->_['modules'] as $module) {
		$name = $module['name'];
		if (isset($module['translate']) && $module['translate'] == 1) {
			$this->setPath(DIR_PLUGIN . $module['plugin']);
			$this->loadBricks('Bricks');
			$name = $this->_['bricks']['bricks'][$module['name']];
		}
?>
	<li><a href="#" rel="<?php echo $module['module']; ?>"><img border="0" src="<?php echo $module['icon']; ?>" /><?php echo $name; ?></a></li>
<?php
	}
?>
</ul>
