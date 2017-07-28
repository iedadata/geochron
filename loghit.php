<?PHP
/**
 * loghit.php
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

include_once("db.php");

$http_user_agent = htmlentities($_SERVER['HTTP_USER_AGENT']);
$remote_addr = $_SERVER['REMOTE_ADDR'];
$http_referer = htmlentities($_SERVER['HTTP_REFERER']);
$request_uri = htmlentities($_SERVER['REQUEST_URI']);
$http_host = htmlentities($_SERVER['HTTP_HOST']);

$day=date("j");
$month=date("n");
$year=date("Y");


	//log some stats here
	$db->query("insert into stats 	
				(http_user_agent,
				remote_addr,
				http_referer,
				request_uri,
				http_host,
				timestamp,
				downloadtype,
				month,
				day,
				year,
				username
				) values (
				'$http_user_agent',
				'$remote_addr',
				'$http_referer',
				'$request_uri',
				'$http_host',
				now(),
				'$downloadtype',
				$month,
				$day,
				$year,
				'".$_SESSION['username']."'
				);");



?>