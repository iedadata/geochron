<?PHP
/**
 * downloadfile.php
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

include("db.php");

session_start();

// **************** get username stuff here for the search ***************
if($_SESSION['username']!=""){
	$username=$_SESSION['username'];
	$userrow=$db->get_row("select * from users where email='$username'");
	$group=$userrow->usergroup;
	$userpkey=$userrow->users_pkey;
}elseif($_POST['username']!="" & $_POST['password']!=""){
	$username=$_POST['username'];
	$password=$_POST['password'];
	$userrow=$db->get_row("select * from users where username='$username' and password='$password'");
	$group=$userrow->usergroup;
	$userpkey=$userrow->users_pkey;
}

if($group==0 or $group==""){
	$group=99999;
}

if($userpkey==""){
	$userpkey=99999;
}

//*************************************************************************

//echo "group: $group userpkey: $userpkey <br><br>";

if($_GET['pkey']!=""){
	$pkey=$_GET['pkey'];
}elseif($_GET['id']!="" && $_GET['id']!="" ){
	$id=$_GET['id'];
	$name=$_GET['name'];
	//echo "name: $name id: $id<br>";


	$pkey=$db->get_var("select sample_pkey from sample where igsn like '%$id' and sample_id='$name'");
	//echo "pkey: $pkey";

	//echo "select sample_pkey from sample where igsn like '%$id' and sample_id='$name'";

}else{
	exit();
}


//echo "pkey: $pkey";
//exit();

/*
$row=$db->get_row("select * from sample 
					left join users on sample.userpkey = users.users_pkey
					where sample.sample_pkey=$pkey
					and (sample.publ=1 or users.usergroup=$group or users.users_pkey=$userpkey)");
*/



$row=$db->get_row("select * from sample
					left join groupsamplerelate on sample.sample_pkey = groupsamplerelate.sample_pkey
					left join grouprelate on grouprelate.group_pkey = groupsamplerelate.group_pkey
					where sample.sample_pkey = $pkey
					and (sample.publ=1 or (sample.userpkey = $userpkey or grouprelate.users_pkey=$userpkey));
					");

$filename=$row->filename;

$origfilename=$row->orig_filename;

$ecproject=$row->ecproject;


if($origfilename==""){
	$origfilename="geochron_$pkey.xml";
}


if($filename==""){
	echo "file is private";
	exit();
}

//echo "$filename"; exit();

//log download here
$downloadtype="single sample";
include("loghit.php");

if($ecproject=="zips"){

	$filename=str_replace(".xml",".zip",$filename);
	$origfilename=str_replace(".xml",".zip",$origfilename);


	header('Content-Type: application/zip');
	header("Content-Disposition: attachment; filename=$origfilename");
	
	readfile("files/$filename");

}elseif($ecproject=="uthhelegacy"){

	$filename=str_replace(".xml",".xls",$filename);
	$origfilename=str_replace(".xml",".xls",$origfilename);


	header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
	header("Content-Disposition: attachment; filename=$origfilename");
	readfile("files/$filename");

}elseif($ecproject=="squid"||$ecproject=="squid2"){

	$filename=str_replace(".xml",".zip",$filename);
	$origfilename="geochron_download_".$filename;


	header('Content-Type: application/zip');
	header("Content-Disposition: attachment; filename=$origfilename");
	
	readfile("files/$filename");

}elseif($ecproject=="fissiontrack"){

	$filename=str_replace(".xml",".fissiontrack",$filename);

	header('Content-Type:   application/vnd.ms-excel; charset=utf-8');
	header("Content-Disposition: attachment; filename=$origfilename");
	
	readfile("files/$filename");
	
	//echo "files/$filename";

}elseif($ecproject=="ararxls"){

	$filename=str_replace(".xml",".ararxls",$filename);

	header('Content-Type:   application/vnd.ms-excel; charset=utf-8');
	header("Content-Disposition: attachment; filename=$origfilename");
	
	readfile("files/$filename");
	
	//echo "files/$filename";

}else{

	header('Content-Type: text/xml');
	header("Content-Disposition: attachment; filename=$origfilename");
	
	readfile("files/$filename");
}


?>