<?PHP
/**
 * listrestitems.php
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

function xml_entities($string) {
    return strtr(
        $string, 
        array(
            "<" => "&lt;",
            ">" => "&gt;",
            '"' => "&quot;",
            "'" => "&apos;",
            
        )
    );
}

$item=$_GET['item'];
$type=$_GET['type'];
$jsonpfunction=$_GET['jsonpfunction'];

if($jsonpfunction==""){
	$jsonpfunction="geochronitemlist";
}

//print_r($_GET);exit();

$items=array("analysismethods","materials","rocktypes","labnames","purposes");

$types=array("xml","json","jsonp");

if((!in_array($item,$items))||(!in_array($type,$types))){
	echo "Incorrect URL.";exit();
}


//first create list of whichever item is requested:

$listitems=array();

if($item=="analysismethods"){


	$rows=$db->get_results("select project||':'||age_name as listitem from (
							select project, age_name from(
							select (case 
									when ecproject = 'redux' then 'U-Pb TIMS'
									when ecproject='helios' then '(U-Th)/He' 
									when ecproject='uthhelegacy' then '(U-Th)/He'
									when ecproject='squid' then 'U-Pb Ion Microprobe'
									when ecproject='zips' then 'U-Pb Ion Microprobe'
									when ecproject='arar' then 'ArAr'
									else '' end) as project,
									sa.age_name
							from sample samp, sample_age sa
							where samp.sample_pkey = sa.sample_pkey
							) foo group by project, age_name order by project, age_name
							)foobar");
							
	foreach($rows as $row){
		$listitems[]=$row->listitem;
	}


}elseif($item=="materials"){

	$rows=$db->get_results("select distinct(material) as listitem from 
							sample 
							where material is not null 
							and material != ''
							order by material");

	foreach($rows as $row){
		$listitems[]=$row->listitem;
	}

}elseif($item=="rocktypes"){

	$rows=$db->get_results("select distinct(rocktype) as listitem from 
							sample 
							where rocktype is not null 
							and rocktype != ''
							order by rocktype");

	foreach($rows as $row){
		$listitems[]=$row->listitem;
	}

}elseif($item=="labnames"){

	$rows=$db->get_results("select distinct(laboratoryname) as listitem from 
							sample 
							where laboratoryname is not null 
							and laboratoryname != ''
							and sample.publ=1
							order by laboratoryname");

	foreach($rows as $row){
		$listitems[]=$row->listitem;
	}

}elseif($item=="purposes"){

	$rows=$db->get_results("select distinct(purpose) as listitem
							from sample 
							where 
							purpose!='NONE' 
							and purpose!='' 
							order by purpose");

	foreach($rows as $row){
		$listitems[]=$row->listitem;
	}


}



if($type=="xml"){
	$out="<geochronItems itemtype=\"$item\">\n";
	foreach($listitems as $li){
		$out.="\t<item>".xml_entities($li)."</item>\n";
	}
	$out.="</geochronItems>";
	
	header("Content-type: text/xml");
	echo $out;

}elseif($type=="json"||$type=="jsonp"){

	$x=0;
	
	foreach($listitems as $li){
		$returnarray[$x]["item"]=htmlspecialchars($li);
		$x++;
	}
	
	$out=json_encode($returnarray);
	
	if($type=="json"){
		
		header('Content-type: application/json');
		echo("$out");
	
	}elseif($type=="jsonp"){

		header("Content-type: text/javascript");
		echo("$jsonpfunction($out);");
	
	}

}




?>