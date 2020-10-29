<?php

// Turn error reporting on/off
error_reporting(E_ALL);
ini_set('display_errors',1);

include 'DB/dbaccess.php';


$dsn = 'RDPCON1';
$username = '';
$password = '';


$rdpconn = odbc_connect('RDPCON1','','');





// Grab HReserveComment data



$rdpCommentSql = "select * from \"HReserveComment\" ORDER BY ResNum ASC;";

$rdpCommentQuery = odbc_exec($rdpconn,$rdpCommentSql);

if ($rdpCommentQuery = odbc_exec($rdpconn,$rdpCommentSql)) {
	
	$rdprows = array();
	
	while ($rdprow = odbc_fetch_array($rdpCommentQuery)) {

		$rdpCommentRows[] = $rdprow;

		//$guestname = odbc_result($rdpquery,"GuestName");
		//echo $guestname . "\r\n";
		
		//file_put_contents("/testing/odbctest-01.txt", date('m/d/y - H:i') . $guestname . "\r\n", FILE_APPEND);
	}
	
	//$rdprows2 = odbc_fetch_array($rdpquery);
	
	//echo "This is the RDP info: \r\n";
	//echo $rdprows[2]['ResNum'] . "\r\n";
	//echo '<pre>' . print_r($rdprows[2], TRUE) . '</pre>';
	//echo "This is the SECOND RDP info: \r\n";
	//echo print_r($rdprows, TRUE);
	
	odbc_free_result($rdpCommentQuery);
} else {
	$message = "Error with RDP connection ";
}


odbc_close($rdpconn);	



// Clear out RDP Comment table

$sqldelete = '';

$conn = mysqli_connect('localhost', 'portaladmin', 'zipp0man', 'portal_res');

$sqldelete = "DELETE From rustler_res.Res_RDP_Comment;";

if ($queryone = mysqli_query($conn, $sqldelete)) {
		$message = "Table Cleared";
		echo $message;
	} else {
		$message = "Error. Table not cleared: " . mysqli_connect_error($conn);
		echo $message;
	}
	mysqli_free_result($queryone);

// Add RDP Comment rows into MariaDB

$sql = '';

foreach($rdpCommentRows as $value){
	$sql = "INSERT INTO portal_res.Res_RDP_Comment (";
	$value2 = array_keys($value);
	$db_columns = '';
		
	foreach ($value2 as $value3) {
		$db_columns .= $value3 . ", ";
	}
	$db_columns = rtrim($db_columns, ', ');
	$sql .= $db_columns . ") VALUES (";
	//echo $db_columns . "\r\n";
	$db_values = '';
		
	foreach ($value as $value2) {
		//echo '<pre>' . print_r($value2, TRUE) . '</pre>';
		//echo "\r\n";
		//echo $value2 . "\r\n";
		//echo array_keys($value2) . "\r\n";
		$db_values .= "'" . $value2 . "', "; 
	}
	$db_values = rtrim($db_values, ', ');
	$sql .= $db_values . ");";
		
	echo $sql . "\r\n";
		
	if ($query = mysqli_query($conn, $sql)) {
		$message = "New reservation comment record created";
		echo $message;
	} else {
		$message = "Error. No comment record created: " . mysqli_connect_error($conn);
		echo $message;
	}
	mysqli_free_result($query);
}		


mysqli_close($conn);


//echo $message;

//$username = posix_getpwuid(posix_geteuid())['name'];
//echo $username;

?>
