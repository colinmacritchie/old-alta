<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>
<body>
<?php

require "../lib/php/global.php";
include 'res_func.php';

// Continue session variables
session_start();

// Verify that timezone is correct
date_default_timezone_set('America/Denver');

// Open connection to Auth database
connect_auth();

// Open connection to Res database
connect_res();
	
// Get configuration settings from config table & assign them to variables
get_config();	
	
// Logoff if was requested.
if($_GET["logoff"]){ logoff(); 
exit;
}

// Close connection to Auth database
disconnect_auth();

// Close connection to Res database
disconnect_res();

header("Location: " . $full_url . "/breakfast_layout.php"); /* Redirect browser */

exit();

?>
</body>
</html>