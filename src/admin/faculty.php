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

		$sql = "SELECT f.id, f.fname, f.lname, d.name as department_name, u.fname as user_fname, u.lname as user_lname, f.deleted_at FROM faculty f " . 
				' JOIN department d ON f.dept_id = d.id' .
				' JOIN user u ON f.user_report_id = u.id' .
				' WHERE f.deleted_at is null' ;
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
<link rel="stylesheet" type="text/css" href="css/faculty.css">
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
	<div class="space"> <a href="reason.php">Reasons</a> </div>
	<div class="admin-selected space"> <a href="faculty.php">Faculty</a> </div>
</div>
<div class="right">
	<a href="addFaculty.php" class="add-btn"><button>Add</button></a>
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
			<td>
				<?php if(empty($faculty['deleted_at'])): ?>
					<a href="deleteFaculty.php?id=<?php echo $faculty['id'];?>">Delete</a>
				<?php endif; ?>
			</td>
		</tr>	
		<?php endforeach ?>
	</table>
</div>
</div>