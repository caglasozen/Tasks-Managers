<?php
	
	if (!isset($_SESSION)){
		session_start();
	}
	
	include "config.php";
	
	function logOut() {
		session_destroy();
		header('Location: form.php');
	}
	
	function goBack() {
		header('Location: index.php');
	}
	
	
	/*
	$user_id = $_SESSION['user_id'];
	$proj_id = $_SESSION['project_id'];
	$board_id = $_SESSION['board_id'];
	*/
	
	$board_id = 1;
	
	/*
	//Fetching project information.
	$query_proj_info = "SELECT * FROM Project WHERE id = 1";
	$result_pr = mysqli_query($mysqli, $query_proj_info);
	$row_pr = mysqli_fetch_assoc($result_pr);
	*/
	
	//Fetching board information.
	$query_board_info = "SELECT * FROM board WHERE id = '" . $board_id . "' ";
	$result_bo = mysqli_query($mysqli, $query_board_info);
	$row_bo = mysqli_fetch_array($result_bo);
	
	//Fetching list information.
	$query_list_info = "SELECT * FROM list WHERE board_ID = '" . $board_id . "'";
	$result_li = mysqli_query($mysqli, $query_list_info);
	
	/*
	if(!isset($user_id)){
		header('Location: form.php');
	}
	 */
	
	
	
	if(array_key_exists('Logout',$_POST)){
		logOut();
	}
	
	if(array_key_exists('GoBack',$_POST)){
		goBack();
	}
	
?>



<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="board_style.css">
<meta name="viewport" content="width=device-width, initial-scale=1">


</head>
<body>


<div class="boardBox">

<h3> Board <?php echo $board_id ?> </h3>


<?php
while ($row = mysqli_fetch_array($result_li))
{
	$l_id = $row['ID'];
	$l_name = $row['name'];
	$l_desc = $row['description'];
	$l_due = $row['due_date'];
	
	echo '<div class="column">';
	

	echo '<h3>List '. $l_name .'  </h3>';
	echo '<div style="overflow: scroll;">';

	echo '<p>'. $l_desc .'</p>';
	echo '</div>';
	echo '<p> <b> Due Date </b>'. $l_due .'</p>';
	
	$query_card_info = "SELECT * FROM card WHERE list_ID = '" . $l_id . "'";
	$result_ca = mysqli_query($mysqli, $query_card_info);
	
	while ($row = mysqli_fetch_array($result_ca))
	{
		$c_id = $row['ID'];
		$c_name = $row['name'];
		$c_desc = $row['description'];
		$c_due = $row['due_date'];
		$c_stat = $row['status'];
		$c_assID = $row['assigned_ID'];

		echo '<div class="card">';
		echo '<h3>Card  '. $c_name .'  </h3>';
		echo '<div style="overflow: scroll;">';
		
		echo '<p>'. $c_desc .'</p>';
		echo '</div>';
		echo '<p> <b> Due Date </b>'. $c_due .'</p>';
		echo '<p> <b> Status </b>'. $c_stat .'</p>';
		echo '<p> <b> Assigned </b>'. $c_assID .'</p>';
		
		echo '</div>';
	}

	
	echo '</div>';
}
?>


<div class="logoutButton">

<form  align="right" method="post">
<input type="submit" name="Logout" id="Logout" value="Log Out" /><br/>
</form>

</div>

<div class="gobackButton">

<form  align="right" method="post">
<input type="submit" name="GoBack" id="GoBack" value="Go Back"  /><br/>
</form>

</div>


</body>
</html>
