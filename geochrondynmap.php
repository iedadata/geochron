<?PHP
/**
 * geochrondynmap.php
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


//var_dump($_GET);

// ex1_map_basic.php
// Tyler Mitchell, August 2005
// Build a map using a pre-made map file

// Load MapScript extension
if (!extension_loaded("MapScript"))
  dl('php_mapscript.'.PHP_SHLIB_SUFFIX);

session_start();
include("db.php");



// **************** get username stuff here for the search ***************
if($_SESSION['username']!=""){
	$username=$_SESSION['username'];
	$userrow=$db->get_row("select * from users where username='$username'");
	$group=$userrow->usergroup;
	$grouparray=$userrow->grouparray;
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

if($grouparray==""){
	$grouparray=99999;
}

if($group==0 or $group==""){
	$group=99999;
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

//echo $userpkey."<br>";


$pkey=$_GET['pkey'];

$mapstring=$db->get_var("select querystring from search_query where search_query_pkey=$pkey");

//echo $mapstring;

//exit();

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

/* ****************************************************************************
foreach ($rows as $row)
{
   //$aPointArray = explode(",",$sPointItem);
   // :TRICKY: Note although we are using points
   // we must use a line object (newLineObj)
   // here I call it a CoordList object for simplicity 
   $oCoordList = ms_newLineObj();
   $oPointShape = ms_newShapeObj(MS_SHAPE_POINT);
   $oCoordList->addXY($row->longitude,$row->latitude);
   $oPointShape->add($oCoordList);
   //$oPointShape->set( "text", chop($aPointArray[2]));
   $oLayerPoints->addFeature($oPointShape);
}

// Create a class object to set feature drawing styles.
$oMapClass = ms_newClassObj($oLayerPoints);

// Create a style object defining how to draw features
$oPointStyle = ms_newStyleObj($oMapClass);
$oPointStyle->color->setRGB(250,0,0);
$oPointStyle->outlinecolor->setRGB(0,0,0);
$oPointStyle->set( "symbolname", "circle");
$oPointStyle->set( "size", "7");

// Create label settings for drawing text labels
//$oMapClass->label->set( "position", MS_AUTO);
//$oMapClass->label->color->setRGB(250,0,0);
//$oMapClass->label->outlinecolor->setRGB(255,255,255);

********************************************************************************* */

// Render the map into an image object
$oMapImage = $oMap->draw();

$oMapClass = ms_newClassObj($oLayerPoints);

$oMapClass->label->set( "position", MS_AUTO);
$oMapClass->label->set( "size", 15);
$oMapClass->label->color->setRGB(250,0,0);
$oMapClass->label->outlinecolor->setRGB(255,255,255);

// Create a style object defining how to draw features
$oPointStyle = ms_newStyleObj($oMapClass);
//$oPointStyle->color->setRGB(250,0,0);
$oPointStyle->outlinecolor->setRGB(0,0,0);
$oPointStyle->set( "symbolname", "circle");
$oPointStyle->set( "size", "9");
//$oPointStyle->color->setRGB(255,255,0);

$snet = $oMap->getlayerbyname("snet");
$snet->set("status",MS_ON);

srand(time());



foreach($rows as $row){
   
	/*
	$random1 = (rand()%255);
	$random2 = (rand()%255);
	$random3 = (rand()%255);
	$oPointStyle->color->setRGB($random1,$random2,$random3);
	*/
	
	if($row->userpkey == $userpkey){
		$oPointStyle->color->setRGB(255,255,0);
	}else{
		$oPointStyle->color->setRGB(255,0,0);
	}
	
	$point = ms_newPointObj();
	$point->setXY($row->longitude,$row->latitude);
	$point->draw($oMap,$oLayerPoints,$oMapImage,0,'');
	

	$pt = ms_newPointObj();
	$pt->setXY( $row->longitude,$row->latitude , 0);
	$pt->draw($oMap, $snet, $oMapImage, 1, "$row->sample_id" );
	$pt->free();


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

$oMap->drawLabelCache($oMapImage);

header('Content-type: image/png');

$oMapImage->saveImage("");

unset($oMap);


/*
echo "<img src=\"multicolor.gif\"></img>";
*/
?>
