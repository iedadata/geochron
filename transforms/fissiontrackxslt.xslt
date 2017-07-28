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
<xsl:apply-templates select="apatiteages"/>
<xsl:apply-templates select="grainlengths"/>
</xsl:template>

<xsl:template match="sampleinfo">
<div class="aboutpage"><div class="headline">Sample Details</div></div>
<div class="box_two">
Sample Name: <xsl:value-of select="samplename"/><br/>
IGSN: <xsl:value-of select="uniqueid"/><br/>
Laboratory: <xsl:value-of select="labname"/><br/>
Analyst: <xsl:value-of select="analystname"/><br/>
Mineral: <xsl:value-of select="mineral"/><br/>
Dosimeter Glass: <xsl:value-of select="dosimeterglass"/><br/>
Dosimeter Glass U ppm: <xsl:value-of select="dosimeterglassuppm"/><br/>
Irradiation: <xsl:value-of select="irradiation"/><br/>
Count Date: <xsl:value-of select="countdate"/><br/>
Locality: <xsl:value-of select="locality"/><br/>
Rock Type: <xsl:value-of select="rocktype"/><br/>
Rock Age: <xsl:value-of select="rockage"/><br/>
Acquisition system: <xsl:value-of select="acquisitionsystem"/><br/>
Magnification: <xsl:value-of select="magnification"/><br/>
Radiation Facility: <xsl:value-of select="radiationfacility"/><br/>
Total Thermal Neutron Fluence: <xsl:value-of select="totalthermalneutronfluence"/><br/>
Position in Irradiation Canister (#): <xsl:value-of select="positioninirradiationcanister"/><br/>
Area of Graticule Square: <xsl:value-of select="areaofgraticulesquare"/><br/>
No. of Crystals Counted: <xsl:value-of select="noofcrystalscounted"/><br/>
Zeta Factor: <xsl:value-of select="zetafactor"/><br/>
Zeta Factor Error (1s): <xsl:value-of select="zetafactorerror1s"/><br/>
Rho d: <xsl:value-of select="rhod"/><br/>
Rho d (% Relative Error): <xsl:value-of select="rhodpctrelativeerror"/><br/>
N d: <xsl:value-of select="nd"/><br/>
Geometry factor (for EDM and Zeta ICPMS methods): <xsl:value-of select="geometryfactorforedmandzetaicpmsmethods"/><br/>
Etchant: <xsl:value-of select="etchant"/><br/>
Etching Conditions: <xsl:value-of select="etchingconditions"/><br/>
Method - U: <xsl:value-of select="methodu"/><br/>
ICPMS Model: <xsl:value-of select="icpmsmodel"/><br/>
Laser Model and Type: <xsl:value-of select="lasermodelandtype"/><br/>
Instrumental Method References: <xsl:value-of select="instrumentalmethodreferences"/><br/>
U Calibration Standard: <xsl:value-of select="ucalibrationstandard"/><br/>
U ppm of Standard: <xsl:value-of select="uppmofstandard"/><br/>
Specific Denisty of Dated Mineral: <xsl:value-of select="specificdenistyofdatedmineral"/><br/>
Avagadro Constant: <xsl:value-of select="avagadroconstant"/><br/>
Registration Factor (Rsp): <xsl:value-of select="registrationfactorrsp"/><br/>
Etching Correction Factor (k): <xsl:value-of select="etchingcorrectionfactork"/><br/>
ICPMS Zeta Standard: <xsl:value-of select="icpmszetastandard"/><br/>
Standard Age: <xsl:value-of select="standardage"/><br/>
Standard Age Error: <xsl:value-of select="standardageerror"/><br/>
Primary Zeta: <xsl:value-of select="primaryzeta"/><br/>
1 sigma Uncertainty: <xsl:value-of select="onesigmauncertainty"/><br/>
Session (Modified) Zeta: <xsl:value-of select="sessionmodifiedzeta"/><br/>
1 sigma (Modified) Uncertainty: <xsl:value-of select="onesigmamodifieduncertainty"/><br/>
Comment: <xsl:value-of select="comment"/><br/>
Chemistry Method: <xsl:value-of select="chemistrymethod"/><br/>
Chemistry Laboratory: <xsl:value-of select="chemistrylaboratory"/><br/>
Chemistry Comment: <xsl:value-of select="chemistrycomment"/><br/>
238U Decay Constant: <xsl:value-of select="u238decayconstant"/><br/>
238U Decay Constant Error: <xsl:value-of select="u238decayconstanterror"/><br/>
235U Decay Constant: <xsl:value-of select="u235decayconstant"/><br/>
235U Decay Constant Error: <xsl:value-of select="u235decayconstanterror"/><br/>
232Th Decay Constant : <xsl:value-of select="th232decayconstant"/><br/>
232Th Decay Constant Error: <xsl:value-of select="th232decayconstanterror"/><br/>
Spontaneous Fission Decay Constant: <xsl:value-of select="spontaneousfissiondecayconstant"/><br/>
Spontaneous Fission Decay Constant Error: <xsl:value-of select="spontaneousfissiondecayconstanterror"/><br/>
238U/235U: <xsl:value-of select="u238_u235"/><br/>
Fission Decay Constant Reference: <xsl:value-of select="fissiondecayconstantreference"/><br/>
Decay Constant References: <xsl:value-of select="decayconstantreferences"/><br/>
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
	<th>Preferred Age Type</th>
	<th>Pooled Age</th>
	<th>Pooled Age Error +95%</th>
	<th>Pooled Age Error -95%</th>
	<th>Chi-squared Value</th>
	<th>Degrees of Freedom</th>
	<th>P (Chi-suqared)</th>
	<th>Mean Crystal Age</th>
	<th>Mean Crystal Age Error +95%</th>
	<th>Mean Crystal Age Error -95%</th>
	<th>Central Age</th>
	<th>Central Age Error +95%</th>
	<th>Central Age Error -95%</th>
	<th>Central Age - Age Dispersion (%)</th>
	<th>Binomial Age</th>
	<th>Binomial Age Error +95%</th>
	<th>Binomial Age Error -95%</th>
	<th>Other Age</th>
	<th>Other Age Error</th>
	<th>Preferred Age Explanation</th>
	<th>Age Reference</th>
	</tr>
	<xsl:apply-templates select="age"/>
	</table>
</div>
</xsl:template>


<xsl:template match="age">
	<tr>
	<td><xsl:value-of select="@analysispurpose"/></td>
	<td><xsl:value-of select="@preferredagetype"/></td>
	<td><xsl:value-of select="@pooledage"/></td>
	<td><xsl:value-of select="@pooledageerrorpos"/></td>
	<td><xsl:value-of select="@pooledageerrorneg"/></td>
	<td><xsl:value-of select="@chisquaredvalue"/></td>
	<td><xsl:value-of select="@degreesoffreedom"/></td>
	<td><xsl:value-of select="@pchisquared"/></td>
	<td><xsl:value-of select="@meancrystalage"/></td>
	<td><xsl:value-of select="@meancrystalageerrorpos"/></td>
	<td><xsl:value-of select="@meancrystalageerrorneg"/></td>
	<td><xsl:value-of select="@centralage"/></td>
	<td><xsl:value-of select="@centralageerrorpos"/></td>
	<td><xsl:value-of select="@centralageerrorneg"/></td>
	<td><xsl:value-of select="@centralageminusagedispersionpct"/></td>
	<td><xsl:value-of select="@binomialage"/></td>
	<td><xsl:value-of select="@binomialageerrorplus95pct"/></td>
	<td><xsl:value-of select="@binomialageerrorminus95pct"/></td>
	<td><xsl:value-of select="@otherage"/></td>
	<td><xsl:value-of select="@otherageerror"/></td>
	<td><xsl:value-of select="@preferredageexplanation"/></td>
	<td><xsl:value-of select="@agereference"/></td>
	</tr>
</xsl:template>

<xsl:template match="apatiteages">
<br/>
<div class="aboutpage"><div class="headline">Apatite Age</div></div>
<div class="box_two">
	<table class="sample">
	<tr>
	<th>Grain ID</th>
	<th>N s</th>
	<th>N i</th>
	<th>Na</th>
	<th>Dpar</th>
	<th>Dper</th>
	<th>Rmr0</th>
	<th>Rho s</th>
	<th>Rho i</th>
	<th>Rho s / Rho i</th>
	<th>Area (Ω)</th>
	<th> # of Etch Figures</th>
	<th>238U/43Ca</th>
	<th>error (1σ)</th>
	<th>U ppm</th>
	<th>U error (1s)</th>
	<th>Age (Ma)</th>
	<th>Age error +1s</th>
	<th>Age error -1s</th>
	<th>CaO</th>
	<th>P2O5</th>
	<th>F</th>
	<th>Cl</th>
	<th>SrO</th>
	<th>BaO</th>
	<th>Si02</th>
	<th>Na2O</th>
	<th>CeO2</th>
	<th>FeO</th>
	<th>Total</th>
	</tr>
	<xsl:apply-templates select="grain"/>
	</table>
</div>
</xsl:template>


<xsl:template match="grain">
	<tr>
	<td><xsl:value-of select="@grainid"/></td>
	<td><xsl:value-of select="@ns"/></td>
	<td><xsl:value-of select="@ni"/></td>
	<td><xsl:value-of select="@na"/></td>
	<td><xsl:value-of select="@dpar"/></td>
	<td><xsl:value-of select="@dper"/></td>
	<td><xsl:value-of select="@rmr0"/></td>
	<td><xsl:value-of select="@rhos"/></td>
	<td><xsl:value-of select="@rhoi"/></td>
	<td><xsl:value-of select="@rhosrhoi"/></td>
	<td><xsl:value-of select="@area"/></td>
	<td><xsl:value-of select="@ofetchfigures"/></td>
	<td><xsl:value-of select="@u238ca43"/></td>
	<td><xsl:value-of select="@error1s"/></td>
	<td><xsl:value-of select="@uppm"/></td>
	<td><xsl:value-of select="@uerror1s"/></td>
	<td><xsl:value-of select="@agema"/></td>
	<td><xsl:value-of select="@ageerrorplus1s"/></td>
	<td><xsl:value-of select="@ageerrorminus1s"/></td>
	<td><xsl:value-of select="@cao"/></td>
	<td><xsl:value-of select="@p2o5"/></td>
	<td><xsl:value-of select="@f"/></td>
	<td><xsl:value-of select="@cl"/></td>
	<td><xsl:value-of select="@sro"/></td>
	<td><xsl:value-of select="@bao"/></td>
	<td><xsl:value-of select="@si02"/></td>
	<td><xsl:value-of select="@na2o"/></td>
	<td><xsl:value-of select="@ceo2"/></td>
	<td><xsl:value-of select="@feo"/></td>
	<td><xsl:value-of select="@total"/></td>
	</tr>
</xsl:template>

<xsl:template match="grainlengths">
<br/>
<div class="aboutpage"><div class="headline">Grain Lengths</div></div>
<div class="box_two">
	<table class="sample">
	<tr>
	<th>Grain ID</th>
	<th>Length</th>
	<th>Angle to C axis</th>
	<th>Dpar</th>
	<th>Tint or Tincle</th>
	<th>Depth</th>
	<th>Width</th>
	<th>Probability of this being a confined length</th>
	<th>Number of times Intersected</th>
	</tr>
	<xsl:apply-templates select="grainlength"/>
	</table>
</div>
</xsl:template>


<xsl:template match="grainlength">
	<tr>
	<td><xsl:value-of select="@grainid"/></td>
	<td><xsl:value-of select="@length"/></td>
	<td><xsl:value-of select="@angletocaxis"/></td>
	<td><xsl:value-of select="@dpar"/></td>
	<td><xsl:value-of select="@tintortincle"/></td>
	<td><xsl:value-of select="@depth"/></td>
	<td><xsl:value-of select="@width"/></td>
	<td><xsl:value-of select="@probability"/></td>
	<td><xsl:value-of select="@intersected"/></td>
	</tr>
</xsl:template>


</xsl:stylesheet>