<?PHP
/**
 * envelopeupstreamcount.php
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

if (!extension_loaded("MapScript"))
  dl('php_mapscript.'.PHP_SHLIB_SUFFIX);

session_start();

//print_r($_SESSION);

include("db.php");

// **************** get username stuff here for the search ***************
if($_SESSION['username']!=""){
	$username=$_SESSION['username'];
	//$userrow=$db->get_row("select * from users where username='$username'");
	$userrow=$db->get_row("select * from users where email='$username'");
	$grouparray=$userrow->grouparray;
	$group=$userrow->usergroup;
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





if($_SESSION['userpkey']!=""){
	$userpkey=$_SESSION['userpkey'];
}else{
	$userpkey="99999";
}


$agemin=$_GET['agemin']; //*1000000;
$agemax=$_GET['agemax']; //*1000000;
$geoages=$_GET['geoages'];
$detritaltype=$_GET['detritaltype'];
$detritalmineral=$_GET['detritalmineral'];
$detritalmethod=$_GET['detritalmethod'];

$bounds=$_GET['bounds'];

//echo $bounds; exit();

$parts=explode(",",$bounds);

$lon1=$parts[0];
$lat1=$parts[1];
$lon2=$parts[2];
$lat2=$parts[3];

$boundstring="$lon1 $lat1, $lon1 $lat2, $lon2 $lat2, $lon2 $lat1, $lon1 $lat1";
$boundstring="and ST_Contains(ST_GeomFromText('Polygon(($boundstring))'),mypoint)";

//echo $boundstring;
//exit();

//and ST_Contains(GeomFromText('Polygon((-117.675 31.621870994569,-115.7625 33.759370994569,-115.2 30.159370994569,-117.675 31.621870994569))'),crd.mypoint) AND 
//and ST_Contains(GeomFromText('Polygon(())'),crd.mypoint) AND 

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


	$resultstring="select count(*) 
						from sample 
						left join sample_age on sample.sample_pkey=sample_age.sample_pkey
						left join users on sample.userpkey = users.users_pkey
						--where (sample.publ=1 or users.usergroup=$group or users.users_pkey=$userpkey) 
						where (sample.publ=1 or array_intersect(users.grouparray, ARRAY[$grouparray]) is not null or users.users_pkey=$userpkey)
						$boundstring ";
						
	$resultstring="select count(*) from (
						select sample.sample_pkey
							from sample
							left join sample_age on sample.sample_pkey = sample_age.sample_pkey
							left join groupsamplerelate on sample.sample_pkey = groupsamplerelate.sample_pkey
							left join grouprelate on groupsamplerelate.group_pkey = grouprelate.group_pkey
							where (sample.publ=1 or sample.userpkey=$userpkey or (grouprelate.users_pkey=$userpkey and grouprelate.confirmed=true))
							$boundstring 
							";
	
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
	
	$resultstring.=" and upstream=true group by sample.sample_pkey) foo";

//echo $resultstring;
//exit();
	
$mycount=$db->get_var($resultstring);

echo $mycount;
?>