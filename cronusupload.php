<?PHP
/**
 * cronusupload.php
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

include("db.php");

//var_dump($_SESSION);
//exit();

include("logincheck.php");


$userpkey=$_SESSION['userpkey'];


if($_POST['submit']==""){

include("includes/geochron-secondary-header.htm");
?>

<style type="text/css">
table.igsnsample {
	border-width: 1px;
	border-spacing: 0px;
	border-style: none;
	border-color: gray;
	border-collapse: collapse;
	background-color: white;
}
table.igsnsample th {
	border-width: 1px;
	padding: 3px;
	border-style: inset;
	border-color: #333333;
	background-color: #CA012D;
	color: #FFFFFF;
	-moz-border-radius: 0px 0px 0px 0px;
}
table.igsnsample td {
	border-width: 1px;
	padding: 3px;
	border-style: inset;
	border-color: #dddddd;
	background-color: white;
	-moz-border-radius: 0px 0px 0px 0px;
}
</style>
<!--
Blank Geochron IGSN Template: <a href="SESAR_Template.xls">HERE</a><br><br>
-->
CRONUS Upload Test:<br><br>
<form name="myform" method="POST" enctype="multipart/form-data">
File: <input type="file" name="file" id="file" /> <br><br>
<input type="submit" name="submit" value="Submit">
<img src="loading2.gif" alt border="0" name="loading"
style="visibility:hidden;">

</form>


<?

include("includes/geochron-secondary-footer.htm");

exit();
}

if($_FILES["file"]["name"]==""){
	header("Location: cronusupload.php");
	exit();
}

//get file stuff here
  //echo "Upload: " . $_FILES["file"]["name"] . "<br />";
  //echo "Type: " . $_FILES["file"]["type"] . "<br />";
  //echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
  //echo "Stored in: " . $_FILES["file"]["tmp_name"];
  //exit();



include("includes/geochron-secondary-header.htm");

flush();





require_once 'Excel/reader.php';

$data = new Spreadsheet_Excel_Reader();

$data->setOutputEncoding('CP1251');

//$data->read('sesartemplatework.xls');
$data->read($_FILES["file"]["tmp_name"]);



error_reporting(E_ALL ^ E_NOTICE);

//$data->sheets[0]['cells'][$i][$x]

//print_r($data);



for($sheetnum=0;$sheetnum<count($data->boundsheets);$sheetnum++){
	echo "sheetname: ".$data->boundsheets[$sheetnum]['name']."<br>\n";
	echo "<table  class=\"aliquot\">\n";
	
	$numrows=$data->sheets[$sheetnum]['numRows'];
	$numcols=$data->sheets[$sheetnum]['numCols'];
	//echo "numrows: $numrows<br>";
	//echo "numcols: $numcols<br>";
	
	for($x=1;$x<=$numrows;$x++){
	
		echo "<tr>\n";
	
		for($y=1;$y<=$numcols;$y++){
		
			if($data->sheets[$sheetnum]['cells'][$x][$y]!=""){
			
				echo "<td nowrap>".$data->sheets[$sheetnum]['cells'][$x][$y]."</td>\n";
			
			}else{
			
				echo "<td>&nbsp;</td>\n";
			
			}
		
		}
		
		echo "</tr>\n";
	
	}

	
	
	echo "</table><br>\n\n";
}









include("includes/geochron-igsn-footer.php");


?>
