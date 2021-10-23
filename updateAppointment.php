<?php 
	session_start();
	if (isset($_POST['btn'])) {
		echo "<pre>";
		var_dump($_POST);
		echo "</pre>";

		$submitValue = $_POST['btn'];
	// 	$submitValue = '78-accepted';
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

		$sql = "SELECT department, reason, faculty, student_fname, student_lname, student_num, 				student_email 
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

				$transaction_type = !empty($appointmentData['student_fname']) ? 'student' : 'guest';

			require dirname(__FILE__) . '/lib/qrcode/qrlib.php';

				$text = <div>"Full Name: ". $appointmentData['student_fname'] . " " .
					$appointmentData['student_lname'] . ""</div>
				$file3 = "public/qrcode/qr-" . $explode[0] . ".png";
  
$ecc = 'H';
$pixel_size = 10;
$frame_size = 5;
  
// Generates QR Code and Save as PNG
QRcode::png($text, $file3, $ecc, $pixel_size, $frame_size);
  
// Displaying the stored QR code if you want
  echo "<div><img src='".$file3."'></div>";

//   // Upload to AWS S3

//   require 'vendor/autoload.php';

// use Aws\S3\S3Client;
// use Aws\S3\Exception\S3Exception;

// $bucket = 'oasis-appointment-group';
// $keyname = "qrcode/qr-" . $explode[0] . ".png";
                        
// $s3 = new S3Client([
//     'version' => 'latest',
//     'region'  => 'ap-southeast-1',
//     'credentials' => [
//     	'key' => 'enter AWS key here',
//     	'secret' => 'enter AWS secret here',
//     ]
// ]);

$s3QrcodeURL = 'https://oasis-appointment-group.s3.ap-southeast-1.amazonaws.com/qrcode/qr-32.png';

// try {
//     // Upload data.
//     $result = $s3->putObject([
//         'Bucket' => $bucket,
//         'Key'    => $keyname,
//         'SourceFile'   => $file3,
//         'ContentType' => "image/png",
//         'ACL'    => 'public-read'
//     ]);

//     $s3QrcodeURL = $result['ObjectURL'];
// } catch (S3Exception $e) {
//     echo $e->getMessage() . PHP_EOL;
// }
 

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
		$mail->AddAddress($appointmentData['student_email'], $appointmentData['student_fname'] . " " .	$appointmentData['student_lname']);
		//$mail->AddAddress("nicole.lopez@mailinator.com", "Aldrino");
		$mail->SetFrom("oasis.appointment.group.2021@mailinator.com", "Oasis");
		// $mail->AddReplyTo("reply-to-email@domain", "reply-to-name");
		// $mail->AddCC("cc-recipient-email@domain", "cc-recipient-name");
		$mail->Subject = "STI College Sta. Maria Appointment Notice";
		$content = "
		<div>
			
			<div>
				<b>Congratulations, your appointment has been approved. This is a confirmation email of your appointment at STI College Sta. Maria. Please check for your appointment details.</b> 
			</div>
			<div>
				<br><h2>Appointment Details:</h2>
			</div>
			<div>
				<br><b>Your name:</b> " . $appointmentData['student_fname'] . " " .
					$appointmentData['student_lname'] . "
			</div>
			<div>
				<br><b>Email:</b> " . $appointmentData['student_email'] . "
			</div>
			<div>
				<br><b>" . ($transaction_type === "guest" ? "Contact" : "Student") . " Number:</b> " . ($transaction_type === "guest" ? $appointmentData['guest_number'] : $appointmentData['student_num']) . "
			</div>
			<div>
				<br><b>Chosen Department:</b> " . ucwords($appointmentData['department']) ."
			</div>
			<div>
				<br><b>Reason:</b> " . ucwords($appointmentData['reason']) ."
			</div>
			<div>
				<br><b>Faculty:</b> " . ucwords($appointmentData['faculty']) ."
			</div>
			<div>
				<img src='" . $s3QrcodeURL. "'/>
			</div>
		</div>";

		$mail->MsgHTML($content); 
		if(!$mail->Send()) {
  		echo "Error while sending Email.";
  		var_dump($mail);
		} else {
  		echo "Email sent successfully";
		}


		$URL="http://localhost/oasis/dashboard.php";
		echo "<script type='text/javascript'>document.location.href='{$URL}';</script>";
		echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $URL . '">';
		exit();
	}
?>