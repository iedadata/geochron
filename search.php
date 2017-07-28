<?PHP
/**
 * search.php
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

include("includes/geochron-secondary-header.htm");
?>




<table class="aboutpage">

	<tr>
		<td colspan="2">
			<h1>Choose Search Interface:</h1>
		</td>
	</tr>

	<tr><td colspan="2"><hr><br></td></tr>

	<tr>
		<td valign="top"><!--<a href="geochronsearch.php"><img src="orgsearch.jpg"></a>--></td>
		<td valign="top" style="padding-left:20px;">
			<h2><a href="geochronsearch.php">General Interface</a></h2>
			Find samples by the broadest combination of parameters, including location, method, mineral, age, and lab.  Output is generalized for most sample types and analysis purposes.
		</td>
	</tr>

	<tr><td colspan="2"><br><hr><br></td></tr>

	<tr>
		<td valign="top"><!--<a href="detritalsearch.php"><img src="detritalsearch.jpg"></a>--></td>
		<td valign="top" style="padding-left:20px;">
			<h2><a href="detritalsearch.php">Detrital Interface</a></h2>
			Find samples run for detrital age analysis.  Search is by location, host rock name, type or age, and mineral.  Output is tuned for detrital samples.
		</td>
	</tr>


	
</table>

<br><br><br><br><br><br><br><br><br><br>



<?

include("includes/geochron-secondary-footer.htm");

?>