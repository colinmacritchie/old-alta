<?php

// Continue session variables
session_start();

// Turn error reporting on/off
//error_reporting(E_ALL);
//ini_set('display_errors',1);

require_once '../lib/PHPMailer/PHPMailerAutoload.php';
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

// Assign session variables
$smarty->assign('username',$_SESSION['username']);
$smarty->assign('user_firstName', $_SESSION['user_firstName']);
$smarty->assign('perms_res_access', $_SESSION['perms_res_access']);
$smarty->assign('perms_res_newUser', $_SESSION['perms_res_newUser']);
$smarty->assign('perms_res_deleteUser', $_SESSION['perms_res_deleteUser']);
$smarty->assign('perms_res_timeslots', $_SESSION['perms_res_timeslots']);
$smarty->assign('perms_res_tables', $_SESSION['perms_res_tables']);
$smarty->assign('perms_limit_override', $_SESSION["perms_limit_override"]);

if (!$_SESSION['username']) {
	header("Location: /auth_sign_in.php");	
	exit;
}

if (!$_SESSION['perms_res_access']) {
	header("Location: /no_access.php");	
	exit;
}

if (!$_SESSION['perms_res_deleteUser']) {
	header("Location: /no_access.php");	
	exit;
}


//echo '<pre>' . print_r($_POST, TRUE) . '</pre>';



// Update House Adults total in header menu bar
houseAdultTotal();

// Update House Children total in header menu bar
houseChildrenTotal();

// Update House Party total in header menu bar
housePartyTotal();

// Update Lodge Guest totals (Adults & Children)
lodgeGuestTotals();



// BEGIN New User

if(isset($_POST["Submit"])) {
	
	// Get POST values
	
	
	
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		//$password = validate_input($_POST["password"]);
		//$hashed_password = password_hash($password,PASSWORD_DEFAULT);
		$username = validate_input($_POST["username"]);
		$active = '1';
		$firstName = validate_input($_POST["firstName"]);
		$lastName = validate_input($_POST["lastName"]);
		$department = validate_input($_POST["department"]);
		$email = validate_input($_POST["email"]);
		$reservations_access = validate_input($_POST["reservations_access"]);
		$reservations_newUser = validate_input($_POST["reservations_newUser"]);
		$reservations_deleteUser = validate_input($_POST["reservations_deleteUser"]);
		$reservations_tables = validate_input($_POST["reservations_tables"]);
	}


/* (10-14-19)
$auth_conn = mysqli_connect($auth_servername, $auth_username, $auth_password, $auth_dbname);
if (!$auth_conn) {
	$message = "Error connecting to database: " . 	mysqli_connect_error() . PHP_EOL;
	die(mysqli_connect_error());
} else {
	$message = "Connection successful";	
}
*/

$zsql = "SELECT email FROM users WHERE email = '" . $email . "' ";
$zresults = mysqli_query($auth_conn, $zsql);

	if (($zresults) && ($zresults->num_rows === 0)) {

$asql = "INSERT INTO users (username, active, firstName, lastName, department, email) VALUES ('$username', '$active', '$firstName', '$lastName', '$department', '$email')";

//echo $asql . "<br><br>";

if (mysqli_query($auth_conn, $asql)) {
	$message = "New user successfully created";
	//echo $message . "<br>";
} else {
	$message = "Error creating new user: " . mysqli_connect_error() . PHP_EOL; 	
	//echo $message . "<br>";
}

$bsql = "INSERT INTO users_permissions (username, reservations_access, reservations_newUser, reservations_deleteUser, reservations_tables) VALUES ('$username', '$reservations_access', '$reservations_newUser', '$reservations_deleteUser', '$reservations_tables');";

//echo $bsql . "<br><br>";

if (mysqli_query($auth_conn, $bsql)) {
	$message = "New user permissions successfully created";
	//echo $message . "<br>";
} else {
	$message = "Error creating new user permissions: " . mysqli_connect_error() . PHP_EOL; 	
	//echo $message . "<br>";
}



// BEGIN Password email

	/* (10-14-19)
	// Creat connection to auth database
	$auth_conn = mysqli_connect($auth_servername, $auth_username, $auth_password, $auth_dbname);
	if (!$auth_conn) {
		$message = "Error connecting to database: " . 	mysqli_connect_error() . PHP_EOL;
		die(mysqli_connect_error());
	} else {
		$message = "Connection successful";	
	}
	*/
	
	$psql = "SELECT email FROM users WHERE email = '" . $_POST['email'] . "' ";
	$presults = mysqli_query($auth_conn, $psql);

	if (($presults) && ($presults->num_rows === 0)) {
		$smarty->display('reset_email_sent.tpl');
		mysqli_free_result($presult);
		exit;
	}

	// Create tokens
	$selector = bin2hex(random_bytes(8));
	$token = random_bytes(32);
	
	//$abs_url = $full_url . '/Portal/Reservations/';
	$abs_url = $full_url . '/';
	$email = $_POST['email'];

	$url = sprintf('%sreset.php?%s', $abs_url, http_build_query([
    	'selector' => $selector,
    	'validator' => bin2hex($token)
	]));

	//echo $url . "<br><br>";

	// Token expiration
	$expires = new DateTime('NOW');
	$expires->add(new DateInterval('PT01H')); // 1 hour

	// Delete any existing tokens for this user
	//$this->db->delete('password_reset', 'email', $user->email);
		
	
	$ysql = "DELETE FROM password_reset WHERE email = '" . $email . "'";
	
	if ($yresult = mysqli_query($auth_conn, $ysql)) {
		$message = "Existing tokens deleted";
		//echo $message . "<br>";
	} else {
		$message = "Error deleting tokens: " . mysqli_connect_error() . PHP_EOL; 	
		//echo $message . "<br>";
	}
	mysqli_free_result($yresult);
	

	// Insert reset token into database
	$hashed_token = hash('sha256', $token);
	$formatted_expires = $expires->format('U');
	$xsql = "INSERT INTO password_reset (email, selector, token, expires) VALUES ('$email', '$selector', '$hashed_token', '$formatted_expires')";
	
	if ($xresult = mysqli_query($auth_conn, $xsql)) {
		$message = "Token inserted into database";
		//echo $message . "<br>";
	} else {
		$message = "Error inserting tokens: " . mysqli_connect_error() . PHP_EOL; 	
		//echo $message . "<br>";
	}
	mysqli_free_result($xresult);

	// Send the email
	
	// Details for the PHP mailer Class are here:
	//      https://github.com/PHPMailer/PHPMailer

	$fp = fopen('request.log', 'w');

	// Subject
	$subject = 'Create a password';

	// Message
	$message = '<p>We recieved a request to create a new password. The link to create your password is below.<br>';
	$message .= 'If you did not make this request, you can ignore this email. ';
	$message .= 'Please do not reply directly to this email.</p>';
	$message .= '<p>Here is your password creation link:<br><br>';
	$message .= sprintf('<a href="%s">%s</a></p>', $url, $url);
	$message .= '<p>Thanks!</p>';

	$mail = new PHPMailer(true); //defaults to using php "mail()"; the true param means it will throw exceptions on errors, which we need to catch

	try {
      	//$mail->SMTPDebug = 3;                               // Enable verbose debug output

     	 $mail->isSMTP();                                      // Set mailer to use SMTP
     	 $mail->Host = 'mail.webguyinternet.com';                          // Specify main and backup SMTP servers
     	 $mail->SMTPAuth = true;                               // Enable SMTP authentication
    	  $mail->Username = 'outgoing@webguyinternet.com';      // SMTP username
    	  $mail->Password = 'outboundemails';                   // SMTP password
    	  $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
    	  $mail->Port = 465;                                    // TCP port to connect to

          $mail->AddReplyTo($email_sendFrom, $email_displayName);
          $mail->AddAddress($email);
          $mail->SetFrom($email_displayFrom, $email_displayName);
          $mail->Subject = $subject;
          $mail->AltBody = $message;
          $mail->MsgHTML($message);
          //$mail->addAttachment('/www/users/altachildrenscenter/PDFFill/output.pdf', 'Enrollment-' . trim($data['Child_Name']) . '.pdf');    // Optional name
          // $mail->AddEmbeddedImage('lib/img/success.png', 'Success');  //this is an example of how you can imbed an image/logo to the email.
          $mail->Send();
        } catch (phpmailerException $e) {
          fwrite($fp, $e->errorMessage()); //Pretty error messages from PHPMailer
        } catch (Exception $e) {
          fwrite($fp, $e->getMessage()); //Boring error messages from anything else!
	}
	
	fclose($fp);
	
/* (10-14-19)	
mysqli_close($auth_conn);	
*/

$smarty->assign('Signup_Status','signup_success');

	} else {
		$smarty->assign('Signup_Status','email_exists');	
	}

}

// END Password Reset


// END New User



//BEGIN Update User Info

// Clear out message variable
$message = "";

if(isset($_POST['update_user'])) {

	// Get POST values
	// Define variables and set to empty values
	$userid = $firstName = $lastName = $username = $old_username = $email = $department = $reservations_access = $reservations_timeslots = $reservations_newUser = $reservations_deleteUser = "";

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$userid = validate_input($_POST["userid"]);
		$firstName = validate_input($_POST["firstName"]);
		$lastName = validate_input($_POST["lastName"]);
		$username = validate_input($_POST["username"]);
		$old_username = validate_input($_POST["old_username"]);
		$email = validate_input($_POST["email"]);
		$department = validate_input($_POST["department"]);
		$reservations_access = validate_input($_POST["reservations_access"]);
		$reservations_timeslots = validate_input($_POST["reservations_timeslots"]);
		$reservations_newUser = validate_input($_POST["reservations_newUser"]);
		$reservations_deleteUser = validate_input($_POST["reservations_deleteUser"]);
	} else {
		$message = 'Unable to collect POST values';	
	}

	if ($reservations_access == '') { $reservations_access = '0'; }
	if ($reservations_timeslots == '') { $reservations_timeslots = '0'; }
	if ($reservations_newUser == '') { $reservations_newUser = '0'; }
	if ($reservations_deleteUser == '') { $reservations_deleteUser = '0'; }
	
	//echo "reservations_access: " . $reservations_access . "<br><br>";
	//echo "reservations_timeslots: " . $reservations_timeslots . "<br><br>";
	//echo "reservations_newUser: " . $reservations_newUser . "<br><br>";
	//echo "reservations_deleteUser: " . $reservations_deleteUser . "<br><br>";

	// Update User info in the Database

	/* (10-14-19)
	// Create connection
	$auth_conn = mysqli_connect($auth_servername, $auth_username, $auth_password, $auth_dbname);
	// Check connection
	if (!$auth_conn) {
		$message = "Error connecting to DB: " . mysqli_connect_error($auth_conn);
		die("Connection failed: " . mysqli_connect_error());	
	} 
	*/

	//echo "Landmark 2";

	// Check to see if email already exists in database

	$bsql = "SELECT * FROM users WHERE email = '" . $email . "' AND id != '" . $userid . "'";
	$bresults = mysqli_query($auth_conn, $bsql);
	
	//echo "bsql: " . $bsql . "<br><br>";

	if (($bresults) && ($bresults->num_rows === 0)) {

		// Update user info in the database

		$csql = "UPDATE users SET firstName = '" . $firstName . "', lastName = '" . $lastName . "', username = '" . $username . "', email = '" . $email . "', department = '" . $department . "' WHERE id='" . $userid . "'";

		//echo "csql: " . $csql . "<br><br>";

		if (mysqli_query($auth_conn, $csql)) {
			$message = "user_update_success";
			$smarty->assign('User_Message', $message);
		} else {
			$message = "user_update_failed";
			$smarty->assign('User_Message', $message);
		}
		
		$dsql = "UPDATE users_permissions SET reservations_access = '" . $reservations_access . "', reservations_timeslots = '" . $reservations_timeslots . "', reservations_newUser = '" . $reservations_newUser . "', reservations_deleteUser = '" . $reservations_deleteUser . "', username = '" . $username . "' WHERE username = '" . $old_username . "'";
		
		//echo "dsql: " . $dsql . "<br><br>";
		
		if (mysqli_query($auth_conn, $dsql)) {
			$message = "user_perms_success";
			$smarty->assign('User_Message', $message);
		} else {
			$message = "user_perms_failed";
			$smarty->assign('User_Message', $message);
		}
		
		/* (10-14-19)
		mysqli_close($conn);
		*/
		
		//echo "WE got this far...";
	
	} else {
		$smarty->assign('User_Message', 'email_exists');
	}
	
} else {

	$message = "No Post Received";
	
}

//END Update User Info



// BEGIN Delete User

if (isset($_POST['delete_username'])) {

	// Get POST values
	// Define variables and set to empty values
	$delete_userid = $delete_username = "";

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$delete_userid = validate_input($_POST["delete_userid"]);
		$delete_username = validate_input($_POST["delete_username"]);
	} else {
		$message = 'Unable to collect POST values';	
	}

	/* (10-14-19)
	// Create connection
	$auth_conn = mysqli_connect($auth_servername, $auth_username, $auth_password, $auth_dbname);
	// Check connection
	if (!$auth_conn) {
		die("Connection failed: " . mysqli_connect_error());	
	}
	*/
	
	$wsql = "SELECT protected FROM users_permissions WHERE protected = '1' AND username = '" . $delete_username . "';";
	$wresults = mysqli_query($auth_conn, $wsql);
	
	if (($wresults) && ($wresults->num_rows === 0)) {
	
		$ysql = "DELETE FROM users WHERE users.id = '" . $delete_userid . "' AND users.username = '" . $delete_username . "'";
	
		if (mysqli_query($auth_conn, $ysql)) {
			$message = "user_delete_success";
			$smarty->assign('User_Message', $message);
		} else {
			$message = "user_delete_failed";
			$smarty->assign('User_Message', $message);
		}
	
		$xsql = "DELETE FROM users_permissions WHERE users_permissions.username = '" . $delete_username . "'";
	
		if (mysqli_query($auth_conn, $xsql)) {
			$message = "perms_delete_success";
			$smarty->assign('User_Message', $message);
		} else {
			$message = "perms_delete_failed";
			$smarty->assign('User_Message', $message);
		}
	} else {
		$message = "user_protected";
		$smarty->assign('User_Message', $message);
	}
	
	/* (10-14-19)
	mysqli_close($auth_conn);
	*/
}

// END Delete User







//BEGIN Update Settings

// Clear out message variable
$message = "";

if(isset($_POST['update_settings'])) {

	// Get POST values
	// Define variables and set to empty values
	$reservations_6pm_ooh = $reservations_8pm_ooh = $enable_table_num = "";

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$reservations_6pm_ooh = validate_input($_POST["reservations_6pm_ooh"]);
		$reservations_8pm_ooh = validate_input($_POST["reservations_8pm_ooh"]);
		$enable_table_num = validate_input($_POST["enable_table_num"]);
		$enable_table_minimum = validate_input($_POST["enable_table_minimum"]);
	} else {
		$message = 'Unable to collect POST values';	
	}

	if ($reservations_6pm_ooh == '') { $reservations_6pm_ooh = '0'; }
	if ($reservations_8pm_ooh == '') { $reservations_8pm_ooh = '0'; }
	if ($enable_table_num == '') { $enable_table_num = '0'; }
	if ($enable_table_minimum == '') { $enable_table_minimum = '0'; }
	
	
	//echo "reservations_8pm_ooh: " . $reservations_8pm_ooh . "<br><br>";

	// Update Settings in the Database

	/* (10-14-19)
	// Create connection
	$auth_conn = mysqli_connect($auth_servername, $auth_username, $auth_password, $auth_dbname);
	// Check connection
	if (!$auth_conn) {
		$message = "Error connecting to DB: " . mysqli_connect_error($auth_conn);
		die("Connection failed: " . mysqli_connect_error());	
	} 
	*/

	//echo "Landmark 2";

	// Update user info in the database

	$aasql = "UPDATE config SET 6pm_ooh = '" . $reservations_6pm_ooh . "', 8pm_ooh = '" . $reservations_8pm_ooh . "', enable_table_num = '" . $enable_table_num . "', enable_table_minimum = '" . $enable_table_minimum . "'";

	//echo "aasql: " . $aasql . "<br><br>";

	if (mysqli_query($auth_conn, $aasql)) {
		$message = "settings update success";
		$smarty->assign('User_Message', $message);
	} else {
		$message = "settings update failed";
		$smarty->assign('User_Message', $message);
	}
	
	/* (10-14-19)	
	mysqli_close($conn);
	*/	
		
	//echo "WE got this far...";
	
} else {

	$message = "No Post Received";
	
}

//END Update Settings









// BEGIN Show list of active users

/* (10-14-19)
// Create connection
$auth_conn = mysqli_connect($auth_servername, $auth_username, $auth_password, $auth_dbname);
// Check connection
if (!$auth_conn) {
	die("Connection failed: " . mysqli_connect_error());	
}
*/

$asql = "SELECT users.id, users.username, users.active, users.firstName, users.lastName, users.department, users.email, users.date_last, users_permissions.reservations_access, users_permissions.reservations_newUser, users_permissions.reservations_deleteUser, users_permissions.reservations_timeslots, users_permissions.reservations_tables, users_permissions.reservations_limit_override FROM users JOIN users_permissions ON users.username = users_permissions.username AND users.active = '1' AND users.hidden = '0' ORDER BY users.lastName ASC;";

if ($query = mysqli_query($auth_conn, $asql)) {
	while ($row = mysqli_fetch_row($query)) {
		$arows[] = $row;
	}
	$smarty->assign('User_Info', $arows);
	mysqli_free_result($query);
} else {
	echo "Error: " . mysqli_connect_error($conn);
	//$message = "Error: " . mysqli_connect_error($conn);
}

/* (10-14-19)
mysqli_close($auth_conn);
*/

// END Show list of active users








// BEGIN Show Settings

/* (10-14-19)
// Create connection
$auth_conn = mysqli_connect($auth_servername, $auth_username, $auth_password, $auth_dbname);
// Check connection
if (!$auth_conn) {
	die("Connection failed: " . mysqli_connect_error());	
}
*/

$bsql = "SELECT 6pm_ooh, 8pm_ooh, enable_table_num, enable_table_minimum FROM config;";

if ($query = mysqli_query($auth_conn, $bsql)) {
	while ($row = mysqli_fetch_row($query)) {
		$brows[] = $row;
	}
	$smarty->assign('Settings', $brows);
	mysqli_free_result($query);
} else {
	echo "Error: " . mysqli_connect_error($conn);
	//$message = "Error: " . mysqli_connect_error($conn);
}

/* (10-14-19)
mysqli_close($auth_conn);
*/

// END Show Settings






//echo '<pre>' . print_r($arows, TRUE) . '</pre>';

// Close connection to Auth database
disconnect_auth();

// Close connection to Res database
disconnect_res();

$smarty->display('res_admin.tpl');

?>