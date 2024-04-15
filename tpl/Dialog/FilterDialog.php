<?php
	session_save_path("/tmp");
	session_start();

	define("B3INCLUDE", 1);
	include("inc/config.php");

	$alias = $this->_['alias'];

	$statuslist = $status[$alias];
	$filter = isset($_SESSION["filter"]) && isset($_SESSION["filter"][$alias]) ? $_SESSION["filter"][$alias] : array();
?>

<form id="filterform">
	<p>
		<button type="button" name="unset">Zur&uuml;cksetzen</button>
		Alle Filter zur&uuml;cksetzen
	</p>

<?php echo $this->_['filtercontrol']; ?>

<?php
	foreach ($statuslist as $statusdef) {
		switch ($statusdef["type"]) {

			case "check":
				if (isset($statusdef["desc"])) echo '<p style="margin:3px 0;">'.$statusdef["desc"].'</p>';
				foreach ($statusdef["options"] as $opt => $dsc) {
?>
	<p class="statusfilter">
		<select name="<? echo $opt; ?>">
			<option value="all"<?php if (!isset($filter["status_" . $opt]) || $filter["status_" . $opt] == "all") echo ' selected="selected"'; ?>>Alle</option>
			<option value="yes"<?php if (isset($filter["status_" . $opt]) && $filter["status_" . $opt] == "yes") echo ' selected="selected"'; ?>>Mit</option>
			<option value="no"<?php if (isset($filter["status_" . $opt]) && $filter["status_" . $opt] == "no") echo ' selected="selected"'; ?>>Ohne</option>
		</select>
		<? echo htmlentities($dsc); ?>
	</p>
<?php
				}
				break;

			case "multi":
?>
		<div style="padding:5px; margin:5px 0; border:1px solid #ddd; background:#eee;">
<?php
				if (isset($statusdef["desc"])) echo '<p style="margin:3px 0;">'.$statusdef["desc"].'</p>';
				foreach ($statusdef["options"] as $opt => $dsc) {
?>
	<p class="statusfilter">
		<select name="<?php echo $opt; ?>">
			<option value="all"<?php if (!isset($filter["status_" . $opt]) || $filter["status_" . $opt] == "all") echo ' selected="selected"'; ?>>Alle</option>
			<option value="yes"<?php if (isset($filter["status_" . $opt]) && $filter["status_" . $opt] == "yes") echo ' selected="selected"'; ?>>Mit</option>
			<option value="no"<?php if (isset($filter["status_" . $opt]) && $filter["status_" . $opt] == "no") echo ' selected="selected"'; ?>>Ohne</option>
		</select>
		<? echo htmlentities($dsc); ?>
	</p>
<?php
				}
?>
		</div>
<?php
				break;

		}
	}
?>

</form>

<script>
	var alias = '<? echo $alias; ?>';
	$('#filterform button[name="unset"]').on('click', function() {
		$('#filterform input[name="archive"]').prop('checked', false);
		$('#filterform p.statusfilter').each(function() { $('option:first', this).prop('selected', true) });
		$.post('filter.php', { module: alias, unset: 1 }, function(res) { console.log(res); });
	});
	$('#filterform input[name="archive"]').on('click', function() {
		var checked = $(this).is(":checked") ? 1 : 0;
		$.post('filter.php', { module: alias, archive: checked }, function(res) { console.log(res); });
	});
	$('#filterform p.statusfilter select').each(function() {
		$(this).on('change', function() {
			var data = { module: alias };
			data['status_' + $(this).attr('name')] = $(this).val();
			$.post('filter.php', data, function(res) { console.log(res); });
		});
	});
</script>
