<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"
xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
xmlns:sfs="http://localhost/Hanze_BOP/Kooij_BOP/SemiFinProds"
exclude-result-prefixes="sfs">
<xsl:template match="/">
<sfu:ProductLists xmlns:sfu="http://localhost/Hanze_BOP/Kooij_BOP/UpProdList">
<xsl:for-each select="sfs:ProductLists/sfs:ProductList"><sfu:ProductList>
<SemiFinishedID><xsl:value-of select="SemiFinishedID"/></SemiFinishedID>
<Name><xsl:value-of select="Name"/></Name>
<Description><xsl:value-of select="Description"/></Description>
<SupplierID><xsl:value-of select="SupplierID"/></SupplierID>
<UnitsInStock><xsl:value-of select="UnitsInStock + 1"/></UnitsInStock>
<Price><xsl:value-of select="Price"/></Price>
</sfu:ProductList>
</xsl:for-each>	
</sfu:ProductLists>

</xsl:template>

</xsl:stylesheet>