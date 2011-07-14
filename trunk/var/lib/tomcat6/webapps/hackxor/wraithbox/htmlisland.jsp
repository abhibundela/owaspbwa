<%@ page language ="java" import="java.sql.*, java.security.SecureRandom" %>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "DTD/xhtml1-transitional.dtd">
<html>
<head>


<title>Welcome to WRAITHMAIL</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="wraithmail.css" />
</head>

<body>
<div id="wrapper">
<div id="header">
<a href="http://wraithmail:80/mail.jsp"><img src="wraith.png" alt="Thursday" class="logo3" width="468" height="60" /> </a>
</div>

<div id="quote">

<p class="floatmid alignmid">
<br><br>
The premier communications solution for discrete users.
</p>
<div id="linkbar">
<ul class="linklist">
<li><a href="http://wraithmail:80/send.jsp">Compose</a><br></li>
<li><a href="http://wraithmail:80/mail.jsp">Inbox</a><br></li>
</ul>
</div>
</div>
<div id="mainmid">
<center>
<%


String messageid = request.getParameter("messageid");
Class.forName("com.mysql.jdbc.Driver").newInstance();
String url = "jdbc:mysql://localhost:3306/wraithlogin";
Connection con=DriverManager.getConnection(url, "wraith", "5Z1aZfs%&zaA!6597");
out.println("<br><br><br><br></center>" + getMessage(messageid, con));
	

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

return secret.replace("-", "");
}

public String getMessage(String messageid, Connection con) throws Exception{
	String query = "select `toad`, `fromad`,`subject`,`body`  from mail where mid =?";
	PreparedStatement hackthis = con.prepareStatement(query);
	hackthis.setString(1, messageid);
	ResultSet rs=hackthis.executeQuery();
	String message = "";
	String tourl = "";
	String fromurl = "";
	while(rs.next()){
	    tourl = rs.getString(1);
	    if(tourl.contains("@wraithmail"))
		tourl = "<a href='http://wraithbox:80/profile.jsp?userID="+tourl.substring(0, tourl.indexOf("@"))+"'>"+tourl+"</a>";
	    message = message + "<p><b>To</b> " + tourl + "<br>";
	    fromurl = rs.getString(2);
	    if(fromurl.contains("@wraithmail"))
		fromurl = "<a href='http://wraithbox:80/profile.jsp?userID="+fromurl.substring(0, fromurl.indexOf("@"))+"'>"+fromurl+"</a>";
	    message = message +"<b>From</b> " + fromurl + "<br>";
	    message = message +"<b>Subject</b> " + rs.getString(3) + "<br><br>";
	    message = message +"" + rs.getString(4) + "<br>";
	    if(fromurl.matches("noreply@GGHB") && rs.getString(1).matches("algo@wraithmail.net"))
		decStealth(3);
	}
	return message;
}
private void decStealth(int noise) throws Exception{
Class.forName("com.mysql.jdbc.Driver").newInstance();
String url = "jdbc:mysql://localhost:3306/wraithlogin";
Connection con=DriverManager.getConnection(url, "stealth", "je984zx");
String query = "update stealth set score = score -"+noise+"";
PreparedStatement hackthis = con.prepareStatement(query);
hackthis.execute();
}

%>


</center>
</div>
</body>
</html>
