<?php  
	session_start();

	if (isset($_POST['submit'])) {
		// echo "<pre>";
		// var_dump($_POST);
		// echo "</pre>";
		// die;
		$servername = "localhost";
		$username = "root";
		$password = "root";
		$dbname = 'oasis';

		$conn = new mysqli($servername, $username, $password, $dbname);
		if ($conn->connect_error) {
  			die("Connection failed: " . $conn->connect_error);
		}
		$sql = "INSERT INTO department (name, value)
				VALUES ('" . $_POST['name'] . "' , '" . $_POST['value'] . "')";

				if ($conn->query($sql) === TRUE) {
  					echo "New record created successfully";
  				} else {
  					echo "Error: " . $sql . "<br>" . $conn->error;
  				}
  				header("Location: admin.php");
  				exit;
  	}

?>
<link rel="stylesheet" type="text/css" href="css/addDepartment.css">
<form action="addDepartment.php" method="POST">
	<div class="main">
	<div>
		<p class="note">Add Department</p>		
	</div>
	<div class="details">
		<label class="name">Name:</label>
		<input type="text" name="name" required="">
	</div>
	<div class="details">
		<label class="name">Value:</label>
		<input type="text" name="value" required="">
	</div>
	<div class="submit-cancel">
		<button class="btn" name="submit">Add</button>
		<a class="btn cancel" href="department.php">Cancel</a>
	</div>
	</div>
</form>