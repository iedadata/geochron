# Geochron
Geochron is a database system designed to capture complete data and metadata to document geochronologic age estimation, allowing future reuse, recalculation, and integration with other data.

The goal is to capture this information at its source--the data reduction programs used in labs to analyze and reduce data coming directly from instrumentation. The human operators of the equipment should not need to re-enter data that are acquired or generated by computers in the first place.

Data can be ingested from a variety of data reduction programs that are used widely in the various geochronology and thermochronology communities. These programs have been adapted to seamlessly upload standardized data to the Geochron database with minimal input from the operator.

# Requirements
* [PHP](http://php.net/) v5.0+
* [MapServer/phpMapScript](http://mapserver.org) v6.2.1+
* [PostgreSQL database](https://www.postgresql.org/) v8.0+

# Installation
- After installing PHP, MapServer/MapScript, and PostgreSQL, clone this repo into the root directory and change config in /includes/config.inc.php

# Documentation

## REST
Endpoints for REST API.

## fonts
Fonts for map interfaces.

## geopass
Helper class for geopass login.

## includes
Headers, Footers and Configurations.

## js
Client-side scripts.

## maps
Map files for mapserver interfaces.

## rest_search_documentation
Documentation for REST search interface.

## schemas
Schemas for various data types.

## service_tests
Test scripts for services.

## shapefiles
Shapefiles for mapserver interfaces.

## templates
Templates for uploads.

## transforms
Transforms for XML documents.

## adddataset.php
Add new dataset to user profile.

## addgroup.php
Add new group to user profile.

## agetypeselector.php
Age type selector for project.

## ajaxpoly.php
Display map with polygon for Ajax interface.

## aliquotxml.php
Display XML for given aliquot.

## ararupload.php
Upload ArAr Data.

## buildquery.php
Helper file for building queries.

## checkigsn.php
Helper file for testing existence of IGSN.

## clearage.php
Clear age from search query.

## clearlaboratory.php
Clear lab from search query.

## clearlocation.php
Clear location from search query.

## clearmaterial.php
Clear material from search query.

## clearmethod.php
Clear method from search query.

## clearmineral.php
Clear mineral from search query.

## clearrocktype.php
Clear rock type from search query.

## clearsampleinformation.php
Clear sample metadata from search query.

## concdynmap.php
Dynamic concordia map.

## credentials_service.php
Endpoint for validating credentials.

## cronusupload.php
Upload CRONUS data.

## datasetdynmap.php
Dynamic map interface for dataset.

## datasetinteractivemap.php
Interactive map for datasets.

## datasetkml.php
Export KML for dataset.

## dategraph.php
Create graph of sample count over time.

## db.php
Helper class for database configuration and abstraction.

## deletedataset.php
Delete given dataset.

## deletedatasetuser.php
Delete shared user from given dataset.

## deletegroup.php
Delete given group.

## deletegroupsample.php
Delete sample from group.

## deletesample.php
Delete sample.

## deletesamples.php
Delete multiple samples.

## deleteuser.php
Delete user from group.

## detritaldynmap.php
Dynamic detrital interface map.

## detritalexcel.php
Download detrital search results in Excel file.

## detritalresults.php
Display detrital search results in HTML table.

## detritalsearch.php
Detrital search interface.

## downloadfile.php
Download file for given id;

## dynmap.php
Dynamic map interface.

## dynmapquery.php
Query helper for dynamic map interface.

## envelopeupstreamcount.php
Return count of detrital samples for given envelope.

## fetchigsn.php
Gather IGSN details from SESAR using SESAR's REST API.

## fetchschema.php
Fetch schema updates from EarthTime.

## fetchschemas.php
Fetch schema updates from EarthTime.

## fissiontrack.php
Upload Fission Track data.

## fontset.txt
Font set file for map interfaces.

## generalexcel.php
Download excel file with general search results.

## geochrondynmap.php
Dynamic map tiles for interactive map.

## geochroninteractivemap.php
Interactive Geochron Search map.

## geochronsearch.php
General search interface.

## geopasscallback.php
Callback for geopass login.

## geopasslogin.php
Login with geopass.

## getagetypes.php
Get age types for given project(s).

## getdata.php
Display HTML content for given sample.

## getfractions.php
Return XML representation of fractions for given sample.

## getlevel2.php
Helper for Ajax Rock Types.

## getlevel3.php
Helper for Ajax Rock Types.

## getlevel4.php
Helper for Ajax Rock Types.

## getoddlevels.php
Helper for Ajax Rock Types.

## getxml.php
Get XML file for given sample.

## githubcallback.php
Callback for Github login.

## githublogin.php
Github login.

## googlelogin.php
Google login.

## googlelogout.php
Google logout.

## googleoauth2callback.php
Helper class for Google login.

## googleoauthconfig.php
Config for Google Oauth.

## groupdynmap.php
Dynamic map for group datasets.

## groupinteractivemap.php
Interactive map interface for group datasets.

## groupmapquery.php
Helper for Interactive group map query.

## igsnexists.php
Helper file for existence of IGSN.

## imageservice.php
Service for uploading images.

## index.php
Landing page for main site.

## indsampledynmap.php
Dynamic map for single sample.

## indsampleinteractivemap.php
Interactive map for single sample.

## invitedatasetusers.php
Invite users to collaborate on dataset.

## inviteusers.php
Invite users to join group.

## jsmarkpublished.php
Helper for marking dataset as published.

## listrestitems.php
Ajax helper to provide JSONP lists.

## login.php
Login to Geochron system.

## logincheck.php
Helper file for checking whether user is logged in.

## logout.php
Logout of Geochron system.

## makedatasetfile.php
Helper file for creating .zip file suitable for publishing at ECL.

## managedata.php
Main data management page.

## managedatasetsamples.php
Manages samples in dataset.

## managegroupsamples.php
Manage samples in group.

## mapdynmap.php
Dynamic map.

## mapquery.php
Helper for dynamic map queries.

## markpublished.php
Helper script for marking datasets published.

## massspecimageservice.php
Service for uploading mass spec images.

## modularloader.php
Modular loading routine for all et-redux files.

## orcidcallback.php
Call back for ORCiD login.

## orcidlogin.php
Login with ORCiD.

## paginator.php
Helper class for creating search result pagination.

## polygoncount.php
REST service for returning counts for IEDA data browser.

## polygonsearch.php
Landing page for IEDA data browser results.

## popupmap.php
Map for individual sample details.

## redux_search_service.php
REST endpoint for search service for ET-Redux.

## redux_service.php
Service for uploading ET-Redux data.

## restsearchservice.php
REST endpoint for search service.

## results.php
Landing page for query results from general search interface.

## samplexml.php
Return XML file for given sample.

## search.php
Choose search interface.

## searchupdate.php
Update search query parameters.

## setage.php
Set age query parameter(s).

## setlaboratory.php
Set laboratory query parameter(s).

## setlocation.php
Set location query parameter(s).

## setmaterial.php
Set material query parameter(s).

## setmethod.php
Set method query parameter(s).

## setmineral.php
Set mineral query parameter(s).

## setrocktype.php
Set rock type query parameter(s).

## setsampleinformation.php
Set sample information query parameter(s).

## showdataset.php
Display html table of dataset.

## squid.php
Upload SQUID/SQUID2 data.

## stats.php
Display upload/download statistics.

## submitdata.php
Details for sumbission of data to Geochron.

## upload_stats_quarter.php
Display upload quarterly statistics.

## uploadfile.php
Upload ET-Redux file to the Geochron system.

## uploadimage.php
Upload image for data submission.

## uploadstats.php
Display upload stats.

## userdynmap.php
Dynamic map of user samples.

## userdynmapquery.php
Helper for user map query.

## userinteractivemap.php
Interactive map of user samples.

## usermapquery.php
Helper for user map query.

## uthhexls.php
XLS output for UTh/He data.

## validatedset.php
Confirm user for dataset sharing.

## validategrp.php
Confirm user for group sharing.

## viewconcordia.php
View concordia file.

## viewfile.php
View XSLT transformed file for given sample.

## viewgroup.php
View group details.

## viewid.php
View details for IGSN.

## viewprobability.php
View probability diagram.

## viewsesar.php
View SESAR detials for given IGSN.

## zips.php
Upload ZIPS data.






