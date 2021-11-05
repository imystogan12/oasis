<?php
	session_start();

	if (isset($_POST['login'])){
		$servername = "localhost";
		$username = "root";
		$password = "root";
		$dbname = 'oasis';

		$conn = new mysqli($servername, $username, $password, $dbname);
		if ($conn->connect_error) {
  			die("Connection failed: " . $conn->connect_error);
		}
		echo "Connected successfully";
		$sql = 'SELECT id, username, password, role, salt FROM user WHERE username="' . $_POST['username'] . '"';
		$result = $conn->query($sql);


		if ($result->num_rows > 0) {
  		// output data of each row
		  while($row = $result->fetch_assoc()) {
		     if(md5(md5( $_POST['password']) . md5($row['salt'])) === $row['password']) {
		     	echo "login successfully";
		     	unset($row['password']);
		     	unset($row['salt']);
		     	$_SESSION['user'] = $row;
		     	if ($row['role'] == "guard") {
		     		header("Location: guardDashboard.php");
   					exit;
   				} elseif ($row['role'] == "admin") {
		     		header("Location: src/admin/report.php");
   					exit;
   				} else {
		     		header("Location: dashboard.php");
   					exit;
   				}
		     	var_dump($_SESSION);
		     } else {
		     	echo "Failed login";
		     }
		  }
		} else {
		  echo "Failed login";
		}
	}

?>

<link rel="stylesheet" type="text/css" href="css/login.css">
<div class="header-div">
	<p class="oasis">OASIS</p>
	<a href="homepage.php"><button class="cancel">Cancel</button></a>
</div>
<body>
	<div class="main">
	<div class="left">
		<form>About Oasis</form>
	</div>

	<div class="right">
		<div class="right-div">
			<div class="faculty">FACULTY/STAFF</div>
			<form action="login.php" method="post">
				<div class="div">
					<label class="text">Username:</label>
					<input type="text" name="username" class="username">
				</div>

				<div class="div">
					<label class="text">Password:</label>
					<input type="password" name="password" class="password">
				</div>
				<p class="reset-pass div text"><a href="resetPasswordUI.php">Reset Password</a></p>
				<div class="login-button div text"><button class="login" name="login">Login</button></div>

			</form>
		</div>
	</div>
</body>