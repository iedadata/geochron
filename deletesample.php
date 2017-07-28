<?PHP
/**
 * deletesample.php
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

//print_r($_SESSION);
//exit();

include("db.php");

$pkey=$_GET['pkey'];
$ip=$_SERVER['remote_addr'];
$username=$_SESSION['username'];
$userpkey=$_SESSION['userpkey'];

$page=$_GET['page'];

$samplecount=$db->get_var("select count(*) as count from sample where sample_pkey = $pkey and userpkey=$userpkey");

if($samplecount==0){
	//header("Location: filemanager.php?page=$page");
	header("Location: managedata.php?page=$page");
	exit();
}



$db->query("delete from datasetrelate where sample_pkey=$pkey");

$db->query("delete from groupsamplerelate where sample_pkey=$pkey");

$db->query("delete from SAMPLE_AGE where sample_pkey=$pkey");



$db->query("delete from SAMPLE where sample_pkey=$pkey");

		$ip=$_SERVER['REMOTE_ADDR'];

		$db->query("
			insert into logs (
				log_pkey,
				logtime,
				ip_address,
				content
			) values (
				nextval('log_seq'),
				now(),
				'$ip',
				'Sample $pkey deleted from database by $username.'
			)
			");

unlink("uploadimages/".$pkey.".jpg");

//header("Location: filemanager.php?page=$page");
header("Location: managedata.php?page=$page");

?>