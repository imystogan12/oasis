<?php
	session_start();
	// unset($_SESSION['appointment']);

	$transaction_type = isset($_SESSION['session_type']) ? $_SESSION['session_type'] : 'student';

	if (isset($_SESSION['appt_form']['monday'])) {
		$monday = $_SESSION['appt_form']['monday'];
		$tuesday = $_SESSION['appt_form']['tuesday'];
		$wednesday = $_SESSION['appt_form']['wednesday'];
		$thursday = $_SESSION['appt_form']['thursday'];
		$friday = $_SESSION['appt_form']['friday'];
		$saturday = $_SESSION['appt_form']['saturday'];
	
	} else {
		$monday = date('m-d-Y', strtotime( 'monday this week' ) );
		$tuesday = date('m-d-Y', strtotime( 'tuesday this week' ) );
		$wednesday = date('m-d-Y', strtotime( 'wednesday this week' ) );
		$thursday = date('m-d-Y', strtotime( 'thursday this week' ) );
		$friday = date('m-d-Y', strtotime( 'friday this week' ) );
		$saturday = date('m-d-Y', strtotime( 'saturday this week' ) );
	}
	
	if (isset($_POST['next']) || isset($_POST['prev'])) {
		$monday_timestamp = DateTime::createFromFormat('m-d-Y', $monday)->getTimestamp();
   		$tuesday_timestamp = DateTime::createFromFormat('m-d-Y', $tuesday)->getTimestamp();
   		$wednesday_timestamp = DateTime::createFromFormat('m-d-Y', $wednesday)->getTimestamp();
   		$thursday_timestamp = DateTime::createFromFormat('m-d-Y', $thursday)->getTimestamp();
   		$friday_timestamp = DateTime::createFromFormat('m-d-Y', $friday)->getTimestamp();
   		$saturday_timestamp = DateTime::createFromFormat('m-d-Y', $saturday)->getTimestamp();

   		if(isset($_POST['next'])) {
	   		$monday = date('m-d-Y', strtotime("next monday", $monday_timestamp));
	   		$tuesday = date('m-d-Y', strtotime("next tuesday", $tuesday_timestamp));
	   		$wednesday = date('m-d-Y', strtotime("next wednesday", $wednesday_timestamp));
	   		$thursday = date('m-d-Y', strtotime("next thursday", $thursday_timestamp));
	   		$friday = date('m-d-Y', strtotime("next friday", $friday_timestamp));
	   		$saturday = date('m-d-Y', strtotime("next saturday", $saturday_timestamp));	
		
		} elseif (isset($_POST['prev'])) {
			$monday = date('m-d-Y', strtotime("previous monday", $monday_timestamp));
	   		$tuesday = date('m-d-Y', strtotime("previous tuesday", $tuesday_timestamp));
	   		$wednesday = date('m-d-Y', strtotime("previous wednesday", $wednesday_timestamp));
	   		$thursday = date('m-d-Y', strtotime("previous thursday", $thursday_timestamp));
	   		$friday = date('m-d-Y', strtotime("previous friday", $friday_timestamp));
	   		$saturday = date('m-d-Y', strtotime("previous saturday", $saturday_timestamp));	
		}

		$_SESSION['appointment']['schedule'] = $_POST['schedule'] ?? null;
		$_SESSION['appointment']['department'] = $_POST['department'] ?? null;
		$_SESSION['appointment']['reason'] = $_POST['reason'] ?? null;
		$_SESSION['appointment']['faculty'] = $_POST['faculty'] ?? null;

		$_SESSION['appt_form']['monday'] = $monday;
		$_SESSION['appt_form']['tuesday'] = $tuesday;
		$_SESSION['appt_form']['wednesday'] = $wednesday;
		$_SESSION['appt_form']['thursday'] = $thursday;
		$_SESSION['appt_form']['friday'] = $friday;
		$_SESSION['appt_form']['saturday'] = $saturday;
	} else {
		unset($_SESSION['appt_form']['monday']);
		unset($_SESSION['appt_form']['tuesday']);
		unset($_SESSION['appt_form']['wednesday']);
		unset($_SESSION['appt_form']['thursday']);
		unset($_SESSION['appt_form']['friday']);
		unset($_SESSION['appt_form']['saturday']);
	}

	$servername = "localhost";
		$username = "root";
		$password = "root";
		$dbname = 'oasis';

		$conn = new mysqli($servername, $username, $password, $dbname);
		if ($conn->connect_error) {
  			die("Connection failed: " . $conn->connect_error);
		}
		$mondayTimeStamp = DateTime::createFromFormat('m-d-Y', $monday);
		$saturdayTimeStamp = DateTime::createFromFormat('m-d-Y', $saturday);

		$sql = 'SELECT * FROM appointment WHERE date_time >= "'. $mondayTimeStamp->format('Y-m-d') .' 00:00:00" AND '.
			' date_time <= "'. $saturdayTimeStamp->format('Y-m-d') .' 23:59:59" AND status="accepted"';

			$result = $conn->query($sql);

			// echo "<pre>";
			// var_dump($sql);
			// var_dump($result->fetch_assoc());
			// echo "</pre>";

		$aptGroups = [];
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				$aptGroups[$row['date_time']][] = $row;
			}
			echo "<pre>";
			// S
			echo "</pre>";
		} else {
			// echo "no appointments";
		}

		echo "<pre>";
			//var_dump($aptGroups);
			echo "</pre>";

	$departments = [];
	$sql = 'SELECT * from department WHERE deleted_at IS NULL ORDER BY name ASC';
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$departments[$row['id']] = $row;
		}
	}

	$reasons = [];
	$sql = 'SELECT * from reason WHERE deleted_at IS NULL';
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$reasons[] = $row;
		}
	}

	$faculties = [];
	$sql = 'SELECT * from faculty WHERE deleted_at IS NULL ORDER BY lname ASC';
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$faculties[] = $row;
		}
	}

	// echo "<pre>";
	// 		var_dump($departments);
	// 		echo "</pre>";



	if (isset($_POST['schedule'])) {
		// echo "<pre>";
		// var_dump($_POST);
		// echo "</pre>";
		$_SESSION['appointment']['schedule'] = $_POST['schedule'] ?? null;
		$_SESSION['appointment']['department'] = $_POST['department'] ?? null;
		$_SESSION['appointment']['reason'] = $_POST['reason'] ?? null;
		$_SESSION['appointment']['faculty'] = $_POST['faculty'] ?? null;
	}

	if (isset($_POST['submit'])) {

		$_SESSION['appointment']['department'] = !empty($_POST['department']) ? $_POST['department'] : $_SESSION['appointment']['department'];
		 $_SESSION['appointment']['reason'] = !empty($_POST['reason']) ? $_POST['reason'] : $_SESSION['appointment']['reason'];
		 $_SESSION['appointment']['faculty'] = !empty($_POST['faculty']) ? $_POST['faculty'] : ($_SESSION['appointment']['faculty'] ?? null);


		 // echo "<pre>";
		 // var_dump('nica');
			// var_dump($_SESSION['appointment']['schedule']);
			// echo "</pre>";


		if (empty($_SESSION['appointment']['schedule'])) {
			// show error
			$_SESSION['appointment']['error']['schedule'] = "Please select schedule";
			header("Location: book-appointment.php");
			exit;

		} else {
			unset($_SESSION['appointment']['error']['schedule']);
		}

		$dateTimeStamp = DateTime::createFromFormat('m-d-Y H:i:s', $_SESSION['appointment']['schedule']);

		$facultyDepartmentId = null;
		$sql = 'SELECT * from department WHERE value="faculty"';
		$result = $conn->query($sql);

		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				$facultyDepartmentId = $row['id'];
			}
		}
		

		$faculty_name = null;
		$user_id = null;
		$faculty_id = null;
		if (!empty($_SESSION['appointment']['department']) && ($_SESSION['appointment']['department'] != $facultyDepartmentId)) {
			// Get user report id
			$sqlFetchUserReportId = "SELECT * from user where role='" . $_SESSION['appointment']['department'] . "'";
			$resultUserReportId = $conn->query($sqlFetchUserReportId);
			if ($resultUserReportId->num_rows > 0) {
			while($row = $resultUserReportId->fetch_assoc()) {
					$user_id = $row['id'];
				}
			}
		} else {
			// Get user report id
			$sqlFetchUserReportId = "SELECT * from faculty where id=" . $_SESSION['appointment']['faculty'];
			$resultUserReportId = $conn->query($sqlFetchUserReportId);
			if ($resultUserReportId->num_rows > 0) {
			while($row = $resultUserReportId->fetch_assoc()) {
					$user_id = $row['user_report_id'];
					$faculty_id = $row['id'];
				}
			}
		}

		if (empty($faculty_id)) {
			// Appointment is not on faculty, get department PIC as user_id
			$getReportingUserSQL = "SELECT * FROM department where id=" . $_SESSION['appointment']['department'];
			$getReportingUserResult = $conn->query($getReportingUserSQL);

			if ($getReportingUserResult->num_rows > 0) {
				while($row = $getReportingUserResult->fetch_assoc()) {
					var_dump($row);
					$user_id = $row['department_pic_id'];
				}
			}
		}

   		$_SESSION[$transaction_type]['appointment'] = [
			'department' => $_SESSION['appointment']['department'],
			'reason' => $_SESSION['appointment']['reason'],
			'user_id' => $user_id,
			'student_id' => $_SESSION['student']['student_id'],
			'date_time' => $_SESSION['appointment']['schedule'],
			'faculty_id' => $faculty_id,
		];
   		header("Location: confirmationPage.php");
   		exit;
   	}   	

	// echo "<pre>";
	// var_dump($_SESSION);
	// echo "</pre>";
?>
<div class="header-div">
	<p class="oasis">OASIS</p>
</div>

<script type="text/javascript" src="js/jquery.min.js"></script>

<script type="text/javascript">
	$(document).ready(function(){
		var noteText = '*NOTE: Users who wish to enroll and are not sure which department to go to must book an appointment with the admissions office for further assistance.';
		var facultyNoteText = '*NOTE: Please consult with your teacher regarding their available consultation hours prior to booking an appointment.';

		$('#note-block').text(noteText);

		$("#department").on("change", function(){
			var deptValue = $(this).find(":selected").data('dept-value');
			$("#reason").find("option." + deptValue);

			$("#reason").find("option." + deptValue).show(); // option.cashier
			$("#reason").find("option:not(." + deptValue + ")").hide(); // option:not(.cashier)
			$("#reason").val('');
			
			if (deptValue=="faculty"){
				$("#faculty").prop('disabled', false);
				$('#note-block').text(facultyNoteText);
			}
			else {
				$("#faculty").prop('disabled', true);
				$('#note-block').text(noteText);
			}
			$("#faculty").val('');

		});

		$(".more").on("click", function(e){
			e.preventDefault();

			// Get day
			var classString = $(this).attr("class");
			var classArray = classString.split(' ');
			var moreDay = classArray[1];
			var moreDayArray = moreDay.split('-');
			var day = moreDayArray[1];

			console.log(day);

			// Check if displayed is am or PM
			var dayCount = $('.time-am-' + day + ':visible').length;
			console.log(dayCount);
			var isMorning = dayCount > 0;
			console.log(isMorning);

			if (isMorning) {
				$('.time-pm-' + day).show();
				$('.time-am-' + day).hide();
			} else {
				$('.time-am-' + day).show();
				$('.time-pm-' + day).hide();
			}

		})

		// $("#cancel").on("click", function(e){
		// 	e.preventDefault();
		// })
	});

</script>

<link rel="stylesheet" type="text/css" href="css/bookAppointment.css">

<body>
	<form action="book-appointment.php" method="POST">
		<div class="main">
			<div class='content'>
				<div class="head">
					<header> Book Appointment </header>
				</div>
				<div class="apt-form">
					<div class="left">
						<div class="div">
							<div>
								<label for="choice">Department</label>
							</div>
							<div>
								<select class="dept" name="department" id="department" required>
									<option value=""></option>
								<?php foreach ($departments as $dept): ?>
									<option
										data-dept-value="<?php echo $dept['value'] ?>"
										value="<?php echo $dept['id'] ?>"
										<?php if (!empty($_SESSION['appointment']['department']) && $_SESSION['appointment']['department'] === $dept['id']) echo " selected" 
										?>
									>
										<?php echo $dept['name']; ?>
									</option>
								<?php endforeach ?>
								</select>
							</div>
						</div>
						<div class="div">
							<div>
								<label for="choice" required>Reason</label>
							</div>
							<div>
								<select class="reason" name="reason" id="reason" required>
									<option value=""></option>
									<?php foreach ($reasons as $reason): ?>
										<option class="<?php echo $departments[$reason['dept_id']]['value'] ?>" value="<?php echo $reason['id'] ?>"
											<?php if (empty($_SESSION['appointment']['reason']) || ($_SESSION['appointment']['department'] !== $departments[$reason['dept_id']]['id'])) echo " hidden " ?> 
										<?php if (!empty($_SESSION['appointment']['reason']) && ($_SESSION['appointment']['reason'] === $reason['id'])) echo " selected" ?>
										>
											<?php echo $reason['name']; ?>
										</option>
									<?php endforeach ?>
								</select>
							</div>
						</div>
						<div class="div">
							<div>
								<label for="choice">Faculty</label>
							</div>
							<div>
								<select class="faculty" name="faculty" id="faculty" required
									<?php echo (empty($_SESSION['appointment']['faculty']) ? ' disabled' : '') ?>
								>
									<option value=""></option>
									<?php foreach ($faculties as $faculty): ?>
										<option value="<?php echo $faculty['id'] ?>"
											<?php if (!empty($_SESSION['appointment']['faculty']) && ($_SESSION['appointment']['faculty'] === $faculty['id'])) echo " selected" ?>
										>
											<?php echo $faculty['lname']. ',' . " " . $faculty['fname'] ?>

										</option>
									<?php endforeach ?>
								</select>
							</div>
						</div>
						<div class="error">
							<?php echo $_SESSION['appointment']['error']['schedule'] ?? '' ?>
						</div>
						<div class="submit-cancel">
							<button class="submit-btn" name="submit">Submit</button>
							<a href="<?php echo $transaction_type  ?>UI.php" class="cancel">Cancel</a>
						</div>
						<div>
							<p id='note-block' class="note needed"></p>
						</div>
					</div>
					<div class="right">
						<table class="day">
							<tr class="box">
								<th><button class="prev-btn" name="prev">prev</button></th>
								<th class="date">Monday <br><?php echo $monday?><br></th>
								<th class="date">Tuesday <br><?php echo $tuesday?><br></th>
								<th class="date">Wednesday <br><?php echo $wednesday?><br></th>
								<th class="date">Thursday <br><?php echo $thursday?><br></th>
								<th class="date">Friday <br><?php echo $friday?><br></th>
								<th class="date">Saturday <br><?php echo $saturday?><br></th>
								<th><button class="next-btn" name="next">next</button></th>
							</tr>
								<td></td>
								<td>
									<?php
										$mondayTimeStamp = DateTime::createFromFormat('m-d-Y', $monday);
										$mondayYmd = $mondayTimeStamp->format('Y-m-d');
										$mondayYmdTime = !empty($aptGroups[$mondayYmd . " 08:00:00"]) ?
											$aptGroups[$mondayYmd . " 08:00:00"] : [];
										$isFull = count($mondayYmdTime) >= 10;
										$isPastDate = date('Y-m-d') > $mondayYmd;
									?>
									<button name ="schedule" value="<?php echo $monday . " 08:00:00" ?>"
										class="time time-am time-am-mon 
											<?php if (isset($_SESSION['appointment']['schedule']) &&
												$monday . " 08:00:00" == $_SESSION['appointment']['schedule']) 
												echo 'selected'; ?>
											<?php echo ($isFull) ? " full" : ''; ?>
										"
										<?php echo ($isFull || $isPastDate) ? " disabled" : '' ?>
									>
										
										<?php echo (!$isFull) ? "08:00 AM - 09:00 AM" : "FULL" ?>
									</button>

									<?php
										$mondayTimeStamp = DateTime::createFromFormat('m-d-Y', $monday);
										$mondayYmd = $mondayTimeStamp->format('Y-m-d');
										$mondayYmdTime = !empty($aptGroups[$mondayYmd . " 13:00:00"]) ?
											$aptGroups[$mondayYmd . " 13:00:00"] : [];
										$isFull = count($mondayYmdTime) >= 10;
										$isPastDate = date('Y-m-d') > $mondayYmd;
									?>
									<button name ="schedule" value="<?php echo $monday . " 13:00:00" ?>"
										class="time time-pm time-pm-mon 
											<?php if (isset($_SESSION['appointment']['schedule']) &&
												$monday . " 13:00:00" == $_SESSION['appointment']['schedule']) 
												echo 'selected'; ?>
											<?php echo ($isFull) ? " full" : ''; ?>
										"
										<?php echo ($isFull || $isPastDate) ? " disabled" : '' ?>
									>
										
										<?php echo (!$isFull) ? "01:00 PM - 02:00 PM" : "FULL" ?>
									</button>
								</td>
								<td>
									<?php
										$tuesdayTimeStamp = DateTime::createFromFormat('m-d-Y', $tuesday);
										$tuesdayYmd = $tuesdayTimeStamp->format('Y-m-d');
										$tuesdayYmdTime = !empty($aptGroups[$tuesdayYmd . " 08:00:00"]) ?
											$aptGroups[$tuesdayYmd . " 08:00:00"] : [];
										$isFull = count($tuesdayYmdTime) >= 10;
										$isPastDate = date('Y-m-d') > $tuesdayYmd;
									?>
									<button name ="schedule" value="<?php echo $tuesday . " 08:00:00" ?>"
										class="time time-am time-am-tue 
											<?php if (isset($_SESSION['appointment']['schedule']) &&
												$tuesday . " 08:00:00" == $_SESSION['appointment']['schedule']) 
												echo 'selected'; ?>
											<?php echo ($isFull) ? " full" : ''; ?>
										"
										<?php echo ($isFull || $isPastDate) ? " disabled" : '' ?>
									>
										
										<?php echo (!$isFull) ? "08:00 AM - 09:00 AM" : "FULL" ?>
									</button>

									<?php
										$tuesdayTimeStamp = DateTime::createFromFormat('m-d-Y', $tuesday);
										$tuesdayYmd = $tuesdayTimeStamp->format('Y-m-d');
										$tuesdayYmdTime = !empty($aptGroups[$tuesdayYmd . " 13:00:00"]) ?
											$aptGroups[$tuesdayYmd . " 13:00:00"] : [];
										$isFull = count($tuesdayYmdTime) >= 10;
										$isPastDate = date('Y-m-d') > $tuesdayYmd;
									?>
									<button name ="schedule" value="<?php echo $tuesday . " 13:00:00" ?>"
										class="time time-pm time-pm-tue 
											<?php if (isset($_SESSION['appointment']['schedule']) &&
												$tuesday . " 13:00:00" == $_SESSION['appointment']['schedule']) 
												echo 'selected'; ?>
											<?php echo ($isFull) ? " full" : ''; ?>
										"
										<?php echo ($isFull || $isPastDate) ? " disabled" : '' ?>
									>
										
										<?php echo (!$isFull) ? "01:00 PM - 02:00 PM" : "FULL" ?>
									</button>
								</td>
								<td>
									<?php
										$wednesdayTimeStamp = DateTime::createFromFormat('m-d-Y', $wednesday);
										$wednesdayYmd = $wednesdayTimeStamp->format('Y-m-d');
										$wednesdayYmdTime = !empty($aptGroups[$wednesdayYmd . " 08:00:00"]) ?
											$aptGroups[$wednesdayYmd . " 08:00:00"] : [];
										$isFull = count($wednesdayYmdTime) >= 10;
										$isPastDate = date('Y-m-d') > $wednesdayYmd;
									?>
									<button name ="schedule" value="<?php echo $wednesday . " 08:00:00" ?>"
										class="time time-am time-am-wed 
											<?php if (isset($_SESSION['appointment']['schedule']) &&
												$wednesday . " 08:00:00" == $_SESSION['appointment']['schedule']) 
												echo 'selected'; ?>
											<?php echo ($isFull) ? " full" : ''; ?>
										"
										<?php echo ($isFull || $isPastDate) ? " disabled" : '' ?>
									>
										
										<?php echo (!$isFull) ? "08:00 AM - 09:00 AM" : "FULL" ?>
									</button>

									<?php
										$wednesdayTimeStamp = DateTime::createFromFormat('m-d-Y', $wednesday);
										$wednesdayYmd = $wednesdayTimeStamp->format('Y-m-d');
										$wednesdayYmdTime = !empty($aptGroups[$wednesdayYmd . " 13:00:00"]) ?
											$aptGroups[$wednesdayYmd . " 13:00:00"] : [];
										$isFull = count($wednesdayYmdTime) >= 10;
										$isPastDate = date('Y-m-d') > $wednesdayYmd;
									?>
									<button name ="schedule" value="<?php echo $wednesday . " 13:00:00" ?>"
										class="time time-pm time-pm-wed 
											<?php if (isset($_SESSION['appointment']['schedule']) &&
												$wednesday . " 13:00:00" == $_SESSION['appointment']['schedule']) 
												echo 'selected'; ?>
											<?php echo ($isFull) ? " full" : ''; ?>
										"
										<?php echo ($isFull || $isPastDate) ? " disabled" : '' ?>
									>
										
										<?php echo (!$isFull) ? "01:00 PM - 02:00 PM" : "FULL" ?>
									</button>
								</td>
								<td>
									<?php
										$thursdayTimeStamp = DateTime::createFromFormat('m-d-Y', $thursday);
										$thursdayYmd = $thursdayTimeStamp->format('Y-m-d');
										$thursdayYmdTime = !empty($aptGroups[$thursdayYmd . " 08:00:00"]) ?
											$aptGroups[$thursdayYmd . " 08:00:00"] : [];
										$isFull = count($thursdayYmdTime) >= 10;
										$isPastDate = date('Y-m-d') > $thursdayYmd;
									?>
									<button name ="schedule" value="<?php echo $thursday . " 08:00:00" ?>"
										class="time time-am time-am-thu 
											<?php if (isset($_SESSION['appointment']['schedule']) &&
												$thursday . " 08:00:00" == $_SESSION['appointment']['schedule']) 
												echo 'selected'; ?>
											<?php echo ($isFull) ? " full" : ''; ?>
										"
										<?php echo ($isFull || $isPastDate) ? " disabled" : '' ?>
									>
										
										<?php echo (!$isFull) ? "08:00 AM - 09:00 AM" : "FULL" ?>
									</button>
									<?php
										$thursdayTimeStamp = DateTime::createFromFormat('m-d-Y', $thursday);
										$thursdayYmd = $thursdayTimeStamp->format('Y-m-d');
										$thursdayYmdTime = !empty($aptGroups[$thursdayYmd . " 13:00:00"]) ?
											$aptGroups[$thursdayYmd . " 13:00:00"] : [];
										$isFull = count($thursdayYmdTime) >= 10;
										$isPastDate = date('Y-m-d') > $thursdayYmd;
									?>
									<button name ="schedule" value="<?php echo $thursday . " 13:00:00" ?>"
										class="time time-pm time-pm-thu 
											<?php if (isset($_SESSION['appointment']['schedule']) &&
												$thursday . " 13:00:00" == $_SESSION['appointment']['schedule']) 
												echo 'selected'; ?>
											<?php echo ($isFull) ? " full" : ''; ?>
										"
										<?php echo ($isFull || $isPastDate) ? " disabled" : '' ?>
									>
										
										<?php echo (!$isFull) ? "01:00 PM - 02:00 PM" : "FULL" ?>
									</button>
								</td>
								<td>
									<?php
										$fridayTimeStamp = DateTime::createFromFormat('m-d-Y', $friday);
										$fridayYmd = $fridayTimeStamp->format('Y-m-d');
										$fridayYmdTime = !empty($aptGroups[$fridayYmd . " 08:00:00"]) ?
											$aptGroups[$fridayYmd . " 08:00:00"] : [];
										$isFull = count($fridayYmdTime) >= 10;
										$isPastDate = date('Y-m-d') > $fridayYmd;
									?>
									<button name ="schedule" value="<?php echo $friday . " 08:00:00" ?>"
										class="time time-am time-am-fri 
											<?php if (isset($_SESSION['appointment']['schedule']) &&
												$friday . " 08:00:00" == $_SESSION['appointment']['schedule']) 
												echo 'selected'; ?>
											<?php echo ($isFull) ? " full" : ''; ?>
										"
										<?php echo ($isFull || $isPastDate) ? " disabled" : '' ?>
									>
										
										<?php echo (!$isFull) ? "08:00 AM - 09:00 AM" : "FULL" ?>
									</button>
									<?php
										$fridayTimeStamp = DateTime::createFromFormat('m-d-Y', $friday);
										$fridayYmd = $fridayTimeStamp->format('Y-m-d');
										$fridayYmdTime = !empty($aptGroups[$fridayYmd . " 13:00:00"]) ?
											$aptGroups[$fridayYmd . " 13:00:00"] : [];
										$isFull = count($fridayYmdTime) >= 10;
										$isPastDate = date('Y-m-d') > $fridayYmd;
									?>
									<button name ="schedule" value="<?php echo $friday . " 13:00:00" ?>"
										class="time time-pm time-pm-fri 
											<?php if (isset($_SESSION['appointment']['schedule']) &&
												$friday . " 13:00:00" == $_SESSION['appointment']['schedule']) 
												echo 'selected'; ?>
											<?php echo ($isFull) ? " full" : ''; ?>
										"
										<?php echo ($isFull || $isPastDate) ? " disabled" : '' ?>
									>
										
										<?php echo (!$isFull) ? "01:00 PM - 02:00 PM" : "FULL" ?>
									</button>
								</td>
								<td>
									<?php
										$saturdayTimeStamp = DateTime::createFromFormat('m-d-Y', $saturday);
										$saturdayYmd = $saturdayTimeStamp->format('Y-m-d');
										$saturdayYmdTime = !empty($aptGroups[$saturdayYmd . " 08:00:00"]) ?
											$aptGroups[$saturdayYmd . " 08:00:00"] : [];
										$isFull = count($saturdayYmdTime) >= 10;
										$isPastDate = date('Y-m-d') > $saturdayYmd;
									?>
									<button name ="schedule" value="<?php echo $saturday . " 08:00:00" ?>"
										class="time time-am time-am-sat 
											<?php if (isset($_SESSION['appointment']['schedule']) &&
												$saturday . " 08:00:00" == $_SESSION['appointment']['schedule']) 
												echo 'selected'; ?>
											<?php echo ($isFull) ? " full" : ''; ?>
										"
										<?php echo ($isFull || $isPastDate) ? " disabled" : '' ?>
									>
										
										<?php echo (!$isFull) ? "08:00 AM - 09:00 AM" : "FULL" ?>
									</button>
									<?php
										$saturdayTimeStamp = DateTime::createFromFormat('m-d-Y', $saturday);
										$saturdayYmd = $saturdayTimeStamp->format('Y-m-d');
										$saturdayYmdTime = !empty($aptGroups[$saturdayYmd . " 13:00:00"]) ?
											$aptGroups[$fridayYmd . " 13:00:00"] : [];
										$isFull = count($saturdayYmdTime) >= 10;
										$isPastDate = date('Y-m-d') > $saturdayYmd;
									?>
									<button name ="schedule" value="<?php echo $saturday . " 13:00:00" ?>"
										class="time time-pm time-pm-sat 
											<?php if (isset($_SESSION['appointment']['schedule']) &&
												$saturday . " 13:00:00" == $_SESSION['appointment']['schedule']) 
												echo 'selected'; ?>
											<?php echo ($isFull) ? " full" : ''; ?>
										"
										<?php echo ($isFull || $isPastDate) ? " disabled" : '' ?>
									>
										
										<?php echo (!$isFull) ? "01:00 PM - 02:00 PM" : "FULL" ?>
									</button>
								</td>
								<td></td>
							<tr>
								<td></td>
								<td>
									<?php
										$mondayTimeStamp = DateTime::createFromFormat('m-d-Y', $monday);
										$mondayYmd = $mondayTimeStamp->format('Y-m-d');
										$mondayYmdTime = !empty($aptGroups[$mondayYmd . " 09:00:00"]) ?
											$aptGroups[$mondayYmd . " 09:00:00"] : [];
										$isFull = count($mondayYmdTime) >= 10;
										$isPastDate = date('Y-m-d') > $mondayYmd;
									?>
									<button name ="schedule" value="<?php echo $monday . " 09:00:00" ?>"
										class="time time-am time-am-mon 
											<?php if (isset($_SESSION['appointment']['schedule']) &&
												$monday . " 09:00:00" == $_SESSION['appointment']['schedule']) 
												echo 'selected'; ?>
											<?php echo ($isFull) ? " full" : ''; ?>
										"
										<?php echo ($isFull || $isPastDate) ? " disabled" : '' ?>
									>
										
										<?php echo (!$isFull) ? "09:00 AM - 10:00 AM" : "FULL" ?>
									</button>
									<?php
										$mondayTimeStamp = DateTime::createFromFormat('m-d-Y', $monday);
										$mondayYmd = $mondayTimeStamp->format('Y-m-d');
										$mondayYmdTime = !empty($aptGroups[$mondayYmd . " 14:00:00"]) ?
											$aptGroups[$mondayYmd . " 14:00:00"] : [];
										$isFull = count($mondayYmdTime) >= 10;
										$isPastDate = date('Y-m-d') > $mondayYmd;
									?>
									<button name ="schedule" value="<?php echo $monday . " 14:00:00" ?>"
										class="time time-pm time-pm-mon 
											<?php if (isset($_SESSION['appointment']['schedule']) &&
												$monday . " 14:00:00" == $_SESSION['appointment']['schedule']) 
												echo 'selected'; ?>
											<?php echo ($isFull) ? " full" : ''; ?>
										"
										<?php echo ($isFull || $isPastDate) ? " disabled" : '' ?>
									>
										
										<?php echo (!$isFull) ? "02:00 PM - 03:00 PM" : "FULL" ?>
									</button>
								</td>
								<td>
									<?php
										$tuesdayTimeStamp = DateTime::createFromFormat('m-d-Y', $tuesday);
										$tuesdayYmd = $tuesdayTimeStamp->format('Y-m-d');
										$tuesdayYmdTime = !empty($aptGroups[$tuesdayYmd . " 09:00:00"]) ?
											$aptGroups[$tuesdayYmd . " 09:00:00"] : [];
										$isFull = count($tuesdayYmdTime) >= 10;
										$isPastDate = date('Y-m-d') > $tuesdayYmd;
									?>
									<button name ="schedule" value="<?php echo $tuesday . " 09:00:00" ?>"
										class="time time-am time-am-tue 
											<?php if (isset($_SESSION['appointment']['schedule']) &&
												$tuesday . " 09:00:00" == $_SESSION['appointment']['schedule']) 
												echo 'selected'; ?>
											<?php echo ($isFull) ? " full" : ''; ?>
										"
										<?php echo ($isFull || $isPastDate) ? " disabled" : '' ?>
									>
										
										<?php echo (!$isFull) ? "09:00 AM - 10:00 AM" : "FULL" ?>
									</button>
									<?php
										$tuesdayTimeStamp = DateTime::createFromFormat('m-d-Y', $tuesday);
										$tuesdayYmd = $tuesdayTimeStamp->format('Y-m-d');
										$tuesdayYmdTime = !empty($aptGroups[$tuesdayYmd . " 14:00:00"]) ?
											$aptGroups[$tuesdayYmd . " 14:00:00"] : [];
										$isFull = count($tuesdayYmdTime) >= 10;
										$isPastDate = date('Y-m-d') > $tuesdayYmd;
									?>
									<button name ="schedule" value="<?php echo $tuesday . " 14:00:00" ?>"
										class="time time-pm time-pm-tue 
											<?php if (isset($_SESSION['appointment']['schedule']) &&
												$tuesday . " 14:00:00" == $_SESSION['appointment']['schedule']) 
												echo 'selected'; ?>
											<?php echo ($isFull) ? " full" : ''; ?>
										"
										<?php echo ($isFull || $isPastDate) ? " disabled" : '' ?>
									>
										
										<?php echo (!$isFull) ? "02:00 PM - 03:00 PM" : "FULL" ?>
									</button>
								</td>
								<td>
									<?php
										$wednesdayTimeStamp = DateTime::createFromFormat('m-d-Y', $wednesday);
										$wednesdayYmd = $wednesdayTimeStamp->format('Y-m-d');
										$wednesdayYmdTime = !empty($aptGroups[$wednesdayYmd . " 09:00:00"]) ?
											$aptGroups[$wednesdayYmd . " 09:00:00"] : [];
										$isFull = count($wednesdayYmdTime) >= 10;
										$isPastDate = date('Y-m-d') > $wednesdayYmd;
									?>
									<button name ="schedule" value="<?php echo $wednesday . " 09:00:00" ?>"
										class="time time-am time-am-wed 
											<?php if (isset($_SESSION['appointment']['schedule']) &&
												$wednesday . " 09:00:00" == $_SESSION['appointment']['schedule']) 
												echo 'selected'; ?>
											<?php echo ($isFull) ? " full" : ''; ?>
										"
										<?php echo ($isFull || $isPastDate) ? " disabled" : '' ?>
									>
										
										<?php echo (!$isFull) ? "09:00 AM - 10:00 AM" : "FULL" ?>
									</button>
									<?php
										$wednesdayTimeStamp = DateTime::createFromFormat('m-d-Y', $wednesday);
										$wednesdayYmd = $wednesdayTimeStamp->format('Y-m-d');
										$wednesdayYmdTime = !empty($aptGroups[$wednesdayYmd . " 14:00:00"]) ?
											$aptGroups[$wednesdayYmd . " 14:00:00"] : [];
										$isFull = count($wednesdayYmdTime) >= 10;
										$isPastDate = date('Y-m-d') > $wednesdayYmd;
									?>
									<button name ="schedule" value="<?php echo $wednesday . " 14:00:00" ?>"
										class="time time-pm time-pm-wed 
											<?php if (isset($_SESSION['appointment']['schedule']) &&
												$wednesday . " 14:00:00" == $_SESSION['appointment']['schedule']) 
												echo 'selected'; ?>
											<?php echo ($isFull) ? " full" : ''; ?>
										"
										<?php echo ($isFull || $isPastDate) ? " disabled" : '' ?>
									>
										
										<?php echo (!$isFull) ? "02:00 PM - 03:00 PM" : "FULL" ?>
									</button>
								</td>
								<td>
									<?php
										$thursdayTimeStamp = DateTime::createFromFormat('m-d-Y', $thursday);
										$thursdayYmd = $thursdayTimeStamp->format('Y-m-d');
										$thursdayYmdTime = !empty($aptGroups[$thursdayYmd . " 09:00:00"]) ?
											$aptGroups[$thursdayYmd . " 09:00:00"] : [];
										$isFull = count($thursdayYmdTime) >= 10;
										$isPastDate = date('Y-m-d') > $thursdayYmd;
									?>
									<button name ="schedule" value="<?php echo $thursday . " 09:00:00" ?>"
										class="time time-am time-am-thu 
											<?php if (isset($_SESSION['appointment']['schedule']) &&
												$thursday . " 09:00:00" == $_SESSION['appointment']['schedule']) 
												echo 'selected'; ?>
											<?php echo ($isFull) ? " full" : ''; ?>
										"
										<?php echo ($isFull || $isPastDate) ? " disabled" : '' ?>
									>
										
										<?php echo (!$isFull) ? "09:00 AM - 10:00 AM" : "FULL" ?>
									</button>
									<?php
										$thursdayTimeStamp = DateTime::createFromFormat('m-d-Y', $thursday);
										$thursdayYmd = $thursdayTimeStamp->format('Y-m-d');
										$thursdayYmdTime = !empty($aptGroups[$thursdayYmd . " 14:00:00"]) ?
											$aptGroups[$thursdayYmd . " 14:00:00"] : [];
										$isFull = count($thursdayYmdTime) >= 10;
										$isPastDate = date('Y-m-d') > $thursdayYmd;
									?>
									<button name ="schedule" value="<?php echo $thursday . " 14:00:00" ?>"
										class="time time-pm time-pm-thu 
											<?php if (isset($_SESSION['appointment']['schedule']) &&
												$thursday . " 14:00:00" == $_SESSION['appointment']['schedule']) 
												echo 'selected'; ?>
											<?php echo ($isFull) ? " full" : ''; ?>
										"
										<?php echo ($isFull || $isPastDate) ? " disabled" : '' ?>
									>
										
										<?php echo (!$isFull) ? "02:00 PM - 03:00 PM" : "FULL" ?>
									</button>
								</td>
								<td>
									<?php
										$fridayTimeStamp = DateTime::createFromFormat('m-d-Y', $friday);
										$fridayYmd = $fridayTimeStamp->format('Y-m-d');
										$fridayYmdTime = !empty($aptGroups[$fridayYmd . " 09:00:00"]) ?
											$aptGroups[$fridayYmd . " 09:00:00"] : [];
										$isFull = count($fridayYmdTime) >= 10;
										$isPastDate = date('Y-m-d') > $fridayYmd;
									?>
									<button name ="schedule" value="<?php echo $friday . " 09:00:00" ?>"
										class="time time-am time-am-fri 
											<?php if (isset($_SESSION['appointment']['schedule']) &&
												$friday . " 09:00:00" == $_SESSION['appointment']['schedule']) 
												echo 'selected'; ?>
											<?php echo ($isFull) ? " full" : ''; ?>
										"
										<?php echo ($isFull || $isPastDate) ? " disabled" : '' ?>
									>
										
										<?php echo (!$isFull) ? "09:00 AM - 10:00 AM" : "FULL" ?>
									</button>
									<?php
										$fridayTimeStamp = DateTime::createFromFormat('m-d-Y', $friday);
										$fridayYmd = $fridayTimeStamp->format('Y-m-d');
										$fridayYmdTime = !empty($aptGroups[$fridayYmd . " 14:00:00"]) ?
											$aptGroups[$fridayYmd . " 14:00:00"] : [];
										$isFull = count($fridayYmdTime) >= 10;
										$isPastDate = date('Y-m-d') > $fridayYmd;
									?>
									<button name ="schedule" value="<?php echo $friday . " 14:00:00" ?>"
										class="time time-pm time-pm-fri 
											<?php if (isset($_SESSION['appointment']['schedule']) &&
												$friday . " 14:00:00" == $_SESSION['appointment']['schedule']) 
												echo 'selected'; ?>
											<?php echo ($isFull) ? " full" : ''; ?>
										"
										<?php echo ($isFull || $isPastDate) ? " disabled" : '' ?>
									>
										
										<?php echo (!$isFull) ? "02:00 PM - 03:00 PM" : "FULL" ?>
									</button>
								</td>
								<td>
									<?php
										$saturdayTimeStamp = DateTime::createFromFormat('m-d-Y', $saturday);
										$saturdayYmd = $saturdayTimeStamp->format('Y-m-d');
										$saturdayYmdTime = !empty($aptGroups[$saturdayYmd . " 09:00:00"]) ?
											$aptGroups[$saturdayYmd . " 09:00:00"] : [];
										$isFull = count($saturdayYmdTime) >= 10;
										$isPastDate = date('Y-m-d') > $saturdayYmd;
									?>
									<button name ="schedule" value="<?php echo $saturday . " 09:00:00" ?>"
										class="time time-am time-am-sat 
											<?php if (isset($_SESSION['appointment']['schedule']) &&
												$saturday . " 09:00:00" == $_SESSION['appointment']['schedule']) 
												echo 'selected'; ?>
											<?php echo ($isFull) ? " full" : ''; ?>
										"
										<?php echo ($isFull || $isPastDate) ? " disabled" : '' ?>
									>
										
										<?php echo (!$isFull) ? "09:00 AM - 10:00 AM" : "FULL" ?>
									</button>
									<?php
										$saturdayTimeStamp = DateTime::createFromFormat('m-d-Y', $saturday);
										$saturdayYmd = $saturdayTimeStamp->format('Y-m-d');
										$saturdayYmdTime = !empty($aptGroups[$saturdayYmd . " 14:00:00"]) ?
											$aptGroups[$saturdayYmd . " 14:00:00"] : [];
										$isFull = count($saturdayYmdTime) >= 10;
										$isPastDate = date('Y-m-d') > $saturdayYmd;
									?>
									<button name ="schedule" value="<?php echo $saturday . " 14:00:00" ?>"
										class="time time-pm time-pm-sat 
											<?php if (isset($_SESSION['appointment']['schedule']) &&
												$saturday . " 14:00:00" == $_SESSION['appointment']['schedule']) 
												echo 'selected'; ?>
											<?php echo ($isFull) ? " full" : ''; ?>
										"
										<?php echo ($isFull || $isPastDate) ? " disabled" : '' ?>
									>
										
										<?php echo (!$isFull) ? "02:00 PM - 03:00 PM" : "FULL" ?>
									</button>
								</td>
								<td></td>
							</tr>

							<tr>
								<td></td>
								<td>
									<?php
										$mondayTimeStamp = DateTime::createFromFormat('m-d-Y', $monday);
										$mondayYmd = $mondayTimeStamp->format('Y-m-d');
										$mondayYmdTime = !empty($aptGroups[$mondayYmd . " 10:00:00"]) ?
											$aptGroups[$mondayYmd . " 10:00:00"] : [];
										$isFull = count($mondayYmdTime) >= 10;
										$isPastDate = date('Y-m-d') > $mondayYmd;
									?>
									<button name ="schedule" value="<?php echo $monday . " 10:00:00" ?>"
										class="time time-am time-am-mon 
											<?php if (isset($_SESSION['appointment']['schedule']) &&
												$monday . " 10:00:00" == $_SESSION['appointment']['schedule']) 
												echo 'selected'; ?>
											<?php echo ($isFull) ? " full" : ''; ?>
										"
										<?php echo ($isFull || $isPastDate) ? " disabled" : '' ?>
									>	
										<?php echo (!$isFull) ? "10:00 AM - 11:00 AM" : "FULL" ?>
									</button>
									<?php
										$mondayTimeStamp = DateTime::createFromFormat('m-d-Y', $monday);
										$mondayYmd = $mondayTimeStamp->format('Y-m-d');
										$mondayYmdTime = !empty($aptGroups[$mondayYmd . " 15:00:00"]) ?
											$aptGroups[$mondayYmd . " 15:00:00"] : [];
										$isFull = count($mondayYmdTime) >= 10;
										$isPastDate = date('Y-m-d') > $mondayYmd;
									?>
									<button name ="schedule" value="<?php echo $monday . " 15:00:00" ?>"
										class="time time-pm time-pm-mon 
											<?php if (isset($_SESSION['appointment']['schedule']) &&
												$monday . " 15:00:00" == $_SESSION['appointment']['schedule']) 
												echo 'selected'; ?>
											<?php echo ($isFull) ? " full" : ''; ?>
										"
										<?php echo ($isFull || $isPastDate) ? " disabled" : '' ?>
									>	
										<?php echo (!$isFull) ? "03:00 PM - 04:00 PM" : "FULL" ?>
									</button>
								</td>
								<td>
									<?php
										$tuesdayTimeStamp = DateTime::createFromFormat('m-d-Y', $tuesday);
										$tuesdayYmd = $tuesdayTimeStamp->format('Y-m-d');
										$tuesdayYmdTime = !empty($aptGroups[$tuesdayYmd . " 10:00:00"]) ?
											$aptGroups[$tuesdayYmd . " 10:00:00"] : [];
										$isFull = count($tuesdayYmdTime) >= 10;
										$isPastDate = date('Y-m-d') > $tuesdayYmd;
									?>
									<button name ="schedule" value="<?php echo $tuesday . " 10:00:00" ?>"
										class="time time-am time-am-tue 
											<?php if (isset($_SESSION['appointment']['schedule']) &&
												$tuesday . " 10:00:00" == $_SESSION['appointment']['schedule']) 
												echo 'selected'; ?>
											<?php echo ($isFull) ? " full" : ''; ?>
										"
										<?php echo ($isFull || $isPastDate) ? " disabled" : '' ?>
									>	
										<?php echo (!$isFull) ? "10:00 AM - 11:00 AM" : "FULL" ?>
									</button>
									<?php
										$tuesdayTimeStamp = DateTime::createFromFormat('m-d-Y', $tuesday);
										$tuesdayYmd = $tuesdayTimeStamp->format('Y-m-d');
										$tuesdayYmdTime = !empty($aptGroups[$tuesdayYmd . " 15:00:00"]) ?
											$aptGroups[$tuesdayYmd . " 15:00:00"] : [];
										$isFull = count($tuesdayYmdTime) >= 10;
										$isPastDate = date('Y-m-d') > $tuesdayYmd;
									?>
									<button name ="schedule" value="<?php echo $tuesday . " 15:00:00" ?>"
										class="time time-pm time-pm-tue 
											<?php if (isset($_SESSION['appointment']['schedule']) &&
												$tuesday . " 15:00:00" == $_SESSION['appointment']['schedule']) 
												echo 'selected'; ?>
											<?php echo ($isFull) ? " full" : ''; ?>
										"
										<?php echo ($isFull || $isPastDate) ? " disabled" : '' ?>
									>	
										<?php echo (!$isFull) ? "03:00 PM - 04:00 PM" : "FULL" ?>
									</button>
								</td>
								<td>
									<?php
										$wednesdayTimeStamp = DateTime::createFromFormat('m-d-Y', $wednesday);
										$wednesdayYmd = $wednesdayTimeStamp->format('Y-m-d');
										$wednesdayYmdTime = !empty($aptGroups[$wednesdayYmd . " 10:00:00"]) ?
											$aptGroups[$wednesdayYmd . " 10:00:00"] : [];
										$isFull = count($wednesdayYmdTime) >= 10;
										$isPastDate = date('Y-m-d') > $wednesdayYmd;
									?>
									<button name ="schedule" value="<?php echo $wednesday . " 10:00:00" ?>"
										class="time time-am time-am-wed 
											<?php if (isset($_SESSION['appointment']['schedule']) &&
												$wednesday . " 10:00:00" == $_SESSION['appointment']['schedule']) 
												echo 'selected'; ?>
											<?php echo ($isFull) ? " full" : ''; ?>
										"
										<?php echo ($isFull || $isPastDate) ? " disabled" : '' ?>
									>	
										<?php echo (!$isFull) ? "10:00 AM - 11:00 AM" : "FULL" ?>
									</button>
									<?php
										$wednesdayTimeStamp = DateTime::createFromFormat('m-d-Y', $wednesday);
										$wednesdayYmd = $wednesdayTimeStamp->format('Y-m-d');
										$wednesdayYmdTime = !empty($aptGroups[$wednesdayYmd . " 15:00:00"]) ?
											$aptGroups[$wednesdayYmd . " 15:00:00"] : [];
										$isFull = count($wednesdayYmdTime) >= 10;
										$isPastDate = date('Y-m-d') > $wednesdayYmd;
									?>
									<button name ="schedule" value="<?php echo $wednesday . " 15:00:00" ?>"
										class="time time-pm time-pm-wed 
											<?php if (isset($_SESSION['appointment']['schedule']) &&
												$wednesday . " 15:00:00" == $_SESSION['appointment']['schedule']) 
												echo 'selected'; ?>
											<?php echo ($isFull) ? " full" : ''; ?>
										"
										<?php echo ($isFull || $isPastDate) ? " disabled" : '' ?>
									>	
										<?php echo (!$isFull) ? "03:00 PM - 04:00 PM" : "FULL" ?>
									</button>
								</td>
								<td>
									<?php
										$thursdayTimeStamp = DateTime::createFromFormat('m-d-Y', $thursday);
										$thursdayYmd = $thursdayTimeStamp->format('Y-m-d');
										$thursdayYmdTime = !empty($aptGroups[$thursdayYmd . " 10:00:00"]) ?
											$aptGroups[$thursdayYmd . " 10:00:00"] : [];
										$isFull = count($thursdayYmdTime) >= 10;
										$isPastDate = date('Y-m-d') > $thursdayYmd;
									?>
									<button name ="schedule" value="<?php echo $thursday . " 10:00:00" ?>"
										class="time time-am time-am-thu 
											<?php if (isset($_SESSION['appointment']['schedule']) &&
												$thursday . " 10:00:00" == $_SESSION['appointment']['schedule']) 
												echo 'selected'; ?>
											<?php echo ($isFull) ? " full" : ''; ?>
										"
										<?php echo ($isFull || $isPastDate) ? " disabled" : '' ?>
									>	
										<?php echo (!$isFull) ? "10:00 AM - 11:00 AM" : "FULL" ?>
									</button>
									<?php
										$thursdayTimeStamp = DateTime::createFromFormat('m-d-Y', $thursday);
										$thursdayYmd = $thursdayTimeStamp->format('Y-m-d');
										$thursdayYmdTime = !empty($aptGroups[$thursdayYmd . " 15:00:00"]) ?
											$aptGroups[$thursdayYmd . " 15:00:00"] : [];
										$isFull = count($thursdayYmdTime) >= 10;
										$isPastDate = date('Y-m-d') > $thursdayYmd;
									?>
									<button name ="schedule" value="<?php echo $thursday . " 15:00:00" ?>"
										class="time time-pm time-pm-thu 
											<?php if (isset($_SESSION['appointment']['schedule']) &&
												$thursday . " 15:00:00" == $_SESSION['appointment']['schedule']) 
												echo 'selected'; ?>
											<?php echo ($isFull) ? " full" : ''; ?>
										"
										<?php echo ($isFull || $isPastDate) ? " disabled" : '' ?>
									>	
										<?php echo (!$isFull) ? "03:00 PM - 04:00 PM" : "FULL" ?>
									</button>
								</td>
								<td>
									<?php
										$fridayTimeStamp = DateTime::createFromFormat('m-d-Y', $friday);
										$fridayYmd = $fridayTimeStamp->format('Y-m-d');
										$fridayYmdTime = !empty($aptGroups[$fridayYmd . " 10:00:00"]) ?
											$aptGroups[$fridayYmd . " 10:00:00"] : [];
										$isFull = count($fridayYmdTime) >= 10;
										$isPastDate = date('Y-m-d') > $fridayYmd;
									?>
									<button name ="schedule" value="<?php echo $friday . " 10:00:00" ?>"
										class="time time-am time-am-fri 
											<?php if (isset($_SESSION['appointment']['schedule']) &&
												$friday . " 10:00:00" == $_SESSION['appointment']['schedule']) 
												echo 'selected'; ?>
											<?php echo ($isFull) ? " full" : ''; ?>
										"
										<?php echo ($isFull || $isPastDate) ? " disabled" : '' ?>
									>	
										<?php echo (!$isFull) ? "10:00 AM - 11:00 AM" : "FULL" ?>
									</button>
									<?php
										$fridayTimeStamp = DateTime::createFromFormat('m-d-Y', $friday);
										$fridayYmd = $fridayTimeStamp->format('Y-m-d');
										$fridayYmdTime = !empty($aptGroups[$fridayYmd . " 15:00:00"]) ?
											$aptGroups[$fridayYmd . " 15:00:00"] : [];
										$isFull = count($fridayYmdTime) >= 10;
										$isPastDate = date('Y-m-d') > $fridayYmd;
									?>
									<button name ="schedule" value="<?php echo $friday . " 15:00:00" ?>"
										class="time time-pm time-pm-fri 
											<?php if (isset($_SESSION['appointment']['schedule']) &&
												$friday . " 15:00:00" == $_SESSION['appointment']['schedule']) 
												echo 'selected'; ?>
											<?php echo ($isFull) ? " full" : ''; ?>
										"
										<?php echo ($isFull || $isPastDate) ? " disabled" : '' ?>
									>		
										<?php echo (!$isFull) ? "03:00 PM - 04:00 PM" : "FULL" ?>
									</button>
								</td>
								<td>
									<?php
										$saturdayTimeStamp = DateTime::createFromFormat('m-d-Y', $saturday);
										$saturdayYmd = $saturdayTimeStamp->format('Y-m-d');
										$saturdayYmdTime = !empty($aptGroups[$saturdayYmd . " 10:00:00"]) ?
											$aptGroups[$saturdayYmd . " 10:00:00"] : [];
										$isFull = count($saturdayYmdTime) >= 10;
										$isPastDate = date('Y-m-d') > $saturdayYmd;
									?>
									<button name ="schedule" value="<?php echo $saturday . " 10:00:00" ?>"
										class="time time-am time-am-sat 
											<?php if (isset($_SESSION['appointment']['schedule']) &&
												$saturday . " 10:00:00" == $_SESSION['appointment']['schedule']) 
												echo 'selected'; ?>
											<?php echo ($isFull) ? " full" : ''; ?>
										"
										<?php echo ($isFull || $isPastDate) ? " disabled" : '' ?>
									>
										<?php echo (!$isFull) ? "10:00 AM - 11:00 AM" : "FULL" ?>
									</button>
									<?php
										$saturdayTimeStamp = DateTime::createFromFormat('m-d-Y', $saturday);
										$saturdayYmd = $saturdayTimeStamp->format('Y-m-d');
										$saturdayYmdTime = !empty($aptGroups[$saturdayYmd . " 15:00:00"]) ?
											$aptGroups[$saturdayYmd . " 15:00:00"] : [];
										$isFull = count($saturdayYmdTime) >= 10;
										$isPastDate = date('Y-m-d') > $saturdayYmd;
									?>
									<button name ="schedule" value="<?php echo $saturday . " 15:00:00" ?>"
										class="time time-pm time-pm-sat 
											<?php if (isset($_SESSION['appointment']['schedule']) &&
												$saturday . " 15:00:00" == $_SESSION['appointment']['schedule']) 
												echo 'selected'; ?>
											<?php echo ($isFull) ? " full" : ''; ?>
										"
										<?php echo ($isFull || $isPastDate) ? " disabled" : '' ?>
									>	
										<?php echo (!$isFull) ? "03:00 PM - 04:00 PM" : "FULL" ?>
									</button>
								</td>
								<td></td>
							</tr>

							<tr>	
								<td></td>
								<td>
									<?php
										$mondayTimeStamp = DateTime::createFromFormat('m-d-Y', $monday);
										$mondayYmd = $mondayTimeStamp->format('Y-m-d');
										$mondayYmdTime = !empty($aptGroups[$mondayYmd . " 11:00:00"]) ?
											$aptGroups[$mondayYmd . " 11:00:00"] : [];
										$isFull = count($mondayYmdTime) >= 10;
										$isPastDate = date('Y-m-d') > $mondayYmd;
									?>
									<button name ="schedule" value="<?php echo $monday . " 11:00:00" ?>"
										class="time time-am time-am-mon 
											<?php if (isset($_SESSION['appointment']['schedule']) &&
												$monday . " 11:00:00" == $_SESSION['appointment']['schedule']) 
												echo 'selected'; ?>
											<?php echo ($isFull) ? " full" : ''; ?>
										"
										<?php echo ($isFull || $isPastDate) ? " disabled" : '' ?>
									>	
										<?php echo (!$isFull) ? "11:00 AM - 12:00 PM" : "FULL" ?>
									</button>
								<td>
									<?php
										$tuesdayTimeStamp = DateTime::createFromFormat('m-d-Y', $tuesday);
										$tuesdayYmd = $tuesdayTimeStamp->format('Y-m-d');
										$tuesdayYmdTime = !empty($aptGroups[$tuesdayYmd . " 11:00:00"]) ?
											$aptGroups[$tuesdayYmd . " 11:00:00"] : [];
										$isFull = count($tuesdayYmdTime) >= 10;
										$isPastDate = date('Y-m-d') > $tuesdayYmd;
									?>
									<button name ="schedule" value="<?php echo $tuesday . " 11:00:00" ?>"
										class="time time-am time-am-tue 
											<?php if (isset($_SESSION['appointment']['schedule']) &&
												$tuesday . " 11:00:00" == $_SESSION['appointment']['schedule']) 
												echo 'selected'; ?>
											<?php echo ($isFull) ? " full" : ''; ?>
										"
										<?php echo ($isFull || $isPastDate) ? " disabled" : '' ?>
									>	
										<?php echo (!$isFull) ? "11:00 AM - 12:00 PM" : "FULL" ?>
									</button>
								<td>
									<?php
										$wednesdayTimeStamp = DateTime::createFromFormat('m-d-Y', $wednesday);
										$wednesdayYmd = $wednesdayTimeStamp->format('Y-m-d');
										$wednesdayYmdTime = !empty($aptGroups[$wednesdayYmd . " 11:00:00"]) ?
											$aptGroups[$wednesdayYmd . " 11:00:00"] : [];
										$isFull = count($wednesdayYmdTime) >= 10;
										$isPastDate = date('Y-m-d') > $wednesdayYmd;
									?>
										<button name ="schedule" value="<?php echo $wednesday . " 11:00:00" ?>"
										class="time time-am time-am-wed 
											<?php if (isset($_SESSION['appointment']['schedule']) &&
												$wednesday . " 11:00:00" == $_SESSION['appointment']['schedule']) 
												echo 'selected'; ?>
											<?php echo ($isFull) ? " full" : ''; ?>
										"
										<?php echo ($isFull || $isPastDate) ? " disabled" : '' ?>
									>
										
										<?php echo (!$isFull) ? "11:00 AM - 12:00 PM" : "FULL" ?>
									</button>
									</td>
								<td>
									<?php
										$thursdayTimeStamp = DateTime::createFromFormat('m-d-Y', $thursday);
										$thursdayYmd = $thursdayTimeStamp->format('Y-m-d');
										$thursdayYmdTime = !empty($aptGroups[$thursdayYmd . " 11:00:00"]) ?
											$aptGroups[$thursdayYmd . " 11:00:00"] : [];
										$isFull = count($thursdayYmdTime) >= 10;
										$isPastDate = date('Y-m-d') > $thursdayYmd;
									?>
										<button name ="schedule" value="<?php echo $thursday . " 11:00:00" ?>"
										class="time time-am time-am-thu 
											<?php if (isset($_SESSION['appointment']['schedule']) &&
												$thursday . " 11:00:00" == $_SESSION['appointment']['schedule']) 
												echo 'selected'; ?>
											<?php echo ($isFull) ? " full" : ''; ?>
										"
										<?php echo ($isFull || $isPastDate) ? " disabled" : '' ?>
									>		
										<?php echo (!$isFull) ? "11:00 AM - 12:00 PM" : "FULL" ?>
									</button>
									</td>
								<td>
									<?php
										$fridayTimeStamp = DateTime::createFromFormat('m-d-Y', $friday);
										$fridayYmd = $fridayTimeStamp->format('Y-m-d');
										$fridayYmdTime = !empty($aptGroups[$fridayYmd . " 11:00:00"]) ?
											$aptGroups[$fridayYmd . " 11:00:00"] : [];
										$isFull = count($fridayYmdTime) >= 10;
										$isPastDate = date('Y-m-d') > $fridayYmd;
									?>
									<button name ="schedule" value="<?php echo $friday . " 11:00:00" ?>"
										class="time time-am time-am-fri 
											<?php if (isset($_SESSION['appointment']['schedule']) &&
												$friday . " 11:00:00" == $_SESSION['appointment']['schedule']) 
												echo 'selected'; ?>
											<?php echo ($isFull) ? " full" : ''; ?>
										"
										<?php echo ($isFull || $isPastDate) ? " disabled" : '' ?>
									>		
										<?php echo (!$isFull) ? "11:00 AM - 12:00 PM" : "FULL" ?>
									</button>
								</td>
								<td>
									<?php
										$saturdayTimeStamp = DateTime::createFromFormat('m-d-Y', $saturday);
										$saturdayYmd = $saturdayTimeStamp->format('Y-m-d');
										$saturdayYmdTime = !empty($aptGroups[$saturdayYmd . " 11:00:00"]) ?
											$aptGroups[$saturdayYmd . " 11:00:00"] : [];
										$isFull = count($saturdayYmdTime) >= 10;
										$isPastDate = date('Y-m-d') > $saturdayYmd;
									?>
										<button name ="schedule" value="<?php echo $saturday . " 11:00:00" ?>"
										class="time time-am time-am-sat 
											<?php if (isset($_SESSION['appointment']['schedule']) &&
												$saturday . " 11:00:00" == $_SESSION['appointment']['schedule']) 
												echo 'selected'; ?>
											<?php echo ($isFull) ? " full" : ''; ?>
										"
										<?php echo ($isFull || $isPastDate) ? " disabled" : '' ?>
									>						
										<?php echo (!$isFull) ? "11:00 AM - 12:00 PM" : "FULL" ?>
									</button>
									</td>
								<td></td>
							</tr>
							<tr>
								<td></td>
								<td><button class="more more-mon">More...</button></td>
								<td><button class="more more-tue">More...</button></td>
								<td><button class="more more-wed">More...</button></td>
								<td><button class="more more-thu">More...</button></td>
								<td><button class="more more-fri">More...</button></td>
								<td><button class="more more-sat">More...</button></td>
								<td></td>
							</tr>
						</table>		
					</div>
				</div>
			</div>
		</div>
	</form>
</body>
