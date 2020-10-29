<?php

/*

error_reporting(E_ALL);
ini_set('display_errors',1);

include "DB/dbaccess.php";

//$table_array = array("26"=>"63", "27"=>"64", "28"=>"65", "29"=>"71", "30"=>"72", "31"=>"73", "32"=>"74", "33"=>"81");

//foreach($table_array as $x => $x_value) {

$i = '1';
//$start_time = strtotime("18:00:00");

while ($i < '36') {
	
	//$qry = "ALTER TABLE Res_Table_Config ADD (table_active_" . $i . " tinyint(1) NOT NULL DEFAULT 0, table_min_" . $i . " int(11) NOT NULL, table_max_" . $i . " int(11) NOT NULL);";
	
	//$qry = "ALTER TABLE Res_Table_Config ADD table_wasatch_" . $i . " tinyint(1) NOT NULL DEFAULT 0;";
	
	//$qry = "ALTER TABLE `Res_Table_Config` CHANGE `table_active_" . $i . "` `table_active_" . $i . "` TINYINT(1) NOT NULL DEFAULT '1';";
	
	$qry = "ALTER TABLE Res_Table_Config ADD (table_row_" . $i . " int(11) NOT NULL, table_column_" . $i . " int(11) NOT NULL);";
	
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	
	//$test_qry = "INSERT INTO Timeblocks (block_name, block_time, block_number, res_time_id, table_id) VALUES ('Table #62 - 8:30pm', '20:30:00', '11', '11', '25');";
	
	
	
	if (mysqli_query($conn, $qry)) {
		echo "tableactive# " . $i . " changed successfully <br><br>";
	} else {
		echo "Error: " . mysqli_error($conn) . "<br><br>";
	}
	mysqli_close($conn);
	
	
	
	//echo $qry . "<br><br>";
	
	
	
	
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
	
		
	
	$i++;
}

//}


*/

?>



?>