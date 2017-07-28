<?PHP
/**
 * geopasscallback.php
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




include("googlelogout.php");

include("db.php");
// Get current JOSSO User and JOSSO Session information,
require_once "geopass/josso.php";
$jossouser = $josso_agent->getUserInSession();

$ip=$_SERVER['REMOTE_ADDR'];

if(!$_SESSION['currentpage'] || $_SESSION['currentpage']=="/login" || $_SESSION['currentpage']==""){
	$_SESSION['currentpage']="managedata.php";
}

//print_r($_SESSION); echo $_SESSION['currentpage'];exit();

if (isset($jossouser)) {

	$username=$jossouser->name;
	$firstname=$jossouser->properties[4]['!value'];
	$lastname=$jossouser->properties[5]['!value'];

	$getuser=$db->get_row("select * from users where email='$username'"); 
	// If a record exists then the user is valid --->
	if (count($getuser)>0) {           

		$userpkey=$getuser->users_pkey;
		$user_level=$getuser->user_level;
		$username=$getuser->email;
		if ($getuser->EDITOR) {$editor=true;} else {$editor=false;}

		$update=$db->query("UPDATE users SET ipaddress = '$ip', Last_Login = now() WHERE users_pkey = $getuser->users_pkey");
		
		/*
		Check here for null firstname or lastname
		This occurs mainly when logging in for the first time via web service and not the web page.
		If firstname or last name is null, we have them in the session vars here so we can update the database
		accordingly.
		*/
		if($getuser->firstname==""){
			if($firstname!=""){
				$db->query("UPDATE users SET firstname = '$firstname' WHERE users_pkey = $getuser->users_pkey");
			}
		}

		if($getuser->lastname==""){
			if($lastname!=""){
				$db->query("UPDATE users SET lastname = '$lastname' WHERE users_pkey = $getuser->users_pkey");
			}
		}


		// Log successful login --->
		$logaction=$db->query("INSERT INTO logs (log_pkey, logtime,ip_address,content) VALUES (nextval('log_seq'), now(), '$ip', '$username logged in successfully')") or die("could not insert into logs in login.php");

	}else{

		//echo "user $username does not exist yet... let's put it in here...<br><br>";
		$db->query("insert into users 	(users_pkey,
											firstname,
											lastname,
											email,
											active,
											user_level
										)values(
											nextval('users_seq'),
											'$firstname',
											'$lastname',
											'$username',
											'yes',
											'user'
											)");
		
		$getuser=$db->get_row("select * from users where email='$username'");

		$userpkey=$getuser->users_pkey;
		$user_level=$getuser->user_level;
		$username=$getuser->email;
		if ($getuser->EDITOR) {$editor=true;} else {$editor=false;}

		$update=$db->query("UPDATE users SET ipaddress = '$ip', Last_Login = now() WHERE users_pkey = $getuser->users_pkey");

		// Log successful login --->
		$logaction=$db->query("INSERT INTO logs (log_pkey, logtime,ip_address,content) VALUES (nextval('log_seq'), now(), '$ip', '$username logged in successfully')") or die("could not insert into logs in login.php");


	}

	$_SESSION['loggedin']="yes";
	$_SESSION['username']="$username via GeoPass";
	$_SESSION['userpkey']=$userpkey;
	$_SESSION['logintype']="geopass";

	//print_r($_SESSION);exit();
	
	header("Location:".$_SESSION['currentpage']);
	exit();
	
}else{

	echo "invalid login";

}

?>