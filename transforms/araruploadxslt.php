<?
include("../db.php");

$pkey=$_GET['pkey'];

$filename=$db->get_var("select filename from sample where sample_pkey = $pkey");

$dom = new DomDocument;
$xmlfile = "files/$filename";

if($dom->Load($xmlfile)){

	$showintensities="no";
	$intensities = $dom->getElementsByTagName("intensities");

	foreach($intensities as $intensity){

		$littleintensities = $intensity->getElementsByTagName("intensity");
		foreach($littleintensities as $littleintensity){
			$showintensities="yes";
		}

	}

	$showratios="no";
	$ratios = $dom->getElementsByTagName("ratios");

	foreach($ratios as $ratio){

		$littleratios = $ratio->getElementsByTagName("ratio");
		foreach($littleratios as $littleratio){
			$showratios="yes";
		}

	}

}

echo '<?xml version="1.0" encoding="utf-8"?>';
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
<xsl:apply-templates select="intensities"/>
<xsl:apply-templates select="ratios"/>
</xsl:template>

<xsl:template match="sampleinfo">
<div class="aboutpage"><div class="headline">Sample Details</div></div>
<div class="box_two">
Sample Name: <xsl:value-of select="samplename"/><br/>
IGSN: <xsl:value-of select="igsn"/><br/>
Laboratory: <xsl:value-of select="laboratory"/><br/>
Analyst: <xsl:value-of select="analyst"/><br/>
Sample Material: <xsl:value-of select="samplematerial"/><br/>
Sample Material Type: <xsl:value-of select="samplematerialtype"/><br/>
Instrumental Method: <xsl:value-of select="instrumentalmethod"/><br/>
Instrumental Method Reference: <xsl:value-of select="instrumentalmethodreference"/><br/>
Decay Constant 40Ar Total: <xsl:value-of select="decayconstant40artotal"/><br/>
Decay Constant 40Ar Total Sigma: <xsl:value-of select="decayconstant40artotalsigma"/><br/>
Decay Constant Reference: <xsl:value-of select="decayconstantreference"/><br/>
Standard Name: <xsl:value-of select="standardname"/><br/>
Standard Material: <xsl:value-of select="standardmaterial"/><br/>
Standard Age: <xsl:value-of select="standardage"/><br/>
J-Value: <xsl:value-of select="jvalue"/><br/>
J-Value Sigma: <xsl:value-of select="jvaluesigma"/><br/>
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
	<th>Experiments Included</th>
	<th>Preferred Age</th>
	<th>Preferred Age Sigma</th>
	<th>Preferred Age Sigma Internal</th>
	<th>Preferred Age Sigma External</th>
	<th>Preferred Age Type</th>
	<th>Preferred Age Classification</th>
	<th>Preferred Age Reference</th>
	<th>Preferred Age Description</th>
	</tr>
	<xsl:apply-templates select="age"/>
	</table>
</div>
</xsl:template>


<xsl:template match="age">
	<tr>
	<td><xsl:value-of select="@analysispurpose"/></td>
	<td><xsl:value-of select="@experimentsincluded"/></td>
	<td><xsl:value-of select="@preferredage"/></td>
	<td><xsl:value-of select="@preferredagesigma"/></td>
	<td><xsl:value-of select="@preferredagesigmainternal"/></td>
	<td><xsl:value-of select="@preferredagesigmaexternal"/></td>
	<td><xsl:value-of select="@preferredagetype"/></td>
	<td><xsl:value-of select="@preferredageclassification"/></td>
	<td><xsl:value-of select="@preferredagereference"/></td>
	<td><xsl:value-of select="@preferredagedescription"/></td>
	</tr>
</xsl:template>

<?
if($showintensities=="yes"){
?>
<xsl:template match="intensities">
<br/>
<div class="aboutpage"><div class="headline">Intensities</div></div>
<div class="box_two">
	<table class="sample">
	<tr>
	<th>ID</th>
	<th>Power</th>
	<th>40Ar</th>
	<th>error 40Ar (1s)</th>
	<th>39Ar</th>
	<th>error 39Ar (1s)</th>
	<th>38Ar</th>
	<th>error 38Ar (1s)</th>
	<th>37Ar</th>
	<th>error 37Ar (1s)</th>
	<th>36Ar</th>
	<th>error 36Ar (1s)</th>
	<th>40Ar* %</th>
	<th>40Ar*/39ArK</th>
	<th>error 40Ar*/39ArK (1s)</th>
	<th>Age</th>
	<th>Age Error (1s)</th>
	</tr>
	<tr>
	<th><xsl:value-of select="@idunits"/></th>
	<th><xsl:value-of select="@powerunits"/></th>
	<th><xsl:value-of select="@ar40units"/></th>
	<th><xsl:value-of select="@error40ar1sunits"/></th>
	<th><xsl:value-of select="@ar39units"/></th>
	<th><xsl:value-of select="@error39ar1sunits"/></th>
	<th><xsl:value-of select="@ar38units"/></th>
	<th><xsl:value-of select="@error38ar1sunits"/></th>
	<th><xsl:value-of select="@ar37units"/></th>
	<th><xsl:value-of select="@error37ar1sunits"/></th>
	<th><xsl:value-of select="@ar36units"/></th>
	<th><xsl:value-of select="@error36ar1sunits"/></th>
	<th><xsl:value-of select="@ar40pctunits"/></th>
	<th><xsl:value-of select="@ar40ar39kunits"/></th>
	<th><xsl:value-of select="@error40ar39ark1sunits"/></th>
	<th><xsl:value-of select="@ageunits"/></th>
	<th><xsl:value-of select="@ageerror1sunits"/></th>
	</tr>
	<xsl:apply-templates select="intensity"/>
	</table>
</div>
</xsl:template>


<xsl:template match="intensity">
	<tr>
	<td><xsl:value-of select="@id"/></td>
	<td><xsl:value-of select="@power"/></td>
	<td><xsl:value-of select="@ar40"/></td>
	<td><xsl:value-of select="@error40ar1s"/></td>
	<td><xsl:value-of select="@ar39"/></td>
	<td><xsl:value-of select="@error39ar1s"/></td>
	<td><xsl:value-of select="@ar38"/></td>
	<td><xsl:value-of select="@error38ar1s"/></td>
	<td><xsl:value-of select="@ar37"/></td>
	<td><xsl:value-of select="@error37ar1s"/></td>
	<td><xsl:value-of select="@ar36"/></td>
	<td><xsl:value-of select="@error36ar1s"/></td>
	<td><xsl:value-of select="@ar40pct"/></td>
	<td><xsl:value-of select="@ar40ar39k"/></td>
	<td><xsl:value-of select="@error40ar39ark1s"/></td>
	<td><xsl:value-of select="@age"/></td>
	<td><xsl:value-of select="@ageerror1s"/></td>
	</tr>
</xsl:template>

<?
}
?>

<?
if($showratios=="yes"){
?>

<xsl:template match="ratios">
<br/>
<div class="aboutpage"><div class="headline">Ratios</div></div>
<div class="box_two">
	<table class="sample">
	<tr>
	<th>ID</th>
	<th>Power</th>
	<th>40Ar/39Ar</th>
	<th>37Ar/39Ar</th>
	<th>36Ar/39Ar</th>
	<th>39ArK</th>
	<th>K/Ca</th>
	<th>40Ar*</th>
	<th>39Ar</th>
	<th>Age</th>
	<th>Age Error 1s</th>
	</tr>
	<tr>
	<th><xsl:value-of select="@idunits"/></th>
	<th><xsl:value-of select="@powerunits"/></th>
	<th><xsl:value-of select="@ar40ar39units"/></th>
	<th><xsl:value-of select="@ar37ar39units"/></th>
	<th><xsl:value-of select="@ar36ar39units"/></th>
	<th><xsl:value-of select="@ar39kunits"/></th>
	<th><xsl:value-of select="@kcaunits"/></th>
	<th><xsl:value-of select="@ar40units"/></th>
	<th><xsl:value-of select="@ar39units"/></th>
	<th><xsl:value-of select="@ageunits"/></th>
	<th><xsl:value-of select="@ageerror1sunits"/></th>
	</tr>
	<xsl:apply-templates select="ratio"/>
	</table>
</div>
</xsl:template>


<xsl:template match="ratio">
	<tr>
	<td><xsl:value-of select="@id"/></td>
	<td><xsl:value-of select="@power"/></td>
	<td><xsl:value-of select="@ar40ar39"/></td>
	<td><xsl:value-of select="@ar37ar39"/></td>
	<td><xsl:value-of select="@ar36ar39"/></td>
	<td><xsl:value-of select="@ar39k"/></td>
	<td><xsl:value-of select="@kca"/></td>
	<td><xsl:value-of select="@ar40"/></td>
	<td><xsl:value-of select="@ar39"/></td>
	<td><xsl:value-of select="@age"/></td>
	<td><xsl:value-of select="@ageerror1s"/></td>
	</tr>
</xsl:template>

<?
}
?>

</xsl:stylesheet>