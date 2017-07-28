<?PHP
/**
 * adddataset.php
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

$chars=array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z","a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z","0","1","2","3","4","5","6","7","8","9");


session_start();

include("logincheck.php");



include("db.php");


if($_POST['submit']!=""){

	$datasetname=addslashes($_POST['datasetname']);
	
	$datasetcount=$db->get_var("select count(*) from datasets where datasetname='$datasetname'");
	
	if($datasetcount > 0){
	
		$error="Dataset '$datasetname' alreday exists in the database. Please try again.";
	
	}else{

		$randstring="";
		for($x=0;$x<5;$x++){
			$randstring.=$chars[rand(0,61)];
		}
		
		
		$datestring=date("Y_m_d");
		
		$randstring="geochron_dataset_".$datestring."_".$randstring;

		$dataset_pkey=$db->get_var("select nextval('datasets_seq')");

		//put in dataset now
		$db->query("insert into datasets values ($dataset_pkey,$userpkey,'$datasetname','$randstring')");
		
		header("Location: managedatasetsamples.php?dataset_pkey=$dataset_pkey");
		
		exit();
		
	
	}

}


if($error != ""){
	$error="<font color=\"red\">$error</font><br>";
}


include("includes/geochron-secondary-header.htm");

?>

<script Language="JavaScript">
<!-- 
function Blank_TextField_Validator()
{
// Check the value of the element named text_name from the form named text_form
if (text_form.datasetname.value == "")
{
  // If null display and alert box
   alert("Please provide a dataset name.");
  // Place the cursor on the field for revision
   text_form.datasetname.focus();
  // return false to stop further processing
   return (false);
}
// If text_name is not null continue processing
return (true);
}
-->
</script>


<h1>Add Dataset</h1><br>

<?=$error?>

<form name="text_form" method="POST" onsubmit="return Blank_TextField_Validator()">
	Dataset Name: <input type="text" name="datasetname"><br><br><br>
	<input type="submit" name="submit" value="Submit">
</form>




<br><br><br><br><br>
<br><br><br><br><br>
<br><br><br><br><br>
<br><br><br><br><br>
<br><br><br><br><br>




















<?

include("includes/geochron-secondary-footer.htm");
?>