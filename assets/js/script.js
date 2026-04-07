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

				methods._replaceHistoryState(methods._parseHistoryState(window.location.search));

				$(window)
					.off('popstate' + methods._eventNs)
					.on('popstate' + methods._eventNs, function() {
						methods._applyHistoryState(methods._parseHistoryState(window.location.search));
					});

				$(document)
					.off('keydown' + methods._eventNs)
					.on('keydown' + methods._eventNs, function(e) {
						methods._handleKeydown(e);
					});

				methods._initSystemNavi(methods.b3m);

				const queryString = window.location.search;
				const urlParams = new URLSearchParams(queryString);

				methods.loadScope(
					urlParams.has('scope') ? urlParams.get('scope') : "",
					!urlParams.has('module'),
					{ historyMode: 'skip' }
				);

				if (urlParams.has('module') && urlParams.has('entryid') && urlParams.has('tab')) {
					methods.loadModule(
						urlParams.get('module'),
						{ method: 'id', entryid: urlParams.get('entryid') },
						urlParams.get('tab'),
						{
							historyMode: 'skip',
							entryHistoryMode: 'skip',
							explicitTabHistoryMode: 'skip',
							defaultTabHistoryMode: 'skip'
						}
					);
				} else if (urlParams.has('module') && urlParams.has('entryid')) {
					methods.loadModule(
						urlParams.get('module'),
						{ method: 'id', entryid: urlParams.get('entryid') },
						'',
						{
							historyMode: 'skip',
							entryHistoryMode: 'skip',
							explicitTabHistoryMode: 'skip',
							defaultTabHistoryMode: 'skip'
						}
					);
				} else if (urlParams.has('module')) {
					methods.loadModule(
						urlParams.get('module'),
						null,
						'',
						{
							historyMode: 'skip',
							entryHistoryMode: 'skip',
							explicitTabHistoryMode: 'skip',
							defaultTabHistoryMode: 'skip'
						}
					);
				} else {
					methods.loadModule(
						null,
						null,
						'',
						{
							historyMode: 'skip',
							entryHistoryMode: 'skip',
							explicitTabHistoryMode: 'skip',
							defaultTabHistoryMode: 'skip'
						}
					);
				}
			});
		},

		_updateClasses: function() {
			var classStr = 'base3manager ' + methods.b3m.data('scope') + ' ' + methods.b3m.data('module');
			methods.b3m.attr('class', classStr);
		},

		////////////////////////////////////////////////////////////////////////////////////////////////////
		// history

		_normalizeHistoryState: function(state) {
			var normalized = $.extend({
				scope: '',
				module: '',
				tab: '',
				entryid: 0
			}, state || {});

			normalized.scope = normalized.scope ? String(normalized.scope) : '';
			normalized.module = normalized.module ? String(normalized.module) : '';
			normalized.tab = normalized.tab ? String(normalized.tab) : '';
			normalized.entryid = parseInt(normalized.entryid, 10);
			normalized.entryid = isNaN(normalized.entryid) || normalized.entryid < 1 ? 0 : normalized.entryid;

			if (!normalized.module.length) {
				normalized.tab = '';
				normalized.entryid = 0;
			}

			return normalized;
		},

		_parseHistoryState: function(search) {
			var query = typeof search === 'string' ? search : window.location.search;
			var urlParams = new URLSearchParams(query);

			return methods._normalizeHistoryState({
				scope: urlParams.has('scope') ? urlParams.get('scope') : '',
				module: urlParams.has('module') ? urlParams.get('module') : '',
				tab: urlParams.has('tab') ? urlParams.get('tab') : '',
				entryid: urlParams.has('entryid') ? urlParams.get('entryid') : 0
			});
		},

		_getCurrentHistoryState: function() {
			var entryId = 0;

			if (typeof window.currentEntryId !== 'undefined') {
				entryId = parseInt(window.currentEntryId, 10);
				entryId = isNaN(entryId) || entryId < 1 ? 0 : entryId;
			}

			return methods._normalizeHistoryState({
				scope: methods.getScope(),
				module: methods.getModule(),
				tab: methods.getTab(),
				entryid: entryId
			});
		},

		_buildHistoryUrl: function(state) {
			var normalized = methods._normalizeHistoryState(state);
			var params = new URLSearchParams();

			if (normalized.scope.length) params.set('scope', normalized.scope);
			if (normalized.module.length) params.set('module', normalized.module);
			if (normalized.entryid > 0) params.set('entryid', normalized.entryid);
			if (normalized.tab.length) params.set('tab', normalized.tab);

			var query = params.toString();

			return window.location.pathname + (query.length ? '?' + query : '');
		},

		_replaceHistoryState: function(state) {
			if (!window.history || !window.history.replaceState) return;

			var normalized = methods._normalizeHistoryState(state);
			var url = methods._buildHistoryUrl(normalized);

			window.history.replaceState(normalized, document.title, url);
		},

		updateHistory: function(values, historyMode) {
			var mode = historyMode || 'push';
			var state;
			var url;
			var currentUrl;

			if (mode == 'skip') return;
			if (!window.history || !window.history.pushState) return;

			state = methods._normalizeHistoryState($.extend({}, methods._getCurrentHistoryState(), values || {}));
			url = methods._buildHistoryUrl(state);
			currentUrl = window.location.pathname + window.location.search;

			if (url == currentUrl) {
				if (mode == 'replace' && window.history.replaceState) {
					window.history.replaceState(state, document.title, url);
				}
				return;
			}

			if (mode == 'replace' && window.history.replaceState) {
				window.history.replaceState(state, document.title, url);
				return;
			}

			window.history.pushState(state, document.title, url);
		},

		_applyHistoryState: function(state) {
			var normalized = methods._normalizeHistoryState(state);
			var context = normalized.entryid > 0
				? { method: 'id', entryid: normalized.entryid }
				: null;

			if (normalized.scope != methods.getScope()) {
				methods.loadScope(normalized.scope, !normalized.module.length, {
					historyMode: 'skip',
					onLoaded: function() {
						if (!normalized.module.length) return;

						methods.loadModule(normalized.module, context, normalized.tab, {
							historyMode: 'skip',
							entryHistoryMode: 'skip',
							explicitTabHistoryMode: 'skip',
							defaultTabHistoryMode: 'skip'
						});
					}
				});
				return;
			}

			if (normalized.module.length) {
				methods.loadModule(normalized.module, context, normalized.tab, {
					historyMode: 'skip',
					entryHistoryMode: 'skip',
					explicitTabHistoryMode: 'skip',
					defaultTabHistoryMode: 'skip'
				});
				return;
			}

			methods.loadScope(normalized.scope, true, {
				historyMode: 'skip'
			});
		},

		////////////////////////////////////////////////////////////////////////////////////////////////////
		// hotkeys

		_hasOpenDialog: function() {
			return $('.ui-dialog:visible').length > 0 || $('.ui-widget-overlay:visible').length > 0;
		},

		_isEditableTarget: function(target) {
			var element = $(target);

			if (!element.length) return false;
			if (element.is('input, textarea, select')) return true;
			if (element.prop('isContentEditable')) return true;
			if (element.closest('[contenteditable="true"]').length) return true;

			return false;
		},

		_normalizeKey: function(e) {
			var key = e.key || '';

			if (key == 'Esc') key = 'Escape';
			if (key == 'Del') key = 'Delete';
			if (key.length == 1) key = key.toLowerCase();

			return key;
		},

		_handleKeydown: function(e) {
			var key = methods._normalizeKey(e);
			var editableTarget = methods._isEditableTarget(e.target);
			var handled = false;

			if (!methods.b3m || !methods.b3m.length) return;
			if (!methods.getModule() || !methods.getModule().length) return;
			if (e.repeat) return;
			if (methods.getDataLoading()) return;
			if (methods._hasOpenDialog()) return;
			if (editableTarget) return;
			if (e.ctrlKey || e.altKey || e.metaKey) return;

			if (key == 't') {
				handled = e.shiftKey
					? methods.loadPrevTab()
					: methods.loadNextTab();

				if (handled) e.preventDefault();
				return;
			}

			if (key == 'm') {
				handled = e.shiftKey
					? methods.loadPrevModule()
					: methods.loadNextModule();

				if (handled) e.preventDefault();
			}
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
			var options = numArgs >= 3 ? arguments[2] : {};
			var historyMode = options.historyMode || 'push';

			methods._setScope(scope);

			methods.updateHistory({
				scope: scope,
				module: '',
				tab: '',
				entryid: 0
			}, historyMode);

			$("#modulenavi").load("?name=modulenavi&scope=" + scope, function(responseText, textStatus) {
				var currentModule;
				var targetModule;

				if (textStatus != 'success') return;

				currentModule = methods.getModule();
				if (currentModule && currentModule.length) $('a[rel="' + currentModule + '"]').parent().addClass("active");

				methods._initModules();

				if (reloadContent) {
					targetModule = $('#modulenavi a[rel="' + currentModule + '"]').length
						? currentModule
						: $("#modulenavi li:first a").attr("rel");

					if (targetModule && targetModule.length) {
						methods.loadModule(targetModule, null, '', {
							historyMode: historyMode == 'skip' ? 'skip' : 'replace',
							entryHistoryMode: historyMode == 'skip' ? 'skip' : 'replace',
							explicitTabHistoryMode: historyMode == 'skip' ? 'skip' : 'replace',
							defaultTabHistoryMode: historyMode == 'skip' ? 'skip' : 'replace'
						});
					}
				}

				if ($.isFunction(options.onLoaded)) options.onLoaded();
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

		_getModuleButtons: function() {
			return $("#modulenavi a[rel]:visible");
		},

		_getRelativeModuleAlias: function(step) {
			var moduleButtons = methods._getModuleButtons();
			var currentModule = methods.getModule();
			var currentIndex = -1;
			var targetIndex;

			if (!moduleButtons.length) return '';

			moduleButtons.each(function(i) {
				if ($(this).attr("rel") == currentModule) {
					currentIndex = i;
					return false;
				}
			});

			if (currentIndex < 0) currentIndex = 0;

			targetIndex = currentIndex + step;

			if (targetIndex < 0) targetIndex = moduleButtons.length - 1;
			if (targetIndex >= moduleButtons.length) targetIndex = 0;

			return moduleButtons.eq(targetIndex).attr("rel") || '';
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

		loadNextModule: function(historyMode) {
			var targetModule;

			if (methods.getLocked()) {
				alert("Bitte zuerst den Bearbeitungsmodus verlassen.");
				return false;
			}

			targetModule = methods._getRelativeModuleAlias(1);
			if (!targetModule.length) return false;

			methods.loadModule(targetModule, null, '', {
				historyMode: historyMode || 'push'
			});
			return true;
		},

		loadPrevModule: function(historyMode) {
			var targetModule;

			if (methods.getLocked()) {
				alert("Bitte zuerst den Bearbeitungsmodus verlassen.");
				return false;
			}

			targetModule = methods._getRelativeModuleAlias(-1);
			if (!targetModule.length) return false;

			methods.loadModule(targetModule, null, '', {
				historyMode: historyMode || 'push'
			});
			return true;
		},

		loadModule: function() {
			var numArgs = arguments.length;
			var module = numArgs >= 1 ? arguments[0] : methods.getModule();
			var context = numArgs >= 2 ? arguments[1] : null;
			var tab = numArgs >= 3 ? arguments[2] : '';
			var options = numArgs >= 4 ? arguments[3] : {};
			var historyMode = options.historyMode || 'push';
			var entryHistoryMode = typeof options.entryHistoryMode !== 'undefined'
				? options.entryHistoryMode
				: (historyMode == 'skip' ? 'skip' : 'replace');
			var explicitTabHistoryMode = typeof options.explicitTabHistoryMode !== 'undefined'
				? options.explicitTabHistoryMode
				: historyMode;
			var defaultTabHistoryMode = typeof options.defaultTabHistoryMode !== 'undefined'
				? options.defaultTabHistoryMode
				: (historyMode == 'skip' ? 'skip' : 'replace');
			var previousModule = methods.getModule();

			if (!module || !module.length) return;

			$("#modulenavi li").removeClass("active");
			$('a[rel="' + module + '"]').parent().addClass("active");

			methods.setModule(module);
			methods.setTab('');
			methods.setDataLoaded(false);
			methods._setHeaderLoaded(false);
			methods.setTabsLoaded(false);
			methods.setContentLoaded(false);

			methods.updateHistory({
				module: module,
				tab: '',
				entryid: 0
			}, historyMode);

			methods.b3m.trigger('loadData', [ module, context, previousModule, entryHistoryMode ]);

			methods.loadSubnavi(module);
			methods.loadToolbar(module);
			methods.loadHeader(module);
			methods.loadTabs(module, tab, {
				explicitTabHistoryMode: explicitTabHistoryMode,
				defaultTabHistoryMode: defaultTabHistoryMode
			});

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

		_getTabButtons: function() {
			return $("#moduletabs a");
		},

		_getRelativeTabAlias: function(step) {
			var tabButtons = methods._getTabButtons();
			var currentTab = methods.getTab();
			var currentIndex = -1;
			var targetIndex;

			if (!tabButtons.length) return '';

			tabButtons.each(function(i) {
				if ($(this).attr("rev") == currentTab) {
					currentIndex = i;
					return false;
				}
			});

			if (currentIndex < 0) currentIndex = 0;

			targetIndex = currentIndex + step;

			if (targetIndex < 0) targetIndex = tabButtons.length - 1;
			if (targetIndex >= tabButtons.length) targetIndex = 0;

			return tabButtons.eq(targetIndex).attr("rev") || '';
		},

		loadNextTab: function(historyMode) {
			var module = methods.getModule();
			var targetTab;

			if (!module || !module.length) return false;
			if (methods.getLocked()) {
				alert("Bitte zuerst den Bearbeitungsmodus verlassen.");
				return false;
			}

			targetTab = methods._getRelativeTabAlias(1);
			if (!targetTab.length) return false;

			methods.loadTab(module, targetTab, historyMode || 'push');
			return true;
		},

		loadPrevTab: function(historyMode) {
			var module = methods.getModule();
			var targetTab;

			if (!module || !module.length) return false;
			if (methods.getLocked()) {
				alert("Bitte zuerst den Bearbeitungsmodus verlassen.");
				return false;
			}

			targetTab = methods._getRelativeTabAlias(-1);
			if (!targetTab.length) return false;

			methods.loadTab(module, targetTab, historyMode || 'push');
			return true;
		},

		loadTabs: function() {
			var numArgs = arguments.length;
			var alias = numArgs >= 1 ? arguments[0] : '';
			var tab = numArgs >= 2 ? arguments[1] : '';
			var options = numArgs >= 3 ? arguments[2] : {};
			var explicitTabHistoryMode = typeof options.explicitTabHistoryMode !== 'undefined'
				? options.explicitTabHistoryMode
				: 'push';
			var defaultTabHistoryMode = typeof options.defaultTabHistoryMode !== 'undefined'
				? options.defaultTabHistoryMode
				: 'replace';
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
					methods.loadTab(alias, tabalias, explicitTabHistoryMode);

					return false;
				});

				if (tab.length) {
					methods.loadTab(alias, tab, explicitTabHistoryMode);
				} else {
					var tabButton = $('#moduletabs a:first');
					if (tabButton.length) methods.loadTab(alias, tabButton.attr("rev"), defaultTabHistoryMode);
				}

				methods.setTabsLoaded(true);
			});
		},

		loadTab: function(alias, tabalias, historyMode) {
			var mode = historyMode || 'push';

			methods.setTab(tabalias);

			$("#moduletabs li").removeClass("active");
			$('a[rev="' + tabalias + '"]').parent().addClass("active");

			methods.updateHistory({
				tab: tabalias
			}, mode);

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
