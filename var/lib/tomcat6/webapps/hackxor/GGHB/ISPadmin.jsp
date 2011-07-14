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
<div id="gghb">GGHB</div>
<div id="settings">
<%


String messageid = request.getParameter("messageid");
if(!request.getHeader("X-Forwarded-For").equals("127.0.0.1"))
    out.print("Local logins only");
else{
if(session.getAttribute("user") != null && session.getAttribute("user").equals("admin"))
{ //somehow block remote access
	
	Class.forName("com.mysql.jdbc.Driver").newInstance();
	String url = "jdbc:mysql://localhost:3306/ISP";
	Connection con=DriverManager.getConnection(url, "kbloom", "741lkz");
	
	out.print("<form name='a' id='a' method=get action='/ISPadmin.jsp'> ");
	out.print("User <input id='user' name='user' type='text' value='" + request.getParameter("user") + "'>");
	out.print("<INPUT TYPE='submit' VALUE='Fetch details'></form><br><br>");
	if(request.getParameter("user") != null){
	String user = request.getParameter("user").toString();
	String newemail = request.getParameter("email");
	String oldpass = request.getParameter("oldpass");
	String newpass1 = request.getParameter("newpass1");
	String newpass2 = request.getParameter("newpass2");
	String gotToken = request.getParameter("token");
	String update = request.getParameter("update");
	String submit = request.getParameter("submit");
	if(update != null && update.equals("1") && session.getAttribute("token") != null && gotToken.equals(session.getAttribute("token"))){
	    if(newemail != "" && !newemail.equals(getSetting("email", user, con))){
		changeSetting("email", user, newemail, con);
		out.print("<br>Email sucessfully updated<br>");
		}
	    if(newpass1 != ""){
		if(newpass2 == null || !newpass1.equals(newpass2))
		    out.print("The new passwords must match");
		else{
		    changeSetting("password", user, newpass1, con);
		    out.print("<br>Password sucessfully updated<br>");
		    }
		}
	}
	out.print("<FORM NAME='details' ID='details' METHOD=POST action='/ISPadmin.jsp'>");
	out.print("Email <INPUT id='email' NAME='email' TYPE='text'  value='" + getSetting("email", user, con) + "'> ");
	out.print("<br>New Password: <INPUT id='newpass1' NAME='newpass1' TYPE='password'  value=''> ");
	out.print("<br>New Password Again: <INPUT id='newpass2' NAME='newpass2' TYPE='password'  value=''><br> ");
	out.print("<INPUT id='update' NAME='update' TYPE='hidden'  value='1'> ");
	String token = createToken();
	session.setAttribute("token", token);
	out.print("<input name='token' id='token' type='hidden' value='" + token + "'>");
	out.print("<input name='user' id='user' type='hidden' value='" + user + "'>");
	out.print("<INPUT TYPE='submit' VALUE='Update details'></form>");
	out.print("<br><a href='/logs/" + getSetting("logname", user, con) + ".pcap'> Traffic log </a>");
}}
else{
	  out.println("Admin only");
	}}
%>

<%!
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

public String changeSetting(String setting, String user, String newvalue, Connection con) throws SQLException{
setting = ascii(setting);
String query = "update users set " + setting + "= ? where user=? "; //setting must not be user input
PreparedStatement ps = con.prepareStatement(query);
//ps.setString(1, setting);
ps.setString(1, newvalue);
ps.setString(2, user);
ps.execute();
return "lol";
}



public String getSetting(String setting, String user, Connection con) throws SQLException{
	String query = "select `" + ascii(setting) + "` from users where user=?";
	PreparedStatement hackthis = con.prepareStatement(query);
	user = ascii(user);
        hackthis.setString(1, user); 
	ResultSet rs=hackthis.executeQuery();
	String value = "";
	if(rs.next()) 
	    value = rs.getString(1);
	//else
	  //  value = setting + " of " + user + " not found";
	return value;
}




%>
</center>
</div>
</body>
</html>
