<?PHP
/**
 * uploadimage.php
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

//var_dump($_SESSION);
//exit();

include("logincheck.php");

include("db.php");

$ip=$_SERVER['remote_addr'];
include("includes/geochron-secondary-header.htm");

?>
<div class="pagetitle">Image Upload</div>
<?

$pkey=$_POST['pkey'];
$ip=$_SERVER['remote_addr'];
$filename=$_POST['filename'];

$username=$_SESSION['username'];
$userpkey=$_SESSION['userpkey'];

	if($_POST['filesubmit']!=""){
  
		//echo "<br><br>";
		//print_r($_FILES);
		//echo "<br><br>";
		
		$finalimagesize=150; //size of image in pixels (square)
		
		//$orig_filename=$_FILES['filetoupload']['name'];
		
		$filename=$_FILES['filetoupload']['name'];
		$filenamearr=explode(".",$filename);
		$filenamelen=count($filenamearr);
		$fileext=$filenamearr[$filenamelen-1];
		
		//echo "file extension: $fileext<br><br>";
		
		
		if(strtolower($fileext)=="jpg" || strtolower($fileext)=="jpeg" || strtolower($fileext)=="gif"){
		
			$pkey=$db->get_var("select nextval('image_seq')");
		
		
			//echo "filenamelen: $filenamelen<br><Br>";
			
			
			
			//echo "filename: $filename<br><br>";
			
			//var_dump($_FILES);
			
			//move uploaded image to tmp folder so we can crop it.
			//need to add some functionality here to grab the .xxx 
			//notation from file name so we can have different kinds
			//of images
			
			$tempname=$_FILES['filetoupload']['tmp_name'];
			
			//echo "temp name: $tempname";
			
			exec("identify $tempname | awk '//{print $3}'", $imagesizes);
			
			
			
			foreach($imagesizes as $imfoo){
				$imagesize=$imfoo;
			}
			
			//echo "imagesize: $imagesize<Br>";
			$sizes=explode("x",$imagesize);
			$currwidth=$sizes[0];
			$currheight=$sizes[1];
			
			//echo "width=$currwidth<br>";
			//echo "height=$currheight<br>";
			
			if($currwidth>$currheight){ //image is 'horizontal'
			
				exec("convert $tempname -resize -5x$finalimagesize $tempname", $blah);
				
				//echo "convert $tempname -resize -5x$finalimagesize $tempname";
				//echo "<br>";
				
				exec("identify $tempname | awk '//{print $3}'", $imagesizes);
				
				//echo "identify $tempname | awk '//{print $3}'";
				//echo "<br>";
		
				foreach($imagesizes as $imfoo){
					$imagesize=$imfoo;
				}
		
				//echo "imagesize: $imagesize<Br>";
				$sizes=explode("x",$imagesize);
				$currwidth=$sizes[0];
				$currheight=$sizes[1];
				
				//echo "largerwidth=$currwidth<br>";
				//echo "largerheight=$currheight<br>";
				
				$offset=($currwidth-$finalimagesize)/2;
				
				//echo "offset=$offset<br>";
				
				exec("convert $tempname -crop ".$finalimagesize."x$finalimagesize+$offset+0 uploadimages/$pkey.jpg",$blah);
				
				//echo "convert $tempname -crop ".$finalimagesize."x$finalimagesize+$offset+0 uploadimages/$pkey.jpg";
				//echo "<br>";
				
				//echo "convert $tempname -crop ".$finalimagesize."x$finalimagesize+$offset+0 uploadimages/1.jpg<Br>";
		
				//move_uploaded_file($_FILES['filetoupload']['tmp_name'], "uploadimages/1.jpg");
				
				$fileurl= "<img src=\"uploadimages/$pkey.jpg\">";
			
			}else{ //image is 'vertical'
			
			//echo "vertical<br>";
			
				//echo "convert $tempname -resize ".$finalimagesize."x-5 $tempname";
				exec("convert $tempname -resize ".$finalimagesize."x-5 $tempname", $blah);
				//echo "<br>";
				//echo "identify $tempname | awk '//{print $3}'";
				exec("identify $tempname | awk '//{print $3}'", $imagesizes);
				//echo "<br>";
		
				foreach($imagesizes as $imfoo){
					$imagesize=$imfoo;
				}
		
				//echo "imagesize: $imagesize<Br>";
				$sizes=explode("x",$imagesize);
				$currwidth=$sizes[0];
				$currheight=$sizes[1];
				
				//echo "largerwidth=$currwidth<br>";
				//echo "largerheight=$currheight<br>";
				
				$offset=($currheight-$finalimagesize)/2;
				
				//echo "offset=$offset<br>";
				
				exec("convert $tempname -crop ".$finalimagesize."x$finalimagesize+0+$offset uploadimages/$pkey.jpg",$blah);
				
				//echo "convert $tempname -crop ".$finalimagesize."x$finalimagesize+0+$offset uploadimages/$pkey.jpg";
				
				//echo "convert $tempname -crop ".$finalimagesize."x$finalimagesize+0+$offset uploadimages/1.jpg<Br>";
		
				//move_uploaded_file($_FILES['filetoupload']['tmp_name'], "uploadimages/1.$fileext");
				
				$fileurl= "<img src=\"uploadimages/$pkey.jpg\">";
			
			//move_uploaded_file($_FILES['filetoupload']['tmp_name'], "tmpimages/1.jpg");
			
			}//end vert vs horiz
			
			?>
			
			<table cellpadding="10">
			<tr>
			<td valign="top">
			Upload success!<br><br>
			Your image has been uploaded and appears to the right.<br><br>
			Here is the URL to this image:<br><br>
			<a href="http://www.geochron.org/uploadimages/<?=$pkey?>.jpg" target="_blank">http://www.geochron.org/uploadimages/<?=$pkey?>.jpg</a><br>
			</td>
			<td>
			<img src="http://www.geochron.org/uploadimages/<?=$pkey?>.jpg">
			</td>
			</tr>
			</table>

			<?

				$showstart="no";

		}else{//end if filename is OK
		
		?>
		<br>
		Incorrect file type detected.<br><br>
		Only jpeg and gif files are allowed.<br><br>
		Please verify your file and try again.<br><br>
		<?
		
		}
		
		//log here
		$db->query("insert into logs ( log_pkey, logtime, ip_address, content ) values 
		( nextval('log_seq'), now(), '$ip', 'Image $pkey uploaded by $username.' )");

	}//end if post
	

	if($showstart!="no"){
	
	?>
	
	<br>The form below allows you to upload image files for inclusion in uploaded XML documents.<br><br>
	The preferred image size is 150px x 150px. Anything smaller or larger will be resized automatically.<br><br>
	Only jpeg and gif files are allowed.<br><br><br>
	
	
	
	<?	
	
	}else{
	
	?>
	
	<br>
	You can use the form below to upload another image file.<br><br>
	
	
	
	<?	
	
	}

?>
    <form action="" method="post" enctype="multipart/form-data">
      <input name="filetoupload" type="file"> &nbsp;
      <input name="filesubmit" type="submit" value="Upload file">
    </form>
<?

//move_uploaded_file($_FILES['filetoupload']['tmp_name'], "files/$geochron_pkey.xml");












?>



