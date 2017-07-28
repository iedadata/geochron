<?PHP
/**
 * uploadfile.php
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

//var_dump($_SESSION);
//exit();

include("logincheck.php");

include("db.php");

include("includes/geochron-secondary-header.htm");
$pkey=$_POST['pkey'];
$ip=$_SERVER['remote_addr'];
$filename=$_POST['filename'];

$username=$_SESSION['username'];
$userpkey=$_SESSION['userpkey'];

?>


<h1>File Upload</h1>
    <?
    if($_POST['makepublic']!=""){
		if($_POST['publicbutton']=="yes"){
		  $pubdigit=1;
		  }else{
		  $pubdigit=0;
		}

    $db->query("update sample set publ=$pubdigit where sample_pkey in ($pkey)");

?>
    <table border=0 cellpadding="0" cellspacing="0">
      <tr>
        <td>Success!
          <p />
          Your file has been successfully uploaded.<br />
          The contents of your file are displayed below.<br />
          Please verify that the data are correct.
          <p />
          <a href="managedata.php">File manager</a> | <a href="uploadfile.php">Upload another file</a> </td>
      </tr>
    </table>
    <hr>
    File Contents:
    <p />

	<?
	
	$pkeyarray=explode(",",$pkey);
	
	foreach($pkeyarray as $currpkey){
	
		$ecproject=$db->get_var("select ecproject from sample where sample_pkey=$currpkey");
		if($ecproject=="redux"){
			$xsltfile="transforms/mainfile.xslt";
		}elseif($ecproject=="arar"){
			$xsltfile="transforms/arar.xslt";
		}elseif($ecproject=="helios"){
			$xsltfile="transforms/helios.xslt";
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
		
		// transform the XML into HTML using the XSL file
		if ($html = $xp->transformToXML($xml_doc)) {
			echo $html;
			echo "<br><br><br>";
		} else {
			trigger_error('XSL transformation failed.', E_USER_ERROR);
		} // if 
	
		include("includes/geochron-secondary-footer.htm");
		exit();
		
	}


  }//end if post makepublic 
  
  if($_POST['filesubmit']!=""){
  
  	//echo "<br><br>";
  	//print_r($_FILES);
  	//echo "<br><br>";
  	
  	$orig_filename=$_FILES['filetoupload']['name'];
  
  	//print_r($_FILES['filetoupload']);
  	//move_uploaded_file($_FILES['filetoupload']['tmp_name'], "temp/foo.xml");
  	//exit();
  	
  	
  	
	$dom = new DomDocument();
	$dom->load($_FILES['filetoupload']['tmp_name']);

	//include the modular loader here
	//this file is a generic loader which takes
	//the dom object and shreds it and loads it
	//into the relational database
	include("modularloader.php");
	

	if($moderror==""){

		move_uploaded_file($_FILES['filetoupload']['tmp_name'], "files/$geochron_pkey.xml");
		
		$ip=$_SERVER['REMOTE_ADDR'];
		
		$db->query("insert into logs ( log_pkey, logtime, ip_address, content ) values 
		( nextval('log_seq'), now(), '$ip', 'Sample $sample_pkey uploaded by $username.' )");
		
		?>
		<table cellspacing=0 cellpadding=0 border=0>
		<tr>
		  <td><form action="" method="post">
			  Do you wish to make the data in this file publicly searchable?
			  <p />
			  <input type="radio" name="publicbutton" value="yes">
			  yes &nbsp;
			  <input type="radio" name="publicbutton" value="no" checked>
			  no
			  <p />
			  <input type="hidden" name="pkey" value="<?=$pkeylist?>">
			  <input type="hidden" name="filename" value="<?=$geochron_pkey?>.xml">
			  <input type="submit" name="makepublic" value="Submit">
			</form></td>
		</tr>
		</table>
		<?

	}else{
	
	?>
      <br><br><br>
      <table cellspacing=0 cellpadding=0 border=0>
        <tr>
          <td>Failure!
            <p />
            Error(s): <?=$moderror?>

            <p />
            <br><br>
            <a href="managedata.php">Manage files</a> | <a href="">Upload another file</a> </td>
        </tr>
      </table>	
    <?
	
	
	
	
	}

  
  }else{ // else if filetoupload is blank  
  
  ?>



    <form action="" method="post" enctype="multipart/form-data">
      <input name="filetoupload" type="file"> &nbsp;
      <input name="filesubmit" type="submit" value="Upload file">
    </form>

<?

}//end if filetoupload is not null

include("includes/geochron-secondary-footer.htm");

?>
