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
           <!-- External Stylesheet -->
       <link href="../styles/collect.css" rel="stylesheet" type="text/css" />
       <link href="../styles/xslt-style.css" rel="stylesheet" type="text/css" />
   </head>
    <body style="font-family: 'Arial';">
    <img src="../assets/images/niko-avatar.png" alt="Níko's Cat Avatar"
             style="width: 120px; height: 120px; object-fit:cover; border-radius: 100%; display: block; margin: 20px auto 10px;" />
        <div id="nav">
            <ul>
                <li>
                    <a href="../../index.html" target="_self">
                        <!-- Inline SVG for Home Icon -->
                        <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 398.99" alt="Home tab">
                          <path d="M291.09,398.99v-118.74h-81.64v118.74h-122.46c-3.94,0-16.63-12.94-15.94-18.69l1.13-151.97L250.27,82.24l178.09,146.09,1.13,151.97c.69,5.76-12,18.69-15.94,18.69h-122.46Z"/>
                          <path d="M350.46,69.97V6.89c0-3.98,12.37-5.97,15.96-6.3,9.29-.85,57.21-1.63,60.92,4.24,9.09,14.37-2.55,109.49,2.79,132.67,1.77,6.04,50.14,41.53,58.89,49.96,12.45,11.99,14.8,12.31,4.49,26.82-7.12,10.03-17.72,25.81-30.61,16.6L250.27,52.69,36.25,231.95c-4.25,1.97-10.73,2.52-14.8.04C19.15,230.58.02,205.2,0,202.33c-.03-4.79,9.25-12.6,12.85-16.02C76.72,125.81,156.07,73.87,220.64,13.13c48.27-41.59,88.86,38.65,129.82,56.85Z"/>
                        </svg>
                    </a>
                </li>
                <li class="dropdown">
                    <button class="dropButton">work</button>

                    <ul class="dropdown-content">
                        <li><a href="/data/niko.xml" target="_self">Níko</a></li>
                        <li><a href="/data/eric.xml" target="_self">Eric</a></li>
                        <li><a href="/data/inês.xml" target="_self">Inês</a></li>
                    </ul>
                </li>
                <li><a href="../pages/about.html" target="_self">about</a></li>
                <li><a href="../pages/contact.html" target="_self">contact</a></li>
            </ul>
        </div><br/>

       <!-- Back Button -->
       <br/><a href="../pages/works.html" style="text-decoration: none; width:125px; padding: 10px; background-color: #a60a0aff; color: #ffffff; border-radius: 5px;">
          Back to Works
        </a><br/>
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
