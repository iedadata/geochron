<?

/*
function read_and_delete_first_line($filename) {
  $file = file($filename);
  $output = $file[0];
  unset($file[0]);
  file_put_contents($filename, $file);
  return $output;
}
*/

exec("ls *.php",$filenames);

foreach($filenames as $filename){

	if($filename!="commentfiles.php"){
	
$newfile = "<?PHP
/**
 * $filename
 *
 * longdesc
 *
 * LICENSE: This source file is subject to version 4.0 of the Creative Commons
 * license that is available through the world-wide-web at the following URI:
 * https://creativecommons.org/licenses/by/4.0/
 *
 * @category   Geochemistry
 * @package    EarthChem Portal
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  IEDA (http://www.iedadata.org/)
 * @license    https://creativecommons.org/licenses/by/4.0/  Creative Commons License 4.0
 * @version    GitHub: $Id$
 * @link       http://ecp.iedadata.org
 * @see        EarthChem, Geochemistry
 */

";
		
		$file = file($filename);
	
		$x=0;
		foreach($file as $f){
			$file[$x]=str_replace("\r","",$f);
			$x++;
		}
		
		unset($file[0]);


		
		file_put_contents("newfiles/$filename",$newfile);
		file_put_contents("newfiles/$filename",$file, FILE_APPEND);
		
		echo "$filename done.\n";
	
	
	}
	
	//exit();

}







?>