<?PHP
/**
 * upload.php
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


header("Location:submitdata");exit();

include("db.php");

session_start();

include("logincheck.php");



include("includes/geochron-secondary-header.htm");


?>



<h1>Upload Data</h1><br>

Please choose from the following list to upload data:<br><br>

<div style="padding-left:20px;padding-top:20px;">

	
	<a href="zips">ZIPS Ion Microprobe</a><br><br>
	
	<a href="uthhexls">(U-TH)He XLS</a><br><br>
	
	<a href="squid">SQUID Ion Microprobe</a><br><br>
	

	

</div>















<br><br><br><br><br>
<br><br><br><br><br>
<br><br><br><br><br>
<br><br><br><br><br>
<br><br><br><br><br>




















<?

include("includes/geochron-secondary-footer.htm");
?>