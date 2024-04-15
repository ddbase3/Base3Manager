	<div name="tablemodulelist" style="width:780px; height:410px; overflow-x:hidden;">
		<table name="modulelist"></table>
	</div>

<script>

		$('table[name="modulelist"]').flexigrid({
			url : 'ajax/connector.php?module=<?php echo $this->_['alias']; ?>&method=list',
			dataType : 'json',
			colModel : [
				{ display: '', name: 'entryid', width: 20, sortable: true, align: 'center' },
				{ display: 'Vorname', name: 'firstname', width: 120, sortable: true, align: 'left' },
				{ display: 'Nachname', name: 'lastname', width: 120, sortable: true, align: 'left' },
				{ display: 'Telefon', name: 'phone', width: 90, sortable: true, align: 'left' },
				{ display: 'Mobil', name: 'mobile', width: 90, sortable: true, align: 'left' },
				{ display: 'E-Mail', name: 'email1', width: 150, sortable: true, align: 'left' },
				{ display: 'Geburtstag', name: 'dateofbirth', width: 80, sortable: true, align: 'left' }
			],
/*
			buttons : [
				{ name: 'Add', bclass: 'add', onpress: Example4 },
				{ name: 'Edit', bclass: 'edit', onpress: Example4 },
				{ name: 'Delete', bclass: 'delete', onpress: Example4 },
				{ separator: true } 
			],
*/
			searchitems : [
				{ display: 'Vorname', name: 'firstname' },
				{ display: 'Nachname', name: 'lastname' }
			],
			sortname: "name",
			sortorder: "asc",
			pagestat: 'Eintr&auml;ge: {total} | Zeige {from} bis {to}',
			pagetext: 'Seite',
			outof: 'von',
			usepager: true,
			useRp: true,
			rp: 10,
			rpOptions: [10,15,20,40,60,80,100],
			width: "auto",
			height: 290,
			resizable: false,
			singleSelect: true,
			datacol: {
				'entryid': function(val) {
					return '<a class="modulefindmodule" href="#" rel="' + val + '">'
						+ '<img src="plugin/Base3Manager/assets/img/edit.gif" border="0" />'
						+ '</a>';
				}
			},
			onSuccess: function(grid) {
				$(".modulefindmodule").click(function() {
					var id = $(this).attr("rel");
					$(".modaldialog").dialog("close");
					loadModule('<?php echo $this->_['alias']; ?>', 'id', id);
					return false;
				});
			}
		});

</script>
