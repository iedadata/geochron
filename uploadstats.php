<?PHP
/**
 * uploadstats.php
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

$today=getdate(); // all the possible parts of a date are in this array: print_r($today); 

$nowyear=$today['year'];
$nowmonth=$today['mon'];

//echo "nowyear: $nowyear nowmonth: $nowmonth <br>";



//2007-9-1

if($_GET['start_month']!=""){

	$start_date=$_GET['start_month'];

}elseif($_GET['start_date']!=""){

	$start_date=$_GET['start_date'];

}


if($start_date!=""){

	$parts=explode("-",$start_date);
	
	//print_r($parts);exit();
	
	$year=$parts[0];
	$month=$parts[1];
	$month=$month+0;

	//echo "month: $month  year:$year ";exit();
	
	if((!is_numeric($year))||(!is_numeric($month))||($year<2007)){
		echo "invalid date specified";exit();
	}

}else{

	$year=2010;
	$month=4;
	
}


 
include("includes/geochron-secondary-header.htm");
?>
<style type="text/css">
table.aliquot, table.sample  {
	border-width: 1px 1px 1px 1px;
	border-spacing: 2px;
	border-style: none none none none;
	border-color: #999999; /*#636363;*/
	border-collapse: collapse;
	background-color: white;
}
table.aliquot th, table.sample th  {
	font-family:arial,verdana,sans-serif;
	font-size:9pt;
	font-weight: 500;
	color:#333333;
	text-transform:uppercase;
	text-align:left;
	/*color: #666699; #636363; #FFFFFF;*/
	border-color: #999999;
	border-width: 1px 1px 1px 1px;
	padding: 5px 5px 5px 5px;
	border-style: solid solid solid solid;
	background-color: #f0f4f5; /* NYTimes tabs background blue. Tried others: #d7e6fc; 325280 #003366;*/
}
table.sample th {
	background-color:antiquewhite;text-transform:none;
	}
table.aliquot td, table.sample td  {
	border-width: 1px 1px 1px 1px;
	border-color: #999999;
	padding: 2px 5px 2px 5px;
	border-style: solid solid solid solid;
	background-color: white;
}
</style>
<?




?>
<h1>EarthChem Citation Statistics</h1><br>
<table class="aliquot">
	<tr>
		<th>Start Date</th>
		<th>End Date</th>
		<th>Number of Uploads</th>
	</tr>

<?

$go="yes";

while($go == "yes"){
	$nextmonth=$month+3;
	$nextyear=$year;
	if($nextmonth>12){
		$nextmonth=1;
		$nextyear++;
	}
	
	$thisdate = DateTime::createFromFormat('Y-n-j', $year."-".$month."-15");
	
	$nowdate = new DateTime("now");
	
	//$datediff = date_diff($thisdate, $nowdate);
	
	//$datediff = $datediff->days;
	
	if($thisdate > $nowdate){
		$go="no";
		
		//echo "$nowdate";
	}
	
	if($go=="yes"){
	
	//get uniquedownloads, uniqueips, samplerecords
	
	$numuploads=$db->get_var("select count(*) from (
									select
									sample_pkey
									from sample where uploaddatetime >= '$year-$month-15'::date and uploaddatetime < '$nextyear-$nextmonth-15'::date
									group by sample_pkey
									) foo");
									

?>

	<tr>
		<td><?=$year?>-<?=$month?>-15</td>
		<td><?=$nextyear?>-<?=$nextmonth?>-15</td>
		<td><?=$numuploads?></td>
	</tr>

<?
	
	}
	
	$month=$month+3;
	
	if($month>12){
		$year++;
		$month=1;
	}
	
	if($year==2014){
		$go="no";
	}

}

//$date = DateTime::createFromFormat('j-M-Y', '15-Feb-2009');

?>
</table>

<?
include("includes/geochron-secondary-footer.htm");


























?>