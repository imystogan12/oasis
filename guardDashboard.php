<?php 
	session_start();
	// echo "<pre>";
	// var_dump($_SESSION);
	// echo "</pre>";

	$servername = "localhost";
		$username = "root";
		$password = "root";
		$dbname = 'oasis';

		$conn = new mysqli($servername, $username, $password, $dbname);
		if ($conn->connect_error) {
  			die("Connection failed: " . $conn->connect_error);
		}

		$totalCount = 0;
		$sql = "SELECT COUNT(*) as count FROM appointment WHERE status != 'deleted' AND status != 'declined'";
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
		$sql = 'SELECT apt.user_id, r.name as reason_name, apt.date_time, s.student_lname, s.student_fname, s.student_email, s.student_num, s.student_companion, s.student_course, s.student_section, g.guest_address, g.guest_companion, g.guest_fname, g.guest_lname, g.guest_email, g.guest_number, d.name as department_name, f.fname, f.lname, apt.status, apt.scanned_at, apt.id as appointment_id' .
				' FROM appointment apt' .
				' JOIN department d ON d.id = apt.department_id ' . 
				' JOIN reason r ON r.id = apt.reason_id ' . 
				' LEFT JOIN student s ON apt.student_id = s.id ' .
				' LEFT JOIN guest g ON apt.guest_id = g.id ' .
				' LEFT JOIN faculty f ON apt.faculty_id = f.id ' .
				' WHERE apt.status!="deleted" AND status!="declined"' . 
				"ORDER BY apt.date_time ASC " .
				" LIMIT " . $perPage . " OFFSET " . $offset;;
		$result = $conn->query($sql);

		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				$appointment[] = $row;
			}
			echo "<pre>";
			// S
			echo "</pre>";
		} else {
			// echo "no appointments";
		}
?>

<link rel="stylesheet" type="text/css" href="css/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="css/guardDashboard.css">
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery-ui.js"></script>


<script type="text/javascript">
	/* $( function() {
	 	 $( '.dialogpopup' ).dialog({
	 	 	autoOpen: false
	 	 });
	 	 $( ".view-btn" ).on( "click", function(e) {
	    	e.preventDefault();
	      // 	$( ".dialogpopup" ).dialog( "close" );
	      	$( '.dialogpopup' ).dialog( "open" );
	    });*/

	 	var barcode = '';
		var interval;
		document.addEventListener('keydown', function (evt) {
			console.log(evt.key);
			if (interval) {
				clearInterval(interval);
			}
			if (evt.code == 'Enter') {
				if (barcode) 
					handleBarcode(barcode);
				barcode = '';
				return;
			}
			if (evt.key != 'Shift') {
				barcode  += evt.key;
				interval = setInterval(function () {
					barcode = ''
				}, 20); 
			}
		});

		function handleBarcode (scanned_barcode) {
			// $( ".dialogpopup" ).dialog( "close" );
			
			document.querySelector('.dialogpopup').innerHTML = scanned_barcode;
			var startSearch = scanned_barcode.indexOf('[[');
			var endSearch = scanned_barcode.indexOf(']]');

			var aptId = scanned_barcode.substring(startSearch, endSearch).substring(2);
			if (!isNaN(aptId)) {
				document.getElementById('apt_id_scanned').value = aptId;
				// document.getElementById('scanned-btn').removeAttribute("hidden");
			} else {
				alert('Malformed data. Please scan again.');
			}



			//$( '.dialogpopup' ).dialog( "open" );
			// $( '.ui-dialog' ).css('display', 'block');
			
			// $( '.dialogpopup' ).dialog( "open" );
			//$('.view-btn').trigger('click');
		}		
//	  } );



	

</script>

<div class="header-div">
	<p class="oasis">OASIS</p><?php include "logout.php";?>
</div>

<div class="head2">
	<h2>Guard Dashboard</h2>
</div>

<div class="div1">
	<form action="updateAppointment.php" method="POST">
	<table class="details info">
		<tr>
			<td colspan=8>
				<div> 
					<div>
						<p class="note">Scanned appointment details will show here:</p>
					</div>
				<div class="dialogpopup" title="Appointment Details" >
				</div>
				<input class="blank" type="text" name="apt_id_scanned" id="apt_id_scanned" value="" readonly />
				<button class="button" id="scanned-btn" name="scanned-btn">Set as scanned</button>
				<div>
			</td>
		</tr>
		<tr>
			<th class="galit">Last Name</th>
			<th class="galit">First Name</th>
		<!-- 	<th class="galit">Email</th> -->
			<th class="galit">Schedule</th>
			<th class="galit">Time Scanned</th>
			<th class="galit">Action</th>
		</tr>
		<?php foreach($appointment as $apt): ?> 
		<?php $transaction_type = !empty($apt['student_fname']) ? 'student' : 'guest'; ?>
		<tr>
			<td> <?php echo $apt[$transaction_type . '_lname']; ?>
			<td> <?php echo  $apt[$transaction_type . '_fname']?>
			<!-- <td> <?php echo  $apt[$transaction_type . '_email']?> -->
			<td> <?php echo (DateTime::createFromFormat('Y-m-d H:i:s', $apt['date_time']))->format('M. d, Y h:i A') ?>
			<td> <?php echo  $apt['scanned_at']?>
			<td>
				<div class="button-div">
						<button class="button">View</button>
				</div>
			<td>
		<tr>
		<?php endforeach; ?>
	</table>
	<div>
		<span>
			<?php if(intval($page) > 1): ?>
			<a href="guardDashboard.php?page=<?php echo $page-1 ?>"> << </a>
			<?php endif; ?>
		</span>
		<span>
			<?php if ($page < $pageCount): ?>
			<a href="guardDashboard.php?page=<?php echo $page+1 ?>"> >> </a>
			<?php endif; ?>
		</span>
		<span> page <?php echo $page?> of <?php echo $pageCount ?></span>
	</div>
	</form>
	
</div>