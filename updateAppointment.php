<?php 
	session_start();
	//if (isset($_POST['btn'])) {
		// echo "<pre>";
		// var_dump($_POST);
		// echo "</pre>";

		// $submitValue = $_POST['btn'];
		$submitValue = '32-accepted';
		$explode = explode("-",$submitValue);

		$servername = "localhost";
		$username = "root";
		$password = "root";
		$dbname = 'oasis';

		$conn = new mysqli($servername, $username, $password, $dbname);
		if ($conn->connect_error) {
  			die("Connection failed: " . $conn->connect_error);
		}

		$sql = 'UPDATE appointment SET status = "' . $explode[1] . '" 
				WHERE id=' . $explode[0];
				var_dump($sql);

		if ($conn->query($sql) === TRUE) {
 			 echo "Record updated successfully";
		} else {
  			echo "Error updating record: " . $conn->error;
		}

		// $conn->close();
		$appointmentData = null;

		$sql = "SELECT department, reason, faculty, student_fname, student_lname, student_num 
				FROM appointment  
				INNER JOIN student ON student.id=appointment.student_id
				WHERE appointment.id=" . $explode[0];

				$result = $conn->query($sql);
				
				if ($result->num_rows > 0) {
					while($row = $result->fetch_assoc()) {
						$appointmentData = $row;
				}

				} else {
					echo "no appointments";
				}
				$conn->close();

				echo "<pre>";
				var_dump($appointmentData);
				echo "</pre>";

 

		require dirname(__FILE__) . '/lib/PHPMailer/src/PHPMailer.php';
		// require 'PHPMailer-master/src/PHPMailer.php';
		 require dirname(__FILE__) . '/lib/PHPMailer/src/SMTP.php';

		$mail = new PHPMailer();
		$mail->IsSMTP();
		$mail->Mailer = "smtp";
		// var_dump();

		$mail->SMTPDebug  = 1;  
		$mail->SMTPAuth   = TRUE;
		$mail->SMTPSecure = "tls";
		$mail->Port       = 587;
		$mail->Host       = "smtp.gmail.com";
		$mail->Username   = "oasis.appointment.group.2021@gmail.com";
		$mail->Password   = "Aldrino12";

		$mail->IsHTML(true);
		$mail->AddAddress("garcia.135106@stamaria.sti.edu.ph", "Paula");
		//$mail->AddAddress("nicole.lopez@mailinator.com", "Aldrino");
		$mail->SetFrom("oasis.appointment.group.2021@stamaria.sti.edu.ph", "Oasis");
		// $mail->AddReplyTo("reply-to-email@domain", "reply-to-name");
		// $mail->AddCC("cc-recipient-email@domain", "cc-recipient-name");
		$mail->Subject = "Test is Test Email sent via Gmail SMTP Server using PHP Mailer";
		$content = "
		<div>
			<b>Congratulations, your appointment has been approved. This is a confirmation email of your appointment at STI College Sta. Maria. Please check for your appointment details.</b> 
			<br><h2>Appointment Details:</h2>
			<br><b>Email:</b>
			<br><b>Student ID number:</b>
			<br><b>Chosen Department:</b>
			<br><b>Reason:</b>
			<br><b>Faculty:(if chosen)</b>
		</div>";

		$mail->MsgHTML($content); 
		if(!$mail->Send()) {
  		echo "Error while sending Email.";
  		var_dump($mail);
		} else {
  		echo "Email sent successfully";
		}

		header("Location: dashboard.php");
		exit();
	

		

?>