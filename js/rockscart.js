	function addrocks()
	{
	  var rocktypesel = document.getElementById('rocktypegroup');
	  var rockchosensel = document.getElementById('rockchosentype');
	  var i;
	  for (i = rocktypesel.length - 1; i>=0; i--) {
		if (rocktypesel.options[i].selected) {

			var rockoptnew = document.createElement('option');
			rockoptnew.text = rocktypesel.options[i].value;
			rockoptnew.value = rocktypesel.options[i].text;

			  try {
				rockchosensel.add(rockoptnew, null); // standards compliant; doesn't work in IE
			  }
			  catch(ex) {
				rockchosensel.add(rockoptnew); // IE only
			  }

			rocktypesel.remove(i);
		}
	  }
	  
	  var rocktypelist='';
	  var delim='';
	  for (i = 0; i<=rockchosensel.length - 1; i++) {
	    rocktypelist=rocktypelist+delim+rockchosensel.options[i].value;
	    delim=',';
	  }
	  mylist = document.getElementById('hiddenrocktypes');
	  mylist.value=rocktypelist;
	  
	}


	function removerocks()
	{
	  var rocktypesel = document.getElementById('rocktypegroup');
	  var rockchosensel = document.getElementById('rockchosentype');
	  var i;
	  for (i = rockchosensel.length - 1; i>=0; i--) {
		if (rockchosensel.options[i].selected) {

			var rockoptnew = document.createElement('option');
			rockoptnew.text = rockchosensel.options[i].value;
			rockoptnew.value = rockchosensel.options[i].text;

			  try {
				rocktypesel.add(rockoptnew, null); // standards compliant; doesn't work in IE
			  }
			  catch(ex) {
				rocktypesel.add(rockoptnew); // IE only
			  }

			rockchosensel.remove(i);
		}
	  }
	  
	  var rocktypelist='';
	  var delim='';
	  for (i = 0; i<=rockchosensel.length - 1; i++) {
	    rocktypelist=rocktypelist+delim+rockchosensel.options[i].value;
	    delim=',';
	  }
	  mylist = document.getElementById('hiddenrocktypes');
	  mylist.value=rocktypelist;
	
	}

	function clearrocks()
	{
	  var rocktypesel = document.getElementById('rocktypegroup');
	  var rockchosensel = document.getElementById('rockchosentype');
	  var i;
	  for (i = rockchosensel.length - 1; i>=0; i--) {

			var rockoptnew = document.createElement('option');
			rockoptnew.text = rockchosensel.options[i].value;
			rockoptnew.value = rockchosensel.options[i].text;

			  try {
				rocktypesel.add(rockoptnew, null); // standards compliant; doesn't work in IE
			  }
			  catch(ex) {
				rocktypesel.add(rockoptnew); // IE only
			  }

			rockchosensel.remove(i);
	  }

	  mylist = document.getElementById('hiddenrocktypes');
	  mylist.value='';
	  
	}
	
	function showrocks()
	{
	
		mylist = document.getElementById('hiddenrocktypes').value;
		alert(mylist);
	
	}