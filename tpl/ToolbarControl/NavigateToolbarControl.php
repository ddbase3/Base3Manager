<?php
	$icons = array(
		"last" => "first",
		"next" => "previous",
		"prev" => "next",
		"first" => "last"
	);
?>
        <button class="view_mode entry_select" id="<?php echo $this->_['action']; ?>" title="<?php echo $this->_['params']['title']; ?>">
                <img border="0" src="plugin/Base3Manager/assets/img/icons/resultset_<?php echo $icons[$this->_['params']['navigate']]; ?>.png" />
        </button>

	<script>
		$("#<?php echo $this->_['action']; ?>").click(function() { loadEntry(currentModule, "<?php echo $this->_['params']['navigate']; ?>"); });
	</script>

