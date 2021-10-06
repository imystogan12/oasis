<?php
	session_start();
	// echo "<pre>";
	// var_dump($_SESSION);
	// echo "</pre>";
?>
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery.show-more.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$(".sub-main").showMore({
		    minheight: 70,
		    animationspeed: 200,
		    buttoncss:'button-showmore'
		});
		$("#terms").on("click",function(){
			if ($('#terms').is(":checked")) {
				$('#continue-btn').prop('disabled', false);
			} else {
				$('#continue-btn').prop('disabled', true);
			}
		})
	});

</script>

<link rel="stylesheet" type="text/css" href="css/terms-agreement.css">
<div class="header-div">
	<p class="oasis">OASIS</p>
</div>
<div class="main">
	<div>
		<header class="header">Terms and Condition</header>
	</div>
	<div class="sub-main">
		<p>Thank you for trusting OASIS. We hope its services will be of use to you. This page contains any information that you must consider prior to using OASIS.</p>
		<div class="box1">
		<p>The “Terms and Conditions” within the site establish an agreement between you and STI College Sta. Maria, the creators and operators of OASIS, all associated services provided by the site, and last, any website affiliated with OASIS. For identifying purposes, the “services” aforementioned include scheduling appointments within the STI College Sta. Maria campus. These “Terms and Conditions” governs your use of the services OASIS provides.
		To put it shortly, it means that by accessing and/or using our services, you are hereby agreeing to all the terms and conditions written within this agreement. If you do not agree, then you may not use the services we provide. As used in this agreement, “you” is identified as any visitor, user, or other person who accesses our services. 
		We may change this Agreement at any given time and without prior notice. In the event that a change is made, it will be effective as soon as it is posted, with the most current version visible within the website.</p> 

		<p>1. ABOUT THE SERVICES
		Subject to the Terms and Conditions, OASIS will give you a non-transferable license to use the Services in accordance with the Terms and Conditions. 
		In using OASIS, you acknowledge that although the website provides services that offer assistance in creating appointments, it is still subject for approval based on the persons in charge within the specific department chosen.</p>

		<p>2. PERSONAL INFORMATION
		Understand that in order to be assisted, you are required to enter certain personal information that may include but are not limited to, your name, address, email, and phone number. In doing so, you acknowledge and agree that such information will be reviewed and approved by you, or someone authorized by you at the time of your appointment to ensure its accuracy. You also acknowledge that OASIS and STI College Sta. Maria may use the data or information you provide.</p>
		</div>
	</div>
	<form action="saveAppointment.php" method="POST">
		<div class="sub-main">
		
			<input type="checkbox" name="terms" id="terms">
			<label for="terms">I accept the terms of service</label>
		</div>
		<div>
			<a href="homepage.php"><button type="submit" class="button" id="continue-btn" disabled>Continue</button></a>
			<a href="confirmationPage.php"><button class="button">Cancel</button></a>
		</div>
		</div>
	</form>