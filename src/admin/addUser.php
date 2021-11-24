<?php
	// include('database.php');
	include '../../database.php';  
	session_start();
	if (isset($_POST['submit'])) {
		
		// $servername = "localhost";
		// $username = "root";
		// $password = "root";
		// $dbname = 'oasis';

		// $conn = new mysqli($servername, $username, $password, $dbname);
		// if ($conn->connect_error) {
  // 			die("Connection failed: " . $conn->connect_error);
		// }

		$salt = 'fds32f';
		$password = md5(md5( $_POST['password']) . md5($salt));

		$sql = "INSERT INTO user (fname, lname, username, password, role, salt)
				VALUES ('" . $_POST['fname'] . "' , '" . $_POST['lname'] . "' ,
							'" . $_POST['username'] . "' , '" . $password . "' , '" . $_POST['role'] . "' , '" . $salt . "')";

				if ($conn->query($sql) === TRUE) {
  					echo "New record created successfully";
  				} else {
  					echo "Error: " . $sql . "<br>" . $conn->error;
  				}
  					header("Location: user.php");
  					exit;
	}
?>

<link rel="stylesheet" type="text/css" href="css/addUser.css">
<form action="addUser.php" method="POST">
	<div class="main">
	<div>
		<p class="note">Add User</p>		
	</div>
	<div class="details">
		<label class="name">First Name:</label>
		<input type="text" name="fname" required>
	</div>
	<div class="details">
		<label class="name">Last Name:</label>
		<input type="text" name="lname" required>
	</div>
	<div class="details">
		<label class="name">Username:</label>
		<input type="text" name="username" required>
	</div>
	<div class="details">
		<label class="name">Password:</label>
		<input type="password" name="password" required>
	</div>
	<div class="details">
		<label class="name">Role:</label>
		<select name="role" id="role" required>
			<option>Please Select</option>
			<option>Cashier</option>
			<option>Registrar</option>
			<option>Admission</option>
			<option>Guidance</option>
			<option>Clinic</option>
			<option>OJT</option>
			<option>Prowear</option>
			<option>Faculty</option>
			<option>Guard</option>
		</select>
	</div>
	<div class="submit-cancel">
		<button class="btn" name="submit">Add</button>
		<a class="btn cancel" href="user.php">Cancel</a>
	</div>
	</div>
</form>