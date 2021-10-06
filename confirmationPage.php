<?php 
	session_start();
	// echo "<pre>";
	// var_dump($_SESSION);
	// echo "</pre>"; 

	$transaction_type = isset($_SESSION['session_type']) ? $_SESSION['session_type'] : 'student';

?>

<link rel="stylesheet" type="text/css" href="css/confirmationPage.css">
<div class="header-div">
	<p class="oasis">OASIS</p>
</div>
<div class="container">
	<p class="letter">Hi <?php echo $_SESSION[$transaction_type][$transaction_type . '_fname']?>!</p>

	<p class="letter">Success! Your appointment has been schedule</p>

	<p class="letter">Here are the details of your upcoming appointment. <b>Please review to check if the following information you entered is correct. Be advised that all appointments (unless to cashier and registrar) made through the OASIS website are subject for approval despite confirmation.</b></p>
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
				<th class="data"><div>Reason:</div> <div><?php echo ucwords($_SESSION['appointment']['reason'])?></div>
				</th>
			</tr>

			<tr>
				<th class="data"><div><?php echo ($transaction_type === "guest" ? "Contact" : "Student")?> Number:</div> <div><?php echo ($transaction_type === "guest" ? $_SESSION['guest']['guest_number'] : $_SESSION['student']['student_num'])?></div>
				</th>
				<th class="data"><div>Department:</div> <div><?php echo ucwords($_SESSION['appointment']['department'])?></div>
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