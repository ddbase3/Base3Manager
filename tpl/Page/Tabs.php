<ul>
<?php
	foreach ($this->_['tabs'] as $tab) {
?>
	<li>
		<a href="#" rel="<?php echo $this->_['alias']; ?>" rev="<?php echo $tab['tab']; ?>">
			<?php echo $tab['name']; ?>
		</a>
	</li>
<?php
	}
?>
</ul>
