<?php
	include "config.php";
	session_start();
	
	function logOut() {
		echo "You are logging out of the system...";
		session_destroy();
		header('Location: form.php');
	}
	
	
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
	
	
	if(!isset($user)){
		header('Location: form.php');
	}
	
	if(array_key_exists('Logout',$_POST)){
		logOut();
	}
	
?>

<!doctype html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="index_style.css">
</head>
<body>
<center> <h1>Welcome <?php echo $f_name.$l_name ?> </h1></center>

<h2>Projects</h2>

<div class= "projects" > <center>
<!-- <?php
	global $mysqli;
	global $user;
	$query_list = "WITH TeamMember( team_ID ) AS ( SELECT team_ID FROM Member WHERE userID = '".$user."' ) SELECT project_ID, name FROM TeamMember as TM, Team  as T WHERE T.team_ID = TM.team_ID";
	$result_list = mysqli_query($mysqli,$query_list);
	while ($row_list = mysqli_fetch_array($result_list)) { ?>
<div class="radioLeft">
<input type="radio" name="rad_list" id="<?php $row_list['project_ID']?>" value = "<?php echo $row_list['project_ID']?>" >
<label for="<?php $row_list['project_ID']?>"><?php echo $row_list['project_ID']; ?> </label>
</div>
<?php } ?> -->

<div class="radioLeft">
<input type="radio" name="rad_list" id="test" value = "test" >
<label for="test"><?php echo test ?> </label>
</div>

</div>


<form method="post">  <center>
<input type="submit" name="Logout" id="Logout" value="Logout" /><br/>
</form>

</body>
</html>
