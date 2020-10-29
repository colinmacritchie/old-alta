<?php



error_reporting(E_ALL);
ini_set('display_errors',1);

include "DB/dbaccess.php";
include 'res_func.php';

//$table_array = array("26"=>"63", "27"=>"64", "28"=>"65", "29"=>"71", "30"=>"72", "31"=>"73", "32"=>"74", "33"=>"81");

//foreach($table_array as $x => $x_value) {
	
//echo '<pre>' . print_r($_POST, TRUE) . '</pre>';	

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



<div class="row" style="margin-top:50px; margin-left:auto; margin-right:auto; float:none;">
	<div class="col-md-6" style="margin-left:auto; margin-right:auto; float:none;">
    
    	<h1 style="text-align:center;">Insert New Table</h1>

        <form action="" method="POST">
          <div class="form-row">
            <div class="form-group col-md-12">
              <label for="inputPassword4">Table Name</label>
              <input type="text" class="form-control" id="table_name" name="table_name" placeholder="Table Name">
            </div>
          </div>
          <div class="form-row">
          	<div class="form-group col-md-6">
              <label for="table_row">Table Column</label>
              <input type="number" class="form-control" id="table_row" name="table_row" placeholder="Table Column">
            </div>
            <div class="form-group col-md-6">
              <label for="table_column">Table Row</label>
              <input type="number" class="form-control" id="table_column" name="table_column" placeholder="Table Row">
            </div>
            
          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="inputCity">Minimum Capacity</label>
              <input type="text" class="form-control" id="capacity_min" name="capacity_min" placeholder="Minimum Capacity">
            </div>
            <div class="form-group col-md-6">
              <label for="inputCity">Maximum Capacity</label>
              <input type="text" class="form-control" id="capacity_max" name="capacity_max" placeholder="Maximum Capacity">
            </div>
          </div>
          <input type="hidden" name="submit-new-table" id="submit-new-table" value="submit-new-table" />
          <button type="submit" class="btn btn-primary">Insert Table</button>
        </form>
  
	</div>        
</div>        	

</body>
</html>






<?php


if(isset($_POST['submit-new-table'])) {


	//echo '<pre>' . print_r($_POST, TRUE) . '</pre>';

	// Get POST values
	// Define variables and set to empty values
	$table_id = "";

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$table_name = validate_input($_POST["table_name"]);	
		$table_column = validate_input($_POST["table_column"]);	
		$table_row = validate_input($_POST["table_row"]);	
		$capacity_min = validate_input($_POST["capacity_min"]);	
		$capacity_max = validate_input($_POST["capacity_max"]);	
	} else {
		$message = 'Unable to collect POST values';	
		//echo $message;
	}
	
	if (!isset($table_name)) {
		$table_name = '';
	}
	
	$table_num = $table_row . $table_column;
	
	$new_table_query  = "INSERT INTO 
			Res_Tables (table_row, table_column, table_num, capacity_min, capacity_max, table_active, wasatch, table_name) 
			VALUES ('$table_row', '$table_column', '$table_num', '$capacity_min', '$capacity_max', '0', '0', '$table_name')";
			
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
	  die("Connection failed: " . $conn->connect_error);
	}
	
	if ($conn->query($new_table_query) === TRUE) {
	  $last_id = $conn->insert_id;
	  //echo "New Res_Tables record created successfully. Last inserted ID is: " . $last_id;
	} else {
	  //echo "Error: " . $new_table_query . "<br>" . $conn->error;
	}

	$change_table_config_query = "ALTER TABLE Res_Table_Config
				ADD (
					table_active_" . $last_id . " tinyint(1) NOT NULL DEFAULT 0, 
					table_min_" . $last_id . " int(11) NOT NULL DEFAULT '$capacity_min',
					table_max_" . $last_id . " int(11) NOT NULL DEFAULT '$capacity_max',
					table_wasatch_" . $last_id . " int(11) NOT NULL DEFAULT 0,
					table_row_" . $last_id . " int(11) NOT NULL DEFAULT '$table_row', 
					table_column_" . $last_id . " int(11) NOT NULL DEFAULT '$table_column'
					);";
					
	if ($conn->query($change_table_config_query) === TRUE) {
	  $last_id = $conn->insert_id;
	  //echo "Table Config successfully updated.";
	} else {
	  //echo "Error: " . $change_table_config_query . "<br>" . $conn->error;
	}
					
	//echo "new_table_query: " . $new_table_query . "<br><br>";
	//echo "change_table_config_query: " . $change_table_config_query . "<br><br>";
	
	$conn->close();
	
}


?>
