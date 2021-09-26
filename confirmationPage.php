<?php 
	session_start();
	echo "<pre>";
	var_dump($_SESSION);
	echo "</pre>"; 

	$transaction_type = isset($_SESSION['session_type']) ? $_SESSION['session_type'] : 'student';

?>

<link rel="stylesheet" type="text/css" href="css/confirmationPage.css">

<p>Hi <?php echo $_SESSION['student']['student_fname']?>!</p>

<p>Success! Your appointment has been schedule</p>

<p>Here are the details of your upcoming appointment. <b>"Please review to check if the following information you entered is correct. Be advised that all appointments (unless to cashier and registrar) made through the OASIS website are subject for approval despite confirmation."</b></p>

<div class="main">
	<div class="sub-main">
		<header class="header">Appointment Details</header>
	</div>
	<div class="box">
		<table class="table">
			<tr>
				<th class="data"><div>Name:</div> <div><?php echo $_SESSION['student']['student_fname']?> <?php echo $_SESSION['student']['student_lname']?></div></th>
				<th class="data"><div>Reason:</div> <div><?php echo ucwords($_SESSION['appointment']['reason'])?></div></th>
			</tr>

			<tr>
				<th class="data"><div>Student Number:</div> <div><?php echo $_SESSION['student']['student_num']?></div></th>
				<th class="data"><div>Department:</div> <div><?php echo ucwords($_SESSION['appointment']['department'])?></div></th>
			</tr>

			<tr>
				<th class="data"><div>Email:</div> <div><?php echo $_SESSION['student']['student_email']?></div></th>
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
	<p class="note">*Note: Please check your email for confirmation and QR code</p>
</div>