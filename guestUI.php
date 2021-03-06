<?php
	session_start();
	$_SESSION['session_type'] = "guest";
	if (empty($_SESSION['guest_comp_count'])) {
		$_SESSION['guest_comp_count'] = 0;
	}
	$comp_count = $_SESSION['guest_comp_count'];
	$_SESSION['guest_comp_count'] = 0;

	$showSubmit = true;
	if ($comp_count > 0) $showSubmit = false; 
?>

<link rel="stylesheet" type="text/css" href="css(1)-backup/guestUI.css">
<div class="header-div">
	<img src="https://i.imgur.com/FTPJl6s.png" style="height:75px;">
</div>
<body> 
	<div class="div-main">
		<div class="left">
			<div class="guest">GUEST</div>
			<form action="guest-signup.php" method="post">

				<div class="fname-lname form-row">
					<div class="div-line form-straight">
						<label class="text">First Name:<span class="required">*</span></label><br>
						<input placeholder="First Name" type="text" name="fname" required
							value="<?php echo !empty($_SESSION['guest']) ? $_SESSION['guest']['guest_fname'] : '' ?>"
							><br>
					</div>
					<div class="form-straight">
						<label class="text">Last Name:<span class="required">*</span></label><br>
						<input placeholder="Last Name" type="text" name="lname" required
							value="<?php echo !empty($_SESSION['guest']) ? $_SESSION['guest']['guest_lname'] : '' ?>"
							><br>
					</div>
				</div>
				<div class="form-row">
					<label class="text">Home Address:<span class="required">*</span></label><br>
					<input placeholder="Home Address" type="text" name="guest-address"		required value="<?php echo !empty($_SESSION['guest']) ? $_SESSION['guest']['guest_address'] : '' ?>"
						><br>
				</div>
				<div class="form-row">
					<label class="text">Contact Number:<span class="required">*</span></label><br>
					<input placeholder="Contact Number" type="text" name="guest-number" 	required value="<?php echo !empty($_SESSION['guest']) ? $_SESSION['guest']['guest_number'] : '' ?>"
						><br>
				</div>
				<div class="form-row">
					<label class="text">Email:<span class="required">*</span></label><br>
					<input placeholder="Email Address" type="text" name="guest-mail"		 required value="<?php echo !empty($_SESSION['guest']) ? $_SESSION['guest']['guest_email'] : '' ?>"
						><br>
				</div>
				<div class="form-row">
					<label class="text">Companion:<span class="required">*</span></label><br>
					<input type="number" name="guest-companion" min="0" max="2"
						value="<?php echo !empty($_SESSION['guest']) ? $_SESSION['guest']['guest_companion'] : 0 ?>"
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
				<div class="top-info">COMPANION</div>
				
				<div class="sub-main">
					<form action="guest-companion.php" method="post">
					<?php for ($i = 0; $i < $comp_count; $i++): ?>
					<div class="companion-FLname companion-line">

						<div class="right-fname">
							<label class="text">First name:<span class="required">*</span></label><br>
							<input placeholder="First Name" type="text" 
								name="companion-fname [<?php echo $i ?>]" required
								value=<?php echo (!empty($_SESSION['guest']['companion'][$i])) ? $_SESSION['guest']['companion'][$i]['gCompanion_fname'] : '' ?>
								><br>
						</div>
						<div>
							<label class="text">Last Name:<span class="required">*</span></label><br>
							<input placeholder="Last Name" type="text" 
								name="companion-lname [<?php echo $i ?>]" required
								value=<?php echo (!empty($_SESSION['guest']['companion'][$i])) ? $_SESSION['guest']['companion'][$i]['gCompanion_lname'] : '' ?>
								><br>
						</div>
					</div>
					<div class="companion-line">
						<label class="text">Email:<span class="required">*</span></label><br>
						<input placeholder="Email" type="text" 
							name="companion-email [<?php echo $i ?>]" required
							value=<?php echo (!empty($_SESSION['guest']['companion'][$i])) ? $_SESSION['guest']['companion'][$i]['gCompanion_email'] : '' ?>
							><br>
					</div>
					<div class="companion-line">
						<label class="text">Contact Number:<span class="required">*</span></label><br>
						<input placeholder="Contact Number" type="text"
							name="companion-contactnum [<?php echo $i ?>]" required
							value=<?php echo (!empty($_SESSION['guest']['companion'][$i])) ? $_SESSION['guest']['companion'][$i]['gCompanion_number'] : '' ?>
							><br>
							<hr>
					</div>
					<?php endfor;?>
					<div class="companion-line ok-button">
						<button class="OK-btn">OK</button>
					</div>
					</form>
				</div>
				
				
				</div>
			</div>
			
			<?php endif; ?>
		</div>

</body>
