<?PHP
/**
 * uthhexls.php
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

if($_POST['submit']!=""){

	$inputtype=$_POST['inputtype'];

	$filename=str_replace(" ","_",$_FILES['uthhefile']['name']);
	$orig_filename=str_replace(" ","_",$_FILES['uthhefile']['name']);

	//should check from filename because 'type' can vary
	$pos = strpos($_FILES['uthhefile']['name'],".xls");
	
	if($pos === false) {
		$error.=$errordelim."Wrong file type detected. File must be .xls spreadsheet.";$errordelim="<br>";
	}

	if($error==""){
		$randnum=$db->get_var("select nextval('heliosxls_seq')");
		
		//make a new directory
		mkdir("heliostemp/$randnum");
		
		$mydir="heliostemp/$randnum";
	
		$newfilename="heliostemp/$randnum"."/".$filename;
		
		$tempname=$_FILES['uthhefile']['tmp_name'];
		
		move_uploaded_file ( $tempname , "$newfilename" );
		
		require_once 'Excel/reader.php';
		
		$data = new Spreadsheet_Excel_Reader();
		
		$data->setOutputEncoding('CP1251');
		
		$data->read($newfilename);
		
	
		$boundsheets=$data->boundsheets;
		$sheets=$data->sheets;
		
		//check age sheet
		$metadataxd=$sheets[0]['cells'];
		
		//print_r($metadataxd);exit();
		
		$maxy=0;
		for($y=1;$y<100;$y++){
			if($metadataxd[$y][1]!=""){
				$maxy=$y;
			}
		}
		
		//loop over rows to get values
		
		$agenum=0;
		
		for($y=1;$y<=$maxy;$y++){
		
			if($metadataxd[$y][1]=="Sample Name"){$samplename=$metadataxd[$y][2];}
			if($metadataxd[$y][1]=="IGSN"){$uniqueid=$metadataxd[$y][2];}
			if($metadataxd[$y][1]=="Laboratory"){$labname=$metadataxd[$y][2];}
			if($metadataxd[$y][1]=="Analyst"){$analystname=$metadataxd[$y][2];}
			if($metadataxd[$y][1]=="Instrumental Method - He"){$instrumentalmethodhe=$metadataxd[$y][2];}
			if($metadataxd[$y][1]=="Instrumental Method Reference - He"){$instrumentalmethodreferencehe=$metadataxd[$y][2];}
			if($metadataxd[$y][1]=="Instrumental Method - U-Th-Sm"){$instrumentalmethoduthsm=$metadataxd[$y][2];}
			if($metadataxd[$y][1]=="Instrumental Method Reference - U-Th-Sm"){$instrumentalmethodreferenceuthsm=$metadataxd[$y][2];}
			if($metadataxd[$y][1]=="Alpha Ejection Correction Method"){$alphaejectioncorrectionmethod=$metadataxd[$y][2];}
			if($metadataxd[$y][1]=="Alpha Ejection Correction Method Reference"){$alphaejectioncorrectionmethodreference=$metadataxd[$y][2];}
			if($metadataxd[$y][1]=="Mineral"){$mineral=$metadataxd[$y][2];}
			if($metadataxd[$y][1]=="Comment"){$comment=$metadataxd[$y][2];}
			if($metadataxd[$y][1]=="238U Decay Constant"){$udecayconstant238=$metadataxd[$y][2];}
			if($metadataxd[$y][1]=="238U Decay Constant Error"){$udecayconstanterror238=$metadataxd[$y][2];}
			if($metadataxd[$y][1]=="235U Decay Constant"){$udecayconstant235=$metadataxd[$y][2];}
			if($metadataxd[$y][1]=="235U Decay Constant Error"){$udecayconstanterror235=$metadataxd[$y][2];}
			if($metadataxd[$y][1]=="232Th Decay Constant "){$thdecayconstant232=$metadataxd[$y][2];}
			if($metadataxd[$y][1]=="232Th Decay Constant Error"){$thdecayconstanterror232=$metadataxd[$y][2];}
			if($metadataxd[$y][1]=="230Th Decay Constant"){$thdecayconstant230=$metadataxd[$y][2];}
			if($metadataxd[$y][1]=="230Th Decay Constant Error"){$thdecayconstanterror230=$metadataxd[$y][2];}
			if($metadataxd[$y][1]=="238U/235U"){$u238u235=$metadataxd[$y][2];}
			if($metadataxd[$y][1]=="Decay Constant Reference"){$decayconstantreference=$metadataxd[$y][2];}
			if($metadataxd[$y][1]=="147Sm Decay Constant"){$smdecayconstant147=$metadataxd[$y][2];}
			if($metadataxd[$y][1]=="147Sm Decay Constant Error"){$smdecayconstanterror147=$metadataxd[$y][2];}
			if($metadataxd[$y][1]=="147Sm Decay Constant Reference"){$smdecayconstantreference147=$metadataxd[$y][2];}
			if($metadataxd[$y][1]=="Decay Constant Comment"){$decayconstantcomment=$metadataxd[$y][2];}

			if($metadataxd[$y][1]=="Spike Type U-Th-Sm"){$spiketypeuthsm=$metadataxd[$y][2];}
			if($metadataxd[$y][1]=="Spike Type He"){$spiketypehe=$metadataxd[$y][2];}
			if($metadataxd[$y][1]=="Standard Mineral"){$standardmineral=$metadataxd[$y][2];}
			if($metadataxd[$y][1]=="Standard Mineral Reference"){$standardmineralreference=$metadataxd[$y][2];}
			if($metadataxd[$y][1]=="Standard True Age"){$standardtrueage=$metadataxd[$y][2];}
			if($metadataxd[$y][1]=="Standard True Age Error"){$standardtrueageerror=$metadataxd[$y][2];}
			if($metadataxd[$y][1]=="Standard Measured Age"){$standardmeasuredage=$metadataxd[$y][2];}
			if($metadataxd[$y][1]=="Standard Measured Age Error"){$standardmeasuredageerror=$metadataxd[$y][2];}
			if($metadataxd[$y][1]=="Data Reduction Comment"){$datareductioncomment=$metadataxd[$y][2];}
		
			if($metadataxd[$y][1]=="Analysis Purpose" || $metadataxd[$y][1]=="Additional Analysis Purpose"){$agenum++;eval("\$analysispurpose$agenum=\$metadataxd[\$y][2];");}
			if($metadataxd[$y][1]=="Preferred Age Type" || $metadataxd[$y][1]=="Additional Age Type"){eval("\$preferredagetype$agenum=\$metadataxd[\$y][2];");}
			if($metadataxd[$y][1]=="Preferred Age" || $metadataxd[$y][1]=="Age"){eval("\$preferredage$agenum=\$metadataxd[\$y][2];");}
			if($metadataxd[$y][1]=="Preferred Age Error" || $metadataxd[$y][1]=="Age Error (Analytical)"){eval("\$preferredageerror$agenum=\$metadataxd[\$y][2];");}
			if($metadataxd[$y][1]=="MSWD" ){eval("\$mswd$agenum=\$metadataxd[\$y][2];");}
			if($metadataxd[$y][1]=="Age Error (Systematic)" ){eval("\$ageerrorsystematic$agenum=\$metadataxd[\$y][2];");}
			if($metadataxd[$y][1]=="Preferred Age Included Analyses" || $metadataxd[$y][1]=="Included Analyses"){eval("\$preferredageincludedanalyses$agenum=\$metadataxd[\$y][2];");}
			if($metadataxd[$y][1]=="Preferred Age Explanation" || $metadataxd[$y][1]=="Age Comment"){eval("\$preferredageexplanation$agenum=\$metadataxd[\$y][2];");}





		}//end foreach y
	



		$overwrite=$_POST['overwrite'];
		$public=$_POST['public'];
		$submit=$_POST['submit'];

		if($public==""){
			$public=1;
		}
	
		$sampleid=$samplename;

		if($samplename==""){$error.=$errordelim."Sample Name cannot be blank.";$errordelim="<br>";}
		if($uniqueid==""){$error.=$errordelim."IGSN cannot be blank.";$errordelim="<br>";}
		if($labname==""){$error.=$errordelim."Laboratory cannot be blank.";$errordelim="<br>";}
		if($analystname==""){$error.=$errordelim."Analyst cannot be blank.";$errordelim="<br>";}
		//these aren't required per Doug on 8/8/2013
		//if($instrumentalmethodhe==""){$error.=$errordelim."Instrumental Method - He cannot be blank.";$errordelim="<br>";}
		//if($instrumentalmethoduthsm==""){$error.=$errordelim."Instrumental Method - U-Th-Sm cannot be blank.";$errordelim="<br>";}
		//if($alphaejectioncorrectionmethod==""){$error.=$errordelim."Alpha Ejection Correction Method cannot be blank.";$errordelim="<br>";}
		if($mineral==""){$error.=$errordelim."Mineral cannot be blank.";$errordelim="<br>";}
		if($udecayconstant238==""){$error.=$errordelim."238U Decay Constant cannot be blank.";$errordelim="<br>";}
		if($udecayconstant235==""){$error.=$errordelim."235U Decay Constant cannot be blank.";$errordelim="<br>";}
		if($thdecayconstant232==""){$error.=$errordelim."232Th Decay Constant  cannot be blank.";$errordelim="<br>";}
		if($u238u235==""){$error.=$errordelim."238U/235U cannot be blank.";$errordelim="<br>";}
		if($decayconstantreference==""){$error.=$errordelim."Decay Constant Reference cannot be blank.";$errordelim="<br>";}
		if($smdecayconstant147==""){$error.=$errordelim."147Sm Decay Constant cannot be blank.";$errordelim="<br>";}
		if($smdecayconstantreference147==""){$error.=$errordelim."147Sm Decay Constant Reference cannot be blank.";$errordelim="<br>";}



		if($analysispurpose1==""){$error.=$errordelim."Analysis Purpose cannot be blank.";$errordelim="<br>";}




		if($error==""){

			//OK, no errors, so let's look for IGSN/GeochronID info
			$modigsn=$uniqueid;
		
			include("fetchigsn.php");
		
			if($moderror!=""){
				$error=$moderror;
			}
	
	
		}


	
	
		if($error==""){
			//check for uniqueness of igsn/aliquotname
			$igsncount=$db->get_var("select count(*) from sample where igsn='$modigsn' and sample_id='$sampleid'");
			if($overwrite!="yes"){
				if($igsncount>0){
					//comment this out so IGSN isn't checked -- temporary
					
					//change this for Tandis - JMA 01/30/2014
					//$error.="Sample with this Unique Identifier: $modigsn and Sample ID: $sampleid already exists in database.<br>";
				}
			}else{ //overwrite = yes, so we need to delete if exists
				if($igsncount>0){
					//$myexistcount=$db->get_var("select count(*) from sample where igsn='$modigsn' and aliquotname='$myaliquotname' and username='$username'");
					$myexistcount=$db->get_var("select count(*) from sample where igsn='$modigsn' and sample_id='$sampleid' and userpkey=$userpkey");
					if($myexistcount > 0){
						$db->query("delete from sample_age where sample_pkey in (select sample_pkey from sample where igsn='$modigsn' and sample_id='$sampleid' and userpkey=$userpkey)");
						$db->query("delete from sample where igsn='$modigsn' and sample_id='$sampleid' and userpkey=$userpkey");
					}else{
						$error="Sample with Unique Identifier: $modigsn and Sample ID: $sampleid already exists in database and you are not the owner. Cannot overwrite.<br>";
					}
				}
			}
		}



		
		//check reduced data
		$numrows=$sheets[2]['numRows'];
		$xd=$data->sheets[2][cells];
		
		//print_r($xd);exit();
		
		//get maxy
		for($y=4;$y<=$numrows;$y++){
			if($xd[$y][1]!=""){
				$maxy=$y;
			}
		}
		
		//echo "maxy: $maxy<br><br>";
		
		$samplist=array();
		
		//get distinct sample names
		for($y=4;$y<=$maxy;$y++){
		
			$thisval=$xd[$y][1];
			
			if($thisval!=""){
				
				//echo "thisval:$thisval<br>";
				
				//look for last non alphanumeric
				$thisval=trim($thisval);
				
				$foundpos=0;
				for($charnum=0;$charnum<strlen($thisval);$charnum++){
					if(!ctype_alnum($thisval[$charnum])){
					
						$foundpos=$charnum;
					
					}
				}
				
				#$error.=$errordelim."foundpos: $foundpos<br>";
				
				if($foundpos>0){
				
					$thissampleid=substr($thisval,0,$foundpos);

					if(!in_array($thissampleid,$samplist)){
						$samplist[]=$thissampleid;
					}
				
				}else{
					
					//error
					$thisline=$y+0;
					$error.=$errordelim."Invalid Sample ID found in row ".$thisline." in reduced data. Please fix and try again.";$errordelim="<br>";
					
				}
				
				/*
				//echo "$thisval<br>";
				$parts=explode("-",$thisval);
				$sampleid=$parts[0];
				
				if(!in_array($sampleid,$samplist)){
					$samplist[]=$sampleid;
				}
				*/
			}
		}
		
		foreach($samplist as $samp){
			//echo "$samp<br>";
		}
		
		$sampliststring=implode(",",$samplist);
		
		//print_r($samplist);
		
		if($sampliststring==""){
			$error.=$errordelim."No Samples found in reduced data tab. Samples must be entered in reduced data tab. Please check file and upload again.";$errordelim="<br>";
		}
		
		

	}

	if($error==""){
	
		//OK, we need to display a page for uploading images
		$sample_pkey=$db->get_var("select nextval('sample_seq')");
		include("includes/geochron-secondary-header.htm");
		
		?>





		<link href="swfupload/default.css" rel="stylesheet" type="text/css" />
		<script type="text/javascript" src="swfupload/swfupload.js"></script>
		<script type="text/javascript" src="swfupload/swfupload.queue.js"></script>
		<script type="text/javascript" src="swfupload/fileprogress.js"></script>
		<script type="text/javascript" src="swfupload/handlers.js"></script>
		<script type="text/javascript">
		var swfu;

		window.onload = function() {
			var settings = {
				flash_url : "swfupload/swfupload.swf",
				flash9_url : "swfupload/swfupload_fp9.swf",
				upload_url: "swfupload/upload.php",
				post_params: {"PHPSESSID" : "<?php echo session_id(); ?>","sample_pkey":"<?=$sample_pkey?>"},
				file_size_limit : "20 MB",
				file_types : "*.jpg;*.jpeg;*.png;*.gif;*.bmp",
				file_types_description : "Image Files",
				file_upload_limit : 100,
				file_queue_limit : 0,
				custom_settings : {
					progressTarget : "fsUploadProgress",
					cancelButtonId : "btnCancel"
				},
				debug: false,

				// Button settings
				button_image_url: "swfupload/swfcolorbar.png",
				button_width: "90",
				button_height: "29",
				button_placeholder_id: "spanButtonPlaceHolder",
				button_text: '<span class="theFont">Upload Image</span>',
				button_text_style: ".theFont { font-family: verdana,arial,sans-serif;font-size:10px; color:#333333; }",
				button_text_left_padding: 7,
				button_text_top_padding: 9,
				
				// The event handler functions are defined in handlers.js
				swfupload_preload_handler : preLoad,
				swfupload_load_failed_handler : loadFailed,
				file_queued_handler : fileQueued,
				file_queue_error_handler : fileQueueError,
				file_dialog_complete_handler : fileDialogComplete,
				upload_start_handler : uploadStart,
				upload_progress_handler : uploadProgress,
				upload_error_handler : uploadError,
				upload_success_handler : uploadSuccess,
				upload_complete_handler : uploadComplete,
				queue_complete_handler : queueComplete	// Queue plugin event
			};

			swfu = new SWFUpload(settings);
				 };
			</script>

			<form name="uploadform" method="POST">

			<h1>Image Upload (optional)</h1>

				<p>Upload an image here.</p>

					<div class="fieldset flash" id="fsUploadProgress">
					<span class="legend">Upload Queue</span>
					</div>
				<div id="divStatus">0 Files Uploaded</div>
					<div>
						<span id="spanButtonPlaceHolder"></span>
						<input id="btnCancel" type="button" value="Cancel All Uploads" onclick="swfu.cancelQueue();" disabled="disabled" style="margin-left: 2px; font-size: 8pt; height: 29px;" />
					</div>




				<br>
				<hr>
				<br>
	
				<input type="submit" name="imagesubmit" value="Continue">
		
	
				<input type="hidden" name="filename">
				<input type="hidden" name="public" value="<?=$public?>">
				<input type="hidden" name="f" value="<?=$randnum?>">
				<input type="hidden" name="l" value="<?=$l?>">
				<input type="hidden" name="n" value="<?=$filename?>">
				<input type="hidden" name="s" value="<?=$s?>">
				<input type="hidden" name="sample_pkey" value="<?=$sample_pkey?>">



			</form>

	
	
	
			</div>
	
	
	
	
	
			<br><br><br><br><br>
			<br><br><br><br><br>
			<br><br><br><br><br>
			<br><br><br><br><br>
			<br><br><br><br><br>
	

	
			<?

			include("includes/geochron-secondary-footer.htm");
			//exec("rm -rf $mydir");
			exit();


	}//end if error = ""

}//end if post submit



if($_POST['imagesubmit']!=""){

		/*
		foreach($_POST as $key=>$value){
			echo "$key : $value<br>";
		}
		exit();
		*/
		
		$geochron_pkey=$db->get_var("select nextval('geochron_seq')");
		
		//$sample_pkey=$db->get_var("select nextval('sample_seq')");
		
		$uploaddate=date("m/d/Y h:i:s a");

		$savefilename="$geochron_pkey.xml";
		
		$isupstream="FALSE";

		$randnum=$_POST['f'];
		$public=$_POST['public'];
		$filename=$_POST['n'];
		$sample_pkey=$_POST['sample_pkey'];

		$newfilename="heliostemp/$randnum"."/".$filename;

		//echo "newfilename: $newfilename sample_pkey: $sample_pkey";exit();

		require_once 'Excel/reader.php';
		
		$data = new Spreadsheet_Excel_Reader();
		
		$data->setOutputEncoding('CP1251');
		
		$data->read($newfilename);
		
	
		$boundsheets=$data->boundsheets;
		$sheets=$data->sheets;
		
		//check age sheet
		$metadataxd=$sheets[0]['cells'];
		
		//print_r($metadataxd);exit();
		
		$maxy=0;
		for($y=1;$y<100;$y++){
			if($metadataxd[$y][1]!=""){
				$maxy=$y;
			}
		}
		
		//loop over rows to get values
		
		$agenum=0;
		
		for($y=1;$y<=$maxy;$y++){
		
			if($metadataxd[$y][1]=="Sample Name"){$samplename=$metadataxd[$y][2];}
			if($metadataxd[$y][1]=="IGSN"){$uniqueid=$metadataxd[$y][2];}
			if($metadataxd[$y][1]=="Laboratory"){$labname=$metadataxd[$y][2];}
			if($metadataxd[$y][1]=="Analyst"){$analystname=$metadataxd[$y][2];}
			if($metadataxd[$y][1]=="Instrumental Method - He"){$instrumentalmethodhe=$metadataxd[$y][2];}
			if($metadataxd[$y][1]=="Instrumental Method Reference - He"){$instrumentalmethodreferencehe=$metadataxd[$y][2];}
			if($metadataxd[$y][1]=="Instrumental Method - U-Th-Sm"){$instrumentalmethoduthsm=$metadataxd[$y][2];}
			if($metadataxd[$y][1]=="Instrumental Method Reference - U-Th-Sm"){$instrumentalmethodreferenceuthsm=$metadataxd[$y][2];}
			if($metadataxd[$y][1]=="Alpha Ejection Correction Method"){$alphaejectioncorrectionmethod=$metadataxd[$y][2];}
			if($metadataxd[$y][1]=="Alpha Ejection Correction Method Reference"){$alphaejectioncorrectionmethodreference=$metadataxd[$y][2];}
			if($metadataxd[$y][1]=="Mineral"){$mineral=$metadataxd[$y][2];}
			if($metadataxd[$y][1]=="Comment"){$comment=$metadataxd[$y][2];}
			if($metadataxd[$y][1]=="238U Decay Constant"){$udecayconstant238=$metadataxd[$y][2];}
			if($metadataxd[$y][1]=="238U Decay Constant Error"){$udecayconstanterror238=$metadataxd[$y][2];}
			if($metadataxd[$y][1]=="235U Decay Constant"){$udecayconstant235=$metadataxd[$y][2];}
			if($metadataxd[$y][1]=="235U Decay Constant Error"){$udecayconstanterror235=$metadataxd[$y][2];}
			if($metadataxd[$y][1]=="232Th Decay Constant "){$thdecayconstant232=$metadataxd[$y][2];}
			if($metadataxd[$y][1]=="232Th Decay Constant Error"){$thdecayconstanterror232=$metadataxd[$y][2];}
			if($metadataxd[$y][1]=="230Th Decay Constant"){$thdecayconstant230=$metadataxd[$y][2];}
			if($metadataxd[$y][1]=="230Th Decay Constant Error"){$thdecayconstanterror230=$metadataxd[$y][2];}
			if($metadataxd[$y][1]=="238U/235U"){$u238u235=$metadataxd[$y][2];}
			if($metadataxd[$y][1]=="Decay Constant Reference"){$decayconstantreference=$metadataxd[$y][2];}
			if($metadataxd[$y][1]=="147Sm Decay Constant"){$smdecayconstant147=$metadataxd[$y][2];}
			if($metadataxd[$y][1]=="147Sm Decay Constant Error"){$smdecayconstanterror147=$metadataxd[$y][2];}
			if($metadataxd[$y][1]=="147Sm Decay Constant Reference"){$smdecayconstantreference147=$metadataxd[$y][2];}
			if($metadataxd[$y][1]=="Decay Constant Comment"){$decayconstantcomment=$metadataxd[$y][2];}

			if($metadataxd[$y][1]=="Spike Type U-Th-Sm"){$spiketypeuthsm=$metadataxd[$y][2];}
			if($metadataxd[$y][1]=="Spike Type He"){$spiketypehe=$metadataxd[$y][2];}
			if($metadataxd[$y][1]=="Standard Mineral"){$standardmineral=$metadataxd[$y][2];}
			if($metadataxd[$y][1]=="Standard Mineral Reference"){$standardmineralreference=$metadataxd[$y][2];}
			if($metadataxd[$y][1]=="Standard True Age"){$standardtrueage=$metadataxd[$y][2];}
			if($metadataxd[$y][1]=="Standard True Age Error"){$standardtrueageerror=$metadataxd[$y][2];}
			if($metadataxd[$y][1]=="Standard Measured Age"){$standardmeasuredage=$metadataxd[$y][2];}
			if($metadataxd[$y][1]=="Standard Measured Age Error"){$standardmeasuredageerror=$metadataxd[$y][2];}
			if($metadataxd[$y][1]=="Data Reduction Comment"){$datareductioncomment=$metadataxd[$y][2];}
		
			if($metadataxd[$y][1]=="Analysis Purpose" || $metadataxd[$y][1]=="Additional Analysis Purpose"){$agenum++;eval("\$analysispurpose$agenum=\$metadataxd[\$y][2];");}
			if($metadataxd[$y][1]=="Preferred Age Type" || $metadataxd[$y][1]=="Additional Age Type"){eval("\$preferredagetype$agenum=\$metadataxd[\$y][2];");}
			if($metadataxd[$y][1]=="Preferred Age" || $metadataxd[$y][1]=="Age"){eval("\$preferredage$agenum=\$metadataxd[\$y][2];");}
			if($metadataxd[$y][1]=="Preferred Age Error" || $metadataxd[$y][1]=="Age Error (Analytical)"){eval("\$preferredageerror$agenum=\$metadataxd[\$y][2];");}
			if($metadataxd[$y][1]=="MSWD" ){eval("\$mswd$agenum=\$metadataxd[\$y][2];");}
			if($metadataxd[$y][1]=="Age Error (Systematic)" ){eval("\$ageerrorsystematic$agenum=\$metadataxd[\$y][2];");}
			if($metadataxd[$y][1]=="Preferred Age Included Analyses" || $metadataxd[$y][1]=="Included Analyses"){eval("\$preferredageincludedanalyses$agenum=\$metadataxd[\$y][2];");}
			if($metadataxd[$y][1]=="Preferred Age Explanation" || $metadataxd[$y][1]=="Age Comment"){eval("\$preferredageexplanation$agenum=\$metadataxd[\$y][2];");}

		}



		$modigsn=$uniqueid;
		$sampleid=$samplename;
	
		include("fetchigsn.php");


















		//first, let's insert the ages here
		//*************************************
		//*************************************
		//*************************************
		//*************************************
		//*************************************
		//*************************************
		//*************************************
		//*************************************
		//*************************************
		//*************************************
		//*************************************
		//*************************************


		
		if($analysispurpose1!=""){
			
			$sampleagepkey=$db->get_var("select nextval('sample_age_seq')");
		
			
		
			$query="insert into sample_age (
				sample_age_pkey,
				sample_pkey,
				age_name,";
			if($preferredage1!=""){
				$query.="
				age_value,
				";
			}
			$query.="	
				uncertainty_type,
				one_sigma,
				mswd,
				preferred,
				age_type
			) values (
				$sampleagepkey,
				$sample_pkey,
				'$analysispurpose1',";
			if($preferredage1!=""){
				$query.="
				$preferredage1,";
			}
			$query.="
				'ABS',
				'$preferredageerror1',
				'$mswd1',
				'1',
				'$preferredagetype1'
			)
			";
			
			$db->query($query);
			
			
		}
		if($analysispurpose2!=""){
			
			$sampleagepkey=$db->get_var("select nextval('sample_age_seq')");
		
			
		
			$query="insert into sample_age (
				sample_age_pkey,
				sample_pkey,
				age_name,";
			if($preferredage2!=""){
				$query.="
				age_value,
				";
			}
			$query.="	
				uncertainty_type,
				one_sigma,
				mswd,
				preferred,
				age_type
			) values (
				$sampleagepkey,
				$sample_pkey,
				'$analysispurpose2',";
			if($preferredage2!=""){
				$query.="
				$preferredage2,";
			}
			$query.="
				'ABS',
				'$preferredageerror2',
				'$mswd2',
				'1',
				'$preferredagetype2'
			)
			";
			
			$db->query($query);
			
		}
		if($analysispurpose3!=""){
			
			$sampleagepkey=$db->get_var("select nextval('sample_age_seq')");
		
			
		
			$query="insert into sample_age (
				sample_age_pkey,
				sample_pkey,
				age_name,";
			if($preferredage3!=""){
				$query.="
				age_value,
				";
			}
			$query.="	
				uncertainty_type,
				one_sigma,
				mswd,
				preferred,
				age_type
			) values (
				$sampleagepkey,
				$sample_pkey,
				'$analysispurpose3',";
			if($preferredage3!=""){
				$query.="
				$preferredage3,";
			}
			$query.="
				'ABS',
				'$preferredageerror3',
				'$mswd3',
				'1',
				'$preferredagetype3'
			)
			";
			
			$db->query($query);
			
		}
		if($analysispurpose4!=""){
			
			$sampleagepkey=$db->get_var("select nextval('sample_age_seq')");
		
			
		
			$query="insert into sample_age (
				sample_age_pkey,
				sample_pkey,
				age_name,";
			if($preferredage4!=""){
				$query.="
				age_value,
				";
			}
			$query.="	
				uncertainty_type,
				one_sigma,
				mswd,
				preferred,
				age_type
			) values (
				$sampleagepkey,
				$sample_pkey,
				'$analysispurpose4',";
			if($preferredage4!=""){
				$query.="
				$preferredage4,";
			}
			$query.="
				'ABS',
				'$preferredageerror4',
				'$mswd4',
				'1',
				'$preferredagetype4'
			)
			";
			
			$db->query($query);
			
		}
		if($analysispurpose5!=""){
			
			$sampleagepkey=$db->get_var("select nextval('sample_age_seq')");
		
			
		
			$query="insert into sample_age (
				sample_age_pkey,
				sample_pkey,
				age_name,";
			if($preferredage5!=""){
				$query.="
				age_value,
				";
			}
			$query.="	
				uncertainty_type,
				one_sigma,
				mswd,
				preferred,
				age_type
			) values (
				$sampleagepkey,
				$sample_pkey,
				'$analysispurpose5',";
			if($preferredage5!=""){
				$query.="
				$preferredage5,";
			}
			$query.="
				'ABS',
				'$preferredageerror5',
				'$mswd5',
				'1',
				'$preferredagetype5'
			)
			";
			
			$db->query($query);
			
		}
		if($analysispurpose6!=""){
			
			$sampleagepkey=$db->get_var("select nextval('sample_age_seq')");
		
			
		
			$query="insert into sample_age (
				sample_age_pkey,
				sample_pkey,
				age_name,";
			if($preferredage6!=""){
				$query.="
				age_value,
				";
			}
			$query.="	
				uncertainty_type,
				one_sigma,
				mswd,
				preferred,
				age_type
			) values (
				$sampleagepkey,
				$sample_pkey,
				'$analysispurpose6',";
			if($preferredage6!=""){
				$query.="
				$preferredage6,";
			}
			$query.="
				'ABS',
				'$preferredageerror6',
				'$mswd6',
				'1',
				'$preferredagetype6'
			)
			";
			
			$db->query($query);
			
		}
		if($analysispurpose7!=""){
			
			$sampleagepkey=$db->get_var("select nextval('sample_age_seq')");
		
			
		
			$query="insert into sample_age (
				sample_age_pkey,
				sample_pkey,
				age_name,";
			if($preferredage7!=""){
				$query.="
				age_value,
				";
			}
			$query.="	
				uncertainty_type,
				one_sigma,
				mswd,
				preferred,
				age_type
			) values (
				$sampleagepkey,
				$sample_pkey,
				'$analysispurpose7',";
			if($preferredage7!=""){
				$query.="
				$preferredage7,";
			}
			$query.="
				'ABS',
				'$preferredageerror7',
				'$mswd7',
				'1',
				'$preferredagetype7'
			)
			";
			
			$db->query($query);
			
		}
		if($analysispurpose8!=""){
			
			$sampleagepkey=$db->get_var("select nextval('sample_age_seq')");
		
			
		
			$query="insert into sample_age (
				sample_age_pkey,
				sample_pkey,
				age_name,";
			if($preferredage8!=""){
				$query.="
				age_value,
				";
			}
			$query.="	
				uncertainty_type,
				one_sigma,
				mswd,
				preferred,
				age_type
			) values (
				$sampleagepkey,
				$sample_pkey,
				'$analysispurpose8',";
			if($preferredage8!=""){
				$query.="
				$preferredage8,";
			}
			$query.="
				'ABS',
				'$preferredageerror8',
				'$mswd8',
				'1',
				'$preferredagetype8'
			)
			";
			
			$db->query($query);
			
		}
		if($analysispurpose9!=""){
			
			$sampleagepkey=$db->get_var("select nextval('sample_age_seq')");
		
			
		
			$query="insert into sample_age (
				sample_age_pkey,
				sample_pkey,
				age_name,";
			if($preferredage9!=""){
				$query.="
				age_value,
				";
			}
			$query.="	
				uncertainty_type,
				one_sigma,
				mswd,
				preferred,
				age_type
			) values (
				$sampleagepkey,
				$sample_pkey,
				'$analysispurpose9',";
			if($preferredage9!=""){
				$query.="
				$preferredage9,";
			}
			$query.="
				'ABS',
				'$preferredageerror9',
				'$mswd9',
				'1',
				'$preferredagetype9'
			)
			";
			
			$db->query($query);
			
		}
		if($analysispurpose10!=""){
			
			$sampleagepkey=$db->get_var("select nextval('sample_age_seq')");
		
			
		
			$query="insert into sample_age (
				sample_age_pkey,
				sample_pkey,
				age_name,";
			if($preferredage10!=""){
				$query.="
				age_value,
				";
			}
			$query.="	
				uncertainty_type,
				one_sigma,
				mswd,
				preferred,
				age_type
			) values (
				$sampleagepkey,
				$sample_pkey,
				'$analysispurpose10',";
			if($preferredage10!=""){
				$query.="
				$preferredage10,";
			}
			$query.="
				'ABS',
				'$preferredageerror10',
				'$mswd10',
				'1',
				'$preferredagetype10'
			)
			";
			
			$db->query($query);
			
		}
		
		

		


		
		include("includes/geochron-secondary-header.htm");
		
		echo "<h1>Success!</h1>";
		
		if($newlist==""){
			$gotourl="managedata.php";
			$buttonlabel="Finish";
			$donemessage="<font color=\"green\"><b>This file has been uploaded.</b></font><br><br>";
		}else{
			$gotourl="uthhexls.php";
			$buttonlabel="Continue Uploading Samples";
		}
		
		?>
		
		Your sample was uploaded successfully. Below is the data as it was uploaded.<br><br>
		
		<?=$donemessage?>
		
		<INPUT TYPE="button" value="<?=$buttonlabel?>" onClick="parent.location='<?=$gotourl?>'"><br><br>
		
		<?
		//start whole xml file
		$wholexml="<sample>\n\t<sampleinfo>\n";

		if($samplename!=""){$wholexml.="\t\t<inputtype>$inputtype</inputtype>\n";}




		if($samplename!=""){$wholexml.="\t\t<samplename>$samplename</samplename>\n";}
		if($uniqueid!=""){$wholexml.="\t\t<uniqueid>$uniqueid</uniqueid>\n";}
		if($labname!=""){$wholexml.="\t\t<labname>$labname</labname>\n";}
		if($analystname!=""){$wholexml.="\t\t<analystname>$analystname</analystname>\n";}
		if($instrumentalmethodhe!=""){$wholexml.="\t\t<instrumentalmethodhe>$instrumentalmethodhe</instrumentalmethodhe>\n";}
		if($instrumentalmethodreferencehe!=""){$wholexml.="\t\t<instrumentalmethodreferencehe>$instrumentalmethodreferencehe</instrumentalmethodreferencehe>\n";}
		if($instrumentalmethoduthsm!=""){$wholexml.="\t\t<instrumentalmethoduthsm>$instrumentalmethoduthsm</instrumentalmethoduthsm>\n";}
		if($instrumentalmethodreferenceuthsm!=""){$wholexml.="\t\t<instrumentalmethodreferenceuthsm>$instrumentalmethodreferenceuthsm</instrumentalmethodreferenceuthsm>\n";}
		if($alphaejectioncorrectionmethod!=""){$wholexml.="\t\t<alphaejectioncorrectionmethod>$alphaejectioncorrectionmethod</alphaejectioncorrectionmethod>\n";}
		if($alphaejectioncorrectionmethodreference!=""){$wholexml.="\t\t<alphaejectioncorrectionmethodreference>$alphaejectioncorrectionmethodreference</alphaejectioncorrectionmethodreference>\n";}
		if($mineral!=""){$wholexml.="\t\t<mineral>$mineral</mineral>\n";}
		if($comment!=""){$wholexml.="\t\t<comment>$comment</comment>\n";}
		if($udecayconstant238!=""){$wholexml.="\t\t<udecayconstant238>$udecayconstant238</udecayconstant238>\n";}
		if($udecayconstanterror238!=""){$wholexml.="\t\t<udecayconstanterror238>$udecayconstanterror238</udecayconstanterror238>\n";}
		if($udecayconstant235!=""){$wholexml.="\t\t<udecayconstant235>$udecayconstant235</udecayconstant235>\n";}
		if($udecayconstanterror235!=""){$wholexml.="\t\t<udecayconstanterror235>$udecayconstanterror235</udecayconstanterror235>\n";}
		if($thdecayconstant232!=""){$wholexml.="\t\t<thdecayconstant232>$thdecayconstant232</thdecayconstant232>\n";}
		if($thdecayconstanterror232!=""){$wholexml.="\t\t<thdecayconstanterror232>$thdecayconstanterror232</thdecayconstanterror232>\n";}
		if($thdecayconstant230!=""){$wholexml.="\t\t<thdecayconstant230>$thdecayconstant230</thdecayconstant230>\n";}
		if($thdecayconstanterror230!=""){$wholexml.="\t\t<thdecayconstanterror230>$thdecayconstanterror230</thdecayconstanterror230>\n";}
		if($u238u235!=""){$wholexml.="\t\t<u238u235>$u238u235</u238u235>\n";}
		if($decayconstantreference!=""){$wholexml.="\t\t<decayconstantreference>$decayconstantreference</decayconstantreference>\n";}
		if($smdecayconstant147!=""){$wholexml.="\t\t<smdecayconstant147>$smdecayconstant147</smdecayconstant147>\n";}
		if($smdecayconstanterror147!=""){$wholexml.="\t\t<smdecayconstanterror147>$smdecayconstanterror147</smdecayconstanterror147>\n";}
		if($smdecayconstantreference147!=""){$wholexml.="\t\t<smdecayconstantreference147>$smdecayconstantreference147</smdecayconstantreference147>\n";}
		if($decayconstantcomment!=""){$wholexml.="\t\t<decayconstantcomment>$decayconstantcomment</decayconstantcomment>\n";}









		$wholexml.="\t</sampleinfo>\n";

		$wholexml.="\t<samplemetadata>\n";
		
		$wholexml.="\t\t<sampleid>$isampleid</sampleid>\n";
		$wholexml.="\t\t<agemin>$iagemin</agemin>\n";
		$wholexml.="\t\t<agemax>$iagemax</agemax>\n";
		$wholexml.="\t\t<sampledescription>$isampledescription</sampledescription>\n";
		$wholexml.="\t\t<geoobjecttype>$igeoobjecttype</geoobjecttype>\n";
		$wholexml.="\t\t<geoobjectclassification>$igeoobjectclassification</geoobjectclassification>\n";
		$wholexml.="\t\t<collectionmethod>$icollectionmethod</collectionmethod>\n";
		$wholexml.="\t\t<material>$imaterial</material>\n";
		$wholexml.="\t\t<latitude>$ilatitude</latitude>\n";
		$wholexml.="\t\t<longitude>$ilongitude</longitude>\n";
		$wholexml.="\t\t<samplecomment>$isamplecomment</samplecomment>\n";
		$wholexml.="\t\t<collector>$icollector</collector>\n";
		$wholexml.="\t\t<materialclassification>$imaterialclassification</materialclassification>\n";
		$wholexml.="\t\t<PrimaryLocationName>$iPrimaryLocationName</PrimaryLocationName>\n";
		$wholexml.="\t\t<PrimaryLocationType>$iPrimaryLocationType</PrimaryLocationType>\n";
		$wholexml.="\t\t<LocationDescription>$iLocationDescription</LocationDescription>\n";
		$wholexml.="\t\t<Locality>$iLocality</Locality>\n";
		$wholexml.="\t\t<LocalityDescription>$iLocalityDescription</LocalityDescription>\n";
		$wholexml.="\t\t<Country>$iCountry</Country>\n";
		$wholexml.="\t\t<Provice>$iProvice</Provice>\n";
		$wholexml.="\t\t<County>$iCounty</County>\n";
		$wholexml.="\t\t<CityOrTownship>$iCityOrTownship</CityOrTownship>\n";
		$wholexml.="\t\t<Platform>$iPlatform</Platform>\n";
		$wholexml.="\t\t<PlatformID>$iPlatformID</PlatformID>\n";
		$wholexml.="\t\t<OriginalArchivalInstitution>$iOriginalArchivalInstitution</OriginalArchivalInstitution>\n";
		$wholexml.="\t\t<OriginalArchivalContact>$iOriginalArchivalContact</OriginalArchivalContact>\n";
		$wholexml.="\t\t<MostRecentArchivalInstitution>$iMostRecentArchivalInstitution</MostRecentArchivalInstitution>\n";
		$wholexml.="\t\t<MostRecentArchivalContact>$iMostRecentArchivalContact</MostRecentArchivalContact>\n";
		
		$wholexml.="\t</samplemetadata>\n";

		$wholexml.="\t<ages>\n";

		if($analysispurpose1!=""){$wholexml.="\t\t<age analysispurpose=\"$analysispurpose1\" value=\"$preferredage1\" error=\"$preferredageerror1\" type=\"$preferredagetype1\" mswd=\"$mswd1\" ageerrorsystematic=\"$ageerrorsystematic1\" preferredageincludedanalyses=\"$preferredageincludedanalyses1\" preferredageexplanation=\"$preferredageexplanation1\" />\n";}
		if($analysispurpose2!=""){$wholexml.="\t\t<age analysispurpose=\"$analysispurpose2\" value=\"$preferredage2\" error=\"$preferredageerror2\" type=\"$preferredagetype2\" mswd=\"$mswd2\" ageerrorsystematic=\"$ageerrorsystematic2\" preferredageincludedanalyses=\"$preferredageincludedanalyses2\" preferredageexplanation=\"$preferredageexplanation2\" />\n";}
		if($analysispurpose3!=""){$wholexml.="\t\t<age analysispurpose=\"$analysispurpose3\" value=\"$preferredage3\" error=\"$preferredageerror3\" type=\"$preferredagetype3\" mswd=\"$mswd3\" ageerrorsystematic=\"$ageerrorsystematic3\" preferredageincludedanalyses=\"$preferredageincludedanalyses3\" preferredageexplanation=\"$preferredageexplanation3\" />\n";}
		if($analysispurpose4!=""){$wholexml.="\t\t<age analysispurpose=\"$analysispurpose4\" value=\"$preferredage4\" error=\"$preferredageerror4\" type=\"$preferredagetype4\" mswd=\"$mswd4\" ageerrorsystematic=\"$ageerrorsystematic4\" preferredageincludedanalyses=\"$preferredageincludedanalyses4\" preferredageexplanation=\"$preferredageexplanation4\" />\n";}
		if($analysispurpose5!=""){$wholexml.="\t\t<age analysispurpose=\"$analysispurpose5\" value=\"$preferredage5\" error=\"$preferredageerror5\" type=\"$preferredagetype5\" mswd=\"$mswd5\" ageerrorsystematic=\"$ageerrorsystematic5\" preferredageincludedanalyses=\"$preferredageincludedanalyses5\" preferredageexplanation=\"$preferredageexplanation5\" />\n";}
		if($analysispurpose6!=""){$wholexml.="\t\t<age analysispurpose=\"$analysispurpose6\" value=\"$preferredage6\" error=\"$preferredageerror6\" type=\"$preferredagetype6\" mswd=\"$mswd6\" ageerrorsystematic=\"$ageerrorsystematic6\" preferredageincludedanalyses=\"$preferredageincludedanalyses6\" preferredageexplanation=\"$preferredageexplanation6\" />\n";}
		if($analysispurpose7!=""){$wholexml.="\t\t<age analysispurpose=\"$analysispurpose7\" value=\"$preferredage7\" error=\"$preferredageerror7\" type=\"$preferredagetype7\" mswd=\"$mswd7\" ageerrorsystematic=\"$ageerrorsystematic7\" preferredageincludedanalyses=\"$preferredageincludedanalyses7\" preferredageexplanation=\"$preferredageexplanation7\" />\n";}
		if($analysispurpose8!=""){$wholexml.="\t\t<age analysispurpose=\"$analysispurpose8\" value=\"$preferredage8\" error=\"$preferredageerror8\" type=\"$preferredagetype8\" mswd=\"$mswd8\" ageerrorsystematic=\"$ageerrorsystematic8\" preferredageincludedanalyses=\"$preferredageincludedanalyses8\" preferredageexplanation=\"$preferredageexplanation8\" />\n";}
		if($analysispurpose9!=""){$wholexml.="\t\t<age analysispurpose=\"$analysispurpose9\" value=\"$preferredage9\" error=\"$preferredageerror9\" type=\"$preferredagetype9\" mswd=\"$mswd9\" ageerrorsystematic=\"$ageerrorsystematic9\" preferredageincludedanalyses=\"$preferredageincludedanalyses9\" preferredageexplanation=\"$preferredageexplanation9\" />\n";}
		if($analysispurpose10!=""){$wholexml.="\t\t<age analysispurpose=\"$analysispurpose10\" value=\"$preferredage10\" error=\"$preferredageerror10\" type=\"$preferredagetype10\" mswd=\"$mswd10\" ageerrorsystematic=\"$ageerrorsystematic10\" preferredageincludedanalyses=\"$preferredageincludedanalyses10\" preferredageexplanation=\"$preferredageexplanation10\" />\n";}



		$wholexml.="\t</ages>\n";
		
		$wholexml.="\t<datareductionparameters>\n";

		$wholexml.="\t\t<spiketypeuthsm>$spiketypeuthsm</spiketypeuthsm>\n";
		$wholexml.="\t\t<spiketypehe>$spiketypehe</spiketypehe>\n";
		$wholexml.="\t\t<standardmineral>$standardmineral</standardmineral>\n";
		$wholexml.="\t\t<standardmineralreference>$standardmineralreference</standardmineralreference>\n";
		$wholexml.="\t\t<standardtrueage>$standardtrueage</standardtrueage>\n";
		$wholexml.="\t\t<standardtrueageerror>$standardtrueageerror</standardtrueageerror>\n";
		$wholexml.="\t\t<standardmeasuredage>$standardmeasuredage</standardmeasuredage>\n";
		$wholexml.="\t\t<standardmeasuredageerror>$standardmeasuredageerror</standardmeasuredageerror>\n";
		$wholexml.="\t\t<datareductioncomment>$datareductioncomment</datareductioncomment>\n";

		$wholexml.="\t</datareductionparameters>\n";
		



		



		$randnum=$_POST['f'];
		
		$newfilename="heliostemp/$randnum/$orig_filename";
		
		//echo "newfilename: $newfilename<br>";

		//$headers=array("aliquot_name","mineral","age_ma","age_err_ma","u_ppm","th_ppm","sm_147_ppm","ue","thUu","he","mass_ug","ft","mean_esr");
		$headers=array("aliquot_name","mineral","age_ma","age_err_ma","u_ppm","th_ppm","sm_147_ppm","thUu","he","mass_ug","ft");

		
		$wholexml.="\t<fractions>\n";
			
			/*
			**********************************
			$data = new Spreadsheet_Excel_Reader();

			$data->setOutputEncoding('CP1251');

			$data->read($newfilename);


			$boundsheets=$data->boundsheets;
			$sheets=$data->sheets;

			//check age sheet
			$metadataxd=$sheets[0]['cells'];

			//print_r($metadataxd);exit();
			**********************************
			*/

			//first reduced data
			$numrows=$sheets[2]['numRows'];
			$xd=$sheets[2][cells];




			
		
			//get maxy
			for($y=4;$y<=$numrows;$y++){
				if($xd[$y][1]!=""){
					$maxy=$y;
				}
			}
			
			//echo "maxy: $maxy<br><br>";
			
			for($y=4;$y<=$maxy;$y++){



				$thisval=$xd[$y][1];

					//OK, put in fraction for this one
					$wholexml.="\t\t<fraction>\n";
					
					$x=1;
					foreach($headers as $header){
						
						$thisval=$xd[$y][$x];
						
						$wholexml.="\t\t\t<$header>".htmlentities($thisval)."</$header>\n";
						
						$x++;
					}
					
					$wholexml.="\t\t</fraction>\n";

			}

		
		$wholexml.="\t</fractions>\n";
		
		
		$y=0;

		$wholexml.="</sample>";

		//echo $wholexml;

		//exit();

		//OK, now that we have the mineral, let's put it in

		//first, fix stupid 'Not Provided' but in SESAR... what a joke.
		if($ilatitude=="Not Provided"){
			$ilatitude="";
		}

		if($ilongitude=="Not Provided"){
			$ilongitude="";
		}


		$querystring="insert into sample ( 
					sample_pkey, 
					igsn, 
					parentigsn, 
					sample_id, 
					sample_description, 
					geoobjecttype, 
					geoobjectclass, 
					collectionmethod, 
					strat_name,
					detrital_type, 
					strat_geo_age, ";
		
		if($ilongitude!="" & $ilatitude!=""){
		$querystring.="
					longitude, 
					latitude, ";
		}

		if($strat_age_min!=""){
		$querystring.="
					strat_age_min, ";
		}

		if($strat_age_max!=""){
		$querystring.="
					strat_age_max, ";
		}

		if($youngestfractiondate!=""){
		$querystring.="
					youngest_frac_date, ";
		}

		if($oldestfractiondate!=""){
		$querystring.="
					oldest_frac_date, ";
		}

		$querystring.="
					sample_comment, 
					analyst_name, 
					laboratoryname, 
					uploaddate,
					userpkey,
					filename,
					username,
					orig_filename,
					ecproject,";
		if($ilongitude!="" & $ilatitude!=""){
		$querystring.="
					mypoint,";
		}
		$querystring.="
					publ,
					collector,
					rocktype,
					primarylocationname,
					primarylocationtype,
					locationdescription,
					locality,
					localitydescription,
					country,
					provice,
					county,
					cityortownship,
					platform,
					platformid,
					originalarchivalinstitution,
					originalarchivalcontact,
					mostrecentarchivalinstitution,
					mostrecentarchivalcontact,
					mineral,
					upstream,
					aliquotname,
					material,
					purpose
					) values ( 
					$sample_pkey, 
					'$modigsn', 
					'$iparentigsn', 
					'$sampleid', 
					'$isampledescription', 
					'$igeoobjecttype', 
					'$igeoobjectclassification', 
					'$icollectionmethod',
					'$strat_name',
					'$detritaltype',
					'$strat_geo_age', ";
		if($ilongitude!="" & $ilatitude!=""){
		$querystring.="
					$ilongitude,
					$ilatitude,";
		}
		
		if($strat_age_min!=""){
		$querystring.="
					$strat_age_min, ";
		}

		if($strat_age_max!=""){
		$querystring.="
					$strat_age_max, ";
		}

		if($youngestfractiondate!=""){
		$querystring.="
					$youngestfractiondate, ";
		}

		if($oldestfractiondate!=""){
		$querystring.="
					$oldestfractiondate, ";
		}

		$querystring.="
					'$isamplecomment',
					'$analystname',
					'$labname',
					'$uploaddate',
					$userpkey,
					'$savefilename',
					'$username',
					'$orig_filename',
					'uthhelegacy',";
		if($ilongitude!="" & $ilatitude!=""){
		$querystring.="
					ST_PointFromText('POINT($ilongitude $ilatitude)',-1),";
		}
		$querystring.="
					$public,
					'$icollector',
					'$imaterialclassification',
					'$iPrimaryLocationName',
					'$iPrimaryLocationType',
					'$iLocationDescription',
					'$iLocality',
					'$iLocalityDescription',
					'$iCountry',
					'$iProvice',
					'$iCounty',
					'$iCityOrTownship',
					'$iPlatform',
					'$iPlatformID',
					'$iOriginalArchivalInstitution',
					'$iOriginalArchivalContact',
					'$iMostRecentArchivalInstitution',
					'$iMostRecentArchivalContact',
					'$mineral',
					$isupstream,
					'$sampleid',
					'".strtolower($mineral)."',
					'$analysispurpose'
					)";
					
					
		//echo nl2br($querystring);exit();
		//exit();
		
		$db->query($querystring);


		
		// Create an instance
		//("files/$geochron_pkey.xls");

		//move xls file to files
		//bool copy ( string $source , string $dest [, resource $context ] )
		copy("heliostemp/$randnum"."/".$filename,"files/".$randnum.".xls");


		if($donemessage!=""){
			exec("rm -rf heliostemp/$f");
		}



		//write XML file contents
		
		//echo "savefilename: $savefilename";
		
		$myfile = "files/$savefilename";
		$fh = fopen($myfile, 'w') or die("can't open file");
		//$stringdata = $_POST['content'];
		
		//$stringdata = preg_replace("/[\n\r]/","",$_POST['content']); 
		
		
		
		fwrite($fh, $wholexml);
		fclose($fh);
		
		
		//$xsltfile="uthhexls.xslt";
		
		$xsltfile="http://www.geochron.org/templates/uthhexls_".$sample_pkey.".xslt";
		


		//echo "xsltfile: $xsltfile";
		
		//$geochron_pkey
		
		$xp = new XsltProcessor();
		// create a DOM document and load the XSL stylesheet
		$xsl = new DomDocument;
		$xsl->load($xsltfile);
		
		// import the XSL styelsheet into the XSLT process
		$xp->importStylesheet($xsl);
		
		// create a DOM document and load the XML datat
		$xml_doc = new DomDocument;
		$xml_doc->load("$myfile");
		
		// transform the XML into HTML using the XSL file
		if ($html = $xp->transformToXML($xml_doc)) {
			echo $html;
		} else {
			trigger_error('XSL transformation failed.', E_USER_ERROR);
		} // if 

		//echo "</table>";

		?>
			<br><br><br><br><br>
			<br><br><br><br><br>
			<br><br><br><br><br>
			<br><br><br><br><br>
			<br><br><br><br><br>
		<?



		include("includes/geochron-secondary-footer.htm");


		exit();
















































}//end if post imagesubmit




































if($error!=""){

	$error="<h2><font color=\"red\">Error!</font></h2><font color=\"red\">$error<br>Please try again.</font><br><br>";

}


include("includes/geochron-secondary-header.htm");
?>

<script type="text/javascript">
function formvalidate(){
	//alert('hey');
	var errors='';
	//if(document.forms["uploadform"]["samplename"].value=="" || document.forms["uploadform"]["samplename"].value==null){errors=errors+'Sample Name must be provided.\n';}
	if(document.forms["uploadform"]["uthhefile"].value=="" || document.forms["uploadform"]["uthhefile"].value==null){errors=errors+'Please choose a file.\n';}

	if(errors!="" && errors!=null){
		alert(errors);
		return false;
	}
}
</script>

<h1>Upload (U-Th)He XLS Data</h1><br>

<?=$error?>

<form name="uploadform" method="POST" onsubmit="return formvalidate();" enctype="multipart/form-data">

		<table style="font-size:10px;">
			<tr>
				<td colspan="2"><h1>Sample File</h2></td>
			</tr>
			<tr>
				<td>(U-Th)_He File (.xls):</td><td><input type="file" name="uthhefile" size="40" ></td>
			</tr>
			<tr>
				<td>Overwrite?:</td><td>
					<input type="radio" name="overwrite" value="no" checked>&nbsp;No&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="radio" name="overwrite" value="yes">&nbsp;Yes
				</td>
			</tr>
			<tr>
				<td>Public?:</td><td>
					<input type="radio" name="public" value="0" checked>&nbsp;No&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="radio" name="public" value="1">&nbsp;Yes
				</td>
			</tr>
		</table>



<input type="submit" name="submit" value="Submit">

</form>

<br><br>
Download Data Template:<br><br>
<a href="templates/(U-Th)_He data template.xls">(U-Th)_He data template.xls</a>

<br><br><br><br><br>
<br><br><br><br><br>
<br><br><br><br><br>
<br><br><br><br><br>
<br><br><br><br><br>

<?

include("includes/geochron-secondary-footer.htm");

?>