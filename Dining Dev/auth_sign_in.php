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

//echo '<pre>' . print_r($_SESSION, TRUE) . '</pre>';

//echo '<pre>' . print_r($_POST, TRUE) . '</pre>';


// BEGIN Password Reset


if(isset($_POST["Reset"])) {

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
	
	if (mysqli_query($auth_conn, $ysql)) {
		$message = "Existing tokens deleted";
		//echo $message . "<br>";
	} else {
		$message = "Error deleting tokens: " . mysqli_connect_error() . PHP_EOL; 	
		//echo $message . "<br>";
	}

	// Insert reset token into database
	$hashed_token = hash('sha256', $token);
	$formatted_expires = $expires->format('U');
	$xsql = "INSERT INTO password_reset (email, selector, token, expires) VALUES ('$email', '$selector', '$hashed_token', '$formatted_expires')";
	
	if (mysqli_query($auth_conn, $xsql)) {
		$message = "Token inserted into database";
		//echo $message . "<br>";
	} else {
		$message = "Error inserting tokens: " . mysqli_connect_error() . PHP_EOL; 	
		//echo $message . "<br>";
	}


	// Send the email
	
	// Details for the PHP mailer Class are here:
	//      https://github.com/PHPMailer/PHPMailer

	$fp = fopen('request.log', 'w');

	// Subject
	$subject = 'Your password reset link';

	// Message
	$message = '<p>We recieved a password reset request. The link to reset your password is below.<br>';
	$message .= 'If you did not make this request, you can ignore this email. ';
	$message .= 'Please do not reply directly to this email.</p>';
	$message .= '<p>Here is your password reset link:<br><br>';
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

$smarty->display('reset_email_sent.tpl');
exit;
}

// END Password Reset








// BEGIN Login 

if(isset($_POST["Submit"])) {
	
	//print_r($_POST);
	//echo "<br><br>";
	
	$username = $_POST["username"];
	
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
	
	$asql = "SELECT password, active FROM users WHERE username = '" . $username . "';";
	
	//echo "sql: " . $sql . "<br><br>";
	
	$UserData = mysqli_query($auth_conn, $asql);
	if((mysqli_num_rows($UserData)) > 0) {
		// Fetch all the rows in an array
    	$rows = array();
    	while ($row = mysqli_fetch_assoc($UserData)) {
           $rows[] = $row;
    	}
		
		//print_r($rows);
		//echo "<br><br>";
		
		$hashed_password = $rows[0]['password'];
		$active = $rows[0]['active'];
		$password = $_POST["password"];
		
		//echo $hashed_password . "<br><br>";
		//echo $password . "<br><br>";
		//echo $active . "<br><br>";
	}
	
	if(password_verify($password, $hashed_password)) {
		if($active == '1'){
			
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
		
		  	$bsql = "select users.id, users.username, users.active, users.firstName, users.lastName, users.department, users.email, users_permissions.reservations_access, users_permissions.reservations_newUser, users_permissions.reservations_deleteUser, users_permissions.reservations_timeslots, users_permissions.reservations_tables, users_permissions.reservations_limit_override, users_permissions.reservations_ooh_1 FROM users left join users_permissions on users.username=users_permissions.username WHERE users.username = '$username'";
	  		$UserData = mysqli_query($auth_conn, $bsql);
	  		if((mysqli_num_rows($UserData)) > 0) {
				// Fetch all the rows in an array
    			$rows = array();
    			while ($row = mysqli_fetch_assoc($UserData)) {
          			$rows[] = $row;
    			}

	    		// Close our Dataset.
	    		mysqli_free_result($UserData);
	    		$csql = "UPDATE users SET date_last = NOW() WHERE users.username = '$username'";
	    		$UserUpdate = mysqli_query($auth_conn, $csql);
	    		if($UserUpdate === false) { $message = "Update of User Failed."; die; }
	   	  		
				if($rows[0]['active'] != '0') {
					$host = $_SERVER['SERVER_NAME'];
					$uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');

					session_start();
					// Assign existing database entries to session.
					$_SESSION["userid"] = $rows[0]['id'];
					$_SESSION["username"] = $rows[0]['username'];
					$_SESSION["user_firstName"] = $rows[0]['firstName'];
					$_SESSION["user_lastName"] = $rows[0]['lastName'];
					$_SESSION["user_email"] = $rows[0]['email'];
					$_SESSION["user_department"] = $rows[0]['department'];
					$_SESSION["perms_res_access"] = $rows[0]['reservations_access'];
					$_SESSION["perms_res_newUser"] = $rows[0]['reservations_newUser'];
					$_SESSION["perms_res_deleteUser"] = $rows[0]['reservations_deleteUser'];
					$_SESSION["perms_res_timeslots"] = $rows[0]['reservations_timeslots'];
					$_SESSION["perms_res_tables"] = $rows[0]['reservations_tables'];
					$_SESSION["perms_limit_override"] = $rows[0]['reservations_limit_override'];
					$_SESSION["perms_ooh_1"] = $rows[0]['reservations_ooh_1'];
					// Assign current date to SESSION
					$_SESSION["selected_date"] = date('Y-m-d');
					header("Location: https://$host$uri/");
					//echo '<pre>' . print_r($_SESSION, TRUE) . '</pre>';
					exit;
				} else { 
					//echo "User Deactivated!"; die; 
				}	
	  		}
		} else {
			//echo "User inactive";
		}
	} else {
		$smarty->assign('Login_Status','login_failed');
		//echo "Login unsuccessful.";
	}

/* (10-14-19)
mysqli_close($auth_conn);
*/

}

// END Login

// Close connection to Auth database
disconnect_auth();

// Close connection to Res database
disconnect_res();

$smarty->display('auth_sign_in.tpl');

?>