<?PHP
/**
 * popupmap.php
 *
 * longdesc
 *
 * LICENSE: This source file is subject to version 4.0 of the Creative Commons
 * license that is available through the world-wide-web at the following URI:
 * https://creativecommons.org/licenses/by/4.0/
 *
 * @category   Geochronology
 * @package    Geochron Portal
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  IEDA (http://www.iedadata.org/)
 * @license    https://creativecommons.org/licenses/by/4.0/  Creative Commons License 4.0
 * @version    GitHub: $
 * @link       http://www.geochron.org
 * @see        Geochron, Geochronology
 */

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Geochron</title>
<link rel='stylesheet' type='text/css' media='all' href='/geochron.css' />
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" /> 
<style type="text/css">
/*NYTimes top tabs pale blue: f0f4f5
NYTimes top tabs border: 999999*/
<!-- the tufte blue 4572b3 from plots, and a palest version for rollovers d7e6fc -->
body {
background-color: #ffffff;
margin: 0 0 0 0;
}
body, td {
font-family: verdana,arial,sans-serif;
font-size: 8pt;
color: #636363;
background-color: #ffffff;
margin: 0 0 0 0;
}
/* pagetitle is used in jason's xml page viewfile.php */
h1, .pagetitle {
font-size: 10pt;
font-weight: 600; /* note: 400 is normal, 700 is bold */
color: #666666;
margin: 0px 0px 10px 0px;
}
.pagetitle {font-size:11pt; color:#636363; margin: 0px 0px 5px 0px;}
/* headline & fatlink are used in jason's xml page viewfile.php */
h2,h3,h4, .headline, .fatlink {
font-size: 8pt;
font-weight: 600;
color: #696969;
margin: 0 0 5px 0;
}
.page {
position: relative;top: 0;left: 0;
width: 705px;
text-align: left;
background-color: #cccccc;
padding: 0px 0px 0px 0px;
margin-left: auto;margin-right: auto;
border-style: none;border-color: cyan;border-width: 1px 1px 1px 1px;
}
a:link, a:visited {
/* color: #1e4148; */
color: #152E33;
text-decoration: none;
}
a:hover {
color: #152E33;
text-decoration: underline;
}
input {
/*float: right;
position: relative;top: 0;left: 0;*/
font-size: 8pt; line-height:130%;
margin: 0px 0px 0px 0px;
padding: 3px 3px 3px 3px;
}
a.button,a.button:link,a.button:visited {
  border-style: solid;
  border-width: 1px 1px 1px 1px;
  border-color : #4572b3;
  padding: 7px 7px 7px 7px;
  color: #999999;
  font-family: verdana,arial,sans-serif;
  font-size: 12px;
  background-color:#f0f4f5;
  text-decoration:none;
}
a.button:hover,a.button:active {color:#333333;border-color:#333333;text-decoration:none;}
#mapDiv {
	width: 800px;
	height: 400px;
	border: 1px solid black;
}
a.menulink { text-decoration:none;
  /*border-style: solid;
  border-width: 1px;
  text-decoration: none;
  padding: 3px 3px 3px 3px;
  margin-top:5px; margin-bottom:5px; margin-right:5px; margin-left:0px;
  border-color : #4572b3;
  text-decoration: none;
  color: #999999;
  font-family: verdana,arial,sans-serif;*/
  font-size: 11px;
}
a.menulink:hover,a:active { color:#990000;}

/* css for results page, from jason's original */
 table.aliquot, table.sample  {
	border-width: 1px 1px 1px 1px;
	border-spacing: 2px;
	border-style: none none none none;
	border-color: #999999; /*#636363;*/
	border-collapse: collapse;
	background-color: white;
}
table.aliquot th, table.sample th  {
	font-family:arial,verdana,sans-serif;
	font-size:10pt;
	font-weight: 500;
	color:#333333;
	text-transform:uppercase;
	text-align:left;
	/*color: #666699; #636363; #FFFFFF;*/
	border-color: #999999;
	border-width: 1px 1px 1px 1px;
	padding: 5px 5px 5px 5px;
	border-style: solid solid solid solid;
	background-color: #f0f4f5; /* NYTimes tabs background blue. Tried others: #d7e6fc; 325280 #003366;*/
}
table.sample th {
	background-color:antiquewhite;text-transform:none;
	}
table.aliquot td, table.sample td  {
	border-width: 1px 1px 1px 1px;
	border-color: #999999;
	padding: 2px 5px 2px 5px;
	border-style: solid solid solid solid;
	background-color: white;
}
/* styles used by viewfile.php - adapted from ones in jason's upbgeochron.css file - some redefined above */
.headlinexxx {
	color: #003366;
	font-weight: bold;
	font-size: 18px;
}
.pagetitlexxx {
	color: #003366;
	font-weight: bold;
	font-size: 28px;
}
.fatlinkxxx {
	color: #003366;
	font-weight: bold;
	font-size: 12px;
}


#mapDiv {
	width: 800px;
	height: 400px;
	border: 1px solid black;
}

#mapDivdyn {
	width: 400px;
	height: 400px;
	border: 1px solid black;
}


</style>
<!-- For the menus: css and javascript  -->
<style type="text/css">


.hide{
display: none;
}
.show{
display: block;
}

.tinylink {
color:#cccccc;font-size:7pt;letter-spacing:0.1em;padding-right:5px;padding-top:15px;
}
a.tinylink:hover {text-decoration:underline;}
</style>
<script language="JavaScript" type="text/JavaScript">
<!--
menu_status = new Array();

function showHide(theid){
    if (document.getElementById) {
    var switch_id = document.getElementById(theid);

        if(menu_status[theid] != 'show') {
           switch_id.className = 'show';
           menu_status[theid] = 'show';
        } else {
           switch_id.className = 'hide';
           menu_status[theid] = 'hide';
        }
    }
}

function show(theid){
    if (document.getElementById) {
    var switch_id = document.getElementById(theid);
           switch_id.className = 'show';
           menu_status[theid] = 'show';
    }
}

function hide(theid){
    if (document.getElementById) {
    var switch_id = document.getElementById(theid);
           switch_id.className = 'hide';
           menu_status[theid] = 'hide';
    }
}

function changebgcolor(theid){
    if (document.getElementById) {
    var switch_id = document.getElementById(theid);
           switch_id.className = 'hide';
           menu_status[theid] = 'hide';
    }
}

function showDebug() {
var thsObj =  document.getElementById("debug");
		if(thsObj.style.display == 'block') {
			thsObj.style.display = 'none';
		}else{
			thsObj.style.display = 'block';
		}
}

//-->
</script>
<!-- end css and javascript for the menus -->
</head>

<body>


<?
//<script src="http://openlayers.org/api/OpenLayers.js"></script>
?>

<script src="openlayers/OpenLayers.js"></script>

  	<div id="the_div"></div>

<table width="800px" cellpadding="0"><tr><td>

	<div style="margin-left:0px; margin-right:20px;">
  <table  style=" cellspacing=0 cellpadding=0 border-collapse:collapse;">
  	<tr>

		<td align="left" class="mapTxt">
			Define the envelope by clicking on three or more locations (corners of the bounding polygon) in the map. Then, click <b>Submit</b> to update your earthchem search criteria.</li>

			<br><br>To change the envelope, first click a marked location to remove it, or click <b>Clear</b> to remove all the marked locations; then, re-define the location.</li>
<br>&nbsp;
		</td>

	</tr>
  </table></div>

		

		<INPUT type="button" value="Submit" onclick="updatesearch();">
		<button onclick="foofoo();">Clear</button>
		<button onclick="parent.Shadowbox.close();">Cancel</button>
		
		<FORM name="mapform" id="mapform" action="search.php" method="post">
		<INPUT type="hidden" id="lat1" name="lat1">
		<INPUT type="hidden" id="lat2" name="lat2">
		<INPUT type="hidden" id="lat3" name="lat3">
		<INPUT type="hidden" id="lat4" name="lat4">

		<INPUT type="hidden" id="lat5" name="lat5">

		<INPUT type="hidden" id="lat6" name="lat6">

		<INPUT type="hidden" id="lat7" name="lat7">
		<INPUT type="hidden" id="lat8" name="lat8">
		<INPUT type="hidden" id="lat9" name="lat9">
		<INPUT type="hidden" id="lat10" name="lat10">
		<INPUT type="hidden" id="lat11" name="lat11">
		<INPUT type="hidden" id="lat12" name="lat12">

		<INPUT type="hidden" id="lat13" name="lat13">

		<INPUT type="hidden" id="lat14" name="lat14">

		<INPUT type="hidden" id="lat15" name="lat15">

		<INPUT type="hidden" id="lat16" name="lat16">
		<INPUT type="hidden" id="lat17" name="lat17">
		<INPUT type="hidden" id="lat18" name="lat18">
		<INPUT type="hidden" id="lat19" name="lat19">
		<INPUT type="hidden" id="lat20" name="lat20">

		<INPUT type="hidden" id="lat21" name="lat21">

		<INPUT type="hidden" id="lat22" name="lat22">
		<INPUT type="hidden" id="lat23" name="lat23">
		<INPUT type="hidden" id="lat24" name="lat24">

		<INPUT type="hidden" id="lat25" name="lat25">
		<INPUT type="hidden" id="long1" name="long1">
		<INPUT type="hidden" id="long2" name="long2">
		<INPUT type="hidden" id="long3" name="long3">

		<INPUT type="hidden" id="long4" name="long4">

		<INPUT type="hidden" id="long5" name="long5">
		<INPUT type="hidden" id="long6" name="long6">
		<INPUT type="hidden" id="long7" name="long7">
		<INPUT type="hidden" id="long8" name="long8">

		<INPUT type="hidden" id="long9" name="long9">
		<INPUT type="hidden" id="long10" name="long10">
		<INPUT type="hidden" id="long11" name="long11">

		<INPUT type="hidden" id="long12" name="long12">

		<INPUT type="hidden" id="long13" name="long13">
		<INPUT type="hidden" id="long14" name="long14">
		<INPUT type="hidden" id="long15" name="long15">
		<INPUT type="hidden" id="long16" name="long16">
		<INPUT type="hidden" id="long17" name="long17">

		<INPUT type="hidden" id="long18" name="long18">
		<INPUT type="hidden" id="long19" name="long19">

		<INPUT type="hidden" id="long20" name="long20">

		<INPUT type="hidden" id="long21" name="long21">
		<INPUT type="hidden" id="long22" name="long22">
		<INPUT type="hidden" id="long23" name="long23">
		<INPUT type="hidden" id="long24" name="long24">
		<INPUT type="hidden" id="long25" name="long25">
		
		<input type="hidden" id="pkey" name="pkey" value="2411">
		
		</form>

		
		<!--
		<button onclick="landsat.setVisibility(false);">Topography</button>
		<button onclick="landsat.setVisibility(true);">Satellite</button>
		-->

    <div id="mapDiv"></div>

</td></tr></table>

    <script type="text/javascript">
        <!--

		var num_markers = 0;
		var marker = new Array();
		marker[1] = null;
		marker[2] = null;
		var ptLat = new Array();
		ptLat[1] = -1;
		ptLat[2] = -1;
		var ptLong = new Array();
		ptLong[1] = -1;
		ptLong[2] = -1;
		env = '';
		var tminx, tmaxx;
		var tminy, tmaxy;
		var cnt_x, cnt_y;

		// Function to convert normal latitude/longitude to mercator easting/northings
		function LonToGmaps(lon) {
				MAGIC_NUMBER=6378137;
				DEG2RAD=0.017453292519943295;
				PI=3.141592653589793;

				var gmaplon = (MAGIC_NUMBER*DEG2RAD*lon);

		return gmaplon;
		}
		function LatToGmaps(lat) {
				MAGIC_NUMBER=6378137;
				DEG2RAD=0.017453292519943295;
				PI=3.141592653589793;

				var gmaplat = MAGIC_NUMBER*Math.log(Math.tan(((lat*DEG2RAD)+(PI/2)) /2));

		return gmaplat;
		}

        //map = new OpenLayers.Map('mapDiv', {maxResolution: 'auto'});


        var extent = new OpenLayers.Bounds(-180, -90, 180, 90);
		
		//maxResolution: 'auto'
		
		var options = {restrictedExtent: extent, maxResolution: 'auto'};
		//var options = {maxResolution: 'auto'};

		OpenLayers.ImgPath = "openlayers/theme/dark/";
        
        //map = new OpenLayers.Map('mapDiv', options);
        map = new OpenLayers.Map('mapDiv', options);


		//var topo = new OpenLayers.Layer.WMS( "Topo",
        //              "http://matisse.kgs.ku.edu/mapserver/topo_redirect.php?",
        //              {layers: 'topo', format: 'jpeg', SRS : "AUTO" } );
		//topo.addOptions({isBaseLayer: true});


		var topo = new OpenLayers.Layer.WMS( "Topo",
                      "http://gmrt.marine-geo.org/cgi-bin/mapserv?map=/public/mgg/web/gmrt.marine-geo.org/htdocs/services/map/wms_merc.map&",
                      {layers: 'topo', format: 'jpeg', SRS : "AUTO" });
		topo.addOptions({isBaseLayer: true});

		var lines = new OpenLayers.Layer.WMS( "Lines",
                      "http://www.geochron.org/cgi-bin/mapserv?map=/public/mgg/web/www.geochron.org/htdocs/lines.map&",
                      {layers: 'state_line,country_line', transparent: true, format: 'png', SRS : "AUTO" });
		//lines.addOptions({isBaseLayer: true});



		//http://www.geochron.org/cgi-bin/mapserv?map=/public/mgg/web/www.geochron.org/htdocs/lines.map&

                var landsat = new OpenLayers.Layer.WMS(
                    "NASA Global Mosaic",
                    "http://t1.hypercube.telascience.org/cgi-bin/landsat7",
                    {layers: "landsat7"} );
                    landsat.addOptions({isBaseLayer: false});
                    landsat.setVisibility(false);

		var markers = new OpenLayers.Layer.Markers( "Specimen Locations" );






		/*
		* Layer style
		*/
		// we want opaque external graphics and non-opaque internal graphics
		var layer_style = OpenLayers.Util.extend({}, OpenLayers.Feature.Vector.style['default']);
		layer_style.fillOpacity = 0.2;
		layer_style.graphicOpacity = 0.5;


		/*
		* Blue style
		*/
		var style_blue = OpenLayers.Util.extend({}, layer_style);
		style_blue.strokeColor = "blue";
		style_blue.fillColor = "blue";

		/*
		* Green style
		*/
		var style_green = {
		strokeColor: "#FF0000",
		strokeOpacity: 0.5,
		strokeWidth: 5,
		pointRadius: 6,
		pointerEvents: "visiblePainted"
		};



		var vectorLayer = new OpenLayers.Layer.Vector("Simple Geometry", {style: layer_style});



		map.addLayers([lines,topo,landsat,vectorLayer,markers]);
		//0map.addControl(new OpenLayers.Control.LayerSwitcher());
		map.addControl(new OpenLayers.Control.MousePosition());
		map.setCenter(new OpenLayers.LonLat(-112, 39), 3);


		AutoSizeAnchored = OpenLayers.Class(OpenLayers.Popup.Anchored, {
		'autoSize': true
		});

		//anchored popup small contents autosize
		ll = new OpenLayers.LonLat(LonToGmaps(-95),LatToGmaps(39));
		popupClass = AutoSizeAnchored;
		popupContentHTML = '<img src="small.jpg"></img>';

		var size = new OpenLayers.Size(10,10);
		var offset = new OpenLayers.Pixel(-5, -5);
		var icon = new OpenLayers.Icon('http://boston.openguides.org/markers/AQUA.png',size,offset);


		function addMarker(ll, popupClass, popupContentHTML, closeBox, overflow) {
			var feature = new OpenLayers.Feature(markers, ll);
			feature.closeBox = closeBox;
			feature.popupClass = popupClass;
			feature.data.popupContentHTML = popupContentHTML;
			feature.data.overflow = (overflow) ? "auto" : "hidden";

			var marker = feature.createMarker();
			marker.icon = new OpenLayers.Icon('http://www.geochron.org/reddot.png',size,offset);

			markers.addMarker(marker);
        	}

		map.events.register("click", map, function(e) {
		var lonlat = map.getLonLatFromViewPortPx(e.xy);
		var bounds = map.getExtent().toBBOX();

				//var mylong = getLongitudeFromMapObjectLonLat(lonlat);
				//var mylat = getLatitudeFromMapObjectLonLat(lonlat);

				//alert("You clicked near " + lonlat.lat + "," + lonlat.lon + " , bbox=" + bounds);

				//foofoo();

				//anchored popup small contents autosize





			if (num_markers == 25) return;
			ll = new OpenLayers.LonLat(lonlat.lon,lonlat.lat);
			popupClass = AutoSizeAnchored;
			popupContentHTML = 1;
			addMarker(ll, popupClass, popupContentHTML);
			num_markers++;


			var magic=6378137;
			var deg2rad=0.017453292519943295;
			var pi=3.141592653589793;
			londd=lonlat.lon/(magic*deg2rad);
			latdd=(2*Math.atan(Math.exp(lonlat.lat/magic))-(pi/2))/deg2rad;

			//marker[num_markers] = new OpenLayers.Geometry.Point(londd, latdd);

			marker[num_markers] = new OpenLayers.Geometry.Point(lonlat.lon, lonlat.lat);



			if (num_markers > 2){
				vectorLayer.eraseFeatures(lineFeature);
				vectorLayer.removeFeatures(lineFeature);
				vectorLayer.destroyFeatures(lineFeature);
			}

			if (num_markers > 1){
				var pointList = [];
				for(var p=1; p<=num_markers; ++p) {
				newPoint = marker[p];
				pointList.push(newPoint);
				}
				newPoint = marker[1];
				pointList.push(newPoint);
				lineFeature = new OpenLayers.Feature.Vector(
				new OpenLayers.Geometry.LineString(pointList),null,style_green);
				vectorLayer.addFeatures(lineFeature);
			}


			document.getElementById('lat'+num_markers).value = lonlat.lat;
			document.getElementById('long'+num_markers).value = lonlat.lon;


		});

		function updatesearch(){
			var pointstring='';
			var pointdelim='';
			for(var p=1; p<=25; ++p) {
				if(document.getElementById('lat'+p).value != '' && document.getElementById('long'+p).value != ''){
					pointstring = pointstring + pointdelim + document.getElementById('long'+p).value + ' ' + document.getElementById('lat'+p).value;
					pointdelim='; ';
				}
			}
			
			//alert(pointstring);
			//parent.document.getElementById('coordinates').value = pointstring;
			
			parent.updatecoordinates(pointstring);
			//parent.Shadowbox.close();
		}
		
		function foofoo(){
			markers.clearMarkers();
			vectorLayer.eraseFeatures(lineFeature);
			vectorLayer.removeFeatures(lineFeature);
			vectorLayer.destroyFeatures(lineFeature);
			num_markers = 0;
			document.getElementById('lat1').value = '';
			document.getElementById('lat2').value = '';
			document.getElementById('lat3').value = '';
			document.getElementById('lat4').value = '';
			document.getElementById('lat5').value = '';
			document.getElementById('lat6').value = '';
			document.getElementById('lat7').value = '';
			document.getElementById('lat8').value = '';
			document.getElementById('lat9').value = '';
			document.getElementById('lat10').value = '';
			document.getElementById('lat11').value = '';
			document.getElementById('lat12').value = '';
			document.getElementById('lat13').value = '';
			document.getElementById('lat14').value = '';
			document.getElementById('lat15').value = '';
			document.getElementById('lat16').value = '';
			document.getElementById('lat17').value = '';
			document.getElementById('lat18').value = '';
			document.getElementById('lat19').value = '';
			document.getElementById('lat20').value = '';
			document.getElementById('lat21').value = '';
			document.getElementById('lat22').value = '';
			document.getElementById('lat23').value = '';
			document.getElementById('lat24').value = '';
			document.getElementById('lat25').value = '';
			document.getElementById('long1').value = '';
			document.getElementById('long2').value = '';
			document.getElementById('long3').value = '';
			document.getElementById('long4').value = '';
			document.getElementById('long5').value = '';
			document.getElementById('long6').value = '';
			document.getElementById('long7').value = '';
			document.getElementById('long8').value = '';
			document.getElementById('long9').value = '';
			document.getElementById('long10').value = '';
			document.getElementById('long11').value = '';
			document.getElementById('long12').value = '';
			document.getElementById('long13').value = '';
			document.getElementById('long14').value = '';
			document.getElementById('long15').value = '';
			document.getElementById('long16').value = '';
			document.getElementById('long17').value = '';
			document.getElementById('long18').value = '';
			document.getElementById('long19').value = '';
			document.getElementById('long20').value = '';
			document.getElementById('long21').value = '';
			document.getElementById('long22').value = '';
			document.getElementById('long23').value = '';
			document.getElementById('long24').value = '';
			document.getElementById('long25').value = '';
		}

        // -->
    </script>

</td>
</tr>
</table>





</body></html>
