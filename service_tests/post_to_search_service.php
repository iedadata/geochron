<?
include("includes/geochron-secondary-header.htm");
?>

Post test data to Geochron search service: <br><br>

This page allows you to test the Geochron search web service. <br><br>
The Geochron search service allows users to remotely query the Geochron database<br>
for the purposes of gathering search results for use in local software.<br><br>
The target URL for this POST service is: http://www.geochron.org/search_service.php<br><br>
The POST variables are detailed below:<br><br>

<form name="blah" action="search_service.php" method="POST">

POST Var: igsn: <input type="text" name="igsn" value=""><br>
POST Var: laboratoryname: <input type="text" name="laboratoryname" value=""><br>
POST Var: analystname: <input type="text" name="analystname" value=""><br>
POST Var: sampleagetype: <select name="sampleagetype" >
	<option value="">Choose...</option>
	<option value="Ar-Ar: Age Plateau">Ar-Ar: Age Plateau</option>
	<option value="Ar-Ar: Normal Isochron">Ar-Ar: Normal Isochron</option>
	<option value="Ar-Ar: Inverse Isochron">Ar-Ar: Inverse Isochron</option>
	<option value="Ar-Ar: Total Fusion">Ar-Ar: Total Fusion</option>
	<option value="U-Pb: single analysis 206Pb/238U">U-Pb: single analysis 206Pb/238U</option>
	<option value="U-Pb: single analysis 207Pb/235U">U-Pb: single analysis 207Pb/235U</option>
	<option value="U-Pb: single analysis 207Pb/206Pb">U-Pb: single analysis 207Pb/206Pb</option>
	<option value="U-Pb: single analysis 208Pb/232Th">U-Pb: single analysis 208Pb/232Th</option>
	<option value="U-Pb: weighted mean 207Pb/235U">U-Pb: weighted mean 207Pb/235U</option>
	<option value="U-Pb: weighted mean 206Pb/238U">U-Pb: weighted mean 206Pb/238U</option>
	<option value="U-Pb: weighted mean 207Pb/206Pb">U-Pb: weighted mean 207Pb/206Pb</option>
	<option value="U-Pb: weighted mean_208Pb/232Th">U-Pb: weighted mean_208Pb/232Th</option>
	<option value="U-Pb: Tuff Zirc 206Pb/238U (filtered median date)">U-Pb: Tuff Zirc 206Pb/238U (filtered median date)</option>
	<option value="U-Pb: Tuff Zirc 207Pb/235U (filtered median date)">U-Pb: Tuff Zirc 207Pb/235U (filtered median date)</option>
	<option value="U-Pb: Tuff Zirc 208Pb/232U (filtered median date)">U-Pb: Tuff Zirc 208Pb/232U (filtered median date)</option>
	<option value="U-Pb: concordia">U-Pb: concordia</option>
	<option value="U-Pb: upper intercept">U-Pb: upper intercept</option>
	<option value="U-Pb: lower intercept">U-Pb: lower intercept</option>
	<option value="U-Pb: 238U/206Pb isochron">U-Pb: 238U/206Pb isochron</option>
	<option value="U-Pb: 235U/207Pb isochron">U-Pb: 235U/207Pb isochron</option>
	<option value="U-Pb: 232Th/208Pb isochron">U-Pb: 232Th/208Pb isochron</option>
	<option value="U-Pb: Semi-TotalPb isochron">U-Pb: Semi-TotalPb isochron</option>
	<option value="U-Pb: Total Pb isochron">U-Pb: Total Pb isochron</option>
	<option value="(U-Th)/He: Average">(U-Th)/He: Average</option>
	<option value="(U-Th)/He: Weighted Mean">(U-Th)/He: Weighted Mean</option>
	<option value="(U-Th)/He: Detrital Sample (no mean age)">(U-Th)/He: Detrital Sample (no mean age)</option>
</select>

<br>
POST Var: sampleagevaluemin: <input type="text" name="sampleagevaluemin" value=""><br>
POST Var: sampleagevaluemax: <input type="text" name="sampleagevaluemax" value=""><br>
<?
/*
?>
POST Var: sampleageerranalmin: <input type="text" name="sampleageerranalmin" value=""><br>
POST Var: sampleageerranalmax: <input type="text" name="sampleageerranalmax" value=""><br>
POST Var: sampleagemeanmin: <input type="text" name="sampleagemeanmin" value=""><br>
POST Var: sampleagemeanmax: <input type="text" name="sampleagemeanmax" value=""><br>
<?
*/
?>

<br>


<br><br>

<input type="submit" name="submitbutton" value="submit now">
</form>


<?
include("includes/geochron-secondary-footer.htm");
?>
