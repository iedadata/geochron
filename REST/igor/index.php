<?php

/*
******************************************************************
igor REST API
Author: Jason Ash (jasonash@ku.edu)
Description: This codebase allows end-users to communicate with
			 the Geochron Database.
******************************************************************
*/


//Initialize Database
include_once "../../db.php";


//Load Base Controller
include "./controllers/MyController.php";

//Load Additional Controllers
foreach (glob("./controllers/*.php") as $filename){
    include_once $filename;
}

include "./library/Request.php";
include "./views/ApiView.php";
include "./views/JsonView.php";
include "./views/HtmlView.php";
include "./views/XMLView.php";
include "../geochronrestapi.php";

list($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']) = explode(':' , base64_decode(substr($_SERVER['HTTP_AUTHORIZATION'], 6)));

$username=$_SERVER['PHP_AUTH_USER'];
$password=$_SERVER['PHP_AUTH_PW'];

//check username/password at GeoPass
include("geopass.php");

	if($userdata=geopasslogin($username,$password)){
		//User is valid, let's check to see if they're in the database yet 
		$userrow = $db->get_row("select * from users where email='$username' limit 1");
		if($userrow->users_pkey!=""){
			$userpkey = $userrow->users_pkey;
		}else{
		
			//put in new record

			$firstname = $userdata['firstname'];
			$lastname = $userdata['lastname'];

			
			$db->query("
							insert into users	(	
									users_pkey,
									last_login,
									firstname,
									lastname,
									email,
									active,
									ipaddress,
									user_level
								) values (
									nextval('users_seq'),
									now(),
									'$firstname',
									'$lastname',
									'$username',
									'yes',
									'".$_SERVER['REMOTE_ADDR']."',
									'user'
								)
			");
						
		}
		


	}else{
		$error = $geopassloginerror;
		header("Bad Request", true, 400);
		echo "<error>$error</error>";exit();
	}



$userpkey=(int)$userpkey;
//$userpkey=3;


$request = new Request();
$grest = new GeochronRestClass($userpkey);
$grest->setUsername($username);
$grest -> setDBtHandler($db);

//log raw input for debug

if(file_exists("log.txt")){
	$rawinput = file_get_contents("php://input");
	file_put_contents ("log.txt", "\n\n************************************************************************************************************************\n\n", FILE_APPEND);
	file_put_contents ("log.txt", "REQUEST: ".ucfirst($request->url_elements[1])."\n\n", FILE_APPEND);
	
	//$thispost = print_r($_POST,true);
	//file_put_contents ("log.txt", "POST: $thispost", FILE_APPEND);
	
	file_put_contents ("log.txt", "username: $username password: $password\n\n", FILE_APPEND);
	
	file_put_contents ("log.txt", "Raw Input:\n".$rawinput, FILE_APPEND);
}




// route the request to the right place
$controller_name = ucfirst($request->url_elements[1]) . 'Controller';

$showcontroller = $request->url_elements[1];
if($showcontroller==""){$showcontroller="null";}

if (class_exists($controller_name)) {
    $controller = new $controller_name();
    $controller->setGeochronRestHandler($grest);
    $action_name = strtolower($request->verb) . 'Action';
    $result = $controller->$action_name($request);
}else{
	//send an error header with brief explanation.
	header("Bad Request", true, 404);
	$result['Error']="No such function (".$showcontroller.")";
	header('Content-Type: application/json; charset=utf8');
}

$view_name = ucfirst($request->apiformat) . 'View';
if(class_exists($view_name)) {
	$view = new $view_name();
	$view->render($result);
}else{
	header("Bad Request", true, 400);
	echo "Error: $request->format output not supported.";
}