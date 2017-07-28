<?PHP
/**
 * clearage.php
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

$pkey=$_GET['pkey'];
include("db.php");

$db->query("
update search_query set
sampleagevaluemin=NULL, sampleagevaluemax=NULL,
sampleageerranalmin=NULL, sampleageerranalmax=NULL,
sampleagemeanmin=NULL, sampleagemeanmax=NULL,
sampleagetype=NULL,
sampleagevalue=NULL,
sampleageerranal=NULL,
sampleagemean=NULL,
sampleageerrsys=NULL,
sampleageexpl=NULL,
sampleagecomment=NULL,
maxageuncertainty=NULL,
ageunit='ma'
where search_query_pkey=$pkey
");

header("Location:search.php?pkey=$pkey");
?>
 