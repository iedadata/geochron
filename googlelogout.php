<?PHP
/**
 * googlelogout.php
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

//Include GP config file
include_once 'googleoauthconfig.php';

//Unset token and user data from session
unset($_SESSION['token']);
unset($_SESSION['userData']);

//Reset OAuth access token
$gClient->revokeToken();

unset($_SESSION['loggedin']);
unset($_SESSION['username']);
unset($_SESSION['userpkey']);
unset($_SESSION['logintype']);


?>