<form name="create">

<?php echo $this->_['createcontrol']; ?>

	<br />
        <table border="0" width="100%">
                <colgroup>
                        <col width="100" />
                        <col />
                </colgroup>
                <tr>
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
        </table>
</form>

