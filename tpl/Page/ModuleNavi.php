<ul>
<?php
	foreach ($this->_['modules'] as $alias => $module) {
?>
	<li>
		<a href="#" rel="<?php echo $alias; ?>">
			<img border="0" src="modules/<?php echo $alias; ?>/icon.png" />
			<?php echo $module->getName(); ?>
		</a>
	</li>
<?php
	}
?>
</ul>
