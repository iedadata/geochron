<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                >



  <xsl:output method="html"/>


<xsl:template match="sample">
Details for IGSN: <xsl:value-of select="igsn"/><br/>
<table>
<tr><td>IGSN:</td><td><xsl:value-of select="igsn"/></td></tr>
<tr><td>Sample Name:</td><td><xsl:value-of select="name"/></td></tr>
<tr><td>Sample Type:</td><td><xsl:value-of select="sample_type"/></td></tr>
<tr><td>Parent Igsn:</td><td><xsl:value-of select="parent_igsn"/></td></tr>
<tr><td>Material:</td><td><xsl:value-of select="material"/></td></tr>
<tr><td>Classification:</td><td><xsl:value-of select="classification"/></td></tr>
<tr><td>Field Name:</td><td><xsl:value-of select="field_name"/></td></tr>
<tr><td>Description:</td><td><xsl:value-of select="description"/></td></tr>
<tr><td>Age Min:</td><td><xsl:value-of select="age_min"/></td></tr>
<tr><td>Age Max:</td><td><xsl:value-of select="age_max"/></td></tr>
<tr><td>Collection Method:</td><td><xsl:value-of select="collection_method"/></td></tr>
<tr><td>Collection Method Description:</td><td><xsl:value-of select="collection_method_description"/></td></tr>
<tr><td>Size:</td><td><xsl:value-of select="size"/></td></tr>
<tr><td>Geological Age:</td><td><xsl:value-of select="geological_age"/></td></tr>
<tr><td>Geological Unit:</td><td><xsl:value-of select="geological_unit"/></td></tr>
<tr><td>Comment:</td><td><xsl:value-of select="sample_comment"/></td></tr>
<tr><td>Latitude:</td><td><xsl:value-of select="latitude"/></td></tr>
<tr><td>Longitude:</td><td><xsl:value-of select="longitude"/></td></tr>
<tr><td>Elevation:</td><td><xsl:value-of select="elevation"/></td></tr>
<tr><td>Primary Location Type:</td><td><xsl:value-of select="primary_location_type"/></td></tr>
<tr><td>Primary Location Name:</td><td><xsl:value-of select="primary_location_name"/></td></tr>
<tr><td>Location Description:</td><td><xsl:value-of select="location_description"/></td></tr>
<tr><td>Locality:</td><td><xsl:value-of select="locality"/></td></tr>
<tr><td>Locality Description:</td><td><xsl:value-of select="locality_description"/></td></tr>
<tr><td>Country:</td><td><xsl:value-of select="country"/></td></tr>
<tr><td>Province:</td><td><xsl:value-of select="province"/></td></tr>
<tr><td>County:</td><td><xsl:value-of select="county"/></td></tr>
<tr><td>City:</td><td><xsl:value-of select="city"/></td></tr>
<tr><td>Cruise/Field/Program:</td><td><xsl:value-of select="cruise_field_prgrm"/></td></tr>
<tr><td>Platform Type:</td><td><xsl:value-of select="platform_type"/></td></tr>
<tr><td>Platform Name:</td><td><xsl:value-of select="platform_name"/></td></tr>
<tr><td>Platform Description:</td><td><xsl:value-of select="platform_descr"/></td></tr>
<tr><td>Collector:</td><td><xsl:value-of select="collector"/></td></tr>
<tr><td>Collector Detail:</td><td><xsl:value-of select="collector_detail"/></td></tr>
<tr><td>Collection Start Date:</td><td><xsl:value-of select="collection_start_date"/></td></tr>
<tr><td>Collection End Date:</td><td><xsl:value-of select="collection_end_date"/></td></tr>
<tr><td>Current Archive:</td><td><xsl:value-of select="current_archive"/></td></tr>
<tr><td>Current Archive Contact:</td><td><xsl:value-of select="current_archive_contact"/></td></tr>
<tr><td>Original Archive:</td><td><xsl:value-of select="original_archive"/></td></tr>
<tr><td>Original Archive Contact:</td><td><xsl:value-of select="original_archive_contact"/></td></tr>
</table>
</xsl:template>


</xsl:stylesheet>
