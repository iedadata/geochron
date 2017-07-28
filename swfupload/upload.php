<?php

	function resizeImage($source, $newwidth, $newheight, $sample_pkey){

		$width = imageSX($source);
		$height = imageSY($source);

		if($width > $height && $newheight < $height){
			$newheight = $height / ($width / $newwidth);
		} else if ($width < $height && $newwidth < $width) {
			$newwidth = $width / ($height / $newheight);   
		} else {
			$newwidth = $width;
			$newheight = $height;
		}
		$thumb = imagecreatetruecolor($newwidth, $newheight);

		imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
		return imagejpeg($thumb,"../uploadimages/".$sample_pkey.".jpg");
		//return "foo";
	}

	$sample_pkey=$_POST['sample_pkey'];

	$tempname=$_FILES['Filedata']['tmp_name'];

	$path = $_FILES['Filedata']['name'];
	$ext = pathinfo($path, PATHINFO_EXTENSION);
	$size = $_FILES['Filedata']['size'];

	if($ext=="jpg"||$ext=="jpeg"){$origsource=imagecreatefromjpeg($tempname);}
	if($ext=="gif"){$origsource=imagecreatefromgif($tempname);}
	if($ext=="png"){$origsource=imagecreatefrompng($tempname);}

	$myimage = resizeImage($origsource, '500', '500', $sample_pkey);





	// Work-around for setting up a session because Flash Player doesn't send the cookies
	if (isset($_POST["PHPSESSID"])) {
		session_id($_POST["PHPSESSID"]);
	}
	session_start();

	// The Demos don't save files
	$myFile = "../swflog.txt";
	$fh = fopen($myFile, 'a') or die("can't open file");

	fwrite($fh, "POST:\n");
	foreach($_POST as $key=>$value){

		fwrite($fh, "$key ---- $value\n\n");
	
	}

	fwrite($fh, "Filedata:\n");
	foreach($_FILES['Filedata'] as $key=>$value){

		fwrite($fh, "$key ---- $value\n\n");
	
	}
	
	$tempname=$_FILES['Filedata']['tmp_name'];
	
	$path = $_FILES['Filedata']['name'];
	$ext = pathinfo($path, PATHINFO_EXTENSION);
	$size = $_FILES['Filedata']['size'];
	
	fwrite($fh, "ext ---- $ext\n\n");
	fwrite($fh, "tempname ---- $tempname\n\n");


	fclose($fh);

?>