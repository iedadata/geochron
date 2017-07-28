


function getRadio(radioObj) {
	if(!radioObj)
		return "";
	var radioLength = radioObj.length;
	if(radioLength == undefined)
		if(radioObj.checked)
			return radioObj.value;
		else
			return "";
	for(var i = 0; i < radioLength; i++) {
		if(radioObj[i].checked) {
			return radioObj[i].value;
		}
	}
	return "";
}

function getCheckbox(checkboxObj) {
	if(!checkboxObj)
		return "";
	var checkboxLength = checkboxObj.length;
	if(checkboxLength == undefined)
		if(checkboxObj.checked)
			return checkboxObj.value;
		else
			return "";
	var mydelim="";
	var mychecklist="";
	for(var i = 0; i < checkboxLength; i++) {
		if(checkboxObj[i].checked) {
			mychecklist=mychecklist+mydelim+checkboxObj[i].value;
			mydelim=", ";
		}
	}
	return mychecklist;
}

function removeSpaces(string) {
	var tstring = "";
	string = '' + string;
	splitstring = string.split(" ");
	for(i = 0; i < splitstring.length; i++)
	tstring += splitstring[i];
	return tstring;
}


function removedashes(string) {
	var tstring = "";
	string = '' + string;
	splitstring = string.split("-");
	for(i = 0; i < splitstring.length; i++)
	tstring += splitstring[i];
	return tstring;
}



function showmap(){
	var mynorth = document.getElementById('north').value;
	var myeast = document.getElementById('east').value;
	var mysouth = document.getElementById('south').value;
	var mywest = document.getElementById('west').value;

	//alert(mynorth+" "+myeast+" "+mysouth+" "+mywest+" ");

	if(mynorth!="" && myeast!="" && mysouth!="" && mywest!=""){
		if (confirm("This will clear your north/east/south/west entries. Are you sure you want to continue?")) {
			Shadowbox.open({content:'popupmap.php', player:'iframe', title:'Geochron Polygon Map', height:520, width:820});
		}
	}else{
		Shadowbox.open({content:'popupmap.php', player:'iframe', title:'Geochron Polygon Map', height:520, width:820});
	}
	

}



function updatecoordinates(pointstring){
	document.getElementById('coordinates').value = pointstring;
	
	document.getElementById('mapinfo').innerHTML='<a onclick="showmap();" ><img src="http://picasso.kgs.ku.edu/custompoints/ajaxpoly.php?coords='+pointstring+'"></a><br><div align="center">Polygon Set</div>\
													&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="button" value="Clear" onclick="clearcoordinates();">  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  <input type="button" value="Edit" onclick="Shadowbox.open({content:\'popupmap.php\', player:\'iframe\', title:\'Geochron Polygon Map\', height:520, width:820});">   ';

	document.getElementById('north').value="";
	document.getElementById('east').value="";
	document.getElementById('south').value="";
	document.getElementById('west').value="";	
	document.getElementById('clearnewsbutton').style.visibility="hidden";

	Shadowbox.close();
	dosearch();
}


function clearcoordinates(){
	document.getElementById('coordinates').value = '';
	
	document.getElementById('mapinfo').innerHTML='<a onclick="showmap();" ><img src="mappersmall.jpg"></a></a><br>Use Geochron Dynamic Mapper   ';
	
	Shadowbox.close();
	dosearch();
}





//Shadowbox.open({content:\'agetypeselector.php?project=redux\',player:"iframe",title:"U-Pb Age Methods",height:250,width:330});


function domethods(){
	
	if(document.getElementById("agemethod").value!=""){
		document.getElementById('clearmethodsbutton').style.visibility="visible";
	}else{
		document.getElementById('clearmethodsbutton').style.visibility="hidden";
		document.getElementById("agemethoddisplay").innerHTML="No methods set.";
	}
	
	Shadowbox.close();
	
	dosearch();
	
}




function doupbcheck(){




	if(document.getElementById('upbcheck').checked){
		//alert('checked');
		Shadowbox.open({
			content:    'agetypeselector.php?project=redux',
			player:     "iframe",
			title:      "U-Pb Age Methods",
			height:     250,
			width:      330
		});
	}else{
		
		var agetypelist='';
		var delim='';
		var htmlagetypelist='';
		var htmldelim='';
		
		//alert('not checked');
		//remove items from agemethod that match prefix (U-Pb: )
		//first, get parent agemethod
		var currentagelist=document.getElementById("agemethod").value;
		//alert(currentagelist);
		
		//next, split list into array
		agearray=currentagelist.split(",");
		
		//now, loop over array and remove any entries that begin with prefix
		
		for (p = 0; p<=agearray.length - 1; p++) {
			if(agearray[p].indexOf('U-Pb: ') != -1){
			}else{
				//add it to agetypelist
				agetypelist=agetypelist+delim+agearray[p];
				delim=',';
				htmlagetypelist=htmlagetypelist+htmldelim+agearray[p];
				htmldelim='<br>';
			}
		}

		if(htmlagetypelist==""){
			htmlagetypelist='No methods set.';
			document.getElementById('clearmethodsbutton').style.visibility="hidden";
		}else{
			document.getElementById('clearmethodsbutton').style.visibility="visible";
		}

		document.getElementById("agemethod").value=agetypelist;
		document.getElementById("agemethoddisplay").innerHTML=htmlagetypelist;
		
		dosearch();

	}


}

function douthhecheck(){




	if(document.getElementById('uthhecheck').checked){
		//alert('checked');
		Shadowbox.open({
			content:    'agetypeselector.php?project=helios',
			player:     "iframe",
			title:      "(U-Th)/He Age Methods",
			height:     250,
			width:      330
		});
	}else{
		
		var agetypelist='';
		var delim='';
		var htmlagetypelist='';
		var htmldelim='';
		
		//alert('not checked');
		//remove items from agemethod that match prefix (U-Pb: )
		//first, get parent agemethod
		var currentagelist=document.getElementById("agemethod").value;
		//alert(currentagelist);
		
		//next, split list into array
		agearray=currentagelist.split(",");
		
		//now, loop over array and remove any entries that begin with prefix
		
		for (p = 0; p<=agearray.length - 1; p++) {
			if(agearray[p].indexOf('(U-Th)/He: ') != -1){
			}else{
				//add it to agetypelist
				agetypelist=agetypelist+delim+agearray[p];
				delim=',';
				htmlagetypelist=htmlagetypelist+htmldelim+agearray[p];
				htmldelim='<br>';
			}
		}
		
		if(htmlagetypelist==""){
			htmlagetypelist='No methods set.';
			document.getElementById('clearmethodsbutton').style.visibility="hidden";
		}else{
			document.getElementById('clearmethodsbutton').style.visibility="visible";
		}

		document.getElementById("agemethod").value=agetypelist;
		document.getElementById("agemethoddisplay").innerHTML=htmlagetypelist;
		
		dosearch();

	}




}

function doararcheck(){






	if(document.getElementById('ararcheck').checked){
		//alert('checked');
		Shadowbox.open({
			content:    'agetypeselector.php?project=arar',
			player:     "iframe",
			title:      "ArAr Age Methods",
			height:     250,
			width:      330
		});
	}else{
		
		var agetypelist='';
		var delim='';
		var htmlagetypelist='';
		var htmldelim='';
		
		//alert('not checked');
		//remove items from agemethod that match prefix (U-Pb: )
		//first, get parent agemethod
		var currentagelist=document.getElementById("agemethod").value;
		//alert(currentagelist);
		
		//next, split list into array
		agearray=currentagelist.split(",");
		
		//now, loop over array and remove any entries that begin with prefix
		
		for (p = 0; p<=agearray.length - 1; p++) {
			if(agearray[p].indexOf('ArAr: ') != -1){
			}else{
				//add it to agetypelist
				agetypelist=agetypelist+delim+agearray[p];
				delim=',';
				htmlagetypelist=htmlagetypelist+htmldelim+agearray[p];
				htmldelim='<br>';
			}
		}
		
		if(htmlagetypelist==""){
			htmlagetypelist='No methods set.';
			document.getElementById('clearmethodsbutton').style.visibility="hidden";
		}else{
			document.getElementById('clearmethodsbutton').style.visibility="visible";
		}

		document.getElementById("agemethod").value=agetypelist;
		document.getElementById("agemethoddisplay").innerHTML=htmlagetypelist;
		
		dosearch();

	}





}

function douseriescheck(){






	if(document.getElementById('useriescheck').checked){
		//alert('checked');
		Shadowbox.open({
			content:    'agetypeselector.php?project=useries',
			player:     "iframe",
			title:      "U-Series Age Methods",
			height:     250,
			width:      330
		});
	}else{
		
		var agetypelist='';
		var delim='';
		var htmlagetypelist='';
		var htmldelim='';
		
		//alert('not checked');
		//remove items from agemethod that match prefix (U-Pb: )
		//first, get parent agemethod
		var currentagelist=document.getElementById("agemethod").value;
		//alert(currentagelist);
		
		//next, split list into array
		agearray=currentagelist.split(",");
		
		//now, loop over array and remove any entries that begin with prefix
		
		for (p = 0; p<=agearray.length - 1; p++) {
			if(agearray[p].indexOf('U-Series: ') != -1){
			}else{
				//add it to agetypelist
				agetypelist=agetypelist+delim+agearray[p];
				delim=',';
				htmlagetypelist=htmlagetypelist+htmldelim+agearray[p];
				htmldelim='<br>';
			}
		}
		
		if(htmlagetypelist==""){
			htmlagetypelist='No methods set.';
			document.getElementById('clearmethodsbutton').style.visibility="hidden";
		}else{
			document.getElementById('clearmethodsbutton').style.visibility="visible";
		}

		document.getElementById("agemethod").value=agetypelist;
		document.getElementById("agemethoddisplay").innerHTML=htmlagetypelist;
		
		dosearch();

	}






}

function doionmicroprobecheck(){







	if(document.getElementById('ionmicroprobecheck').checked){
		//alert('checked');
		Shadowbox.open({
			content:    'agetypeselector.php?project=ionmicroprobe',
			player:     "iframe",
			title:      "Ion Microprobe Age Methods",
			height:     250,
			width:      330
		});
	}else{
		
		var agetypelist='';
		var delim='';
		var htmlagetypelist='';
		var htmldelim='';
		
		//alert('not checked');
		//remove items from agemethod that match prefix (U-Pb: )
		//first, get parent agemethod
		var currentagelist=document.getElementById("agemethod").value;
		//alert(currentagelist);
		
		//next, split list into array
		agearray=currentagelist.split(",");
		
		//now, loop over array and remove any entries that begin with prefix
		
		for (p = 0; p<=agearray.length - 1; p++) {
			if(agearray[p].indexOf('Ion Microprobe: ') != -1){
			}else{
				//add it to agetypelist
				agetypelist=agetypelist+delim+agearray[p];
				delim=',';
				htmlagetypelist=htmlagetypelist+htmldelim+agearray[p];
				htmldelim='<br>';
			}
		}
		
		if(htmlagetypelist==""){
			htmlagetypelist='No methods set.';
			document.getElementById('clearmethodsbutton').style.visibility="hidden";
		}else{
			document.getElementById('clearmethodsbutton').style.visibility="visible";
		}

		document.getElementById("agemethod").value=agetypelist;
		document.getElementById("agemethoddisplay").innerHTML=htmlagetypelist;
		
		dosearch();

	}







}

function dolaicpmscheck(){








	if(document.getElementById('laicpmscheck').checked){
		//alert('checked');
		Shadowbox.open({
			content:    'agetypeselector.php?project=laicpms',
			player:     "iframe",
			title:      "LAICP-MS Age Methods",
			height:     250,
			width:      330
		});
	}else{
		
		var agetypelist='';
		var delim='';
		var htmlagetypelist='';
		var htmldelim='';
		
		//alert('not checked');
		//remove items from agemethod that match prefix (U-Pb: )
		//first, get parent agemethod
		var currentagelist=document.getElementById("agemethod").value;
		//alert(currentagelist);
		
		//next, split list into array
		agearray=currentagelist.split(",");
		
		//now, loop over array and remove any entries that begin with prefix
		
		for (p = 0; p<=agearray.length - 1; p++) {
			if(agearray[p].indexOf('LAICP-MS: ') != -1){
			}else{
				//add it to agetypelist
				agetypelist=agetypelist+delim+agearray[p];
				delim=',';
				htmlagetypelist=htmlagetypelist+htmldelim+agearray[p];
				htmldelim='<br>';
			}
		}
		
		if(htmlagetypelist==""){
			htmlagetypelist='No methods set.';
			document.getElementById('clearmethodsbutton').style.visibility="hidden";
		}else{
			document.getElementById('clearmethodsbutton').style.visibility="visible";
		}

		document.getElementById("agemethod").value=agetypelist;
		document.getElementById("agemethoddisplay").innerHTML=htmlagetypelist;
		
		dosearch();

	}







}

function clearage(){

	document.getElementById('clearagebutton').style.visibility="hidden";
	document.getElementById('minage').value="";
	document.getElementById('maxage').value="";
	document.getElementById('age').value="";
	document.getElementById('ageplusminus').value="";
	dosearch();

}

function clearnews() {

	document.getElementById('clearnewsbutton').style.visibility="hidden";
	document.getElementById('north').value="";
	document.getElementById('east').value="";
	document.getElementById('south').value="";
	document.getElementById('west').value="";
	document.getElementById('mapinfo').innerHTML='<a onclick="showmap();" ><img src="mappersmall.jpg"></a></a><br>Use Geochron Dynamic Mapper   ';
	dosearch();
}

function clearmethods(){
	document.getElementById('upbcheck').checked=false;
	document.getElementById('uthhecheck').checked=false;
	document.getElementById('ararcheck').checked=false;
	document.getElementById('agemethod').value='';
	document.getElementById('agemethoddisplay').innerHTML='No methods set.';
	document.getElementById('clearmethodsbutton').style.visibility="hidden";
	dosearch();
}

function clearsampleinfo(){
	document.getElementById('igsn').value='';
	document.getElementById('sample_id').value='';
	document.getElementById('collector').value='';
	document.getElementById('sampledescription').value='';
	document.getElementById('collectionmethod').value='';
	document.getElementById('samplecomment').value='';
	document.getElementById('primarylocationname').value='';
	document.getElementById('primarylocationtype').value='';
	document.getElementById('locationdescription').value='';
	document.getElementById('locality').value='';
	document.getElementById('localitydescription').value='';
	document.getElementById('country').value='';
	document.getElementById('province').value='';
	document.getElementById('clearsampleinfobutton').style.visibility="hidden";
	dosearch();
}

function clearrocktype(){
	var rocktypesel = document.getElementById('rocktype');

	for (p = 0; p<=rocktypesel.length - 1; p++) {
		rocktypesel.options[p].selected=false;
	}
	
	document.getElementById('clearrocktypebutton').style.visibility="hidden";
	dosearch();
}

function clearrocktype(){
	var rocktypesel = document.getElementById('rocktype');

	for (p = 1; p<=rocktypesel.length - 1; p++) {
		rocktypesel.options[p].selected=false;
	}
	
	rocktypesel.options[0].selected=true;
	
	document.getElementById('clearrocktypebutton').style.visibility="hidden";
	dosearch();
}

function clearlabnames(){
	var labnamessel = document.getElementById('labnames');

	for (p = 1; p<=labnamessel.length - 1; p++) {
		labnamessel.options[p].selected=false;
	}
	
	labnamessel.options[0].selected=true;
	
	document.getElementById('clearlabnamesbutton').style.visibility="hidden";
	dosearch();
}

function clearagenames(){
	var agenamessel = document.getElementById('purposes');

	for (p = 1; p<=agenamessel.length - 1; p++) {
		agenamessel.options[p].selected=false;
	}
	
	agenamessel.options[0].selected=true;
	
	document.getElementById('clearagenamesbutton').style.visibility="hidden";
	dosearch();
}

function clearmaterials(){
	var materialssel = document.getElementById('materials');

	for (p = 1; p<=materialssel.length - 1; p++) {
		materialssel.options[p].selected=false;
	}
	
	materialssel.options[0].selected=true;
	
	document.getElementById('clearmaterialsbutton').style.visibility="hidden";
	dosearch();
}






function dosearch() {

	var gonow="yes";
	
	//**************************************************
	
	//add clear buttons here
	var north = document.getElementById('north').value;
	var east = document.getElementById('east').value;
	var south = document.getElementById('south').value;
	var west = document.getElementById('west').value;
	
	var minage = document.getElementById('minage').value;
	var maxage = document.getElementById('maxage').value;
	var age = document.getElementById('age').value;
	var ageplusminus = document.getElementById('ageplusminus').value;

	var igsn = document.getElementById('igsn').value;
	var sample_id = document.getElementById('sample_id').value;
	var collector = document.getElementById('collector').value;
	var sampledescription = document.getElementById('sampledescription').value;
	var collectionmethod = document.getElementById('collectionmethod').value;
	var samplecomment = document.getElementById('samplecomment').value;
	var primarylocationname = document.getElementById('primarylocationname').value;
	var primarylocationtype = document.getElementById('primarylocationtype').value;
	var locationdescription = document.getElementById('locationdescription').value;
	var locality = document.getElementById('locality').value;
	var localitydescription = document.getElementById('localitydescription').value;
	var country = document.getElementById('country').value;
	var province = document.getElementById('province').value;

	if(igsn != "" || sample_id != "" || collector != "" || sampledescription != "" || collectionmethod != "" || samplecomment != "" || primarylocationname != "" || primarylocationtype != "" || locationdescription != "" || locality != "" || localitydescription != "" || country != "" || province != ""){
		document.getElementById('clearsampleinfobutton').style.visibility="visible";
	}else{
		document.getElementById('clearsampleinfobutton').style.visibility="hidden";
	}

	if(north!="" && east!="" && south!="" && west!=""){
		document.getElementById('clearnewsbutton').style.visibility="visible";
		//build coords string
		var coordstring='';
		coordstring = north + ' ' + west;
		coordstring = coordstring + '; ' + north + ' ' + east;
		coordstring = coordstring + '; ' + south + ' ' + east;
		coordstring = coordstring + '; ' + south + ' ' + west;
		coordstring = coordstring + '; ' + north + ' ' + west;		

	}



	//add functionality for location settings here

	var coordinates = document.getElementById('coordinates').value;



	if(north!="" && east!="" && south!="" && west!="" && coordinates!=""){

		if(confirm("This will clear your polygon setting. Are you sure?")){
			document.getElementById('coordinates').value='';


	
			//alert(coordstring);
			
			document.getElementById('mapinfo').innerHTML='<a onclick="showmap();" ><img src="http://picasso.kgs.ku.edu/custompoints/ajaxpoly.php?coords='+ coordstring +'"></a></a><br>Use Geochron Dynamic Mapper   ';

			gonow="yes";
		}else{
			gonow="no";
		}
		
	}else if(north!="" && east!="" && south!="" && west!=""){
		document.getElementById('mapinfo').innerHTML='<a onclick="showmap();" ><img src="http://picasso.kgs.ku.edu/custompoints/ajaxpoly.php?coords='+ coordstring +'"></a></a><br>Use Geochron Dynamic Mapper   ';
	}
	



	// do age checks here
	// first, add button
	if(minage != "" || maxage != "" || age != "" || ageplusminus != ""){
		document.getElementById('clearagebutton').style.visibility="visible";
	}else{
		document.getElementById('clearagebutton').style.visibility="hidden";
	}

	//next, check values mistakes
	if( ( (minage!="" && maxage!="") && ( age!="" || ageplusminus !="" ) ) ||  ( (age!="" && ageplusminus!="") && ( minage!="" || maxage !="" ) ) ){
		alert('Either Min Age and Max Age OR Age and Age +- can be set, but not both.');
		gonow="no";
	}else if( (minage!="" && maxage=="") || (minage=="" && maxage!="")){
		alert('If Min Age or Max Age is set, the other must also be set.');
		gonow="no";
	}else if( (age!="" && ageplusminus=="") || (age=="" && ageplusminus!="")){
		alert('If Age or Age +- is set, the other must also be set.');
		gonow="no";
	}



	if(gonow=="yes"){
		sendsearch();
	}
	
}




function sendsearch() {

	var north = document.getElementById('north').value;
	var east = document.getElementById('east').value;
	var south = document.getElementById('south').value;
	var west = document.getElementById('west').value;
	var coordinates = document.getElementById('coordinates').value;
	var minage = document.getElementById('minage').value;
	var maxage = document.getElementById('maxage').value;
	var age = document.getElementById('age').value;
	var ageplusminus = document.getElementById('ageplusminus').value;
	var igsn = document.getElementById('igsn').value;
	var sample_id = document.getElementById('sample_id').value;
	var collector = document.getElementById('collector').value;
	var sampledescription = document.getElementById('sampledescription').value;
	var collectionmethod = document.getElementById('collectionmethod').value;
	var samplecomment = document.getElementById('samplecomment').value;
	var primarylocationname = document.getElementById('primarylocationname').value;
	var primarylocationtype = document.getElementById('primarylocationtype').value;
	var locationdescription = document.getElementById('locationdescription').value;
	var locality = document.getElementById('locality').value;
	var localitydescription = document.getElementById('localitydescription').value;
	var country = document.getElementById('country').value;
	var province = document.getElementById('province').value;


	
	myobj=document.getElementsByName('ageunit');
	ageunit = getRadio(myobj);
	
	var showclearrocktypebutton="no";
	var rocktypesel = document.getElementById('rocktype');
	var rocktypelist='';
	var delim='';
	for (p = 0; p<=rocktypesel.length - 1; p++) {
		if (rocktypesel.options[p].selected) {
			rocktypelist=rocktypelist+delim+rocktypesel.options[p].value;
			delim=',';
			if(p > 0){
				showclearrocktypebutton="yes";
			}
		}
	}
	if(showclearrocktypebutton=="yes"){
		document.getElementById('clearrocktypebutton').style.visibility="visible";
	}else{
		document.getElementById('clearrocktypebutton').style.visibility="hidden";
	}



	var showclearlabnamesbutton="no";
	var labnamesel = document.getElementById('labnames');
	var labnamelist='';
	var delim='';
	for (p = 0; p<=labnamesel.length - 1; p++) {
		if (labnamesel.options[p].selected) {
			labnamelist=labnamelist+delim+labnamesel.options[p].value;
			delim='***';
			if(p > 0){
				showclearlabnamesbutton="yes";
			}
		}
	}
	if(showclearlabnamesbutton=="yes"){
		document.getElementById('clearlabnamesbutton').style.visibility="visible";
	}else{
		document.getElementById('clearlabnamesbutton').style.visibility="hidden";
	}
	
	
	var showclearagenamesbutton="no";
	var purposesel = document.getElementById('purposes');
	var purposelist='';
	var delim='';
	for (p = 0; p<=purposesel.length - 1; p++) {
		if (purposesel.options[p].selected) {
			purposelist=purposelist+delim+purposesel.options[p].value;
			delim='***';
			if(p > 0){
				showclearagenamesbutton="yes";
			}
		}
	}
	if(showclearagenamesbutton=="yes"){
		document.getElementById('clearagenamesbutton').style.visibility="visible";
	}else{
		document.getElementById('clearagenamesbutton').style.visibility="hidden";
	}



	var showclearmaterialsbutton="no";
	var materialssel = document.getElementById('materials');
	var materiallist='';
	var delim='';
	for (p = 0; p<=materialssel.length - 1; p++) {
		if (materialssel.options[p].selected) {
			materiallist=materiallist+delim+materialssel.options[p].value;
			delim='***';
			if(p > 0){
				showclearmaterialsbutton="yes";
			}
		}
	}
	if(showclearmaterialsbutton=="yes"){
		document.getElementById('clearmaterialsbutton').style.visibility="visible";
	}else{
		document.getElementById('clearmaterialsbutton').style.visibility="hidden";
	}







	//get agemethod
	agemethod = document.getElementById("agemethod").value;











	var url = 'searchupdate.php';
	//var pars = 'level1='+escape($F('level1'))+'&level2='+escape($F('level2'))+'&level3='+escape($F('level3'));
	
	
	
	var pars = pars + '&userpkey='+escape($F('userpkey'));
	var pars = pars + '&pkey='+escape($F('pkey'));
	var pars = pars + '&locnorth='+escape($F('north'));
	var pars = pars + '&loceast='+escape($F('east'));
	var pars = pars + '&locsouth='+escape($F('south'));
	var pars = pars + '&locwest='+escape($F('west'));
	var pars = pars + '&coordinates='+escape($F('coordinates'));
	var pars = pars + '&sampleagevaluemin='+escape($F('minage'));
	var pars = pars + '&sampleagevaluemax='+escape($F('maxage'));
	var pars = pars + '&sampleagevalue='+escape($F('age'));
	var pars = pars + '&maxageuncertainty='+escape($F('ageplusminus'));
	var pars = pars + '&igsn='+escape($F('igsn'));
	var pars = pars + '&sample_id='+escape($F('sample_id'));
	var pars = pars + '&collector='+escape($F('collector'));
	var pars = pars + '&sampledescription='+escape($F('sampledescription'));
	var pars = pars + '&collectionmethod='+escape($F('collectionmethod'));
	var pars = pars + '&samplecomment='+escape($F('samplecomment'));
	var pars = pars + '&primarylocationname='+escape($F('primarylocationname'));
	var pars = pars + '&primarylocationtype='+escape($F('primarylocationtype'));
	var pars = pars + '&locationdescription='+escape($F('locationdescription'));
	var pars = pars + '&locality='+escape($F('locality'));
	var pars = pars + '&localitydescription='+escape($F('localitydescription'));
	var pars = pars + '&country='+escape($F('country'));
	var pars = pars + '&provice='+escape($F('province'));
	var pars = pars + '&ageunit='+escape(ageunit);
	var pars = pars + '&hiddenrocktypes='+escape(rocktypelist);
	var pars = pars + '&labnames='+escape(labnamelist);
	var pars = pars + '&purposes='+escape(purposelist);
	var pars = pars + '&materials='+escape(materiallist);
	var pars = pars + '&sampleagetype='+escape(agemethod);

	
	var myAjax = new Ajax.Request(url, {
		method: 'post',
		parameters: pars,
		onSuccess: function(transport) {
			//alert(transport.responseText);
			document.getElementById("results").innerHTML=transport.responseText;
		},
		onFailure: function(t) {
			alert('Error ' + t.status + ' -- ' + t.statusText);
		},
	});
	

	//scroll(0,0);
	$j('html, body').animate({ scrollTop: 0 }, 'slow');

	
	//alert('dosearch here');

}