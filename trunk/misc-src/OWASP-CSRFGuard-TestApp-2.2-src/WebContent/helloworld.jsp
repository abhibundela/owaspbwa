<%@ page language="java" contentType="text/html; charset=ISO-8859-1"
    pageEncoding="ISO-8859-1"%>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>JSP Hello World</title>
</head>
<body>
What is your name?
<form name="first" method="POST" action="/CSRFGuardTestApp/HelloWorld">
<input type="text" name="name" value=""/>
<input type="submit" name="submit" value="submit"/>
</form>
<br/>
<a href="/CSRFGuardTestApp/index.jsp">Go Home!</a>
</body>
</html>