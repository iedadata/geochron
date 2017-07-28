<?PHP
/**
 * getagetypes.php
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

include("db.php");
$projects = $_GET['project'];

$projects=split(",",$projects);
$projectdelim="";
foreach($projects as $project){
	$projectlist.=$projectdelim."'$project'";
	$projectdelim=',';
}

$getrocknames = $db->get_results("select * from agetypes where project in (".$projectlist.")");

//echo("select typename as agetype from agetypes where project in (".$projectlist.")");


//echo("select typename as agetype from agetypes where project='".$project."'");
//exit();

$xmlrockname="";

if($db->num_rows > 0){
	foreach($getrocknames as $g) {

	$myproject="";

	switch($g->project){
		case "redux": $showproject="U-Pb"; break;
		case "arar": $showproject="Ar-Ar"; break;
		case "helios": $showproject="(U-Th)/He"; break;
		default: $showproject="";
	}


	$xmlrockname .= "
	<agetype>
		<typename>$showproject: $g->typename</typename>
		<typevalue>$g->typename</typevalue>
	</agetype>
	";
	}
}


$ajaxresponse = "
<ajaxresponse>
$xmlrockname
</ajaxresponse>
";

echo $ajaxresponse;

/*
<CFQUERY name="getrocknames" datasource="#datasource#">
select distinct upper(ec_level_2) level2 from rocknames where upper(ec_level_1)='#url.level1#' and ec_level_2 != 'unknown'
</CFQUERY>
<cfsavecontent variable="ajaxresponse">
<ajaxresponse>
</ajaxresponse>
</cfsavecontent>
<cfsavecontent variable="rockname">
	<rockname>
		<name></name>
	</rockname>
</cfsavecontent>
<cfset xmlajaxresponse = XmlParse(Trim(ajaxresponse)) >
<CFLOOP QUERY="getrocknames">
<cfset xmlrockname = XmlParse(Trim(rockname)) >
<cfset xmlrockname.rockname.name.XmlText = "#level2#" >
<cfset xmlajaxresponse.ajaxresponse.appendChild(xmlajaxresponse.importNode(xmlrockname.rockname.cloneNode(True), True)) >
</CFLOOP>
<cfcontent type="text/xml; charset=iso-8859-1">
#ToString(xmlajaxresponse)#
*/
