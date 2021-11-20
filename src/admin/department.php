<?php 
	include('database.php');
	session_start();
	// $servername = "localhost";
	// $username = "root";
	// $password = "root";
	// $dbname = 'oasis';

	// $departments = [];

	// $conn = new mysqli($servername, $username, $password, $dbname);
	// 	if ($conn->connect_error) {
 //  			die("Connection failed: " . $conn->connect_error);
	// 	}

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


	if (isset($_POST['submit'])) {
		// echo "<pre>";
		// var_dump($_POST);
		// echo "</pre>";
		// die;

		// $servername = "localhost";
		// $username = "root";
		// $password = "root";
		// $dbname = 'oasis';

		// $conn = new mysqli($servername, $username, $password, $dbname);
		// if ($conn->connect_error) {
  // 			die("Connection failed: " . $conn->connect_error);
		// }
		$sql = "INSERT INTO department (name, value)
				VALUES ('" . $_POST['name'] . "' , '" . $_POST['value'] . "')";

				if ($conn->query($sql) === TRUE) {
  					echo "New record created successfully";
  				} else {
  					echo "Error: " . $sql . "<br>" . $conn->error;
  				}
  				header("Location: department.php");
  				exit;
  	}


?>

<!-- CSS -->
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
<link rel="stylesheet" type="text/css" href="CSS/department.css">

<!-- JS -->
<script type="text/javascript" src="js-backup/jquery.min.js"></script>
<script type="text/javascript" src="js-backup/jquery-ui.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script type="text/javascript" src="js/bootstrap.bundle.js"></script>
<script type="text/javascript" src="js/bootstrap.bundle.min.js"></script>
<script type="text/javascript" src="js/bootstrap.esm.js"></script>
<script type="text/javascript" src="js/bootstrap.esm.min.js"></script>
<script type="text/javascript" src="js/bootstrap.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script>
	$(document).ready(function(){	
	$("#addDepartment").submit(function(event){
		submitForm();
		return false;
	});

	function submitForm(){
	 $.ajax({
		type: "POST",
		url: "addDepartment.php",
		cache:false,
		data: $('#addDepartment').serialize(),
		success: function(response){
			location.reload();
			//$("#toastAddComplete").toast("show");
			document.getElementById('toastAddComplete').className= "toast align-items-center show";

		},
		error: function(){
			alert("Error");
		}
	});
}
});	



</script>
<div class="header-div-oasis">
	<img src="https://i.imgur.com/FTPJl6s.png" style="height:75px;"><?php include "adminLogout.php";?>
</div>
<div class="col mt-4 div-top">
	<h2 class="mt-1">Admin Dashboard</h2>
</div>
<div class="col mt-4 div-btn">
		<a href="report.php"><button class="btn btn-primary">Reports</button></a>
		<a href="appointment.php"><button class="btn btn-primary">Appointment</button></a>
		<a href="user.php"><button class="btn btn-primary">User</button></a>
		<a href="department.php"><button class="btn btn-primary admin-selected">Department</button></a>
		<a href="reason.php"><button class="btn btn-primary">Reason</button></a>
		<a href="faculty.php"><button class="btn btn-primary">Faculty</button></a>
</div>
<!-- <div class="main">
<div class="left">
	<div class="space"> <a href="report.php">Reports</a> </div>
	<div class="space"> <a href="appointment.php">Appointments</a> </div>
	<div class="space"> <a href="user.php">Users</a> </div>
	<div class="admin-selected space"> <a href="department.php">Departments</a> </div>
	<div class="space"> <a href="reason.php">Reasons</a> </div>
	<div class="space"> <a href="faculty.php">Faculty</a> </div>	
</div> -->

<!-- 	<a href="addDepartment.php" class="add-btn btn">Add Department</a>
 -->
	<!-- START Button trigger DEPTModal -->
	<button type="button" class="btn btn-success btn-add" data-bs-toggle="modal" data-bs-toggle="tooltip"data-bs-placement="top" title="Add" data-bs-target="#DEPTModal">
	<span class="material-icons">person add</span>
	</button>
	<!--END Button trigger DEPTModal -->
	<div class="row align-items-center">
	<div class="col">
      		
    </div>
    <div class="col-9">
	<table class="table">
		<tr>
			<th class="col text-center" style="width: 5%;">ID</th>
			<th class="col text-center" style="width: 15%;">Name</th>
			<th class="col text-center" style="width: 15%;">Value</th>
			<th class="text-center" style="width: 1%;"></th>
			<th class="text-center" style="width: 1%;"></th>
		</tr>
		<?php foreach ($departments as $dept): ?>
		<tr>
			<td class="details"><?php echo $dept['id'] ?></td>
			<td class="details"><?php echo $dept['name'] ?></td>
			<td class="details"><?php echo $dept['value'] ?></td>
			<td>
				<button type="button" class="btn btn-warning" data-bs-toggle="modal" 			data-bs-toggle="tooltip"data-bs-placement="top" title="Edit" data-bs-target="		#EditDEPTRModal">
					<span class="material-icons">edit</span>
				</button>
			</td>
			<td>
				<?php if(empty($dept['deleted_at'])): ?>
					<a class="" href="deleteDepartment.php?id=<?php echo $dept['id'];?>"><button class="btn btn-secondary" 
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
	<div class="col-3">
      	<div class="pagination text-center">
			<span>
				<?php if(intval($page) > 1): ?>
				<a class="page-link" style="margin-left: 3px;" href="user.php?page=<?php echo $page-1 ?>"> << </a>
				<?php endif; ?>
			</span>
			<span class="pageCount mt-1"> PAGE <?php echo $page?> OF <?php echo $pageCount ?></span>
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




<!-- MODAL NG ADD DEPT -->
<!-- Modal -->
<div class="modal fade" id="DEPTModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
  	        	<form method="POST" id="addDepartment">

    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Department</h5>
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
        <button type="submit" class="btn btn-success" name="submit">Add</button>
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
      </div>
    </div>

</form>
  </div>
</div>

<div class="modal fade" id="EditDEPTRModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
  	        	<form method="POST" id="editDepartment">

    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Department</h5>
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

<div id="toastAddComplete" class="toast align-items-center" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="4000" style="position: fixed;top: 0; right: 0">
  <div class="d-flex">
    <div class="toast-body">
    Added Successfully!
   </div>
    <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
  </div>
</div>