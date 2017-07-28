<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:n="http://www.geosamples.org"
                >

	<xsl:import href="http://www.geochron.org/EarthTimeOrgCommonTransforms.xslt"/>

	<xsl:output method="html"/>

	<xsl:template match="sampledata">

		<div class="aboutpage">

			<div class="aboutpage">
				<div class="headline">Sample Details
				<a><xsl:attribute name="href">downloadfile.php?id=<xsl:value-of select="igsn"/>&amp;name=<xsl:value-of select="sample"/></xsl:attribute>(Download XML File)</a>
				</div>
			</div>

			<div class="box_two">

				<span class="itemname">IGSN: </span><xsl:value-of select="igsn"/>
				<xsl:text> </xsl:text>
				<a><xsl:attribute name="href">viewid.php?id=<xsl:value-of select="igsn"/></xsl:attribute><xsl:attribute name="target">_blank</xsl:attribute>(Detail)</a><br/>
				<xsl:if test="igsn_status!=''"><span class="itemname">IGSN Status: </span><xsl:value-of select="igsn_status"/><br/></xsl:if>
				<xsl:if test="confidentiality!=''"><span class="itemname">Confidentiality: </span><xsl:value-of select="confidentiality"/><br/></xsl:if>
				<xsl:if test="sample_id!=''"><span class="itemname">Sample ID: </span><xsl:value-of select="sample_id"/><br/></xsl:if>
				<xsl:if test="sample!=''"><span class="itemname">Sample: </span><xsl:value-of select="sample"/><br/></xsl:if>
				<xsl:if test="lng!=''"><span class="itemname">Longitude: </span><xsl:value-of select="lng"/><br/></xsl:if>
				<xsl:if test="lngdec!=''"><span class="itemname">Decimal Longitude: </span><xsl:value-of select="lngdec"/><br/></xsl:if>
				<xsl:if test="lat!=''"><span class="itemname">Latitude: </span><xsl:value-of select="lat"/><br/></xsl:if>
				<xsl:if test="latdec!=''"><span class="itemname">Decimal Latitude: </span><xsl:value-of select="latdec"/><br/></xsl:if>
				<xsl:if test="location!=''"><span class="itemname">Location: </span><xsl:value-of select="location"/><br/></xsl:if>
				<xsl:if test="calculatedlocation!=''"><span class="itemname">Calculated Location: </span><xsl:value-of select="calculatedlocation"/><br/></xsl:if>
				<xsl:if test="elevation!=''"><span class="itemname">Elevation: </span><xsl:value-of select="elevation"/><br/></xsl:if>
				<xsl:if test="collecteddate!=''"><span class="itemname">Collected Date: </span><xsl:value-of select="collecteddate"/><br/></xsl:if>
				<xsl:if test="collectedby!=''"><span class="itemname">Collected By: </span><xsl:value-of select="collectedby"/><br/></xsl:if>

				<xsl:choose>
					<xsl:when test="purpose='detrital'">
						<span class="itemname">Purpose: </span>Detrital Spectrum<br/>
					</xsl:when>
					<xsl:otherwise>
						<span class="itemname">Purpose: </span><xsl:value-of select="purpose"/><br/>
					</xsl:otherwise>
				</xsl:choose>

				<xsl:if test="analyst!=''"><span class="itemname">Analyst: </span><xsl:value-of select="analyst"/><br/></xsl:if>
				<xsl:if test="description!=''"><span class="itemname">Description: </span><xsl:value-of select="description"/><br/></xsl:if>
				<xsl:if test="projectname!=''"><span class="itemname">Project Name: </span><xsl:value-of select="projectname"/><br/></xsl:if>
				<xsl:if test="changed!=''"><span class="itemname">Changed: </span><xsl:value-of select="changed"/><br/></xsl:if>
				<xsl:if test="notes!=''"><span class="itemname">Notes: </span><xsl:value-of select="notes"/><br/></xsl:if>
				<xsl:if test="minage!=''"><span class="itemname">Min Age: </span><xsl:value-of select="minage"/><br/></xsl:if>
				<xsl:if test="maxage!=''"><span class="itemname">Max Age: </span><xsl:value-of select="maxage"/><br/></xsl:if>
				<xsl:if test="geologicalunit!=''"><span class="itemname">Geological Unit: </span><xsl:value-of select="geologicalunit"/><br/></xsl:if>
				<xsl:if test="mineral!=''"><span class="itemname">Mineral: </span><xsl:value-of select="mineral"/><br/></xsl:if>
				<xsl:if test="rocktype!=''"><span class="itemname">Rock Type: </span><xsl:value-of select="rocktype"/><br/></xsl:if>
				<xsl:if test="owner!=''"><span class="itemname">Owner: </span><xsl:value-of select="owner"/><br/></xsl:if>
				<xsl:if test="changedby!=''"><span class="itemname">Changed By: </span><xsl:value-of select="changedby"/><br/></xsl:if>

				<xsl:choose>
					<xsl:when test="type='generic'">
						<span class="itemname">Type: </span>sample<br/>
					</xsl:when>
					<xsl:otherwise>
						<span class="itemname">Type: </span><xsl:value-of select="type"/><br/>
					</xsl:otherwise>
				</xsl:choose>

			</div>

		</div>
	
		<br/><div class="headline">Grain Data:</div>
	
		<xsl:apply-templates select="sequencedata"/>

	</xsl:template>

	<xsl:template match="sequencedata">

		<xsl:apply-templates select="graindata"/>

	</xsl:template>

	<xsl:template match="graindata">

		<div class="box_two">
	
				<xsl:if test="realgrain!=''"><span class="itemname">Grain ID: </span><xsl:value-of select="realgrain"/><br/></xsl:if>
				<xsl:if test="sample!=''"><span class="itemname">Sample: </span><xsl:value-of select="sample"/><br/></xsl:if>
				<xsl:if test="sequence!=''"><span class="itemname">Sequence: </span><xsl:value-of select="sequence"/><br/></xsl:if>
				<xsl:if test="d!=''"><span class="itemname">Date: </span><xsl:value-of select="d"/><br/></xsl:if>
				<xsl:if test="t!=''"><span class="itemname">Time: </span><xsl:value-of select="t"/><br/></xsl:if>
				<xsl:if test="comments!=''"><span class="itemname">comments: </span><xsl:value-of select="comments"/><br/></xsl:if>

		<br/>
		<div class="box_one">
		<div class="headline">Radiogenic Isotope Ratios</div>
		<div class="box_two">
		<table class="sample">
		<tr>
		<th>Name</th>
		<th>Value</th>
		<th>Uncertainty Type</th>
		<th>One Sigma</th>
		</tr>
		
		<xsl:if test="Final206_238!=''">
			<tr>
				<td>r206_238r Value</td>
				<td><xsl:value-of select="Final206_238"/></td>
				<td>One Sigma</td>
				<td><xsl:value-of select="Final206_238_Int2SE div 2"/></td>
				
			</tr>
		</xsl:if>
		
		<xsl:if test="Final207_206!=''">
			<tr>
				<td>r207_206r</td>
				<td><xsl:value-of select="Final207_206"/></td>
				<td>One Sigma</td>
				<td><xsl:value-of select="Final207_206_Int2SE div 2"/></td>
			</tr>
		</xsl:if>
		
		<xsl:if test="Final207_235!=''">
			<tr>
				<td>r207_235r</td>
				<td><xsl:value-of select="Final207_235"/></td>
				<td>One Sigma</td>
				<td><xsl:value-of select="Final207_235_Int2SE div 2"/></td>
			</tr>
		</xsl:if>

		<xsl:if test="ErrorCorrelation_38_6vs7_6!=''">
			<tr>
				<td>rhoR207_206r__r238_206r</td>
				<td><xsl:value-of select="ErrorCorrelation_38_6vs7_6"/></td>
				<td></td>
				<td></td>
			</tr>
		</xsl:if>
		
		<xsl:if test="ErrorCorrelation_6_38vs7_35!=''">
			<tr>
				<td>rhoR206_238r__r207_235r</td>
				<td><xsl:value-of select="ErrorCorrelation_6_38vs7_35"/></td>
				<td></td>
				<td></td>
			</tr>
		</xsl:if>

		</table>
		</div>
		</div>


		<br/>
		<div class="box_one">
		<div class="headline">Radiogenic Isotope Dates</div>
		<div class="box_two">
		<table class="sample">
		<tr>
		<th>Name</th>
		<th>Value</th>
		<th>Uncertainty Type</th>
		<th>One Sigma</th>
		</tr>

		<xsl:if test="FinalAge206_238!=''">
			<tr>
				<td>age206_238r</td>
				<td><xsl:value-of select="FinalAge206_238"/></td>
				<td>One Sigma</td>
				<td><xsl:value-of select="FinalAge206_238_Int2SE div 2"/></td>
			</tr>
		</xsl:if>
		
		<xsl:if test="FinalAge207_206!=''">
			<tr>
				<td>age207_206r</td>
				<td><xsl:value-of select="FinalAge207_206"/></td>
				<td>One Sigma</td>
				<td><xsl:value-of select="FinalAge207_206_Int2SE div 2"/></td>
			</tr>
		</xsl:if>
		
		<xsl:if test="FinalAge207_235!=''">
			<tr>
				<td>age207_235r</td>
				<td><xsl:value-of select="FinalAge207_235"/></td>
				<td>One Sigma</td>
				<td><xsl:value-of select="FinalAge207_235_Int2SE div 2"/></td>
			</tr>
		</xsl:if>
		
		<xsl:if test="FinalDiscPercent!=''">
			<tr>
				<td>percentDiscordance</td>
				<td><xsl:value-of select="FinalDiscPercent"/></td>
				<td></td>
				<td></td>
			</tr>
		</xsl:if>
		

		

		</table>
		</div>
		</div>





		</div>

	</xsl:template>

</xsl:stylesheet>