<%@ page contentType="text/html;charset=UTF-8" %>
<%@ taglib uri="/tags/struts-bean" prefix="bean" %>
<%@ taglib uri="/tags/struts-html" prefix="html" %>
<%@ taglib uri="/tags/struts-logic" prefix="logic" %>
<html:html>
 <head>
  <title>Struts Validator Form with JavaScript</title>
 </head>
 <body>

<center><table border=0 width=100%><tr>
<td>
<center><h1>Struts Validator Form with JavaScript</h1></center>
</td>
<td align=right>
Contributed by:<br>
<a href="http://www.mandiant.com/"><img src="mandiant_logo.png" border=0 align=right></a>
</td>
</tr></table></center>

This version of the form uses the Struts ValidatorForm to implement white-list input validation and prevent Reflected Cross Site Scripting.  In addition, this form uses the Struts JavaScript input validation on this page.
<p>
<logic:messagesPresent>
  <bean:message key="errors.header"/>
  <ul>
  <html:messages id="error">
     <li><bean:write name="error"/></li>
  </html:messages>
  </ul><hr />
</logic:messagesPresent>

<html:javascript formName="nameBean"/> 

<html:form action="submitname.do" method="GET" onsubmit="return validateNameBean(this);"> 

	Please enter your name:
	<html:text property="name"/>
	<html:submit property="submit" value="Submit"/>       
</html:form>
  
</body>
</html:html>
