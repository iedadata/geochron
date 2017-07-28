<?PHP
/**
 * updatemetadata.php
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

	//CRON script to go to SESAR and update all metadata.
	
	$igsnnames = array("isampleid ", "isampledescription ", "igeoobjecttype ", "igeoobjectclassification ", "icollectionmethod", "ilongitude", "ilatitude", "isamplecomment", "icollector", "imaterialclassification", "iPrimaryLocationName", "iPrimaryLocationType", "iLocationDescription", "iLocality", "iLocalityDescription", "iCountry", "iProvice", "iCounty", "iCityOrTownship", "iPlatform", "iPlatformID", "iOriginalArchivalInstitution", "iOriginalArchivalContact", "iMostRecentArchivalInstitution", "iMostRecentArchivalContact");
	$geochronnames=array("sample_id ", "sample_description ", "geoobjecttype ", "geoobjectclass ", "collectionmethod ", "longitude ", "latitude", "sample_comment ", "collector", "rocktype", "primarylocationname", "primarylocationtype", "locationdescription", "locality", "localitydescription", "country", "provice", "county", "cityortownship", "platform", "platformid", "originalarchivalinstitution", "originalarchivalcontact", "mostrecentarchivalinstitution", "mostrecentarchivalcontact");
	$textornums=array("text", "text", "text", "text", "text", "number", "number", "text", "text", "text", "text", "text", "text", "text", "text", "text", "text", "text", "text", "text", "text", "text", "text", "text", "text");
	
	include("db.php");
	
	$rows=$db->get_results("select sample_pkey,igsn from sample");	
	

	foreach($rows as $row){

		$modigsn=$row->igsn;
		$sample_pkey=$row->sample_pkey;
		
		$moderror="";
	
		include("fetchigsn.php");
	
		if($moderrer==""){
	
			//first, let's get rid of the "Not Provided" nonsense from SESAR
			foreach($igsnnames as $igsnname){
	
				eval("if(strtolower(\$$igsnname)==\"not provided\"){\$$igsnname=\"\";}");
	
			}
	
			//now, loop over all igsnnames and build update string
			$updatestring="";
			$updatedelim="";
			for($x=0;$x<count($igsnnames);$x++){
				$thisigsnname=$igsnnames[$x];
				$thisgeochronname=$geochronnames[$x];
				$thistextornum=$textornums[$x];
				eval("\$thisval = \$$thisigsnname;");
				if($thisval!=""){
					if($thistextornum=="text"){
						$updatestring.=$updatedelim."$thisgeochronname = '$thisval'";
						$updatedelim=",\n";
					}else{
						$updatestring.=$updatedelim."$thisgeochronname = $thisval";
						$updatedelim=",\n";
					}
				}//end if thisval
			}//end for x
		
			//add geolocation here
			if($ilatitude != "" and $ilongitude != ""){
				$updatestring.=$updatedelim."mypoint = ST_PointFromText('POINT($ilongitude $ilatitude)',-1)";
			}
		
			//finish updatestring
			$updatestring="update sample set
							$updatestring
							where sample_pkey = $sample_pkey;";
		
			//echo nl2br($updatestring);
		
			$db->query("$updatestring");
			
			echo "$modigsn done<br>\n";

		}//end if moderror 
	
	}//end foreach rows
	

	
?>