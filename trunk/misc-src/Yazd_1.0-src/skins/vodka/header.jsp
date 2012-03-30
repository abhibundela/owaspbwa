
<%
/**
 *	$RCSfile: header.jsp,v $
 *	$Revision: 1.3 $
 *	$Date: 2000/12/18 02:04:56 $
 */
%>

<%@ page import="java.util.Date,
                 java.text.SimpleDateFormat" %>

<%!	////////////////////////
	// global page variables
	
	// date formatter for today's date
	private final SimpleDateFormat todayDateFormatter = 
		new SimpleDateFormat("MMMM d");
%>

<html>
<head>
	<title><%= title %></title>
	<link rel="stylesheet" href="style/global.css">
</head>

<%-- customize the background colors, links, etc for the entire skin here: --%>
<body bgcolor="#FFFFFF" text="#000000" link="#0000FF" vlink="#800080" alink="#FF0000">

<%	/////////////////////
	// header variables
	
	// change these values to customize the look of your header
	
	// Colors
	String headerBgcolor = "#000000";
	String headerFgcolor = "#ffffff";
	
	// header image vars
	String headerImgURL = "";
	String headerImgSRC = "images/header.gif";
	String headerImgWidth = "154";
	String headerImgHeight = "47";
	String headerImgAltText = "Yazd: Example Skin";
	
	// Header text
	String headerText = "Yazd Powered Discussion Forums";
%>

<table bgcolor="<%= headerBgcolor %>" cellspacing="0" cellpadding="1" width="100%" border="0">
<td><table bgcolor="<%= headerFgcolor %>" cellspacing="0" cellpadding="0" width="100%" border="0">
	<td width="1%"><a href="<%= headerImgURL %>"><img src="<%= headerImgSRC %>" width="<%= headerImgWidth %>" height="<%= headerImgHeight %>" alt="<%= headerImgAltText %>" border="0"></a></td>
	<td width="98%" align="center">
		<span class="headerText"><%= headerText %></span>
	</td>
	<td width="1%" nowrap>
		<span class="headerDate"><%= todayDateFormatter.format( new Date() ) %></span>
		&nbsp;
	</td>
</table></td></table>

<p>

