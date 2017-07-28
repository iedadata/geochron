<?PHP
/**
 * datasetkml.php
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


include("db.php");

$id=$_GET['id'];

$datasetrow = $db->get_row("select * from datasets where dataset_pkey=$id");

$datasetname = str_replace(" ","_",$datasetrow->datasetname);

//echo $datasetname;exit();

$rows = $db->get_results("select sample.sample_pkey,
							sample.sample_id,
							sample.igsn,
							sample.laboratoryname,
							sample.analyst_name,
							sample.ecproject,
							sample.latitude,
							sample.longitude,
							sample.userpkey,
							sample.material
							from sample
							left join sample_age on sample.sample_pkey = sample_age.sample_pkey
							left join groupsamplerelate on sample.sample_pkey = groupsamplerelate.sample_pkey
							left join grouprelate on groupsamplerelate.group_pkey = grouprelate.group_pkey
							left join groups on grouprelate.group_pkey = groups.group_pkey
							left join datasetrelate dr on dr.sample_pkey = sample.sample_pkey
							left join datasetuserrelate dur on dur.dataset_pkey = dr.dataset_pkey
							left join datasets ds on dr.dataset_pkey = ds.dataset_pkey
							where 1=1
							and (sample.publ=1 or sample.userpkey=1 or ((dur.users_pkey=1 and dur.confirmed=true) or ds.users_pkey=1 ) or ((grouprelate.users_pkey=1 and grouprelate.confirmed=true) or groups.users_pkey=1))
							and ds.dataset_pkey = $id
							group by
							sample.sample_pkey,
							sample.sample_id,
							sample.igsn,
							sample.laboratoryname,
							sample.analyst_name,
							sample.ecproject,
							sample.latitude,
							sample.longitude,
							sample.userpkey,
							sample.material
							;
							");



/*
print_r($rows);
            [sample_pkey] => 9920
            [sample_id] => CR2
            [igsn] => JMA00001A
            [laboratoryname] => New Lab - No Name
            [analyst_name] => TIMS Lab User
            [ecproject] => redux
            [latitude] => 39.3446
            [longitude] => -112.208
            [userpkey] => 1
            [material] => zircon

sample_id
igsn
laboratoryname
analyst_name
ecproject
latitude
longitude
userpkey
material


sample_id
igsn
laboratoryname
analyst_name
ecproject
material

http://www.geochron.org/viewfile.php?pkey=9467
*/

header("Content-Description: File Transfer"); 
header("Content-Type: application/octet-stream"); 
header("Content-Disposition: attachment; filename=\"".$datasetname.".kml\""); 

?>
<kml xmlns="http://www.opengis.net/kml/2.2">
<Document id="root_doc">
<Schema name="OGRGeoJSON" id="OGRGeoJSON">
<SimpleField name="sample_id" type="string"></SimpleField>
<SimpleField name="igsn" type="string"></SimpleField>
<SimpleField name="laboratoryname" type="string"></SimpleField>
<SimpleField name="analyst_name" type="string"></SimpleField>
<SimpleField name="ecproject" type="string"></SimpleField>
<SimpleField name="latitude" type="string"></SimpleField>
<SimpleField name="longitude" type="string"></SimpleField>
<SimpleField name="userpkey" type="string"></SimpleField>
<SimpleField name="material" type="string"></SimpleField>
</Schema>
<Style id="highlightPlacemark"><IconStyle><Icon><href>http://maps.google.com/mapfiles/kml/shapes/placemark_circle_highlight.png</href></Icon></IconStyle></Style><Style id="normalPlacemark"><IconStyle><Icon><href>http://maps.google.com/mapfiles/kml/shapes/placemark_circle.png</href></Icon></IconStyle></Style><StyleMap id="exampleStyleMap"><Pair><key>normal</key><styleUrl>#normalPlacemark</styleUrl></Pair><Pair><key>highlight</key><styleUrl>#highlightPlacemark</styleUrl></Pair></StyleMap>
<Folder><name><?=$datasetname?></name>
<?
foreach($rows as $row){
?>
<Placemark>
<styleUrl>#exampleStyleMap</styleUrl><Style><LineStyle><color>641400FF</color><width>5</width></LineStyle><PolyStyle><color>641400FF</color><colorMode>normal</colorMode><fill>1</fill><outline>1</outline></PolyStyle></Style>
<name><?=$row->sample_id?></name>
<ExtendedData><SchemaData schemaUrl="#OGRGeoJSON">
<SimpleData name="Sample ID"><?=$row->sample_id?></SimpleData>
<SimpleData name="Unique ID"><?=$row->igsn?></SimpleData>
<SimpleData name="Laboratory Name"><?=$row->laboratoryname?></SimpleData>
<SimpleData name="Analyst Name"><?=$row->analyst_name?></SimpleData>
<SimpleData name="Geochron Project"><?=$row->ecproject?></SimpleData>
<SimpleData name="Material"><?=$row->material?></SimpleData>
<SimpleData name="Detail URL">http://www.geochron.org/viewfile.php?pkey=<?=$row->sample_pkey?></SimpleData>
</SchemaData></ExtendedData>
<Point><coordinates><?=$row->longitude?>,<?=$row->latitude?></coordinates></Point>
</Placemark>
<?
}
?>
</Folder>
</Document></kml>