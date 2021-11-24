<?php  
	// include('database.php');
	include '../../database.php';
	session_start();
	// $servername = "localhost";
	// $username = "root";
	// $password = "root";
	// $dbname = 'oasis';

	// $conn = new mysqli($servername, $username, $password, $dbname);
	// 	if ($conn->connect_error) {
 //  			die("Connection failed: " . $conn->connect_error);
	// 	}

	$id = '';
	if (isset($_GET['id'])) {
		// get $id in url
		$id = $_GET['id'];

		// get in department table when id = $id
		$reason = [];

		$sql = "SELECT id, fname, lname, username, password, role FROM user WHERE id=" . $id;
		$result = $conn->query($sql);

		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
					$reason[] = $row;
				}
		} else {
			echo "not";
		}	
	}

	

	// var_dump($_POST);die;

	if (isset($_POST['submit'])) {
		$sql = "UPDATE user SET id='" . $_POST['id'] . "'  , username='" . $_POST['username'] . "' " . "WHERE id=" . $_POST['id'] ;

		if ($conn->query($sql) === TRUE) {
			echo "New record created successfully";
		} else {
			echo "Error: " . $sql . "<br>" . $conn->error;
		}
		header("Location: reason.php");
		exit;
	}

?>
<link rel="stylesheet" type="text/css" href="css/editReason.css">
<form action="editReason.php?id=<?php echo $id ?>" method="POST">
	<div class="main">
	<div>
		<p class="note">Edit Reason</p>		
	</div>
	<div class="details">
		<label class="name">Name:</label>
		<input type="text" name="name" value="<?php echo $user[0]['name'] ?>">
		<input type="hidden" name="id" value="<?php echo $id; ?>">
	</div>
	<div class="details">
		<label class="name">Value:</label>
		<input type="text" name="value" value="<?php echo $user[0]['value'] ?>">
	</div>
	<div class="submit-cancel">
		<button class="btn" name="submit">Edit</button>
		<a class="btn" href="user.php">Cancel</a>
	</div>
	</div>
</form>