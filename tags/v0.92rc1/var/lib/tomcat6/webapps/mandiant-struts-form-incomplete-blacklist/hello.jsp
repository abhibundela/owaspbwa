<html>
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

<h1>Welcome</h1>

<jsp:useBean id="nameBean" class="com.mandiant.NameBean" scope="request"></jsp:useBean>
<p>Welcome, <%=nameBean.getName()%>!</p>

</body>
</html>
