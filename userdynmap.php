<?PHP
/**
 * userdynmap.php
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

include("logincheck.php");

$showfig=$_GET['showfig'];
$zoom=$_GET['zoom'];

$label=$_GET['label'];


$querystring=$newquerystring;

//echo nl2br($querystring)." <br><br>";exit();

$totalcount=$db->get_var("select count(*) 
								from 
								(select 
								samp.sample_pkey
								from 
								sample samp
								left join groupsamplerelate gsr on gsr.sample_pkey = samp.sample_pkey
								left join grouprelate grp on grp.group_pkey = gsr.group_pkey
								left join groups on grp.group_pkey = groups.group_pkey
								left join datasetrelate dr on dr.sample_pkey = samp.sample_pkey
								left join datasetuserrelate dur on dur.dataset_pkey = dr.dataset_pkey
								left join datasets ds on dr.dataset_pkey = ds.dataset_pkey
								where (samp.userpkey=$userpkey or ((dur.users_pkey=$userpkey and dur.confirmed=true) or ds.users_pkey=$userpkey ) or ((grp.users_pkey = $userpkey and grp.confirmed=true) or groups.users_pkey=$userpkey)) and del=0 
								group by
								samp.sample_pkey) foo");




$mapstring="select 
			samp.sample_pkey, 
			samp.sample_id,
			samp.igsn,
			samp.laboratoryname, 
			samp.analyst_name,
			samp.ecproject,
			samp.latitude,
			samp.longitude,
			samp.userpkey,
			samp.material,
			samp.filename,

			getagetypes(samp.sample_pkey) as agetypes
			from 
			sample samp
			left join groupsamplerelate gsr on gsr.sample_pkey = samp.sample_pkey
			left join grouprelate grp on grp.group_pkey = gsr.group_pkey
			left join groups on grp.group_pkey = groups.group_pkey
			left join datasetrelate dr on dr.sample_pkey = samp.sample_pkey
			left join datasetuserrelate dur on dur.dataset_pkey = dr.dataset_pkey
			left join datasets ds on dr.dataset_pkey = ds.dataset_pkey
			where (samp.userpkey=$userpkey or ((dur.users_pkey=$userpkey and dur.confirmed=true) or ds.users_pkey=$userpkey ) or ((grp.users_pkey = $userpkey and grp.confirmed=true) or groups.users_pkey=$userpkey)) and del=0 
			group by
			samp.sample_pkey, 
			sample_id,
			igsn,
			laboratoryname, 
			analyst_name,
			ecproject,
			latitude,
			longitude,
			userpkey,
			material,
			filename,

			agetypes";







//echo $mapstring; exit();


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
	
	//IMAGE '/var/www/geochron/concordias/$filefolder/".$row->sample_pkey.".jpg' 
	
	foreach($rows as $row){
if(file_exists("/public/mgg/web/www.geochron.org/htdocs/concordias/$filefolder/".$row->sample_pkey.".gif")){
$filestring.="
  SYMBOL
    NAME '".$row->sample_pkey."'
    TYPE pixmap
    IMAGE '/public/mgg/web/www.geochron.org/htdocs/concordias/$filefolder/".$row->sample_pkey.".gif' 
  END
";
}else{
$filestring.="
  SYMBOL
    NAME '".$row->sample_pkey."'
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

	//echo $filestring;exit();

	$myFile = "mapfiles/$filename";
	$fh = fopen($myFile, 'w') or die("can't open file");
	fwrite($fh, $filestring);
	fclose($fh);
	
	// Create a map object.
	//$oMap = ms_newMapObj("ex4_map_points.map");
	$oMap = ms_newMapObj("/public/mgg/web/www.geochron.org/htdocs/mapfiles/$filename");
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
	
	//IMAGE '/var/www/geochron/concordias/$filefolder/".$row->sample_pkey.".jpg' 
	
	foreach($rows as $row){
if(file_exists("/public/mgg/web/www.geochron.org/htdocs/probabilities/$filefolder/".$row->sample_pkey.".gif")){
$filestring.="
  SYMBOL
    NAME '".$row->sample_pkey."'
    TYPE pixmap
    IMAGE '/public/mgg/web/www.geochron.org/htdocs/probabilities/$filefolder/".$row->sample_pkey.".gif' 
  END
";
}else{
$filestring.="
  SYMBOL
    NAME '".$row->sample_pkey."'
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


	$myFile = "mapfiles/$filename";
	$fh = fopen($myFile, 'w') or die("can't open file");
	fwrite($fh, $filestring);
	fclose($fh);
	
	// Create a map object.
	//$oMap = ms_newMapObj("ex4_map_points.map");
	$oMap = ms_newMapObj("/public/mgg/web/www.geochron.org/htdocs/mapfiles/$filename");
}else{ //end if showfig==conc or prob

	$oMap = ms_newMapObj("/public/mgg/web/www.geochron.org/htdocs/dynmap.map");

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
			$nSymbolId = ms_newSymbolObj($oMap, $row->sample_pkey);
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
				
				$oPointStyle->set( "symbolname", $row->sample_pkey);
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
				
				$oPointStyle->set( "symbolname", $row->sample_pkey);
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


					if($row->userpkey==$userpkey){
						$oPointStyle->color->setRGB(255,0,0);
					}else{
						$oPointStyle->color->setRGB(255,255,0);
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
