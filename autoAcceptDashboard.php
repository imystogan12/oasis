<?php
	include('database.php');
	session_start();
	// echo "<pre>";
	// var_dump($_SESSION);
	// echo "</pre>";

	$appointment = [];
	$filters = [];

	if (isset($_GET['search'])) {
		$filters['fullname'] = $_GET['fullname'];
		// $filters['status'] = $_GET['status'];
		// if ($_GET['status'] === "") {
		// 	unset($filters['status']);
		// }
	}

		// $servername = "localhost";
		// $username = "root";
		// $password = "root";
		// $dbname = 'oasis';

		// // $transaction_type = isset($_SESSION['session_type']) ? $_SESSION['session_type'] : 'student';

		// $conn = new mysqli($servername, $username, $password, $dbname);
		// if ($conn->connect_error) {
  // 			die("Connection failed: " . $conn->connect_error);
		// }

		

		// Get total count first
		$totalCount = 0;
		$sql = "SELECT COUNT(*) as count " .
				" FROM appointment apt" .
				' LEFT JOIN student s ON apt.student_id = s.id ' .
				' LEFT JOIN guest g ON apt.guest_id = g.id ' .
				" WHERE apt.status != 'deleted' AND apt.user_id=" . $_SESSION['user']['id'];

		if (count($filters) > 0) {
			// apply search filters
			if (!empty($filters['fullname'])) {
				$sql .= ' AND (CONCAT(s.student_fname, " ", s.student_lname) LIKE "%' . $filters['fullname'] . '%" '.
				 	'OR CONCAT(g.guest_fname, " ", g.guest_lname) LIKE "%' . $filters['fullname'] . '%")';
			}
		}
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
				' AND apt.status!="deleted" ';

		if (count($filters) > 0) {
			// apply search filters
			if (!empty($filters['fullname'])) {
				$sql .= ' AND (CONCAT(s.student_fname, " ", s.student_lname) LIKE "%' . $filters['fullname'] . '%" '.
				 	'OR CONCAT(g.guest_fname, " ", g.guest_lname) LIKE "%' . $filters['fullname'] . '%")';
			}
		}


		$sql .=	"ORDER BY apt.date_time ASC " .
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
		
		// var_dump($appointment);
// usort($data, "sortFunction");
// var_dump($data);
 
 ?>

<!-- CSS -->
<link rel="stylesheet" type="text/css" href="CSS/bootstrap.css" />
<link rel="stylesheet" type="text/css" href="css(1)-backup/autoAcceptDashboard.css" />
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.rtl.min.css" integrity="sha384-gXt9imSW0VcJVHezoNQsP+TNrjYXoGcrqBZJpry9zJt8PCQjobwmhMGaDHTASo9N" crossorigin="anonymous" />
<link rel="stylesheet" type="text/css" href="css(1)-backup/jquery-ui.css" />

<!-- JS -->
 <script type="text/javascript" src="js-backup/jquery.min.js"></script>
<script type="text/javascript" src="js-backup/jquery-ui.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.28.0/feather.min.js" 
	crossorigin="anonymous">
</script>
<!-- <script type="text/javascript" src="js/bootstrap.bundle.js"></script> -->
<script type="text/javascript" src="js/bootstrap.bundle.min.js"></script>
<script type="text/javascript" src="js/bootstrap.esm.js"></script>
<!-- <script type="text/javascript" src="js/bootstrap.esm.min.js"></script> -->
<!-- <script type="text/javascript" src="js/bootstrap.js"></script> -->
<script type="text/javascript" src="js/bootstrap.min.js"></script>
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
  <script>
function showConfirm(form) {
    if(confirm('Do you really want to continue?')) {
    	return 'updateAppointment.php?return=autoAcceptDashboard';
    } else {
    	return false;
    }
}
</script>


<div class="header-div-oasis">
	<img src="https://i.imgur.com/FTPJl6s.png" style="height:75px;"><?php include "logout.php";?>
</div>

<div class="head2">
	<h2><?php echo ucwords($_SESSION['user']['role']) ?> Dashboard</h2>
</div>
<form id="search" method="GET" action="autoAcceptDashboard.php">
<div class="search">
		
			<div class="fullname-search"><b>Full Name:</b> <input type="text" name="fullname" value="<?php echo isset($_GET['fullname']) ? $_GET['fullname'] : ''  ?>" /></div>
			<!-- <div class="status-search"><b>Status:</b>
				<select name="status">
					<option value="">All</option>
					<option value="pending" <?php echo isset($_GET['status']) && $_GET['status'] === 'pending' ? " selected" : '' ?>>Pending</option>
					<option value="accepted" <?php echo isset($_GET['status']) && $_GET['status'] === 'accepted' ? " selected" : '' ?>>Accepted</option>
					<option value="declined" <?php echo isset($_GET['status']) && $_GET['status'] === 'declined' ? " selected" : '' ?>>Declined</option>
				</select></div> -->
			<div class="search-btn" style="width: 1%;">
				<button style="color: white;" name="search" type="submit" class="btn bg-primary"><span class="material-icons">search</span></button>
			</div>
		
	</div>
</form>
<div class="row align-items-center">
	<div class="col">
      		
    </div>
<div class="col-12">
	<form onsubmit="return showConfirm(this)" action="updateAppointment.php?return=autoAcceptDashboard" method="POST" id="updateAppointment-form">
	<table class="table">
		<tr>
			<th class="text-center">Last Name</th>
			<th class="text-center">First Name</th>
			<!-- <th>Email</th>
			<th>Reason</th> -->
			<th class="text-center">Schedule</th>
			<th class="text-center">Time Arrived</th>
			<th class="text-center">Action</th>
		</tr>
		<?php foreach($appointment as $apt): ?> 
		<?php $transaction_type = !empty($apt['student_fname']) ? 'student' : 'guest'; ?>
		<tr>
			<!-- <td> <?php echo $apt['appointment_id']; ?> -->
			<td class="text-center"> <?php echo $apt[$transaction_type . '_lname']; ?>
			<td class="text-center"> <?php echo  $apt[$transaction_type . '_fname']?>
			 <!-- <td> <?php echo  $apt[$transaction_type . '_email']?> -->
			<!-- <td> <?php echo $apt['fname'] . " " . $apt['lname'] ?> -->
			<!-- <td> <?php echo ucwords($apt['reason_name']) ?> -->
			<td class="text-center"> <?php echo (DateTime::createFromFormat('Y-m-d H:i:s', $apt['date_time']))->format('M. d, Y h:i A') ?>
			<td class="text-center"> 
				<?php if (!empty($apt['scanned_at'])) {
					echo (DateTime::createFromFormat('Y-m-d H:i:s', $apt['scanned_at']))->format('M. d, Y h:i A') ;
				} else {
					echo "N/A";
				}
				?>
			
				<td class="text-center">
					<div>
						<button class="btn btn-primary text-center view-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="View" data-apt-id="<?php echo $apt['appointment_id'] ?>"><span class="material-icons">visibility</span></button>
								<?php if ($apt['status'] == "accepted" || $apt['status'] == "declined"): ?>
						<button value="<?php echo($apt['appointment_id'])?>-deleted" name="btn" 		class="btn btn-secondary text-center" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete"><span class="material-icons">delete</span></button>
							<?php endif; ?>

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
				</td>

			</tr>


		<?php endforeach; ?>
		 <div class="col">
      
    
    </div>		
	</table>
</form>
	

    <div class="container">
    <div class="pagination text-center">

    	<div class="col">
      		
    	</div>
    	<div class="col">			
		
		</div>

	<div class="col-auto">
      	<div class="pagination text-center">
			<span>
				<?php if(intval($page) > 1): ?>
				<a class="page-link" style="margin-left: 3px;" href="dashboard.php?page=<?php echo $page-1 ?>"> << </a>
				<?php endif; ?>
			</span>
			<span class="pageCount mt-2"> PAGE <?php echo $page?> OF <?php echo $pageCount ?></span>
			<span>
				<?php if ($page < $pageCount): ?>
				<a class="page-link" style="margin-left: 5px;" href="dashboard.php?page=<?php echo $page+1 ?>"> >> </a>
				<?php endif; ?>
			</span>
		</div>
    </div>
</div>
</div>
</div>
</div>