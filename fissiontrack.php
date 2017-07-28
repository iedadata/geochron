<?PHP
/**
 * fissiontrack.php
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


if($_POST['submitfile']!=""){

	$apatiteheaders[1][0]="Grain ID";
	$apatiteheaders[2][0]="N s";
	$apatiteheaders[3][0]="N i";
	$apatiteheaders[4][0]="Na";
	$apatiteheaders[5][0]="Dpar";
	$apatiteheaders[6][0]="Dper";
	$apatiteheaders[7][0]="Rmr0";
	$apatiteheaders[8][0]="Rho s";
	$apatiteheaders[9][0]="Rho i";
	$apatiteheaders[10][0]="Rho s / Rho i";
	$apatiteheaders[11][0]="Area (Ω)";
	$apatiteheaders[12][0]="Number of Etch Figures";
	$apatiteheaders[13][0]="238U/43Ca";
	$apatiteheaders[14][0]="error (1σ)";
	$apatiteheaders[15][0]="U ppm";
	$apatiteheaders[16][0]="U error (1s)";
	$apatiteheaders[17][0]="Age (Ma)";
	$apatiteheaders[18][0]="Age error +1s";
	$apatiteheaders[19][0]="Age error -1s";
	$apatiteheaders[20][0]="CaO";
	$apatiteheaders[21][0]="P2O5";
	$apatiteheaders[22][0]="F";
	$apatiteheaders[23][0]="Cl";
	$apatiteheaders[24][0]="SrO";
	$apatiteheaders[25][0]="BaO";
	$apatiteheaders[26][0]="Si02";
	$apatiteheaders[27][0]="Na2O";
	$apatiteheaders[28][0]="CeO2";
	$apatiteheaders[29][0]="FeO";
	$apatiteheaders[30][0]="Total";

	$apatiteheaders[1][1]="grainid";
	$apatiteheaders[2][1]="ns";
	$apatiteheaders[3][1]="ni";
	$apatiteheaders[4][1]="na";
	$apatiteheaders[5][1]="dpar";
	$apatiteheaders[6][1]="dper";
	$apatiteheaders[7][1]="rmr0";
	$apatiteheaders[8][1]="rhos";
	$apatiteheaders[9][1]="rhoi";
	$apatiteheaders[10][1]="rhosrhoi";
	$apatiteheaders[11][1]="area";
	$apatiteheaders[12][1]="ofetchfigures";
	$apatiteheaders[13][1]="u238ca43";
	$apatiteheaders[14][1]="error1s";
	$apatiteheaders[14][1]="error1s";
	$apatiteheaders[15][1]="uppm";
	$apatiteheaders[16][1]="uerror1s";
	$apatiteheaders[17][1]="agema";
	$apatiteheaders[18][1]="ageerror1s";
	$apatiteheaders[18][1]="ageerrorplus1s";
	$apatiteheaders[19][1]="ageerrorminus1s";
	$apatiteheaders[20][1]="cao";
	$apatiteheaders[21][1]="p2o5";
	$apatiteheaders[22][1]="f";
	$apatiteheaders[23][1]="cl";
	$apatiteheaders[24][1]="sro";
	$apatiteheaders[25][1]="bao";
	$apatiteheaders[26][1]="si02";
	$apatiteheaders[27][1]="na2o";
	$apatiteheaders[28][1]="ceo2";
	$apatiteheaders[29][1]="feo";
	$apatiteheaders[30][1]="total";

	$overwrite=$_POST['overwrite'];
	$public=$_POST['public'];

	//echo "overwrite: $overwrite public:$public";exit();

	//file has been submitted. Check it here and save it for 
	//processing samples
	
	if($error==""){
	
		//print_r($_FILES);
		$orig_name=$_FILES['ftfile']['name'];
		$temp_name=$_FILES['ftfile']['tmp_name'];
		
		//echo "name: $orig_name temp_name: $temp_name";exit();

	
		//check filetype here
		$path_parts = pathinfo($orig_name);
		$extension = strtolower($path_parts['extension']);
		//echo "extension: $extension";exit();
		
		if($extension!="ods" && $extension!="xlsx" && $extension!="xls"){
			$error.="Wrong file type detected. File must be .xlsx or .ods document.<br>";
		}
		

		
		
	}

	if($error==""){
	
		//OK, the file is good, so let's parse it and check for required values
		set_include_path(get_include_path() . PATH_SEPARATOR . 'Classes/');
		include 'PHPExcel/IOFactory.php';

		$inputFileName = $temp_name;

		try {
			$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
		} catch(Exception $e) {
			die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
		}
	
		//Go through data and look for errors
		/*
		sheetnum: 0 sheetname: PlotDat1
		sheetnum: 1 sheetname: Sample-Methods Worksheet
		sheetnum: 2 sheetname: Apatite Age Worksheet
		sheetnum: 3 sheetname: Length Worksheet
		*/

		$sheetcount=$objPHPExcel->getSheetCount();

		//echo "sheetcount: $sheetcount<br>";

		$samplemethodsheetnum=55;
		$apatiteagesheetnum=55;
		$lengthsheetnum=55;

		for($sheetnum=0;$sheetnum<$sheetcount;$sheetnum++){

			$objPHPExcel->setActiveSheetIndex($sheetnum);

			$wholesheet = $objPHPExcel->getActiveSheet();
			$sheetname=$wholesheet->getTitle();

			//$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
			//$sheetData = $objPHPExcel->getActiveSheet()->toArray();
	
			if($sheetname=="Sample-Methods Worksheet"){$samplemethodsheetnum=$sheetnum;}
			if($sheetname=="Apatite Age Worksheet" || $sheetname=="Age Worksheet"){$apatiteagesheetnum=$sheetnum;}
			if($sheetname=="Length Worksheet"){$lengthsheetnum=$sheetnum;}


		}

		/*
		echo "samplemethodsheetnum=$samplemethodsheetnum<br>";
		echo "apatiteagesheetnum=$apatiteagesheetnum<br>";
		echo "lengthsheetnum=$lengthsheetnum<br>";
		*/
		
		if($samplemethodsheetnum==55 or $apatiteagesheetnum==55 or $lengthsheetnum==55){
			$error.="Required sheet(s) not found in workbook. Please use provided template below.<br>";
		}
	
	
	}//end if error == ""

	if($error==""){
		
		//OK, we found the required sheets. Let's check the sample methods sheet for required fields.
		$objPHPExcel->setActiveSheetIndex($samplemethodsheetnum);

		//$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
		$sd = $objPHPExcel->getActiveSheet()->toArray();
		


		$maxy=0;
		for($y=1;$y<200;$y++){
			if($sd[$y][1]!=""){
				$maxy=$y;
			}
		}
		
		//echo "maxy: $maxy<br>";exit();
		
		//loop over rows to get values
		
		$agenum=0;
		
		for($y=1;$y<=$maxy;$y++){
		
			if($sd[$y][0]=="Sample Name"){$samplename=$sd[$y][1];}
			if($sd[$y][0]=="IGSN"){$uniqueid=$sd[$y][1];}
			if($sd[$y][0]=="Laboratory"){$labname=$sd[$y][1];}
			if($sd[$y][0]=="Analyst"){$analystname=$sd[$y][1];}
			if($sd[$y][0]=="Mineral"){$mineral=$sd[$y][1];}

			if($sd[$y][0]=="Dosimeter Glass"){$dosimeterglass=$sd[$y][1];}
			if($sd[$y][0]=="Dosimeter Glass U ppm"){$dosimeterglassuppm=$sd[$y][1];}
			if($sd[$y][0]=="Irradiation"){$irradiation=$sd[$y][1];}
			if($sd[$y][0]=="Count Date"){$countdate=$sd[$y][1];}
			if($sd[$y][0]=="Locality"){$locality=$sd[$y][1];}
			if($sd[$y][0]=="Rock Type"){$rocktype=$sd[$y][1];}
			if($sd[$y][0]=="Rock Age"){$rockage=$sd[$y][1];}
			if($sd[$y][0]=="Acquisition system"){$acquisitionsystem=$sd[$y][1];}
			if($sd[$y][0]=="Magnification"){$magnification=$sd[$y][1];}
			if($sd[$y][0]=="Radiation Facility"){$radiationfacility=$sd[$y][1];}
			if($sd[$y][0]=="Total Thermal Neutron Fluence"){$totalthermalneutronfluence=$sd[$y][1];}
			if($sd[$y][0]=="Position in Irradiation Canister (#)"){$positioninirradiationcanister=$sd[$y][1];}
			if($sd[$y][0]=="Area of Graticule Square"){$areaofgraticulesquare=$sd[$y][1];}
			if($sd[$y][0]=="No. of Crystals Counted"){$noofcrystalscounted=$sd[$y][1];}
			if($sd[$y][0]=="Zeta Factor"){$zetafactor=$sd[$y][1];}
			if($sd[$y][0]=="Zeta Factor Error (1s)"){$zetafactorerror1s=$sd[$y][1];}
			if($sd[$y][0]=="Rho d"){$rhod=$sd[$y][1];}
			if($sd[$y][0]=="Rho d (% Relative Error)"){$rhodpctrelativeerror=$sd[$y][1];}
			if($sd[$y][0]=="N d"){$nd=$sd[$y][1];}
			if($sd[$y][0]=="Geometry factor (for EDM and Zeta ICPMS methods)"){$geometryfactorforedmandzetaicpmsmethods=$sd[$y][1];}
			if($sd[$y][0]=="Etchant"){$etchant=$sd[$y][1];}
			if($sd[$y][0]=="Etching Conditions"){$etchingconditions=$sd[$y][1];}
			if($sd[$y][0]=="Method - U"){$methodu=$sd[$y][1];}
			if($sd[$y][0]=="ICPMS Model"){$icpmsmodel=$sd[$y][1];}
			if($sd[$y][0]=="Laser Model and Type"){$lasermodelandtype=$sd[$y][1];}
			if($sd[$y][0]=="Instrumental Method References"){$instrumentalmethodreferences=$sd[$y][1];}
			if($sd[$y][0]=="U Calibration Standard"){$ucalibrationstandard=$sd[$y][1];}
			if($sd[$y][0]=="U ppm of Standard"){$uppmofstandard=$sd[$y][1];}
			if($sd[$y][0]=="Specific Denisty of Dated Mineral"){$specificdenistyofdatedmineral=$sd[$y][1];}
			if($sd[$y][0]=="Avagadro Constant"){$avagadroconstant=$sd[$y][1];}
			if($sd[$y][0]=="Registration Factor (Rsp)"){$registrationfactorrsp=$sd[$y][1];}
			if($sd[$y][0]=="Etching Correction Factor (k)"){$etchingcorrectionfactork=$sd[$y][1];}
			if($sd[$y][0]=="ICPMS Zeta Standard"){$icpmszetastandard=$sd[$y][1];}
			if($sd[$y][0]=="Standard Age"){$standardage=$sd[$y][1];}
			if($sd[$y][0]=="Standard Age Error"){$standardageerror=$sd[$y][1];}
			if($sd[$y][0]=="Primary Zeta"){$primaryzeta=$sd[$y][1];}
			if($sd[$y][0]=="1 sigma Uncertainty"){$onesigmauncertainty=$sd[$y][1];}
			if($sd[$y][0]=="Session (Modified) Zeta"){$sessionmodifiedzeta=$sd[$y][1];}
			if($sd[$y][0]=="1 sigma (Modified) Uncertainty"){$onesigmamodifieduncertainty=$sd[$y][1];}
			if($sd[$y][0]=="Comment"){$comment=$sd[$y][1];}
			if($sd[$y][0]=="Chemistry Method"){$chemistrymethod=$sd[$y][1];}
			if($sd[$y][0]=="Chemistry Laboratory"){$chemistrylaboratory=$sd[$y][1];}
			if($sd[$y][0]=="Chemistry Comment"){$chemistrycomment=$sd[$y][1];}

			if($sd[$y][0]=="238U Decay Constant"){$u238decayconstant=$sd[$y][1];}
			if($sd[$y][0]=="238U Decay Constant Error"){$u238decayconstanterror=$sd[$y][1];}
			if($sd[$y][0]=="235U Decay Constant"){$u235decayconstant=$sd[$y][1];}
			if($sd[$y][0]=="235U Decay Constant Error"){$u235decayconstanterror=$sd[$y][1];}
			if($sd[$y][0]=="232Th Decay Constant "){$th232decayconstant=$sd[$y][1];}
			if($sd[$y][0]=="232Th Decay Constant Error"){$th232decayconstanterror=$sd[$y][1];}
			if($sd[$y][0]=="Spontaneous Fission Decay Constant"){$spontaneousfissiondecayconstant=$sd[$y][1];}
			if($sd[$y][0]=="Spontaneous Fission Decay Constant Error"){$spontaneousfissiondecayconstanterror=$sd[$y][1];}
			if($sd[$y][0]=="238U/235U"){$u238_u235=$sd[$y][1];}
			if($sd[$y][0]=="Fission Decay Constant Reference"){$fissiondecayconstantreference=$sd[$y][1];}
			if($sd[$y][0]=="Decay Constant References"){$decayconstantreferences=$sd[$y][1];}
			if($sd[$y][0]=="Decay Constant Comment"){$decayconstantcomment=$sd[$y][1];}

			if($sd[$y][0]=="Analysis Purpose" || $sd[$y][0]=="Additional Analysis Purpose"){$agenum++;eval("\$analysispurpose$agenum=\$sd[\$y][1];");}
			if($sd[$y][0]=="Preferred Age Type" || $sd[$y][0]=="Additional Age Type"){eval("\$preferredagetype$agenum=\$sd[\$y][1];");}
			if($sd[$y][0]=="Pooled Age"){eval("\$pooledage$agenum=\$sd[\$y][1];");}
			if($sd[$y][0]=="Pooled Age Error +95%"){eval("\$pooledageerrorpos$agenum=\$sd[\$y][1];");}
			if($sd[$y][0]=="Pooled Age Error -95%"){eval("\$pooledageerrorneg$agenum=\$sd[\$y][1];");}
			if($sd[$y][0]=="Chi-squared Value"){eval("\$chisquaredvalue$agenum=\$sd[\$y][1];");}
			if($sd[$y][0]=="Degrees of Freedom"){eval("\$degreesoffreedom$agenum=\$sd[\$y][1];");}
			if($sd[$y][0]=="P (Chi-squred) "){eval("\$pchisquared$agenum=\$sd[\$y][1];");}

			if($sd[$y][0]=="Mean Crystal Age"){eval("\$meancrystalage$agenum=\$sd[\$y][1];");}
			if($sd[$y][0]=="Mean Crystal Age Error +95%"){eval("\$meancrystalageerrorpos$agenum=\$sd[\$y][1];");}
			if($sd[$y][0]=="Mean Crystal Age Error -95%"){eval("\$meancrystalageerrorneg$agenum=\$sd[\$y][1];");}

			if($sd[$y][0]=="Central Age"){eval("\$centralage$agenum=\$sd[\$y][1];");}
			if($sd[$y][0]=="Central Age Error +95%"){eval("\$centralageerrorpos$agenum=\$sd[\$y][1];");}
			if($sd[$y][0]=="Central Age Error -95%"){eval("\$centralageerrorneg$agenum=\$sd[\$y][1];");}

			if($sd[$y][0]=="Central Age - Age Dispersion (%)"){eval("\$centralageminusagedispersionpct$agenum=\$sd[\$y][1];");}
			if($sd[$y][0]=="Binomial Age"){eval("\$binomialage$agenum=\$sd[\$y][1];");}
			if($sd[$y][0]=="Binomial Age Error +95%"){eval("\$binomialageerrorplus95pct$agenum=\$sd[\$y][1];");}
			if($sd[$y][0]=="Binomial Age Error -95%"){eval("\$binomialageerrorminus95pct$agenum=\$sd[\$y][1];");}
			if($sd[$y][0]=="Other Age"){eval("\$otherage$agenum=\$sd[\$y][1];");}
			if($sd[$y][0]=="Other Age Error"){eval("\$otherageerror$agenum=\$sd[\$y][1];");}
			if($sd[$y][0]=="Preferred Age Explanation"){eval("\$preferredageexplanation$agenum=\$sd[\$y][1];");}		
			if($sd[$y][0]=="Additional Age Explanation"){eval("\$additionalageexplanation$agenum=\$sd[\$y][1];");}	
			if($sd[$y][0]=="Age Reference"){eval("\$agereference$agenum=\$sd[\$y][1];");}	
	

		}//end for y

		//Check for required values here
		$sampleid=$samplename;

		if($samplename==""){$error.=$errordelim."Sample Name cannot be blank.";$errordelim="<br>";}
		if($uniqueid==""){$error.=$errordelim."IGSN cannot be blank.";$errordelim="<br>";}
		if($labname==""){$error.=$errordelim."Lab Name cannot be blank.";$errordelim="<br>";}
		if($analystname==""){$error.=$errordelim."Analyst Name cannot be blank.";$errordelim="<br>";}
		if($mineral==""){$error.=$errordelim."Mineral cannot be blank.";$errordelim="<br>";}

		if($u238decayconstant==""){$error.=$errordelim."238U Decay Constant cannot be blank.";$errordelim="<br>";}
		if($u235decayconstant==""){$error.=$errordelim."235U Decay Constant cannot be blank.";$errordelim="<br>";}
		if($th232decayconstant==""){$error.=$errordelim."232Th Decay Constant  cannot be blank.";$errordelim="<br>";}
		if($u238_u235==""){$error.=$errordelim."238U/235U cannot be blank.";$errordelim="<br>";}
		if($decayconstantreferences==""){$error.=$errordelim."Decay Constant References cannot be blank.";$errordelim="<br>";}

		if($analysispurpose1==""){$error.=$errordelim."Analysis Purpose cannot be blank.";$errordelim="<br>";}

	}//end if error==""

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

	if($error==""){
		
		//let's put it in the database
		
		$geochron_pkey=$db->get_var("select nextval('geochron_seq')");
		
		$sample_pkey=$db->get_var("select nextval('sample_seq')");
		
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

		if($analysispurpose1=="DetritalSpectrum"){$isupstream="TRUE";}
		if($analysispurpose2=="DetritalSpectrum"){$isupstream="TRUE";}
		if($analysispurpose3=="DetritalSpectrum"){$isupstream="TRUE";}
		if($analysispurpose4=="DetritalSpectrum"){$isupstream="TRUE";}
		if($analysispurpose5=="DetritalSpectrum"){$isupstream="TRUE";}
		if($analysispurpose6=="DetritalSpectrum"){$isupstream="TRUE";}
		if($analysispurpose7=="DetritalSpectrum"){$isupstream="TRUE";}
		if($analysispurpose8=="DetritalSpectrum"){$isupstream="TRUE";}
		if($analysispurpose9=="DetritalSpectrum"){$isupstream="TRUE";}
		if($analysispurpose10=="DetritalSpectrum"){$isupstream="TRUE";}
		
		
		if($analysispurpose1!=""){
			
			$sampleagepkey=$db->get_var("select nextval('sample_age_seq')");
		
			
		
			$query="insert into sample_age (
				sample_age_pkey,
				sample_pkey,
				age_name,";
			if($pooledage1!=""){
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
			if($pooledage1!=""){
				$query.="
				$pooledage1,";
			}
			$query.="
				'ABS',
				'$pooledageerror1',
				'',
				'1',
				'$preferredagetype1'
			)
			";
			
			$db->query($query);
			//echo nl2br($query);exit();
		}


		if($analysispurpose2!=""){
			
			$sampleagepkey=$db->get_var("select nextval('sample_age_seq')");
		
			
		
			$query="insert into sample_age (
				sample_age_pkey,
				sample_pkey,
				age_name,";
			if($pooledage2!=""){
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
			if($pooledage2!=""){
				$query.="
				$pooledage2,";
			}
			$query.="
				'ABS',
				'$pooledageerror2',
				'',
				'0',
				'$preferredagetype2'
			)
			";
			
			$db->query($query);
			//echo nl2br($query);exit();
		}

		if($analysispurpose3!=""){
			
			$sampleagepkey=$db->get_var("select nextval('sample_age_seq')");
		
			
		
			$query="insert into sample_age (
				sample_age_pkey,
				sample_pkey,
				age_name,";
			if($pooledage3!=""){
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
			if($pooledage3!=""){
				$query.="
				$pooledage3,";
			}
			$query.="
				'ABS',
				'$pooledageerror3',
				'',
				'0',
				'$preferredagetype3'
			)
			";
			
			$db->query($query);
			//echo nl3br($query);exit();
		}

		if($analysispurpose4!=""){
			
			$sampleagepkey=$db->get_var("select nextval('sample_age_seq')");
		
			
		
			$query="insert into sample_age (
				sample_age_pkey,
				sample_pkey,
				age_name,";
			if($pooledage4!=""){
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
			if($pooledage4!=""){
				$query.="
				$pooledage4,";
			}
			$query.="
				'ABS',
				'$pooledageerror4',
				'',
				'0',
				'$preferredagetype4'
			)
			";
			
			$db->query($query);
			//echo nl4br($query);exit();
		}

		if($analysispurpose5!=""){
			
			$sampleagepkey=$db->get_var("select nextval('sample_age_seq')");
		
			
		
			$query="insert into sample_age (
				sample_age_pkey,
				sample_pkey,
				age_name,";
			if($pooledage5!=""){
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
			if($pooledage5!=""){
				$query.="
				$pooledage5,";
			}
			$query.="
				'ABS',
				'$pooledageerror5',
				'',
				'0',
				'$preferredagetype5'
			)
			";
			
			$db->query($query);
			//echo nl5br($query);exit();
		}

		if($analysispurpose6!=""){
			
			$sampleagepkey=$db->get_var("select nextval('sample_age_seq')");
		
			
		
			$query="insert into sample_age (
				sample_age_pkey,
				sample_pkey,
				age_name,";
			if($pooledage6!=""){
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
			if($pooledage6!=""){
				$query.="
				$pooledage6,";
			}
			$query.="
				'ABS',
				'$pooledageerror6',
				'',
				'0',
				'$preferredagetype6'
			)
			";
			
			$db->query($query);
			//echo nl6br($query);exit();
		}

		if($analysispurpose7!=""){
			
			$sampleagepkey=$db->get_var("select nextval('sample_age_seq')");
		
			
		
			$query="insert into sample_age (
				sample_age_pkey,
				sample_pkey,
				age_name,";
			if($pooledage7!=""){
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
			if($pooledage7!=""){
				$query.="
				$pooledage7,";
			}
			$query.="
				'ABS',
				'$pooledageerror7',
				'',
				'0',
				'$preferredagetype7'
			)
			";
			
			$db->query($query);
			//echo nl7br($query);exit();
		}

		if($analysispurpose8!=""){
			
			$sampleagepkey=$db->get_var("select nextval('sample_age_seq')");
		
			
		
			$query="insert into sample_age (
				sample_age_pkey,
				sample_pkey,
				age_name,";
			if($pooledage8!=""){
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
			if($pooledage8!=""){
				$query.="
				$pooledage8,";
			}
			$query.="
				'ABS',
				'$pooledageerror8',
				'',
				'0',
				'$preferredagetype8'
			)
			";
			
			$db->query($query);
			//echo nl8br($query);exit();
		}

		if($analysispurpose9!=""){
			
			$sampleagepkey=$db->get_var("select nextval('sample_age_seq')");
		
			
		
			$query="insert into sample_age (
				sample_age_pkey,
				sample_pkey,
				age_name,";
			if($pooledage9!=""){
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
			if($pooledage9!=""){
				$query.="
				$pooledage9,";
			}
			$query.="
				'ABS',
				'$pooledageerror9',
				'',
				'0',
				'$preferredagetype9'
			)
			";
			
			$db->query($query);
			//echo nl9br($query);exit();
		}

		if($analysispurpose10!=""){
			
			$sampleagepkey=$db->get_var("select nextval('sample_age_seq')");
		
			
		
			$query="insert into sample_age (
				sample_age_pkey,
				sample_pkey,
				age_name,";
			if($pooledage10!=""){
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
			if($pooledage10!=""){
				$query.="
				$pooledage10,";
			}
			$query.="
				'ABS',
				'$pooledageerror10',
				'',
				'0',
				'$preferredagetype10'
			)
			";
			
			$db->query($query);
			//echo nl10br($query);exit();
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
					'$orig_name',
					'fissiontrack',";
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
					'$analysispurpose1'
					)";
					
					
		//echo nl2br($querystring);exit();
		
		$db->query($querystring);


		//start whole xml file
		$wholexml="<sample>\n\t<sampleinfo>\n";
		
		if($samplename!=""){$wholexml.="\t\t<samplename>".htmlspecialchars($samplename)."</samplename>\n";}
		if($uniqueid!=""){$wholexml.="\t\t<uniqueid>".htmlspecialchars($uniqueid)."</uniqueid>\n";}
		if($labname!=""){$wholexml.="\t\t<labname>".htmlspecialchars($labname)."</labname>\n";}
		if($analystname!=""){$wholexml.="\t\t<analystname>".htmlspecialchars($analystname)."</analystname>\n";}
		if($mineral!=""){$wholexml.="\t\t<mineral>".htmlspecialchars($mineral)."</mineral>\n";}
		if($dosimeterglass!=""){$wholexml.="\t\t<dosimeterglass>".htmlspecialchars($dosimeterglass)."</dosimeterglass>\n";}
		if($dosimeterglassuppm!=""){$wholexml.="\t\t<dosimeterglassuppm>".htmlspecialchars($dosimeterglassuppm)."</dosimeterglassuppm>\n";}
		if($irradiation!=""){$wholexml.="\t\t<irradiation>".htmlspecialchars($irradiation)."</irradiation>\n";}
		if($countdate!=""){$wholexml.="\t\t<countdate>".htmlspecialchars($countdate)."</countdate>\n";}
		if($locality!=""){$wholexml.="\t\t<locality>".htmlspecialchars($locality)."</locality>\n";}
		if($rocktype!=""){$wholexml.="\t\t<rocktype>".htmlspecialchars($rocktype)."</rocktype>\n";}
		if($rockage!=""){$wholexml.="\t\t<rockage>".htmlspecialchars($rockage)."</rockage>\n";}
		if($acquisitionsystem!=""){$wholexml.="\t\t<acquisitionsystem>".htmlspecialchars($acquisitionsystem)."</acquisitionsystem>\n";}
		if($magnification!=""){$wholexml.="\t\t<magnification>".htmlspecialchars($magnification)."</magnification>\n";}
		if($radiationfacility!=""){$wholexml.="\t\t<radiationfacility>".htmlspecialchars($radiationfacility)."</radiationfacility>\n";}
		if($totalthermalneutronfluence!=""){$wholexml.="\t\t<totalthermalneutronfluence>".htmlspecialchars($totalthermalneutronfluence)."</totalthermalneutronfluence>\n";}
		if($positioninirradiationcanister!=""){$wholexml.="\t\t<positioninirradiationcanister>".htmlspecialchars($positioninirradiationcanister)."</positioninirradiationcanister>\n";}
		if($areaofgraticulesquare!=""){$wholexml.="\t\t<areaofgraticulesquare>".htmlspecialchars($areaofgraticulesquare)."</areaofgraticulesquare>\n";}
		if($noofcrystalscounted!=""){$wholexml.="\t\t<noofcrystalscounted>".htmlspecialchars($noofcrystalscounted)."</noofcrystalscounted>\n";}
		if($zetafactor!=""){$wholexml.="\t\t<zetafactor>".htmlspecialchars($zetafactor)."</zetafactor>\n";}
		if($zetafactorerror1s!=""){$wholexml.="\t\t<zetafactorerror1s>".htmlspecialchars($zetafactorerror1s)."</zetafactorerror1s>\n";}
		if($rhod!=""){$wholexml.="\t\t<rhod>".htmlspecialchars($rhod)."</rhod>\n";}
		if($rhodpctrelativeerror!=""){$wholexml.="\t\t<rhodpctrelativeerror>".htmlspecialchars($rhodpctrelativeerror)."</rhodpctrelativeerror>\n";}
		if($nd!=""){$wholexml.="\t\t<nd>".htmlspecialchars($nd)."</nd>\n";}
		if($geometryfactorforedmandzetaicpmsmethods!=""){$wholexml.="\t\t<geometryfactorforedmandzetaicpmsmethods>".htmlspecialchars($geometryfactorforedmandzetaicpmsmethods)."</geometryfactorforedmandzetaicpmsmethods>\n";}
		if($etchant!=""){$wholexml.="\t\t<etchant>".htmlspecialchars($etchant)."</etchant>\n";}
		if($etchingconditions!=""){$wholexml.="\t\t<etchingconditions>".htmlspecialchars($etchingconditions)."</etchingconditions>\n";}
		if($methodu!=""){$wholexml.="\t\t<methodu>".htmlspecialchars($methodu)."</methodu>\n";}
		if($icpmsmodel!=""){$wholexml.="\t\t<icpmsmodel>".htmlspecialchars($icpmsmodel)."</icpmsmodel>\n";}
		if($lasermodelandtype!=""){$wholexml.="\t\t<lasermodelandtype>".htmlspecialchars($lasermodelandtype)."</lasermodelandtype>\n";}
		if($instrumentalmethodreferences!=""){$wholexml.="\t\t<instrumentalmethodreferences>".htmlspecialchars($instrumentalmethodreferences)."</instrumentalmethodreferences>\n";}
		if($ucalibrationstandard!=""){$wholexml.="\t\t<ucalibrationstandard>".htmlspecialchars($ucalibrationstandard)."</ucalibrationstandard>\n";}
		if($uppmofstandard!=""){$wholexml.="\t\t<uppmofstandard>".htmlspecialchars($uppmofstandard)."</uppmofstandard>\n";}
		if($specificdenistyofdatedmineral!=""){$wholexml.="\t\t<specificdenistyofdatedmineral>".htmlspecialchars($specificdenistyofdatedmineral)."</specificdenistyofdatedmineral>\n";}
		if($avagadroconstant!=""){$wholexml.="\t\t<avagadroconstant>".htmlspecialchars($avagadroconstant)."</avagadroconstant>\n";}
		if($registrationfactorrsp!=""){$wholexml.="\t\t<registrationfactorrsp>".htmlspecialchars($registrationfactorrsp)."</registrationfactorrsp>\n";}
		if($etchingcorrectionfactork!=""){$wholexml.="\t\t<etchingcorrectionfactork>".htmlspecialchars($etchingcorrectionfactork)."</etchingcorrectionfactork>\n";}
		if($icpmszetastandard!=""){$wholexml.="\t\t<icpmszetastandard>".htmlspecialchars($icpmszetastandard)."</icpmszetastandard>\n";}
		if($standardage!=""){$wholexml.="\t\t<standardage>".htmlspecialchars($standardage)."</standardage>\n";}
		if($standardageerror!=""){$wholexml.="\t\t<standardageerror>".htmlspecialchars($standardageerror)."</standardageerror>\n";}
		if($primaryzeta!=""){$wholexml.="\t\t<primaryzeta>".htmlspecialchars($primaryzeta)."</primaryzeta>\n";}
		if($onesigmauncertainty!=""){$wholexml.="\t\t<onesigmauncertainty>".htmlspecialchars($onesigmauncertainty)."</onesigmauncertainty>\n";}
		if($sessionmodifiedzeta!=""){$wholexml.="\t\t<sessionmodifiedzeta>".htmlspecialchars($sessionmodifiedzeta)."</sessionmodifiedzeta>\n";}
		if($onesigmamodifieduncertainty!=""){$wholexml.="\t\t<onesigmamodifieduncertainty>".htmlspecialchars($onesigmamodifieduncertainty)."</onesigmamodifieduncertainty>\n";}
		if($comment!=""){$wholexml.="\t\t<comment>".htmlspecialchars($comment)."</comment>\n";}
		if($chemistrymethod!=""){$wholexml.="\t\t<chemistrymethod>".htmlspecialchars($chemistrymethod)."</chemistrymethod>\n";}
		if($chemistrylaboratory!=""){$wholexml.="\t\t<chemistrylaboratory>".htmlspecialchars($chemistrylaboratory)."</chemistrylaboratory>\n";}
		if($chemistrycomment!=""){$wholexml.="\t\t<chemistrycomment>".htmlspecialchars($chemistrycomment)."</chemistrycomment>\n";}
		if($u238decayconstant!=""){$wholexml.="\t\t<u238decayconstant>".htmlspecialchars($u238decayconstant)."</u238decayconstant>\n";}
		if($u238decayconstanterror!=""){$wholexml.="\t\t<u238decayconstanterror>".htmlspecialchars($u238decayconstanterror)."</u238decayconstanterror>\n";}
		if($u235decayconstant!=""){$wholexml.="\t\t<u235decayconstant>".htmlspecialchars($u235decayconstant)."</u235decayconstant>\n";}
		if($u235decayconstanterror!=""){$wholexml.="\t\t<u235decayconstanterror>".htmlspecialchars($u235decayconstanterror)."</u235decayconstanterror>\n";}
		if($th232decayconstant!=""){$wholexml.="\t\t<th232decayconstant>".htmlspecialchars($th232decayconstant)."</th232decayconstant>\n";}
		if($th232decayconstanterror!=""){$wholexml.="\t\t<th232decayconstanterror>".htmlspecialchars($th232decayconstanterror)."</th232decayconstanterror>\n";}
		if($spontaneousfissiondecayconstant!=""){$wholexml.="\t\t<spontaneousfissiondecayconstant>".htmlspecialchars($spontaneousfissiondecayconstant)."</spontaneousfissiondecayconstant>\n";}
		if($spontaneousfissiondecayconstanterror!=""){$wholexml.="\t\t<spontaneousfissiondecayconstanterror>".htmlspecialchars($spontaneousfissiondecayconstanterror)."</spontaneousfissiondecayconstanterror>\n";}
		if($u238_u235!=""){$wholexml.="\t\t<u238_u235>".htmlspecialchars($u238_u235)."</u238_u235>\n";}
		if($fissiondecayconstantreference!=""){$wholexml.="\t\t<fissiondecayconstantreference>".htmlspecialchars($fissiondecayconstantreference)."</fissiondecayconstantreference>\n";}
		if($decayconstantreferences!=""){$wholexml.="\t\t<decayconstantreferences>".htmlspecialchars($decayconstantreferences)."</decayconstantreferences>\n";}
		if($decayconstantcomment!=""){$wholexml.="\t\t<decayconstantcomment>".htmlspecialchars($decayconstantcomment)."</decayconstantcomment>\n";}


		$wholexml.="\t</sampleinfo>\n";

		$wholexml.="\t<samplemetadata>\n";
		
		$wholexml.="\t\t<sampleid>".htmlspecialchars($isampleid)."</sampleid>\n";
		$wholexml.="\t\t<agemin>".htmlspecialchars($iagemin)."</agemin>\n";
		$wholexml.="\t\t<agemax>".htmlspecialchars($iagemax)."</agemax>\n";
		$wholexml.="\t\t<sampledescription>".htmlspecialchars($isampledescription)."</sampledescription>\n";
		$wholexml.="\t\t<geoobjecttype>".htmlspecialchars($igeoobjecttype)."</geoobjecttype>\n";
		$wholexml.="\t\t<geoobjectclassification>".htmlspecialchars($igeoobjectclassification)."</geoobjectclassification>\n";
		$wholexml.="\t\t<collectionmethod>".htmlspecialchars($icollectionmethod)."</collectionmethod>\n";
		$wholexml.="\t\t<material>".htmlspecialchars($imaterial)."</material>\n";
		$wholexml.="\t\t<latitude>".htmlspecialchars($ilatitude)."</latitude>\n";
		$wholexml.="\t\t<longitude>".htmlspecialchars($ilongitude)."</longitude>\n";
		$wholexml.="\t\t<samplecomment>".htmlspecialchars($isamplecomment)."</samplecomment>\n";
		$wholexml.="\t\t<collector>".htmlspecialchars($icollector)."</collector>\n";
		$wholexml.="\t\t<materialclassification>".htmlspecialchars($imaterialclassification)."</materialclassification>\n";
		$wholexml.="\t\t<PrimaryLocationName>".htmlspecialchars($iPrimaryLocationName)."</PrimaryLocationName>\n";
		$wholexml.="\t\t<PrimaryLocationType>".htmlspecialchars($iPrimaryLocationType)."</PrimaryLocationType>\n";
		$wholexml.="\t\t<LocationDescription>".htmlspecialchars($iLocationDescription)."</LocationDescription>\n";
		$wholexml.="\t\t<Locality>".htmlspecialchars($iLocality)."</Locality>\n";
		$wholexml.="\t\t<LocalityDescription>".htmlspecialchars($iLocalityDescription)."</LocalityDescription>\n";
		$wholexml.="\t\t<Country>".htmlspecialchars($iCountry)."</Country>\n";
		$wholexml.="\t\t<Provice>".htmlspecialchars($iProvice)."</Provice>\n";
		$wholexml.="\t\t<County>".htmlspecialchars($iCounty)."</County>\n";
		$wholexml.="\t\t<CityOrTownship>".htmlspecialchars($iCityOrTownship)."</CityOrTownship>\n";
		$wholexml.="\t\t<Platform>".htmlspecialchars($iPlatform)."</Platform>\n";
		$wholexml.="\t\t<PlatformID>".htmlspecialchars($iPlatformID)."</PlatformID>\n";
		$wholexml.="\t\t<OriginalArchivalInstitution>".htmlspecialchars($iOriginalArchivalInstitution)."</OriginalArchivalInstitution>\n";
		$wholexml.="\t\t<OriginalArchivalContact>".htmlspecialchars($iOriginalArchivalContact)."</OriginalArchivalContact>\n";
		$wholexml.="\t\t<MostRecentArchivalInstitution>".htmlspecialchars($iMostRecentArchivalInstitution)."</MostRecentArchivalInstitution>\n";
		$wholexml.="\t\t<MostRecentArchivalContact>".htmlspecialchars($iMostRecentArchivalContact)."</MostRecentArchivalContact>\n";
		
		$wholexml.="\t</samplemetadata>\n";

		$wholexml.="\t<ages>\n";

		if($analysispurpose1!=""){$wholexml.="\t\t<age analysispurpose=\"$analysispurpose1\" preferredagetype=\"$preferredagetype1\" pooledage=\"$pooledage1\" pooledageerrorpos=\"$pooledageerrorpos1\" pooledageerrorneg=\"$pooledageerrorneg1\" chisquaredvalue=\"$chisquaredvalue1\" degreesoffreedom=\"$degreesoffreedom1\" pchisquared=\"$pchisquared1\" meancrystalage=\"$meancrystalage1\" meancrystalageerrorpos=\"$meancrystalageerrorpos1\" meancrystalageerrorneg=\"$meancrystalageerrorneg1\" centralage=\"$centralage1\" centralageerrorpos=\"$centralageerrorpos1\" centralageerrorneg=\"$centralageerrorneg1\" centralageminusagedispersionpct=\"$centralageminusagedispersionpct1\" binomialage=\"$binomialage1\" binomialageerrorplus95pct=\"$binomialageerrorplus95pct1\" binomialageerrorminus95pct=\"$binomialageerrorminus95pct1\" otherage=\"$otherage1\" otherageerror=\"$otherageerror1\" preferredageexplanation=\"$preferredageexplanation1\" agereference=\"".htmlspecialchars($agereference1)."\" />\n";}
		if($analysispurpose2!=""){$wholexml.="\t\t<age analysispurpose=\"$analysispurpose2\" preferredagetype=\"$preferredagetype2\" pooledage=\"$pooledage2\" pooledageerrorpos=\"$pooledageerrorpos2\" pooledageerrorneg=\"$pooledageerrorneg2\" chisquaredvalue=\"$chisquaredvalue2\" degreesoffreedom=\"$degreesoffreedom2\" pchisquared=\"$pchisquared2\" meancrystalage=\"$meancrystalage2\" meancrystalageerrorpos=\"$meancrystalageerrorpos2\" meancrystalageerrorneg=\"$meancrystalageerrorneg2\" centralage=\"$centralage2\" centralageerrorpos=\"$centralageerrorpos2\" centralageerrorneg=\"$centralageerrorneg2\" centralageminusagedispersionpct=\"$centralageminusagedispersionpct2\" binomialage=\"$binomialage2\" binomialageerrorplus95pct=\"$binomialageerrorplus95pct2\" binomialageerrorminus95pct=\"$binomialageerrorminus95pct2\" otherage=\"$otherage2\" otherageerror=\"$otherageerror2\" preferredageexplanation=\"$additionalageexplanation2\" agereference=\"".htmlspecialchars($agereference2)."\" />\n";}
		if($analysispurpose3!=""){$wholexml.="\t\t<age analysispurpose=\"$analysispurpose3\" preferredagetype=\"$preferredagetype3\" pooledage=\"$pooledage3\" pooledageerrorpos=\"$pooledageerrorpos3\" pooledageerrorneg=\"$pooledageerrorneg3\" chisquaredvalue=\"$chisquaredvalue3\" degreesoffreedom=\"$degreesoffreedom3\" pchisquared=\"$pchisquared3\" meancrystalage=\"$meancrystalage3\" meancrystalageerrorpos=\"$meancrystalageerrorpos3\" meancrystalageerrorneg=\"$meancrystalageerrorneg3\" centralage=\"$centralage3\" centralageerrorpos=\"$centralageerrorpos3\" centralageerrorneg=\"$centralageerrorneg3\" centralageminusagedispersionpct=\"$centralageminusagedispersionpct3\" binomialage=\"$binomialage3\" binomialageerrorplus95pct=\"$binomialageerrorplus95pct3\" binomialageerrorminus95pct=\"$binomialageerrorminus95pct3\" otherage=\"$otherage3\" otherageerror=\"$otherageerror3\" preferredageexplanation=\"$additionalageexplanation3\" agereference=\"".htmlspecialchars($agereference3)."\" />\n";}
		if($analysispurpose4!=""){$wholexml.="\t\t<age analysispurpose=\"$analysispurpose4\" preferredagetype=\"$preferredagetype4\" pooledage=\"$pooledage4\" pooledageerrorpos=\"$pooledageerrorpos4\" pooledageerrorneg=\"$pooledageerrorneg4\" chisquaredvalue=\"$chisquaredvalue4\" degreesoffreedom=\"$degreesoffreedom4\" pchisquared=\"$pchisquared4\" meancrystalage=\"$meancrystalage4\" meancrystalageerrorpos=\"$meancrystalageerrorpos4\" meancrystalageerrorneg=\"$meancrystalageerrorneg4\" centralage=\"$centralage4\" centralageerrorpos=\"$centralageerrorpos4\" centralageerrorneg=\"$centralageerrorneg4\" centralageminusagedispersionpct=\"$centralageminusagedispersionpct4\" binomialage=\"$binomialage4\" binomialageerrorplus95pct=\"$binomialageerrorplus95pct4\" binomialageerrorminus95pct=\"$binomialageerrorminus95pct4\" otherage=\"$otherage4\" otherageerror=\"$otherageerror4\" preferredageexplanation=\"$additionalageexplanation4\" agereference=\"".htmlspecialchars($agereference4)."\" />\n";}
		if($analysispurpose5!=""){$wholexml.="\t\t<age analysispurpose=\"$analysispurpose5\" preferredagetype=\"$preferredagetype5\" pooledage=\"$pooledage5\" pooledageerrorpos=\"$pooledageerrorpos5\" pooledageerrorneg=\"$pooledageerrorneg5\" chisquaredvalue=\"$chisquaredvalue5\" degreesoffreedom=\"$degreesoffreedom5\" pchisquared=\"$pchisquared5\" meancrystalage=\"$meancrystalage5\" meancrystalageerrorpos=\"$meancrystalageerrorpos5\" meancrystalageerrorneg=\"$meancrystalageerrorneg5\" centralage=\"$centralage5\" centralageerrorpos=\"$centralageerrorpos5\" centralageerrorneg=\"$centralageerrorneg5\" centralageminusagedispersionpct=\"$centralageminusagedispersionpct5\" binomialage=\"$binomialage5\" binomialageerrorplus95pct=\"$binomialageerrorplus95pct5\" binomialageerrorminus95pct=\"$binomialageerrorminus95pct5\" otherage=\"$otherage5\" otherageerror=\"$otherageerror5\" preferredageexplanation=\"$additionalageexplanation5\" agereference=\"".htmlspecialchars($agereference5)."\" />\n";}
		if($analysispurpose6!=""){$wholexml.="\t\t<age analysispurpose=\"$analysispurpose6\" preferredagetype=\"$preferredagetype6\" pooledage=\"$pooledage6\" pooledageerrorpos=\"$pooledageerrorpos6\" pooledageerrorneg=\"$pooledageerrorneg6\" chisquaredvalue=\"$chisquaredvalue6\" degreesoffreedom=\"$degreesoffreedom6\" pchisquared=\"$pchisquared6\" meancrystalage=\"$meancrystalage6\" meancrystalageerrorpos=\"$meancrystalageerrorpos6\" meancrystalageerrorneg=\"$meancrystalageerrorneg6\" centralage=\"$centralage6\" centralageerrorpos=\"$centralageerrorpos6\" centralageerrorneg=\"$centralageerrorneg6\" centralageminusagedispersionpct=\"$centralageminusagedispersionpct6\" binomialage=\"$binomialage6\" binomialageerrorplus95pct=\"$binomialageerrorplus95pct6\" binomialageerrorminus95pct=\"$binomialageerrorminus95pct6\" otherage=\"$otherage6\" otherageerror=\"$otherageerror6\" preferredageexplanation=\"$additionalageexplanation6\" agereference=\"".htmlspecialchars($agereference6)."\" />\n";}
		if($analysispurpose7!=""){$wholexml.="\t\t<age analysispurpose=\"$analysispurpose7\" preferredagetype=\"$preferredagetype7\" pooledage=\"$pooledage7\" pooledageerrorpos=\"$pooledageerrorpos7\" pooledageerrorneg=\"$pooledageerrorneg7\" chisquaredvalue=\"$chisquaredvalue7\" degreesoffreedom=\"$degreesoffreedom7\" pchisquared=\"$pchisquared7\" meancrystalage=\"$meancrystalage7\" meancrystalageerrorpos=\"$meancrystalageerrorpos7\" meancrystalageerrorneg=\"$meancrystalageerrorneg7\" centralage=\"$centralage7\" centralageerrorpos=\"$centralageerrorpos7\" centralageerrorneg=\"$centralageerrorneg7\" centralageminusagedispersionpct=\"$centralageminusagedispersionpct7\" binomialage=\"$binomialage7\" binomialageerrorplus95pct=\"$binomialageerrorplus95pct7\" binomialageerrorminus95pct=\"$binomialageerrorminus95pct7\" otherage=\"$otherage7\" otherageerror=\"$otherageerror7\" preferredageexplanation=\"$additionalageexplanation7\" agereference=\"".htmlspecialchars($agereference7)."\" />\n";}
		if($analysispurpose8!=""){$wholexml.="\t\t<age analysispurpose=\"$analysispurpose8\" preferredagetype=\"$preferredagetype8\" pooledage=\"$pooledage8\" pooledageerrorpos=\"$pooledageerrorpos8\" pooledageerrorneg=\"$pooledageerrorneg8\" chisquaredvalue=\"$chisquaredvalue8\" degreesoffreedom=\"$degreesoffreedom8\" pchisquared=\"$pchisquared8\" meancrystalage=\"$meancrystalage8\" meancrystalageerrorpos=\"$meancrystalageerrorpos8\" meancrystalageerrorneg=\"$meancrystalageerrorneg8\" centralage=\"$centralage8\" centralageerrorpos=\"$centralageerrorpos8\" centralageerrorneg=\"$centralageerrorneg8\" centralageminusagedispersionpct=\"$centralageminusagedispersionpct8\" binomialage=\"$binomialage8\" binomialageerrorplus95pct=\"$binomialageerrorplus95pct8\" binomialageerrorminus95pct=\"$binomialageerrorminus95pct8\" otherage=\"$otherage8\" otherageerror=\"$otherageerror8\" preferredageexplanation=\"$additionalageexplanation8\" agereference=\"".htmlspecialchars($agereference8)."\" />\n";}
		if($analysispurpose9!=""){$wholexml.="\t\t<age analysispurpose=\"$analysispurpose9\" preferredagetype=\"$preferredagetype9\" pooledage=\"$pooledage9\" pooledageerrorpos=\"$pooledageerrorpos9\" pooledageerrorneg=\"$pooledageerrorneg9\" chisquaredvalue=\"$chisquaredvalue9\" degreesoffreedom=\"$degreesoffreedom9\" pchisquared=\"$pchisquared9\" meancrystalage=\"$meancrystalage9\" meancrystalageerrorpos=\"$meancrystalageerrorpos9\" meancrystalageerrorneg=\"$meancrystalageerrorneg9\" centralage=\"$centralage9\" centralageerrorpos=\"$centralageerrorpos9\" centralageerrorneg=\"$centralageerrorneg9\" centralageminusagedispersionpct=\"$centralageminusagedispersionpct9\" binomialage=\"$binomialage9\" binomialageerrorplus95pct=\"$binomialageerrorplus95pct9\" binomialageerrorminus95pct=\"$binomialageerrorminus95pct9\" otherage=\"$otherage9\" otherageerror=\"$otherageerror9\" preferredageexplanation=\"$additionalageexplanation9\" agereference=\"".htmlspecialchars($agereference9)."\" />\n";}
		if($analysispurpose10!=""){$wholexml.="\t\t<age analysispurpose=\"$analysispurpose10\" preferredagetype=\"$preferredagetype10\" pooledage=\"$pooledage10\" pooledageerrorpos=\"$pooledageerrorpos10\" pooledageerrorneg=\"$pooledageerrorneg10\" chisquaredvalue=\"$chisquaredvalue10\" degreesoffreedom=\"$degreesoffreedom10\" pchisquared=\"$pchisquared10\" meancrystalage=\"$meancrystalage10\" meancrystalageerrorpos=\"$meancrystalageerrorpos10\" meancrystalageerrorneg=\"$meancrystalageerrorneg10\" centralage=\"$centralage10\" centralageerrorpos=\"$centralageerrorpos10\" centralageerrorneg=\"$centralageerrorneg10\" centralageminusagedispersionpct=\"$centralageminusagedispersionpct10\" binomialage=\"$binomialage10\" binomialageerrorplus105pct=\"$binomialageerrorplus105pct10\" binomialageerrorminus105pct=\"$binomialageerrorminus105pct10\" otherage=\"$otherage10\" otherageerror=\"$otherageerror10\" preferredageexplanation=\"$additionalageexplanation10\" agereference=\"".htmlspecialchars($agereference10)."\" />\n";}






		$wholexml.="\t</ages>\n";
		
		//Now add section for Apatite Ages

		
		
		$objPHPExcel->setActiveSheetIndex($apatiteagesheetnum);

		//$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
		$ad = $objPHPExcel->getActiveSheet()->toArray();
		


		//find max x on row 8
		$maxx=0;
		for($x=0;$x<200;$x++){
			if($ad[8][$x]!=""){$maxx=$x;}
		}
		
		//find maxy in column 0
		$maxy=0;
		for($y=0;$y<200;$y++){
			if($ad[$y][0]!=""){$maxy=$y;}
		}
		
		//Look at row 8 to maxx to get column numbers
		for($x=0;$x<=$maxx;$x++){
			if($ad[8][$x]=="Grain ID"){$grainidcolnum=$x;}
			if($ad[8][$x]=="N s"){$nscolnum=$x;}
			if($ad[8][$x]=="N i"){$nicolnum=$x;}
			if($ad[8][$x]=="Na"){$nacolnum=$x;}
			if($ad[8][$x]=="Dpar"){$dparcolnum=$x;}
			if($ad[8][$x]=="Dper"){$dpercolnum=$x;}
			if($ad[8][$x]=="Rmr0"){$rmr0colnum=$x;}
			if($ad[8][$x]=="Rho s"){$rhoscolnum=$x;}
			if($ad[8][$x]=="Rho i"){$rhoicolnum=$x;}
			if($ad[8][$x]=="Rho s / Rho i"){$rhosrhoicolnum=$x;}
			if($ad[8][$x]=="Area (Ω)"){$areacolnum=$x;}
			if($ad[8][$x]=="Number of Etch Figures (Dpar, Dper) Measured"){$ofetchfigurescolnum=$x;}
			if($ad[8][$x]=="238U/43Ca"){$u238ca43colnum=$x;}
			if($ad[8][$x]=="error (1σ)"){$error1scolnum=$x;}
			if($ad[8][$x]=="U ppm"){$uppmcolnum=$x;}
			if($ad[8][$x]=="U error (1s)"){$uerror1scolnum=$x;}
			if($ad[8][$x]=="Age (Ma)"){$agemacolnum=$x;}
			if($ad[8][$x]=="Age Error +1s"){$ageerrorplus1scolnum=$x;}
			if($ad[8][$x]=="Age Error -1s"){$ageerrorminus1scolnum=$x;}
			if($ad[8][$x]=="CaO"){$caocolnum=$x;}
			if($ad[8][$x]=="P2O5"){$p2o5colnum=$x;}
			if($ad[8][$x]=="F"){$fcolnum=$x;}
			if($ad[8][$x]=="Cl"){$clcolnum=$x;}
			if($ad[8][$x]=="SrO"){$srocolnum=$x;}
			if($ad[8][$x]=="BaO"){$baocolnum=$x;}
			if($ad[8][$x]=="SiO2"){$si02colnum=$x;}
			if($ad[8][$x]=="Na2O"){$na2ocolnum=$x;}
			if($ad[8][$x]=="CeO2"){$ceo2colnum=$x;}
			if($ad[8][$x]=="FeO"){$feocolnum=$x;}
			if($ad[8][$x]=="Total"){$totalcolnum=$x;}
		}

		//get units
		$caounits=$ad[9][$caocolnum];
		$p2o5units=$ad[9][$p2o5colnum];
		$funits=$ad[9][$fcolnum];
		$clunits=$ad[9][$clcolnum];
		$srounits=$ad[9][$srocolnum];
		$baounits=$ad[9][$baocolnum];
		$si02units=$ad[9][$si02colnum];
		$na2ounits=$ad[9][$na2ocolnum];
		$ceo2units=$ad[9][$ceo2colnum];
		$feounits=$ad[9][$feocolnum];
		$totalunits=$ad[9][$totalcolnum];
		
		$wholexml.="\t<apatiteages ";
		$wholexml.="caounits=\"$caounits\" ";
		$wholexml.="p2o5units=\"$p2o5units\" ";
		$wholexml.="funits=\"$funits\" ";
		$wholexml.="clunits=\"$clunits\" ";
		$wholexml.="srounits=\"$srounits\" ";
		$wholexml.="baounits=\"$baounits\" ";
		$wholexml.="si02units=\"$si02units\" ";
		$wholexml.="na2ounits=\"$na2ounits\" ";
		$wholexml.="ceo2units=\"$ceo2units\" ";
		$wholexml.="feounits=\"$feounits\" ";
		$wholexml.="totalunits=\"$totalunits\" ";
		$wholexml.=">\n";
		
		//loop over rows and build XML
		for($y=10;$y<=$maxy;$y++){
			if($ad[$y][$grainidcolnum]!=""){
				//OK, put this one in
				
				$wholexml.="\t\t<grain ";
				
				$wholexml.="grainid=\"".$ad[$y][$grainidcolnum]."\" ";
				$wholexml.="ns=\"".$ad[$y][$nscolnum]."\" ";
				$wholexml.="ni=\"".$ad[$y][$nicolnum]."\" ";
				$wholexml.="na=\"".$ad[$y][$nacolnum]."\" ";
				$wholexml.="dpar=\"".$ad[$y][$dparcolnum]."\" ";
				$wholexml.="dper=\"".$ad[$y][$dpercolnum]."\" ";
				$wholexml.="rmr0=\"".$ad[$y][$rmr0colnum]."\" ";
				$wholexml.="rhos=\"".$ad[$y][$rhoscolnum]."\" ";
				$wholexml.="rhoi=\"".$ad[$y][$rhoicolnum]."\" ";
				$wholexml.="rhosrhoi=\"".$ad[$y][$rhosrhoicolnum]."\" ";
				$wholexml.="area=\"".$ad[$y][$areacolnum]."\" ";
				$wholexml.="ofetchfigures=\"".$ad[$y][$ofetchfigurescolnum]."\" ";
				$wholexml.="u238ca43=\"".$ad[$y][$u238ca43colnum]."\" ";
				$wholexml.="error1s=\"".$ad[$y][$error1scolnum]."\" ";
				$wholexml.="uppm=\"".$ad[$y][$uppmcolnum]."\" ";
				$wholexml.="uerror1s=\"".$ad[$y][$uerror1scolnum]."\" ";
				$wholexml.="agema=\"".$ad[$y][$agemacolnum]."\" ";
				$wholexml.="ageerrorplus1s=\"".$ad[$y][$ageerrorplus1scolnum]."\" ";
				$wholexml.="ageerrorminus1s=\"".$ad[$y][$ageerrorminus1scolnum]."\" ";
				$wholexml.="cao=\"".$ad[$y][$caocolnum]."\" ";
				$wholexml.="p2o5=\"".$ad[$y][$p2o5colnum]."\" ";
				$wholexml.="f=\"".$ad[$y][$fcolnum]."\" ";
				$wholexml.="cl=\"".$ad[$y][$clcolnum]."\" ";
				$wholexml.="sro=\"".$ad[$y][$srocolnum]."\" ";
				$wholexml.="bao=\"".$ad[$y][$baocolnum]."\" ";
				$wholexml.="si02=\"".$ad[$y][$si02colnum]."\" ";
				$wholexml.="na2o=\"".$ad[$y][$na2ocolnum]."\" ";
				$wholexml.="ceo2=\"".$ad[$y][$ceo2colnum]."\" ";
				$wholexml.="feo=\"".$ad[$y][$feocolnum]."\" ";
				$wholexml.="total=\"".$ad[$y][$totalcolnum]."\" ";
				$wholexml.="caounits=\"$caounits\" ";
				$wholexml.="p2o5units=\"$p2o5units\" ";
				$wholexml.="funits=\"$funits\" ";
				$wholexml.="clunits=\"$clunits\" ";
				$wholexml.="srounits=\"$srounits\" ";
				$wholexml.="baounits=\"$baounits\" ";
				$wholexml.="si02units=\"$si02units\" ";
				$wholexml.="na2ounits=\"$na2ounits\" ";
				$wholexml.="ceo2units=\"$ceo2units\" ";
				$wholexml.="feounits=\"$feounits\" ";
				$wholexml.="totalunits=\"$totalunits\" ";
				
				$wholexml.="/>\n";
				
			}
		}
		
		$wholexml.="\t</apatiteages>\n";
		

		//next, add length worksheet

		$wholexml.="\t<grainlengths>\n";
		
		$objPHPExcel->setActiveSheetIndex($lengthsheetnum);

		//$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
		$ld = $objPHPExcel->getActiveSheet()->toArray();
		


		//find max x on row 8
		$maxx=0;
		for($x=0;$x<200;$x++){
			if($ld[7][$x]!=""){$maxx=$x;}
		}
		
		//find maxy in column 0
		$maxy=0;
		for($y=0;$y<200;$y++){
			if($ld[$y][0]!=""){$maxy=$y;}
		}
		
		//Look at row 8 to maxx to get column numbers
		for($x=0;$x<=$maxx;$x++){
			if($ld[7][$x]=="Grain ID"){$grainidcolnum=$x;}
			if($ld[7][$x]=="Length"){$lengthcolnum=$x;}
			if($ld[7][$x]=="Angle to C axis"){$angletocaxiscolnum=$x;}
			if($ld[7][$x]=="Dpar"){$dparcolnum=$x;}
			if($ld[7][$x]=="Tint or Tincle"){$tintcolnum=$x;}
			if($ld[7][$x]=="Depth"){$depthcolnum=$x;}
			if($ld[7][$x]=="Width"){$widthcolnum=$x;}
			if($ld[7][$x]=="Probability of this being a confined length"){$probabilitycolnum=$x;}
			if($ld[7][$x]=="Number of times Intersected"){$intersectedcolnum=$x;}
		}
		
		//loop over rows and build XML
		for($y=8;$y<=$maxy;$y++){
			if($ld[$y][$grainidcolnum]!=""){
				//OK, put this one in
				
				$wholexml.="\t\t<grainlength ";
				
				$wholexml.="grainid=\"".$ld[$y][$grainidcolnum]."\" ";
				$wholexml.="length=\"".$ld[$y][$lengthcolnum]."\" ";
				$wholexml.="angletocaxis=\"".$ld[$y][$angletocaxiscolnum]."\" ";
				$wholexml.="dpar=\"".$ld[$y][$dparcolnum]."\" ";
				$wholexml.="tintortincle=\"".$ld[$y][$tintcolnum]."\" ";
				$wholexml.="depth=\"".$ld[$y][$depthcolnum]."\" ";
				$wholexml.="width=\"".$ld[$y][$widthcolnum]."\" ";
				$wholexml.="probability=\"".$ld[$y][$probabilitycolnum]."\" ";
				$wholexml.="intersected=\"".$ld[$y][$intersectedcolnum]."\" ";
				
				$wholexml.="/>\n";
				
			}
		}
		
		$wholexml.="\t</grainlengths>\n";


		$wholexml.="</sample>";

		//save files...

		$myfile = "files/$savefilename";
		$fh = fopen($myfile, 'w') or die("can't open new XML file");
		fwrite($fh, $wholexml);
		fclose($fh);
		
		$newfilename=str_replace("xml","fissiontrack",$savefilename);
		$newfilename="files/$newfilename";
		
		move_uploaded_file ( $temp_name , "$newfilename" );
		
		//echo "$savefilename $newfilename";exit();
		
		//header('Content-Type: text/xml');echo $wholexml;exit();
		
		//OK, files are saved, let's show the uploaded file:
		
		$xsltfile="http://www.geochron.org/transforms/fissiontrackxslt.xslt";

		$xp = new XsltProcessor();
		// create a DOM document and load the XSL stylesheet
		$xsl = new DomDocument;
		$xsl->load($xsltfile);
		
		// import the XSL styelsheet into the XSLT process
		$xp->importStylesheet($xsl);
		
		// create a DOM document and load the XML datat
		$xml_doc = new DomDocument;
		$xml_doc->load("$myfile");
		





		include("includes/geochron-secondary-header.htm");
		
		echo "<h1>Success!</h1>";
		

		
		?>
		
		Your sample was uploaded successfully. Below is the data as it was uploaded.<br><br>
		
		<INPUT TYPE="button" value="Upload Another Sample" onClick="parent.location='fissiontrack'">&nbsp;
		<INPUT TYPE="button" value="Go to Data Manager" onClick="parent.location='managedata.php'"><br><br>


		<?


		
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



		include("includes/geochron-secondary-footer.htm");exit();
		
		
		
		

	}//end if error==""
















}//end if POST submitfile










/*
		for($sheetnum=0;$sheetnum<$sheetcount;$sheetnum++){

			$objPHPExcel->setActiveSheetIndex($sheetnum);

			$wholesheet = $objPHPExcel->getActiveSheet();
			$sheetname=$wholesheet->getTitle();

			//$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
			$sheetData = $objPHPExcel->getActiveSheet()->toArray();
	
	
			echo '<hr />';
			echo "sheetnum: $sheetnum sheetname: $sheetname";
			echo '<hr />';
			echo "<pre>";
	

			//print_r($sheetData);

		}
*/



































	if($error!=""){
	
		$error="<h2><font color=\"red\">Error!</font></h2><font color=\"red\">$error<br>Please try again.</font><br><br>";
	
	}
	
	
	include("includes/geochron-secondary-header.htm");
	
	
	?>
	
	<script type="text/javascript">

	
	function formvalidate(){
		//alert('hey');
		var errors='';
		if(document.forms["uploadform"]["ftfile"].value=="" || document.forms["uploadform"]["ftfile"].value==null){errors=errors+'Fission Track file must be provided.\n';}
		
		if(errors!="" && errors!=null){
			alert(errors);
			return false;
		}
	}
	
	
	</script>
	
	<h1>Upload Fission Track Data</h1><br>
	
	Please upload your Fission Track template file (file must be in .xlsx or .ods format):<br><br>
	
	<div style="padding-left:20px;padding-top:20px;">
	
		<?=$error?>
	

	
	<form name="uploadform" method="POST" onsubmit="return formvalidate();" enctype="multipart/form-data">
		
		<table style="font-size:10px;">
			<tr>
				<td colspan="2"><h1>Sample File</h2></td>
			</tr>
			<tr>
				<td>Fission Track File:</td><td><input type="file" name="ftfile" size="40" ></td>
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
	


		<br>
	
		<input type="submit" name="submitfile" value="Submit">
		
		<br>
		<br>
		<br>
		<h2>Upload Templates:</h2>Please choose the appropriate file below:<br><br>
		<a href="templates/Geochron_FT_Template.xlsx">Geochron_FT_Template.xlsx</a> Microsoft Office .XLSX Document<br>
		<a href="templates/Geochron_FT_Template.ods">Geochron_FT_Template.ods</a> Open Office .ODS Document<br>
		
	
	</form>
	
	</div>
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	<br><br><br><br><br>
	<br><br><br><br><br>
	<br><br><br><br><br>
	<br><br><br><br><br>
	<br><br><br><br><br>
	
	
	



