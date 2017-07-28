<?PHP
/**
 * googleoauthconfig.php
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

//Include Google client library 
include_once 'googleOauth2/vendor/autoload.php';

/*
 * Configuration and setup Google API
 */
include_once("includes/config.inc.php");
$redirectURL = 'http://dev.geochron.org/googleoauth2callback';

//Call Google API
$gClient = new Google_Client();
$gClient->setApplicationName('Login to CodexWorld.com');
$gClient->setClientId($googleouathclientId);
$gClient->setClientSecret($googleouathclientSecret);
$gClient->setRedirectUri($redirectURL);
$gClient->addScope('profile');
$gClient->addScope('email');

$google_oauthV2 = new Google_Service_Oauth2($gClient);
?>