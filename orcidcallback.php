<?PHP
/**
 * orcidcallback.php
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
define('OAUTH_REDIRECT_URI', 'http://dev.geochron.org/orcidcallback'); // URL of this script

define('OAUTH_AUTHORIZATION_URL', 'https://orcid.org/oauth/authorize');
define('OAUTH_TOKEN_URL', 'https://pub.orcid.org/oauth/token');

// redirect the user to approve the application
if (!$_GET['code']) {
  /*
  $state = bin2hex(openssl_random_pseudo_bytes(16));
  setcookie('oauth_state', $state, time() + 3600, null, null, false, true);
  $url = OAUTH_AUTHORIZATION_URL . '?' . http_build_query(array(
      'response_type' => 'code',
      'client_id' => OAUTH_CLIENT_ID,
      'redirect_uri' => OAUTH_REDIRECT_URI,
      'scope' => '/authenticate',
      'state' => $state,
  ));
  header('Location: ' . $url);
  */
  header("Location:/");
  exit();
}

// code is returned, check the state
if (!$_GET['state'] || $_GET['state'] !== $_COOKIE['oauth_state']) {
  //exit('Invalid state');
}
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
    'client_id' => $orcidclientid,
    'client_secret' => $orcidclientsecret,
  ))
));
$result = curl_exec($curl);
$response = json_decode($result, true);

$orcid = $response['orcid'];
$name = $response['name'];

//echo "orcid: $orcid<br>";
//echo "name: $name";
//exit();

if($orcid!=""){

	include("db.php");

	$getuser=$db->get_row("select * from users where orcid='$orcid'"); 
	// If a record exists then the user is valid --->
	if (count($getuser)>0) {           

		$userpkey=$getuser->users_pkey;
		$user_level=$getuser->user_level;
		$username=$name;
		if ($getuser->EDITOR) {$editor=true;} else {$editor=false;}

		$update=$db->query("UPDATE users SET ipaddress = '$ip', Last_Login = now() WHERE users_pkey = $getuser->users_pkey");
		


		// Log successful login --->
		$logaction=$db->query("INSERT INTO logs (log_pkey, logtime,ip_address,content) VALUES (nextval('log_seq'), now(), '$ip', '$username logged in successfully via ORCID')") or die("could not insert into logs in login.php");

	}else{

		//echo "user $username does not exist yet... let's put it in here...<br><br>";
		$db->query("insert into users 	(users_pkey,
											firstname,
											lastname,
											email,
											active,
											user_level,
											orcid
										)values(
											nextval('users_seq'),
											'$name',
											'',
											'$email',
											'yes',
											'user',
											'$orcid'
											)");
		
		$getuser=$db->get_row("select * from users where orcid='$orcid'");

		$userpkey=$getuser->users_pkey;
		$user_level=$getuser->user_level;
		$username=$name;
		if ($getuser->EDITOR) {$editor=true;} else {$editor=false;}

		$update=$db->query("UPDATE users SET ipaddress = '$ip', Last_Login = now() WHERE users_pkey = $getuser->users_pkey");

		// Log successful login --->
		$logaction=$db->query("INSERT INTO logs (log_pkey, logtime,ip_address,content) VALUES (nextval('log_seq'), now(), '$ip', '$username logged in successfully via ORCID')") or die("could not insert into logs in login.php");


	}

	$_SESSION['loggedin']="yes";
	$_SESSION['username']="$username via ORCID";
	$_SESSION['userpkey']=$userpkey;
	$_SESSION['logintype']="orcid";
	
	header("Location:".$_SESSION['currentpage']);


}

?>