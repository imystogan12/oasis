<?php
	include('database.php');  
	session_start();
	// $servername = "localhost";
	// $username = "root";
	// $password = "root";
	// $dbname = 'oasis';

	

	// $conn = new mysqli($servername, $username, $password, $dbname);
	// 	if ($conn->connect_error) {
 //  			die("Connection failed: " . $conn->connect_error);
	// 	}
	if (isset($_POST['submit'])) {
		$sql = "INSERT INTO faculty (dept_id, fname, lname, user_report_id)
			VALUES ( " . $_POST['dept_id'] . " , '" . $_POST['fname'] . "' , '" . $_POST['lname'] . "' , " . $_POST['program-head'] . ")";

			if ($conn->query($sql) === TRUE) {
  					echo "New record created successfully";
  				} else {
  					echo "Error: " . $sql . "<br>" . $conn->error;
  				}
  				header("Location: faculty.php");
  				exit;
	}


	$departments = [];

	$sql = "SELECT id, name, value FROM department WHERE value='faculty'" ;
	$result = $conn->query($sql);
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				$departments[] = $row;
			}
		} else {
			echo "not";
		}

		 // echo "<pre>";
			// var_dump($departments);
			//  echo "</pre>";

		$faculty = [];

		$sql = "SELECT id, fname, lname FROM user WHERE role = 'faculty'" ;
		$result = $conn->query($sql);
			if ($result->num_rows > 0) {
				while($row = $result->fetch_assoc()) {
					$faculty[] = $row;
				}
			} else {
				echo "not";
			}




?>
<link rel="stylesheet" type="text/css" href="css/addFaculty.css">
<form action="addFaculty.php" method="POST">
	<div class="main">
	<div>
		<p class="note">Add Faculty</p>		
	</div>
	<div class="details">
		<Label class="name">Department:</Label>
		<span class="name">Faculty</span>
		<input type="hidden" name="dept_id" value="<?php echo $departments['0']['id'] ?>">
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
		<label class="name">Program Head:</label>
		<select name="program-head" id="program-head" required>
			<option>Please Select</option>
			<?php foreach ($faculty as $person): ?>
				<option value="<?php echo $person['id'] ?>"> <?php echo $person['fname'] . ' ' . $person['lname'] ?></option>
			<?php endforeach; ?>
		</select>		
	</div>
	<div>
	<div class="submit-cancel">
		<button class="btn" name="submit">Add</button>
		<a class="btn cancel" href="faculty.php">Cancel</a>
	</div>
	</div>
</div>
</form>