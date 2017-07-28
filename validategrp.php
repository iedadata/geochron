<?PHP
/**
 * validategrp.php
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


include("includes/geochron-secondary-header.htm");

include("db.php");

$id=$_GET['id'];



$row=$db->get_row("select 
					grp.groupname,
					usr.email 
					from users usr, groups grp, grouprelate gr
					where usr.users_pkey = grp.users_pkey and
					grp.group_pkey = gr.group_pkey
					and gr.emailstring='$id'");

if($row->groupname!=""){

	$db->query("update grouprelate set confirmed=true where emailstring='$id'");

	$groupname=$row->groupname;
	$email=$row->email;

?>

	<h1>Success!</h1><br>
	You have successfully confirmed your membership to '<?=$groupname?>'.<br><br>
	This group belongs to <?=$email?>.




<?





}else{

?>
	<h1>Error!</h1><br>
	Group not found.

<?






}

?>


<br><br><br><br><br>
<br><br><br><br><br>
<br><br><br><br><br>
<br><br><br><br><br>
<br><br><br><br><br>


<?
include("includes/geochron-secondary-footer.htm");
?>