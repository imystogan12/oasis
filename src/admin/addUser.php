<?php  
	session_start();
	if (isset($_POST['submit'])) {
		
		$servername = "localhost";
		$username = "root";
		$password = "root";
		$dbname = 'oasis';

		$conn = new mysqli($servername, $username, $password, $dbname);
		if ($conn->connect_error) {
  			die("Connection failed: " . $conn->connect_error);
		}

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


<form action="addUser.php" method="POST">
	<div>
		<label>First Name:</label>
		<input type="text" name="fname">
	</div>
	<div>
		<label>Last Name:</label>
		<input type="text" name="lname">
	</div>
	<div>
		<label>Username:</label>
		<input type="text" name="username">
	</div>
	<div>
		<label>Password:</label>
		<input type="password" name="password">
	</div>
	<div>
		<label>Role:</label>
		<select name="role" id="role">
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
	<div>
		<button name="submit">Submit</button>
		<a href="user.php">Cancel</a>
	</div>
</form>