<?php 
	// include('database.php');
include '../../database.php';
	session_start();
	// $servername = "localhost";
	// $username = "root";
	// $password = "root";
	// $dbname = 'oasis';

	$appointments = [];

	// $conn = new mysqli($servername, $username, $password, $dbname);
	// if ($conn->connect_error) {
	// 	die("Connection failed: " . $conn->connect_error);
	// }

	// Get total count first
	$totalCount = 0;
	$sql = "SELECT COUNT(*) as count FROM appointment WHERE status != 'deleted'";
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
	$sql = "SELECT a.id as apt_id, a.status, a.date_time, d.name as department_name," .
			" u.fname as user_fname, u.lname as user_lname, " .
			" s.student_fname, s.student_lname, " .
			" g.guest_fname, g.guest_lname " .
			" FROM appointment a " .
			" JOIN department d ON d.id = a.department_id " .
			"JOIN user u ON u.id = a.user_id " . 
			"LEFT JOIN student s ON s.id = a.student_id " .
			"LEFT JOIN guest g ON g.id = a.guest_id " .
			"WHERE status != 'deleted' " .
			"ORDER BY a.date_time ASC " .
			"LIMIT " . $perPage . " OFFSET " . $offset;
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
				$appointments[] = $row;
			}
	} else {
		// echo "not";
	}


?>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

<link rel="stylesheet" type="text/css" href="CSS/bootstrap.css">
<link rel="stylesheet" type="text/css" href="CSS/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="CSS/bootstrap.rtl.css">
<link rel="stylesheet" type="text/css" href="CSS/bootstrap.rtl.min.css">
<link rel="stylesheet" type="text/css" href="CSS/bootstrap-grid.css">
<link rel="stylesheet" type="text/css" href="CSS/bootstrap-grid.min.css">
<link rel="stylesheet" type="text/css" href="CSS/bootstrap-grid.rtl.css">
<link rel="stylesheet" type="text/css" href="CSS/bootstrap-grid.rtl.min.css">
<link rel="stylesheet" type="text/css" href="CSS/bootstrap-reboot.css">
<link rel="stylesheet" type="text/css" href="CSS/bootstrap-reboot.min.css">
<link rel="stylesheet" type="text/css" href="CSS/bootstrap-reboot.rtl.css">
<link rel="stylesheet" type="text/css" href="CSS/bootstrap-reboot.rtl.min.css">
<link rel="stylesheet" type="text/css" href="CSS/bootstrap-utilities.css">
<link rel="stylesheet" type="text/css" href="CSS/bootstrap-utilities.min.css">
<link rel="stylesheet" type="text/css" href="CSS/bootstrap-utilities.rtl.css">
<link rel="stylesheet" type="text/css" href="CSS/bootstrap-utilities.rtl.min.css">
<link rel="stylesheet" type="text/css" href="CSS/appointment.css">


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script type="text/javascript" src="js/bootstrap.bundle.js"></script>
<script type="text/javascript" src="js/bootstrap.bundle.min.js"></script>
<script type="text/javascript" src="js/bootstrap.esm.js"></script>
<script type="text/javascript" src="js/bootstrap.esm.min.js"></script>
<script type="text/javascript" src="js/bootstrap.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>

<!-- <style>
	.admin-selected {
		background-color: #F2F2F2;
	}
</style> -->
<div class="header-div-oasis">
	<img src="https://i.imgur.com/FTPJl6s.png" style="height:75px;"><?php include "adminLogout.php";?>
</div>

<div class="col mt-4 div-top">
			<h2 class="mt-1">Admin Dashboard</h2>
</div>

	<div class="col mt-4 div-btn">
		<a href="report.php"><button class="btn btn-primary">Reports</button></a>
		<a href="appointment.php"><button class="btn btn-primary admin-selected">Appointment</button></a>
		<a href="user.php"><button class="btn btn-primary">User</button></a>
		<a href="department.php"><button class="btn btn-primary">Department</button></a>
		<a href="reason.php"><button class="btn btn-primary">Reason</button></a>
		<a href="faculty.php"><button class="btn btn-primary">Faculty</button></a>
	</div>

	<div class="print-div">
			<button class="bg-success btn" style="color: white;" onClick="window.print()">Print this page</button>
</div>

<div class="container">
<!-- BUTTONS -->
<div class="row row-cols-1 row-cols-1 row-cols-md-1 justify-content-left">
	
	<!-- <div class="col mt-3">
			<h2 class="mt-1">Admin Dashboard</h2>
	</div> -->
	

</div>
</div>


<!-- <div class="head2">
	<h2>Admin Dashboard</h2>
</div>
<div class="main">
<div class="left">
	<div class="space"> <a href="report.php">Reports</a> </div>
	<div class="admin-selected space"> <a href="appointment.php">Appointment</a> </div>
	<div class="space"> <a href="user.php">Users</a> </div>
	<div class="space"> <a href="department.php">Departments</a> </div>
	<div class="space"> <a href="reason.php">Reasons</a> </div>
	<div class="space"> <a href="faculty.php">Faculty</a> </div>
	
</div> -->
<!-- <div class="right"> -->
	<div class="row align-items-center">
    <div class="col">
      		
    </div>
    <div class="col-11">
	<table class="table mt-2">
		<tr>
			<th class="col text-center" style="width: 5%;">ID</th>
			<th class="col text-center" style="width: 15%;">Full Name</th>
			<th class="col text-center" style="width: 15%;">Type</th>
			<th class="col text-center" style="width: 15%;">Department</th>
			<th class="col text-center" style="width: 15%;">Assignee</th>
			<th class="col text-center" style="width: 20%;" >Schedule</th>
			<th class="col text-center" style="width: 5%;">Status</th>
		</tr>
		<?php foreach ($appointments as $apt): ?>
		<?php $transaction_type = !empty($apt['student_fname']) ? 'student' : 'guest'; ?>
		<tr>
			<td class="details"><?php echo $apt['apt_id'] ?></td>
			<td class="details"><?php echo $apt["{$transaction_type}_fname"] . " " . $apt["{$transaction_type}_lname"] ?></td>
			<td class="text-center">
				<button 
					class=" btn text-center <?php echo($transaction_type == 'student') ? "btn-primary" : "btn-warning"  ?>" style="width: 80%;" >	<?php echo ucwords($transaction_type) ?>	
				</button>
				</td>
			<td class="details"><?php echo $apt['department_name'] ?></td>
			<td class="details"><?php echo $apt['user_fname'] . " " . $apt['user_lname'] ?></td>
			<td class="details"> <?php 
				$aptDateTime = (DateTime::createFromFormat('Y-m-d H:i:s', $apt['date_time']));
				echo $aptDateTime->format('M. d, Y h:i A')
			?> </td>
			<td><button  style="width: 100%;" onclick="return false" style="pointer-events: none; min-width: 100%;" class="text-center btn text-light <?php echo ($apt['status'] == "pending") ? "btn-warning" : 
				($apt['status'] == "declined" ? "bg-danger" : "bg-success")  ?>"><?php echo ucwords($apt['status']) ?></button></td>
			<td class="details">
				<?php if($apt['status'] !== "deleted"): ?>
				<button class="btn btn-secondary text-center" data-bs-toggle="tooltip" 						data-bs-placement="top" title="Delete" href="deleteAppointment.php?id=<?php echo $apt['apt_id']; ?>"><span class="material-icons">delete</span></button>
			<?php endif; ?>
			</td>
		</tr>	
		<?php endforeach ?>
	</table>
	</div>
	<div class="col">
      		
    </div>
</div>

<div class="col">
      
    </div>
  </div>
	<div class="container">
	<div class="row align-items-center">
    	<div class="col bg-primary">
      		
    	</div>
    	<div class="col">

      
			
	</div>
	<div class="col-auto">
      	<div class="pagination text-center">
			<span>
				<?php if(intval($page) > 1): ?>
				<a class="page-link" style="margin-left: 3px;" href="appointment.php?page=<?php echo $page-1 ?>"> << </a>
				<?php endif; ?>
			</span>
			<span class="pageCount mt-2"> PAGE <?php echo $page?> OF <?php echo $pageCount ?></span>
			<span>
				<?php if ($page < $pageCount): ?>
				<a class="page-link" style="margin-left: 5px;" href="appointment.php?page=<?php echo $page+1 ?>"> >> </a>
				<?php endif; ?>
			</span>
		</div>
    </div>
	</div>
	</div>
</div>
	
</div>