//Map Interface Building

var volcanoPointsSource = new ol.source.Vector();

var volcanoPointsLayer = new ol.layer.Vector({
	source: volcanoPointsSource,
	style: volcanoPointStyleFunction(),
	name: 'volcanopoints',
	title: 'Smithsonian Volcanoes'

});



fetch('/wfs.js').then(function(response){  
	if (response.status !== 200) {  
		console.log('Looks like there was a problem. Status Code: ' +  response.status);  
		return;  
	}
	
	response.json().then(function(json) {  

		var features = new ol.format.GeoJSON().readFeatures(json);

		var features = new ol.format.GeoJSON().readFeatures(json, {
		dataProjection: 'EPSG:4326',
		featureProjection: 'EPSG:3857'
		});
		
		volcanoPointsSource.addFeatures(features);
		
		$("#volcanoeswaiting").hide("slow");
		
	}); 
	 
}).catch(function(err) {  
	
});


var baseLayers = new ol.layer.Group({
	'title': 'Base maps',
	layers: [
		new ol.layer.Group({
			title: 'Water color with labels',
			type: 'base',
			combine: true,
			visible: false,
			layers: [
				new ol.layer.Tile({
					source: new ol.source.Stamen({
						layer: 'watercolor'
					})
				}),
				new ol.layer.Tile({
					source: new ol.source.Stamen({
						layer: 'terrain-labels'
					})
				})
			]
		}),
		new ol.layer.Tile({
			title: 'Water color',
			type: 'base',
			visible: false,
			source: new ol.source.Stamen({
				layer: 'watercolor'
			})
		}),
		new ol.layer.Tile({
			title: 'OSM',
			type: 'base',
			visible: true,
			source: new ol.source.OSM()
		}),
        new ol.layer.Tile({
			source: new ol.source.TileWMS({
				url: 'http://gmrt.marine-geo.org/cgi-bin/mapserv?map=/public/mgg/web/gmrt.marine-geo.org/htdocs/services/map/wms_merc.map&',
				params: {'LAYERS': 'topo', 'TILED': true},
				serverType: 'geoserver'
			}),
			title: 'IEDA/MGDS Bathymetry',
			type: 'base'
        })



/*
var wms = new OpenLayers.Layer.WMS( "IEDA/MGDS Bathymetry",
				"http://gmrt.marine-geo.org/cgi-bin/mapserv?map=/public/mgg/web/gmrt.marine-geo.org/htdocs/services/map/wms_merc.map&", {layers: 'topo',format: 'png', SRS : "3395" },{wrapDateLine: true});
				
*/




	]
});

//'center': [-11000000, 4600000],

var mapView = new ol.View({
	'projection': 'EPSG:3857',
	'center': [0, 0], //'center': [-11000000, 4600000],
	'zoom': 4, //5
	'minZoom': 4
});


map = new ol.Map({
	target: 'map',
	controls: ol.control.defaults({}),
	view: mapView
});

map.addLayer(baseLayers);
map.addLayer(volcanoPointsLayer);

var layerSwitcher = new ol.control.LayerSwitcher({
	tipLabel: 'Layers' // Optional label for button
});

map.addControl(layerSwitcher);

map.on('click', function (evt) {

	removeSelectedSymbol(map);

	var feature = getClickedFeature(map, evt);
	
	var layer = getClickedLayer(map, evt);
	if (feature && layer && layer.get('name') !== 'selectedHighlightLayer') {
		clickedMapFeature = feature.get('id');

		setSelectedSymbol(map, feature.getGeometry());

		updateSidebar(feature);
	
		openSideBar();

	}else{
		clickedMapFeature = undefined;
		closeSideBar();
	}
});

