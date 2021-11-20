<?php 
	session_start();
	// echo "<pre>";
	// var_dump($_SESSION);
	// echo "</pre>"; 

	$transaction_type = isset($_SESSION['session_type']) ? $_SESSION['session_type'] : 'student';

	$servername = "localhost";
	$username = "root";
	$password = "root";
	$dbname = 'oasis';

	$conn = new mysqli($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	// Get department name
	$departmentName = "N/A";
	$sql = 'SELECT * from department WHERE id=' . $_SESSION['appointment']['department'];
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$departmentName = $row['name'];
		}
	} 

	// Get reason name
	$reasonName = "N/A";
	$sql = 'SELECT * from reason WHERE id=' . $_SESSION['appointment']['reason'];
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$reasonName = $row['name'];
		}
	} 
?>

<link rel="stylesheet" type="text/css" href="css(1)-backup/confirmationPage.css">
<div class="header-div">
	<img src="https://i.imgur.com/FTPJl6s.png" style="height:75px;">
</div>
<div class="container">
	<p class="letter">Hi <?php echo $_SESSION[$transaction_type][$transaction_type . '_fname']?>!</p>

	<p class="letter">Congratulations on submitting your appointment through OASIS. Please check the following details to ensure that all of the information displayed is correct prior to proceeding. Be advised that all appointments made through OASIS unless to the cashier, registrar, and admissions are subject to approval. You will be receiving an email once your appointment is confirmed.</b></p>
</div>

<div class="main">
	<div class="sub-main">
		<header class="header">Appointment Details</header>
	</div>
	<div class="box">
		<table class="table">
			<tr>
				<th class="data"><div>Name:</div> <div><?php echo $_SESSION[$transaction_type][$transaction_type . '_fname']?> <?php echo $_SESSION[$transaction_type][$transaction_type . '_lname']?>
					
				</div>
				</th>
				<th class="data"><div>Reason:</div> <div><?php echo $reasonName ?></div>
				</th>
			</tr>

			<tr>
				<th class="data"><div><?php echo ($transaction_type === "guest" ? "Contact" : "Student")?> Number:</div> <div><?php echo ($transaction_type === "guest" ? $_SESSION['guest']['guest_number'] : $_SESSION['student']['student_num'])?></div>
				</th>
				<th class="data"><div>Department:</div> <div><?php echo $departmentName ?></div>
				</th>
			</tr>

			<tr>
				<th class="data"><div>Email:</div> <div><?php echo ($transaction_type === "guest" ? $_SESSION['guest']['guest_email'] : $_SESSION['student']['student_email'])?></div>
				</th>
			</tr>
		</table>
	</div>
</div>
<div class="button-div">
	<a href="terms-agreement.php"><button class="button">Confirm your Appointment</button></a>
	<a href="<?php echo $transaction_type  ?>UI.php">
		<button class="button">Edit Appointment</button>
	</a>
</div>
<div class="note-div">
	<p class="note"><b>*NOTE: Please check your email for confirmation and QR code</b></p>
</div>