<a class="toggle" href='#'></a>
<ul>
<?php
	foreach ($this->_['modules'] as $module) {
?>
	<li><a href="#" rel="<?php echo $module['module']; ?>"><img border="0" src="<?php echo $module['icon']; ?>" /><?php echo $module['name']; ?></a></li>
<?php
	}
?>
</ul>
