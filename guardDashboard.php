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
		// echo "Connected successfully";
		$sql = 'SELECT apt.user_id, apt.reason, apt.date_time, s.student_lname, s.student_fname, s.student_email, s.student_num, s.student_companion, s.student_course, s.student_section, g.guest_address, g.guest_companion, g.guest_fname, g.guest_lname, g.guest_email, g.guest_number, apt.department, apt.faculty, apt.status, apt.scanned_at, apt.id as appointment_id' .
				' FROM appointment apt' .
				' LEFT JOIN student s ON apt.student_id = s.id ' .
				' LEFT JOIN guest g ON apt.guest_id = g.id ' .
				' WHERE apt.status!="deleted"' ;
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

<div class="head1">
	<header>Appointment Details</header>
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
					<div>Scanned appointment details will show here:</div>
				<div class="dialogpopup" title="Appointment Details" >
					
				</div>
				<input type="text" name="apt_id_scanned" id="apt_id_scanned" value="" />
				<button name="scanned-btn" >Set as scanned</button>
				<div>
			</td>
		</tr>
		<tr>
			<th>Last Name</th>
			<th>First Name</th>
			<th>Email</th>
			<th>Time Scanned</th>
			<th>Action</th>
		</tr>
		<?php foreach($appointment as $apt): ?> 
		<?php $transaction_type = !empty($apt['student_fname']) ? 'student' : 'guest'; ?>
		<tr>
			<td> <?php echo $apt[$transaction_type . '_lname']; ?>
			<td> <?php echo  $apt[$transaction_type . '_fname']?>
			<td> <?php echo  $apt[$transaction_type . '_email']?>
			<td> <?php echo  $apt['scanned_at']?>

			<td>
				<div class="button-div">
						<button class="button">View</button>
				</div>
			<td>
		<tr>
		<?php endforeach; ?>
	</table>
	</form>
	
</div>