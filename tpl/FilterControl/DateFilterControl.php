<?php
	$alias = $this->_['alias'];
	$filter = isset($_SESSION["filter"]) && isset($_SESSION["filter"][$alias]) ? $_SESSION["filter"][$alias] : array();
?>
	<p>
		<input type="checkbox" name="olddates" value="1" <?php if (isset($filter["olddates"]) && $filter["olddates"]) echo 'checked="checked" '; ?>/>
		Alte Termine anzeigen
	</p>
	<p>
		<input type="checkbox" name="archive" value="1" <?php if (isset($filter["archive"]) && $filter["archive"]) echo 'checked="checked" '; ?>/>
		Archiv anzeigen
	</p>
