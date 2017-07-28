<?PHP
/**
 * detritalsearch.php
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

// **************** get username stuff here for the search ***************
if($_SESSION['username']!=""){
	$username=$_SESSION['username'];
	//$userrow=$db->get_row("select * from users where username='$username'");
	$userrow=$db->get_row("select * from users where email='$username'");
	$group=$userrow->usergroup;
	$grouparray=$userrow->grouparray;
	$userpkey=$userrow->users_pkey;
}elseif($_POST['username']!="" & $_POST['password']!=""){
	$username=$_POST['username'];
	$password=$_POST['password'];
	$userrow=$db->get_row("select * from users where username='$username' and password='$password'");
	$group=$userrow->usergroup;
	$userpkey=$userrow->users_pkey;
}


if($group==0 or $group==""){
	$group=99999;
}

if($userpkey==""){
	$userpkey=99999;
}

if($grouparray==""){
	$grouparray=99999;
}

//echo "username:$username group:$group <br>";

$grouparray=str_replace("{","",$grouparray);
$grouparray=str_replace("}","",$grouparray);


//echo "grouparray: $grouparray<br>";

//*************************************************************************





if($_SESSION['userpkey']!=""){
	$userpkey=$_SESSION['userpkey'];
}else{
	$userpkey="99999";
}



$totalcount=$db->get_var("select count(*) from (
							select sample.sample_pkey
							from sample 
							left join sample_age on sample.sample_pkey = sample_age.sample_pkey
							left join groupsamplerelate on sample.sample_pkey = groupsamplerelate.sample_pkey
							left join grouprelate on groupsamplerelate.group_pkey = grouprelate.group_pkey
							left join datasetrelate dr on dr.sample_pkey = sample.sample_pkey
							left join datasetuserrelate dur on dur.dataset_pkey = dr.dataset_pkey
							left join datasets ds on dr.dataset_pkey = ds.dataset_pkey
							where (sample.publ=1 or sample.userpkey=$userpkey or ((dur.users_pkey=$userpkey and dur.confirmed=true) or ds.users_pkey=$userpkey ) or (grouprelate.users_pkey=$userpkey and grouprelate.confirmed=true))
							and sample.upstream=true
							group by sample.sample_pkey
							) foo");



//echo "totalcount: $totalcount<br>";

/*
//put in detrital type
for($x=1;$x<5499;$x++){

	$thisrand=rand(0,9);
	echo "detritaltype$thisrand<br>";
	//$db->query("update testdata set detritaltype='detritaltype$thisrand' where pkey=$x");

}
exit();
*/

/*
//make some testdata

$chars="ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

echo "<table>";

for($x=1;$x<5000;$x++){

	$randstring="";
	for($y=1;$y<9;$y++){
		
		$randstring.=substr($chars,rand(0,35),1);
		
	}
	
	
	$lon=(rand(9999,230000)/10000)+26;
	$lat=((rand(9999,580000)/10000)+66)*-1;
	$age=((rand(99,19000)/100)+65);
	


	echo "<tr><td>lat: $lat</td><td>lon: $lon</td><td>age: $age</td><td>sampleid: $randstring</td></tr>";
	
	//$db->query("insert into testdata (lat,lon,age,sampleid) values ($lat,$lon,$age,'$randstring')");
}

echo "</table>";

exit();

*/










include("includes/geochron-secondary-header.htm");
//include("includes/geochron-secondary-header_upstream.htm");
//include("includes/geochron-secondary-header.htm");

/*
<link rel="stylesheet" type="text/css" href="slider/fonts-min.css" />
<link rel="stylesheet" type="text/css" href="slider/slider.css" />

<script type="text/javascript" src="slider/yahoo-dom-event.js"></script>

<script type="text/javascript" src="slider/animation-min.js"></script>
<script type="text/javascript" src="slider/dragdrop-min.js"></script>
<script type="text/javascript" src="slider/slider-min.js"></script>
*/
?>


<?


//$pkey=$_GET['pkey'];
$pkey=12345;




//$mapstring=$db->get_var("select querystring from search_query where search_query_pkey=$pkey");

$mapstring="select lon as longitude,
lat as latitude from testdata
--limit 500
";

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

/*
echo "north: $north<br>";
echo "south: $south<br>";
echo "east: $east<br>";
echo "west: $west<br>";
*/

$mapbounds="$west,$south,$east,$north";
		
//exit(); 


/*
echo "navdat: $navdat<br>";
echo "petdb: $petdb<br>";
echo "georoc: $georoc<br>";
echo "usgs: $usgs<br>";
*/

//<script src="http://openlayers.org/api/OpenLayers.js"></script>
//<script src="openlayers/OpenLayers.js"></script>
//<script src="http://openlayers.org/dev/OpenLayers.js"></script>
?>

<script src="openlayers/OpenLayers.js"></script>




<script src="js/jquery.js"></script>

<script src="js/mapAjaxRequest.js" type="text/javascript"></script>
<script src="js/upstreamajax.js" type="text/javascript"></script>

<!--
<input type="button" onclick="drawagain();" value="delete map">
<input type="button" onclick="drawagainb();" value="redraw map">
<input type="button" onclick="dosearch();" value="do both">
<br>
-->

<form name="upstreamform">
	<table class="aboutpage">
		<tr>	
			<td>
				<table class="aboutpage">
					<tr>

						<td>
							<b>Host Rock Age: (Ma) </b>&nbsp;&nbsp;
						</td>

						<td>
							<label>Min: <input type="text" id="age_min" size="5" maxlength="8" value="" ></label>

							<label> Max: <input type="text" id="age_max" size="5" maxlength="8" value="" ></label>
						</td>

					</tr>
					<!--
					<tr>
						<td><b>or</b></td>
						<td>&nbsp;</td>
					</tr>
					-->

					<tr>
						<td><b>or Geological Age</b></td>
						<td>
							<select class="geoageselect" id="geoage" size="5" multiple style="font-size:.8em;">
							<option value="">ALL
							
							<?
							
							$rows=$db->get_results("select * from geoages order by pkey");
							
							foreach($rows as $row){
								
										$showline="<option value=\"".$row->pkey."\">";
								
								if($row->indentcount!=0){
							
									for($x=0;$x<$row->indentcount;$x++){
										$showline.="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
									}
									
									$showline.="&raquo; ";
								}
								
								$showline.=$row->xmllabel."\n";
								echo $showline;
							}
							
							
							?>
							
							</select>
						</td>
					</tr>
				</table>
			</td>
			<td>
				&nbsp;&nbsp;&nbsp;
			</td>
			<td valign="top">
				<table class="aboutpage" border=0>
<?
$detritaltypes=$db->get_results("select distinct(detrital_type) 
								from 
								sample
								left join users on sample.userpkey = users.users_pkey
								where detrital_type!='' 
								and detrital_type!='NONE' 
								and upstream=TRUE 
								--and (sample.publ=1 or users.usergroup=$group or users.users_pkey=$userpkey)
								and (sample.publ=1 or array_intersect(users.grouparray, ARRAY[$grouparray]) is not null or users.users_pkey=$userpkey)
								");
?>
					<tr>
						<td nowrap>
							<b>Detrital Rock Type:</b>&nbsp;&nbsp;
						<!--
						</td>
						<td>
						-->
							<select name="detritaltype" id="detritaltype" onchange="getcount();">
								<option value="">ALL</option>
<?
foreach($detritaltypes as $dt){
?>
								<option value="<?=$dt->detrital_type?>"><?=$dt->detrital_type?></option>
<?
}
?>
							</select>
					<!--
						</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
					</tr>-->



<?
$mineralnames=$db->get_results("select distinct(material) 
								from 
								sample
								left join users on sample.userpkey = users.users_pkey
								where material!=''
								and upstream=TRUE 
								--and (sample.publ=1 or users.usergroup=$group or users.users_pkey=$userpkey)
								and (sample.publ=1 or array_intersect(users.grouparray, ARRAY[$grouparray]) is not null or users.users_pkey=$userpkey)
								");
?>
					<!--<tr>
						<td>
						-->
						&nbsp;&nbsp;&nbsp;
						
							<b>Detrital Mineral:</b>&nbsp;&nbsp;
						<!--
						</td>
						<td>
						-->
							<select name="detritalmineral" id="detritalmineral" onchange="getcount();">
								<option value="">ALL</option>
<?
foreach($mineralnames as $mn){
?>
								<option value="<?=$mn->material?>"><?=$mn->material?></option>
<?
}
?>
							</select>
							
							
							
							<br>
							
							<b>Detrital Method:</b>&nbsp;&nbsp;
							<select name="detritalmethod" id="detritalmethod" onchange="getcount();">
								<option value="">ALL</option>
								<option value="redux">U-Pb</option>
								<option value="helios">(U-Th)/He</option>
								<option value="arar">Ar-Ar</option>
								<option value="fissiontrack">Fission Track</option>

							</select>
							
							
							
						</td>

					</tr>
<?
if($_SESSION['userpkey']!="99999" && $_SESSION['userpkey']!=""){
	$hdisplay="block";
}else{
	$hdisplay="none";
}
?>
					<tr>
						<td>
							<div style="display:<?=$hdisplay?>;">
								<b>Highlight my Samples:</b> <input type="checkbox" name="hmysamples" id="hmysamples" onchange="getcount();">
							</div>
							<b>Show Sample IDs</b> <input type="checkbox" name="labelids" id="labelids" onchange="getcount();" checked>
						</td>
					</tr>



					<tr>
						<td>
							<b>Show:</b>&nbsp;&nbsp;
						<!--
						</td>
						<td>
						-->
							<input type="radio" name="upstreamshowall" value="yes" onclick="getcount();" > Show All Samples
							<input type="radio" name="upstreamshowall" value="no" onclick="getcount();" CHECKED> Show Only Filtered
						</td>
						<td>
							&nbsp;
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
					Use the options to the left to constrain the data. The map can be
					dragged and zoomed to better view the data. You can click on individual samples
					to view details.
			</td>
		</tr>
	</table>






		</div>
		
		<br><hr><br>
		<div id="allcount" align="center" style="padding-top:20px;">Total Sample Count: <?=$totalcount?></div>
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








           	


        // -->
    </script>

</td></tr>
</table>



<div id="myurl">

<div id="mycount"></div>


</div>



    <script type="text/javascript">
    
		
		if ($.browser.msie) {  
		  $(function() {  
			$('input:radio, input:checkbox').click(function() {  
			  this.blur();  
			  this.focus();  
			});  
		  });  
		} 
		
		$( "#geoage" ).change(function() {
			getcount();
		});

		$( "#age_min" ).keyup(function() {
			getcount();
		});

		$( "#age_max" ).keyup(function() {
			getcount();
		});



        var map, topo, foolayer, myzoom;
        
        myzoom=0;

		var options = { maxResolution: "auto",
						minResolution: "auto"
					  };

		map = new OpenLayers.Map( 'mapDivdynbig', options );

		topo = new OpenLayers.Layer.WMS( "Bathymetry",
                      "http://gmrt.marine-geo.org/cgi-bin/mapserv?map=/public/mgg/web/gmrt.marine-geo.org/htdocs/services/map/wms_merc.map&",
                      {layers: 'topo', format: 'jpeg', SRS : "AUTO", isBaseLayer: true } );

		foolayer = new OpenLayers.Layer.WMS.Untiled( "Samples",
						"detritaldynmap.php?pkey=<?=$pkey?>&label=yes&zoom="+myzoom+"&", {layers: 'basic'},
						{ singleTile: true, ratio: 1, isBaseLayer: false } );

		var old_ol_wms = new OpenLayers.Layer.WMS.Untiled( "Geological Units",
						"http://mapdmzrec.brgm.fr/cgi-bin/mapserv54?map=/carto/ogg/mapFiles/CGMW_Bedrock_and_Structural_Geology.map&",
						 {layers: 'World_CGMW_50M_GeologicalUnitsOnshore,World_CGMW_50M_GeologicalUnitsOffshore'}, 
						 {singleTile: false, isBaseLayer: true} );
	
		
		

		map.addLayer(topo);
		map.addLayer(foolayer);
		map.addLayer(old_ol_wms);
		
		map.addControl(new OpenLayers.Control.LayerSwitcher());

		var bounds = new OpenLayers.Bounds(<?=$mapbounds?>);
		
		map.zoomToExtent(bounds);

		map.maxZoomLevel=5;

		map.zoomToMaxExtent();
		
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

				//get ages
				//agemin=((ageslider.minVal)/.7843137).toFixed(1);
				//agemax=((ageslider.maxVal-20)/.7843137).toFixed(1);
				agemin=document.getElementById("age_min").value;;
				agemax=document.getElementById("age_max").value;;
				//alert('agemin: '+agemin);

				//get detritaltype
				detritaltype=document.getElementById("detritaltype").value;
				detritalmineral=document.getElementById("detritalmineral").value;
				detritalmethod=document.getElementById("detritalmethod").value;

				//alert('detritaltype: '+detritaltype);
				
				//check showall radio button
				for (var thiscount=0; thiscount < 2; thiscount++){
					if (document.upstreamform.upstreamshowall[thiscount].checked){
						//alert(document.upstreamform.upstreamshowall[thiscount].value);
						//urlstring+='&showall='+document.upstreamform.upstreamshowall[thiscount].value;
						showall=document.upstreamform.upstreamshowall[thiscount].value;
						//alert('showall:'+showall);
					}
				}

				for (var thiscount=0; thiscount < 3; thiscount++){
					if (document.upstreamform.showfig[thiscount].checked){
						//alert(document.upstreamform.upstreamshowall[thiscount].value);
						//urlstring+='&showall='+document.upstreamform.upstreamshowall[thiscount].value;
						showfig=document.upstreamform.showfig[thiscount].value;
						//alert('showall:'+showall);
					}
				}

				var geoagesel = document.getElementById('geoage');
				var geoagelist='';
				var delim='';
				for (p = 1; p<=geoagesel.length - 1; p++) {
					if (geoagesel.options[p].selected) {
						geoagelist=geoagelist+delim+geoagesel.options[p].value;
						delim=',';
					}
				}
				//alert(geoagelist);
				
				show_my_form(lonlat.lat, lonlat.lon, <?=$pkey?>, myzoom, agemin, agemax, detritaltype, detritalmineral, detritalmethod, geoagelist, showall, showfig);




           	});



















		var resultsurl='detritalresults.php';
		var excelurl='detritalexcel.php';

		


		getcount = function () {

			var bounds = map.getExtent().toBBOX();
			//alert(bounds);



			




			var urlstring='';
			var delimiter='';

			//agemin=((ageslider.minVal)/.7843137).toFixed(1);
			//agemax=((ageslider.maxVal-20)/.7843137).toFixed(1);
			
			agemin=document.getElementById("age_min").value;
			agemax=document.getElementById("age_max").value;
			
			//check=document.getElementById('sio2check'); if(check.checked==1){ urlstring=urlstring+delimiter+'sio2_min='+sio2min+'&sio2_max='+sio2max; delimiter='&'; }

			
			//myurl='lepr_count_wrapper.php?sio2_min='+sio2min+'&sio2_max='+sio2max+'&al2o3_min='+al2o3min+'&al2o3_max='+al2o3max+'&cao_min='+caomin+'&cao_max='+caomax+'&mgo_min='+mgomin+'&mgo_max='+mgomax+'&na2o_min='+na2omin+'&na2o_max='+na2omax+'&k2o_min='+k2omin+'&k2o_max='+k2omax+'&p2o5_min='+p2o5min+'&p2o5_max='+p2o5max+'&mno_min='+mnomin+'&mno_max='+mnomax+'&tio2_min='+tio2min+'&tio2_max='+tio2max+'&feo_min='+feomin+'&feo_max='+feomax;
			
			//myurl='lepr_count_wrapper.php?'+urlstring;
			
			//document.getElementById('myurl').innerHTML=urlstring;
			
			//makeRequest(myurl);

			

			//alert('agemin: '+agemin+' agemax: '+agemax);

			var foodate = new Date;
			var unixtime_ms = foodate.getTime();
			var unixtime = parseInt(unixtime_ms / 1000);
			foolayer.destroy();
			myrandnum=Math.floor(Math.random()*11);
			
			myzoom=map.getZoom();
			//alert(myzoom);
			//alert('hi');
			
			urlstring="detritaldynmap.php?pkey=<?=$pkey?>&randnum="+unixtime+myrandnum+"&zoom="+myzoom
			
			if(agemin!='0.0' || agemax!='255.0'){
				urlstring+='&agemin='+agemin+'&agemax='+agemax;
			}
			
			detritaltype=document.getElementById("detritaltype").value;
			
			if (detritaltype!=''){
				
				//alert('detritaltype: '+detritaltype);
				urlstring+='&detritaltype='+detritaltype
			}

			detritalmineral=document.getElementById("detritalmineral").value;
			
			if (detritalmineral!=''){
				
				//alert('detritalmineral: '+detritalmineral);
				urlstring+='&detritalmineral='+detritalmineral
			}

			detritalmethod=document.getElementById("detritalmethod").value;
			
			if (detritalmethod!=''){
				
				//alert('detritalmethod: '+detritalmethod);
				urlstring+='&detritalmethod='+detritalmethod
			}

			//highlight
			if(document.getElementById("hmysamples").checked == true){
				urlstring+='&hmysamples=yes'
			}

			//labels
			if(document.getElementById("labelids").checked == true){
				urlstring+='&label=yes'
			}



			//check showall radio button
			for (var thiscount=0; thiscount < 2; thiscount++){
				if (document.upstreamform.upstreamshowall[thiscount].checked){
					//alert(document.upstreamform.upstreamshowall[thiscount].value);
					urlstring+='&showall='+document.upstreamform.upstreamshowall[thiscount].value;
				}
			}

			//check showconc radio button
			for (var thiscount=0; thiscount < 3; thiscount++){
				if (document.upstreamform.showfig[thiscount].checked){
					//alert(document.upstreamform.upstreamshowall[thiscount].value);
					urlstring+='&showfig='+document.upstreamform.showfig[thiscount].value;
				}
			}

			var geoagesel = document.getElementById('geoage');
			var geoagelist='';
			var delim='';
			for (p = 1; p<=geoagesel.length - 1; p++) {
				if (geoagesel.options[p].selected) {
					geoagelist=geoagelist+delim+geoagesel.options[p].value;
					delim=',';
				}
			}
			//alert(geoagelist);
			
			//document.getElementById('debug').innerHTML=myzoom;
			
			urlstring+='&geoages='+geoagelist;

			//wrap up the urlstring
			urlstring+="&";
			
			//document.getElementById('myurl').innerHTML=urlstring;
			foolayer = new OpenLayers.Layer.WMS.Untiled( "Samples",
			urlstring, {layers: 'basic'},
				{ singleTile: true, ratio: 1 } );
			foolayer.addOptions({isBaseLayer: false});
			map.addLayers([foolayer]);
			//return false;

			//also change button if needed
			//alert(agemin+' '+agemax); 0.0 255.0
			if(detritalmethod!='' || detritaltype!='' || detritalmineral!='' || (agemin!='' || agemax!='') || geoagelist!='' ){
				resultsurl='detritalresults?';
				excelurl='detritalexcel.php?';

				
				if(agemin!='' || agemax!=''){
					
					resultsurl+='agemin='+agemin+'&';
					resultsurl+='agemax='+agemax+'&';
					excelurl+='agemin='+agemin+'&';
					excelurl+='agemax='+agemax+'&';

				}

				if(detritaltype!=''){	
					resultsurl+='detritaltype='+detritaltype+'&';
					excelurl+='detritaltype='+detritaltype+'&';
				}

				if(detritalmineral!=''){	
					resultsurl+='detritalmineral='+detritalmineral+'&';
					excelurl+='detritalmineral='+detritalmineral+'&';
				}

				if(detritalmethod!=''){	
					resultsurl+='detritalmethod='+detritalmethod+'&';
					excelurl+='detritalmethod='+detritalmethod+'&';
				}

				if(geoagelist!=''){
					resultsurl+='geoages='+geoagelist+'&';
					excelurl+='geoages='+geoagelist+'&';
				}

				//highlight
				if(document.getElementById("hmysamples").checked == true){
					resultsurl+='&hmysamples=yes&'
					excelurl+='&hmysamples=yes&'
				}

				enveloperesultsurl=resultsurl+'&bounds='+bounds;
				envelopeexcelurl=excelurl+'&bounds='+bounds;
				
				//alert(excelurl);

				document.getElementById("showtable").style.display="";
				document.getElementById("envelopeshowtable").style.display="";

				fetch_count(agemin,agemax,detritaltype,geoagelist);
				envelope_fetch_count(bounds,agemin,agemax,detritaltype,geoagelist);

				
			}else{
				//document.getElementById("showtable").innerHTML="<input type=\"button\" value=\"View Results\" onClick=\"window.open(\"detritalresults\")\">";
				document.getElementById("showtable").style.display="none";
				document.getElementById("envelopeshowtable").style.display="none";
				resultsurl='notset.php';
				document.getElementById("constrainedcount").innerHTML="";
			}

		}



		




    </script>

















































<?php
include("includes/geochron-secondary-footer.htm");
?>