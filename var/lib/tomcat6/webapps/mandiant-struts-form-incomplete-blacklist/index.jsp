<%@ page contentType="text/html;charset=UTF-8" %>
<%@ taglib uri="/tags/struts-bean" prefix="bean" %>
<%@ taglib uri="/tags/struts-html" prefix="html" %>
<%@ taglib uri="/tags/struts-logic" prefix="logic" %>
<html:html>
 <head>
  <title>Struts Form (Incomplete Black List)</title>
 </head>
 <body>

<center><table border=0 width=100%><tr>
<td>
<center><h1>Struts Form (Incomplete Black List)</h1></center>
</td>
<td align=right>
Contributed by:<br>
<a href="http://www.mandiant.com/"><img src="mandiant_logo.png" border=0 align=right></a>
</td>
</tr></table></center>

This version of the form does black list input validation to prevent "&lt;script&gt;" tags, but is vulnerable to more sophisticated types of Reflected Cross Site Scripting.
<p>
<html:form action="submitname.do" method="GET">
	Please enter your name:
	<html:text property="name"/>
	<html:submit property="submit" value="Submit"/>       
</html:form>
  
</body>
</html:html>
