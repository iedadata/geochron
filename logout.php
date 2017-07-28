<?PHP
/**
 * logout.php
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


if($_GET['l']){
	header("Location:http://www.geochron.org/geopass/josso-logout.php?josso_current_url=http://dev.geochron.org/logout");
	exit();
}

//logout systems first
include("googlelogout.php");

//kill session
$_SESSION['user_pkey']='';
$_SESSION['user_level']='';
$_SESSION = array();
session_destroy();

?>
<!DOCTYPE html>
<html>
<head>
	<script src="https://code.jquery.com/jquery-2.1.4.js"></script>
    <script>
        $.ajax({url: 'https://sandbox.orcid.org/userStatus.json?logUserOut=true'});
        window.location.href = "/";
    </script>
</head>
<body>    
</body>
</html>