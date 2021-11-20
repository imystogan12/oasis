<?php  
	include('database.php');
	session_start();
	// $servername = "localhost";
	// $username = "root";
	// $password = "root";
	// $dbname = 'oasis';

	$users = [];

	// $conn = new mysqli($servername, $username, $password, $dbname);
	// 	if ($conn->connect_error) {
 //  			die("Connection failed: " . $conn->connect_error);
	// 	}

	$totalCount = 0;
	$sql = "SELECT COUNT(*) as count FROM user WHERE deleted_at is NULL";
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
		
	$sql = "SELECT id, username, fname, lname, role, password FROM user " . " LIMIT " . $perPage . " OFFSET " . $offset;
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
				$users[] = $row;
			}
	} else {
		echo "not";
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
<link rel="stylesheet" type="text/css" href="CSS/user.css">

<div class="header-div-oasis">
	<img src="https://i.imgur.com/FTPJl6s.png" style="height:75px;"><?php include "adminLogout.php";?>
</div>
<div class="col mt-4 div-top">
			<h2 class="mt-1">Admin Dashboard</h2>
	</div>

	<div class="col mt-4 div-btn">
		<a href="report.php"><button class="btn btn-primary">Reports</button></a>
		<a href="appointment.php"><button class="btn btn-primary">Appointment</button></a>
		<a href="user.php"><button class="btn btn-primary admin-selected">User</button></a>
		<a href="department.php"><button class="btn btn-primary">Department</button></a>
		<a href="reason.php"><button class="btn btn-primary">Reason</button></a>
		<a href="faculty.php"><button class="btn btn-primary">Faculty</button></a>
	</div>

	<div class="container">

		<div class="row row-cols-1 row-cols-1 row-cols-md-1 justify-content-left">
		</div>
	</div>



<!-- <div class="left">
	<div class="space"> <a href="report.php">Reports</a> </div>
	<div class="space"> <a href="appointment.php">Appointments</a> </div>
	<div class="admin-selected space"> <a href="user.php">Users</a> </div>
	<div class="space"> <a href="department.php">Departments</a> </div>
	<div class="space"> <a href="reason.php">Reasons</a> </div>
	<div class="space"> <a href="faculty.php">Faculty</a> </div>	

</div> -->
	<!-- <a href="addUser.php"><button class="btn btn-success btn-add" data-bs-toggle="tooltip"						data-bs-placement="top" title="Add"><span class="material-icons">person add</span></button></a> -->
	<button type="button" class="btn btn-success btn-add" data-bs-toggle="modal" data-bs-toggle="tooltip"data-bs-placement="top" title="Add" data-bs-target="#USERModal">
	<span class="material-icons">person add</span>
	</button>

	<div class="row align-items-center">
	<div class="col">
		
	</div>
	<div class="col-11">
	<table class="table">
		<tr>
		<th class="col text-center" style="width: 5%;">ID</th>
		<th class="col text-center" style="width: 15%;">Username</th>
		<th class="col text-center" style="width: 15%;">First Name</th>
		<th class="col text-center" style="width: 15%;">Last Name</th>		
		<th class="col text-center" style="width: 10%;">Role</th>
		<th style="width: 1%;"></th>
		<th style="width: 1%;"></th>
	</tr>
	<?php foreach ($users as $user): ?>
		<tr>
			<td class="details"><?php echo $user['id'] ?></td>
			<td class="details"><?php echo $user['username'] ?></td>
			<td class="details"><?php echo $user['fname'] ?></td>
			<td class="details"><?php echo $user['lname'] ?></td>
			<td class="details"><?php echo $user['role'] ?></td>
			<td style="width: 1%;">
				<button type="button" class="btn btn-warning" data-bs-toggle="modal" 			data-bs-toggle="tooltip"data-bs-placement="top" title="Edit" data-bs-target="		#EditUSERModal">
					<span class="material-icons">edit</span>
				</button>
			</td>
			<td style="width: 1%;">
				<?php if(empty($faculty['deleted_at'])): ?>
					<a href="deleteFaculty.php"><button class="btn btn-secondary" 
						data-bs-toggle="tooltip" data-bs-placement="top" title="Delete"><span class="material-icons">delete</span></button></a>
				<?php endif; ?>
			</td>	
		</tr>	
		<?php endforeach ?>
	</table>
	</div>
	<div class="col">
		
	</div>
	<div class="container">
	<div class="row align-items-center">
    	<div class="col bg-primary">
      		
    	</div>
    	<div class="col">

      
			
	</div>
	<div class="col-2">
      	<div class="pagination text-center">
			<span>
				<?php if(intval($page) > 1): ?>
				<a class="page-link" style="margin-left: 3px;" href="user.php?page=<?php echo $page-1 ?>"> << </a>
				<?php endif; ?>
			</span>
			<span class="pageCount mt-2"> PAGE <?php echo $page?> OF <?php echo $pageCount ?></span>
			<span>
				<?php if ($page < $pageCount): ?>
				<a class="page-link" style="margin-left: 5px;" href="user.php?page=<?php echo $page+1 ?>"> >> </a>
				<?php endif; ?>
			</span>
		</div>
    </div>
	</div>
	</div>
</div>

<div class="modal fade" id="USERModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
  	        	<form method="POST" id="addUser">

    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">    
        <!-- BODY START -->
					<label>First Name:</label>
					<input type="text" name="fname" required class="form-control">

					<label>Last Name:</label>
					<input type="text" name="lname" required class="form-control">

					<label>Username:</label>
					<input type="text" name="username" required class="form-control">

					<label>Password:</label>
					<input type="password" name="password" required class="form-control">

					<label>Role:</label>
					<select name="role" id="role" required class="form-control">
						<option>Please Select</option>
						<option>Cashier</option>
						<option>Registrar</option>
						<option>Admission</option>
						<option>Guidance</option>
						<option>Clinic</option>
						<option>OJT</option>
						<option>Prowear</option>
						<option>Faculty</option>
						<option>Guard</option>
						<option>Administrator</option>
					</select>
        <!-- BODY END -->
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success" name="submit">Add</button>
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
      </div>
    </div>
</form>
  </div>
</div>

<div class="modal fade" id="EditUSERModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
  	        	<form method="POST" id="editUser">

    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">    
        <!-- BODY START -->
					<label>First Name:</label>
					<input type="text" name="fname" required class="form-control">

					<label>Last Name:</label>
					<input type="text" name="lname" required class="form-control">

					<label>Username:</label>
					<input type="text" name="username" required class="form-control">

					<label>Password:</label>
					<input type="password" name="password" required class="form-control">

					<label>Role:</label>
					<select name="role" id="role" required class="form-control">
						<option>Please Select</option>
						<option>Cashier</option>
						<option>Registrar</option>
						<option>Admission</option>
						<option>Guidance</option>
						<option>Clinic</option>
						<option>OJT</option>
						<option>Prowear</option>
						<option>Faculty</option>
						<option>Guard</option>
						<option>Administrator</option>
					</select>
        <!-- BODY END -->
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success" name="submit">Edit</button>
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
      </div>
    </div>
</form>
 </div>
</div>