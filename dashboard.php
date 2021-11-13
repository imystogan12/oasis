<?php
	session_start();
	// echo "<pre>";
	// var_dump($_SESSION);
	// echo "</pre>";

	$appointment = [];

		$servername = "localhost";
		$username = "root";
		$password = "root";
		$dbname = 'oasis';

		// $transaction_type = isset($_SESSION['session_type']) ? $_SESSION['session_type'] : 'student';

		$conn = new mysqli($servername, $username, $password, $dbname);
		if ($conn->connect_error) {
  			die("Connection failed: " . $conn->connect_error);
		}

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

		// echo "Connected successfully";
		$sql = 'SELECT apt.user_id, r.name as reason_name, apt.date_time, s.student_lname, s.student_fname, s.student_email, s.student_num, s.student_companion, s.student_course, s.student_section, g.guest_address, g.guest_companion, g.guest_fname, g.guest_lname, g.guest_email, g.guest_number, d.name as department_name, apt.status, apt.id as appointment_id, f.fname, f.lname, apt.scanned_at ' .
				' FROM appointment apt ' .
				' JOIN department d ON d.id = apt.department_id ' . 
				' JOIN reason r ON r.id = apt.reason_id ' . 
				' LEFT JOIN student s ON apt.student_id = s.id ' .
				' LEFT JOIN guest g ON apt.guest_id = g.id ' .
				' LEFT JOIN faculty f ON apt.faculty_id = f.id ' .
				' WHERE apt.user_id="' . $_SESSION['user']['id']. '"' .
				' AND apt.status!="deleted"' .
				"ORDER BY FIELD (apt.status, 'pending', 'accepted', 'declined'), apt.date_time ASC " .
				"LIMIT " . $perPage . " OFFSET " . $offset;
		$result = $conn->query($sql);

		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				$appointment[] = $row;
			}
		}
		function sortFunction( $a, $b ) {
    return strtotime($a["date"]) - strtotime($b["date"]);
}
// usort($data, "sortFunction");
// var_dump($data);
 
 ?>
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery-ui.js"></script>
<script>
  $( function() {
 	 $( '.dialog' ).dialog({
 	 	autoOpen: false
 	 });
    $( ".view-btn" ).on( "click", function(e) {
    	e.preventDefault();
      	$( ".dialog" ).dialog( "close" );
      	var aptId=$(this).data('apt-id');
      	$( '.dialog[data-apt-id="' + aptId +'"]' ).dialog( "open" );
    });
  } );
  </script>

<link rel="stylesheet" type="text/css" href="css/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="css/dashboard.css">
<div class="header-div">
	<p class="oasis">OASIS</p><?php include "logout.php";?>
</div>

<div class="head2">
	<h2><?php echo ucwords($_SESSION['user']['role']) ?> Dashboard</h2>
</div>
<script>
function showConfirm(form) {
	console.log(form)

    if(confirm('Do you really want to continue?')) {
    	return 'updateAppointment.php';
    } else {
    	return false;
    }
}
</script>
	<form onsubmit="return showConfirm(this)" action="updateAppointment.php" method="POST" id="updateAppointment-form">
	<table class="details info">
		<tr>
			<!-- <th class="galit">ID</th> -->
			<th class="galit">Last Name</th>
			<th class="galit">First Name</th>
			<!-- <th class="galit">Email</th> -->
			<th class="galit">Faculty</th>
			<!-- <th class="galit">Reason</th> -->
			<th class="galit">Schedule</th>
			<th class="galit">Status</th>
			<th class="galit">Time Arrived</th>
			<th class="galit">Action</th>
		</tr>
		<?php foreach($appointment as $apt): ?> 
		<?php $transaction_type = !empty($apt['student_fname']) ? 'student' : 'guest'; ?>
		<tr>
			<!-- <td> <?php echo $apt['appointment_id']; ?> -->
			<td> <?php echo $apt[$transaction_type . '_lname']; ?>
			<td> <?php echo  $apt[$transaction_type . '_fname']?>
			<!-- <td> <?php echo  $apt[$transaction_type . '_email']?> -->
			<td> <?php echo $apt['fname'] . " " . $apt['lname'] ?>
			<!-- <td> <?php echo ucwords($apt['reason_name']) ?> -->
			<td> <?php echo (DateTime::createFromFormat('Y-m-d H:i:s', $apt['date_time']))->format('M. d, Y h:i A') ?>
			<td> <?php echo ucwords($apt['status']) ?>
			<td> 
				<?php if (!empty($apt['scanned_at'])) {
					echo (DateTime::createFromFormat('Y-m-d H:i:s', $apt['scanned_at']))->format('M. d, Y h:i A') ;
				} else {
					echo "N/A";
				}
				?>
			<!-- <td> <?php echo  $apt['scanned_at']?> -->

			<td>
				<div class="button-div">
					<?php if ($apt['status'] == "pending"): ?>
						<button value="<?php echo($apt['appointment_id'])?>-accepted" name="btn" class="button">Accept</button>
						<button value="<?php echo($apt['appointment_id'])?>-declined" name="btn" class="button">Decline</button>
					<?php endif ?>
						<button class="button view-btn" data-apt-id="<?php echo $apt['appointment_id'] ?>">View</button>
						<?php if ($apt['status'] == "accepted" || $apt['status'] == "declined"): ?>
						<button value="<?php echo($apt['appointment_id'])?>-deleted" name="btn" class="button">Delete</button>
						<?php endif ?>
				</div>
				<div class="dialog" data-apt-id="<?php echo $apt['appointment_id'] ?>" title="Appointment Details">
					<div class="style">
						<table>
							<tr>
								<td class="data"><div>Name:</div> <div><div><?php echo $apt[$transaction_type . '_fname']?> <?php echo $apt[$transaction_type . '_lname']?></div>
								</td>
								<td class="data"><div>Email:</div> <div><?php echo  $apt[$transaction_type . '_email']?></div>
								</td>
							</tr>
							<tr>
								<td class="data"><div><?php echo ($transaction_type === "guest" ? "Contact" : "Student")?> Number:</div> <div><?php echo ($transaction_type === "guest" ? $apt['guest_number'] : $apt['student_num'])?></div>
								</td>
								<td class="data"><div>Department:</div> <div><?php echo ucwords($apt['department_name'])?></div>
								</td>
							</tr>
							<tr>
								<td class="data">
									<div><?php echo ($transaction_type=="student") ? "Course:" : "Address:"; ?></div>
									<div><?php echo ($transaction_type=="student") ? $apt[$transaction_type . '_course'] : $apt[$transaction_type . '_address']; ?></div>
								</td>
								<td class="data"><div>Companion:</div> <div><?php echo $apt[$transaction_type . '_companion']?> </div>
								</td>
								<?php if ($transaction_type=="student"): ?>
								<tr>
									<td class="data"><div>Section:</div> <div><?php echo $apt[$transaction_type . '_section']?> </div> 
									</td>
									<td class="data"><div>Reason:</div> <div><?php echo ucwords($apt['reason_name'])?></div>
									</td>
								</tr>
								<?php endif; ?>
							

							</tr>
						</table>
					</div>
				</div>
			<td>
		<tr>
		<?php endforeach; ?>
	</table>
	<div>
		<span>
			<?php if(intval($page) > 1): ?>
			<a href="dashboard.php?page=<?php echo $page-1 ?>"> << </a>
			<?php endif; ?>
		</span>
		<span>
			<?php if ($page < $pageCount): ?>
			<a href="dashboard.php?page=<?php echo $page+1 ?>"> >> </a>
			<?php endif; ?>
		</span>
		<span> page <?php echo $page?> of <?php echo $pageCount ?></span>
	</div>
	</form>
	
</div>