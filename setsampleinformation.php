<?PHP
/**
 * setsampleinformation.php
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

<h1>Set Sample Information</h1>

<form name="myform" action="search.php" method="post">
<table cellspacing=5 cellpadding=5 border=0 align="center">

<tr>
	<td>Unique ID</td>
	<td>
		<input type="text" name="igsn" value="<?=$row->igsn?>" >
	</td>
</tr>
<!--
<tr>
	<td>IGSN Namespace</td>
	<td>
		<input type="text" name="igsnnamespace" value="<?=$row->igsnnamespace?>" size="3" maxlength="3" >
	</td>
</tr>
-->
<tr>
	<td>Sample Name/Number</td>
	<td>
		<input type="text" name="sample_id" value="<?=$row->sample_id?>" >
	</td>
</tr>

<tr>
	<td>Collector</td>
	<td>
		<input type="text" name="collector" value="<?=$row->collector?>" >
	</td>
</tr>

<tr><td>Sample Description</td><td><input type="text" name="sampledescription" value="<?=$row->sampledescription?>" ></td></tr>
<tr><td>Collection Method</td><td><input type="text" name="collectionmethod" value="<?=$row->collectionmethod?>" ></td></tr>
<tr><td>Sample Comment</td><td><input type="text" name="samplecomment" value="<?=$row->samplecomment?>" ></td></tr>
<tr><td>Primary Location Name</td><td><input type="text" name="primarylocationname" value="<?=$row->primarylocationname?>" ></td></tr>
<tr><td>Primary Location Type</td><td><input type="text" name="primarylocationtype" value="<?=$row->primarylocationtype?>" ></td></tr>
<tr><td>Location Description</td><td><input type="text" name="locationdescription" value="<?=$row->locationdescription?>" ></td></tr>
<tr><td>Locality</td><td><input type="text" name="locality" value="<?=$row->locality?>" ></td></tr>
<tr><td>Locality Description</td><td><input type="text" name="localitydescription" value="<?=$row->localitydescription?>" ></td></tr>
<tr><td>Country</td><td><input type="text" name="country" value="<?=$row->country?>" ></td></tr>
<tr><td>Province</td><td><input type="text" name="provice" value="<?=$row->provice?>" ></td></tr>
<tr><td>County</td><td><input type="text" name="county" value="<?=$row->county?>" ></td></tr>
<tr><td>City or Township</td><td><input type="text" name="cityortownship" value="<?=$row->cityortownship?>" ></td></tr>
<tr><td>Platform</td><td><input type="text" name="platform" value="<?=$row->platform?>" ></td></tr>
<tr><td>Platform ID</td><td><input type="text" name="platformid" value="<?=$row->platformid?>" ></td></tr>
<tr><td>Original Archival Institution</td><td><input type="text" name="originalarchivalinstitution" value="<?=$row->originalarchivalinstitution?>" ></td></tr>
<tr><td>Original Archival Contact</td><td><input type="text" name="originalarchivalcontact" value="<?=$row->originalarchivalcontact?>" ></td></tr>
<tr><td>Most Recent Archival Institution</td><td><input type="text" name="mostrecentarchivalinstitution" value="<?=$row->mostrecentarchivalinstitution?>" ></td></tr>
<tr><td>Most Recent Archival Contact</td><td><input type="text" name="mostrecentarchivalcontact" value="<?=$row->mostrecentarchivalcontact?>" ></td></tr>




<tr><td colspan="2" align="right"><input name="" type="submit" value="Submit"></td></tr>
</table>
<input type="hidden" name="pkey" value="<?=$pkey?>">
</form>


<?
include("includes/geochron-secondary-footer.htm");
?>

