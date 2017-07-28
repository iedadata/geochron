<?PHP
/**
 * viewprobability.php
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

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Geochron - Probability Diagram</title>
<style type="text/css">
form{display:inline; }
a:hover{color:#003366; text-decoration:underline; font-weight: bold; }
a:link,a:visited{color:#003366; text-decoration:none; font-weight: bold; }
a:hover{color:#003366; text-decoration:underline; font-weight: bold; }
body,table,td,th,p,div,input {

	font-family: verdana,helvetica,arial;
	color:#003366;
	font-size: 14px;

}

p{margin: 10px;}

.fatlink {
	color: #003366;
	font-weight: bold;
	font-size: 14px;
}

.linky {
	color: #003366;
	font-weight: bold;
	font-size: 10px;
}

.headline {
	color: #003366;
	font-weight: bold;
	font-size: 28px;
}

.pagehead {
	color: #003366;
	font-weight: bold;
	font-size: 24px;
}

body {
	background-color: #FFFFFF;
	margin-left: 8px;
	margin-top: 15px;
	margin-right: 0px;
	margin-bottom: 0px;
}

.artist {
	padding:1px;
}

.album {
	padding:3px;
	margin-top:1px;
	margin-left:30px;
	border-style: solid;
	border-width: 1px;
	width: 700px;
	background-color: #111111;
}

.songs {
	padding: 5px;
	margin-left:30px;
	background-color: #111111;
}

table.box1 {
	border-width: 1px 1px 1px 1px;
	border-spacing: 0px;
	border-style: solid solid solid solid;
	border-color: #000000;
	border-collapse: collapse;
}
table.box1 th {
	background-color: #003366;
	color: #FFFFFF;
	border-width: 1px 1px 1px 1px;
	padding: 3px 3px 3px 3px;
	border-style: solid solid solid solid;
	border-color: #000000;
}
table.box1 td {
	border-width: 1px 1px 1px 1px;
	padding: 3px 3px 3px 3px;
	border-style: solid solid solid solid;
	border-color: #000000;
}


</style>
</head>

<body>

<embed src="probabilities/originals/<?=$_GET['sample_pkey']?>.svg" width="566" height="400" type="image/svg+xml" pluginspage="http://www.adobe.com/svg/viewer/install/" /> 




</body>
</html>