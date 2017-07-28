<?PHP
/**
 * deletedatasetuser.php
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



$pkey=$_GET['pkey'];

$p=$_GET['p'];
$pp=$_GET['pp'];


$g=$_GET['g'];

//delete from grouprelate
$db->query("delete from datasetuserrelate where datasetuserrelate_pkey=$pkey");

if($p=="jjjj"){


}else{

	header("Location: invitedatasetusers.php?dataset_pkey=$g&p=$pp");

}



























?>