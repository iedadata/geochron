<?PHP
/**
 * getlevel4.php
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

include('db.php'); // load database drivers, and connect
$level1=$_GET['level1'];
$level2=$_GET['level2'];
$level3=$_GET['level3'];
$getrocknames = $db->get_results("
	
	select distinct upper(data4) as level4 
	from ech 
	where upper(data1)='".$level1."' 
	and upper(data2)='".$level2."' 
	and upper(data3)='".$level3."' 
	and data4 != '' 
	and data4 != 'unknown' 
	and data4 != 'not-given' 
	and data4 != 'not given' order by level4
	
	");

$xmlrockname="";

if($db->num_rows > 0){
	foreach($getrocknames as $g) {
	$xmlrockname .= "
	<rockname>
		<name>$g->level4</name>
	</rockname>
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
<CFOUTPUT>
<CFQUERY name="getrocknames" datasource="#datasource#">
select distinct upper(ec_level_4) level2 from rocknames where upper(ec_level_1)='#url.level1#' and upper(ec_level_2)='#url.level2#' and upper(ec_level_3)='#url.level3#' and ec_level_4 != 'unknown' and ec_level_4 != 'not-given'
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
</CFOUTPUT>
*/
?>