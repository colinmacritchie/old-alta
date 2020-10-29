<?php

/* OLD CONFIG. KEEP IT AROUND FOR A BIT JUST IN CASE

session_start();

//error_reporting(E_ALL);
//ini_set('display_errors',1);

// put full path to Smarty.class.php
require('/users/s/skigmd/skigmd_html/Portal/Reservations/lib/php/Smarty/Smarty.class.php');
$smarty = new Smarty();

$smarty->setTemplateDir('/users/s/skigmd/skigmd_html/Portal/Reservations/tpl');
$smarty->setCompileDir('/users/s/skigmd/skigmd_html/Portal/Reservations/smarty/templates_c');
$smarty->setCacheDir('/users/s/skigmd/skigmd_html/Portal/Reservations/smarty/cache');
$smarty->setConfigDir('/users/s/skigmd/skigmd_html/Portal/Reservations/smarty/configs');

include 'DB/dbaccess.php';
date_default_timezone_set('America/Denver');


// Validate data
function validate_input($data) {
	
	$remove[] = "'";
	$remove[] = '"';
	$remove[] = "-";
	
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	$data = str_replace($remove, "", "$data");
	return $data;
}


$serverself = $_SERVER['PHP_SELF'];
$smarty->assign('serverself', $serverself);

*/











// Continue session variables
session_start();

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







//echo '<pre>' . print_r($_POST, TRUE) . '</pre>';

if (isset($_POST['New_Password'])) {

	//echo "Landmark 1<br>";
	
	// Get tokens

	$selector = $_POST['selector'];
	$validator = $_POST['validator'];
	$password = $_POST['password'];
	$time = time();
	
	// Creat connection to auth database
	$auth_conn = mysqli_connect($auth_servername, $auth_username, $auth_password, $auth_dbname);
	if (!$auth_conn) {
		$message = "Error connecting to database: " . 	mysqli_connect_error() . PHP_EOL;
		die(mysqli_connect_error());
	} else {
		$message = "Connection successful";	
	}	
	
	$sql = "SELECT * FROM password_reset WHERE selector = '" . $selector . "' AND expires >='" . $time . "'";
	
	//echo "Landmark 2: " . $sql . "<br>";
	
	if ($query = mysqli_query($auth_conn, $sql)) {
		while ($row = mysqli_fetch_assoc($query)) {
			$results[] = $row;
		}
	} else {
		//echo "Error : " . mysqli_connect_error($auth_conn);
		//$message = "Error: " . mysqli_connect_error($conn);
	}
	//echo "Landmark 33<br>";
	
	if ( empty( $results ) ) {
    	return array('status'=>0,'message'=>'There was an error processing your request. Error Code: 002');
	}

	$auth_token = $results[0];
	$calc = hash('sha256', hex2bin($validator));
	
	//echo "auth_token: " . $auth_token . "<br>";
	//echo "calc: " . $calc . "<br>";

	// Validate tokens
	if ( hash_equals( $calc, $auth_token['token'] ) )  {
		//echo "Landmark 4<br>";
		
		$bsql = "SELECT email FROM users WHERE email = '" . $auth_token['email'] . "'";
		
		if ($bquery = mysqli_query($auth_conn, $bsql)) {
		while ($row = mysqli_fetch_assoc($bquery)) {
			$bresults[] = $row;
		}
		} else {
			//echo "Error : " . mysqli_connect_error($auth_conn);
			//$message = "Error: " . mysqli_connect_error($conn);
		}
		
		$user = $bresults[0]['email'];
    
    	if (isset($user) && $user !== '') {

    		// Update password
			$updated = ''; // Set empty variable
			$newpassword = password_hash($password, PASSWORD_DEFAULT);
   			$csql = "UPDATE users SET password = '" . $newpassword . "' WHERE email = '" . $user . "'";
			
			//echo "password: " . $password . "<br><br>";
			//echo "newpassword: " . $newpassword . "<br><br>";
			//echo "csql: " . $csql . "<br><br>";
			
			if (mysqli_query($auth_conn, $csql)) {
				$message = "Password updated successfully";
				//echo $message;
				$updated = true;
			} else {
				$message = "Error updating password: " . mysqli_connect_error($auth_conn);
				$updated == false;
				//echo $message;
			}
    
    		// Delete any existing password reset AND remember me tokens for this user
			/*
			$dsql = "DELETE FROM password_reset WHERE email = '" . $user . "'";
			
			if (mysqli_query($auth_conn, $dsql)) {
				$message = "Password reset record deleted";
				$updated = true;
			} else {
				$message = "Error deleting password reset record: " . mysqli_connect_error($auth_conn);
				$updated = false;
				echo $message;
			}
    		*/
			mysqli_close($auth_conn);
			
			//echo "Landmark 99<br><br>";
			//echo "updated: " . $updated . "<br><br>";
			
    		if ( $updated == true ) {
        		// New password. New session.
        		session_destroy();
        		$message = "Password reset successfully completed";
				//echo $message;
				$smarty->display('successful_reset.tpl');
				//header("Location: /Portal/Reservations/auth_sign_in.php");
				//exit;
    		}
		}
	} else {
		echo "Token comparison failed";
	}

} else {
	$smarty->display('successful_reset.tpl');	
}

?>