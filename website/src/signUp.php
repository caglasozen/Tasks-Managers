<?php 
	include "config.php";
	session_start();

	if (isset($_POST['sign'])) {
        header('Location: index.php');
    }


?>
<!DOCTYPE html>
<html>
<head>
	<title> SIGN UP</title>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">

</head>
<body>
<div>
	<?php
	if (isset($_POST["sign"])) {
		$email = $_POST['email'];
		$first_name = $_POST['first_name'];
		$last_name = $_POST['last_name'];
		$password = $_POST['password'];
		$experience = $_POST['experience'];

		$sql = "INSERT INTO user (ID, email, password, first_name, last_name,experience) VALUES (LAST_INSERT_ID(), '$email', '$password' ,'$first_name','$last_name','	$experience')";
		$insertq = mysqli_query($mysqli, $sql);	

	}

	?>
</div>

<div >
	<form action="signUp.php" method="POST">
		<div class="container">

			<div class="row justify-content-center">
                <div class="col-sm-4"></div>
				<div class="col-sm-3">
					<h1> Sign Up </h1>
					<p> Please fill the form to create account </p>

					<label for="email"> <b> Email </b> </label>
					<input class="form-control" type="text" name="email" required>

					<label for="first_name"> <b> Name </b> </label>
					<input class="form-control" type="text" name="first_name" required>

					<label for="last_name"> <b> Surname </b></label>
					<input class="form-control" type="text" name="last_name" required>

					<label for="password"> <b> Password </b></label>
					<input class="form-control" type="password" name="password" required>

					<label for="experience"><b> Experience </b></label>
					<input class="form-control" type="text" name="experience" required>
					<br>
					
					<input class="btn btn-primary" type="submit" name="sign" value="Sign Up"> </input>
				</div>
                <div class="col-sm-4"></div>
			</div>

		</div>

	</form>
	
</div>





</body>
</html>
