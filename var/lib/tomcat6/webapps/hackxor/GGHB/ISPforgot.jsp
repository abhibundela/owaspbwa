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
<div id="settings">
<%!
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
%>


<%


//if(request.getParameter("login") != null && request.getParameter("login").equals("1")){
if(request.getParameter("email") != null){
try{
    Class.forName("com.mysql.jdbc.Driver").newInstance();
    String purl = "jdbc:mysql://localhost:3306/ISP";
    Connection pcon=DriverManager.getConnection(purl, "kbloom", "741lkz");
    String pquery = "select password, user from users where email = ? ;";
    PreparedStatement getpass = pcon.prepareStatement(pquery);
    getpass.setString(1, request.getParameter("email"));
    ResultSet rs=getpass.executeQuery();
    String pass = "";
    String username = "";
    if(rs.next()) {
	pass=rs.getString(1);
	username = rs.getString(2);
	Class.forName("com.mysql.jdbc.Driver").newInstance();
	String url = "jdbc:mysql://localhost:3306/wraithlogin";
	Connection con=DriverManager.getConnection(url, "sendmail", "awv8ja");
	String query = "insert into mail values (?, ?, ?,?,?);";
	PreparedStatement hackthis = con.prepareStatement(query);
	SecureRandom random = new SecureRandom();
	int mID = random.nextInt();
	String toName = request.getParameter("email");
	String toAddress = request.getParameter("email");
	String fromAddress = "noreply@GGHB";
	String subject = "Password reminder";
	String body = "Your username is: " + username + "<br>Your password is: " + pass;
	hackthis.setInt(1, mID);
	hackthis.setString(2, toAddress);
	hackthis.setString(3, fromAddress);
	hackthis.setString(4, subject);
	hackthis.setString(5, body);
	hackthis.execute();
	out.println("Message succesfully sent");
	}
    else
	out.println("Email not found");

}catch(Exception e1)
{out.println(e1);}


}


%>

<FORM NAME="login2" ID="login2" ENCTYPE="multipart/form-data" METHOD=GET action="/ISPforgot.jsp" >
<p>
    <center>   Email: <INPUT id="email" NAME="email" TYPE='text' VALUE=""> </center><p>
   <center> <INPUT TYPE="submit" VALUE="Send me my password">  </center><br>
    </FORM></div>
<br>

</div>

</body>
</html>
