<?php
	session_start();
 	$servername = "localhost";
	$username = "root";
	$password = "root";
	$dbname = 'oasis';

	$conn = new mysqli($servername, $username, $password, $dbname);


	if ($conn->connect_error) {
   		die("Connection failed: " . $conn->connect_error);
	}

	if ($_SESSION['session_type'] == 'student') {

	 	echo "Connected successfully";
	 	$sql = "INSERT INTO student (student_fname, student_lname, student_num, student_email, student_course, student_section, student_companion , created_at) 
	 	VALUES ('" . $_SESSION['student']['student_fname'] . "' , '"
	 	 	. $_SESSION['student']['student_lname'] . "' , '"
	 	  	. $_SESSION['student']['student_num'] . "' , '" 
	 	  	. $_SESSION['student']['student_email'] . "' , '" 
	 	  	. $_SESSION['student']['student_course'] . "' , '" 
	 	  	. $_SESSION['student']['student_section'] . "' , '"
	 	  	.$_SESSION['student']['student_companion'] . "' , '" 
	 	  	. date('Y-m-d H:i:s'). "')";


		if ($conn->query($sql) === TRUE) {
	  		echo "New record created successfully";
	   		$last_id = $conn->insert_id;

	   		//$_SESSION['student']['student_id'] = $last_id;
	   		if (!empty($_SESSION['student']['companion'])) {
	   			for ($i = 0; $i < count($_SESSION['student']['companion']); $i++) {

				$sql = "INSERT INTO student_companion (student_id, sCompanion_fname, sCompanion_lname, sCompanion_email, sCompanion_number, created_at) 
				VALUES (" . $last_id . ", '" . $_SESSION['student']['companion'][$i]['sCompanion_fname'] . "' , '" 
					. $_SESSION['student']['companion'][$i]['sCompanion_lname'] . "' , '" 
					. $_SESSION['student']['companion'][$i]['sCompanion_email'] . "' , '" 
					. $_SESSION['student']['companion'][$i]['sCompanion_number'] . "' , '" 
					. date('Y-m-d H:i:s'). "')";

				if ($conn->query($sql) === TRUE) {
		  			echo "New record created successfully";
				
				}else {
		  			echo "Error: " . $sql . "<br>" . $conn->error;
				
				}
			}
	   		}
	   		

			$dateTimeStamp = DateTime::createFromFormat('m-d-Y H:i:s', $_SESSION['student']['appointment']['date_time']);

			$sql = "INSERT INTO appointment (department, reason, user_id, student_id, faculty, date_time) 
			VALUES ('" . $_SESSION['student']['appointment']['department'] . "' , '" . $_SESSION['student']['appointment']['reason'] . "' , '" . $_SESSION['student']['appointment']['user_id'] . "' , '" . $last_id . "' , '" . $_SESSION['student']['appointment']['faculty_name'] . "' , '" . $dateTimeStamp->format('Y-m-d H:i:s')  . "')";

			if ($conn->query($sql) === TRUE) {
	  			echo "New record created successfully";
	  			// unset($_SESSION['appointment']);
	  			unset($_SESSION['appointment']);
	  			unset($_SESSION['student']);

			}else {
	  			echo "Error: " . $sql . "<br>" . $conn->error;
			}
			
		}else {
	  		echo "Error: " . $sql . "<br>" . $conn->error;
		}
	} else {
		// Guest appointment
		echo "Connected successfully";
	 	$sql = "INSERT INTO guest (guest_fname, guest_lname, guest_address, guest_number, guest_email, guest_companion, created_at) 
	 	VALUES ('" . $_SESSION['guest']['guest_fname'] . "' , '"
	 	 	. $_SESSION['guest']['guest_lname'] . "' , '"
	 	  	. $_SESSION['guest']['guest_address'] . "' , '" 
	 	  	. $_SESSION['guest']['guest_number'] . "' , '" 
	 	  	. $_SESSION['guest']['guest_email'] . "' , '" 
	 	  	. $_SESSION['guest']['guest_companion'] . "' , '" 
	 	  	. date('Y-m-d H:i:s'). "')";


		if ($conn->query($sql) === TRUE) {
	  		echo "New record created successfully";
	   		$last_id = $conn->insert_id;

	   		if (!empty($_SESSION['guest']['companion'])) {
	   			for ($i = 0; $i < count($_SESSION['guest']['companion']); $i++) {

				$sql = "INSERT INTO guest_companion (guest_id, gCompanion_fname, gCompanion_lname, gCompanion_email, gCompanion_number, created_at) 
				VALUES (" . $last_id . ", '" . $_SESSION['guest']['companion'][$i]['gCompanion_fname'] . "' , '" 
					. $_SESSION['guest']['companion'][$i]['gCompanion_lname'] . "' , '" 
					. $_SESSION['guest']['companion'][$i]['gCompanion_email'] . "' , '" 
					. $_SESSION['guest']['companion'][$i]['gCompanion_number'] . "' , '" 
					. date('Y-m-d H:i:s'). "')";

				if ($conn->query($sql) === TRUE) {
		  			echo "New record created successfully";
				
				}else {
		  			echo "Error: " . $sql . "<br>" . $conn->error;
				
				}
	   		}
	   		
			}

			$dateTimeStamp = DateTime::createFromFormat('m-d-Y H:i:s', $_SESSION['guest']['appointment']['date_time']);

			$sql = "INSERT INTO appointment (department, reason, user_id, guest_id, faculty, date_time) 
			VALUES ('" . $_SESSION['guest']['appointment']['department'] . "' , '" . $_SESSION['guest']['appointment']['reason'] . "' , '" . $_SESSION['guest']['appointment']['user_id'] . "' , '" . $last_id . "' , '" .  $_SESSION['guest']['appointment']['faculty_name'] . "' , '" . $dateTimeStamp->format('Y-m-d H:i:s')  . "')";

			if ($conn->query($sql) === TRUE) {
	  			echo "New record created successfully";
	  			unset($_SESSION['appointment']);
	  			unset($_SESSION['guest']);

			}else {
	  			echo "Error: " . $sql . "<br>" . $conn->error;
			}
			
		}else {
	  		echo "Error: " . $sql . "<br>" . $conn->error;
		}
	}

	 $conn->close();
	 header("Location: homepage.php");
	 exit();


	 ?>