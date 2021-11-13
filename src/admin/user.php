<?php  
	session_start();
	$servername = "localhost";
	$username = "root";
	$password = "root";
	$dbname = 'oasis';

	$users = [];

	$conn = new mysqli($servername, $username, $password, $dbname);
		if ($conn->connect_error) {
  			die("Connection failed: " . $conn->connect_error);
		}

	$sql = "SELECT id, fname, lname, username, role FROM user";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
				$users[] = $row;
			}
	} else {
		echo "not";
	}

?>
<link rel="stylesheet" type="text/css" href="css/user.css">
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
	<div class="admin-selected space"> <a href="user.php">Users</a> </div>
	<div class="space"> <a href="department.php">Departments</a> </div>
	<div class="space"> <a href="reason.php">Reasons</a> </div>
	<div class="space"> <a href="faculty.php">Faculty</a> </div>	

</div>
<div class="right">
	<a href="addUser.php" class="add-btn"><button>Add</button></a>
	<table>
		<tr>
		<th>ID</th>
		<th>Username</th>
		<th>First Name</th>
		<th>Last Name</th>		
		<th>Role</th>
	</tr>
	<?php foreach ($users as $user): ?>
		<tr>
			<td><?php echo $user['id'] ?></td>
			<td><?php echo $user['username'] ?></td>
			<td><?php echo $user['fname'] ?></td>
			<td><?php echo $user['lname'] ?></td>
			<td><?php echo $user['role'] ?></td>
		</tr>	
		<?php endforeach ?>
	</table>
	
</div>
</div>