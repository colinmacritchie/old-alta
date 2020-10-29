<?php



error_reporting(E_ALL);
ini_set('display_errors',1);

include "DB/dbaccess.php";

//$table_array = array("26"=>"63", "27"=>"64", "28"=>"65", "29"=>"71", "30"=>"72", "31"=>"73", "32"=>"74", "33"=>"81");

$table_array = array();
$table_array_sql = "SELECT table_id, table_num FROM Res_Tables";

$conn = mysqli_connect($servername, $username, $password, $dbname);
if ($table_array_query = mysqli_query($conn, $table_array_sql)) {
	while ($row = mysqli_fetch_assoc($table_array_query)) {
		if ($row['table_id'] != '35') {
			$table_array[$row['table_id']] = $row['table_num'];
		}
	}
}

mysqli_close($conn);

//echo '<pre>' . print_r($table_array, TRUE) . '</pre>';
		


foreach($table_array as $x => $x_value) {

	$i = 1;
	$start_time = strtotime("07:00:00");
	
	while ($i < 14) {
		
		$block_name_time = date("g:ia", $start_time);	
		
		$table_id = $x;
		$table_number = $x_value;
		
		$block_name = "Table #" . $table_number . " - " . $block_name_time;
		$block_time = date("G:i:s", $start_time);
		$block_number = $i;
		$res_time_id = $i + 28;
		
		$qry = "INSERT INTO Timeblocks (block_name, block_time, block_number, res_time_id, table_id, original_block_time) VALUES ('" . $block_name . "', '" . $block_time . "', '" . $block_number . "', '" . $res_time_id . "', '" . $table_id . "', '" . $block_time . "');";
		
		echo "qry: " . $qry . "<br><br>";
		
		
		// Uncomment this out to implement
		
		/*
		$conn = mysqli_connect($servername, $username, $password, $dbname);
		
		//$test_qry = "INSERT INTO Timeblocks (block_name, block_time, block_number, res_time_id, table_id) VALUES ('Table #62 - 8:30pm', '20:30:00', '11', '11', '25');";
		
		if (mysqli_query($conn, $qry)) {
			echo $table_id . " - " . $table_number . "Inserted successfully <br><br>";
		} else {
			echo "Error: " . mysqli_connect_error($conn);
		}
		mysqli_close($conn);
		*/
		
		
		//echo $qry . "<br><br>";
		
		
		$start_time += 900;
		
		/* Old method of inserting different times.
		
		if ( $i < 4) { 
			$start_time = $start_time + 600; //10 minutes
		}
		if ( $i > 3 && $i < 6) {
			$start_time = $start_time + 900; //15 minutes
		}
		if ( $i > 5 && $i < 7) {
			$start_time = $start_time + 1800; //30 minutes
		}
		if ( $i > 6) {
			$start_time = $start_time + 900; //15 minutes
		}
		
		*/
		
		$i++;
	}

}






?>