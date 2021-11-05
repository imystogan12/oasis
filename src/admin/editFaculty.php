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

	$id = '';
	if (isset($_GET['id'])) {
		// get $id in url
		$id = $_GET['id'];

		// get in department table when id = $id
		$faculty = [];

		$sql = "SELECT id, dept_id, fname, lname, user_report_id  FROM faculty WHERE id=" . $id;
		$result = $conn->query($sql);

		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
					$faculty[] = $row;
				}
		} else {
			echo "not";
		}	
	}

	

	// var_dump($_POST);die;

	if (isset($_POST['submit'])) {
		$sql = "UPDATE faculty SET dept_id='" . $_POST['dept_id'] . "', fname='" . $_POST['fname'] . "', lname='" . $_POST['lname'] . "' " . "WHERE id=" . $_POST['id'] ;

		if ($conn->query($sql) === TRUE) {
			echo "New record created successfully";
		} else {
			echo "Error: " . $sql . "<br>" . $conn->error;
		}
		header("Location: faculty.php");
		exit;
	}

	$facultyList = [];

	$sql = "SELECT id, fname, lname FROM user WHERE role = 'faculty'" ;
	$result = $conn->query($sql);
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				$facultyList[] = $row;
			}
		} else {
			echo "not";
		}
?>

<form action="editFaculty.php" method="POST">
	<div>
		<input type="hidden" name="id" value="<?php echo $faculty[0]['id'] ?>">
		<Label>Department:</Label>
		<span>Faculty</span>
		<input type="hidden" name="dept_id" value="<?php echo $faculty[0]['dept_id'] ?>">
	</div>
	<div>
		<label>First Name</label>
		<input type="text" name="fname" value="<?php echo $faculty[0]['fname'] ?>">
	</div>
	<div>
		<label>Last Name</label>
		<input type="text" name="lname" value="<?php echo $faculty[0]['lname'] ?>">
	<div>
		<label>Program Head</label>
		<select name="program-head" id="program-head">
			<option>Please Select</option>
			<?php foreach ($facultyList as $person): ?>
				<option value="<?php echo $person['id'] ?>"
					<?php echo ($person['id'] === $faculty[0]['user_report_id']) ? " selected" : "" ?>
				>
					<?php echo $person['fname'] . ' ' . $person['fname'] ?>
				</option>
			<?php endforeach; ?>
		</select>		
	</div>
	</div>
	<div>
		<button name="submit">Submit</button>
	</div>
</form>