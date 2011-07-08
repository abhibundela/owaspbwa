<%@ page language ="java" import="java.sql.*, java.security.SecureRandom, java.lang.System" %>
<html>
<head>


<title>Welcome to WRAITHMAIL</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="wraithmail.css" />
</head>

<body>
<div id="wrapper">
<div id="header">
<a href="index.jsp"><img src="wraith.png" alt="Thursday" class="logo3" width="468" height="60" /> </a>
</div>

<div id="quote">

<p class="alignmid">
<br><br>
The premier communications solution for discrete users.
</p>
</div><div id="mainmid">
<%!
private long getFails(String user, Connection con) throws Exception{
String query = "select fails from users where user =?";
PreparedStatement hackthis = con.prepareStatement(query);
user = ascii(user);
hackthis.setString(1, user);
ResultSet rs=hackthis.executeQuery();
String fails = "0";
if(rs.next())
    fails = rs.getString(1);
return Long.parseLong(fails);
}

private void setFails(String user, Connection con, long fails) throws Exception
{
PreparedStatement hackthis = con.prepareStatement("update users set fails=? where user =?");
hackthis.setString(1, "" + fails);
hackthis.setString(2, user);
hackthis.execute();
}

private void regFail(String user, Connection con) throws Exception{
    long fails = getFails(user, con);
    if(fails >= 9)
	setFails(user, con, System.currentTimeMillis());
    else
	setFails(user, con, ++fails);
}

public boolean isLocked(String user, Connection con) throws Exception{
long time = getFails(user, con);
boolean locked;
if(time <= 9)
    locked = false;
else{
    if(time > (System.currentTimeMillis()-1800000))
	locked = true;
    else{
	locked = false;
	setFails(user, con, 0);
	}
    }
return locked;
}


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

public String ascii(String input){
    String output = "";
    for(int i = 0; i<input.length(); i++){
	char c = input.charAt(i);
	if((c > 47 && c < 58) || (c > 64 && c < 91) || (c > 96 && c < 122))
	    output = output + c;
    }
    if(!(output.matches("[a-zAA-Z0-9]*"))){

    }
    return output;
}
%>


<%

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

     try{
      
      Class.forName("com.mysql.jdbc.Driver").newInstance();
      String url = "jdbc:mysql://localhost:3306/wraithlogin";
      Connection con=DriverManager.getConnection(url, "wraith", "5Z1aZfs%&zaA!6597");
      user = ascii(user);

      if(isLocked(user, con))
        out.print("Account temporarily locked.");
      else{
      String query = "select `user` from users where user =? AND password = ?";
      PreparedStatement hackthis = con.prepareStatement(query);
     
      hackthis.setString(1, user); 
      hackthis.setString(2, pass); 
      ResultSet rs=hackthis.executeQuery();
    
     if(rs.next()){
	setFails(user, con, 0);
       String username=rs.getString(1);
        session.setAttribute("loggedin", "1");
	session.setAttribute("user", user);
	response.sendRedirect("mail.jsp"); 
	out.println("Logged in as " + session.getAttribute("user")); 
      } 
  else{
	  regFail(user, con);
 	  out.println("<center><font color='red'>Access denied</font></center>"); 
	  session.setAttribute("loggedin", "0");
	  session.setAttribute("user", "guest");
	}}

}catch(Exception e1)
{}
}}


%><div id="login2">
<FORM NAME="login" ID="login" METHOD=POST action="/index.jsp" class="login">
    <center>   Username: <INPUT id="user" NAME="user" TYPE='text' VALUE="" /> </center><p>
   <center>    Password: <INPUT id="pass" NAME="pass" TYPE='password' VALUE="" /> </center><p>
		<input name="login" id="login" type="hidden" value="1" />
<%
String token = createToken();
session.setAttribute("token", token);
out.print("<input name='token' id='token' type='hidden' value='" + token + "'/>");
%>
   <center> <INPUT name="submit" TYPE="submit" VALUE="Login"/>  </center><br>
    </FORM>
    </div>
<br>

</div>
<div id="footer">Copyright 1999-2011 Discrete Industries Ltd and related entities </div>

</body>
</html>
