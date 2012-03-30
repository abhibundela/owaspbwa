<%@ page language="java" contentType="text/html; charset=ISO-8859-1"
    pageEncoding="ISO-8859-1"%>
<%@ taglib uri="/WEB-INF/CSRFGuard.tld" prefix="csrf" %>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>Insert title here</title>
</head>
<body>
<a href="http://www.owasp.org?<csrf:token/>">link 1</a>
<a href="http://www.owasp.org?test=1&<csrf:token/>">link 2</a>
<a href="http://www.owasp.org?test&<csrf:token/>">link 3</a>

<a href="/some/link?<csrf:token/>">link 4</a>
<a href="/some/link?test=1&<csrf:token/>">link 5</a>
<a href="/some/link?test&<csrf:token/>">link 6</a>

<img src="http://www.owasp.org?<csrf:token/>"/>
<img src="http://www.owasp.org?test=1&<csrf:token/>"/>
<img src="http://www.owasp.org?test&<csrf:token/>"/>

<img src="/some/link?<csrf:token/>"/>
<img src="/some/link?test=1<csrf:token/>"/>
<img src="/some/link?test&<csrf:token/>"/>
<br/><a href="/CSRFGuardTestAppVulnerable/index.jsp">Go Home!</a>
<form name=form">
<input name="<csrf:token-name/>" value="<csrf:token-value/>"/>
</form>
</body>
</html>