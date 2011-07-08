<%@ page language ="java" import="java.sql.*, java.security.SecureRandom, java.io.*" %>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "DTD/xhtml1-transitional.dtd">
<html>
<head>


<title>GGHB</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="gghb.css" />
</head>

<body>
<div id="wrapper">


<div id="quote">
The premier communications solution for discrete users.
</div>
<div id="gghb" onclick="document.location='/'">GGHB</div>
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
	System.out.println("escaping fail \n\n\n\n\nAAAAH");
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
//if(!gotToken.equals(session.getAttribute("token")) || session.getAttribute("token") == null){
  //  out.println(gotToken + "<br>" + session.getAttribute("token"));
   // out.println("<center><font color='red'>Access denied: Invalid token</font></center>");
 // }
//else{
Boolean correct = false;

     try{

	


     Class.forName("com.mysql.jdbc.Driver").newInstance();
     String url = "jdbc:mysql://localhost:3306/ISP";
     Connection con=DriverManager.getConnection(url, "kbloom", "741lkz");
     	String query = "select `user` from users where user =? AND password = ?";
	PreparedStatement hackthis = con.prepareStatement(query);
	user = ascii(user);
	hackthis.setString(1, user); 
        hackthis.setString(2, pass); 
    ResultSet rs=hackthis.executeQuery();
     if(isLocked(user, con))
        out.print("Account temporarily locked.");
      else{
     if(rs.next()){
	setFails(user, con, 0);
       String username=rs.getString(1);
        session.setAttribute("loggedin", "1");
	session.setAttribute("user", user);
	response.sendRedirect("ISPpanel.jsp"); 
	out.println("Logged in as " + session.getAttribute("user")); 
      } 
  else{
	  regFail(user, con);
 	  out.println("<center><font color='red'>Access denied</font></center>"); 
	  session.setAttribute("loggedin", "0");
	  session.setAttribute("user", "guest");
	}}

}catch(Exception e1)
{out.println(e1);}
//}
}


%><div id="login2">
<FORM NAME="login" ID="login" METHOD=POST action="/ISP.jsp">
<p> <center> <b>Secure login: </b></center> <br>
    <center>   Username: <INPUT id="user" NAME="user" TYPE="text"  /> </center><p>
   <center>    Password: <INPUT id="pass" NAME="pass" TYPE='password' VALUE="" /> </center><p>
		<input name="login" id="login" type="hidden" value="1" />
<%
String token = createToken();
session.setAttribute("token", token);
//out.print("<input name='token' id='token' type='hidden' value='" + token + "'>");
%>
   <center> <INPUT id="submit" name="submit" TYPE="submit" VALUE="Login">  </center><br>
    </FORM>
     <center> <a href='ISPforgot.jsp'>Forgot your password?</a></center>
<br>
</div>
</div>


<!--

-->
</body>
</html>
