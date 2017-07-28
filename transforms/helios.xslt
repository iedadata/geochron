<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:n="http://www.geosamples.org"
                >

<xsl:import href="http://www.geochronportal.org/EarthTimeOrgCommonTransforms.xslt"/>

<xsl:output method="html"/>

<xsl:template match="n:HeliosModel">
<xsl:apply-templates select="n:SampleForGeochron"/>
</xsl:template>

<xsl:template match="n:SampleForGeochron">
<div class="aboutpage">
<div class="headline">Sample Details
<a><xsl:attribute name="href">downloadfile.php?id=<xsl:value-of select="n:IGSN"/>&amp;name=<xsl:value-of select="n:SampleName"/></xsl:attribute>(Download XML File)</a>
</div>
</div>
<div class="box_two">
Sample Name: <xsl:value-of select="n:SampleName"/><br/>
Sample Comment: <xsl:value-of select="n:SampleComment"/><br/>
<div class="aboutpage">
Unique ID: <xsl:value-of select="n:IGSN"/>
<xsl:text> </xsl:text>
<a><xsl:attribute name="href">viewid.php?id=<xsl:value-of select="n:IGSN"/></xsl:attribute><xsl:attribute name="target">_blank</xsl:attribute>(Detail)</a>
</div>
Analyst Name: <xsl:value-of select="n:AnalystName"/><br/>
Laboratory Name: <xsl:value-of select="n:LaboratoryName"/><br/>
Publication: <xsl:value-of select="n:Publication"/><br/>
DOI: <xsl:value-of select="n:DOI"/><br/>
Award Number: <xsl:value-of select="n:AwardNumber"/><br/>
Stratigraphy: <xsl:value-of select="n:Stratigraphy"/><br/>
Stratigraphic Age: <xsl:value-of select="n:StratigraphicAge"/><br/>
La238: <xsl:value-of select="n:La238"/><br/>
La235: <xsl:value-of select="n:La235"/><br/>
La232: <xsl:value-of select="n:La232"/><br/>
La147: <xsl:value-of select="n:La147"/><br/>
Mean ESR: <xsl:value-of select="n:MeanESR"/><br/>
</div>
<br/>
<xsl:apply-templates select="n:Ages"/>
<xsl:apply-templates select="n:Aliquots"/>
</xsl:template>

<xsl:template match="n:Ages">
	<div class="headline">Ages:</div>
	<div class="box_two">
		<xsl:apply-templates select="n:Age"/>
	</div>
</xsl:template>

<xsl:template match="n:Age">
	<div class="box_two">
		Sample Age: <xsl:value-of select="n:SampleAge"/><br/>
		Sample Age Error: <xsl:value-of select="n:SampleAgeErr"/><br/>
		Sample Age Standard Deviation: <xsl:value-of select="n:SampleAgeStDev"/><br/>
		n: <xsl:value-of select="n:n"/><br/>
		Type of Age: <xsl:value-of select="n:TypeOfAge"/><br/>
		Calculation Method: <xsl:value-of select="n:CalculationMethod"/><br/>
		Age Units: <xsl:value-of select="n:AgeUnits"/><br/>
		Uncertainty Type: <xsl:value-of select="n:UncertaintyType"/><br/>
		One Sigma: <xsl:value-of select="n:OneSigma"/><br/>
		Preferred Age: <xsl:value-of select="n:PreferredAge"/><br/>
		Age Comment: <xsl:value-of select="n:AgeComment"/><br/>
	</div>
</xsl:template>

<xsl:template match="n:Aliquots">
	<br/>
	<div class="headline">Aliquots:</div>
	<div class="box_two">
		<xsl:apply-templates select="n:AliquotForGeochron"/>
	</div>
</xsl:template>

<xsl:template match="n:AliquotForGeochron">
	<div class="box_two">
		<xsl:if test="n:AliquotID!='0'">Aliquot ID: <xsl:value-of select="n:AliquotID"/><br/></xsl:if>
		<xsl:if test="n:Mineral!='0'">Mineral: <xsl:value-of select="n:Mineral"/><br/></xsl:if>
		<xsl:if test="n:Mass!='0'">Mass: <xsl:value-of select="n:Mass"/><br/></xsl:if>
		<xsl:if test="n:MassUnits!='0'">Mass Units: <xsl:value-of select="n:MassUnits"/><br/></xsl:if>
		<xsl:if test="n:UFt!='0'">U/Ft: <xsl:value-of select="n:UFt"/><br/></xsl:if>
		<xsl:if test="n:ThFt!='0'">Th/Ft: <xsl:value-of select="n:ThFt"/><br/></xsl:if>
		<xsl:if test="n:He!='0'">He: <xsl:value-of select="n:He"/><br/></xsl:if>
		<xsl:if test="n:HeErr!='0'">He Error: <xsl:value-of select="n:HeErr"/><br/></xsl:if>
		<xsl:if test="n:U238_235Meas!='0'">U238/235 Measured: <xsl:value-of select="n:U238_235Meas"/><br/></xsl:if>
		<xsl:if test="n:Th232_230Meas!='0'">Th232/230 Measured: <xsl:value-of select="n:Th232_230Meas"/><br/></xsl:if>
		<xsl:if test="n:Sm147_149Meas!='0'">Sm147/149 Measured: <xsl:value-of select="n:Sm147_149Meas"/><br/></xsl:if>
		<xsl:if test="n:UMeasErr!='0'">U Measured Error: <xsl:value-of select="n:UMeasErr"/><br/></xsl:if>
		<xsl:if test="n:ThMeasErr!='0'">Th Measured Error: <xsl:value-of select="n:ThMeasErr"/><br/></xsl:if>
		<xsl:if test="n:SmMeasErr!='0'">Sm Measured Error: <xsl:value-of select="n:SmMeasErr"/><br/></xsl:if>
		<xsl:if test="n:MeanL!='0'">Mean Lenth: <xsl:value-of select="n:MeanL"/><br/></xsl:if>
		<xsl:if test="n:MeanW!='0'">Mean Width: <xsl:value-of select="n:MeanW"/><br/></xsl:if>
		<xsl:if test="n:ESR!='0'">ESR: <xsl:value-of select="n:ESR"/><br/></xsl:if>
		<xsl:if test="n:SpikeID!='0'">Spike ID: <xsl:value-of select="n:SpikeID"/><br/></xsl:if>
		<xsl:if test="n:SpikeVol!='0'">Spike Volume: <xsl:value-of select="n:SpikeVol"/><br/></xsl:if>
		<xsl:if test="n:SpkConcUnits!='0'">Spike Concentration Units: <xsl:value-of select="n:SpkConcUnits"/><br/></xsl:if>
		<xsl:if test="n:Spk238_235!='0'">Spike 238/235: <xsl:value-of select="n:Spk238_235"/><br/></xsl:if>
		<xsl:if test="n:Spk232_230!='0'">Spike 232/230: <xsl:value-of select="n:Spk232_230"/><br/></xsl:if>
		<xsl:if test="n:Spk147_149!='0'">Spike 147/149: <xsl:value-of select="n:Spk147_149"/><br/></xsl:if>
		<xsl:if test="n:Spk235AtomsPerMl!='0'">Spike 235 Atoms per Ml: <xsl:value-of select="n:Spk235AtomsPerMl"/><br/></xsl:if>
		<xsl:if test="n:Spk230AtomsPerMl!='0'">Spike 230 Atoms per Ml: <xsl:value-of select="n:Spk230AtomsPerMl"/><br/></xsl:if>
		<xsl:if test="n:Spk149AtomsPerMl!='0'">Spike 149 Atoms per Ml: <xsl:value-of select="n:Spk149AtomsPerMl"/><br/></xsl:if>
		<xsl:if test="n:NormalID!='0'">Normal ID: <xsl:value-of select="n:NormalID"/><br/></xsl:if>
		<xsl:if test="n:NormalVol!='0'">Normal Volume: <xsl:value-of select="n:NormalVol"/><br/></xsl:if>
		<xsl:if test="n:NorConcUnits!='0'">Normal Concentraion Units: <xsl:value-of select="n:NorConcUnits"/><br/></xsl:if>
		<xsl:if test="n:Nor238_235meas!='0'">Normal 238/235 Measured: <xsl:value-of select="n:Nor238_235meas"/><br/></xsl:if>
		<xsl:if test="n:Nor232_230meas!='0'">Normal 232/230 Measured: <xsl:value-of select="n:Nor232_230meas"/><br/></xsl:if>
		<xsl:if test="n:Nor147_149meas!='0'">Normal 147/149 Measured: <xsl:value-of select="n:Nor147_149meas"/><br/></xsl:if>
		<xsl:if test="n:NorUConc!='0'">Normal U Concentration: <xsl:value-of select="n:NorUConc"/><br/></xsl:if>
		<xsl:if test="n:NorThConc!='0'">Normal Th Concentration: <xsl:value-of select="n:NorThConc"/><br/></xsl:if>
		<xsl:if test="n:NorSmConc!='0'">Normal Sm Concentration: <xsl:value-of select="n:NorSmConc"/><br/></xsl:if>
		<xsl:if test="n:SpNorVolumeUnits!='0'">Spike Normal Volume Units: <xsl:value-of select="n:SpNorVolumeUnits"/><br/></xsl:if>
		<xsl:if test="n:Age!='0'">Age: <xsl:value-of select="n:Age"/><br/></xsl:if>
		<xsl:if test="n:AbsAgeErr!='0'">Absolute Age Error: <xsl:value-of select="n:AbsAgeErr"/><br/></xsl:if>
		<xsl:if test="n:PAgeErr!='0'">P Age Error: <xsl:value-of select="n:PAgeErr"/><br/></xsl:if>
		<xsl:if test="n:Ft!='0'">Ft: <xsl:value-of select="n:Ft"/><br/></xsl:if>
		<xsl:if test="n:ThURatio!='0'">Th/U Ratio: <xsl:value-of select="n:ThURatio"/><br/></xsl:if>
		<xsl:if test="n:Uppm!='0'">U ppm: <xsl:value-of select="n:Uppm"/><br/></xsl:if>
		<xsl:if test="n:Thppm!='0'">Th ppm: <xsl:value-of select="n:Thppm"/><br/></xsl:if>
		<xsl:if test="n:Sm147ppm!='0'">Sm 147 ppm: <xsl:value-of select="n:Sm147ppm"/><br/></xsl:if>
		<xsl:if test="n:HeNmolG!='0'">He Nmol G: <xsl:value-of select="n:HeNmolG"/><br/></xsl:if>
		<xsl:if test="n:UeffConc!='0'">U eff Concentration: <xsl:value-of select="n:UeffConc"/><br/></xsl:if>
		<xsl:if test="n:UsedForAgeCalc!='0'">Used For Age Calculation: <xsl:value-of select="n:UsedForAgeCalc"/><br/></xsl:if>
	</div><br/>
</xsl:template>






</xsl:stylesheet>