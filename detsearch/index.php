<?

session_start();
$userlevel = $_SESSION['userlevel'];

?>
<!DOCTYPE html>
<html>
  <head>
	<meta charset="utf-8"/>
	
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

	<title>Geochron Detrital Search</title>
	<link href='https://fonts.googleapis.com/css?family=Raleway:400,300,200,700&amp;subset=latin,latin-ext' rel='stylesheet' type='text/css' />
	<link rel="stylesheet" href="includes/ol4/ol.css" type="text/css">
	<link rel="stylesheet" href="includes/layerswitcher/layerswitcher.css" type="text/css">
	<link rel="stylesheet" href="includes/jquery-sidebar/jquery.sidebar.css" type="text/css">
	<link rel="stylesheet" href="includes/jquery-ui/jquery-ui.css" type="text/css">
	<link rel="stylesheet" href="includes/featherlight/featherlight.css" type="text/css">
	<link rel="stylesheet" href="includes/ionic/css/ionic.css" type="text/css">
	<link rel="stylesheet" href="includes/map_search.css" type="text/css">

	<!--<link rel="stylesheet" href="/includes/bootstrap/css/bootstrap.css" type="text/css">-->

	<!-- The line below is only needed for old environments like Internet Explorer and Android 4.x -->
	<script src="https://cdn.polyfill.io/v2/polyfill.min.js?features=requestAnimationFrame,Element.prototype.classList,URL"></script>
	<script src="includes/ol4/ol.js"></script>
	<script src="includes/layerswitcher/layerswitcher.js"></script>

	<!-- Map Search-Specific Files-->
	<script src="includes/map_search_functions.js"></script>
	<script src="includes/tab_builders.js"></script>

	<script src="includes/underscore/underscore-min.js"></script>
	<!---<script src="includes/jquery/jquery-3.1.1.min.js"></script>--->
	<script src="includes/jquery/jquery.min.js"></script>
	<!--<script src="includes/bootstrap/js/bootstrap.js"></script>-->

	<script src="includes/jquery-sidebar/jquery.sidebar.min.js"></script>
	<script src="includes/jquery-ui/jquery-ui.js"></script>

	<script src="includes/featherlight/featherlight.js"></script>

	<script src="includes/turf/turf.min.js"></script>
	<!---<script src="/includes/jquery/jquery.json.min.js"></script>--->


  </head>
  <body>

	<div id="datawaiting">
		<table>
			<tr>
				<td><img src="includes/images/loading.gif"></td>
				<td valign="middle"><div style="padding-bottom:5px;"> Loading Data...</div></td>
			</tr>
		</table>
	</div>

	<div id="map_query"><button class="map_query_button tooltip" onClick="alert('hi');"><span class="tooltiptext">Set Search Criteria</span></div>

    <div id="map" class="map"></div>
    <div id="toptext">
		<div align="center"><img src="/images/logo-150.jpg" border="0"></div>
		<div align="center" style="font-size:28px;font-weight:bold;color:#666666;">Detrital Search Interface</div>
		<div id="docs">
			<div style="display:none">
			Info about the detrital search interface.</div>
		</div>
    </div>



	<div class="sidebar right">

		<div id="volcanowrapper">

		</div>
	</div>

    <script>
    	//initialize JQuery sidebar
		var sidebarState = "closed";
		$(".sidebar.right").sidebar({side: "right"});

		$(".sidebar.right").on("sidebar:opened", function () {
		   sidebarState = "open";
		});

		$(".sidebar.right").on("sidebar:closed", function () {
		   sidebarState = "closed";
		});

    </script>
    <script src="includes/map_interface.js"></script>
    <?
    if($_GET['c']!=""){
    	$c = $_GET['c'];
    ?>
    <script>
    	zoomToCenterAndExtent('<?=$c?>');
    </script>
    <?
    }
    ?>
  </body>
</html>