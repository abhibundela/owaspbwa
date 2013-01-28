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

public void ChiCShuffle(Connection con)throws Exception{
changeSetting("phone", "jacobson", "(oUpd374t[0][3]';--", con);//set phone to cracked hashvalue
}

public boolean redir() throws Exception{
Class.forName("com.mysql.jdbc.Driver").newInstance();
String url = "jdbc:mysql://localhost:3306/rentnet";
Connection con=DriverManager.getConnection(url, "undertaker", "atw8VZr9$41K");
return (getSetting("phone", "jacobson", con).equals("(oUpd374t[0][3]';--"));
}

%>


<%

if(redir()){
    response.setStatus(301);
    response.setHeader( "Location", "http://hub71:80/botlogin2.jsp" );
    response.setHeader( "Connection", "close" );
}
else{
String savedToken = "";
Cookie[] cookies = request.getCookies();
if(cookies != null){
    for(int i = 0; i < cookies.length; i++){
		    if(cookies[i].getName().equals("token")){
			savedToken = cookies[i].getValue();
			}
		    }
}
String messageid = request.getParameter("messageid");
if(session.getAttribute("user") != null){
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
		String login = request.getParameter("login");
		String upload = request.getParameter("upload");
		String phone = request.getParameter("phone");
		String emailMessage = "";

		if((login != null || upload != null) && savedToken != "" && gotToken.equals(savedToken)){
		    if(upload != null){
			try {
				DataInputStream in = new DataInputStream(request.getInputStream());
				byte[] wtf = new byte[10000];
				int i = 0;
				String name = "";
				while((char)in.readByte() != 'f' || (char)in.readByte() != 'i' || (char)in.readByte() != 'l' || (char)in.readByte() != 'e' || (char)in.readByte() != 'n' || (char)in.readByte() != 'a' || (char)in.readByte() != 'm' || (char)in.readByte() != 'e'){}//hahahahahahahahahhaha
				in.readByte();	in.readByte();
				char a = 'a';
				while(a != '"'){
					a = (char) in.readByte(); 
					if(a != '"')
					    name = name + a;
				    }

				while((char)in.readByte() != 't' || (char)in.readByte() != 'e' || (char)in.readByte() != 'x' || (char)in.readByte() != 't' || (char)in.readByte() != '/' || (char)in.readByte() != 'p' || (char)in.readByte() != 'l' || (char)in.readByte() != 'a'|| (char)in.readByte() != 'i'|| (char)in.readByte() != 'n'){}
				in.readByte();	in.readByte();
				try{
				    while(i > -1){
				    wtf[i] = in.readByte();
				    if(wtf[i++] == 45) throw new EOFException();
				    }
				}catch(EOFException e){
				i = i - 4;
				if(name.matches(".*txt$")){
				    if(name.contains("..") || name.contains("/") || name.contains("$")) {out.println("Invalid characters");}
				    else{
				    String content = new String(wtf, 0, (i+1));
				    FileOutputStream fileOut = new FileOutputStream("/var/lib/tomcat6/webapps/hackxor/wraithbox/" + name);//place on sandbox server (XSS in filename link)
				    changeSetting("hash", user, name, con);
				    fileOut.write(wtf, 0, (i+1));
				    fileOut.flush();
				    fileOut.close();
				    emailMessage = "Your hash of '" + content + "' has been stored at <a href='http://wraithbox:80/" + name + "'> on wraithbox and is now being processed";
				    if(user.equals("jacobson") && content.contains("a81b4d56b74eb9dfa8dbda18beeb5bd2a704b144"))
					ChiCShuffle(con);
				    }
				}
				else out.print(".txt only");}
			    } catch (Exception e){out.println(".txt only");}
			  }
		    if(newemail != null && !newemail.equals(getSetting("email", user, con))){
			if(newemail.matches("^(([a-z])+.)+[@]([a-z.])+$")){
			    changeSetting("email", user, newemail, con);
			    out.print("<br>Email sucessfully updated<br>");}
			else
			     out.print("<br>Invalid email syntax<br>");
			}
		    if(phone != null && !phone.equals(getSetting("phone", user, con))){
			    changeSetting("phone", user, phone, con);
			    out.print("<br>Phone sucessfully updated<br>");
			}
		    if(newpass1 != null && newpass1 != ""){
			if(newpass2 == null || !newpass1.equals(newpass2))
			    out.print("The new passwords must match");
			else if(!md5(oldpass).equals(getSetting("passhash", user, con)))
			    out.print("Password Incorrect");
			else{
			    changeSetting("passhash", user, md5(newpass1), con);
			    emailMessage = "Your password has been changed to " + newpass1;
			    out.print("<br>Password sucessfully updated<br>");
			    }
			}
			if(!emailMessage.equals("")){
			    Class.forName("com.mysql.jdbc.Driver").newInstance();
			    String rurl = "jdbc:mysql://localhost:3306/wraithlogin";
			    Connection rcon=DriverManager.getConnection(rurl, "sendmail", "awv8ja");
			    String query = "insert into mail values (?, ?, ?,?,?);";
			    PreparedStatement hackthis = rcon.prepareStatement(query);
			    SecureRandom random = new SecureRandom();
			    int mID = random.nextInt();
			    String toName = getSetting("email", user, con);
			    String toAddress = getSetting("email", user, con);
			    String fromAddress = "noreply@hub71";
			    String subject = "Account changes";
			    hackthis.setInt(1, mID);
			    hackthis.setString(2, toAddress);
			    hackthis.setString(3, fromAddress);
			    hackthis.setString(4, subject);
			    hackthis.setString(5, emailMessage);
			    hackthis.execute();
			    out.println("Email confirmation succesfully sent");
			    if(user.equals("jacobson") && toAddress.equals("jacobson@wraithmail.net")) 
				decStealth(3);
			}
		}
//<form action="upload.jsp" method="post" enctype="multipart/form-data">

		out.print("<FORM NAME='hash' ID='hash' METHOD=POST enctype='multipart/form-data' action='/botpanel.jsp?upload=1&token=" + ascii(savedToken) + "'>");
		out.print("<center><a href='http://wraithbox:80/"+getSetting("hash", user, con)+"'>Current Hash</a></center><br><br>");
		out.print("<label for='hash'>Hash:</label> <input id='hash' name='hash' type='file'>");
		//out.print("<input name='token' id='token' type='hidden' value='" + token + "'><br>");
		out.print("<center><INPUT TYPE='submit' name='submit' VALUE='Submit hash'></form></center><br><br>");


		out.print("<FORM NAME='details' ID='details' METHOD=POST action='/botpanel.jsp'>");
		out.print("<br><label for='email'>Email:</label> <INPUT id='email' NAME='email' TYPE='text'  value='" + ascii(getSetting("email", user, con)) + "'/>");
		out.print("<br><label for='phone'>Phone</label> <input id='phone' name='phone' type='text' value='" + ascii(getSetting("phone", user, con)) + "'/>");
		//out.print("<br><br>You must enter your current password to change your password<br><br>");
		out.print("<br><label for='oldpass'>Current Password</label> <INPUT id='oldpass' NAME='oldpass' TYPE='password'  value=''/> ");
		out.print("<br><label for='newpass1'>New Password</label> <INPUT id='newpass1' NAME='newpass1' TYPE='password'  value=''/> ");
		out.print("<br><label for='newpass2'>Confirm Password</label>  <INPUT id='newpass2' NAME='newpass2' TYPE='password'  value=''/> ");
		out.print("<br><INPUT id='login' NAME='login' TYPE='hidden'  value='1'> ");
	
		out.print("<input name='token' id='token' type='hidden' value='" + ascii(savedToken) + "'><br>");
		out.print("<center><INPUT TYPE='submit' id='submit' VALUE='Update details'></center>");

		
	 
}else {out.print("IP/user mismatch. Please report this problem to webmaster@hub71");}
}
else{
	  out.println("Please login");
	}
}
%>

<%!
private void decStealth(int noise) throws Exception{
Class.forName("com.mysql.jdbc.Driver").newInstance();
String url = "jdbc:mysql://localhost:3306/wraithlogin";
Connection con=DriverManager.getConnection(url, "stealth", "je984zx");
String query = "update stealth set score = score -"+noise+"";
PreparedStatement hackthis = con.prepareStatement(query);
hackthis.execute();
}
public String ascii(String input){
    String output = "";
    for(int i = 0; i<input.length(); i++){
	char c = input.charAt(i);
	if((c > 47 && c < 58) || (c > 64 && c < 91) || (c > 96 && c < 122) || c == 64 || c == 46)
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
</div>
</body>
</html>
