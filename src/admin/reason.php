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

	$sql = "SELECT id, dept_id, name, value FROM reason";
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
	<a href="addReason.php"><button>Add</button></a>
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
		</tr>	
		<?php endforeach ?>
	</table>
</div>