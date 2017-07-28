<?PHP
/**
 * stats.php
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

$monthnames[1]="January";
$monthnames[2]="February";
$monthnames[3]="March";
$monthnames[4]="April";
$monthnames[5]="May";
$monthnames[6]="June";
$monthnames[7]="July";
$monthnames[8]="August";
$monthnames[9]="September";
$monthnames[10]="October";
$monthnames[11]="November";
$monthnames[12]="December";

$yearrows=$db->get_results("select 
							year,
							sum(singlecount) as singlesum,
							sum(xlscount) as xlssum
							from
							(select
							month,
							day,
							year,
							(CASE WHEN downloadtype = 'single sample' THEN 1 ELSE 0 END) AS singlecount,
							(CASE WHEN downloadtype = 'xls download' THEN 1 ELSE 0 END) AS xlscount
							from stats where year is not null order by pkey) foo
							group by year order by year;");



$monthrows=$db->get_results("select 
							month,
							year,
							sum(singlecount) as singlesum,
							sum(xlscount) as xlssum
							from
							(select
							month,
							day,
							year,
							(CASE WHEN downloadtype = 'single sample' THEN 1 ELSE 0 END) AS singlecount,
							(CASE WHEN downloadtype = 'xls download' THEN 1 ELSE 0 END) AS xlscount
							from stats where year is not null order by pkey) foo
							group by month, year order by year, month;");



if($_GET['v']=="csv"){

	header('Content-Type: application/csv');
	header('Content-Disposition: attachment; filename=geochron_citation_stats.csv');
	header('Pragma: no-cache');


	echo "Yearly Stats\n\n";
	
	echo "Year,Sample,XLS,Total Downloads\n";

	foreach($yearrows as $yearrow){

		$year=$yearrow->year;
		$singlesum=$yearrow->singlesum;
		$xlssum=$yearrow->xlssum;
		$total = $singlesum + $xlssum;

		
		//echo "foo ".$yearrow->year.",".$yearrow->singlesum.",".$yearrow->xlssum.",".$yearrow->singlesum+$yearrow->xlssum."\n";
		echo "$year,$singlesum,$xlssum,$total\n";

	}

	
	echo "\nMonthly Stats\n";
	echo "\nMonth,Year,Sample,XLS,Total Downloads\n";
	

	foreach($monthrows as $monthrow){
		$month=$monthrow->month;
		//$showmonth = date("F", mktime(0, 0, 0, ($month)));
		$showmonth = $monthnames[$month];

		$year=$monthrow->year;
		$singlesum=$monthrow->singlesum;
		$xlssum=$monthrow->xlssum;
		$total = $singlesum + $xlssum;

		echo "$showmonth,$year,$singlesum,$xlssum,$total\n";
	}



	echo "\nSample Stats\n";
	echo "Month,Year,New Samples,Total Samples,Active Users\n";


	$year=2010;
	$month=4;

	$go="yes";
	
	$totalcount=0;
	
	while($go == "yes"){
		$nextmonth=$month+1;
		$nextyear=$year;
		if($nextmonth==13){
			$nextmonth=1;
			$nextyear++;
		}
		
		$thisdate = DateTime::createFromFormat('Y-n-j', $year."-".$month."-1");
		
		$nowdate = new DateTime("now");
		
		if($thisdate > $nowdate){
			$go="no";
		}
		
		if($go=="yes"){
		
			
			$samplecount=$db->get_var("select count(*) as mycount from sample where uploaddatetime >= '$year-$month-1'::date and uploaddatetime < '$nextyear-$nextmonth-1'::date;");
			
			$usercount = $db->get_var("select count(distinct(userpkey)) as mycount from sample where uploaddatetime >= '$year-$month-1'::date and uploaddatetime < '$nextyear-$nextmonth-1'::date;");
			
			if($samplecount==""){
				$samplecount=0;
			}
			
			$totalcount = $totalcount + $samplecount;
			
			echo "$monthnames[$month],$year,$samplecount,$totalcount,$usercount\n";

		
		}
		
		$month++;
		
		if($month==13){
			$year++;
			$month=1;
		}
		
		if($year==$nowyear){
			$go="no";
		}
	
	}

















































}else{

include("includes/geochron-secondary-header.htm");
?>

<div style="padding-left:50px;">

	<h1>Geochron Download Stats</h1>

	<div style="padding-left:20px;padding-top:20px;">
		<h2>Yearly Stats</h2>
		<table border="1" cellpadding="3" cellspacing="1" bgcolor="#999999">
			<tr>
				<td bgcolor="#444444" nowrap><div style="color:#FFFFFF;">&nbsp;&nbsp;&nbsp;YEAR&nbsp;&nbsp;&nbsp;</div></td>
				<td bgcolor="#444444" nowrap><div style="color:#FFFFFF;">&nbsp;&nbsp;&nbsp;SAMPLE&nbsp;&nbsp;&nbsp;</div></td>
				<td bgcolor="#444444" nowrap><div style="color:#FFFFFF;">&nbsp;&nbsp;&nbsp;XLS&nbsp;&nbsp;&nbsp;</div></td>
				<td bgcolor="#444444" nowrap><div style="color:#FFFFFF;">&nbsp;&nbsp;&nbsp;TOTAL DOWNLOADS&nbsp;&nbsp;&nbsp;</div></td>
			</tr>
<?
foreach($yearrows as $yearrow){
?>
			<tr>
				<td bgcolor="#EEEEEE" align="center" nowrap>&nbsp;&nbsp;&nbsp;<?=$yearrow->year?>&nbsp;&nbsp;&nbsp;</td>
				<td bgcolor="#EEEEEE" align="center" nowrap>&nbsp;&nbsp;&nbsp;<?=$yearrow->singlesum?>&nbsp;&nbsp;&nbsp;</td>
				<td bgcolor="#EEEEEE" align="center" nowrap>&nbsp;&nbsp;&nbsp;<?=$yearrow->xlssum?>&nbsp;&nbsp;&nbsp;</td>
				<td bgcolor="#EEEEEE" align="center" nowrap>&nbsp;&nbsp;&nbsp;<?=$yearrow->singlesum+$yearrow->xlssum?>&nbsp;&nbsp;&nbsp;</td>
			</tr>
<?
}
?>
		</table>
	</div>

	<div style="padding-left:20px;padding-top:40px;">
		<h2>Monthly Stats</h2>
		<table border="0" cellpadding="3" cellspacing="1" bgcolor="#999999">
			<tr>
				<td bgcolor="#444444" nowrap><div style="color:#FFFFFF;">&nbsp;&nbsp;&nbsp;MONTH&nbsp;&nbsp;&nbsp;</div></td>
				<td bgcolor="#444444" nowrap><div style="color:#FFFFFF;">&nbsp;&nbsp;&nbsp;YEAR&nbsp;&nbsp;&nbsp;</div></td>
				<td bgcolor="#444444" nowrap><div style="color:#FFFFFF;">&nbsp;&nbsp;&nbsp;SAMPLE&nbsp;&nbsp;&nbsp;</div></td>
				<td bgcolor="#444444" nowrap><div style="color:#FFFFFF;">&nbsp;&nbsp;&nbsp;XLS&nbsp;&nbsp;&nbsp;</div></td>
				<td bgcolor="#444444" nowrap><div style="color:#FFFFFF;">&nbsp;&nbsp;&nbsp;TOTAL DOWNLOADS&nbsp;&nbsp;&nbsp;</div></td>
			</tr>
<?
foreach($monthrows as $monthrow){
	$month=$monthrow->month;
	//$showmonth = date("F", mktime(0, 0, 0, ($month)));
	$showmonth = $monthnames[$month];
?>
			<tr>
				<td bgcolor="#EEEEEE" nowrap>&nbsp;&nbsp;&nbsp;<?=$showmonth?>&nbsp;&nbsp;&nbsp;</td>
				<td bgcolor="#EEEEEE" nowrap>&nbsp;&nbsp;&nbsp;<?=$monthrow->year?>&nbsp;&nbsp;&nbsp;</td>
				<td bgcolor="#EEEEEE" align="center" nowrap>&nbsp;&nbsp;&nbsp;<?=$monthrow->singlesum?>&nbsp;&nbsp;&nbsp;</td>
				<td bgcolor="#EEEEEE" align="center" nowrap>&nbsp;&nbsp;&nbsp;<?=$monthrow->xlssum?>&nbsp;&nbsp;&nbsp;</td>
				<td bgcolor="#EEEEEE" align="center" nowrap>&nbsp;&nbsp;&nbsp;<?=$monthrow->singlesum+$monthrow->xlssum?>&nbsp;&nbsp;&nbsp;</td>
			</tr>
<?
}
?>
		</table>
	</div>




	<div style="padding-left:20px;padding-top:40px;">
		<h2>Sample Stats</h2>
		<table border="0" cellpadding="3" cellspacing="1" bgcolor="#999999">
			<tr>
				<td bgcolor="#444444" nowrap><div style="color:#FFFFFF;">&nbsp;&nbsp;&nbsp;MONTH&nbsp;&nbsp;&nbsp;</div></td>
				<td bgcolor="#444444" nowrap><div style="color:#FFFFFF;">&nbsp;&nbsp;&nbsp;YEAR&nbsp;&nbsp;&nbsp;</div></td>
				<td bgcolor="#444444" nowrap><div style="color:#FFFFFF;">&nbsp;&nbsp;&nbsp;NEW&nbsp;SAMPLES&nbsp;&nbsp;&nbsp;</div></td>
				<td bgcolor="#444444" nowrap><div style="color:#FFFFFF;">&nbsp;&nbsp;&nbsp;TOTAL&nbsp;SAMPLES&nbsp;&nbsp;&nbsp;</div></td>
				<td bgcolor="#444444" nowrap><div style="color:#FFFFFF;">&nbsp;&nbsp;&nbsp;ACTIVE&nbsp;USERS&nbsp;&nbsp;&nbsp;</div></td>
			</tr>






<?

	$year=2010;
	$month=4;

	$go="yes";
	
	$totalcount=0;
	
	while($go == "yes"){
		$nextmonth=$month+1;
		$nextyear=$year;
		if($nextmonth==13){
			$nextmonth=1;
			$nextyear++;
		}
		
		$thisdate = DateTime::createFromFormat('Y-n-j', $year."-".$month."-1");
		
		$nowdate = new DateTime("now");
		
		//$datediff = date_diff($thisdate, $nowdate);
		
		//$datediff = $datediff->days;
		
		if($thisdate > $nowdate){
			$go="no";
		}
		
		if($go=="yes"){
		
			
			$samplecount=$db->get_var("select count(*) as mycount from sample where uploaddatetime >= '$year-$month-1'::date and uploaddatetime < '$nextyear-$nextmonth-1'::date;");
			
			$usercount = $db->get_var("select count(distinct(userpkey)) as mycount from sample where uploaddatetime >= '$year-$month-1'::date and uploaddatetime < '$nextyear-$nextmonth-1'::date;");
			
			if($samplecount==""){
				$samplecount=0;
			}
			
			$totalcount = $totalcount + $samplecount;
			

			?>
			<tr>
				<td bgcolor="#EEEEEE" align="center" nowrap>&nbsp;&nbsp;&nbsp;<?=$monthnames[$month]?>&nbsp;&nbsp;&nbsp;</td>
				<td bgcolor="#EEEEEE" align="center" nowrap>&nbsp;&nbsp;&nbsp;<?=$year?>&nbsp;&nbsp;&nbsp;</td>
				<td bgcolor="#EEEEEE" align="center" nowrap>&nbsp;&nbsp;&nbsp;<?=$samplecount?>&nbsp;&nbsp;&nbsp;</td>
				<td bgcolor="#EEEEEE" align="center" nowrap>&nbsp;&nbsp;&nbsp;<?=$totalcount?>&nbsp;&nbsp;&nbsp;</td>
				<td bgcolor="#EEEEEE" align="center" nowrap>&nbsp;&nbsp;&nbsp;<?=$usercount?>&nbsp;&nbsp;&nbsp;</td>
			</tr>
			<?
		
		}
		
		$month++;
		
		if($month==13){
			$year++;
			$month=1;
		}
		
		if($year==$nowyear){
			$go="no";
		}
	
	}



?>


		</table>
	</div>


















</div>

<br><br><br><br><br><br><br><br><br><br><br><br>

<?

include("includes/geochron-secondary-footer.htm");
}//end if v=csv
?>