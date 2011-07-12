<?php

// $Header: /cvsroot/tikiwiki/tiki/index.php,v 1.4.2.2 2005/01/01 00:11:21 damosoft Exp $

// Copyright (c) 2002-2005, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
require_once ('tiki-setup.php');
if(!(headers_sent())){
header ("location: $tikiIndex");
}
die("header already sent");

?>
