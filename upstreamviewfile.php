<?PHP
/**
 * upstreamviewfile.php
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
include("db.php");

include("includes/geochron-secondary-header.htm");



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

//echo "group: $group userpkey: $userpkey <br><br>";

$pkey=$_GET['pkey'];

?>
<div class="aboutpage">
<div class="headline">Details
<a href="downloadfile.php?pkey=<?=$pkey?>">(Download XML File)</a>
</div>
</div>
<?

$myrow=$db->get_row("select * from sample 
					left join users on sample.userpkey = users.users_pkey
					where sample.sample_pkey=$pkey
					--and (sample.publ=1 or users.usergroup=$group or users.users_pkey=$userpkey)");


$filename=$myrow->filename;
$ecproject=$myrow->ecproject;

if($filename==""){
	exit();
}

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
}elseif($ecproject=="fissiontrack"){
	$xsltfile="transforms/fissiontrackxslt.xslt";
}

$xp = new XsltProcessor();
// create a DOM document and load the XSL stylesheet
$xsl = new DomDocument;
$xsl->load($xsltfile);

// import the XSL styelsheet into the XSLT process
$xp->importStylesheet($xsl);

// create a DOM document and load the XML datat
$xml_doc = new DomDocument;
$xml_doc->load("files/$filename");

// transform the XML into HTML using the XSL file
if ($html = $xp->transformToXML($xml_doc)) {
    echo $html;
} else {
    trigger_error('XSL transformation failed.', E_USER_ERROR);
} // if 



include("includes/geochron-secondary-footer.htm");

?>