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
<xsl:apply-templates select="spots"/>
</xsl:template>

<xsl:template match="sampleinfo">
<div class="aboutpage"><div class="headline">Sample Details</div></div>
<div class="box_two">
Sample Name: <xsl:value-of select="samplename"/><br/>
IGSN: <xsl:value-of select="igsn"/><br/>
Laboratory: <xsl:value-of select="laboratory"/><br/>
Analyst: <xsl:value-of select="analyst"/><br/>
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
	<th>Age</th>
	<th>Age Error</th>
	<th>Age Type</th>
	</tr>
	<xsl:apply-templates select="age"/>
	</table>
</div>
</xsl:template>


<xsl:template match="age">
	<td><xsl:value-of select="@value"/></td>
	<td><xsl:value-of select="@error"/></td>
	<td><xsl:value-of select="@type"/></td>
</xsl:template>

<xsl:template match="spots">
<br/>
<div class="aboutpage"><div class="headline">Spot(s)</div></div>
<div class="box_two">
	<table class="sample">
	<tr>
	<th>Age (Ma) 206Pb/238U</th>
	<th>Age (Ma) 206Pb/238U 1 s.e.</th>
	<th>Age (Ma) 207Pb/235U</th>
	<th>Age (Ma) 207Pb/235U 1 s.e.</th>
	<th>Age (Ma) 207Pb/206Pb</th>
	<th>Age (Ma) 207Pb/206Pb 1 s.e.</th>
	<th>206Pb*/238U</th>
	<th>206Pb*/238U 1 s.e.</th>
	<th>207Pb*/235U</th>
	<th>207Pb*/235U 1 s.e.</th>
	<th>Correlation of Concordia Ellipses</th>
	<th>207Pb*/206Pb*</th>
	<th>207Pb*/206Pb* 1 s.e.</th>
	<th>Th</th>
	<th>U</th>
	<th>Common 206Pb/204Pb</th>
	<th>Common 207Pb/204Pb</th>
	<th>Common 208Pb/204Pb</th>
	<th>Pb Correlation</th>
	<th>Blk Size</th>
	<th>Name</th>
	<th>Rejected</th>
	</tr>
	<xsl:apply-templates select="spot"/>
	</table>
</div>
</xsl:template>

<xsl:template match="spot">
	<tr>
	<td><xsl:value-of select="agema206pb238u"/></td>
	<td><xsl:value-of select="agema206pb238u1se"/></td>
	<td><xsl:value-of select="agema207pb235u"/></td>
	<td><xsl:value-of select="agema207pb235u1se"/></td>
	<td><xsl:value-of select="agema207pb206pb"/></td>
	<td><xsl:value-of select="agema207pb206pb1se"/></td>
	<td><xsl:value-of select="v206pb238u"/></td>
	<td><xsl:value-of select="v206pb238u1se"/></td>
	<td><xsl:value-of select="v207pb235u"/></td>
	<td><xsl:value-of select="v207pb235u1se"/></td>
	<td><xsl:value-of select="correlationofconcordiaellipses"/></td>
	<td><xsl:value-of select="v207pb206pb"/></td>
	<td><xsl:value-of select="v207pb206pb1se"/></td>
	<td><xsl:value-of select="th"/></td>
	<td><xsl:value-of select="u"/></td>
	<td><xsl:value-of select="common206pb204pb"/></td>
	<td><xsl:value-of select="common207pb204pb"/></td>
	<td><xsl:value-of select="common208pb204pb"/></td>
	<td><xsl:value-of select="pbcorrelations"/></td>
	<td><xsl:value-of select="blksize"/></td>
	<td><xsl:value-of select="name"/></td>
	<td><xsl:value-of select="rejected"/></td>
	</tr>
</xsl:template>

</xsl:stylesheet>