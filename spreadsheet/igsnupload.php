<?php

session_start();

include("db.php");

//var_dump($_SESSION);
//exit();

include("logincheck.php");


$userpkey=$_SESSION['userpkey'];


if($_POST['submit']==""){

include("includes/geochron-secondary-header.htm");
?>

<style type="text/css">
table.igsnsample {
	border-width: 1px;
	border-spacing: 0px;
	border-style: none;
	border-color: gray;
	border-collapse: collapse;
	background-color: white;
}
table.igsnsample th {
	border-width: 1px;
	padding: 3px;
	border-style: inset;
	border-color: #333333;
	background-color: #CA012D;
	color: #FFFFFF;
	-moz-border-radius: 0px 0px 0px 0px;
}
table.igsnsample td {
	border-width: 1px;
	padding: 3px;
	border-style: inset;
	border-color: #dddddd;
	background-color: white;
	-moz-border-radius: 0px 0px 0px 0px;
}
</style>

Blank Geochron IGSN Template: <a href="SESAR_Template.xls">HERE</a><br><br>

<form name="myform" method="POST" enctype="multipart/form-data">
SESAR Username: <input type="text" name="username"><br><br>
SESAR Password: <input type="password" name="password"><br><br>
File: <input type="file" name="file" id="file" /> <br><br>
<input type="submit" name="submit" value="Submit">
<img src="loading2.gif" alt border="0" name="loading"
style="visibility:hidden;">

</form>


<?

$logrows=$db->get_results("select 
							to_char(uploaddate, 'MM-DD-YYYY  HH:MI:SS AM') as mydate,
							numsamps,
							xlscode
							from igsnlog where userpkey=$userpkey order by pkey desc");

if(count($logrows)>0){

?>



<br><br><br><br>
<div style="font-weight:bold;">Past IGSN Creation:</div>
<table class="igsnsample">
	<tr>
		<th>Created On:</th>
		<th>Number of Samples:</th>
		<th>&nbsp;</th>
	</tr>

<?
foreach($logrows as $logrow){
?>

	<tr>
		<td><?=$logrow->mydate?></td>
		<td><?=$logrow->numsamps?></td>
		<td><a href="excelfiles/<?=$logrow->xlscode?>">download</a></td>
	</tr>

<?
} //end foreach logrows
?>

</table>

<?

}//end if count logrows > 0


include("includes/geochron-secondary-footer.htm");

exit();
}

if($_FILES["file"]["name"]==""){
	header("Location: igsnupload.php");
	exit();
}

//get file stuff here
  //echo "Upload: " . $_FILES["file"]["name"] . "<br />";
  //echo "Type: " . $_FILES["file"]["type"] . "<br />";
  //echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
  //echo "Stored in: " . $_FILES["file"]["tmp_name"];
  //exit();



include("includes/geochron-igsn-header.php");





flush();


$urlbase="http://www.geosamples.org";
//$urlbase="http://matisse.kgs.ku.edu/sesarsvn/sesar/sesar";


$xlsxml.="<rows>\n";
/*
$xlsxml.="<row>\n";

$xlsxml="";
$columnnames=array("IGSN","Parent IGSN","Sample ID","Sample Description","Sample Comment","GeoObject Type","GeoObject Class","Collection Method","Collection Method Desc.","Size","Min Age","Max Age","Material","Material Class","Start Longitude","Start Latitude","Start Geodetic Datum","End Longitude","End Latitude","End Geodetic Datum","Start Elevation","End Elevation","Primary Location Name","Primary Location Type","Location Desc.","Country","Province","County","City or Township","Min. Depth","Max. Depth","Vertical Datum","Field Description","Platform","Platform ID","Platform Desc.","Collector","Start Date","End Date","Orig. Archive Inst.","Orig. Archive Inst. Contact","Most Recent Archival Inst.","Most Recent Archival Contact");
$colnum=0;
foreach($columnnames as $columnname){
	$xlsxml.="<column>".$columname."</column>\n";
}
*/



//$username="jasonash@ku.edu";
//$password="chicago";

$username=$_POST['username'];
$password=$_POST['password'];

//Check Authentication
$postvars="username=$username&password=$password";

//echo "$urlbase/uploadservice.php\n\n";

$curl_handle=curl_init();
curl_setopt($curl_handle, CURLOPT_POST      ,1);
//curl_setopt($curl_handle, CURLOPT_POSTFIELDS    ,POSTVARS);
curl_setopt($curl_handle, CURLOPT_POSTFIELDS    ,$postvars);
curl_setopt($curl_handle,CURLOPT_URL,"$urlbase/credentials_service.php");
curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,5);
curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,1);
$buffer = curl_exec($curl_handle);
curl_close($curl_handle);

if (empty($buffer))
{
	$error.="SESAR Authentication web page unavailable.<br>";
}
else
{
				//OK, we got a response from SESAR. Let's check if the username/password is good
				$dom = new DomDocument();
				if($dom->loadXML($buffer)){
					
					$myvalid="";
					$valids=$dom->getElementsByTagName("valid");
					foreach($valids as $valid){
						$myvalid=$valid->textContent;
					}
					
					if($myvalid!="yes"){
						$error.="Invalid Username/Password combination<br>";
					}

					$mycode="";
					$codes=$dom->getElementsByTagName("usercode");
					foreach($codes as $code){
						$mycode=$code->textContent;
					}

				}else{
					$error.="1Bad XML received from SESAR.<br>";
				}
				
				//echo "buffer: $buffer<br>";
}




























require_once 'Excel/reader.php';

$data = new Spreadsheet_Excel_Reader();

$data->setOutputEncoding('CP1251');

//$data->read('sesartemplatework.xls');
$data->read($_FILES["file"]["tmp_name"]);



error_reporting(E_ALL ^ E_NOTICE);


$xlspass=$data->sheets[0]['cells'][5246][1];

if($xlspass!="123"){
	$error.="Invalid spreadsheet. Please download the official IGSN template <a href=\"SESAR_Template.xls\">here</a><br>";
}

if($error==""){
	//OK, first let's roll over all of the rows to check for errors...
	
	//display progress
	?>
		<script language="JavaScript" type="text/JavaScript">
		document.getElementById('progress').innerHTML = "Checking file for errors...";
		</script>
	<?
		flush();
	
	$igsns=array(); //initialize array to check for duplicate igsns
	$n=1;
	for ($i = 8; $i <= 508; $i++) {
	
		$showrow="no";
		for($x=1;$x<=43;$x++){
			if(trim($data->sheets[0]['cells'][$i][$x]!="")){
				$showrow="yes";
			}
		}
		
		if($showrow=="yes"){
	
			//gather variables for checking
			//IGSN, Parent IGSN, Sample ID, GeoObjectType
			$thisigsn="";
			$thisparentigsn="";
			$thissampleid="";
			$thisgeoobjecttype="";
			$thislat="";
			$thislon="";
			$thisstartdate="";
			$thisenddate="";
			
			
			if($data->sheets[0]['cells'][$i][1]!=""){$thisigsn=strtoupper(trim($data->sheets[0]['cells'][$i][1]));}
			if($data->sheets[0]['cells'][$i][2]!=""){$thisparentigsn=strtoupper(trim($data->sheets[0]['cells'][$i][2]));}
			if($data->sheets[0]['cells'][$i][3]!=""){$thissampleid=trim($data->sheets[0]['cells'][$i][3]);}
			if($data->sheets[0]['cells'][$i][6]!=""){$thisgeoobjecttype=trim($data->sheets[0]['cells'][$i][6]);}
			if($data->sheets[0]['cells'][$i][15]!=""){$thislat=$data->sheets[0]['cells'][$i][15];}
			if($data->sheets[0]['cells'][$i][16]!=""){$thislon=$data->sheets[0]['cells'][$i][16];}

			//also check dates...
			if($data->sheets[0]['cells'][$i][38]!=""){$thisstartdate=trim($data->sheets[0]['cells'][$i][38]);}
			if($data->sheets[0]['cells'][$i][39]!=""){$thisenddate=trim($data->sheets[0]['cells'][$i][39]);}
			
			if($thisstartdate!=""){
				$dateerror="";
				$dateparts=split("/",$thisstartdate);
				$dateparts[0]=ltrim($dateparts[0],"0");
				$dateparts[1]=ltrim($dateparts[1],"0");
				
				if($dateparts[0]<1||$dateparts[0]>12||$dateparts[1]<1||$dateparts[1]>31){
					$dateerror="yes";
				}
				
				$thisyear=$dateparts[2];
				if(strlen($thisyear)!=2 && strlen($thisyear)!=4){
					$dateerror="yes";
				}
				
				if(!is_numeric($thisyear)){
					$dateerror="yes";
				}
				
				if($dateerror=="yes"){
					$error.="ROW $i: Incorrect Start Date Format. Should be in the format MM/DD/YYYY<br>";
				}
				
					
				
				//$error.="<br><br>error";
			}


			if($thisenddate!=""){
				$dateerror="";
				$dateparts=split("/",$thisenddate);
				$dateparts[0]=ltrim($dateparts[0],"0");
				$dateparts[1]=ltrim($dateparts[1],"0");
				
				if($dateparts[0]<1||$dateparts[0]>12||$dateparts[1]<1||$dateparts[1]>31){
					$dateerror="yes";
				}
				
				$thisyear=$dateparts[2];
				if(strlen($thisyear)!=2 && strlen($thisyear)!=4){
					$dateerror="yes";
				}
				
				if(!is_numeric($thisyear)){
					$dateerror="yes";
				}
				
				if($dateerror=="yes"){
					$error.="ROW $i: Incorrect End Date Format. Should be in the format MM/DD/YYYY<br>";
				}
				
					
				
				//$error.="<br><br>error";
			}


			if($thisstartdate=="" && $thisenddate!=""){
				$error.="ROW $i: If End Date is provided, Start Date must also be provided.<br>";
			}
			
			
			//echo rand(999,10000)."\n";
			//echo "igsn: $thisigsn\n";
			//echo "parentigsn: $thisparentigsn\n";
			//echo "sampleid: $thissampleid\n";
			//echo "geoobjecttype: $thisgeoobjecttype\n";
			//echo "\n\n\n";
			
			//check for valid igsn here
			// first, letters and digits...
			if($thisigsn!=""){
				if(!preg_match("/^([A-Z]){3}([A-Z0-9]){6}$/",$thisigsn)){
					$error.="ROW $i: Invalid IGSN found. IGSN's should be in the format ABC123456.<br>";
				}else{
					//letters and digits OK, check for correct usercode ($mycode)
					$thiscode="";
					$thiscode=substr($thisigsn,0,3);
					if($thiscode!=$mycode){
						$error.="ROW $i: Invalid usercode for IGSN. Your usercode should be: $mycode, but the IGSN given has $thiscode.<br>";
					}
				}
			}
			
			
			
			
			if($thisgeoobjecttype==""){
				$error.="Row $i: GeoObjectType must be provided for all samples.<br>";
			}

			if($thislat=="" or $thislon==""){
				$error.="Row $i: Latitude and Longitude must be provided for all samples.<br>";
			}
			
			if($thissampleid==""){
				$error.="Row $i: Sample ID must be provided for all samples.<br>";
			}
			
			
			//OK, first look at the IGSNs and check that they don't exist...
			if($thisigsn!=""){
				
				//check for duplicate igsns here
				if(in_array($thisigsn,$igsns)){
					$error.="Row $i: IGSN $thisigsn has duplicate entries in the spreadsheet. This is not allowed.<br>";
				}
				
				$curl_handle=curl_init();
				curl_setopt($curl_handle,CURLOPT_URL,"$urlbase/display.php?igsn=$thisigsn");
				curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,5);
				curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,1);
				$buffer = curl_exec($curl_handle);
				curl_close($curl_handle);
				
				if (empty($buffer))
				{
					$error.="SESAR web page unavailable.<br>";
				}
				else
				{
					//OK, we got a response from SESAR. Let's make sure it's an error
					$dom = new DomDocument();
					if($dom->loadXML($buffer)){
						
						$myxmlerror="";
						$xmlerrors=$dom->getElementsByTagName("Error");
						foreach($xmlerrors as $xmlerror){
							$myxmlerror=$xmlerror->textContent;
						}
						
						if($myxmlerror==""){
							$error.="Row $i: IGSN $thisigsn already exists.<br>";
						}else{
							//echo "IGSN $thisigsn does not exist.<br>\n";
						}
						
					}else{
						$error.="2Bad XML received from SESAR.<br>";
					}
					
				}
				
				$igsns[]=$thisigsn;
				
			}//end if igsn != ""
			
	
	
			//OK, now look at the Parent IGSNs and check that they do exist...
			if($thisparentigsn!=""){
				$curl_handle=curl_init();
				curl_setopt($curl_handle,CURLOPT_URL,"$urlbase/display.php?igsn=$thisparentigsn");
				curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,5);
				curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,1);
				$buffer = curl_exec($curl_handle);
				curl_close($curl_handle);
				
				if (empty($buffer))
				{
					$error.="SESAR web page unavailable.<br>";
				}
				else
				{
					//OK, we got a response from SESAR. Let's make sure it's an error
					$dom = new DomDocument();
					if($dom->loadXML($buffer)){
						
						$myxmlerror="";
						$xmlerrors=$dom->getElementsByTagName("Error");
						foreach($xmlerrors as $xmlerror){
							$myxmlerror=$xmlerror->textContent;
						}
						
						if($myxmlerror!=""){
							$error.="Row $i: Parent IGSN $thisparentigsn does not exist.<br>";
						}else{
							//echo "Parent IGSN $thisparentigsn exists in sesar.<br>\n";
						}
						
					}else{
						$error.="3Bad XML received from SESAR.<br>";
					}
					
					//echo "\n\n$buffer\n\n";
					
				}
			}//end if parentigsn != ""
	
		//echo "\n\n\n\n";
		
		} //end if showrow = yes
	}//end foreach row in xls file

}//end if error == ""



//$error="test";


if($error==""){

	//display progress
	?>
		<script language="JavaScript" type="text/JavaScript">
		document.getElementById('progress').innerHTML = "Gathering IGSN data from SESAR...";
		</script>
	<?
		flush();
		

		//let's create an XML doc here for the spreadsheet to download...

		$xlsxml="";

		$xlsxml.="<rows>\n";

		//add date
		$xlsxml.="<date>".date("m/d/Y g:ia")."</date>\n";



		/*
		$xlsxml.="\t<row>\n";
		$columnnames=array("IGSN","Parent IGSN","Sample ID","Sample Description","Sample Comment","GeoObject Type","GeoObject Class","Collection Method","Collection Method Desc.","Size","Min Age","Max Age","Material","Material Class","Start Longitude","Start Latitude","Start Geodetic Datum","End Longitude","End Latitude","End Geodetic Datum","Start Elevation","End Elevation","Primary Location Name","Primary Location Type","Location Desc.","Country","Province","County","City or Township","Min. Depth","Max. Depth","Vertical Datum","Field Description","Platform","Platform ID","Platform Desc.","Collector","Start Date","End Date","Orig. Archive Inst.","Orig. Archive Inst. Contact","Most Recent Archival Inst.","Most Recent Archival Contact");
		$colnum=0;
		foreach($columnnames as $columnname){
			$xlsxml.="\t\t<column>".$columnname."</column>\n";
		}
		$xlsxml.="\t</row>\n";
		*/


	$rowcount=0;
	
	//Now if there are no errors, we can roll over the rows again and hit up SESAR
	for ($i = 8; $i <= 508; $i++) {
	
		$showrow="no";
		for($x=1;$x<=43;$x++){
			if(trim($data->sheets[0]['cells'][$i][$x]!="")){
				$showrow="yes";
			}
		}
		
		if($showrow=="yes"){
			
			$thisxml="<sample>\n";
		
			//check for $thisigsn in case we need to populate it later
			$thisigsn=strtoupper(trim($data->sheets[0]['cells'][$i][1]));
		
			if($data->sheets[0]['cells'][$i][1]!=""){$thisxml.="<IGSN>".htmlentities($data->sheets[0]['cells'][$i][1])."</IGSN>\n";}
			if($data->sheets[0]['cells'][$i][2]!=""){$thisxml.="<ParentIGSN>".htmlentities($data->sheets[0]['cells'][$i][2])."</ParentIGSN>\n";}
			if($data->sheets[0]['cells'][$i][3]!=""){$thisxml.="<SampleID>".htmlentities($data->sheets[0]['cells'][$i][3])."</SampleID>\n";}
			if($data->sheets[0]['cells'][$i][4]!=""){$thisxml.="<SampleDescription>".htmlentities($data->sheets[0]['cells'][$i][4])."</SampleDescription>\n";}
			if($data->sheets[0]['cells'][$i][5]!=""){$thisxml.="<SampleComment>".htmlentities($data->sheets[0]['cells'][$i][5])."</SampleComment>\n";}
			if($data->sheets[0]['cells'][$i][6]!=""){$thisxml.="<GeoObjectType>".htmlentities($data->sheets[0]['cells'][$i][6])."</GeoObjectType>\n";}
			if($data->sheets[0]['cells'][$i][7]!=""){$thisxml.="<GeoObjectClassification>".htmlentities($data->sheets[0]['cells'][$i][7])."</GeoObjectClassification>\n";}
			if($data->sheets[0]['cells'][$i][8]!=""){$thisxml.="<CollectionMethod>".htmlentities($data->sheets[0]['cells'][$i][8])."</CollectionMethod>\n";}
			if($data->sheets[0]['cells'][$i][9]!=""){$thisxml.="<CollectionMethodDescription>".htmlentities($data->sheets[0]['cells'][$i][9])."</CollectionMethodDescription>\n";}
			if($data->sheets[0]['cells'][$i][10]!=""){$thisxml.="<Size>".htmlentities($data->sheets[0]['cells'][$i][10])."</Size>\n";}
			if($data->sheets[0]['cells'][$i][11]!=""){$thisxml.="<AgeMin>".htmlentities($data->sheets[0]['cells'][$i][11])."</AgeMin>\n";}
			if($data->sheets[0]['cells'][$i][12]!=""){$thisxml.="<AgeMax>".htmlentities($data->sheets[0]['cells'][$i][12])."</AgeMax>\n";}
			if($data->sheets[0]['cells'][$i][13]!=""){$thisxml.="<Material>".htmlentities($data->sheets[0]['cells'][$i][13])."</Material>\n";}
			if($data->sheets[0]['cells'][$i][14]!=""){$thisxml.="<MaterialClassification>".htmlentities($data->sheets[0]['cells'][$i][14])."</MaterialClassification>\n";}
			
			
			$thisxml.="<StartLocation>\n";
			$thisxml.="<Coordinates>";
			if($data->sheets[0]['cells'][$i][15]!=""){$thisxml.="".htmlentities($data->sheets[0]['cells'][$i][15])."";}
			if($data->sheets[0]['cells'][$i][16]!=""){$thisxml.=", ".htmlentities($data->sheets[0]['cells'][$i][16])."";}
			$thisxml.="</Coordinates>\n";
			if($data->sheets[0]['cells'][$i][17]!=""){$thisxml.="<GeodeticDatum>".htmlentities($data->sheets[0]['cells'][$i][17])."</GeodeticDatum>\n";}
			$thisxml.="</StartLocation>\n";
			
			$thisxml.="<EndLocation>\n";
			$thisxml.="<Coordinates>";
			if($data->sheets[0]['cells'][$i][18]!=""){$thisxml.="".htmlentities($data->sheets[0]['cells'][$i][18])."";}
			if($data->sheets[0]['cells'][$i][19]!=""){$thisxml.=", ".htmlentities($data->sheets[0]['cells'][$i][19])."";}
			$thisxml.="</Coordinates>\n";
			if($data->sheets[0]['cells'][$i][20]!=""){$thisxml.="<GeodeticDatum>".htmlentities($data->sheets[0]['cells'][$i][20])."</GeodeticDatum>\n";}
			$thisxml.="</EndLocation>\n";
			
			
			if($data->sheets[0]['cells'][$i][21]!=""){$thisxml.="<StartElevation>".htmlentities($data->sheets[0]['cells'][$i][21])."</StartElevation>\n";}
			if($data->sheets[0]['cells'][$i][22]!=""){$thisxml.="<EndElevation>".htmlentities($data->sheets[0]['cells'][$i][22])."</EndElevation>\n";}
			
			$thisxml.="<LocationInfo>\n";
			if($data->sheets[0]['cells'][$i][23]!=""){$thisxml.="<PrimaryLocationName>".htmlentities($data->sheets[0]['cells'][$i][23])."</PrimaryLocationName>\n";}
			if($data->sheets[0]['cells'][$i][24]!=""){$thisxml.="<PrimaryLocationType>".htmlentities($data->sheets[0]['cells'][$i][24])."</PrimaryLocationType>\n";}
			if($data->sheets[0]['cells'][$i][25]!=""){$thisxml.="<LocationDescription>".htmlentities($data->sheets[0]['cells'][$i][25])."</LocationDescription>\n";}
			if($data->sheets[0]['cells'][$i][26]!=""){$thisxml.="<Country>".htmlentities($data->sheets[0]['cells'][$i][26])."</Country>\n";}
			if($data->sheets[0]['cells'][$i][27]!=""){$thisxml.="<Province>".htmlentities($data->sheets[0]['cells'][$i][27])."</Province>\n";}
			if($data->sheets[0]['cells'][$i][28]!=""){$thisxml.="<County>".htmlentities($data->sheets[0]['cells'][$i][28])."</County>\n";}
			if($data->sheets[0]['cells'][$i][29]!=""){$thisxml.="<CityorTownship>".htmlentities($data->sheets[0]['cells'][$i][29])."</CityorTownship>\n";}
			$thisxml.="</LocationInfo>\n";
			
			$thisxml.="<DepthInParent>\n";
			if($data->sheets[0]['cells'][$i][30]!=""){$thisxml.="<DepthMin>".htmlentities($data->sheets[0]['cells'][$i][30])."</DepthMin>\n";}
			if($data->sheets[0]['cells'][$i][31]!=""){$thisxml.="<DepthMax>".htmlentities($data->sheets[0]['cells'][$i][31])."</DepthMax>\n";}
			if($data->sheets[0]['cells'][$i][32]!=""){$thisxml.="<VerticalDatum>".htmlentities($data->sheets[0]['cells'][$i][32])."</VerticalDatum>\n";}
			$thisxml.="</DepthInParent>\n";
			
			$thisxml.="<Expedition>\n";
			if($data->sheets[0]['cells'][$i][33]!=""){$thisxml.="<FieldDescription>".htmlentities($data->sheets[0]['cells'][$i][33])."</FieldDescription>\n";}
			if($data->sheets[0]['cells'][$i][34]!=""){$thisxml.="<Platform>".htmlentities($data->sheets[0]['cells'][$i][34])."</Platform>\n";}
			if($data->sheets[0]['cells'][$i][35]!=""){$thisxml.="<PlatformID>".htmlentities($data->sheets[0]['cells'][$i][35])."</PlatformID>\n";}
			if($data->sheets[0]['cells'][$i][36]!=""){$thisxml.="<PlatformDescription>".htmlentities($data->sheets[0]['cells'][$i][36])."</PlatformDescription>\n";}
			if($data->sheets[0]['cells'][$i][37]!=""){$thisxml.="<Collector>".htmlentities($data->sheets[0]['cells'][$i][37])."</Collector>\n";}
			if($data->sheets[0]['cells'][$i][38]!=""){$thisxml.="<StartDate>".htmlentities($data->sheets[0]['cells'][$i][38])."</StartDate>\n";}
			if($data->sheets[0]['cells'][$i][39]!=""){$thisxml.="<EndDate>".htmlentities($data->sheets[0]['cells'][$i][39])."</EndDate>\n";}
			$thisxml.="</Expedition>\n";
			
			$thisxml.="<ArchivalInformation>\n";
			if($data->sheets[0]['cells'][$i][40]!=""){$thisxml.="<OriginalArchivalInstitution>".htmlentities($data->sheets[0]['cells'][$i][40])."</OriginalArchivalInstitution>\n";}
			if($data->sheets[0]['cells'][$i][41]!=""){$thisxml.="<OriginalArchivalContact>".htmlentities($data->sheets[0]['cells'][$i][41])."</OriginalArchivalContact>\n";}
			if($data->sheets[0]['cells'][$i][42]!=""){$thisxml.="<MostRecentArchivalInstitution>".htmlentities($data->sheets[0]['cells'][$i][42])."</MostRecentArchivalInstitution>\n";}
			if($data->sheets[0]['cells'][$i][43]!=""){$thisxml.="<MostRecentArchivalContact>".htmlentities($data->sheets[0]['cells'][$i][43])."</MostRecentArchivalContact>\n";}
			$thisxml.="</ArchivalInformation>\n";
			
			$thisxml.="</sample>";
			
			//echo $thisxml;
			
			
			$content=urlencode($thisxml);
			
			//define('POSTVARS', "username=$username&password=$password&content=$content&submit=ggg");  // POST VARIABLES TO BE SENT
			
			$postvars="username=$username&password=$password&content=$content&submit=ggg";
			
			//echo "$urlbase/uploadservice.php\n\n";
			
			$curl_handle=curl_init();
			curl_setopt($curl_handle, CURLOPT_POST      ,1);
			//curl_setopt($curl_handle, CURLOPT_POSTFIELDS    ,POSTVARS);
			curl_setopt($curl_handle, CURLOPT_POSTFIELDS    ,$postvars);
			curl_setopt($curl_handle,CURLOPT_URL,"$urlbase/uploadservice.php");
			curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,5);
			curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,1);
			$buffer = curl_exec($curl_handle);
			curl_close($curl_handle);
			
			if (empty($buffer))
			{
				$error.="SESAR web page unavailable.<br>";
			}
			else
			{
				//OK, if we've gotten this far, we can assume that everything worked and we got an
				//XML igsn document back... if $thisigsn isn't set, we need to grab it for the XLS file, which we should
				//also be creating here...
				
				if($thisigsn!="aaaaaaaaa"){
					//let's parse the xml response for the igsn
					//echo "$buffer";
					
					$dom = new DomDocument();
					if($dom->loadXML($buffer)){
						
						$myxmligsn="";
						$xmligsns=$dom->getElementsByTagName("IGSN");
						foreach($xmligsns as $xmligsn){
							$myxmligsn=$xmligsn->textContent;
						}
						
						$thisigsn=$myxmligsn;
						$data->sheets[0]['cells'][$i][1]=$myxmligsn;
						
						//echo "\n\nIGSN from SESAR: $myxmligsn\n\n\n";
						//display success
						?>
							<script language="JavaScript" type="text/JavaScript">
							document.getElementById('progress').innerHTML += "<br>New IGSN: <?=$thisigsn?>";
							</script>
						<?
							flush();
						
					}else{
						$error.="4Bad XML received from SESAR upload service.<br>";
					}
					
				}
			}

			// add row to xlsxml for this row
			$xlsxml.="\t<row>\n";
			for($h=1;$h<=43;$h++){
				$xlsxml.="\t\t<column>".$data->sheets[0]['cells'][$i][$h]."</column>\n";
			}
			$xlsxml.="\t</row>\n";
	
			$rowcount++;
	
		} //end if showrow = yes
	}//end for each row
	
	$xlsxml.="</rows>";
	
	
	//echo "\n\n\n\n\n\n\n\n\n";
	//echo nl2br($xlsxml);
	
	//save xml file
	
	$xmlrandstring=time().rand(11111,100000);
	//echo "\n\n\n$xmlrandstring\n\n\n";
	$xmlfilename="usersheets/".$xmlrandstring.".txt";
	//echo "\n\n\n\n$xmlfilename";
	
	
	$fh = fopen($xmlfilename, 'w') or die("can't open file");
	fwrite($fh, $xlsxml);
	fclose($fh);
	
	//also log this for future download...
	$db->query("insert into igsnlog (xlscode,userpkey,numsamps) values ('$xmlrandstring',$userpkey,$rowcount)");
	
	$successstring="Congratulations, your IGSNs have been successfully created. If you would like to download an excel sheet populated with your new values, click <a href=\"excelfiles/$xmlrandstring\">here</a>";
	
	//display success
	?>
		<script language="JavaScript" type="text/JavaScript">
		document.getElementById('progress').innerHTML += "<br>Complete!";
		document.getElementById('success').innerHTML = '<?=$successstring?>';
		document.getElementById('success').style.display = "";
		document.getElementById('workinggif').style.display = "none";
		</script>
	<?
		flush();


		
		
		


			
} //if error==""

if($error!=""){
		$error.="Please <a href=\"igsnupload.php\" style=\"color:red; font-weight:bold;\">try again</a>.";

	?>
		<script language="JavaScript" type="text/JavaScript">
		document.getElementById('myerror').innerHTML = '<?=$error?>';
		document.getElementById('myerror').style.display = "";
		document.getElementById('success').style.display = "none";
		document.getElementById('workinggif').style.display = "none";
		document.getElementById('progress').style.display = "none";
		</script>
	<?
		flush();

}

include("includes/geochron-igsn-footer.php");


?>
