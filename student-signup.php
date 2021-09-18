<?php
	session_start();
	$_SESSION['comp_count'] = $_POST["student-companion"];

	// $servername = "localhost";
	// $username = "root";
	// $password = "root";
	// $dbname = 'oasis';

	// $conn = new mysqli($servername, $username, $password, $dbname);


	// if ($conn->connect_error) {
 //  	die("Connection failed: " . $conn->connect_error);
	// }
	// echo "Connected successfully";
	// $sql = "INSERT INTO student (student_fname, student_lname, student_num, student_email, student_course, student_section, student_companion , created_at) 
	// VALUES ('" . $_POST["fname"] . "' , '" . $_POST["lname"] . "' , '" . $_POST["student-num"] . "' , '" . $_POST["mail"] . "' , '" . $_POST["student-course"] . "' , '" . $_POST["student-section"] . "' , '".$_POST["student-companion"] . "' , '" . date('Y-m-d H:i:s'). "')";


	// if ($conn->query($sql) === TRUE) {
 //  	echo "New record created successfully";
 //  	$last_id = $conn->insert_id;

 //  	$_SESSION['comp_count'] = $_POST["student-companion"];
 //  	$_SESSION['student']['student_id'] = $last_id;

 //  	if ($_POST["student-companion"]=="0") {
 //  		header("Location: book-appointment.php");
 //  		exit;
 //  	}

	// }
	//  else {
 //  	echo "Error: " . $sql . "<br>" . $conn->error;
	// }

	// $conn->close();


	// Save to session first
	// $_SESSION['student'] = [
	// 	'student_fname' => $_POST["fname"],
	// 	'student_lname' => $_POST["lname"],
	// 	'student_num' => $_POST["student-num"],
	// 	'student_email' => $_POST["mail"],
	// 	'student_course' => $_POST["student-course"],
	// 	'student_section' => $_POST["student-section"],
	// 	'student_companion' => $_POST["student-companion"]

	// ];

	$_SESSION['student']['student_fname'] = $_POST["fname"];
	$_SESSION['student']['student_lname'] = $_POST["lname"];
	$_SESSION['student']['student_num'] = $_POST["student-num"];
	$_SESSION['student']['student_email'] = $_POST["mail"];
	$_SESSION['student']['student_course'] = $_POST["student-course"];
	$_SESSION['student']['student_section'] = $_POST["student-section"];
	$_SESSION['student']['student_companion'] = $_POST["student-companion"];

	if ($_POST["student-companion"]=="0") {
  		header("Location: book-appointment.php");
  		exit;
  	}

	header("Location: studentUI.php");
   	exit;
?>