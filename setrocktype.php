<?PHP
/**
 * setrocktype.php
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

<script src="rockscart.js" type="text/javascript"></script>


<h1>Set Rock Type</h1>

<cfset mylist=getquery.sampleagetype>

<form name="myform" action="search.php" method="post">
<table cellspacing=5 cellpadding=5 border=0 align="center">

<tr>
	<td>



		<H3>Rock Type:</H3>
		<div style="background-color:#DDDDDD;padding:5px;">
		<table>
			<tr>
				<td>
		
					<select name="rocktypegroup[]" size="12" style="width:200px;" id="rocktypegroup" multiple>
						<option value="Rock">Rock
						<option value="Igneous">Igneous
						<option value="Igneous>Plutonic">Igneous>Plutonic
						<option value="Igneous>Plutonic>Exotic">Igneous>Plutonic>Exotic
						<option value="Igneous>Plutonic>Felsic">Igneous>Plutonic>Felsic
						<option value="Igneous>Plutonic>Intermediate">Igneous>Plutonic>Intermediate
						<option value="Igneous>Plutonic>Mafic">Igneous>Plutonic>Mafic
						<option value="Igneous>Plutonic>Ultramafic">Igneous>Plutonic>Ultramafic
						<option value="Igneous>Volcanic">Igneous>Volcanic
						<option value="Igneous>Volcanic>Exotic">Igneous>Volcanic>Exotic
						<option value="Igneous>Volcanic>Felsic">Igneous>Volcanic>Felsic
						<option value="Igneous>Volcanic>Intermediate">Igneous>Volcanic>Intermediate
						<option value="Igneous>Volcanic>Mafic">Igneous>Volcanic>Mafic
						<option value="Igneous>Volcanic>Ultramafivc">Igneous>Volcanic>Ultramafivc
						<option value="Metamorphic">Metamorphic
						<option value="Metamorphic>Calc-Silicate">Metamorphic>Calc-Silicate
						<option value="Metamorphic>Eclogite">Metamorphic>Eclogite
						<option value="Metamorphic>Gneiss">Metamorphic>Gneiss
						<option value="Metamorphic>Granofels">Metamorphic>Granofels
						<option value="Metamorphic>Granulite">Metamorphic>Granulite
						<option value="Metamorphic>MechanicallyBroken">Metamorphic>MechanicallyBroken
						<option value="Metamorphic>Meta-Carbonate">Metamorphic>Meta-Carbonate
						<option value="Metamorphic>Meta-Ultramafic">Metamorphic>Meta-Ultramafic
						<option value="Metamorphic>Metasomatic">Metamorphic>Metasomatic
						<option value="Metamorphic>Schist">Metamorphic>Schist
						<option value="Metamorphic>Slate">Metamorphic>Slate
						<option value="Ore">Ore
						<option value="Ore>Other">Ore>Other
						<option value="Ore>Oxide">Ore>Oxide
						<option value="Ore>Sulfide">Ore>Sulfide
						<option value="Sedimentary">Sedimentary
						<option value="Sedimentary>Carbonate">Sedimentary>Carbonate
						<option value="Sedimentary>Conglomerate&amp;Breccia">Sedimentary>Conglomerate&amp;Breccia
						<option value="Sedimentary>Evaporite">Sedimentary>Evaporite
						<option value="Sedimentary>Glacial&amp;Paleosol">Sedimentary>Glacial&amp;Paleosol
						<option value="Sedimentary>Hybrid">Sedimentary>Hybrid
						<option value="Sedimentary>Ironstone">Sedimentary>Ironstone
						<option value="Sedimentary>MixedCarb-Siliciclastic">Sedimentary>MixedCarb-Siliciclastic
						<option value="Sedimentary>Mn-Nodule/Crust">Sedimentary>Mn-Nodule/Crust
						<option value="Sedimentary>Phosphorite">Sedimentary>Phosphorite
						<option value="Sedimentary>SiliceousBiogenic">Sedimentary>SiliceousBiogenic
						<option value="Sedimentary>Siliciclastic">Sedimentary>Siliciclastic
						<option value="Sedimentary>Volcaniclastic">Sedimentary>Volcaniclastic
						<option value="Unknown">Unknown
						<option value="Xenolithic">Xenolithic
						<option value="Igneous">Igneous
						<option value="Igneous>Plutonic">Igneous>Plutonic
						<option value="Igneous>Plutonic>Exotic">Igneous>Plutonic>Exotic
						<option value="Igneous>Plutonic>Felsic">Igneous>Plutonic>Felsic
						<option value="Igneous>Plutonic>Intermediate">Igneous>Plutonic>Intermediate
						<option value="Igneous>Plutonic>Mafic">Igneous>Plutonic>Mafic
						<option value="Igneous>Plutonic>Ultramafic">Igneous>Plutonic>Ultramafic
						<option value="Igneous>Volcanic">Igneous>Volcanic
						<option value="Igneous>Volcanic>Exotic">Igneous>Volcanic>Exotic
						<option value="Igneous>Volcanic>Felsic">Igneous>Volcanic>Felsic
						<option value="Igneous>Volcanic>Intermediate">Igneous>Volcanic>Intermediate
						<option value="Igneous>Volcanic>Mafic">Igneous>Volcanic>Mafic
						<option value="Igneous>Volcanic>Ultramafivc">Igneous>Volcanic>Ultramafivc
						<option value="Metamorphic">Metamorphic
						<option value="Metamorphic>Calc-Silicate">Metamorphic>Calc-Silicate
						<option value="Metamorphic>Eclogite">Metamorphic>Eclogite
						<option value="Metamorphic>Gneiss">Metamorphic>Gneiss
						<option value="Metamorphic>Granofels">Metamorphic>Granofels
						<option value="Metamorphic>Granulite">Metamorphic>Granulite
						<option value="Metamorphic>MechanicallyBroken">Metamorphic>MechanicallyBroken
						<option value="Metamorphic>Meta-Carbonate">Metamorphic>Meta-Carbonate
						<option value="Metamorphic>Meta-Ultramafic">Metamorphic>Meta-Ultramafic
						<option value="Metamorphic>Metasomatic">Metamorphic>Metasomatic
						<option value="Metamorphic>Schist">Metamorphic>Schist
						<option value="Metamorphic>Slate">Metamorphic>Slate
					</select>
				</td>
				<td>
					<center>
					<input type="button" value=">>" onclick="javascript:addrocks();"><br><br>
					<input type="button" value="<<" onclick="javascript:removerocks();"><br><br>
					<input type="button" value="CLEAR" onclick="javascript:clearrocks();">
					</center>
				</td>
				<td>
		
					<select name="rockchosentype[]" size="12" style="width:200px;" id="rockchosentype" multiple>
						
					</select>
				</td>
			</tr>
		</table>
		<INPUT type="hidden" name="hiddenrocktypes" id="hiddenrocktypes" value="">
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

