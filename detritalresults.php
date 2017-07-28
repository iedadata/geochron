<?PHP
/**
 * detritalresults.php
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

//build sort url here
$sorturl=$_SERVER['PHP_SELF']."?";
$sortdelim="";
foreach($_GET as $key=>$value){
	if($key!="s" && $key!="page" && $key!="yipp"){
		$sorturl.=$sortdelim.$key."=".$value;
		$sortdelim="&";
	}
}
 


include("db.php");

// **************** get username stuff here for the search ***************
if($_SESSION['username']!=""){
	$username=$_SESSION['username'];
	$userrow=$db->get_row("select * from users where username='$username'");
	$group=$userrow->usergroup;
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

//*************************************************************************





if($_SESSION['userpkey']!=""){
	$userpkey=$_SESSION['userpkey'];
}else{
	$userpkey="99999";
}



include("includes/geochron-secondary-header.htm");
//include("includes/geochron-secondary-header_upstream.htm");
//include("includes/geochron-secondary-header_exxon.htm");



/*
<a href="javascript:popconcordia('viewconcordia.php?sample_pkey=6211');"><img src="concordias/6/6211.gif" border="0"></a>

<a href="concordias/fullsize/6211.jpg" class="MagicZoom" rel="opacity:0; entire-image:true; zoom-fade:true;" title="Concordia Diagram"><img src="concordias/6/6211.gif" width="110" height="80" alt=""/></a>

<a href="concordias/fullsize/6211.jpg" class="MagicZoom" ><img src="concordias/6/6211.gif"/></a>
*/

?>
<style type="text/css">
.paginate {
	border-style: solid;
	border-width: 1px;
	border-color: #999999;
	color: #333333;
	padding: 2px 3px 2px 3px;
	background-color: #FFFFFF; /*f0f4f5;*/
	margin:0px 0px 0px 0px;
	text-decoration:none;
	font-weight:bold;
}
.current {
	border-style: solid;
	border-width: 1px;
	border-color: #999999;
	color: #333333;
	padding: 2px 3px 2px 3px;
	background-color: #99CCFF; /*f0f4f5;*/
	margin:0px 0px 0px 0px;
	text-decoration:none;
	font-weight:bold;
}
.inactive {
	border-style: solid;
	border-width: 1px;
	border-color: #999999;
	color: #999999;
	padding: 2px 3px 2px 3px;
	background-color: #FFFFFF; /*f0f4f5;*/
	margin:0px 0px 0px 0px;
	text-decoration:none;
	font-weight:bold;
}
</style>

<div class="aboutpage">
<a href="search.php">new search</a>
</div>
 
 <?
 /*
 &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href="geochroninteractivemap.php?pkey=<?=$_GET['pkey']?>">view samples on interactive map</a>
 */
 ?>
 
 
 <p>
 

<h1>Results</h1>



<SCRIPT type="text/javascript" src="js/prototype.js"></SCRIPT>
<SCRIPT type="text/javascript" src="/magiczoom/magiczoom.js"></SCRIPT>
<link type="text/css" rel="stylesheet" media="all" href="/magiczoom/magiczoom.css" />

<script language="JavaScript" type="text/JavaScript">
<!--



function showfracs(id) {
	var thsObj = document.getElementById('row'+id);
	var imgObj = document.getElementById('img'+id);
	
	if(thsObj.style.display == 'table-row') {
		thsObj.style.display = 'none';
		imgObj.src = 'images/rightarrow.gif';
	}else{
		//do AJAX call here to get fraction information

		var url = 'getfractions.php';

		var pars = pars + '&pkey='+id;
		
		//alert('http://dev2.geochron.org/' + url + pars);
		
		var myAjax = new Ajax.Request(url, {
			method: 'get',
			parameters: pars,
			onSuccess: function(transport) {
				//alert(transport.responseText);
				document.getElementById('fracdiv'+id).innerHTML=transport.responseText;
			},
			onFailure: function(t) {
				alert('Error ' + t.status + ' -- ' + t.statusText);
			},
		});

		thsObj.style.display = 'table-row';
		imgObj.src = 'images/downarrow.gif';
	}
}

//-->
</script>

































































<?

$agemin=$_GET['agemin']; //*1000000;
$agemax=$_GET['agemax']; //*1000000;
$geoages=$_GET['geoages'];
$detritaltype=$_GET['detritaltype'];
$detritalmineral=$_GET['detritalmineral'];
$detritalmethod=$_GET['detritalmethod'];

$ipp=$_GET['ipp'];
$page=$_GET['page'];
$sort=urldecode($_GET['s']);
$oldsort=$_GET['os'];

if($sort=="unique_id"){
	$sort="igsn";
}

if($sort=="unique_id desc"){
	$sort="igsn desc";
}



if($ipp==""){
	$ipp=8;
}

if($page==""){
	$page=1;
}

if($sort==""){
	$sort="sample_pkey desc";
}

if($oldsort==""){
	$oldsort=="sample_pkey";
}

$s=urlencode($sort);
$os=urlencode($oldsort);

//echo "$s";
//exit();

$bounds=$_GET['bounds'];

if($bounds!=""){
	$parts=explode(",",$bounds);
	
	$lon1=$parts[0];
	$lat1=$parts[1];
	$lon2=$parts[2];
	$lat2=$parts[3];
	
	$boundstring="$lon1 $lat1, $lon1 $lat2, $lon2 $lat2, $lon2 $lat1, $lon1 $lat1";
	$boundstring="and ST_Contains(ST_GeomFromText('Polygon(($boundstring))'),mypoint)";
}

//build geoagearray here if needed
if($geoages!=""){
	$geoages=explode(",",$geoages);
	$geonum=0;
	foreach($geoages as $geoage){
		$georow=$db->get_row("select * from geoages where pkey=$geoage");
		$thisminage=$georow->minage; //*1000000;
		$thismaxage=$georow->maxage; //*1000000;
		$geoagearray[$geonum][minage]=$thisminage;
		$geoagearray[$geonum][maxage]=$thismaxage;		
		$geonum++;
	}
}




$agesdelim="";

if($agemin!="" || $agemax !=""){
	$resultstring.=" and (";
	
	if($agemin!=""){
		$resultstring.=$agesdelim."(age_min >= $agemin or age_max >= $agemin) ";
		$agesdelim=" and ";
	}

	if($agemax!=""){
		$resultstring.=$agesdelim."(age_min <= $agemax or age_max <= $agemax) ";
	}
	
	$resultstring.=")";
}


if($detritaltype!=""){
	$resultstring.="and detrital_type='$detritaltype' ";
}

if($detritalmineral!=""){
	$resultstring.="and material='$detritalmineral' ";
}

if($detritalmethod!=""){
	$resultstring.="and ecproject='$detritalmethod' ";
}


	if(count($geoagearray)>0){
	
		$resultstring.=" and (";
	
		$geoages=explode(",",$geoages);
		$geodelim="";
		foreach($geoagearray as $geoage){
			$thisminage=$geoage[minage];
			$thismaxage=$geoage[maxage];

			$resultstring.=$geodelim."(age_min >= $thisminage or age_max >= $thisminage) and (age_min <= $thismaxage or age_max <= $thismaxage) ";
			$geodelim=" or ";
		}
		
		$resultstring.=") ";
	}
	
	$resultstring.=" and upstream=true";

//echo $resultstring; exit();

$countstring="select count(*) from (
						select sample.sample_pkey
							from sample
							left join sample_age on sample.sample_pkey = sample_age.sample_pkey
							left join groupsamplerelate on sample.sample_pkey = groupsamplerelate.sample_pkey
							left join grouprelate on groupsamplerelate.group_pkey = grouprelate.group_pkey
							where (sample.publ=1 or sample.userpkey=$userpkey or (grouprelate.users_pkey=$userpkey and grouprelate.confirmed=true))
						$boundstring ".$resultstring." group by sample.sample_pkey) foo";

$resultstring="select sample.sample_pkey, 
							sample.sample_id,
							sample.igsn,
							sample.laboratoryname, 
							sample.analyst_name,
							sample.ecproject,
							sample.latitude,
							sample.longitude,
							sample.userpkey,
							sample.material,
							detrital_type,
							oldest_frac_date,
							youngest_frac_date,
							age_min, age_max, age_value, one_sigma, age_name,
							getagetypes(sample.sample_pkey) as agetypes
							from sample
							left join sample_age on sample.sample_pkey = sample_age.sample_pkey
							left join groupsamplerelate on sample.sample_pkey = groupsamplerelate.sample_pkey
							left join grouprelate on groupsamplerelate.group_pkey = grouprelate.group_pkey
							where (sample.publ=1 or sample.userpkey=$userpkey or (grouprelate.users_pkey=$userpkey and grouprelate.confirmed=true))
							$boundstring
							$resultstring
							group by
							sample.sample_pkey, 
							sample.sample_id,
							sample.igsn,
							sample.laboratoryname, 
							sample.analyst_name,
							sample.ecproject,
							sample.latitude,
							sample.longitude,
							sample.userpkey,
							sample.material,
							detrital_type,
							oldest_frac_date,
							youngest_frac_date,
							age_min, age_max, age_value, one_sigma, age_name,
							agetypes";



$samplecount=$db->get_var("$countstring");




//do paginator here
include("paginator.php");

$pages = new Paginator;
$pages->items_total = $samplecount;  
$pages->mid_range = 9;  
$pages->paginate();  


echo "Page $pages->current_page of $pages->num_pages";


$offset=($page-1)*$ipp;

//$rows=$db->get_results("select * from pagetest order by pkey limit $numtoshow offset $offset");

$resultstring.=" order by $sort limit $ipp offset $offset";

//echo nl2br($resultstring);

$rows=$db->get_results($resultstring);


//put back sort
if($sort=="igsn"){
	$sort="unique_id";
}

if($sort=="igsn desc"){
	$sort="unique_id desc";
}








if(count($rows)>0){










	//build javascript array here for all pkeys
	//var myCars=new Array("Saab","Volvo","BMW");
	$jsstring="var mypkeys = new Array(";
	$jsstringdelim="";
	foreach($rows as $row){
		$jsstring.=$jsstringdelim."\"$row->sample_pkey\"";
		$jsstringdelim=",";
	}
	
	$jsstring.=");\n";









?>
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
				newwindow=window.open(url,'name','height=420,width=600,scrollbars=1');
				if (window.focus) {newwindow.focus()}
			}
		-->
	</script>











<script type="text/javascript">

function expandall()
{
	var url = 'getfractions.php';
	
	<?=$jsstring?>
	//mypkeys
	//for (var mycount = 0; mycount < mypkeys.length; mycount++) {
	for (var mycount = 0; mycount < mypkeys.length; mycount++) {
		//alert(mypkeys[mycount]);
		//Do something
		var thsObj = document.getElementById('row'+mypkeys[mycount]);
		var imgObj = document.getElementById('img'+mypkeys[mycount]);

		var thsfracdiv = document.getElementById('fracdiv'+mypkeys[mycount]);

		var pars = '&pkey='+mypkeys[mycount];
		
		new Ajax.Request(url, {
			method: 'get',
			asynchronous: false,
			parameters: pars,
			onSuccess: function(transport) {
				//alert(transport.responseText);
				//document.getElementById('fracdiv'+mypkeys[mycount]).innerHTML=transport.responseText;
				thsfracdiv.innerHTML=transport.responseText;
				//alert(mypkeys[mycount]+transport.responseText);
			},
			onFailure: function(t) {
				alert('Error ' + t.status + ' -- ' + t.statusText);
			},
		});	
		
		thsObj.style.display = 'table-row';
		imgObj.src = 'images/downarrow.gif';

	}

}
</script>

<script type="text/javascript">

function contractall()
{
	
	<?=$jsstring?>
	//mypkeys
	//for (var mycount = 0; mycount < mypkeys.length; mycount++) {
	for (var mycount = 0; mycount < mypkeys.length; mycount++) {
		//alert(mypkeys[mycount]);
		//Do something
		var thsObj = document.getElementById('row'+mypkeys[mycount]);
		var imgObj = document.getElementById('img'+mypkeys[mycount]);

		var thsfracdiv = document.getElementById('fracdiv'+mypkeys[mycount]);
		
		thsfracdiv.innerHTML='';
		
		thsObj.style.display = 'none';
		imgObj.src = 'images/rightarrow.gif';

	}

}
</script>











<div align="center">
<table align="center" class="aliquot" width="750px";>
	<tr>
		<th style="background-color:#FFFFFF;border-width: 0px 1px 1px 0px;" colspan="5"></th>
		<th style="background-color:#99CCFF;" colspan="6">
			<div align="center">HOST ROCK</div>
		</th>
		<th style="background-color:#FF99CC;" colspan="2">
			<div align="center">FRACTION</div>
		</th>
		<th style="background-color:#FFFFFF;border-width: 0px 0px 1px 1px;"></th>
	</tr>
	<tr>

		<th colspan="3" nowrap>
			<INPUT TYPE="button" value="Expand All" onClick="expandall();"> <INPUT TYPE="button" value="Contract All" onClick="contractall();">
		</th>

		<? if($sort=="sample_id"){ $sortstring="sample_id+desc"; $sortchar = "down";}else{ $sortstring="sample_id"; $sortchar = "up";} ?>

		<th nowrap><a class="sortlink" href="<?=$sorturl?>&s=<?=$sortstring?>">Sample ID</a></th>
		
		<? if($sort=="unique_id"){ $sortstring="unique_id+desc"; $sortchar = "down";}else{ $sortstring="unique_id"; $sortchar = "up";} ?>
		<th nowrap><a class="sortlink" href="<?=$sorturl?>&s=<?=$sortstring?>">Unique&nbsp;ID</a></th>
		
		<? if($sort=="age_min"){ $sortstring="age_min+desc"; $sortchar = "down";}else{ $sortstring="age_min"; $sortchar = "up";} ?>
		<th ><a class="sortlink" href="<?=$sorturl?>&s=<?=$sortstring?>">Min Age</a></th>
		
		<? if($sort=="age_max"){ $sortstring="age_max+desc"; $sortchar = "down";}else{ $sortstring="age_max"; $sortchar = "up";} ?>
		<th ><a class="sortlink" href="<?=$sorturl?>&s=<?=$sortstring?>">Max Age</a></th>
		
		<? if($sort=="ecproject"){ $sortstring="ecproject+desc"; $sortchar = "down";}else{ $sortstring="ecproject"; $sortchar = "up";} ?>
		<th ><a class="sortlink" href="<?=$sorturl?>&s=<?=$sortstring?>">Detrital<br>Method</a></th>
		
		<? if($sort=="detrital_type"){ $sortstring="detrital_type+desc"; $sortchar = "down";}else{ $sortstring="detrital_type"; $sortchar = "up";} ?>
		<th ><a class="sortlink" href="<?=$sorturl?>&s=<?=$sortstring?>">Detrital<br>Type</a></th>
		
		<? if($sort=="material"){ $sortstring="material+desc"; $sortchar = "down";}else{ $sortstring="material"; $sortchar = "up";} ?>
		<th ><a class="sortlink" href="<?=$sorturl?>&s=<?=$sortstring?>">Detrital<br>Mineral</a></th>
		
		<? if($sort=="strat_name"){ $sortstring="strat_name+desc"; $sortchar = "down";}else{ $sortstring="strat_name"; $sortchar = "up";} ?>
		<th ><a class="sortlink" href="<?=$sorturl?>&s=<?=$sortstring?>">Stratigraphic<br>Formation Name</a></th>
		
		<? if($sort=="oldest_frac_date"){ $sortstring="oldest_frac_date+desc"; $sortchar = "down";}else{ $sortstring="oldest_frac_date"; $sortchar = "up";} ?>
		<th ><a class="sortlink" href="<?=$sorturl?>&s=<?=$sortstring?>">Oldest Date</a></th>
		
		<? if($sort=="youngest_frac_date"){ $sortstring="youngest_frac_date+desc"; $sortchar = "down";}else{ $sortstring="youngest_frac_date"; $sortchar = "up";} ?>
		<th ><a class="sortlink" href="<?=$sorturl?>&s=<?=$sortstring?>">Youngest Date</a></th>

		<th >Concordia</th>
		<th >Probability Density</th>
	</tr>
<?

$rownum="1";

foreach($rows as $row){

if($row->ecproject=="redux"){
	$detritalmethod="U-Pb";
}elseif($row->ecproject=="helios"){
	$detritalmethod="(U-Th)/He";
}elseif($row->ecproject=="arar"){
	$detritalmethod="Ar-Ar";
}

if($row->strat_name!=""){
	$showstratname=$row->strat_name;
}else{
	$showstratname="n/a";
}

$pkey=$row->sample_pkey;

?>
	<tr>
		<td nowrap valign="bottom"><a onClick="showfracs('<?=$pkey?>');"><img id="img<?=$pkey?>" src="images/rightarrow.gif"></a></td>

		<td class="aboutpage" colspan="2" valign="top" nowrap>
			<a href="upstreamviewfile.php?pkey=<?=$row->sample_pkey?>" target="_blank">view file</a>&nbsp;&nbsp;
			<a href="downloadfile.php?pkey=<?=$row->sample_pkey?>">download</a>
		</td>

		<td nowrap valign="top"><?=$row->sample_id?></td>
		<!--
		<td valign="top"><a style="color:#3333CC;font-size:7pt;font-weight:bold; " href="javascript:popwindow('viewid.php?id=<?=$row->igsn?>');"><?=$row->igsn?></a></td>
		<td valign="top" nowrap><a style="color:#3333CC;font-size:7pt;font-weight:bold; " href="javascript:popwindow('upstreamviewfile.php?pkey=<?=$row->sample_pkey?>');"><img src="magglass.png" border="0">DETAILS</a></td>
		-->
		<td class="aboutpage" valign="top"><a href="javascript:popwindow('viewid.php?id=<?=$row->igsn?>');"><?=$row->igsn?></a></td>
		
		<td valign="top" style="background-color:#eef5fc;" nowrap><?=round($row->age_min,0)?> Ma</td>
		<td valign="top" style="background-color:#eef5fc;" nowrap><?=round($row->age_max,0)?> Ma</td>
		<td valign="top" style="background-color:#eef5fc;" ><?=$detritalmethod?></td>
		<td valign="top" style="background-color:#eef5fc;" ><?=$row->detrital_type?></td>
		<td valign="top" style="background-color:#eef5fc;" ><?=$row->material?></td>
		<td valign="top" style="background-color:#eef5fc;" ><?=$showstratname?></td>
		<td valign="top" style="background-color:#fcf4f9;" nowrap><?=round($row->oldest_frac_date/1000000,0)?> Ma</td>
		<td valign="top" style="background-color:#fcf4f9;" nowrap><?=round($row->youngest_frac_date/1000000,0)?> Ma</td>
		<td width="25px" valign="top">
			<?
			if(file_exists("concordias/6/$row->sample_pkey.gif")){
			?>
			<a href="concordias/fullsize/<?=$row->sample_pkey?>.jpg" class="MagicZoom" rel="preload-selectors-big:false; hint-text:;hint-opacity:50;opacity:50; entire-image:true; zoom-fade:false;" title="Concordia Diagram"><img src="concordias/6/<?=$row->sample_pkey?>.gif" width="110" height="80" alt=""/></a>
			<?
			}else{
			?>
			Not Available
			<?
			}
			?>
		</td>
		<td width="25px" valign="top">
			<?
			if(file_exists("probabilities/6/$row->sample_pkey.gif")){
			?>
			<a href="probabilities/fullsize/<?=$row->sample_pkey?>.jpg" class="MagicZoom" rel="preload-selectors-big:false; hint-text:;hint-opacity:50;opacity:50; entire-image:true; zoom-fade:false;" title="Probability Density"><img src="probabilities/6/<?=$row->sample_pkey?>.gif" width="110" height="80" alt=""/></a>
			<?
			}else{
			?>
			Not Available
			<?
			}
			?>
		</td>
	</tr>
	<tr id="row<?=$pkey?>" style="display:none;">
		<td style="background-color:#eef5fc;">
			&nbsp;
		</td>
		<td colspan="14">
			<div id ="fracdiv<?=$pkey?>" style="padding:10px 10px 10px 30px;">














			</div>
		</td>
	</tr>
	
<?
$rownum++;
}//end foreach row
?>
	
</table>
</div>

<?
	if($samplecount > $ipp){
		//pagination here
		echo "<br>".$pages->display_pages()."<span style=\"margin-left:25px\"> ".$pages->display_jump_menu()."&nbsp;&nbsp;".$pages->display_items_per_page() . "</span>";
	}
}else{ //end if rows > 0

?>

No Results Found.<br><br><br><br><br><br><br>

<?


} //end if rows > 0


include("includes/geochron-secondary-footer.htm");
//include("includes/geochron-secondary-footer_upstream.htm");

/*
$countstring="select count(*)
						from sample 
						left join sample_age on sample.sample_pkey=sample_age.sample_pkey
						left join users on sample.userpkey = users.users_pkey
						where (sample.publ=1 or users.usergroup=$group or users.users_pkey=$userpkey)
						$boundstring ".$resultstring;

$resultstring="select sample.sample_pkey,
						sample_id,
						igsn,
						longitude,
						latitude, 
						age_min,
						age_max,
						detrital_type,
						strat_name,
						oldest_frac_date,
						youngest_frac_date,
						material,
						ecproject
						from sample 
						left join sample_age on sample.sample_pkey=sample_age.sample_pkey
						left join users on sample.userpkey = users.users_pkey
						where (sample.publ=1 or users.usergroup=$group or users.users_pkey=$userpkey)
						$boundstring ".$resultstring;
*/

?>