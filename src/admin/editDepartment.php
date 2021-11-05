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
		$department = [];

		$sql = "SELECT id, name, value FROM department WHERE id=" . $id;
		$result = $conn->query($sql);

		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
					$department[] = $row;
				}
		} else {
			echo "not";
		}	
	}

	

	// var_dump($_POST);die;

	if (isset($_POST['submit'])) {
		$sql = "UPDATE department SET name='" . $_POST['name'] . "', value='" . $_POST['value'] . "' " . "WHERE id=" . $_POST['id'] ;

		if ($conn->query($sql) === TRUE) {
			echo "New record created successfully";
		} else {
			echo "Error: " . $sql . "<br>" . $conn->error;
		}
		header("Location: department.php");
		exit;
	}

?>

<form action="editDepartment.php?id=<?php echo $id ?>" method="POST">
	<div>
		<label>Name</label>
		<input type="text" name="name" value="<?php echo $department[0]['name'] ?>">
		<input type="hidden" name="id" value="<?php echo $id; ?>">
	</div>
	<div>
		<label>Value</label>
		<input type="text" name="value" value="<?php echo $department[0]['value'] ?>">
	</div>
	<div>
		<button name="submit">Submit</button>
	</div>
</form>