/*
 * $RCSfile: AutoComplete.js,v $
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

/*
 * Inspired by code from:
 * - Guyon Roche (http://www.webreference.com/programming/javascript/gr/column5/)
 * - Zichun (http://codeproject.com/jscript/jsactb.asp)
 */
var autoCompleteContexts = new Array();

function autoCompleteTrigger(id) {
    // Query for current value if known to be a directory (due to trailing slash or from
    // previous query results) else query for dirname(value); skip repeated queries.
    var orig = document.getElementById(id).value, value = orig.replace(/([\\\/].*)[\\\/]$/, '$1');
    if (value == '' || autoCompleteContexts[id]['queries'][value]) return autoCompleteRender(id);
    if (value == orig && !autoCompleteContexts[id]['results'][value]) {
	value = value.replace(/([\\\/])[^\\\/]+$/, '$1').replace(/([\\\/].*)[\\\/]$/, '$1');;
	if (autoCompleteContexts[id]['queries'][value]) return autoCompleteRender(id);
    }

    var responseCallback = function(response) {
	if (response.readyState != 4) {
	    return;
	}

	autoCompleteContexts[id]['queries'][value] = 1;
	results = response.responseText.split("\n");
	for (i = 0; i < results.length; i++) {
	    autoCompleteContexts[id]['results'][autoCompleteUnentity(results[i])] = results[i];
	}
	autoCompleteRender(id);
    }

    var url = autoCompleteContexts[id]['url'].replace(/__VALUE__/, escape(value));
    SendHttpGet(autoCompleteContexts[id]['connection'], url, responseCallback);
}

function autoCompleteGetLeft(element) {
    var offset = 0;
    while (element) {
	offset += element.offsetLeft;
	element = element.offsetParent;
    }
    return offset;
}

function autoCompleteGetTop(element) {
    var offset = 0;
    while (element) {
	offset += element.offsetTop;
	element = element.offsetParent;
    }
    return offset;
}

function autoCompleteIsVisible(id) {
    var autoCompleteId = id + '_autocomplete';
    var ac = document.getElementById(autoCompleteId);
    if (!ac) {
	return false;
    }
    return ac.style.visibility == 'visible';
}

function autoCompleteRender(id) {
    var autoCompleteId = id + '_autocomplete';
    var source = document.getElementById(id);
    var ac = document.getElementById(autoCompleteId);
    if (!ac) {
	ac = document.createElement('div');
	ac.className = 'autoCompleteBackground';
	ac.style.position = 'absolute';
	ac.id = autoCompleteId;
	document.body.appendChild(ac);
    }
    ac.style.top = eval(autoCompleteGetTop(source) + source.offsetHeight) + 'px';
    ac.style.left = autoCompleteGetLeft(source) + 'px';

    var matches = autoCompleteFindMatches(source.value, autoCompleteContexts[id]['results']);

    // Escape regexp characters (quotemeta)..
    var regexp = new RegExp("^(" + autoCompleteEscape(source.value) + ")", "i");
    if (matches.length > 0) {
	for (i = 0; i < Math.min(matches.length, 10); i++) {
	    var row, newHTML = matches[i].replace(regexp,
			       '<span class="autoCompleteHighlight">$1</span>');
	    if (i >= ac.childNodes.length) {
		row = document.createElement('div');
		ac.appendChild(row);
	    } else {
		row = ac.childNodes[i];
		if (row.innerHTML == newHTML) {
		    // Already up to date
		    continue;
		}
	    }
	    row.className = 'autoCompleteNotSelected';
	    row.innerHTML = newHTML;
	    row.ac_data = autoCompleteUnentity(matches[i]);
	    row.ac_index = i;
	    row.onmousedown = function() { source.value = this.ac_data; };
	    row.onmouseover = function() { autoCompleteSelect(id, this); };
	    row.onmouseout = function() { autoCompleteDeselect(id); };
	}
	while (i < ac.childNodes.length) {
	    ac.removeChild(ac.childNodes[i]);
	}
	ac.style.visibility = 'visible';
	autoCompleteContexts[id]['current'] = -1;
    } else {
	ac.style.visibility = 'hidden';
    }
}

function autoCompleteMove(id, delta) {
    var autoCompleteId = id + '_autocomplete';
    var ac = document.getElementById(autoCompleteId);
    if (!ac || ac.childNodes.length == 0) {
	return;
    }
    var source = document.getElementById(id);
    var current = autoCompleteContexts[id]['current'] + delta;
    if (current < 0) {
	current += ac.childNodes.length;
    }
    current = current % ac.childNodes.length;
    autoCompleteSelect(id, ac.childNodes[current]);
}

function autoCompleteSelect(id, row) {
    autoCompleteDeselect(id);
    row.className = 'autoCompleteSelected';
    autoCompleteContexts[id]['current'] = row.ac_index;
}

function autoCompleteDeselect(id) {
    var current = autoCompleteContexts[id]['current'];
    if (current != -1) {
	var autoCompleteId = id + '_autocomplete';
	var ac = document.getElementById(autoCompleteId);
	ac.childNodes[current].className = 'autoCompleteNotSelected';
	autoCompleteContexts[id]['current'] = -1;
    }
}

function autoCompleteChoose(id) {
    var current = autoCompleteContexts[id]['current'];
    if (current != -1) {
	var autoCompleteId = id + '_autocomplete';
	var ac = document.getElementById(autoCompleteId);
	var source = document.getElementById(id);
	source.value = ac.childNodes[current].ac_data;
	autoCompleteHide(id);
    }
}

function autoCompleteFindMatches(needle, haystack) {
    var matches = new Array(), regexp = new RegExp("^" + autoCompleteEscape(needle), "i");
    for (hay in haystack) {
	hay = haystack[hay];
	if (regexp.test(hay)) {
	    matches.push(hay);
	}
    }
    return matches.sort();
}

function autoCompleteHide(id) {
    if (autoCompleteContexts[id]['timerId']) {
	clearTimeout(autoCompleteContexts[id]['timerId']);
    }

    autoCompleteId = id + '_autocomplete';
    var ac = document.getElementById(autoCompleteId);
    if (ac) {
	ac.style.visibility = 'hidden';
    }
}

function autoCompleteAttach(id, url) {
    autoCompleteContexts[id] = new Array();
    autoCompleteContexts[id]['connection'] = GetXmlHttp();
    autoCompleteContexts[id]['queries'] = new Array();
    autoCompleteContexts[id]['results'] = new Array();
    autoCompleteContexts[id]['url'] = url;

    var source = document.getElementById(id);
    if (document.all) {
	// IE doesn't let you set attributes by passing in a lambda function like
	// Mozilla does.  Instead, we have to create a string and pass it to the
	// Function() constructor.  We expand the 'id' and 'url' elements in the
	// string, but allow the event object to pass through from the calling
	// scope.  Ugh.
	source.onblur = new Function('autoCompleteHide("' + id + '");');
	source.onkeydown = new Function('return autoCompleteHandleEvent("' + id +
					'", event, "' + url + '"); ');
    } else {
	// Everything else
	source.onblur = function() { autoCompleteHide(id); }
	source.onkeydown = function(event) { return autoCompleteHandleEvent(id, event, url);}
    }
}

function autoCompleteHandleEvent(id, event, url) {

    switch(event.keyCode) {
    case 38: // up key
	if (autoCompleteIsVisible(id)) {
	    autoCompleteMove(id, -1);
	    return false;
	}
	break;

    case 40: // down key
	if (autoCompleteIsVisible(id)) {
	    autoCompleteMove(id, 1);
	    return false;
	}
	break;

    case 39: // right-arrow
	if (autoCompleteIsVisible(id)) {
	    if (autoCompleteContexts[id]['timerId']) {
		clearTimeout(autoCompleteContexts[id]['timerId']);
	    }

	    autoCompleteChoose(id);
	    autoCompleteContexts[id]['timerId'] = setTimeout('autoCompleteTrigger("' + id + '")', 250);
	    autoCompleteRender(id);
	    return false;
	}
	break;

    case 9: // tab
	if (autoCompleteIsVisible(id)) {
	    autoCompleteChoose(id);
	    return false;
	}
	break;

    case 13: // enter
	if (autoCompleteIsVisible(id)) {
	    autoCompleteChoose(id);
	    return true;
	}
	break;

    case 37: // left-arrow
    case 27: // escape
	if (autoCompleteIsVisible(id)) {
	    autoCompleteHide(id);
	    return false;
	}
	break;

    default:
	if (autoCompleteContexts[id]['timerId']) {
	    clearTimeout(autoCompleteContexts[id]['timerId']);
	}

	autoCompleteContexts[id]['timerId'] = setTimeout('autoCompleteTrigger("' + id + '")', 250);
	autoCompleteRender(id);
    }
    return true;
}

function autoCompleteEscape(s) {
    // Convert to html entities, then escape regexp characters (quotemeta)..
    return s.replace(/&/, '&amp;').replace(/"/, '&quot;').replace(/>/, '&gt;').replace(/</, '&lt;')
	    .replace(/([\\\[\](){}^$.*+?|])/g, '\\$1');
}
function autoCompleteUnentity(s) {
    return s.replace(/&quot;/, '"').replace(/&gt;/, '>').replace(/&lt;/, '<').replace(/&amp;/, '&');
}
