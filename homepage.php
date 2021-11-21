<?php
	include('database.php');
	session_start();
	// echo "<pre>";
	// var_dump($_SESSION);
	// echo "</pre>";
	if (isset($_POST['login'])){
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
   				} elseif ($row['role'] == "cashier" || $row['role'] == "registrar" || $row['role'] == "admission") {
		     		header("Location: autoAcceptDashboard.php");
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
<!DOCTYPE html>
<html>
<head>
	<title>OASIS | HOMEPAGE</title>
	 <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  	 <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  	 <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script> -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous" />
	 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" />
  	 
  	 <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  	 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"> </script>
 	 <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
 <!--  	 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
 	 <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script> -->
 	 <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"/>


	 <link rel="stylesheet" type="text/css" href="css(1)-backup/HomePage.css"/>

	<div class="header-div">
		<img src="https://i.imgur.com/FTPJl6s.png" style="height:75px;">
		<button type="button" class="btn btn-info btn-round" data-toggle="modal" data-target="#loginModal">Login</button>
	</div>

<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      
        	<div class="login">
        		
				  <div class="form">
				    <form class="login-form" action="homepage.php" method="post">
				      <span class="material-icons">lock</span>
						<input type="text" name="username" class="username" placeholder="Username">
					<input type="password" name="password" class="password" placeholder="Password">
				      <button class="login" name="login">Login</button>
				    </form>  
				    <br>
				    <button type="button" data-dismiss="modal" aria-label="Close">CANCEL</button>
					</div>
  </div>
</div>
	</div>
</div></div>

<body>

	<div class="main">
	<div class="left">

		 <div id="myCarousel" class="carousel slide" data-interval="3000">
		 <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
		  <ol class="carousel-indicators">
		    <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
		    <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
		  </ol>
		  <div class="carousel-inner">
		    <div class="carousel-item active">
		      <img class="d-block w-100" src="https://i.imgur.com/L8z8LZ6.png" alt="First slide" draggable="false">
		    </div>
		    <div class="carousel-item">
		      <img class="d-block w-100" src="https://i.imgur.com/iE7ssjq.png" alt="Second slide" draggable="false">
		    </div>
		  </div>
		  <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
		    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
		    <span class="sr-only">Previous</span>
		  </a>
		  <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
		    <span class="carousel-control-next-icon" aria-hidden="true"></span>
		    <span class="sr-only">Next</span>
		  </a>
		</div>
	</div>
	</div>

	<div class="right">
	<div class="sub-right">
			<div>
				<!-- <img src="https://i.imgur.com/KkEPYyJ.jpg" style="margin-top:3px;margin-left:3px;width:150px;height:150px;" draggable="false"> -->
				<div class="question">Are you a?</div>
				<br>
				<div>
					<a href="studentUI.php"><button class="choice-button">Student</button></a> 
				</div>
				<div>
					<span class="or">Or</span>
				</div>
				<div>
					<a href="guestUI.php"><button class="choice-button2">Visitor</button></a>
				</div>	
			</div>
			
		</div>
	</div>
	</div>
</body>
</html>