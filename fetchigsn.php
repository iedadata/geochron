<?PHP
/**
 * fetchigsn.php
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

			
		//if($ecproject=="redux"){
		
		//look for GCH in igsn
		
		if(substr($modigsn,0,3)=="ZZZ"){
		
			//$moderror="Redux here";
			$lookigsn=str_replace("GCH.","",$modigsn);
		
			$igsndom = new DomDocument();		

			//$igsndom->load("http://www.geochronid.org/display.php?geochronid=$modigsn");
			$igsndom->load("http://www.geochronid.org/display.php?geochronid=$lookigsn");


			$igsnerrors = $igsndom->getElementsByTagName("error");
			foreach($igsnerrors as $igsnerror){
				$moderror="GeochronID ($lookigsn) not found in GeochronID database.";
			}

			$igsnsamples = $igsndom->getElementsByTagName("results");
			
			foreach($igsnsamples as $igsnsample){
			
				$xmlvarnames=array("isampleid","isampledescription","igeoobjecttype","igeoobjectclassification","icollectionmethod","imaterial","isamplecomment","icollector","imaterialclassification","iPrimaryLocationName","iPrimaryLocationType","iLocationDescription","iLocality","iLocalityDescription","iCountry","iProvice","iCounty","iCityOrTownship","iPlatform","iPlatformID","iOriginalArchivalInstitution","iOriginalArchivalContact","iMostRecentArchivalInstitution","iMostRecentArchivalContact");
				$xmltagnames=array("SampleID","SampleDescription","GeoObjectType","GeoObjectClassification","CollectionMethod","Material","SampleComment","Collector","MaterialClassification","PrimaryLocationName","PrimaryLocationType","LocationDescription","Locality","LocalityDescription","Country","Province","County","CityorTownship","Platform","PlatformID","OriginalArchivalInstitution","OriginalArchivalContact","MostRecentArchivalInstitution","MostRecentArchivalContact");
				
				for($u=0;$u<24;$u++){
					$thesevals = $igsnsample->getElementsByTagName($xmltagnames[$u]);
					foreach($thesevals as $theseval){
						//$isampledescription=addslashes($igsnsampledescription->textContent);
						eval("\$".$xmlvarnames[$u]."=\$theseval->textContent;");
					}
					
					//eval("echo \"".$xmlvarnames[$u]." = $".$xmlvarnames[$u]."<br>\";");
				}
			
				$goocoord="yes";
				$thesevals = $igsnsample->getElementsByTagName("Coordinates");
				foreach($thesevals as $theseval){
					if($goocoord=="yes"){
						$icoordstring=$theseval->textContent;
						$goocoord="no";
					}
				}

				$icoordarray=explode(",",$icoordstring);
				
				if($ilatitude==""){
				$ilatitude=trim($icoordarray[1]);
				}
				
				if($ilongitude==""){
				$ilongitude=trim($icoordarray[0]);
				}

				//echo "ilatitude = $ilatitude<br>";
				//echo "ilongitude = $ilongitude<br>";


			} //end foreach igsnsamples.

		}elseif(substr($modigsn,0,3)=="SSX"){

			//add SSR by default here for SESAR samples
			//(if no prefix given, it defaults to SESAR)
			if(substr($modigsn,0,3)!="SSX"){
				$modigsn="SSX.".$modigsn;
			}
			
			$lookigsn=str_replace("SSX.","",$modigsn);
			
			// this bit is for loading IGSN from SESAR
			$igsndom = new DomDocument();
			
			//http://beta.www.geosamples.org/sesarWeb/ws/display?igsn=
			
			//$igsndom->load("http://matisse.kgs.ku.edu/geochron2/testigsndoc.xml?igsn=$modigsn");
			
			//echo "$modigsn<br>";
			
			//$igsndom->load("http://beta.www.geosamples.org/sesarWeb/ws/display?igsn=$modigsn");
			//$igsndom->load("http://gfg.ldeo.columbia.edu/sesar/display.php?igsn=$modigsn");
			
			////////$igsndom->load("http://www.geosamples.org/display.php?igsn=$modigsn");
			
			$igsndom->load("https://sesardev.geosamples.org/webservices/display.php?igsn=$lookigsn");
			
			
			//http://app.geosamples.org/webservices/display.php?igsn=GEE0000O4
			//$igsndom->load("http://matisse.kgs.ku.edu/geochronid/fetchigsn.php?igsn=$modigsn");
			
			
			
			$igsnerrors = $igsndom->getElementsByTagName("error");
			foreach($igsnerrors as $igsnerror){
				$moderror="IGSN ($lookigsn) not found in SESAR database.";
			}
			
			/*
			$igsnerrors = $igsndom->getElementsByTagName("Error");
			foreach($igsnerrors as $igsnerror){
				$moderror="IGSN not found in SESAR database.";
			}
			
			$igsnerrors = $igsndom->getElementsByTagName("Display");
			foreach($igsnerrors as $igsnerror){
				$moderror="IGSN not found in SESAR database.";
			}
			*/
			
			
			//$igsnsamples = $igsndom->getElementsByTagName("results"); //broken 08152015
			
			$igsnsamples = $igsndom->getElementsByTagName("sample");
			
			//echo "count: ".count($igsnsamples);exit();
			//print_r($igsnsamples);exit();
			
			$sesardown="yes";
			
			foreach($igsnsamples as $igsnsample){
			
				$sesardown="no";
				//echo "foo";
			
				$igsnparentigsns=$igsnsample->getElementsByTagName("parent_igsn");
				foreach($igsnparentigsns as $igsnparentigsn){
					$iparentigsn=addslashes($igsnparentigsn->textContent);
					if($iparentigsn=="Not Provided"){
						$iparentigsn="";
					}
				}
				//echo "parentIGSN: $iparentigsn<br>";
				
				$igsnsampleids=$igsnsample->getElementsByTagName("name");
				foreach($igsnsampleids as $igsnsampleid){
					$isampleid=pg_escape_string($igsnsampleid->textContent);
				}
				//echo "SampleID: $isampleid<br>";exit();

				$agemins=$igsnsample->getElementsByTagName("age_min");
				foreach($agemins as $agemin){
					$iagemin=addslashes($agemin->textContent);
				}
				
				if($iagemin=="Not Provided"){$iagemin="";}
				
				//echo "agemin: $iagemin<br>";

				$agemaxs=$igsnsample->getElementsByTagName("age_max");
				foreach($agemaxs as $agemax){
					$iagemax=addslashes($agemax->textContent);
				}
				
				if($iagemax=="Not Provided"){$iagemax="";}
				
				//echo "agemax: $iagemax<br>";

				$igsnsampledescriptions=$igsnsample->getElementsByTagName("description");
				foreach($igsnsampledescriptions as $igsnsampledescription){
					$isampledescription=addslashes($igsnsampledescription->textContent);
				}
				//echo "SampleDescription: $isampledescription<br>";
				
				$igsngeoobjecttypes=$igsnsample->getElementsByTagName("sample_type");
				foreach($igsngeoobjecttypes as $igsngeoobjecttype){
					$igeoobjecttype=addslashes($igsngeoobjecttype->textContent);
				}
				//echo "GeoObjectType: $igeoobjecttype<br>";
				
				$igsngeoobjectclassifications=$igsnsample->getElementsByTagName("classification");
				foreach($igsngeoobjectclassifications as $igsngeoobjectclassification){
					$igeoobjectclassification=addslashes($igsngeoobjectclassification->textContent);
				}
				//echo "GeoObjectClassification: $igeoobjectclassification<br>";
				
				$igsncollectionmethods=$igsnsample->getElementsByTagName("collection_method");
				foreach($igsncollectionmethods as $igsncollectionmethod){
					$icollectionmethod=addslashes($igsncollectionmethod->textContent);
				}
				//echo "CollectionMethod: $icollectionmethod<br>";

				$igsnmaterials=$igsnsample->getElementsByTagName("material");
				foreach($igsnmaterials as $igsnmaterial){
					$imaterial=addslashes($igsnmaterial->textContent);
				}

				$igsnlatitudes=$igsnsample->getElementsByTagName("latitude");
				foreach($igsnlatitudes as $igsnlatitude){
					$ilatitude=addslashes($igsnlatitude->textContent);
				}

				$igsnlongitudes=$igsnsample->getElementsByTagName("longitude");
				foreach($igsnlongitudes as $igsnlongitude){
					$ilongitude=addslashes($igsnlongitude->textContent);
				}

	
				$igsnsamplecomments=$igsnsample->getElementsByTagName("comment");
				foreach($igsnsamplecomments as $igsnsamplecomment){
					$isamplecomment=addslashes($igsnsamplecomment->textContent);
				}
				//echo "SampleComment: $isamplecomment<br>";

				$igsncollectors=$igsnsample->getElementsByTagName("collector");
				foreach($igsncollectors as $igsncollector){
					$icollector=addslashes($igsncollector->textContent);
				}

				$igsnmaterialclassifications=$igsnsample->getElementsByTagName("classification");
				foreach($igsnmaterialclassifications as $igsnmaterialclassification){
					$imaterialclassification=$igsnmaterialclassification->textContent;
					//$imaterialclassification="foo";
				}

				/*
				PrimaryLocationName
				PrimaryLocationType
				LocationDescription
				Locality
				LocalityDescription
				Country
				Provice
				County
				CityOrTownship
				Platform
				PlatformID
				OriginalArchivalInstitution
				OriginalArchivalContact
				MostRecentArchivalInstitution
				MostRecentArchivalContact
				*/

				$igsnPrimaryLocationNames=$igsnsample->getElementsByTagName("primary_location_name");
				foreach($igsnPrimaryLocationNames as $igsnPrimaryLocationName){
					$iPrimaryLocationName=addslashes($igsnPrimaryLocationName->textContent);
				}

				$igsnPrimaryLocationTypes=$igsnsample->getElementsByTagName("primary_location_type");
				foreach($igsnPrimaryLocationTypes as $igsnPrimaryLocationType){
					$iPrimaryLocationType=addslashes($igsnPrimaryLocationType->textContent);
				}

				$igsnLocationDescriptions=$igsnsample->getElementsByTagName("location_description");
				foreach($igsnLocationDescriptions as $igsnLocationDescription){
					$iLocationDescription=addslashes($igsnLocationDescription->textContent);
				}

				$igsnLocalitys=$igsnsample->getElementsByTagName("locality");
				foreach($igsnLocalitys as $igsnLocality){
					$iLocality=addslashes($igsnLocality->textContent);
				}

				$igsnLocalityDescriptions=$igsnsample->getElementsByTagName("locality_description");
				foreach($igsnLocalityDescriptions as $igsnLocalityDescription){
					$iLocalityDescription=addslashes($igsnLocalityDescription->textContent);
				}

				$igsnCountrys=$igsnsample->getElementsByTagName("country");
				foreach($igsnCountrys as $igsnCountry){
					$iCountry=$igsnCountry->textContent;
				}

				$igsnProvices=$igsnsample->getElementsByTagName("province");
				foreach($igsnProvices as $igsnProvice){
					$iProvice=addslashes($igsnProvice->textContent);
				}

				$igsnCountys=$igsnsample->getElementsByTagName("county");
				foreach($igsnCountys as $igsnCounty){
					$iCounty=addslashes($igsnCounty->textContent);
				}

				$igsnCityOrTownships=$igsnsample->getElementsByTagName("city");
				foreach($igsnCityOrTownships as $igsnCityOrTownship){
					$iCityOrTownship=addslashes($igsnCityOrTownship->textContent);
				}

				$igsnPlatforms=$igsnsample->getElementsByTagName("platform_type");
				foreach($igsnPlatforms as $igsnPlatform){
					$iPlatform=$igsnPlatform->textContent;
				}

				$igsnPlatformIDs=$igsnsample->getElementsByTagName("platform_name");
				foreach($igsnPlatformIDs as $igsnPlatformID){
					$iPlatformID=$igsnPlatformID->textContent;
				}

				$igsnOriginalArchivalInstitutions=$igsnsample->getElementsByTagName("original_archive");
				foreach($igsnOriginalArchivalInstitutions as $igsnOriginalArchivalInstitution){
					$iOriginalArchivalInstitution=addslashes($igsnOriginalArchivalInstitution->textContent);
				}

				$igsnOriginalArchivalContacts=$igsnsample->getElementsByTagName("original_archive_contact");
				foreach($igsnOriginalArchivalContacts as $igsnOriginalArchivalContact){
					$iOriginalArchivalContact=addslashes($igsnOriginalArchivalContact->textContent);
				}

				$igsnMostRecentArchivalInstitutions=$igsnsample->getElementsByTagName("current_archive");
				foreach($igsnMostRecentArchivalInstitutions as $igsnMostRecentArchivalInstitution){
					$iMostRecentArchivalInstitution=addslashes($igsnMostRecentArchivalInstitution->textContent);
				}

				$igsnMostRecentArchivalContacts=$igsnsample->getElementsByTagName("current_archive_contact");
				foreach($igsnMostRecentArchivalContacts as $igsnMostRecentArchivalContact){
					$iMostRecentArchivalContact=addslashes($igsnMostRecentArchivalContact->textContent);
				}

				$isampleid=htmlentities($isampleid);
				$iagemin=htmlentities($iagemin);
				$iagemax=htmlentities($iagemax);
				$isampledescription=htmlentities($isampledescription);
				$igeoobjecttype=htmlentities($igeoobjecttype);
				$igeoobjectclassification=htmlentities($igeoobjectclassification);
				$icollectionmethod=htmlentities($icollectionmethod);
				$imaterial=htmlentities($imaterial);
				$ilatitude=htmlentities($ilatitude);
				$ilongitude=htmlentities($ilongitude);
				$isamplecomment=htmlentities($isamplecomment);
				$icollector=htmlentities($icollector);
				$imaterialclassification=htmlentities($imaterialclassification);
				$iPrimaryLocationName=htmlentities($iPrimaryLocationName);
				$iPrimaryLocationType=htmlentities($iPrimaryLocationType);
				$iLocationDescription=htmlentities($iLocationDescription);
				$iLocality=htmlentities($iLocality);
				$iLocalityDescription=htmlentities($iLocalityDescription);
				$iCountry=htmlentities($iCountry);
				$iProvice=htmlentities($iProvice);
				$iCounty=htmlentities($iCounty);
				$iCityOrTownship=htmlentities($iCityOrTownship);
				$iPlatform=htmlentities($iPlatform);
				$iPlatformID=htmlentities($iPlatformID);
				$iOriginalArchivalInstitution=htmlentities($iOriginalArchivalInstitution);
				$iOriginalArchivalContact=htmlentities($iOriginalArchivalContact);
				$iMostRecentArchivalInstitution=htmlentities($iMostRecentArchivalInstitution);
				$iMostRecentArchivalContact=htmlentities($iMostRecentArchivalContact);


			}//end igsn loader portion

			if($sesardown=="yes"){
				$moderror="SESAR database is offline.";
			}


			//$imaterialclassification="";







			if($iparentigsn != ""){
				$igsndom = new DomDocument();
				
				//http://beta.www.geosamples.org/sesarWeb/ws/display?igsn=
				
				//$igsndom->load("http://matisse.kgs.ku.edu/geochron2/testigsndoc.xml?igsn=$modigsn");
				
				//echo "parentigsn: $iparentigsn<br>";
				
				
				//$igsndom->load("http://beta.www.geosamples.org/sesarWeb/ws/display?igsn=$iparentigsn");
				//$igsndom->load("http://gfg.ldeo.columbia.edu/sesar/display.php?igsn=$iparentigsn");
				$igsndom->load("https://sesardev.geosamples.org/webservices/display.php?igsn=$iparentigsn");

				
				/* maybe not do error checking here?
				$igsnerrors = $igsndom->getElementsByTagName("Error");
				foreach($igsnerrors as $igsnerror){
					$moderror="IGSN not found in SESAR database.";
				}
				
				$igsnerrors = $igsndom->getElementsByTagName("Display");
				foreach($igsnerrors as $igsnerror){
					$moderror="IGSN not found in SESAR database.";
				}
				*/
				
				
				$igsnsamples = $igsndom->getElementsByTagName("sample");
				
				foreach($igsnsamples as $igsnsample){
					
					$igsnsampleids=$igsnsample->getElementsByTagName("SampleID");
					foreach($igsnsampleids as $igsnsampleid){
						
						if($isampleid==""){
						$isampleid=$igsnsampleid->textContent;
						}
					}
					//echo "SampleID: $isampleid<br>";
					
					$igsnsampledescriptions=$igsnsample->getElementsByTagName("SampleDescription");
					foreach($igsnsampledescriptions as $igsnsampledescription){
						
						if($isampledescription==""){
						$isampledescription=addslashes($igsnsampledescription->textContent);
						}
					}
					//echo "SampleDescription: $isampledescription<br>";
					
					$igsngeoobjecttypes=$igsnsample->getElementsByTagName("GeoObjectType");
					foreach($igsngeoobjecttypes as $igsngeoobjecttype){
						
						if($igeoobjecttype==""){
						$igeoobjecttype=$igsngeoobjecttype->textContent;
						}
					}
					//echo "GeoObjectType: $igeoobjecttype<br>";
					
					$igsngeoobjectclassifications=$igsnsample->getElementsByTagName("GeoObjectClassification");
					foreach($igsngeoobjectclassifications as $igsngeoobjectclassification){
						
						if($igeoobjectclassification==""){
						$igeoobjectclassification=$igsngeoobjectclassification->textContent;
						}
					}
					//echo "GeoObjectClassification: $igeoobjectclassification<br>";
					
					$igsncollectionmethods=$igsnsample->getElementsByTagName("CollectionMethod");
					foreach($igsncollectionmethods as $igsncollectionmethod){
						
						if($icollectionmethod==""){
						$icollectionmethod=$igsncollectionmethod->textContent;
						}
					}
					//echo "CollectionMethod: $icollectionmethod<br>";
					

					$igsnmaterials=$igsnsample->getElementsByTagName("Material");
					foreach($igsnmaterials as $igsnmaterial){
						
						if($imaterial==""){
						$imaterial=$igsnmaterial->textContent;
						}
					}

					
					$igsnstartlocations=$igsnsample->getElementsByTagName("StartLocation");
					foreach($igsnstartlocations as $igsnstartlocation){
						$igsncoordinates=$igsnstartlocation->getElementsByTagName("Coordinates");
						foreach($igsncoordinates as $igsncoordinate){
							$icoordstring=$igsncoordinate->textContent;
						}
						$icoordarray=explode(",",$icoordstring);
						
						if($ilatitude==""){
						$ilatitude=trim($icoordarray[0]);
						}
						
						if($ilongitude==""){
						$ilongitude=trim($icoordarray[1]);
						}
						//echo "Latitude: $ilatitude<br>";
						//echo "Longitude: $ilongitude<br>";
					}
		
					$igsnsamplecomments=$igsnsample->getElementsByTagName("SampleComment");
					foreach($igsnsamplecomments as $igsnsamplecomment){
						
						if($isamplecomment==""){
						$isamplecomment=addslashes($igsnsamplecomment->textContent);
						}
					}
					//echo "SampleComment: $isamplecomment<br>";
		

					$igsnmaterialclassifications=$igsnsample->getElementsByTagName("MaterialClassification");
					foreach($igsnmaterialclassifications as $igsnmaterialclassification){
						if($imaterialclassification==""){
							$imaterialclassification=$igsnmaterialclassification->textContent;
						}
					//$imaterialclassification="bar";
					}

					//$imaterialclassification="foo";


					$igsnexpeditions=$igsnsample->getElementsByTagName("Expedition");
					foreach($igsnexpeditions as $igsnexpedition){
						$igsncollectors=$igsnexpedition->getElementsByTagName("Collector");
						foreach($igsncollectors as $igsncollector){
							if($icollector==""){
								$icollector=addslashes($igsncollector->textContent);
							}
						//$icollector="blah";
						}
					}
	

					/*
					PrimaryLocationName
					PrimaryLocationType
					LocationDescription
					Locality
					LocalityDescription
					Country
					Provice
					County
					CityOrTownship
					Platform
					PlatformID
					OriginalArchivalInstitution
					OriginalArchivalContact
					MostRecentArchivalInstitution
					MostRecentArchivalContact
					*/


					$igsnPrimaryLocationNames=$igsnsample->getElementsByTagName("PrimaryLocationName");
					foreach($igsnPrimaryLocationNames as $igsnPrimaryLocationName){
						if($iPrimaryLocationName==""){
						$iPrimaryLocationName=addslashes($igsnPrimaryLocationName->textContent);
						}
					}

					$igsnPrimaryLocationTypes=$igsnsample->getElementsByTagName("PrimaryLocationType");
					foreach($igsnPrimaryLocationTypes as $igsnPrimaryLocationType){
						if($iPrimaryLocationType==""){
						$iPrimaryLocationType=addslashes($igsnPrimaryLocationType->textContent);
						}
					}

					$igsnLocationDescriptions=$igsnsample->getElementsByTagName("LocationDescription");
					foreach($igsnLocationDescriptions as $igsnLocationDescription){
						if($iLocationDescription==""){
						$iLocationDescription=addslashes($igsnLocationDescription->textContent);
						}
					}

					$igsnLocalitys=$igsnsample->getElementsByTagName("Locality");
					foreach($igsnLocalitys as $igsnLocality){
						if($iLocality==""){
						$iLocality=addslashes($igsnLocality->textContent);
						}
					}

					$igsnLocalityDescriptions=$igsnsample->getElementsByTagName("LocalityDescription");
					foreach($igsnLocalityDescriptions as $igsnLocalityDescription){
						if($iLocalityDescription==""){
						$iLocalityDescription=addslashes($igsnLocalityDescription->textContent);
						}
					}

					$igsnCountrys=$igsnsample->getElementsByTagName("Country");
					foreach($igsnCountrys as $igsnCountry){
						if($iCountry==""){
						$iCountry=$igsnCountry->textContent;
						}
					}

					$igsnProvices=$igsnsample->getElementsByTagName("Provice");
					foreach($igsnProvices as $igsnProvice){
						if($iProvice==""){
						$iProvice=addslashes($igsnProvice->textContent);
						}
					}

					$igsnCountys=$igsnsample->getElementsByTagName("County");
					foreach($igsnCountys as $igsnCounty){
						if($iCounty==""){
						$iCounty=addslashes($igsnCounty->textContent);
						}
					}

					$igsnCityOrTownships=$igsnsample->getElementsByTagName("CityOrTownship");
					foreach($igsnCityOrTownships as $igsnCityOrTownship){
						if($iCityOrTownship==""){
						$iCityOrTownship=addslashes($igsnCityOrTownship->textContent);
						}
					}

					$igsnPlatforms=$igsnsample->getElementsByTagName("Platform");
					foreach($igsnPlatforms as $igsnPlatform){
						if($iPlatform==""){
						$iPlatform=$igsnPlatform->textContent;
						}
					}

					$igsnPlatformIDs=$igsnsample->getElementsByTagName("PlatformID");
					foreach($igsnPlatformIDs as $igsnPlatformID){
						if($iPlatformID==""){
						$iPlatformID=$igsnPlatformID->textContent;
						}
					}

					$igsnOriginalArchivalInstitutions=$igsnsample->getElementsByTagName("OriginalArchivalInstitution");
					foreach($igsnOriginalArchivalInstitutions as $igsnOriginalArchivalInstitution){
						if($iOriginalArchivalInstitution==""){
						$iOriginalArchivalInstitution=$igsnOriginalArchivalInstitution->textContent;
						}
					}

					$igsnOriginalArchivalContacts=$igsnsample->getElementsByTagName("OriginalArchivalContact");
					foreach($igsnOriginalArchivalContacts as $igsnOriginalArchivalContact){
						if($iOriginalArchivalContact==""){
						$iOriginalArchivalContact=addslashes($igsnOriginalArchivalContact->textContent);
						}
					}

					$igsnMostRecentArchivalInstitutions=$igsnsample->getElementsByTagName("MostRecentArchivalInstitution");
					foreach($igsnMostRecentArchivalInstitutions as $igsnMostRecentArchivalInstitution){
						if($iMostRecentArchivalInstitution==""){
						$iMostRecentArchivalInstitution=addslashes($igsnMostRecentArchivalInstitution->textContent);
						}
					}

					$igsnMostRecentArchivalContacts=$igsnsample->getElementsByTagName("MostRecentArchivalContact");
					foreach($igsnMostRecentArchivalContacts as $igsnMostRecentArchivalContact){
						if($iMostRecentArchivalContact==""){
						$iMostRecentArchivalContact=addslashes($igsnMostRecentArchivalContact->textContent);
						}
					}



				}

			} //end if iparentigsn != ""

		
		}else{
			
			//add SSR by default here for SESAR samples
			//(if no prefix given, it defaults to SESAR)
			if(substr($modigsn,0,3)!="SSR"){
				//$modigsn="SSR.".$modigsn;
			}
			
			$lookigsn=str_replace("SSR.","",$modigsn);
			
			// this bit is for loading IGSN from SESAR
			$igsndom = new DomDocument();
			
			//http://beta.www.geosamples.org/sesarWeb/ws/display?igsn=
			
			//$igsndom->load("http://matisse.kgs.ku.edu/geochron2/testigsndoc.xml?igsn=$modigsn");
			
			//echo "$modigsn<br>";
			
			//$igsndom->load("http://beta.www.geosamples.org/sesarWeb/ws/display?igsn=$modigsn");
			//$igsndom->load("http://gfg.ldeo.columbia.edu/sesar/display.php?igsn=$modigsn");
			
			////////$igsndom->load("http://www.geosamples.org/display.php?igsn=$modigsn");
			
			//$igsndom->load("https://app.geosamples.org/webservices/display.php?igsn=$lookigsn");
			
			//echo "https://app.geosamples.org/webservices/display.php?igsn=$lookigsn";exit();
			
			//$stuff = file_get_contents("https://app.geosamples.org/webservices/display.php?igsn=$lookigsn");echo "stuff: ".$stuff;exit();
			
			$curl = curl_init();
			curl_setopt_array($curl, array(
				CURLOPT_RETURNTRANSFER => 1,
				CURLOPT_URL => "https://app.geosamples.org/webservices/display.php?igsn=$lookigsn"
			));
			
			$sesarxml = curl_exec($curl);
			
			//echo "sesarxml: ".$sesarxml;exit();
			
			$igsndom->loadXML($sesarxml);
			
			
			//http://app.geosamples.org/webservices/display.php?igsn=GEE0000O4
			//$igsndom->load("http://matisse.kgs.ku.edu/geochronid/fetchigsn.php?igsn=$modigsn");
			
			
			
			$igsnerrors = $igsndom->getElementsByTagName("error");
			foreach($igsnerrors as $igsnerror){
				$moderror=$igsnerror->textContent;
			}
			
			/*
			$igsnerrors = $igsndom->getElementsByTagName("Error");
			foreach($igsnerrors as $igsnerror){
				$moderror="IGSN not found in SESAR database.";
			}
			
			$igsnerrors = $igsndom->getElementsByTagName("Display");
			foreach($igsnerrors as $igsnerror){
				$moderror="IGSN not found in SESAR database.";
			}
			*/
			
			
			//$igsnsamples = $igsndom->getElementsByTagName("results"); //broken 08152015
			
			$igsnsamples = $igsndom->getElementsByTagName("sample");
			
			//echo "count: ".count($igsnsamples);exit();
			//print_r($igsnsamples);exit();
			
			if($moderror==""){
			
				$sesardown="yes";
							
				foreach($igsnsamples as $igsnsample){
		
					$sesardown="no";
					//echo "foo";
		
					$igsnparentigsns=$igsnsample->getElementsByTagName("parent_igsn");
					foreach($igsnparentigsns as $igsnparentigsn){
						$iparentigsn=addslashes($igsnparentigsn->textContent);
						if($iparentigsn=="Not Provided"){
							$iparentigsn="";
						}
					}
					//echo "parentIGSN: $iparentigsn<br>";
			
					$igsnsampleids=$igsnsample->getElementsByTagName("name");
					foreach($igsnsampleids as $igsnsampleid){
						$isampleid=pg_escape_string($igsnsampleid->textContent);
					}
					//echo "SampleID: $isampleid<br>";exit();

					$agemins=$igsnsample->getElementsByTagName("age_min");
					foreach($agemins as $agemin){
						$iagemin=addslashes($agemin->textContent);
					}
			
					if($iagemin=="Not Provided"){$iagemin="";}
			
					//echo "agemin: $iagemin<br>";

					$agemaxs=$igsnsample->getElementsByTagName("age_max");
					foreach($agemaxs as $agemax){
						$iagemax=addslashes($agemax->textContent);
					}
			
					if($iagemax=="Not Provided"){$iagemax="";}
			
					//echo "agemax: $iagemax<br>";

					$igsnsampledescriptions=$igsnsample->getElementsByTagName("description");
					foreach($igsnsampledescriptions as $igsnsampledescription){
						$isampledescription=addslashes($igsnsampledescription->textContent);
					}
					//echo "SampleDescription: $isampledescription<br>";
			
					$igsngeoobjecttypes=$igsnsample->getElementsByTagName("sample_type");
					foreach($igsngeoobjecttypes as $igsngeoobjecttype){
						$igeoobjecttype=addslashes($igsngeoobjecttype->textContent);
					}
					//echo "GeoObjectType: $igeoobjecttype<br>";
			
					$igsngeoobjectclassifications=$igsnsample->getElementsByTagName("classification");
					foreach($igsngeoobjectclassifications as $igsngeoobjectclassification){
						$igeoobjectclassification=addslashes($igsngeoobjectclassification->textContent);
					}
					//echo "GeoObjectClassification: $igeoobjectclassification<br>";
			
					$igsncollectionmethods=$igsnsample->getElementsByTagName("collection_method");
					foreach($igsncollectionmethods as $igsncollectionmethod){
						$icollectionmethod=addslashes($igsncollectionmethod->textContent);
					}
					//echo "CollectionMethod: $icollectionmethod<br>";

					$igsnmaterials=$igsnsample->getElementsByTagName("material");
					foreach($igsnmaterials as $igsnmaterial){
						$imaterial=addslashes($igsnmaterial->textContent);
					}

					$igsnlatitudes=$igsnsample->getElementsByTagName("latitude");
					foreach($igsnlatitudes as $igsnlatitude){
						$ilatitude=addslashes($igsnlatitude->textContent);
					}

					$igsnlongitudes=$igsnsample->getElementsByTagName("longitude");
					foreach($igsnlongitudes as $igsnlongitude){
						$ilongitude=addslashes($igsnlongitude->textContent);
					}


					$igsnsamplecomments=$igsnsample->getElementsByTagName("comment");
					foreach($igsnsamplecomments as $igsnsamplecomment){
						$isamplecomment=addslashes($igsnsamplecomment->textContent);
					}
					//echo "SampleComment: $isamplecomment<br>";

					$igsncollectors=$igsnsample->getElementsByTagName("collector");
					foreach($igsncollectors as $igsncollector){
						$icollector=addslashes($igsncollector->textContent);
					}

					$igsnmaterialclassifications=$igsnsample->getElementsByTagName("classification");
					foreach($igsnmaterialclassifications as $igsnmaterialclassification){
						$imaterialclassification=$igsnmaterialclassification->textContent;
						//$imaterialclassification="foo";
					}

					/*
					PrimaryLocationName
					PrimaryLocationType
					LocationDescription
					Locality
					LocalityDescription
					Country
					Provice
					County
					CityOrTownship
					Platform
					PlatformID
					OriginalArchivalInstitution
					OriginalArchivalContact
					MostRecentArchivalInstitution
					MostRecentArchivalContact
					*/

					$igsnPrimaryLocationNames=$igsnsample->getElementsByTagName("primary_location_name");
					foreach($igsnPrimaryLocationNames as $igsnPrimaryLocationName){
						$iPrimaryLocationName=addslashes($igsnPrimaryLocationName->textContent);
					}

					$igsnPrimaryLocationTypes=$igsnsample->getElementsByTagName("primary_location_type");
					foreach($igsnPrimaryLocationTypes as $igsnPrimaryLocationType){
						$iPrimaryLocationType=addslashes($igsnPrimaryLocationType->textContent);
					}

					$igsnLocationDescriptions=$igsnsample->getElementsByTagName("location_description");
					foreach($igsnLocationDescriptions as $igsnLocationDescription){
						$iLocationDescription=addslashes($igsnLocationDescription->textContent);
					}

					$igsnLocalitys=$igsnsample->getElementsByTagName("locality");
					foreach($igsnLocalitys as $igsnLocality){
						$iLocality=addslashes($igsnLocality->textContent);
					}

					$igsnLocalityDescriptions=$igsnsample->getElementsByTagName("locality_description");
					foreach($igsnLocalityDescriptions as $igsnLocalityDescription){
						$iLocalityDescription=addslashes($igsnLocalityDescription->textContent);
					}

					$igsnCountrys=$igsnsample->getElementsByTagName("country");
					foreach($igsnCountrys as $igsnCountry){
						$iCountry=$igsnCountry->textContent;
					}

					$igsnProvices=$igsnsample->getElementsByTagName("province");
					foreach($igsnProvices as $igsnProvice){
						$iProvice=addslashes($igsnProvice->textContent);
					}

					$igsnCountys=$igsnsample->getElementsByTagName("county");
					foreach($igsnCountys as $igsnCounty){
						$iCounty=addslashes($igsnCounty->textContent);
					}

					$igsnCityOrTownships=$igsnsample->getElementsByTagName("city");
					foreach($igsnCityOrTownships as $igsnCityOrTownship){
						$iCityOrTownship=addslashes($igsnCityOrTownship->textContent);
					}

					$igsnPlatforms=$igsnsample->getElementsByTagName("platform_type");
					foreach($igsnPlatforms as $igsnPlatform){
						$iPlatform=$igsnPlatform->textContent;
					}

					$igsnPlatformIDs=$igsnsample->getElementsByTagName("platform_name");
					foreach($igsnPlatformIDs as $igsnPlatformID){
						$iPlatformID=$igsnPlatformID->textContent;
					}

					$igsnOriginalArchivalInstitutions=$igsnsample->getElementsByTagName("original_archive");
					foreach($igsnOriginalArchivalInstitutions as $igsnOriginalArchivalInstitution){
						$iOriginalArchivalInstitution=addslashes($igsnOriginalArchivalInstitution->textContent);
					}

					$igsnOriginalArchivalContacts=$igsnsample->getElementsByTagName("original_archive_contact");
					foreach($igsnOriginalArchivalContacts as $igsnOriginalArchivalContact){
						$iOriginalArchivalContact=addslashes($igsnOriginalArchivalContact->textContent);
					}

					$igsnMostRecentArchivalInstitutions=$igsnsample->getElementsByTagName("current_archive");
					foreach($igsnMostRecentArchivalInstitutions as $igsnMostRecentArchivalInstitution){
						$iMostRecentArchivalInstitution=addslashes($igsnMostRecentArchivalInstitution->textContent);
					}

					$igsnMostRecentArchivalContacts=$igsnsample->getElementsByTagName("current_archive_contact");
					foreach($igsnMostRecentArchivalContacts as $igsnMostRecentArchivalContact){
						$iMostRecentArchivalContact=addslashes($igsnMostRecentArchivalContact->textContent);
					}

					$isampleid=htmlentities($isampleid);
					$iagemin=htmlentities($iagemin);
					$iagemax=htmlentities($iagemax);
					$isampledescription=htmlentities($isampledescription);
					$igeoobjecttype=htmlentities($igeoobjecttype);
					$igeoobjectclassification=htmlentities($igeoobjectclassification);
					$icollectionmethod=htmlentities($icollectionmethod);
					$imaterial=htmlentities($imaterial);
					$ilatitude=htmlentities($ilatitude);
					$ilongitude=htmlentities($ilongitude);
					$isamplecomment=htmlentities($isamplecomment);
					$icollector=htmlentities($icollector);
					$imaterialclassification=htmlentities($imaterialclassification);
					$iPrimaryLocationName=htmlentities($iPrimaryLocationName);
					$iPrimaryLocationType=htmlentities($iPrimaryLocationType);
					$iLocationDescription=htmlentities($iLocationDescription);
					$iLocality=htmlentities($iLocality);
					$iLocalityDescription=htmlentities($iLocalityDescription);
					$iCountry=htmlentities($iCountry);
					$iProvice=htmlentities($iProvice);
					$iCounty=htmlentities($iCounty);
					$iCityOrTownship=htmlentities($iCityOrTownship);
					$iPlatform=htmlentities($iPlatform);
					$iPlatformID=htmlentities($iPlatformID);
					$iOriginalArchivalInstitution=htmlentities($iOriginalArchivalInstitution);
					$iOriginalArchivalContact=htmlentities($iOriginalArchivalContact);
					$iMostRecentArchivalInstitution=htmlentities($iMostRecentArchivalInstitution);
					$iMostRecentArchivalContact=htmlentities($iMostRecentArchivalContact);


				}//end igsn loader portion
				
				if($sesardown=="yes"){
					$moderror="SESAR Database is offline.";
				}


			}

			//$imaterialclassification="";







			if($iparentigsn != ""){
				$igsndom = new DomDocument();
				
				//http://beta.www.geosamples.org/sesarWeb/ws/display?igsn=
				
				//$igsndom->load("http://matisse.kgs.ku.edu/geochron2/testigsndoc.xml?igsn=$modigsn");
				
				//echo "parentigsn: $iparentigsn<br>";
				
				
				//$igsndom->load("http://beta.www.geosamples.org/sesarWeb/ws/display?igsn=$iparentigsn");
				//$igsndom->load("http://gfg.ldeo.columbia.edu/sesar/display.php?igsn=$iparentigsn");
				$igsndom->load("http://app.geosamples.org/webservices/display.php?igsn=$iparentigsn");

				
				/* maybe not do error checking here?
				$igsnerrors = $igsndom->getElementsByTagName("Error");
				foreach($igsnerrors as $igsnerror){
					$moderror="IGSN not found in SESAR database.";
				}
				
				$igsnerrors = $igsndom->getElementsByTagName("Display");
				foreach($igsnerrors as $igsnerror){
					$moderror="IGSN not found in SESAR database.";
				}
				*/
				
				
				$igsnsamples = $igsndom->getElementsByTagName("sample");
				
				foreach($igsnsamples as $igsnsample){
					
					$igsnsampleids=$igsnsample->getElementsByTagName("SampleID");
					foreach($igsnsampleids as $igsnsampleid){
						
						if($isampleid==""){
						$isampleid=$igsnsampleid->textContent;
						}
					}
					//echo "SampleID: $isampleid<br>";
					
					$igsnsampledescriptions=$igsnsample->getElementsByTagName("SampleDescription");
					foreach($igsnsampledescriptions as $igsnsampledescription){
						
						if($isampledescription==""){
						$isampledescription=addslashes($igsnsampledescription->textContent);
						}
					}
					//echo "SampleDescription: $isampledescription<br>";
					
					$igsngeoobjecttypes=$igsnsample->getElementsByTagName("GeoObjectType");
					foreach($igsngeoobjecttypes as $igsngeoobjecttype){
						
						if($igeoobjecttype==""){
						$igeoobjecttype=$igsngeoobjecttype->textContent;
						}
					}
					//echo "GeoObjectType: $igeoobjecttype<br>";
					
					$igsngeoobjectclassifications=$igsnsample->getElementsByTagName("GeoObjectClassification");
					foreach($igsngeoobjectclassifications as $igsngeoobjectclassification){
						
						if($igeoobjectclassification==""){
						$igeoobjectclassification=$igsngeoobjectclassification->textContent;
						}
					}
					//echo "GeoObjectClassification: $igeoobjectclassification<br>";
					
					$igsncollectionmethods=$igsnsample->getElementsByTagName("CollectionMethod");
					foreach($igsncollectionmethods as $igsncollectionmethod){
						
						if($icollectionmethod==""){
						$icollectionmethod=$igsncollectionmethod->textContent;
						}
					}
					//echo "CollectionMethod: $icollectionmethod<br>";
					

					$igsnmaterials=$igsnsample->getElementsByTagName("Material");
					foreach($igsnmaterials as $igsnmaterial){
						
						if($imaterial==""){
						$imaterial=$igsnmaterial->textContent;
						}
					}

					
					$igsnstartlocations=$igsnsample->getElementsByTagName("StartLocation");
					foreach($igsnstartlocations as $igsnstartlocation){
						$igsncoordinates=$igsnstartlocation->getElementsByTagName("Coordinates");
						foreach($igsncoordinates as $igsncoordinate){
							$icoordstring=$igsncoordinate->textContent;
						}
						$icoordarray=explode(",",$icoordstring);
						
						if($ilatitude==""){
						$ilatitude=trim($icoordarray[0]);
						}
						
						if($ilongitude==""){
						$ilongitude=trim($icoordarray[1]);
						}
						//echo "Latitude: $ilatitude<br>";
						//echo "Longitude: $ilongitude<br>";
					}
		
					$igsnsamplecomments=$igsnsample->getElementsByTagName("SampleComment");
					foreach($igsnsamplecomments as $igsnsamplecomment){
						
						if($isamplecomment==""){
						$isamplecomment=addslashes($igsnsamplecomment->textContent);
						}
					}
					//echo "SampleComment: $isamplecomment<br>";
		

					$igsnmaterialclassifications=$igsnsample->getElementsByTagName("MaterialClassification");
					foreach($igsnmaterialclassifications as $igsnmaterialclassification){
						if($imaterialclassification==""){
							$imaterialclassification=$igsnmaterialclassification->textContent;
						}
					//$imaterialclassification="bar";
					}

					//$imaterialclassification="foo";


					$igsnexpeditions=$igsnsample->getElementsByTagName("Expedition");
					foreach($igsnexpeditions as $igsnexpedition){
						$igsncollectors=$igsnexpedition->getElementsByTagName("Collector");
						foreach($igsncollectors as $igsncollector){
							if($icollector==""){
								$icollector=addslashes($igsncollector->textContent);
							}
						//$icollector="blah";
						}
					}
	

					/*
					PrimaryLocationName
					PrimaryLocationType
					LocationDescription
					Locality
					LocalityDescription
					Country
					Provice
					County
					CityOrTownship
					Platform
					PlatformID
					OriginalArchivalInstitution
					OriginalArchivalContact
					MostRecentArchivalInstitution
					MostRecentArchivalContact
					*/


					$igsnPrimaryLocationNames=$igsnsample->getElementsByTagName("PrimaryLocationName");
					foreach($igsnPrimaryLocationNames as $igsnPrimaryLocationName){
						if($iPrimaryLocationName==""){
						$iPrimaryLocationName=addslashes($igsnPrimaryLocationName->textContent);
						}
					}

					$igsnPrimaryLocationTypes=$igsnsample->getElementsByTagName("PrimaryLocationType");
					foreach($igsnPrimaryLocationTypes as $igsnPrimaryLocationType){
						if($iPrimaryLocationType==""){
						$iPrimaryLocationType=addslashes($igsnPrimaryLocationType->textContent);
						}
					}

					$igsnLocationDescriptions=$igsnsample->getElementsByTagName("LocationDescription");
					foreach($igsnLocationDescriptions as $igsnLocationDescription){
						if($iLocationDescription==""){
						$iLocationDescription=addslashes($igsnLocationDescription->textContent);
						}
					}

					$igsnLocalitys=$igsnsample->getElementsByTagName("Locality");
					foreach($igsnLocalitys as $igsnLocality){
						if($iLocality==""){
						$iLocality=addslashes($igsnLocality->textContent);
						}
					}

					$igsnLocalityDescriptions=$igsnsample->getElementsByTagName("LocalityDescription");
					foreach($igsnLocalityDescriptions as $igsnLocalityDescription){
						if($iLocalityDescription==""){
						$iLocalityDescription=addslashes($igsnLocalityDescription->textContent);
						}
					}

					$igsnCountrys=$igsnsample->getElementsByTagName("Country");
					foreach($igsnCountrys as $igsnCountry){
						if($iCountry==""){
						$iCountry=$igsnCountry->textContent;
						}
					}

					$igsnProvices=$igsnsample->getElementsByTagName("Provice");
					foreach($igsnProvices as $igsnProvice){
						if($iProvice==""){
						$iProvice=addslashes($igsnProvice->textContent);
						}
					}

					$igsnCountys=$igsnsample->getElementsByTagName("County");
					foreach($igsnCountys as $igsnCounty){
						if($iCounty==""){
						$iCounty=addslashes($igsnCounty->textContent);
						}
					}

					$igsnCityOrTownships=$igsnsample->getElementsByTagName("CityOrTownship");
					foreach($igsnCityOrTownships as $igsnCityOrTownship){
						if($iCityOrTownship==""){
						$iCityOrTownship=addslashes($igsnCityOrTownship->textContent);
						}
					}

					$igsnPlatforms=$igsnsample->getElementsByTagName("Platform");
					foreach($igsnPlatforms as $igsnPlatform){
						if($iPlatform==""){
						$iPlatform=$igsnPlatform->textContent;
						}
					}

					$igsnPlatformIDs=$igsnsample->getElementsByTagName("PlatformID");
					foreach($igsnPlatformIDs as $igsnPlatformID){
						if($iPlatformID==""){
						$iPlatformID=$igsnPlatformID->textContent;
						}
					}

					$igsnOriginalArchivalInstitutions=$igsnsample->getElementsByTagName("OriginalArchivalInstitution");
					foreach($igsnOriginalArchivalInstitutions as $igsnOriginalArchivalInstitution){
						if($iOriginalArchivalInstitution==""){
						$iOriginalArchivalInstitution=$igsnOriginalArchivalInstitution->textContent;
						}
					}

					$igsnOriginalArchivalContacts=$igsnsample->getElementsByTagName("OriginalArchivalContact");
					foreach($igsnOriginalArchivalContacts as $igsnOriginalArchivalContact){
						if($iOriginalArchivalContact==""){
						$iOriginalArchivalContact=addslashes($igsnOriginalArchivalContact->textContent);
						}
					}

					$igsnMostRecentArchivalInstitutions=$igsnsample->getElementsByTagName("MostRecentArchivalInstitution");
					foreach($igsnMostRecentArchivalInstitutions as $igsnMostRecentArchivalInstitution){
						if($iMostRecentArchivalInstitution==""){
						$iMostRecentArchivalInstitution=addslashes($igsnMostRecentArchivalInstitution->textContent);
						}
					}

					$igsnMostRecentArchivalContacts=$igsnsample->getElementsByTagName("MostRecentArchivalContact");
					foreach($igsnMostRecentArchivalContacts as $igsnMostRecentArchivalContact){
						if($iMostRecentArchivalContact==""){
						$iMostRecentArchivalContact=addslashes($igsnMostRecentArchivalContact->textContent);
						}
					}








				}

			} //end if iparentigsn != ""


		} //end if ecproject == redux


	
			if($modigsn=="qqq"){ //debug here
				echo "iparentigsn=$iparentigsn<br>";
				echo "isampleid=$isampleid<br>";
				echo "isampledescription=$isampledescription<br>";
				echo "igeoobjecttype=$igeoobjecttype<br>";
				echo "igeoobjectclassification=$igeoobjectclassification<br>";
				echo "icollectionmethod=$icollectionmethod<br>";
				echo "imaterial=$imaterial<br>";
				echo "ilatitude=$ilatitude<br>";
				echo "ilongitude=$ilongitude<br>";
				echo "isamplecomment=$isamplecomment<br><br>";
				
				echo "moderror: $moderror";
			}

?>