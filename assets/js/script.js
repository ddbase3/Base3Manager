(function($, window) {

	var methods = {

		b3m: null,

		init: function(options) {
			return this.each(function() {
				var opt = $.extend({
					data: {}
				}, options);

				methods.b3m = $(this);
				methods.b3m.addClass('base3manager');

				// TODO refactoring
				$(window).on("popstate", function(e) {
					if (e.originalEvent.state !== null) location.reload();
				});

				methods._initSystemNavi(base3manager);

				const queryString = window.location.search;
				const urlParams = new URLSearchParams(queryString);

				methods.loadScope(urlParams.has('scope') ? urlParams.get('scope') : "", !urlParams.has('module'))

				// TODO refactoring
				if (urlParams.has('module') && urlParams.has('entryid') && urlParams.has('tab')) {
					methods.loadModule(urlParams.get('module'), { method: 'id', entryid: urlParams.get('entryid') }, urlParams.get('tab'));
				} else if (urlParams.has('module') && urlParams.has('entryid')) {
					methods.loadModule(urlParams.get('module'), { method: 'id', entryid: urlParams.get('entryid') });
				} else if (urlParams.has('module')) {
					methods.loadModule(urlParams.get('module'));
				} else {
					methods.loadModule();
				}

			});
		},

		////////////////////////////////////////////////////////////////////////////////////////////////////
		// system navi

		_initSystemNavi: function(base3manager) {
			$(".systemnavi > ul > li", base3manager)
				.mouseenter(function() { $(this).children("ul").show(); })
				.mouseleave(function() { $(this).children("ul").hide(); });
			$('.systemnavi > .toggle', base3manager).on('click', function(e) {
				e.preventDefault();
				$(this).siblings('ul').toggleClass('active');
			});
			$('.systemnavi a', base3manager).on('click', function(e) {
				$(this).parents('ul.active').removeClass('active');
			});
		},

		////////////////////////////////////////////////////////////////////////////////////////////////////
		// data

		getContext: function() {
			return methods.b3m.data('context');
		},

		setContext: function(context) {
			methods.b3m.data('context', context);
		},

		getLocked: function() {
			return methods.b3m.data('locked') > 0;
		},

		setLocked: function(locked) {
			methods.b3m.data('locked', locked ? 1 : 0);
		},

		getDataLoading: function() {
			return methods.b3m.data('dataLoading') > 0;
		},

		setDataLoading: function(dataLoading) {
			methods.b3m.data('dataLoading', dataLoading ? 1 : 0);
		},

		getDataLoaded: function() {
			return methods.b3m.data('dataLoaded') > 0;
		},

		setDataLoaded: function(dataLoaded) {
			methods.b3m.data('dataLoaded', dataLoaded ? 1 : 0);
		},

		////////////////////////////////////////////////////////////////////////////////////////////////////
		// scope

		getScope: function() {
			return methods.b3m.data('scope');
		},

		_setScope: function(scope) {
			methods.b3m.data('scope', scope);
		},

		loadScope: function() {

			var numArgs = arguments.length;
			var scope = numArgs >= 1 ? arguments[0] : "";
			var reloadContent = numArgs >= 2 ? arguments[1] : true;

			methods._setScope(scope);

			$("#modulenavi").load("?name=modulenavi&scope=" + scope, function() {
				var module = methods.getModule();
				$('a[rel="' + module + '"]').parent().addClass("active");
				methods._initModules();
				if (!reloadContent) return;
				var module = $('#modulenavi a[rel="' + module + '"]').length
					? module
					: $("#modulenavi li:first a").attr("rel");
				methods.loadModule(module);
			});
		},

		////////////////////////////////////////////////////////////////////////////////////////////////////
		// module

		getModule: function() {
			return methods.b3m.data('module');
		},

		setModule: function(module) {
			methods.b3m.data('module', module);
		},

		_initModules: function() {
			$("#modulenavi a").on('click', function(e) {
				e.preventDefault();
				var module = $(this).attr("rel");
				methods.loadModule(module);
				$(this).parents('ul.active').removeClass('active');
			});
			$('#modulenavi > .toggle').on('click', function(e) {
				e.preventDefault();
				$(this).siblings('ul').toggleClass('active');
			});
		},

		loadModule: function() {

			var numArgs = arguments.length;
			var module = numArgs >= 1 ? arguments[0] : methods.getModule();
			var context = numArgs >= 2 ? arguments[1] : null;
			var tab = numArgs >= 3 ? arguments[2] : '';

			if (!module || !module.length) return;

/*
			// TODO
			var scope = $('#base3manager').base3manager('getScope');
			if (scope.length && numArgs >= 1) {
				var url = "?scope=" + scope + "&module=" + module;
				if (entryId) url += "&entryid=" + currentEntryId;
				if (tab.length) url += "&tab=" + tab;
				history.pushState({}, document.title, url);
			}
*/

			$("#modulenavi li").removeClass("active");
			$('a[rel="' + module + '"]').parent().addClass("active");

			methods.setModule(module);
			methods.setDataLoaded(false);

console.log({ "trigger": 'loadData', "payload": [ module, context ] });
			methods.b3m.trigger('loadData', [ module, context ]);

			methods.loadSubnavi(module);
			methods.loadToolbar(module);
			methods.loadHeader(module);
			methods.loadTabs(module, tab);
		},

		////////////////////////////////////////////////////////////////////////////////////////////////////
		// header

		getHeaderLoaded: function() {
			return methods.b3m.data('headerLoaded') > 0;
		},

		_setHeaderLoaded: function(headerLoaded) {
			methods.b3m.data('headerLoaded', headerLoaded ? 1 : 0);
		},

		loadHeader: function(alias) {
console.log({ "trigger": 'destroyHeader', "payload": [] });
			methods.b3m.trigger('destroyHeader', []);
console.log({ "trigger": 'destroyContent', "payload": null });
			$('#modulehead').trigger('destroyContent');
			methods._setHeaderLoaded(false);
			$('#modulehead').load('?name=header&alias=' + alias, methods.getContext(), function() {
				methods._setHeaderLoaded(true);
				methods.initHeader();
			});
		},

		initHeader: function() {
			if (!methods.b3m.base3manager('getDataLoaded') || !methods.b3m.base3manager('getHeaderLoaded')) return;
console.log({ "trigger": 'headerLoaded', "payload": [] });
			methods.b3m.trigger('headerLoaded', []);
console.log({ "trigger": 'contentLoaded', "payload": null });
			$('#modulehead').trigger('contentLoaded');
		},

		////////////////////////////////////////////////////////////////////////////////////////////////////
		// subnavi

		loadSubnavi: function(module) {
			$("#subnavi").load("?name=subnavi&alias=" + module, function() {
				$('#subnavi ul a').on('click', function(e) {
					e.preventDefault();
					methods.setLocked(true);
					var url = $(this).attr("href");
					var size = $(this).attr("rev").split("x");
					var title = $(this).attr("title");
					methods.showSubNaviDialog(url, title, size[0], size[1]);
					$(this).parents('ul.active').removeClass('active');
				});
				$('#subnavi > .toggle').on('click', function(e) {
					e.preventDefault();
					$(this).siblings('ul').toggleClass('active');
				});
			});
		},

		showSubNaviDialog: function(url, title, w, h) {
			methods.setLocked(true);
			var subnavidialog = $('<div class="subnavidialog" />').appendTo("body").dialog({
				title: title,
				width: w,
				height: h,
				modal: true,
				open: function () {
					$(this).load(url, methods.getContext(), function() {
console.log({ "trigger": 'contentLoaded1', "payload": null });
						methods.b3m.trigger("contentLoaded");
console.log({ "trigger": 'contentLoaded2', "payload": null });
						$(this).trigger('contentLoaded');
					});
				},
				close: function () {
console.log({ "trigger": 'destroyDialogContent', "payload": [] });
					methods.b3m.trigger('destroyDialogContent', []);
console.log({ "trigger": 'destroyContent', "payload": null });
					$(this).trigger('destroyContent');
					$(".subnavidialog").dialog("destroy").remove();
					$('#base3manager').base3manager('setLocked', false);
				},
				buttons: { "Schließen": function() { $(this).dialog("close"); } }
			});
		},

		////////////////////////////////////////////////////////////////////////////////////////////////////
		// toolbar

		loadToolbar: function(alias) {
			$("#toolbar").load("?name=toolbar&alias=" + alias);
		},

		////////////////////////////////////////////////////////////////////////////////////////////////////
		// tab

		getTab: function() {
			return methods.b3m.data('tab');
		},

		setTab: function(tab) {
			methods.b3m.data('tab', tab);
		},

		getTabsLoaded: function() {
			return methods.b3m.data('tabsLoaded') > 0;
		},

		setTabsLoaded: function(tabsLoaded) {
			methods.b3m.data('tabsLoaded', tabsLoaded ? 1 : 0);
		},

		loadTabs: function(alias, tab) {
			methods.setTabsLoaded(false);
			methods.setContentLoaded(false);

			$("#moduletabs").load("?name=tabs&alias=" + alias, function() {
				$("#moduletabs a").on('click', function() {
					if (methods.getLocked()) {
						alert("Bitte zuerst den Bearbeitungsmodus verlassen.");
						return;
					}
					var tabalias = $(this).attr("rev");
					methods.loadTab(alias, tabalias);
					var scope = methods.getScope();

					// TODO refactoring
					history.pushState({}, document.title, "?scope=" + scope + "&module=" + alias + "&entryid=" + currentEntryId + "&tab=" + tabalias);

					return false;
				});
				if (tab.length) {
					methods.loadTab(alias, tab);
				} else {
					var tabButton = $('#moduletabs a:first');
					if (tabButton.length) methods.loadTab(alias, tabButton.attr("rev"));
				}
				methods.setTabsLoaded(true);
			});
		},

		loadTab: function(alias, tabalias) {
			methods.setTab(tabalias)

			$("#moduletabs li").removeClass("active");
			$('a[rev="' + tabalias + '"]').parent().addClass("active");

			methods._loadContent(alias, tabalias);
		},

		////////////////////////////////////////////////////////////////////////////////////////////////////
		// content

		getContentLoaded: function() {
			return methods.b3m.data('contentLoaded') > 0;
		},

		setContentLoaded: function(contentLoaded) {
			methods.b3m.data('contentLoaded', contentLoaded ? 1 : 0);
		},

		_loadContent: function(alias, tabalias) {
console.log({ "trigger": 'destroyContent1', "payload": [] });
			methods.b3m.trigger('destroyContent', []);
console.log({ "trigger": 'destroyContent2', "payload": null });
			$('#content').trigger('destroyContent');
			$("#content").load("?name=content&alias=" + alias + "&tabalias=" + tabalias, methods.getContext(), function() {
				methods.setContentLoaded(true);
				methods.initContent();
			});
		},

		initContent: function() {
			if (!methods.b3m.base3manager('getDataLoaded') || !methods.b3m.base3manager('getContentLoaded')) return;
console.log({ "trigger": 'contentLoaded1', "payload": null });
			methods.b3m.trigger("contentLoaded");
console.log({ "trigger": 'contentLoaded2', "payload": null });
			$("#content").trigger('contentLoaded');
		}
	};

	$.fn.base3manager = function(method) {

		if ( methods[method] ) {
			return methods[method].apply( this, Array.prototype.slice.call( arguments, 1 ));
		} else if ( typeof method === 'object' || ! method ) {
			return methods.init.apply( this, arguments );
		} else {
			$.error( 'Method ' +  method + ' does not exist on jQuery.base3manager' );
		}    

	};

})(jQuery, window);

$(function() {
	$('#base3manager').base3manager();
});

