<?php
	if($_GET['name'] == NULL || $_GET['name'] == ''){
	
		$isempty = true;
		
	} else {
	
		$html .= '<pre>';
		$html .= 'Hello ' . strip_tags($_GET['name']);
		$html .= '</pre>';
		
		}
?>