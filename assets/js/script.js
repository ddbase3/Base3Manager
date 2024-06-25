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

var onCurrentEntryChangedHeader = function() {}
var onCurrentEntryChangedContent = function() {}
var onCurrentModeChanged = function() {}

// systemnavi

var initSystemNavi = function() {
	$("#systemnavi > ul > li")
		.mouseenter(function() { $(this).children("ul").show(); })
		.mouseleave(function() { $(this).children("ul").hide(); });
};

var initModules = function() {
	$("#modulenavi a").on('click', function() {
		var alias = $(this).attr("rel");
		loadModule(alias);
		return false;
	});
};

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
	var tab = numArgs >= 4 ? arguments[3] : "";

	if (!alias.length) return;

/*
	if (currentScope.length && numArgs >= 1) {
		var url = "?scope=" + currentScope + "&module=" + alias;
		if (entryId) url += "&entryid=" + currentEntryId;
		if (tab.length) url += "&tab=" + tab;
		history.pushState({}, document.title, url);
	}
*/

	$("#modulenavi li").removeClass("active");
	$('a[rel="' + alias + '"]').parent().addClass("active");

	loadingEntry = false;
	currentModule = alias;
	currentEntryId = 0;
	currentEntryAccess = "";
	currentEntry = {};
	entryEmpty = true;
	entryLoaded = false;
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

	loadHeader(alias);
	loadTabs(alias, tab);
}

var loadScope = function() {

	var numArgs = arguments.length;
	var scope = numArgs >= 1 ? arguments[0] : "";
	var reloadContent = numArgs >= 2 ? arguments[1] : true;

	currentScope = scope;

	$("#modulenavi").load("?name=modulenavi&scope=" + scope, function() {
		$('a[rel="' + currentModule + '"]').parent().addClass("active");
		initModules();
		if (!reloadContent) return;
		var module = $('#modulenavi a[rel="' + currentModule + '"]').length
			? currentModule
			: $("#modulenavi li:first a").attr("rel");
		loadModule(module);
	});
}

// header

var loadHeader = function(alias) {
	headerLoaded = false;
	$("#modulehead").load("?name=header&alias=" + alias, function() {
		headerLoaded = true;
		fillHeader();
	});
};

var fillHeader = function() {
	if (!entryLoaded || !headerLoaded) return;
	onCurrentEntryChangedHeader();
};

// tabs

var loadTabs = function(alias, tab) {
	tabsLoaded = false;
	$("#moduletabs").load("?name=tabs&alias=" + alias, function() {
		initTabs(alias);
		if (tab.length) {
			loadTab(alias, tab);
		} else {
			var tabButton = $('#moduletabs a:first');
			if (tabButton.length) loadTab(alias, tabButton.attr("rev"));
		}
		tabsLoaded = true;
		fillHeader();
	});
};

var initTabs = function(alias) {
	$("#moduletabs a").on('click', function() {
		if (editMode) {
			alert("Bitte zuerst den Bearbeitungsmodus verlassen.");
			return;
		}
		var tabalias = $(this).attr("rev");
		loadTab(alias, tabalias);
		history.pushState({}, document.title, "?scope=" + currentScope + "&module=" + alias + "&entryid=" + currentEntryId + "&tab=" + tabalias);
		return false;
	});
};

var loadTab = function(alias, tabalias) {
	currentTab = tabalias;
	$("#moduletabs li").removeClass("active");
	$('a[rev="' + tabalias + '"]').parent().addClass("active");
	// onCurrentEntryChangedContent = function() {};
	$(document).off("currentEntryChanged");
	$("#content").load("?name=content&alias=" + alias + "&tabalias=" + tabalias + "&entryid=" + currentEntryId, function() {
		$(document).on("currentEntryChanged", onCurrentEntryChangedContent);
		contentLoaded = true;
		fillContent();
	});
};

// content

var fillContent = function() {
	if (!entryLoaded || !contentLoaded) return;
	// onCurrentEntryChangedContent();
	$(document).trigger("currentEntryChanged");
	set_view_mode();
};

// initialize

$(function() {

	initSystemNavi();

	/////////////////////////////

	$(window).on("popstate", function(e) {
		if (e.originalEvent.state !== null) location.reload();
	});

	const queryString = window.location.search;
	const urlParams = new URLSearchParams(queryString);

	loadScope(urlParams.has('scope') ? urlParams.get('scope') : "", !urlParams.has('module'));

	if (urlParams.has('module') && urlParams.has('entryid') && urlParams.has('tab')) {
		loadModule(urlParams.get('module'), "id", urlParams.get('entryid'), urlParams.get('tab'));
	} else if (urlParams.has('module') && urlParams.has('entryid')) {
		loadModule(urlParams.get('module'), "id", urlParams.get('entryid'));
	} else if (urlParams.has('module')) {
		loadModule(urlParams.get('module'));
	} else {
		loadModule();
	}
});
