<?PHP
/**
 * detritalexcel.php
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

//print_r($_SESSION);



include("db.php");

// **************** get username stuff here for the search ***************
if($_SESSION['username']!=""){
	$username=$_SESSION['username'];
	$userrow=$db->get_row("select * from users where username='$username'");
	$group=$userrow->usergroup;
	$userpkey=$userrow->users_pkey;
}elseif($_POST['username']!="" & $_POST['password']!=""){
	$username=$_POST['username'];
	$password=$_POST['password'];
	$userrow=$db->get_row("select * from users where username='$username' and password='$password'");
	$group=$userrow->usergroup;
	$userpkey=$userrow->users_pkey;
}


if($group==0 or $group==""){
	$group=99999;
}

if($userpkey==""){
	$userpkey=99999;
}

//*************************************************************************





if($_SESSION['userpkey']!=""){
	$userpkey=$_SESSION['userpkey'];
}else{
	$userpkey="99999";
}



//log download here
$downloadtype="xls download";
include("loghit.php");




$agemin=$_GET['agemin']; //*1000000;
$agemax=$_GET['agemax']; //*1000000;
$geoages=$_GET['geoages'];
$detritaltype=$_GET['detritaltype'];
$detritalmineral=$_GET['detritalmineral'];
$detritalmethod=$_GET['detritalmethod'];

$bounds=$_GET['bounds'];

if($bounds!=""){
	$parts=explode(",",$bounds);
	
	$lon1=$parts[0];
	$lat1=$parts[1];
	$lon2=$parts[2];
	$lat2=$parts[3];
	
	$boundstring="$lon1 $lat1, $lon1 $lat2, $lon2 $lat2, $lon2 $lat1, $lon1 $lat1";
	$boundstring="and ST_Contains(st_GeomFromText('Polygon(($boundstring))'),mypoint)";
}

//build geoagearray here if needed
if($geoages!=""){
	$geoages=explode(",",$geoages);
	$geonum=0;
	foreach($geoages as $geoage){
		$georow=$db->get_row("select * from geoages where pkey=$geoage");
		$thisminage=$georow->minage; //*1000000;
		$thismaxage=$georow->maxage; //*1000000;
		$geoagearray[$geonum][minage]=$thisminage;
		$geoagearray[$geonum][maxage]=$thismaxage;		
		$geonum++;
	}
}


$resultstring="select sample.sample_pkey,
						sample.filename,
						sample_id,
						sample_comment,
						sample_description,
						igsn,
						longitude,
						latitude, 
						age_min,
						age_max,
						detrital_type,
						strat_name,
						oldest_frac_date,
						youngest_frac_date,
						ecproject,
						material,
						geoobjecttype,
						geoobjectclass,
						collectionmethod,
						analyst_name,
						laboratoryname,
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
						mostrecentarchivalcontact
						from sample 
						left join sample_age on sample.sample_pkey = sample_age.sample_pkey
						left join groupsamplerelate on sample.sample_pkey = groupsamplerelate.sample_pkey
						left join grouprelate on groupsamplerelate.group_pkey = grouprelate.group_pkey
						where (sample.publ=1 or sample.userpkey=$userpkey or (grouprelate.users_pkey=$userpkey and grouprelate.confirmed=true))
						$boundstring
						";

$agesdelim="";

if($agemin!="" || $agemax !=""){
	$resultstring.=" and (";
	
	if($agemin!=""){
		$resultstring.=$agesdelim."(age_min >= $agemin or age_max >= $agemin) ";
		$agesdelim=" and ";
	}

	if($agemax!=""){
		$resultstring.=$agesdelim."(age_min <= $agemax or age_max <= $agemax) ";
	}
	
	$resultstring.=")";
}


if($detritaltype!=""){
	$resultstring.="and detrital_type='$detritaltype' ";
}

if($detritalmineral!=""){
	$resultstring.="and material='$detritalmineral' ";
}

if($detritalmethod!=""){
	$resultstring.="and ecproject='$detritalmethod' ";
}



	if(count($geoagearray)>0){
	
		$resultstring.=" and (";
	
		$geoages=explode(",",$geoages);
		$geodelim="";
		foreach($geoagearray as $geoage){
			$thisminage=$geoage[minage];
			$thismaxage=$geoage[maxage];

			$resultstring.=$geodelim."(age_min >= $thisminage or age_max >= $thisminage) and (age_min <= $thismaxage or age_max <= $thismaxage) ";
			$geodelim=" or ";
		}
		
		$resultstring.=") ";
	}
	
	$resultstring.=" and upstream=true
						group by
						sample.sample_pkey,
						sample.filename,
						sample_id,
						sample_comment,
						sample_description,
						igsn,
						longitude,
						latitude, 
						age_min,
						age_max,
						detrital_type,
						strat_name,
						oldest_frac_date,
						youngest_frac_date,
						ecproject,
						material,
						geoobjecttype,
						geoobjectclass,
						collectionmethod,
						analyst_name,
						laboratoryname,
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
						mostrecentarchivalcontact";

//echo nl2br($resultstring);
//exit();
	
$rows=$db->get_results($resultstring);

//exit();

			// Include PEAR::Spreadsheet_Excel_Writer
			require_once "Spreadsheet/Excel/Writer.php";
			
			// Create an instance
			$xls =& new Spreadsheet_Excel_Writer();
			
			// Send HTTP headers to tell the browser what's coming
			$xls->send("Geochron_sample_download.xls");
			
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
			$sheet->write(0,0,"Geochron Sample Download",$formathead);

			$columnnames=array("Sample ID","Unique ID       ","Sample Description","Sample Comment","Longitude","Latitude","Min Age (Ma)","Max Age (Ma)","Detrital Method","Detrital Type","Detrital Mineral","Stratigraphic Formation Name","Oldest Frac. Date (Ma)","Youngest Frac. Date (Ma)","Metadata","Concordia Diagram","Probability Density","CSV Table","GeoObject Type","GeoObject Class","Collection Method","Analyst Name","Laboratory Name","Collector","Rock Type","Primary Location Name","Primary Location Type","Location Description","Locality","Locality Description","Country","Province","County","City or Township","Platform","Platform ID","Original Archival Institution","Original Archival Contact","Most Recent Archival Institution","Most Recent Archival Contact");
			$colnum=0;
			foreach($columnnames as $columnname){
				$thisheader=$columnname;
				$thiswidth=strlen($thisheader)+1;
				if($thisheader=="Age (Ma)"){$thiswidth="12";}
				$sheet->write(7,$colnum,$thisheader,$formatwhiteblue);
				$sheet->setColumn($colnum,$colnum,$thiswidth);
				$colnum++;
			}
			
			
			for ( $i=7;$i<106;$i++ ) {
				for($j=0;$j<3;$j++){
					//$sheet->writeBlank($i,$j,$formatwhite);
				}
}

			$y=8;
			if(count($rows)>0){
				
				$showheader="no";
			
				foreach($rows as $row){
				
					if($showheader=="yes"){
						$colnum=0;
						foreach($columnnames as $columnname){
							$thisheader=$columnname;
							$sheet->write($y,$colnum,$thisheader,$formatwhiteblue);
							$colnum++;
						}
						$y++;
					}

					if($row->ecproject=="redux"){
						$detritalmethod="U-Pb";
					}elseif($row->ecproject=="helios"){
						$detritalmethod="(U-Th)/He";
					}elseif($row->ecproject=="arar"){
						$detritalmethod="Ar-Ar";
					}
					
					if($row->strat_name!=""){
						$showstratname=$row->strat_name;
					}else{
						$showstratname="n/a";
					}

					$sheet->write($y,0,$row->sample_id,$formatwhite);
					$sheet->write($y,1,$row->igsn,$formatwhite);

					$sheet->write($y,2,$row->sample_description,$formatwhite);
					$sheet->write($y,3,$row->sample_comment,$formatwhite);
					$sheet->write($y,4,$row->longitude,$formatwhite);
					$sheet->write($y,5,$row->latitude,$formatwhite);
					
					$sheet->write($y,6,round($row->age_min,0),$formatwhite);
					$sheet->write($y,7,round($row->age_max,0),$formatwhite);
					$sheet->write($y,8,$detritalmethod,$formatwhite);
					$sheet->write($y,9,$row->detrital_type,$formatwhite);
					$sheet->write($y,10,$row->material,$formatwhite);

					$sheet->write($y,11,$showstratname,$formatwhite);
					$sheet->write($y,12,round($row->oldest_frac_date/1000000,0),$formatwhite);
					$sheet->write($y,13,round($row->youngest_frac_date/1000000,0),$formatwhite);

					$sheet->writeUrl($y,14,"http://www.geochron.org/m/$row->sample_pkey","http://www.geochron.org/m/$row->sample_pkey");
					$sheet->writeUrl($y,15,"http://www.geochron.org/c/$row->sample_pkey","http://www.geochron.org/c/$row->sample_pkey");
					$sheet->writeUrl($y,16,"http://www.geochron.org/pd/$row->sample_pkey","http://www.geochron.org/pd/$row->sample_pkey");
					$sheet->writeUrl($y,17,"http://www.geochron.org/csv/$row->sample_pkey","http://www.geochron.org/csv/$row->sample_pkey");

					$sheet->write($y,18,$row->geoobjecttype,$formatwhite);
					$sheet->write($y,19,$row->geoobjectclass,$formatwhite);
					$sheet->write($y,20,$row->collectionmethod,$formatwhite);
					$sheet->write($y,21,$row->analyst_name,$formatwhite);
					$sheet->write($y,22,$row->laboratoryname,$formatwhite);
					$sheet->write($y,23,$row->collector,$formatwhite);
					$sheet->write($y,24,$row->rocktype,$formatwhite);
					$sheet->write($y,25,$row->primarylocationname,$formatwhite);
					$sheet->write($y,26,$row->primarylocationtype,$formatwhite);
					$sheet->write($y,27,$row->locationdescription,$formatwhite);
					$sheet->write($y,28,$row->locality,$formatwhite);
					$sheet->write($y,29,$row->localitydescription,$formatwhite);
					$sheet->write($y,30,$row->country,$formatwhite);
					$sheet->write($y,31,$row->provice,$formatwhite);
					$sheet->write($y,32,$row->county,$formatwhite);
					$sheet->write($y,33,$row->cityortownship,$formatwhite);
					$sheet->write($y,34,$row->platform,$formatwhite);
					$sheet->write($y,35,$row->platformid,$formatwhite);
					$sheet->write($y,36,$row->originalarchivalinstitution,$formatwhite);
					$sheet->write($y,37,$row->originalarchivalcontact,$formatwhite);
					$sheet->write($y,38,$row->mostrecentarchivalinstitution,$formatwhite);
					$sheet->write($y,39,$row->mostrecentarchivalcontact,$formatwhite);
					
					//$y++;
					//$sheet->write($y,1,"Foo Foo $ecproject");
					
					//now do fractions
					$filename=$row->filename;
					$ecproject=$row->ecproject;
					
					$dom = new DomDocument;
					$xmlfile = "files/$filename";
					
					$showtable="no";
					
					$rows="";
					
					if($dom->Load($xmlfile)){

						//$y++;
						//$sheet->write($y,1,"Foo Foo $ecproject");

						if($ecproject=="redux"){
							
							$y++;
							$sheet->write($y,1,"Fraction Info:");
							$sheet->write($y,2,"Fraction ID",$formatfrachead);
							$sheet->write($y,3,"206/238 Age",$formatfrachead);
							$sheet->write($y,4,"206/238 Age Error",$formatfrachead);
							$sheet->write($y,5,"207/235 Age",$formatfrachead);
							$sheet->write($y,6,"207/235 Age Error",$formatfrachead);
							$sheet->write($y,7,"207/206 Age",$formatfrachead);
							$sheet->write($y,8,"207/206 Age Error",$formatfrachead);
							$sheet->write($y,9,"Pb*/Pbc",$formatfrachead);
							//$sheet->write($y,10,"Pb*/Pbc Error",$formatfrachead);
							$sheet->write($y,10,"Rho",$formatfrachead);
							$sheet->write($y,11,"Rho Error",$formatfrachead);

							//additional
							$sheet->write($y,12,"206/238",$formatfrachead);
							$sheet->write($y,13,"206/238 Error",$formatfrachead);
							$sheet->write($y,14,"206/204",$formatfrachead);
							$sheet->write($y,15,"208/206",$formatfrachead);
							$sheet->write($y,16,"conc U",$formatfrachead);
							$sheet->write($y,17,"Th/U samp",$formatfrachead);
							$sheet->write($y,18,"207/235 Age",$formatfrachead);
							$sheet->write($y,19,"207/235 Age Error",$formatfrachead);
							$sheet->write($y,20,"207/206 Age",$formatfrachead);
							$sheet->write($y,21,"207/206 Age Error",$formatfrachead);
							$sheet->write($y,22,"206/238xTh Age",$formatfrachead);
							$sheet->write($y,23,"206/238xTh Age Error",$formatfrachead);
							$sheet->write($y,24,"207/235xPa Age",$formatfrachead);
							$sheet->write($y,25,"207/235xPa Age Error",$formatfrachead);
							$sheet->write($y,26,"207/206xTh Age",$formatfrachead);
							$sheet->write($y,27,"207/206xTh Age Error",$formatfrachead);
							$sheet->write($y,28,"207/206xPa Age",$formatfrachead);
							$sheet->write($y,29,"207/206xPa Age Error",$formatfrachead);

						
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
									$r206_204r="";
									$r208_206r="";
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
											
											if($myname=="age206_238r"){$age206_238r=round($myvalue/1000000,2);$age206_238rerr=round($myonesigma/1000000,2);$showrow="yes";}
											if($myname=="age207_235r"){$age207_235r=round($myvalue/1000000,2);$age207_235rerr=round($myonesigma/1000000,2);$showrow="yes";}
											if($myname=="age207_206r"){$age207_206r=round($myvalue/1000000,2);$age207_206rerr=round($myonesigma/1000000,2);$showrow="yes";}

											//additional...

											if($myname=="age207_235r"){$age207_235r=round($myvalue/1000000,2);$age207_235rerr=round($myonesigma/1000000,2);$showrow="yes";}
											if($myname=="age207_206r"){$age207_206r=round($myvalue/1000000,2);$age207_206rerr=round($myonesigma/1000000,2);$showrow="yes";}
											if($myname=="age206_238r_Th"){$age206_238r_th=round($myvalue/1000000,2);$age206_238r_therr=round($myonesigma/1000000,2);$showrow="yes";}
											if($myname=="age207_235r_Pa"){$age207_235r_pa=round($myvalue/1000000,2);$age207_235r_paerr=round($myonesigma/1000000,2);$showrow="yes";}
											if($myname=="age207_206r_Th"){$age207_206r_th=round($myvalue/1000000,2);$age207_206r_therr=round($myonesigma/1000000,2);$showrow="yes";}
											if($myname=="age207_206r_Pa"){$age207_206r_pa=round($myvalue/1000000,2);$age207_206r_paerr=round($myonesigma/1000000,2);$showrow="yes";}

										
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
											
											if($myname=="radToCommonTotal"){$radtocommontotal=round($myvalue,2);$radtocommontotalerr=round($myonesigma,2);$showrow="yes";}
											
											//additional...
											if($myname=="concU"){$concu=$myvalue;$showrow="yes";}
											if($myname=="rTh_Usample"){$rth_usample=round($myvalue,2);$showrow="yes";}
										
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
											
											if($myname=="rhoR206_238r__r207_235r"){$rho=round($myvalue,2);$rhoerr=round($myonesigma,2);$showrow="yes";}

											
											//additional
											if($myname=="r206_238r"){$r206_238r=round($myvalue,2);$r206_238rerr=round($myonesigma,2);$showrow="yes";}
											if($myname=="r206_204r"){$r206_204r=round($myvalue,2);$r206_204rerr=round($myonesigma,2);$showrow="yes";}
											if($myname=="r208_206r"){$r208_206r=round($myvalue,2);$r208_206rerr=round($myonesigma,2);$showrow="yes";}

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
										$sheet->write($y,2,"$myfractionid",$formatfrac);
										$sheet->write($y,3,"$age206_238r",$formatfrac);
										$sheet->write($y,4,"$age206_238rerr",$formatfrac);
										$sheet->write($y,5,"$age207_235r",$formatfrac);
										$sheet->write($y,6,"$age207_235rerr",$formatfrac);
										$sheet->write($y,7,"$age207_206r",$formatfrac);
										$sheet->write($y,8,"$age207_206rerr",$formatfrac);
										$sheet->write($y,9,"$radtocommontotal",$formatfrac);
										//$sheet->write($y,10,"$radtocommontotalerr",$formatfrac);
										$sheet->write($y,10,"$rho",$formatfrac);
										$sheet->write($y,11,"$rhoerr",$formatfrac);

										//additional...
										$sheet->write($y,12,"$r206_238r",$formatfrac);
										$sheet->write($y,13,"$r206_238rerr",$formatfrac);
										$sheet->write($y,14,"$r206_204r",$formatfrac);
										$sheet->write($y,15,"$r208_206r",$formatfrac);
										$sheet->write($y,16,"$concu",$formatfrac);
										$sheet->write($y,17,"$rth_usample",$formatfrac);
										$sheet->write($y,18,"$age207_235r",$formatfrac);
										$sheet->write($y,19,"$age207_235rerr",$formatfrac);
										$sheet->write($y,20,"$age207_206r",$formatfrac);
										$sheet->write($y,21,"$age207_206rerr",$formatfrac);
										$sheet->write($y,22,"$age206_238r_th",$formatfrac);
										$sheet->write($y,23,"$age206_238r_therr",$formatfrac);
										$sheet->write($y,24,"$age207_235r_pa",$formatfrac);
										$sheet->write($y,25,"$age207_235r_paerr",$formatfrac);
										$sheet->write($y,26,"$age207_206r_th",$formatfrac);
										$sheet->write($y,27,"$age207_206r_therr",$formatfrac);
										$sheet->write($y,28,"$age207_206r_pa",$formatfrac);
										$sheet->write($y,29,"$age207_206r_paerr",$formatfrac);


									}//end if showrow
						
								}//end foreach analysisfractions
							
								if($showtable=="yes"){

								}else{
									//echo "No fraction data found.";
								}
							
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
					
								$umeaserrs = $aliquot->getElementsByTagName("UMeasErr");
								foreach($umeaserrs as $umeaserr){
									$myumeaserr=round($umeaserr->textContent,2);
								}
					
								$hes = $aliquot->getElementsByTagName("He");
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
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
						
						}else{ //ecproject 
							//echo "for future development";
						}
					
					}//end if dom load









































































































































					
					
					//writeUrl ( integer $row , integer $col , string $url , string $string='' , mixed $format=0 )
					$showheader="yes";
					$y++;
				}
			}




$sheet->setMerge(2,0,4,6);

$sheet->write(2,0,"Notes:",$formatinstr);

$sheet->setMerge(6,6,6,11);

$sheet->write(6,6,"Host Rock",$formathostrock);

$sheet->setMerge(6,12,6,13);

$sheet->write(6,12,"Fraction",$formatfraction);

// Finish the spreadsheet, dumping it to the browser
$xls->close(); 

exit();









/*
for($x=0;$x<100;$x++){

	echo "\$test".$x." =& \$xls->addFormat();"."\n";
	echo "\$test".$x."->setColor('white');"."\n";
	echo "\$test".$x."->setFgColor($x); //30 62 60"."\n";
	echo "\$test".$x."->setBold(700);"."\n";
	echo "\$test".$x."->setHAlign('center');"."\n";
	echo "\n\n";


}
*/

/*
for($x=0;$x<100;$x++){

	$thisnum=$x+25;
	echo "\$sheet->write(".$thisnum.",0,\"test $x\",\$test".$x.");\n";


}


exit();


$test0 =& $xls->addFormat();
$test0->setColor('white');
$test0->setFgColor(0); //30 62 60
$test0->setBold(700);
$test0->setHAlign('center');


$test1 =& $xls->addFormat();
$test1->setColor('white');
$test1->setFgColor(1); //30 62 60
$test1->setBold(700);
$test1->setHAlign('center');


$test2 =& $xls->addFormat();
$test2->setColor('white');
$test2->setFgColor(2); //30 62 60
$test2->setBold(700);
$test2->setHAlign('center');


$test3 =& $xls->addFormat();
$test3->setColor('white');
$test3->setFgColor(3); //30 62 60
$test3->setBold(700);
$test3->setHAlign('center');


$test4 =& $xls->addFormat();
$test4->setColor('white');
$test4->setFgColor(4); //30 62 60
$test4->setBold(700);
$test4->setHAlign('center');


$test5 =& $xls->addFormat();
$test5->setColor('white');
$test5->setFgColor(5); //30 62 60
$test5->setBold(700);
$test5->setHAlign('center');


$test6 =& $xls->addFormat();
$test6->setColor('white');
$test6->setFgColor(6); //30 62 60
$test6->setBold(700);
$test6->setHAlign('center');


$test7 =& $xls->addFormat();
$test7->setColor('white');
$test7->setFgColor(7); //30 62 60
$test7->setBold(700);
$test7->setHAlign('center');


$test8 =& $xls->addFormat();
$test8->setColor('white');
$test8->setFgColor(8); //30 62 60
$test8->setBold(700);
$test8->setHAlign('center');


$test9 =& $xls->addFormat();
$test9->setColor('white');
$test9->setFgColor(9); //30 62 60
$test9->setBold(700);
$test9->setHAlign('center');


$test10 =& $xls->addFormat();
$test10->setColor('white');
$test10->setFgColor(10); //30 62 60
$test10->setBold(700);
$test10->setHAlign('center');


$test11 =& $xls->addFormat();
$test11->setColor('white');
$test11->setFgColor(11); //30 62 60
$test11->setBold(700);
$test11->setHAlign('center');


$test12 =& $xls->addFormat();
$test12->setColor('white');
$test12->setFgColor(12); //30 62 60
$test12->setBold(700);
$test12->setHAlign('center');


$test13 =& $xls->addFormat();
$test13->setColor('white');
$test13->setFgColor(13); //30 62 60
$test13->setBold(700);
$test13->setHAlign('center');


$test14 =& $xls->addFormat();
$test14->setColor('white');
$test14->setFgColor(14); //30 62 60
$test14->setBold(700);
$test14->setHAlign('center');


$test15 =& $xls->addFormat();
$test15->setColor('white');
$test15->setFgColor(15); //30 62 60
$test15->setBold(700);
$test15->setHAlign('center');


$test16 =& $xls->addFormat();
$test16->setColor('white');
$test16->setFgColor(16); //30 62 60
$test16->setBold(700);
$test16->setHAlign('center');


$test17 =& $xls->addFormat();
$test17->setColor('white');
$test17->setFgColor(17); //30 62 60
$test17->setBold(700);
$test17->setHAlign('center');


$test18 =& $xls->addFormat();
$test18->setColor('white');
$test18->setFgColor(18); //30 62 60
$test18->setBold(700);
$test18->setHAlign('center');


$test19 =& $xls->addFormat();
$test19->setColor('white');
$test19->setFgColor(19); //30 62 60
$test19->setBold(700);
$test19->setHAlign('center');


$test20 =& $xls->addFormat();
$test20->setColor('white');
$test20->setFgColor(20); //30 62 60
$test20->setBold(700);
$test20->setHAlign('center');


$test21 =& $xls->addFormat();
$test21->setColor('white');
$test21->setFgColor(21); //30 62 60
$test21->setBold(700);
$test21->setHAlign('center');


$test22 =& $xls->addFormat();
$test22->setColor('white');
$test22->setFgColor(22); //30 62 60
$test22->setBold(700);
$test22->setHAlign('center');


$test23 =& $xls->addFormat();
$test23->setColor('white');
$test23->setFgColor(23); //30 62 60
$test23->setBold(700);
$test23->setHAlign('center');


$test24 =& $xls->addFormat();
$test24->setColor('white');
$test24->setFgColor(24); //30 62 60
$test24->setBold(700);
$test24->setHAlign('center');


$test25 =& $xls->addFormat();
$test25->setColor('white');
$test25->setFgColor(25); //30 62 60
$test25->setBold(700);
$test25->setHAlign('center');


$test26 =& $xls->addFormat();
$test26->setColor('white');
$test26->setFgColor(26); //30 62 60
$test26->setBold(700);
$test26->setHAlign('center');


$test27 =& $xls->addFormat();
$test27->setColor('white');
$test27->setFgColor(27); //30 62 60
$test27->setBold(700);
$test27->setHAlign('center');


$test28 =& $xls->addFormat();
$test28->setColor('white');
$test28->setFgColor(28); //30 62 60
$test28->setBold(700);
$test28->setHAlign('center');


$test29 =& $xls->addFormat();
$test29->setColor('white');
$test29->setFgColor(29); //30 62 60
$test29->setBold(700);
$test29->setHAlign('center');


$test30 =& $xls->addFormat();
$test30->setColor('white');
$test30->setFgColor(30); //30 62 60
$test30->setBold(700);
$test30->setHAlign('center');


$test31 =& $xls->addFormat();
$test31->setColor('white');
$test31->setFgColor(31); //30 62 60
$test31->setBold(700);
$test31->setHAlign('center');


$test32 =& $xls->addFormat();
$test32->setColor('white');
$test32->setFgColor(32); //30 62 60
$test32->setBold(700);
$test32->setHAlign('center');


$test33 =& $xls->addFormat();
$test33->setColor('white');
$test33->setFgColor(33); //30 62 60
$test33->setBold(700);
$test33->setHAlign('center');


$test34 =& $xls->addFormat();
$test34->setColor('white');
$test34->setFgColor(34); //30 62 60
$test34->setBold(700);
$test34->setHAlign('center');


$test35 =& $xls->addFormat();
$test35->setColor('white');
$test35->setFgColor(35); //30 62 60
$test35->setBold(700);
$test35->setHAlign('center');


$test36 =& $xls->addFormat();
$test36->setColor('white');
$test36->setFgColor(36); //30 62 60
$test36->setBold(700);
$test36->setHAlign('center');


$test37 =& $xls->addFormat();
$test37->setColor('white');
$test37->setFgColor(37); //30 62 60
$test37->setBold(700);
$test37->setHAlign('center');


$test38 =& $xls->addFormat();
$test38->setColor('white');
$test38->setFgColor(38); //30 62 60
$test38->setBold(700);
$test38->setHAlign('center');


$test39 =& $xls->addFormat();
$test39->setColor('white');
$test39->setFgColor(39); //30 62 60
$test39->setBold(700);
$test39->setHAlign('center');


$test40 =& $xls->addFormat();
$test40->setColor('white');
$test40->setFgColor(40); //30 62 60
$test40->setBold(700);
$test40->setHAlign('center');


$test41 =& $xls->addFormat();
$test41->setColor('white');
$test41->setFgColor(41); //30 62 60
$test41->setBold(700);
$test41->setHAlign('center');


$test42 =& $xls->addFormat();
$test42->setColor('white');
$test42->setFgColor(42); //30 62 60
$test42->setBold(700);
$test42->setHAlign('center');


$test43 =& $xls->addFormat();
$test43->setColor('white');
$test43->setFgColor(43); //30 62 60
$test43->setBold(700);
$test43->setHAlign('center');


$test44 =& $xls->addFormat();
$test44->setColor('white');
$test44->setFgColor(44); //30 62 60
$test44->setBold(700);
$test44->setHAlign('center');


$test45 =& $xls->addFormat();
$test45->setColor('white');
$test45->setFgColor(45); //30 62 60
$test45->setBold(700);
$test45->setHAlign('center');


$test46 =& $xls->addFormat();
$test46->setColor('white');
$test46->setFgColor(46); //30 62 60
$test46->setBold(700);
$test46->setHAlign('center');


$test47 =& $xls->addFormat();
$test47->setColor('white');
$test47->setFgColor(47); //30 62 60
$test47->setBold(700);
$test47->setHAlign('center');


$test48 =& $xls->addFormat();
$test48->setColor('white');
$test48->setFgColor(48); //30 62 60
$test48->setBold(700);
$test48->setHAlign('center');


$test49 =& $xls->addFormat();
$test49->setColor('white');
$test49->setFgColor(49); //30 62 60
$test49->setBold(700);
$test49->setHAlign('center');


$test50 =& $xls->addFormat();
$test50->setColor('white');
$test50->setFgColor(50); //30 62 60
$test50->setBold(700);
$test50->setHAlign('center');


$test51 =& $xls->addFormat();
$test51->setColor('white');
$test51->setFgColor(51); //30 62 60
$test51->setBold(700);
$test51->setHAlign('center');


$test52 =& $xls->addFormat();
$test52->setColor('white');
$test52->setFgColor(52); //30 62 60
$test52->setBold(700);
$test52->setHAlign('center');


$test53 =& $xls->addFormat();
$test53->setColor('white');
$test53->setFgColor(53); //30 62 60
$test53->setBold(700);
$test53->setHAlign('center');


$test54 =& $xls->addFormat();
$test54->setColor('white');
$test54->setFgColor(54); //30 62 60
$test54->setBold(700);
$test54->setHAlign('center');


$test55 =& $xls->addFormat();
$test55->setColor('white');
$test55->setFgColor(55); //30 62 60
$test55->setBold(700);
$test55->setHAlign('center');


$test56 =& $xls->addFormat();
$test56->setColor('white');
$test56->setFgColor(56); //30 62 60
$test56->setBold(700);
$test56->setHAlign('center');


$test57 =& $xls->addFormat();
$test57->setColor('white');
$test57->setFgColor(57); //30 62 60
$test57->setBold(700);
$test57->setHAlign('center');


$test58 =& $xls->addFormat();
$test58->setColor('white');
$test58->setFgColor(58); //30 62 60
$test58->setBold(700);
$test58->setHAlign('center');


$test59 =& $xls->addFormat();
$test59->setColor('white');
$test59->setFgColor(59); //30 62 60
$test59->setBold(700);
$test59->setHAlign('center');


$test60 =& $xls->addFormat();
$test60->setColor('white');
$test60->setFgColor(60); //30 62 60
$test60->setBold(700);
$test60->setHAlign('center');


$test61 =& $xls->addFormat();
$test61->setColor('white');
$test61->setFgColor(61); //30 62 60
$test61->setBold(700);
$test61->setHAlign('center');


$test62 =& $xls->addFormat();
$test62->setColor('white');
$test62->setFgColor(62); //30 62 60
$test62->setBold(700);
$test62->setHAlign('center');


$test63 =& $xls->addFormat();
$test63->setColor('white');
$test63->setFgColor(63); //30 62 60
$test63->setBold(700);
$test63->setHAlign('center');


$test64 =& $xls->addFormat();
$test64->setColor('white');
$test64->setFgColor(64); //30 62 60
$test64->setBold(700);
$test64->setHAlign('center');


$test65 =& $xls->addFormat();
$test65->setColor('white');
$test65->setFgColor(65); //30 62 60
$test65->setBold(700);
$test65->setHAlign('center');


$test66 =& $xls->addFormat();
$test66->setColor('white');
$test66->setFgColor(66); //30 62 60
$test66->setBold(700);
$test66->setHAlign('center');


$test67 =& $xls->addFormat();
$test67->setColor('white');
$test67->setFgColor(67); //30 62 60
$test67->setBold(700);
$test67->setHAlign('center');


$test68 =& $xls->addFormat();
$test68->setColor('white');
$test68->setFgColor(68); //30 62 60
$test68->setBold(700);
$test68->setHAlign('center');


$test69 =& $xls->addFormat();
$test69->setColor('white');
$test69->setFgColor(69); //30 62 60
$test69->setBold(700);
$test69->setHAlign('center');


$test70 =& $xls->addFormat();
$test70->setColor('white');
$test70->setFgColor(70); //30 62 60
$test70->setBold(700);
$test70->setHAlign('center');


$test71 =& $xls->addFormat();
$test71->setColor('white');
$test71->setFgColor(71); //30 62 60
$test71->setBold(700);
$test71->setHAlign('center');


$test72 =& $xls->addFormat();
$test72->setColor('white');
$test72->setFgColor(72); //30 62 60
$test72->setBold(700);
$test72->setHAlign('center');


$test73 =& $xls->addFormat();
$test73->setColor('white');
$test73->setFgColor(73); //30 62 60
$test73->setBold(700);
$test73->setHAlign('center');


$test74 =& $xls->addFormat();
$test74->setColor('white');
$test74->setFgColor(74); //30 62 60
$test74->setBold(700);
$test74->setHAlign('center');


$test75 =& $xls->addFormat();
$test75->setColor('white');
$test75->setFgColor(75); //30 62 60
$test75->setBold(700);
$test75->setHAlign('center');


$test76 =& $xls->addFormat();
$test76->setColor('white');
$test76->setFgColor(76); //30 62 60
$test76->setBold(700);
$test76->setHAlign('center');


$test77 =& $xls->addFormat();
$test77->setColor('white');
$test77->setFgColor(77); //30 62 60
$test77->setBold(700);
$test77->setHAlign('center');


$test78 =& $xls->addFormat();
$test78->setColor('white');
$test78->setFgColor(78); //30 62 60
$test78->setBold(700);
$test78->setHAlign('center');


$test79 =& $xls->addFormat();
$test79->setColor('white');
$test79->setFgColor(79); //30 62 60
$test79->setBold(700);
$test79->setHAlign('center');


$test80 =& $xls->addFormat();
$test80->setColor('white');
$test80->setFgColor(80); //30 62 60
$test80->setBold(700);
$test80->setHAlign('center');


$test81 =& $xls->addFormat();
$test81->setColor('white');
$test81->setFgColor(81); //30 62 60
$test81->setBold(700);
$test81->setHAlign('center');


$test82 =& $xls->addFormat();
$test82->setColor('white');
$test82->setFgColor(82); //30 62 60
$test82->setBold(700);
$test82->setHAlign('center');


$test83 =& $xls->addFormat();
$test83->setColor('white');
$test83->setFgColor(83); //30 62 60
$test83->setBold(700);
$test83->setHAlign('center');


$test84 =& $xls->addFormat();
$test84->setColor('white');
$test84->setFgColor(84); //30 62 60
$test84->setBold(700);
$test84->setHAlign('center');


$test85 =& $xls->addFormat();
$test85->setColor('white');
$test85->setFgColor(85); //30 62 60
$test85->setBold(700);
$test85->setHAlign('center');


$test86 =& $xls->addFormat();
$test86->setColor('white');
$test86->setFgColor(86); //30 62 60
$test86->setBold(700);
$test86->setHAlign('center');


$test87 =& $xls->addFormat();
$test87->setColor('white');
$test87->setFgColor(87); //30 62 60
$test87->setBold(700);
$test87->setHAlign('center');


$test88 =& $xls->addFormat();
$test88->setColor('white');
$test88->setFgColor(88); //30 62 60
$test88->setBold(700);
$test88->setHAlign('center');


$test89 =& $xls->addFormat();
$test89->setColor('white');
$test89->setFgColor(89); //30 62 60
$test89->setBold(700);
$test89->setHAlign('center');


$test90 =& $xls->addFormat();
$test90->setColor('white');
$test90->setFgColor(90); //30 62 60
$test90->setBold(700);
$test90->setHAlign('center');


$test91 =& $xls->addFormat();
$test91->setColor('white');
$test91->setFgColor(91); //30 62 60
$test91->setBold(700);
$test91->setHAlign('center');


$test92 =& $xls->addFormat();
$test92->setColor('white');
$test92->setFgColor(92); //30 62 60
$test92->setBold(700);
$test92->setHAlign('center');


$test93 =& $xls->addFormat();
$test93->setColor('white');
$test93->setFgColor(93); //30 62 60
$test93->setBold(700);
$test93->setHAlign('center');


$test94 =& $xls->addFormat();
$test94->setColor('white');
$test94->setFgColor(94); //30 62 60
$test94->setBold(700);
$test94->setHAlign('center');


$test95 =& $xls->addFormat();
$test95->setColor('white');
$test95->setFgColor(95); //30 62 60
$test95->setBold(700);
$test95->setHAlign('center');


$test96 =& $xls->addFormat();
$test96->setColor('white');
$test96->setFgColor(96); //30 62 60
$test96->setBold(700);
$test96->setHAlign('center');


$test97 =& $xls->addFormat();
$test97->setColor('white');
$test97->setFgColor(97); //30 62 60
$test97->setBold(700);
$test97->setHAlign('center');


$test98 =& $xls->addFormat();
$test98->setColor('white');
$test98->setFgColor(98); //30 62 60
$test98->setBold(700);
$test98->setHAlign('center');


$test99 =& $xls->addFormat();
$test99->setColor('white');
$test99->setFgColor(99); //30 62 60
$test99->setBold(700);
$test99->setHAlign('center');


$sheet->write(25,0,"test 0",$test0);
$sheet->write(26,0,"test 1",$test1);
$sheet->write(27,0,"test 2",$test2);
$sheet->write(28,0,"test 3",$test3);
$sheet->write(29,0,"test 4",$test4);
$sheet->write(30,0,"test 5",$test5);
$sheet->write(31,0,"test 6",$test6);
$sheet->write(32,0,"test 7",$test7);
$sheet->write(33,0,"test 8",$test8);
$sheet->write(34,0,"test 9",$test9);
$sheet->write(35,0,"test 10",$test10);
$sheet->write(36,0,"test 11",$test11);
$sheet->write(37,0,"test 12",$test12);
$sheet->write(38,0,"test 13",$test13);
$sheet->write(39,0,"test 14",$test14);
$sheet->write(40,0,"test 15",$test15);
$sheet->write(41,0,"test 16",$test16);
$sheet->write(42,0,"test 17",$test17);
$sheet->write(43,0,"test 18",$test18);
$sheet->write(44,0,"test 19",$test19);
$sheet->write(45,0,"test 20",$test20);
$sheet->write(46,0,"test 21",$test21);
$sheet->write(47,0,"test 22",$test22);
$sheet->write(48,0,"test 23",$test23);
$sheet->write(49,0,"test 24",$test24);
$sheet->write(50,0,"test 25",$test25);
$sheet->write(51,0,"test 26",$test26);
$sheet->write(52,0,"test 27",$test27);
$sheet->write(53,0,"test 28",$test28);
$sheet->write(54,0,"test 29",$test29);
$sheet->write(55,0,"test 30",$test30);
$sheet->write(56,0,"test 31",$test31);
$sheet->write(57,0,"test 32",$test32);
$sheet->write(58,0,"test 33",$test33);
$sheet->write(59,0,"test 34",$test34);
$sheet->write(60,0,"test 35",$test35);
$sheet->write(61,0,"test 36",$test36);
$sheet->write(62,0,"test 37",$test37);
$sheet->write(63,0,"test 38",$test38);
$sheet->write(64,0,"test 39",$test39);
$sheet->write(65,0,"test 40",$test40);
$sheet->write(66,0,"test 41",$test41);
$sheet->write(67,0,"test 42",$test42);
$sheet->write(68,0,"test 43",$test43);
$sheet->write(69,0,"test 44",$test44);
$sheet->write(70,0,"test 45",$test45);
$sheet->write(71,0,"test 46",$test46);
$sheet->write(72,0,"test 47",$test47);
$sheet->write(73,0,"test 48",$test48);
$sheet->write(74,0,"test 49",$test49);
$sheet->write(75,0,"test 50",$test50);
$sheet->write(76,0,"test 51",$test51);
$sheet->write(77,0,"test 52",$test52);
$sheet->write(78,0,"test 53",$test53);
$sheet->write(79,0,"test 54",$test54);
$sheet->write(80,0,"test 55",$test55);
$sheet->write(81,0,"test 56",$test56);
$sheet->write(82,0,"test 57",$test57);
$sheet->write(83,0,"test 58",$test58);
$sheet->write(84,0,"test 59",$test59);
$sheet->write(85,0,"test 60",$test60);
$sheet->write(86,0,"test 61",$test61);
$sheet->write(87,0,"test 62",$test62);
$sheet->write(88,0,"test 63",$test63);
$sheet->write(89,0,"test 64",$test64);
$sheet->write(90,0,"test 65",$test65);
$sheet->write(91,0,"test 66",$test66);
$sheet->write(92,0,"test 67",$test67);
$sheet->write(93,0,"test 68",$test68);
$sheet->write(94,0,"test 69",$test69);
$sheet->write(95,0,"test 70",$test70);
$sheet->write(96,0,"test 71",$test71);
$sheet->write(97,0,"test 72",$test72);
$sheet->write(98,0,"test 73",$test73);
$sheet->write(99,0,"test 74",$test74);
$sheet->write(100,0,"test 75",$test75);
$sheet->write(101,0,"test 76",$test76);
$sheet->write(102,0,"test 77",$test77);
$sheet->write(103,0,"test 78",$test78);
$sheet->write(104,0,"test 79",$test79);
$sheet->write(105,0,"test 80",$test80);
$sheet->write(106,0,"test 81",$test81);
$sheet->write(107,0,"test 82",$test82);
$sheet->write(108,0,"test 83",$test83);
$sheet->write(109,0,"test 84",$test84);
$sheet->write(110,0,"test 85",$test85);
$sheet->write(111,0,"test 86",$test86);
$sheet->write(112,0,"test 87",$test87);
$sheet->write(113,0,"test 88",$test88);
$sheet->write(114,0,"test 89",$test89);
$sheet->write(115,0,"test 90",$test90);
$sheet->write(116,0,"test 91",$test91);
$sheet->write(117,0,"test 92",$test92);
$sheet->write(118,0,"test 93",$test93);
$sheet->write(119,0,"test 94",$test94);
$sheet->write(120,0,"test 95",$test95);
$sheet->write(121,0,"test 96",$test96);
$sheet->write(122,0,"test 97",$test97);
$sheet->write(123,0,"test 98",$test98);
$sheet->write(124,0,"test 99",$test99);




*/











?>