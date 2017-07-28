<?PHP
/**
 * validatedset.php
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
					dset.datasetname,
					usr.email,
					(select email from users where users_pkey = dset.users_pkey) as owner
					from users usr, datasets dset, datasetuserrelate dur
					where usr.users_pkey = dur.users_pkey and
					dur.dataset_pkey = dset.dataset_pkey
					and dur.emailstring='$id'");

if($row->datasetname!=""){

	$db->query("update datasetuserrelate set confirmed=true where emailstring='$id'");

	$datasetname=$row->datasetname;
	$owner=$row->owner;

?>

	<h1>Success!</h1><br>
	You have successfully confirmed your membership to <?=$datasetname?>.<br><br>
	This dataset belongs to <?=$owner?>.




<?





}else{

?>
	<h1>Error!</h1><br>
	Dataset not found.

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