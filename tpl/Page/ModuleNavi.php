<ul>
<?php
	foreach ($this->_['modules'] as $module) {
		// TODO remove old way
		$icon = isset($module['icon'])
			? $module['icon']
			: 'modules/' . $module['module'] . '/icon.png';
?>
	<li><a href="#" rel="<?php echo $module['module']; ?>"><img border="0" src="<?php echo $icon; ?>" /><?php echo $module['name']; ?></a></li>
<?php
	}
?>
</ul>
