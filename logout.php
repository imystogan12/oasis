<?php 
	//session_start();
	if (isset($_POST['logout'])) {
		unset($_SESSION);

		header("Location: homepage.php");
   		exit;
	}
?>
<style type="text/css">
	.logout {
		text-align: right;
		margin: 5px;
	}
	.logoutBTN {
		padding: 5px;
	}

</style>
<div class="logout">
	<form method="post">
		<button name="logout" class="logoutBTN">Logout</button>
	</form>
</div>


