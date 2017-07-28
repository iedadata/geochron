<?PHP
/**
 * samplexml.php
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

header('Content-Type: text/xml');

include("db.php");

$igsn=strtoupper($_GET['igsn']);
$sampleigsn=$_GET['sampleigsn'];
$aliquotname=$_GET['aliquotname'];

if($igsn==""){
	$igsn=$sampleigsn;
}

if($igsn==""){
	echo "<results>
	<error>IGSN must be provided.</error>
</results>";
	exit();
}

$igsn=str_replace("SES.","",$igsn);
$igsn=str_replace("GCH.","",$igsn);

$querystring="";

$pkey=$_GET['pkey'];

if($igsn==""){
	if($pkey!=""){
		$querystring=" and samp.sample_pkey=$pkey ";
	}else{
		$querystring=" and 1 = 2 "; //force failure
	}
}else{
	$querystring=" and sample.parentigsn like '%$igsn'";
}

$username=$_GET['username'];
$password=$_GET['password'];
$validateonly=$_GET['validateonly'];

$url = 'http://app.geosamples.org/webservices/credentials_service.php';
$fields = array(
            'username'=>urlencode($username),
            'password'=>urlencode($password)
        );

//url-ify the data for the POST
foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
rtrim($fields_string,'&');

//open connection
$ch = curl_init();

//set the url, number of POST vars, POST data
curl_setopt($ch,CURLOPT_URL,$url);
curl_setopt($ch,CURLOPT_POST,count($fields));
curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

//execute post
$result = curl_exec($ch);

//close connection
curl_close($ch);

//echo $result; exit();

//load xml from $result and parse for valid==true


$dom = new DomDocument;
$dom->loadXML($result);

$validmessage=="";

$results=$dom->getElementsByTagName("results");
foreach($results as $result){

	$valids=$result->getElementsByTagName("valid");
	foreach($valids as $valid){
	
		$validmessage=$valid->textContent;
	
	}

}


if($validmessage=="yes"){
	$userrow=$db->get_row("select * from users where email='$username'");

}







$group=$userrow->usergroup;
$userpkey=$userrow->users_pkey;

//echo "group: $group userpkey: $userpkey";exit();

if($group==0 or $group==""){
	$group=99999;
}

if($userpkey==""){
	$userpkey=99999;
}

//$samplerow=$db->get_row

//add option for sampleid here
if($aliquotname!=""){
	$sidstring="and aliquotname = '$aliquotname'\n";
}



$rows=$db->get_results("
select sample.sample_pkey,
sample.filename,
sample.orig_filename
from sample
left join sample_age on sample.sample_pkey = sample_age.sample_pkey
left join groupsamplerelate on sample.sample_pkey = groupsamplerelate.sample_pkey
left join grouprelate on groupsamplerelate.group_pkey = grouprelate.group_pkey
left join groups on grouprelate.group_pkey = groups.group_pkey
left join datasetrelate dr on dr.sample_pkey = sample.sample_pkey
left join datasetuserrelate dur on dur.dataset_pkey = dr.dataset_pkey
left join datasets ds on dr.dataset_pkey = ds.dataset_pkey
where 1=1 and ecproject='redux'
and (sample.publ=1 or sample.userpkey=$userpkey or ((dur.users_pkey=$userpkey and dur.confirmed=true) or ds.users_pkey=$userpkey ) or ((grouprelate.users_pkey=$userpkey and grouprelate.confirmed=true) or groups.users_pkey=$userpkey))
$querystring
group by
sample.sample_pkey,
sample.filename,
sample.orig_filename
");


//print_r($db);



//$newdom = new DOMDocument('1.0', 'iso-8859-1');
$newdom = new DOMDocument;
$newdom->loadXML("<Aliquots></Aliquots>");

foreach($rows as $row){

	$pkey=$row->sample_pkey;
	$filename=$row->filename;
	$origfilename=$row->orig_filename;

	$dom = new DomDocument;
	$dom->load("files/$filename");
	$myaliquots = $dom->getElementsByTagName("Aliquot");
	foreach($myaliquots as $myaliquot){

		$newnode = $newdom->importNode($myaliquot, true);
		// And then append it to the "<root>" node
		//$newdoc->documentElement->appendChild($node);
	
		$newdom->documentElement->appendChild($newnode);
	
		//$newdom->appendChild($newnode);
	}

}

$newxml = $newdom->saveXML();

echo $newxml;



?>