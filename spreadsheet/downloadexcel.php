<?



$key=$_GET['key'];

if(file_exists("usersheets/$key.txt")){

	$myxml=file_get_contents("usersheets/$key.txt");
	
		$dom = new DomDocument();
		if($dom->loadXML($myxml)){
			

			// Include PEAR::Spreadsheet_Excel_Writer
			require_once "Spreadsheet/Excel/Writer.php";
			
			// Create an instance
			$xls =& new Spreadsheet_Excel_Writer();
			
			// Send HTTP headers to tell the browser what's coming
			$xls->send("SESAR_Template.xls");
			
			// Add a worksheet to the file, returning an object to add data to
			$sheet =& $xls->addWorksheet('IGSN');
			
			$formatwhiteblue =& $xls->addFormat();
			$formatwhiteblue->setFgColor(63); //30
			$formatwhiteblue->setColor('white');
			$formatwhiteblue->setBorder(1);
			
			$formatwhite =& $xls->addFormat();
			$formatwhite->setBorder(1);
			
			$formathead =& $xls->addFormat();
			$formathead->setColor(63); //30
			$formathead->setSize(18);
			$formathead->setBold(700);
			$formathead->setItalic();
			
			$formatinstr =& $xls->addFormat();
			$formatinstr->setTextWrap();
			$formatinstr->setVAlign('top');
			
			
			
			//write header
			$sheet->write(0,0,"Geochron IGSN Download",$formathead);
			
			
			
			
			$columnnames=array("IGSN","Parent IGSN","Sample ID","Sample Description","Sample Comment","GeoObject Type","GeoObject Class","Collection Method","Collection Method Desc.","Size","Min Age","Max Age","Material","Material Class","Start Longitude","Start Latitude","Start Geodetic Datum","End Longitude","End Latitude","End Geodetic Datum","Start Elevation","End Elevation","Primary Location Name","Primary Location Type","Location Desc.","Country","Province","County","City or Township","Min. Depth","Max. Depth","Vertical Datum","Field Description","Platform","Platform ID","Platform Desc.","Collector","Start Date","End Date","Orig. Archive Inst.","Orig. Archive Inst. Contact","Most Recent Archival Inst.","Most Recent Archival Contact");
			$colnum=0;
			foreach($columnnames as $columnname){
				$thisheader=$columnname;
				$thiswidth=strlen($thisheader)+1;
				if($thisheader=="IGSN"){$thiswidth="12";}
				$sheet->write(6,$colnum,$thisheader,$formatwhiteblue);
				$sheet->setColumn($colnum,$colnum,$thiswidth);
				$colnum++;
			}
			
			
			for ( $i=7;$i<106;$i++ ) {
				for($j=0;$j<43;$j++){
					$sheet->writeBlank($i,$j,$formatwhite);
				}
}




























			$rows=$dom->getElementsByTagName("row");
			$y=7;
			foreach($rows as $row){

				$x=0;
				$columns=$row->getElementsByTagName("column");
				foreach($columns as $column){
					$contents=$column->textContent;
					//echo "$contents";
					$sheet->write($y,$x,$contents,$formatwhite);
					$x++;
				}
				
				$y++;
			}
			

			
		}else{
			$error.="Bad XML in file.<br>\n";
		}


}


$dates=$dom->getElementsByTagName("date");
foreach($dates as $date){
	$mydate=$date->textContent;
}



$sheet->write(0,4,"IGSNs created: $mydate");

// Merge cells from row 0, col 0 to row 2, col 2
$sheet->setMerge(0, 0, 0, 3);

$sheet->setMerge(2,0,4,6);

$sheet->write(2,0,"Notes:",$formatinstr);

// Finish the spreadsheet, dumping it to the browser
$xls->close(); 

exit();





















?>