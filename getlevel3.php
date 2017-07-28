<?PHP
/**
 * getlevel3.php
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

$rocknames=$db->get_results("select distinct upper(data3) as level3 from ech where upper(data1)='".$_GET['level1']."' and upper(data2)='".$_GET['level2']."' and data3 != 'unknown' and data3!='' order by level3");


$xmlrockname="";

if($db->num_rows > 0){
	foreach($rocknames as $r) {
	$xmlrockname .= "
	<rockname>
		<name>$r->level3</name>
	</rockname>
	";
	}
}

$ajaxresponse = "
<ajaxresponse>
$xmlrockname
</ajaxresponse>
";

echo $ajaxresponse;

?>