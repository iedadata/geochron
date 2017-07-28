<?PHP
/**
 * viewfile.php
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

include("db.php");
session_start();

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









//echo "userpkey:$userpkey";







$pkey=$_GET['pkey'];

if($pkey == "igor"){

	$filename="igor_v2.xml";
	$ecproject="igor";

}else{

	/*
	$myrow=$db->get_row("select * from sample 
						left join users on sample.userpkey = users.users_pkey
						where sample.sample_pkey=$pkey
						--and (sample.publ=1 or users.usergroup=$group or users.users_pkey=$userpkey)");
	*/


	$myrow=$db->get_row("select * from sample
						left join groupsamplerelate on sample.sample_pkey = groupsamplerelate.sample_pkey
						left join grouprelate on grouprelate.group_pkey = groupsamplerelate.group_pkey
						left join groups on grouprelate.group_pkey = groups.group_pkey
						left join datasetrelate dr on dr.sample_pkey = sample.sample_pkey
						left join datasetuserrelate dur on dur.dataset_pkey = dr.dataset_pkey
						left join datasets ds on dr.dataset_pkey = ds.dataset_pkey
						where sample.sample_pkey = $pkey
						and (sample.publ=1 or ((dur.users_pkey=$userpkey and dur.confirmed=true) or ds.users_pkey=$userpkey ) or (sample.userpkey = $userpkey or grouprelate.users_pkey=$userpkey or groups.users_pkey=$userpkey));
						");

	$filename=$myrow->filename;

	$ecproject=$myrow->ecproject;

	if($ecproject=="zips"){

		$filename=str_replace("zip","xml",$filename);

	}

	$geochron_pkey=str_replace(".xml","",$filename);

}

//echo "filename:$filename";

//echo "ecproject:$ecproject";exit();

if($filename==""){
	echo "Error: No file found.";exit();
}

include("includes/geochron-secondary-header.htm");

if($ecproject=="redux"){

	//Find method and choose ICPMS xsltfile if necessary

	$thismethod="";	

	$dom = new DomDocument;
	$dom->load("files/$filename");

	$aliquots = $dom->getElementsByTagName("Aliquot");

	foreach($aliquots as $aliquot){

		$myaliquotinstrumentalmethods=$aliquot->getElementsByTagName("aliquotInstrumentalMethod");
		foreach($myaliquotinstrumentalmethods as $myaliquotinstrumentalmethod){
		
			$thismethod=$myaliquotinstrumentalmethod->textContent;
		
		}

	}//end foreach aliquot


	$thismethod=strtolower($thismethod);
	
	if (strlen(strstr($thismethod,"icpms"))>0) {
		$xsltfile="transforms/icpmsmainfile.xslt";
	}else{
		$xsltfile="transforms/mainfile.xslt";
	}

	//echo "xsltfile: $xsltfile";
	//exit();

	//echo "method: $thismethod";
	//exit();


	//$xsltfile="mainfile.xslt";


}elseif($ecproject=="arar"){
	$xsltfile="transforms/arar.xslt";
}elseif($ecproject=="helios"){
	$xsltfile="transforms/helios.xslt";
}elseif($ecproject=="zips"){
	$xsltfile="http://www.geochron.org/transforms/zips_$geochron_pkey.xslt";
}elseif($ecproject=="uthhelegacy"){
	//$xsltfile="uthhexls.xslt";
	$xsltfile="http://www.geochron.org/transforms/uthhexls_".$pkey.".xslt";
}elseif($ecproject=="squid"){
	//$xsltfile="squid.xslt";
	$xsltfile="http://www.geochron.org/transforms/squid_".$pkey.".xslt";
}elseif($ecproject=="squid2"){
	//$xsltfile="squid.xslt";
	$xsltfile="http://www.geochron.org/transforms/squid2_".$pkey.".xslt";
}elseif($ecproject=="fissiontrack"){
	//$xsltfile="squid.xslt";
	$xsltfile="http://www.geochron.org/transforms/fissiontrackxslt.xslt";
}elseif($ecproject=="ararxls"){
	//$xsltfile="squid.xslt";
	$xsltfile="http://www.geochron.org/transforms/ararxslt_".$pkey.".xslt";
}elseif($ecproject=="igor"){
	$xsltfile="igor.xslt";
}

//echo "xsltfile: $xsltfile"; exit();

$xp = new XsltProcessor();
// create a DOM document and load the XSL stylesheet
$xsl = new DomDocument;
$xsl->load($xsltfile);

// import the XSL styelsheet into the XSLT process
$xp->importStylesheet($xsl);

// create a DOM document and load the XML datat
$xml_doc = new DomDocument;
$xml_doc->load("files/$filename");

//echo "filename: files/$filename";

//echo $xml_doc->saveXML();exit();

// transform the XML into HTML using the XSL file
if ($html = $xp->transformToXML($xml_doc)) {
    echo $html;
} else {
    trigger_error('XSL transformation failed.', E_USER_ERROR);
} // if 



include("includes/geochron-secondary-footer.htm");

?>