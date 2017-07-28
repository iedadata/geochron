<?PHP
/**
 * xlsxdebug.php
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

set_include_path(get_include_path() . PATH_SEPARATOR . 'testClasses05152014/');
//set_include_path(get_include_path() . PATH_SEPARATOR . 'oldClasses03082014/');

include 'PHPExcel/IOFactory.php';

$inputFileName="decimal_places_error.xlsx";

$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);

$objPHPExcel->setActiveSheetIndex(0);

$sd = $objPHPExcel->getActiveSheet()->toArray();

print_r($sd);

?>