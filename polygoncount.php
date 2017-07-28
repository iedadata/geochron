<?PHP
/**
 * polygoncount.php
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

//polygonsearch.php - populates landing page with polygon search
//

include("db.php");

//polygonsearch.php
//accepts a polygon $_GET var and starts a new search with polygon populated

function polygonisvalid($polygon){
	$isvalid=true;
	
	$parts=explode(",",$polygon);
	foreach($parts as $part){
		$part=trim($part);
		$bits = explode(" ",$part);
		if(count($bits)!=2){
			$isvalid=false;
		}
		if(!is_numeric($bits[0])){$isvalid=false;}
		if(!is_numeric($bits[1])){$isvalid=false;}

	}
	
	if(count($parts)>2){
		$lastpart = array_pop($parts);
		//echo "lastpart: ***".trim($lastpart)."*** parts0: ***".trim($parts[0])."***";
		if(trim($parts[0])!=trim($lastpart)){$isvalid=false;}
	}else{
		$isvalid=false;
	}
	
	return($isvalid);
}

header ("Content-Type:text/xml");

$polygon=$_GET['polygon'];

if($polygon==""){echo "<results>\n\t<error>Error. No polygon provided.</error>\n</results>";exit();}
if(!polygonisvalid($polygon)){echo "<results>\n\t<error>Invalid polygon provided.</error>\n</results>";exit();}


$srs=$_GET['srs'];

if($srs!=""){
if(!is_numeric($srs)){
	echo "<results>\n\t<error>Error. Invalid SRS provided.</error>\n</results>";exit();
}
}

if($srs!="" && $srs!="4326" && $srs!=4236){
	$iedapolygon = $polygon;
	$polygon=$db->get_var("SELECT ST_AsText(ST_Transform(ST_GeomFromText('POLYGON(($polygon))',$srs),4326)) as geom;");
	$polygon=str_replace("POLYGON((","",$polygon);
	$polygon=str_replace("))","",$polygon);
}


$newpoly=$db->get_var("select fixpoly('Multipolygon((($polygon)))')");
$polygon=explode(",",$polygon);
array_pop($polygon);
$polygon=implode("; ",$polygon);





$pkey=$db->get_var("select nextval('search_query_seq')");
$db->query("insert into search_query (search_query_pkey,coordinates,iedapolygon,srs) values ($pkey,'$polygon','$iedapolygon','$srs')");

include("buildquery.php");

$querystring=$newquerystring;

$totalcount=$db->get_var("select count(*) from ($querystring) foo");

echo "<results>\n";
echo "\t<count name=\"total\" label=\"Total\" value =\"$totalcount\"/>\n";
echo "</results>";

?>