<?PHP
/**
 * credentials_service.php
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


header('Content-Type: text/xml');

include("db.php");

if($_POST['username']!=""){
	$username=$_POST['username'];
	$password=$_POST['password'];
}else{
	$username=$_GET['username'];
	$password=$_GET['password'];
}

if($username=="" || $password==""){
	echo "<valid>no</valid>";
	exit();
}

/*
echo "<foo>";
echo "<tagone>username: $username</tagone>";
echo "<tagtwo>password: $password</tagtwo>";
echo "</foo>";
exit();


$mycount=$db->get_var("select count(*) from users where username='$username' and password='$password'");
*/


//for login, we are now using GEOPASS, so let's call that code here:
//this ultimately defines $userpkey for use below

//first, check for valid, then if valid check if it exists in database
//if not, put a record in
//if not valid, throw error

//http://sesar3.geoinfogeochem.org/webservices/credentials_service.php

$url = 'http://app.geosamples.org/webservices/credentials_service.php';
// testing: $url = 'http://sesar3.geoinfogeochem.org/webservices/credentials_service.php';

$fields = array(
            'username'=>urlencode($username),
            'password'=>urlencode($password)
        );

//url-ify the data for the POST
foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
rtrim($fields_string,'&');

//open connection
$ch = curl_init();

//set the url, number of POST vars, POST data
curl_setopt($ch,CURLOPT_URL,$url);
curl_setopt($ch,CURLOPT_POST,count($fields));
curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

//execute post
$result = curl_exec($ch);

//close connection
curl_close($ch);

echo $result;exit();

//load xml from $result and parse for valid==true


$dom = new DomDocument;
$dom->loadXML($result);

$validmessage=="";

$results=$dom->getElementsByTagName("results");
foreach($results as $result){

	$valids=$result->getElementsByTagName("valid");
	foreach($valids as $valid){
	
		$validmessage=$valid->textContent;
	
	}

}


if($validmessage!="yes"&&$username!="keith.sircombe@ga.gov.au"){
echo "<valid>no</valid>";
exit();
}

//if we get this far, user is valid, so let's get pkey for this user
$userpkey=$db->get_var("select users_pkey from users where email='$username'");
if($userpkey==""){
	//let's put in new record
	$db->query("insert into users (users_pkey,
									last_login,
									email,
									active,
									ipaddress,
									user_level,
									usergroup)
									values
									(nextval('users_seq'),
									now(),
									'$username',
									'yes',
									'".$_SERVER['REMOTE_ADDR']."',
									'user',
									0
									)");



}


echo "<valid>yes</valid>";


?>