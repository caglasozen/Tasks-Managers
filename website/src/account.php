<?php
include "config.php";
session_start();

$user = $_SESSION['user_id'];



$query = "SELECT * FROM user WHERE ID =  '".$user."' ";
$result = mysqli_query($mysqli,$query);
$row = mysqli_fetch_array($result);
$u_email = $row['email'];
$f_name =  $row['first_name'];
$l_name =  $row['last_name'];
$exp = $row['experience'];
$u_pass = $row['password'];

if (isset($_POST['submit'])) {
    header('Location: index.php');
}
if(array_key_exists('GoBack',$_POST)){
        header('Location: index.php');
    }

?>

<!DOCTYPE html>
<html>
<head>
    <title> ACCOUNT</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">

</head>
<body>
<div>
    <?php

    if (isset($_POST["submit"])){

        $email = $_POST['email'];
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $password = $_POST['password'];
        $experience = $_POST['experience'];

        $sql = "UPDATE user SET email = '$email', password = '$password', first_name = '$first_name', last_name ='$last_name' ,experience = '$experience'
        WHERE ID = '$user' ";
        $insertq = mysqli_query($mysqli, $sql);

    }

    ?>
</div>

<div >
    <div class="d-flex justify-content-start">
        <form  method="post">
            <input class="btn btn-info" type="submit" name="GoBack" id="GoBack" value="Go Back">
        </form>
    </div>
    <form action="account.php" method="POST">
        <div class="container">

            <div class="row justify-content-center">
                <div class="col-sm-4"></div>
                <div class="col-sm-3">
                    <h1> Account Page</h1>

                    <label for="email"> <b> Email </b> </label>
                    <input class="form-control" type="text" name="email" value = "<?php echo $u_email?> ">

                    <label for="first_name"> <b> Name </b> </label>
                    <input class="form-control" type="text" name="first_name"  value = "<?php echo $f_name?>">

                    <label for="last_name"> <b> Surname </b></label>
                    <input class="form-control" type="text" name="last_name"  value = "<?php echo $l_name?>">

                    <label for="password"> <b> Password </b></label>
                    <input class="form-control" type="password" name="password" value = "<?php echo $u_pass?>" >

                    <label for="experience"><b> Experience </b></label>
                    <input class="form-control" type="text" name="experience"  value = "<?php echo $exp?>">
                    <br>

                    <input class="btn btn-primary" type="submit" name="submit" value="Save"> </input>
                </div>
                <div class="col-sm-4"></div>
            </div>

        </div>

    </form>

</div>





</body>

