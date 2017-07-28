<?PHP
/**
 * search_service.php
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

header('Content-Type: text/xml');
/*
echo "posts:*******************************<br>";
if(count($_POST)>0){
	foreach($_POST as $key=>$value){
		echo "$key : $value<br>";
	}
}
echo "*************************************<br>";
*/

include("db.php");

$pkey=$db->get_var("select nextval('search_query_seq')");
$db->query("insert into search_query (search_query_pkey) values ($pkey)");

//$_POST['parentigsn']=$_POST['sampleigsn'];
//$_POST['igsn']=$_POST['aliquotigsn'];


include("buildquery.php");

//header('Content-Type: text/xml');
//header("Content-Disposition: attachment; filename=$origfilename");

//echo "query: $newquerystring";


$myrows=$db->get_results($newquerystring);

//echo "<Br><br>count:".count($myrows);

//exit();

//echo nl2br($newquerystring);
//exit();

//print_r($db);

$numrows=$db->num_rows;

//var_dump($db);

//exit();

if(count($myrows) > 0){

echo "<results>
	<count>$numrows</count>
";


foreach($myrows as $row){
echo "	<result>
		<igsn>$row->igsn</igsn>
		<laboratoryname>$row->laboratoryname</laboratoryname>
		<analystname>$row->analyst_name</analystname>
		<detailurl>http://www.geochron.org/numdata/$row->sample_pkey</detailurl>
		<downloadurl>http://www.geochron.org/numxmldata/$row->sample_pkey</downloadurl>
	</result>\n";
}

echo "</results>\n";

/*
<results>
	<count>#getsamples.recordcount#</count>
<cfloop query="getsamples">
	<result>
		<aliquotigsn>#aliquotigsn#</aliquotigsn>
		<sampleigsn>#sampleigsn#</sampleigsn>
		<labname>#laboratoryname#</labname>
		<analystname>#analystname#</analystname>
		<aliquotreference>#aliquotreference#</aliquotreference>
		<aliquotmethod>#aliquotinstmethod#</aliquotmethod>
		<detailurl>http://geoportal.kgs.ku.edu/earthchem/geochron/viewfile.cfm?filename=#aliquot_pkey#.xml</detailurl>
		<downloadurl>http://geoportal.kgs.ku.edu/earthchem/geochron/downloadfile.cfm?pkey=#aliquot_pkey#</downloadurl>
	</result>
</cfloop>
</results>
</cfoutput>
*/
}else{
echo "<results>
	<count>0</count>
</results>";
}
?>