<?xml version="1.0" encoding="UTF-8"?>
<xsl:transform version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

  <!-- Converts to HTML, defines output as HTML: -->
<xsl:output
method="html"
encoding="UTF-8"
indent="yes"/> <!-- causes a nice indent -->

<xsl:template match="/">

<html>
<head>
<title>Níko's Work</title>
<link href="../styles/works-style.css" rel="stylesheet" type="text/css" />
</head>

    <body style="font-family: 'Arial';">
       <!-- Back Button -->
        <a href="../pages/works.html" style="text-decoration: none; padding: 10px; background-color: #a60a0aff; color: #ffffff; border-radius: 5px;">
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
          <td><xsl:value-of select="Title"/></td>
          <td>
            <xsl:if test="Link">
              <a href="{Link}">
                <xsl:value-of select="Link"/>
              </a>
            </xsl:if>
          </td>

          <td><xsl:value-of select="DatePublished"/></td>
          <td><xsl:value-of select="TimeToComplete"/></td>
          <td><xsl:value-of select="Description"/></td>

        </tr>
      </xsl:for-each>
    </table>
    </body>
    </html>


  </xsl:template>
</xsl:transform>
