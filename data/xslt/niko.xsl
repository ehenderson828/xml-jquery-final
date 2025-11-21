<?xml version="1.0" encoding="UTF-8"?>
<xsl:transform version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
  <!--xsl:output
  method="html"
  doctype-system="about:legacy=compact"
  encoding=UTF-8
  indent="yes"-->
  
  <xsl:template match="/">
    <html>
    <body style="font-family: 'Arial';">
       <!-- Back Button -->
        <a href="/pages/works.html" style="text-decoration: none; padding: 10px; background-color: #a60a0aff; color: #ffffff; border-radius: 5px;">
          Back to Works
        </a>
    <h2>Níko's Recent Projects</h2>
    <table border="1" style="padding:5rem;">
      <tr bgcolor="#a60a0a">
        <th>Title</th>
        <th>Link to File</th>

        <th>Date Published</th>
        <th>Time to Complete</th>
        <th>Description</th>

      </tr>
      <xsl:for-each select="nikosProjects/nikosProject">
        <tr>
          <td style="height:fit-content; width:max-content;"><xsl:value-of select="Title"/></td>
          <td style="height:fit-content; width:max-content;"><xsl:value-of select="Link"/></td>

          <td style="height:fit-content; width:max-content;"><xsl:value-of select="DatePublished"/></td>
          <td style="height:fit-content; width:max-content;"><xsl:value-of select="TimeToComplete"/></td>
          <td style="height:fit-content; width:max-content;"><xsl:value-of select="Description"/></td>

        </tr>
      </xsl:for-each>
    </table>
    </body>
    </html>
  </xsl:template>

</xsl:transform>
