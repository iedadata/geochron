<?PHP
/**
 * checkigsn.php
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

header("Content-type: text/xml"); 


if($_GET['igsn']!=""){
	$igsn=$_GET['igsn'];
}else{
	$igsn=$_POST['igsn'];
}


if($igsn==""){
	exit();
}

$igsn=strtoupper($igsn);

include("db.php");

$rows=$db->get_results("select * from sample where upper(igsn) like '%$igsn%'");

echo "<results>\n";

if(count($rows)>0){
	echo "\t<IGSNExists>yes</IGSNExists>\n";
	echo "\t\t<SampleIDs>\n";

	foreach($rows as $row){
		echo "\t\t\t<SampleID>".htmlentities($row->sample_id)."</SampleID>\n";
	}

	echo "\t\t</SampleIDs>\n";
}else{
	echo "\t<IGSNExists>no</IGSNExists>\n";
}


echo "</results>";

?>