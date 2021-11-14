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

	$totalCount = 0;
	$sql = "SELECT COUNT(*) as count FROM reason WHERE deleted_at IS NULL";
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

	$sql = "SELECT id, name, value, deleted_at FROM reason where deleted_at is null " . 
			"LIMIT " . $perPage . " OFFSET " . $offset ;
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
	<a href="addReason.php" class="add-btn btn">Add Reason</a>
	<table>
		<tr>
			<th class="galit id">Id</th>
			<th class="galit">Name</th>
			<th class="galit">Value</th>
		</tr>
		<?php foreach ($reasons as $reason): ?>
		<tr>
			<td class="details"><?php echo $reason['id'] ?></td>
			<td class="details"><?php echo $reason['name'] ?></td>
			<td class="details"><?php echo $reason['value'] ?></td>	
			<div>
				<td class="right-div">
					<a class="edit-delete btn" href="editReason.php?id=<?php echo $reason['id'];?>">Edit</a>
					<?php if(empty($reason['deleted_at'])): ?>
					<a class="edit-delete btn" href="deleteReason.php?id=<?php echo $reason['id'];?>	">Delete</a>
					<?php endif; ?>
				</td>
			</div>
			
		</tr>	
		<?php endforeach ?>
	</table>
	<div class="page">
		<span>
			<?php if(intval($page) > 1): ?>
			<a class="next back" href="reason.php?page=<?php echo $page-1 ?>"> << </a>
			<?php endif; ?>
		</span>
		<span>
			<?php if ($page < $pageCount): ?>
			<a class="next" href="reason.php?page=<?php echo $page+1 ?>"> >> </a>
			<?php endif; ?>
		</span>
		<span class="pageCount"> page <?php echo $page?> of <?php echo $pageCount ?></span>
	</div>
</div>
</div>