<?php

// Turn error reporting on/off
//error_reporting(E_ALL);
//ini_set('display_errors',1);

include 'DB/dbaccess.php';


$dsn = 'RDPCON1';
$username = '';
$password = '';


$rdpconn = odbc_connect('RDPCON1','','');


$rdpsql = "select * from \"HReserve\" ORDER BY ResNum ASC;";



//$rdpsql = "select * from \"BookHist\" where ArrivalDate >= curdate() ORDER BY ResNum ASC;";
//$rdpsql = "select * from \"BookHist\" where ArrivalDate <= '2019-04-2' AND DepartureDate >= '2019-04-02' ORDER BY ResNum ASC;";
//$rdpsql = "select * from \"BookHist\" where ArrivalDate >= '2019-01-1' AND ArrivalDate <= '2019-03-1' ORDER BY ResNum ASC;";
//$rdpsql = "select * from \"BookHist\" where ArrivalDate <= '2019-02-23' AND DepartureDate >= '2019-02-23' ORDER BY ResNum ASC;";

//file_put_contents("/testing/odbctest-02.txt", date('m/d/y - H:i') . "QUERY: " . $rdpsql . "\r\n", FILE_APPEND);

$rdpquery = odbc_exec($rdpconn,$rdpsql);



/*
$conn = mysqli_connect('localhost', 'rustlerresadmin', 'zipp0man', 'rustler_res');

if (!$conn) {
	$message = "Error connecting to DB: " . mysqli_connect_error($conn);
	die("Connection failed: " . mysqli_connect_error());	
} 

$sql = "SELECT * FROM Res_RDP ORDER BY ResNum ASC";

if ($query = mysqli_query($conn, $sql)) {
	while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
		$rows[] = $row;
	}
	
	echo "This is the MariaDB info: \r\n";
	echo '<pre>' . print_r($rows, TRUE) . '</pre>';
	
	mysqli_free_result($query);
} else {
	$message = "Error: " . mysqli_connect_error($conn);
}
mysqli_close($conn);
*/

if ($rdpquery = odbc_exec($rdpconn,$rdpsql)) {
	
	$rdprows = array();
	
	while ($rdprow = odbc_fetch_array($rdpquery)) {

		$rdprows[] = $rdprow;

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
	
	odbc_free_result($rdpquery);
} else {
	$message = "Error with RDP connection ";
	echo $message;
}


//echo "This is a test message to make sure that it's working";

//print_r($rdprows);


// Grab HReserveComment data


/*

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

*/



// Grab NoteFile data

/*

$rdpNoteSql = "select * from \"NoteFile\" ORDER BY Number ASC;";

$rdpNoteQuery = odbc_exec($rdpconn,$rdpNoteSql);

if ($rdpNoteQuery = odbc_exec($rdpconn,$rdpNoteSql)) {
	
	$rdprows = array();
	
	while ($rdprow = odbc_fetch_array($rdpNoteQuery)) {

		$rdpNoteRows[] = $rdprow;

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
	
	odbc_free_result($rdpNoteQuery);
} else {
	$message = "Error with RDP connection ";
}

*/

odbc_close($rdpconn);	




// Clear out RDP table

$sqldelete = '';

$conn = mysqli_connect('localhost', 'portaladmin', 'zipp0man', 'portal_res');

$sqldelete = "DELETE From portal_res.Res_RDP;";

if ($queryone = mysqli_query($conn, $sqldelete)) {
		$message = "Table Cleared.<br><br>";
		echo $message;
	} else {
		$message = "Error. Table not cleared: " . mysqli_connect_error($conn) . "<br><br>";
		echo $message;
	}
	mysqli_free_result($queryone);

// Add RDP rows into MariaDB

$sql = '';

foreach($rdprows as $value){
	$sql = "INSERT INTO portal_res.Res_RDP (";
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
		
		if (strstr($value2, "'")) {
			//echo "before string replacement: " . $value2;
			$value2 = str_replace("'","''",$value2);
			//echo "A string was replaced.<br><br>";
			//echo "after string replacement: " . $value2;
		}
		
		$db_values .= "'" . $value2 . "', "; 
	}
	$db_values = rtrim($db_values, ', ');
	$sql .= $db_values . ");";
		
	//echo $sql . "\r\n";
		
	if ($query = mysqli_query($conn, $sql)) {
		$message = "New RDP record created";
		//echo $message;
	} else {
		$message = "Error. No RDP record created: " . mysqli_connect_error($conn) . "<br><br>";
		echo $sql . "\r\n";
		echo $message;
	}
	mysqli_free_result($query);
}





/*

// Clear out RDP Comment table

$sqldelete = '';

$conn = mysqli_connect('localhost', 'rustlerresadmin', 'zipp0man', 'rustler_res');

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
	$sql = "INSERT INTO rustler_res.Res_RDP_Comment (";
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

*/


mysqli_close($conn);


//echo $message;

//$username = posix_getpwuid(posix_geteuid())['name'];
//echo $username;

?>
