<?
include("includes/geochron-secondary-header.htm");

	$xp = new XsltProcessor();
	// create a DOM document and load the XSL stylesheet
	$xsl = new DomDocument;
	$xsl->load("ararc.xslt");
	
	// import the XSL styelsheet into the XSLT process
	$xp->importStylesheet($xsl);
	
	// create a DOM document and load the XML datat
	$xml_doc = new DomDocument;
	//$xml_doc->load("arartest.xml");
	$xml_doc->load("files/6248.xml");

	// transform the XML into HTML using the XSL file
	if ($html = $xp->transformToXML($xml_doc)) {
		echo $html;
	} else {
		trigger_error('XSL transformation failed.', E_USER_ERROR);
	} // if 






include("includes/geochron-secondary-footer.htm");
?>