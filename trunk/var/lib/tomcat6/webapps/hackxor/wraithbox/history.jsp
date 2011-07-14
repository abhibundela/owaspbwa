<%@ page language ="java" import="java.sql.*, java.security.SecureRandom" %>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "DTD/xhtml1-transitional.dtd">
<html>
<head>


<title>Welcome to CROWD</title>
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
Extensions
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
<table id="hor-minimalist-b" summary="Employee Pay Sheet">
    <thead> 
    	<tr><br><br>Recent login attempts::
        	<th scope="col">Date</th>
            <th scope="col">IP</th>
            <th scope="col">User-Agent</th>
	<th scope="col">Successful?</th>
	<th scope="col">Abuse contact</th>
        </tr>
    </thead>
    <tbody>
<%


String userid = request.getParameter("id");
Class.forName("com.mysql.jdbc.Driver").newInstance();
String url = "jdbc:mysql://localhost:3306/HEX";
Connection con=DriverManager.getConnection(url, "hex", "tryCAKE4ever");
if(userid != null && request.getHeader("Referer") != null && request.getHeader("Referer").contains("wraithmail")){
    out.print(getList(userid, con));
    out.print("</tbody></table>");
} //no timing attacks for YOU!
else{
   //  out.print(userid + "|" + request.getHeader("Referer"));
    out.print("<br><font color='red'>Header validation failed</font><br>");
}

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


public String getList(String userid, Connection con) throws SQLException{
	String query = "select `date`, `ip`,`useragent`,`successful`, `abuse` from loginhistory where userid = ?";
	PreparedStatement hackthis = con.prepareStatement(query);
	//mid = ascii(mid);
	hackthis.setString( 1, userid); 
	String message = "";
	ResultSet rs=hackthis.executeQuery();
	while(rs.next()){
	    String wtf = "";//"<a id='a' href='/mail.jsp?messageid=" + rs.getString(6) + "'>";
	    message = message + "<tr>"; 
	    message = message + "<td> " +wtf+  rs.getString(1) + "</a></td>";
	    message = message + "<td> " +wtf+ rs.getString(2) + "</a></td>";
	    message = message + "<td> " +wtf+ rs.getString(3) + "</a></td>";
	    message = message + "<td> " +wtf+ rs.getString(4) + "</a></td>";
	    message = message + "<td> " +wtf+ rs.getString(5) + "</a></td>";
	    message = message + "</tr>\n";
	}
	return message;
}

%>


Login History Checker Pro! See who is attacking YOU! - addon by |-|3X3R
</center>
</div>
</body>
</html>
