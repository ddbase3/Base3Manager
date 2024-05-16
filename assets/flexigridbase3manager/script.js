/**
 * Voraussetzungen:
 * - Server: Im entsprechenden Modul muss es eine Datei method_subitems.php (oder entsprechend) geben
 * - BASE3Manager: Im entsprechenden Modul und Tab muss es eine Datei createsub.dialog.php (oder entsprechend) geben
 */

(function($) {

	var methods = {

		init: function(options) {

			return this.each(function() {

				var opt = $.extend({
					subEntryName: "sub",
					height: 0,
					colModel: [
						{ display: '', name: 'entryid', width: 20, sortable: true, align: 'center' },
						{ display: 'Name', name: 'name', width: 300, sortable: true, align: 'left' }
					],
					searchitems: [
						{ display: 'Name', name: 'name', isdefault: true }
					],
					sortname: "name"
				}, options);

				var object = $(this);

				if (!opt.height) opt.height = object.height()-118;

				object.html('<table name="' + opt.subEntryName + 's" style="display:none;"></table>');
				$('table[name="' + opt.subEntryName + 's"]').flexigrid({
					url : '?name=connector&out=json&module=' + currentModule + '&method=' + opt.subEntryName + 'items&id=' + currentEntryId,
					dataType : 'json',
					colModel : opt.colModel,
					buttons : [
						{ name: 'Hinzufügen', bclass: 'connect', onpress: function(command, grid) {
							var modaldialog = $('<div class="modaldialog" />').appendTo("body").dialog({
								width: 500,
								height: 340,
								title: "Neuer Eintrag",
								modal: true,
								open: function () {
									$(this).load("modules/" + currentModule + "/tabs/" + currentTab + "/create" + opt.subEntryName + ".dialog.php", function() {
										modaldialog.find("form").submit(function() { return false; });
									});
								},
								close: function () { $(".modaldialog").dialog("destroy").remove(); },
								buttons: {
									"Ok": function() {
										var data = $(this).find("form").serialize();
										$.post("?name=connector&out=json&module=" + currentModule + "&method=" + opt.subEntryName + "items&id=" + currentEntryId, data, function(res) {
											$('table[name="' + opt.subEntryName + 's"]').flexReload();
										});
										$(this).dialog("close");
									},
									"Schließen": function() { $(this).dialog("close"); }
								}
							});
						} },
						{ name: 'Entfernen', bclass: 'disconnect', onpress: function(command, grid) {
							var numOfEntries = $('.trSelected', grid).length;
							switch (numOfEntries) {
								case 0:
									alert("Keine Einträge zum Entfernen markiert.");
									return;
								case 1:
									if (!confirm("Soll 1 Eintrag aus der Liste entfernt werden?")) return;
									break;
								default:
									if (!confirm("Sollen " + numOfEntries + " Einträge aus der Liste entfernt werden?")) return;
									break;
							}
							var entries = "";
							$('.trSelected', grid).each(function() {
								entries += $(this).attr("id").substr(3) + ",";
							});
							entries = entries.substr(0, entries.length - 1);
							$.post("?name=connector&out=json&module=" + currentModule + "&method=" + opt.subEntryName + "items&id=" + currentEntryId + "&action=disconnect", { entries: entries }, function() {
								$('table[name="' + opt.subEntryName + 's"]').flexReload();
							});
						} },
						{ separator: true } 
					],
					searchitems: opt.searchitems,
					sortname: opt.sortname,
					sortorder: "asc",
					pagestat: 'Einträge: {total} | Zeige {from} bis {to}',
					pagetext: 'Seite',
					outof: 'von',
					usepager: true,
					useRp: true,
					rp: 10,
					rpOptions: [10,15,20,40,60,80,100],
					width: "auto",
					height: opt.height,
					resizable: false,
					singleSelect: true,
					datacol: {
						'entryid': function(val) {
							return '<a class="' + currentModule + 'edit' + opt.subEntryName + '" href="#" rel="' + val + '"><img src="plugin/Base3Manager/assets/img/edit.gif" border="0" /></a>';
						}
					},
					onSuccess: function(grid) {
						$("." + currentModule + "edit" + opt.subEntryName).click(function() {
							var id = $(this).attr("rel");
							loadModule(opt.subEntryName, 'id', id);
							return false;
						});
					}
				});
			});
		}
	};

	$.fn.flexigridbase3manager = function(method) {

		if ( methods[method] ) {
			return methods[method].apply( this, Array.prototype.slice.call( arguments, 1 ));
		} else if ( typeof method === 'object' || ! method ) {
			return methods.init.apply( this, arguments );
		} else {
			$.error( 'Method ' +  method + ' does not exist on jQuery.flexigridbase3manager' );
		}    

	};

})(jQuery);
