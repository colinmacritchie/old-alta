<?php

//error_reporting(E_ALL);
//ini_set('display_errors',1);


// put full path to Smarty.class.php
require('/users/s/skigmd/skigmd_html/Reservations/lib/php/Smarty/Smarty.class.php');
$smarty = new Smarty();

$smarty->setTemplateDir('/users/s/skigmd/skigmd_html/Reservations/tpl');
$smarty->setCompileDir('/users/s/skigmd/skigmd_html/Reservations/smarty/templates_c');
$smarty->setCacheDir('/users/s/skigmd/skigmd_html/Reservations/smarty/cache');
$smarty->setConfigDir('/users/s/skigmd/skigmd_html/Reservations/smarty/configs');

//Example
$smarty->assign('name', 'DonRaphael');


include 'DB/dbaccess.php';

date_default_timezone_set('America/Denver');

$serverself = $_SERVER['PHP_SELF'];
$smarty->assign('serverself', $serverself);

//print_r($_POST);
//echo "<br><br>";

echo "Header Landmark 1";

// BEGIN House Total Seatings

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());	
}

$xsql = "SELECT SUM(party_num) FROM Reservations WHERE res_date = CURRENT_DATE AND res_status != '2'";

if ($result = mysqli_query($conn, $xsql)) {
	$smarty->assign('House_Total', $result);
	mysqli_free_result($result);
} else {
	echo "Error: " . mysqli_connect_error($conn);
	//$message = "Error: " . mysqli_connect_error($conn);
}

mysqli_close($conn);

// END House Total Seatings



$smarty->display('header.tpl');

?>
