<?PHP
/**
 * githubcallback.php
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

$ip=$_SERVER['REMOTE_ADDR'];

if(!$_SESSION['currentpage'] || $_SESSION['currentpage']=="/login"){
	$_SESSION['currentpage']="managedata.php";
}

function dumpVar($var){
	echo "<pre>";
	print_r($var);
	echo "<pre>";
}

include("includes/config.inc.php");

// Register your client at https://orcid.org/developer-tools and replace the details below
define('OAUTH_REDIRECT_URI', 'http://dev.geochron.org/githubcallback'); // URL of this script

define('OAUTH_TOKEN_URL', 'https://github.com/login/oauth/access_token');

// fetch the access token
$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => OAUTH_TOKEN_URL,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_HTTPHEADER => array('Accept: application/json'),
  CURLOPT_POST => true,
  CURLOPT_POSTFIELDS => http_build_query(array(
    'code' => $_GET['code'],
    'grant_type' => 'authorization_code',
    'client_id' => $githubclientid,
    'client_secret' => $githubclientsecret,
    'scope' => "user user:email"
  ))
));
$result = curl_exec($curl);
$response = json_decode($result, true);

$token = $response['access_token'];

//echo "token: $token";exit();

if($token!=""){

	// fetch the access token
	$curl = curl_init();
	curl_setopt_array($curl, array(
	  CURLOPT_URL => "https://api.github.com/user?access_token=$token",
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_HTTPHEADER => array('Accept: application/json','User-Agent: Geochron Web')
	));
	$result = curl_exec($curl);
	$result = json_decode($result);
	
	$githubid = $result->id;
	$login = $result->login;
	
	if($login && $githubid){
	
		include("db.php");

		$getuser=$db->get_row("select * from users where githubid='$githubid'"); 
		// If a record exists then the user is valid --->
		if (count($getuser)>0) {           

			$userpkey=$getuser->users_pkey;
			$user_level=$getuser->user_level;
			$username=$login;
			if ($getuser->EDITOR) {$editor=true;} else {$editor=false;}

			$update=$db->query("UPDATE users SET ipaddress = '$ip', Last_Login = now() WHERE users_pkey = $getuser->users_pkey");
		


			// Log successful login --->
			$logaction=$db->query("INSERT INTO logs (log_pkey, logtime,ip_address,content) VALUES (nextval('log_seq'), now(), '$ip', '$username logged in successfully via GitHub')") or die("could not insert into logs in login.php");

		}else{

			//echo "user $username does not exist yet... let's put it in here...<br><br>";
			$db->query("insert into users 	(users_pkey,
												firstname,
												lastname,
												email,
												active,
												user_level,
												githubid
											)values(
												nextval('users_seq'),
												'$login',
												'',
												'$email',
												'yes',
												'user',
												'$githubid'
												)");
		
			$getuser=$db->get_row("select * from users where githubid='$githubid'");

			$userpkey=$getuser->users_pkey;
			$user_level=$getuser->user_level;
			$username=$name;
			if ($getuser->EDITOR) {$editor=true;} else {$editor=false;}

			$update=$db->query("UPDATE users SET ipaddress = '$ip', Last_Login = now() WHERE users_pkey = $getuser->users_pkey");

			// Log successful login --->
			$logaction=$db->query("INSERT INTO logs (log_pkey, logtime,ip_address,content) VALUES (nextval('log_seq'), now(), '$ip', '$username logged in successfully via Github')") or die("could not insert into logs in login.php");

		}

		$_SESSION['loggedin']="yes";
		$_SESSION['username']="$login via GitHub";
		$_SESSION['userpkey']=$userpkey;
		$_SESSION['logintype']="github";
	
		header("Location:".$_SESSION['currentpage']);

	}else{
	
		echo "error at github occurred.";
		exit();

	}

}

?>