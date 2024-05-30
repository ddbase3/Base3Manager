<?php
/*
	define("B3INCLUDE", true);

	include("../../../../inc/config.php");
	include("../../../../inc/init.php");
*/
?>
<form name="create">
	<table border="0" width="100%;">
		<colgroup>
			<col width="100" />
			<col />
		</colgroup>
		<tr>
			<td>&nbsp;</td>
			<td>
				<input type="radio" name="action" value="new" checked="checked" /> Neu hinzuf&uuml;gen
				&nbsp;
				<input type="radio" name="action" value="connect" /> Vorhandenen ausw&auml;hlen
			</td>
		</tr>
		<tr><td colspan="2">&nbsp;</td></tr>

<?php ////////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>

		<tr class="new">
			<td>Bezeichnung</td>
			<td><input type="text" name="name" value="" style="width:100%;" /></td>
		</tr>
		<tr class="new">
			<td valign="top">Link</td>
			<td><input type="text" name="link" value="" style="width:100%;" /></td>
		</tr>

<?php ////////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>

		<tr class="new"><td colspan="2">&nbsp;</td></tr>
		<tr class="new">
			<td valign="top">Freigabe</td>
			<td valign="top">
<?php
	if (isset($_SESSION["activegroups"]) && sizeof($_SESSION["activegroups"])) {
		$groups = array();
		foreach ($_SESSION["activegroups"] as $key => $value) $groups[] = $key;
?>
				<input type="radio" name="access" value="private" />
				Datensatz privat nutzen
				<br />
				<input type="radio" name="access" value="<?php echo implode(",", $groups); ?>" checked="checked" />
				Gruppe(n): <?php echo implode(", ", $_SESSION["activegroups"]); ?> 
<?php
	} else {
?>
				<input type="radio" name="access" value="private" <?php if (!$this->_['accessstdallusers']) echo 'checked="checked" '; ?>/>
				Datensatz privat nutzen
				<br />
				<input type="radio" name="access" value="all" <?php if ($this->_['accessstdallusers']) echo 'checked="checked" '; ?>/>
				Datensatz f&uuml;r alle Benutzer verf&uuml;gbar
<?php
	}
?>
			</td>
		</tr>
		<tr class="connect">
			<td>Auswahl</td>
			<td>
				<input type="hidden" name="entries" value="" />
				<input type="text" name="selectentry" style="width:100%;" />
			</td>
		</tr>
	</table>
</form>

<script>
	var form = $('form[name="create"]');
	form.find(".connect").hide();
	form.find('input[name="action"]').change(function() {
		if ($(this).val() == "connect") {
			form.find('.connect').show();
			form.find('.new').hide();
		} else {
			form.find('.new').show();
			form.find('.connect').hide();
		}
	});

	form.find('input[name="selectentry"]').autocomplete({

<?php ////////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>

		source: "?name=connector&out=json&module=linklist&method=suggest",

<?php ////////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>

		minLength: 2,
		select: function(event, ui) {
			form.find('input[name="entries"]').val(ui.item.value);
			form.find('input[name="selectentry"]').val(ui.item.label);
			return false;
		}
	});
</script>
