<%@ page language ="java" import="java.sql.*, java.security.SecureRandom" %>
<html>
<head>


<title>Cloak</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="cloaknet.css" />
</head>

<body>
<div id="wrapper">
<div id="header">
<a href="index.jsp"><img src="/logo.png" alt="Thursday" class="logo3" width="468" height="60" /> </a>
</div>

<div id="quote">

<p class="alignmid">
<br><br>
The premier cloaking solution for discrete users.
</p>
</div>
<div id="mainmid">
<%!
public String createToken(){
      SecureRandom random = new SecureRandom();
      byte bytes[] = new byte[32];
      random.nextBytes(bytes);
      String secret = "";
      for(int i = 0; i<32; i++){
	secret = secret + bytes[i];
    }

return secret.replace("-", "");//craps on randomness but whatever
}


private String makeSafe(String input){
input = input.replace("'", "''");
input = input.replace("\\", "\\\\");//"fix syntax  highlighting
input = input.toLowerCase().replace("union", "");
input = input.toLowerCase().replace("select", "");
return input;
}
%>


<%

//
//if(request.getParameter("login") != null && request.getParameter("login").equals("1")){
if("1".equals(request.getParameter("login"))){
String user=request.getParameter("user");
String pass=request.getParameter("pass");
String gotToken = request.getParameter("token");
if(!gotToken.equals(session.getAttribute("token")) || session.getAttribute("token") == null){
    out.println("<center><font color='red'>Access denied: Invalid token</font></center>");
  }
else{
Boolean correct = false;
// user: asd 
//password: asd' union select id,password from users-- 
     try{
     Class.forName("com.mysql.jdbc.Driver").newInstance();
     String url = "jdbc:mysql://localhost:3306/PROXY";
     Connection con=DriverManager.getConnection(url, "webmaster", "disCON1991");
     Statement st=con.createStatement();
     if(user.toLowerCase().matches(".*f.*i.*l.*e.*"))
	    out.print("Attack detected");
     ResultSet rs=st.executeQuery("select id, user from users where user = '"+makeSafe(user)+"' and password = '" + makeSafe(pass) + "'");
    
     if(rs.next()){
       String userid=rs.getString(1);
	user=rs.getString(2);
	session.setAttribute("user", user);
        session.setAttribute("loggedin", "1");
	session.setAttribute("userid", userid);
	response.addHeader("Set-Cookie", "userid=" + userid);
	response.sendRedirect("proxypanel.jsp"); 
	out.println("Logged in as " + session.getAttribute("user")); 
      } 
  else{
 	  out.println("<center><font color='red'>Access denied</font></center>"); 
	  session.setAttribute("loggedin", "0");
	  session.setAttribute("user", "guest");
	}

}catch(Exception e1)
{out.println(e1);}
}}


%>
<FORM NAME="login" ID="login" METHOD="POST" action="/proxy.jsp" >
<p>
    <center> Username: <INPUT id="user" NAME="user" TYPE='text' VALUE=""> </center><p>
   <center>    Password: <INPUT id="pass" NAME="pass" TYPE='password' VALUE="" > </center><p>
		<input name="login" id="login" type="hidden" value="1">
<%
String token = createToken();
session.setAttribute("token", token);
out.print("<input name='token' id='token' type='hidden' value='" + token + "'>");
%>
   <center> <INPUT name="submit" TYPE="submit" VALUE="Login">  </center><br>
    </FORM>
<br>

   <center> <div onclick="alert('Try our demo account with username:demo password:demo')">No account? </div></center>
</div>
<div id="footer">Copyright 1999-2011 Discrete Industries Ltd and related entities </div>

</body>
</html>
