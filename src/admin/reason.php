<?php  
	session_start();

	$servername = "localhost";
	$username = "root";
	$password = "root";
	$dbname = 'oasis';

	$reasons = [];

	$conn = new mysqli($servername, $username, $password, $dbname);
		if ($conn->connect_error) {
  			die("Connection failed: " . $conn->connect_error);
		}

	$sql = "SELECT id, name, value, deleted_at FROM reason where deleted_at is null";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
				$reasons[] = $row;
			}
	} else {
		echo "not";
	}
	// var_dump($reasons);

?>
<link rel="stylesheet" type="text/css" href="css/reason.css">
<div class="header-div">
	<p class="oasis">OASIS</p><!-- <?php include "logout.php";?> -->
</div>
<div class="head2">
	<h2>Admin Dashboard</h2>
</div>
<div class="main">
<div class="left">
	<div class="space"> <a href="report.php">Reports</a> </div>
	<div class="space"> <a href="appointment.php">Appointments</a> </div>
	<div class="space"> <a href="user.php">Users</a> </div>	
	<div class="space"> <a href="department.php">Departments</a> </div>
	<div class="admin-selected space"> <a href="reason.php">Reasons</a> </div>
	<div class="space"> <a href="faculty.php">Faculty</a> </div>
</div>
<div class="right">
	<a href="addReason.php" class="add-btn"><button>Add</button></a>
	<table>
		<tr>
			<th>Id</th>
			<th>Name</th>
			<th>Value</th>
		</tr>
		<?php foreach ($reasons as $reason): ?>
		<tr>
			<td><?php echo $reason['id'] ?></td>
			<td><?php echo $reason['name'] ?></td>
			<td><?php echo $reason['value'] ?></td>	
			<td>
				<a href="editReason.php?id=<?php echo $reason['id'];?>">Edit</a>
				<?php if(empty($reason['deleted_at'])): ?>
					<a href="deleteReason.php?id=<?php echo $reason['id'];?>">Delete</a>
				<?php endif; ?>
			</td>
		</tr>	
		<?php endforeach ?>
	</table>
</div>
</div>