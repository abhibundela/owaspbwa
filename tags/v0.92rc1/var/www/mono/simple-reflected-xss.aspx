<%@ Page Language="C#" validateRequest="false" %>
<html>
<head>
<title>Simple ASP.NET Page with Reflected Cross Site Scripting</title>
</head>
<body>
    <p>
        A simple ASP.NET page vulnerable to Reflected Cross Site Scripting
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
<form name="input" action="simple-reflected-xss.aspx" method="get">
Enter your name
<input type="text" name="name" />
<input type="submit" value="Submit" />
</form> 
<p>
Note: This page is vulnerable because it has explicitly disabled ASP.NET request validation.  If you would like to try to exploit this issue with request validation turned on, see the <a href="simple.aspx">Simple ASP.NET Page</a>.
<p>
<center>These forms were contributed by <a href="http://www.mandiant.com">MANDIANT</a> <a href="http://www.mandiant.com"><img src="mandiant.png"align="center" border=0></a></center>
</body>
</html>
