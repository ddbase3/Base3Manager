(function($) {

	$.event.special.widthChanged = {

		remove: function() {
			$(this).children('iframe.width-changed').remove();
		},

		add: function () {
			var elm = $(this);
			var iframe = elm.children('iframe.width-changed');
			if (!iframe.length) {
				iframe = $('<iframe/>').addClass('width-changed').css({
					"width": "100%",
					"display": "block",
					"border": 0,
					"height": 0,
					"margin": 0
				}).prependTo(this);
			}
			var oldWidth = elm.width();
			function elmResized() {
				var width = elm.width();
				if (oldWidth != width) {
					elm.trigger('widthChanged', [width, oldWidth]);
					oldWidth = width;
				}
			}

			var timer = 0;
			var ielm = iframe[0];
			(ielm.contentWindow || ielm).onresize = function() {
				clearTimeout(timer);
				timer = setTimeout(elmResized, 20);
			};
		}

	};

	var methods = {

		init: function(options) {
			return this.each(function() {
				var opt = $.extend({
					data: {}
				}, options);

				var mm = $(this);
				mm.addClass("base3mindmap");
				methods._draw(mm, opt.data);
				methods._beziers(mm);

				mm.on('widthChanged', function() {
					methods._beziers(mm);
				});

			});
		},

		_draw: function(m, data, dir) {
			methods._drawSub(m, data, dir, "left");
			if (data.name) {
				var styles = '';
				if (data.color) styles += 'color:' + data.color + ';';
				if (data.bold) styles += 'font-weight:bold;';
				if (data.italic) styles += 'font-style:italic;';
				if (styles.length) styles = ' style="' + styles + '"';
				var name = data.name;
				if (data.icon) name = '<img src="' + data.icon + '" style="height:10px;" /> ' + name;
				if (data.link) name = '<a href="' + data.link + '" target="_blank">' + name + '</a>';
				var name = $('<div><span' + styles + '>' + name + '</span></div>').addClass("cell").appendTo(m);
				if (typeof dir === "undefined") name.addClass("root");
			}
			methods._drawSub(m, data, dir, "right");
		},

		_drawSub: function(m, data, dir, subdir) {
			if (!data.sub) return;
			var sub = {};
			for (var i in data.sub) {
				var d = dir || data.sub[i].dir || "right";
				if (d != subdir) continue;
				if (!sub[d]) {
					sub[d] = $('<div></div>').addClass("cell").appendTo(m);
					if (typeof dir === "undefined") sub[d].addClass(d);
				}
				var node = $('<div class="node"></div>').appendTo(sub[d])
				methods._draw(node, data.sub[i], d);
			}
		},

		_beziers: function(m) {
			var c = $('#canvas', m);
			if (!c.length) c = $('<canvas id="canvas"></canvas>').appendTo(m);
			var o = c.offset();
			var canvas = document.getElementById('canvas');
			if (canvas.getContext) {
				var ctx = canvas.getContext('2d');
				ctx.canvas.width = m.width();
				ctx.canvas.height = m.height();
				ctx.clearRect(0, 0, ctx.canvas.width, ctx.canvas.height);
				$('span', m).each(function() {

					var root = $(this).parent().hasClass("root");
					var p1 = $(this).offset();
					var p1h = root ? $(this).outerHeight() / 2 : $(this).height();
					var p1w = root ? $(this).outerWidth() : $(this).width();
					var p1x = p1.left - o.left;
					var p1y = p1.top - o.top + p1h;

					ctx.beginPath();
					ctx.moveTo(p1x, p1y + 1);
					ctx.lineTo(p1x + p1w, p1y + 1);
					ctx.stroke();

					$(this).parent().siblings('div').children('.node').children('.cell').children('span').each(function() {

						var left = $(this).parents('.left').length;
						var _p1x = p1x + (left ? 0 : p1w);
						var p2 = $(this).offset();
						var p2w = $(this).width();
						var p2h = $(this).height();
						var p2x = p2.left - o.left + (left ? p2w : 0);
						var p2y = p2.top - o.top + p2h;

						ctx.beginPath();
						ctx.moveTo(_p1x, p1y + 1);
						ctx.bezierCurveTo(_p1x + (p2x - _p1x)/2, p1y + 1, _p1x + (p2x - _p1x)/2, p2y + 1, p2x, p2y + 1);
						ctx.stroke();
					});
				});


			}
		}

	};

	$.fn.mindmap = function(method) {

		if ( methods[method] ) {
			return methods[method].apply( this, Array.prototype.slice.call( arguments, 1 ));
		} else if ( typeof method === 'object' || ! method ) {
			return methods.init.apply( this, arguments );
		} else {
			$.error( 'Method ' +  method + ' does not exist on jQuery.mindmap' );
		}    

	};

})(jQuery);
