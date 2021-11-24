<?php
	include('database.php');
	include('config.php');
	session_start();
 // 	$servername = "localhost";
	// $username = "root";
	// $password = "root";
	// $dbname = 'oasis';

	// $conn = new mysqli($servername, $username, $password, $dbname);

		$apt_id = $_GET['apt_id'];

		require dirname(__FILE__) . '/lib/PHPMailer/src/PHPMailer.php';
		// require 'PHPMailer-master/src/PHPMailer.php';
		 require dirname(__FILE__) . '/lib/PHPMailer/src/SMTP.php';

		 // $conn->close();
		$appointmentData = null;
		// Query to get transaction type
		$sql = "SELECT student_id
				FROM appointment  
				WHERE appointment.id=" . $apt_id;

		$transaction_type = 'student';
		$result = $conn->query($sql);		
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				// var_dump($row);die;
				$transaction_type = !empty($row['student_id']) ? 'student' : 'guest';
				break;
			}
		}

		 $sql  = '';
		if ($transaction_type === 'student') {
			$sql = "SELECT department.name as department, reason.name as reason, CONCAT(faculty.fname , ' ', faculty.lname) as faculty, date_time, student_fname, student.id as student_id, student_lname, student_num, student_email, student_course, student_section
				FROM appointment
				INNER JOIN department ON department.id=appointment.department_id
				INNER JOIN reason ON reason.id=appointment.reason_id
				INNER JOIN student ON student.id=appointment.student_id
				LEFT JOIN faculty ON faculty.id=appointment.faculty_id
				WHERE appointment.id=" . $apt_id;
			} else {
				$sql = "SELECT department.name as department, reason.name as reason, CONCAT(faculty.fname , ' ', faculty.lname) as faculty, date_time, guest_fname, guest.id as guest_id, guest_lname, guest_address, guest_number, guest_email
				FROM appointment  
				INNER JOIN guest ON guest.id=appointment.guest_id
				INNER JOIN department ON department.id=appointment.department_id
				INNER JOIN reason ON reason.id=appointment.reason_id
				LEFT JOIN faculty ON faculty.id=appointment.faculty_id
				WHERE appointment.id=" . $apt_id;
			}

		

				$result = $conn->query($sql);
				if ($result->num_rows > 0) {
					while($row = $result->fetch_assoc()) {
						$appointmentData = $row;

						// Get companions
						if ($transaction_type === 'student') {
							$sql = "SELECT sCompanion_fname, sCompanion_lname
							FROM student_companion
							WHERE student_id=" . $row['student_id'];
							$resultCompanions = $conn->query($sql);
							if ($resultCompanions->num_rows > 0) {
								while($rowCompanions = $resultCompanions->fetch_assoc()) {
									$appointmentData['companions'][] = $rowCompanions;
								}
							}
						} else {
							$sql = "SELECT gCompanion_fname, gCompanion_lname
							FROM guest_companion
							WHERE guest_id=" . $row['guest_id'];
							$resultCompanions = $conn->query($sql);
							if ($resultCompanions->num_rows > 0) {
								while($rowCompanions = $resultCompanions->fetch_assoc()) {
									$appointmentData['companions'][] = $rowCompanions;
								}
							}
						}
						
					}
				} else {
					echo "no appointments";
				}
				$conn->close();

				// echo "<pre>";
				// var_dump($appointmentData);
				// echo "</pre>";


			$text = '';
			$s3QrcodeURL = '';
			require dirname(__FILE__) . '/lib/qrcode/qrlib.php';

			if 	($transaction_type === "student") {
				$text = "Appointment ID: [[". $apt_id . "]] </br>" .
					"Full Name: ". $appointmentData['student_fname'] . " " .
					$appointmentData['student_lname'] . "</br>" .
					"Student Number: " . $appointmentData['student_num'] . "</br>" .
					"Chosen Department: " . ucwords($appointmentData['department']) ."</br>" .
					"Reason: " . ucwords($appointmentData['reason']) . "</br>" .
					// "Date & Time: " . (DateTime::createFromFormat('Y-m-d H:i:s', $appointmentData['date_time']))->format('M. d, Y h:i A') . "</br>" .
					"Section: " . $appointmentData['student_section'] . "</br>" .
					"Course: " . $appointmentData['student_course'] . "</br>" .				
					"Companion: " ;

					if (!empty($appointmentData['companions'])) {
						foreach ($appointmentData['companions'] as $companion) {
							$text .= $companion['sCompanion_fname'] . " " . $companion['sCompanion_lname'] . ", ";
						}
					}
				} else {
					$text =  "Appointment ID: [[".  $apt_id . "]] </br>" .
					"Full Name: ". $appointmentData['guest_fname'] . " " .
					$appointmentData['guest_lname'] . "</br>" .
					"Contact Number: " . $appointmentData['guest_number']. "</br>" .
					"Chosen Department: " . ucwords($appointmentData['department']) ."</br>" .
					"Reason: " . ucwords($appointmentData['reason']) . "</br>" .
					// "Date & Time: " . (DateTime::createFromFormat('Y-m-d H:i:s', $appointmentData['date_time']))->format('M. d, Y h:i A') . "</br>" .
					"Address: " . $appointmentData['guest_address']. "</br>" .
					"Companion:" ;

					if (!empty($appointmentData['companions'])) {
						foreach ($appointmentData['companions'] as $companion) {
							$text .= $companion['gCompanion_fname'] . " " . $companion['gCompanion_lname'] . ", ";
					}
				}
				}

				
				// $text =	" hello\n\n\nbye";
				$file3 = "public/qrcode/qr-" . $apt_id . ".png";
				$ecc = 'H';
				$pixel_size = 4;
				$frame_size = 1;
  
				// Generates QR Code and Save as PNG
				QRcode::png($text, $file3, $ecc, $pixel_size, $frame_size);
				  
				// Displaying the stored QR code if you want
				  echo "<div><img src='".$file3."'></div>";

				  // Upload to AWS S3

				  require 'vendor/autoload.php';

				use Aws\S3\S3Client;
				use Aws\S3\Exception\S3Exception;

				$bucket = 'oasis-appointment-group';
				$keyname = "qrcode/qr-" . $apt_id . ".png";
				                        
				$s3 = new S3Client([
				    'version' => 'latest',
				    'region'  => 'ap-southeast-1',
				    'credentials' => [
				    	'key' => $awsKey,
				    	'secret' => $awsSecret,
				    ]
				]);

				// $s3QrcodeURL = 'https://oasis-appointment-group.s3.ap-southeast-1.amazonaws.com/qrcode/qr-32.png';

				try {
				    // Upload data.
				    $result = $s3->putObject([
				        'Bucket' => $bucket,
				        'Key'    => $keyname,
				        'SourceFile'   => $file3,
				        'ContentType' => "image/png",
				        'ACL'    => 'public-read'
				    ]);

				    $s3QrcodeURL = $result['ObjectURL'];
				} catch (S3Exception $e) {
				    echo $e->getMessage() . PHP_EOL;
				}
 



		$mail = new PHPMailer();
		$mail->IsSMTP();
		$mail->Mailer = "smtp";
		// var_dump();

		$mail->SMTPDebug  = 1;  
		$mail->SMTPAuth   = TRUE;
		$mail->SMTPSecure = "tls";
		$mail->Port       = 587;
		$mail->Host       = "smtp.gmail.com";
		$mail->Username   = $oasisEmail;
		$mail->Password   = $oasisEmailPassword;

		$receiverEmail = '';
		$receiverName = '';

		if ($transaction_type === 'student') {
			$receiverEmail = $appointmentData['student_email'];
			$receiverName = $appointmentData['student_fname'] . " " .	$appointmentData['student_lname'];
		} else {
			$receiverEmail = $appointmentData['guest_email'];
			$receiverName = $appointmentData['guest_fname'] . " " .	$appointmentData['guest_lname'];
		}

		$mail->IsHTML(true);
		$mail->AddAddress($receiverEmail, $receiverName);
		//$mail->AddAddress("nicole.lopez@mailinator.com", "Aldrino");
		$mail->SetFrom("oasis.appointment.group.2021@mailinator.com", "Oasis");
		// $mail->AddReplyTo("reply-to-email@domain", "reply-to-name");
		// $mail->AddCC("cc-recipient-email@domain", "cc-recipient-name");
		$mail->Subject = "STI College Sta. Maria Appointment Notice";

		// $acceptedMessage = "<b>Congratulations, your appointment has been approved. This is a confirmation email of your appointment at STI College Sta. Maria. Please check for your appointment details.</b>"

		$acceptedMessage = "<div><b>Congratulations, your appointment has been approved. This is a confirmation email of your appointment at STI College Sta. Maria. Please check for your appointment details.</b></div>
			<div>
				<br><h2>Appointment Details:</h2>
			</div>
			<table>
			<tr>
				<td><br><b>Your name:</b>
				" . $appointmentData["{$transaction_type}_fname"] . " " .$appointmentData["{$transaction_type}_lname"] . "
				</td>
			</tr>
			<tr>
				<td><br><b>Email:</b> 
				" . ($transaction_type === "guest" ? $appointmentData['guest_email'] : $appointmentData['student_email']) . "
				</td>
			</tr>
			<tr>
				<td><br><b>" . ($transaction_type === "guest" ? "Contact" : "Student") . " Number:</b>
				" . ($transaction_type === "guest" ? $appointmentData['guest_number'] : $appointmentData['student_num']) . "
				</td>
			</tr>
			<tr>	
				<td><br><b>Chosen Department:</b>
				" . ucwords($appointmentData['department']) ."
				</td>
			</tr>
			<tr>
				<td><br><b>Reason:</b> 
				" . ucwords($appointmentData['reason']) ."
				</td>
			</tr>
			<tr>
				<td><br><b>Date & Time:</b> 
				";

			$aptDateTime = DateTime::createFromFormat('Y-m-d H:i:s', $appointmentData['date_time']);

			$acceptedMessage .= $aptDateTime->format('M. d, Y h:i A') ."
				</td> 
			</tr>		
			</table>
			<div>
				<img src='" . $s3QrcodeURL. "'/>
			</div>
			<br><br><br><br><br><br><br>
			<b>***This email is auto generated, please do not reply.";

		// header("Location: homepage.php");
	 // 	exit();		

		$mail->MsgHTML($acceptedMessage); 
		if(!$mail->Send()) {
  		echo "Error while sending Email.";
  		// var_dump($mail);
		} else {
  		echo "Email sent successfully";
		}


		$URL= $siteUrl . "/oasis/homepage.php";
		echo "<script type='text/javascript'>document.location.href='{$URL}';</script>";
		echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $URL . '">';
		exit();
		
?>