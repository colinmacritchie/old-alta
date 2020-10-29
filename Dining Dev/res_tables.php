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
$smarty->assign('username',$_SESSION['username']);
$smarty->assign('user_firstName', $_SESSION['user_firstName']);
$smarty->assign('perms_res_access', $_SESSION['perms_res_access']);
$smarty->assign('perms_res_newUser', $_SESSION['perms_res_newUser']);
$smarty->assign('perms_res_deleteUser', $_SESSION['perms_res_deleteUser']);
//$smarty->assign('perms_res_timeslots', $_SESSION['perms_res_timeslots']);
$smarty->assign('perms_res_tables', $_SESSION['perms_res_tables']);
$smarty->assign('selected_date', $_SESSION['selected_date']);

if (!$_SESSION['username']) {
	header("Location: /auth_sign_in.php");	
	exit;
}

if (!$_SESSION['perms_res_access']) {
	header("Location: /no_access.php");	
	exit;
}

if (!$_SESSION['perms_res_tables']) {
	header("Location: /no_access.php");	
	exit;
}

$selected_date = $_SESSION['selected_date'];


// Update House Adults total in header menu bar
houseAdultTotal();

// Update House Children total in header menu bar
houseChildrenTotal();

// Update House Party total in header menu bar
housePartyTotal();

// Update Lodge Guest totals (Adults & Children)
lodgeGuestTotals();

// Update RDP Guest total in header
RDPGuestTotals();


// BEGIN Various Date variables

$date_plus2 = date('Y-m-d', strtotime('+2 years', strtotime(date('Y'))));
$current_time = date('H:i');
$date_display = date('m/d/y');
$serverself = $_SERVER['PHP_SELF'];

$smarty->assign('current_date', $current_date);
$smarty->assign('date_plus', $date_plus);
$smarty->assign('date_display', $date_display);
$smarty->assign('serverself', $serverself);

// END Various Date variables



//echo '<pre>' . print_r($_POST, TRUE) . '<pre>';
//echo '<pre>' . print_r($_SESSION, TRUE) . '</pre>';



// BEGIN Change meal

if(isset($_POST['changemeal'])){

	//echo '<pre>' . print_r($_POST, TRUE) . '<pre>';	
	
	// Get POST values
	// Define variables and set to empty values
	$unformatted_date = "";
	
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$new_meal = validate_input($_POST["new_meal"]);
	} else {
		$message = 'Unable to collect POST values';
		//echo $message;	
	}
	
	$_SESSION['current_meal'] = $new_meal;
	$current_meal = $new_meal;
	$smarty->assign('current_meal', $_SESSION['current_meal']);	
	
}

// END Change meal




// Variable to set the times to use for this tab
if (!$_SESSION['current_meal']) {
	$current_meal = 'dinner';	
} else {
	$current_meal = $_SESSION['current_meal'];
}
$enabled_times = 'enabled_' . $current_meal;
$active = $current_meal . '_active';
$smarty->assign('current_meal', $current_meal);	




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
		$smarty->assign('selected_date', $_SESSION['selected_date']);		
		//echo "Date Updated<br><br>";
	}	
	
}

// BEGIN Change selected date







// BEGIN Reschedule Update

if(isset($_POST['updateres'])) {
	
	
	//echo '<pre>' . print_r($_POST, TRUE) . '</pre>';
	
	

	// Get POST values
	// Define variables and set to empty values
	$original_block_id = $change_block_id = $res_id = $notes = $specialrequests = "";

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$original_block_id = validate_input($_POST["original_block_id"]);	
		$change_block_id = validate_input($_POST["change_block_id"]);
		$res_id = validate_input($_POST["res_id"]);
		$notes = validate_input($_POST["notes"]);
		$specialrequests = validate_input($_POST["specialrequests"]);
	} else {
		$message = 'Unable to collect POST values';	
		//echo $message;
	}
	
	
	if($change_block_id != '') {
		if($change_block_id != $original_block_id) {
			$block_id = $change_block_id;	
		} else {
			$block_id = $original_block_id;	
		}
	} else {
			$block_id = $original_block_id;
	}
	
	//echo "block_id: " . $block_id . "<br><br>";
	//echo "res_id: " . $res_id . "<br><br>";
	//echo "notes: " . $notes . "<br><br>";
	//echo "specialrequests: " . $specialrequests . "<br><br>";
	
	
	
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

	$sql = "UPDATE Reservations SET block_id = '" . $block_id . "', notes = '" . $notes . "', special_requests = '" . $specialrequests . "' WHERE res_id = " . $res_id . ";";

	if (mysqli_query($conn, $sql)) {
		$message = "Reservation updated successfully";
		//echo $message;
	} else {
		$message = "Error: " . mysqli_connect_error($conn);
		//echo $message;
	}
	/* (11-14-19)
	mysqli_close($conn);
	*/
	
	
} 

// END Reschedule Update







// BEGIN Schedule Reservation

if(isset($_POST['scheduleres'])) {


	//echo '<pre>' . print_r($_POST, TRUE) . '</pre>';


	// Get POST values
	// Define variables and set to empty values
	$block_id = $res_id = "";

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$block_id = validate_input($_POST["block_id"]);
		$res_id = validate_input($_POST["schedule_res_id"]);	
	} else {
		$message = 'Unable to collect POST values';	
		//echo $message;
	}
	
	//echo "block_id: " . $block_id . "<br><br>";
	//echo "res_id: " . $res_id . "<br><br>";


	/* (11-14-19)
	// Create connection
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	// Check connection
	if (!$conn) {
		$message = "Error connecting to DB: " . mysqli_connect_error($conn);
		die("Connection failed: " . mysqli_connect_error());	
	} 
	*/
	
	$sgql = "UPDATE Reservations SET block_id = '" . $block_id . "' WHERE res_id = '" . $res_id . "'; ";

	//echo "sql: " . $sgql . "<br><br>";

	if (mysqli_query($conn, $sgql)) {
		$message = "Table info updated successfully";
		//echo $message;
	} else {
		$message = "Error: " . mysqli_connect_error($conn);
		//echo $message;
	}
	/* (11-14-19)
	mysqli_close($conn);
	*/
	
} 

// END Schedule Reservation







// BEGIN Change Table info 

if(isset($_POST['updatetable'])) {


	//echo '<pre>' . print_r($_POST, TRUE) . '</pre>';

	// Get POST values
	// Define variables and set to empty values
	$capacity_min = $capacity_max =$table_id = $wasatch = $original_block_time = "";

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$capacity_min = validate_input($_POST["capacity_min"]);
		$capacity_max = validate_input($_POST["capacity_max"]);	
		$table_id = validate_input($_POST["table_id"]);
		$wasatch = validate_input($_POST["wasatch"]);
		$table_name = validate_input($_POST["table_name"]);
	} else {
		$message = 'Unable to collect POST values';	
		//echo $message;
	}
	
	//echo "capacity_min: " . $capacity_min . "<br><br>";
	//echo "capacity_max: " . $capacity_max . "<br><br>";
	//echo "table_id: " . $table_id . "<br><br>";
	
	if ($wasatch == '1') {	
		
		$sql_one = "SELECT block_id, block_time, original_block_time FROM Timeblocks WHERE table_id = '" . $table_id . "';";

		if ($query = mysqli_query($conn, $sql_one)) {
			$i = '0';
			while ($row = mysqli_fetch_assoc($query)) {
				$rows[] = $row;
		
				$block_id = $rows[$i]['block_id'];
				$block_time = $rows[$i]['block_time'];
				$original_block_time = $rows[$i]['original_block_time'];
		
				//echo "block_id: " . $block_id . "<br><br>";
				//echo "block_time: " . $block_time . "<br><br>";
				//echo "original_block_time: " . $original_block_time . "<br><br>";
		
				$updatesql = "UPDATE Timeblocks SET block_time = '18:01:00' WHERE block_id = '" . $block_id . "';";
			
				if ($update_query = mysqli_query($conn, $updatesql)) {
					//echo "Successfully updated block_time.<br><br>";
					mysqli_free_result($update_query);
					
					// Update timeblock_config record.
					
					$find_table_config_sql = "SELECT table_config_id FROM Res_Table_Config WHERE res_date = '" . $_SESSION['selected_date'] . "'";
					
					if ($find_table_config_query = mysqli_query($conn, $find_table_config_sql)) {
						while ($row = mysqli_fetch_assoc($find_table_config_query)) {
							$table_config_id = $row['table_config_id'];
						};
					};
					
					//$find_timeblock_config_sql = "SELECT timeblock_config_id FROM Timeblock_Config WHERE table_config_id = '" . $table_config_id . "' AND block_id =  '" . $block_id . "';";
					
					//echo "find_timeblock_config_sql: " . $find_timeblock_config_sql . "<br><br>";
										
					//$find_timeblock_config_query = mysqli_query($conn, $find_timeblock_config_sql);
					
					$update_timeblock_config_sql = "UPDATE Timeblock_Config SET " . $active . " = '0' WHERE table_config_id = '" . $table_config_id . "' AND block_id =  '" . $block_id . "'";					
			
					//echo "update_timeblock_config_sql: " . $update_timeblock_config_sql . "<br><br>";
			
					if ($update_timeblock_result = mysqli_query($conn, $update_timeblock_config_sql)) {
						//echo "Successfully updated the timeblock_config record for timeblock_id: " . $block_id . ".<br><br>";
						
					} else {
						//echo "Failure in updating timeblock_config record.<br><br>";
					}
					mysqli_free_result($update_timeblock_result);			
					
				} else {
					//echo "Failed to update block_time.<br><br>";
				}
		
				$i++;
		
			}	
			
		} else {
			//echo "Error New Res: " . mysqli_connect_error($conn);
			//$message = "Error: " . mysqli_connect_error($conn);
		}
		
		mysqli_free_result($query);
		
		
		$activate_one_sql = "SELECT block_id FROM Timeblocks WHERE table_id = '" . $table_id . "' AND duplicate = '0' AND original_block_time = '18:00:00' LIMIT 1;";
					
		//echo "activate_one_sql: " . $activate_one_sql . "<br><br>";
					
		$activate_result = mysqli_query($conn, $activate_one_sql);
					
		if (mysqli_num_rows($activate_result) == 1) {
						
			//echo "Successfully found one 6pm block time for wasatch";
						
			while ($row = mysqli_fetch_assoc($activate_result)) {
				$six_block_id = $row['block_id'];
						
			}
			mysqli_free_result($activate_result);
						
			//echo "six_block_id: " . $six_block_id . "<br><br>";
			
			$reactivate_one_sql = $update_config_sql = "UPDATE Timeblock_Config tc JOIN Res_Table_Config rtc ON rtc.table_config_id = tc.table_config_id SET tc." . $active . " = '1' WHERE tc.block_id = '" . $six_block_id . "' AND rtc.res_date = '" . $_SESSION['selected_date'] . "'";
			
				
			// Old query:			
			//$reactivate_one_sql = $update_config_sql = "UPDATE Res_Table_Config SET timeblock_" . $six_block_id . " = '1' WHERE res_date = '" . $_SESSION['selected_date'] . "';";
						
			//echo "reactivate_one_sql: " . $reactivate_one_sql . "<br><br>";
						
			if ($reactivate_result = mysqli_query($conn, $reactivate_one_sql)) {
				//echo "Successfully reactivated 6pm timeblock_: " . $six_block_id . "  on table_config record.<br><br>";
			} else {
				//echo "Failed to reactivate 6pm timeblock.";
			}
			mysqli_free_result($reactivate_result);			
						
		} else {
			//echo "Failed to find on one 6pm block time.";
		}
		
		$wasatchsql = "UPDATE Res_Table_Config SET table_min_" . $table_id . " = '" . $capacity_min . "', table_max_" . $table_id . " = '" . $capacity_max . "', table_wasatch_" . $table_id . " = '1' WHERE res_date = '" . $_SESSION['selected_date'] . "';";
		
		//echo "wasatchsql: " . $wasatchsql . "<br><br>";
		
		if (mysqli_query($conn, $wasatchsql)){
			//echo "Success. marked table_config as wasatch. <br><br>";
		} else {
			//echo "Failure. Unable to marke table_config as wasatch. <br><br>";
		}
		
	} else if ($wasatch == '0') {
		$sql_one = "SELECT block_id, block_time, original_block_time FROM Timeblocks WHERE table_id = '" . $table_id . "';";

		if ($query = mysqli_query($conn, $sql_one)) {
			$i = '0';
			while ($row = mysqli_fetch_assoc($query)) {
				$rows[] = $row;
		
				$block_id = $rows[$i]['block_id'];
				$block_time = $rows[$i]['block_time'];
				$original_block_time = $rows[$i]['original_block_time'];
		
				//echo "block_id: " . $block_id . "<br><br>";
				//echo "block_time: " . $block_time . "<br><br>";
				//echo "original_block_time: " . $block_time . "<br><br>";
		
				$updatesql = "UPDATE Timeblocks SET block_time = '" . $original_block_time . "' WHERE block_id = '" . $block_id . "';";
			
				if (mysqli_query($conn, $updatesql)) {
					//echo "Success. Changed back to original time.<br><br>";
				} else {
					//echo "Failed. Didn't change back to original time.<br><br>";
				}
		
				$i++;
		
			}
			mysqli_free_result($query);
		} else {
			//echo "Error New Res: " . mysqli_connect_error($conn);
			//$message = "Error: " . mysqli_connect_error($conn);
		}
		
		$wasatchsql = "UPDATE Res_Table_Config SET table_min_" . $table_id . " = '" . $capacity_min . "', table_max_" . $table_id . " = '" . $capacity_max . "', table_wasatch_" . $table_id . " = '0' WHERE res_date = '" . $_SESSION['selected_date'] . "';";
		
		if (mysqli_query($conn, $wasatchsql)){
			//echo "Success. Updated Res_Table_Config to uncheck wasatch.<br><br>";
		} else {
			//echo "Failure. Unable to update Res_Table_Config to uncheck wasatch.<br><br>";
		}
	}
	
	// Update table name
	
	$table_name_sql = "UPDATE Res_Tables SET table_name = '" . $table_name . "' WHERE table_id = '" . $table_id . "'";
	
	if (mysqli_query($conn, $table_name_sql)){
		//echo "Success. Updated table name in Res_Tables.<br><br>";
	} else {
		//echo "Failure. Unable to update Res_Tables table name.<br><br>";
	}
	/* (11-14-19)
	mysqli_close($conn);
	*/
	
} 

// END Change Table info 





// BEGIN Deactivate table 

if(isset($_POST['deactivatetable'])) {


	//echo '<pre>' . print_r($_POST, TRUE) . '</pre>';

	// Get POST values
	// Define variables and set to empty values
	$table_id = "";

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$table_id = validate_input($_POST["table_id"]);	
	} else {
		$message = 'Unable to collect POST values';	
		//echo $message;
	}
	
	//echo "table_id: " . $table_id . "<br><br>";


	/* (11-14-19)
	// Create connection
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	// Check connection
	if (!$conn) {
		$message = "Error connecting to DB: " . mysqli_connect_error($conn);
		die("Connection failed: " . mysqli_connect_error());	
	} 
	*/
	
	//Old code to make changes directly on Res_tables. New code updates Res_Table_Config record instead.
	//$sql = "UPDATE Res_Tables SET table_active = '0' WHERE table_id = '" . $table_id . "'; ";
	
	$sql = "UPDATE Res_Table_Config SET table_active_" . $table_id . " = '0' WHERE res_date = '" . $_SESSION['selected_date'] . "';";
	
	//echo "sql: " . $sql . "<br><br>";

	if (mysqli_query($conn, $sql)) {
		$message = "Table info updated successfully";
		//echo $message;
	} else {
		$message = "Error: " . mysqli_connect_error($conn);
		//echo $message;
	}
	
	/* (11-14-19)
	mysqli_close($conn);
	*/
	
	
	
} 

// END Deactivate table






// BEGIN Activate table 

if(isset($_POST['activate'])) {


	//echo '<pre>' . print_r($_POST, TRUE) . '</pre>';


	// Get POST values
	// Define variables and set to empty values
	$table_id = "";

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$table_id = validate_input($_POST["table_id"]);	
	} else {
		$message = 'Unable to collect POST values';	
		//echo $message;
	}
	
	//echo "table_id: " . $table_id . "<br><br>";


	/* (11-14-19)
	// Create connection
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	// Check connection
	if (!$conn) {
		$message = "Error connecting to DB: " . mysqli_connect_error($conn);
		die("Connection failed: " . mysqli_connect_error());	
	} 
	*/
	
	//Old Code. Now updating Res_Table_Config instead of Res_Tables directly.
	//$sql = "UPDATE Res_Tables SET table_active = '1' WHERE table_id = '" . $table_id . "'; ";
	
	$sql = "UPDATE Res_Table_Config SET table_active_" . $table_id . " = '1' WHERE res_date = '" . $_SESSION['selected_date'] . "';";

	//echo "sql: " . $sql . "<br><br>";

	if (mysqli_query($conn, $sql)) {
		$message = "Table info updated successfully";
		//echo $message;
	} else {
		$message = "Error activating table: " . mysqli_connect_error($conn);
		//echo $message;
	}
	/* (11-14-19)
	mysqli_close($conn);
	*/	
	
} 

// END Activate table







/* Old block code--set to only activate existing timeblocks, whereas this will activate existing timeblocks or create new ones if they don't exist.

// BEGIN block 

if(isset($_POST['addtime'])) {


	//echo '<pre>' . print_r($_POST, TRUE) . '</pre>';


	// Get POST values
	// Define variables and set to empty values
	$block_id = "";

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$block_id = validate_input($_POST["block_id"]);	
	} else {
		$message = 'Unable to collect POST values';	
		//echo $message;
	}
	
	//echo "block_id: " . $block_id . "<br><br>";


	// Create connection
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	// Check connection
	if (!$conn) {
		$message = "Error connecting to DB: " . mysqli_connect_error($conn);
		die("Connection failed: " . mysqli_connect_error());	
	} 
	
	$aasql = "UPDATE Timeblocks SET active = '1' WHERE block_id = '" . $block_id . "'; ";

	//echo "sql: " . $aasql . "<br><br>";

	if (mysqli_query($conn, $aasql)) {
		$message = "Table info updated successfully";
		//echo $message;
	} else {
		$message = "Error: " . mysqli_connect_error($conn);
		//echo $message;
	}
	mysqli_close($conn);
		
	
} 

// END block

*/



//BEGIN block (forreal)



// Clear out message variable
$message = "";

if(isset($_POST['addtime'])) {

	// Get POST values
	// Define variables and set to empty values
	$res_time_id = $table_id = "";

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$res_time_id = validate_input($_POST["res_time_id"]);
		$table_id = validate_input($_POST["table_id"]);
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
		
		//Old Code. Now, instead of updating Timeblocks directly, Res_Table_Config record is edited
		//$mmsql = "UPDATE Timeblocks SET active = '1' WHERE Timeblocks.block_id = '" . $ggblock_id . "';";
		
		$mmsql = "UPDATE Timeblock_Config tc JOIN Res_Table_Config rtc ON rtc.table_config_id = tc.table_config_id SET " . $active . " = '1' WHERE tc.block_id = '" . $ggblock_id . "' AND rtc.res_date = '" . $_SESSION['selected_date'] . "';";
		//$mmsql = "UPDATE Res_Table_Config SET timeblock_" . $ggblock_id . " = '1' WHERE res_date = '" . $_SESSION['selected_date'] . "';";
		
		//echo $mmsql;
		
		if (mysqli_query($conn, $mmsql)) {
			//echo "mmsql: TIMEBLOCK SET TO ACTIVE";
			//$message = "Reservation Updated";
			//$smarty->assign('new_res_message_formatting', 'success');
		} else {
			//echo "mmsql: TIMEBLOCK UPDATE FAILED";
			//$message = "Error. No record updated: " . mysqli_connect_error($conn);
			//$smarty->assign('new_res_message_formatting', 'fail');
		}
		
		/* (11-14-19)	
		mysqli_close($conn);
		*/
		
	} else {
		//echo "Query found NO results <br><br>";
	
		$hhsql = "SELECT Res_Tables.table_num, Timeblocks.block_name, Timeblocks.block_time, Timeblocks.block_number, Res_Times.res_time FROM Timeblocks JOIN Res_Tables ON Timeblocks.table_id = Res_Tables.table_id JOIN Res_Times ON Timeblocks.res_time_id = Res_Times.res_time_id WHERE Timeblocks.table_id = '" . $table_id . "' AND Timeblocks.res_time_id = '" . $res_time_id . "' LIMIT 1;";
	
		//echo "hhsql: " . $hhsql . "<br><br>";
		//echo "Getting this far #1.<br><br>";
	
		if ($query = mysqli_query($conn, $hhsql)) {
			
			//echo "Getting this far #2.<br><br>";
			
			if (mysqli_num_rows($query) > 0) {
				
				//echo "Found existing table.<br><br>";
				
				while ($row = mysqli_fetch_assoc($query)) {
					$table_num = $row['table_num'];
					$block_name = $row['block_name'];
					$block_time = $row['block_time'];
					$block_number = $row['block_number'];
				}
			} else {
				
				//echo "Must be a new table.<br><br>";
				
				$hhsql_no_timeblock = "SELECT (SELECT table_num FROM Res_Tables WHERE table_id = '$table_id') table_num, (SELECT res_time FROM Res_Times WHERE res_time_id = '$res_time_id') res_time FROM dual;";

				if ($query_no_timeblock = mysqli_query($conn, $hhsql_no_timeblock)) {
					
					while ($row = mysqli_fetch_assoc($query_no_timeblock)) {
									
						$res_time = strtotime($row['res_time']);
						$table_num = $row['table_num'];
						$block_name_time = date("g:ia", $res_time);
						$block_name = "Table #" . $table_num . " - " . $block_name_time;
						$block_time = date("G:i:s", $res_time);
					}
				}
			}
	
			$iisql = "INSERT INTO Timeblocks (block_name, block_time, block_number, " . $active . ", res_time_id, table_id, duplicate, original_block_time) VALUES ('" . $block_name . "', '" . $block_time . "', '" . $block_number . "', '1', '" . $res_time_id . "', '" . $table_id . "', '1', '" . $block_time . "');";
	
			//echo "iisql: " . $iisql . "<br><br>";
			
			if (mysqli_query($conn, $iisql)) {
				//echo "iisql: TIMEBLOCK CREATED";
				//$message = "Reservation Updated";
				//$smarty->assign('new_res_message_formatting', 'success');
			} else {
				//echo "iisql: TIMEBLOCK FAILED";
				//$message = "Error. No record updated: " . mysqli_connect_error($conn);
				//$smarty->assign('new_res_message_formatting', 'fail');
			}
			
			$new_timeblock_id = mysqli_insert_id($conn);
			
			//$jjsql = "ALTER TABLE Res_Table_Config block_" . $new_timeblock_id . " tinyint(1) NOT NULL DEFAULT 0;";
			//$jjsql .= "UPDATE Res_Table_Config SET timeblock_" . $new_timeblock_id . " = '1' WHERE res_date = '" . $_SESSION['selected_date'] . "';";
			
			$get_res_table_config_sql = "SELECT table_config_id FROM Res_Table_Config WHERE res_date = '" . $_SESSION['selected_date'] . "'";
			if ($get_res_table_config_query = mysqli_query($conn, $get_res_table_config_sql)) {
				while ($row = mysqli_fetch_assoc($get_res_table_config_query)) {
					$table_config_id = $row['table_config_id'];	
				}
			}
	
			$jjsql = "INSERT INTO Timeblock_Config (table_config_id, block_id, " . $active . ") VALUES ('$table_config_id', '$new_timeblock_id', '1')";
			
			//echo "jjsql: " . $jjsql . "<br><br>";
	
			if (mysqli_multi_query($conn, $jjsql)) {
				//echo "jjsql: THIS IS THE SUCCESS MESSAGE";
				//$message = "Reservation Updated";
				//$smarty->assign('new_res_message_formatting', 'success');
			} else {
				//echo "jjsql: THIS IS THE FAILURE MESSGAGE";
				//$message = "Error. No record updated: " . mysqli_connect_error($conn);
				//$smarty->assign('new_res_message_formatting', 'fail');
			}
			
			/* (11-14-19)
			mysqli_close($conn);
			*/
		}
	}
	
} else {

	$smarty->assign('new_res_message_formatting', 'none');
	
}



//END block (forreal)













































// BEGIN Deactivate Timeblock 

if(isset($_POST['deactivatetime'])) {


	//echo '<pre>' . print_r($_POST, TRUE) . '</pre>';

	// Get POST values
	// Define variables and set to empty values
	$block_id = "";

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$block_id = validate_input($_POST["block_id"]);	
	} else {
		$message = 'Unable to collect POST values';	
		//echo $message;
	}
	
	//echo "table_id: " . $table_id . "<br><br>";


	/* (11-14-19)
	// Create connection
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	// Check connection
	if (!$conn) {
		$message = "Error connecting to DB: " . mysqli_connect_error($conn);
		die("Connection failed: " . mysqli_connect_error());	
	} 
	*/
	
	//Old code. Updates are now done on Res_Table_Config instead of Res_Tables directly
	//$sql = "UPDATE Timeblocks SET active = '0' WHERE block_id = '" . $block_id . "'; ";
	

	$sql = "UPDATE Timeblock_Config tc JOIN Res_Table_Config rtc ON rtc.table_config_id = tc.table_config_id SET " . $active . " = '0' WHERE tc.block_id = '" . $block_id . "' AND rtc.res_date = '" . $_SESSION['selected_date'] . "';";
	//$sql = "UPDATE Res_Table_Config SET timeblock_" . $block_id . " = '0' WHERE res_date = '" . $_SESSION['selected_date'] . "';";

	//echo "sql: " . $sql . "<br><br>";

	if (mysqli_query($conn, $sql)) {
		$message = "Table info updated successfully";
		//echo $message;
	} else {
		$message = "Error: " . mysqli_connect_error($conn);
		//echo $message;
	}
	/* (11-14-19)
	mysqli_close($conn);
	*/
	
	
} 

// END Deactivate Timeblock




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
	$res_id = "";

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$res_id = validate_input($_POST["cancel_res_id"]);	
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
	
	$ssql = "SELECT Timeblocks.duplicate FROM Timeblocks JOIN Reservations ON Reservations.block_id = Timeblocks.block_id WHERE Reservations.res_id = '" . $res_id . "'; AND Timeblocks.duplicate = '1'";
		
	//echo "CANCEL UPDATE#1. ssql: " . $ssql . "<br><br>";
		
	$ssresult = mysqli_query($conn, $ssql);
	if (mysqli_num_rows($ssresult) > 0) {
		$sssql = "UPDATE Timeblocks JOIN Reservations ON Reservations.block_id = Timeblocks.block_id SET Timeblocks." . $active . " = '0' WHERE Reservations.res_id = '" . $res_id . "';";
		
		//echo "CANCEL UPDATE #2 Successful? sssql: " . $sssql . "<br><br>";
		
		if (mysqli_query($conn, $sssql)) {
			//echo "CANCEL UPDATE #2 Successful? sssql: " . $sssql . "<br><br>";
		}
	}
	//$sql = "UPDATE Reservations SET res_status = '2' WHERE res_id = " . $res_id . ";";
	
	
	$sql = "DELETE FROM Reservations WHERE res_id = '" . $res_id . "';";

	if (mysqli_query($conn, $sql)) {
		$message = "Reservation updated successfully";
		//echo $message;
	} else {
		$message = "Error: " . mysqli_connect_error($conn);
		//echo $message;
	}
	/* (11-14-19)
	mysqli_close($conn);
	*/
	
} 

// END Cancel Update









// BEGIN table grid layout

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

	//echo "testsql: " . $createsql . "<br><br>";
	
	if (mysqli_query($conn, $createsql)) {
		
		//echo "Record created.<br><br>";
		
		$update22sql = "SELECT * FROM Res_Table_Config WHERE res_date = '" . $_SESSION['selected_date'] . "';";

		//echo "2nd version updatesql: " . $update22sql . "<br><br>";

		if ($query = mysqli_query($conn, $update22sql)) {
			while ($row = mysqli_fetch_assoc($query)) {
				$updaterows[] = $row;
			}
			//echo '<pre>' . print_r($updaterows, TRUE) . '</pre>';
			mysqli_free_result($query);
		} else {
		$message = "Error: " . mysqli_error($conn);
		//echo $message;
		}
	}
}


foreach ($row_number as $z) {

	$zsql{$z} = "SELECT * FROM Res_Tables WHERE table_row = '" . $z . "' ORDER BY table_num ASC;";
	
	
	if ($zresult = mysqli_query($conn, $zsql{$z})) {
		
		$z2 = 1;
		
		while ($zrow = mysqli_fetch_row($zresult)) {
			
			//echo $zrow[0];
			$current_table_id = $zrow[0];
			
			$updatesql = "UPDATE Res_Tables SET capacity_min = '" . $updaterows[0]['table_min_' . $current_table_id . ''] . "', capacity_max = '" . $updaterows[0]['table_max_' . $current_table_id . ''] . "', wasatch = '" . $updaterows[0]['table_wasatch_' . $current_table_id . ''] . "', table_active = '" . $updaterows[0]['table_active_' . $current_table_id . ''] . "' WHERE table_id = '" . $current_table_id . "';";
			
			//echo "updatesql: " . $updatesql . "<br><br>";
			
			if (mysqli_query($conn, $updatesql)) {
				$message = "Tables table updated successfully.<br><br>";
				//echo $message;
			} else {
				$message = "Error updating Tables table: " . mysqli_error($conn);
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
		//$update3sql = "UPDATE Timeblocks SET active = '" . $update_timeblock_rows[$current_timeblock] . "' WHERE block_id = '" . $current_timeblock . "';";
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
}

mysqli_free_result($z3result);

/* (11-14-19)
mysqli_close($conn);
*/



foreach ($row_number as $i) {

	/* (11-14-19)
	// Create connection
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	// Check connection
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());	
	}
	*/

	$asql{$i} = "SELECT * FROM Res_Tables WHERE table_active = '1' AND table_row = '" . $i . "' ORDER BY table_num ASC;";
	
	//echo "asql: " . $asql{$i} . "<br><br>";
	
	if ($aresult = mysqli_query($conn, $asql{$i})) {
		
		$x = 1;
		
		while ($arow = mysqli_fetch_row($aresult)) {
			
			//echo "x is now: " . $x . "<br><br>";
			
			$atable{$i} = array();
			//$atable{$i} = $arow;
			$atable{$i}[$x] = $arow;
			//$atable{$i}[] = $arow;  
			$atables2{$i}[] = $x;
			
			
			$bsql{$i} = "SELECT Timeblocks.block_id, Timeblocks.block_time, Reservations.party_name, Reservations.room_num, Reservations.party_num, Reservations.special_requests, Reservations.notes, Reservations.res_id, Reservations.res_status, Timeblocks.original_block_time FROM Timeblocks LEFT JOIN Reservations ON Reservations.block_id = Timeblocks.block_id AND Reservations.res_date = '" . $_SESSION['selected_date'] . "' WHERE Timeblocks." . $active . " = '1' AND Timeblocks.table_id = '" . $atable{$i}[$x][0] . "' ORDER BY Timeblocks.original_block_time ASC;";
			
			//echo "bsql: " . $bsql{$i} . "<br><br>";
			
			if ($bresult = mysqli_query($conn, $bsql{$i})) {
				
				$y = 1;
				
				while ($brow = mysqli_fetch_row($bresult)) {
					
					$btimeblock{$i} = array();
					$btimeblock{$i}[$y] = $brow;
					$btimeblock2{$x}[] = $y;
					$btimeblock3{$i}{$x}[] = $y;
					
					
					//echo '<pre>' . print_r($brow, TRUE) . '</pre>';
					//echo $i . "_" . $y . "_TableTimeblock<br><br>";
					//echo $i . "_" . $x . "_" . $y . "_TableTimeblock<br><br>";
					//echo $btimeblock{$i}{$x}[$y];					

					$smarty->assign($i . "_" . $x . "_" . $y . '_TableTimeblock', $brow);
						
					//echo "block_id?" . $brow[0] . "<br><br>";
					//echo "block_time?" . $brow[1] . "<br><br>";
					//echo "original_block_time?" . $brow[9] . "<br><br>";
					
					//BEGIN Table View: Show list of available Reservations for a Timeblock

					/* (11-14-19)
					// Create connection
					$conn = mysqli_connect($servername, $username, $password, $dbname);
					// Check connection
					if (!$conn) {
						die("Connection failed: " . mysqli_connect_error());	
					}
					*/

					$afsql = "SELECT Reservations.res_id, Reservations.party_name, Reservations.party_num, Reservations.block_id FROM Reservations WHERE res_time = '" . $brow[1] . "' AND block_id = '131' AND res_date = '" . $_SESSION['selected_date'] . "' AND Reservations.res_status != '2';";
					
					//echo $afsql . "<br><br>";
					
					$afrows = array();

					if ($afquery = mysqli_query($conn, $afsql)) {
						while ($afrow = mysqli_fetch_assoc($afquery)) {
							$afrows[] = $afrow;
						}
						
						//echo $i . "_" . $x . "_" . $y . "_Availres<br><br>";
						//echo '<pre>' . print_r($afrows, TRUE) . '</pre>';
						//echo $afrows['party_name'] . "<br><br>";
						
						$smarty->assign($i . "_" . $x . "_" . $y . '_Availres', $afrows);	
						mysqli_free_result($afquery);
					} else {
						//echo "Error: " . mysqli_connect_error($conn);
						//$message = "Error: " . mysqli_connect_error($conn);
					}

					//mysqli_close($conn);

					//END Table View: Show list of available Reservations for a Timeblock
					
					
					
					
					
					
					
					
					
					
					
					
					$y++;	
				}	
				
				//echo '<pre>' . print_r($btimeblock3{$i}{$x}, TRUE) . '</pre>';
				//echo $i . "_" . $x . "_ColumnTimeblocks<br><br>";
				$smarty->assign($i . "_" . $x . '_ColumnTimeblocks', $btimeblock3{$i}{$x});
				
			}
			
			
			//echo '<pre>' . print_r($arow, TRUE) . '</pre>';
			//echo "I is now: " . $i . "<br><br>";
			//echo '<pre>' . print_r($atable{$i}, TRUE) . '</pre>';
			//echo $atable{$i}[$x][1] . "_" . $atable{$i}[$x][2] .  "_TableInfo(1)<br><br>";
			//echo $i . "_" . $x .  "_TableInfo(2)<br><br>";
			//echo "TThis should be 2-digit table number: " . $atable{$i}[$x][3] . "<br><br>";
			
			//$smarty->assign($atable{$i}[$x][1] . "_" . $atable{$i}[$x][2] .  "_TableInfo", $arow);
			$smarty->assign($i . "_" . $x .  "_TableInfo", $arow);	
			mysqli_free_result($bresult);
			
			
			
			
			// BEGIN Show 'Timeblocks available to add' dropdown

			/* (11-14-19)
			// Create connection
			$conn = mysqli_connect($servername, $username, $password, $dbname);
			// Check connection
			if (!$conn) {
				die("Connection failed: " . mysqli_connect_error());	
			}
			*/

			$acsql{$i}{$x} = "SELECT block_time, block_id FROM Timeblocks WHERE table_id = '" . $atable{$i}[$x][0] . "' AND " . $active . " = '0' ORDER BY block_time ASC;";
			
			//echo $acsql{$i}{$x} . "<br><br>";

			//echo "absql" . $absql{$i} . "<br><br>";


			if ($acquery = mysqli_query($conn, $acsql{$i}{$x})) {
				while ($row = mysqli_fetch_assoc($acquery)) {
						$acrows{$i}{$x}[] = $row;
				}
				$smarty->assign($i . "_" . $x . '_Addtimeblock', $acrows{$i}{$x});	
				
				//echo "addtimeblock: " . $i . "_" . $x . "_Addtimeblock<br><br>"; 
				
				mysqli_free_result($acquery);
			} else {
				//echo "Error: " . mysqli_connect_error($conn);
				//$message = "Error: " . mysqli_connect_error($conn);
			}

			//mysqli_close($conn);

			// END Show 'Timeblocks available to add' dropdown	
			
			
			$x++;
		}
		
		//echo '<pre>' . print_r($atables2{$i}, TRUE) . '</pre>';
		$smarty->assign($i . '_RowColumns', $atables2{$i});
		
		mysqli_free_result($aresult);	
	}
	
	
	
	
	
// BEGIN Show 'Tables available to activate' dropdown

/* (11-14-19)
// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());	
}
*/

$absql{$i} = "SELECT Res_Tables.table_id, Res_Tables.table_num, Res_Tables.capacity_min, Res_Tables.capacity_max FROM Res_Tables WHERE Res_Tables.table_row = '" . $i . "' AND table_active = '0';";


//echo "absql" . $absql{$i} . "<br><br>";


if ($query = mysqli_query($conn, $absql{$i})) {
	while ($row = mysqli_fetch_assoc($query)) {
			$abrows{$i}[] = $row;
	}
	$smarty->assign($i . '_Activate', $abrows{$i});	
	mysqli_free_result($query);
} else {
	//echo "Error: " . mysqli_connect_error($conn);
	//$message = "Error: " . mysqli_connect_error($conn);
}

/* (11-14-19)
mysqli_close($conn);
*/

// END Show 'Tables available to activate' dropdown	
	
	
	
	
	
}

// END table grid layout






// BEGIN Show 'Res_Times' dropdown

/* (11-14-19)
// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());	
}
*/

// Below is previous code excluding 18:01 time, but it doesn't appear to be needed right now...
//$zzzsql = "SELECT res_time, res_time_id FROM Res_Times WHERE res_time > '00:00:00' AND <> '18:01:00' ORDER BY res_time ASC;";
$zzzsql = "SELECT res_time, res_time_id FROM Res_Times WHERE res_time > '00:00:00' AND " . $enabled_times . " = '1' ORDER BY res_time ASC;";

//echo "zzzsql: " . $zzzsql . "<br><br>";
		
if ($zzzquery = mysqli_query($conn, $zzzsql)) {
	while ($row = mysqli_fetch_assoc($zzzquery)) {
			$zzzrows[] = $row;
			//echo "TEST-res_time: " . $zzzrows['res_time'] . "<br><br>"; 
	}
	$smarty->assign('ResTimes', $zzzrows);	
			
	//echo "WHAT IS GOING ON: " . $zzzrows['res_time'] . "<br><br>";
	//echo '<pre>' . print_r($zzzrows, TRUE) . "</pre>";
	//echo "<br><br>";	
				
	mysqli_free_result($zzzquery);
} else {
	//echo "Error: " . mysqli_connect_error($conn);
	$message = "Error: " . mysqli_connect_error($conn);
}

/* (11-14-19)
mysqli_close($conn);
*/

// END Show 'Res_Times' dropdown


// BEGIN Show 'Available Timeblocks' dropdown

/* (11-14-19)
// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());	
}
*/

$adsql = "SELECT Timeblocks.block_id, Timeblocks.block_time, Res_Tables.table_num FROM Timeblocks JOIN Res_Tables ON Timeblocks.table_id = Res_Tables.table_id WHERE NOT EXISTS (SELECT NULL FROM Reservations WHERE Timeblocks.block_id = Reservations.block_id AND Reservations.res_date = '" . $_SESSION['selected_date'] . "') AND Timeblocks." . $active . " = '1' AND Res_Tables.table_active = '1';";


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

// END Show 'Available Timeblocks' dropdown


// Close connection to Auth database
disconnect_auth();

// Close connection to Res database
disconnect_res();

$smarty->display('res_tables.tpl');

?>
