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
 //  	die("Connection failed: " . $conn->connect_error);
	// }
	// echo "Connected successfully";
	
	// for ($i = 0; $i < count($_POST["companion-fname_"]); $i++) {

	// 	$sql = "INSERT INTO guest_companion (gCompanion_fname, gCompanion_lname, gCompanion_email, gCompanion_number, created_at) 
	// 	VALUES ('" . $_POST["companion-fname_"][$i] . "' , '" . $_POST["companion-lname_"][$i] . "' , '" . $_POST["companion-email_"][$i] . "' , '" . $_POST["companion-contactnum_"][$i] . "' , '" . date('Y-m-d H:i:s'). "')";

	// 	if ($conn->query($sql) === TRUE) {
 //  		echo "New record created successfully";
	// 	}
	// 	else {
 //  		echo "Error: " . $sql . "<br>" . $conn->error;
	// 	}

	// }

	// $conn->close();

	echo "<pre>";
	var_dump($_POST);
	echo "</pre>";
	for ($i = 0; $i < count($_POST["companion-fname_"]); $i++) {
		$_SESSION['guest']['companion'][$i] = [
			'gCompanion_fname' => $_POST["companion-fname_"][$i],
			'gCompanion_lname' => $_POST["companion-lname_"][$i],
			'gCompanion_email' => $_POST["companion-email_"][$i],
			'gCompanion_number' => $_POST["companion-contactnum_"][$i],
		];
	}

	header("Location: book-appointment.php");
   	exit;
?>
