<?PHP
/**
 * uploadservice.php
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



foreach($_POST as $key=>$value){
	$logdata.="$key : $value \n";
}
$myFile = "log.txt";
$fh = fopen($myFile, 'a') or die("can't open file");
fwrite($fh, $logdata."");
fclose($fh);


header('Content-Type: text/xml');

//ini_set('error_reporting', '^ E_WARNING');

libxml_use_internal_errors(true);

//print_r(ini_get_all());
//exit();

include("db.php");










if($_POST['content']!=""){


	//set xml content here
	$stringdata=stripslashes($_POST['content']);
	
	$overwrite=$_POST['overwrite'];
	
	if($_POST['public']=="no"){
		$publ=0;
	}else{
		$publ=1;
	}
	
	$username=$_POST['username'];
	$password=$_POST['password'];


}elseif($_FILES['filetoupload']['tmp_name']!=""){
 	
	$zip = new ZipArchive;
	$res = $zip->open($_FILES['filetoupload']['tmp_name']);
	if ($res === TRUE) {

		$fp = $zip->getStream('tempDataForAliquotUpload');
		if(!$fp) exit("failed to open zip file\n");
	
		while (!feof($fp)) {
			$contents .= fread($fp, 2);
		}
	
		fclose($fp);
		
		parse_str($contents);
		//echo "username: $username<br><br>";
		//echo "password: $password<br><br>";

		if($public=="no"){
			$publ=0;
		}else{
			$publ=1;
		}

		$content=stripslashes(urldecode($content));
		
		//echo $content;
		$stringdata=$content;

		$zip->close();
		//echo "ok";
	} else {
echo "<results>
<error>yes</error>
<message>Invalid Zip File.</message>
</results>";exit();
	}



}else{ //no post and no file

echo "<results>
<error>yes</error>
<message>No Data Provided.</message>
</results>";
exit();

}













//echo "username: $username<br>";
//echo "userpkey: $userpkey<br><br>";

/* switch this up to use geopass instead

$usercount=$db->get_var("select count(*) from users where username='".$username."' and password='".$password."'");

if($usercount==0){
echo "<results>
<error>yes</error>
<message>Invalid Username/Password.</message>
</results>";
exit();
}

*/



//for login, we are now using GEOPASS, so let's call that code here:
//this ultimately defines $userpkey for use below

include("servicelogin.php");










$ip=$_SERVER['REMOTE_ADDR'];

//$filepkey=$db->get_var("select nextval('geochron_seq')");

$filerand=rand(0,999999);

//write file contents
$myfile = "temp/$filerand.xml";
$fh = fopen($myfile, 'w') or die("can't open file");
//$stringdata = $_POST['content'];

//$stringdata = preg_replace("/[\n\r]/","",$_POST['content']); 



fwrite($fh, $stringdata);
fclose($fh);

//now parse file
	$dom = new DomDocument;
	$xmlfile = $myfile;

	//Load the xml document in the DOMDocument object
	//if($xdoc->Load($xmlfile,LIBXML_NOERROR)){
	if($dom->Load($xmlfile)){


		include("modularloader.php");
		
		if($moderror==""){
			echo "<results>
<error>no</error>
<message>Upload Success.</message>
</results>";
exec("mv $myfile files/$filename", $foobar);
		}else{
			echo "<results>
<error>yes</error>
<message>Error: $moderror</message>
</results>";
//exec("rm -f $myfile");
		}

	
	
  	}else{
  	
		$errors = libxml_get_errors();
		foreach($errors as $err){
			$moderror=$moderror."Line ".$err->line.": ".$err->message;
		}
		
		$moderror=htmlspecialchars($moderror);

  		echo "<results>
<error>yes</error>
<message>Error: $moderror. Please check file and try again.</message>
</results>";





//exec("rm -f $myfile");





  	
  	}//end if file loads





?>
