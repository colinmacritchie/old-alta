
<?php

// Currently single login for both databases
$single_servername = "localhost";
$single_username = "portaladmin";
$single_password = "zipp0man";
$res_database = "portal_res";
$auth_database = "portal_auth";

// Reservations database
$servername = $single_servername;
$username = $single_username;
$password = $single_password;
$dbname = $res_database;

// Authentication database
$auth_servername = $single_servername;
$auth_username = $single_username;
$auth_password = $single_password;
$auth_dbname = $auth_database;


//Database connection.
$conn = mysqli_connect($servername, $username, $password, $dbname);
//Check connection
if (!$conn) {
	$message = "Error connecting to DB: " . mysqli_connect_error($conn);
	die("Connection failed: " . mysqli_connect_error());	
} else {
	//echo "We are able to connect";
}

/*
$auth_conn = mysqli_connect($auth_servername, $auth_username, $auth_password, $auth_dbname);
if (!$auth_conn) {
	$message = "Error connecting to database: " . 	mysqli_connect_error() . PHP_EOL;
	die(mysqli_connect_error());
	echo "auth connection: " . $message;
} else {
	$message = "Connection successful";	
	echo "auth connection: " . $message;
}
*/
?>