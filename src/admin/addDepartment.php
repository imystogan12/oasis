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

<form action="addDepartment.php" method="POST">
	<div>
		<label>Name:</label>
		<input type="text" name="name">
	</div>
	<div>
		<label>Value:</label>
		<input type="text" name="value">
	</div>
	<div>
		<button name="submit">Submit</button>
		<a href="department.php">Cancel</a>
	</div>
</form>