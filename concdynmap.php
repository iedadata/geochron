<?PHP
/**
 * concdynmap.php
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

/*
  SYMBOL
    NAME 'mygif'
    TYPE pixmap
    IMAGE 'doug2.gif' 
  END
*/
//var_dump($_GET);

// ex1_map_basic.php
// Tyler Mitchell, August 2005
// Build a map using a pre-made map file

// Load MapScript extension
if (!extension_loaded("MapScript"))
  dl('php_mapscript.'.PHP_SHLIB_SUFFIX);

include("db.php");



$mapstring="select lon as longitude,
lat as latitude, age, detritaltype from testdata limit 500
";


$agemin=$_GET['agemin'];
$agemax=$_GET['agemax'];
$detritaltype=$_GET['detritaltype'];
$conc=$_GET['conc'];


$showall=$_GET['showall'];


if($showall=="no"){

	$mapstring="select lon as longitude,
	lat as latitude, age, detritaltype from testdata where 1=1 ";
	
	if($agemin!="" && $agemax!=""){
		$mapstring.="and age >= $agemin and age <= $agemax ";
	}
	
	if($detritaltype!=""){
		$mapstring.="and detritaltype='$detritaltype' ";
	}
	
	$mapstring.=" limit 500";

}else{

	$mapstring="select lon as longitude,
	lat as latitude, age, detritaltype from testdata limit 500
	";

}




$rows=$db->get_results("$mapstring");


// Create a map object.
//$oMap = ms_newMapObj("ex4_map_points.map");
$oMap = ms_newMapObj("dynmap.map");

$oMap->setSize(400,400);

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


// Create another layer to hold point locations
$oLayerPoints = ms_newLayerObj($oMap);
$oLayerPoints->set( "name", "custom_points");
$oLayerPoints->set( "type", MS_LAYER_POINT);
$oLayerPoints->set( "status", MS_DEFAULT);
//$oLayerPoints->set("transparency", 20);



// Render the map into an image object
$oMapImage = $oMap->draw();

$oMapClass = ms_newClassObj($oLayerPoints);

$oMapClass->label->set( "position", MS_AUTO);
$oMapClass->label->set( "size", 15);
$oMapClass->label->color->setRGB(250,0,0);
$oMapClass->label->outlinecolor->setRGB(255,255,255);



srand(time());


if(count($rows)>0){
	//roll over results twice,
	//first show the grey ones, then the red ones,
	//to preserve visibilit of red which is most
	//important

	if($conc=="yes"){

		//create a new Symbol Object here... we can use a style later to change it a bit
		$nSymbolId = ms_newSymbolObj($oMap, "mygif");
		$oSymbol = $oMap->getsymbolobjectbyid($nSymbolId);
		$oSymbol->set("type", MS_SYMBOL_PIXMAP);
		$oSymbol->set("filled", MS_TRUE);
		$aPoints[0] = 1;
		$aPoints[1] = 1;
		$oSymbol->setpoints($aPoints);

		// Create a style object defining how to draw features
		$oPointStyle = ms_newStyleObj($oMapClass);
		//$oPointStyle->color->setRGB(250,0,0);
		$oPointStyle->outlinecolor->setRGB(0,0,0);
		$oPointStyle->set( "symbolname", "circle");
		$oPointStyle->set( "size", "9");
		$oPointStyle->color->setRGB(255,255,0);

		$oPointStyle = ms_newStyleObj($oMapClass);
		$oPointStyle->outlinecolor->setRGB(0,0,0);
		$oPointStyle->set( "symbolname", "mygif"); //this comes from the symbol object we defined above.


		foreach($rows as $row){ //then red
		   
			//check for set values
			$agepasses="no";
			if($agemin!=""){
				$thisage=$row->age;
				if($thisage>=$agemin && $thisage<=$agemax){
					$agepasses="yes";
				}
			}else{
				$agepasses="yes";
			}
			
			$detritalpasses="no";
			if($detritaltype!=""){
				$thisdetritaltype=$row->detritaltype;
				if($thisdetritaltype==$detritaltype){
					$detritalpasses="yes";
				}
			}else{
				$detritalpasses="yes";
			}
			
		
			
			if($agepasses=="yes" && $detritalpasses=="yes"){
				
				$oPointStyle->color->setRGB(255,255,0);
				$point = ms_newPointObj();
				$point->setXY($row->longitude,$row->latitude);
				$point->draw($oMap,$oLayerPoints,$oMapImage,0,'');
			
			}
			
		}
	
	}else{
	
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
			if($agemin!=""){
				$thisage=$row->age;
				if($thisage>=$agemin && $thisage<=$agemax){
					$agepasses="yes";
				}
			}else{
				$agepasses="yes";
			}
			
			$detritalpasses="no";
			if($detritaltype!=""){
				$thisdetritaltype=$row->detritaltype;
				if($thisdetritaltype==$detritaltype){
					$detritalpasses="yes";
				}
			}else{
				$detritalpasses="yes";
			}
			
		
			
			if($agepasses=="no" || $detritalpasses=="no"){
				$oPointStyle->color->setRGB(200,200,200);
				$point = ms_newPointObj();
				$point->setXY($row->longitude,$row->latitude);
				$point->draw($oMap,$oLayerPoints,$oMapImage,0,'');
			}
		
		
			
		}
		
		
		
		foreach($rows as $row){ //then red
		   
			//check for set values
			$agepasses="no";
			if($agemin!=""){
				$thisage=$row->age;
				if($thisage>=$agemin && $thisage<=$agemax){
					$agepasses="yes";
				}
			}else{
				$agepasses="yes";
			}
			
			$detritalpasses="no";
			if($detritaltype!=""){
				$thisdetritaltype=$row->detritaltype;
				if($thisdetritaltype==$detritaltype){
					$detritalpasses="yes";
				}
			}else{
				$detritalpasses="yes";
			}
			
		
			
			if($agepasses=="yes" && $detritalpasses=="yes"){
				$oPointStyle->color->setRGB(255,0,0);
				$point = ms_newPointObj();
				$point->setXY($row->longitude,$row->latitude);
				$point->draw($oMap,$oLayerPoints,$oMapImage,0,'');
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

header('Content-type: image/png');

$oMapImage->saveImage("");

unset($oMap);


/*
echo "<img src=\"multicolor.gif\"></img>";
*/
?>
