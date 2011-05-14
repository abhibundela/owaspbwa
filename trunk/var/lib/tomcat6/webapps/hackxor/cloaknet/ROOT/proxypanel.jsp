<%@ page language ="java" import="java.sql.*, java.security.SecureRandom" %>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "DTD/xhtml1-transitional.dtd">
<html>
<head>


<title>Cloak Control Panel</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="cloaknet.css" />
</head>

<body>
<div id="wrapper">
<div id="header">
<a href="index.jsp"><img src="/logo.png" alt="Thursday" class="logo3" width="468" height="60" /> </a>
</div>

<div id="quote">

<p class="floatmid alignmid">
<br><br>
The premier cloaking solution for discrete users.
</p>
</div>
<center>
<div id="mainmid">
<a href="/logout.jsp">Logout</a>
<%


String messageid = request.getParameter("messageid");
if(session.getAttribute("user") != null){
	   out.print("<br><br><br>Welcome " + session.getAttribute("user"));
	  String userid = getUID(request.getCookies());
	    Class.forName("com.mysql.jdbc.Driver").newInstance();
	     String url = "jdbc:mysql://localhost:3306/PROXY";
	    Connection con=DriverManager.getConnection(url, "webmaster", "disCON1991");
	  

		String mailtable = "<table id='hor-minimalist-b' summary='Employee Pay Sheet'>\n<thead>\n<tr>\n<th scope='col'>"
		 +"Source</th>\n<th scope='col'>Target</th>\n<th scope='col'>Date</th>\n</tr>\n</thead>\n<tbody>\n";
		out.print(mailtable);
		out.print(getList(userid, con));
		out.print("</tbody></table>");

}
%>

<%!
private String makeSafe(String input){
input = input.replace("'", "''");
input = input.replace("\\", "\\\\");//"fix syntax  highlighting
input = input.toLowerCase().replace("union", "");
input = input.toLowerCase().replace("select", "");
return input;
}

private String getUID(Cookie[] cookies){
String userid = "";
	    if(cookies != null){
		for(int i = 0; i < cookies.length; i++){
				if(cookies[i].getName().equals("userid")){
				    userid = cookies[i].getValue();
				    }
				}
	    }
return userid;
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
	String message = "";
	Statement st=con.createStatement();//badly hide logs
	ResultSet rs=st.executeQuery("select source, target, timestamp from logs where userid = "+makeSafe(userid)+" ");
	while(rs.next()){
	    message = message + "<tr>"; 
	    message = message + "<td> " + rs.getString(1) + "</a></td>";
	    message = message + "<td> " + rs.getString(2) + "</a></td>";
	    message = message + "<td> " + rs.getString(3) + "</a></td>";
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
