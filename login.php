<?PHP
/**
 * login.php
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


//roll back to geopass only.
header("Location:geopasslogin?l=1");
exit();

include("includes/geochron-secondary-header.htm");
?>
<link rel="stylesheet" href="/loginbuttons/css/buttons-si.css">

<?

session_start();

if(!$_SESSION['currentpage'] || $_SESSION['currentpage']=="/login"){
	$_SESSION['currentpage']="managedata";
}

?>

<div style="padding-top:60px;font-size:1.4em;width:900px;" align="center">

	<div>
		<a href="googlelogin?l=1"><button class="btn-si btn-google" >Sign in with Google</button></a><br>
	</div>

	<div style="padding-top:15px;">
		<a href="orcidlogin?l=1"><button class="btn-si btn-orcid" >Sign in with ORCID</button></a><br>
	</div>

	<div style="padding-top:15px;">
		<a href="githublogin?l=1"><button class="btn-si btn-github" >Sign in with GitHub</button></a><br>
	</div>

	<!---
	<div style="padding-top:15px;">
		<a href="facebooklogin?l=1"><button class="btn-si btn-facebook" >Sign in with Facebook</button></a><br>
	</div>
	--->

	<div style="padding-top:15px;">
		<a href="geopasslogin?l=1"><button class="btn-si btn-geopass" >Sign in with GeoPass</button></a><br>
	</div>

</div>

<?
include("includes/geochron-secondary-footer.htm");
?>