<?PHP
/**
 * deletegroup.php
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


include("db.php");

$group_pkey=$_GET['group_pkey'];

$p=$_GET['p'];
$pp=$_GET['pp'];


$g=$_GET['g'];

//$userpkey
$count=$db->get_var("select count(*) from groups where group_pkey=$group_pkey and users_pkey=$userpkey");
if($count==0){
	echo "group not found.";
	exit();
}




$db->query("delete from groups where group_pkey=$group_pkey");
$db->query("delete from grouprelate where group_pkey=$group_pkey");
$db->query("delete from groupsamplerelate where group_pkey=$group_pkey");


if($p=="jjjj"){


}else{

	header("Location: managedata.php");

}



























?>