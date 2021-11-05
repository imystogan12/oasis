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
	$aptScannedCount = executeCountQuery($conn, "SELECT COUNT(*) as count FROM appointment WHERE status='scanned'");
	$aptAcceptedCount = executeCountQuery($conn, "SELECT COUNT(*) as count FROM appointment WHERE status='accepted'");
	$aptDeclinedCount = executeCountQuery($conn, "SELECT COUNT(*) as count FROM appointment WHERE status='declined'");
	$aptDeletedCount = executeCountQuery($conn, "SELECT COUNT(*) as count FROM appointment WHERE status='deleted'");

	$studTotalCount = executeCountQuery($conn, "SELECT COUNT(*) as count FROM student");
	$studCompanionCount = executeCountQuery($conn, "SELECT SUM(student_companion) as count FROM student");

	$guestTotalCount = executeCountQuery($conn, "SELECT COUNT(*) as count FROM guest");
	$guestCompanionCount = executeCountQuery($conn, "SELECT SUM(guest_companion) as count FROM guest");

?>
<div>
	<header>OASIS</header>
</div>
<div>
	<h2>Admin Dashboard</h2>
</div>
<div>
	<div> <a href="report.php">Reports</a> </div>
	<div> <a href="user.php">Users</a> </div>
	<div> <a href="department.php">Departments</a> </div>
	<div> <a href="reason.php">Reasons</a> </div>
	<div> <a href="faculty.php">Faculty</a> </div>
</div>
<div>
	<h3> Appointments </h3>
	<table>
		<tr>
			<td> Total </td>
			<td> <?php echo $aptTotalCount ?> </td>
		</tr>
		<tr>
			<td> Pending </td>
			<td> <?php echo $aptPendingCount ?> </td>
		</tr>
		<tr>
			<td> Scanned </td>
			<td> <?php echo $aptScannedCount ?> </td>
		</tr>
		<tr>
			<td> Accepted </td>
			<td> <?php echo $aptAcceptedCount ?> </td>
		</tr>
		<tr>
			<td> Declined </td>
			<td> <?php echo $aptDeclinedCount ?> </td>
		</tr>
		<tr>
			<td> Deleted </td>
			<td> <?php echo $aptDeletedCount ?> </td>
		</tr>
	</table>

	<h3> Students </h3>
	<table>
		<tr>
			<td> Total </td>
			<td> <?php echo $studTotalCount ?> </td>
		</tr>
		<tr>
			<td> Companion Count </td>
			<td> <?php echo $studCompanionCount ?> </td>
		</tr>
	</table>


	<h3> Guests </h3>
	<table>
		<tr>
			<td> Total </td>
			<td> <?php echo $guestTotalCount ?> </td>
		</tr>
		<tr>
			<td> Companion Count </td>
			<td> <?php echo $guestCompanionCount ?> </td>
		</tr>
	</table>
</div>