<%@ page language="java" contentType="text/html; charset=ISO-8859-1"
    pageEncoding="ISO-8859-1"%>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>Evaluation of Reflected XSS Detection Accuracy - HTTP GET Method - Experimental</title>
</head>
<body>

<%
	String anticsrf = (String)request.getSession().getAttribute("anticsrf");
	if(anticsrf == null) {
		//Generate and store a new token
		anticsrf = "" + Math.random();
		request.getSession().setAttribute("anticsrf", anticsrf);
	}
%>

<font size="5">Test Cases:</font><br><br>
<B><a href="Case01-Tag2HtmlPageScope-StripScriptTag.jsp?userinput=textvalue">Case01-Tag2HtmlPageScope-StripScriptTag.jsp</a></B><br>
  Injection of tags to the scope of the HTML page that strips script tags.<br>
  <U>Barriers:</U><br>
  Script tags are stripped from the input<br>
  <U>Sample Exploit Structures:</U><br>
  &lt;input type=text [dhtmlevent]=&quot;[code]&quot;&gt;<br>
  &lt;[customtag] style=&quot;width: expression&#40;[exploit code]&#41;;&quot;&gt;<br>
  <U>Examples:</U><br>
  Silent Exploit: &lt;img src=&quot;a&quot; onerror=&quot;javascript:document&#46;title=document&#46;domain&quot;&gt;<br>
  IE Custom Tag Exploit: &lt;sectooladdict style=&quot;width: expression&#40;document&#46;title=document&#46;domain&#41;;&quot;&gt;<br>
  <br>
  
<B><a href="Case02-Tag2HtmlPageScope-SecretVectorPOST.jsp?userinput=textvalue">Case02-Tag2HtmlPageScope-SecretVectorPOST.jsp</a></B><br>
  Injection of tags to the scope of the HTML page that that only relies on secret POST input.<br>
  <U>Barriers:</U><br>
  Secret input vector without any hints<br>
  <U>Sample Exploit Structures:</U><br>
  &lt;script&gt;[exploit code]&lt;/script&gt;<br>
  &lt;input type=text [dhtmlevent]=&quot;[code]&quot;&gt;<br>
  &lt;[customtag] style=&quot;width: expression&#40;[exploit code]&#41;;&quot;&gt;<br>
  <U>Examples:</U><br>
  Exploit: &#60;script&#62;document&#46;title&#61;&#34;Exploit&#34;&#59;&#60;&#47;script&#62;<br>
  Silent Exploit: &lt;img src=&quot;a&quot; onerror=&quot;javascript:document&#46;title=document&#46;domain&quot;&gt;<br>
  IE Custom Tag Exploit: &lt;sectooladdict style=&quot;width: expression&#40;document&#46;title=document&#46;domain&#41;;&quot;&gt;<br>
  <br>
  
<B><a href="Case03-Tag2HtmlPageScope-ConstantAntiCSRFToken.jsp?anticsrf=<%=anticsrf%>&userinput=textvalue">Case03-Tag2HtmlPageScope-ConstantAntiCSRFToken.jsp</a></B><br>
  Injection of tags to the scope of the HTML page that requires a constant session stored AntiCSRF token.<br>
  <U>Barriers:</U><br>
  Requires a constant session-specific AntiCSRF token<br>
  <U>Sample Exploit Structures (alongside the AntiCSRF token):</U><br>
  &lt;script&gt;[exploit code]&lt;/script&gt;<br>
  &lt;input type=text [dhtmlevent]=&quot;[code]&quot;&gt;<br>
  &lt;[customtag] style=&quot;width: expression&#40;[exploit code]&#41;;&quot;&gt;<br>
  <U>Examples (alongside the AntiCSRF token):</U><br>
  Exploit: &#60;script&#62;document&#46;title&#61;&#34;Exploit&#34;&#59;&#60;&#47;script&#62;<br>
  Silent Exploit: &lt;img src=&quot;a&quot; onerror=&quot;javascript:document&#46;title=document&#46;domain&quot;&gt;<br>
  IE Custom Tag Exploit: &lt;sectooladdict style=&quot;width: expression&#40;document&#46;title=document&#46;domain&#41;;&quot;&gt;<br>
  <br>
  
<B><a href="Case04-Tag2HtmlPageScope-ChangingAntiCSRFToken.jsp">Case04-Tag2HtmlPageScope-ChangingAntiCSRFToken.jsp</a></B><br>
  Injection of tags to the scope of the HTML page that requires an expiring one-use session stored AntiCSRF token.<br>
  <U>Barriers:</U><br>
  Requires a changing, newly generated session-specific AntiCSRF token<br>
  <U>Sample Exploit Structures (alongside the AntiCSRF token):</U><br>
  &lt;script&gt;[exploit code]&lt;/script&gt;<br>
  &lt;input type=text [dhtmlevent]=&quot;[code]&quot;&gt;<br>
  &lt;[customtag] style=&quot;width: expression&#40;[exploit code]&#41;;&quot;&gt;<br>
  <U>Examples (alongside the AntiCSRF token):</U><br>
  Exploit: &#60;script&#62;document&#46;title&#61;&#34;Exploit&#34;&#59;&#60;&#47;script&#62;<br>
  Silent Exploit: &lt;img src=&quot;a&quot; onerror=&quot;javascript:document&#46;title=document&#46;domain&quot;&gt;<br>
  IE Custom Tag Exploit: &lt;sectooladdict style=&quot;width: expression&#40;document&#46;title=document&#46;domain&#41;;&quot;&gt;<br>
  <br>
  
 
</body>
</html>