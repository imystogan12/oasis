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

		// Get total count first
	$totalCount = 0;
	$sql = "SELECT COUNT(*) as count FROM department WHERE deleted_at IS NULL";
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

	$sql = "SELECT id, name, value, deleted_at FROM department where deleted_at is null " . 
			"LIMIT " . $perPage . " OFFSET " . $offset ;
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
	<a href="addDepartment.php" class="add-btn btn">Add Department</a>
	<table>
		<tr>
			<th class="galit id">Id</th>
			<th class="galit">Name</th>
			<th class="galit">Value</th>
		</tr>
		<?php foreach ($departments as $dept): ?>
		<tr>
			<td class="details"><?php echo $dept['id'] ?></td>
			<td class="details"><?php echo $dept['name'] ?></td>
			<td class="details"><?php echo $dept['value'] ?></td>
			<td>
				<a class="edit-delete btn" href="editDepartment.php?id=<?php echo $dept['id']; ?>">		Edit</a>
			</td>
			<td>
				<?php if(empty($dept['deleted_at'])): ?>
					<a class="edit-delete btn" href="deleteDepartment.php?id=<?php echo $dept['id'];?>">Delete</a>
				<?php endif; ?>
			</td>
		</tr>	
		<?php endforeach ?>
	</table>
	<div class="page">
		<span>
			<?php if(intval($page) > 1): ?>
			<a class="next back" href="department.php?page=<?php echo $page-1 ?>"> << </a>
			<?php endif; ?>
		</span>
		<span>
			<?php if ($page < $pageCount): ?>
			<a class="next" href="department.php?page=<?php echo $page+1 ?>"> >> </a>
			<?php endif; ?>
		</span>
		<span class="pageCount"> page <?php echo $page?> of <?php echo $pageCount ?></span>
	</div>
</div>
</div>