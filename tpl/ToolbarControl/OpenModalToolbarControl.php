	<button class="view_mode entry_select entry_filled" id="<?php echo $this->_['action']; ?>" title="<?php echo $this->_['params']['title']; ?>">
		<img border="0" src="<?php echo $this->_['params']['icon']; ?>" />
	</button>

	<script>
                $('#<?php echo $this->_['action']; ?>')
                        .on('click', function() {
                                editMode = true;
                                var url = '<?php echo $this->_['params']['control']; ?>.php?alias=' + currentModule;
                                var title = $(this).attr("title");
                                var subnavidialog = $('<div class="subnavidialog" />').appendTo("body").dialog({
                                        width: <?php echo $this->_['params']['width']; ?>,
                                        height: <?php echo $this->_['params']['height']; ?>,
                                        title: title,
                                        modal: true,
                                        open: function () {
                                                onCurrentEntryChangedContent = function() {};
                                                $(this).load(url + "&entryid=" + currentEntryId, function() {
                                                        onCurrentEntryChangedContent();
                                                });
                                        },
                                        close: function () {
                                                $(".subnavidialog").dialog("destroy").remove();
                                                editMode = false;
                                        },
                                        buttons: { "Close": function() { $(this).dialog("close"); } }
                                });
                                return false;
                        });
	</script>

