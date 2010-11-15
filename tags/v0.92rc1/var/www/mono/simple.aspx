<%@ Page Language="C#" validateRequest="true" %>
<html>
<head>
<title>Simple ASP.NET Page</title>
</head>
<body>
    <p>
        A simple ASP.NET page
    </p>
    <p>
<%
	String name = Request.QueryString["name"];
	if(name != null) {
	Response.Write ("Hello, " + name); 
	} else {
	Response.Write ("Please enter your name below."); 
	} 
%>
<p>
<form name="input" action="simple.aspx" method="get">
Enter your name
<input type="text" name="name" />
<input type="submit" value="Submit" />
</form> 
<p>
Note: This page should be vulnerable to Reflected Cross Site Scripting, but is protected by ASP.NET request validation.  If you would like to try to exploit this issue with request validation disabled, see the <a href="simple-reflected-xss.aspx">Simple ASP.NET Page with Reflected Cross Site Scripting</a>.
<p>
<center>These forms were contributed by <a href="http://www.mandiant.com">MANDIANT</a> <a href="http://www.mandiant.com"><img src="mandiant.png"align="center" border=0></a></center>
</body>
</html>
