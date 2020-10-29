<?php



error_reporting(E_ALL);
ini_set('display_errors',1);

include "DB/dbaccess.php";

//$table_array = array("26"=>"63", "27"=>"64", "28"=>"65", "29"=>"71", "30"=>"72", "31"=>"73", "32"=>"74", "33"=>"81");

//foreach($table_array as $x => $x_value) {

?>




<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Reservations: Reservation Tables</title>

    <meta name="apple-mobile-web-app-capable" content="yes">
    
	<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
    <script type="text/javascript">
	  $(document).bind("mobileinit", function () { $.mobile.ajaxEnabled = false; });
	</script>
	
	<script src="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jtsage-datebox-jqm@5.1.3/jtsage-datebox.min.js" type="text/javascript"></script>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css" />    
 
    <link rel="stylesheet" href="lib/css/Reservations.css" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
</head>
<body>

<form>
  <div class="form-row">
  	<div class="form-group col-md-6">
      <label for="inputPassword4">Table Name</label>
      <input type="text" class="form-control" id="table_name" placeholder="Password">
      <small id="table_name_help" class="form-text text-muted">We'll never share your email with anyone else.</small>
    </div>
    <div class="form-group col-md-3">
      <label for="table_column">Table Row</label>
      <input type="number" class="form-control" id="table_column" placeholder="Table Row">
      <small id="table_row_help" class="form-text text-muted">We'll never share your email with anyone else.</small>
    </div>
    <div class="form-group col-md-3">
      <label for="table_row">Table Column</label>
      <input type="number" class="form-control" id="table_row" placeholder="Table Column">
      <small id="table_column_help" class="form-text text-muted">We'll never share your email with anyone else.</small>
    </div>
  </div>
  <div class="form-row">
    <div class="form-group col-md-6">
      <label for="inputCity">Minimum Capacity</label>
      <input type="text" class="form-control" id="inputCity">
    </div>
    <div class="form-group col-md-6">
      <label for="inputCity">Maximum Capacity</label>
      <input type="text" class="form-control" id="inputCity">
    </div>
  </div>
  <input type="hidden" name="submit-new-table" id="submit-new-table" value="submit-new-table" />
  <button type="submit" class="btn btn-primary">Insert Table</button>
</form>

</body>
</html>






<?php

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




?>



?>