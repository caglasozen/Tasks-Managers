<?php
	include "config.php";
	
	session_start();
	
	if(isset($_POST['submitCred'])){
		
		
		$user = mysqli_real_escape_string($mysqli,$_POST['CustomerName']);
		$pass = mysqli_real_escape_string($mysqli,$_POST['Password']);
		
		
		if ($user != "" && $pass != ""){
						
			$cred_query = "select email, password from User where email='".$user."' and password='".$pass."'";
			
			$result = mysqli_query($mysqli,$cred_query);
			$flag = mysqli_fetch_row($result);
			
			
			if($flag === null  ){
				echo '<script language="javascript">';
				echo 'alert("No such user with the given password in the system")';
				echo '</script>';
			}else{
				
				$_SESSION['user_name'] = $user;
				$_SESSION['password'] = $pass;
				header('Location: index.php');
			}
			
		}
		else
		{
			echo '<script language="javascript">';
			echo 'alert("Please enter a username and a password")';
			echo '</script>';
		}
		
	}
	
	if(isset($_POST['signUp'])){
		header('Location: signUp.php');
	}
?>


<html>
<head>
<title>form.php</title>
<link rel="stylesheet" type="text/css" href="form_style.css">
<script type="text/javascript">
function checkCred()
{
	var customer_id = document.forms["Login"]["c_name"].value;
	var customer_pass = document.forms["Login"]["pass"].value;
	if (customer_id==null || customer_id=="")
	{
		alert("Customer Name can't be blank");
		return false;
	}
	else if (password==null || password=="")
	{
		alert("Password can't be blank");
		return false;
	}
	return true;
}
</script>
</head>
<body>
<div style="text-align:center"><h1> TASKS&MANAGERS LOGIN </h1></div>
<br>
<form name="Login" method="post" action="" onsubmit="return checkCred();" >
<div style="text-align:center"> Username: <input type="text" name="CustomerName" id = "c_name" /> </div>
<div style="text-align:center"> Password: <input type="password" name="Password" id = "pass" placeholder = "*******" /> </div>
<div style="text-align:center"> <input type="submit" value="Login" name = "submitCred" id = "submitCred" ></input></div>
<div style="text-align:center"> <input type="submit" value="Sign Up" name = "signUp" id = "signUp" ></input></div>
</form>
</body>
</html>
