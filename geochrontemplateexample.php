<?PHP
/**
 * geochrontemplateexample.php
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



$headers[0][1]="agemapb206u238";
$headers[1][1]="agemapb206u2381se";
$headers[2][1]="agemapb207u235";
$headers[3][1]="agemapb207u2351se";
$headers[4][1]="agemapb207pb206";
$headers[5][1]="agemapb207pb2061se";
$headers[6][1]="correlationofconcordiaellipses";
$headers[7][1]="pb206ru238";
$headers[8][1]="pb206ru2381se";
$headers[9][1]="pb207ru235";
$headers[10][1]="pb207ru2351se";
$headers[11][1]="pb207rpb206r";
$headers[12][1]="pb207rpb206r1se";
$headers[13][1]="pctradiogenicpb206";
$headers[14][1]="pctradiogenicpb2061se";
$headers[15][1]="pctradiogenicpb207";
$headers[16][1]="pctradiogenicpb2071se";
$headers[17][1]="pb206u238";
$headers[18][1]="pb206u2381se";
$headers[19][1]="pb207u235";
$headers[20][1]="pb207u2351se";
$headers[21][1]="pb207pb206";
$headers[22][1]="pb207pb2061se";
$headers[23][1]="th";
$headers[24][1]="th1se";
$headers[25][1]="u";
$headers[26][1]="u1se";
$headers[27][1]="th94zr2o";
$headers[28][1]="th94zr2o1se";
$headers[29][1]="u94zr2o";
$headers[30][1]="u94zr2o1se";
$headers[31][1]="agemapb208th232";
$headers[32][1]="agemapb208th2321se";
$headers[33][1]="pb208th232";
$headers[34][1]="pb208th2321se";
$headers[35][1]="pctradiogenicpb208";
$headers[36][1]="pctradiogenicpb2081se";
$headers[37][1]="commonpb206pb204";
$headers[38][1]="commonpb207pb204";
$headers[39][1]="commonpb208pb204";
$headers[40][1]="pbcorr";
$headers[41][1]="blksize";
$headers[42][1]="name";
$headers[43][1]="rejected";

$headers[0][0]="Age (Ma) 206Pb/ 238U";
$headers[1][0]="Age (Ma) 206Pb/ 238U 1 s.e.";
$headers[2][0]="Age (Ma) 207Pb/ 235U";
$headers[3][0]="Age (Ma) 207Pb/ 235U 1 s.e.";
$headers[4][0]="Age (Ma) 207Pb/ 206Pb";
$headers[5][0]="Age (Ma) 207Pb/ 206Pb 1 s.e.";
$headers[6][0]="Correlation of Concordia Ellipses";
$headers[7][0]="206Pb*/ 238U";
$headers[8][0]="206Pb*/ 238U 1 s.e.";
$headers[9][0]="207Pb*/ 235U";
$headers[10][0]="207Pb*/ 235U 1 s.e.";
$headers[11][0]="207Pb*/ 206Pb*";
$headers[12][0]="207Pb*/ 206Pb* 1 s.e.";
$headers[13][0]="% Radiogenic 206Pb";
$headers[14][0]="% Radiogenic 206Pb 1 s.e.";
$headers[15][0]="% Radiogenic 207Pb";
$headers[16][0]="% Radiogenic 207Pb 1 s.e.";
$headers[17][0]="206Pb/ 238U";
$headers[18][0]="206Pb/ 238U 1 s.e.";
$headers[19][0]="207Pb/ 235U";
$headers[20][0]="207Pb/ 235U 1 s.e.";
$headers[21][0]="207Pb/ 206Pb";
$headers[22][0]="207Pb/ 206Pb 1 s.e.";
$headers[23][0]="Th";
$headers[24][0]="Th 1 s.e.";
$headers[25][0]="U";
$headers[26][0]="U 1 s.e.";
$headers[27][0]="Th/ 94Zr2 O";
$headers[28][0]="Th/ 94Zr2 O 1 s.e.";
$headers[29][0]="U/ 94Zr2 O";
$headers[30][0]="U/ 94Zr2 O 1 s.e.";
$headers[31][0]="Age (Ma) 208Pb/ 232Th";
$headers[32][0]="Age (Ma) 208Pb/ 232Th 1 s.e.";
$headers[33][0]="208Pb/ 232Th";
$headers[34][0]="208Pb/ 232Th 1 s.e.";
$headers[35][0]="% Radiogenic 208Pb";
$headers[36][0]="% Radiogenic 208Pb 1 s.e.";
$headers[37][0]="Common 206Pb/ 204Pb";
$headers[38][0]="Common 207Pb/ 204Pb";
$headers[39][0]="Common 208Pb/ 204Pb";
$headers[40][0]="Pb corr.";
$headers[41][0]="Blk Size";
$headers[42][0]="Name";
$headers[43][0]="Rejected";

function get_zip_originalsize($filename) {
    $size = 0;
    $resource = zip_open($filename);
    while ($dir_resource = zip_read($resource)) {
        $size += zip_entry_filesize($dir_resource);
    }
    zip_close($resource);

    return $size;
}

include("db.php");

session_start();

include("logincheck.php");

if($_GET['s']!=""){

	$samplename=$_GET['s'];

}else{

	$samplename=$_POST['s'];
}



if($_POST['submitfile']!=""){


	//file has been submitted. Check it here and save it for 
	//processing samples
	
	if($error==""){
	
		//check filetype here
		//$filetype=$_FILES['zipsfile']['type'];
		
		//if($filetype!="application/zip"){
		
			//$error.=$errordelim."Wrong file type detected ($filetype) File must be zip archive.";$errordelim="<br>";
		
		//}
		
		//should check from filename because 'type' can vary
		$pos = strpos($_FILES['zipsfile']['name'],".zip");
		
		if($pos === false) {
			$error.=$errordelim."Wrong file type detected. File must be zip archive.";$errordelim="<br>";
		}
		
		
		
	}
	

	if($error==""){
		//unzip file here
		//var_dump($_FILES);
		
		$randnum=$db->get_var("select nextval('zips_seq')");
		
		$filename=str_replace(" ","_",$_FILES['zipsfile']['name']);
		
		//$filename=$_FILES['zipsfile']['name'];
		
		$tempname=$_FILES['zipsfile']['tmp_name'];
		
		$orig_filename=str_replace(" ","_",$_FILES['zipsfile']['name']);

		//$orig_filename=$_FILES['zipsfile']['name'];


		mkdir("zipstemp/$randnum");

		//mkdir("zipstemp/z$randnum");
		$mydir="zipstemp/$randnum";
		
		
		//echo "filename:$filename<br>";
		//echo "tempname:$tempname<br>";
		
		$newfilename="zipstemp/$randnum"."/".$filename;
		
		move_uploaded_file ( $tempname , "$newfilename" );
		
		//copy("$newfilename","zipstemp/z$randnum/$filename");
		
		$linuxfilename=str_replace(" ","\ ",$newfilename);
		$linuxfilename=str_replace("(","\(",$linuxfilename);
		$linuxfilename=str_replace(")","\)",$linuxfilename);

		$linuxfilename=escapeshellcmd($newfilename);
		
		
		//echo "$linuxfilename <br>";
		

	}
	
	if($error==""){

		$size = get_zip_originalsize("$newfilename");
		//echo "original size: $size bytes\n";
		
		//if($size > 20000000){
		if($size > 20000000){
			
			$error.=$errordelim."Zip file is too large. (".number_format($size)." bytes uncompressed)";$errordelim="<br>";
		
		}
	}
	

	if($error==""){
	
		//unzip file
		exec("/usr/bin/unzip $linuxfilename -d zipstemp/$randnum/");
		
		//delete maxosx bullshit
		exec("/usr/bin/find zipstemp/$randnum/ -name '__MACOSX' -exec rm -rf {} \;",$foo);
		
		//now find the prn file
		exec("/usr/bin/find zipstemp/$randnum/ -name '*.prn'",$found);
		
		//echo "/usr/bin/find zipstemp/$randnum/ -name '*.prn' <br><br>";
		
		//var_dump($found);
		
		//exit();
		
		$mycount=count($found);
		
		//echo "mycount:$mycount<br>";
		
		if(count($found)==0){
			$error.=$errordelim.".prn file not found in zip file.";$errordelim="<br>";
		}

		if(count($found)>1){
			$error.=$errordelim."More than one .prn file found in zip file.";$errordelim="<br>";
			var_dump($found);
		}

	}


	if($error==""){
	
		$samparray=array();

		//get a list of samples here 

		exec("/usr/bin/find zipstemp/$randnum/ -name '*.ais'",$samps);
		
		if(count($samps)==0){
		
			$error.=$errordelim."No sample (.ais) files found in zip file.";$errordelim="<br>";
		
		}
	}
	
	if($error==""){
	
		$samparray=array();

		//get a list of samples here 

		exec("/usr/bin/find zipstemp/$randnum/ -name '*.asc'",$ascs);
		
		if(count($ascs)==0){
		
			$error.=$errordelim."No sample (.asc) files found in zip file.";$errordelim="<br>";
		
		}
	}
	
	if($error==""){
	
		//print_r($samps);
		
		//clean up sample names
		foreach($samps as $samp){
			//find last / and get everything after
			$slashpos=strrpos($samp , "/");
			
			
			//echo "$samp $slashpos <br>";
			
			$samp=substr($samp,$slashpos+1);
			$samp=str_replace(".ais","",$samp);

			$pos = strpos($samp,"@");
			
			if($pos === false) {
		
			}else{
				$samp=explode("@",$samp);
				$samp=$samp[0];
			}
		
			$pos = strpos($samp,"-");
			
			if($pos === false) {
		
			}else{
				$samp=explode("-",$samp);
				$samp=$samp[0];
			}
		
			$pos = strpos($samp,"=");
			
			if($pos === false) {
		
			}else{
				$samp=explode("=",$samp);
				$samp=$samp[0];
			}
		
			if(
				//change to contains, also add 91500 and r33 too
				//Zr_AS3 is a problem
				//(substr(strtolower($samp),0,3)!="res") &&
				//(substr(strtolower($samp),0,3)!="as3")
				
				(strpos(strtolower($samp),"res")===FALSE) &&
				(strpos(strtolower($samp),"as3")===FALSE) &&
				(strpos(strtolower($samp),"91500")===FALSE) &&
				(strpos(strtolower($samp),"v33")===FALSE)
				
 
			){
				if(!in_array($samp,$samparray)){
				
					$samparray[]=$samp;
					//echo "$samp foo<br>";
				
				}
				
				
			}
		
		
		}//end foreach samps
		
		$sampliststringdelim="";
		
		foreach($samparray as $sa){
		
			//echo "$sa <br>";
			$sampliststring.=$sampliststringdelim.$sa;
			$sampliststringdelim=",";
			
		
		}
		
		//echo "sampliststring: $sampliststring <br>";
		
		//OK, let's redirect here with 3 variables
		//1. f = folder (randnum)
		//2. l = sample list (from above)
		//3. n = filename ($filename)
		
		header("Location: zips.php?f=$randnum&ll=".urlencode($sampliststring)."&n=".urlencode($filename));

		
		exit();

	}



	



}





//if samplelist (l) is set, let's show a list of samples to upload and gather info

if($_GET['ll']!=""){

	//OK, samplelist is set, so let's show a list
	
	$samplelist=$_GET['ll'];
	$randnum=$_GET['f'];
	$filename=$_GET['n'];
	$sample_pkey=$_POST['sample_pkey'];
	


	//echo "samplelist: $samplelist<br>";
	//echo "randnum: $randnum<br>";
	//echo "filename: $filename<br>";

	
	include("includes/geochron-secondary-header.htm");
	
	echo "<h1>Sample Selection: $filename</h1>";

	?>
	The following samples were found in <?=$filename?>.<br>
	Please choose each below to upload to Geochron.<br><br>
	
	<table class="sample">
		<tr>
			<th>Sample Name</th>
			<th>&nbsp;</th>
		</tr>
	<?

	$samples=explode(",",$samplelist);
	foreach($samples as $samp){
	
	?>
	
		<tr>
			<td><?=$samp?></td>
			<td>
				<INPUT TYPE="button" value="Upload" onClick="parent.location='zips.php?f=<?=$randnum?>&l=<?=$samplelist?>&n=<?=$filename?>&s=<?=$samp?>'">
			</td>
		</tr>
	
	<?
	
	}




	?>
	</table>
	
		<br><br><br><br><br>
		<br><br><br><br><br>
		<br><br><br><br><br>
		<br><br><br><br><br>
		<br><br><br><br><br>
	<?

	include("includes/geochron-secondary-footer.htm");

	exit();
}







































if($_GET['f']!=""){$f=$_GET['f'];}else{$f=$_POST['f'];}
if($_GET['l']!=""){$l=$_GET['l'];}else{$l=$_POST['l'];}
if($_GET['n']!=""){$n=$_GET['n'];}else{$n=$_POST['n'];}
if($_GET['s']!=""){$s=$_GET['s'];}else{$s=$_POST['s'];}

$orig_filename=$n;

$randnum=$f;

if($_POST['submit']!=""){

	//s = samplenumber
	//l = list
	//we need to remove s from l to make a new list
	$newlist=array();
	$larray=explode(",",$l);
	foreach($larray as $lpart){
		if($lpart != $s){
			$newlist[]=$lpart;
		}
	}
	
	$newlist=implode(",",$newlist);
	
	//var_dump($newlist);
	
	//exit();
	

	$error="";
	$errordelim="";

	$inputtype=$_POST['inputtype'];
	

	if($inputtype=="html"){
		$samplename=$_POST['samplename'];
		$uniqueid=$_POST['uniqueid'];
		$labname=$_POST['labname'];
		$analystname=$_POST['analystname'];
	
		$myinstrumentalmethod=$_POST['instrumentalmethod'];
		if($myinstrumentalmethod=="other"){
			$instrumentalmethod=$_POST['otherinstrumentalmethod'];
		}else{
			$instrumentalmethod=$myinstrumentalmethod;
		}
		
		$instrumentalmethodreference=$_POST['instrumentalmethodreference'];
		
		$mymineral=$_POST['mineral'];
		if($mymineral=="other"){
			$mineral=$_POST['othermineral'];
		}else{
			$mineral=$mymineral;
		}
		
		$comment=$_POST['comment'];
		$decaycomment=$_POST['decaycomment'];
		$udecayconstant238=$_POST['udecayconstant238'];
		$udecayconstanterror238=$_POST['udecayconstanterror238'];
		$udecayconstant235=$_POST['udecayconstant235'];
		$udecayconstanterror235=$_POST['udecayconstanterror235'];
		$thdecayconstant232=$_POST['thdecayconstant232'];
		$thdecayconstanterror232=$_POST['thdecayconstanterror232'];
		$thdecayconstant230=$_POST['thdecayconstant230'];
		$thdecayconstanterror230=$_POST['thdecayconstanterror230'];
		$u235u238=$_POST['u235u238'];
		$decayconstantreference=$_POST['decayconstantreference'];
		
		$preferredage1=$_POST['preferredage1'];
		$preferredageerror1=$_POST['preferredageerror1'];
		$mswd1=$_POST['mswd1'];
		$ageerrorsystematic1=$_POST['ageerrorsystematic1'];
		$preferredageincludedanalyses1=$_POST['preferredageincludedanalyses1'];
		$preferredageexplanation1=$_POST['preferredageexplanation1'];
		
		$preferredage2=$_POST['preferredage2'];
		$preferredageerror2=$_POST['preferredageerror2'];
		$mswd2=$_POST['mswd2'];
		$ageerrorsystematic2=$_POST['ageerrorsystematic2'];
		$preferredageincludedanalyses2=$_POST['preferredageincludedanalyses2'];
		$preferredageexplanation2=$_POST['preferredageexplanation2'];
		
		$preferredage3=$_POST['preferredage3'];
		$preferredageerror3=$_POST['preferredageerror3'];
		$mswd3=$_POST['mswd3'];
		$ageerrorsystematic3=$_POST['ageerrorsystematic3'];
		$preferredageincludedanalyses3=$_POST['preferredageincludedanalyses3'];
		$preferredageexplanation3=$_POST['preferredageexplanation3'];
		
		$preferredage4=$_POST['preferredage4'];
		$preferredageerror4=$_POST['preferredageerror4'];
		$mswd4=$_POST['mswd4'];
		$ageerrorsystematic4=$_POST['ageerrorsystematic4'];
		$preferredageincludedanalyses4=$_POST['preferredageincludedanalyses4'];
		$preferredageexplanation4=$_POST['preferredageexplanation4'];
		
		$preferredage5=$_POST['preferredage5'];
		$preferredageerror5=$_POST['preferredageerror5'];
		$mswd5=$_POST['mswd5'];
		$ageerrorsystematic5=$_POST['ageerrorsystematic5'];
		$preferredageincludedanalyses5=$_POST['preferredageincludedanalyses5'];
		$preferredageexplanation5=$_POST['preferredageexplanation5'];
		
		$preferredage6=$_POST['preferredage6'];
		$preferredageerror6=$_POST['preferredageerror6'];
		$mswd6=$_POST['mswd6'];
		$ageerrorsystematic6=$_POST['ageerrorsystematic6'];
		$preferredageincludedanalyses6=$_POST['preferredageincludedanalyses6'];
		$preferredageexplanation6=$_POST['preferredageexplanation6'];
		
		$preferredage7=$_POST['preferredage7'];
		$preferredageerror7=$_POST['preferredageerror7'];
		$mswd7=$_POST['mswd7'];
		$ageerrorsystematic7=$_POST['ageerrorsystematic7'];
		$preferredageincludedanalyses7=$_POST['preferredageincludedanalyses7'];
		$preferredageexplanation7=$_POST['preferredageexplanation7'];
		
		$preferredage8=$_POST['preferredage8'];
		$preferredageerror8=$_POST['preferredageerror8'];
		$mswd8=$_POST['mswd8'];
		$ageerrorsystematic8=$_POST['ageerrorsystematic8'];
		$preferredageincludedanalyses8=$_POST['preferredageincludedanalyses8'];
		$preferredageexplanation8=$_POST['preferredageexplanation8'];
		
		$preferredage9=$_POST['preferredage9'];
		$preferredageerror9=$_POST['preferredageerror9'];
		$mswd9=$_POST['mswd9'];
		$ageerrorsystematic9=$_POST['ageerrorsystematic9'];
		$preferredageincludedanalyses9=$_POST['preferredageincludedanalyses9'];
		$preferredageexplanation9=$_POST['preferredageexplanation9'];
		
		$preferredage10=$_POST['preferredage10'];
		$preferredageerror10=$_POST['preferredageerror10'];
		$mswd10=$_POST['mswd10'];
		$ageerrorsystematic10=$_POST['ageerrorsystematic10'];
		$preferredageincludedanalyses10=$_POST['preferredageincludedanalyses10'];
		$preferredageexplanation10=$_POST['preferredageexplanation10'];
		
		$commonleadcorrection1=$_POST['commonleadcorrection1'];
		$commonleadcorrection2=$_POST['commonleadcorrection2'];
		$commonleadcorrection3=$_POST['commonleadcorrection3'];
		$commonleadcorrection4=$_POST['commonleadcorrection4'];
		$commonleadcorrection5=$_POST['commonleadcorrection5'];
		$commonleadcorrection6=$_POST['commonleadcorrection6'];
		$commonleadcorrection7=$_POST['commonleadcorrection7'];
		$commonleadcorrection8=$_POST['commonleadcorrection8'];
		$commonleadcorrection9=$_POST['commonleadcorrection9'];
		$commonleadcorrection10=$_POST['commonleadcorrection10'];


	
	
		$myanalysispurpose1=$_POST['analysispurpose1'];
		if($myanalysispurpose1=="other"){
			$analysispurpose1=$_POST['otherap1'];
		}else{
			$analysispurpose1=$myanalysispurpose1;
		}
		
		$mypreferredagetype1=$_POST['preferredagetype1'];
		if($mypreferredagetype1=="other"){
			$preferredagetype1=$_POST['otherpatype1'];
		}else{
			$preferredagetype1=$mypreferredagetype1;
		}
	
	
		$myanalysispurpose2=$_POST['analysispurpose2'];
		if($myanalysispurpose2=="other"){
			$analysispurpose2=$_POST['otherap2'];
		}else{
			$analysispurpose2=$myanalysispurpose2;
		}
		
		$mypreferredagetype2=$_POST['preferredagetype2'];
		if($mypreferredagetype2=="other"){
			$preferredagetype2=$_POST['otherpatype2'];
		}else{
			$preferredagetype2=$mypreferredagetype2;
		}
	
	
		$myanalysispurpose3=$_POST['analysispurpose3'];
		if($myanalysispurpose3=="other"){
			$analysispurpose3=$_POST['otherap3'];
		}else{
			$analysispurpose3=$myanalysispurpose3;
		}
		
		$mypreferredagetype3=$_POST['preferredagetype3'];
		if($mypreferredagetype3=="other"){
			$preferredagetype3=$_POST['otherpatype3'];
		}else{
			$preferredagetype3=$mypreferredagetype3;
		}
	
	
		$myanalysispurpose4=$_POST['analysispurpose4'];
		if($myanalysispurpose4=="other"){
			$analysispurpose4=$_POST['otherap4'];
		}else{
			$analysispurpose4=$myanalysispurpose4;
		}
		
		$mypreferredagetype4=$_POST['preferredagetype4'];
		if($mypreferredagetype4=="other"){
			$preferredagetype4=$_POST['otherpatype4'];
		}else{
			$preferredagetype4=$mypreferredagetype4;
		}
	
	
		$myanalysispurpose5=$_POST['analysispurpose5'];
		if($myanalysispurpose5=="other"){
			$analysispurpose5=$_POST['otherap5'];
		}else{
			$analysispurpose5=$myanalysispurpose5;
		}
		
		$mypreferredagetype5=$_POST['preferredagetype5'];
		if($mypreferredagetype5=="other"){
			$preferredagetype5=$_POST['otherpatype5'];
		}else{
			$preferredagetype5=$mypreferredagetype5;
		}
	
	
		$myanalysispurpose6=$_POST['analysispurpose6'];
		if($myanalysispurpose6=="other"){
			$analysispurpose6=$_POST['otherap6'];
		}else{
			$analysispurpose6=$myanalysispurpose6;
		}
		
		$mypreferredagetype6=$_POST['preferredagetype6'];
		if($mypreferredagetype6=="other"){
			$preferredagetype6=$_POST['otherpatype6'];
		}else{
			$preferredagetype6=$mypreferredagetype6;
		}
	
	
		$myanalysispurpose7=$_POST['analysispurpose7'];
		if($myanalysispurpose7=="other"){
			$analysispurpose7=$_POST['otherap7'];
		}else{
			$analysispurpose7=$myanalysispurpose7;
		}
		
		$mypreferredagetype7=$_POST['preferredagetype7'];
		if($mypreferredagetype7=="other"){
			$preferredagetype7=$_POST['otherpatype7'];
		}else{
			$preferredagetype7=$mypreferredagetype7;
		}
	
	
		$myanalysispurpose8=$_POST['analysispurpose8'];
		if($myanalysispurpose8=="other"){
			$analysispurpose8=$_POST['otherap8'];
		}else{
			$analysispurpose8=$myanalysispurpose8;
		}
		
		$mypreferredagetype8=$_POST['preferredagetype8'];
		if($mypreferredagetype8=="other"){
			$preferredagetype8=$_POST['otherpatype8'];
		}else{
			$preferredagetype8=$mypreferredagetype8;
		}
	
	
		$myanalysispurpose9=$_POST['analysispurpose9'];
		if($myanalysispurpose9=="other"){
			$analysispurpose9=$_POST['otherap9'];
		}else{
			$analysispurpose9=$myanalysispurpose9;
		}
		
		$mypreferredagetype9=$_POST['preferredagetype9'];
		if($mypreferredagetype9=="other"){
			$preferredagetype9=$_POST['otherpatype9'];
		}else{
			$preferredagetype9=$mypreferredagetype9;
		}
	
	
		$myanalysispurpose10=$_POST['analysispurpose10'];
		if($myanalysispurpose10=="other"){
			$analysispurpose10=$_POST['otherap10'];
		}else{
			$analysispurpose10=$myanalysispurpose10;
		}
		
		$mypreferredagetype10=$_POST['preferredagetype10'];
		if($mypreferredagetype10=="other"){
			$preferredagetype10=$_POST['otherpatype10'];
		}else{
			$preferredagetype10=$mypreferredagetype10;
		}

	}else{//get info from spreadsheet
	
		//print_r($_POST);exit();
		//print_r($_FILES);exit();
		
		$randnum=$_POST['f'];
	
		$metadatafilename=str_replace(" ","_",$_FILES['separatefile']['name']);
		
		$newmetadatafilename="squidtemp"."/".$metadatafilename;
		
		$metadatatempname=$_FILES['separatefile']['tmp_name'];
		
		move_uploaded_file ( $metadatatempname , "$newmetadatafilename" );
		
		require_once 'Excel/reader.php';
		
		$metadatadata = new Spreadsheet_Excel_Reader();
		
		$metadatadata->setOutputEncoding('CP1251');
		
		$metadatadata->read($newmetadatafilename);
		
		$metadatasheets=$metadatadata->sheets;
		
		$metadataxd=$metadatasheets[0]['cells'];
		
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
		
			//if($metadataxd[$y][1]=="Sample Name"){$samplename=$metadataxd[$y][2];}
			if($metadataxd[$y][1]=="IGSN"){$uniqueid=$metadataxd[$y][2];}
			if($metadataxd[$y][1]=="Laboratory"){$labname=$metadataxd[$y][2];}
			if($metadataxd[$y][1]=="Analyst"){$analystname=$metadataxd[$y][2];}
			if($metadataxd[$y][1]=="Instrumental Method"){$instrumentalmethod=$metadataxd[$y][2];}
			if($metadataxd[$y][1]=="Instrumental Method Reference"){$instrumentalmethodreference=$metadataxd[$y][2];}
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
			if($metadataxd[$y][1]=="235U/238U"){$u235u238=$metadataxd[$y][2];}
			if($metadataxd[$y][1]=="Decay Constant Reference"){$decayconstantreference=$metadataxd[$y][2];}
			if($metadataxd[$y][1]=="Decay Comment"){$decaycomment=$metadataxd[$y][2];}
		
			if($metadataxd[$y][1]=="Analysis Purpose" || $metadataxd[$y][1]=="Additional Analysis Purpose"){$agenum++;eval("\$analysispurpose$agenum=\$metadataxd[\$y][2];");}
			if($metadataxd[$y][1]=="Preferred Age Type" || $metadataxd[$y][1]=="Additional Age Type"){eval("\$preferredagetype$agenum=\$metadataxd[\$y][2];");}
			if($metadataxd[$y][1]=="Preferred Age" || $metadataxd[$y][1]=="Age"){eval("\$preferredage$agenum=\$metadataxd[\$y][2];");}
			if($metadataxd[$y][1]=="Preferred Age Error" || $metadataxd[$y][1]=="Age Error (Analytical)"){eval("\$preferredageerror$agenum=\$metadataxd[\$y][2];");}
			if($metadataxd[$y][1]=="MSWD" ){eval("\$mswd$agenum=\$metadataxd[\$y][2];");}
			if($metadataxd[$y][1]=="Age Error (Systematic)" ){eval("\$ageerrorsystematic$agenum=\$metadataxd[\$y][2];");}
			if($metadataxd[$y][1]=="Preferred Age Included Analyses" || $metadataxd[$y][1]=="Included Analyses"){eval("\$preferredageincludedanalyses$agenum=\$metadataxd[\$y][2];");}
			if($metadataxd[$y][1]=="Preferred Age Explanation" || $metadataxd[$y][1]=="Age Comment"){eval("\$preferredageexplanation$agenum=\$metadataxd[\$y][2];");}
			if($metadataxd[$y][1]=="Common Lead Correction" ){eval("\$commonleadcorrection$agenum=\$metadataxd[\$y][2];");}




/*



















$1 
$1 
$1 
$1 
$1 
$1 
$1 
$1
*/

		}//end foreach y
	
	}


	$overwrite=$_POST['overwrite'];
	$public=$_POST['public'];
	$submit=$_POST['submit'];
	$filename=$_POST['filename'];
	$sample_pkey=$_POST['sample_pkey'];

	if($public==""){
		$public=1;
	}
	
	$sampleid=$samplename;

	if($samplename==""){$error.=$errordelim."Sample Name cannot be blank.";$errordelim="<br>";}
	if($uniqueid==""){$error.=$errordelim."IGSN cannot be blank.";$errordelim="<br>";}
	if($labname==""){$error.=$errordelim."Lab Name cannot be blank.";$errordelim="<br>";}
	if($analystname==""){$error.=$errordelim."Analyst Name cannot be blank.";$errordelim="<br>";}
	if($instrumentalmethod==""){$error.=$errordelim."Instrumental method cannot be blank.";$errordelim="<br>";}
	if($mineral==""){$error.=$errordelim."Mineral cannot be blank.";$errordelim="<br>";}

	if($udecayconstant238==""){$error.=$errordelim."238U Decay Constant cannot be blank.";$errordelim="<br>";}
	if($udecayconstant235==""){$error.=$errordelim."235U Decay Constant cannot be blank.";$errordelim="<br>";}
	if($thdecayconstant232==""){$error.=$errordelim."232Th Decay Constant  cannot be blank.";$errordelim="<br>";}
	if($u235u238==""){$error.=$errordelim."235U/238U cannot be blank.";$errordelim="<br>";}
	if($decayconstantreference==""){$error.=$errordelim."Decay Constant Reference cannot be blank.";$errordelim="<br>";}

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
				$error.="Sample with this Unique Identifier: $modigsn and Sample ID: $sampleid already exists in database.<br>";
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

	/*
	echo "$samplename<br>";
	echo "$uniqueid<br>";
	echo "$analystname<br>";
	echo "$labname<br>";
	echo "$mineral<br>";

	var_dump($_FILES);

	*/







	if($error==""){
		
		//let's put it in the database
		
		$geochron_pkey=$db->get_var("select nextval('geochron_seq')");
		

		
		//$sample_pkey=$db->get_var("select nextval('sample_seq')");
		
		$uploaddate=date("m/d/Y h:i:s a");

		$savefilename="$geochron_pkey.xml";
		
		$isupstream="FALSE";

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
					'zips',";
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
		
		$db->query($querystring);
		

		

		
		
		
		include("includes/geochron-secondary-header.htm");
		
		echo "<h1>Success!</h1>";
		
		if($newlist==""){
			$gotourl="managedata.php";
			$buttonlabel="Finish";
			$donemessage="<font color=\"green\"><b>All Samples from this file have been uploaded.</b></font><br><br>";
		}else{
			$gotourl="zips.php?f=$f&ll=$newlist&n=$n";
			$buttonlabel="Continue Uploading Samples";
		}
		
		?>
		
		Your sample was uploaded successfully. Below is the data as it was uploaded.<br><br>
		
		<?=$donemessage?>
		
		<INPUT TYPE="button" value="<?=$buttonlabel?>" onClick="parent.location='<?=$gotourl?>'"><br><br>
		
		<?
		//start whole xml file
		$wholexml="<sample>\n\t<sampleinfo>\n";

		if($inputtype!=""){$wholexml.="\t\t<inputtype>$inputtype</inputtype>\n";}
		if($samplename!=""){$wholexml.="\t\t<samplename>$samplename</samplename>\n";}
		if($uniqueid!=""){$wholexml.="\t\t<uniqueid>$uniqueid</uniqueid>\n";}
		if($labname!=""){$wholexml.="\t\t<labname>$labname</labname>\n";}
		if($analystname!=""){$wholexml.="\t\t<analystname>$analystname</analystname>\n";}
		if($instrumentalmethod!=""){$wholexml.="\t\t<instrumentalmethod>$instrumentalmethod</instrumentalmethod>\n";}
		if($otherinstrumentalmethod!=""){$wholexml.="\t\t<otherinstrumentalmethod>$otherinstrumentalmethod</otherinstrumentalmethod>\n";}
		if($instrumentalmethodreference!=""){$wholexml.="\t\t<instrumentalmethodreference>$instrumentalmethodreference</instrumentalmethodreference>\n";}
		if($mineral!=""){$wholexml.="\t\t<mineral>$mineral</mineral>\n";}
		if($othermineral!=""){$wholexml.="\t\t<othermineral>$othermineral</othermineral>\n";}
		if($comment!=""){$wholexml.="\t\t<comment>$comment</comment>\n";}
		if($udecayconstant238!=""){$wholexml.="\t\t<udecayconstant238>$udecayconstant238</udecayconstant238>\n";}
		if($udecayconstanterror238!=""){$wholexml.="\t\t<udecayconstanterror238>$udecayconstanterror238</udecayconstanterror238>\n";}
		if($udecayconstant235!=""){$wholexml.="\t\t<udecayconstant235>$udecayconstant235</udecayconstant235>\n";}
		if($udecayconstanterror235!=""){$wholexml.="\t\t<udecayconstanterror235>$udecayconstanterror235</udecayconstanterror235>\n";}
		if($thdecayconstant232!=""){$wholexml.="\t\t<thdecayconstant232>$thdecayconstant232</thdecayconstant232>\n";}
		if($thdecayconstanterror232!=""){$wholexml.="\t\t<thdecayconstanterror232>$thdecayconstanterror232</thdecayconstanterror232>\n";}
		if($thdecayconstant230!=""){$wholexml.="\t\t<thdecayconstant230>$thdecayconstant230</thdecayconstant230>\n";}
		if($thdecayconstanterror230!=""){$wholexml.="\t\t<thdecayconstanterror230>$thdecayconstanterror230</thdecayconstanterror230>\n";}
		if($u235u238!=""){$wholexml.="\t\t<u235u238>$u235u238</u235u238>\n";}
		if($decayconstantreference!=""){$wholexml.="\t\t<decayconstantreference>$decayconstantreference</decayconstantreference>\n";}
		if($decaycomment!=""){$wholexml.="\t\t<decaycomment>$decaycomment</decaycomment>\n";}

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

		if($analysispurpose1!=""){$wholexml.="\t\t<age analysispurpose=\"$analysispurpose1\" value=\"$preferredage1\" error=\"$preferredageerror1\" type=\"$preferredagetype1\" mswd=\"$mswd1\" ageerrorsystematic=\"$ageerrorsystematic1\" preferredageincludedanalyses=\"$preferredageincludedanalyses1\" preferredageexplanation=\"$preferredageexplanation1\" commonleadcorrection=\"$commonleadcorrection1\" />\n";}
		if($analysispurpose2!=""){$wholexml.="\t\t<age analysispurpose=\"$analysispurpose2\" value=\"$preferredage2\" error=\"$preferredageerror2\" type=\"$preferredagetype2\" mswd=\"$mswd2\" ageerrorsystematic=\"$ageerrorsystematic2\" preferredageincludedanalyses=\"$preferredageincludedanalyses2\" preferredageexplanation=\"$preferredageexplanation2\" commonleadcorrection=\"$commonleadcorrection2\" />\n";}
		if($analysispurpose3!=""){$wholexml.="\t\t<age analysispurpose=\"$analysispurpose3\" value=\"$preferredage3\" error=\"$preferredageerror3\" type=\"$preferredagetype3\" mswd=\"$mswd3\" ageerrorsystematic=\"$ageerrorsystematic3\" preferredageincludedanalyses=\"$preferredageincludedanalyses3\" preferredageexplanation=\"$preferredageexplanation3\" commonleadcorrection=\"$commonleadcorrection3\" />\n";}
		if($analysispurpose4!=""){$wholexml.="\t\t<age analysispurpose=\"$analysispurpose4\" value=\"$preferredage4\" error=\"$preferredageerror4\" type=\"$preferredagetype4\" mswd=\"$mswd4\" ageerrorsystematic=\"$ageerrorsystematic4\" preferredageincludedanalyses=\"$preferredageincludedanalyses4\" preferredageexplanation=\"$preferredageexplanation4\" commonleadcorrection=\"$commonleadcorrection4\" />\n";}
		if($analysispurpose5!=""){$wholexml.="\t\t<age analysispurpose=\"$analysispurpose5\" value=\"$preferredage5\" error=\"$preferredageerror5\" type=\"$preferredagetype5\" mswd=\"$mswd5\" ageerrorsystematic=\"$ageerrorsystematic5\" preferredageincludedanalyses=\"$preferredageincludedanalyses5\" preferredageexplanation=\"$preferredageexplanation5\" commonleadcorrection=\"$commonleadcorrection5\" />\n";}
		if($analysispurpose6!=""){$wholexml.="\t\t<age analysispurpose=\"$analysispurpose6\" value=\"$preferredage6\" error=\"$preferredageerror6\" type=\"$preferredagetype6\" mswd=\"$mswd6\" ageerrorsystematic=\"$ageerrorsystematic6\" preferredageincludedanalyses=\"$preferredageincludedanalyses6\" preferredageexplanation=\"$preferredageexplanation6\" commonleadcorrection=\"$commonleadcorrection6\" />\n";}
		if($analysispurpose7!=""){$wholexml.="\t\t<age analysispurpose=\"$analysispurpose7\" value=\"$preferredage7\" error=\"$preferredageerror7\" type=\"$preferredagetype7\" mswd=\"$mswd7\" ageerrorsystematic=\"$ageerrorsystematic7\" preferredageincludedanalyses=\"$preferredageincludedanalyses7\" preferredageexplanation=\"$preferredageexplanation7\" commonleadcorrection=\"$commonleadcorrection7\" />\n";}
		if($analysispurpose8!=""){$wholexml.="\t\t<age analysispurpose=\"$analysispurpose8\" value=\"$preferredage8\" error=\"$preferredageerror8\" type=\"$preferredagetype8\" mswd=\"$mswd8\" ageerrorsystematic=\"$ageerrorsystematic8\" preferredageincludedanalyses=\"$preferredageincludedanalyses8\" preferredageexplanation=\"$preferredageexplanation8\" commonleadcorrection=\"$commonleadcorrection8\" />\n";}
		if($analysispurpose9!=""){$wholexml.="\t\t<age analysispurpose=\"$analysispurpose9\" value=\"$preferredage9\" error=\"$preferredageerror9\" type=\"$preferredagetype9\" mswd=\"$mswd9\" ageerrorsystematic=\"$ageerrorsystematic9\" preferredageincludedanalyses=\"$preferredageincludedanalyses9\" preferredageexplanation=\"$preferredageexplanation9\" commonleadcorrection=\"$commonleadcorrection9\" />\n";}
		if($analysispurpose10!=""){$wholexml.="\t\t<age analysispurpose=\"$analysispurpose10\" value=\"$preferredage10\" error=\"$preferredageerror10\" type=\"$preferredagetype10\" mswd=\"$mswd10\" ageerrorsystematic=\"$ageerrorsystematic10\" preferredageincludedanalyses=\"$preferredageincludedanalyses10\" preferredageexplanation=\"$preferredageexplanation10\" commonleadcorrection=\"$commonleadcorrection10\" />\n";}

		$wholexml.="\t</ages>\n";
		
		


		/*
		echo "<br><h2>Sample Information:</h2>";
		echo "<table class=\"aliquot\">";
		if($samplename!=""){echo "<tr><td>samplename:</td><td>$samplename</td></tr>";}
		if($uniqueid!=""){echo "<tr><td>uniqueid:</td><td>$uniqueid</td></tr>";}
		if($analystname!=""){echo "<tr><td>analystname:</td><td>$analystname</td></tr>";}
		if($labname!=""){echo "<tr><td>labname:</td><td>$labname</td></tr>";}
		if($mineral!=""){echo "<tr><td>mineral:</td><td>$mineral</td></tr>";}
		if($age1!=""){echo "<tr><td>age1:</td><td>$age1</td></tr>";}
		if($ageerror1!=""){echo "<tr><td>ageerror1:</td><td>$ageerror1</td></tr>";}
		if($agetype1!=""){echo "<tr><td>agetype1:</td><td>$agetype1</td></tr>";}
		if($age2!=""){echo "<tr><td>age2:</td><td>$age2</td></tr>";}
		if($ageerror2!=""){echo "<tr><td>ageerror2:</td><td>$ageerror2</td></tr>";}
		if($agetype2!=""){echo "<tr><td>agetype2:</td><td>$agetype2</td></tr>";}
		if($age3!=""){echo "<tr><td>age3:</td><td>$age3</td></tr>";}
		if($ageerror3!=""){echo "<tr><td>ageerror3:</td><td>$ageerror3</td></tr>";}
		if($agetype3!=""){echo "<tr><td>agetype3:</td><td>$agetype3</td></tr>";}
		if($age4!=""){echo "<tr><td>age4:</td><td>$age4</td></tr>";}
		if($ageerror4!=""){echo "<tr><td>ageerror4:</td><td>$ageerror4</td></tr>";}
		if($agetype4!=""){echo "<tr><td>agetype4:</td><td>$agetype4</td></tr>";}
		if($age5!=""){echo "<tr><td>age5:</td><td>$age5</td></tr>";}
		if($ageerror5!=""){echo "<tr><td>ageerror5:</td><td>$ageerror5</td></tr>";}
		if($agetype5!=""){echo "<tr><td>agetype5:</td><td>$agetype5</td></tr>";}
		if($age6!=""){echo "<tr><td>age6:</td><td>$age6</td></tr>";}
		if($ageerror6!=""){echo "<tr><td>ageerror6:</td><td>$ageerror6</td></tr>";}
		if($agetype6!=""){echo "<tr><td>agetype6:</td><td>$agetype6</td></tr>";}
		if($age7!=""){echo "<tr><td>age7:</td><td>$age7</td></tr>";}
		if($ageerror7!=""){echo "<tr><td>ageerror7:</td><td>$ageerror7</td></tr>";}
		if($agetype7!=""){echo "<tr><td>agetype7:</td><td>$agetype7</td></tr>";}
		if($age8!=""){echo "<tr><td>age8:</td><td>$age8</td></tr>";}
		if($ageerror8!=""){echo "<tr><td>ageerror8:</td><td>$ageerror8</td></tr>";}
		if($agetype8!=""){echo "<tr><td>agetype8:</td><td>$agetype8</td></tr>";}
		if($age9!=""){echo "<tr><td>age9:</td><td>$age9</td></tr>";}
		if($ageerror9!=""){echo "<tr><td>ageerror9:</td><td>$ageerror9</td></tr>";}
		if($agetype9!=""){echo "<tr><td>agetype9:</td><td>$agetype9</td></tr>";}
		if($age10!=""){echo "<tr><td>age10:</td><td>$age10</td></tr>";}
		if($ageerror10!=""){echo "<tr><td>ageerror10:</td><td>$ageerror10</td></tr>";}
		if($agetype10!=""){echo "<tr><td>agetype10:</td><td>$agetype10</td></tr>";}
		echo "</table>";
		
		
		echo "<br><br><h2>Unique ID Information:</h2>";
		echo "<table class=\"aliquot\">";
		echo "<tr><td>sampleid:</td><td>$isampleid</td></tr>";
		echo "<tr><td>agemin:</td><td>$iagemin</td></tr>";
		echo "<tr><td>agemax:</td><td>$iagemax</td></tr>";
		echo "<tr><td>sampledescription:</td><td>$isampledescription</td></tr>";
		echo "<tr><td>geoobjecttype:</td><td>$igeoobjecttype</td></tr>";
		echo "<tr><td>geoobjectclassification:</td><td>$igeoobjectclassification</td></tr>";
		echo "<tr><td>collectionmethod:</td><td>$icollectionmethod</td></tr>";
		echo "<tr><td>material:</td><td>$imaterial</td></tr>";
		echo "<tr><td>latitude:</td><td>$ilatitude</td></tr>";
		echo "<tr><td>longitude:</td><td>$ilongitude</td></tr>";
		echo "<tr><td>samplecomment:</td><td>$isamplecomment</td></tr>";
		echo "<tr><td>collector:</td><td>$icollector</td></tr>";
		echo "<tr><td>materialclassification:</td><td>$imaterialclassification</td></tr>";
		echo "<tr><td>PrimaryLocationName:</td><td>$iPrimaryLocationName</td></tr>";
		echo "<tr><td>PrimaryLocationType:</td><td>$iPrimaryLocationType</td></tr>";
		echo "<tr><td>LocationDescription:</td><td>$iLocationDescription</td></tr>";
		echo "<tr><td>Locality:</td><td>$iLocality</td></tr>";
		echo "<tr><td>LocalityDescription:</td><td>$iLocalityDescription</td></tr>";
		echo "<tr><td>Country:</td><td>$iCountry</td></tr>";
		echo "<tr><td>Provice:</td><td>$iProvice</td></tr>";
		echo "<tr><td>County:</td><td>$iCounty</td></tr>";
		echo "<tr><td>CityOrTownship:</td><td>$iCityOrTownship</td></tr>";
		echo "<tr><td>Platform:</td><td>$iPlatform</td></tr>";
		echo "<tr><td>PlatformID:</td><td>$iPlatformID</td></tr>";
		echo "<tr><td>OriginalArchivalInstitution:</td><td>$iOriginalArchivalInstitution</td></tr>";
		echo "<tr><td>OriginalArchivalContact:</td><td>$iOriginalArchivalContact</td></tr>";
		echo "<tr><td>MostRecentArchivalInstitution:</td><td>$iMostRecentArchivalInstitution</td></tr>";
		echo "<tr><td>MostRecentArchivalContact:</td><td>$iMostRecentArchivalContact</td></tr>";
		echo "</table>";
		*/
		



		$randnum=$_POST['f'];

		//now find the prn file
		exec("/usr/bin/find zipstemp/$randnum/ -name '*.prn'",$found);

		//var_dump($found);

		//Now, let's build a new .prn file with pared down table data
		//While we're at it, let's add the right-table stuff to $wholexml
		
		$prnfilename=$found[0];
		
		//echo "prnfilename: $prnfilename<br>";
		
		$prnparts=explode("/",$prnfilename);
		
		$prnpartscount=count($prnparts);
		
		//echo "prnpartscount: $prnpartscount<br>";
		
		$showprnfilename=$prnparts[$prnpartscount-1];
		
		//echo "showprnfilename: $showprnfilename<br>";
		
		//echo "prnfilename: $prnfilename<br>";
		
		$filecontents=file_get_contents("$prnfilename");
		
		

		$lines=explode("\n",$filecontents);
		
		$y=0;
		
		$maxx=0;
		
		
		//put test.prn into a two-dimensional array for easy access
		foreach($lines as $line){
		
			$x=0;
			
			$cells=explode("\t",$line);
		
			foreach($cells as $cell){
				
				$sheet[$y][$x]=$cell;
				
				if($x>$maxx){$maxx=$x;}
				
				$x++;
			
			}
			
			$y++;
			
		}
		
		
		
		//echo "maxx: $maxx<br><br>";
		
		//var_dump($sheet);
		
		$numrows=count($sheet);
		
		//echo "count sheet: ".count($sheet[0]);
		
		//find maxy (for side)
		$limit=0;
		for($y=0;$y<=$numrows;$y++){
		
			if($sheet[$y][8]!=""){
			
				$maxy=$y;
				$limit=0;
			
			}else{
			
				$limit++;
			
			}
			
			if($limit>=10){
				break;
			}
		
		}
		
		//echo "maxy: $maxy<br>";
		
		
		//make a copy of sheet
		$newsheet=$sheet;
		
		//remove side rows from sheet
		for($y=5;$y<=$maxy;$y++){
		
			for($x=8;$x<=$maxx;$x++){
			
				$newsheet[$y][$x]="";
			
			}
		
		}
		
		
		//OK, let's look at the original sheet and find the name header so we know what column
		//to look for sample ids in.
		//note: actual data starts in row 5
		//note: Name header is in row 0
		
		$namecolumn="";
		
		for($x=8;$x<=$maxx;$x++){
		
			if(trim(strtolower($sheet[0][$x]))=="name"){
			
				$namecolumn=$x;
			
			}
		
		}
		
		//echo "name column: $namecolumn<br><br>";
		
		//OK, now let's roll over the name column and see which rows match $samplename
		
		//Also add these rows to $wholexml
		
		$wholexml.="\t<spots>\n";
		
		
		$newsheetrow=5;
		
		for($y=5;$y<=$maxy;$y++){
		
			$val=$sheet[$y][$namecolumn];
			
			$val=explode("\\",$val);
			
			$val=trim($val[1]);
			
			$val=str_replace(".ais","",$val);
			
		
			$pos = strpos($val,"@");
			
			if($pos === false) {
		
			}else{
				$val=explode("@",$val);
				$val=$val[0];
			}
		
			$pos = strpos($val,"-");
			
			if($pos === false) {
		
			}else{
				$val=explode("-",$val);
				$val=$val[0];
			}
		
			$pos = strpos($val,"=");
			
			if($pos === false) {
		
			}else{
				$val=explode("=",$val);
				$val=$val[0];
			}
		
			if(
				(substr(strtolower($val),0,3)=="res") ||
				(substr(strtolower($val),0,3)=="as3") ||
				$val==$s
			){

				//add spot
				$wholexml.="\t\t<spot>\n";

				//echo "$val<br>";
				//add this row to $newsheet
				for($i=8;$i<=$maxx;$i++){
					$newsheet[$newsheetrow][$i]=$sheet[$y][$i];
					
					$thisval=$sheet[$y][$i];
					
					$thisval=str_replace("\r","",$thisval);
					
					//also add each xml val to this <spot>
					$delim="";
					$string="";
					if($sheet[0][$i]!=""){$string.=$delim.$sheet[0][$i];$delim=" ";}
					if($sheet[1][$i]!=""){$string.=$delim.$sheet[1][$i];$delim=" ";}
					if($sheet[2][$i]!=""){$string.=$delim.$sheet[2][$i];$delim=" ";}
					if($sheet[3][$i]!=""){$string.=$delim.$sheet[3][$i];$delim=" ";}
					if($sheet[4][$i]!=""){$string.=$delim.$sheet[4][$i];$delim=" ";}
				
					$string=trim($string);
					
					
					//look for string in $headers and get xml string
					$xmlstring="";
					foreach($headers as $header){
						if($header[0]==$string){
							$xmlstring=$header[1];
						}
					}
					
					if($xmlstring!=""){
						$wholexml.="\t\t\t<$xmlstring>$thisval</$xmlstring>\n";
					}
				}
				$newsheetrow++;

				$wholexml.="\t\t</spot>\n";
				
				
			}
			
			
			
		
		}
		
		$wholexml.="\t</spots>\n";
		
		
		$y=0;
		
		//dump newsheet
		/*
		echo "<table border=\"1\">\n";
		foreach($newsheet as $newrow){
			$x=0;
			echo "<tr>\n";
			foreach($newrow as $cell){
				//echo "<td nowrap>($x x $y )$cell&nbsp;</td>\n";
				echo "<td nowrap>$cell&nbsp;</td>\n";
				$x++;
			}
			echo "</tr>\n";
			$y++;
		}
		echo "</table>\n";
		*/

		$wholexml.="</sample>";

		//echo $wholexml;

		//exit();




		//OK, let's write out new .prn file
		$newprn="";
		foreach($newsheet as $newrow){
			$newprn.=$rowdelim;
			$coldelim="";
			foreach($newrow as $cell){
				$newprn.=$coldelim.$cell;
				$coldelim="\t";
			}
			$rowdelim="\n";
		}

		

		//OK, we have the new prn file in $newprn, let's make a new directory ($fgeochron) to put
		//the new files into.
		
		mkdir("zipstemp/$f/$geochron_pkey");

		//write new prn file
		$myfile = "zipstemp/$f/$geochron_pkey"."/data.prn";
		$fh = fopen($myfile, 'w') or die("can't open new prn file");
		fwrite($fh, $newprn);
		fclose($fh);

		
		
		//create zip archive
		$z = new ZipArchive();
		$z->open("files/$geochron_pkey.zip", ZIPARCHIVE::CREATE);
		
		//used to be pkey_data
		$z->addEmptyDir("$geochron_pkey"."_data");
		
		//let's make it samplename_data
		
		
		$z->addFile("zipstemp/$f/$geochron_pkey"."/data.prn", "$geochron_pkey"."_data/data.prn");

		exec("/usr/bin/find zipstemp/$f/ -name '*.ais'",$listfound);
		
		foreach($listfound as $line){
			//echo "$line<br>";
			$pos=strrpos($line,"/");
			//echo "$pos<br>";
			$filename=substr($line,$pos+1);
			//echo "$filename<br>";
			
			$val=trim($filename);
			
			$val=str_replace(".ais","",$val);
		
			$pos = strpos($val,"@");
			
			if($pos === false) {
		
			}else{
				$val=explode("@",$val);
				$val=$val[0];
			}
		
			$pos = strpos($val,"-");
			
			if($pos === false) {
		
			}else{
				$val=explode("-",$val);
				$val=$val[0];
			}
		
			$pos = strpos($val,"=");
			
			if($pos === false) {
		
			}else{
				$val=explode("=",$val);
				$val=$val[0];
			}
		
			if(
				(substr(strtolower($val),0,3)=="res") ||
				(substr(strtolower($val),0,3)=="as3") ||
				$val==$s
			){

				//echo "$val<br>";
				//echo "$filename<br>";
				//echo "$line<br>";
				//copy("$line","zipstemp/$f/$geochron_pkey"."geochron/$filename");
				
				//add file to zip
				$z->addFile("$line", "$geochron_pkey"."_data/$filename");
				
				//$zipFile->addEmptyDir($f);
			}

		}//end foreach listfound

		exec("/usr/bin/find zipstemp/$f/ -name '*.asc'",$ascfound);
		
		foreach($ascfound as $line){
			//echo "$line<br>";
			$pos=strrpos($line,"/");
			//echo "$pos<br>";
			$filename=substr($line,$pos+1);
			//echo "$filename<br>";
			
			$val=trim($filename);
			
			$val=str_replace(".ais","",$val);
		
			$pos = strpos($val,"@");
			
			if($pos === false) {
		
			}else{
				$val=explode("@",$val);
				$val=$val[0];
			}
		
			$pos = strpos($val,"-");
			
			if($pos === false) {
		
			}else{
				$val=explode("-",$val);
				$val=$val[0];
			}
		
			$pos = strpos($val,"=");
			
			if($pos === false) {
		
			}else{
				$val=explode("=",$val);
				$val=$val[0];
			}
		
			if(
				(substr(strtolower($val),0,3)=="res") ||
				(substr(strtolower($val),0,3)=="as3") ||
				$val==$s
			){

				//echo "$val<br>";
				//echo "$filename<br>";
				//echo "$line<br>";
				//copy("$line","zipstemp/$f/$geochron_pkey"."geochron/$filename");
				
				//add file to zip
				$z->addFile("$line", "$geochron_pkey"."_data/$filename");
				
				//$zipFile->addEmptyDir($f);
			}

		}//end foreach ascfound



		//close zip file
		$z->close();

		exec("rm -rf zipstemp/$f/$geochron_pkey");

		if($donemessage!=""){
			exec("rm -rf zipstemp/$f");
		}


















































































		

		//write XML file contents
		$myfile = "files/$savefilename";
		$fh = fopen($myfile, 'w') or die("can't open file");
		//$stringdata = $_POST['content'];
		
		//$stringdata = preg_replace("/[\n\r]/","",$_POST['content']); 
		
		
		
		fwrite($fh, $wholexml);
		fclose($fh);
		
		//$xsltfile="zips.xslt";
		
		$xsltfile="http://ieda.geochron.org/zips_$geochron_pkey.xslt";

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






		//$newfilename

		
		
		
		
		//OK, we need to create the new zip archive here...
		
		
		
		
		
		//copy($newfilename, "files/$geochron_pkey.zip");
		
		//exec("rm -rf $mydir");



		exit();
		

	
	}


/*
samplename
uniqueid
analystname
labname
mineral
age1
ageerror1
agetype1
age2
ageerror2
agetype2
age3
ageerror3
agetype3
age4
ageerror4
agetype4
age5
ageerror5
agetype5
age6
ageerror6
agetype6
age7
ageerror7
agetype7
age8
ageerror8
agetype8
age9
ageerror9
agetype9
age10
ageerror10
agetype10
*/


	


	//exit();

	//test error here
	//$error="Error here.";

}

if($samplename!=""){

	//let's get a sample_pkey here, so we can use it for the image upload stuff
	$sample_pkey=$_POST['sample_pkey'];
	if($sample_pkey==""){
		$sample_pkey=$db->get_var("select nextval('sample_seq')");
	}

	//samplename is chosen, so let's gather the other information here

	$inputtype=$_POST['inputtype'];
	
	if($inputtype=="html"){
		$htmlshow="block";
		$htmlbutton=" checked";
	}else{
		$htmlshow="none";
	}

	if($inputtype=="separatefile"){
		$separatefileshow="block";
		$separatefilebutton=" checked";
	}else{
		$separatefileshow="none";
	}


	//default decay constant stuff here

	if($udecayconstant238==""){$udecayconstant238="1.55125E-10";}
	if($udecayconstant235==""){$udecayconstant235="9.84850E-10";}
	if($thdecayconstant232==""){$thdecayconstant232="4.94750E-11";}
	if($u235u238==""){$u235u238="0.0072526835";}
	if($decayconstantreference==""){$decayconstantreference="Steiger, R.H. and Jager, E., 1977, Subcommission on geochronology: Convention on the use of decay constants in geo- and cosmochronology. Earth and Planetary Science Letters, v. 36, no. 3, p. 359-362.";}


	if($error!=""){
	
		$error="<h2><font color=\"red\">Error!</font></h2><font color=\"red\">$error<br>Please try again.</font><br>";
	
	}
	
	
	include("includes/geochron-secondary-header.htm");


	
	?>
	
	<script type="text/javascript">
	function addage(){
		for (i=1;i<11;i++){
			var thsObj =  document.getElementById("agerow"+i);
			if(thsObj.style.display == 'none') {
				thsObj.style.display = 'table-row';
				break;
			}
		}
	}
	
	function formvalidate(){
		//return true;
		//alert('hey');
		var errors='';
		
		var myinputtype='';
		for (var thiscount=0; thiscount < 2; thiscount++){
			if (document.uploadform.inputtype[thiscount].checked){
				myinputtype=document.uploadform.inputtype[thiscount].value;
			}
		}
		
		if(myinputtype=="" || myinputtype==null){errors=errors+'Please choose a method for data input.\n';}
		
		if(myinputtype=="html"){
		
			if(document.forms["uploadform"]["samplename"].value=="" || document.forms["uploadform"]["samplename"].value==null){errors=errors+'Sample Name must be provided.\n';}
			if(document.forms["uploadform"]["uniqueid"].value=="" || document.forms["uploadform"]["uniqueid"].value==null){errors=errors+'IGSN must be provided.\n';}
			if(document.forms["uploadform"]["labname"].value=="" || document.forms["uploadform"]["labname"].value==null){errors=errors+'Lab Name must be provided.\n';}
			if(document.forms["uploadform"]["analystname"].value=="" || document.forms["uploadform"]["analystname"].value==null){errors=errors+'Analyst Name must be provided.\n';}
	
			if(document.forms["uploadform"]["instrumentalmethod"].value=="" || document.forms["uploadform"]["instrumentalmethod"].value==null){
				errors=errors+'Instrumental Method must be provided.\n';
			}

			if(document.forms["uploadform"]["instrumentalmethod"].value=="other" ){
				if(document.forms["uploadform"]["otherinstrumentalmethod"].value=="" || document.forms["uploadform"]["otherinstrumentalmethod"].value==null){
					errors=errors+'Instrumental Method must be provided.\n';
				}
			}

			if(document.forms["uploadform"]["mineral"].value=="" || document.forms["uploadform"]["mineral"].value==null){errors=errors+'Mineral must be provided.\n';}

			if(document.forms["uploadform"]["mineral"].value=="other" ){
				if(document.forms["uploadform"]["othermineral"].value=="" || document.forms["uploadform"]["othermineral"].value==null){
					errors=errors+'Mineral must be provided.\n';
				}
			}
			
			if(document.forms["uploadform"]["udecayconstant238"].value=="" || document.forms["uploadform"]["udecayconstant238"].value==null){errors=errors+'238U Decay Constant must be provided.\n';}
			if(document.forms["uploadform"]["udecayconstant235"].value=="" || document.forms["uploadform"]["udecayconstant235"].value==null){errors=errors+'235U Decay Constant must be provided.\n';}
			if(document.forms["uploadform"]["thdecayconstant232"].value=="" || document.forms["uploadform"]["thdecayconstant232"].value==null){errors=errors+'232Th Decay Constant  must be provided.\n';}
			if(document.forms["uploadform"]["u235u238"].value=="" || document.forms["uploadform"]["u235u238"].value==null){errors=errors+'235U/238U must be provided.\n';}
			if(document.forms["uploadform"]["decayconstantreference"].value=="" || document.forms["uploadform"]["decayconstantreference"].value==null){errors=errors+'Decay Constant Reference must be provided.\n';}

			if(document.forms["uploadform"]["analysispurpose1"].value=="" || document.forms["uploadform"]["analysispurpose1"].value==null){errors=errors+'Analysis Purpose must be provided.\n';}

			if(document.forms["uploadform"]["analysispurpose1"].value=="other" ){
				if(document.forms["uploadform"]["otherap1"].value=="" || document.forms["uploadform"]["otherap1"].value==null){
					errors=errors+'Analysis Purpose must be provided.\n';
				}
			}

		}



		if(errors!="" && errors!=null){
			alert(errors);
			return false;
		}


		
	}
	
	function showdivs(){

		var myinputtype='';
		for (var thiscount=0; thiscount < 2; thiscount++){
			if (document.uploadform.inputtype[thiscount].checked){
				myinputtype=document.uploadform.inputtype[thiscount].value;
			}
		}
		
		//alert('myinputtype: '+myinputtype);
		
		if(myinputtype=='html'){
			document.getElementById("separatefileinput").style.display="none";
			document.getElementById("htmlinput").style.display="block";
		}
		
		if(myinputtype=='separatefile'){
			document.getElementById("separatefileinput").style.display="block";
			document.getElementById("htmlinput").style.display="none";
		}
	
	}
	
	function instmethoddiv(){
		myinstrumentalmethod=document.getElementById("instrumentalmethod").value;
		
		if(myinstrumentalmethod=="other"){
			document.getElementById("otherinstrumentalmethoddiv").style.display="inline";
		}else{
			document.getElementById("otherinstrumentalmethoddiv").style.display="none";
			document.getElementById("otherinstrumentalmethod").value="";
		}
	}

	function mineraldiv(){
		mymineral=document.getElementById("mineral").value;
		
		if(mymineral=="other"){
			document.getElementById("othermineraldiv").style.display="inline";
		}else{
			document.getElementById("othermineraldiv").style.display="none";
			document.getElementById("othermineral").value="";
		}
	}





	function avdiv1(){
		myval=document.getElementById("analysispurpose1").value;
		
		if(myval=="other"){
			document.getElementById("otherapdiv1").style.display="inline";
		}else{
			document.getElementById("otherapdiv1").style.display="none";
			document.getElementById("otherap1").value="";
		}
	}

	function patypediv1(){
		myval=document.getElementById("preferredagetype1").value;
		
		if(myval=="other"){
			document.getElementById("otherpatypediv1").style.display="inline";
		}else{
			document.getElementById("otherpatypediv1").style.display="none";
			document.getElementById("otherpatype1").value="";
		}
	}


	function avdiv2(){
		myval=document.getElementById("analysispurpose2").value;
		
		if(myval=="other"){
			document.getElementById("otherapdiv2").style.display="inline";
		}else{
			document.getElementById("otherapdiv2").style.display="none";
			document.getElementById("otherap2").value="";
		}
	}

	function patypediv2(){
		myval=document.getElementById("preferredagetype2").value;
		
		if(myval=="other"){
			document.getElementById("otherpatypediv2").style.display="inline";
		}else{
			document.getElementById("otherpatypediv2").style.display="none";
			document.getElementById("otherpatype2").value="";
		}
	}


	function avdiv3(){
		myval=document.getElementById("analysispurpose3").value;
		
		if(myval=="other"){
			document.getElementById("otherapdiv3").style.display="inline";
		}else{
			document.getElementById("otherapdiv3").style.display="none";
			document.getElementById("otherap3").value="";
		}
	}

	function patypediv3(){
		myval=document.getElementById("preferredagetype3").value;
		
		if(myval=="other"){
			document.getElementById("otherpatypediv3").style.display="inline";
		}else{
			document.getElementById("otherpatypediv3").style.display="none";
			document.getElementById("otherpatype3").value="";
		}
	}


	function avdiv4(){
		myval=document.getElementById("analysispurpose4").value;
		
		if(myval=="other"){
			document.getElementById("otherapdiv4").style.display="inline";
		}else{
			document.getElementById("otherapdiv4").style.display="none";
			document.getElementById("otherap4").value="";
		}
	}

	function patypediv4(){
		myval=document.getElementById("preferredagetype4").value;
		
		if(myval=="other"){
			document.getElementById("otherpatypediv4").style.display="inline";
		}else{
			document.getElementById("otherpatypediv4").style.display="none";
			document.getElementById("otherpatype4").value="";
		}
	}


	function avdiv5(){
		myval=document.getElementById("analysispurpose5").value;
		
		if(myval=="other"){
			document.getElementById("otherapdiv5").style.display="inline";
		}else{
			document.getElementById("otherapdiv5").style.display="none";
			document.getElementById("otherap5").value="";
		}
	}

	function patypediv5(){
		myval=document.getElementById("preferredagetype5").value;
		
		if(myval=="other"){
			document.getElementById("otherpatypediv5").style.display="inline";
		}else{
			document.getElementById("otherpatypediv5").style.display="none";
			document.getElementById("otherpatype5").value="";
		}
	}


	function avdiv6(){
		myval=document.getElementById("analysispurpose6").value;
		
		if(myval=="other"){
			document.getElementById("otherapdiv6").style.display="inline";
		}else{
			document.getElementById("otherapdiv6").style.display="none";
			document.getElementById("otherap6").value="";
		}
	}

	function patypediv6(){
		myval=document.getElementById("preferredagetype6").value;
		
		if(myval=="other"){
			document.getElementById("otherpatypediv6").style.display="inline";
		}else{
			document.getElementById("otherpatypediv6").style.display="none";
			document.getElementById("otherpatype6").value="";
		}
	}


	function avdiv7(){
		myval=document.getElementById("analysispurpose7").value;
		
		if(myval=="other"){
			document.getElementById("otherapdiv7").style.display="inline";
		}else{
			document.getElementById("otherapdiv7").style.display="none";
			document.getElementById("otherap7").value="";
		}
	}

	function patypediv7(){
		myval=document.getElementById("preferredagetype7").value;
		
		if(myval=="other"){
			document.getElementById("otherpatypediv7").style.display="inline";
		}else{
			document.getElementById("otherpatypediv7").style.display="none";
			document.getElementById("otherpatype7").value="";
		}
	}


	function avdiv8(){
		myval=document.getElementById("analysispurpose8").value;
		
		if(myval=="other"){
			document.getElementById("otherapdiv8").style.display="inline";
		}else{
			document.getElementById("otherapdiv8").style.display="none";
			document.getElementById("otherap8").value="";
		}
	}

	function patypediv8(){
		myval=document.getElementById("preferredagetype8").value;
		
		if(myval=="other"){
			document.getElementById("otherpatypediv8").style.display="inline";
		}else{
			document.getElementById("otherpatypediv8").style.display="none";
			document.getElementById("otherpatype8").value="";
		}
	}


	function avdiv9(){
		myval=document.getElementById("analysispurpose9").value;
		
		if(myval=="other"){
			document.getElementById("otherapdiv9").style.display="inline";
		}else{
			document.getElementById("otherapdiv9").style.display="none";
			document.getElementById("otherap9").value="";
		}
	}

	function patypediv9(){
		myval=document.getElementById("preferredagetype9").value;
		
		if(myval=="other"){
			document.getElementById("otherpatypediv9").style.display="inline";
		}else{
			document.getElementById("otherpatypediv9").style.display="none";
			document.getElementById("otherpatype9").value="";
		}
	}


	function avdiv10(){
		myval=document.getElementById("analysispurpose10").value;
		
		if(myval=="other"){
			document.getElementById("otherapdiv10").style.display="inline";
		}else{
			document.getElementById("otherapdiv10").style.display="none";
			document.getElementById("otherap10").value="";
		}
	}

	function patypediv10(){
		myval=document.getElementById("preferredagetype10").value;
		
		if(myval=="other"){
			document.getElementById("otherpatypediv10").style.display="inline";
		}else{
			document.getElementById("otherpatypediv10").style.display="none";
			document.getElementById("otherpatype10").value="";
		}
	}




	</script>
	
	<h1>Upload ZIPS Ion Microprobe Data</h1><br>
	
	Please provide the following information for your sample:<br><br>
	
	<div style="padding-left:20px;padding-top:0px;">
	
<table style="font-size:10px;" >

	<tr>
	
		<td valign="top">
		
		<div style="width:600px;"></div>
	
	<form name="uploadform" method="POST" onsubmit="return formvalidate();" enctype="multipart/form-data">
	










<h1>Sample Metadata and Age Data</h1>

<br>
Please select your method of data input:<br>


<br>

<input type="radio" name="inputtype" value="html" onclick="showdivs();" <?=$htmlbutton?>> HTML Input
<input type="radio" name="inputtype" value="separatefile" onclick="showdivs();" <?=$separatefilebutton?>> Separate File

<br>

<div style="padding-left:15px;display:<?=$htmlshow?>;" id="htmlinput">

<!--- ****************************** html metadata and age ********************************************** --->

		<br>

		<?
		if($inputtype=="html"){
			echo $error;
		}
		?>

		<h1>General Information About Sample</h1>
		<table style="font-size:10px;">
			<tr><td colspan="2"><div style="color:red;" >* = Required</div></td></tr>
			<tr><td>Sample Name:<span style="color:red;">*</span></td><td><input type="text" name="samplename" value="<?=$samplename?>" readonly></td></tr>
			<tr><td>IGSN:<span style="color:red;">*</span></td><td><input type="text" name="uniqueid" value="<?=$uniqueid?>" ></td></tr>
			<tr><td>Laboratory:<span style="color:red;">*</span></td><td><input type="text" name="labname" value="<?=$labname?>" ></td></tr>
			<tr><td>Analyst:<span style="color:red;">*</span></td><td><input type="text" name="analystname" value="<?=$analystname?>" ></td></tr>

			<tr>
				<td >Instrumental Method:<span style="color:red;">*</span></td>
				<td nowrap>
					<select name="instrumentalmethod" id="instrumentalmethod" onchange="instmethoddiv();">
					<option value="">select...</option>
						<!---<option value="ID-TIMS" <? if($instrumentalmethod=="ID-TIMS"){echo "selected";} ?>>ID-TIMS</option>
						<option value="SHRIMP Ion Probe" <? if($instrumentalmethod=="SHRIMP Ion Probe"){echo "selected";} ?>>SHRIMP Ion Probe</option>
						--->
						<option value="Cameca Ion Probe" <? if($instrumentalmethod=="Cameca Ion Probe"){echo "selected";} ?>>Cameca Ion Probe</option>
						<!---
						<option value="Quad-ICPMS" <? if($instrumentalmethod=="Quad-ICPMS"){echo "selected";} ?>>Quad-ICPMS</option>
						<option value="HR-ICPMS" <? if($instrumentalmethod=="HR-ICPMS"){echo "selected";} ?>>HR-ICPMS</option>
						<option value="MC-ICPMS" <? if($instrumentalmethod=="MC-ICPMS"){echo "selected";} ?>>MC-ICPMS</option>
						--->
						<option value="other" <? if($instrumentalmethod=="other"){echo "selected";} ?>>other</option>
					</select>
					<span id="otherinstrumentalmethoddiv" style="color:red;display:none;">
					&nbsp;&nbsp;&nbsp;Please Provide:&nbsp;<input type="text" id="otherinstrumentalmethod" name="otherinstrumentalmethod" value="<?=$otherinstrumentalmethod?>" >
					</span>
					
				</td>
			</tr>
			
			<tr><td>Instrumental Method Reference:</td><td><input type="text" name="instrumentalmethodreference" value="<?=$instrumentalmethodreference?>" ></td></tr>
			
			<tr>
				<td>Mineral:<span style="color:red;">*</span></td>
				<td>
					<select name="mineral" id="mineral" onchange="mineraldiv();">
					<option value="">select...</option>
						<option value="zircon" <? if($mineral=="zircon"){echo "selected";} ?>>zircon</option>
						<option value="xenotime" <? if($mineral=="xenotime"){echo "selected";} ?>>xenotime</option>
						<option value="monazite" <? if($mineral=="monazite"){echo "selected";} ?>>monazite</option>
						<option value="apatite" <? if($mineral=="apatite"){echo "selected";} ?>>apatite</option>
						<option value="titanite" <? if($mineral=="titanite"){echo "selected";} ?>>titanite</option>
						<option value="rutile" <? if($mineral=="rutile"){echo "selected";} ?>>rutile</option>
						<option value="calcite" <? if($mineral=="calcite"){echo "selected";} ?>>calcite</option>
						<option value="whole rock" <? if($mineral=="whole rock"){echo "selected";} ?>>whole rock</option>
						<option value="other" <? if($mineral=="other"){echo "selected";} ?>>other</option>
					</select>
					
					<span id="othermineraldiv" style="color:red;display:none;">
					&nbsp;&nbsp;&nbsp;Please Provide:&nbsp;<input type="text" id="othermineral" name="othermineral" value="<?=$othermineral?>" >
					</span>
					
				</td>
			</tr>
			
			<tr><td>Comment:</td><td><input type="text" name="comment" value="<?=$comment?>" ></td></tr>
		</table>
		
		<br>
		<h1>Decay Constant Parameters</h1>
		<div style="color:#AAAAAA;">&nbsp;&nbsp;Change if different than Steiger/Jager</div>
		<table style="font-size:10px;">
			<tr><td>238U Decay Constant:<span style="color:red;">*</span></td><td><input type="text" name="udecayconstant238" value="<?=$udecayconstant238?>" ></td></tr>
			<tr><td>238U Decay Constant Error:</td><td><input type="text" name="udecayconstanterror238" value="<?=$udecayconstanterror238?>" ></td></tr>
			<tr><td>235U Decay Constant:<span style="color:red;">*</span></td><td><input type="text" name="udecayconstant235" value="<?=$udecayconstant235?>" ></td></tr>
			<tr><td>235U Decay Constant Error:</td><td><input type="text" name="udecayconstanterror235" value="<?=$udecayconstanterror235?>" ></td></tr>
			<tr><td>232Th Decay Constant:<span style="color:red;">*</span></td><td><input type="text" name="thdecayconstant232" value="<?=$thdecayconstant232?>" ></td></tr>
			<tr><td>232Th Decay Constant Error:</td><td><input type="text" name="thdecayconstanterror232" value="<?=$thdecayconstanterror232?>" ></td></tr>
			<tr><td>230Th Decay Constant:</td><td><input type="text" name="thdecayconstant230" value="<?=$thdecayconstant230?>" ></td></tr>
			<tr><td>230Th Decay Constant Error:</td><td><input type="text" name="thdecayconstanterror230" value="<?=$thdecayconstanterror230?>" ></td></tr>
			<tr><td>235U/238U:<span style="color:red;">*</span></td><td><input type="text" name="u235u238" value="<?=$u235u238?>" ></td></tr>
			<tr><td>Decay Constant Reference:<span style="color:red;">*</span></td><td><input type="text" name="decayconstantreference" value="<?=$decayconstantreference?>" ></td></tr>
			<tr><td>Comment:</td><td><input type="text" name="decaycomment" value="<?=$comment?>" ></td></tr>
		</table>







































	

		<br>
		<h1>Interpreted Sample Age Information</h1>
		<table style="font-size:10px;">




			<tr id="agerow1">
				<td>
					<table style="font-size:10px;">
					<tr><td>Analysis Purpose:<span style="color:red;">*</span></td>
						<td>
							<select name="analysispurpose1" id="analysispurpose1" onchange="avdiv1();">
								<option value="">select...</option>
								<option value="DetritalSpectrum" <? if($analysispurpose1=="DetritalSpectrum"){echo "selected";} ?>>DetritalSpectrum</option>
								<option value="SingleAge" <? if($analysispurpose1=="SingleAge"){echo "selected";} ?>>SingleAge</option>
								<option value="Cooling" <? if($analysispurpose1=="Cooling"){echo "selected";} ?>>Cooling</option>
								<option value="Crystallization" <? if($analysispurpose1=="Crystallization"){echo "selected";} ?>>Crystallization</option>
								<option value="Metamorphic" <? if($analysispurpose1=="Metamorphic"){echo "selected";} ?>>Metamorphic</option>
								<option value="Recrystallization" <? if($analysispurpose1=="Recrystallization"){echo "selected";} ?>>Recrystallization</option>
								<option value="Inheritence" <? if($analysispurpose1=="Inheritence"){echo "selected";} ?>>Inheritence</option>
								<option value="Shock" <? if($analysispurpose1=="Shock"){echo "selected";} ?>>Shock</option>
								<option value="Hydrothermal" <? if($analysispurpose1=="Hydrothermal"){echo "selected";} ?>>Hydrothermal</option>
								<option value="Diagenetic" <? if($analysispurpose1=="Diagenetic"){echo "selected";} ?>>Diagenetic</option>
								<option value="TimeScaleCalibration" <? if($analysispurpose1=="TimeScaleCalibration"){echo "selected";} ?>>TimeScaleCalibration</option>
								<option value="other" <? if($mineral=="other"){echo "selected";} ?>>other</option>
							</select>
							
							<span id="otherapdiv1" style="color:red;display:none;">
							&nbsp;&nbsp;&nbsp;Please Provide:&nbsp;<input type="text" id="otherap1" name="otherap1" value="<?=$otherap1?>" >
							</span>
							
						</td>
					</tr>
					<tr>
						<td>Preferred Age Type:</td>
						<td>
						
						
							<select name="preferredagetype1" id="preferredagetype1" onchange="patypediv1();">
								<option value="">select...</option>
								<option value="single analysis 206Pb/238U" <? if($preferredagetype1=="single analysis 206Pb/238U"){echo "selected";} ?>>single analysis 206Pb/238U</option>
								<option value="single analysis 207Pb/235U" <? if($preferredagetype1=="single analysis 207Pb/235U"){echo "selected";} ?>>single analysis 207Pb/235U</option>
								<option value="single analysis 207Pb/206Pb" <? if($preferredagetype1=="single analysis 207Pb/206Pb"){echo "selected";} ?>>single analysis 207Pb/206Pb</option>
								<option value="single analysis 208Pb/232Th" <? if($preferredagetype1=="single analysis 208Pb/232Th"){echo "selected";} ?>>single analysis 208Pb/232Th</option>
								<option value="weighted mean 207Pb/235U" <? if($preferredagetype1=="weighted mean 207Pb/235U"){echo "selected";} ?>>weighted mean 207Pb/235U</option>
								<option value="weighted mean 206Pb/238U" <? if($preferredagetype1=="weighted mean 206Pb/238U"){echo "selected";} ?>>weighted mean 206Pb/238U</option>
								<option value="weighted mean 207Pb/206Pb" <? if($preferredagetype1=="weighted mean 207Pb/206Pb"){echo "selected";} ?>>weighted mean 207Pb/206Pb</option>
								<option value="weighted mean 208Pb/232Th" <? if($preferredagetype1=="weighted mean 208Pb/232Th"){echo "selected";} ?>>weighted mean 208Pb/232Th</option>
								<option value="weighted mean 207Pb/235U (Pa-corrected)" <? if($preferredagetype1=="weighted mean 207Pb/235U (Pa-corrected)"){echo "selected";} ?>>weighted mean 207Pb/235U (Pa-corrected)</option>
								<option value="weighted mean 206Pb/238U (Th- and Pa-corrected)" <? if($preferredagetype1=="weighted mean 206Pb/238U (Th- and Pa-corrected)"){echo "selected";} ?>>weighted mean 206Pb/238U (Th- and Pa-corrected)</option>
								<option value="weighted mean 207Pb/206Pb (Th-corrected)" <? if($preferredagetype1=="weighted mean 207Pb/206Pb (Th-corrected)"){echo "selected";} ?>>weighted mean 207Pb/206Pb (Th-corrected)</option>
								<option value="weighted mean 207Pb/206Pb (Pa-corrected)" <? if($preferredagetype1=="weighted mean 207Pb/206Pb (Pa-corrected)"){echo "selected";} ?>>weighted mean 207Pb/206Pb (Pa-corrected)</option>
								<option value="weighted mean 207Pb/206Pb (Th- and Pa-corrected)" <? if($preferredagetype1=="weighted mean 207Pb/206Pb (Th- and Pa-corrected)"){echo "selected";} ?>>weighted mean 207Pb/206Pb (Th- and Pa-corrected)</option>
								<option value="minimum age" <? if($preferredagetype1=="minimum age"){echo "selected";} ?>>minimum age</option>
								<option value="maximum age" <? if($preferredagetype1=="maximum age"){echo "selected";} ?>>maximum age</option>
								<option value="upper intercept" <? if($preferredagetype1=="upper intercept"){echo "selected";} ?>>upper intercept</option>
								<option value="lower intercept" <? if($preferredagetype1=="lower intercept"){echo "selected";} ?>>lower intercept</option>
								<option value="238U-206Pb isochron" <? if($preferredagetype1=="238U-206Pb isochron"){echo "selected";} ?>>238U-206Pb isochron</option>
								<option value="235U-207Pb isochron" <? if($preferredagetype1=="235U-207Pb isochron"){echo "selected";} ?>>235U-207Pb isochron</option>
								<option value="232Th-208Pb isochron" <? if($preferredagetype1=="232Th-208Pb isochron"){echo "selected";} ?>>232Th-208Pb isochron</option>
								<option value="semi-total Pb isochron" <? if($preferredagetype1=="semi-total Pb isochron"){echo "selected";} ?>>semi-total Pb isochron</option>
								<option value="total Pb isochron" <? if($preferredagetype1=="total Pb isochron"){echo "selected";} ?>>total Pb isochron</option>
								<option value="none" <? if($preferredagetype1=="none"){echo "selected";} ?>>none</option>
								<option value="other" <? if($preferredagetype1=="other"){echo "selected";} ?>>other</option>
							</select>

							<span id="otherpatypediv1" style="color:red;display:none;">
							&nbsp;&nbsp;&nbsp;Please Provide:&nbsp;<input type="text" id="otherpatype1" name="otherpatype1" value="<?=$otherpatype1?>" >
							</span>
						
						</td>
					</tr>
					<tr><td>Preferred Age:</td><td><input type="text" name="preferredage1" value="<?=$preferredage1?>" ></td></tr>
					<tr><td>Preferred Age Error:</td><td><input type="text" name="preferredageerror1" value="<?=$preferredageerror1?>" >&nbsp;&plusmn;2&sigma;(abs)</td></tr>
					<tr><td>MSWD:</td><td><input type="text" name="mswd1" value="<?=$mswd1?>" ></td></tr>
					<tr><td>Age Error (Systematic):</td><td><input type="text" name="ageerrorsystematic1" value="<?=$ageerrorsystematic1?>" >&nbsp;&plusmn;2&sigma;(abs)</td></tr>
					<tr><td>Preferred Age Included Analyses:</td><td><input type="text" name="preferredageincludedanalyses1" value="<?=$preferredageincludedanalyses1?>" ></td></tr>
					<tr><td>Preferred Age Explanation:</td><td><input type="text" name="preferredageexplanation1" value="<?=$preferredageexplanation1?>" ></td></tr>
					<tr>
						<td>Common Lead Correction</td>
						<td>
						
							<select name="commonleadcorrection1">
								<option value="">select...</option>
								<option value="204" <? if($commonleadcorrection1=="204"){echo "selected";} ?>>204</option>
								<option value="207" <? if($commonleadcorrection1=="207"){echo "selected";} ?>>207</option>
								<option value="208" <? if($commonleadcorrection1=="208"){echo "selected";} ?>>208</option>
							</select>
						</td>
					</tr>
					<tr><td>&nbsp;</td></tr>
					</table>
				</td>
			</tr>
			

			<tr id="agerow2" style="display:none;">
				<td>
					<table style="font-size:10px;">
					<tr><td>Additional Analysis Purpose:<span style="color:red;">*</span></td>
						<td>
							<select name="analysispurpose2" id="analysispurpose2" onchange="avdiv2();">
								<option value="">select...</option>
								<option value="DetritalSpectrum" <? if($analysispurpose2=="DetritalSpectrum"){echo "selected";} ?>>DetritalSpectrum</option>
								<option value="SingleAge" <? if($analysispurpose2=="SingleAge"){echo "selected";} ?>>SingleAge</option>
								<option value="Cooling" <? if($analysispurpose2=="Cooling"){echo "selected";} ?>>Cooling</option>
								<option value="Crystallization" <? if($analysispurpose2=="Crystallization"){echo "selected";} ?>>Crystallization</option>
								<option value="Metamorphic" <? if($analysispurpose2=="Metamorphic"){echo "selected";} ?>>Metamorphic</option>
								<option value="Recrystallization" <? if($analysispurpose2=="Recrystallization"){echo "selected";} ?>>Recrystallization</option>
								<option value="Inheritence" <? if($analysispurpose2=="Inheritence"){echo "selected";} ?>>Inheritence</option>
								<option value="Shock" <? if($analysispurpose2=="Shock"){echo "selected";} ?>>Shock</option>
								<option value="Hydrothermal" <? if($analysispurpose2=="Hydrothermal"){echo "selected";} ?>>Hydrothermal</option>
								<option value="Diagenetic" <? if($analysispurpose2=="Diagenetic"){echo "selected";} ?>>Diagenetic</option>
								<option value="TimeScaleCalibration" <? if($analysispurpose2=="TimeScaleCalibration"){echo "selected";} ?>>TimeScaleCalibration</option>
								<option value="other" <? if($mineral=="other"){echo "selected";} ?>>other</option>
							</select>
							
							<span id="otherapdiv2" style="color:red;display:none;">
							&nbsp;&nbsp;&nbsp;Please Provide:&nbsp;<input type="text" id="otherap2" name="otherap2" value="<?=$otherap2?>" >
							</span>
							
						</td>
					</tr>
					<tr>
						<td>Additional Age Type:</td>
						<td>
						
						
							<select name="preferredagetype2" id="preferredagetype2" onchange="patypediv2();">
								<option value="">select...</option>
								<option value="single analysis 206Pb/238U" <? if($preferredagetype2=="single analysis 206Pb/238U"){echo "selected";} ?>>single analysis 206Pb/238U</option>
								<option value="single analysis 207Pb/235U" <? if($preferredagetype2=="single analysis 207Pb/235U"){echo "selected";} ?>>single analysis 207Pb/235U</option>
								<option value="single analysis 207Pb/206Pb" <? if($preferredagetype2=="single analysis 207Pb/206Pb"){echo "selected";} ?>>single analysis 207Pb/206Pb</option>
								<option value="single analysis 208Pb/232Th" <? if($preferredagetype2=="single analysis 208Pb/232Th"){echo "selected";} ?>>single analysis 208Pb/232Th</option>
								<option value="weighted mean 207Pb/235U" <? if($preferredagetype2=="weighted mean 207Pb/235U"){echo "selected";} ?>>weighted mean 207Pb/235U</option>
								<option value="weighted mean 206Pb/238U" <? if($preferredagetype2=="weighted mean 206Pb/238U"){echo "selected";} ?>>weighted mean 206Pb/238U</option>
								<option value="weighted mean 207Pb/206Pb" <? if($preferredagetype2=="weighted mean 207Pb/206Pb"){echo "selected";} ?>>weighted mean 207Pb/206Pb</option>
								<option value="weighted mean 208Pb/232Th" <? if($preferredagetype2=="weighted mean 208Pb/232Th"){echo "selected";} ?>>weighted mean 208Pb/232Th</option>
								<option value="weighted mean 207Pb/235U (Pa-corrected)" <? if($preferredagetype2=="weighted mean 207Pb/235U (Pa-corrected)"){echo "selected";} ?>>weighted mean 207Pb/235U (Pa-corrected)</option>
								<option value="weighted mean 206Pb/238U (Th- and Pa-corrected)" <? if($preferredagetype2=="weighted mean 206Pb/238U (Th- and Pa-corrected)"){echo "selected";} ?>>weighted mean 206Pb/238U (Th- and Pa-corrected)</option>
								<option value="weighted mean 207Pb/206Pb (Th-corrected)" <? if($preferredagetype2=="weighted mean 207Pb/206Pb (Th-corrected)"){echo "selected";} ?>>weighted mean 207Pb/206Pb (Th-corrected)</option>
								<option value="weighted mean 207Pb/206Pb (Pa-corrected)" <? if($preferredagetype2=="weighted mean 207Pb/206Pb (Pa-corrected)"){echo "selected";} ?>>weighted mean 207Pb/206Pb (Pa-corrected)</option>
								<option value="weighted mean 207Pb/206Pb (Th- and Pa-corrected)" <? if($preferredagetype2=="weighted mean 207Pb/206Pb (Th- and Pa-corrected)"){echo "selected";} ?>>weighted mean 207Pb/206Pb (Th- and Pa-corrected)</option>
								<option value="minimum age" <? if($preferredagetype2=="minimum age"){echo "selected";} ?>>minimum age</option>
								<option value="maximum age" <? if($preferredagetype2=="maximum age"){echo "selected";} ?>>maximum age</option>
								<option value="upper intercept" <? if($preferredagetype2=="upper intercept"){echo "selected";} ?>>upper intercept</option>
								<option value="lower intercept" <? if($preferredagetype2=="lower intercept"){echo "selected";} ?>>lower intercept</option>
								<option value="238U-206Pb isochron" <? if($preferredagetype2=="238U-206Pb isochron"){echo "selected";} ?>>238U-206Pb isochron</option>
								<option value="235U-207Pb isochron" <? if($preferredagetype2=="235U-207Pb isochron"){echo "selected";} ?>>235U-207Pb isochron</option>
								<option value="232Th-208Pb isochron" <? if($preferredagetype2=="232Th-208Pb isochron"){echo "selected";} ?>>232Th-208Pb isochron</option>
								<option value="semi-total Pb isochron" <? if($preferredagetype2=="semi-total Pb isochron"){echo "selected";} ?>>semi-total Pb isochron</option>
								<option value="total Pb isochron" <? if($preferredagetype2=="total Pb isochron"){echo "selected";} ?>>total Pb isochron</option>
								<option value="none" <? if($preferredagetype2=="none"){echo "selected";} ?>>none</option>
								<option value="other" <? if($preferredagetype2=="other"){echo "selected";} ?>>other</option>
							</select>

							<span id="otherpatypediv2" style="color:red;display:none;">
							&nbsp;&nbsp;&nbsp;Please Provide:&nbsp;<input type="text" id="otherpatype2" name="otherpatype2" value="<?=$otherpatype2?>" >
							</span>
						
						</td>
					</tr>
					<tr><td>Age:</td><td><input type="text" name="preferredage2" value="<?=$preferredage2?>" ></td></tr>
					<tr><td>Age Error (Analytical):</td><td><input type="text" name="preferredageerror2" value="<?=$preferredageerror2?>" >&nbsp;&plusmn;2&sigma;(abs)</td></tr>
					<tr><td>MSWD:</td><td><input type="text" name="mswd2" value="<?=$mswd2?>" ></td></tr>
					<tr><td>Age Error (Systematic):</td><td><input type="text" name="ageerrorsystematic2" value="<?=$ageerrorsystematic2?>" >&nbsp;&plusmn;2&sigma;(abs)</td></tr>
					<tr><td>Included Analyses:</td><td><input type="text" name="preferredageincludedanalyses2" value="<?=$preferredageincludedanalyses2?>" ></td></tr>
					<tr><td>Age Comment:</td><td><input type="text" name="preferredageexplanation2" value="<?=$preferredageexplanation2?>" ></td></tr>
					<tr>
						<td>Common Lead Correction</td>
						<td>
						
							<select name="commonleadcorrection2">
								<option value="">select...</option>
								<option value="204" <? if($commonleadcorrection2=="204"){echo "selected";} ?>>204</option>
								<option value="207" <? if($commonleadcorrection2=="207"){echo "selected";} ?>>207</option>
								<option value="208" <? if($commonleadcorrection2=="208"){echo "selected";} ?>>208</option>
							</select>
						</td>
					</tr>
					<tr><td>&nbsp;</td></tr>
					</table>
				</td>
			</tr>


			<tr id="agerow3" style="display:none;">
				<td>
					<table style="font-size:10px;">
					<tr><td>Additional Analysis Purpose:<span style="color:red;">*</span></td>
						<td>
							<select name="analysispurpose3" id="analysispurpose3" onchange="avdiv3();">
								<option value="">select...</option>
								<option value="DetritalSpectrum" <? if($analysispurpose3=="DetritalSpectrum"){echo "selected";} ?>>DetritalSpectrum</option>
								<option value="SingleAge" <? if($analysispurpose3=="SingleAge"){echo "selected";} ?>>SingleAge</option>
								<option value="Cooling" <? if($analysispurpose3=="Cooling"){echo "selected";} ?>>Cooling</option>
								<option value="Crystallization" <? if($analysispurpose3=="Crystallization"){echo "selected";} ?>>Crystallization</option>
								<option value="Metamorphic" <? if($analysispurpose3=="Metamorphic"){echo "selected";} ?>>Metamorphic</option>
								<option value="Recrystallization" <? if($analysispurpose3=="Recrystallization"){echo "selected";} ?>>Recrystallization</option>
								<option value="Inheritence" <? if($analysispurpose3=="Inheritence"){echo "selected";} ?>>Inheritence</option>
								<option value="Shock" <? if($analysispurpose3=="Shock"){echo "selected";} ?>>Shock</option>
								<option value="Hydrothermal" <? if($analysispurpose3=="Hydrothermal"){echo "selected";} ?>>Hydrothermal</option>
								<option value="Diagenetic" <? if($analysispurpose3=="Diagenetic"){echo "selected";} ?>>Diagenetic</option>
								<option value="TimeScaleCalibration" <? if($analysispurpose3=="TimeScaleCalibration"){echo "selected";} ?>>TimeScaleCalibration</option>
								<option value="other" <? if($mineral=="other"){echo "selected";} ?>>other</option>
							</select>
							
							<span id="otherapdiv3" style="color:red;display:none;">
							&nbsp;&nbsp;&nbsp;Please Provide:&nbsp;<input type="text" id="otherap3" name="otherap3" value="<?=$otherap3?>" >
							</span>
							
						</td>
					</tr>
					<tr>
						<td>Additional Age Type:</td>
						<td>
						
						
							<select name="preferredagetype3" id="preferredagetype3" onchange="patypediv3();">
								<option value="">select...</option>
								<option value="single analysis 206Pb/238U" <? if($preferredagetype3=="single analysis 206Pb/238U"){echo "selected";} ?>>single analysis 206Pb/238U</option>
								<option value="single analysis 207Pb/235U" <? if($preferredagetype3=="single analysis 207Pb/235U"){echo "selected";} ?>>single analysis 207Pb/235U</option>
								<option value="single analysis 207Pb/206Pb" <? if($preferredagetype3=="single analysis 207Pb/206Pb"){echo "selected";} ?>>single analysis 207Pb/206Pb</option>
								<option value="single analysis 208Pb/232Th" <? if($preferredagetype3=="single analysis 208Pb/232Th"){echo "selected";} ?>>single analysis 208Pb/232Th</option>
								<option value="weighted mean 207Pb/235U" <? if($preferredagetype3=="weighted mean 207Pb/235U"){echo "selected";} ?>>weighted mean 207Pb/235U</option>
								<option value="weighted mean 206Pb/238U" <? if($preferredagetype3=="weighted mean 206Pb/238U"){echo "selected";} ?>>weighted mean 206Pb/238U</option>
								<option value="weighted mean 207Pb/206Pb" <? if($preferredagetype3=="weighted mean 207Pb/206Pb"){echo "selected";} ?>>weighted mean 207Pb/206Pb</option>
								<option value="weighted mean 208Pb/232Th" <? if($preferredagetype3=="weighted mean 208Pb/232Th"){echo "selected";} ?>>weighted mean 208Pb/232Th</option>
								<option value="weighted mean 207Pb/235U (Pa-corrected)" <? if($preferredagetype3=="weighted mean 207Pb/235U (Pa-corrected)"){echo "selected";} ?>>weighted mean 207Pb/235U (Pa-corrected)</option>
								<option value="weighted mean 206Pb/238U (Th- and Pa-corrected)" <? if($preferredagetype3=="weighted mean 206Pb/238U (Th- and Pa-corrected)"){echo "selected";} ?>>weighted mean 206Pb/238U (Th- and Pa-corrected)</option>
								<option value="weighted mean 207Pb/206Pb (Th-corrected)" <? if($preferredagetype3=="weighted mean 207Pb/206Pb (Th-corrected)"){echo "selected";} ?>>weighted mean 207Pb/206Pb (Th-corrected)</option>
								<option value="weighted mean 207Pb/206Pb (Pa-corrected)" <? if($preferredagetype3=="weighted mean 207Pb/206Pb (Pa-corrected)"){echo "selected";} ?>>weighted mean 207Pb/206Pb (Pa-corrected)</option>
								<option value="weighted mean 207Pb/206Pb (Th- and Pa-corrected)" <? if($preferredagetype3=="weighted mean 207Pb/206Pb (Th- and Pa-corrected)"){echo "selected";} ?>>weighted mean 207Pb/206Pb (Th- and Pa-corrected)</option>
								<option value="minimum age" <? if($preferredagetype3=="minimum age"){echo "selected";} ?>>minimum age</option>
								<option value="maximum age" <? if($preferredagetype3=="maximum age"){echo "selected";} ?>>maximum age</option>
								<option value="upper intercept" <? if($preferredagetype3=="upper intercept"){echo "selected";} ?>>upper intercept</option>
								<option value="lower intercept" <? if($preferredagetype3=="lower intercept"){echo "selected";} ?>>lower intercept</option>
								<option value="238U-206Pb isochron" <? if($preferredagetype3=="238U-206Pb isochron"){echo "selected";} ?>>238U-206Pb isochron</option>
								<option value="235U-207Pb isochron" <? if($preferredagetype3=="235U-207Pb isochron"){echo "selected";} ?>>235U-207Pb isochron</option>
								<option value="232Th-208Pb isochron" <? if($preferredagetype3=="232Th-208Pb isochron"){echo "selected";} ?>>232Th-208Pb isochron</option>
								<option value="semi-total Pb isochron" <? if($preferredagetype3=="semi-total Pb isochron"){echo "selected";} ?>>semi-total Pb isochron</option>
								<option value="total Pb isochron" <? if($preferredagetype3=="total Pb isochron"){echo "selected";} ?>>total Pb isochron</option>
								<option value="none" <? if($preferredagetype3=="none"){echo "selected";} ?>>none</option>
								<option value="other" <? if($preferredagetype3=="other"){echo "selected";} ?>>other</option>
							</select>

							<span id="otherpatypediv3" style="color:red;display:none;">
							&nbsp;&nbsp;&nbsp;Please Provide:&nbsp;<input type="text" id="otherpatype3" name="otherpatype3" value="<?=$otherpatype3?>" >
							</span>
						
						</td>
					</tr>
					<tr><td>Age:</td><td><input type="text" name="preferredage3" value="<?=$preferredage3?>" ></td></tr>
					<tr><td>Age Error (Analytical):</td><td><input type="text" name="preferredageerror3" value="<?=$preferredageerror3?>" >&nbsp;&plusmn;2&sigma;(abs)</td></tr>
					<tr><td>MSWD:</td><td><input type="text" name="mswd3" value="<?=$mswd3?>" ></td></tr>
					<tr><td>Age Error (Systematic):</td><td><input type="text" name="ageerrorsystematic3" value="<?=$ageerrorsystematic3?>" >&nbsp;&plusmn;2&sigma;(abs)</td></tr>
					<tr><td>Included Analyses:</td><td><input type="text" name="preferredageincludedanalyses3" value="<?=$preferredageincludedanalyses3?>" ></td></tr>
					<tr><td>Age Comment:</td><td><input type="text" name="preferredageexplanation3" value="<?=$preferredageexplanation3?>" ></td></tr>
					<tr>
						<td>Common Lead Correction</td>
						<td>
						
							<select name="commonleadcorrection3">
								<option value="">select...</option>
								<option value="204" <? if($commonleadcorrection3=="204"){echo "selected";} ?>>204</option>
								<option value="207" <? if($commonleadcorrection3=="207"){echo "selected";} ?>>207</option>
								<option value="208" <? if($commonleadcorrection3=="208"){echo "selected";} ?>>208</option>
							</select>
						</td>
					</tr>
					<tr><td>&nbsp;</td></tr>
					</table>
				</td>
			</tr>


			<tr id="agerow4" style="display:none;">
				<td>
					<table style="font-size:10px;">
					<tr><td>Additional Analysis Purpose:<span style="color:red;">*</span></td>
						<td>
							<select name="analysispurpose4" id="analysispurpose4" onchange="avdiv4();">
								<option value="">select...</option>
								<option value="DetritalSpectrum" <? if($analysispurpose4=="DetritalSpectrum"){echo "selected";} ?>>DetritalSpectrum</option>
								<option value="SingleAge" <? if($analysispurpose4=="SingleAge"){echo "selected";} ?>>SingleAge</option>
								<option value="Cooling" <? if($analysispurpose4=="Cooling"){echo "selected";} ?>>Cooling</option>
								<option value="Crystallization" <? if($analysispurpose4=="Crystallization"){echo "selected";} ?>>Crystallization</option>
								<option value="Metamorphic" <? if($analysispurpose4=="Metamorphic"){echo "selected";} ?>>Metamorphic</option>
								<option value="Recrystallization" <? if($analysispurpose4=="Recrystallization"){echo "selected";} ?>>Recrystallization</option>
								<option value="Inheritence" <? if($analysispurpose4=="Inheritence"){echo "selected";} ?>>Inheritence</option>
								<option value="Shock" <? if($analysispurpose4=="Shock"){echo "selected";} ?>>Shock</option>
								<option value="Hydrothermal" <? if($analysispurpose4=="Hydrothermal"){echo "selected";} ?>>Hydrothermal</option>
								<option value="Diagenetic" <? if($analysispurpose4=="Diagenetic"){echo "selected";} ?>>Diagenetic</option>
								<option value="TimeScaleCalibration" <? if($analysispurpose4=="TimeScaleCalibration"){echo "selected";} ?>>TimeScaleCalibration</option>
								<option value="other" <? if($mineral=="other"){echo "selected";} ?>>other</option>
							</select>
							
							<span id="otherapdiv4" style="color:red;display:none;">
							&nbsp;&nbsp;&nbsp;Please Provide:&nbsp;<input type="text" id="otherap4" name="otherap4" value="<?=$otherap4?>" >
							</span>
							
						</td>
					</tr>
					<tr>
						<td>Additional Age Type:</td>
						<td>
						
						
							<select name="preferredagetype4" id="preferredagetype4" onchange="patypediv4();">
								<option value="">select...</option>
								<option value="single analysis 206Pb/238U" <? if($preferredagetype4=="single analysis 206Pb/238U"){echo "selected";} ?>>single analysis 206Pb/238U</option>
								<option value="single analysis 207Pb/235U" <? if($preferredagetype4=="single analysis 207Pb/235U"){echo "selected";} ?>>single analysis 207Pb/235U</option>
								<option value="single analysis 207Pb/206Pb" <? if($preferredagetype4=="single analysis 207Pb/206Pb"){echo "selected";} ?>>single analysis 207Pb/206Pb</option>
								<option value="single analysis 208Pb/232Th" <? if($preferredagetype4=="single analysis 208Pb/232Th"){echo "selected";} ?>>single analysis 208Pb/232Th</option>
								<option value="weighted mean 207Pb/235U" <? if($preferredagetype4=="weighted mean 207Pb/235U"){echo "selected";} ?>>weighted mean 207Pb/235U</option>
								<option value="weighted mean 206Pb/238U" <? if($preferredagetype4=="weighted mean 206Pb/238U"){echo "selected";} ?>>weighted mean 206Pb/238U</option>
								<option value="weighted mean 207Pb/206Pb" <? if($preferredagetype4=="weighted mean 207Pb/206Pb"){echo "selected";} ?>>weighted mean 207Pb/206Pb</option>
								<option value="weighted mean 208Pb/232Th" <? if($preferredagetype4=="weighted mean 208Pb/232Th"){echo "selected";} ?>>weighted mean 208Pb/232Th</option>
								<option value="weighted mean 207Pb/235U (Pa-corrected)" <? if($preferredagetype4=="weighted mean 207Pb/235U (Pa-corrected)"){echo "selected";} ?>>weighted mean 207Pb/235U (Pa-corrected)</option>
								<option value="weighted mean 206Pb/238U (Th- and Pa-corrected)" <? if($preferredagetype4=="weighted mean 206Pb/238U (Th- and Pa-corrected)"){echo "selected";} ?>>weighted mean 206Pb/238U (Th- and Pa-corrected)</option>
								<option value="weighted mean 207Pb/206Pb (Th-corrected)" <? if($preferredagetype4=="weighted mean 207Pb/206Pb (Th-corrected)"){echo "selected";} ?>>weighted mean 207Pb/206Pb (Th-corrected)</option>
								<option value="weighted mean 207Pb/206Pb (Pa-corrected)" <? if($preferredagetype4=="weighted mean 207Pb/206Pb (Pa-corrected)"){echo "selected";} ?>>weighted mean 207Pb/206Pb (Pa-corrected)</option>
								<option value="weighted mean 207Pb/206Pb (Th- and Pa-corrected)" <? if($preferredagetype4=="weighted mean 207Pb/206Pb (Th- and Pa-corrected)"){echo "selected";} ?>>weighted mean 207Pb/206Pb (Th- and Pa-corrected)</option>
								<option value="minimum age" <? if($preferredagetype4=="minimum age"){echo "selected";} ?>>minimum age</option>
								<option value="maximum age" <? if($preferredagetype4=="maximum age"){echo "selected";} ?>>maximum age</option>
								<option value="upper intercept" <? if($preferredagetype4=="upper intercept"){echo "selected";} ?>>upper intercept</option>
								<option value="lower intercept" <? if($preferredagetype4=="lower intercept"){echo "selected";} ?>>lower intercept</option>
								<option value="238U-206Pb isochron" <? if($preferredagetype4=="238U-206Pb isochron"){echo "selected";} ?>>238U-206Pb isochron</option>
								<option value="235U-207Pb isochron" <? if($preferredagetype4=="235U-207Pb isochron"){echo "selected";} ?>>235U-207Pb isochron</option>
								<option value="232Th-208Pb isochron" <? if($preferredagetype4=="232Th-208Pb isochron"){echo "selected";} ?>>232Th-208Pb isochron</option>
								<option value="semi-total Pb isochron" <? if($preferredagetype4=="semi-total Pb isochron"){echo "selected";} ?>>semi-total Pb isochron</option>
								<option value="total Pb isochron" <? if($preferredagetype4=="total Pb isochron"){echo "selected";} ?>>total Pb isochron</option>
								<option value="none" <? if($preferredagetype4=="none"){echo "selected";} ?>>none</option>
								<option value="other" <? if($preferredagetype4=="other"){echo "selected";} ?>>other</option>
							</select>

							<span id="otherpatypediv4" style="color:red;display:none;">
							&nbsp;&nbsp;&nbsp;Please Provide:&nbsp;<input type="text" id="otherpatype4" name="otherpatype4" value="<?=$otherpatype4?>" >
							</span>
						
						</td>
					</tr>
					<tr><td>Age:</td><td><input type="text" name="preferredage4" value="<?=$preferredage4?>" ></td></tr>
					<tr><td>Age Error (Analytical):</td><td><input type="text" name="preferredageerror4" value="<?=$preferredageerror4?>" >&nbsp;&plusmn;2&sigma;(abs)</td></tr>
					<tr><td>MSWD:</td><td><input type="text" name="mswd4" value="<?=$mswd4?>" ></td></tr>
					<tr><td>Age Error (Systematic):</td><td><input type="text" name="ageerrorsystematic4" value="<?=$ageerrorsystematic4?>" >&nbsp;&plusmn;2&sigma;(abs)</td></tr>
					<tr><td>Included Analyses:</td><td><input type="text" name="preferredageincludedanalyses4" value="<?=$preferredageincludedanalyses4?>" ></td></tr>
					<tr><td>Age Comment:</td><td><input type="text" name="preferredageexplanation4" value="<?=$preferredageexplanation4?>" ></td></tr>
					<tr>
						<td>Common Lead Correction</td>
						<td>
						
							<select name="commonleadcorrection4">
								<option value="">select...</option>
								<option value="204" <? if($commonleadcorrection4=="204"){echo "selected";} ?>>204</option>
								<option value="207" <? if($commonleadcorrection4=="207"){echo "selected";} ?>>207</option>
								<option value="208" <? if($commonleadcorrection4=="208"){echo "selected";} ?>>208</option>
							</select>
						</td>
					</tr>
					<tr><td>&nbsp;</td></tr>
					</table>
				</td>
			</tr>


			<tr id="agerow5" style="display:none;">
				<td>
					<table style="font-size:10px;">
					<tr><td>Additional Analysis Purpose:<span style="color:red;">*</span></td>
						<td>
							<select name="analysispurpose5" id="analysispurpose5" onchange="avdiv5();">
								<option value="">select...</option>
								<option value="DetritalSpectrum" <? if($analysispurpose5=="DetritalSpectrum"){echo "selected";} ?>>DetritalSpectrum</option>
								<option value="SingleAge" <? if($analysispurpose5=="SingleAge"){echo "selected";} ?>>SingleAge</option>
								<option value="Cooling" <? if($analysispurpose5=="Cooling"){echo "selected";} ?>>Cooling</option>
								<option value="Crystallization" <? if($analysispurpose5=="Crystallization"){echo "selected";} ?>>Crystallization</option>
								<option value="Metamorphic" <? if($analysispurpose5=="Metamorphic"){echo "selected";} ?>>Metamorphic</option>
								<option value="Recrystallization" <? if($analysispurpose5=="Recrystallization"){echo "selected";} ?>>Recrystallization</option>
								<option value="Inheritence" <? if($analysispurpose5=="Inheritence"){echo "selected";} ?>>Inheritence</option>
								<option value="Shock" <? if($analysispurpose5=="Shock"){echo "selected";} ?>>Shock</option>
								<option value="Hydrothermal" <? if($analysispurpose5=="Hydrothermal"){echo "selected";} ?>>Hydrothermal</option>
								<option value="Diagenetic" <? if($analysispurpose5=="Diagenetic"){echo "selected";} ?>>Diagenetic</option>
								<option value="TimeScaleCalibration" <? if($analysispurpose5=="TimeScaleCalibration"){echo "selected";} ?>>TimeScaleCalibration</option>
								<option value="other" <? if($mineral=="other"){echo "selected";} ?>>other</option>
							</select>
							
							<span id="otherapdiv5" style="color:red;display:none;">
							&nbsp;&nbsp;&nbsp;Please Provide:&nbsp;<input type="text" id="otherap5" name="otherap5" value="<?=$otherap5?>" >
							</span>
							
						</td>
					</tr>
					<tr>
						<td>Additional Age Type:</td>
						<td>
						
						
							<select name="preferredagetype5" id="preferredagetype5" onchange="patypediv5();">
								<option value="">select...</option>
								<option value="single analysis 206Pb/238U" <? if($preferredagetype5=="single analysis 206Pb/238U"){echo "selected";} ?>>single analysis 206Pb/238U</option>
								<option value="single analysis 207Pb/235U" <? if($preferredagetype5=="single analysis 207Pb/235U"){echo "selected";} ?>>single analysis 207Pb/235U</option>
								<option value="single analysis 207Pb/206Pb" <? if($preferredagetype5=="single analysis 207Pb/206Pb"){echo "selected";} ?>>single analysis 207Pb/206Pb</option>
								<option value="single analysis 208Pb/232Th" <? if($preferredagetype5=="single analysis 208Pb/232Th"){echo "selected";} ?>>single analysis 208Pb/232Th</option>
								<option value="weighted mean 207Pb/235U" <? if($preferredagetype5=="weighted mean 207Pb/235U"){echo "selected";} ?>>weighted mean 207Pb/235U</option>
								<option value="weighted mean 206Pb/238U" <? if($preferredagetype5=="weighted mean 206Pb/238U"){echo "selected";} ?>>weighted mean 206Pb/238U</option>
								<option value="weighted mean 207Pb/206Pb" <? if($preferredagetype5=="weighted mean 207Pb/206Pb"){echo "selected";} ?>>weighted mean 207Pb/206Pb</option>
								<option value="weighted mean 208Pb/232Th" <? if($preferredagetype5=="weighted mean 208Pb/232Th"){echo "selected";} ?>>weighted mean 208Pb/232Th</option>
								<option value="weighted mean 207Pb/235U (Pa-corrected)" <? if($preferredagetype5=="weighted mean 207Pb/235U (Pa-corrected)"){echo "selected";} ?>>weighted mean 207Pb/235U (Pa-corrected)</option>
								<option value="weighted mean 206Pb/238U (Th- and Pa-corrected)" <? if($preferredagetype5=="weighted mean 206Pb/238U (Th- and Pa-corrected)"){echo "selected";} ?>>weighted mean 206Pb/238U (Th- and Pa-corrected)</option>
								<option value="weighted mean 207Pb/206Pb (Th-corrected)" <? if($preferredagetype5=="weighted mean 207Pb/206Pb (Th-corrected)"){echo "selected";} ?>>weighted mean 207Pb/206Pb (Th-corrected)</option>
								<option value="weighted mean 207Pb/206Pb (Pa-corrected)" <? if($preferredagetype5=="weighted mean 207Pb/206Pb (Pa-corrected)"){echo "selected";} ?>>weighted mean 207Pb/206Pb (Pa-corrected)</option>
								<option value="weighted mean 207Pb/206Pb (Th- and Pa-corrected)" <? if($preferredagetype5=="weighted mean 207Pb/206Pb (Th- and Pa-corrected)"){echo "selected";} ?>>weighted mean 207Pb/206Pb (Th- and Pa-corrected)</option>
								<option value="minimum age" <? if($preferredagetype5=="minimum age"){echo "selected";} ?>>minimum age</option>
								<option value="maximum age" <? if($preferredagetype5=="maximum age"){echo "selected";} ?>>maximum age</option>
								<option value="upper intercept" <? if($preferredagetype5=="upper intercept"){echo "selected";} ?>>upper intercept</option>
								<option value="lower intercept" <? if($preferredagetype5=="lower intercept"){echo "selected";} ?>>lower intercept</option>
								<option value="238U-206Pb isochron" <? if($preferredagetype5=="238U-206Pb isochron"){echo "selected";} ?>>238U-206Pb isochron</option>
								<option value="235U-207Pb isochron" <? if($preferredagetype5=="235U-207Pb isochron"){echo "selected";} ?>>235U-207Pb isochron</option>
								<option value="232Th-208Pb isochron" <? if($preferredagetype5=="232Th-208Pb isochron"){echo "selected";} ?>>232Th-208Pb isochron</option>
								<option value="semi-total Pb isochron" <? if($preferredagetype5=="semi-total Pb isochron"){echo "selected";} ?>>semi-total Pb isochron</option>
								<option value="total Pb isochron" <? if($preferredagetype5=="total Pb isochron"){echo "selected";} ?>>total Pb isochron</option>
								<option value="none" <? if($preferredagetype5=="none"){echo "selected";} ?>>none</option>
								<option value="other" <? if($preferredagetype5=="other"){echo "selected";} ?>>other</option>
							</select>

							<span id="otherpatypediv5" style="color:red;display:none;">
							&nbsp;&nbsp;&nbsp;Please Provide:&nbsp;<input type="text" id="otherpatype5" name="otherpatype5" value="<?=$otherpatype5?>" >
							</span>
						
						</td>
					</tr>
					<tr><td>Age:</td><td><input type="text" name="preferredage5" value="<?=$preferredage5?>" ></td></tr>
					<tr><td>Age Error (Analytical):</td><td><input type="text" name="preferredageerror5" value="<?=$preferredageerror5?>" >&nbsp;&plusmn;2&sigma;(abs)</td></tr>
					<tr><td>MSWD:</td><td><input type="text" name="mswd5" value="<?=$mswd5?>" ></td></tr>
					<tr><td>Age Error (Systematic):</td><td><input type="text" name="ageerrorsystematic5" value="<?=$ageerrorsystematic5?>" >&nbsp;&plusmn;2&sigma;(abs)</td></tr>
					<tr><td>Included Analyses:</td><td><input type="text" name="preferredageincludedanalyses5" value="<?=$preferredageincludedanalyses5?>" ></td></tr>
					<tr><td>Age Comment:</td><td><input type="text" name="preferredageexplanation5" value="<?=$preferredageexplanation5?>" ></td></tr>
					<tr>
						<td>Common Lead Correction</td>
						<td>
						
							<select name="commonleadcorrection5">
								<option value="">select...</option>
								<option value="204" <? if($commonleadcorrection5=="204"){echo "selected";} ?>>204</option>
								<option value="207" <? if($commonleadcorrection5=="207"){echo "selected";} ?>>207</option>
								<option value="208" <? if($commonleadcorrection5=="208"){echo "selected";} ?>>208</option>
							</select>
						</td>
					</tr>
					<tr><td>&nbsp;</td></tr>
					</table>
				</td>
			</tr>


			<tr id="agerow6" style="display:none;">
				<td>
					<table style="font-size:10px;">
					<tr><td>Additional Analysis Purpose:<span style="color:red;">*</span></td>
						<td>
							<select name="analysispurpose6" id="analysispurpose6" onchange="avdiv6();">
								<option value="">select...</option>
								<option value="DetritalSpectrum" <? if($analysispurpose6=="DetritalSpectrum"){echo "selected";} ?>>DetritalSpectrum</option>
								<option value="SingleAge" <? if($analysispurpose6=="SingleAge"){echo "selected";} ?>>SingleAge</option>
								<option value="Cooling" <? if($analysispurpose6=="Cooling"){echo "selected";} ?>>Cooling</option>
								<option value="Crystallization" <? if($analysispurpose6=="Crystallization"){echo "selected";} ?>>Crystallization</option>
								<option value="Metamorphic" <? if($analysispurpose6=="Metamorphic"){echo "selected";} ?>>Metamorphic</option>
								<option value="Recrystallization" <? if($analysispurpose6=="Recrystallization"){echo "selected";} ?>>Recrystallization</option>
								<option value="Inheritence" <? if($analysispurpose6=="Inheritence"){echo "selected";} ?>>Inheritence</option>
								<option value="Shock" <? if($analysispurpose6=="Shock"){echo "selected";} ?>>Shock</option>
								<option value="Hydrothermal" <? if($analysispurpose6=="Hydrothermal"){echo "selected";} ?>>Hydrothermal</option>
								<option value="Diagenetic" <? if($analysispurpose6=="Diagenetic"){echo "selected";} ?>>Diagenetic</option>
								<option value="TimeScaleCalibration" <? if($analysispurpose6=="TimeScaleCalibration"){echo "selected";} ?>>TimeScaleCalibration</option>
								<option value="other" <? if($mineral=="other"){echo "selected";} ?>>other</option>
							</select>
							
							<span id="otherapdiv6" style="color:red;display:none;">
							&nbsp;&nbsp;&nbsp;Please Provide:&nbsp;<input type="text" id="otherap6" name="otherap6" value="<?=$otherap6?>" >
							</span>
							
						</td>
					</tr>
					<tr>
						<td>Additional Age Type:</td>
						<td>
						
						
							<select name="preferredagetype6" id="preferredagetype6" onchange="patypediv6();">
								<option value="">select...</option>
								<option value="single analysis 206Pb/238U" <? if($preferredagetype6=="single analysis 206Pb/238U"){echo "selected";} ?>>single analysis 206Pb/238U</option>
								<option value="single analysis 207Pb/235U" <? if($preferredagetype6=="single analysis 207Pb/235U"){echo "selected";} ?>>single analysis 207Pb/235U</option>
								<option value="single analysis 207Pb/206Pb" <? if($preferredagetype6=="single analysis 207Pb/206Pb"){echo "selected";} ?>>single analysis 207Pb/206Pb</option>
								<option value="single analysis 208Pb/232Th" <? if($preferredagetype6=="single analysis 208Pb/232Th"){echo "selected";} ?>>single analysis 208Pb/232Th</option>
								<option value="weighted mean 207Pb/235U" <? if($preferredagetype6=="weighted mean 207Pb/235U"){echo "selected";} ?>>weighted mean 207Pb/235U</option>
								<option value="weighted mean 206Pb/238U" <? if($preferredagetype6=="weighted mean 206Pb/238U"){echo "selected";} ?>>weighted mean 206Pb/238U</option>
								<option value="weighted mean 207Pb/206Pb" <? if($preferredagetype6=="weighted mean 207Pb/206Pb"){echo "selected";} ?>>weighted mean 207Pb/206Pb</option>
								<option value="weighted mean 208Pb/232Th" <? if($preferredagetype6=="weighted mean 208Pb/232Th"){echo "selected";} ?>>weighted mean 208Pb/232Th</option>
								<option value="weighted mean 207Pb/235U (Pa-corrected)" <? if($preferredagetype6=="weighted mean 207Pb/235U (Pa-corrected)"){echo "selected";} ?>>weighted mean 207Pb/235U (Pa-corrected)</option>
								<option value="weighted mean 206Pb/238U (Th- and Pa-corrected)" <? if($preferredagetype6=="weighted mean 206Pb/238U (Th- and Pa-corrected)"){echo "selected";} ?>>weighted mean 206Pb/238U (Th- and Pa-corrected)</option>
								<option value="weighted mean 207Pb/206Pb (Th-corrected)" <? if($preferredagetype6=="weighted mean 207Pb/206Pb (Th-corrected)"){echo "selected";} ?>>weighted mean 207Pb/206Pb (Th-corrected)</option>
								<option value="weighted mean 207Pb/206Pb (Pa-corrected)" <? if($preferredagetype6=="weighted mean 207Pb/206Pb (Pa-corrected)"){echo "selected";} ?>>weighted mean 207Pb/206Pb (Pa-corrected)</option>
								<option value="weighted mean 207Pb/206Pb (Th- and Pa-corrected)" <? if($preferredagetype6=="weighted mean 207Pb/206Pb (Th- and Pa-corrected)"){echo "selected";} ?>>weighted mean 207Pb/206Pb (Th- and Pa-corrected)</option>
								<option value="minimum age" <? if($preferredagetype6=="minimum age"){echo "selected";} ?>>minimum age</option>
								<option value="maximum age" <? if($preferredagetype6=="maximum age"){echo "selected";} ?>>maximum age</option>
								<option value="upper intercept" <? if($preferredagetype6=="upper intercept"){echo "selected";} ?>>upper intercept</option>
								<option value="lower intercept" <? if($preferredagetype6=="lower intercept"){echo "selected";} ?>>lower intercept</option>
								<option value="238U-206Pb isochron" <? if($preferredagetype6=="238U-206Pb isochron"){echo "selected";} ?>>238U-206Pb isochron</option>
								<option value="235U-207Pb isochron" <? if($preferredagetype6=="235U-207Pb isochron"){echo "selected";} ?>>235U-207Pb isochron</option>
								<option value="232Th-208Pb isochron" <? if($preferredagetype6=="232Th-208Pb isochron"){echo "selected";} ?>>232Th-208Pb isochron</option>
								<option value="semi-total Pb isochron" <? if($preferredagetype6=="semi-total Pb isochron"){echo "selected";} ?>>semi-total Pb isochron</option>
								<option value="total Pb isochron" <? if($preferredagetype6=="total Pb isochron"){echo "selected";} ?>>total Pb isochron</option>
								<option value="none" <? if($preferredagetype6=="none"){echo "selected";} ?>>none</option>
								<option value="other" <? if($preferredagetype6=="other"){echo "selected";} ?>>other</option>
							</select>

							<span id="otherpatypediv6" style="color:red;display:none;">
							&nbsp;&nbsp;&nbsp;Please Provide:&nbsp;<input type="text" id="otherpatype6" name="otherpatype6" value="<?=$otherpatype6?>" >
							</span>
						
						</td>
					</tr>
					<tr><td>Age:</td><td><input type="text" name="preferredage6" value="<?=$preferredage6?>" ></td></tr>
					<tr><td>Age Error (Analytical):</td><td><input type="text" name="preferredageerror6" value="<?=$preferredageerror6?>" >&nbsp;&plusmn;2&sigma;(abs)</td></tr>
					<tr><td>MSWD:</td><td><input type="text" name="mswd6" value="<?=$mswd6?>" ></td></tr>
					<tr><td>Age Error (Systematic):</td><td><input type="text" name="ageerrorsystematic6" value="<?=$ageerrorsystematic6?>" >&nbsp;&plusmn;2&sigma;(abs)</td></tr>
					<tr><td>Included Analyses:</td><td><input type="text" name="preferredageincludedanalyses6" value="<?=$preferredageincludedanalyses6?>" ></td></tr>
					<tr><td>Age Comment:</td><td><input type="text" name="preferredageexplanation6" value="<?=$preferredageexplanation6?>" ></td></tr>
					<tr>
						<td>Common Lead Correction</td>
						<td>
						
							<select name="commonleadcorrection6">
								<option value="">select...</option>
								<option value="204" <? if($commonleadcorrection6=="204"){echo "selected";} ?>>204</option>
								<option value="207" <? if($commonleadcorrection6=="207"){echo "selected";} ?>>207</option>
								<option value="208" <? if($commonleadcorrection6=="208"){echo "selected";} ?>>208</option>
							</select>
						</td>
					</tr>
					<tr><td>&nbsp;</td></tr>
					</table>
				</td>
			</tr>


			<tr id="agerow7" style="display:none;">
				<td>
					<table style="font-size:10px;">
					<tr><td>Additional Analysis Purpose:<span style="color:red;">*</span></td>
						<td>
							<select name="analysispurpose7" id="analysispurpose7" onchange="avdiv7();">
								<option value="">select...</option>
								<option value="DetritalSpectrum" <? if($analysispurpose7=="DetritalSpectrum"){echo "selected";} ?>>DetritalSpectrum</option>
								<option value="SingleAge" <? if($analysispurpose7=="SingleAge"){echo "selected";} ?>>SingleAge</option>
								<option value="Cooling" <? if($analysispurpose7=="Cooling"){echo "selected";} ?>>Cooling</option>
								<option value="Crystallization" <? if($analysispurpose7=="Crystallization"){echo "selected";} ?>>Crystallization</option>
								<option value="Metamorphic" <? if($analysispurpose7=="Metamorphic"){echo "selected";} ?>>Metamorphic</option>
								<option value="Recrystallization" <? if($analysispurpose7=="Recrystallization"){echo "selected";} ?>>Recrystallization</option>
								<option value="Inheritence" <? if($analysispurpose7=="Inheritence"){echo "selected";} ?>>Inheritence</option>
								<option value="Shock" <? if($analysispurpose7=="Shock"){echo "selected";} ?>>Shock</option>
								<option value="Hydrothermal" <? if($analysispurpose7=="Hydrothermal"){echo "selected";} ?>>Hydrothermal</option>
								<option value="Diagenetic" <? if($analysispurpose7=="Diagenetic"){echo "selected";} ?>>Diagenetic</option>
								<option value="TimeScaleCalibration" <? if($analysispurpose7=="TimeScaleCalibration"){echo "selected";} ?>>TimeScaleCalibration</option>
								<option value="other" <? if($mineral=="other"){echo "selected";} ?>>other</option>
							</select>
							
							<span id="otherapdiv7" style="color:red;display:none;">
							&nbsp;&nbsp;&nbsp;Please Provide:&nbsp;<input type="text" id="otherap7" name="otherap7" value="<?=$otherap7?>" >
							</span>
							
						</td>
					</tr>
					<tr>
						<td>Additional Age Type:</td>
						<td>
						
						
							<select name="preferredagetype7" id="preferredagetype7" onchange="patypediv7();">
								<option value="">select...</option>
								<option value="single analysis 206Pb/238U" <? if($preferredagetype7=="single analysis 206Pb/238U"){echo "selected";} ?>>single analysis 206Pb/238U</option>
								<option value="single analysis 207Pb/235U" <? if($preferredagetype7=="single analysis 207Pb/235U"){echo "selected";} ?>>single analysis 207Pb/235U</option>
								<option value="single analysis 207Pb/206Pb" <? if($preferredagetype7=="single analysis 207Pb/206Pb"){echo "selected";} ?>>single analysis 207Pb/206Pb</option>
								<option value="single analysis 208Pb/232Th" <? if($preferredagetype7=="single analysis 208Pb/232Th"){echo "selected";} ?>>single analysis 208Pb/232Th</option>
								<option value="weighted mean 207Pb/235U" <? if($preferredagetype7=="weighted mean 207Pb/235U"){echo "selected";} ?>>weighted mean 207Pb/235U</option>
								<option value="weighted mean 206Pb/238U" <? if($preferredagetype7=="weighted mean 206Pb/238U"){echo "selected";} ?>>weighted mean 206Pb/238U</option>
								<option value="weighted mean 207Pb/206Pb" <? if($preferredagetype7=="weighted mean 207Pb/206Pb"){echo "selected";} ?>>weighted mean 207Pb/206Pb</option>
								<option value="weighted mean 208Pb/232Th" <? if($preferredagetype7=="weighted mean 208Pb/232Th"){echo "selected";} ?>>weighted mean 208Pb/232Th</option>
								<option value="weighted mean 207Pb/235U (Pa-corrected)" <? if($preferredagetype7=="weighted mean 207Pb/235U (Pa-corrected)"){echo "selected";} ?>>weighted mean 207Pb/235U (Pa-corrected)</option>
								<option value="weighted mean 206Pb/238U (Th- and Pa-corrected)" <? if($preferredagetype7=="weighted mean 206Pb/238U (Th- and Pa-corrected)"){echo "selected";} ?>>weighted mean 206Pb/238U (Th- and Pa-corrected)</option>
								<option value="weighted mean 207Pb/206Pb (Th-corrected)" <? if($preferredagetype7=="weighted mean 207Pb/206Pb (Th-corrected)"){echo "selected";} ?>>weighted mean 207Pb/206Pb (Th-corrected)</option>
								<option value="weighted mean 207Pb/206Pb (Pa-corrected)" <? if($preferredagetype7=="weighted mean 207Pb/206Pb (Pa-corrected)"){echo "selected";} ?>>weighted mean 207Pb/206Pb (Pa-corrected)</option>
								<option value="weighted mean 207Pb/206Pb (Th- and Pa-corrected)" <? if($preferredagetype7=="weighted mean 207Pb/206Pb (Th- and Pa-corrected)"){echo "selected";} ?>>weighted mean 207Pb/206Pb (Th- and Pa-corrected)</option>
								<option value="minimum age" <? if($preferredagetype7=="minimum age"){echo "selected";} ?>>minimum age</option>
								<option value="maximum age" <? if($preferredagetype7=="maximum age"){echo "selected";} ?>>maximum age</option>
								<option value="upper intercept" <? if($preferredagetype7=="upper intercept"){echo "selected";} ?>>upper intercept</option>
								<option value="lower intercept" <? if($preferredagetype7=="lower intercept"){echo "selected";} ?>>lower intercept</option>
								<option value="238U-206Pb isochron" <? if($preferredagetype7=="238U-206Pb isochron"){echo "selected";} ?>>238U-206Pb isochron</option>
								<option value="235U-207Pb isochron" <? if($preferredagetype7=="235U-207Pb isochron"){echo "selected";} ?>>235U-207Pb isochron</option>
								<option value="232Th-208Pb isochron" <? if($preferredagetype7=="232Th-208Pb isochron"){echo "selected";} ?>>232Th-208Pb isochron</option>
								<option value="semi-total Pb isochron" <? if($preferredagetype7=="semi-total Pb isochron"){echo "selected";} ?>>semi-total Pb isochron</option>
								<option value="total Pb isochron" <? if($preferredagetype7=="total Pb isochron"){echo "selected";} ?>>total Pb isochron</option>
								<option value="none" <? if($preferredagetype7=="none"){echo "selected";} ?>>none</option>
								<option value="other" <? if($preferredagetype7=="other"){echo "selected";} ?>>other</option>
							</select>

							<span id="otherpatypediv7" style="color:red;display:none;">
							&nbsp;&nbsp;&nbsp;Please Provide:&nbsp;<input type="text" id="otherpatype7" name="otherpatype7" value="<?=$otherpatype7?>" >
							</span>
						
						</td>
					</tr>
					<tr><td>Age:</td><td><input type="text" name="preferredage7" value="<?=$preferredage7?>" ></td></tr>
					<tr><td>Age Error (Analytical):</td><td><input type="text" name="preferredageerror7" value="<?=$preferredageerror7?>" >&nbsp;&plusmn;2&sigma;(abs)</td></tr>
					<tr><td>MSWD:</td><td><input type="text" name="mswd7" value="<?=$mswd7?>" ></td></tr>
					<tr><td>Age Error (Systematic):</td><td><input type="text" name="ageerrorsystematic7" value="<?=$ageerrorsystematic7?>" >&nbsp;&plusmn;2&sigma;(abs)</td></tr>
					<tr><td>Included Analyses:</td><td><input type="text" name="preferredageincludedanalyses7" value="<?=$preferredageincludedanalyses7?>" ></td></tr>
					<tr><td>Age Comment:</td><td><input type="text" name="preferredageexplanation7" value="<?=$preferredageexplanation7?>" ></td></tr>
					<tr>
						<td>Common Lead Correction</td>
						<td>
						
							<select name="commonleadcorrection7">
								<option value="">select...</option>
								<option value="204" <? if($commonleadcorrection7=="204"){echo "selected";} ?>>204</option>
								<option value="207" <? if($commonleadcorrection7=="207"){echo "selected";} ?>>207</option>
								<option value="208" <? if($commonleadcorrection7=="208"){echo "selected";} ?>>208</option>
							</select>
						</td>
					</tr>
					<tr><td>&nbsp;</td></tr>
					</table>
				</td>
			</tr>


			<tr id="agerow8" style="display:none;">
				<td>
					<table style="font-size:10px;">
					<tr><td>Additional Analysis Purpose:<span style="color:red;">*</span></td>
						<td>
							<select name="analysispurpose8" id="analysispurpose8" onchange="avdiv8();">
								<option value="">select...</option>
								<option value="DetritalSpectrum" <? if($analysispurpose8=="DetritalSpectrum"){echo "selected";} ?>>DetritalSpectrum</option>
								<option value="SingleAge" <? if($analysispurpose8=="SingleAge"){echo "selected";} ?>>SingleAge</option>
								<option value="Cooling" <? if($analysispurpose8=="Cooling"){echo "selected";} ?>>Cooling</option>
								<option value="Crystallization" <? if($analysispurpose8=="Crystallization"){echo "selected";} ?>>Crystallization</option>
								<option value="Metamorphic" <? if($analysispurpose8=="Metamorphic"){echo "selected";} ?>>Metamorphic</option>
								<option value="Recrystallization" <? if($analysispurpose8=="Recrystallization"){echo "selected";} ?>>Recrystallization</option>
								<option value="Inheritence" <? if($analysispurpose8=="Inheritence"){echo "selected";} ?>>Inheritence</option>
								<option value="Shock" <? if($analysispurpose8=="Shock"){echo "selected";} ?>>Shock</option>
								<option value="Hydrothermal" <? if($analysispurpose8=="Hydrothermal"){echo "selected";} ?>>Hydrothermal</option>
								<option value="Diagenetic" <? if($analysispurpose8=="Diagenetic"){echo "selected";} ?>>Diagenetic</option>
								<option value="TimeScaleCalibration" <? if($analysispurpose8=="TimeScaleCalibration"){echo "selected";} ?>>TimeScaleCalibration</option>
								<option value="other" <? if($mineral=="other"){echo "selected";} ?>>other</option>
							</select>
							
							<span id="otherapdiv8" style="color:red;display:none;">
							&nbsp;&nbsp;&nbsp;Please Provide:&nbsp;<input type="text" id="otherap8" name="otherap8" value="<?=$otherap8?>" >
							</span>
							
						</td>
					</tr>
					<tr>
						<td>Additional Age Type:</td>
						<td>
						
						
							<select name="preferredagetype8" id="preferredagetype8" onchange="patypediv8();">
								<option value="">select...</option>
								<option value="single analysis 206Pb/238U" <? if($preferredagetype8=="single analysis 206Pb/238U"){echo "selected";} ?>>single analysis 206Pb/238U</option>
								<option value="single analysis 207Pb/235U" <? if($preferredagetype8=="single analysis 207Pb/235U"){echo "selected";} ?>>single analysis 207Pb/235U</option>
								<option value="single analysis 207Pb/206Pb" <? if($preferredagetype8=="single analysis 207Pb/206Pb"){echo "selected";} ?>>single analysis 207Pb/206Pb</option>
								<option value="single analysis 208Pb/232Th" <? if($preferredagetype8=="single analysis 208Pb/232Th"){echo "selected";} ?>>single analysis 208Pb/232Th</option>
								<option value="weighted mean 207Pb/235U" <? if($preferredagetype8=="weighted mean 207Pb/235U"){echo "selected";} ?>>weighted mean 207Pb/235U</option>
								<option value="weighted mean 206Pb/238U" <? if($preferredagetype8=="weighted mean 206Pb/238U"){echo "selected";} ?>>weighted mean 206Pb/238U</option>
								<option value="weighted mean 207Pb/206Pb" <? if($preferredagetype8=="weighted mean 207Pb/206Pb"){echo "selected";} ?>>weighted mean 207Pb/206Pb</option>
								<option value="weighted mean 208Pb/232Th" <? if($preferredagetype8=="weighted mean 208Pb/232Th"){echo "selected";} ?>>weighted mean 208Pb/232Th</option>
								<option value="weighted mean 207Pb/235U (Pa-corrected)" <? if($preferredagetype8=="weighted mean 207Pb/235U (Pa-corrected)"){echo "selected";} ?>>weighted mean 207Pb/235U (Pa-corrected)</option>
								<option value="weighted mean 206Pb/238U (Th- and Pa-corrected)" <? if($preferredagetype8=="weighted mean 206Pb/238U (Th- and Pa-corrected)"){echo "selected";} ?>>weighted mean 206Pb/238U (Th- and Pa-corrected)</option>
								<option value="weighted mean 207Pb/206Pb (Th-corrected)" <? if($preferredagetype8=="weighted mean 207Pb/206Pb (Th-corrected)"){echo "selected";} ?>>weighted mean 207Pb/206Pb (Th-corrected)</option>
								<option value="weighted mean 207Pb/206Pb (Pa-corrected)" <? if($preferredagetype8=="weighted mean 207Pb/206Pb (Pa-corrected)"){echo "selected";} ?>>weighted mean 207Pb/206Pb (Pa-corrected)</option>
								<option value="weighted mean 207Pb/206Pb (Th- and Pa-corrected)" <? if($preferredagetype8=="weighted mean 207Pb/206Pb (Th- and Pa-corrected)"){echo "selected";} ?>>weighted mean 207Pb/206Pb (Th- and Pa-corrected)</option>
								<option value="minimum age" <? if($preferredagetype8=="minimum age"){echo "selected";} ?>>minimum age</option>
								<option value="maximum age" <? if($preferredagetype8=="maximum age"){echo "selected";} ?>>maximum age</option>
								<option value="upper intercept" <? if($preferredagetype8=="upper intercept"){echo "selected";} ?>>upper intercept</option>
								<option value="lower intercept" <? if($preferredagetype8=="lower intercept"){echo "selected";} ?>>lower intercept</option>
								<option value="238U-206Pb isochron" <? if($preferredagetype8=="238U-206Pb isochron"){echo "selected";} ?>>238U-206Pb isochron</option>
								<option value="235U-207Pb isochron" <? if($preferredagetype8=="235U-207Pb isochron"){echo "selected";} ?>>235U-207Pb isochron</option>
								<option value="232Th-208Pb isochron" <? if($preferredagetype8=="232Th-208Pb isochron"){echo "selected";} ?>>232Th-208Pb isochron</option>
								<option value="semi-total Pb isochron" <? if($preferredagetype8=="semi-total Pb isochron"){echo "selected";} ?>>semi-total Pb isochron</option>
								<option value="total Pb isochron" <? if($preferredagetype8=="total Pb isochron"){echo "selected";} ?>>total Pb isochron</option>
								<option value="none" <? if($preferredagetype8=="none"){echo "selected";} ?>>none</option>
								<option value="other" <? if($preferredagetype8=="other"){echo "selected";} ?>>other</option>
							</select>

							<span id="otherpatypediv8" style="color:red;display:none;">
							&nbsp;&nbsp;&nbsp;Please Provide:&nbsp;<input type="text" id="otherpatype8" name="otherpatype8" value="<?=$otherpatype8?>" >
							</span>
						
						</td>
					</tr>
					<tr><td>Age:</td><td><input type="text" name="preferredage8" value="<?=$preferredage8?>" ></td></tr>
					<tr><td>Age Error (Analytical):</td><td><input type="text" name="preferredageerror8" value="<?=$preferredageerror8?>" >&nbsp;&plusmn;2&sigma;(abs)</td></tr>
					<tr><td>MSWD:</td><td><input type="text" name="mswd8" value="<?=$mswd8?>" ></td></tr>
					<tr><td>Age Error (Systematic):</td><td><input type="text" name="ageerrorsystematic8" value="<?=$ageerrorsystematic8?>" >&nbsp;&plusmn;2&sigma;(abs)</td></tr>
					<tr><td>Included Analyses:</td><td><input type="text" name="preferredageincludedanalyses8" value="<?=$preferredageincludedanalyses8?>" ></td></tr>
					<tr><td>Age Comment:</td><td><input type="text" name="preferredageexplanation8" value="<?=$preferredageexplanation8?>" ></td></tr>
					<tr>
						<td>Common Lead Correction</td>
						<td>
						
							<select name="commonleadcorrection8">
								<option value="">select...</option>
								<option value="204" <? if($commonleadcorrection8=="204"){echo "selected";} ?>>204</option>
								<option value="207" <? if($commonleadcorrection8=="207"){echo "selected";} ?>>207</option>
								<option value="208" <? if($commonleadcorrection8=="208"){echo "selected";} ?>>208</option>
							</select>
						</td>
					</tr>
					<tr><td>&nbsp;</td></tr>
					</table>
				</td>
			</tr>


			<tr id="agerow9" style="display:none;">
				<td>
					<table style="font-size:10px;">
					<tr><td>Additional Analysis Purpose:<span style="color:red;">*</span></td>
						<td>
							<select name="analysispurpose9" id="analysispurpose9" onchange="avdiv9();">
								<option value="">select...</option>
								<option value="DetritalSpectrum" <? if($analysispurpose9=="DetritalSpectrum"){echo "selected";} ?>>DetritalSpectrum</option>
								<option value="SingleAge" <? if($analysispurpose9=="SingleAge"){echo "selected";} ?>>SingleAge</option>
								<option value="Cooling" <? if($analysispurpose9=="Cooling"){echo "selected";} ?>>Cooling</option>
								<option value="Crystallization" <? if($analysispurpose9=="Crystallization"){echo "selected";} ?>>Crystallization</option>
								<option value="Metamorphic" <? if($analysispurpose9=="Metamorphic"){echo "selected";} ?>>Metamorphic</option>
								<option value="Recrystallization" <? if($analysispurpose9=="Recrystallization"){echo "selected";} ?>>Recrystallization</option>
								<option value="Inheritence" <? if($analysispurpose9=="Inheritence"){echo "selected";} ?>>Inheritence</option>
								<option value="Shock" <? if($analysispurpose9=="Shock"){echo "selected";} ?>>Shock</option>
								<option value="Hydrothermal" <? if($analysispurpose9=="Hydrothermal"){echo "selected";} ?>>Hydrothermal</option>
								<option value="Diagenetic" <? if($analysispurpose9=="Diagenetic"){echo "selected";} ?>>Diagenetic</option>
								<option value="TimeScaleCalibration" <? if($analysispurpose9=="TimeScaleCalibration"){echo "selected";} ?>>TimeScaleCalibration</option>
								<option value="other" <? if($mineral=="other"){echo "selected";} ?>>other</option>
							</select>
							
							<span id="otherapdiv9" style="color:red;display:none;">
							&nbsp;&nbsp;&nbsp;Please Provide:&nbsp;<input type="text" id="otherap9" name="otherap9" value="<?=$otherap9?>" >
							</span>
							
						</td>
					</tr>
					<tr>
						<td>Additional Age Type:</td>
						<td>
						
						
							<select name="preferredagetype9" id="preferredagetype9" onchange="patypediv9();">
								<option value="">select...</option>
								<option value="single analysis 206Pb/238U" <? if($preferredagetype9=="single analysis 206Pb/238U"){echo "selected";} ?>>single analysis 206Pb/238U</option>
								<option value="single analysis 207Pb/235U" <? if($preferredagetype9=="single analysis 207Pb/235U"){echo "selected";} ?>>single analysis 207Pb/235U</option>
								<option value="single analysis 207Pb/206Pb" <? if($preferredagetype9=="single analysis 207Pb/206Pb"){echo "selected";} ?>>single analysis 207Pb/206Pb</option>
								<option value="single analysis 208Pb/232Th" <? if($preferredagetype9=="single analysis 208Pb/232Th"){echo "selected";} ?>>single analysis 208Pb/232Th</option>
								<option value="weighted mean 207Pb/235U" <? if($preferredagetype9=="weighted mean 207Pb/235U"){echo "selected";} ?>>weighted mean 207Pb/235U</option>
								<option value="weighted mean 206Pb/238U" <? if($preferredagetype9=="weighted mean 206Pb/238U"){echo "selected";} ?>>weighted mean 206Pb/238U</option>
								<option value="weighted mean 207Pb/206Pb" <? if($preferredagetype9=="weighted mean 207Pb/206Pb"){echo "selected";} ?>>weighted mean 207Pb/206Pb</option>
								<option value="weighted mean 208Pb/232Th" <? if($preferredagetype9=="weighted mean 208Pb/232Th"){echo "selected";} ?>>weighted mean 208Pb/232Th</option>
								<option value="weighted mean 207Pb/235U (Pa-corrected)" <? if($preferredagetype9=="weighted mean 207Pb/235U (Pa-corrected)"){echo "selected";} ?>>weighted mean 207Pb/235U (Pa-corrected)</option>
								<option value="weighted mean 206Pb/238U (Th- and Pa-corrected)" <? if($preferredagetype9=="weighted mean 206Pb/238U (Th- and Pa-corrected)"){echo "selected";} ?>>weighted mean 206Pb/238U (Th- and Pa-corrected)</option>
								<option value="weighted mean 207Pb/206Pb (Th-corrected)" <? if($preferredagetype9=="weighted mean 207Pb/206Pb (Th-corrected)"){echo "selected";} ?>>weighted mean 207Pb/206Pb (Th-corrected)</option>
								<option value="weighted mean 207Pb/206Pb (Pa-corrected)" <? if($preferredagetype9=="weighted mean 207Pb/206Pb (Pa-corrected)"){echo "selected";} ?>>weighted mean 207Pb/206Pb (Pa-corrected)</option>
								<option value="weighted mean 207Pb/206Pb (Th- and Pa-corrected)" <? if($preferredagetype9=="weighted mean 207Pb/206Pb (Th- and Pa-corrected)"){echo "selected";} ?>>weighted mean 207Pb/206Pb (Th- and Pa-corrected)</option>
								<option value="minimum age" <? if($preferredagetype9=="minimum age"){echo "selected";} ?>>minimum age</option>
								<option value="maximum age" <? if($preferredagetype9=="maximum age"){echo "selected";} ?>>maximum age</option>
								<option value="upper intercept" <? if($preferredagetype9=="upper intercept"){echo "selected";} ?>>upper intercept</option>
								<option value="lower intercept" <? if($preferredagetype9=="lower intercept"){echo "selected";} ?>>lower intercept</option>
								<option value="238U-206Pb isochron" <? if($preferredagetype9=="238U-206Pb isochron"){echo "selected";} ?>>238U-206Pb isochron</option>
								<option value="235U-207Pb isochron" <? if($preferredagetype9=="235U-207Pb isochron"){echo "selected";} ?>>235U-207Pb isochron</option>
								<option value="232Th-208Pb isochron" <? if($preferredagetype9=="232Th-208Pb isochron"){echo "selected";} ?>>232Th-208Pb isochron</option>
								<option value="semi-total Pb isochron" <? if($preferredagetype9=="semi-total Pb isochron"){echo "selected";} ?>>semi-total Pb isochron</option>
								<option value="total Pb isochron" <? if($preferredagetype9=="total Pb isochron"){echo "selected";} ?>>total Pb isochron</option>
								<option value="none" <? if($preferredagetype9=="none"){echo "selected";} ?>>none</option>
								<option value="other" <? if($preferredagetype9=="other"){echo "selected";} ?>>other</option>
							</select>

							<span id="otherpatypediv9" style="color:red;display:none;">
							&nbsp;&nbsp;&nbsp;Please Provide:&nbsp;<input type="text" id="otherpatype9" name="otherpatype9" value="<?=$otherpatype9?>" >
							</span>
						
						</td>
					</tr>
					<tr><td>Age:</td><td><input type="text" name="preferredage9" value="<?=$preferredage9?>" ></td></tr>
					<tr><td>Age Error (Analytical):</td><td><input type="text" name="preferredageerror9" value="<?=$preferredageerror9?>" >&nbsp;&plusmn;2&sigma;(abs)</td></tr>
					<tr><td>MSWD:</td><td><input type="text" name="mswd9" value="<?=$mswd9?>" ></td></tr>
					<tr><td>Age Error (Systematic):</td><td><input type="text" name="ageerrorsystematic9" value="<?=$ageerrorsystematic9?>" >&nbsp;&plusmn;2&sigma;(abs)</td></tr>
					<tr><td>Included Analyses:</td><td><input type="text" name="preferredageincludedanalyses9" value="<?=$preferredageincludedanalyses9?>" ></td></tr>
					<tr><td>Age Comment:</td><td><input type="text" name="preferredageexplanation9" value="<?=$preferredageexplanation9?>" ></td></tr>
					<tr>
						<td>Common Lead Correction</td>
						<td>
						
							<select name="commonleadcorrection9">
								<option value="">select...</option>
								<option value="204" <? if($commonleadcorrection9=="204"){echo "selected";} ?>>204</option>
								<option value="207" <? if($commonleadcorrection9=="207"){echo "selected";} ?>>207</option>
								<option value="208" <? if($commonleadcorrection9=="208"){echo "selected";} ?>>208</option>
							</select>
						</td>
					</tr>
					<tr><td>&nbsp;</td></tr>
					</table>
				</td>
			</tr>


			<tr id="agerow10" style="display:none;">
				<td>
					<table style="font-size:10px;">
					<tr><td>Additional Analysis Purpose:<span style="color:red;">*</span></td>
						<td>
							<select name="analysispurpose10" id="analysispurpose10" onchange="avdiv10();">
								<option value="">select...</option>
								<option value="DetritalSpectrum" <? if($analysispurpose10=="DetritalSpectrum"){echo "selected";} ?>>DetritalSpectrum</option>
								<option value="SingleAge" <? if($analysispurpose10=="SingleAge"){echo "selected";} ?>>SingleAge</option>
								<option value="Cooling" <? if($analysispurpose10=="Cooling"){echo "selected";} ?>>Cooling</option>
								<option value="Crystallization" <? if($analysispurpose10=="Crystallization"){echo "selected";} ?>>Crystallization</option>
								<option value="Metamorphic" <? if($analysispurpose10=="Metamorphic"){echo "selected";} ?>>Metamorphic</option>
								<option value="Recrystallization" <? if($analysispurpose10=="Recrystallization"){echo "selected";} ?>>Recrystallization</option>
								<option value="Inheritence" <? if($analysispurpose10=="Inheritence"){echo "selected";} ?>>Inheritence</option>
								<option value="Shock" <? if($analysispurpose10=="Shock"){echo "selected";} ?>>Shock</option>
								<option value="Hydrothermal" <? if($analysispurpose10=="Hydrothermal"){echo "selected";} ?>>Hydrothermal</option>
								<option value="Diagenetic" <? if($analysispurpose10=="Diagenetic"){echo "selected";} ?>>Diagenetic</option>
								<option value="TimeScaleCalibration" <? if($analysispurpose10=="TimeScaleCalibration"){echo "selected";} ?>>TimeScaleCalibration</option>
								<option value="other" <? if($mineral=="other"){echo "selected";} ?>>other</option>
							</select>
							
							<span id="otherapdiv10" style="color:red;display:none;">
							&nbsp;&nbsp;&nbsp;Please Provide:&nbsp;<input type="text" id="otherap10" name="otherap10" value="<?=$otherap10?>" >
							</span>
							
						</td>
					</tr>
					<tr>
						<td>Additional Age Type:</td>
						<td>
						
						
							<select name="preferredagetype10" id="preferredagetype10" onchange="patypediv10();">
								<option value="">select...</option>
								<option value="single analysis 206Pb/238U" <? if($preferredagetype10=="single analysis 206Pb/238U"){echo "selected";} ?>>single analysis 206Pb/238U</option>
								<option value="single analysis 207Pb/235U" <? if($preferredagetype10=="single analysis 207Pb/235U"){echo "selected";} ?>>single analysis 207Pb/235U</option>
								<option value="single analysis 207Pb/206Pb" <? if($preferredagetype10=="single analysis 207Pb/206Pb"){echo "selected";} ?>>single analysis 207Pb/206Pb</option>
								<option value="single analysis 208Pb/232Th" <? if($preferredagetype10=="single analysis 208Pb/232Th"){echo "selected";} ?>>single analysis 208Pb/232Th</option>
								<option value="weighted mean 207Pb/235U" <? if($preferredagetype10=="weighted mean 207Pb/235U"){echo "selected";} ?>>weighted mean 207Pb/235U</option>
								<option value="weighted mean 206Pb/238U" <? if($preferredagetype10=="weighted mean 206Pb/238U"){echo "selected";} ?>>weighted mean 206Pb/238U</option>
								<option value="weighted mean 207Pb/206Pb" <? if($preferredagetype10=="weighted mean 207Pb/206Pb"){echo "selected";} ?>>weighted mean 207Pb/206Pb</option>
								<option value="weighted mean 208Pb/232Th" <? if($preferredagetype10=="weighted mean 208Pb/232Th"){echo "selected";} ?>>weighted mean 208Pb/232Th</option>
								<option value="weighted mean 207Pb/235U (Pa-corrected)" <? if($preferredagetype10=="weighted mean 207Pb/235U (Pa-corrected)"){echo "selected";} ?>>weighted mean 207Pb/235U (Pa-corrected)</option>
								<option value="weighted mean 206Pb/238U (Th- and Pa-corrected)" <? if($preferredagetype10=="weighted mean 206Pb/238U (Th- and Pa-corrected)"){echo "selected";} ?>>weighted mean 206Pb/238U (Th- and Pa-corrected)</option>
								<option value="weighted mean 207Pb/206Pb (Th-corrected)" <? if($preferredagetype10=="weighted mean 207Pb/206Pb (Th-corrected)"){echo "selected";} ?>>weighted mean 207Pb/206Pb (Th-corrected)</option>
								<option value="weighted mean 207Pb/206Pb (Pa-corrected)" <? if($preferredagetype10=="weighted mean 207Pb/206Pb (Pa-corrected)"){echo "selected";} ?>>weighted mean 207Pb/206Pb (Pa-corrected)</option>
								<option value="weighted mean 207Pb/206Pb (Th- and Pa-corrected)" <? if($preferredagetype10=="weighted mean 207Pb/206Pb (Th- and Pa-corrected)"){echo "selected";} ?>>weighted mean 207Pb/206Pb (Th- and Pa-corrected)</option>
								<option value="minimum age" <? if($preferredagetype10=="minimum age"){echo "selected";} ?>>minimum age</option>
								<option value="maximum age" <? if($preferredagetype10=="maximum age"){echo "selected";} ?>>maximum age</option>
								<option value="upper intercept" <? if($preferredagetype10=="upper intercept"){echo "selected";} ?>>upper intercept</option>
								<option value="lower intercept" <? if($preferredagetype10=="lower intercept"){echo "selected";} ?>>lower intercept</option>
								<option value="238U-206Pb isochron" <? if($preferredagetype10=="238U-206Pb isochron"){echo "selected";} ?>>238U-206Pb isochron</option>
								<option value="235U-207Pb isochron" <? if($preferredagetype10=="235U-207Pb isochron"){echo "selected";} ?>>235U-207Pb isochron</option>
								<option value="232Th-208Pb isochron" <? if($preferredagetype10=="232Th-208Pb isochron"){echo "selected";} ?>>232Th-208Pb isochron</option>
								<option value="semi-total Pb isochron" <? if($preferredagetype10=="semi-total Pb isochron"){echo "selected";} ?>>semi-total Pb isochron</option>
								<option value="total Pb isochron" <? if($preferredagetype10=="total Pb isochron"){echo "selected";} ?>>total Pb isochron</option>
								<option value="none" <? if($preferredagetype10=="none"){echo "selected";} ?>>none</option>
								<option value="other" <? if($preferredagetype10=="other"){echo "selected";} ?>>other</option>
							</select>

							<span id="otherpatypediv10" style="color:red;display:none;">
							&nbsp;&nbsp;&nbsp;Please Provide:&nbsp;<input type="text" id="otherpatype10" name="otherpatype10" value="<?=$otherpatype10?>" >
							</span>
						
						</td>
					</tr>
					<tr><td>Age:</td><td><input type="text" name="preferredage10" value="<?=$preferredage10?>" ></td></tr>
					<tr><td>Age Error (Analytical):</td><td><input type="text" name="preferredageerror10" value="<?=$preferredageerror10?>" >&nbsp;&plusmn;2&sigma;(abs)</td></tr>
					<tr><td>MSWD:</td><td><input type="text" name="mswd10" value="<?=$mswd10?>" ></td></tr>
					<tr><td>Age Error (Systematic):</td><td><input type="text" name="ageerrorsystematic10" value="<?=$ageerrorsystematic10?>" >&nbsp;&plusmn;2&sigma;(abs)</td></tr>
					<tr><td>Included Analyses:</td><td><input type="text" name="preferredageincludedanalyses10" value="<?=$preferredageincludedanalyses10?>" ></td></tr>
					<tr><td>Age Comment:</td><td><input type="text" name="preferredageexplanation10" value="<?=$preferredageexplanation10?>" ></td></tr>
					<tr>
						<td>Common Lead Correction</td>
						<td>
						
							<select name="commonleadcorrection10">
								<option value="">select...</option>
								<option value="204" <? if($commonleadcorrection10=="204"){echo "selected";} ?>>204</option>
								<option value="207" <? if($commonleadcorrection10=="207"){echo "selected";} ?>>207</option>
								<option value="208" <? if($commonleadcorrection10=="208"){echo "selected";} ?>>208</option>
							</select>
						</td>
					</tr>
					<tr><td>&nbsp;</td></tr>
					</table>
				</td>
			</tr>























			<tr>
				<td valign="middle" ><br><img src="addagetype.png" onclick="addage();"></td>
			</tr>
	
	
	
		</table>

</div>

<!--- ***************************end html metadata and age********************************** --->



<div style="padding-left:15px;display:<?=$separatefileshow?>;" id="separatefileinput">

<br>

		<?
		if($inputtype=="separatefile"){
			echo $error;
		}
		?>

			<h1>Sample Metadata/Age File:</h1><br>
			Sample Metadata/Age (.xls) File: <input type="file" name="separatefile" size="40" ><br>
			<br>
			<a href="U-Pb_Age_and_Metadata_Template.xls">Click here to download template.</a>

</div>










</td>
<td>&nbsp;&nbsp;&nbsp;</td>	
<td valign="top">










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




			Overwrite Existing Sample?
			<select name="overwrite">
				<option value="no" <? if($overwrite=="no"){echo "selected";} ?>>no</option>
				<option value="yes" <? if($overwrite=="yes"){echo "selected";} ?>>yes</option>
			</select>
			
			<br>
	
			Make Sample Public?
			<select name="public">
				<option value="1" <? if($public=="1"){echo "selected";} ?>>yes</option>
				<option value="0" <? if($public=="0"){echo "selected";} ?>>no</option>
			</select>
		<br>
		<hr>
		<br>
	
		<input type="submit" name="submit" value="Submit">
		
	
		<input type="hidden" name="filename">
		<input type="hidden" name="f" value="<?=$f?>">
		<input type="hidden" name="l" value="<?=$l?>">
		<input type="hidden" name="n" value="<?=$n?>">
		<input type="hidden" name="s" value="<?=$s?>">
		<input type="hidden" name="sample_pkey" value="<?=$sample_pkey?>">

	</form>




		</td>
	</tr>
</table>





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

}//end if samplename is not blank




//the following is reached initially, to get a .zip file from the user

	if($error!=""){
	
		$error="<h2><font color=\"red\">Error!</font></h2><font color=\"red\">$error<br>Please try again.</font><br>";
	
	}
	
	
	include("includes/geochron-secondary-header.htm");
	
	
	?>
	
	<script type="text/javascript">
	function addage(){
		for (i=1;i<11;i++){
			var thsObj =  document.getElementById("agerow"+i);
			if(thsObj.style.display == 'none') {
				thsObj.style.display = 'table-row';
				break;
			}
		}
	}
	
	function formvalidate(){
		//alert('hey');
		var errors='';
		if(document.forms["uploadform"]["zipsfile"].value=="" || document.forms["uploadform"]["zipsfile"].value==null){errors=errors+'ZIPS file must be provided.\n';}
		
		if(errors!="" && errors!=null){
			alert(errors);
			return false;
		}
	}
	
	
	</script>
	
	<h1>Upload ZIPS Ion Microprobe Data</h1><br>
	
	Please upload your ZIPS file (file must be in .zip format):<br><br>
	
	<div style="padding-left:20px;padding-top:20px;">
	
		<?=$error?>
	

	
	<form name="uploadform" method="POST" onsubmit="return formvalidate();" enctype="multipart/form-data">
		
		<table style="font-size:10px;">
			<tr>
				<td colspan="2"><h1>Sample File</h2></td>
			</tr>
			<tr>
				<td>ZIPS File:</td><td><input type="file" name="zipsfile" accept=".zip" size="40" ></td>
			</tr>
		</table>
	


		<br>
	
		<input type="submit" name="submitfile" value="Submit">
		
	
		<input type="hidden" name="filename">
	</form>
	
	</div>
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	<br><br><br><br><br>
	<br><br><br><br><br>
	<br><br><br><br><br>
	<br><br><br><br><br>
	<br><br><br><br><br>
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	

	
	
	
	
	<?

	include("includes/geochron-secondary-footer.htm");





































/*
for($x=1;$x<=10;$x++){










echo"		if(\$age$x!=\"\"){
			
			\$sampleagepkey=$db->get_var(\"select nextval('sample_age_seq')\");
		
			
		
			\$db->query(\"insert into sample_age (
				sample_age_pkey,
				sample_pkey,
				age_name,
				age_value,
				uncertainty_type,
				one_sigma,
				mswd,
				preferred
			) values (
				\$sampleagepkey,
				\$sample_pkey,
				'\$agetype$x',
				\$age$x,
				'ABS',
				\$ageerror$x,
				000,
				'1'
			)
			\");
			
		}

";










}

























exit();

















for($x=1;$x<11;$x++){

echo "		<tr id=\"agerow$x\" style=\"display:none;\">
			<td>Age:<input type=\"text\" name=\"age$x\" value=\"<?=\$age$x?>\"></td>
			<td>&nbsp;&nbsp;&nbsp;Age Error:<input type=\"text\" name=\"ageerror$x\" value=\"<?=\$ageerror$x?>\"></td>
			<td>
				&nbsp;&nbsp;&nbsp;Age Type:
				<select name=\"agetype$x\">
					<option value=\"\">select...</option>
					<option value=\"Crystallization Age\" <? if(\$agetype$x==\"Crystallization Age\"){echo \"selected\";} ?>>Crystallization Age</option>
 					<option value=\"Metamorphic\" <? if(\$agetype$x==\"Metamorphic\"){echo \"selected\";} ?>>Metamorphic</option>
 					<option value=\"Recrystallization\" <? if(\$agetype$x==\"Recrystallization\"){echo \"selected\";} ?>>Recrystallization</option>
 					<option value=\"Inheritence Ages\" <? if(\$agetype$x==\"Inheritence Ages\"){echo \"selected\";} ?>>Inheritence Ages</option>
 					<option value=\"Detrital Spectrum\" <? if(\$agetype$x==\"Detrital Spectrum\"){echo \"selected\";} ?>>Detrital Spectrum</option>
 					<option value=\"Shock Age\" <? if(\$agetype$x==\"Shock Age\"){echo \"selected\";} ?>>Shock Age</option>
 					<option value=\"Hydrothermal\" <? if(\$agetype$x==\"Hydrothermal\"){echo \"selected\";} ?>>Hydrothermal</option>
 					<option value=\"Diagenetic\" <? if(\$agetype$x==\"Diagenetic\"){echo \"selected\";} ?>>Diagenetic</option>
			</td>
		</tr>
";

}


*/

/*
samplename
uniqueid
analystname
labname
mineral
age1
ageerror1
agetype1
*/


?>