	function addlabs()
	{
	  var labtypesel = document.getElementById('labnamegroup');
	  var labchosensel = document.getElementById('labchosengroup');
	  var i;
	  for (i = labtypesel.length - 1; i>=0; i--) {
		if (labtypesel.options[i].selected) {

			var laboptnew = document.createElement('option');
			laboptnew.text = labtypesel.options[i].value;
			laboptnew.value = labtypesel.options[i].text;

			  try {
				labchosensel.add(laboptnew, null); // standards compliant; doesn't work in IE
			  }
			  catch(ex) {
				labchosensel.add(laboptnew); // IE only
			  }

			labtypesel.remove(i);
		}
	  }
	  
	  var labtypelist='';
	  var delim='';
	  for (i = 0; i<=labchosensel.length - 1; i++) {
	    labtypelist=labtypelist+delim+labchosensel.options[i].value;
	    delim='***';
	  }
	  mylist = document.getElementById('labnames');
	  mylist.value=labtypelist;
	  
	}


	function removelabs()
	{
	  var labtypesel = document.getElementById('labnamegroup');
	  var labchosensel = document.getElementById('labchosengroup');
	  var i;
	  for (i = labchosensel.length - 1; i>=0; i--) {
		if (labchosensel.options[i].selected) {

			var laboptnew = document.createElement('option');
			laboptnew.text = labchosensel.options[i].value;
			laboptnew.value = labchosensel.options[i].text;

			  try {
				labtypesel.add(laboptnew, null); // standards compliant; doesn't work in IE
			  }
			  catch(ex) {
				labtypesel.add(laboptnew); // IE only
			  }

			labchosensel.remove(i);
		}
	  }
	  
	  var labtypelist='';
	  var delim='';
	  for (i = 0; i<=labchosensel.length - 1; i++) {
	    labtypelist=labtypelist+delim+labchosensel.options[i].value;
	    delim='***';
	  }
	  mylist = document.getElementById('labnames');
	  mylist.value=labtypelist;
	
	}

	function clearlabs()
	{
	  var labtypesel = document.getElementById('labnamegroup');
	  var labchosensel = document.getElementById('labchosengroup');
	  var i;
	  for (i = labchosensel.length - 1; i>=0; i--) {

			var laboptnew = document.createElement('option');
			laboptnew.text = labchosensel.options[i].value;
			laboptnew.value = labchosensel.options[i].text;

			  try {
				labtypesel.add(laboptnew, null); // standards compliant; doesn't work in IE
			  }
			  catch(ex) {
				labtypesel.add(laboptnew); // IE only
			  }

			labchosensel.remove(i);
	  }

	  mylist = document.getElementById('labnames');
	  mylist.value='';
	  
	}
	
	function showlabs()
	{
	
		mylist = document.getElementById('labnames').value;
		alert(mylist);
	
	}