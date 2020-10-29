
<?php

include 'DB/dbaccess.php';

date_default_timezone_set('America/Denver');

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>

<style>

DIV.table { display:table; }
DIV.tr { display:table-row; }
DIV.titlerow { margin-bottom:10px; }
SPAN.td { display:table-cell; padding-right:20px; }
SPAN.titletd { font-size:20px; font-weight:900; padding-bottom:10px; }
DIV.timeblock { margin-bottom:20px; }
DIV.column-4 { width:30%; padding:10px; float:left; position:relative; }

</style>

</head>

<body>

<div class="column-4">
<div class="timeblock">
<div class="tr titlerow"><span class="td titletd">5:30pm</span><span class="td titletd">
<?php 

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());	
}

$sql = "SELECT Timeblocks.block_name, Reservations.res_date, Reservations.first_name, Reservations.last_name, Reservations.party_num, Reservations.special_requests, Reservations.block_id, Timeblocks.block_time FROM Reservations JOIN Timeblocks ON Reservations.block_id = Timeblocks.block_id AND res_date = curdate() AND Timeblocks.block_time = '17:30:00' GROUP BY block_id ASC";
if ($result = mysqli_query($conn, $sql)) {
	$headcount = "";
	while ($row = mysqli_fetch_row($result)) {
		$headcount += $row[4]; 
	}
	mysqli_free_result($result);
} else {
	echo "Error: " . mysqli_connect_error($conn);
	//$message = "Error: " . mysqli_connect_error($conn);
}

mysqli_close($conn);

echo $headcount; 

?>
</span>
</div>

<?php

// 5:30pm - Show list of current reservations

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());	
}

$sql = "SELECT Timeblocks.block_name, Reservations.res_date, Reservations.first_name, Reservations.last_name, Reservations.party_num, Reservations.special_requests, Reservations.block_id, Timeblocks.block_time FROM Reservations JOIN Timeblocks ON Reservations.block_id = Timeblocks.block_id AND res_date = curdate() AND Timeblocks.block_time = '17:30:00' GROUP BY block_id ASC";
if ($result = mysqli_query($conn, $sql)) {
	$headcount = "";
	while ($row = mysqli_fetch_row($result)) {
		echo '<div class="tr"><span class="td">' . $row[2] . ' ' . $row[3] . '</span><span class="td">' . $row[4] . '</span></div>';
		$headcount += $row[4]; 
	}
	mysqli_free_result($result);
} else {
	echo "Error: " . mysqli_connect_error($conn);
	//$message = "Error: " . mysqli_connect_error($conn);
}

mysqli_close($conn);

?>
</div>






<div class="timeblock">
<div class="tr titlerow"><span class="td titletd">5:45pm</span><span class="td titletd">
<?php 

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());	
}

$sql = "SELECT Timeblocks.block_name, Reservations.res_date, Reservations.first_name, Reservations.last_name, Reservations.party_num, Reservations.special_requests, Reservations.block_id, Timeblocks.block_time FROM Reservations JOIN Timeblocks ON Reservations.block_id = Timeblocks.block_id AND res_date = curdate() AND Timeblocks.block_time = '17:45:00' GROUP BY block_id ASC";
if ($result = mysqli_query($conn, $sql)) {
	$headcount = "";
	while ($row = mysqli_fetch_row($result)) {
		$headcount += $row[4]; 
	}
	mysqli_free_result($result);
} else {
	echo "Error: " . mysqli_connect_error($conn);
	//$message = "Error: " . mysqli_connect_error($conn);
}

mysqli_close($conn);

echo $headcount; 

?>
</span>
</div>

<?php

// 5:30pm - Show list of current reservations

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());	
}

$sql = "SELECT Timeblocks.block_name, Reservations.res_date, Reservations.first_name, Reservations.last_name, Reservations.party_num, Reservations.special_requests, Reservations.block_id, Timeblocks.block_time FROM Reservations JOIN Timeblocks ON Reservations.block_id = Timeblocks.block_id AND res_date = curdate() AND Timeblocks.block_time = '17:45:00' GROUP BY block_id ASC";
if ($result = mysqli_query($conn, $sql)) {
	$headcount = "";
	while ($row = mysqli_fetch_row($result)) {
		echo '<div class="tr"><span class="td">' . $row[2] . ' ' . $row[3] . '</span><span class="td">' . $row[4] . '</span></div>';
		$headcount += $row[4]; 
	}
	mysqli_free_result($result);
} else {
	echo "Error: " . mysqli_connect_error($conn);
	//$message = "Error: " . mysqli_connect_error($conn);
}

mysqli_close($conn);

?>
</div>







<div class="timeblock">
<div class="tr titlerow"><span class="td titletd">6:00pm</span><span class="td titletd">
<?php 

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());	
}

$sql = "SELECT Timeblocks.block_name, Reservations.res_date, Reservations.first_name, Reservations.last_name, Reservations.party_num, Reservations.special_requests, Reservations.block_id, Timeblocks.block_time FROM Reservations JOIN Timeblocks ON Reservations.block_id = Timeblocks.block_id AND res_date = curdate() AND Timeblocks.block_time = '18:00:00' GROUP BY block_id ASC";
if ($result = mysqli_query($conn, $sql)) {
	$headcount = "";
	while ($row = mysqli_fetch_row($result)) {
		$headcount += $row[4]; 
	}
	mysqli_free_result($result);
} else {
	echo "Error: " . mysqli_connect_error($conn);
	//$message = "Error: " . mysqli_connect_error($conn);
}

mysqli_close($conn);

echo $headcount; 

?>
</span>
</div>

<?php

// 5:30pm - Show list of current reservations

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());	
}

$sql = "SELECT Timeblocks.block_name, Reservations.res_date, Reservations.first_name, Reservations.last_name, Reservations.party_num, Reservations.special_requests, Reservations.block_id, Timeblocks.block_time FROM Reservations JOIN Timeblocks ON Reservations.block_id = Timeblocks.block_id AND res_date = curdate() AND Timeblocks.block_time = '18:00:00' GROUP BY block_id ASC";
if ($result = mysqli_query($conn, $sql)) {
	$headcount = "";
	while ($row = mysqli_fetch_row($result)) {
		echo '<div class="tr"><span class="td">' . $row[2] . ' ' . $row[3] . '</span><span class="td">' . $row[4] . '</span></div>';
		$headcount += $row[4]; 
	}
	mysqli_free_result($result);
} else {
	echo "Error: " . mysqli_connect_error($conn);
	//$message = "Error: " . mysqli_connect_error($conn);
}

mysqli_close($conn);

?>
</div>






<div class="timeblock">
<div class="tr titlerow"><span class="td titletd">6:15pm</span><span class="td titletd">
<?php 

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());	
}

$sql = "SELECT Timeblocks.block_name, Reservations.res_date, Reservations.first_name, Reservations.last_name, Reservations.party_num, Reservations.special_requests, Reservations.block_id, Timeblocks.block_time FROM Reservations JOIN Timeblocks ON Reservations.block_id = Timeblocks.block_id AND res_date = curdate() AND Timeblocks.block_time = '18:15:00' GROUP BY block_id ASC";
if ($result = mysqli_query($conn, $sql)) {
	$headcount = "";
	while ($row = mysqli_fetch_row($result)) {
		$headcount += $row[4]; 
	}
	mysqli_free_result($result);
} else {
	echo "Error: " . mysqli_connect_error($conn);
	//$message = "Error: " . mysqli_connect_error($conn);
}

mysqli_close($conn);

echo $headcount; 

?>
</span>
</div>

<?php

// 5:30pm - Show list of current reservations

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());	
}

$sql = "SELECT Timeblocks.block_name, Reservations.res_date, Reservations.first_name, Reservations.last_name, Reservations.party_num, Reservations.special_requests, Reservations.block_id, Timeblocks.block_time FROM Reservations JOIN Timeblocks ON Reservations.block_id = Timeblocks.block_id AND res_date = curdate() AND Timeblocks.block_time = '18:15:00' GROUP BY block_id ASC";
if ($result = mysqli_query($conn, $sql)) {
	$headcount = "";
	while ($row = mysqli_fetch_row($result)) {
		echo '<div class="tr"><span class="td">' . $row[2] . ' ' . $row[3] . '</span><span class="td">' . $row[4] . '</span></div>';
		$headcount += $row[4]; 
	}
	mysqli_free_result($result);
} else {
	echo "Error: " . mysqli_connect_error($conn);
	//$message = "Error: " . mysqli_connect_error($conn);
}

mysqli_close($conn);

?>
</div>




</div>























<div class="column-4">
<div class="timeblock">
<div class="tr titlerow"><span class="td titletd">6:30pm</span><span class="td titletd">
<?php 

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());	
}

$sql = "SELECT Timeblocks.block_name, Reservations.res_date, Reservations.first_name, Reservations.last_name, Reservations.party_num, Reservations.special_requests, Reservations.block_id, Timeblocks.block_time FROM Reservations JOIN Timeblocks ON Reservations.block_id = Timeblocks.block_id AND res_date = curdate() AND Timeblocks.block_time = '18:30:00' GROUP BY block_id ASC";
if ($result = mysqli_query($conn, $sql)) {
	$headcount = "";
	while ($row = mysqli_fetch_row($result)) {
		$headcount += $row[4]; 
	}
	mysqli_free_result($result);
} else {
	echo "Error: " . mysqli_connect_error($conn);
	//$message = "Error: " . mysqli_connect_error($conn);
}

mysqli_close($conn);

echo $headcount; 

?>
</span>
</div>

<?php

// 5:30pm - Show list of current reservations

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());	
}

$sql = "SELECT Timeblocks.block_name, Reservations.res_date, Reservations.first_name, Reservations.last_name, Reservations.party_num, Reservations.special_requests, Reservations.block_id, Timeblocks.block_time FROM Reservations JOIN Timeblocks ON Reservations.block_id = Timeblocks.block_id AND res_date = curdate() AND Timeblocks.block_time = '18:30:00' GROUP BY block_id ASC";
if ($result = mysqli_query($conn, $sql)) {
	$headcount = "";
	while ($row = mysqli_fetch_row($result)) {
		echo '<div class="tr"><span class="td">' . $row[2] . ' ' . $row[3] . '</span><span class="td">' . $row[4] . '</span></div>';
		$headcount += $row[4]; 
	}
	mysqli_free_result($result);
} else {
	echo "Error: " . mysqli_connect_error($conn);
	//$message = "Error: " . mysqli_connect_error($conn);
}

mysqli_close($conn);

?>
</div>






<div class="timeblock">
<div class="tr titlerow"><span class="td titletd">6:45pm</span><span class="td titletd">
<?php 

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());	
}

$sql = "SELECT Timeblocks.block_name, Reservations.res_date, Reservations.first_name, Reservations.last_name, Reservations.party_num, Reservations.special_requests, Reservations.block_id, Timeblocks.block_time FROM Reservations JOIN Timeblocks ON Reservations.block_id = Timeblocks.block_id AND res_date = curdate() AND Timeblocks.block_time = '18:45:00' GROUP BY block_id ASC";
if ($result = mysqli_query($conn, $sql)) {
	$headcount = "";
	while ($row = mysqli_fetch_row($result)) {
		$headcount += $row[4]; 
	}
	mysqli_free_result($result);
} else {
	echo "Error: " . mysqli_connect_error($conn);
	//$message = "Error: " . mysqli_connect_error($conn);
}

mysqli_close($conn);

echo $headcount; 

?>
</span>
</div>

<?php

// 5:30pm - Show list of current reservations

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());	
}

$sql = "SELECT Timeblocks.block_name, Reservations.res_date, Reservations.first_name, Reservations.last_name, Reservations.party_num, Reservations.special_requests, Reservations.block_id, Timeblocks.block_time FROM Reservations JOIN Timeblocks ON Reservations.block_id = Timeblocks.block_id AND res_date = curdate() AND Timeblocks.block_time = '18:45:00' GROUP BY block_id ASC";
if ($result = mysqli_query($conn, $sql)) {
	$headcount = "";
	while ($row = mysqli_fetch_row($result)) {
		echo '<div class="tr"><span class="td">' . $row[2] . ' ' . $row[3] . '</span><span class="td">' . $row[4] . '</span></div>';
		$headcount += $row[4]; 
	}
	mysqli_free_result($result);
} else {
	echo "Error: " . mysqli_connect_error($conn);
	//$message = "Error: " . mysqli_connect_error($conn);
}

mysqli_close($conn);

?>
</div>







<div class="timeblock">
<div class="tr titlerow"><span class="td titletd">7:00pm</span><span class="td titletd">
<?php 

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());	
}

$sql = "SELECT Timeblocks.block_name, Reservations.res_date, Reservations.first_name, Reservations.last_name, Reservations.party_num, Reservations.special_requests, Reservations.block_id, Timeblocks.block_time FROM Reservations JOIN Timeblocks ON Reservations.block_id = Timeblocks.block_id AND res_date = curdate() AND Timeblocks.block_time = '19:00:00' GROUP BY block_id ASC";
if ($result = mysqli_query($conn, $sql)) {
	$headcount = "";
	while ($row = mysqli_fetch_row($result)) {
		$headcount += $row[4]; 
	}
	mysqli_free_result($result);
} else {
	echo "Error: " . mysqli_connect_error($conn);
	//$message = "Error: " . mysqli_connect_error($conn);
}

mysqli_close($conn);

echo $headcount; 

?>
</span>
</div>

<?php

// 5:30pm - Show list of current reservations

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());	
}

$sql = "SELECT Timeblocks.block_name, Reservations.res_date, Reservations.first_name, Reservations.last_name, Reservations.party_num, Reservations.special_requests, Reservations.block_id, Timeblocks.block_time FROM Reservations JOIN Timeblocks ON Reservations.block_id = Timeblocks.block_id AND res_date = curdate() AND Timeblocks.block_time = '19:00:00' GROUP BY block_id ASC";
if ($result = mysqli_query($conn, $sql)) {
	$headcount = "";
	while ($row = mysqli_fetch_row($result)) {
		echo '<div class="tr"><span class="td">' . $row[2] . ' ' . $row[3] . '</span><span class="td">' . $row[4] . '</span></div>';
		$headcount += $row[4]; 
	}
	mysqli_free_result($result);
} else {
	echo "Error: " . mysqli_connect_error($conn);
	//$message = "Error: " . mysqli_connect_error($conn);
}

mysqli_close($conn);

?>
</div>






<div class="timeblock">
<div class="tr titlerow"><span class="td titletd">7:15pm</span><span class="td titletd">
<?php 

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());	
}

$sql = "SELECT Timeblocks.block_name, Reservations.res_date, Reservations.first_name, Reservations.last_name, Reservations.party_num, Reservations.special_requests, Reservations.block_id, Timeblocks.block_time FROM Reservations JOIN Timeblocks ON Reservations.block_id = Timeblocks.block_id AND res_date = curdate() AND Timeblocks.block_time = '19:15:00' GROUP BY block_id ASC";
if ($result = mysqli_query($conn, $sql)) {
	$headcount = "";
	while ($row = mysqli_fetch_row($result)) {
		$headcount += $row[4]; 
	}
	mysqli_free_result($result);
} else {
	echo "Error: " . mysqli_connect_error($conn);
	//$message = "Error: " . mysqli_connect_error($conn);
}

mysqli_close($conn);

echo $headcount; 

?>
</span>
</div>

<?php

// 5:30pm - Show list of current reservations

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());	
}

$sql = "SELECT Timeblocks.block_name, Reservations.res_date, Reservations.first_name, Reservations.last_name, Reservations.party_num, Reservations.special_requests, Reservations.block_id, Timeblocks.block_time FROM Reservations JOIN Timeblocks ON Reservations.block_id = Timeblocks.block_id AND res_date = curdate() AND Timeblocks.block_time = '19:15:00' GROUP BY block_id ASC";
if ($result = mysqli_query($conn, $sql)) {
	$headcount = "";
	while ($row = mysqli_fetch_row($result)) {
		echo '<div class="tr"><span class="td">' . $row[2] . ' ' . $row[3] . '</span><span class="td">' . $row[4] . '</span></div>';
		$headcount += $row[4]; 
	}
	mysqli_free_result($result);
} else {
	echo "Error: " . mysqli_connect_error($conn);
	//$message = "Error: " . mysqli_connect_error($conn);
}

mysqli_close($conn);

?>
</div>




</div>









































<div class="column-4">
<div class="timeblock">
<div class="tr titlerow"><span class="td titletd">7:30pm</span><span class="td titletd">
<?php 

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());	
}

$sql = "SELECT Timeblocks.block_name, Reservations.res_date, Reservations.first_name, Reservations.last_name, Reservations.party_num, Reservations.special_requests, Reservations.block_id, Timeblocks.block_time FROM Reservations JOIN Timeblocks ON Reservations.block_id = Timeblocks.block_id AND res_date = curdate() AND Timeblocks.block_time = '19:30:00' GROUP BY block_id ASC";
if ($result = mysqli_query($conn, $sql)) {
	$headcount = "";
	while ($row = mysqli_fetch_row($result)) {
		$headcount += $row[4]; 
	}
	mysqli_free_result($result);
} else {
	echo "Error: " . mysqli_connect_error($conn);
	//$message = "Error: " . mysqli_connect_error($conn);
}

mysqli_close($conn);

echo $headcount; 

?>
</span>
</div>

<?php

// 5:30pm - Show list of current reservations

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());	
}

$sql = "SELECT Timeblocks.block_name, Reservations.res_date, Reservations.first_name, Reservations.last_name, Reservations.party_num, Reservations.special_requests, Reservations.block_id, Timeblocks.block_time FROM Reservations JOIN Timeblocks ON Reservations.block_id = Timeblocks.block_id AND res_date = curdate() AND Timeblocks.block_time = '19:30:00' GROUP BY block_id ASC";
if ($result = mysqli_query($conn, $sql)) {
	$headcount = "";
	while ($row = mysqli_fetch_row($result)) {
		echo '<div class="tr"><span class="td">' . $row[2] . ' ' . $row[3] . '</span><span class="td">' . $row[4] . '</span></div>';
		$headcount += $row[4]; 
	}
	mysqli_free_result($result);
} else {
	echo "Error: " . mysqli_connect_error($conn);
	//$message = "Error: " . mysqli_connect_error($conn);
}

mysqli_close($conn);

?>
</div>






<div class="timeblock">
<div class="tr titlerow"><span class="td titletd">7:45pm</span><span class="td titletd">
<?php 

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());	
}

$sql = "SELECT Timeblocks.block_name, Reservations.res_date, Reservations.first_name, Reservations.last_name, Reservations.party_num, Reservations.special_requests, Reservations.block_id, Timeblocks.block_time FROM Reservations JOIN Timeblocks ON Reservations.block_id = Timeblocks.block_id AND res_date = curdate() AND Timeblocks.block_time = '19:45:00' GROUP BY block_id ASC";
if ($result = mysqli_query($conn, $sql)) {
	$headcount = "";
	while ($row = mysqli_fetch_row($result)) {
		$headcount += $row[4]; 
	}
	mysqli_free_result($result);
} else {
	echo "Error: " . mysqli_connect_error($conn);
	//$message = "Error: " . mysqli_connect_error($conn);
}

mysqli_close($conn);

echo $headcount; 

?>
</span>
</div>

<?php

// 5:30pm - Show list of current reservations

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());	
}

$sql = "SELECT Timeblocks.block_name, Reservations.res_date, Reservations.first_name, Reservations.last_name, Reservations.party_num, Reservations.special_requests, Reservations.block_id, Timeblocks.block_time FROM Reservations JOIN Timeblocks ON Reservations.block_id = Timeblocks.block_id AND res_date = curdate() AND Timeblocks.block_time = '19:45:00' GROUP BY block_id ASC";
if ($result = mysqli_query($conn, $sql)) {
	$headcount = "";
	while ($row = mysqli_fetch_row($result)) {
		echo '<div class="tr"><span class="td">' . $row[2] . ' ' . $row[3] . '</span><span class="td">' . $row[4] . '</span></div>';
		$headcount += $row[4]; 
	}
	mysqli_free_result($result);
} else {
	echo "Error: " . mysqli_connect_error($conn);
	//$message = "Error: " . mysqli_connect_error($conn);
}

mysqli_close($conn);

?>
</div>







<div class="timeblock">
<div class="tr titlerow"><span class="td titletd">8:00pm</span><span class="td titletd">
<?php 

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());	
}

$sql = "SELECT Timeblocks.block_name, Reservations.res_date, Reservations.first_name, Reservations.last_name, Reservations.party_num, Reservations.special_requests, Reservations.block_id, Timeblocks.block_time FROM Reservations JOIN Timeblocks ON Reservations.block_id = Timeblocks.block_id AND res_date = curdate() AND Timeblocks.block_time = '20:00:00' GROUP BY block_id ASC";
if ($result = mysqli_query($conn, $sql)) {
	$headcount = "";
	while ($row = mysqli_fetch_row($result)) {
		$headcount += $row[4]; 
	}
	mysqli_free_result($result);
} else {
	echo "Error: " . mysqli_connect_error($conn);
	//$message = "Error: " . mysqli_connect_error($conn);
}

mysqli_close($conn);

echo $headcount; 

?>
</span>
</div>

<?php

// 5:30pm - Show list of current reservations

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());	
}

$sql = "SELECT Timeblocks.block_name, Reservations.res_date, Reservations.first_name, Reservations.last_name, Reservations.party_num, Reservations.special_requests, Reservations.block_id, Timeblocks.block_time FROM Reservations JOIN Timeblocks ON Reservations.block_id = Timeblocks.block_id AND res_date = curdate() AND Timeblocks.block_time = '20:00:00' GROUP BY block_id ASC";
if ($result = mysqli_query($conn, $sql)) {
	$headcount = "";
	while ($row = mysqli_fetch_row($result)) {
		echo '<div class="tr"><span class="td">' . $row[2] . ' ' . $row[3] . '</span><span class="td">' . $row[4] . '</span></div>';
		$headcount += $row[4]; 
	}
	mysqli_free_result($result);
} else {
	echo "Error: " . mysqli_connect_error($conn);
	//$message = "Error: " . mysqli_connect_error($conn);
}

mysqli_close($conn);

?>
</div>






<div class="timeblock">
<div class="tr titlerow"><span class="td titletd">8:15pm</span><span class="td titletd">
<?php 

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());	
}

$sql = "SELECT Timeblocks.block_name, Reservations.res_date, Reservations.first_name, Reservations.last_name, Reservations.party_num, Reservations.special_requests, Reservations.block_id, Timeblocks.block_time FROM Reservations JOIN Timeblocks ON Reservations.block_id = Timeblocks.block_id AND res_date = curdate() AND Timeblocks.block_time = '20:15:00' GROUP BY block_id ASC";
if ($result = mysqli_query($conn, $sql)) {
	$headcount = "";
	while ($row = mysqli_fetch_row($result)) {
		$headcount += $row[4]; 
	}
	mysqli_free_result($result);
} else {
	echo "Error: " . mysqli_connect_error($conn);
	//$message = "Error: " . mysqli_connect_error($conn);
}

mysqli_close($conn);

echo $headcount; 

?>
</span>
</div>

<?php

// 5:30pm - Show list of current reservations

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());	
}

$sql = "SELECT Timeblocks.block_name, Reservations.res_date, Reservations.first_name, Reservations.last_name, Reservations.party_num, Reservations.special_requests, Reservations.block_id, Timeblocks.block_time FROM Reservations JOIN Timeblocks ON Reservations.block_id = Timeblocks.block_id AND res_date = curdate() AND Timeblocks.block_time = '20:15:00' GROUP BY block_id ASC";
if ($result = mysqli_query($conn, $sql)) {
	$headcount = "";
	while ($row = mysqli_fetch_row($result)) {
		echo '<div class="tr"><span class="td">' . $row[2] . ' ' . $row[3] . '</span><span class="td">' . $row[4] . '</span></div>';
		$headcount += $row[4]; 
	}
	mysqli_free_result($result);
} else {
	echo "Error: " . mysqli_connect_error($conn);
	//$message = "Error: " . mysqli_connect_error($conn);
}

mysqli_close($conn);

?>
</div>

<div class="timeblock">
<div class="tr titlerow"><span class="td titletd">8:30pm</span><span class="td titletd">
<?php 

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());	
}

$sql = "SELECT Timeblocks.block_name, Reservations.res_date, Reservations.first_name, Reservations.last_name, Reservations.party_num, Reservations.special_requests, Reservations.block_id, Timeblocks.block_time FROM Reservations JOIN Timeblocks ON Reservations.block_id = Timeblocks.block_id AND res_date = curdate() AND Timeblocks.block_time = '20:30:00' GROUP BY block_id ASC";
if ($result = mysqli_query($conn, $sql)) {
	$headcount = "";
	while ($row = mysqli_fetch_row($result)) {
		$headcount += $row[4]; 
	}
	mysqli_free_result($result);
} else {
	echo "Error: " . mysqli_connect_error($conn);
	//$message = "Error: " . mysqli_connect_error($conn);
}

mysqli_close($conn);

echo $headcount; 

?>
</span>
</div>

<?php

// 5:30pm - Show list of current reservations

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());	
}

$sql = "SELECT Timeblocks.block_name, Reservations.res_date, Reservations.first_name, Reservations.last_name, Reservations.party_num, Reservations.special_requests, Reservations.block_id, Timeblocks.block_time FROM Reservations JOIN Timeblocks ON Reservations.block_id = Timeblocks.block_id AND res_date = curdate() AND Timeblocks.block_time = '20:30:00' GROUP BY block_id ASC";
if ($result = mysqli_query($conn, $sql)) {
	$headcount = "";
	while ($row = mysqli_fetch_row($result)) {
		echo '<div class="tr"><span class="td">' . $row[2] . ' ' . $row[3] . '</span><span class="td">' . $row[4] . '</span></div>';
		$headcount += $row[4]; 
	}
	mysqli_free_result($result);
} else {
	echo "Error: " . mysqli_connect_error($conn);
	//$message = "Error: " . mysqli_connect_error($conn);
}

mysqli_close($conn);

?>
</div>





</div>













</body>
</html>
