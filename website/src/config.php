<?php
	session_start();
	define('HOST', 'localhost:8889');
	define('USERNAME', 'root');
	define('PASSWORD', 'root');
	define('DB_NAME', 'tasksNmanagers');
	
	$mysqli = mysqli_connect(HOST, USERNAME, PASSWORD, DB_NAME);
	
	if($mysqli === false){
		die("ERROR: Could not connect. " . $mysqli->connect_error);
	}


