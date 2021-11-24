<?php
	// include('database.php');
	include '../../database.php';  
	session_start();

	if (isset($_POST['name'])) {
		// $servername = "localhost";
		// $username = "root";
		// $password = "root";
		// $dbname = 'oasis';

		// $conn = new mysqli($servername, $username, $password, $dbname);
		// if ($conn->connect_error) {
  // 			die("Connection failed: " . $conn->connect_error);
		// }
		$name = strip_tags($_POST['name']);
		$value = strip_tags($_POST['value']);
		$sql = "INSERT INTO department (name, value)
				VALUES ('" . $name . "' , '" . $value . "')";

				if ($conn->query($sql) === TRUE) {
					var_dump('success');
  					echo "New record created successfully";
  				} else {
  					var_dump('failed');
  					echo "Error: " . $sql . "<br>" . $conn->error;
  				}
  	}

?>