<?PHP
/**
 * modularloader.php
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

		if($publ==""){
			$publ=0;
		}
		
		$isupstream="FALSE";
		
		$material="";
		
		//first, determine file type...
		//then find igsn and call SESAR for details.
		
		//first check to see if aliquots exist... if so, it is from redux
		$ecproject="";
		$aliquots = $dom->getElementsByTagName("Aliquot");
		if($aliquots->length > 0){
			$ecproject="redux";
			//echo "length of redux: ".$aliquots->length."<br>";
		}
		
		
		$ararmodels = $dom->getElementsByTagName("ArArModel");
		if($ararmodels->length > 0){
			$ecproject="arar";
			//echo "length of arar: ".$ararmodels->length."<br>";
		}

		$heliosmodels = $dom->getElementsByTagName("HeliosModel");
		if($heliosmodels->length > 0){
			$ecproject="helios";
			//echo "length of arar: ".$ararmodels->length."<br>";
		}
		
		$elsfneutrons = $dom->getElementsByTagName("elsfneutron");
		if($elsfneutrons->length > 0){
			$ecproject="cronus";
			//echo "length of arar: ".$ararmodels->length."<br>";
		}

		$confidentialies = $dom->getElementsByTagName("confidentiality");
		if($confidentialies->length > 0){
			$ecproject="igor";
			//echo "length of arar: ".$ararmodels->length."<br>";
		}



		//echo "EC project: $ecproject<br>";
		
		//now, find igsn based on ecproject
		if($ecproject=="arar"){
			$ararsamples = $dom->getElementsByTagName("Sample");
			foreach($ararsamples as $ararsample){
				$modigsn=strtoupper($ararsample->getAttribute("igsn"));
			}		
		}elseif($ecproject=="redux"){
			
			$reduxsamples = $dom->getElementsByTagName("aliquotIGSN");
			foreach($reduxsamples as $reduxsample){
				$aliquotigsn=$reduxsample->textContent;
			}
			
			$reduxsamples = $dom->getElementsByTagName("sampleIGSN");
			foreach($reduxsamples as $reduxsample){
				$sampleigsn=strtoupper($reduxsample->textContent);
			}
			
			if($aliquotigsn!=""){
				$modigsn=$aliquotigsn;
			}else{
				$modigsn=$sampleigsn;
			}
			
		}elseif($ecproject=="helios"){
			$heliossamples = $dom->getElementsByTagName("IGSN");
			foreach($heliossamples as $heliossample){
				$modigsn=strtoupper($heliossample->textContent);
			}
		}elseif($ecproject=="cronus"){
			$cronussamples = $dom->getElementsByTagName("igsn");
			foreach($cronussamples as $cronussample){
				$modigsn=strtoupper($cronussample->textContent);
			}
		}elseif($ecproject=="igor"){
			$igorsamples = $dom->getElementsByTagName("igsn");
			foreach($igorsamples as $igorsample){
				$modigsn=strtoupper($igorsample->textContent);
			}
		}else{
			$moderror="invalid file";
		}

		
		if($modigsn!=""){
			//echo "igsn: $modigsn<br>";
			/* ************* changed to allow multiple IGSNs JMA 02-21-2011************************* 
			$igsncount=$db->get_var("select count(*) from sample where igsn='$modigsn'");
			if($overwrite!="yes"){
				if($igsncount>0){
					//comment this out so IGSN isn't checked -- temporary
					$moderror="Sample with this IGSN: $modigsn already exists in database. Please delete and try again.";
				}
			}else{ //overwrite = yes, so we need to delete if exists
				if($igsncount>0){
					$myexistcount=$db->get_var("select count(*) from sample where igsn='$modigsn' and username='$username'");
					if($myexistcount > 0){
						$db->query("delete from sample_age where sample_pkey in (select sample_pkey from sample where igsn='$modigsn' and username='$username')");
						$db->query("delete from sample where igsn='$modigsn' and username='$username'");
					}else{
						$moderror="Sample with this IGSN already exists in database and you are not the owner. Cannot overwrite.";
					}
				}
			}
			****************************************************************************************
			*/
		}else{ //modigsn==""
		
			$moderror="No IGSN provided. Please correct and try again.";
		
		}

		//if no errors
		if($moderror==""){

			//now on to the parsing
			
			//we need to make a list of pkeys for use later to update public flag
			$pkeylist="";
			$pkeydelim="";
			
			//first, if file is redux
			if($ecproject=="redux"){
				//$xmlschema = '52108schema.xsd';
				//$xmlschema = '040609reduxschema.xsd';
				$xmlschema = 'upbreduxschemas/AliquotXMLSchema.xsd';
				if ($dom->schemaValidate($xmlschema)) {
				
					
						
					$geochron_pkey=$db->get_var("select nextval('geochron_seq')");
					

					
					$aliquots = $dom->getElementsByTagName("Aliquot");



					foreach($aliquots as $aliquot){
					
						$material="";

						$mymaterials=$aliquot->getElementsByTagName("mineralName");
						foreach($mymaterials as $mymaterial){
							$material=$mymaterial->textContent;
						}

						
						$reduxsamples = $aliquot->getElementsByTagName("aliquotIGSN");
						foreach($reduxsamples as $reduxsample){
							$aliquotigsn=$reduxsample->textContent;
						}
			
						$reduxsamples = $aliquot->getElementsByTagName("sampleIGSN");
						foreach($reduxsamples as $reduxsample){
							$sampleigsn=strtoupper($reduxsample->textContent);
						}
			
						if($aliquotigsn!="" && $aliquotigsn != "Deprecated"){
							$modigsn=$aliquotigsn;
						}else{
							$modigsn=$sampleigsn;
							$aliquotigsn=$sampleigsn;
						}

						$aliguotnames=$aliquot->getElementsByTagName("aliquotName");
						foreach($aliguotnames as $aliguotname){
							$myaliquotname=$aliguotname->textContent;
						}

						
						//echo "aliquot igsn: $myaliguotigsn<br>";

						//check for uniqueness of igsn/aliquotname
						//$igsncount=$db->get_var("select count(*) from sample where igsn='$modigsn' and aliquotname='$myaliquotname'");
						$igsncount=$db->get_var("select count(*) from sample where igsn='$modigsn'"); //Only look for aliquot_igsn per JB 09/02/2016
						if($overwrite!="yes"){
							if($igsncount>0){
								//comment this out so IGSN isn't checked -- temporary
								//$moderror="Sample with this Aliquot IGSN: $modigsn and Aliquotname: $myaliquotname already exists in database. Please delete and try again.";
								$moderror="Sample with this Aliquot IGSN: $modigsn already exists in database. Please delete and try again.";
							}
						}else{ //overwrite = yes, so we need to delete if exists
							if($igsncount>0){
								//$myexistcount=$db->get_var("select count(*) from sample where igsn='$modigsn' and aliquotname='$myaliquotname' and username='$username'");
								//$myexistcount=$db->get_var("select count(*) from sample where igsn='$modigsn' and aliquotname='$myaliquotname' and userpkey=$userpkey");
								$myexistcount=$db->get_var("select count(*) from sample where igsn='$modigsn' and userpkey=$userpkey");
								if($myexistcount > 0){
								
									//$thispkey = $db->get_var("select sample_pkey from sample where igsn='$modigsn' and aliquotname='$myaliquotname' and userpkey=$userpkey");
									$thispkey = $db->get_var("select sample_pkey from sample where igsn='$modigsn' and userpkey=$userpkey");
								
									//$db->query("delete from sample_age where sample_pkey in (select sample_pkey from sample where igsn='$modigsn' and aliquotname='$myaliquotname' and userpkey=$userpkey)");
									$db->query("delete from sample_age where sample_pkey in (select sample_pkey from sample where igsn='$modigsn' and userpkey=$userpkey)");
									
									//$db->query("delete from sample where igsn='$modigsn' and aliquotname='$myaliquotname' and userpkey=$userpkey");
									$db->query("delete from sample where igsn='$modigsn' and userpkey=$userpkey");
								

									
									//also look to see if this sample belongs to any datasets
									$dsrows = $db->get_results("select dataset_pkey from datasetrelate where sample_pkey = $thispkey");
									$datasetsarray=array();
									if($db->num_rows > 0){
										foreach($dsrows as $dsrow){
											$datasetsarray[]=$dsrow->dataset_pkey;
										}
									}
									
									//echo "datasets:";print_r($datasetsarray);
								
									$db->query("delete from datasetrelate where sample_pkey = $thispkey");
								
								}else{
									$moderror="Sample with IGSN: $modigsn and Aliquotname: $myaliquotname already exists in database and you are not the owner. Cannot overwrite.";
									$moderror="Sample with Aliquot IGSN: $modigsn already exists in database and you are not the owner. Cannot overwrite.";
								}
							}
						}














						include("fetchigsn.php");

						if($moderror==""){
				
							$sample_pkey=$db->get_var("select nextval('sample_seq')");
							$pkeylist=$pkeylist.$pkeydelim.$sample_pkey;
							$pkeydelim=",";
							
							$laboratorynames=$aliquot->getElementsByTagName("laboratoryName");
							foreach($laboratorynames as $laboratoryname){
								$mylaboratoryname=$laboratoryname->textContent;
							}
							//echo "laboratoryname : $mylaboratoryname<br>";
				
							$analystnames=$aliquot->getElementsByTagName("analystName");
							foreach($analystnames as $analystname){
								$myanalystname=$analystname->textContent;
							}
							//echo "analystname : $myanalystname<br>";

							$mineralnames=$aliquot->getElementsByTagName("standardMineralName");
							foreach($mineralnames as $mineralname){
								$mymineral=$mineralname->textContent;
							}
							//echo "analystname : $myanalystname<br>";

							$uploaddate=date("m/d/Y h:i:s a");
							//echo "uploaddate: $uploaddate<br>";
							
							$filename="$geochron_pkey.xml";
							//echo "filename: $filename<br>";
							
							//echo "orig_filename: ".$origfilename."<br>";
							
							//get new metadata here
							$mysesarss=$aliquot->getElementsByTagName("mySESARSampleMetadata");
							foreach($mysesarss as $mysesars){
							
								//$isupstream="TRUE";
								
								$mystratnames=$mysesars->getElementsByTagName("stratigraphicFormationName");
								foreach($mystratnames as $mystratname){
									$strat_name=$mystratname->textContent;
								}
								//echo "strat_name: $strat_name<br>";

								$mystratgeoages=$mysesars->getElementsByTagName("stratigraphicGeologicAgeMa");
								foreach($mystratgeoages as $mystratgeoage){
									$strat_geo_age=trim($mystratgeoage->textContent);
								}
								//echo "strat_geo_age: $strat_geo_age<br>";

								$myminstratabsages=$mysesars->getElementsByTagName("stratigraphicMinAbsoluteAgeMa");
								foreach($myminstratabsages as $myminstratabsage){
									$min_strat_abs_age=$myminstratabsage->textContent;
								}
								//echo "min_strat_abs_age: $min_strat_abs_age<br>";

								$mymaxstratabsages=$mysesars->getElementsByTagName("stratigraphicMaxAbsoluteAgeMa");
								foreach($mymaxstratabsages as $mymaxstratabsage){
									$max_strat_abs_age=$mymaxstratabsage->textContent;
								}
								//echo "max_strat_abs_age: $max_strat_abs_age<br>";

								$mydetritaltypes=$mysesars->getElementsByTagName("detritalType");
								foreach($mydetritaltypes as $mydetritaltype){
									$detritaltype=$mydetritaltype->textContent;
								}
								//echo "detritaltype: $detritaltype<br>";
								
								


							}
							

							$myanalysispurposes=$aliquot->getElementsByTagName("analysisPurpose");
							foreach($myanalysispurposes as $myanalysispurpose){
								$analysispurpose=$myanalysispurpose->textContent;
							}

							//look for detrital in analysispurpose and set upstream=true if found
							$pos = strpos(strtolower($analysispurpose),"detrital");
							
							if($pos === false) {
							 // string needle NOT found in haystack
							}
							else {
							 $isupstream="TRUE";
							}
							
							
							
							
							
							
							
							
							
							
							//this clears up junk from redux
							if($min_strat_abs_age=="0.0" && $max_strat_abs_age=="0.0"){
								$min_strat_abs_age="";
								$max_strat_abs_age="";
							}
							
							//figure out strat_age_min and strat_age_max here
							if($min_strat_abs_age!="" && $max_strat_abs_age!=""){
								$strat_age_min=$min_strat_abs_age;
								$strat_age_max=$max_strat_abs_age;
							}elseif($strat_geo_age!=""){
								$thisagerow=$db->get_row("select * from geoages where xmllabel='$strat_geo_age'");
								$strat_age_min=$thisagerow->minage;
								$strat_age_max=$thisagerow->maxage;
							}
							
							if($strat_age_min=="-999999"){$strat_age_min="";}
							
							//echo "strat_age_min: $strat_age_min<br>";
							//echo "strat_age_max: $strat_age_max<br>";

							//insert sample age here
							//age_min
							if($strat_age_min!="" && $strat_age_max!=""){
								//insert age row here
								$db->query("insert into sample_age
											(
												sample_age_pkey,
												sample_pkey,
												age_name,
												preferred,
												age_min,
												age_max
											)values(
												nextval('sample_age_seq'),
												$sample_pkey,
												'$analysispurpose',
												true,
												$strat_age_min,
												$strat_age_max
											)
											");
							}
							
							

							//also get the radiogenicIsotopeRatios
							$oldestfractiondate=-999;
							$youngestfractiondate=9999999999999;
							
							$myradiodates=$aliquot->getElementsByTagName("radiogenicIsotopeDates");
							foreach($myradiodates as $myradiodate){

								$myvaluemodels=$myradiodate->getElementsByTagName("ValueModel");
								foreach($myvaluemodels as $myvaluemodel){
									$myvaluenames=$myvaluemodel->getElementsByTagName("name");
									foreach($myvaluenames as $myvaluename){
										$thisvaluename=$myvaluename->textContent;
									}
									if($thisvaluename=="bestAge"){
										$myvalues=$myvaluemodel->getElementsByTagName("value");
										foreach($myvalues as $myvalue){
											$thisvalue=$myvalue->textContent;
											//echo "$thisvalue\n";
											if($thisvalue<$youngestfractiondate){$youngestfractiondate=$thisvalue;}
											if($thisvalue>$oldestfractiondate){$oldestfractiondate=$thisvalue;}
										}
									}
									
								}
								
								/*
								$myvalues=$myvaluemodel->getElementsByTagName("value");
								$myvalues=$myradiodate->getElementsByTagName("value");
								foreach($myvalues as $myvalue){
								
									$thisvalue=$myvalue->textContent;
									
									if($thisvalue<$youngestfractiondate){$youngestfractiondate=$thisvalue;}
									if($thisvalue>$oldestfractiondate){$oldestfractiondate=$thisvalue;}
								
								}
								*/
							}

							//echo "youngestfractiondate: $youngestfractiondate<br>";
							//echo "oldestfractiondate: $oldestfractiondate<br>";

							//check modigsn for GEG... if so, make upstream true

							/*
							$pos = strpos($modigsn,'GEG.');
							
							if($pos === false) {
								$isupstream="FALSE";
							}
							else {
								$isupstream="TRUE";
							}
							*/

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
										'$aliquotigsn', 
										'$sampleigsn', 
										'$myaliquotname', 
										'$isampledescription', 
										'$igeoobjecttype', 
										'$igeoobjectclassification', 
										'$icollectionmethod',
										'$strat_name',
										'$detritaltype',
										'$strat_geo_age', ";
										
										//$isampleid
										
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
										'$myanalystname',
										'$mylaboratoryname',
										'$uploaddate',
										$userpkey,
										'$filename',
										'$username',
										'$orig_filename',
										'redux',";
							if($ilongitude!="" & $ilatitude!=""){
							$querystring.="
										ST_PointFromText('POINT($ilongitude $ilatitude)',-1),";
							}
							$querystring.="
										$publ,
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
										'$mymineral',
										$isupstream,
										'$myaliquotname',
										'".strtolower($material)."',
										'$analysispurpose'
										)";
										
										
							//echo nl2br($querystring);
							
							$db->query($querystring);

							//put sample back into datasetrelate if necessary
							if(count($datasetsarray)>0){
								foreach($datasetsarray as $thisdsnum){
									//sample_pkey $sample_pkey=$db->get_var("select nextval('sample_seq')");
									$datasetrelatepkey=$db->get_var("select nextval('datasetrelate_seq')");
									$db->query("insert into datasetrelate values ($datasetrelatepkey, $thisdsnum, $sample_pkey)");
									
								}
							}
							
							$sampleagemodels = $aliquot->getElementsByTagName("sampleDateModels");
							foreach($sampleagemodels as $sampleagemodel){
								//echo "sampleagemodel found<br>";
								$mysampleagemodels = $sampleagemodel->getElementsByTagName("SampleDateModel");
								foreach($mysampleagemodels as $mysampleagemodel){
								
									//get sampleage pkey
									$sampleagepkey=$db->get_var("select nextval('sample_age_seq')");
									//echo "<br>sampleagepkey: $sampleagepkey<br>";
									
									$sampleagenames=$mysampleagemodel->getElementsByTagName("name");
									foreach($sampleagenames as $sampleagename){
										$mysampleagename=$sampleagename->textContent;
									}
									//echo "sampleagename: $mysampleagename<br>";
				
									$sampleagevalues=$mysampleagemodel->getElementsByTagName("value");
									foreach($sampleagevalues as $sampleagevalue){
										$mysampleagevalue=$sampleagevalue->textContent;
									}
									//echo "sampleagevalue: $mysampleagevalue<br>";
				
									$sampleageuncertaintytypes=$mysampleagemodel->getElementsByTagName("uncertaintyType");
									foreach($sampleageuncertaintytypes as $sampleageuncertaintytype){
										$mysampleageuncertaintytype=$sampleageuncertaintytype->textContent;
									}
									//echo "sampleageuncertaintytype: $mysampleageuncertaintytype<br>";
				
									$sampleageonesigmas=$mysampleagemodel->getElementsByTagName("oneSigma");
									foreach($sampleageonesigmas as $sampleageonesigma){
										$mysampleageonesigma=$sampleageonesigma->textContent;
									}
									//echo "sampleageonesigma: $mysampleageonesigma<br>";
									
									$sampleagemeansquareds=$mysampleagemodel->getElementsByTagName("meanSquaredWeightedDeviation");
									foreach($sampleagemeansquareds as $sampleagemeansquared){
										$mysampleagemeansquared=$sampleagemeansquared->textContent;
									}
									//echo "sampleagemeansquared: $mysampleagemeansquared<br>";
																	
									$sampleagepreferreds=$mysampleagemodel->getElementsByTagName("preferred");
									foreach($sampleagepreferreds as $sampleagepreferred){
										$mysampleagepreferred=$sampleagepreferred->textContent;
									}
									//echo "preferred: $mysampleagepreferred<br>";
									
									$sampleageinternalerrors=$mysampleagemodel->getElementsByTagName("internalError");
									foreach($sampleageinternalerrors as $sampleageinternalerror){
										$mysampleageinternalerror=$sampleageinternalerror->textContent;
									}
									//echo "sampleageinternalerror: $mysampleageinternalerror<br>";
									
									$sampleageinternalerrortces=$mysampleagemodel->getElementsByTagName("internalErrorWithTracerCalibrationError");
									foreach($sampleageinternalerrortces as $sampleageinternalerrortce){
										$mysampleageinternalerrortce=$sampleageinternalerrortce->textContent;
									}
									//echo "sampleageinternalerrortce: $mysampleageinternalerrortce<br>";
									
									$sampleageinternalerrortcdes=$mysampleagemodel->getElementsByTagName("internalErrorWithTracerCalibrationAndDecayConstantError");
									foreach($sampleageinternalerrortcdes as $sampleageinternalerrortcde){
										$mysampleageinternalerrortcde=$sampleageinternalerrortcde->textContent;
									}
									//echo "sampleageinternalerrortcde: $mysampleageinternalerrortcde<br>";
									
									$sampleageexplanations=$mysampleagemodel->getElementsByTagName("explanation");
									foreach($sampleageexplanations as $sampleageexplanation){
										$mysampleageexplanation=$sampleageexplanation->textContent;
									}
									//echo "sampleageexplanation: $mysampleageexplanation<br>";
									
									$sampleagecomments=$mysampleagemodel->getElementsByTagName("comment");
									foreach($sampleagecomments as $sampleagecomment){
										$mysampleagecomment=$sampleagecomment->textContent;
									}
									//echo "sampleagecomment: $mysampleagecomment<br>";
									
									
									$db->query("insert into sample_age (
										sample_age_pkey,
										sample_pkey,
										age_name,
										age_value,
										uncertainty_type,
										one_sigma,
										mswd,
										preferred,
										age_min,
										age_max,
										raw_age_value
									) values (
										$sampleagepkey,
										$sample_pkey,
										'$mysampleagename',
										$mysampleagevalue,
										'$mysampleageuncertaintytype',
										$mysampleageonesigma,
										$mysampleagemeansquared,
										'$mysampleagepreferred',
										$mysampleagevalue,
										$mysampleagevalue,
										$mysampleagevalue
									)");
									
									/*
									$db->query("insert into sampleage (
										sampleage_pkey,
										aliquot_pkey,
										name,
										savalue,
										uncertaintytype,
										onesigma,
										meansquared,
										internalerror,
										internalerrortce,
										internalerrortcde,
										explanation,
										sacomment
										) values (
										$sampleagepkey,
										$aliquot_pkey,
										'$mysampleagename',
										$mysampleagevalue,
										'$mysampleageuncertaintytype',
										$mysampleageonesigma,
										$mysampleagemeansquared,
										$mysampleageinternalerror,
										$mysampleageinternalerrortce,
										$mysampleageinternalerrortcde,
										'$mysampleageexplanation',
										'$mysampleagecomment'
									)");
									*/
								}//end for each mysampleagemodels
							}//end for each sampleagemodels
							
							//look for figures here
							$analysisimages = $aliquot->getElementsByTagName("AnalysisImage");
							foreach($analysisimages as $analysisimage){
								
								//imageType imageURL
								$myimagetypes=$analysisimage->getElementsByTagName("imageType");
								foreach($myimagetypes as $myimagetype){
									$imagetype=$myimagetype->textContent;
								}
								
								$myimageurls=$analysisimage->getElementsByTagName("imageURL");
								foreach($myimageurls as $myimageurl){
									$imageurl=$myimageurl->textContent;
								}
								
								$imagefilename=str_replace("http://www.geochronportal.org/uploadimages/","",$imageurl);
								
								//echo "imagetype: $imagetype --- imageurl: $imageurl<br>";
								//20029-concordia-n019-287-2_no19_tempConcordiaForUpload.svg
								/*
								
								10162
								http://www.geochron.org/concordias/fullsize/10162.jpg
								http://www.geochron.org/concordias/originals/10162.svg
								
								
								
								
								cp uploadimages/20029-concordia-n019-287-2_no19_tempConcordiaForUpload.svg /dev/shm/foo.svg
								/usr/bin/rsvg /dev/shm/foo.svg /local/public/mgg/web/www.geochron.org/htdocs/concordias/fullsize/10162.jpg
								*/







								if($imagetype=="concordia"){
								if($imagefilename!=""){
									//parse and whatnot here
									
									copy("uploadimages/$imagefilename", "concordias/originals/$sample_pkey.svg");
									copy("uploadimages/$imagefilename", "/dev/shm/$imagefilename");
									
									//exec("/usr/bin/rsvg /dev/shm/$imagefilename /local/public/mgg/web/www.geochron.org/htdocs/concordias/fullsize/$sample_pkey.jpg");

									$body = file_get_contents("/dev/shm/$imagefilename");

									$ch = curl_init();
									curl_setopt($ch, CURLOPT_URL,            "http://www.strabospot.org/svg2jpg.php" );
									curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
									curl_setopt($ch, CURLOPT_POST,           1 );
									curl_setopt($ch, CURLOPT_POSTFIELDS,     $body ); 
									curl_setopt($ch, CURLOPT_HTTPHEADER,     array('Content-Type: text/plain')); 
									$result=curl_exec ($ch);

									$filecontent = file_get_contents($result);
									file_put_contents("/local/public/mgg/web/www.geochron.org/htdocs/concordias/fullsize/$sample_pkey.jpg",$filecontent);

									$dom = new DomDocument();
									$dom->formatOutput = true;
									$dom->load("/dev/shm/$imagefilename");
								
									$content=$dom->saveXML();
								
									$content=explode("\n",$content);
									
									foreach($content as $line){
										
										if(stripos($line, "<text")>0){
											$xml = new SimpleXMLElement($line);
											$mytext=$xml[0];
											
											if($mytext=="COMPILED SAMPLE"){$xml->attributes()->style="font-size:15; ".$xml->attributes()->style; }
											if($mytext=="206"||$mytext=="238"||$mytext=="207"||$mytext=="235"){$xml->attributes()->style=str_replace("14","12", $xml->attributes()->style);}
											if($mytext=="Pb/"||$mytext=="U"){$xml->attributes()->style=str_replace("20","18", $xml->attributes()->style);}
															
											
											if($mytext>1 && $mytext!="206" && $mytext!="238" && $mytext!="207" && $mytext!="235" && $mytext > 90){
												//$xml[0].=" foo";
												$xml->attributes()->style="font-size:30; ".$xml->attributes()->style;
												$xml->attributes()->x=$xml->attributes()->x-80;
												$xml->attributes()->y=$xml->attributes()->y+15;
											}
											
											
											//echo $mytext."\n";
											$newline=$xml->asXML();
											$newline=str_replace("<?xml version=\"1.0\"?>\n","",$newline);
											$newfile.=$newline."\n";
								
								
								
										}else{ //not <text, but still echo line
										
											$newfile.=$line."\n";
										
										}
									
									
									
									}
									
									
									
								
									//print_r($xml);
									
									//print_r($xml->attributes()->x);
									
									//$xml->attributes()->x="555";
								
								
									//echo $xml->asXML();
								
									//echo "$newfile";
								
									$fh = fopen("/dev/shm/$imagefilename", 'w') or die("can't open concordia file");
									$stringData = "$newfile";
									fwrite($fh, $stringData);
								
									fclose($fh);
								
									//exec("/usr/bin/rsvg /dev/shm/$imagefilename /local/public/mgg/web/www.geochron.org/htdocs/concordias/work/$sample_pkey.png");

									$body = file_get_contents("/dev/shm/$imagefilename");

									$ch = curl_init();
									curl_setopt($ch, CURLOPT_URL,            "http://www.strabospot.org/svg2png.php" );
									curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
									curl_setopt($ch, CURLOPT_POST,           1 );
									curl_setopt($ch, CURLOPT_POSTFIELDS,     $body ); 
									curl_setopt($ch, CURLOPT_HTTPHEADER,     array('Content-Type: text/plain')); 
									$result=curl_exec ($ch);
									
									$filecontent = file_get_contents($result);

									file_put_contents("/local/public/mgg/web/www.geochron.org/htdocs/concordias/work/$sample_pkey.png",$filecontent);
									
									unlink("/dev/shm/$imagefilename");

									$filebase="/local/public/mgg/web/www.geochron.org/htdocs/concordias";
									exec("/usr/bin/convert $filebase/work/$sample_pkey.png -background '#EEEEEE' -flatten -trim -bordercolor white -border 10x10 -bordercolor '#DDDDDD' -border 3x3 $filebase/work/$sample_pkey.jpg");

									exec("/usr/bin/convert $filebase/work/$sample_pkey.jpg -resize 35x -alpha off $filebase/0/$sample_pkey.gif");
									exec("/usr/bin/convert $filebase/work/$sample_pkey.jpg -resize 35x -alpha off $filebase/1/$sample_pkey.gif");
									exec("/usr/bin/convert $filebase/work/$sample_pkey.jpg -resize 50x -alpha off $filebase/2/$sample_pkey.gif");
									exec("/usr/bin/convert $filebase/work/$sample_pkey.jpg -resize 65x -alpha off $filebase/3/$sample_pkey.gif");
									exec("/usr/bin/convert $filebase/work/$sample_pkey.jpg -resize 80x -alpha off $filebase/4/$sample_pkey.gif");
									exec("/usr/bin/convert $filebase/work/$sample_pkey.jpg -resize 95x -alpha off $filebase/5/$sample_pkey.gif");
									exec("/usr/bin/convert $filebase/work/$sample_pkey.jpg -resize 110x -alpha off  $filebase/6/$sample_pkey.gif");
									exec("/usr/bin/convert $filebase/work/$sample_pkey.jpg -resize 245x -alpha off  $filebase/sidebar/$sample_pkey.jpg");
									//exec("/bin/rm $filebase/work/$sample_pkey.jpg");
									//exec("/bin/rm $filebase/work/$sample_pkey.png");

								}//end if imagename!=""

								}//end if imagetype=concordia
								



								if($imagetype=="probability_density"){
								if($imagefilename!=""){
									//parse and whatnot here
									
									copy("uploadimages/$imagefilename", "probabilities/originals/$sample_pkey.svg");
									copy("uploadimages/$imagefilename", "/dev/shm/$imagefilename");
									
									// move down exec("/usr/bin/rsvg /dev/shm/$imagefilename /var/www/geochron/probabilities/fullsize/$sample_pkey.jpg");

									//exec("/usr/bin/rsvg /dev/shm/$imagefilename /local/public/mgg/web/www.geochron.org/htdocs/probabilities/work/$sample_pkey.png");

									//our server is crap, so we have to use strabospot to convert svg2png
									$body = file_get_contents("/dev/shm/$imagefilename");

									$ch = curl_init();
									curl_setopt($ch, CURLOPT_URL,            "http://www.strabospot.org/svg2png.php" );
									curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
									curl_setopt($ch, CURLOPT_POST,           1 );
									curl_setopt($ch, CURLOPT_POSTFIELDS,     $body ); 
									curl_setopt($ch, CURLOPT_HTTPHEADER,     array('Content-Type: text/plain')); 
									$result=curl_exec ($ch);

									$filecontent = file_get_contents($result);
									file_put_contents("/local/public/mgg/web/www.geochron.org/htdocs/probabilities/work/$sample_pkey.png",$filecontent);
									
									unlink("/dev/shm/$imagefilename");

									$filebase="/local/public/mgg/web/www.geochron.org/htdocs/probabilities";
									exec("/usr/bin/convert $filebase/work/$sample_pkey.png -background '#FFFFFF' -flatten -trim -bordercolor white -border 10x10 -bordercolor '#DDDDDD' -border 3x3 $filebase/fullsize/$sample_pkey.jpg");
									exec("/usr/bin/convert $filebase/work/$sample_pkey.png -background '#EEEEEE' -flatten -trim -bordercolor white -border 10x10 -bordercolor '#DDDDDD' -border 3x3 $filebase/work/$sample_pkey.jpg");
									exec("/usr/bin/convert $filebase/work/$sample_pkey.jpg -resize 35x -alpha off $filebase/0/$sample_pkey.gif");
									exec("/usr/bin/convert $filebase/work/$sample_pkey.jpg -resize 35x -alpha off $filebase/1/$sample_pkey.gif");
									exec("/usr/bin/convert $filebase/work/$sample_pkey.jpg -resize 50x -alpha off $filebase/2/$sample_pkey.gif");
									exec("/usr/bin/convert $filebase/work/$sample_pkey.jpg -resize 65x -alpha off $filebase/3/$sample_pkey.gif");
									exec("/usr/bin/convert $filebase/work/$sample_pkey.jpg -resize 80x -alpha off $filebase/4/$sample_pkey.gif");
									exec("/usr/bin/convert $filebase/work/$sample_pkey.jpg -resize 95x -alpha off $filebase/5/$sample_pkey.gif");
									exec("/usr/bin/convert $filebase/work/$sample_pkey.jpg -resize 110x -alpha off  $filebase/6/$sample_pkey.gif");
									exec("/usr/bin/convert $filebase/work/$sample_pkey.jpg -resize 245x -alpha off  $filebase/sidebar/$sample_pkey.jpg");
									//exec("/bin/rm $filebase/work/$sample_pkey.jpg");
									//exec("/bin/rm $filebase/work/$sample_pkey.png");

								}//end if imagename!=""

								}//end if imagetype=probability_density








								if($imagetype=="report_csv"){
									if($imagefilename!=""){
	
										copy("uploadimages/$imagefilename", "csvs/$sample_pkey.csv");
	
									}//end if imagename!=""
								}//end if imagetype=report_csv




















							}//end foreach analysisimages
							
							
							
							
							
							
							
							
							
							
							
							
							
							
							
							
							
							
							
							
							
							
							
							
							
							
							
							
							
							
							
						}//end if moderror="" (from fetchigsn)
					
					} //end for each aliquot

				}else{ //schema does not validate

					$errors = libxml_get_errors();

					foreach($errors as $err){
					
						$moderror=$moderror.$err->line.": ".$err->message;

					}

				
				}//end if $dom validates to schema

			}elseif($ecproject=="arar"){
				$xmlschema = 'ararschema.xsd';
				if ($dom->schemaValidate($xmlschema)) {

					$geochron_pkey=$db->get_var("select nextval('geochron_seq')");


					$ararsamples = $dom->getElementsByTagName("Sample");
					foreach($ararsamples as $ararsample){
					
						$material="";
						
						$modigsn=strtoupper($ararsample->getAttribute("igsn"));
						//remove this temporarily
						include("fetchigsn.php");

						//If IGSN provided Lat/Long is blank,
						//get latitude and longitude from sample XML here...
						//Check for DD, and throw error if not...
						
						
						//first, fix stupid 'Not Provided' but in SESAR... what a joke.
						if($ilatitude=="Not Provided"){
							$ilatitude="";
						}

						if($ilongitude=="Not Provided"){
							$ilongitude="";
						}


						
						if($ilatitude=="" && $ilongitude==""){
							//Get Lat/Long from XML
							$ilatitude=$ararsample->getAttribute("latitude");
							$ilongitude=$ararsample->getAttribute("longitude");
							
							if($ilongitude!=""){
								//OK, it is set, now check for numeric
								if(!is_numeric($ilongitude)){
									$moderror.="Longitude provided ($ilongitude) is not numeric. \n";
								}
							}

							if($ilatitude!=""){
								//OK, it is set, now check for numeric
								if(!is_numeric($ilatitude)){
									$moderror.="Latitude provided ($ilatitude) is not numeric. \n";
								}
							}


						
						}
						
						if($ilatitude=="" or $ilongitude==""){
							//both latitude and longitude are blank,
							//this is an error as they are both required.
							$moderror.="Latitude and Longitude cannot be blank. They must be populated in IGSN or Sample XML";
						}










						//check for uniqueness of igsn/aliquotname
						$igsncount=$db->get_var("select count(*) from sample where igsn='$modigsn' and sample_id='$isampleid'");
						if($overwrite!="yes"){
							if($igsncount>0){
								//comment this out so IGSN isn't checked -- temporary
								$moderror="Sample with this IGSN: $modigsn and Sample ID: $isampleid already exists in database. Please delete and try again.";
							}
						}else{ //overwrite = yes, so we need to delete if exists
							if($igsncount>0){
								//$myexistcount=$db->get_var("select count(*) from sample where igsn='$modigsn' and aliquotname='$myaliquotname' and username='$username'");
								$myexistcount=$db->get_var("select count(*) from sample where igsn='$modigsn' and sample_id='$isampleid' and userpkey=$userpkey");
								if($myexistcount > 0){
									$db->query("delete from sample_age where sample_pkey in (select sample_pkey from sample where igsn='$modigsn' and sample_id='$isampleid' and userpkey=$userpkey)");
									$db->query("delete from sample where igsn='$modigsn' and sample_id='$isampleid' and userpkey=$userpkey");
								}else{
									$moderror="Sample with IGSN: $modigsn and Sample ID: $isampleid already exists in database and you are not the owner. Cannot overwrite.";
								}
							}
						}











						if($moderror==""){
	
							$sample_pkey=$db->get_var("select nextval('sample_seq')");
							$pkeylist=$pkeylist.$pkeydelim.$sample_pkey;
							$pkeydelim=",";
	
							$myanalystname=$ararsample->getAttribute("analystName");
							//echo "analystname: $analystname<br>";
	
							$laboratoryname="";
							$lastlab="";
	
							$myparameters = $ararsample->getElementsByTagName("Parameters");
							foreach($myparameters as $myparameter){


							
							

								$myexperiments = $myparameter->getElementsByTagName("Experiment");
								foreach($myexperiments as $myexperiment){
									if($myexperiment->getAttribute("laboratory")!=$lastlab){
										$laboratoryname=$laboratoryname.$labdelim.$myexperiment->getAttribute("laboratory");
										$labdelim="; ";
										$lastlab=$myexperiment->getAttribute("laboratory");
										//$mymineral=$myexperiment->getAttribute("mineralName");
										$mymineral=$myexperiment->getAttribute("sampleMaterial");
									}
								}//end for each experiment
							}//end for each parameter
							
							//echo "lab name: $laboratoryname<br>";
							
							$uploaddate=date("m/d/Y h:i:s a");
							//echo "uploaddate: $uploaddate<br>";
							
							$filename="$geochron_pkey.xml";
							//echo "filename: $filename<br>";
							

							//echo($querystring);
										
							$mypreferredages = $ararsample->getElementsByTagName("PreferredAge");
							foreach($mypreferredages as $mypreferredage){
								
								$sampleagepkey=$db->get_var("select nextval('sample_age_seq')");
								
								$agename=$mypreferredage->getAttribute("preferredAgeType");

								$agevalue=$mypreferredage->getAttribute("preferredAge");

								$preferredageclassification=$mypreferredage->getAttribute("preferredAgeClassification");

								//look for detrital in analysispurpose and set upstream=true if found
								$pos = strpos(strtolower($agename),"detrital");
								
								if($pos === false) {
									//not detrital, so get age from xml file
								}
								else {
									$isupstream="TRUE";
								}



								$agevaluemin=$mypreferredage->getAttribute("preferredAge");	
								$uncertaintytype="ABS";
								$onesigma=$mypreferredage->getAttribute("preferredAgeSigma");
								//??? $mswd=$mypreferredage->getAttribute("");
								$preferred="1";
								
								$db->query("insert into sample_age (
									sample_age_pkey,
									sample_pkey,
									age_name,
									age_value,
									uncertainty_type,
									one_sigma,
									mswd,
									preferred,
									age_min,
									age_max
								) values (
									$sampleagepkey,
									$sample_pkey,
									'$agename',
									$agevalue,
									'$uncertaintytype',
									$onesigma,
									000,
									'$preferred',
									$agevalue,
									$agevalue
								)
								");
								
								/*
								echo("insert into sample_age (
									sample_age_pkey,
									sample_pkey,
									age_name,
									age_value,
									uncertainty_type,
									one_sigma,
									mswd,
									preferred,
									age_min,
									age_max
								) values (
									$sampleagepkey,
									$sample_pkey,
									'$agename',
									$agevalue,
									'$uncertaintytype',
									$onesigma,
									000,
									'$preferred',
									$agevalue,
									$agevalue
								)
								");
								*/
								
							}//end for each preferredage
							
							$myinterpretedagesgroups = $ararsample->getElementsByTagName("InterpretedAges");
							foreach($myinterpretedagesgroups as $myinterpretedagesgroup){
								$myinterpretedages = $myinterpretedagesgroup->getElementsByTagName("InterpretedAge");
								foreach($myinterpretedages as $myinterpretedage){
								
									$sampleagepkey=$db->get_var("select nextval('sample_age_seq')");
									
									//$agename=$myinterpretedage->getAttribute("interpretedAgeType");
									$agename=$myinterpretedage->getAttribute("ageType");

									//look for detrital in analysispurpose and set upstream=true if found
									$pos = strpos(strtolower($agename),"detrital");
									
									if($pos === false) {
									 // string needle NOT found in haystack
									}
									else {
									 $isupstream="TRUE";
									}

									//$agevalue=$myinterpretedage->getAttribute("interpretedAge");
									$agevalue=$myinterpretedage->getAttribute("age");

									$uncertaintytype="ABS";
									//$onesigma=$myinterpretedage->getAttribute("interpretedAgeSigma");
									$onesigma=$myinterpretedage->getAttribute("ageSigma");

									//??? $mswd=$mypreferredage->getAttribute("");
									$preferred="false";
									
									$db->query("insert into sample_age (
										sample_age_pkey,
										sample_pkey,
										age_name,
										age_value,
										uncertainty_type,
										one_sigma,
										mswd,
										preferred,
										age_min,
										age_max
									) values (
										$sampleagepkey,
										$sample_pkey,
										'$agename',
										$agevalue,
										'$uncertaintytype',
										$onesigma,
										000,
										'$preferred',
										$agevalue,
										$agevalue
									)
									");

									/*
									echo("insert into sample_age (
										sample_age_pkey,
										sample_pkey,
										age_name,
										age_value,
										uncertainty_type,
										one_sigma,
										mswd,
										preferred,
										age_min,
										age_max
									) values (
										$sampleagepkey,
										$sample_pkey,
										'$agename',
										$agevalue,
										'$uncertaintytype',
										$onesigma,
										000,
										'$preferred',
										$agevalue,
										$agevalue
									)
									");
									*/


	
								}// end foreach myinterpretedage
							
							}//end foreach myinterpretedagegroup



							$querystring="insert into sample ( 
										sample_pkey, 
										igsn, 
										parentigsn, 
										sample_id, 
										sample_description, 
										geoobjecttype, 
										geoobjectclass, 
										collectionmethod, ";
							if($ilongitude!="" & $ilatitude!=""){
							$querystring.="
										longitude, 
										latitude, ";
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
										material,
										upstream,
										purpose
										) values ( 
										$sample_pkey, 
										'$modigsn', 
										'$iparentigsn', 
										'$isampleid', 
										'$isampledescription', 
										'$igeoobjecttype', 
										'$igeoobjectclassification', 
										'$icollectionmethod',";
							if($ilongitude!="" & $ilatitude!=""){
							$querystring.="
										$ilongitude,
										$ilatitude,";
							}
							$querystring.="
										'$isamplecomment',
										'$myanalystname',
										'$laboratoryname',
										'$uploaddate',
										$userpkey,
										'$filename',
										'$username',
										'$orig_filename',
										'arar',";
							if($ilongitude!="" & $ilatitude!=""){
							$querystring.="
										ST_PointFromText('POINT($ilongitude $ilatitude)',-1),";
							}
							$querystring.="
										$publ,
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
										'$mymineral',
										'".strtolower($mymineral)."',
										$isupstream,
										'$preferredageclassification'
										)";
										
							//echo nl2br($querystring);
							
							$db->query($querystring);




						} //end if moderror="" from fetchigsn	
							
					
					}//end for each ararsample

					
				}else{ //arar file does not validate
				
					$errors = libxml_get_errors();

					foreach($errors as $err){
					
						$moderror=$moderror."Line ".$err->line.": ".$err->message;

					}
				
				}
			}elseif($ecproject=="helios"){
				$xmlschema = 'heliosschema.xsd';
				if ($dom->schemaValidate($xmlschema)) {

					$geochron_pkey=$db->get_var("select nextval('geochron_seq')");



					$heliossamples = $dom->getElementsByTagName("SampleForGeochron");
					foreach($heliossamples as $heliossample){
					
						//parse through aliquots here to get oldest_frac_date and youngest_frac_date
						//Aliquots->AliquotForGeochron->Age
						
						$oldest_frac_date=-99999;
						$youngest_frac_date=999999999;
						
						$myaliquots=$heliossample->getElementsByTagName("Aliquots");
						foreach($myaliquots as $myaliquot){
						
							$myaliquotforgeochrons=$myaliquot->getElementsByTagName("AliquotForGeochron");
							foreach($myaliquotforgeochrons as $myaliquotforgeochron){
							
								$myages=$myaliquotforgeochron->getElementsByTagName("Age");
								foreach($myages as $myage){
								
									$thisage=$myage->textContent;
									
									if($thisage > $oldest_frac_date){$oldest_frac_date = $thisage;}
									if($thisage < $youngest_frac_date){$youngest_frac_date = $thisage;}

								
								}
							
							}
						
						}
						
						if($youngest_frac_date==999999999){$youngest_frac_date="";}
						if($oldest_frac_date==-99999){$oldest_frac_date="";}

						if($oldest_frac_date != "" && $youngest_frac_date != ""){
						
							$oldest_frac_date=$oldest_frac_date*1000000;
							$youngest_frac_date=$youngest_frac_date*1000000;
						
						}
						
						// end of oldest_frac_date and youngest_frac_date stuff
						
						$material="";
						
						$mymaterials=$heliossample->getElementsByTagName("Mineral");
						foreach($mymaterials as $mymaterial){
							$material=$mymaterial->textContent;
						}
					
						$myigsns = $heliossample->getElementsByTagName("IGSN");
						foreach($myigsns as $myigsn){
							$modigsn=strtoupper($myigsn->textContent);
						}//end for myigsns
						
						include("fetchigsn.php");


	
							$sample_pkey=$db->get_var("select nextval('sample_seq')");
							$pkeylist=$pkeylist.$pkeydelim.$sample_pkey;
							$pkeydelim=",";
	
							$theanalystnames=$heliossample->getElementsByTagName("AnalystName");
							foreach($theanalystnames as $theanalystname){
								$myanalystname=$theanalystname->textContent;
							}

							$thelaboratorynames=$heliossample->getElementsByTagName("LaboratoryName");
							foreach($thelaboratorynames as $thelaboratoryname){
								$laboratoryname=$thelaboratoryname->textContent;
							}
							
							$thesampleids=$heliossample->getElementsByTagName("SampleName");
							foreach($thesampleids as $thesampleid){
								$sampleid=$thesampleid->textContent;
							}
							
							//echo "isampleid: $isampleid";exit();
							
							$mineralnames=$heliossample->getElementsByTagName("Mineral");
							foreach($mineralnames as $mineralname){
								$mymineral=$mineralname->textContent;
							}
							

							//check for uniqueness of igsn/aliquotname
							$igsncount=$db->get_var("select count(*) from sample where igsn='$modigsn' and sample_id='$sampleid'");
							if($overwrite!="yes"){
								if($igsncount>0){
									//comment this out so IGSN isn't checked -- temporary
									$moderror="Sample with this Unique Identifier: $modigsn and Sample ID: $sampleid already exists in database. Please delete and try again.";
								}
							}else{ //overwrite = yes, so we need to delete if exists
								if($igsncount>0){
									//$myexistcount=$db->get_var("select count(*) from sample where igsn='$modigsn' and aliquotname='$myaliquotname' and username='$username'");
									$myexistcount=$db->get_var("select count(*) from sample where igsn='$modigsn' and sample_id='$sampleid' and userpkey=$userpkey");
									if($myexistcount > 0){
										$db->query("delete from sample_age where sample_pkey in (select sample_pkey from sample where igsn='$modigsn' and sample_id='$sampleid' and userpkey=$userpkey)");
										$db->query("delete from sample where igsn='$modigsn' and sample_id='$sampleid' and userpkey=$userpkey");
									}else{
										$moderror="Sample with Unique Identifier: $modigsn and Sample ID: $sampleid already exists in database and you are not the owner. Cannot overwrite.";
									}
								}
							}

						if($moderror==""){
							
							//echo "lab name: $laboratoryname<br>";
							
							$uploaddate=date("m/d/Y h:i:s a");
							//echo "uploaddate: $uploaddate<br>";
							
							$filename="$geochron_pkey.xml";
							//echo "filename: $filename<br>";
							

										
							//ages here
							// age_name
							// age_value
							// uncertainty_type
							// one_sigma
							// mswd
							// preferred
							
							$theagewrappers=$heliossample->getElementsByTagName("Ages");
							foreach($theagewrappers as $theagewrapper){

								$theages=$theagewrapper->getElementsByTagName("Age");
								foreach($theages as $theage){
								
									$sampleagepkey=$db->get_var("select nextval('sample_age_seq')");

									$theagenames=$theage->getElementsByTagName("TypeOfAge");
									foreach($theagenames as $theagename){
										$age_name=$theagename->textContent;
									}


									//look for detrital in analysispurpose and set upstream=true if found
									$pos = strpos(strtolower($age_name),"detrital");
									
									if($pos === false) {
										// string needle NOT found in haystack
										$theagevalues=$theage->getElementsByTagName("SampleAge");
										foreach($theagevalues as $theagevalue){
											$age_value=$theagevalue->textContent;
										}
										$theminage=$age_value;
										$themaxage=$age_value;
									}
									else {
										$isupstream="TRUE";
										$theminage=$iagemin;
										$themaxage=$iagemax;
										$age_value=0;
									}


									//echo "theminage: $theminage<br>";
									//echo "themaxage: $themaxage<br>";


									$theonesigmas=$theage->getElementsByTagName("OneSigma");
									foreach($theonesigmas as $theonesigma){
										$one_sigma=$theonesigma->textContent;
									}
									
									//insert into db here
									
									$db->query("insert into sample_age (
										sample_age_pkey,
										sample_pkey,
										age_name,
										age_value,
										uncertainty_type,
										one_sigma,
										mswd,
										preferred,
										age_min,
										age_max
									) values (
										$sampleagepkey,
										$sample_pkey,
										'$age_name',
										$age_value,
										'ABS',
										$one_sigma,
										000,
										'1',
										$theminage,
										$themaxage
									)
									");
									
									

								}

							}
							
							//Not Provided
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
										collectionmethod, ";
							if($ilongitude!="" & $ilatitude!=""){
							$querystring.="
										longitude, 
										latitude, ";
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
										material,
										upstream
										";
							
							if($oldest_frac_date!="" && $oldest_frac_date!=""){
							
							$querystring.=",oldest_frac_date,
										youngest_frac_date,
										detrital_type
										";
							
							}
							
							
							$querystring.=") values ( 
										$sample_pkey, 
										'$modigsn', 
										'$iparentigsn', 
										'$isampleid', 
										'$isampledescription', 
										'$igeoobjecttype', 
										'$igeoobjectclassification', 
										'$icollectionmethod',";
							if($ilongitude!="" & $ilatitude!=""){
							$querystring.="
										$ilongitude,
										$ilatitude,";
							}
							$querystring.="
										'$isamplecomment',
										'$myanalystname',
										'$laboratoryname',
										'$uploaddate',
										$userpkey,
										'$filename',
										'$username',
										'$orig_filename',
										'helios',";
							if($ilongitude!="" & $ilatitude!=""){
							$querystring.="
										ST_PointFromText('POINT($ilongitude $ilatitude)',-1),";
							}
							$querystring.="
										$publ,
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
										'$mymineral',
										'".strtolower($mymineral)."',
										$isupstream";
										
							if($oldest_frac_date!="" && $oldest_frac_date!=""){
							
							$querystring.=",
											$oldest_frac_date,
											$youngest_frac_date,
											'$isampledescription'
											";
							
							}
										
							$querystring.="
										)";
										
							//echo nl2br($querystring);
							$db->query($querystring);




							
						} //end if moderror="" from fetchigsn	
							
					
					}//end for each heliossample

					
				}else{ //arar file does not validate
				
					$errors = libxml_get_errors();

					foreach($errors as $err){
					
						$moderror=$moderror."Line ".$err->line.": ".$err->message;

					}
				
				}

			}elseif($ecproject=="igor"){
				$xmlschema = 'igorschema.xsd';
				if ($dom->schemaValidate($xmlschema)) {

					$geochron_pkey=$db->get_var("select nextval('geochron_seq')");

					/*

					$igorsamples = $dom->getElementsByTagName("sampledata");
					foreach($igorsamples as $igorsample){
					
						//parse through aliquots here to get oldest_frac_date and youngest_frac_date
						//Aliquots->AliquotForGeochron->Age
						
						$oldest_frac_date=-99999;
						$youngest_frac_date=999999999;
						
						$myaliquots=$heliossample->getElementsByTagName("Aliquots");
						foreach($myaliquots as $myaliquot){
						
							$myaliquotforgeochrons=$myaliquot->getElementsByTagName("AliquotForGeochron");
							foreach($myaliquotforgeochrons as $myaliquotforgeochron){
							
								$myages=$myaliquotforgeochron->getElementsByTagName("Age");
								foreach($myages as $myage){
								
									$thisage=$myage->textContent;
									
									if($thisage > $oldest_frac_date){$oldest_frac_date = $thisage;}
									if($thisage < $youngest_frac_date){$youngest_frac_date = $thisage;}

								
								}
							
							}
						
						}
						
						if($youngest_frac_date==999999999){$youngest_frac_date="";}
						if($oldest_frac_date==-99999){$oldest_frac_date="";}

						if($oldest_frac_date != "" && $youngest_frac_date != ""){
						
							$oldest_frac_date=$oldest_frac_date*1000000;
							$youngest_frac_date=$youngest_frac_date*1000000;
						
						}
						
						// end of oldest_frac_date and youngest_frac_date stuff
						
						$material="";
						
						$mymaterials=$heliossample->getElementsByTagName("Mineral");
						foreach($mymaterials as $mymaterial){
							$material=$mymaterial->textContent;
						}
					
						$myigsns = $heliossample->getElementsByTagName("IGSN");
						foreach($myigsns as $myigsn){
							$modigsn=strtoupper($myigsn->textContent);
						}//end for myigsns
						
						include("fetchigsn.php");


	
						$sample_pkey=$db->get_var("select nextval('sample_seq')");
						$pkeylist=$pkeylist.$pkeydelim.$sample_pkey;
						$pkeydelim=",";

						$theanalystnames=$heliossample->getElementsByTagName("AnalystName");
						foreach($theanalystnames as $theanalystname){
							$myanalystname=$theanalystname->textContent;
						}

						$thelaboratorynames=$heliossample->getElementsByTagName("LaboratoryName");
						foreach($thelaboratorynames as $thelaboratoryname){
							$laboratoryname=$thelaboratoryname->textContent;
						}
						
						$thesampleids=$heliossample->getElementsByTagName("SampleName");
						foreach($thesampleids as $thesampleid){
							$sampleid=$thesampleid->textContent;
						}
						
						//echo "isampleid: $isampleid";exit();
						
						$mineralnames=$heliossample->getElementsByTagName("Mineral");
						foreach($mineralnames as $mineralname){
							$mymineral=$mineralname->textContent;
						}
						

						//check for uniqueness of igsn/aliquotname
						$igsncount=$db->get_var("select count(*) from sample where igsn='$modigsn' and sample_id='$sampleid'");
						if($overwrite!="yes"){
							if($igsncount>0){
								//comment this out so IGSN isn't checked -- temporary
								$moderror="Sample with this Unique Identifier: $modigsn and Sample ID: $sampleid already exists in database. Please delete and try again.";
							}
						}else{ //overwrite = yes, so we need to delete if exists
							if($igsncount>0){
								//$myexistcount=$db->get_var("select count(*) from sample where igsn='$modigsn' and aliquotname='$myaliquotname' and username='$username'");
								$myexistcount=$db->get_var("select count(*) from sample where igsn='$modigsn' and sample_id='$sampleid' and userpkey=$userpkey");
								if($myexistcount > 0){
									$db->query("delete from sample_age where sample_pkey in (select sample_pkey from sample where igsn='$modigsn' and sample_id='$sampleid' and userpkey=$userpkey)");
									$db->query("delete from sample where igsn='$modigsn' and sample_id='$sampleid' and userpkey=$userpkey");
								}else{
									$moderror="Sample with Unique Identifier: $modigsn and Sample ID: $sampleid already exists in database and you are not the owner. Cannot overwrite.";
								}
							}
						}

						if($moderror==""){
							
							//echo "lab name: $laboratoryname<br>";
							
							$uploaddate=date("m/d/Y h:i:s a");
							//echo "uploaddate: $uploaddate<br>";
							
							$filename="$geochron_pkey.xml";
							//echo "filename: $filename<br>";
							

										
							//ages here
							// age_name
							// age_value
							// uncertainty_type
							// one_sigma
							// mswd
							// preferred
							
							$theagewrappers=$heliossample->getElementsByTagName("Ages");
							foreach($theagewrappers as $theagewrapper){

								$theages=$theagewrapper->getElementsByTagName("Age");
								foreach($theages as $theage){
								
									$sampleagepkey=$db->get_var("select nextval('sample_age_seq')");

									$theagenames=$theage->getElementsByTagName("TypeOfAge");
									foreach($theagenames as $theagename){
										$age_name=$theagename->textContent;
									}


									//look for detrital in analysispurpose and set upstream=true if found
									$pos = strpos(strtolower($age_name),"detrital");
									
									if($pos === false) {
										// string needle NOT found in haystack
										$theagevalues=$theage->getElementsByTagName("SampleAge");
										foreach($theagevalues as $theagevalue){
											$age_value=$theagevalue->textContent;
										}
										$theminage=$age_value;
										$themaxage=$age_value;
									}
									else {
										$isupstream="TRUE";
										$theminage=$iagemin;
										$themaxage=$iagemax;
										$age_value=0;
									}


									//echo "theminage: $theminage<br>";
									//echo "themaxage: $themaxage<br>";


									$theonesigmas=$theage->getElementsByTagName("OneSigma");
									foreach($theonesigmas as $theonesigma){
										$one_sigma=$theonesigma->textContent;
									}
									
									//insert into db here
									
									$db->query("insert into sample_age (
										sample_age_pkey,
										sample_pkey,
										age_name,
										age_value,
										uncertainty_type,
										one_sigma,
										mswd,
										preferred,
										age_min,
										age_max
									) values (
										$sampleagepkey,
										$sample_pkey,
										'$age_name',
										$age_value,
										'ABS',
										$one_sigma,
										000,
										'1',
										$theminage,
										$themaxage
									)
									");
									
									

								}

							}
							
							//Not Provided
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
										collectionmethod, ";
							if($ilongitude!="" & $ilatitude!=""){
							$querystring.="
										longitude, 
										latitude, ";
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
										material,
										upstream
										";
							
							if($oldest_frac_date!="" && $oldest_frac_date!=""){
							
							$querystring.=",oldest_frac_date,
										youngest_frac_date,
										detrital_type
										";
							
							}
							
							
							$querystring.=") values ( 
										$sample_pkey, 
										'$modigsn', 
										'$iparentigsn', 
										'$isampleid', 
										'$isampledescription', 
										'$igeoobjecttype', 
										'$igeoobjectclassification', 
										'$icollectionmethod',";
							if($ilongitude!="" & $ilatitude!=""){
							$querystring.="
										$ilongitude,
										$ilatitude,";
							}
							$querystring.="
										'$isamplecomment',
										'$myanalystname',
										'$laboratoryname',
										'$uploaddate',
										$userpkey,
										'$filename',
										'$username',
										'$orig_filename',
										'helios',";
							if($ilongitude!="" & $ilatitude!=""){
							$querystring.="
										ST_PointFromText('POINT($ilongitude $ilatitude)',-1),";
							}
							$querystring.="
										$publ,
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
										'$mymineral',
										'".strtolower($mymineral)."',
										$isupstream";
										
							if($oldest_frac_date!="" && $oldest_frac_date!=""){
							
							$querystring.=",
											$oldest_frac_date,
											$youngest_frac_date,
											'$isampledescription'
											";
							
							}
										
							$querystring.="
										)";
										
							//echo nl2br($querystring);
							$db->query($querystring);




							
						} //end if moderror="" from fetchigsn	
							
					
					}//end for each igorsample
					
					*/

					
				}else{ //igor file does not validate
				
					$errors = libxml_get_errors();

					foreach($errors as $err){
					
						$moderror=$moderror."Line ".$err->line.": ".$err->message;

					}
				
				}



			}//end if project=="redux" or "arar" or "helios" or "igor"

		}//end if moderror==""



?>