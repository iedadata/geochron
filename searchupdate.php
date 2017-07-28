<?PHP
/**
 * searchupdate.php
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

//print_r($_POST);

$pkey=$_POST['pkey'];

include("db.php");

$db->query("update search_query set 
			coordinates='',
			sampleagevaluemin = null,
			sampleagevaluemax = null,
			sampleagetype = null,
			hiddenrocktypes = null,
			materials = null,
			igsn= null,
			sample_id= null,
			collector= null,
			sampledescription= null,
			collectionmethod= null,
			samplecomment= null,
			primarylocationname= null,
			primarylocationtype= null,
			locationdescription= null,
			locality= null,
			localitydescription= null,
			country= null,
			provice= null,
			ageunit= null,
			labnames= null,
			purposes= null,
			locnorth = null,
			loceast = null,
			locsouth = null,
			locwest = null,
			sampleagevalue = null,
			maxageuncertainty = null
			where search_query_pkey = $pkey");

foreach($_POST as $key=>$value){
	if($value!=""){
		//echo "$key : $value <br>";
	}
}


include("buildquery.php");


//echo nl2br($newquerystring)."<br><br>";

?>
<div class="aboutpage">
<?

//echo nl2br($newquerystring);

if($newquerystring!=""){
	$mycount=$db->get_var("select count(*) from ($newquerystring) foo;");
	
	if($mycount==0){
		echo "No Results Found.";
	}elseif($mycount==1){
		echo "1 Result Found. <br> <br> <a href=\"results.php?pkey=$pkey\" target=\"_blank\">View Results</a>";
	}else{
		echo "$mycount Results Found. <br> <br> <a href=\"results.php?pkey=$pkey\" target=\"_blank\">View Results</a>";
	}
	
}else{
	echo "No Search Set.";
}


//$mycount=$db->get_var("select count(*) as count from ( $newquerystring ) foo");




?>
</div>