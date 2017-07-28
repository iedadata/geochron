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



	function show_my_form(mylat,mylon,mysqpkey,myzoom,showfig){

			//document.getElementById('results').innerHTML = '<img src="loadingAnimation.gif">';
			showanimation();

			//alert('you clicked point '+mypkey);
			AjaxRequest.get(
				{
					'url':'dynmapquery.php'
					,'parameters':{ 'mylat':mylat, 'mylon':mylon, 'pkey':mysqpkey, 'zoom':myzoom, 'showfig':showfig }
					//,'onSuccess':function(req){ updatebox.value=req.responseText; }
					//,'onSuccess':function(req){ alert('Success!'); }
					//,'onSuccess':function(req) { document.getElementById(updatebox).innerHTML = req.url; }
					,'onSuccess':function(req) { document.getElementById('results').innerHTML = req.responseText; hideanimation(); }
					,'onError':function(req){ document.getElementById('pointdetail').innerHTML = req.responseText;}
					//,'onError':function(req){ alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);}
				}
			);


	}
	


	function show_detail(mypkey,mylat,mylon,mysearch_query_pkey,myzoom,myshowfig){
	
			showanimation();


			//alert('you clicked point '+mypkey);
			AjaxRequest.get(
				{
					'url':'dynmapquery.php'
					,'parameters':{ 'sample_pkey':mypkey, 'mylat':mylat, 'mylon':mylon, 'pkey':mysearch_query_pkey, 'zoom':myzoom, 'showfig':myshowfig }
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


	function greet(updatebox, pkey){
		tb_remove();

		//var myclinic=document.getElementById('clinic').value;


		//myobj=document.forms[0].signs_of_rabies;
		//document.getElementById('foofoo').innerHTML = getCheckbox(myobj);
		//myobj=document.getElementsByName('substate');
		//document.getElementById('foofoo').innerHTML = getRadio(myobj);
		//document.getElementById('foofoo').innerHTML = document.getElementById('longitude').value;



		myobj=document.getElementsByName('substate');
		mysubstate = getRadio(myobj);

		myobj=document.getElementsByName('vacc_status');
		myvacc_status = getRadio(myobj);

		myobj=document.getElementsByName('sick');
		mysick = getRadio(myobj);

		myobj=document.getElementsByName('bittenyesno');
		mybittenyesno = getRadio(myobj);

		myobj=document.getElementsByName('petcontact');
		mypetcontact = getRadio(myobj);

		myobj=document.getElementsByName('pet_contact_vacc');
		mypet_contact_vacc = getRadio(myobj);

		myobj=document.forms[0].signs_of_rabies;
		mysigns_of_rabies = getCheckbox(myobj);

		myobj=document.forms[0].signs_other;
		mysigns_other = getCheckbox(myobj);


		AjaxRequest.post(
			{
				'url':'upload.cfm',
				'parameters':{
					'longitude':document.getElementById('longitude').value,
					'latitude':document.getElementById('latitude').value,
					'neb_ra':document.getElementById('neb_ra').value,
					'othersub':document.getElementById('othersub').value,
					'clinic':document.getElementById('clinic').value,
					'contact':document.getElementById('contact').value,
					'address1':document.getElementById('address1').value,
					'address2':document.getElementById('address2').value,
					'phone':document.getElementById('phone').value,
					'city':document.getElementById('city').value,
					'state':document.getElementById('state').value,
					'zip':document.getElementById('zip').value,
					'fax':document.getElementById('fax').value,
					'kind_of_animal':document.getElementById('kind_of_animal').value,
					'breed_species':document.getElementById('breed_species').value,
					'age':document.getElementById('age').value,
					'color':document.getElementById('color').value,
					'town':document.getElementById('town').value,
					'county':document.getElementById('county').value,
					'spec_location':document.getElementById('spec_location').value,
					'signs_other_desc':document.getElementById('signs_other_desc').value,
					'date_of_death':document.getElementById('date_of_death').value,
					'manner_of_death':document.getElementById('manner_of_death').value,
					'date_submitted':document.getElementById('date_submitted').value,
					'owner_name':document.getElementById('owner_name').value,
					'owner_phone':document.getElementById('owner_phone').value,
					'owner_address1':document.getElementById('owner_address1').value,
					'owner_address2':document.getElementById('owner_address2').value,
					'owner_city':document.getElementById('owner_city').value,
					'owner_state':document.getElementById('owner_state').value,
					'owner_zip':document.getElementById('owner_zip').value,
					'bitten_name':document.getElementById('bitten_name').value,
					'bitten_date':document.getElementById('bitten_date').value,
					'bitten_details':document.getElementById('bitten_details').value,
					'pet_contact_date':document.getElementById('pet_contact_date').value,
					'pet_contact_species':document.getElementById('pet_contact_species').value,
					'pet_owner':document.getElementById('pet_owner').value,
					'pet_owner_address1':document.getElementById('pet_owner_address1').value,
					'pet_owner_address2':document.getElementById('pet_owner_address2').value,
					'pet_owner_phone':document.getElementById('pet_owner_phone').value,
					'substate':mysubstate,
					'vacc_status':myvacc_status,
					'sick':mysick,
					'bittenyesno':mybittenyesno,
					'petcontact':mypetcontact,
					'pet_contact_vacc':mypet_contact_vacc,
					'signs_of_rabies':mysigns_of_rabies,
					'signs_other':mysigns_other
				}




				//,'onSuccess':function(req){ updatebox.value=req.responseText; }
				//,'onSuccess':function(req){ alert('Success!'); }
				//,'onSuccess':function(req) { document.getElementById(updatebox).innerHTML = req.url; }
				//,'onSuccess':function(req) { document.getElementById('foofoo').innerHTML = req.responseText; }
				,'onSuccess':function(req) { show_new_marker(req.responseText); }
				,'onError':function(req){ document.getElementById('foofoo').innerHTML =req.responseText;}
			}
		);


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

