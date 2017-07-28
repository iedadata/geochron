<?PHP
/**
 * mapquery.php
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
include("db.php");




$pkey=$_GET['pkey'];

$mylon=$_GET['mylon'];
$mylat=$_GET['mylat'];
$myzoom=$_GET['zoom'];

//echo "$mylon &nbsp;&nbsp;&nbsp; $mylat<br><br>";

/*
if($_GET['navdat']!=""){$navdat=$_GET['navdat'];}else{$navdat=$_POST['navdat'];}
if($_GET['petdb']!=""){$petdb=$_GET['petdb'];}else{$petdb=$_POST['petdb'];}
if($_GET['georoc']!=""){$georoc=$_GET['georoc'];}else{$georoc=$_POST['georoc'];}
if($_GET['usgs']!=""){$usgs=$_GET['usgs'];}else{$usgs=$_POST['usgs'];}
*/

/*
echo "navdat: $navdat<br>";
echo "petdb: $petdb<br>";
echo "georoc: $georoc<br>";
echo "usgs: $usgs<br>";
*/



if($_GET['sample_pkey']!=""){
	
	
	$sample_pkey=$_GET['sample_pkey'];
	
	$myrow=$db->get_row("SELECT * from sample where sample_pkey=$sample_pkey");
				
	//var_dump($myrow);



?>


	<table width="250" cellpadding="2" cellspacing="1" bgcolor="#333333">
  	<tr>
    	<td bgcolor="#333333">
		<table width="100%">
		<tr>
		<td><font color="#FFFFFF"><strong>Sample Details: </strong></font></td>
		<td><div align="right"><a href="javascript:show_my_form(<?=$mylat?>,<?=$mylon?>,<?=$pkey?>,<?=$myzoom?>);" ><font color="#FFFFFF"><strong>back</strong></font></a></div></td>
		<td>&nbsp;</td>
		</tr>
		</table>
		</td>
  	</tr>
  	<tr>
    	<td bgcolor="#FFFFFF">
      	<table width="250" cellspacing="1" cellpadding="2">

<?



if($myrow->ecproject!=""){?><tr><td>Project:</td><td><?=strtoupper($myrow->ecproject)?></td></tr><?}
if($myrow->igsn!=""){?><tr><td>Unique ID:</td><td><?=$myrow->igsn?></td></tr><?}
if($myrow->parentigsn!=""){?><tr><td>Parent IGSN:</td><td><?=$myrow->parentigsn?></td></tr><?}
if($myrow->sample_id!=""){?><tr><td>Sample ID:</td><td><?=$myrow->sample_id?></td></tr><?}
if($myrow->sample_description!=""){?><tr><td>Sample Desc.:</td><td><?=$myrow->sample_description?></td></tr><?}
if($myrow->geoobjecttype!=""){?><tr><td>GeoObject Type:</td><td><?=$myrow->geoobjecttype?></td></tr><?}
if($myrow->geoobjectclass!=""){?><tr><td>GeoObject Class:</td><td><?=$myrow->geoobjectclass?></td></tr><?}
if($myrow->collectionmethod!=""){?><tr><td>Collection Method:</td><td><?=$myrow->collectionmethod?></td></tr><?}
if($myrow->longitude!=""){?><tr><td>Longitude:</td><td><?=$myrow->longitude?></td></tr><?}
if($myrow->latitude!=""){?><tr><td>Latitude:</td><td><?=$myrow->latitude?></td></tr><?}
if($myrow->sample_comment!=""){?><tr><td>Sample Comment:</td><td><?=$myrow->sample_comment?></td></tr><?}
if($myrow->analyst_name!=""){?><tr><td>Analyst Name:</td><td><?=$myrow->analyst_name?></td></tr><?}
if($myrow->laboratoryname!=""){?><tr><td>Lab Name:</td><td><?=$myrow->laboratoryname?></td></tr><?}



?>
        	<tr>
          	<td>Detail Link:</td>
          	<td>
          	<INPUT TYPE="button" value="Details" onClick="window.open('viewfile.php?pkey=<?=$myrow->sample_pkey?>')">
          	<!---<a href="viewfile.php?pkey=<?=$myrow->sample_pkey?>" target="_blank">Click</a>--->
          	</td>
        	</tr>
      	</table> </td>
  	</tr>
	</table>



<?














	


}else{ //get sample_pkey is not set, let's query based on lat/long
	
	
	
	$mapstring=$db->get_var("select querystring from search_query where search_query_pkey=$pkey");
	
	
	
	
	if($myzoom==0){$mymult=3;}
	if($myzoom==1){$mymult=2;}
	if($myzoom==2){$mymult=1.5;}
	if($myzoom==3){$mymult=.8;}
	if($myzoom==4){$mymult=.4;}
	if($myzoom==5){$mymult=.2;}
	if($myzoom==6){$mymult=.07;}
	if($myzoom==7){$mymult=.05;}
	if($myzoom==8){$mymult=.025;}
	if($myzoom==9){$mymult=.0125;}
	if($myzoom==10){$mymult=.00625;}
	if($myzoom==11){$mymult=.003125;}
	if($myzoom==12){$mymult=.0015625;}
	if($myzoom==13){$mymult=.00078125;}
	if($myzoom==14){$mymult=.000390625;}
	if($myzoom==15){$mymult=.0001953125;}


	//$mymult=1;
	
	//$mymult=1-((66.6*$myzoom)/1000);
	//$mymult=66.6*$myzoom;
	//$mymult=$mymult/1000;
	//$mymult=1-$mymult;
	
	//echo "$mymult<br>";
	
	$left=$mylon-$mymult;
	$right=$mylon+$mymult;
	$top=$mylat+$mymult;
	$bottom=$mylat-$mymult;
	
	//ST_Contains(GeomFromText('Polygon((-124.31875 44.2875,-115.0375 46.3125,-114.75625 45.4125,-124.43125 42.99375,-124.31875 44.2875))'),loc.mypoint)
	
	$coordbox="$left $top,$right $top,$left $bottom,$right $bottom,$left $top";
	
	//$mapstring.=" and ST_Contains(GeomFromText('Polygon(($coordbox))'),crd.mypoint)";
	
	$mapstring.=" and GeomFromText('Polygon(($coordbox))') ~ mypoint limit 25";
	
	include("buildquery.php");
	
	//echo "$mapstring";
	
	//$myrows=$db->get_results("$mapstring");


	//echo nl2br($mapquerystring);
	
	$myrows=$db->get_results("$mapquerystring");

	
	$numrows=$db->num_rows;
	
	//var_dump($myrows);
	//echo "num rows: ".$db->num_rows."<br><br>";
	
	//var_dump($myrows);

	
	if($numrows==0){
	?>
		<table width="250" cellpadding="2" cellspacing="1" bgcolor="#333333">
  		<tr>
    		<td bgcolor="#333333"><font color="#FFFFFF"><strong>No Samples Found: </strong></font></td>
  		</tr>
  		<tr>
    		<td bgcolor="#FFFFFF"> No samples found. Please click on another point or
      		zoom in to a more detailed view.
    		</td>
  		</tr>
		</table>
		<br>

	
	<?
	}//end if rows = 0
	
	
	if($numrows==1){
	
	//echo "only one row";
	
	//var_dump($myrows);
	
	
	
	$sample_pkey=$myrows[0]->sample_pkey;






	$myrow=$db->get_row("SELECT * from sample where sample_pkey=$sample_pkey");
				
	//var_dump($myrow);



?>


			<table width="250" cellpadding="2" cellspacing="1" bgcolor="#333333">
			<tr>
				<td bgcolor="#333333">
				<table width="100%">
				<tr>
				<td><font color="#FFFFFF"><strong>Sample Details: </strong></font></td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				</tr>
				</table>
				</td>
			</tr>
			<tr>
				<td bgcolor="#FFFFFF">
				<table width="250" cellspacing="1" cellpadding="2">		
		<?


if($myrow->ecproject!=""){?><tr><td>Project:</td><td><?=strtoupper($myrow->ecproject)?></td></tr><?}
if($myrow->igsn!=""){?><tr><td>Unique ID:</td><td><?=$myrow->igsn?></td></tr><?}
if($myrow->parentigsn!=""){?><tr><td>Parent IGSN:</td><td><?=$myrow->parentigsn?></td></tr><?}
if($myrow->sample_id!=""){?><tr><td>Sample ID:</td><td><?=$myrow->sample_id?></td></tr><?}
if($myrow->sample_description!=""){?><tr><td>Sample Desc.:</td><td><?=$myrow->sample_description?></td></tr><?}
if($myrow->geoobjecttype!=""){?><tr><td>GeoObject Type:</td><td><?=$myrow->geoobjecttype?></td></tr><?}
if($myrow->geoobjectclass!=""){?><tr><td>GeoObject Class:</td><td><?=$myrow->geoobjectclass?></td></tr><?}
if($myrow->collectionmethod!=""){?><tr><td>Collection Method:</td><td><?=$myrow->collectionmethod?></td></tr><?}
if($myrow->longitude!=""){?><tr><td>Longitude:</td><td><?=$myrow->longitude?></td></tr><?}
if($myrow->latitude!=""){?><tr><td>Latitude:</td><td><?=$myrow->latitude?></td></tr><?}
if($myrow->sample_comment!=""){?><tr><td>Sample Comment:</td><td><?=$myrow->sample_comment?></td></tr><?}
if($myrow->analyst_name!=""){?><tr><td>Analyst Name:</td><td><?=$myrow->analyst_name?></td></tr><?}
if($myrow->laboratoryname!=""){?><tr><td>Lab Name:</td><td><?=$myrow->laboratoryname?></td></tr><?}
		
		
		
		?>
					<tr>
					<td>Detail Link:</td>
					<td>
					<INPUT TYPE="button" value="Details" onClick="window.open('viewfile.php?pkey=<?=$myrow->sample_pkey?>')">
					<!---<a href="viewfile.php?pkey=<?=$myrow->sample_pkey?>" target="_blank">Click</a>--->
					</td>
					</tr>
				</table> </td>
			</tr>
			</table>

		<?


	}//end if rows = 1
	
	
	if($numrows>1){
	
?>
	
	
		<table width="250" cellpadding="2" cellspacing="1" bgcolor="#333333">
  		<tr>
    		<td bgcolor="#333333"><font color="#FFFFFF"><strong>Samples Found: </strong></font></td>
  		</tr>
  		<tr>
    		<td bgcolor="#FFFFFF">
			<?if($numrows==25){?>
			More than 25 samples found. Here are the first 25:<br>
			<?}else{?>
			More than one sample found:<br>
			<?}?>
      		<table width="250" cellspacing="1" cellpadding="2">
        		

				<? foreach($myrows as $myrow){ ?>
				<tr>
          		<td><?=$myrow->sample_id?></td>
          		<td><?=strtoupper($myrow->ecproject)?></td>
          		<td><a href="javascript:show_detail(<?=$myrow->sample_pkey?>,<?=$mylat?>,<?=$mylon?>,<?=$pkey?>,<?=$myzoom?>);" >detail</a></td>
        		</tr>
        		<? } //end for each row ?>

				
				
				
				
      		</table> </td>
  		</tr>
		</table>	
	
	
	



<?
	}//end if rows > 1




}//end if sample_pkey is set



?>