<?PHP
/**
 * setmethod.php
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

<SCRIPT type="text/javascript" src="prototype.js"></SCRIPT>
<SCRIPT type="text/javascript" src="methodajax.js"></SCRIPT>


<h1>Set Method</h1>

<cfset mylist=getquery.sampleagetype>

<form name="myform" action="search.php" method="post">
<table cellspacing=5 cellpadding=5 border=0 >
	<tr>
		<td width="200px" valign="top">
			<input type="checkbox" id="reduxcheck" value="redux" onclick="updatebox('redux')"> U-Pb<br><br>
			<input type="checkbox" id="ararcheck" value="arar" onclick="updatebox('arar')"> Ar-Ar<br><br>
			<input type="checkbox" id="helioscheck" value="helios" onclick="updatebox('helios')"> (U-Th)/He<br>
		<td valign="top">
			<div id="hiddenbox" style="display:none;">

				<H3>Age Type:</H3>
				<div style="background-color:#DDDDDD;padding:5px;">
				<table>
					<tr>
						<td>
				
							<select name="agetypelist[]" size="12"  id="agetypelist" multiple>
		
		
							</select>
		
						</td>
						<td>
							<center>
							<input type="button" value=">>" onclick="javascript:addages();"><br><br>
							<input type="button" value="<<" onclick="javascript:removeages();"><br><br>
							<input type="button" value="CLEAR" onclick="javascript:clearages();">
							</center>
						</td>
						<td>
		
				
							<select name="agechosentype[]" size="12" style="width:200px;" id="agechosentype" multiple>
								
							</select>
						</td>
					</tr>
				</table>
				<INPUT type="hidden" name="sampleagetype" id="agetype" value="">
				</div>
				<div style="text-align:right;">
				<input type="submit" name="submit" value="Submit">
				</div>

			</div>
			<div id="visiblebox" style="display:block;">
				<br>
				<br>
				<br>
				<br>
				<br>
				<br>
				<br>
				
			</div>

		</td>
	</tr>
</table>
<input type="hidden" name="pkey" value="<?=$pkey?>">
</form>

<br><br><br><br><br><br>

<?
include("includes/geochron-secondary-footer.htm");
?>

