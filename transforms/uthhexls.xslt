<?xml version="1.0" encoding="utf-8"?>
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
<xsl:apply-templates select="datareductionparameters"/>
<xsl:apply-templates select="fractions"/>
</xsl:template>

<xsl:template match="sampleinfo">
<div class="aboutpage"><div class="headline">Sample Details</div></div>
<div class="box_two">

Sample Name: <xsl:value-of select="samplename"/><br/>
IGSN: <xsl:value-of select="uniqueid"/><br/>
Laboratory: <xsl:value-of select="labname"/><br/>
Analyst: <xsl:value-of select="analystname"/><br/>
Instrumental Method - He: <xsl:value-of select="instrumentalmethodhe"/><br/>
Instrumental Method Reference - He: <xsl:value-of select="instrumentalmethodreferencehe"/><br/>
Instrumental Method - U-Th-Sm: <xsl:value-of select="instrumentalmethoduthsm"/><br/>
Instrumental Method Reference - U-Th-Sm: <xsl:value-of select="instrumentalmethodreferenceuthsm"/><br/>
Alpha Ejection Correction Method: <xsl:value-of select="alphaejectioncorrectionmethod"/><br/>
Alpha Ejection Correction Method Reference: <xsl:value-of select="alphaejectioncorrectionmethodreference"/><br/>
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
238U/235U: <xsl:value-of select="u238u235"/><br/>
Decay Constant Reference: <xsl:value-of select="decayconstantreference"/><br/>
147Sm Decay Constant: <xsl:value-of select="smdecayconstant147"/><br/>
147Sm Decay Constant Error: <xsl:value-of select="smdecayconstanterror147"/><br/>
147Sm Decay Constant Reference: <xsl:value-of select="smdecayconstantreference147"/><br/>
Decay Constant Comment: <xsl:value-of select="decayconstantcomment"/><br/>





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
	</tr>
	<xsl:apply-templates select="age"/>
	</table>
</div>
</xsl:template>

<xsl:template match="datareductionparameters">
<br/>
<div class="aboutpage"><div class="headline">Data Reduction Parameters</div></div>
<div class="box_two">
Spike Type U-Th-Sm: <xsl:value-of select="spiketypeuthsm"/><br/>
Spike Type He: <xsl:value-of select="spiketypehe"/><br/>
Standard Mineral: <xsl:value-of select="standardmineral"/><br/>
Standard Mineral Reference: <xsl:value-of select="standardmineralreference"/><br/>
Standard True Age: <xsl:value-of select="standardtrueage"/><br/>
Standard True Age Error: <xsl:value-of select="standardtrueageerror"/><br/>
Standard Measured Age: <xsl:value-of select="standardmeasuredage"/><br/>
Standard Measured Age Error: <xsl:value-of select="standardmeasuredageerror"/><br/>
Data Reduction Comment: <xsl:value-of select="datareductioncomment"/><br/>
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
	</tr>
</xsl:template>

<xsl:template match="fractions">
<br/>
<div class="aboutpage"><div class="headline">Fraction(s)</div></div>
<div class="box_two">
	<table class="sample">
	<tr>
		<th>Fraction Name</th>
		<th>Mineral</th>
		<th>Age, Ma</th>
		<th>err., Ma</th>
		<th>U (ppm)</th>
		<th>Th (ppm)</th>
		<th>147Sm (ppm)</th>
		<th>[U]e</th>
		<th>Th/U</th>
		<th>He (nmol/g)</th>
		<th>Mass (ug)</th>
		<th>Ft</th>
		<th>Mean ESR</th>
	</tr>
	<xsl:apply-templates select="fraction"/>
	</table>
</div>
</xsl:template>

<xsl:template match="fraction">
	<tr>
		<td><xsl:attribute name="nowrap" /><xsl:value-of select="aliquot_name"/></td>
		<td><xsl:attribute name="nowrap" /><xsl:value-of select="mineral"/></td>
		<td><xsl:attribute name="nowrap" /><xsl:value-of select="age_ma"/></td>
		<td><xsl:attribute name="nowrap" /><xsl:value-of select="age_err_ma"/></td>
		<td><xsl:attribute name="nowrap" /><xsl:value-of select="u_ppm"/></td>
		<td><xsl:attribute name="nowrap" /><xsl:value-of select="th_ppm"/></td>
		<td><xsl:attribute name="nowrap" /><xsl:value-of select="sm_147_ppm"/></td>
		<td><xsl:attribute name="nowrap" /><xsl:value-of select="ue"/></td>
		<td><xsl:attribute name="nowrap" /><xsl:value-of select="thUu"/></td>
		<td><xsl:attribute name="nowrap" /><xsl:value-of select="he"/></td>
		<td><xsl:attribute name="nowrap" /><xsl:value-of select="mass_ug"/></td>
		<td><xsl:attribute name="nowrap" /><xsl:value-of select="ft"/></td>
		<td><xsl:attribute name="nowrap" /><xsl:value-of select="mean_esr"/></td>
	</tr>
</xsl:template>

</xsl:stylesheet>