<?PHP
/**
 * submitdata.php
 *
 * longdesc
 *
 * LICENSE: This source file is subject to version 4.0 of the Creative Commons
 * license that is available through the world-wide-web at the following URI:
 * https://creativecommons.org/licenses/by/4.0/
 *
 * @category   Geochronology
 * @package    Geochron Portal
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  IEDA (http://www.iedadata.org/)
 * @license    https://creativecommons.org/licenses/by/4.0/  Creative Commons License 4.0
 * @version    GitHub: $
 * @link       http://www.geochron.org
 * @see        Geochron, Geochronology
 */

session_start();
include("includes/geochron-secondary-header.htm");
?>


<h1>Data Reduction and Upload Software</h1>

<div class="aboutpage" style="padding-left:20px;padding-right:15px;">
Two approaches to getting data into the database are used with the Geochron database.  The 
preferred method is to use data reduction programs to directly interact with the database.  
Such programs typically contain all of the needed data that is important to understanding the 
sample dates and derived age interpretations.  These programs provide a simple user interface 
for uploading data that interacts with the database using a set of web services. These 
interfaces also provide utilities for obtaining or verifying the unique sample identifier 
IGSN. Data are stored in XML format, and these files can be downloaded and ingested directly 
back into the reduction program. The other method is to use spreadsheets or text files that 
can be read into the database.  These do not typically contain all of the important data or 
metadata, but allow for upload by users not wanting to use the data reduction programs provided 
here or for methods where no general data reduction program is available.
<br><br>
Data reporting and the approach employed above were the subject of numerous EarthChem and 
EARTHTIME sponsored workshops.  Those workshop reports can be found at: 
<a href="http://www.earthchem.org/workshops">http://www.earthchem.org/workshops.</a>
</div>

<br>

<fieldset class="aboutpage" style="border: 1px solid #CDCDCD; padding: 8px; padding-bottom:0px; margin: 8px 0">
	<legend><strong>(U-Th)/He</strong></legend>
	<table class="sample" width="100%" >
		<!---
		<tr>
			<td valign="top" width="130px">
				<a href="/helios" target="_blank"><img src="helioslogo.gif" border="0"></a>
			</td>
			<td valign="top">
				<h2>Helios</h2>
				Helios is a data reduction program for (U-Th-Sm)/He date calculation and simple age
				interpretations. It incorporates all standard methodology and supports either the
				spike-blank/spike-normal approach or using massing data. Aliquots can be reduced
				individually or in sets. Analytical data can be read in from Excel files or entered
				manually...<br><br>
				<a href="/helios" target="_blank">http://www.geochronportal.org/helios</a>
			</td>
		</tr>
		<tr>
		--->
			<td valign="top" width="130px">
				<a href="uthhexls.php" ><img src="manualupload.jpg" border="0"></a>
			</td>
			<td valign="top">
				<h2>(U-Th)/He Manual Upload</h2>
				<a href="uthhexls.php" >(U-Th)/He Manual Upload Using Geochron Spreadsheets</a>
			</td>
		</tr>
	</table><br>
</fieldset>
<br>

<fieldset class="aboutpage" style="border: 1px solid #CDCDCD; padding: 8px; padding-bottom:0px; margin: 8px 0">
	<legend><strong>U-Pb</strong></legend>
	<table class="sample" width="100%">
		<tr>
			<td valign="middle" width="130px">
				<a href="https://github.com/CIRDLES/ET_Redux" target="_blank"><img src="uth-pb-redux-logo.png" width="130px;" border="0"></a>
			</td>
			<td valign="top">
				<h2>ET_Redux</h2>
				ET_Redux is the flagship cyber infrastructure product of <a href="http://cirdles.org" target="_blank">CIRDLES</a>, the Cyber Infrastructure Research and 
				Development Lab for the Earth Sciences, an undergraduate research lab at the College of Charleston in 
				Charleston, South Carolina. ET_Redux was previously known as U-Pb_Redux, but in January of 2015, was 
				renamed to reflect its expanding functionality and its sponsor, EARTHTIME (EARTH-TIME.org). <br><br>
				<a href="https://github.com/CIRDLES/ET_Redux" target="_blank">https://github.com/CIRDLES/ET_Redux</a>
				<br><br>
				<a href="uploadfile.php" target="_blank">ET_Redux Manual Upload Using XML File</a>
			</td>
		</tr>
		<tr>
			<td valign="top" width="130px">
				<a href="squid.php" ><img src="manualupload.jpg" border="0"></a>
			</td>
			<td valign="top">
				<h2>SQUID/SQUID2 Ion Microprobe Manual Upload</h2>
				<a href="squid.php" >SQUID/SQUID2 Ion Microprobe Manual Upload Using Geochron Spreadsheets</a>
			</td>
		</tr>
		<tr>
			<td valign="top" width="130px">
				<a href="zips.php" ><img src="manualupload.jpg" border="0"></a>
			</td>
			<td valign="top">
				<h2>ZIPS Ion Microprobe Manual Upload</h2>
				<a href="zips.php" >ZIPS Ion Microprobe Manual Upload Using Geochron Spreadsheets</a>
			</td>
		</tr>
	</table><br>
</fieldset>
<br>

<fieldset class="aboutpage" style="border: 1px solid #CDCDCD; padding: 8px; padding-bottom:0px; margin: 8px 0">
	<legend><strong>Ar-Ar</strong></legend>
	<table class="sample" width="100%">
		<tr>
			<td valign="top" width="130px">
				<a href="http://earthref.org/tools/ararcalc/" target="_blank"><img src="ararcalc.gif" border="0"></a>
			</td>
			<td valign="top">
				<h2>ArArCALC</h2>
				The program ArArCALC provides an interactive interface to data reduction in 40Ar/39Ar 
				geochronology. ArArCALC is coded within Visual Basic for Excel 2000-XP-2003 using 
				datasheets, charts, menus and dialogboxes. All 40Ar/39Ar age calculations are reported 
				in single Excel workbooks and thus can be easily shared between different ArArCALC 
				users... <br><br>
				<a href="http://earthref.org/tools/ararcalc/" target="_blank">http://earthref.org/tools/ararcalc/</a>
			</td>
		</tr>

		<tr>
			<td valign="top" width="130px">
				<img src="massspeclogo.jpg" border="0">
			</td>
			<td valign="top">
				<h2>Mass Spec</h2>
				Mass Spec is an integrated data collection / data reduction program for acquiring data 
				from Noble gas extraction systems. For the purposes of 40Ar/39Ar dating, the program has 
				specialized analytical tools for displaying data in the form of age-probability density 
				diagrams, incremental heating spectra, and isochrons. Most data-reduction use of Mass 
				Spec is for data collected by the program directly during mass spectrometry, but data 
				import facilities are available.<br><br>
				<a href="mailto:al@bgc.org" target="_blank">For more information, contact Alan Deino at al@bgc.org.</a>
			</td>
		</tr>

		<tr>
			<td valign="top" width="130px">
				<a href="ararupload.php" ><img src="manualupload.jpg" border="0"></a>
			</td>
			<td valign="top">
				<h2>Ar/Ar Manual Upload</h2>
				<a href="ararupload.php" >Ar/Ar Manual Upload Using Geochron Spreadsheets</a>
			</td>
		</tr>

	</table><br>
</fieldset>
<br>



<fieldset class="aboutpage" style="border: 1px solid #CDCDCD; padding: 8px; padding-bottom:0px; margin: 8px 0">
	<legend><strong>Fission Track</strong></legend>
	<table class="sample" width="100%">


		<tr>
			<td valign="top" width="130px">
				<a href="fissiontrack.php" ><img src="manualupload.jpg" border="0"></a>
			</td>
			<td valign="top">
				<h2>Fission Track Manual Upload</h2>
				<a href="fissiontrack.php" >Fission Track Manual Upload Using Geochron Spreadsheets</a>
			</td>
		</tr>

	</table><br>
</fieldset>
<br>






<fieldset class="aboutpage" style="border: 1px solid #CDCDCD; padding: 8px; padding-bottom:0px; margin: 8px 0">
	<legend><strong>TCN</strong></legend>
		under development.<br><br>
</fieldset>
<br>

<fieldset class="aboutpage" style="border: 1px solid #CDCDCD; padding: 8px; padding-bottom:0px; margin: 8px 0">
	<legend><strong>U-Series</strong></legend>
		under development.<br><br>
</fieldset>
<br>










<?include("includes/geochron-secondary-footer.htm");?>
