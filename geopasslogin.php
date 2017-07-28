<?PHP
/**
 * geopasslogin.php
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
	header("Location:http://www.geochron.org/geopass/josso-logout.php?josso_current_url=http://dev.geochron.org/geopasslogin");
	exit();
}

//logout systems first
include("googlelogout.php");


?>
<!DOCTYPE html>
<html>
<head>
	<script src="https://code.jquery.com/jquery-2.1.4.js"></script>
    <script>
        $.ajax({
            url: 'https://www.orcid.org/userStatus.json?logUserOut=true',
            dataType: 'jsonp',
            success: function(result,status,xhr) {
                window.location.href = "geopass/josso-login.php?josso_current_url=/geopasscallback";
            },
            error: function (xhr, status, error) {
                alert(error);
            }
        });
    </script>
</head>
<body>    
</body>
</html>