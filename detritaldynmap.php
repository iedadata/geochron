<?PHP
/**
 * detritaldynmap.php
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


if (!extension_loaded("MapScript"))
  dl('php_mapscript.'.PHP_SHLIB_SUFFIX);

session_start();

//print_r($_SESSION);



include("db.php");

// **************** get username stuff here for the search ***************
if($_SESSION['username']!=""){
	$username=$_SESSION['username'];
	//$userrow=$db->get_row("select * from users where username='$username'");
	$userrow=$db->get_row("select * from users where email='$username'");
	$grouparray=$userrow->grouparray;
	$group=$userrow->usergroup;
	$userpkey=$userrow->users_pkey;
}elseif($_POST['username']!="" & $_POST['password']!=""){
	$username=$_POST['username'];
	$password=$_POST['password'];
	$userrow=$db->get_row("select * from users where username='$username' and password='$password'");
	$group=$userrow->usergroup;
	$userpkey=$userrow->users_pkey;
}

$grouparray=str_replace("{","",$grouparray);
$grouparray=str_replace("}","",$grouparray);

if($group==0 or $group==""){
	$group=99999;
}

if($grouparray==""){
	$grouparray=99999;
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


//echo "userpkey: $userpkey";exit();



/*
$mapstring="select lon as longitude,
lat as latitude, age, detritaltype from testdata limit 500
";
*/

$agemin=$_GET['agemin']; //*1000000;
$agemax=$_GET['agemax']; //*1000000;
$geoages=$_GET['geoages'];
$detritaltype=$_GET['detritaltype'];
$detritalmineral=$_GET['detritalmineral'];
$detritalmethod=$_GET['detritalmethod'];

//$conc=$_GET['conc'];
$showfig=$_GET['showfig'];
$zoom=$_GET['zoom'];
$showall=$_GET['showall'];
$hmysamples=$_GET['hmysamples'];
$label=$_GET['label'];


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

//print_r($geoagearray); exit();

//build bbox for query
if($_GET['BBOX'] != "" ){
	$mybox=$_GET['BBOX'];
}else{
	$mybox="-146.78124999999997,-5.062500000000085,-56.78124999999997,84.93749999999991";
}

$parts=explode(",",$mybox);

$lon1=$parts[0];
$lat1=$parts[1];
$lon2=$parts[2];
$lat2=$parts[3];

$boundstring="$lon1 $lat1, $lon1 $lat2, $lon2 $lat2, $lon2 $lat1, $lon1 $lat1";
$boundstring="and ST_Contains(ST_GeomFromText('Polygon(($boundstring))'),mypoint)";


if($showall=="no"){

	$mapstring="select sample.sample_pkey as pkey,
						sample.sample_id,
						longitude,
						latitude, 
						age_min,
						age_max,
						detrital_type,
						ecproject,
						material,
						userpkey
						from sample 
						left join sample_age on sample.sample_pkey = sample_age.sample_pkey
						left join groupsamplerelate on sample.sample_pkey = groupsamplerelate.sample_pkey
						left join grouprelate on groupsamplerelate.group_pkey = grouprelate.group_pkey
						left join datasetrelate dr on dr.sample_pkey = sample.sample_pkey
						left join datasetuserrelate dur on dur.dataset_pkey = dr.dataset_pkey
						left join datasets ds on dr.dataset_pkey = ds.dataset_pkey
						where 1=1 ";
	
	$agesdelim="";
	
	if($agemin!="" || $agemax !=""){
		$mapstring.=" and (";
		
		if($agemin!=""){
			$mapstring.=$agesdelim."(age_min >= $agemin or age_max >= $agemin) ";
			$agesdelim=" and ";
		}
	
		if($agemax!=""){
			$mapstring.=$agesdelim."(age_min <= $agemax or age_max <= $agemax) ";
		}
		
		$mapstring.=")";
	}
	
	
	
	if($detritaltype!=""){
		$mapstring.="and detrital_type='$detritaltype' ";
	}

	if($detritalmineral!=""){
		$mapstring.="and material='$detritalmineral' ";
	}

	if($detritalmethod!=""){
		$mapstring.="and ecproject='$detritalmethod' ";
	}

	if(count($geoagearray)>0){
	
		$mapstring.=" and (";
	
		$geoages=explode(",",$geoages);
		$geodelim="";
		foreach($geoagearray as $geoage){
			$thisminage=$geoage[minage];
			$thismaxage=$geoage[maxage];
			//$mapstring.=$geodelim."( age >= ".$georow->minage*1000000." and age <= ".$georow->minage*1000000." )";
			//$mapstring.=$geodelim."( age >= $thisminage and age <= $thismaxage )";
			$mapstring.=$geodelim."(age_min >= $thisminage or age_max >= $thisminage) and (age_min <= $thismaxage or age_max <= $thismaxage) ";
			$geodelim=" or ";
		}
		
		$mapstring.=") ";
	}
	
	$mapstring.=" 	and upstream=true
					and (sample.publ=1 or sample.userpkey=$userpkey or ((dur.users_pkey=$userpkey and dur.confirmed=true) or ds.users_pkey=$userpkey ) or (grouprelate.users_pkey=$userpkey and grouprelate.confirmed=true))
					$boundstring
					group by
					pkey,
					sample.sample_id,
					longitude,
					latitude, 
					age_min,
					age_max,
					detrital_type,
					ecproject,
					material,
					userpkey
					";
	
	//echo $mapstring; exit();

}else{
	//showall == yes
	$mapstring="select sample.sample_pkey as pkey,
				sample.sample_id,
				longitude,
				latitude, 
				age_min,
				age_max,
				detrital_type,
				ecproject,
				material,
				userpkey
				from sample 
				left join sample_age on sample.sample_pkey = sample_age.sample_pkey
				left join groupsamplerelate on sample.sample_pkey = groupsamplerelate.sample_pkey
				left join grouprelate on groupsamplerelate.group_pkey = grouprelate.group_pkey
				left join datasetrelate dr on dr.sample_pkey = sample.sample_pkey
				left join datasetuserrelate dur on dur.dataset_pkey = dr.dataset_pkey
				left join datasets ds on dr.dataset_pkey = ds.dataset_pkey
				where 1=1
				and upstream=true
				and (sample.publ=1 or sample.userpkey=$userpkey or ((dur.users_pkey=$userpkey and dur.confirmed=true) or ds.users_pkey=$userpkey ) or (grouprelate.users_pkey=$userpkey and grouprelate.confirmed=true))
				$boundstring
				group by
				pkey,
				sample.sample_id,
				longitude,
				latitude, 
				age_min,
				age_max,
				detrital_type,
				ecproject,
				material,
				userpkey
				";

}

//echo nl2br($mapstring); exit();


$rows=$db->get_results("$mapstring");

//create map file in /dev/shm here
$filename=time().rand(99999,999999).".map";
//echo "filename: $filename";

if($showfig=="conc"){

	if($zoom<7){
		$filefolder=$zoom;
	}else{
		$filefolder=6;
	}
	
	$filestring="MAP
	  NAME MAP_POINTS
	  SIZE 600 300
	  EXTENT -180 -90 180 90
	  IMAGETYPE png
	  TRANSPARENT true
      FONTSET /public/mgg/web/www.geochron.org/htdocs/fontset.txt
	";
	
	//IMAGE '/var/www/geochron/concordias/$filefolder/".$row->pkey.".jpg' 
	
	foreach($rows as $row){
if(file_exists("concordias/$filefolder/".$row->pkey.".gif")){
$filestring.="
  SYMBOL
    NAME '".$row->pkey."'
    TYPE pixmap
    IMAGE '/public/mgg/web/www.geochron.org/htdocs/concordias/$filefolder/".$row->pkey.".gif' 
  END
";
}else{
$filestring.="
  SYMBOL
    NAME '".$row->pkey."'
	TYPE ellipse
	FILLED true
	POINTS
		9 9
	END
  END
";
}
}


$filestring.="  LAYER # begin antialiased country boundary (line) layer
    NAME 'country_line'
    DATA '/public/mgg/web/www.geochron.org/htdocs/world_borders'
    TYPE LINE
    STATUS ON
    TRANSPARENCY ALPHA
    
    PROJECTION
      \"init=epsg:4326\"
    END

    CLASS
      NAME 'Country Boundary'
      STYLE
        COLOR 96 96 96
        WIDTH 2
        ANTIALIAS TRUE
      END
    END
  END # end country boundary layer
  
  
  LAYER # state layer
    NAME 'state_line'
    DATA '/public/mgg/web/www.geochron.org/htdocs/fe_2007_us_state00'
    TYPE LINE
    STATUS ON
    TRANSPARENCY ALPHA
    
    PROJECTION
      \"init=epsg:4326\"
    END

    CLASS
      NAME 'State Boundary'
      STYLE
        COLOR 120 120 120
        WIDTH 2
        ANTIALIAS TRUE
      END
    END
  END # end state boundary layer

	LAYER
		TYPE POINT
		NAME pointonly
		STATUS OFF
		PROJECTION
			\"proj=latlong\"
		END
		LABELCACHE ON
	END


	LAYER
	  NAME snet
	  STATUS ON
	  TYPE POINT
	  LABELCACHE ON
	  PROJECTION
	   \"init=epsg:4326\"
	  END
	  CLASS
		COLOR -1 -1 -1
		LABEL
		  COLOR 0 0 0
	      OUTLINECOLOR 255 255 250
	      OUTLINEWIDTH 3
		  SIZE 12
		  MINSIZE 4
		  MAXSIZE 18
		  TYPE TRUETYPE
		  FONT alte
		  ANTIALIAS TRUE
		  BUFFER 2
		  FORCE TRUE
		  POSITION UC
		  OFFSET 2 1
		END
	  END
	  CLASS
		COLOR -1 -1 -1
		LABEL
		  COLOR 0 0 0
	      OUTLINECOLOR 255 255 250
	      OUTLINEWIDTH 3
		  SIZE 12
		  MINSIZE 4
		  MAXSIZE 18
		  TYPE TRUETYPE
		  FONT alte
		  ANTIALIAS TRUE
		  BUFFER 2
		  FORCE FALSE
		  POSITION AUTO
		  OFFSET 2 1
		END
	  END
	  CLASS
		COLOR -1 -1 -1
		LABEL
		  COLOR  255 255 0
		  OUTLINECOLOR  0 0 0
		  SHADOWCOLOR 0 0 0
		  SHADOWSIZE 1 1
		  SIZE 16
		  MINSIZE 4
		  MAXSIZE 18
		  TYPE TRUETYPE
		  FONT alte
		  ANTIALIAS TRUE
		  BUFFER 2
		  FORCE TRUE
		  POSITION UC
		  OFFSET 2 1
		END
	  END
	END


END
";
	//echo "count: ".count($rows);exit();
	//echo $filestring;exit();

	
	$myFile = "/public/mgg/web/www.geochron.org/htdocs/mapfiles/$filename";
	$fh = fopen($myFile, 'w') or die("can't open file");
	fwrite($fh, $filestring);
	fclose($fh);
	//chmod($myFile, 0777);
	
	
	// Create a map object.
	//$oMap = ms_newMapObj("ex4_map_points.map");
	$oMap = ms_newMapObj("/public/mgg/web/www.geochron.org/htdocs/mapfiles/$filename");
	//$oMap = ms_newMapObj("/public/mgg/web/www.geochron.org/htdocs/mapfiles/1384791571772432.map");

}elseif($showfig=="prob"){



	if($zoom<7){
		$filefolder=$zoom;
	}else{
		$filefolder=6;
	}
	
	$filestring="MAP
	  NAME MAP_POINTS
	  SIZE 600 300
	  EXTENT -180 -90 180 90
	  IMAGETYPE png
	  TRANSPARENT true
      FONTSET /public/mgg/web/www.geochron.org/htdocs/fontset.txt
	";
	
	//IMAGE '/var/www/geochron/concordias/$filefolder/".$row->pkey.".jpg' 
	
	foreach($rows as $row){
if(file_exists("probabilities/$filefolder/".$row->pkey.".gif")){
$filestring.="
  SYMBOL
    NAME '".$row->pkey."'
    TYPE pixmap
    IMAGE '/public/mgg/web/www.geochron.org/htdocs/probabilities/$filefolder/".$row->pkey.".gif' 
  END
";
}else{
$filestring.="
  SYMBOL
    NAME '".$row->pkey."'
	TYPE ellipse
	FILLED true
	POINTS
		9 9
	END
  END
";
}
}


$filestring.="  LAYER # begin antialiased country boundary (line) layer
    NAME 'country_line'
    DATA '/public/mgg/web/www.geochron.org/htdocs/world_borders'
    TYPE LINE
    STATUS ON
    TRANSPARENCY ALPHA
    
    PROJECTION
      \"init=epsg:4326\"
    END

    CLASS
      NAME 'Country Boundary'
      STYLE
        COLOR 96 96 96
        WIDTH 2
        ANTIALIAS TRUE
      END
    END
  END # end country boundary layer
  
  
  LAYER # state layer
    NAME 'state_line'
    DATA '/public/mgg/web/www.geochron.org/htdocs/fe_2007_us_state00'
    TYPE LINE
    STATUS ON
    TRANSPARENCY ALPHA
    
    PROJECTION
      \"init=epsg:4326\"
    END

    CLASS
      NAME 'State Boundary'
      STYLE
        COLOR 120 120 120
        WIDTH 2
        ANTIALIAS TRUE
      END
    END
  END # end state boundary layer

	LAYER
		TYPE POINT
		NAME pointonly
		STATUS OFF
		PROJECTION
			\"proj=latlong\"
		END
		LABELCACHE ON
	END


	LAYER
	  NAME snet
	  STATUS ON
	  TYPE POINT
	  LABELCACHE ON
	  PROJECTION
	   \"init=epsg:4326\"
	  END
	  CLASS
		COLOR -1 -1 -1
		LABEL
		  COLOR 0 0 0
	      OUTLINECOLOR 255 255 250
	      OUTLINEWIDTH 3
		  SIZE 12
		  MINSIZE 4
		  MAXSIZE 18
		  TYPE TRUETYPE
		  FONT alte
		  ANTIALIAS TRUE
		  BUFFER 2
		  FORCE TRUE
		  POSITION UC
		  OFFSET 2 1
		END
	  END
	  CLASS
		COLOR -1 -1 -1
		LABEL
		  COLOR 0 0 0
	      OUTLINECOLOR 255 255 250
	      OUTLINEWIDTH 3
		  SIZE 12
		  MINSIZE 4
		  MAXSIZE 18
		  TYPE TRUETYPE
		  FONT alte
		  ANTIALIAS TRUE
		  BUFFER 2
		  FORCE FALSE
		  POSITION AUTO
		  OFFSET 2 1
		END
	  END
	  CLASS
		COLOR -1 -1 -1
		LABEL
		  COLOR  255 255 0
		  OUTLINECOLOR  0 0 0
		  SHADOWCOLOR 0 0 0
		  SHADOWSIZE 1 1
		  SIZE 16
		  MINSIZE 4
		  MAXSIZE 18
		  TYPE TRUETYPE
		  FONT alte
		  ANTIALIAS TRUE
		  BUFFER 2
		  FORCE TRUE
		  POSITION UC
		  OFFSET 2 1
		END
	  END
	END


END
";
	//echo "count: ".count($rows);exit();
	//echo $filestring;exit();

	
	$myFile = "/public/mgg/web/www.geochron.org/htdocs/mapfiles/$filename";
	$fh = fopen($myFile, 'w') or die("can't open file");
	fwrite($fh, $filestring);
	fclose($fh);
	//chmod($myFile, 0777);
	
	
	// Create a map object.
	//$oMap = ms_newMapObj("ex4_map_points.map");
	//$oMap = ms_newMapObj("/public/mgg/web/www.geochron.org/htdocs/mapfiles/$filename");
	$oMap = ms_newMapObj("/public/mgg/web/www.geochron.org/htdocs/mapfiles/$filename");
	//$oMap = ms_newMapObj("/public/mgg/web/www.geochron.org/htdocs/mapfiles/1384791571772432.map");


}else{ //end if showfig==conc or prob

	$oMap = ms_newMapObj("/local/public/mgg/web/www.geochron.org/htdocs/dynmap.map");

} //end if conc==yes

$oMap->setSize(800,600);

if($_GET['BBOX'] != "" ){
	$mybox=$_GET['BBOX'];
}else{
	$mybox="-146.78124999999997,-5.062500000000085,-56.78124999999997,84.93749999999991";
}

$BBOX=explode(",",$mybox);

$oMap->setExtent($BBOX[0], $BBOX[1], $BBOX[2], $BBOX[3]);

$nSymbolId = ms_newSymbolObj($oMap, "circle");
$oSymbol = $oMap->getsymbolobjectbyid($nSymbolId);
$oSymbol->set("type", MS_SYMBOL_ELLIPSE);
$oSymbol->set("filled", MS_TRUE);
$aPoints[0] = 1;
$aPoints[1] = 1;
$oSymbol->setpoints($aPoints);


/*
$oLayerClouds = ms_newLayerObj($oMap);
$oLayerClouds->set( "name", "clouds");
$oLayerClouds->set( "type", MS_LAYER_RASTER);
$oLayerClouds->set( "status", MS_DEFAULT);
$oLayerClouds->set( "data","data/global_clouds.tif");
*/


$oLayerPoints=$oMap->getlayerbyname("pointonly");
$oLayerPoints->set("status", MS_ON);

/*
// Create another layer to hold point locations
$oLayerPoints = ms_newLayerObj($oMap);
$oLayerPoints->set( "name", "custom_points");
$oLayerPoints->set( "type", MS_LAYER_POINT);
$oLayerPoints->set( "status", MS_DEFAULT);
$oLayerPoints->set("labelitem","dynamic label");
$oLayerPoints->set( "labelcache", OFF);

//$oLayerPoints->set("transparency", 20);
*/


$oMapClass = ms_newClassObj($oLayerPoints);


/*
$oMapClass->set("type", MS_BITMAP);
$oMapClass->label->set( "position", MS_AUTO);
$oMapClass->label->set( "size", 15);
$oMapClass->label->color->setRGB(250,0,0);
$oMapClass->label->outlinecolor->setRGB(255,255,255);
*/

$oMapImage = $oMap->prepareImage();

$clines=$oMap->getlayerbyname("country_line");
$clines->set("status",MS_ON);
$clines->draw($oMapImage);

$slines=$oMap->getlayerbyname("state_line");
$slines->set("status",MS_ON);
$slines->draw($oMapImage);

$snet = $oMap->getlayerbyname("snet");
$snet->set("status",MS_ON);

srand(time());


if(count($rows)>0){
	//roll over results twice,
	//first show the grey ones, then the red ones,
	//to preserve visibilit of red which is most
	//important

	if($showfig=="conc" || $showfig=="prob"){

		//create a new Symbol Object here... we can use a style later to change it a bit
		/*
		foreach($rows as $row){
			$nSymbolId = ms_newSymbolObj($oMap, $row->pkey);
			$oSymbol = $oMap->getsymbolobjectbyid($nSymbolId);
			$oSymbol->set("type", MS_SYMBOL_PIXMAP);
			$oSymbol->set("filled", MS_TRUE);
			$aPoints[0] = 1;
			$aPoints[1] = 1;
			$oSymbol->setpoints($aPoints);
		}
		*/

		// Create a style object defining how to draw features
		$oPointStyle = ms_newStyleObj($oMapClass);
		//$oPointStyle->color->setRGB(250,0,0);
		$oPointStyle->outlinecolor->setRGB(0,0,0);
		$oPointStyle->set( "symbolname", "circle");
		$oPointStyle->set( "size", "9");
		$oPointStyle->color->setRGB(255,255,0);

		$oPointStyle = ms_newStyleObj($oMapClass);
		$oPointStyle->outlinecolor->setRGB(0,0,0);
		//$oPointStyle->set( "symbolname", "mygif"); //this comes from the symbol object we defined above.



		foreach($rows as $row){ //then red
		   
			//check for set values
			$agepasses="no";
			if($agemin!=""||$agemax!=""){
				$thisminage=$row->age_min;
				$thismaxage=$row->age_max;
				
				if($agemin!=""){
					
					if($thisminage>=$agemin||$thismaxage>=$agemin){
						$agepasses="yes";	
					}else{
						$agepasses="no";
					}
										
				}

				if($agemax!=""){
					
					if($thisminage<=$agemax||$thismaxage<=$agemax){
						$agepasses="yes";	
					}else{
						$agepasses="no";
					}
										
				}
				
			}elseif($geoages!=""){
				//figure out geoages stuff here
				//grey geoages now ... initially set to no
				$rowminage=$row->age_min;
				$rowmaxage=$row->age_max;

				foreach($geoagearray as $geoage){
					$thisminage=$geoage[minage];
					$thismaxage=$geoage[maxage];
					//(age_min >= $thisminage or age_max >= $thisminage) and (age_min <= $thismaxage or age_max <= $thismaxage) 
					//if($thisage>=$thisminage && $thisage<=$thismaxage ){
					if(($rowminage >= $thisminage || $rowmaxage >= $thisminage) && ($rowminage <= $thismaxage || $rowmaxage <= $thismaxage)){
						$agepasses="yes";
					}
				}
			}else{
				$agepasses="yes";
			}
			
			$detritalpasses="no";
			if($detritaltype!=""){
				$thisdetritaltype=$row->detrital_type;
				if($thisdetritaltype==$detritaltype){
					$detritalpasses="yes";
				}
			}else{
				$detritalpasses="yes";
			}
			
			$detritalmineralpasses="no";
			if($detritalmineral!=""){
				$thisdetritalmineral=$row->material;
				if($thisdetritalmineral==$detritalmineral){
					$detritalmineralpasses="yes";
				}
			}else{
				$detritalmineralpasses="yes";
			}
			
			$detritalmethodpasses="no";
			if($detritalmethod!=""){
				$thisdetritalmethod=$row->ecproject;
				if($thisdetritalmethod==$detritalmethod){
					$detritalmethodpasses="yes";
				}
			}else{
				$detritalmethodpasses="yes";
			}
			

			
			if($agepasses=="yes" && $detritalpasses=="yes" && $detritalmineralpasses=="yes" && $detritalmethodpasses=="yes" ){
				
				$oPointStyle->set( "symbolname", $row->pkey);
				if($hmysamples=="yes"){
					if($row->userpkey==$userpkey){
						$oPointStyle->color->setRGB(255,255,0);
					}else{
						$oPointStyle->color->setRGB(255,0,0);
					}
				}else{
					$oPointStyle->color->setRGB(255,0,0);
				}
				//$oPointStyle->set( "size", "9");






				$point = ms_newPointObj();
				$point->setXY($row->longitude,$row->latitude);
				$point->draw($oMap,$oLayerPoints,$oMapImage,0,'');

				if($label=="yes"){
					//label here
					$pt = ms_newPointObj();
					$pt->setXY( $row->longitude,$row->latitude , 0);
					$pt->draw($oMap, $snet, $oMapImage, 1, "$row->sample_id" );
					//$pt->free();
				}





			}else{
				
				$oPointStyle->set( "symbolname", $row->pkey);
				//$oPointStyle->color->setRGB(200,200,200);
				if($hmysamples=="yes"){
					if($row->userpkey==$userpkey){
						$oPointStyle->color->setRGB(255,255,0);
					}else{
						$oPointStyle->color->setRGB(200,200,200);
					}
				}else{
					$oPointStyle->color->setRGB(200,200,200);
				}
				//$oPointStyle->set( "size", "9");








				$point = ms_newPointObj();
				$point->setXY($row->longitude,$row->latitude);
				$point->draw($oMap,$oLayerPoints,$oMapImage,0,'');

				if($label=="yes"){
					//label here
					$pt = ms_newPointObj();
					$pt->setXY( $row->longitude,$row->latitude , 0);
					$pt->draw($oMap, $snet, $oMapImage, 1, "$row->sample_id" );
					//$pt->free();
				}

			





			}
			
		}
	
	}else{ //else not conc
	
		// Create a style object defining how to draw features
		$oPointStyle = ms_newStyleObj($oMapClass);

		//$oPointStyle->color->setRGB(250,0,0);
		$oPointStyle->outlinecolor->setRGB(0,0,0);
		$oPointStyle->set( "symbolname", "circle");
		$oPointStyle->set( "size", "9");
		$oPointStyle->color->setRGB(255,255,0);


		foreach($rows as $row){ //first grey
		   
			//check for set values
			$agepasses="no";
			if($agemin!=""||$agemax!=""){
				$thisminage=$row->age_min;
				$thismaxage=$row->age_max;
				
				if($agemin!=""){
					
					if($thisminage>=$agemin||$thismaxage>=$agemin){
						$agepasses="yes";	
					}else{
						$agepasses="no";
					}
										
				}

				if($agemax!=""){
					
					if($thisminage<=$agemax||$thismaxage<=$agemax){
						$agepasses="yes";	
					}else{
						$agepasses="no";
					}
										
				}
				
			}elseif($geoages!=""){
				//figure out geoages stuff here
				//grey geoages now ... initially set to no
				$rowminage=$row->age_min;
				$rowmaxage=$row->age_max;

				foreach($geoagearray as $geoage){
					$thisminage=$geoage[minage];
					$thismaxage=$geoage[maxage];
					//(age_min >= $thisminage or age_max >= $thisminage) and (age_min <= $thismaxage or age_max <= $thismaxage) 
					//if($thisage>=$thisminage && $thisage<=$thismaxage ){
					if(($rowminage >= $thisminage || $rowmaxage >= $thisminage) && ($rowminage <= $thismaxage || $rowmaxage <= $thismaxage)){
						$agepasses="yes";
					}
				}
			}else{
				$agepasses="yes";
			}
			
			$detritalpasses="no";
			if($detritaltype!=""){
				$thisdetritaltype=$row->detrital_type;
				if($thisdetritaltype==$detritaltype){
					$detritalpasses="yes";
				}
			}else{
				$detritalpasses="yes";
			}
			

			$detritalmineralpasses="no";
			if($detritalmineral!=""){
				$thisdetritalmineral=$row->material;
				if($thisdetritalmineral==$detritalmineral){
					$detritalmineralpasses="yes";
				}
			}else{
				$detritalmineralpasses="yes";
			}
			
			$detritalmethodpasses="no";
			if($detritalmethod!=""){
				$thisdetritalmethod=$row->ecproject;
				if($thisdetritalmethod==$detritalmethod){
					$detritalmethodpasses="yes";
				}
			}else{
				$detritalmethodpasses="yes";
			}

			//echo "agepasses $agepasses detritalpasses $detritalpasses detritalmineralpasses $detritalmineralpasses detritalmethodpasses $detritalmethodpasses";
			//exit();
			
			if($agepasses=="no" || $detritalpasses=="no" || $detritalmineralpasses=="no" || $detritalmethodpasses=="no" ){
				//$oPointStyle->color->setRGB(200,200,200);
				if($hmysamples=="yes"){
					if($row->userpkey==$userpkey){
						$oPointStyle->color->setRGB(255,255,0);
					}else{
						$oPointStyle->color->setRGB(200,200,200);
					}
				}else{
					$oPointStyle->color->setRGB(200,200,200);
				}





				/*
				$point = ms_newPointObj();
				$point->setXY($row->longitude,$row->latitude);
				$point->draw($oMap,$oLayerPoints,$oMapImage,0,'');
				*/


				$pt = ms_newPointObj();
				$pt->setXY( $row->longitude,$row->latitude , 0 );
				$pt->draw($oMap, $oLayerPoints, $oMapImage, 0, '' );
				//$pt->free();
				
				if($label=="yes"){
					//label here
					$pt = ms_newPointObj();
					$pt->setXY( $row->longitude,$row->latitude , 0);
					$pt->draw($oMap, $snet, $oMapImage, 1, "$row->sample_id" );
					//$pt->free();
				}





			}
		
		
			
		}
		
		
		
		foreach($rows as $row){ //then red
		   
			//check for set values
			$agepasses="no";
			if($agemin!=""||$agemax!=""){
				$thisminage=$row->age_min;
				$thismaxage=$row->age_max;
				
				if($agemin!=""){
					
					if($thisminage>=$agemin||$thismaxage>=$agemin){
						$agepasses="yes";	
					}else{
						$agepasses="no";
					}
										
				}

				if($agemax!=""){
					
					if($thisminage<=$agemax||$thismaxage<=$agemax){
						$agepasses="yes";	
					}else{
						$agepasses="no";
					}
										
				}
				
			}elseif($geoages!=""){
				//figure out geoages stuff here
				//grey geoages now ... initially set to no
				$rowminage=$row->age_min;
				$rowmaxage=$row->age_max;

				foreach($geoagearray as $geoage){
					$thisminage=$geoage[minage];
					$thismaxage=$geoage[maxage];
					//(age_min >= $thisminage or age_max >= $thisminage) and (age_min <= $thismaxage or age_max <= $thismaxage) 
					//if($thisage>=$thisminage && $thisage<=$thismaxage ){
					if(($rowminage >= $thisminage || $rowmaxage >= $thisminage) && ($rowminage <= $thismaxage || $rowmaxage <= $thismaxage)){
						$agepasses="yes";
					}
				}
			}else{
				$agepasses="yes";
			}
			
			$detritalpasses="no";
			if($detritaltype!=""){
				$thisdetritaltype=$row->detrital_type;
				if($thisdetritaltype==$detritaltype){
					$detritalpasses="yes";
				}
			}else{
				$detritalpasses="yes";
			}
			
			$detritalmineralpasses="no";
			if($detritalmineral!=""){
				$thisdetritalmineral=$row->material;
				if($thisdetritalmineral==$detritalmineral){
					$detritalmineralpasses="yes";
				}
			}else{
				$detritalmineralpasses="yes";
			}
			
			$detritalmethodpasses="no";
			if($detritalmethod!=""){
				$thisdetritalmethod=$row->ecproject;
				if($thisdetritalmethod==$detritalmethod){
					$detritalmethodpasses="yes";
				}
			}else{
				$detritalmethodpasses="yes";
			}
			
			if($agepasses=="yes" && $detritalpasses=="yes" && $detritalmineralpasses=="yes" && $detritalmethodpasses=="yes" ){
				//$oPointStyle->color->setRGB(255,0,0);

				if($hmysamples=="yes"){
					if($row->userpkey==$userpkey){
						$oPointStyle->color->setRGB(255,255,0);
					}else{
						$oPointStyle->color->setRGB(255,0,0);
					}
				}else{
					$oPointStyle->color->setRGB(255,0,0);
				}
				
				//$oPointStyle->color->setRGB(255,0,255);
				
				//$point = ms_newPointObj();
				//$point = ms_newPointObj();
				
				//$point->setXY($row->longitude,$row->latitude);
				//$point->draw($oMap,$oLayerPoints,$oMapImage,0,'');

				
				
				/*
				$oCoordList = ms_newLineObj();
				$oPointShape = ms_newShapeObj(MS_SHAPE_POINT);
				$oPointShape->set("text","foo");
				$oCoordList->addXY($row->longitude,$row->latitude);
				$oPointShape->add($oCoordList);
				$oLayerPoints->addFeature($oPointShape);
				*/



				//$mylayer = $gpoMap->getLayerByName("credits");
				//$txtp = ms_newPointObj();
				//$txtp->setXY( $row->longitude,$row->latitude );
				//$txtp->draw($myMap,$mylayer,$img,"credits","MYSTRING");
				//$txtp->draw($oMap,$oLayerPoints,$oMapImage,0,"MYSTRING");
				
				
				$pt = ms_newPointObj();
				$pt->setXY( $row->longitude,$row->latitude , 0 );
				$pt->draw($oMap, $oLayerPoints, $oMapImage, 0, '' );
				//$pt->free();
				
				if($label=="yes"){
					//label here
					$pt = ms_newPointObj();
					$pt->setXY( $row->longitude,$row->latitude , 0);
					$pt->draw($oMap, $snet, $oMapImage, 1, "$row->sample_id" );
					//$pt->free();
				}


				
			}
			
		}
		
	}
}

/*
   $point
 
 for ( $i=0; $i<3; $i++ ) {
   $meterStyle[$i] = $meterClass0->getStyle($i);
   $meterStyle[$i]->outlinecolor->setRGB($red[$i],$green[$i],$blue[$i]);
   $meterStyle[$i]->color->setRGB($red[$i],$green[$i],$blue[$i]);
   $meterStyle[$i]->set('offsetx',$i*3);
   $meterStyle[$i]->set('offsety',0);
 }
 $point = ms_newPointObj();
 $point->setXY((-1)*$row[1],$row[0]);
 $point->draw($map,$meterLayer,$image,0,$row[2]);
}

$image_url=$image->saveWebImage();
*/


// Save the map to an image file

// Render the map into an image object
//$oMapImage = $oMap->draw();

$oMap->drawLabelCache($oMapImage);

header('Content-type: image/png');

$oMapImage->saveImage("");

unset($oMap);

unlink($myFile);

/*
echo "<img src=\"multicolor.gif\"></img>";
*/
?>
