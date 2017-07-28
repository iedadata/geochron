<?PHP
/**
 * ararupload.php
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
	$apatiteheaders[18][0]="Age error (1s)";
	$apatiteheaders[19][0]="CaO";
	$apatiteheaders[20][0]="P2O5";
	$apatiteheaders[21][0]="F";
	$apatiteheaders[22][0]="Cl";
	$apatiteheaders[23][0]="SrO";
	$apatiteheaders[24][0]="BaO";
	$apatiteheaders[25][0]="Si02";
	$apatiteheaders[26][0]="Na2O";
	$apatiteheaders[27][0]="CeO2";
	$apatiteheaders[28][0]="FeO";
	$apatiteheaders[29][0]="Total";

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
	$apatiteheaders[15][1]="uppm";
	$apatiteheaders[16][1]="uerror1s";
	$apatiteheaders[17][1]="agema";
	$apatiteheaders[18][1]="ageerror1s";
	$apatiteheaders[19][1]="cao";
	$apatiteheaders[20][1]="p2o5";
	$apatiteheaders[21][1]="f";
	$apatiteheaders[22][1]="cl";
	$apatiteheaders[23][1]="sro";
	$apatiteheaders[24][1]="bao";
	$apatiteheaders[25][1]="si02";
	$apatiteheaders[26][1]="na2o";
	$apatiteheaders[27][1]="ceo2";
	$apatiteheaders[28][1]="feo";
	$apatiteheaders[29][1]="total";

	$overwrite=$_POST['overwrite'];
	$public=$_POST['public'];

	//echo "overwrite: $overwrite public:$public";exit();

	//file has been submitted. Check it here and save it for 
	//processing samples
	
	if($error==""){
	
		//print_r($_FILES);
		$orig_name=$_FILES['ararfile']['name'];
		$temp_name=$_FILES['ararfile']['tmp_name'];
		
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

		$samplemethodsheetnum="";
		$apatiteagesheetnum="";
		$lengthsheetnum="";

		for($sheetnum=0;$sheetnum<$sheetcount;$sheetnum++){

			$objPHPExcel->setActiveSheetIndex($sheetnum);

			$wholesheet = $objPHPExcel->getActiveSheet();
			$sheetname=$wholesheet->getTitle();

			//$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
			//$sheetData = $objPHPExcel->getActiveSheet()->toArray();
	
			if($sheetname=="Sample-Methods Worksheet"){$samplemethodsheetnum=$sheetnum;}
			if($sheetname=="Intensities Step-Grain Workshee"){$intensitiessheetnum=$sheetnum;}
			if($sheetname=="Ratios Step-Grain Worksheet"){$ratiossheetnum=$sheetnum;}


		}

		/*
		echo "samplemethodsheetnum=$samplemethodsheetnum<br>";
		echo "intensitiessheetnum=$intensitiessheetnum<br>";
		echo "ratiossheetnum=$ratiossheetnum<br>";
		exit();
		*/
		
		if($samplemethodsheetnum < 0 || $intensitiessheetnum < 0 || $ratiossheetnum < 0){
			$error.="Required sheet(s) not found in workbook. Please use provided template below.<br>";
		}
	
	
	}//end if error == ""

	if($error==""){
		
		//OK, we found the required sheets. Let's check the sample methods sheet for required fields.
		$objPHPExcel->setActiveSheetIndex($samplemethodsheetnum);

		//$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
		$sd = $objPHPExcel->getActiveSheet()->toArray();
		
		//print_r($sd);exit();

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
			if($sd[$y][0]=="Sample Material"){$samplematerial=$sd[$y][1];}
			if($sd[$y][0]=="Sample Material Type"){$samplematerialtype=$sd[$y][1];}
			if($sd[$y][0]=="Instrumental Method"){$instrumentalmethod=$sd[$y][1];}
			if($sd[$y][0]=="Instrumental Method Reference"){$instrumentalmethodreference=$sd[$y][1];}
			if($sd[$y][0]=="K2O"){$k2o=$sd[$y][1];}
			if($sd[$y][0]=="Discrimination"){$discrimination=$sd[$y][1];}
			if($sd[$y][0]=="Comment"){$comment=$sd[$y][1];}
			if($sd[$y][0]=="Irradiation Reactor Name"){$irradiationreactorname=$sd[$y][1];}
			if($sd[$y][0]=="Irradiation Total Duration"){$irradiationtotalduration=$sd[$y][1];}
			if($sd[$y][0]=="Irradiation End Date and Time"){$irradiationenddateandtime=$sd[$y][1];}
			if($sd[$y][0]=="Decay Constant 40Ar Total"){$decayconstant40artotal=$sd[$y][1];}
			if($sd[$y][0]=="Decay Constant 40Ar Total Sigma"){$decayconstant40artotalsigma=$sd[$y][1];}
			if($sd[$y][0]=="Decay Constant Reference"){$decayconstantreference=$sd[$y][1];}
			if($sd[$y][0]=="Decay Constant Comment"){$decayconstantcomment=$sd[$y][1];}
			if($sd[$y][0]=="Standard Name"){$standardname=$sd[$y][1];}
			if($sd[$y][0]=="Standard Material"){$standardmaterial=$sd[$y][1];}
			if($sd[$y][0]=="Standard Age"){$standardage=$sd[$y][1];}
			if($sd[$y][0]=="Standard Reference"){$standardreference=$sd[$y][1];}
			if($sd[$y][0]=="J-Value"){$jvalue=$sd[$y][1];}
			if($sd[$y][0]=="J-Value Sigma"){$jvaluesigma=$sd[$y][1];}



			if($sd[$y][0]=="Analysis Purpose"){$agenum++;eval("\$analysispurpose$agenum=\$sd[\$y][1];");}
			if($sd[$y][0]=="Experiments Included"){eval("\$experimentsincluded$agenum=\$sd[\$y][1];");}
			if($sd[$y][0]=="Preferred Age"){eval("\$preferredage$agenum=\$sd[\$y][1];");}
			if($sd[$y][0]=="Preferred Age Sigma"){eval("\$preferredagesigma$agenum=\$sd[\$y][1];");}
			if($sd[$y][0]=="Preferred Age Sigma Internal"){eval("\$preferredagesigmainternal$agenum=\$sd[\$y][1];");}
			if($sd[$y][0]=="Preferred Age Sigma External"){eval("\$preferredagesigmaexternal$agenum=\$sd[\$y][1];");}
			if($sd[$y][0]=="Preferred Age Type"){eval("\$preferredagetype$agenum=\$sd[\$y][1];");}
			if($sd[$y][0]=="Preferred Age Classification"){eval("\$preferredageclassification$agenum=\$sd[\$y][1];");}
			if($sd[$y][0]=="Preferred Age Reference"){eval("\$preferredagereference$agenum=\$sd[\$y][1];");}
			if($sd[$y][0]=="Preferred Age Description"){eval("\$preferredagedescription$agenum=\$sd[\$y][1];");}


		}//end for y

		//Check for required values here
		$sampleid=$samplename;

		if($samplename==""){$error.=$errordelim."Sample Name cannot be blank.";$errordelim="<br>";}
		if($uniqueid==""){$error.=$errordelim."IGSN cannot be blank.";$errordelim="<br>";}
		if($labname==""){$error.=$errordelim."Laboratory cannot be blank.";$errordelim="<br>";}
		if($analystname==""){$error.=$errordelim."Analyst cannot be blank.";$errordelim="<br>";}
		if($samplematerial==""){$error.=$errordelim."Sample Material cannot be blank.";$errordelim="<br>";}
		if($samplematerialtype==""){$error.=$errordelim."Sample Material Type cannot be blank.";$errordelim="<br>";}
		if($instrumentalmethod==""){$error.=$errordelim."Instrumental Method cannot be blank.";$errordelim="<br>";}
		//if($instrumentalmethodreference==""){$error.=$errordelim."Instrumental Method Reference cannot be blank.";$errordelim="<br>";}
		if($decayconstant40artotal==""){$error.=$errordelim."Decay Constant 40Ar Total cannot be blank.";$errordelim="<br>";}
		if($decayconstant40artotalsigma==""){$error.=$errordelim."Decay Constant 40Ar Total Sigma cannot be blank.";$errordelim="<br>";}
		if($decayconstantreference==""){$error.=$errordelim."Decay Constant Reference cannot be blank.";$errordelim="<br>";}
		if($standardname==""){$error.=$errordelim."Standard Name cannot be blank.";$errordelim="<br>";}
		if($standardmaterial==""){$error.=$errordelim."Standard Material cannot be blank.";$errordelim="<br>";}
		if($standardage==""){$error.=$errordelim."Standard Age cannot be blank.";$errordelim="<br>";}
		if($jvalue==""){$error.=$errordelim."J-Value cannot be blank.";$errordelim="<br>";}
		if($jvaluesigma==""){$error.=$errordelim."J-Value Sigma cannot be blank.";$errordelim="<br>";}

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
				'$preferredagetype1',";
			if($preferredage1!=""){
				$query.="
				$preferredage1,";
			}
			$query.="
				'ABS',
				'$preferredagesigma1',
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
				'$preferredagetype2',";
			if($preferredage2!=""){
				$query.="
				$preferredage2,";
			}
			$query.="
				'ABS',
				'$preferredagesigma2',
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
				'$preferredagetype3',";
			if($preferredage3!=""){
				$query.="
				$preferredage3,";
			}
			$query.="
				'ABS',
				'$preferredagesigma3',
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
				'$preferredagetype4',";
			if($preferredage4!=""){
				$query.="
				$preferredage4,";
			}
			$query.="
				'ABS',
				'$preferredagesigma4',
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
				'$preferredagetype5',";
			if($preferredage5!=""){
				$query.="
				$preferredage5,";
			}
			$query.="
				'ABS',
				'$preferredagesigma5',
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
				'$preferredagetype6',";
			if($preferredage6!=""){
				$query.="
				$preferredage6,";
			}
			$query.="
				'ABS',
				'$preferredagesigma6',
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
				'$preferredagetype7',";
			if($preferredage7!=""){
				$query.="
				$preferredage7,";
			}
			$query.="
				'ABS',
				'$preferredagesigma7',
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
				'$preferredagetype8',";
			if($preferredage8!=""){
				$query.="
				$preferredage8,";
			}
			$query.="
				'ABS',
				'$preferredagesigma8',
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
				'$preferredagetype9',";
			if($preferredage9!=""){
				$query.="
				$preferredage9,";
			}
			$query.="
				'ABS',
				'$preferredagesigma9',
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
				'$preferredagetype10',";
			if($preferredage10!=""){
				$query.="
				$preferredage10,";
			}
			$query.="
				'ABS',
				'$preferredagesigma10',
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
					'ararxls',";
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
					'".$samplematerial."',
					'$analysispurpose1'
					)";
					
					
		//echo nl2br($querystring);exit();
		
		$db->query($querystring);


		//start whole xml file
		$wholexml="<sample>\n\t<sampleinfo>\n";
		
		if($samplename!=""){$wholexml.="\t\t<samplename>".htmlspecialchars($samplename)."</samplename>\n";}
		if($uniqueid!=""){$wholexml.="\t\t<igsn>".htmlspecialchars($uniqueid)."</igsn>\n";}
		if($labname!=""){$wholexml.="\t\t<laboratory>".htmlspecialchars($labname)."</laboratory>\n";}
		if($analystname!=""){$wholexml.="\t\t<analyst>".htmlspecialchars($analystname)."</analyst>\n";}
		if($samplematerial!=""){$wholexml.="\t\t<samplematerial>".htmlspecialchars($samplematerial)."</samplematerial>\n";}
		if($samplematerialtype!=""){$wholexml.="\t\t<samplematerialtype>".htmlspecialchars($samplematerialtype)."</samplematerialtype>\n";}
		if($instrumentalmethod!=""){$wholexml.="\t\t<instrumentalmethod>".htmlspecialchars($instrumentalmethod)."</instrumentalmethod>\n";}
		if($instrumentalmethodreference!=""){$wholexml.="\t\t<instrumentalmethodreference>".htmlspecialchars($instrumentalmethodreference)."</instrumentalmethodreference>\n";}
		if($k2o!=""){$wholexml.="\t\t<k2o>".htmlspecialchars($k2o)."</k2o>\n";}
		if($discrimination!=""){$wholexml.="\t\t<discrimination>".htmlspecialchars($discrimination)."</discrimination>\n";}
		if($comment!=""){$wholexml.="\t\t<comment>".htmlspecialchars($comment)."</comment>\n";}
		if($irradiationreactorname!=""){$wholexml.="\t\t<irradiationreactorname>".htmlspecialchars($irradiationreactorname)."</irradiationreactorname>\n";}
		if($irradiationtotalduration!=""){$wholexml.="\t\t<irradiationtotalduration>".htmlspecialchars($irradiationtotalduration)."</irradiationtotalduration>\n";}
		if($irradiationenddateandtime!=""){$wholexml.="\t\t<irradiationenddateandtime>".htmlspecialchars($irradiationenddateandtime)."</irradiationenddateandtime>\n";}
		if($decayconstant40artotal!=""){$wholexml.="\t\t<decayconstant40artotal>".$decayconstant40artotal."</decayconstant40artotal>\n";}
		if($decayconstant40artotalsigma!=""){$wholexml.="\t\t<decayconstant40artotalsigma>".htmlspecialchars($decayconstant40artotalsigma)."</decayconstant40artotalsigma>\n";}
		if($decayconstantreference!=""){$wholexml.="\t\t<decayconstantreference>".htmlspecialchars($decayconstantreference)."</decayconstantreference>\n";}
		if($decayconstantcomment!=""){$wholexml.="\t\t<decayconstantcomment>".htmlspecialchars($decayconstantcomment)."</decayconstantcomment>\n";}
		if($standardname!=""){$wholexml.="\t\t<standardname>".htmlspecialchars($standardname)."</standardname>\n";}
		if($standardmaterial!=""){$wholexml.="\t\t<standardmaterial>".htmlspecialchars($standardmaterial)."</standardmaterial>\n";}
		if($standardage!=""){$wholexml.="\t\t<standardage>".htmlspecialchars($standardage)."</standardage>\n";}
		if($standardreference!=""){$wholexml.="\t\t<standardreference>".htmlspecialchars($standardreference)."</standardreference>\n";}
		if($jvalue!=""){$wholexml.="\t\t<jvalue>".htmlspecialchars($jvalue)."</jvalue>\n";}
		if($jvaluesigma!=""){$wholexml.="\t\t<jvaluesigma>".htmlspecialchars($jvaluesigma)."</jvaluesigma>\n";}

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
		
		if($analysispurpose1!=""){$wholexml.="\t\t<age analysispurpose=\"$analysispurpose1\" experimentsincluded=\"$experimentsincluded1\" preferredage=\"$preferredage1\" preferredagesigma=\"$preferredagesigma1\" preferredagesigmainternal=\"$preferredagesigmainternal1\" preferredagesigmaexternal=\"$preferredagesigmaexternal1\" preferredagetype=\"$preferredagetype1\" preferredageclassification=\"$preferredageclassification1\" preferredagereference=\"$preferredagereference1\" preferredagedescription=\"$preferredagedescription1\" />\n";}
		if($analysispurpose2!=""){$wholexml.="\t\t<age analysispurpose=\"$analysispurpose2\" experimentsincluded=\"$experimentsincluded2\" preferredage=\"$preferredage2\" preferredagesigma=\"$preferredagesigma2\" preferredagesigmainternal=\"$preferredagesigmainternal2\" preferredagesigmaexternal=\"$preferredagesigmaexternal2\" preferredagetype=\"$preferredagetype2\" preferredageclassification=\"$preferredageclassification2\" preferredagereference=\"$preferredagereference2\" preferredagedescription=\"$preferredagedescription2\" />\n";}
		if($analysispurpose3!=""){$wholexml.="\t\t<age analysispurpose=\"$analysispurpose3\" experimentsincluded=\"$experimentsincluded3\" preferredage=\"$preferredage3\" preferredagesigma=\"$preferredagesigma3\" preferredagesigmainternal=\"$preferredagesigmainternal3\" preferredagesigmaexternal=\"$preferredagesigmaexternal3\" preferredagetype=\"$preferredagetype3\" preferredageclassification=\"$preferredageclassification3\" preferredagereference=\"$preferredagereference3\" preferredagedescription=\"$preferredagedescription3\" />\n";}
		if($analysispurpose4!=""){$wholexml.="\t\t<age analysispurpose=\"$analysispurpose4\" experimentsincluded=\"$experimentsincluded4\" preferredage=\"$preferredage4\" preferredagesigma=\"$preferredagesigma4\" preferredagesigmainternal=\"$preferredagesigmainternal4\" preferredagesigmaexternal=\"$preferredagesigmaexternal4\" preferredagetype=\"$preferredagetype4\" preferredageclassification=\"$preferredageclassification4\" preferredagereference=\"$preferredagereference4\" preferredagedescription=\"$preferredagedescription4\" />\n";}
		if($analysispurpose5!=""){$wholexml.="\t\t<age analysispurpose=\"$analysispurpose5\" experimentsincluded=\"$experimentsincluded5\" preferredage=\"$preferredage5\" preferredagesigma=\"$preferredagesigma5\" preferredagesigmainternal=\"$preferredagesigmainternal5\" preferredagesigmaexternal=\"$preferredagesigmaexternal5\" preferredagetype=\"$preferredagetype5\" preferredageclassification=\"$preferredageclassification5\" preferredagereference=\"$preferredagereference5\" preferredagedescription=\"$preferredagedescription5\" />\n";}
		if($analysispurpose6!=""){$wholexml.="\t\t<age analysispurpose=\"$analysispurpose6\" experimentsincluded=\"$experimentsincluded6\" preferredage=\"$preferredage6\" preferredagesigma=\"$preferredagesigma6\" preferredagesigmainternal=\"$preferredagesigmainternal6\" preferredagesigmaexternal=\"$preferredagesigmaexternal6\" preferredagetype=\"$preferredagetype6\" preferredageclassification=\"$preferredageclassification6\" preferredagereference=\"$preferredagereference6\" preferredagedescription=\"$preferredagedescription6\" />\n";}
		if($analysispurpose7!=""){$wholexml.="\t\t<age analysispurpose=\"$analysispurpose7\" experimentsincluded=\"$experimentsincluded7\" preferredage=\"$preferredage7\" preferredagesigma=\"$preferredagesigma7\" preferredagesigmainternal=\"$preferredagesigmainternal7\" preferredagesigmaexternal=\"$preferredagesigmaexternal7\" preferredagetype=\"$preferredagetype7\" preferredageclassification=\"$preferredageclassification7\" preferredagereference=\"$preferredagereference7\" preferredagedescription=\"$preferredagedescription7\" />\n";}
		if($analysispurpose8!=""){$wholexml.="\t\t<age analysispurpose=\"$analysispurpose8\" experimentsincluded=\"$experimentsincluded8\" preferredage=\"$preferredage8\" preferredagesigma=\"$preferredagesigma8\" preferredagesigmainternal=\"$preferredagesigmainternal8\" preferredagesigmaexternal=\"$preferredagesigmaexternal8\" preferredagetype=\"$preferredagetype8\" preferredageclassification=\"$preferredageclassification8\" preferredagereference=\"$preferredagereference8\" preferredagedescription=\"$preferredagedescription8\" />\n";}
		if($analysispurpose9!=""){$wholexml.="\t\t<age analysispurpose=\"$analysispurpose9\" experimentsincluded=\"$experimentsincluded9\" preferredage=\"$preferredage9\" preferredagesigma=\"$preferredagesigma9\" preferredagesigmainternal=\"$preferredagesigmainternal9\" preferredagesigmaexternal=\"$preferredagesigmaexternal9\" preferredagetype=\"$preferredagetype9\" preferredageclassification=\"$preferredageclassification9\" preferredagereference=\"$preferredagereference9\" preferredagedescription=\"$preferredagedescription9\" />\n";}
		if($analysispurpose10!=""){$wholexml.="\t\t<age analysispurpose=\"$analysispurpose10\" experimentsincluded=\"$experimentsincluded10\" preferredage=\"$preferredage10\" preferredagesigma=\"$preferredagesigma10\" preferredagesigmainternal=\"$preferredagesigmainternal10\" preferredagesigmaexternal=\"$preferredagesigmaexternal10\" preferredagetype=\"$preferredagetype10\" preferredageclassification=\"$preferredageclassification10\" preferredagereference=\"$preferredagereference10\" preferredagedescription=\"$preferredagedescription10\" />\n";}


		$wholexml.="\t</ages>\n";
		
		//Now add section for Apatite Ages

		
		
		$objPHPExcel->setActiveSheetIndex($intensitiessheetnum);

		$id = $objPHPExcel->getActiveSheet()->toArray();
		
		//find max x on row 11(10)
		$maxx=0;
		for($x=0;$x<200;$x++){
			if($id[10][$x]!=""){$maxx=$x;}
		}
		
		//find maxy in column 0
		$maxy=0;
		for($y=0;$y<200;$y++){
			if($id[$y][1]!=""){$maxy=$y;}
		}
		
		//Look at row 8 to maxx to get column numbers
		for($x=1;$x<=$maxx;$x++){
			if($id[10][$x]=="ID"){$idcolnum=$x;}
			if($id[10][$x]=="Power"){$powercolnum=$x;}
			if($id[10][$x]=="40Ar"){$ar40colnum=$x;}
			if($id[10][$x]=="error 40Ar (1s)"){$error40ar1scolnum=$x;}
			if($id[10][$x]=="39Ar"){$ar39colnum=$x;}
			if($id[10][$x]=="error 39Ar (1s)"){$error39ar1scolnum=$x;}
			if($id[10][$x]=="38Ar"){$ar38colnum=$x;}
			if($id[10][$x]=="error 38Ar (1s)"){$error38ar1scolnum=$x;}
			if($id[10][$x]=="37Ar"){$ar37colnum=$x;}
			if($id[10][$x]=="error 37Ar (1s)"){$error37ar1scolnum=$x;}
			if($id[10][$x]=="36Ar"){$ar36colnum=$x;}
			if($id[10][$x]=="error 36Ar (1s)"){$error36ar1scolnum=$x;}
			if($id[10][$x]=="40Ar* %"){$ar40pctcolnum=$x;}
			if($id[10][$x]=="40Ar*/39ArK"){$ar40ar39kcolnum=$x;}
			if($id[10][$x]=="error 40Ar*/39ArK (1s)"){$error40ar39ark1scolnum=$x;}
			if($id[10][$x]=="Age"){$agecolnum=$x;}
			if($id[10][$x]=="Age Error (1s)"){$ageerror1scolnum=$x;}
		}

		//get units
		$idunits=$id[11][$idcolnum];
		$powerunits=$id[11][$powercolnum];
		$ar40units=$id[11][$ar40colnum];
		$error40ar1sunits=$id[11][$error40ar1scolnum];
		$ar39units=$id[11][$ar39colnum];
		$error39ar1sunits=$id[11][$error39ar1scolnum];
		$ar38units=$id[11][$ar38colnum];
		$error38ar1sunits=$id[11][$error38ar1scolnum];
		$ar37units=$id[11][$ar37colnum];
		$error37ar1sunits=$id[11][$error37ar1scolnum];
		$ar36units=$id[11][$ar36colnum];
		$error36ar1sunits=$id[11][$error36ar1scolnum];
		$ar40pctunits=$id[11][$ar40pctcolnum];
		$ar40ar39kunits=$id[11][$ar40ar39kcolnum];
		$error40ar39ark1sunits=$id[11][$error40ar39ark1scolnum];
		$ageunits=$id[11][$agecolnum];
		$ageerror1sunits=$id[11][$ageerror1scolnum];


		
		$wholexml.="\t<intensities ";

		$wholexml.="idunits=\"$idunits\" ";
		$wholexml.="powerunits=\"$powerunits\" ";
		$wholexml.="ar40units=\"$ar40units\" ";
		$wholexml.="error40ar1sunits=\"$error40ar1sunits\" ";
		$wholexml.="ar39units=\"$ar39units\" ";
		$wholexml.="error39ar1sunits=\"$error39ar1sunits\" ";
		$wholexml.="ar38units=\"$ar38units\" ";
		$wholexml.="error38ar1sunits=\"$error38ar1sunits\" ";
		$wholexml.="ar37units=\"$ar37units\" ";
		$wholexml.="error37ar1sunits=\"$error37ar1sunits\" ";
		$wholexml.="ar36units=\"$ar36units\" ";
		$wholexml.="error36ar1sunits=\"$error36ar1sunits\" ";
		$wholexml.="ar40pctunits=\"$ar40pctunits\" ";
		$wholexml.="ar40ar39kunits=\"$ar40ar39kunits\" ";
		$wholexml.="error40ar39ark1sunits=\"$error40ar39ark1sunits\" ";
		$wholexml.="ageunits=\"$ageunits\" ";
		$wholexml.="ageerror1sunits=\"$ageerror1sunits\" ";


		$wholexml.=">\n";
		
		//loop over rows and build XML
		for($y=12;$y<=$maxy;$y++){
			if($id[$y][$idcolnum]!=""){
				//OK, put this one in
				
				$wholexml.="\t\t<intensity ";

				$wholexml.="id=\"".$id[$y][$idcolnum]."\" ";
				$wholexml.="power=\"".$id[$y][$powercolnum]."\" ";
				$wholexml.="ar40=\"".$id[$y][$ar40colnum]."\" ";
				$wholexml.="error40ar1s=\"".$id[$y][$error40ar1scolnum]."\" ";
				$wholexml.="ar39=\"".$id[$y][$ar39colnum]."\" ";
				$wholexml.="error39ar1s=\"".$id[$y][$error39ar1scolnum]."\" ";
				$wholexml.="ar38=\"".$id[$y][$ar38colnum]."\" ";
				$wholexml.="error38ar1s=\"".$id[$y][$error38ar1scolnum]."\" ";
				$wholexml.="ar37=\"".$id[$y][$ar37colnum]."\" ";
				$wholexml.="error37ar1s=\"".$id[$y][$error37ar1scolnum]."\" ";
				$wholexml.="ar36=\"".$id[$y][$ar36colnum]."\" ";
				$wholexml.="error36ar1s=\"".$id[$y][$error36ar1scolnum]."\" ";
				$wholexml.="ar40pct=\"".$id[$y][$ar40pctcolnum]."\" ";
				$wholexml.="ar40ar39k=\"".$id[$y][$ar40ar39kcolnum]."\" ";
				$wholexml.="error40ar39ark1s=\"".$id[$y][$error40ar39ark1scolnum]."\" ";
				$wholexml.="age=\"".$id[$y][$agecolnum]."\" ";
				$wholexml.="ageerror1s=\"".$id[$y][$ageerror1scolnum]."\" ";

				$wholexml.="idunits=\"$idunits\" ";
				$wholexml.="powerunits=\"$powerunits\" ";
				$wholexml.="ar40units=\"$ar40units\" ";
				$wholexml.="error40ar1sunits=\"$error40ar1sunits\" ";
				$wholexml.="ar39units=\"$ar39units\" ";
				$wholexml.="error39ar1sunits=\"$error39ar1sunits\" ";
				$wholexml.="ar38units=\"$ar38units\" ";
				$wholexml.="error38ar1sunits=\"$error38ar1sunits\" ";
				$wholexml.="ar37units=\"$ar37units\" ";
				$wholexml.="error37ar1sunits=\"$error37ar1sunits\" ";
				$wholexml.="ar36units=\"$ar36units\" ";
				$wholexml.="error36ar1sunits=\"$error36ar1sunits\" ";
				$wholexml.="ar40pctunits=\"$ar40pctunits\" ";
				$wholexml.="ar40ar39kunits=\"$ar40ar39kunits\" ";
				$wholexml.="error40ar39ark1sunits=\"$error40ar39ark1sunits\" ";
				$wholexml.="ageunits=\"$ageunits\" ";
				$wholexml.="ageerror1sunits=\"$ageerror1sunits\" ";

				$wholexml.="/>\n";
				
			}
		}
		
		$wholexml.="\t</intensities>\n";
		

		//next, add length worksheet				

		$objPHPExcel->setActiveSheetIndex($ratiossheetnum);

		$rd = $objPHPExcel->getActiveSheet()->toArray();
		
		//find max x on row 11 (10)
		$maxx=0;
		for($x=0;$x<200;$x++){
			if($rd[10][$x]!=""){$maxx=$x;}
		}
		
		//find maxy in column 0
		$maxy=0;
		for($y=0;$y<200;$y++){
			if($rd[$y][1]!=""){$maxy=$y;}
		}
		
		//Look at row 8 to maxx to get column numbers
		for($x=1;$x<=$maxx;$x++){
			if($rd[10][$x]=="ID"){$idcolnum=$x;}
			if($rd[10][$x]=="Power"){$powercolnum=$x;}
			if($rd[10][$x]=="40Ar/39Ar"){$ar40ar39colnum=$x;}
			if($rd[10][$x]=="37Ar/39Ar"){$ar37ar39colnum=$x;}
			if($rd[10][$x]=="36Ar/39Ar"){$ar36ar39colnum=$x;}
			if($rd[10][$x]=="39ArK"){$ar39kcolnum=$x;}
			if($rd[10][$x]=="K/Ca"){$kcacolnum=$x;}
			if($rd[10][$x]=="40Ar*"){$ar40colnum=$x;}
			if($rd[10][$x]=="39Ar"){$ar39colnum=$x;}
			if($rd[10][$x]=="Age"){$agecolnum=$x;}
			if($rd[10][$x]=="Age Error 1s"){$ageerror1scolnum=$x;}
		}

		//get units
		$idunits=$rd[11][$idcolnum];
		$powerunits=$rd[11][$powercolnum];
		$ar40ar39units=$rd[11][$ar40ar39colnum];
		$ar37ar39units=$rd[11][$ar37ar39colnum];
		$ar36ar39units=$rd[11][$ar36ar39colnum];
		$ar39kunits=$rd[11][$ar39kcolnum];
		$kcaunits=$rd[11][$kcacolnum];
		$ar40units=$rd[11][$ar40colnum];
		$ar39units=$rd[11][$ar39colnum];
		$ageunits=$rd[11][$agecolnum];
		$ageerror1sunits=$rd[11][$ageerror1scolnum];

		$wholexml.="\t<ratios idunits=\"$idunits\" powerunits=\"$powerunits\" ar40ar39units=\"$ar40ar39units\" ar37ar39units=\"$ar37ar39units\" ar36ar39units=\"$ar36ar39units\" ar39kunits=\"$ar39kunits\" kcaunits=\"$kcaunits\" ar40units=\"$ar40units\" ar39units=\"$ar39units\" ageunits=\"$ageunits\" ageerror1sunits=\"$ageerror1sunits\" >\n";

		//loop over rows and build XML
		for($y=12;$y<=$maxy;$y++){
			if($rd[$y][$idcolnum]!=""){
				//OK, put this one in
				
				$wholexml.="\t\t<ratio ";

				$wholexml.="id=\"".$rd[$y][$idcolnum]."\" ";
				$wholexml.="power=\"".$rd[$y][$powercolnum]."\" ";
				$wholexml.="ar40ar39=\"".$rd[$y][$ar40ar39colnum]."\" ";
				$wholexml.="ar37ar39=\"".$rd[$y][$ar37ar39colnum]."\" ";
				$wholexml.="ar36ar39=\"".$rd[$y][$ar36ar39colnum]."\" ";
				$wholexml.="ar39k=\"".$rd[$y][$ar39kcolnum]."\" ";
				$wholexml.="kca=\"".$rd[$y][$kcacolnum]."\" ";
				$wholexml.="ar40=\"".$rd[$y][$ar40colnum]."\" ";
				$wholexml.="ar39=\"".$rd[$y][$ar39colnum]."\" ";
				$wholexml.="age=\"".$rd[$y][$agecolnum]."\" ";
				$wholexml.="ageerror1s=\"".$rd[$y][$ageerror1scolnum]."\" ";

				$wholexml.="/>\n";
				
			}
		}
		
		$wholexml.="\t</ratios>\n";


		$wholexml.="</sample>";

		//save files...

		$myfile = "files/$savefilename";
		$fh = fopen($myfile, 'w') or die("can't open new XML file");
		fwrite($fh, $wholexml);
		fclose($fh);
		
		$newfilename=str_replace("xml","ararxls",$savefilename);
		$newfilename="files/$newfilename";
		
		move_uploaded_file ( $temp_name , "$newfilename" );
		
		//echo "$savefilename $newfilename";exit();
		
		//header('Content-Type: text/xml');echo $wholexml;exit();
		
		//OK, files are saved, let's show the uploaded file:
		
		$xsltfile="http://www.geochron.org/transforms/ararxslt_$sample_pkey.xslt";

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
		
		<INPUT TYPE="button" value="Upload Another Sample" onClick="parent.location='ararupload'">&nbsp;
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
		if(document.forms["uploadform"]["ararfile"].value=="" || document.forms["uploadform"]["ararfile"].value==null){errors=errors+'ArAr file must be provided.\n';}
		
		if(errors!="" && errors!=null){
			alert(errors);
			return false;
		}
	}
	
	
	</script>
	
	<h1>Upload ArAr Data</h1><br>
	
	Please upload your ArAr template file (file must be in .xlsx or .ods format):<br><br>
	
	<div style="padding-left:20px;padding-top:20px;">
	
		<?=$error?>
	

	
	<form name="uploadform" method="POST" onsubmit="return formvalidate();" enctype="multipart/form-data">
		
		<table style="font-size:10px;">
			<tr>
				<td colspan="2"><h1>Sample File</h2></td>
			</tr>
			<tr>
				<td>ArAr File:</td><td><input type="file" name="ararfile" size="40" ></td>
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
		<a href="templates/Geochron_ArAr_Template.xlsx">Geochron_ArAr_Template.xlsx</a> Microsoft Office .XLSX Document<br>
		<a href="templates/Geochron_ArAr_Template.ods">Geochron_ArAr_Template.ods</a> Open Office .ODS Document<br>
		
	
	</form>
	
	</div>
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	<br><br><br><br><br>
	<br><br><br><br><br>
	<br><br><br><br><br>
	<br><br><br><br><br>
	<br><br><br><br><br>
	
	
	



