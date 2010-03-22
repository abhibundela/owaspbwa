<?php
include ("header.php");
// Grab inputs
$page = $_GET[page];
if ($page=="") {include ("home.htm"); } 
else 
{include ("$page"); } 
include ("footer.php");
?>