	<html><head><title>Geochron REST Server Documentation</title>
	<style type="text/css">
		body    { font-family: arial; color: #000000; background-color: #ffffff; margin: 0px 0px 0px 0px; }
		p       { font-family: arial; color: #000000; margin-top: 0px; margin-bottom: 12px; }
		pre { background-color: silver; padding: 5px; font-family: Courier New; font-size: x-small; color: #000000;}
		ul      { margin-top: 10px; margin-left: 20px; }
		li      { list-style-type: none; margin-top: 10px; color: #000000; }
		.content{
		margin-left: 0px; padding-bottom: 2em; }
		.nav {
		padding-top: 10px; padding-bottom: 10px; padding-left: 15px; font-size: .70em;
		margin-top: 10px; margin-left: 0px; color: #000000;
		background-color: #ff6600; width: 232px; margin-left: 5px; margin-top: 5px; }
		.title {
		font-family: arial; font-size: 26px; color: #ffffff;
		background-color: #333333; width: 105%; margin-left: 0px;
		padding-top: 10px; padding-bottom: 10px; padding-left: 15px;}
		.hidden {
		position: absolute; visibility: hidden; z-index: 200; left: 257px; top: 56px;
		font-family: arial; overflow: hidden; width: 600;
		padding: 20px; font-size: 11px; background-color: #999999;
		layer-background-color:#FFFFFF; }
		a,a:active  { color: #ff6600; font-weight: bold; }
		a:visited   { color: #ff6600; font-weight: bold; }
		a:hover     { color: #ff6600; font-weight: bold; }
		.mydoc{
		width:600px;
		margin-left:20px;
		margin-top:20px;
		}
		.xmldiv{
		padding:5px;
		margin-left:20px;
		margin-top:20px;
		font-size:.8em;
		background-color:#EEEEEE;
		border:1px #CCCCCC dashed;
		}
		.itemtitle{
		font-size:1em;
		font-weight:bold;
		margin-left:10px;
		margin-bottom:15px;
		}
		.itemdesc{
		margin-left:10px;
		padding:5px;
		background-color:#EEEEEE;
		border:1px #CCCCCC dashed;
		font-size:.9em;
		font-weight:normal;
		}
	</style>

	</head>
	<body>
	<div class=content>
		
		<div class=title>Geochron REST Server Documentation</div>

		<div class="mydoc">
			
			The Geochron REST Search Service accepts GET string variables which determine search criteria.<br><br>

			<br>
			
			The target URL for the Geochron REST Search Service is:<br><br>
			
			<div style="padding-left:20px;color:#ff6600;">
			http://www.geochron.org/restsearchservice<br>
			</div>
			
			<br>
			
			<div style="font-weight:bold;font-size:1.5em;">Usage of GET Variables:</div><hr>
			
			<br>
			




			<div class="itemtitle">polygon
				<div class="itemdesc">
					This field allows a polygon to be provided in order to constrain results geographically.
					The polygon must be provided in a closed format of comma-delimited coordinate pairs.<br>
					Example:<br>
					-109.35 36.7875,-105.525 40.725,-104.5125 37.0125,-107.1 35.4375,-109.35 36.7875
				</div>
			</div>

			<div class="itemtitle">north
				<div class="itemdesc">
					Northern bound of a geographic envelope used to constrain results geographically.<br>
					If a northern bound is provided, an east, south, and west value must also be provided.
				</div>
			</div>

			<div class="itemtitle">east
				<div class="itemdesc">
					Eastern bound of a geographic envelope used to constrain results geographically.<br>
					If an eastern bound is provided, a south, west, and north value must also be provided.
				</div>
			</div>

			<div class="itemtitle">south
				<div class="itemdesc">
					Southern bound of a geographic envelope used to constrain results geographically.<br>
					If a southern bound is provided, a west, north, and east value must also be provided.
				</div>
			</div>

			<div class="itemtitle">west
				<div class="itemdesc">
					Western bound of a geographic envelope used to constrain results geographically.<br>
					If a western bound is provided, a north, east, and south value must also be provided.
				</div>
			</div>

			<div class="itemtitle">minage
				<div class="itemdesc">
					Minimum age of the sample.
				</div>
			</div>

			<div class="itemtitle">maxage
				<div class="itemdesc">
					Maximum age of the sample.
				</div>
			</div>

			<div class="itemtitle">age
				<div class="itemdesc">
					Age of the sample.
				</div>
			</div>

			<div class="itemtitle">ageoffset
				<div class="itemdesc">
					When age is provided, the is the age offset. i.e. 123 +- .5 where .5 is the age offset.
				</div>
			</div>

			<div class="itemtitle">analysismethod
				<div class="itemdesc">
					Method by which sample was analyzed. A list of valid methods can be found at:<br>
					<a href="/restitems/analysismethods/xml" target="blank">http://www.geochron.org/restitems/analysismethods/xml</a><br>
					<a href="/restitems/analysismethods/jsonp" target="blank">http://www.geochron.org/restitems/analysismethods/jsonp</a><br>
					<a href="/restitems/analysismethods/jsonp/myjsonpfunctionname" target="blank">http://www.geochron.org/restitems/analysismethods/jsonp/myjsonpfunctionname</a>
				</div>
			</div>

			<div class="itemtitle">materialanalyzed
				<div class="itemdesc">
					Material analyzed within sample. A list of valid materials can be found at:<br>
					<a href="/restitems/materials/xml" target="blank">http://www.geochron.org/restitems/materials/xml</a><br>
					<a href="/restitems/materials/jsonp" target="blank">http://www.geochron.org/restitems/materials/jsonp</a><br>
					<a href="/restitems/materials/jsonp/myjsonpfunctionname" target="blank">http://www.geochron.org/restitems/materials/jsonp/myjsonpfunctionname</a>
				</div>
			</div>

			<div class="itemtitle">rocktype
				<div class="itemdesc">
					Rock type. A list of valid rock types can be found at:<br>
					<a href="/restitems/rocktypes/xml" target="blank">http://www.geochron.org/restitems/rocktypes/xml</a><br>
					<a href="/restitems/rocktypes/jsonp" target="blank">http://www.geochron.org/restitems/rocktypes/jsonp</a><br>
					<a href="/restitems/rocktypes/jsonp/myjsonpfunctionname" target="blank">http://www.geochron.org/restitems/rocktypes/jsonp/myjsonpfunctionname</a>
				</div>
			</div>

			<div class="itemtitle">laboratory
				<div class="itemdesc">
					Laboratory where sample was analyzed. A list of valid lab names can be found at:<br>
					<a href="/restitems/labnames/xml" target="blank">http://www.geochron.org/restitems/labnames/xml</a><br>
					<a href="/restitems/labnames/jsonp" target="blank">http://www.geochron.org/restitems/labnames/jsonp</a><br>
					<a href="/restitems/labnames/jsonp/myjsonpfunctionname" target="blank">http://www.geochron.org/restitems/labnames/jsonp/myjsonpfunctionname</a>
				</div>
			</div>

			<div class="itemtitle">purpose
				<div class="itemdesc">
					Purpose of sample analysis. A list of valid purposes can be found at:<br>
					<a href="/restitems/purposes/xml" target="blank">http://www.geochron.org/restitems/purposes/xml</a><br>
					<a href="/restitems/purposes/jsonp" target="blank">http://www.geochron.org/restitems/purposes/jsonp</a><br>
					<a href="/restitems/purposes/jsonp/myjsonpfunctionname" target="blank">http://www.geochron.org/restitems/purposes/jsonp/myjsonpfunctionname</a>
				</div>
			</div>

			<div class="itemtitle">uniqueid
				<div class="itemdesc">
					Unique identifier of sample.
				</div>
			</div>

			<div class="itemtitle">sampleid
				<div class="itemdesc">
					Sample ID assigned by analyst.
				</div>
			</div>

			<div class="itemtitle">collector
				<div class="itemdesc">
					Name of person who collected sample.
				</div>
			</div>

			<div class="itemtitle">sampledescription
				<div class="itemdesc">
					Description of sample provided by analyst.
				</div>
			</div>

			<div class="itemtitle">collectionmethod
				<div class="itemdesc">
					Method by which sample was collected.
				</div>
			</div>

			<div class="itemtitle">samplecomment
				<div class="itemdesc">
					Comment provided by analyst.
				</div>
			</div>

			<div class="itemtitle">primarylocationname
				<div class="itemdesc">
					Name of location where sample was collected.
				</div>
			</div>

			<div class="itemtitle">primarylocationtype
				<div class="itemdesc">
					Type of location.
				</div>
			</div>

			<div class="itemtitle">locationdescription
				<div class="itemdesc">
					Description of location where sample was collected.
				</div>
			</div>

			<div class="itemtitle">locality
				<div class="itemdesc">
					Locality where sample was collected.
				</div>
			</div>

			<div class="itemtitle">localitydescription
				<div class="itemdesc">
					Description of Locality.
				</div>
			</div>

			<div class="itemtitle">country
				<div class="itemdesc">
					Country where sample was collected.
				</div>
			</div>

			<div class="itemtitle">province
				<div class="itemdesc">
					Province where sample was collected.
				</div>
			</div>


















			<div class="itemtitle">searchtype
				<div class="itemdesc">
					Desired type of search query. Possible values are "count", "rowdata", and "distinctitems"<br><br>
					count: returns count of results from query.<br>
					rowdata: returns table of results from query in the format of "outputtype" below.
				</div>
			</div>

			<div class="itemtitle">outputtype
				<div class="itemdesc">
					Desired output type from query. Possible values are "html", "csv", "xml", "json", "jsonp" and "staticmap".<br><br>
					html: returns HTML table of results from query.<br>
					csv: returns CSV table of results from query.<br>
					xml: returns XML document of results from query.<br>
					json: returns JSON document of results from query.<br>
					jsonp: returns JSON string wrapped in a Javascript function call defined by "jsonpfunction" below:<br>
					staticmap: returns map image of results from query.<br><br>
					Please note: When a searchtype of "count" is set, "html", "csv", and "staticmap" will return a plaintext integer count of samples from the query.
				</div>
			</div>

			<div class="itemtitle">jsonfunction
				<div class="itemdesc">
					Desired Javascript function to be wrapped around JSON in the event that "outputtype" is set to "jsonp"
				</div>
			</div>

			<div class="itemtitle">outputrows:start/end
				<div class="itemdesc">
					These values determine which rows of query will be displayed. The first row is always 0 and the
					last row is always the count value minus 1 (n-1). <br><br>
					Important: A maximum of 50 rows are allowed to be displayed at a time. Any start/end values
					that exceed a difference of 50 will result in the start value and start value + 50 values being
					displayed. If start/end values are not provided, rows 0-49 will be displayed.
				</div>
			</div>

			<div class="itemtitle">showcolumnnames
				<div class="itemdesc">
					Control display of column names in CSV and HTML output modes. Possible values are yes and no. Default value is no.
				</div>
			</div>


			<div class="itemtitle">Example 1:
				<div class="itemdesc">
					Show html output for samples matching lab "Arizona Geochron". Show column Names in HTML.<br><br>
					<div style="font-weight:bold;">HTTP Link:</div><br>
						<div style="font-size:.8em;">
						<a href="/restsearchservice?laboratory=Arizona%20Geochron&searchtype=rowdata&outputtype=html&showcolumnnames=yes" target="_blank">http://www.geochron.org/restsearchservice?laboratory=Arizona%20Geochron&searchtype=rowdata&outputtype=html&showcolumnnames=yes</a>
						</div>
				</div>
			</div>

			<div class="itemtitle">Example 2:
				<div class="itemdesc">
					Show xml output for samples located in southern California.<br><br>
					<div style="font-weight:bold;">HTTP Link:</div><br>
						<div style="font-size:.8em;">
						<a href="/restsearchservice?polygon=-122.259375 37.105319023132,-119.30625 38.483444023132,-114.1875 34.292819023132,-117.1125 32.492819023132,-122.259375 37.105319023132&searchtype=rowdata&outputtype=xml" target="_blank">http://www.geochron.org/restsearchservice?polygon=-122.259375 37.105319023132,-119.30625 38.483444023132,-114.1875 34.292819023132,-117.1125 32.492819023132,-122.259375 37.105319023132&searchtype=rowdata&outputtype=xml</a>
						</div>
				</div>
			</div>


			<div class="itemtitle">Example 3:
				<div class="itemdesc">
					Show static map of samples between 0 and .5 Ma in the western United States.<br><br>
					<div style="font-weight:bold;">HTTP Link:</div><br>
						<div style="font-size:.8em;">
							<a href="/restsearchservice?north=49&east=-100&south=23&west=-124&minage=0&maxage=.5&ageunit=ma&outputtype=staticmap" target="_blank">http://www.geochron.org/restsearchservice?north=49&east=-100&south=23&west=-124&minage=0&maxage=.5&ageunit=ma&outputtype=staticmap</a>
						</div>
				</div>
			</div>

			<div class="itemtitle">Example 4:
				<div class="itemdesc">
					Retrieve a JSONP document of samples from Colorado. The Javascript function is "myJSFunction"<br><br>
					<div style="font-weight:bold;">HTTP Link:</div><br>
						<div style="font-size:.8em;">
							<a href="/restsearchservice.php?outputtype=jsonp&searchtype=rowdata&jsonpfunction=myJSFunction&polygon=-109.040625 40.930319023132,-102.09375 40.902194023132,-102.0375 36.992819023132,-109.040625 36.992819023132,-109.040625 40.930319023132" target="_blank">http://www.geochron.org/restsearchservice.php?outputtype=jsonp&searchtype=rowdata&jsonpfunction=myJSFunction&polygon=-109.040625 40.930319023132,-102.09375 40.902194023132,-102.0375 36.992819023132,-109.040625 36.992819023132,-109.040625 40.930319023132</a>
						</div>
				</div>
			</div>


		</div>
	</div></body></html>