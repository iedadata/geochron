<?PHP
/**
 * managedatasetsamples.php
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

session_start();

include("logincheck.php");


//check for post here
if($_POST['submit']!=""){
	
	$dataset_pkey=$_POST['dataset_pkey'];
	
	if($dataset_pkey==""){
		$dataset_pkey=9999;
	}
	
	$datasetname=$db->get_var("select datasetname from datasets where dataset_pkey=$dataset_pkey");
	
	//delete from groupsamplerelate
	$db->query("delete from datasetrelate where dataset_pkey=$dataset_pkey
				and sample_pkey in (select sample_pkey from sample where userpkey=$userpkey)");
	
	
	
	foreach($_POST as $key=>$value){
	
		$pos = strpos($key,"check");
		
		if($pos === false) {
		 // string needle NOT found in haystack
		}
		else {
		 	$key=str_replace("check","",$key);
		 	//echo "$key<br>";
		 	$db->query("insert into datasetrelate values (nextval('datasetrelate_seq'),$dataset_pkey,$key)");
		 	$sample_id=$db->get_var("select sample_id from sample where sample_pkey=$key");
		 	$samplelist.=$sample_id."<br>";
		}
	
	}
	
	include("makedatasetfile.php");
	
	include("includes/geochron-secondary-header.htm");

	?>
	
	<h1>Success!</h1><br>
	The following samples now belong to the dataset <?=$datasetname?>:<br><br>
	<?=$samplelist?><br>
	
	<INPUT TYPE="button" value="Continue" onClick="parent.location='managedata.php'">
	
	<br><br><br><br><br>
	<br><br><br><br><br>
	<br><br><br><br><br>
	<br><br><br><br><br>
	<br><br><br><br><br>
	
	<?
	
	include("includes/geochron-secondary-footer.htm");

	exit();
}








//$userpkey

$dataset_pkey=$_GET['dataset_pkey'];

$sort=$_GET['sort'];

if($sort==""){
	$sort="sample_id";
}

if($dataset_pkey==""){
	$dataset_pkey=999999;
}


$datasetrow=$db->get_row("select * from datasets ds
							left join datasetuserrelate dur on ds.dataset_pkey = dur.dataset_pkey
							where ds.dataset_pkey=$dataset_pkey 
							and (ds.users_pkey=$userpkey or (dur.users_pkey=$userpkey and dur.confirmed=TRUE))");



$datasetname=$datasetrow->datasetname;

$otherrows=$db->get_results("select
					samp.sample_pkey,
					igsn,
					sample_id,
					material,
					uploaddate,
					(select lastname||', '||firstname from users where users_pkey=samp.userpkey) as ownername,
					(select count(*) from datasetrelate where sample_pkey=samp.sample_pkey and dataset_pkey=$dataset_pkey) as samplecount
					from sample samp
					left join datasetrelate ds on samp.sample_pkey = ds.sample_pkey
					where userpkey!=$userpkey and ds.dataset_pkey=$dataset_pkey
					order by $sort;");

$rows=$db->get_results("select
					sample_pkey,
					igsn,
					sample_id,
					material,
					uploaddate,
					(select lastname||', '||firstname from users where users_pkey=sample.userpkey) as ownername,
					(select count(*) from datasetrelate where sample_pkey=sample.sample_pkey and dataset_pkey=$dataset_pkey) as samplecount
					from sample
					where userpkey=$userpkey
					order by $sort;");

include("includes/geochron-secondary-header.htm");



?>

<h1>Manage Samples in Dataset '<?=$datasetname?>'</h1><br>

Choose samples from the following list to include in dataset '<?=$datasetname?>'.<br><br>

<form method="POST">

<input type="submit" value="Submit" name="submit"><br>

<table class="aliquot" style="margin-top:15px;border-width:1px 1px 1px 1px;border-style:solid">
	<tr style="vertical-align:middle">
		<th style="vertical-align:middle;border-width:0 0 1px 0;width:25px;"></th>

		<? if($sort=="sample_id"){ $sortstring="sample_id+desc"; $sortchar = "down";}else{ $sortstring="sample_id"; $sortchar = "up";} ?>
		<th style="<?=$cellpadding?>;vertical-align:middle;border-width:0 0 1px 0"><a href="managegroupsamples.php?sort=<?=$sortstring?>&group_pkey=<?=$group_pkey?>">Sample ID</a></th>
		
		<? if($sort=="igsn"){ $sortstring="igsn+desc"; $sortchar = "down";}else{ $sortstring="igsn"; $sortchar = "up";} ?>
		<th style="<?=$cellpadding?>;vertical-align:middle;border-width:0 0 1px 0"><a href="managegroupsamples.php?sort=<?=$sortstring?>&group_pkey=<?=$group_pkey?>">Unique ID<a/></th>

		<? if($sort=="material"){ $sortstring="material+desc"; $sortchar = "down";}else{ $sortstring="material"; $sortchar = "up";} ?>
		<th style="<?=$cellpadding?>;vertical-align:middle;border-width:0 0 1px 0"><a href="managegroupsamples.php?sort=<?=$sortstring?>&group_pkey=<?=$group_pkey?>">Material</a></th>
		
		<? if($sort=="uploaddate"){ $sortstring="uploaddate+desc"; $sortchar = "down";}else{ $sortstring="uploaddate"; $sortchar = "up";} ?>
		<th style="<?=$cellpadding?>;vertical-align:middle;border-width:0 0 1px 0"><a href="managegroupsamples.php?sort=<?=$sortstring?>&group_pkey=<?=$group_pkey?>">Upload Date</a></th>

		<? if($sort=="ownername"){ $sortstring="ownername+desc"; $sortchar = "down";}else{ $sortstring="ownername"; $sortchar = "up";} ?>
		<th style="<?=$cellpadding?>;vertical-align:middle;border-width:0 0 1px 0"><a href="managegroupsamples.php?sort=<?=$sortstring?>&group_pkey=<?=$group_pkey?>">Sample Owner</a></th>

	</tr>

<?

$color="fce7e7";

foreach($otherrows as $row){

		$row->igsn=str_replace("SSR.","",$row->igsn);
		$row->igsn=str_replace("GCH.","",$row->igsn);

	?>
		<tr>
			<td style="background-color:#<?=$color?>;"></td>
			<td style="background-color:#<?=$color?>;"><?=$row->sample_id?></td>
			<td style="background-color:#<?=$color?>;"><?=$row->igsn?></td>
			<td style="background-color:#<?=$color?>;"><?=$row->material?></td>
			<td style="background-color:#<?=$color?>;"><?=$row->uploaddate?></td>
			<td style="background-color:#<?=$color?>;"><?=$row->ownername?></td>
		</tr>
	<?

}



foreach($rows as $row){

		$row->igsn=str_replace("SSR.","",$row->igsn);
		$row->igsn=str_replace("GCH.","",$row->igsn);

	?>
		<tr>
			<td><input name="check<?=$row->sample_pkey?>" type="checkbox"<? if($row->samplecount>0){echo " checked";} ?>></td>
			<td><?=$row->sample_id?></td>
			<td><?=$row->igsn?></td>
			<td><?=$row->material?></td>
			<td><?=$row->uploaddate?></td>
			<td><?=$row->ownername?></td>
		</tr>
	<?

}

?>


</table><br>

<input type="hidden" name="dataset_pkey" value="<?=$dataset_pkey?>">
<input type="submit" value="Submit" name="submit">

</form>































<br><br><br><br><br>
<br><br><br><br><br>
<br><br><br><br><br>
<br><br><br><br><br>
<br><br><br><br><br>




















<?

include("includes/geochron-secondary-footer.htm");
?>