<?

$pkey = "10068";

exec("ls /local/public/mgg/web/www.geochron.org/geochronuploadimages | grep _".$pkey."_",$files);

foreach($files as $file){
	$pp = pathinfo($file);
	$extension = strtolower($pp['extension']);
	echo "extension: $extension<br>";
}

?>