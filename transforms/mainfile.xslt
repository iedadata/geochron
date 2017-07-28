<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:n="https://raw.githubusercontent.com/EARTHTIME/Schema"
                >

	<xsl:import href="http://www.geochron.org/EarthTimeOrgCommonTransforms.xslt"/>

  <xsl:output method="html"/>


  <xsl:template match="n:Aliquot">


<div class="box_two">
<div class="aboutpage">
Sample IGSN =  <xsl:value-of select="n:sampleIGSN"/>
<xsl:text> </xsl:text>
<a><xsl:attribute name="href">viewid.php?id=<xsl:value-of select="n:sampleIGSN"/></xsl:attribute><xsl:attribute name="target">_blank</xsl:attribute>(Detail)</a>
</div>
<div class="aboutpage">
Aliquot IGSN =  <xsl:value-of select="n:aliquotIGSN"/>
<xsl:text> </xsl:text>
<a><xsl:attribute name="href">viewid.php?id=<xsl:value-of select="n:aliquotIGSN"/></xsl:attribute><xsl:attribute name="target">_blank</xsl:attribute>(Detail)</a>
</div>
<!--
Aliquot Unique Identifier =  <xsl:value-of select="n:aliquotIGSN"/><br/>
-->
Aliquot Name = <xsl:value-of select="n:aliquotName"/><br/>
Analysis Purpose = <xsl:value-of select="n:analysisPurpose"/><br/>
Laboratory Name =  <xsl:value-of select="n:laboratoryName"/><br/>
Analyst Name = <xsl:value-of select="n:analystName"/><br/>
Aliquot Reference = <xsl:value-of select="n:aliquotReference"/><br/>
Aliquot Instrumental Method =  <xsl:value-of select="n:aliquotInstrumentalMethod"/><br/>
Aliquot Instrumental Method Reference = <xsl:value-of select="n:aliquotInstrumentalMethodReference"/><br/>
Calibration Uncertainty 206/238 = <xsl:value-of select="n:calibrationUnct206-238"/><br/>
Calibration Uncertainty 208/232 = <xsl:value-of select="n:calibrationUnct208-232"/><br/>
CalibrationUncertainty 207/206 = <xsl:value-of select="n:calibrationUnct207-206"/><br/>
Aliquot Comment = <xsl:value-of select="n:aliquotComment"/><br/>



<xsl:apply-templates select="n:mySESARSampleMetadata"/>



Keywords: <xsl:value-of select="n:keyWordsCSV"/><br/>

<xsl:apply-templates select="n:analysisImages"/>


</div><br/>

<xsl:apply-templates select="n:sampleAgeInterpretations"/>

<xsl:apply-templates select="n:physicalConstants"/>

<xsl:apply-templates select="n:mineralStandardModels"/>

<xsl:apply-templates select="n:sampleDateModels"/>



<xsl:apply-templates select="n:pbBlanks"/>

<xsl:apply-templates select="n:tracers"/>



<xsl:apply-templates select="n:alphaPbModels"/><br/>

<xsl:apply-templates select="n:alphaUModels"/>


<xsl:apply-templates select="n:standardMineral"/>

<xsl:apply-templates select="n:analysisFractions"/>


<br/>

<xsl:apply-templates select="n:physicalConstantsModel"/><br/>



  </xsl:template>

<xsl:template match="n:analysisImages">
	<div>
	<xsl:attribute name="class">aboutpage</xsl:attribute>
	<br/>
	<xsl:apply-templates select="n:AnalysisImage"/><br/>
	</div>

</xsl:template>

<xsl:template match="n:AnalysisImage">
	<xsl:if test="n:imageURL!=''">
	<a>
	<xsl:attribute name="href"><xsl:value-of select="n:imageURL"/></xsl:attribute>
	<xsl:attribute name="target">_blank</xsl:attribute>
	Attachment: <xsl:value-of select="n:imageType"/>
	</a>
	<br/>
	</xsl:if>
</xsl:template>

<xsl:template match="n:mySESARSampleMetadata">
	
	<br/>
	<div class="headline">Sample Metadata</div>
	Stratigraphic Formation Name = <xsl:value-of select="n:stratigraphicFormationName"/><br/>
	Stratigraphic Geologic Age (Ma) = <xsl:value-of select="n:stratigraphicGeologicAgeMa"/><br/>
	Stratigraphic Minimum Absolute Age (Ma) = <xsl:value-of select="n:stratigraphicMinAbsoluteAgeMa"/><br/>
	Stratigraphic Maximum Absolute Age (Ma) = <xsl:value-of select="n:stratigraphicMaxAbsoluteAgeMa"/><br/>
	Detrital Type = <xsl:value-of select="n:detritalType"/><br/>
</xsl:template>

<xsl:template match="n:sampleAgeInterpretations">
	<div class="headline">Sample Age Interpretations</div>
	<div class="box_one">
	<xsl:apply-templates select="n:SampleAge"/>
	</div><br/>
</xsl:template>


<xsl:template match="n:SampleAge">
	<div class="box_two">
	Sample Age Type =  <xsl:value-of select="n:sampleAgeType"/><br/>
	Sample Age Value =  <xsl:value-of select="n:sampleAgeValue"/><br/>
	Analytical Sample Age Error =  <xsl:value-of select="n:sampleAgeErrorAnalytical"/><br/>
	Sample Age Mean Squared Weighted Deviation =  <xsl:value-of select="n:sampleAgeMeanSquaredWeightedDeviation"/><br/>
	Systematic Sample Age Error =  <xsl:value-of select="n:sampleAgeErrorSystematic"/><br/>
	Sample Age Explanation =  <xsl:value-of select="n:sampleAgeExplanation"/><br/>
	SampleAgeComment =  <xsl:value-of select="n:sampleAgeComment"/>
	</div><br/>
</xsl:template>



<xsl:template match="n:physicalConstants">
	<div class="headline">Physical Constants</div>
	<div class="box_one">
	<xsl:apply-templates select="n:atomicMolarMasses"/>
	<xsl:apply-templates select="n:measuredConstants"/>
	<div class="box_two">
	Decay Constants Reference =  <xsl:value-of select="n:decayConstantsReference"/><br/>
	Physical Constants Comment =  <xsl:value-of select="n:physicalConstantsComment"/><br/>
	</div>
	</div><br/>
</xsl:template>


<xsl:template match="n:atomicMolarMasses">
	<div class="headline">Atomic Molar Masses</div>
	<div class="box_two">
	<table class="sample">
	<tr>
	<th>Atomic Molar Mass Name</th>
	<th>Atomic Molar Mass Value</th>
	</tr>
	<xsl:apply-templates select="n:AtomicMolarMass"/>
	</table>
	</div><br/>
</xsl:template>


<xsl:template match="n:AtomicMolarMass">
	<tr>
	<td><xsl:value-of select="n:atomicMolarMassName"/></td>
	<td><xsl:value-of select="n:atomicMolarMassValue"/></td>
	</tr>
</xsl:template>


<xsl:template match="n:measuredConstants">
	<div class="headline">Measured Constants</div>
	<div class="box_two">
	<table class="sample">
	<tr>
	<th>Measured Constant Name</th>
	<th>Measured Constant Value</th>
	<th>Measured Constant Error</th>
	</tr>
	<xsl:apply-templates select="n:MeasuredConstant"/>
	</table>
	</div><br/>
</xsl:template>


<xsl:template match="n:MeasuredConstant">
	<tr>
	<td><xsl:value-of select="n:measuredConstantName"/></td>
	<td><xsl:value-of select="n:measuredConstantValue"/></td>
	<td><xsl:value-of select="n:measuredConstantError"/></td>
	</tr>
</xsl:template>



<xsl:template match="n:sampleDateModels">
	<xsl:param name="formatter" />
	<xsl:if test="count(n:SampleDateModel) > 0">
		<div class="headline">Sample Date Models</div>
		<div class="box_one">
		<xsl:apply-templates select="n:SampleDateModel">
			<xsl:with-param name="formatter" select="$formatter"/>
		</xsl:apply-templates>
		</div><br/>
	</xsl:if>
</xsl:template>


<xsl:template match="n:SampleDateModel">
	<xsl:param name="formatter" />
	<div class="box_two">
	<div class="fatlink">Sample Date Model Name: <xsl:value-of select="n:name"/></div>
	Value: <xsl:value-of select="n:value"/><br/>
	Uncertainty Type: <xsl:value-of select="n:uncertaintyType"/><br/>
	One Sigma: <xsl:value-of select="n:oneSigma"/><br/>
	Mean Squared Weighted Deviation: <xsl:value-of select="n:meanSquaredWeightedDeviation"/><br/>
	Internal Two Sigma Uncertainty: <xsl:value-of select="n:internalTwoSigmaUnct"/><br/>
	PlusInternal Two Sigma Uncertainty: <xsl:value-of select="n:plusInternalTwoSigmaUnct"/><br/>
	Minus Internal Two Sigma Uncertainty: <xsl:value-of select="n:minusInternalTwoSigmaUnct"/><br/>
	Internal Two Sigma Uncertainty with Tracer Calibration Uncertainty: <xsl:value-of select="n:internalTwoSigmaUnctWithTracerCalibrationUnct"/><br/>
	Plus Internal Two Sigma Uncertainty with Tracer Calibration Uncertainty: <xsl:value-of select="n:plusInternalTwoSigmaUnctWithTracerCalibrationUnct"/><br/>
	Minus Internal Two Sigma Uncertainty with Tracer Calibration Uncertainty: <xsl:value-of select="n:minusInternalTwoSigmaUnctWithTracerCalibrationUnct"/><br/>
	Internal Two Sigma Uncertainty with Tracer Calibration and Decay Constant Uncertainty: <xsl:value-of select="n:internalTwoSigmaUnctWithTracerCalibrationAndDecayConstantUnct"/><br/>
	Plus Internal Two Sigma Uncertainty with Tracer Calibration and Decay Constant Uncertainty: <xsl:value-of select="n:plusInternalTwoSigmaUnctWithTracerCalibrationAndDecayConstantUnct"/><br/>
	Minus Internal Two Sigma Uncertainty with Tracer Calibration and Decay Constant Uncertainty: <xsl:value-of select="n:minusInternalTwoSigmaUnctWithTracerCalibrationAndDecayConstantUnct"/><br/>
	<xsl:apply-templates select="n:includedFractionsVector"/>
	Explanation: <xsl:value-of select="n:explanation"/><br/>
	Comment: <xsl:value-of select="n:comment"/><br/>
	Preferred: <xsl:value-of select="n:preferred"/><br/>
	</div><br/>
</xsl:template>

<xsl:template match="n:includedFractionsVector">
	<br/>
	<div class="headline">Included Fractions Vector:</div>
	<div class="box_one">
	<xsl:apply-templates select="n:fractionID"/>
	</div><br/>
</xsl:template>

<xsl:template match="n:fractionID">
	<xsl:apply-templates /><br/>
</xsl:template>

<xsl:template match="n:mineralStandardModels">
	<xsl:if test="count(n:MineralStandardModel) > 0">
		<div class="headline">Mineral Standard Models</div>
		<div class="box_one">
		<xsl:apply-templates select="n:MineralStandardModel"/>
		</div><br/>
	</xsl:if>
</xsl:template>


<xsl:template match="n:MineralStandardModel">
	<div class="box_two">
	<div class="fatlink">Mineral Standard Model Name: <xsl:value-of select="n:name"/></div>
	Mineral Standard Name: <xsl:value-of select="n:mineralStandardName"/><br/>
	Standard Mineral Name: <xsl:value-of select="n:standardMineralName"/><br/>
	<xsl:apply-templates select="n:trueAge"/>
	<xsl:apply-templates select="n:measuredAge"/>






	</div><br/>
</xsl:template>

<xsl:template match="n:trueAge">
True Age: <xsl:value-of select="n:value"/> Uncertainty: <xsl:value-of select="n:oneSigma"/> (<xsl:value-of select="n:uncertaintyType"/>) Reference: <xsl:value-of select="n:reference"/><br/>
</xsl:template>

<xsl:template match="n:measuredAge">
Measured Age: <xsl:value-of select="n:value"/> Uncertainty: <xsl:value-of select="n:oneSigma"/> (<xsl:value-of select="n:uncertaintyType"/>) Reference: <xsl:value-of select="n:reference"/><br/>
</xsl:template>



<xsl:template match="n:pbBlanks">
	<xsl:if test="count(n:PbBlank) > 0">
		<div class="headline">Pb Blanks</div>
		<div class="box_one">
		<xsl:apply-templates select="n:PbBlank"/>
		</div><br/>
	</xsl:if>
</xsl:template>


<xsl:template match="n:PbBlank">
	<div class="fatlink">Blank Name: <xsl:value-of select="n:name"/></div>
	<div class="box_two">
	<table class="sample">
	<tr>
	<th>Name</th>
	<th>Value</th>
	<th>Uncertainty Type</th>
	<th>One Sigma</th>
	</tr>
	<xsl:apply-templates select="n:ratios"/>
	</table><br/>
	<table class="sample">
	<tr>
	<th>Name</th>
	<th>Value</th>
	<th>Uncertainty Type</th>
	<th>One Sigma</th>
	</tr>
	<xsl:apply-templates select="n:rhoCorrelations"/>
	</table>
	</div><br/>
</xsl:template>


<xsl:template match="n:PbBlank/n:ratios">
	<xsl:apply-templates select="n:ValueModel"/>
</xsl:template>


<xsl:template match="n:PbBlankRatio">
	<tr>
	<td><xsl:value-of select="n:ratioName"/></td>
	<td><xsl:call-template name="format" ><xsl:with-param name="number" select="n:ratioValue"/></xsl:call-template></td>
	<td><xsl:value-of select="n:oneSigmaAbsoluteStdErr"/></td>
	</tr>
</xsl:template>


<xsl:template match="/n:rhoCorrelations">
	<xsl:apply-templates select="n:ValueModel"/>
</xsl:template>


<xsl:template match="n:PbBlankRhoCorrelation">
	<tr>
	<td><xsl:value-of select="n:correlationName"/></td>
	<td><xsl:value-of select="n:correlationValue"/></td>
	</tr>
</xsl:template>


<xsl:template match="n:tracers">
	<xsl:if test="count(n:Tracer) > 0">
		<div class="headline">Tracers</div>
		<div class="box_one">
		<xsl:apply-templates select="n:Tracer"/>
		</div><br/>
	</xsl:if>
</xsl:template>


<xsl:template match="n:Tracer">
	<div class="box_two">
	<xsl:apply-templates select="n:version"/>

	<div class="box_two">
	<div class="fatlink">Tracer Name: <xsl:value-of select="n:tracerName"/></div>
	Version Number = <xsl:value-of select="n:versionNumber"/><br/>
	Tracer Type = <xsl:value-of select="n:tracerType"/><br/>
	Lab Name = <xsl:value-of select="n:labName"/><br/>
	Date Certified = <xsl:value-of select="n:dateCertified"/>
	</div><br/>

	<table class="sample">
	<tr>
	<th>Name</th>
	<th>Value</th>
	<th>Uncertainty Type</th>
	<th>One Sigma</th>
	</tr>
	<xsl:apply-templates select="n:ratios"/>
	</table><br/>
	<table class="sample">
	<tr>
	<th>Name</th>
	<th>Value</th>
	<th>Uncertainty Type</th>
	<th>One Sigma</th>
	</tr>
	<xsl:apply-templates select="n:isotopeConcentrations"/>
	</table>
	</div><br/>
</xsl:template>





<xsl:template match="n:version">
	<div class="box_two">
	<div class="fatlink">Tracer Name: <xsl:value-of select="n:tracerName"/></div>
	Version Number = <xsl:value-of select="n:versionNumber"/><br/>
	Tracer Type = <xsl:value-of select="n:tracerType"/><br/>
	Lab Name = <xsl:value-of select="n:labName"/><br/>
	Date Certified = <xsl:value-of select="n:dateCertified"/>
	</div><br/>
</xsl:template>


<xsl:template match="n:Tracer/n:ratios">
	<xsl:apply-templates select="n:ValueModel"/>
</xsl:template>


<xsl:template match="n:tracerRatio">
	<tr>
	<td><xsl:value-of select="n:ratioName"/></td>
	<td><xsl:value-of select="n:ratioValue"/></td>
	<td><xsl:value-of select="n:oneSigmaPCTrelativeUncert"/></td>
	</tr>
</xsl:template>


<xsl:template match="/n:isotopeConcentrations">
	<xsl:apply-templates select="n:tracerIsotope"/>
</xsl:template>


<xsl:template match="n:tracerIsotope">
	<tr>
	<td><xsl:value-of select="n:isotopeConcName"/></td>
	<td><xsl:value-of select="n:molesPerGramValue"/></td>
	<td><xsl:value-of select="n:oneSigmaPCTrelativeUncert"/></td>
	</tr>
</xsl:template>


<xsl:template match="n:standardMineral">
	<div class="headline">Standard Mineral</div>
	<div class="box_one">
	<div class="box_two">
	Standard Mineral Name = <xsl:value-of select="n:standardMineralName"/><br/>
	Standard Mineral Reference = <xsl:value-of select="n:standardMineralReference"/><br/>
	Standard Mineral True Age = <xsl:value-of select="n:standardMineralTrueAge"/><br/>
	Standard Mineral True Age Error = <xsl:value-of select="n:standardMineralTrueAgeError"/><br/>
	Standard Mineral Measured Age = <xsl:value-of select="n:standardMineralMeasuredAge"/><br/>
	Standard Mineral Measured Age Error = <xsl:value-of select="n:standardMineralMeasuredAgeError"/><br/>
	Standard Mineral Comment = <xsl:value-of select="n:standardMineralComment"/>
	</div>
	</div><br/>
</xsl:template>


<xsl:template match="n:alphaPbModels">
	<xsl:if test="count(n:ValueModel) > 0">
		<div>
		<div class="headline">Alph Pb Models:</div>
		<table class="sample">
		<tr>
		<th>Name</th>
		<th>Value</th>
		<th>Uncertainty Type</th>
		<th>One Sigma</th>
		</tr>
		<xsl:apply-templates select="n:ValueModel"/>
		</table>
		</div><br/>
	</xsl:if>
</xsl:template>

<xsl:template match="n:alphaUModels">
	<xsl:if test="count(n:ValueModel) > 0">
		<div>
		<div class="headline">alphUModels:</div>
		<table class="sample">
		<tr>
		<th>Name</th>
		<th>Value</th>
		<th>Uncertainty Type</th>
		<th>One Sigma</th>
		</tr>
		<xsl:apply-templates select="n:ValueModel"/>
		</table>
		</div><br/>
	</xsl:if>
</xsl:template>















<xsl:template match="n:analysisFractions">
	<xsl:param name="formatter"/>
	<div class="headline">Analysis Fractions</div>
	<div class="box_one">
	<xsl:apply-templates select="n:AnalysisFraction"/>
	</div>
</xsl:template>



<xsl:template match="n:AnalysisFraction">
	<div class="box_two">
	

	
	<div class="headline">Sample Name = <xsl:value-of select="n:sampleName"/></div>
	Legacy = <xsl:value-of select="n:isLegacy"/><br/>
	Fraction ID = <xsl:value-of select="n:fractionID"/><br/>
	Grain ID = <xsl:value-of select="n:grainID"/><br/>
	Zircon = <xsl:value-of select="n:zircon"/><br/>
	<xsl:if test="n:imageURL/@URL!='http://' and n:imageURL/@URL!=''">
	Image: <xsl:call-template name="showimage" ><xsl:with-param name="input" select="n:imageURL/@URL"/></xsl:call-template><br/>
	</xsl:if>
	Time Stamp = <xsl:value-of select="n:timeStamp"/><br/>
	Mineral Name = <xsl:value-of select="n:mineralName"/><br/>
	Setting Type = <xsl:value-of select="n:settingType"/><br/>
	Number of Grains = <xsl:value-of select="n:numberOfGrains"/><br/>
	Estimated Date = <xsl:call-template name="format" ><xsl:with-param name="number" select="n:estimatedDate"/></xsl:call-template><br/>
	Stacey Kramers One Percent Uncertainty = <xsl:value-of select="n:staceyKramersOnePctUnct"/><br/>
	Stacey Kramers Correlation Coefficients = <xsl:value-of select="n:staceyKramersCorrelationCoeffs"/><br/>
	Physically Abraded = <xsl:value-of select="n:physicallyAbraded"/><br/>
	Leached in HF Acid = <xsl:value-of select="n:leachedInHFAcid"/><br/>
	Annealed and Chemically Abraded = <xsl:value-of select="n:annealedAndChemicallyAbraded"/><br/>
	Chemically Purified UPb = <xsl:value-of select="n:chemicallyPurifiedUPb"/><br/>
	Analysis Fraction Comment = <xsl:value-of select="n:analysisFractionComment"/><br/>
	Pb Blank ID = <xsl:value-of select="n:pbBlankID"/><br/>
	TracerID = <xsl:value-of select="n:tracerID"/><br/>
	Fractionation Corrected Pb = <xsl:value-of select="n:fractionationCorrectedPb"/><br/>
	Alpha Pb Model ID = <xsl:value-of select="n:alphaPbModelID"/><br/>
	Fractionation Corrected U = <xsl:value-of select="n:fractionationCorrectedU"/><br/>
	Alpha U Model ID = <xsl:value-of select="n:alphaUModelID"/><br/>

	<xsl:apply-templates select="n:initialPbModel"/><br/>

	Pb Collector Type = <xsl:value-of select="n:pbCollectorType"/><br/>

	U Collector Type = <xsl:value-of select="n:uCollectorType"/><br/><br/>

	<xsl:apply-templates select="n:analysisMeasures"/><br/>

	<xsl:apply-templates select="n:measuredRatios"/><br/>
	<xsl:apply-templates select="n:radiogenicIsotopeRatios"/><br/>
	<xsl:apply-templates select="n:radiogenicIsotopeDates"/><br/>
	<xsl:apply-templates select="n:compositionalMeasures"/><br/>
	<xsl:apply-templates select="n:sampleIsochronRatios"/><br/>
	<br/>
	</div>
</xsl:template>


<xsl:template match="n:initialPbModel">
	<br/>
	<div class="headline">Initial Pb Model Name</div><xsl:value-of select="n:name"/><br/><br/>
	<div class="headline">Initial Pb Model Reference</div><xsl:value-of select="n:reference"/><br/><br/>
	<xsl:apply-templates select="n:ratios"/>
</xsl:template>


<xsl:template match="n:ratios">
	<div class="box_one">
	<div class="headline">Initial Pb Model Ratios</div>
	<div class="box_two">
	<table class="sample">
	<tr>
	<th>Name</th>
	<th>Value</th>
	<th>Uncertainty Type</th>
	<th>One Sigma</th>
	</tr>
	<xsl:apply-templates select="n:ValueModel"/>
	</table>
	</div>
	</div>
</xsl:template>


<xsl:template match="n:analysisMeasures">
	<xsl:if test="count(n:ValueModel) > 0">
		<div class="box_one">
		<div class="headline">Analysis Measures</div>
		<div class="box_two">
		<table class="sample">
		<tr>
		<th>Name</th>
		<th>Value</th>
		<th>Uncertainty Type</th>
		<th>One Sigma</th>
		</tr>
		<xsl:apply-templates select="n:ValueModel"/>
		</table>
		</div>
		</div>
	</xsl:if>
</xsl:template>


<xsl:template match="n:ValueModel">
	<xsl:choose>
	<xsl:when test="local-name(parent::*)='radiogenicIsotopeDates'">
		<tr>
		<td><xsl:value-of select="n:name"/></td>
		<xsl:choose>
			<xsl:when test="n:name != 'percentDiscordance'">
				<td><xsl:call-template name="dateformat" ><xsl:with-param name="number" select="n:value"/></xsl:call-template> Ma</td>
			</xsl:when>
			<xsl:otherwise>
				<td><xsl:call-template name="format" ><xsl:with-param name="number" select="n:value"/></xsl:call-template></td>
			</xsl:otherwise>
		</xsl:choose>
		<xsl:choose>
			<xsl:when test="n:uncertaintyType != 'NONE'">
				<td>
					<xsl:value-of select="n:uncertaintyType"/>
				</td>
			</xsl:when>
			<xsl:otherwise>
				<td></td>
			</xsl:otherwise>
		</xsl:choose>
		<xsl:choose>
			<xsl:when test="n:uncertaintyType != 'NONE'">
				<td>
					<xsl:call-template name="icpmsdateformat" ><xsl:with-param name="number" select="n:oneSigma"/></xsl:call-template> Ma
				</td>
			</xsl:when>
			<xsl:otherwise>
				<td></td>
			</xsl:otherwise>
		</xsl:choose>
		</tr>
	</xsl:when>
	<xsl:otherwise>
		<tr>
		<td><xsl:value-of select="n:name"/></td>
		<td><xsl:call-template name="format" ><xsl:with-param name="number" select="n:value"/></xsl:call-template></td>
		<td><xsl:value-of select="n:uncertaintyType"/></td>
		<td><xsl:call-template name="icpmsformat" ><xsl:with-param name="number" select="n:oneSigma"/></xsl:call-template></td>
		</tr>
	</xsl:otherwise>
	</xsl:choose>
</xsl:template>

<xsl:template match="n:dateValueModel">
	<tr>
	<td><xsl:value-of select="n:name"/></td>
	<td><xsl:call-template name="dateformat" ><xsl:with-param name="number" select="n:value"/></xsl:call-template></td>
	<td><xsl:value-of select="n:uncertaintyType"/></td>
	<td><xsl:call-template name="icpmsdateformat" ><xsl:with-param name="number" select="n:oneSigma"/></xsl:call-template></td>
	</tr>
</xsl:template>


<xsl:template match="n:measuredRatios">
	<xsl-param name="formatter"/>
	<xsl:if test="count(n:MeasuredRatioModel) > 0">
		<div class="box_one">
		<div class="headline">Measured Ratios</div>
		<div class="box_two">
		<table class="sample">
		<tr>
		<th>Name</th>
		<th>Value</th>
		<th>Uncertainty Type</th>
		<th>One Sigma</th>
		</tr>
		<xsl:apply-templates select="n:MeasuredRatioModel"/>
		</table>
		</div>
		</div>
	</xsl:if>
</xsl:template>


<xsl:template match="n:MeasuredRatioModel">
	<tr>
	<td><xsl:value-of select="n:name"/></td>
	<td><xsl:call-template name="format" ><xsl:with-param name="number" select="n:value"/></xsl:call-template></td>
	<td><xsl:value-of select="n:uncertaintyType"/></td>
	<td><xsl:call-template name="icpmsformat" ><xsl:with-param name="number" select="n:oneSigma"/></xsl:call-template></td>
	</tr>
</xsl:template>


<xsl:template match="n:radiogenicIsotopeRatios">
	<xsl:if test="count(n:ValueModel) > 0">
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
		<xsl:apply-templates select="n:ValueModel"/>
		</table>
		</div>
		</div>
	</xsl:if>
</xsl:template>



<xsl:template match="n:radiogenicIsotopeDates">
	<xsl:if test="count(n:ValueModel) > 0">
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
		<xsl:apply-templates select="n:ValueModel"/>
		</table>
		</div>
		</div>
	</xsl:if>
</xsl:template>




<xsl:template match="n:compositionalMeasures">
	<xsl:if test="count(n:ValueModel) > 0">
		<div class="box_one">
		<div class="headline">Compositional Measures</div>
		<div class="box_two">
		<table class="sample">
		<tr>
		<th>Name</th>
		<th>Value</th>
		<th>Uncertainty Type</th>
		<th>One Sigma</th>
		</tr>
		<xsl:apply-templates select="n:ValueModel"/>
		</table>
		</div>
		</div>
	</xsl:if>
</xsl:template>




<xsl:template match="n:sampleIsochronRatios">
	<xsl:if test="count(n:ValueModel) > 0">
		<div class="box_one">
		<div class="headline">Sample Isochron Ratios</div>
		<div class="box_two">
		<table class="sample">
		<tr>
		<th>Name</th>
		<th>Value</th>
		<th>Uncertainty Type</th>
		<th>One Sigma</th>
		</tr>
		<xsl:apply-templates select="n:ValueModel"/>
		</table>
		</div>
		</div>
	</xsl:if>
</xsl:template>



<xsl:template match="n:RadiogenicIsotopeRatio">
	<tr>
	<td><xsl:value-of select="n:ratioName"/></td>
	<td><xsl:call-template name="format" ><xsl:with-param name="number" select="n:ratioValue"/></xsl:call-template></td>
	<td><xsl:call-template name="format" ><xsl:with-param name="number" select="n:ratioError"/></xsl:call-template></td>
	<td><xsl:call-template name="format" ><xsl:with-param name="number" select="n:ratioAge"/></xsl:call-template></td>
	<td><xsl:call-template name="format" ><xsl:with-param name="number" select="n:ratioAgeError"/></xsl:call-template></td>
	</tr>
</xsl:template>


<xsl:template match="n:physicalConstantsModel">
	<div class="fatlink">Physical Constants</div>
	<div class="box_two">
	<div class="fatlink">Physical Constant Name: <xsl:value-of select="n:name"/></div>
	Atomic Molar Masses<br/>
	<table class="sample">
	<tr>
	<th>Name</th>
	<th>Value</th>
	<th>Uncertainty Type</th>
	<th>One Sigma</th>
	</tr>
	<xsl:apply-templates select="n:atomicMolarMasses"/>
	</table><br/>
	Measured Constants
	<table class="sample">
	<tr>
	<th>Name</th>
	<th>Value</th>
	<th>Uncertainty Type</th>
	<th>One Sigma</th>
	<th>Reference</th>
	</tr>
	<xsl:apply-templates select="n:measuredConstants"/>
	</table><br/>
	Comment: <xsl:value-of select="n:physicalConstantsComment"/>
	</div><br/>
</xsl:template>


<xsl:template match="n:atomicMolarMasses">
	<xsl:apply-templates select="n:ValueModel"/>
</xsl:template>


<xsl:template match="n:measuredConstants">
	<xsl:apply-templates select="n:ValueModelReferenced"/>
</xsl:template>


<xsl:template match="n:ValueModelReferenced">
	<tr>
	<td><xsl:value-of select="n:name"/></td>
	<td><xsl:call-template name="format" ><xsl:with-param name="number" select="n:value"/></xsl:call-template></td>
	<td><xsl:value-of select="n:uncertaintyType"/></td>
	<td><xsl:call-template name="format" ><xsl:with-param name="number" select="n:oneSigma"/></xsl:call-template></td>
	<td><xsl:value-of select="n:reference"/></td>
	</tr>
</xsl:template>



</xsl:stylesheet>
