<?PHP
/**
 * buildquery.php
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
}elseif($_POST['userpkey']!=""){
	$userrow=$db->get_row("select * from users where users_pkey=".$_POST['userpkey']);
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

//fix laboratoryname
if($_POST['labnames']==""){
	$_POST['labnames']=$_POST['laboratoryname'];
}

//echo "sampleagetype: ".$_POST['sampleagetype']."<br><br>";

if($_POST['locnorth']!="" || $_POST['loceast']!="" || $_POST['locsouth']!="" || $_POST['locwest']!="" || $_POST['long3']!="" || $_POST['coordinates']!=""){
	$db->query("update search_query set
						locnorth='',
						loceast='',
						locsouth='',
						locwest='',
						coordinates=''
					where search_query_pkey=$pkey");
}



if($_POST['long3'] != ""){ //take care of coordinates here
	$coorddelim="";
	$coordlist="";
	for($x=1;$x<26;$x++){
		eval("\$mylong = \$_POST['long$x'];");
		eval("\$mylat = \$_POST['lat$x'];");
		if($mylong!="" && $mylat!=""){
			$coordlist=$coordlist.$coorddelim.$mylong." ".$mylat;
			$coorddelim="; ";
		}
	}
	$db->query("update search_query set coordinates='$coordlist' where search_query_pkey=$pkey");
}

/*
if(count($_POST['agetype'])>0){
	$agetypelist=implode(",",$_POST['agetype']);
	//echo "agetypelist: $agetypelist<br>";
	$db->query("update search_query set sampleagetype='$agetypelist' where search_query_pkey=$pkey");
}
*/

//echo "agetype: ".$_POST['agetype']." <br>";


//echo "sampleagetype:<br><br>";

//first handle the sampleagetypes. PHP handles multiple
//selects as an array.
/*
if($_POST['sampleagetype']!=""){
	$mydelim="";
	$typelist="";
	$sampleagetypes=$_POST['sampleagetype'];
	foreach($sampleagetypes as $mytype){
		$typelist=$typelist.$mydelim.$mytype;
		$mydelim=",";
	}
	
	$_POST['sampleagetype']=$typelist;
}
*/

//now roll through all possible posted values and update
//the search_query table accordingly
$formlist="sampleagetype,locnorth,loceast,locsouth,locwest,igsn,igsnnamespace,sample_id,collector,parentigsn,agetypeproject,labnames,purposes,analystname,aliquotreference,aliquotinstmethod,aliquotmethodref,aliquotcomment,sampleagetype,sampleagevalue,sampleagevaluemin,sampleagevaluemax,ageunit,maxageuncertainty,hiddenrocktypes,sampleageerranalmin,sampleageerranalmax,sampleagemeanmin,sampleagemeanmax,sampleageerrsys,sampleageexpl,sampleagecomment,minerals,sampledescription,collectionmethod,samplecomment,primarylocationname,primarylocationtype,locationdescription,locality,localitydescription,country,provice,county,cityortownship,platform,platformid,originalarchivalinstitution,originalarchivalcontact,mostrecentarchivalinstitution,mostrecentarchivalcontact,coordinates,materials";
$formarray=explode(",",$formlist);
foreach($formarray as $formval){
	//echo $formval."<br>";
	if($_POST["$formval"]!=""){
		//echo "$formval: ".$_POST["$formval"]."<br>";
		$db->query("update search_query set $formval='".$_POST["$formval"]."' where search_query_pkey=$pkey");
	}
}

/*
foreach($formarray as $formval){
	echo $formval."<br>";
	eval("\$thisval=\$_POST['$formval'];");
	if($thisval!=""){
		echo "$formval: $thisval <br>";
		$db->query("update search_query set $formval='$thisval' where search_query_pkey=$pkey");
	}
}
*/


//now get all search criteria in an array so we can build
//the search query strings.
$queryrow=$db->get_row("select * from search_query where search_query_pkey=$pkey");

//let's take the coordinate string and break it up so we
//can put it in the right format for postgres to handle.
$coordlist="";
if($queryrow->coordinates != ""){
	$searchcoords=$queryrow->coordinates;
	$mycoordarray=explode("; ", $searchcoords);
	foreach($mycoordarray as $mycoord){
		$coordlist=$coordlist.$mycoord.", ";
	}
	$coordlist=$coordlist.$mycoordarray[0];
	//echo "coord list: ( $coordlist )";
}

if(
($queryrow->locnorth!="" &&
$queryrow->loceast!="" &&
$queryrow->locsouth!="" &&
$queryrow->locwest!="" ) ||
$queryrow->coordinates!="" ||
($queryrow->sampleagevalue!="" &&
$queryrow->maxageuncertainty!="") ||
($queryrow->sampleagevaluemin!="" &&
$queryrow->sampleagevaluemax!="" ) ||
$queryrow->minerals!="" ||
$queryrow->hiddenrocktypes!="" ||
$queryrow->igsn!="" ||
$queryrow->igsnnamespace!="" ||
$queryrow->sample_id!="" ||
$queryrow->collector!="" ||
$queryrow->sampledescription!="" ||
$queryrow->collectionmethod!="" ||
$queryrow->samplecomment!="" ||
$queryrow->primarylocationname!="" ||
$queryrow->primarylocationtype!="" ||
$queryrow->locationdescription!="" ||
$queryrow->locality!="" ||
$queryrow->localitydescription!="" ||
$queryrow->country!="" ||
$queryrow->provice!="" ||
$queryrow->county!="" ||
$queryrow->cityortownship!="" ||
$queryrow->platform!="" ||
$queryrow->platformid!="" ||
$queryrow->originalarchivalinstitution!="" ||
$queryrow->originalarchivalcontact!="" ||
$queryrow->mostrecentarchivalinstitution!="" ||
$queryrow->mostrecentarchivalcontact!="" ||
$queryrow->labnames!="" ||
$queryrow->sampleagetype!="" ||
$queryrow->analystname!="" ||
$queryrow->coordinates!="" ||
$queryrow->purposes!="" ||
$queryrow->materials!=""
){

	$delim="";

							


	if($queryrow->locnorth!="" && $queryrow->loceast!="" && $queryrow->locsouth!="" && $queryrow->locwest!=""){
		$newquerystring=$newquerystring."\n and sample.longitude >= ".$queryrow->locwest." and sample.longitude <= ".$queryrow->loceast." and sample.latitude >= ".$queryrow->locsouth." and sample.latitude <= ".$queryrow->locnorth;
	}
	
	if($queryrow->srs!="" && $queryrow->iedapolygon!=""){
	
		$srs = $queryrow->srs;
		$iedapolygon = $queryrow->iedapolygon;
	
		$newquerystring=$newquerystring."\n AND newgeom is not null \n"." 
			AND ST_Contains(ST_GeomFromText('POLYGON(($iedapolygon))',$srs),ST_Transform(newgeom,$srs))"; 
	
	}elseif($queryrow->coordinates!=""){
		$newpoly=$db->get_var("select fixpoly('Multipolygon((($coordlist)))')");
		$newquerystring=$newquerystring."\n and ST_Contains(ST_GeomFromText('$newpoly'), mypoint)"; $delim=" AND ";
		//$newquerystring=$newquerystring."\n and ST_Contains(ST_GeomFromText('Polygon(($coordlist))'), mypoint)"; $delim=" AND ";
	}
	
	
	
	if($queryrow->sampleagevaluemin != "" && $queryrow->sampleagevaluemax != ""){
		if($queryrow->ageunit=="ma"){
			$newquerystring=$newquerystring."\nand sample_age.age_value >= ".($queryrow->sampleagevaluemin * 1000000)." AND sample_age.age_value <= ".($queryrow->sampleagevaluemax * 1000000);
		}else{
			$newquerystring=$newquerystring."\nand sample_age.age_value >= ".($queryrow->sampleagevaluemin * 1000)." AND sample_age.age_value <= ".($queryrow->sampleagevaluemax * 1000);
		}
	}
	
	
	/*
	if($queryrow->maxageuncertainty!=""){
		$newquerystring=$newquerystring."\nand sample_age.one_sigma <= ".$queryrow->maxageuncertainty;
	}
	*/

	if($queryrow->sampleagevalue!="" && $queryrow->maxageuncertainty!="" and is_numeric($queryrow->sampleagevalue) && is_numeric($queryrow->maxageuncertainty)   ){
		
		$thisageval=$queryrow->sampleagevalue;
		$thisageuncertainty=$queryrow->maxageuncertainty;
		
		if($queryrow->ageunit=="ma"){
			$thisageval=$thisageval*1000000;
			$thisageuncertainty=$thisageuncertainty*1000000;
		}else{
			$thisageval=$thisageval*1000;
			$thisageuncertainty=$thisageuncertainty*1000;
		}
		
		$thisminage = $thisageval - $thisageuncertainty;
		$thismaxage = $thisageval + $thisageuncertainty;

		
		$newquerystring=$newquerystring."\nand sample_age.age_value >= ".$thisminage." AND sample_age.age_value <= ".$thismaxage;

	}

	if($queryrow->analystname!=""){
		$newquerystring=$newquerystring."\nand lower(sample.analyst_name) = '".strtolower($queryrow->analystname)."'";
	}
	
	if($queryrow->minerals!=""){
		$minerals=split(",",$queryrow->minerals);
		$mineraldelim="";
		foreach($minerals as $mineral){
			$minerallist.=$mineraldelim."'".$mineral."'";
			$mineraldelim=",";
		}
		$newquerystring=$newquerystring."\nand sample.mineral in ($minerallist)";
	}

	if($queryrow->sampleagetype!=""){
		$newquerystring.= " and (";
		$queryagetypes=split(",",$queryrow->sampleagetype);
		$queryagetypedelim="";
		foreach($queryagetypes as $queryagetype){
			$queryagetype=split(": ",$queryagetype);
			$queryecproject=$queryagetype[0];
			$queryagetype=$queryagetype[1];
			
			$showecproject="";
			if($queryecproject=="U-Pb Ion Microprobe"){$showecproject="'squid','zips'";}
			if($queryecproject=="U-Pb Tims"){$showecproject="'redux'";}
			if($queryecproject=="(U-Th)/He"){$showecproject="'helios','uthhelegacy'";}
			if($queryecproject=="ArAr"){$showecproject="'arar','ararxls'";}
			if($queryecproject=="Fission Track"){$showecproject="'fissiontrack'";}


			$newquerystring.=$queryagetypedelim."(sample.ecproject in ($showecproject) and sample_age.age_name='$queryagetype')";
			
			$queryagetypedelim=" OR ";
		}
		
		$newquerystring.= ") ";
	}
	


	/*
	if($queryrow->sampleagetype!=""){
		$queryagetypes=split(",",$queryrow->sampleagetype);
		$queryagetypedelim="";
		foreach($queryagetypes as $queryagetype){
			$queryagetype=split(": ",$queryagetype);
			$queryagetype=$queryagetype[1];
			$queryagetypelist.=$queryagetypedelim."'".$queryagetype."'";
			$queryagetypedelim=",";
		}
		$newquerystring=$newquerystring."\nand sample_age.age_name in ($queryagetypelist)";
		//$newquerystring=$newquerystring."\nand sample.ecproject = '".$queryrow->agetypeproject."'";;
	}
	*/
	




	if($queryrow->hiddenrocktypes!=""){
		$hiddenrocktypes=split(",",$queryrow->hiddenrocktypes);
		$rocktypedelim="";
		foreach($hiddenrocktypes as $hiddenrocktype){
			$rocktypelist.=$rocktypedelim."'".$hiddenrocktype."'";
			$rocktypedelim=",";
		}
		$newquerystring=$newquerystring."\nand sample.rocktype in ($rocktypelist)";
	}

	if($queryrow->labnames!=""){
		$labnames=explode("***",$queryrow->labnames);
		$labnamedelim="";
		foreach($labnames as $labname){
			$labnamelist.=$labnamedelim."'".$labname."'";
			$labnamedelim=",";
		}
		$newquerystring=$newquerystring."\nand sample.laboratoryname in ($labnamelist)";
	}

	if($queryrow->purposes!=""){
		$purposes=explode("***",$queryrow->purposes);
		$purposedelim="";
		foreach($purposes as $purpose){
			$purposelist.=$purposedelim."'".$purpose."'";
			$purposedelim=",";
		}
		//$newquerystring=$newquerystring."\nand sample_age.age_name in ($purposelist)";
		$newquerystring=$newquerystring."\nand sample.purpose in ($purposelist)";
	}

	if($queryrow->materials!=""){
		$materials=explode("***",$queryrow->materials);
		$materialdelim="";
		foreach($materials as $material){
			$materiallist.=$materialdelim."'".$material."'";
			$materialdelim=",";
		}
		$newquerystring=$newquerystring."\nand sample.material in ($materiallist)";
	}



	if($queryrow->igsn!=""){$newquerystring.="\nand (lower(igsn) like '%".strtolower($queryrow->igsn)."%' or lower(parentigsn) like '%".strtolower($queryrow->igsn)."%')";}
	if($queryrow->igsnnamespace!=""){$newquerystring.="\nand lower(igsn) like '".strtolower($queryrow->igsnnamespace)."%'";}
	if($queryrow->sample_id!=""){$newquerystring.="\nand lower(sample_id) like '%".strtolower($queryrow->sample_id)."%'";}
	if($queryrow->collector!=""){$newquerystring.="\nand lower(collector) like '%".strtolower($queryrow->collector)."%'";}
	if($queryrow->sampledescription!=""){$newquerystring.="\nand lower(sample_description) like '%".strtolower($queryrow->sampledescription)."%'";}
	if($queryrow->collectionmethod!=""){$newquerystring.="\nand lower(collectionmethod) like '%".strtolower($queryrow->collectionmethod)."%'";}
	if($queryrow->samplecomment!=""){$newquerystring.="\nand lower(sample_comment) like '%".strtolower($queryrow->samplecomment)."%'";}
	if($queryrow->primarylocationname!=""){$newquerystring.="\nand lower(primarylocationname) like '%".strtolower($queryrow->primarylocationname)."%'";}
	if($queryrow->primarylocationtype!=""){$newquerystring.="\nand lower(primarylocationtype) like '%".strtolower($queryrow->primarylocationtype)."%'";}
	if($queryrow->locationdescription!=""){$newquerystring.="\nand lower(locationdescription) like '%".strtolower($queryrow->locationdescription)."%'";}
	if($queryrow->locality!=""){$newquerystring.="\nand lower(locality) like '%".strtolower($queryrow->locality)."%'";}
	if($queryrow->localitydescription!=""){$newquerystring.="\nand lower(localitydescription) like '%".strtolower($queryrow->localitydescription)."%'";}
	if($queryrow->country!=""){$newquerystring.="\nand lower(country) like '%".strtolower($queryrow->country)."%'";}
	if($queryrow->provice!=""){$newquerystring.="\nand lower(provice) like '%".strtolower($queryrow->provice)."%'";}
	if($queryrow->county!=""){$newquerystring.="\nand lower(county) like '%".strtolower($queryrow->county)."%'";}
	if($queryrow->cityortownship!=""){$newquerystring.="\nand lower(cityortownship) like '%".strtolower($queryrow->cityortownship)."%'";}
	if($queryrow->platform!=""){$newquerystring.="\nand lower(platform) like '%".strtolower($queryrow->platform)."%'";}
	if($queryrow->platformid!=""){$newquerystring.="\nand lower(platformid) like '%".strtolower($queryrow->platformid)."%'";}
	if($queryrow->originalarchivalinstitution!=""){$newquerystring.="\nand lower(originalarchivalinstitution) like '%".strtolower($queryrow->originalarchivalinstitution)."%'";}
	if($queryrow->originalarchivalcontact!=""){$newquerystring.="\nand lower(originalarchivalcontact) like '%".strtolower($queryrow->originalarchivalcontact)."%'";}
	if($queryrow->mostrecentarchivalinstitution!=""){$newquerystring.="\nand lower(mostrecentarchivalinstitution) like '%".strtolower($queryrow->mostrecentarchivalinstitution)."%'";}
	if($queryrow->mostrecentarchivalcontact!=""){$newquerystring.="\nand lower(mostrecentarchivalcontact) like '%".strtolower($queryrow->mostrecentarchivalcontact)."%'";}

	// and GeomFromText('Polygon(($coordbox))') ~ mypoint limit 25



	$mapquerystring="select sample.sample_pkey, 
							sample.sample_id,
							sample.igsn,
							sample.laboratoryname, 
							sample.analyst_name,
							sample.ecproject,
							sample.latitude,
							sample.longitude,
							sample.userpkey,
							sample.material,
							sample.filename,
							age_min, age_max, age_value, one_sigma, age_name,
							getagetypes(sample.sample_pkey) as agetypes
							from sample 
							left join sample_age on sample.sample_pkey = sample_age.sample_pkey
							left join groupsamplerelate on sample.sample_pkey = groupsamplerelate.sample_pkey
							left join grouprelate on groupsamplerelate.group_pkey = grouprelate.group_pkey
							left join groups on grouprelate.group_pkey = groups.group_pkey
							left join datasetrelate dr on dr.sample_pkey = sample.sample_pkey
							left join datasetuserrelate dur on dur.dataset_pkey = dr.dataset_pkey
							left join datasets ds on dr.dataset_pkey = ds.dataset_pkey
							where 1=1 ".$newquerystring."
										and ST_GeomFromText('Polygon(($coordbox))') ~ mypoint
										--and publ=1 
										--and (sample.publ=1 or users.usergroup=$group or users.users_pkey=$userpkey)
										--and (sample.publ=1 or array_intersect(users.grouparray, ARRAY[$grouparray]) is not null or users.users_pkey=$userpkey)
										and (sample.publ=1 or sample.userpkey=$userpkey or ((dur.users_pkey=$userpkey and dur.confirmed=true) or ds.users_pkey=$userpkey ) or ((grouprelate.users_pkey=$userpkey and grouprelate.confirmed=true) or groups.users_pkey=$userpkey))
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
										 sample.filename,
										age_min, age_max, age_value, one_sigma, age_name,
										agetypes
										";



	$newquerystring="select sample.sample_pkey, 
							sample.sample_id,
							sample.igsn,
							sample.laboratoryname, 
							sample.analyst_name,
							sample.ecproject,
							sample.latitude,
							sample.longitude,
							sample.userpkey,
							sample.material,
							sample.filename,
							age_min, age_max, age_value, one_sigma, age_name,upstream,
							getagetypes(sample.sample_pkey) as agetypes
							from sample 
							left join sample_age on sample.sample_pkey = sample_age.sample_pkey
							left join groupsamplerelate on sample.sample_pkey = groupsamplerelate.sample_pkey
							left join grouprelate on groupsamplerelate.group_pkey = grouprelate.group_pkey
							left join groups on grouprelate.group_pkey = groups.group_pkey
							left join datasetrelate dr on dr.sample_pkey = sample.sample_pkey
							left join datasetuserrelate dur on dur.dataset_pkey = dr.dataset_pkey
							left join datasets ds on dr.dataset_pkey = ds.dataset_pkey
							where 1=1 ".$newquerystring;

	$mmnewquerystring=$newquerystring."
									and (sample.publ=1 or sample.userpkey=$userpkey or ((dur.users_pkey=$userpkey and dur.confirmed=true) or ds.users_pkey=$userpkey ) or ((grouprelate.users_pkey=$userpkey and grouprelate.confirmed=true) or groups.users_pkey=$userpkey))
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
									 sample.filename,
									age_min, age_max, age_value, one_sigma, age_name,
									agetypes";




	$newquerystring=$newquerystring."
									and (sample.publ=1 or sample.userpkey=$userpkey or ((dur.users_pkey=$userpkey and dur.confirmed=true) or ds.users_pkey=$userpkey ) or ((grouprelate.users_pkey=$userpkey and grouprelate.confirmed=true) or groups.users_pkey=$userpkey))
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
									 sample.filename,
									age_min, age_max, age_value, one_sigma, age_name,upstream,
									agetypes
									";



	$db->query("update search_query set querystring=".pg_escape_literal($newquerystring)." where search_query_pkey=$pkey");
	
	$queryrow->querystring=$newquerystring;

	//echo "newquerystring: $newquerystring <br><br>";
	

}else{//nothing set

	$db->query("
		update search_query set querystring='' where search_query_pkey=$pkey
	");
	$queryrow->querystring="";
	
	$newquerystring="";
	
	//echo "nothing set.";


}//end if anything is set


?>