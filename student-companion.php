<?php
	session_start();

 	// echo "<pre>";
	// var_dump($_POST);echo "</pre>"; die;


	// $servername = "localhost";
	// $username = "root";
	// $password = "root";
	// $dbname = 'oasis';

	// $conn = new mysqli($servername, $username, $password, $dbname);


	// if ($conn->connect_error) {
 //  		die("Connection failed: " . $conn->connect_error);
	// }
	// echo "Connected successfully";
	
	// for ($i = 0; $i < count($_POST["companion-fname_"]); $i++) {

	// 	$sql = "INSERT INTO student_companion (sCompanion_fname, sCompanion_lname, sCompanion_email, sCompanion_number, created_at) 
	// 	VALUES ('" . $_POST["companion-fname_"][$i] . "' , '" . $_POST["companion-lname_"][$i] . "' , '" . $_POST["companion-email_"][$i] . "' , '" . $_POST["companion-contactnum_"][$i] . "' , '" . date('Y-m-d H:i:s'). "')";

	// 	if ($conn->query($sql) === TRUE) {
	//   		echo "New record created successfully";
	// 	} else {
	//   		echo "Error: " . $sql . "<br>" . $conn->error;
			
	// 	}
	// }

	// $conn->close();

	$_SESSION['student']['companion'] = [];

	for ($i = 0; $i < count($_POST["companion-fname_"]); $i++) {
		$_SESSION['student']['companion'][$i] = [
			'sCompanion_fname' => $_POST["companion-fname_"][$i],
			'sCompanion_lname' => $_POST["companion-lname_"][$i],
			'sCompanion_email' => $_POST["companion-email_"][$i],
			'sCompanion_number' => $_POST["companion-contactnum_"][$i],
		];
	}

	
	header("Location: book-appointment.php");
   	exit;
?>
