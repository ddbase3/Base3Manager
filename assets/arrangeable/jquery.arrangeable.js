/**
 * jQuery arrangeable plugin
 * This jQuery plugin was inspired on ... by ... (http://...),
 * based on jQuery by ... (http://...) and jQuery UI Draggable by ... (http://...)
 * and adapted to me for use like a plugin of jQuery.
 * @name jquery.arrangeable.js
 * @author Daniel Dahme - http://www.base3.de
 * @version 0.2
 * @date August 16, 2012
 * @category jQuery plugin
 * @copyright (c) 2012 Daniel Dahme (www.base3.de)
 * @license MIT (http://...), GPL (http://...)
 * @example ...
 */

(function($) {

	var methods = {

		init: function(options) {

			return this.each(function() {

				var opt = $.extend({
					angle: 0,
					cursor: "auto",			// css cursors
					snap: 0,
					key: "shift",			// shift | alt | ctrl | none
					start: function(e, ui) {},
					rotate: function(e, ui) {},
					stop: function(e, ui) {}
				}, options);

				var object = $(this);
				var imgStartAngle, mouseStartAngle;
				var rotating = false;

				
				$(document)
					.on("mousemove.arrangeable", function(e) {
						if (!rotating) return;
						var c = methods._centre(rotating);
						currAngle = Math.atan2(e.pageY - c[1], e.pageX - c[0]) * 180 / Math.PI;
						newAngle = imgStartAngle + (currAngle - mouseStartAngle);
						if (opt.snap) newAngle = Math.round(newAngle/opt.snap) * opt.snap;
						methods._rotate(rotating, newAngle);
						opt.rotate.apply(this, [e, { element: rotating, angle: newAngle }]);
					})
					.on("mouseup.arrangeable", function(e) {
						if (!rotating) return;
						rotating.parents().css('cursor', 'auto');
						opt.stop.apply(this, [e, { element: rotating, angle: object.data('currentRotation') }]);
						window.setTimeout(function() { rotating = false; }, 10);
					})
					.on("click.arrangeable", function(e, ui) {
						if (!rotating) return;
						e.preventDefault();
						e.stopImmediatePropagation();
						return false;
					});


				object
					.on("mousedown.arrangeable", function(e) {
						if (object.data("rotatable_disabled") == 1) return;
						if ((opt.key == "shift" && e.shiftKey) || (opt.key == "alt" && e.altKey) || (opt.key == "ctrl" && e.ctrlKey) || opt.key == "none") {
							rotating = object;
							object.parents().css('cursor', opt.cursor);
							imgStartAngle = object.data('currentRotation') || 0;
							var c = methods._centre($(this));
							mouseStartAngle = methods._rad2deg(Math.atan2(e.pageY - c[1], e.pageX - c[0]));
							opt.start.apply(this, [e, { element: rotating, angle: imgStartAngle }]);
						}
					})
					.on("dragstart.arrangeable", function(e, ui) {
						if (rotating) return false;
					})
					.on("drag.arrangeable", function(e, ui) {
						if (rotating) return false;
					})
					.on("dragstop.arrangeable", function(e, ui) {
						if (rotating) return false;
					})
					.on("click.arrangeable", function(e, ui) {
						if (!rotating) return;
						e.preventDefault();
						e.stopImmediatePropagation();
						return false;
					});

					
				methods._rotate(object, parseFloat(methods._parseval(opt.angle, 0)));

			});
		},

		destroy: function() {
			return this.each(function() {
				$(this).off(".arrangeable");
				$(document).off(".arrangeable");
			});
		},

		disable: function() {
			return this.each(function() {
				$(this).data("rotatable_disabled", 1)
			});
		},

		enable: function() {
			return this.each(function() {
				$(this).data("rotatable_disabled", 0)
			});
		},

		rotate: function(deg) {
			return this.each(function() {
				methods._rotate($(this), deg);
			});
		},

		_rotate: function(obj, deg) {
			obj.css('transform', 'rotate(' + deg + 'deg)');
			obj.css('-moz-transform', 'rotate(' + deg + 'deg)');
			obj.css('-webkit-transform', 'rotate(' + deg + 'deg)');
			obj.css('-o-transform', 'rotate(' + deg + 'deg)');
			obj.css({ msTransform: 'rotate(' + deg + 'deg)' });
			obj.css('-khtml-transform', 'rotate(' + deg + 'deg)');
var s = methods._size(obj);
$("#width").val(s[0]);
$("#height").val(s[1]);
			if ($.browser.msie && $.browser.version < 9.0) {
				var rad = methods._deg2rad(deg);
				var sin = Math.sin(rad);
				var cos = Math.cos(rad);
				var width = obj.width();
				var height = obj.height();
				var w = Math.abs(height*sin) + Math.abs(width*cos);
				var h = Math.abs(height*cos) + Math.abs(width*sin);
				var left = parseInt((width-w)/2);
				var top = parseInt((height-h)/2);
				obj.css('filter', "progid:DXImageTransform.Microsoft.Matrix(M11="+cos+", M12="+(-sin)+", M21="+sin+", M22="+cos+", SizingMethod='auto expand')");
				obj.css('margin', top+'px '+left+'px');  // buggy !!!

/*
$("#deg").val(deg);
$("#w").val(w);
$("#h").val(h);
*/
			}
			obj.data('currentRotation', deg);
		},

		_centre: function(obj) {
			var curr = obj.data('currentRotation');
			methods._rotate(obj, 0);
			var offset = obj.offset();
			var cx = offset.left + obj.width() / 2;
			var cy = offset.top + obj.height() / 2;
			methods._rotate(obj, curr);
			return Array(cx, cy);
		},

		_size: function(obj) {
			var w = obj.width();
			var h = obj.height();
			if ($.browser.msie && $.browser.version < 9.0) {
				var deg = obj.data('currentRotation');
				var rad = methods._deg2rad(deg);
				var sin = Math.sin(rad);
				var cos = Math.cos(rad);
				var width = ( w - h*sin/cos ) / ( 1 - sin*sin / cos );
				var height = (h - width * sin) / cos;
$("#w").val(width);
$("#h").val(height);
			}
			return Array(w, h);
		},

		_deg2rad: function(deg) {
			return deg * Math.PI / 180;
		},

		_rad2deg: function(rad) {
			return rad * 180 / Math.PI;
		},

		_parseval: function(value, defaultvalue) {
			if ($.isFunction(value)) return value();
			if (!value) return defaultvalue;
			return value;
		}

	};

	$.fn.arrangeable = function(method) {

		if ( methods[method] ) {
			return methods[method].apply( this, Array.prototype.slice.call( arguments, 1 ));
		} else if ( typeof method === 'object' || ! method ) {
			return methods.init.apply( this, arguments );
		} else {
			$.error( 'Method ' +  method + ' does not exist on jQuery.arrangeable' );
		}    

	};

})(jQuery);
