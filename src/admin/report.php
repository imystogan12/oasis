<?php 
	// include('database.php');
	include '../../database.php';
	session_start();
	// $servername = "localhost";
	// $username = "root";
	// $password = "root";
	// $dbname = 'oasis';

	$departments = [];

	// $conn = new mysqli($servername, $username, $password, $dbname);
	// 	if ($conn->connect_error) {
 //  			die("Connection failed: " . $conn->connect_error);
	// 	}

	$sql = "SELECT id, name, value FROM department";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
				$departments[] = $row;
			}
	} else {
		echo "not";
	}

	function executeCountQuery($conn, $query) {
		$result = $conn->query($query);
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				return $row['count'];
			}
		}
	}

	// Get count of appointments
	$aptTotalCount = executeCountQuery($conn, "SELECT COUNT(*) as count FROM appointment");
	$aptPendingCount = executeCountQuery($conn, "SELECT COUNT(*) as count FROM appointment WHERE status='pending'");
	$aptScannedCount = executeCountQuery($conn, "SELECT COUNT(*) as count FROM appointment WHERE scanned_at IS NOT NULL");
	$aptAcceptedCount = executeCountQuery($conn, "SELECT COUNT(*) as count FROM appointment WHERE status='accepted'");
	$aptDeclinedCount = executeCountQuery($conn, "SELECT COUNT(*) as count FROM appointment WHERE status='declined'");
	$aptDeletedCount = executeCountQuery($conn, "SELECT COUNT(*) as count FROM appointment WHERE status='deleted'");

	$studTotalCount = executeCountQuery($conn, "SELECT COUNT(*) as count FROM student");
	$studCompanionCount = executeCountQuery($conn, "SELECT SUM(student_companion) as count FROM student");

	$guestTotalCount = executeCountQuery($conn, "SELECT COUNT(*) as count FROM guest");
	$guestCompanionCount = executeCountQuery($conn, "SELECT SUM(guest_companion) as count FROM guest");

?>
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
<link rel="stylesheet" type="text/css" href="CSS/report.css">

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script type="text/javascript" src="js/bootstrap.bundle.js"></script>
<script type="text/javascript" src="js/bootstrap.bundle.min.js"></script>
<script type="text/javascript" src="js/bootstrap.esm.js"></script>
<script type="text/javascript" src="js/bootstrap.esm.min.js"></script>
<script type="text/javascript" src="js/bootstrap.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript">
	var element = document.getElementById('export');
	html2pdf(element);
</script> -->

<!-- <script>
// 	function printDocument(documentId) {
//     var doc = document.getElementById(documentId);
//     console.log('printDocument');
//     console.log(doc);
//     console.log(doc.print);

//     //Wait until PDF is ready to print    
//     // if (!doc || typeof doc === 'undefined' || typeof doc.print === 'undefined') {\
//     if (!doc || typeof doc === 'undefined' || typeof doc.print() === 'undefined') {    
//         setTimeout(function(){printDocument(documentId);}, 1000);
//     } else {
//         doc.print();
//     }
// }
// printDocument('export');

// 	function printDocument() {
// 		window.print()
// 	}


// setTimeout(function(){window.print()}, 5000);

</script> -->

<?php
	// $dir = dirname(__FILE__, 3);
	// echo $dir . "<br>";
	// echo dirname('../../oasis'); die;
?>

<div class="header-div-oasis">
	<img src="https://i.imgur.com/FTPJl6s.png" style="height:75px;"><?php include "adminLogout.php";?>
</div>
<!-- <div class="head2">
	<h2>Admin Dashboard</h2>
</div> -->
<!-- <div class="main">
<div class="left">
	<div class="admin-selected space"> <a href="report.php">Reports</a> </div>
	<div class="space"> <a href="appointment.php">Appointments</a> </div>
	<div class="space"> <a href="user.php">Users</a> </div>
	<div class="space"> <a href="department.php">Departments</a> </div>
	<div class="space"> <a href="reason.php">Reasons</a> </div>
	<div class="space"> <a href="faculty.php">Faculty</a> </div>
</div>
</div> -->

<div class="col mt-4 div-top">
			<h2 class="mt-1">Admin Dashboard</h2>
	</div>

	<div class="col mt-4 div-btn">
		<a href="report.php"><button class="btn btn-primary admin-selected">Reports</button></a>
		<a href="appointment.php"><button class="btn btn-primary">Appointment</button></a>
		<a href="user.php"><button class="btn btn-primary">User</button></a>
		<a href="department.php"><button class="btn btn-primary">Department</button></a>
		<a href="reason.php"><button class="btn btn-primary">Reason</button></a>
		<a href="faculty.php"><button class="btn btn-primary">Faculty</button></a>
	</div>

<div class="container">
<!-- BUTTONS -->
<div class="row row-cols-1 row-cols-1 row-cols-md-1 justify-content-left">
	
	<!-- <div class="col mt-3">
			<h2 class="mt-1">Admin Dashboard</h2>
	</div>
	<div class="col mt-4">
		<a href="report.php"><button class="btn btn-primary">Reports</button></a>
		<a href="appointment.php"><button class="btn btn-primary">Appointment</button></a>
		<a href="user.php"><button class="btn btn-primary">User</button></a>
		<a href="department.php"><button class="btn btn-primary">Department</button></a>
		<a href="reason.php"><button class="btn btn-primary">Reason</button></a>
		<a href="faculty.php"><button class="btn btn-primary">Faculty</button></a>

	</div> -->

</div>

<!-- TITLE HEADER -->
<div class="print-div">
			<button class="bg-success btn" style="color: white;" onClick="window.print()">Print this page</button>
</div>
<div class="row row-cols-2 row-cols-2 row-cols-md-5 justify-content-start">
	<div class="col mt-3">
		<p class="fs-3 fw-bold">Appointments:</p> 
		<!-- <div class="col">
			<button class="print-btn" onClick="window.print()">Print this page</button>
		</div> -->
	</div>

</div>
<!-- HEADER CARD CONTENT -->
<div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 justify-content-start">

	<!-- 
		row-cols-2 = mobile / 2 column lang ugn ibig sabihin ng 2

	-->

	<div class="col mb-2">
		<div class="card bg-primary" style="">
		  <div class="card-body">
		    <p class="card-title fs-4 fw-bold">Total:</p>
		    <p class="card-subtitle mb-2 fs-4 fw-bold"><?php echo $aptTotalCount ?></p>
		    
		  </div>
		</div>
	</div>

	<!-- PENDING CARD START -->

	<div class="col mb-2">
		<div class="card bg-warning" style="">
		  <div class="card-body">
		    <p class="card-title fs-4 fw-bold">Pending:</p>
		    <p class="card-subtitle mb-2 fs-4 fw-bold"><?php echo $aptPendingCount ?></p>
		    
		  </div>
		</div>
	</div>
	<!-- PENDING CARD END -->

	<!-- ACCEPT CARD START -->
	<div class="col mb-2">
		<div class="card bg-success" style="">
		  <div class="card-body">
		    <p class="card-title fs-4 fw-bold">Accepted:</p>
		    <p class="card-subtitle mb-2 fs-4 fw-bold"><?php echo $aptAcceptedCount ?></p>
		    
		  </div>
		</div>
	</div>
	<!-- ACCEPT CARD END -->

		<!-- SCANNED CARD START -->
	<div class="col mb-2">
		<div class="card bg-secondary" style="">
		  <div class="card-body">
		    <p class="card-title fs-4 fw-bold">Scanned:</p>
		    <p class="card-subtitle mb-2 fs-4 fw-bold"><?php echo $aptScannedCount ?></p>
		    
		  </div>
		</div>
	</div>
	<!-- SCANNED CARD END -->

		<!-- SCANNED CARD START -->
	<div class="col mb-2">
		<div class="card bg-danger" style="">
		  <div class="card-body">
		    <p class="card-title fs-4 fw-bold">Declined:</p>
		    <p class="card-subtitle mb-2 fs-4 fw-bold"><?php echo $aptDeclinedCount ?></p>
		    
		  </div>
		</div>
	</div>
	<!-- SCANNED CARD END -->

	<div class="col mb-2">
		<div class="card bg-muted" style="">
		  <div class="card-body">
		    <p class="card-title fs-4 fw-bold">Deleted:</p>
		    <p class="card-subtitle mb-2 fs-4 fw-bold"><?php echo $aptDeletedCount ?></p>
		    
		  </div>
		</div>
	</div>

</div>

	<div class="row row-cols-2 row-cols-2 row-cols-md-5 justify-content-start">
		<div class="col mt-3">
			<p class="fs-3 fw-bold">Students:</p>
		</div>
	</div>

	<div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 justify-content-start">

	<div class="col mb-2">
		<div class="card bg-primary" style="">
		  <div class="card-body">
		    <p class="card-title fs-4 fw-bold">Total:</p>
		    <p class="card-subtitle mb-2 fs-4 fw-bold"><?php echo $studTotalCount ?></p>
		    
		  </div>
		</div>
	</div>

	<div class="col mb-2">
		<div class="card bg-info" style="">
		  <div class="card-body">
		    <p class="card-title fs-4 fw-bold">Companion Count:</p>
		    <p class="card-subtitle mb-2 fs-4 fw-bold"><?php echo $studCompanionCount ?></p>
		    
		  </div>
		</div>
	</div>

</div>
	
	<div class="row row-cols-2 row-cols-2 row-cols-md-5 justify-content-start">
		<div class="col mt-3">
			<p class="fs-3 fw-bold">Guests:</p>
		</div>
	</div>

	<div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 justify-content-start">

	<div class="col mb-2">
		<div class="card bg-primary" style="">
		  <div class="card-body">
		    <p class="card-title fs-4 fw-bold">Total:</p>
		    <p class="card-subtitle mb-2 fs-4 fw-bold"><?php echo $guestTotalCount ?></p>
		  </div>
		</div>
	</div>

	<div class="col mb-2">
		<div class="card bg-info" style="">
		  <div class="card-body">
		    <p class="card-title fs-4 fw-bold">Companion Count:</p>
		    <p class="card-subtitle mb-2 fs-4 fw-bold"><?php echo $guestCompanionCount ?></p>
		  </div>
		</div>
	</div>

	</div>

</div>
<!-- <div class="right" id="export">
	<div class="table-container">
		<div>
			<h3> Appointments </h3>
			<table>
				<tr>
					<td class="text"> Total: </td>
					<td class="text"> <?php echo $aptTotalCount ?> </td>
				</tr>
				<tr>
					<td class="text"> Pending: </td>
					<td class="text"> <?php echo $aptPendingCount ?> </td>
				</tr>
				<tr>
					<td class="text"> Scanned: </td>
					<td class="text"> <?php echo $aptScannedCount ?> </td>
				</tr>
				<tr>
					<td class="text"> Accepted: </td>
					<td class="text"> <?php echo $aptAcceptedCount ?> </td>
				</tr>
				<tr>
					<td class="text"> Declined: </td>
					<td class="text"> <?php echo $aptDeclinedCount ?> </td>
				</tr>
				<tr>
					<td class="text"> Deleted: </td>
					<td class="text"> <?php echo $aptDeletedCount ?> </td>
				</tr>
			</table>
		</div>
		<div>
			<h3> Students </h3>
			<table>
				<tr>
					<td class="text"> Total: </td>
					<td class="text"> <?php echo $studTotalCount ?> </td>
				</tr>
				<tr>
					<td class="text"> Companion Count: </td>
					<td class="text"> <?php echo $studCompanionCount ?> </td>
				</tr>
			</table>
		</div>
		<div>
			<h3> Guests </h3>
			<table>
				<tr>
					<td class="text"> Total: </td>
					<td class="text"> <?php echo $guestTotalCount ?> </td>
				</tr>
				<tr>
					<td class="text"> Companion Count: </td>
					<td class="text"> <?php echo $guestCompanionCount ?> </td>
				</tr>
			</table>
		</div>
	<div>
	</div>
</div>
</div> -->
<!-- <div class="print-div">
	<button class="print-btn" onClick="window.print()">Print this page</button>
</div> -->