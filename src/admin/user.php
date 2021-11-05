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
</div>
<div>
	<a href="addUser.php"><button>Add</button></a>
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