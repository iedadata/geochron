<?PHP
/**
 * viewid.php
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

$igsn=$_GET['id'];
?>
<html>
<head>
	<style media="all" type="text/css">
		@import url('http://app.geosamples.org/includes/css/sesar.css');
	</style>
	<title>SESAR | My Samples</title>
</head>
<div id="header">
		<span class="lib-name">SESAR</span>

</div>

<div style="padding-left:50px;">
Note: The following metadata come from the SESAR database (<a href="http://www.geosamples.org" target="_blank">http://www.geosamples.org</a>).
</div>

<div id="content">
<h1>IGSN: <?=$igsn?></h1>





<?

if(substr($igsn,0,3)=="SSX"){
	$suburl = "https://sesardev.geosamples.org/webservices/display.php?igsn=";
}else{
	$suburl = "https://app.geosamples.org/webservices/display.php?igsn=";
}
	
//echo "$igsn";

$igsn=str_replace("SSR.","",$igsn);
$igsn=str_replace("SSX.","",$igsn);


$xsltfile="transforms/sesar.xslt";

$xp = new XsltProcessor();
// create a DOM document and load the XSL stylesheet
$xsl = new DomDocument;

$xsl->load($xsltfile);

// import the XSL styelsheet into the XSLT process
$xp->importStylesheet($xsl);

// create a DOM document and load the XML datat
$xml_doc = new DomDocument;
//$xml_doc->load("http://gfg.ldeo.columbia.edu/sesar/display.php?igsn=$igsn");

//echo "http://app.geosamples.org/webservices/display.php?igsn=$igsn";exit();

//$xml_doc->load("http://app.geosamples.org/webservices/display.php?igsn=$igsn");

$url = $suburl.$igsn;

//$url = "http://app.geosamples.org/webservices/display.php?igsn=$igsn";

$headers = array(
	"Content-Type: text/xml"
);
$rest = curl_init();
curl_setopt($rest,CURLOPT_URL,$url);
curl_setopt($rest,CURLOPT_HTTPHEADER,$headers);
curl_setopt($rest,CURLOPT_RETURNTRANSFER, true);
curl_setopt($rest, CURLOPT_VERBOSE, 1);
curl_setopt($rest, CURLOPT_HEADER, 1);
$response = curl_exec($rest);
$header_size = curl_getinfo($rest, CURLINFO_HEADER_SIZE);
$header = substr($response, 0, $header_size);
$body = substr($response, $header_size);



//echo "body: ".$body;exit();


$xml_doc->loadXML($body);







//print_r($xml_doc->textContent);exit();

$mystatuses=$xml_doc->getElementsByTagName("status");

//print_r($mystatuses);exit();

foreach($mystatuses as $mystatus){
	$status=$mystatus->textContent;
}


if($status!=""){
echo $status; exit();
}


$mylons=$xml_doc->getElementsByTagName("longitude");
foreach($mylons as $mylon){
	$lon=$mylon->textContent;
}

$mylats=$xml_doc->getElementsByTagName("latitude");
foreach($mylats as $mylat){
	$lat=$mylat->textContent;
}


//echo "lon: $lon lat: $lat<br>";

if($lat!="Not Provided"&&$lon!="Not Provided"&&$lat!=""&&$lon!=""){
?>

 <iframe frameborder=0 width="640px" height="440px" src="http://www.geochron.org/indsampleinteractivemap?lon=<?=$lon?>&lat=<?=$lat?>"></iframe><br>

<?
}


// transform the XML into HTML using the XSL file

if ($html = $xp->transformToXML($xml_doc)) {
    echo $html;
    //print_r(libxml_get_errors());
} else {
    trigger_error('XSL transformation failed.', E_USER_ERROR);
    //echo "IGSN not found in SESAR database.";
} // if 


?>