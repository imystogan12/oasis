<?php
	session_start();
	echo "<pre>";
	var_dump($_SESSION);
	echo "</pre>"; 
	$_SESSION['session_type'] = "student";
	$comp_count = $_SESSION['comp_count'];
	$_SESSION['comp_count'] = 0;

	$showSubmit = true;
	if ($comp_count > 0) $showSubmit = false; 
?>

<link rel="stylesheet" type="text/css" href="css/studentUI.css">
<body> 
	<div class="div-main">
		<div class="left">
			<p>Student</p>
			<form action="student-signup.php" method="post">

				<div class="fname-lname form-row">
					<div class="div-line form-straight">
						<label>First Name:<span class="required">*</span></label><br>
						<input placeholder="First Name" type="text" name="fname" required
							value="<?php echo !empty($_SESSION['student']) ? $_SESSION['student']['student_fname'] : '' ?>"
						><br>
					</div>
					<div class="form-straight">
						<label>Last Name:<span class="required">*</span></label><br>
						<input placeholder="Last Name" type="text" name="lname" required 
								value="<?php echo !empty($_SESSION['student']) ? $_SESSION['student']['student_lname'] : '' ?>"
						><br>
					</div>
				</div>
				<div class="form-row">
					<label>Student Number:<span class="required">*</span></label><br>
					<input placeholder="Student Number" type="text" name="student-num" required 
							value="<?php echo !empty($_SESSION['student']) ? $_SESSION['student']['student_num'] : '' ?>"
						><br>
				</div>
				<div class="form-row">
					<label>Email:<span class="required">*</span><span class="word">(<b>Note:</b>use your school email</span>)</label><br>
					<input placeholder="Email" type="email" name="mail" required 
							value="<?php echo !empty($_SESSION['student']) ? $_SESSION['student']['student_email'] : '' ?>">
					<br>
				</div>
				<div class="course-section form-row">
					<div class="div-line form-straight">
						<label>Course:<span class="required">*</span></label><br>
						<input placeholder="Course" type="text" name="student-course" required 
								value="<?php echo !empty($_SESSION['student']) ? $_SESSION['student']['student_course'] : '' ?>"
						><br>
					</div>
					<div class="form-straight">
						<label>Section:<span class="required">*</span></label><br>
						<input placeholder="Section" type="text" name="student-section" required 		value="<?php echo !empty($_SESSION['student']) ? $_SESSION['student']['student_section'] : '' ?>"
						><br>
					</div>
				</div>
				<div class="form-row">
					<label>Companion:<span class="required">*</span></label><br>
					<input type="number" name="student-companion" min="0" max="2" 
							value="<?php echo !empty($_SESSION['student']) ? intval($_SESSION['student']['student_companion']) : 0 ?>"
					><br>
				</div>
				<div class="submit-cancel form-row">
					<?php if ($showSubmit): ?> <button>Submit</button> <?php endif; ?>
					<a class="cancel-button" href="homepage.php">Cancel</a>	
				</div>
			</form>
		</div>
		<div class="right">
			<?php if ($comp_count > 0): ?>
			
			<div class="background">
				<div class="top-info">Companion Information</div>
				
				<div class="sub-main">
					<form action="student-companion.php" method="post">
					<?php for ($i = 0; $i < $comp_count; $i++): ?>
						<hr>
					<div class="companion-FLname companion-line">

						<div class="right-fname">
							<label>First name:<span class="required">*</span></label><br>
							<input placeholder="First Name" type="text" name="companion-fname [<?php echo $i ?>]" required
								value=<?php echo (!empty($_SESSION['student']['companion'][$i])) ? $_SESSION['student']['companion'][$i]['sCompanion_fname'] : '' ?>
							><br>
						</div>
						<div>
							<label>Last Name:<span class="required">*</span></label><br>
							<input placeholder="Last Name" type="text" name="companion-lname [<?php echo $i ?>]" required
								value=<?php echo (!empty($_SESSION['student']['companion'][$i])) ? $_SESSION['student']['companion'][$i]['sCompanion_lname'] : '' ?>
							><br>
						</div>
					</div>
					<div class="companion-line">
						<label>Email:<span class="required">*</span></label><br>
						<input placeholder="Email" type="text" name="companion-email [<?php echo $i ?>]" required 
							value=<?php echo (!empty($_SESSION['student']['companion'][$i])) ? $_SESSION['student']['companion'][$i]['sCompanion_email'] : '' ?>
						><br>
					</div>
					<div class="companion-line">
						<label>Contact Number:<span class="required">*</span></label><br>
						<input placeholder="Contact Number" type="text" name="companion-contactnum [<?php echo $i ?>]" required
							value=<?php echo (!empty($_SESSION['student']['companion'][$i])) ? $_SESSION['student']['companion'][$i]['sCompanion_number'] : '' ?>
						><br>
					</div>
					<?php endfor;?>
					<div class="companion-line ok-button">
						<button class="OK-button">OK</button>
					</div>
					</form>
				</div>
				
				
				</div>
			</div>
			
			<?php endif; ?>
		</div>

</body>
