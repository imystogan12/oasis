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

	$sql = "SELECT id, name, value FROM department";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
				$departments[] = $row;
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
	<a href="addDepartment.php"><button>Add</button></a>
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
		</tr>	
		<?php endforeach ?>
	</table>
</div>