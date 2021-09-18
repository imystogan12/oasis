<?php
	session_start();
	echo "<pre>";
	var_dump($_SESSION);
	echo "</pre>";

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
		$sql = 'SELECT apt.user_id, apt.reason, apt.date_time, s.student_lname, s.student_fname, s.student_email' .
				' FROM appointment apt' .
				' LEFT JOIN student s ON apt.student_id = s.id ' .
				' WHERE apt.user_id="' . $_SESSION['user']['id']. '"';
		$result = $conn->query($sql);

		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				$appointment[] = $row;
			}
			echo "<pre>";
			// var_dump($appointment);
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
	<table class="details info">
		<tr>
			<th>Last Name</th>
			<th>First Name</th>
			<th>Email</th>
			<th>Reason</th>
			<th>Date & Time</th>
			<th>Action</th>
		</tr>
		<?php foreach($appointment as $apt): ?> 
		<tr>
			<td> <?php echo $apt['student_lname'] ?>
			<td> <?php echo $apt['student_fname'] ?>
			<td> <?php echo $apt['student_email'] ?>
			<td> <?php echo $apt['reason'] ?>
			<td> <?php echo $apt['date_time'] ?>
			<td>
				<div class="button-div">
					<button class="button">Accept</button>
					<button class="button">Decline</button>
					<button class="button">View</button>
				</div>
			<td>
		<tr>
		<?php endforeach; ?>
	</table>

	
</div>