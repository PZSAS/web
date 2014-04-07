<?php
	error_reporting(E_ALL & E_STRICT);
	ini_set('display_errors', '1');
	ini_set('log_errors', '0');
	ini_set('error_log', './');
	
	function content($lang,$pack,$page,$section){
		$xml = simplexml_load_file("../xml/content.xml");
		
		if($section==-1)
			$cont = $xml->xpath($lang.'/'.$pack.'/'.$page);
		else
			$cont = $xml->xpath($lang.'/'.$pack.'/'.$page.'/'.$section);
		
		header('Content-Type: application/json');
		echo json_encode($cont);
	}
	
	if($_POST){
		if($_POST['language'])
			$lang = $_POST['language'];
		else
			$lang = 'PL';
		if($_POST['pack'])
			$pack = $_POST['pack'];
		else
			$pack = 'main';
		if($_POST['page'])
			$page = $_POST['page'];
		else
			$page = 'index';
		if($_POST['section'])
			$section = $_POST['section'];
		else
			$section = -1;
	}else if($_GET){
		if($_GET['language'])
			$lang = $_GET['language'];
		else
			$lang = 'PL';
		if($_GET['pack'])
			$pack = $_GET['pack'];
		else
			$pack = 'main';
		if($_GET['page'])
			$page = $_GET['page'];
		else
			$page = 'index';
		if($_GET['section'])
			$section = $_GET['section'];
		else
			$section = -1;
	}
	
	content($lang,$pack,$page,$section);
	
?>