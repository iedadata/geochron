<?PHP
/**
 * groupmapquery.php
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

//print_r($_SESSION);

//print_r($_GET);exit();

include("db.php");

include("logincheck.php");





$group_pkey=$_GET['group_pkey'];


$pkey=$_GET['pkey'];

$mylon=$_GET['mylon'];
$mylat=$_GET['mylat'];
$myzoom=$_GET['zoom'];

//echo "myzoom: $myzoom";

$myshowfig=$_GET['showfig'];

if($myshow=="conc" || $myshow=="prob"){
	$conc="yes";
}



if($_GET['sample_pkey']!=""){
	
	$sample_pkey=$_GET['sample_pkey'];
	
	$myrow=$db->get_row("select sample.sample_pkey, 
							sample.sample_id,
							sample.igsn,
							sample.laboratoryname, 
							sample.analyst_name,
							sample.ecproject,
							sample.latitude,
							sample.longitude,
							sample.userpkey,
							sample.material,
							detrital_type,
							oldest_frac_date,
							youngest_frac_date,
							age_min, age_max, age_value, one_sigma, age_name,
							getagetypes(sample.sample_pkey) as agetypes
							from sample 
							left join sample_age on sample.sample_pkey = sample_age.sample_pkey
							left join groupsamplerelate on sample.sample_pkey = groupsamplerelate.sample_pkey
							left join grouprelate on groupsamplerelate.group_pkey = grouprelate.group_pkey
							left join groups on grouprelate.group_pkey = groups.group_pkey
							where sample.sample_pkey=$sample_pkey
							and (sample.publ=1 or sample.userpkey=$userpkey or ((grouprelate.users_pkey=$userpkey and grouprelate.confirmed=true)) or groups.users_pkey=$userpkey)
							and grouprelate.group_pkey=$group_pkey
							group by
							sample.sample_pkey, 
							sample.sample_id,
							sample.igsn,
							sample.laboratoryname, 
							sample.analyst_name,
							sample.ecproject,
							sample.latitude,
							sample.longitude,
							sample.userpkey,
							sample.material,
							detrital_type,
							oldest_frac_date,
							youngest_frac_date,
							age_min, age_max, age_value, one_sigma, age_name,
							agetypes
							");
				
	//var_dump($myrow);

	if($myrow->ecproject=="redux"){
		$showdetritalmethod="U-Pb";
	}elseif($myrow->ecproject=="helios"){
		$showdetritalmethod="(U-Th)/He";
	}elseif($myrow->ecproject=="arar"){
		$showdetritalmethod="Ar-Ar";
	}elseif($myrow->ecproject=="squid"){
		$showdetritalmethod="SQUID";
	}

?>


	<table class="aboutpage" width="250" cellpadding="2" cellspacing="1" bgcolor="#333333">
  	<tr>
    	<td bgcolor="#333333">
		<table class="aboutpage" width="100%">
		<tr>
		<td><font color="#FFFFFF"><strong>Sample Details: </strong></font></td>
		<td><div align="right"><a href="javascript:show_my_form(<?=$mylat?>,<?=$mylon?>,'<?=$group_pkey?>',<?=$myzoom?>,'<?=$myshowfig?>');" ><font color="#FFFFFF"><strong>back</strong></font></a></div></td>
		<td>&nbsp;</td>
		</tr>
		</table>
		</td>
  	</tr>
  	<tr>
    	<td bgcolor="#FFFFFF">
      	<table class="aboutpage" width="250" cellspacing="1" cellpadding="2">

<?


if($myrow->upstream=="t"){
	if($myrow->sample_id!=""){?><tr><td>SampleID:</td><td><?=strtoupper($myrow->sample_id)?></td></tr><?}
	if($showdetritalmethod!=""){?><tr><td>Detrital Method:</td><td><?=$showdetritalmethod?></td></tr><?}
	if($showdetritalmethod!=""){?><tr><td>Detrital Mineral:</td><td><?=$myrow->material?></td></tr><?}
	if($myrow->detrital_type!=""){?><tr><td>Detrital Rock Type:</td><td><?=$myrow->detrital_type?></td></tr><?}
	if($myrow->age_min!=""){?><tr><td>Min. Strat Age:</td><td><?=$myrow->age_min?> Ma</td></tr><?}
	if($myrow->age_max!=""){?><tr><td>Max. Strat Age:</td><td><?=$myrow->age_max?> Ma</td></tr><?}
	if($myrow->strat_name!=""){?><tr><td>Stratigraphic<br>Formation Name:</td><td><?=$myrow->strat_name?></td></tr><?}
	if($myrow->oldest_frac_date!=""){?><tr><td>Oldest Frac. Date:</td><td><?=round($myrow->oldest_frac_date/1000000, 3)?> Ma</td></tr><?}
	if($myrow->youngest_frac_date!=""){?><tr><td>Youngest Frac. Date:</td><td><?=round($myrow->youngest_frac_date/1000000,3)?> Ma</td></tr><?}
}else{
	
	$showage="";
	$showonesigma="";
	
	if($myrow->ecproject=="redux"){
		if($myrow->age_value!=""){
			$thisage=$myrow->age_value/1000000;
			$thisonesigma=$myrow->one_sigma/1000000;
			$showage=sigorigval($thisage,$thisonesigma,4);
			$showonesigma=sigerrval($thisonesigma,4);
		}
	}else{
		$showage=$myrow->age_value;
		$showonesigma=$myrow->one_sigma;
	}

	if($myrow->sample_id!=""){?><tr><td>SampleID:</td><td><?=strtoupper($myrow->sample_id)?></td></tr><?}
	if($showdetritalmethod!=""){?><tr><td>Age Method:</td><td><?=$showdetritalmethod?></td></tr><?}
	if($myrow->material!=""){?><tr><td>Mineral:</td><td><?=$myrow->material?></td></tr><?}
	if($myrow->detrital_type!=""){?><tr><td>Age Type:</td><td><?=$myrow->age_name?></td></tr><?}
	if($showage!=""){?><tr><td>Age:</td><td><?=$showage?> Ma</td></tr><?}
	if($showonesigma!=""){?><tr><td nowrap>Age Error &plusmn;2&sigma;:</td><td><?=2*$showonesigma?> </td></tr><?}

}

if(file_exists("concordias/sidebar/$sample_pkey.jpg")||file_exists("probabilities/sidebar/$sample_pkey.jpg")){

?>
	<tr>
		<td colspan="2">


			<div id="concordiatab" style="padding:2px;
							float:left;
							font-size:10px;
							border:1px solid;
							color:#000000;
							background:#CCCCCC;">Concordia&nbsp;Diagram</div>

			<div id="probabilitytab" style="padding:2px;
							float:left;
							font-size:10px;
							border:1px solid;
							color:#3333CC;
							background:#FFFFFF;"><a style="color:#3333CC;" href="javascript:showprobability();">Probability&nbsp;Density</a></div>
			
			<div style="clear:left;"></div>

			<div id="" style="border:1px solid;">
				<div id="concordiadiv">
					<?
					if(file_exists("concordias/sidebar/$sample_pkey.jpg")){
					?>
					<a href="javascript:popconcordia('viewconcordia.php?sample_pkey=<?=$sample_pkey?>');" >
						<img src="concordias/sidebar/<?=$sample_pkey?>.jpg" border="0">
					</a>
					<?
					}else{
					?>
					<img src="noconcordia.jpg" border="0">
					<?
					}
					?>
				</div>
				<div id="probabilitydiv" style="display:none;">
					<?
					if(file_exists("probabilities/sidebar/$sample_pkey.jpg")){
					?>
					<a href="javascript:popconcordia('viewprobability.php?sample_pkey=<?=$sample_pkey?>');" >
						<img src="probabilities/sidebar/<?=$sample_pkey?>.jpg" border="0">
					</a>
					<?
					}else{
					?>
					<img src="noprobability.jpg" border="0">
					<?
					}
					?>
				</div>
			</div>


		</td>
	</tr>

<?
}//end if files exist
?>

<tr><td colspan="2"><div align="center"><a style="color:#3333CC;font-size:8pt;font-weight:bold; " href="viewfile.php?pkey=<?=$myrow->sample_pkey?>" target="_blank"><img src="/images/magglass.png" border="0"> VIEW SAMPLE DETAILS</a></div></td></tr>
<?



?>
        	
        	<?
        	/*
        	<tr>
          	<td>Detail Link:</td>
          	<td>
          	<INPUT TYPE="button" value="Details" onClick="window.open('viewfile.php?pkey=<?=$myrow->sample_pkey?>')">
          	<!---<a href="viewfile.php?pkey=<?=$myrow->sample_pkey?>" target="_blank">Click</a>--->
          	</td>
        	</tr>
        	*/
        	?>
        	
      	</table> </td>
  	</tr>
	</table>



<?














	


}else{ //get sample_pkey is not set, let's query based on lat/long
	



	
if($conc=="yes"){
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
}else{
	if($myzoom==0){$mymult=2.5;}
	if($myzoom==1){$mymult=1.25;}
	if($myzoom==2){$mymult=0.625;}
	if($myzoom==3){$mymult=0.3125;}
	if($myzoom==4){$mymult=0.15625;}
	if($myzoom==5){$mymult=0.078125;}
	if($myzoom==6){$mymult=0.0390625;}
	if($myzoom==7){$mymult=0.01953125;}
	if($myzoom==8){$mymult=0.009765625;}
	if($myzoom==9){$mymult=0.004882813;}
	if($myzoom==10){$mymult=0.002441406;}
	if($myzoom==11){$mymult=0.001220703;}
	if($myzoom==12){$mymult=0.000610352;}
	if($myzoom==13){$mymult=0.000305176;}
	if($myzoom==14){$mymult=0.000152588;}
	if($myzoom==15){$mymult=7.62939E-05;}
}


	$left=$mylon-$mymult;
	$right=$mylon+$mymult;
	$top=$mylat+$mymult;
	$bottom=$mylat-(2*$mymult);
	
	//ST_Contains(GeomFromText('Polygon((-124.31875 44.2875,-115.0375 46.3125,-114.75625 45.4125,-124.43125 42.99375,-124.31875 44.2875))'),loc.mypoint)
	
	$coordbox="$left $top,$right $top,$left $bottom,$right $bottom,$left $top";
	
	$mapstring="select 
				sample.sample_pkey,
				sample.sample_id
				from 
				sample
				left join groupsamplerelate gsr on gsr.sample_pkey = sample.sample_pkey
				left join grouprelate grp on grp.group_pkey = gsr.group_pkey
				left join groups on grp.group_pkey = groups.group_pkey
				where 
				(sample.publ=1 or sample.userpkey=$userpkey or ((grp.users_pkey=$userpkey and grp.confirmed=true)) or groups.users_pkey=$userpkey)
				and ST_Contains(ST_GeomFromText('Polygon(($coordbox))'), mypoint)
				and grp.group_pkey=$group_pkey
				and del=0 
				group by
				sample.sample_pkey,
				sample.sample_id";
	
	//echo nl2br($mapstring);exit();

	$myrows=$db->get_results("$mapstring");

	//exit();
	
	$numrows=$db->num_rows;
	
	//echo "numrows: $numrows";exit();
	
	//var_dump($myrows);
	//echo "num rows: ".$db->num_rows."<br><br>";
	
	//var_dump($myrows);

	
	if($numrows==0){
	?>
		<table class="aboutpage" width="250" cellpadding="2" cellspacing="1" bgcolor="#333333">
  		<tr>
    		<td bgcolor="#333333"><font color="#FFFFFF"><strong>No Samples Found: </strong></font></td>
  		</tr>
  		<tr>
    		<td bgcolor="#FFFFFF"> No samples selected. Please click on another point or
      		zoom in and click.
    		</td>
  		</tr>
		</table>
		<br>

	
	<?
	}//end if rows = 0
	
	
	if($numrows==1){
	
	//var_dump($myrows);
	
	
	
	$sample_pkey=$myrows[0]->sample_pkey;



				
	$myrow=$db->get_row("select sample.sample_pkey, 
							sample.sample_id,
							sample.igsn,
							sample.laboratoryname, 
							sample.analyst_name,
							sample.ecproject,
							sample.latitude,
							sample.longitude,
							sample.userpkey,
							sample.material,
							detrital_type,
							oldest_frac_date,
							youngest_frac_date,
							age_min, age_max, age_value, one_sigma, age_name,
							getagetypes(sample.sample_pkey) as agetypes,
							(select lastname||' '||firstname from users where users_pkey=sample.userpkey) as owner
							from sample
							left join sample_age on sample.sample_pkey = sample_age.sample_pkey
							left join groupsamplerelate on sample.sample_pkey = groupsamplerelate.sample_pkey
							left join grouprelate on groupsamplerelate.group_pkey = grouprelate.group_pkey
							left join groups on grouprelate.group_pkey = groups.group_pkey
							where sample.sample_pkey=$sample_pkey
							and (sample.publ=1 or sample.userpkey=$userpkey or ((grouprelate.users_pkey=$userpkey and grouprelate.confirmed=true)) or groups.users_pkey=$userpkey)
							and grouprelate.group_pkey=$group_pkey
							group by
							sample.sample_pkey, 
							sample.sample_id,
							sample.igsn,
							sample.laboratoryname, 
							sample.analyst_name,
							sample.ecproject,
							sample.latitude,
							sample.longitude,
							sample.userpkey,
							sample.material,
							detrital_type,
							oldest_frac_date,
							youngest_frac_date,
							age_min, age_max, age_value, one_sigma, age_name,
							agetypes");
							
	//exit();
						
	if($myrow->ecproject=="redux"){
		$showdetritalmethod="U-Pb";
	}elseif($myrow->ecproject=="helios"){
		$showdetritalmethod="(U-Th)/He";
	}elseif($myrow->ecproject=="arar"){
		$showdetritalmethod="Ar-Ar";
	}elseif($myrow->ecproject=="squid"){
		$showdetritalmethod="SQUID";
	}
				
	//var_dump($myrow);



?>


			<table class="aboutpage" width="250" cellpadding="2" cellspacing="1" bgcolor="#333333">
			<tr>
				<td bgcolor="#333333">
				<table class="aboutpage" width="100%">
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
				<table class="aboutpage" width="250" cellspacing="1" cellpadding="2">		
		<?




if($myrow->upstream=="t"){
	if($myrow->sample_id!=""){?><tr><td>SampleID:</td><td><?=strtoupper($myrow->sample_id)?></td></tr><?}
	if($showdetritalmethod!=""){?><tr><td>Detrital Method:</td><td><?=$showdetritalmethod?></td></tr><?}
	if($showdetritalmethod!=""){?><tr><td>Detrital Mineral:</td><td><?=$myrow->material?></td></tr><?}
	if($myrow->detrital_type!=""){?><tr><td>Detrital Rock Type:</td><td><?=$myrow->detrital_type?></td></tr><?}
	if($myrow->age_min!=""){?><tr><td>Min. Strat Age:</td><td><?=$myrow->age_min?> Ma</td></tr><?}
	if($myrow->age_max!=""){?><tr><td>Max. Strat Age:</td><td><?=$myrow->age_max?> Ma</td></tr><?}
	if($myrow->strat_name!=""){?><tr><td>Stratigraphic<br>Formation Name:</td><td><?=$myrow->strat_name?></td></tr><?}
	if($myrow->oldest_frac_date!=""){?><tr><td>Oldest Frac. Date:</td><td><?=round($myrow->oldest_frac_date/1000000, 3)?> Ma</td></tr><?}
	if($myrow->youngest_frac_date!=""){?><tr><td>Youngest Frac. Date:</td><td><?=round($myrow->youngest_frac_date/1000000,3)?> Ma</td></tr><?}
}else{
	
	$showage="";
	$showonesigma="";
	
	if($myrow->ecproject=="redux"){
		if($myrow->age_value!=""){
			$thisage=$myrow->age_value/1000000;
			$thisonesigma=$myrow->one_sigma/1000000;
			$showage=sigorigval($thisage,$thisonesigma,4);
			$showonesigma=sigerrval($thisonesigma,4);
		}
	}else{
		$showage=$myrow->age_value;
		$showonesigma=$myrow->one_sigma;
	}

	if($myrow->sample_id!=""){?><tr><td>SampleID:</td><td><?=strtoupper($myrow->sample_id)?></td></tr><?}
	if($showdetritalmethod!=""){?><tr><td>Age Method:</td><td><?=$showdetritalmethod?></td></tr><?}
	if($myrow->material!=""){?><tr><td>Mineral:</td><td><?=$myrow->material?></td></tr><?}
	if($myrow->detrital_type!=""){?><tr><td>Age Type:</td><td><?=$myrow->age_name?></td></tr><?}
	if($showage!=""){?><tr><td>Age:</td><td><?=$showage?> Ma</td></tr><?}
	if($showonesigma!=""){?><tr><td nowrap>Age Error &plusmn;2&sigma;:</td><td><?=2*$showonesigma?> </td></tr><?}

}

if(file_exists("concordias/sidebar/$sample_pkey.jpg")||file_exists("probabilities/sidebar/$sample_pkey.jpg")){
?>

	<tr>
		<td colspan="2">


			<div id="concordiatab" style="padding:2px;
							float:left;
							font-size:10px;
							border:1px solid;
							color:#000000;
							background:#CCCCCC;">Concordia&nbsp;Diagram</div>

			<div id="probabilitytab" style="padding:2px;
							float:left;
							font-size:10px;
							border:1px solid;
							color:#3333CC;
							background:#FFFFFF;"><a style="color:#3333CC;" href="javascript:showprobability();">Probability&nbsp;Density</a></div>
			
			<div style="clear:left;"></div>

			<div id="" style="border:1px solid;">
				<div id="concordiadiv">
					<?
					if(file_exists("concordias/sidebar/$sample_pkey.jpg")){
					?>
					<a href="javascript:popconcordia('viewconcordia.php?sample_pkey=<?=$sample_pkey?>');" >
						<img src="concordias/sidebar/<?=$sample_pkey?>.jpg" border="0">
					</a>
					<?
					}else{
					?>
					<img src="noconcordia.jpg" border="0">
					<?
					}
					?>
				</div>
				<div id="probabilitydiv" style="display:none;">
					<?
					if(file_exists("probabilities/sidebar/$sample_pkey.jpg")){
					?>
					<a href="javascript:popconcordia('viewprobability.php?sample_pkey=<?=$sample_pkey?>');" >
						<img src="probabilities/sidebar/<?=$sample_pkey?>.jpg" border="0">
					</a>
					<?
					}else{
					?>
					<img src="noprobability.jpg" border="0">
					<?
					}
					?>
				</div>
			</div>

		</td>
	</tr>
<?
}//end if files exist
?>

<tr><td colspan="2"><div align="center"><a style="color:#3333CC;font-size:8pt;font-weight:bold; " href="viewfile.php?pkey=<?=$myrow->sample_pkey?>" target="_blank"><img src="/images/magglass.png" border="0"> VIEW SAMPLE DETAILS</a></div></td></tr>
<?

		
		
		?>
					<?
					/*
					<tr>
					<td>Detail Link:</td>
					<td>
					<INPUT TYPE="button" value="Details" onClick="window.open('viewfile.php?pkey=<?=$myrow->sample_pkey?>')">
					<!---<a href="viewfile.php?pkey=<?=$myrow->sample_pkey?>" target="_blank">Click</a>--->
					</td>
					</tr>
					*/
					?>
				</table> </td>
			</tr>
			</table>

		<?


	}//end if rows = 1
	
	
	if($numrows>1){
	
?>
	
	
		<table class="aboutpage" width="250" cellpadding="2" cellspacing="1" bgcolor="#333333">
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
      		<table class="aboutpage" width="250" cellspacing="1" cellpadding="2">
        		

				<? foreach($myrows as $myrow){ ?>
				<tr>
          		<td><?=strtoupper($myrow->sample_id)?></td>
          		<td><a href="javascript:show_detail(<?=$myrow->sample_pkey?>,<?=$mylat?>,<?=$mylon?>,<?=$group_pkey?>,<?=$myzoom?>,'<?=$myshowfig?>');" >detail</a></td>
        		</tr>
        		<? } //end for each row ?>

				
				
				
				
      		</table> </td>
  		</tr>
		</table>	
	
	
	



<?
	}//end if rows > 1




}//end if sample_pkey is set

/*

if($conc=="yes"){
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
}else{
	if($myzoom==0){$mymult=1.5;}
	if($myzoom==1){$mymult=.8;}
	if($myzoom==2){$mymult=.4;}
	if($myzoom==3){$mymult=.2;}
	if($myzoom==4){$mymult=.07;}
	if($myzoom==5){$mymult=.05;}
	if($myzoom==6){$mymult=.025;}
	if($myzoom==7){$mymult=.0125;}
	if($myzoom==8){$mymult=.00625;}
	if($myzoom==9){$mymult=.003125;}
	if($myzoom==10){$mymult=.0015625;}
	if($myzoom==11){$mymult=.00078125;}
	if($myzoom==12){$mymult=.000390625;}
	if($myzoom==13){$mymult=.0001953125;}
	if($myzoom==14){$mymult=.0001953125;}
	if($myzoom==15){$mymult=.0001953125;}
}





























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
*/

?>