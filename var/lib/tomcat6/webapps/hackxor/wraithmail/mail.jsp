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
<a href="index.jsp"><img src="wraith.png" alt="Thursday" class="logo3" width="468" height="60" /> </a>
</div>

<div id="quote">

<p class="floatmid alignmid">
<br><br>
The premier communications solution for discrete users.
</p>
</div>
<div id="linkbar">
<ul class="linklist">
<li><a href="/send.jsp">Compose</a><br></li>
<li><a href="/mail.jsp">Inbox</a><br></li>
<li><a href="http://wraithbox:8080/history.jsp?id=<%out.print(session.getAttribute("user"));%>">Login history</a></li>
</ul>
</div>
</div>
<div id="mainmid">
<center>
<%


String messageid = request.getParameter("messageid");
if(session.getAttribute("user") != null){
	  String user = session.getAttribute("user").toString();
	  if(user.equals("algo")) out.print("Stealth rating: "+getStealth());
	   Class.forName("com.mysql.jdbc.Driver").newInstance();
	     String url = "jdbc:mysql://localhost:3306/wraithlogin";
	     Connection con=DriverManager.getConnection(url, "wraith", "5Z1aZfs%&zaA!6597");
	  
	if(messageid == null){
		String mailtable = "<table id='hor-minimalist-b' >\n<thead>\n<tr>\n<th scope='col'>"
		 +"To</th>\n<th scope='col'>From</th>\n<th scope='col'>Subject</th>\n</tr>\n</thead>\n<tbody>\n";
		out.print(mailtable);
		out.print(getList(user, con));
		out.print("</tbody></table>");
	      } //no timing attacks for YOU, scum!
	else{
	  out.println("<br><br><br><br></center>" + getMessage(messageid, user, con));
	}
}
else
    response.sendRedirect("index.jsp"); 
%>

<%!
private String getStealth() throws Exception{
Class.forName("com.mysql.jdbc.Driver").newInstance();
String url = "jdbc:mysql://localhost:3306/wraithlogin";
Connection con=DriverManager.getConnection(url, "stealth", "je984zx");
String query = "select * from stealth";
PreparedStatement hackthis = con.prepareStatement(query);
ResultSet rs=hackthis.executeQuery();
rs.next();
return rs.getString(1);
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

public String getMessage(String messageid, String user, Connection con) throws SQLException{
	String query = "select `toad`, `fromad`,`subject`,`body`  from mail where mid =? AND user = ?";
	PreparedStatement hackthis = con.prepareStatement(query);
	user = ascii(user);
	hackthis.setInt(1, Integer.parseInt(messageid)); 
        hackthis.setString(2, user); 
	ResultSet rs=hackthis.executeQuery();
	String message = "";
	while(rs.next()){
	    message = message + "<p>To " + rs.getString(1) + "<br>";
	    message = message +"From " + rs.getString(2) + "<br>";
	    message = message +"Subject " + rs.getString(3) + "<br>";
	    message = message +"Date " + rs.getString(4) + "<br><br>";
	    message = message +"" + rs.getString(6) + "<br>";
	}
	return message;
}

public String getList(String user, Connection con) throws SQLException{
	String query = "select `toad`, `fromad`,`subject`,`mid`  from mail where toad = ?";
	PreparedStatement hackthis = con.prepareStatement(query);
	user = ascii(user) + "@wraithmail.net";
	hackthis.setString( 1, user); 
	String message = "";
	ResultSet rs=hackthis.executeQuery();
	while(rs.next()){
	    String wtf = "<a id='a' href='http://wraithbox:8080/htmlisland.jsp?messageid=" + rs.getString(4) + "'>";
	    message = message + "<tr>"; 
	    message = message + "<td> " +wtf+  rs.getString(1) + "</a></td>";
	    message = message + "<td> " +wtf+ rs.getString(2) + "</a></td>";
	    message = message + "<td> " +wtf+ rs.getString(3) + "</a></td>";
	    message = message + "</tr>\n";
	}
	return message;
}

%>



</center>
</div>
<div id="footer">Copyright 1999-2011 Discrete Industries Ltd and related entities </div>
</body>
</html>
