<?PHP
/**
 * massspecimageservice.php
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


//print_r($_FILES);
//print_r($_POST);
//exit();

header('Content-Type: text/xml');

include("db.php");

$ip=$_SERVER['remote_addr'];




$ip=$_SERVER['remote_addr'];

$username="massspec";
$password="massspec";
$userpkey=99999;
$imagetype="photo";

if($imagetype!="concordia"&&$imagetype!="weighted_mean"&&$imagetype!="probability_density"&&$imagetype!="histogram"&&$imagetype!="photo"&&$imagetype!="report"){
echo"<results>
	<error>yes</error>
	<message>Incorrect value for imagetype</message>
</results>";
exit();
}


  
		//echo "<br><br>";
		//var_dump($_FILES);
		//echo "<br><br>";
		
		//$finalimagesize=150; //size of image in pixels (square)
		$finalimagesize=256; //size of image in pixels (square)
		
		//$orig_filename=$_FILES['filetoupload']['name'];
		
		$filename=$_FILES['filMyFile']['name'];
		$filenamearr=explode(".",$filename);
		$filenamelen=count($filenamearr);
		$fileext=$filenamearr[$filenamelen-1];
		$filerawname=$filenamearr[$filenamelen-2];
		$filerawname=str_replace(" ","_",$filerawname);
		$tempname=$_FILES['filMyFile']['tmp_name'];
		
		//echo "file extension: $fileext<br><br>";
		
		
		if(strtolower($fileext)=="jpg" || strtolower($fileext)=="jpeg" || strtolower($fileext)=="gif"){
		
			$pkey=$db->get_var("select nextval('image_seq')");
			
			//echo "temp name: $tempname";
			
			exec("/bin/cp $tempname uploadimages/$pkey-$imagetype-$filerawname.$fileext",$blah);
			//exec("convert $tempname -resize ".$finalimagesize."x uploadimages/$pkey-$imagetype-$filerawname.jpg",$blah);
			//echo("convert $tempname -resize ".$finalimagesize."x uploadimages/$pkey-$filerawname.jpg");
			

			

			
echo"<results>
	<error>no</error>
	<message>Image Upload Success</message>
	<imageurl>http://www.geochronportal.org/uploadimages/$pkey-$imagetype-$filerawname.jpg</imageurl>
</results>";

			//log here
			$db->query("insert into logs ( log_pkey, logtime, ip_address, content ) values 
			( nextval('log_seq'), now(), '$ip', 'Image $pkey-$imagetype-$filerawname.jpg uploaded by $username.' )");

		}elseif(strtolower($fileext)=="svg"||strtolower($fileext)=="pdf"||strtolower($fileext)=="xml"){
			
			$pkey=$db->get_var("select nextval('image_seq')");
		
			//move svg to uploadimages
			exec("/bin/cp $tempname uploadimages/$pkey-$imagetype-$filerawname.$fileext",$blah);
			
echo"<results>
	<error>no</error>
	<message>Image Upload Success</message>
	<imageurl>http://www.geochronportal.com/uploadimages/$pkey-$imagetype-$filerawname.svg</imageurl>
</results>";

			//log here
			$db->query("insert into logs ( log_pkey, logtime, ip_address, content ) values 
			( nextval('log_seq'), now(), '$ip', 'Image $pkey-$imagetype-$filerawname.svg uploaded by $username.' )");

		}else{//end if filename is OK
		
echo"<results>
	<error>yes</error>
	<message>Incorrect File Type</message>
</results>";

		
		}
		



?>



