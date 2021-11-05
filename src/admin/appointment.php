<?php 
	session_start();
	$servername = "localhost";
	$username = "root";
	$password = "root";
	$dbname = 'oasis';

	$appointments = [];

	$conn = new mysqli($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	// Get total count first
	$totalCount = 0;
	$sql = "SELECT COUNT(*) as count FROM appointment WHERE status != 'deleted'";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
				$totalCount = $row['count'];
			}
	}
	$perPage = 10;
	$pageCount = ceil($totalCount / $perPage);
	
	$page = 1;
	if (!empty($_GET['page'])) {
		$page = $_GET['page'];
	}

	$offset = ($page - 1) * $perPage;
	$sql = "SELECT a.id as apt_id, a.department, a.reason, a.status, a.date_time, " .
			" u.fname as user_fname, u.lname as user_lname, " .
			" s.student_fname, s.student_lname, " .
			" g.guest_fname, g.guest_lname " .
			" FROM appointment a " .
			"JOIN user u ON u.id = a.user_id " . 
			"LEFT JOIN student s ON s.id = a.student_id " .
			"LEFT JOIN guest g ON g.id = a.guest_id " .
			"WHERE status != 'deleted' " .
			"ORDER BY apt_id DESC " .
			"LIMIT " . $perPage . " OFFSET " . $offset;
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
				$appointments[] = $row;
			}
	} else {
		echo "not";
	}


?>
<div>
	<header>OASIS</header>
</div>
<div>
	<h2>Admin Dashboard</h2>
</div>
<div>
	<div> <a href="user.php">Users</a> </div>
	<div> <a href="department.php">Departments</a> </div>
	<div> <a href="reason.php">Reasons</a> </div>
	<div> <a href="faculty.php">Faculty</a> </div>
	<div> <a href="appointment.php">Appointment</a> </div>
</div>
<div>
	<table>
		<tr>
			<th>Id</th>
			<th>Department</th>
			<th>Assignee</th>
			<th>Type</th>
			<th>Owner</th>
			<th>Schedule</th>
			<th>Status</th>
		</tr>
		<?php foreach ($appointments as $apt): ?>
		<?php $transaction_type = !empty($apt['student_fname']) ? 'student' : 'guest'; ?>
		<tr>
			<td><?php echo $apt['apt_id'] ?></td>
			<td><?php echo $apt['department'] ?></td>
			<td><?php echo $apt['user_fname'] . " " . $apt['user_lname'] ?></td>
			<td><?php echo ucwords($transaction_type) ?></td>
			<td><?php echo $apt["{$transaction_type}_fname"] . " " . $apt["{$transaction_type}_lname"] ?></td>
			<td><?php
				$datetime = new DateTime($apt['date_time']);
				echo $datetime->format('m-d-Y h:i:s A');

			?></td>
			<td><?php echo $apt['status'] ?></td>
			<td>
				<?php if($apt['status'] !== "deleted"): ?>
				<a href="deleteAppointment.php?id=<?php echo $apt['apt_id']; ?>">Delete</a>
			<?php endif; ?>
			</td>
		</tr>	
		<?php endforeach ?>
	</table>
	<div>
		<span>
			<?php if(intval($page) > 1): ?>
			<a href="appointment.php?page=<?php echo $page-1 ?>"> << </a>
			<?php endif; ?>
		</span>
		<span>
			<?php if ($page < $pageCount): ?>
			<a href="appointment.php?page=<?php echo $page+1 ?>"> >> </a>
			<?php endif; ?>
		</span>
		<span> page <?php echo $page?> of <?php echo $pageCount ?></span>
	</div>
</div>