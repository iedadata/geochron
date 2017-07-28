<?PHP
/**
 * indsampleinteractivemap.php
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

session_start();

//print_r($_SESSION);



include("db.php");

//include("logincheck.php");


if($_SESSION['userpkey']!=""){
	$userpkey=$_SESSION['userpkey'];
}else{
	$userpkey="99999";
}

$lat=$_GET['lat'];
if($lat==""){
	exit();
}

$lon=$_GET['lon'];
if($lon==""){
	exit();
}




//include("includes/geochron-secondary-header.htm");
?>

<style type="text/css">
/*<![CDATA[*/
-->

body {

	margin: 0px;

}

#mapDivdynbig {
	z-index: -25;
	width: 600px;
	height: 400px;
	border: 1px solid black;
}
-->
/*]]>*/
</style>
<body>
<?




$north=$lat;
$south=$lat;
$east=$lon;
$west=$lon;
		
$north=$north+1;
$south=$south-1;
$east=$east+1;
$west=$west-1;

if($north>90){$north=90;}
if($south<-90){$south=-90;}
if($east>180){$east=180;}
if($west<-180){$west=-180;}



$mapbounds="$west,$south,$east,$north";
		
//echo $mapbounds;exit();
?>

<script src="openlayers/OpenLayers.js"></script>

<script src="mapAjaxRequest.js" type="text/javascript"></script>
<script src="usermapajax.js" type="text/javascript"></script>



<table class="aboutpage" width="100%" cellpadding="5">
<tr><td>

	<!--
	<table>
	<tr><td colspan="2"><H1>Geochron Dynamic Map</H1></td></tr>
	<tr>
		<td>
			<div class="mapdetails">
			The map below contains samples from the current search query. You can drag the map dynamically,
			as well as use the zoom bar to zoom in and out. Use shift-click to create a dynamic zoom range for
			more detailed zooming. To get sample details, click on the individual samples.
			</div>

		</td>
	</tr>
	</table>
	-->
	
	<div id="mapDivdynbig"></div>
	


	

  </body>
    <script type="text/javascript">
        <!--
			var newwindow;
			function popwindow(url)
			{
				newwindow=window.open(url,'name','height=600,width=800,scrollbars=1');
				if (window.focus) {newwindow.focus()}
			}




			var options = { maxResolution: "auto",
							minResolution: "auto",
						  };

			OpenLayers.ImgPath = "openlayers/theme/dark/";

			map = new OpenLayers.Map( 'mapDivdynbig' , options);


            //map = new OpenLayers.Map('mapDivdyn', {maxResolution: 0.17578125});
            //"http://neko.kgs.ku.edu/cgi-bin/mapserv?map=/var/world/world.map&",

		/*
		var old_ol_wms = new OpenLayers.Layer.WMS.Untiled( "Metacarta",
		"http://mapdmzrec.brgm.fr/cgi-bin/mapserv54?map=/carto/ogg/mapFiles/CGMW_Bedrock_and_Structural_Geology.map&",
		    "http://labs.metacarta.com/wms/vmap0?", {layers: 'basic'} );
		old_ol_wms.addOptions({isBaseLayer: true});
		*/

		/*
		var old_ol_wms = new OpenLayers.Layer.WMS.Untiled( "Test World",
		"http://ngmdb.geos.pdx.edu/cgi-bin/mapserv.cgi?map=/vol/www/ngmdb/htmaps/gmna/GMNA_OWS_display.map&",
		 {layers: 'GMNA_raster'}, {singleTile: true} );
		old_ol_wms.addOptions({isBaseLayer: true});
		*/
		
		var old_ol_wms = new OpenLayers.Layer.WMS.Untiled( "Geological Units",
		"http://mapdmzrec.brgm.fr/cgi-bin/mapserv54?map=/carto/ogg/mapFiles/CGMW_Bedrock_and_Structural_Geology.map&",
		 {layers: 'World_CGMW_50M_GeologicalUnitsOnshore,World_CGMW_50M_GeologicalUnitsOffshore'}, {singleTile: false} );
		old_ol_wms.addOptions({isBaseLayer: true});		
		
		
		//http://129.206.228.72/cached/osm?LAYERS=osm_auto:all
		//http://129.206.228.72/cached/osm?LAYERS=osm_auto%3Aall&SRS=EPSG%3A900913&FORMAT=image%2Fpng&SERVICE=WMS&VERSION=1.1.1&REQUEST=GetMap&STYLES=&BBOX=12523442.7112,10018754.1688,15028131.2536,12523442.7112&WIDTH=256&HEIGHT=256
		//var omsmap = new OpenLayers.Layer.WMS.Untiled( "Streets Map",
		//"http://129.206.228.72/cached/osm&",
		// {layers: 'osm_auto%3Aall'}, {singleTile: false} );
		//omsmap.addOptions({isBaseLayer: true});	
		
		//http://ows.terrestris.de/osm/service?

		var osmmap = new OpenLayers.Layer.WMS.Untiled( "Streets",
		"http://ows.terrestris.de/osm/service?",
		 {layers: 'OSM-WMS'}, {singleTile: false} );
		old_ol_wms.addOptions({isBaseLayer: true});		
		
		


            //var new_ol_wms = new OpenLayers.Layer.WMS.Untiled( "WMS w/singleTile",
            //    "http://labs.metacarta.com/wms/vmap0?", {layers: 'basic'},
            //    { singleTile: true, ratio: 1 } );
            //new_ol_wms.addOptions({isBaseLayer: true});
			//http://gmrt.marine-geo.org/cgi-bin/mapserv?map=/public/mgg/web/gmrt.marine-geo.org/htdocs/services/map/wms_merc.map&
			//http://neko.kgs.ku.edu/cgi-bin/mapserv?map=/marinemaps/wms.map&
		
		var topo = new OpenLayers.Layer.WMS( "Bathymetry",
                      "http://gmrt.marine-geo.org/cgi-bin/mapserv?map=/public/mgg/web/gmrt.marine-geo.org/htdocs/services/map/wms_merc.map&",
                      {layers: 'topo', format: 'jpeg', SRS : "AUTO" } );
		topo.addOptions({isBaseLayer: true});

			myzoom=0;

            var foolayer = new OpenLayers.Layer.WMS.Untiled( "Samples",
		    "indsampledynmap.php?lat=<?=$lat?>&lon=<?=$lon?>&label=yes&zoom="+myzoom+"&", {layers: 'basic'},
                { singleTile: true, ratio: 1 } );
            foolayer.addOptions({isBaseLayer: false});




			


            map.addLayers([topo,foolayer,old_ol_wms,osmmap]);
            
            map.addControl(new OpenLayers.Control.LayerSwitcher());
            
            //map.setCenter(new OpenLayers.LonLat(-112, 39), 8);
            
            var bounds = new OpenLayers.Bounds(<?=$mapbounds?>);
            
            map.zoomToExtent(bounds);
            
            myzoom=map.getZoom();
            
            map.maxZoomLevel=5;
			


           	
		function drawagain() {
			myrandnum=Math.floor(Math.random()*11);
			foolayer.destroy();
        }

		function drawagainb() {
            foolayer = new OpenLayers.Layer.WMS.Untiled( "Samples",
		    "indsampledynmap.php?lat=<?=$lat?>&lon=<?=$lon?>&", {layers: 'basic'},
                { singleTile: true, ratio: 1 } );
            foolayer.addOptions({isBaseLayer: false});
			map.addLayers([foolayer]);
			return false;
        }

		function dosearch() {
			var foodate = new Date;
			var unixtime_ms = foodate.getTime();
			var unixtime = parseInt(unixtime_ms / 1000);
			foolayer.destroy();
			myrandnum=Math.floor(Math.random()*11);
            foolayer = new OpenLayers.Layer.WMS.Untiled( "Samples",
		    "indsampledynmap.php?lat=<?=$lat?>&lon=<?=$lon?>&randnum="+unixtime+myrandnum+"&", {layers: 'basic'},
                { singleTile: true, ratio: 1 } );
            foolayer.addOptions({isBaseLayer: false});
			map.addLayers([foolayer]);
			return false;
        }

        // -->
    </script>







		<script type="text/javascript">


		


		getcount = function () {

			var bounds = map.getExtent().toBBOX();
			//alert(bounds);

			var urlstring='';
			var delimiter='';

			var foodate = new Date;
			var unixtime_ms = foodate.getTime();
			var unixtime = parseInt(unixtime_ms / 1000);
			foolayer.destroy();
			myrandnum=Math.floor(Math.random()*11);
			
			myzoom=map.getZoom();
			//alert(myzoom);
			//alert('hi');
			
			urlstring="indsampledynmap.php?lat=<?=$lat?>&lon=<?=$lon?>&randnum="+unixtime+myrandnum+"&zoom="+myzoom
			


			//wrap up the urlstring
			urlstring+="&";
			
			//document.getElementById('myurl').innerHTML=urlstring;
			foolayer = new OpenLayers.Layer.WMS.Untiled( "Samples",
			urlstring, {layers: 'basic'},
				{ singleTile: true, ratio: 1 } );
			foolayer.addOptions({isBaseLayer: false});
			map.addLayers([foolayer]);
			//return false;



		}




		</script>
































</div>

</html>