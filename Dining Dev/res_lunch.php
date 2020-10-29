<?php

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

// Assign session variables
$smarty->assign('username', $_SESSION['username']);
$smarty->assign('user_firstName', $_SESSION['user_firstName']);
$smarty->assign('perms_res_access', $_SESSION['perms_res_access']);
$smarty->assign('perms_res_newUser', $_SESSION['perms_res_newUser']);
$smarty->assign('perms_res_deleteUser', $_SESSION['perms_res_deleteUser']);
//$smarty->assign('perms_res_timeslots', $_SESSION['perms_res_timeslots']);
$smarty->assign('perms_res_tables', $_SESSION['perms_res_tables']);
$smarty->assign('selected_date', $_SESSION['selected_date']);

//echo '<pre>' . print_r($_SESSION, TRUE) . '</pre>';
//echo '<pre>' . print_r($_POST, TRUE) . '</pre>';


if (!$_SESSION['username']) {
	header("Location: /auth_sign_in.php");	
	exit;
}

if (!$_SESSION['perms_res_access']) {
	header("Location: /no_access.php");	
	exit;
}

$selected_date = $_SESSION['selected_date'];



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

// BEGIN Create/Update Breakfast Record

// Clear out message variable
$message = "";

if(isset($_POST['breakfast_res_num'])) {
	
	//echo '<pre>' . print_r($_POST, TRUE) . '<pre>';

	// Get POST values
	// Define variables and set to empty values
	$res_num = $arrived_guests = "";

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$res_num = validate_input($_POST["breakfast_res_num"]);
		$arrived_guests = validate_input($_POST["arrived_guests"]);	
	} else {
		$message = 'Unable to collect POST values';	
	}

	$uusql = "SELECT res_party_num FROM Res_breakfastandlunch WHERE res_date = '" . $_SESSION['selected_date'] . "' AND ResNum = '$res_num' AND breakfast_record = '1';";

	//echo "uusql: " . $uusql . "<br><br>";
	
	/* (11-14-19)
	// Create connection
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	// Check connection
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());	
	}
	*/

	$uuresult = mysqli_query($conn, $uusql);
	if (mysqli_num_rows($uuresult) > 0) {
	
		//echo "Records were found, apparently<br><br>";
	
		// Update existing record's res_party_total
		while ($row = mysqli_fetch_assoc($uuresult)) {
			$uurows[] = $row;	
		}
		
		//echo "uurows array: " . $uurows[0]['res_party_num'] . "<br><br>";
		//echo "arrived guests: " . $arrived_guests . "<br><br>";
		$breakfast_addition = $uurows[0]['res_party_num'];
		$updated_res_party_num = $breakfast_addition + $arrived_guests;	
		$vvsql = "UPDATE Res_breakfastandlunch SET res_party_num = '$updated_res_party_num', breakfast_addition = '$breakfast_addition' WHERE ResNum = '$res_num';";
		
		//echo "vvsql: " . $vvsql . "<br><br>";
	
		if (mysqli_query($conn, $vvsql)) {
			$message = "Reservation updated";
			$smarty->assign('new_res_message_formatting', 'success');
			//echo $message;
		} else {
			$message = "Error. No record created: " . mysqli_connect_error($conn);
			$smarty->assign('new_res_message_formatting', 'fail');
			//echo $message;
		}
		/* (11-14-19)
		mysqli_close($conn);
		*/
	
		$smarty->assign('new_res_message', $message);
	} else {
	
		//echo "Records were NOT found<br><br>";
	
		// Create new breakfast record for this reservation
		$vvsql = "INSERT INTO Res_breakfastandlunch (breakfast_record, res_date, ResNum, res_party_num, breakfast_addition) VALUES ('1', '" . $_SESSION['selected_date'] . "', '$res_num', '$arrived_guests', '$arrived_guests');";
	
		//echo "vvsql: " . $vvsql . "<br><br>";
	
		if (mysqli_query($conn, $vvsql)) {
			$message = "New record created";
			$smarty->assign('new_res_message_formatting', 'success');
			//echo $message;
		} else {
			$message = "Error. No record created: " . mysqli_connect_error($conn);
			$smarty->assign('new_res_message_formatting', 'fail');
			//echo $message;
		}
		
		/* (11-14-19)
		mysqli_close($conn);
		*/
		
		$smarty->assign('new_res_message', $message);
	}
}

// END Create/Update Breakfast Record



// BEGIN Undo latest Breakfast Record

if(isset($_POST['undo_last_breakfast'])) {
	
	//echo '<pre>' . print_r($_POST, TRUE) . '<pre>';

	// Get POST values
	// Define variables and set to empty values
	$undo_last_breakfast = "";

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$undo_last_breakfast = validate_input($_POST["undo_last_breakfast"]);	
	} else {
		$message = 'Unable to collect POST values';	
	}


	//echo "undo_last_breakfast: " . $undo_last_breakfast . "<br><br>";
		
	//$undo_sql = "SELECT MAX(bandl_timestamp) as most_recent_signin FROM Res_breakfastandlunch;";
	$undo_sql = "SELECT * FROM Res_breakfastandlunch WHERE bandl_id IN (SELECT bandl_id FROM Res_breakfastandlunch WHERE breakfast_record = '1' AND bandl_timestamp = (SELECT MAX(bandl_timestamp) FROM Res_breakfastandlunch)) ORDER BY bandl_id DESC LIMIT 1;";	
				
	if ($undo_query = mysqli_query($conn, $undo_sql)) {
		while ($row = mysqli_fetch_assoc($undo_query)) {
		$undo_rows[] = $row;
		}
			
		//echo "old party_num: " . $undo_rows[0]['res_party_num'] . "<br><br>";
			
		//echo "old lunch_addition: " . $undo_rows[0]['lunch_addition'] . "<br><br>";
			
		$subtracted_party_num = $undo_rows[0]['res_party_num'] - $undo_rows[0]['breakfast_addition'];
			
		//echo "updated subtracted_party_num: " . $subtracted_party_num . "<br><br>";
			
		//echo '<pre>' . print_r($undo_rows, TRUE) . '</pre>';
			
		//echo "latest_lunch_update: " . $latest_lunch_update . "<br><br>";
			
		mysqli_free_result($undo_query);
			
		if ($subtracted_party_num = '0') {
				
			$subtract_sql = "DELETE FROM Res_breakfastandlunch WHERE bandl_id = '" . $undo_rows[0]['bandl_id'] . "' ";
			
			//echo "subtracted_sql: " . $subtract_sql . "<br><br>";
	
			if (mysqli_query($conn, $subtract_sql)) {
				$message = "Breakfast Reservation deleted";
				$smarty->assign('new_res_message_formatting', 'success');
				//echo $message;
			} else {
				$message = "Error. No lunch record created: " . mysqli_connect_error($conn);
				$smarty->assign('new_res_message_formatting', 'fail');
				//echo $message;
			}	
		} else {	
			
			$subtract_sql = "UPDATE Res_breakfastandlunch SET breakfast_addition = '0', res_party_num = '" . $subtracted_party_num . "' WHERE bandl_id = '" . $undo_rows[0]['bandl_id'] . "' ";
			
			//echo "subtracted_sql: " . $subtract_sql . "<br><br>";
	
			if (mysqli_query($conn, $subtract_sql)) {
				$message = "Breakfast Reservation updated";
				$smarty->assign('new_res_message_formatting', 'success');
				//echo $message;
			} else {
				$message = "Error. No lunch record created: " . mysqli_connect_error($conn);
				$smarty->assign('new_res_message_formatting', 'fail');
				//echo $message;
			}
		}
			
	} else {
		//echo "Error Avail Blocks: " . mysqli_connect_error($conn);
		//$message = "Error: " . mysqli_connect_error($conn);
	}
	//echo "Did we get this far?<br><br>";
}

//echo "Did we get this far #2?<br><br>";

// End Undo latest Breakfast Record







// BEGIN Create/Update Lunch Record

// Clear out message variable
$message = "";

if(isset($_POST['lunch_addition'])) {
	
	//echo '<pre>' . print_r($_POST, TRUE) . '</pre>';

	// Get POST values
	// Define variables and set to empty values
	
	$num_one = $num_two = $num_three = $num_four = $num_five = $num_six = $num_seven = $num_eight = $num_nine = $num_ten = $num_eleven = $num_twelve = $num_thirteen = 0;
	$undo_last = '';

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$num_one = validate_input($_POST["num_one"]);
		$num_two = validate_input($_POST["num_two"]);
		$num_three = validate_input($_POST["num_three"]);
		$num_four = validate_input($_POST["num_four"]);
		$num_five = validate_input($_POST["num_five"]);
		$num_six = validate_input($_POST["num_six"]);
		$num_seven = validate_input($_POST["num_seven"]);
		$num_eight = validate_input($_POST["num_eight"]);
		$num_nine = validate_input($_POST["num_nine"]);
		$num_ten = validate_input($_POST["num_ten"]);
		$num_eleven = validate_input($_POST["num_eleven"]);
		$num_twelve = validate_input($_POST["num_twelve"]);
		$num_thirteen = validate_input($_POST["num_thirteen"]);
		$undo_last = validate_input($_POST["undo_last"]);
	} else {
		$message = 'Unable to collect POST values';	
	}
	
	//echo "undo_last: " . $undo_last . "<br><br>";
	
	if ($undo_last == "Undo") {
		
		//$undo_sql = "SELECT MAX(bandl_timestamp) as most_recent_signin FROM Res_breakfastandlunch;";
		$undo_sql = "SELECT * FROM Res_breakfastandlunch WHERE bandl_id IN (SELECT bandl_id FROM Res_breakfastandlunch WHERE lunch_record = '1' AND bandl_timestamp = (SELECT MAX(bandl_timestamp) FROM Res_breakfastandlunch)) ORDER BY bandl_id DESC LIMIT 1;";	
		
		
		//echo "undo_sql: " . $undo_sql . "<br><br>";
		
		if ($undo_query = mysqli_query($conn, $undo_sql)) {
			while ($row = mysqli_fetch_assoc($undo_query)) {
			$undo_rows[] = $row;
			}
			
			//echo "old party_num: " . $undo_rows[0]['res_party_num'] . "<br><br>";
			
			//echo "old lunch_addition: " . $undo_rows[0]['lunch_addition'] . "<br><br>";
			
			$subtracted_party_num = $undo_rows[0]['res_party_num'] - $undo_rows[0]['lunch_addition'];
			
			//echo "updated subtracted_party_num: " . $subtracted_party_num . "<br><br>";
			
			//echo '<pre>' . print_r($undo_rows, TRUE) . '</pre>';
			
			//echo "latest_lunch_update: " . $latest_lunch_update . "<br><br>";
			
			mysqli_free_result($undo_query);
			
			$subtract_sql = "UPDATE Res_breakfastandlunch SET lunch_addition = '0', res_party_num = '" . $subtracted_party_num . "' WHERE bandl_id = '" . $undo_rows[0]['bandl_id'] . "' ";
			
			//echo "subtracted_sql: " . $subtract_sql . "<br><br>";
	
		if (mysqli_query($conn, $subtract_sql)) {
			$message = "Lunch Reservation updated";
			$smarty->assign('new_res_message_formatting', 'success');
			//echo $message;
		} else {
			$message = "Error. No lunch record created: " . mysqli_connect_error($conn);
			$smarty->assign('new_res_message_formatting', 'fail');
			//echo $message;
		}
			
		} else {
			//echo "Error Avail Blocks: " . mysqli_connect_error($conn);
			//$message = "Error: " . mysqli_connect_error($conn);
		}
	} else {
	
	$timestamp = date('Y-m-d H:i:s');
	
	$num_array = array($num_one, $num_two, $num_three, $num_four, $num_five, $num_six, $num_seven, $num_eight, $num_nine, $num_ten, $num_eleven, $num_twelve, $num_thirteen);

	$arrived_guests = 0;
	
	foreach ($num_array as $x) {
		if ($x == "") {
			$x = 0;	
		} 
		//echo "x: " . $x . "<br><br>";
		$arrived_guests += $x;
		//echo "arrived_guests: " . $arrived_guests . "<br><br>";
	}
	
	//echo "arrived_guests: " . $arrived_guests . "<br><br>";
	
	//echo '<pre>' . print_r($num_array, TRUE) . '</pre>';

	//$arrived_guests = $num_one + $num_two + $num_three + $num_four + $num_five + $num_six + $num_seven + $num_eight + $num_nine + $num_10 + $num_eleven + $num_twelve + $num_thirteen;
	
	//echo "arrived_guests: " . $arrived_guests . "<br><br>";
	
	$vvvsql = "SELECT res_party_num FROM Res_breakfastandlunch WHERE res_date = '" . $_SESSION['selected_date'] . "' AND lunch_record = '1';";

	//echo "vvvsql: " . $vvvsql . "<br><br>";
	
	/* (11-14-19)
	// Create connection
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	// Check connection
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());	
	}
	*/

	$vvvresult = mysqli_query($conn, $vvvsql);
	if (mysqli_num_rows($vvvresult) > 0) {
	
		//echo "Records were found, apparently<br><br>";
	
		// Update existing record's res_party_total
		while ($row = mysqli_fetch_assoc($vvvresult)) {
			$vvvrows[] = $row;	
		}
		
		//echo "vvvrows array: " . $vvvrows[0]['res_party_num'] . "<br><br>";
		//echo "arrived guests: " . $arrived_guests . "<br><br>";
		
		$updated_res_party_num = $vvvrows[0]['res_party_num'] + $arrived_guests;	
		
		$xxxsql = "UPDATE Res_breakfastandlunch SET res_party_num = '$updated_res_party_num', bandl_timestamp = '" . $timestamp . "', lunch_addition = '" . $arrived_guests . "' WHERE lunch_record = '1' AND res_date = '" . $_SESSION['selected_date'] . "';";
		
		//echo "xxxsql: " . $xxxsql . "<br><br>";
	
		if (mysqli_query($conn, $xxxsql)) {
			$message = "Lunch Reservation updated";
			$smarty->assign('new_res_message_formatting', 'success');
			//echo $message;
		} else {
			$message = "Error. No lunch record created: " . mysqli_connect_error($conn);
			$smarty->assign('new_res_message_formatting', 'fail');
			//echo $message;
		}
		/* (11-14-19)
		mysqli_close($conn);
		*/
	
		$smarty->assign('new_res_message', $message);
	} else {
	
		//echo "Records were NOT found<br><br>";
	
		// Create new lunch record for this reservation
		$xxxsql = "INSERT INTO Res_breakfastandlunch (lunch_record, res_date, res_party_num, bandl_timestamp, lunch_addition) VALUES ('1', '" . $_SESSION['selected_date'] . "', '$arrived_guests', '" . $timestamp . "', '" . $arrived_guests . "');";
	
		//echo "xxxsql: " . $xxxsql . "<br><br>";
	
		if (mysqli_query($conn, $xxxsql)) {
			$message = "New Lunch record created";
			$smarty->assign('new_res_message_formatting', 'success');
			//echo $message;
		} else {
			$message = "Error. No new lunch record created: " . mysqli_connect_error($conn);
			$smarty->assign('new_res_message_formatting', 'fail');
			//echo $message;
		}
		/* (11-14-19)
		mysqli_close($conn);
		*/
	
		$smarty->assign('new_res_message', $message);
	}
	
	}
}

// END Create/Update Lunch Record









// BEGIN Manual Breakfast Count

if (isset($_POST['breakfast_count'])) {
	
	$manual_breakfast_count = '';
	
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$manual_breakfast_count = validate_input($_POST["breakfast_count"]);
	} else {
		$message = 'Unable to collect POST values';	
	}
	
	// Update Res_Count breakfast record
	
	$manual_breakfast_count_sql = "SELECT res_count_breakfast FROM Res_Count WHERE res_count_date = '" . $_SESSION['selected_date'] . "';";	
	
	$manual_breakfast_result = mysqli_query($conn, $manual_breakfast_count_sql);
	if (mysqli_num_rows($manual_breakfast_result) > 0) {
	
		//echo "Records were found, apparently<br><br>";
	
		// Update existing record's res_party_total
		while ($row = mysqli_fetch_assoc($manual_breakfast_result)) {
			$manual_breakfast_rows[] = $row;	
		}

		$calc_breakfast_count = $manual_breakfast_rows[0]['res_count_breakfast'];
		
		//echo "calc_breakfast_count: " . $calc_breakfast_count . "<br><br>";
		
		$res_count_breakfast_offset = $manual_breakfast_count - $calc_breakfast_count;
		
		//echo "res_count_breakfast_offset: " . $res_count_breakfast_offset . "<br><br>";
		
		$update_breakfast_offset_sql = "UPDATE Res_Count SET res_count_breakfast_offset = '" . $res_count_breakfast_offset . "' WHERE res_count_date = '" . $_SESSION['selected_date'] . "';";
	
		if (mysqli_query($conn, $update_breakfast_offset_sql)) {
			//$smarty->assign('Breakfast_Offset', $res_count_breakfast_offset);
			$message = "Breakfast count offset updated";
			//$smarty->assign('new_res_message_formatting', 'success');
			//echo $message;
		} else {
			$message = "Error. Breakfast offset not updated: " . mysqli_connect_error($conn);
			//$smarty->assign('new_res_message_formatting', 'fail');
			//echo $message;
		}
	} else {
		$message = "ERROR. No Res_Count record found";
		//echo $message;
	}
}

// END Manual Breakfast Count




// BEGIN Manual Lunch Count

if(isset($_POST['count_lunch'])) {
	
	$count_lunch = '';
	
	if($_SERVER['REQUEST_METHOD'] == "POST") {
		$count_lunch = validate_input($_POST['count_lunch']);	
	} else {
		$message = "Unable to retrieve POST";	
	}
	
	//echo "count_lunch: " . $count_lunch . "<br><br>";

	$timestamp = date('Y-m-d H:i:s');
	
	$manual_lunch_count_sql = "SELECT res_party_num FROM Res_breakfastandlunch WHERE res_date = '" . $_SESSION['selected_date'] . "' AND lunch_record = '1';";
	
	//echo "manual_lunch_count_sql : " . $manual_lunch_count_sql . "<br><br>";

	$manual_lunch_count_result = mysqli_query($conn, $manual_lunch_count_sql);
	if (mysqli_num_rows($manual_lunch_count_result) > 0) {
	
		while ($row = mysqli_fetch_assoc($manual_lunch_count_result)) {
			$manual_lunch_count_rows[] = $row;	
		}
		
		//echo "manual_lunch_count_rows: " . $manual_lunch_count_rows[0]['res_party_num'] . "<br><br>";
		
		$manual_lunch_offset = $count_lunch - $manual_lunch_count_rows[0]['res_party_num'];	
		
		//echo "manual_lunch_offset : " . $manual_lunch_offset . "<br><br>";
		
		$manual_lunch_update_sql = "UPDATE Res_breakfastandlunch SET res_party_num = '" . $count_lunch . "', bandl_timestamp = '" . $timestamp . "', lunch_addition = '" . $manual_lunch_offset . "' WHERE lunch_record = '1' AND res_date = '" . $_SESSION['selected_date'] . "';";
	
		if (mysqli_query($conn, $manual_lunch_update_sql)) {
			$message = "Lunch Reservation updated";
			$smarty->assign('new_res_message_formatting', 'success');
			//echo $message;
		} else {
			$message = "Error. No lunch record created: " . mysqli_connect_error($conn);
			$smarty->assign('new_res_message_formatting', 'fail');
			//echo $message;
		}
	
		$smarty->assign('new_res_message', $message);
	} else {
	
		// Create new lunch record for this reservation
		$manual_lunch_create_sql = "INSERT INTO Res_breakfastandlunch (lunch_record, res_date, res_party_num, bandl_timestamp, lunch_addition) VALUES ('1', '" . $_SESSION['selected_date'] . "', '" . $count_lunch . "', '" . $timestamp . "', '" . $count_lunch . "');";
		
		//echo "manual_lunch_create_sql : " . $manual_lunch_create_sql . "<br><br>";
		
		if (mysqli_query($conn, $manual_lunch_create_sql)) {
			$message = "New Lunch record created";
			$smarty->assign('new_res_message_formatting', 'success');
			//echo $message;
		} else {
			$message = "Error. No new lunch record created: " . mysqli_connect_error($conn);
			$smarty->assign('new_res_message_formatting', 'fail');
			//echo $message;
		}
	
		$smarty->assign('new_res_message', $message);
	}

}

// END Manual Lunch Count




// BEGIN Guestlist dropdown

/* (11-14-19)
// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());	
}
*/

//$bbbsql = "SELECT Res_RDP.GuestName, Res_RDP.RoomNum, Res_RDP.ResNumNumeric, Res_RDP.People1, Res_RDP.People2, Res_RDP.People3, Res_RDP.People4 FROM Res_RDP WHERE NOT EXISTS (SELECT NULL FROM Reservations WHERE Reservations.ResNum = Res_RDP.ResNumNumeric AND Reservations.res_date = '" . $_SESSION['selected_date'] . "') AND Res_RDP.ArrivalDate <= '" . $_SESSION['selected_date'] . "' AND Res_RDP.DepartureDate >= '" . $_SESSION['selected_date'] . "' ORDER BY Res_RDP.RoomNum ASC;";

//$bbbsql = "SELECT Res_RDP.GuestName, Res_RDP.RoomNum, Res_RDP.ResNumNumeric, Res_RDP.People1, Res_RDP.People2, Res_RDP.People3, Res_RDP.People4 FROM Res_RDP INNER JOIN Res_breakfastandlunch ON Res_breakfastandlunch.ResNum = Res_RDP.ResNumNumeric AND Res_breakfastandlunch.res_party_num < (Res_RDP.People1 + Res_RDP.People2 + Res_RDP.People3 + Res_RDP.People4) AND Res_breakfastandlunch.res_date = '" . $_SESSION['selected_date'] . "';";

//$bbbsql = "SELECT Res_RDP.GuestName, Res_RDP.RoomNum, Res_RDP.ResNumNumeric, Res_RDP.People1, Res_RDP.People2, Res_RDP.People3, Res_RDP.People4, Res_breakfastandlunch.res_party_num FROM Res_RDP lEFT JOIN Res_breakfastandlunch ON Res_breakfastandlunch.ResNum = Res_RDP.ResNumNumeric WHERE NOT EXISTS (SELECT NULL FROM Res_breakfastandlunch WHERE Res_breakfastandlunch.ResNum = Res_RDP.ResNumNumeric AND Res_breakfastandlunch.res_date = '" . $_SESSION['selected_date'] . "' AND Res_breakfastandlunch.breakfast_record = '1' AND Res_breakfastandlunch.res_party_num = (Res_RDP.People1 + Res_RDP.People2 + Res_RDP.People3 + Res_RDP.People4)) AND Res_RDP.ArrivalDate <= '" . $_SESSION['selected_date'] . "' AND Res_RDP.DepartureDate >= '" . $_SESSION['selected_date'] . "' ORDER BY Res_RDP.RoomNum ASC;";

//$bbbsql = "SELECT Res_RDP.GuestName, Res_RDP.RoomNum, Res_RDP.ResNumNumeric, Res_RDP.People1, Res_RDP.People2, Res_RDP.People3, Res_RDP.People4, Res_breakfastandlunch.res_party_num FROM Res_RDP lEFT JOIN Res_breakfastandlunch ON Res_breakfastandlunch.ResNum = Res_RDP.ResNumNumeric WHERE NOT EXISTS (SELECT NULL FROM Res_breakfastandlunch WHERE Res_breakfastandlunch.ResNum = Res_RDP.ResNumNumeric AND Res_breakfastandlunch.res_date = '" . $_SESSION['selected_date'] . "' AND Res_breakfastandlunch.breakfast_record = '1' AND Res_breakfastandlunch.res_party_num = (Res_RDP.People1 + Res_RDP.People2 + Res_RDP.People3 + Res_RDP.People4)) AND Res_RDP.ArrivalDate <= '" . $_SESSION['selected_date'] . "' AND Res_RDP.DepartureDate >= '" . $_SESSION['selected_date'] . "' AND Res_RDP.RoomType <> 'N/RWTL' AND Res_RDP.ResStatus <= '6' AND Res_RDP.MarketCode <> 'mn' AND Res_RDP.ResType NOT IN ('M', 'X') ORDER BY Res_RDP.RoomNum ASC;";

//$bbbsql = "SELECT Res_RDP.GuestName, Res_RDP.RoomNum, Res_RDP.ResNumNumeric, Res_RDP.People1, Res_RDP.People2, Res_RDP.People3, Res_RDP.People4, Res_breakfastandlunch.res_party_num, Res_breakfastandlunch.res_date FROM Res_RDP LEFT JOIN Res_breakfastandlunch ON Res_breakfastandlunch.ResNum = Res_RDP.ResNumNumeric WHERE NOT EXISTS (SELECT NULL FROM Res_breakfastandlunch WHERE Res_breakfastandlunch.ResNum = Res_RDP.ResNumNumeric AND Res_breakfastandlunch.res_date = '" . $_SESSION['selected_date'] . "' AND Res_breakfastandlunch.breakfast_record = '1' AND Res_breakfastandlunch.res_party_num = (Res_RDP.People1 + Res_RDP.People2 + Res_RDP.People3 + Res_RDP.People4)) AND Res_RDP.ArrivalDate <= '" . $_SESSION['selected_date'] . "' AND Res_RDP.DepartureDate >= '" . $_SESSION['selected_date'] . "' AND Res_RDP.RoomType <> 'N/RWTL' AND Res_RDP.ResStatus <= '6' AND Res_RDP.MarketCode <> 'mn' AND Res_RDP.ResType NOT IN ('M', 'X') AND (Res_RDP.People1 + Res_RDP.People2 + Res_RDP.People3 + Res_RDP.People4) > 0 ORDER BY Res_RDP.RoomNum ASC;";


// The Version below is the correct version for when Rustler is open. It is disabled right now so that there is test data for the offseason.

$bbbsql = "SELECT Res_RDP.GuestName, Res_RDP.RoomNum, Res_RDP.ResNumNumeric, Res_RDP.People1, Res_RDP.People2, Res_RDP.People3, Res_RDP.People4, Res_breakfastandlunch.res_party_num, Res_breakfastandlunch.res_date FROM Res_RDP LEFT JOIN Res_breakfastandlunch ON Res_breakfastandlunch.ResNum = Res_RDP.ResNumNumeric WHERE Res_RDP.ArrivalDate <= '" . $_SESSION['selected_date'] . "' AND Res_RDP.DepartureDate >= '" . $_SESSION['selected_date'] . "' AND Res_RDP.RoomType <> 'N/RWTL' AND Res_RDP.ResStatus <= '6' AND Res_RDP.MarketCode <> 'mn' AND Res_RDP.ResType NOT IN ('M', 'X') AND (Res_RDP.People1 + Res_RDP.People2 + Res_RDP.People3 + Res_RDP.People4) > 0 ORDER BY Res_RDP.RoomNum ASC;";

// The version below is the test version for offseason that removes the ResStatus requirement.

//$bbbsql = "SELECT Res_RDP.GuestName, Res_RDP.RoomNum, Res_RDP.ResNumNumeric, Res_RDP.People1, Res_RDP.People2, Res_RDP.People3, Res_RDP.People4, Res_breakfastandlunch.res_party_num, Res_breakfastandlunch.res_date FROM Res_RDP LEFT JOIN Res_breakfastandlunch ON Res_breakfastandlunch.ResNum = Res_RDP.ResNumNumeric WHERE Res_RDP.ArrivalDate <= '" . $_SESSION['selected_date'] . "' AND Res_RDP.DepartureDate >= '" . $_SESSION['selected_date'] . "' AND Res_RDP.RoomType <> 'N/RWTL' AND Res_RDP.MarketCode <> 'mn' AND Res_RDP.ResType NOT IN ('M', 'X') AND (Res_RDP.People1 + Res_RDP.People2 + Res_RDP.People3 + Res_RDP.People4) > 0 ORDER BY Res_RDP.RoomNum ASC;";


//echo $bbbsql . "<br><br>";

if ($query = mysqli_query($conn, $bbbsql)) {
	$x = 0;
	while ($row = mysqli_fetch_assoc($query)) {
			$bbbrows[$x] = $row;
			
			//echo '<pre>' . print_r($bbbrows[$x], TRUE) . '</pre>';
			
			//echo "res_date?: " . $bbbrows[$x]['res_date'] . "<br><br>";
			
			//echo "pre-IF res_party_num: " . $bbbrows[$x]['res_party_num'] . "<br><br>";
			
			if ($bbbrows[$x]['res_date'] !== $_SESSION['selected_date']) {
				$bbbrows[$x]['res_party_num'] = 0;
				
				//echo "res_party_num is zero?: " . $bbbrows[$x]['res_party_num'] . "<br><br>";
			}
			
			//echo "res_party_num final: " . $bbbrows[$x]['res_party_num'] . "<br><br>";
			
			$x++;
	}
	
	$bbbrows_length = count($bbbrows);
	
	$y = 0;
	while ($y < $bbbrows_length) {
		if ($bbbrows[$y]['res_date'] == $_SESSION['selected_date']) {
			$res_num_to_check = $bbbrows[$y]['ResNumNumeric'];
			$z = 0;
			while ($z < $bbbrows_length) {
				if($bbbrows[$z]['ResNumNumeric'] == $res_num_to_check && $bbbrows[$z]['res_date'] !== $_SESSION['selected_date']) {
					
					//echo "Found an off-date duplicate: <br><br>";
					//echo '<pre>' . print_r($bbbrows[$z], TRUE) . '</pre>';
					
					unset($bbbrows[$z]);
				}
				$z++;
			}
		}
		$y++;
	}
	
	//echo "This is the array with duplicates removed: <br><br>";
	
	//echo '<pre>' . print_r($bbbrows, TRUE) . '</pre>';
	
	$bbbrows_reindexed = array_values($bbbrows);
	
	//echo "This is the reindexed array: <br><br>";
	
	//echo '<pre>' . print_r($bbbrows_reindexed, TRUE) . '</pre>';
	

	$second_sort = array_unique(array_column($bbbrows_reindexed, 'ResNumNumeric'));
	$bbbrows_reindexed_two = array_values(array_intersect_key($bbbrows_reindexed, $second_sort));
	
	//echo "This is the 2ND reindexed array: <br><br>";
	
	//echo '<pre>' . print_r($bbbrows_reindexed_two, TRUE) . '</pre>';
	
	
	$smarty->assign('Guestlist', $bbbrows_reindexed_two);	
	
	$bbbrows_length_two = count($bbbrows_reindexed_two);
	
	$c = 0;
	$people1 = $people2 = $people3 = $people4 = 0;
	while ($c < $bbbrows_length_two) {
		$people1 += $bbbrows_reindexed_two[$c]['People1'];
		$people2 += $bbbrows_reindexed_two[$c]['People2'];
		$people3 += $bbbrows_reindexed_two[$c]['People3'];
		$people4 += $bbbrows_reindexed_two[$c]['People4'];
		$res_party_num += $bbbrows_reindexed_two[$c]['res_party_num'];
		$c++;
	}
	
	$total_breakfast_guests = $people1 + $people2 + $people3 + $people4 - $res_party_num;
	
	mysqli_free_result($query);
	
} else {
	//echo "Error Avail Blocks: " . mysqli_connect_error($conn);
	//$message = "Error: " . mysqli_connect_error($conn);
}

/* (11-14-19)
mysqli_close($conn);
*/

// END Guestlist dropdown

// BEGIN Total Breakfast Guests

/* (11-14-19)
// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());	
}
*/

//$ssql = "SELECT People1, People2, People3, People4 FROM Res_RDP WHERE ArrivalDate <= '" . $_SESSION['selected_date'] . "' AND DepartureDate >= '" . $_SESSION['selected_date'] . "';";

/*

$ssql = "SELECT People1, People2, People3, People4 FROM Res_RDP WHERE ArrivalDate <= '" . $_SESSION['selected_date'] . "' AND DepartureDate >= '" . $_SESSION['selected_date'] . "' AND RoomType <> 'N/RWTL' AND ResStatus <= '6' AND MarketCode <> 'mn' AND ResType NOT IN ('M', 'X') ORDER BY RoomNum ASC;";




	
		//echo "ssql: " . $ssql . "<br><br>";
	
		if ($squery = mysqli_query($conn, $ssql)) {
			$i = 0;
			$people1 = $people2 = $people3 = $people4 = 0;
			while ($row = mysqli_fetch_assoc($squery)) {
				$srows[$i] = $row;
				$people1 += $srows[$i]['People1'];
				$people2 += $srows[$i]['People2'];
				$people3 += $srows[$i]['People3'];
				$people4 += $srows[$i]['People4'];
				$i++;
			}
		}
		$total_breakfast_guests = $people1 + $people2 + $people3 + $people4;
		$smarty->assign('Breakfast_Total', $total_breakfast_guests);

*/

/* (11-14-19)
mysqli_close($conn);
*/

// END Total Breakfast Guests

// BEGIN Arrived Breakfast Guests

/* (11-14-19)
// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());	
}
*/

/*

$ttql = "SELECT res_party_num FROM Res_breakfastandlunch WHERE res_date = '" . $_SESSION['selected_date'] . "' AND breakfast_record = '1';";
	
		//echo "ssql: " . $ssql . "<br><br>";
	
		if ($tquery = mysqli_query($conn, $ttql)) {
			$i = 0;
			$arrived_breakfast = 0;
			while ($row = mysqli_fetch_assoc($tquery)) {
				$trows[$i] = $row;
				$arrived_breakfast += $trows[$i]['res_party_num'];
				$i++;	
			}
		}
		$smarty->assign('Breakfast_Arrived', $arrived_breakfast);

*/

/* (11-14-19)
mysqli_close($conn);
*/

// END Arrived Breakfast Guests







// BEGIN Arrived Lunch Guests

/* (11-14-19)
// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());	
}
*/

$zzql = "SELECT res_party_num FROM Res_breakfastandlunch WHERE res_date = '" . $_SESSION['selected_date'] . "' AND lunch_record = '1';";
	
		//echo "uuql: " . $zzql . "<br><br>";
	
if ($zzquery = mysqli_query($conn, $zzql)) {
	$i = 0;
	$arrived_lunch = 0;
	while ($row = mysqli_fetch_assoc($zzquery)) {
		$zzrows[$i] = $row;
		$arrived_lunch += $zzrows[$i]['res_party_num'];
		$i++;	
	}
}
$smarty->assign('Lunch_Arrived', $arrived_lunch);

/* (11-14-19)
mysqli_close($conn);
*/

// END Arrived Lunch Guests








// BEGIN Breakfast guest list for Lunch

/* (11-14-19)
// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());	
}
*/

/*

//$dsql = "SELECT Res_RDP.GuestName, Res_RDP.RoomNum, Res_RDP.ResNumNumeric, Res_RDP.People1, Res_RDP.People2, Res_RDP.People3, Res_RDP.People4 FROM Res_RDP WHERE Res_RDP.ArrivalDate <= '" . $_SESSION['selected_date'] . "' AND Res_RDP.DepartureDate >= '" . $_SESSION['selected_date'] . "' ORDER BY Res_RDP.RoomNum ASC;";

//$dsql = "SELECT Res_RDP.GuestName, Res_RDP.RoomNum, Res_RDP.ResNumNumeric, Res_RDP.People1, Res_RDP.People2, Res_RDP.People3, Res_RDP.People4, Res_breakfastandlunch.res_party_num FROM Res_RDP lEFT JOIN Res_breakfastandlunch ON Res_breakfastandlunch.ResNum = Res_RDP.ResNumNumeric WHERE NOT EXISTS (SELECT NULL FROM Res_breakfastandlunch WHERE Res_breakfastandlunch.ResNum = Res_RDP.ResNumNumeric AND Res_breakfastandlunch.res_date = '" . $_SESSION['selected_date'] . "' AND Res_breakfastandlunch.breakfast_record = '1' AND Res_breakfastandlunch.res_party_num = (Res_RDP.People1 + Res_RDP.People2 + Res_RDP.People3 + Res_RDP.People4)) AND Res_RDP.ArrivalDate <= '" . $_SESSION['selected_date'] . "' AND Res_RDP.DepartureDate >= '" . $_SESSION['selected_date'] . "' AND Res_RDP.RoomType <> 'N/RWTL' AND Res_RDP.ResStatus <= '6' AND Res_RDP.MarketCode <> 'mn' AND Res_RDP.ResType NOT IN ('M', 'X') ORDER BY Res_RDP.RoomNum ASC;";

$dsql = "SELECT Res_RDP.GuestName, Res_RDP.RoomNum, Res_RDP.ResNumNumeric, Res_RDP.People1, Res_RDP.People2, Res_RDP.People3, Res_RDP.People4, Res_breakfastandlunch.res_party_num FROM Res_RDP LEFT JOIN Res_breakfastandlunch ON Res_breakfastandlunch.ResNum = Res_RDP.ResNumNumeric WHERE NOT EXISTS (SELECT NULL FROM Res_breakfastandlunch WHERE Res_breakfastandlunch.ResNum = Res_RDP.ResNumNumeric AND Res_breakfastandlunch.res_date = '" . $_SESSION['selected_date'] . "' AND Res_breakfastandlunch.breakfast_record = '1' AND Res_breakfastandlunch.res_party_num = (Res_RDP.People1 + Res_RDP.People2 + Res_RDP.People3 + Res_RDP.People4)) AND Res_RDP.ArrivalDate <= '" . $_SESSION['selected_date'] . "' AND Res_RDP.DepartureDate >= '" . $_SESSION['selected_date'] . "' AND Res_RDP.RoomType <> 'N/RWTL' AND Res_RDP.ResStatus <= '6' AND Res_RDP.MarketCode <> 'mn' AND Res_RDP.ResType NOT IN ('M', 'X') ORDER BY Res_RDP.RoomNum ASC;";


if ($query = mysqli_query($conn, $dsql)) {
	while ($row = mysqli_fetch_row($query)) {
		$drows[] = $row;
		
		//echo '<tr><td>' . $row[0] . '</td><td>' . $row[2] . '</td><td>' . $row[3] . '</td><td>' . $row[4] . '</td><td>' . $row[5] . '</td></tr>';
	}
	
	//echo '<pre>' . print_r($drows, TRUE) . '</pre>';
	
	$smarty->assign('Breakfast_Reservations', $drows);
	mysqli_free_result($query);
} else {
	//echo "Error Future Res: " . mysqli_connect_error($conn);
	//$message = "Error: " . mysqli_connect_error($conn);
}

*/

/* (11-14-19)
mysqli_close($conn);
*/

// END Breakfast guest list for Lunch

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

//echo "Did we get this far #3?<br><br>";

$smarty->display('res_lunch.tpl');

//echo "Did we get this far #4?<br><br>";

?>