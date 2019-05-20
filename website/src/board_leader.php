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
	
	function insertList()
	{
		global $newListName;
		global $newListDesc;
		global $newListDue;
		global $board_id;
		global $mysqli;
	
		
		$date = date('Y-m-d');
		
		$cred_query = "insert into list(name, description, issue_date,due_date,board_ID) values ('$newListName', '$newListDesc' ,'$date','$newListDue','$board_id')";
		
		
		$flag = mysqli_query($mysqli, $cred_query);
		
		
		if($flag === null ){
			echo '<script language="javascript">';
			echo 'alert("Not able to add List")';
			echo '</script>';
		}else{
			header("Refresh:0");
		}
	}
	
	function insertCard()
	{
		
		$list_id = $_POST['newCardBut'];
		
		global $newCardName;
		global $newCardDesc;
		global $newCardDue;
		global $mysqli;
		
		
		$date = date('Y-m-d');

		/*
		$cred_query = "insert into Card(name, description, issue_date,due_date,status, assigned_ID, list_ID) values ('$newListName', '$newListDesc' ,'$date','$newListDue','NOT STARTED','1','1')";
		
		$flag = mysqli_query($mysqli, $cred_query);
		
		
		if($flag === null ){
			echo '<script language="javascript">';
			echo 'alert("Not able to add Card")';
			echo '</script>';
		}else{
			header("Refresh:0");
		}*/
		
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
	
	
	if(isset($_POST['submitList'])){
		
		$newListName = mysqli_real_escape_string($mysqli,$_POST['listName']);
		$newListDesc = mysqli_real_escape_string($mysqli,$_POST['listDesc']);
		$newListDue = mysqli_real_escape_string($mysqli,$_POST['listDue']);
		
		insertList();

	}
	
	if(isset($_POST['submitCard'])){
		
		$newCardName = mysqli_real_escape_string($mysqli,$_POST['cardName']);
		$newCardDesc = mysqli_real_escape_string($mysqli,$_POST['cardDesc']);
		$newCardDue = mysqli_real_escape_string($mysqli,$_POST['cardDue']);
		
		insertCard();
		
	}
	
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

<script>
function newCard() {
	document.getElementById("cardForm").style.display = "block";
}

function closeNewCard() {
	document.getElementById("cardForm").style.display = "none";
}

function newList() {
	document.getElementById("ListForm").style.display = "block";
}

function closeNewList() {
	document.getElementById("ListForm").style.display = "none";
}

</script>


</head>
<body>


<div class="boardBox">

<h3> Board <?php echo $board_id ?> </h3>

<div class="cornBut">

<button onclick="newList()">New List</button> <center>

</div>

<?php
while ($row = mysqli_fetch_array($result_li))
{
	$l_id = $row['ID'];
	$l_name = $row['name'];
	$l_desc = $row['description'];
	$l_due = $row['due_date'];
	
	echo '<div class="column">';
	echo '<div class="cornBut">';
	
	echo '<button class="newcardBut" type="submit" name = newCardBut value ='. $l_id .' onclick="newCard()">+</button>';
	
	echo '</div>';
	

	echo '<h3>List '. $l_name .'  <a class="btn btn-sm btn-danger" href="#">X</a> </h3>';
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
		echo '<h3>Card  '. $c_name .'  <a class="btn btn-sm btn-danger" href="#">X</a> </h3>';
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

<div class="form-popup" id="cardForm">
<form method="post" action="board_leader.php" class="form-container">
<h1>Create New Card</h1>

<label for="cardName">Card Name</b></label>
<input type="text" placeholder="CardName" name="CardName" required>

<label for="cardDesc"><b>Description</b></label>
<input type="text" placeholder="Enter List Description" name="cardDesc">

<label for="cardDue"><b>Due Date</b></label>
<input type="date" name="cardDue" required>

<button type="submit" name="submitCard" id="submitCard" class="but">Add Card</button>
<button type="button" class="but_cancel"  onclick="closeNewCard()">Close</button>
</form>
</div>

<div class="form-popup" id="ListForm">
<form method="post" action="board_leader.php" class="form-container" >
<h1>Create New List</h1>

        <label for="listName"><b>List Name</b></label>
        <input type="text" placeholder="ListName" name="listName" required>

        <label for="listDesc"><b>Description</b></label>
        <input type="text" placeholder="Enter List Description" name="listDesc">

        <label for="listDue"><b>Due Date</b></label>
        <input type="date" name="listDue" required>


        <button type="submit" name="submitList" id="submitList" class="but">Add List</button>
        <button type="button" class="but_cancel"  onclick="closeNewList()">Close</button>
    </form>
</div>

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
