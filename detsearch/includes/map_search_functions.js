/*
This file contains Javascript for the DECADE map search Interface.

Author: Jason Ash (jasonash@ku.edu) 04/19/2017
*/

//g
//var activeFeature = {};
var activeId = 0;
var activeGeometry = {};

var volcanonumber = '';
var volcanoname = '';
var country = '';
var regionname = '';
var subregionname = '';
var remarks = '';
var volcanotype = '';
var geotimeepoch = '';
var geotimeepochcertainty = '';
var elevation = '';
var latitudedecimal = '';
var longitudedecimal = '';
var tectonicsetting = '';
var imagefile = '';
var imagecaption = '';
var imagecredit = '';
var rocktype = '';
var lasteruption = '';



function gozoom () {
	var volcanosel = document.getElementById('volcanoselect');
	for (var p = 1; p<=volcanosel.length - 1; p++) {
		if (volcanosel.options[p].selected) {
			var volcanonum = volcanosel.options[p].value;
			var features = volcanoPointsSource.getFeatures();
			_.each(features, function (f){
				foundnum = f.get('VolcanoNumber');
				if(foundnum==volcanonum){
					//mapView.fit(f.getGeometry(), {padding: [170, 50, 30, 150], minResolution: 5, duration: 500});
					mapView.fit(f.getGeometry(), {padding: [170, 50, 30, 150], maxZoom: 8, duration: 500});
				}
			});
		}
	}
}


function getClickedFeature(map, evt) {
	return map.forEachFeatureAtPixel(evt.pixel, function (feat, lyr) {
		return feat;
	}, this, function (lyr) {
		// we only want the layer where the spots are located
		return (lyr instanceof ol.layer.Vector) && lyr.get('name') !== 'drawLayer' &&
			lyr.get('name') !== 'geolocationLayer' && lyr.get('name') !== 'selectedHighlightLayer';
	});
}

function getClickedLayer(map, evt) {
	return map.forEachFeatureAtPixel(evt.pixel, function (feat, lyr) {
		return lyr;
	}, this, function (lyr) {
		// we only want the layer where the spots are located
		return (lyr instanceof ol.layer.Vector) && lyr.get('name') !== 'drawLayer' &&
			lyr.get('name') !== 'geolocationLayer' && lyr.get('name') !== 'selectedHighlightLayer';
	});
}

function removeSelectedSymbol(map) {
	//map.removeLayer(selectedHighlightLayer);

	var mylayers = map.getLayers();
	mylayers.forEach(function (thislayer) {
		//console.log(thislayer.getProperties());
		if(thislayer.get('name')=='selectedHighlightLayer'){
			map.removeLayer(thislayer);
		}
	});

}


// Add a feature to highlight selected Spot
// Encompassing orange circle for a point, orange stroke for a line, and orange fill for a polygon
function setSelectedSymbol(map, geometry) {
	var selected = new ol.Feature({
		geometry: geometry
	});

	var style = {};
	if (geometry.getType() === 'Point') {
		style = new ol.style.Style({
			image: new ol.style.Circle({
				radius: 30,
				stroke: new ol.style.Stroke({
					color: 'white',
					width: 1
				}),
				fill: new ol.style.Fill({
					color: [255, 0, 0, 0.2]
				})
			})
		});
	}
	else if (geometry.getType() === 'LineString' || geometry.getType() === 'MultiLineString') {
		style = new ol.style.Style({
			stroke: new ol.style.Stroke({
				color: [245, 121, 0, 0.6],
				width: 4
			})
		})
	}
	else {
		style = new ol.style.Style({
			stroke: new ol.style.Stroke({
				color: 'white',
				width: 2
			}),
			fill: new ol.style.Fill({
				color: [245, 121, 0, 0.6]
			})
		})
	}

	var selectedHighlightSource = new ol.source.Vector({
		features: [selected]
	});

	selectedHighlightLayer = new ol.layer.Vector({
		name: 'selectedHighlightLayer',
		source: selectedHighlightSource,
		style: style
	});

	map.addLayer(selectedHighlightLayer);

}

var hideLayer = function(layername){
	var mylayers = map.getLayers();
	mylayers.forEach(function (thislayer) {
		//console.log(thislayer.getProperties());
		if(thislayer.get('name')==layername){
			thislayer.setVisible(false);
		}
	});
}

var showLayer = function(layername){
	var mylayers = map.getLayers();
	mylayers.forEach(function (thislayer) {
		//console.log(thislayer.getProperties());
		if(thislayer.get('name')==layername){
			thislayer.setVisible(true);
		}
	});
}





var volcanoGetText = function(feature, resolution) {

	var maxResolution = 2000; //1000
	var text = feature.get('VolcanoName');

	if (resolution > maxResolution) {
		text = '';
	}

	return text;
};

var volcanoGetIcon = function(feature, resolution) {

	var maxResolution = 2000; //1000

	var filename = '';
	
	//console.log(filename);
	
	if (resolution > maxResolution) {
		//filename = '/includes/images/mapicons/nnnn.png';
		filename = 'includes/images/mapicons/'+feature.get('has_gvp')+feature.get('has_ecp')+feature.get('has_sesar')+feature.get('has_maga')+'.png';
	}else{
		filename = 'includes/images/mapicons/'+feature.get('has_gvp')+feature.get('has_ecp')+feature.get('has_sesar')+feature.get('has_maga')+'.png';
	}

	return filename;
};




var volcanoPointStyleFunction = function() {
	return function(feature, resolution) {
		var style = new ol.style.Style({
			image: new ol.style.Icon(({
				anchor: [0.5, 17],
				scale: .7,
				anchorXUnits: 'fraction',
				anchorYUnits: 'pixels',
				src: volcanoGetIcon(feature, resolution)
			})),
			text: new ol.style.Text({
				text: volcanoGetText(feature, resolution),
				offsetX: 15,
				offsetY: -15,
				font: '16px Calibri,sans-serif',
				fill: new ol.style.Fill({
					color: '#000'
				}),
				stroke: new ol.style.Stroke({
					color: '#fff',
					width: 5
				})
			})
		});
		return [style];
	};
};



var spotStyleFunction = function() {
	return function(feature, resolution) {

		var thistype = feature.getGeometry().getType();

		if(thistype=="Point"){

			var style = new ol.style.Style({
				image: new ol.style.Circle({
					radius: 7,
					fill: new ol.style.Fill({
						color: 'rgba(255,0,255,0.8)'
					}),
					stroke: new ol.style.Stroke({color: 'black', width: 2})
				}),
				text: new ol.style.Text({
					//text: datasetGetText(feature, resolution),
					offsetX: 15,
					offsetY: -15,
					font: '16px Calibri,sans-serif',
					fill: new ol.style.Fill({
						color: '#000'
					}),
					stroke: new ol.style.Stroke({
						color: '#fff',
						width: 5
					})
				})
			});
			return style;

		}

		if(thistype=="Polygon"){

			var style = new ol.style.Style({
				stroke: new ol.style.Stroke({
					color: 'black',
					//lineDash: [4],
					width: 1
				}),
				fill: new ol.style.Fill({
					color: 'rgba(255, 0, 0, 0.2)'
				})
			});

			return style;

		}

		if(thistype=="LineString"){

			var style = new ol.style.Style({
			  stroke: new ol.style.Stroke({
				color: 'black',
				width: 2
			  })
			});

			return style;

		}

	};
};


function zzztoRadians(deg) {
	return deg * (Math.PI / 180);
}

function zzzgetSymbolPath(feature_type, orientation, orientation_type) {

	var symbols = {
		'default_point': '/includes/images/geology/point.png',

		// Planar Feature Symbols
		'bedding_horizontal': '/includes/images/geology/bedding_horizontal.png',
		'bedding_inclined': '/includes/images/geology/bedding_inclined.png',
		'bedding_vertical': '/includes/images/geology/bedding_vertical.png',
		'contact_inclined': '/includes/images/geology/contact_inclined.png',
		'contact_vertical': '/includes/images/geology/contact_vertical.png',
		'fault': '/includes/images/geology/fault.png',
		'foliation_horizontal': '/includes/images/geology/foliation_horizontal.png',
		'foliation_inclined': '/includes/images/geology/foliation_general_inclined.png',
		'foliation_vertical': '/includes/images/geology/foliation_general_vertical.png',
		'fracture': '/includes/images/geology/fracture.png',
		'shear_zone_inclined': '/includes/images/geology/shear_zone_inclined.png',
		'shear_zone_vertical': '/includes/images/geology/shear_zone_vertical.png',
		'vein': '/includes/images/geology/vein.png',

		// Old
		// 'axial_planar_inclined': 'includes/images/geology/cleavage_inclined.png',
		// 'axial_planar_vertical': 'includes/images/geology/cleavage_vertical.png',
		// 'joint_inclined': 'includes/images/geology/joint_surface_inclined.png',
		// 'joint_vertical': 'includes/images/geology/joint_surface_vertical.png',
		// 'shear_fracture': 'includes/images/geology/shear_fracture.png',

		// Linear Feature Symbols
		// 'fault': 'includes/images/geology/fault_striation.png',
		// 'flow': 'includes/images/geology/flow.png',
		// 'fold_hinge': 'includes/images/geology/fold_axis.png',
		// 'intersection': 'includes/images/geology/intersection.png',
		'lineation_general': '/includes/images/geology/lineation_general.png'
		// 'solid_state': 'includes/images/geology/solid_state.png',
		// 'vector': 'includes/images/geology/vector.png'
	};

	// Set a default symbol by whether feature is planar or linear
	var default_symbol = symbols.default_point;
	if (orientation_type === 'linear_orientation') default_symbol = symbols.lineation_general;

	switch (true) {
		case (orientation === 0):
			return symbols[feature_type + '_horizontal'] || symbols[feature_type + '_inclined'] || symbols[feature_type] || default_symbol;
		case ((orientation > 0) && (orientation < 90)):
			return symbols[feature_type + '_inclined'] || symbols[feature_type] || default_symbol;
		case (orientation === 90):
			return symbols[feature_type + '_vertical'] || symbols[feature_type] || default_symbol;
		default:
			return default_symbol;
	}
}

// creates a ol vector layer for supplied geojson object
function zzzgeojsonToVectorLayer(geojson, projection) {
	// textStyle is a function because each point has a different text associated
	function textStyle(text) {
		return new ol.style.Text({
			'font': '12px Calibri,sans-serif',
			'text': text,
			'fill': new ol.style.Fill({
				'color': '#000'
			}),
			'stroke': new ol.style.Stroke({
				'color': '#fff',
				'width': 3
			})
		});
	}

	function textStylePoint(text, rotation) {
		return new ol.style.Text({
			'font': '12px Calibri,sans-serif',
			'text': '					       ' + text,	// we pad with spaces due to rotational offset
			'textAlign': 'center',
			'fill': new ol.style.Fill({
				'color': '#000'
			}),
			'stroke': new ol.style.Stroke({
				'color': '#fff',
				'width': 3
			})
		});
	}

	function getStrokeStyle(feature) {
		var color = '#663300';
		var width = 2;
		var lineDash = [1, 0];

		if (feature.get('trace')) {
			var trace = feature.get('trace');

			// Set line color and weight
			if (trace.trace_type && trace.trace_type === 'geologic_struc') color = '#FF0000';
			else if (trace.trace_type && trace.trace_type === 'contact') color = '#000000';
			else if (trace.trace_type && trace.trace_type === 'geomorphic_fea') {
				width = 4;
				color = '#0000FF';
			}
			else if (trace.trace_type && trace.trace_type === 'anthropenic_fe') {
				width = 4;
				color = '#800080';
			}

			// Set line pattern
			lineDash = [.01, 10];
			if (trace.trace_quality && trace.trace_quality === 'known') lineDash = [1, 0];
			else if (trace.trace_quality && trace.trace_quality === 'approximate'
				|| trace.trace_quality === 'questionable') lineDash = [20, 15];
			else if (trace.trace_quality && trace.trace_quality === 'other') lineDash = [20, 15, 0, 15];
		}

		return new ol.style.Stroke({
			'color': color,
			'width': width,
			'lineDash': lineDash
		});
	}

	function getIconForFeature(feature) {
		var feature_type = 'none';
		var rotation = 0;
		var symbol_orientation = 0;
		var orientation_type = 'none';
		var orientation = feature.get('orientation');
		if (orientation) {
			rotation = orientation.strike || orientation.trend || rotation;
			symbol_orientation = orientation.dip || orientation.plunge || symbol_orientation;
			feature_type = orientation.feature_type || feature_type;
			orientation_type = orientation.type || orientation_type;
		}

		return new ol.style.Icon({
			'anchorXUnits': 'fraction',
			'anchorYUnits': 'fraction',
			'opacity': 1,
			'rotation': toRadians(rotation),
			'src': getSymbolPath(feature_type, symbol_orientation, orientation_type),
			'scale': 0.05
		});
	}

	function getPolyFill(feature) {
		var color = 'rgba(0, 0, 255, 0.4)';			 // blue
		if (feature.get('surface_feature')) {
			var surfaceFeature = feature.get('surface_feature');
			switch (surfaceFeature.surface_feature_type) {
				case 'rock_unit':
					color = 'rgba(0, 255, 255, 0.4)';	 // light blue
					break;
				case 'contiguous_outcrop':
					color = 'rgba(240, 128, 128, 0.4)'; // pink
					break;
				case 'geologic_structure':
					color = 'rgba(0, 255, 255, 0.4)';	 // light blue
					break;
				case 'geomorphic_feature':
					color = 'rgba(0, 128, 0, 0.4)';		 // green
					break;
				case 'anthropogenic_feature':
					color = 'rgba(128, 0, 128, 0.4)';	 // purple
					break;
				case 'extent_of_mapping':
					color = 'rgba(128, 0, 128, 0)';		 // no fill
					break;
				case 'extent_of_biological_marker':	 // green
					color = 'rgba(0, 128, 0, 0.4)';
					break;
				case 'subjected_to_similar_process':
					color = 'rgba(255, 165, 0,0.4)';		// orange
					break;
				case 'gradients':
					color = 'rgba(255, 165, 0,0.4)';		// orange
					break;
			}
		}
		return new ol.style.Fill({
			'color': color
		});
	}

	// Set styles for points, lines and polygon and groups
	function styleFunction(feature, resolution) {
		var rotation = 0;
		var pointText = feature.get('name');
		var orientation = feature.get('orientation');
		if (orientation) {
			rotation = orientation.strike || orientation.trend || rotation;
			pointText = orientation.dip || orientation.plunge || pointText;
		}

		var pointStyle = [
			new ol.style.Style({
				'image': getIconForFeature(feature),
				'text': textStylePoint(pointText.toString(), rotation)
			})
		];
		var lineStyle = [
			new ol.style.Style({
				'stroke': getStrokeStyle(feature),
				'text': textStyle(feature.get('name'))
			})
		];
		var polyText = feature.get('name');
		var polyStyle = [
			new ol.style.Style({
				'stroke': new ol.style.Stroke({
					'color': '#000000',
					'width': 0.5
				}),
				'fill': getPolyFill(feature),
				'text': textStyle(polyText)
			})
		];
		var styles = [];
		styles.Point = pointStyle;
		styles.MultiPoint = pointStyle;
		styles.LineString = lineStyle;
		styles.MultiLineString = lineStyle;
		styles.Polygon = polyStyle;
		styles.MultiPolygon = polyStyle;

		return styles[feature.getGeometry().getType()];
	}

	var features;
	if (projection.getUnits() === 'pixels') {
		features = (new ol.format.GeoJSON()).readFeatures(geojson);
	}
	else {
		features = (new ol.format.GeoJSON()).readFeatures(geojson, {
			'featureProjection': projection
		});
	}

	return new ol.layer.Vector({
		'source': new ol.source.Vector({
			'features': features
		}),
		'title': geojson.properties.name,
		'style': styleFunction,
		'visible': typeVisibility[geojson.properties.name.split(' (')[0]]
		//'visible': true
	});
}

//fetch features from database
var zzzloadFeatures = function(){

	if((loadedFeatures=="" || envelopeOutside()) && !currentlyLoading){
	
		currentlyLoading = true;
		
		var extent = map.getView().calculateExtent(map.getSize());

		var offset=0.2;
		var bottomLeft = ol.proj.transform(ol.extent.getBottomLeft(extent),'EPSG:3857', 'EPSG:4326');
		var topRight = ol.proj.transform(ol.extent.getTopRight(extent),'EPSG:3857', 'EPSG:4326');
		var left = bottomLeft[0]-offset;
		var right = topRight[0]+offset;
		var top = topRight[1]+offset;
		var bottom = bottomLeft[1]-offset;

		loadedEnvelope = turf.polygon([[
			[left, bottom],
			[left, top],
			[right, top],
			[right, bottom],
			[left, bottom]
		]]);

		env = left+','+top+','+right+','+bottom;

		featureLayer.getLayers().clear();

		//show loading animation
		document.getElementById('spotswaiting').style.display="block";

		$.getJSON('searchspots.json?env='+env,function(result){
			loadedFeatures = result;
			
			saveIdsToNames();
			
			document.getElementById('spotswaiting').style.display="none";

			updateMap();
			currentlyLoading = false;
		});
		
	}else{

		updateMap();
	}
}

var openSideBar = function(){
	$(".sidebar.right").trigger("sidebar:open");
}

var closeSideBar = function(){
	$("#sidebarquery").hide();
	$(".sidebar.right").trigger("sidebar:close");
}

var toggleSideBar = function(){
	$(".sidebar.right").trigger("sidebar:toggle");
}



var zzzgetCurrentSpot = function(){
	return new Promise(function(resolve, reject){
		currentSpot="";
		_.each(loadedFeatures.features, function (spot) {
			if(spot.properties.id == clickedMapFeature){
				currentSpot = spot;
			}	
		});
		resolve();
	});
}

//get info for current spot and tab and update UI
var updateSidebar = function(feature){

/*
VolcanoNumber
VolcanoName
Country
Remarks
VolcanoType
LastEruption
Elevation
TectonicSetting
Within_5km
Within_10km
Within_30km
Within_100km
VPImageNum
VPImageCaption
VPImageCredit
LatitudeDecimal
LongitudeDecimal
has_gvp
has_ecp
has_sesar
has_maga

volcanonumber
volcanoname
country
remarks
volcanotype
lasteruption
elevation
tectonicsetting
within_5km
within_10km
within_30km
within_100km
vpimagenum
vpimagecaption
vpimagecredit
latitudedecimal
longitudedecimal
has_gvp
has_ecp
has_sesar
has_maga

*/
	console.log(feature.getProperties());
	volcanonumber = feature.get('VolcanoNumber');
	volcanoname = feature.get('VolcanoName');
	country = feature.get('Country');
	remarks = feature.get('Remarks');
	volcanotype = feature.get('VolcanoType');
	lasteruption = feature.get('LastEruption');
	elevation = feature.get('Elevation');
	tectonicsetting = feature.get('TectonicSetting');
	within_5km = feature.get('Within_5km');
	within_10km = feature.get('Within_10km');
	within_30km = feature.get('Within_30km');
	within_100km = feature.get('Within_100km');
	vpimagenum = feature.get('VPImageNum');
	vpimagecaption = feature.get('VPImageCaption');
	vpimagecredit = feature.get('VPImageCredit');
	latitudedecimal = feature.get('LatitudeDecimal');
	longitudedecimal = feature.get('LongitudeDecimal');
	has_gvp = feature.get('has_gvp');
	has_ecp = feature.get('has_ecp');
	has_sesar = feature.get('has_sesar');
	has_maga = feature.get('has_maga');


	if(!vpimagenum){
		imagefile="/includes/images/noimage.jpg";
	}else{
		imagefile="http://volcano.si.edu/photos/full/"+vpimagenum+".jpg";
	}
	
	thishtml='';

	thishtml += '<div id="volcanoname">'+volcanoname+'</div><hr>';
	
	thishtml += '<table class="volcanotable">';

	if(volcanonumber) thishtml += '<tr><td class="boldtd">Volcano Number: </td><td>'+volcanonumber+'</td></tr>';
	//if(volcanoname) thishtml += '<tr><td class="boldtd">Volcano Name: </td><td>'+volcanoname+'</td></tr>';
	if(country) thishtml += '<tr><td class="boldtd">Country: </td><td>'+country+'</td></tr>';
	//if(remarks) thishtml += '<tr><td class="boldtd">Remarks: </td><td>'+remarks+'</td></tr>';
	if(volcanotype) thishtml += '<tr><td class="boldtd">Volcano Type: </td><td>'+volcanotype+'</td></tr>';
	if(lasteruption) thishtml += '<tr><td class="boldtd">Last Eruption: </td><td>'+lasteruption+'</td></tr>';
	if(elevation) thishtml += '<tr><td class="boldtd">Elevation: </td><td>'+elevation+'m</td></tr>';
	if(tectonicsetting) thishtml += '<tr><td class="boldtd">Tectonic Setting: </td><td>'+tectonicsetting+'</td></tr>';
	if(within_5km) thishtml += '<tr><td class="boldtd">Pop. within 5km: </td><td>'+within_5km+'</td></tr>';
	if(within_10km) thishtml += '<tr><td class="boldtd">Pop. within 10km: </td><td>'+within_10km+'</td></tr>';
	if(within_30km) thishtml += '<tr><td class="boldtd">Pop. within 30km: </td><td>'+within_30km+'</td></tr>';
	if(within_100km) thishtml += '<tr><td class="boldtd">Pop. within 100km: </td><td>'+within_100km+'</td></tr>';
	if(latitudedecimal) thishtml += '<tr><td class="boldtd">Latitude: </td><td>'+latitudedecimal+'</td></tr>';
	if(longitudedecimal) thishtml += '<tr><td class="boldtd">Longitude: </td><td>'+longitudedecimal+'</td></tr>';
	
	
	if(has_gvp=="y"){
		has_gvp="Yes";
	}else{
		has_gvp="No";
	}

	if(has_ecp=="y"){
		has_ecp="Yes";
	}else{
		has_ecp="No";
	}

	if(has_sesar=="y"){
		has_sesar="Yes";
	}else{
		has_sesar="No";
	}

	if(has_maga=="y"){
		has_maga="Yes";
	}else{
		has_maga="No";
	}
	
	
	if(has_gvp) thishtml += '<tr><td class="boldtd">Has&nbsp;GVP&nbsp;Data: </td><td>'+has_gvp+'</td></tr>';
	if(has_ecp) thishtml += '<tr><td class="boldtd">Has&nbsp;ECP&nbsp;Data: </td><td>'+has_ecp+'</td></tr>';
	if(has_sesar) thishtml += '<tr><td class="boldtd">Has&nbsp;SESAR&nbsp;Data: </td><td>'+has_sesar+'</td></tr>';
	if(has_maga) thishtml += '<tr><td class="boldtd">Has&nbsp;MaGa&nbsp;Data: </td><td>'+has_maga+'</td></tr>';

	thishtml += '</table>';

	if(remarks){
		//thishtml += '<div style="padding-top:10px;">Remarks:<br>'+remarks+'</div>';
	}

	thishtml += '<div align="center" style="padding-top:10px;"><button class="detailbutton" onclick="window.open(\'d/'+latitudedecimal+'/'+longitudedecimal+'/'+volcanonumber+'\'); return false;"><span>Go To Data</span></button></div>';

	thishtml += '<div align="center" style="padding-top:10px;"><img class="volcanoimage" src="'+imagefile+'"></div>';
	
	if(vpimagecaption){
		thishtml += '<div class="imagecaption">'+vpimagecaption;
		if(vpimagecredit){
			thishtml += ' '+vpimagecredit;
		}
		thishtml += '</div>';
	}

	$("#volcanowrapper").html(thishtml);
}

var saveExtent = function(){
	savedCenterZoom.center = map.getView().getCenter();
	savedCenterZoom.zoom = map.getView().getZoom();
}

var zoomToSavedExtent = function(){
	mapView.setCenter(savedCenterZoom.center);
	mapView.setZoom(savedCenterZoom.zoom);
}

var zoomToCenterAndExtent = function(encodedstring){
	var string = atob(encodedstring);
	var newcenter = [];
	var newzoom = 0;
	
	var res = string.split("x");
	
	newcenter[0]=Number(res[0]);
	newcenter[1]=Number(res[1]);
	newzoom = res[2];

	mapView.setCenter(newcenter);
	mapView.setZoom(newzoom);

} 

var showStaticUrl = function(){
	
	var getcenter = map.getView().getCenter();
	var getzoom = map.getView().getZoom();

	console.log(getcenter);

	var encodedstring = btoa(getcenter.join("x")+"x"+getzoom);
	
	console.log(encodedstring);
	
	var thishtml = "<div style='padding:20px 20px 20px 20px;'>";
	thishtml += "<h3>Static URL</h3>";
	thishtml += "<div>This URL will link directly to the zoom/center of the current map view:</div>";
	thishtml += "<div style='padding-top:5px;'>https://strabospot.org/search?c="+encodedstring+"</div>";
	thishtml += "<div>&nbsp;</div>";
	thishtml += "</div>";
	
	$.featherlight(thishtml);
	
}

