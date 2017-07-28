<?PHP
/**
 * showages.php
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

	$rows=$db->get_results("select * from geoages order by pkey");
	
	foreach($rows as $row){
		
				//$showline="<option value=\"".$row->pkey."\">";
				$showline="";
		
		if($row->indentcount!=0){
	
			for($x=0;$x<$row->indentcount;$x++){
				//$showline.="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
				$showline.="";

			}
			
			//$showline.="&raquo; ";
		}
		
		$showline.=$row->xmllabel."\n";
		echo $showline;
	}



?>