<?PHP
/**
 * deletesamples.php
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

$username=$_SESSION['username'];

include("includes/geochron-secondary-header.htm");

?>
<script type="text/javascript">

function confSubmit(form) {
	if (confirm("Are you sure? Checked samples will be permanently deleted!")) {
		form.submit();
	}
}
</script>
<?

if($username!="jason" && $username!="doug"){
	echo "Invalid user credentials.";
	include("includes/geochron-secondary-footer.htm");
	exit();
}

include("db.php");

?>

<table align="center" cellspacing=0 cellpadding=0><tr><td width=715 align="center"><h1>Delete Samples</h1></td></tr></table>

<?
$numtoshow=15;
if($_POST['samplelist']!=""){

	//print_r($_POST);

	$pkeydelim="";
	$pkeylist="";
	
	foreach($_POST as $key=>$value){
		//echo "$key : $value <br>";
		//echo strpos($key,"check")." here<br>";
		if(strpos($key,"heck")>0){
			//echo "$key : $value <br>";
			$pkey=str_replace("check","",$key);
			$pkeylist.=$pkeydelim.$pkey;
			$pkeydelim=",";
		}
	}
	
	//echo "list: $pkeylist<br><br>";
	
	$db->query("delete from sample_age where sample_pkey in ($pkeylist)");
	$db->query("delete from sample where sample_pkey in ($pkeylist)");
	
	echo "<div style=\"color:red;\">Samples Deleted.</div><br>";



}


if($_POST['page']!=""){
	$page=$_POST['page'];
}elseif($_GET['page']!=""){
	$page=$_GET['page'];
}else{
	$page=1;
}

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
$totalcount=$db->get_var("select count(*) from sample ");
?>
  <table width=100% cellspacing=0 cellpadding=0 border=0>
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
      <td align="right" style="padding-right:5px"></td>
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
	
	//$myrows=$db->get_results("select * from sample where userpkey=".$_SESSION['userpkey']." and del=0 order by sample_pkey desc limit $numtoshow offset $offset");
	//$myrows=$db->get_results("select * from sample  order by sample_pkey desc limit $numtoshow offset $offset");
	$myrows=$db->get_results("select * from sample  order by sample_pkey");
							
?>

  <form name="myform" action="deletesamples.php" method="post">
  <input style="margin:5px 0 0 5px" type="button" onClick="confSubmit(this.form);" value="Delete Checked Samples">
  <input type="hidden" name="samplelist" value="fff">
  <input type="hidden" name="page" value="<?=$page?>">
  <?
  $cellpadding="padding:3px 7px 3px 7px;";
  ?>
  <!-- little extra left and right padding for the columns containing data -->
  <table class="aliquot" style="width:100%;margin-top:15px;border-width:1px 1px 1px 1px;border-style:solid">
    <tr style="vertical-align:middle">
      <th style="vertical-align:middle;border-width:0 0 1px 0">delete?</th>
      <th style="vertical-align:middle;border-width:0 0 1px 0" colspan=1></th>
      <th style="<?=$cellpadding?>;vertical-align:middle;border-width:0 0 1px 0">Date</th>
	  <th style="<?=$cellpadding?>;vertical-align:middle;border-width:0 0 1px 0">IGSN</th>
      <th style="<?=$cellpadding?>;vertical-align:middle;border-width:0 0 1px 0">Parent&nbsp;IGSN</th>
      <th style="<?=$cellpadding?>;vertical-align:middle;border-width:0 0 1px 0">Analyst</th>
    </tr>
    <?
    $samplelist="";
    $sampledelim="";
    $linecount=0;
    $linestyle='';
    foreach($myrows as $row){
      $linecount++;
      if($linecount==3){
        $linestyle="border-width:0px 0px 1px 0px;";
        $linecount=0;
      }else{
        $linestyle="border-style:none;";
      }
    ?>
      <tr  style="vertical-align:middle;text-align:left;">
        <td style="<?=$linestyle?>"><div align="center">
            <label>&nbsp;&nbsp;
            <input name="check<?=$row->sample_pkey?>" type="checkbox" value="checkbox">
&nbsp;&nbsp;</label>
            <!-- the label lets one click slightly outside the checkbox and still get it checked -->
          </div></td>
        <td style=" vertical-align:middle;text-align:left;<?=$linestyle?>;"><a style="color:##696969;font-size:7pt; " href="javascript:popwindow('viewfile.php?pkey=<?=$row->sample_pkey?>');">VIEW</a></td>
        <td style=" <?=$cellpadding?>;vertical-align:middle;text-align:left;<?=$linestyle?>;color:black;white-space:nowrap"><?=$row->uploaddate?></td>
		<td style=" <?=$cellpadding?>;vertical-align:middle;text-align:left;<?=$linestyle?>;color:black;white-space:nowrap"><a href="javascript:popwindow('viewsesar.php?igsn=<?=$row->igsn?>');"><?=$row->igsn?></a></td>
        <td style=" <?=$cellpadding?>;vertical-align:middle;text-align:left;<?=$linestyle?>;color:black;white-space:nowrap"><a href="javascript:popwindow('viewsesar.php?igsn=<?=$row->parentigsn?>');"><?=$row->parentigsn?></a></td>
        <td style=" <?=$cellpadding?>;vertical-align:middle;text-align:left;<?=$linestyle?>;color:black;white-space:nowrap"><?=$row->analyst_name?></td>
      </tr>
      <?
      //$samplelist=$samplelist.$sampledelim.$row->sample_pkey;
      //$sampledelim=";";
    }//end loop over aliquots
      ?>

  </table>
  
  <?
  /*
  ?>
  <table align="left" class="aliquot"  style="margin-top:5px;border:none;">
































      <tr>
      	<?
      	if($page > 1){
      	?>
          <td style="border-style:none;padding-right:5px;padding-left:0px"><a href="filemanager.php?page=<?=$page-1?>">&larr;&nbsp;previous</a> </td>
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
                <a href="filemanager.php?page=<?=$x?>"><?=$x?></a>
                <?
				}
			}
        }
        
        
        

        
        
        
        
        
        
        
        ?>
        </td>
        <?
        if($numpages > $page){
        ?>
        	<td style="border-style:none"><a href="filemanager.php?page=<?=$page+1?>">next&nbsp;&rarr;</a> </td>
        <?
        }
        ?>
      </tr>
<?
		*/





} //end if results > 0
?>













  </table>
  <br clear="left" />

  </form>
  <div id="debug" style="display: none"> <br>
    <br>
	select * from aliquot 
	where userpkey=".$_SESSION['userpkey']." 
	and del=0 order by aliquot_pkey desc limit $numtoshow offset $offset
  </div>
  </cfif>

<?
include("includes/geochron-secondary-footer.htm");
?>
