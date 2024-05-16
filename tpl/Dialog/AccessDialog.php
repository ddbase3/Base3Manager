<?php
	if (!$this->_['id']) {
		echo "Kein Eintrag ausgew&auml;hlt.";
		exit();
	}
?>
<div id="accessroles" style="float:left; width:300px; height:380px; border-right:1px solid #cccccc; margin-right:10px; overflow:auto; overflow-x:hidden;">
</div>

<div id="accessconfig" style="float:left; width:450px; height:380px; overflow:auto; overflow-x:hidden;">

	<div id="accessconfigedit" style="display:none; padding-bottom:10px; margin-bottom:10px; border-bottom:1px solid #cccccc;">
	</div>

	<div id="accessconfignew" style="padding-bottom:10px; margin-bottom:10px; border-bottom:1px solid #cccccc;">
		<p>Eine neue Zugriffsrecht-Regel hinzuf&uuml;gen:</p>
		<p>
			<select id="accessconfignew_select" style="width:295px;">
				<option value="">Bitte w&auml;hlen ...</select>
			</select>
			<button type="button" id="accessconfignew_read" disabled="disabled" style="width:70px;">Lesen</button>
			<button type="button" id="accessconfignew_write" disabled="disabled" style="width:70px;">&Auml;ndern</button>
		</p>
		<p>
			<button type="button" id="accessconfignew_public" style="width:145px;">Ver&ouml;ffentlichen</button>
			<button type="button" id="accessconfignew_allusersread" style="width:145px;">Lesen f&uuml;r alle User</button>
			<button type="button" id="accessconfignew_alluserswrite" style="width:145px;">&Auml;ndern f&uuml;r alle User</button>
		</p>
	</div>

</div>

<script>
	var box = $("#accessroles");

	var cssRole = { "font-weight": "bold" };
	var cssRoleList = { "margin": "0", "padding": "0 10px 0 0", "list-style": "none" };
	var cssRoleListItem = { "margin": "0", "padding": "0 0 0 20px", "min-height": "16px", "border": "1px solid #ffffff", "cursor": "pointer" };
	var cssRoleRead = { "background": "url(plugin/Base3Manager/assets/img/icons/eye.png) no-repeat left center" };
	var cssRoleWrite = { "background": "url(plugin/Base3Manager/assets/img/icons/page_white_edit.png) no-repeat left center" };
	var cssActiveItem = { "background-color": "#eeeeee", "border": "1px solid #cccccc" };
	var cssInactiveItem = { "background-color": "transparent", "border": "1px solid #ffffff" };

	var usersandgroupsLoaded = false;
	var accessrulesLoaded = false;

	var updateSelectAfterLoad = function() {
		if (!usersandgroupsLoaded || !accessrulesLoaded) return;

		var select = $("#accessconfignew_select");
		box.find('li').each(function() {
			if (!$(this).hasClass("user") && !$(this).hasClass("group")) return;
			var val = $(this).attr("id").split("access").join("-");
			select.find('option[value="'+val+'"]').remove();
		});
	}

	var handleRuleClick = function(elem) {
		box.find('li').css(cssInactiveItem);
		elem.css(cssActiveItem);

		var editbox = $("#accessconfigedit");

		editbox.text('').show();
		$('<p>Zugriffsrecht-Regel bearbeiten:</p>').appendTo(editbox);

		if (elem.hasClass("user")) {

			$('<input type="hidden" name="role" value="user" />').appendTo(editbox);
			$('<input type="hidden" name="roleid" value="'+elem.attr("id").substr(10)+'" />').appendTo(editbox);

			modestr = "";
			if (elem.hasClass('visit')) modestr = "Besucher";
			if (elem.hasClass('member')) modestr = "Member";
			if (elem.hasClass('admin')) modestr = "Administrator";
			$('<p>'+elem.text()+' <span style="font-size:10px; color:#cc6600;">('+modestr+')'+'</span></p>').appendTo(editbox);

			if (elem.hasClass('owner')) {
				$('<p>Der Nutzer, der diesen Eintrag erstellt hat, ist der Eigent&uuml;mer dieses Eintrags. '
					+ 'Er hat als Urheber immer alle Rechte zur Ansicht, Bearbeitung und L&ouml;schung. '
					+ 'Seine Rechte k&ouml;nnen nicht ge&auml;ndert werden.</p>').appendTo(editbox);
			} else {
				$('<p><input type="radio" name="accesstype" value="read" '+( elem.hasClass("read") ? 'checked="checked" ' : '' )+'/> Benutzer darf den Eintrag sehen<br />'
					+'<input type="radio" name="accesstype" value="write" '+( elem.hasClass("write") ? 'checked="checked" ' : '' )+'/> Benutzer darf den Eintrag bearbeiten oder l&ouml;schen</p>').appendTo(editbox);
				$('<p><button type="button" class="remove">Regel entfernen</button></p>').appendTo(editbox);
			}
		}

		if (elem.hasClass("public")) {

			$('<input type="hidden" name="role" value="user" />').appendTo(editbox);
			$('<input type="hidden" name="roleid" value="0" />').appendTo(editbox);

			$('<p>&Ouml;ffentlichkeit</p>').appendTo(editbox);

			$('<p><button type="button" class="remove">Regel entfernen</button></p>').appendTo(editbox);

			$('<p>Der &Ouml;ffentlichkeit kann lediglich ein Leserecht zugeteilt werden.</p>').appendTo(editbox);
		}

		if (elem.hasClass("allusers")) {

			$('<input type="hidden" name="role" value="group" />').appendTo(editbox);
			$('<input type="hidden" name="roleid" value="0" />').appendTo(editbox);

			$('<p>Alle Benutzer</p>').appendTo(editbox);

			$('<p><input type="radio" name="accesstype" value="read" '+( elem.hasClass("read") ? 'checked="checked" ' : '' )+'/> Die Gruppe darf den Eintrag sehen<br />'
				+'<input type="radio" name="accesstype" value="write" '+( elem.hasClass("write") ? 'checked="checked" ' : '' )+'/> Die Gruppe darf den Eintrag bearbeiten oder l&ouml;schen (au&szlig;er Besucher)</p>').appendTo(editbox);
			$('<p><button type="button" class="remove">Regel entfernen</button></p>').appendTo(editbox);
		}

		if (elem.hasClass("group")) {

			$('<input type="hidden" name="role" value="group" />').appendTo(editbox);
			$('<input type="hidden" name="roleid" value="'+elem.attr("id").substr(11)+'" />').appendTo(editbox);

			$('<p>'+elem.text()+'</p>').appendTo(editbox);

			$('<p><input type="radio" name="accesstype" value="read" '+( elem.hasClass("read") ? 'checked="checked" ' : '' )+'/> Die Gruppe darf den Eintrag sehen<br />'
				+'<input type="radio" name="accesstype" value="write" '+( elem.hasClass("write") ? 'checked="checked" ' : '' )+'/> Die Gruppe darf den Eintrag bearbeiten oder l&ouml;schen (au&szlig;er Besucher)</p>').appendTo(editbox);
			$('<p><button type="button" class="remove">Regel entfernen</button></p>').appendTo(editbox);
		}

		editbox.find('input[name="accesstype"]').click(function() {
			if (!$(this).is(":checked")) return;
			var type = $(this).val();
			var role = $("#accessconfigedit").find('input[name="role"]').val();
			var roleid = $("#accessconfigedit").find('input[name="roleid"]').val();
			$.post("?name=connector&out=json&module=system&method=access", { action: "change", id: <?php echo $this->_['id']; ?>, role: role, roleid: roleid, type: type });

			var oppositeType = type == "read" ? "write" : "read";
			$("#accessroles").find('#'+role+'access'+roleid).removeClass(oppositeType).addClass(type).css(type == "read" ? cssRoleRead : cssRoleWrite);
		});

		editbox.find("button.remove").click(function() {
			var role = $("#accessconfigedit").find('input[name="role"]').val();
			var roleid = $("#accessconfigedit").find('input[name="roleid"]').val();
			$.post("?name=connector&out=json&module=system&method=access", { action: "remove", id: <?php echo $this->_['id']; ?>, role: role, roleid: roleid });

			if (role == "user" && roleid == 0) $("#accessconfignew_public").show();
			if (role == "group" && roleid == 0) $("#accessconfignew_allusersread, #accessconfignew_alluserswrite").show();

			editbox.hide();
			var li = $("#accessroles").find('#'+role+'access'+roleid);


			// Dem Select wieder hinzufügen
			var select = $("#accessconfignew_select");
			var name = li.text();
			select.append('<option class="' + role + '" value="' + role + '-' + roleid + '">' + name + '</option>');


			var ul = li.parent();
			li.remove();
			if (!ul.children().length) {
				ul.prev().remove();
				ul.remove();
			}

			return false;
		});
	}

	$.get("?name=connector&out=json&module=system&method=usersandgroups", function(result) {

		var select = $("#accessconfignew_select");
		for (var i=0; i<result.length; i++)
			select.append('<option class="' + result[i].type + '" value="' + result[i].type + '-' + result[i].id + '">' + result[i].name + '</option>');

		usersandgroupsLoaded = true;
		updateSelectAfterLoad();
	});
	$("#accessconfignew_select").change(function() {
		if (!$(this).val().length) {
			$("#accessconfignew_read, #accessconfignew_write").attr("disabled", "disabled");
			return;
		}
		$("#accessconfignew_read, #accessconfignew_write").removeAttr("disabled");
	});
	$("#accessconfignew_read, #accessconfignew_write").click(function() {
		var type = $(this).attr("id") == "accessconfignew_write" ? "write" : "read";
		var val = $("#accessconfignew_select").val();
		if (!val.length) return false;
		var valparts = val.split("-");
		var role = valparts[0];
		var roleid = parseInt(valparts[1]);

		$.post("?name=connector&out=json&module=system&method=access", { action: "add", id: <?php echo $this->_['id']; ?>, role: role, roleid: roleid, type: type });

		if (!box.find("ul."+role+"s").length) {
			$('<p>' + ( role == "group" ? 'Gruppen' : 'Benutzer' ) + '</p>').css(cssRole).appendTo(box);
			$('<ul class="'+role+'s" />').css(cssRoleList).appendTo(box);
		}
		var rolelist = box.find("ul."+role+"s");

		var selelem = $('#accessconfignew_select option[value="'+val+'"]');
		var name = selelem.text();
		$('<li id="'+role+'access'+roleid+'" class="'+role+' '+type+'">'+name+'</li>')
			.css(cssRoleListItem).css(type == "read" ? cssRoleRead : cssRoleWrite).click(function() {
				handleRuleClick($(this));
			}).appendTo(rolelist);
		selelem.remove();

		return false;
	});
	$("#accessconfignew_public").click(function() {
		$.post("?name=connector&out=json&module=system&method=access", { action: "add", id: <?php echo $this->_['id']; ?>, role: "user", roleid: 0, type: "read" });

		if (!box.find("ul.general").length) {
			$('<p>Allgemein</p>').css(cssRole).appendTo(box);
			$('<ul class="general" />').css(cssRoleList).appendTo(box);
		}
		var general = box.find("ul.general");

		$('<li id="useraccess0" class="public read">&Ouml;ffentlichkeit</li>').css(cssRoleListItem).css(cssRoleRead).click(function() {
			handleRuleClick($(this));
		}).appendTo(general);
		$("#accessconfignew_public").hide();

		return false;
	});
	$("#accessconfignew_allusersread").click(function() {
		$.post("?name=connector&out=json&module=system&method=access", { action: "add", id: <?php echo $this->_['id']; ?>, role: "group", roleid: 0, type: "read" });

		if (!box.find("ul.general").length) {
			$('<p>Allgemein</p>').css(cssRole).appendTo(box);
			$('<ul class="general" />').css(cssRoleList).appendTo(box);
		}
		var general = box.find("ul.general");

		$('<li id="groupaccess0" class="allusers read">Alle Benutzer</li>').css(cssRoleListItem).css(cssRoleRead).click(function() {
			handleRuleClick($(this));
		}).appendTo(general);
		$("#accessconfignew_allusersread, #accessconfignew_alluserswrite").hide();

		return false;
	});
	$("#accessconfignew_alluserswrite").click(function() {
		$.post("?name=connector&out=json&module=system&method=access", { action: "add", id: <?php echo $this->_['id']; ?>, role: "group", roleid: 0, type: "write" });

		if (!box.find("ul.general").length) {
			$('<p>Allgemein</p>').css(cssRole).appendTo(box);
			$('<ul class="general" />').css(cssRoleList).appendTo(box);
		}
		var general = box.find("ul.general");

		$('<li id="groupaccess0" class="allusers write">Alle Benutzer</li>').css(cssRoleListItem).css(cssRoleWrite).click(function() {
			handleRuleClick($(this));
		}).appendTo(general);
		$("#accessconfignew_allusersread, #accessconfignew_alluserswrite").hide();

		return false;
	});

	$.get("?name=connector&out=json&module=system&method=access&id=<?php echo $this->_['id']; ?>", function(result) {

		if (result.owner && typeof result.owner !== undefined) {
			$('<p>Eigent&uuml;mer</p>').css(cssRole).appendTo(box);
			var owner = $('<ul class="owner" />').css(cssRoleList).appendTo(box);
			$('<li id="useraccess'+result.owner.id+'" class="user owner write '+result.owner.mode+'">'+result.owner.fullname+'</li>').css(cssRoleListItem).appendTo(owner);
		}

		if ((result.public && typeof result.public !== undefined) || (result.allusers && typeof result.allusers !== undefined)) {
			$('<p>Allgemein</p>').css(cssRole).appendTo(box);
			var general = $('<ul class="general" />').css(cssRoleList).appendTo(box);
			if (result.public && typeof result.public !== undefined && result.public == 1) {
				$('<li id="useraccess0" class="public read">&Ouml;ffentlichkeit</li>').css(cssRoleListItem).appendTo(general);
				$("#accessconfignew_public").hide();
			}
			if (result.allusers && typeof result.allusers !== undefined) {
				var type = result.allusers == "moderator" ? "write" : "read";
				$('<li id="groupaccess0" class="allusers ' + type + '">Alle Benutzer</li>').css(cssRoleListItem).appendTo(general);
				$("#accessconfignew_allusersread, #accessconfignew_alluserswrite").hide();
			}
		}

		if ((result.visitor_groups && typeof result.visitor_groups !== undefined && result.visitor_groups.length)
				|| (result.moderator_groups && typeof result.moderator_groups !== undefined && result.moderator_groups.length)) {
			$('<p>Gruppen</p>').css(cssRole).appendTo(box);
			var groups = $('<ul class="groups" />').css(cssRoleList).appendTo(box);
			if (result.visitor_groups && typeof result.visitor_groups !== undefined)
				for (var i=0; i<result.visitor_groups.length; i++)
					$('<li id="groupaccess'+result.visitor_groups[i].id+'" class="group read">'+result.visitor_groups[i].name+'</li>').css(cssRoleListItem).appendTo(groups);
			if (result.moderator_groups && typeof result.moderator_groups !== undefined)
				for (var i=0; i<result.moderator_groups.length; i++)
					$('<li id="groupaccess'+result.moderator_groups[i].id+'" class="group write">'+result.moderator_groups[i].name+'</li>').css(cssRoleListItem).appendTo(groups);
		}

		if ((result.visitors && typeof result.visitors !== undefined && result.visitors.length)
				|| (result.moderators && typeof result.moderators !== undefined && result.moderators.length)) {
			$('<p>Benutzer</p>').css(cssRole).appendTo(box);
			var users = $('<ul class="users" />').css(cssRoleList).appendTo(box);
			if (result.visitors && typeof result.visitors !== undefined)
				for (var i=0; i<result.visitors.length; i++)
					$('<li id="useraccess'+result.visitors[i].id+'" class="user read '+result.visitors[i].mode+'">'+result.visitors[i].fullname+'</li>').css(cssRoleListItem).appendTo(users);
			if (result.moderators && typeof result.moderators !== undefined)
				for (var i=0; i<result.moderators.length; i++)
					$('<li id="useraccess'+result.moderators[i].id+'" class="user write '+result.moderators[i].mode+'">'+result.moderators[i].fullname+'</li>').css(cssRoleListItem).appendTo(users);
		}

		box.find('.read').css(cssRoleRead);
		box.find('.write').css(cssRoleWrite);

		box.find('li').click(function() {
			handleRuleClick($(this));
		});

		accessrulesLoaded = true;
		updateSelectAfterLoad();
	});
</script>
