<?PHP
/**
 * viewgroup.php
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

include("logincheck.php");



include("db.php");

$group_pkey=$_GET['group_pkey'];

$script_name=$_SERVER['SCRIPT_NAME']."?group_pkey=$group_pkey";


/*
$grouprow=$db->get_row("select
						groups.group_pkey,
						groups.users_pkey,
						groups.groupname
						from groups 
						left join grouprelate on groups.group_pkey = grouprelate.group_pkey
						where groups.group_pkey=$group_pkey and grouprelate.users_pkey=$userpkey");
*/

$grouprow=$db->get_row("select
						grp.group_pkey,
						grp.users_pkey,
						grp.groupname
						from groups grp
						left join grouprelate grprel on grp.group_pkey = grprel.group_pkey
						where grp.users_pkey=$userpkey 
						or (grprel.users_pkey=$userpkey and grprel.confirmed=true)
						and grp.group_pkey=$group_pkey
						");




if($grouprow->group_pkey==""){
	echo "Error. Group not found.";exit();
}

include("includes/geochron-secondary-header.htm");

$groupname=$grouprow->groupname;

if($userpkey == $grouprow->users_pkey){
	$mine="yes";
}else{
	$mine="no";
}

$sort=$_GET['sort'];
if($sort!=""){
	$sortstring="&sort=$sort";
	$urlsortstring="&sort=$sort";
	$sortsql=" order by $sort ";
}else{
	$sortsql=" order by sample_pkey desc ";
}

//echo "mine: $mine";
?>

<script type="text/javascript">
var newwindow;
function popwindow(url)
{
	newwindow=window.open(url,'name','height=600,width=800,scrollbars=1');
	if (window.focus) {newwindow.focus()}
}
</script>





<?
//show existing users
$users=$db->get_results("select
						grouprelate_pkey,
						confirmed,
						(select email from users where users_pkey=grouprelate.users_pkey) as email
						from grouprelate where group_pkey=$group_pkey");

$ownername=$db->get_var("select firstname||' '||lastname from users where users_pkey=
						(select users_pkey from groups where group_pkey=$group_pkey)");

if(count($users)>0){

	?>
	<h1>Group Name: <?=$groupname?></h1>
<?
if($mine=="no"){
?>
	<h1>Group Owner: <?=$ownername?></h1>
<?
}
?>
	<br>
	<h1>Existing Users:</h1>

	<table class="aliquot" style="margin-top:5px;border-width:1px 1px 1px 1px;border-style:solid">
		<tr style="vertical-align:middle">
			<th style="vertical-align:middle;border-width:0 0 1px 0;width:25px;">email</th>
			<th style="vertical-align:middle;border-width:0 0 1px 0;width:25px;">active</th>
<?
if($mine=="yes"){
?>
			<th style="vertical-align:middle;border-width:0 0 1px 0">&nbsp;</th>
<?
}
?>
		</tr>
	
	<?
	foreach($users as $row){
	?>

		<tr>
			<td><?=$row->email?></td>
			<td><? if($row->confirmed=="t"){echo "YES";}else{echo "NO";}?></td>
<?
if($mine=="yes"){
?>
			<td><a href="deleteuser.php?pkey=<?=$row->grouprelate_pkey?>&pp=<?=$p?>&p=iu&g=<?=$group_pkey?>" OnClick="return confirm('Are you sure you want to delete <?=$row->email?>?')">DELETE</a></td>
<?
}
?>
		</tr>

	<?
	}
	?>

	</table><br><br>
	<?
	
}else{

?>

<h1>No Users for Group '<?=$groupname?>'.</h1><br>

<?


}


if($mine=="yes"){
?>
<INPUT TYPE="button" value="Invite Users" onClick="parent.location='inviteusers.php?group_pkey=<?=$group_pkey?>&p=vg'">
<?
}
?>

<hr><br>


<?










































	$thissql="select samp.*,
				getgroups(samp.sample_pkey) as groups,
				getdatasets(samp.sample_pkey) as datasets,
				(select lastname||', '||firstname from users where users_pkey=samp.userpkey) as ownername
				from
				sample samp
				left join groupsamplerelate gsr on gsr.sample_pkey = samp.sample_pkey
				left join grouprelate grp on grp.group_pkey = gsr.group_pkey
				where
				grp.group_pkey=$group_pkey 
				and del=0 $sortsql";

	//echo nl2br($thissql);exit();


	$myrows=$db->get_results("$thissql");
								
?>

  <form name="myform" action="<?=$script_name?>" method="post">
  <?
  $cellpadding="padding:3px 7px 3px 7px;";
  
  //echo "sort:$sort";
  
  ?>
  <!-- little extra left and right padding for the columns containing data -->
  <table class="aliquot" style="width:100%;margin-top:15px;border-width:1px 1px 1px 1px;border-style:solid">
    <tr style="vertical-align:middle">
      
      <? if($sort=="public"){ $sortstring="public+desc"; $sortchar = "down";}else{ $sortstring="public"; $sortchar = "up";} ?>
      <th style="vertical-align:middle;border-width:0 0 1px 0"><a href="<?=$script_name?>&sort=<?=$sortstring?>">public?</a></th>
 
 <?
 /*
      <? if($sort=="groups"){ $sortstring="groups+desc"; $sortchar = "down";}else{ $sortstring="groups"; $sortchar = "up";} ?>
      <th style="<?=$cellpadding?>;vertical-align:middle;border-width:0 0 1px 0"><a href="<?=$script_name?>&sort=<?=$sortstring?>">groups</a></th>
      
      <? if($sort=="datasets"){ $sortstring="datasets+desc"; $sortchar = "down";}else{ $sortstring="datasets"; $sortchar = "up";} ?>
      <th style="<?=$cellpadding?>;vertical-align:middle;border-width:0 0 1px 0"><a href="<?=$script_name?>&sort=<?=$sortstring?>">datasets</a></th>
*/
?>
      <? if($sort=="sample_id"){ $sortstring="sample_id+desc"; $sortchar = "down";}else{ $sortstring="sample_id"; $sortchar = "up";} ?>
      <th style="<?=$cellpadding?>;vertical-align:middle;border-width:0 0 1px 0"><a href="<?=$script_name?>&sort=<?=$sortstring?>">Sample ID</a></th>
      
      <? if($sort=="material"){ $sortstring="material+desc"; $sortchar = "down";}else{ $sortstring="material"; $sortchar = "up";} ?>
      <th style="<?=$cellpadding?>;vertical-align:middle;border-width:0 0 1px 0"><a href="<?=$script_name?>&sort=<?=$sortstring?>">Material</a></th>
	  
	  <? if($sort=="igsn"){ $sortstring="igsn+desc"; $sortchar = "down";}else{ $sortstring="igsn"; $sortchar = "up";} ?>
	  <th style="<?=$cellpadding?>;vertical-align:middle;border-width:0 0 1px 0"><a href="<?=$script_name?>&sort=<?=$sortstring?>">Unique ID<a/></th>

	  <? if($sort=="ownername"){ $sortstring="ownername+desc"; $sortchar = "down";}else{ $sortstring="ownername"; $sortchar = "up";} ?>
	  <th style="<?=$cellpadding?>;vertical-align:middle;border-width:0 0 1px 0"><a href="<?=$script_name?>&sort=<?=$sortstring?>">Owner<a/></th>
      
      <? if($sort=="uploaddate"){ $sortstring="uploaddate+desc"; $sortchar = "down";}else{ $sortstring="uploaddate"; $sortchar = "up";} ?>
      <th style="<?=$cellpadding?>;vertical-align:middle;border-width:0 0 1px 0"><a href="<?=$script_name?>&sort=<?=$sortstring?>">Upload Date</a></th>
      
      <th style="vertical-align:middle;border-width:0 0 1px 0" colspan=2></th>
    </tr>
    <?
    


    $samplelist="";
    $sampledelim="";
    $linecount=0;
    $linestyle='';
    foreach($myrows as $row){

		$row->igsn=str_replace("SSR.","",$row->igsn);
		$row->igsn=str_replace("GCH.","",$row->igsn);

    	$datasets=str_replace(";;;","<br>",$row->datasets);
    	$groups=str_replace(";;;","<br>",$row->groups);

    	if($row->userpkey==$userpkey){
    		$mine="yes";
    		$minestyle="";
    	}else{
    		$mine="no";
    		$minestyle="background-color:#fce7e7;";
    		$datasets="";
    	}
    


    	
    	//fix uploaddate here
    	$dateparts=$row->uploaddate;
    	$dateparts=explode(" ",$dateparts);
    	$datepart=$dateparts[0];
    	$timepart=$dateparts[1];
    	$datecode=$dateparts[2];
    	
    	$timepart=substr($timepart,0,5);
    	
    	$showdate="$datepart $timepart $datecode";
    	
      $linecount++;
      if($linecount==3){
        $linestyle="border-width:0px 0px 1px 0px;$minestyle";
        $linecount=0;
      }else{
        $linestyle="border-style:none;$minestyle";
      }
    ?>
      <tr  style="vertical-align:middle;text-align:left;">
        <td style="<?=$linestyle?>"><div align="center">
<?

	if($row->publ==1){
		echo "Yes";
	}else{
		echo "No";
	}

?>
          </div></td>

 <?
 /*
 		<td style=" <?=$cellpadding?>;vertical-align:middle;text-align:left;<?=$linestyle?>;color:black;white-space:nowrap"><div style="font-size:.9em;"><?=$groups?></dev></td>
        <td style=" <?=$cellpadding?>;vertical-align:middle;text-align:left;<?=$linestyle?>;color:black;white-space:nowrap"><div style="font-size:.9em;"><?=$datasets?></div></td>
 */
 ?>
 		<td style=" <?=$cellpadding?>;vertical-align:middle;text-align:left;<?=$linestyle?>;color:black;white-space:nowrap"><?=$row->sample_id?></td>
        <td style=" <?=$cellpadding?>;vertical-align:middle;text-align:left;<?=$linestyle?>;color:black;white-space:nowrap"><?=$row->material?></td>
		<td style=" <?=$cellpadding?>;vertical-align:middle;text-align:left;<?=$linestyle?>;color:black;white-space:nowrap"><div class="aboutpage"><a href="javascript:popwindow('viewid.php?id=<?=$row->igsn?>');"><?=strtoupper($row->igsn)?></a></div></td>
		<td style=" <?=$cellpadding?>;vertical-align:middle;text-align:left;<?=$linestyle?>;color:black;white-space:nowrap"><div class="aboutpage"><?=$row->ownername?></div></td>
		<!--
        <td style=" <?=$cellpadding?>;vertical-align:middle;text-align:left;<?=$linestyle?>;color:black;white-space:nowrap"><a href="javascript:popwindow('viewsesar.php?igsn=<?=$row->parentigsn?>');"><?=$row->parentigsn?></a></td>
        
        <td style=" <?=$cellpadding?>;vertical-align:middle;text-align:left;<?=$linestyle?>;color:black;white-space:nowrap"><?=$row->laboratoryname?></td>
        <td style=" <?=$cellpadding?>;vertical-align:middle;text-align:left;<?=$linestyle?>;color:black;white-space:nowrap"><?=$row->analyst_name?></td>
        -->
        <td style=" <?=$cellpadding?>;vertical-align:middle;text-align:left;<?=$linestyle?>;color:black;white-space:nowrap"><?=$showdate?></td>
<?
/*
if($mine=="yes"){
?>
        <td style=" vertical-align:middle;text-align:left;<?=$linestyle?>;" ><div class="aboutpage"><a style="font-size:7pt; " href="deletesample.php?pkey=<?=$row->sample_pkey?>&page=<?=$page?>" OnClick="return confirm('Are you sure you want to delete <?=$row->igsn?>?')">DELETE</a></div></td>
<?
}else{
?>
		<td style=" vertical-align:middle;text-align:left;<?=$linestyle?>;" ><div class="aboutpage"></div></td>
<?
}
*/
?>
		<!--
        <td style=" vertical-align:middle;text-align:left;<?=$linestyle?>;"><div class="aboutpage"><a style="font-size:7pt; " href="javascript:popwindow('viewfile.php?pkey=<?=$row->sample_pkey?>');">VIEW</a></div></td>
		-->
		
        <td style=" vertical-align:middle;text-align:left;<?=$linestyle?>;"><div class="aboutpage"><a style="font-size:7pt; " href="viewfile.php?pkey=<?=$row->sample_pkey?>" target="_blank">VIEW</a></div></td>

        <td style=" vertical-align:middle;text-align:left;<?=$linestyle?>;"><div class="aboutpage"><a style="font-size:7pt; " href="downloadfile.php?pkey=<?=$row->sample_pkey?>">DOWNLOAD</a></div></td>
        
      </tr>
      <?
      $samplelist=$samplelist.$sampledelim.$row->sample_pkey;
      $sampledelim=";";
    }//end loop over aliquots

















      ?>

  </table>
















































































<?
exit();
//show existing samples
$samples=$db->get_results("select gr.groupsamplerelatepkey,
							samp.sample_id,
							samp.igsn
							from groupsamplerelate gr,
							sample samp
							where gr.sample_pkey = samp.sample_pkey
							and gr.group_pkey = $group_pkey
							order by groupsamplerelatepkey desc;");

if(count($samples)>0){

	?>
	
	<h1>Existing Samples in Group: <?=$groupname?></h1>

	<table class="aliquot" style="margin-top:15px;border-width:1px 1px 1px 1px;border-style:solid">
		<tr style="vertical-align:middle">
			<th style="vertical-align:middle;border-width:0 0 1px 0;width:25px;">sample id</th>
			<th style="vertical-align:middle;border-width:0 0 1px 0;width:25px;">igsn</th>
<?
if($mine=="yes"){
?>
			<th style="vertical-align:middle;border-width:0 0 1px 0">&nbsp;</th>
<?
}
?>
		</tr>
	
	<?
	foreach($samples as $row){
	?>

		<tr>
			<td><?=$row->sample_id?></td>
			<td><?=$row->igsn?></td>
<?
if($mine=="yes"){
?>
			<td><a href="deletegroupsample.php?pkey=<?=$row->groupsamplerelatepkey?>&g=<?=$group_pkey?>" OnClick="return confirm('Are you sure you want to remove <?=$row->sample_id?> from group <?=$groupname?>?')">REMOVE</a></td>
<?
}
?>
		</tr>

	<?
	}
	?>

	</table><br><br>


<?

































}else{

?>

<h1>No Samples for Group '<?=$groupname?>'.</h1><br>

<?


}

?>

<INPUT TYPE="button" value="Manage Samples" onClick="parent.location='managegroupsamples.php?group_pkey=<?=$group_pkey?>&p=vg'">

























<br><br><br><br><br>
<br><br><br><br><br>
<br><br><br><br><br>
<br><br><br><br><br>
<br><br><br><br><br>




















<?

include("includes/geochron-secondary-footer.htm");
?>