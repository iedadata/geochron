<?PHP
/**
 * deletedataset.php
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

$dataset_pkey=$_GET['dataset_pkey'];

$p=$_GET['p'];
$pp=$_GET['pp'];


$g=$_GET['g'];

//$userpkey
$count=$db->get_var("select count(*) from datasets where dataset_pkey=$dataset_pkey and users_pkey=$userpkey");
if($count==0){
	echo "datset not found.";
	exit();
}


$linkstring=$db->get_var("select linkstring from datasets where dataset_pkey=$dataset_pkey");

//delete zip file
exec("rm -rf publish/$linkstring.zip");


$db->query("delete from datasets where dataset_pkey=$dataset_pkey");
$db->query("delete from datasetrelate where dataset_pkey=$dataset_pkey");



if($p=="jjjj"){


}else{

	header("Location: managedata.php");

}



























?>