<?php
	
	if (!isset($_SESSION)){
		session_start();
	}

	include "config.php";
	

	if(isset($_POST['submitCred'])){


		$user = mysqli_real_escape_string($mysqli, $_POST['CustomerName']);
		$pass = mysqli_real_escape_string($mysqli, $_POST['Password']);


		if ($user != "" && $pass != ""){

			$cred_query = "select email, password from user where email='".$user."' and password='".$pass."'";

			$result = mysqli_query($mysqli,$cred_query);
			$flag = mysqli_fetch_row($result);


			if($flag === null  ){
				echo '<script language="javascript">';
				echo 'alert("No such user with the given password in the system")';
				echo '</script>';
			}else{

			    $query_user_id = "select distinct id from user where email = '$user'";
                $query_user_id_res = mysqli_query($mysqli, $query_user_id);
                $row = mysqli_fetch_assoc($query_user_id_res);
                $_SESSION['user_id'] = $row["id"];

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
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <script type="text/javascript">
        function checkCred() {
            let customer_id = document.forms["Login"]["c_name"].value;
            let customer_pass = document.forms["Login"]["pass"].value;
            if (customer_id == null || customer_id === "") {
                alert("Customer Name can't be blank");
                return false;
            } else if (customer_pass == null || customer_pass === "") {
                alert("Password can't be blank");
                return false;
            }
            return true;
        }
    </script>
</head>

<body>
    <div style="text-align:center">
        <h1> Tasks&Managers Login </h1></div>
    <br>

    <form name="Login" method="post" action="" onsubmit="return checkCred();">

        <div class="form-group row justify-content-center" style="text-align:center">
            <label for="colFormLabel" class="col-md-2 col-form-label"><b>Username</b></label>
            <div class="col-md-2" >
                <input type="text" name="CustomerName" class="form-control" id="user" >
            </div>
        </div>

        <div class="form-group row justify-content-center" style="text-align:center">
            <label for="colFormLabel" class="col-md-2 col-form-label"><b>Password</b></label>
            <div class="col-md-2" >
                <input type="password" name="Password" class="form-control" id="pass" >
            </div>
        </div>


        <div style="text-align:center"><br>
            <input class="btn btn-primary" type="submit" value="Login" name="submitCred" id="submitCred">
        </div>

    </form>

    <form>
        <div style="text-align:center">
            <input class="btn btn-primary" type="submit" value="Sign Up" name="signUp" id="signUp">
        </div>
    </form>



</body>

</html>
