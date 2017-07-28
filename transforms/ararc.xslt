<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:n="http://matisse.kgs.ku.edu/arar/schema"
                >

<xsl:import href="http://www.geochronportal.org/EarthTimeOrgCommonTransforms.xslt"/>

<xsl:output method="html"/>

<xsl:template match="n:ArArModel">
<xsl:apply-templates select="n:Sample"/>
</xsl:template>

<xsl:template match="n:Sample">
<div class="headline">Sample Details</div>
<div class="box_two">

<xsl:if test="@sampleID!=''"><b>Sample ID</b>: <xsl:value-of select="@sampleID"/><br/></xsl:if>
<xsl:if test="@sampleOtherName!=''"><b>Other Sample Name</b>: <xsl:value-of select="@sampleOtherName"/><br/></xsl:if>
<xsl:if test="@igsn!=''"><b>IGSN</b>: <xsl:value-of select="@igsn"/><br/></xsl:if>
<xsl:if test="@longitude!=''"><b>Longitude</b>: <xsl:value-of select="@longitude"/><br/></xsl:if>
<xsl:if test="@latitude!=''"><b>Latitude</b>: <xsl:value-of select="@latitude"/><br/></xsl:if>
<xsl:if test="@analystName!=''"><b>Analyst Name</b>: <xsl:value-of select="@analystName"/><br/></xsl:if>



<xsl:apply-templates select="n:PreferredAge"/><br/>
<xsl:apply-templates select="n:InterpretedAges"/><br/><br/>
<xsl:apply-templates select="n:Parameters"/>
</div><br/>

<xsl:apply-templates select="n:physicalConstants"/>
<xsl:apply-templates select="n:mineralStandardModels"/>
<xsl:apply-templates select="n:sampleAgeModels"/>
<xsl:apply-templates select="n:pbBlanks"/>
<xsl:apply-templates select="n:tracers"/>
<xsl:apply-templates select="n:standardMineral"/>
<xsl:apply-templates select="n:analysisFractions"/><br/>
<xsl:apply-templates select="n:physicalConstantsModel"/><br/>
</xsl:template>


<xsl:template match="n:PreferredAge">
<br/>
<div class="headline">Preferred Age</div>
<div class="box_two">

<xsl:if test="@preferredAge!=''"><b>Value</b>: <xsl:value-of select="@preferredAge"/><br/></xsl:if>
<xsl:if test="@preferredAgeSigma!=''"><b>Sigma</b>: <xsl:value-of select="@preferredAgeSigma"/><br/></xsl:if>
<xsl:if test="@preferredAgeSigmaInternal!=''"><b>Sigma Internal</b>: <xsl:value-of select="@preferredAgeSigmaInternal"/><br/></xsl:if>
<xsl:if test="@preferredAgeSigmaExternal!=''"><b>Sigma External</b>: <xsl:value-of select="@preferredAgeSigmaExternal"/><br/></xsl:if>
<xsl:if test="@preferredAgeType!=''"><b>Type</b>: <xsl:value-of select="@preferredAgeType"/><br/></xsl:if>
<xsl:if test="@preferredAgeClassification!=''"><b>Classification</b>: <xsl:value-of select="@preferredAgeClassification"/><br/></xsl:if>
<xsl:if test="@preferredAgeReference!=''"><b>Reference</b>: <xsl:value-of select="@preferredAgeReference"/><br/></xsl:if>
<xsl:if test="@preferredAgeDescription!=''"><b>Description</b>: <xsl:value-of select="@preferredAgeDescription"/><br/></xsl:if>

<table>
<tr><td valign="top">Experiments Included:</td><td>
<xsl:apply-templates select="ExperimentsIncluded"/>
</td></tr></table>
</div>
</xsl:template>

<xsl:template match="ExperimentsIncluded">
<xsl:apply-templates select="ExperimentsIncluded/Experiment"/>
</xsl:template>

<xsl:template match="ExperimentsIncluded/Experiment">
<xsl:value-of select="@experimentIdentifier"/><br/>
</xsl:template>

<xsl:template match="n:InterpretedAges">
<xsl:apply-templates select="n:InterpretedAge"/>
</xsl:template>

<xsl:template match="n:InterpretedAge">
<div class="headline">Interpreted Age</div>
<div class="box_two">


<xsl:if test="@age!=''"><b>Value</b>: <xsl:value-of select="@age"/><br/></xsl:if>
<xsl:if test="@ageSigma!=''"><b>Sigma</b>: <xsl:value-of select="@ageSigma"/><br/></xsl:if>
<xsl:if test="@ageType!=''"><b>Type</b>: <xsl:value-of select="@ageType"/><br/></xsl:if>
<xsl:if test="@ageClassification!=''"><b>Classification</b>: <xsl:value-of select="@ageClassification"/><br/></xsl:if>
<xsl:if test="@ageReference!=''"><b>Reference</b>: <xsl:value-of select="@ageReference"/><br/></xsl:if>
<xsl:if test="@description!=''"><b>Description</b>: <xsl:value-of select="@description"/><br/></xsl:if>


<table>
<tr><td valign="top">Experiments Included:</td><td>
<xsl:apply-templates select="n:InterpretedExperimentsIncluded"/>
</td></tr> </table>
</div><br/>
</xsl:template>

<xsl:template match="n:InterpretedExperimentsIncluded">
<xsl:apply-templates select="n:InterpretedExperiment"/>
</xsl:template>

<xsl:template match="n:InterpretedExperiment">
<xsl:value-of select="@experimentIdentifier"/><br/>
</xsl:template>

<xsl:template match="n:Parameters">
<div class="headline">Parameters</div>
<div class="box_two">

<xsl:if test="@standardName!=''"><b>Standard Name</b>: <xsl:value-of select="@standardName"/><br/></xsl:if>
<xsl:if test="@standardReference!=''"><b>Standard Reference</b>: <xsl:value-of select="@standardReference"/><br/></xsl:if>
<xsl:if test="@standardMaterial!=''"><b>Standard Material</b>: <xsl:value-of select="@standardMaterial"/><br/></xsl:if>
<xsl:if test="@standardBatch!=''"><b>Standard Batch</b>: <xsl:value-of select="@standardBatch"/><br/></xsl:if>
<xsl:if test="@standardAge!=''"><b>Standard Age</b>: <xsl:value-of select="@standardAge"/><br/></xsl:if>
<xsl:if test="@standardAgeSigma!=''"><b>Standard Age Sigma</b>: <xsl:value-of select="@standardAgeSigma"/><br/></xsl:if>
<xsl:if test="@standardAge40ArAbundance!=''"><b>Standard Age 40Ar Abundance</b>: <xsl:value-of select="@standardAge40ArAbundance"/><br/></xsl:if>
<xsl:if test="@standardAge40ArAbundanceSigma!=''"><b>Standard Age 40Ar Abundance Sigma</b>: <xsl:value-of select="@standardAge40ArAbundanceSigma"/><br/></xsl:if>
<xsl:if test="@standardAgeKAbundance!=''"><b>Standard Age K Abundance</b>: <xsl:value-of select="@standardAgeKAbundance"/><br/></xsl:if>
<xsl:if test="@standardAgeKAbundanceSigma!=''"><b>Standard Age K Abundance Sigma</b>: <xsl:value-of select="@standardAgeKAbundanceSigma"/><br/></xsl:if>
<xsl:if test="@standardAge40Ar40KRatio!=''"><b>Standard Age 40Ar 40K Ratio</b>: <xsl:value-of select="@standardAge40Ar40KRatio"/><br/></xsl:if>
<xsl:if test="@standardAge40Ar40KRatioSigma!=''"><b>Standard Age 40Ar 40K Ratio Sigma</b>: <xsl:value-of select="@standardAge40Ar40KRatioSigma"/><br/></xsl:if>
<xsl:if test="@decayConstant40ArTotal!=''"><b>Decay Constant 40Ar Total</b>: <xsl:value-of select="@decayConstant40ArTotal"/><br/></xsl:if>
<xsl:if test="@decayConstant40ArTotalSigma!=''"><b>Decay Constant 40Ar Total Sigma</b>: <xsl:value-of select="@decayConstant40ArTotalSigma"/><br/></xsl:if>
<xsl:if test="@decayConstant40ArBeta!=''"><b>Decay Constant 40Ar Beta</b>: <xsl:value-of select="@decayConstant40ArBeta"/><br/></xsl:if>
<xsl:if test="@decayConstant40ArBetaSigma!=''"><b>Decay Constant 40Ar Beta Sigma</b>: <xsl:value-of select="@decayConstant40ArBetaSigma"/><br/></xsl:if>
<xsl:if test="@decayConstant40ArElectron!=''"><b>Decay Constant 40Ar Electron</b>: <xsl:value-of select="@decayConstant40ArElectron"/><br/></xsl:if>
<xsl:if test="@decayConstant40ArElectronSigma!=''"><b>Decay Constant 40Ar Electron Sigma</b>: <xsl:value-of select="@decayConstant40ArElectronSigma"/><br/></xsl:if>
<xsl:if test="@activity40ArBeta!=''"><b>Activity 40Ar Beta</b>: <xsl:value-of select="@activity40ArBeta"/><br/></xsl:if>
<xsl:if test="@activity40ArBetaSigma!=''"><b>Activity 40Ar Beta Sigma</b>: <xsl:value-of select="@activity40ArBetaSigma"/><br/></xsl:if>
<xsl:if test="@activity40ArElectron!=''"><b>Activity 40Ar Electron</b>: <xsl:value-of select="@activity40ArElectron"/><br/></xsl:if>
<xsl:if test="@activity40ArElectronSigma!=''"><b>Activity 40Ar Electron Sigma</b>: <xsl:value-of select="@activity40ArElectronSigma"/><br/></xsl:if>
<xsl:if test="@avogadroNumber!=''"><b>Avogadro Number</b>: <xsl:value-of select="@avogadroNumber"/><br/></xsl:if>
<xsl:if test="@solarYear!=''"><b>Solar Year</b>: <xsl:value-of select="@solarYear"/><br/></xsl:if>
<xsl:if test="@atomicWeightK!=''"><b>Atomic Weight K</b>: <xsl:value-of select="@atomicWeightK"/><br/></xsl:if>
<xsl:if test="@atomicWeightKSigma!=''"><b>Atomic Weight K Sigma</b>: <xsl:value-of select="@atomicWeightKSigma"/><br/></xsl:if>
<xsl:if test="@abundanceRatio40KK!=''"><b>Abundance Ratio 40K K</b>: <xsl:value-of select="@abundanceRatio40KK"/><br/></xsl:if>
<xsl:if test="@abundanceRatio40KKSigma!=''"><b>AbundanceRatio 40K K Sigma</b>: <xsl:value-of select="@abundanceRatio40KKSigma"/><br/></xsl:if>
<xsl:if test="@jValue!=''"><b>J Value</b>: <xsl:value-of select="@jValue"/><br/></xsl:if>
<xsl:if test="@jValueSigma!=''"><b>J Value Sigma</b>: <xsl:value-of select="@jValueSigma"/><br/></xsl:if>
<xsl:if test="@parametersDescription!=''"><b>Parameters Description</b>: <xsl:value-of select="@parametersDescription"/><br/></xsl:if>
<br/>


<div class="headline">Experiments</div>

<div class="box_two">
<xsl:apply-templates select="n:Experiment"/>
</div>

<br/>

<div class="box_two">
<xsl:apply-templates select="n:Age"/>
</div>

</div>

</xsl:template>


<xsl:template match="n:Experiment">



<xsl:if test="@experimentIdentifier!=''"><b>Experiment Identifier</b>: <xsl:value-of select="@experimentIdentifier"/><br/></xsl:if>
<xsl:if test="@experimentType!=''"><b>Experiment Type</b>: <xsl:value-of select="@experimentType"/><br/></xsl:if>
<xsl:if test="@sampleMaterial!=''"><b>Sample Material</b>: <xsl:value-of select="@sampleMaterial"/><br/></xsl:if>
<xsl:if test="@sampleMaterialType!=''"><b>Sample Material Type</b>: <xsl:value-of select="@sampleMaterialType"/><br/></xsl:if>
<xsl:if test="@mineralName!=''"><b>Mineral Name</b>: <xsl:value-of select="@mineralName"/><br/></xsl:if>
<xsl:if test="@sampleGrainSizeFraction!=''"><b>Sample Grain Size Fraction</b>: <xsl:value-of select="@sampleGrainSizeFraction"/><br/></xsl:if>
<xsl:if test="@sampleTreatment!=''"><b>Sample Treatment</b>: <xsl:value-of select="@sampleTreatment"/><br/></xsl:if>
<xsl:if test="@sampleWeight!=''"><b>Sample Weight</b>: <xsl:value-of select="@sampleWeight"/><br/></xsl:if>
<xsl:if test="@igsn!=''"><b>IGSN</b>: <xsl:value-of select="@igsn"/><br/></xsl:if>
<xsl:if test="@projectName!=''"><b>Project Name</b>: <xsl:value-of select="@projectName"/><br/></xsl:if>
<xsl:if test="@extractionMethod!=''"><b>Extraction Method</b>: <xsl:value-of select="@extractionMethod"/><br/></xsl:if>
<xsl:if test="@massSpectrometer!=''"><b>Mass Spectrometer</b>: <xsl:value-of select="@massSpectrometer"/><br/></xsl:if>
<xsl:if test="@laboratory!=''"><b>Laboratory</b>: <xsl:value-of select="@laboratory"/><br/></xsl:if>
<xsl:if test="@laboratoryReference!=''"><b>Laboratory Reference</b>: <xsl:value-of select="@laboratoryReference"/><br/></xsl:if>
<xsl:if test="@instrumentName!=''"><b>Instrument Name</b>: <xsl:value-of select="@instrumentName"/><br/></xsl:if>
<xsl:if test="@acquisitionSoftware!=''"><b>Acquisition Software</b>: <xsl:value-of select="@acquisitionSoftware"/><br/></xsl:if>
<xsl:if test="@dataReductionSoftware!=''"><b>Data Reduction Software</b>: <xsl:value-of select="@dataReductionSoftware"/><br/></xsl:if>
<xsl:if test="@sampleDescription!=''"><b>Sample Description</b>: <xsl:value-of select="@sampleDescription"/><br/></xsl:if>
<xsl:if test="@experimentDescription!=''"><b>Experiment Description</b>: <xsl:value-of select="@experimentDescription"/><br/></xsl:if>
<br/>

<xsl:apply-templates select="n:Irradiation"/>
<br/>

<xsl:apply-templates select="n:Measurement"/>
</xsl:template>


<xsl:template match="n:Irradiation">
<div class="headline">Irradiation</div>
<div class="box_two">
<xsl:if test="@irradiationName!=''"><b>Irradiation Name</b>: <xsl:value-of select="@irradiationName"/><br/></xsl:if>
<xsl:if test="@irradiationReactorName!=''"><b>Irradiation Reactor Name</b>: <xsl:value-of select="@irradiationReactorName"/><br/></xsl:if>
<xsl:if test="@irradiationTotalDuration!=''"><b>Irradiation Total Duration</b>: <xsl:value-of select="@irradiationTotalDuration"/><br/></xsl:if>
<xsl:if test="@irradiationEndDateTime!=''"><b>Irradiation End Date Time</b>: <xsl:value-of select="@irradiationEndDateTime"/><br/></xsl:if>
<xsl:if test="@irradiationPower!=''"><b>Irradiation Power</b>: <xsl:value-of select="@irradiationPower"/><br/></xsl:if>
<xsl:if test="@irradiationSegmentList!=''"><b>Irradiation Segment List</b>: <xsl:value-of select="@irradiationSegmentList"/><br/></xsl:if>
<xsl:if test="@correction40Ar36ArAtmospheric!=''"><b>Correction 40Ar 36Ar Atmospheric</b>: <xsl:value-of select="@correction40Ar36ArAtmospheric"/><br/></xsl:if>
<xsl:if test="@correction40Ar36ArAtmosphericSigma!=''"><b>Correction 40Ar 36Ar Atmospheric Sigma</b>: <xsl:value-of select="@correction40Ar36ArAtmosphericSigma"/><br/></xsl:if>
<xsl:if test="@correction40Ar36ArCosmogenic!=''"><b>Correction 40Ar 36Ar Cosmogenic</b>: <xsl:value-of select="@correction40Ar36ArCosmogenic"/><br/></xsl:if>
<xsl:if test="@correction40Ar36ArCosmogenicSigma!=''"><b>Correction 40Ar 36Ar Cosmogenic Sigma</b>: <xsl:value-of select="@correction40Ar36ArCosmogenicSigma"/><br/></xsl:if>
<xsl:if test="@correction38Ar36ArAtmospheric!=''"><b>Correction 38Ar 36Ar Atmospheric</b>: <xsl:value-of select="@correction38Ar36ArAtmospheric"/><br/></xsl:if>
<xsl:if test="@correction38Ar36ArAtmosphericSigma!=''"><b>Correction 38Ar 36Ar Atmospheric Sigma</b>: <xsl:value-of select="@correction38Ar36ArAtmosphericSigma"/><br/></xsl:if>
<xsl:if test="@correction38Ar36ArCosmogenic!=''"><b>Correction 38Ar 36Ar Cosmogenic</b>: <xsl:value-of select="@correction38Ar36ArCosmogenic"/><br/></xsl:if>
<xsl:if test="@correction38Ar36ArCosmogenicSigma!=''"><b>Correction 38Ar 36Ar Cosmogenic Sigma</b>: <xsl:value-of select="@correction38Ar36ArCosmogenicSigma"/><br/></xsl:if>
<xsl:if test="@correction39Ar37ArCalcium!=''"><b>Correction 39Ar 37Ar Calcium</b>: <xsl:value-of select="@correction39Ar37ArCalcium"/><br/></xsl:if>
<xsl:if test="@correction39Ar37ArCalciumSigma!=''"><b>Correction 39Ar 37Ar Calcium Sigma</b>: <xsl:value-of select="@correction39Ar37ArCalciumSigma"/><br/></xsl:if>
<xsl:if test="@correction38Ar37ArCalcium!=''"><b>Correction 38Ar 37Ar Calcium</b>: <xsl:value-of select="@correction38Ar37ArCalcium"/><br/></xsl:if>
<xsl:if test="@correction38Ar37ArCalciumSigma!=''"><b>Correction 38Ar 37Ar Calcium Sigma</b>: <xsl:value-of select="@correction38Ar37ArCalciumSigma"/><br/></xsl:if>
<xsl:if test="@correction36Ar37ArCalcium!=''"><b>Correction 36Ar 37Ar Calcium</b>: <xsl:value-of select="@correction36Ar37ArCalcium"/><br/></xsl:if>
<xsl:if test="@correction36Ar37ArCalciumSigma!=''"><b>Correction 36Ar 37Ar Calcium Sigma</b>: <xsl:value-of select="@correction36Ar37ArCalciumSigma"/><br/></xsl:if>
<xsl:if test="@correction40Ar39ArPotassium!=''"><b>Correction 40Ar 39Ar Potassium</b>: <xsl:value-of select="@correction40Ar39ArPotassium"/><br/></xsl:if>
<xsl:if test="@correction40Ar39ArPotassiumSigma!=''"><b>Correction 40Ar 39Ar Potassium Sigma</b>: <xsl:value-of select="@correction40Ar39ArPotassiumSigma"/><br/></xsl:if>
<xsl:if test="@correction38Ar39ArPotassium!=''"><b>Correction 38Ar 39Ar Potassium</b>: <xsl:value-of select="@correction38Ar39ArPotassium"/><br/></xsl:if>
<xsl:if test="@correction38Ar39ArPotassiumSigma!=''"><b>Correction 38Ar 39Ar Potassium Sigma</b>: <xsl:value-of select="@correction38Ar39ArPotassiumSigma"/><br/></xsl:if>
<xsl:if test="@chlorineProductionRatio36Ar38Ar!=''"><b>Chlorine Production Ratio 36Ar 38Ar</b>: <xsl:value-of select="@chlorineProductionRatio36Ar38Ar"/><br/></xsl:if>
<xsl:if test="@chlorineProductionRatio36Ar38ArSigma!=''"><b>Chlorine Production Ratio 36Ar 38A rSigma</b>: <xsl:value-of select="@chlorineProductionRatio36Ar38ArSigma"/><br/></xsl:if>
<xsl:if test="@correctionKCa!=''"><b>Correction KCa</b>: <xsl:value-of select="@correctionKCa"/><br/></xsl:if>
<xsl:if test="@correctionKCaSigma!=''"><b>Correction KCa Sigma</b>: <xsl:value-of select="@correctionKCaSigma"/><br/></xsl:if>
<xsl:if test="@correctionKCl!=''"><b>Correction KCl</b>: <xsl:value-of select="@correctionKCl"/><br/></xsl:if>
<xsl:if test="@correctionKClSigma!=''"><b>Correction KCl Sigma</b>: <xsl:value-of select="@correctionKClSigma"/><br/></xsl:if>
<xsl:if test="@correctionCaCl!=''"><b>Correction CaCl</b>: <xsl:value-of select="@correctionCaCl"/><br/></xsl:if>
<xsl:if test="@correctionCaClSigma!=''"><b>Correction CaCl Sigma</b>: <xsl:value-of select="@correctionCaClSigma"/><br/></xsl:if>
<xsl:if test="@irradiationDescription!=''"><b>Irradiation Description</b>: <xsl:value-of select="@irradiationDescription"/><br/></xsl:if>


<xsl:apply-templates select="n:IrradiationSegments"/>

</div><br/>
</xsl:template>


<xsl:template match="n:IrradiationSegments">
<br/>
<div class="headline">Irradiation Segments</div>
<div class="box_two">
<xsl:apply-templates select="n:Segment"/>
</div>
</xsl:template>

<xsl:template match="n:Segment">
<div class="box_two">
<xsl:if test="@segmentNumber!=''"><b>Segment Number</b>: <xsl:value-of select="@segmentNumber"/><br/></xsl:if>
<xsl:if test="@segmentDuration!=''"><b>Segment Duration</b>: <xsl:value-of select="@segmentDuration"/><br/></xsl:if>
<xsl:if test="@segmentDate!=''"><b>Segment Date</b>: <xsl:value-of select="@segmentDate"/><br/></xsl:if>
<xsl:if test="@segmentEndTime!=''"><b>Segment End Time</b>: <xsl:value-of select="@segmentEndTime"/><br/></xsl:if>
<xsl:if test="@segmentPowerSetting!=''"><b>Segment Power Setting</b>: <xsl:value-of select="@segmentPowerSetting"/><br/></xsl:if>
</div>
</xsl:template>

<xsl:template match="n:Age">
<div class="headline">Age</div>
<div class="box_two">
<xsl:if test="@weightedPlateau40Ar39ArRatio!=''"><b>Weighted Plateau 40Ar 39Ar Ratio</b>: <xsl:value-of select="@weightedPlateau40Ar39ArRatio"/><br/></xsl:if>
<xsl:if test="@weightedPlateau40Ar39ArRatioSigma!=''"><b>Weighted Plateau 40Ar 39Ar Ratio Sigma</b>: <xsl:value-of select="@weightedPlateau40Ar39ArRatioSigma"/><br/></xsl:if>
<xsl:if test="@weightedPlateauAge!=''"><b>Weighted Plateau Age</b>: <xsl:value-of select="@weightedPlateauAge"/><br/></xsl:if>
<xsl:if test="@weightedPlateauAgeSigma!=''"><b>Weighted Plateau Age Sigma</b>: <xsl:value-of select="@weightedPlateauAgeSigma"/><br/></xsl:if>
<xsl:if test="@weightedPlateauAgeSigmaInternal!=''"><b>Weighted Plateau Age Sigm aInternal</b>: <xsl:value-of select="@weightedPlateauAgeSigmaInternal"/><br/></xsl:if>
<xsl:if test="@weightedPlateauAgeSigmaExternal!=''"><b>Weighted Plateau Age Sigma External</b>: <xsl:value-of select="@weightedPlateauAgeSigmaExternal"/><br/></xsl:if>
<xsl:if test="@weightedPlateauKCaRatio!=''"><b>Weighted Plateau KCa Ratio</b>: <xsl:value-of select="@weightedPlateauKCaRatio"/><br/></xsl:if>
<xsl:if test="@weightedPlateauKCaRatioSigma!=''"><b>Weighted Plateau KCa Ratio Sigma</b>: <xsl:value-of select="@weightedPlateauKCaRatioSigma"/><br/></xsl:if>
<xsl:if test="@weightedPlateauKClRatio!=''"><b>Weighted Plateau KCl Ratio</b>: <xsl:value-of select="@weightedPlateauKClRatio"/><br/></xsl:if>
<xsl:if test="@weightedPlateauKClRatioSigma!=''"><b>Weighted Plateau KCl Ratio Sigma</b>: <xsl:value-of select="@weightedPlateauKClRatioSigma"/><br/></xsl:if>
<xsl:if test="@weightedPlateauMSWD!=''"><b>Weighted Plateau MSWD</b>: <xsl:value-of select="@weightedPlateauMSWD"/><br/></xsl:if>
<xsl:if test="@weightedPlateauErrorMagnification!=''"><b>Weighted Plateau Error Magnification</b>: <xsl:value-of select="@weightedPlateauErrorMagnification"/><br/></xsl:if>
<xsl:if test="@weightedPlateauWidth!=''"><b>Weighted Plateau Width</b>: <xsl:value-of select="@weightedPlateauWidth"/><br/></xsl:if>
<xsl:if test="@weightedPlateauN!=''"><b>Weighted Plateau N</b>: <xsl:value-of select="@weightedPlateauN"/><br/></xsl:if>
<xsl:if test="@stepsInTotalGasFusion!=''"><b>Steps In Total Gas Fusion</b>: <xsl:value-of select="@stepsInTotalGasFusion"/><br/></xsl:if>
<xsl:if test="@totalGasFusion40Ar39ArRatio!=''"><b>Total Gas Fusion 40Ar 39Ar Ratio</b>: <xsl:value-of select="@totalGasFusion40Ar39ArRatio"/><br/></xsl:if>
<xsl:if test="@totalGasFusion40Ar39ArRatioSigma!=''"><b>Total Gas Fusion 40Ar 39Ar Ratio Sigma</b>: <xsl:value-of select="@totalGasFusion40Ar39ArRatioSigma"/><br/></xsl:if>
<xsl:if test="@totalGasFusionAge!=''"><b>Total Gas Fusion Age</b>: <xsl:value-of select="@totalGasFusionAge"/><br/></xsl:if>
<xsl:if test="@totalGasFusionAgeSigma!=''"><b>Total Gas Fusion Age Sigma</b>: <xsl:value-of select="@totalGasFusionAgeSigma"/><br/></xsl:if>
<xsl:if test="@totalGasFusionAgeSigmaInternal!=''"><b>Total Gas Fusion Age Sigma Internal</b>: <xsl:value-of select="@totalGasFusionAgeSigmaInternal"/><br/></xsl:if>
<xsl:if test="@totalGasFusionAgeSigmaExternal!=''"><b>Total Gas Fusion Age Sigma External</b>: <xsl:value-of select="@totalGasFusionAgeSigmaExternal"/><br/></xsl:if>
<xsl:if test="@totalGasFusionKCaRatio!=''"><b>Total Gas Fusion KCa Ratio</b>: <xsl:value-of select="@totalGasFusionKCaRatio"/><br/></xsl:if>
<xsl:if test="@totalGasFusionKCaRatioSigma!=''"><b>Total Gas Fusion KCa Ratio Sigma</b>: <xsl:value-of select="@totalGasFusionKCaRatioSigma"/><br/></xsl:if>
<xsl:if test="@totalGasFusionKClRatio!=''"><b>Total Gas Fusion KCl Ratio</b>: <xsl:value-of select="@totalGasFusionKClRatio"/><br/></xsl:if>
<xsl:if test="@totalGasFusionKClRatioSigma!=''"><b>Total Gas Fusion KCl Ratio Sigma</b>: <xsl:value-of select="@totalGasFusionKClRatioSigma"/><br/></xsl:if>
<xsl:if test="@totalGasFusionMSWD!=''"><b>Total Gas Fusion MSWD</b>: <xsl:value-of select="@totalGasFusionMSWD"/><br/></xsl:if>
<xsl:if test="@totalGasFusionErrorMagnification!=''"><b>Total Gas Fusion Error Magnification</b>: <xsl:value-of select="@totalGasFusionErrorMagnification"/><br/></xsl:if>
<xsl:if test="@totalGasFusionN!=''"><b>Total Gas FusionN</b>: <xsl:value-of select="@totalGasFusionN"/><br/></xsl:if>
<xsl:if test="@stepsInNormalIsochron!=''"><b>Steps In Normal Isochron</b>: <xsl:value-of select="@stepsInNormalIsochron"/><br/></xsl:if>
<xsl:if test="@normalIsochron40Ar39ArRatio!=''"><b>Normal Isochron 40Ar 39Ar Ratio</b>: <xsl:value-of select="@normalIsochron40Ar39ArRatio"/><br/></xsl:if>
<xsl:if test="@normalIsochron40Ar39ArRatioSigma!=''"><b>Normal Isochron 40Ar 39Ar Ratio Sigma</b>: <xsl:value-of select="@normalIsochron40Ar39ArRatioSigma"/><br/></xsl:if>
<xsl:if test="@normalIsochronAge!=''"><b>Normal Isochron Age</b>: <xsl:value-of select="@normalIsochronAge"/><br/></xsl:if>
<xsl:if test="@normalIsochronAgeSigma!=''"><b>Normal Isochron Age Sigma</b>: <xsl:value-of select="@normalIsochronAgeSigma"/><br/></xsl:if>
<xsl:if test="@normalIsochronAgeSigmaInternal!=''"><b>Normal Isochron Age Sigma Internal</b>: <xsl:value-of select="@normalIsochronAgeSigmaInternal"/><br/></xsl:if>
<xsl:if test="@normalIsochronAgeSigmaExternal!=''"><b>Normal Isochron Age Sigma External</b>: <xsl:value-of select="@normalIsochronAgeSigmaExternal"/><br/></xsl:if>
<xsl:if test="@normalIsochron40Ar36ArRatio!=''"><b>Normal Isochron 40Ar 36Ar Ratio</b>: <xsl:value-of select="@normalIsochron40Ar36ArRatio"/><br/></xsl:if>
<xsl:if test="@normalIsochron40Ar36ArRatioSigma!=''"><b>Normal Isochron 40Ar 36Ar Ratio Sigma</b>: <xsl:value-of select="@normalIsochron40Ar36ArRatioSigma"/><br/></xsl:if>
<xsl:if test="@normalIsochronMSWD!=''"><b>Normal Isochron MSWD</b>: <xsl:value-of select="@normalIsochronMSWD"/><br/></xsl:if>
<xsl:if test="@normalIsochronErrorMagnification!=''"><b>Normal Isochron Error Magnification</b>: <xsl:value-of select="@normalIsochronErrorMagnification"/><br/></xsl:if>
<xsl:if test="@normalIsochronConvergence!=''"><b>Normal Isochron Convergence</b>: <xsl:value-of select="@normalIsochronConvergence"/><br/></xsl:if>
<xsl:if test="@normalIsochronIterations!=''"><b>Normal Isochron Iterations</b>: <xsl:value-of select="@normalIsochronIterations"/><br/></xsl:if>
<xsl:if test="@normalIsochronN!=''"><b>Normal Isochron N</b>: <xsl:value-of select="@normalIsochronN"/><br/></xsl:if>
<xsl:if test="@stepsInInverseIsochron!=''"><b>Steps In Inverse Isochron</b>: <xsl:value-of select="@stepsInInverseIsochron"/><br/></xsl:if>
<xsl:if test="@inverseIsochron40Ar39ArRatio!=''"><b>Inverse Isochron 40Ar 39Ar Ratio</b>: <xsl:value-of select="@inverseIsochron40Ar39ArRatio"/><br/></xsl:if>
<xsl:if test="@inverseIsochron40Ar39ArRatioSigma!=''"><b>Inverse Isochron 40Ar 39Ar Ratio Sigma</b>: <xsl:value-of select="@inverseIsochron40Ar39ArRatioSigma"/><br/></xsl:if>
<xsl:if test="@inverseIsochronAge!=''"><b>Inverse Isochron Age</b>: <xsl:value-of select="@inverseIsochronAge"/><br/></xsl:if>
<xsl:if test="@inverseIsochronAgeSigma!=''"><b>Inverse Isochron Age Sigma</b>: <xsl:value-of select="@inverseIsochronAgeSigma"/><br/></xsl:if>
<xsl:if test="@inverseIsochronAgeSigmaInternal!=''"><b>Inverse Isochron Age Sigma Internal</b>: <xsl:value-of select="@inverseIsochronAgeSigmaInternal"/><br/></xsl:if>
<xsl:if test="@inverseIsochronAgeSigmaExternal!=''"><b>Inverse Isochron Age Sigma External</b>: <xsl:value-of select="@inverseIsochronAgeSigmaExternal"/><br/></xsl:if>
<xsl:if test="@inverseIsochron40Ar36ArRatio!=''"><b>Inverse Isochron 40Ar 36Ar Ratio</b>: <xsl:value-of select="@inverseIsochron40Ar36ArRatio"/><br/></xsl:if>
<xsl:if test="@inverseIsochron40Ar36ArRatioSigma!=''"><b>Inverse Isochron 40Ar 36Ar Ratio Sigma</b>: <xsl:value-of select="@inverseIsochron40Ar36ArRatioSigma"/><br/></xsl:if>
<xsl:if test="@inverseIsochronMSWD!=''"><b>Inverse Isochron MSWD</b>: <xsl:value-of select="@inverseIsochronMSWD"/><br/></xsl:if>
<xsl:if test="@inverseIsochronErrorMagnification!=''"><b>Inverse Isochron Error Magnification</b>: <xsl:value-of select="@inverseIsochronErrorMagnification"/><br/></xsl:if>
<xsl:if test="@inverseIsochronConvergence!=''"><b>Inverse Isochron Convergence</b>: <xsl:value-of select="@inverseIsochronConvergence"/><br/></xsl:if>
<xsl:if test="@inverseIsochronIterations!=''"><b>Inverse Isochron Iterations</b>: <xsl:value-of select="@inverseIsochronIterations"/><br/></xsl:if>
<xsl:if test="@inverseIsochronN!=''"><b>Inverse Isochron N</b>: <xsl:value-of select="@inverseIsochronN"/><br/></xsl:if>
<xsl:if test="@ageDescription!=''"><b>Age Description</b>: <xsl:value-of select="@ageDescription"/><br/></xsl:if><br/>

<xsl:apply-templates select="n:StepsInAgePlateau"/>
<xsl:apply-templates select="n:StepsInTotalGasFusion"/>
<xsl:apply-templates select="n:StepsInNormalIsochron"/>
<xsl:apply-templates select="n:StepsInInverseIsochron"/>
</div><br/>
</xsl:template>

<xsl:template match="n:StepsInAgePlateau">
<table><tr><td valign="top"><b>Steps In Age Plateau:</b></td><td>
<xsl:apply-templates select="n:Step"/>
</td></tr></table>
</xsl:template>

<xsl:template match="n:StepsInTotalGasFusion">
<table><tr><td valign="top"><b>Steps In Total Gas Fusion:</b></td><td>
<xsl:apply-templates select="n:Step"/>
</td></tr></table>
</xsl:template>

<xsl:template match="n:StepsInNormalIsochron">
<table><tr><td valign="top"><b>Steps In Normal Isochron:</b></td><td>
<xsl:apply-templates select="n:Step"/>
</td></tr></table>
</xsl:template>

<xsl:template match="n:StepsInInverseIsochron">
<table><tr><td valign="top"><b>Steps In Inverse Isochron:</b></td><td>
<xsl:apply-templates select="n:Step"/>
</td></tr></table>
</xsl:template>

<xsl:template match="n:Step">
<xsl:value-of select="@measurementNumber"/><br/>
</xsl:template>

<xsl:template match="n:Measurement">
<br/>
<div class="headline">Measurement</div>
<div class="box_two">

<xsl:if test="@measurementNumber!=''"><b>Measurement Number</b>: <xsl:value-of select="@measurementNumber"/><br/></xsl:if>
<xsl:if test="@measurementDateTime!=''"><b>Measurement Date Time</b>: <xsl:value-of select="@measurementDateTime"/><br/></xsl:if>
<xsl:if test="@temperature!=''"><b>Temperature</b>: <xsl:value-of select="@temperature"/><br/></xsl:if>
<xsl:if test="@temperatureSigma!=''"><b>Temperature Sigma</b>: <xsl:value-of select="@temperatureSigma"/><br/></xsl:if>
<xsl:if test="@temperatureUnit!=''"><b>Temperature Unit</b>: <xsl:value-of select="@temperatureUnit"/><br/></xsl:if>
<xsl:if test="@heatingDuration!=''"><b>Heating Duration</b>: <xsl:value-of select="@heatingDuration"/><br/></xsl:if>
<xsl:if test="@isolationDuration!=''"><b>Isolation Duration</b>: <xsl:value-of select="@isolationDuration"/><br/></xsl:if>
<xsl:if test="@irradiationName!=''"><b>Irradiation Name</b>: <xsl:value-of select="@irradiationName"/><br/></xsl:if>
<xsl:if test="@mdfValue!=''"><b>MDF Value</b>: <xsl:value-of select="@mdfValue"/><br/></xsl:if>
<xsl:if test="@mdfValueSigma!=''"><b>MDF Value Sigma</b>: <xsl:value-of select="@mdfValueSigma"/><br/></xsl:if>
<xsl:if test="@mdfLawApplied!=''"><b>MDF Law Applied</b>: <xsl:value-of select="@mdfLawApplied"/><br/></xsl:if>
<xsl:if test="@mdf40Ar36ArStandardRatio!=''"><b>MDF 40Ar 36Ar Standard Ratio</b>: <xsl:value-of select="@mdf40Ar36ArStandardRatio"/><br/></xsl:if>
<xsl:if test="@mdf40Ar36ArStandardRatioSigma!=''"><b>MDF 40Ar 36Ar Standard Ratio Sigma</b>: <xsl:value-of select="@mdf40Ar36ArStandardRatioSigma"/><br/></xsl:if>
<xsl:if test="@fraction40ArRadiogenic!=''"><b>Fraction 40Ar Radiogenic</b>: <xsl:value-of select="@fraction40ArRadiogenic"/><br/></xsl:if>
<xsl:if test="@fraction39ArPotassium!=''"><b>Fraction 39Ar Potassium</b>: <xsl:value-of select="@fraction39ArPotassium"/><br/></xsl:if>
<xsl:if test="@measuredAge!=''"><b>Measured Age</b>: <xsl:value-of select="@measuredAge"/><br/></xsl:if>
<xsl:if test="@measuredAgeSigma!=''"><b>Measured Age Sigma</b>: <xsl:value-of select="@measuredAgeSigma"/><br/></xsl:if>
<xsl:if test="@measuredKCaRatio!=''"><b>Measured KCa Ratio</b>: <xsl:value-of select="@measuredKCaRatio"/><br/></xsl:if>
<xsl:if test="@measuredKCaRatioSigma!=''"><b>Measured KCa Ratio Sigma</b>: <xsl:value-of select="@measuredKCaRatioSigma"/><br/></xsl:if>
<xsl:if test="@measuredKClRatio!=''"><b>Measured KCl Ratio</b>: <xsl:value-of select="@measuredKClRatio"/><br/></xsl:if>
<xsl:if test="@measuredKClRatioSigma!=''"><b>Measured KCl Ratio Sigma</b>: <xsl:value-of select="@measuredKClRatioSigma"/><br/></xsl:if>
<xsl:if test="@intercept36Ar!=''"><b>Intercept 36Ar</b>: <xsl:value-of select="@intercept36Ar"/><br/></xsl:if>
<xsl:if test="@intercept36ArSigma!=''"><b>Intercept 36Ar Sigma</b>: <xsl:value-of select="@intercept36ArSigma"/><br/></xsl:if>
<xsl:if test="@intercept36ArRegressionType!=''"><b>Intercept 36Ar Regression Type</b>: <xsl:value-of select="@intercept36ArRegressionType"/><br/></xsl:if>
<xsl:if test="@intercept37Ar!=''"><b>Intercept 37Ar</b>: <xsl:value-of select="@intercept37Ar"/><br/></xsl:if>
<xsl:if test="@intercept37ArSigma!=''"><b>Intercept 37Ar Sigma</b>: <xsl:value-of select="@intercept37ArSigma"/><br/></xsl:if>
<xsl:if test="@intercept37ArRegressionType!=''"><b>Intercept 37Ar Regression Type</b>: <xsl:value-of select="@intercept37ArRegressionType"/><br/></xsl:if>
<xsl:if test="@intercept38Ar!=''"><b>Intercept 38Ar</b>: <xsl:value-of select="@intercept38Ar"/><br/></xsl:if>
<xsl:if test="@intercept38ArSigma!=''"><b>Intercept 38Ar Sigma</b>: <xsl:value-of select="@intercept38ArSigma"/><br/></xsl:if>
<xsl:if test="@intercept38ArRegressionType!=''"><b>Intercept 38Ar Regression Type</b>: <xsl:value-of select="@intercept38ArRegressionType"/><br/></xsl:if>
<xsl:if test="@intercept39Ar!=''"><b>Intercept 39Ar</b>: <xsl:value-of select="@intercept39Ar"/><br/></xsl:if>
<xsl:if test="@intercept39ArSigma!=''"><b>Intercept 39Ar Sigma</b>: <xsl:value-of select="@intercept39ArSigma"/><br/></xsl:if>
<xsl:if test="@intercept39ArRegressionType!=''"><b>Intercept 39Ar Regression Type</b>: <xsl:value-of select="@intercept39ArRegressionType"/><br/></xsl:if>
<xsl:if test="@intercept40Ar!=''"><b>Intercept 40Ar</b>: <xsl:value-of select="@intercept40Ar"/><br/></xsl:if>
<xsl:if test="@intercept40ArSigma!=''"><b>Intercept 40Ar Sigma</b>: <xsl:value-of select="@intercept40ArSigma"/><br/></xsl:if>
<xsl:if test="@intercept40ArRegressionType!=''"><b>Intercept 40Ar Regression Type</b>: <xsl:value-of select="@intercept40ArRegressionType"/><br/></xsl:if>
<xsl:if test="@interceptUnit!=''"><b>Intercept Unit</b>: <xsl:value-of select="@interceptUnit"/><br/></xsl:if>
<xsl:if test="@blank36Ar!=''"><b>Blank 36Ar</b>: <xsl:value-of select="@blank36Ar"/><br/></xsl:if>
<xsl:if test="@blank36ArSigma!=''"><b>Blank 36Ar Sigma</b>: <xsl:value-of select="@blank36ArSigma"/><br/></xsl:if>
<xsl:if test="@blank37Ar!=''"><b>Blank 37Ar</b>: <xsl:value-of select="@blank37Ar"/><br/></xsl:if>
<xsl:if test="@blank37ArSigma!=''"><b>Blank 37Ar Sigma</b>: <xsl:value-of select="@blank37ArSigma"/><br/></xsl:if>
<xsl:if test="@blank38Ar!=''"><b>Blank 38Ar</b>: <xsl:value-of select="@blank38Ar"/><br/></xsl:if>
<xsl:if test="@blank38ArSigma!=''"><b>Blank 38Ar Sigma</b>: <xsl:value-of select="@blank38ArSigma"/><br/></xsl:if>
<xsl:if test="@blank39Ar!=''"><b>Blank 39Ar</b>: <xsl:value-of select="@blank39Ar"/><br/></xsl:if>
<xsl:if test="@blank39ArSigma!=''"><b>Blank 39Ar Sigma</b>: <xsl:value-of select="@blank39ArSigma"/><br/></xsl:if>
<xsl:if test="@blank40Ar!=''"><b>Blank 40Ar</b>: <xsl:value-of select="@blank40Ar"/><br/></xsl:if>
<xsl:if test="@blank40ArSigma!=''"><b>Blank 40Ar Sigma</b>: <xsl:value-of select="@blank40ArSigma"/><br/></xsl:if>
<xsl:if test="@blankUnit!=''"><b>Blank Unit</b>: <xsl:value-of select="@blankUnit"/><br/></xsl:if>
<xsl:if test="@correctedTotal40Ar39ArRatio!=''"><b>Corrected Total 40Ar 39Ar Ratio</b>: <xsl:value-of select="@correctedTotal40Ar39ArRatio"/><br/></xsl:if>
<xsl:if test="@correctedTotal40Ar39ArRatioSigma!=''"><b>Corrected Total 40Ar 39Ar Ratio Sigma</b>: <xsl:value-of select="@correctedTotal40Ar39ArRatioSigma"/><br/></xsl:if>
<xsl:if test="@correctedTotal37Ar39ArRatio!=''"><b>Corrected Total 37Ar3 9Ar Ratio</b>: <xsl:value-of select="@correctedTotal37Ar39ArRatio"/><br/></xsl:if>
<xsl:if test="@correctedTotal37Ar39ArRatioSigma!=''"><b>Corrected Total 37Ar 39Ar Ratio Sigma</b>: <xsl:value-of select="@correctedTotal37Ar39ArRatioSigma"/><br/></xsl:if>
<xsl:if test="@correctedTotal36Ar39ArRatio!=''"><b>Corrected Total 36Ar 39Ar Ratio</b>: <xsl:value-of select="@correctedTotal36Ar39ArRatio"/><br/></xsl:if>
<xsl:if test="@correctedTotal36Ar39ArRatioSigma!=''"><b>Corrected Total 36Ar 39Ar Ratio Sigma</b>: <xsl:value-of select="@correctedTotal36Ar39ArRatioSigma"/><br/></xsl:if>
<xsl:if test="@corrected40ArRad39ArKRatio!=''"><b>Corrected 40Ar Rad 39Ar K Ratio</b>: <xsl:value-of select="@corrected40ArRad39ArKRatio"/><br/></xsl:if>
<xsl:if test="@corrected40ArRad39ArKRatioSigma!=''"><b>Corrected 40Ar Rad 39Ar K Ratio Sigma</b>: <xsl:value-of select="@corrected40ArRad39ArKRatioSigma"/><br/></xsl:if>
<xsl:if test="@corrrected39ArK36ArAtmRatio!=''"><b>Corrrected 39Ar K 36Ar Atm Ratio</b>: <xsl:value-of select="@corrrected39ArK36ArAtmRatio"/><br/></xsl:if>
<xsl:if test="@corrrected39ArK36ArAtmRatioSigma!=''"><b>Corrrected 39Ar K 36Ar Atm Ratio Sigma</b>: <xsl:value-of select="@corrrected39ArK36ArAtmRatioSigma"/><br/></xsl:if>
<xsl:if test="@corrrected40ArRadAtm36ArAtmRatio!=''"><b>Corrrected 40Ar Rad Atm 36Ar Atm Ratio</b>: <xsl:value-of select="@corrrected40ArRadAtm36ArAtmRatio"/><br/></xsl:if>
<xsl:if test="@corrrected40ArRadAtm36ArAtmRatioSigma!=''"><b>Corrrected 40Ar Rad Atm 36Ar Atm Ratio Sigma</b>: <xsl:value-of select="@corrrected40ArRadAtm36ArAtmRatioSigma"/><br/></xsl:if>
<xsl:if test="@corrrected39ArK40ArRadAtmRatio!=''"><b>Corrrected 39Ar K 40Ar Rad Atm Ratio</b>: <xsl:value-of select="@corrrected39ArK40ArRadAtmRatio"/><br/></xsl:if>
<xsl:if test="@corrrected39ArK40ArRadAtmRatioSigma!=''"><b>Corrrected 39Ar K 40Ar Rad Atm Ratio Sigma</b>: <xsl:value-of select="@corrrected39ArK40ArRadAtmRatioSigma"/><br/></xsl:if>
<xsl:if test="@corrrected36ArAtm40ArRadAtmRatio!=''"><b>Corrrected 36Ar Atm 40Ar Rad Atm Ratio</b>: <xsl:value-of select="@corrrected36ArAtm40ArRadAtmRatio"/><br/></xsl:if>
<xsl:if test="@corrrected36ArAtm40ArRadAtmRatioSigma!=''"><b>Corrrected 36Ar Atm 40Ar Rad Atm Ratio Sigma</b>: <xsl:value-of select="@corrrected36ArAtm40ArRadAtmRatioSigma"/><br/></xsl:if>
<xsl:if test="@corrCoefficient4036over3936!=''"><b>Corr Coefficient 4036 over 3936</b>: <xsl:value-of select="@corrCoefficient4036over3936"/><br/></xsl:if>
<xsl:if test="@corrCoefficient3640over3940!=''"><b>Corr Coefficient 3640 over 3940</b>: <xsl:value-of select="@corrCoefficient3640over3940"/><br/></xsl:if>
<xsl:if test="@corrected36ArAtmospheric!=''"><b>Corrected 36Ar Atmospheric</b>: <xsl:value-of select="@corrected36ArAtmospheric"/><br/></xsl:if>
<xsl:if test="@corrected36ArAtmosphericSigma!=''"><b>Corrected 36Ar Atmospheric Sigma</b>: <xsl:value-of select="@corrected36ArAtmosphericSigma"/><br/></xsl:if>
<xsl:if test="@corrected36ArCosmogenic!=''"><b>Corrected 36Ar Cosmogenic</b>: <xsl:value-of select="@corrected36ArCosmogenic"/><br/></xsl:if>
<xsl:if test="@corrected36ArCosmogenicSigma!=''"><b>Corrected 36Ar Cosmogenic Sigma</b>: <xsl:value-of select="@corrected36ArCosmogenicSigma"/><br/></xsl:if>
<xsl:if test="@corrected36ArCalcium!=''"><b>Corrected 36Ar Calcium</b>: <xsl:value-of select="@corrected36ArCalcium"/><br/></xsl:if>
<xsl:if test="@corrected36ArCalciumSigma!=''"><b>Corrected 36Ar Calcium Sigma</b>: <xsl:value-of select="@corrected36ArCalciumSigma"/><br/></xsl:if>
<xsl:if test="@corrected36ArChlorine!=''"><b>Corrected 36Ar Chlorine</b>: <xsl:value-of select="@corrected36ArChlorine"/><br/></xsl:if>
<xsl:if test="@corrected36ArChlorineSigma!=''"><b>Corrected 36Ar Chlorine Sigma</b>: <xsl:value-of select="@corrected36ArChlorineSigma"/><br/></xsl:if>
<xsl:if test="@corrected37ArCalcium!=''"><b>Corrected 37Ar Calcium</b>: <xsl:value-of select="@corrected37ArCalcium"/><br/></xsl:if>
<xsl:if test="@corrected37ArCalciumSigma!=''"><b>Corrected 37Ar Calcium Sigma</b>: <xsl:value-of select="@corrected37ArCalciumSigma"/><br/></xsl:if>
<xsl:if test="@corrected38ArAtmospheric!=''"><b>Corrected 38Ar Atmospheric</b>: <xsl:value-of select="@corrected38ArAtmospheric"/><br/></xsl:if>
<xsl:if test="@corrected38ArAtmosphericSigma!=''"><b>Corrected 38Ar Atmospheric Sigma</b>: <xsl:value-of select="@corrected38ArAtmosphericSigma"/><br/></xsl:if>
<xsl:if test="@corrected38ArCosmogenic!=''"><b>Corrected 38Ar Cosmogenic</b>: <xsl:value-of select="@corrected38ArCosmogenic"/><br/></xsl:if>
<xsl:if test="@corrected38ArCosmogenicSigma!=''"><b>Corrected 38Ar Cosmogenic Sigma</b>: <xsl:value-of select="@corrected38ArCosmogenicSigma"/><br/></xsl:if>
<xsl:if test="@corrected38ArCalcium!=''"><b>Corrected 38Ar Calcium</b>: <xsl:value-of select="@corrected38ArCalcium"/><br/></xsl:if>
<xsl:if test="@corrected38ArCalciumSigma!=''"><b>Corrected 38Ar Calcium Sigma</b>: <xsl:value-of select="@corrected38ArCalciumSigma"/><br/></xsl:if>
<xsl:if test="@corrected38ArChlorine!=''"><b>Corrected 38Ar Chlorine</b>: <xsl:value-of select="@corrected38ArChlorine"/><br/></xsl:if>
<xsl:if test="@corrected38ArChlorineSigma!=''"><b>Corrected 38Ar Chlorine Sigma</b>: <xsl:value-of select="@corrected38ArChlorineSigma"/><br/></xsl:if>
<xsl:if test="@corrected38ArPotassium!=''"><b>Corrected 38Ar Potassium</b>: <xsl:value-of select="@corrected38ArPotassium"/><br/></xsl:if>
<xsl:if test="@corrected38ArPotassiumSigma!=''"><b>Corrected 38Ar Potassium Sigma</b>: <xsl:value-of select="@corrected38ArPotassiumSigma"/><br/></xsl:if>
<xsl:if test="@corrected39ArCalcium!=''"><b>Corrected 39Ar Calcium</b>: <xsl:value-of select="@corrected39ArCalcium"/><br/></xsl:if>
<xsl:if test="@corrected39ArCalciumSigma!=''"><b>Corrected 39Ar Calcium Sigma</b>: <xsl:value-of select="@corrected39ArCalciumSigma"/><br/></xsl:if>
<xsl:if test="@corrected39ArPotassium!=''"><b>Corrected 39Ar Potassium</b>: <xsl:value-of select="@corrected39ArPotassium"/><br/></xsl:if>
<xsl:if test="@corrected39ArPotassiumSigma!=''"><b>Corrected 39Ar Potassium Sigma</b>: <xsl:value-of select="@corrected39ArPotassiumSigma"/><br/></xsl:if>
<xsl:if test="@corrected40ArAtmospheric!=''"><b>Corrected 40Ar Atmospheric</b>: <xsl:value-of select="@corrected40ArAtmospheric"/><br/></xsl:if>
<xsl:if test="@corrected40ArAtmosphericSigma!=''"><b>Corrected 40Ar Atmospheric Sigma</b>: <xsl:value-of select="@corrected40ArAtmosphericSigma"/><br/></xsl:if>
<xsl:if test="@corrected40ArCosmogenic!=''"><b>Corrected 40Ar Cosmogenic</b>: <xsl:value-of select="@corrected40ArCosmogenic"/><br/></xsl:if>
<xsl:if test="@corrected40ArCosmogenicSigma!=''"><b>Corrected 40Ar Cosmogenic Sigma</b>: <xsl:value-of select="@corrected40ArCosmogenicSigma"/><br/></xsl:if>
<xsl:if test="@corrected40ArPotassium!=''"><b>Corrected 40Ar Potassium</b>: <xsl:value-of select="@corrected40ArPotassium"/><br/></xsl:if>
<xsl:if test="@corrected40ArPotassiumSigma!=''"><b>Corrected 40Ar Potassium Sigma</b>: <xsl:value-of select="@corrected40ArPotassiumSigma"/><br/></xsl:if>
<xsl:if test="@corrected40ArRadiogenic!=''"><b>Corrected 40Ar Radiogenic</b>: <xsl:value-of select="@corrected40ArRadiogenic"/><br/></xsl:if>
<xsl:if test="@corrected40ArRadiogenicSigma!=''"><b>Corrected 40Ar Radiogenic Sigma</b>: <xsl:value-of select="@corrected40ArRadiogenicSigma"/><br/></xsl:if>
<xsl:if test="@measurementDescription!=''"><b>Measurement Description</b>: <xsl:value-of select="@measurementDescription"/><br/></xsl:if><br/>


</div>
</xsl:template>



</xsl:stylesheet>