<?PHP
/**
 * managedata.php
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


$script_name=$_SERVER['SCRIPT_NAME'];

//echo $script_name;exit();

session_start();

include("logincheck.php");

//echo "userpkey: $userpkey";

include("includes/geochron-secondary-header.htm");

include("db.php");


//begin file manager here....
?>

<table align="center" cellspacing=0 cellpadding=0><tr><td width=715 align="left"><h1>File Manager</h1></td></tr></table>

<?
$numtoshow=15;
if($_POST['submit']!=""){

	$mylist=explode(";",$_POST['samplelist']);
	foreach($mylist as $mypkey){
		
		if($_POST["check$mypkey"]){
			//echo "need to update $mypkey here.";
			$db->query("update sample set publ=1 where sample_pkey=$mypkey and userpkey=$userpkey");
		}else{
			$db->query("update sample set publ=0 where sample_pkey=$mypkey and userpkey=$userpkey");
		}
	}
	
	echo "<div style=\"color:#009900;\">Public information updated.</div>";


}


if($_POST['page']!=""){
	$page=$_POST['page'];
}elseif($_GET['page']!=""){
	$page=$_GET['page'];
}else{
	$page=1;
}

if($_POST['sort']!=""){
	$sort=$_POST['sort'];
}elseif($_GET['sort']!=""){
	$sort=$_GET['sort'];
}

if($sort!=""){
	$sortstring="&sort=$sort";
	$urlsortstring="&sort=$sort";
	$sortsql=" order by $sort ";
}else{
	$sortsql=" order by sample_pkey desc ";
}

//echo "sort:$sort";

?>

  <script type="text/javascript">
var newwindow;
function popwindow(url)
{
	newwindow=window.open(url,'name','height=600,width=800,scrollbars=1');
	if (window.focus) {newwindow.focus()}
}
</script>
  <!--- <cfdump var=#form#> --->

<?
$totalcount=$db->get_var("select count(*) 
								from 
								(select 
								samp.sample_pkey
								from 
								sample samp
								left join groupsamplerelate gsr on gsr.sample_pkey = samp.sample_pkey
								left join grouprelate grp on grp.group_pkey = gsr.group_pkey
								left join groups on grp.group_pkey = groups.group_pkey
								left join datasetrelate dr on dr.sample_pkey = samp.sample_pkey
								left join datasetuserrelate dur on dur.dataset_pkey = dr.dataset_pkey
								left join datasets ds on dr.dataset_pkey = ds.dataset_pkey
								where (samp.userpkey=$userpkey or ((dur.users_pkey=$userpkey and dur.confirmed=true) or ds.users_pkey=$userpkey ) or ((grp.users_pkey = $userpkey and grp.confirmed=true) or groups.users_pkey=$userpkey)) and del=0 
								group by
								samp.sample_pkey) foo");

?>
  <div class="aboutpage">
  <table class="aboutpage" width="100%" cellspacing="0" cellpadding="0" border="0">
    <tr>
      <td style="padding-left:5px">
      <?
      if($totalcount == 1){
      echo "$totalcount file found";
      }else{
      echo "$totalcount files found";
      }
      ?>
      </td>
      <td align="right" style="padding-right:5px">&nbsp;</td>
    </tr>
  </table>
  <!-- Close the table started in the header, and start a new table. This is to make the content above center within the header horizontal margins, even though the data table below may stretch horizontally outside the header width.
   </td></tr></table><table align="center" style="padding:0 5px 0 5px"><tr><td> EILEEN DO SOMETHING WITH THE FORCED HEIGHT OF 199 ON THE LEFT HAND TABLE CELL IN THE HEADER -->


<?
if($totalcount>0){
	if($totalcount%$numtoshow==0){
		$numpages=$totalcount/$numtoshow;
	}else{
		$numpages=intval($totalcount/$numtoshow)+1;
	}
	
	$offset=($page-1)*$numtoshow;
	
	/*
	$myrows=$db->get_results("select samp.*,
							getgroups(samp.sample_pkey) as groups,
							getdatasets(samp.sample_pkey) as datasets 
							from sample samp where userpkey=".$_SESSION['userpkey']." and del=0 $sortsql limit $numtoshow offset $offset");
	*/



	$myrows=$db->get_results("select 
								samp.sample_pkey,
								samp.userpkey,
								samp.uploaddate,
								samp.sample_id,
								samp.material,
								samp.igsn,
								getgroups(samp.sample_pkey) as groups,
								getdatasets(samp.sample_pkey) as datasets,
								(select lastname||', '||firstname from users where users_pkey=samp.userpkey) as owner,
								samp.publ
								from 
								sample samp
								left join groupsamplerelate gsr on gsr.sample_pkey = samp.sample_pkey
								left join grouprelate grp on grp.group_pkey = gsr.group_pkey
								left join groups on grp.group_pkey = groups.group_pkey
								left join datasetrelate dr on dr.sample_pkey = samp.sample_pkey
								left join datasetuserrelate dur on dur.dataset_pkey = dr.dataset_pkey
								left join datasets ds on dr.dataset_pkey = ds.dataset_pkey
								where (samp.userpkey=$userpkey or ((dur.users_pkey=$userpkey and dur.confirmed=true) or ds.users_pkey=$userpkey ) or ((grp.users_pkey = $userpkey and grp.confirmed=true) or groups.users_pkey=$userpkey)) and del=0 
								group by
								samp.userpkey,
								samp.uploaddate,
								samp.sample_pkey,
								samp.sample_id,
								samp.material,
								samp.igsn,
								groups,
								datasets,
								owner,
								publ
								$sortsql
								limit $numtoshow offset $offset;");


								
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
      <th style="vertical-align:middle;border-width:0 0 1px 0"><a href="<?=$script_name?>?sort=<?=$sortstring?>">public?</a></th>
      
      <? if($sort=="groups"){ $sortstring="groups+desc"; $sortchar = "down";}else{ $sortstring="groups"; $sortchar = "up";} ?>
      <th style="<?=$cellpadding?>;vertical-align:middle;border-width:0 0 1px 0"><a href="<?=$script_name?>?sort=<?=$sortstring?>">groups</a></th>
      
      <? if($sort=="datasets"){ $sortstring="datasets+desc"; $sortchar = "down";}else{ $sortstring="datasets"; $sortchar = "up";} ?>
      <th style="<?=$cellpadding?>;vertical-align:middle;border-width:0 0 1px 0"><a href="<?=$script_name?>?sort=<?=$sortstring?>">datasets</a></th>
      
      <? if($sort=="sample_id"){ $sortstring="sample_id+desc"; $sortchar = "down";}else{ $sortstring="sample_id"; $sortchar = "up";} ?>
      <th style="<?=$cellpadding?>;vertical-align:middle;border-width:0 0 1px 0"><a href="<?=$script_name?>?sort=<?=$sortstring?>">Sample ID</a></th>
      
      <? if($sort=="material"){ $sortstring="material+desc"; $sortchar = "down";}else{ $sortstring="material"; $sortchar = "up";} ?>
      <th style="<?=$cellpadding?>;vertical-align:middle;border-width:0 0 1px 0"><a href="<?=$script_name?>?sort=<?=$sortstring?>">Material</a></th>
	  
	  <? if($sort=="igsn"){ $sortstring="igsn+desc"; $sortchar = "down";}else{ $sortstring="igsn"; $sortchar = "up";} ?>
	  <th style="<?=$cellpadding?>;vertical-align:middle;border-width:0 0 1px 0"><a href="<?=$script_name?>?sort=<?=$sortstring?>">Unique ID<a/></th>

	  <? if($sort=="owner"){ $sortstring="owner+desc"; $sortchar = "down";}else{ $sortstring="owner"; $sortchar = "up";} ?>
	  <th style="<?=$cellpadding?>;vertical-align:middle;border-width:0 0 1px 0"><a href="<?=$script_name?>?sort=<?=$sortstring?>">Owner<a/></th>



	  <!--
      <th style="<?=$cellpadding?>;vertical-align:middle;border-width:0 0 1px 0">Parent&nbsp;IGSN</th>
      -->
      
      <? if($sort=="laboratoryname"){ $sortstring="laboratoryname+desc"; $sortchar = "down";}else{ $sortstring="laboratoryname"; $sortchar = "up";} ?>
      <!--
      <th style="<?=$cellpadding?>;vertical-align:middle;border-width:0 0 1px 0"><a href="<?=$script_name?>?sort=<?=$sortstring?>">Lab&nbsp;Name</a></th>
      -->
      
      <? if($sort=="analyst_name"){ $sortstring="analyst_name+desc"; $sortchar = "down";}else{ $sortstring="analyst_name"; $sortchar = "up";} ?>
      
      <!--
      <th style="<?=$cellpadding?>;vertical-align:middle;border-width:0 0 1px 0"><a href="<?=$script_name?>?sort=<?=$sortstring?>">Analyst</a></th>
      -->
      
      <? if($sort=="uploaddate"){ $sortstring="uploaddate+desc"; $sortchar = "down";}else{ $sortstring="uploaddate"; $sortchar = "up";} ?>
      <th style="<?=$cellpadding?>;vertical-align:middle;border-width:0 0 1px 0"><a href="<?=$script_name?>?sort=<?=$sortstring?>">Upload Date</a></th>
      
      <th style="vertical-align:middle;border-width:0 0 1px 0" colspan=3></th>
    </tr>
    <?
    



/*
public
groups
datasets
sample_id
material
igsn
laboratoryname
analyst_name
uploaddate

<? 
	if($sort==""){

		$sortstring="+desc";
		$sortchar="up";
	
	}elseif($sort==" desc"){
	
		$sortstring="";
		$sortchar="down";
	
	}
?>


<? if($sort=="public"){$sortstring="public+desc"; $sortchar="up"; }elseif($sort=="public desc"){$sortstring="public"; $sortchar="down";} ?>
<? if($sort=="groups"){$sortstring="groups+desc"; $sortchar="up"; }elseif($sort=="groups desc"){$sortstring="groups"; $sortchar="down";} ?>
<? if($sort=="datasets"){$sortstring="datasets+desc"; $sortchar="up"; }elseif($sort=="datasets desc"){$sortstring="datasets"; $sortchar="down";} ?>
<? if($sort=="sample_id"){$sortstring="sample_id+desc"; $sortchar="up"; }elseif($sort=="sample_id desc"){$sortstring="sample_id"; $sortchar="down";} ?>
<? if($sort=="material"){$sortstring="material+desc"; $sortchar="up"; }elseif($sort=="material desc"){$sortstring="material"; $sortchar="down";} ?>
<? if($sort=="igsn"){$sortstring="igsn+desc"; $sortchar="up"; }elseif($sort=="igsn desc"){$sortstring="igsn"; $sortchar="down";} ?>
<? if($sort=="laboratoryname"){$sortstring="laboratoryname+desc"; $sortchar="up"; }elseif($sort=="laboratoryname desc"){$sortstring="laboratoryname"; $sortchar="down";} ?>
<? if($sort=="analyst_name"){$sortstring="analyst_name+desc"; $sortchar="up"; }elseif($sort=="analyst_name desc"){$sortstring="analyst_name"; $sortchar="down";} ?>
<? if($sort=="uploaddate"){$sortstring="uploaddate+desc"; $sortchar="up"; }elseif($sort=="uploaddate desc"){$sortstring="uploaddate"; $sortchar="down";} ?>


*/













	//print_r($myrows);exit();



    
    
	
    $samplelist="";
    $sampledelim="";
    $linecount=0;
    $linestyle='';
    foreach($myrows as $row){

		$row->igsn=str_replace("SSR.","",$row->igsn);
		$row->igsn=str_replace("GCH.","",$row->igsn);
    	
    	$datasets=str_replace(";;;","<br>",$row->datasets);
    	//$datasets=$row->datasets;
    	$groups=str_replace(";;;","<br>",$row->groups);

    	if($row->userpkey==$userpkey){
    		$mine="yes";
    		$minestyle="";
    	}else{
    		$mine="no";
    		$minestyle="background-color:#fce7e7;";
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
if($mine=="yes"){
?>
            <label>&nbsp;&nbsp;
            <input name="check<?=$row->sample_pkey?>" type="checkbox" value="checkbox"
            <?
            if($row->publ==1){
            ?> 
            checked
            <?
            }
            ?>
            >
&nbsp;&nbsp;</label>
            <!-- the label lets one click slightly outside the checkbox and still get it checked -->
<?
}else{
	if($row->publ==1){
		echo "Yes";
	}else{
		echo "No";
	}
}
?>
          </div></td>

        <td style=" <?=$cellpadding?>;vertical-align:middle;text-align:left;<?=$linestyle?>;color:black;white-space:nowrap"><div style="font-size:.9em;"><?=$groups?></dev></td>
        <td style=" <?=$cellpadding?>;vertical-align:middle;text-align:left;<?=$linestyle?>;color:black;white-space:nowrap"><div style="font-size:.9em;"><?=$datasets?></div></td>
        <td style=" <?=$cellpadding?>;vertical-align:middle;text-align:left;<?=$linestyle?>;color:black;white-space:nowrap"><?=$row->sample_id?></td>
        <td style=" <?=$cellpadding?>;vertical-align:middle;text-align:left;<?=$linestyle?>;color:black;white-space:nowrap"><?=$row->material?></td>
		<td style=" <?=$cellpadding?>;vertical-align:middle;text-align:left;<?=$linestyle?>;color:black;white-space:nowrap"><div class="aboutpage"><a href="javascript:popwindow('viewid.php?id=<?=$row->igsn?>');"><?=strtoupper($row->igsn)?></a></div></td>
		<td style=" <?=$cellpadding?>;vertical-align:middle;text-align:left;<?=$linestyle?>;color:black;white-space:nowrap"><div class="aboutpage"><?=$row->owner?></div></td>
		<!--
        <td style=" <?=$cellpadding?>;vertical-align:middle;text-align:left;<?=$linestyle?>;color:black;white-space:nowrap"><a href="javascript:popwindow('viewsesar.php?igsn=<?=$row->parentigsn?>');"><?=$row->parentigsn?></a></td>
        
        <td style=" <?=$cellpadding?>;vertical-align:middle;text-align:left;<?=$linestyle?>;color:black;white-space:nowrap"><?=$row->laboratoryname?></td>
        <td style=" <?=$cellpadding?>;vertical-align:middle;text-align:left;<?=$linestyle?>;color:black;white-space:nowrap"><?=$row->analyst_name?></td>
        -->
        <td style=" <?=$cellpadding?>;vertical-align:middle;text-align:left;<?=$linestyle?>;color:black;white-space:nowrap"><?=$showdate?></td>
<?
if($mine=="yes"){
?>
        <td style=" vertical-align:middle;text-align:left;<?=$linestyle?>;" ><div class="aboutpage"><a style="font-size:7pt; " href="deletesample.php?pkey=<?=$row->sample_pkey?>&page=<?=$page?>" OnClick="return confirm('Are you sure you want to delete <?=$row->igsn?>?')">DELETE</a></div></td>
<?
}else{
?>
		<td style=" vertical-align:middle;text-align:left;<?=$linestyle?>;" ><div class="aboutpage"></div></td>
<?
}
?>
		<!--
        <td style=" vertical-align:middle;text-align:left;<?=$linestyle?>;"><div class="aboutpage"><a style="font-size:7pt; " href="javascript:popwindow('viewfile.php?pkey=<?=$row->sample_pkey?>');">VIEW</a></div></td>
		-->
		
        <td style=" vertical-align:middle;text-align:left;<?=$linestyle?>;"><div class="aboutpage"><a style="font-size:7pt; " href="viewfile.php?pkey=<?=$row->sample_pkey?>" >VIEW</a></div></td>

        <td style=" vertical-align:middle;text-align:left;<?=$linestyle?>;"><div class="aboutpage"><a style="font-size:7pt; " href="downloadfile.php?pkey=<?=$row->sample_pkey?>">DOWNLOAD</a></div></td>
        
      </tr>
      <?
      $samplelist=$samplelist.$sampledelim.$row->sample_pkey;
      $sampledelim=";";
    }//end loop over aliquots
      ?>

  </table>
  <table align="left" class="aliquot"  style="margin-top:5px;border:none;">

      <tr>
      	<?
      	if($page > 1){
      	?>
          <td style="border-style:none;padding-right:5px;padding-left:0px"><a href="<?=$script_name?>?page=<?=$page-1?><?=$urlsortstring?>">&larr;&nbsp;previous</a> </td>
        <?
        }else{
        ?>
        <td style="border-style:none;padding-right:5px;padding-left:0px">&nbsp;</td>
        <?
        }
        ?>
        <td style="border-style:none">
        <?
        
        
        
        if($numpages > 1){
        	
        	if($page > 10){
        		$startpage=$page-10;
        	}else{
        		$startpage=1;
        	}
        	
        	if($numpages > ($startpage+19)){
        		$endpage=$startpage+19;
        	}else{
        		$endpage=$numpages;
        	}
        	
        	for($x=$startpage;$x<=$endpage;$x++){
        		if($page==$x){
        			echo $x;
        		}else{
              	?>
                <a href="<?=$script_name?>?page=<?=$x?><?=$urlsortstring?>"><?=$x?></a>
                <?
				}
			}
        }

        ?>
        </td>
        <?
        if($numpages > $page){
        ?>
        	<td style="border-style:none"><a href="<?=$script_name?>?page=<?=$page+1?><?=$urlsortstring?>">next&nbsp;&rarr;</a> </td>
        <?
        }
        ?>
      </tr>

<?
} //end if results > 0
?>
  </table>
  <br clear="left" />
<?
if($totalcount > 0){
?>
  <input style="margin:5px 0 0 5px" type="submit" name="submit" value="Update public info">
<?
}
?>
  <input type="hidden" name="samplelist" value="<?=$samplelist?>">
  <input type="hidden" name="page" value="<?=$page?>">
  <input type="hidden" name="sort" value="<?=$sort?>">
  </form>
  
  <br><br>
  
  <INPUT TYPE="button" value="Submit Data" onClick="parent.location='submitdata.php'">
  
  </div>

  <div id="debug" style="display: none"> <br>
    <br>
	select * from aliquot 
	where userpkey=".$_SESSION['userpkey']." 
	and del=0 order by aliquot_pkey desc limit $numtoshow offset $offset
  </div>


<?
if($totalcount>0){
?>

<div name="footer" style="width:900px;align:center;margin-left:auto;margin-right:auto;text-align:center;margin-top:20px;margin-bottom:10px;padding-top:10px; border-width:1px 0px 0px 0px;border-style:solid;border-color:#4572bc;font-size:9px;font-color:c9c9c9" align="center"></div>

<h1>Interactive Map</h1>

<div style="padding-left:0px;">
<INPUT TYPE="button" value="View Your Samples on Interactive Map" onClick="parent.location='userinteractivemap.php'">
</div>

<?
}
?>

<div name="footer" style="width:900px;align:center;margin-left:auto;margin-right:auto;text-align:center;margin-top:20px;margin-bottom:10px;padding-top:10px; border-width:1px 0px 0px 0px;border-style:solid;border-color:#4572bc;font-size:9px;font-color:c9c9c9" align="center"></div>


<h1>Groups</h1>
<br>The groups feature allows you to invite other Geochron users to view your samples. To share <br>your samples
with a group of users, first create a group and then choose sample to be shared.

<?

$groupcount=$db->get_var("select
						count(*)
						from groups grp
						left join grouprelate grprel on grp.group_pkey = grprel.group_pkey
						where grp.users_pkey=$userpkey 
						or (grprel.users_pkey=$userpkey and grprel.confirmed=true)");

if($groupcount==0){

?>
You have not defined any groups.
<?

}else{
	
	$cellpadding="padding:3px 7px 3px 7px;";

?>
<div align="center">
<table class="aliquot" style="width:900px;margin-top:15px;border-width:1px 1px 1px 1px;border-style:solid">
	<tr style="vertical-align:middle">
		<th style="vertical-align:middle;border-width:0 0 1px 0">name</th>
		<th style="vertical-align:middle;border-width:0 0 1px 0">owner</th>
		<th style="vertical-align:middle;border-width:0 0 1px 0">sample count</th>
		<th style="vertical-align:middle;border-width:0 0 1px 0">users</th>
		<th style="vertical-align:middle;border-width:0 0 1px 0"></th>
		<th style="vertical-align:middle;border-width:0 0 1px 0"></th>
		<th style="vertical-align:middle;border-width:0 0 1px 0"></th>
		<th style="vertical-align:middle;border-width:0 0 1px 0"></th>
		<th style="vertical-align:middle;border-width:0 0 1px 0"></th>
	</tr>
<?

	//get group list
	$rows=$db->get_results("select
							grp.group_pkey,
							groupname,
							grp.users_pkey as userpkey,
							(select lastname||', '||firstname from users where users_pkey=grp.users_pkey) as owner,
							(select count(*) from groupsamplerelate where group_pkey = grp.group_pkey) as samplecount,
							(select count(*) from grouprelate where group_pkey = grp.group_pkey and confirmed=true) as activecount,
							(select count(*) from grouprelate where group_pkey = grp.group_pkey and confirmed=false) as pendingcount
							from groups grp
							left join grouprelate grprel on grp.group_pkey = grprel.group_pkey
							where grp.users_pkey=$userpkey 
							or (grprel.users_pkey=$userpkey and grprel.confirmed=true) order by grp.group_pkey desc");

							$linecount=0;


							foreach($rows as $row){

								if($row->userpkey==$userpkey){
									$mine="yes";
									$minestyle="";
								}else{
									$mine="no";
									$minestyle="background-color:#fce7e7;";
									$datasets="";
								}

								$linestyle='';
	
								$linecount++;
								if($linecount==3){
									$linestyle="border-width:0px 0px 1px 0px;$minestyle";
									$linecount=0;
								}else{
									$linestyle="border-style:none;$minestyle";
								}
							

							
							
								?>
								<tr  style="vertical-align:middle;text-align:left;">
								<td style=" <?=$cellpadding?>;vertical-align:middle;text-align:left;<?=$linestyle?>;color:black;white-space:nowrap"><?=$row->groupname?></td>
								<td style=" <?=$cellpadding?>;vertical-align:middle;text-align:left;<?=$linestyle?>;color:black;white-space:nowrap"><?=$row->owner?></td>
								<td style=" <?=$cellpadding?>;vertical-align:middle;text-align:left;<?=$linestyle?>;color:black;white-space:nowrap"><?=$row->samplecount?></td>
								<td style=" <?=$cellpadding?>;vertical-align:middle;text-align:left;<?=$linestyle?>;color:black;white-space:nowrap">Active(<?=$row->activecount?>) Pending(<?=$row->pendingcount?>)</td>

								<td style=" vertical-align:middle;text-align:left;<?=$linestyle?>;"><div class="aboutpage"><a style="font-size:7pt; " href="viewgroup.php?group_pkey=<?=$row->group_pkey?>" >VIEW</a></div></td>
								<td style=" vertical-align:middle;text-align:left;<?=$linestyle?>;"><div class="aboutpage"><a style="font-size:7pt; " href="managegroupsamples.php?group_pkey=<?=$row->group_pkey?>&p=md">MANAGE SAMPLES</a></div></td>
<?
if($mine=="yes"){
?>
								<td style=" vertical-align:middle;text-align:left;<?=$linestyle?>;"><div class="aboutpage"><a style="font-size:7pt; " href="inviteusers.php?group_pkey=<?=$row->group_pkey?>&p=md">ADD USERS</a></div></td>
								<td style=" vertical-align:middle;text-align:left;<?=$linestyle?>;" ><div class="aboutpage"><a style="font-size:7pt; " href="deletegroup.php?group_pkey=<?=$row->group_pkey?>" OnClick="return confirm('Are you sure you want to delete <?=$row->groupname?>?')">DELETE</a></div></td>
<?
}else{
?>
								<td style=" vertical-align:middle;text-align:left;<?=$linestyle?>;"><div class="aboutpage"></div></td>
								<td style=" vertical-align:middle;text-align:left;<?=$linestyle?>;" ><div class="aboutpage"></div></td>
<?
}
?>
								<td style=" vertical-align:middle;text-align:left;<?=$linestyle?>;" >
									<?
									if($row->samplecount>0){
									?>
									<INPUT TYPE="button" value="Map" onClick="parent.location='groupinteractivemap.php?group_pkey=<?=$row->group_pkey?>'">
									<?
									}else{
									?>
									&nbsp;
									<?
									}
									?>
								</td>
								</tr>
								<?
							}
?>
</table>
</div>

<?
}

?>



<br><br>
<INPUT TYPE="button" value="Add New Group" onClick="parent.location='addgroup.php'">


































<div name="footer" style="width:900px;align:center;margin-left:auto;margin-right:auto;text-align:center;margin-top:20px;margin-bottom:10px;padding-top:10px; border-width:1px 0px 0px 0px;border-style:solid;border-color:#4572bc;font-size:9px;font-color:c9c9c9" align="center"></div>


<h1>Datasets</h1>

<?

$datasetcount=$db->get_var("select count(*) from datasets where users_pkey=$userpkey");

if($datasetcount==0){

?>
You have not defined any datasets.
<?

}else{
	
	$cellpadding="padding:3px 7px 3px 7px;";



?>
<div align="center">
<table class="aliquot" style="width:900px;margin-top:15px;border-width:1px 1px 1px 1px;border-style:solid">
	<tr style="vertical-align:middle">
		<th style="vertical-align:middle;border-width:0 0 1px 0">name</th>
		<th style="vertical-align:middle;border-width:0 0 1px 0">samples</th>
		<th style="vertical-align:middle;border-width:0 0 1px 0"></th>
		<th style="vertical-align:middle;border-width:0 0 1px 0"></th>
		<th style="vertical-align:middle;border-width:0 0 1px 0"></th>
		<th style="vertical-align:middle;border-width:0 0 1px 0"></th>
		<th style="vertical-align:middle;border-width:0 0 1px 0"></th>
		<th style="vertical-align:middle;border-width:0 0 1px 0"></th>
		<th style="vertical-align:middle;border-width:0 0 1px 0"></th>
		<th style="vertical-align:middle;border-width:0 0 1px 0"></th>
		<th style="vertical-align:middle;border-width:0 0 1px 0"></th>
	</tr>
<?

	//get dataset list
	$rows=$db->get_results("select
							ds.dataset_pkey,
							ds.users_pkey as userpkey,
							datasetname,
							linkstring,
							published,
							to_char(uploaddate, 'YYYY-MM-DD') as mydate,
							(select count(*) from datasetrelate where dataset_pkey = ds.dataset_pkey) as samplecount,
							(select count(*) from datasetuserrelate where dataset_pkey = ds.dataset_pkey and confirmed=true) as activecount,
							(select count(*) from datasetuserrelate where dataset_pkey = ds.dataset_pkey and confirmed=false) as pendingcount
							from datasets ds
							left join datasetuserrelate dur on ds.dataset_pkey = dur.dataset_pkey
							where ds.users_pkey=$userpkey
							or (dur.users_pkey=$userpkey and dur.confirmed=true)
							order by ds.dataset_pkey desc");

			

							$linecount=0;
							$linestyle='';

							$linecount++;
							if($linecount==3){
								$linestyle="border-width:0px 0px 1px 0px;";
								$linecount=0;
							}else{
								$linestyle="border-style:none;";
							}

							foreach($rows as $row){

								if($row->userpkey==$userpkey){
									$mine="yes";
									$minestyle="";
								}else{
									$mine="no";
									$minestyle="background-color:#fce7e7;";
									$datasets="";
								}

								$linestyle='';
	
								$linecount++;
								if($linecount==3){
									$linestyle="border-width:0px 0px 1px 0px;$minestyle";
									$linecount=0;
								}else{
									$linestyle="border-style:none;$minestyle";
								}

								$samplecount=$row->samplecount;
								$published=$row->published;
								?>
								<tr  style="vertical-align:middle;text-align:left;">
								<td style=" <?=$cellpadding?>;vertical-align:middle;text-align:left;<?=$linestyle?>;color:black;white-space:nowrap"><?=$row->datasetname?></td>
								<td style=" <?=$cellpadding?>;vertical-align:middle;text-align:left;<?=$linestyle?>;color:black;white-space:nowrap"><?=$row->samplecount?></td>
								
								<td style=" <?=$cellpadding?>;vertical-align:middle;text-align:left;<?=$linestyle?>;color:black;white-space:nowrap">Active(<?=$row->activecount?>) Pending(<?=$row->pendingcount?>)</td>

<?
if($published!="t"){
?>

								<td style=" vertical-align:middle;text-align:left;<?=$linestyle?>;"><div class="aboutpage"><a style="font-size:7pt; " href="managedatasetsamples.php?dataset_pkey=<?=$row->dataset_pkey?>&p=md">MANAGE&nbsp;SAMPLES</a></div></td>


<?
if($mine=="yes"){
?>
								<td style=" vertical-align:middle;text-align:left;<?=$linestyle?>;"><div class="aboutpage"><a style="font-size:7pt; " href="invitedatasetusers.php?dataset_pkey=<?=$row->dataset_pkey?>&p=md">ADD&nbsp;USERS</a></div></td>
<?
}else{
?>
								<td style=" vertical-align:middle;text-align:left;<?=$linestyle?>;"><div class="aboutpage"></div></td>

<?
}
?>


<?
}else{
?>
								
<td style=" vertical-align:middle;text-align:left;<?=$linestyle?>;"><div class="aboutpage"></td>
<td style=" vertical-align:middle;text-align:left;<?=$linestyle?>;"><div class="aboutpage"></td>



<?
}
?>



								<?
								if($samplecount > 0){
								?>
									<td style=" vertical-align:middle;text-align:left;<?=$linestyle?>;"><div class="aboutpage"><a style="font-size:7pt; " href="/dataset/html/<?=$row->linkstring?>" target="_blank">HTML&nbsp;LINK</a></div></td>
									<td style=" vertical-align:middle;text-align:left;<?=$linestyle?>;"><div class="aboutpage"><a style="font-size:7pt; " href="/dataset/xls/<?=$row->linkstring?>" target="_blank">XLS&nbsp;LINK</a></div></td>
									
									<td style=" vertical-align:middle;text-align:left;<?=$linestyle?>;"><div class="aboutpage"><a style="font-size:7pt; " href="/datasetinteractivemap?id=<?=$row->dataset_pkey?>" target="_blank">MAP</a></div></td>
									
									<td style=" vertical-align:middle;text-align:left;<?=$linestyle?>;"><div class="aboutpage"><a style="font-size:7pt; " href="/datasetkml?id=<?=$row->dataset_pkey?>" target="_blank">KML</a></div></td>
								<?
								}else{
								?>
									<td style=" vertical-align:middle;text-align:left;<?=$linestyle?>;">&nbsp;</td>
									<td style=" vertical-align:middle;text-align:left;<?=$linestyle?>;">&nbsp;</td>
								<?
								}
								?>





<?
if($mine=="yes"){
?>
								<?
								if($published!="t"){
								?>
								<td style=" vertical-align:middle;text-align:left;<?=$linestyle?>;" ><div class="aboutpage"><a style="font-size:7pt; " href="deletedataset.php?dataset_pkey=<?=$row->dataset_pkey?>" OnClick="return confirm('Are you sure you want to delete <?=$row->datasetname?>?')">DELETE</a></div></td>
								<?
								}else{
								?>
								<td style=" vertical-align:middle;text-align:left;<?=$linestyle?>;" ></td>
								<?
								}
								?>
<?
}else{
?>
								<td style=" vertical-align:middle;text-align:left;<?=$linestyle?>;"><div class="aboutpage"></div></td>

<?
}
?>








								









<?
if($mine=="yes"){
?>
								<td style=" vertical-align:middle;text-align:left;<?=$linestyle?>;" >
								
								
								<?
								if(($samplecount > 0)&&($published!="t")){
									//http://www.earthchem.org/library/submit/geochron/start
									//http://www.earthchem.org/library/submit/geochron
									
									http://www.earthchem.org/library/submit/external
								?>
								
								
									<form action="http://www.earthchem.org/library/submit/external" method="POST">
										<input type="hidden" name="geo_title" value="<?=$row->datasetname?> - <?=$row->mydate?>">
										<input type="hidden" name="system" value="geochron">
										<input type="hidden" name="geo_file" value="<?=$row->linkstring?>.zip">
										<input type="submit" name="submit" value="Publish to EC Library">
									</form>
								
								
								<?
								}
								if($published=="t"){
								?>
								Dataset is Published.
								<?
								}
								?>
								</td>
<?
}else{
?>
								<td style=" vertical-align:middle;text-align:left;<?=$linestyle?>;" >
								<?
								if($published=="t"){
								?>
								Dataset is Published.
								<?
								}else{
								?>
								&nbsp;
								<?
								}
								?>
								</td>
								

<?
}
?>




















								</tr>
								<?
							}

?>
</table>
</div>
<?

}//end if datasetcount > 0

?>



<br><br>
<INPUT TYPE="button" value="Add New Dataset" onClick="parent.location='adddataset.php'">

  


































<!---
<div name="footer" style="width:900px;align:center;margin-left:auto;margin-right:auto;text-align:center;margin-top:20px;margin-bottom:10px;padding-top:10px; border-width:1px 0px 0px 0px;border-style:solid;border-color:#4572bc;font-size:9px;font-color:c9c9c9" align="center"></div>


<h1>File Upload</h1>
<form action="uploadfile.php" method="post" enctype="multipart/form-data">
	<input name="filetoupload" type="file"> &nbsp;
	<input name="filesubmit" type="submit" value="Upload file">
</form>
--->

<div name="footer" style="width:900px;align:center;margin-left:auto;margin-right:auto;text-align:center;margin-top:30px;margin-bottom:10px;padding-top:10px; border-width:1px 0px 0px 0px;border-style:solid;border-color:#4572bc;font-size:9px;font-color:c9c9c9" align="center"></div>

<h1>Upload Image</h1>
    <form action="uploadimage.php" method="post" enctype="multipart/form-data">
      <input name="filetoupload" type="file"> &nbsp;
      <input name="filesubmit" type="submit" value="Upload file">
    </form>

<div name="footer" style="width:900px;align:center;margin-left:auto;margin-right:auto;text-align:center;margin-top:30px;margin-bottom:10px;padding-top:10px; border-width:1px 0px 0px 0px;border-style:solid;border-color:#4572bc;font-size:9px;font-color:c9c9c9" align="center"></div>

<h1>Data Reduction Software</h1>
<div class="aboutpage">
Click <a href="submitdata.php">HERE</a> to get data reduction software.
</div>








<?
include("includes/geochron-secondary-footer.htm");
?>
