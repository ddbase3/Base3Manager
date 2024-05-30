var CoolMarker = L.Marker.extend({
	options: {
		id: ''
	}
});

(function($) {

	var methods = {

		init: function(options) {

			return this.each(function() {

				var opt = $.extend({
					// angle: 0,
					// load: function(e, ui) {}
				}, options);

				var object = $(this);
				var id = object.attr("id");  // TODO check if object has an id, otherwise give it a random id

				mymap = L.map(id);
				mymap.setView([0, 0], 3);

/*
				var mapBoxApiKey = 'pk.eyJ1IjoiYmFzZTMiLCJhIjoiY2pwbTZuNGNpMDh4ejQycnQ4Z2twd3ZjMCJ9.08zb7KJxRc1Xuc0FeIDABQ';
				var mapboxUrl = 'https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={apikey}';
				var mapboxAttr = 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>';

				var baseLayers = {};
				baseLayers['streets'] = L.tileLayer(mapboxUrl, { maxZoom: 18, id: 'mapbox.streets', attribution: mapboxAttr, apikey: mapBoxApiKey });
				baseLayers['grayscale'] = L.tileLayer(mapboxUrl, { maxZoom: 18, id: 'mapbox.light', attribution: mapboxAttr, apikey: mapBoxApiKey });
*/

				var attrOsm = '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>';

				var osmUrl = 'https://{s}.tile.openstreetmap.de/tiles/osmde/{z}/{x}/{y}.png';
				var osmAttr = attrOsm + ' contributors';
				var esriUrl = 'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}';
				var esriAttr = 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community';
				var natgeoUrl = 'https://server.arcgisonline.com/ArcGIS/rest/services/NatGeo_World_Map/MapServer/tile/{z}/{y}/{x}';
				var natgeoAttr = 'Tiles &copy; Esri &mdash; National Geographic, Esri, DeLorme, NAVTEQ, UNEP-WCMC, USGS, NASA, ESA, METI, NRCAN, GEBCO, NOAA, iPC';
				var topoUrl = 'https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png';
				var topoAttr = 'Map data: ' + attrOsm + ' contributors, <a href="http://viewfinderpanoramas.org">SRTM</a> | Map style: &copy; <a href="https://opentopomap.org">OpenTopoMap</a> (<a href="https://creativecommons.org/licenses/by-sa/3.0/">CC-BY-SA</a>)';
				var cycleApiKey = '0006d16b868e45568f033290584ff8d4';
				var cycleUrl = 'https://{s}.tile.thunderforest.com/{variant}/{z}/{x}/{y}.png?apikey={apikey}';
				var cycleAttr = '&copy; <a href="http://www.thunderforest.com/">Thunderforest</a>, ' + attrOsm;
				var simpleUrl = 'https://cartodb-basemaps-{s}.global.ssl.fastly.net/light_all/{z}/{x}/{y}.png';
				var simpleAttr = attrOsm + ", &copy;<a href=\"https://carto.com/attribution\">CARTO</a>";

				var baseLayers = {};
				baseLayers['Basic'] = L.tileLayer(osmUrl, { maxZoom: 18, attribution: osmAttr });
				baseLayers['Satellite'] = L.tileLayer(esriUrl, { attribution: esriAttr });
				baseLayers['NatGeo'] = L.tileLayer(natgeoUrl, { maxZoom: 16, attribution: natgeoAttr });
				baseLayers['Topologic'] = L.tileLayer(topoUrl, { maxZoom: 17, attribution: topoAttr });
				baseLayers['Cycle'] = L.tileLayer(cycleUrl, { maxZoom: 22, variant: 'cycle', attribution: cycleAttr, apikey: cycleApiKey });
				baseLayers['Simple'] = L.tileLayer(simpleUrl, { maxZoom: 18, attribution: simpleAttr });

				var overlays = $.extend({}, overlays, {});
				baseLayers['Basic'].addTo(mymap);	// additional overlay possible
				var layersControl = L.control.layers(baseLayers, overlays).addTo(mymap);

				coolmap = { "map": mymap, "markers": [] };

				object.data("coolmap", coolmap);

				// methods._rotate(object, parseFloat(methods._parseval(opt.angle, 0)));
			});
		},

		clearMap: function() {
			return this.each(function() {
				var object = $(this);
				var coolmap = object.data("coolmap");
				if (typeof coolmap.markers !== 'undefined') {
					coolmap.markers.forEach(marker => coolmap.map.removeLayer(marker));
					coolmap.markers = [];
				}
			});
		},

		addMarker: function(posLatLng) {
			var opt = $.extend({
				id: null,
				title: null,
				draggable: false,
				click: function() {},
				dragend: function() {}
			}, arguments.length > 0 ? arguments[1] : []);

			return this.each(function() {
				var coolmap = $(this).data("coolmap");
				var marker = new CoolMarker([posLatLng[0], posLatLng[1]], { id: opt.id, draggable: opt.draggable })
					.addTo(coolmap.map)
					.on('click', opt.click)
					.on('dragend', opt.dragend);
				if (opt.title) marker.bindTooltip(opt.title);
				coolmap.markers.push(marker);
			});
		},

		fitBounds: function() {
			return this.each(function() {
				var coolmap = $(this).data("coolmap");
				if (!coolmap.markers.length) return;
				var lls = [];
				coolmap.markers.forEach(marker => lls.push(marker.getLatLng()));
				var bounds = L.latLngBounds(lls);
				coolmap.map.fitBounds(bounds, { padding: [50, 50] });
			});
		}

	};

	$.fn.coolmap = function(method) {

		if ( methods[method] ) {
			return methods[method].apply( this, Array.prototype.slice.call( arguments, 1 ));
		} else if ( typeof method === 'object' || ! method ) {
			return methods.init.apply( this, arguments );
		} else {
			$.error( 'Method ' +  method + ' does not exist on jQuery.coolmap' );
		}    

	};

})(jQuery);
