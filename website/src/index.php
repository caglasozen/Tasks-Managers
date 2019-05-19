<?php
	include "config.php";
	session_start();

	$user = $_SESSION['user_name'];
	$userPrint = strtoupper($user);
	$c_id = $_SESSION['password'];

	//Get the variables you may need from the database using the email you got in the login page.

	$query = "SELECT * FROM User WHERE email =  '".$user."' ";
	$result = mysqli_query($mysqli,$query);
	$row = mysqli_fetch_array($result);
	$email = $row['email'];
	$user = $row['ID'];
	$f_name =  $row['first_name'];
	$l_name =  $row['last_name'];

	if($f_name == ''){
        header('Location: form.php');
    }

	if(array_key_exists('Logout',$_POST)){
	    header('Location: logOut.php');

	}

	if (isset($_POST['account'])) {
        header('Location: account.php');
    }

?>

<!doctype html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css"
          integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
</head>
<body>

    <div class="d-flex flex-lg-row-reverse">
        <div class="p-2">
            <form method="post">
                <input class="btn btn-primary" type="submit" name="Logout" id="Logout" value="Logout" />
            </form>
        </div>


        <div class="p-2">
            <form method="post" >
                <input class="btn btn-info" type="submit" name="account" value="Account" />
            </form>
        </div>


        <h1 class="mr-auto" style="text-align: center">Welcome <?php echo $f_name. " ".$l_name ?> </h1>
    </div>

<br>


<h2>Projects</h2>

<div class= "projects" >

    <div class="radioLeft">
        <br>
        <input type="radio" name="rad_list" id="test" value = "test" >
        <label for="test"><?php echo test ?> </label>
    </div>

</div>



</body>
</html>