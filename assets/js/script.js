(function($, window) {

	var methods = {

		b3m: null,
		_eventNs: '.base3manager',
		_headerRequestId: 0,
		_subnaviRequestId: 0,
		_toolbarRequestId: 0,
		_tabsRequestId: 0,
		_contentRequestId: 0,

		init: function(options) {
			return this.each(function() {
				var opt = $.extend({
					data: {}
				}, options);

				methods.b3m = $(this);
				methods.b3m.addClass('base3manager');
				methods.b3m.data('opt', opt);

				methods.setContext(null);
				methods.setLocked(false);
				methods.setDataLoading(false);
				methods.setDataLoaded(false);
				methods._setScope('');
				methods.setModule('');
				methods.setTab('');
				methods._setHeaderLoaded(false);
				methods.setTabsLoaded(false);
				methods.setContentLoaded(false);

				$(window)
					.off('popstate' + methods._eventNs)
					.on('popstate' + methods._eventNs, function(e) {
						if (e.originalEvent.state !== null) location.reload();
					});

				methods._initSystemNavi(methods.b3m);

				const queryString = window.location.search;
				const urlParams = new URLSearchParams(queryString);

				methods.loadScope(urlParams.has('scope') ? urlParams.get('scope') : "", !urlParams.has('module'));

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

		_updateClasses: function() {
			var classStr = 'base3manager ' + methods.b3m.data('scope') + ' ' + methods.b3m.data('module');
			methods.b3m.attr('class', classStr);
		},

		////////////////////////////////////////////////////////////////////////////////////////////////////
		// system navi

		_initSystemNavi: function(base3manager) {
			$(".systemnavi > ul > li", base3manager)
				.off('mouseenter' + methods._eventNs + ' mouseleave' + methods._eventNs)
				.on('mouseenter' + methods._eventNs, function() { $(this).children("ul").show(); })
				.on('mouseleave' + methods._eventNs, function() { $(this).children("ul").hide(); });

			$('.systemnavi > .toggle', base3manager)
				.off('click' + methods._eventNs)
				.on('click' + methods._eventNs, function(e) {
					e.preventDefault();
					$(this).siblings('ul').toggleClass('active');
				});

			$('.systemnavi a', base3manager)
				.off('click' + methods._eventNs)
				.on('click' + methods._eventNs, function() {
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

			$("#modulenavi").load("?name=modulenavi&scope=" + scope, function(responseText, textStatus) {
				var currentModule;
				var targetModule;

				if (textStatus != 'success') return;

				currentModule = methods.getModule();
				if (currentModule && currentModule.length) $('a[rel="' + currentModule + '"]').parent().addClass("active");

				methods._initModules();

				if (!reloadContent) return;

				targetModule = $('#modulenavi a[rel="' + currentModule + '"]').length
					? currentModule
					: $("#modulenavi li:first a").attr("rel");

				if (targetModule && targetModule.length) methods.loadModule(targetModule);
			});

			methods.b3m.data("scope", scope);
			methods._updateClasses();
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
			var modulenavi = $("#modulenavi");

			modulenavi
				.off('click' + methods._eventNs, 'a')
				.on('click' + methods._eventNs, 'a', function(e) {
					e.preventDefault();
					var module = $(this).attr("rel");
					methods.loadModule(module);
					$(this).parents('ul.active').removeClass('active');
				});

			modulenavi
				.off('click' + methods._eventNs, '.toggle')
				.on('click' + methods._eventNs, '.toggle', function(e) {
					e.preventDefault();
					$(this).siblings('ul').toggleClass('active');
				});
		},

		loadModule: function() {
			var numArgs = arguments.length;
			var module = numArgs >= 1 ? arguments[0] : methods.getModule();
			var context = numArgs >= 2 ? arguments[1] : null;
			var tab = numArgs >= 3 ? arguments[2] : '';
			var previousModule = methods.getModule();

			if (!module || !module.length) return;

			$("#modulenavi li").removeClass("active");
			$('a[rel="' + module + '"]').parent().addClass("active");

			methods.setModule(module);
			methods.setDataLoaded(false);
			methods._setHeaderLoaded(false);
			methods.setTabsLoaded(false);
			methods.setContentLoaded(false);

			methods.b3m.trigger('loadData', [ module, context, previousModule ]);

			methods.loadSubnavi(module);
			methods.loadToolbar(module);
			methods.loadHeader(module);
			methods.loadTabs(module, tab);

			methods.b3m.data("module", module);
			methods._updateClasses();
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
			var requestId = ++methods._headerRequestId;

			methods.b3m.trigger('destroyHeader', []);
			$('#modulehead').trigger('destroyContent');
			methods._setHeaderLoaded(false);

			$('#modulehead').load('?name=header&alias=' + alias, methods.getContext(), function(responseText, textStatus) {
				if (requestId != methods._headerRequestId) return;
				if (textStatus != 'success') return;

				methods._setHeaderLoaded(true);
				methods.initHeader();
			});
		},

		initHeader: function() {
			if (!methods.b3m.base3manager('getDataLoaded') || !methods.b3m.base3manager('getHeaderLoaded')) return;
			methods.b3m.trigger('headerLoaded', []);
			$('#modulehead').trigger('contentLoaded');
		},

		////////////////////////////////////////////////////////////////////////////////////////////////////
		// subnavi

		_initSubnavi: function() {
			var subnavi = $('#subnavi');

			subnavi
				.off('click' + methods._eventNs, 'ul a')
				.on('click' + methods._eventNs, 'ul a', function(e) {
					e.preventDefault();
					var url = $(this).attr("href");
					var size = $(this).attr("rev").split("x");
					var title = $(this).attr("title");
					methods.showSubNaviDialog(url, title, size[0], size[1]);
					$(this).parents('ul.active').removeClass('active');
				});

			subnavi
				.off('click' + methods._eventNs, '.toggle')
				.on('click' + methods._eventNs, '.toggle', function(e) {
					e.preventDefault();
					$(this).siblings('ul').toggleClass('active');
				});
		},

		loadSubnavi: function(module) {
			var requestId = ++methods._subnaviRequestId;

			$("#subnavi").load("?name=subnavi&alias=" + module, function(responseText, textStatus) {
				if (requestId != methods._subnaviRequestId) return;
				if (textStatus != 'success') return;
				methods._initSubnavi();
			});
		},

		showSubNaviDialog: function(url, title, w, h) {
			methods.setLocked(true);
			$('<div class="subnavidialog" />').appendTo("body").dialog({
				title: title,
				width: w,
				height: h,
				modal: true,
				open: function() {
					$(this).load(url, methods.getContext(), function() {
						methods.b3m.trigger("dialogLoaded");
						$(this).trigger('contentLoaded');
					});
				},
				close: function() {
					methods.b3m.trigger('destroyDialogContent', []);
					$(this).trigger('destroyContent');
					$(".subnavidialog").dialog("destroy").remove();
					methods.setLocked(false);
				},
				buttons: { "Schließen": function() { $(this).dialog("close"); } }
			});
		},

		////////////////////////////////////////////////////////////////////////////////////////////////////
		// toolbar

		loadToolbar: function(alias) {
			var requestId = ++methods._toolbarRequestId;

			$("#toolbar").load("?name=toolbar&alias=" + alias, function(responseText, textStatus) {
				if (requestId != methods._toolbarRequestId) return;
				if (textStatus != 'success') return;
			});
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
			var requestId = ++methods._tabsRequestId;

			methods.setTabsLoaded(false);
			methods.setContentLoaded(false);

			$("#moduletabs").load("?name=tabs&alias=" + alias, function(responseText, textStatus) {
				if (requestId != methods._tabsRequestId) return;
				if (textStatus != 'success') return;

				$("#moduletabs a").off('click' + methods._eventNs).on('click' + methods._eventNs, function() {
					if (methods.getLocked()) {
						alert("Bitte zuerst den Bearbeitungsmodus verlassen.");
						return false;
					}

					var tabalias = $(this).attr("rev");
					var scope = methods.getScope();

					methods.loadTab(alias, tabalias);

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
			methods.setTab(tabalias);

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
			var requestId = ++methods._contentRequestId;

			methods.setContentLoaded(false);
			methods.b3m.trigger('destroyContent', []);
			$('#content').trigger('destroyContent');

			$("#content").load("?name=content&alias=" + alias + "&tabalias=" + tabalias, methods.getContext(), function(responseText, textStatus) {
				if (requestId != methods._contentRequestId) return;
				if (textStatus != 'success') return;

				methods.setContentLoaded(true);
				methods.initContent();
			});
		},

		initContent: function() {
			if (!methods.b3m.base3manager('getDataLoaded') || !methods.b3m.base3manager('getContentLoaded')) return;
			methods.b3m.trigger("contentLoaded");
			$("#content").trigger('contentLoaded');
		}
	};

	$.fn.base3manager = function(method) {
		if (methods[method]) {
			return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
		} else if (typeof method === 'object' || !method) {
			return methods.init.apply(this, arguments);
		} else {
			$.error('Method ' + method + ' does not exist on jQuery.base3manager');
		}
	};

})(jQuery, window);

$(function() {
	$('#base3manager').base3manager();
});
