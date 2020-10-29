
<?php

	// Validate data
	function validate_input($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>New Reservation</title>
<style>

DIV.table { display:table; }
FORM.tr, DIV.tr { display:table-row; }
SPAN.td { display:table-cell; width:200px; }

</style>
</head>
<body>

<?php

include 'DB/dbaccess.php';

date_default_timezone_set('America/Denver');

// TESTING

//print_r($_POST);

// END TESTING



if(isset($_POST['submit'])) {

	// Get POST values

	// Define variables and set to empty values
	$first_name = $last_name = $phone = $party_num = $party_adults = $party_children = $res_date = $block_id = $special_requests = $notes = "";

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$first_name = validate_input($_POST["first_name"]);
		$last_name = validate_input($_POST["last_name"]);
		$phone = validate_input($_POST["phone"]);
		$party_num = validate_input($_POST["party_num"]);
		$party_adults = validate_input($_POST["party_adults"]);
		$party_children = validate_input($_POST["party_children"]);
		$res_date = validate_input($_POST["res_date"]);
		$block_id = validate_input($_POST["block_id"]);
		$special_requests = validate_input($_POST["special_requests"]);
		$notes = validate_input($_POST["notes"]);		
	} else {
		echo '<div><p style="text-align:center; color:blue; font-weight:bold">Error message 2</p></div>';	
	}

	// Create connection
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	// Check connection
	if (!$conn) {
		$message = "Error: " . mysqli_connect_error($conn);
		die("Connection failed: " . mysqli_connect_error());	
	} 

	$sql = "INSERT INTO Reservations (first_name, last_name, phone, party_num, party_adults, party_children, res_date, block_id, special_requests, notes)";
	$sql .= " VALUES ('$first_name', '$last_name', '$phone', '$party_num', '$party_adults', '$party_children', '$res_date', '$block_id',";
	$sql .= " '$special_requests', '$notes')";

	if (mysqli_query($conn, $sql)) {
		$message = "New record created successfully";
	} else {
		$message = "Error: " . mysqli_connect_error($conn);
	}
	mysqli_close($conn);
	
	echo '<div><p style="text-align:center; color:blue; font-weight:bold">' . $message . '</p></div>';
} else {

	//echo '<div><p style="text-align:center; color:blue; font-weight:bold">POST didnt work</p></div>';
	
}

//echo '<div><p style="text-align:center; color:blue; font-weight:bold">Im not sure this works' . $message . '</p></div>';



$current_date = date('Y-m-d');
$date_plus2 = date('Y-m-d', strtotime('+2 years', strtotime(date('Y'))));
$current_time = date('H:i');

?>


<div style="padding:50px;">

<div class="table" style="width:40%; padding-right:20px; float:left;">
<div class="tr"><span class="td"><h3>New Reservation</h3></span></div>
<br />
	<form name="new_reservation" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
		<div class="tr"><span class="td">First Name: </span><span class="td"><input type="text" name="first_name" required></span></div>
		<div class="tr"><span class="td">Last Name: </span><span class="td"><input type="text" name="last_name" required></span></div>
		<div class="tr"><span class="td">Phone: </span><span class="td"><input type="text" name="phone"></span></div>
		<div class="tr"><span class="td">Number of people in party: </span><span class="td"><input type="number" name="party_num" min="1" required></span></div>
		<div class="tr"><span class="td">Number of Adults (16+): </span><span class="td"><input type="number" name="party_adults" min="1"></span></div>
		<div class="tr"><span class="td">Number of Children (0-16): </span><span class="td"><input type="number" name="party_children"></span></div>
		<div class="tr"><span class="td">Reservation Date: </span><span class="td"><input type="date" name="res_date" value="<?php echo $current_date; ?>" min="<?php echo $current_date; ?>" max="<?php echo $date_plus2; ?>" readonly></span></div>
		<div class="tr"><span class="td">Reservation Time: </span><span class="td"><select name="block_id" required>
        	<option hidden disabled selected value> -- select a time block -- </option>

<?php
// Show available blocks in dropdown

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());	
}

$asql = "SELECT Timeblocks.block_id, Timeblocks.block_name FROM Timeblocks WHERE NOT EXISTS (SELECT NULL FROM Reservations WHERE Timeblocks.block_id = Reservations.block_id AND Reservations.res_date = curdate()) AND Timeblocks.active = '1'";
if ($result = mysqli_query($conn, $asql)) {
	while ($row = mysqli_fetch_row($result)) {
		echo '<option value="' . $row[0] . '">' . $row[1] . '</option>';
	}
	mysqli_free_result($result);
} else {
	echo "Error: " . mysqli_connect_error($conn);
	//$message = "Error: " . mysqli_connect_error($conn);
}

mysqli_close($conn);
?>

		</select></span></div>
		<div class="tr"><span class="td">Special Requests: </span><span class="td"><input type="text" name="special_requests"></span></div>
		<div class="tr"><span class="td">Notes: </span><span class="td"><input type="notes" name="notes"></span></div>
<br>
<input name="submit" type="submit">
</form>

</div>

<div style="width:40%; padding-left:20px; float:right;">
<h3>Today's Reservations (<?php echo date('m/d/y'); ?>)</h3>
<br>
<table style="width:100%;">
<?php

// Show list of current reservations

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());	
}

//$sql = "SELECT block_id, res_date, res_time, first_name, last_name, party_num, special_requests FROM Reservations WHERE res_date >= curdate() ORDER BY res_date ASC, block_id ASC";
$bsql = "SELECT Timeblocks.block_name, Reservations.res_date, Reservations.first_name, Reservations.last_name, Reservations.party_num, Reservations.special_requests, Reservations.block_id FROM Reservations JOIN Timeblocks ON Reservations.block_id = Timeblocks.block_id AND res_date = curdate() GROUP BY block_id ASC";
if ($result = mysqli_query($conn, $bsql)) {
	while ($row = mysqli_fetch_row($result)) {
		echo '<tr><td>' . $row[0] . '</td><td>' . $row[2] . '</td><td>' . $row[3] . '</td><td>' . $row[4] . '</td><td>' . $row[5] . '</td></tr>';
	}
	mysqli_free_result($result);
} else {
	echo "Error: " . mysqli_connect_error($conn);
	//$message = "Error: " . mysqli_connect_error($conn);
}

mysqli_close($conn);

?>
</table>
<br>
<br>
<h3>Future Reservations</h3>
<br>
<table style="width:100%;">
<?php

// Show list of current reservations

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());	
}

$csql = "SELECT Timeblocks.block_name, Reservations.res_date, Reservations.first_name, Reservations.last_name, Reservations.party_num, Reservations.special_requests, Reservations.block_id FROM Reservations JOIN Timeblocks ON Reservations.block_id = Timeblocks.block_id AND res_date > curdate()";
if ($result = mysqli_query($conn, $csql)) {
	while ($row = mysqli_fetch_row($result)) {
		echo '<tr><td>' . date("m/d/y", strtotime("$row[1]")) . '</td><td>' . $row[0] . '</td><td>' . $row[2] . '</td><td>' . $row[3] . '</td><td>' . $row[4] . '</td><td>' . $row[5] . '</td></tr>';
	}
	mysqli_free_result($result);
} else {
	echo "Error: " . mysqli_connect_error($conn);
	//$message = "Error: " . mysqli_connect_error($conn);
}

mysqli_close($conn);

?>
</table>
</div>

</div>













<div style="width:100%; margin-top:800px;">

<?php

// TESTING

// Show available blocks in Reservation Time dropdown

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());	
}

$bsql = "SELECT Timeblocks.block_id, Timeblocks.block_name FROM Timeblocks WHERE NOT EXISTS (SELECT NULL FROM Reservations WHERE Timeblocks.block_id = Reservations.block_id AND Reservations.res_date = curdate()) AND Timeblocks.active = '1'";
//$query = mysqli_query($conn, $bsql);
$result = array();
if ($query = mysqli_query($conn, $bsql)) {

	while ($row = mysqli_fetch_assoc($query)) {
			$result[] = $row;
	}
	
	print_r($result);
	echo "<br>";
	echo $result[0]['block_id'].": In stock: ".$result[0]['block_name'].".<br>";
	echo $result[1]['block_id'].": In stock: ".$result[1]['block_name'].".<br>";
	echo $result[2]['block_id'].": In stock: ".$result[2]['block_name'].".<br>";
	echo $result[3]['block_id'].": In stock: ".$result[3]['block_name'].".<br>";
	
	mysqli_free_result($result);

}

mysqli_close($conn);




// END TESTING

?>

</div>

<div style="width:100%; margin-top:800px;">

<?php


// Show list of Today's reservations

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());	
}

$csql = "SELECT Timeblocks.block_name, Reservations.res_date, Reservations.first_name, Reservations.last_name, Reservations.party_num, Reservations.special_requests, Reservations.block_id FROM Reservations JOIN Timeblocks ON Reservations.block_id = Timeblocks.block_id AND res_date = curdate() GROUP BY block_id ASC";
$rows2 = array();
if ($query = mysqli_query($conn, $csql)) {
	while ($row = mysqli_fetch_row($query)) {
		$rows2[] = $row;
		
		//echo '<tr><td>' . $row[0] . '</td><td>' . $row[2] . '</td><td>' . $row[3] . '</td><td>' . $row[4] . '</td><td>' . $row[5] . '</td></tr>';
	}
	
	print_r($rows2);
	echo "<br>";
	echo $rows2[0][0].": In stock: ".$rows2[0][1].".<br>";
	echo $rows2[1][0].": In stock: ".$rows2[1][1].".<br>";
	
	mysqli_free_result($query);
} else {
	echo "Error: " . mysqli_connect_error($conn);
	//$message = "Error: " . mysqli_connect_error($conn);
}

mysqli_close($conn);



?>

</div>












</body>
</html>
