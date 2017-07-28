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
		//return imagejpeg($thumb,"../uploadimages/".$sample_pkey.".jpg");
		return imagejpeg($thumb,"../uploadimages/".rand(111111,999999)."_".$sample_pkey."_.jpg");
		//return "foo";
	}

	$sample_pkey=$_POST['sample_pkey'];
	
	if($sample_pkey==""){exit();}

	$tempname=$_FILES['file']['tmp_name'];

	$path = $_FILES['file']['name'];
	$ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
	$size = $_FILES['file']['size'];

	
	if($ext=="jpg"||$ext=="jpeg"||$ext=="gif"||$ext=="png"){
	
		if($ext=="jpg"||$ext=="jpeg"){$origsource=imagecreatefromjpeg($tempname);}
		if($ext=="gif"){$origsource=imagecreatefromgif($tempname);}
		if($ext=="png"){$origsource=imagecreatefrompng($tempname);}

		$myimage = resizeImage($origsource, '500', '500', $sample_pkey);

	}elseif($ext=="tiff"||$ext="tif"){
	
		move_uploaded_file ( $_FILES['file']['tmp_name'] , "../uploadimages/".rand(111111,999999)."_".$sample_pkey."_.tiff" );
	
	}



	// Work-around for setting up a session because Flash Player doesn't send the cookies
	//if (isset($_POST["PHPSESSID"])) {
	//	session_id($_POST["PHPSESSID"]);
	//}
	//session_start();

	// The Demos don't save files
	$myFile = "log.txt";
	$fh = fopen($myFile, 'a') or die("can't open file");

	fwrite($fh, "POST:\n");
	foreach($_POST as $key=>$value){

		fwrite($fh, "$key ---- $value\n\n");
	
	}

	//fwrite($fh, "file:\n");
	foreach($_FILES['file'] as $key=>$value){

		//fwrite($fh, "$key ---- $value\n\n");
	
	}
	
	$tempname=$_FILES['file']['tmp_name'];
	
	$path = $_FILES['file']['name'];
	$ext = pathinfo($path, PATHINFO_EXTENSION);
	$size = $_FILES['file']['size'];
	
	//fwrite($fh, "ext ---- $ext\n\n");
	//fwrite($fh, "tempname ---- $tempname\n\n");


	fclose($fh);


/*
	$f = print_r($_FILES,1);
	
	$myFile = "log.txt";
	$fh = fopen($myFile, 'a') or die("can't open file");
	
	fwrite($fh, "FILE Data\n\n");
	
	fwrite($fh, "$f\n\n");
	
	fwrite($fh, "*************************************\n\n");
	
	fwrite($fh, "POST Data:\n\n");
	foreach($_POST as $key=>$value){

		fwrite($fh, "$key ---- $value\n\n");
	
	}
	
	fwrite($fh, "*************************************\n\n");
	
	fclose($fh);
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	exit();
*/

?>