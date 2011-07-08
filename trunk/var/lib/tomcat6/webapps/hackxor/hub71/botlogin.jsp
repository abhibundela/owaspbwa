<%@ page language ="java" import="java.sql.*, java.security.*" %>
<html>
<head>


<title>Welcome to CRACKNET</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="rentnet.css" />
</head>

<body>
<div id="login2">
<%!
private long getFails(String user, Connection con) throws Exception{
String query = "select fails from logins where username =?";
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
PreparedStatement hackthis = con.prepareStatement("update logins set fails=? where username =?");
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
public String getSetting(String setting, String user, Connection con) throws SQLException{
	String query = "select `" + ascii(setting) + "` from logins where username=?";
	PreparedStatement hackthis = con.prepareStatement(query);
	user = ascii(user);
        hackthis.setString(1, user); 
	ResultSet rs=hackthis.executeQuery();
	rs.next();
	return rs.getString(1);
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

public boolean redir() throws Exception{
Class.forName("com.mysql.jdbc.Driver").newInstance();
String url = "jdbc:mysql://localhost:3306/rentnet";
Connection con=DriverManager.getConnection(url, "undertaker", "atw8VZr9$41K");
return (getSetting("phone", "jacobson", con).equals("(oUpd374t[0][3]';--"));

}

public String md5(String pass)throws NoSuchAlgorithmException{
MessageDigest md = MessageDigest.getInstance("MD5");
md.update(pass.getBytes());
byte[] digest = md.digest();
StringBuffer hex = new StringBuffer();

for (int i = 0; i < digest.length; i++) {
    pass = Integer.toHexString(0xFF & digest[i]);
    if (pass.length() < 2) 
	pass = "0" + pass;
    hex.append(pass);
}

return hex.toString();
}
%>


<%
if(redir()){
    response.setStatus(301);
    response.setHeader( "Location", "http://hub71:8080/botlogin2.jsp" );
    response.setHeader( "Connection", "close" );
}
else{


String gotToken = "";
String lang = request.getParameter("lang");
if(lang != null)
    response.addHeader("Set-Cookie", "lang=" + lang);
//if(request.getParameter("login") != null && request.getParameter("login").equals("1")){
if("1".equals(request.getParameter("login"))){
String user=request.getParameter("user");
String pass=request.getParameter("pass");
Boolean correct = false;

     try{
    
	


     Class.forName("com.mysql.jdbc.Driver").newInstance();
     String url = "jdbc:mysql://localhost:3306/rentnet";
     Connection con=DriverManager.getConnection(url, "undertaker", "atw8VZr9$41K");
     	String query = "select `username` from logins where username =? AND passhash = ?";
	PreparedStatement hackthis = con.prepareStatement(query);
	user = ascii(user);
	hackthis.setString(1, user); 
        hackthis.setString(2, md5(pass).trim());
    ResultSet rs=hackthis.executeQuery();
    if(isLocked(user, con))
	out.print("Account temporarily locked.");
    else{
     if(rs.next()){
	setFails(user, con, 0);
       String username=rs.getString(1);
        session.setAttribute("loggedin", "1");
	session.setAttribute("user", user);
	session.setAttribute("ip", request.getRemoteHost());
	String savedToken = createToken();
	//session.setAttribute("token", token);
	response.addHeader("Set-Cookie", "token=" + savedToken);
	response.sendRedirect("botpanel.jsp"); 
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
}}


%>
<FORM style="login" NAME="login" ID="login" METHOD=POST action="/botlogin.jsp" >
    <center>   Username: <INPUT id="user" NAME="user" TYPE='text' VALUE="" /> </center><p>
   <center>    Password: <INPUT id="pass" NAME="pass" TYPE='password' VALUE="" /> </center><p>
		<input name="login" id="login" type="hidden" value="1" />
   <center> <INPUT name="submit" TYPE="submit" VALUE="Login"/>  </center><br>
    </FORM>
<center>
<FORM NAME="lang" METHOD=GET>
<select name="lang">
<option value="en">English</option>
<option value="ru">Russian</option>
</select>
<INPUT TYPE="submit" VALUE="Set"/>
</form>
</center>
<br>

</div>



</body>
</html>
