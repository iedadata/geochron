	function addminerals()
	{
	  var mineraltypesel = document.getElementById('mineraltypegroup');
	  var mineralchosensel = document.getElementById('mineralchosentype');
	  var i;
	  for (i = mineraltypesel.length - 1; i>=0; i--) {
		if (mineraltypesel.options[i].selected) {

			var mineraloptnew = document.createElement('option');
			mineraloptnew.text = mineraltypesel.options[i].value;
			mineraloptnew.value = mineraltypesel.options[i].text;

			  try {
				mineralchosensel.add(mineraloptnew, null); // standards compliant; doesn't work in IE
			  }
			  catch(ex) {
				mineralchosensel.add(mineraloptnew); // IE only
			  }

			mineraltypesel.remove(i);
		}
	  }
	  
	  var mineraltypelist='';
	  var delim='';
	  for (i = 0; i<=mineralchosensel.length - 1; i++) {
	    mineraltypelist=mineraltypelist+delim+mineralchosensel.options[i].value;
	    delim=',';
	  }
	  mylist = document.getElementById('minerals');
	  mylist.value=mineraltypelist;
	  
	}


	function removeminerals()
	{
	  var mineraltypesel = document.getElementById('mineraltypegroup');
	  var mineralchosensel = document.getElementById('mineralchosentype');
	  var i;
	  for (i = mineralchosensel.length - 1; i>=0; i--) {
		if (mineralchosensel.options[i].selected) {

			var mineraloptnew = document.createElement('option');
			mineraloptnew.text = mineralchosensel.options[i].value;
			mineraloptnew.value = mineralchosensel.options[i].text;

			  try {
				mineraltypesel.add(mineraloptnew, null); // standards compliant; doesn't work in IE
			  }
			  catch(ex) {
				mineraltypesel.add(mineraloptnew); // IE only
			  }

			mineralchosensel.remove(i);
		}
	  }
	  
	  var mineraltypelist='';
	  var delim='';
	  for (i = 0; i<=mineralchosensel.length - 1; i++) {
	    mineraltypelist=mineraltypelist+delim+mineralchosensel.options[i].value;
	    delim=',';
	  }
	  mylist = document.getElementById('minerals');
	  mylist.value=mineraltypelist;
	
	}

	function clearminerals()
	{
	  var mineraltypesel = document.getElementById('mineraltypegroup');
	  var mineralchosensel = document.getElementById('mineralchosentype');
	  var i;
	  for (i = mineralchosensel.length - 1; i>=0; i--) {

			var mineraloptnew = document.createElement('option');
			mineraloptnew.text = mineralchosensel.options[i].value;
			mineraloptnew.value = mineralchosensel.options[i].text;

			  try {
				mineraltypesel.add(mineraloptnew, null); // standards compliant; doesn't work in IE
			  }
			  catch(ex) {
				mineraltypesel.add(mineraloptnew); // IE only
			  }

			mineralchosensel.remove(i);
	  }

	  mylist = document.getElementById('minerals');
	  mylist.value='';
	  
	}
	
	function showminerals()
	{
	
		mylist = document.getElementById('minerals').value;
		alert(mylist);
	
	}