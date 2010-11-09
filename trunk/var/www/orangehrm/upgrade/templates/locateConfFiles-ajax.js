var xmlHttp;
var errorCount = 0;
var actions = new Array();
var index = 0;

function setData(actionsArray) {
	actions = actionsArray;
}

function $($id) {
	return document.getElementById($id);
}

function locateConfFiles() {

	try { // Firefox, Opera 8.0+, Safari
  		xmlHttp=new XMLHttpRequest();
  	}
	catch(e) { // Internet Explorer

  		try {
    		xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
    	}
  		catch(e) {

    		try {
      			xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
      		}
    		catch(e) {
      			alert("Your browser does not support AJAX!");
      			return false;
      		}
    	}
  	}

	xmlHttp.onreadystatechange=requestSender;
	xmlHttp.open("GET","./?hdnState=locateConfFiles&action="+actions[index],true);

	xmlHttp.send(null);

}

function requestSender() {

	if(xmlHttp.readyState==4) {

		var response = xmlHttp.responseText;

		switch (response) {

			case "confYes":
				$("conf").innerHTML = "Done";
				break;

			case "confNo":
				$("conf").innerHTML = "Failed";
				errorCount++;
				break;

			case "mailYes":
				$("mail").innerHTML = "Done";
				break;

			case "mailNoFile":
				$("mail").innerHTML = "mailConf.php hasn't been created in current installation. No need to copy.";
				break;

			case "mailNo":
				$("mail").innerHTML = "Failed";
				errorCount++;
				break;

			case "enckeyYes":
				$("enckey").innerHTML = "Done";
				break;

			case "enckeyNoFile":
				$("enckey").innerHTML = "key.ohrm hasn't been created in current installation. No need to copy.";
				break;

			case "enckeyNo":
				$("enckey").innerHTML = "Failed";
				errorCount++;
				break;

			case "upgradeYes":
				$("upgrade").innerHTML = "Done";
				break;

			case "upgradeNo":
				$("upgrade").innerHTML = "Failed";
				errorCount++;
				break;

		}

		index++;

		if (index < actions.length) {
			locateConfFiles();
		} else {
			showFinalResults();
		}

	}

}

function showFinalResults() {

	if (errorCount > 0) {
		$("message").innerHTML = "There were errors when locating configuration files. You may delete this database and start with a new one";
		document.frmUpgraderFinished.hdnState.value = "confError";
		document.frmUpgraderFinished.btnSubmit.value = "Back";
		document.frmUpgraderFinished.btnSubmit.style.display = "block";
	} else {
		$("message").innerHTML = "Upgrader successfully located configuration files and you have completed upgrading. Please click Finish button to login to new installation. If you are satisfied with upgrade, you can replace old installation. Check <a href=\"http://www.orangehrm.com/upgrade-instructions.shtml\" target=\"_blank\">Upgrade Instructions</a> at our web site for more details.";
		document.frmUpgraderFinished.hdnState.value = "upgradeFinish";
		document.frmUpgraderFinished.btnSubmit.value = "Finish";
		document.frmUpgraderFinished.btnSubmit.style.display = "block";
	}

}

