<?xml version="1.0" encoding="utf-8"?>
<!--
      This XSLT contains a library of common transforms
      for the Earth-Time.org / EarthChem project.....

      Copyright 2006-2008 James F. Bowring and www.Earth-Time.org

      Licensed under the Apache License, Version 2.0 (the "License");
      you may not use this file except in compliance with the License.
      You may obtain a copy of the License at

      http://www.apache.org/licenses/LICENSE-2.0

      Unless required by applicable law or agreed to in writing, software
      distributed under the License is distributed on an "AS IS" BASIS,
      WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
      See the License for the specific language governing permissions and
      limitations under the License.

      Author: James F. Bowring[smtp:bowring@gmail.com]

      Created: 1.August.2007
-->
<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:n="http://www.earth-time.org"
                >


  <xsl:template name="format" >
    <xsl:param name="number" select="." />
    <xsl:call-template name="leading-zero-to-space">
      <xsl:with-param name="input"
                      select="format-number($number,
                                          '###,###,###,###,##0.0####')"/>
    </xsl:call-template>
  </xsl:template>

  <xsl:template name="dateformat" >
    <xsl:param name="number" select="." />
    <xsl:call-template name="leading-zero-to-space">
      <xsl:with-param name="input"
                      select="format-number($number*.000001,
                                          '##,###,###,###,###,###,##0.0##')"/>
    </xsl:call-template>
  </xsl:template>

  <xsl:template name="icpmsformat" >
    <xsl:param name="number" select="." />
    <xsl:call-template name="leading-zero-to-space">
      <xsl:with-param name="input"
                      select="format-number($number,
                                          '###,###,###,###,##0.0#')"/>
    </xsl:call-template>
  </xsl:template>

  <xsl:template name="icpmsdateformat" >
    <xsl:param name="number" select="." />
    <xsl:call-template name="leading-zero-to-space">
      <xsl:with-param name="input"
                      select="format-number($number*.000001,
                                          '##,###,###,###,###,###,##0.0#')"/>
    </xsl:call-template>
  </xsl:template>



  <xsl:template name="leading-zero-to-space">
    <xsl:param name="input"/>
		<xsl:value-of select="$input" />
  </xsl:template>

  <xsl:template name="align">
    <xsl:param name="string"
               select="''" />
    <xsl:param name="padding"
               select="''" />
    <xsl:param name="alignment"
               select="'left'" />
    <xsl:variable name="str-length"
                  select="string-length($string)" />
    <xsl:variable name="pad-length"
                  select="string-length($padding)" />
    <xsl:choose>
      <xsl:when test="$str-length >= $pad-length">
        <xsl:value-of select="substring($string, 1, $pad-length)" />
      </xsl:when>
      <xsl:when test="$alignment = 'center'">
        <xsl:variable name="half-remainder"
                      select="floor(($pad-length - $str-length) div 2)" />
        <xsl:value-of select="substring($padding, 1, $half-remainder)" />
        <xsl:value-of select="$string" />
        <xsl:value-of select="substring($padding, $str-length + $half-remainder + 1)" />
      </xsl:when>
      <xsl:when test="$alignment = 'right'">
        <xsl:value-of select="substring($padding, 1, $pad-length - $str-length)" />
        <xsl:value-of select="$string" />
      </xsl:when>
      <xsl:otherwise>
        <xsl:value-of select="$string" />
        <xsl:value-of select="substring($padding, $str-length + 1)" />
      </xsl:otherwise>
    </xsl:choose>
  </xsl:template>


  <xsl:template name="showimage">
    <xsl:param name="input"
               select="." />
    <xsl:variable name="str-length"
                  select="string-length($input)" />
    <xsl:choose>
      <xsl:when test="$str-length > 0">
        <img src="{$input}" height="100" width="100" />
      </xsl:when>
      <xsl:otherwise>
        N/A
      </xsl:otherwise>
    </xsl:choose>
  </xsl:template>



</xsl:stylesheet>
