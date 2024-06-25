<ul id="modulesubnavi">
<?php foreach ($this->_['subnavi'] as $button) { ?>
	<li>
		<a href="?name=content&alias=<?php echo $this->_['alias']; ?>&subnavialias=<?php echo $button['subnavi']; ?>" rev="<?php echo $button['dialog']['width']."x".$button['dialog']['height']; ?>" title="<?php echo htmlentities($button['name']); ?>">
			<?php echo htmlentities($button['name']); ?>
		</a>
	</li>
<?php } ?>
</ul>

<div class="control">

<?php foreach ($this->_['toolbar'] as $toolgroup) { ?>
	<span>
<?php foreach ($toolgroup as $tool) echo $tool; ?>
	</span>
<?php } ?>

</div>

