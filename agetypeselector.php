<?PHP
/**
 * agetypeselector.php
 *
 * longdesc
 *
 * LICENSE: This source file is subject to version 4.0 of the Creative Commons
 * license that is available through the world-wide-web at the following URI:
 * https://creativecommons.org/licenses/by/4.0/
 *
 * @category   Geochronology
 * @package    Geochron Portal
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  IEDA (http://www.iedadata.org/)
 * @license    https://creativecommons.org/licenses/by/4.0/  Creative Commons License 4.0
 * @version    GitHub: $
 * @link       http://www.geochron.org
 * @see        Geochron, Geochronology
 */

session_start();

//print_r($_SESSION);



include("db.php");

// **************** get username stuff here for the search ***************
if($_SESSION['username']!=""){
	$username=$_SESSION['username'];
	$userrow=$db->get_row("select * from users where username='$username'");
	$group=$userrow->usergroup;
	$userpkey=$userrow->users_pkey;
}elseif($_POST['username']!="" & $_POST['password']!=""){
	$username=$_POST['username'];
	$password=$_POST['password'];
	$userrow=$db->get_row("select * from users where username='$username' and password='$password'");
	$group=$userrow->usergroup;
	$userpkey=$userrow->users_pkey;
}


if($group==0 or $group==""){
	$group=99999;
}

if($userpkey==""){
	$userpkey=99999;
}

//*************************************************************************



if($_SESSION['userpkey']!=""){
	$userpkey=$_SESSION['userpkey'];
}else{
	$userpkey="99999";
}

$project=$_GET['project'];

if($project=="redux"){$prefix="U-Pb Tims";}
if($project=="helios"){$prefix="(U-Th)/He";}
if($project=="arar;ararxls"){$prefix="ArAr";}
if($project=="squid;zips"){$prefix="U-Pb Ion Microprobe";}
if($project=="fissiontrack"){$prefix="Fission Track";}


$projectarray=explode(";",$project);

$projectlist="";
$projectlistdelim="";

foreach($projectarray as $indproj){
	$projectlist.=$projectlistdelim."'$indproj'";
	$projectlistdelim=",";
}

//$agetypes=$db->get_results("select typename from agetypes where project='$project'");

$agetypes=$db->get_results("select distinct(age_name) as typename from 
							sample_age
							left join sample on sample_age.sample_pkey = sample.sample_pkey
							left join users on sample.userpkey = users.users_pkey
							where age_name is not null
							and age_name != ''
							and sample.ecproject in ($projectlist)
							and (sample.publ=1 or users.usergroup=$group or users.users_pkey=$userpkey)
							--and sample.upstream=FALSE
							order by age_name");

/*
$agenames=$db->get_results("select distinct(age_name) from 
							sample_age
							left join sample on sample_age.sample_pkey = sample.sample_pkey
							left join users on sample.userpkey = users.users_pkey
							where age_name is not null
							and age_name != ''
							and (sample.publ=1 or users.usergroup=$group or users.users_pkey=$userpkey)
							and sample.upstream=FALSE
							order by age_name");
*/




?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Geochron</title>
<link rel='stylesheet' type='text/css' media='all' href='/geochron.css' />
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" /> 
<style type="text/css">
/*NYTimes top tabs pale blue: f0f4f5
NYTimes top tabs border: 999999*/
<!-- the tufte blue 4572b3 from plots, and a palest version for rollovers d7e6fc -->
body {
background-color: #ffffff;
margin: 15px 15px 15px 15px;
}
body, td {
font-family: verdana,arial,sans-serif;
font-size: 8pt;
color: #636363;
background-color: #ffffff;
margin: 15px 15px 15px 15px;
}
/* pagetitle is used in jason's xml page viewfile.php */
h1, .pagetitle {
font-size: 10pt;
font-weight: 600; /* note: 400 is normal, 700 is bold */
color: #666666;
margin: 0px 0px 10px 0px;
}
.pagetitle {font-size:11pt; color:#636363; margin: 0px 0px 5px 0px;}
/* headline & fatlink are used in jason's xml page viewfile.php */
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
a.button,a.button:link,a.button:visited {
  border-style: solid;
  border-width: 1px 1px 1px 1px;
  border-color : #4572b3;
  padding: 7px 7px 7px 7px;
  color: #999999;
  font-family: verdana,arial,sans-serif;
  font-size: 12px;
  background-color:#f0f4f5;
  text-decoration:none;
}
a.button:hover,a.button:active {color:#333333;border-color:#333333;text-decoration:none;}
#mapDiv {
	width: 800px;
	height: 400px;
	border: 1px solid black;
}
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
	text-align:left;
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
/* styles used by viewfile.php - adapted from ones in jason's upbgeochron.css file - some redefined above */
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


#mapDiv {
	width: 800px;
	height: 400px;
	border: 1px solid black;
}

#mapDivdyn {
	width: 400px;
	height: 400px;
	border: 1px solid black;
}


</style>
<!-- For the menus: css and javascript  -->
<style type="text/css">


.hide{
display: none;
}
.show{
display: block;
}

.tinylink {
color:#cccccc;font-size:7pt;letter-spacing:0.1em;padding-right:5px;padding-top:15px;
}
a.tinylink:hover {text-decoration:underline;}
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

function showDebug() {
var thsObj =  document.getElementById("debug");
		if(thsObj.style.display == 'block') {
			thsObj.style.display = 'none';
		}else{
			thsObj.style.display = 'block';
		}
}

function passlist(){

	var delim='';
	var htmldelim='';
	
	var agetypelist='';
	var htmlagetypelist='';

	//first, get parent agemethod
	var currentagelist=parent.document.getElementById("agemethod").value;
	//alert(currentagelist);
	
	//next, split list into array
	agearray=currentagelist.split(",");
	
	//now, loop over array and remove any entries that begin with prefix
	
	for (p = 0; p<=agearray.length - 1; p++) {
		if(agearray[p].indexOf("<?=$prefix?>") != -1){
		}else{
			//add it to agetypelist
			agetypelist=agetypelist+delim+agearray[p];
			delim=',';
			htmlagetypelist=htmlagetypelist+htmldelim+agearray[p];
			htmldelim='<br>';
		}
	}
	
	//alert('cleaned up:'+agetypelist);
	
	var agetypesel = document.getElementById('agetype');

	
	if(agetypelist==''){
		delim='';
		htmldelim='';
	}

	for (p = 0; p<=agetypesel.length - 1; p++) {
		if (agetypesel.options[p].selected) {
			agetypelist=agetypelist+delim+agetypesel.options[p].value;
			delim=',';
			htmlagetypelist=htmlagetypelist+htmldelim+agetypesel.options[p].value;
			htmldelim='<br>';
		}
	}
	
	//alert(agetypelist);
	parent.document.getElementById("agemethod").value=agetypelist;
	parent.document.getElementById("agemethoddisplay").innerHTML=htmlagetypelist;
	
	parent.domethods();

	
}

//-->
</script>
<!-- end css and javascript for the menus -->
</head>

<body>
<?
if(count($agetypes)>0){
?>

				<select name="agetype[]" size="12" style="width:300px;" id="agetype" multiple>
					<?
					foreach($agetypes as $agetype){
					?>
					<option value="<?=$prefix?>: <?=$agetype->typename?>"><?=$agetype->typename?>
					<?
					}
					?>
				</select>
				
				<br><br>
				
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="button" value="Update" onClick="javascript:passlist()">
<?
}else{
?>

<div align="center">Sorry, no Age Types exist for <?=$prefix?>.</div>

<?
}
?>


</body>
</html>