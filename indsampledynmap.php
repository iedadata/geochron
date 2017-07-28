<?PHP
/**
 * indsampledynmap.php
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

//include("logincheck.php");

$showfig=$_GET['showfig'];
$zoom=$_GET['zoom'];

$label=$_GET['label'];

//sample_pkey
$lat=$_GET['lat'];
$lon=$_GET['lon'];


$querystring=$newquerystring;

//create map file in /dev/shm here
$filename=time().rand(99999,999999).".map";
//echo "filename: $filename";


$oMap = ms_newMapObj("/public/mgg/web/www.geochron.org/htdocs/dynmap.map");



$oMap->setSize(600,400);

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



	
// Create a style object defining how to draw features
$oPointStyle = ms_newStyleObj($oMapClass);

//$oPointStyle->color->setRGB(250,0,0);
$oPointStyle->outlinecolor->setRGB(0,0,0);
$oPointStyle->set( "symbolname", "circle");
$oPointStyle->set( "size", "9");
$oPointStyle->color->setRGB(255,255,0);

$oPointStyle->color->setRGB(255,0,0);
		
		
$pt = ms_newPointObj();
$pt->setXY( $lon,$lat , 0 );
$pt->draw($oMap, $oLayerPoints, $oMapImage, 0, '' );
//$pt->free();

if($label=="yes"){
	//label here
	$pt = ms_newPointObj();
	$pt->setXY( $row->longitude,$row->latitude , 0);
	$pt->draw($oMap, $snet, $oMapImage, 1, "$row->sample_id" );
	//$pt->free();
}




$oMap->drawLabelCache($oMapImage);

header('Content-type: image/png');

$oMapImage->saveImage("");

unset($oMap);

unlink($myFile);

/*
echo "<img src=\"multicolor.gif\"></img>";
*/
?>
