<?php
	session_start();
	// echo "<pre>";
	// var_dump($_SESSION);
	// echo "</pre>";

	$appointment = [];

		$servername = "localhost";
		$username = "root";
		$password = "root";
		$dbname = 'oasis';

		$conn = new mysqli($servername, $username, $password, $dbname);
		if ($conn->connect_error) {
  			die("Connection failed: " . $conn->connect_error);
		}
		echo "Connected successfully";
		$sql = 'SELECT apt.user_id, apt.reason, apt.date_time, s.student_lname, s.student_fname, s.student_email, apt.faculty, apt.status, apt.id as appointment_id' .
				' FROM appointment apt' .
				' LEFT JOIN student s ON apt.student_id = s.id ' .
				' WHERE apt.user_id="' . $_SESSION['user']['id']. '"' ;
		$result = $conn->query($sql);

		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				$appointment[] = $row;
			}
			echo "<pre>";
			// S
			echo "</pre>";
		} else {
			echo "no appointments";
		}
 
 ?>



<link rel="stylesheet" type="text/css" href="css/dashboard.css">
<?php 
	include "logout.php";
?>
<div class="head1">
	<header>Appointment Details</header>
</div>

<div class="head2">
	<h2><?php echo ucwords($_SESSION['user']['role']) ?> Dashboard</h2>
</div>

<div class="div1">
	<form action="updateAppointment.php" method="POST">
	<table class="details info">
		<tr>
			<th>Last Name</th>
			<th>First Name</th>
			<th>Email</th>
			<th>Faculty</th>
			<th>Reason</th>
			<th>Date & Time</th>
			<th>Status</th>
			<th>Action</th>
		</tr>
		<?php foreach($appointment as $apt): ?> 
		<tr>
			<td> <?php echo $apt['student_lname'] ?>
			<td> <?php echo $apt['student_fname'] ?>
			<td> <?php echo $apt['student_email'] ?>
			<td> <?php echo $apt['faculty'] ?>
			<td> <?php echo $apt['reason'] ?>
			<td> <?php echo $apt['date_time'] ?>
			<td> <?php echo ucwords($apt['status']) ?>

			<td>
				<div class="button-div">
					<?php if ($apt['status'] == "pending"): ?>
						<button value="<?php echo($apt['appointment_id'])?>-accepted" name="btn" class="button">Accept</button>
						<button value="<?php echo($apt['appointment_id'])?>-declined" name="btn" class="button">Decline</button>
					<?php endif ?>
						<button class="button">View</button>
				</div>
			<td>
		<tr>
		<?php endforeach; ?>
	</table>
	</form>
	
</div>