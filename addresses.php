<?PHP
/**
 * addresses.php
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

require_once 'Excel/reader.php';

$data = new Spreadsheet_Excel_Reader();

$data->setOutputEncoding('CP1251');

$data->read('pamlist.xls');

error_reporting(E_ALL ^ E_NOTICE);

	for ($i=2;$i<=58;$i++) {
	

			if(trim($data->sheets[0]['cells'][$i][2]!="")){echo trim($data->sheets[0]['cells'][$i][2])." ";}
			if(trim($data->sheets[0]['cells'][$i][1]!="")){echo trim($data->sheets[0]['cells'][$i][1])."<br>";}
			if(trim($data->sheets[0]['cells'][$i][3]!="")){echo trim($data->sheets[0]['cells'][$i][3])."<br>";}
			if(trim($data->sheets[0]['cells'][$i][4]!="")){echo trim($data->sheets[0]['cells'][$i][4])."<br>";}
			if(trim($data->sheets[0]['cells'][$i][5]!="")){echo trim($data->sheets[0]['cells'][$i][5])."<br>";}
			if(trim($data->sheets[0]['cells'][$i][6]!="")){echo trim($data->sheets[0]['cells'][$i][6])."<br>";}
			if(trim($data->sheets[0]['cells'][$i][7]!="")){echo trim($data->sheets[0]['cells'][$i][7])."<br>";}
			if(trim($data->sheets[0]['cells'][$i][8]!="")){echo trim($data->sheets[0]['cells'][$i][8])."<br>";}
			if(trim($data->sheets[0]['cells'][$i][9]!="")){echo trim($data->sheets[0]['cells'][$i][9]).", ";}
			if(trim($data->sheets[0]['cells'][$i][10]!="")){echo trim($data->sheets[0]['cells'][$i][10])." ";}
			if(trim($data->sheets[0]['cells'][$i][11]!="")){echo trim($data->sheets[0]['cells'][$i][11])." <br>";}
			if(trim($data->sheets[0]['cells'][$i][12]!="")){echo trim($data->sheets[0]['cells'][$i][12])." <br>";}



			echo "<br>";
		
	}

?>