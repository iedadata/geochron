<?PHP
/**
 * addgroup.php
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

include("logincheck.php");

include("includes/geochron-secondary-header.htm");

include("db.php");

?>

<script Language="JavaScript">
<!-- 
function Blank_TextField_Validator()
{
// Check the value of the element named text_name from the form named text_form
if (text_form.groupname.value == "")
{
  // If null display and alert box
   alert("Please provide a group name.");
  // Place the cursor on the field for revision
   text_form.groupname.focus();
  // return false to stop further processing
   return (false);
}
// If text_name is not null continue processing
return (true);
}
-->
</script>


<h1>Add Group</h1><br>

<form name="text_form" method="POST" action="inviteusers.php" onsubmit="return Blank_TextField_Validator()">
	Group Name: <input type="text" name="groupname"><br><br><br>
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