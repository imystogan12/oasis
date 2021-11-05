<?php  
	session_start();
	$servername = "localhost";
	$username = "root";
	$password = "root";
	$dbname = 'oasis';

	

	$conn = new mysqli($servername, $username, $password, $dbname);
		if ($conn->connect_error) {
  			die("Connection failed: " . $conn->connect_error);
		}
	if (isset($_POST['submit'])) {
		$sql = "INSERT INTO reason (dept_id, name, value)
			VALUES ( " . $_POST['department'] . " , '" . $_POST['name'] . "' , '" . $_POST['value'] . "' )";

			if ($conn->query($sql) === TRUE) {
  					echo "New record created successfully";
  				} else {
  					echo "Error: " . $sql . "<br>" . $conn->error;
  				}
  				header("Location: reason.php");
  				exit;
	}


	$departments = [];

	$sql = "SELECT id, name, value FROM department";
	$result = $conn->query($sql);
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				$departments[] = $row;
			}
		} else {
			echo "not";
		}



?>

<form action="addReason.php" method="POST">
	<div>
		<Label>Department</Label>
		<select  name="department" id="department">
			<option>Please Select</option>
			<?php foreach ($departments as $dept): ?>
				<option value="<?php echo $dept['id'] ?>"><?php echo $dept['name'] ?></option>
			<?php endforeach; ?>
			
		</select>
	</div>
	<div>
		<label>Name</label>
		<input type="text" name="name">
	</div>
	<div>
		<label>Value</label>
		<input type="text" name="value">
	</div>
	<div>
		<button name="submit">Submit</button>
	</div>
</form>