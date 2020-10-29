<?php

/* OLD CONFIG. KEEP AROUND FOR A BIT JUST IN CASE AND THEN DELETE

// put full path to Smarty.class.php
require('/users/s/skigmd/skigmd_html/Portal/Reservations/lib/php/Smarty/Smarty.class.php');
$smarty = new Smarty();

$smarty->setTemplateDir('/users/s/skigmd/skigmd_html/Portal/Reservations/tpl');
$smarty->setCompileDir('/users/s/skigmd/skigmd_html/Portal/Reservations/smarty/templates_c');
$smarty->setCacheDir('/users/s/skigmd/skigmd_html/Portal/Reservations/smarty/cache');
$smarty->setConfigDir('/users/s/skigmd/skigmd_html/Portal/Reservations/smarty/configs');

include 'DB/dbaccess.php';
date_default_timezone_set('America/Denver');


$serverself = $_SERVER['PHP_SELF'];
$smarty->assign('serverself', $serverself);

*/








// Turn error reporting on/off
//error_reporting(E_ALL);
//ini_set('display_errors',1);

include 'DB/dbaccess.php';
include '../lib/php/global.php';
include 'res_func.php';

// Verify that timezone is correct
date_default_timezone_set('America/Denver');


// Open connection to Auth database
connect_auth();

// Open connection to Res database
connect_res();

// Get configuration settings from config table & assign them to variables
get_config();

// put full path to Smarty.class.php
require($full_path . 'Portal/Reservations/lib/php/Smarty/Smarty.class.php');
$smarty = new Smarty();

// Set SMARTY paths
smarty_paths();

// Have SMARTY assign config & other variables
assign_config();









// Check for tokens
$selector = filter_input(INPUT_GET, 'selector');
$validator = filter_input(INPUT_GET, 'validator');
$time = time();

if ( false !== ctype_xdigit( $selector ) && false !== ctype_xdigit( $validator ) ) {
	// Creat connection to auth database
	/*
	$auth_conn = mysqli_connect($auth_servername, $auth_username, $auth_password, $auth_dbname);
	if (!$auth_conn) {
		$message = "Error connecting to database: " . 	mysqli_connect_error() . PHP_EOL;
		die(mysqli_connect_error());
	} else {
		$message = "Connection successful";	
	}
	*/	
	
	$sql = "SELECT * FROM password_reset WHERE selector = '" . $selector . "' AND expires >='" . $time . "'";
	
	//echo "Landmark 2: " . $sql . "<br>";
	
	$results = mysqli_query($auth_conn, $sql);
	
	if (($results) && ($results->num_rows === 0)) {
		$smarty->display('no_access.tpl');
	} else {
		$smarty->assign('selector', $selector);
		$smarty->assign('validator', $validator);
		$smarty->display('reset.tpl');
	}
} else {
	$smarty->display('no_access.tpl');	
}


?>