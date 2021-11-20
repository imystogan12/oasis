<?php
	include('database.php');
	session_start();
	// $servername = "localhost";
	// $username = "root";
	// $password = "root";
	// $dbname = 'oasis';

	// $conn = new mysqli($servername, $username, $password, $dbname);
	// 	if ($conn->connect_error) {
 //  			die("Connection failed: " . $conn->connect_error);
	// 	}

	$id = '';
	if (isset($_GET['id'])) {
		$sql = "UPDATE faculty SET deleted_at='" . date('Y-m-d H:i:s') . "'
				WHERE id=" . $_GET['id'];
		if ($conn->query($sql) === TRUE) {
			echo "New record created successfully";
		} else {
			echo "Error: " . $sql . "<br>" . $conn->error;
		}
		header("Location: faculty.php");
		exit;
	}
?>