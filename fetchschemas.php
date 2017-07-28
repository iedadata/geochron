<?PHP
/**
 * fetchschemas.php
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

//SELECT last_value FROM test_id_seq;
//SELECT c.relname FROM pg_class c WHERE c.relkind = 'S';

$rows=$db->get_results("select * from pg_class c WHERE c.relkind = 'S' order by relname");

echo "<table border=\"1\">";

foreach($rows as $row){

	$seqname=$row->relname;
	
	$lastval = $db->get_var("select last_value from $seqname");
	
	echo "<tr><td>$seqname</td><td>$lastval</td></tr>";


}

echo "</table>";



?>