<?php

// $Header: /cvsroot/tikiwiki/tiki/files/index.php,v 1.1.2.1 2006/01/27 22:43:04 sylvieg Exp $

// Copyright (c) 2002-2005, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.

// This redirects to the sites root to prevent directory browsing

header ("location: ../tiki-index.php");
die;

?>
