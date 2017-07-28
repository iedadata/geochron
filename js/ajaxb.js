
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


	Event.observe(window, 'load', init, false);




	function DeselectAllList(CONTROL){
	for(var i = 0;i < document.getElementById(CONTROL).length;i++){
	document.getElementById(CONTROL).options[i].selected = false;
	}
	}


	function init(){
	obj=document.getElementById('box2');
	obj.style.display=('none');
	obj=document.getElementById('box3');
	obj.style.display=('none');
	obj=document.getElementById('box4');
	obj.style.display=('none');

	//Event.observe('rockclass', 'change', dorocknamebox, false);
	Event.observe('level1', 'change', dobox1, false);
	Event.observe('level2', 'change', dobox2, false);
	Event.observe('level3', 'change', dobox3, false);
	}


	var errFunc = function(t) {
    	alert('Error ' + t.status + ' -- ' + t.statusText);
	}


	var handlerFuncone = function(t) {
		xmlstuff = t.responseText;
		var xmlobject = (new DOMParser()).parseFromString(xmlstuff, "text/xml");
		var root = xmlobject.getElementsByTagName('ajaxresponse')[0];
		var items = root.getElementsByTagName("rockname");
		var square2 = document.forms[0].level2;
		square2.options.length = 0;
		for (var i = 0 ; i < items.length ; i++) {
			var item = items[i];
			var name = item.getElementsByTagName("name")[0].firstChild.nodeValue;

				square2.options[i] = new Option(name,name);

		}

		if(items.length > 0){
			obj=document.getElementById('box2');
			obj.style.display=('');
		}
	}
	
	
	var handlerrocknames = function(t) {
		xmlstuff = t.responseText;
		var xmlobject = (new DOMParser()).parseFromString(xmlstuff, "text/xml");
		var root = xmlobject.getElementsByTagName('ajaxresponse')[0];
		var items = root.getElementsByTagName("rockname");
		var square2 = document.forms[0].rockname;
		square2.options.length = 0;
		for (var i = 0 ; i < items.length ; i++) {
			var item = items[i];
			var name = item.getElementsByTagName("name")[0].firstChild.nodeValue;

				square2.options[i] = new Option(name,name);

		}

		if(items.length > 0){
			obj=document.getElementById('box4');
			obj.style.display=('');
		}
	}


	var handlerFuncodd = function(t) {
		xmlstuff = t.responseText;
		var xmlobject = (new DOMParser()).parseFromString(xmlstuff, "text/xml");
		var root = xmlobject.getElementsByTagName('ajaxresponse')[0];
		var items = root.getElementsByTagName("rockname");
		var square4 = document.forms[0].level4;
		square4.options.length = 0;
		for (var i = 0 ; i < items.length ; i++) {
			var item = items[i];
			var name = item.getElementsByTagName("name")[0].firstChild.nodeValue;

				square4.options[i] = new Option(name,name);

		}

		if(items.length > 0){
			obj=document.getElementById('box4');
			obj.style.display=('');
		}
	}



	var handlerFunctwo = function(t) {
		//alert('handlerfunctwo');
		xmlstuff = t.responseText;
		var xmlobject = (new DOMParser()).parseFromString(xmlstuff, "text/xml");
		var root = xmlobject.getElementsByTagName('ajaxresponse')[0];
		var items = root.getElementsByTagName("rockname");
		var square2 = document.forms[0].level3;
		square2.options.length = 0;
		for (var i = 0 ; i < items.length ; i++) {
			var item = items[i];
			var name = item.getElementsByTagName("name")[0].firstChild.nodeValue;

				square2.options[i] = new Option(name,name);

		}

		if(items.length > 0){
			obj=document.getElementById('box3');
			obj.style.display=('');
		}
	}



	var handlerFuncthree = function(t) {
		xmlstuff = t.responseText;
		var xmlobject = (new DOMParser()).parseFromString(xmlstuff, "text/xml");
		var root = xmlobject.getElementsByTagName('ajaxresponse')[0];
		var items = root.getElementsByTagName("rockname");
		var square2 = document.forms[0].level4;
		square2.options.length = 0;
		for (var i = 0 ; i < items.length ; i++) {
			var item = items[i];
			var name = item.getElementsByTagName("name")[0].firstChild.nodeValue;

				square2.options[i] = new Option(name,name);

		}

		if(items.length > 0){
			obj=document.getElementById('box4');
			obj.style.display=('');
		}
	}



	function dobox1(){
	//alert('hi');
	var count=0;
	//alert('changed');

	for(var i = 0;i < document.getElementById('level1').length;i++){
		if(document.getElementById('level1').options[i].selected == true)
		{
		count=count+1;
		//set variable here holding select value.
		//alert(document.getElementById('level1').options[i].value);
		selvalue=document.getElementById('level1').options[i].value;
		}
	}

		obj=document.getElementById('box2');
		obj.style.display=('none');

	if(count==1)
	{
		//add if metamorphic or if ore here.... and then call getmetamorphic.php or getore.php accordingly - jma

		if(selvalue=='ALTERATION' || selvalue=='METAMORPHIC' || selvalue=='ORE' || selvalue=='VEIN' || selvalue=='SEDIMENTARY')
		{

			var url = 'getoddlevels.php';
			var pars = 'level1='+escape($F('level1'));
			
			alert('http://www.geochron.org/getoddlevels.php?'+pars);
			
			var myAjax = new Ajax.Request(url, {	method: 'get',	parameters: pars, onSuccess:handlerFuncodd, onFailure:errFunc});

		}else{
			var url = 'getlevel2.php';
			var pars = 'level1='+escape($F('level1'));
			var myAjax = new Ajax.Request(url, {	method: 'get',	parameters: pars, onSuccess:handlerFuncone, onFailure:errFunc});
		}

	}
	else
	{
		obj=document.getElementById('box2');
		obj.style.display=('none');
	}

	DeselectAllList('level2');
	obj=document.getElementById('box3');
	obj.style.display=('none');
	DeselectAllList('level3');
	obj=document.getElementById('box4');
	obj.style.display=('none');
	DeselectAllList('level4');
	}




	function dorocknamebox(){
	var count=0;
	//alert('changed');

	for(var i = 0;i < document.getElementById('rockclass').length;i++){
		if(document.getElementById('rockclass').options[i].selected == true)
		{
		count=count+1;
		//set variable here holding select value.
		//alert(document.getElementById('level1').options[i].value);
		selvalue=document.getElementById('rockclass').options[i].value;
		}
	}

		obj=document.getElementById('box2');
		obj.style.display=('none');

	if(count==1)
	{
		//add if metamorphic or if ore here.... and then call getmetamorphic.php or getore.php accordingly - jma


			var url = 'getrocknames.php';
			var pars = 'mainclasses='+escape($F('rockclass'));
			var myAjax = new Ajax.Request(url, {	method: 'get',	parameters: pars, onSuccess:handlerrocknames, onFailure:errFunc});


	}
	else
	{
		obj=document.getElementById('box2');
		obj.style.display=('none');
	}

	DeselectAllList('level2');
	obj=document.getElementById('box3');
	obj.style.display=('none');
	DeselectAllList('level3');
	obj=document.getElementById('box4');
	obj.style.display=('none');
	DeselectAllList('rockname');
	}




	function dobox2(){
	//alert('two');
	var count=0;

	for(var i = 0;i < document.getElementById('level2').length;i++){
		if(document.getElementById('level2').options[i].selected == true)
		{
		count=count+1;
		}
	}
	
	//alert('count: '+count);

		obj=document.getElementById('box3');
		obj.style.display=('none');

	if(count==1)
	{

	  	//alert('count equals 1');
	  	var url = 'getlevel3.php';
		var pars = 'level1='+escape($F('level1'))+'&level2='+escape($F('level2'));
		//alert(pars);
		var myAjax = new Ajax.Request(url, {	method: 'get',	parameters: pars, onSuccess:handlerFunctwo, onFailure:errFunc});


	}else{
		//alert('count does not equal 1');

		obj=document.getElementById('box3');
		obj.style.display=('none');

	}

	document.getElementById('level3').value = '';
	obj=document.getElementById('box3');
	obj.style.display=('none');
	DeselectAllList('level3');
	obj=document.getElementById('box4');
	obj.style.display=('none');
	DeselectAllList('level4');
	}



	function dobox3(){
	var count=0;

	for(var i = 0;i < document.getElementById('level3').length;i++){
		if(document.getElementById('level3').options[i].selected == true)
		{
		count=count+1;
		}
	}

		obj=document.getElementById('box4');
		obj.style.display=('none');

	if(count==1)
	{

	  	var url = 'getlevel4.php';
		var pars = 'level1='+escape($F('level1'))+'&level2='+escape($F('level2'))+'&level3='+escape($F('level3'));
		
		//alert('http://www.geochron.org/getlevel4.php?'+pars);
		
		var myAjax = new Ajax.Request(url, {	method: 'get',	parameters: pars, onSuccess:handlerFuncthree, onFailure:errFunc});




	}
	else
	{
		obj=document.getElementById('box4');
		obj.style.display=('none');
	}

	DeselectAllList('level4');
	}


	function addrocks()
	{
	  var rocknamesel = document.getElementById('rocknamegroup');
	  var rockchosensel = document.getElementById('rockchosengroup');
	  var i;
	  for (i = rocknamesel.length - 1; i>=0; i--) {
		if (rocknamesel.options[i].selected) {

			var rockoptnew = document.createElement('option');
			rockoptnew.text = rocknamesel.options[i].value;
			rockoptnew.value = rocknamesel.options[i].text;

			  try {
				rockchosensel.add(rockoptnew, null); // standards compliant; doesn't work in IE
			  }
			  catch(ex) {
				rockchosensel.add(rockoptnew); // IE only
			  }

			rocknamesel.remove(i);
		}
	  }
	  
	  var rocknamelist='';
	  var delim='';
	  for (i = 0; i<=rockchosensel.length - 1; i++) {
	    rocknamelist=rocknamelist+delim+rockchosensel.options[i].value;
	    delim=',';
	  }
	  mylist = document.getElementById('hiddenrocknames');
	  mylist.value=rocknamelist;
	  
	}


	function removerocks()
	{
	  var rocknamesel = document.getElementById('rocknamegroup');
	  var rockchosensel = document.getElementById('rockchosengroup');
	  var i;
	  for (i = rockchosensel.length - 1; i>=0; i--) {
		if (rockchosensel.options[i].selected) {

			var rockoptnew = document.createElement('option');
			rockoptnew.text = rockchosensel.options[i].value;
			rockoptnew.value = rockchosensel.options[i].text;

			  try {
				rocknamesel.add(rockoptnew, null); // standards compliant; doesn't work in IE
			  }
			  catch(ex) {
				rocknamesel.add(rockoptnew); // IE only
			  }

			rockchosensel.remove(i);
		}
	  }
	  
	  var rocknamelist='';
	  var delim='';
	  for (i = 0; i<=rockchosensel.length - 1; i++) {
	    rocknamelist=rocknamelist+delim+rockchosensel.options[i].value;
	    delim=',';
	  }
	  mylist = document.getElementById('hiddenrocknames');
	  mylist.value=rocknamelist;
	
	}

	function clearrocks()
	{
	  var rocknamesel = document.getElementById('rocknamegroup');
	  var rockchosensel = document.getElementById('rockchosengroup');
	  var i;
	  for (i = rockchosensel.length - 1; i>=0; i--) {

			var rockoptnew = document.createElement('option');
			rockoptnew.text = rockchosensel.options[i].value;
			rockoptnew.value = rockchosensel.options[i].text;

			  try {
				rocknamesel.add(rockoptnew, null); // standards compliant; doesn't work in IE
			  }
			  catch(ex) {
				rocknamesel.add(rockoptnew); // IE only
			  }

			rockchosensel.remove(i);
	  }

	  mylist = document.getElementById('hiddenrocknames');
	  mylist.value='';
	  
	}
	
	function showrocks()
	{
	
		mylist = document.getElementById('hiddenrocknames').value;
		alert(mylist);
	
	}