


	var errFunc = function(t) {
    	alert('Error ' + t.status + ' -- ' + t.statusText);
	}

	function updatebox(footext) {

		var checklist='';
		var checkdelim='';

		var redux=document.getElementById('reduxcheck');
		if(redux.checked==1){
			//alert('redux checked');
			checklist=checklist+checkdelim+'redux';
			checkdelim=',';
		}

		var arar=document.getElementById('ararcheck');
		if(arar.checked==1){
			//alert('arar checked');
			checklist=checklist+checkdelim+'arar';
			checkdelim=',';
		}

		var helios=document.getElementById('helioscheck');
		if(helios.checked==1){
			//alert('helios checked');
			checklist=checklist+checkdelim+'helios';
			checkdelim=',';
		}







		//alert('checklist: '+checklist);
		
		//alert('here');
		//alert('changed to '+footext);
		//DeselectAllList('agetype');
		var url = 'getagetypes.php';
		var pars = 'project='+checklist;
		var myAjax = new Ajax.Request(url, {	method: 'get',	parameters: pars, onSuccess:handleragetype, onFailure:errFunc});

		if(checklist==''){
			document.getElementById('hiddenbox').style.display='none';
			document.getElementById('visiblebox').style.display='block';
		}else{
			document.getElementById('hiddenbox').style.display='block';
			document.getElementById('visiblebox').style.display='none';
		}

	}

	function DeselectAllList(CONTROL){
	  var agetypesel = document.getElementById('agetype');
	  var i;
	  for (i = agetypesel.length - 1; i>=0; i--) {
			agetypesel.remove(i);
	  }
	}

	var handleragetype = function(t) {
		
		xmlstuff = t.responseText;
		var xmlobject = (new DOMParser()).parseFromString(xmlstuff, "text/xml");
		var root = xmlobject.getElementsByTagName('ajaxresponse')[0];
		var items = root.getElementsByTagName("agetype");
		var square2 = document.forms[0].agetypelist;
		square2.options.length = 0;
		var z=0;
		for (var i = 0 ; i < items.length ; i++) {
			var item = items[i];
			var name = item.getElementsByTagName("typename")[0].firstChild.nodeValue;
			var thisvalue = item.getElementsByTagName("typevalue")[0].firstChild.nodeValue;

			
			
			
			
			//**************************************************************************************
			var agechosensel = document.getElementById('agechosentype');
			var y;
			var putin='yes';
			for (y = agechosensel.length - 1; y>=0; y--) {
				if(agechosensel[y].value==name){
					putin='no';
				}
			}
			
			if(putin=='yes'){
				//add it to agetypelist
				square2.options[z] = new Option(name,name);
				z=z+1;
			}
			
		}

	}




	function clearages()
	{
	  var agetypesel = document.getElementById('agetypelist');
	  var agechosensel = document.getElementById('agechosentype');
	  agechosensel.length=0;

	  mylist = document.getElementById('agetype');
	  mylist.value='';
	  
	  updatebox();
	  
	}




	function removeages()
	{
	  var agetypesel = document.getElementById('agetypelist');
	  var agechosensel = document.getElementById('agechosentype');
	  var i;
	  for (i = agechosensel.length - 1; i>=0; i--) {
		if (agechosensel.options[i].selected) {

			agechosensel.remove(i);
		}
	  }
	  
	  var agetypelist='';
	  var delim='';
	  for (i = 0; i<=agechosensel.length - 1; i++) {
	    agetypelist=agetypelist+delim+agechosensel.options[i].value;
	    delim=',';
	  }
	  mylist = document.getElementById('agetype');
	  mylist.value=agetypelist;
		
		updatebox();
	}

	function addages()
	{
	  var agetypesel = document.getElementById('agetypelist');
	  var agechosensel = document.getElementById('agechosentype');
	  var i;
	  for (i = agetypesel.length - 1; i>=0; i--) {
		if (agetypesel.options[i].selected) {

			var ageoptnew = document.createElement('option');
			ageoptnew.text = agetypesel.options[i].value;
			ageoptnew.value = agetypesel.options[i].text;

			  try {
				agechosensel.add(ageoptnew, null); // standards compliant; doesn't work in IE
			  }
			  catch(ex) {
				agechosensel.add(ageoptnew); // IE only
			  }

			
		}
		
		
		
	  }
	  
	  updatebox();
	  
	  var agetypelist='';
	  var delim='';
	  for (i = 0; i<=agechosensel.length - 1; i++) {
	    agetypelist=agetypelist+delim+agechosensel.options[i].value;
	    delim=',';
	  }
	  mylist = document.getElementById('agetype');
	  mylist.value=agetypelist;
	  
	}





