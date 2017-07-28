<?PHP
/**
 * getdata.php
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

// **************** get username stuff here for the search ***************
if($_SESSION['username']!=""){
	$username=$_SESSION['username'];
	$userrow=$db->get_row("select * from users where username='$username'");
	$group=$userrow->usergroup;
	$userpkey=$userrow->users_pkey;
}elseif($_POST['username']!="" & $_POST['password']!=""){
	$username=$_POST['username'];
	$password=$_POST['password'];
	$userrow=$db->get_row("select * from users where username='$username' and password='$password'");
	$group=$userrow->usergroup;
	$userpkey=$userrow->users_pkey;
}

if($group==0 or $group==""){
	$group=99999;
}

if($userpkey==""){
	$userpkey=99999;
}

//*************************************************************************

//first, if pkey is set, then show that file

if($_GET['pkey']!=""){

	$pkey=$_GET['pkey'];
	
	$myrow=$db->get_row("select * from sample 
						left join users on sample.userpkey = users.users_pkey
						where sample.sample_pkey=$pkey
						and (sample.publ=1 or users.usergroup=$group or users.users_pkey=$userpkey)");
	
	
	$filename=$myrow->filename;
	$ecproject=$myrow->ecproject;
	
	if($filename==""){
		echo 'Error: File not found.<div style="height:400px;">';
	}else{
	
		if($ecproject=="redux"){
			$xsltfile="mainfile.xslt";
		}elseif($ecproject=="arar"){
			$xsltfile="arar.xslt";
		}elseif($ecproject=="helios"){
			$xsltfile="helios.xslt";
		}
		
		$xp = new XsltProcessor();
		// create a DOM document and load the XSL stylesheet
		$xsl = new DomDocument;
		$xsl->load($xsltfile);
		
		// import the XSL styelsheet into the XSLT process
		$xp->importStylesheet($xsl);
		
		// create a DOM document and load the XML datat
		$xml_doc = new DomDocument;
		$xml_doc->load("files/$filename");
		
		//echo "files/$filename <br>";
		//exit();
		//echo "<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>";
		
		// transform the XML into HTML using the XSL file
		if ($html = $xp->transformToXML($xml_doc)) {
			echo $html;
		} else {
			trigger_error('XSL transformation failed.', E_USER_ERROR);
		} // if 
	
	}//end if filename==""

	
	

}elseif($_GET['igsn']!=""){
	//OK, IGSN is given, do query based on that
	$igsn=$_GET['igsn'];
	$igsn=str_replace("SES.","",$igsn);
	$igsn=str_replace("GCH.","",$igsn);

	
	$myrows=$db->get_results("select * from sample 
						left join users on sample.userpkey = users.users_pkey
						where sample.igsn like '%$igsn'
						and (sample.publ=1 or users.usergroup=$group or users.users_pkey=$userpkey)");
	
	$numrows=$db->num_rows;
	
	if($numrows==0){
	
		echo 'Error: File not found.<div style="height:400px;">';
	
	}elseif($numrows==1){
	
		//show one file
	
		$filename=$myrows[0]->filename;
		$ecproject=$myrows[0]->ecproject;
		
		if($filename==""){
			echo 'Error: File not found.<div style="height:400px;">';
		}else{
		
			if($ecproject=="redux"){
				$xsltfile="mainfile.xslt";
			}elseif($ecproject=="arar"){
				$xsltfile="arar.xslt";
			}elseif($ecproject=="helios"){
				$xsltfile="helios.xslt";
			}
			
			$xp = new XsltProcessor();
			// create a DOM document and load the XSL stylesheet
			$xsl = new DomDocument;
			$xsl->load($xsltfile);
			
			// import the XSL styelsheet into the XSLT process
			$xp->importStylesheet($xsl);
			
			// create a DOM document and load the XML datat
			$xml_doc = new DomDocument;
			$xml_doc->load("files/$filename");
			//echo("files/$filename");
			
			//echo "files/$filename <br>";
			//echo "<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>";
			
			// transform the XML into HTML using the XSL file
			if ($html = $xp->transformToXML($xml_doc)) {
				echo $html;
			} else {
				trigger_error('XSL transformation failed.', E_USER_ERROR);
			} // if 
		
		}//end if filename==""
	
	}else{ //must be more than one row
	
		echo "More than one file found:<br><br>";
		
		echo "<table class=\"sample\">";
		
		foreach($myrows as $myrow){
			echo "<tr><td><a href=\"/numdata/".$myrow->sample_pkey."\">View</a></td><td>".$myrow->sample_id."</td><td>".$myrow->uploaddate."</td></tr>";
		}
		
		echo "</table>";
	}
	
}else{

}

include("includes/geochron-secondary-footer.htm");

?>