<?php 
	include('database.php');
	session_start();
	// echo "<pre>";
	// var_dump($_SESSION);
	// echo "</pre>";

	

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

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

<link rel="stylesheet" type="text/css" href="CSS/bootstrap.css" />
<link rel="stylesheet" type="text/css" href="CSS/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" href="CSS/bootstrap.rtl.css" />
<link rel="stylesheet" type="text/css" href="CSS/bootstrap.rtl.min.css" />
<link rel="stylesheet" type="text/css" href="CSS/bootstrap-grid.css" />
<link rel="stylesheet" type="text/css" href="CSS/bootstrap-grid.min.css" />
<link rel="stylesheet" type="text/css" href="CSS/bootstrap-grid.rtl.css" />
<link rel="stylesheet" type="text/css" href="CSS/bootstrap-grid.rtl.min.css" />
<link rel="stylesheet" type="text/css" href="CSS/bootstrap-reboot.css" />
<link rel="stylesheet" type="text/css" href="CSS/bootstrap-reboot.min.css" />
<link rel="stylesheet" type="text/css" href="CSS/bootstrap-reboot.rtl.css" />
<link rel="stylesheet" type="text/css" href="CSS/bootstrap-reboot.rtl.min.css" />
<link rel="stylesheet" type="text/css" href="CSS/bootstrap-utilities.css" />
<link rel="stylesheet" type="text/css" href="CSS/bootstrap-utilities.min.css" />
<link rel="stylesheet" type="text/css" href="CSS/bootstrap-utilities.rtl.css" />
<link rel="stylesheet" type="text/css" href="CSS/bootstrap-utilities.rtl.min.css" />
<link rel="stylesheet" type="text/css" href="css/jquery-ui.css" />
<link rel="stylesheet" type="text/css" href="CSS/guardDashboard.css" />

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script type="text/javascript" src="js/bootstrap.bundle.js"></script>
<script type="text/javascript" src="js/bootstrap.bundle.min.js"></script>
<script type="text/javascript" src="js/bootstrap.esm.js"></script>
<script type="text/javascript" src="js/bootstrap.esm.min.js"></script>
<script type="text/javascript" src="js/bootstrap.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
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
			// console.log(evt.key);
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

<div class="header-div-oasis">
	<img src="https://i.imgur.com/FTPJl6s.png" style="height:75px;"><?php include "logout.php";?>
</div>

<div class="head2 mt-3">
	<h2>Guard Dashboard</h2>
</div>
	<form action="updateAppointment.php?return=guardDashboard" method="POST">
	<div class="auto">
					<div>
						<p class="note">Scanned appointment details will show here:</p>
					</div>
					<div class="dialogpopup" title="Appointment Details"></div>
					<div class="scanned-details">
					<input class="blank mt-2" type="text" name="apt_id_scanned" id="apt_id_scanned" value="" readonly />
					<button class="btn btn-primary" id="scanned-btn" name="scanned-btn">Set as scanned</button>
				</div>
	</div>
	
	<div class="row align-items-center">
	<div class="col">
		
	</div>

	<div class="col-11">
	<table class="table bs-5">
		
		<tr>
			<th class="col text-center" style="width: 1%;">Last Name</th>
			<th class="col text-center" style="width: 5%;">First Name</th>
			<th class="col text-center" style="width: 1%;">Type</th>
			<!-- <th class="galit">Email</th> -->
			<th class="col text-center" style="width: 5%;">Schedule</th>
			<th class="col text-center" style="width: 2%;">Time Scanned</th>
			<!-- <th class="galit">Action</th> -->
		</tr>
		<?php foreach($appointment as $apt): ?> 
		<?php $transaction_type = !empty($apt['student_fname']) ? 'student' : 'guest'; ?>
		<tr>
			<td class="text-center"> <?php echo $apt[$transaction_type . '_lname']; ?>
			<td class="text-center"> <?php echo  $apt[$transaction_type . '_fname']?>
			<td class="text-center">
				<button 
					class=" btn text-center <?php echo($transaction_type == 'student') ? "btn-primary" : "btn-warning"  ?>" style="width: 80%;" >	<?php echo ucwords($transaction_type) ?>	
				</button>
				</td>
				
			<!-- <td> <?php echo  $apt[$transaction_type . '_email']?> -->
			<td class="text-center"> <?php 
				$aptDateTime = (DateTime::createFromFormat('Y-m-d H:i:s', $apt['date_time']));
				echo $aptDateTime->format('M. d, Y h:i A') ?>
			<td class="text-center"> 
				<?php if (!empty($apt['scanned_at'])) {
					$aptScannedAt = (DateTime::createFromFormat('Y-m-d H:i:s', $apt['scanned_at']));
					echo $aptScannedAt->format('M. d, Y h:i A') ;
				} else {
					echo "N/A";
				}
				?>
			<!-- <td>
				<div class="button-div">
						<button class="button">View</button>
				</div>
			<td> -->
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
				<a class="page-link" style="margin-left: 3px;" href="guardDashboard.php?page=<?php echo $page-1 ?>"> << </a>
				<?php endif; ?>
			</span>
			<span class="pageCount mt-2"> PAGE <?php echo $page?> OF <?php echo $pageCount ?></span>
			<span>
				<?php if ($page < $pageCount): ?>
				<a class="page-link" style="margin-left: 5px;" href="guardDashboard.php?page=<?php echo $page+1 ?>"> >> </a>
				<?php endif; ?>
			</span>
		</div>
    </div>
	</div>
	</div>
	</form>