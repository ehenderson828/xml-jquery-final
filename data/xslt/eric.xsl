<?xml version="1.0" encoding="UTF-8"?>
<xsl:transform version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
  
  <xsl:template match="/">
    <html>
    <body style="font-family: 'Arial';">
       <!-- Back Button -->
        <a href="/pages/works.html" style="text-decoration: none; padding: 10px; background-color: #0a39a6; color: #ffffff; border-radius: 5px;">
          Back to Works
        </a>
    <h2>Eric's Recent Projects</h2>
    <table border="1" style="padding:5rem;">
      <tr bgcolor="#2a5fda">
        <th>Title</th>
        <th>Link to File</th>

        <th>Date Published</th>
        <th>Time to Complete</th>
        <th>Description</th>

      </tr>
      <xsl:for-each select="ericsProjects/ericsProject">
        <tr>
          <td style="height:fit-content; width:max-content; text-align: center;"><xsl:value-of select="Title"/></td>
          <td style="height:fit-content; width:max-content; text-align: center;"><xsl:value-of select="Link"/></td>

          <td style="height:fit-content; width:max-content text-align: center;"><xsl:value-of select="DatePublished"/></td>
          <td style="height:fit-content; width:max-content; text-align: center;"><xsl:value-of select="TimeToComplete"/></td>
          <td style="height:fit-content; width:max-content; text-align: center;"><xsl:value-of select="Description"/></td>

        </tr>
      </xsl:for-each>
    </table>
    </body>
    </html>
  </xsl:template>

</xsl:transform>
