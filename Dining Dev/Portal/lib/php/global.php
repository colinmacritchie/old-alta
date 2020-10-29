<?php
function logoff() {
	
	include '/Portal/Reservations/res_func.php';
	
	get_config();
	
	$host = $_SERVER['SERVER_NAME'];

	// Unset all of the session variables.
	$_SESSION = array();

	// If it's desired to kill the session, also delete the session cookie.
	// Note: This will destroy the session, and not just the session data!
	if (ini_get("session.use_cookies")) {
		$params = session_get_cookie_params();
		setcookie(session_name(), '', time() - 42000,
			$params["path"], $params["domain"],
			$params["secure"], $params["httponly"]
		);
	}
	session_destroy();
	//header("Location: http://$host/");
	header("Location: " . $full_path . "/auth_sign_in.php");
	die;
}

function db_connect() {
  global $config;
  global $db;
  
  if(!isset($db)) {
	$db = mysqli_connect($config['db_host'],$config['db_user'],$config['db_password'],$config['db_table']);
  }
  // If connection was not successful, handle the error
  if($db === false) {
	// Handle error - notify administrator, log to a file, show an error screen, etc.
	echo mysqli_connect_error();
  }
}

function db_query($query) {
	global $ai;
	global $db;

	// Query the database
	$result = mysqli_query($db,$query);
    $ai = mysqli_insert_id($db);
	return $result;
}

function db_prep($data)
// Basic prep function - trims and escapes data for db insert.
{
   if (isset($data) and $data != ''){ 
    $data = str_replace('"', "", $data);
	$data = str_replace("'", "", $data);
    $data = htmlspecialchars(strip_tags($data));
	$prepped = "'" . trim($data) . "'";
	}
     else { $prepped = "NULL"; }

   return $prepped;
}


?>
