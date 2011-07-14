<%@ page language ="java" import="java.sql.*, java.security.*, java.io.*" %>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "DTD/xhtml1-transitional.dtd">
<html>
<head>


<title>Welcome to CRACKNET</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="rentnet.css" />
</head>

<body>

<div id="settings">
<%!
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


String messageid = request.getParameter("messageid");
Cookie[] cookies = request.getCookies();
String pass = "";
for(int i = 0; i < cookies.length; i++){
    if(cookies[i].getName().equals("pass"))
	pass = cookies[i].getValue();
}

if(session.getAttribute("user") != null  ){
if(session.getAttribute("ip").equals(request.getHeader("X-Forwarded-For"))){
	  String user = session.getAttribute("user").toString();
	  
	   Class.forName("com.mysql.jdbc.Driver").newInstance();
	     String url = "jdbc:mysql://localhost:3306/rentnet";
	      Connection con=DriverManager.getConnection(url, "undertaker", "atw8VZr9$41K");
	  

		String newemail = request.getParameter("email");
		String oldpass = request.getParameter("oldpass");
		String newpass1 = request.getParameter("newpass1");
		String newpass2 = request.getParameter("newpass2");
		String gotToken = request.getParameter("token");
		if(user != null && session.getAttribute("pass").equals(pass) && session.getAttribute("token") != null && gotToken != null && gotToken.equals(session.getAttribute("token"))){
		    if(newemail != null && !newemail.equals(getSetting("email", user, con))){
			changeSetting("email", user, ascii(newemail), con);
			out.print("<br>Email sucessfully updated<br>");
			}
		    if(newpass1 != null && newpass1 != ""){
			if(newpass2 == null || !newpass1.equals(newpass2))
			    out.print("The new passwords must match");
			else if(!md5(oldpass).equals(getSetting("passhash", user, con)))
			    out.print("Password Incorrect<br>");
			else{
			    changeSetting("passhash", user, newpass1, con);
			    out.print("<br>Password sucessfully updated<br>");
			    }
			}
		    String src = request.getParameter("img");
		    if(src != null && !src.equals("")){
			src = src.substring(0,Math.min(10, src.length()));
			changeSetting("img", user, src, con);
			}
		    String src2 = request.getParameter("sound");
		      if(src2 != null && !src2.equals("")){
			src2 = src2.substring(0,Math.min(10, src2.length()));
			changeSetting("sound", user, src2, con);
			}
		    String src3 = request.getParameter("hash");
		    if(src3 != null && !src3.equals("")){
			src3 = src3.substring(0,Math.min(10, src3.length()));
			changeSetting("hash", user, src3, con);
			}
		    String esc = request.getParameter("text");
		    if(esc != null && !esc.equals("")){
			esc = esc.replaceAll("[<,>,',\",+,-,&,[,]]", "");//+,-,&,\[,\]
			changeSetting("text", user, esc, con);
			
			}
		}
		else{if(gotToken != null)out.print("KEY MISMATCH");}

		String token = createToken();
		session.setAttribute("token", token);




		out.println("<form name='storage' id='storage' METHOD=POST action='/botpanel2.jsp'>");
		out.println("New hash submissions are temporarily disabled<br>");
		if(getSetting("phone", user, con) == null || getSetting("phone", user, con).equals("")) out.println("Cracking: " + getSetting("hash", user, con) + "<br>");
		else out.println("Cracked hash: " + getSetting("phone", user, con) + "<br>");
		out.println("<input type='hidden' id='hash' name='hash' type='text' value=''>");
		out.println("<br><label for='text'>Description:</label> <input name='text' id='text' value='" + getSetting("text", user, con) + "'>");
		out.print("<br><label for='img'>Image: </label><input id='img' name='img' type='text' value='" + getSetting("img", user, con)+ "'>");
		out.print("<br><label for='sound'>Sound: </label><input id='sound' name='sound' type='text' value='" + getSetting("sound", user, con)+ "'>");
		out.println("<input name='token' id='token' type='hidden' value='" + token + "'><br>");
		out.println("<center><INPUT TYPE='submit' VALUE='Submit data'> </center></form>");

		out.print("<FORM NAME='details' ID='details' METHOD=POST action='/botpanel2.jsp'>");
		out.print("<br><label for='email'>Email:</label> <INPUT id='email' NAME='email' TYPE='text'  value='" + ascii(getSetting("email", user, con)) + "'> <br>");
		out.print("<br><label for='oldpass'>Current password: </label><INPUT id='oldpass' NAME='oldpass' TYPE='password'  value=''> ");
		out.print("<br><label for='newpass1'>New Password: </label><INPUT id='newpass1' NAME='newpass1' TYPE='password'  value=''> ");
		out.print("<br><label for='newpass2'>New Password Again:</label> <INPUT id='newpass2' NAME='newpass2' TYPE='password'  value=''> ");
		out.print("<INPUT id='login' NAME='login' TYPE='hidden'  value='1'> ");

		out.print("<input name='token' id='token' type='hidden' value='" + token + "'><br>");
		out.print("<center><INPUT TYPE='submit' VALUE='Update details'></center>");
	 
}else{out.println("IP change detected. Please re-authenticate. This has been logged");}
}
else{
	  out.println("Please login");
	}
/*


*/
%>

<%!
public String ascii(String input){
    String output = "";
    for(int i = 0; i<input.length(); i++){
	char c = input.charAt(i);
	if((c > 47 && c < 58) || (c > 64 && c < 91) || (c > 96 && c < 122)|| c == 46 || c == 64)
	    output = output + c;
    }
    if(!(output.matches("[a-zAA-Z0-9@.]*"))){
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
String query = "update logins set " + setting + "= ? where username=? "; //setting must not be user input
PreparedStatement ps = con.prepareStatement(query);
//ps.setString(1, setting);
ps.setString(1, newvalue);
ps.setString(2, user);
ps.execute();
return "lol";
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




%>
</center>
</div>
</body>
</html>
