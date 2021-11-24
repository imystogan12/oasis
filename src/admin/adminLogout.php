<?php 
	include '../../config.php';
	//session_start();
	if (isset($_POST['logout'])) {
		unset($_SESSION);
		
		header("Location: " . $siteUrl . "/oasis/homepage.php");
   		exit;
	}
?>
<style type="text/css">
	@import url('https://fonts.googleapis.com/css?family=Numans');
	.adminLogoutBTN {
		padding: 5px;
		margin-top: 10px;
		margin-right: 15px;
		font-family: Numans;
		font-style: bold;
		position: absolute;
		right: 0;
		font-size: 12px;
		padding: 10px 5px 10px 5px;
		text-transform: uppercase;
		text-align: center;
		background-color: #006cb7;
		border-radius: 8px;
		color: white;
		border-style: none;
	}
	.adminLogoutBTN:hover {
		padding: 5px;
		margin-top: 10px;
		margin-right: 15px;
		font-family: Numans;
		font-style: bold;
		position: absolute;
		right: 0;
		font-size: 12px;
		padding: 10px 5px 10px 5px;
		text-transform: uppercase;
		text-align: center;
		background-color: #006cb7;
		border-radius: 8px;
		color: white;
		border-style: none;
		border-style: 1px solid #fff;
	}
	.adminLogoutBTN {
		margin-top: 20px;
	}


</style>
<div class="logout">
	<form method="post">
		<button name="logout" class="adminLogoutBTN">Logout</button>
	</form>
</div>


