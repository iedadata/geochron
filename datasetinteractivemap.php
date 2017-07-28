<?PHP
/**
 * datasetinteractivemap.php
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

include("logincheck.php");


if($_SESSION['userpkey']!=""){
	$userpkey=$_SESSION['userpkey'];
}else{
	$userpkey="99999";
}

$id=$_GET['id'];
if($id==""){
	exit();
}

$drrow=$db->get_row("select * from datasets where dataset_pkey=$id;");
$datasetname = $drrow->datasetname;

$totalcount=$db->get_var("select count(*) 
								from 
								(select 
								samp.sample_pkey
								from 
								sample samp
								left join groupsamplerelate gsr on gsr.sample_pkey = samp.sample_pkey
								left join grouprelate grp on grp.group_pkey = gsr.group_pkey
								left join groups on grp.group_pkey = groups.group_pkey
								left join datasetrelate dr on dr.sample_pkey = samp.sample_pkey
								left join datasetuserrelate dur on dur.dataset_pkey = dr.dataset_pkey
								left join datasets ds on dr.dataset_pkey = ds.dataset_pkey
								where (samp.userpkey=$userpkey or ((dur.users_pkey=$userpkey and dur.confirmed=true) or ds.users_pkey=$userpkey ) or ((grp.users_pkey = $userpkey and grp.confirmed=true) or groups.users_pkey=$userpkey)) and del=0 
								and dr.dataset_pkey=$id
								group by
								samp.sample_pkey) foo");

include("includes/geochron-secondary-header.htm");



$mapstring="select 
			longitude,
			latitude
			from 
			sample samp
			left join groupsamplerelate gsr on gsr.sample_pkey = samp.sample_pkey
			left join grouprelate grp on grp.group_pkey = gsr.group_pkey
			left join groups on grp.group_pkey = groups.group_pkey
			left join datasetrelate dr on dr.sample_pkey = samp.sample_pkey
			left join datasetuserrelate dur on dur.dataset_pkey = dr.dataset_pkey
			left join datasets ds on dr.dataset_pkey = ds.dataset_pkey
			where (samp.userpkey=$userpkey or ((dur.users_pkey=$userpkey and dur.confirmed=true) or ds.users_pkey=$userpkey ) or ((grp.users_pkey = $userpkey and grp.confirmed=true) or groups.users_pkey=$userpkey)) and del=0 
			and dr.dataset_pkey=$id
			group by
			latitude,longitude";

$myrow=$db->get_row("select min(latitude) as south,
			max(latitude) as north,
			min(longitude) as west,
			max(longitude) as east
		from ($mapstring) foo");

$north=$myrow->north;
$south=$myrow->south;
$east=$myrow->east;
$west=$myrow->west;
		
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

<script src="js/mapAjaxRequest.js" type="text/javascript"></script>
<script src="js/usermapajax.js" type="text/javascript"></script>


<form name="upstreamform">
	<table class="aboutpage">
		<tr>	

			<td valign="top">
				<table class="aboutpage" border=0>

					<tr>
						<td><h1>Dataset: <?=$datasetname?></h1></td>
					</tr>

					<tr>
						<td>
							<b>Show Sample IDs</b> <input type="checkbox" name="labelids" id="labelids" onchange="getcount();" checked>
						</td>
					</tr>




					<tr>
						<td>
							<b>Figures:</b>&nbsp;&nbsp;
						<!--
						</td>
						<td>
						-->
							<input type="radio" name="showfig" value="conc" onclick="getcount();" > Show Concordia Diagrams
							<input type="radio" name="showfig" value="prob" onclick="getcount();" > Show Probability Density Diagrams
							<input type="radio" name="showfig" value="points" onclick="getcount();" CHECKED> Show points
						</td>
						<td>
							&nbsp;
						</td>
					</tr>

				</table>
			</td>
		</tr>
	</table>
</form>

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
	<table class="aboutpage">

	<tr>
	<td valign="top">
		<div id="mapDivdynbig"></div>
	</td>
	<td>&nbsp;&nbsp;&nbsp;</td>
	<td valign="top">
	    <div id="animationbar" style="display:none;"><img src="/images/loadingAnimation.gif"></div>
		<div id="results">




	<table class="aboutpage" width="250" cellpadding="2" cellspacing="1" bgcolor="#333333">
		<tr>
			<td bgcolor="#333333">
				<table class="aboutpage" width="100%">
					<tr>
					<td><font color="#FFFFFF"><strong>Instructions: </strong></font></td>
					<td></td>
					<td>&nbsp;</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td bgcolor="#FFFFFF">
					Use the options to the left to change the map view. The map can be
					dragged and zoomed to better view the data. You can click on individual samples
					to view details.
			</td>
		</tr>
	</table>






		</div>
		
		
		<br><hr><br>
		<img src="images/maplegend.jpg">
		<div id="allcount" align="center" style="padding-top:20px;">Sample&nbsp;Count: <?=$totalcount?></div>
		<div id="constrainedcount" align="center" style="color:green;"></div>
		<div id="showtable" style="padding-top:20px;display:none;" align="center">
		<input type="button" value="View Results in HTML Table" onClick="window.open(resultsurl)"> <br><br>
		<input type="button" value="View Results in Excel" onClick="window.open(excelurl)">
		<br><hr><br><br>
		<div id="envelopeconstrainedcount" align="center" style="color:green;"></div>
		<div id="envelopeshowtable" style="padding-top:20px;display:none;" align="center">
		<input type="button" value="View Results in HTML Table" onClick="window.open(enveloperesultsurl)"> <br><br>
		<input type="button" value="View Results in Excel" onClick="window.open(envelopeexcelurl);">
		<!--<input type="button" value="View Results in Excel" onClick="alert(envelopeexcelurl);">-->
		</div>
		<div id="debug"></div>

	</td>
	</tr>
	</table>
	<br>
	<br>
	<div id="pointdetail"></div>
	

  </body>
    <script type="text/javascript">
        <!--
			var newwindow;
			function popwindow(url)
			{
				newwindow=window.open(url,'name','height=600,width=800,scrollbars=1');
				if (window.focus) {newwindow.focus()}
			}

			function popconcordia(url)
			{
				newwindow=window.open(url,'name','height=440,width=600,scrollbars=1');
				if (window.focus) {newwindow.focus()}
			}


			function showprobability(){
				//alert('heyo');
				document.getElementById("concordiadiv").style.display="none";
				document.getElementById("probabilitydiv").style.display="block";
				
				document.getElementById("concordiatab").style.background="#FFFFFF";
				document.getElementById("probabilitytab").style.background="#CCCCCC";
				document.getElementById("probabilitytab").style.color="#000000";
				document.getElementById("probabilitytab").innerHTML="Probability&nbsp;Density";
				document.getElementById("concordiatab").innerHTML='<a  style="color:#3333CC;" href="javascript:showconcordia();">Concordia&nbsp;Diagram<\/>';


			}

			function showconcordia(){
				//alert('heyo');
				document.getElementById("probabilitydiv").style.display="none";
				document.getElementById("concordiadiv").style.display="block";
				
				document.getElementById("probabilitytab").style.background="#FFFFFF";
				document.getElementById("concordiatab").style.background="#CCCCCC";
				document.getElementById("concordiatab").style.color="#000000";
				document.getElementById("concordiatab").innerHTML="Concordia&nbsp;Diagram";
				document.getElementById("probabilitytab").innerHTML='<a  style="color:#3333CC;" href="javascript:showprobability();">Probability&nbsp;Density<\/>';


			}



            ////map = new OpenLayers.Map('mapDivdyn', {minZoomLevel:5});

			//var options = { maxResolution: 0.17578125,
			//				minResolution: "auto",
			//			  };

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
		    "datasetdynmap.php?id=<?=$id?>&label=yes&zoom="+myzoom+"&", {layers: 'basic'},
                { singleTile: true, ratio: 1 } );
            foolayer.addOptions({isBaseLayer: false});




			


            map.addLayers([topo,foolayer,old_ol_wms,osmmap]);
            
            map.addControl(new OpenLayers.Control.LayerSwitcher());
            
            //map.setCenter(new OpenLayers.LonLat(-112, 39), 8);
            
            var bounds = new OpenLayers.Bounds(<?=$mapbounds?>);
            
            map.zoomToExtent(bounds);
            
            myzoom=map.getZoom();
            
            map.maxZoomLevel=5;
			
			map.events.register("zoomend", map, function(e) {
			
				//alert('zoom changed.');
				getcount();
			
			})
			
			map.events.register("moveend", map, function(e) {
			
				//alert('move end.');
				getcount();
			
			})
			

			
          	map.events.register("click", map, function(e) {
               	var lonlat = map.getLonLatFromViewPortPx(e.xy);
               	var bounds = map.getExtent().toBBOX();

				//alert(bounds);


				for (var thiscount=0; thiscount < 3; thiscount++){
					if (document.upstreamform.showfig[thiscount].checked){
						//alert(document.upstreamform.upstreamshowall[thiscount].value);
						//urlstring+='&showall='+document.upstreamform.upstreamshowall[thiscount].value;
						showfig=document.upstreamform.showfig[thiscount].value;
						//alert('showall:'+showall);
					}
				}

				
				show_my_form(lonlat.lat, lonlat.lon, myzoom, showfig);




           	});
           	
		function drawagain() {
			myrandnum=Math.floor(Math.random()*11);
			foolayer.destroy();
        }

		function drawagainb() {
            foolayer = new OpenLayers.Layer.WMS.Untiled( "Samples",
		    "datasetdynmap.php?id=<?=$id?>&", {layers: 'basic'},
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
		    "datasetdynmap.php?id=<?=$id?>&randnum="+unixtime+myrandnum+"&", {layers: 'basic'},
                { singleTile: true, ratio: 1 } );
            foolayer.addOptions({isBaseLayer: false});
			map.addLayers([foolayer]);
			return false;
        }

        // -->
    </script>

</td></tr>
</table>



<div id="myurl">

<div id="mycount"></div>
































































		<script type="text/javascript">
		
		resultsurl='detritalresults.php';
		excelurl='detritalexcel.php';

		


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
			
			urlstring="datasetdynmap.php?id=<?=$id?>&randnum="+unixtime+myrandnum+"&zoom="+myzoom
			




			//labels
			if(document.getElementById("labelids").checked == true){
				urlstring+='&label=yes'
			}


			//check showconc radio button
			for (var thiscount=0; thiscount < 3; thiscount++){
				if (document.upstreamform.showfig[thiscount].checked){
					//alert(document.upstreamform.upstreamshowall[thiscount].value);
					urlstring+='&showfig='+document.upstreamform.showfig[thiscount].value;
				}
			}

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
<?php
include("includes/geochron-secondary-footer.htm");
?>