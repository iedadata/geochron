<?PHP
/**
 * googleoauth2callback.php
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

//Include GP config file && User class
include_once 'googleoauthconfig.php';

$ip=$_SERVER['REMOTE_ADDR'];

include("db.php");

function dumpVar($var){
	echo "<pre>";
	print_r($var);
	echo "<pre>";
}

if(isset($_GET['code'])){
    $gClient->authenticate($_GET['code']);
    $_SESSION['token'] = $gClient->getAccessToken();
    header('Location: ' . filter_var($redirectURL, FILTER_SANITIZE_URL));
}

if (isset($_SESSION['token'])) {
    $gClient->setAccessToken($_SESSION['token']);
}

if ($gClient->getAccessToken()) {
    
    //user logged in, check to see if they're in the db yet
    
    //Get user profile data from google
    $gpUserProfile = $google_oauthV2->userinfo->get();
    
    //dumpVar($gpUserProfile);exit();

	$firstname = $gpUserProfile->getGivenName();
	$lastname = $gpUserProfile->getFamilyName();
	$google_id = $gpUserProfile->getId();
	$email = $gpUserProfile->getEmail();

	$getuser=$db->get_row("select * from users where google_id='$google_id'"); 
	// If a record exists then the user is valid --->
	if (count($getuser)>0) {           

		$userpkey=$getuser->users_pkey;
		$user_level=$getuser->user_level;
		$username=$getuser->email;
		if ($getuser->EDITOR) {$editor=true;} else {$editor=false;}

		$update=$db->query("UPDATE users SET ipaddress = '$ip', Last_Login = now() WHERE users_pkey = $getuser->users_pkey");
		


		// Log successful login --->
		$logaction=$db->query("INSERT INTO logs (log_pkey, logtime,ip_address,content) VALUES (nextval('log_seq'), now(), '$ip', '$username logged in successfully via Google')") or die("could not insert into logs in login.php");

	}else{

		//echo "user $username does not exist yet... let's put it in here...<br><br>";
		$db->query("insert into users 	(users_pkey,
											firstname,
											lastname,
											email,
											active,
											user_level,
											google_id
										)values(
											nextval('users_seq'),
											'$firstname',
											'$lastname',
											'$email',
											'yes',
											'user',
											'$google_id'
											)");
		
		$getuser=$db->get_row("select * from users where google_id='$google_id'");

		$userpkey=$getuser->users_pkey;
		$user_level=$getuser->user_level;
		$username=$getuser->email;
		if ($getuser->EDITOR) {$editor=true;} else {$editor=false;}

		$update=$db->query("UPDATE users SET ipaddress = '$ip', Last_Login = now() WHERE users_pkey = $getuser->users_pkey");

		// Log successful login --->
		$logaction=$db->query("INSERT INTO logs (log_pkey, logtime,ip_address,content) VALUES (nextval('log_seq'), now(), '$ip', '$username logged in successfully via Google')") or die("could not insert into logs in login.php");


	}

	$_SESSION['loggedin']="yes";
	$_SESSION['username']="$username via Google";
	$_SESSION['userpkey']=$userpkey;
	$_SESSION['logintype']="google";
	
	header("Location:".$_SESSION['currentpage']);

}

?>