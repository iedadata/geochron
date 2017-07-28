<?PHP
/**
 * fetchschema.php
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

//header ("Content-Type:text/xml");

//https://raw.githubusercontent.com/EARTHTIME/Schema/master/AliquotXMLSchema.xsd

//exit();

function fetchurl($myurl,$first){
	//if($lines=file_get_contents('')){
	if($lines=file_get_contents($myurl)){
		$lines=split("\n",$lines);
		
		foreach($lines as $line){
		
			$pos = strpos($line, "xs:include");
			if($pos>0){
				//call recursion
				$thisline=$line;
				$line=str_replace(" ","",$line);
				$line=str_replace("<xs:includeschemaLocation=\"","",$line);
				$line=str_replace("\"/>","",$line);
				$line=str_replace("\r","",$line);
				
				//$thisline=str_replace("http://earth-time.org/projects/upb/public_data/XSD/","http://www.geochron.org/upbreduxschemas/",$thisline);
				$thisline=str_replace("https://raw.githubusercontent.com/EARTHTIME/Schema/master/","http://www.geochron.org/upbreduxschemas/",$thisline);
				$myfile.=$thisline."\n";
				
				//echo $line."\n";
				//$mycontent.="*".$line."*\n";
				//$line="'$line'";
				fetchurl($line,'no');
				
			}else{
				$line=str_replace(" type=\"xs:decimal\"","",$line);
				$line=str_replace(" fixed=\"0.0\"","",$line);
				$line=str_replace(" fixed =\"0.0\"","",$line);
				// fixed ="0.0"
				$myfile.=$line."\n";
			}
		
		}
		


	}

		//write file
		//$filename=str_replace("http://earth-time.org/projects/upb/public_data/XSD/","/local/public/mgg/web/www.geochron.org/htdocs/upbreduxschemas/",$myurl);
		$filename=str_replace("https://raw.githubusercontent.com/EARTHTIME/Schema/master/","/local/public/mgg/web/www.geochron.org/htdocs/upbreduxschemas/",$myurl);
		//echo "jasonfile: filename: $filename \n$myfile\n\n\n";
		//echo "jasonfile: filename: $filename \n";

		
		$writefile = "$filename";
		$fh = fopen($writefile, 'w') or die("can't open file");
		fwrite($fh, $myfile);
		fclose($fh);
		

	//return $myfile;
}


//'http://earth-time.org/projects/upb/public_data/XSD/ValueModelXMLSchema.xsd'
//http://www.earth-time.org/projects/upb/public_data/XSD/AliquotXMLSchema.xsd

//fetchurl('http://earth-time.org/projects/upb/public_data/XSD/AliquotXMLSchema.xsd','yes');
fetchurl('https://raw.githubusercontent.com/EARTHTIME/Schema/master/AliquotXMLSchema.xsd','yes');
//echo $mycontent;

?>