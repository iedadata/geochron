<?PHP
/**
 * getfractions.php
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

function getElementFromDom($varname,$dom){
	$elements=$dom->getElementsByTagName($varname);

	$valarray=array();
	
	foreach($elements as $element){
		$valarray[]=$element->textContent;
	}
	
	$value = pg_escape_string($valarray[0]);
	
	return $value;
}

include("db.php");

$pkey=$_GET["pkey"];

if($pkey==""){
	echo "no pkey given";
	exit();
}

//echo "<a href=\"getfractions.php?pkey=$pkey\" target=\"_blank\">here</a><br>";

$row=$db->get_row("select * from sample where sample_pkey=$pkey");

if($row->filename==""){
	echo "file not found";
	exit();
}

$filename=$row->filename;
$ecproject=$row->ecproject;

if($ecproject=="zips"){

	$filename=str_replace("zip","xml",$filename);

}

$dom = new DomDocument;
$xmlfile = "files/$filename";

//echo "$xmlfile";exit();
//echo "$ecproject";exit();

$showtable="no";

$rows="";

if($dom->Load($xmlfile)){

	if($ecproject=="redux"){



		//figure out if this is a fraction used in age calculation
		//first, build array of fractionids that were used
		
		unset($fractionidarray);
		$fractionidarray=array();
		
		$mypreferred="false";
		
		$sampledatemodels = $dom->getElementsByTagName("SampleDateModel");

		foreach($sampledatemodels as $sampledatemodel){
		
			$preferreds=$sampledatemodel->getElementsByTagName("preferred");
			
			foreach($preferreds as $preferred){
				$mypreferred=$preferred->textContent;
			}//end foreach analysisfraction
			
			if($mypreferred=="true"){
				
				$includedfractionsvectors=$sampledatemodel->getElementsByTagName("includedFractionsVector");
				
				foreach($includedfractionsvectors as $includedfractionsvector){
					
					$fractionids=$includedfractionsvector->getElementsByTagName("fractionID");
					
					foreach($fractionids as $fractionid){
					
						$fractionidarray[]=$fractionid->textContent;
					
					}
				}
			}
			
		}//end foreach aliquot



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
	
				//fractionID
				$fractionids = $analysisfraction->getElementsByTagName("fractionID");
				foreach($fractionids as $fractionid){
					$myfractionid = $fractionid->textContent;
				}

				//check to see if fractionid is used in age interpretation
				if(in_array($myfractionid,$fractionidarray)){
					$agein="Yes";
				}else{
					$agein="No";
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
						
						if($myname=="age206_238r"){
							$age206_238r=sigorigval($myvalue/1000000,$myonesigma/1000000,2);
							$age206_238rerr=sigerrval($myonesigma/1000000,2);
							$showrow="yes";
						}
						
						if($myname=="age207_235r"){
							$age207_235r=sigorigval($myvalue/1000000,$myonesigma/1000000,2);
							$age207_235rerr=sigerrval($myonesigma/1000000,2);
							$showrow="yes";
						}
						
						if($myname=="age207_206r"){
							$age207_206r=sigorigval($myvalue/1000000,$myonesigma/1000000,2);
							$age207_206rerr=sigerrval($myonesigma/1000000,2);
							$showrow="yes";
						}
	
					
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
						
						if($myname=="rhoR206_238r__r207_235r"){
							$rho=sigaloneval($myvalue,3);
							$rhoerr=round($myonesigma,2);
							$showrow="yes";
						}
					
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
								<td>$agein</td>
								<td>$myfractionid</td>
								<td>$age206_238r</td><td>$age206_238rerr</td>
								<td>$age207_235r</td><td>$age207_235rerr</td>
								<td>$age207_206r</td><td>$age207_206rerr</td>
								<td>$radtocommontotal</td>
								<td>$rho</td>
							</tr>";
					$showtable="yes";
				}//end if showrow
	
			}//end foreach analysisfractions
		
			if($showtable=="yes"){
				echo "Additional Fraction Information:<br>
					<table class=\"aliquot\" border=1>
						<tr>
							<th>Included in Age Interp?</td>
							<th>Fraction ID</th>
							<th><div style=\"text-transform:none;\">206/238 DATE (Ma)</div></th><th><div style=\"text-transform:none;\">&plusmn;2&sigma; (abs)</div></th>
							<th><div style=\"text-transform:none;\">207/235 DATE (Ma)</div></th><th><div style=\"text-transform:none;\">&plusmn;2&sigma; (abs)</div></th>
							<th><div style=\"text-transform:none;\">207/206 DATE (Ma)</div></th><th><div style=\"text-transform:none;\">&plusmn;2&sigma; (abs)</div></th>
							<th><div style=\"text-transform:none;\">Pb*/Pbc</div></th>
							<th>Rho</th>
						</tr>
						$rows
					</table>";
			}else{
				echo "No fraction data found.";
			}
		
		}//end foreach aliquots
	
	}elseif($ecproject=="helios"){
	
		//echo "Helios Here<br><br>";

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

			//$umeaserrs = $aliquot->getElementsByTagName("UMeasErr");
			$umeaserrs = $aliquot->getElementsByTagName("UeffConc");
			foreach($umeaserrs as $umeaserr){
				$myumeaserr=round($umeaserr->textContent,2);
			}

			//$hes = $aliquot->getElementsByTagName("He");
			$hes = $aliquot->getElementsByTagName("HeNmolG");
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
						<td>$mymineral</td>
						<td>$myage</td>
						<td>$myageerr</td>
						<td>$myuppm</td>
						<td>$mythppm</td>
						<td>$mysm147ppm</td>
						<td>$myumeaserr</td>
						<td>$mythuratio</td>
						<td>$myhe</td>
						<td>$mymass</td>
						<td>$myft</td>
						<td>$myesr</td>
					</tr>";










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


		
		echo "Additional Fraction Information:<br>
			<table class=\"aliquot\">
				<tr>
					<th>Fraction ID</th>
					<th>Mineral</th>
					<th>Age (Ma)</th>
					<th>Age Err. (Ma)</th>
					<th>U (ppm)</th>
					<th>Th (ppm)</th>
					<th>147Sm (ppm)</th>
					<th>[U]e</th>
					<th>Th/U</th>
					<th>He (nmol/g)</th>
					<th>Mass (ug)</th>
					<th>Ft</th>
					<th>Mean ESR</th>
				</tr>
				$rows
			</table>";


	}elseif($ecproject=="zips"){

		$spots = $dom->getElementsByTagName("spot");
		
		foreach($spots as $spot){
		
			$myagemapb206u238="";
			$myagemapb206u2381se="";
			$myagemapb207u235="";
			$myagemapb207u2351se="";
			$myagemapb207pb206="";
			$myagemapb207pb2061se="";
			$mycommonpb206pb204="";
			$mycommonpb207pb204="";
			$mycommonpb208pb204="";
			$mypbcorr="";
			$myblksize="";
			$myname="";
			$myrejected="";
			
			$agemapb206u238s=$spot->getElementsByTagName("agemapb206u238");foreach($agemapb206u238s as $agemapb206u238){$myagemapb206u238 = $agemapb206u238->textContent;$showrow="yes";}
			$agemapb206u2381ses=$spot->getElementsByTagName("agemapb206u2381se");foreach($agemapb206u2381ses as $agemapb206u2381se){$myagemapb206u2381se = $agemapb206u2381se->textContent;$showrow="yes";}
			$agemapb207u235s=$spot->getElementsByTagName("agemapb207u235");foreach($agemapb207u235s as $agemapb207u235){$myagemapb207u235 = $agemapb207u235->textContent;$showrow="yes";}
			$agemapb207u2351ses=$spot->getElementsByTagName("agemapb207u2351se");foreach($agemapb207u2351ses as $agemapb207u2351se){$myagemapb207u2351se = $agemapb207u2351se->textContent;$showrow="yes";}
			$agemapb207pb206s=$spot->getElementsByTagName("agemapb207pb206");foreach($agemapb207pb206s as $agemapb207pb206){$myagemapb207pb206 = $agemapb207pb206->textContent;$showrow="yes";}
			$agemapb207pb2061ses=$spot->getElementsByTagName("agemapb207pb2061se");foreach($agemapb207pb2061ses as $agemapb207pb2061se){$myagemapb207pb2061se = $agemapb207pb2061se->textContent;$showrow="yes";}
			$commonpb206pb204s=$spot->getElementsByTagName("commonpb206pb204");foreach($commonpb206pb204s as $commonpb206pb204){$mycommonpb206pb204 = $commonpb206pb204->textContent;$showrow="yes";}
			$commonpb207pb204s=$spot->getElementsByTagName("commonpb207pb204");foreach($commonpb207pb204s as $commonpb207pb204){$mycommonpb207pb204 = $commonpb207pb204->textContent;$showrow="yes";}
			$commonpb208pb204s=$spot->getElementsByTagName("commonpb208pb204");foreach($commonpb208pb204s as $commonpb208pb204){$mycommonpb208pb204 = $commonpb208pb204->textContent;$showrow="yes";}
			$pbcorrs=$spot->getElementsByTagName("pbcorr");foreach($pbcorrs as $pbcorr){$mypbcorr = $pbcorr->textContent;$showrow="yes";}
			$blksizes=$spot->getElementsByTagName("blksize");foreach($blksizes as $blksize){$myblksize = $blksize->textContent;$showrow="yes";}
			$names=$spot->getElementsByTagName("name");foreach($names as $name){$myname = $name->textContent;$showrow="yes";}
			$rejecteds=$spot->getElementsByTagName("rejected");foreach($rejecteds as $rejected){$myrejected = $rejected->textContent;$showrow="yes";}

			$mynameparts=explode("@",$myname);
			$bmyname=$mynameparts[1];
			$bmyname=str_replace(".ais","",$bmyname);

/*
			<agemapb206u238>1095</agemapb206u238>
			<agemapb206u2381se>125</agemapb206u2381se>
			<agemapb207u235>1077</agemapb207u235>
			<agemapb207u2351se>83.3</agemapb207u2351se>
			<agemapb207pb206>1041</agemapb207pb206>
			<agemapb207pb2061se>15.0</agemapb207pb2061se>
			<commonpb206pb204>18.86</commonpb206pb204>
			<commonpb207pb204>15.62</commonpb207pb204>
			<commonpb208pb204>38.34</commonpb208pb204>
			<pbcorr>(204Pb)</pbcorr>
			<blksize> 9</blksize>
			<name>11-02-07Nov\  as3@1.ais</name>
			<rejected>[/10]</rejected>

agemapb206u238
agemapb206u2381se
agemapb207u235
agemapb207u2351se
agemapb207pb206
agemapb207pb2061se
commonpb206pb204
commonpb207pb204
commonpb208pb204
pbcorr
blksize
name
rejected

<td>$myname</td>
<td>$myagemapb206u238</td>
<td>$myagemapb206u2381se</td>
<td>$myagemapb207u235</td>
<td>$myagemapb207u2351se</td>
<td>$myagemapb207pb206</td>
<td>$myagemapb207pb2061se</td>
<td>$mypbcorr</td>

<td>$mycommonpb206pb204</td>
<td>$mycommonpb207pb204</td>
<td>$mycommonpb208pb204</td>

<td>$myblksize</td>

<td>$myrejected</td>






*/

			$rows.="<tr>
						<td nowrap>$myname</td>
						<td>$myagemapb206u238</td>
						<td>$myagemapb206u2381se</td>
						<td>$myagemapb207u235</td>
						<td>$myagemapb207u2351se</td>
						<td>$myagemapb207pb206</td>
						<td>$myagemapb207pb2061se</td>
						<td>$mypbcorr</td>
					</tr>";

		}

		echo "Additional Spot Information:<br>
			<table class=\"aliquot\">
				<tr>
					<th>Spot Number</th>
					<th>206/238 Age</th><th>206/238 Age Error</th>
					<th>207/235 Age</th><th>207/235 Age Error</th>
					<th>207/206 Age</th><th>207/206 Age Error</th>
					<th>Pb*/Pbc</th>
				</tr>
				$rows
			</table>";






	}elseif($ecproject=="uthhelegacy"){

		$fractions = $dom->getElementsByTagName("fraction");
		
		foreach($fractions as $fraction){

			$myaliquot_name="";
			$mymineral="";
			$myage_ma="";
			$myage_err_ma="";
			$myu_ppm="";
			$myth_ppm="";
			$mysm_147_ppm="";
			$myue="";
			$mythUu="";
			$myhe="";
			$mymass_ug="";
			$myft="";
			$mymean_esr="";






			$aliquot_names=$fraction->getElementsByTagName("aliquot_name");foreach($aliquot_names as $aliquot_name){$myaliquot_name = $aliquot_name->textContent;$showrow="yes";}
			$minerals=$fraction->getElementsByTagName("mineral");foreach($minerals as $mineral){$mymineral = $mineral->textContent;$showrow="yes";}
			$age_mas=$fraction->getElementsByTagName("age_ma");foreach($age_mas as $age_ma){$myage_ma = $age_ma->textContent;$showrow="yes";}
			$age_err_mas=$fraction->getElementsByTagName("age_err_ma");foreach($age_err_mas as $age_err_ma){$myage_err_ma = $age_err_ma->textContent;$showrow="yes";}
			$u_ppms=$fraction->getElementsByTagName("u_ppm");foreach($u_ppms as $u_ppm){$myu_ppm = $u_ppm->textContent;$showrow="yes";}
			$th_ppms=$fraction->getElementsByTagName("th_ppm");foreach($th_ppms as $th_ppm){$myth_ppm = $th_ppm->textContent;$showrow="yes";}
			$sm_147_ppms=$fraction->getElementsByTagName("sm_147_ppm");foreach($sm_147_ppms as $sm_147_ppm){$mysm_147_ppm = $sm_147_ppm->textContent;$showrow="yes";}
			$ues=$fraction->getElementsByTagName("ue");foreach($ues as $ue){$myue = $ue->textContent;$showrow="yes";}
			$thUus=$fraction->getElementsByTagName("thUu");foreach($thUus as $thUu){$mythUu = $thUu->textContent;$showrow="yes";}
			$hes=$fraction->getElementsByTagName("he");foreach($hes as $he){$myhe = $he->textContent;$showrow="yes";}
			$mass_ugs=$fraction->getElementsByTagName("mass_ug");foreach($mass_ugs as $mass_ug){$mymass_ug = $mass_ug->textContent;$showrow="yes";}
			$fts=$fraction->getElementsByTagName("ft");foreach($fts as $ft){$myft = $ft->textContent;$showrow="yes";}
			$mean_esrs=$fraction->getElementsByTagName("mean_esr");foreach($mean_esrs as $mean_esr){$mymean_esr = $mean_esr->textContent;$showrow="yes";}



			$rows.="<tr>
						<td>$myaliquot_name</td>
						<td>$mymineral</td>
						<td>$myage_ma</td>
						<td>$myage_err_ma</td>
						<td>$myu_ppm</td>
						<td>$myth_ppm</td>
						<td>$mysm_147_ppm</td>
						<td>$myue</td>
						<td>$mythUu</td>
						<td>$myhe</td>
						<td>$mymass_ug</td>
						<td>$myft</td>
						<td>$mymean_esr</td>
					</tr>";

		}

		echo "Additional Fraction Information:<br>
			<table class=\"aliquot\">
				<tr>
					<th>Aliquot Name</th>
					<th>Mineral</th>
					<th>Age, Ma</th>
					<th>err., Ma</th>
					<th>U (ppm)</th>
					<th>Th (ppm)</th>
					<th>147Sm (ppm)</th>
					<th>[U]e</th>
					<th>Th/U</th>
					<th>He (nmol/g)</th>
					<th>Mass (ug)</th>
					<th>Ft</th>
					<th>Mean ESR</th>
				</tr>
				$rows
			</table>";



	
	}elseif($ecproject=="squid"){

		//echo "$filename";

		$fractions = $dom->getElementsByTagName("fraction");
		
		foreach($fractions as $fraction){

			$myfractionid="";
			$myage_206_238="";
			$myage_206_238_err="";
			$myage_208_232="";
			$myage_208_232_err="";
			$myage_207_206="";
			$myage_207_206_err="";
			$myrho="";
			$myage_207_235="";
			$myage_207_235_err="";




			
			$fractionids=$fraction->getElementsByTagName("fractionid");foreach($fractionids as $fractionid){$myfractionid = $fractionid->textContent;$showrow="yes";}
			$age_206_238s=$fraction->getElementsByTagName("age_206_238");foreach($age_206_238s as $age_206_238){$myage_206_238 = $age_206_238->textContent;$showrow="yes";}
			$age_206_238_errs=$fraction->getElementsByTagName("age_206_238_err");foreach($age_206_238_errs as $age_206_238_err){$myage_206_238_err = $age_206_238_err->textContent;$showrow="yes";}
			$age_208_232s=$fraction->getElementsByTagName("age_208_232");foreach($age_208_232s as $age_208_232){$myage_208_232 = $age_208_232->textContent;$showrow="yes";}
			$age_208_232_errs=$fraction->getElementsByTagName("age_208_232_err");foreach($age_208_232_errs as $age_208_232_err){$myage_208_232_err = $age_208_232_err->textContent;$showrow="yes";}
			$age_207_206s=$fraction->getElementsByTagName("age_207_206");foreach($age_207_206s as $age_207_206){$myage_207_206 = $age_207_206->textContent;$showrow="yes";}
			$age_207_206_errs=$fraction->getElementsByTagName("age_207_206_err");foreach($age_207_206_errs as $age_207_206_err){$myage_207_206_err = $age_207_206_err->textContent;$showrow="yes";}
			$rhos=$fraction->getElementsByTagName("rho");foreach($rhos as $rho){$myrho = $rho->textContent;$showrow="yes";}
			$age_207_235s=$fraction->getElementsByTagName("age_207_235");foreach($age_207_235s as $age_207_235){$myage_207_235 = $age_207_235->textContent;$showrow="yes";}
			$age_207_235_errs=$fraction->getElementsByTagName("age_207_235_err");foreach($age_207_235_errs as $age_207_235_err){$myage_207_235_err = $age_207_235_err->textContent;$showrow="yes";}
			
			/*
			$fractionids=$fraction->getElementsByTagName("fractionid");foreach($fractionids as $fractionid){$myfractionid = $fractionid->textContent;$showrow="yes";}
			$age_206_238s=$fraction->getElementsByTagName("age_206_238");foreach($age_206_238s as $age_206_238){$myage_206_238 = sigorigval($age_206_238->textContent,2);$showrow="yes";}
			$age_206_238_errs=$fraction->getElementsByTagName("age_206_238_err");foreach($age_206_238_errs as $age_206_238_err){$myage_206_238_err = sigerrval($age_206_238_err->textContent,2);$showrow="yes";}
			$age_208_232s=$fraction->getElementsByTagName("age_208_232");foreach($age_208_232s as $age_208_232){$myage_208_232 = sigorigval($age_208_232->textContent,2);$showrow="yes";}
			$age_208_232_errs=$fraction->getElementsByTagName("age_208_232_err");foreach($age_208_232_errs as $age_208_232_err){$myage_208_232_err = sigerrval($age_208_232_err->textContent,2);$showrow="yes";}
			$age_207_206s=$fraction->getElementsByTagName("age_207_206");foreach($age_207_206s as $age_207_206){$myage_207_206 = sigorigval($age_207_206->textContent,2);$showrow="yes";}
			$age_207_206_errs=$fraction->getElementsByTagName("age_207_206_err");foreach($age_207_206_errs as $age_207_206_err){$myage_207_206_err = sigerrval($age_207_206_err->textContent,2);$showrow="yes";}
			$rhos=$fraction->getElementsByTagName("rho");foreach($rhos as $rho){$myrho = sigaloneval($rho->textContent,2);$showrow="yes";}
			$age_207_235s=$fraction->getElementsByTagName("age_207_235");foreach($age_207_235s as $age_207_235){$myage_207_235 = sigorigval($age_207_235->textContent,2);$showrow="yes";}
			$age_207_235_errs=$fraction->getElementsByTagName("age_207_235_err");foreach($age_207_235_errs as $age_207_235_err){$myage_207_235_err = sigerrval($age_207_235_err->textContent,2);$showrow="yes";}
			*/

			if($myage_206_238!=""){$myage_206_238=sigorigval($myage_206_238,$myage_206_238_err,2);$myage_206_238_err=sigerrval($myage_206_238_err,2);}
			if($myage_207_235!=""){$myage_207_235=sigorigval($myage_207_235,$myage_207_235_err,2);$myage_207_235_err=sigerrval($myage_207_235_err,2);}
			if($myage_207_206!=""){$myage_207_206=sigorigval($myage_207_206,$myage_207_206_err,2);$myage_207_206_err=sigerrval($myage_207_206_err,2);}
			if($myage_208_232!=""){$myage_208_232=sigorigval($myage_208_232,$myage_208_232_err,2);$myage_208_232_err=sigerrval($myage_208_232_err,2);}

			if($myrho!=""){$myrho=sigaloneval($myrho,2);}
			

			/* correct order per doug:
			
				206/238
				207/235
				207/206
				rho
				208/232
			
			*/

			$rows.="<tr>
						<td>$myfractionid</td>
						<td>$myage_206_238</td>
						<td>$myage_206_238_err</td>
						<td>$myage_207_235</td>
						<td>$myage_207_235_err</td>
						<td>$myage_207_206</td>
						<td>$myage_207_206_err</td>
						<td>$myrho</td>
						<td>$myage_208_232</td>
						<td>$myage_208_232_err</td>
					</tr>";

		}

		echo "Additional Fraction Information:<br>
			<table border=1 class=\"aliquot\">
				<tr>
					<th>Spot ID</th>
					<th>204 corr 206Pb/238U Age</th>
					<th><div style=\"text-transform:none;\">&plusmn;2&sigma;&nbsp;err</div></th>
					<th>204 corr 207Pb/235U Age</th>
					<th><div style=\"text-transform:none;\">&plusmn;2&sigma;&nbsp;err</div></th>
					<th>204 corr 207Pb/206Pb Age</th>
					<th><div style=\"text-transform:none;\">&plusmn;2&sigma;&nbsp;err</div></th>
					<th>Rho</th>
					<th>204 corr 208Pb/232Th Age</th>
					<th><div style=\"text-transform:none;\">&plusmn;2&sigma;&nbsp;err</div></th>
				</tr>
				$rows
			</table>";


	}elseif($ecproject=="arar"){

		$samples = $dom->getElementsByTagName("Sample");
		foreach($samples as $sample){

			$sampleid=$sample->attributes->getNamedItem("sampleID")->value;

		}

		//echo "sampleid: $sampleid";exit();

		$artotal=0;
		
		$measurements = $dom->getElementsByTagName("Measurement");
		foreach($measurements as $measurement){

			$artotal=$artotal+$measurement->attributes->getNamedItem("fraction39ArPotassium")->value;
			$interceptunit=$measurement->attributes->getNamedItem("interceptUnit")->value;

		}
		
		//echo "interceptunit: $interceptunit";exit();
		
		$runningartotal=0;
		
		$measurements = $dom->getElementsByTagName("Measurement");
		foreach($measurements as $measurement){
		
			$myfraction39ArPotassium=sigaloneval($measurement->attributes->getNamedItem("fraction39ArPotassium")->value,4);
		
			$runningartotal=$runningartotal+$measurement->attributes->getNamedItem("fraction39ArPotassium")->value;
			$showar39=sigaloneval($runningartotal/$artotal*100,3);
			
			$mymeasurementNumber=$measurement->attributes->getNamedItem("measurementNumber")->value;
			$mytemperature=sigaloneval($measurement->attributes->getNamedItem("temperature")->value,6);
			$mytemperatureUnit=$measurement->attributes->getNamedItem("temperatureUnit")->value;
			$myintercept40Ar=sigaloneval($measurement->attributes->getNamedItem("intercept40Ar")->value,6);
			$myintercept40ArSigma=sigaloneval($measurement->attributes->getNamedItem("intercept40ArSigma")->value,6);
			$myintercept39Ar=sigaloneval($measurement->attributes->getNamedItem("intercept39Ar")->value,6);
			$myintercept39ArSigma=sigaloneval($measurement->attributes->getNamedItem("intercept39ArSigma")->value,6);
			$myintercept38Ar=sigaloneval($measurement->attributes->getNamedItem("intercept38Ar")->value,6);
			$myintercept38ArSigma=sigaloneval($measurement->attributes->getNamedItem("intercept38ArSigma")->value,6);
			$myintercept37Ar=sigaloneval($measurement->attributes->getNamedItem("intercept37Ar")->value,6);
			$myintercept37ArSigma=sigaloneval($measurement->attributes->getNamedItem("intercept37ArSigma")->value,6);
			$myintercept36Ar=sigaloneval($measurement->attributes->getNamedItem("intercept36Ar")->value,6);
			$myintercept36ArSigma=sigaloneval($measurement->attributes->getNamedItem("intercept36ArSigma")->value,6);
			$mymeasuredKCaRatio=sigaloneval($measurement->attributes->getNamedItem("measuredKCaRatio")->value,6);
			$mymeasuredKCaRatioSigma=sigaloneval($measurement->attributes->getNamedItem("measuredKCaRatioSigma")->value,6);
			$myfraction40ArRadiogenic=sigaloneval($measurement->attributes->getNamedItem("fraction40ArRadiogenic")->value,6);
			$mycorrectedTotal40Ar39ArRatio=sigaloneval($measurement->attributes->getNamedItem("correctedTotal40Ar39ArRatio")->value,6);
			$mycorrectedTotal40Ar39ArRatioSigma=sigaloneval($measurement->attributes->getNamedItem("correctedTotal40Ar39ArRatioSigma")->value,6);
			$mymeasuredAge=sigaloneval($measurement->attributes->getNamedItem("measuredAge")->value,6);
			$mymeasuredAgeSigma=sigaloneval($measurement->attributes->getNamedItem("measuredAgeSigma")->value,6);

			$rows.="<tr>
						<td>$mymeasurementNumber</td>
						<td>$mytemperature</td>
						<td>$showar39</td>
						<td>$myintercept40Ar</td>
						<td>$myintercept40ArSigma</td>
						<td>$myintercept39Ar</td>
						<td>$myintercept39ArSigma</td>
						<td>$myintercept38Ar</td>
						<td>$myintercept38ArSigma</td>
						<td>$myintercept37Ar</td>
						<td>$myintercept37ArSigma</td>
						<td>$myintercept36Ar</td>
						<td>$myintercept36ArSigma</td>
						<td>$mymeasuredKCaRatio</td>
						<td>$mymeasuredKCaRatioSigma</td>
						<td>$myfraction40ArRadiogenic</td>
						<td>$mycorrectedTotal40Ar39ArRatio</td>
						<td>$mycorrectedTotal40Ar39ArRatioSigma</td>
						<td>$mymeasuredAge</td>
						<td>$mymeasuredAgeSigma</td>
					</tr>";

		}

		echo "Additional Fraction Information:<br>
			<table border=1 class=\"aliquot\">
				<tr>
					<th>Step No</th>
					<th>Power ($mytemperatureUnit) </th>
					<th>Cum.% 39Ar</th>
					<th>40Ar ($interceptunit)</th>
					<th><div style=\"text-transform:none;\">&plusmn;1&sigma;</div></th>
					<th>39Ar ($interceptunit)</th>
					<th><div style=\"text-transform:none;\">&plusmn;1&sigma;</div></th>
					<th>38Ar ($interceptunit)</th>
					<th><div style=\"text-transform:none;\">&plusmn;1&sigma;</div></th>
					<th>37Ar ($interceptunit)</th>
					<th><div style=\"text-transform:none;\">&plusmn;1&sigma;</div></th>
					<th>36Ar ($interceptunit)</th>
					<th><div style=\"text-transform:none;\">&plusmn;1&sigma;</div></th>
					<th>K/Ca</th>
					<th><div style=\"text-transform:none;\">&plusmn;1&sigma;</div></th>
					<th>%40Ar*</th>
					<th>40Ar*/39Ar</th>
					<th><div style=\"text-transform:none;\">&plusmn;1&sigma;</div></th>
					<th>Age (Ma)</th>
					<th><div style=\"text-transform:none;\">&plusmn;1&sigma;</div></th>
				</tr>
				$rows
			</table>";

	}elseif($ecproject=="ararxls"){

		$showintensities="no";
		$intensities = $dom->getElementsByTagName("intensities");

		foreach($intensities as $intensity){

			

			$littleintensities = $intensity->getElementsByTagName("intensity");
			foreach($littleintensities as $littleintensity){
				$intensityrows.="<tr>";
				$showintensities="yes";
				
				$intensityrows.="<td>".$littleintensity->attributes->getNamedItem("id")->value."</td>\n";
				$intensityrows.="<td>".$littleintensity->attributes->getNamedItem("power")->value."</td>\n";
				$intensityrows.="<td>".sigaloneval($littleintensity->attributes->getNamedItem("ar40")->value,6)."</td>\n";
				$intensityrows.="<td>".sigaloneval($littleintensity->attributes->getNamedItem("error40ar1s")->value,6)."</td>\n";
				$intensityrows.="<td>".sigaloneval($littleintensity->attributes->getNamedItem("ar39")->value,6)."</td>\n";
				$intensityrows.="<td>".sigaloneval($littleintensity->attributes->getNamedItem("error39ar1s")->value,6)."</td>\n";
				$intensityrows.="<td>".sigaloneval($littleintensity->attributes->getNamedItem("ar38")->value,6)."</td>\n";
				$intensityrows.="<td>".sigaloneval($littleintensity->attributes->getNamedItem("error38ar1s")->value,6)."</td>\n";
				$intensityrows.="<td>".sigaloneval($littleintensity->attributes->getNamedItem("ar37")->value,6)."</td>\n";
				$intensityrows.="<td>".sigaloneval($littleintensity->attributes->getNamedItem("error37ar1s")->value,6)."</td>\n";
				$intensityrows.="<td>".sigaloneval($littleintensity->attributes->getNamedItem("ar36")->value,6)."</td>\n";
				$intensityrows.="<td>".sigaloneval($littleintensity->attributes->getNamedItem("error36ar1s")->value,6)."</td>\n";
				$intensityrows.="<td>".sigaloneval($littleintensity->attributes->getNamedItem("ar40pct")->value,6)."</td>\n";
				$intensityrows.="<td>".sigaloneval($littleintensity->attributes->getNamedItem("ar40ar39k")->value,6)."</td>\n";
				$intensityrows.="<td>".sigaloneval($littleintensity->attributes->getNamedItem("error40ar39ark1s")->value,6)."</td>\n";
				$intensityrows.="<td>".sigaloneval($littleintensity->attributes->getNamedItem("age")->value,6)."</td>\n";
				$intensityrows.="<td>".sigaloneval($littleintensity->attributes->getNamedItem("ageerror1s")->value,6)."</td>\n";
		
				$intensityrows.="</tr>\n";
			}

			

		}

		if($showintensities=="yes"){

		echo "Additional Fraction Information:<br>
			<table border=1 class=\"aliquot\">
				<tr>		
			<th>ID</th>
			<th>Power</th>
			<th>40Ar</th>
			<th><div style=\"text-transform:none;\">&plusmn;1&sigma;</div></th>
			<th>39Ar</th>
			<th><div style=\"text-transform:none;\">&plusmn;1&sigma;</div></th>
			<th>38Ar</th>
			<th><div style=\"text-transform:none;\">&plusmn;1&sigma;</div></th>
			<th>37Ar</th>
			<th><div style=\"text-transform:none;\">&plusmn;1&sigma;</div></th>
			<th>36Ar</th>
			<th><div style=\"text-transform:none;\">&plusmn;1&sigma;</div></th>
			<th>40Ar* %</th>
			<th>40Ar*/39ArK</th>
			<th><div style=\"text-transform:none;\">&plusmn;1&sigma;</div></th>
			<th>Age</th>
			<th><div style=\"text-transform:none;\">&plusmn;1&sigma;</div></th>
			</tr>
			$intensityrows
			</table>";
		
		}

















		$showratios="no";
		$ratios = $dom->getElementsByTagName("ratios");

		foreach($ratios as $ratio){

			$littleratios = $ratio->getElementsByTagName("ratio");
			foreach($littleratios as $littleratio){
				$ratiorows.="<tr>";
				$showratios="yes";
				
				$ratiorows.="<td>".$littleratio->attributes->getNamedItem("id")->value."</td>\n";
				$ratiorows.="<td>".$littleratio->attributes->getNamedItem("power")->value."</td>\n";
				$ratiorows.="<td>".sigaloneval($littleratio->attributes->getNamedItem("ar40ar39")->value,6)."</td>\n";
				$ratiorows.="<td>".sigaloneval($littleratio->attributes->getNamedItem("ar37ar39")->value,6)."</td>\n";
				$ratiorows.="<td>".sigaloneval($littleratio->attributes->getNamedItem("ar36ar39")->value,6)."</td>\n";
				$ratiorows.="<td>".sigaloneval($littleratio->attributes->getNamedItem("ar39k")->value,6)."</td>\n";
				$ratiorows.="<td>".sigaloneval($littleratio->attributes->getNamedItem("kca")->value,6)."</td>\n";
				$ratiorows.="<td>".sigaloneval($littleratio->attributes->getNamedItem("ar40")->value,6)."</td>\n";
				$ratiorows.="<td>".sigaloneval($littleratio->attributes->getNamedItem("ar39")->value,6)."</td>\n";
				$ratiorows.="<td>".sigaloneval($littleratio->attributes->getNamedItem("age")->value,6)."</td>\n";
				$ratiorows.="<td>".sigaloneval($littleratio->attributes->getNamedItem("ageerror1s")->value,6)."</td>\n";
				$ratiorows.="</tr>\n";
			}

		}

		if($showratios=="yes"){
		
		if($showintensities=="yes"){
			echo "<br><br>";
		}

		echo "Additional Fraction Information:<br>
			<table border=1 class=\"aliquot\">
			<tr>
			<th>ID</th>
			<th>Power</th>
			<th>40Ar/39Ar</th>
			<th>37Ar/39Ar</th>
			<th>36Ar/39Ar</th>
			<th>39ArK</th>
			<th>K/Ca</th>
			<th>40Ar*</th>
			<th>39Ar</th>
			<th>Age</th>
			<th><div style=\"text-transform:none;\">&plusmn;1&sigma;</div></th>
			</tr>
			$ratiorows
			</table>";
		
		}









	}elseif($ecproject=="fissiontrack"){

		$apatiteages = $dom->getElementsByTagName("apatiteages");

		foreach($apatiteages as $apatiteage){

			$grainrows = "Additional Fraction Information:<br>
				<table border=1 class=\"aliquot\">
					<tr>		
				<th>Grain ID</th>
				<th>N s</th>
				<th>N i</th>
				<th>Na</th>
				<th>Dpar</th>
				<th>Dper</th>
				<th>Rmr0</th>
				<th>Rho s</th>
				<th>Rho i</th>
				<th>Rho s / Rho i</th>
				<th>Area</th>
				<th>Number of Etch Figures</th>
				<th>238U/43Ca</th>
				<th><div style=\"text-transform:none;\">(&plusmn;1&sigma;)</div></th>
				<th>U ppm</th>
				<th><div style=\"text-transform:none;\">(&plusmn;1&sigma;)</div></th>
				<th>Age (Ma)</th>
				<th><div style=\"text-transform:none;\">(&plusmn;1&sigma;)</div></th>
				<th>CaO</th>
				<th>P2O5</th>
				<th>F</th>
				<th>Cl</th>
				<th>SrO</th>
				<th>BaO</th>
				<th>SiO2</th>
				<th>Na2O</th>
				<th>CeO2</th>
				<th>FeO</th>
				<th>Total</th>
				</tr>
				<tr>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				<th>".$apatiteage->attributes->getNamedItem("caounits")->value."</th>
				<th>".$apatiteage->attributes->getNamedItem("p2o5units")->value."</th>
				<th>".$apatiteage->attributes->getNamedItem("funits")->value."</th>
				<th>".$apatiteage->attributes->getNamedItem("clunits")->value."</th>
				<th>".$apatiteage->attributes->getNamedItem("srounits")->value."</th>
				<th>".$apatiteage->attributes->getNamedItem("baounits")->value."</th>
				<th>".$apatiteage->attributes->getNamedItem("si02units")->value."</th>
				<th>".$apatiteage->attributes->getNamedItem("na2ounits")->value."</th>
				<th>".$apatiteage->attributes->getNamedItem("ceo2units")->value."</th>
				<th>".$apatiteage->attributes->getNamedItem("feounits")->value."</th>
				<th>".$apatiteage->attributes->getNamedItem("totalunits")->value."</th>
				</tr>
				
				";

			$grains = $apatiteage->getElementsByTagName("grain");
			foreach($grains as $grain){
				$grainrows.="<tr>";

				$grainrows.="<td>".$grain->attributes->getNamedItem("grainid")->value."</td>\n";
				$grainrows.="<td>".$grain->attributes->getNamedItem("ns")->value."</td>\n";
				$grainrows.="<td>".$grain->attributes->getNamedItem("ni")->value."</td>\n";
				$grainrows.="<td>".$grain->attributes->getNamedItem("na")->value."</td>\n";
				$grainrows.="<td>".$grain->attributes->getNamedItem("dpar")->value."</td>\n";
				$grainrows.="<td>".$grain->attributes->getNamedItem("dper")->value."</td>\n";
				$grainrows.="<td>".$grain->attributes->getNamedItem("rmr0")->value."</td>\n";
				$grainrows.="<td>".$grain->attributes->getNamedItem("rhos")->value."</td>\n";
				$grainrows.="<td>".$grain->attributes->getNamedItem("rhoi")->value."</td>\n";
				$grainrows.="<td>".$grain->attributes->getNamedItem("rhosrhoi")->value."</td>\n";
				$grainrows.="<td>".$grain->attributes->getNamedItem("area")->value."</td>\n";
				$grainrows.="<td>".$grain->attributes->getNamedItem("ofetchfigures")->value."</td>\n";
				$grainrows.="<td>".$grain->attributes->getNamedItem("u238ca43")->value."</td>\n";
				$grainrows.="<td>".$grain->attributes->getNamedItem("error1s")->value."</td>\n";
				$grainrows.="<td>".$grain->attributes->getNamedItem("uppm")->value."</td>\n";
				$grainrows.="<td>".$grain->attributes->getNamedItem("uerror1s")->value."</td>\n";
				$grainrows.="<td>".$grain->attributes->getNamedItem("agema")->value."</td>\n";
				$grainrows.="<td>".$grain->attributes->getNamedItem("ageerror1s")->value."</td>\n";
				$grainrows.="<td>".$grain->attributes->getNamedItem("cao")->value."</td>\n";
				$grainrows.="<td>".$grain->attributes->getNamedItem("p2o5")->value."</td>\n";
				$grainrows.="<td>".$grain->attributes->getNamedItem("f")->value."</td>\n";
				$grainrows.="<td>".$grain->attributes->getNamedItem("cl")->value."</td>\n";
				$grainrows.="<td>".$grain->attributes->getNamedItem("sro")->value."</td>\n";
				$grainrows.="<td>".$grain->attributes->getNamedItem("bao")->value."</td>\n";
				$grainrows.="<td>".$grain->attributes->getNamedItem("si02")->value."</td>\n";
				$grainrows.="<td>".$grain->attributes->getNamedItem("na2o")->value."</td>\n";
				$grainrows.="<td>".$grain->attributes->getNamedItem("ceo2")->value."</td>\n";
				$grainrows.="<td>".$grain->attributes->getNamedItem("feo")->value."</td>\n";
				$grainrows.="<td>".$grain->attributes->getNamedItem("total")->value."</td>\n";
		
				$grainrows.="</tr>\n";
			}

			

		}



			
			$grainrows.="
			</table>";


			echo $grainrows;



	}elseif($ecproject=="igor"){


	?>
	Additional Grain Information:<br>
	<table class="aliquot" border=1>
	<tr>
		<th>Grain ID</th>
		<th><div style="text-transform:none;">206/238 DATE (Ma)</div></th><th><div style="text-transform:none;">&plusmn;2&sigma; (abs)</div></th>
		<th><div style="text-transform:none;">207/235 DATE (Ma)</div></th><th><div style="text-transform:none;">&plusmn;2&sigma; (abs)</div></th>
		<th><div style="text-transform:none;">207/206 DATE (Ma)</div></th><th><div style="text-transform:none;">&plusmn;2&sigma; (abs)</div></th>
	</tr>
	<?
	
	//getElementFromDom($varname,$dom)
	
	$grains = $dom->getElementsByTagName("graindata");
	foreach($grains as $grain){

		$grainid = getElementFromDom("realgrain",$grain);
		$FinalAge206_238 = getElementFromDom("FinalAge206_238",$grain);
		$FinalAge206_238_Int2SE = getElementFromDom("FinalAge206_238_Int2SE",$grain);
		$FinalAge207_235 = getElementFromDom("FinalAge207_235",$grain);
		$FinalAge207_235_Int2SE = getElementFromDom("FinalAge207_235_Int2SE",$grain);
		$FinalAge207_206 = getElementFromDom("FinalAge207_206",$grain);
		$FinalAge207_206_Int2SE = getElementFromDom("FinalAge207_206_Int2SE",$grain);

		?>
		<tr>
		<td><?=$grainid?></td>
		<td><?=$FinalAge206_238?></td>
		<td><?=$FinalAge206_238_Int2SE?></td>
		<td><?=$FinalAge207_235?></td>
		<td><?=$FinalAge207_235_Int2SE?></td>
		<td><?=$FinalAge207_206?></td>
		<td><?=$FinalAge207_206_Int2SE?></td>
		</tr>
		<?

	}
		

	/*
	FinalAge206_238
	FinalAge206_238_Int2SE
	FinalAge207_235
	FinalAge207_235_Int2SE
	FinalAge207_206
	FinalAge207_206_Int2SE


	$FinalAge206_238 = getElementFromDom("FinalAge206_238",$grain);
	$FinalAge206_238_Int2SE = getElementFromDom("FinalAge206_238_Int2SE",$grain);
	$FinalAge207_235 = getElementFromDom("FinalAge207_235",$grain);
	$FinalAge207_235_Int2SE = getElementFromDom("FinalAge207_235_Int2SE",$grain);
	$FinalAge207_206 = getElementFromDom("FinalAge207_206",$grain);
	$FinalAge207_206_Int2SE = getElementFromDom("FinalAge207_206_Int2SE",$grain);

	<td><?=$FinalAge206_238?></td>
	<td><?=$FinalAge206_238_Int2SE?></td>
	<td><?=$FinalAge207_235?></td>
	<td><?=$FinalAge207_235_Int2SE?></td>
	<td><?=$FinalAge207_206?></td>
	<td><?=$FinalAge207_206_Int2SE?></td>
	*/


	?>
	</table>
	<?

	}else{ //ecproject 
		echo "for future development";
	}

}//end if dom load








/*
old arar
		$artotal=0;
		
		$measurements = $dom->getElementsByTagName("Measurement");
		foreach($measurements as $measurement){

			$artotal=$artotal+$measurement->attributes->getNamedItem("fraction39ArPotassium")->value;

		}
		
		$runningartotal=0;
		
		$measurements = $dom->getElementsByTagName("Measurement");
		foreach($measurements as $measurement){
		
			$runningartotal=$runningartotal+$measurement->attributes->getNamedItem("fraction39ArPotassium")->value;
			$showar39=sigaloneval($runningartotal/$artotal*100,3);
			
			$mymeasurementNumber=$measurement->attributes->getNamedItem("measurementNumber")->value;
			$mytemperature=sigaloneval($measurement->attributes->getNamedItem("temperature")->value,4);
			$mytemperatureUnit=$measurement->attributes->getNamedItem("temperatureUnit")->value;
			$mycorrectedTotal40Ar39ArRatio=sigaloneval($measurement->attributes->getNamedItem("correctedTotal40Ar39ArRatio")->value,4);
			$mycorrectedTotal37Ar39ArRatio=sigaloneval($measurement->attributes->getNamedItem("correctedTotal37Ar39ArRatio")->value,3);
			$mycorrectedTotal36Ar39ArRatio=sigaloneval($measurement->attributes->getNamedItem("correctedTotal36Ar39ArRatio")->value,4);
			$mycorrected39ArPotassium=sigaloneval($measurement->attributes->getNamedItem("corrected39ArPotassium")->value,3);
			$mymeasuredKCaRatio=sigaloneval($measurement->attributes->getNamedItem("measuredKCaRatio")->value,3);
			$myfraction40ArRadiogenic=sigaloneval($measurement->attributes->getNamedItem("fraction40ArRadiogenic")->value,3);
			$myfraction39ArPotassium=sigaloneval($measurement->attributes->getNamedItem("fraction39ArPotassium")->value,4);
			$mymeasuredAge=sigaloneval($measurement->attributes->getNamedItem("measuredAge")->value,4);
			$mymeasuredAgeSigma=sigaloneval($measurement->attributes->getNamedItem("measuredAgeSigma")->value,3);

			$rows.="<tr>
						<td>$mymeasurementNumber</td>
						<td>$mytemperature $mytemperatureUnit</td>
						<td>$mycorrectedTotal40Ar39ArRatio</td>
						<td>$mycorrectedTotal37Ar39ArRatio</td>
						<td>$mycorrectedTotal36Ar39ArRatio</td>
						<td>$mycorrected39ArPotassium</td>
						<td>$mymeasuredKCaRatio</td>
						<td>$myfraction40ArRadiogenic</td>
						<td>$showar39</td>
						<td>$mymeasuredAge</td>
						<td>$mymeasuredAgeSigma</td>
					</tr>";

		}

		echo "Additional Fraction Information:<br>
			<table border=1 class=\"aliquot\">
				<tr>
					<th>ID (step or grain)</th>
					<th>Power (Watts, Temp)</th>
					<th>40Ar/39Ar</th>
					<th>37Ar/39Ar</th>
					<th>36Ar/39Ar (x 10-3)</th>
					<th>39ArK (x 10-15 mol)</th>
					<th>K/Ca</th>
					<th>40Ar* (%)</th>
					<th>39Ar (%)</th>
					<th>Age (Ma)</th>
					<th><div style=\"text-transform:none;\">&plusmn;1&sigma;&nbsp;err (Ma)</div></th>
				</tr>
				$rows
			</table>";
*/
?>