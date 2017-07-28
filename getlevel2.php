<?PHP
/**
 * getlevel2.php
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

include('db.php'); // load database drivers, and connect
$level1 = $_GET['level1'];

$getrocknames = $db->get_results("select distinct upper(data2) as level2 from ech where upper(data1)='".$level1."' and data2 != 'unknown' and data2 != '' order by level2");
$xmlrockname="";

if($db->num_rows > 0){
	foreach($getrocknames as $g) {
	if($g->level2 != ""){
	$xmlrockname .= "
	<rockname>
		<name>$g->level2</name>
	</rockname>
	";
	}
	}
}


$ajaxresponse = "
<ajaxresponse>
$xmlrockname
</ajaxresponse>
";

echo $ajaxresponse;

?>
