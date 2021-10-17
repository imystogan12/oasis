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


	if (isset($_POST['schedule'])) {
		echo "<pre>";
		var_dump($_POST);
		echo "</pre>";
		$_SESSION['appointment']['schedule'] = $_POST['schedule'] ?? null;
		$_SESSION['appointment']['department'] = $_POST['department'] ?? null;
		$_SESSION['appointment']['reason'] = $_POST['reason'] ?? null;
		$_SESSION['appointment']['faculty'] = $_POST['faculty'] ?? null;
	}

	if (isset($_POST['submit'])) {
		// $servername = "localhost";
		// $username = "root";
		// $password = "root";
		// $dbname = 'oasis';

		// $conn = new mysqli($servername, $username, $password, $dbname);


		// if ($conn->connect_error) {
  // 		die("Connection failed: " . $conn->connect_error);
		// }
		// echo "Connected successfully";

		$_SESSION['appointment']['department'] = !empty($_POST['department']) ? $_POST['department'] : $_SESSION['appointment']['department'];
		 $_SESSION['appointment']['reason'] = !empty($_POST['reason']) ? $_POST['reason'] : $_SESSION['appointment']['reason'];
		 $_SESSION['appointment']['faculty'] = !empty($_POST['faculty']) ? $_POST['faculty'] : $_SESSION['appointment']['faculty'];

		$dateTimeStamp = DateTime::createFromFormat('m-d-Y H:i:s', $_SESSION['appointment']['schedule']);
		

		$user_id = null;
		$faculty_name = 'N/A';
		
		switch ($_SESSION['appointment']['department']) {
			case 'cashier':
				$user_id = 1;
				break;
			case 'registrar':
				$user_id = 2;
				break;
			case 'admission':
				$user_id = 3;
				break;
			case 'guidance':
				$user_id = 4;
				break;
			case 'clinic':
				$user_id = 5;
				break;
			case 'ojt':
				$user_id = 6;
				break;
			case 'prowear':
				$user_id = 7;
				break;

			default:
				break;
		}

		switch ($_SESSION['appointment']['faculty']) {
			case 'christian-torres':
				$user_id = 8;
				$faculty_name="Christian Torres";
				break;
			case 'jennilyn-silva':
				$user_id = 8;
				$faculty_name="Jennilyn Silva";
				break;
			case 'reynaldo-merced':
				$user_id = 8;
				$faculty_name="Reynaldo Merced";
				break;
			case 'allan-badilla':
				$user_id = 10;
				$faculty_name="Allan Badilla";
				break;
			case 'kathleen':
				$user_id = 10;
				$faculty_name="Kathleen";
				break;
			case 'grace-pangilinan':
				$user_id = 12;
				$faculty_name="Grace Pangilinan";
				break;
			case 'tina-mendoza':
				$user_id = 12;
				$faculty_name="Christina Mendoza";
				break;
			case 'romeo-olympia':
				$user_id = 11;
				$faculty_name="Romeo Olympia";
				break;
			case 'neth-portugues':
				$user_id = 11;
				$faculty_name="Neth Portugues";
				break;
			default:
				break;
		}

   		$_SESSION[$transaction_type]['appointment'] = [
			'department' => $_SESSION['appointment']['department'],
			'reason' => $_SESSION['appointment']['reason'],
			'user_id' => $user_id,
			'student_id' => $_SESSION['student']['student_id'],
			'date_time' => $_SESSION['appointment']['schedule'],
			'faculty' => $_SESSION['appointment']['faculty'],
			'faculty_name' => $faculty_name,
		];
   		header("Location: confirmationPage.php");
   		exit;
   	}   	

	echo "<pre>";
	// var_dump($_SESSION);
	echo "</pre>";
?>
<div class="header-div">
	<p class="oasis">OASIS</p>
</div>



<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		console.log("dgfdf");
		//$("label").css("color", "red");
		//console.log($("label").length);
		//$("select#choice")
		console.log($("#department").length);
		$("#department").on("change", function(){
			console.log("change");
			var deptValue = this.value;
			console.log('deptValue', this.value);
			$("#reason").find("option." + deptValue);
			console.log("option." + deptValue);
			$("#reason").find("option." + deptValue).show(); // option.cashier
			$("#reason").find("option:not(." + deptValue + ")").hide(); // option:not(.cashier)
			$("#reason").val('');
			
			if (deptValue=="faculty"){
				$("#faculty").prop('disabled', false);
			}
			else {
				$("#faculty").prop('disabled', true);
			}
			$("#faculty").val('');

		});

		$(".more").on("click", function(e){
			e.preventDefault();
			// console.log("clicked");

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
								<select class="dept" name="department" id="department">
									<option value=""></option>
									<option value="cashier"
										<?php if (!empty($_SESSION['appointment']['department']) && $_SESSION['appointment']['department'] === "cashier") echo " selected" 
										?>
									>Cashier</option>
									<option value="registrar"
										<?php if (!empty($_SESSION['appointment']['department']) && $_SESSION['appointment']['department'] === "registrar") echo " selected" 
										?>
									>Registrar</option>
									<option value="admission"
										<?php if (!empty($_SESSION['appointment']['department']) && $_SESSION['appointment']['department'] === "admission") echo " selected" 
										?>
									>Admission</option>
									<option value="guidance"
										<?php if (!empty($_SESSION['appointment']['department']) && $_SESSION['appointment']['department'] === "guidance") echo " selected" 
										?>
									>Guidance</option>
									<option value="clinic"
										<?php if (!empty($_SESSION['appointment']['department']) && $_SESSION['appointment']['department'] === "clinic") echo " selected" 
										?>
									>Clinic</option>
									<option value="ojt"
										<?php if (!empty($_SESSION['appointment']['department']) && $_SESSION['appointment']['department'] === "ojt") echo " selected" 
										?>
									>APO(OJT)</option>
									<option value="prowear"
										<?php if (!empty($_SESSION['appointment']['department']) && $_SESSION['appointment']['department'] === "prowear") echo " selected" 
										?>
									>Prowear</option>
									<option value="faculty"
										<?php if (!empty($_SESSION['appointment']['department']) && $_SESSION['appointment']['department'] === "faculty") echo " selected" 
										?>
									>Faculty (Teachers)</option>
								</select>
							</div>
						</div>
						<div class="div">
							<div>
								<label for="choice">Reason</label>
							</div>
							<div>
								<select class="reason" name="reason" id="reason">
									<option value=""></option>
									<option	class="cashier" value="enrollment"
										<?php if (empty($_SESSION['appointment']['reason']) || ($_SESSION['appointment']['department'] !== "cashier")) echo " hidden " ?> 
										<?php if (!empty($_SESSION['appointment']['reason']) && ($_SESSION['appointment']['reason'] === "enrollment")) echo " selected" ?>
										>Enrollment</option>
									<option class="cashier" value="paying-other-bills"
										<?php if (empty($_SESSION['appointment']['reason']) || ($_SESSION['appointment']['department'] !== "cashier")) echo " hidden " ?> 
										<?php if (!empty($_SESSION['appointment']['reason']) && ($_SESSION['appointment']['reason'] === "paying-other-bills")) echo " selected" ?>
										>Paying other bills</option>
									<option class="registrar" value="clearance"
										<?php if (empty($_SESSION['appointment']['reason']) || ($_SESSION['appointment']['department'] !== "registrar")) echo " hidden " ?> 
										<?php if (!empty($_SESSION['appointment']['reason']) && ($_SESSION['appointment']['reason'] === "consultation")) echo " selected" ?>
										>Clearance</option>
									<option class="registrar" value="form-request"
										<?php if (empty($_SESSION['appointment']['reason']) || ($_SESSION['appointment']['department'] !== "registrar")) echo " hidden " ?> 
										<?php if (!empty($_SESSION['appointment']['reason']) && ($_SESSION['appointment']['reason'] === "form-request")) echo " selected" ?>
										>Form Request</option>
									<option class="admission" value="general-inquiry"
										<?php if (empty($_SESSION['appointment']['reason']) || ($_SESSION['appointment']['department'] !== "admission")) echo " hidden " ?> 
										<?php if (!empty($_SESSION['appointment']['reason']) && ($_SESSION['appointment']['reason'] === "general-inquiry")) echo " selected" ?>
										>General Inquiry</option>
									<option class="admission" value="other-concerns"
										<?php if (empty($_SESSION['appointment']['reason']) || ($_SESSION['appointment']['department'] !== "admission")) echo " hidden " ?> 
										<?php if (!empty($_SESSION['appointment']['reason']) && ($_SESSION['appointment']['reason'] === "other-concerns")) echo " selected" ?>
										>Other Concerns</option>
									<option class="guidance" value="consultation"
										<?php if (empty($_SESSION['appointment']['reason']) || ($_SESSION['appointment']['department'] !== "guidance")) echo " hidden " ?> 
										<?php if (!empty($_SESSION['appointment']['reason']) && ($_SESSION['appointment']['reason'] === "consultation")) echo " selected" ?>
										>Consultation</option>
									<option class="clinic" value="check-up"
										<?php if (empty($_SESSION['appointment']['reason']) || ($_SESSION['appointment']['department'] !== "clinic")) echo " hidden " ?> 
										<?php if (!empty($_SESSION['appointment']['reason']) && ($_SESSION['appointment']['reason'] === "check-up")) echo " selected" ?>
										>Check up</option>
									<option class="clinic" value="medical-reports"
										<?php if (empty($_SESSION['appointment']['reason']) || ($_SESSION['appointment']['department'] !== "clinic")) echo " hidden " ?> 
										<?php if (!empty($_SESSION['appointment']['reason']) && ($_SESSION['appointment']['reason'] === "medical-reports")) echo " selected" ?>
										>Medical Reports</option>
									<option class="ojt" value="submission-of-requirements"
										<?php if (empty($_SESSION['appointment']['reason']) || ($_SESSION['appointment']['department'] !== "ojt")) echo " hidden " ?> 
										<?php if (!empty($_SESSION['appointment']['reason']) && ($_SESSION['appointment']['reason'] === "submission-of-requirements")) echo " selected" ?>
										>Submission of Requirements</option>
									<option class="ojt" value="general-inquiry"
										<?php if (empty($_SESSION['appointment']['reason']) || ($_SESSION['appointment']['department'] !== "ojt")) echo " hidden " ?> 
										<?php if (!empty($_SESSION['appointment']['reason']) && ($_SESSION['appointment']['reason'] === "general-inquiry")) echo " selected" ?>
										>General Inquiry</option>
									<option class="prowear" value="buying-merchandises"
										<?php if (empty($_SESSION['appointment']['reason']) || ($_SESSION['appointment']['department'] !== "prowear")) echo " hidden " ?> 
										<?php if (!empty($_SESSION['appointment']['reason']) && ($_SESSION['appointment']['reason'] === "buying-merchandises")) echo " selected" ?>
										>Buying Merchandises</option>
									<option class="faculty" value="consultation"
										<?php if (empty($_SESSION['appointment']['reason']) || ($_SESSION['appointment']['department'] !== "faculty")) echo " hidden " ?> 
										<?php if (!empty($_SESSION['appointment']['reason']) && ($_SESSION['appointment']['reason'] === "consultation")) echo " selected" ?>
										>Consultation</option>
									<option class="faculty" value="other-concerns"
										<?php if (empty($_SESSION['appointment']['reason']) || ($_SESSION['appointment']['department'] !== "faculty")) echo " hidden " ?> 
										<?php if (!empty($_SESSION['appointment']['reason']) && ($_SESSION['appointment']['reason'] === "other-concerns")) echo " selected" ?>
										>Other concerns</option>
								</select>
							</div>
						</div>
						<div class="div">
							<div>
								<label for="choice">Faculty</label>
							</div>
							<div>
								<select class="faculty" name="faculty" id="faculty"
									<?php echo (empty($_SESSION['appointment']['faculty']) ? ' disabled' : '') ?>
								>
									<option value=""></option>
									<option value="shs" class="bold">Senior High School:</option>
									<option value="grace-pangilinan"
										<?php if (!empty($_SESSION['appointment']['faculty']) && ($_SESSION['appointment']['faculty'] === "grace-pangilinan")) echo " selected" ?>
									>Mary Grace Pangilinan</option>
									<option value="tina-mendoza"
									<?php if (!empty($_SESSION['appointment']['faculty']) && ($_SESSION['appointment']['faculty'] === "tina-mendoza")) echo " selected" ?>
									>Chritina Mendoza</option>
									<option value="shs" class="bold">Tertiary:</option>
									<option value="christian-torres"	
										<?php if (!empty($_SESSION['appointment']['faculty']) && ($_SESSION['appointment']['faculty'] === "christian-torres")) echo " selected" ?>
									>Christian Torres</option>
									<option value="jennilyn-silva"
										<?php if (!empty($_SESSION['appointment']['faculty']) && ($_SESSION['appointment']['faculty'] === "jennilyn-silva")) echo " selected" ?>
									>Jennilyn Silva</option>
									<option value="reynaldo-merced"
										<?php if (!empty($_SESSION['appointment']['faculty']) && ($_SESSION['appointment']['faculty'] === "reynaldo-merced")) echo " selected" ?>
									>Reynaldo Merced</option>
									<option value="allan-badilla"
										<?php if (!empty($_SESSION['appointment']['faculty']) && ($_SESSION['appointment']['faculty'] === "allan-badilla")) echo " selected" ?>
									>Allan Badilla</option>
									<option value="romeo-olympia"
										<?php if (!empty($_SESSION['appointment']['faculty']) && ($_SESSION['appointment']['faculty'] === "romeo-olympia")) echo " selected" ?>
									>Romeo Olympia</option>
									<option value="neth-portugues"
										<?php if (!empty($_SESSION['appointment']['faculty']) && ($_SESSION['appointment']['faculty'] === "neth-portugues")) echo " selected" ?>
									>Neth Portugues</option>
									<option value="kathleen"
									<?php if (!empty($_SESSION['appointment']['faculty']) && ($_SESSION['appointment']['faculty'] === "kathleen")) echo " selected" ?>
									>Maam Kathleen</option>
								</select>
							</div>
						</div>
						<div class="submit-cancel">
							<button class="submit-btn" name="submit">Submit</button>
							<a href="<?php echo $transaction_type  ?>UI.php" class="cancel">Cancel</a>
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
										
										<?php echo (!$isFull) ? "01:00 PM - 02:00 PMm" : "FULL" ?>
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
