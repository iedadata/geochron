<?
$pkey=$_GET['pkey'];

echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>";
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
<xsl:apply-templates select="fractions"/>
<xsl:apply-templates select="traces"/>

<? if(file_exists("/public/mgg/web/www.geochron.org/htdocs/uploadimages/".$pkey.".jpg")){ ?>
<br/><div class="aboutpage"><div class="headline">Sample Image</div><img src="uploadimages/<?=$pkey?>.jpg"/></div>
<? }

exec("ls /local/public/mgg/web/www.geochron.org/geochronuploadimages | grep _".$pkey."_",$files);

foreach($files as $file){
$pp = pathinfo($file);
$ext = strtolower($pp['extension']);
if($ext=="jpg"||$ext=="jpeg"||$ext=="gif"||$ext=="png"){?>
<br/><div class="aboutpage"><div class="headline">Sample Image</div><img src="uploadimages/<?=$file?>"/></div>
<?}elseif($ext=="tiff"||$ext="tif"){?>
<br/><div class="aboutpage"><div class="headline">Sample Image</div><a href="uploadimages/<?=$file?>"><?=$file?></a></div>
<?}
}?>
</xsl:template>

<xsl:template match="sampleinfo">
<div class="aboutpage"><div class="headline">Sample Details<?=$pkey?></div></div>
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


<xsl:template match="fractions">
<br/>
<div class="aboutpage"><div class="headline">Fraction(s)</div></div>
<div class="box_two">



<xsl:choose>
	<xsl:when test="count(fraction) > 0">
		<table class="sample">
		<tr>
			<th><xsl:attribute name="nowrap" />Spot ID</th>
			<th><xsl:attribute name="nowrap" />204 corr 206Pb/238U Age</th>
			<th><xsl:attribute name="nowrap" />1 s err</th>
			<th><xsl:attribute name="nowrap" />207Pb/235U Age</th>
			<th><xsl:attribute name="nowrap" />1 s err</th>
			<th><xsl:attribute name="nowrap" />204 corr 207Pb/206Pb Age</th>
			<th><xsl:attribute name="nowrap" />1 s err</th>
			<th><xsl:attribute name="nowrap" />Rho</th>
			<th><xsl:attribute name="nowrap" />204 corr 208Pb/232Th Age</th>
			<th><xsl:attribute name="nowrap" />1 s err</th>
	
	
		</tr>
		<xsl:apply-templates select="fraction"/>
		</table>
	</xsl:when>
	<xsl:otherwise>
	No Fractions Found.
	</xsl:otherwise>
</xsl:choose>
</div>
</xsl:template>

<xsl:template match="fraction">
	<tr>
		<td><xsl:attribute name="nowrap" /><xsl:value-of select="fractionid"/></td>
		<td><xsl:attribute name="nowrap" /><xsl:value-of select="age_206_238"/></td>
		<td><xsl:attribute name="nowrap" /><xsl:value-of select="age_206_238_err"/></td>
		<td><xsl:attribute name="nowrap" /><xsl:value-of select="age_207_235"/></td>
		<td><xsl:attribute name="nowrap" /><xsl:value-of select="age_207_235_err"/></td>
		<td><xsl:attribute name="nowrap" /><xsl:value-of select="age_207_206"/></td>
		<td><xsl:attribute name="nowrap" /><xsl:value-of select="age_207_206_err"/></td>
		<td><xsl:attribute name="nowrap" /><xsl:value-of select="rho"/></td>
		<td><xsl:attribute name="nowrap" /><xsl:value-of select="age_208_232"/></td>
		<td><xsl:attribute name="nowrap" /><xsl:value-of select="age_208_232_err"/></td>
	</tr>
</xsl:template>





<xsl:template match="traces">
<br/>
<div class="aboutpage"><div class="headline">Trace(s)</div></div>
<div class="box_two">



<xsl:choose>
	<xsl:when test="count(trace) > 0">
		<table class="sample">
		<tr>
			<th><xsl:attribute name="nowrap" />Spot ID</th>
			<th><xsl:attribute name="nowrap" />Y (ppm)</th>
			<th><xsl:attribute name="nowrap" />La (ppm)</th>
			<th><xsl:attribute name="nowrap" />Ce (ppm)</th>
			<th><xsl:attribute name="nowrap" />Pr (ppm)</th>
			<th><xsl:attribute name="nowrap" />Nd (ppm)</th>
			<th><xsl:attribute name="nowrap" />Sm (ppm)</th>
			<th><xsl:attribute name="nowrap" />Eu (ppm)</th>
			<th><xsl:attribute name="nowrap" />Gd (ppm)</th>
			<th><xsl:attribute name="nowrap" />Dy (ppm)</th>
			<th><xsl:attribute name="nowrap" />Er (ppm)</th>
			<th><xsl:attribute name="nowrap" />Yb (ppm)</th>
			<th><xsl:attribute name="nowrap" />Hf (ppm)</th>
			<th><xsl:attribute name="nowrap" />U (ppm)</th>
			<th><xsl:attribute name="nowrap" />Th (ppm)</th>

	
	
		</tr>
		<xsl:apply-templates select="trace"/>
		</table>
	</xsl:when>
	<xsl:otherwise>
	No Traces Found.
	</xsl:otherwise>
</xsl:choose>
</div>
</xsl:template>

<xsl:template match="trace">
	<tr>
		<td><xsl:attribute name="nowrap" /><xsl:value-of select="spotid"/></td>
		<td><xsl:attribute name="nowrap" /><xsl:value-of select="y_ppm"/></td>
		<td><xsl:attribute name="nowrap" /><xsl:value-of select="la_ppm"/></td>
		<td><xsl:attribute name="nowrap" /><xsl:value-of select="ce_ppm"/></td>
		<td><xsl:attribute name="nowrap" /><xsl:value-of select="pr_ppm"/></td>
		<td><xsl:attribute name="nowrap" /><xsl:value-of select="nd_ppm"/></td>
		<td><xsl:attribute name="nowrap" /><xsl:value-of select="sm_ppm"/></td>
		<td><xsl:attribute name="nowrap" /><xsl:value-of select="eu_ppm"/></td>
		<td><xsl:attribute name="nowrap" /><xsl:value-of select="gd_ppm"/></td>
		<td><xsl:attribute name="nowrap" /><xsl:value-of select="dy_ppm"/></td>
		<td><xsl:attribute name="nowrap" /><xsl:value-of select="er_ppm"/></td>
		<td><xsl:attribute name="nowrap" /><xsl:value-of select="yb_ppm"/></td>
		<td><xsl:attribute name="nowrap" /><xsl:value-of select="hf_ppm"/></td>
		<td><xsl:attribute name="nowrap" /><xsl:value-of select="u_ppm"/></td>
		<td><xsl:attribute name="nowrap" /><xsl:value-of select="th_ppm"/></td>

	</tr>
</xsl:template>


</xsl:stylesheet>