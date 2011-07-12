<%@ page language ="java" import="java.sql.*, java.security.SecureRandom, com.gargoylesoftware.htmlunit.html.*, com.gargoylesoftware.htmlunit.*" %>
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
<div id="linkbar">
<ul class="linklist">
<li><a href="/send.jsp">Compose</a><br></li>
<li><a href="/mail.jsp">Inbox</a><br></li>
<li><a href="http://wraithbox:8080/history.jsp?id=<%out.print(session.getAttribute("user"));%>">Login history</a></li>
</ul>
</div>
</div>
<div id="mainmid">

<FORM NAME="email" ID="email" METHOD=POST action="/send.jsp" >
<p><br>
<label for='fromAddress'>From: </label><INPUT id="fromAddress" NAME="fromAddress" TYPE='text' VALUE="<%out.print(session.getAttribute("user"));%>@wraithmail.net"> <br>
<label for='toAddress'>To: </label><INPUT id="toAddress" NAME="toAddress" TYPE='text' VALUE="<%if(request.getParameter("to") != null)out.print(ascii(request.getParameter("to"))); %>" > <br>
<label for='subject'>Subject: </label><INPUT id="subject" NAME="subject" TYPE='text' VALUE="" > <br>
<label for='body'>Body:</label><textarea rows="30" cols="120" id="body" name="body"></textarea> <p>
<center> <INPUT TYPE="submit" VALUE="Send">  </center><br>
    </FORM>
<%!

public String ascii(String input){
    String output = "";
    for(int i = 0; i<input.length(); i++){
	char c = input.charAt(i);
	if((c > 47 && c < 58) || (c > 64 && c < 91) || (c > 96 && c < 122 || c == 46 || c == 64))
	    output = output + c;
    }
    if(!(output.matches("[a-zAA-Z0-9@.]*"))){
	System.out.println("escaping fail \n\n\n\n\nAAAAH");
    }
    return output;
}

%>
<%

//String user = session.getAttribute("user").toString();


String toAddress = request.getParameter("toAddress");
if(toAddress != null){
 SecureRandom random = new SecureRandom();
String mID = random.nextInt() +  "" + random.nextInt();
//String mID = request.getParameter("mailid"); //FIXME
String fromAddress = request.getParameter("fromAddress"); //hidden input
String subject = request.getParameter("subject");
String body = request.getParameter("body");


try{
    Class.forName("com.mysql.jdbc.Driver").newInstance();
    String url = "jdbc:mysql://localhost:3306/wraithlogin";
    Connection con=DriverManager.getConnection(url, "wraith", "5Z1aZfs%&zaA!6597");
    String query = "insert into mail values (?,?,?,?,?);";
    PreparedStatement hackthis = con.prepareStatement(query);
    hackthis.setString(1, mID);
    hackthis.setString(2, ascii(toAddress));
    hackthis.setString(3, ascii(fromAddress));
    hackthis.setString(4, ascii(subject));
    hackthis.setString(5, body);
    hackthis.execute();
    out.println("Message succesfully sent");
    out.println("<a href='http://wraithbox:8080/htmlisland.jsp?messageid=" + mID+"'>View here</a>");



    con=DriverManager.getConnection("jdbc:mysql://localhost:3306/citizens", "citizens", "35DAS2aj^27sxSLFDHaaf");
    WebClient browser = new WebClient(BrowserVersion.FIREFOX_3_6);
    PreparedStatement hackthis2 = con.prepareStatement("select `site`, `username`,`password` from logins where email = ?");
    hackthis2.setString(1, toAddress); 
    ResultSet rs=hackthis2.executeQuery();
    String site, user, pass;
   // browser.getParams().setCookiePolicy("CookiePolicy.BROWSER_COMPATIBILITY");

    while(rs.next()){
	site = rs.getString(1);
	user = rs.getString(2);
	pass = rs.getString(3);

	final HtmlPage login = browser.getPage("http://" + site);
	final HtmlForm form = login.getFormByName("login"); //TODO should be login globally
	form.getInputByName("user").setValueAttribute(user);
	form.getInputByName("pass").setValueAttribute(pass);
	form.getInputByName("submit").click();
    }

    HtmlPage wtf = browser.getPage("http://wraithbox:8080/htmlisland.jsp?messageid=" + mID);
}catch(Exception e1)
{out.println(e1);}
}

%>
</tbody>
</table>

</div>
<div id="footer">Copyright 1999-2011 Discrete Industries Ltd and related entities </div>

</body>
</html>
