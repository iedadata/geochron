/*
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
*/




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




	function fetch_myresults(mypkey){

		myobj=document.getElementsByName('mymap_tool');
		mytool = getRadio(myobj);

		if(mytool == 'identify'){

			//alert('you clicked point '+mypkey);
			AjaxRequest.get(
				{
					'url':'detail.cfm'
					,'parameters':{ 'pkey':mypkey }
					//,'onSuccess':function(req){ updatebox.value=req.responseText; }
					//,'onSuccess':function(req){ alert('Success!'); }
					//,'onSuccess':function(req) { document.getElementById(updatebox).innerHTML = req.url; }
					,'onSuccess':function(req) { document.getElementById('pointdetail').innerHTML = req.responseText; }
					,'onError':function(req){ document.getElementById('pointdetail').innerHTML = req.responseText;}
					//,'onError':function(req){ alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);}
				}
			);

		}else{

		null;

		}

	}

	function showanimation(){
		document.getElementById('results').style.display="none";
		document.getElementById('animationbar').style.display="block";
	}

	function hideanimation(){
		document.getElementById('animationbar').style.display="none";
		document.getElementById('results').style.display="block";
	}



	function show_my_form(mylat,mylon,mysqpkey,myzoom,agemin,agemax,detritaltype,detritalmineral,detritalmethod,geoagelist,showall,showfig){

			//document.getElementById('results').innerHTML = '<img src="loadingAnimation.gif">';
			showanimation();

			//alert('you clicked point '+mypkey);
			AjaxRequest.get(
				{
					'url':'upstreammapquery.php'
					,'parameters':{ 'mylat':mylat, 'mylon':mylon, 'pkey':mysqpkey, 'zoom':myzoom, 'agemin':agemin, 'agemax':agemax, 'detritaltype':detritaltype,'detritalmineral':detritalmineral,'detritalmethod':detritalmethod,'geoages':geoagelist, 'showall':showall, 'showfig':showfig }
					//,'onSuccess':function(req){ updatebox.value=req.responseText; }
					//,'onSuccess':function(req){ alert('Success!'); }
					//,'onSuccess':function(req) { document.getElementById(updatebox).innerHTML = req.url; }
					,'onSuccess':function(req) { document.getElementById('results').innerHTML = req.responseText; hideanimation(); }
					,'onError':function(req){ document.getElementById('pointdetail').innerHTML = req.responseText;}
					//,'onError':function(req){ alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);}
				}
			);


	}
	


	function show_detail(mypkey,mylat,mylon,mysearch_query_pkey,myzoom,agemin,agemax,detritaltype,detritalmineral,detritalmethod,geoagelist,showall,conc){
	
			showanimation();


			//alert('you clicked point '+mypkey);
			AjaxRequest.get(
				{
					'url':'upstreammapquery.php'
					,'parameters':{ 'sample_pkey':mypkey, 'mylat':mylat, 'mylon':mylon, 'pkey':mysearch_query_pkey, 'zoom':myzoom, 'agemin':agemin, 'agemax':agemax, 'detritaltype':detritaltype,'detritalmineral':detritalmineral,'detritalmethod':detritalmethod, 'geoages':geoagelist, 'showall':showall, 'conc':conc }
					//,'onSuccess':function(req){ updatebox.value=req.responseText; }
					//,'onSuccess':function(req){ alert('Success!'); }
					//,'onSuccess':function(req) { document.getElementById(updatebox).innerHTML = req.url; }
					,'onSuccess':function(req) { document.getElementById('results').innerHTML = req.responseText;  hideanimation(); }
					,'onError':function(req){ document.getElementById('pointdetail').innerHTML = req.responseText;}
					//,'onError':function(req){ alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);}
				}
			);


	}

	function fetch_count(agemin,agemax,detritaltype,geoagelist){

			//document.getElementById('results').innerHTML = '<img src="loadingAnimation.gif">';
			//showanimation();

			//alert('you clicked point '+mypkey);
			AjaxRequest.get(
				{
					'url':'upstreamcount.php'
					,'parameters':{ 'agemin':agemin, 'agemax':agemax, 'detritaltype':detritaltype,'detritalmineral':detritalmineral,'detritalmethod':detritalmethod, 'geoages':geoagelist }
					//,'onSuccess':function(req){ updatebox.value=req.responseText; }
					//,'onSuccess':function(req){ alert('Success!'); }
					//,'onSuccess':function(req) { document.getElementById(updatebox).innerHTML = req.url; }
					,'onSuccess':function(req) {
						document.getElementById('constrainedcount').innerHTML = 'Filtered Sample Count: '+req.responseText;
						if(req.responseText=="0"){
							document.getElementById("showtable").style.display="none";
						}
					}
					,'onError':function(req){ document.getElementById('pointdetail').innerHTML = req.responseText;}
					//,'onError':function(req){ alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);}
				}
			);


	}

	function envelope_fetch_count(bounds,agemin,agemax,detritaltype,geoagelist){

			//document.getElementById('results').innerHTML = '<img src="loadingAnimation.gif">';
			//showanimation();

			//alert('you clicked point '+mypkey);
			AjaxRequest.get(
				{
					'url':'envelopeupstreamcount.php'
					,'parameters':{ 'bounds':bounds, 'agemin':agemin, 'agemax':agemax, 'detritaltype':detritaltype,'detritalmineral':detritalmineral,'detritalmethod':detritalmethod,'geoages':geoagelist }
					//,'onSuccess':function(req){ updatebox.value=req.responseText; }
					//,'onSuccess':function(req){ alert('Success!'); }
					//,'onSuccess':function(req) { document.getElementById(updatebox).innerHTML = req.url; }
					,'onSuccess':function(req) {
						document.getElementById('envelopeconstrainedcount').innerHTML = 'Filtered Sample Count in Current View: '+req.responseText;
					}
					,'onError':function(req){ document.getElementById('pointdetail').innerHTML = req.responseText;}
					//,'onError':function(req){ alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);}
				}
			);


	}



	function close_results(){


	document.getElementById('pointdetail').innerHTML = "";


	}

	function show_new_marker(mytext){

		mytext=mytext.replace(/ /,'');
		temp=mytext.split(',');

		mylon=temp[0];
		mylat=temp[1];
		mypkey=temp[2];

		//document.getElementById('foofoo').innerHTML = 'lon = '+mylon+' lat = '+mylat+' pkey = '+mypkey;

		AutoSizeAnchored = OpenLayers.Class(OpenLayers.Popup.Anchored, {
    		'autoSize': true
		});

		//anchored popup small contents autosize
		ll = new OpenLayers.LonLat(LonToGmaps(mylon),LatToGmaps(mylat));
		popupClass = AutoSizeAnchored;
		popupContentHTML = mypkey;
		addMarker(ll, popupClass, popupContentHTML);


	}




