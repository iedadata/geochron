<?PHP
/**
 * setlaboratory.php
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

$labnames=$db->get_results("select distinct(laboratoryname) from sample where laboratoryname is not null and publ=1");

?>

<script src="labscart.js" type="text/javascript"></script>


<h1>Choose Laboratories</h1>


<form name="myform" action="search.php" method="post">
<table cellspacing=5 cellpadding=5 border=0 align="center">

<tr>
	<td>



		<H3>Laboratory:</H3>
		<div style="background-color:#DDDDDD;padding:5px;">
		<table>
			<tr>
				<td>
		
					<select name="labnamegroup[]" size="12" style="width:200px;" id="labnamegroup" multiple>
<?
foreach($labnames as $labname){
?>
						<option value="<?=$labname->laboratoryname?>"><?=$labname->laboratoryname?>
<?
}
?>
<!---
						<option value="Lab Name 1">Lab Name 1
						<option value="Lab Name 2">Lab Name 2
						<option value="Lab Name 3">Lab Name 3
						<option value="Lab Name 4">Lab Name 4
						<option value="Lab Name 5">Lab Name 5
						<option value="Lab Name 6">Lab Name 6
						<option value="Lab Name 7">Lab Name 7
						<option value="Lab Name 8">Lab Name 8
						<option value="Lab Name 9">Lab Name 9
						<option value="Lab Name 10">Lab Name 10
						<option value="Lab Name 11">Lab Name 11
						<option value="Lab Name 12">Lab Name 12
						<option value="Lab Name 13">Lab Name 13
						<option value="Lab Name 14">Lab Name 14
						<option value="Lab Name 15">Lab Name 15
						<option value="Lab Name 16">Lab Name 16
						<option value="Lab Name 17">Lab Name 17
						<option value="Lab Name 18">Lab Name 18
						<option value="Lab Name 19">Lab Name 19
						<option value="Lab Name 20">Lab Name 20
--->
					</select>
				</td>
				<td>
					<center>
					<input type="button" value=">>" onclick="javascript:addlabs();"><br><br>
					<input type="button" value="<<" onclick="javascript:removelabs();"><br><br>
					<input type="button" value="CLEAR" onclick="javascript:clearlabs();">
					</center>
				</td>
				<td>
		
					<select name="labchosengroup[]" size="12" style="width:200px;" id="labchosengroup" multiple>
						
					</select>
				</td>
			</tr>
		</table>
		<INPUT type="hidden" name="labnames" id="labnames" value="">
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

