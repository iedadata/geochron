<?PHP
/**
 * dategraph.php
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

$month=1;
$year=2010;

$oldyear=2009;
$oldmonth=12;

$olddate = "12-1-2009";

while($year < 2017){

	$mydate = $month."-1-".$year;
	
	$uploadcount=$db->get_var("select count(*) from sample where uploaddatetime >= '$olddate' and uploaddatetime < '$mydate';");

	$usercount=$db->get_var("select count(distinct(userpkey)) from sample where uploaddatetime >= '$olddate' and uploaddatetime < '$mydate';");

	$totalcount=$db->get_var("select count(*) from sample where uploaddatetime < '$mydate';");
	
	
	//$privatecount=$db->get_var("select count(*) from sample where uploaddatetime < '$mydate' and publ=0;");

	echo "$olddate,$mydate,$uploadcount,$usercount,$totalcount<br>";

	$olddate = $mydate;
	
	$month++;
	if($month==13){
		$month=1;
		$year++;
	}
}







?>