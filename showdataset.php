<?PHP
/**
 * showdataset.php
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

function getElementFromDom($varname,$dom){
	$elements=$dom->getElementsByTagName($varname);

	$valarray=array();
	
	foreach($elements as $element){
		$valarray[]=$element->textContent;
	}
	
	$value = pg_escape_string($valarray[0]);
	
	return $value;
}

session_start();
include("db.php");

$fromsearch=$_GET['fromsearch'];

if($fromsearch=="yes"){

	$type="xls";
	$pkey=$_GET['pkey'];
	
	include("buildquery.php");

	$querystring=$newquerystring;
	
	//echo nl2br($querystring); exit();

}else{// get dataset stuff instead

	$type=$_GET['type'];
	$id=$_GET['id'];
	
	$dataset_row=$db->get_row("select * from datasets where linkstring='$id'");
	
	$dataset_pkey=$dataset_row->dataset_pkey;
	$datasetname=$dataset_row->datasetname;
	
	if($dataset_pkey==""){
		echo "dataset not found.";
		exit();
	}
	
	
	
	$querystring="select 
					sample.*, 
					age_min, 
					age_max, 
					age_value, 
					one_sigma, 
					age_name,
					getagetypes(sample.sample_pkey) as agetypes,
					datasetrelate.dataset_pkey
					from 
					sample
					left join sample_age on sample.sample_pkey = sample_age.sample_pkey
					left join users on sample.userpkey = users.users_pkey
					left join datasetrelate on sample.sample_pkey = datasetrelate.sample_pkey
					where 
					datasetrelate.dataset_pkey = $dataset_pkey";

}//end if fromsearch

if($type=="html"){


		
		//build sort url here
		$sorturl=$_SERVER['PHP_SELF']."?";
		$sortdelim="";
		foreach($_GET as $key=>$value){
			if($key!="s" && $key!="page" && $key!="yipp"){
				$sorturl.=$sortdelim.$key."=".$value;
				$sortdelim="&";
			}
		}
		
		
		
		
		include("includes/geochron-secondary-header.htm");
		?>
		<script language="JavaScript" type="text/JavaScript">
		function showdebug(){
			document.getElementById('debug').style.display='block';
		}
		</script>
		
		<SCRIPT type="text/javascript" src="/prototype.js"></SCRIPT>
		
		<script language="JavaScript" type="text/JavaScript">
		<!--
		
		
		
		function showfracs(id) {
			var thsObj = document.getElementById('row'+id);
			var imgObj = document.getElementById('img'+id);
			
			if(thsObj.style.display == 'table-row') {
				thsObj.style.display = 'none';
				imgObj.src = '/rightarrow.gif';
			}else{
				//do AJAX call here to get fraction information
		
				var url = '/getfractions.php';
		
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
				imgObj.src = '/downarrow.gif';
			}
		}
		
		//-->
		</script>
		
		<style type="text/css">
		.paginate {
			border-style: solid;
			border-width: 1px;
			border-color: #999999;
			color: #333333;
			padding: 2px 3px 2px 3px;
			background-color: #FFFFFF; /*f0f4f5;*/
			margin:0px 0px 0px 0px;
			text-decoration:none;
			font-weight:bold;
		}
		.current {
			border-style: solid;
			border-width: 1px;
			border-color: #999999;
			color: #333333;
			padding: 2px 3px 2px 3px;
			background-color: #99CCFF; /*f0f4f5;*/
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
		
		
		 
		 <?
		 /*
		 &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href="geochroninteractivemap.php?pkey=<?=$_GET['pkey']?>">view samples on interactive map</a>
		 */
		 

			
			//echo nl2br($querystring)." <br><br>";
			
			$totalcount=$db->get_var("select count(*) from ($querystring) foo");
		
			
			//echo "$querystring order by aliquot_pkey desc limit $numtoshow offset $offset;";
		 
		 
		 ?>
		 
		 
		 <p>
		 
		
		<h1>Dataset: <?=$datasetname?> (<?=$totalcount?> Samples)</h1>
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
			var url = '/getfractions.php';
			
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
				imgObj.src = '/downarrow.gif';
		
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
				imgObj.src = '/rightarrow.gif';
		
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

				$myrow->igsn=str_replace("SSR.","",$myrow->igsn);
				$myrow->igsn=str_replace("GCH.","",$myrow->igsn);

				if($myrow->ecproject=="redux"){
					$showproject="U-Pb_Redux";
				}else{
					$showproject=$myrow->ecproject;
				}
				
				$method="";
				if($myrow->ecproject=="redux"){
					$method="U-Pb";
				}
				if($myrow->ecproject=="arar"){
					$method="Ar-Ar";
				}
				if($myrow->ecproject=="helios"){
					$method="(U-Th)He";
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
		
				$showage=sigorigval($showage,$showonesigma,2);
				$showonesigma=sigerrval($showonesigma,2);
		
				?>    
				
				<tr>
					<!--
					<td colspan=2 style="white-space:nowrap"><a href="javascript:popwindow('viewfile.php?pkey=<?=$myrow->sample_pkey?>');">view&nbsp;file</a> &nbsp; <a target="_blank" href="downloadfile.php?pkey=<?=$myrow->sample_pkey?>">download</a></td>
					-->
					<td nowrap valign="bottom"><a onClick="showfracs('<?=$sample_pkey?>');"><img id="img<?=$sample_pkey?>" src="/rightarrow.gif"></a></td>
					<td class="aboutpage" colspan=2 style="white-space:nowrap"><a href="/viewfile.php?pkey=<?=$myrow->sample_pkey?>" target="_blank">view&nbsp;file</a> &nbsp; <a target="_blank" href="/downloadfile.php?pkey=<?=$myrow->sample_pkey?>">download</a></td>
					<td nowrap><?=$myrow->sample_id?></td>
					<td class="aboutpage"><a href="javascript:popwindow('/viewid.php?id=<?=$myrow->igsn?>');"><?=$myrow->igsn?></a></td>
					<td><?=$method?></td>
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
		<!--
		<INPUT TYPE="button" value="Interactive Map" onClick="parent.location='geochroninteractivemap.php?pkey=<?=$pkey?>'">
		-->
		<INPUT TYPE="button" value="Download Excel File" onClick="parent.location='/dataset/xls/<?=$id?>'">
		</td>
		</tr>
		</table>
		
		</div>
		
		<?
		}//end if count myrows > 0
		?>
		
		  <div id="debug" style="display: none"> <br>
			<? print_r($_SESSION); ?>
			<br><br><br>
			<?=nl2br($querystring);?>
		  </div>
		
		<?
		include("includes/geochron-secondary-footer.htm");

}elseif($type=="xls"){


	//log download here
	$downloadtype="xls download";
	include("loghit.php");




		
		
		
		
		
		
		

		
		
		
		
		
		//echo nl2br($resultstring);
		//exit();
			
		$rows=$db->get_results($querystring);
		
					// Include PEAR::Spreadsheet_Excel_Writer
					require_once "Spreadsheet/Excel/Writer.php";
					
					// Create an instance
					$xls =& new Spreadsheet_Excel_Writer();
					
					$filename=$datasetname;
					$filename=str_replace("","_",$filename);
					
					// Send HTTP headers to tell the browser what's coming
					


					//write header
					if($fromsearch!="yes"){
						$xls->send("Geochron_Dataset_$filename.xls");
					}else{
						$xls->send("Geochron_Search_Query_Download.xls");
					}


					// Add a worksheet to the file, returning an object to add data to
					$sheet =& $xls->addWorksheet('Samples');
					
					$formatwhiteblue =& $xls->addFormat();
					$formatwhiteblue->setFgColor(63); //30
					$formatwhiteblue->setColor('white');
					$formatwhiteblue->setBorder(1);
					
					$formatwhite =& $xls->addFormat();
					$formatwhite->setBorder(1);
					
					$formatfrachead =& $xls->addFormat();
					$formatfrachead->setFGColor(27);
					$formatfrachead->setBorder(1);
					
					$formatfrac =& $xls->addFormat();
					$formatfrac->setFGColor(26);
					$formatfrac->setBorder(1);
					
					$formathead =& $xls->addFormat();
					$formathead->setColor(63); //30
					$formathead->setSize(18);
					$formathead->setBold(700);
					$formathead->setItalic();
					
					$formatinstr =& $xls->addFormat();
					$formatinstr->setTextWrap();
					$formatinstr->setVAlign('top');
					
					$formathostrock =& $xls->addFormat();
					$formathostrock->setColor('black');
					$formathostrock->setFgColor(44); //30 62 60
					$formathostrock->setBold(700);
					$formathostrock->setHAlign('center');
					
					
					$formatfraction =& $xls->addFormat();
					$formatfraction->setColor('black');
					$formatfraction->setFgColor(45); //30 62 60
					$formatfraction->setBold(700);
					$formatfraction->setHAlign('center');
		
					//write header
					if($fromsearch!="yes"){
						$sheet->write(0,0,"Geochron Dataset Download",$formathead);
					}else{
						$sheet->write(0,0,"Geochron Search Query Download",$formathead);
					}
		
					//$columnnames=array("Sample ID","Unique ID       ","Sample Description","Sample Comment","Longitude","Latitude","Min Age (Ma)","Max Age (Ma)","Detrital Method","Detrital Type","Detrital Mineral","Stratigraphic Formation Name","Oldest Frac. Date (Ma)","Youngest Frac. Date (Ma)","Metadata","Concordia Diagram","Probability Density","CSV Table","GeoObject Type","GeoObject Class","Collection Method","Analyst Name","Laboratory Name","Collector","Rock Type","Primary Location Name","Primary Location Type","Location Description","Locality","Locality Description","Country","Province","County","City or Township","Platform","Platform ID","Original Archival Institution","Original Archival Contact","Most Recent Archival Institution","Most Recent Archival Contact");
					//$columnnames=array("Sample ID","Unique ID       ","Sample Description","Sample Comment","Longitude","Latitude","Min Age (Ma)","Max Age (Ma)","Stratigraphic Formation Name","Oldest Frac. Date (Ma)","Youngest Frac. Date (Ma)","Metadata","Concordia Diagram","Probability Density","CSV Table","GeoObject Type","GeoObject Class","Collection Method","Analyst Name","Laboratory Name","Collector","Rock Type","Primary Location Name","Primary Location Type","Location Description","Locality","Locality Description","Country","Province","County","City or Township","Platform","Platform ID","Original Archival Institution","Original Archival Contact","Most Recent Archival Institution","Most Recent Archival Contact");
					//$columnnames=array("Sample ID","Unique ID       ","Sample Description","Sample Comment","Longitude","Latitude","Method","Material","Age","Age Error","Age Type","GeoObject Type","GeoObject Class","Collection Method","Analyst Name","Laboratory Name","Collector","Rock Type","Primary Location Name","Primary Location Type","Location Description","Locality","Locality Description","Country","Province","County","City or Township","Platform","Platform ID","Original Archival Institution","Original Archival Contact","Most Recent Archival Institution","Most Recent Archival Contact");
					$columnnames=array("Sample ID","IGSN       ","Metadata URL    ","Sample Description","Sample Comment","Longitude","Latitude","Method","Material","Age (Ma)",chr(177)." 2s (abs)","Age Type","GeoObject Type","GeoObject Class","Collection Method","Analyst Name","Laboratory Name","Collector","Rock Type","Primary Location Name","Primary Location Type","Location Description","Locality","Locality Description","Country","Province");
		
					$colnum=0;
					foreach($columnnames as $columnname){
						$thisheader=$columnname;
						$thiswidth=strlen($thisheader)-1;
						if($thisheader=="Age (Ma)"){$thiswidth="12";}
						if($thiswidth<10){
							$thiswidth=10;
						}
						$sheet->write(6,$colnum,$thisheader,$formatwhiteblue);
						$sheet->setColumn($colnum,$colnum,$thiswidth);
						$colnum++;
					}
					
					$sheet->setColumn($colnum,$colnum,12);
					
					for ( $i=7;$i<106;$i++ ) {
						for($j=0;$j<3;$j++){
							//$sheet->writeBlank($i,$j,$formatwhite);
						}
		}
		
					$y=7;
					if(count($rows)>0){
						
						$showheader="no";
					
						foreach($rows as $row){

							$row->igsn=str_replace("SSR.","",$row->igsn);
							$row->igsn=str_replace("GCH.","",$row->igsn);

							if($showheader=="yes"){
								$colnum=0;
								foreach($columnnames as $columnname){
									$thisheader=$columnname;
									$sheet->write($y,$colnum,$thisheader,$formatwhiteblue);
									$colnum++;
								}
								$y++;
							}
		
							$showage=$row->age_value;
							$showonesigma=$row->one_sigma;
							
							if($row->ecproject=="redux"){
								if($showage!=""){
								
									$showage=sigorigval($showage/1000000,$showonesigma/1000000,6);
									$showonesigma=sigerrval($showonesigma/1000000,6);

								}
							}
		
							if($row->upstream=="f"){
								$showage=sigorigval($showage,$showonesigma,6);
								$showonesigma=sigerrval($showonesigma,6);
							}else{
								$showage="";
								$showonesigma="";
							}	
	
							if($row->ecproject=="redux"){
								$detritalmethod="U-Pb";
							}elseif($row->ecproject=="helios"){
								$detritalmethod="(U-Th)/He";
							}elseif($row->ecproject=="arar"){
								$detritalmethod="Ar-Ar";
							}
		
		
		
		
							if($row->ecproject=="redux"){
								$showproject="U-Pb_Redux";
							}else{
								$showproject=$row->ecproject;
							}
							
							$method="";
							if($row->ecproject=="redux"){
								$method="U-Pb";
							}
							if($row->ecproject=="arar"){
								$method="Ar-Ar";
							}
							if($row->ecproject=="helios"){
								$method="(U-Th)He";
							}
		
		
		
		
		
							if($row->strat_name!=""){
								$showstratname=$row->strat_name;
							}else{
								$showstratname="n/a";
							}
		
							$sheet->write($y,0,$row->sample_id,$formatwhite);
							$sheet->write($y,1,$row->igsn,$formatwhite);
							$sheet->writeUrl($y,2,"http://www.geochron.org/viewid.php?id=".$row->igsn); //http://www.geochron.org/viewid.php?id=
		
							$sheet->write($y,3,$row->sample_description,$formatwhite);
							$sheet->write($y,4,$row->sample_comment,$formatwhite);
							$sheet->write($y,5,$row->longitude,$formatwhite);
							$sheet->write($y,6,$row->latitude,$formatwhite);
							$sheet->write($y,7,$method,$formatwhite);
		
							//material
							//age
							//age_err
							//age type
							
							$sheet->write($y,8,$row->material,$formatwhite);
							$sheet->write($y,9,$showage,$formatwhite);
							$sheet->write($y,10,$showonesigma,$formatwhite);
							$sheet->write($y,11,$row->age_name,$formatwhite);					
							
							//$sheet->write($y,6,round($row->age_min,0),$formatwhite);
							//$sheet->write($y,7,round($row->age_max,0),$formatwhite);
							
							////////////$sheet->write($y,8,$detritalmethod,$formatwhite);
							////////////$sheet->write($y,9,$row->detrital_type,$formatwhite);
							////////////$sheet->write($y,10,$row->material,$formatwhite);
		
							//$sheet->write($y,8,$showstratname,$formatwhite);
							//$sheet->write($y,9,round($row->oldest_frac_date/1000000,0),$formatwhite);
							//$sheet->write($y,10,round($row->youngest_frac_date/1000000,0),$formatwhite);
		
							//$sheet->writeUrl($y,9,"http://www.geochron.org/m/$row->sample_pkey","http://www.geochron.org/m/$row->sample_pkey");
							//$sheet->writeUrl($y,10,"http://www.geochron.org/c/$row->sample_pkey","http://www.geochron.org/c/$row->sample_pkey");
							//$sheet->writeUrl($y,11,"http://www.geochron.org/pd/$row->sample_pkey","http://www.geochron.org/pd/$row->sample_pkey");
							//$sheet->writeUrl($y,12,"http://www.geochron.org/csv/$row->sample_pkey","http://www.geochron.org/csv/$row->sample_pkey");
		
							$sheet->write($y,12,$row->geoobjecttype,$formatwhite);
							$sheet->write($y,13,$row->geoobjectclass,$formatwhite);
							$sheet->write($y,14,$row->collectionmethod,$formatwhite);
							$sheet->write($y,15,$row->analyst_name,$formatwhite);
							$sheet->write($y,16,$row->laboratoryname,$formatwhite);
							$sheet->write($y,17,$row->collector,$formatwhite);
							$sheet->write($y,18,$row->rocktype,$formatwhite);
							$sheet->write($y,19,$row->primarylocationname,$formatwhite);
							$sheet->write($y,20,$row->primarylocationtype,$formatwhite);
							$sheet->write($y,21,$row->locationdescription,$formatwhite);
							$sheet->write($y,22,$row->locality,$formatwhite);
							$sheet->write($y,23,$row->localitydescription,$formatwhite);
							$sheet->write($y,24,$row->country,$formatwhite);
							$sheet->write($y,25,$row->provice,$formatwhite);
							//$sheet->write($y,26,$row->county,$formatwhite);
							//$sheet->write($y,27,$row->cityortownship,$formatwhite);
							//$sheet->write($y,28,$row->platform,$formatwhite);
							//$sheet->write($y,29,$row->platformid,$formatwhite);
							//$sheet->write($y,30,$row->originalarchivalinstitution,$formatwhite);
							//$sheet->write($y,31,$row->originalarchivalcontact,$formatwhite);
							//$sheet->write($y,32,$row->mostrecentarchivalinstitution,$formatwhite);
							//$sheet->write($y,33,$row->mostrecentarchivalcontact,$formatwhite);
							
							//$y++;
							//$sheet->write($y,1,"Foo Foo $ecproject");
							
							//now do fractions
							$filename=$row->filename;
							$ecproject=$row->ecproject;
							
							$dom = new DomDocument;
							$xmlfile = "files/$filename";
							
							$showtable="no";
							
							$rows="";
							
							//$sheet->write($y+1,0,"Foo");
							$sheet->writeUrl($y+2,0,"http://www.geochron.org/downloadfile.php?pkey=$row->sample_pkey","http://www.geochron.org/downloadfile.php?pkey=$row->sample_pkey");
							
							if($dom->Load($xmlfile)){
		
								//$y++;
								//$sheet->write($y,1,"Foo Foo $ecproject");
		
								if($ecproject=="redux"){
								





									//figure out if this is a fraction used in age calculation
									//first, build array of fractionids that were used
									
									unset($fractionidarray);
									$fractionidarray=array();
									
									$mypreferred="false";
									
									$sampledatemodels = $dom->getElementsByTagName("SampleDateModel");
							
									foreach($sampledatemodels as $sampledatemodel){
									
										$preferreds=$sampledatemodel->getElementsByTagName("preferred");
										
										foreach($preferreds as $preferred){
											$mypreferred=$preferred->textContent;
										}//end foreach analysisfraction
										
										if($mypreferred=="true"){
											
											$includedfractionsvectors=$sampledatemodel->getElementsByTagName("includedFractionsVector");
											
											foreach($includedfractionsvectors as $includedfractionsvector){
												
												$fractionids=$includedfractionsvector->getElementsByTagName("fractionID");
												
												foreach($fractionids as $fractionid){
												
													$fractionidarray[]=$fractionid->textContent;
												
												}
											}
										}
										
									}//end foreach aliquot


									$fraclist=implode(",",$fractionidarray);

	
									$y++;
									$y++;
									$sheet->write($y,1,"Included in Age Interp?:",$formatfrachead);
									//$sheet->write($y,1,"$fraclist");
									$sheet->write($y,2,"Fraction ID",$formatfrachead);
									$sheet->write($y,3,"206/238 Date",$formatfrachead);
									$sheet->write($y,4,chr(177)." 2s (abs)",$formatfrachead);
									$sheet->write($y,5,"207/235 Date",$formatfrachead);
									$sheet->write($y,6,chr(177)." 2s (abs)",$formatfrachead);
									$sheet->write($y,7,"207/206 Date",$formatfrachead);
									$sheet->write($y,8,chr(177)." 2s (abs)",$formatfrachead);
									$sheet->write($y,9,"Pb*/Pbc",$formatfrachead);
									//$sheet->write($y,10,"Pb*/Pbc Error",$formatfrachead);

									
		
									//additional
									$sheet->write($y,10,"206/238",$formatfrachead);
									$sheet->write($y,11,chr(177)." 2s (%)",$formatfrachead);
									

									
									$sheet->write($y,12,"207/235",$formatfrachead);
									$sheet->write($y,13,chr(177)." 2s (%)",$formatfrachead);

									$sheet->write($y,14,"rho 206/238-207/235",$formatfrachead);

									$sheet->write($y,15,"207/206",$formatfrachead);
									$sheet->write($y,16,chr(177)." 2s (%)",$formatfrachead);

									$sheet->write($y,17,"208/206",$formatfrachead);
									//$sheet->write($y,18,chr(177)." 2s (%)",$formatfrachead);



									//$sheet->write($y,19,"206/204",$formatfrachead);

									//$sheet->write($y,18,"conc U",$formatfrachead);
									$sheet->write($y,18,"Th/U samp",$formatfrachead);
									$sheet->write($y,19,"206/238xTh Date",$formatfrachead);
									$sheet->write($y,20,chr(177)." 2s (abs)",$formatfrachead);

									$sheet->write($y,21,"207/235xPa Date",$formatfrachead);
									$sheet->write($y,22,chr(177)." 2s (abs)",$formatfrachead);
									$sheet->write($y,23,"207/206xTh Date",$formatfrachead);
									$sheet->write($y,24,chr(177)." 2s (abs)",$formatfrachead);
									$sheet->write($y,25,"207/206xPa Date",$formatfrachead);
									$sheet->write($y,26,chr(177)." 2s (abs)",$formatfrachead);
								
									$aliquots = $dom->getElementsByTagName("Aliquot");
							
									foreach($aliquots as $aliquot){
									
										$analysisfractions=$aliquot->getElementsByTagName("AnalysisFraction");
										
										foreach($analysisfractions as $analysisfraction){
										
											$showrow="no";
											
											$myfractionid="";
											
											$age206_238r="";
											$age207_235r="";
											$age207_206r="";
								
											$age206_238rerr="";
											$age207_235rerr="";
											$age207_206rerr="";
											
											$radtocommontotal="";
											$radtocommontotalerr="";
											
											$rho="";
											$rhoerr="";
		
											//additional items...
											$r206_238r="";
											$r206_238rerr="";
											$r207_206r="";
											$r207_206rerr="";
											$r207_235r="";
											$r207_235rerr="";
											

											$r208_206r="";
											$r208_206rerr="";
											
											$r206_204r="";
											$concu="";
											$rth_usample="";
											$age207_235r="";
											$age207_235rerr="";
											$age207_206r="";
											$age207_206rerr="";
											$age206_238r_th="";
											$age206_238r_therr="";
											$age207_235r_pa="";
											$age207_235r_paerr="";
											$age207_206r_th="";
											$age207_206r_therr="";
											$age207_206r_pa="";
											$age207_206r_paerr="";
		
											//fractionID
											$fractionids = $analysisfraction->getElementsByTagName("fractionID");
											foreach($fractionids as $fractionid){
												$myfractionid = $fractionid->textContent;
											}
											
											//check to see if fractionid is used in age interpretation
											if(in_array($myfractionid,$fractionidarray)){
												$agein="Yes";
											}else{
												$agein="No";
											}
											
											//radiogenicIsotopeDates
											$raddates = $analysisfraction->getElementsByTagName("radiogenicIsotopeDates");
											foreach($raddates as $raddate){
												
												//age206_238r
												//age207_235r
												//age207_206r
												
												$valuemodels = $raddate->getElementsByTagName("ValueModel");
												foreach($valuemodels as $valuemodel){
												
													$myname="";
													$myvalue="";
													$myuncertaintytype="";
													$myonesigma="";
													
													$names = $valuemodel->getElementsByTagName("name");
													foreach($names as $name){
														$myname=$name->textContent;
													}
													
													$values = $valuemodel->getElementsByTagName("value");
													foreach($values as $value){
														$myvalue=$value->textContent;
													}
													
													$uncertaintytypes = $valuemodel->getElementsByTagName("uncertaintyType");
													foreach($uncertaintytypes as $uncertaintytype){
														$myuncertaintytype=$uncertaintytype->textContent;
													}
													
													$onesigmas = $valuemodel->getElementsByTagName("oneSigma");
													foreach($onesigmas as $onesigma){
														$myonesigma=$onesigma->textContent;
													}

													/*
													$age206_238r=sigorigval($myvalue/1000000,$myonesigma/1000000,6);
													$age206_238rerr=sigerrval($myonesigma/1000000,6);
													*/

													if($myname=="age206_238r"){
														$age206_238r=sigorigval($myvalue/1000000,$myonesigma/1000000,6);
														$age206_238rerr=sigerrval($myonesigma/1000000,6);
														$showrow="yes";
													}
													
													if($myname=="age207_235r"){
														$age207_235r=sigorigval($myvalue/1000000,$myonesigma/1000000,6);
														$age207_235rerr=sigerrval($myonesigma/1000000,6);
														$showrow="yes";
													}
													
													if($myname=="age207_206r"){
														$age207_206r=sigorigval($myvalue/1000000,$myonesigma/1000000,6);
														$age207_206rerr=sigerrval($myonesigma/1000000,6);
														$showrow="yes";
													}
		
													//additional...
		
													if($myname=="age207_235r"){
														$age207_235r=sigorigval($myvalue/1000000,$myonesigma/1000000,6);
														$age207_235rerr=sigerrval($myonesigma/1000000,6);
														$showrow="yes";
													}
													
													if($myname=="age207_206r"){
														$age207_206r=sigorigval($myvalue/1000000,$myonesigma/1000000,6);
														$age207_206rerr=sigerrval($myonesigma/1000000,6);
														$showrow="yes";
													}
													
													if($myname=="age206_238r_Th"){
														$age206_238r_th=sigorigval($myvalue/1000000,$myonesigma/1000000,6);
														$age206_238r_therr=sigerrval($myonesigma/1000000,6);
														$showrow="yes";
													}
													
													if($myname=="age207_235r_Pa"){
														$age207_235r_pa=sigorigval($myvalue/1000000,$myonesigma/1000000,6);
														$age207_235r_paerr=sigerrval($myonesigma/1000000,6);
														$showrow="yes";
													}
													
													if($myname=="age207_206r_Th"){
														$age207_206r_th=sigorigval($myvalue/1000000,$myonesigma/1000000,6);
														$age207_206r_therr=sigerrval($myonesigma/1000000,6);
														$showrow="yes";
													}
													
													if($myname=="age207_206r_Pa"){
														$age207_206r_pa=sigorigval($myvalue/1000000,$myonesigma/1000000,6);
														$age207_206r_paerr=sigerrval($myonesigma/1000000,6);
														$showrow="yes";
													}
		
												
												}//end foreach valuemodel
												
											}//end foreach radisodates
											
											
											
											//compositionalMeasures
											$compositionalmeasures = $analysisfraction->getElementsByTagName("compositionalMeasures");
											foreach($compositionalmeasures as $compositionalmeasure){
												
												//radToCommonTotal
												
												$valuemodels = $compositionalmeasure->getElementsByTagName("ValueModel");
												foreach($valuemodels as $valuemodel){
												
													$myname="";
													$myvalue="";
													$myuncertaintytype="";
													$myonesigma="";
													
													$names = $valuemodel->getElementsByTagName("name");
													foreach($names as $name){
														$myname=$name->textContent;
													}
													
													$values = $valuemodel->getElementsByTagName("value");
													foreach($values as $value){
														$myvalue=$value->textContent;
													}
													
													$uncertaintytypes = $valuemodel->getElementsByTagName("uncertaintyType");
													foreach($uncertaintytypes as $uncertaintytype){
														$myuncertaintytype=$uncertaintytype->textContent;
													}
													
													$onesigmas = $valuemodel->getElementsByTagName("oneSigma");
													foreach($onesigmas as $onesigma){
														$myonesigma=$onesigma->textContent;
													}
													
													if($myname=="radToCommonTotal"){
														$radtocommontotal=sigaloneval($myvalue,6);
														$radtocommontotalerr=sigerrval($myonesigma,6);
														$showrow="yes";
													}
		
													//additional...
													if($myname=="concU"){
														$concu=$myvalue;
														$showrow="yes";
													}
													
													if($myname=="rTh_Usample"){
														$rth_usample=sigaloneval($myvalue,6);
														$showrow="yes";
													}
		
												}//end foreach valuemodel
												
											}//end foreach compositionalmeasures
											
											
											
											
											
											
											
											
											
											
											//radiogenicIsotopeRatios
											$radiogenicisotoperatios = $analysisfraction->getElementsByTagName("radiogenicIsotopeRatios");
											foreach($radiogenicisotoperatios as $radiogenicisotoperatio){
												
												//rhoR206_238r__r207_235r
												
												$valuemodels = $radiogenicisotoperatio->getElementsByTagName("ValueModel");
												foreach($valuemodels as $valuemodel){
												
													$myname="";
													$myvalue="";
													$myuncertaintytype="";
													$myonesigma="";
													
													$names = $valuemodel->getElementsByTagName("name");
													foreach($names as $name){
														$myname=$name->textContent;
													}
													
													$values = $valuemodel->getElementsByTagName("value");
													foreach($values as $value){
														$myvalue=$value->textContent;
													}
													
													$uncertaintytypes = $valuemodel->getElementsByTagName("uncertaintyType");
													foreach($uncertaintytypes as $uncertaintytype){
														$myuncertaintytype=$uncertaintytype->textContent;
													}
													
													$onesigmas = $valuemodel->getElementsByTagName("oneSigma");
													foreach($onesigmas as $onesigma){
														$myonesigma=$onesigma->textContent;
														

													}

													if($myonesigma!="" && $myvalue!=""){
														//convert $myonesigma to percentage
														$myonesigma=$myonesigma/$myvalue*100;
													}
													
													if($myname=="rhoR206_238r__r207_235r"){
														$rho=sigaloneval($myvalue,6);
														$showrow="yes";
													}
		
													//additional
													if($myname=="r206_238r"){
														$r206_238r=sigorigval($myvalue,$myonesigma,6);
														$r206_238rerr=sigerrval(($myonesigma),6);
														
														$showrow="yes";
													}
													
													if($myname=="r207_206r"){
														$r207_206r=sigorigval($myvalue,$myonesigma,6);
														$r207_206rerr=sigerrval(($myonesigma),6);
														
														$showrow="yes";
													}
													
													if($myname=="r207_235r"){
														$r207_235r=sigorigval($myvalue,$myonesigma,6);
														$r207_235rerr=sigerrval(($myonesigma),6);
														
														$showrow="yes";
													}
													

													
													if($myname=="r208_206r"){
														$r208_206r=sigaloneval($myvalue,6);
														$r208_206rerr=sigerrval(($myonesigma),6);
														$showrow="yes";
													}

													if($myname=="r206_204r"){
														$r206_204r=sigorigval($myvalue,$myonesigma,6);
														$r206_204rerr=sigerrval(($myonesigma),6);
														$showrow="yes";
													}

												}//end foreach valuemodel
												
											}//end foreach radiogenicisotoperatios
								
								
								
								
								
								
								
								
											
											
											
											
											
											
											
											
											
											
											
											
											
											//echo fraction information
											
											/*
											echo "age206_238r:$age206_238r age206_238rerr=$age206_238rerr<br>";
											echo "age207_235r:$age207_235r age207_235rerr=$age207_235rerr<br>";
											echo "age207_206r:$age207_206r age207_206rerr=$age207_206rerr<br>";
											echo "radtocommontotal:$radtocommontotal radtocommontotalerr=$radtocommontotalerr<br>";
											echo "rho:$rho rhoerr=$rhoerr<br><br>";
											*/
											
											if($showrow=="yes"){
												$rows.="<tr>
															<td>$myfractionid</td>
															<td>$age206_238r</td><td>$age206_238rerr</td>
															<td>$age207_235r</td><td>$age207_235rerr</td>
															<td>$age207_206r</td><td>$age207_206rerr</td>
															<td>$radtocommontotal</td><td>$radtocommontotalerr</td>
															<td>$rho</td><td>$rhoerr</td>
														</tr>";
												$showtable="yes";
												
												$y++;
												$sheet->write($y,1,"$agein",$formatfrac);
												$sheet->write($y,2,"$myfractionid",$formatfrac);
												$sheet->write($y,3,"$age206_238r",$formatfrac);
												$sheet->write($y,4,"$age206_238rerr",$formatfrac);
												$sheet->write($y,5,"$age207_235r",$formatfrac);
												$sheet->write($y,6,"$age207_235rerr",$formatfrac);
												$sheet->write($y,7,"$age207_206r",$formatfrac);
												$sheet->write($y,8,"$age207_206rerr",$formatfrac);
												$sheet->write($y,9,"$radtocommontotal",$formatfrac);
												//$sheet->write($y,10,"$radtocommontotalerr",$formatfrac);

												
		
												//additional...
												$sheet->write($y,10,"$r206_238r",$formatfrac);
												$sheet->write($y,11,"$r206_238rerr",$formatfrac);

												$sheet->write($y,12,"$r207_235r",$formatfrac);
												$sheet->write($y,13,"$r207_235rerr",$formatfrac);

												$sheet->write($y,14,"$rho",$formatfrac);

												$sheet->write($y,15,"$r207_206r",$formatfrac);
												$sheet->write($y,16,"$r207_206rerr",$formatfrac);

												$sheet->write($y,17,"$r208_206r",$formatfrac);
												//$sheet->write($y,18,"$r208_206rerr",$formatfrac);

												
												
												//$sheet->write($y,19,"$r206_204r",$formatfrac);
												
												//$sheet->write($y,18,"$concu",$formatfrac);
												$sheet->write($y,18,"$rth_usample",$formatfrac);
												$sheet->write($y,19,"$age206_238r_th",$formatfrac);
												$sheet->write($y,20,"$age206_238r_therr",$formatfrac);

												$sheet->write($y,21,"$age207_235r_pa",$formatfrac);
												$sheet->write($y,22,"$age207_235r_paerr",$formatfrac);
												$sheet->write($y,23,"$age207_206r_th",$formatfrac);
												$sheet->write($y,24,"$age207_206r_therr",$formatfrac);
												$sheet->write($y,25,"$age207_206r_pa",$formatfrac);
												$sheet->write($y,26,"$age207_206r_paerr",$formatfrac);
		
											}//end if showrow
								
										}//end foreach analysisfractions
									
										if($showtable=="yes"){
		
										}else{
											//echo "No fraction data found.";
										}
										
										$y++;
										$y++;
									
									}//end foreach aliquots
								
								}elseif($ecproject=="igor"){

									$y++;
									$y++;

									$sheet->write($y,1,"Grain ID",$formatfrachead);
									$sheet->write($y,2,"206/238 Date",$formatfrachead);
									$sheet->write($y,3,chr(177)." 2s (abs)",$formatfrachead);
									$sheet->write($y,4,"207/235 Date",$formatfrachead);
									$sheet->write($y,5,chr(177)." 2s (abs)",$formatfrachead);
									$sheet->write($y,6,"207/206 Date",$formatfrachead);
									$sheet->write($y,7,chr(177)." 2s (abs)",$formatfrachead);
									$sheet->write($y,8,"208/232 Date",$formatfrachead);
									$sheet->write($y,9,chr(177)." 2s (abs)",$formatfrachead);
									$sheet->write($y,10,"206/238",$formatfrachead);
									$sheet->write($y,11,chr(177)." 2s (%)",$formatfrachead);
									$sheet->write($y,12,"207/235",$formatfrachead);
									$sheet->write($y,13,chr(177)." 2s (%)",$formatfrachead);
									$sheet->write($y,14,"207/206",$formatfrachead);
									$sheet->write($y,15,chr(177)." 2s (%)",$formatfrachead);
									$sheet->write($y,16,"208/232",$formatfrachead);
									$sheet->write($y,17,chr(177)." 2s (%)",$formatfrachead);
									$sheet->write($y,18,"rho 207/206-238/206",$formatfrachead);
									$sheet->write($y,19,"rho 206/238-207/235",$formatfrachead);
									$sheet->write($y,20,"Approx. Pb ppm",$formatfrachead);
									$sheet->write($y,21,chr(177)." 2s (abs)",$formatfrachead);
									$sheet->write($y,22,"Approx. Th ppm",$formatfrachead);
									$sheet->write($y,23,chr(177)." 2s (abs)",$formatfrachead);
									$sheet->write($y,24,"Approx. U ppm",$formatfrachead);
									$sheet->write($y,25,chr(177)." 2s (abs)",$formatfrachead);

									$sampledatas = $dom->getElementsByTagName("sampledata");
							
									foreach($sampledatas as $sampledata){
									
										$graindatas=$sampledata->getElementsByTagName("graindata");
										
										foreach($graindatas as $grain){

											$grainid = getElementFromDom("realgrain",$grain);
											$FinalAge206_238 = getElementFromDom("FinalAge206_238",$grain);
											$FinalAge206_238_Int2SE = getElementFromDom("FinalAge206_238_Int2SE",$grain);
											$FinalAge207_235 = getElementFromDom("FinalAge207_235",$grain);
											$FinalAge207_235_Int2SE = getElementFromDom("FinalAge207_235_Int2SE",$grain);
											$FinalAge207_206 = getElementFromDom("FinalAge207_206",$grain);
											$FinalAge207_206_Int2SE = getElementFromDom("FinalAge207_206_Int2SE",$grain);
											$Final206_238 = getElementFromDom("Final206_238",$grain);
											$Final206_238_Int2SE = getElementFromDom("Final206_238_Int2SE",$grain);
											$Final207_206 = getElementFromDom("Final207_206",$grain);
											$Final207_206_Int2SE = getElementFromDom("Final207_206_Int2SE",$grain);
											$Final207_235 = getElementFromDom("Final207_235",$grain);
											$Final207_235_Int2SE = getElementFromDom("Final207_235_Int2SE",$grain);
											$ErrorCorrelation_38_6vs7_6 = getElementFromDom("ErrorCorrelation_38_6vs7_6",$grain);
											$ErrorCorrelation_6_38vs7_35 = getElementFromDom("ErrorCorrelation_6_38vs7_35",$grain);
											$Final208_232 = getElementFromDom("Final208_232",$grain);
											$Final208_232_Int2SE = getElementFromDom("Final208_232_Int2SE",$grain);
											$FinalAge208_232 = getElementFromDom("FinalAge208_232",$grain);
											$FinalAge208_232_Int2SE = getElementFromDom("FinalAge208_232_Int2SE",$grain);
											$Approx_Pb_PPM = getElementFromDom("Approx_Pb_PPM",$grain);
											$Approx_Pb_PPM_Int2SE = getElementFromDom("Approx_Pb_PPM_Int2SE",$grain);
											$Approx_Th_PPM = getElementFromDom("Approx_Th_PPM",$grain);
											$Approx_Th_PPM_Int2SE = getElementFromDom("Approx_Th_PPM_Int2SE",$grain);
											$Approx_U_PPM = getElementFromDom("Approx_U_PPM",$grain);
											$Approx_U_PPM_Int2SE = getElementFromDom("Approx_U_PPM_Int2SE",$grain);

											$showtable="yes";
											
											$y++;

											$sheet->write($y,1,"$grainid",$formatfrac);
											$sheet->write($y,2,"$FinalAge206_238",$formatfrac);
											$sheet->write($y,3,"$FinalAge206_238_Int2SE",$formatfrac);
											$sheet->write($y,4,"$FinalAge207_235",$formatfrac);
											$sheet->write($y,5,"$FinalAge207_235_Int2SE",$formatfrac);
											$sheet->write($y,6,"$FinalAge207_206",$formatfrac);
											$sheet->write($y,7,"$FinalAge207_206_Int2SE",$formatfrac);
											$sheet->write($y,8,"$FinalAge208_232",$formatfrac);
											$sheet->write($y,9,"$FinalAge208_232_Int2SE",$formatfrac);
											$sheet->write($y,10,"$Final206_238",$formatfrac);
											$sheet->write($y,11,"$Final206_238_Int2SE",$formatfrac);
											$sheet->write($y,12,"$Final207_235",$formatfrac);
											$sheet->write($y,13,"$Final207_235_Int2SE",$formatfrac);
											$sheet->write($y,14,"$Final207_206",$formatfrac);
											$sheet->write($y,15,"$Final207_206_Int2SE",$formatfrac);
											$sheet->write($y,16,"$Final208_232",$formatfrac);
											$sheet->write($y,17,"$Final208_232_Int2SE",$formatfrac);
											$sheet->write($y,18,"$ErrorCorrelation_38_6vs7_6",$formatfrac);
											$sheet->write($y,19,"$ErrorCorrelation_6_38vs7_35",$formatfrac);
											$sheet->write($y,20,"$Approx_Pb_PPM",$formatfrac);
											$sheet->write($y,21,"$Approx_Pb_PPM_Int2SE",$formatfrac);
											$sheet->write($y,22,"$Approx_Th_PPM",$formatfrac);
											$sheet->write($y,23,"$Approx_Th_PPM_Int2SE",$formatfrac);
											$sheet->write($y,24,"$Approx_U_PPM",$formatfrac);
											$sheet->write($y,25,"$Approx_U_PPM_Int2SE",$formatfrac);

										}//end foreach grain
									
										if($showtable=="yes"){
		
										}else{
											//echo "No fraction data found.";
										}
										
										$y++;
										$y++;
									
									}//end foreach aliquots
								


								}elseif($ecproject=="helios"){
								
									//echo "Helios Here<br><br>";
		
		
									$y++;
									$sheet->write($y,1,"Fraction Info:");							
									$sheet->write($y,2,"Fraction ID",$formatfrachead);
									$sheet->write($y,3,"Mineral",$formatfrachead);
									$sheet->write($y,4,"Age (Ma)",$formatfrachead);
									$sheet->write($y,5,"Age Err. (Ma)",$formatfrachead);
									$sheet->write($y,6,"U (ppm)",$formatfrachead);
									$sheet->write($y,7,"Th (ppm)",$formatfrachead);
									$sheet->write($y,8,"147Sm (ppm)",$formatfrachead);
									$sheet->write($y,9,"[U]e",$formatfrachead);
									$sheet->write($y,10,"Th/U",$formatfrachead);
									$sheet->write($y,11,"He (nmol/g)",$formatfrachead);
									$sheet->write($y,12,"Mass (ug)",$formatfrachead);
									$sheet->write($y,13,"Ft",$formatfrachead);
									$sheet->write($y,14,"Mean ESR",$formatfrachead);
		
		
		
		
		
		
							
									$aliquots = $dom->getElementsByTagName("AliquotForGeochron");
							
									foreach($aliquots as $aliquot){
							
										$myfractionid="";
										$myage="";
										$myageerr="";
										$myft="";
										$mythuration="";
										$myuppm="";
										$mythppm="";
										$mysm147ppm="";
		
										$mymineral="";
										$myumeaserr="";
										$myhe="";
										$mymass="";
										$myesr="";
		
										$showrow="no";
										
										$fractionids = $aliquot->getElementsByTagName("AliquotID");
										foreach($fractionids as $fractionid){
											$myfractionid=$fractionid->textContent;
										}
							
										$ages = $aliquot->getElementsByTagName("Age");
										foreach($ages as $age){
											$myage=round($age->textContent,2);
										}
							
										$ageerrs = $aliquot->getElementsByTagName("AbsAgeErr");
										foreach($ageerrs as $ageerr){
											$myageerr=round($ageerr->textContent,2);
										}
							
							
										$fts = $aliquot->getElementsByTagName("Ft");
										foreach($fts as $ft){
											$myft=round($ft->textContent,2);
										}
							
										$thuratios = $aliquot->getElementsByTagName("ThURatio");
										foreach($thuratios as $thuratio){
											$mythuratio=round($thuratio->textContent,2);
										}
							
										$uppms = $aliquot->getElementsByTagName("Uppm");
										foreach($uppms as $uppm){
											$myuppm=round($uppm->textContent,2);
										}
							
										$thppms = $aliquot->getElementsByTagName("Thppm");
										foreach($thppms as $thppm){
											$mythppm=round($thppm->textContent,2);
										}
							
										$sm147ppms = $aliquot->getElementsByTagName("Sm147ppm");
										foreach($sm147ppms as $sm147ppm){
											$mysm147ppm=round($sm147ppm->textContent,2);
										}
		
										$minerals = $aliquot->getElementsByTagName("Mineral");
										foreach($minerals as $mineral){
											$mymineral=$mineral->textContent;
										}
							
										//$umeaserrs = $aliquot->getElementsByTagName("UMeasErr");
										$umeaserrs = $aliquot->getElementsByTagName("UeffConc");
										foreach($umeaserrs as $umeaserr){
											$myumeaserr=round($umeaserr->textContent,2);
										}
							
										//$hes = $aliquot->getElementsByTagName("He");
										$hes = $aliquot->getElementsByTagName("HeNmolG");
										foreach($hes as $he){
											$myhe=round($he->textContent,5);
										}
							
										$masss = $aliquot->getElementsByTagName("Mass");
										foreach($masss as $mass){
											$mymass=round($mass->textContent,2);
										}
							
										$esrs = $aliquot->getElementsByTagName("ESR");
										foreach($esrs as $esr){
											$myesr=round($esr->textContent,2);
										}
		
										$rows.="<tr>
												<td>$myfractionid</td>
												<td>$myage</td>
												<td>$myageerr</td>
												<td>$myft</td>
												<td>$mythuratio</td>
												<td>$myuppm</td>
												<td>$mythppm</td>
												<td>$mysm147ppm</td>
												</tr>";
							
										$y++;
										$sheet->write($y,2,"$myfractionid",$formatfrac);
										$sheet->write($y,3,"$mymineral",$formatfrac);
										$sheet->write($y,4,"$myage",$formatfrac);
										$sheet->write($y,5,"$myageerr",$formatfrac);
										$sheet->write($y,6,"$myuppm",$formatfrac);
										$sheet->write($y,7,"$mythppm",$formatfrac);
										$sheet->write($y,8,"$mysm147ppm",$formatfrac);
										$sheet->write($y,9,"$myumeaserr",$formatfrac);
										$sheet->write($y,10,"$mythuratio",$formatfrac);
										$sheet->write($y,11,"$myhe",$formatfrac);
										$sheet->write($y,12,"$mymass",$formatfrac);
										$sheet->write($y,13,"$myft",$formatfrac);
										$sheet->write($y,14,"$myesr",$formatfrac);
		
							
							

							
							
							
							
										/*
										echo "fractionid: $myfractionid<br>";
										echo "age: $myage ageerr:$myageerr<br>";
										echo "ft: $myft<br>";
										echo "thuration: $mythuratio<br>";
										echo "uppm: $myuppm<br>";
										echo "thppm: $mythppm<br>";
										echo "sm147ppm: $mysm147ppm<br><br>";
										*/
							
							
							
									}//end foreach aliquots
							
							
									/*
									echo "Additional Fraction Information:<br>
										<table class=\"aliquot\">
											<tr>
												<th>Fraction ID</th>
												<th>Age</th>
												<th>Age Error</th>
												<th>Ft</th>
												<th>Th/U Ratio</th>
												<th>U ppm</th>
												<th>Th ppm</th>
												<th>Sm147 ppm</th>
											</tr>
											$rows
										</table>";
									*/
							
							
									$y++;
									$y++;

								}elseif($ecproject=="uthhelegacy"){
								
									//echo "Helios Here<br><br>";
		
		
									$y++;
									$sheet->write($y,1,"Fraction Info:");
									$sheet->write($y,2,"Aliquot Name",$formatfrachead);
									$sheet->write($y,3,"Mineral",$formatfrachead);
									$sheet->write($y,4,"Age, Ma",$formatfrachead);
									$sheet->write($y,5,"err., Ma",$formatfrachead);
									$sheet->write($y,6,"U (ppm)",$formatfrachead);
									$sheet->write($y,7,"Th (ppm)",$formatfrachead);
									$sheet->write($y,8,"147Sm (ppm)",$formatfrachead);
									$sheet->write($y,9,"[U]e",$formatfrachead);
									$sheet->write($y,10,"Th/U",$formatfrachead);
									$sheet->write($y,11,"He (nmol/g)",$formatfrachead);
									$sheet->write($y,12,"Mass (ug)",$formatfrachead);
									$sheet->write($y,13,"Ft",$formatfrachead);
									$sheet->write($y,14,"Mean ESR",$formatfrachead);
		
									$fractions = $dom->getElementsByTagName("fraction");
		
									foreach($fractions as $fraction){

										$myaliquot_name="";
										$mymineral="";
										$myage_ma="";
										$myage_err_ma="";
										$myu_ppm="";
										$myth_ppm="";
										$mysm_147_ppm="";
										$myue="";
										$mythUu="";
										$myhe="";
										$mymass_ug="";
										$myft="";
										$mymean_esr="";

										$aliquot_names=$fraction->getElementsByTagName("aliquot_name");foreach($aliquot_names as $aliquot_name){$myaliquot_name = $aliquot_name->textContent;$showrow="yes";}
										$minerals=$fraction->getElementsByTagName("mineral");foreach($minerals as $mineral){$mymineral = $mineral->textContent;$showrow="yes";}
										$age_mas=$fraction->getElementsByTagName("age_ma");foreach($age_mas as $age_ma){$myage_ma = $age_ma->textContent;$showrow="yes";}
										$age_err_mas=$fraction->getElementsByTagName("age_err_ma");foreach($age_err_mas as $age_err_ma){$myage_err_ma = $age_err_ma->textContent;$showrow="yes";}
										$u_ppms=$fraction->getElementsByTagName("u_ppm");foreach($u_ppms as $u_ppm){$myu_ppm = $u_ppm->textContent;$showrow="yes";}
										$th_ppms=$fraction->getElementsByTagName("th_ppm");foreach($th_ppms as $th_ppm){$myth_ppm = $th_ppm->textContent;$showrow="yes";}
										$sm_147_ppms=$fraction->getElementsByTagName("sm_147_ppm");foreach($sm_147_ppms as $sm_147_ppm){$mysm_147_ppm = $sm_147_ppm->textContent;$showrow="yes";}
										$ues=$fraction->getElementsByTagName("ue");foreach($ues as $ue){$myue = $ue->textContent;$showrow="yes";}
										$thUus=$fraction->getElementsByTagName("thUu");foreach($thUus as $thUu){$mythUu = $thUu->textContent;$showrow="yes";}
										$hes=$fraction->getElementsByTagName("he");foreach($hes as $he){$myhe = $he->textContent;$showrow="yes";}
										$mass_ugs=$fraction->getElementsByTagName("mass_ug");foreach($mass_ugs as $mass_ug){$mymass_ug = $mass_ug->textContent;$showrow="yes";}
										$fts=$fraction->getElementsByTagName("ft");foreach($fts as $ft){$myft = $ft->textContent;$showrow="yes";}
										$mean_esrs=$fraction->getElementsByTagName("mean_esr");foreach($mean_esrs as $mean_esr){$mymean_esr = $mean_esr->textContent;$showrow="yes";}

										$y++;
										$sheet->write($y,2,"$myaliquot_name",$formatfrac);
										$sheet->write($y,3,"$mymineral",$formatfrac);
										$sheet->write($y,4,"$myage_ma",$formatfrac);
										$sheet->write($y,5,"$myage_err_ma",$formatfrac);
										$sheet->write($y,6,"$myu_ppm",$formatfrac);
										$sheet->write($y,7,"$myth_ppm",$formatfrac);
										$sheet->write($y,8,"$mysm_147_ppm",$formatfrac);
										$sheet->write($y,9,"$myue",$formatfrac);
										$sheet->write($y,10,"$mythUu",$formatfrac);
										$sheet->write($y,11,"$myhe",$formatfrac);
										$sheet->write($y,12,"$mymass_ug",$formatfrac);
										$sheet->write($y,13,"$myft",$formatfrac);
										$sheet->write($y,14,"$mymean_esr",$formatfrac);

							
									}//end foreach fractions
							
									$y++;
									$y++;

								}elseif($ecproject=="zips"){

									$y++;
									$sheet->write($y,1,"Fraction Info:");
									$sheet->write($y,2,"Spot Number",$formatfrachead);
									$sheet->write($y,3,"206/238 Age",$formatfrachead);
									$sheet->write($y,4,"206/238 Age Error",$formatfrachead);
									$sheet->write($y,5,"207/235 Age",$formatfrachead);
									$sheet->write($y,6,"207/235 Age Error",$formatfrachead);
									$sheet->write($y,7,"207/206 Age",$formatfrachead);
									$sheet->write($y,8,"207/206 Age Error",$formatfrachead);
									$sheet->write($y,9,"Pb*/Pbc",$formatfrachead);

									$spots = $dom->getElementsByTagName("spot");
		
									foreach($spots as $spot){
		
										$myagemapb206u238="";
										$myagemapb206u2381se="";
										$myagemapb207u235="";
										$myagemapb207u2351se="";
										$myagemapb207pb206="";
										$myagemapb207pb2061se="";
										$mycommonpb206pb204="";
										$mycommonpb207pb204="";
										$mycommonpb208pb204="";
										$mypbcorr="";
										$myblksize="";
										$myname="";
										$myrejected="";
			
										$agemapb206u238s=$spot->getElementsByTagName("agemapb206u238");foreach($agemapb206u238s as $agemapb206u238){$myagemapb206u238 = $agemapb206u238->textContent;$showrow="yes";}
										$agemapb206u2381ses=$spot->getElementsByTagName("agemapb206u2381se");foreach($agemapb206u2381ses as $agemapb206u2381se){$myagemapb206u2381se = $agemapb206u2381se->textContent;$showrow="yes";}
										$agemapb207u235s=$spot->getElementsByTagName("agemapb207u235");foreach($agemapb207u235s as $agemapb207u235){$myagemapb207u235 = $agemapb207u235->textContent;$showrow="yes";}
										$agemapb207u2351ses=$spot->getElementsByTagName("agemapb207u2351se");foreach($agemapb207u2351ses as $agemapb207u2351se){$myagemapb207u2351se = $agemapb207u2351se->textContent;$showrow="yes";}
										$agemapb207pb206s=$spot->getElementsByTagName("agemapb207pb206");foreach($agemapb207pb206s as $agemapb207pb206){$myagemapb207pb206 = $agemapb207pb206->textContent;$showrow="yes";}
										$agemapb207pb2061ses=$spot->getElementsByTagName("agemapb207pb2061se");foreach($agemapb207pb2061ses as $agemapb207pb2061se){$myagemapb207pb2061se = $agemapb207pb2061se->textContent;$showrow="yes";}
										$commonpb206pb204s=$spot->getElementsByTagName("commonpb206pb204");foreach($commonpb206pb204s as $commonpb206pb204){$mycommonpb206pb204 = $commonpb206pb204->textContent;$showrow="yes";}
										$commonpb207pb204s=$spot->getElementsByTagName("commonpb207pb204");foreach($commonpb207pb204s as $commonpb207pb204){$mycommonpb207pb204 = $commonpb207pb204->textContent;$showrow="yes";}
										$commonpb208pb204s=$spot->getElementsByTagName("commonpb208pb204");foreach($commonpb208pb204s as $commonpb208pb204){$mycommonpb208pb204 = $commonpb208pb204->textContent;$showrow="yes";}
										$pbcorrs=$spot->getElementsByTagName("pbcorr");foreach($pbcorrs as $pbcorr){$mypbcorr = $pbcorr->textContent;$showrow="yes";}
										$blksizes=$spot->getElementsByTagName("blksize");foreach($blksizes as $blksize){$myblksize = $blksize->textContent;$showrow="yes";}
										$names=$spot->getElementsByTagName("name");foreach($names as $name){$myname = $name->textContent;$showrow="yes";}
										$rejecteds=$spot->getElementsByTagName("rejected");foreach($rejecteds as $rejected){$myrejected = $rejected->textContent;$showrow="yes";}

										$mynameparts=explode("@",$myname);
										$bmyname=$mynameparts[1];
										$bmyname=str_replace(".ais","",$bmyname);


										$y++;
										$sheet->write($y,2,"$myname",$formatfrac);
										$sheet->write($y,3,"$myagemapb206u238",$formatfrac);
										$sheet->write($y,4,"$myagemapb206u2381se",$formatfrac);
										$sheet->write($y,5,"$myagemapb207u235",$formatfrac);
										$sheet->write($y,6,"$myagemapb207u2351se",$formatfrac);
										$sheet->write($y,7,"$myagemapb207pb206",$formatfrac);
										$sheet->write($y,8,"$myagemapb207pb2061se",$formatfrac);
										$sheet->write($y,9,"$mypbcorr",$formatfrac);

							
									}//end foreach spot
							
									$y++;
									$y++;

								}elseif($ecproject=="squid"){

									$y++;
									$sheet->write($y,1,"Fraction Info:");
									$sheet->write($y,2,"Spot ID",$formatfrachead);
									$sheet->write($y,3,"204 corr 206Pb/238U Age",$formatfrachead);
									$sheet->write($y,4,"+- 2s err",$formatfrachead);
									$sheet->write($y,5,"204 corr 207Pb/235U Age",$formatfrachead);
									$sheet->write($y,6,"+- 2s err",$formatfrachead);
									$sheet->write($y,7,"204 corr 207Pb/206Pb Age",$formatfrachead);
									$sheet->write($y,8,"+- 2s err",$formatfrachead);
									$sheet->write($y,9,"Rho",$formatfrachead);
									$sheet->write($y,10,"204 corr 208Pb/232Th Age",$formatfrachead);
									$sheet->write($y,11,"+- 2s err",$formatfrachead);

									$fractions = $dom->getElementsByTagName("fraction");
		
									foreach($fractions as $fraction){

										$myfractionid="";
										$myage_206_238="";
										$myage_206_238_err="";
										$myage_208_232="";
										$myage_208_232_err="";
										$myage_207_206="";
										$myage_207_206_err="";
										$myrho="";
										$myage_207_235="";
										$myage_207_235_err="";




			
										$fractionids=$fraction->getElementsByTagName("fractionid");foreach($fractionids as $fractionid){$myfractionid = $fractionid->textContent;$showrow="yes";}
										$age_206_238s=$fraction->getElementsByTagName("age_206_238");foreach($age_206_238s as $age_206_238){$myage_206_238 = $age_206_238->textContent;$showrow="yes";}
										$age_206_238_errs=$fraction->getElementsByTagName("age_206_238_err");foreach($age_206_238_errs as $age_206_238_err){$myage_206_238_err = $age_206_238_err->textContent;$showrow="yes";}
										$age_208_232s=$fraction->getElementsByTagName("age_208_232");foreach($age_208_232s as $age_208_232){$myage_208_232 = $age_208_232->textContent;$showrow="yes";}
										$age_208_232_errs=$fraction->getElementsByTagName("age_208_232_err");foreach($age_208_232_errs as $age_208_232_err){$myage_208_232_err = $age_208_232_err->textContent;$showrow="yes";}
										$age_207_206s=$fraction->getElementsByTagName("age_207_206");foreach($age_207_206s as $age_207_206){$myage_207_206 = $age_207_206->textContent;$showrow="yes";}
										$age_207_206_errs=$fraction->getElementsByTagName("age_207_206_err");foreach($age_207_206_errs as $age_207_206_err){$myage_207_206_err = $age_207_206_err->textContent;$showrow="yes";}
										$rhos=$fraction->getElementsByTagName("rho");foreach($rhos as $rho){$myrho = $rho->textContent;$showrow="yes";}
										$age_207_235s=$fraction->getElementsByTagName("age_207_235");foreach($age_207_235s as $age_207_235){$myage_207_235 = $age_207_235->textContent;$showrow="yes";}
										$age_207_235_errs=$fraction->getElementsByTagName("age_207_235_err");foreach($age_207_235_errs as $age_207_235_err){$myage_207_235_err = $age_207_235_err->textContent;$showrow="yes";}

										if($myage_206_238!=""){$myage_206_238=sigorigval($myage_206_238,$myage_206_238_err,6);$myage_206_238_err=sigerrval($myage_206_238_err,6);}
										if($myage_207_235!=""){$myage_207_235=sigorigval($myage_207_235,$myage_207_235_err,6);$myage_207_235_err=sigerrval($myage_207_235_err,6);}
										if($myage_207_206!=""){$myage_207_206=sigorigval($myage_207_206,$myage_207_206_err,6);$myage_207_206_err=sigerrval($myage_207_206_err,6);}
										if($myage_208_232!=""){$myage_208_232=sigorigval($myage_208_232,$myage_208_232_err,6);$myage_208_232_err=sigerrval($myage_208_232_err,6);}

										if($myrho!=""){$myrho=sigaloneval($myrho,6);}

										$y++;
										$sheet->write($y,2,"$myfractionid",$formatfrac);
										$sheet->write($y,3,"$myage_206_238",$formatfrac);
										$sheet->write($y,4,"$myage_206_238_err",$formatfrac);
										$sheet->write($y,5,"$myage_207_235",$formatfrac);
										$sheet->write($y,6,"$myage_207_235_err",$formatfrac);
										$sheet->write($y,7,"$myage_207_206",$formatfrac);
										$sheet->write($y,8,"$myage_207_206_err",$formatfrac);
										$sheet->write($y,9,"$myrho",$formatfrac);
										$sheet->write($y,10,"$myage_208_232",$formatfrac);
										$sheet->write($y,11,"$myage_208_232_err",$formatfrac);






							
									}//end foreach spot
							
									$y++;
									$y++;

								}elseif($ecproject=="arar"){

									$measurements = $dom->getElementsByTagName("Measurement");
									foreach($measurements as $measurement){

										$artotal=$artotal+$measurement->attributes->getNamedItem("fraction39ArPotassium")->value;
										$interceptunit=$measurement->attributes->getNamedItem("interceptUnit")->value;

									}
									$y++;
									$sheet->write($y,1,"Fraction Info:");
									$sheet->write($y,2,"Step No");
									$sheet->write($y,3,"Power (W) ",$formatfrachead);
									$sheet->write($y,4,"Cum.% 39Ar",$formatfrachead);
									$sheet->write($y,5,"40Ar ($interceptunit)",$formatfrachead);
									$sheet->write($y,6,"+-1s",$formatfrachead);
									$sheet->write($y,7,"39Ar ($interceptunit)",$formatfrachead);
									$sheet->write($y,8,"+-1s",$formatfrachead);
									$sheet->write($y,9,"38Ar ($interceptunit)",$formatfrachead);
									$sheet->write($y,10,"+-1s",$formatfrachead);
									$sheet->write($y,11,"37Ar ($interceptunit)",$formatfrachead);
									$sheet->write($y,12,"+-1s",$formatfrachead);
									$sheet->write($y,13,"36Ar ($interceptunit)",$formatfrachead);
									$sheet->write($y,14,"+-1s",$formatfrachead);
									$sheet->write($y,15,"Ca/K",$formatfrachead);
									$sheet->write($y,16,"+-1s",$formatfrachead);
									$sheet->write($y,17,"%40Ar*",$formatfrachead);
									$sheet->write($y,18,"40Ar*/39Ar",$formatfrachead);
									$sheet->write($y,19,"+-1s",$formatfrachead);
									$sheet->write($y,20,"Age (Ma)",$formatfrachead);
									$sheet->write($y,21,"+-1s",$formatfrachead);

									$artotal=0;
		


									$runningartotal=0;

									$measurements = $dom->getElementsByTagName("Measurement");
									foreach($measurements as $measurement){

										$runningartotal=$runningartotal+$measurement->attributes->getNamedItem("fraction39ArPotassium")->value;
										$showar39=sigaloneval($runningartotal/$artotal*100,6);

										$mymeasurementNumber=$measurement->attributes->getNamedItem("measurementNumber")->value;
										$mytemperature=sigaloneval($measurement->attributes->getNamedItem("temperature")->value,6);
										$mytemperatureUnit=$measurement->attributes->getNamedItem("temperatureUnit")->value;
										$myintercept40Ar=sigaloneval($measurement->attributes->getNamedItem("intercept40Ar")->value,6);
										$myintercept40ArSigma=sigaloneval($measurement->attributes->getNamedItem("intercept40ArSigma")->value,6);
										$myintercept39Ar=sigaloneval($measurement->attributes->getNamedItem("intercept39Ar")->value,6);
										$myintercept39ArSigma=sigaloneval($measurement->attributes->getNamedItem("intercept39ArSigma")->value,6);
										$myintercept38Ar=sigaloneval($measurement->attributes->getNamedItem("intercept38Ar")->value,6);
										$myintercept38ArSigma=sigaloneval($measurement->attributes->getNamedItem("intercept38ArSigma")->value,6);
										$myintercept37Ar=sigaloneval($measurement->attributes->getNamedItem("intercept37Ar")->value,6);
										$myintercept37ArSigma=sigaloneval($measurement->attributes->getNamedItem("intercept37ArSigma")->value,6);
										$myintercept36Ar=sigaloneval($measurement->attributes->getNamedItem("intercept36Ar")->value,6);
										$myintercept36ArSigma=sigaloneval($measurement->attributes->getNamedItem("intercept36ArSigma")->value,6);
										$mymeasuredKCaRatio=sigaloneval($measurement->attributes->getNamedItem("measuredKCaRatio")->value,6);
										$mymeasuredKCaRatioSigma=sigaloneval($measurement->attributes->getNamedItem("measuredKCaRatioSigma")->value,6);
										$myfraction40ArRadiogenic=sigaloneval($measurement->attributes->getNamedItem("fraction40ArRadiogenic")->value,6);
										$mycorrectedTotal40Ar39ArRatio=sigaloneval($measurement->attributes->getNamedItem("correctedTotal40Ar39ArRatio")->value,6);
										$mycorrectedTotal40Ar39ArRatioSigma=sigaloneval($measurement->attributes->getNamedItem("correctedTotal40Ar39ArRatioSigma")->value,6);
										$mymeasuredAge=sigaloneval($measurement->attributes->getNamedItem("measuredAge")->value,6);
										$mymeasuredAgeSigma=sigaloneval($measurement->attributes->getNamedItem("measuredAgeSigma")->value,6);

										$y++;
										$sheet->write($y,2,"$mymeasurementNumber",$formatfrac);
										$sheet->write($y,3,"$mytemperature",$formatfrac);
										$sheet->write($y,4,"$showar39",$formatfrac);
										$sheet->write($y,5,"$myintercept40Ar",$formatfrac);
										$sheet->write($y,6,"$myintercept40ArSigma",$formatfrac);
										$sheet->write($y,7,"$myintercept39Ar",$formatfrac);
										$sheet->write($y,8,"$myintercept39ArSigma",$formatfrac);
										$sheet->write($y,9,"$myintercept38Ar",$formatfrac);
										$sheet->write($y,10,"$myintercept38ArSigma",$formatfrac);
										$sheet->write($y,11,"$myintercept37Ar",$formatfrac);
										$sheet->write($y,12,"$myintercept37ArSigma",$formatfrac);
										$sheet->write($y,13,"$myintercept36Ar",$formatfrac);
										$sheet->write($y,14,"$myintercept36ArSigma",$formatfrac);
										$sheet->write($y,15,"$mymeasuredKCaRatio",$formatfrac);
										$sheet->write($y,16,"$mymeasuredKCaRatioSigma",$formatfrac);
										$sheet->write($y,17,"$myfraction40ArRadiogenic",$formatfrac);
										$sheet->write($y,18,"$mycorrectedTotal40Ar39ArRatio",$formatfrac);
										$sheet->write($y,19,"$mycorrectedTotal40Ar39ArRatioSigma",$formatfrac);
										$sheet->write($y,20,"$mymeasuredAge",$formatfrac);
										$sheet->write($y,21,"$mymeasuredAgeSigma",$formatfrac);





							
									}//end foreach spot
							
									$y++;
									$y++;


								}elseif($ecproject=="ararxls"){

									
									$showintensities="no";
									$intensities = $dom->getElementsByTagName("intensity");
									foreach($intensities as $intensitiy){

										$showintensities="yes";

									}
									
									if($showintensities=="yes"){
									
										$y++;

										$sheet->write($y,2,"ID",$formatfrachead);
										$sheet->write($y,3,"Power",$formatfrachead);
										$sheet->write($y,4,"40Ar",$formatfrachead);
										$sheet->write($y,5,"error 40Ar (1s)",$formatfrachead);
										$sheet->write($y,6,"39Ar",$formatfrachead);
										$sheet->write($y,7,"error 39Ar (1s)",$formatfrachead);
										$sheet->write($y,8,"38Ar",$formatfrachead);
										$sheet->write($y,9,"error 38Ar (1s)",$formatfrachead);
										$sheet->write($y,10,"37Ar",$formatfrachead);
										$sheet->write($y,11,"error 37Ar (1s)",$formatfrachead);
										$sheet->write($y,12,"36Ar",$formatfrachead);
										$sheet->write($y,13,"error 36Ar (1s)",$formatfrachead);
										$sheet->write($y,14,"40Ar* %",$formatfrachead);
										$sheet->write($y,15,"40Ar*/39ArK",$formatfrachead);
										$sheet->write($y,16,"error 40Ar*/39ArK (1s)",$formatfrachead);
										$sheet->write($y,17,"Age",$formatfrachead);
										$sheet->write($y,18,"Age Error (1s)",$formatfrachead);

										$y++;//show units here
										$intensitiesunitsb = $dom->getElementsByTagName("intensities");
										foreach($intensitiesunitsb as $intensitiesunits){
											$myidunits=$intensitiesunits->attributes->getNamedItem("idunits")->value;
											$mypowerunits=$intensitiesunits->attributes->getNamedItem("powerunits")->value;
											$myar40units=$intensitiesunits->attributes->getNamedItem("ar40units")->value;
											$myerror40ar1sunits=$intensitiesunits->attributes->getNamedItem("error40ar1sunits")->value;
											$myar39units=$intensitiesunits->attributes->getNamedItem("ar39units")->value;
											$myerror39ar1sunits=$intensitiesunits->attributes->getNamedItem("error39ar1sunits")->value;
											$myar38units=$intensitiesunits->attributes->getNamedItem("ar38units")->value;
											$myerror38ar1sunits=$intensitiesunits->attributes->getNamedItem("error38ar1sunits")->value;
											$myar37units=$intensitiesunits->attributes->getNamedItem("ar37units")->value;
											$myerror37ar1sunits=$intensitiesunits->attributes->getNamedItem("error37ar1sunits")->value;
											$myar36units=$intensitiesunits->attributes->getNamedItem("ar36units")->value;
											$myerror36ar1sunits=$intensitiesunits->attributes->getNamedItem("error36ar1sunits")->value;
											$myar40pctunits=$intensitiesunits->attributes->getNamedItem("ar40pctunits")->value;
											$myar40ar39kunits=$intensitiesunits->attributes->getNamedItem("ar40ar39kunits")->value;
											$myerror40ar39ark1sunits=$intensitiesunits->attributes->getNamedItem("error40ar39ark1sunits")->value;
											$myageunits=$intensitiesunits->attributes->getNamedItem("ageunits")->value;
											$myageerror1sunits=$intensitiesunits->attributes->getNamedItem("ageerror1sunits")->value;
										}

										$sheet->write($y,2,"$myidunits",$formatfrachead);
										$sheet->write($y,3,"$mypowerunits",$formatfrachead);
										$sheet->write($y,4,"$myar40units",$formatfrachead);
										$sheet->write($y,5,"$myerror40ar1sunits",$formatfrachead);
										$sheet->write($y,6,"$myar39units",$formatfrachead);
										$sheet->write($y,7,"$myerror39ar1sunits",$formatfrachead);
										$sheet->write($y,8,"$myar38units",$formatfrachead);
										$sheet->write($y,9,"$myerror38ar1sunits",$formatfrachead);
										$sheet->write($y,10,"$myar37units",$formatfrachead);
										$sheet->write($y,11,"$myerror37ar1sunits",$formatfrachead);
										$sheet->write($y,12,"$myar36units",$formatfrachead);
										$sheet->write($y,13,"$myerror36ar1sunits",$formatfrachead);
										$sheet->write($y,14,"$myar40pctunits",$formatfrachead);
										$sheet->write($y,15,"$myar40ar39kunits",$formatfrachead);
										$sheet->write($y,16,"$myerror40ar39ark1sunits",$formatfrachead);
										$sheet->write($y,17,"$myageunits",$formatfrachead);
										$sheet->write($y,18,"$myageerror1sunits",$formatfrachead);

										foreach($intensities as $intensity){

											$myid=$intensity->attributes->getNamedItem("id")->value;
											$mypower=$intensity->attributes->getNamedItem("power")->value;
											$myar40=sigaloneval($intensity->attributes->getNamedItem("ar40")->value,6);
											$myerror40ar1s=sigaloneval($intensity->attributes->getNamedItem("error40ar1s")->value,6);
											$myar39=sigaloneval($intensity->attributes->getNamedItem("ar39")->value,6);
											$myerror39ar1s=sigaloneval($intensity->attributes->getNamedItem("error39ar1s")->value,6);
											$myar38=sigaloneval($intensity->attributes->getNamedItem("ar38")->value,6);
											$myerror38ar1s=sigaloneval($intensity->attributes->getNamedItem("error38ar1s")->value,6);
											$myar37=sigaloneval($intensity->attributes->getNamedItem("ar37")->value,6);
											$myerror37ar1s=sigaloneval($intensity->attributes->getNamedItem("error37ar1s")->value,6);
											$myar36=sigaloneval($intensity->attributes->getNamedItem("ar36")->value,6);
											$myerror36ar1s=sigaloneval($intensity->attributes->getNamedItem("error36ar1s")->value,6);
											$myar40pct=sigaloneval($intensity->attributes->getNamedItem("ar40pct")->value,6);
											$myar40ar39k=sigaloneval($intensity->attributes->getNamedItem("ar40ar39k")->value,6);
											$myerror40ar39ark1s=sigaloneval($intensity->attributes->getNamedItem("error40ar39ark1s")->value,6);
											$myage=sigaloneval($intensity->attributes->getNamedItem("age")->value,6);
											$myageerror1s=sigaloneval($intensity->attributes->getNamedItem("ageerror1s")->value,6);


											$y++;
											$sheet->write($y,2,"$myid",$formatfrac);
											$sheet->write($y,3,"$mypower",$formatfrac);
											$sheet->write($y,4,"$myar40",$formatfrac);
											$sheet->write($y,5,"$myerror40ar1s",$formatfrac);
											$sheet->write($y,6,"$myar39",$formatfrac);
											$sheet->write($y,7,"$myerror39ar1s",$formatfrac);
											$sheet->write($y,8,"$myar38",$formatfrac);
											$sheet->write($y,9,"$myerror38ar1s",$formatfrac);
											$sheet->write($y,10,"$myar37",$formatfrac);
											$sheet->write($y,11,"$myerror37ar1s",$formatfrac);
											$sheet->write($y,12,"$myar36",$formatfrac);
											$sheet->write($y,13,"$myerror36ar1s",$formatfrac);
											$sheet->write($y,14,"$myar40pct",$formatfrac);
											$sheet->write($y,15,"$myar40ar39k",$formatfrac);
											$sheet->write($y,16,"$myerror40ar39ark1s",$formatfrac);
											$sheet->write($y,17,"$myage",$formatfrac);
											$sheet->write($y,18,"$myageerror1s",$formatfrac);




							
										}//end foreach spot
							
										$y++;
										$y++;

									}


									
									$showratios="no";
									$ratios = $dom->getElementsByTagName("ratio");
									foreach($ratios as $ratio){

										$showratios="yes";

									}
									
									if($showratios=="yes"){
									
										$y++;

										$sheet->write($y,2,"ID",$formatfrachead);
										$sheet->write($y,3,"Power",$formatfrachead);
										$sheet->write($y,4,"40Ar/39Ar",$formatfrachead);
										$sheet->write($y,5,"37Ar/39Ar",$formatfrachead);
										$sheet->write($y,6,"36Ar/39Ar",$formatfrachead);
										$sheet->write($y,7,"39ArK",$formatfrachead);
										$sheet->write($y,8,"K/Ca",$formatfrachead);
										$sheet->write($y,9,"40Ar*",$formatfrachead);
										$sheet->write($y,10,"39Ar",$formatfrachead);
										$sheet->write($y,11,"Age",$formatfrachead);
										$sheet->write($y,12,"Age Error 1s",$formatfrachead);

										$y++;//do units here
										$ratiosunitsb=$dom->getElementsByTagName("ratios");
										foreach($ratiosunitsb as $ratiosunits){
											$myidunits=$ratiosunits->attributes->getNamedItem("idunits")->value;
											$mypowerunits=$ratiosunits->attributes->getNamedItem("powerunits")->value;
											$myar40ar39units=$ratiosunits->attributes->getNamedItem("ar40ar39units")->value;
											$myar37ar39units=$ratiosunits->attributes->getNamedItem("ar37ar39units")->value;
											$myar36ar39units=$ratiosunits->attributes->getNamedItem("ar36ar39units")->value;
											$myar39kunits=$ratiosunits->attributes->getNamedItem("ar39kunits")->value;
											$mykcaunits=$ratiosunits->attributes->getNamedItem("kcaunits")->value;
											$myar40units=$ratiosunits->attributes->getNamedItem("ar40units")->value;
											$myar39units=$ratiosunits->attributes->getNamedItem("ar39units")->value;
											$myageunits=$ratiosunits->attributes->getNamedItem("ageunits")->value;
											$myageerror1sunits=$ratiosunits->attributes->getNamedItem("ageerror1sunits")->value;
										}

										$sheet->write($y,2,"$myidunits",$formatfrachead);
										$sheet->write($y,3,"$mypowerunits",$formatfrachead);
										$sheet->write($y,4,"$myar40ar39units",$formatfrachead);
										$sheet->write($y,5,"$myar37ar39units",$formatfrachead);
										$sheet->write($y,6,"$myar36ar39units",$formatfrachead);
										$sheet->write($y,7,"$myar39kunits",$formatfrachead);
										$sheet->write($y,8,"$mykcaunits",$formatfrachead);
										$sheet->write($y,9,"$myar40units",$formatfrachead);
										$sheet->write($y,10,"$myar39units",$formatfrachead);
										$sheet->write($y,11,"$myageunits",$formatfrachead);
										$sheet->write($y,12,"$myageerror1sunits",$formatfrachead);

										foreach($ratios as $ratio){

											$myid=$ratio->attributes->getNamedItem("id")->value;
											$mypower=$ratio->attributes->getNamedItem("power")->value;
											$myar40ar39=$ratio->attributes->getNamedItem("ar40ar39")->value;
											$myar37ar39=$ratio->attributes->getNamedItem("ar37ar39")->value;
											$myar36ar39=$ratio->attributes->getNamedItem("ar36ar39")->value;
											$myar39k=$ratio->attributes->getNamedItem("ar39k")->value;
											$mykca=$ratio->attributes->getNamedItem("kca")->value;
											$myar40=$ratio->attributes->getNamedItem("ar40")->value;
											$myar39=$ratio->attributes->getNamedItem("ar39")->value;
											$myage=$ratio->attributes->getNamedItem("age")->value;
											$myageerror1s=$ratio->attributes->getNamedItem("ageerror1s")->value;

											$y++;
											$sheet->write($y,2,"$myid",$formatfrac);
											$sheet->write($y,3,"$mypower",$formatfrac);
											$sheet->write($y,4,"$myar40ar39",$formatfrac);
											$sheet->write($y,5,"$myar37ar39",$formatfrac);
											$sheet->write($y,6,"$myar36ar39",$formatfrac);
											$sheet->write($y,7,"$myar39k",$formatfrac);
											$sheet->write($y,8,"$mykca",$formatfrac);
											$sheet->write($y,9,"$myar40",$formatfrac);
											$sheet->write($y,10,"$myar39",$formatfrac);
											$sheet->write($y,11,"$myage",$formatfrac);
											$sheet->write($y,12,"$myageerror1s",$formatfrac);

							
										}//end foreach spot
							
										$y++;
										$y++;

									}

								}elseif($ecproject=="fissiontrack"){

									$y++;
									$sheet->write($y,2,"Grain ID",$formatfrachead);
									$sheet->write($y,3,"N s",$formatfrachead);
									$sheet->write($y,4,"N i",$formatfrachead);
									$sheet->write($y,5,"Na",$formatfrachead);
									$sheet->write($y,6,"Dpar",$formatfrachead);
									$sheet->write($y,7,"Dper",$formatfrachead);
									$sheet->write($y,8,"Rmr0",$formatfrachead);
									$sheet->write($y,9,"Rho s",$formatfrachead);
									$sheet->write($y,10,"Rho i",$formatfrachead);
									$sheet->write($y,11,"Rho s / Rho i",$formatfrachead);
									$sheet->write($y,12,"Area",$formatfrachead);
									$sheet->write($y,13,"# of Etch Figures",$formatfrachead);
									$sheet->write($y,14,"238U/43Ca",$formatfrachead);
									$sheet->write($y,15,"error (1σ)",$formatfrachead);
									$sheet->write($y,16,"U ppm",$formatfrachead);
									$sheet->write($y,17,"U error (1s)",$formatfrachead);
									$sheet->write($y,18,"Age (Ma)",$formatfrachead);
									$sheet->write($y,19,"Age error (1s)",$formatfrachead);
									$sheet->write($y,20,"CaO",$formatfrachead);
									$sheet->write($y,21,"P2O5",$formatfrachead);
									$sheet->write($y,22,"F",$formatfrachead);
									$sheet->write($y,23,"Cl",$formatfrachead);
									$sheet->write($y,24,"SrO",$formatfrachead);
									$sheet->write($y,25,"BaO",$formatfrachead);
									$sheet->write($y,26,"Si02",$formatfrachead);
									$sheet->write($y,27,"Na2O",$formatfrachead);
									$sheet->write($y,28,"CeO2",$formatfrachead);
									$sheet->write($y,29,"FeO",$formatfrachead);
									$sheet->write($y,30,"Total",$formatfrachead);

									$y++;//show units here
									$apatiteagesb = $dom->getElementsByTagName("apatiteages");
									foreach($apatiteagesb as $apatiteages){
										$mygrainidunits=$apatiteages->attributes->getNamedItem("grainidunits")->value;
										$mynsunits=$apatiteages->attributes->getNamedItem("nsunits")->value;
										$myniunits=$apatiteages->attributes->getNamedItem("niunits")->value;
										$mynaunits=$apatiteages->attributes->getNamedItem("naunits")->value;
										$mydparunits=$apatiteages->attributes->getNamedItem("dparunits")->value;
										$mydperunits=$apatiteages->attributes->getNamedItem("dperunits")->value;
										$myrmr0units=$apatiteages->attributes->getNamedItem("rmr0units")->value;
										$myrhosunits=$apatiteages->attributes->getNamedItem("rhosunits")->value;
										$myrhoiunits=$apatiteages->attributes->getNamedItem("rhoiunits")->value;
										$myrhosrhoiunits=$apatiteages->attributes->getNamedItem("rhosrhoiunits")->value;
										$myareaunits=$apatiteages->attributes->getNamedItem("areaunits")->value;
										$myofetchfiguresunits=$apatiteages->attributes->getNamedItem("ofetchfiguresunits")->value;
										$myu238ca43units=$apatiteages->attributes->getNamedItem("u238ca43units")->value;
										$myerror1sunits=$apatiteages->attributes->getNamedItem("error1sunits")->value;
										$myuppmunits=$apatiteages->attributes->getNamedItem("uppmunits")->value;
										$myuerror1sunits=$apatiteages->attributes->getNamedItem("uerror1sunits")->value;
										$myagemaunits=$apatiteages->attributes->getNamedItem("agemaunits")->value;
										$myageerror1sunits=$apatiteages->attributes->getNamedItem("ageerror1sunits")->value;
										$mycaounits=$apatiteages->attributes->getNamedItem("caounits")->value;
										$myp2o5units=$apatiteages->attributes->getNamedItem("p2o5units")->value;
										$myfunits=$apatiteages->attributes->getNamedItem("funits")->value;
										$myclunits=$apatiteages->attributes->getNamedItem("clunits")->value;
										$mysrounits=$apatiteages->attributes->getNamedItem("srounits")->value;
										$mybaounits=$apatiteages->attributes->getNamedItem("baounits")->value;
										$mysi02units=$apatiteages->attributes->getNamedItem("si02units")->value;
										$myna2ounits=$apatiteages->attributes->getNamedItem("na2ounits")->value;
										$myceo2units=$apatiteages->attributes->getNamedItem("ceo2units")->value;
										$myfeounits=$apatiteages->attributes->getNamedItem("feounits")->value;
										$mytotalunits=$apatiteages->attributes->getNamedItem("totalunits")->value;
									}

									$sheet->write($y,2,"$mygrainidunits",$formatfrachead);
									$sheet->write($y,3,"$mynsunits",$formatfrachead);
									$sheet->write($y,4,"$myniunits",$formatfrachead);
									$sheet->write($y,5,"$mynaunits",$formatfrachead);
									$sheet->write($y,6,"$mydparunits",$formatfrachead);
									$sheet->write($y,7,"$mydperunits",$formatfrachead);
									$sheet->write($y,8,"$myrmr0units",$formatfrachead);
									$sheet->write($y,9,"$myrhosunits",$formatfrachead);
									$sheet->write($y,10,"$myrhoiunits",$formatfrachead);
									$sheet->write($y,11,"$myrhosrhoiunits",$formatfrachead);
									$sheet->write($y,12,"$myareaunits",$formatfrachead);
									$sheet->write($y,13,"$myofetchfiguresunits",$formatfrachead);
									$sheet->write($y,14,"$myu238ca43units",$formatfrachead);
									$sheet->write($y,15,"$myerror1sunits",$formatfrachead);
									$sheet->write($y,16,"$myuppmunits",$formatfrachead);
									$sheet->write($y,17,"$myuerror1sunits",$formatfrachead);
									$sheet->write($y,18,"$myagemaunits",$formatfrachead);
									$sheet->write($y,19,"$myageerror1sunits",$formatfrachead);
									$sheet->write($y,20,"$mycaounits",$formatfrachead);
									$sheet->write($y,21,"$myp2o5units",$formatfrachead);
									$sheet->write($y,22,"$myfunits",$formatfrachead);
									$sheet->write($y,23,"$myclunits",$formatfrachead);
									$sheet->write($y,24,"$mysrounits",$formatfrachead);
									$sheet->write($y,25,"$mybaounits",$formatfrachead);
									$sheet->write($y,26,"$mysi02units",$formatfrachead);
									$sheet->write($y,27,"$myna2ounits",$formatfrachead);
									$sheet->write($y,28,"$myceo2units",$formatfrachead);
									$sheet->write($y,29,"$myfeounits",$formatfrachead);
									$sheet->write($y,30,"$mytotalunits",$formatfrachead);

									$grains = $dom->getElementsByTagName("grain");

									foreach($grains as $grain){

										$mygrainid=$grain->attributes->getNamedItem("grainid")->value;
										$myns=sigaloneval($grain->attributes->getNamedItem("ns")->value,6);
										$myni=sigaloneval($grain->attributes->getNamedItem("ni")->value,6);
										$myna=sigaloneval($grain->attributes->getNamedItem("na")->value,6);
										$mydpar=sigaloneval($grain->attributes->getNamedItem("dpar")->value,6);
										$mydper=sigaloneval($grain->attributes->getNamedItem("dper")->value,6);
										$myrmr0=sigaloneval($grain->attributes->getNamedItem("rmr0")->value,6);
										$myrhos=sigaloneval($grain->attributes->getNamedItem("rhos")->value,6);
										$myrhoi=sigaloneval($grain->attributes->getNamedItem("rhoi")->value,6);
										$myrhosrhoi=sigaloneval($grain->attributes->getNamedItem("rhosrhoi")->value,6);
										$myarea=sigaloneval($grain->attributes->getNamedItem("area")->value,6);
										$myofetchfigures=$grain->attributes->getNamedItem("ofetchfigures")->value;
										$myu238ca43=sigaloneval($grain->attributes->getNamedItem("u238ca43")->value,6);
										$myerror1s=sigaloneval($grain->attributes->getNamedItem("error1s")->value,6);
										$myuppm=sigaloneval($grain->attributes->getNamedItem("uppm")->value,6);
										$myuerror1s=sigaloneval($grain->attributes->getNamedItem("uerror1s")->value,6);
										$myagema=sigaloneval($grain->attributes->getNamedItem("agema")->value,6);
										$myageerror1s=sigaloneval($grain->attributes->getNamedItem("ageerror1s")->value,6);
										$mycao=sigaloneval($grain->attributes->getNamedItem("cao")->value,6);
										$myp2o5=sigaloneval($grain->attributes->getNamedItem("p2o5")->value,6);
										$myf=sigaloneval($grain->attributes->getNamedItem("f")->value,6);
										$mycl=sigaloneval($grain->attributes->getNamedItem("cl")->value,6);
										$mysro=sigaloneval($grain->attributes->getNamedItem("sro")->value,6);
										$mybao=sigaloneval($grain->attributes->getNamedItem("bao")->value,6);
										$mysi02=sigaloneval($grain->attributes->getNamedItem("si02")->value,6);
										$myna2o=sigaloneval($grain->attributes->getNamedItem("na2o")->value,6);
										$myceo2=sigaloneval($grain->attributes->getNamedItem("ceo2")->value,6);
										$myfeo=sigaloneval($grain->attributes->getNamedItem("feo")->value,6);
										$mytotal=sigaloneval($grain->attributes->getNamedItem("total")->value,6);


										$y++;
										$sheet->write($y,2,"$mygrainid",$formatfrac);
										$sheet->write($y,3,"$myns",$formatfrac);
										$sheet->write($y,4,"$myni",$formatfrac);
										$sheet->write($y,5,"$myna",$formatfrac);
										$sheet->write($y,6,"$mydpar",$formatfrac);
										$sheet->write($y,7,"$mydper",$formatfrac);
										$sheet->write($y,8,"$myrmr0",$formatfrac);
										$sheet->write($y,9,"$myrhos",$formatfrac);
										$sheet->write($y,10,"$myrhoi",$formatfrac);
										$sheet->write($y,11,"$myrhosrhoi",$formatfrac);
										$sheet->write($y,12,"$myarea",$formatfrac);
										$sheet->write($y,13,"$myofetchfigures",$formatfrac);
										$sheet->write($y,14,"$myu238ca43",$formatfrac);
										$sheet->write($y,15,"$myerror1s",$formatfrac);
										$sheet->write($y,16,"$myuppm",$formatfrac);
										$sheet->write($y,17,"$myuerror1s",$formatfrac);
										$sheet->write($y,18,"$myagema",$formatfrac);
										$sheet->write($y,19,"$myageerror1s",$formatfrac);
										$sheet->write($y,20,"$mycao",$formatfrac);
										$sheet->write($y,21,"$myp2o5",$formatfrac);
										$sheet->write($y,22,"$myf",$formatfrac);
										$sheet->write($y,23,"$mycl",$formatfrac);
										$sheet->write($y,24,"$mysro",$formatfrac);
										$sheet->write($y,25,"$mybao",$formatfrac);
										$sheet->write($y,26,"$mysi02",$formatfrac);
										$sheet->write($y,27,"$myna2o",$formatfrac);
										$sheet->write($y,28,"$myceo2",$formatfrac);
										$sheet->write($y,29,"$myfeo",$formatfrac);
										$sheet->write($y,30,"$mytotal",$formatfrac);
						
									}//end foreach spot
						
									$y++;
									$y++;
















								}else{ //ecproject 

									$y++;
									$y++;

								}
							
							}else{//end if dom load

							}
		
							//writeUrl ( integer $row , integer $col , string $url , string $string='' , mixed $format=0 )
							$showheader="yes";
							$y++;
						}
					}
		
		
		
		
		$sheet->setMerge(2,0,4,6);

		//write header
		if($fromsearch!="yes"){
			$sheet->write(2,0,"Notes: The following samples are found in dataset '$datasetname'",$formatinstr);
		}else{
			$sheet->write(2,0,"Notes:",$formatinstr);
		}

		
		
		//$sheet->setMerge(6,6,6,8);
		
		//$sheet->write(6,6,"Host Rock",$formathostrock);
		
		//$sheet->setMerge(6,9,6,10);
		
		//$sheet->write(6,9,"Fraction",$formatfraction);
		
		// Finish the spreadsheet, dumping it to the browser
		$xls->close(); 
		
		exit();
		














		


}

/*
old arar

									$y++;
									$sheet->write($y,1,"Fraction Info:");
									$sheet->write($y,2,"ID (step or grain)",$formatfrachead);
									$sheet->write($y,3,"Power (Watts, Temp)",$formatfrachead);
									$sheet->write($y,4,"40Ar/39Ar",$formatfrachead);
									$sheet->write($y,5,"37Ar/39Ar",$formatfrachead);
									$sheet->write($y,6,"36Ar/39Ar (x 10-3)",$formatfrachead);
									$sheet->write($y,7,"39ArK (x 10-15 mol)",$formatfrachead);
									$sheet->write($y,8,"K/Ca",$formatfrachead);
									$sheet->write($y,9,"40Ar* (%)",$formatfrachead);
									$sheet->write($y,10,"39Ar (%)",$formatfrachead);
									$sheet->write($y,11,"Age (Ma)",$formatfrachead);
									$sheet->write($y,12,"+- 1s err",$formatfrachead);

									$artotal=0;
		
									$measurements = $dom->getElementsByTagName("Measurement");
									foreach($measurements as $measurement){

										$artotal=$artotal+$measurement->attributes->getNamedItem("fraction39ArPotassium")->value;

									}

									$runningartotal=0;

									$measurements = $dom->getElementsByTagName("Measurement");
									foreach($measurements as $measurement){

										$runningartotal=$runningartotal+$measurement->attributes->getNamedItem("fraction39ArPotassium")->value;
										$showar39=sigaloneval($runningartotal/$artotal*100,6);

										$mymeasurementNumber=$measurement->attributes->getNamedItem("measurementNumber")->value;
										$mytemperature=sigaloneval($measurement->attributes->getNamedItem("temperature")->value,6);
										$mytemperatureUnit=$measurement->attributes->getNamedItem("temperatureUnit")->value;
										$mycorrectedTotal40Ar39ArRatio=sigaloneval($measurement->attributes->getNamedItem("correctedTotal40Ar39ArRatio")->value,6);
										$mycorrectedTotal37Ar39ArRatio=sigaloneval($measurement->attributes->getNamedItem("correctedTotal37Ar39ArRatio")->value,6);
										$mycorrectedTotal36Ar39ArRatio=sigaloneval($measurement->attributes->getNamedItem("correctedTotal36Ar39ArRatio")->value,6);
										$mycorrected39ArPotassium=sigaloneval($measurement->attributes->getNamedItem("corrected39ArPotassium")->value,6);
										$mymeasuredKCaRatio=sigaloneval($measurement->attributes->getNamedItem("measuredKCaRatio")->value,6);
										$myfraction40ArRadiogenic=sigaloneval($measurement->attributes->getNamedItem("fraction40ArRadiogenic")->value,6);
										$myfraction39ArPotassium=sigaloneval($measurement->attributes->getNamedItem("fraction39ArPotassium")->value,6);
										$mymeasuredAge=sigaloneval($measurement->attributes->getNamedItem("measuredAge")->value,6);
										$mymeasuredAgeSigma=sigaloneval($measurement->attributes->getNamedItem("measuredAgeSigma")->value,6);

										$y++;
										$sheet->write($y,2,"$mymeasurementNumber",$formatfrac);
										$sheet->write($y,3,"$mytemperature $mytemperatureUnit",$formatfrac);
										$sheet->write($y,4,"$mycorrectedTotal40Ar39ArRatio",$formatfrac);
										$sheet->write($y,5,"$mycorrectedTotal37Ar39ArRatio",$formatfrac);
										$sheet->write($y,6,"$mycorrectedTotal36Ar39ArRatio",$formatfrac);
										$sheet->write($y,7,"$mycorrected39ArPotassium",$formatfrac);
										$sheet->write($y,8,"$mymeasuredKCaRatio",$formatfrac);
										$sheet->write($y,9,"$myfraction40ArRadiogenic",$formatfrac);
										$sheet->write($y,10,"$showar39",$formatfrac);
										$sheet->write($y,11,"$mymeasuredAge",$formatfrac);
										$sheet->write($y,12,"$mymeasuredAgeSigma",$formatfrac);






							
									}//end foreach spot
							
									$y++;
									$y++;
*/

?>