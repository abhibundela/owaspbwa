/*
 * $RCSfile: XmlHttp.js,v $
 *
 * Gallery - a web based photo album viewer and editor
 * Copyright (C) 2000-2006 Bharat Mediratta
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or (at
 * your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street - Fifth Floor, Boston, MA  02110-1301, USA.
 */
function GetXmlHttp() {
    var xmlHttp = null;
    try {
	xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e) {
	try {
	    xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
	} catch (e) {
	    try {
		xmlHttp = new XMLHttpRequest();
	    } catch (e) {
		xmlHttp = false;
	    }
	}
    }

    if (!xmlHttp && typeof XMLHttpRequest!='undefined') {
	xmlHttp = new XMLHttpRequest();
    }

    return xmlHttp;
}

function SendHttpPost(xmlHttp, url, args, callback) {
    xmlHttp.open("POST", url, /* async */ true);
    xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xmlHttp.onreadystatechange = function() { callback(xmlHttp); }
    xmlHttp.send(args);
}

function SendHttpGet(xmlHttp, url, callback) {
    xmlHttp.open("GET", url, /* async */ true);
    xmlHttp.onreadystatechange = function() { callback(xmlHttp); }
    xmlHttp.send("FOO");
}
