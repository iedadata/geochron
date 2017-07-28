<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta name="keywords" content="" />
<meta name=”description” content=””>
<title>Geochron home</title>
<SCRIPT language="JavaScript">
<!--
pic1= new Image(100,25);
pic1.src="images/imagename.gif";
//-->
</SCRIPT>
<!--<link rel='stylesheet' type='text/css' media='all' href='geochron.css' />-->
<style type="text/css">
/* Nav grey #616161 */
/* Header grey #787878 */
/* Text grey #8c8c8c as of 20050112 */
/* Text grey #636363 */

/* adapted from Geochron logo:
dk red #810704"
dk blue #051072
mustard #4572b3
palest blue (background highlight on rollover, top menus, in main pages)#e3eefc
input box border pale blue-grey #7f9db9
*/

/*NYTimes top tabs pale blue: f0f4f5
NYTimes top tabs border: 999999*/
<!-- the tufte blue 4572b3 from plots, and a palest version for rollovers d7e6fc -->

.eej {font-size: 9px; color:blue; font-family:"times new roman",serif} /* eej notes during dev */
/* Default page values **********************************************************************/
body {
background-color: #ffffff;
margin: 20pt 20pt 20pt 20pt;
text-align: center;
}
td {
color: #636363;
font-family: verdana,arial,sans-serif;
}

body, td {
font-family: verdana,arial,sans-serif;
font-size: 8pt;
color: #636363;
}

/* Links ************************************************************************************/
a:link {
/* color: #1e4148; */
color: #152E33;
text-decoration: none;
}
a:visited {
color: #734B01;
text-decoration: none;
}
a:active {
color: #152E33;
}
a:hover {
color: #152E33;
text-decoration: underline;
}

/* pagetitle is used in jason's xml page viewfile.cfm */
h1, .pagetitle {
font-size: 10pt;
font-weight: 600; /* note: 400 is normal, 700 is bold */
color: #666666;
margin: 0px 0px 10px 0px;
}
.pagetitle {font-size:11pt; color:#636363; margin: 0px 0px 5px 0px;}
/* headline & fatlink are used in jason's xml page viewfile.cfm */
h2,h3,h4, .headline, .fatlink {
font-size: 8pt;
font-weight: 600;
color: #696969;
margin: 0 0 5px 0;
}
.page {
position: relative;top: 0;left: 0;
width: 705px;
text-align: left;
background-color: #cccccc;
padding: 0px 0px 0px 0px;
margin-left: auto;margin-right: auto;
border-style: none;border-color: cyan;border-width: 1px 1px 1px 1px;
}
a:link, a:visited {
/* color: #1e4148; */
color: #152E33;
text-decoration: none;
}
a:hover {
color: #152E33;
text-decoration: underline;
}
input {
/*float: right;
position: relative;top: 0;left: 0;*/
font-size: 8pt; line-height:130%;
margin: 0px 0px 0px 0px;
padding: 3px 3px 3px 3px;
}
a.button:link {
  border-style: solid;
  border-width: 1px;
  text-decoration: none;
  margin: 0px;
  padding: 5px 5px 5px 5px;
  border-color : #4572b3;
  text-decoration: none;
  color: #999999;
  font-family: verdana,arial,sans-serif;
  font-size: 12px;
  background-color:#f0f4f5;
}
a.button:hover,a:active {color:#333333;border-color:#333333;}
a.menulink { text-decoration:none;
  /*border-style: solid;
  border-width: 1px;
  text-decoration: none;
  padding: 3px 3px 3px 3px;
  margin-top:5px; margin-bottom:5px; margin-right:5px; margin-left:0px;
  border-color : #4572b3;
  text-decoration: none;
  color: #999999;
  font-family: verdana,arial,sans-serif;*/
  font-size: 11px;
}
a.menulink:hover,a:active { color:#990000;}

/* css for results page, from jason's original */
 table.aliquot, table.sample  {
	border-width: 1px 1px 1px 1px;
	border-spacing: 2px;
	border-style: none none none none;
	border-color: #999999; /*#636363;*/
	border-collapse: collapse;
	background-color: white;
}
table.aliquot th, table.sample th  {
	font-family:arial,verdana,sans-serif;
	font-size:10pt;
	font-weight: 500;
	color:#333333;
	text-transform:uppercase;
	/*color: #666699; #636363; #FFFFFF;*/
	border-color: #999999;
	border-width: 1px 1px 1px 1px;
	padding: 5px 5px 5px 5px;
	border-style: solid solid solid solid;
	background-color: #f0f4f5; /* NYTimes tabs background blue. Tried others: #d7e6fc; 325280 #003366;*/
}
table.sample th {
	background-color:antiquewhite;text-transform:none;
	}
table.aliquot td, table.sample td  {
	border-width: 1px 1px 1px 1px;
	border-color: #999999;
	padding: 2px 5px 2px 5px;
	border-style: solid solid solid solid;
	background-color: white;
}
/* styles used by viewfile.cfm - adapted from ones in jason's upbgeochron.css file - some redefined above */
.headlinexxx {
	color: #003366;
	font-weight: bold;
	font-size: 18px;
}
.pagetitlexxx {
	color: #003366;
	font-weight: bold;
	font-size: 28px;
}
.fatlinkxxx {
	color: #003366;
	font-weight: bold;
	font-size: 12px;
}
.mainbox {
	/*marginrgin-left: auto;
	margin-right: auto;*/
	width: 750px;
}
.box_one {  /*
	border-style: solid;
	border-width: 1px;
	padding: 10px;  5px;
	background-color: silver;  */
}
.box_two {line-height:140%;
	border-style: solid;
	border-width: 1px;
	border-color: #999999;
	padding: 10px; /*5px;*/
	background-color: #fefefe; /*f0f4f5;*/
	margin:0px 0px 0px 0px;
}
.box_five {  /*
	border-style: solid;
	border-width: 1px;
	padding: 10px;
	background-color: pink; */
}
table.samplexxx {
	border-width: 1px 1px 1px 1px;
	border-spacing: 2px;
	border-style: none none none none;
	border-color: gray gray gray gray;
	border-collapse: collapse;
	background-color: white;
}
table.samplexxx th {
	color: #003366;
	border-width: 1px 1px 1px 1px;
	padding: 1px 1px 1px 1px;
	padding-left: 5px;
	padding-right: 5px;
	border-style: dashed dashed dashed dashed;
	border-color: gray gray gray gray;
	background-color: white;
}
table.samplexxx td {
	border-width: 1px 1px 1px 1px;
	padding: 1px 1px 1px 1px;
	padding-left: 5px;
	padding-right: 5px;
	border-style: dashed dashed dashed dashed;
	border-color: gray gray gray gray;
	background-color: white;
}

</style>
<!-- For the menus: css and javascript  -->
<style type="text/css">

.menu_item_top, a.menu_item_top {
text-decoration:none;
text-align:left;
padding-top:7px;
font-size:10pt;
}
.menu_item_sub, a.menu_item_sub:link, a.menu_item_sub:hover, a.menu_item_sub:active {
text-decoration:none;
background-color:#ffffff;
border-style:solid;
border-color: #cccccc;
border-width:0px 1px 1px 1px;
color: #333333;
display: block;
padding: 5px 5px 5px 5px;
text-align:left;
font-size:8pt;
line-height:normal;
}
.hide{
display: none;
}
.show{
display: block;
}
</style>
<script language="JavaScript" type="text/JavaScript">
<!--
menu_status = new Array();

function showHide(theid){
    if (document.getElementById) {
    var switch_id = document.getElementById(theid);

        if(menu_status[theid] != 'show') {
           switch_id.className = 'show';
           menu_status[theid] = 'show';
        } else {
           switch_id.className = 'hide';
           menu_status[theid] = 'hide';
        }
    }
}

function show(theid){
    if (document.getElementById) {
    var switch_id = document.getElementById(theid);
           switch_id.className = 'show';
           menu_status[theid] = 'show';
    }
}

function hide(theid){
    if (document.getElementById) {
    var switch_id = document.getElementById(theid);
           switch_id.className = 'hide';
           menu_status[theid] = 'hide';
    }
}

function changebgcolor(theid){
    if (document.getElementById) {
    var switch_id = document.getElementById(theid);
           switch_id.className = 'hide';
           menu_status[theid] = 'hide';
    }
}

//-->
</script>
<!-- end css and javascript for the menus -->
<style type="text/css">
/* for the home page feature */
.home-feature-section {
 background-color:pink;
width: 715px;
margin: 15pt auto 0 auto; min-height:59px;
padding: 0 0 0 0;
border-color: #4572b3;
border-width: 0 0 1px 0;
border-style: solid;
}

.home-feature {/*
float: left;*/
position: relative;
top: 0;
left: 0;
width: 23%;
margin: 0 10pt 0 0;
padding: 0 0 0 0;
}

.home-footer {
float: left;
position: relative;
top: 0;
left: 0;
width: 100%;
font-size: 7pt;
margin: 10pt 0 0 0;
padding: 0 0 0 0;
}

.tinylink {
color:#cccccc;font-size:7pt;letter-spacing:0.1em;padding-right:5px;padding-top:15px;
}
a.tinylink:hover {text-decoration:underline;}

</style>
</head>
<body>
<cfset session.script="index.cfm">
<div name="doesnothing" style="padding:0px;background-color:white;width:755px;margin:0 auto;">
  <!-- this div actually does nothing and is not needed -->
  <div style="text-align:center;margin :0 auto;width:715px;border:#4572b3 solid; border-width: 1px 0 0 0; background-color:white;">
    <!-- The expanding menus -->
    <div align="center" name="topmenus" style="text-align:center;position:relative;top:0px;left:0px;width:715px;height:71px;border:none;border-width:1px 0 1px 0">
      <CFINCLUDE template="includes/geochron_menus.htm">
      <cfif session.loggedin eq "yes">
        <div name="loginout" class="menu_item_top" style="position:absolute;top:0px;left:600px;height:30px;width:115px;text-align:right"> <a href="logout.cfm" class="menu_item_top" style="font-size:7pt;letter-spacing:0.1em;padding-right:5px" >|&nbsp;logout</a>
          <div id="submenu5" class="tinylink"> Logged in as <cfoutput>#session.username#</cfoutput></div>
        </div>
        <!-- ended div loginout-->
      </cfif>
    </div>
    <!-- ended div topmenus -->
    </td>
    </tr>
    </table>
  </div>
  <!-- Begin Home Rectangle -->
  <div style="text-align:center;margin :0 auto;width:715px;height:290px;border:#4572b3 solid 1px; background-color:white;">
    <table width="715" cellspacing="0" cellpadding="0" border="0" style="border:none 1px cyan; padding: 0 0 0 0; margin:0 0 0 0;">
      <tr>
        <td width="14" height="290"><img src=spacer.gif height=2 width=2 hspace=6 vspace=144 /></td>
        <td align="right" valign="middle"><div name="spacer" style="height:55px">&nbsp;</div>
		<div name="logo" style="vertical-align:middle;height:150px"><img src="images/logo-320.jpg" width="320" height="138" hspace="0" vspace="0" border="0" /></div>
		<div name="caption_container" style="height:55px;width:100%;"><table cellspacing=0 celpadding=0 border=0 style="width:100%;height:100%"><tr><td align=right style="height:100%;width:100%;padding:0;margin:0;vertical-align:bottom;text-align:right;font-size:8pt;color: #000000;font-family:georgia,'times new roman',courier,serif"><div id="caption">Photo caption displayed here. Credit visible on mouseover.</div></td></tr></table></div>
          <!--<div style="margin-top:15px;font-size:10pt;color:black;font-style:italic">a project of EarthChem and EARTHTIME</div>--></td>
        <td width="14" height="290"><img src=spacer.gif height=2 width=1 hspace=7 vspace=144 /></td>
        <td align="left" width=350><!--<img src="images/testimage.gif" height=260 width=350 hspace=0 vspace=0 border=1 alt="caption for rotating photo 350x260 pixels"/> -->
          <script language="JavaScript">
<!--
/*
Random Image Link Script
By Website Abstraction (http://www.wsabstract.com)
and Java-scripts.net (http://www.java-scripts.net)
*/

function random_imglink(){
// arrays contain the image name, link to website source, links to image itself, caption
  var images=new Array()
  var links=new Array()
  var sources=new Array()
  var captions=new Array()
  var credits=new Array()
  var notes=new Array()
  
  for (i=0;i<24;i++) {
  images[i]="rutile_14.jpg"
  captions[i]="Photo caption displayed here."
  sources[i]="source"
  links[i]="link"
  credits[i]="Photo credit displayed here."
  notes[i]=""
  }

  //specify random images below. You can have as many as you wish
  images[0]="monazite_X.jpg"
  captions[0]="Monazite"
  
  images[1]="NanoESIFT.jpg"
  
  images[2]="rutile1.jpg" //rock349.jpg"
  captions[2]="Rutile"
  
  images[3]="SIMSChamber2.jpg"
  sources[3]="http://www.nibib.nih.gov/nibib/Image/SIMSchamber2.jpg"
  links[3]="http://www.nibib.nih.gov/Research/ResourceCenters/ListName/Castner"
  credits[3]="http://www.nibib.nih.gov/Research/ResourceCenters/ListName/Castner"
  captions[3]="The inside of a time-of-flight secondary ion mass spectrometer (ToF-SIMS) instrument."
  notes[3]=""
  
  images[4]="rutile_14.jpg"
  sources[4]="http://fk.strahlen.org/images/rutile-14.jpg"
  links[4]="http://fk.strahlen.org/rutile14.html"
  captions[4]="Rutile"
  // Sent email on 10/23/07 asking for permission to use this photo 

  images[5]="rutile1.jpg"
  sources[5]="http://www.mineralatlas.com/mineral%20photos/R/rutile1.jpg"
  links[5]="http://www.mineralatlas.com/mineral%20photos/R/rutile%20cp.htm"
  captions[5]="Rutile twin"
  credits[5]="Photo by Lou Perloff, courtesy of The Photo Atlas of Minerals" 
  notes[5]="The website credits Lou Perloff and this source http://www.nhm.org/pam/photo.htm - The Photo Atlas of Minerals, produced by the Los Angeles County Museum of Natural History, Gem and Mineral Council. This page contains this information: Lou Perloff: deceased. Lou Perloff gave blanket permission for any of his photographs to be used for any non-commercial purpose provided that proper credit is given. If you have any questions, contact Anthony R. Kampf: akampf@nhm.org. eej emailed akampf@nhm.org on 10/23/2007. I received permission by email on 10/23/2007 from Anthony R. Kampf, Ph.D., Curator, Mineral Sciences, Natural History Museum of Los Angeles County. Email is stored in folder named 'copyrights' on the Geochron website. "
  
   images[6]="zircon-X.jpg"
  captions[6]="Zircon"
  images[7]="equipment1.jpg"
  
  images[8]="97080058-30FN.jpg"
  sources[8]="http://picturethis.pnl.gov/im2/97080058-30FN0/97080058-30FN.jpg"
  links[8]="http://picturethis.pnl.gov/PictureT.nsf/All/3RF37L?opendocument"
  captions[8]="The 7-Tesla Fourier transform ion cyclotron resonance (FTICR) mass spectrometer in the William R. Wiley Environmental Molecular Sciences Laboratory High Field Mass Spectrometry Facility."
  credits[8]="photo credit"
  notes[8]="webmaster@pnl.gov webmaster eej emailed on 10/23/2007"
  
  // don't like this thin section much images[9]="equipment2.jpg"
  images[10]="equipment3.jpg"
  images[11]="equipment4.jpg"
  images[12]="equipment5.jpg"
  images[13]="equipment6.jpg"

  images[14]="Figura-tema5A.jpg" /*
  links[14]="http://www.csg.to.cnr.it/r35.html"
  sources[14]="http://www.csg.to.cnr.it/images/Figura-tema5A.jpg"
  captions[14]="U-Th-Pb dating of monazite"*/

  images[15]="monazites.jpg"
  sources[15]="http://chall.ifj.edu.pl/~dept2/nz2s/mjl/dating/monazites.jpg"
  links[15]="http://chall.ifj.edu.pl/~dept2/nz2s/mjl/dating/dating.html"
  captions[15]="Optical microscope image of a monomeral concentrate of monazite (a result of processing the uppermost carboniferous sandstone from Kwaczawa village, Gródek gorge)"
  credits[15]=captions[15]
  notes[15]="bednarczyk@b-site.ifj.edu.pl eej emailed 10/23/2007 to ask permission to use photo"

  images[16]="source.jpg"
  sources[16]="http://maduncan.myweb.uga.edu/source.JPG"
  links[16]="http://maduncan.myweb.uga.edu/"
  captions[16]="Source chamber"
  notes[16]="maduncan@uga.edu Dr. Michael A. Duncan - eej emailed him 10/23/2007 to ask permission to use photos"

  images[18]="monazite1.jpg"
  sources[18]="http://www.mineralatlas.com/mineral%20photos/M/monazite1.jpg"
  links[18]="http://www.mineralatlas.com/mineral%20photos/M/monazite%20cp.htm"
  captions[18]="Monazite on lepidolite"
  credits[18]="Photo by Lou Perloff, courtesy of The Photo Atlas of Minerals"
  notes[18]="See other notes for this photographer, on this page - he is deceased and gave blanket permission, plus eej got email permission"
  
  images[19]="monazite2.jpg"
  sources[19]="http://www.mineralatlas.com/mineral%20photos/M/monazite2.jpg"
  links[19]="http://www.mineralatlas.com/mineral%20photos/M/monazite%20cp.htm"
  captions[19]="Monazite on muscovite"
  credits[19]="Photo by Lou Perloff, courtesy of The Photo Atlas of Minerals"
  notes[19]="See other notes for this photographer, on this page - he is deceased and gave blanket permission, plus eej got email permission"

  images[20]="SAMs.h9.gif"
  sources[20]="http://www.chem.uci.edu/airuci/SAMs.h9.gif"
  links[20]="http://www.chem.uci.edu/airuci/SAMs.htm"
  captions[20]="Laser desorption mass spectrometry chamber"
  notes[20]=""
  credits[20]="http://www.chem.uci.edu/airuci/SAMs.htm"

  var ry=Math.floor(Math.random()*images.length)

  document.write('<a href='+'"'+links[ry]+'"'+'><img id="home_image"  src="images/'+images[ry]+'" title="' + credits[ry] + '"   width=350 height=260 border=0 ></a>')
  document.getElementById('caption').innerHTML= captions[ry];

}

  random_imglink()
//-->
</script>
        </td>
        <td width="15" height="290"></td>
      </tr>
    </table>
  </div>
  <!-- End Home Rectangle -->
  <!-- Begin Home News & Announcements  -->
  <div name="news" style="margin:0px auto 0 auto;padding:15px 0px 15px 0px;width:715px; border:#4572b3 solid; border-width:0 0 1px 0;background-color:white;">
    <table width="715" cellspacing="0" cellpadding="0" border="0" style="border:none 1px cyan; padding: 0 0 0 0; margin:0 0 0 0;">
      <tr valign="top">
        <td width="14" height="50"></td>
        <td width=320 align="right"><div style="margin-top:0px;font-size:8pt;color:black;font-style:italic">Geochron is a project of <a href="http://earthchem.org">EarthChem</a>&nbsp;and&nbsp;<a href="http://earth-time.org">EARTHTIME</a></div></td>
        <td width="14" ></td>
        <td align="center" width=170><b>Article Title</b><br />
          <a href="temp.cfm">link to article</a><br />
          short description&nbsp;.&nbsp;.&nbsp;.</td>
        <td width="10" ></td>
        <td align="center" width=170><b>Article Title</b><br />
          <a href="temp.cfm">link to article</a><br />
          short description&nbsp;.&nbsp;.&nbsp;.</td>
        <td width="15"  ></td>
      </tr>
    </table>
  </div>
  <!-- End Home News & Announcements -->
  <!-- Begin Home Footer -->
  <div class="home-footer"> &copy; The University of Kansas | <a href="contact.cfm">Contact</a></div>
  <!-- End Home Footer -->
  <br clear="left" />
</div>
<!-- TRY THE FADEIN PHOTO TECHNIQUE css/javascript -->
<!-- Experiments in fading images in/out using div layers on top of each other - but hide, for now, maybe apply this action to the main photo -->
<div id="digicam" style="visibility:hidden;position:absolute;top:500px;left:10px">
  <div id="d1" style="position:absolute;top:10px;left:10px;z-index:1"><img id="id1" src="images/1.gif"   /></div>
  <div id="d2" style="position:absolute;top:10px;left:10px;z-index:2"><img id="id2" src="images/2.gif"  /></div>
  <div id="d3" style="position:absolute;top:10px;left:10px;z-index:3"><img id="id3" src="images/3.gif"   /></div>
</div>
<script language="javascript">

function opacity(id, opacStart, opacEnd, millisec) {
    //speed for each frame
    var speed = Math.round(millisec / 100);
    var timer = 0;

    //determine the direction for the blending, if start and end are the same nothing happens
    if(opacStart > opacEnd) {
        for(i = opacStart; i >= opacEnd; i--) {
            setTimeout("changeOpac(" + i + ",'" + id + "')",(timer * speed));
            timer++;
        }
    } else if(opacStart < opacEnd) {
        for(i = opacStart; i <= opacEnd; i++)
            {
            setTimeout("changeOpac(" + i + ",'" + id + "')",(timer * speed));
            timer++;
        }
    }
}

//change the opacity for different browsers
function changeOpac(opacity, id) {
    var object = document.getElementById(id).style;
    object.opacity = (opacity / 100);
    object.MozOpacity = (opacity / 100);
    object.KhtmlOpacity = (opacity / 100);
    object.filter = "alpha(opacity=" + opacity + ")";
}

function shiftOpacity(id, millisec) {
    //if an element is invisible, make it visible, else make it ivisible
    if(document.getElementById(id).style.opacity == 0) {
        opacity(id, 0, 100, millisec);
    } else {
        opacity(id, 100, 0, millisec);
    }
}



changeOpac(0,'id3')
changeOpac(0,'id2')
changeOpac(0,'id1')
setTimeout("opacity('id1', 0, 100, 1000)",10)
setTimeout("opacity('id2', 0, 100, 1000)",1100)
setTimeout("opacity('id3', 0, 100, 1000)",2200)
</script>
<p />
<p />
<!-- works: <a href="javascript:shiftOpacity('home_image', 1000)">click here to fade the photo in or out</a> -->
<style type="text/css">
  .prop {
    height:50px;
    float:right;
    width:1px;
  }

  .clear {
    clear:both;
    height:1px;
    overflow:hidden;
  }
</style>
<!--- Try this workaround because IE does not respect the min-height property which I want to use in the div that contains the news articles. This works.
<div style="background-color:pink">
  <div style=
  'height:50px;
    float:right;
    width:1px;
	'></div>
  box
  <div style="    clear:both;
    height:1px;
    overflow:hidden;"></div>
</div>--->
</body>
</html>
