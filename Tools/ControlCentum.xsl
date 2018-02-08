<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"
xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
xmlns:pro="http://localhost/test">

<xsl:template match="/">
<table>
<tr>
<xsl:for-each select="pro:processes/proces">
<td class ="proces_box">
<h3><xsl:value-of select="title"/></h3>
			<Form method="POST">
			<xsl:attribute name="action"><xsl:value-of select="filename"/></xsl:attribute>
				<input type="hidden" name="MessageMap" value=""/> 
				<input type="hidden" name="Step" value="0"/>
				<input type="hidden" name="StartInput" value="startinput_to_replace"/>
				<input type="submit" value="Execute"/>
				<br />
                                <input type="checkbox" name="Automatisch" id="Autom1">
<xsl:attribute name="unchecked"/></input>
<label for="Autom1">Automatisch uitvoeren</label><br />
			</Form>
</td>

		  <xsl:if test="position()mod3=0">
			newline
	  	</xsl:if>

</xsl:for-each>
</tr>
<tr><td class ="proces_box"><h3>Terug naar startscherm</h3>
<form method="POST">
<xsl:attribute name="action">../index.php</xsl:attribute>
<Input type="submit" value="Terug"/></form></td></tr>
</table>
</xsl:template>

</xsl:stylesheet>