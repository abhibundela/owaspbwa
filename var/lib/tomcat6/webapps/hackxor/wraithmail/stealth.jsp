<%@page contentType="text/html;"%>
<%@page import="java.io.*, java.security.*, java.sql.*"%>

<html>
<body>
<%!

private String getStealth() throws Exception{
Class.forName("com.mysql.jdbc.Driver").newInstance();
String url = "jdbc:mysql://localhost:3306/wraithlogin";
Connection con=DriverManager.getConnection(url, "stealth", "je984zx");
String query = "select * from stealth";
PreparedStatement hackthis = con.prepareStatement(query);
ResultSet rs=hackthis.executeQuery();
rs.next();
return rs.getString(1);
}
%>
<% 
out.print(getStealth());
%>



</body>
</html>

