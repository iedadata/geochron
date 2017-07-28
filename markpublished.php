<?PHP
/**
 * markpublished.php
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

//markpublished.php

//this script is called remotely by the EarthChem Library
//to mark datasets published. When a dataset is marked published,
//the 'publish' and 'delete' links are removed from the managedata page

//m6HPPWGb4hM

include("db.php");

$file=$_GET['file'];

$linkstring=str_replace(".zip","",$file);

$mycount=$db->get_var("select count(*) from datasets where linkstring='$linkstring'");

if($mycount==0){
	echo "Error. Dataset not found";
}else{
	$db->query("update datasets set published=true where linkstring='$linkstring'");
	echo "success";
}











?>