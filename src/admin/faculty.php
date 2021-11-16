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

	$totalCount = 0;
	$sql = "SELECT COUNT(*) as count FROM faculty WHERE deleted_at IS NULL";
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

		$sql = "SELECT f.id, f.fname, f.lname, d.name as department_name, u.fname as user_fname, u.lname as user_lname, f.deleted_at FROM faculty f " . 
				' JOIN department d ON f.dept_id = d.id' .
				' JOIN user u ON f.user_report_id = u.id' .
				' WHERE f.deleted_at is null' . 
				" LIMIT " . $perPage . " OFFSET " . $offset ;
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
	<a href="addFaculty.php" class="add-btn btn">Add Faculty</a>
	<table>
		<tr>
			<th class="galit id">Id</th>
			<th class="galit">Department</th>
			<th class="galit">First Name</th>
			<th class="galit">Last Name</th>
			<th class="galit">Program Head</th>
		</tr>
		<?php foreach ($faculty as $faculty): ?>
		<tr>
			<td class="details"><?php echo $faculty['id'] ?></td>
			<td class="details"><?php echo $faculty['department_name'] ?></td>
			<td class="details"><?php echo $faculty['fname'] ?></td>
			<td class="details"><?php echo $faculty['lname'] ?></td>
			<td class="details"><?php echo $faculty['user_fname'] . ' ' .  $faculty['user_lname']?>
			</td>
			<td class="td-edit"><a class="btn" href="editFaculty.php?id=<?php echo $faculty['id']; ?>">		Edit</a>	
			</td>
			<td>
				<?php if(empty($faculty['deleted_at'])): ?>
					<a class="edit-delete btn" href="deleteFaculty.php?id=<?php echo $faculty['id'];?>">Delete</a>
				<?php endif; ?>
			</td>
		</tr>	
		<?php endforeach ?>
	</table>
	<div class="page">
		<span>
			<?php if(intval($page) > 1): ?>
			<a class="next back" href="faculty.php?page=<?php echo $page-1 ?>"> << </a>
			<?php endif; ?>
		</span>
		<span>
			<?php if ($page < $pageCount): ?>
			<a class="next" href="faculty.php?page=<?php echo $page+1 ?>"> >> </a>
			<?php endif; ?>
		</span>
		<span class="pageCount"> page <?php echo $page?> of <?php echo $pageCount ?></span>
	</div>
</div>
</div>