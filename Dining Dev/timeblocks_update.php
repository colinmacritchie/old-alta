<?php


/*

error_reporting(E_ALL);
ini_set('display_errors',1);

include "DB/dbaccess.php";

$conn = mysqli_connect($servername, $username, $password, $dbname);

$sql = "SELECT block_id, block_time FROM rustler_res.Timeblocks;";

if ($query = mysqli_query($conn, $sql)) {
	$i = '0';
	while ($row = mysqli_fetch_assoc($query)) {
		$rows[] = $row;
		
		$block_id = $rows[$i]['block_id'];
		$block_time = $rows[$i]['block_time'];
		
		echo "block_id: " . $block_id . "<br><br>";
		echo "block_time: " . $block_time . "<br><br>";
		
		$updatesql = "UPDATE rustler_res.Timeblocks SET original_block_time = '" . $block_time . "' WHERE block_id = '" . $block_id . "';";
			
		if (mysqli_query($conn, $updatesql)) {
			echo "Success/<br><br>";
		} else {
			echo "Failed.<br><br>";
		}
		
		$i++;
		
	}
	//$block_id = $rows[0]['block_id'];
	mysqli_free_result($query);
} else {
	echo "Error New Res: " . mysqli_connect_error($conn);
	//$message = "Error: " . mysqli_connect_error($conn);
}



mysqli_close($conn);
	

*/

?>