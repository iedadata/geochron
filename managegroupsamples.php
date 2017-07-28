<?PHP
/**
 * managegroupsamples.php
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
	
	$group_pkey=$_POST['group_pkey'];
	
	if($group_pkey==""){
		$group_pkey=9999;
	}
	
	$groupname=$db->get_var("select groupname from groups where group_pkey=$group_pkey");
	
	//delete from groupsamplerelate
	$db->query("delete from groupsamplerelate where group_pkey=$group_pkey 
				and sample_pkey in (select sample_pkey from sample where userpkey=$userpkey)");
	
	
	
	foreach($_POST as $key=>$value){
	
		$pos = strpos($key,"check");
		
		if($pos === false) {
		 // string needle NOT found in haystack
		}
		else {
		 	$key=str_replace("check","",$key);
		 	//echo "$key<br>";
		 	$db->query("insert into groupsamplerelate values (nextval('groupsamplerelate_pkey_seq'),$group_pkey,$key)");
		 	$sample_id=$db->get_var("select sample_id from sample where sample_pkey=$key");
		 	$samplelist.=$sample_id."<br>";
		}
	
	}

	include("includes/geochron-secondary-header.htm");

	?>
	
	<h1>Success!</h1><br>
	
	
	<?
	if($samplelist!=""){
	?>
	The following samples now belong to the group <?=$groupname?>:<br><br>
	<?=$samplelist?><br>
	<?
	}else{
	?>
	Group <?=$groupname?> has been updated.<br><br>
	<?
	}
	?>
	
	
	
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

$group_pkey=$_GET['group_pkey'];
















$sort=$_GET['sort'];

if($sort==""){
	$sort="sample_id";
}

if($group_pkey==""){
	$group_pkey=999999;
}

$grouprow=$db->get_row("select 
						grp.groupname,
						grp.group_pkey,
						grp.users_pkey,
						(select firstname||' '||lastname from users where users_pkey=grp.users_pkey) as ownername
						from groups grp
						left join grouprelate grprel on grp.group_pkey=grprel.group_pkey
						where grp.group_pkey=$group_pkey
						and (grp.users_pkey=$userpkey or grprel.users_pkey=$userpkey)");


if($userpkey == $grouprow->users_pkey){
	$mine="yes";
}else{
	$mine="no";
}



$groupname=$grouprow->groupname;

//get rows not owned by me
$otherrows=$db->get_results("select
					sample.sample_pkey,
					igsn,
					sample_id,
					material,
					uploaddate,
					(select count(*) from groupsamplerelate where sample_pkey=sample.sample_pkey and group_pkey=$group_pkey) as samplecount,
					(select lastname||', '||firstname from users where users_pkey=sample.userpkey) as ownername
					from sample
					left join groupsamplerelate on groupsamplerelate.sample_pkey = sample.sample_pkey
					where userpkey!=$userpkey
					and groupsamplerelate.group_pkey=$group_pkey
					order by $sort;");

$rows=$db->get_results("select
					sample_pkey,
					igsn,
					sample_id,
					material,
					uploaddate,
					(select count(*) from groupsamplerelate where sample_pkey=sample.sample_pkey and group_pkey=$group_pkey) as samplecount,
					(select lastname||', '||firstname from users where users_pkey=sample.userpkey) as ownername
					from sample
					where userpkey=$userpkey
					order by $sort;");

include("includes/geochron-secondary-header.htm");



?>

<h1>Manage Samples in Group '<?=$groupname?>'</h1>
<?
if($mine=="no"){
?>
<h1>Group Owner: <?=$grouprow->ownername?></h1>
<?
}
?>
<br>
Choose samples from the following list to include in group '<?=$groupname?>'.<br><br>

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

<input type="hidden" name="group_pkey" value="<?=$group_pkey?>">
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