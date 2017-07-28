<?PHP
/**
 * about.php
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

<h1>About Geochron</h1>

<div style="margin-left:auto;margin-right:auto;text-align:center;margin-top:5px;margin-bottom:20px;padding-top:0px; border-width:1px 0px 0px 0px;border-style:solid;border-color:#4572bc;font-size:9px;font-color:c9c9c9" align="center"></div>


<div class="aboutpage" style="padding-left:30px;">

	<h2>News and Announcements</h2>
	<div style="margin-left:auto;margin-right:auto;text-align:center;margin-top:5px;margin-bottom:7px;padding-top:0px; border-width:1px 0px 0px 0px;border-style:solid;border-color:#4572bc;font-size:9px;font-color:c9c9c9" align="center"></div>
	Geochron is now in a 1.0 release!  We currently are supporting data contributions for U-Pb, (U-Th)/He, and Ar-Ar geochronology and thermochronology.  
	Data upload is done using data reduction programs specific to these types of data (see <a href="submitdata.php">Data Reduction section</a> of the site).  Data can be downloaded in tabular form (still under some development) or as XML files to be put back into these programs for more detailed analysis. 

	<br><br><br>

	<h2>FAQ and How to Use Geochron</h2>
	<div style="margin-left:auto;margin-right:auto;text-align:center;margin-top:5px;margin-bottom:7px;padding-top:0px; border-width:1px 0px 0px 0px;border-style:solid;border-color:#4572bc;font-size:9px;font-color:c9c9c9" align="center"></div>
	
		<div style="padding-left:10px;">
		<h2>How do I submit data to Geochron?</h2>
		Geochron is designed to accept data from a variety of <a href="submitdata.php">data reduction</a> programs that are used widely  in the various geochronology and thermochronology communities. These programs have features to seamlessly upload data to Geochron, but do require a unique sample or aliquot identifier.  You can access these programs under the <a href="submitdata.php">Data Reduction Software</a> part of the website.  Many of these will accommodate legacy data or data not reduced by those programs.  We are working on tabular forms for upload of legacy data for some of the methods.
		<br><br>
		
		<h2>How do I get data from Geochron?</h2>
		You can perform standard searches and get results in HTML, XLS, and XML formats.  The XML format will read into many of the <a href="submitdata.php">data reduction</a> programs.  Again, you can get these programs where available at the <a href="submitdata.php">Data Reduction Software</a> part of the website.  For some methods (TCN and Ion Microprobe, both of which are under development) the best way to get data into the reduction programs is with the XLS files.
		<br><br>
		
		<h2>How were the data reporting standards for Geochron developed?</h2>
		Geochron is a part of the <a href="http://www.earthchem.org">EarthChem</a> and <a href="http://www.iedadata.org/">Interdisciplinary Earth Data Alliance (IEDA)</a> projects.  A series of workshops have been run for the various methods of analysis.  These were typically small in size (3 to 10 participants) and involved data reduction experts as well as general community members.  Each attempted to establish data reporting requirements as well as how a website should function.  Reports on the methods can be found at: <a href="http://www.earthchem.org/workshops">http://www.earthchem.org/workshops</a>.
	</div>
	
	<br><br><br>

	<h2>Contact</h2>
	<div style="margin-left:auto;margin-right:auto;text-align:center;margin-top:5px;margin-bottom:7px;padding-top:0px; border-width:1px 0px 0px 0px;border-style:solid;border-color:#4572bc;font-size:9px;font-color:c9c9c9" align="center"></div>
	To submit questions or comments about this website, please e-mail Doug Walker at <a href="mailto:jdwalker@ku.edu">jdwalker@ku.edu</a>.


	<br><br><br><br><br><br><br><br><br><br><br><br><br>





</div>



<?
include("includes/geochron-secondary-footer.htm");

/*

News

(put this up now)
Geochron is now in a 1.0 release!  We currently are supporting data contributions for U-Pb, (U-Th)/He, and Ar-Ar geochronology and thermochronology.  Data upload is done using data reduction programs specific to these types of data (see <a href="submitdata.php">Data Reduction section</a> of the site).  Data can be downloaded in tabular form (still under some development) or as XML files to be put back into these programs for more detailed analysis. 

(put this up when you get this finished)
11/8/11 – New search interface added as well as interface for detrital results.




FAQ and How to Use Geochron

How do I submit data to Geochron?  Geochron is designed to accept data from a variety of data reduction programs that are used widely  in the various geochronology and thermochronology communities. These programs have features to seamlessly upload data to Geochron, but do require a unique sample or aliquot identifier.  You can access these programs under the <a href="submitdata.php">Data Reduction Software</a> part of the website.  Many of these will accommodate legacy data or data not reduced by those programs.  We are working on tabular forms for upload of legacy data for some of the methods.

How do I get data from Geochron?  You can perform standard searches and get results in HTML, XLS, and XML formats.  The XML format will read into many of the data reduction programs.  Again, you can get these programs where available at the Data Reduction Software part of the website.  For some methods (TCN and Ion Microprobe, both of which are under development) the best way to get data into the reduction programs is with the XLS files.

How were the data reporting standards for Geochron developed?  Geochron is a part of the EarthChem and Integrated Earth Data Applications (IEDA) projects.  A series of workshops have been run for the various methods of analysis.  These were typically small in size (3 to 10 participants) and involved data reduction experts as well as general community members.  Each attempted to establish data reporting requirements as well as how a website should function.  Reports on the methods can be found at: <a href="http://www.earthchem.org/workshop">http://www.earthchem.org/workshop</a>.

*/


?>
