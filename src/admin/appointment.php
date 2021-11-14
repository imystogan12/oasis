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
	$sql = "SELECT a.id as apt_id, a.status, a.date_time, d.name as department_name," .
			" u.fname as user_fname, u.lname as user_lname, " .
			" s.student_fname, s.student_lname, " .
			" g.guest_fname, g.guest_lname " .
			" FROM appointment a " .
			" JOIN department d ON d.id = a.department_id " .
			"JOIN user u ON u.id = a.user_id " . 
			"LEFT JOIN student s ON s.id = a.student_id " .
			"LEFT JOIN guest g ON g.id = a.guest_id " .
			"WHERE status != 'deleted' " .
			"ORDER BY a.date_time ASC " .
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
<link rel="stylesheet" type="text/css" href="css/appointment.css">
<!-- <style>
	.admin-selected {
		background-color: #F2F2F2;
	}
</style> -->
<div class="header-div">
	<p class="oasis">OASIS</p><!-- <?php include "logout.php";?> -->
</div>
<div class="head2">
	<h2>Admin Dashboard</h2>
</div>
<div class="main">
<div class="left">
	<div class="space"> <a href="report.php">Reports</a> </div>
	<div class="admin-selected space"> <a href="appointment.php">Appointment</a> </div>
	<div class="space"> <a href="user.php">Users</a> </div>
	<div class="space"> <a href="department.php">Departments</a> </div>
	<div class="space"> <a href="reason.php">Reasons</a> </div>
	<div class="space"> <a href="faculty.php">Faculty</a> </div>
	
</div>
<div class="right">
	
	<div class="table-container">
	<table>
		<tr>
			<th class="galit id">Id</th>
			<th class="galit">Full Name</th>
			<th class="galit type">Type</th>
			<th class="galit">Department</th>
			<th class="galit">Assignee</th>
			<div class="sched-div">
				<th class="galit sched">Schedule</th>
			</div>
			
			<th class="galit">Status</th>
		</tr>
		<?php foreach ($appointments as $apt): ?>
		<?php $transaction_type = !empty($apt['student_fname']) ? 'student' : 'guest'; ?>
		<tr>
			<td class="details"><?php echo $apt['apt_id'] ?></td>
			<td class="details"><?php echo $apt["{$transaction_type}_fname"] . " " . $apt["{$transaction_type}_lname"] ?></td>
			<td class="trans-type details"><?php echo ucwords($transaction_type) ?></td>
			<td class="details"><?php echo $apt['department_name'] ?></td>
			<td class="details"><?php echo $apt['user_fname'] . " " . $apt['user_lname'] ?></td>
			<td class="details"> <?php echo (DateTime::createFromFormat('Y-m-d H:i:s', $apt['date_time']))->format('M. d, Y h:i A') ?> </td>
			<td class="details"><?php echo $apt['status'] ?></td>
			<td class="details">
				<?php if($apt['status'] !== "deleted"): ?>
				<a class="delete-btn" href="deleteAppointment.php?id=<?php echo $apt['apt_id']; ?>">Delete</a>
			<?php endif; ?>
			</td>
		</tr>	
		<?php endforeach ?>
	</table>
</div>
<div class="page">
		<span>
			<?php if(intval($page) > 1): ?>
			<a class="next back" href="appointment.php?page=<?php echo $page-1 ?>"> << </a>
			<?php endif; ?>
		</span>
		<span>
			<?php if ($page < $pageCount): ?>
			<a class="next" href="appointment.php?page=<?php echo $page+1 ?>"> >> </a>
			<?php endif; ?>
		</span>
		<span class="pageCount"> page <?php echo $page?> of <?php echo $pageCount ?></span>
	</div>
</div>
	
</div>