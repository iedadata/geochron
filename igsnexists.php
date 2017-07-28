<?PHP
/**
 * igsnexists.php
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

$igsn=$_GET['igsn'];

if($igsn==""){
	$igsn="xxxxxxxxxxx";
}

$igsn=strtolower($igsn);

$igsn=str_replace("SES.","",$igsn);
$igsn=str_replace("GCH.","",$igsn);

$count=$db->get_var("select count(*) from sample where lower(igsn) like '%$igsn'");

if($count>0){
	echo "<results>yes</results>";
}else{
	echo "<results>no</results>";
}




?>