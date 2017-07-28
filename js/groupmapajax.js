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






	function showanimation(){
		document.getElementById('results').style.display="none";
		document.getElementById('animationbar').style.display="block";
	}

	function hideanimation(){
		document.getElementById('animationbar').style.display="none";
		document.getElementById('results').style.display="block";
	}



	function show_my_form(mylat,mylon,mygrouppkey,myzoom,showfig){

			//document.getElementById('results').innerHTML = '<img src="loadingAnimation.gif">';
			showanimation();

			//alert('you clicked point '+mypkey);
			AjaxRequest.get(
				{
					'url':'groupmapquery.php'
					,'parameters':{ 'mylat':mylat, 'mylon':mylon,'group_pkey':mygrouppkey, 'zoom':myzoom, 'showfig':showfig }
					//,'onSuccess':function(req){ updatebox.value=req.responseText; }
					//,'onSuccess':function(req){ alert('Success!'); }
					//,'onSuccess':function(req) { document.getElementById(updatebox).innerHTML = req.url; }
					,'onSuccess':function(req) { document.getElementById('results').innerHTML = req.responseText; hideanimation(); }
					,'onError':function(req){ document.getElementById('pointdetail').innerHTML = req.responseText;}
					//,'onError':function(req){ alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);}
				}
			);


	}
	


	function show_detail(mypkey,mylat,mylon,mygrouppkey,myzoom,myshowfig){
	
			showanimation();


			//alert('you clicked point '+mypkey);
			AjaxRequest.get(
				{
					'url':'groupmapquery.php'
					,'parameters':{ 'sample_pkey':mypkey, 'mylat':mylat, 'mylon':mylon, 'group_pkey':mygrouppkey, 'zoom':myzoom, 'showfig':myshowfig }
					//,'onSuccess':function(req){ updatebox.value=req.responseText; }
					//,'onSuccess':function(req){ alert('Success!'); }
					//,'onSuccess':function(req) { document.getElementById(updatebox).innerHTML = req.url; }
					,'onSuccess':function(req) { document.getElementById('results').innerHTML = req.responseText;  hideanimation(); }
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




	function greetclear(updatebox, pkey){
		AjaxRequest.get(
			{
				'url':'greetingclear.cfm'
				,'parameters':{ 'item':updatebox, 'pkey':pkey }
				//,'onSuccess':function(req){ updatebox.value=req.responseText; }
				//,'onSuccess':function(req){ alert('Success!'); }
				//,'onSuccess':function(req) { document.getElementById(updatebox).innerHTML = req.url; }
				,'onSuccess':function(req) { document.getElementById(updatebox).innerHTML = '&nbsp;'; }
				,'onError':function(req){ alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);}
			}
		);

	}

