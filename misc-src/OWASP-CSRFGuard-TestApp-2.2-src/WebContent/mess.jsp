<%@ page language="java" contentType="text/html; charset=ISO-8859-1"
    pageEncoding="ISO-8859-1"%>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>Insert title here</title>
</head>
<body>
<a href="http://www.owasp.org">link 1</a>
<a href="http://www.owasp.org?test=1">link 2</a>
<a href="http://www.owasp.org?test">link 3</a>

<a href="/some/link">link 4</a>
<a href="/some/link?test=1">link 5</a>
<a href="/some/link?test">link 6</a>

<img src="http://www.owasp.org"/>
<img src="http://www.owasp.org?test=1"/>
<img src="http://www.owasp.org?test"/>

<img src="/some/link"/>
<img src="/some/link?test=1"/>
<img src="/some/link?test"/>

</body>
</html>