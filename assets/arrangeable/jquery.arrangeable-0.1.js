(function($) {

	$.fn.arrangeable = function(options) {

		var opt = $.extend({
			abc: "test"
		}, options);

		var object = $(this);
		var rotating = false;
		var imgStartAngle, mouseStartAngle;

		var rotate = function(obj, deg) {
			obj.css('transform', 'rotate(' + deg + 'deg)');
			obj.css('-moz-transform', 'rotate(' + deg + 'deg)');
			obj.css('-webkit-transform', 'rotate(' + deg + 'deg)');
			obj.css('-o-transform', 'rotate(' + deg + 'deg)');
			obj.data('currentRotation', deg);
		}

		var centre = function(obj) {
			var curr = obj.data('currentRotation');
			rotate(obj, 0);
			var offset = obj.offset();
			var cx = offset.left + obj.width() / 2;
			var cy = offset.top + obj.height() / 2;
			rotate(obj, curr);
			return Array(cx, cy);
		}

		object.mousedown(function(e) {
			if (!e.shiftKey) return;
			rotating = object;
			imgStartAngle = object.data('currentRotation') || 0;
			var c = centre($(this));
			mouseStartAngle = Math.atan2(e.pageY - c[1], e.pageX - c[0]) * 180 / Math.PI;
			e.preventDefault();
			e.stopImmediatePropagation();
		});

		$(document).mousemove(function(e) {
			if (!rotating) return;
			var c = centre(rotating);
			currAngle = Math.atan2(e.pageY - c[1], e.pageX - c[0]) * 180 / Math.PI;
			rotate(rotating, imgStartAngle + (currAngle - mouseStartAngle));
			e.preventDefault();
			e.stopImmediatePropagation();
		});

		$(document).mouseup(function(e) {
			if (!rotating) return;
			rotating = false;
			e.preventDefault();
			e.stopImmediatePropagation();
		});

		$(document).click(function(e) {
			if (!rotating) return;
			e.preventDefault();
			e.stopImmediatePropagation();
		});

		object
			.draggable(opt)
			.bind("dragstart", function(e, ui) { if (rotating) return false; })
			.bind("drag", function(e, ui) { if (rotating) return false; })
			.bind("dragstop", function(e, ui) { if (rotating) return false; });

		return $(this);
	};

})(jQuery);
