<?PHP
/**
 * restsearchservice.php
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


	function sigorigval($origval,$origerr,$numplaces){

		//Math from Noah McLean at MIT

		$x=round($origval*pow(10,(($numplaces-1)-floor(log10(2*$origerr))))) * pow(10,((floor(log10(2*$origerr))))-($numplaces-1));
	
		return($x);

	}

	function sigerrval($origerr,$numplaces){

		//Math from Noah McLean at MIT

		$x=round(2*$origerr*(pow(10,(($numplaces-1)-floor(log10(2*$origerr)))))) * pow(10,(floor(log10(2*$origerr)))-($numplaces-1));
		
		return($x);
	}

	function sigaloneval($origerr,$numplaces){

		//Math from Noah McLean at MIT

		$x=round($origerr*(pow(10,(($numplaces-1)-floor(log10($origerr)))))) * pow(10,(floor(log10($origerr)))-($numplaces-1));
	
		return($x);

	}

    if (!function_exists('http_response_code')) {
        function http_response_code($code = NULL) {

            if ($code !== NULL) {

                switch ($code) {
                    case 100: $text = 'Continue'; break;
                    case 101: $text = 'Switching Protocols'; break;
                    case 200: $text = 'OK'; break;
                    case 201: $text = 'Created'; break;
                    case 202: $text = 'Accepted'; break;
                    case 203: $text = 'Non-Authoritative Information'; break;
                    case 204: $text = 'No Content'; break;
                    case 205: $text = 'Reset Content'; break;
                    case 206: $text = 'Partial Content'; break;
                    case 300: $text = 'Multiple Choices'; break;
                    case 301: $text = 'Moved Permanently'; break;
                    case 302: $text = 'Moved Temporarily'; break;
                    case 303: $text = 'See Other'; break;
                    case 304: $text = 'Not Modified'; break;
                    case 305: $text = 'Use Proxy'; break;
                    case 400: $text = 'Bad Request'; break;
                    case 401: $text = 'Unauthorized'; break;
                    case 402: $text = 'Payment Required'; break;
                    case 403: $text = 'Forbidden'; break;
                    case 404: $text = 'Not Found'; break;
                    case 405: $text = 'Method Not Allowed'; break;
                    case 406: $text = 'Not Acceptable'; break;
                    case 407: $text = 'Proxy Authentication Required'; break;
                    case 408: $text = 'Request Time-out'; break;
                    case 409: $text = 'Conflict'; break;
                    case 410: $text = 'Gone'; break;
                    case 411: $text = 'Length Required'; break;
                    case 412: $text = 'Precondition Failed'; break;
                    case 413: $text = 'Request Entity Too Large'; break;
                    case 414: $text = 'Request-URI Too Large'; break;
                    case 415: $text = 'Unsupported Media Type'; break;
                    case 500: $text = 'Internal Server Error'; break;
                    case 501: $text = 'Not Implemented'; break;
                    case 502: $text = 'Bad Gateway'; break;
                    case 503: $text = 'Service Unavailable'; break;
                    case 504: $text = 'Gateway Time-out'; break;
                    case 505: $text = 'HTTP Version not supported'; break;
                    default:
                        exit('Unknown http status code "' . htmlentities($code) . '"');
                    break;
                }

                $protocol = (isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0');

                header($protocol . ' ' . $code . ' ' . $text);

                $GLOBALS['http_response_code'] = $code;

            } else {

                $code = (isset($GLOBALS['http_response_code']) ? $GLOBALS['http_response_code'] : 200);

            }

            return $code;

        }
    }

if($_GET['username']!="" & $_GET['password']!=""){
	$username=$_GET['username'];
	$password=$_GET['password'];
	$userrow=$db->get_row("select * from users where username='$username' and password='$password'");
	$group=$userrow->usergroup;
	$userpkey=$userrow->users_pkey;
}else{
	$userpkey=999999;
	$group=99999;
}

$errorcode=406;

//let's get rid files older than 24 hours in directory soapmap
exec("find soapmap -mtime +1 -exec rm {} \;",$results);

$outputtype=$_GET['outputtype'];

$jsonpvar=$_GET['jsonpfunction'];

if($jsonpvar==""){
	$jsonpvar="parseResponse";
}
	
	include("db.php");
	


			$dosearch="no";
			
			$queryitems=array("polygon","north","east","south","west","minage","maxage","age","ageoffset","analysismethod","materialanalyzed","rocktype","laboratory","purpose","uniqueid","sampleid","collector","sampledescription","collectionmethod","samplecomment","primarylocationname","primarylocationtype","locationdescription","locality","localitydescription","country","province");

			foreach($queryitems as $queryitem){
			
				//echo $queryitem."<br>";
				
				eval("\$myval = \$_GET['$queryitem'];");
			
				if($myval!=""){
				
					//echo "$queryitem = $myval<br>";
					$dosearch="yes";
					
				}
			
			}
    		
    		if($dosearch=="no"){
    			
    			http_response_code($errorcode);
    			
    			if($outputtype=="xml"){
    			
    				header("Content-type: text/xml");
    				echo "<error>no search criteria set. please set a search item and try again.</error>";
    			
    			}elseif($outputtype=="json"){
    			
    				header('Content-type: application/json');
    				echo "{\"Error\": \"no search criteria set. please set a search item and try again.\"}";
    			
    			}elseif($outputtype=="jsonp"){
    			
    				header("Content-type: text/javascript");
    				echo "$jsonpvar({\"Error\": \"no search criteria set. please set a search item and try again.\"});";
    			
    			}else{
    				header("Content-type: text/plain");
    				echo "no search criteria set. please set a search item and try again.";
    			}
    			
    			exit();
    		
    		}






			/*
			//OK, we've made it this far, so let's track with Google Analytics
			//***************************************************************************************
			include("autoload.php");
			
			use UnitedPrototype\GoogleAnalytics;
			
			// Initilize GA Tracker
			$tracker = new GoogleAnalytics\Tracker('UA-4350683-15', 'www.geochron.org');
			
			// Assemble Visitor information
			// (could also get unserialized from database)
			$visitor = new GoogleAnalytics\Visitor();
			$visitor->setIpAddress($_SERVER['REMOTE_ADDR']);
			$visitor->setUserAgent($_SERVER['HTTP_USER_AGENT']);
			$visitor->setScreenResolution('1024x768');
			
			// Assemble Session information
			// (could also get unserialized from PHP session)
			$session = new GoogleAnalytics\Session();
			
			// Assemble Page information
			$page = new GoogleAnalytics\Page('/restsearchservice.php');
			$page->setTitle('Rest Search Service');
			
			// Track page view
			$tracker->trackPageview($page, $session, $visitor);
			//***************************************************************************************
			*/





			$outputtype=$_GET['outputtype'];
			
			$startrow=0;
			$endrow=50;
			
			if($_GET['startrow']!=""){
				$startrow=$_GET['startrow'];
			}
			
			if($_GET['endrow']!=""){
				$endrow=$_GET['endrow'];
			}

			if($startrow > $endrow){
				echo("Error: End row must be greater than start row.");
				exit();
			}else{
				$limitrow=$endrow-$startrow+1;
				if($limitrow>50){$limitrow=50;};
			}




			$coordinates=$_GET['polygon'];
			$locnorth=$_GET['north'];
			$loceast=$_GET['east'];
			$locsouth=$_GET['south'];
			$locwest=$_GET['west'];
			$minage=$_GET['minage'];
			$maxage=$_GET['maxage'];
			$age=$_GET['age'];
			$ageoffset=$_GET['ageoffset'];
			$ageunit=$_GET['ageunit'];
			$analysismethod=$_GET['analysismethod'];
			$materialanalyzed=$_GET['materialanalyzed'];
			$rocktype=$_GET['rocktype'];
			$laboratory=$_GET['laboratory'];
			$purpose=$_GET['purpose'];
			$igsn=$_GET['uniqueid'];
			$sampleid=$_GET['sampleid'];
			$collector=$_GET['collector'];
			$sampledescription=$_GET['sampledescription'];
			$collectionmethod=$_GET['collectionmethod'];
			$samplecomment=$_GET['samplecomment'];
			$primarylocationname=$_GET['primarylocationname'];
			$primarylocationtype=$_GET['primarylocationtype'];
			$locationdescription=$_GET['locationdescription'];
			$locality=$_GET['locality'];
			$localitydescription=$_GET['localitydescription'];
			$country=$_GET['country'];
			$province=$_GET['province'];
			$showcolumnnames=$_GET['showcolumnnames'];


			//check for errors.
			
			$error="";
			
			if($age!="" && $ageoffset==""){
				$error.="If age is provided, ageoffset must also be provided. ";
			}

			if($locnorth!=""&&($loceast=="" || $locsouth=="" || $locwest=="")){$error.-"If one directional bound is provides, all bounds must be provided";
			}elseif($loceast!=""&&($locsouth=="" || $locwest=="" || $locnorth=="")){$error.-"If one directional bound is provides, all bounds must be provided";
			}elseif($loc!=""&&($loc=="" || $loc=="" || $loc=="")){$error.-"If one directional bound is provides, all bounds must be provided";
			}elseif($loc!=""&&($loc=="" || $loc=="" || $loc=="")){$error.-"If one directional bound is provides, all bounds must be provided";}

			

    		if($error!=""){
    			
    			http_response_code($errorcode);
    			
    			if($outputtype=="xml"){
    			
    				header("Content-type: text/xml");
    				echo "<error>$error</error>";
    			
    			}elseif($outputtype=="json"){
    			
    				header('Content-type: application/json');
    				echo "{\"Error\": \"$error\"}";
    			
    			}elseif($outputtype=="jsonp"){
    			
    				header("Content-type: text/javascript");
    				echo "$jsonpvar({\"Error\": \"$error\"});";
    			
    			}else{
    				header("Content-type: text/plain");
    				echo "$error";
    			}
    			
    			exit();
    		
    		}





			if($locnorth!="" && $loceast!="" && $locsouth!="" && $locwest!=""){
				$newquerystring=$newquerystring."\n and sample.longitude >= ".$locwest." and sample.longitude <= ".$loceast." and sample.latitude >= ".$locsouth." and sample.latitude <= ".$locnorth;
			}
	
			if($coordinates!=""){$newquerystring=$newquerystring."\n and ST_Contains(ST_GeomFromText('Polygon(($coordinates))'), mypoint)"; $delim=" AND ";}
	
	
	
			if($minage != "" && $maxage != ""){
				if($ageunit=="ma"){
					$newquerystring=$newquerystring."\nand sample_age.age_value >= ".($minage * 1000000)." AND sample_age.age_value <= ".($maxage * 1000000);
				}else{
					$newquerystring=$newquerystring."\nand sample_age.age_value >= ".($minage * 1000)." AND sample_age.age_value <= ".($maxage * 1000);
				}
			}
	
	
			/*
			if($maxageuncertainty!=""){
				$newquerystring=$newquerystring."\nand sample_age.one_sigma <= ".$maxageuncertainty;
			}
			*/

			if($age!="" && $ageoffset!="" and is_numeric($age) && is_numeric($ageoffset)   ){
		
				$thisageval=$age;
				$thisageuncertainty=$ageoffset;
		
				if($ageunit=="ma"){
					$thisageval=$thisageval*1000000;
					$thisageuncertainty=$thisageuncertainty*1000000;
				}else{
					$thisageval=$thisageval*1000;
					$thisageuncertainty=$thisageuncertainty*1000;
				}
		
				$thisminage = $thisageval - $thisageuncertainty;
				$thismaxage = $thisageval + $thisageuncertainty;

		
				$newquerystring=$newquerystring."\nand sample_age.age_value >= ".$thisminage." AND sample_age.age_value <= ".$thismaxage;

			}

			if($analystname!=""){
				$newquerystring=$newquerystring."\nand lower(sample.analyst_name) = '".strtolower($analystname)."'";
			}
	
			if($mineral!=""){
				$mineral=split(";",$mineral);
				$mineraldelim="";
				foreach($minerals as $mineral){
					$minerallist.=$mineraldelim."'".$mineral."'";
					$mineraldelim=",";
				}
				$newquerystring=$newquerystring."\nand sample.mineral in ($minerallist)";
			}

			if($analysismethod!=""){
				$newquerystring.= " and (";
				$queryagetypes=split(",",$analysismethod);
				$queryagetypedelim="";
				foreach($queryagetypes as $queryagetype){
					$queryagetype=split(": ",$queryagetype);
					$queryecproject=$queryagetype[0];
					$queryagetype=$queryagetype[1];
			
					$showecproject="";
					if($queryecproject=="U-Pb Ion Microprobe"){$showecproject="'squid','zips'";}
					if($queryecproject=="U-Pb Tims"){$showecproject="'redux'";}
					if($queryecproject=="(U-Th)/He"){$showecproject="'helios','uthhelegacy'";}
					if($queryecproject=="ArAr"){$showecproject="'arar'";}

					$newquerystring.=$queryagetypedelim."(sample.ecproject in ($showecproject) and sample_age.age_name='$queryagetype')";
			
					$queryagetypedelim=" OR ";
				}
		
				$newquerystring.= ") ";
			}
	





			if($rocktype!=""){
				$hiddenrocktypes=split(";",$rocktype);
				$rocktypedelim="";
				foreach($hiddenrocktypes as $hiddenrocktype){
					$rocktypelist.=$rocktypedelim."'".$hiddenrocktype."'";
					$rocktypedelim=",";
				}
				$newquerystring=$newquerystring."\nand sample.rocktype in ($rocktypelist)";
			}

			if($laboratory!=""){
				$labnames=explode(";",$laboratory);
				$labnamedelim="";
				foreach($labnames as $labname){
					$labnamelist.=$labnamedelim."'".$labname."'";
					$labnamedelim=",";
				}
				$newquerystring=$newquerystring."\nand sample.laboratoryname in ($labnamelist)";
			}

			if($purpose!=""){
				$purposes=explode(";",$purpose);
				$purposedelim="";
				foreach($purposes as $purpose){
					$purposelist.=$purposedelim."'".$purpose."'";
					$purposedelim=",";
				}
				//$newquerystring=$newquerystring."\nand sample_age.age_name in ($purposelist)";
				$newquerystring=$newquerystring."\nand sample.purpose in ($purposelist)";
			}

			if($materialanalyzed!=""){
				$materials=explode(";",$materialanalyzed);
				$materialdelim="";
				foreach($materials as $material){
					$materiallist.=$materialdelim."'".$material."'";
					$materialdelim=",";
				}
				$newquerystring=$newquerystring."\nand sample.material in ($materiallist)";
			}



			if($igsn!=""){$newquerystring.="\nand lower(igsn) like '%".strtolower($igsn)."%'";}
			if($igsnnamespace!=""){$newquerystring.="\nand lower(igsn) like '".strtolower($igsnnamespace)."%'";}
			if($sampleid!=""){$newquerystring.="\nand lower(sample_id) like '%".strtolower($sample_id)."%'";}
			if($collector!=""){$newquerystring.="\nand lower(collector) like '%".strtolower($collector)."%'";}
			if($sampledescription!=""){$newquerystring.="\nand lower(sample_description) like '%".strtolower($sampledescription)."%'";}
			if($collectionmethod!=""){$newquerystring.="\nand lower(collectionmethod) like '%".strtolower($collectionmethod)."%'";}
			if($samplecomment!=""){$newquerystring.="\nand lower(sample_comment) like '%".strtolower($samplecomment)."%'";}
			if($primarylocationname!=""){$newquerystring.="\nand lower(primarylocationname) like '%".strtolower($primarylocationname)."%'";}
			if($primarylocationtype!=""){$newquerystring.="\nand lower(primarylocationtype) like '%".strtolower($primarylocationtype)."%'";}
			if($locationdescription!=""){$newquerystring.="\nand lower(locationdescription) like '%".strtolower($locationdescription)."%'";}
			if($locality!=""){$newquerystring.="\nand lower(locality) like '%".strtolower($locality)."%'";}
			if($localitydescription!=""){$newquerystring.="\nand lower(localitydescription) like '%".strtolower($localitydescription)."%'";}
			if($country!=""){$newquerystring.="\nand lower(country) like '%".strtolower($country)."%'";}
			if($provice!=""){$newquerystring.="\nand lower(provice) like '%".strtolower($provice)."%'";}
			if($county!=""){$newquerystring.="\nand lower(county) like '%".strtolower($county)."%'";}
			if($cityortownship!=""){$newquerystring.="\nand lower(cityortownship) like '%".strtolower($cityortownship)."%'";}
			if($platform!=""){$newquerystring.="\nand lower(platform) like '%".strtolower($platform)."%'";}
			if($platformid!=""){$newquerystring.="\nand lower(platformid) like '%".strtolower($platformid)."%'";}
			if($originalarchivalinstitution!=""){$newquerystring.="\nand lower(originalarchivalinstitution) like '%".strtolower($originalarchivalinstitution)."%'";}
			if($originalarchivalcontact!=""){$newquerystring.="\nand lower(originalarchivalcontact) like '%".strtolower($originalarchivalcontact)."%'";}
			if($mostrecentarchivalinstitution!=""){$newquerystring.="\nand lower(mostrecentarchivalinstitution) like '%".strtolower($mostrecentarchivalinstitution)."%'";}
			if($mostrecentarchivalcontact!=""){$newquerystring.="\nand lower(mostrecentarchivalcontact) like '%".strtolower($mostrecentarchivalcontact)."%'";}



			$mapquerystring="select sample.sample_pkey, 
									sample.sample_id,
									sample.igsn,
									sample.laboratoryname, 
									sample.analyst_name,
									sample.ecproject,
									sample.latitude,
									sample.longitude,
									sample.userpkey,
									sample.material,
									sample.filename,
									age_min, age_max, age_value, one_sigma, age_name,
									getagetypes(sample.sample_pkey) as agetypes
									from sample 
									left join sample_age on sample.sample_pkey = sample_age.sample_pkey
									left join groupsamplerelate on sample.sample_pkey = groupsamplerelate.sample_pkey
									left join grouprelate on groupsamplerelate.group_pkey = grouprelate.group_pkey
									left join groups on grouprelate.group_pkey = groups.group_pkey
									where 1=1 ".$newquerystring."
												and ST_GeomFromText('Polygon(($coordbox))') ~ mypoint
												--and publ=1 
												--and (sample.publ=1 or users.usergroup=$group or users.users_pkey=$userpkey)
												--and (sample.publ=1 or array_intersect(users.grouparray, ARRAY[$grouparray]) is not null or users.users_pkey=$userpkey)
												and (sample.publ=1 or sample.userpkey=$userpkey or ((grouprelate.users_pkey=$userpkey and grouprelate.confirmed=true) or groups.users_pkey=$userpkey))
												group by
												sample.sample_pkey, 
												sample.sample_id,
												sample.igsn,
												sample.laboratoryname, 
												sample.analyst_name,
												sample.ecproject,
												sample.latitude,
												sample.longitude,
												sample.userpkey,
												sample.material,
												 sample.filename,
												age_min, age_max, age_value, one_sigma, age_name,
												agetypes
												";



			$newquerystring="select sample.sample_pkey, 
									sample.sample_id,
									sample.igsn,
									sample.laboratoryname, 
									sample.analyst_name,
									sample.ecproject,
									sample.latitude,
									sample.longitude,
									sample.userpkey,
									sample.material,
									sample.filename,
									sample.longitude,
									sample.latitude,
									age_min, age_max, age_value, one_sigma, age_name,upstream,
									getagetypes(sample.sample_pkey) as agetypes
									from sample 
									left join sample_age on sample.sample_pkey = sample_age.sample_pkey
									left join groupsamplerelate on sample.sample_pkey = groupsamplerelate.sample_pkey
									left join grouprelate on groupsamplerelate.group_pkey = grouprelate.group_pkey
									left join groups on grouprelate.group_pkey = groups.group_pkey
									where 1=1 ".$newquerystring;

			$mmnewquerystring=$newquerystring."
											and (sample.publ=1 or sample.userpkey=$userpkey or ((grouprelate.users_pkey=$userpkey and grouprelate.confirmed=true) or groups.users_pkey=$userpkey))
											group by
											sample.sample_pkey, 
											sample.sample_id,
											sample.igsn,
											sample.laboratoryname, 
											sample.analyst_name,
											sample.ecproject,
											sample.latitude,
											sample.longitude,
											sample.userpkey,
											sample.material,
											 sample.filename,
											age_min, age_max, age_value, one_sigma, age_name,
											agetypes";




			$newquerystring=$newquerystring."
											and (sample.publ=1 or sample.userpkey=$userpkey or ((grouprelate.users_pkey=$userpkey and grouprelate.confirmed=true) or groups.users_pkey=$userpkey))
											group by
											sample.sample_pkey, 
											sample.sample_id,
											sample.igsn,
											sample.laboratoryname, 
											sample.analyst_name,
											sample.ecproject,
											sample.latitude,
											sample.longitude,
											sample.userpkey,
											sample.material,
											 sample.filename,
											age_min, age_max, age_value, one_sigma, age_name,upstream,
											agetypes order by sample.sample_pkey
											";





			$headercolumns=array("Sample ID","Unique ID","Method","Material","Age (Ma)","Age 2 Sigma","Age Type","Lab Name","Analyst Name");
			$outcolumns="";























































			if($_GET['debug']!=""){
				echo nl2br($newquerystring);
				exit();
			}


			$searchtype=$_GET['searchtype'];


			//******************************** COUNT *********************************
			
			if($searchtype=="count"){
				//some error checking for query
				
				$mycount=$db->get_var("select count(*) from ($newquerystring) foo");
				
				if($mycount==""){$mycount="0";}
				
				//echo($mycount);








    			if($outputtype=="xml"){
    			
    				header("Content-type: text/xml");
    				echo "<count>$mycount</count>";
    			
    			}elseif($outputtype=="json"){
    			
    				header('Content-type: application/json');
    				echo "{\"Count\": \"$mycount\"}";
    			
    			}elseif($outputtype=="jsonp"){
    			
    				header("Content-type: text/javascript");
    				echo "$jsonpvar({\"Count\": \"$mycount\"});";
    			
    			}else{
    				header("Content-type: text/plain");
    				echo "$mycount";
    			}
    			
    			exit();





			}elseif($searchtype=="rowdata" && $outputtype=="html"){ //end if outputtype=count
	
			//***********************************************************************
			//******************************** HTML *********************************
			
				$myrows=$db->get_results("$newquerystring limit $limitrow offset $startrow");
			
				//echo nl2br("$newquerystring limit $limitrow offset $startrow");exit();
	
	
				/*
				Sample ID
				Unique ID
				Method
				Material
				Age (Ma)
				Age 2 Sigma
				Age Type
				Lab Name
				Analyst Name
				*/

	
	
	
				if(count($myrows)>0){
				
					$returnval="<table class=\"geochrontable\">\n";
					
					if($showcolumnnames=="yes"){
					
						$returnval.="<tr>";
						foreach($headercolumns as $headercolumn){
						
							$returnval.="<th>$headercolumn</th>";
						
						}
					
						$returnval.="</tr>\n";
					}
					
					foreach($myrows as $myrow){
					
						$returnval.="<tr>\n";

						$method="";
						if($myrow->ecproject=="redux"){
							$method="U-Pb";
						}
						if($myrow->ecproject=="arar"){
							$method="Ar-Ar";
						}
						if($myrow->ecproject=="helios"){
							$method="(U-Th)He";
						}
						if($myrow->ecproject=="zips"){
							$method="U-Pb&nbsp;(ZIPS)";
						}
						if($myrow->ecproject=="uthhelegacy"){
							$method="(U-Th)He";
						}
						if($myrow->ecproject=="squid"){
							$method="SQUID";
						}

						$sample_pkey=$myrow->sample_pkey;
		
						$showage=$myrow->age_value;
						$showonesigma=$myrow->one_sigma;
		
						if($myrow->ecproject=="redux"){
							if($showage!=""){
								$showage=$showage/1000000;
								$showonesigma=$showonesigma/1000000;
							}else{
								$showonesigma="";
							}
						}

						if($myrow->upstream=="f"){
						$showage=sigorigval($showage,$showonesigma,2);
						$showonesigma=sigerrval($showonesigma,2);
						}else{
						$showage="";
						$showonesigma="";
						}


						$returnval.="<td>$myrow->sample_id</td>\n";
						$returnval.="<td>$myrow->igsn</td>\n";
						$returnval.="<td>$method</td>\n";
						$returnval.="<td>$myrow->material</td>\n";
						$returnval.="<td>$showage</td>\n";
						$returnval.="<td>$showonesigma</td>\n";
						$returnval.="<td>$myrow->age_name</td>\n";
						$returnval.="<td>$myrow->laboratoryname</td>\n";
						$returnval.="<td>$myrow->analyst_name</td>\n";



						$returnval.="</tr>\n";

					}
					
					$returnval.="</table>";
					
					echo("$returnval");
				
				}else{ //end if count myrows > 0
				
					echo("no results found");
				
				}
				
	
			}elseif($searchtype=="rowdata" && $outputtype=="csv"){//end if outputtype=html
	
			//***********************************************************************
	
			//******************************** CSV *********************************
			
			
				//some error checking for query
				
				
				$myrows=$db->get_results("$newquerystring limit $limitrow offset $startrow");
				
				if(count($myrows)>0){
				
					$returnval="";
					
					if($showcolumnnames=="yes"){
					
						$commaval="";
					
						foreach($headercolumns as $headercolumn){
						
							$returnval.=$commaval.$headercolumn;
							$commaval=",";
						}
					
						$returnval.="\n";
					}
					
					foreach($myrows as $myrow){
						

						$method="";
						if($myrow->ecproject=="redux"){
							$method="U-Pb";
						}
						if($myrow->ecproject=="arar"){
							$method="Ar-Ar";
						}
						if($myrow->ecproject=="helios"){
							$method="(U-Th)He";
						}
						if($myrow->ecproject=="zips"){
							$method="U-Pb&nbsp;(ZIPS)";
						}
						if($myrow->ecproject=="uthhelegacy"){
							$method="(U-Th)He";
						}
						if($myrow->ecproject=="squid"){
							$method="SQUID";
						}

						$sample_pkey=$myrow->sample_pkey;
		
						$showage=$myrow->age_value;
						$showonesigma=$myrow->one_sigma;
		
						if($myrow->ecproject=="redux"){
							if($showage!=""){
								$showage=$showage/1000000;
								$showonesigma=$showonesigma/1000000;
							}else{
								$showonesigma="";
							}
						}

						if($myrow->upstream=="f"){
						$showage=sigorigval($showage,$showonesigma,2);
						$showonesigma=sigerrval($showonesigma,2);
						}else{
						$showage="";
						$showonesigma="";
						}


						$returnval.="$myrow->sample_id,";
						$returnval.="$myrow->igsn,";
						$returnval.="$method,";
						$returnval.="$myrow->material,";
						$returnval.="$showage,";
						$returnval.="$showonesigma,";
						$returnval.="$myrow->age_name,";
						$returnval.="$myrow->laboratoryname,";
						$returnval.="$myrow->analyst_name,";
						$returnval.="\n";
					}
					
					
					header("Content-type: text/plain");
					echo("$returnval");
				
				}else{ //end if count myrows > 0
				
					header("Content-type: text/plain");
					echo("no results found");
				
				}
				
	
			}elseif($searchtype=="rowdata" && $outputtype=="xml"){//end if outputtype=csv
	
			//***********************************************************************
		
			//******************************** XML *********************************
			
			
				//some error checking for query
				
				
				$myrows=$db->get_results("$newquerystring limit $limitrow offset $startrow");
				
				if(count($myrows)>0){
				
					$returnval="<geochronSamples>\n";
					
					foreach($myrows as $myrow){
					
						$returnval.="\t<Sample>\n";
	
						$method="";
						if($myrow->ecproject=="redux"){
							$method="U-Pb";
						}
						if($myrow->ecproject=="arar"){
							$method="Ar-Ar";
						}
						if($myrow->ecproject=="helios"){
							$method="(U-Th)He";
						}
						if($myrow->ecproject=="zips"){
							$method="U-Pb&nbsp;(ZIPS)";
						}
						if($myrow->ecproject=="uthhelegacy"){
							$method="(U-Th)He";
						}
						if($myrow->ecproject=="squid"){
							$method="SQUID";
						}

						$sample_pkey=$myrow->sample_pkey;
		
						$showage=$myrow->age_value;
						$showonesigma=$myrow->one_sigma;
		
						if($myrow->ecproject=="redux"){
							if($showage!=""){
								$showage=$showage/1000000;
								$showonesigma=$showonesigma/1000000;
							}else{
								$showonesigma="";
							}
						}

						if($myrow->upstream=="f"){
						$showage=sigorigval($showage,$showonesigma,2);
						$showonesigma=sigerrval($showonesigma,2);
						}else{
						$showage="";
						$showonesigma="";
						}


						$returnval.="<sampleid>$myrow->sample_id</sampleid>\n";
						$returnval.="<uniqueid>$myrow->igsn</uniqueid>\n";
						$returnval.="<method>$method</method>\n";
						$returnval.="<material>$myrow->material</material>\n";
						$returnval.="<age>$showage</age>\n";
						$returnval.="<agetwosigma>$showonesigma</agetwosigma>\n";
						$returnval.="<agetype>$myrow->age_name</agetype>\n";
						$returnval.="<laboratory>$myrow->laboratoryname</laboratory>\n";
						$returnval.="<analyst>$myrow->analyst_name</analyst>\n";
						
						$returnval.="\t</Sample>\n";
					}
					
					$returnval.="</geochronSamples>";
					
					header("Content-type: text/xml"); 
					
					echo("$returnval");
				
				}else{ //end if count myrows > 0
				
					echo("no results found");
				
				}
				
			}elseif($searchtype=="rowdata" && ($outputtype=="json" || $outputtype=="jsonp")){//end if outputtype=csv
	
			//***********************************************************************
		
			//******************************** JSON *********************************
			
			
				//some error checking for query
				
				
				$myrows=$db->get_results("$newquerystring limit $limitrow offset $startrow");
				
				if(count($myrows)>0){
				
					$x=0;
					
					foreach($myrows as $myrow){

						$method="";
						if($myrow->ecproject=="redux"){
							$method="U-Pb";
						}
						if($myrow->ecproject=="arar"){
							$method="Ar-Ar";
						}
						if($myrow->ecproject=="helios"){
							$method="(U-Th)He";
						}
						if($myrow->ecproject=="zips"){
							$method="U-Pb&nbsp;(ZIPS)";
						}
						if($myrow->ecproject=="uthhelegacy"){
							$method="(U-Th)He";
						}
						if($myrow->ecproject=="squid"){
							$method="SQUID";
						}

						$sample_pkey=$myrow->sample_pkey;
		
						$showage=$myrow->age_value;
						$showonesigma=$myrow->one_sigma;
		
						if($myrow->ecproject=="redux"){
							if($showage!=""){
								$showage=$showage/1000000;
								$showonesigma=$showonesigma/1000000;
							}else{
								$showonesigma="";
							}
						}

						if($myrow->upstream=="f"){
						$showage=sigorigval($showage,$showonesigma,2);
						$showonesigma=sigerrval($showonesigma,2);
						}else{
						$showage="";
						$showonesigma="";
						}

						$returnarray[$x]["sampleid"]=htmlspecialchars($myrow->sample_id);
						$returnarray[$x]["uniqueid"]=htmlspecialchars($myrow->uniqueid);
						$returnarray[$x]["method"]=htmlspecialchars($method);
						$returnarray[$x]["material"]=htmlspecialchars($myrow->material);
						$returnarray[$x]["age"]=htmlspecialchars($showage);
						$returnarray[$x]["agetwosigma"]=htmlspecialchars($showonesigma);
						$returnarray[$x]["agetype"]=htmlspecialchars($myrow->age_name);
						$returnarray[$x]["laboratory"]=htmlspecialchars($myrow->laboratoryname);
						$returnarray[$x]["analyst"]=htmlspecialchars($myrow->analyst_name);

					
					$x++;
					
					}
					
					$returnval=json_encode($returnarray);
					
					//header('Content-type: application/json');
					//header("Content-type: text/javascript");
					
					if($outputtype=="json"){
						
						header('Content-type: application/json');
						echo("$returnval");
					
					}elseif($outputtype=="jsonp"){
					
						
						header("Content-type: text/javascript");
						echo("$jsonpvar($returnval);");
					
					}
				
				}else{ //end if count myrows > 0
				
					echo("no results found");
				
				}
	
	
			}elseif($outputtype=="staticmap"){//end if outputtype=json
	
			//***********************************************************************
		
			//**************************** STATIC MAP *******************************
	
				if (!extension_loaded("MapScript"))
				  dl('php_mapscript.'.PHP_SHLIB_SUFFIX);
				
				
				
				
				$rows=$db->get_results("$newquerystring limit $limitrow offset $startrow");
				
				if(count($rows)==0){
				
					/*
					$file1 = 'test.jpg';
					$file2 = 'test2.jpg';

					// First image
					$image = imagecreatefromjpeg($file1);

					// Second image (the overlay)
					$overlay = imagecreatefromjpeg($file2);

					// We need to know the width and height of the overlay
					list($width, $height, $type, $attr) = getimagesize($file2);

					// Apply the overlay
					imagecopy($image, $overlay, 0, 0, 0, 0, $width, $height);
					imagedestroy($overlay);

					// Output the results
					header('Content-type: image/png');
					imagepng($image);
					imagedestroy($image);

					*/

					$errorimage = imagecreatefromjpeg("nosamplemap.jpg");
					header('Content-type: image/jpeg');
					imagepng($errorimage);
					unset($errorimage);
					
					
					
					exit();
				}
				
				// Create a map object.
				$oMap = ms_newMapObj("/local/public/mgg/web/ecp.iedadata.org/htdocs/bathy.map");
				
				//$oMap->setSize(512,512);
				$oMap->setSize(800       ,400);
				
				$minx=999;
				$maxx=-999;
				$miny=999;
				$maxy=-999;
				
				foreach ($rows as $row){
					if($row->longitude > $maxx){$maxx=$row->longitude;}
					if($row->longitude < $minx){$minx=$row->longitude;}
					if($row->latitude > $maxy){$maxy=$row->latitude;}
					if($row->latitude < $miny){$miny=$row->latitude;}
				}
				
				$minx=$minx-1; if($minx<-180){$minx=-180;}
				$maxx=$maxx+1; if($maxx>180){$maxx=180;}
				$miny=$miny-1; if($miny<-90){$miny=-90;}
				$maxy=$maxy+1; if($maxy>90){$maxy=90;}
				
				$mybox="$minx,$miny,$maxx,$maxy";
				
				//echo "$mybox";exit();
				
				$BBOX=explode(",",$mybox);
				
				$oMap->setExtent($BBOX[0], $BBOX[1], $BBOX[2], $BBOX[3]);
				
				$nSymbolId = ms_newSymbolObj($oMap, "circle");
				$oSymbol = $oMap->getsymbolobjectbyid($nSymbolId);
				$oSymbol->set("type", MS_SYMBOL_ELLIPSE);
				$oSymbol->set("filled", MS_TRUE);
				$aPoints[0] = 1;
				$aPoints[1] = 1;
				$oSymbol->setpoints($aPoints);
				
				
				// Create another layer to hold point locations
				$oLayerPoints = ms_newLayerObj($oMap);
				$oLayerPoints->set( "name", "custom_points");
				$oLayerPoints->set( "type", MS_LAYER_POINT);
				$oLayerPoints->set( "status", MS_DEFAULT);
				//$oLayerPoints->set("transparency", 20);
				
				
				
				// Render the map into an image object
				$oMapImage = $oMap->draw();
				
				$oMapClass = ms_newClassObj($oLayerPoints);
				
				/*
				$oMapClass->label->set( "position", MS_AUTO);
				$oMapClass->label->set( "size", 15);
				$oMapClass->label->color->setRGB(250,0,0);
				$oMapClass->label->outlinecolor->setRGB(255,255,255);
				*/
				
				// Create a style object defining how to draw features
				$oPointStyle = ms_newStyleObj($oMapClass);
				//$oPointStyle->color->setRGB(250,0,0);
				$oPointStyle->outlinecolor->setRGB(0,0,0);
				$oPointStyle->set( "symbolname", "circle");
				$oPointStyle->set( "size", "9");
				$oPointStyle->color->setRGB(255,0,0);
				
				srand(time());
				
				
				
				foreach($rows as $row){
				   
					$redcolor=255;
					$bluecolor=0;
					$greencolor=0;
				
				   
					/*
					if($row->source=="navdat"){
						$redcolor=99;
						$bluecolor=101;
						$greencolor=49;
					}elseif($row->source=="petdb"){
						$redcolor=74;
						$bluecolor=154;
						$greencolor=165;
					}elseif($row->source=="georoc"){
						$redcolor=156;
						$bluecolor=0;
						$greencolor=156;
					}elseif($row->source=="usgs"){
						$redcolor=255;
						$bluecolor=0;
						$greencolor=0;
					}elseif($row->source=="seddb"){
						$redcolor=255;
						$bluecolor=153;
						$greencolor=0;
					}elseif($row->source=="ganseki"){
						$redcolor=240;
						$bluecolor=206;
						$greencolor=65;
					}else{
						$redcolor=255;
						$bluecolor=255;
						$greencolor=255;
					}
					*/
				
				   
				   $oPointStyle->color->setRGB($redcolor,$bluecolor,$greencolor);
				   $point = ms_newPointObj();
				   $point->setXY($row->longitude,$row->latitude);
				   $point->draw($oMap,$oLayerPoints,$oMapImage,0,'');
					
				}
				
				
				$rand=rand(111111111111,999999999999);
				
				/*
				$oMapImage->saveImage("temp/".$rand.".jpg");
				
				$ecoverlayimg = new Imagick("eclogooverlay.png");
				
				$bigimg = new Imagick("temp/".$rand.".jpg");
				
				$bigimg->compositeImage($ecoverlayimg, imagick::COMPOSITE_OVER, 5, 355);
				
				header('Content-type: image/png');
				
				echo $bigimg;
				
				unset($oMapImage);
				unset($ecoverlayimg);
				unset($bigimg);
				
				unlink("temp/".$rand.".jpg");
				*/
				
				header('Content-type: image/png');
				$oMapImage->saveImage("");
				
				
				
				
				/*
				
				$url="http://www.geochron.org/soapmap/".$rand.".jpg";
				
				$rand=rand(111111111111,999999999999);
				
				$oMapImage->saveImage("soapmap/".$rand.".jpg");
				

				$bigimg = new Imagick("soapmap/".$rand.".jpg");
				
				$ecoverlayimg = new Imagick("eclogooverlay.png");
				
				$bigimg->compositeImage($ecoverlayimg, imagick::COMPOSITE_OVER, 5, 355);

				//header('Content-type: image/png');
				
				$bigimg->saveImage("");
				
				unset($bigimg);

				//$bigimg->writeImage("soapmap/".$rand.".jpg");
				*/













				
				
	
			}else{ //end if outputtype=staticmap
				
    			http_response_code($errorcode);
    			
    			if($outputtype=="xml"){
    			
    				header("Content-type: text/xml");
    				echo "<error>Incorrect output type / search type specified.</error>";
    			
    			}elseif($outputtype=="json"){
    			
    				header('Content-type: application/json');
    				echo "{\"Error\": \"Incorrect output type / search type specified.\"}";
    			
    			}elseif($outputtype=="jsonp"){
    			
    				header("Content-type: text/javascript");
    				echo "$jsonpvar({\"Error\": \"Incorrect output type / search type specified.\"});";
    			
    			}else{
    				header("Content-type: text/plain");
    				echo "Incorrect output type / search type specified.";
    			}
	
			}
			//***********************************************************************
    	
		

    











?>