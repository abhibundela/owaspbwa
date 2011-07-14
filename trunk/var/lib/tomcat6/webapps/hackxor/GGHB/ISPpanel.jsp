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
<div id='settings'>
<%


String messageid = request.getParameter("messageid");
if(session.getAttribute("user") != null && session.getAttribute("user").equals("admin"))
    response.sendRedirect("http://GGHB/ISPadmin.jsp"); 
else{
if(session.getAttribute("user") != null){

	 String user = session.getAttribute("user").toString();
	try{
	String adID;
	if(request.getParameter("adID") == null) adID = "2";
	else adID = request.getParameter("adID");
	adID = adID.replaceAll("[\\,/]", "");
	String path = "/var/lib/tomcat6/webapps/hackxor/GGHB/2";
	if(adID.contains("..")){ }
	else
	     path = "/var/lib/tomcat6/webapps/hackxor/GGHB/" + adID;
	out.print("<!--<div id='adbar'>");
	BufferedReader input = new BufferedReader(new FileReader(path));
	String line = "";
	while ((line = input.readLine()) != null) {
	out.println(line);
	}
	out.println("<a href='ISPpanel.jsp?adID=" + (Integer.parseInt(adID)+1) + "'>Next advert</a>");
	input.close();
	}catch(Exception e) {
	//out.print(e.getMessage());
	}
	out.print("</div> Re-enable once it fits the new admin layout-->");
	   Class.forName("com.mysql.jdbc.Driver").newInstance();
	     String url = "jdbc:mysql://localhost:3306/ISP";
	    Connection con=DriverManager.getConnection(url, "kbloom", "741lkz");
	  
	if(user != null){
		String newemail = request.getParameter("email");
		String oldpass = request.getParameter("oldpass");
		String newpass1 = request.getParameter("newpass1");
		String newpass2 = request.getParameter("newpass2");
		String confirm = "Update details";//request.getParameter("submit");

		if(confirm != null && confirm.equals("Update details")){
		    if(newemail != null && !newemail.equals(getSetting("email", user, con))){
			changeSetting("email", user, newemail, con);
			out.print("<br>Email sucessfully updated<br>");
		    }
		    if(newpass1 != null && newpass1 != ""){
			if(newpass2 == null || !newpass1.equals(newpass2))
			    out.print("The new passwords must match");
			else if(!oldpass.equals(getSetting("password", user, con)))
			    out.print("Password Incorrect");
			else{
			    changeSetting("password", user, newpass1, con);
			    out.print("<br>Password sucessfully updated<br>");
			}
		    }
		}
		out.print("<FORM NAME='details' ID='details' METHOD=POST action='/ISPpanel.jsp'>");
		out.print("<label for='email'>Email: </label><INPUT id='email' NAME='email' TYPE='text'  value='" + ascii(getSetting("email", user, con)) + "'> ");
		out.print(" <INPUT id='id' NAME='id' TYPE='hidden'  value='" + getSetting("userid", user, con) + "'> ");
		out.print("<!--<br>You must enter your current password to change your password<br><br>-->");
		out.print("<br><label for='oldpass'>Current password: </label><INPUT id='oldpass' NAME='oldpass' TYPE='password'  value=''> ");
		out.print("<br><label for='newpass1'>New Password: </label><INPUT id='newpass1' NAME='newpass1' TYPE='password'  value=''> ");
		out.print("<br><label for='newpass2'>New Password Again: </label><INPUT id='newpass2' NAME='newpass2' TYPE='password'  value=''> ");
		out.print("<center><INPUT TYPE='submit' name='submit' VALUE='Update details'></center>");
}}
else{
	  response.sendRedirect("/ISP.jsp"); 
	}
}
%>

<%!
public String ascii(String input){
    String output = "";
    for(int i = 0; i<input.length(); i++){
	char c = input.charAt(i);
	if((c > 47 && c < 58) || (c > 64 && c < 91) || (c > 96 && c < 122 || c == 46 || c == 64))
	    output = output + c;
    }
    if(!(output.matches("[a-zAA-Z0-9@.]*"))){
	
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
	return value;
}




%>
</div>

<div id="footer">Abuse concerns? Just contact <a href="http://wraithmail:80/send.jsp?to=kbloom@wraithmail.net">the administrator</a></div>
</div>
</body>
</html>
