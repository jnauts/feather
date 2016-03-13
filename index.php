<?php
	ob_start();  // Turn on the output buffering.
	
	require_once("common" . DIRECTORY_SEPARATOR . "common.php");  // load the common functions
	
	$url = ltrim($_SERVER['REQUEST_URI'], '/'); // get the request URI to decide which class's which method to run
	
	$uris = explode("/", $url); // separate the uris
	unset($uris[0]); 			// $uris' first element always is '/', in here it is unset
		
	$classFile = "controllers" . DIRECTORY_SEPARATOR . $uris[1] . ".php"; // generate controller class path
	
	if (is_file($classFile)) { 		// check whether the class is exist.
		require_once($classFile); 	// if exist it will be loaded. otherwise show 404.
	} else {
		show_404();
	}
	
	$controller = $uris[1]; // element one is the always choose as controller class
	$controllerObject = new $controller();
	
	$method = $uris[2]; // element two is the method
	
	$controllerObject->{$method}($uris[2]);
	
	ob_end_flush(); // flush the echoed output
?>
