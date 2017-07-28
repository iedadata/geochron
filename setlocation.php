<?PHP
/**
 * setlocation.php
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

include("db.php");
include("includes/geochron-secondary-header.htm");

$pkey=$_GET['pkey'];

$row=$db->get_row("select * from search_query where search_query_pkey=$pkey");

?>

<table width="500" border="0" align="center" cellpadding="10">
  <tr>
    <td><a href="polygonmap.php?pkey=<?=$pkey?>"><img src="mapper.jpg" border="0" /></a></td>
    <td>Click <a href="polygonmap.php?pkey=<?=$pkey?>">here</a> to define a polygon using the Geochron dynamic mapper, or use the boxes below to define an envelope manually.</td>
  </tr>
</table>

<hr/><br/>

<form action="search.php" method="POST">
<table width="630" border="0" align="center">
  <tr>
    <td width="210px">&nbsp;</td>
    <td width="210px" nowrap>North: <input name="locnorth" type="text" size="8" value="<?=$row->locnorth?>"></td>
    <td width="210px">&nbsp;</td>
  </tr>
  <tr>
    <td width="210px">West: <input type="text" name="locwest" size="8" value="<?=$row->locwest?>"></td>
    <td width="210px">&nbsp;</td>
    <td width="210px">East: <input type="text" name="loceast" size="8" value="<?=$row->loceast?>"></td>
  </tr>
  <tr>
    <td width="210px">&nbsp;</td>
    <td width="210px">South: <input type="text" name="locsouth" size="8" value="<?=$row->locsouth?>"></td>
    <td width="210px">&nbsp;</td>
  </tr>
  <tr>
    <td width="210px">&nbsp;</td>
    <td width="210px">&nbsp;</td>
    <td width="210px">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3">
    	<div align="center">
    		<b>All inputs are in decimal degrees. (e.g. 38.555 or -143.1222)</b><br><br>
    	</div>
    </td>
  </tr>
  <tr>
    <td width="210px">&nbsp;</td>
    <td width="210px"><div align="center"><input type="submit" name="button" id="button" value="Submit" /></div></td>
    <td width="210px">&nbsp;</td>
  </tr>
</table>
<input type="hidden" name="pkey" value="<?=$pkey?>">
</form>

<?
include("includes/geochron-secondary-footer.htm");
?>



