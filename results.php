<?PHP
/**
 * results.php
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

function sigorigval($origval,$origerr,$numplaces){

	//Math from Noah McLean at MIT

	$x=round($origval*pow(10,(($numplaces-1)-floor(log10(2*$origerr))))) * pow(10,((floor(log10(2*$origerr))))-($numplaces-1));
	
	return($x);

}

function sigerrval($origerr,$numplaces){

	//Math from Noah McLean at MIT

	$x=round(2*$origerr*(pow(10,(($numplaces-1)-floor(log10(2*$origerr)))))) * pow(10,(floor(log10(2*$origerr)))-($numplaces-1));
		
	return($x);
}

function sigaloneval($origerr,$numplaces){

	//Math from Noah McLean at MIT

	$x=round($origerr*(pow(10,(($numplaces-1)-floor(log10($origerr)))))) * pow(10,(floor(log10($origerr)))-($numplaces-1));
	
	return($x);

}

//build sort url here
$sorturl=$_SERVER['PHP_SELF']."?";
$sortdelim="";
foreach($_GET as $key=>$value){
	if($key!="s" && $key!="page" && $key!="yipp"){
		$sorturl.=$sortdelim.$key."=".$value;
		$sortdelim="&";
	}
}

session_start();
include("db.php");


include("includes/geochron-secondary-header.htm");
?>
<script language="JavaScript" type="text/JavaScript">
function showdebug(){
	document.getElementById('debug').style.display='block';
}
</script>

<SCRIPT type="text/javascript" src="js/prototype.js"></SCRIPT>

<script language="JavaScript" type="text/JavaScript">
<!--



function showfracs(id) {
	var thsObj = document.getElementById('row'+id);
	var imgObj = document.getElementById('img'+id);
	
	if(thsObj.style.display == 'table-row') {
		thsObj.style.display = 'none';
		imgObj.src = 'images/rightarrow.gif';
	}else{
		//do AJAX call here to get fraction information

		var url = 'getfractions.php';

		var pars = pars + '&pkey='+id;
		
		var myAjax = new Ajax.Request(url, {
			method: 'get',
			parameters: pars,
			onSuccess: function(transport) {
				//alert(transport.responseText);
				document.getElementById('fracdiv'+id).innerHTML=transport.responseText;
			},
			onFailure: function(t) {
				alert('Error ' + t.status + ' -- ' + t.statusText);
			},
		});

		thsObj.style.display = 'table-row';
		imgObj.src = 'images/downarrow.gif';
	}
}

//-->
</script>

<style type="text/css">
.paginate {
	border-style: solid;
	border-width: 1px;
	border-color: #999999;
	color: #8a1e04;
	padding: 2px 3px 2px 3px;
	background-color: #FFFFFF; /*f0f4f5;*/
	margin:0px 0px 0px 0px;
	text-decoration:none;
	font-weight:bold;
}

.paginate a:link a:visited{
	color: #8a1e04;
}

.current {
	border-style: solid;
	border-width: 1px;
	border-color: #999999;
	color: #FFFFFF;
	padding: 2px 3px 2px 3px;
	background-color: #8a1e04; /*f0f4f5;*/
	margin:0px 0px 0px 0px;
	text-decoration:none;
	font-weight:bold;
}
.inactive {
	border-style: solid;
	border-width: 1px;
	border-color: #999999;
	color: #999999;
	padding: 2px 3px 2px 3px;
	background-color: #FFFFFF; /*f0f4f5;*/
	margin:0px 0px 0px 0px;
	text-decoration:none;
	font-weight:bold;
}
.sortlink a:link, .sortlink a:visited {
	color: #999999;
	text-decoration:none;
	font-weight:bold;
}
</style>

<div class="saboutpage">

<div class="aboutpage">
<a href="search.php">new search</a>
</div>
 
 <?
 /*
 &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href="geochroninteractivemap.php?pkey=<?=$_GET['pkey']?>">view samples on interactive map</a>
 */
 ?>
 
 
 <p>
 

<h1>Results</h1>
<?


$ipp=$_GET['ipp'];
$page=$_GET['page'];
$sort=urldecode($_GET['s']);
$oldsort=$_GET['os'];

if($sort=="unique_id"){
	$sort="igsn";
}

if($sort=="unique_id desc"){
	$sort="igsn desc";
}



if($ipp==""){
	$ipp=25;
}

if($page==""){
	$page=1;
}

if($sort==""){
	$sort="sample_pkey desc";
}

if($oldsort==""){
	$oldsort=="sample_pkey";
}

$s=urlencode($sort);
$os=urlencode($oldsort);

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
	if(!$pkey){
		if($_GET['pkey'] != ""){
			$pkey=$_GET['pkey'];
		}else{
			header("Location:search.php");
		}
	}
	
	include("buildquery.php");

	//$queryrow=$db->get_row("select * from search_query where search_query_pkey=$pkey");
	//$querystring=$queryrow->querystring;
	
	$querystring=$newquerystring;
	
	//echo nl2br($querystring)." <br><br>";
	
	
	$totalcount=$db->get_var("select count(*) from ($querystring) foo");

	
	//echo "$querystring order by aliquot_pkey desc limit $numtoshow offset $offset;";
	
	
	


	//do paginator here
	include("bpaginator.php");
	
	$pages = new Paginator;
	$pages->items_total = $totalcount;  
	$pages->mid_range = 9;  
	$pages->paginate();  
		
	echo "Page $pages->current_page of $pages->num_pages";
	
	$offset=($page-1)*$ipp;
	
	$myrows=$db->get_results("$querystring order by $sort limit $ipp offset $offset");

//put back sort
if($sort=="igsn"){
	$sort="unique_id";
}

if($sort=="igsn desc"){
	$sort="unique_id desc";
}

if(count($myrows)>0){


	//build javascript array here for all pkeys
	//var myCars=new Array("Saab","Volvo","BMW");
	$jsstring="var mypkeys = new Array(";
	$jsstringdelim="";
	foreach($myrows as $row){
		$jsstring.=$jsstringdelim."\"$row->sample_pkey\"";
		$jsstringdelim=",";
	}
	
	$jsstring.=");\n";
	
	//echo $jsstring;
	//exit();




	?>







<script type="text/javascript">

function expandall()
{
	var url = 'getfractions.php';
	
	<?=$jsstring?>
	//mypkeys
	//for (var mycount = 0; mycount < mypkeys.length; mycount++) {
	for (var mycount = 0; mycount < mypkeys.length; mycount++) {
		//alert(mypkeys[mycount]);
		//Do something
		var thsObj = document.getElementById('row'+mypkeys[mycount]);
		var imgObj = document.getElementById('img'+mypkeys[mycount]);

		var thsfracdiv = document.getElementById('fracdiv'+mypkeys[mycount]);

		var pars = '&pkey='+mypkeys[mycount];
		
		new Ajax.Request(url, {
			method: 'get',
			asynchronous: false,
			parameters: pars,
			onSuccess: function(transport) {
				//alert(transport.responseText);
				//document.getElementById('fracdiv'+mypkeys[mycount]).innerHTML=transport.responseText;
				thsfracdiv.innerHTML=transport.responseText;
				//alert(mypkeys[mycount]+transport.responseText);
			},
			onFailure: function(t) {
				alert('Error ' + t.status + ' -- ' + t.statusText);
			},
		});	
		
		thsObj.style.display = 'table-row';
		imgObj.src = 'images/downarrow.gif';

	}

}
</script>

<script type="text/javascript">

function contractall()
{
	
	<?=$jsstring?>
	//mypkeys
	//for (var mycount = 0; mycount < mypkeys.length; mycount++) {
	for (var mycount = 0; mycount < mypkeys.length; mycount++) {
		//alert(mypkeys[mycount]);
		//Do something
		var thsObj = document.getElementById('row'+mypkeys[mycount]);
		var imgObj = document.getElementById('img'+mypkeys[mycount]);

		var thsfracdiv = document.getElementById('fracdiv'+mypkeys[mycount]);
		
		thsfracdiv.innerHTML='';
		
		thsObj.style.display = 'none';
		imgObj.src = 'images/rightarrow.gif';

	}

}
</script>








  <table align="center" class="aliquot" width="750px";>
    <tr>
      
      
		<th nowrap colspan=3>
			<INPUT TYPE="button" value="Expand All" onClick="expandall();"> <INPUT TYPE="button" value="Contract All" onClick="contractall();">
		</th>
		
		<? if($sort=="sample_id"){ $sortstring="sample_id+desc"; $sortchar = "down";}else{ $sortstring="sample_id"; $sortchar = "up";} ?>
		<th><a class="sortlink" href="<?=$sorturl?>&s=<?=$sortstring?>">Sample ID</a></th>
		
		<? if($sort=="unique_id"){ $sortstring="unique_id+desc"; $sortchar = "down";}else{ $sortstring="unique_id"; $sortchar = "up";} ?>
		<th nowrap><a class="sortlink" href="<?=$sorturl?>&s=<?=$sortstring?>">IGSN</a></th>
		
		<? if($sort=="ecproject"){ $sortstring="ecproject+desc"; $sortchar = "down";}else{ $sortstring="ecproject"; $sortchar = "up";} ?>
		<th><a class="sortlink" href="<?=$sorturl?>&s=<?=$sortstring?>">Method</a></th>
		
		<? if($sort=="material"){ $sortstring="material+desc"; $sortchar = "down";}else{ $sortstring="material"; $sortchar = "up";} ?>
		<th><a class="sortlink" href="<?=$sorturl?>&s=<?=$sortstring?>">Material</a></th>
		
		<? if($sort=="age_value"){ $sortstring="age_value+desc"; $sortchar = "down";}else{ $sortstring="age_value"; $sortchar = "up";} ?>
		<th><a class="sortlink" href="<?=$sorturl?>&s=<?=$sortstring?>"><div style="text-transform:none;">AGE&nbsp;(Ma)</div></a></th>
		
		<? if($sort=="one_sigma"){ $sortstring="one_sigma+desc"; $sortchar = "down";}else{ $sortstring="one_sigma"; $sortchar = "up";} ?>
		<th><a class="sortlink" href="<?=$sorturl?>&s=<?=$sortstring?>"><div style="text-transform:none;">&plusmn;2&sigma;&nbsp;(abs)</div></a></th>
		
		<? if($sort=="age_name"){ $sortstring="age_name+desc"; $sortchar = "down";}else{ $sortstring="age_name"; $sortchar = "up";} ?>
		<th><a class="sortlink" href="<?=$sorturl?>&s=<?=$sortstring?>">Age Type</a></th>
		

		
		<? if($sort=="laboratoryname"){ $sortstring="laboratoryname+desc"; $sortchar = "down";}else{ $sortstring="laboratoryname"; $sortchar = "up";} ?>
		<th nowrap><a class="sortlink" href="<?=$sorturl?>&s=<?=$sortstring?>">Lab Name</a></th>
		
		<? if($sort=="analyst_name"){ $sortstring="analyst_name+desc"; $sortchar = "down";}else{ $sortstring="analyst_name"; $sortchar = "up";} ?>
		<th nowrap><a class="sortlink" href="<?=$sorturl?>&s=<?=$sortstring?>">Analyst Name</a></th>

    </tr>
    
    
    
    <?
    
		foreach($myrows as $myrow){
		if($myrow->ecproject=="redux"){
			$showproject="U-Pb_Redux";
		}else{
			$showproject=$myrow->ecproject;
		}
		
		$myrow->igsn=str_replace("SSR.","",$myrow->igsn);
		$myrow->igsn=str_replace("GCH.","",$myrow->igsn);

		
		$method="";
		if($myrow->ecproject=="redux"){
			$method="U-Pb";
		}
		if($myrow->ecproject=="igor"){
			$method="U-Pb";
		}
		if($myrow->ecproject=="arar"){
			$method="Ar-Ar";
		}
		if($myrow->ecproject=="ararxls"){
			$method="Ar-Ar";
		}
		if($myrow->ecproject=="helios"){
			$method="(U-Th)He";
		}
		if($myrow->ecproject=="zips"){
			$method="U-Pb&nbsp;(ZIPS)";
		}
		if($myrow->ecproject=="uthhelegacy"){
			$method="(U-Th)He";
		}
		if($myrow->ecproject=="squid"){
			$method="SQUID";
		}
		if($myrow->ecproject=="fissiontrack"){
			$method="Fission Track";
		}

		$sample_pkey=$myrow->sample_pkey;
		
		$showage=$myrow->age_value;
		$showonesigma=$myrow->one_sigma;
		
		if($myrow->ecproject=="redux"){
			if($showage!=""){
				$showage=$showage/1000000;
				$showonesigma=$showonesigma/1000000;
			}else{
				$showonesigma="";
			}
		}
		
		if($myrow->ecproject=="squid"){
			if($showage!=""){
				$showage=$showage/1000000;
			}else{
				$showonesigma="";
			}
		}
		


		if($myrow->upstream=="f"){
		$showage=sigorigval($showage,$showonesigma,2);
		$showonesigma=sigerrval($showonesigma,2);
		}else{
		$showage="";
		$showonesigma="";
		}

		?>    
		
		<tr>
			<!--
			<td colspan=2 style="white-space:nowrap"><a href="javascript:popwindow('viewfile.php?pkey=<?=$myrow->sample_pkey?>');">view&nbsp;file</a> &nbsp; <a target="_blank" href="downloadfile.php?pkey=<?=$myrow->sample_pkey?>">download</a></td>
			-->
			<td nowrap valign="bottom"><a onClick="showfracs('<?=$sample_pkey?>');"><img id="img<?=$sample_pkey?>" src="images/rightarrow.gif"></a></td>
			<td class="aboutpage" colspan=2 style="white-space:nowrap"><a href="viewfile.php?pkey=<?=$myrow->sample_pkey?>" target="_blank">view&nbsp;file</a> &nbsp; <a target="_blank" href="downloadfile.php?pkey=<?=$myrow->sample_pkey?>">download</a></td>
			<td nowrap><?=$myrow->sample_id?></td>
			<td class="aboutpage"><a href="javascript:popwindow('viewid.php?id=<?=$myrow->igsn?>');"><?=$myrow->igsn?></a></td>
			<td nowrap><?=$method?></td>
			<td><?=$myrow->material?></td>

			
			<td><?=$showage?></td>
			
			<td><?=$showonesigma?></td>
			
			<td nowrap><?=$myrow->age_name?></td>

			<!--
			<td><a href="viewid.php?id=<?=$myrow->igsn?>" target="_blank"><?=$myrow->igsn?></a></td>
			-->
			<!--
			<td><a href="javascript:popwindow('viewsesar.php?igsn=<?=$myrow->parentigsn?>');"><?=$myrow->parentigsn?></a></td>
			-->
			<td style="white-space:nowrap"><?=$myrow->laboratoryname?></td>
			<td style="white-space:nowrap"><?=$myrow->analyst_name?></td>
	
		</tr>
		  
		<tr id="row<?=$sample_pkey?>" style="display:none;">
			<td style="background-color:#eef5fc;">
				&nbsp;
			</td>
			<td colspan="11">
				<div id ="fracdiv<?=$sample_pkey?>" style="padding:10px 10px 10px 30px;">
	
	
	
	
	
	
	
	
	
	
	
	
	
	
				</div>
			</td>
		</tr>
		  
		<?
		}//end foreach myrows


?>
  </table>
  
<?
//pagination here
echo "<br>".$pages->display_pages()."<span style=\"margin-left:25px\"> ".$pages->display_jump_menu()."&nbsp;&nbsp;".$pages->display_items_per_page() . "</span>";
?>
<br><br>
<INPUT TYPE="button" value="Interactive Map" onClick="parent.location='geochroninteractivemap.php?pkey=<?=$pkey?>'">
<INPUT TYPE="button" value="Download Excel File" onClick="parent.location='/searchxls/<?=$pkey?>'">
</td>
</tr>
</table>



<?
}else{
	echo "<br><br><h1>No Results Found.</h1>";
}//end if count myrows > 0
?>

</div>

  <div id="debug" style="display: none"> <br>
	<? print_r($_SESSION); ?>
    <br><br><br>
	<?=nl2br($querystring);?>
  </div>

<?
include("includes/geochron-secondary-footer.htm");
?>