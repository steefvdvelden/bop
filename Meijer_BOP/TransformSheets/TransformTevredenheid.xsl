<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"
xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
xmlns:tev="http://localhost/Hanze_BOP/Kooij_BOP/tevredenheid"
exclude-result-prefixes="tev">
<xsl:template match="/">
<trt:transformedTev xmlns:trt='http://localhost/Hanze_BOP/Kooij_BOP/transform'>
<xsl:for-each select="tev:tevredenheids/tev:tevredenheid"><trt:tevredenheid>
<gemid><xsl:value-of select="gemiddelde"/></gemid></trt:tevredenheid>
</xsl:for-each>	

</trt:transformedTev>

</xsl:template>

</xsl:stylesheet>