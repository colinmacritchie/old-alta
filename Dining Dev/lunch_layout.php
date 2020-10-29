<?php

$tab_meal = 'lunch';

// Continue session variables
session_start();

// Turn error reporting on/off
//error_reporting(E_ALL);
//ini_set('display_errors',1);

include 'DB/dbaccess.php';
include '../lib/php/global.php';
include 'res_func.php';

// Autoload composer
//require __DIR__ . '../vendor/autoload.php';
require '../vendor/autoload.php';

// Use the REST API Client to make requests to the Twilio REST API
use Twilio\Rest\Client;

// Your Account SID and Auth Token from twilio.com/console
$account_sid = "ACf51efbdb6a43d227cbc3c5590611b99a";
$auth_token = "e652ac1f9c33b56d3d14f0edf2664793";

// Old Account info:
//$account_sid = 'AC3e4be570e548e956dc5c1113e383849f';
//$auth_token = '346cef2864e6ac979824f04a131a8f05';


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
//$smarty->assign('perms_res_timeslots', $_SESSION['perms_res_timeslots']);
$smarty->assign('perms_res_tables', $_SESSION['perms_res_tables']);
$smarty->assign('selected_date', $_SESSION['selected_date']);
$smarty->assign('perms_limit_override', $_SESSION["perms_limit_override"]);
$smarty->assign('perms_res_ooh_1', $_SESSION["perms_ooh_1"]);


if (!$_SESSION['username']) {
	header("Location: auth_sign_in.php");	
	exit;
}

if (!$_SESSION['perms_res_access']) {
	header("Location: no_access.php");	
	exit;
}

$selected_date = $_SESSION['selected_date'];

// Settings for this tab (breakfast, lunch, and dinner)
$default_active =  $tab_meal . '_default_active';
$active = $tab_meal . '_active';
$enabled_times = 'enabled_' . $tab_meal;
switch ($tab_meal) {
	case "dinner":
		$res_type = 3;
		break;
	case "lunch":
		$res_type = 2;
		break;
	case "breakfast":
		$res_type = 1;
		break;
	default:
		$res_type = 0;	
}

//echo '<pre>' . print_r($_POST, TRUE) . '</pre>'; 
//echo '<pre>' . print_r($_SESSION, TRUE) . '</pre>';



// BEGIN Change selected date

if(isset($_POST['newdate'])){

	//echo '<pre>' . print_r($_POST, TRUE) . '<pre>';	
	
	// Get POST values
	// Define variables and set to empty values
	$unformatted_date = "";
	
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$unformatted_date = validate_input($_POST["newdate"]);
	} else {
		$message = 'Unable to collect POST values';
		//echo $message;	
	}
	
	//echo $unformatted_date . "<br><br>";
	
	$date_filter = strtotime($unformatted_date);
	
	$new_date = date('Y-m-d', $date_filter);
	
	//echo $new_date . "<br><br>";
	
	
	
	if ($new_date == $_SESSION['selected_date']) {
		$message = "No date change";
		//echo $message;
	} else {
		$_SESSION['selected_date'] = $new_date;
		$selected_date = $_SESSION['selected_date'];
		$smarty->assign('selected_date', $_SESSION['selected_date']);		
		//echo "Date Updated<br><br>";
	}	
	
}

// BEGIN Change selected date




//BEGIN Update Reservation

// Clear out message variable
$message = "";

if(isset($_POST['submit'])) {
	
	//echo '<pre>' . print_r($_POST, TRUE) . '</pre>';

	// Get POST values
	// Define variables and set to empty values
	$gog_num = $out_of_hotel = $party_name = $room_num = $party_num = $party_adults = $party_children = $original_res_date = $change_res_date = $original_res_time = $change_res_time = $res_id = $original_block_id = $change_table_time = $food_requests = $special_requests = $notes = $actual_table = $table_num = $original_gog_num = $original_party_num = $res_time = $new_table_num = $new_res_time = $block_id_changed = $table_max = $wasatch = "";

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$gog_num = validate_input($_POST["gog_num"]);
		$out_of_hotel = validate_input($_POST["out_of_hotel"]);
		$party_name = validate_input($_POST["party_name"]);
		$room_num = validate_input($_POST["room_num"]);
		$party_num = validate_input($_POST["party_num"]);
		$party_adults = validate_input($_POST["party_adults"]);
		$party_children = validate_input($_POST["party_children"]);
		$original_res_date = validate_input($_POST["original_res_date"]);
		$change_res_date = validate_input($_POST["change_res_date"]);
		$change_res_time = validate_input($_POST["change_res_time"]);
		$original_res_time = validate_input($_POST["original_res_time"]);
		$res_id = validate_input($_POST["res_id"]);
		$original_block_id = validate_input($_POST["original_block_id"]);
		$change_table_time = validate_input($_POST["change_table_time"]);
		$food_requests = validate_input($_POST["food_requests_og"]);
		$actual_table = validate_input($_POST["actual_table"]);
		$special_requests = validate_input($_POST["special_requests"]);
		$table_num = validate_input($_POST["table_num"]);
		$original_gog_num = validate_input($_POST["original_gog_num"]);
		$original_party_num = validate_input($_POST["original_party_num"]);
		$res_time = validate_input($_POST["res_time"]);
		$notes = validate_input($_POST["notes"]);
		$table_max = validate_input($_POST["table_max"]);
		$wasatch = validate_input($_POST["wasatch"]);
		$block_id_changed = '0';
	} else {
		$message = 'Unable to collect POST values';	
	}
	


// Old code--for this Reservations version, we don't pick the next available timeblock, but pick a specific timeblock from the select list
/*

If($original_res_time !== $res_time){
	
	// Use POST res_time to get next available timeslot in that Reservation Time
	
	// Create connection
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	// Check connection
	if (!$conn) {
		$message = "Error connecting to DB: " . mysqli_connect_error($conn);
		die("Connection failed: " . mysqli_connect_error());	
	} 
	
	$zsql = "SELECT Timeblocks.block_id, Timeblocks.block_time, MIN(Timeblocks.block_number) FROM Timeblocks WHERE NOT EXISTS (SELECT NULL FROM Reservations WHERE Timeblocks.block_id = Reservations.block_id AND Reservations.res_date = '" . $_SESSION['selected_date'] . "') AND Timeblocks.active = '1' AND Timeblocks.block_time = '" . $res_time . "'";
	
	if ($query = mysqli_query($conn, $zsql)) {
		while ($row = mysqli_fetch_assoc($query)) {
			$zrows[] = $row;
		}
		$block_id = $zrows[0]['block_id'];
		mysqli_free_result($query);
	} else {
		echo "Error New Res: " . mysqli_connect_error($conn);
		//$message = "Error: " . mysqli_connect_error($conn);
	}

	mysqli_close($conn);
	
}

*/
	
	// Update Reservation in the Database


	/* (11-14-19)
	// Create connection
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	// Check connection
	if (!$conn) {
		$message = "Error connecting to DB: " . mysqli_connect_error($conn);
		die("Connection failed: " . mysqli_connect_error());	
	} 
	*/
	
	// Storing the res_time_id and table_id for the Reservation before being updated in case it's needed later.
	
	$reorder_sql = "SELECT Timeblocks.block_id, Timeblocks.duplicate, Timeblocks.res_time_id, Timeblocks.table_id, Reservations.res_id FROM Reservations JOIN Timeblocks ON Timeblocks.block_id = Reservations.block_id WHERE Reservations.res_id = '" . $res_id . "';";
		
	//echo "reorder_sql: " . $reorder_sql . "<br><br>";
	
	$original_res_time_id = '';
	$original_table_id = '';
	$reorder_result = mysqli_query($conn, $reorder_sql);
	while ($row = mysqli_fetch_assoc($reorder_result)){
		$original_res_time_id = $row['res_time_id'];
		$original_table_id = $row['table_id'];
	}
		
	//echo "original_res_time_id: " . $original_res_time_id . "<br><br>";
	//echo "original_table_id: " . $original_table_id . "<br><br>";
	
	
	// Setting up changes to the party count record
	
	
	$old_party_count2 = $original_party_num + $original_gog_num;
	$new_party_count2 = $party_num + $gog_num;
	
	//echo "old_party_count: " . $old_party_count2 . "<br><br>";
	//echo "new_party_count: " . $new_party_count2 . "<br><br>";
	
	if ($old_party_count2 !== $new_party_count2){
		$count_difference2 = $new_party_count2 - $old_party_count2;
	}
	
	//echo "count_difference2: " . $count_difference2 . "<br><br>";
			
	$count_check_sql2 = "SELECT party_count FROM Res_Table_Count WHERE res_date = '" . $original_res_date . "' AND table_num = '" . $table_num . "' AND res_time = '" . $res_time . "';";
	
	//echo "count_check_sql2: " . $count_check_sql2 . "<br><br>";
			
	$count_check_result2 = mysqli_query($conn, $count_check_sql2);
			
	$res_table_count2 = '';
	while ($row = mysqli_fetch_assoc($count_check_result2)) {
		//echo '<pre>' . print_r($row, TRUE) . '</pre>';
		$res_table_count2 = $row['party_count'];	
		//echo "row0: " . $row['party_count'] . "<br><br>";
	}
	
	$fake_break = '0';
	$total_party_size = $res_table_count2 + $count_difference2;
	
	if (($total_party_size > $table_max) && ($wasatch == '0')) {
		$alert_message = "Unable to update Reservation, party size too large: Party Size: " . $total_party_size . ", Table Capacity: " . $table_max . ".";
		echo "<script type='text/javascript'>alert('$alert_message');</script>";
		$fake_break = '1';
	}
	mysqli_free_result($count_check_result2);
	
	if ($fake_break == '0'){
		
		
		
		
		
		
		
		
		
		

	if($change_table_time != '') {
		
		$result_explode = explode(',', $change_table_time);
		$change_block_id = $result_explode[0];
		$new_table_num = $result_explode[1];
		$new_res_time = $result_explode[2];
		
		//echo "change_block_id: " . $change_block_id . "<br><br>";
		//echo "new_table_num: " . $new_table_num . "<br><br>";
		//echo "new_res_time: " . $new_res_time . "<br><br>";
		
		if($change_block_id != $original_block_id) {
			$block_id = $change_block_id;	
			$block_id_changed = '1';
			
			$duplicate_check = "SELECT duplicate from Timeblocks WHERE block_id = '" . $original_block_id . "';";
			$duplicate_check_result = mysqli_query($conn, $duplicate_check);
			
			while ($row = mysqli_fetch_assoc($duplicate_check_result)) {
				//echo '<pre>' . print_r($row, TRUE) . '</pre>';
				$is_duplicate = $row['duplicate'];	
				//echo "row0: " . $row['duplicate'] . "<br><br>";
			}	
		} else {
			$block_id = $original_block_id;	
		}
	} else {
			$block_id = $original_block_id;
	}
		
		
	if ($party_adults === '') {
		$party_adults = '0';
	}
		
	if ($party_children === '') {
		$party_children = '0';
	}	


	if ($change_res_date != $original_res_date) {
		
		//echo "change res date is different than original res date";
		
		$res_date = $change_res_date;
		$block_id = '131';
		$res_time = $original_res_time;	

		$ysql = "UPDATE Reservations SET last_name = '" . $last_name . "', room_num = '" . $room_num . "', party_num = '" . $party_num . "', party_adults = '" . $party_adults . "', party_children = '" . $party_children . "', res_date = '" . $res_date . "', food_requests = '" . $food_requests . "', special_requests = '" . $special_requests . "', notes='" . $notes . "', block_id='" . $block_id . "', gog_num = '" . $gog_num . "', out_of_hotel = '" . $out_of_hotel . "', res_time = '" . $res_time . "', actual_table = '" . $actual_table . "' WHERE res_id='" . $res_id . "'";
	
		//echo "<br><br>" . $ysql . "<br><br>";
	
	} else {
		$res_date = $original_res_date;	
		
		//echo "change res date is the same as original res date";
		
		// Update Reservation in the Database


		/* (11-14-19)
		// Create connection
		$conn = mysqli_connect($servername, $username, $password, $dbname);
		// Check connection
		if (!$conn) {
			$message = "Error connecting to DB: " . mysqli_connect_error($conn);
			die("Connection failed: " . mysqli_connect_error());	
		}
		*/ 

		$ysql = "UPDATE Reservations SET party_name = '" . $party_name . "', room_num = '" . $room_num . "', party_num = '" . $party_num . "', party_adults = '" . $party_adults . "', party_children = '" . $party_children . "', res_date = '" . $res_date . "', food_requests = '" . $food_requests . "', special_requests = '" . $special_requests . "', notes='" . $notes . "', block_id='" . $block_id . "', gog_num = '" . $gog_num . "', out_of_hotel = '" . $out_of_hotel . "', actual_table = '" . $actual_table . "' WHERE res_id='" . $res_id . "'";
	
		//echo "<br><br>" . $ysql . "<br><br>";
	}


	if (mysqli_query($conn, $ysql)) {
		$message = "Reservation Updated";
		$smarty->assign('new_res_message_formatting', 'success');
		
		if ($block_id_changed == '1'){
			
			if ($is_duplicate == '1'){
				$zzsql = "UPDATE Timeblocks SET " . $active . " = '0' WHERE block_id = '" . $original_block_id . "';";
				
				if (mysqli_query($conn, $zzsql)) {
					$message = "Duplicate timeblock set as inactive.<br><br>";
					//echo $message;
				} else {
					$message = "Error setting duplicate timeblock as inactive: " . mysqli_error();
					//echo $message;
				}
			} else {
				
				//echo "Block ID is changing and it's not a duplicate.<br><br>";
		
				// Check to see if there are any duplicate timeblocks attached to this table.
		
				$reorder_sql_2 = "SELECT Reservations.res_id, Reservations.block_id FROM Reservations JOIN Timeblocks on Timeblocks.block_id = Reservations.block_id WHERE Timeblocks.res_time_id = '" . $original_res_time_id . "' AND Timeblocks.table_id = '" . $original_table_id . "' AND Timeblocks." . $active . " = '1' AND Reservations.res_id != '" . $res_id . "' AND Reservations.res_date = '" . $_SESSION['selected_date'] . "' LIMIT 1;";
		
				//echo "reorder_sql_2: " . $reorder_sql_2 . "<br><br>";
		
				$reorder_result_2 = mysqli_query($conn, $reorder_sql_2);
				if (mysqli_num_rows($reorder_result_2) > 0){
					while ($row = mysqli_fetch_assoc($reorder_result_2)){
						$duplicate_res_id = $row['res_id'];
						$duplicate_block_id = $row['block_id'];
					}
			
					//echo "We have found additional Duplicate reservations on this table.<br><br>";
			
					//echo "duplicate_res_id: " . $duplicate_res_id . "<br><br>";
					//echo "duplicate_block_id: " . $duplicate_block_id . "<br><br>";
			
					$update_table_config = "UPDATE Res_Table_Config SET timeblock_" . $duplicate_block_id . " = '0' WHERE res_date = '" . $_SESSION['selected_date'] . "';";
			
					//echo "update_table_config: " . $update_table_config . "<br><br>";
			
					if (mysqli_query($conn, $update_table_config)){
						//echo "Duplicate deactivated.<br><br>";
				
						$update_duplicate = "UPDATE Reservations SET block_id = '" . $original_block_id . "' WHERE res_id = '" . $duplicate_res_id . "';";
				
						if (mysqli_query($conn, $update_duplicate)){
							//echo "2nd Res record updated to take old res record's block_id.<br><br>";
						} else {
							//echo "Unable to assign old res record's block_id to new 2nd res record.<br><br>";
						}
					} else {
						//echo "Unable to deactivate duplicate block_id on Res_Table_Config record.<br><br>";
					}	
			
				} else {
					//echo "No duplicates found.<br><br>";
				}
		
			}			
			
			
			$new_party_count = $party_num + $gog_num;
			
			$count_check_old_sql = "SELECT party_count FROM Res_Table_Count WHERE res_date = '" . $res_date . "' AND table_num = '" . $table_num . "' AND res_time = '" . $res_time . "';";
			
			//echo "count_check_old_sql: " . $count_check_old_sql . "<br><br>";
			
			$count_check_old_result = mysqli_query($conn, $count_check_old_sql);
			
			$table_count_old = '';
			while ($row = mysqli_fetch_assoc($count_check_old_result)) {
				//echo '<pre>' . print_r($row, TRUE) . '</pre>';
				$table_count_old = $row['party_count'];	
				//echo "row0: " . $row['party_count'] . "<br><br>";
			}
			$table_count_old -= $new_party_count;
			
			$update_count_old = "UPDATE Res_Table_Count SET party_count = '" . $table_count_old . "' WHERE res_date = '" . $res_date . "' AND table_num = '" . $table_num . "' AND res_time = '" . $res_time . "'";	
			
			//echo "update_count_old: " . $update_count_old . "<br><br>";
			
			if (mysqli_query($conn, $update_count_old)) {
				$message = "Res_Table_Count_Old record updated.<br><br>";
				//echo $message;
			} else {
				$message = " Old Count Record Update Error: " . mysqli_error();
				//echo $message;
			}
			
			$count_check_new_sql = "SELECT party_count FROM Res_Table_Count WHERE res_date = '" . $res_date . "' AND table_num = '" . $new_table_num . "' AND res_time = '" . $new_res_time . "';";
			
			//echo "count_check_new_sql: " . $count_check_new_sql . "<br><br>";
			
			$count_check_new_result = mysqli_query($conn, $count_check_new_sql);
			
			if (mysqli_num_rows($count_check_new_result) > 0) {
			
				$table_count_new = '';
				while ($row = mysqli_fetch_assoc($count_check_new_result)) {
					//echo '<pre>' . print_r($row, TRUE) . '</pre>';
					$table_count_new = $row['party_count'];	
					//echo "row0: " . $row['party_count'] . "<br><br>";
				}
				$table_count_new += $new_party_count;
			
				$update_count_new = "UPDATE Res_Table_Count SET party_count = '" . $table_count_new . "' WHERE res_date = '" . $res_date . "' AND table_num = '" . $new_table_num . "' AND res_time = '" . $new_res_time . "'";	
			
				//echo "update_count_new: " . $update_count_new . "<br><br>";
			
				if (mysqli_query($conn, $update_count_new)) {
					$message = "Res_Table_Count_New record updated.<br><br>";
					//echo $message;
				} else {
					$message = " New Count Record Update Error: " . mysqli_error();
					//echo $message;
				}
			} else {
				$create_count_new = "INSERT INTO Res_Table_Count (res_date, table_num, res_time, party_count) VALUES ('$res_date', '$new_table_num', '$new_res_time', '$new_party_count');";
				
				//echo "create_count_new" . $create_count_new . "<br><br>";
				
				if (mysqli_query($conn, $create_count_new)) {
					$message = "Res_Table_Count_New record created.<br><br>";
					//echo $message;
				} else {
					$message = " New Count Record Creation Error: " . mysqli_error();
					//echo $message;
				}
			}
			
		} else {
		
			$old_party_count = $original_party_num + $original_gog_num;
			$new_party_count = $party_num + $gog_num;
		
			if ($old_party_count !== $new_party_count) {
				$count_difference = $new_party_count - $old_party_count;
			
				//echo "count_difference: " . $count_difference . "<br><br>";
			
				$count_check_sql = "SELECT party_count FROM Res_Table_Count WHERE res_date = '" . $res_date . "' AND table_num = '" . $table_num . "' AND res_time = '" . $res_time . "';";
			
				//echo "count_check_sql: " . $count_check_sql . "<br><br>";
		
				$count_check_result = mysqli_query($conn, $count_check_sql);
			
				$table_count = '';
				while ($row = mysqli_fetch_assoc($count_check_result)) {
					//echo '<pre>' . print_r($row, TRUE) . '</pre>';
					$table_count = $row['party_count'];	
					//echo "row0: " . $row['party_count'] . "<br><br>";
				}
				//echo "table_count 1: " . $table_count . "<br><br>";
				$table_count += $count_difference;
				//echo "table_count 2: " . $table_count . "<br><br>";
			
				$update_count = "UPDATE Res_Table_Count SET party_count = '" . $table_count . "' WHERE res_date = '" . $res_date . "' AND table_num = '" . $table_num . "' AND res_time = '" . $res_time . "'";	
			
				//echo "update_count: " . $update_count . "<br><br>";
			
				if (mysqli_query($conn, $update_count)) {
					$message = "Res_Table_Count record updated.<br><br>";
					//echo $message;
				
				} else {
					$message = " Count Record Update Error: " . mysqli_error();
					//echo $message;
				}
			}
		}
		
		
	} else {
		$message = "Error. No record updated: " . mysqli_connect_error($conn);
		$smarty->assign('new_res_message_formatting', 'fail');
		//echo "524--" . $message . "<br><br>";
	}
	
	/* (11-14-19)
	mysqli_close($conn);
	*/
	
	$smarty->assign('new_res_message', $message);

	}
	
} else {

	$smarty->assign('new_res_message_formatting', 'none');
	
}

//END Update Reservation












//BEGIN Duplicate Reservations



// Clear out message variable
$message = "";

if(isset($_POST['duplicate'])) {
	
	// Get POST values
	// Define variables and set to empty values
	$res_num = $party_name = $room_num = $party_num = $ooh_party_name = $ooh_party_num = $gog_num = $res_date = $res_time = $food_requests = $special_requests = $notes = $res_status = $block_id = $actual_table = $table_num = $res_time_id = $table_id = $res_time = $table_max = $wasatch = "";

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$res_num = validate_input($_POST["res_num"]);
		$party_name = validate_input($_POST["party_name"]);
		$room_num = validate_input($_POST["room_num"]);
		$party_num = validate_input($_POST["party_num"]);
		$ooh_party_name = validate_input($_POST["ooh_party_name"]);
		$ooh_party_num = validate_input($_POST["ooh_party_num"]);
		$gog_num = validate_input($_POST["gog_num"]);
		$res_date = validate_input($_POST["res_date"]);
		$res_time = validate_input($_POST["res_time"]);
		$food_requests = validate_input($_POST["food_requests"]);
		$special_requests = validate_input($_POST["special_requests"]);
		$notes = validate_input($_POST["notes"]);
		$block_id = validate_input($_POST["block_id"]);
		$actual_table = validate_input($_POST["actual_table"]);
		$table_num = validate_input($_POST["table_num"]);
		$res_time_id = validate_input($_POST["res_time_id"]);
		$res_time = validate_input($_POST["res_time"]);
		$table_id = validate_input($_POST["table_id"]);
		$table_max = validate_input($_POST["table_max"]);
		$wasatch = validate_input($_POST["wasatch"]);
		$res_status = validate_input('1');		
	} else {
		$message = 'Unable to collect POST values';	
	}	


	//echo '<pre>' . print_r($_POST, TRUE) . '</pre>';

	/* (11-14-19)
	// Create connection
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	// Check connection
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());	
	}
	*/
	
	
	
	
	
	
	$res_num_explode = explode(',', $res_num);
	$res_num = $res_num_explode[0];
	
	if ($res_num == 'ooh_yes') {
		$party_num = $ooh_party_num;
		$gog_num = '0';
	} else {
		$party_num = $res_num_explode[1];
	}
	
	$party_count3 = $party_num + $gog_num;
	
	//echo "party_num: " . $party_num . "<br><br>";
	//echo "gog_num: " . $gog_num . "<br><br>";
	//echo "party_count3: " . $party_count3 . "<br><br>";
			
	$count_check_sql2 = "SELECT party_count FROM Res_Table_Count WHERE res_date = '" . $res_date . "' AND table_num = '" . $table_num . "' AND res_time = '" . $res_time . "';";
	
	//echo "count_check_sql2: " . $count_check_sql2 . "<br><br>";
			
	$count_check_result2 = mysqli_query($conn, $count_check_sql2);
			
	$res_table_count2 = '';
	while ($row = mysqli_fetch_assoc($count_check_result2)) {
		//echo '<pre>' . print_r($row, TRUE) . '</pre>';
		$res_table_count2 = $row['party_count'];	
		//echo "party_count: " . $row['party_count'] . "<br><br>";
	}
	
	$fake_break = '0';
	$total_party_size = $res_table_count2 + $party_count3;
		
	//echo "total_party_size: " . $total_party_size . "<br><br>";
	
	if (($total_party_size > $table_max) && ($wasatch == '0')) {
		$alert_message = "Unable to add Reservation to table, party size too large: Party Size: " . $total_party_size . ", Table Capacity: " . $table_max . ".";
		echo "<script type='text/javascript'>alert('$alert_message');</script>";
		$fake_break = '1';
	}
	mysqli_free_result($count_check_result2);
	
	if ($fake_break == '0'){
	
	
		
	

	// Check to see if there is already an inactive Timeblock to use

	$ggsql{$i} = "SELECT Timeblocks.block_id, Timeblocks.block_time, Timeblocks.block_number, Res_Tables.table_num, Res_Tables.capacity_min, Res_Tables.capacity_max FROM Timeblocks JOIN Res_Tables ON Timeblocks.table_id = Res_Tables.table_id WHERE Timeblocks.table_id = '" . $table_id . "' AND Timeblocks.res_time_id = '" . $res_time_id . "' AND Timeblocks." . $active . " = '0' LIMIT 1;";

	//echo $ggsql{$i} . "<br><br>";
	
	$ggresult = mysqli_query($conn, $ggsql{$i});
	if (mysqli_num_rows($ggresult) > 0) {
		//echo "Query found results <br><br>";
		
		while ($row = mysqli_fetch_assoc($ggresult)) {
				$ggrows[] = $row;
				$ggblock_id = $ggrows[0]['block_id'];
		}
		
		//echo "ggrows0block_id: " . $ggrows[0]['block_id'] . "<br><br>";
		//echo "ggblock_id: " . $ggblock_id . "<br><br>";
		
		mysqli_free_result($ggresult);
		
		//$mmsql = "UPDATE Timeblocks SET active = '1' WHERE block_id = '" . $ggblock_id . "';";
				
		$mmsql = "UPDATE Res_Table_Config SET timeblock_" . $ggblock_id . " = '1' WHERE res_date = '" . $_SESSION['selected_date'] . "';";
		
		//echo "mmsql: " . $mmsql . "<br><br>";
		
		$mmsql_result = mysqli_query($conn, $mmsql);
		if ( false===$mmsql_result ) {
			//printf("mmsql error: %s\n", mysqli_error($conn));
		} else {
  			//echo 'mmsql: TIMEBLOCK SET TO ACTIVE.<br><br>';

		}
		
		if ($res_num == 'ooh_yes') {
	
			$out_of_hotel = '1';
			$party_name = $ooh_party_name;
			$party_num = $ooh_party_num;
		
		} else {
	
			// Grab party_name & room_num from table
	
			$ssql = "SELECT RoomNum, GuestName, People1, People2, People3, People4 FROM Res_RDP WHERE ResNumNumeric = '" . $res_num . "';";
	
			//echo "ssql: " . $ssql . "<br><br>";
	
			if ($squery = mysqli_query($conn, $ssql)) {
				while ($row = mysqli_fetch_assoc($squery)) {
					$srows[] = $row;
				}
			}
		
			$out_of_hotel = '0';
			$party_name = $srows[0]['GuestName'];
			$room_num = $srows[0]['RoomNum'];
			$party_num = $srows[0]['People1'] + $srows[0]['People2'] + $srows[0]['People3'] + $srows[0]['People4'];
		}
	
		$sql = "INSERT INTO Reservations (ResNum, party_name, room_num, party_num, gog_num, out_of_hotel, res_date, res_time, block_id, food_requests, special_requests, notes, res_status, actual_table, res_type) VALUES ('$res_num', '$party_name', '$room_num', '$party_num', '$gog_num', '$out_of_hotel', '$res_date', '$res_time', '$ggblock_id', '$food_requests', '$special_requests', '$notes', '$res_status', '$actual_table', '$res_type')";
	
		//echo "sql: " . $sql . "<br><br>";

		if (mysqli_query($conn, $sql)) {
			$message = "New reservation created";
			$smarty->assign('new_res_message_formatting', 'success');
		
			$count_check_sql = "SELECT party_count FROM Res_Table_Count WHERE res_date = '" . $res_date . "' AND table_num = '" . $table_num . "' AND res_time = '" . $res_time . "'";
		
			$count_check_result = mysqli_query($conn, $count_check_sql);
			if (mysqli_num_rows($count_check_result) > 0) {
				$table_count = '';
				while ($row = mysqli_fetch_assoc($count_check_result)) {
					$table_count = $row['party_count'];	
					//echo "row0: " . $row['party_count'] . "<br><br>";
				}
				//echo "table_count 1: " . $table_count . "<br><br>";
				//echo "party_num: " . $party_num . "<br><br>";
				//echo "gog_num: " . $gog_num . "<br><br>";
				$table_count += $party_num + $gog_num;
				//echo "table_count 2: " . $table_count . "<br><br>";
			
				$update_count = "UPDATE Res_Table_Count SET party_count = '" . $table_count . "' WHERE res_date = '" . $res_date . "' AND table_num = '" . $table_num . "' AND res_time = '" . $res_time . "'";	
			
				if (mysqli_query($conn, $update_count)) {
					$message = "Res_Table_Count record updated.<br><br>";
					//echo $message;
				} else {
					$message = " Count Record Update Error: " . mysqli_error();
					//echo $message;
				}
			
			} else {
				$table_count += $party_num + $gog_num;
			
				$update_count = "INSERT INTO Res_Table_Count (party_count, res_date, table_num, res_time) VALUES ('$table_count', '$res_date', '$table_num', '$res_time');";
			
				if (mysqli_query($conn, $update_count)) {
					$message = "New Res_Table_Count record created.<br><br>";
					//echo $message;
				} else {
					$message = " Count Record Create Error: " . mysqli_error();
					//echo $message;
				}
			}	
		
		
		} else {
			$message = "Error. No record created: " . mysqli_connect_error($conn);
			$smarty->assign('new_res_message_formatting', 'fail');
			echo $message . "<br><br>";
		}
		
		/* (11-14-19)	
		mysqli_close($conn);
		*/
		
	} else {
		//echo "Query found NO results <br><br>";
		
		mysqli_free_result($ggresult);
	
		$hhsql = "SELECT Res_Tables.table_num, Timeblocks.block_name, Timeblocks.block_time, Timeblocks.block_number, Timeblocks.original_block_time FROM Timeblocks JOIN Res_Tables ON Timeblocks.table_id = Res_Tables.table_id WHERE Timeblocks.table_id = '" . $table_id . "' AND Timeblocks.res_time_id = '" . $res_time_id . "' LIMIT 1;";
	
		if ($query = mysqli_query($conn, $hhsql)) {
			while ($row = mysqli_fetch_assoc($query)) {
				$hhrows[] = $row;
				$table_num = $hhrows[0]['table_num'];
				$block_name = $hhrows[0]['block_name'];
				$block_time = $hhrows[0]['block_time'];
				$block_number = $hhrows[0]['block_number'];
				$original_block_time = $hhrows[0]['original_block_time'];
			}
	
			$iisql = "INSERT INTO Timeblocks (block_name, block_time, block_number, " . $active . ", res_time_id, table_id, duplicate, original_block_time) VALUES ('" . $block_name . "', '" . $block_time . "', '" . $block_number . "', '1', '" . $res_time_id . "', '" . $table_id . "', '1', '" . $original_block_time . "');";
	
			//echo "iisql: " . $iisql . "<br><br>";
			
			if ($iisql_query = mysqli_query($conn, $iisql)) {
				//echo "iisql: TIMEBLOCK CREATED";
				//$message = "Reservation Updated";
				//$smarty->assign('new_res_message_formatting', 'success');
			} else {
				//echo "iisql: TIMEBLOCK FAILED";
				//$message = "Error. No record updated: " . mysqli_connect_error($conn);
				//$smarty->assign('new_res_message_formatting', 'fail');
			}
			
			$new_timeblock_id = mysqli_insert_id($conn);
			
			mysqli_free_result($iisql_query);
			
			//echo "new_timeblock_id: " . $new_timeblock_id . "<br><br>";
			
			$kksql = "ALTER TABLE Res_Table_Config ADD timeblock_" . $new_timeblock_id . " tinyint(1) NOT NULL DEFAULT 0;";
			
			$kksql .= "UPDATE Res_Table_Config SET timeblock_" . $new_timeblock_id . " = '1' WHERE res_date = '" . $_SESSION['selected_date'] . "';";
	
			if ($kksql_query = mysqli_multi_query($conn, $kksql)) {
				//echo "kksql: THIS IS THE SUCCESS MESSAGE.<br><br>";
				//$message = "Reservation Updated";
				//$smarty->assign('new_res_message_formatting', 'success');
			} else {
				//echo "kksql: THIS IS THE FAILURE MESSGAGE.<br><br>";
				//$message = "Error. No record updated: " . mysqli_connect_error($conn);
				//$smarty->assign('new_res_message_formatting', 'fail');
			}
			
			mysqli_free_result($kksql_query);
			
			/* (11-14-19)	
			mysqli_close($conn);
			*/
	
			$jjsql = "SELECT Timeblocks.block_id, Timeblocks.block_time, MIN(Timeblocks.block_number) FROM Timeblocks WHERE NOT EXISTS (SELECT NULL FROM Reservations WHERE Timeblocks.block_id = Reservations.block_id AND Reservations.res_date = '" . $_SESSION['selected_date'] . "') AND Timeblocks." . $active . " = '1' AND Timeblocks.res_time_id = '" . $res_time_id . "' AND Timeblocks.table_id = '" . $table_id . "';";
		
			//echo "jjsql: " . $jjsql . "<br><br>";
			
			/* (11-14-19)
			// Create connection
			$conn = mysqli_connect($servername, $username, $password, $dbname);
			// Check connection
			if (!$conn) {
				die("Connection failed: " . mysqli_connect_error());	
			}
			*/
	
			if ($query = mysqli_query($conn, $jjsql)) {
				
				//echo "We got this far";
				
				while ($row = mysqli_fetch_assoc($query)) {
					$jjrows[] = $row;
					$block_id = $jjrows[0]['block_id'];
				}
				
				if ($res_num == 'ooh_yes') {
	
					$out_of_hotel = '1';
					$party_name = $ooh_party_name;
					$party_num = $ooh_party_num;
		
				} else {
	
					// Grab party_name & room_num from table
	
					$ssql = "SELECT RoomNum, GuestName, People1, People2, People3, People4 FROM Res_RDP WHERE ResNumNumeric = '" . $res_num . "';";
	
					//echo "ssql: " . $ssql . "<br><br>";
	
					if ($squery = mysqli_query($conn, $ssql)) {
						while ($row = mysqli_fetch_assoc($squery)) {
							$srows[] = $row;
						}
					}
		
					$out_of_hotel = '0';
					$party_name = $srows[0]['GuestName'];
					$room_num = $srows[0]['RoomNum'];
					$party_num = $srows[0]['People1'] + $srows[0]['People2'] + $srows[0]['People3'] + $srows[0]['People4'];
				}
	
				$sql = "INSERT INTO Reservations (ResNum, party_name, room_num, party_num, gog_num, out_of_hotel, res_date, res_time, block_id, food_requests, special_requests, notes, res_status, actual_table) VALUES ('$res_num', '$party_name', '$room_num', '$party_num', '$gog_num', '$out_of_hotel', '$res_date', '$res_time', '$block_id', '$food_requests', '$special_requests', '$notes', '$res_status', '$actual_table')";
	
				//echo "sql: " . $sql . "<br><br>";

				if (mysqli_query($conn, $sql)) {
					$message = "New reservation created";
					//echo $message . "<br><br>";
					$smarty->assign('new_res_message_formatting', 'success');
		
					$count_check_sql = "SELECT party_count FROM Res_Table_Count WHERE res_date = '" . $res_date . "' AND table_num = '" . $table_num . "' AND res_time = '" . $res_time . "';";
		
					$count_check_result = mysqli_query($conn, $count_check_sql);
					if (mysqli_num_rows($count_check_result) > 0) {
						$table_count = '';
						while ($row = mysqli_fetch_assoc($count_check_result)) {
							$table_count = $row['party_count'];	
							//echo "row0: " . $row['party_count'] . "<br><br>";
						}
						//echo "table_count 1: " . $table_count . "<br><br>";
						//echo "party_num: " . $party_num . "<br><br>";
						//echo "gog_num: " . $gog_num . "<br><br>";
						$table_count += $party_num + $gog_num;
							//echo "table_count 2: " . $table_count . "<br><br>";
			
						$update_count = "UPDATE Res_Table_Count SET party_count = '" . $table_count . "' WHERE res_date = '" . $res_date . "' AND table_num = '" . $table_num . "' AND res_time = '" . $res_time . "'";	
			
						if (mysqli_query($conn, $update_count)) {
							$message = "Res_Table_Count record updated.<br><br>";
							echo $message;
						} else {
							$message = " Count Record Update Error: " . mysqli_error();
							//echo $message;
						}
			
					} else {
						$table_count += $party_num + $gog_num;
			
						$update_count = "INSERT INTO Res_Table_Count (party_count, res_date, table_num, res_time) VALUES ('$table_count', '$res_date', '$table_num', '$res_time');";
			
						if (mysqli_query($conn, $update_count)) {
							$message = "New Res_Table_Count record created.<br><br>";
							//echo $message;
						} else {
							$message = " Count Record Create Error: " . mysqli_error();
							//echo $message;
						}
					}	
		
		
				} else {
					$message = "Error. No record created: " . mysqli_connect_error($conn);
					$smarty->assign('new_res_message_formatting', 'fail');
				}
			
				/* (11-14-19)	
				mysqli_close($conn);
				*/	
				
			} else {
				//echo "Unabe to find block_id: " . mysqli_error($conn) . ".<br><br>";
			}
		
		}
	}
		
	}
	
} else {

	$smarty->assign('new_res_message_formatting', 'none');
	
}



//END Duplicate Reservations







//BEGIN Assign Reservations



// Clear out message variable
$message = "";

if(isset($_POST['assign'])) {

	// Get POST values
	// Define variables and set to empty values
	$res_id = $block_id = "";

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$res_id = validate_input($_POST["select_res"]);
		$block_id = validate_input($_POST["block_id"]);
	} else {
		$message = 'Unable to collect POST values';	
	}

	//echo '<pre>' . print_r($_POST, TRUE) . '</pre>';
	
	//echo "res_id: " . $res_id . "<br><br>";
	//echo "block_id: " . $block_id . "<br><br>";	

	/* (11-14-19)
	// Create connection
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	// Check connection
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());	
	}
	*/

	$assign_sql = "UPDATE Reservations SET Reservations.block_id = '" . $block_id . "' WHERE Reservations.res_id = '" . $res_id . "';";
	
	//echo "assign_sql: " . $assign_sql . "<br><br>";
	
	if (mysqli_query($conn, $assign_sql)) {
		//echo "assign_sql: RESERVATION ASSIGNED";
		//$message = "Reservation Updated";
		//$smarty->assign('new_res_message_formatting', 'success');
	} else {
		//echo "assign_sql: RESERVATION ASSIGN FAILED";
		//$message = "Error. No record updated: " . mysqli_connect_error($conn);
		//$smarty->assign('new_res_message_formatting', 'fail');
	}

	/* (11-14-19)	
	mysqli_close($conn);
	*/

} else {

	$smarty->assign('new_res_message_formatting', 'none');
	
}

// END Assign Reservation









// BEGIN Various Date variables

$current_date = date('Y-m-d');
$date_plus2 = date('Y-m-d', strtotime('+2 years', strtotime(date('Y'))));
$current_time = date('H:i');
$date_display = date('m/d/y');
$serverself = $_SERVER['PHP_SELF'];

$smarty->assign('current_date', $current_date);
//$smarty->assign('date_plus2', $date_plus2);
$smarty->assign('date_display', $date_display);
$smarty->assign('serverself', $serverself);

// END Various Date variables



// BEGIN Unassign Update

if(isset($_POST['unassign'])) {

	// Get POST values
	// Define variables and set to empty values
	$res_id = "";

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$res_id = validate_input($_POST["res_id"]);	
	} else {
		$message = 'Unable to collect POST values';	
		//echo $message;
	}

	/* (11-14-19)
	// Create connection
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	// Check connection
	if (!$conn) {
		$message = "Error connecting to DB: " . mysqli_connect_error($conn);
		die("Connection failed: " . mysqli_connect_error());	
	} 
	*/

	$sql = "UPDATE Reservations SET block_id = '131' WHERE res_id = " . $res_id . ";";
	//echo $sql . "<br>";

	if (mysqli_query($conn, $sql)) {
		$message = "Reservation updated successfully";
	} else {
		$message = "Error: " . mysqli_connect_error($conn);
	}
	/* (11-14-19)	
	mysqli_close($conn);
	*/
	
} 

// END Unassign Update




// BEGIN Reassign Update

if(isset($_POST['change_res_time'])) {

	// Get POST values
	// Define variables and set to empty values
	$res_id = "";

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$res_time = validate_input($_POST["change_res_time"]);	
		$res_id = validate_input($_POST["change_res_id"]);
	} else {
		$message = 'Unable to collect POST values';	
		//echo $message;
	}
	
	
	// Use POST res_time to get net available timeslot in that Reservation Time
	
	/* (11-14-19)
	// Create connection
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	// Check connection
	if (!$conn) {
		$message = "Error connecting to DB: " . mysqli_connect_error($conn);
		die("Connection failed: " . mysqli_connect_error());	
	} 
	*/
	
	$zsql = "SELECT Timeblocks.block_id, Timeblocks.block_time, MIN(Timeblocks.block_number) FROM Timeblocks WHERE NOT EXISTS (SELECT NULL FROM Reservations WHERE Timeblocks.block_id = Reservations.block_id AND Reservations.res_date = '" . $_SESSION['selected_date'] . "' AND Reservations.res_status != '2') AND Timeblocks." . $active . " = '1' AND Timeblocks.block_time = '" . $res_time . "'";
	
	if ($query = mysqli_query($conn, $zsql)) {
		while ($row = mysqli_fetch_assoc($query)) {
			$zrows[] = $row;
		}
		$block_id = $zrows[0]['block_id'];
		mysqli_free_result($query);
	} else {
		//echo "Error: " . mysqli_connect_error($conn);
		//$message = "Error: " . mysqli_connect_error($conn);
	}
	
	
	// Update Reservation Time

	/* (11-14-19)
	// Create connection
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	// Check connection
	if (!$conn) {
		$message = "Error connecting to DB: " . mysqli_connect_error($conn);
		die("Connection failed: " . mysqli_connect_error());	
	} 
	*/

	$sql = "UPDATE Reservations SET block_id = '" . $block_id . "' WHERE res_id = " . $res_id . ";";

	if (mysqli_query($conn, $sql)) {
		$message = "Reservation updated successfully";
	} else {
		$message = "Error: " . mysqli_connect_error($conn);
	}
	
	/* (11-14-19)	
	mysqli_close($conn);
	*/
	
} 

// END Reassign Update




// BEGIN Arrived Update

if(isset($_POST['Arrived'])) {

	// Get POST values
	// Define variables and set to empty values
	$res_id = $res_status = "";

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$res_id = validate_input($_POST["Arrived"]);
		$res_status = validate_input($_POST["res_status"]);	
	} else {
		$message = 'Unable to collect POST values';	
		//echo $message;
	}

	/* (11-14-19)
	// Create connection
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	// Check connection
	if (!$conn) {
		$message = "Error connecting to DB: " . mysqli_connect_error($conn);
		die("Connection failed: " . mysqli_connect_error());	
	} 
	*/
	
	if ($res_status == '1') {
	$sql = "UPDATE Reservations SET res_status = '3' WHERE res_id = " . $res_id . ";";
	} else {
	$sql = "UPDATE Reservations SET res_status = '1' WHERE res_id = " . $res_id . ";";
	}

	if (mysqli_query($conn, $sql)) {
		$message = "Reservation updated successfully";
	} else {
		$message = "Error: " . mysqli_connect_error($conn);
	}
	
	/* (11-14-19)	
	mysqli_close($conn);
	*/
	
} 

// END Arrived Update





// BEGIN Cancel Update

if(isset($_POST['cancel_res_id'])) {
	
	//echo '<pre>' . print_r($_POST, TRUE) . '</pre>';

	// Get POST values
	// Define variables and set to empty values
	$res_id = $res_date = $res_time = $table_num = $party_num = $gog_num = $block_id = $res_time_id = $table_id = "";

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$res_id = validate_input($_POST["cancel_res_id"]);
		$res_date = validate_input($_POST["res_date"]);
		$res_time = validate_input($_POST["res_time"]);
		$table_num = validate_input($_POST["table_num"]);
		$party_num = validate_input($_POST["party_num"]);
		$gog_num = validate_input($_POST["gog_num"]);
		$block_id = validate_input($_POST["block_id"]);
	} else {
		$message = 'Unable to collect POST values';	
		//echo $message;
	}

	/* (11-14-19)
	// Create connection
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	// Check connection
	if (!$conn) {
		$message = "Error connecting to DB: " . mysqli_connect_error($conn);
		die("Connection failed: " . mysqli_connect_error());	
	} 
	*/
	
	$ssql = "SELECT Timeblocks.duplicate FROM Timeblocks JOIN Reservations ON Reservations.block_id = Timeblocks.block_id WHERE Reservations.res_id = '" . $res_id . "' AND Timeblocks.duplicate = '1';";
	
	//$ssql = "SELECT Timeblocks.duplicate FROM Timeblocks WHERE block_id = '" . $block_id . "';";
		
	//echo "CANCEL UPDATE#1. ssql: " . $ssql . "<br><br>";
		
	$ssresult = mysqli_query($conn, $ssql);
	if (mysqli_num_rows($ssresult) > 0) {
		
		//echo "Timeblock is a duplicate.<br><br>";
		
		//$sssql = "UPDATE Timeblocks JOIN Reservations ON Reservations.block_id = Timeblocks.block_id SET Timeblocks.active = '0' WHERE Reservations.res_id = '" . $res_id . "';";
		
		$sssql = "UPDATE Res_Table_Config SET timeblock_" . $block_id . " = '0' WHERE res_date = '" . $_SESSION['selected_date'] . "';";
		
		//echo "sssql: " . $sssql . "<br><br>";
		
		if (mysqli_query($conn, $sssql)) {
			//echo "Table_Config record successfully update. sssql: " . $sssql . "<br><br>";
		} else {
			//echo "Error updating Table_Config record.";
		}
	} else {
		
		//echo "Timeblock is not a duplicate.<br><br>";
		
		$reorder_sql = "SELECT Timeblocks.block_id, Timeblocks.duplicate, Timeblocks.res_time_id, Timeblocks.table_id, Reservations.res_id FROM Reservations JOIN Timeblocks ON Timeblocks.block_id = Reservations.block_id WHERE Reservations.res_id = '" . $res_id . "';";
		
		//echo "reorder_sql: " . $reorder_sql . "<br><br>";
		
		$reorder_result = mysqli_query($conn, $reorder_sql);
		while ($row = mysqli_fetch_assoc($reorder_result)){
			$res_time_id = $row['res_time_id'];
			$table_id = $row['table_id'];
		}
		
		//echo "res_time_id: " . $res_time_id . "<br><br>";
		//echo "table_id: " . $table_id . "<br><br>";
		
		// Check to see if there are any duplicate timeblocks attached to this table.
		
		$reorder_sql_2 = "SELECT Reservations.res_id, Reservations.block_id FROM Reservations JOIN Timeblocks on Timeblocks.block_id = Reservations.block_id WHERE Timeblocks.res_time_id = '" . $res_time_id . "' AND Timeblocks.table_id = '" . $table_id . "' AND Timeblocks." . $active . " = '1' AND Reservations.res_id != '" . $res_id . "' AND Reservations.res_date = '" . $_SESSION['selected_date'] . "' LIMIT 1;";
		
		//echo "reorder_sql_2: " . $reorder_sql_2 . "<br><br>";
		
		$reorder_result_2 = mysqli_query($conn, $reorder_sql_2);
		if (mysqli_num_rows($reorder_result_2) > 0){
			while ($row = mysqli_fetch_assoc($reorder_result_2)){
				$duplicate_res_id = $row['res_id'];
				$duplicate_block_id = $row['block_id'];
			}
			
			//echo "We have found additional Duplicate reservations on this table.<br><br>";
			
			//echo "duplicate_res_id: " . $duplicate_res_id . "<br><br>";
			//echo "duplicate_block_id: " . $duplicate_block_id . "<br><br>";
			
			$update_table_config = "UPDATE Res_Table_Config SET timeblock_" . $duplicate_block_id . " = '0' WHERE res_date = '" . $_SESSION['selected_date'] . "';";
			
			//echo "update_table_config: " . $update_table_config . "<br><br>";
			
			if (mysqli_query($conn, $update_table_config)){
				//echo "Duplicate deactivated.<br><br>";
				
				$update_duplicate = "UPDATE Reservations SET block_id = '" . $block_id . "' WHERE res_id = '" . $duplicate_res_id . "';";
				
				if (mysqli_query($conn, $update_duplicate)){
					//echo "2nd Res record updated to take old res record's block_id.<br><br>";
				} else {
					//echo "Unable to assign old res record's block_id to new 2nd res record.<br><br>";
				}
			} else {
				//echo "Unable to deactivate duplicate block_id on Res_Table_Config record.<br><br>";
			}	
			
		} else {
			//echo "No duplicates found.<br><br>";
		}
		
		
	}
	//$sql = "UPDATE Reservations SET res_status = '2' WHERE res_id = " . $res_id . ";";
	$sql = "DELETE FROM Reservations WHERE res_id = '" . $res_id . "';";

	if (mysqli_query($conn, $sql)) {
		$message = "Reservation deleted successfully";
		//echo $message . "<br><br>";
		
		
	} else {
		$message = "Error: " . mysqli_connect_error($conn);
		//echo $message . "<br><br>";
	}
	
	// Update table count
	
	$count_check_sql = "SELECT party_count FROM Res_Table_Count WHERE res_date = '" . $res_date . "' AND table_num = '" . $table_num . "' AND res_time = '" . $res_time . "'";
		
	$count_check_result = mysqli_query($conn, $count_check_sql);
	
	$table_count = '';
	
	while ($row = mysqli_fetch_assoc($count_check_result)) {
		$table_count = $row['party_count'];	
		//echo "row0: " . $row['party_count'] . "<br><br>";
	}
	//echo "table_count 1: " . $table_count . "<br><br>";
	//echo "party_num: " . $party_num . "<br><br>";
	//echo "gog_num: " . $gog_num . "<br><br>";
	
	$table_count -= $party_num + $gog_num;
	
	//echo "table_count 2: " . $table_count . "<br><br>";
			
	$update_count = "UPDATE Res_Table_Count SET party_count = '" . $table_count . "' WHERE res_date = '" . $res_date . "' AND table_num = '" . $table_num . "' AND res_time = '" . $res_time . "'";	
			
	if (mysqli_query($conn, $update_count)) {
		$message = "Res_Table_Count record updated.<br><br>";
		//echo $message;
	} else {
		$message = " Count Record Update Error: " . mysqli_error();
		//echo $message;
	}
	
	/* (11-14-19)	
	mysqli_close($conn);
	*/
	
} 

// END Cancel Update







// BEGIN New SMS Update

if(isset($_POST['guest_message'])) {
	
	//echo '<pre>' . print_r($_POST, TRUE) . '<pre>';

	// Get POST values
	// Define variables and set to empty values
	$message_body = $guest_phone = $res_id = "";
	
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$message_body = validate_input($_POST["guest_message"]);
		$guest_phone = validate_input($_POST["to_phone"]);	
		$res_id = validate_input($_POST["sms_res_id"]);	
	} else {
		$message = 'Unable to collect POST values';	
		//echo $message;
	}
		
	$guest_phone = validate_sms($guest_phone);	
		
	$twilio_number = "+18015095311";

	$client = new Client($account_sid, $auth_token);

	// Use the client to do fun stuff like send text messages!
	$client->messages->create(
		// the number you'd like to send the message to
		$guest_phone,
		//'+18018676846',
		[
			// A Twilio phone number you purchased at twilio.com/console
			'from' => $twilio_number,
			// the body of the text message you'd like to send
			'body' => $message_body
		]
	);

	$message_body = validate_input($message_body);

	$sms_query = "INSERT INTO users_sms (fromNumber, toNumber, body) VALUES ('$twilio_number', '$guest_phone', '$message_body')";

	//echo "this is the sms_query: " . $sms_query . "<br><br>";

	if ($sms_result = mysqli_query($conn, $sms_query)) {
		//echo "Successfully inserted into db";
	mysqli_free_result($result);
	} else {
		//file_put_contents("sms-test.txt", "Error: " . mysqli_connect_error($conn), FILE_APPEND); 
		//$message = "Error: " . mysqli_connect_error($conn);
		//echo "1752--ERROR inserting into db";
	}
	
}


// END New SMS Update







//BEGIN Create New Reservation

// Clear out message variable
$message = "";

if(isset($_POST['newsubmit'])) {
	
	//echo '<pre>' . print_r($_POST, TRUE) . '</pre>';

	// Get POST values
	// Define variables and set to empty values
	$res_num = $party_name = $room_num = $party_num = $ooh_party_name = $ooh_party_num = $gog_num = $res_date = $res_time = $food_requests = $special_requests = $notes = $res_status = $block_id = $actual_table = $table_num = $table_max = $wasatch = $affiliation = $sms_opt_in = $guest_phone = "";

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$res_num = validate_input($_POST["res_num"]);
		//$party_name = validate_input($_POST["party_name"]);
		//$room_num = validate_input($_POST["room_num"]);
		//$party_num = validate_input($_POST["party_num"]);
		$ooh_party_name = validate_input($_POST["ooh_party_name"]);
		$ooh_party_num = validate_input($_POST["ooh_party_num"]);
		$gog_num = validate_input($_POST["gog_num"]);
		$res_date = validate_input($_POST["res_date"]);
		$res_time = validate_input($_POST["res_time"]);
		$food_requests = validate_input($_POST["food_requests"]);
		$special_requests = validate_input($_POST["special_requests"]);
		$notes = validate_input($_POST["notes"]);
		$block_id = validate_input($_POST["block_id"]);
		$actual_table = validate_input($_POST["actual_table"]);
		$table_num = validate_input($_POST["table_num"]);
		$table_max = validate_input($_POST['table_max']);
		$wasatch = validate_input($_POST['wasatch']);
		$affiliation = validate_input($_POST['affiliation']);
		$res_status = validate_input('1');
		$sms_opt_in = validate_input($_POST["sms_opt_in"]);
		$guest_phone = validate_input($_POST["guest_phone"]);
	} else {
		$message = 'Unable to collect POST values';	
	}	
	
	$res_num_explode = explode(',', $res_num);
	$res_num = $res_num_explode[0];
	$party_num = $res_num_explode[1];
	
	// Check for Affiliations so that they can be all added together
	
	$fake_break = '0';
	$affiliation_break = '0';
	
	//echo "affiliation?: " . $affiliation . "<br><br>";
	
	if ($affiliation != '') { 
		
		//echo "They do have an affiliation.<br><br>";
		
		// Check for other related guests with the same affiliation
		
		$affsql = "SELECT Res_RDP.RoomNum, Res_RDP.GuestName, Res_RDP.People1, Res_RDP.People2, Res_RDP.People3, Res_RDP.People4, Res_RDP_Comment.Comment7 FROM Res_RDP JOIN Res_RDP_Comment ON Res_RDP.ResNum = Res_RDP_Comment.ResNum WHERE NOT EXISTS (SELECT NULL FROM Reservations WHERE Reservations.ResNum = Res_RDP.ResNumNumeric AND Reservations.res_date = '" . $_SESSION['selected_date'] . "' AND res_type = '" . $res_type . "') AND Res_RDP_Comment.Comment7 = '" . $affiliation . "' AND ResNumNumeric <> '" . $res_num . "' AND Res_RDP.ArrivalDate <= '" . $_SESSION['selected_date'] . "' AND Res_RDP.DepartureDate >= '" . $_SESSION['selected_date'] . "' AND Res_RDP.RoomType <> 'N/RWTL' AND Res_RDP.ResStatus <= '6' AND Res_RDP.MarketCode <> 'mn' AND Res_RDP.ResType NOT IN ('M', 'X') ORDER BY Res_RDP.RoomNum ASC;";
		
		//echo "affsql: " . $affsql . "<br><br>";
		
		/* (11-14-19)
		// Create connection
		$conn = mysqli_connect($servername, $username, $password, $dbname);
		// Check connection
		if (!$conn) {
			$message = "Error connecting to DB: " . mysqli_connect_error($conn);
			die("Connection failed: " . mysqli_connect_error());	
		} 
		*/
		
		$aff_check_result = mysqli_query($conn, $affsql);
		if (mysqli_num_rows($aff_check_result) > 0) {
			
			//echo "Affiliated guest records have been found.<br><br>";
			
			// Check to see if party total of all affiliated guests exceeds table max
			
			$aff_headcount = 0;
			while ($row = mysqli_fetch_row($aff_check_result)) {
				$aff_headcount += $row[2];
				$aff_headcount += $row[3];
				$aff_headcount += $row[4];
				$aff_headcount += $row[5];
			}
			
			$total_party_size = '';
			
			$total_party_size = $aff_headcount + $party_num + $gog_num;
			
			//echo "total_party_size: " . $total_party_size . "<br><br>";
			//echo "table_max: " . $table_max . "<br><br>";
			
			if (($total_party_size > $table_max) && ($wasatch == '0'))  {
				$alert_message = "Unable to assign Reservation Party, party size too large: Party Size: " . $total_party_size . ", Table Capacity: " . $table_max . ".";
		
				echo "<script type='text/javascript'>alert('$alert_message');</script>";
				$fake_break = '1';
			} else {
				
				//echo "Combined affiliation will fit on table.<br><br>";
				$affiliation_break = '1';
			}
	
		} else {
			
			//echo "No affiliated guest records found.<br><br>";
		}
		
	} else {
		//echo "No Affiliation for selected guest.<br><br>";
	}
	
	
	
	
	
	// First attempt at narrowing down requirements for reservation limit. Based on number of reservations +/- one month from selected date.
	
	/*
	$affsql = "SELECT Res_RDP.RoomNum, Res_RDP.GuestName, Res_RDP.People1, Res_RDP.People2, Res_RDP.People3, Res_RDP.People4, Res_RDP_Comment.Comment7, DATEADD(month, 1, '" . $_SESSION['selected_date'] . "') AS MonthFuture, DATEADD(month, -1, '" . $_SESSION['selected_date'] . "') AS MonthPast FROM Res_RDP JOIN Res_RDP_Comment ON Res_RDP.ResNum = Res_RDP_Comment.ResNum WHERE ResNumNumeric = '" . $res_num . "' AND Res_RDP.ArrivalDate <= MonthPast AND Res_RDP.DepartureDate >= '" . $_SESSION['selected_date'] . "' AND Res_RDP.RoomType <> 'N/RWTL' AND Res_RDP.ResStatus <= '6' AND Res_RDP.MarketCode <> 'mn' AND Res_RDP.ResType NOT IN ('M', 'X') ORDER BY Res_RDP.RoomNum ASC;";
	*/
	
	$limit_sql = "SELECT ResNum, party_name, room_num FROM Reservations WHERE ResNum = '" . $res_num . "' AND res_type = '" . $res_type . "';";
	
	//echo "limit_sql: " . $limit_sql . "<br><br>";
	
	/* (11-14-19)
	// Create connection
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	// Check connection
	if (!$conn) {
		$message = "Error connecting to DB: " . mysqli_connect_error($conn);
		die("Connection failed: " . mysqli_connect_error());	
	} 
	*/
		
	$limit_check_result = mysqli_query($conn, $limit_sql);
	if ((mysqli_num_rows($limit_check_result) >= 2) && ($_SESSION["perms_limit_override"] == '0')) {
		$alert_message = "Unable to add Reservation Party, Party has already reserved 2 nights.";
		echo "<script type='text/javascript'>alert('$alert_message');</script>";
		$fake_break = '1';
	} elseif ((mysqli_num_rows($limit_check_result) >= 2) && ($_SESSION["perms_limit_override"] == '1')) {
		$limit_total = mysqli_num_rows($limit_check_result) + 1;
		$alert_message = "Reservation limit overridden. Number of reservations: " . $limit_total . ".";
		echo "<script type='text/javascript'>alert('$alert_message');</script>";
	} else {
		//echo "Good to go. Still within 2 reservation limit.<br><br>";
	}
	
	mysqli_free_result($limit_check_result);
	
	
	
	
	
	
	$total_party_size = '';
	$total_party_size = $party_num + $gog_num;
	
	if (($total_party_size > $table_max) && ($wasatch == '0'))  {
		$alert_message = "Unable to assign Reservation Party, party size too large: Party Size: " . $total_party_size . ", Table Capacity: " . $table_max . ".";
		echo "<script type='text/javascript'>alert('$alert_message');</script>";
		$fake_break = '1';
	}
	
	if ($fake_break == '0'){
	

	/* Old code to get next available timeslot. No longer used--we get the block_id directly from selected listing.
	
	// Use POST res_time to get next available timeslot in that Reservation Time
	
	// Create connection
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	// Check connection
	if (!$conn) {
		$message = "Error connecting to DB: " . mysqli_connect_error($conn);
		die("Connection failed: " . mysqli_connect_error());	
	} 
	
	$zsql = "SELECT Timeblocks.block_id, Timeblocks.block_time, MIN(Timeblocks.block_number) FROM Timeblocks WHERE NOT EXISTS (SELECT NULL FROM Reservations WHERE Timeblocks.block_id = Reservations.block_id AND Reservations.res_date = curdate()) AND Timeblocks.active = '1' AND Timeblocks.block_time = '" . $res_time . "'";
	
	if ($query = mysqli_query($conn, $zsql)) {
		while ($row = mysqli_fetch_assoc($query)) {
			$zrows[] = $row;
		}
		$block_id = $zrows[0]['block_id'];
		mysqli_free_result($query);
	} else {
		echo "Error New Res: " . mysqli_connect_error($conn);
		//$message = "Error: " . mysqli_connect_error($conn);
	}

	mysqli_close($conn);	
	
	*/
	
	

	// Add new Reservation into Database

	
	if ($res_num == 'ooh_yes') {
	
		$out_of_hotel = '1';
		$party_name = $ooh_party_name;
		$party_num = $ooh_party_num;
		$res_num = '0';
		if ($actual_table === '') {
			$actual_table = '0';
		}

	} else {
	
		// Grab party_name & room_num from table
	
		$ssql = "SELECT RoomNum, GuestName, People1, People2, People3, People4 FROM Res_RDP WHERE ResNumNumeric = '" . $res_num . "';";
	
		//echo "ssql: " . $ssql . "<br><br>";
	
		if ($squery = mysqli_query($conn, $ssql)) {
			while ($row = mysqli_fetch_assoc($squery)) {
				$srows[] = $row;
			}
		}
		
		$out_of_hotel = '0';
		$party_name = $srows[0]['GuestName'];
		$room_num = $srows[0]['RoomNum'];
		$party_num = $srows[0]['People1'] + $srows[0]['People2'] + $srows[0]['People3'] + $srows[0]['People4'];
	}
		
		
	if ($sms_opt_in	=== 'on') {
		$sms_opt_in = '1'; 
	} else {
		$sms_opt_in = '0'; 
	}
		
		
	if ($actual_table === '') {
		$actual_table = '0'; 
	}
			
	
	//echo "This is guest opt-in: " . $sms_opt_in . "<br><br>";
		
	
	if ($sms_opt_in === '1') {	
		if (substr(trim($guest_phone), 0, 1) != 1) {
			$guest_phone = "+1" . $guest_phone;
		} else {
			$guest_phone = "+" . $guest_phone;
		}
	} else {
		$guest_phone = '';
	}
	
	$guest_phone = validate_sms($guest_phone);
			
	//echo "This is guest phone: " . $guest_phone . "<br><br>";
		
	
	$sql = "INSERT INTO Reservations (ResNum, party_name, room_num, party_num, gog_num, out_of_hotel, res_date, res_time, block_id, food_requests, special_requests, notes, res_status, actual_table, affiliation, phone, sms_opt_in, res_type) VALUES ('$res_num', '$party_name', '$room_num', '$party_num', '$gog_num', '$out_of_hotel', '$res_date', '$res_time', '$block_id', '$food_requests', '$special_requests', '$notes', '$res_status', '$actual_table', '$affiliation', '$guest_phone', '$sms_opt_in', '$res_type')";
	
	//echo "sql: " . $sql . "<br><br>";

	if (mysqli_query($conn, $sql)) {
		$message = "New reservation created";
		
		//echo $message . "<br><br>";
		
		$smarty->assign('new_res_message_formatting', 'success');
		
		$count_check_sql = "SELECT party_count FROM Res_Table_Count WHERE res_date = '" . $res_date . "' AND table_num = '" . $table_num . "' AND res_time = '" . $res_time . "'";
		
		//echo "count_check_sql: " . $count_check_sql . "<br><br>";
		
		$count_check_result = mysqli_query($conn, $count_check_sql);
		if (mysqli_num_rows($count_check_result) > 0) {
			
			//echo "Table Count record found.<br>";
			
			$table_count = '';
			while ($row = mysqli_fetch_assoc($count_check_result)) {
				$table_count = $row['party_count'];	
				//echo "row0: " . $row['party_count'] . "<br><br>";
			}
			//echo "table_count 1: " . $table_count . "<br><br>";
			//echo "party_num: " . $party_num . "<br><br>";
			//echo "gog_num: " . $gog_num . "<br><br>";
			$table_count += $party_num + $gog_num;
			//echo "table_count 2: " . $table_count . "<br><br>";
			
			$update_count = "UPDATE Res_Table_Count SET party_count = '" . $table_count . "' WHERE res_date = '" . $res_date . "' AND table_num = '" . $table_num . "' AND res_time = '" . $res_time . "'";	
			
			if (mysqli_query($conn, $update_count)) {
				$message = "Res_Table_Count record updated.<br><br>";
				//echo $message;
			} else {
				$message = " Count Record Update Error: " . mysqli_error();
				//echo $message;
			}
			
		} else {
			
			//echo "No table count update record found. <br><br>";
			
			$table_count += $party_num + $gog_num;
			
			$update_count = "INSERT INTO Res_Table_Count (party_count, res_date, table_num, res_time) VALUES ('$table_count', '$res_date', '$table_num', '$res_time');";
			
			//echo "update_count: " . $update_count . "<br><br>";
			
			if (mysqli_query($conn, $update_count)) {
				$message = "New Res_Table_Count record created.<br><br>";
				//echo $message;
			} else {
				$message = " Count Record Create Error: " . mysqli_error();
				//echo $message;
			}
		}
		
		// BEGIN SMS opt-in
		
		if ($sms_opt_in === '1') {
			
			//echo "1693 - It recognized it as opt-in";

			//echo "This is todays date: " . date('Y-m-d') . "<br><br>";
			//echo "This is the res date: " . $res_date . "<br><br>";

			$formatted_todays_date = date('Y-m-d');
			$formatted_tomorrows_date = date('Y-m-d', strtotime('+1 day'));
			$formatted_res_date = date('Y-m-d', strtotime($res_date));

			//echo "This is the formatted todays date: " . $formatted_todays_date . "<br><br>";
			//echo "This is the formatted res date: " . $formatted_res_date . "<br><br>";

			//echo "This is the formatted tomorrows date: " . $formatted_tomorrows_date . "<br><br>";

			//date('Y-m-d', strtotime('+1 day', strtotime(date('Y'))));

			if ($formatted_res_date === $formatted_todays_date) {
				$res_time = "Today at " . date("g:i a", strtotime($res_time));
			} elseif ($formatted_res_date === $formatted_tomorrows_date) {
				$res_time = "Tomorrow at " . date("g:i a", strtotime($res_time));
			} else {
				$res_time = date("g:i a", strtotime($res_time));
			}

			$res_date = date("F d, Y", strtotime($res_date));
			//$guest_message = "Your Goldminer's Daughter dinner reservation is confirmed for " . $res_time . " on " . $res_date . ". You can reply directly to this message with any questions, or send 'STOP' to opt-out of further communication.";
			$message_body = "Your " . $config_name . " reservation is confirmed for " . $res_time . " on " . $res_date . ". You can reply directly to this message with any questions.";
			//echo "this is the guest message: " . $guest_message . "<br><br>";
			
			
			$twilio_number = "+18015095311";

			$client = new Client($account_sid, $auth_token);
			
			// Use the client to do fun stuff like send text messages!
			$client->messages->create(
				// the number you'd like to send the message to
				$guest_phone,
				//'+18018676846',
				[
					// A Twilio phone number you purchased at twilio.com/console
					'from' => $twilio_number,
					// the body of the text message you'd like to send
					'body' => $message_body
				]
			);

			$message_body = validate_input($message_body);

			$sms_query = "INSERT INTO users_sms (fromNumber, toNumber, body) VALUES ('$twilio_number', '$guest_phone', '$message_body')";

			//echo "this is the sms_query: " . $sms_query . "<br><br>";

			if ($sms_result = mysqli_query($conn, $sms_query)) {
				//echo "Successfully inserted into db";
			mysqli_free_result($result);
			} else {
				//file_put_contents("sms-test.txt", "Error: " . mysqli_connect_error($conn), FILE_APPEND); 
				//$message = "Error: " . mysqli_connect_error($conn);
				//echo "1752--ERROR inserting into db";
			}
		}
		
		// END SMS opt-in
		
	} else {
		$message = "1858--Error. No record created: " . mysqli_connect_error($conn);
		$smarty->assign('new_res_message_formatting', 'fail');
		//echo $message . "<br>";
	}
	
	/* (11-14-19)	
	mysqli_close($conn);
	*/
	
	$smarty->assign('new_res_message', $message);
	
	// Section to run if there are affiliations to add to the table
	
	if ($affiliation_break == '1') {
	
		//echo "This triggered the affiliation_break section.<br><br>";

//BEGIN Duplicate Affiliation Reservations
		
		$affsql = "SELECT Res_RDP.RoomNum, Res_RDP.GuestName, Res_RDP.People1, Res_RDP.People2, Res_RDP.People3, Res_RDP.People4, Res_RDP_Comment.Comment7, Res_RDP.ResNumNumeric, Res_RDP_Comment.Comment8 FROM Res_RDP JOIN Res_RDP_Comment ON Res_RDP.ResNum = Res_RDP_Comment.ResNum WHERE NOT EXISTS (SELECT NULL FROM Reservations WHERE Reservations.ResNum = Res_RDP.ResNumNumeric AND Reservations.res_date = '" . $_SESSION['selected_date'] . "' AND res_type = '" . $res_type . "') AND Res_RDP_Comment.Comment7 = '" . $affiliation . "' AND Res_RDP.ResNumNumeric <> '" . $res_num . "' AND Res_RDP.ArrivalDate <= '" . $_SESSION['selected_date'] . "' AND Res_RDP.DepartureDate >= '" . $_SESSION['selected_date'] . "' AND Res_RDP.RoomType <> 'N/RWTL' AND Res_RDP.ResStatus <= '6' AND Res_RDP.MarketCode <> 'mn' AND Res_RDP.ResType NOT IN ('M', 'X') ORDER BY Res_RDP.RoomNum ASC;";
		
		//echo "affsql: " . $affsql . "<br><br>";
		
		/* (11-14-19)
		// Create connection
		$conn = mysqli_connect($servername, $username, $password, $dbname);
		// Check connection
		if (!$conn) {
			$message = "Error connecting to DB: " . mysqli_connect_error($conn);
			die("Connection failed: " . mysqli_connect_error());	
		} 
		*/
		
		$aff_check_result = mysqli_query($conn, $affsql);
		if (mysqli_num_rows($aff_check_result) > 0) {
			
		//echo "Affiliated guest records have been found.<br><br>";
		
		while ($row = mysqli_fetch_row($aff_check_result)) {
		
		$out_of_hotel = '0';
		$gog_num = '0';
		$special_requests = '';
		$notes = '';	
		$room_num = $row[0];
		$party_name = $row[1];
		$party_num =  $row[2] + $row[3] + $row[4] + $row[5];
		$affiliation = $row[6];
		$res_num = $row[7];
		$food_requests = $row[8];
			
		//echo "room_num: " . $room_num . "<br><br>";
		//echo "party_name: " . $party_name . "<br><br>";
		//echo "party_num: " . $party_num . "<br><br>";
		//echo "affiliation: " . $affiliation . "<br><br>";
		//echo "res_num: " . $res_num . "<br><br>";
		//echo "food_requests: " . $food_requests . "<br><br>";	
		
		/* (11-14-19)	
		// Create connection
		$conn = mysqli_connect($servername, $username, $password, $dbname);
		// Check connection
		if (!$conn) {
			die("Connection failed: " . mysqli_connect_error());	
		}
		*/
	
		// Get table_id and res_time_id from original reservation's block_id (get this directly from POST instead?)	
			
		$table_info_sql = "SELECT table_id, res_time_id FROM Timeblocks WHERE block_id = '" . $block_id . "';";
			
		//echo "table_info_sql: " . $table_info_sql . "<br><br>";
			
		$table_info_result = mysqli_query($conn, $table_info_sql);
		while ($row = mysqli_fetch_assoc($table_info_result)) {
			$table_id = $row['table_id'];	
			$res_time_id = $row['res_time_id'];
		}
		mysqli_free_result($table_info_result);
			
		//echo "table_id: " . $table_id . "<br><br>";
		//echo "res_time_id: " . $res_time_id . "<br><br>";
		

		// Check to see if there is already an inactive Timeblock to use

		$gggsql = "SELECT Timeblocks.block_id, Timeblocks.block_time, Timeblocks.block_number, Res_Tables.table_num, Res_Tables.capacity_min, Res_Tables.capacity_max FROM Timeblocks JOIN Res_Tables ON Timeblocks.table_id = Res_Tables.table_id WHERE Timeblocks.table_id = '" . $table_id . "' AND Timeblocks.res_time_id = '" . $res_time_id . "' AND Timeblocks." . $active . " = '0' LIMIT 1;";

		//echo $gggsql . "<br><br>";
	
		$gggresult = mysqli_query($conn, $gggsql);
		if (mysqli_num_rows($gggresult) > 0) {
			
			//echo "Query found timeblock to use <br><br>";
		
			while ($row = mysqli_fetch_assoc($gggresult)) {
				$gggrows[] = $row;
				$ggblock_id = $gggrows[0]['block_id'];
			}
		
			//echo "ggrows0block_id: " . $ggrows[0]['block_id'] . "<br><br>";
			//echo "ggblock_id: " . $ggblock_id . "<br><br>";
		
			mysqli_free_result($gggresult);
			
			$temp_active = "UPDATE Timeblocks SET " . $active . " = '1' WHERE block_id = '" . $ggblock_id . "';";
			
			$temp_active_result = mysqli_query($conn, $temp_active);
			if ( false===$temp_active_result ) {
				//printf("mmsql error: %s\n", mysqli_error($conn));
			} else {
  				//echo 'temp_active: TIMEBLOCK SET TO ACTIVE.<br><br>';
			}
			
			//mysqli_free_result($temp_active_result);
				
			$mmsql = "UPDATE Res_Table_Config SET timeblock_" . $ggblock_id . " = '1' WHERE res_date = '" . $_SESSION['selected_date'] . "';";
		
			//echo "mmsql: " . $mmsql . "<br><br>";
		
			$mmsql_result = mysqli_query($conn, $mmsql);
			if ( false===$mmsql_result ) {
				//printf("mmsql error: %s\n", mysqli_error($conn));
			} else {
  				//echo 'mmsql: TIMEBLOCK SET TO ACTIVE.<br><br>';
			}
	
			$sql = "INSERT INTO Reservations (ResNum, party_name, room_num, party_num, gog_num, out_of_hotel, res_date, res_time, block_id, food_requests, special_requests, notes, res_status, actual_table, affiliation) VALUES ('$res_num', '$party_name', '$room_num', '$party_num', '$gog_num', '$out_of_hotel', '$res_date', '$res_time', '$ggblock_id', '$food_requests', '$special_requests', '$notes', '$res_status', '$actual_table', '$affiliation')";
	
			//echo "sql: " . $sql . "<br><br>";

			if (mysqli_query($conn, $sql)) {
				$message = "New reservation created";
				$smarty->assign('new_res_message_formatting', 'success');
		
				$count_check_sql = "SELECT party_count FROM Res_Table_Count WHERE res_date = '" . $res_date . "' AND table_num = '" . $table_num . "' AND res_time = '" . $res_time . "'";
		
				$count_check_result = mysqli_query($conn, $count_check_sql);
				if (mysqli_num_rows($count_check_result) > 0) {
					$table_count = '';
					while ($row = mysqli_fetch_assoc($count_check_result)) {
						$table_count = $row['party_count'];	
						//echo "row0: " . $row['party_count'] . "<br><br>";
					}
					//echo "table_count 1: " . $table_count . "<br><br>";
					//echo "party_num: " . $party_num . "<br><br>";
					//echo "gog_num: " . $gog_num . "<br><br>";
					$table_count += $party_num + $gog_num;
					//echo "table_count 2: " . $table_count . "<br><br>";
			
					$update_count = "UPDATE Res_Table_Count SET party_count = '" . $table_count . "' WHERE res_date = '" . $res_date . "' AND table_num = '" . $table_num . "' AND res_time = '" . $res_time . "'";	
			
					if (mysqli_query($conn, $update_count)) {
						$message = "Res_Table_Count record updated.<br><br>";
						//echo $message;
					} else {
						$message = " Count Record Update Error: " . mysqli_error();
						//echo $message;
					}
			
				} else {
					$table_count += $party_num + $gog_num;
			
					$update_count = "INSERT INTO Res_Table_Count (party_count, res_date, table_num, res_time) VALUES ('$table_count', '$res_date', '$table_num', '$res_time');";
			
					if (mysqli_query($conn, $update_count)) {
						$message = "New Res_Table_Count record created.<br><br>";
						//echo $message;
					} else {
						$message = " Count Record Create Error: " . mysqli_error();
						//echo $message;
					}
				}
				
			} else {
				$message = "Error. No record created: " . mysqli_connect_error($conn);
				$smarty->assign('new_res_message_formatting', 'fail');
			}
			
			/* (11-14-19)	
			mysqli_close($conn);
			*/
		
		
		} else {
			//echo "Query found NO available timeblock results <br><br>";
		
			mysqli_free_result($ggresult);
	
			$hhsql = "SELECT Res_Tables.table_num, Timeblocks.block_name, Timeblocks.block_time, Timeblocks.block_number, Timeblocks.original_block_time FROM Timeblocks JOIN Res_Tables ON Timeblocks.table_id = Res_Tables.table_id WHERE Timeblocks.table_id = '" . $table_id . "' AND Timeblocks.res_time_id = '" . $res_time_id . "' LIMIT 1;";
	
			if ($query = mysqli_query($conn, $hhsql)) {
				while ($row = mysqli_fetch_assoc($query)) {
					$hhrows[] = $row;
					$table_num = $hhrows[0]['table_num'];
					$block_name = $hhrows[0]['block_name'];
					$block_time = $hhrows[0]['block_time'];
					$block_number = $hhrows[0]['block_number'];
					$original_block_time = $hhrows[0]['original_block_time'];
				}
	
				$iisql = "INSERT INTO Timeblocks (block_name, block_time, block_number, " . $active . ", res_time_id, table_id, duplicate, original_block_time) VALUES ('" . $block_name . "', '" . $block_time . "', '" . $block_number . "', '1', '" . $res_time_id . "', '" . $table_id . "', '1', '" . $original_block_time . "');";
	
				//echo "iisql: " . $iisql . "<br><br>";
			
				if ($iisql_query = mysqli_query($conn, $iisql)) {
				//echo "iisql: TIMEBLOCK CREATED.<br><br>";
				//$message = "Reservation Updated";
				//$smarty->assign('new_res_message_formatting', 'success');
				} else {
				//echo "iisql: TIMEBLOCK CREATION FAILED.<br><br>";
				//$message = "Error. No record updated: " . mysqli_connect_error($conn);
				//$smarty->assign('new_res_message_formatting', 'fail');
				}
			
				$new_timeblock_id = mysqli_insert_id($conn);
			
				mysqli_free_result($iisql_query);
			
				//echo "new_timeblock_id: " . $new_timeblock_id . "<br><br>";
			
				$kksql = "ALTER TABLE Res_Table_Config ADD timeblock_" . $new_timeblock_id . " tinyint(1) NOT NULL DEFAULT 0;";
			
				$kksql .= "UPDATE Res_Table_Config SET timeblock_" . $new_timeblock_id . " = '1' WHERE res_date = '" . $_SESSION['selected_date'] . "';";
	
				if ($kksql_query = mysqli_multi_query($conn, $kksql)) {
					//echo "kksql: NEW TIMEBLOCK FIELD ADDED TO CONFIG.<br><br>";
					//$message = "Reservation Updated";
					//$smarty->assign('new_res_message_formatting', 'success');
				} else {
					//echo "kksql: FAILED TO ADD NEW TIMEBLOCK FIELD TO CONFIG.<br><br>";
					//$message = "Error. No record updated: " . mysqli_connect_error($conn);
					//$smarty->assign('new_res_message_formatting', 'fail');
				}
			
				/* (11-14-19)	
				mysqli_close($conn);
				*/
	
				$jjsql = "SELECT Timeblocks.block_id, Timeblocks.block_time, MIN(Timeblocks.block_number) FROM Timeblocks WHERE NOT EXISTS (SELECT NULL FROM Reservations WHERE Timeblocks.block_id = Reservations.block_id AND Reservations.res_date = '" . $_SESSION['selected_date'] . "') AND Timeblocks." . $active . " = '1' AND Timeblocks.res_time_id = '" . $res_time_id . "' AND Timeblocks.table_id = '" . $table_id . "';";
		
				//echo "jjsql: " . $jjsql . "<br><br>";
			
				/* (11-14-19)
				// Create connection
				$conn = mysqli_connect($servername, $username, $password, $dbname);
				// Check connection
				if (!$conn) {
					die("Connection failed: " . mysqli_connect_error());	
				}
				*/
	
				if ($query = mysqli_query($conn, $jjsql)) {
					while ($row = mysqli_fetch_assoc($query)) {
						$jjrows[] = $row;
						$block_id = $jjrows[0]['block_id'];
					}
	
					$sql = "INSERT INTO Reservations (ResNum, party_name, room_num, party_num, gog_num, out_of_hotel, res_date, res_time, block_id, food_requests, special_requests, notes, res_status, actual_table, affiliation) VALUES ('$res_num', '$party_name', '$room_num', '$party_num', '$gog_num', '$out_of_hotel', '$res_date', '$res_time', '$block_id', '$food_requests', '$special_requests', '$notes', '$res_status', '$actual_table', '$affiliation')";
	
					//echo "sql: " . $sql . "<br><br>";

					if (mysqli_query($conn, $sql)) {
						$message = "New reservation created";
						//echo $message . "<br><br>";
						$smarty->assign('new_res_message_formatting', 'success');
		
						$count_check_sql = "SELECT party_count FROM Res_Table_Count WHERE res_date = '" . $res_date . "' AND table_num = '" . $table_num . "' AND res_time = '" . $res_time . "';";
		
						$count_check_result = mysqli_query($conn, $count_check_sql);
						if (mysqli_num_rows($count_check_result) > 0) {
							$table_count = '';
							while ($row = mysqli_fetch_assoc($count_check_result)) {
								$table_count = $row['party_count'];	
								//echo "row0: " . $row['party_count'] . "<br><br>";
							}
							//echo "table_count 1: " . $table_count . "<br><br>";
							//echo "party_num: " . $party_num . "<br><br>";
							//echo "gog_num: " . $gog_num . "<br><br>";
							$table_count += $party_num + $gog_num;
							//echo "table_count 2: " . $table_count . "<br><br>";
			
							$update_count = "UPDATE Res_Table_Count SET party_count = '" . $table_count . "' WHERE res_date = '" . $res_date . "' AND table_num = '" . $table_num . "' AND res_time = '" . $res_time . "'";	
			
							if (mysqli_query($conn, $update_count)) {
								$message = "Res_Table_Count record updated.<br><br>";
								//echo $message;
							} else {
								$message = " Count Record Update Error: " . mysqli_error();
								//echo $message;
							}
			
						} else {
							$table_count += $party_num + $gog_num;
			
							$update_count = "INSERT INTO Res_Table_Count (party_count, res_date, table_num, res_time) VALUES ('$table_count', '$res_date', '$table_num', '$res_time');";
			
							if (mysqli_query($conn, $update_count)) {
								$message = "New Res_Table_Count record created.<br><br>";
								//echo $message;
							} else {
								$message = " Count Record Create Error: " . mysqli_error();
								//echo $message;
							}
						}	
		
		
					} else {
						$message = "Error. No record created: " . mysqli_connect_error($conn);
						$smarty->assign('new_res_message_formatting', 'fail');
					}
			
					/* (11-14-19)	
					mysqli_close($conn);
					*/	
				
				} else {
				//echo "Unabe to find block_id: " . mysqli_error($conn) . ".<br><br>";
				}
		
			}
		}
		}
		}

//END Duplicate affiliation Reservations	
		
	} // End affiliation break	
		
	} // End fake break
		
} else {

	$smarty->assign('new_res_message_formatting', 'none');
	
}

//END Create New Reservation









// BEGIN Guestlist dropdown

/* (11-14-19)
// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());	
}
*/

// Version 1

//$bbbsql = "SELECT Res_RDP.GuestName, Res_RDP.RoomNum, Res_RDP.ResNum FROM Res_RDP WHERE Res_RDP.ArrivalDate <= '" . $_SESSION['selected_date'] . "' AND Res_RDP.DepartureDate >= '" . $_SESSION['selected_date'] . "';";


//Version 2

//$bbbsql = "SELECT Res_RDP.GuestName, Res_RDP.RoomNum, Res_RDP.ResNum FROM Res_RDP INNER JOIN Reservations ON Reservations.ResNum = Res_RDP.ResNum WHERE NOT EXISTS (SELECT NULL FROM Reservations WHERE Reservations.ResNum = Res_RDP.ResNum AND Reservations.res_date = '" . $_SESSION['selected_date'] . "') AND Res_RDP.ArrivalDate <= '" . $_SESSION['selected_date'] . "' AND Res_RDP.DepartureDate >= '" . $_SESSION['selected_date'] . "';";


// Version 3

//$bbbsql = "SELECT Res_RDP.GuestName, Res_RDP.RoomNum, Res_RDP.ResNumNumeric, Res_RDP.People1, Res_RDP.People2, Res_RDP.People3, Res_RDP.People4 FROM Res_RDP WHERE NOT EXISTS (SELECT NULL FROM Reservations WHERE Reservations.ResNum = Res_RDP.ResNumNumeric AND Reservations.res_date = '" . $_SESSION['selected_date'] . "') AND Res_RDP.ArrivalDate <= '" . $_SESSION['selected_date'] . "' AND Res_RDP.DepartureDate >= '" . $_SESSION['selected_date'] . "' AND Res_RDP.RoomType <> 'N/RWTL' AND Res_RDP.ResStatus <= '6' AND Res_RDP.MarketCode <> 'mn' AND Res_RDP.ResType NOT IN ('M', 'X') ORDER BY Res_RDP.RoomNum ASC;";







// Version 4

// The version below is the correct version for when business is open. It has been temporarily disabled to adjust the ResStatus field so that test data can be used.

//$bbbsql = "SELECT Res_RDP.GuestName, Res_RDP.RoomNum, Res_RDP.ResNumNumeric, Res_RDP.People1, Res_RDP.People2, Res_RDP.People3, Res_RDP.People4, Res_RDP_Comment.Comment1, Res_RDP_Comment.Comment2, Res_RDP_Comment.Comment3, Res_RDP_Comment.Comment4, Res_RDP_Comment.Comment5, Res_RDP_Comment.Comment6, Res_RDP_Comment.Comment7, Res_RDP_Comment.Comment8, Res_RDP_Comment.Comment9, Res_RDP_Comment.Comment10, Res_RDP_Comment.Comment11, Res_RDP_Comment.Comment12, Res_RDP_Comment.Comment13, Res_RDP_Comment.Comment14, Res_RDP_Comment.Comment15, Res_RDP_Comment.Comment16, Res_RDP_Comment.Comment17, Res_RDP_Comment.Comment18, Res_RDP_Comment.Comment19, Res_RDP_Comment.Comment20, Res_RDP_Comment.Comment21, Res_RDP_Comment.Comment22, Res_RDP_Comment.Comment23, Res_RDP_Comment.Comment24, Res_RDP_Comment.Comment25 FROM Res_RDP JOIN Res_RDP_Comment ON Res_RDP.ResNum = Res_RDP_Comment.ResNum WHERE NOT EXISTS (SELECT NULL FROM Reservations WHERE Reservations.ResNum = Res_RDP.ResNumNumeric AND Reservations.res_date = '" . $_SESSION['selected_date'] . "') AND Res_RDP.ArrivalDate <= '" . $_SESSION['selected_date'] . "' AND Res_RDP.DepartureDate >= '" . $_SESSION['selected_date'] . "' AND Res_RDP.RoomType <> 'N/RWTL' AND Res_RDP.ResStatus <= '6' AND Res_RDP.MarketCode <> 'mn' AND Res_RDP.ResType NOT IN ('M', 'X') ORDER BY Res_RDP.RoomNum ASC;";

//echo "Here is bbbsql: " . $bbbsql . "<br><br>";

// This is the test version without comments:


$bbbsql = "SELECT Res_RDP.GuestName, Res_RDP.RoomNum, Res_RDP.ResNumNumeric, Res_RDP.People1, Res_RDP.People2, Res_RDP.People3, Res_RDP.People4, Res_RDP.Phone1 FROM Res_RDP WHERE NOT EXISTS (SELECT NULL FROM Reservations WHERE Reservations.ResNum = Res_RDP.ResNumNumeric AND Reservations.res_date = '" . $_SESSION['selected_date'] . "' AND res_type = '" . $res_type . "') AND Res_RDP.ArrivalDate <= '" . $_SESSION['selected_date'] . "' AND Res_RDP.DepartureDate >= '" . $_SESSION['selected_date'] . "' AND Res_RDP.RoomType <> 'N/RWTL' AND Res_RDP.ResStatus <= '6' AND Res_RDP.MarketCode <> 'mn' AND Res_RDP.ResType NOT IN ('M', 'X') ORDER BY Res_RDP.RoomNum ASC;";

//echo "Here is bbbsql: " . $bbbsql . "<br><br>";









// The version below is the test version to use for when business is not open.

//$bbbsql = "SELECT Res_RDP.GuestName, Res_RDP.RoomNum, Res_RDP.ResNumNumeric, Res_RDP.People1, Res_RDP.People2, Res_RDP.People3, Res_RDP.People4, Res_RDP_Comment.Comment1, Res_RDP_Comment.Comment2, Res_RDP_Comment.Comment3, Res_RDP_Comment.Comment4, Res_RDP_Comment.Comment5, Res_RDP_Comment.Comment6, Res_RDP_Comment.Comment7, Res_RDP_Comment.Comment8, Res_RDP_Comment.Comment9, Res_RDP_Comment.Comment10, Res_RDP_Comment.Comment11, Res_RDP_Comment.Comment12, Res_RDP_Comment.Comment13, Res_RDP_Comment.Comment14, Res_RDP_Comment.Comment15, Res_RDP_Comment.Comment16, Res_RDP_Comment.Comment17, Res_RDP_Comment.Comment18, Res_RDP_Comment.Comment19, Res_RDP_Comment.Comment20, Res_RDP_Comment.Comment21, Res_RDP_Comment.Comment22, Res_RDP_Comment.Comment23, Res_RDP_Comment.Comment24, Res_RDP_Comment.Comment25 FROM Res_RDP JOIN Res_RDP_Comment ON Res_RDP.ResNum = Res_RDP_Comment.ResNum WHERE NOT EXISTS (SELECT NULL FROM Reservations WHERE Reservations.ResNum = Res_RDP.ResNumNumeric AND Reservations.res_date = '" . $_SESSION['selected_date'] . "') AND Res_RDP.ArrivalDate <= '" . $_SESSION['selected_date'] . "' AND Res_RDP.DepartureDate >= '" . $_SESSION['selected_date'] . "' AND Res_RDP.RoomType <> 'N/RWTL' AND Res_RDP.MarketCode <> 'mn' AND Res_RDP.ResType NOT IN ('M', 'X') ORDER BY Res_RDP.RoomNum ASC;";

//echo "bbbsql: " . $bbbsql . "<br><br>";

if ($query = mysqli_query($conn, $bbbsql)) {
	while ($row = mysqli_fetch_assoc($query)) {
			$bbbrows[] = $row;
	}
	$smarty->assign('Guestlist', $bbbrows);	
	mysqli_free_result($query);
} else {
	//echo "Error Avail Blocks: " . mysqli_connect_error($conn);
	//$message = "Error: " . mysqli_connect_error($conn);
}

/* (11-14-19)	
mysqli_close($conn);
*/

// END Guestlist dropdown






// BEGIN Show available blocks in Reservation Time dropdown

/* (11-14-19)
// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());	
}
*/

$nsql = "SELECT DISTINCT Res_Times.res_time FROM Res_Times INNER JOIN Timeblocks ON Res_Times.res_time_id = Timeblocks.res_time_id WHERE NOT EXISTS (SELECT NULL FROM Reservations WHERE Timeblocks.block_id = Reservations.block_id AND Reservations.res_date = '" . $_SESSION['selected_date'] . "' AND Reservations.res_status != '2') AND Timeblocks." . $active . " = '1'";

if ($query = mysqli_query($conn, $nsql)) {
	while ($row = mysqli_fetch_assoc($query)) {
			$nrows[] = $row;
	}
	$smarty->assign('Avail_Timeblocks_Layout', $nrows);	
	mysqli_free_result($query);
} else {
	//echo "Error: " . mysqli_connect_error($conn);
	//$message = "Error: " . mysqli_connect_error($conn);
}

/* (11-14-19)	
mysqli_close($conn);
*/

// END Show available blocks in Reservation Time dropdown









// BEGIN Show 'Available Times & Tables' dropdown

/* (11-14-19)
// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());	
}
*/

$adsql = "SELECT Timeblocks.block_id, Timeblocks.block_time, Res_Tables.table_num, Res_Tables.capacity_max, Res_Tables.capacity_min FROM Timeblocks JOIN Res_Tables ON Timeblocks.table_id = Res_Tables.table_id WHERE NOT EXISTS (SELECT NULL FROM Reservations WHERE Timeblocks.block_id = Reservations.block_id AND Reservations.res_date = '" . $_SESSION['selected_date'] . "') AND Timeblocks." . $active . " = '1' AND Res_Tables.table_active = '1' ORDER BY Timeblocks.block_time ASC, Res_Tables.capacity_max ASC;";


//echo "absql" . $absql{$i} . "<br><br>";


if ($adquery = mysqli_query($conn, $adsql)) {
	while ($row = mysqli_fetch_assoc($adquery)) {
			$adrows[] = $row;
	}
	$smarty->assign('Reschedule', $adrows);	
	mysqli_free_result($adquery);
} else {
	//echo "Error: " . mysqli_connect_error($conn);
	//$message = "Error: " . mysqli_connect_error($conn);
}

/* (11-14-19)	
mysqli_close($conn);
*/

// END Show 'Available Times & Tables' dropdown

$table_setup_sql = "SELECT res_time, column_num FROM Res_Times WHERE " . $enabled_times . " = '1' ORDER BY column_num ASC, res_time ASC";

//echo "absql" . $absql{$i} . "<br><br>";


if ($table_setup_query = mysqli_query($conn, $table_setup_sql)) {
	while ($row = mysqli_fetch_assoc($table_setup_query)) {
		if ($row['column_num'] == '1') {
			$colonetimes[] = substr($row['res_time'], 0, 5);
			$colonetimesClean[] = str_replace(":", "", substr($row['res_time'], 0, 5));
		} elseif ($row['column_num'] == '2') {
			$col2times[] = substr($row['res_time'], 0, 5);
			$col2timesClean[] = str_replace(":", "", substr($row['res_time'], 0, 5));
		} elseif ($row['column_num'] == '3') {
			$col3times[] = substr($row['res_time'], 0, 5);
			$col3timesClean[] = str_replace(":", "", substr($row['res_time'], 0, 5));
		}
	}
	mysqli_free_result($table_setup_query);
} else {
	//echo "Error: " . mysqli_connect_error($conn);
	//$message = "Error: " . mysqli_connect_error($conn);
}

//echo '<pre>' . print_r($colonetimes, TRUE) . '</pre>';
//echo '<pre>' . print_r($colonetimesClean, TRUE) . '</pre>';
//echo '<pre>' . print_r($col2times, TRUE) . '</pre>';
//echo '<pre>' . print_r($col2timesClean, TRUE) . '</pre>';
//echo '<pre>' . print_r($col3times, TRUE) . '</pre>';
//echo '<pre>' . print_r($col3timesClean, TRUE) . '</pre>';




//$colonetimes = array("18:00", "18:01", "18:30", "19:30", "20:15");
//$colonetimesClean = array("1800", "1801", "1830", "1930", "2015");
$smarty->assign('colonetimesClean', $colonetimesClean);

//$col2times = array("18:10", "18:45", "19:45", "20:30");
//$col2timesClean = array("1810", "1845", "1945", "2030");
$smarty->assign('col2timesClean', $col2timesClean);

//$col3times = array("18:20", "19:00", "20:00");
//$col3timesClean = array("1820", "1900", "2000");
$smarty->assign('col3timesClean', $col3timesClean);









// Update Res_Tables table based on selected_date's Res_Config_Table record

$row_number = array("1", "2", "3", "4", "5", "6", "7", "8");
//$row_number = array("1", "2");
$smarty->assign('NumRows', $row_number);

//Clear out updaterows array
$updaterows = array();

//First, update the Res_Tables table based on the date's Res_Table_Config record


/* (11-14-19)
// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
	die("Connection failed: " . mysqli_error());	
}
*/

//echo "SESSION selected_date: " . $_SESSION['selected_date'] . "<br><br>";


$update22sql = "SELECT * FROM Res_Table_Config WHERE res_date = '" . $_SESSION['selected_date'] . "';";

//echo "updatesql: " . $update22sql . "<br><br>";

$update22result = mysqli_query($conn, $update22sql);
if (mysqli_num_rows($update22result) > 0) {
	
	//echo "existing table_config record found.<br><br>";
	
	while ($row = mysqli_fetch_assoc($update22result)) {
			$updaterows[] = $row;
	}
	//echo '<pre>' . print_r($updaterows, TRUE) . '</pre>';
	mysqli_free_result($update22result);
	
	$update_timeblock_rows_sql = "SELECT block_id, breakfast_active, lunch_active, dinner_active FROM Timeblock_Config WHERE table_config_id = '" . $updaterows[0]['table_config_id'] . "'";
	//echo "update_timeblock_rows_sql: " . $update_timeblock_rows_sql . "<br><br>";
	$update_timeblock_rows_result = mysqli_query($conn, $update_timeblock_rows_sql);
	if (mysqli_num_rows($update_timeblock_rows_result) > 0) {
		while ($row = mysqli_fetch_assoc($update_timeblock_rows_result)) {
			$update_timeblock_rows[$row['block_id']] = $row;	
		}
		//echo '<pre>' . print_r($update_timeblock_rows, TRUE) . '</pre>';
	}
	
} else {
	
	//echo "No table_config record found. Creating.<br><br>";
	
	$createsql = "INSERT INTO Res_Table_Config (res_date) VALUES ('" . $_SESSION['selected_date'] . "');";

	//echo "createsql: " . $createsql . "<br><br>";
	
	if (mysqli_query($conn, $createsql)) {
		
		//echo "Res_Table_Config Record created.<br><br>";
		
		// Get last inserted config record

		$last_insert_sql = "SELECT LAST_INSERT_ID()";
		$last_insert_result = mysqli_query($conn, $last_insert_sql);
		while ($row = mysqli_fetch_assoc($last_insert_result)) {
			$table_config_id = $row['LAST_INSERT_ID()'];
		};
		
		$select_all_timeblocks_sql = "SELECT block_id, breakfast_default_active, lunch_default_active, dinner_default_active FROM Timeblocks";
		
		//echo "select_all_timeblocks_sql" . $select_all_timeblocks_sql . "<br><br>";
		
		$select_all_timeblocks_result = mysqli_query($conn, $select_all_timeblocks_sql);
		while ($row = mysqli_fetch_assoc($select_all_timeblocks_result)) {
			$block_id = $row['block_id'];
			$breakfast_active = $row['breakfast_default_active'];
			$lunch_active = $row['lunch_default_active'];
			$dinner_active = $row['dinner_default_active'];
			$insert_timeblock_config_sql = "INSERT INTO Timeblock_Config (table_config_id, block_id, breakfast_active, lunch_active, dinner_active) VALUES ('$table_config_id', '$block_id', '$breakfast_active', '$lunch_active', '$dinner_active')";	
			//echo "insert_timeblock_config_sql: " . $insert_timeblock_config_sql . "<br><br>";
			if (mysqli_query($conn, $insert_timeblock_config_sql)) {
				//echo "Successfully created timeblock_config record";
			} else {
				//echo "Successfully created timeblock_config record";	
			}
		}
		
		$selectBlockTimes = "SELECT block_id, block_time, original_block_time FROM Timeblocks;";
		
		if ($resetQuery = mysqli_query($conn, $selectBlockTimes)) {
			
			//echo "Well, we made it this far.<br><br>";
			
			$i = '0';
			while ($row = mysqli_fetch_assoc($resetQuery)) {
				$rows[] = $row;
		
				$block_id = $rows[$i]['block_id'];
				$block_time = $rows[$i]['block_time'];
				$original_block_time = $rows[$i]['original_block_time'];
		
				//echo "block_id: " . $block_id . "<br><br>";
				//echo "block_time: " . $block_time . "<br><br>";
				//echo "original_block_time: " . $original_block_time . "<br><br>";
		
				$resetBlockTimes = "UPDATE Timeblocks SET block_time = '" . $original_block_time . "' WHERE block_id = '" . $block_id . "';";
			
				if (mysqli_query($conn, $resetBlockTimes)) {
					//echo "Timeblocks reset to original time.<br><br>";
				} else {
					//echo "Failed to reset timeblocks to original time.<br><br>";
				}
		
			$i++;
		
			}
			//$block_id = $rows[0]['block_id'];
			mysqli_free_result($resetQuery);
		} else {
			//echo "Error on resetQuery: " . mysqli_error($conn);
			//$message = "Error: " . mysqli_connect_error($conn);
		}
		
		
		
		$update22sql = "SELECT * FROM Res_Table_Config WHERE res_date = '" . $_SESSION['selected_date'] . "';";

		//echo "update22sql: " . $update22sql . "<br><br>";

		if ($query = mysqli_query($conn, $update22sql)) {
			while ($row = mysqli_fetch_assoc($query)) {
				$updaterows[] = $row;
			}
			//echo '<pre>' . print_r($updaterows, TRUE) . '</pre>';
			mysqli_free_result($query);
			
			$update_timeblock_rows_sql = "SELECT block_id, breakfast_active, lunch_active, dinner_active FROM Timeblock_Config WHERE table_config_id = '" . $updaterows[0]['table_config_id'] . "'";
			//echo "update_timeblock_rows_sql: " . $update_timeblock_rows_sql . "<br><br>";
			$update_timeblock_rows_result = mysqli_query($conn, $update_timeblock_rows_sql);
			if (mysqli_num_rows($update_timeblock_rows_result) > 0) {
				while ($row = mysqli_fetch_assoc($update_timeblock_rows_result)) {
					$update_timeblock_rows[$row['block_id']] = $row;	
				}
				//echo '<pre>' . print_r($update_timeblock_rows, TRUE) . '</pre>';
			}
			
		} else {
		$message = "Error: " . mysqli_error($conn);
		//echo $message;
		}
	}
}


foreach ($row_number as $z) {

	$zsql{$z} = "SELECT * FROM Res_Tables WHERE table_row = '" . $z . "' ORDER BY table_num ASC;";
	
	//echo "zsqlOFz: " . $zsql{$z} . "<br><br>";
	
	
	if ($zresult = mysqli_query($conn, $zsql{$z})) {
		
		$z2 = 1;
		
		while ($zrow = mysqli_fetch_row($zresult)) {
			
			//echo "current_table_id: " . $zrow[0] . "<br><br>";
			$current_table_id = $zrow[0];
			
			$updatesql = "UPDATE Res_Tables SET capacity_min = '" . $updaterows[0]['table_min_' . $current_table_id . ''] . "', capacity_max = '" . $updaterows[0]['table_max_' . $current_table_id . ''] . "', wasatch = '" . $updaterows[0]['table_wasatch_' . $current_table_id . ''] . "', table_active = '" . $updaterows[0]['table_active_' . $current_table_id . ''] . "' WHERE table_id = '" . $current_table_id . "';";
			
			//echo "updatesql: " . $updatesql . "<br><br>";
			
			if (mysqli_query($conn, $updatesql)) {
				$message = "Tables updated successfully.<br><br>";
				//echo $message;
			} else {
				$message = "Error: " . mysqli_error($conn);
				//echo $message;
			}
			
		$z2++;
		
		}
	}
	
	mysqli_free_result($zresult);
}

$z3sql = "SELECT block_id, " . $active . " FROM Timeblocks ORDER BY block_id ASC;";
	
if ($z3result = mysqli_query($conn, $z3sql)) {	
	$z3 = 1;	
	while ($z3row = mysqli_fetch_row($z3result)) {	
		$current_timeblock = $z3row[0];
		
		$update3sql = "UPDATE Timeblocks SET " . $active . " = '" . $update_timeblock_rows[$current_timeblock][$active] . "' WHERE block_id = '" . $current_timeblock . "';";
		//$update3sql = "UPDATE Timeblocks SET active = '" . $updaterows[0]['timeblock_' . $current_timeblock . ''] . "' WHERE block_id = '" . $current_timeblock . "';";
		
		//echo "update3sql: " . $update3sql . "<br><br>";
		//echo "current_timeblocK: " . $current_timeblock . "<br><br>";
		
		if (mysqli_query($conn, $update3sql)) {
			$message = "Timeblocks updated successfully.<br><br>";
			//echo $message;
		} else {
			$message = "Error: " . mysqli_error($conn);
			//echo $message;
		}
	$z3++;
	}
	mysqli_free_result($z3result);
}

// Reset timeblocks back to original time.

$reset_timeblocks_sql = "SELECT block_id, block_time, original_block_time FROM Timeblocks;";

//echo "reset_timeblocks_sql: " . $reset_timeblocks_sql . "<br><br>"

if ($reset_timeblocks_result = mysqli_query($conn, $reset_timeblocks_sql)) {
	$y1 = '0';
	while ($row = mysqli_fetch_assoc($reset_timeblocks_result)) {
		$rows[] = $row;
		
		$block_id = $rows[$y1]['block_id'];
		$original_block_time = $rows[$y1]['original_block_time'];
		
		//echo "block_id: " . $block_id . "<br><br>";
		//echo "original_block_time: " . $original_block_time . "<br><br>";
		
		$original_time_sql = "UPDATE Timeblocks SET block_time = '" . $original_block_time . "' WHERE block_id = '" . $block_id . "';";
		
		//echo "original_time_sql: " . $original_time_sql . "<br><br>";
		
		if (mysqli_query($conn, $original_time_sql)) {
			//echo "Successfully reset block_id: " . $block_id . " to original time: " . $original_block_time . ".<br><br>";
		} else {
			//echo "Failure to reset block_id: " . $block_id . " to original time.<br><br>";
		}
		
		$y1++;
	}
	
}

mysqli_free_result($reset_timeblocks_result);




// Set Wasatch timeblocks

$selectWasatch = "SELECT block_id, block_time, original_block_time FROM Timeblocks JOIN Res_Tables ON Timeblocks.table_id = Res_Tables.table_id WHERE Res_Tables.wasatch = '1';";

if ($wasatchQuery = mysqli_query($conn, $selectWasatch)) {
			
	//echo "Well, we made it this far.<br><br>";
			
	//$y2 = '0';
	while ($row = mysqli_fetch_assoc($wasatchQuery)) {
		//$rows[] = $row;
		
		//echo '<pre>' . print_r($row, TRUE) . '</pre>';
		
		$block_id = $row['block_id'];
		$block_time = $row['block_time'];
		$original_block_time = $row['original_block_time'];
		//$table_id = $row[$y2]['tableID'];
		
		//echo "block_id: " . $block_id . "<br><br>";
		//echo "block_time: " . $block_time . "<br><br>";
		//echo "original_block_time: " . $original_block_time . "<br><br>";
		
		$changeWasatch = "UPDATE Timeblocks SET block_time = '18:01:00' WHERE block_id = '" . $block_id . "';";
		
		//echo "changeWasatch: " . $changeWasatch . "<br><br>";
		
		if (mysqli_query($conn, $changeWasatch)) {
			//echo "Timeblock: " . $block_id . " changed to wasatch time. for table_id: " . $table_id . ".<br><br>";
		} else {
			//echo "Failed to change timeblocks to wasatch time.<br><br>";
		}
		
	//$y2++;
		
	}
	//$block_id = $rows[0]['block_id'];
} else {
	//echo "Error on resetQuery: " . mysqli_error($conn);
	//$message = "Error: " . mysqli_connect_error($conn);
}

mysqli_free_result($wasatchQuery);

/* (11-14-19)	
mysqli_close($conn);
*/
















// START Col 1

$i=0;
foreach ($colonetimes as $value) {
	
	$cleanValue = str_replace(":","",$value);
	//$smarty->assign('colOneHeader' . $i, $value);

// BEGIN Time

/* (11-14-19)
// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());	
}
*/

$asql{$i} = "SELECT block_time FROM Timeblocks WHERE block_time = '" . $value . "'";

if ($result = mysqli_query($conn, $asql{$i})) {
	$atime{$i} = "";
	while ($row = mysqli_fetch_row($result)) {
		$atime{$i} = $row[0];
	}
	$smarty->assign($cleanValue . '_Time', $atime{$i});	
	mysqli_free_result($result);
} else {
	//echo "Time Error: " . mysqli_connect_error($conn);
	//$message = "Error: " . mysqli_connect_error($conn);
}

/* (11-14-19)	
mysqli_close($conn);
*/

// END TIME


// BEGIN Total Seatings

/* (11-14-19)
// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());	
}
*/

//$bsql{$i} = "SELECT Timeblocks.block_name, Reservations.res_date, Reservations.first_name, Reservations.last_name, Reservations.party_num, Reservations.special_requests, Reservations.block_id, Timeblocks.block_time FROM Reservations JOIN Timeblocks ON Reservations.block_id = Timeblocks.block_id AND res_date = '" . $_SESSION['selected_date'] . "' AND Reservations.res_status != '2' AND Timeblocks.block_time = '" . $value . "' GROUP BY block_id ASC";
	
$bsql{$i} = "SELECT party_count FROM Res_Table_Count WHERE res_date = '" . $_SESSION['selected_date'] . "' AND res_time = '" . $value . "';";	
//echo "bsql: " . $bsql{$i} . "<br><br>";

if ($result = mysqli_query($conn, $bsql{$i})) {
	$bheadcount{$i} = 0;
	while ($row = mysqli_fetch_row($result)) {
		$bheadcount{$i} += $row[0]; 
	}
	$smarty->assign($cleanValue . '_Total', $bheadcount{$i});	
	mysqli_free_result($result);
} else {
	//echo "Error: " . mysqli_connect_error($conn);
	//$message = "Error: " . mysqli_connect_error($conn);
}

/* (11-14-19)	
mysqli_close($conn);
*/

// END Total Seatings
	

	
	
// BEGIN Table Count

/* (11-14-19)	
// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());	
}
*/

$abcsql{$i} = "SELECT party_count, table_num FROM Res_Table_Count WHERE res_date = '" . $_SESSION['selected_date'] . "' AND res_time = '" . $value . "';";
	
//echo $abcsql{$i};

if ($result = mysqli_query($conn, $abcsql{$i})) {
	while ($row = mysqli_fetch_row($result)) {
		
		//echo "row0: " . $row[0] . "<br><br>";
		//echo "row1: " . $row[1] . "<br><br>";
		//echo "cleanValue: " . $cleanValue . "<br><br>";
		
		$smarty->assign($cleanValue . '_'  . $row[1] . '_Table', $row[0]);	
	}
	
	mysqli_free_result($result);
} else {
	//echo "Error: " . mysqli_error($conn);
	//$message = "Error: " . mysqli_connect_error($conn);
}

/* (11-14-19)	
mysqli_close($conn);
*/

//$smarty->assign('1800_11_Total', '99');	
	
// END Table Count
	


// BEGIN Show list of current reservations

/* (11-14-19)
// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());	
}
*/

$csql{$i} = "SELECT Timeblocks.block_name, Reservations.res_date, Reservations.last_name, Reservations.party_num, Reservations.special_requests, Reservations.block_id, Reservations.res_id, Timeblocks.block_time, Reservations.res_status, Reservations.notes, Reservations.party_adults, Reservations.party_children, Reservations.phone, Reservations.room_num, Res_Tables.table_num, Reservations.party_name, Res_Tables.capacity_max, Timeblocks.res_time_id, Timeblocks.table_id, Reservations.gog_num, Reservations.out_of_hotel, Reservations.food_requests, Res_Tables.wasatch, Reservations.actual_table, Timeblocks.duplicate, Reservations.affiliation, Reservations.phone, Reservations.sms_opt_in FROM Reservations JOIN Timeblocks ON Reservations.block_id = Timeblocks.block_id JOIN Res_Tables ON Timeblocks.table_id = Res_Tables.table_id AND res_date = '" . $_SESSION['selected_date'] . "' AND Reservations.res_status != '2' AND Timeblocks.block_time = '" . $value . "' ORDER BY Res_Tables.table_num ASC, Timeblocks.duplicate ASC";

if ($query = mysqli_query($conn, $csql{$i})) {
	$crows{$i} = array();
	while ($row = mysqli_fetch_row($query)) {
		$crows{$i}[] = $row;
	}
	$smarty->assign($cleanValue . '_Res', $crows{$i});
	mysqli_free_result($query);
} else {
	//echo "Error: " . mysqli_connect_error($conn);
	//$message = "Error: " . mysqli_connect_error($conn);
}

/* (11-14-19)	
mysqli_close($conn);
*/

// END Show list of current reservations


// BEGIN Show list of available blocks

/*(11-14-19)
// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());	
}
*/

$hsql{$i} = "SELECT Timeblocks.block_id, Timeblocks.block_time, Timeblocks.block_number, Res_Tables.table_num, Res_Tables.capacity_min, Res_Tables.capacity_max, Res_Tables.wasatch, Timeblocks.duplicate FROM Timeblocks JOIN Res_Tables ON Timeblocks.table_id = Res_Tables.table_id WHERE NOT EXISTS (SELECT NULL FROM Reservations WHERE Timeblocks.block_id = Reservations.block_id AND Reservations.res_date = '" . $_SESSION['selected_date'] . "' AND Reservations.res_status != '2') AND Timeblocks." . $active . " = '1' AND Timeblocks.block_time = '" . $value . "' ORDER BY Res_Tables.capacity_max ASC;";
	
//echo $hsql{$i} . "<br><br>";

if ($query = mysqli_query($conn, $hsql{$i})) {
	while ($row = mysqli_fetch_assoc($query)) {
			$hrows{$i}[] = $row;
	}
	$smarty->assign($cleanValue . '_Avail', $hrows{$i});	
	mysqli_free_result($query);
} else {
	//echo "Error: " . mysqli_connect_error($conn);
	//$message = "Error: " . mysqli_connect_error($conn);
}

/* (11-14-19)	
mysqli_close($conn);
*/

// END Show list of available blocks



// BEGIN Show 'Reservations available to assign' dropdown

/* (11-14-19)
// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());	
}
*/

$mmmsql{$i} = "SELECT Reservations.res_id, Reservations.party_name, Reservations.room_num FROM Reservations WHERE Reservations.res_date = '" . $_SESSION['selected_date'] . "' AND Reservations.res_status != '2' AND Reservations.res_time = '" . $value . "' AND Reservations.block_id = '131';";


//echo $mmmsql{$i};


if ($query = mysqli_query($conn, $mmmsql{$i})) {
	$mmmrows{$i} = array();
	while ($row = mysqli_fetch_assoc($query)) {
			$mmmrows{$i}[] = $row;
	}
	$smarty->assign($cleanValue . '_Assign', $mmmrows{$i});	
	mysqli_free_result($query);
} else {
	//echo "Error: " . mysqli_connect_error($conn);
	//$message = "Error: " . mysqli_connect_error($conn);
}

/* (11-14-19)	
mysqli_close($conn);
*/

// END Show 'Reservations available to assign' dropdown


	
// BEGIN Show message history


$sms_history{$i} = "SELECT users_sms.toNumber, users_sms.manual_timestamp, users_sms.body, users_sms.fromNumber FROM users_sms JOIN Reservations ON users_sms.toNumber = Reservations.phone OR users_sms.fromNumber = Reservations.phone JOIN Timeblocks ON Reservations.block_id = Timeblocks.block_id AND Reservations.res_date = '" . $selected_date . "' AND Reservations.res_status != '2' AND Timeblocks.block_time = '" . $value . "' ORDER BY users_sms.manual_timestamp DESC LIMIT 50";

//echo "This is the sms_history query: " . $sms_history{$i} . "<br><br>";

if ($query = mysqli_query($conn, $sms_history{$i})) {
	while ($row = mysqli_fetch_row($query)) {
		
		//echo '<pre>' . print_r($row, TRUE) . '</pre>';
		
		$row[1] = date("m-j-Y g:ia", strtotime($row[1]));
		
		if($row[0] === '+18015095311') {
			//echo "This is the replyMessageId from guest: " . $row[3] . "<br><br>"; 
			$row[3] = 'from_guest';	
		} else {
			//echo "This is the replyMessageId from gmd: " . $row[3] . "<br><br>"; 
			$row[3] = 'from_gmd';	
		}
		$sms_history_rows{$i}[] = $row;
		//echo '<pre>' . print_r($sms_history_rows{$i}, TRUE) . '</pre>';
	}
	$smarty->assign($cleanValue . '_SMS', $sms_history_rows{$i});
	mysqli_free_result($query);
} else {
	//echo "Error: " . mysqli_connect_error($conn);
	//$message = "Error: " . mysqli_connect_error($conn);
}


// END Show message history	
	
	

$i++;
} // end foreach loop

// END Col 1






// START Col 2

$i=0;
foreach ($col2times as $value) {
	
	$cleanValue = str_replace(":","",$value);

// BEGIN Time

/* (11-14-19)
// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());	
}
*/

$gsql{$i} = "SELECT block_time FROM Timeblocks WHERE block_time = '" . $value . "'";

if ($result = mysqli_query($conn, $gsql{$i})) {
	$gtime{$i} = "";
	while ($row = mysqli_fetch_row($result)) {
		$gtime{$i} = $row[0]; 
	}
	$smarty->assign($cleanValue . '_Time', $gtime{$i});	
	mysqli_free_result($result);
} else {
	//echo "Error: " . mysqli_connect_error($conn);
	//$message = "Error: " . mysqli_connect_error($conn);
}

/* (11-14-19)	
mysqli_close($conn);
*/

// END TIME
	
	
// BEGIN Total Seatings

/* (11-14-19)
// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());	
}
*/
	
$dsql{$i} = "SELECT party_count FROM Res_Table_Count WHERE res_date = '" . $_SESSION['selected_date'] . "' AND res_time = '" . $value . "';";	

if ($result = mysqli_query($conn, $dsql{$i})) {
	$dheadcount{$i} = 0;
	while ($row = mysqli_fetch_row($result)) {
		$dheadcount{$i} += $row[0]; 
	}
	$smarty->assign($cleanValue . '_Total', $dheadcount{$i});	
	mysqli_free_result($result);
} else {
	//echo "Error: " . mysqli_connect_error($conn);
	//$message = "Error: " . mysqli_connect_error($conn);
}

/* (11-14-19)	
mysqli_close($conn);
*/

// END Total Seatings	
	
	
	
	
// BEGIN Table Count

/* (11-14-19)	
// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());	
}
*/

$aabcsql{$i} = "SELECT party_count, table_num FROM Res_Table_Count WHERE res_date = '" . $_SESSION['selected_date'] . "' AND res_time = '" . $value . "';";
	
//echo $abcsql{$i};

if ($result = mysqli_query($conn, $aabcsql{$i})) {
	while ($row = mysqli_fetch_row($result)) {
		
		//echo "row0: " . $row[0] . "<br><br>";
		//echo "row1: " . $row[1] . "<br><br>";
		//echo "cleanValue: " . $cleanValue . "<br><br>";
		
		$smarty->assign($cleanValue . '_'  . $row[1] . '_Table', $row[0]);	
	}
	
	mysqli_free_result($result);
} else {
	//echo "Error: " . mysqli_error($conn);
	//$message = "Error: " . mysqli_connect_error($conn);
}

/* (11-14-19)	
mysqli_close($conn);
*/

//$smarty->assign('1800_11_Total', '99');	
	
// END Table Count	
	
	
	

// BEGIN Show list of current reservations

/* (11-14-19)
// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());	
}
*/

$esql{$i} = "SELECT Timeblocks.block_name, Reservations.res_date, Reservations.last_name, Reservations.party_num, Reservations.special_requests, Reservations.block_id, Reservations.res_id, Timeblocks.block_time, Reservations.res_status, Reservations.notes, Reservations.party_adults, Reservations.party_children, Reservations.phone, Reservations.room_num, Res_Tables.table_num, Reservations.party_name, Res_Tables.capacity_max, Timeblocks.res_time_id, Timeblocks.table_id, Reservations.gog_num, Reservations.out_of_hotel, Reservations.food_requests, Res_Tables.wasatch, Reservations.actual_table, Timeblocks.duplicate, Reservations.affiliation, Reservations.phone, Reservations.sms_opt_in FROM Reservations JOIN Timeblocks ON Reservations.block_id = Timeblocks.block_id JOIN Res_Tables ON Timeblocks.table_id = Res_Tables.table_id AND res_date = '" . $_SESSION['selected_date'] . "' AND Reservations.res_status != '2' AND Timeblocks.block_time = '" . $value . "' ORDER BY Res_Tables.table_num ASC, Timeblocks.duplicate ASC";

if ($query = mysqli_query($conn, $esql{$i})) {
	$erows{$i} = array();
	while ($row = mysqli_fetch_row($query)) {
		$erows{$i}[] = $row;
	}
	$smarty->assign($cleanValue . '_Res', $erows{$i});
	mysqli_free_result($query);
} else {
	//echo "Error: " . mysqli_connect_error($conn);
	//$message = "Error: " . mysqli_connect_error($conn);
}

/* (11-14-19)	
mysqli_close($conn);
*/

// END Show list of current reservations
	
	

// BEGIN Show list of available blocks

/* (11-14-19)
// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());	
}
*/

$fsql{$i} = "SELECT Timeblocks.block_id, Timeblocks.block_time, Timeblocks.block_number, Res_Tables.table_num, Res_Tables.capacity_min, Res_Tables.capacity_max, Res_Tables.wasatch, Timeblocks.duplicate FROM Timeblocks JOIN Res_Tables ON Timeblocks.table_id = Res_Tables.table_id WHERE NOT EXISTS (SELECT NULL FROM Reservations WHERE Timeblocks.block_id = Reservations.block_id AND Reservations.res_date = '" . $_SESSION['selected_date'] . "' AND Reservations.res_status != '2') AND Timeblocks." . $active . " = '1' AND Timeblocks.block_time = '" . $value . "' ORDER BY Res_Tables.capacity_max ASC;";

if ($query = mysqli_query($conn, $fsql{$i})) {
	while ($row = mysqli_fetch_assoc($query)) {
			$frows{$i}[] = $row;
	}
	$smarty->assign($cleanValue . '_Avail', $frows{$i});	
	mysqli_free_result($query);
} else {
	//echo "Error: " . mysqli_connect_error($conn);
	//$message = "Error: " . mysqli_connect_error($conn);
}

/* (11-14-19)	
mysqli_close($conn);
*/

// END Show list of available blocks	
	

	
	
// BEGIN Show 'Reservations available to assign' dropdown

/* (11-14-19)
// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());	
}
*/

$nnnsql{$i} = "SELECT Reservations.res_id, Reservations.party_name, Reservations.room_num FROM Reservations WHERE Reservations.res_date = '" . $_SESSION['selected_date'] . "' AND Reservations.res_status != '2' AND Reservations.res_time = '" . $value . "' AND Reservations.block_id = '131';";


//echo $mmmsql{$i};


if ($query = mysqli_query($conn, $nnnsql{$i})) {
	$nnnrows{$i} = array();
	while ($row = mysqli_fetch_assoc($query)) {
		$nnnrows{$i}[] = $row;
	}
	$smarty->assign($cleanValue . '_Assign', $nnnrows{$i});	
	mysqli_free_result($query);
} else {
	//echo "Error: " . mysqli_connect_error($conn);
	//$message = "Error: " . mysqli_connect_error($conn);
}

/* (11-14-19)	
mysqli_close($conn);
*/

// END Show 'Reservations available to assign' dropdown	


// BEGIN Show message history


$sms_history_two{$i} = "SELECT users_sms.toNumber, users_sms.manual_timestamp, users_sms.body, users_sms.fromNumber FROM users_sms JOIN Reservations ON users_sms.toNumber = Reservations.phone OR users_sms.fromNumber = Reservations.phone JOIN Timeblocks ON Reservations.block_id = Timeblocks.block_id AND Reservations.res_date = '" . $selected_date . "' AND Reservations.res_status != '2' AND Timeblocks.block_time = '" . $value . "' ORDER BY users_sms.manual_timestamp DESC LIMIT 50";

//echo "This is the sms_history query: " . $sms_history{$i} . "<br><br>";

if ($query = mysqli_query($conn, $sms_history_two{$i})) {
	while ($row = mysqli_fetch_row($query)) {
		
		//echo '<pre>' . print_r($row, TRUE) . '</pre>';
		
		if($row[0] === '+18015095311') {
			//echo "This is the replyMessageId from guest: " . $row[3] . "<br><br>"; 
			$row[3] = 'from_guest';	
		} else {
			//echo "This is the replyMessageId from gmd: " . $row[3] . "<br><br>"; 
			$row[3] = 'from_gmd';	
		}
		$sms_history_rows_two{$i}[] = $row;
		//echo '<pre>' . print_r($sms_history_rows{$i}, TRUE) . '</pre>';
	}
	$smarty->assign($cleanValue . '_SMS', $sms_history_rows_two{$i});
	mysqli_free_result($query);
} else {
	//echo "Error: " . mysqli_connect_error($conn);
	//$message = "Error: " . mysqli_connect_error($conn);
}


// END Show message history	
		


$i++;

} // end foreach loop

// END Col 2






// START Col 3

$i=0;
foreach ($col3times as $value) {
	
	$cleanValue = str_replace(":","",$value);
	
	
// BEGIN Time

/* (11-14-19)
// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());	
}
*/

$jsql{$i} = "SELECT block_time FROM Timeblocks WHERE block_time = '" . $value . "'";

if ($result = mysqli_query($conn, $jsql{$i})) {
	$jtime{$i} = "";
	while ($row = mysqli_fetch_row($result)) {
		$jtime{$i} = $row[0]; 
	}
	$smarty->assign($cleanValue . '_Time', $jtime{$i});	
	mysqli_free_result($result);
} else {
	//echo "Error: " . mysqli_connect_error($conn);
	//$message = "Error: " . mysqli_connect_error($conn);
}

/* (11-14-19)	
mysqli_close($conn);
*/

// END Time	
	
	
// BEGIN Total Seatings

/* (11-14-19)
// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());	
}
*/
	
$ksql{$i} = "SELECT party_count FROM Res_Table_Count WHERE res_date = '" . $_SESSION['selected_date'] . "' AND res_time = '" . $value . "';";	

if ($result = mysqli_query($conn, $ksql{$i})) {
	$kheadcount{$i} = "";
	while ($row = mysqli_fetch_row($result)) {
		$kheadcount{$i} += $row[0]; 
	}
	$smarty->assign($cleanValue . '_Total', $kheadcount{$i});	
	mysqli_free_result($result);
} else {
	//echo "Error: " . mysqli_connect_error($conn);
	//$message = "Error: " . mysqli_connect_error($conn);
}

/* (11-14-19)	
mysqli_close($conn);
*/

// END Total Seatings
	
	
	
	
// BEGIN Table Count

/* (11-14-19)	
// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());	
}
*/

$aaabcsql{$i} = "SELECT party_count, table_num FROM Res_Table_Count WHERE res_date = '" . $_SESSION['selected_date'] . "' AND res_time = '" . $value . "';";
	
//echo $abcsql{$i};

if ($result = mysqli_query($conn, $aaabcsql{$i})) {
	while ($row = mysqli_fetch_row($result)) {
		
		//echo "row0: " . $row[0] . "<br><br>";
		//echo "row1: " . $row[1] . "<br><br>";
		//echo "cleanValue: " . $cleanValue . "<br><br>";
		
		$smarty->assign($cleanValue . '_'  . $row[1] . '_Table', $row[0]);	
	}
	
	mysqli_free_result($result);
} else {
	//echo "Error: " . mysqli_error($conn);
	//$message = "Error: " . mysqli_connect_error($conn);
}

/* (11-14-19)	
mysqli_close($conn);
*/

//$smarty->assign('1800_11_Total', '99');	
	
// END Table Count		
	
	
	
// BEGIN Show list of current reservations

/* (11-14-19)
// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());	
}
*/

$lsql{$i} = "SELECT Timeblocks.block_name, Reservations.res_date, Reservations.last_name, Reservations.party_num, Reservations.special_requests, Reservations.block_id, Reservations.res_id, Timeblocks.block_time, Reservations.res_status, Reservations.notes, Reservations.party_adults, Reservations.party_children, Reservations.phone, Reservations.room_num, Res_Tables.table_num, Reservations.party_name, Res_Tables.capacity_max, Timeblocks.res_time_id, Timeblocks.table_id, Reservations.gog_num, Reservations.out_of_hotel, Reservations.food_requests, Res_Tables.wasatch, Reservations.actual_table, Timeblocks.duplicate, Reservations.affiliation, Reservations.phone, Reservations.sms_opt_in FROM Reservations JOIN Timeblocks ON Reservations.block_id = Timeblocks.block_id JOIN Res_Tables ON Timeblocks.table_id = Res_Tables.table_id AND res_date = '" . $_SESSION['selected_date'] . "' AND Reservations.res_status != '2' AND Timeblocks.block_time = '" . $value . "' ORDER BY Res_Tables.table_num ASC, Timeblocks.duplicate ASC";

if ($query = mysqli_query($conn, $lsql{$i})) {
	$lrows{$i} = array();
	while ($row = mysqli_fetch_row($query)) {
		$lrows{$i}[] = $row;
	}
	$smarty->assign($cleanValue . '_Res', $lrows{$i});
	mysqli_free_result($query);
} else {
	//echo "Error: " . mysqli_connect_error($conn);
	//$message = "Error: " . mysqli_connect_error($conn);
}

/* (11-14-19)	
mysqli_close($conn);
*/

// END Show list of current reservations	
	
	
	
// BEGIN Show list of available blocks

/* (11-14-19)
// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());	
}
*/

$msql{$i} = "SELECT Timeblocks.block_id, Timeblocks.block_time, Timeblocks.block_number, Res_Tables.table_num, Res_Tables.capacity_min, Res_Tables.capacity_max, Res_Tables.wasatch, Timeblocks.duplicate FROM Timeblocks JOIN Res_Tables ON Timeblocks.table_id = Res_Tables.table_id WHERE NOT EXISTS (SELECT NULL FROM Reservations WHERE Timeblocks.block_id = Reservations.block_id AND Reservations.res_date = '" . $_SESSION['selected_date'] . "' AND Reservations.res_status != '2') AND Timeblocks." . $active . " = '1' AND Timeblocks.block_time = '" . $value . "' ORDER BY Res_Tables.capacity_max ASC;";

if ($query = mysqli_query($conn, $msql{$i})) {
	while ($row = mysqli_fetch_assoc($query)) {
			$mrows{$i}[] = $row;
	}
	$smarty->assign($cleanValue . '_Avail', $mrows{$i});	
	mysqli_free_result($query);
} else {
	//echo "Error: " . mysqli_connect_error($conn);
	//$message = "Error: " . mysqli_connect_error($conn);
}

/* (11-14-19)	
mysqli_close($conn);
*/

// END Show list of available blocks	
		
	
	
// BEGIN Show 'Reservations available to assign' dropdown

/* (11-14-19)
// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());	
}
*/

$ooosql{$i} = "SELECT Reservations.res_id, Reservations.party_name, Reservations.room_num FROM Reservations WHERE Reservations.res_date = '" . $_SESSION['selected_date'] . "' AND Reservations.res_status != '2' AND Reservations.res_time = '" . $value . "' AND Reservations.block_id = '131';";


//echo $mmmsql{$i};


if ($query = mysqli_query($conn, $ooosql{$i})) {
	$ooorows{$i} = array();
	while ($row = mysqli_fetch_assoc($query)) {
		$ooorows{$i}[] = $row;
	}
	$smarty->assign($cleanValue . '_Assign', $ooorows{$i});	
	mysqli_free_result($query);
} else {
	//echo "Error: " . mysqli_connect_error($conn);
	//$message = "Error: " . mysqli_connect_error($conn);
}

/* (11-14-19)	
mysqli_close($conn);
*/

// END Show 'Reservations available to assign' dropdown		
	
	

// BEGIN Show message history


$sms_history_three{$i} = "SELECT users_sms.toNumber, users_sms.manual_timestamp, users_sms.body, users_sms.fromNumber FROM users_sms JOIN Reservations ON users_sms.toNumber = Reservations.phone OR users_sms.fromNumber = Reservations.phone JOIN Timeblocks ON Reservations.block_id = Timeblocks.block_id AND Reservations.res_date = '" . $selected_date . "' AND Reservations.res_status != '2' AND Timeblocks.block_time = '" . $value . "' ORDER BY users_sms.manual_timestamp DESC LIMIT 50";

//echo "This is the sms_history query: " . $sms_history{$i} . "<br><br>";

if ($query = mysqli_query($conn, $sms_history_three{$i})) {
	while ($row = mysqli_fetch_row($query)) {
		
		//echo '<pre>' . print_r($row, TRUE) . '</pre>';
		
		if($row[0] === '+18015095311') {
			//echo "This is the replyMessageId from guest: " . $row[3] . "<br><br>"; 
			$row[3] = 'from_guest';	
		} else {
			//echo "This is the replyMessageId from gmd: " . $row[3] . "<br><br>"; 
			$row[3] = 'from_gmd';	
		}
		$sms_history_rows_three{$i}[] = $row;
		//echo '<pre>' . print_r($sms_history_rows{$i}, TRUE) . '</pre>';
	}
	$smarty->assign($cleanValue . '_SMS', $sms_history_rows_three{$i});
	mysqli_free_result($query);
} else {
	//echo "Error: " . mysqli_connect_error($conn);
	//$message = "Error: " . mysqli_connect_error($conn);
}


// END Show message history		
	


$i++;
} // end foreach loop

// END Col 3











// BEGIN Unassigned

/* (11-14-19)
// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());	
}
*/

$ysql = "SELECT party_name, party_num FROM Reservations WHERE res_date = '" . $_SESSION['selected_date'] . "' AND block_id = '131' GROUP BY party_name ASC";

if ($result = mysqli_query($conn, $ysql)) {
	$yheadcount = "";
	while ($row = mysqli_fetch_row($result)) {
		$yheadcount += $row[1]; 
	}
	$smarty->assign('Unassigned_Total', $yheadcount);	
	mysqli_free_result($result);
} else {
	//echo "Error: " . mysqli_connect_error($conn);
	//$message = "Error: " . mysqli_connect_error($conn);
}

/* (11-14-19)	
mysqli_close($conn);
*/




// Unassigned - Show list of current unassigned reservations

/* (11-14-19)
// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());	
}
*/

$zsql = "SELECT party_name, party_num, res_id FROM Reservations WHERE res_date = '" . $_SESSION['selected_date'] . "' AND block_id = '131' GROUP BY party_name ASC";

if ($query = mysqli_query($conn, $zsql)) {
	$zrows = array();
	while ($row = mysqli_fetch_row($query)) {
		$zrows[] = $row;
	}
	$smarty->assign('Unassigned_Res', $zrows);
	mysqli_free_result($query);
} else {
	//echo "Error: " . mysqli_connect_error($conn);
	//$message = "Error: " . mysqli_connect_error($conn);
}

/* (11-14-19)	
mysqli_close($conn);
*/


// END Unassigned


// Update House Adults total in header menu bar
//houseAdultTotal();


// Update House Children total in header menu bar
//houseChildrenTotal();

// Update House Party total in header menu bar
//housePartyTotal();

// Update Lodge Guest totals (Adults & Children)
//lodgeGuestTotals();

// List unread notifications
unread_notification_list();

// Mark unread messages as read
mark_as_read();

// Update Unread Notification number in menu bar
unread_notification_count();

// Update RDP Guest total in header
RDPGuestTotals();

// Close connection to Auth database
disconnect_auth();

// Close connection to Res database
disconnect_res();

$smarty->display($tab_meal . '_layout.tpl');

?>
