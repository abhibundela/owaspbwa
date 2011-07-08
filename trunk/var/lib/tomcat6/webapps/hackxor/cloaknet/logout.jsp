<%@ page language ="java" import="java.sql.*, java.security.SecureRandom" %>


<%
session.invalidate();
response.addHeader("Set-Cookie", "sessionid=0");
response.addHeader("Set-Cookie", "userid=0");
response.sendRedirect("proxy.jsp"); 
%>

