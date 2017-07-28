<?PHP
/**
 * setage.php
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

$searchsampleagelist=$row->sampleagetype;
$mylist=explode(",",$searchsampleagelist);

?>

<h1>Set Age</h1>

<cfset mylist=getquery.sampleagetype>

<form name="myform" action="search.php" method="post">
<table cellspacing=5 cellpadding=5 border=0 align="center">

<tr>
	<td>Preferred Age</td>
	<td>
		Min&nbsp;<input type="text" name="sampleagevaluemin" value="<?=$row->sampleagevaluemin?>" size="5">&nbsp;Max&nbsp;<input type="text" name="sampleagevaluemax" value="<?=$row->sampleagevaluemax?>" size="5">
		&nbsp;&nbsp;&nbsp;
		<input type="radio" name="ageunit" value="ma" <? if($row->ageunit =="ma"){echo "checked";}?>> Ma 
		<input type="radio" name="ageunit" value="ka" <? if($row->ageunit =="ka"){echo "checked";}?>>  Ka
	</td>
</tr>

<tr>
	<td>Max Age Uncertainty</td>
	<td>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="maxageuncertainty" value="<?=$row->maxageuncertainty?>" size="5">
	</td>
</tr>

<tr><td colspan="2" align="right"><input name="" type="submit" value="Submit"></td></tr>
</table>
<input type="hidden" name="pkey" value="<?=$pkey?>">
</form>


<?
include("includes/geochron-secondary-footer.htm");
?>

