var xmlHttp;
var errorCount = 0;
var actions = new Array();
actions[0] = "tables";
actions[1] = "alter";
actions[2] = "store";
var index = 0;

function $($id) {
	return document.getElementById($id);
}

function newDbChanges() {

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

	xmlHttp.open("GET","./?hdnState=newDbChanges&action="+actions[index],true);
	xmlHttp.send(null);

}

function requestSender() {

	if(xmlHttp.readyState==4) {

		var response = xmlHttp.responseText;

		switch (response) {

			case "tablesYes":
				$("tables").innerHTML = "Done";
				break;

			case "tablesNo":
				$("tables").innerHTML = "Failed";
				errorCount++;
				break;

			case "alterYes":
				$("alter").innerHTML = "Done";
				break;

			case "alterNo":
				$("alter").innerHTML = "Failed";
				errorCount++;
				break;

			case "storeYes":
				$("store").innerHTML = "Done";
				break;

			case "storeNo":
				$("store").innerHTML = "Failed";
				errorCount++;
				break;

		}

		index++;

		if (index < 3) {
			newDbChanges();
		} else {
			showFinalResults();
		}

	}

}

function showFinalResults() {

	if (errorCount > 0) {
		$("message").innerHTML = "There were errors when applying new database changes. You may delete this database and start with a new one";
		document.frmNewDbChanges.hdnState.value = "upgradeStart";
		document.frmNewDbChanges.btnSubmit.value = "Back";
		document.frmNewDbChanges.btnSubmit.style.display = "block";
	} else {
		$("message").innerHTML = "Upgrader successfully applied new database changes. Please click Continue button to proceed";
		document.frmNewDbChanges.hdnState.value = "dbValueChangeOption";
		document.frmNewDbChanges.btnSubmit.value = "Continue";
		document.frmNewDbChanges.btnSubmit.style.display = "block";
	}

}

