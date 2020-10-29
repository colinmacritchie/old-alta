<?php

//error_reporting(E_ALL);
//ini_set('display_errors',1);

include 'DB/dbaccess.php';


// BEGIN Create connection to auth database

function connect_auth() {

	global $auth_servername, $auth_username, $auth_password, $auth_dbname, $auth_conn;

	$auth_conn = mysqli_connect($auth_servername, $auth_username, $auth_password, $auth_dbname);
	if (!$auth_conn) {
		$message = "Error connecting to database: " . 	mysqli_connect_error() . PHP_EOL;
		die(mysqli_connect_error());
		//echo "auth connection: " . $message;
	} else {
		$message = "Connection successful";	
		//echo "auth connection: " . $message;
	}
	
}

// END Create connection to auth database

// BEGIN Close connection to auth database

function disconnect_auth() {
	
	global $auth_servername, $auth_username, $auth_password, $auth_dbname, $auth_conn;
	
	$auth_conn = mysqli_connect($auth_servername, $auth_username, $auth_password, $auth_dbname);
	mysqli_close($auth_conn);	

}

// End Close connection to auth database




// BEGIN Create connection to Res database

function connect_res() {
	
	global $servername, $username, $password, $dbname, $conn;

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if (!$conn) {
		$message = "Error connecting to DB: " . mysqli_connect_error($conn);
		die("Connection failed: " . mysqli_connect_error());	
	} 

}

// END Create connection to Res database

// BEGIN Close connection to Res database

function disconnect_res() {
	
	global $servername, $username, $password, $dbname, $conn;
	
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	mysqli_close($conn);	

}

// End Close connection to Res database





// Get config variables
function get_config() {
	
	/*
	//Old global variables with own db connection (11-14-19)
	
	global $auth_servername, $auth_username, $auth_password, $auth_dbname, $config_array, $theme_logo, $theme_color, $theme_color_secondary, $full_url, $full_path, $email_sendFrom, $email_displayFrom, $email_displayName, $res_6pm_ooh, $res_8pm_ooh, $enable_table_num;
	
	*/
	
	global $auth_conn, $config_array, $theme_logo, $theme_color, $theme_color_secondary, $full_url, $full_path, $email_sendFrom, $email_displayFrom, $email_displayName, $res_6pm_ooh, $res_8pm_ooh, $enable_table_num, $enable_table_minimum, $res_table, $auth_table, $config_name, $tab_1_name, $tab_2_name, $tab_3_name, $tab_4_name, $tab_5_name, $tab_6_name;
	
	
	/* (11-14-19)
	
	// Create connection to auth database
	$auth_conn = mysqli_connect($auth_servername, $auth_username, $auth_password, $auth_dbname);
	if (!$auth_conn) {
		$message = "Error connecting to database: " . 	mysqli_connect_error() . PHP_EOL;
		die(mysqli_connect_error());
	} else {
		$message = "Connection successful";	
	}
	
	*/
	
	$csql = "SELECT * FROM config";
	if ($cquery = mysqli_query($auth_conn, $csql)) {
		while ($row = mysqli_fetch_assoc($cquery)) {
			$config_array[] = $row;
		}
		
		$theme_logo = $config_array[0]['theme_logo'];
		$theme_color = $config_array[0]['theme_color'];
		$theme_color_secondary = $config_array[0]['theme_color_secondary'];
		$full_url = $config_array[0]['full_url'];
		$full_path = $config_array[0]['full_path'];
		$email_sendFrom = $config_array[0]['email_sendFrom'];
		$email_displayFrom = $config_array[0]['email_displayFrom'];
		$email_displayName = $config_array[0]['email_displayName'];
		$res_6pm_ooh = $config_array[0]['6pm_ooh'];
		$res_8pm_ooh = $config_array[0]['8pm_ooh'];
		$enable_table_num = $config_array[0]['enable_table_num'];
		$enable_table_minimum = $config_array[0]['enable_table_minimum'];
		$res_table = $config_array[0]['res_table'];
		$auth_table = $config_array[0]['auth_table'];
		$config_name = $config_array[0]['name'];
		$tab_1_name = $config_array[0]['tab_1_name'];
		$tab_2_name = $config_array[0]['tab_2_name'];
		$tab_3_name = $config_array[0]['tab_3_name'];
		$tab_4_name = $config_array[0]['tab_4_name'];
		$tab_5_name = $config_array[0]['tab_5_name'];
		$tab_6_name = $config_array[0]['tab_6_name'];
		
		mysqli_free_result($cquery);
		
		/* (11-14-19)
		mysqli_close($auth_conn);
		*/
	} else {
		echo "Error loading config: " . mysqli_connect_error($auth_conn);
		//$message = "Error: " . mysqli_connect_error($conn);
	}	
}



// Set SMARTY paths
function smarty_paths() {
	
	global $smarty, $full_path;
	
	$smarty->setTemplateDir($full_path . 'Portal/Reservations/tpl');
	$smarty->setCompileDir($full_path . 'Portal/Reservations/smarty/templates_c');
	$smarty->setCacheDir($full_path . 'Portal/Reservations/smarty/cache');
	$smarty->setConfigDir($full_path . 'Portal/Reservations/smarty/configs');	
}




// Assign config variables
function assign_config() {
	
	global $smarty, $theme_logo, $theme_color, $theme_color_secondary, $full_url, $full_path, $email_sendFrom, $email_displayFrom, $email_displayName, $res_6pm_ooh, $res_8pm_ooh, $enable_table_num, $tab_1_name, $tab_2_name, $tab_3_name, $tab_4_name, $tab_5_name, $tab_6_name;
	
	$smarty->assign('serverself', $_SERVER['PHP_SELF']);
	$smarty->assign('theme_logo', $theme_logo);
	$smarty->assign('theme_color', $theme_color);
	$smarty->assign('full_url', $full_url);
	$smarty->assign('full_path', $full_path);
	$smarty->assign('email_sendFrom', $email_sendFrom);
	$smarty->assign('email_displayFrom', $email_displayFrom);
	$smarty->assign('email_displayName', $email_displayName);
	$smarty->assign('res_6pm_ooh', $res_6pm_ooh);
	$smarty->assign('res_8pm_ooh', $res_8pm_ooh);		
	$smarty->assign('enable_table_num', $enable_table_num);
	$smarty->assign('enable_table_minimum', $enable_table_minimum);
	$smarty->assign('tab_1_name', $tab_1_name);
	$smarty->assign('tab_2_name', $tab_2_name);
	$smarty->assign('tab_3_name', $tab_3_name);
	$smarty->assign('tab_4_name', $tab_4_name);
	$smarty->assign('tab_5_name', $tab_5_name);
	$smarty->assign('tab_6_name', $tab_6_name);
}




// BEGIN Validate data
function validate_input($data) {
	
	$remove[] = "'";
	$remove[] = '"';
	// Need to keep dashes for affiliation names...
	//$remove[] = "-";
	
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	$data = str_replace($remove, "", "$data");
	return $data;
}
// END Validate Data


// BEGIN Validate SMS data
	function validate_sms($data) {
		
		$remove[] = "(";
		$remove[] = ")";
		$remove[] = "-";
		
		$data = str_replace($remove, "", "$data");
		return $data;
	}
// END Validate Data


// BEGIN House Adult Total Seatings

function houseAdultTotal() {


	/* (11-14-19)
	global $smarty, $servername, $username, $password, $dbname;
	*/
	
	global $smarty, $conn;

	/* (11-14-19)
	// Create connection
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	// Check connection
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());	
	}
	*/

	$xsql = "SELECT SUM(party_adults) FROM Reservations WHERE res_date = CURRENT_DATE AND res_status != '2'";

	//echo "xsql: " . $xsql . "<br><br>";

	if ($xquery = mysqli_query($conn, $xsql)) {
		while ($row = mysqli_fetch_assoc($xquery)) {
				$xresult[] = $row;
		}
		$adults_total = $xresult[0]['SUM(party_adults)'];
		$smarty->assign('House_Adults_Total', $adults_total);
		mysqli_free_result($xresult);
	} else {
		//echo "Error House Total: " . mysqli_connect_error($conn);
		//$message = "Error: " . mysqli_connect_error($conn);
	}


	/* (11-14-19)
	mysqli_close($conn);
	*/
}

// END House Adult Total Seatings


// BEGIN House Children Total Seatings

function houseChildrenTotal() {

	/* (11-14-19)	
	global $smarty, $servername, $username, $password, $dbname;
	*/
	
	global $smarty, $conn;

	/* (11-14-19)
	// Create connection
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	// Check connection
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());	
	}
	*/

	$ysql = "SELECT SUM(party_children) FROM Reservations WHERE res_date = CURRENT_DATE AND res_status != '2'";

	if ($yquery = mysqli_query($conn, $ysql)) {
		while ($row = mysqli_fetch_assoc($yquery)) {
				$yresult[] = $row;
		}
		$children_total = $yresult[0]['SUM(party_children)'];
		$smarty->assign('House_Children_Total', $children_total);
		mysqli_free_result($result);
	} else {
		//echo "Error House Total: " . mysqli_connect_error($conn);
		//$message = "Error: " . mysqli_connect_error($conn);
	}

	/* (11-14-19)
	mysqli_close($conn);
	*/

}

// END House Children Total Seatings






// BEGIN Party Total Seatings

function housePartyTotal() {

	/* (11-14-19)
	global $smarty, $servername, $username, $password, $dbname;
	*/
	
	global $smarty, $conn;

	/* (11-14-19)
	// Create connection
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	// Check connection
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());	
	}
	*/

$xsql = "SELECT party_num FROM Reservations JOIN Timeblocks ON Reservations.block_id = Timeblocks.block_id AND res_date = curdate() AND Reservations.res_status != '2'";

if ($result = mysqli_query($conn, $xsql)) {
	$xheadcount = "";
	while ($row = mysqli_fetch_row($result)) {
		$xheadcount += $row[0]; 
	}
	$smarty->assign('House_Party_Total', $xheadcount);	
	mysqli_free_result($result);
} else {
	echo "Error: " . mysqli_connect_error($conn);
	//$message = "Error: " . mysqli_connect_error($conn);
}

/* (11-14-19)
mysqli_close($conn);
*/

}

// END Party Total Seatings








// BEGIN Latest Guest totals

function lodgeGuestTotals() {
	
	/* (11-14-19)
	global $smarty, $servername, $username, $password, $dbname;
	*/
	
	global $smarty, $conn;

	/* (11-14-19)
	// Create connection
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	// Check connection
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());	
	}
	*/

//$wsql = "SELECT house_adults, house_children, lodge_adults, lodge_children, timestamp FROM Res_Count WHERE timestamp = (SELECT MAX(timestamp) FROM Res_Count);";
$wsql = 'SELECT house_adults, house_children, lodge_adults, lodge_children, timestamp FROM Res_Count WHERE timestamp = (SELECT MAX(timestamp) FROM Res_Count) AND timestamp >= CURRENT_DATE;';

if ($wquery = mysqli_query($conn, $wsql)) {
	while ($row = mysqli_fetch_assoc($wquery)) {
			$wresult[] = $row;
	}
	$lodge_adults_total = $wresult[0]['lodge_adults'];
	$lodge_children_total = $wresult[0]['lodge_children'];
	
	if ($lodge_adults_total == '') {
		$lodge_adults_total = '0';
	}
	
	if ($lodge_children_total == '') {
		$lodge_children_total = '0';
	}
	
	$smarty->assign('Lodge_Adults_Total', $lodge_adults_total);
	$smarty->assign('Lodge_Children_Total', $lodge_children_total);
	
	//echo $lodge_adults_total . "<br><br>";
	//echo $lodge_children_total . "<br><br>";
	
	mysqli_free_result($result);
} else {
	//echo "Error House Total: " . mysqli_connect_error($conn);
	//$message = "Error: " . mysqli_connect_error($conn);
}

/* (11-14-19)
mysqli_close($conn);
*/

}

// END Latest Guest totals



// BEGIN RDP Guest totals

function RDPGuestTotals() {
	
	/* (11-14-19)
	global $selected_date, $smarty, $servername, $username, $password, $dbname;
	*/
	
	global $selected_date, $smarty, $conn;

	/* (11-14-19)
	// Create connection
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	// Check connection
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());	
	}
	*/
	
// Beginnings of Query for RDP totals
//$zzsql = "SELECT People1, People2, People3, People4 FROM Res_RDP JOIN Reservations ON Reservations.ResNum = Res_RDP.ResNumNumberic AND res_date = curdate() AND Reservations.res_status != '2'";

//$zzsql = "SELECT party_num, gog_num FROM Reservations WHERE res_date = '" . $selected_date . "' AND res_status = '1';"; 
$zzsql = "SELECT party_num, gog_num FROM Reservations WHERE res_date = '" . $selected_date . "' AND res_status != '2';";	

if ($result = mysqli_query($conn, $zzsql)) {
	$zztotalheadcount = 0;
	$zzheadcount1 = 0;
	$zzheadcount2 = 0;
	while ($row = mysqli_fetch_row($result)) {
		$zzheadcount1 += $row[0];
		$zzheadcount2 += $row[1];  
		//echo "zzheadcount1: " . $zzheadcount1 . "<br><br>";
		//echo "zzheadcount2: " . $zzheadcount2 . "<br><br>";
	}
	$zztotalheadcount = $zzheadcount1 + $zzheadcount2;
	
	//echo "TOTAL zzheadcount1: " . $zzheadcount1 . "<br><br>";
	//echo "TOTAL zzheadcount2: " . $zzheadcount2 . "<br><br>";
	
	$smarty->assign('RDP_Guest_Total', $zztotalheadcount);	
	mysqli_free_result($result);
} else {
	echo "Error: " . mysqli_connect_error($conn);
	//$message = "Error: " . mysqli_connect_error($conn);
}

/* (11-14-19)
mysqli_close($conn);
*/

}

// END RDP Guest totals



// BEGIN unread notifications

function unread_notification_list() {
	
	global $smarty, $servername, $username, $password, $dbname;

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());	
}

$sms_unread = "SELECT users_sms.fromNumber, users_sms.manual_timestamp, users_sms.body, Reservations.party_name, Reservations.room_num, Timeblocks.block_time FROM users_sms JOIN Reservations ON users_sms.fromNumber = Reservations.phone JOIN Timeblocks ON Reservations.block_id = Timeblocks.block_id AND Reservations.res_status != '2' AND users_sms.message_read = 0 ORDER BY users_sms.manual_timestamp DESC LIMIT 20";

//echo "This is the sms_unread query: " . $sms_unread . "<br><br>";

if ($query = mysqli_query($conn, $sms_unread)) {
	while ($row = mysqli_fetch_row($query)) {
		$sms_unread_rows[] = $row;
		//echo '<pre>' . print_r($sms_history_rows{$i}, TRUE) . '</pre>';
	}
	
	//echo '<pre>' . print_r($sms_unread_rows, TRUE) . '</pre>';
	
	$smarty->assign('SMS_unread', $sms_unread_rows);
	mysqli_free_result($query);
} else {
	//echo "Error: " . mysqli_connect_error($conn);
	//$message = "Error: " . mysqli_connect_error($conn);
}

mysqli_close($conn);

}

// End unread notifications



// BEGIN mark notifications as read

function mark_as_read() {

	global $smarty, $servername, $username, $password, $dbname;

$mark_read = "";

if(isset($_POST['mark_read'])) {

	$mark_read_sql = "UPDATE users_sms SET message_read = 1 WHERE message_read = 0;";
	
	// Create connection
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	// Check connection
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());	
	}
	
	if ($query = mysqli_query($conn, $mark_read_sql)) {
		//echo "Updated db. Marked messages as read<br><br>";
	mysqli_free_result($query);
	} else {
		//echo "Error: " . mysqli_connect_error($conn);
		//$message = "Error: " . mysqli_connect_error($conn);
	}
	mysqli_close($conn);
}

}

// END mark notifications as read




// BEGIN Unread Notification Count

function unread_notification_count() {
	
	//echo "We got this far on houseAdultTotal<br><br>";
	
	global $smarty, $servername, $username, $password, $dbname;

	// Create connection
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	// Check connection
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());	
	}

	$unread_sql = "SELECT COUNT(*) as unread_total FROM users_sms WHERE message_read = 0 AND fromNumber <> '+18015095311'";

	//echo "xsql: " . $xsql . "<br><br>";
	
	if ($unread_query = mysqli_query($conn, $unread_sql)) {
		while ($row = mysqli_fetch_assoc($unread_query)) {
				$unread_result[] = $row;
		}
		$unread_total = $unread_result[0]['unread_total'];
		$smarty->assign('Unread_Total', $unread_total);
		mysqli_free_result($unread_result);
	} else {
		//echo "Error House Total: " . mysqli_connect_error($conn);
		//$message = "Error: " . mysqli_connect_error($conn);
	}

	mysqli_close($conn);

}

// END Unread Notification Count






?>