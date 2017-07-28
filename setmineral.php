<?PHP
/**
 * setmineral.php
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

$minerals=$db->get_results("select distinct(mineral) from sample where mineral is not null and mineral != ''");

?>

<script src="mineralscart.js" type="text/javascript"></script>


<h1>Set Mineral</h1>


<form name="myform" action="search.php" method="post">
<table cellspacing=5 cellpadding=5 border=0 align="center">

<tr>
	<td>



		<H3>Mineral:</H3>
		<div style="background-color:#DDDDDD;padding:5px;">
		<table>
			<tr>
				<td>
		
					<select name="mineraltypegroup[]" size="12" style="width:200px;" id="mineraltypegroup" multiple>
<?
foreach($minerals as $mineral){
?>
						<option value="<?=$mineral->mineral?>"><?=$mineral->mineral?>
<?
}
?>
<!---
						<option value="Mineral 1">Mineral 1
						<option value="Mineral 2">Mineral 2
						<option value="Mineral 3">Mineral 3
						<option value="Mineral 4">Mineral 4
						<option value="Mineral 5">Mineral 5
--->
					</select>
				</td>
				<td>
					<center>
					<input type="button" value=">>" onclick="javascript:addminerals();"><br><br>
					<input type="button" value="<<" onclick="javascript:removeminerals();"><br><br>
					<input type="button" value="CLEAR" onclick="javascript:clearminerals();">
					</center>
				</td>
				<td>
		
					<select name="mineralchosentype[]" size="12" style="width:200px;" id="mineralchosentype" multiple>
						
					</select>
				</td>
			</tr>
		</table>
		<INPUT type="hidden" name="minerals" id="minerals" value="">
		</div>





	</td>
</tr>



<tr><td colspan="2" align="right"><input name="" type="submit" value="Submit"></td></tr>
</table>
<input type="hidden" name="pkey" value="<?=$pkey?>">
</form>


<?
include("includes/geochron-secondary-footer.htm");
?>

