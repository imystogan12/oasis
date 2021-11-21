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
		$filters['status'] = $_GET['status'];
		if ($_GET['status'] === "") {
			unset($filters['status']);
		}
	}

		// Get total count first
		$totalCount = 0;
		$sql = "SELECT COUNT(*) as count " .
				// ' CONCAT(s.student_fname, " ", s.student_lname) as fullname ' .
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

			if (!empty($filters['status'])) {
				$sql .= ' AND apt.status = "' . $filters['status'] . '" ';
			}

			
		}

		$result= $conn->query($sql);
		// var_dump($conn->last_query());

		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
					$totalCount = $row['count'];
				}
		}
		// var_dump($totalCount);

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
				' AND apt.status!="deleted"';

		if (count($filters) > 0) {
			// apply search filters
			if (!empty($filters['fullname'])) {
				$sql .= ' AND (CONCAT(s.student_fname, " ", s.student_lname) LIKE "%' . $filters['fullname'] . '%" '.
				 	'OR CONCAT(g.guest_fname, " ", g.guest_lname) LIKE "%' . $filters['fullname'] . '%") ';
			}

			if (!empty($filters['status'])) {
				$sql .= ' AND apt.status = "' . $filters['status'] . '" ';
			}			
		}

		$sql .= "ORDER BY FIELD (apt.status, 'pending', 'accepted', 'declined'), apt.date_time ASC " .
				" LIMIT " . $perPage . " OFFSET " . $offset;
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

<!-- CSS -->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"/>
<link rel="stylesheet" type="text/css" href="CSS/bootstrap.css"/>
<link rel="stylesheet" type="text/css" href="css(1)-backup/dashboard.css"/>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.rtl.min.css" integrity="sha384-gXt9imSW0VcJVHezoNQsP+TNrjYXoGcrqBZJpry9zJt8PCQjobwmhMGaDHTASo9N" crossorigin="anonymous"/>
<link rel="stylesheet" type="text/css" href="css(1)-backup/jquery-ui.css"/>

 <!-- JS -->


<script type="text/javascript" src="js-backup/jquery.min.js"></script>
<script type="text/javascript" src="js-backup/jquery-ui.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>

<!-- <script type="text/javascript" src="js/bootstrap.bundle.js"></script> -->
<script type="text/javascript" src="js/bootstrap.bundle.min.js"></script>
<script type="text/javascript" src="js/bootstrap.esm.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.28.0/feather.min.js" 
	crossorigin="anonymous"></script>

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
    $('.btn-decline').on("click", function(e) {
		e.preventDefault();
		console.log('nic');
		var btnVal = this.value;
		var split = this.value.split("-");


		$('#declineAptId').val(split[0]);


		$('#reasonmodal').modal('show');
	});
  } );
  </script>

<div class="header-div-oasis">
	<img src="https://i.imgur.com/FTPJl6s.png" style="height:75px;"><?php include "logout.php";?>
</div>

<div class="head2">
	<h2><?php echo ucwords($_SESSION['user']['role']) ?> Dashboard</h2>
</div>
<script>
function showModal(form) {
	console.log('showmodal');
	return true;
}

function showConfirm(form) {
	console.log('form', $(form).serializeArray());

	

    if(confirm('Do you really want to continue?')) {
    	return 'updateAppointment.php?return=dashboard';
    } else {
    	return false;
    }
}

function saveReason() {
	console.log('niccc');
	var reasonVal = $('#reasonModalField').val();
	$('#reasonHidden').val(reasonVal);

	$('#updateAppointment-form').submit();
	// return true;
}

</script>
<!-- <script>
	// $('#reasonmodal').modal('hide');

	$('.btn-decline').on("click", function(e) {
		e.preventDefault();
		console.log('nic');


		// $('#reasonmodal').modal('show');
	});
</script> -->
<!-- <script>
	var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
	var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
 	return new bootstrap.Tooltip(tooltipTriggerEl)
})
</script> -->

<form id="search" method="GET" action="dashboard.php">
	<div class="search">
		
			<div class="fullname-search"><b>Full Name:</b> <input type="text" name="fullname" value="<?php echo isset($_GET['fullname']) ? $_GET['fullname'] : ''  ?>" /></div>
			<div class="status-search"><b>Status:</b>
				<select name="status">
					<option value="">All</option>
					<option value="pending" <?php echo isset($_GET['status']) && $_GET['status'] === 'pending' ? " selected" : '' ?>>Pending</option>
					<option value="accepted" <?php echo isset($_GET['status']) && $_GET['status'] === 'accepted' ? " selected" : '' ?>>Accepted</option>
					<option value="declined" <?php echo isset($_GET['status']) && $_GET['status'] === 'declined' ? " selected" : '' ?>>Declined</option>
				</select></div>
			<div class="search-btn" style="width: 1%;">
				<button style="color: white;" name="search" type="submit" class="btn bg-primary"><span class="material-icons">search</span></button>
			</div>
		
	</div>
	</form>

	<form onsubmit="return showConfirm(this)" action="updateAppointment.php?return=dashboard" method="POST" id="updateAppointment-form">

		<input type="hidden" name="reason" id="reasonHidden"/>
		<input type="hidden" name="declineAptId" id="declineAptId"/>

	<!-- <div class="modal fade" id="dashboardModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  	<div class="modal-dialog">
  	       

   <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"> Indicate Reason</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
					<label class="">Reason:</label>
					<input type="text" name="reason" required >
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success" name="submit">Add</button>
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
      </div>
    </div> -->
</div>
</div>
	<div class="row align-items-center">
    	<div class="col">
      		
    	</div>
    <div class="col-11">
      <table class="table">
		<tr>
			<!-- <th class="galit">ID</th> -->
			<th class="text-center">Last Name</th>
			<th class="text-center">First Name</th>
			<!-- <th class="galit">Email</th> -->
			<th class="text-center">Faculty</th>
			<!-- <th class="galit">Reason</th> -->
			<th class="text-center">Schedule</th>
			<th class="text-center">Status</th>
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
			<td class="text-center"> <?php echo $apt['fname'] . " " . $apt['lname'] ?>
			<!-- <td> <?php echo ucwords($apt['reason_name']) ?> -->
			<td class="text-center">
				<?php 
					$dateTime = DateTime::createFromFormat('Y-m-d H:i:s', $apt['date_time']);
					echo $dateTime->format('M. d, Y h:i A') ?>
			<!-- <td class=" btn btn-primary<?php echo ($apt['status'] == "pending") ? "bg-warning" : 
				($apt['status'] == "declined" ? "bg-danger" : "bg-success") ?>"> <?php echo ucwords($apt['status']) ?> -->
				<td>
					<!-- onclick="return false" -->
					<button 
						type="button"
						style="pointer-events: none; min-width: 100%;"
						class="btn text-light <?php echo ($apt['status'] == "pending") ? "btn-warning" : 
							($apt['status'] == "declined" ? "bg-danger" : "bg-success")  ?>"
					>
						<?php echo ucwords($apt['status']) ?>			
					</button>
				</td>
		

			<td class="text-center"> 
				<?php 
					if (!empty($apt['scanned_at'])) {
					$scannedAt = DateTime::createFromFormat('Y-m-d H:i:s', $apt['scanned_at'])->format('M. d, Y h:i A');
					echo $scannedAt->format('M. d, Y h:i A');
				} else {
					echo "N/A";
				}
				?>
			<!-- <td> <?php echo  $apt['scanned_at']?> -->

			<td>
				<div class="text-center container">
					<?php if ($apt['status'] == "pending"): ?>
						<button type="button" value="<?php echo($apt['appointment_id'])?>-accepted" name="btn" class="btn btn-success text-center" data-bs-toggle="tooltip" data-bs-placement="top" title="Accept"><span class="material-icons">done</span></button>
						<button 
							type="button"
							value="<?php echo($apt['appointment_id'])?>-declined"
							name="btn"
							class="btn btn-decline btn-danger text-center"
							data-bs-toggle="tooltip" data-bs-placement="top" title="Decline" 
						>
						<span class="material-icons" >close</span></button>
					<?php endif ?>
						<button class="btn btn-primary text-center view-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="View"  data-apt-id="<?php echo $apt['appointment_id'] ?>"><span class="material-icons">visibility</span></button>
						<?php if ($apt['status'] == "accepted" || $apt['status'] == "declined"): ?>
						<button value="<?php echo($apt['appointment_id'])?>-deleted" name="btn" class="btn btn-secondary text-center" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete"><span class="material-icons">delete</span></button>
						<?php endif ?>
				</div>
				<div class="dialog" data-apt-id="<?php echo $apt['appointment_id'] ?>" title="Appointment Details">
					<div class="style">
						<table>
							<tr>
								<td class="data"><div>Name:</div> <div><div><?php echo $apt[$transaction_type . '_fname']?> <?php echo $apt[$transaction_type . '_lname']?></div>
								</td>
								
								<td class="data"><div>Type:</div><?php echo ucwords($transaction_type) ?></td>
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
								<tr>
									<td class="data"><div>Email:</div> <div><?php echo  $apt[$transaction_type . '_email']?></div>
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
				<a class="page-link" style="margin-left: 3px;" href="dashboard.php?page=<?php echo $page-1 ?>"> << </a>
				<?php endif; ?>
			</span>
			<span class="pageCount mt-2"> PAGE <?php echo $page?> of <?php echo $pageCount ?></span>
			<span>
				<?php if ($page < $pageCount): ?>
				<a class="page-link" style="margin-left: 5px;" href="dashboard.php?page=<?php echo $page+1 ?>"> >> </a>
				<?php endif; ?>
			</span>
		</div>
    </div>
	</div>
	</div>
	
	</form>
	
	<div id="reasonmodal" class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input type="reason" name="reason" id="reasonModalField"/>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="saveReason()">Save changes</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

</div>