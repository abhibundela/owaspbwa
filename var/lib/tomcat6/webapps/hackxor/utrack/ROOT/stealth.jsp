<%@page contentType="text/html;"%>
<%@page import="java.io.*, java.security.*, java.sql.*"%>

<html>
<body>
<%!
private void decStealth(int noise) throws Exception{
Class.forName("com.mysql.jdbc.Driver").newInstance();
String url = "jdbc:mysql://localhost:3306/wraithlogin";
Connection con=DriverManager.getConnection(url, "stealth", "je984zx");
String query = "update stealth set score = score -"+noise+"";
PreparedStatement hackthis = con.prepareStatement(query);
hackthis.execute();
}
%>
<% 

%>



</body>
</html>

