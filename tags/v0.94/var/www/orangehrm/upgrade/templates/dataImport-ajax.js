var tablesArray = new Array();
var numOfTables
var xmlHttp;
var tablesCount = 0;
var successCount = 0;
var failiureCount = 0;
var failedTables = new Array();

function setData(tArray, tCount) {
	tablesArray = tArray;
	numOfTables = tCount;
}

function $($id) {
	return document.getElementById($id);
}

function dataImport() {

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

	xmlHttp.open("GET","./?hdnState=dataImport&table="+tablesArray[tablesCount],true);
	xmlHttp.send(null);

}

function requestSender() {

	if(xmlHttp.readyState==4) {

		var response = xmlHttp.responseText;
		var results = response.split("-");

		if (results[0] == "Yes") {
			successCount++;
			progressPercentage = Math.ceil((successCount / numOfTables) * 100);
			changeProgressBar(progressPercentage);
		} else {
			failedTables[failiureCount] = results[1];
			failiureCount++;
		}

		tablesCount++;

		if (tablesCount < numOfTables) {
			dataImport();
		} else {
			showFinalResults();
		}

	}

}

function changeProgressBar(pecentage) {

	$('progressBar').style.width = pecentage + '%';
	$('spanProgressPercentage').innerHTML = pecentage + '%';

}


function showFinalResults() {

	if (failiureCount > 0) {
		$("message").innerHTML = "There were errors when importing data. You may delete this database and start with a new one";
		document.frmDataImport.hdnState.value = "dataImportError";
		document.frmDataImport.btnSubmit.value = "Back";
		document.frmDataImport.btnSubmit.style.display = "block";
	} else {
		$("message").innerHTML = "Upgrader successfully imported data from current database. Please click Continue button to proceed";
		document.frmDataImport.hdnState.value = "oldConstraints";
		document.frmDataImport.btnSubmit.value = "Continue";
		document.frmDataImport.btnSubmit.style.display = "block";
	}

}

