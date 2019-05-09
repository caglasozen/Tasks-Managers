<?php
	session_start();
	define('HOST', 'dijkstra.ug.bcc.bilkent.edu.tr:3306');
	define('USERNAME', 'cagla.sozen');
	define('PASSWORD', 'xD1U8Ui3');
	define('DB_NAME', 'cagla_sozen');
	
	$mysqli = mysqli_connect(HOST, USERNAME, PASSWORD, DB_NAME);
	
	if($mysqli === false){
		die("ERROR: Could not connect. " . $mysqli->connect_error);
	}
?>
