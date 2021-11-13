<?php 
	session_start();
	$servername = "localhost";
	$username = "root";
	$password = "root";
	$dbname = 'oasis';

	$departments = [];

	$conn = new mysqli($servername, $username, $password, $dbname);
		if ($conn->connect_error) {
  			die("Connection failed: " . $conn->connect_error);
		}

	$sql = "SELECT id, name, value, deleted_at FROM department where deleted_at is null";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
				$departments[] = $row;
			}
	} else {
		echo "not";
	}


?>
<link rel="stylesheet" type="text/css" href="css/department.css">
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
	<div class="admin-selected space"> <a href="department.php">Departments</a> </div>
	<div class="space"> <a href="reason.php">Reasons</a> </div>
	<div class="space"> <a href="faculty.php">Faculty</a> </div>	
</div>
<div class="right">
	<a href="addDepartment.php" class="add-btn"><button>Add</button></a>
	<table>
		<tr>
			<th>Id</th>
			<th>Name</th>
			<th>Value</th>
		</tr>
		<?php foreach ($departments as $dept): ?>
		<tr>
			<td><?php echo $dept['id'] ?></td>
			<td><?php echo $dept['name'] ?></td>
			<td><?php echo $dept['value'] ?></td>
			<td><a href="editDepartment.php?id=<?php echo $dept['id']; ?>">Edit</a>
			<td>
				<?php if(empty($dept['deleted_at'])): ?>
					<a href="deleteDepartment.php?id=<?php echo $dept['id'];?>">Delete</a>
				<?php endif; ?>
			</td>
		</tr>	
		<?php endforeach ?>
	</table>
</div>
</div>