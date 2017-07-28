<?
include("../db.php");
header("Content-type: text/xml"); 
//this is a dynamic xslt file for ZIPS data since the spot information varies from sample to sample

$pkey=$_GET['pkey'];


$headers[0][1]="agemapb206u238";
$headers[1][1]="agemapb206u2381se";
$headers[2][1]="agemapb207u235";
$headers[3][1]="agemapb207u2351se";
$headers[4][1]="agemapb207pb206";
$headers[5][1]="agemapb207pb2061se";
$headers[6][1]="correlationofconcordiaellipses";
$headers[7][1]="pb206ru238";
$headers[8][1]="pb206ru2381se";
$headers[9][1]="pb207ru235";
$headers[10][1]="pb207ru2351se";
$headers[11][1]="pb207rpb206r";
$headers[12][1]="pb207rpb206r1se";
$headers[13][1]="pctradiogenicpb206";
$headers[14][1]="pctradiogenicpb2061se";
$headers[15][1]="pctradiogenicpb207";
$headers[16][1]="pctradiogenicpb2071se";
$headers[17][1]="pb206u238";
$headers[18][1]="pb206u2381se";
$headers[19][1]="pb207u235";
$headers[20][1]="pb207u2351se";
$headers[21][1]="pb207pb206";
$headers[22][1]="pb207pb2061se";
$headers[23][1]="th";
$headers[24][1]="th1se";
$headers[25][1]="u";
$headers[26][1]="u1se";
$headers[27][1]="th94zr2o";
$headers[28][1]="th94zr2o1se";
$headers[29][1]="u94zr2o";
$headers[30][1]="u94zr2o1se";
$headers[31][1]="agemapb208th232";
$headers[32][1]="agemapb208th2321se";
$headers[33][1]="pb208th232";
$headers[34][1]="pb208th2321se";
$headers[35][1]="pctradiogenicpb208";
$headers[36][1]="pctradiogenicpb2081se";
$headers[37][1]="commonpb206pb204";
$headers[38][1]="commonpb207pb204";
$headers[39][1]="commonpb208pb204";
$headers[40][1]="pbcorr";
//$headers[41][1]="blksize";
$headers[42][1]="name";
//$headers[43][1]="rejected";

$headers[0][0]="Age (Ma) 206Pb/ 238U";
$headers[1][0]="Age (Ma) 206Pb/ 238U 1 s.e.";
$headers[2][0]="Age (Ma) 207Pb/ 235U";
$headers[3][0]="Age (Ma) 207Pb/ 235U 1 s.e.";
$headers[4][0]="Age (Ma) 207Pb/ 206Pb";
$headers[5][0]="Age (Ma) 207Pb/ 206Pb 1 s.e.";
$headers[6][0]="Correlation of Concordia Ellipses";
$headers[7][0]="206Pb*/ 238U";
$headers[8][0]="206Pb*/ 238U 1 s.e.";
$headers[9][0]="207Pb*/ 235U";
$headers[10][0]="207Pb*/ 235U 1 s.e.";
$headers[11][0]="207Pb*/ 206Pb*";
$headers[12][0]="207Pb*/ 206Pb* 1 s.e.";
$headers[13][0]="% Radiogenic 206Pb";
$headers[14][0]="% Radiogenic 206Pb 1 s.e.";
$headers[15][0]="% Radiogenic 207Pb";
$headers[16][0]="% Radiogenic 207Pb 1 s.e.";
$headers[17][0]="206Pb/ 238U";
$headers[18][0]="206Pb/ 238U 1 s.e.";
$headers[19][0]="207Pb/ 235U";
$headers[20][0]="207Pb/ 235U 1 s.e.";
$headers[21][0]="207Pb/ 206Pb";
$headers[22][0]="207Pb/ 206Pb 1 s.e.";
$headers[23][0]="Th";
$headers[24][0]="Th 1 s.e.";
$headers[25][0]="U";
$headers[26][0]="U 1 s.e.";
$headers[27][0]="Th/ 94Zr2 O";
$headers[28][0]="Th/ 94Zr2 O 1 s.e.";
$headers[29][0]="U/ 94Zr2 O";
$headers[30][0]="U/ 94Zr2 O 1 s.e.";
$headers[31][0]="Age (Ma) 208Pb/ 232Th";
$headers[32][0]="Age (Ma) 208Pb/ 232Th 1 s.e.";
$headers[33][0]="208Pb/ 232Th";
$headers[34][0]="208Pb/ 232Th 1 s.e.";
$headers[35][0]="% Radiogenic 208Pb";
$headers[36][0]="% Radiogenic 208Pb 1 s.e.";
$headers[37][0]="Common 206Pb/ 204Pb";
$headers[38][0]="Common 207Pb/ 204Pb";
$headers[39][0]="Common 208Pb/ 204Pb";
$headers[40][0]="Pb corr.";
//$headers[41][0]="Blk Size";
$headers[42][0]="Name";
//$headers[43][0]="Rejected";

$sample_pkey=$db->get_var("select sample_pkey from sample where filename='$pkey.xml'");

//echo $sample_pkey;

$xml = simplexml_load_file("files/$pkey.xml");

$spot=$xml->spots->spot[0];



echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
?>
<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:n="http://www.geosamples.org"
                >

<xsl:import href="http://www.geochronportal.org/EarthTimeOrgCommonTransforms.xslt"/>

<xsl:output method="html"/>

<xsl:template match="sample">
<xsl:apply-templates select="sampleinfo"/>
<xsl:apply-templates select="samplemetadata"/>
<xsl:apply-templates select="ages"/>
<xsl:apply-templates select="spots"/>
<? if(file_exists("uploadimages/".$sample_pkey.".jpg")){ ?>
<br/><div class="aboutpage"><div class="headline">Sample Image</div><img src="uploadimages/<?=$sample_pkey?>.jpg"/></div>
<? } ?>
</xsl:template>

<xsl:template match="sampleinfo">
<div class="aboutpage"><div class="headline">Sample Details</div></div>
<div class="box_two">
Sample Name: <xsl:value-of select="samplename"/><br/>
IGSN: <xsl:value-of select="uniqueid"/><br/>
Laboratory: <xsl:value-of select="labname"/><br/>
Analyst: <xsl:value-of select="analystname"/><br/>
Instrumental Method: <xsl:value-of select="instrumentalmethod"/><br/>
Instrumental Method Reference: <xsl:value-of select="instrumentalmethodreference"/><br/>
Mineral: <xsl:value-of select="mineral"/><br/>
Comment: <xsl:value-of select="comment"/><br/>
238U Decay Constant: <xsl:value-of select="udecayconstant238"/><br/>
238U Decay Constant Error: <xsl:value-of select="udecayconstanterror238"/><br/>
235U Decay Constant: <xsl:value-of select="udecayconstant235"/><br/>
235U Decay Constant Error: <xsl:value-of select="udecayconstanterror235"/><br/>
232Th Decay Constant : <xsl:value-of select="thdecayconstant232"/><br/>
232Th Decay Constant Error: <xsl:value-of select="thdecayconstanterror232"/><br/>
230Th Decay Constant: <xsl:value-of select="thdecayconstant230"/><br/>
230Th Decay Constant Error: <xsl:value-of select="thdecayconstanterror230"/><br/>
235U/238U: <xsl:value-of select="u235u238"/><br/>
Decay Constant Reference: <xsl:value-of select="decayconstantreference"/><br/>
Decay Comment: <xsl:value-of select="decaycomment"/><br/>
</div><br/>
</xsl:template>

<xsl:template match="samplemetadata">
<div class="aboutpage"><div class="headline">Sample Metadata</div></div>
<div class="box_two">
Sample ID: <xsl:value-of select="sampleid"/><br/>
Sample Description: <xsl:value-of select="sampledescription"/><br/>
GeoObject Type: <xsl:value-of select="geoobjecttype"/><br/>
Geoobject Classification: <xsl:value-of select="geoobjectclassification"/><br/>
Collection Method: <xsl:value-of select="collectionmethod"/><br/>
Material: <xsl:value-of select="material"/><br/>
Latitude: <xsl:value-of select="latitude"/><br/>
Longitude: <xsl:value-of select="longitude"/><br/>
Sample Comment: <xsl:value-of select="samplecomment"/><br/>
Collector: <xsl:value-of select="collector"/><br/>
Material Classification: <xsl:value-of select="materialclassification"/><br/>
Primary Location Name: <xsl:value-of select="PrimaryLocationName"/><br/>
Primary Location Type: <xsl:value-of select="PrimaryLocationType"/><br/>
Location Description: <xsl:value-of select="LocationDescription"/><br/>
Locality: <xsl:value-of select="Locality"/><br/>
Locality Description: <xsl:value-of select="LocalityDescription"/><br/>
Country: <xsl:value-of select="Country"/><br/>
Provice: <xsl:value-of select="Provice"/><br/>
County: <xsl:value-of select="County"/><br/>
City Or Township: <xsl:value-of select="CityOrTownship"/><br/>
Platform: <xsl:value-of select="Platform"/><br/>
Platform ID: <xsl:value-of select="PlatformID"/><br/>
Original Archival Institution: <xsl:value-of select="OriginalArchivalInstitution"/><br/>
Original Archival Contact: <xsl:value-of select="OriginalArchivalContact"/><br/>
Most Recent Archival Institution: <xsl:value-of select="MostRecentArchivalInstitution"/><br/>
Most Recent Archival Contact: <xsl:value-of select="MostRecentArchivalContact"/><br/>
</div>
</xsl:template>

<xsl:template match="ages">
<br/>
<div class="aboutpage"><div class="headline">Age(s)</div></div>
<div class="box_two">
	<table class="sample">
	<tr>
	<th>Analysis Purpose</th>
	<th>Value</th>
	<th>Error</th>
	<th>Age Type</th>
	<th>MSWD</th>
	<th>Age Error Systematic</th>
	<th>Included Analyses</th>
	<th>Comment</th>
	<th>Common Lead Correction</th>
	</tr>
	<xsl:apply-templates select="age"/>
	</table>
</div>
</xsl:template>


<xsl:template match="age">
	<tr>
	<td><xsl:value-of select="@analysispurpose"/></td>
	<td><xsl:value-of select="@value"/></td>
	<td><xsl:value-of select="@error"/></td>
	<td><xsl:value-of select="@type"/></td>
	<td><xsl:value-of select="@mswd"/></td>
	<td><xsl:value-of select="@ageerrorsystematic"/></td>
	<td><xsl:value-of select="@preferredageincludedanalyses"/></td>
	<td><xsl:value-of select="@preferredageexplanation"/></td>
	<td><xsl:value-of select="@commonleadcorrection"/></td>
	</tr>
</xsl:template>

<xsl:template match="spots">
<br/>
<div class="aboutpage"><div class="headline">Spot(s)</div></div>
<div class="box_two">
	<table class="sample">
	<tr>
<?

foreach($spot as $key=>$value){
	
	$showstring="";
	
	foreach($headers as $h){
	
		if($h[1]==$key){
			$showstring=$h[0];
		}
	
	}
	
	if($showstring!=""){
		echo "		<th>$showstring</th>\n";
	}
}

?>
	</tr>
	<xsl:apply-templates select="spot"/>
	</table>
</div>
</xsl:template>

<xsl:template match="spot">
	<tr>
<?
foreach($spot as $key=>$value){

	if($key!="blksize" && $key != "rejected"){
		echo "		<td><xsl:attribute name=\"nowrap\" /><xsl:value-of select=\"$key\"/></td>\n";
	}

}
?>
	</tr>
</xsl:template>

</xsl:stylesheet>