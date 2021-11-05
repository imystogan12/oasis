<?php  
	session_start();
	$servername = "localhost";
	$username = "root";
	$password = "root";
	$dbname = 'oasis';

	$faculty = [];

	$conn = new mysqli($servername, $username, $password, $dbname);
		if ($conn->connect_error) {
  			die("Connection failed: " . $conn->connect_error);
		}

		$sql = "SELECT f.id, f.dept_id, f.fname, f.lname, f.user_report_id, d.name as department_name, u.fname as user_fname, u.lname as user_lname FROM faculty f " . 
				' JOIN department d ON f.dept_id = d.id' .
				' JOIN user u ON f.user_report_id = u.id' ;
		$result = $conn->query($sql);

		// var_dump($sql);
		// die;

	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
				$faculty[] = $row;
			}
	} else {
		echo "not";
	}
	// echo "<pre>";
	// var_dump($faculty);
	// echo "</pre>";
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
	<a href="addFaculty.php"><button>Add</button></a>
	<table>
		<tr>
			<th>Id</th>
			<th>Department</th>
			<th>First Name</th>
			<th>Last Name</th>
			<th>Program Head</th>
		</tr>
		<?php foreach ($faculty as $faculty): ?>
		<tr>
			<td><?php echo $faculty['id'] ?></td>
			<td><?php echo $faculty['department_name'] ?></td>
			<td><?php echo $faculty['fname'] ?></td>
			<td><?php echo $faculty['lname'] ?></td>
			<td><?php echo $faculty['user_fname'] . ' ' .  $faculty['user_lname']?></td>
			<td><a href="editFaculty.php?id=<?php echo $faculty['id']; ?>">Edit</a>	
		</tr>	
		<?php endforeach ?>
	</table>
</div>