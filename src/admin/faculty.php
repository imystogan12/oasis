<?php  
	session_start();
	$servername = "localhost";
	$username = "root";
	$password = "root";
	$dbname = 'oasis';



	$conn = new mysqli($servername, $username, $password, $dbname);
		if ($conn->connect_error) {
  			die("Connection failed: " . $conn->connect_error);
		}

	if (isset($_POST['submit'])) {
		$sql = "INSERT INTO faculty (dept_id, fname, lname, user_report_id)
			VALUES ( " . $_POST['dept_id'] . " , '" . $_POST['fname'] . "' , '" . $_POST['lname'] . "' , " . $_POST['program-head'] . ")";

			if ($conn->query($sql) === TRUE) {
  					echo "New record created successfully";
  				} else {
  					echo "Error: " . $sql . "<br>" . $conn->error;
  				}
  				header("Location: faculty.php");
  				exit;
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
<link rel="stylesheet" type="text/css" href="CSS/faculty.css">
<!-- JS -->
<script type="text/javascript" src="js-backup/jquery.min.js"></script>
<script type="text/javascript" src="js-backup/jquery-ui.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script type="text/javascript" src="js/bootstrap.bundle.js"></script>
<script type="text/javascript" src="js/bootstrap.bundle.min.js"></script>
<script type="text/javascript" src="js/bootstrap.esm.js"></script>
<script type="text/javascript" src="js/bootstrap.esm.min.js"></script>
<script type="text/javascript" src="js/bootstrap.js"></script>

<div class="header-div-oasis">
	<img src="https://i.imgur.com/FTPJl6s.png" style="height:75px;">
</div>
<div class="col mt-4 div-top">
	<h2 class="mt-1">Admin Dashboard</h2>
</div>

<div class="col mt-4 div-btn">
		<a href="report.php"><button class="btn btn-primary">Reports</button></a>
		<a href="appointment.php"><button class="btn btn-primary">Appointment</button></a>
		<a href="user.php"><button class="btn btn-primary">User</button></a>
		<a href="department.php"><button class="btn btn-primary">Department</button></a>
		<a href="reason.php"><button class="btn btn-primary">Reason</button></a>
		<a href="faculty.php"><button class="btn btn-primary admin-selected">Faculty</button></a>
</div>

	<button type="button" class="btn btn-success btn-add" data-bs-toggle="modal" data-bs-toggle="tooltip"data-bs-placement="top" title="Add" data-bs-target="#FACULTYModal">
	<span class="material-icons">person add</span>
	</button>

	<div class="row align-items-center">
		<div class="col">
			
		</div>
	<div class="col-10">
	<table class="table">
		<tr>
			<th class="col text-center" style="width: 10%;">Id</th>
			<!-- <th class="col text-center">Department</th> -->
			<th class="col text-center" style="width: 30%;">First Name</th>
			<th class="col text-center" style="width: 30%;">Last Name</th>
			<th class="col text-center" style="width: 30%;">Program Head</th>
			<th style="width: 1%;"></th>
			<th style="width: 1%;"></th>
		</tr>
		<?php foreach ($faculty as $faculty): ?>
		<tr>
			<td class="details"><?php echo $faculty['id'] ?></td>
			<!-- <td class="details"><?php echo $faculty['department_name'] ?></td> -->
			<td class="details"><?php echo $faculty['fname'] ?></td>
			<td class="details"><?php echo $faculty['lname'] ?></td>
			<td 
				class="details"><?php echo $faculty['user_fname'] . ' ' .  $faculty['user_lname']?>
			</td>
			<td> 
				<button type="button" class="btn btn-warning" data-bs-toggle="modal" 			data-bs-toggle="tooltip" data-bs-placement="top" title="Edit" 
				data-bs-target="#EditFACULTYModal">
					<span class="material-icons">edit</span>
				</button>
			</td>
			<td>
				<?php if(empty($faculty['deleted_at'])): ?>
					<a class="" href="deleteFaculty.php?id=<?php echo $faculty['id'];?>"><button class="btn btn-secondary" 
						data-bs-toggle="tooltip" data-bs-placement="top" title="Delete"><span class="material-icons">delete</span></button></a>
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
				<a class="page-link" style="margin-left: 3px;" href="faculty.php?page=<?php echo $page-1 ?>"> << </a>
				<?php endif; ?>
			</span>
			<span class="pageCount mt-1"> PAGE <?php echo $page?> OF <?php echo $pageCount ?></span>
			<span>
				<?php if ($page < $pageCount): ?>
				<a class="page-link" style="margin-left: 5px;" href="faculty.php?page=<?php echo $page+1 ?>"> >> </a>
				<?php endif; ?>
			</span>
		</div>
    </div>
	</div>
	</div>

	<div class="modal fade" id="FACULTYModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
  	        	<form method="POST" id="addFaculty">

    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Faculty</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        
        <!-- BODY START -->



					<label class="name">First Name:</label>
					<input type="text" name="fname" required class="form-control">


					<label class="name">Last Name:</label>
					<input type="text" name="lname" required class="form-control">





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

<div class="modal fade" id="EditFACULTYModal" tabindex="-1" aria-labelledby="exampleModalLabel" 
		aria-hidden="true">
  <div class="modal-dialog">
  	        	<form method="POST" id="editFaculty">

    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Faculty</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">    
        <!-- BODY START -->
					<label class="">Name:</label>
					<input type="text" name="name" required="" class="form-control">


					<label class="">Value:</label>
					<input type="text" name="value" required="" class="form-control">
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
<!--  TOAST ADD COMPLETE -->

<!-- <div id="toastAddComplete" class="toast align-items-center" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="4000" style="position: fixed;top: 0; right: 0">
  <div class="d-flex">
    <div class="toast-body">
    Added Successfully!
   </div>
    <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
  </div>
</div> -->