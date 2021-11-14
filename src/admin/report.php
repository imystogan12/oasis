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
<link rel="stylesheet" type="text/css" href="css/report.css">
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

<div class="header-div">
	<p class="oasis">OASIS</p><?php include "adminLogout.php";?>
</div>
<div class="head2">
	<h2>Admin Dashboard</h2>
</div>
<div class="main">
<div class="left">
	<div class="admin-selected space"> <a href="report.php">Reports</a> </div>
	<div class="space"> <a href="appointment.php">Appointments</a> </div>
	<div class="space"> <a href="user.php">Users</a> </div>
	<div class="space"> <a href="department.php">Departments</a> </div>
	<div class="space"> <a href="reason.php">Reasons</a> </div>
	<div class="space"> <a href="faculty.php">Faculty</a> </div>
</div>
<div class="right" id="export">
	<!-- <button onClick="window.print()">Print this page</button> -->
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
<div class="print-div">
	<button class="print-btn" onClick="window.print()">Print this page</button>
</div>