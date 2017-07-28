<?PHP
/**
 * geochronsearch.php
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

$grouparray=str_replace("{","",$grouparray);
$grouparray=str_replace("}","",$grouparray);

if($group==0 or $group==""){
	$group=99999;
}

if($grouparray==""){
	$grouparray=99999;
}

if($userpkey==""){
	$userpkey=99999;
}

//*************************************************************************


//echo "grouparray: $grouparray<br>";


if($_SESSION['userpkey']!=""){
	$userpkey=$_SESSION['userpkey'];
}else{
	$userpkey="99999";
}


include("includes/geochron-secondary-header.htm");


$rocktypes=$db->get_results("select distinct(rocktype) from 
							sample 
							left join users on sample.userpkey = users.users_pkey
							where rocktype is not null 
							and rocktype != ''
							--and (sample.publ=1 or users.usergroup=$group or users.users_pkey=$userpkey)
							and (sample.publ=1 or array_intersect(users.grouparray, ARRAY[$grouparray]) is not null or users.users_pkey=$userpkey)
							--and sample.upstream=FALSE
							order by rocktype");

$labnames=$db->get_results("select distinct(laboratoryname) from 
							sample 
							left join users on sample.userpkey = users.users_pkey
							where laboratoryname is not null 
							and laboratoryname != ''
							--and (sample.publ=1 or users.usergroup=$group or users.users_pkey=$userpkey)
							and (sample.publ=1 or array_intersect(users.grouparray, ARRAY[$grouparray]) is not null or users.users_pkey=$userpkey)
							--and sample.upstream=FALSE
							order by laboratoryname");
/*
$agenames=$db->get_results("select distinct(age_name) from 
							sample_age
							left join sample on sample_age.sample_pkey = sample.sample_pkey
							left join users on sample.userpkey = users.users_pkey
							where age_name is not null
							and age_name != ''
							and (sample.publ=1 or users.usergroup=$group or users.users_pkey=$userpkey)
							--and sample.upstream=FALSE
							order by age_name");
*/

$agenames=$db->get_results("select distinct(purpose) 
							from sample 
							left join users on sample.userpkey = users.users_pkey
							where 
							purpose!='NONE' 
							and purpose!='' 
							--and (sample.publ=1 or users.usergroup=$group or users.users_pkey=$userpkey)
							and (sample.publ=1 or array_intersect(users.grouparray, ARRAY[$grouparray]) is not null or users.users_pkey=$userpkey)
							order by purpose");



$materials=$db->get_results("select distinct(material) from 
							sample 
							left join users on sample.userpkey = users.users_pkey
							where rocktype is not null 
							and rocktype != ''
							--and (sample.publ=1 or users.usergroup=$group or users.users_pkey=$userpkey)
							and (sample.publ=1 or array_intersect(users.grouparray, ARRAY[$grouparray]) is not null or users.users_pkey=$userpkey)
							--and sample.upstream=FALSE
							order by material");

// $group and $userpkey are used in buildquery.php to get samples that belong
// to the user or the user group.

?>

<SCRIPT type="text/javascript" src="js/prototype.js"></SCRIPT>
<SCRIPT type="text/javascript" src="js/searchpage.js"></SCRIPT>
<!--<SCRIPT type="text/javascript" src="js/sarissa.js"></SCRIPT>-->

<link rel="stylesheet" type="text/css" href="styles/shadowbox.css">
<script type="text/javascript" src="js/shadowbox.js"></script>
<script type="text/javascript">
Shadowbox.init();
</script>

<script type="text/javascript" src="js/jquery.js"></script>
   <script type="text/javascript">
     var $j = jQuery.noConflict();
     
     // Use jQuery via $j(...)
     //$j(document).ready(function(){
     //  $j("div").hide();
     //});
     
     // Use Prototype with $(...), etc.
     //$('someid').hide();
   </script>


<script language="JavaScript" type="text/JavaScript">
function showdebug(){
	document.getElementById('debug').style.display='block';
}

</script>

<h1>Set Search Criteria by Category</h1>
<?

if($_GET['pkey']=="" && $_POST['pkey']==""){
	$pkey=$db->get_var("select nextval('search_query_seq')");
	$db->query("insert into search_query (search_query_pkey) values ($pkey)");
}

if($_GET['pkey']!=""){
	$pkey=$_GET['pkey'];
}

if($_POST['pkey']!=""){
	$pkey=$_POST['pkey'];
}

$runquery="no";

//echo "pkey: $pkey<br><br>";

//http://picasso.kgs.ku.edu/custompoints/geochronsearchpagepoly.php?pkey=555

//<CFINCLUDE template="buildquery.cfm">
//include("buildquery.php");

?>









<table class="aboutpage">
<tr>
<td>
	<table class="aboutpage" border=0 cellspacing=10 cellpadding=10><!--- leave enough cellpadding so that IE does not obliterate part of the CSS "button" --->
	
		<tr style="vertical-align:middle">
			<td style="padding:10px;vertical-align:top;font-weight:bold;">Location:</td>
			<td style="padding:10px;vertical-align:middle;border:1px;border-style:solid;line-height:130%">
				<table class="aboutpage">
					<tr>
						<td>
							<table class="aboutpage">
								<tr>
									<td>
										&nbsp;
									</td>
									<td>
										North:<br>
										<input type="text" name="north" id="north" size="4">
									</td>
									<td>
										&nbsp;
									</td>
								</tr>
								<tr>
									<td>
										West:<br>
										<input style="font-size:.8em;" type="text" name="west" id="west" size="4">
									</td>
									<td>
										&nbsp;
									</td>
									<td>
										East:<br>
										<input style="font-size:.8em;" type="text" name="east" id="east" size="4">
									</td>
								</tr>
								<tr>
									<td>
										&nbsp;
									</td>
									<td>
										South:<br>
										<input style="font-size:.8em;" type="text" name="south" id="south" size="4">
									</td>
									<td>
										&nbsp;
									</td>
								</tr>
								<tr>
									<td colspan="3">
										<div align="center">
											(decimal degrees)
										</div>
									</td>
								</tr>
								<tr>
									<td colspan="3">
										<div align="left">
											<span><input type="button" value="Update" onClick="javascript:dosearch()"></span>
											<span id="clearnewsbutton" style="visibility:hidden;"><input type="button" value="Clear" onClick="clearnews();"></span>
										</div>
									</td>
								</tr>

							</table>
						</td>
						<td nowrap>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- or -&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
						<td>
							<div id="mapinfo">
							<a onclick="showmap();" ><img src="images/mappersmall.jpg"></a><br>
							Use Geochron Dynamic Mapper
							</div>
							<input type="hidden" id="coordinates">
						</td>
					</tr>
				</table>
			</td>
		</tr>




		<tr style="vertical-align:middle">
			<td style="padding:10px;vertical-align:top;font-weight:bold;">Age:</td>
			<td style="padding:10px;vertical-align:middle;border:1px;border-style:solid;line-height:130%">
				<table class="aboutpage">
					<tr>
						<td>
							Min Age: <input style="font-size:.8em;" type="text" name="minage" id="minage" size="4">
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							Max Age: <input style="font-size:.8em;" type="text" name="maxage" id="maxage" size="4">
							<div align="center" style="padding-top:5px;padding-bottom:5px;">- or -</div>
							Age: <input style="font-size:.8em;" type="text" name="age" id="age" size="4">
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;+/-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<input style="font-size:.8em;" type="text" name="ageplusminus" id="ageplusminus" size="4">
						</td>
						<td>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						</td>
						<td>
							<input name="ageunit" type="radio" value="ma" checked> Ma <br>
							<input name="ageunit" type="radio" value="ka"> Ka
						</td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td>
							<span><input type="button" value="Update" onClick="javascript:dosearch()"></span>
							<span id="clearagebutton" style="visibility:hidden;"><input type="button" value="Clear" onClick="clearage();"></span>
						</td>
					</tr>
				</table>
			</td>
		</tr>



		<tr style="vertical-align:middle">
			<td style="padding:10px;vertical-align:top;font-weight:bold;">Analysis Method:</td>
			<td style="padding:10px;vertical-align:middle;border:1px;border-style:solid;line-height:130%">
				<table class="aboutpage" width="100%">

					<tr>
						<td>
							<input type="checkbox" id="upbtimscheck" onChange="doupbtimscheck();"> U-Pb TIMS
						</td>
						<td>
							<input type="checkbox" id="uthhecheck" onChange="douthhecheck();"> (U-Th)/He
						</td>
						<td>&nbsp;</td>
					</tr>

					<tr>
						<td>
							<input type="checkbox" id="upbioncheck" onChange="doupbioncheck();"> U-Pb Ion Microprobe
						</td>
						<td><input type="checkbox" id="ararcheck" onChange="doararcheck();"> ArAr</td>


					</tr>

					<tr>
						<td>
							<input type="checkbox" id="ftcheck" onChange="doftcheck();"> Fission Track
						</td>
						<td>&nbsp;</td>
						<td>
							<span id="clearmethodsbutton" style="visibility:hidden;"><input type="button" value="Clear" onClick="clearmethods();"></span>
						</td>

					</tr>



					<tr>
						<td colspan="3">
							<br>
							<div id="agemethoddisplay" style="border:1px solid;padding:3px;">
							No methods set.
							</div>
						</td>
						<input type="hidden" name="agemethod" id="agemethod" value="">
					</tr>


<!--
					<tr>
						<td>
							<input type="checkbox" id="upbcheck" onChange="doupbcheck();"> U/Pb
						</td>
						<td>
							<input type="checkbox" id="uthhecheck" onChange="douthhecheck();"> (U-Th)/He
						</td>
					</tr>
					<tr>
						<td>
							<input type="checkbox" id="tcncheck" onChange="dotcncheck();"> TCN
						</td>
						<td>
							<input type="checkbox" id="useriescheck" onChange="douseriescheck();"> U-Series
						</td>
					</tr>
					<tr>
						<td>
							<input type="checkbox" id="ionmicroprobecheck" onChange="doionmicroprobecheck();"> Ion Microprobe
						</td>
						<td>
							<input type="checkbox" id="laicpmscheck" onChange="dolaicpmscheck();"> LAICP-MS
						</td>
					</tr>
-->
				</table>
				<input type="hidden" id="upbmethods">
				<input type="hidden" id="uthhemethods">
				<input type="hidden" id="tcnmethods">
				<input type="hidden" id="useriesmethods">
				<input type="hidden" id="ionmicroprobemethods">
				<input type="hidden" id="laicpmsmethods">
			</td>
		</tr>



		<tr style="vertical-align:middle">
			<td style="padding:10px;vertical-align:top;font-weight:bold;">Material Analyzed:</td>
			<td style="padding:10px;vertical-align:middle;border:1px;border-style:solid;line-height:130%">
				<select name="materials[]" size="5" style="width:200px;" id="materials" multiple>
					<option value="" selected>No Selection
					<?
					foreach($materials as $material){
					?>
					<option value="<?=$material->material?>"><?=$material->material?>
					<?
					}
					?>
				</select>
				
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<span><input type="button" value="Update" onClick="javascript:dosearch()"></span>
				<span id="clearmaterialsbutton" style="visibility:visible;"><input type="button" value="Clear" onClick="clearmaterials();"></span>
			</td>
		</tr>



		<tr style="vertical-align:middle">
			<td style="padding:10px;vertical-align:top;font-weight:bold;">Rock Type:</td>
			<td style="padding:10px;vertical-align:middle;border:1px;border-style:solid;line-height:130%">
				<select name="rocktype[]" size="5" style="width:200px;" id="rocktype" multiple>
					<option value="" selected>No Selection
					<?
					foreach($rocktypes as $rocktype){
					?>
					<option value="<?=$rocktype->rocktype?>"><?=$rocktype->rocktype?>
					<?
					}
					?>
				</select>
				
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<span><input type="button" value="Update" onClick="javascript:dosearch()"></span>
				<span id="clearrocktypebutton" style="visibility:hidden;"><input type="button" value="Clear" onClick="clearrocktype();"></span>
			</td>
		</tr>







		<tr style="vertical-align:middle">
			<td style="padding:10px;vertical-align:top;font-weight:bold;">Laboratory:</td>
			<td style="padding:10px;vertical-align:middle;border:1px;border-style:solid;line-height:130%">
				<select name="labnames[]" size="5" style="width:200px;" id="labnames" multiple>
					<option value="" selected>No Selection
					<?
					foreach($labnames as $labname){
					?>
					<option value="<?=$labname->laboratoryname?>"><?=$labname->laboratoryname?>
					<?
					/*
					<option value="<?=$labname->laboratoryname?>"><?=$labname->laboratoryname?>
					*/
					}
					?>
				</select>

				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<span><input type="button" value="Update" onClick="javascript:dosearch()"></span>
				<span id="clearlabnamesbutton" style="visibility:hidden;"><input type="button" value="Clear" onClick="clearlabnames();"></span>
			</td>
		</tr>



		<tr style="vertical-align:middle">
			<td style="padding:10px;vertical-align:top;font-weight:bold;">Purpose/Method:</td>
			<td style="padding:10px;vertical-align:middle;border:1px;border-style:solid;line-height:130%">
				<select name="purposes[]" size="5" style="width:200px;" id="purposes" multiple>
					<option value="" selected>No Selection
					<?
					foreach($agenames as $agename){
					?>
					<option value="<?=$agename->purpose?>"><?=$agename->purpose?>
					<?
					}
					?>
				</select>
				
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<span><input type="button" value="Update" onClick="javascript:dosearch()"></span>
				<span id="clearagenamesbutton" style="visibility:hidden;"><input type="button" value="Clear" onClick="clearagenames();"></span>
			</td>
		</tr>


		<tr style="vertical-align:middle">
			<td style="padding:10px;vertical-align:top;font-weight:bold;">Sample Info:</td>
			<td style="padding:10px;vertical-align:middle;border:1px;border-style:solid;line-height:130%">
				<table class="aboutpage">
					<tr><td>IGSN</td><td><input style="font-size:.8em;" type="text" id="igsn" name="igsn" value="" ></td></tr>
					<tr><td>Sample Name/Number</td><td><input style="font-size:.8em;" type="text" id="sample_id" name="sample_id" value="" ></td></tr>
					<tr><td>Collector</td><td><input style="font-size:.8em;" type="text" id="collector" name="collector" value="" ></td></tr>
					<tr><td>Sample Description</td><td><input style="font-size:.8em;" type="text" id="sampledescription" name="sampledescription" value="" ></td></tr>
					<tr><td>Collection Method</td><td><input style="font-size:.8em;" type="text" id="collectionmethod" name="collectionmethod" value="" ></td></tr>
					<tr><td>Sample Comment</td><td><input style="font-size:.8em;" type="text" id="samplecomment" name="samplecomment" value="" ></td></tr>
					<tr><td>Primary Location Name</td><td><input style="font-size:.8em;" type="text" id="primarylocationname" name="primarylocationname" value="" ></td></tr>
					<tr><td>Primary Location Type</td><td><input style="font-size:.8em;" type="text" id="primarylocationtype" name="primarylocationtype" value="" ></td></tr>
					<tr><td>Location Description</td><td><input style="font-size:.8em;" type="text" id="locationdescription" name="locationdescription" value="" ></td></tr>
					<tr><td>Locality</td><td><input style="font-size:.8em;" type="text" id="locality" name="locality" value="" ></td></tr>
					<tr><td>Locality Description</td><td><input style="font-size:.8em;" type="text" id="localitydescription" name="locality" value="" ></td></tr>
					<tr><td>Country</td><td><input style="font-size:.8em;" type="text" id="country" name="country" value="" ></td></tr>
					<tr><td>Province</td><td><input style="font-size:.8em;" type="text" id="province" name="province" value="" ></td></tr>
					<!--
					<tr><td>County</td><td><input type="text" name="county" value="" ></td></tr>
					<tr><td>City or Township</td><td><input type="text" name="cityortownship" value="" ></td></tr>
					<tr><td>Platform</td><td><input type="text" name="platform" value="" ></td></tr>
					<tr><td>Platform ID</td><td><input type="text" name="platformid" value="" ></td></tr>
					<tr><td>Original Archival Institution</td><td><input type="text" name="originalarchivalinstitution" value="" ></td></tr>
					<tr><td>Original Archival Contact</td><td><input type="text" name="originalarchivalcontact" value="" ></td></tr>
					<tr><td>Most Recent Archival Institution</td><td><input type="text" name="mostrecentarchivalinstitution" value="" ></td></tr>
					<tr><td>Most Recent Archival Contact</td><td><input type="text" name="mostrecentarchivalcontact" value="" ></td></tr>
					-->
					<tr><td></td><td>
					<br>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

					<span><input type="button" value="Update" onClick="javascript:dosearch()"></span>
					<span id="clearsampleinfobutton" style="visibility:hidden;"><input type="button" value="Clear" onClick="clearsampleinfo();"></span>
					</td></tr>
				</table>
			</td>
		</tr>













	</table>
</td>
<td valign="top">
	<div id="results">
		No Search Set.
	</div>
</td>
</tr>
</table>

<!--
<input type="button" onclick="$j('html, body').animate({ scrollTop: 0 }, 'slow');">
-->

<input type="hidden" id="pkey" value="<?=$pkey?>">
<input type="hidden" id="userpkey" value="<?=$userpkey?>">

  <DIV id="debug" style="display: none">
  <?
  echo "posts:*******************************<br>";
if(count($_POST)>0){
	foreach($_POST as $key=>$value){
		echo "$key : $value<br>";
	}
}
echo "*************************************<br>";
  ?>
  <br />
    <br />
    <?=print_r($_POST)?>
    <br><br><br>
    <?=nl2br($newquerystring)?>
    <br><br><br>
    <? print_r($_SESSION); ?>
  </DIV>

<?
include("includes/geochron-secondary-footer.htm");
?>