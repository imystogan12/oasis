<?php
	session_start();
	$_SESSION['guest_comp_count'] = $_POST["guest-companion"];
	// $servername = "localhost";
	// $username = "root";
	// $password = "root";
	// $dbname = 'oasis';

	// $conn = new mysqli($servername, $username, $password, $dbname);


	// if ($conn->connect_error) {
 //  	die("Connection failed: " . $conn->connect_error);
	// }
	// echo "Connected successfully";
	// $sql = "INSERT INTO guest (guest_fname, guest_lname, guest_address, guest_number, guest_email, guest_companion, created_at) 
	// VALUES ('".$_POST["fname"] . "' , '".$_POST["lname"] . "' , '".$_POST["guest-address"] . "' , '".$_POST["guest-number"] . "' , '".$_POST["guest-mail"] . "' , '".$_POST["guest-companion"] . "' , '" . date('Y-m-d H:i:s') . "')";


	// if ($conn->query($sql) === TRUE) {
 //  	echo "New record created successfully";

 //  	$_SESSION['guest_comp_count'] = $_POST["guest-companion"];

  	

	// } else {
 //  	echo "Error: " . $sql . "<br>" . $conn->error;
	// }

	// $conn->close();
	$_SESSION['guest']['guest_fname'] = $_POST["fname"];
	$_SESSION['guest']['guest_lname'] = $_POST["lname"];
	$_SESSION['guest']['guest_address'] = $_POST["guest-address"];
	$_SESSION['guest']['guest_number'] = $_POST["guest-number"];
	$_SESSION['guest']['guest_email'] = $_POST["guest-mail"];
	$_SESSION['guest']['guest_companion'] = $_POST["guest-companion"];

	if ($_POST["guest-companion"]=="0") {
  		header("Location: book-appointment.php");
  		exit;
  	}

	header("Location: guestUI.php");
   	exit;

?>