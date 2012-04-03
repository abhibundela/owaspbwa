<?php
	echo "<script type=\"text/javascript\">
			try{
				window.localStorage.setItem(\"LocalStorageTarget\",\"This is set by the index.php page\");
				window.sessionStorage.setItem(\"SessionStorageTarget\",\"This is set by the index.php page\");
			}catch(e){
				alert(e);
			};
		</script>";
?>