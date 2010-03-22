<?php
	if($_GET['name'] == NULL || $_GET['name'] == ''){
	
		$isempty = true;
		
	} else{
	
		$html .= '<pre>';
		$html .= 'Hello ' . htmlspecialchars($_GET['name']);
		$html .= '</pre>';
		
		}
?>