<?php

/*
******************************************************************
Geochron REST API
My Controller
Author: Jason Ash (jasonash@ku.edu)
Description: This is the Geochron REST API.
******************************************************************
*/

libxml_use_internal_errors(true);

class GeochronRestClass
{

 	function GeochronRestClass($userpkey){
 		$this->userpkey=$userpkey;
 	}
 	
 	public function setDBtHandler($db){
 		$this->db=$db;
 	}

 	public function setUsername($username){
 		$this->username=$username;
 	}

	public function testfunc($value){

		echo "$value";exit();

	}

	public function getElementFromDom($varname,$dom){
		$elements=$dom->getElementsByTagName($varname);

		$valarray=array();
		
		foreach($elements as $element){
			$valarray[]=$element->textContent;
		}
		
		$value = pg_escape_string($valarray[0]);
		
		return $value;
	}

	public function getIgorSample($igsn){
	
		$filename = $this->db->get_var("select filename from sample where igsn='$igsn' and ecproject='igor' and userpkey=$this->userpkey limit 1");
		
		if($filename==""){
			header("Bad Request", true, 404);
			echo "<results>\n\t<error>yes</error>\n\t<message>Error: Sample $igsn not found.</message>\n</results>";
			exit();
		}else{
			$data = file_get_contents("../../files/$filename");
		}
		
		return $data;
	
	}
	
	public function insertIgorSample($xml){

		$username=$this->username;
		$userpkey=$this->userpkey;

		//first, check XML against schema
		$dom = new DomDocument;

		//Load the xml document in the DOMDocument object
		if($dom->LoadXML($xml)){

			$xmlschema = 'igorschema.xsd';

			if ($dom->schemaValidate($xmlschema)) {

				//OK, XML is good, let's continue to other checks

				//parse through aliquots here to get oldest_frac_date and youngest_frac_date
				//Aliquots->AliquotForGeochron->Age
				
				$oldest_frac_date=-99999;
				$youngest_frac_date=999999999;
				
				$mygrains=$dom->getElementsByTagName("graindata");
				foreach($mygrains as $mygrain){
				
					$thisage = $this->getElementFromDom("agelabel",$mygrain);

					if($thisage > $oldest_frac_date){$oldest_frac_date = $thisage;}
					if($thisage < $youngest_frac_date){$youngest_frac_date = $thisage;}

				}
				
				if($youngest_frac_date==999999999){$youngest_frac_date="";}
				if($oldest_frac_date==-99999){$oldest_frac_date="";}

				if($oldest_frac_date != "" && $youngest_frac_date != ""){
				
					$oldest_frac_date=$oldest_frac_date*1000000;
					$youngest_frac_date=$youngest_frac_date*1000000;
				
				}
				
				// end of oldest_frac_date and youngest_frac_date stuff
				
				$material = $this->getElementFromDom("mineral",$dom);
				
				$modigsn = $this->getElementFromDom("igsn",$dom);

				include("../../fetchigsn.php");

				$sample_pkey=$this->db->get_var("select nextval('sample_seq')");

				$myanalystname = $this->getElementFromDom("analyst",$dom);
				//$laboratoryname = "UT Geo-Thermochronometry Lab";
				$laboratoryname = "UT Chron";
				$sampleid = $this->getElementFromDom("sample",$dom);
				$mymineral = $this->getElementFromDom("mineral",$dom);
				$longitude = $this->getElementFromDom("lngdec",$dom);
				$latitude = $this->getElementFromDom("latdec",$dom);
				$purpose = $this->getElementFromDom("purpose",$dom);
				if($purpose=="detrital") $purpose="DetritalSpectrum";

				$isupstream="FALSE";

				$confidentiality = $this->getElementFromDom("confidentiality",$dom);
				if($confidentiality == "private"){
					$publ=0;
				}else{
					$publ=1;
				}				


				//check for uniqueness of igsn
				/*
				This is looking for IGSN and Sample ID
				$igsncount=$this->db->get_var("select count(*) from sample where igsn='$modigsn' and sample_id='$sampleid'");

				if($igsncount>0){
					$myexistcount=$db->get_var("select count(*) from sample where igsn='$modigsn' and sample_id='$sampleid' and userpkey=$this->userpkey");
					if($myexistcount > 0){
						$db->query("delete from sample_age where sample_pkey in (select sample_pkey from sample where igsn='$modigsn' and sample_id='$sampleid' and userpkey=$userpkey)");
						$db->query("delete from sample where igsn='$modigsn' and sample_id='$sampleid' and userpkey=$userpkey");
					}else{
						$moderror="Sample with Unique Identifier: $modigsn and Sample ID: $sampleid already exists in database and you are not the owner. Cannot overwrite.";
					}
				}
				*/
				
				
				//Check just for IGSN 02/15/2016
				$igsncount=$this->db->get_var("select count(*) from sample where igsn='$modigsn'");

				if($igsncount>0){
					$myexistcount=$this->db->get_var("select count(*) from sample where igsn='$modigsn' and userpkey=$this->userpkey");

					if($myexistcount > 0){
						$this->db->query("delete from sample_age where sample_pkey in (select sample_pkey from sample where igsn='$modigsn' and userpkey=$this->userpkey)");
						$this->db->query("delete from sample where igsn='$modigsn' and userpkey=$this->userpkey");
					}else{
						$moderror="Sample with Unique Identifier: $modigsn already exists in database and you are not the owner. Cannot overwrite.";
					}
				}

				if($moderror==""){
					
					//echo "lab name: $laboratoryname<br>";
					
					$uploaddate=date("m/d/Y h:i:s a");
					//echo "uploaddate: $uploaddate<br>";
					
					$geochron_pkey=$this->db->get_var("select nextval('geochron_seq')");
					$filename="$geochron_pkey.xml";

					if($purpose=="DetritalSpectrum"){ //only put in one age
					
						$isupstream = "TRUE";
					
						$sampleagepkey=$this->db->get_var("select nextval('sample_age_seq')");
						
						$agequerystring="insert into sample_age (
							sample_age_pkey,
							sample_pkey,
							age_name,
							preferred
						) values (
							$sampleagepkey,
							$sample_pkey,
							'DetritalSpectrum',
							1
						)
						";
						
						$this->db->query($agequerystring);
					
					}else{

						header("Bad Request", true, 400);
						echo "<results>\n\t<error>yes</error>\n\t<message>Error: Only detrital samples supported now.</message>\n</results>";
						exit();

						$sequencedatas=$dom->getElementsByTagName("sequencedata");
						foreach($sequencedatas as $sequencedata){

							$grains=$sequencedata->getElementsByTagName("graindata");
							foreach($grains as $grain){
						


							}

						}
					
					}//end if purpose == detritalspectrum
					
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
					if($longitude!="" & $latitude!=""){
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
					if($longitude!="" & $latitude!=""){
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
								'$sampleid', 
								'$isampledescription', 
								'$igeoobjecttype', 
								'$igeoobjectclassification', 
								'$icollectionmethod',";
					if($longitude!="" & $latitude!=""){
					$querystring.="
								$longitude,
								$latitude,";
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
								'igor',";
					if($longitude!="" & $latitude!=""){
					$querystring.="
								ST_PointFromText('POINT($longitude $latitude)',-1),";
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
								'$purpose'";
								
					if($oldest_frac_date!="" && $oldest_frac_date!=""){
					
					$querystring.=",
									$oldest_frac_date,
									$youngest_frac_date,
									'$isampledescription'
									";
					
					}
								
					$querystring.="
								)";
								
					//echo $querystring; exit();
					$this->db->query($querystring);

					//write file
					file_put_contents("../../files/$filename", $xml);

					$data = "<results>\n\t<error>no</error>\n\t<message>Sample $modigsn successfully uploaded.</message>\n</results>";

				}else{ //end if moderror="" from fetchigsn

					$moderror=htmlspecialchars($moderror);
					$moderror = str_replace("\n","",$moderror);
				
					$data = "<results>\n\t<error>yes</error>\n\t<message>Error: $moderror</message>\n</results>";

				}


			}else{ //schema does not validate.
			
				$errors = libxml_get_errors();
				foreach($errors as $err){
					$moderror=$moderror."Line ".$err->line.": ".$err->message;
				}

				$moderror=htmlspecialchars($moderror);
				$moderror = str_replace("\n","",$moderror);
				
				$data = "<results>\n\t<error>yes</error>\n\t<message>Error: $moderror Please check file and try again.</message>\n</results>";

			}

		}else{ //failed to load dom

			$errors = libxml_get_errors();

			foreach($errors as $err){
				$moderror=$moderror."Line ".$err->line.": ".$err->message;
			}
		
			$moderror=htmlspecialchars($moderror);
			$moderror = str_replace("\n","",$moderror);

			$data = "<results>\n\t<error>yes</error>\n\t<message>Error: $moderror Please check file and try again.</message>\n</results>";

		}
		
		return $data;
	}
 
 
 	public function deleteIgorSample($modigsn){
 		if($modigsn!=""){
			$igsncount=$this->db->get_var("select count(*) from sample where igsn='$modigsn'");

			if($igsncount>0){
				$myexistcount=$this->db->get_var("select count(*) from sample where igsn='$modigsn' and userpkey=$this->userpkey");
				if($myexistcount > 0){
					$this->db->query("delete from sample_age where sample_pkey in (select sample_pkey from sample where igsn='$modigsn' and userpkey=$this->userpkey and ecproject='igor')");
					$this->db->query("delete from sample where igsn='$modigsn' and userpkey=$this->userpkey and ecproject='igor'");
					header("Sample deleted", true, 204);
					$data = "<results>\n\t<error>no</error>\n\t<message>Sample $modigsn deleted.</message>\n</results>";
				}else{
					header("Bad Request", true, 400);
					$data = "<results>\n\t<error>yes</error>\n\t<message>Error: Sample with Unique Identifier: $modigsn already exists in database and you are not the owner. Cannot delete.</message>\n</results>";
				}
			}else{
				header("Bad Request", true, 404);
				$data = "<results>\n\t<error>yes</error>\n\t<message>Error: IGSN $modigsn not found.</message>\n</results>";
			}
 		}else{
 			header("Bad Request", true, 400);
 			$data = "<results>\n\t<error>yes</error>\n\t<message>Error: IGSN must be provided.</message>\n</results>";
 		}
 		
 		return $data;
 		
 	}

	public function findSample($modigsn){
		$myexistcount=$this->db->get_var("select count(*) from sample where igsn='$modigsn' and userpkey=$this->userpkey");
		if($myexistcount>-1){
			return true;
		}else{
			return false;
		}
	}




}

?>