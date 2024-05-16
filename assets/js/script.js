var currentScope = "";
var currentModule = "";
var currentEntryId = 0;
var currentEntryAccess = "";
var currentEntry = {};
var currentTab = "";
var entryLoaded = false;
var entryEmpty = true;
var headerLoaded = false;
var tabsLoaded = false;
var contentLoaded = false;
var loadingEntry = false;
var editMode = false;
var isSingleEntryModule = true;

var viewModeFieldStatus = "readonly";  // disabled | readonly

var onCurrentEntryChangedHeader = function() {}
var onCurrentEntryChangedContent = function() {}
var onCurrentModeChanged = function() {}

var initModules = function() {
	$("#modulenavi a").click(function() {
		var alias = $(this).attr("rel");
		loadModule(alias);
		return false;
	});
}

var loadEntry = function() {

	var numArgs = arguments.length;
	var alias = numArgs >= 1 ? arguments[0] : currentModule;
	var method = numArgs >= 2 ? arguments[1] : "last";
	var entryId = numArgs >= 3 ? arguments[2] : 0;

	if (loadingEntry == true) return;

	if (method == "empty") {
		currentEntryId = 0;
		currentEntryAccess = "";
		currentEntry = {};
		entryEmpty = true;
		entryLoaded = true;
		fillHeader();
		fillContent();
		return;
	}

	id = method == "prev" || method == "next" ? currentEntryId : entryId;
	// var url = "ajax/connector.php?module=" + alias + "&method=" + method + "&id=" + id;
	var url = "?name=connector&out=json&module=" + alias + "&method=" + method + "&id=" + id;

	var oldEntryId = currentEntryId;
	var oldEntryAccess = currentEntryAccess;
	var oldEntry = currentEntry;
	currentEntryId = 0;
	currentEntryAccess = "";
	currentEntry = {};
	entryLoaded = false;

	loadingEntry = true;
	$.getJSON(url, function(result) {
console.log(result);
		loadingEntry = false;
		entryLoaded = true;
		if (result == null || typeof result[0] === "undefined") {
			if (method == "prev" || method == "next") {
				currentEntryId = oldEntryId;
				currentEntryAccess = oldEntryAccess;
				currentEntry = oldEntry;
			}
		} else {
			currentEntryId = parseInt(result[0]["id"]);
			currentEntryAccess = result[0]["access"];
			currentEntry = result[0];
		}
		entryEmpty = typeof currentEntry["data"] === "undefined";
		fillHeader();
		fillContent();
		set_view_mode();
	});
}

var loadModule = function() {

	var numArgs = arguments.length;
	var alias = numArgs >= 1 ? arguments[0] : currentModule;
	var method = numArgs >= 2 ? arguments[1] : "last";
	var entryId = numArgs >= 3 ? arguments[2] : 0;

	$("#modulenavi li").removeClass("active");
	$('a[rel="' + alias + '"]').parent().addClass("active");

	loadingEntry = false;
	currentModule = alias;
	currentEntryId = 0;
	currentEntryAccess = "";
	currentEntry = {};
	entryEmpty = true;
	entryLoaded = false;
	headerLoaded = false;
	tabsLoaded = false;
	contentLoaded = false;
	onCurrentEntryChangedHeader = function() {};
	// onCurrentEntryChangedContent = function() {};
	onCurrentModeChanged = function() {};

	loadEntry(alias, method, entryId);

	$("#modulesub").load("?name=subnavi&alias=" + alias, function() {
		set_view_mode();
		$("#modulesubnavi a")
			.click(function() {
				editMode = true;
				var url = $(this).attr("href");
				var size = $(this).attr("rev").split("x");
				var title = $(this).attr("title");
				var subnavidialog = $('<div class="subnavidialog" />').appendTo("body").dialog({
					width: size[0],
					height: size[1],
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
					buttons: { "Schließen": function() { $(this).dialog("close"); } }
				});
				return false;
			});
	});

	$("#modulehead").load("?name=header&alias=" + alias, function() {
		headerLoaded = true;
		fillHeader();
	});

	$("#moduletabs").load("?name=tabs&alias=" + alias, function() {
		initTabs(alias);
		var tabButton = $('#moduletabs a:first');
		if (tabButton.length) loadTab(alias, tabButton.attr("rev"));
		tabsLoaded = true;
		fillHeader();
	});
}

var loadType = function() {

	var numArgs = arguments.length;
	if (numArgs < 1) return;

	var type = arguments[0];
	var method = numArgs >= 2 ? arguments[1] : "last";
	var entryId = numArgs >= 3 ? arguments[2] : 0;

	$.get("?name=typeservice&out=json&type=" + type, function(res) {
		if (!res.scope || !res.module) return;
		currentModule = res.module;
		loadScope(res.scope, false);
		loadModule(res.module, "id", entryId);
	});
}

var loadScope = function() {

	var numArgs = arguments.length;
	var scope = numArgs >= 1 ? arguments[0] : "";
	var reloadContent = numArgs >= 2 ? arguments[1] : true;

	currentScope = scope;

	$("#modulenavi").load("?name=modulenavi&scope=" + scope, function() {
		initModules();
		if (!reloadContent) return;
		$("#modulenavi ul").draggable({ axis: "x" });
		var module = $('#modulenavi a[rel="' + currentModule + '"]').length
			? currentModule
			: $("#modulenavi li:first a").attr("rel");
		loadModule(module);
	});
}

var fillHeader = function() {
	if (!entryLoaded || !headerLoaded) return;
	onCurrentEntryChangedHeader();
}

var initTabs = function(alias) {
	$("#moduletabs a").click(function() {
		if (editMode) {
			alert("Bitte zuerst den Bearbeitungsmodus verlassen.");
			return;
		}
		var tabalias = $(this).attr("rev");
		loadTab(alias, tabalias);
		return false;
	});
}

var loadTab = function(alias, tabalias) {
	currentTab = tabalias;
	$("#moduletabs li").removeClass("active");
	$('a[rev="' + tabalias + '"]').parent().addClass("active");
	// onCurrentEntryChangedContent = function() {};
	$(document).off("currentEntryChanged");
	$("#wrap").load("?name=content&alias=" + alias + "&tabalias=" + tabalias + "&entryid=" + currentEntryId, function() {
		$(document).on("currentEntryChanged", onCurrentEntryChangedContent);
		contentLoaded = true;
		fillContent();
	});
}

var fillContent = function() {
	if (!entryLoaded || !contentLoaded) return;
	// onCurrentEntryChangedContent();
	$(document).trigger("currentEntryChanged");
	set_view_mode();
}

var convertDateSql2Human = function(date) {
	var expr = /(\d{4})-(\d{2})-(\d{2})\s(\d{2}):(\d{2}):(\d{2})/;
	expr.exec(date);
	return RegExp.$3 + "." + RegExp.$2 + "." + RegExp.$1 + " " + RegExp.$4 + ":" + RegExp.$5 + ":" + RegExp.$6;
}

var copyToClipboard = function(str) {
	window.prompt("Copy to clipboard: Ctrl+C, Enter", str);
}

//////////////////////////////////////////////////////////////////////////////////
// Subnavi

	$.fn.controlEnabled = function(enabled) {
		if (enabled) $(this).removeAttr("disabled"); else $(this).attr("disabled", "disabled");
		return $(this);
	}

	var set_edit_mode = function() {
		if (currentEntryAccess != "edit") return;
		editMode = true;
		$("#wrap .masterdata").find("input, textarea").not(".readonly").removeAttr(viewModeFieldStatus);
		$("#wrap .masterdata").find("select").not(".readonly").removeAttr("disabled");
		$(".edit_mode").removeAttr("disabled");
		$(".view_mode").attr("disabled", "disabled");
		onCurrentModeChanged();
	}

	var set_view_mode = function() {
		editMode = false;
		$("#wrap .masterdata").find("input, textarea").attr(viewModeFieldStatus, viewModeFieldStatus);
		$("#wrap .masterdata").find("select").attr("disabled", "disabled");
		$(".edit_mode").attr("disabled", "disabled");
		$(".view_mode").each(function() {
			var enabled = !$(this).hasClass("edit_access") || currentEntryAccess == "edit";
			if ($(this).hasClass("entry_select") && !isSingleEntryModule) enabled = false;
			if ($(this).hasClass("entry_filled") && entryEmpty) enabled = false;
			$(this).controlEnabled(enabled);
		});
		onCurrentModeChanged();
	}

	var set_nodata_mode = function() {
		$("#wrap .masterdata").find("input, select, textarea").attr("disabled", "disabled");
		$(".edit_mode, .view_mode").attr("disabled", "disabled");
	}


//////////////////////////////////////////////////////////////////////////////////

$(function() {

	$(document).keyup(function(e) {
		if (isSingleEntryModule && !editMode && !$("input:focus").length) {
			switch (e.keyCode) {
				case 37: // links
					loadEntry(currentModule, "next");
					break;
				case 38: // hoch
					loadEntry(currentModule, "last");
					break;
				case 39: // rechts
					loadEntry(currentModule, "prev");
					break;
				case 40: // unten
					loadEntry(currentModule, "first");
					break;
			}
		}
	});

	$.datepicker.regional['de'] = { clearText: 'löschen', clearStatus: 'aktuelles Datum löschen',
		closeText: 'schließen', closeStatus: 'ohne Änderungen schließen',
		prevText: '&#x3c;zurück', prevStatus: 'letzten Monat zeigen',
		nextText: 'Vor&#x3e;', nextStatus: 'nächsten Monat zeigen',
		currentText: 'heute', currentStatus: '',
		monthNames: ['Januar','Februar','März','April','Mai','Juni','Juli','August','September','Oktober','November','Dezember'],
		monthNamesShort: ['Jan','Feb','Mär','Apr','Mai','Jun','Jul','Aug','Sep','Okt','Nov','Dez'],
		monthStatus: 'anderen Monat anzeigen', yearStatus: 'anderes Jahr anzeigen',
		weekHeader: 'Wo', weekStatus: 'Woche des Monats',
		dayNames: ['Sonntag','Montag','Dienstag','Mittwoch','Donnerstag','Freitag','Samstag'],
		dayNamesShort: ['So','Mo','Di','Mi','Do','Fr','Sa'],
		dayNamesMin: ['So','Mo','Di','Mi','Do','Fr','Sa'],
		dayStatus: 'Setze DD als ersten Wochentag', dateStatus: 'Wähle D, M d',
		dateFormat: 'dd.mm.yy', firstDay: 1,
		initStatus: 'Wähle ein Datum', isRTL: false };
	$.datepicker.setDefaults($.datepicker.regional['de']);

	$("#systemnavi > ul > li")
		.mouseenter(function() { $(this).children("ul").show(); })
		.mouseleave(function() { $(this).children("ul").hide(); });

	/////////////////////////////

	const queryString = window.location.search;
	const urlParams = new URLSearchParams(queryString);
	loadScope(urlParams.has('scope') ? urlParams.get('scope') : "");
	loadModule(urlParams.has('module') ? urlParams.get('module') : "");
});
