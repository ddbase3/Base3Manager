<ul>
<?php
	foreach ($this->_['tabs'] as $tabalias => $tab) {
?>
	<li><a href="#" rel="<?php echo $this->_['alias']; ?>" rev="<?php echo $tabalias; ?>"><?php echo utf8_encode($tab->getName()); ?></a></li>
<?php
	}
?>
</ul>
